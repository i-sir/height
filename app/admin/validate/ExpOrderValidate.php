<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"ExpOrder",
    *     "name_underline"   =>"exp_order",
    *     "table_name"       =>"exp_order",
    *     "validate_name"    =>"ExpOrderValidate",
    *     "remark"           =>"订单管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-17 15:53:12",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, ExpOrder);
    * )
    */

class ExpOrderValidate extends Validate
{

protected $rule = [];




protected $message = [];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
