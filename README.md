Laravel 5  UEditor
=============

[UEditor](http://ueditor.baidu.com) 是由百度web前端研发部开发所见即所得富文本web编辑器

此包为laravel5的支持,新增多语言配置,可自由部署前端代码,默认基于 UEditor 1.4.3.1

UEditor 前台文件完全无修改,可自由gulp等工具部署到生产环境
 
根据系统的config.app.locale自动切换多语言. 暂时只支持 en,zh_CN,zh_TW

支持本地和阿里云存储,默认为本地上传 public/uploads

##Requirement
* Lavavel5.1 LTS, for Lavavel5.3, I do not test, you may have a try.
* PHP5.6+, PHP7.0 is suitable
* Composer

##Installation
* Run the command in your laravel project home.

```composer require menthe/aliyun-ueditor -vvv
```
* Copy the provider line into your config/app.php

```Harris\UEditor\UEditorServiceProvider::class,
```
* Then run the command.

```php artisan vendor:publish
```
* Change the config file -> config/UEditorUpload.php

* Include the line in the blade template file, like template.blade.php, and you have the ueditor static file included.

```@include('ueditor::head');
```
* Init the ueditor in Javascript.

```<!-- 加载编辑器的容器 -->
<script id="container" name="content" type="text/plain">
    这里写你的初始化内容
</script>

<!-- 实例化编辑器 -->
<script type="text/javascript">
    var ue = UE.getEditor('container');
        ue.ready(function() {
        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.    
    });
</script>
```

##ChangeLog
 
 v0.1 First Release, just use for myself.
 v0.1.1 正式发布，测试通过，完善文档。
 





##License

Laravel 5  UEditor is licensed under [The MIT License (MIT)](LICENSE).


