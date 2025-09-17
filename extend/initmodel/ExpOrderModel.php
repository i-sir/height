<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"ExpOrder",
    *     "name_underline"   =>"exp_order",
    *     "table_name"       =>"exp_order",
    *     "model_name"       =>"ExpOrderModel",
    *     "remark"           =>"订单管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-17 15:53:12",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\ExpOrderModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class ExpOrderModel extends Model{

	protected $name = 'exp_order';//订单管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
