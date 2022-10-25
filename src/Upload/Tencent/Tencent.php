<?php

namespace wjLorine\OssUtils\Upload\Tencent;



use wjLorine\OssUtils\Upload\ICloud;
use Qcloud\Cos\Client;

class Tencent implements ICloud
{
    //$tmpName 要求文件地址
    public function uploadFile($config, $tmpName, $fileName)
    {
        // TODO: Implement uploadFile() method.

        try {
            $cosClient = new Client(
                array(
                    'region' => $config['region'],
                    'schema' => 'https', //协议头部，默认为http
                    'credentials' => array(
                        'secretId' => $config['ak'],
                        'secretKey' => $config['sk']
                    )
                )
            );

            $cosClient->Upload(
                $bucket = $config['bucket'],
                $key = $fileName,
                $body = fopen($tmpName, 'rb')
            );
            $data = ['code' => 0, 'msg' => 'success', 'data' => $fileName];
        } catch (\Exception $e) {
            $data = ['code' => 1, 'msg' => 'error', 'data' => ['result' => $e->getMessage()]];
        }
        return json_encode($data);
    }
    //$tmpName 上传流文件
    public function uploadFiles($config, $tmpName, $fileName)
    {
        // TODO: Implement uploadFile() method.

        try {
            $cosClient = new Client(
                array(
                    'region' => $config['region'],
                    'schema' => 'https', //协议头部，默认为http
                    'credentials' => array(
                        'secretId' => $config['ak'],
                        'secretKey' => $config['sk']
                    )
                )
            );

            $cosClient->Upload(
                $bucket = $config['bucket'],
                $key = $fileName,
                $body = $tmpName
            );
            $data = ['code' => 0, 'msg' => 'success', 'data' => $fileName];
        } catch (\Exception $e) {
            $data = ['code' => 1, 'msg' => 'error', 'data' => ['result' => $e->getMessage()]];
        }
        return json_encode($data);
    }
    //上传文件夹
    public function uploadpathFile($config, $path)
    {
        // TODO: Implement uploadFile() method.
        try {
            $cosClient = new Client(
                array(
                    'region' => $config['region'],
                    'schema' => 'https', //协议头部，默认为http
                    'credentials' => array(
                        'secretId' => $config['ak'],
                        'secretKey' => $config['sk']
                    )
                )
            );
            $datas = $this->uploadpahtfiles($path, $cosClient, $config['bucket']);
            $data = ['code' => 0, 'msg' => 'success', 'data' => $datas];
        } catch (\Exception $e) {
            $data = ['code' => 1, 'msg' => 'error', 'data' => ['result' => $e->getMessage()]];
        }
        return $data;
    }
    //取本地文件上传cos生成对象
    public function uploadpahtfiles($path, $cosClient, $buckets)
    {
        $data = [];
        foreach (scandir($path) as $afile) {
            if ($afile == '.' || $afile == '..') continue;
            if (is_dir($path . '/' . $afile)) {
                $this->uploadpahtfiles($path . '/' . $afile, $cosClient, $buckets);
            } else {
                $local_file_path = $path . '/' . $afile;
                $cos_file_path = $local_file_path;
                // 按照需求自定义拼接上传路径
                try {
                    $is_true = $cosClient->upload(
                        $bucket = $buckets, //存储桶名称，由BucketName-Appid 组成，可以在COS控制台查看 https://console.cloud.tencent.com/cos5/bucket
                        $key = $cos_file_path,
                        $body = fopen($cos_file_path, 'rb')
                    );
                    array_push($data, $is_true['Location']);
                } catch (\Exception $e) {
                    echo ($e);
                }
            }
        }
        return $data;
    }
    //删除cos对象
    // config 配置
    // key 对象地址
    public function del_file($config, $key)
    {
        try {
            $cosClient = new Client(
                array(
                    'region' => $config['region'],
                    'schema' => 'https', //协议头部，默认为http
                    'credentials' => array(
                        'secretId' => $config['ak'],
                        'secretKey' => $config['sk']
                    )
                )
            );
            $result = $cosClient->deleteObject(array(
                'Bucket' => $config['bucket'], //存储桶名称，由BucketName-Appid 组成，可以在COS控制台查看 https://console.cloud.tencent.com/cos5/bucket
                'Key' => $key //若多路径则写为folder/exampleobject，不要在第一层带/，否则删除会失败
            ));
            $data = ['code' => 0, 'msg' => 'success', 'data' => $result];
        } catch (\Exception $e) {
            $data = ['code' => 1, 'msg' => 'error', 'data' => ['result' => $e->getMessage()]];
        }
        return $data;
    }
}
