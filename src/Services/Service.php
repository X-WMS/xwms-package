<?php

namespace LaravelShared\Core\Services;

use App\Models\User;
use \LaravelShared\Core\Services\ImageService;
use Illuminate\Support\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception AS MailException;

class Service
{

    protected User $UserModel;
    protected ImageService $ImageService;
    protected $adminRoles = [
        'Owner', 'Moderator'
    ];

    public function __construct()
    {
        $this->UserModel = new User;
        $this->ImageService = new ImageService;
    }

    public function getUserFromDB($userId, $data = null, $User = null)
    {
        if (!$userId && !$User) return null;
        $user = $userId ? $this->UserModel->where('id', $userId)->first() : $User;
        if (!$user) return null;
        if ($data === true) return $user;

        $username = $user->username ?? '';
        $nameParts = explode(' ', $username);
        $firstName = $nameParts[0] ?? ''; // Default to empty string if not present
        $lastName = $nameParts[1] ?? ''; // Default to empty string if not present
        
        // Generate display name with error handling for missing parts
        $displayname = '';
        if (isset($nameParts[0])) {
            $initial = isset($nameParts[1]) ? substr($nameParts[1], 0, 1) . '. ' : '';
            $displayname = $initial . $nameParts[0];
        } else {
            $displayname = $firstName; // Fall back to firstName if only one part exists
        }
    
        // Calculate online status: user is online if last activity was within the last 5 minutes
        $isOnline = $user->online_date && Carbon::parse($user->online_date)->diffInMinutes(now()) < 5;
        $onlineStatus = $isOnline ? 'online' : 'offline';
    
        // Retrieve the user's primary role from the roles relationship, defaulting to 'User'
        $role = $user->roles->first()->name ?? 'Client';
        $adminRole = in_array($role, $this->adminRoles);
    
        // Get user image through the image service, with a default value if it fails
        $img = $this->ImageService->getImg('users', $userId) ?? 'default_image_path';
    
        // Return structured user data as an array
        return [
            'id' => $user->id,
            'username' => $username,
            'displayname' => $displayname,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $user->email ?? '',
            'active' => $onlineStatus,
            'role' => $role,
            'adminRole' => $adminRole,
            'img' => $img,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ];
    }

    protected function hsrImg(string $img): string
    {
        return "hsr::img::{$img}";
    }

    protected function hsrTitle(string $title): string
    {
        return "hsr::title::" . str_replace(' ', '_', $title);
    }

    protected function hsrStatus(string $status): string
    {
        return "hsr::status::{$status}";
    }

    protected function hsrDuration(int $ms): string
    {
        return "hsr::duration::{$ms}";
    }

    protected function hsrClosable(bool $bool): string
    {
        return "hsr::closable::" . ($bool ? 'true' : 'false');
    }

    protected function hsrIcon(string $icon): string
    {
        return "hsr::icon::{$icon}";
    }

    // Voeg eventueel meer toe zoals:
    protected function hsrPosition(string $pos): string
    {
        return "hsr::position::{$pos}";
    }
}
