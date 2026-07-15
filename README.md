# EduFund

EduFund is a web-based education crowdfunding platform built with **Laravel 12**. It connects students, schools, and donors to fund educational needs, while leveraging the **Stellar blockchain** (testnet) to provide transparent, verifiable donation transactions through an escrow model.

The platform supports four distinct user roles — **Student**, **School**, **Donor**, and **Admin** — each with its own dashboard and workflows.

---

## Features

### Students
- Create and manage funding requests (with supporting documents and milestones).
- Submit funding requests for school approval.
- Track milestones and submit milestone evidence.
- Manage personal profile, achievements, wallet, and transactions.

### Schools
- Manage school profile (NPSN, headmaster, logo, Stellar wallet address).
- Review, approve, or reject student funding requests.
- View enrolled students and verification history.
- Requires a verified profile before accessing funding workflows.

### Donors
- Browse and search public campaigns.
- Donate to campaigns via Stellar wallet binding and on-chain verification.
- Save/unsave campaigns for later.
- View donation history and wallet balance.

### Admins
- Verify or reject school registrations.
- Monitor and toggle campaign statuses.
- Monitor blockchain transactions and retry failed ones.

### Blockchain Integration (Stellar Classic + Stellar 3.0 / Soroban)
- **Stellar Classic (Horizon API)**: Donations are verified on-chain using transaction hashes and an escrow account. Wallet balances and transaction submissions are handled through the Horizon API.
- **Stellar 3.0 / Soroban Smart Contracts**: Escrow smart contract written in Rust manages campaign funds with automated milestone-based release and donor refund capabilities.
- **Soneso Stellar PHP SDK** v1.10 provides both Horizon and Soroban RPC client capabilities.
- **SorobanService** (`app/Services/SorobanService.php`) implements `StellarServiceInterface` with full SDK integration for balance checks, transaction verification, and smart contract invocation.
- **EduFund Escrow Contract** (`contracts/escrow/`) is a Rust-based Soroban smart contract that handles:
  - `initialize()` - Setup campaign escrow with admin, school, and goal amount
  - `deposit()` - Donor deposits XLM to the contract
  - `add_milestone()` / `verify_milestone()` - Milestone-based fund release
  - `complete_campaign()` / `fail_campaign()` - Campaign lifecycle with automated refunds
  - `get_balance()` / `get_total_raised()` - On-chain balance queries
- **DisbursementService** integrates with Soroban for on-chain fund releases when milestones are verified.
- Fallback to classic Stellar when Soroban contract is not deployed.

---

## Tech Stack

- **PHP** `^8.2`
- **Laravel** `^12.0`
- **Laravel Sanctum** `^4.3` (API token authentication)
- **Spatie Laravel Permission** `^6.25` (roles & permissions)
- **Soneso Stellar PHP SDK** `1.10` (blockchain integration)
- **MySQL** (default database)
- **Vite** + Blade templates (frontend)
- **Database queue** driver

---

## Requirements

- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL (or a compatible database)
- Internet access to the Stellar Horizon testnet (for blockchain features)

---

## Installation

1. **Clone the repository**

   ```bash
   git clone <repository-url> edufund
   cd edufund
   ```

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**

   ```bash
   npm install
   ```

4. **Create environment file**

   ```bash
   cp .env.example .env
   ```

5. **Generate application key**

   ```bash
   php artisan key:generate
   ```

6. **Configure your database** in `.env` (see [Configuration](#configuration)).

7. **Run migrations and seeders**

   ```bash
   php artisan migrate --seed
   ```

8. **Build frontend assets**

   ```bash
   npm run build
   ```

9. **Start the development server**

   ```bash
   composer run dev
   ```

   This runs the web server, queue worker, log watcher, and Vite in parallel.

Alternatively, use the convenience setup script:

```bash
composer run setup
```

---

## Configuration

### Database

Edit `.env` with your database credentials:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edufund
DB_USERNAME=root
DB_PASSWORD=
```

### Stellar Blockchain

The Stellar integration is configured via environment variables (see `.env.example`):

```dotenv
STELLAR_NETWORK=testnet
STELLAR_HORIZON_URL=https://horizon-testnet.stellar.org
STELLAR_NETWORK_PASSPHRASE=Test SDF Network ; September 2015
STELLAR_ESCROW_ACCOUNT=GDEMOESCROWACCOUNT1234567890123456789012345678901234
STELLAR_RPC_URL=https://soroban-testnet.stellar.org
```

> **Note:** The escrow account and network values above are demo defaults. Replace them with your own Stellar testnet account for production-like testing.

---

## Roles & Permissions

Roles are managed with `spatie/laravel-permission`. The four roles are:

| Role     | Description                                              |
|----------|----------------------------------------------------------|
| `student`| Creates and tracks funding requests and milestones.      |
| `school` | Approves student requests; requires verification.        |
| `donor`  | Browses campaigns and donates via Stellar.               |
| `admin`  | Verifies schools and monitors campaigns/transactions.    |

Role-based access is enforced through the `role` middleware (e.g. `role:student`, `role:donor`) and authorization policies such as [`app/Policies/FundingRequestPolicy.php`](app/Policies/FundingRequestPolicy.php).

---

## Architecture

The application follows a **service-oriented** architecture with clear separation of concerns:

- **Contracts** (`app/Contracts/Services/`) — Interfaces defining service behavior (e.g. `StellarServiceInterface`, `DonationServiceInterface`).
- **Services** (`app/Services/`) — Concrete implementations of business logic.
- **Enums** (`app/Enums/`) — Type-safe constants for statuses, categories, roles, etc.
- **Models** (`app/Models/`) — Eloquent models for all domain entities.
- **HTTP Controllers / Requests / Middleware** — Request handling, validation, and access control.
- **Policies** — Authorization logic for sensitive operations.

### Key Domain Models

`User`, `School`, `StudentProfile`, `FundingRequest`, `Campaign`, `Milestone`, `MilestoneSubmission`, `Donation`, `BlockchainTransaction`, `SupportingDocument`, `Achievement`, `Verification`.

---

## Project Structure

```
app/
├── Contracts/Services/   # Service interfaces
├── Enums/                # Type-safe enums
├── Http/
│   ├── Controllers/      # Role-based controllers (Admin, Donor, School, Student)
│   ├── Middleware/       # Role & profile guards
│   └── Requests/         # Form request validation
├── Models/               # Eloquent models
├── Policies/             # Authorization policies
├── Providers/            # Service providers
└── Services/             # Business logic implementations
database/
├── migrations/           # Database schema
├── seeders/              # Demo data seeders
└── factories/            # Model factories
resources/
├── views/                # Blade templates (auth, admin, donor, school, student)
├── css/                  # Stylesheets
└── js/                   # Frontend scripts
routes/
├── web.php               # Entry / home / about
├── auth.php              # Login, register, password reset
├── student.php           # Student routes
├── school.php            # School routes
├── donor.php             # Donor routes
└── admin.php             # Admin routes
```

---

## Database Seeders

Seeders populate demo data for each role:

- `RoleSeeder` / `PermissionSeeder` — Roles and permissions.
- `AdminSeeder`, `SchoolSeeder`, `StudentSeeder`, `DonorSeeder` — Demo users.
- `CampaignSeeder`, `FundingRequestSeeder`, `DonationSeeder`, `MilestoneSeeder` — Demo content.

Run all seeders with:

```bash
php artisan db:seed
```

---

## Testing

Run the test suite with:

```bash
composer run test
```

or directly:

```bash
php artisan test
```

---

## Code Style

Code style is enforced with [Laravel Pint](https://github.com/laravel/pint):

```bash
./vendor/bin/pint
```

---

## License

EduFund is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
