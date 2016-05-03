<?php
class LoginAction extends Action {
	public function _before_index() {
		if (session ( '?userinfo.id' )) {
			$this->redirect ( 'Index/index' );
		}else{
			session(null);
		}
	}
	public function index() {
		if (empty ( $_POST )) {
			$this->display ();
		} else {
			$data ['id'] = I ( 'post.account' );
			$data ['password'] = I ( 'post.user_password', '', 'md5' );
			if (I ( 'post.level', 'student' ) == "student") {
				$UserInfo = M ( 'StuInfo' );
			} else if (I ( 'post.level', 'student' ) == "teacher") {
				$UserInfo = M ( 'TeachInfo' );
			}

			$userinfo = $UserInfo->where ( $data )
								 ->field('password',true)
								 ->find ();

			if (! $userinfo) {
				$this->error ( '用户名或者密码错误！' );
			}
			$UserInfo->lastip = $_SERVER['REMOTE_ADDR'];
			$UserInfo->lastlg = date ( 'Y-m-d H:i:s', time () );
			$UserInfo->save();
			$UserInfo->where( $data )->setInc('logtimes');
			if (I ( 'post.level', 'student' ) == "student") {
				$userinfo ['level'] = 'student';
			} else if (I ( 'post.level', 'student' ) == "teacher") {
				$userinfo ['level'] = 'teacher';
			}
			$userinfo ['nowip'] = $_SERVER['REMOTE_ADDR'];
			session ( 'userinfo', $userinfo );
			$this->redirect ( 'Index/index' );
		}
	}
	public function _before_logout() {
		if (! session ( '?userinfo.id' )) {
			$this->redirect ( 'Login/index' );
		}
	}
	public function logout() {
		session ( null );
		if (isset ( $_COOKIE [session_name ()] )) {
			cookie ( session_name (), null );
		}
		session_destroy ();
		$this->redirect ( 'Login/index' );
	}
}

?>