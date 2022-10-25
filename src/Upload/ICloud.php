<?php

namespace wjLorine\OssUtils\Upload;

interface ICloud
{
    public function uploadFile($config, $tmpName, $fileName);
}
