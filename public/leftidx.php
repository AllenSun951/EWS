<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset="utf-8"/>  
	<style type="text/css">
	    ul {
		    text-align:left;
			list-style:circle;
			margin:15px; 
			padding:5px;
		}
		li{
		   margin-bottom: 10px;
		}
		 
    a:link { text-decoration: none;color: blue}
    a:hover { text-decoration:underline;color: red} 
	
    </style>
  </head>
  <body style="background-color: menu">
    <div id="sideuser">
    <span style="text-align: left;"><font color="#4B0082" size="2">用户:<?php session_start(); echo $_SESSION['username'];?></font>
    </span>&nbsp;   			
    <span style="text-align: right;">
   			<a href="Mlogin/outlogin"  target="_top"><font size="2">注销</font></a>
   </span> 
   <HR style="FILTER: progid:DXImageTransform.Microsoft.Shadow(color:#987cb9,direction:145,strength:15)" width="100%" color=#987cb9 SIZE=1.5>
   </div>  
	<div id="sidebar" style="width:200px;height:30px;">
	  <ul >
	    <li><a href="introduce.html"  class="color" target="mainFrame">首页</a>
	    <li><a href="student/result"  class="color" target="mainFrame">警示结果查询</a></li>
		<li><a href="student/failCourse"  class="color" target="mainFrame">不及格课程</a></li>
		<li><a href="student/creditFinish"  class="color" target="mainFrame">学分完成情况</a></li>
		<li><a href="Personal/PersonalInfo"  class="color" target="mainFrame">基本信息</a></li>
		<li><a href="helpidx.html"  class="color" target="mainFrame">帮助</a></li>
		<!-- >li><a href="login.php"  class="color" target="_top">注销</a-->
      </ul>	
	</div>

  </body>
</html>