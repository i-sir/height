<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"CourseOrder",
 *     "name_underline"          =>"course_order",
 *     "controller_name"         =>"CourseOrder",
 *     "table_name"              =>"course_order",
 *     "remark"                  =>"课程订单"
 *     "api_url"                 =>"/api/wxapp/course_order/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-09-19 15:08:29",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\CourseOrderController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/course_order/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/course_order/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class CourseOrderController extends AuthController
{

    //public function initialize(){
    //	//课程订单
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/course_order/index
     * https://xcxkf173.aubye.com/api/wxapp/course_order/index
     */
    public function index()
    {
        $CourseOrderInit  = new \init\CourseOrderInit();//课程订单   (ps:InitController)
        $CourseOrderModel = new \initmodel\CourseOrderModel(); //课程订单   (ps:InitModel)

        $result = [];

        $this->success('课程订单-接口请求成功', $result);
    }


    /**
     * 课程订单 列表
     * @OA\Post(
     *     tags={"课程订单"},
     *     path="/wxapp/course_order/find_order_list",
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
     *         name="status",
     *         in="query",
     *         description="状态:1待付款,2已付款,4已发货,6已收货,8已完成,10已取消,12退款申请,14退款不通过,16退款通过,20待生成价格",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/course_order/find_order_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course_order/find_order_list
     *   api:  /wxapp/course_order/find_order_list
     *   remark_name: 课程订单 列表
     *
     */
    public function find_order_list()
    {
        $this->checkAuth();

        $CourseOrderInit  = new \init\CourseOrderInit();//课程订单   (ps:InitController)
        $CourseOrderModel = new \initmodel\CourseOrderModel(); //课程订单   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['user_id', '=', $this->user_id];
        if ($params["keyword"]) $where[] = ["order_num|phone", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $CourseOrderInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $CourseOrderInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 课程订单 详情
     * @OA\Post(
     *     tags={"课程订单"},
     *     path="/wxapp/course_order/find_order",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/course_order/find_order
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course_order/find_order
     *   api:  /wxapp/course_order/find_order
     *   remark_name: 课程订单 详情
     *
     */
    public function find_order()
    {
        $CourseOrderInit  = new \init\CourseOrderInit();//课程订单    (ps:InitController)
        $CourseOrderModel = new \initmodel\CourseOrderModel(); //课程订单   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $CourseOrderInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


    /**
     * 下单
     * @OA\Post(
     *     tags={"课程订单"},
     *     path="/wxapp/course_order/add_order",
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
     *
     *
     *
     *
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://height.ikun:9090/api/wxapp/course_order/add_order
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course_order/add_order
     *   api:  /wxapp/course_order/add_order
     *   remark_name: 下单
     *
     */
    public function add_order()
    {
        $this->checkAuth();

        $CourseOrderModel = new \initmodel\CourseOrderModel(); //课程订单   (ps:InitModel)
        $CourseModel      = new \initmodel\CourseModel(); //课程计划   (ps:InitModel)
        $CourseSetModel   = new \initmodel\CourseSetModel(); //收费配置   (ps:InitModel)

        /** 获取参数 **/
        $params = $this->request->param();

        $order_nums        = '';
        $insert['user_id'] = $this->user_id;
        $insert['phone']   = $this->user_info['phone'];
        $insert['openid']  = $this->openid;

        //用户已支付的课程
        $map             = [];
        $map[]           = ['user_id', '=', $this->user_id];
        $map[]           = ['status', '=', 2];//已支付
        $not_course_list = $CourseOrderModel->where($map)->column('course_id');

        if (empty($params['course_id'])) $this->error("请选择课程");

        //如果有收费配置,优先使用收费配置
        $map      = [];
        $map[]    = ['', 'EXP', Db::raw("FIND_IN_SET({$params['course_id']},course_ids)")];
        $set_info = $CourseSetModel
            ->where($map)
            ->order('id desc')
            ->find();

        if ($set_info) {
            $map100   = [];
            $map100[] = ['id', 'in', $this->getParams($set_info['course_ids'])];
        } else {
            //下单指定
            $map100   = [];
            $map100[] = ['id', 'in', $this->getParams($params['course_id'])];
        }

        //查询列表
        $course_list = $CourseModel->where($map100)->select();

        // 插入数据
        $order_nums  = []; // 使用数组存储订单号，最后再拼接，避免处理逗号
        $courseIds   = [];
        $totalAmount = 0;

        // 先收集需要处理的课程ID
        if ($set_info) {
            $courseIds   = $this->getParams($set_info['course_ids']);
            $totalAmount = $set_info['price']; // 目标总金额
        }

        // 计算需要分配的课程数量
        $totalCourses  = count($courseIds);
        $normalCourses = [];

        // 先筛选出符合条件的课程
        foreach ($course_list as $course_info) {
            if (!in_array($course_info['id'], $not_course_list)) {
                $normalCourses[] = $course_info;
            }
        }

        // 计算精确的分配金额
        $amounts = [];
        if ($set_info && $totalCourses > 0) {
            // 基础金额 = 总金额 / 课程数量（向下取整到分）
            $baseAmount = bcdiv($totalAmount, $totalCourses, 2);
            // 计算总基础金额
            $totalBase = bcmul($baseAmount, $totalCourses, 2);
            // 计算差额（需要补到最后一个课程上）
            $diff = bcsub($totalAmount, $totalBase, 2);

            // 为每个课程分配基础金额
            foreach ($normalCourses as $index => $course_info) {
                $amount = $baseAmount;
                // 如果是最后一个课程，加上差额
                if ($index == count($normalCourses) - 1 && $diff > 0) {
                    $amount = bcadd($amount, $diff, 2);
                }
                $amounts[$course_info['id']] = $amount;
            }
        }

        // 插入数据
        foreach ($normalCourses as $course_info) {
            $order_num           = $this->get_num_only();
            $insert['order_num'] = $order_num;
            $insert['course_id'] = $course_info['id'];
            $insert['name']      = $course_info['name'];
            $insert['class_id']  = $course_info['class_id'];

            // 应用计算好的金额
            if ($set_info && $totalCourses > 0) {
                $insert['amount'] = $amounts[$course_info['id']];
            } else {
                $insert['amount'] = $course_info['price'];
            }

            $insert['coupon_amount'] = $course_info['amount'];//优惠金额.
            $insert['create_time']   = time();

            //算佣金
            if ($set_info) {
                $insert['commission']  = round($insert['amount'] * ($set_info['commission'] / 100), 2);
                $insert['commission2'] = round($insert['amount'] * ($set_info['commission2'] / 100), 2);
            } else {
                $insert['commission']  = round($insert['amount'] * ($course_info['commission'] / 100), 2);
                $insert['commission2'] = round($insert['amount'] * ($course_info['commission2'] / 100), 2);
            }

            $result = $CourseOrderModel->strict(false)->insert($insert);
            if (empty($result)) $this->error("失败请重试");


            $order_nums[] = $order_num;
        }

        // 最后拼接订单号
        $order_nums = implode(',', $order_nums);

        if (empty($order_nums)) $this->error("暂无课程");

        $this->success('下单成功请支付', ['order_type' => 40, 'order_num' => rtrim($order_nums, ',')]);
    }

}
