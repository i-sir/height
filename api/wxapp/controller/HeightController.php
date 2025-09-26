<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Height",
 *     "name_underline"          =>"height",
 *     "controller_name"         =>"Height",
 *     "table_name"              =>"height",
 *     "remark"                  =>"身高数据"
 *     "api_url"                 =>"/api/wxapp/height/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-09-17 11:10:46",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\HeightController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/height/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/height/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class HeightController extends AuthController
{

    //public function initialize(){
    //	//身高数据
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/height/index
     * https://xcxkf173.aubye.com/api/wxapp/height/index
     */
    public function index()
    {
        $HeightInit  = new \init\HeightInit();//身高数据   (ps:InitController)
        $HeightModel = new \initmodel\HeightModel(); //身高数据   (ps:InitModel)

        $result = [];

        $this->success('身高数据-接口请求成功', $result);
    }


    /**
     * 身高数据 列表
     * @OA\Post(
     *     tags={"身高数据"},
     *     path="/wxapp/height/find_height_list",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/height/find_height_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/height/find_height_list
     *   api:  /wxapp/height/find_height_list
     *   remark_name: 身高数据 列表
     *
     */
    public function find_height_list()
    {
        $this->checkAuth();

        $HeightInit  = new \init\HeightInit();//身高数据   (ps:InitController)
        $HeightModel = new \initmodel\HeightModel(); //身高数据   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['user_id', '=', $this->user_id];
        if ($params["keyword"]) $where[] = ["user_id|height|weight|bmi|date", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $HeightInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $HeightInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 身高数据 详情
     * @OA\Post(
     *     tags={"身高数据"},
     *     path="/wxapp/height/find_height",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/height/find_height
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/height/find_height
     *   api:  /wxapp/height/find_height
     *   remark_name: 身高数据 详情
     *
     */
    public function find_height()
    {
        $HeightInit  = new \init\HeightInit();//身高数据    (ps:InitController)
        $HeightModel = new \initmodel\HeightModel(); //身高数据   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $HeightInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


    /**
     * 身高数据 编辑&添加
     * @OA\Post(
     *     tags={"身高数据"},
     *     path="/wxapp/height/edit_height",
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
     *         name="height",
     *         in="query",
     *         description="身高",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="weight",
     *         in="query",
     *         description="体重",
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
     *         name="date",
     *         in="query",
     *         description="日期 2025-09-09",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/height/edit_height
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/height/edit_height
     *   api:  /wxapp/height/edit_height
     *   remark_name: 身高数据 编辑&添加
     *
     */
    public function edit_height()
    {
        $this->checkAuth();

        $HeightInit  = new \init\HeightInit();//身高数据    (ps:InitController)
        $HeightModel = new \initmodel\HeightModel(); //身高数据   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 检测参数信息 **/
        $validateResult = $this->validate($params, 'Height');
        if ($validateResult !== true) $this->error($validateResult);

        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];


        /** 提交更新 **/
        $result = $HeightInit->api_edit_post($params, $where);
        if (empty($result)) $this->error("失败请重试");


        if (empty($params["id"])) $msg = "添加成功";
        if (!empty($params["id"])) $msg = "编辑成功";
        $this->success($msg);
    }


    /**
     * 身高数据 删除
     * @OA\Post(
     *     tags={"身高数据"},
     *     path="/wxapp/height/delete_height",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/height/delete_height
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/height/delete_height
     *   api:  /wxapp/height/delete_height
     *   remark_name: 身高数据 删除
     *
     */
    public function delete_height()
    {
        $HeightInit  = new \init\HeightInit();//身高数据    (ps:InitController)
        $HeightModel = new \initmodel\HeightModel(); //身高数据   (ps:InitModel)

        /** 获取参数 **/
        $params = $this->request->param();

        /** 删除数据 **/
        $result = $HeightInit->delete_post($params["id"]);
        if (empty($result)) $this->error("失败请重试");

        $this->success("删除成功");
    }


    /**
     * 折线图,统计数据
     * @OA\Post(
     *     tags={"身高数据"},
     *     path="/wxapp/height/statistics",
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
     *    @OA\Parameter(
     *         name="begin_time",
     *         in="query",
     *         description="开始时间 2025-09-09",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *    @OA\Parameter(
     *         name="end_time",
     *         in="query",
     *         description="结束时间 2025-09-09",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/height/statistics
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/height/statistics
     *   api:  /wxapp/height/statistics
     *   remark_name: 折线图,统计数据
     *
     */
    public function statistics()
    {
        $this->checkAuth();

        $HeightModel = new \initmodel\HeightModel(); //身高数据
        $params      = $this->request->param();

        $map   = [];
        $map[] = ['user_id', '=', $this->user_id];
        $map[] = ['delete_time', '=', 0]; // 添加未删除的条件
        $map[] = $this->getBetweenTime($params['begin_time'], $params['end_time']);
        $list  = $HeightModel->where($map)->order('time asc')->select(); // 改为升序以便处理时间序列

        if (empty($list)) {
            $this->success("成功", [
                'line_chart' => [
                    'categories' => [],
                    'series'     => []
                ],
                'height'     => 0,
                'weight'     => 0,
                'bmi'        => 0
            ]);
        }

        // 处理折线图数据
        $categories = [];
        $heightData = [];
        $weightData = [];
        $bmiData    = [];

        foreach ($list as $item) {
            // 格式化日期，使用date字段或time字段转换
            $date         = !empty($item['date']) ? $item['date'] : date('Y-m-d', $item['time'] / 1000);
            $categories[] = $date;

            $heightData[] = floatval($item['height']);
            $weightData[] = floatval($item['weight']);
            $bmiData[]    = floatval($item['bmi']);
        }

        // 折线图数据
        $result['line_chart'] = [
            'categories' => $categories,
            'series'     => [
                [
                    'name' => '身高',
                    'data' => $heightData
                ],
                [
                    'name' => '体重',
                    'data' => $weightData
                ],
                [
                    'name' => 'BMI',
                    'data' => $bmiData
                ]
            ]
        ];

        // 基础数据 - 取最新的一条记录
        $latestRecord     = $list[count($list) - 1]; // 因为按时间升序排列，最后一条是最新的
        $result['height'] = floatval($latestRecord['height']);
        $result['weight'] = floatval($latestRecord['weight']);
        $result['bmi']    = floatval($latestRecord['bmi']);

        $this->success("成功", $result);
    }

}
