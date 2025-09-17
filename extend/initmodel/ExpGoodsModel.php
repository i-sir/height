<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"ExpGoods",
    *     "name_underline"   =>"exp_goods",
    *     "table_name"       =>"exp_goods",
    *     "model_name"       =>"ExpGoodsModel",
    *     "remark"           =>"体验卡",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-17 15:49:28",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\ExpGoodsModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class ExpGoodsModel extends Model{

	protected $name = 'exp_goods';//体验卡

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
