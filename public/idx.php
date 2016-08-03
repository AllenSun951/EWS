<?php session_start(); // Session 启动！！！！
		if(!isset($_SESSION['userid'])||$_SESSION['userqx']<>0){ // 要不要判断 ID 是否存在于系统数据库中？？？---  2015 08 24  中午
			//header("Location:../login.php");
			echo '<br/>';echo '<br/>';
			//echo '未登录！请点击此处 <a href="login.php" target="_top">登录</a>';
			//redirect('login.php');
			header("Location:login.php");
			exit();
		}
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset="utf-8"/>    <!-- 设置页面的编码格式为utf-8-->
    <title>常熟理工学院学业警示系统</title>
  </head >
  <frameset rows="18%,77%,*"  frameborder="1" border="1" framespacing="0" bordercolor="4682B4">
    <frame name="topFrame" src="topidx.html" scrolling="no"  noresize="noresize" marginwidth="0" />
	  <frameset id="mainFrameset" cols="12%,*" framespacing="2" frameborder="1"   border="1" framespacing="0"  >
	    <frame name="leftFrame" src="leftidx.php" scrolling="no" noresize="noresize" scrolling="auto"/>
	    <frame name="mainFrame" src="introduce.html" noresize="noresize" scrolling="auto"/>
	  </frameset>
	<frame name="footerFrame" src="footer.html" scrolling="no"   noresize="noresize" marginheight="0" marginwidth="0" />
  </frameset>

</html>