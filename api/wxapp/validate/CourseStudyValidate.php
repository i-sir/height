<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"CourseStudy",
    *     "name_underline"   =>"course_study",
    *     "table_name"       =>"course_study",
    *     "validate_name"    =>"CourseStudyValidate",
    *     "remark"           =>"学习记录",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-21 17:22:41",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, CourseStudy);
    * )
    */

class CourseStudyValidate extends Validate
{

protected $rule = [];




protected $message = [];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
