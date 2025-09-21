<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"CourseHelp",
    *     "name_underline"   =>"course_help",
    *     "table_name"       =>"course_help",
    *     "validate_name"    =>"CourseHelpValidate",
    *     "remark"           =>"训练帮助",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-19 17:47:59",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, CourseHelp);
    * )
    */

class CourseHelpValidate extends Validate
{

protected $rule = ['name'=>'require',
'describe'=>'require',
];




protected $message = ['name.require'=>'名称不能为空!',
'describe.require'=>'描述不能为空!',
];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
