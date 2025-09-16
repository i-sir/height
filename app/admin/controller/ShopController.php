<?php

namespace app\admin\controller;


/**
 * @adminMenuRoot(
 *     "name"                =>"Shop",
 *     "name_underline"      =>"shop",
 *     "controller_name"     =>"Shop",
 *     "table_name"          =>"shop",
 *     "action"              =>"default",
 *     "parent"              =>"",
 *     "display"             => true,
 *     "order"               => 10000,
 *     "icon"                =>"none",
 *     "remark"              =>"店铺管理",
 *     "author"              =>"",
 *     "create_time"         =>"2025-09-16 11:11:22",
 *     "version"             =>"1.0",
 *     "use"                 => new \app\admin\controller\ShopController();
 * )
 */


use think\facade\Db;
use cmf\controller\AdminBaseController;


class ShopController extends AdminBaseController
{

    // public function initialize(){
    //	//店铺管理
    //	parent::initialize();
    //	}


    /**
     * 首页基础信息
     */
    protected function base_index()
    {

    }

    /**
     * 编辑,添加基础信息
     */
    protected function base_edit()
    {

        $ShopClassInit = new \init\ShopClassInit();//店铺类型    (ps:InitController)
        $map           = [];
        $map[]         = ['is_show', '=', 1];
        $this->assign('class_list', $ShopClassInit->get_list($map));

    }



    public function getArea()
    {
        if (cache('admin_region_list')) {
            $area = cache('admin_region_list');
        } else {
            $area = Db::name('region')->where('parent_id', '=', 10000000)->field('id,name,code')->select()->each(function ($item, $key) {
                $item['cityList'] = Db::name("region")->where(['parent_id' => $item['id']])->field('id,name,code')->select()->each(function ($item1, $key) {
                    $item1['areaList'] = Db::name("region")->where(['parent_id' => $item1['id']])->field('id,name,code')->select()->each(function ($item2, $key) {
                        return $item2;
                    });
                    return $item1;
                });
                return $item;
            });
            cache("admin_region_list", $area);
        }
        $this->success('list', '', $area);
    }




    /**
     * 地址转换为坐标(高德地图)
     */
    public function search_address_ii()
    {
        $address = $this->request->param('address');
        $key     = "0f7cbfb881a2bea61d912a4cc920b663";

        $url    = "https://restapi.amap.com/v3/geocode/geo?address={$address}&key={$key}";
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        if ($result['status'] == 1) {

            $geocodes = $result['geocodes'];
            $return   = [];
            foreach ($geocodes as $item) {
                $location = explode(',', $item['location']);
                $return[] = ['lon' => $location[0], 'lat' => $location[1]];
            }
            $this->success('', '', $return);
        } else {
            $this->success('', '', $result['info']);
        }
    }

    /**
     * 坐标转换地址(高德地图)
     */
    public function reverse_address_ii()
    {
        $lng = $this->request->param('lng');
        $lat = $this->request->param('lat');
        $key = "0f7cbfb881a2bea61d912a4cc920b663";
        $url = "https://restapi.amap.com/v3/geocode/regeo?location={$lng},{$lat}&key={$key}";

        $result = file_get_contents($url);
        $result = json_decode($result, true);
        if ($result['status'] == 1) {

            $regeocode         = $result['regeocode'];
            $formatted_address = $regeocode['formatted_address'];
            $this->success('', '', $formatted_address);
        } else {
            $this->success('', '', $result['info']);
        }
    }


    /**
     * 首页列表数据
     * @adminMenu(
     *     'name'             => 'Shop',
     *     'name_underline'   => 'shop',
     *     'parent'           => 'index',
     *     'display'          => true,
     *     'hasView'          => true,
     *     'order'            => 10000,
     *     'icon'             => '',
     *     'remark'           => '店铺管理',
     *     'param'            => ''
     * )
     */
    public function index()
    {
        $this->base_index();//处理基础信息


        $ShopInit  = new \init\ShopInit();//店铺管理    (ps:InitController)
        $ShopModel = new \initmodel\ShopModel(); //店铺管理   (ps:InitModel)
        $params    = $this->request->param();

        /** 查询条件 **/
        $where = [];
        //$where[]=["type","=", 1];
        if ($params["keyword"]) $where[] = ["name|phone|address", "like", "%{$params["keyword"]}%"];
        if ($params["test"]) $where[] = ["test", "=", $params["test"]];


        //$where[] = $this->getBetweenTime($params['begin_time'], $params['end_time']);
        //if($params["status"]) $where[]=["status","=", $params["status"]];
        //$where[]=["type","=", 1];


        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段


        /** 导出数据 **/
        if ($params["is_export"]) $ShopInit->export_excel($where, $params);


        /** 查询数据 **/
        $result = $ShopInit->get_list_paginate($where, $params);


        /** 数据渲染 **/
        $this->assign("list", $result);
        $this->assign("pagination", $result->render());//单独提取分页出来
        $this->assign("page", $result->currentPage());//当前页码


        return $this->fetch();
    }


    //添加
    public function add()
    {
        $this->base_edit();//处理基础信息

        return $this->fetch();
    }


    //添加提交
    public function add_post()
    {
        $ShopInit  = new \init\ShopInit();//店铺管理   (ps:InitController)
        $ShopModel = new \initmodel\ShopModel(); //店铺管理   (ps:InitModel)
        $params    = $this->request->param();


        /** 检测参数信息 **/
        $validateResult = $this->validate($params, 'Shop');
        if ($validateResult !== true) $this->error($validateResult);


        /** 插入数据 **/
        $result = $ShopInit->admin_edit_post($params);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功", "index{$this->params_url}");
    }


    //查看详情
    public function find()
    {
        $this->base_edit();//处理基础信息

        $ShopInit  = new \init\ShopInit();//店铺管理    (ps:InitController)
        $ShopModel = new \initmodel\ShopModel(); //店铺管理   (ps:InitModel)
        $params    = $this->request->param();

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ShopInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        /** 数据格式转数组 **/
        $toArray = $result->toArray();
        foreach ($toArray as $k => $v) {
            $this->assign($k, $v);
        }

        return $this->fetch();
    }


    //编辑详情
    public function edit()
    {
        $this->base_edit();//处理基础信息

        $ShopInit  = new \init\ShopInit();//店铺管理  (ps:InitController)
        $ShopModel = new \initmodel\ShopModel(); //店铺管理   (ps:InitModel)
        $params    = $this->request->param();

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ShopInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        /** 数据格式转数组 **/
        $toArray = $result->toArray();
        foreach ($toArray as $k => $v) {
            $this->assign($k, $v);
        }

        return $this->fetch();
    }


    //提交编辑
    public function edit_post()
    {
        $ShopInit  = new \init\ShopInit();//店铺管理   (ps:InitController)
        $ShopModel = new \initmodel\ShopModel(); //店铺管理   (ps:InitModel)
        $params    = $this->request->param();


        /** 检测参数信息 **/
        $validateResult = $this->validate($params, 'Shop');
        if ($validateResult !== true) $this->error($validateResult);


        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];


        /** 提交数据 **/
        $result = $ShopInit->admin_edit_post($params, $where);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功", "index{$this->params_url}");
    }


    //提交(副本,无任何操作) 编辑&添加
    public function edit_post_two()
    {
        $ShopInit  = new \init\ShopInit();//店铺管理   (ps:InitController)
        $ShopModel = new \initmodel\ShopModel(); //店铺管理   (ps:InitModel)
        $params    = $this->request->param();

        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];

        /** 提交数据 **/
        $result = $ShopInit->edit_post_two($params, $where);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功", "index{$this->params_url}");
    }


    //驳回
    public function refuse()
    {
        $ShopInit  = new \init\ShopInit();//店铺管理  (ps:InitController)
        $ShopModel = new \initmodel\ShopModel(); //店铺管理   (ps:InitModel)
        $params    = $this->request->param();

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ShopInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        /** 数据格式转数组 **/
        $toArray = $result->toArray();
        foreach ($toArray as $k => $v) {
            $this->assign($k, $v);
        }

        return $this->fetch();
    }


    //驳回,更改状态
    public function audit_post()
    {
        $ShopInit  = new \init\ShopInit();//店铺管理   (ps:InitController)
        $ShopModel = new \initmodel\ShopModel(); //店铺管理   (ps:InitModel)
        $params    = $this->request->param();

        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];


        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $item                    = $ShopInit->get_find($where);
        if (empty($item)) $this->error("暂无数据");

        /** 通过&拒绝时间 **/
        if ($params['status'] == 2) $params['pass_time'] = time();
        if ($params['status'] == 3) $params['refuse_time'] = time();

        /** 提交数据 **/
        $result = $ShopInit->edit_post_two($params, $where);
        if (empty($result)) $this->error("失败请重试");

        $this->success("操作成功");
    }

    //删除
    public function delete()
    {
        $ShopInit  = new \init\ShopInit();//店铺管理   (ps:InitController)
        $ShopModel = new \initmodel\ShopModel(); //店铺管理   (ps:InitModel)
        $params    = $this->request->param();

        if ($params["id"]) $id = $params["id"];
        if (empty($params["id"])) $id = $this->request->param("ids/a");

        /** 删除数据 **/
        $result = $ShopInit->delete_post($id);
        if (empty($result)) $this->error("失败请重试");

        $this->success("删除成功");//   , "index{$this->params_url}"
    }


    //批量操作
    public function batch_post()
    {
        $ShopInit  = new \init\ShopInit();//店铺管理   (ps:InitController)
        $ShopModel = new \initmodel\ShopModel(); //店铺管理   (ps:InitModel)
        $params    = $this->request->param();

        $id = $this->request->param("id/a");
        if (empty($id)) $id = $this->request->param("ids/a");

        //提交编辑
        $result = $ShopInit->batch_post($id, $params);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功");//   , "index{$this->params_url}"
    }


    //更新排序
    public function list_order_post()
    {
        $ShopInit  = new \init\ShopInit();//店铺管理   (ps:InitController)
        $ShopModel = new \initmodel\ShopModel(); //店铺管理   (ps:InitModel)
        $params    = $this->request->param("list_order/a");

        //提交更新
        $result = $ShopInit->list_order_post($params);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功"); //   , "index{$this->params_url}"
    }


}
