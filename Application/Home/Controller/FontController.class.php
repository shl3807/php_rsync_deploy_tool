<?php
namespace Home\Controller;
use Think\Controller;
class FontController extends Controller {
	public function __construct(){
        parent::__construct();
        $this->wkey = session_id();
        $this->username = S($this->wkey);

	}

	/**
	 * 检测是否登陆
	 * @return [type] [description]
	 */
	public function checklogin(){
		if(empty($this->username)){
			$this->display('Index/login');
			exit();
		}
	}
	/**
     * 登录
     * @return [type] [description]
     */
    public function login(){
        if(I('request.username')){
            $username = I('request.username');
            $passwd = I('request.passwd');
            if($username =='****' && $passwd =='*****'){
                S($this->wkey,$username,86400);
                header("location:/?s=home/index/index");
                exit;
            }else{
                $this->error('用户名密码错误',$_SERVER['HTTP_REFERER']);
            }
        }
        $this->display('Index/login');
    }
}

