<?php

//Get folder path to save files
if(!function_exists('get_uploads_folder')){
    function get_uploads_folder()
    {
        $uploadsDir = public_path('uploads');
        $firstPath = array_diff(scandir($uploadsDir), array('..', '.'));
        $secondPath = array_diff(scandir($uploadsDir.'/'.end($firstPath)), array('..', '.'));
        $currentPath = $uploadsDir.'/'.end($firstPath).'/'.end($secondPath);
        $public_path = 'uploads/'.end($firstPath).'/'.end($secondPath);
        $where_save = $currentPath;

        $currentDir = scandir($currentPath);
        if(count($currentDir) < 1000){
            $where_save = $currentPath;
        }else {
            $totalDirs = count( scandir($uploadsDir.'/'.end($firstPath)) );

            if($totalDirs < 10001){
                $newDir = str_pad($totalDirs - 1, 4, "0", STR_PAD_LEFT);
                $where_save = $uploadsDir.'/'.end($firstPath).'/'.$newDir;
                $public_path = 'uploads/'.end($firstPath).'/'.$newDir;
            }
            else {
                $totalDirs = count( scandir($uploadsDir) );
                $newDir = str_pad($totalDirs - 1, 4, "0", STR_PAD_LEFT);
                $where_save = $uploadsDir.'/'.$newDir.'/0001';
                $public_path = 'uploads/'.$newDir.'/0001';
            }
            mkdir($where_save, 0755, true);
        }
        return $result = ['where_save' => $where_save,'public_path' => $public_path];
    }
}
