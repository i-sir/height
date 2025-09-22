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
     *         name="course_ids",
     *         in="query",
     *         description="数组格式  [1,2,3]",
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
     *         name="is_all",
     *         in="query",
     *         description="全部购买",
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


        if ($params["is_all"]) {
            //下单全部
            $map100      = [];
            $map100[]    = ['is_show', '=', 1];
            $map100[]    = ['class_id', '=', $params['class_id'] ?? 1];
            $course_list = $CourseModel->where($map100)->select();
        } elseif ($params['course_ids']) {
            //下单指定
            $map100      = [];
            $map100[]    = ['id', 'in', $params['course_ids']];
            $course_list = $CourseModel->where($map100)->select();
        } else {
            $this->error("请选择课程");
        }


        //插入数据
        foreach ($course_list as $key => $course_info) {
            if (!in_array($course_info['id'], $not_course_list)) {
                $order_num               = $this->get_num_only();
                $insert['order_num']     = $order_num;
                $insert['course_id']     = $course_info['id'];
                $insert['amount']        = $course_info['price'];
                $insert['name']          = $course_info['name'];
                $insert['coupon_amount'] = $course_info['price'];
                $insert['class_id']      = $course_info['class_id'];
                $insert['create_time']   = time();

                $result = $CourseOrderModel->strict(false)->insert($insert);
                if (empty($result)) $this->error("失败请重试");

                $order_nums .= $order_num . ',';
            }
        }

        if (empty($order_nums)) $this->error("暂无课程");


        $this->success('下单成功请支付', ['order_type' => 40, 'order_num' => rtrim($order_nums, ',')]);
    }


}
