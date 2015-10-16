<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends FontController {
    /**
     * [index description]
     * @return [type] [列表页面]
     */
    public function index(){
        $this->checklogin();
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
                $result[$key]['path'] = ensepchtml($real_path); //因为tpurl结尾的html默认不处理
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
        $this->checklogin();
        $paths = I('request.path');
        $path_arr = [];
        if(empty($paths)){
            $this->error('提交数据为空',$_SERVER['HTTP_REFERER']);
        }
        foreach($paths as $k=>$v){
            $file_name = desepchtml($v);
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
            $one_rsync = $rsync_sets[$path_has[4]];
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
            if(empty($rsync_to_psth)){
                $file_name = $file_name.'/';
            }

            $cmd = "rsync -vzrtopgl --progress {$one_rsync['exclude']} {$file_name} {$one_rsync['ip']}::{$one_rsync['module']}{$rsync_to_psth}";
            echo $cmd;
            //exit;
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
        $this->checklogin();
        $path = I('request.path');
        $path = desepchtml($path);
        if(!is_file($path)){
            $this->error('不是文件',$_SERVER['HTTP_REFERER']);
        }
        $this->file = $path;
        $this->code = file_get_contents($path);
        $this->display();
    }
}