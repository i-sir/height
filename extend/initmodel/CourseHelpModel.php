<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"CourseHelp",
    *     "name_underline"   =>"course_help",
    *     "table_name"       =>"course_help",
    *     "model_name"       =>"CourseHelpModel",
    *     "remark"           =>"训练帮助",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-19 17:47:59",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CourseHelpModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CourseHelpModel extends Model{

	protected $name = 'course_help';//训练帮助

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
