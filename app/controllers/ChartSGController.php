<?php
use Phalcon\Mvc\Model\Query;
header("Content-type:text/html;charset=utf-8");
use Phalcon\Db\Adapter\Pdo\Oracle as DbAdapter;
class ChartSGController extends ControllerBase
{
	//画图  初始化学年学期
	public function chartAlertAction(){
		session_start(); // Session 启动！！！！
		if(!isset($_SESSION['userid'])||$_SESSION['userqx']<>5){ // 要不要判断 ID 是否存在于系统数据库中？？？---  2015 08 24  中午
			//header("Location:../login.php");
			echo '<br/>';echo '<br/>';
			echo '未登录！请点击此处 <a href="../login.php" target="_top">登录</a>';
			exit();
		}
		try{
			$conn =$this->getDI()->get("db");
			$sql="select *  from XQView";
			$result = $conn->query($sql);
			$this->view->setVar("xqs",$result);
			// 获取 session 中的用户ID，传给页面
			$this->view->setVar("sessionName",$_SESSION['username']);
		}catch(Exception $e) {
	   		   		echo '<font color="#4B0082">数据库连接错误！</font>';
	   	}
	}
	//警示结果统计----学期初始化
	public function xnqAction(){
		//$x=$this->request->get("xy");
		try{
			$conn =$this->getDI()->get("db");
			$sql1="select *  from XQView";
			$xyresult= $conn->query($sql1);
			echo '<option value=\'00\'>全学期统计</option>';
			while($usr=$xyresult->fetch()){
				echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
			}
		}catch(Exception $e) {
	   		   		echo '<font color="#4B0082">数据库连接错误！</font>';
	   	}
	}
	//警示结果统计----学院初始化
	public function collegeAction(){
		//$x=$this->request->get("xy");
		try{
			$conn =$this->getDI()->get("db");
			$sql1="select distinct(xy) from QUERYVIEW order by xy";
			$xyresult= $conn->query($sql1);
			echo '<option value=\'00\'>未选择学院</option>';
			while($usr=$xyresult->fetch()){
				echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
			}
		}catch(Exception $e) {
	   		   		echo '<font color="#4B0082">数据库连接错误！</font>';
	   	}

	}

	//警示结果统计----专业初始化
	public function majorAction(){
		$x=$this->request->get("xy");
		try{
			$conn =$this->getDI()->get("db");
			$sql="select distinct(zymc) from QUERYVIEW where xy='" . $x ."' order by zymc";
			$result = $conn->query($sql);
			echo '<option value=\'00\'>未选择专业</option>';
			while($usr=$result->fetch()){
				echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
			}
		}catch(Exception $e) {
	   		   		echo '<font color="#4B0082">数据库连接错误！</font>';
	   	}

	}

	//警示结果统计----班级初始化
	public function gradeAction(){
		$x=$this->request->get("zy");
		try{
			$conn =$this->getDI()->get("db");
			$sql="select distinct(xzb) from QUERYVIEW where zymc='" . $x ."'order by xzb";
			$result = $conn->query($sql);
			echo '<option value=\'00\'>未选择班级</option>';
			while($usr=$result->fetch()){
				echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
			}
		}catch(Exception $e) {
				echo '<font color="#4B0082">数据库连接错误！</font>';
		}

	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////          画图   柱状图  警示结果统计                                                                ////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  最初     模板
	public function JSchartAction(){
		$str=$this->request->get("mChart");
		try{
			$conn =$this->getDI()->get("db");
			$sql="select XY,IJS,IIJS,IIIJS  from XUEXIAO where XY is not null order by xy ";
			$result = $conn->query($sql);
			$i=0;
			$XY= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			while($usr=$result->fetch()){
				$XY[$i]=$usr[0];
				$IJS[$i]=$usr[1];
				$IIJS[$i]=$usr[2];
				$IIIJS[$i]=$usr[3];
				$i++;
			}
		}catch(Exception $e) {
	   		  echo '<font color="#4B0082">数据库连接错误！</font>';
	   	}
		// 必须先用 jason 进行封装！！！
		$rtn = json_encode($XY);
		$arr1 =json_encode($IJS);
		$arr2 =json_encode($IIJS);
		$arr3 =json_encode($IIIJS);
		echo '<center>';
		//echo $i;
		echo '<caption ><font size=5><b>'.$str.$i.'学业警示统计图</b></font></caption>';
		echo '</center>';
		echo '<br/>';
		echo '<table align="center" width="30%" border="0"  cellpadding="0" cellspacing="0" >';
			
		echo '<tr>';
		echo '<td width=15 height=5></td> <td bgcolor="FF6666" width=20 height=5 > </td> <td width=4><pre><font size="4"> I级警示 </font></pre> </td>
				  <td bgcolor="FFCC33" width=20 height=5>  </td> <td width=4><pre><font size="4"> II级警示 </font></pre> </td>
				  <td bgcolor="99CCFF"   width=20 height=5> </td> <td width=4><pre><font size="4"> III级警示 </font></pre></td>';
		echo '</tr>';
		echo '</table>';
			
		echo '<script src="../js/chart/Chart.js"></script>';
		echo '<center>';
		echo '<div id="tu" style="float:left  width:400% height:300%" >';
		echo '<canvas id="myChart" width=800% height=400%></canvas>';
		echo '</div>';
		echo '</center>';
		//echo '<canvas id="myChart"  width="1000" height="500"></canvas>';
		echo '<script type="text/javascript">';
		// 画柱状图
		echo '
			var ctx = document.getElementById("myChart").getContext("2d");';
		echo '
			var data = {
    		labels :  '.$rtn.',
    		datasets : [
    		{
    			fillColor : "rgba(255,10,10,0.5)",
    			strokeColor : "rgba(255,0,0,0.6)",
    			data :  '.$arr1.',
    		},
    					{
    			fillColor : "rgba( 255, 165, 0,0.5)",
    			strokeColor : "rgba( 255, 165, 0,0.8)",
    			data :  '.$arr2.',
    		},
    		{
    			fillColor : "rgba(151,187,255,0.75)",
    			strokeColor : "rgba(151,187,205,0.9)",
    			data : '.$arr3.',
    		}
    		]
    	  }
  
    	var defaults = {
     
	    scaleOverlay : false,   					//Boolean - If we show the scale above the chart data
	    scaleOverride : false,						//Boolean - If we want to override with a hard coded scale
	    scaleSteps : null,							//Number - The number of steps in a hard coded scale
	    scaleStepWidth : 20,						//Number - The value jump in the hard coded scale
	    scaleStartValue : null,						// Y 轴的起始值
	    scaleLineColor : "rgba(151,187,255,1)",  	// Y/X轴的颜色
	    scaleLineWidth : 2,  						// X,Y轴的宽度
	    scaleShowLabels : true, 					// 刻度是否显示标签, 即Y轴上是否显示文字
	    scaleLabel : "<%=value%>", 					// Y轴上的刻度,即文字
	    scaleFontSize : 15, 			 			// 文字大小
	    scaleFontStyle : "bold",					// 文字样式
	    scaleFontColor : "#000000",					// 文字颜色
	    scaleFontFamily : "'.'\''.'黑体'.'\''.'",
	    scaleShowGridLines : true,  				// 是否显示网格
	    scaleGridLineColor : "rgba(151,187,255,.35)",// 网格颜色
	    scaleGridLineWidth : 1,						// 网格宽度
	    bezierCurve : false, 						// 是否使用贝塞尔曲线, 即:线条是否弯曲
	    pointDot : true, 							// 是否显示点数
	    pointDotRadius : 8, 						// 圆点的大小
	    pointDotStrokeWidth : 2,					// 圆点的笔触宽度, 即:圆点外层白色大小
	    datasetStroke : true,						// 数据集行程
	    datasetStrokeWidth : 2,  					// 线条的宽度, 即:数据集
	    datasetFill : false,    					// 是否填充数据集
	    animation : true,  							// 是否执行动画
	    animationSteps : 60, 						// 动画的时间
	    animationEasing : "easeOutQuart",     		// 动画的特效
	    onAnimationComplete : null   				// 动画完成时的执行函数

	};
    ';
		echo 'ctx.canvas.width  = window.innerWidth/1.1;
			ctx.canvas.height = window.innerHeight/1.3;';
		echo 'var myNewChart = new Chart(ctx).Bar(data,defaults);';
		echo 'window.onresize=DrawbtnClick;'; //检测窗口大小变化
		echo '</script>';
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////   不带学期的                图表统计                                        //////////////////////////////////////////

	//不带学期      学校图表统计
	public function xxChartAction(){
		$str=$this->request->get("xx");
		try{
			$conn =$this->getDI()->get("db");
		//查询学年期是否为空
			$sql1="select count(*)  from XQView";
			$result1 = $conn->query($sql1);
			$num=0;
			while($usr=$result1->fetch()){
					
				$num=$usr[0];
			}
			if($num==0){
				echo '<br/><br/><br/>';
				echo '<font color="#4B0082">&nbsp&nbsp后台数据正在更新，请耐心等待....................</font>';
			}
			else{
				$sql="select XY,IJS,IIJS,IIIJS  from XUEXIAO where XY is not null order by xy";
				$result = $conn->query($sql);
				$i=0;
				$XY= Array();
				$IJS= Array();
				$IIJS= Array();
				$IIIJS= Array();
				while($usr=$result->fetch()){
					$XY[$i]=$usr[0];
					$IJS[$i]=$usr[1];
					$IIJS[$i]=$usr[2];
					$IIIJS[$i]=$usr[3];
					$i++;
				}
				// 必须先用 jason 进行封装！！！
				$rtn = json_encode($XY);
				$arr1 =json_encode($IJS);
				$arr2 =json_encode($IIJS);
				$arr3 =json_encode($IIIJS);
				
				// 饼状图用的参数
				$BIJS=0;$BIIJS=0;$BIIIJS=0;
				for ($i=0;$i<sizeof($IJS);$i++){
					$BIJS+=$IJS[$i];
				}
				for ($i=0;$i<count($IIJS);$i++){
					$BIIJS+=$IIJS[$i];
				}
				for ($i=0;$i<sizeof($IIIJS);$i++){
					$BIIIJS+=$IIIJS[$i];
				}
				$BIJS=json_encode($BIJS);
				$BIIJS=json_encode($BIIJS);
				$BIIIJS=json_encode($BIIIJS);
			
			///////////////////////////////////////////
			echo '<center>';
			echo '<br/>';
			echo '<caption ><font size=5><b>全校学业警示统计图</b></font></caption>';
			echo '</center>';
			echo '<br/>';
			echo '<table align="center" width="30%" border="0"  cellpadding="0" cellspacing="0" >';
				
			echo '<tr>';
			echo '<td width=15 height=5></td> <td bgcolor="FF6666" width=20 height=5 > </td> <td width=4><pre><font size="4"> I级警示 </font></pre> </td>
					  <td bgcolor="FFCC33" width=20 height=5>  </td> <td width=4><pre><font size="4"> II级警示 </font></pre> </td>
					  <td bgcolor="99CCFF"   width=20 height=5> </td> <td width=4><pre><font size="4"> III级警示 </font></pre></td>';
			echo '</tr>';
			echo '</table>';
				
			echo '<script src="../js/chart/Chart.js"></script>';
			echo '<center>';
			echo '<div id="tu" style="float:left  width:400% height:300%" >';
			echo '<canvas id="myChart" width=800% height=400%></canvas>';
			echo '</div>';
			echo '</center>';
			//echo '<canvas id="myChart"  width="1000" height="500"></canvas>';
			echo '<script type="text/javascript">';
			// 1 表示柱状图，2 表示 圆饼图
		if ($str=="z")
			{
			// 画柱状图
			echo '
				var ctx = document.getElementById("myChart").getContext("2d");';
			echo '
				var data = {
	    		labels :  '.$rtn.',
	    		datasets : [
	    		{
	    			fillColor : "rgba(255,10,10,0.5)",
	    			strokeColor : "rgba(255,0,0,0.6)",
	    			data :  '.$arr1.',

	    		},
	    		{
	    			fillColor : "rgba( 255, 165, 0,0.5)",
	    			strokeColor : "rgba( 255, 165, 0,0.8)",
	    			data :  '.$arr2.',
	    		},
	    		{
	    			fillColor : "rgba(151,187,255,0.75)",
	    			strokeColor : "rgba(151,187,205,0.9)",
	    			data : '.$arr3.',
	    		}
	    		]
	    	  }
	    								
	    	var defaults = {
		    scaleOverlay : false,
		    scaleOverride : false,
		    scaleSteps : null,
		    scaleStepWidth : 20,
		    scaleStartValue : null,
		    scaleLineColor : "rgba(151,187,255,1)",
		    scaleLineWidth : 2,
		    scaleShowLabels : true,                     // 刻度是否显示标签, 即X轴上是否显示文字
		    scaleLabel : "<%=value%>人",                 //y轴标签
		    scaleFontSize : 15, 			 			// 文字大小
		    scaleFontStyle : "bold",					// 文字样式
		    scaleFontColor : "#000000",					// 文字颜色
		    scaleFontFamily : "'.'\''.'黑体'.'\''.'",
		    scaleShowGridLines : true,  				// 是否显示网格
		    scaleGridLineColor : "rgba(151,187,255,.35)",// 网格颜色
		    scaleGridLineWidth : 1,						// 网格宽度
		    bezierCurve : false, 						// 是否使用贝塞尔曲线, 即:线条是否弯曲
		    pointDot : true, 							// 是否显示点数
		    pointDotRadius : 8, 						// 圆点的大小
		    pointDotStrokeWidth : 2,					// 圆点的笔触宽度, 即:圆点外层白色大小
		    datasetStroke : true,						// 数据集行程
		    datasetStrokeWidth : 2,  					// 线条的宽度, 即:数据集
		    datasetFill : false,    					// 是否填充数据集
		    animation : true,  							// 是否执行动画
		    animationSteps : 60, 						// 动画的时间
		    animationEasing : "easeOutQuart",     		// 动画的特效
		    onAnimationComplete : null   				// 动画完成时的执行函数
	 		};
			';
			echo 'ctx.canvas.width  = window.innerWidth/1.1;
				  ctx.canvas.height = window.innerHeight/1.3;';
			echo 'var myNewChart = new Chart(ctx).Bar(data,defaults);';
			echo 'window.onresize=DrawOldbtnClick;'; //检测窗口大小变化
			}
			// 画总的 饼状 图
		else
			{//echo $BIJS;
					echo ' var ctx = document.getElementById("myChart").getContext("2d");
				    var data = [
		        	{
		        		value : '.$BIJS.',
		        		color: "#FF6666"
		        	},
		        	{
		        		value : '.$BIIJS.',
		        		color: "#FFCC33"
		        	},
		        	{
		        		value : '.$BIIIJS.',
		        		color: "#99CCFF"
		        	}
		         ]
				';
				echo 'ctx.canvas.width  = window.innerWidth;
					  ctx.canvas.height = window.innerHeight/1.3;';
				echo 'var myNewChart = new Chart(ctx).PolarArea(data); ';  //极地区域图
				echo 'window.onresize=BDrawOldbtnClick;'; //检测窗口大小变化
			}
			echo '</script>';
		}	 //echo $BIJS;
	}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
	}
}

	//不带学期      学院  图表统计
	public function xyChartAction(){
		$str1=$this->request->get("xy");
		$str=substr($str1,0,1);
		$x=substr($str1,1);
		try{
		$conn =$this->getDI()->get("db");
		//$sql="select XH,XM,ZY,BJ,JSJB,JSYY,HDXF,NJ from MY_XYJS where xy='" . $x ."'";
		$sql="select ZY,IJS,IIJS,IIIJS from XUEYUAN where xy like '%" . $x ."%' order by xy";
		$result = $conn->query($sql);
		$i=0;
		$ZY= Array();
		$IJS= Array();
		$IIJS= Array();
		$IIIJS= Array();
		while($usr=$result->fetch()){
			$ZY[$i]=$usr[0];
			$IJS[$i]=$usr[1];
			$IIJS[$i]=$usr[2];
			$IIIJS[$i]=$usr[3];
			$i++;
		}
		//判断专业是否有警示信息
		$j=0;
		while($j<$i){
			if($IJS[$j]==0 && $IIJS[$j]==0 && $IIIJS[$j]==0){
				$j++;
			}
			else
				break;
		}
		if($j==$i){
			echo '<br/>';
			echo '<font color="#4B0082">'. $x.'  没有警示信息！</font>';
		}else{
			//判断专业的个数
			while($i<6){
			
				$ZY[$i]="0";
				$IJS[$i]=0;
				$IIJS[$i]=0;
				$IIIJS[$i]=0;
				$i++;
			}
			//echo $i;
			// 必须先用 jason 进行封装！！！
			$rtn = json_encode($ZY);
			$arr1 =json_encode($IJS);
			$arr2 =json_encode($IIJS);
			$arr3 =json_encode($IIIJS);
			// 饼状图用的参数
			$BIJS=0;$BIIJS=0;$BIIIJS=0;
			for ($i=0;$i<sizeof($IJS);$i++){
				$BIJS+=$IJS[$i];
			}
			for ($i=0;$i<count($IIJS);$i++){
				$BIIJS+=$IIJS[$i];
			}
			for ($i=0;$i<sizeof($IIIJS);$i++){
				$BIIIJS+=$IIIJS[$i];
			}
			$BIJS=json_encode($BIJS);
			$BIIJS=json_encode($BIIJS);
			$BIIIJS=json_encode($BIIIJS);
			echo '<center>';echo '<br/>';
			echo '<caption ><font size=5><b>'.$x.' 学业警示统计图</b></font></caption>';
			echo '</center>';
			echo '<br/>';
			echo '<table align="center" width="30%" border="0"  cellpadding="0" cellspacing="0" >';
				
			echo '<tr>';
			echo '<td width=15 height=5></td> <td bgcolor="FF6666" width=20 height=5 > </td> <td width=4><pre><font size="4"> I级警示 </font></pre> </td>
				  <td bgcolor="FFCC33" width=20 height=5>  </td> <td width=4><pre><font size="4"> II级警示 </font></pre> </td>
				  <td bgcolor="99CCFF"   width=20 height=5> </td> <td width=4><pre><font size="4"> III级警示 </font></pre></td>';
			echo '</tr>';
			echo '</table>';
				
			echo '<script src="../js/chart/Chart.js"></script>';
			echo '<center>';
			echo '<div id="tu" style="float:left  width:400% height:300%" >';
			echo '<canvas id="myChart" width=800% height=400%></canvas>';
			echo '</div>';
			echo '</center>';
			//echo '<canvas id="myChart"  width="1000" height="500"></canvas>';
			echo '<script type="text/javascript">';
			if ($str=="z") // 全校用 1 表示 柱状图，由于 1+字符串出错，所以用 z 表示柱状，b 表示饼状
			{// 画柱状图
				echo '
					var ctx = document.getElementById("myChart").getContext("2d");';
				echo '
					var data = {
		    		labels :  '.$rtn.',
		    		datasets : [
		    		{
		    			fillColor : "rgba(255,10,10,0.5)",
		    			strokeColor : "rgba(255,0,0,0.6)",
	        			data :  '.$arr1.',
			
		    		},
		    		{
		    			fillColor : "rgba( 255, 165, 0,0.5)",
		    			strokeColor : "rgba( 255, 165, 0,0.8)",
		    			data :  '.$arr2.',
		    		},
		    		{
		    			fillColor : "rgba(151,187,255,0.75)",
		    			strokeColor : "rgba(151,187,205,0.9)",
		    			data : '.$arr3.',
		    		}
		    		]
		    	  }
			
		    	var defaults = {
			    scaleOverlay : false,
			    scaleOverride : false,
			    scaleSteps : null,
			    scaleStepWidth : 20,
			    scaleStartValue : null,   // Y 轴的起始值
			    scaleLineColor : "rgba(151,187,255,1)",  // Y/X轴的颜色
			    scaleLineWidth : 2,                      // X,Y轴的宽度
			    scaleShowLabels : true,                  // 刻度是否显示标签, 即X/Y轴上是否显示文字
			    scaleLabel : "<%=value%>人",
			    scaleFontSize : 15, 			 			// 文字大小
			    scaleFontStyle : "bold",					// 文字样式
			    scaleFontColor : "#000000",					// 文字颜色
			    scaleFontFamily : "'.'\''.'黑体'.'\''.'",
			    scaleShowGridLines : true,  				// 是否显示网格
			    scaleGridLineColor : "rgba(151,187,255,.35)",// 网格颜色
			    scaleGridLineWidth : 1,						// 网格宽度
			    bezierCurve : false, 						// 是否使用贝塞尔曲线, 即:线条是否弯曲
			    pointDot : true, 							// 是否显示点数
			    pointDotRadius : 8, 						// 圆点的大小
			    pointDotStrokeWidth : 2,					// 圆点的笔触宽度, 即:圆点外层白色大小
			    datasetStroke : true,						// 数据集行程
			    datasetStrokeWidth : 2,  					// 线条的宽度, 即:数据集
			    datasetFill : false,    					// 是否填充数据集
			    animation : true,  							// 是否执行动画
			    animationSteps : 60, 						// 动画的时间
			    animationEasing : "easeOutQuart",     		// 动画的特效
			    onAnimationComplete : null   				// 动画完成时的执行函数
			};
			';
				echo 'ctx.canvas.width  = window.innerWidth/1.1;
					ctx.canvas.height = window.innerHeight/1.3;';
				echo 'var myNewChart = new Chart(ctx).Bar(data,defaults);';
				echo 'window.onresize=DrawOldbtnClick;'; //检测窗口大小变化
			}else
			{
				//echo $BIJS;
				echo ' var ctx = document.getElementById("myChart").getContext("2d");
				    var data = [
		        	{
		        		value : '.$BIJS.',
		        		color: "#FF6666"
		        	},
		        	{
		        		value : '.$BIIJS.',
		        		color: "#FFCC33"
		        	},
		        	{
		        		value : '.$BIIIJS.',
		        		color: "#99CCFF"
		        	}
		         ]
				';
				echo 'ctx.canvas.width  = window.innerWidth;
					  ctx.canvas.height = window.innerHeight/1.3;';
				echo 'var myNewChart = new Chart(ctx).PolarArea(data); ';  //极地区域图
				echo 'window.onresize=BDrawOldbtnClick;'; //检测窗口大小变化
			}
			echo '</script>';	
		}
	}catch(Exception $e) {
	   	echo '<font color="#4B0082">数据库连接错误！</font>';
		}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//不带学期      专业    图表统计，饼图状图可以一起画出来, ----------------有的专业 班级 特别多！eg：理学院 财务管理！！！
	public function zyChartAction(){
		$str1=$this->request->get("zy");
		$str=substr($str1,0,1);
		$x=substr($str1,1);
		try{
		$conn =$this->getDI()->get("db");
		$sql="select BJ,IJS,IIJS,IIIJS  from ZHUANYE where zy = '" . $x ."' order by bj";
		$result = $conn->query($sql);
		$i=0;
		$BJ= Array();
		$IJS= Array();
		$IIJS= Array();
		$IIIJS= Array();
		while($usr=$result->fetch()){
			$BJ[$i]=$usr[0];
			$IJS[$i]=$usr[1];
			$IIJS[$i]=$usr[2];
			$IIIJS[$i]=$usr[3];
			$i++;
		}
		
		//判断各班是否有警示信息
		$j=0;
		while($j<$i){
			if($IJS[$j]==0 && $IIJS[$j]==0 && $IIIJS[$j]==0){
				$j++;
			}
			else 
				break;		
		}
		if($j==$i){
			echo '<br/>';
			echo '<font color="#4B0082">'. $x.'(专业)  没有警示信息！</font>';
		}else{
			
			//判断班级的个数
			while($i<6){
			
				$BJ[$i]="0";
				$IJS[$i]=0;
				$IIJS[$i]=0;
				$IIIJS[$i]=0;
				$i++;
			
			}
			// 必须先用 jason 进行封装！！！
			$rtn = json_encode($BJ);
			$arr1 =json_encode($IJS);
			$arr2 =json_encode($IIJS);
			$arr3 =json_encode($IIIJS);
			// 饼状图用的参数
			$BIJS=0;$BIIJS=0;$BIIIJS=0;
			for ($i=0;$i<sizeof($IJS);$i++){
				$BIJS+=$IJS[$i];
			}
			for ($i=0;$i<count($IIJS);$i++){
				$BIIJS+=$IIJS[$i];
			}
			for ($i=0;$i<sizeof($IIIJS);$i++){
				$BIIIJS+=$IIIJS[$i];
			}
			$BIJS=json_encode($BIJS);
			$BIIJS=json_encode($BIIJS);
			$BIIIJS=json_encode($BIIIJS);
			
			echo '<center>';echo '<br/>';
			echo '<caption ><font size=5><b>'.$x.'(专业)  学业警示统计图</b></font></caption>';
			echo '</center>';
			echo '<br/>';
			echo '<table align="center" width="30%" border="0"  cellpadding="0" cellspacing="0" >';
				
			echo '<tr>';
			echo '<td width=15 height=5></td> <td bgcolor="FF6666" width=20 height=5 > </td> <td width=4><pre><font size="4"> I级警示 </font></pre> </td>
				  <td bgcolor="FFCC33" width=20 height=5>  </td> <td width=4><pre><font size="4"> II级警示 </font></pre> </td>
				  <td bgcolor="99CCFF"   width=20 height=5> </td> <td width=4><pre><font size="4"> III级警示 </font></pre></td>';
			echo '</tr>';
			echo '</table>';
				
			echo '<script src="../js/chart/Chart.js"></script>';
			echo '<center>';
			echo '<div id="tu" style="float:left  width:400% height:300%" >';
			echo '<canvas id="myChart" width=800% height=400%></canvas>';
			echo '</div>';
			echo '</center>';
			//echo '<canvas id="myChart"  width="1000" height="500"></canvas>';
			echo '<script type="text/javascript">';
			if ($str=="z") // 全校用 1 表示 柱状图，由于 1+字符串出错，所以用 z 表示柱状，b 表示饼状
			{		// 画柱状图
			echo '
					var ctx = document.getElementById("myChart").getContext("2d");';
			echo '
					var data = {
		    		labels :  '.$rtn.',
		    		datasets : [
		    		{
		    			fillColor : "rgba(255,10,10,0.5)",
		    			strokeColor : "rgba(255,0,0,0.6)",
		    			data :  '.$arr1.',
			
		    		},
		    		{
		    			fillColor : "rgba( 255, 165, 0,0.5)",
		    			strokeColor : "rgba( 255, 165, 0,0.8)",
		    			data :  '.$arr2.',
		    		},
		    		{
		    			fillColor : "rgba(151,187,255,0.75)",
		    			strokeColor : "rgba(151,187,205,0.9)",
		    			data : '.$arr3.',
		    		}
		    		]
		    	  }
			
		    	var defaults = {
			
			    scaleOverlay : false,
			    scaleOverride : false,
			    scaleSteps : null,
			    scaleStepWidth : 20,
			    scaleStartValue : null,
			    scaleLineColor : "rgba(151,187,255,1)",
			    scaleLineWidth : 2,
			    scaleShowLabels : true,
			    scaleLabel : "<%=value%>人",
			    scaleFontSize : 15, 			 			// 文字大小
			    scaleFontStyle : "bold",					// 文字样式
			    scaleFontColor : "#000000",					// 文字颜色
			    scaleFontFamily : "'.'\''.'黑体'.'\''.'",
			    scaleShowGridLines : true,  				// 是否显示网格
			    scaleGridLineColor : "rgba(151,187,255,.35)",// 网格颜色
			    scaleGridLineWidth : 1,						// 网格宽度
			    bezierCurve : false, 						// 是否使用贝塞尔曲线, 即:线条是否弯曲
			    pointDot : true, 							// 是否显示点数
			    pointDotRadius : 8, 						// 圆点的大小
			    pointDotStrokeWidth : 2,					// 圆点的笔触宽度, 即:圆点外层白色大小
			    datasetStroke : true,						// 数据集行程
			    datasetStrokeWidth : 2,  					// 线条的宽度, 即:数据集
			    datasetFill : false,    					// 是否填充数据集
			    animation : true,  							// 是否执行动画
			    animationSteps : 60, 						// 动画的时间
			    animationEasing : "easeOutQuart",     		// 动画的特效
			    onAnimationComplete : null   				// 动画完成时的执行函数
			};
			';
			echo 'ctx.canvas.width  = window.innerWidth/1.1;
					ctx.canvas.height = window.innerHeight/1.3;';
			echo 'var myNewChart = new Chart(ctx).Bar(data,defaults);';
			echo 'window.onresize=DrawOldbtnClick;'; //检测窗口大小变化
			}else
			{
				//echo $BIJS;
				echo ' var ctx = document.getElementById("myChart").getContext("2d");
				    var data = [
			
		        	{
		        		value : '.$BIJS.',
		        		color: "#FF6666"
		        	},
		        	{
		        		value : '.$BIIJS.',
		        		color: "#FFCC33"
		        	},
		        	{
		        		value : '.$BIIIJS.',
		        		color: "#99CCFF"
		        	}
		         ]
				';
			
				echo 'ctx.canvas.width  = window.innerWidth;
					  ctx.canvas.height = window.innerHeight/1.3;';
				echo 'var myNewChart = new Chart(ctx).PolarArea(data); ';  //极地区域图
				echo 'window.onresize=BDrawOldbtnClick;'; //检测窗口大小变化
			}
			echo '</script>';
		  }
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}		
	}
	//不带学期      班级  只要 图表统计？柱状图也可以要？先不画。。。
	
	public function bjChartAction(){
		$str1=$this->request->get("bj");
		$str=substr($str1,0,1);
		$x=substr($str1,1);
        try{
		$conn =$this->getDI()->get("db");
		$sql1="select COUNT(JSJB) from MY_XYJS where JSJB like '%Ⅰ级警示%'  and bj like '%" . $x ."%' ";
		$sql2="select COUNT(JSJB) from MY_XYJS where JSJB like '%Ⅱ级警示%' and bj like '%" . $x ."%' ";
		$sql3="select COUNT(JSJB) from MY_XYJS where JSJB like '%Ⅲ级警示%'  and bj like '%" . $x ."%' ";
		$result1 = $conn->query($sql1);
		$result2 = $conn->query($sql2);
		$result3 = $conn->query($sql3);
		$IJS= 0;
		$IIJS=0;
		$IIIJS=0;
		while($usr=$result1->fetch()){
			$IJS+=$usr[0];
		}

		while($usr=$result2->fetch()){
			$IIJS+=$usr[0];
		}
		while($usr=$result3->fetch()){
			$IIIJS+=$usr[0];
		}
		//判断该班是否有警示信息
        if($IJS==0 && $IIJS==0 && $IIIJS==0){
		echo '<br/>';
		echo '<font color="#4B0082">'. $x.'班级  没有警示信息！</font>';
		}
		else{
			echo '<center>';echo '<br/>';
			echo '<caption ><font size=5><b>'.$x.'(班级) 学业警示统计图</b></font></caption>';
			echo '</center>';
			echo '<br/>';
			echo '<table align="center" width="30%" border="0"  cellpadding="0" cellspacing="0" >';
				
			echo '<tr>';
			echo '<td width=15 height=5></td> <td bgcolor="FF6666" width=20 height=5 > </td> <td width=4><pre><font size="4"> I级警示 </font></pre> </td>
					  <td bgcolor="FFCC33" width=20 height=5>  </td> <td width=4><pre><font size="4"> II级警示 </font></pre> </td>
					  <td bgcolor="99CCFF"   width=20 height=5> </td> <td width=4><pre><font size="4"> III级警示 </font></pre></td>';
			echo '</tr>';
			echo '</table>';
				
			echo '<script src="../js/chart/Chart.js"></script>';
			echo '<center>';
			echo '<div id="tu" style="float:left  width:400% height:300%" >';
			echo '<canvas id="myChart" width=800% height=400%></canvas>';
			echo '</div>';
			echo '</center>';
			echo '<script type="text/javascript">';
			//极地区域图
			echo ' var ctx = document.getElementById("myChart").getContext("2d");
			var data = [
	        	{
	        		value : '.$IJS.',
	        		color: "#FF6666"
	        	},
	        	{
	        		value : '.$IIJS.',
	        		color: "#FFCC33"
	        	},
	        	{
	        		value : '.$IIIJS.',
	        		color: "#99CCFF"
	        	}
	         ]
			';
	
			echo 'ctx.canvas.width  = window.innerWidth;
				  ctx.canvas.height = window.innerHeight/1.3;';
			echo 'var myNewChart = new Chart(ctx).PolarArea(data); ';  //极地区域图
			echo 'window.onresize=DrawOldbtnClick;'; //检测窗口大小变化
			echo '</script>';
		 }
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
  }
	///////////////////////////////////////////带学期     图表统计//////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////
	//带学期      学校图表统计
	public function xnqxxChartAction(){
		$str1=$this->request->get("xnq");
		$str=substr($str1,0,1); // 获取 z 画图标识符
		$x=substr($str1,1);
	try{
		$conn =$this->getDI()->get("db");
		$sql="select  XY,IJS,IIJS,IIIJS  from XNQXUEXIAO where xnq='" . $x ."' order by xy";
		$result = $conn->query($sql);
		$i=0;
		$XY= Array();
		$IJS= Array();
		$IIJS= Array();
		$IIIJS= Array();
		while($usr=$result->fetch()){
			$XY[$i]=$usr[0];
			$IJS[$i]=$usr[1];
			$IIJS[$i]=$usr[2];
			$IIIJS[$i]=$usr[3];
			$i++;
		}
		//判断学院是否有警示信息
		$j=0;
		while($j<$i){
			if($IJS[$j]==0 && $IIJS[$j]==0 && $IIIJS[$j]==0){
				$j++;
			}
			else
				break;
		}
		if($j==$i){
			echo '<br/>';
			echo '<font color="#4B0082">本学期全校 没有警示信息！</font>';
		}else{
			// 必须先用 jason 进行封装！！！
			$rtn = json_encode($XY);
			$arr1 =json_encode($IJS);
			$arr2 =json_encode($IIJS);
			$arr3 =json_encode($IIIJS);
			// 饼状图用的参数
			$BIJS=0;$BIIJS=0;$BIIIJS=0;
			for ($i=0;$i<sizeof($IJS);$i++){
				$BIJS+=$IJS[$i];
			}
			for ($i=0;$i<count($IIJS);$i++){
				$BIIJS+=$IIJS[$i];
			}
			for ($i=0;$i<sizeof($IIIJS);$i++){
				$BIIIJS+=$IIIJS[$i];
			}
			$BIJS=json_encode($BIJS);
			$BIIJS=json_encode($BIIJS);
			$BIIIJS=json_encode($BIIIJS);
			echo '<center>';echo '<br/>';
			echo '<caption ><font size=5><b>'.$x.'(学期)全校   学业警示统计图</b></font></caption>';
			echo '</center>';
			echo '<br/>';
			echo '<table align="center" width="30%" border="0"  cellpadding="0" cellspacing="0" >';
				
			echo '<tr>';
			echo '<td width=15 height=5></td> <td bgcolor="FF6666" width=20 height=5 > </td> <td width=4><pre><font size="4"> I级警示 </font></pre> </td>
					  <td bgcolor="FFCC33" width=20 height=5>  </td> <td width=4><pre><font size="4"> II级警示 </font></pre> </td>
					  <td bgcolor="99CCFF"   width=20 height=5> </td> <td width=4><pre><font size="4"> III级警示 </font></pre></td>';
			echo '</tr>';
			echo '</table>';
				
			echo '<script src="../js/chart/Chart.js"></script>';
			echo '<center>';
			echo '<div id="tu" style="float:left  width:400% height:300%" >';
			echo '<canvas id="myChart" width=800% height=400%></canvas>';
			echo '</div>';
			echo '</center>';
			//echo '<canvas id="myChart"  width="1000" height="500"></canvas>';
			echo '<script type="text/javascript">';
			if ($str=="z") // 全校用 1 表示 柱状图，由于 1+字符串出错，所以用 z 表示柱状，b 表示饼状
			{			// 画柱状图
						echo '
							var ctx = document.getElementById("myChart").getContext("2d");';
						echo '
							var data = {
				    		labels :  '.$rtn.',
				    		datasets : [
				    		{
				    			fillColor : "rgba(255,10,10,0.5)",
				    			strokeColor : "rgba(255,0,0,0.6)",
				    			data :  '.$arr1.',
				
				    		},
				    		{
				    			fillColor : "rgba( 255, 165, 0,0.5)",
				    			strokeColor : "rgba( 255, 165, 0,0.8)",
				    			data :  '.$arr2.',
				    		},
				    		{
				    			fillColor : "rgba(151,187,255,0.75)",
				    			strokeColor : "rgba(151,187,205,0.9)",
				    			data : '.$arr3.',
				    		}
				    		]
				    	  }
				
				    	var defaults = {
				
					    scaleOverlay : false,
					    scaleOverride : false,
					    scaleSteps : null,
					    scaleStepWidth : 20,
					    scaleStartValue : null,
					    scaleLineColor : "rgba(151,187,255,1)",
					    scaleLineWidth : 2,
					    scaleShowLabels : true,
					    scaleLabel : "<%=value%>人",
					    scaleFontSize : 15, 			 			// 文字大小
					    scaleFontStyle : "bold",					// 文字样式
					    scaleFontColor : "#000000",					// 文字颜色
					    scaleFontFamily : "'.'\''.'黑体'.'\''.'",
					    scaleShowGridLines : true,  				// 是否显示网格
					    scaleGridLineColor : "rgba(151,187,255,.35)",// 网格颜色
					    scaleGridLineWidth : 1,						// 网格宽度
					    bezierCurve : false, 						// 是否使用贝塞尔曲线, 即:线条是否弯曲
					    pointDot : true, 							// 是否显示点数
					    pointDotRadius : 8, 						// 圆点的大小
					    pointDotStrokeWidth : 2,					// 圆点的笔触宽度, 即:圆点外层白色大小
					    datasetStroke : true,						// 数据集行程
					    datasetStrokeWidth : 2,  					// 线条的宽度, 即:数据集
					    datasetFill : false,    					// 是否填充数据集
					    animation : true,  							// 是否执行动画
					    animationSteps : 60, 						// 动画的时间
					    animationEasing : "easeOutQuart",     		// 动画的特效
					    onAnimationComplete : null   				// 动画完成时的执行函数
				
					};
					';
				
						echo 'ctx.canvas.width  = window.innerWidth/1.1;
							ctx.canvas.height = window.innerHeight/1.3;';
						echo 'var myNewChart = new Chart(ctx).Bar(data,defaults);';
						echo 'window.onresize=DrawOldbtnClick;'; //检测窗口大小变化
			}else
			{
				//echo $BIJS;
				echo ' var ctx = document.getElementById("myChart").getContext("2d");
					    var data = [
			
			        	{
			        		value : '.$BIJS.',
			        		color: "#FF6666"
			        	},
			        	{
			        		value : '.$BIIJS.',
			        		color: "#FFCC33"
			        	},
			        	{
			        		value : '.$BIIIJS.',
			        		color: "#99CCFF"
			        	}
			         ]
					';
			
				echo 'ctx.canvas.width  = window.innerWidth;
						  ctx.canvas.height = window.innerHeight/1.3;';
				echo 'var myNewChart = new Chart(ctx).PolarArea(data); ';  //极地区域图
				echo 'window.onresize=BDrawOldbtnClick;'; //检测窗口大小变化
			}
			echo '</script>';
		 }
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}

	//带学期      学院  图表统计
	public function xnqxyChartAction(){
		$str1=$this->request->get("xnq");
		$str=substr($str1,0,1);
		$x=substr($str1,1,11);
		$y=substr($str1,12);
	try{
		$conn =$this->getDI()->get("db");
		$sql="select  ZY,IJS,IIJS,IIIJS  from XNQXUEYUAN where xnq='" .$x."' and xy like '%" . $y ."%'order by zy";
		$result = $conn->query($sql);
		$i=0;
		$ZY= Array();
		$IJS= Array();
		$IIJS= Array();
		$IIIJS= Array();
		while($usr=$result->fetch()){
			$ZY[$i]=$usr[0];
			$IJS[$i]=$usr[1];
			$IIJS[$i]=$usr[2];
			$IIIJS[$i]=$usr[3];
			$i++;
		}
		//判断各专业是否有警示信息
		$j=0;
		while($j<$i){
			if($IJS[$j]==0 && $IIJS[$j]==0 && $IIIJS[$j]==0){
				$j++;
			}
			else
				break;
		}
		if($j==$i){
			echo '<br/>';
			echo '<font color="#4B0082">'.$x.'学期'.$y.'  没有警示信息！</font>';
		}
		else{
			//判断专业的个数
			while($i<6){
			
				$ZY[$i]="0";
				$IJS[$i]=0;
				$IIJS[$i]=0;
				$IIIJS[$i]=0;
				$i++;
			
			}
			// 必须先用 jason 进行封装！！！
			$rtn = json_encode($ZY);
			$arr1 =json_encode($IJS);
			$arr2 =json_encode($IIJS);
			$arr3 =json_encode($IIIJS);
			// 饼状图用的参数
			$BIJS=0;$BIIJS=0;$BIIIJS=0;
			for ($i=0;$i<sizeof($IJS);$i++){
				$BIJS+=$IJS[$i];
			}
			for ($i=0;$i<count($IIJS);$i++){
				$BIIJS+=$IIJS[$i];
			}
			for ($i=0;$i<sizeof($IIIJS);$i++){
				$BIIIJS+=$IIIJS[$i];
			}
			$BIJS=json_encode($BIJS);
			$BIIJS=json_encode($BIIJS);
			$BIIIJS=json_encode($BIIIJS);
			echo '<center>';echo '<br/>';
			echo '<caption ><font size=5><b>'.$x.'学期  '.$y.'  学业警示统计图</b></font></caption>';
			echo '</center>';
			echo '<br/>';
			echo '<table align="center" width="30%" border="0"  cellpadding="0" cellspacing="0" >';
				
			echo '<tr>';
			echo '<td width=15 height=5></td> <td bgcolor="FF6666" width=20 height=5 > </td> <td width=4><pre><font size="4"> I级警示 </font></pre> </td>
					  <td bgcolor="FFCC33" width=20 height=5>  </td> <td width=4><pre><font size="4"> II级警示 </font></pre> </td>
					  <td bgcolor="99CCFF"   width=20 height=5> </td> <td width=4><pre><font size="4"> III级警示 </font></pre></td>';
			echo '</tr>';
			echo '</table>';
				
			echo '<script src="../js/chart/Chart.js"></script>';
			echo '<center>';
			echo '<div id="tu" style="float:left  width:400% height:300%" >';
			echo '<canvas id="myChart" width=800% height=400%></canvas>';
			echo '</div>';
			echo '</center>';
			//echo '<canvas id="myChart"  width="1000" height="500"></canvas>';
			echo '<script type="text/javascript">';
			if ($str=="z") // 全校用 1 表示 柱状图，由于 1+字符串出错，所以用 z 表示柱状，b 表示饼状
			{			// 画柱状图
						echo '
							var ctx = document.getElementById("myChart").getContext("2d");';
						echo '
							var data = {
				    		labels :  '.$rtn.',
				    		datasets : [
				    		{
				    			fillColor : "rgba(255,10,10,0.5)",
				    			strokeColor : "rgba(255,0,0,0.6)",
				    			data :  '.$arr1.',
				
				    		},
				    		{
				    			fillColor : "rgba( 255, 165, 0,0.5)",
				    			strokeColor : "rgba( 255, 165, 0,0.8)",
				    			data :  '.$arr2.',
				    		},
				    		{
				    			fillColor : "rgba(151,187,255,0.75)",
				    			strokeColor : "rgba(151,187,205,0.9)",
				    			data : '.$arr3.',
				    		}
				    		]
				    	  }
				
				    	var defaults = {
				
					    scaleOverlay : false,
					    scaleOverride : false,
					    scaleSteps : null,
					    scaleStepWidth : 20,
					    scaleStartValue : null,
					    scaleLineColor : "rgba(151,187,255,1)",
					    scaleLineWidth : 2,
					    scaleShowLabels : true,
					    scaleLabel : "<%=value%>人",
					    scaleFontSize : 15, 			 			// 文字大小
					    scaleFontStyle : "bold",					// 文字样式
					    scaleFontColor : "#000000",					// 文字颜色
					    scaleFontFamily : "'.'\''.'黑体'.'\''.'",
					    scaleShowGridLines : true,  				// 是否显示网格
					    scaleGridLineColor : "rgba(151,187,255,.35)",// 网格颜色
					    scaleGridLineWidth : 1,						// 网格宽度
					    bezierCurve : false, 						// 是否使用贝塞尔曲线, 即:线条是否弯曲
					    pointDot : true, 							// 是否显示点数
					    pointDotRadius : 8, 						// 圆点的大小
					    pointDotStrokeWidth : 2,					// 圆点的笔触宽度, 即:圆点外层白色大小
					    datasetStroke : true,						// 数据集行程
					    datasetStrokeWidth : 2,  					// 线条的宽度, 即:数据集
					    datasetFill : false,    					// 是否填充数据集
					    animation : true,  							// 是否执行动画
					    animationSteps : 60, 						// 动画的时间
					    animationEasing : "easeOutQuart",     		// 动画的特效
					    onAnimationComplete : null   				// 动画完成时的执行函数
				
					};
					';
				
						echo 'ctx.canvas.width  = window.innerWidth/1.1;
							  ctx.canvas.height = window.innerHeight/1.3;';
						echo 'var myNewChart = new Chart(ctx).Bar(data,defaults);';
						echo 'window.onresize=DrawOldbtnClick;'; //检测窗口大小变化
			}else
			{
				//echo $BIJS;
				echo ' var ctx = document.getElementById("myChart").getContext("2d");
					    var data = [
			
			        	{
			        		value : '.$BIJS.',
			        		color: "#FF6666"
			        	},
			        	{
			        		value : '.$BIIJS.',
			        		color: "#FFCC33"
			        	},
			        	{
			        		value : '.$BIIIJS.',
			        		color: "#99CCFF"
			        	}
			         ]
					';
				echo 'ctx.canvas.width  = window.innerWidth;
						  ctx.canvas.height = window.innerHeight/1.3;';
				echo 'var myNewChart = new Chart(ctx).PolarArea(data); ';  //极地区域图
				echo 'window.onresize=BDrawOldbtnClick;'; //检测窗口大小变化
			}
			echo '</script>';
		}
	 }catch(Exception $e) {
		echo '<font color="#4B0082">数据库连接错误！</font>';
	}
 }

	//带学期      专业    图表统计
	public function xnqzyChartAction(){
		$str1=$this->request->get("xnq");
		$str=substr($str1,0,1);
		$x=substr($str1,1,11);
		$y=substr($str1,12);
 try{
		$conn =$this->getDI()->get("db");
		$sql="select  BJ,IJS,IIJS,IIIJS  from XNQZHUANYE where xnq='" . $x ."' and zy = '" . $y ."'order by bj";
		$result = $conn->query($sql);
		$i=0;
		$BJ= Array();
		$IJS= Array();
		$IIJS= Array();
		$IIIJS= Array();
		while($usr=$result->fetch()){
			$BJ[$i]=$usr[0];
			$IJS[$i]=$usr[1];
			$IIJS[$i]=$usr[2];
			$IIIJS[$i]=$usr[3];
			$i++;
		}
		//判断各班级是否有警示信息
		$j=0;
		while($j<$i){
			if($IJS[$j]==0 && $IIJS[$j]==0 && $IIIJS[$j]==0){
				$j++;
			}
			else
				break;
		}
		if($j==$i){
			echo '<br/>';
			echo '<font color="#4B0082">'.$x.'学期'.$y.'(专业)  没有警示信息！</font>';
		}else{
			//判断班级的个数
			while($i<6){
			
				$BJ[$i]="0";
				$IJS[$i]=0;
				$IIJS[$i]=0;
				$IIIJS[$i]=0;
				$i++;
			
			}
			// 必须先用 jason 进行封装！！！
			$rtn = json_encode($BJ);
			$arr1 =json_encode($IJS);
			$arr2 =json_encode($IIJS);
			$arr3 =json_encode($IIIJS);
			// 饼状图用的参数
			$BIJS=0;$BIIJS=0;$BIIIJS=0;
			for ($i=0;$i<sizeof($IJS);$i++){
				$BIJS+=$IJS[$i];
			}
			for ($i=0;$i<count($IIJS);$i++){
				$BIIJS+=$IIJS[$i];
			}
			for ($i=0;$i<sizeof($IIIJS);$i++){
				$BIIIJS+=$IIIJS[$i];
			}
			$BIJS=json_encode($BIJS);
			$BIIJS=json_encode($BIIJS);
			$BIIIJS=json_encode($BIIIJS);
			echo '<center>';echo '<br/>';
			echo '<caption ><font size=5><b>'.$x.'学期  '.$y.'(专业)   学业警示统计图</b></font></caption>';
			echo '</center>';
			echo '<br/>';
			echo '<table align="center" width="30%" border="0"  cellpadding="0" cellspacing="0" >';
				
			echo '<tr>';
			echo '<td width=15 height=5></td> <td bgcolor="FF6666" width=20 height=5 > </td> <td width=4><pre><font size="4"> I级警示 </font></pre> </td>
					  <td bgcolor="FFCC33" width=20 height=5>  </td> <td width=4><pre><font size="4"> II级警示 </font></pre> </td>
					  <td bgcolor="99CCFF"   width=20 height=5> </td> <td width=4><pre><font size="4"> III级警示 </font></pre></td>';
			echo '</tr>';
			echo '</table>';
				
			echo '<script src="../js/chart/Chart.js"></script>';
			echo '<center>';
			echo '<div id="tu" style="float:left  width:400% height:300%" >';
			echo '<canvas id="myChart" width=800% height=400%></canvas>';
			echo '</div>';
			echo '</center>';
			//echo '<canvas id="myChart"  width="1000" height="500"></canvas>';
			echo '<script type="text/javascript">';
			if ($str=="z") // 全校用 1 表示 柱状图，由于 1+字符串出错，所以用 z 表示柱状，b 表示饼状
			{			// 画柱状图
						echo '
							var ctx = document.getElementById("myChart").getContext("2d");';
						echo '
							var data = {
				    		labels :  '.$rtn.',
				    		datasets : [
				    		{
				    			fillColor : "rgba(255,10,10,0.5)",
				    			strokeColor : "rgba(255,0,0,0.6)",
				    			data :  '.$arr1.',
				
				    		},
				    		{
				    			fillColor : "rgba( 255, 165, 0,0.5)",
				    			strokeColor : "rgba( 255, 165, 0,0.8)",
				    			data :  '.$arr2.',
				    		},
				    		{
				    			fillColor : "rgba(151,187,255,0.75)",
				    			strokeColor : "rgba(151,187,205,0.9)",
				    			data : '.$arr3.',
				    		}
				    		]
				    	  }
				
				    	var defaults = {
				
					    scaleOverlay : false,
					    scaleOverride : false,
					    scaleSteps : null,
					    scaleStepWidth : 20,
					    scaleStartValue : null,
					    scaleLineColor : "rgba(151,187,255,1)",
					    scaleLineWidth : 2,
					    scaleShowLabels : true,
					    scaleLabel : "<%=value%>人",
					    scaleFontSize : 15, 			 			// 文字大小
					    scaleFontStyle : "bold",					// 文字样式
					    scaleFontColor : "#000000",					// 文字颜色
					    scaleFontFamily : "'.'\''.'黑体'.'\''.'",
					    scaleShowGridLines : true,  				// 是否显示网格
					    scaleGridLineColor : "rgba(151,187,255,.35)",// 网格颜色
					    scaleGridLineWidth : 1,						// 网格宽度
					    bezierCurve : false, 						// 是否使用贝塞尔曲线, 即:线条是否弯曲
					    pointDot : true, 							// 是否显示点数
					    pointDotRadius : 8, 						// 圆点的大小
					    pointDotStrokeWidth : 2,					// 圆点的笔触宽度, 即:圆点外层白色大小
					    datasetStroke : true,						// 数据集行程
					    datasetStrokeWidth : 2,  					// 线条的宽度, 即:数据集
					    datasetFill : false,    					// 是否填充数据集
					    animation : true,  							// 是否执行动画
					    animationSteps : 60, 						// 动画的时间
					    animationEasing : "easeOutQuart",     		// 动画的特效
					    onAnimationComplete : null   				// 动画完成时的执行函数
				
					};
					';
				
						echo 'ctx.canvas.width  = window.innerWidth/1.1;
							ctx.canvas.height = window.innerHeight/1.3;';
						echo 'var myNewChart = new Chart(ctx).Bar(data,defaults);';
						echo 'window.onresize=DrawOldbtnClick;'; //检测窗口大小变化
			
			}else
			{
				//echo $BIJS;
				echo ' var ctx = document.getElementById("myChart").getContext("2d");
					    var data = [
			
			        	{
			        		value : '.$BIJS.',
			        		color: "#FF6666"
			        	},
			        	{
			        		value : '.$BIIJS.',
			        		color: "#FFCC33"
			        	},
			        	{
			        		value : '.$BIIIJS.',
			        		color: "#99CCFF"
			        	}
			         ]
					';
			
				echo 'ctx.canvas.width  = window.innerWidth;
						  ctx.canvas.height = window.innerHeight/1.3;';
				echo 'var myNewChart = new Chart(ctx).PolarArea(data); ';  //极地区域图
				echo 'window.onresize=BDrawOldbtnClick;'; //检测窗口大小变化
			}
			echo '</script>';
		}
	}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
}

	//带学期      班级   图表统计
	public function xnqbjChartAction(){
		$str1=$this->request->get("xnq");
		$str=substr($str1,0,1);
		$x=substr($str1,1,11);
		$y=substr($str1,12);
	try{
		$conn =$this->getDI()->get("db");
		$sql1="select COUNT(JSJB) from MY_XYJS2 where JSJB like '%Ⅰ级警示%' and xnq='" . $x ."' and bj like '%" . $y ."%' ";
		$sql2="select COUNT(JSJB) from MY_XYJS2 where JSJB like '%Ⅱ级警示%' and xnq='" . $x ."' and bj like '%" . $y ."%' ";
		$sql3="select COUNT(JSJB) from MY_XYJS2 where JSJB like '%Ⅲ级警示%' and xnq='" . $x ."' and bj like '%" . $y ."%' ";
		$result1 = $conn->query($sql1);
		$result2 = $conn->query($sql2);
		$result3 = $conn->query($sql3);
		$IJS= 0;
		$IIJS=0;
		$IIIJS=0;
		while($usr=$result1->fetch()){
			$IJS+=$usr[0];
		}

		while($usr=$result2->fetch()){
			$IIJS+=$usr[0];
		}

		while($usr=$result3->fetch()){
			$IIIJS+=$usr[0];
		}
		//判断各班是否有警示信息	
		if($IJS==0 && $IIJS==0 && $IIIJS==0){
			echo '<br/>';
			echo '<font color="#4B0082">'.$x.'学期'.$y.'班级  没有警示信息！</font>';
		}
		else{
			echo '<center>';echo '<br/>';
			echo '<caption ><font size=5><b>'.$x.'学期  '.$y.'班   学业警示统计图</b></font></caption>';
			echo '</center>';
			echo '<br/>';
			echo '<table align="center" width="30%" border="0"  cellpadding="0" cellspacing="0" >';
				
			echo '<tr>';
			echo '<td width=15 height=5></td> <td bgcolor="FF6666" width=20 height=5 > </td> <td width=4><pre><font size="4"> I级警示 </font></pre> </td>
					  <td bgcolor="FFCC33" width=20 height=5>  </td> <td width=4><pre><font size="4"> II级警示 </font></pre> </td>
					  <td bgcolor="99CCFF"   width=20 height=5> </td> <td width=4><pre><font size="4"> III级警示 </font></pre></td>';
			echo '</tr>';
			echo '</table>';
				
			echo '<script src="../js/chart/Chart.js"></script>';
			echo '<center>';
			echo '<div id="tu" style="float:left  width:400% height:300%" >';
			echo '<canvas id="myChart" width=800% height=400%></canvas>';
			echo '</div>';
			echo '</center>';
			//echo '<canvas id="myChart"  width="1000" height="500"></canvas>';
			echo '<script type="text/javascript">';
	
			//极地区域图
			echo ' var ctx = document.getElementById("myChart").getContext("2d");
			var data = [
	     
	        	{
	        		value : '.$IJS.',
	        		color: "#FF6666"
	        	},
	        	{
	        		value : '.$IIJS.',
	        		color: "#FFCC33"
	        	},
	        	{
	        		value : '.$IIIJS.',
	        		color: "#99CCFF"
	        	}
	         ]
			';
	
			echo 'ctx.canvas.width  = window.innerWidth;
				  ctx.canvas.height = window.innerHeight/1.3;';
			echo 'var myNewChart = new Chart(ctx).PolarArea(data); ';  //极地区域图
			echo 'window.onresize=DrawOldbtnClick;'; //检测窗口大小变化
			echo '</script>';
		  }
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	

	public function indexAction()
	{
		 
	}

	public function homeAction(){
		 
	}
}
?>