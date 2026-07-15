<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'stellar' => [
        'horizon_url' => env('STELLAR_HORIZON_URL', 'https://horizon-testnet.stellar.org'),
        'network_passphrase' => env('STELLAR_NETWORK_PASSPHRASE', 'Test SDF Network ; September 2015'),
        'escrow_account' => env('STELLAR_ESCROW_ACCOUNT', ''),
        'signer_secret' => env('STELLAR_SIGNER_SECRET', ''),

        // Soroban / Stellar 3.0 RPC Configuration
        'rpc_url' => env('STELLAR_RPC_URL', 'https://soroban-testnet.stellar.org'),
        'soroban_network_passphrase' => env('STELLAR_NETWORK_PASSPHRASE', 'Test SDF Network ; September 2015'),

        // Escrow Smart Contract (deployed on Soroban)
        'escrow_contract_id' => env('STELLAR_ESCROW_CONTRACT_ID', ''),

        // Soroban Admin Account (for contract deployment and management)
        'soroban_admin_secret_key' => env('SOROBAN_ADMIN_SECRET_KEY', ''),
        'soroban_admin_public_key' => env('SOROBAN_ADMIN_PUBLIC_KEY', ''),
    ],

];