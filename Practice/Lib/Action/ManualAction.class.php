<?php
class ManualAction extends Action {
	public function _initialize() {
		if (session ( 'userinfo.level' ) != 'student') {
			$this->redirect ( 'Indx/index' );
		}
	}
	public function index() {
		//一次性的
		$Type = M ( 'NoteType' );
		$ty = $Type->select ();
		$others = M('Other')->where(array('stu_id' => session ( 'userinfo.id' )))->field('type_id')->select();
		$others_va = array();
		foreach ($others as $value) {
			$others_va[] = $value['type_id'];
		}
		foreach ($ty as &$value) {
			if(in_array($value['id'], $others_va)){
				$value['isok'] = 1;
			}else{
				$value['isok'] = 0;
			}
		}
		$this->assign ( 'type', $ty );
		//岗前培训
		$Train = M ( 'Train' );
		$tr = $Train->field ( 'cont', true )->where ( array (
				'stu_id' => session ( 'userinfo.id' )
		) )->order('add_time DESC')->select ();
		$this->assign ( 'train', $tr );

		//教学工作周记
		$WorkWeek = M ( 'WorkWeek' );
		$ww = $WorkWeek->field ( 'cont', true )->where ( array (
				'stu_id' => session ( 'userinfo.id' )
		) )->select ();
		$this->assign ( 'workweek', $ww );

		//班主任工作周记
		$TeachWeek = M ( 'TeachWeek' );
		$tw = $TeachWeek->field ( 'cont', true )->where ( array (
				'stu_id' => session ( 'userinfo.id' )
		) )->select ();
		$this->assign ( 'teachweek', $tw );

		//教学实习教案
		$TeachPlan = M ( 'TeachPlan' );
		$tp = $TeachPlan->field ( 'cont', true )->where ( array (
				'stu_id' => session ( 'userinfo.id' )
		) )->select ();
		$this->assign ( 'teachplan', $tp );

		//听课和评课记录
		$VisitRecord = M ( 'VisitRecord' );
		$vr = $VisitRecord->field ( 'cont', true )->where ( array (
				'stu_id' => session ( 'userinfo.id' )
		) )->select ();
		$this->assign ( 'visitrecord', $vr );

		$this->display ();
	}

	public function other($typeid = 3) {
		$condi ['type_id'] = $typeid;
		$condi ['stu_id'] = session ( 'userinfo.id' );
		$OtherType = M ( 'Other' );
		$ot = $OtherType->where ( $condi )->find ();
		$this->assign ( 'othertype', $ot );

		$nty = $Type = M ( 'NoteType' )->find ( $typeid );
		$this->assign ( 'ntype', $nty );

		$this->display ();
	}

	public function otherAdd($typeid = 1) {
		$nty = $Type = M ( 'NoteType' )->find ( $typeid );
		if ($this->isPost ()) {
			$data ['stu_id'] = session ( 'userinfo.id' );
			$data ['add_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['mod_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['type_id'] = $typeid;
			$data ['cont'] = I ( 'post.content' );
			$Other = M ( 'Other' );
			$Other->create ( $data );
			if ($Other->add ()) {
				$this->redirect ( 'Manual/other', array (
						'typeid' => $typeid
				) );
			} else {
				$this->error ( 'ops，提交不成功，在试下！' );
			}
		} else {
			$nty = $Type = M ( 'NoteType' )->find ( $typeid );
			$this->assign ( 'ntype', $nty );
			$this->display ();
		}
	}

	public function otherMod($typeid = 1) {
		$nty = $Type = M ( 'NoteType' )->find ( $typeid );
		$Other = M ( 'Other' );
		$data ['stu_id'] = session ( 'userinfo.id' );
		$data ['type_id'] = $typeid;
		$ot = $Other->where ( $data )->find ();
		if ($this->isPost ()) {
			$data ['id'] = $ot ['id'];
			$data ['add_time'] = $ot ['add_time'];
			$data ['mod_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['cont'] = I ( 'post.content' );
			if ($Other->save ( $data )) {
				$this->redirect ( 'Manual/other', array (
						'typeid' => $typeid
				) );
			} else {
				$this->error ( 'ops，提交不成功，在试下！' );
			}
		} else {
			$content = $ot ['cont'];
			$this->assign ( 'content', $content );
			$nty = $Type = M ( 'NoteType' )->find ( $typeid );
			$this->assign ( 'ntype', $nty );
			$this->display ();
		}
	}


	public function train($id = 0) {
		$data ['id'] = I ( 'get.id' );
		$data ['stu_id'] = session ( 'userinfo.id' );
		$Train = M ( 'Train' );
		$tran = $Train->where ( $data )->find ();
		$this->assign ( 'train', $tran );
		$this->display ();
	}

	public function trainAdd() {
		if ($this->isPost ()) {
			$data ['stu_id'] = session ( 'userinfo.id' );
			$data ['add_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['mod_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['cont'] = I ( 'post.content' );
			$Train = M ( 'Train' );
			$Train->create ( $data );
			if ($id = $Train->add ()) {
				$this->redirect ( 'Manual/train', array (
						'id' => $id
				) );
			} else {
				$this->error ( 'ops，提交不成功，在试下！' );
			}
		} else {
			$TrainLimit = M('TrainLimit');
			$data['start'] = array(array('elt',date ('Y-m-d')),array('egt',date('Y-m-d',strtotime('-3 day'))));
			$limits = $TrainLimit->where($data)->select();
			$this->assign('title',$limits);
			$this->display ();
		}
	}

	public function trainMod($id = 0) {
		$data ['id'] = $id;
		$data ['stu_id'] = session ( 'userinfo.id' );
		$Train = M ( 'Train' );
		$tran = $Train->where ( $data )->find ();
		if ($this->isPost ()) {
			$data ['id'] = $tran ['id'];
			$data ['add_time'] = $tran ['add_time'];
			$data ['mod_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['cont'] = I ( 'post.content' );
			if ($Train->save ( $data )) {
				$this->redirect ( 'Manual/train', array (
						'id' => I ( 'get.id' )
				) );
			} else {
				$this->error ( 'ops，提交不成功，在试下！' );
			}
		} else {
			$this->assign ( 'train', $tran );
			$this->display ();
		}
	}

	public function work($id = 0) {
		$data ['id'] = I ( 'get.id' );
		$data ['stu_id'] = session ( 'userinfo.id' );
		$Work = M ( 'WorkWeek' );
		$wok = $Work->where ( $data )->find ();
		$this->assign ( 'work', $wok );
		$this->display ();
	}

	public function workAdd() {
		if ($this->isPost ()) {
			$data ['stu_id'] = session ( 'userinfo.id' );
			$data ['add_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['mod_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['cont'] = I ( 'post.content' );
			$WorkWeek = M ( 'WorkWeek' );
			$WorkWeek->create ( $data );
			if ($id = $WorkWeek->add ()) {
				$this->redirect ( 'Manual/work', array (
						'id' => $id
				) );
			} else {
				$this->error ( 'ops，提交不成功，在试下！' );
			}
		} else {
			$this->display ();
		}
	}

	public function workMod($id = 0) {
		$data ['id'] = $id;
		$data ['stu_id'] = session ( 'userinfo.id' );
		$Work = M ( 'WorkWeek' );
		$wok = $Work->where ( $data )->find ();
		if ($this->isPost ()) {
			$data ['id'] = $wok ['id'];
			$data ['add_time'] = $wok ['add_time'];
			$data ['mod_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['cont'] = I ( 'post.content' );
			if ($Work->save ( $data )) {
				$this->redirect ( 'Manual/work', array (
						'id' => I ( 'get.id' )
				) );
			} else {
				$this->error ( 'ops，提交不成功，在试下！' );
			}
		} else {
			$this->assign ( 'work', $wok );
			$this->display ();
		}
	}

	public function teach($id = 0) {
		$data ['id'] = $id;
		$data ['stu_id'] = session ( 'userinfo.id' );
		$Teach = M ( 'TeachWeek' );
		$tch = $Teach->where ( $data )->find ();
		$this->assign ( 'teach', $tch );
		$this->display ();
	}

	public function teachAdd() {
		if ($this->isPost ()) {
			$data ['stu_id'] = session ( 'userinfo.id' );
			$data ['add_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['mod_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['cont'] = I ( 'post.content' );
			$TeachWeek = M ( 'TeachWeek' );
			$TeachWeek->create ( $data );
			if ($id = $TeachWeek->add ()) {
				$this->redirect ( 'Manual/teach', array (
						'id' => $id
				) );
			} else {
				$this->error ( 'ops，提交不成功，在试下！' );
			}
		} else {
			$this->display ();
		}
	}

	public function teachMod($id = 0) {
		$data ['id'] = $id;
		$data ['stu_id'] = session ( 'userinfo.id' );
		$Teach = M ( 'TeachWeek' );
		$tch = $Teach->where ( $data )->find ();
		if ($this->isPost ()) {
			$data ['id'] = $tch ['id'];
			$data ['add_time'] = $tch ['add_time'];
			$data ['mod_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['cont'] = I ( 'post.content' );
			if ($Teach->save ( $data )) {
				$this->redirect ( 'Manual/teach', array (
						'id' => I ( 'get.id' )
				) );
			} else {
				$this->error ( 'ops，提交不成功，在试下！' );
			}
		} else {
			$this->assign ( 'teach', $tch );
			$this->display ();
		}
	}

	public function plan($id = 0) {
		$data ['id'] = $id;
		$data ['stu_id'] = session ( 'userinfo.id' );
		$TeachPlan = M ( 'TeachPlan' );
		$pn = $TeachPlan->where ( $data )->find ();
		$this->assign ( 'plan', $pn );
		$this->display ();
	}

	public function planAdd() {
		if ($this->isPost ()) {
			$data ['stu_id'] = session ( 'userinfo.id' );
			$data ['add_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['mod_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['cont'] = I ( 'post.content' );
			$data ['rethink'] = I ( 'post.rethink' );
			$TeachPlan = M ( 'TeachPlan' );
			$TeachPlan->create ( $data );
			if ($id = $TeachPlan->add ()) {
				$this->redirect ( 'Manual/plan', array (
						'id' => $id
				) );
			} else {
				$this->error ( 'ops，提交不成功，在试下！' );
			}
		} else {
			$this->display ();
		}
	}

	public function planMod($id = 0) {
		$data ['id'] = $id;
		$data ['stu_id'] = session ( 'userinfo.id' );
		$TeachPlan = M ( 'TeachPlan' );
		$pn = $TeachPlan->where ( $data )->find ();
		if ($this->isPost ()) {
			$data ['id'] = $pn ['id'];
			$data ['add_time'] = $pn ['add_time'];
			$data ['mod_time'] = date ( 'Y-m-d H:i:s', time () );
			$data ['cont'] = I ( 'post.content' );
			$data ['rethink'] = I ( 'post.rethink' );
			if ($TeachPlan->save ( $data )) {
				$this->redirect ( 'Manual/plan', array (
						'id' => I ( 'get.id' )
				) );
			} else {
				$this->error ( 'ops，提交不成功，在试下！' );
			}
		} else {
			$this->assign ( 'plan', $pn );
			$this->display ();
		}
	}

	public function record($id = 0) {
		$VisitRecord = M ( 'VisitRecord' );
		$rd = $VisitRecord->find ( $id );
		$this->assign ( 'record', $rd );
		$this->display ();
	}

	public function recordAdd() {
		$VisitRecord = M ( 'VisitRecord' );
		if ($this->isPost ()) {
			$VisitRecord->create ();
			$VisitRecord->stu_id = session ( 'userinfo.id' );
			$VisitRecord->add_time = date ( 'Y-m-d H:i:s', time () );
			$VisitRecord->mod_time = date ( 'Y-m-d H:i:s', time () );
			if ($id = $VisitRecord->add ()) {
				$this->redirect ( 'Manual/record', array (
						'id' => $id
				) );
			} else {
				$this->error ( 'ops，提交不成功，在试下！' );
			}
		} else {
			$this->display ();
		}
	}

	public function recordMod($id = 0) {
		$data['id'] = $id;
		$data ['stu_id'] = session ( 'userinfo.id' );
		$VisitRecord = M ( 'VisitRecord' );
		$rd = $VisitRecord->where($data)->find ();
		if ($this->isPost ()) {
			$data['class']     = I('post.class');
			$data['teacher']   = I('post.teacher');
			$data['time']      = I('post.time');
			$data['course']    = I('post.course');
			$data['title']     = I('post.title');
			$data['cont']   = I('post.content');
			$data['comment']   = I('post.comment');
			$data ['add_time'] = $rd ['add_time'];
			$data ['mod_time'] = date ( 'Y-m-d H:i:s', time () );
			if ($VisitRecord->save ( $data )) {
				$this->redirect ( 'Manual/record', array (
						'id' => I ( 'get.id' )
				) );
			} else {
				$this->error ( 'ops，提交不成功，在试下！' );
			}
		} else {
			$this->assign ( 'record', $rd );
			$this->display ();
		}
	}

	public function delarticle($id='',$sort='') {
		$Table = M($sort);
		$bool = $Table->where(array('stu_id'=>session ( 'userinfo.id' )))->delete($id);
		if($bool){
			$this->redirect('Manual/index');
		}else{
			$this->error('删除失败,请重新试试！');
		}
	}
}
?>