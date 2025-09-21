<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"CourseClass",
    *     "name_underline"   =>"course_class",
    *     "table_name"       =>"course_class",
    *     "validate_name"    =>"CourseClassValidate",
    *     "remark"           =>"分类管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-19 16:44:49",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, CourseClass);
    * )
    */

class CourseClassValidate extends Validate
{

protected $rule = ['name'=>'require',
'image'=>'require',
];




protected $message = ['name.require'=>'名称不能为空!',
'image.require'=>'图片不能为空!',
];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
