<?php

namespace api\wxapp\controller;

use initmodel\AssetModel;
use initmodel\MemberModel;

/**
 * @ApiController(
 *     "name"                    =>"Init",
 *     "name_underline"          =>"init",
 *     "controller_name"         =>"Init",
 *     "table_name"              =>"无",
 *     "remark"                  =>"基础接口,封装的接口"
 *     "api_url"                 =>"/api/wxapp/init/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2024-04-24 17:16:22",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\InitController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/init/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/init/index",
 * )
 */
class InitController
{
    /**
     * 本模块,用于封装常用方法,复用方法
     */


    /**
     * 给上级发放佣金
     * @param $p_user_id 上级id
     * @param $child_id  子级id
     *                   https://xcxkf173.aubye.com/api/wxapp/init/send_invitation_commission?p_user_id=1
     */
    public function sendInvitationCommission($p_user_id = 0, $child_id = 0)
    {
        //邀请佣金
        $price  = cmf_config('invitation_rewards');
        $remark = "操作人[邀请奖励];操作说明[邀请好友得佣金];操作类型[佣金奖励];";//管理备注

        AssetModel::incAsset('邀请注册奖励,给上级发放佣金 [120]', [
            'operate_type'  => 'balance',//操作类型，balance|point ...
            'identity_type' => 'member',//身份类型，member| ...
            'user_id'       => $p_user_id,
            'price'         => $price,
            'order_num'     => cmf_order_sn(),
            'order_type'    => 120,
            'content'       => '邀请奖励',
            'remark'        => $remark,
            'order_id'      => 0,
            'child_id'      => $child_id
        ]);

        return true;
    }


    /**
     * 订单完成,赠送积分
     * @param $order_num
     */
    public function sendShopOrderAccomplish($order_num)
    {
        $ShopOrderModel      = new \initmodel\ShopOrderModel();//订单管理
        $MemberModel         = new \initmodel\MemberModel();//用户管理
        $ShopCouponUserModel = new \initmodel\ShopCouponUserModel(); //优惠券领取记录   (ps:InitModel)


        $map        = [];
        $map[]      = ['order_num', '=', $order_num];
        $order_info = $ShopOrderModel->where($map)->find();
        if (empty($order_info)) return false;


        //给下单用户赠送优惠券
        $coupon_validity_period = cmf_config('coupon_validity_period'); //下单成功赠送订单金额的优惠,有效期n天

        $code     = 'Y' . uniqid(mt_rand());
        $qr_image = '';

        /** 发放优惠券 **/
        $ShopCouponUserModel->strict(false)->insert([
            'user_id'     => $order_info['user_id'],
            'coupon_id'   => 0,
            'name'        => '优惠券',
            'full_amount' => $order_info['amount'] + 0.01,
            'amount'      => $order_info['amount'],
            'discount'    => '下单赠送优惠券',
            'type'        => 2,
            'coupon_type' => 1,
            'end_time'    => time() + ($coupon_validity_period * 86400),
            'code'        => $code,
            'qr_image'    => $qr_image,
            'start_time'  => time(),
            'create_time' => time(),
        ]);


        //查询上级
        $p_user_id = $MemberModel->where('id', '=', $order_info['user_id'])->value('pid');
        if ($p_user_id && $order_info['commission']) {
            $remark = "操作人[下单得佣金];操作说明[下单得佣金];操作类型[下单得佣金];";//管理备注
            AssetModel::incAsset('下单得佣金,给上级发放佣金 [120]', [
                'operate_type'  => 'balance',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $p_user_id,
                'price'         => $order_info['commission'],
                'order_num'     => $order_num,
                'order_type'    => 120,
                'content'       => '商城下单奖励',
                'remark'        => $remark,
                'order_id'      => $order_info['id'],
            ]);

            //查询上上级
            $sp_user_id = $MemberModel->where('id', '=', $p_user_id)->value('pid');
            if ($sp_user_id && $order_info['commission2']) {
                $remark = "操作人[下单得佣金];操作说明[下单得佣金];操作类型[下单得佣金];";//管理备注
                AssetModel::incAsset('下单得佣金,给上级发放佣金 [130]', [
                    'operate_type'  => 'balance',//操作类型，balance|point ...
                    'identity_type' => 'member',//身份类型，member| ...
                    'user_id'       => $sp_user_id,
                    'price'         => $order_info['commission2'],
                    'order_num'     => $order_num,
                    'order_type'    => 130,
                    'content'       => '商城下单奖励',
                    'remark'        => $remark,
                    'order_id'      => $order_info['id'],
                ]);
            }
        }

        return true;
    }












}