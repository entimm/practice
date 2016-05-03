<?php
 Class ToPDFAction extends Action{
 	public function index($id,$t='t'){
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

 	public function pdf(){
 		header("Content-type:text/html;charset=utf-8");
	    $stuinfos = M('StuInfo')->field('id,name')->select();
	    foreach ($stuinfos as $value) {
	    	$dirname = 'E:/Practice/PDF/'.$value['id'].$value['name'];
	    	// mkdir(iconv('utf-8', 'gbk', $dirname));
	    	$vrs = M('VisitRecord')->where(array('stu_id'=>$value['id']))->field('id')->select();
	    	$windir = 'E:\\Practice\\PDF\\';
	    	$todir = $windir.$value['id'].$value['name'].'\\';
	    	foreach ($vrs as $key => $val) {
	    		$key++;
	    		$str='wkhtmltopdf http://210.37.0.10:888/ToPDF/index/t/vr/id/'.$val['id'].' '.'E:/Practice/PDF/'.$value['id'].'_VisitRecord_'.$key.'.pdf';
	    		echo $str.'<br />';
	    		echo 'move '.$windir.$value['id'].'_VisitRecord_'.$key.'.pdf '.$todir.'听评课记录'.$key.'.pdf'.'<br />';
	    	}

	    	$tps = M('TeachPlan')->where(array('stu_id'=>$value['id']))->field('id')->select();
	    	foreach ($tps as $key => $val) {
	    		$key++;
	    		$str='wkhtmltopdf http://210.37.0.10:888/ToPDF/index/t/tp/id/'.$val['id'].' '.'E:/Practice/PDF/'.$value['id'].'_TeachPlan_'.$key.'.pdf';
	    		echo $str.'<br />';
	    		echo 'move '.$windir.$value['id'].'_TeachPlan_'.$key.'.pdf '.$todir.'教学实习教案'.$key.'.pdf'.'<br />';
	    	}

	    	$tws = M('TeachWeek')->where(array('stu_id'=>$value['id']))->field('id')->select();
	    	foreach ($tws as $key => $val) {
	    		$key++;
	    		$str='wkhtmltopdf http://210.37.0.10:888/ToPDF/index/t/tw/id/'.$val['id'].' '.'E:/Practice/PDF/'.$value['id'].'_TeachWeek_'.$key.'.pdf';
	    		echo $str.'<br />';
	    		echo 'move '.$windir.$value['id'].'_TeachWeek_'.$key.'.pdf '.$todir.'班主任工作周记'.$key.'.pdf'.'<br />';
	    	}

	    	$wws = M('WorkWeek')->where(array('stu_id'=>$value['id']))->field('id')->select();
	    	foreach ($wws as $key => $val) {
	    		$key++;
	    		$str='wkhtmltopdf http://210.37.0.10:888/ToPDF/index/t/ww/id/'.$val['id'].' '.'E:/Practice/PDF/'.$value['id'].'_WorkWeek_'.$key.'.pdf';
	    		echo $str.'<br />';
	    		echo 'move '.$windir.$value['id'].'_WorkWeek_'.$key.'.pdf '.$todir.'教学工作实习周记'.$key.'.pdf'.'<br />';
	    	}

	    	$ts = M('Train')->where(array('stu_id'=>$value['id']))->field('id')->select();
	    	foreach ($ts as $key => $val) {
	    		$key++;
	    		$str='wkhtmltopdf http://210.37.0.10:888/ToPDF/index/t/t/id/'.$val['id'].' '.'E:/Practice/PDF/'.$value['id'].'_Train_'.$key.'.pdf';
	    		echo $str.'<br />';
	    		echo 'move '.$windir.$value['id'].'_Train_'.$key.'.pdf '.$todir.'岗前培训'.$key.'.pdf'.'<br />';
	    	}

	    	$os = M('Other')->join('INNER JOIN sx_note_type ON sx_note_type.id = sx_other.type_id')
	    					->where(array('stu_id'=>$value['id']))
	    					->field('sx_other.id,type')->select();
	    	foreach ($os as $key => $val) {
	    		$key++;
	    		$str='wkhtmltopdf http://210.37.0.10:888/ToPDF/index/t/o/id/'.$val['id'].' '.'E:/Practice/PDF/'.$value['id'].'_Other_'.$key.'.pdf';
	    		echo $str.'<br />';
	    		echo 'move '.$windir.$value['id'].'_Other_'.$key.'.pdf '.$todir.'其它_'.$val['type'].'.pdf'.'<br />';
	    	}
	    }
 	}
 }
 ?>