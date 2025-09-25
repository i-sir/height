<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Service",
    *     "name_underline"   =>"service",
    *     "table_name"       =>"service",
    *     "validate_name"    =>"ServiceValidate",
    *     "remark"           =>"客服管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-25 17:08:42",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Service);
    * )
    */

class ServiceValidate extends Validate
{

protected $rule = ['type'=>'require',
'phone'=>'require',
'time'=>'require',
];




protected $message = ['type.require'=>'类型不能为空!',
'phone.require'=>'客服电话不能为空!',
'time.require'=>'工作时间不能为空!',
];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
