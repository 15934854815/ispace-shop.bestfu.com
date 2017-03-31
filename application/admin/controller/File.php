<?php
/**
 * 文件上传controller
 * @author liangjian
 * @email liangjian@bestfu.com
 */
namespace app\admin\controller;
use app\admin\controller\Base;

class File extends Base
{
	public function _initialize(){
		parent::_initialize();
	}
	
	/**
	 * keditor编辑器上传图片
	 */
	public function keditor_upload_picture(){
		$file = request()->file('imgFile');
		/* 返回标准数据 */
		$info = $file
				->validate(['size'=>config('picture_size'),'ext'=>config('picture_ext')])
				->move(config('upload_full_path'));
		if($info){
			$return['error']	= 0;
			$return['url']		= str_replace("\\", "/", config('upload_path') . $info->getSaveName());
	    }else{
	        // 上传失败获取错误信息
			$return['error']	= 1;
			$return['message']	= $file->getError();
	    }
		return json($return);
	}
}
