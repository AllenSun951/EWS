<html>

  <head>
  <meta http-equiv="Content-Type" content="text/html; charset="utf-8"/>    <!-- 设置页面的编码格式为utf-8-->
	<style type="text/css">
	/* *{ margin-left:1px; margin-right:1px; padding:0;} */
	*{ margin:0px; margin-left:1.5px; padding:0;}
	    ul {
		    text-align:left;
			list-style:circle;
			margin:10%; 
			padding:5px;
		}
		li{
		   margin-bottom: 10px;
		}
		 
    a:link { text-decoration: none;color: blue}
    a:hover { text-decoration:underline;color: red} 
/*  	.sidebar ul li ul li{list-style:square;}  */
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
	    <li><a href="frontCo/resultAlert"  class="color" target="mainFrame">警示结果查询</a>
	    	<ul>
	    		<li style="list-style-type:square"> <a href="SeniorSearch/SeniorSearchAlert"  class="color" target="mainFrame">高级查询</a></li>
	    	</ul>
	    </li>
	    <li><a href="ChartCo/chartAlert"  class="color" target="mainFrame">警示结果统计</a></li>
	    <li><a href="manager/manageFrame"  class="color" target="mainFrame">警示规则操作</a></li>
	    <li><a href="Personal/PersonalInfo"  class="color" target="mainFrame">基本信息</a></li>
	    <li><a href="helpindex.html"  class="color" target="mainFrame">帮助</a></li>
		<!-- >li><!--a href="login.php"  class="color" target="_top">注销</a-->
      </ul>	
	</div> 
  </body>
</html>