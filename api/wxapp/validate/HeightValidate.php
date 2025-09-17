<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Height",
    *     "name_underline"   =>"height",
    *     "table_name"       =>"height",
    *     "validate_name"    =>"HeightValidate",
    *     "remark"           =>"身高数据",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-17 11:10:46",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Height);
    * )
    */

class HeightValidate extends Validate
{

protected $rule = [];




protected $message = [];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
