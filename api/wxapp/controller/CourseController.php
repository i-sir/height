<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Course",
 *     "name_underline"          =>"course",
 *     "controller_name"         =>"Course",
 *     "table_name"              =>"course",
 *     "remark"                  =>"课程计划"
 *     "api_url"                 =>"/api/wxapp/course/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-09-18 11:42:28",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\CourseController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/course/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/course/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class CourseController extends AuthController
{

    //public function initialize(){
    //	//课程计划
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/course/index
     * https://xcxkf173.aubye.com/api/wxapp/course/index
     */
    public function index()
    {
        $CourseInit  = new \init\CourseInit();//课程计划   (ps:InitController)
        $CourseModel = new \initmodel\CourseModel(); //课程计划   (ps:InitModel)

        $result = [];

        $this->success('课程计划-接口请求成功', $result);
    }


    /**
     * 分类列表
     * @OA\Post(
     *     tags={"课程计划"},
     *     path="/wxapp/course/find_class_list",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://height.ikun:9090/api/wxapp/course/find_class_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course/find_class_list
     *   api:  /wxapp/course/find_class_list
     *   remark_name: 分类列表
     *
     */
    public function find_class_list()
    {
        $CourseClassInit  = new \init\CourseClassInit();//分类管理   (ps:InitController)
        $CourseClassModel = new \initmodel\CourseClassModel(); //分类管理   (ps:InitModel)

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
        $result                  = $CourseClassInit->get_list($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }

    /**
     * 分类详情
     * @OA\Post(
     *     tags={"课程计划"},
     *     path="/wxapp/course/find_class",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/course/find_class
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course/find_class
     *   api:  /wxapp/course/find_class
     *   remark_name: 分类详情
     *
     */
    public function find_class()
    {
        $CourseClassInit  = new \init\CourseClassInit();//分类管理   (ps:InitController)
        $CourseClassModel = new \initmodel\CourseClassModel(); //分类管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '=', $params['id']];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        $result                  = $CourseClassInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }

    /**
     * 课程计划 列表
     * @OA\Post(
     *     tags={"课程计划"},
     *     path="/wxapp/course/find_course_list",
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
     *         description="分类id    1身高管理计划,2体态管理计划,3减重管理计划,4局部塑型计划",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/course/find_course_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course/find_course_list
     *   api:  /wxapp/course/find_course_list
     *   remark_name: 课程计划 列表
     *
     */
    public function find_course_list()
    {
        $CourseInit       = new \init\CourseInit();//课程计划   (ps:InitController)
        $CourseModel      = new \initmodel\CourseModel(); //课程计划   (ps:InitModel)
        $CourseOrderModel = new \initmodel\CourseOrderModel(); //课程订单

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["name|introduce", "like", "%{$params['keyword']}%"];
        if ($params["class_id"]) $where[] = ["class_id", "=", $params["class_id"]];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 获取用户已支付课程ID列表 **/
        $paidCourseIds = $CourseOrderModel
            ->where('user_id', $params['user_id'])
            ->where('status', 2)
            ->column('course_id');

        /** 计算下一个可解锁课程的list_order **/
        $max_list_order = 0;
        if (!empty($paidCourseIds)) {
            $max_list_order = $CourseModel
                ->where('id', 'in', $paidCourseIds)
                ->where('is_show', 1)
                ->max('list_order');
        }

        $next_course               = $CourseModel
            ->where('list_order', '>', $max_list_order)
            ->where('is_show', 1)
            ->where('class_id', '=', $params['class_id'] ?? 1)
            ->order('list_order')
            ->find();
        $next_list_order           = $next_course ? $next_course['list_order'] : 0;
        $params['paid_course_ids'] = $paidCourseIds;
        $params['next_list_order'] = $next_list_order;

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $CourseInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $CourseInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 课程计划 详情
     * @OA\Post(
     *     tags={"课程计划"},
     *     path="/wxapp/course/find_course",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/course/find_course
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course/find_course
     *   api:  /wxapp/course/find_course
     *   remark_name: 课程计划 详情
     *
     */
    public function find_course()
    {
        $CourseInit  = new \init\CourseInit();//课程计划    (ps:InitController)
        $CourseModel = new \initmodel\CourseModel(); //课程计划   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $CourseInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
