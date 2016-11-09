<?php
namespace FileHosting\Helpers;
class ID3Choser
{

    protected $keys;

    function __construct(){
        $this->keys['audio']=array(
            'Формат'=>'dataformat',
            'Кол-во каналов'=>'channels',
            'Частота дискретизации'=>'sample_rate',
            'Битрейт'=>'bitrate',
            'Кодек'=>'codec');
        $this->keys['video'] = array(
                'Формат'=>'dataformat',
                'Длина'=>'resolution_x',
                'Ширина'=>'resolution_y',
                'Кадров  в секунду'=>'frame_rate',
            );
    }


    public function getTags($filePath,$type)
    {
        $ID3Tags=new \getID3;
        $tags=$ID3Tags->analyze($filePath);

        if ($type=='audio') {
            $tags=$tags["audio"]["streams"][0];
        } elseif ($type=='video') {
            $tags=$tags['video'];
        } else {
            return NULL;
        }

        foreach ($tags as $key => $value) {
            if (!in_array($key,$this->keys[$type])) {
                unset($tags[$key]);
            }
        }

        return $tags;
    }

    public function getJSONInfo($filePath,$type)
    {
        $tags=$this->getTags($filePath,$type);
        if ($tags!=NULL){
            return json_encode($tags);
        }
        return $tags; //NULL
    }

    public function replaceKeys(array $mediaInfo,$type)
    {
        $replacedKeys=array();
        foreach ($this->keys[$type] as $key => $value) {
            $replacedKeys[$key]=$mediaInfo[$value];
        }
        return $replacedKeys;
    }
}