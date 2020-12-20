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

  public static function checkFilesImg($files, $single = false)
  {
    $msg = '';
    // Count total files
    $countfiles = count($files['name']);

    $count = 0;
    // Looping all files
    for ($i = 0; $i < $countfiles; $i++) {
      $uploadOk = 1;

      if ($single) {
        $imageFileType = strtolower(pathinfo($files["name"], PATHINFO_EXTENSION));
        $filename = $files["name"];
        $tempName = $files["tmp_name"];
      } else {
        $imageFileType = strtolower(pathinfo($files["name"][$i], PATHINFO_EXTENSION));
        $filename = $files["name"][$i];
        $tempName = $files["tmp_name"][$i];
      }

      if ($imageFileType == '')
        return ['msg' => 'No files provided'];

      // Check if image file is a actual image or fake image
      $check = getimagesize($tempName);
      if ($check == false) {
        $msg .= "[" . $filename . "] is not an image.";
        $uploadOk = 0;
      }


      // Check file size
      if ($files["size"][$i] > 2000000) {
        $msg .= "Sorry, file [" . $filename . "] is too large.";
        $uploadOk = 0;
      }

      // Allow certain file formats
      if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
      ) {
        $msg .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
      }


      // Check if $msg is set to a message by an error
      if ($msg != '') {
        return ['msg' => $msg];
        // everything is oke
      } else {
        return ['msg' => ''];
      }
    }
  }

  public static function checkFilesVideo($files)
  {
    $msg = '';
    // Count total files
    $countfiles = count($files['name']);

    $count = 0;
    // Looping all files
    for ($i = 0; $i < $countfiles; $i++) {
      $uploadOk = 1;
      $videoType = strtolower(pathinfo($files["name"][$i], PATHINFO_EXTENSION));
      if ($videoType == '')
        return ['msg' => 'No files provided'];



      // Check file size
      if ($files["size"][$i] > 20000000) {
        $msg .= "Sorry, file [" . $files["name"][$i] . "] is too large.";
        $uploadOk = 0;
      }

      // Allow certain file formats
      if (
        $videoType != "mp4" && $videoType != "mk"
      ) {
        $msg .= "Sorry, only mp4 and mk files are allowed.";
        $uploadOk = 0;
      }


      // Check if $msg is set to a message by an error
      if ($msg != '') {
        return ['msg' => $msg];
        // everything is oke
      } else {
        return ['msg' => ''];
      }
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
