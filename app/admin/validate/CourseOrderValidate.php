<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"CourseOrder",
    *     "name_underline"   =>"course_order",
    *     "table_name"       =>"course_order",
    *     "validate_name"    =>"CourseOrderValidate",
    *     "remark"           =>"订单管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-19 15:08:29",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, CourseOrder);
    * )
    */

class CourseOrderValidate extends Validate
{

protected $rule = [];




protected $message = [];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
