<?php

namespace LaravelShared\Core\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Role;

use LaravelShared\Core\Services\ImageService;

class UserService extends Service
{
    private $defaultRole = "client";
    
    // ------------------------------------------------------
    // --------- GET THIS USER
    // ------------------------------------------------------

    public function getUserData(int $userId): array
    {
        // Verbind met de dynamische database via het User-model
        $user = (new User)->with('roles')->find($userId);
    
        // Controleer of de gebruiker bestaat
        if (!$user) {
            throw new Exception('User not found');
        }
    
        // Bepaal de hoogste rol van de gebruiker of standaard naar 'client' als er geen rollen zijn
        $highestRole = $user->roles->sortByDesc('level')->pluck('name')->first() ?? $this->defaultRole;
    
        // Splits de gebruikersnaam in voor- en achternaam
        [$usernameF, $usernameL] = $this->splitUsername($user->username);
    
        // Haal de afbeelding op via de ImageService
        $imageService = app()->make(ImageService::class);
        $userImg = $imageService->getImg('users', $user->id);
    
        // Haal alle beschikbare rollen in de database op
        $allRoles = $this->getRoles();
    
        // Stel de gebruikersgegevens samen
        return [
            'id' => $user->id,
            'username_f' => $usernameF,
            'username_l' => $usernameL,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $highestRole,
            'country' => $user->country,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'img' => $userImg,
            'roles_count' => $user->roles->count(), // Aantal gekoppelde rollen voor deze gebruiker
            'roles' => $allRoles, // Alle rollen in de database
        ];
    }

    public function getRoles()
    {
        return (new Role)
        ->pluck('name')
        ->toArray();
    }
    
    /**
     * Splits de gebruikersnaam in voor- en achternaam.
     *
     * @param string $username
     * @return array
     */
    private function splitUsername(string $username): array
    {
        $parts = explode(' ', $username);
    
        // Controleer of er een voor- en achternaam is
        $usernameF = $parts[0] ?? '';
        $usernameL = $parts[1] ?? '';
    
        return [$usernameF, $usernameL];
    }
    


    /**
     * Haal de gebruikerlogs op met niveau, categorie en andere details.
     *
     * @param string $db
     * @param int $userId
     * @return array
     */
    public function getUserLogs(int $userId): array
    {
        $connection = DB::connection();

        // Query om gebruikerslogs te krijgen
        $logs = $connection->table('logs')
            ->select(
                DB::raw('LOWER(level) as level'), // Zet 'level' naar kleine letters
                DB::raw('LOWER(category) as category'), // Zet 'category' naar kleine letters
                'message',
                'context',
                'adress_data', // JSON-veld voor IP en locatiegegevens
                'created_at'
            )
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Type messages bepalen
        $logData = [];
        foreach ($logs as $log) {
            $addressData = json_decode($log->adress_data, true);

            $logData[] = [
                'level' => $log->level,
                'category' => $log->category,
                'icon' => $this->getLogIcon($log->level),
                'message' => $log->message,
                'typemessage' => $this->determineErrorType($log->message, $log->level),
                'context' => $log->context,
                'color' => $this->getColor($log->level),
                'countryname' => $addressData['country'] ?? 'Unknown',
                'city' => $addressData['city'] ?? 'Unknown',
                'time' => Carbon::parse($log->created_at)->format('j M Y H:i'), // Formatteer de tijd
                'ipaddress' => $addressData['ipaddress'] ?? 'Unknown',
            ];
        }

        return [
            'data' => $logData,
            'groups' => $this->groupLogData($logs),
        ];
    }
        /**
     * Bepaal het type foutbericht op basis van de inhoud van het bericht.
     *
     * @param string $message
     * @return string
     */
    private function determineErrorType(string $message, string $level): string
    {
        if ($level === 'info' || $level === 'debug'){
            return "";
        }

        if (strpos($message, 'SQLSTATE') !== false) {
            return 'sql error';
        } elseif (strpos($message, 'connection') !== false) {
            return 'connection error';
        } elseif (strpos($message, 'authentication') !== false) {
            return 'authentication error';
        } elseif (strpos($message, 'timeout') !== false) {
            return 'timeout error';
        } elseif ($level === 'critical') {
            return 'critical error';
        }

        return 'general error';
    }

    /**
     * Haal een passend icoon op basis van het logniveau.
     *
     * @param string $level
     * @return string
     */
    private function getLogIcon(string $level): string
    {
        $icons = [
            'info' => 'mdi mdi-information text-light',
            'warning' => 'mdi mdi-alert-outline text-danger',
            'error' => 'mdi mdi-alert-circle text-warning',
            'critical' => 'mdi mdi-bug text-danger',
        ];

        return $icons[$level] ?? 'mdi mdi-information text-info';
    }

    /**
     * Groepeer loggegevens op niveaus en categorieën.
     *
     * @param \Illuminate\Support\Collection $logs
     * @return array
     */
    private function groupLogData($logs): array
    {
        $levels = [
            'debug' => 0, 'info' => 0, 'warning' => 0, 'error' => 0, 'critical' => 0
        ];
        $categories = [];

        foreach ($logs as $log) {
            $levels[$log->level] = isset($levels[$log->level]) ? $levels[$log->level] + 1 : 1;
            $categories[$log->category] = isset($categories[$log->category]) ? $categories[$log->category] + 1 : 1;
        }

        return [
            'levels' => $levels,
            'categories' => $categories,
        ];
    }

    private function getColor($level)
    {
        $icons = [
            'info' => 'info',
            'warning' => 'warning',
            'error' => 'danger',
            'critical' => 'secondary',
            'debug' => 'primary',
        ];

        return $icons[$level] ?? 'muted';
    }

    public function updateUser(int $userId, array $updateData): string
    {

        if (empty($updateData)) {
            throw new Exception("No changes were provided.");
        }

        // Haal het dynamische gebruikersmodel op
        $userModel = new User;

        // Controleer of de gebruiker bestaat
        $user = $userModel->find($userId);
        if (!$user) {
            throw new Exception('The user does not exist. Please try again.');
        }

        $updateFields = [];
        $updatedFieldsList = [];

        // Voornaam, achternaam en gebruikersnaam
        [$currentFirstName, $currentLastName] = explode(' ', $user->username . ' ');
        $currentFirstName = trim($currentFirstName);
        $currentLastName = trim($currentLastName);

        $firstName = $updateData['front_name'] ?? $currentFirstName;
        $lastName = $updateData['last_name'] ?? $currentLastName;

        if ($firstName !== $currentFirstName || $lastName !== $currentLastName) {
            $updateFields['username'] = trim("{$firstName} {$lastName}");
            $updatedFieldsList[] = "udpated the name";
        }

        // E-mail bijwerken
        if (isset($updateData['email'])) {
            if (!filter_var($updateData['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("The provided email is invalid. Please enter a valid email address.");
            }

            if ($updateData['email'] !== $user->email) {
                // Controleer of de e-mail al bestaat
                $existingUser = $userModel->where('email', xwms_hash($updateData['email']))->first();
                if ($existingUser) {
                    throw new Exception("The email address is already in use. Please use a different email.");
                }

                $updateFields['email'] = $updateData['email'];
                $updatedFieldsList[] = "Changed the email";
                // Zet email_verified_at op null als de e-mail wordt gewijzigd
                if ($user->pendingUser) {
                    $user->pendingUser->update(['email_verified_at' => null]);
                }
            }
        }

        // Rol toevoegen
        if (isset($updateData['role'])) {
            $role = $updateData['role'];
            if (!$user->hasRole($role)) {
                $user->assignRole($role);
                $updatedFieldsList[] = "Has given the role $role";
            }
        }

        // Wachtwoord bijwerken
        if (isset($updateData['password'])) {
            $updateFields['password'] = bcrypt($updateData['password']);
            $updatedFieldsList[] = "Has updated the password";
        }

        // Update uitvoeren

        if (empty($updateFields)) {
            throw new Exception("No changes were made to the user. Please modify some fields before submitting.");
        }

        if (!$user->update($updateFields)){
            throw new Exception("Something went wrong updating this user.");
        }

        $fieldsUpdated = implode('and ', $updatedFieldsList);
        $message = "The system {$fieldsUpdated} successfully.";
        return $message;
    }

    public function createViewData()
    {
        $response = [];
        $response['roles'] = $this->getRoles();
        return $response;
    }



    // ------------------------------------------------------
    // --------- USER SETTINGS
    // ------------------------------------------------------

    public function getUser($userId, $data = false)
    {
        if (!$userId) throw new Exception("You are not logged in.");
        $user = $this->UserModel->where('id', $userId)->first();
        if (!$user) throw new Exception("Something went wrong. logout and try again.");
        if ($data) return $user;
        return $this->formatUser($user);
    }

    public function formatUser($user)
    {
        $userId = $user->id;
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
        // $role = $user->roles->first()->name ?? 'Client';
        // $adminRole = in_array($role, $this->adminRoles);
    
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
            'role' => "client",
            'img' => $img,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ];
    }
}
