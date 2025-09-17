<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"ExpGoods",
    *     "name_underline"   =>"exp_goods",
    *     "table_name"       =>"exp_goods",
    *     "validate_name"    =>"ExpGoodsValidate",
    *     "remark"           =>"体验卡",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-17 15:49:28",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, ExpGoods);
    * )
    */

class ExpGoodsValidate extends Validate
{

protected $rule = [];




protected $message = [];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
