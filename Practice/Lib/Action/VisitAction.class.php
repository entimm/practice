<?php
 Class VisitAction extends CommonAction{
   public function index($space=''){
    $data['id'] != '';
    if($space != ''){
      $data['stu_id'] = $space;
    }
      $WorkWeek = M('WorkWeek');
      $workweeks = $WorkWeek->join('INNER JOIN sx_stu_info ON sx_stu_info.id = sx_work_week.stu_id')
          ->field('sx_work_week.id,sx_stu_info.name,stu_id,add_time,browse_times')
          ->where($data)
          ->limit('20')
          ->order('add_time DESC')
          ->select();
      $this->assign('workweeks',$workweeks);

      $TeachWeek = M('TeachWeek');
      $teachweeks = $TeachWeek->join('INNER JOIN sx_stu_info ON sx_stu_info.id = sx_teach_week.stu_id')
          ->field('sx_teach_week.id,sx_stu_info.name,stu_id,add_time,browse_times')
          ->where($data)
          ->limit('20')
          ->order('add_time DESC')
          ->select();
      $this->assign('teachweeks',$teachweeks);

      $Other = M('Other');
      $others = $Other->join('INNER JOIN sx_stu_info ON sx_stu_info.id = sx_other.stu_id')
          ->join('INNER JOIN sx_note_type ON sx_note_type.id = sx_other.type_id')
          ->field('sx_other.id,sx_stu_info.name,stu_id,add_time,browse_times,sx_note_type.type')
          ->where($data)
          ->limit('20')
          ->order('add_time DESC')
          ->select();
      $this->assign('others',$others);

      $TeachPlan = M('TeachPlan');
      $teachplans = $TeachPlan->join('INNER JOIN sx_stu_info ON sx_stu_info.id = sx_teach_plan.stu_id')
          ->field('sx_teach_plan.id,sx_stu_info.name,stu_id,add_time,browse_times')
          ->where($data)
          ->limit('20')
          ->order('add_time DESC')
          ->select();
      $this->assign('teachplans',$teachplans);

      $VisitRecord = M('VisitRecord');
      $visitrecords = $VisitRecord->join('INNER JOIN sx_stu_info ON sx_stu_info.id = sx_visit_record.stu_id')
          ->field('sx_visit_record.id,sx_stu_info.name,stu_id,add_time,browse_times')
          ->where($data)
          ->limit('20')
          ->order('add_time DESC')
          ->select();
      $this->assign('visitrecords',$visitrecords);

   		$Train = M('Train');
   		$trains = $Train->join('INNER JOIN sx_stu_info ON sx_stu_info.id = sx_train.stu_id')
   			  ->field('sx_train.id,sx_stu_info.name,stu_id,add_time,browse_times')
          ->where($data)
          ->limit('20')
   			  ->order('add_time DESC')
   			  ->select();
      $this->assign('trains',$trains);

      $StuInfo = M('StuInfo');
      $stus = $StuInfo->field('id,name')->select();
      $this->assign('stus',$stus);

      $condition = $StuInfo->field('id,name')->find($space);
      $this->assign('condition',$condition);

   		$this->display();
  }

  public function details($id,$t='t'){
    $arr = array('vr'=>array('VisitRecord','sx_visit_record','听评课记录'),
                 'tp'=>array('TeachPlan','sx_teach_plan','教学实习教案'),
                 'tw'=>array('TeachWeek','sx_teach_week','班主任工作周记'),
                 'ww'=>array('WorkWeek','sx_work_week','教学工作实习周记'),
                  'o'=>array('Other','sx_other','其他'),
                  't'=>array('Train','sx_train','岗前培训')
                  );
    if(!array_key_exists($t, $arr)){
        $this->error('对不起，请示错误！');
    }
    $tab = $arr[$t][0];
    $tab_str = $arr[$t][1];
		$Table = M ( $tab );
    $data [$tab_str.'.id'] = $id;
    if(session($tab) != $id){
        $Table->where($data)->setInc("browse_times");
        $stu_id = $Table->where(array('id'=>$id))->getField('stu_id');
        M('StuInfo')->where(array('id'=>$stu_id))
                       ->setInc('popular');
        session($tab,$id);
    }
    if($t == 'o'){
        $article = $Table->join('INNER JOIN sx_stu_info ON sx_stu_info.id = '.$tab_str.'.stu_id')
                ->join('INNER JOIN sx_note_type ON sx_note_type.id = sx_other.type_id')
                ->field('sx_stu_info.name,stu_id,add_time,mod_time,cont,browse_times,sx_note_type.type')
                ->where ( $data )
                ->find();

        $this->assign('title',$article['type']);
    }else if($t == 'vr'){
        $article = $Table->join('INNER JOIN sx_stu_info ON sx_stu_info.id = '.$tab_str.'.stu_id')
                ->field('sx_stu_info.name,stu_id,add_time,mod_time,cont,browse_times,sx_visit_record.class,teacher,time,course,title,comment')
                ->where ( $data )
                ->find();
        $this->assign('title',$arr[$t][2]);
    }
    else if($t == 'tp'){
        $article = $Table->join('INNER JOIN sx_stu_info ON sx_stu_info.id = '.$tab_str.'.stu_id')
                ->field('sx_stu_info.name,stu_id,add_time,mod_time,cont,browse_times,rethink')
                ->where ( $data )
                ->find();
        $this->assign('title',$arr[$t][2]);
    }else{
        $article = $Table->join('INNER JOIN sx_stu_info ON sx_stu_info.id = '.$tab_str.'.stu_id')
                ->field('sx_stu_info.name,stu_id,add_time,mod_time,cont,browse_times')
                ->where ( $data )
                ->find();
        $this->assign('title',$arr[$t][2]);
    }
    $this->assign('sort' ,$t);
		$this->assign ( 'article', $article );
		$this->display ();
  }

  public function lists($space = '',$sort = ''){
    $arr = array('vr'=>array('VisitRecord','sx_visit_record','听评课记录'),
                 'tp'=>array('TeachPlan','sx_teach_plan','教学实习教案'),
                 'tw'=>array('TeachWeek','sx_teach_week','班主任工作周记'),
                 'ww'=>array('WorkWeek','sx_work_week','教学工作实习周记'),
                  'o'=>array('Other','sx_other','其他'),
                  't'=>array('Train','sx_train','岗前培训')
                  );
    if(!array_key_exists($sort, $arr)){
        $this->error('对不起，请示错误！');
    }
    $tab = $arr[$sort][0];
    $tab_str = $arr[$sort][1];
    $Table = M ( $tab );

    $data['id'] != '';
    if($space != ''){
      $data['stu_id'] = $space;
    }
    $lists = array();
    $count = 0;
    if($sort != 'o'){
        $lists = $Table->join('INNER JOIN sx_stu_info ON sx_stu_info.id = '.$tab_str.'.stu_id')
            ->field($tab_str.'.id,sx_stu_info.name,stu_id,add_time,browse_times')
            ->where($data)
            ->page($_GET['p'].',25')
            ->order('add_time DESC')
            ->select();
        $count = $Table->where($data)->count();
    }else{
        $lists = $Table->join('INNER JOIN sx_stu_info ON sx_stu_info.id = '.$tab_str.'.stu_id')
            ->join('INNER JOIN sx_note_type ON sx_note_type.id = sx_other.type_id')
            ->field($tab_str.'.id,sx_stu_info.name,stu_id,add_time,browse_times,sx_note_type.type')
            ->where($data)
            ->page($_GET['p'].',25')
            ->order('add_time DESC')
            ->select();
        $count = $Table->where($data)->count();
    }

    import("ORG.Util.Page");
    $Page = new Page($count,25);
    $Page->rollPage = 5;
    $show = $Page->show();
    $this->assign('page',$show);

    $StuInfo = M('StuInfo');
    $stus = $StuInfo->field('id,name')->select();
    $this->assign('stus',$stus);

    $condition = $StuInfo->field('id,name')->find($space);
    $condition['sort'] = $arr[$sort][2];
    $this->assign('condition',$condition);

    $this->assign('empty','<li><b>这里还没有写一篇内容哦！</b></li>');

    $this->assign('lists',$lists);
    $this->assign('sort',$sort);
    $this->assign('title',$arr[$sort][2]);
    $this->display();
  }
 }
 ?>