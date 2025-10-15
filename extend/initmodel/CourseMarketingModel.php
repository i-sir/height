<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"CourseMarketing",
    *     "name_underline"   =>"course_marketing",
    *     "table_name"       =>"course_marketing",
    *     "model_name"       =>"CourseMarketingModel",
    *     "remark"           =>"营销管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-14 17:49:29",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CourseMarketingModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CourseMarketingModel extends Model{

	protected $name = 'course_marketing';//营销管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
