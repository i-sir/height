<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Cooperation",
 *     "name_underline"          =>"cooperation",
 *     "controller_name"         =>"Cooperation",
 *     "table_name"              =>"cooperation",
 *     "remark"                  =>"合作申请"
 *     "api_url"                 =>"/api/wxapp/cooperation/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-09-17 10:47:34",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\CooperationController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/cooperation/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/cooperation/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class CooperationController extends AuthController
{

    //public function initialize(){
    //	//合作申请
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/cooperation/index
     * https://xcxkf173.aubye.com/api/wxapp/cooperation/index
     */
    public function index()
    {
        $CooperationInit  = new \init\CooperationInit();//合作申请   (ps:InitController)
        $CooperationModel = new \initmodel\CooperationModel(); //合作申请   (ps:InitModel)

        $result = [];

        $this->success('合作申请-接口请求成功', $result);
    }


    /**
     * 下单
     * @OA\Post(
     *     tags={"合作记录"},
     *     path="/wxapp/cooperation/add_cooperation",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://height.ikun:9090/api/wxapp/cooperation/add_cooperation
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/cooperation/add_cooperation
     *   api:  /wxapp/cooperation/add_cooperation
     *   remark_name: 下单
     *
     */
    public function add_cooperation()
    {
        $this->checkAuth();

        $CooperationModel = new \initmodel\CooperationModel(); //合作申请   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;


        //合作申请,需要支付金额,不可以为0
        $cooperation_application_price = cmf_config('cooperation_application_price');

        $order_num             = $this->get_num_only();
        $insert['order_num']   = $order_num;
        $insert['user_id']     = $this->user_id;
        $insert['phone']       = $this->user_info['phone'];
        $insert['amount']      = $cooperation_application_price;
        $insert['create_time'] = time();


        /** 提交更新 **/
        $result = $CooperationModel->strict(false)->insert($insert);
        if (empty($result)) $this->error("失败请重试");


        $this->success('下单成功', ['order_type' => 20, 'order_num' => $order_num]);
    }


}
