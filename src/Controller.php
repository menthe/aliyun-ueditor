<?php namespace Harris\UEditor;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Harris\UEditor\Uploader\UploadScrawl;
use Harris\UEditor\Uploader\UploadFile;
use Harris\UEditor\Uploader\UploadCatch;
use Harris\AliyunOSS\OSSUtils;
use Illuminate\Support\Facades\Input;

class Controller extends BaseController {

    public function __construct() {

    }

    public function server(Request $request) {
        $config = config('UEditorUpload.upload');
        $action = $request->get('action');

        switch ($action) {
            case 'config':
                $result = $config;
                break;
            case 'uploadimage':
                $upConfig = array(
                    "pathFormat" => $config['imagePathFormat'],
                    "maxSize" => $config['imageMaxSize'],
                    "allowFiles" => $config['imageAllowFiles'],
                    'fieldName' => $config['imageFieldName'],
                );
                $result = with(new UploadFile($upConfig, $request))->upload();
                break;
            case 'uploadscrawl':
                $upConfig = array(
                    "pathFormat" => $config['scrawlPathFormat'],
                    "maxSize" => $config['scrawlMaxSize'],
                    //   "allowFiles" => $config['scrawlAllowFiles'],
                    "oriName" => "scrawl.png",
                    'fieldName' => $config['scrawlFieldName'],
                );
                $result = with(new UploadScrawl($upConfig, $request))->upload();
                break;
            case 'uploadvideo':
                $upConfig = array(
                    "pathFormat" => $config['videoPathFormat'],
                    "maxSize" => $config['videoMaxSize'],
                    "allowFiles" => $config['videoAllowFiles'],
                    'fieldName' => $config['videoFieldName'],
                );
                $result = with(new UploadFile($upConfig, $request))->upload();

                break;
            case 'uploadfile':
                $upConfig = array(
                    "pathFormat" => $config['filePathFormat'],
                    "maxSize" => $config['fileMaxSize'],
                    "allowFiles" => $config['fileAllowFiles'],
                    'fieldName' => $config['fileFieldName'],
                );
                $result = with(new UploadFile($upConfig, $request))->upload();

                break;

            /* 列出图片 */
            case 'listimage':
                if (config('UEditorUpload.core.mode') == 'aliyun-oss') {
                	$files = OSSUtils::getAllObjectUrls();
                	$result = [
                		"state" => "SUCCESS",
                		"list" => $files,
                		"start" => 0,
                		"total" => count($files)
                	];
                } 

                break;
            /* 列出文件 */
            case 'listfile':
                if (config('UEditorUpload.core.mode') == 'aliyun-oss') {
                    $files = OSSUtils::getAllObjectUrls();
                    $result = [
                    	"state" => "SUCCESS",
                    	"list" => $files,
                    	"start" => 0,
                    	"total" => count($files)
                    ];
                }
                
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $upConfig = array(
                    "pathFormat" => $config['catcherPathFormat'],
                    "maxSize" => $config['catcherMaxSize'],
                    "allowFiles" => $config['catcherAllowFiles'],
                    "oriName" => "remote.png",
                    'fieldName' => $config['catcherFieldName'],
                );

                $sources = Input::get($upConfig['fieldName']);
                $list = [];
                foreach ($sources as $imgUrl) {
                    $upConfig['imgUrl'] = $imgUrl;
                    $info = with(new UploadCatch($upConfig, $request))->upload();

                    array_push($list, array(
                        "state" => $info["state"],
                        "url" => $info["url"],
                        "size" => $info["size"],
                        "title" => htmlspecialchars($info["title"]),
                        "original" => htmlspecialchars($info["original"]),
                        "source" => htmlspecialchars($imgUrl)
                    ));
                }
                $result = [
                    'state' => count($list) ? 'SUCCESS' : 'ERROR',
                    'list' => $list
                ];
                break;
        }
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }

}
