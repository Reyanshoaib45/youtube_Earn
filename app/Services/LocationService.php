<?php

namespace App\Services;

use App\Models\IpTracking;
use App\Models\LocationLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationService
{
    public static function trackUserLocation($user, $ipAddress = null)
    {
        try {
            $ipAddress = $ipAddress ?: request()->ip();
            
            // Check if IP already tracked today
            $existingTracking = IpTracking::where('ip_address', $ipAddress)
                ->whereDate('created_at', today())
                ->first();
            
            if (!$existingTracking) {
                $locationData = self::getLocationFromIP($ipAddress);
                
                IpTracking::create([
                    'user_id' => $user->id,
                    'ip_address' => $ipAddress,
                    'country' => $locationData['country'] ?? null,
                    'region' => $locationData['region'] ?? null,
                    'city' => $locationData['city'] ?? null,
                    'latitude' => $locationData['latitude'] ?? null,
                    'longitude' => $locationData['longitude'] ?? null,
                    'user_agent' => request()->userAgent(),
                ]);
            }
            
            // Always log location for user
            LocationLog::create([
                'user_id' => $user->id,
                'ip_address' => $ipAddress,
                'country' => $locationData['country'] ?? null,
                'region' => $locationData['region'] ?? null,
                'city' => $locationData['city'] ?? null,
                'action' => 'login',
                'user_agent' => request()->userAgent(),
            ]);
            
            // Update user's current location
            $user->update([
                'current_location' => ($locationData['city'] ?? '') . ', ' . ($locationData['country'] ?? ''),
                'last_login_ip' => $ipAddress,
                'last_login_at' => now(),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Location tracking failed: ' . $e->getMessage());
        }
    }
    
    public static function getLocationFromIP($ipAddress)
    {
        try {
            // Skip local IPs
            if (in_array($ipAddress, ['127.0.0.1', '::1', 'localhost'])) {
                return [
                    'country' => 'Local',
                    'region' => 'Local',
                    'city' => 'Local',
                    'latitude' => null,
                    'longitude' => null,
                ];
            }
            
            // Use ip-api.com (free service)
            $response = Http::timeout(10)->get("http://ip-api.com/json/{$ipAddress}");
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'success') {
                    return [
                        'country' => $data['country'] ?? null,
                        'region' => $data['regionName'] ?? null,
                        'city' => $data['city'] ?? null,
                        'latitude' => $data['lat'] ?? null,
                        'longitude' => $data['lon'] ?? null,
                    ];
                }
            }
            
            return [];
        } catch (\Exception $e) {
            Log::error('IP location lookup failed: ' . $e->getMessage());
            return [];
        }
    }
    
    public static function logUserAction($user, $action, $details = null)
    {
        try {
            LocationLog::create([
                'user_id' => $user->id,
                'ip_address' => request()->ip(),
                'action' => $action,
                'details' => $details,
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            Log::error('Action logging failed: ' . $e->getMessage());
        }
    }
}
