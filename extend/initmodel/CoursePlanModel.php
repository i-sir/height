<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"CoursePlan",
    *     "name_underline"   =>"course_plan",
    *     "table_name"       =>"course_plan",
    *     "model_name"       =>"CoursePlanModel",
    *     "remark"           =>"计划管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-18 16:53:49",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CoursePlanModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CoursePlanModel extends Model{

	protected $name = 'course_plan';//计划管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
