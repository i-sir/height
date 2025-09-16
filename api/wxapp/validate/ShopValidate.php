<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Shop",
    *     "name_underline"   =>"shop",
    *     "table_name"       =>"shop",
    *     "validate_name"    =>"ShopValidate",
    *     "remark"           =>"店铺管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-16 11:11:22",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Shop);
    * )
    */

class ShopValidate extends Validate
{

protected $rule = [];




protected $message = [];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
