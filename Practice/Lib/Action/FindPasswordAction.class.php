<?php
class FindPasswordAction extends Action {
	public function index() {
		$this->display();
	}

	public function findpwd(){
		if($this->isPost()){
			$id = I('post.id');
			$userinfo = M('StuInfo')->where(array('id' => $id))->find();
			if($userinfo){
				vendor('PHPMailer.class#PHPMailer');
				$phpmailer = new PHPMailer();
				$phpmailer->IsSMTP();
				$phpmailer->Host = C('MAIL_HOST');
				$phpmailer->Port = C('MAIL_PORT');
				$phpmailer->SMTPAuth   = true;

				$phpmailer->CharSet  = "UTF-8";
				$phpmailer->Encoding = "base64";

				$phpmailer->Username = C('MAIL_USERNAME');
				$phpmailer->Password = C('MAIL_PASSWORD');

				$phpmailer->Subject = "海南师范大学信息学院实习管理系统密码找回"; //邮件标题
				$phpmailer->From = "1733365102@qq.com";
				$phpmailer->FromName = "海师实习系统";

				$phpmailer->AddAddress($userinfo['email'], $userinfo['name']);

				$phpmailer->IsHTML(true);
				$phpmailer->AddEmbeddedImage("Public/Images/logo.png", "my-attach");
				$phpmailer->Body = '你好, <b>'.$userinfo['name'].'</b> 同学! <br/>';
				$phpmailer->Body .= '你于 <span style="color:red">'.date('Y年m月d日H时i分').'</span> 在 <a href="http://'.$_SERVER['HTTP_HOST'].'">海师实习管理系统</a> 进行了 <span style="color:red">找回密码</span> 操作！<br />';
				$phpmailer->Body .= '请<span style="color:red">及时(30分钟内)</span>点击下面的连接来修改你的密码。<br />';
				$phpmailer->Body .= '——><a href="http://'.$_SERVER['HTTP_HOST'].'/FindPassword/modPwd/id/'.$userinfo['id'].'/token/'.$userinfo['password'].'">点击我重置实习管理系统的密码</a><br />';
				$phpmailer->Body .= '若你不想重置你的实习网站密码，请无视这封电子邮件！';

				if(!$phpmailer->Send()) {
				  echo "发送失败: " . $mail->ErrorInfo;
				}
				M('StuInfo')->where(array('id' => $id))->setField('resetpwd',date ( 'Y-m-d H:i:s', time ()+1800 ));
				$data['status'] = 1;
				$data['info'] = '<span class="okinfo">我们已经向你的邮箱'.$userinfo['email']."发送了重置密码的链接<br />请及时(30分钟内)查看并点击此连接即可重置你的密码哦`(*∩_∩*)′</span>";
			}else{
				$data['status'] = 0;
				$data['info'] = '<span class="error">你输入的学号错了吧？！</span>';
			}
			$this->ajaxReturn ( $data, 'JSON' );
		}
	}

	public function modPwd($id='',$token=''){
		$userinfo = M('StuInfo')->where(array('id' => $id,'password'=>$token))->find();
		$endtime = strtotime($userinfo['resetpwd']);
		if($endtime-time()<0){
			$this->error('对不起，这个请求过期了或者这是非法请求哦！','/Login/Index');
		}
		if($userinfo){
			if($this->isPost()){
				if(strlen(I('post.new'))<9){
					$this->error("密码必须大于等于9位");
				}
				if(I('post.new')!=I('post.renew')){
					$this->error("你2次密码输入不一致，请仔细并牢记哦！");
				}
				M('StuInfo')->where(array('id' => $id))->setField(array('password'=>md5(I('post.new')),'resetpwd'=>date ( 'Y-m-d H:i:s', time ())));
				$this->success('恭喜，重置成功，请牢记哦！','/Login/Index');
			}else{
				$this->display();
			}
		}else{
			$this->error('对不起，这是非法请求！','/Login/Index');
		}
	}
}
?>