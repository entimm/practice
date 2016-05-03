<?php
class IndexAction extends CommonAction {
	public function index() {
		$Notice = M ( 'Notice' );
		$notices = $Notice->field ( 'content', true )
						  ->order ( 'time' )
						  ->select ();
		if(session('userinfo.level')=='student'){
			$pra_type = session('userinfo.pra_type');
			foreach ($notices as $key => $value) {
				$arr = explode(',', $value['receive']);
				if(!in_array($pra_type, $arr)){
					unset($notices[$key]);
				}
			}
		}
		$this->assign ( 'notices', $notices );
		$this->display ();
	}

	public function notice() {
		$Notice = M ( 'Notice' );
		$id = I ( 'get.id' );
		$ntc = $Notice->find ( $id );
		$this->assign ( 'notice', $ntc );
		$this->display ();
	}
}