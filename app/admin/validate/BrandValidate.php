<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Brand",
    *     "name_underline"   =>"brand",
    *     "table_name"       =>"brand",
    *     "validate_name"    =>"BrandValidate",
    *     "remark"           =>"品牌动态",
    *     "author"           =>"",
    *     "create_time"      =>"2025-09-16 10:43:31",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Brand);
    * )
    */

class BrandValidate extends Validate
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
