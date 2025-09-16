<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Shop",
    *     "name_underline"   =>"shop",
    *     "table_name"       =>"shop",
    *     "model_name"       =>"ShopModel",
    *     "remark"           =>"店铺管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-16 11:11:22",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\ShopModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class ShopModel extends Model{

	protected $name = 'shop';//店铺管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
