<?php
class ManualTeachAction extends Action {
	public function _initialize() {
		if (session ( 'userinfo.level' ) != 'teacher') {
			$this->redirect ( 'Indx/index' );
		}
	}

	public function index(){
		$arctypes = M('ArctpTeach')->select();
		$arcs = array();
		$ArcTeach = M('ArcTeach');
		$data['teach_id'] = session('userinfo.id');
		foreach ($arctypes as $key =>$value) {
			$data['arctype'] = $value['id'];
			$arcs[$key]['arcs'] = $ArcTeach->where($data)->order('add_time desc')->select();
			$arcs[$key]['type'] = $value['type'];
			$arcs[$key]['tpid'] = $value['id'];
		}
		$this->assign('arcs',$arcs);
		$this->display();
	}


	public function detail ($arcid = '') {
		$data['sx_arc_teach.id'] = $arcid;
		$data['teach_id'] = session('userinfo.id');
		$arc = M('ArcTeach')->join('INNER JOIN sx_arctp_teach ON sx_arctp_teach.id = sx_arc_teach.arctype')
							  ->field('type,sx_arc_teach.id,cont,add_time,mod_time,browse_times')
							  ->where($data)
							  ->find();
		$this->assign('arc',$arc);
		$this->display();
	}

	public function arcadd($typeid = ''){
		$title = M('ArctpTeach')->where(array('id'=>$typeid))->getField('type');
		if($this->isPost()){
			$data ['teach_id'] = session ( 'userinfo.id' );
			$data ['add_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['cont'] = I ( 'post.content' );
			$data ['arctype'] = $typeid;
			$ArcTeach = M ( 'ArcTeach' );
			$ArcTeach->create ( $data );
			if ($id = $ArcTeach->add ()) {
				$this->redirect ( 'ManualTeach/detail', array (
						'arcid' => $id
				) );
			} else {
				$this->error ( 'ops，提交不成功，在试下！' );
			}
		}else{
			$this->assign('title',$title);
			$this->display();
		}
	}

	public function arcmod($arcid = ''){
		$data['sx_arc_teach.id'] = $arcid;
		$data['teach_id'] = session('userinfo.id');
		if($this->isPost()){
			$data['id']= $arcid;
			$data ['add_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['cont'] = I ( 'post.content' );
			$ArcTeach = M ( 'ArcTeach' );
			$ArcTeach->create ( $data );
			if ($ArcTeach->save ()) {
				$this->redirect ( 'ManualTeach/detail', array (
						'arcid' => $arcid
				) );
			} else {
				$this->error ( 'ops，提交不成功，在试下！' );
			}
		}else{
			$arc = M('ArcTeach')->join('INNER JOIN sx_arctp_teach ON sx_arctp_teach.id = sx_arc_teach.arctype')
								  ->field('type,sx_arc_teach.id,cont,add_time,mod_time,browse_times')
								  ->where($data)
								  ->find();
			$this->assign('arc',$arc);
			$this->display();
		}
	}

	public function arcdel($arcid = ''){
		$data['id'] = $arcid;
		$data['teach_id'] = session('userinfo.id');
		$bool = M('ArcTeach')->where($data)->delete();
		if($bool){
			$this->redirect('ManualTeach/index');
		}else{
			$this->error('删除失败,请重新试试！');
		}
	}
}
?>