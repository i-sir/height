<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"CooperationApply",
    *     "name_underline"   =>"cooperation_apply",
    *     "table_name"       =>"cooperation_apply",
    *     "model_name"       =>"CooperationApplyModel",
    *     "remark"           =>"合作申请",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-17 18:09:14",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CooperationApplyModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CooperationApplyModel extends Model{

	protected $name = 'cooperation_apply';//合作申请

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
