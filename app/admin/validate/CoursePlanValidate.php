<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"CoursePlan",
    *     "name_underline"   =>"course_plan",
    *     "table_name"       =>"course_plan",
    *     "validate_name"    =>"CoursePlanValidate",
    *     "remark"           =>"计划管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-18 16:53:49",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, CoursePlan);
    * )
    */

class CoursePlanValidate extends Validate
{

protected $rule = ['name'=>'require',
];




protected $message = ['name.require'=>'名称不能为空!',
];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
