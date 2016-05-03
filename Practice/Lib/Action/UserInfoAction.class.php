<?php

Class UserInfoAction extends CommonAction{
  public function index(){
  	if ($this->isPost()) {
        if(session('userinfo.level') == 'student'){
            $Mod = M('StuInfo');
        }else if(session('userinfo.level') == 'teacher'){
            $Mod = M('TeachInfo');
        }
  			$Mod->id=session('userinfo.id');
  			if(I('post.old','','md5')!=session('userinfo.password')){
  				$this->error('旧密码不正确！');
  			}
  			if(strlen(I('post.new'))<6){
  				$this->error('新密码必须在6位以上！');
  			}
  			if(I('post.new')!=I('post.renew')){
  				$this->error('两次密码输入不一样！');
  			}
  			$Mod->password=I('post.new','','md5');
  			if(!$Mod->save()){
  				$this->error('密码修改出错，请重试...');
  			}
  			session('userinfo.password',I('post.new','','md5'));
  			$this->success('密码修改成功！');
  	}else{
     	$this->display();
  	}
 }
}
 ?>