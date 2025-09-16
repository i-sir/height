<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"ShopOrderRefund",
 *     "name_underline"          =>"shop_order_refund",
 *     "controller_name"         =>"ShopOrderRefund",
 *     "table_name"              =>"shop_order_refund",
 *     "remark"                  =>"退款管理"
 *     "api_url"                 =>"/api/wxapp/shop_order_refund/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-06-06 10:53:27",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\ShopOrderRefundController();
 *     "test_environment"        =>"http://seed.ikun:9090/api/wxapp/shop_order_refund/index",
 *     "official_environment"    =>"https://xcxkf022.aubye.com/api/wxapp/shop_order_refund/index",
 * )
 */


use init\StockInit;
use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class ShopOrderRefundController extends AuthController
{

    //public function initialize(){
    //	//退款管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/shop_order_refund/index
     * https://xcxkf022.aubye.com/api/wxapp/shop_order_refund/index
     */
    public function index()
    {
        $ShopOrderRefundInit  = new \init\ShopOrderRefundInit();//退款管理   (ps:InitController)
        $ShopOrderRefundModel = new \initmodel\ShopOrderRefundModel(); //退款管理   (ps:InitModel)

        $result = [];

        $this->success('退款管理-接口请求成功', $result);
    }


    /**
     * 退款  详情
     * @OA\Post(
     *     tags={"退款管理"},
     *     path="/wxapp/shop_order_refund/find_refund",
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="detail_id",
     *         in="query",
     *         description="detail_id 详情id",
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
     *   test_environment: http://seed.ikun:9090/api/wxapp/shop_order_refund/find_refund
     *   official_environment: https://xcxkf022.aubye.com/api/wxapp/shop_order_refund/find_refund
     *   api:  /wxapp/shop_order_refund/find_refund
     *   remark_name: 退款  详情
     *
     */
    public function find_refund()
    {
        $ShopOrderRefundInit  = new \init\ShopOrderRefundInit();//退款管理    (ps:InitController)
        $ShopOrderRefundModel = new \initmodel\ShopOrderRefundModel(); //退款管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        //查询条件
        $where   = [];
        $where[] = ["detail_id", "=", $params["detail_id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ShopOrderRefundInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


    /**
     * 申请退款
     * @OA\Post(
     *     tags={"退款管理"},
     *     path="/wxapp/shop_order_refund/add_refund",
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
     *         description="售后类型:1退货退款,2换货",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="refund_why",
     *         in="query",
     *         description="换货原因  文字",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="detail_id",
     *         in="query",
     *         description="详情id 订单列表goods_list 中的id ",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="amount",
     *         in="query",
     *         description="退款金额 (订单 商品列表有 max_refund_amount 最多退款金额)",
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
     *         name="content",
     *         in="query",
     *         description="说明",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="images",
     *         in="query",
     *         description="图集     (数组格式)",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="sku_id",
     *         in="query",
     *         description="更换规格id",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="sku_name",
     *         in="query",
     *         description="更换规格",
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
     *   test_environment: http://seed.ikun:9090/api/wxapp/shop_order_refund/add_refund
     *   official_environment: https://xcxkf022.aubye.com/api/wxapp/shop_order_refund/add_refund
     *   api:  /wxapp/shop_order_refund/add_refund
     *   remark_name: 申请退款
     *
     */
    public function add_refund()
    {
        $this->checkAuth();

        // 启动事务
        Db::startTrans();


        $ShopOrderRefundInit  = new \init\ShopOrderRefundInit();//退款管理    (ps:InitController)
        $ShopOrderRefundModel = new \initmodel\ShopOrderRefundModel(); //退款管理   (ps:InitModel)
        $ShopOrderModel       = new \initmodel\ShopOrderModel(); //订单管理  (ps:InitModel)
        $ShopOrderDetailModel = new \initmodel\ShopOrderDetailModel();//订单详情
        $ShopGoodsModel       = new \initmodel\ShopGoodsModel();//商品
        $StockInit            = new StockInit();//规格方法引入


        /** 获取参数 **/
        //参数
        $params               = $this->request->param();
        $params["user_id"]    = $this->user_id;
        $params["refund_num"] = $this->get_num_only('refund_num');


        //订单详情
        $order_detail        = $ShopOrderDetailModel->where('id', '=', $params['detail_id'])->find();
        $params['count']     = $order_detail['count'];
        $params['order_num'] = $order_detail['order_num'];
        if (empty($params['amount'])) $params['amount'] = $order_detail['max_refund_amount'];


        if ($params['amount'] > $order_detail['max_refund_amount']) $this->error("退款金额不能大于订单可退金额");

        //订单信息
        $order_info             = $ShopOrderModel->where('order_num', '=', $order_detail['order_num'])->find();
        $params['pay_num']      = $order_info['pay_num'];
        $params['order_amount'] = $order_info['amount'];

        //商品
        $goods_info           = $ShopGoodsModel->where('id', '=', $params['goods_id'])->find();
        $params['goods_name'] = $goods_info['goods_name'];
        $params['image']      = cmf_get_asset_url($goods_info['image']);

        //检测是否已经提交
        $where       = [];
        $where[]     = ['detail_id', '=', $params['detail_id']];
        $refund_info = $ShopOrderRefundModel->where($where)->find();
        if ($refund_info) $this->error("该订单已提交过退款申请");

        //订单详情退款状态修改为待审核
        $ShopOrderDetailModel->where('id', '=', $params['detail_id'])->strict(false)->update([
            'status'      => 1,
            'update_time' => time()
        ]);

        //更改订单状态
        $map    = [];
        $map[]  = ['order_num', '=', $order_detail['order_num']];
        $result = $ShopOrderModel->where($map)->strict(false)->update(['status' => 12, 'update_time' => time()]);
        if (empty($result)) $this->error("失败请重试!!");


        //插入退款管理
        $result = $ShopOrderRefundInit->api_edit_post($params);
        if (empty($result)) $this->error("失败请重试!!");


        //售后类型:1退货退款,2换货
        if ($params['type'] == 2) {
            //退货增加库存 & 拿到货在进行增加,或者不增加
            //$StockInit->inc_stock($order_detail['sku_id'], $order_detail['count']);

            //换货的减少库存
            //$StockInit->dec_stock('shop_goods', $params['sku_id'], $order_detail['count']);
        }


        // 提交事务
        Db::commit();


        $this->success('提交成功,等待审核!');
    }


    /**
     * 申请通过,完善快递信息
     * @OA\Post(
     *     tags={"退款管理"},
     *     path="/wxapp/shop_order_refund/set_exp",
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
     *         name="detail_id",
     *         in="query",
     *         description="详情id",
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
     *         name="exp_num",
     *         in="query",
     *         description="快递单号",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="exp_name",
     *         in="query",
     *         description="快递名称",
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
     *   test_environment: http://seed.ikun:9090/api/wxapp/shop_order_refund/add_refund
     *   official_environment: https://xcxkf022.aubye.com/api/wxapp/shop_order_refund/add_refund
     *   api:  /wxapp/shop_order_refund/add_refund
     *   remark_name: 申请退款
     *
     */
    public function set_exp()
    {
        $this->checkAuth();
        $ShopOrderRefundInit  = new \init\ShopOrderRefundInit();//退款管理    (ps:InitController)
        $ShopOrderRefundModel = new \initmodel\ShopOrderRefundModel(); //退款管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;


        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];


        /** 提交更新 **/
        $result = $ShopOrderRefundInit->api_edit_post($params, $where);
        if (empty($result)) $this->error("失败请重试");

        $this->success('提交成功');
    }


}
