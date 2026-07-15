<?php

namespace App\Console\Commands;

use App\Contracts\Services\StellarServiceInterface;
use Illuminate\Console\Command;

class StellarSetupCommand extends Command
{
    protected $signature = 'stellar:setup
        {--generate-keypair : Generate a new Stellar keypair for testing}
        {--deploy-contract : Show deploy instructions for Soroban escrow contract}
        {--check-balance= : Check XLM balance of a wallet address}
        {--fund-testnet= : Fund a testnet account via Friendbot}';

    protected $description = 'Setup Stellar blockchain integration for EduFund';

    public function handle(StellarServiceInterface $stellarService): int
    {
        if ($this->option('generate-keypair')) {
            return $this->generateKeypair();
        }

        if ($this->option('deploy-contract')) {
            return $this->showDeployInstructions();
        }

        if ($this->option('check-balance')) {
            return $this->checkBalance($this->option('check-balance'), $stellarService);
        }

        if ($this->option('fund-testnet')) {
            return $this->fundTestnet($this->option('fund-testnet'));
        }

        $this->outputGuide();

        return self::SUCCESS;
    }

    private function outputGuide(): void
    {
        $this->info('============================================');
        $this->info('   EduFund Stellar Setup Guide');
        $this->info('============================================');
        $this->newLine();

        $this->info('STEP 1: Install Rust');
        $this->warn('   Buka https://rustup.rs/ dan download rustup-init.exe');
        $this->warn('   Install seperti biasa (pilih default)');
        $this->newLine();

        $this->info('STEP 2: Install Soroban CLI');
        $this->warn('   Setelah Rust terinstall, buka terminal baru dan jalankan:');
        $this->line('   cargo install soroban-cli --features opt');
        $this->newLine();

        $this->info('STEP 3: Generate Stellar Testnet Keypair');
        $this->warn('   Jalankan perintah berikut untuk generate keypair:');
        $this->line('   php artisan stellar:setup --generate-keypair');
        $this->newLine();

        $this->info('STEP 4: Fund account with testnet XLM');
        $this->warn('   Dapatkan XLM gratis (10.000 XLM) via Friendbot:');
        $this->line('   php artisan stellar:setup --fund-testnet=G...PUBLIC_KEY...');
        $this->newLine();

        $this->info('STEP 5: Build Smart Contract');
        $this->warn('   Build Rust contract ke WebAssembly:');
        $this->line('   cd contracts/escrow');
        $this->line('   cargo build --target wasm32-unknown-unknown --release');
        $this->newLine();

        $this->info('STEP 6: Deploy Contract ke Soroban Testnet');
        $this->warn('   Deploy hasil build ke Soroban testnet:');
        $this->line('   soroban contract deploy');
        $this->line('     --wasm target/wasm32-unknown-unknown/release/edufund_escrow.wasm');
        $this->line('     --network testnet');
        $this->line('     --source <YOUR_SECRET_SEED>');
        $this->newLine();

        $this->info('STEP 7: Initialize Contract');
        $this->warn('   Inisialisasi contract dengan admin, school, dan goal:');
        $this->line('   soroban contract invoke');
        $this->line('     --id <DEPLOYED_CONTRACT_ID>');
        $this->line('     --network testnet');
        $this->line('     --source <YOUR_SECRET_SEED>');
        $this->line('     --');
        $this->line('     initialize');
        $this->line('     --admin <YOUR_PUBLIC_KEY>');
        $this->line('     --school <SCHOOL_PUBLIC_KEY>');
        $this->line('     --goal_amount <GOAL_IN_STROOPS>');
        $this->newLine();

        $this->info('STEP 8: Update .env file');
        $this->warn('   Masukkan hasil deploy ke file .env:');
        $this->line('   STELLAR_SIGNER_SECRET=S...YOUR_SECRET_SEED...');
        $this->line('   STELLAR_ESCROW_CONTRACT_ID=C...DEPLOYED_CONTRACT_ID...');
        $this->newLine();

        $this->info('STEP 9: Test Integration');
        $this->line('   php artisan stellar:setup --check-balance=G...YOUR_PUBLIC_KEY...');
        $this->newLine();

        $this->info('============================================');
        $this->info('   TANPA setup di atas, EduFund tetap berfungsi');
        $this->info('   menggunakan Stellar Classic (Horizon API).');
        $this->info('   Soroban hanya diperlukan untuk fitur lanjutan.');
        $this->info('============================================');
    }

    private function generateKeypair(): int
    {
        $this->info('Generating new Stellar keypair...');
        $this->newLine();

        try {
            $keypair = \Soneso\StellarSDK\Crypto\KeyPair::random();

            $this->line('Public Key  (G...): ' . $keypair->getAccountId());
            $this->line('Secret Seed (S...): ' . $keypair->getSecretSeed());
            $this->newLine();

            $this->warn('SAVE THESE CREDENTIALS SECURELY!');
            $this->warn('Tambahkan ke .env kamu:');
            $this->line('STELLAR_SIGNER_SECRET=' . $keypair->getSecretSeed());
            $this->newLine();

            $this->info('Untuk mendapatkan XLM gratis testnet, jalankan:');
            $this->line('php artisan stellar:setup --fund-testnet=' . $keypair->getAccountId());

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    private function checkBalance(string $address, StellarServiceInterface $stellarService): int
    {
        $this->info('Checking balance for: ' . $address);
        $this->newLine();

        try {
            $balance = $stellarService->getBalance($address);
            $this->info('Balance: ' . $balance . ' XLM');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    private function fundTestnet(string $address): int
    {
        $this->info('Funding testnet account: ' . $address);
        $this->newLine();

        try {
            // Disable SSL verification for development environment
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->get('https://friendbot.stellar.org?addr=' . $address);

            if ($response->successful()) {
                $this->info('Account funded successfully! You received 10,000 testnet XLM.');
                return self::SUCCESS;
            } else {
                $this->warn('Account mungkin sudah difund sebelumnya.');
                $this->warn('Friendbot hanya bisa funding sekali per account.');
                return self::SUCCESS;
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    private function showDeployInstructions(): int
    {
        $this->warn('Deploy contract harus dilakukan manual melalui Soroban CLI.');
        $this->newLine();
        $this->line('1. Build contract:');
        $this->line('   cd ' . base_path('contracts/escrow'));
        $this->line('   cargo build --target wasm32-unknown-unknown --release');
        $this->newLine();
        $this->line('2. Deploy ke testnet:');
        $this->line('   soroban contract deploy');
        $this->line('     --wasm target/wasm32-unknown-unknown/release/edufund_escrow.wasm');
        $this->line('     --network testnet');
        $this->line('     --source <YOUR_SECRET_SEED>');
        $this->newLine();
        $this->line('3. Initialize contract:');
        $this->line('   soroban contract invoke');
        $this->line('     --id <DEPLOYED_CONTRACT_ID>');
        $this->line('     --network testnet');
        $this->line('     --source <YOUR_SECRET_SEED>');
        $this->line('     --');
        $this->line('     initialize');
        $this->line('     --admin <YOUR_PUBLIC_KEY>');
        $this->line('     --school <SCHOOL_PUBLIC_KEY>');
        $this->line('     --goal_amount <GOAL_IN_STROOPS>');
        $this->newLine();
        $this->info('Note: 1 XLM = 10,000,000 stroops');
        $this->newLine();
        $this->info('Setelah deploy, update .env dengan:');
        $this->line('STELLAR_ESCROW_CONTRACT_ID=<DEPLOYED_CONTRACT_ID>');
        $this->line('STELLAR_SIGNER_SECRET=<YOUR_SECRET_SEED>');

        return self::SUCCESS;
    }
}