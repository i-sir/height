<?php

namespace initmodel;

/**
 * @AdminModel(
 *     "name"             =>"BaseLike",
 *     "name_underline"   =>"base_like",
 *     "table_name"       =>"base_like",
 *     "model_name"       =>"BaseLikeModel",
 *     "remark"           =>"点赞&amp;收藏",
 *     "author"           =>"",
 *     "create_time"      =>"2025-04-10 17:44:07",
 *     "version"          =>"1.0",
 *     "use"              => new \initmodel\BaseLikeModel();
 * )
 */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class BaseLikeModel extends Model
{

    protected $name = 'base_like';//点赞&amp;收藏

    //软删除
    protected $hidden            = ['delete_time'];
    protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
