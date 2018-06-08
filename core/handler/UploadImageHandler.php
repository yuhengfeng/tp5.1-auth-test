<?php
namespace core\handler;

use core\exception\ImageUploadException;
use think\facade\Session;
use think\File;

class UploadImageHandler
{
    protected $file;

    /**
     * @param File $file
     * 上传头像
     */
    public function upload($file,$type = 'avatar')
    {
        $this->file = $file;
        $fileInfo = $this->file->validate($this->rules('avatar'))->rule('uniqid')->move(public_path().'/uploads/'.$type.'/');
        if (!$fileInfo)
        {
            throw new ImageUploadException($this->file->getError());
        }
        $filePath = '/uploads/'.$type.'/'.$fileInfo->getFilename();

        return ['filename' => $filePath];
    }

    /**
     * @param $type
     * @return array
     * 验证规则
     */
    public function rules($type)
    {
        switch ($type)
        {
            case 'avatar':
                return ['ext'=>'jpg,png,gif,jpeg'];
            case 'file':
                return ['ext'=>'zip,xlsx,docx,txt'];
            default:
                return ['ext'=>'jpg,png,gif'];
        }
    }

    /**
     * @param $fileFullPath
     * @return bool
     * 清除旧照片
     */
    public function clearOldImage($fileFullPath)
    {
        if (is_file($fileFullPath) && isset($fileFullPath)){
            return unlink($fileFullPath);
        }
    }
}