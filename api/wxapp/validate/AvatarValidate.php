<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Avatar",
    *     "name_underline"   =>"avatar",
    *     "table_name"       =>"avatar",
    *     "validate_name"    =>"AvatarValidate",
    *     "remark"           =>"头像库",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-28 15:59:18",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Avatar);
    * )
    */

class AvatarValidate extends Validate
{

protected $rule = [];




protected $message = [];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
