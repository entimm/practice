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
	    </style><![endif]--><script type="text/javascript" src="__JQUERYUI__/jquery-1.9.1.js"></script><script type="text/javascript" src="__JS__/jquery.cookie.js"></script><script type="text/javascript" src="__EDITOR__/ueditor.config.js"></script><script type="text/javascript" src="__EDITOR__/ueditor.all.min.js"></script></head><body><div id="wrapper"><div id="header"><div id="logo"><a href="/"><img src="__IMAGES__/logo.png" /></a></div><?php if(isset($_SESSION['userinfo'])): ?><div id="mainnav" style="clear:none;"><ul><li><a href="/">首页</a></li><?php if($_SESSION['userinfo']['level']== student): ?><li><a href="<?php echo U('Manual/index');?>">实习手册</a></li><li><a href="<?php echo U('Visit/index');?>">访问同学</a></li><li><a href="<?php echo U('Answer/index');?>">在线咨询</a></li><li><a href="<?php echo U('Apply/index');?>">实习报名</a></li><li><a href="<?php echo U('StuInfo/index');?>">统计</a></li><?php elseif($_SESSION['userinfo']['level']== teacher): ?><li><a href="<?php echo U('ManualTeach/index');?>">实习手册</a></li><li><a href="<?php echo U('VisitTeach/index');?>">访问老师</a></li><li><a href="<?php echo U('Visit/index');?>">实习督察</a></li><li><a href="<?php echo U('Search/index');?>">内容搜索</a></li><li><a href="<?php echo U('Answer/index');?>">在线解答</a></li><li><a href="<?php echo U('StuInfo/index');?>">统计</a></li><?php endif; ?><li><a href="<?php echo U('UserInfo/index');?>">个人信息</a></li><li><a href="<?php echo U('Login/logout');?>">退出</a></li></ul></div><?php endif; ?></div><?php if(($_SESSION['userinfo']['level']) == "student"): ?><h3>我要提问：</h3><script type="text/plain" name="question" id="question" style="height:100px" required></script><input type="submit" id="submit" value="提交" /><span id="tips"></span><?php else: ?><h3>在线解答(请点击回复)：</h3><?php endif; if(is_array($ques)): $k = 0; $__LIST__ = $ques;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="question_set"><div class="question"><span class="time"><?php echo (date("Y年m月d日H时i分" ,strtotime($vo["time"]))); ?></span><div class="authinfo"><span class="num"><?php echo ($k); ?>#</span><?php echo ($vo["name"]); ?></div><a href="javascript:;" data-id="<?php echo ($vo["id"]); ?>">回复</a><?php echo (htmlspecialchars_decode($vo["question"])); ?></div><?php if(($vo["isans"]) == "1"): if(is_array($vo['ans'])): $kk = 0; $__LIST__ = $vo['ans'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($kk % 2 );++$kk;?><div class="answer"><span class="time"><?php echo (date("Y年m月d日H时i分" ,strtotime($v["time"]))); ?></span><div class="authinfo"><span class="nnum"><?php echo ($kk); ?>##</span><?php echo ($v["author"]); ?></div><?php echo (htmlspecialchars_decode($v["answer"])); ?></div><?php endforeach; endif; else: echo "" ;endif; endif; ?></div><?php endforeach; endif; else: echo "" ;endif; ?><script type="text/javascript"><?php if(($_SESSION['userinfo']['level']) == "student"): ?>UE.getEditor('question',
	    {
	        toolbars:[
	        ['emotion','insertimage','|', 'undo', 'redo']
	        ]
	    });<?php endif; ?>	$("#submit").click(function() {
		var questext = UE.getEditor('question').getContent();
		if( questext == ''){
			$('#tips').html('请输入内容').show().fadeOut(3000);
			return;
		}
		$.ajax({
			url: '<?php echo U("Answer/ajax");?>',
			type: 'POST',
			data: {question: questext},
			beforeSend:function(){$("#tips").html("loading...").show();},
			success:function(){$("#tips").html("").hide();}
		})
		.done(function(data) {
            var str = '<div class="question_set"><div class="question"><div class="authinfo">我<span class="time">刚刚</span></div>'
            		+ questext + '</div></div>';
            $("#tips").after(str);
            $("#tips").html(data.info).show().fadeOut(3000);
            UE.getEditor('question').setContent("");
		})
		.fail(function() {
			$('#tips').html("请求失败");
		})
		.always(function(data) {
			$('#tips').html(data.info).show().fadeOut(3000);
		});

	});

	$(".question > a").click(function() {
		id = $(this).attr('data-id');
		var text = '<div class="answer_box"><div id="toans"><textarea name="answer" style="padding:5px;" class="warn">在这里写下你的回复吧！</textarea><button type="button" data-id="'+id+'" class="ansbt">提交</button></div></div>';
		$('#toans').parent().remove();
		$(this).parent().after(text);

		$("#toans textarea").on('focus',function(){
			if($(this).val() == "在这里写下你的回复吧！"){
				$(this).val("");
				$(this).removeClass('warn');
			}
		});
		$("#toans textarea").on('blur',function(){
			if($(this).val() == ""){
				$(this).val("在这里写下你的回复吧！");
				$(this).addClass('warn');
			}
		});

		$("#toans button").on('click', function () {
			var answer = $('textarea').val();
			if( answer == ''|| answer == '在这里写下你的回复吧！'){
				$('#toans').append('<p class="warn">请输入内容...</p>');
				return;
			}
			var id = $(this).attr('data-id');
			$.ajax({
				url: '<?php echo U("Answer/anwer");?>',
				type: 'POST',
				data: {answer: answer,to:id}
			})
			.done(function(data) {
				$('#toans').append(data.info);
				$('#toans').parent().parent().append('<div class="answer"><div class="authinfo">我<span class="time">刚刚</span></div>'
            		+ answer + '</div>');
				$('#toans').parent().remove();
			})
			.fail(function(data) {
				$('#toans').append('请求失败');
			})
			.always(function(data) {
				$('#toans').append(data.info);
			});
		});
	});

</script><div class="clear"></div></div><div id="footer">&copy;2013海南师范大学信息科学技术学院</div></body></html>