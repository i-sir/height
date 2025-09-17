<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Cooperation",
    *     "name_underline"   =>"cooperation",
    *     "table_name"       =>"cooperation",
    *     "validate_name"    =>"CooperationValidate",
    *     "remark"           =>"合作申请",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-17 10:47:34",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Cooperation);
    * )
    */

class CooperationValidate extends Validate
{

protected $rule = [];




protected $message = [];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
