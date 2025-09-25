<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Make",
    *     "name_underline"   =>"make",
    *     "table_name"       =>"make",
    *     "validate_name"    =>"MakeValidate",
    *     "remark"           =>"预约记录",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-25 17:03:26",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Make);
    * )
    */

class MakeValidate extends Validate
{

protected $rule = [];




protected $message = [];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
