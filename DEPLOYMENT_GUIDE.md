# EduFund Soroban Contract Deployment Guide

This guide provides step-by-step instructions for building, deploying, and configuring the EduFund escrow smart contract on the Stellar testnet.

## Prerequisites

- **Rust** (1.70 or later) - [Install Rust](https://www.rust-lang.org/tools/install)
- **Cargo** - Comes with Rust
- **Soroban CLI** - Will be installed automatically
- **Stellar Testnet Account** - Funded with test XLM
- **Laravel 11+** with PHP 8.2+
- **Composer dependencies** installed

## Table of Contents

1. [Install Soroban CLI](#1-install-soroban-cli)
2. [Build the Smart Contract](#2-build-the-smart-contract)
3. [Deploy to Stellar Testnet](#3-deploy-to-stellar-testnet)
4. [Configure Laravel Application](#4-configure-laravel-application)
5. [Test the Integration](#5-test-the-integration)
6. [Production Deployment](#6-production-deployment)

---

## 1. Install Soroban CLI

### Windows

```bash
# Install soroban-cli using cargo
cargo install --locked soroban-cli

# Verify installation
soroban --version
```

### macOS/Linux

```bash
# Install soroban-cli using cargo
cargo install --locked soroban-cli

# Add to PATH (if needed)
echo 'export PATH="$HOME/.cargo/bin:$PATH"' >> ~/.bashrc
source ~/.bashrc

# Verify installation
soroban --version
```

**Expected Output:** `soroban-cli 27.0.0` (or similar version)

---

## 2. Build the Smart Contract

### Navigate to Contract Directory

```bash
cd contracts/escrow
```

### Build the Contract

```bash
# Build the contract in release mode
soroban contract build --release --network testnet

# This will create:
# - target/wasm32-unknown-unknown/release/edufund_escrow.wasm
# - target/soroban_escrow.optimized.wasm
```

### Verify Build

```bash
# Check that the WASM file was created
dir target\wasm32-unknown-unknown\release\
# You should see: edufund_escrow.wasm

# Check optimized version
dir target\
# You should see: soroban_escrow.optimized.wasm
```

### Run Tests (Optional but Recommended)

```bash
# Run contract unit tests
cargo test

# Expected output: test result: ok. 2 passed; 0 failed
```

---

## 3. Deploy to Stellar Testnet

### 3.1 Create/Import Stellar Testnet Account

#### Option A: Create New Account

```bash
# Generate a new keypair
soroban key generate --network testnet --global edufund-admin

# This will output:
# - Secret key (save this securely!)
# - Public key (this is your account ID)

# Fund the account with test XLM
# Visit: https://laboratory.stellar.org/#account-creator?network=public
# Or use the Stellar testnet faucet
```

#### Option B: Use Existing Account

```bash
# Import existing secret key
soroban key import \
  --global edufund-admin \
  --secret-key "SCZANGBA5YHTNYVVV4C3U252E2B6P6X5X3YVZOIP2YLNOO3ON6Q2DASU" \
  --network testnet

# Verify the account
soroban keys address edufund-admin
```

### 3.2 Fund the Account

```bash
# Get test XLM from the faucet
# Visit: https://friendbot.stellar.org/
# Or use the laboratory: https://laboratory.stellar.org/#account-creator?network=public

# Check balance
soroban keys balance edufund-admin --network testnet
```

**Minimum Balance Required:**
- Account creation: ~1.5 XLM
- Contract deployment: ~2 XLM
- Contract operations: ~0.5 XLM
- **Total recommended:** 10 XLM

### 3.3 Deploy the Contract

```bash
# Deploy the contract to testnet
soroban contract deploy \
  --wasm target/soroban_escrow.optimized.wasm \
  --source-account edufund-admin \
  --network testnet

# This will output the contract ID (save this!)
# Example: Contract ID: CABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890
```

**Important:** Save the contract ID - you'll need it for Laravel configuration.

### 3.4 Initialize the Contract

```bash
# Initialize the contract with campaign parameters
soroban contract invoke \
  --id <CONTRACT_ID> \
  --source-account edufund-admin \
  --network testnet \
  -- initialize \
  --admin <ADMIN_PUBLIC_KEY> \
  --school <SCHOOL_PUBLIC_KEY> \
  --goal_amount 1000000000

# Note: goal_amount is in stroops (1 XLM = 10,000,000 stroops)
# 1000 XLM = 10,000,000,000 stroops
```

### 3.5 Verify Deployment

```bash
# Check contract state
soroban contract invoke \
  --id <CONTRACT_ID> \
  --source-account edufund-admin \
  --network testnet \
  -- get_state

# Expected output: Active
```

---

## 4. Configure Laravel Application

### 4.1 Update .env File

Add the following to your `.env` file:

```env
# Stellar Testnet Configuration
STELLAR_NETWORK=testnet
STELLAR_HORIZON_URL=https://horizon-testnet.stellar.org
STELLAR_NETWORK_PASSPHRASE="Test SDF Network ; September 2015"
STELLAR_ESCROW_ACCOUNT=<ESCROW_ACCOUNT_PUBLIC_KEY>
STELLAR_SIGNER_SECRET=<ESCROW_ACCOUNT_SECRET_KEY>

# Soroban Configuration
STELLAR_RPC_URL=https://soroban-testnet.stellar.org
STELLAR_ESCROW_CONTRACT_ID=<DEPLOYED_CONTRACT_ID>

# Optional: Soroban Network Passphrase (if different)
STELLAR_SOROBAN_NETWORK_PASSPHRASE="Test SDF Network ; September 2015"
```

### 4.2 Example Configuration

```env
# Example values (DO NOT use these in production!)
STELLAR_NETWORK=testnet
STELLAR_HORIZON_URL=https://horizon-testnet.stellar.org
STELLAR_NETWORK_PASSPHRASE="Test SDF Network ; September 2015"
STELLAR_ESCROW_ACCOUNT=GDEMOESCROWACCOUNT1234567890123456789012345678901234
STELLAR_SIGNER_SECRET=SCZANGBA5YHTNYVVV4C3U252E2B6P6X5X3YVZOIP2YLNOO3ON6Q2DASU
STELLAR_RPC_URL=https://soroban-testnet.stellar.org
STELLAR_ESCROW_CONTRACT_ID=CABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890
```

### 4.3 Verify Configuration

```bash
# Test the configuration
php artisan stellar:test-connection

# Or manually test via Tinker
php artisan tinker
>>> config('services.stellar')
>>> app(\App\Contracts\Services\StellarServiceInterface::class)->getBalance('G...')
```

---

## 5. Test the Integration

### 5.1 Run Laravel Tests

```bash
# Run Stellar-related tests
php artisan test --filter=Stellar

# Run all feature tests
php artisan test
```

### 5.2 Test Contract Functions

```bash
# Test deposit function
soroban contract invoke \
  --id <CONTRACT_ID> \
  --source-account edufund-admin \
  --network testnet \
  -- deposit \
  --donor <DONOR_PUBLIC_KEY> \
  --amount 100000000

# Test get_total_raised
soroban contract invoke \
  --id <CONTRACT_ID> \
  --source-account edufund-admin \
  --network testnet \
  -- get_total_raised
```

### 5.3 Test Laravel Integration

```bash
# Create a test campaign via Laravel
php artisan db:seed --class=CampaignSeeder

# Verify the campaign has the contract ID
php artisan tinker
>>> App\Models\Campaign::first()->escrow_contract_id
```

---

## 6. Production Deployment

### 6.1 Prepare for Mainnet

1. **Create Mainnet Account**
   ```bash
   soroban key generate --network mainnet --global edufund-main
   ```

2. **Fund with Real XLM**
   - Purchase XLM from an exchange
   - Send to your mainnet account
   - Minimum: 50 XLM recommended

3. **Deploy to Mainnet**
   ```bash
   soroban contract deploy \
     --wasm target/soroban_escrow.optimized.wasm \
     --source-account edufund-main \
     --network mainnet
   ```

4. **Update Laravel Configuration**
   ```env
   STELLAR_NETWORK=mainnet
   STELLAR_HORIZON_URL=https://horizon.stellar.org
   STELLAR_NETWORK_PASSPHRASE="Public Global Stellar Network ; September 2015"
   STELLAR_RPC_URL=https://soroban-rpc.stellar.org
   ```

### 6.2 Security Considerations

- **Never commit secret keys** to version control
- Use environment variables for all sensitive data
- Implement multi-signature for admin operations
- Regular security audits of the smart contract
- Monitor contract events and balances

### 6.3 Monitoring

```bash
# Monitor contract events
soroban contract events \
  --id <CONTRACT_ID> \
  --network testnet \
  --start-ledger 100000 \
  --tail

# Check contract balance
soroban contract invoke \
  --id <CONTRACT_ID> \
  --network testnet \
  -- get_balance
```

---

## Troubleshooting

### Common Issues

#### 1. "soroban: command not found"

**Solution:** Add Cargo bin directory to PATH
```bash
# Windows
$env:Path += ";$env:USERPROFILE\.cargo\bin"

# macOS/Linux
export PATH="$HOME/.cargo/bin:$PATH"
```

#### 2. "Insufficient balance"

**Solution:** Fund your account with more test XLM
```bash
# Check balance
soroban keys balance edufund-admin --network testnet

# Get more from faucet
# https://friendbot.stellar.org/
```

#### 3. "Contract already initialized"

**Solution:** Deploy a new contract instance or use a different contract ID

#### 4. "Invalid network passphrase"

**Solution:** Ensure you're using the correct passphrase for the network
- Testnet: `Test SDF Network ; September 2015`
- Mainnet: `Public Global Stellar Network ; September 2015`

---

## Additional Resources

- [Soroban Documentation](https://soroban.stellar.org/docs)
- [Stellar Laboratory](https://laboratory.stellar.org/)
- [Stellar Expert](https://stellar.expert/explorer/testnet)
- [Soroban Examples](https://github.com/stellar/soroban-examples)

---

## Support

For issues or questions:
- Check the [EduFund documentation](README.md)
- Review [Soroban troubleshooting guide](https://soroban.stellar.org/docs/troubleshooting)
- Open an issue on GitHub

---

**Last Updated:** 2026-07-15
**Contract Version:** 1.0.0
**Soroban SDK Version:** 22.0.0