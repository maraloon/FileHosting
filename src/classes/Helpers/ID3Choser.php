<?php
namespace FileHosting\Helpers;
class ID3Choser
{
    public function getTags($filePath,$type)
    {
        $ID3Tags=new \getID3;
        $info=$ID3Tags->analyze($filePath);
        
        if ($type=='audio') {
            $info=$info["audio"]["streams"][0];

            $keys = array(
                'Формат'=>'dataformat',
                'Кол-во каналов'=>'channels',
                'Частота дискретизации'=>'sample_rate',
                'Битрейт'=>'bitrate',
                'Кодек'=>'codec'
            );
        } elseif ($type=='video') {
            $info=$info['video'];

            $keys = array(
                'Формат'=>'dataformat',
                'Длина'=>'resolution_x',
                'Ширина'=>'resolution_y',
                'Кадров  в секунду'=>'frame_rate',
            );

        } else {
            return NULL;
        }

        foreach ($keys as $key => $value) {
            $tags[$key]=$info[$value];
        }

        return $tags;
    }
}