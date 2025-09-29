<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Avatar",
    *     "name_underline"   =>"avatar",
    *     "table_name"       =>"avatar",
    *     "model_name"       =>"AvatarModel",
    *     "remark"           =>"头像库",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-28 15:59:18",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\AvatarModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class AvatarModel extends Model{

	protected $name = 'avatar';//头像库

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
