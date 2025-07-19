<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Package;
use App\Models\Video;
use App\Models\UserPackage;
use App\Models\WatchedVideo;
use App\Models\Withdrawal;
use App\Models\Referral;
use App\Models\Notification;
use App\Models\ManagerLog;
use App\Models\Earning;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'referral_code' => 'ADMIN001',
            'is_active' => true,
        ]);

        // Create manager users
        $managers = [];
        for ($i = 1; $i <= 3; $i++) {
            $managers[] = User::create([
                'name' => "Manager {$i}",
                'email' => "manager{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'manager',
                'referral_code' => "MGR00{$i}",
                'is_active' => true,
            ]);
        }

        // Create categories
        $categories = [
            ['name' => 'Technology', 'icon' => 'fa-laptop', 'color' => '#3B82F6'],
            ['name' => 'Education', 'icon' => 'fa-graduation-cap', 'color' => '#10B981'],
            ['name' => 'Entertainment', 'icon' => 'fa-film', 'color' => '#F59E0B'],
            ['name' => 'Health & Fitness', 'icon' => 'fa-heartbeat', 'color' => '#EF4444'],
            ['name' => 'Business', 'icon' => 'fa-briefcase', 'color' => '#8B5CF6'],
            ['name' => 'Lifestyle', 'icon' => 'fa-home', 'color' => '#EC4899'],
            ['name' => 'Travel', 'icon' => 'fa-plane', 'color' => '#06B6D4'],
            ['name' => 'Food & Cooking', 'icon' => 'fa-utensils', 'color' => '#F97316'],
        ];

        foreach ($categories as $index => $categoryData) {
            Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => "Videos related to {$categoryData['name']}",
                'icon' => $categoryData['icon'],
                'color' => $categoryData['color'],
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Create packages
        $packages = [
            [
                'name' => 'Starter',
                'description' => 'Perfect for beginners',
                'price' => 10.00,
                'video_limit' => 50,
                'reward_per_video' => 0.25,
                'min_withdrawal' => 5.00,
                'referral_bonus' => 1.00,
                'validity_days' => 30,
                'features' => ['50 Videos', '$0.25 per video', '30 days validity', 'Basic support'],
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Basic',
                'description' => 'Great value for regular users',
                'price' => 25.00,
                'video_limit' => 150,
                'reward_per_video' => 0.30,
                'min_withdrawal' => 10.00,
                'referral_bonus' => 2.50,
                'validity_days' => 60,
                'features' => ['150 Videos', '$0.30 per video', '60 days validity', 'Email support'],
                'is_featured' => true,
                'badge_text' => 'Popular',
                'badge_color' => '#10B981',
                'sort_order' => 2,
            ],
            [
                'name' => 'Premium',
                'description' => 'Best for active users',
                'price' => 50.00,
                'video_limit' => 350,
                'reward_per_video' => 0.35,
                'min_withdrawal' => 15.00,
                'referral_bonus' => 5.00,
                'validity_days' => 90,
                'features' => ['350 Videos', '$0.35 per video', '90 days validity', 'Priority support'],
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Professional',
                'description' => 'For serious earners',
                'price' => 100.00,
                'video_limit' => 800,
                'reward_per_video' => 0.40,
                'min_withdrawal' => 20.00,
                'referral_bonus' => 10.00,
                'validity_days' => 120,
                'features' => ['800 Videos', '$0.40 per video', '120 days validity', 'Phone support'],
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Maximum earning potential',
                'price' => 200.00,
                'video_limit' => 2000,
                'reward_per_video' => 0.50,
                'min_withdrawal' => 25.00,
                'referral_bonus' => 20.00,
                'validity_days' => 180,
                'features' => ['2000 Videos', '$0.50 per video', '180 days validity', 'Dedicated support'],
                'is_featured' => true,
                'badge_text' => 'Best Value',
                'badge_color' => '#F59E0B',
                'sort_order' => 5,
            ],
        ];

        foreach ($packages as $packageData) {
            Package::create([
                'name' => $packageData['name'],
                'slug' => Str::slug($packageData['name']),
                'description' => $packageData['description'],
                'price' => $packageData['price'],
                'video_limit' => $packageData['video_limit'],
                'reward_per_video' => $packageData['reward_per_video'],
                'min_withdrawal' => $packageData['min_withdrawal'],
                'referral_bonus' => $packageData['referral_bonus'],
                'validity_days' => $packageData['validity_days'],
                'features' => $packageData['features'],
                'is_active' => true,
                'is_featured' => $packageData['is_featured'],
                'badge_text' => $packageData['badge_text'] ?? null,
                'badge_color' => $packageData['badge_color'] ?? null,
                'sort_order' => $packageData['sort_order'],
            ]);
        }

        // Create videos
        $videoTitles = [
            'Introduction to Web Development',
            'Advanced JavaScript Concepts',
            'Building REST APIs with Laravel',
            'React Hooks Deep Dive',
            'Database Design Best Practices',
            'Mobile App Development with Flutter',
            'Machine Learning Basics',
            'Digital Marketing Strategies',
            'Photography Fundamentals',
            'Cooking Italian Cuisine',
            'Yoga for Beginners',
            'Personal Finance Management',
            'Travel Photography Tips',
            'Healthy Meal Prep Ideas',
            'Home Workout Routines',
            'Starting Your Own Business',
            'Social Media Marketing',
            'Graphic Design Principles',
            'Public Speaking Skills',
            'Time Management Techniques',
            'Cryptocurrency Basics',
            'Content Creation Tips',
            'Email Marketing Strategies',
            'SEO Optimization Guide',
            'Video Editing Masterclass',
        ];

        $categories = Category::all();
        $difficulties = ['beginner', 'intermediate', 'advanced'];

        foreach ($videoTitles as $index => $title) {
            Video::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => "Learn about {$title} in this comprehensive video tutorial.",
                'youtube_link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'min_watch_minutes' => rand(5, 15),
                'reward_amount' => rand(25, 75) / 100,
                'category_id' => $categories->random()->id,
                'difficulty_level' => $difficulties[array_rand($difficulties)],
                'tags' => ['tutorial', 'educational', 'beginner-friendly'],
                'view_count' => rand(50, 500),
                'completion_count' => rand(20, 200),
                'total_rewards_paid' => rand(10, 100),
                'is_active' => true,
                'is_featured' => rand(0, 1) == 1,
                'published_at' => now()->subDays(rand(1, 30)),
                'created_by' => $managers[array_rand($managers)]->id,
            ]);
        }

        // Create regular users with referral relationships
        $users = [];
        $packages = Package::all();

        for ($i = 1; $i <= 2; $i++) {
            $referrer = $i > 5 ? $users[array_rand($users)] : null;
            
            $user = User::create([
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'user',
                'referral_code' => "USR" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'referred_by' => $referrer?->id,
                'reward_balance' => rand(0, 100),
                'referral_earnings' => rand(0, 50),
                'phone_number' => '+92300' . rand(1000000, 9999999),
                'country' => 'Pakistan',
                'city' => ['Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad'][array_rand(['Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad'])],
                'gender' => ['male', 'female'][array_rand(['male', 'female'])],
                'date_of_birth' => now()->subYears(rand(18, 50))->subDays(rand(1, 365)),
                'is_active' => true,
                'last_login_at' => now()->subDays(rand(0, 7)),
            ]);
            
            $users[] = $user;

            // Create referral record if user was referred
            if ($referrer) {
                Referral::create([
                    'referrer_id' => $referrer->id,
                    'referred_id' => $user->id,
                    'bonus_amount' => rand(1, 5),
                    'bonus_paid' => true,
                    'bonus_paid_at' => $user->created_at,
                ]);
            }

            // 80% chance of having a package
            if (rand(1, 10) <= 8) {
                $package = $packages->random();
                $userPackage = UserPackage::create([
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'start_date' => now()->subDays(rand(1, 30)),
                    'end_date' => now()->addDays(rand(10, 60)),
                    'is_active' => true,
                    'amount_paid' => $package->price,
                    'payment_method' => ['jazzcash', 'easypaisa'][array_rand(['jazzcash', 'easypaisa'])],
                    'transaction_id' => 'TXN' . rand(100000, 999999),
                    'payment_status' => 'approved',
                    'approved_at' => now()->subDays(rand(1, 30)),
                    'approved_by' => $managers[array_rand($managers)]->id,
                    'videos_watched' => rand(0, min(50, $package->video_limit)),
                    'total_earned' => rand(0, 50),
                ]);

                // Create some watched videos for this user
                $videos = Video::inRandomOrder()->take(rand(5, 20))->get();
                foreach ($videos as $video) {
                    $watchedVideo = WatchedVideo::create([
                        'user_id' => $user->id,
                        'video_id' => $video->id,
                        'watched_seconds' => rand(300, 900),
                        'watch_percentage' => rand(80, 100),
                        'is_completed' => true,
                        'reward_granted' => true,
                        'reward_amount' => $video->reward_amount,
                        'started_at' => now()->subDays(rand(1, 20)),
                        'completed_at' => now()->subDays(rand(1, 20)),
                        'ip_address' => '192.168.1.' . rand(1, 255),
                        'device_type' => ['desktop', 'mobile', 'tablet'][array_rand(['desktop', 'mobile', 'tablet'])],
                        'browser' => ['Chrome', 'Firefox', 'Safari', 'Edge'][array_rand(['Chrome', 'Firefox', 'Safari', 'Edge'])],
                    ]);

                    // Create earning record
                    Earning::create([
                        'user_id' => $user->id,
                        'type' => 'video_reward',
                        'amount' => $video->reward_amount,
                        'description' => "Reward for watching: {$video->title}",
                        'reference_type' => 'watched_video',
                        'reference_id' => $watchedVideo->id,
                    ]);
                }
            }

            // 30% chance of having withdrawal requests
            if (rand(1, 10) <= 3) {
                $statuses = ['pending', 'processing', 'approved', 'rejected'];
                $methods = ['jazzcash', 'easypaisa', 'bank_transfer'];
                
                for ($w = 0; $w < rand(1, 3); $w++) {
                    $amount = rand(10, 100);
                    $method = $methods[array_rand($methods)];
                    $status = $statuses[array_rand($statuses)];
                    
                    Withdrawal::create([
                        'user_id' => $user->id,
                        'amount' => $amount,
                        'method' => $method,
                        'account_name' => $user->name,
                        'account_number' => '0300' . rand(1000000, 9999999),
                        'bank_name' => $method === 'bank_transfer' ? 'HBL Bank' : null,
                        'user_notes' => 'Please process quickly',
                        'fee_amount' => $amount * 0.02,
                        'final_amount' => $amount * 0.98,
                        'status' => $status,
                        'admin_notes' => $status === 'rejected' ? 'Insufficient balance' : null,
                        'transaction_id' => $status === 'approved' ? 'TXN' . rand(100000, 999999) : null,
                        'requested_at' => now()->subDays(rand(1, 10)),
                        'processed_at' => in_array($status, ['approved', 'rejected']) ? now()->subDays(rand(1, 5)) : null,
                        'completed_at' => $status === 'approved' ? now()->subDays(rand(1, 5)) : null,
                        'processed_by' => in_array($status, ['approved', 'rejected']) ? $managers[array_rand($managers)]->id : null,
                    ]);
                }
            }
        }

        // Create notifications for managers
        foreach ($managers as $manager) {
            for ($n = 0; $n < rand(5, 15); $n++) {
                $types = ['payment_submitted', 'withdrawal_requested', 'video_watched', 'user_registered'];
                $type = $types[array_rand($types)];
                
                Notification::create([
                    'user_id' => $manager->id,
                    'type' => $type,
                    'title' => ucfirst(str_replace('_', ' ', $type)),
                    'message' => "This is a sample notification for {$type}",
                    'data' => ['sample' => 'data'],
                    'is_read' => rand(0, 1) == 1,
                    'read_at' => rand(0, 1) == 1 ? now()->subDays(rand(1, 5)) : null,
                    'priority' => ['low', 'medium', 'high'][array_rand(['low', 'medium', 'high'])],
                ]);
            }
        }

        // Create manager logs
        foreach ($managers as $manager) {
            for ($l = 0; $l < rand(10, 30); $l++) {
                $actions = ['user_created', 'package_updated', 'video_added', 'withdrawal_approved', 'payment_reviewed'];
                $action = $actions[array_rand($actions)];
                
                ManagerLog::create([
                    'manager_id' => $manager->id,
                    'action_type' => $action,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $users[array_rand($users)]->id,
                    'description' => "Manager performed action: {$action}",
                    'old_data' => ['status' => 'old'],
                    'new_data' => ['status' => 'new'],
                    'ip_address' => '192.168.1.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'severity' => ['info', 'warning'][array_rand(['info', 'warning'])],
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }

        // Create system settings
        $settings = [
            ['key' => 'site_name', 'value' => 'Video Rewards Platform', 'type' => 'string', 'description' => 'Site name'],
            ['key' => 'site_description', 'value' => 'Earn money by watching videos', 'type' => 'string', 'description' => 'Site description'],
            ['key' => 'jazzcash_number', 'value' => '03001234567', 'type' => 'string', 'description' => 'JazzCash account number'],
            ['key' => 'jazzcash_name', 'value' => 'Video Rewards', 'type' => 'string', 'description' => 'JazzCash account name'],
            ['key' => 'easypaisa_number', 'value' => '03007654321', 'type' => 'string', 'description' => 'EasyPaisa account number'],
            ['key' => 'easypaisa_name', 'value' => 'Video Rewards', 'type' => 'string', 'description' => 'EasyPaisa account name'],
            ['key' => 'contact_number', 'value' => '03001234567', 'type' => 'string', 'description' => 'Contact number for support'],
            ['key' => 'min_withdrawal_amount', 'value' => '5', 'type' => 'integer', 'description' => 'Minimum withdrawal amount'],
            ['key' => 'withdrawal_fee_percentage', 'value' => '2', 'type' => 'integer', 'description' => 'Withdrawal fee percentage'],
            ['key' => 'referral_enabled', 'value' => '1', 'type' => 'boolean', 'description' => 'Enable referral system'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'description' => 'Maintenance mode'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Manager: manager1@example.com / password');
        $this->command->info('User: user1@example.com / password');
    }
}