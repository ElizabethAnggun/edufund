# EduFund Soroban Smart Contract Integration

This document provides an overview of the Soroban smart contract integration for the EduFund platform.

## Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│                    EduFund Laravel Application               │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │
│  │   Donor      │  │   School     │  │   Student    │    │
│  │   Portal     │  │   Portal     │  │   Portal     │    │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘    │
│         │                 │                 │              │
│         └─────────────────┼─────────────────┘              │
│                           │                                │
│  ┌────────────────────────▼────────────────────────┐      │
│  │         Laravel Services Layer                   │      │
│  │  ┌──────────────────────────────────────────┐   │      │
│  │  │   SorobanService / StellarService         │   │      │
│  │  │   - Contract deployment                   │   │      │
│  │  │   - Fund deposits                         │   │      │
│  │  │   - Milestone verification                │   │      │
│  │  │   - Fund releases                         │   │      │
│  │  │   - Refunds                               │   │      │
│  │  └──────────────────────────────────────────┘   │      │
│  └────────────────────────┬────────────────────────┘      │
│                           │                                │
└───────────────────────────┼────────────────────────────────┘
                            │
                            │ HTTP/RPC
                            │
┌───────────────────────────▼────────────────────────────────┐
│              Stellar Testnet / Mainnet                      │
│  ┌────────────────────────────────────────────────────┐   │
│  │         Soroban Smart Contract (Rust/WASM)          │   │
│  │  ┌──────────────────────────────────────────────┐  │   │
│  │  │  EduFundEscrow Contract                      │  │   │
│  │  │  - initialize()                              │  │   │
│  │  │  - deposit()                                 │  │   │
│  │  │  - add_milestone()                           │  │   │
│  │  │  - verify_milestone()                        │  │   │
│  │  │  - complete_campaign()                       │  │   │
│  │  │  - fail_campaign()                           │  │   │
│  │  │  - get_balance()                             │  │   │
│  │  └──────────────────────────────────────────────┘  │   │
│  └────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

## Smart Contract Functions

### 1. `initialize(admin, school, goal_amount)`
Initializes the escrow contract for a campaign.
- **Who can call:** Once only (during setup)
- **Parameters:**
  - `admin`: Campaign administrator's public key
  - `school`: School/recipient's public key
  - `goal_amount`: Funding goal in stroops (1 XLM = 10,000,000 stroops)

### 2. `deposit(donor, amount)`
Donor deposits XLM to the escrow contract.
- **Who can call:** Any donor
- **Parameters:**
  - `donor`: Donor's public key
  - `amount`: Amount in stroops
- **Effects:** Tracks donation, updates campaign total

### 3. `add_milestone(admin, amount, recipient)`
Adds a milestone to the campaign.
- **Who can call:** Admin only
- **Parameters:**
  - `amount`: Milestone amount in stroops
  - `recipient`: Recipient's public key

### 4. `verify_milestone(admin, milestone_id)`
Verifies a milestone and releases funds to the recipient.
- **Who can call:** Admin only
- **Parameters:**
  - `milestone_id`: ID of the milestone to verify
- **Effects:** Transfers funds from escrow to recipient

### 5. `complete_campaign(admin)`
Completes the campaign and releases remaining funds to the school.
- **Who can call:** Admin only
- **Effects:** Releases remaining balance to school

### 6. `fail_campaign(admin)`
Fails the campaign and refunds all donors.
- **Who can call:** Admin only
- **Effects:** Refunds all donations to donors

### 7. `get_balance()`
Returns the contract's current XLM balance.
- **Who can call:** Anyone (read-only)

### 8. `get_total_raised()`
Returns the total amount raised for the campaign.
- **Who can call:** Anyone (read-only)

## Laravel Service Integration

### SorobanService

The `SorobanService` provides the interface between Laravel and the Soroban smart contract.

**Key Methods:**
- `createEscrowAccount(Campaign $campaign)`: Assigns contract ID to campaign
- `depositToEscrow(Campaign $campaign, string $donorPublicKey, float $amount)`: Records deposit
- `releaseFunds(Campaign $campaign, Milestone $milestone, string $recipientAddress)`: Releases milestone funds
- `refundDonors(Campaign $campaign)`: Refunds all donors
- `getEscrowBalance(Campaign $campaign)`: Checks contract balance

### StellarService

The `StellarService` provides classic Stellar (Horizon API) integration for:
- Balance checking
- Transaction verification
- Transaction submission

## Data Flow

### Donation Flow
```
1. Donor initiates donation on Laravel frontend
2. Laravel creates Donation record (pending status)
3. Donor sends XLM to escrow account via Stellar wallet
4. Laravel verifies transaction via Horizon API
5. Laravel calls Soroban contract deposit() function
6. Contract updates campaign total and tracks donation
7. Laravel updates Donation status to completed
```

### Milestone Release Flow
```
1. School completes milestone
2. Admin verifies milestone completion
3. Laravel calls Soroban contract verify_milestone()
4. Contract transfers funds to school/student
5. Contract marks milestone as verified
6. Laravel creates Disbursement record
7. All parties notified
```

### Refund Flow
```
1. Campaign fails or is cancelled
2. Admin calls fail_campaign() on contract
3. Contract iterates through all donations
4. Contract transfers funds back to each donor
5. Laravel updates all Donation statuses to refunded
```

## Configuration

### Environment Variables

```env
# Stellar Network
STELLAR_NETWORK=testnet
STELLAR_HORIZON_URL=https://horizon-testnet.stellar.org
STELLAR_NETWORK_PASSPHRASE="Test SDF Network ; September 2015"

# Escrow Account (Classic Stellar)
STELLAR_ESCROW_ACCOUNT=
STELLAR_SIGNER_SECRET=

# Soroban Configuration
STELLAR_RPC_URL=https://soroban-testnet.stellar.org
STELLAR_ESCROW_CONTRACT_ID=

# Soroban Admin
SOROBAN_ADMIN_SECRET_KEY=
SOROBAN_ADMIN_PUBLIC_KEY=
```

## Database Schema

### Campaign Model
```php
protected $fillable = [
    // ... other fields
    'escrow_contract_id',  // Soroban contract ID
];
```

### Donation Model
```php
protected $fillable = [
    // ... other fields
    'stellar_tx_hash',     // Stellar transaction hash
    'status',              // pending, completed, refunded
];
```

### Milestone Model
```php
protected $fillable = [
    // ... other fields
    'verified',            // Verification status
    'disbursed_at',        // When funds were released
];
```

## Testing

### Unit Tests
```bash
# Run contract unit tests
cd contracts/escrow
cargo test
```

### Integration Tests
```bash
# Run Laravel feature tests
php artisan test --filter=DonationFlowTest
php artisan test --filter=MilestoneFlowTest
php artisan test --filter=DisbursementFlowTest
```

### Manual Testing
```bash
# Deploy contract
php artisan soroban:deploy

# Test contract functions
soroban contract invoke --id <CONTRACT_ID> --network testnet -- get_state
soroban contract invoke --id <CONTRACT_ID> --network testnet -- get_total_raised
```

## Security Considerations

1. **Private Keys**: Never commit secret keys to version control
2. **Multi-sig**: Consider multi-signature for admin operations
3. **Access Control**: Admin functions restricted to campaign admin only
4. **Input Validation**: All amounts validated on-chain
5. **Reentrancy**: Contract state updated before transfers

## Monitoring

### Contract Events
Monitor contract events using Soroban RPC:
```bash
soroban contract events --id <CONTRACT_ID> --network testnet --tail
```

### Laravel Logs
Check Laravel logs for Soroban operations:
```bash
tail -f storage/logs/laravel.log | grep -i soroban
```

### Stellar Explorer
View contract on Stellar Expert:
- Testnet: https://stellar.expert/explorer/testnet/contract/<CONTRACT_ID>
- Mainnet: https://stellar.expert/explorer/public/contract/<CONTRACT_ID>

## Troubleshooting

### Common Issues

1. **"Contract not initialized"**
   - Solution: Run `soroban contract invoke --id <ID> -- initialize ...`

2. **"Insufficient balance"**
   - Solution: Fund admin account with more XLM

3. **"Only admin can call this function"**
   - Solution: Ensure you're using the correct admin keypair

4. **"Milestone already verified"**
   - Solution: Each milestone can only be verified once

## Performance Considerations

- **Gas Costs**: Each contract operation consumes XLM for gas
- **Transaction Speed**: Soroban transactions typically confirm in 5-10 seconds
- **Batch Operations**: Consider batching multiple operations when possible
- **Caching**: Cache contract state reads to reduce RPC calls

## Future Enhancements

1. **Multi-campaign Support**: Deploy separate contract instances per campaign
2. **Token Support**: Support for custom Stellar assets (not just XLM)
3. **Time-locks**: Add time-based release conditions
4. **Dispute Resolution**: Add arbitration mechanism
5. **Analytics**: Track campaign performance metrics

## Resources

- [Soroban Documentation](https://soroban.stellar.org/docs)
- [Soroban SDK Rust Docs](https://docs.rs/soroban-sdk/latest/soroban_sdk/)
- [Stellar Laboratory](https://laboratory.stellar.org/)
- [Stellar Expert Explorer](https://stellar.expert/)

---

**Version:** 1.0.0  
**Last Updated:** 2026-07-15  
**Contract SDK:** soroban-sdk 22.0.0