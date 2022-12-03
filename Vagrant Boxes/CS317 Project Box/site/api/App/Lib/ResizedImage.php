<?php

namespace App\Lib;

class ResizedImage
{
    private $imgfile;
    private $new_width = 0;
    private $new_height = 0;

    private $img = null;
    private $resized_img;
    private $optimized;
    public function __construct($filename,$new_height,$new_width)
    {
        $filename = pathinfo($filename);
        if (file_exists("/var/www/album_art/".$filename."_optimized.jpg"))
        {
            $this->optimized = true;
        }
        if (!is_null($filename) && file_exists($filename))
        {
            $this->imgfile = $filename;
            $this->new_height = $new_height;
            $this->new_width = $new_width;
            $this->loadImage();

        }
    }
    public function getAsBase64()
    {
        if (is_null($this->img))
        {
            $this->loadImage();
        }
        ob_start();
        imagepng($this->resized_img);
        $base64img = ob_get_contents();
        ob_clean();
        imagedestroy($this->img);
        return base64_encode($base64img);
    }

    private function loadImage()
    {
        if (!$this->optimized) {
            $this->img = imagecreatefromstring(file_get_contents($this->imgfile));
            if ($this->new_height > 0 && $this->new_width > 0) {
                $this->generateResizedImage();
            }
        }
        else
        {
            $filename = pathinfo($this->imgfile);
            $this->img = imagecreatefromstring(file_get_contents("/var/www/album_art/".$filename."_optimized.jpg"));
        }
    }

    private function generateResizedImage()
    {
        $vertical_scale_factor = $this->new_height / imagesy($this->img);
        $horizontal_scale_factor = $this->new_width / imagesx($this->img);
        $im_fact = min($vertical_scale_factor,$horizontal_scale_factor);
        $new_height = round(imagesy($this->img) * $im_fact);
        $new_width = round(imagesx($this->img) * $im_fact);

        $this->resized_img = imagecreatetruecolor($new_width,$new_height);
        imagecopyresampled($this->resized_img,$this->img,0,0,0,0,$new_width,$new_height,imagesx($this->img),imagesy($this->img));
        if (!$this->optimized)
        {
            $filename = pathinfo($this->imgfile);
            imagejpeg($this->resized_img,"/var/www/album_art/".$filename."_optimized.jpg");
        }
    }

}