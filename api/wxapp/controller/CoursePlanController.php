<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"CoursePlan",
 *     "name_underline"          =>"course_plan",
 *     "controller_name"         =>"CoursePlan",
 *     "table_name"              =>"course_plan",
 *     "remark"                  =>"计划管理"
 *     "api_url"                 =>"/api/wxapp/course_plan/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-09-18 16:53:49",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\CoursePlanController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/course_plan/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/course_plan/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class CoursePlanController extends AuthController
{

    //public function initialize(){
    //	//计划管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/course_plan/index
     * https://xcxkf173.aubye.com/api/wxapp/course_plan/index
     */
    public function index()
    {
        $CoursePlanInit  = new \init\CoursePlanInit();//计划管理   (ps:InitController)
        $CoursePlanModel = new \initmodel\CoursePlanModel(); //计划管理   (ps:InitModel)

        $result = [];

        $this->success('计划管理-接口请求成功', $result);
    }


    /**
     * 计划管理 列表
     * @OA\Post(
     *     tags={"计划管理"},
     *     path="/wxapp/course_plan/find_plan_list",
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
     *         name="course_id",
     *         in="query",
     *         description="课时id",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/course_plan/find_plan_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course_plan/find_plan_list
     *   api:  /wxapp/course_plan/find_plan_list
     *   remark_name: 计划管理 列表
     *
     */
    public function find_plan_list()
    {
        $this->checkAuth();
        $CoursePlanInit   = new \init\CoursePlanInit();//计划管理   (ps:InitController)
        $CoursePlanModel  = new \initmodel\CoursePlanModel(); //计划管理   (ps:InitModel)
        $CourseStudyModel = new \initmodel\CourseStudyModel(); //学习记录   (ps:InitModel)
        $CourseModel      = new \initmodel\CourseModel(); //课程计划   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["name|introduce|description", "like", "%{$params['keyword']}%"];
        if ($params["course_id"]) $where[] = ["course_id", "=", $params["course_id"]];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 获取用户 学习完成的课时ID列表 **/
        $paidPlanIds = $CourseStudyModel
            ->where('user_id', $params['user_id'])
            ->where('course_id', '=', $params['course_id'] ?? 0)
            ->where('status', 2)
            ->column('plan_id');

        /** 计算下一个可解锁课程的list_order **/
        $max_list_order = 0;
        if (!empty($paidPlanIds)) {
            $max_list_order = $CoursePlanModel
                ->where('id', 'in', $paidPlanIds)
                ->where('is_show', 1)
                ->max('list_order');
        }

        //下一个可解锁课时
        $next_plan = $CoursePlanModel
            ->where('list_order', '>', $max_list_order)
            ->where('is_show', 1)
            ->where('course_id', '=', $params['course_id'] ?? 0)
            ->order('list_order')
            ->find();


        //最近解锁 时间
        $new_date = $CourseStudyModel
                ->where('user_id', $params['user_id'])
                ->where('course_id', '=', $params['course_id'] ?? 0)
                ->where('status', 2)
                ->order('id desc')
                ->value('date') ?? date('Y-m-d', strtotime('-1 day'));


        //检测上个阶段是否全部学习 如果没有本阶段全部解锁
        $is_all_unlock = false;
        $course_info   = $CourseModel->where('id', '=', $params['course_id'])->find();

        //获取上一个阶段
        $front_course_info = $CourseModel
            ->where('list_order', '<', $course_info['list_order'])
            ->where('is_show', 1)
            ->order('id desc')
            ->find();


        //不属于第一个阶段,检测上个阶段是否全部学习
        if ($front_course_info) {
            $frontPlanIds = $CoursePlanModel
                ->where('course_id', '=', $front_course_info['id'])
                ->where('is_show', 1)
                ->column('id');

            $studyPlanIds = $CourseStudyModel
                ->where('user_id', $params['user_id'])
                ->where('course_id', '=', $front_course_info['id'] ?? 0)
                ->where('status', 2)
                ->group('plan_id')
                ->column('plan_id');

            if (count($studyPlanIds) != count($frontPlanIds)) $is_all_unlock = true;//全锁
        }

        //参数
        $next_list_order           = $next_plan ? $next_plan['list_order'] : 0;
        $params['paid_plan_ids']   = $paidPlanIds;
        $params['next_list_order'] = $next_list_order;
        $params['new_date']        = $new_date;
        $params['is_all_unlock']   = $is_all_unlock;


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $CoursePlanInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $CoursePlanInit->get_list_paginate($where, $params);


        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 计划管理 详情
     * @OA\Post(
     *     tags={"计划管理"},
     *     path="/wxapp/course_plan/find_plan",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/course_plan/find_plan
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course_plan/find_plan
     *   api:  /wxapp/course_plan/find_plan
     *   remark_name: 计划管理 详情
     *
     */
    public function find_plan()
    {
        $CoursePlanInit  = new \init\CoursePlanInit();//计划管理    (ps:InitController)
        $CoursePlanModel = new \initmodel\CoursePlanModel(); //计划管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $CoursePlanInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
