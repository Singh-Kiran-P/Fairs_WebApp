<?php

class Upload
{

  /**
   * To upload files
   *
   * @param [type] $files
   * @param [type] $id
   * @param [type] $type
   * @param [type] $ex
   * @return 0-> error < 0 succesfull
   */
  public static function uploadFiles($files, $id, $type, $ex)
  {


    if ($type == "profile") {
      // Upload file
      $ext = pathinfo($files['name'], PATHINFO_EXTENSION);
      move_uploaded_file($files['tmp_name'], __DIR__ . '/../uploads/' . $type . '_' . $ex . '/' . $id . '.' . $ext);
      return 1;
    } else {
      // Count total files
      $countfiles = count($files['name']);

      $count = 0;
      // Looping all files
      for ($i = 0; $i < $countfiles; $i++) {
        $filename = $files['name'][$i];

        // Upload file
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (move_uploaded_file($files['tmp_name'][$i], __DIR__ . '/../uploads/' . $type . '_' . $ex . '/' . $id . '_' . $count . '.' . $ext))
          $count++;
      }
      return $count;
    }
  }

  /**
   * Gives the right file with de right extension
   *
   * @param [type] $fileName
   * @return String Filename
   */
  public static function getUploadedFilePath($fileName, $type)
  {
    $dir = __DIR__ . '/../uploads/' . $type;

    // Sort in ascending order - this is default
    $a = scandir($dir);

    foreach ($a as $file) {
      if (strpos($file, $fileName) !== false)
        return $file;
    }

  }
}
