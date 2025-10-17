<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Make",
 *     "name_underline"          =>"make",
 *     "controller_name"         =>"Make",
 *     "table_name"              =>"make",
 *     "remark"                  =>"预约记录"
 *     "api_url"                 =>"/api/wxapp/make/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-09-25 17:03:25",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\MakeController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/make/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/make/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class MakeController extends AuthController
{

    //public function initialize(){
    //	//预约记录
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/make/index
     * https://xcxkf173.aubye.com/api/wxapp/make/index
     */
    public function index()
    {
        $MakeInit  = new \init\MakeInit();//预约记录   (ps:InitController)
        $MakeModel = new \initmodel\MakeModel(); //预约记录   (ps:InitModel)

        $result = [];

        $this->success('预约记录-接口请求成功', $result);
    }


    /**
     * 预约记录 列表
     * @OA\Post(
     *     tags={"预约记录"},
     *     path="/wxapp/make/find_make_list",
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
     *         description="type",
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
     *         name="shop_id",
     *         in="query",
     *         description="店铺id 如果存在优先查找",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/make/find_make_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/make/find_make_list
     *   api:  /wxapp/make/find_make_list
     *   remark_name: 预约记录 列表
     *
     */
    public function find_make_list()
    {
        $MakeInit  = new \init\MakeInit();//预约记录   (ps:InitController)
        $MakeModel = new \initmodel\MakeModel(); //预约记录   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        if (empty($params['shop_id'])) $where[] = ['user_id', '=', $this->user_id];
        if (($params['shop_id'])) $where[] = ['shop_id', '=', $params['shop_id']];
        if ($params["keyword"]) $where[] = ["order_num|make_date|username|phone", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];
        if ($params['type']) $where[] = ['type', '=', $params['type']];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $MakeInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $MakeInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 预约记录 详情
     * @OA\Post(
     *     tags={"预约记录"},
     *     path="/wxapp/make/find_make",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/make/find_make
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/make/find_make
     *   api:  /wxapp/make/find_make
     *   remark_name: 预约记录 详情
     *
     */
    public function find_make()
    {
        $MakeInit  = new \init\MakeInit();//预约记录    (ps:InitController)
        $MakeModel = new \initmodel\MakeModel(); //预约记录   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $MakeInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


    /**
     * 预约记录 添加
     * @OA\Post(
     *     tags={"预约记录"},
     *     path="/wxapp/make/add_make",
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
     *         description="预约项目",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="shop_id",
     *         in="query",
     *         description="shop_id",
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
     *         name="make_date",
     *         in="query",
     *         description="预约日期",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="make_time",
     *         in="query",
     *         description="预约时间段",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="username",
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
     *         description="联系电话",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/make/add_make
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/make/add_make
     *   api:  /wxapp/make/add_make
     *   remark_name: 预约记录  添加
     *
     */
    public function add_make()
    {
        $MakeInit  = new \init\MakeInit();//预约记录    (ps:InitController)
        $MakeModel = new \initmodel\MakeModel(); //预约记录   (ps:InitModel)
        $ShopModel = new \initmodel\ShopModel(); //店铺管理   (ps:InitModel)

        /** 获取参数 **/
        $params              = $this->request->param();
        $params["user_id"]   = $this->user_id;
        $params['order_num'] = $this->get_num_only();

        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];

        $shop_info = $ShopModel->where('id', '=', $params['shop_id'])->find();
        if (empty($shop_info)) $this->error("暂无店铺信息");

        /** 提交更新 **/
        $result = $MakeInit->api_edit_post($params, $where);
        if (empty($result)) $this->error("失败请重试");


        //通知店铺 关联手机号
        $ali_sms   = cmf_get_plugin_class("HuYi");
        $sms       = new $ali_sms();
        $send_data = ["mobile" => $shop_info['phone'], 'content' => '您有一个新的门店预约单，请登录后台查看预约信息！'];
        $sms->sendMobileText($send_data);

        $this->success('预约成功');
    }


}
