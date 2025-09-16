<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Shop",
 *     "name_underline"          =>"shop",
 *     "controller_name"         =>"Shop",
 *     "table_name"              =>"shop",
 *     "remark"                  =>"店铺管理"
 *     "api_url"                 =>"/api/wxapp/shop/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-07-07 16:44:47",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\ShopController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/shop/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/shop/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class ShopController extends AuthController
{

    //public function initialize(){
    //	//店铺管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/shop/index
     * https://xcxkf173.aubye.com/api/wxapp/shop/index
     */
    public function index()
    {
        $ShopInit  = new \init\ShopInit();//店铺管理   (ps:InitController)
        $ShopModel = new \initmodel\ShopModel(); //店铺管理   (ps:InitModel)

        $result = [];

        $this->success('店铺管理-接口请求成功', $result);
    }


    /**
     * 分类列表
     * @OA\Post(
     *     tags={"店铺管理"},
     *     path="/wxapp/shop/find_class_list",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://height.ikun:9090/api/wxapp/shop/find_class_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/shop/find_class_list
     *   api:  /wxapp/shop/find_class_list
     *   remark_name: 分类列表
     *
     */
    public function find_class_list()
    {
        $ShopClassInit  = new \init\ShopClassInit();//店铺类型   (ps:InitController)
        $ShopClassModel = new \initmodel\ShopClassModel(); //店铺类型   (ps:InitModel)

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
        $result                  = $ShopClassInit->get_list($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 店铺管理 列表
     * @OA\Post(
     *     tags={"店铺管理"},
     *     path="/wxapp/shop/find_shop_list",
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
     *    @OA\Parameter(
     *         name="lat",
     *         in="query",
     *         description="纬度",
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
     *         name="lng",
     *         in="query",
     *         description="经度",
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
     *         name="class_id",
     *         in="query",
     *         description="分类id",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/shop/find_shop_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/shop/find_shop_list
     *   api:  /wxapp/shop/find_shop_list
     *   remark_name: 店铺管理 列表
     *
     */
    public function find_shop_list()
    {
        $ShopInit  = new \init\ShopInit();//店铺管理   (ps:InitController)
        $ShopModel = new \initmodel\ShopModel(); //店铺管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        if ($params["keyword"]) $where[] = ["name|phone|introduce", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];
        if ($params['class_id']) $where[] = ['', 'EXP', Db::raw("FIND_IN_SET({$params['class_id']},class_ids)")];


        $field_lat = 'lat';// 数据库字段名 - 纬度  -90°到90°
        $field_lng = 'lng';// 数据库字段名 - 经度  -180°到180°
        $lat       = $params['lat'];// 数据库字段名 - 纬度  -90°到90°
        $lng       = $params['lng'];// 数据库字段名 - 经度  -180°到180°
        if (!empty($lat) && !empty($lng)) {
            $field           = "*, (6378.138 * 2 * asin(sqrt(pow(sin(({$field_lng} * pi() / 180 - {$lng} * pi() / 180) / 2),2) + cos({$field_lng} * pi() / 180) * cos({$lng} * pi() / 180) * pow(sin(({$field_lat} * pi() / 180 - {$lat} * pi() / 180) / 2),2))) * 1000) as distance";
            $params['order'] = 'distance asc,id desc';
            $params['field'] = $field;
        }


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        if ($params['is_paginate']) $result = $ShopInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $ShopInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 店铺管理 详情
     * @OA\Post(
     *     tags={"店铺管理"},
     *     path="/wxapp/shop/find_shop",
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
     *    @OA\Parameter(
     *         name="is_me",
     *         in="query",
     *         description="true  自己的店铺信息",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/shop/find_shop
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/shop/find_shop
     *   api:  /wxapp/shop/find_shop
     *   remark_name: 店铺管理 详情
     *
     */
    public function find_shop()
    {
        $ShopInit  = new \init\ShopInit();//店铺管理    (ps:InitController)
        $ShopModel = new \initmodel\ShopModel(); //店铺管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where = [];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['is_me']) $where[] = ["user_id", "=", $this->user_id];


        $field_lat = 'lat';// 数据库字段名 - 纬度  -90°到90°
        $field_lng = 'lng';// 数据库字段名 - 经度  -180°到180°
        $lat       = $params['lat'];// 数据库字段名 - 纬度  -90°到90°
        $lng       = $params['lng'];// 数据库字段名 - 经度  -180°到180°
        if (!empty($lat) && !empty($lng)) {
            $field           = "*, (6378.138 * 2 * asin(sqrt(pow(sin(({$field_lng} * pi() / 180 - {$lng} * pi() / 180) / 2),2) + cos({$field_lng} * pi() / 180) * cos({$lng} * pi() / 180) * pow(sin(({$field_lat} * pi() / 180 - {$lat} * pi() / 180) / 2),2))) * 1000) as distance";
            $params['order'] = 'distance asc,id desc';
            $params['field'] = $field;
        }

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ShopInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
