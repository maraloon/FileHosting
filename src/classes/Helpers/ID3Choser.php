<?php
namespace FileHosting\Helpers;
class ID3Choser{

	public function getTags($filePath,$type){

		$ID3Tags=new \getID3;
        $info=$ID3Tags->analyze($filePath);

        if ($type=='audio') {
        	$info=$info['tags']['id3v2'];

        	foreach ($info as $key => $value) { //костыль
        		$info[$key]=$info[$key][0];
        	}
        }
        elseif ($type=='video') {
        	$info=$info['video'];
        }

		return $info;
	}
}