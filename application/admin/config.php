<?php
//配置文件
return [
    'pwd_auth_key'	=>	'6BCt9uZTauHbtVvW',	//用户密码加密密钥
    'user_administrator'	=>	1, //超级管理员ID

    'view_replace_str'=>[
        '__STATIC__' => '/public/static',
        '__IMG__'    => '/public/' . \think\Request::instance()->module() . '/img',
        '__CSS__'    => '/public/' . \think\Request::instance()->module() . '/css',
        '__JS__'     => '/public/' . \think\Request::instance()->module() . '/scripts',
        '__PUBLIC__' => '/public',
    ],

    'page_size' => 15, //分页数量

    //上传配置
    'picture_size'	=> 2097152,			//允许上传图片大小
    'picture_ext'	=> 'jpg,png,gif,tmp',	//允许上传图片格式
    'upload_full_path'	=> ROOT_PATH . 'public' . DS . 'uploads' . DS,	//上传目录（绝对路径）
    'upload_path'	=> DS . 'public' . DS . 'uploads' . DS,	//上传目录（相对路径）
];