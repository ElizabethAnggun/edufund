<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class DeploySorobanContract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soroban:deploy
                            {--network=testnet : The network to deploy to (testnet or mainnet)}
                            {--admin-key= : The admin secret key for deployment}
                            {--wasm-path= : Path to the compiled WASM file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy Soroban escrow contract to Stellar network';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $network = $this->option('network');
        $adminKey = $this->option('admin-key') ?? Config::get('services.stellar.soroban_admin_secret_key');
        $wasmPath = $this->option('wasm-path') ?? base_path('contracts/escrow/target/soroban_escrow.optimized.wasm');

        $this->info("🚀 Deploying EduFund Escrow Contract to {$network}...");

        // Validate inputs
        if (empty($adminKey)) {
            $this->error('❌ Admin secret key is required. Provide via --admin-key or SOROBAN_ADMIN_SECRET_KEY env variable.');
            return Command::FAILURE;
        }

        if (!file_exists($wasmPath)) {
            $this->error("❌ WASM file not found at: {$wasmPath}");
            $this->info('💡 Build the contract first using: soroban contract build');
            return Command::FAILURE;
        }

        // Check if soroban CLI is installed
        if (!$this->checkSorobanCli()) {
            return Command::FAILURE;
        }

        // Step 1: Verify admin account
        $this->info('📋 Step 1: Verifying admin account...');
        $adminPublicKey = $this->getPublicKeyFromSecret($adminKey);
        
        if (!$adminPublicKey) {
            $this->error('❌ Failed to derive public key from secret key.');
            return Command::FAILURE;
        }

        $this->info("✅ Admin Public Key: {$adminPublicKey}");

        // Step 2: Check account balance
        $this->info('💰 Step 2: Checking account balance...');
        $balance = $this->getAccountBalance($adminPublicKey, $network);

        if ($balance === null) {
            $this->warn('⚠️  Could not fetch balance. Ensure the account exists and is funded.');
            if (!$this->confirm('Continue anyway?', false)) {
                return Command::FAILURE;
            }
        } else {
            $this->info("✅ Account Balance: {$balance} XLM");
            
            if ($balance < 5) {
                $this->warn("⚠️  Low balance: {$balance} XLM. Recommended minimum: 10 XLM");
                if (!$this->confirm('Continue anyway?', false)) {
                    return Command::FAILURE;
                }
            }
        }

        // Step 3: Deploy contract
        $this->info('📤 Step 3: Deploying contract...');
        $contractId = $this->deployContract($wasmPath, $adminKey, $network);

        if (!$contractId) {
            $this->error('❌ Contract deployment failed.');
            return Command::FAILURE;
        }

        $this->info("✅ Contract deployed successfully!");
        $this->info("📝 Contract ID: {$contractId}");

        // Step 4: Initialize contract
        $this->info('⚙️  Step 4: Initializing contract...');
        
        if (!$this->confirm('Do you want to initialize the contract now?', true)) {
            $this->warn('⚠️  Skipping initialization. Remember to initialize before use.');
            $this->showNextSteps($contractId, $network, false);
            return Command::SUCCESS;
        }

        $goalAmount = $this->ask('Enter goal amount (in XLM)', 1000);
        $schoolPublicKey = $this->ask('Enter school/public key');

        $stroops = (int) ($goalAmount * 10000000);

        if (!$this->initializeContract($contractId, $adminKey, $adminPublicKey, $schoolPublicKey, $stroops, $network)) {
            $this->error('❌ Contract initialization failed.');
            $this->showNextSteps($contractId, $network, false);
            return Command::FAILURE;
        }

        $this->info('✅ Contract initialized successfully!');

        // Step 5: Verify deployment
        $this->info('🔍 Step 5: Verifying deployment...');
        $state = $this->getContractState($contractId, $adminKey, $network);

        if ($state) {
            $this->info("✅ Contract State: {$state}");
        } else {
            $this->warn('⚠️  Could not verify contract state.');
        }

        // Show next steps
        $this->showNextSteps($contractId, $network, true);

        // Log deployment
        Log::info('Soroban contract deployed', [
            'network' => $network,
            'contract_id' => $contractId,
            'admin_public_key' => $adminPublicKey,
            'goal_amount' => $goalAmount,
        ]);

        return Command::SUCCESS;
    }

    /**
     * Check if soroban CLI is installed.
     */
    private function checkSorobanCli(): bool
    {
        $this->info('🔧 Checking soroban CLI...');
        
        exec('soroban --version 2>&1', $output, $returnCode);
        
        if ($returnCode !== 0) {
            $this->error('❌ Soroban CLI is not installed or not in PATH.');
            $this->info('💡 Install with: cargo install --locked soroban-cli');
            return false;
        }

        $version = implode("\n", $output);
        $this->info("✅ Soroban CLI found: {$version}");
        
        return true;
    }

    /**
     * Get public key from secret key.
     */
    private function getPublicKeyFromSecret(string $secretKey): ?string
    {
        exec("soroban keys address {$secretKey} 2>&1", $output, $returnCode);
        
        if ($returnCode !== 0) {
            return null;
        }

        return trim(implode("\n", $output));
    }

    /**
     * Get account balance.
     */
    private function getAccountBalance(string $publicKey, string $network): ?float
    {
        $networkFlag = $network === 'mainnet' ? '--network mainnet' : '--network testnet';
        exec("soroban keys balance {$publicKey} {$networkFlag} 2>&1", $output, $returnCode);
        
        if ($returnCode !== 0) {
            return null;
        }

        $balance = trim(implode("\n", $output));
        
        return is_numeric($balance) ? (float) $balance : null;
    }

    /**
     * Deploy the contract.
     */
    private function deployContract(string $wasmPath, string $adminKey, string $network): ?string
    {
        $networkFlag = $network === 'mainnet' ? '--network mainnet' : '--network testnet';
        $command = "soroban contract deploy --wasm {$wasmPath} --source-account {$adminKey} {$networkFlag} 2>&1";
        
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            $this->error('Deployment failed: ' . implode("\n", $output));
            return null;
        }

        $output = implode("\n", $output);
        
        // Extract contract ID from output
        if (preg_match('/[A-Z0-9]{56}/', $output, $matches)) {
            return $matches[0];
        }

        $this->warn('Could not extract contract ID from output:');
        $this->line($output);
        
        return null;
    }

    /**
     * Initialize the contract.
     */
    private function initializeContract(
        string $contractId,
        string $adminKey,
        string $adminPublicKey,
        string $schoolPublicKey,
        int $goalAmount,
        string $network
    ): bool {
        $networkFlag = $network === 'mainnet' ? '--network mainnet' : '--network testnet';
        $command = "soroban contract invoke --id {$contractId} --source-account {$adminKey} {$networkFlag} -- initialize --admin {$adminPublicKey} --school {$schoolPublicKey} --goal_amount {$goalAmount} 2>&1";
        
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            $this->error('Initialization failed: ' . implode("\n", $output));
            return false;
        }

        return true;
    }

    /**
     * Get contract state.
     */
    private function getContractState(string $contractId, string $adminKey, string $network): ?string
    {
        $networkFlag = $network === 'mainnet' ? '--network mainnet' : '--network testnet';
        $command = "soroban contract invoke --id {$contractId} --source-account {$adminKey} {$networkFlag} -- get_state 2>&1";
        
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            return null;
        }

        return trim(implode("\n", $output));
    }

    /**
     * Show next steps after deployment.
     */
    private function showNextSteps(string $contractId, string $network, bool $initialized): void
    {
        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('📋 Next Steps:');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('');
        
        $this->line("1. Add to your .env file:");
        $this->line("   <fg=green>STELLAR_ESCROW_CONTRACT_ID={$contractId}</>");
        $this->line("   <fg=green>STELLAR_NETWORK={$network}</>");
        $this->line('');
        
        if (!$initialized) {
            $this->line("2. Initialize the contract:");
            $this->line("   <fg=yellow>soroban contract invoke --id {$contractId} --source-account <ADMIN_KEY> --network {$network} -- initialize --admin <ADMIN_PUBLIC_KEY> --school <SCHOOL_PUBLIC_KEY> --goal_amount 1000000000</>");
            $this->line('');
        }
        
        $this->line("3. Test the contract:");
        $this->line("   <fg=yellow>soroban contract invoke --id {$contractId} --source-account <ADMIN_KEY> --network {$network} -- get_state</>");
        $this->line('');
        
        $this->line("4. View on Stellar Expert:");
        $networkUrl = $network === 'mainnet' 
            ? "https://stellar.expert/explorer/public/contract/{$contractId}"
            : "https://stellar.expert/explorer/testnet/contract/{$contractId}";
        $this->line("   <fg=blue>{$networkUrl}</>");
        $this->line('');
        
        $this->info('═══════════════════════════════════════════════════════════');
    }
}