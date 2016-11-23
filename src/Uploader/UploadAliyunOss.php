<?php namespace Stevenyangecho\UEditor\Uploader;

use Stevenyangecho\UEditor\OSSUtils;


/**
 *
 *
 * trait UploadAliyunOss
 *
 * 阿里云 上传 类
 *
 * @package Stevenyangecho\UEditor\Uploader
 */
trait UploadAliyunOss
{

	public static function uploadFs($fullname, $realpath) {
		
		$start = strripos($fullname, '/')+1;
		$end = strripos($fullname, '.');
		$name = substr($fullname, $start, ($end-$start));
		$left = substr($fullname, 0, $start);
		$oExt = substr(strrchr($fullname, '.'), 1);

		$fullname = $left. md5($name). '.' . $oExt;
		OSSUtils::upload($fullname, $realpath);
		return $fullname;
	}
	
	public static function uploadContent($fullname, $content) {
		$start = strripos($fullname, '/')+1;
		$end = strripos($fullname, '.');
		$name = substr($fullname, $start, ($end-$start));
		$left = substr($fullname, 0, $start);
		$oExt = substr(strrchr($fullname, '.'), 1);
		
		$fullname = $left. md5($name). '.' . $oExt;
		OSSUtils::uploadContent($fullname, $content);
		return $fullname;
	}
	
}