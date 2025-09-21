<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"CourseOrder",
    *     "name_underline"   =>"course_order",
    *     "table_name"       =>"course_order",
    *     "model_name"       =>"CourseOrderModel",
    *     "remark"           =>"订单管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-19 15:08:29",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CourseOrderModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CourseOrderModel extends Model{

	protected $name = 'course_order';//订单管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
