<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"ExpOrder",
 *     "name_underline"          =>"exp_order",
 *     "controller_name"         =>"ExpOrder",
 *     "table_name"              =>"exp_order",
 *     "remark"                  =>"体验卡订单管理"
 *     "api_url"                 =>"/api/wxapp/exp_order/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-09-17 15:53:12",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\ExpOrderController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/exp_order/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/exp_order/index",
 * )
 */


use init\QrInit;
use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class ExpOrderController extends AuthController
{

    //public function initialize(){
    //	//体验卡订单管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/exp_order/index
     * https://xcxkf173.aubye.com/api/wxapp/exp_order/index
     */
    public function index()
    {
        $ExpOrderInit  = new \init\ExpOrderInit();//体验卡订单管理   (ps:InitController)
        $ExpOrderModel = new \initmodel\ExpOrderModel(); //体验卡订单管理   (ps:InitModel)

        $result = [];

        $this->success('体验卡订单管理-接口请求成功', $result);
    }


    /**
     * 体验卡订单管理 列表
     * @OA\Post(
     *     tags={"体验卡订单管理"},
     *     path="/wxapp/exp_order/find_order_list",
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
     *         description="状态:2待核销,8已核销,11已取消",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/exp_order/find_order_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/exp_order/find_order_list
     *   api:  /wxapp/exp_order/find_order_list
     *   remark_name: 体验卡订单管理 列表
     *
     */
    public function find_order_list()
    {
        $ExpOrderInit  = new \init\ExpOrderInit();//体验卡订单管理   (ps:InitController)
        $ExpOrderModel = new \initmodel\ExpOrderModel(); //体验卡订单管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ["status", "in", [2, 8, 11]];
        if ($params["keyword"]) $where[] = ["order_num|username|phone|goods_name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $ExpOrderInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $ExpOrderInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 体验卡订单管理 详情
     * @OA\Post(
     *     tags={"体验卡订单管理"},
     *     path="/wxapp/exp_order/find_order",
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
     *         name="order_num",
     *         in="query",
     *         description="id 订单号二选一",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="cav_code",
     *         in="query",
     *         description="cav_code 订单号二选一",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/exp_order/find_order
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/exp_order/find_order?id=14
     *   api:  /wxapp/exp_order/find_order
     *   remark_name: 体验卡订单管理 详情
     *
     */
    public function find_order()
    {
        $ExpOrderInit  = new \init\ExpOrderInit();//体验卡订单管理    (ps:InitController)
        $ExpOrderModel = new \initmodel\ExpOrderModel(); //体验卡订单管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where = [];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ["order_num", "=", $params["order_num"]];
        if ($params['cav_code']) $where[] = ["cav_code", "=", $params["cav_code"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ExpOrderInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


    /**
     * 下单
     * @OA\Post(
     *     tags={"体验卡订单管理"},
     *     path="/wxapp/exp_order/add_order",
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
     *         name="coupon_id",
     *         in="query",
     *         description="优惠券id",
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
     *         name="goods_id",
     *         in="query",
     *         description="商品id",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/exp_order/add_order
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/exp_order/add_order
     *   api:  /wxapp/exp_order/add_order
     *   remark_name: 下单
     *
     */
    public function add_order()
    {
        $ExpOrderInit        = new \init\ExpOrderInit();//体验卡订单管理    (ps:InitController)
        $ExpOrderModel       = new \initmodel\ExpOrderModel(); //体验卡订单管理   (ps:InitModel)
        $ShopCouponUserModel = new \initmodel\ShopCouponUserModel(); //优惠券领取记录   (ps:InitModel)
        $ExpGoodsModel       = new \initmodel\ExpGoodsModel(); //体验卡   (ps:InitModel)
        $QrInit              = new QrInit();//生成二维码

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;


        //商品信息
        $goods_info = $ExpGoodsModel->where('id', '=', $params['goods_id'])->find();
        if (empty($goods_info)) $this->error('商品信息错误');


        $coupon_amount = 0;
        //优惠券信息
        if ($params['coupon_id']) {
            $coupon_info = $ShopCouponUserModel->where('id', '=', $params['coupon_id'])->find();
            if (empty($coupon_info) || $coupon_info['used'] != 1) $this->error('优惠券信息错误');
            if ($coupon_info) {
                $coupon_amount = $coupon_info['amount'];
            }
        }


        //插入数据
        $order_num               = $this->get_num_only();
        $insert['order_num']     = $order_num;
        $insert['openid']        = $this->openid;
        $insert['shop_id']       = $goods_info['shop_id'];
        $insert['user_id']       = $params['user_id'];
        $insert['username']      = $params['username'];
        $insert['phone']         = $params['phone'];
        $insert['coupon_id']     = $params['coupon_id'];
        $insert['make_date']     = $params['make_date'];
        $insert['make_time']     = $params['make_time'];
        $insert['goods_id']      = $params['goods_id'];
        $insert['type']          = $params['type'];
        $insert['coupon_amount'] = $coupon_amount;
        $insert['goods_amount']  = $goods_info['price'];
        $insert['total_amount']  = $goods_info['price'];
        $insert['amount']        = round($goods_info['price'] - $coupon_amount, 2);
        $insert['goods_name']    = $goods_info['goods_name'];
        $insert['goods_image']   = cmf_get_asset_url($goods_info['image']);
        $insert['create_time']   = time();
        $insert['cav_code']      = $this->get_num_only('cav_code', 12, 4, 'T');
        $insert['cav_qr_code']   = $QrInit->get_qr($insert['cav_code']);


        /** 提交更新 **/
        $result = $ExpOrderModel->strict(false)->insert($insert);
        if (empty($result)) $this->error("失败请重试");


        $this->success('下单成功,请支付', ['order_type' => 30, 'order_num' => $order_num]);
    }


    /**
     * 取消订单
     * @OA\Post(
     *     tags={"体验卡订单管理"},
     *     path="/wxapp/exp_order/cancel_order",
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
     *         name="order_num",
     *         in="query",
     *         description="id 订单号二选一",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/exp_order/cancel_order
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/exp_order/cancel_order
     *   api:  /wxapp/exp_order/cancel_order
     *   remark_name: 取消订单
     *
     */
    public function cancel_order()
    {
        $this->checkAuth();
        $ExpOrderModel       = new \initmodel\ExpOrderModel(); //体验卡订单管理   (ps:InitModel)
        $ShopCouponUserModel = new \initmodel\ShopCouponUserModel(); //优惠券领取记录   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ["order_num", "=", $params["order_num"]];


        $order_info = $ExpOrderModel->where($where)->find();
        if (empty($order_info)) $this->error("暂无数据");


        //优惠券退回
        if ($order_info['coupon_id']) {
            $ShopCouponUserModel->where('id', '=', $order_info['coupon_id'])->update(['used' => 1, 'update_time' => time()]);
        }

        if ($order_info['status'] != 2) $this->error("状态错误");
        $result = $ExpOrderModel->where($where)->strict(false)->update([
            "status"      => 11,
            "update_time" => time(),
            "cancel_time" => time(),
        ]);
        if (empty($result)) $this->error("暂无数据");

        $this->success("取消成功");
    }


    /**
     * 核销订单
     * @OA\Post(
     *     tags={"体验卡订单管理"},
     *     path="/wxapp/exp_order/verification_order",
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
     *         name="order_num",
     *         in="query",
     *         description="id 订单号三选一 ",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *    @OA\Parameter(
     *         name="cav_code",
     *         in="query",
     *         description="code 三选一",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/exp_order/find_order
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/exp_order/find_order
     *   api:  /wxapp/exp_order/find_order
     *   remark_name: 体验卡订单管理 详情
     *
     */
    public function verification_order()
    {
        $this->checkAuth(2);
        $ExpOrderModel = new \initmodel\ExpOrderModel(); //体验卡订单管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ["order_num", "=", $params["order_num"]];
        if ($params['cav_code']) $where[] = ["cav_code", "=", $params["cav_code"]];


        $order_info = $ExpOrderModel->where($where)->find();
        if (empty($order_info)) $this->error("暂无数据");


        if ($order_info['status'] != 2) $this->error("状态错误");
        $result = $ExpOrderModel->where($where)->strict(false)->update([
            "cav_user_id"     => $this->user_id,
            "cav_username"    => $this->user_info['nickname'],
            "status"          => 8,
            "update_time"     => time(),
            "accomplish_time" => time(),
        ]);
        if (empty($result)) $this->error("暂无数据");

        $this->success("核销成功");
    }


    /**
     * 核销记录
     * @OA\Post(
     *     tags={"体验卡订单管理"},
     *     path="/wxapp/exp_order/find_verification_list",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://height.ikun:9090/api/wxapp/exp_order/find_verification_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/exp_order/find_verification_list
     *   api:  /wxapp/exp_order/find_verification_list
     *   remark_name: 核销记录
     *
     */
    public function find_verification_list()
    {
        $this->checkAuth(2);


        $ExpOrderInit  = new \init\ExpOrderInit();//体验卡订单管理   (ps:InitController)
        $ExpOrderModel = new \initmodel\ExpOrderModel(); //体验卡订单管理   (ps:InitModel)

        /** 获取参数 **/
        $params                = $this->request->param();
        $params["cav_user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ["status", "in", [2, 8, 11]];
        if ($params["keyword"]) $where[] = ["order_num|username|phone|goods_name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $ExpOrderInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $ExpOrderInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);

    }


}
