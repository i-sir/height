<?php
namespace api\wxapp\controller;

/**
    * @ApiController(
    *     "name"                    =>"Course",
    *     "name_underline"          =>"course",
    *     "controller_name"         =>"Course",
    *     "table_name"              =>"course",
    *     "remark"                  =>"课程计划"
    *     "api_url"                 =>"/api/wxapp/course/index",
    *     "author"                  =>"",
    *     "create_time"             =>"2025-09-18 11:42:28",
    *     "version"                 =>"1.0",
    *     "use"                     => new \api\wxapp\controller\CourseController();
    *     "test_environment"        =>"http://height.ikun:9090/api/wxapp/course/index",
    *     "official_environment"    =>"https://xcxkf173.aubye.com/api/wxapp/course/index",
    * )
    */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;




error_reporting(0);



class CourseController extends AuthController{

	//public function initialize(){
	//	//课程计划
	//	parent::initialize();
	//}



	/**
	* 默认接口
	* /api/wxapp/course/index
https://xcxkf173.aubye.com/api/wxapp/course/index

	*/
	public function index(){
		$CourseInit = new \init\CourseInit();//课程计划   (ps:InitController)
		$CourseModel = new \initmodel\CourseModel(); //课程计划   (ps:InitModel)

		$result = [];

		$this->success('课程计划-接口请求成功',$result);
	}




	        /**
         * 课程计划 列表
         * @OA\Post(
         *     tags={"课程计划"},
         *     path="/wxapp/course/find_course_list",
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
         *   test_environment: http://height.ikun:9090/api/wxapp/course/find_course_list
         *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course/find_course_list
         *   api:  /wxapp/course/find_course_list
         *   remark_name: 课程计划 列表
         *
         */
	public function find_course_list()
	{	
		$CourseInit = new \init\CourseInit();//课程计划   (ps:InitController)
		$CourseModel = new \initmodel\CourseModel(); //课程计划   (ps:InitModel)

		/** 获取参数 **/
		$params = $this->request->param();
		$params["user_id"] = $this->user_id;

		/** 查询条件 **/
		$where=[];
		$where[]=['id','>',0];
$where[]=['is_show','=',1];
		if ($params["keyword"]) $where[] = ["name|introduce", "like", "%{$params['keyword']}%"];
		if($params["status"]) $where[]=["status","=", $params["status"]];
		


		/** 查询数据 **/
		$params["InterfaceType"] = "api";//接口类型
		$params["DataFormat"]    = "list";//数据格式,find详情,list列表
		$params["field"]         = "*";//过滤字段
		if ($params['is_paginate']) $result = $CourseInit->get_list($where, $params);
		if (empty($params['is_paginate'])) $result = $CourseInit->get_list_paginate($where, $params);
		if (empty($result)) $this->error("暂无信息!");

		$this->success("请求成功!", $result);
	}



            /**
         * 课程计划 详情
         * @OA\Post(
         *     tags={"课程计划"},
         *     path="/wxapp/course/find_course",
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
         *   test_environment: http://height.ikun:9090/api/wxapp/course/find_course
         *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course/find_course
         *   api:  /wxapp/course/find_course
         *   remark_name: 课程计划 详情
         *
         */
    public function find_course()
{
		$CourseInit = new \init\CourseInit();//课程计划    (ps:InitController)
		$CourseModel = new \initmodel\CourseModel(); //课程计划   (ps:InitModel)

		/** 获取参数 **/
	 	$params = $this->request->param();
		$params["user_id"] = $this->user_id;

		/** 查询条件 **/
		$where   = [];
		$where[] = ["id", "=", $params["id"]];

		/** 查询数据 **/
		$params["InterfaceType"] = "api";//接口类型
		$params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result = $CourseInit->get_find($where,$params);
		if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据",$result);
    }




	        /**
         * 课程计划 编辑&添加
         * @OA\Post(
         *     tags={"课程计划"},
         *     path="/wxapp/course/edit_course",
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
         *         name="name",
         *         in="query",
         *         description="名称",
         *         required=false,
         *         @OA\Schema(
         *             type="string",
         *         )
         *     ), 
         *
         *
         *
         *    @OA\Parameter(
         *         name="introduce",
         *         in="query",
         *         description="介绍",
         *         required=false,
         *         @OA\Schema(
         *             type="string",
         *         )
         *     ), 
         *
         *
         *
         *    @OA\Parameter(
         *         name="total_day",
         *         in="query",
         *         description="总天数",
         *         required=false,
         *         @OA\Schema(
         *             type="string",
         *         )
         *     ), 
         *
         *
         *
         *    @OA\Parameter(
         *         name="attend_number",
         *         in="query",
         *         description="参加人数",
         *         required=false,
         *         @OA\Schema(
         *             type="string",
         *         )
         *     ), 
         *
         *
         *
         *    @OA\Parameter(
         *         name="virtual_number",
         *         in="query",
         *         description="虚拟人数",
         *         required=false,
         *         @OA\Schema(
         *             type="string",
         *         )
         *     ), 
         *
         *
         *
         *    @OA\Parameter(
         *         name="is_show",
         *         in="query",
         *         description="显示:1是,2否",
         *         required=false,
         *         @OA\Schema(
         *             type="string",
         *         )
         *     ), 
         *
         *
         *
         *    @OA\Parameter(
         *         name="list_order",
         *         in="query",
         *         description="排序",
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
         *         name="id",
         *         in="query",
         *         description="id空添加,存在编辑",
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
         *   test_environment: http://height.ikun:9090/api/wxapp/course/edit_course
         *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course/edit_course
         *   api:  /wxapp/course/edit_course
         *   remark_name: 课程计划 编辑&添加
         *
         */
	public function edit_course(){
		$CourseInit = new \init\CourseInit();//课程计划    (ps:InitController)
		$CourseModel = new \initmodel\CourseModel(); //课程计划   (ps:InitModel)

		/** 获取参数 **/
		$params = $this->request->param();
		$params["user_id"]=$this->user_id;

		/** 检测参数信息 **/
		$validateResult = $this->validate($params, 'Course');
		if ($validateResult !== true) $this->error($validateResult);

		/** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
		$where   = [];
		if($params['id'])$where[] = ['id', '=', $params['id']];


		/** 提交更新 **/
		$result = $CourseInit->api_edit_post($params,$where);
		if (empty($result)) $this->error("失败请重试");


		if (empty($params["id"])) $msg = "添加成功";
		if (!empty($params["id"])) $msg = "编辑成功";
		$this->success($msg);
	}


	        /**
         * 课程计划 删除
         * @OA\Post(
         *     tags={"课程计划"},
         *     path="/wxapp/course/delete_course",
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
         *   test_environment: http://height.ikun:9090/api/wxapp/course/delete_course
         *   official_environment: https://xcxkf173.aubye.com/api/wxapp/course/delete_course
         *   api:  /wxapp/course/delete_course
         *   remark_name: 课程计划 删除
         *
         */
	public function delete_course()
	{
		$CourseInit = new \init\CourseInit();//课程计划    (ps:InitController)
		$CourseModel = new \initmodel\CourseModel(); //课程计划   (ps:InitModel)

		/** 获取参数 **/
		$params      = $this->request->param();

		/** 删除数据 **/
		$result = $CourseInit->delete_post($params["id"]);
		if (empty($result)) $this->error("失败请重试");

		$this->success("删除成功");
	}





}
