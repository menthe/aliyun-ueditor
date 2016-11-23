<?php

namespace Stevenyangecho\UEditor;

use JohnLui\AliyunOSS\AliyunOSS;

class OSSUtils {
	
	
	private $ossClient;
	
	public function __construct($isInternal = false) {
// 		$isInternal = Config::get('app.debug');
		$serverAddress = $isInternal ? config('UEditorUpload.core.aliyun-oss.ossServerInternal') : config('UEditorUpload.core.aliyun-oss.ossServer');
		$this->ossClient = AliyunOSS::boot($serverAddress, config('UEditorUpload.core.aliyun-oss.accessKeyId'), config('UEditorUpload.core.aliyun-oss.accessKeySecret'));
	}
	
	public static function upload($ossKey, $filePath) {
		$oss = new OSSUtils();
		$oss->ossClient->setBucket(config('UEditorUpload.core.aliyun-oss.ossBucket'));
		return $oss->ossClient->uploadFile($ossKey, $filePath);
	}
	
	public static function uploadContent($osskey, $content) {
		$oss = new OSSUtils();
		$oss->ossClient->setBucket(config('UEditorUpload.core.aliyun-oss.ossBucket'));
		return $oss->ossClient->uploadContent($osskey, $content);
	}
	
	/**
	 * 删除存储在oss中的文件
	 *
	 */
	public static function deleteObject($ossKey) {
		$oss = new OSSUtils();
		return $oss->ossClient->deleteObject(config('UEditorUpload.core.aliyun-oss.ossBucket'), $ossKey);
	}
	
	public function copyObject($sourceBuckt, $sourceKey, $destBucket, $destKey) {
		$oss = new OSSUtils(); 
		return $oss->ossClient->copyObject($sourceBuckt, $sourceKey, $destBucket, $destKey);
	}
	
	public function moveObject($sourceBuckt, $sourceKey, $destBucket, $destKey) {
		$oss = new OSSUtils(true);
		return $oss->ossClient->moveObject($sourceBuckt, $sourceKey, $destBucket, $destKey);
	}
	
	public static function getUrl($ossKey) {
		$oss = new OSSUtils();
		$oss->ossClient->setBucket(config('UEditorUpload.core.aliyun-oss.ossBucket'));
		return $oss->ossClient->getUrl($ossKey, new \DateTime ( "+1 day" ));
	}
	
	public static function createBucket($bucketName) {
		$oss = new OSSUtils();
		return $oss->ossClient->createBucket($bucketName);
	}
	
	public static function getAllObjectKey($bucketName, $folder) {
		$oss = new OSSUtils();
		return $oss->ossClient->getAllObjectKeyWithPrefix($bucketName, $folder);
	}
	
	public static function getAllObjectUrls($bucketName, $folder) {
		$objectKeys = OSSUtils::getAllObjectKey($bucketName, $folder);
		$data = [];
		foreach ($objectKeys as $objectKey) {
			$data[count($data)] = [
				'url' => config('UEditorUpload.core.aliyun-oss.staticEndPoint') . $objectKey,
			];
		}
		return $data;
	}
}
