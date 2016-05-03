<?php

Class NoticeAction extends Action{
	public function _initialize() {
		if (session ( 'userinfo.level' ) != 'teacher') {
			$this->redirect ( 'Indx/index' );
		}
	}

	public function addNotice() {
		if ($this->isPost ()) {
			$Notice = M ( 'Notice' );
			$Notice->create ();
			$Notice->receive=implode(',',I('receive'));
			$Notice->time = date ( 'Y-m-d H:i:s', time () );
			if ($Notice->add ()) {
				$this->success ( '公告已成功发布！', U ( 'Index/index' ) );
			} else {
				$this->error ( '公告发布出错，请重试！' );
			}
		} else {
			$PraType = M ( 'PraType' );
			$pratype = $PraType->select ();
			$this->assign ( 'pratype', $pratype );
			$this->display ();
		}
	}

	public function modNotice($id="") {
		if ($this->isPost ()) {
			$Notice = M ( 'Notice' );
			$Notice->create ();
			$Notice->receive=implode(',',I('receive'));
			$Notice->time = date ( 'Y-m-d H:i:s', time () );
			if ($Notice->save ()) {
				$this->success ( '公告已修改完成！', U ( 'Index/index' ) );
			} else {
				$this->error ( '公告修改出错，请重试！' );
			}
		} else {
			$Notice = M ( 'notice' );
			$ntc = $Notice->find ( $id );
			$PraType = M ( 'PraType' );
			$pratype = $PraType->select ();
			$reces = explode(',', $ntc['receive']);
			foreach ($pratype as $key => $value) {
				if(in_array($value['id'], $reces)){
					$pratype[$key]['selected'] = 1;
				}else{
					$pratype[$key]['selected'] = 0;
				}
			}
			$this->assign ( 'pratype', $pratype );
			$this->assign ( 'notice', $ntc );
			$this->display ();
		}
	}

	public function delNotice() {
		$id = I('post.id');
		$data = array();
		 if(M('Notice')->delete($id)){
		 	$data['status'] = 1;
		 	$data['info'] = "删除成功";
		 }else{
		 	$data['status'] = 0;
		 	$data['info'] = "操作失败，请重试";
		 }
		 $this->ajaxReturn ( $data, 'JSON' );
	}
}
 ?>