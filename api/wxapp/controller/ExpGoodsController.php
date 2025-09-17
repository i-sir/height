<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"ExpGoods",
 *     "name_underline"          =>"exp_goods",
 *     "controller_name"         =>"ExpGoods",
 *     "table_name"              =>"exp_goods",
 *     "remark"                  =>"体验卡"
 *     "api_url"                 =>"/api/wxapp/exp_goods/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-09-17 15:49:28",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\ExpGoodsController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/exp_goods/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/exp_goods/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class ExpGoodsController extends AuthController
{

    //public function initialize(){
    //	//体验卡
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/exp_goods/index
     * https://xcxkf173.aubye.com/api/wxapp/exp_goods/index
     */
    public function index()
    {
        $ExpGoodsInit  = new \init\ExpGoodsInit();//体验卡   (ps:InitController)
        $ExpGoodsModel = new \initmodel\ExpGoodsModel(); //体验卡   (ps:InitModel)

        $result = [];

        $this->success('体验卡-接口请求成功', $result);
    }


    /**
     * 体验卡 列表
     * @OA\Post(
     *     tags={"体验卡"},
     *     path="/wxapp/exp_goods/find_goods_list",
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
     *
     *
     *
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://height.ikun:9090/api/wxapp/exp_goods/find_goods_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/exp_goods/find_goods_list
     *   api:  /wxapp/exp_goods/find_goods_list
     *   remark_name: 体验卡 列表
     *
     */
    public function find_goods_list()
    {
        $ExpGoodsInit  = new \init\ExpGoodsInit();//体验卡   (ps:InitController)
        $ExpGoodsModel = new \initmodel\ExpGoodsModel(); //体验卡   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["goods_name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $ExpGoodsInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $ExpGoodsInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 体验卡 详情
     * @OA\Post(
     *     tags={"体验卡"},
     *     path="/wxapp/exp_goods/find_goods",
     *
     *
     *
     *    @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://height.ikun:9090/api/wxapp/exp_goods/find_goods
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/exp_goods/find_goods
     *   api:  /wxapp/exp_goods/find_goods
     *   remark_name: 体验卡 详情
     *
     */
    public function find_goods()
    {
        $ExpGoodsInit  = new \init\ExpGoodsInit();//体验卡    (ps:InitController)
        $ExpGoodsModel = new \initmodel\ExpGoodsModel(); //体验卡   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ExpGoodsInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
