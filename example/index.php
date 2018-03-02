<?php

class File
{
    static $find_flag = false;

    public static function loadDirClassFiles($dir, &$data, $ignoreTypeDirs=null)
    {
        if (!is_dir($dir))
        {
            return;
        }
        $files = scandir($dir);
        foreach($files as $file)
        {
            $fullpath = $dir.DIRECTORY_SEPARATOR.$file;
            if (in_array($file, array('.','..')))
            {
                continue;
            }
            if (is_dir($fullpath))
            {
                self::loadDirClassFiles($fullpath, $data, $ignoreTypeDirs);
            }
            if (is_file($fullpath))
            {
                if (false == $index = stripos($file, ".tpl"))
                {
                    continue;
                }

                $class = self::getClassName($file);

                $data[$class] = $file;
            }
        }
    }

    public static function findFiles($dir, $findFileName)
    {
        if (self::$find_flag == true) {
            return;
        }
        if (!is_dir($dir))
        {
            return;
        }
        $files = scandir($dir);

        foreach($files as $file)
        {
            $fullpath = $dir.DIRECTORY_SEPARATOR.$file;
            if (in_array($file, array('.','..')))
            {
                continue;
            }
            if (is_dir($fullpath))
            {
                self::findFiles($fullpath, $findFileName);
            }
            if (is_file($fullpath))
            {
                if (false == $index = stripos($file, ".tpl"))
                {
                    continue;
                }

                $class = self::getClassName($file);

                if ($class == $findFileName) {
                    self::$find_flag = true;
                    break;
                }
            }
        }

        return self::$find_flag;
    }

    public static function getClassName($file)
    {
        $index = stripos($file, ".tpl");
        if ($index == false)
        {
            return null;
        }
        return str_ireplace(".tpl", "", $file);
    }
}
error_reporting(E_ALL);
$skyalr7_path = 'F:\\skylar_web_code\\new_www';
$skyalrR3_paths = array(
//    'E:\\skylar_web_code_test\\new_www\\source\\logic',
//    'E:\\skylar_web_code_test\\new_www\\\application',
    'E:\\skylar_web_code_test\\new_www\\view',
    );
$data = array();
foreach ($skyalrR3_paths as $path) {
    $files = array();
    File::loadDirClassFiles($path, $files);

    foreach ($files as $key => $file) {

        $fileName = File::getClassName($file);
        $ret = File::findFiles($skyalr7_path, $fileName);
        if ($ret == false) {
            array_push($data, $fileName);
        }
        File::$find_flag = false;
    }
}

var_dump($data);

