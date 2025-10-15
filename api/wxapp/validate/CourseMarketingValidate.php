<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"CourseMarketing",
    *     "name_underline"   =>"course_marketing",
    *     "table_name"       =>"course_marketing",
    *     "validate_name"    =>"CourseMarketingValidate",
    *     "remark"           =>"营销管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-14 17:49:29",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, CourseMarketing);
    * )
    */

class CourseMarketingValidate extends Validate
{

protected $rule = [];




protected $message = [];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
