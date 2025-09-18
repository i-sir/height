<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Course",
    *     "name_underline"   =>"course",
    *     "table_name"       =>"course",
    *     "validate_name"    =>"CourseValidate",
    *     "remark"           =>"课程计划",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-18 11:42:28",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Course);
    * )
    */

class CourseValidate extends Validate
{

protected $rule = ['name'=>'require',
'introduce'=>'require',
];




protected $message = ['name.require'=>'名称不能为空!',
'introduce.require'=>'介绍不能为空!',
];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
