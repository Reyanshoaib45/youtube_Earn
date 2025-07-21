<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Package;
use App\Models\Video;
use App\Models\Referral;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'Reyanshoaib@gmail.com',
            'password' => Hash::make('Reyan123p098'),
            'role' => 'admin',
            'referral_code' => 'ADMIN001',
            'total_earnings' => 0,
        ]);

        // Create Manager User
        // $manager = User::create([
        //     'name' => 'Platform Manager',
        //     'email' => 'manager@watchearn.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'manager',
        //     'referral_code' => 'MANAGER1',
        //     'total_earnings' => 0,
        // ]);

        // Create Test Users with Referral System
        // $testUser = User::create([
        //     'name' => 'Test User',
        //     'email' => 'user@test.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'user',
        //     'referral_code' => 'TESTUSER',
        //     'total_earnings' => 1500.00,
        // ]);

        // Create 5 Realistic Subscription Packages with Proper Calculations
        $packages = [
            [
                'name' => '🚀 Starter Package',
                'price' => 1000.00,
                'duration_days' => 7,
                'daily_video_limit' => 5,
                'total_reward' => 2100.00, // 5 videos × 7 days × Rs.60 = Rs.2100
                'description' => 'Perfect for beginners - 110% profit in just 1 week!',
                'is_active' => true,
                // Calculation: 5 × 7 × 60 = 2100
                // Profit: 2100 - 1000 = 1100 (110% ROI)
            ],
            [
                'name' => '💎 Basic Package',
                'price' => 2000.00,
                'duration_days' => 10,
                'daily_video_limit' => 8,
                'total_reward' => 4800.00, // 8 videos × 10 days × Rs.60 = Rs.4800
                'description' => 'Great value - 140% profit in 10 days!',
                'is_active' => true,
                // Calculation: 8 × 10 × 60 = 4800
                // Profit: 4800 - 2000 = 2800 (140% ROI)
            ],
            [
                'name' => '⭐ Premium Package',
                'price' => 3000.00,
                'duration_days' => 14,
                'daily_video_limit' => 10,
                'total_reward' => 8400.00, // 10 videos × 14 days × Rs.60 = Rs.8400
                'description' => 'Most popular - 180% profit in 2 weeks!',
                'is_active' => true,
                // Calculation: 10 × 14 × 60 = 8400
                // Profit: 8400 - 3000 = 5400 (180% ROI)
            ],
            [
                'name' => '🏆 Gold Package',
                'price' => 5000.00,
                'duration_days' => 21,
                'daily_video_limit' => 12,
                'total_reward' => 15120.00, // 12 videos × 21 days × Rs.60 = Rs.15120
                'description' => 'High earner - 202% profit in 3 weeks!',
                'is_active' => true,
                // Calculation: 12 × 21 × 60 = 15120
                // Profit: 15120 - 5000 = 10120 (202% ROI)
            ],
            [
                'name' => '💰 Diamond Package',
                'price' => 8000.00,
                'duration_days' => 30,
                'daily_video_limit' => 15,
                'total_reward' => 27000.00, // 15 videos × 30 days × Rs.60 = Rs.27000
                'description' => 'Maximum profit - 237% return in 1 month!',
                'is_active' => true,
                // Calculation: 15 × 30 × 60 = 27000
                // Profit: 27000 - 8000 = 19000 (237% ROI)
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }

        // Create Sample Videos with Realistic Content
        $videos = [
            [
                'title' => 'How to Make Money Online in Pakistan 2024 - Complete Guide',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'reward' => 60.00,
                'duration' => 300, // 5 minutes
                'is_active' => true,
            ],
            [
                'title' => 'Digital Marketing Masterclass - Earn Rs.50,000 Monthly',
                'youtube_url' => 'https://www.youtube.com/watch?v=9bZkp7q19f0',
                'reward' => 60.00,
                'duration' => 420, // 7 minutes
                'is_active' => true,
            ],
            [
                'title' => 'Cryptocurrency Trading for Beginners - Pakistan Guide',
                'youtube_url' => 'https://www.youtube.com/watch?v=fJ9rUzIMcZQ',
                'reward' => 60.00,
                'duration' => 360, // 6 minutes
                'is_active' => true,
            ],
            [
                'title' => 'Freelancing Success Tips - Upwork & Fiverr Strategies',
                'youtube_url' => 'https://www.youtube.com/watch?v=QH2-TGUlwu4',
                'reward' => 60.00,
                'duration' => 480, // 8 minutes
                'is_active' => true,
            ],
            [
                'title' => 'YouTube Channel Growth Hacks - Get 1M Subscribers',
                'youtube_url' => 'https://www.youtube.com/watch?v=nfWlot6h_JM',
                'reward' => 60.00,
                'duration' => 540, // 9 minutes
                'is_active' => true,
            ],
            [
                'title' => 'E-commerce Business Setup - Shopify & Daraz Guide',
                'youtube_url' => 'https://www.youtube.com/watch?v=y6120QOlsfU',
                'reward' => 60.00,
                'duration' => 600, // 10 minutes
                'is_active' => true,
            ],
            [
                'title' => 'Social Media Marketing - Instagram & Facebook Ads',
                'youtube_url' => 'https://www.youtube.com/watch?v=kJQP7kiw5Fk',
                'reward' => 60.00,
                'duration' => 450, // 7.5 minutes
                'is_active' => true,
            ],
            [
                'title' => 'Passive Income Ideas That Actually Work in 2024',
                'youtube_url' => 'https://www.youtube.com/watch?v=lAhg_P_6GQI',
                'reward' => 60.00,
                'duration' => 390, // 6.5 minutes
                'is_active' => true,
            ],
            [
                'title' => 'Online Business Success Stories - Pakistani Entrepreneurs',
                'youtube_url' => 'https://www.youtube.com/watch?v=Pa-7a2gLDjI',
                'reward' => 60.00,
                'duration' => 330, // 5.5 minutes
                'is_active' => true,
            ],
            [
                'title' => 'Investment Strategies for Young Entrepreneurs',
                'youtube_url' => 'https://www.youtube.com/watch?v=L_jWHffIx5E',
                'reward' => 60.00,
                'duration' => 510, // 8.5 minutes
                'is_active' => true,
            ],
            [
                'title' => 'Affiliate Marketing Complete Course - Earn Daily',
                'youtube_url' => 'https://www.youtube.com/watch?v=abc123def456',
                'reward' => 60.00,
                'duration' => 420, // 7 minutes
                'is_active' => true,
            ],
            [
                'title' => 'Content Creation Tips - Viral Video Strategies',
                'youtube_url' => 'https://www.youtube.com/watch?v=xyz789uvw012',
                'reward' => 60.00,
                'duration' => 380, // 6.3 minutes
                'is_active' => true,
            ],
        ];

        foreach ($videos as $video) {
            Video::create($video);
        }

        // // Create Comprehensive Referral System Test Data
        // $referralUsers = [];
        
        // // Create 5 users who were referred by test user
        // for ($i = 1; $i <= 5; $i++) {
        //     $referredUser = User::create([
        //         'name' => "Referred User $i",
        //         'email' => "referred$i@test.com",
        //         'password' => Hash::make('password'),
        //         'role' => 'user',
        //         'referral_code' => "REF00$i" . strtoupper(substr(md5(uniqid()), 0, 4)),
        //         'total_earnings' => rand(100, 1000),
        //     ]);

        //     // Create referral record with proper reward
        //     Referral::create([
        //         'referrer_id' => $testUser->id,
        //         'referred_id' => $referredUser->id,
        //         'reward' => 50.00, // Rs.50 per referral
        //     ]);

        //     $referralUsers[] = $referredUser;
        // }

        // // Create multi-level referral system (referred users referring others)
        // foreach ($referralUsers as $index => $referrer) {
        //     if ($index < 3) { // Only first 3 users refer others
        //         for ($j = 1; $j <= 2; $j++) {
        //             $subReferredUser = User::create([
        //                 'name' => "Sub Referred User {$index}-{$j}",
        //                 'email' => "subreferred{$index}{$j}@test.com",
        //                 'password' => Hash::make('password'),
        //                 'role' => 'user',
        //                 'referral_code' => "SUB{$index}{$j}" . strtoupper(substr(md5(uniqid()), 0, 4)),
        //                 'total_earnings' => rand(50, 500),
        //             ]);

        //             // Create referral record
        //             Referral::create([
        //                 'referrer_id' => $referrer->id,
        //                 'referred_id' => $subReferredUser->id,
        //                 'reward' => 50.00,
        //             ]);

        //             // Update referrer's earnings
        //             $referrer->increment('total_earnings', 50);
        //         }
        //     }
        // }

        // // Update test user's earnings with referral rewards
        // $totalReferralReward = $testUser->referrals()->sum('reward');
        // $testUser->increment('total_earnings', $totalReferralReward);

        // // Create additional sample users for realistic platform stats
        // for ($i = 1; $i <= 20; $i++) {
        //     $user = User::create([
        //         'name' => "Sample User $i",
        //         'email' => "sample$i@example.com",
        //         'password' => Hash::make('password'),
        //         'role' => 'user',
        //         'referral_code' => "SAMPLE$i" . strtoupper(substr(md5(uniqid()), 0, 4)),
        //         'total_earnings' => rand(0, 2000),
        //     ]);

        //     // Some users refer others randomly
        //     if (rand(1, 3) == 1) { // 33% chance
        //         $referredCount = rand(1, 3);
        //         for ($j = 1; $j <= $referredCount; $j++) {
        //             $referred = User::create([
        //                 'name' => "Random Referred $i-$j",
        //                 'email' => "randomref{$i}{$j}@example.com",
        //                 'password' => Hash::make('password'),
        //                 'role' => 'user',
        //                 'referral_code' => "RND{$i}{$j}" . strtoupper(substr(md5(uniqid()), 0, 4)),
        //                 'total_earnings' => rand(0, 1000),
        //             ]);

        //             Referral::create([
        //                 'referrer_id' => $user->id,
        //                 'referred_id' => $referred->id,
        //                 'reward' => 50.00,
        //             ]);

        //             $user->increment('total_earnings', 50);
        //         }
        //     }
        // }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('📊 Created:');
        $this->command->info('   • 1 Admin (admin@watchearn.com / password)');
        $this->command->info('   • 1 Manager (manager@watchearn.com / password)');
        $this->command->info('   • 1 Test User (user@test.com / password) with 5 referrals');
        $this->command->info('   • 5 Realistic packages with proper ROI calculations');
        $this->command->info('   • 12 Sample videos for earning');
        $this->command->info('   • 20+ Additional users with referral network');
        $this->command->info('   • Multi-level referral system demonstration');
        $this->command->info('');
        $this->command->info('💰 Package Breakdown:');
        $this->command->info('   • Starter: Rs.1,000 → Rs.2,100 (110% profit)');
        $this->command->info('   • Basic: Rs.2,000 → Rs.4,800 (140% profit)');
        $this->command->info('   • Premium: Rs.3,000 → Rs.8,400 (180% profit)');
        $this->command->info('   • Gold: Rs.5,000 → Rs.15,120 (202% profit)');
        $this->command->info('   • Diamond: Rs.8,000 → Rs.27,000 (237% profit)');
    }
}