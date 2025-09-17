<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Cooperation",
    *     "name_underline"   =>"cooperation",
    *     "table_name"       =>"cooperation",
    *     "model_name"       =>"CooperationModel",
    *     "remark"           =>"合作申请",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-17 10:47:34",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CooperationModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CooperationModel extends Model{

	protected $name = 'cooperation';//合作申请

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
