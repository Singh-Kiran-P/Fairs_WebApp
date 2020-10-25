<?php
$db_type = "pgsql";
$db_host = "localhost";
$port = "5432";
$db_user = "postgres";
$db_password = "singh";
$db_name = "kiransingh";
$rootURL="http://uhasselt.me";

function absolute_include($file)
         {
         /*
         $file is the file url relative to the root of your site.
         Yourdomain.com/folder/file.inc would be passed as
         "folder/file.inc"
         */

         $folder_depth = substr_count($_SERVER["PHP_SELF"] , "/");

         if($folder_depth == false)
            $folder_depth = 1;

         include(str_repeat("../", $folder_depth - 1) . $file);
         }
?>
