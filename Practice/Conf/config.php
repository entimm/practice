<?php
return array (
		'DB_DSN'                => 'mysql://root:root@127.0.0.1:3306/practice',
		'TMPL_L_DELIM'          => '<{',
		'TMPL_R_DELIM'          => '}>',
		'TMPL_TEMPLANTE_SUFFIX' => '.html',
		'URL_HTML_SUFFIX'       => 'php',
		'DB_PREFIX'             => 'sx_',
		'URL_ROUTER_ON'         => true,
		'URL_ROUTE_RULES' 		=> array(
		    'Visit/lists/sort/:t' => 'Visit/lists?sort=:1&p=1',
		),

		// 'SHOW_PAGE_TRACE' =>true,
		'SESSION_AUTO_START'   => true,
		'SESSION_OPTIONS'      => array('expire'=>3600),
		'URL_MODEL'            => 2,

		'MAIL_HOST'            =>"smtp.qq.com",
		'MAIL_PORT'			   =>25,
		'MAIL_USERNAME'        =>"",
		'MAIL_PASSWORD'		   =>"",


		'TMPL_PARSE_STRING'  => array (
				'__JS__'       => '/Public/Js',
				'__IMAGES__'   => '/Public/Images',
				'__UPLOAD__'   => '/Uploads',
				'__CSS__'      => '/Public/Css',
				'__EDITOR__'   => '/UEditor',
				'__JQUERYUI__' => '/Public/Jquery-ui-1.10.3',
				'__ORBIT__'    => '/Public/Orbit'
		)
);
?>
