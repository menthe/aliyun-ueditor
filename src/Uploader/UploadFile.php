<?php

namespace Harris\UEditor\Uploader;

use Harris\UEditor\Uploader\Upload;
use App\Libs\YuuGames\FileUploadUtils;
use Carbon\Carbon;

/**
 * Class UploadFile
 * 文件/图像普通上传
 * @package Harris\UEditor\Uploader
 */

class UploadFile extends Upload {
	use UploadQiniu;
	
	public function doUpload() {
		$file = $this->request->file($this->fileField);
		if (empty ($file)) {
			$this->stateInfo = $this->getStateInfo("ERROR_FILE_NOT_FOUND");
			return false;
		}
		if (!$file->isValid()) {
			$this->stateInfo = $this->getStateInfo($file->getError());
			return false;
		}
		
		$this->file = $file;
		$this->oriName = $this->file->getClientOriginalName();
		$this->fileSize = $this->file->getSize();
		$this->fileType = $this->getFileExt();
		$this->fullName = $this->getFullName();
		$this->filePath = $this->getFilePath();
		$this->fileName = basename ($this->filePath);
		
		// 检查文件大小是否超出限制
		if (!$this->checkSize()) {
			$this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED" );
			return false;
		}
		
		// 检查是否不允许的文件格式
		if (!$this->checkType()) {
			$this->stateInfo = $this->getStateInfo ("ERROR_TYPE_NOT_ALLOWED");
			return false;
		}
		
		if (config('ueditor.core.mode') == 'local') {
			
		} else if (config('ueditor.core.mode') == 'aliyun-oss') {
			try {
				$nName = md5($this->oriName . time() . rand()) . '.' . $this->fileType;
				$ossKey = config('ueditor.core.aliyun.upload_dir') . Carbon::now()->format('Ymd') . '/' . $nName;
				$this->fullName = $ossKey
				AliyunUpload::upload($ossKey, $this->file);
				$this->stateInfo = $this->stateMap [0];
			} catch (FileException $exception ) {
				$this->stateInfo = $this->getStateInfo ( "ERROR_WRITE_CONTENT" );
				return false;
			}
		} else {
			$this->stateInfo = $this->getStateInfo ( "ERROR_UNKNOWN_MODE" );
			return false;
		}
		return true;
	}
}
