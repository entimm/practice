<?php
 Class VisitTeachAction extends CommonAction {
	public function index($space = ''){
		if($space != ''){
			$data['teach_id'] = $space;
		}

		$TeachInfo = M('TeachInfo');
		$teachs = $TeachInfo->field('id,name')->select();
		$this->assign('teachs',$teachs);

		$condition = $TeachInfo->field('id,name')->find($space);
		$this->assign('condition',$condition);

		$arcs = array();
		$arctypes = M('ArctpTeach')->select();
		$this->assign('sorts',$arctypes);
		$ArcTeach = M('ArcTeach');
		foreach ($arctypes as $key =>$value) {
			$data['arctype'] = $value['id'];
			$arcs[$key]['arcs'] = $ArcTeach->join('INNER JOIN sx_teach_info ON sx_teach_info.id = sx_arc_teach.teach_id')
										   ->field('sx_arc_teach.id,teach_id,add_time,browse_times,sx_teach_info.name')
										   ->where($data)
										   ->order('add_time desc')
										   ->select();
			$arcs[$key]['type'] = $value['type'];
			$arcs[$key]['tpid'] = $value['id'];
		}
		$this->assign('arcs',$arcs);
		$this->display();
	}

	public function lists($space = '',$sort = ''){
		if($space != ''){
			$data['teach_id'] = $space;
		}
		if($sort != ''){
			$data['arctype'] = $sort;
		}

		$TeachInfo = M('TeachInfo');
		$teachs = $TeachInfo->field('id,name')->select();
		$this->assign('teachs',$teachs);

		$sorts = M('ArctpTeach')->select();
		$this->assign('sorts',$sorts);

		$condition['space'] = $TeachInfo->field('id,name')->find($space);
		$condition['sort'] = M('ArctpTeach')->find($sort);
		$this->assign('condition',$condition);

		$ArcTeach = M('ArcTeach');
		$arcs = $ArcTeach->join('INNER JOIN sx_teach_info ON sx_teach_info.id = sx_arc_teach.teach_id')
					     ->field('sx_arc_teach.id,teach_id,add_time,browse_times,sx_teach_info.name')
					     ->where($data)
					     ->order('add_time desc')
					     ->select();

	    import("ORG.Util.Page");
	    $Page = new Page($count,25);
	    $Page->rollPage = 5;
	    $show = $Page->show();
	    $this->assign('page',$show);

	    $this->assign('empty','<li><b>这里还没有写一篇内容哦！</b></li>');

	    $this->assign('arcs',$arcs);
	    $this->display();
	}

	public function details($id=''){
		$data['sx_arc_teach.id'] = $id;
		$ArcTeach = M('ArcTeach');
		$arc = $ArcTeach->join('INNER JOIN sx_teach_info ON sx_teach_info.id = sx_arc_teach.teach_id')
						 ->join('INNER JOIN sx_arctp_teach ON sx_arctp_teach.id = sx_arc_teach.arctype')
					   	 ->field('sx_arc_teach.id,add_time,mod_time,browse_times,sx_teach_info.name,type,cont')
					   	 ->where($data)
					   	 ->order('add_time desc')
					   	 ->find();
		$this->assign('arc',$arc);
		$this->display();
	}
 }
 ?>