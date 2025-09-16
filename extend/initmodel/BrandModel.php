<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Brand",
    *     "name_underline"   =>"brand",
    *     "table_name"       =>"brand",
    *     "model_name"       =>"BrandModel",
    *     "remark"           =>"品牌动态",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-16 10:43:31",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\BrandModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class BrandModel extends Model{

	protected $name = 'brand';//品牌动态

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
