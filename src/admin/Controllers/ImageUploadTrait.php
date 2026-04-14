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
            @mkdir($dirSave, 0777, true);
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
}
