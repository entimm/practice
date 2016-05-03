<?php if (!defined('THINK_PATH')) exit();?><!doctype html><html lang="en"><head><meta charset="UTF-8" /><title>海师信息学院实习系统</title><meta name="keywords" content="海南师范大学 实习管理系统"><link rel="shortcut icon" href="__IMAGES__/favicon.ico" /><link rel="stylesheet" type="text/css" href="__JQUERYUI__/themes/base/jquery.ui.all.css" /><link rel="stylesheet" type="text/css" href="__ORBIT__/orbit-1.2.3.css" /><link rel="stylesheet" type="text/css" href="__CSS__/style.css" /><!--[if IE]><style type="text/css">	         .timer {
	             display: none!important;
	         }
	         div.caption {
	             background: transparent;
	             filter: progid: DXImageTransform.Microsoft.gradient(startColorstr = #99000000,endColorstr= #99000000);zoom: 1; }
	         .answer {
				border:1px solid #AEAEAE;
				border-top: 0 none;
	         }
	    </style><![endif]--><script type="text/javascript" src="__JQUERYUI__/jquery-1.9.1.js"></script><script type="text/javascript" src="__JS__/jquery.cookie.js"></script><script type="text/javascript" src="__EDITOR__/ueditor.config.js"></script><script type="text/javascript" src="__EDITOR__/ueditor.all.min.js"></script></head><body><div id="wrapper"><div id="header"><div id="logo"><a href="/"><img src="__IMAGES__/logo.png" /></a></div><?php if(isset($_SESSION['userinfo'])): ?><div id="mainnav" style="clear:none;"><ul><li><a href="/">首页</a></li><?php if($_SESSION['userinfo']['level']== student): ?><li><a href="<?php echo U('Manual/index');?>">实习手册</a></li><li><a href="<?php echo U('Visit/index');?>">访问同学</a></li><li><a href="<?php echo U('Answer/index');?>">在线咨询</a></li><li><a href="<?php echo U('Apply/index');?>">实习报名</a></li><li><a href="<?php echo U('StuInfo/index');?>">统计</a></li><?php elseif($_SESSION['userinfo']['level']== teacher): ?><li><a href="<?php echo U('ManualTeach/index');?>">实习手册</a></li><li><a href="<?php echo U('VisitTeach/index');?>">访问老师</a></li><li><a href="<?php echo U('Visit/index');?>">实习督察</a></li><li><a href="<?php echo U('Search/index');?>">内容搜索</a></li><li><a href="<?php echo U('Answer/index');?>">在线解答</a></li><li><a href="<?php echo U('StuInfo/index');?>">统计</a></li><?php endif; ?><li><a href="<?php echo U('UserInfo/index');?>">个人信息</a></li><li><a href="<?php echo U('Login/logout');?>">退出</a></li></ul></div><?php endif; ?></div><div id="info" style="width:435"><div style="font-size: 25px"><em>你好</em>，<b><?php echo ($_SESSION['userinfo']['name']); ?></b><em><?php if($_SESSION['userinfo']['level']== student): ?>同学<?php else: ?>老师<?php endif; ?></em></div><div style="font-size: 15px"><p>上一次登录时间：<?php echo (date("Y年m月d日H时i分" ,strtotime($_SESSION['userinfo']['lastlg']))); ?></p><p>累计登录次数：<?php echo ($_SESSION['userinfo']['logtimes']); ?></p><p>上次登录ip：<?php echo (($_SESSION['userinfo']['lastip'])?($_SESSION['userinfo']['lastip']):"--"); ?></p><p>本次登录ip：<?php if(($_SESSION['userinfo']['nowip']) == $_SESSION['userinfo']['lastip']): ?>和上次一样哦<?php else: echo ($_SESSION['userinfo']['nowip']); endif; ?></p></div></div><a href="<?php echo U('Notice/addNotice');?>"><h2>实习公告</h2></a><ul class="list"><?php if(is_array($notices)): $i = 0; $__LIST__ = $notices;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><?php if(($_SESSION['userinfo']['level']) == "teacher"): ?><span class="mod"><a href="<?php echo U('Notice/modNotice',array('id'=>$vo['id']));?>">改</a></span><span class="del"><a href="javascript:;" data-id="<?php echo ($vo['id']); ?>">删</a></span><?php endif; ?><span class="time"><?php echo (date("Y/m/d" ,strtotime($vo["time"]))); ?></span><span class="num"><?php echo ($i); ?>#</span><a href="<?php echo U('Index/notice',array('id'=>$vo['id']));?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?></ul><h2>下载</h2><ul class="list"><li><img src="__EDITOR__/dialogs/attachment/fileTypeImages/icon_xls.gif" /><a href="__EDITOR__/php/upload/20130912/2013年顶岗实习班级管理岗前强化培训安排一览表.xls">2013年顶岗实习班级管理岗前强化培训安排一览表</a></li><li><img src="__EDITOR__/dialogs/attachment/fileTypeImages/icon_doc.gif" /><a href="__EDITOR__/php/upload/20130912/实习管理系统用户指南.doc">实习管理系统用户指南</a></li><li><img src="__EDITOR__/dialogs/attachment/fileTypeImages/icon_doc.gif" /><a href="__EDITOR__/php/upload/20130912/海南师范大学信息科学技术学院实习方案(2010级）.doc">海南师范大学信息科学技术学院实习方案(2010级）</a></li><li><img src="__EDITOR__/dialogs/attachment/fileTypeImages/icon_rar.gif" /><a href="__EDITOR__/php/upload/20130912/实习工作相关表格.rar">实习工作相关表格.rar</a></li></ul><?php if(($_SESSION['userinfo']['level']) == "teacher"): ?><script>    $(".del a").click(function(){
        if(confirm("真的要删除这篇公告吗？")){
            id=$(this).attr("data-id");
            pnt=$(this).parent().parent();
            $.ajax({
                url: '<?php echo U("Notice/delNotice");?>',
                type: 'POST',
                data: {id: id},
            })
            .done(function() {
                pnt.remove();
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });

        }
    });
</script><?php endif; ?><div class="clear"></div></div><div id="footer">&copy;2013海南师范大学信息科学技术学院</div></body></html>