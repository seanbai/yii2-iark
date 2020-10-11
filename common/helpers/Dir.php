<?php
namespace common\helpers;

use yii\base\Exception;

class Dir{
    private $_root = null;

    /**
     * Dir constructor.
     * @param null $root
     */
    public function __construct($root = null)
    {
        $this->_root = $root;
    }

    /**
     * @param $root
     * @return $this
     */
    public function setRoot($root){
        $this->_root = trim($root, '\/');
        return $this;
    }

    /**
     * @param string $path
     * @param bool $recursive
     * @return array
     */
    public function readFile($path='/', $recursive=true){
        $path = trim($path, '\/');

        $data = [];
        $this->_read($data, $path, $recursive);

        return $data;
    }

    /**
     * @param array $data
     * @param string $path
     * @param bool $recursive
     */
    private function _read(&$data=[], $path='', $recursive=true){
        if (!empty($path)) $path = '/'.trim($path, '\/');
        if (!is_dir($this->_root.$path)) throw new Exception("'$this->_root$path' is not a dir");

        $resource = opendir($this->_root.$path);
        while ($row = readdir($resource)){
            if ($row=='.' || $row=='..') continue;
            $row = '/'.$row;
            $tmp = $this->_root.$path.$row;
            if (is_dir($tmp) && $recursive){
                static::_read($data, $path.$row, $recursive);
            }elseif(is_file($tmp)){
                $data[] = $path.$row;
            }
        }
    }

    /**
     * @param $path
     * @param string $diversion_type
     * @param int $mod
     * @return string
     */
    public function getPath($path, $diversion_type='M', $mod=0777){
        $dir = '/';
        switch ($diversion_type){
            case 'M':
                $dir .= date('Y-m');
                break;
            case 'Y':
                $dir .= date('Y');
                break;
        }

        $path = rtrim($path, '\/').$dir;
        if (!is_dir($this->_root.$path))
            mkdir($this->_root.$path, $mod,true);

        return $path;
    }
}