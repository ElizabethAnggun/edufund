#![no_std]
use soroban_sdk::{contract, contractimpl, contracttype, Address, Env, String, Vec, Map, symbol_short, Symbol, token, TokenClient};

/// EduFund Escrow Smart Contract
/// 
/// This contract manages the escrow of funds for educational campaigns.
/// Donors deposit XLM, and funds are released when milestones are verified.
/// If a campaign fails, donors can be refunded.

#[contracttype]
#[derive(Clone, Debug, Eq, PartialEq)]
pub enum CampaignState {
    Active,
    Completed,
    Failed,
}

#[contracttype]
#[derive(Clone, Debug, Eq, PartialEq)]
pub struct Milestone {
    pub id: u64,
    pub amount: i128,
    pub recipient: Address,
    pub verified: bool,
}

#[contracttype]
#[derive(Clone, Debug, Eq, PartialEq)]
pub struct Campaign {
    pub admin: Address,
    pub school: Address,
    pub state: CampaignState,
    pub total_donated: i128,
    pub total_released: i128,
    pub goal_amount: i128,
}

#[contracttype]
#[derive(Clone)]
pub struct Donation {
    pub donor: Address,
    pub amount: i128,
}

#[contracttype]
pub enum DataKey {
    Campaign,
    Donations,
    Milestones,
    MilestoneCount,
    DonorCount,
}

fn put_campaign(env: &Env, campaign: &Campaign) {
    env.storage().instance().set(&DataKey::Campaign, campaign);
}

fn get_campaign(env: &Env) -> Campaign {
    env.storage().instance().get(&DataKey::Campaign).unwrap()
}

fn put_donations(env: &Env, donations: &Vec<Donation>) {
    env.storage().instance().set(&DataKey::Donations, donations);
}

fn get_donations(env: &Env) -> Vec<Donation> {
    env.storage().instance().get(&DataKey::Donations).unwrap_or(Vec::new(env))
}

fn put_milestones(env: &Env, milestones: &Vec<Milestone>) {
    env.storage().instance().set(&DataKey::Milestones, milestones);
}

fn get_milestones(env: &Env) -> Vec<Milestone> {
    env.storage().instance().get(&DataKey::Milestones).unwrap_or(Vec::new(env))
}

#[contract]
pub struct EduFundEscrow;

#[contractimpl]
impl EduFundEscrow {

    /// Initialize the escrow contract for a campaign
    /// Sets the campaign admin, school, and goal amount
    pub fn initialize(env: Env, admin: Address, school: Address, goal_amount: i128) {
        // Only allow initialization once
        assert!(
            !env.storage().instance().has(&DataKey::Campaign),
            "already initialized"
        );

        admin.require_auth();

        let campaign = Campaign {
            admin: admin.clone(),
            school,
            state: CampaignState::Active,
            total_donated: 0,
            total_released: 0,
            goal_amount,
        };

        put_campaign(&env, &campaign);
    }

    /// Get the current campaign state
    pub fn get_state(env: Env) -> CampaignState {
        let campaign = get_campaign(&env);
        campaign.state
    }

    /// Get campaign details
    pub fn get_campaign_details(env: Env) -> Campaign {
        get_campaign(&env)
    }

    /// Donor deposits XLM to the escrow contract
    /// The donor sends XLM and the contract tracks the donation
    pub fn deposit(env: Env, donor: Address, amount: i128) {
        donor.require_auth();

        let mut campaign = get_campaign(&env);
        assert!(campaign.state == CampaignState::Active, "campaign is not active");

        // Transfer XLM from donor to this contract
        let token_address = env.current_contract_address();
        let token = token::Client::new(&env, &token_address);
        
        // Track the donation
        let mut donations = get_donations(&env);
        donations.push_back(Donation {
            donor: donor.clone(),
            amount,
        });
        put_donations(&env, &donations);

        // Update campaign total
        campaign.total_donated += amount;
        put_campaign(&env, &campaign);
    }

    /// Add a milestone to the campaign
    /// Only the admin can add milestones
    pub fn add_milestone(env: Env, admin: Address, amount: i128, recipient: Address) {
        admin.require_auth();

        let mut campaign = get_campaign(&env);
        assert!(campaign.admin == admin, "only admin can add milestones");
        assert!(campaign.state == CampaignState::Active, "campaign is not active");

        let mut milestones = get_milestones(&env);
        let count = milestones.len() as u64;

        milestones.push_back(Milestone {
            id: count,
            amount,
            recipient: recipient.clone(),
            verified: false,
        });

        put_milestones(&env, &milestones);

        // Verify total milestones don't exceed goal
        let total_milestone_amount: i128 = milestones.iter().map(|m| m.amount).sum();
        assert!(total_milestone_amount <= campaign.goal_amount, "milestone total exceeds goal");
    }

    /// Verify a milestone and release funds
    /// The admin verifies that a milestone has been achieved
    pub fn verify_milestone(env: Env, admin: Address, milestone_id: u64) {
        admin.require_auth();

        let mut campaign = get_campaign(&env);
        assert!(campaign.admin == admin, "only admin can verify milestones");
        assert!(campaign.state == CampaignState::Active, "campaign is not active");

        let mut milestones = get_milestones(&env);
        let mut milestone = milestones.get(milestone_id).unwrap();
        assert!(!milestone.verified, "milestone already verified");

        // Mark milestone as verified
        milestone.verified = true;
        milestones.set(milestone_id, milestone.clone());
        put_milestones(&env, &milestones);

        // Transfer funds to the recipient (school/student)
        let token = token::Client::new(&env, &env.current_contract_address());
        token.transfer(&campaign.admin, &milestone.recipient, &milestone.amount);

        // Update campaign
        campaign.total_released += milestone.amount;
        put_campaign(&env, &campaign);
    }

    /// Get all milestones
    pub fn get_milestones_list(env: Env) -> Vec<Milestone> {
        get_milestones(&env)
    }

    /// Get all donations
    pub fn get_donations_list(env: Env) -> Vec<Donation> {
        get_donations(&env)
    }

    /// Complete the campaign successfully
    /// Releases any remaining funds to the school
    pub fn complete_campaign(env: Env, admin: Address) {
        admin.require_auth();

        let mut campaign = get_campaign(&env);
        assert!(campaign.admin == admin, "only admin can complete");
        assert!(campaign.state == CampaignState::Active, "already completed or failed");

        campaign.state = CampaignState::Completed;
        put_campaign(&env, &campaign);

        // Release remaining funds to school
        let remaining = campaign.total_donated - campaign.total_released;
        if remaining > 0 {
            let token = token::Client::new(&env, &env.current_contract_address());
            token.transfer(&admin, &campaign.school, &remaining);
            campaign.total_released += remaining;
            put_campaign(&env, &campaign);
        }
    }

    /// Fail the campaign and refund all donors
    pub fn fail_campaign(env: Env, admin: Address) {
        admin.require_auth();

        let mut campaign = get_campaign(&env);
        assert!(campaign.admin == admin, "only admin can fail campaign");
        assert!(campaign.state == CampaignState::Active, "already completed or failed");

        campaign.state = CampaignState::Failed;
        put_campaign(&env, &campaign);

        // Refund all donors
        let donations = get_donations(&env);
        let token = token::Client::new(&env, &env.current_contract_address());

        for donation in donations.iter() {
            token.transfer(&admin, &donation.donor, &donation.amount);
        }
    }

    /// Get the contract's XLM balance
    pub fn get_balance(env: Env) -> i128 {
        let token = token::Client::new(&env, &env.current_contract_address());
        token.balance(&env.current_contract_address())
    }

    /// Get the total amount raised so far
    pub fn get_total_raised(env: Env) -> i128 {
        let campaign = get_campaign(&env);
        campaign.total_donated
    }
}

#[cfg(test)]
mod tests {
    use super::*;
    use soroban_sdk::{Env, Address, vec, symbol_short};

    #[test]
    fn test_initialize() {
        let env = Env::default();
        let contract_id = env.register_contract(None, EduFundEscrow);
        let client = EduFundEscrowClient::new(&env, &contract_id);

        let admin = Address::generate(&env);
        let school = Address::generate(&env);

        client.initialize(&admin, &school, &1000i128);

        let campaign = client.get_campaign_details();
        assert_eq!(campaign.admin, admin);
        assert_eq!(campaign.school, school);
        assert_eq!(campaign.goal_amount, 1000);
        assert_eq!(campaign.state, CampaignState::Active);
    }

    #[test]
    fn test_add_and_verify_milestone() {
        let env = Env::default();
        let contract_id = env.register_contract(None, EduFundEscrow);
        let client = EduFundEscrowClient::new(&env, &contract_id);

        let admin = Address::generate(&env);
        let school = Address::generate(&env);
        let recipient = Address::generate(&env);

        client.initialize(&admin, &school, &1000i128);
        client.add_milestone(&admin, &500i128, &recipient);

        let milestones = client.get_milestones_list();
        assert_eq!(milestones.len(), 1);
        assert_eq!(milestones.get(0).unwrap().amount, 500);
    }
}