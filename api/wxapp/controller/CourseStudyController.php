<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"CourseStudy",
 *     "name_underline"          =>"course_study",
 *     "controller_name"         =>"CourseStudy",
 *     "table_name"              =>"course_study",
 *     "remark"                  =>"学习记录"
 *     "api_url"                 =>"/api/wxapp/course_study/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-09-21 17:22:41",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\CourseStudyController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/course_study/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/course_study/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class CourseStudyController extends AuthController
{

    //public function initialize(){
    //	//学习记录
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/course_study/index
     * https://xcxkf173.aubye.com/api/wxapp/course_study/index
     */
    public function index()
    {
        $CourseStudyInit  = new \init\CourseStudyInit();//学习记录   (ps:InitController)
        $CourseStudyModel = new \initmodel\CourseStudyModel(); //学习记录   (ps:InitModel)

        $result = [];

        $this->success('学习记录-接口请求成功', $result);
    }


    /**
     * 获取日历列表
     * @OA\Post(
     *     tags={"学习记录"},
     *     path="/wxapp/course_study/date_list",
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
     *    @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="日期2025-09 如不传默认本月",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://height.ikun:9090/api/wxapp/course_study/date_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course_study/date_list
     *   api:  /wxapp/course_study/date_list
     *   remark_name: 日历列表
     *
     */
    public function date_list()
    {
        $CourseStudyModel = new \initmodel\CourseStudyModel(); //学习记录   (ps:InitModel)

        $date = $this->request->param('date') ?? date('Y-m');

        $result        = [];
        $month         = date('Y-m', strtotime($date));
        $daysInMonth   = date('t', strtotime($date));
        $weekdayText   = ['日', '一', '二', '三', '四', '五', '六'];
        $weekdayNumber = [7, 1, 2, 3, 4, 5, 6];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = $month . '-' . sprintf('%02d', $day);
            $weekday     = date('w', strtotime($currentDate));


            $is_study = false;
            if ($this->user_id) {
                $map      = [];
                $map[]    = ['user_id', '=', $this->user_id];
                $map[]    = ['date', '=', $currentDate];
                $study    = $CourseStudyModel->where($map)->count();
                $is_study = $study ? true : false;
            }

            $result[] = [
                'is_study'     => $is_study,
                'date'         => $currentDate,
                'month_day'    => date('m-d', strtotime($currentDate)),
                'day'          => sprintf('%02d', $day),
                'weekday_key'  => $weekdayNumber[$weekday],
                'weekday_name' => '周' . $weekdayText[$weekday],
            ];
        }

        $this->success('请求成功!', $result);
    }


    /**
     * 学习记录 列表
     * @OA\Post(
     *     tags={"学习记录"},
     *     path="/wxapp/course_study/find_study_list",
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
     *    @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="日期2025-09-09 如不传默认今天",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
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
     *   test_environment: http://height.ikun:9090/api/wxapp/course_study/find_study_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course_study/find_study_list
     *   api:  /wxapp/course_study/find_study_list
     *   remark_name: 学习记录 列表
     *
     */
    public function find_study_list()
    {
        $this->checkAuth();

        $CourseStudyInit  = new \init\CourseStudyInit();//学习记录   (ps:InitController)
        $CourseStudyModel = new \initmodel\CourseStudyModel(); //学习记录   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['a.id', '>', 0];
        $where[] = ['a.user_id', '=', $this->user_id];
        $where[] = ['a.date', '=', $params['date'] ?? date('Y-m-d')];
        if ($params["status"]) $where[] = ["a.status", "=", $params["status"]];
        if ($params["course_id"]) $where[] = ["a.course_id", "=", $params["course_id"]];
        if ($params["class_id"]) $where[] = ["a.class_id", "=", $params["class_id"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        $result                  = $CourseStudyInit->get_join_list($where, $params);
        if (empty($result)) $this->success("暂无信息!", []);

        $this->success("请求成功!", $result);
    }


    /**
     * 学习记录 详情
     * @OA\Post(
     *     tags={"学习记录"},
     *     path="/wxapp/course_study/find_study",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/course_study/find_study
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course_study/find_study
     *   api:  /wxapp/course_study/find_study
     *   remark_name: 学习记录 详情
     *
     */
    public function find_study()
    {
        $CourseStudyInit  = new \init\CourseStudyInit();//学习记录    (ps:InitController)
        $CourseStudyModel = new \initmodel\CourseStudyModel(); //学习记录   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $CourseStudyInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


    /**
     * 学习记录 增加,点击结束时请求
     * @OA\Post(
     *     tags={"学习记录"},
     *     path="/wxapp/course_study/add_study",
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
     *    @OA\Parameter(
     *         name="course_id",
     *         in="query",
     *         description="课程id",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="plan_id",
     *         in="query",
     *         description="计划id",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="状态:1未学习,2已学习(组数已完成)",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="second",
     *         in="query",
     *         description="学习秒数",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="number",
     *         in="query",
     *         description="学习次数",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="image",
     *         in="query",
     *         description="评价图片 全路径",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *    @OA\Parameter(
     *         name="experience",
     *         in="query",
     *         description="体验描述",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *    @OA\Parameter(
     *         name="group",
     *         in="query",
     *         description="学习组数",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://height.ikun:9090/api/wxapp/course_study/add_study
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course_study/add_study
     *   api:  /wxapp/course_study/add_study
     *   remark_name: 学习记录 添加
     *
     */
    public function add_study()
    {
        $this->checkAuth();

        $CourseStudyInit  = new \init\CourseStudyInit();//学习记录    (ps:InitController)
        $CourseStudyModel = new \initmodel\CourseStudyModel(); //学习记录   (ps:InitModel)
        $MemberModel      = new \initmodel\MemberModel();//用户管理
        $CoursePlanModel  = new \initmodel\CoursePlanModel(); //计划管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;


        /** 提交更新 **/
        $result = $CourseStudyInit->api_edit_post($params);
        if (empty($result)) $this->error("失败请重试");


        //记录用户学习时长
        if ($params['status'] == 2 && $this->user_info['study_date'] != date('Y-m-d')) {
            $MemberModel->where('id', '=', $this->user_id)->strict(false)->update([
                'study_date' => date('Y-m-d'),
                'study_day'  => $this->user_info['study_day'] + 1,
            ]);
        }


        //增加参加人数
        $CoursePlanModel->where('id', '=', $params['plan_id'])->inc('attend_number')->update();


        $this->success('记录成功');
    }


    /**
     * 运动记录统计
     * @OA\Post(
     *     tags={"学习记录"},
     *     path="/wxapp/course_study/statistics",
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
     *         name="is_me",
     *         in="query",
     *         description="true查看自己信息",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/course_study/statistics
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course_study/statistics
     *   api:  /wxapp/course_study/statistics
     *   remark_name: 运动记录统计
     *
     */
    public function statistics()
    {
        $params           = $this->request->param();
        $CourseStudyModel = new \initmodel\CourseStudyModel(); //学习记录   (ps:InitModel)


        if ($params['is_me']) {
            $map100 = [];
            //$map100[] = ['status', '=', 2];
            $map100[] = ['class_id', '=', $params['class_id'] ?? 1];
            $map100[] = ['user_id', '=', $this->user_id];

            //累计天数
            $result["total_day"] = $CourseStudyModel->where($map100)->group('date')->count();


            //累计时长
            $result["total_time"] = (int)(($CourseStudyModel->where($map100)->sum('second') ?? 0) / 60);


            //今日时长
            $map100[]             = ['date', '=', date('Y-m-d')];
            $result["today_time"] = (int)(($CourseStudyModel->where($map100)->sum('second') ?? 0) / 60);

        } else {

            $map100       = [];
            $map100[]     = ['status', '=', 2];
            $punch_number = $CourseStudyModel->where($map100)->count();
            //设置虚拟数+实际人数
            $accumulated_number           = cmf_config('accumulated_number');
            $result["total_punch_number"] = (int)$accumulated_number + $punch_number;//累计打卡人数


            //设置虚拟数+实际人数
            $time_number          = (int)(($CourseStudyModel->where($map100)->sum('second') ?? 0) / 60);
            $cumulative_duration  = cmf_config('cumulative_duration');
            $result["total_time"] = (int)($cumulative_duration + $time_number);  //累计时长(分钟)

            //设置虚拟数+实际人数
            $map100[]                     = ['date', '=', date('Y-m-d')];
            $today_number                 = $CourseStudyModel->where($map100)->count();
            $number_clock                 = cmf_config('number_clock');
            $result["today_punch_number"] = (int)$number_clock + $today_number;//今日打卡人数
        }


        $this->success("统计数据", $result);
    }


    /**
     * 排名列表
     * @OA\Post(
     *     tags={"学习记录"},
     *     path="/wxapp/course_study/ranking",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://height.ikun:9090/api/wxapp/course_study/ranking
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course_study/ranking
     *   api:  /wxapp/course_study/ranking
     *   remark_name: 排名列表
     *
     */
    public function ranking()
    {
        $MemberInit = new \init\MemberInit();//用户管理


        $params          = $this->request->param();
        $params['order'] = 'study_day desc,id desc';
        $params['limit'] = 100;


        $result = $MemberInit->get_list([], $params);


        $this->success("排名列表", $result);
    }


}
