<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"BaseLike",
 *     "name_underline"          =>"base_like",
 *     "controller_name"         =>"BaseLike",
 *     "table_name"              =>"base_like",
 *     "remark"                  =>"点赞&收藏"
 *     "api_url"                 =>"/api/wxapp/base_like/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-04-10 17:44:07",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\BaseLikeController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/base_like/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/base_like/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class BaseLikeController extends AuthController
{

    //public function initialize(){
    //	//点赞&收藏
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/base_like/index
     * https://xcxkf173.aubye.com/api/wxapp/base_like/index
     */
    public function index()
    {
        $BaseLikeInit  = new \init\BaseLikeInit();//点赞&收藏   (ps:InitController)
        $BaseLikeModel = new \initmodel\BaseLikeModel(); //点赞&收藏   (ps:InitModel)

        $result = [];

        $this->success('点赞&收藏-接口请求成功', $result);
    }


    /**
     * 点赞&收藏 列表
     * @OA\Post(
     *     tags={"点赞&收藏"},
     *     path="/wxapp/base_like/find_like_list",
     *
     *
     *
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="(选填)关键字搜索",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="is_paginate",
     *         in="query",
     *         description="false=分页(不传默认分页),true=不分页",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="openid",
     *         in="query",
     *         description="openid",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="类型:goods_class=分类,默认不用传",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://height.ikun:9090/api/wxapp/base_like/find_like_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/base_like/find_like_list
     *   api:  /wxapp/base_like/find_like_list
     *   remark_name: 点赞&收藏 列表
     *
     */
    public function find_like_list()
    {
        $BaseLikeInit  = new \init\BaseLikeInit();//点赞&收藏   (ps:InitController)
        $BaseLikeModel = new \initmodel\BaseLikeModel(); //点赞&收藏   (ps:InitModel)


        //参数
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;
        $params["type"]    = $params["type"] ?? 'goods_class';

        //查询条件
        $where   = [];
        $where[] = ['l.id', '>', 0];
        $where[] = ["l.type", "=", $params["type"] ?? 'goods_class'];
        $where[] = ["l.user_id", "=", $this->user_id];


        $result = $BaseLikeInit->get_join_list($where, $params);
        if (empty($result)) $this->success("暂无信息!", []);

        $this->success("请求成功!", $result);
    }


    /**
     * 点赞&收藏 点赞,取消点赞
     * @OA\Post(
     *     tags={"点赞&收藏"},
     *     path="/wxapp/base_like/edit_like",
     *
     *
     *
     *    @OA\Parameter(
     *         name="openid",
     *         in="query",
     *         description="openid",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="类型:goods_class=分类,默认不用传",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="pid",
     *         in="query",
     *         description="关联id",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *
     *
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://height.ikun:9090/api/wxapp/base_like/edit_like
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/base_like/edit_like
     *   api:  /wxapp/base_like/edit_like
     *   remark_name: 点赞&收藏  点赞,取消点赞
     *
     */
    public function edit_like()
    {
        $this->checkAuth();
        $BaseLikeInit = new \init\BaseLikeInit();//点赞&收藏    (ps:InitController)


        //参数
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;
        $params['type']    = $params['type'] ?? 'goods_class';

        //处理 点赞数量
        if ($params['type'] == 'community') $Model = 0; //论坛管理   (ps:InitModel)

        //操作点赞数量的类型
        $operate_like_count = ['community'];


        //检测是否已收藏,如果收藏了取消,如果未收藏则添加
        $where   = [];
        $where[] = ['user_id', '=', $this->user_id];
        $where[] = ['pid', '=', $params['pid']];
        $where[] = ['type', '=', $params['type'] ?? 'goods_class'];
        $is_like = $BaseLikeInit->get_find($where);
        if ($is_like) {
            $update['delete_time'] = time();
            $BaseLikeInit->edit_post($update, $where);

            //处理点赞数量
            // if (in_array($params['type'], $operate_like_count)) $Model->where('id', '=', $params['pid'])->dec('like_count')->update();
            $this->success("取消成功");
        } else {
            $BaseLikeInit->edit_post($params);

            //处理点赞数量
            //if (in_array($params['type'], $operate_like_count)) $Model->where('id', '=', $params['pid'])->inc('like_count')->update();
            $this->success("操作成功");
        }


    }


}
