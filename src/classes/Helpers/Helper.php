<?php
namespace FileHosting\Helpers;

use FileHosting\Models\File;
class Helper
{

    static function getAbsolutePath($file)
    {
        return realpath(__DIR__.'/../../../'.$file);
    }

    /**
     * Транслит кириллицы в латиницу
     * @param string $input 
     * @return string
     */

    static function transliterate($input)
    {
        $gost = array(
        "Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"-","є"=>"ye","ѓ"=>"g",
        "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
        "Е"=>"E","Ё"=>"YO","Ж"=>"ZH",
        "З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L",
        "М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
        "С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"X",
        "Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
        "Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA",
        "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
        "е"=>"e","ё"=>"yo","ж"=>"zh",
        "з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"x",
        "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
        "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya");

        return strtr($input, $gost);
    }

    static function getPathForFile($uploadFolder,File $file)
    {
        return $uploadFolder.$file->path.$file->name;
    }

    static function randHash($count = 20)
    {
        $result='';
        $array=array_merge(range('a','z'),range('0','9'));
        for($i=0; $i < $count; $i++){
            $result.=$array[mt_rand(0,count($array)-1)];
        }
        
        return $result;
    }

    /**
     * Добавляет хеш к имени файла, игнорируя разрешение (любой вложенности вроде: archive.tar.gz)
     * @param string $filename имя файла
     * @return string name_$hash.format
     */
    static function addHashToFileName(string $filename)
    {
        $hash=self::randHash(3);
        //${1} - имя файла; $2 - расширение; 
        return preg_replace("/([a-zа-я0-9_-]*)(([.][a-z0-9]*)*)/ui", '${1}_'.$hash.'$2', $filename,1);
    }
}