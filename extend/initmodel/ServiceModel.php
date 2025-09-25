<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Service",
    *     "name_underline"   =>"service",
    *     "table_name"       =>"service",
    *     "model_name"       =>"ServiceModel",
    *     "remark"           =>"客服管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-25 17:08:42",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\ServiceModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class ServiceModel extends Model{

	protected $name = 'service';//客服管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
