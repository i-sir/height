<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"CooperationApply",
 *     "name_underline"          =>"cooperation_apply",
 *     "controller_name"         =>"CooperationApply",
 *     "table_name"              =>"cooperation_apply",
 *     "remark"                  =>"合作申请"
 *     "api_url"                 =>"/api/wxapp/cooperation_apply/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-09-17 18:09:14",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\CooperationApplyController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/cooperation_apply/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/cooperation_apply/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class CooperationApplyController extends AuthController
{

    //public function initialize(){
    //	//合作申请
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/cooperation_apply/index
     * https://xcxkf173.aubye.com/api/wxapp/cooperation_apply/index
     */
    public function index()
    {
        $CooperationApplyInit  = new \init\CooperationApplyInit();//合作申请   (ps:InitController)
        $CooperationApplyModel = new \initmodel\CooperationApplyModel(); //合作申请   (ps:InitModel)

        $result = [];

        $this->success('合作申请-接口请求成功', $result);
    }


    /**
     * 合作申请 列表
     * @OA\Post(
     *     tags={"合作申请"},
     *     path="/wxapp/cooperation_apply/find_cooperation_apply_list",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/cooperation_apply/find_cooperation_apply_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/cooperation_apply/find_cooperation_apply_list
     *   api:  /wxapp/cooperation_apply/find_cooperation_apply_list
     *   remark_name: 合作申请 列表
     *
     */
    public function find_cooperation_apply_list()
    {
        $CooperationApplyInit  = new \init\CooperationApplyInit();//合作申请   (ps:InitController)
        $CooperationApplyModel = new \initmodel\CooperationApplyModel(); //合作申请   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];

        if ($params["keyword"]) $where[] = ["usernmae|phone|address|direction", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $CooperationApplyInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $CooperationApplyInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 合作申请 详情
     * @OA\Post(
     *     tags={"合作申请"},
     *     path="/wxapp/cooperation_apply/find_cooperation_apply",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/cooperation_apply/find_cooperation_apply
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/cooperation_apply/find_cooperation_apply
     *   api:  /wxapp/cooperation_apply/find_cooperation_apply
     *   remark_name: 合作申请 详情
     *
     */
    public function find_cooperation_apply()
    {
        $CooperationApplyInit  = new \init\CooperationApplyInit();//合作申请    (ps:InitController)
        $CooperationApplyModel = new \initmodel\CooperationApplyModel(); //合作申请   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $CooperationApplyInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


    /**
     * 合作申请 编辑&添加
     * @OA\Post(
     *     tags={"合作申请"},
     *     path="/wxapp/cooperation_apply/edit_cooperation_apply",
     *
     *
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="openid",
     *         in="query",
     *         description="身份标识 openid",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="usernmae",
     *         in="query",
     *         description="姓名",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="手机号",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="address",
     *         in="query",
     *         description="地址",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="direction",
     *         in="query",
     *         description="合作方向",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id空添加,存在编辑",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/cooperation_apply/edit_cooperation_apply
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/cooperation_apply/edit_cooperation_apply
     *   api:  /wxapp/cooperation_apply/edit_cooperation_apply
     *   remark_name: 合作申请 编辑&添加
     *
     */
    public function edit_cooperation_apply()
    {
        $CooperationApplyInit  = new \init\CooperationApplyInit();//合作申请    (ps:InitController)
        $CooperationApplyModel = new \initmodel\CooperationApplyModel(); //合作申请   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;


        /** 检测参数信息 **/
        $validateResult = $this->validate($params, 'CooperationApply');
        if ($validateResult !== true) $this->error($validateResult);

        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];


        /** 提交更新 **/
        $result = $CooperationApplyInit->api_edit_post($params, $where);
        if (empty($result)) $this->error("失败请重试");


        if (empty($params["id"])) $msg = "添加成功";
        if (!empty($params["id"])) $msg = "编辑成功";
        $this->success($msg);
    }


}
