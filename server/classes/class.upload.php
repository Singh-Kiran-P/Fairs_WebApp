<?php

class Upload
{
  public static function uploadFiles($files, $id, $type, $ex)
  {
    if ($type == "profile") {
      // Upload file
      $ext = pathinfo($files['name'], PATHINFO_EXTENSION);
      move_uploaded_file($files['tmp_name'], __DIR__ . '/../uploads/' . $type . '_' . $ex . '/' . $id . '.' . $ext);
    } else {
      // Count total files
      $countfiles = count($files['name']);

      // Looping all files
      for ($i = 0; $i < $countfiles; $i++) {
        $filename = $files['name'][$i];

        // Upload file
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        move_uploaded_file($files['tmp_name'][$i], __DIR__ . '/../uploads/' . $type . '_' . $ex . '/' . $id . '_' . $i . '.' . $ext);
      }
    }
  }
}
