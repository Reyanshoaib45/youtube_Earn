<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpTracking extends Model
{
    use HasFactory;

    protected $table = 'ip_tracking';

    protected $fillable = [
        'ip_address',
        'account_count',
        'is_flagged',
        'flagged_reason',
        'first_seen_at',
        'last_seen_at',
        'user_ids',
    ];

    protected $casts = [
        'user_ids' => 'array',
        'is_flagged' => 'boolean',
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function users()
    {
        return User::whereIn('id', $this->user_ids ?? [])->get();
    }

    public function flagAsMultipleAccounts($reason = null)
    {
        $this->update([
            'is_flagged' => true,
            'flagged_reason' => $reason ?? 'Multiple accounts detected from same IP',
        ]);
    }

    public static function trackUser($userId, $ipAddress)
    {
        $tracking = self::firstOrCreate(
            ['ip_address' => $ipAddress],
            [
                'account_count' => 0,
                'first_seen_at' => now(),
                'last_seen_at' => now(),
                'user_ids' => [],
            ]
        );

        $userIds = $tracking->user_ids ?? [];
        
        if (!in_array($userId, $userIds)) {
            $userIds[] = $userId;
            $tracking->update([
                'user_ids' => $userIds,
                'account_count' => count($userIds),
                'last_seen_at' => now(),
            ]);

            // Flag if more than 2 accounts
            if (count($userIds) > 2 && !$tracking->is_flagged) {
                $tracking->flagAsMultipleAccounts();
                
                // Create notification for admins
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'type' => 'ip_flagged',
                        'title' => 'Multiple Accounts Detected',
                        'message' => "IP {$ipAddress} has {$tracking->account_count} accounts registered",
                        'data' => [
                            'ip_address' => $ipAddress,
                            'account_count' => $tracking->account_count,
                            'user_ids' => $userIds,
                        ],
                        'priority' => 'high',
                    ]);
                }
            }
        }

        return $tracking;
    }
}
