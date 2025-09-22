<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"CourseStudy",
    *     "name_underline"   =>"course_study",
    *     "table_name"       =>"course_study",
    *     "model_name"       =>"CourseStudyModel",
    *     "remark"           =>"学习记录",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-21 17:22:41",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CourseStudyModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CourseStudyModel extends Model{

	protected $name = 'course_study';//学习记录

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
