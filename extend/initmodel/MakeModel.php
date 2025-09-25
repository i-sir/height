<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Make",
    *     "name_underline"   =>"make",
    *     "table_name"       =>"make",
    *     "model_name"       =>"MakeModel",
    *     "remark"           =>"预约记录",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-25 17:03:26",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\MakeModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class MakeModel extends Model{

	protected $name = 'make';//预约记录

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
