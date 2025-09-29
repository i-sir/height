<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"CourseSet",
    *     "name_underline"   =>"course_set",
    *     "table_name"       =>"course_set",
    *     "validate_name"    =>"CourseSetValidate",
    *     "remark"           =>"收费配置",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-28 16:37:01",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, CourseSet);
    * )
    */

class CourseSetValidate extends Validate
{

protected $rule = ['name'=>'require',
'price'=>'require',
];




protected $message = ['name.require'=>'备注不能为空!',
'price.require'=>'价格不能为空!',
];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
