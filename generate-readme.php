<?php
$rootDir = __DIR__;
$subDirList = [];

// 列出子目录
if ($rootDirRes = opendir($rootDir)) {
    while (($file = readdir($rootDirRes)) !== false) {
        if (filetype($rootDir . '/' . $file) == 'dir'
            && !in_array(substr($file, 0, 1), ['.', '~'])
        ) {
            $subDirList[] = $rootDir . '/' . $file;
        }
    }
}
//print_r($subDirList);

// 查询子目录下的md文件
$mdFiles = [];
foreach ($subDirList as $subDir) {
    if ($subDirRes = opendir($subDir)) {
        while (($file = readdir($subDirRes)) !== false) {
            if (filetype($subDir . '/' . $file) == 'file'
                && !in_array(substr($file, 0, 1), ['.', '~'])
                && substr($file, -3) == '.md'
            ) {
                $mdFiles[substr($file, 0, 8)] = $subDir . '/' . $file;
            }
        }
    }
}
krsort($mdFiles);
//print_r($mdFiles);

// 解析md文件内容
$readmeLists = [];
foreach ($mdFiles as $createDate => $mdFile) {
    $mdFileHandle = fopen($mdFile, 'r');
    $title = fgets($mdFileHandle);
    $subTitle = fgets($mdFileHandle);
    fclose($mdFileHandle);
    $readmeLists[] = [
        'title' => $title !== false ? trim(trim($title, '#')) : '标题未定义',
        'subTitle' => $subTitle !== false ? trim(trim($subTitle, '>')) : '副标题未定义',
        'path' => str_replace($rootDir . '/', '', $mdFile),
        'createDate' => $createDate,
    ];
}
//print_r($readmeLists);

// 生成README.md
$readMeContent = '';
foreach ($readmeLists as $item) {
    $readMeContent .= sprintf("[%s](%s) created_at：%s\n>%s\n\n\n", $item['title'], $item['path'], getPrettyDate($item['createDate']), ($item['subTitle'] ?: 'subtitle not define'));
}
file_put_contents('README.md', $readMeContent);

// function define

function getPrettyDate($date)
{
    return date('Y-m-d', strtotime($date));
}