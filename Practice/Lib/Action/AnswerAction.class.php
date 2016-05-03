<?php
class AnswerAction extends CommonAction {
	public function index() {
		$Ques = M ( 'Ques' );
		$ques = $Ques->join ( 'INNER JOIN sx_stu_info ON sx_ques.stu_id=sx_stu_info.id' )->order ( 'anstime desc' )->getField ( 'sx_ques.id,name,question,time,isans' );

		$Answer = M ( 'Answer' );
		$ans = $Answer->getField ( 'id,author,answer,time,to' );
		foreach ( $ans as $value ) {
			$ques [$value ['to']] ['ans'] [] = $value;
		}
		// var_dump($ques);
		$this->assign ( 'ques', $ques );
		$this->display ();
	}
	public function ajax() {
		if ($this->isPost ()) {
			$Ques = M ( 'Ques' );
			$Ques->create ();
			$time = date ( 'Y-m-d H:i:s', time () );
			$Ques->time = $time;
			$Ques->stu_id = session ( 'userinfo.id' );
			if ($Ques->add ()) {
				$data ['status'] = 1;
				$data ['info'] = '提问成功！';
				// $data ['name'] = session ( 'userinfo.name' );
				// $data ['time'] = date ( 'Y年m月d日 H时i分', strtotime ( $time ) );
				// $data ['question'] = I ( 'post.question' );
			} else {
				$data ['status'] = 0;
				$data ['info'] = '提问失败！';
			}
			$this->ajaxReturn ( $data, 'JSON' );
		}
	}
	public function anwer() {
		if ($this->isPost ()) {
			$Answer = M ( 'Answer' );
			$Answer->create ();
			$time = date ( 'Y-m-d H:i:s', time () );
			$Answer->time = $time;
			if(session('userinfo.level')=='student'){
				$Answer->author = session ( 'userinfo.name' );
			}else{
				$Answer->author = session ( 'userinfo.name' ).'老师';
			}

			if ($Answer->add ()) {
				$Ques = M ( 'Ques' );
				$Ques->where(array('id'=>I('post.to')))->setField('isans',1);
				$data ['status'] = 1;
				$data ['info'] = '回答成功！';
				// $data ['name'] = session ( 'userinfo.name' );
				// $data ['time'] = date ( 'Y年m月d日 H时i分', strtotime ( $time ) );
				// $data ['question'] = I ( 'post.question' );
			} else {
				$data ['status'] = 0;
				$data ['info'] = '回答失败！';
			}
			$this->ajaxReturn ( $data, 'JSON' );
		}
	}
}
?>