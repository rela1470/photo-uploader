<?php
/**
 * POSTされた画像を回転して保存
 */


/**
 * main
 */
function main ()
{
    $uploadDirName = 'uploads';
    if ($_FILES['file']['tmp_name'] ?? '') {
        $inputName = $_FILES['file']['tmp_name'];

        $fileType = exif_imagetype($inputName);
        $exif = @exif_read_data($inputName); //EXIF無かったらエラーになる
        $exif = $exif ? $exif : [];

        $outputName = $uploadDirName . DIRECTORY_SEPARATOR . uniqid(date('Y-m-d-H-i-s')) . image_type_to_extension($fileType);

        $image = imagecreatefromstring(file_get_contents($inputName));
        if (! $image) exit('image create error');

        $image = rotate($image, $exif);
        return save($image, $outputName, $fileType);
    }
}

/**
 * rotate
 * @param resource $image
 * @param array $exif
 * @return resource
 */
function rotate($image, array $exif)
{

    $orientation = $exif['Orientation'] ?? 1;

    switch ($orientation) {
        case 1 : //no rotate
            break;
        case 2 : //FLIP_HORIZONTAL
            imageflip($image, IMG_FLIP_HORIZONTAL);
            break;
        case 3 : //ROTATE 180
            $image = imagerotate($image,180, 0);
            break;
        case 4 : //FLIP_VERTICAL
            imageflip($image, IMG_FLIP_VERTICAL);
            break;
        case 5 : //ROTATE 270 FLIP_HORIZONTAL
            $image = imagerotate($image,270, 0);
            imageflip($image, IMG_FLIP_HORIZONTAL);
            break;
        case 6 : //ROTATE 90
            $image = imagerotate($image,270, 0);
            break;
        case 7 : //ROTATE 90 FLIP_HORIZONTAL
            $image = imagerotate($image,90, 0);
            imageflip($image, IMG_FLIP_HORIZONTAL);
            break;
        case 8 : //ROTATE 270
            $image = imagerotate($image,90, 0);
            break;
    }
    return $image;
}

/**
 * save
 * @param resource $image
 * @param string $outputName
 * @param int $fileType
 * @return bool
 */
function save($image, string $outputName, int $fileType)
{
    switch ($fileType) {
        case IMAGETYPE_GIF :
            return imagegif($image, $outputName);
            break;
        case IMAGETYPE_JPEG :
            return imagejpeg($image, $outputName);
            break;
        case IMAGETYPE_PNG :
            return imagepng($image, $outputName);
            break;

    }

    imagedestroy($image);
}

main();
