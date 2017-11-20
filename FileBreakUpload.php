<?php

/**
 * 分片上传工具类
 */
class FileBreakUpload
{

    private $filePath = './upload';//上传目录

    private $tmpPath = './tmp';//上传临时目录

    private $blobNum; //第几块文件

    private $totalBlobNum;//文件块总数

    private $fileName; //文件名

    public function __construct($filePath, $tmpPath, $blobNum, $totalBlobNum, $fileName)
    {
        $this->filePath = $filePath;
        $this->tmpPath = $tmpPath;
        $this->blobNum = $blobNum;
        $this->totalBlobNum;
        $this->fileName = $fileName;
        $this->uploadBlobFile();
        $this->mergeUploadFile();
    }

    /**
     * 创建目录
     */
    private function createUplaodDirByPath()
    {
        if (!file_exists($this->filePath)){
            mkdir($this->filePath, 777);
        }
    }

    /**
     * 上传分片文件
     */
    private function uploadBlobFile()
    {
        $this->createUplaodDirByPath();
        $fileBolbName = $this->filePath . '/'. $this->fileName. '__'. $this->blobNum;
        move_uploaded_file($this->tmpPath, $fileBolbName);
    }

    private function mergeUploadFile()
    {
        if ($this->blobNum == $this->totalBlobNum){
            $blob = '';
            for ($i=1; $i<=$this->totalBlobNum; $i++){
                $blob .= file_get_contents($this->filePath. '/'. $this->fileName. '__'. $i);
            }
            file_put_contents($this->filePath. '/'. $this->fileName, $blob);
        }
    }

    private function deleteBlobFile()
    {
        for ($i=1; $i<=$this->totalBlobNum; $i++){
            @unlink($this->filePath . '/'. $this->fileName. '__'.$i);
        }
    }
}