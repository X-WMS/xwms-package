<?php

namespace LaravelShared\Core\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageService
{
    protected $basePath = 'assets/images';
    protected $LbasePath;

    public function __construct()
    {
        $this->LbasePath = public_path('assets/images');
    }
    /**
     * Get the image URL for a specific type and ID.
     *
     * @param string $type The type/category of the image.
     * @param int $id The ID of the image.
     * @return string The image URL.
     */
    public function getImg(string $type, $id = null, $extras = []): string
    {
        // Ondersteunde extensies
        $validExtensions = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
    
        $externalTypes = [
            "users" => "https://xwms.nl/"
        ];
    
        $name = $extras['gender'] ?? $type;
        $name = strtolower(trim($name));

        $defaultPath = array_key_exists($type, $externalTypes)
            ? $externalTypes[$type] . "{$this->basePath}/{$type}/default/{$name}.png"
            : asset("{$this->basePath}/{$type}/default/{$name}.png");

        if (!file_exists($defaultPath) && isset($extras['gender'])){
            $name = $type;
        }

        if (!$id) {
            return $defaultPath;
        }
    
        // Controleer of het type extern is
        if (isset($externalTypes[$type]) && env("APP_NAME") !== "xwms") {
            $sessionKey = "externalImage_{$type}_{$id}";
            if (session()->has($sessionKey)) {
                return session($sessionKey);
            }

            foreach ($validExtensions as $ext) {
                $externalUrl = "{$externalTypes[$type]}/item_$id.$ext";
                if ($this->checkRemoteFileExists($externalUrl)) {
                    session([$sessionKey => $externalUrl]);
                    return $externalUrl;
                }
            }

            if (!$this->checkRemoteFileExists($defaultPath)){
                $defaultPath = asset("{$this->basePath}/{$type}/default/{$name}.png");
            }

            session([$sessionKey => $defaultPath]);
            return $defaultPath;
        }
    
        // Controleer lokale bestanden
        foreach ($validExtensions as $ext) {
            $localPath = "$this->LbasePath/$type/images/item_$id.$ext"; // Fysiek pad
            $webPath = "$this->basePath/$type/images/item_$id.$ext"; // URL pad
    
            if (file_exists($localPath)) {
                return asset($webPath);
            }
        }
    
        return $defaultPath;
    }
    

    private function checkRemoteFileExists(string $url): bool
    {
        $headers = @get_headers($url);
        return $headers && strpos($headers[0], '200') !== false;
    }
    
    

    /**
     * Upload or update an image for a specific type and ID.
     *
     * @param UploadedFile $file The uploaded image file.
     * @param string $type The type/category of the image.
     * @param int $id The ID associated with the image.
     * @return string|bool Success message or error message.
     */
    public function setImg(UploadedFile $file, string $type, int $id)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileExtension = $file->extension();

        if (!in_array($fileExtension, $allowedExtensions)) {
            return "Invalid image. Only PNG or JPG files are allowed.";
        }

        $fileName = "item_$id.png";
        $uploadPath = "$this->basePath/$type/images";
        $filePath = "$uploadPath/$fileName";

        // Delete old file if exists
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        // Store the new file
        $file->storeAs($uploadPath, $fileName, 'public');
        
        return true;
    }

    /**
     * Delete an image for a specific type and ID.
     *
     * @param string $type The type/category of the image.
     * @param int $id The ID associated with the image.
     * @return bool True if deleted, false otherwise.
     */
    public function deleteImage(string $type, int $id): bool
    {
        $filePath = "$this->basePath/$type/images/item_$id.png";
        
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
            return true;
        }

        return false;
    }

    /**
     * Get the default image URL for a specific type.
     *
     * @param string $type The type/category of the image.
     * @return string The default image URL.
     */
    public function getDefaultImageUrl(string $type): string
    {
        return asset("$this->basePath/$type/default/$type.png");
    }

    /**
     * Get the correct image URL for a user, with fallback to gender or default.
     *
     * @param object $user User instance with `img` and optional `gender`.
     * @return string
     */
    public function getUserImage($user): string
    {
        // Als er een img-pad is
        if (!empty($user->img) && is_string($user->img)) {
            $localPath = public_path($user->img);
            if (file_exists($localPath)) {
                return asset($user->img);
            }
        }

        // Fallback: gender-based default
        $gender = strtolower($user->gender ?? ''); // kan 'male', 'female' of null zijn
        $genderPath = "{$this->basePath}/users/default/{$gender}.png";
        if (file_exists(public_path($genderPath))) {
            return asset($genderPath);
        }

        // Final fallback: algemene default
        return asset("{$this->basePath}/users/default/users.png");
    }
}
