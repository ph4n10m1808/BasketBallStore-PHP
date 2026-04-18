<?php

/**
 * Trait chung cho việc upload ảnh, thay vì copy-paste formatImage() trong 3 controller
 */
trait ImageUploadTrait
{
    public function formatImage(string $nameInput, string $subDir = "product"): string
    {
        $dirSave = "../public/imgs/{$subDir}/";

        // Ensure upload directory exists and is writable
        if (!is_dir($dirSave)) {
            @mkdir($dirSave, 0770, true);
        }

        $image = "";

        // Check if file was actually uploaded
        if (!isset($_FILES[$nameInput]) || $_FILES[$nameInput]['error'] !== UPLOAD_ERR_OK) {
            return $image;
        }

        $target_file = $dirSave . basename($_FILES[$nameInput]["name"]);

        // Use @ to suppress warnings so header() can still work
        $status_upload = @move_uploaded_file($_FILES[$nameInput]["tmp_name"], $target_file);

        if ($status_upload) {
            $image = "imgs/{$subDir}/" . basename($_FILES[$nameInput]["name"]);
        }
        return $image;
    }

    /**
     * Delete an image from the public directory
     *
     * @param string|null $imagePath Relative path from the public directory (e.g., 'imgs/product/file.jpg')
     */
    public function deleteImage(?string $imagePath): void
    {
        if (!empty($imagePath)) {
            $filePath = __DIR__ . "/../../public/" . $imagePath;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    /**
     * Handle updating an image: uploads new image if provided, deletes old image, and returns the path to save in DB.
     *
     * @param string $inputName The name of the file input
     * @param string|null $oldImage The existing image path from the database
     * @param string $subDir The subdirectory to save the image in
     * @return string The image path to save in the database
     */
    public function updateImage(string $inputName, ?string $oldImage, string $subDir = "product"): string
    {
        $newImage = $this->formatImage($inputName, $subDir);

        if (!empty($newImage)) {
            // If a new image was successfully uploaded, delete the old one
            $this->deleteImage($oldImage);
            return $newImage;
        }

        // Otherwise, keep the old image
        return $oldImage ?? "";
    }
}
