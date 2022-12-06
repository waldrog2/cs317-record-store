<?php

namespace App\Lib;

class URLUtils
{

    public static function artPathToURL($path)
    {
        $info = pathinfo($path);
        return "http://localhost:8044/album_art/". urlencode($info['filename'] . ".jpg");
    }

    public static function generateAPIPath($type,$value)
    {
        return match ($type) {
            "albumAPI" => "/api/album?id=$value",
            "albumPage" => "/album/$value",
            default => "",
        };
    }



}