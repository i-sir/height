<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"ShopClass",
    *     "name_underline"   =>"shop_class",
    *     "table_name"       =>"shop_class",
    *     "validate_name"    =>"ShopClassValidate",
    *     "remark"           =>"店铺类型",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-16 11:17:45",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, ShopClass);
    * )
    */

class ShopClassValidate extends Validate
{

protected $rule = ['name'=>'require',
];




protected $message = ['name.require'=>'名称不能为空!',
];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
