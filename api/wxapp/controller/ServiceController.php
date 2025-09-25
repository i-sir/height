<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Service",
 *     "name_underline"          =>"service",
 *     "controller_name"         =>"Service",
 *     "table_name"              =>"service",
 *     "remark"                  =>"客服管理"
 *     "api_url"                 =>"/api/wxapp/service/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-09-25 17:08:42",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\ServiceController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/service/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/service/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class ServiceController extends AuthController
{

    //public function initialize(){
    //	//客服管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/service/index
     * https://xcxkf173.aubye.com/api/wxapp/service/index
     */
    public function index()
    {
        $ServiceInit  = new \init\ServiceInit();//客服管理   (ps:InitController)
        $ServiceModel = new \initmodel\ServiceModel(); //客服管理   (ps:InitModel)

        $result = [];

        $this->success('客服管理-接口请求成功', $result);
    }


    /**
     * 客服管理 列表
     * @OA\Post(
     *     tags={"客服管理"},
     *     path="/wxapp/service/find_service_list",
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
     *
     *    @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="类型",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
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
     *   test_environment: http://height.ikun:9090/api/wxapp/service/find_service_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/service/find_service_list
     *   api:  /wxapp/service/find_service_list
     *   remark_name: 客服管理 列表
     *
     */
    public function find_service_list()
    {
        $ServiceInit  = new \init\ServiceInit();//客服管理   (ps:InitController)
        $ServiceModel = new \initmodel\ServiceModel(); //客服管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["type|phone", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];
        if ($params['type']) $where[] = ['type', '=', $params['type']];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $ServiceInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $ServiceInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 客服管理 详情
     * @OA\Post(
     *     tags={"客服管理"},
     *     path="/wxapp/service/find_service",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/service/find_service
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/service/find_service
     *   api:  /wxapp/service/find_service
     *   remark_name: 客服管理 详情
     *
     */
    public function find_service()
    {
        $ServiceInit  = new \init\ServiceInit();//客服管理    (ps:InitController)
        $ServiceModel = new \initmodel\ServiceModel(); //客服管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ServiceInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
