<?php

namespace App\Common;

class Helper
{
    public static function TrimMedia(\Illuminate\Database\Eloquent\Model $model): void
    {
        if ($model){
            $modelMedia = $model->getMedia();

            if (count($modelMedia > 0 && $modelMedia > 1)){
                $mediaCount = count($modelMedia);
                foreach ($modelMedia as $i => $media){
                    if (($i+1) < $mediaCount){
                        $media->delete();
                    }
                }
            }
        }   
    }

    public static function svgMedia($media, $alt='')
    {   
        if ($media instanceof \Spatie\MediaLibrary\MediaCollections\Models\Media){                                 
            if (str($media->getFullUrl())->endsWith('.svg')){
                $xml = simplexml_load_file( storage_path('app/public/'.$media->id.'/'.$media->file_name) );
                echo $xml->asXML();
            } else {
                echo '<img src="'.$media->getFullUrl().'" alt="'.$alt.'">';
            }
        }
    }

    public static function jsonData($row, $json_key)
    {
        if (isset($row->json)){
            if (is_array($row['json'])){
                if (array_key_exists($json_key, $row['json'])){
                    return $row['json'][$json_key];
                }
            }
        }

        return '';
    }
}
