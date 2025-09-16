<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Brand",
 *     "name_underline"          =>"brand",
 *     "controller_name"         =>"Brand",
 *     "table_name"              =>"brand",
 *     "remark"                  =>"品牌动态"
 *     "api_url"                 =>"/api/wxapp/brand/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-09-16 10:43:31",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\BrandController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/brand/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/brand/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class BrandController extends AuthController
{

    //public function initialize(){
    //	//品牌动态
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/brand/index
     * https://xcxkf173.aubye.com/api/wxapp/brand/index
     */
    public function index()
    {
        $BrandInit  = new \init\BrandInit();//品牌动态   (ps:InitController)
        $BrandModel = new \initmodel\BrandModel(); //品牌动态   (ps:InitModel)

        $result = [];

        $this->success('品牌动态-接口请求成功', $result);
    }


    /**
     * 品牌动态 列表
     * @OA\Post(
     *     tags={"品牌动态"},
     *     path="/wxapp/brand/find_brand_list",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/brand/find_brand_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/brand/find_brand_list
     *   api:  /wxapp/brand/find_brand_list
     *   remark_name: 品牌动态 列表
     *
     */
    public function find_brand_list()
    {
        $BrandInit  = new \init\BrandInit();//品牌动态   (ps:InitController)
        $BrandModel = new \initmodel\BrandModel(); //品牌动态   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $BrandInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $BrandInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 品牌动态 详情
     * @OA\Post(
     *     tags={"品牌动态"},
     *     path="/wxapp/brand/find_brand",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/brand/find_brand
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/brand/find_brand
     *   api:  /wxapp/brand/find_brand
     *   remark_name: 品牌动态 详情
     *
     */
    public function find_brand()
    {
        $BrandInit  = new \init\BrandInit();//品牌动态    (ps:InitController)
        $BrandModel = new \initmodel\BrandModel(); //品牌动态   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $BrandInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
