<?php

namespace init;

use api\wxapp\controller\InitController;
use plugins\weipay\lib\PayController;
use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;

/**
 * 定时任务
 */
class TaskInit
{


    /**
     * 打卡数量 随机增加
     */
    public function operation_punch()
    {
        $randomly_increase_number = cmf_config('randomly_increase_number'); //随机增加数

        if (date('H:i') == "00:00") {
            //累计打卡人数
            $map                = [];
            $map[]              = ['name', '=', 'accumulated_number'];
            $accumulated_number = cmf_config('accumulated_number'); //设置虚拟数+实际人数
            Db::name('base_config')->where($map)->strict(false)->update([
                'value'       => serialize($accumulated_number + rand(30, 60)),
                'update_time' => time(),
            ]);

            //今日打卡人数
            $map200       = [];
            $map200[]     = ['name', '=', 'number_clock'];
            $number_clock = cmf_config('number_clock');//设置虚拟数+实际人数
            Db::name('base_config')->where($map200)->strict(false)->update([
                'value'       => serialize($number_clock + rand(30, 60)),
                'update_time' => time(),
            ]);
        }


        //累计时长
        //        $map100              = [];
        //        $map100[]            = ['name', '=', 'cumulative_duration'];
        //        $cumulative_duration = cmf_config('cumulative_duration');//设置虚拟数+实际人数
        //        Db::name('base_config')->where($map100)->strict(false)->update([
        //            'value'       => serialize($cumulative_duration + rand(0, $randomly_increase_number)),
        //            'update_time' => time(),
        //        ]);


        echo("打卡数量 随机增加,执行成功\n" . cmf_random_string(80) . "\n" . date('Y-m-d H:i:s') . "\n");
    }


    /**
     * 自动取消订单
     */
    public function operation_cancel_order()
    {
        $ShopOrderModel       = new \initmodel\ShopOrderModel(); //商城订单   (ps:InitModel)
        $ShopOrderDetailModel = new \initmodel\ShopOrderDetailModel();//订单详情
        $ShopCouponUserModel  = new \initmodel\ShopCouponUserModel(); //优惠券领取记录   (ps:InitModel)
        $StockInit            = new \init\StockInit();
        $Pay                  = new PayController();
        $OrderPayModel        = new \initmodel\OrderPayModel();

        $map   = [];
        $map[] = ['auto_cancel_time', '<', time()];
        $map[] = ['status', '=', 1];
        $list  = $ShopOrderModel->where($map)->select();

        foreach ($list as $key => $order_info) {

            //微信支付取消 && 不让再次支付了
            if (empty($order_info['pay_num'])) {
                $map300   = [];
                $map300[] = ['order_num', '=', $order_info['order_num']];
                $pay_num  = $OrderPayModel->where($map300)->value('pay_num');
            } else {
                $pay_num = $order_info['pay_num'];
            }
            if ($pay_num) $Pay->close_order($pay_num);


            //添加库存
            $order_detail = $ShopOrderDetailModel->where('order_num', '=', $order_info['order_num'])->select();
            foreach ($order_detail as $k => $v) {
                $StockInit->inc_stock('shop_goods', $v['sku_id'], $v['count'], $v['goods_id'], $order_info['order_num']);
            }


            //优惠券退回
            if ($order_info['coupon_id']) {
                $ShopCouponUserModel->where('id', '=', $order_info['coupon_id'])->update(['used' => 1, 'update_time' => time()]);
            }
        }

        $ShopOrderModel->where($map)->strict(false)->update([
            'status'      => 10,
            'cancel_time' => time(),
            'update_time' => time(),
        ]);


        echo("自动取消订单,执行成功\n" . cmf_random_string(80) . "\n" . date('Y-m-d H:i:s') . "\n");
    }


    /**
     * 自动完成订单
     */
    public function operation_accomplish_order()
    {
        $ShopOrderModel = new \initmodel\ShopOrderModel(); //商城订单   (ps:InitModel)
        $InitController = new InitController();//基础接口


        $map   = [];
        $map[] = ['auto_accomplish_time', '<', time()];
        $map[] = ['status', '=', 4];

        $list = $ShopOrderModel->where($map)->field('id,order_num')->select();
        foreach ($list as $k => $order_info) {
            //这里处理订单完成后的逻辑
            $InitController->sendShopOrderAccomplish($order_info['order_num']);
        }

        $ShopOrderModel->where($map)->strict(false)->update([
            'status'          => 8,
            'accomplish_time' => time(),
            'update_time'     => time(),
        ]);


        echo("自动取消订单,执行成功\n" . cmf_random_string(80) . "\n" . date('Y-m-d H:i:s') . "\n");
    }


    /**
     * 更新vip状态
     */
    public function operation_vip()
    {
        $MemberModel = new \initmodel\MemberModel();//用户管理

        //操作vip   vip_time vip到期时间
        //$MemberModel->where('vip_time', '<', time())->update(['is_vip' => 0]);
        echo("更新vip状态,执行成功\n" . cmf_random_string(80) . "\n" . date('Y-m-d H:i:s') . "\n");
    }


    /**
     * 将公众号的official_openid存入member表中
     */
    public function update_official_openid()
    {
        $gzh_list = Db::name('member_gzh')->select();
        foreach ($gzh_list as $k => $v) {
            Db::name('member')->where('unionid', '=', $v['unionid'])->update(['official_openid' => $v['openid']]);
        }

        echo("将公众号的official_openid存入member表中,执行成功\n" . cmf_random_string(80) . "\n" . date('Y-m-d H:i:s') . "\n");
    }

}