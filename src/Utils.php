<?php

namespace grintea;

class Utils{

    public static function getFile(string $filePath){
        return \trim(\file_get_contents($filePath), "\xEF\xBB\xBF");
    }

    public static function replaceFileExtension( $fileName, $extension ){
        $fileName = \explode('.', $fileName);
        if(\count($fileName) == 1){
            return "$fileName.$extension";
        }
        else{
            return $fileName[0] . '.' .$extension;
        }
    }

    public static function copy($src,$dst, $replaceExtension = false) {
        $dir = \opendir($src);
        if(!\file_exists($dst)){
            \mkdir($dst);
        }
        while(false !== ( $file = \readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . \DS . $file) ) {
                    self::copy($src . \DS . $file,$dst . \DS . $file, $replaceExtension);
                }
                else {
                    if($replaceExtension){
                        \copy($src . \DS . $file,$dst . \DS . self::replaceFileExtension($file, $replaceExtension));
                    }
                    else{
                        \copy($src . \DS . $file,$dst . \DS . $file);
                    }
                }
            }
        }
        \closedir($dir);
    }

}