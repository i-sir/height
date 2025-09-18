<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Course",
    *     "name_underline"   =>"course",
    *     "table_name"       =>"course",
    *     "model_name"       =>"CourseModel",
    *     "remark"           =>"课程计划",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-18 11:42:28",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CourseModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CourseModel extends Model{

	protected $name = 'course';//课程计划

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
