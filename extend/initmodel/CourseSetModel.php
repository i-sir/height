<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"CourseSet",
    *     "name_underline"   =>"course_set",
    *     "table_name"       =>"course_set",
    *     "model_name"       =>"CourseSetModel",
    *     "remark"           =>"收费配置",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-28 16:37:01",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CourseSetModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CourseSetModel extends Model{

	protected $name = 'course_set';//收费配置

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
