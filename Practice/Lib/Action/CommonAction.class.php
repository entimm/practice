<?php
class CommonAction extends Action {
	public function _initialize() {
		if (! session ( '?userinfo' )) {
			// session(null);
			$this->redirect ( 'Login/index' );
		}
	}
}
?>