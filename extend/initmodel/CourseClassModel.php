<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"CourseClass",
    *     "name_underline"   =>"course_class",
    *     "table_name"       =>"course_class",
    *     "model_name"       =>"CourseClassModel",
    *     "remark"           =>"分类管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-19 16:44:49",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CourseClassModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CourseClassModel extends Model{

	protected $name = 'course_class';//分类管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
