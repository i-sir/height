<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"CooperationApply",
    *     "name_underline"   =>"cooperation_apply",
    *     "table_name"       =>"cooperation_apply",
    *     "validate_name"    =>"CooperationApplyValidate",
    *     "remark"           =>"合作申请",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-17 18:09:14",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, CooperationApply);
    * )
    */

class CooperationApplyValidate extends Validate
{

protected $rule = [];




protected $message = [];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
