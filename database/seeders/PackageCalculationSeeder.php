<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageCalculationSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing packages
        Package::truncate();

        // Package calculation formula:
        // Total Reward = Daily Videos × Duration Days × Reward Per Video
        // Profit = Total Reward - Package Price
        // ROI = (Profit / Package Price) × 100

        $packages = [
            [
                'name' => '🚀 Starter Package',
                'price' => 1000.00,
                'duration_days' => 7,
                'daily_video_limit' => 5,
                'total_reward' => 2100.00, // 5 × 7 × 60 = 2100
                'is_active' => true,
                'description' => 'Perfect for beginners - 110% profit in just 1 week!',
                // ROI: (1100/1000) × 100 = 110%
            ],
            [
                'name' => '💎 Basic Package',
                'price' => 2000.00,
                'duration_days' => 10,
                'daily_video_limit' => 8,
                'total_reward' => 4800.00, // 8 × 10 × 60 = 4800
                'is_active' => true,
                'description' => 'Great value - 140% profit in 10 days!',
                // ROI: (2800/2000) × 100 = 140%
            ],
            [
                'name' => '⭐ Premium Package',
                'price' => 3000.00,
                'duration_days' => 14,
                'daily_video_limit' => 10,
                'total_reward' => 8400.00, // 10 × 14 × 60 = 8400
                'is_active' => true,
                'description' => 'Most popular - 180% profit in 2 weeks!',
                // ROI: (5400/3000) × 100 = 180%
            ],
            [
                'name' => '🏆 Gold Package',
                'price' => 5000.00,
                'duration_days' => 21,
                'daily_video_limit' => 12,
                'total_reward' => 15120.00, // 12 × 21 × 60 = 15120
                'is_active' => true,
                'description' => 'High earner - 202% profit in 3 weeks!',
                // ROI: (10120/5000) × 100 = 202%
            ],
            [
                'name' => '💰 Diamond Package',
                'price' => 8000.00,
                'duration_days' => 30,
                'daily_video_limit' => 15,
                'total_reward' => 27000.00, // 15 × 30 × 60 = 27000
                'is_active' => true,
                'description' => 'Maximum profit - 237% return in 1 month!',
                // ROI: (19000/8000) × 100 = 237%
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }

        $this->command->info('✅ Created 5 packages with realistic profit calculations');
        $this->command->info('📊 Package Breakdown:');
        $this->command->info('• Starter: Rs.1000 → Rs.2100 (110% profit)');
        $this->command->info('• Basic: Rs.2000 → Rs.4800 (140% profit)');
        $this->command->info('• Premium: Rs.3000 → Rs.8400 (180% profit)');
        $this->command->info('• Gold: Rs.5000 → Rs.15120 (202% profit)');
        $this->command->info('• Diamond: Rs.8000 → Rs.27000 (237% profit)');
    }
}
