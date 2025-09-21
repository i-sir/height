<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"CourseHelp",
 *     "name_underline"          =>"course_help",
 *     "controller_name"         =>"CourseHelp",
 *     "table_name"              =>"course_help",
 *     "remark"                  =>"训练帮助"
 *     "api_url"                 =>"/api/wxapp/course_help/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-09-19 17:47:59",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\CourseHelpController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/course_help/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/course_help/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class CourseHelpController extends AuthController
{

    //public function initialize(){
    //	//训练帮助
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/course_help/index
     * https://xcxkf173.aubye.com/api/wxapp/course_help/index
     */
    public function index()
    {
        $CourseHelpInit  = new \init\CourseHelpInit();//训练帮助   (ps:InitController)
        $CourseHelpModel = new \initmodel\CourseHelpModel(); //训练帮助   (ps:InitModel)

        $result = [];

        $this->success('训练帮助-接口请求成功', $result);
    }


    /**
     * 训练帮助 列表
     * @OA\Post(
     *     tags={"训练帮助"},
     *     path="/wxapp/course_help/find_help_list",
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
     *    @OA\Parameter(
     *         name="class_id",
     *         in="query",
     *         description="分类ID",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/course_help/find_help_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course_help/find_help_list
     *   api:  /wxapp/course_help/find_help_list
     *   remark_name: 训练帮助 列表
     *
     */
    public function find_help_list()
    {
        $CourseHelpInit  = new \init\CourseHelpInit();//训练帮助   (ps:InitController)
        $CourseHelpModel = new \initmodel\CourseHelpModel(); //训练帮助   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["name|describe", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];
        if ($params['class_id']) $where[] = ['class_id', '=', $params['class_id']];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $CourseHelpInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $CourseHelpInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 训练帮助 详情
     * @OA\Post(
     *     tags={"训练帮助"},
     *     path="/wxapp/course_help/find_help",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/course_help/find_help
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course_help/find_help
     *   api:  /wxapp/course_help/find_help
     *   remark_name: 训练帮助 详情
     *
     */
    public function find_help()
    {
        $CourseHelpInit  = new \init\CourseHelpInit();//训练帮助    (ps:InitController)
        $CourseHelpModel = new \initmodel\CourseHelpModel(); //训练帮助   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $CourseHelpInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
