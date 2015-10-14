<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function __construct(){
        parent::__construct();
        /*$this->wkey = session_id();
        $uid = S($this->wkey);
        if(empty($uid)){
            $this->login();
            exit;
        }   */
    }
    /**
     * 登录
     * @return [type] [description]
     */
    public function login(){
        if(I('request.username')){
            $username = I('request.username');
            $passwd = I('request.passwd');
            if($username =='shl3807' && $passwd =='shl1212'){
                S($this->wkey,$username,86400);
                header("location:/?s=home/index/index");
                exit;
            }else{
                $this->error('用户名密码错误',$_SERVER['HTTP_REFERER']);
            }
        }
        $this->display();
    }
    /**
     * [index description]
     * @return [type] [列表页面]
     */
    public function index(){
    	$path = I('request.path');
        if(empty($path)){
            $path = C('SVN_DATA_PATH');
        }else{
            $path = str_replace('|','/',$path);
            /*echo '/usr/bin/svn up '.$path;
            system('/usr/bin/svn up '.$path ,$out);*/
        }
        $arr = scandir($path);
    	foreach ($arr as $key => $value) {
    		if( in_array($value, C('EXCLUDE_PATH_NAME')) ){
    			unset($arr[$key]);
    		}else{
                $result[$key]['name'] = $value;
                $real_path = $path.'/'.$value;
                if(is_dir($real_path)){
                    $result[$key]['is_mulu'] = 1;
                }else{
                    $result[$key]['is_mulu'] = 0;
                }
                $result[$key]['path'] = str_replace('/','|',$real_path);
                $result[$key]['last_up_time'] = date('Y-m-d H:i:s',filemtime($real_path));
            }
    	}

    	$this->assign('result',array_values($result));
    	$this->display();
    }

    /**
     * 部署页面
     * @return [type] [description]
     */
    public function submit(){
        $paths = I('request.path');
        $path_arr = [];
        if(empty($paths)){
            $this->error('提交数据为空',$_SERVER['HTTP_REFERER']);
        }
        foreach($paths as $k=>$v){
            $file_name = str_replace('|','/',$v);
            $path_has = explode('/',$file_name);
            if(in_array($path_has[3] ,C('EXCLUDE_PATH_NAME'))){
                $this->error('错误数据',$_SERVER['HTTP_REFERER']);
            }
            //推送的配置文件
            $rsync_sets = C('RSYNC_FILE_SET');
            if(!in_array($path_has[4] ,array_keys($rsync_sets))){
                $this->error('错误数据,没有rsync配置',$_SERVER['HTTP_REFERER']);
            }

            //当前目录的raync配置
            $one_rsync = $rsync_sets[$path_has[4]][$path_has[5]];
            if(empty($one_rsync)){
                $this->error('错误数据,没有rsync配置',$_SERVER['HTTP_REFERER']);
            }

            $rsync_to_psth = '';
            $left_path = array_slice($path_has,5);
            if(!empty($left_path)){
                //删除最后一个
                array_pop($left_path);
                $rsync_to_psth = '/'.implode('/', $left_path);
            }

            $cmd = "rsync -vzrtopgl --progress --exclude=\".svn\" {$file_name} {$one_rsync['ip']}::{$one_rsync['module']}{$rsync_to_psth}";
            
            //执行rsync命令
            system($cmd);
            
        }
        
        $this->success('部署成功',$_SERVER['HTTP_REFERER']);
    }

    /**
     * 查看源码
     * @return [type] [description]
     */
    public function lookcode(){
        $path = I('request.path');
        $path = str_replace('|','/',$path);
        if(!is_file($path)){
            $this->error('不是文件',$_SERVER['HTTP_REFERER']);
        }
        $this->file = $path;
        $this->code = file_get_contents($path);
        $this->display();
    }
}