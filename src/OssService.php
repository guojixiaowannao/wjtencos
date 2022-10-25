<?php

namespace wjLorine\OssUtils;


use wjLorine\OssUtils\Exception\ErrorService;
use wjLorine\OssUtils\Upload\Aliyun\Aliyun;
use wjLorine\OssUtils\Upload\Qiniu\Qiniu;
use wjLorine\OssUtils\Upload\Tencent\Tencent;

class OssService
{
    public function getOssService($type)
    {
        $obj = null;
        switch ($type) {
            case 'Aliyun':
                $obj =  new Aliyun();
                break;
            case 'Tencent':
                $obj = new Tencent();
                break;
            case 'Qiniu':
                $obj = new Qiniu();
                break;
            default:
                throw new ErrorService();
        }
        return $obj;
    }
}
