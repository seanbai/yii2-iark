<?php
namespace common\helpers;

class Image{
    /**
     * @param $image_data
     * @param $path
     */
    public static function getImageInfo($image_data, $path){
        $rtn = [
            'width' => 0,
            'height' => 0,
            'suffix' => '',
            'size' => 0,
        ];

        $image_info = getimagesizefromstring($image_data['body']);

        if ($image_info){
            $rtn = [
                'width' => $image_info[0],
                'height' => $image_info[1],
                'suffix' => self::_getImageType($image_info[2]),
            ];
        }

        //$rtn['size'] = count($image_data);
        if (!empty($image_data['name'])){
            $rtn['name'] = $image_data['name'];
        }else{
            $rtn['name'] = self::_getName($path);
        }

        return $rtn;
    }

    /**
     * @param $int
     * @return mixed|string
     */
    private static function _getImageType($int){
        $types = [
            1 => 'gif',
            2 => 'jpg',
            3 => 'png',
            4 => 'swf',
            5 => 'psd',
            6 => 'bmp',
            7 => 'tiff_ii',
            8 => 'tiff_mm',
            9 => 'JPC',
            10 => 'JP2',
            11 => 'JPX',
            12 => 'JB2',
            13 => 'SWC',
            14 => 'IFF',
            15 => 'WBMP',
            16 => 'XBM',
            17 => 'ICO',
            18 => 'WEBP',
        ];

        return $types[$int] ?? 'unknown';
    }

    /**
     * 由图片路径找到图片名称
     * @param $path
     */
    private static function _getName($path){
        $path = parse_url($path);
        $path = $path['path'];

        $pos = strrpos($path, '/');

        return substr($path, $pos + 1);
    }
}