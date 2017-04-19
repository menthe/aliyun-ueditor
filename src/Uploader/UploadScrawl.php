<?php


namespace Harris\UEditor\Uploader;

use Harris\UEditor\Uploader\Upload;
use Harris\AliyunOSS\OSSUtils;

/**
 * 涂鸦上传
 */
class UploadScrawl extends Upload {
	
	public function doUpload() {
		$base64Data = $this->request->get( $this->fileField);
		$img = base64_decode ($base64Data);
		if (!$img) {
			$this->stateInfo = $this->getStateInfo ( "ERROR_FILE_NOT_FOUND" );
			return false;
		}
		
		// $this->file = $file;
		
		$this->oriName = $this->config['oriName'];
		
		$this->fileSize = strlen($img);
		$this->fileType = $this->getFileExt();
		
		$this->fullName = $this->getFullName();
		
		$this->filePath = $this->getFilePath();
		
		$this->fileName = basename ($this->filePath);
		$dirname = dirname($this->filePath);
		
		// 检查文件大小是否超出限制
		if (!$this->checkSize()) {
			$this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
			return false;
		}
		
		if (config('UEditorUpload.core.mode') == 'aliyun-oss') {
			$path = OSSUtils::doUploadContent($img);
			if ($path) {
				$this->fullName = config('fileuploads.aliyun-oss.ossPrefix') . $path;
				$this->stateInfo = $this->stateMap [0];
				return true;
			} else {
				$this->stateInfo = $this->getStateInfo("ERROR_UNKNOWN_MODE");
				return false;
			}
		} else {
			$this->stateInfo = $this->getStateInfo("ERROR_UNKNOWN_MODE");
			return false;
		}
	}
	
	/**
	 * 获取文件扩展名
	 * 
	 * @return string
	 */
	protected function getFileExt() {
		return strtolower(strrchr($this->oriName, '.'));
	}
}