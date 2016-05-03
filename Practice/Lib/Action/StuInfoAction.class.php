<?php

Class StuInfoAction extends CommonAction{
  public function index(){
  	$StuInfo = M('StuInfo');
  	$stuinfos = $StuInfo->field(array('id','name','lastlg','lastip','logtimes','popular'))
  						->order('lastlg desc')
  						->select();
  	$this->assign('stuinfos',$stuinfos);
  	$this->display();
 }

 public function arcinfo(){
 	$stuinfos = M('StuInfo')->field(array('id','name'))
 						->order('lastlg desc')
 						->select();
	$arcinfo = array();
	$total = array('total' => 0,'tp' => 0, 'vr' =>0, 'tw' => 0, 'ww' => 0, 't' => 0, 'o' => 0 );
 	foreach ($stuinfos as $value) {
 		$arcinfo[$value['id']]['id'] = $value['id'];
 		$arcinfo[$value['id']]['name'] = $value['name'];
 		$arcinfo[$value['id']]['tpn'] = M('TeachPlan')->where(array('stu_id' => $value['id']))->count();
 		$arcinfo[$value['id']]['vrn'] = M('VisitRecord')->where(array('stu_id' => $value['id']))->count();
 		$arcinfo[$value['id']]['twn'] = M('TeachWeek')->where(array('stu_id' => $value['id']))->count();
 		$arcinfo[$value['id']]['wwn'] = M('WorkWeek')->where(array('stu_id' => $value['id']))->count();
 		$arcinfo[$value['id']]['tn'] = M('Train')->where(array('stu_id' => $value['id']))->count();
 		$arcinfo[$value['id']]['on'] = M('Other')->where(array('stu_id' => $value['id']))->count();
 		$arcinfo[$value['id']]['total'] = $arcinfo[$value['id']]['tpn']
 									    + $arcinfo[$value['id']]['vrn']
 									    + $arcinfo[$value['id']]['twn']
 									    + $arcinfo[$value['id']]['wwn']
 									    + $arcinfo[$value['id']]['tn']
 									    + $arcinfo[$value['id']]['on'];
		$total['total'] += $arcinfo[$value['id']]['total'];
    $total['tp'] += $arcinfo[$value['id']]['tpn'];
    $total['vr'] += $arcinfo[$value['id']]['vrn'];
    $total['tw'] += $arcinfo[$value['id']]['twn'];
    $total['ww'] += $arcinfo[$value['id']]['wwn'];
    $total['t'] += $arcinfo[$value['id']]['tn'];
    $total['o'] += $arcinfo[$value['id']]['on'];
 	}
 	$arcinfo = array_sort($arcinfo,'total','desc');
 	$this->assign('total',$total);
 	$this->assign('arcinfo',$arcinfo);
  	$this->display();
 }
}
 ?>