<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"CourseMarketing",
 *     "name_underline"          =>"course_marketing",
 *     "controller_name"         =>"CourseMarketing",
 *     "table_name"              =>"course_marketing",
 *     "remark"                  =>"营销管理"
 *     "api_url"                 =>"/api/wxapp/course_marketing/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-10-14 17:49:29",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\CourseMarketingController();
 *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/course_marketing/index",
 *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/course_marketing/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class CourseMarketingController extends AuthController
{

    //public function initialize(){
    //	//营销管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/course_marketing/index
     * https://xcxkf173.aubye.com/api/wxapp/course_marketing/index
     */
    public function index()
    {
        $CourseMarketingInit  = new \init\CourseMarketingInit();//营销管理   (ps:InitController)
        $CourseMarketingModel = new \initmodel\CourseMarketingModel(); //营销管理   (ps:InitModel)

        $result = [];

        $this->success('营销管理-接口请求成功', $result);
    }


    /**
     * 营销管理 添加
     * @OA\Post(
     *     tags={"营销管理"},
     *     path="/wxapp/course_marketing/add_marketing",
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
     *    @OA\Parameter(
     *         name="number",
     *         in="query",
     *         description="第几次营销",
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
     *   test_environment: http://height.ikun:9090/api/wxapp/course_marketing/add_marketing
     *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course_marketing/add_marketing
     *   api:  /wxapp/course_marketing/add_marketing
     *   remark_name: 营销管理 编辑&添加
     *
     */
    public function add_marketing()
    {
        $this->checkAuth();
        $CourseMarketingInit  = new \init\CourseMarketingInit();//营销管理    (ps:InitController)
        $CourseMarketingModel = new \initmodel\CourseMarketingModel(); //营销管理   (ps:InitModel)
        $CourseSetModel       = new \initmodel\CourseSetModel(); //收费配置   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 检测参数信息 **/
        $validateResult = $this->validate($params, 'CourseMarketing');
        if ($validateResult !== true) $this->error($validateResult);


        //查出关联课程
        $map        = [];
        $map[]      = ['', 'EXP', Db::raw("FIND_IN_SET({$params['course_id']},course_ids)")];
        $set_info   = $CourseSetModel->where($map)->find();
        $course_ids = $set_info['course_ids'];
        if ($course_ids) {
            //小时
            $hour = $set_info['end_time'];
            if ($params['number'] == 2) $hour = $set_info['end_time2'];


            foreach ($this->getParams($course_ids) as $course_id) {
                $CourseMarketingModel->strict(false)->insert([
                    'user_id'     => $this->user_id,
                    'course_id'   => $course_id,
                    'number'      => $params['number'],
                    'end_time'    => time() + ($hour * 60 * 60),
                    'create_time' => time(),
                ]);

            }
        }


        $this->success('添加成功');
    }


}
