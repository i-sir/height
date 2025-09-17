<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Height",
    *     "name_underline"   =>"height",
    *     "table_name"       =>"height",
    *     "model_name"       =>"HeightModel",
    *     "remark"           =>"身高数据",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-17 11:10:46",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\HeightModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class HeightModel extends Model{

	protected $name = 'height';//身高数据

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
