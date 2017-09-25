<?php
/**
 * Created by PhpStorm.
 * User: Watanabe
 * Date: 2017/09/11
 * Time: 19:52
 */
$count = $_GET['count'] ?? 5;
$dateTime = $_GET['date_time'] ?? 0;
$uploadDirName = 'uploads';
$iterator = new DirectoryIterator ($uploadDirName);
$returnArray = [];
foreach ($iterator as $fileInfo) {
    if (! preg_match('/.*?(jpg|jpeg|gif|png)/', $fileInfo->getExtension())) continue;
    $fileTime = explode('.', $fileInfo->getFilename())[0] ?? new DateTime();
    if ($fileTime <= $dateTime) continue;
    $returnArray[$fileTime] = $uploadDirName . DIRECTORY_SEPARATOR . $fileInfo->getFilename();
}
ksort($returnArray);

header('content-type: application/json; charset=utf-8');
echo json_encode(array_slice($returnArray, 0, $count));