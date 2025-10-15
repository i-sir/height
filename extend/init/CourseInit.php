<?php

namespace init;


/**
 * @Init(
 *     "name"            =>"Course",
 *     "name_underline"  =>"course",
 *     "table_name"      =>"course",
 *     "model_name"      =>"CourseModel",
 *     "remark"          =>"课程计划",
 *     "author"          =>"",
 *     "create_time"     =>"2025-09-18 11:42:28",
 *     "version"         =>"1.0",
 *     "use"             => new \init\CourseInit();
 * )
 */

use think\facade\Db;
use app\admin\controller\ExcelController;


class CourseInit extends Base
{

    public $is_show   = [1 => '是', 2 => '否'];//显示
    public $is_unlock = [1 => '可解锁', 2 => '锁定', 3 => '已购买'];


    protected $Field         = "*";//过滤字段,默认全部
    protected $Limit         = 100000;//如不分页,展示条数
    protected $PageSize      = 15;//分页每页,数据条数
    protected $Order         = "list_order,id desc";//排序
    protected $InterfaceType = "api";//接口类型:admin=后台,api=前端
    protected $DataFormat    = "find";//数据格式,find详情,list列表

    //本init和model
    public function _init()
    {
        $CourseInit  = new \init\CourseInit();//课程计划   (ps:InitController)
        $CourseModel = new \initmodel\CourseModel(); //课程计划  (ps:InitModel)
    }

    /**
     * 处理公共数据
     * @param array $item   单条数据
     * @param array $params 参数
     * @return array|mixed
     */
    public function common_item($item = [], $params = [])
    {
        $CourseStudyModel     = new \initmodel\CourseStudyModel(); //学习记录   (ps:InitModel)
        $CoursePlanModel      = new \initmodel\CoursePlanModel(); //计划管理   (ps:InitModel)
        $CourseSetModel       = new \initmodel\CourseSetModel(); //收费配置   (ps:InitModel)
        $CourseMarketingModel = new \initmodel\CourseMarketingModel(); //营销管理   (ps:InitModel)


        //接口类型
        if ($params['InterfaceType']) $this->InterfaceType = $params['InterfaceType'];
        //数据格式
        if ($params['DataFormat']) $this->DataFormat = $params['DataFormat'];


        /** 数据格式(公共部分),find详情&&list列表 共存数据 **/
        if (in_array($item['id'], $params['paid_course_ids'])) {
            $item['is_unlock'] = 3; // 已购买
        } else {
            if ($item['list_order'] == $params['next_list_order']) {
                $item['is_unlock'] = 1; // 可解锁
            } else {
                $item['is_unlock'] = 2; // 不可解锁
            }
        }
        $item['is_unlock_name'] = $this->is_unlock[$item['is_unlock']];


        //检测是否需要关联购买,获取价格
        $map      = [];
        $map[]    = ['', 'EXP', Db::raw("FIND_IN_SET({$item['id']},course_ids)")];
        $set_info = $CourseSetModel
            ->where($map)
            ->order('id desc')
            ->find();
        if ($set_info) $item['price'] = $set_info['price'];

        //完成课时
        $item['accomplish_number'] = $CourseStudyModel
            ->where('user_id', $params['user_id'])
            ->where('course_id', '=', $item['id'])
            ->group('plan_id')
            ->where('status', 2)
            ->count();


        //总课时
        $item['total_number'] = $CoursePlanModel
            ->where('is_show', '=', 1)
            ->where('course_id', '=', $item['id'])
            ->count();


        //完成进度条
        $item['progress'] = $item['total_number'] > 0 ? round($item['accomplish_number'] / $item['total_number'] * 100) : 0;


        //营销板块
        if ($set_info['is_marketing'] == 1) {
            //营销次数,营销倒计时
            $item['marketing_time']   = 0;
            $item['marketing_number'] = 0;

            //第一次营销
            $map1000        = [];
            $map1000[]      = ['user_id', '=', $params['user_id']];
            $map1000[]      = ['course_id', '=', $item['id']];
            $map1000[]      = ['number', '=', 1];
            $marketing_info = $CourseMarketingModel
                ->where($map1000)
                ->order('id')
                ->find();
            if ($marketing_info) {
                if ($marketing_info['end_time'] < time()) {
                    //第一次营销 结束
                    $map2000         = [];
                    $map2000[]       = ['user_id', '=', $params['user_id']];
                    $map2000[]       = ['course_id', '=', $item['id']];
                    $map2000[]       = ['number', '=', 2];
                    $marketing_info2 = $CourseMarketingModel
                        ->where($map2000)
                        ->order('id')
                        ->find();
                    if ($marketing_info2) {
                        $item['marketing_number'] = 2;
                        if ($marketing_info2['end_time'] > time()) {
                            $item['marketing_time'] = $marketing_info2['end_time'] - time();
                            //第二次营销价格
                            $item['price'] = $set_info['marketing_price'];
                        }
                    } else {
                        $item['marketing_number'] = 1;
                    }


                } else {
                    $item['marketing_time']   = $marketing_info['end_time'] - time();
                    $item['price']            = $set_info['marketing_price'];
                    $item['marketing_number'] = 1;
                }
            }
        }


        /** 处理文字描述 **/
        $item['is_show_name'] = $this->is_show[$item['is_show']];//显示


        /** 处理数据 **/
        if ($this->InterfaceType == 'api') {
            /** api处理文件 **/


            /** 处理富文本 **/


            if ($this->DataFormat == 'find') {
                /** find详情数据格式 **/


            } else {
                /** list列表数据格式 **/

            }


        } else {
            /** admin处理文件 **/


            if ($this->DataFormat == 'find') {
                /** find详情数据格式 **/


                /** 处理富文本 **/


            } else {
                /** list列表数据格式 **/

            }

        }


        /** 导出数据处理 **/
        if (isset($params["is_export"]) && $params["is_export"]) {
            $item["create_time"] = date("Y-m-d H:i:s", $item["create_time"]);
            $item["update_time"] = date("Y-m-d H:i:s", $item["update_time"]);
        }

        return $item;
    }


    /**
     * 获取列表
     * @param $where  条件
     * @param $params 扩充参数 order=排序  field=过滤字段 limit=限制条数  InterfaceType=admin|api后端,前端
     * @return false|mixed
     */
    public function get_list($where = [], $params = [])
    {
        $CourseModel = new \initmodel\CourseModel(); //课程计划  (ps:InitModel)


        /** 查询数据 **/
        $result = $CourseModel
            ->where($where)
            ->order($params['order'] ?? $this->Order)
            ->field($params['field'] ?? $this->Field)
            ->limit($params["limit"] ?? $this->Limit)
            ->select()
            ->each(function ($item, $key) use ($params) {

                /** 处理公共数据 **/
                $item = $this->common_item($item, $params);

                return $item;
            });

        /** 根据接口类型,返回不同数据类型 **/
        if ($params['InterfaceType']) $this->InterfaceType = $params['InterfaceType'];
        if ($this->InterfaceType == 'api' && empty(count($result))) return false;

        return $result;
    }


    /**
     * 分页查询
     * @param $where  条件
     * @param $params 扩充参数 order=排序  field=过滤字段 page_size=每页条数  InterfaceType=admin|api后端,前端
     * @return mixed
     */
    public function get_list_paginate($where = [], $params = [])
    {
        $CourseModel = new \initmodel\CourseModel(); //课程计划  (ps:InitModel)


        /** 查询数据 **/
        $result = $CourseModel
            ->where($where)
            ->order($params['order'] ?? $this->Order)
            ->field($params['field'] ?? $this->Field)
            ->paginate(["list_rows" => $params["page_size"] ?? $this->PageSize, "query" => $params])
            ->each(function ($item, $key) use ($params) {

                /** 处理公共数据 **/
                $item = $this->common_item($item, $params);

                return $item;
            });

        /** 根据接口类型,返回不同数据类型 **/
        if ($params['InterfaceType']) $this->InterfaceType = $params['InterfaceType'];
        if ($this->InterfaceType == 'api' && $result->isEmpty()) return [null];


        return $result;
    }

    /**
     * 获取列表
     * @param $where  条件
     * @param $params 扩充参数 order=排序  field=过滤字段 limit=限制条数  InterfaceType=admin|api后端,前端
     * @return false|mixed
     */
    public function get_join_list($where = [], $params = [])
    {
        $CourseModel = new \initmodel\CourseModel(); //课程计划  (ps:InitModel)

        /** 查询数据 **/
        $result = $CourseModel
            ->alias('a')
            ->join('member b', 'a.user_id = b.id')
            ->where($where)
            ->order('a.id desc')
            ->field('a.*')
            ->paginate(["list_rows" => $params["page_size"] ?? $this->PageSize, "query" => $params])
            ->each(function ($item, $key) use ($params) {

                /** 处理公共数据 **/
                $item = $this->common_item($item, $params);


                return $item;
            });

        /** 根据接口类型,返回不同数据类型 **/
        if ($params['InterfaceType']) $this->InterfaceType = $params['InterfaceType'];
        if ($this->InterfaceType == 'api' && empty(count($result))) return false;

        return $result;
    }


    /**
     * 获取详情
     * @param $where     条件 或 id值
     * @param $params    扩充参数 field=过滤字段  InterfaceType=admin|api后端,前端
     * @return false|mixed
     */
    public function get_find($where = [], $params = [])
    {
        $CourseModel = new \initmodel\CourseModel(); //课程计划  (ps:InitModel)

        /** 可直接传id,或者where条件 **/
        if (is_string($where) || is_int($where)) $where = ["id" => (int)$where];
        if (empty($where)) return false;

        /** 查询数据 **/
        $item = $CourseModel
            ->where($where)
            ->order($params['order'] ?? $this->Order)
            ->field($params['field'] ?? $this->Field)
            ->find();


        if (empty($item)) return false;


        /** 处理公共数据 **/
        $item = $this->common_item($item, $params);


        return $item;
    }


    /**
     * 前端  编辑&添加
     * @param $params 参数
     * @param $where  where条件
     * @return void
     */
    public function api_edit_post($params = [], $where = [])
    {
        $result = false;

        /** 接口提交,处理数据 **/


        $result = $this->edit_post($params, $where);//api提交

        return $result;
    }


    /**
     * 后台  编辑&添加
     * @param $model  类
     * @param $params 参数
     * @param $where  更新提交(编辑数据使用)
     * @return void
     */
    public function admin_edit_post($params = [], $where = [])
    {
        $result = false;

        /** 后台提交,处理数据 **/


        $result = $this->edit_post($params, $where);//admin提交

        return $result;
    }


    /**
     * 提交 编辑&添加
     * @param $params
     * @param $where where条件(或传id)
     * @return void
     */
    public function edit_post($params, $where = [])
    {
        $CourseModel = new \initmodel\CourseModel(); //课程计划  (ps:InitModel)


        /** 查询详情数据 && 需要再打开 **/
        //if (!empty($params["id"])) $item = $this->get_find(["id" => $params["id"]],["DataFormat"=>"list"]);
        //if (empty($params["id"]) && !empty($where)) $item = $this->get_find($where,["DataFormat"=>"list"]);

        /** 可直接传id,或者where条件 **/
        if (is_string($where) || is_int($where)) $where = ["id" => (int)$where];


        /** 公共提交,处理数据 **/


        if (!empty($where)) {
            //传入where条件,根据条件更新数据
            $params["update_time"] = time();
            $result                = $CourseModel->where($where)->strict(false)->update($params);
            //if ($result) $result = $item["id"];
        } elseif (!empty($params["id"])) {
            //如传入id,根据id编辑数据
            $params["update_time"] = time();
            $result                = $CourseModel->where("id", "=", $params["id"])->strict(false)->update($params);
            //if($result) $result = $item["id"];
        } else {
            //无更新条件则添加数据
            $params["create_time"] = time();
            $result                = $CourseModel->strict(false)->insert($params, true);
        }

        return $result;
    }


    /**
     * 提交(副本,无任何操作,不查询详情,不返回id) 编辑&添加
     * @param $params
     * @param $where where 条件(或传id)
     * @return void
     */
    public function edit_post_two($params, $where = [])
    {
        $CourseModel = new \initmodel\CourseModel(); //课程计划  (ps:InitModel)


        /** 可直接传id,或者where条件 **/
        if (is_string($where) || is_int($where)) $where = ["id" => (int)$where];


        /** 公共提交,处理数据 **/


        if (!empty($where)) {
            //传入where条件,根据条件更新数据
            $params["update_time"] = time();
            $result                = $CourseModel->where($where)->strict(false)->update($params);
        } elseif (!empty($params["id"])) {
            //如传入id,根据id编辑数据
            $params["update_time"] = time();
            $result                = $CourseModel->where("id", "=", $params["id"])->strict(false)->update($params);
        } else {
            //无更新条件则添加数据
            $params["create_time"] = time();
            $result                = $CourseModel->strict(false)->insert($params);
        }

        return $result;
    }


    /**
     * 删除数据 软删除
     * @param $id     传id  int或array都可以
     * @param $type   1软删除 2真实删除
     * @param $params 扩充参数
     * @return void
     */
    public function delete_post($id, $type = 1, $params = [])
    {
        $CourseModel = new \initmodel\CourseModel(); //课程计划  (ps:InitModel)


        if ($type == 1) $result = $CourseModel->destroy($id);//软删除 数据表字段必须有delete_time
        if ($type == 2) $result = $CourseModel->destroy($id, true);//真实删除

        return $result;
    }


    /**
     * 后台批量操作
     * @param $id
     * @param $params 修改值
     * @return void
     */
    public function batch_post($id, $params = [])
    {
        $CourseModel = new \initmodel\CourseModel(); //课程计划  (ps:InitModel)

        $where   = [];
        $where[] = ["id", "in", $id];//$id 为数组


        $params["update_time"] = time();
        $result                = $CourseModel->where($where)->strict(false)->update($params);//修改状态

        return $result;
    }


    /**
     * 后台  排序
     * @param $list_order 排序
     * @param $params     扩充参数
     * @return void
     */
    public function list_order_post($list_order, $params = [])
    {
        $CourseModel = new \initmodel\CourseModel(); //课程计划   (ps:InitModel)

        foreach ($list_order as $k => $v) {
            $where   = [];
            $where[] = ["id", "=", $k];
            $result  = $CourseModel->where($where)->strict(false)->update(["list_order" => $v, "update_time" => time()]);//排序
        }

        return $result;
    }


    /**
     * 导出数据
     * @param array $where 条件
     */
    public function export_excel($where = [], $params = [])
    {
        $CourseInit  = new \init\CourseInit();//课程计划   (ps:InitController)
        $CourseModel = new \initmodel\CourseModel(); //课程计划  (ps:InitModel)

        $result = $CourseInit->get_list($where, $params);

        $result = $result->toArray();
        foreach ($result as $k => &$item) {

            //订单号过长问题
            if ($item["order_num"]) $item["order_num"] = $item["order_num"] . "\t";

            //图片链接 可用默认浏览器打开   后面为展示链接名字 --单独,多图特殊处理一下
            if ($item["image"]) $item["image"] = '=HYPERLINK("' . cmf_get_asset_url($item['image']) . '","图片.png")';


            //用户信息
            $user_info        = $item['user_info'];
            $item['userInfo'] = "(ID:{$user_info['id']}) {$user_info['nickname']}  {$user_info['phone']}";


            //背景颜色
            if ($item['unit'] == '测试8') $item['BackgroundColor'] = 'red';
        }

        $headArrValue = [
            ["rowName" => "ID", "rowVal" => "id", "width" => 10],
            ["rowName" => "用户信息", "rowVal" => "userInfo", "width" => 30],
            ["rowName" => "名字", "rowVal" => "name", "width" => 20],
            ["rowName" => "年龄", "rowVal" => "age", "width" => 20],
            ["rowName" => "测试", "rowVal" => "test", "width" => 20],
            ["rowName" => "创建时间", "rowVal" => "create_time", "width" => 30],
        ];


        //副标题 纵单元格
        //        $subtitle = [
        //            ["rowName" => "列1", "acrossCells" => count($headArrValue)/2],
        //            ["rowName" => "列2", "acrossCells" => count($headArrValue)/2],
        //        ];

        $Excel = new ExcelController();
        $Excel->excelExports($result, $headArrValue, ["fileName" => "课程计划"]);
    }

}
