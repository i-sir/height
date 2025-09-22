<?php
// +----------------------------------------------------------------------
// | 会员中心
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
namespace api\wxapp\controller;

use think\facade\Db;

header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:*');
// 响应头设置
header('Access-Control-Allow-Headers:*');


error_reporting(0);


class MemberController extends AuthController
{
    //    public function initialize()
    //    {
    //        parent::initialize();//初始化方法
    //    }

    /**
     * 测试用
     *
     *   test_environment: http://height.ikun:9090/api/wxapp/member/index
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/member/index
     *   api: /wxapp/member/index
     *   remark_name: 测试用
     *
     */
    public function index()
    {
        $MemberInit  = new \init\MemberInit();//用户管理
        $MemberModel = new \initmodel\MemberModel();//用户管理


        $map    = [];
        $map[]  = ['openid', '=', $openid ?? 1];
        $result = $MemberInit->get_my_info($map);

        $this->success('请求成功', $result);
    }


    /**
     * 查询会员信息
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @OA\Post(
     *     tags={"会员中心模块"},
     *     path="/wxapp/member/find_member",
     *
     *
     *
     *     @OA\Parameter(
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
     *   test_environment: http://height.ikun:9090/api/wxapp/member/find_member
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/member/find_member
     *   api: /wxapp/member/find_member
     *   remark_name: 查询会员信息
     *
     */
    public function find_member()
    {
        $this->checkAuth();

        $MemberModel = new \initmodel\MemberModel();//用户管理
        $MemberInit  = new \init\MemberInit();//用户管理


        $map    = [];
        $map[]  = ['openid', '=', $this->openid];
        $result = $MemberInit->get_my_info($map);

        $this->success("请求成功!", $result);
    }


    /**
     * 更新会员信息
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @OA\Post(
     *     tags={"会员中心模块"},
     *     path="/wxapp/member/update_member",
     *
     *
     *     @OA\Parameter(
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
     *     @OA\Parameter(
     *         name="nickname",
     *         in="query",
     *         description="昵称",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="手机号",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="avatar",
     *         in="query",
     *         description="头像",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *      @OA\Parameter(
     *         name="used_pass",
     *         in="query",
     *         description="旧密码,如需要传,不需要请勿传",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="pass",
     *         in="query",
     *         description="更改密码,如需要传,不需要请勿传",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/member/update_member
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/member/update_member
     *   api: /wxapp/member/update_member
     *   remark_name: 更新会员信息
     *
     */
    public function update_member()
    {
        $this->checkAuth();

        $MemberModel = new \initmodel\MemberModel();//用户管理


        $params                = $this->request->param();
        $params['update_time'] = time();
        $member                = $this->user_info;


        //        $result = $this->validate($params, 'Member');
        //        if ($result !== true) $this->error($result);


        if (empty($member)) $this->error("该会员不存在!");
        if ($member['pid']) unset($params['pid']);


        //修改密码
        if ($params['pass']) {
            if (!cmf_compare_password($params['used_pass'], $member['pass'])) $this->error('旧密码错误');
            $params['pass'] = cmf_password($params['pass']);
        }

        $result = $MemberModel->where('id', $member['id'])->strict(false)->update($params);
        if ($result) {
            $result = $this->getUserInfoByOpenid($this->openid);
            $this->success("保存成功!", $result);
        } else {
            $this->error("保存失败!");
        }
    }


    /**
     * 账户资产变动明细
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @OA\Post(
     *     tags={"会员中心模块"},
     *     path="/wxapp/member/find_asset_list",
     *
     *
     *     @OA\Parameter(
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
     *     @OA\Parameter(
     *         name="operate_type",
     *         in="query",
     *         description="操作字段类型:balance余额,point积分",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="change_type",
     *         in="query",
     *         description="类型:1=收入,2=支出 (选填)",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *     @OA\Parameter(
     *         name="begin_time",
     *         in="query",
     *         description="2025-01-15",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *     @OA\Parameter(
     *         name="end_time",
     *         in="query",
     *         description="2025-01-15",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/member/find_asset_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/member/find_asset_list
     *   api: /wxapp/member/find_asset_list
     *   remark_name: 账户资产变动明细
     *
     */
    public function find_asset_list()
    {
        $this->checkAuth();
        $AssetModel = new \initmodel\AssetModel();

        $params = $this->request->param();


        //数据类型
        $operate_type_list = $AssetModel->operate_type;
        if (empty($params['operate_type'])) $params['operate_type'] = array_keys($operate_type_list)[0];


        $where   = [];
        $where[] = ['user_id', '=', $this->user_id];
        $where[] = ['identity_type', '=', $this->user_info['identity_type'] ?? 'member'];
        $where[] = ['operate_type', '=', $params['operate_type'] ?? 'balance'];
        $where[] = $this->getBetweenTime($params['begin_time'], $params['end_time']);
        if ($params['change_type']) $where[] = ['change_type', '=', $params['change_type'] ?? 1];

        $result = $AssetModel->where($where)
            ->field("id,user_id,order_num,operate_type,identity_type,order_type,price,content,change_type,create_time")
            ->order("id desc")
            ->paginate($params['page_size'])
            ->each(function ($item, $key) use ($operate_type_list) {

                if ($item['change_type'] == 2) {
                    $item['price'] = -$item['price'];
                } else {
                    $item['price'] = '+' . $item['price'];
                }


                return $item;
            });

        $this->success("请求成功！", $result);
    }


    /**
     * 团队列表查询
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @OA\Post(
     *     tags={"会员中心模块"},
     *     path="/wxapp/member/find_team_list",
     *
     *
     *
     *    @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="1 直接团队成员列表 2间接团队成员列表",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *     @OA\Parameter(
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
     *   test_environment: http://height.ikun:9090/api/wxapp/member/find_team_list
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/member/find_team_list
     *   api: /wxapp/member/find_team_list
     *   remark_name: 团队列表查询
     *
     */
    public function find_team_list()
    {
        $this->checkAuth();
        $MemberModel = new \initmodel\MemberModel();//用户管理


        $params  = $this->request->param();
        $user_id = $this->user_id;
        if ($params['user_id']) {
            $user_id = $params['user_id'];
        }

        if ($params['type'] == 2) {
            $result = $MemberModel
                ->where("spid", $user_id)
                ->field('*')
                ->order("id desc")
                ->paginate(10)
                ->each(function ($item, $key) use ($MemberModel) {
                    $item['avatar']           = cmf_get_asset_url($item['avatar']);
                    $item['child_total_fans'] = $MemberModel->where('pid', $item['id'])->count(); //直接下级数
                    //$item['second_total_fans'] = $MemberModel->where('spid', $item['id'])->count(); //间接下级数

                    return $item;
                });
        } else {
            $result = $MemberModel
                ->where("pid", $user_id)
                ->field('*')
                ->order("id desc")
                ->paginate(10)
                ->each(function ($item, $key) use ($MemberModel) {
                    $item['avatar']           = cmf_get_asset_url($item['avatar']);
                    $item['child_total_fans'] = $MemberModel->where('pid', $item['id'])->count(); //直接下级数
                    //$item['second_total_fans'] = $MemberModel->where('spid', $item['id'])->count(); //间接下级数

                    return $item;
                });
        }
        $this->success("请求成功！", $result);
    }


    /**
     * 统计佣金资金信息
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @OA\Post(
     *     tags={"会员中心模块"},
     *     path="/wxapp/member/statistics",
     *
     *
     *
     *     @OA\Parameter(
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
     *   test_environment: http://height.ikun:9090/api/wxapp/member/statistics
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/member/statistics
     *   api: /wxapp/member/statistics
     *   remark_name: 统计佣金资金信息
     *
     */
    public function statistics()
    {
        $this->checkAuth();

        $AssetModel = new \initmodel\AssetModel();


        $map   = [];
        $map[] = ['user_id', '=', $this->user_id];
        $map[] = ['operate_type', '=', 'balance'];
        $map[] = ['identity_type', '=', 'member'];
        $map[] = ['change_type', '=', 1];
        $map[] = ['order_type', 'in', [120, 130]];


        //累计佣金
        $result['total_commission'] = $AssetModel->where($map)->sum('price');
        //可提现佣金
        $result['balance'] = $this->user_info['balance'];
        //今日收益
        $result['today_commission'] = $AssetModel->where($map)->whereTime('create_time', 'today')->sum('price');
        //团队人数
        $result['team_number'] = count($this->getAllChildIds($this->user_id));


        $this->success("请求成功！", $result);
    }


    /**
     * 获取所有子级ID（递归方法）
     * @param int    $pid      父级ID
     * @param array &$childIds 用于存储结果的数组
     * @return array
     */
    public function getAllChildIds($pid, &$childIds = [])
    {
        $MemberModel = new \initmodel\MemberModel();


        // 查询直接子级
        $map      = [];
        $map[]    = ['pid', '=', $pid];
        $children = $MemberModel->where($map)->column('id');

        if (!empty($children)) {
            foreach ($children as $childId) {
                $childIds[] = $childId;
                // 递归查询子级的子级
                $this->getAllChildIds($childId, $childIds);
            }
        }

        return $childIds;
    }

}