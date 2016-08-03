<?php
use Phalcon\Mvc\Model\Query;
header("Content-type:text/html;charset=utf-8");
use Phalcon\Db\Adapter\Pdo\Oracle as DbAdapter;
class ChartJSController extends ControllerBase
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
		try{// 初始化 学年期
			$conn =$this->getDI()->get("db");
			$sql="select distinct xnq from XQView order by xnq desc"; //没起作用！！！ 被下面初始化后，被覆盖了
			$result = $conn->query($sql);
			$this->view->setVar("xqs",$result);
			$sql1="select distinct nj  from XQView where nj is not null order by nj  desc";
			$result1= $conn->query($sql1);
			$this->view->setVar("njs",$result1); //此方法初始化年级以后，查询出问题，而用ajax初始化，则不存在相同问题！
			$sql2="select distinct(xy) from QUERYVIEW order by xy";
			$result2= $conn->query($sql2); //初始化 学院
			$this->view->setVar("xys",$result2);
			// 获取 session 中的用户ID，传给页面
			$this->view->setVar("sessionName",$_SESSION['username']);
		}catch(Exception $e) {
	   		   		echo '<font color="#4B0082">数据库连接错误！</font>';
	   	}
	}
	//警示结果统计----年级初始化
	public function njAction(){
		//$x=$this->request->get("xy");
		try{
			$conn =$this->getDI()->get("db");
			$sql1="select distinct nj  from XQView where nj is not null order by nj  desc";
			$xyresult= $conn->query($sql1);
			echo '<option value=\'00\'>未选择年级</option>';
			while($usr=$xyresult->fetch()){
				echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	//警示结果统计----学期初始化
	public function xnqAction(){
	try{
			$conn =$this->getDI()->get("db");
			$sql1="select distinct(xnq) from QUERYVIEW order by xnq desc";
			$xyresult= $conn->query($sql1);
			echo '<option value=\'00\'>未选择学期</option>';
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
			$sql="select distinct(zy) from QUERYVIEW where xy='" . $x ."' order by zy";
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
			$sql="select distinct(bj) from QUERYVIEW where zy='" . $x ."'order by bj";
			$result = $conn->query($sql);
			echo '<option value=\'00\'>未选择班级</option>';
			while($usr=$result->fetch()){
				echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
			}
		}catch(Exception $e) {
				echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	//---------------------------------------------------------------------------------------------
	/**
	 * 初始化 下拉框的 函数
	 */
	// (1) 选择学期之后，控制年级。
	public function  xqnjAction(){
		$str=$this->request->get("xqnj"); // 传 学年期
		$x=substr($str,0,4); //学期截取的 年级 eg:2014
		try{
			$conn =$this->getDI()->get("db");
			$sql1="select distinct nj  from QUERYVIEW where substr(nj,0,4)<='".$x."' order by nj  desc";
			$xyresult= $conn->query($sql1);
			echo '<option value=\'00\'>未选择年级</option>';
			while($usr=$xyresult->fetch()){
				echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	// (2) 选择学期之后，与专业  一起控制班级。
	public function  xqgradeAction(){
		$str=$this->request->get("xqzy");
		$x=substr($str,0,11); //学期 找班级
		$y=substr($str,11); // 专业
		try{
			$conn =$this->getDI()->get("db");
			$sql1="select distinct bj  from QUERYVIEW where xnq ='".$x."' and zy ='".$y."' order by bj";
			$xyresult= $conn->query($sql1);
			echo '<option value=\'00\'>未选择班级</option>';
			while($usr=$xyresult->fetch()){
				echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	// (3) 学年选择，控制学期 njxq
	public function  njxqAction(){
		$str=$this->request->get("njxq");// 年级 控制学期，参数为 年级 ：2014 级
		$x=substr($str,0,4); // 截取 年份：2014
		try{
			$conn =$this->getDI()->get("db");
			$sql1="select distinct xnq  from QUERYVIEW where substr(xnq,0,4) >='".$x."' order by xnq desc";
			$xyresult= $conn->query($sql1);
			echo '<option value=\'00\'>未选择学期</option>';
			while($usr=$xyresult->fetch()){
				echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	// (4) 年级已变动，专业+年级 控制 班级
	public function njgradeAction(){
		$str=$this->request->get("njzy");// 年级 专业
		$x=substr($str,0,8); //年级
		$y=substr($str,8); //专业
		try{
			$conn =$this->getDI()->get("db");
			$sql1="select distinct bj  from QUERYVIEW where nj ='".$x."' and zy ='".$y."' order by bj";
			$xyresult= $conn->query($sql1);
			echo '<option value=\'00\'>未选择班级</option>';
			while($usr=$xyresult->fetch()){
				echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	// (5)专业 已选择，变动 年级，使其与专业 一起控制班级。 gradenj
	public function gradenjAction(){
		$str=$this->request->get("zynj"); //年级 专业
		$x=substr($str,0,8); // 不能 截取，要与表中 年级 格式 一致：如 ，2014 级
		$y=substr($str,8);
		try{
			$conn =$this->getDI()->get("db");
			$sql1="select distinct bj  from QUERYVIEW where nj ='".$x."' and zy ='".$y."' order by bj";
			$xyresult= $conn->query($sql1);
			echo '<option value=\'00\'>未选择班级</option>';
			while($usr=$xyresult->fetch()){
				echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	// （6） bjxnq 班级选择了，控制学年期里面的数值
	public function bjxnqAction(){
		$x=$this->request->get("bjxnq");
		//$x=substr($str,0,4);
		try{
			$conn =$this->getDI()->get("db");
			$sql1="select distinct xnq  from QUERYVIEW where bj ='".$x."' order by xnq  desc";
			$xyresult= $conn->query($sql1);
			echo '<option value=\'00\'>未选择学期</option>';
			while($usr=$xyresult->fetch()){
				echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	//（7） 年级+学期+专业控制 班级  njxqzy
	public function njxqzygradeAction(){
		$str=$this->request->get("njxqzy");
		$x=substr($str,0,8);
		$y=substr($str,8,11);
		$z=substr($str,19);
		try{
			$conn =$this->getDI()->get("db");
			$sql1="select distinct bj  from QUERYVIEW where nj ='".$x."' and xnq ='".$y."' and zy ='".$z."' order by bj";
			$xyresult= $conn->query($sql1);
			echo '<option value=\'00\'>未选择班级</option>';
			while($usr=$xyresult->fetch()){
				echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////// 画图   柱状图  警示结果统计/////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  最初    模板
	public function JSchartAction(){
		//孙昊:
		try{
			$conn =$this->getDI()->get("db");
			$sql1="select XY,sum(IJS),sum(IIJS),sum(IIIJS) from XUEXIAO where XY is not null group by xy order by xy";
			$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQXUEXIAO  group by XNQ order by xnq";
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$i=0;
			$XY= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			while($usr=$result1->fetch()){
				$XY[$i]=$usr[0];
				$IJS[$i]=intval($usr[1]);
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
				$i++;
			}
			
			//全校各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			while($usr=$result2->fetch()){
				$ZXNQ[$k]=$usr[0];
				$ZIJS[$k]=intval($usr[1]);
				$ZIIJS[$k]=intval($usr[2]);
				$ZIIIJS[$k]=intval($usr[3]);
				$k++;
			}
			
			
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
		// 必须先用 jason 进行封装！！！
		$rtn = json_encode($XY);
		$arr1 =json_encode($IJS);
		$arr2 =json_encode($IIJS);
		$arr3 =json_encode($IIIJS);
		//折线图 参数封装
		$zrtn =json_encode($ZXNQ);
		$zarr1 =json_encode($ZIJS);
		$zarr2 =json_encode($ZIIJS);
		$zarr3 =json_encode($ZIIIJS);
		
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
		//highchart 柱状图和饼图在一起       examples/combo
		echo '<center>';
		
		echo '<div id="tu" style="float:left  width:400% height:300%" >';
		//echo '<canvas id="myChart" width=800% height=400%></canvas>';
		echo '</div>';
		echo '<div id="linetu" style="float:left  width:400% height:300%" >';
		//echo '<canvas id="myChart" width=800% height=400%></canvas>';
		echo '</div>';
		echo '</center>';
	 echo'<script type="text/javascript">	 		
	 		$(function () {
			$("#tu").highcharts({
				title: {
					text: "全校各学院学业警示统计图",
	 		        style: {
							color: "black",
							fontWeight:"bold",
							fontSize:"25"
						}
				},
				xAxis: {
					categories: '.$rtn.'
				},
				yAxis: {
				   min: 0,
				   title: {
				   text: "警示人数"
						}
				},
		
				credits: { 
          			enabled:false
						},
				exporting: {
            		enabled:true
				},
							
				labels: {
					items: [{
						html: "全校警示统计图",
						style: {
							left: "0px",
							top: "-50px",
							color: "black",
						    fontSize:"15"
						}
					}]
				},
				series: [{
					type:"column",
					name: "I级警示",
					color: "rgba(255,10,10,0.6)",
					data: '.$arr1.'
				}, {
					type:"column",
					name: "II级警示",
					color:"rgba( 255, 165, 0,0.6)",
					data: '.$arr2.'
				}, {
					type: "column",
					name: "III级警示",
					color: "rgba(151,187,255,0.75)",
					data:'.$arr3.'
				}, 
				 {
					type: "pie",
					name: "人数",
					data: [{
						name: "I级警示",
						y: '.$BIJS.',
						color:"rgba(255,10,10,0.6)"
						}, {
						name: "II级警示",
						y: '.$BIIJS.',
						color:"rgba( 255, 165, 0,0.6)"
					}, {
						name: "III级警示",
						y: '.$BIIIJS.',
						color: "rgba(151,187,255,0.75)"
					}],
					center: [26, 10],
					size: 90,
					showInLegend: false,
					dataLabels: {
					enabled: false
					}
				}]
			});
		});				
		'; 
	 echo 'tu.width  = window.innerWidth/1.3;
				  tu.height = window.innerHeight/0.7;';
	// echo 'window.onresize=DrawbtnClick;
				echo '</script>';
	 /* //折线图
	 echo'<script type="text/javascript">
	 		$(function () {
    $("#linetu").highcharts({
        chart: {
            type: "line"
        },
        title: {
            text: "全校各学期警示统计折线图",
	 		style: {
							color: "black",
							fontWeight:"bold",
							fontSize:"25"
						}
        },
        
        xAxis: {
	 		categories:'.$zrtn.'
        },
        yAxis: {
            title: {
                text: "警示人数"
            },
            min: 0
        },
 		credits: { 
          			enabled:false
						},
				exporting: {
            		enabled:true
				},
        plotOptions: {
            spline: {
                marker: {
                    enabled: true
                }
            }
        },

        series: [{
					name: "I级警示",
					color: "rgba(255,10,10,0.6)",
					data: '.$zarr1.'
				}, {
					
					name: "II级警示",
					color:"rgba( 255, 165, 0,0.6)",
					data: '.$zarr2.'
				}, {
					
					name: "III级警示",
					color: "rgba(151,187,255,0.75)",
					data:'.$zarr3.'
				}]
    });
}); 		window.onresize=DrawbtnClick;
	 		</script>'; */
//------------------------------------------------------------------------------------------------
		/* echo'<script type="text/javascript">
				var chart;
				$(function () {
					$("#divtu").highcharts({
						chart: {
							type: "column"
						},
						title: {
							text: "柱状统计结果"
						},
						xAxis: {
							categories:'.$rtn.
						'},
						yAxis: {
							min: 0,
							title: {
								text: "警示统计百分比"
							}
						},
						tooltip: {
							shared: true
						},
						plotOptions: {
							column: {
								stacking: "percent"
							}
						},
						credits: { 
          				enabled:false
						},
						exporting: {
            			enabled:true
						},
						series: [ {
						  color : "rgba(0,155,0,0.3)",
							name: "无警示",
							data: '.$arr4.'
						},{
							color : "rgba(151,187,255,0.95)",
							name: "III级警示",
							data: '.$arr3.',
						}, {
							color : "rgba(255,165,0,0.6)",
							name: "II级警示",
							data: '.$arr2.',
						},{
						    color : "rgba(255,10,10,0.6)",
							name: "I级警示",
							data: '.$arr1.',
						} ]
					});
				});';
			echo 'divtu.width  = window.innerWidth/1.3;
				  divtu.height = window.innerHeight;';
			echo 'window.onresize=DrawbtnClick; 
				</script>'; */
	//------------------------------------------------------------------------------------------------	
		/*$str=$this->request->get("mChart");
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
		echo '</script>';*/
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////// (1) 不带学期的  //// 图表统计       /////////////////////////////////////////////////////

	//不带学期      学校图表统计
	public function xxChartAction(){
		//$str=$this->request->get("xx");
		try{
			$conn =$this->getDI()->get("db");
			//查询学年期是否为空， 为空  说明后台数据正在更新.......
			$sql="select distinct xnq from XQView order by xnq desc";
			$result = $conn->query($sql);
			$num=0;
			while($usr=$result->fetch()){	
				$num=$usr[0];
			}
			if($num==0){
				echo '<br/><br/><br/>';
				echo '<font color="#4B0082">&nbsp&nbsp后台数据正在更新，请耐心等待....................</font>';
			}
			else{
			//全校  柱状图参数
			$sql1="select XY,sum(IJS),sum(IIJS),sum(IIIJS) from XUEXIAO where XY is not null group by xy order by xy";
			//全校   各学期    折线图参数
			$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQXUEXIAO  group by XNQ order by xnq";
			//全校各年级     柱状图参数
			$sql3="select NJ,sum(IJS),sum(IIJS),sum(IIIJS) from XUEXIAO where NJ is not null group by nj order by nj";
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$result3 = $conn->query($sql3);
				$i=0;
				$XY= Array();
				$IJS= Array();
				$IIJS= Array();
				$IIIJS= Array();
			while($usr=$result1->fetch()){
				$XY[$i]=$usr[0];
				//必须强制转换成整形
				$IJS[$i]=intval($usr[1]); 
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
				$i++;
			}
			//全校各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
				$k=0;
				$ZXNQ= Array();
				$ZIJS= Array();
				$ZIIJS= Array();
				$ZIIIJS= Array();
				while($usr=$result2->fetch()){
					$ZXNQ[$k]=strval($usr[0]);
					$ZIJS[$k]=intval($usr[1]);
					$ZIIJS[$k]=intval($usr[2]);
					$ZIIIJS[$k]=intval($usr[3]);
					$k++;
				}
			
			//全校  各年级  柱状图  参数
				$k=0;
				$NJ= Array();
				$NIJS= Array();
				$NIIJS= Array();
				$NIIIJS= Array();
			while($usr=$result3->fetch()){
				$NJ[$k]=$usr[0];
				//必须强制转换成整形
				$NIJS[$k]=intval($usr[1]);
				$NIIJS[$k]=intval($usr[2]);
				$NIIIJS[$k]=intval($usr[3]);
				$k++;
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
				echo '<font color="#4B0082">全校没有学业预警信息！</font>';
			}else{	
				// 必须先用 jason 进行封装！！！
				$rtn = json_encode($XY);
				$arr1 =json_encode($IJS);
				$arr2 =json_encode($IIJS);
				$arr3 =json_encode($IIIJS);
				//折线图 参数封装
				$zrtn =json_encode($ZXNQ);
				$zarr1 =json_encode($ZIJS);
				$zarr2 =json_encode($ZIIJS);
				$zarr3 =json_encode($ZIIIJS);
				
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
				
				// 全校  各年级   参数封装   ！！！
				$nrtn = json_encode($NJ);
				$narr1 =json_encode($NIJS);
				$narr2 =json_encode($NIIJS);
				$narr3 =json_encode($NIIIJS);
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '<div id="ntu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '</center>';
			 echo'<script type="text/javascript">	 		
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text: "全校各学院 学业预警统计图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories: '.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警人数"
								}
						},
				        
						credits: { 
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
									
						labels: {
							items: [{
								html: "全校预警统计图",
								style: {
									left: "0px",
									top: "-50px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						}, 
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});				
				'; 
			 echo ' </script>';
			 //折线图
			 echo'<script type="text/javascript">
			 		$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text: "全校各学期 学业预警统计折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
		        },
		        
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警人数"
		            },
		            min: 0
		        },
		 		credits: { 
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
		
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
							
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
							
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				}); 
			 	</script>';
	//////////// 以年级为横轴的柱状图-------------------------------------------------------------
			  echo'<script type="text/javascript">	 		
			 		$(function () {
					$("#ntu").highcharts({
						title: {
							text: "全校各年级学业预警统计图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories: '.$nrtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警人数"
								}
						},
				
						credits: { 
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
									
						labels: {
							items: [{
								html: "全校预警统计图",
								style: {
									left: "0px",
									top: "-50px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$narr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$narr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$narr3.'
						}, 
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});				
				'; 
			 echo ' </script>';
        }
	 }
  }catch(Exception $e) {
	 	echo '<font color="#4B0082">数据库连接错误！</font>';
  }
	
}

	//不带学期      学院  图表统计
	public function xyChartAction(){
		$x=$this->request->get("xy");
		//echo $x;
		try{
			$conn =$this->getDI()->get("db");
			//学院 柱状图参数
			$sql1="select ZY,sum(IJS),sum(IIJS),sum(IIIJS) from XUEYUAN where xy like '%" . $x ."' group by zy order by zy";
			//某学院   各学期    折线图参数
			$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQXUEYUAN where xy like '%".$x."%' group by XNQ order by xnq";
			$sql3="select NJ,sum(IJS),sum(IIJS),sum(IIIJS) from XUEYUAN where NJ is not null group by nj order by nj";
				
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$result3 = $conn->query($sql3);
			
			$i=0;
			$ZY= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			while($usr=$result1->fetch()){
				$ZY[$i]=$usr[0];
				$IJS[$i]=intval($usr[1]);
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
				$i++;
			}
				
			//某学院     各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			while($usr=$result2->fetch()){
				$ZXNQ[$k]=$usr[0];
				$ZIJS[$k]=intval($usr[1]);
				$ZIIJS[$k]=intval($usr[2]);
				$ZIIIJS[$k]=intval($usr[3]);
				$k++;
			}
			//全校  各年级  柱状图  参数
			$k=0;
			$NJ= Array();
			$NIJS= Array();
			$NIIJS= Array();
			$NIIIJS= Array();
			while($usr=$result3->fetch()){
				$NJ[$k]=$usr[0];
				//必须强制转换成整形
				$NIJS[$k]=intval($usr[1]);
				$NIIJS[$k]=intval($usr[2]);
				$NIIIJS[$k]=intval($usr[3]);
				$k++;
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
				echo '<font color="#4B0082">'. $x.'  没有预警信息！</font>';
			}
			else{	
				// 必须先用 jason 进行封装！！！
				$rtn = json_encode($ZY);
				$arr1 =json_encode($IJS);
				$arr2 =json_encode($IIJS);
				$arr3 =json_encode($IIIJS);
				//折线图 参数封装
				$zrtn =json_encode($ZXNQ);
				$zarr1 =json_encode($ZIJS);
				$zarr2 =json_encode($ZIIJS);
				$zarr3 =json_encode($ZIIIJS);
				
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
				// 全校  各年级   参数封装   ！！！
				$nrtn = json_encode($NJ);
				$narr1 =json_encode($NIJS);
				$narr2 =json_encode($NIIJS);
				$narr3 =json_encode($NIIIJS);
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '<div id="ntu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$x.'各专业   学业预警统计图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警人数"
								}
						},
				
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
						
						labels: {
							items: [{
								html:"学院预警统计图",
								style: {
									left: "-5px",
									top: "-40px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo '</script>';
				//折线图
				echo'<script type="text/javascript">
			$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text:"'.$x.'各学期 学业预警统计折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
		        },
				
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警人数"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
				
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
					
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
					
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
				</script>';
				/// 以年级为横轴 
				echo'<script type="text/javascript">
			 		$(function () {
					$("#ntu").highcharts({
						title: {
							text:"'.$x.'各年级   学业预警统计图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories:'.$nrtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警人数"
								}
						},
				
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
				
						labels: {
							items: [{
								html:"学院预警统计图",
								style: {
									left: "-5px",
									top: "-40px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$narr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$narr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$narr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo 'tu.width  = window.innerWidth/1.3;
						  tu.height = window.innerHeight/0.7;';
				echo '</script>';
	 }	
	}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
	}	
}

	//不带学期      专业    图表统计，饼图状图可以一起画出来, ----------------有的专业 班级 特别多！eg：理学院 财务管理！！！
	public function zyChartAction(){
		$x=$this->request->get("zy");
		try{	
			$conn =$this->getDI()->get("db");
			//专业    柱状图参数
			$sql1="select BJ,sum(IJS),sum(IIJS),sum(IIIJS) from ZHUANYE where zy like'%".$x."' group by bj order by bj ";
			//某专业    各学期  折线图参数
			$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQZHUANYE where zy like '%" .$x."' group by XNQ order by xnq";
			$sql3="select NJ,sum(IJS),sum(IIJS),sum(IIIJS) from ZHUANYE where zy like '%" .$x."' and NJ is not null group by nj order by nj";
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$result3 = $conn->query($sql3);
			
			$i=0;
			$BJ= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			while($usr=$result1->fetch()){
				$BJ[$i]=$usr[0];
				$IJS[$i]=intval($usr[1]);
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
				$i++;
			}
		
			//某专业     各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			while($usr=$result2->fetch()){
				$ZXNQ[$k]=$usr[0];
				$ZIJS[$k]=intval($usr[1]);
				$ZIIJS[$k]=intval($usr[2]);
				$ZIIIJS[$k]=intval($usr[3]);
				$k++;
			}
				$k=0;
				$NJ= Array();
				$NIJS= Array();
				$NIIJS= Array();
				$NIIIJS= Array();
				while($usr=$result3->fetch()){
					$NJ[$k]=$usr[0];
					//必须强制转换成整形
					$NIJS[$k]=intval($usr[1]);
					$NIIJS[$k]=intval($usr[2]);
					$NIIIJS[$k]=intval($usr[3]);
					$k++;
				}
			//判断各班是否有预警信息
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
				echo '<font color="#4B0082">'. $x.'(专业)没有预警信息！</font>';
			}
			else{
				// 必须先用 jason 进行封装！！！
				$rtn = json_encode($BJ);
				$arr1 =json_encode($IJS);
				$arr2 =json_encode($IIJS);
				$arr3 =json_encode($IIIJS);
				//折线图 参数封装
				$zrtn =json_encode($ZXNQ);
				$zarr1 =json_encode($ZIJS);
				$zarr2 =json_encode($ZIIJS);
				$zarr3 =json_encode($ZIIIJS);
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
				// 全校  各年级   参数封装   ！！！
				$nrtn = json_encode($NJ);
				$narr1 =json_encode($NIJS);
				$narr2 =json_encode($NIIJS);
				$narr3 =json_encode($NIIIJS);
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="ntu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '</center>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$x.'专业各班级学业预警统计图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"23"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警人数"
								}
						},
				
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
				
						labels: {
							items: [{
								html:"专业预警统计图",
								style: {
									left: "-5px",
									top: "-40px",
									color: "black",
								    fontSize:"13"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo '</script>';
				//折线图
				echo'<script type="text/javascript">
			$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text:"'.$x.'专业 各学期学业预警统计折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"23"
								}
		        },
				
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警人数"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
				
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
				
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
				
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
			  </script>';
				/// 以年级为横轴：
				echo'<script type="text/javascript">
			 		$(function () {
					$("#ntu").highcharts({
						title: {
							text:"'.$x.'专业各年级学业预警统计图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"23"
								}
						},
						xAxis: {
							categories:'.$nrtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警人数"
								}
						},
				
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
				
						labels: {
							items: [{
								html:"专业预警统计图",
								style: {
									left: "-5px",
									top: "-40px",
									color: "black",
								    fontSize:"13"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$narr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$narr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$narr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo '</script>';
	}
  }catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
  }
}
	
	//不带学期      班级  只要 图表统计？柱状图也可以要？先不画。。。
	public function bjChartAction(){
		$x=$this->request->get("bj");
        try{
		$conn =$this->getDI()->get("db");
		//班级    柱状图参数
		$sql1="select COUNT(xh) from MY_XYJS where JSJB like '%Ⅰ级警示%' and bj like '%" . $x ."' ";
		$sql2="select COUNT(xh) from MY_XYJS where JSJB like '%Ⅱ级警示%' and bj like '%" . $x ."' ";
		$sql3="select COUNT(xh) from MY_XYJS where JSJB like '%Ⅲ级警示%'  and bj like '%" . $x ."' ";
		$result1 = $conn->query($sql1);
		$result2 = $conn->query($sql2);
		$result3 = $conn->query($sql3);
		$IJS= 0;
		$IIJS=0;
		$IIIJS=0;
		while($usr=$result1->fetch()){
			$IJS+=intval($usr[0]);
		}

		while($usr=$result2->fetch()){
			$IIJS+=intval($usr[0]);
		}
		while($usr=$result3->fetch()){
			$IIIJS+=intval($usr[0]);
		}
		$IJS =json_encode($IJS);
		$IIJS =json_encode($IIJS);
		$IIIJS =json_encode($IIIJS);
		
//////////////////////////////班级的每个学年期的统计：---画折线图使用。
		//先把学期选择出来。 数据库中表格：XNQZHUANYE
		$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQZHUANYE where bj like '%".$x."' group by XNQ order by xnq";
        //班级各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
		$result2 = $conn->query($sql2);
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			while($usr=$result2->fetch()){
				$ZXNQ[$k]=$usr[0];
				$ZIJS[$k]=intval($usr[1]);
				$ZIIJS[$k]=intval($usr[2]);
				$ZIIIJS[$k]=intval($usr[3]);
				$k++;
			}
			//折线图 参数封装
			$zrtn =json_encode($ZXNQ);
			$zarr1 =json_encode($ZIJS);
			$zarr2 =json_encode($ZIIJS);
			$zarr3 =json_encode($ZIIIJS);
			
		//判断该班是否有警示信息
        if($IJS==0 && $IIJS==0 && $IIIJS==0){
		echo '<br/>';
		echo '<font color="#4B0082">'. $x.'班级  没有预警信息！</font>';
		}
		else{
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">';
			//饼图
			echo '$(function () {
				    $("#tu").highcharts({
				        chart: {
				            type: "pie",
				            options3d: {
				                enabled: true,
				                alpha: 45,
				                beta: 10
				            }
				        },
				        title: {
				            text: "'.$x.'(班级) 学业预警统计图",
				            style: {
										color: "black",
										fontWeight:"bold",
										fontSize:"25"
									}
				            
				        },
				        tooltip: {
				            //pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
				        },
				        plotOptions: {
				            pie: {
				                allowPointSelect: true,
				                cursor: "pointer",
				                depth:35,
				                dataLabels: {
				                    enabled: true,
				                    format: "{point.name}"
				                }
				            }
				        },
	            		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
				        series: [{
				            type: "pie",
				            name: "人数",
				            data: [ 
				            	{
								name: "II级预警",
								color:"rgba( 255, 165, 0,0.6)",
								y: '.$IIJS.'
								}, 
								{
								name: "III级预警",
								color: "rgba(151,187,255,0.75)",
								y: '.$IIIJS.'
								}, 		
			            		{
			                    name: "I级预警",
			               		color: "rgba(255,10,10,0.6)",
			                    y: '.$IJS.',
			                    sliced: true,
			                    selected: true
				                },					                	
				            ]
				        }]
				    });
				});';
			echo '</script>';
				//折线图,每学期统计，班级已经固定属于某个学年了。。。
				echo'<script type="text/javascript">
				$(function () {
			    $("#linetu").highcharts({
			        chart: {
			            type: "line"
			        },
			        title: {
			            text:"'.$x.'班级 各学期  学业预警统计折线图",
				 		style: {
										color: "black",
										fontWeight:"bold",
										fontSize:"25"
									}
			        },
						
			        xAxis: {
				 		categories:'.$zrtn.'
			        },
			        yAxis: {
			            title: {
			                text: "预警人数"
			            },
			            min: 0
			        },
			 		credits: {
			          			enabled:false
									},
							exporting: {
			            		enabled:true
							},
			        plotOptions: {
			            spline: {
			                marker: {
			                    enabled: true
			                }
			            }
			        },
						
			        series: [{
								name: "I级预警",
								color: "rgba(255,10,10,0.6)",
								data: '.$zarr1.'
							}, {
						
								name: "II级预警",
								color:"rgba( 255, 165, 0,0.6)",
								data: '.$zarr2.'
							}, {
						
								name: "III级预警",
								color: "rgba(151,187,255,0.75)",
								data:'.$zarr3.'
							}]
					    });
					});
						</script>';
				 }
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
  }
	
	////////////////////////////////// (2) 带学期     图表统计//////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////
	//带学期      学校图表统计
	public function xnqxxChartAction(){
		$x=$this->request->get("xnq");
		//echo $x;
	try{
			$conn =$this->getDI()->get("db");
			//查询学年期是否为空， 为空  说明后台数据正在更新.......
			$sql="select distinct xnq from XQView order by xnq desc";
			$result = $conn->query($sql);
			$num=0;
			while($usr=$result->fetch()){	
				$num=$usr[0];
			}
			if($num==0){
				echo '<br/><br/><br/>';
				echo '<font color="#4B0082">&nbsp&nbsp后台数据正在更新，请耐心等待....................</font>';
			}
			else{
			//全校  柱状图参数$sql1="select ZY,sum(IJS),sum(IIJS),sum(IIIJS) from XUEYUAN where xy like '%" . $x ."' group by zy order by zy";
			$sql1="select XY,sum(IJS),sum(IIJS),sum(IIIJS) from XNQXUEXIAO where xnq like '%" . $x ."' group by xy order by xy";
			//全校   各学期    折线图参数
			$sql2="select NJ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQXUEXIAO where xnq like '%" . $x ."'  group by nj order by nj";
			//$sql3="select NJ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQXUEXIAO  group by XNQ order by xnq";
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$i=0;
			$XY= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			while($usr=$result1->fetch()){
				$XY[$i]=$usr[0];
				//必须强制转换成整形
				$IJS[$i]=intval($usr[1]); 
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
				$i++;
			}
			//全校各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			while($usr=$result2->fetch()){
				$ZXNQ[$k]=$usr[0];
				$ZIJS[$k]=intval($usr[1]);
				$ZIIJS[$k]=intval($usr[2]);
				$ZIIIJS[$k]=intval($usr[3]);
				$k++;
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
				echo '<font color="#4B0082">本学期全校 没有预警信息！</font>';
			}else{	
				// 必须先用 jason 进行封装！！！
				$rtn = json_encode($XY);
				$arr1 =json_encode($IJS);
				$arr2 =json_encode($IIJS);
				$arr3 =json_encode($IIIJS);
				//折线图 参数封装
				$zrtn =json_encode($ZXNQ);
				$zarr1 =json_encode($ZIJS);
				$zarr2 =json_encode($ZIIJS);
				$zarr3 =json_encode($ZIIIJS);
				
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
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '</center>';
			 echo'<script type="text/javascript">	 		
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text: "'.$x.'学期全校各学院  学业预警统计图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories: '.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警人数"
								}
						},
				
						credits: { 
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
									
						labels: {
							items: [{
								html: "全校预警统计图",
								style: {
									left: "0px",
									top: "-50px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						}, 
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});				
				'; 
			 echo 'tu.width  = window.innerWidth/1.3;
				tu.height = window.innerHeight/0.7;';
			 echo ' </script>';
			 //折线图
			 echo'<script type="text/javascript">
			 		$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text: "'.$x.'学期全校各年级  学业预警统计折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
		        },
		        
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警人数"
		            },
		            min: 0
		        },
		 		credits: { 
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
		
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
							
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
							
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				}); 
			 	</script>';
        }
	 }
  }catch(Exception $e) {
	 	echo '<font color="#4B0082">数据库连接错误！</font>';
  }
	
}

	//带学期      学院  图表统计
	public function xnqxyChartAction(){
		$str=$this->request->get("xnq");
		$x=substr($str,0,11);
		$y=substr($str,11);
	try{
		$conn =$this->getDI()->get("db");
		//学院    柱状图参数
		$sql1="select  ZY,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQXUEYUAN where (xnq='" .$x."' and xy like '%" . $y ."') group by zy order by zy";
		//学院       折线图参数  需要修改为    年级
		$sql2="select NJ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQXUEYUAN where (xnq='" .$x."' and xy like '%" . $y ."') group by nj order by nj";
		$result1 = $conn->query($sql1);
		$result2 = $conn->query($sql2);
		$i=0;
		$ZY= Array();
		$IJS= Array();
		$IIJS= Array();
		$IIIJS= Array();
		while($usr=$result1->fetch()){
			$ZY[$i]=$usr[0];
			$IJS[$i]=intval($usr[1]);
			$IIJS[$i]=intval($usr[2]);
			$IIIJS[$i]=intval($usr[3]);
			$i++;
		}
		
		//某学院     各个年级    IJS IIJS  IIIJS 的人数，用来画折线图
		$k=0;
		$ZXNQ= Array();
		$ZIJS= Array();
		$ZIIJS= Array();
		$ZIIIJS= Array();
		while($usr=$result2->fetch()){
			$ZXNQ[$k]=$usr[0];
			$ZIJS[$k]=intval($usr[1]);
			$ZIIJS[$k]=intval($usr[2]);
			$ZIIIJS[$k]=intval($usr[3]);
			$k++;
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
			echo '<font color="#4B0082">'.$x.'学期'.$y.'没有预警信息！</font>';
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
			//折线图 参数封装
			$zrtn =json_encode($ZXNQ);
			$zarr1 =json_encode($ZIJS);
			$zarr2 =json_encode($ZIIJS);
			$zarr3 =json_encode($ZIIIJS);
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
			//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$x.'学期'.$y.'各专业学业预警统计图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警人数"
								}
						},
				
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
						
						labels: {
							items: [{
								html:"学院预警统计图",
								style: {
									left: "-8px",
									top: "-30px",
									color: "black",
								    fontSize:"14"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo 'tu.width  = window.innerWidth/1.3;
						  tu.height = window.innerHeight/0.7;';
				echo '</script>';
				//折线图
				echo'<script type="text/javascript">
			$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text:"'.$x.'学期各年级  学业预警统计折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
		        },
				
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警人数"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
				
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
					
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
					
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
				</script>';
		}
	 }catch(Exception $e) {
		echo '<font color="#4B0082">数据库连接错误！</font>';
	}
 }

	//带学期      专业    图表统计
	public function xnqzyChartAction(){
		$str=$this->request->get("xnq");
		$x=substr($str,0,11);
		$y=substr($str,11);
 try{
		$conn =$this->getDI()->get("db");
		//专业     柱状图参数
		$sql1="select  BJ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQZHUANYE where (xnq='" . $x ."' and zy like '%" . $y ."') group by bj order by bj";
		//专业  折线图参数      需要修改为年级
		$sql2="select nj,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQXUEYUAN where (xnq='" . $x ."' and zy like '%" . $y ."') group by nj order by nj";
		$result1 = $conn->query($sql1);
		$result2 = $conn->query($sql2);
		$i=0;
		$BJ= Array();
		$IJS= Array();
		$IIJS= Array();
		$IIIJS= Array();
		while($usr=$result1->fetch()){
			$BJ[$i]=$usr[0];
			$IJS[$i]=intval($usr[1]);
			$IIJS[$i]=intval($usr[2]);
			$IIIJS[$i]=intval($usr[3]);
			$i++;
		}
		//某专业   各个年级      IJS IIJS  IIIJS 的人数，用来画折线图
		$k=0;
		$ZXNQ= Array();
		$ZIJS= Array();
		$ZIIJS= Array();
		$ZIIIJS= Array();
		while($usr=$result2->fetch()){
			$ZXNQ[$k]=$usr[0];
			$ZIJS[$k]=intval($usr[1]);
			$ZIIJS[$k]=intval($usr[2]);
			$ZIIIJS[$k]=intval($usr[3]);
			$k++;
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
			echo '<font color="#4B0082">'.$x.'学期'.$y.'(专业)没有预警信息！</font>';
		}else{
			/* //判断班级的个数
			while($i<6){
			
				$BJ[$i]="0";
				$IJS[$i]=0;
				$IIJS[$i]=0;
				$IIIJS[$i]=0;
				$i++;
			
			} */
			// 必须先用 jason 进行封装！！！
			$rtn = json_encode($BJ);
			$arr1 =json_encode($IJS);
			$arr2 =json_encode($IIJS);
			$arr3 =json_encode($IIIJS);
			//折线图 参数封装
			$zrtn =json_encode($ZXNQ);
			$zarr1 =json_encode($ZIJS);
			$zarr2 =json_encode($ZIIJS);
			$zarr3 =json_encode($ZIIIJS);
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
			//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$x.'学期'.$y.'专业各班级学业预警统计图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"20"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警人数"
								}
						},
				
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
				
						labels: {
							items: [{
								html:"专业预警统计图",
								style: {
									left: "-15px",
									top: "-20px",
									color: "black",
								    fontSize:"13"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [5, 10],
							size: 70,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo '</script>';
				//折线图
				echo'<script type="text/javascript">
			$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text:"'.$x.'学期 '.$y.'专业 各年级  学业预警统计折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"20"
								}
		        },
				
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警人数"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
				
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
				
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
				
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
			  </script>';	
		}
	}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
}

	//带学期      班级   图表统计
	public function xnqbjChartAction(){
		$str=$this->request->get("xnq");
		$x=substr($str,0,11);
		$y=substr($str,11);
	try{
		$conn =$this->getDI()->get("db");
		//班级     柱状图参数
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
			$IJS+=intval($usr[0]);
		}

		while($usr=$result2->fetch()){
			$IIJS+=intval($usr[0]);
		}

		while($usr=$result3->fetch()){
			$IIIJS+=intval($usr[0]);
		}	
		
		//判断各班是否有警示信息	
		if($IJS==0 && $IIJS==0 && $IIIJS==0){
			echo '<br/>';
			echo '<font color="#4B0082">'.$x.'学期'.$y.'班级  没有预警信息！</font>';
		}
		else{
			//柱状图    参数封装
			$IJS =json_encode($IJS);
			$IIJS =json_encode($IIJS);
			$IIIJS =json_encode($IIIJS);
	
			echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">';
			//饼图
			echo '$(function () {
				    $("#tu").highcharts({
				        chart: {
				            type: "pie",
				            options3d: {
				                enabled: true,
				                alpha: 45,
				                beta: 10
				            }
				        },
				        title: {
				            text: "'.$x.'学期  '.$y.'(班级) 学业预警统计图",
				            style: {
										color: "black",
										fontWeight:"bold",
										fontSize:"25"
									}
				            
				        },
				        tooltip: {
				            //pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
				        },
				        plotOptions: {
				            pie: {
				                allowPointSelect: true,
				                cursor: "pointer",
				                depth:35,
				                dataLabels: {
				                    enabled: true,
				                    format: "{point.name}"
				                }
				            }
				        },
	            		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
				        series: [{
				            type: "pie",
				            name: "人数",
				            data: [ 
				            	{
								name: "II级预警",
								color:"rgba( 255, 165, 0,0.6)",
								y: '.$IIJS.'
								}, 
								{
								name: "III级预警",
								color: "rgba(151,187,255,0.75)",
								y: '.$IIIJS.'
								}, 		
			            		{
			                    name: "I级预警",
			               		color: "rgba(255,10,10,0.6)",
			                    y: '.$IJS.',
			                    sliced: true,
			                    selected: true
				                },					                	
				            ]
				        }]
				    });
				});';
			echo '</script>';
				
		  }
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////   (3)       选择年级的各种情况             //////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

////////////       选择年级     未选择学期          ////////////////////////////////////////////////////////////////////////////////	
	//年级      不带学期      学校图表统计
	public function njxxChartAction(){
		$str=$this->request->get("nj");
		//空格占两个字节
		$x=substr($str,0,8);
		//echo $x;
		try{
			$conn =$this->getDI()->get("db");
			//查询学年期是否为空， 为空  说明后台数据正在更新.......
			$sql="select distinct xnq from XQView order by xnq desc";
			$result = $conn->query($sql);
			$num=0;
			while($usr=$result->fetch()){
				$num=$usr[0];
			}
			if($num==0){
				echo '<br/><br/><br/>';
				echo '<font color="#4B0082">&nbsp&nbsp后台数据正在更新，请耐心等待....................</font>';
			}
			else{
				//全校  柱状图参数
				$sql1="select XY,sum(IJS),sum(IIJS),sum(IIIJS) from XUEXIAO where NJ='".$x."' and XY is not null group by xy order by xy";
				//全校   各学期    折线图参数
				$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQXUEXIAO where NJ='".$x."' group by XNQ order by xnq";
				//$sql3="select NJ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQXUEXIAO  group by XNQ order by xnq";
				$result1 = $conn->query($sql1);
				$result2 = $conn->query($sql2);
				$i=0;
				$XY= Array();
				$IJS= Array();
				$IIJS= Array();
				$IIIJS= Array();
				while($usr=$result1->fetch()){
					$XY[$i]=$usr[0];
					//必须强制转换成整形
					$IJS[$i]=intval($usr[1]);
					$IIJS[$i]=intval($usr[2]);
					$IIIJS[$i]=intval($usr[3]);
					$i++;
				}
				//全校各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
				$k=0;
				$ZXNQ= Array();
				$ZIJS= Array();
				$ZIIJS= Array();
				$ZIIIJS= Array();
				while($usr=$result2->fetch()){
					$ZXNQ[$k]=$usr[0];
					$ZIJS[$k]=intval($usr[1]);
					$ZIIJS[$k]=intval($usr[2]);
					$ZIIIJS[$k]=intval($usr[3]);
					$k++;
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
					echo '<font color="#4B0082">本年级全校 没有预警信息！</font>';
				}else{
					// 必须先用 jason 进行封装！！！
					$rtn = json_encode($XY);
					$arr1 =json_encode($IJS);
					$arr2 =json_encode($IIJS);
					$arr3 =json_encode($IIIJS);
					//折线图 参数封装
					$zrtn =json_encode($ZXNQ);
					$zarr1 =json_encode($ZIJS);
					$zarr2 =json_encode($ZIIJS);
					$zarr3 =json_encode($ZIIIJS);
	
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
					//highchart 柱状图和饼图在一起       examples/combo
					echo '<center>';
					echo '<div id="tu" style="float:left  width:100% height:100%" >';
					echo '</div>';
					echo '<div id="linetu" style="float:left  width:100% height:100%" >';
					echo '</div>';
					echo '</center>';
				 echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text: "'.$x.' 全校各学院  学业预警统计图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories: '.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警人数"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
					
						labels: {
							items: [{
								html: "全校预警统计图",
								style: {
									left: "0px",
									top: "-50px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				 echo 'tu.width  = window.innerWidth/1.3;
				tu.height = window.innerHeight/0.7;';
				 echo ' </script>';
				 //折线图
				 echo'<script type="text/javascript">
			 		$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text: "'.$x.'  全校各学期  学业预警统计折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
		        },
	
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警人数"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
	
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
				
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
				
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
			 	</script>';
				}
		 }
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	
	}
	
	//年级    不带学期      学院  图表统计
	public function njxyChartAction(){
		$str=$this->request->get("xy");
		//空格占两个字节
		$nj=substr($str,0,8);
		$x=substr($str,8);  
		try{
			$conn =$this->getDI()->get("db");
			//学院 柱状图参数
			$sql1="select ZY,sum(IJS),sum(IIJS),sum(IIIJS) from XUEYUAN where NJ='".$nj."' and xy like '%" . $x ."' group by zy order by zy";
			//某学院   各学期    折线图参数
			$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQXUEYUAN where NJ='".$nj."' and  xy like '%".$x."%' group by XNQ order by xnq";
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$i=0;
			$ZY= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			while($usr=$result1->fetch()){
				$ZY[$i]=$usr[0];
				$IJS[$i]=intval($usr[1]);
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
				$i++;
			}
	
			//某学院     各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			while($usr=$result2->fetch()){
				$ZXNQ[$k]=$usr[0];
				$ZIJS[$k]=intval($usr[1]);
				$ZIIJS[$k]=intval($usr[2]);
				$ZIIIJS[$k]=intval($usr[3]);
				$k++;
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
				echo '<font color="#4B0082">'.$nj.' '. $x.'  没有预警信息！</font>';
			}
			else{
				// 必须先用 jason 进行封装！！！
				$rtn = json_encode($ZY);
				$arr1 =json_encode($IJS);
				$arr2 =json_encode($IIJS);
				$arr3 =json_encode($IIIJS);
				//折线图 参数封装
				$zrtn =json_encode($ZXNQ);
				$zarr1 =json_encode($ZIJS);
				$zarr2 =json_encode($ZIIJS);
				$zarr3 =json_encode($ZIIIJS);
	
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
	
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$nj.' '.$x.'各专业  学业 预警统计图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警人数"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
	
						labels: {
							items: [{
								html:"学院预警统计图",
								style: {
									left: "0px",
									top: "-50px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo 'tu.width  = window.innerWidth/1.3;
						  tu.height = window.innerHeight/0.7;';
				echo '</script>';
				//折线图
				echo'<script type="text/javascript">
			$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text:"'.$nj.' '.$x.'各学期 学业预警统计折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
		        },
	
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警人数"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
	
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
			
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
			
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
				</script>';
		 }
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//年级      不带学期      专业    图表统计，饼图状图可以一起画出来, ----------------有的专业 班级 特别多！eg：理学院 财务管理！！！
	public function njzyChartAction(){
		
		$str=$this->request->get("zy");
		//空格占两个字节
		$nj=substr($str,0,8);
		$x=substr($str,8); 
		try{
			$conn =$this->getDI()->get("db");
			//专业    柱状图参数
			$sql1="select BJ,sum(IJS),sum(IIJS),sum(IIIJS) from ZHUANYE where  NJ='".$nj."' and zy like'%".$x."' group by bj order by bj ";
			//某专业    各学期  折线图参数
			$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQZHUANYE where NJ='".$nj."' and zy like '%" .$x."' group by XNQ order by xnq";
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$i=0;
			$BJ= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			while($usr=$result1->fetch()){
				$BJ[$i]=$usr[0];
				$IJS[$i]=intval($usr[1]);
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
				$i++;
			}
	
			//某专业     各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			while($usr=$result2->fetch()){
				$ZXNQ[$k]=$usr[0];
				$ZIJS[$k]=intval($usr[1]);
				$ZIIJS[$k]=intval($usr[2]);
				$ZIIIJS[$k]=intval($usr[3]);
				$k++;
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
				echo '<font color="#4B0082">'. $nj.' '. $x.'(专业)没有预警信息！</font>';
			}
			else{
				// 必须先用 jason 进行封装！！！
				$rtn = json_encode($BJ);
				$arr1 =json_encode($IJS);
				$arr2 =json_encode($IIJS);
				$arr3 =json_encode($IIIJS);
				//折线图 参数封装
				$zrtn =json_encode($ZXNQ);
				$zarr1 =json_encode($ZIJS);
				$zarr2 =json_encode($ZIIJS);
				$zarr3 =json_encode($ZIIIJS);
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
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$nj.' '.$x.'专业各班级学业预警统计图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警人数"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
	
						labels: {
							items: [{
								html:"专业预警统计图",
								style: {
									left: "-15px",
									top: "-23px",
									color: "black",
								    fontSize:"13"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [5, 10],
							size: 70,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo '</script>';
				//折线图
				echo'<script type="text/javascript">
			$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text:"'.$nj.' '.$x.'专业各学期 学业预警统计折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
		        },
	
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警人数"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
	
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
	
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
	
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
			  </script>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//不带学期      班级  饼图   折线图
	public function njbjChartAction(){
		$str=$this->request->get("bj");
		//空格占两个字节
		$nj=substr($str,0,8);
		$x=substr($str,8);
		try{
			$conn =$this->getDI()->get("db");
			//班级    柱状图参数
			$sql=" select  sum(IJS),sum(IIJS),sum(IIIJS)  from ZHUANYE where (nj='".$nj ."' and bj like '%" . $x ."') group by bj";
			$result = $conn->query($sql);
			$IJS= 0;
			$IIJS=0;
			$IIIJS=0;
			while($usr=$result->fetch()){
				$IJS=intval($usr[0]);
				$IIJS=intval($usr[1]);
				$IIIJS=intval($usr[2]);
			}
			$IJS =json_encode($IJS);
			$IIJS =json_encode($IIJS);
			$IIIJS =json_encode($IIIJS);
	
			//////////////////////////////班级的每个学年期的统计：---画折线图使用。
			//先把学期选择出来。 数据库中表格：XNQZHUANYE
			$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQZHUANYE where nj='".$nj ."' and bj like '%".$x."' group by XNQ order by xnq";
			//班级各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
			$result2 = $conn->query($sql2);
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			while($usr=$result2->fetch()){
				$ZXNQ[$k]=$usr[0];
				$ZIJS[$k]=intval($usr[1]);
				$ZIIJS[$k]=intval($usr[2]);
				$ZIIIJS[$k]=intval($usr[3]);
				$k++;
			}
			//折线图 参数封装
			$zrtn =json_encode($ZXNQ);
			$zarr1 =json_encode($ZIJS);
			$zarr2 =json_encode($ZIIJS);
			$zarr3 =json_encode($ZIIIJS);
				
			//判断该班是否有警示信息
			if($IJS==0 && $IIJS==0 && $IIIJS==0){
				echo '<br/>';
				echo '<font color="#4B0082">'. $nj.' '. $x.'班级  没有预警信息！</font>';
			}
			else{
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">';
				//饼图
				echo '$(function () {
				    $("#tu").highcharts({
				        chart: {
				            type: "pie",
				            options3d: {
				                enabled: true,
				                alpha: 45,
				                beta: 10
				            }
				        },
				        title: {
				            text: "'. $nj.' '.$x.'(班级) 学业预警统计图",
				            style: {
										color: "black",
										fontWeight:"bold",
										fontSize:"25"
									}
	
				        },
				        tooltip: {
				            //pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
				        },
				        plotOptions: {
				            pie: {
				                allowPointSelect: true,
				                cursor: "pointer",
				                depth:35,
				                dataLabels: {
				                    enabled: true,
				                    format: "{point.name}"
				                }
				            }
				        },
	            		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
				        series: [{
				            type: "pie",
				            name: "人数",
				            data: [
				            	{
								name: "II级预警",
								color:"rgba( 255, 165, 0,0.6)",
								y: '.$IIJS.'
								},
								{
								name: "III级预警",
								color: "rgba(151,187,255,0.75)",
								y: '.$IIIJS.'
								},
			            		{
			                    name: "I级预警",
			               		color: "rgba(255,10,10,0.6)",
			                    y: '.$IJS.',
			                    sliced: true,
			                    selected: true
				                },
				            ]
				        }]
				    });
				});';
				echo '</script>';
				//折线图,每学期统计，班级已经固定属于某个学年了。。。
				echo'<script type="text/javascript">
				$(function () {
			    $("#linetu").highcharts({
			        chart: {
			            type: "line"
			        },
			        title: {
			            text:"'. $nj.' '.$x.'班级 各学期  学业预警统计折线图",
				 		style: {
										color: "black",
										fontWeight:"bold",
										fontSize:"25"
									}
			        },
	
			        xAxis: {
				 		categories:'.$zrtn.'
			        },
			        yAxis: {
			            title: {
			                text: "预警人数"
			            },
			            min: 0
			        },
			 		credits: {
			          			enabled:false
									},
							exporting: {
			            		enabled:true
							},
			        plotOptions: {
			            spline: {
			                marker: {
			                    enabled: true
			                }
			            }
			        },
	
			        series: [{
								name: "I级预警",
								color: "rgba(255,10,10,0.6)",
								data: '.$zarr1.'
							}, {
	
								name: "II级预警",
								color:"rgba( 255, 165, 0,0.6)",
								data: '.$zarr2.'
							}, {
	
								name: "III级预警",
								color: "rgba(151,187,255,0.75)",
								data:'.$zarr3.'
							}]
					    });
					});
						</script>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	
	
//////////////////////////////////   (4) 选择年级     并且    选择学期       //////////////////////////////////////////////////////////

	//带学期  年级      学校图表统计
	public function njxnqxxChartAction(){
		$str=$this->request->get("xnq");
		
		//空格占两个字节
		$nj=substr($str,0,8);
		$x=substr($str,8);
		//echo $x;
		try{
			$conn =$this->getDI()->get("db");
			//查询学年期是否为空， 为空  说明后台数据正在更新.......
			$sql="select distinct xnq from XQView order by xnq desc";
			$result = $conn->query($sql);
			$num=0;
			while($usr=$result->fetch()){
				$num=$usr[0];
			}
			if($num==0){
				echo '<br/><br/><br/>';
				echo '<font color="#4B0082">&nbsp&nbsp后台数据正在更新，请耐心等待....................</font>';
			}
			else{
				//全校  柱状图参数$sql1="select ZY,sum(IJS),sum(IIJS),sum(IIIJS) from XUEYUAN where xy like '%" . $x ."' group by zy order by zy";
				$sql1="select XY,sum(IJS),sum(IIJS),sum(IIIJS) from XNQXUEXIAO where NJ='".$nj."' and xnq like '%" . $x ."' group by xy order by xy";
				$result1 = $conn->query($sql1);
				$i=0;
				$XY= Array();
				$IJS= Array();
				$IIJS= Array();
				$IIIJS= Array();
				while($usr=$result1->fetch()){
					$XY[$i]=$usr[0];
					//必须强制转换成整形
					$IJS[$i]=intval($usr[1]);
					$IIJS[$i]=intval($usr[2]);
					$IIIJS[$i]=intval($usr[3]);
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
					echo '<font color="#4B0082">本年级本学期全校 没有预警信息！</font>';
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
					//highchart 柱状图和饼图在一起       examples/combo
					echo '<center>';
					echo '<div id="tu" style="float:left  width:100% height:100%" >';
					echo '</div>';
					echo '</center>';
				 echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text: "'.$nj.' '.$x.'学期  全校各学院  学业预警统计图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories: '.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警人数"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
					
						labels: {
							items: [{
								html: "全校预警统计图",
								style: {
									left: "0px",
									top: "-50px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				 echo 'tu.width  = window.innerWidth/1.3;
				tu.height = window.innerHeight/0.7;';
				 echo ' </script>';
				 
				}
		 }
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	
	}
	
	//带学期   年级      学院  图表统计
	public function njxnqxyChartAction(){
		$str=$this->request->get("xnq");
		//空格占两个字节
		$nj=substr($str,0,8);
		$x=substr($str,8,11);
		$y=substr($str,19);
		
		try{
			$conn =$this->getDI()->get("db");
			//学院    柱状图参数
			$sql1="select  ZY,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQXUEYUAN where (NJ='".$nj."' and xnq='" .$x."' and xy like '%" . $y ."') group by zy order by zy";
			$result1 = $conn->query($sql1);
			$i=0;
			$ZY= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			while($usr=$result1->fetch()){
				$ZY[$i]=$usr[0];
				$IJS[$i]=intval($usr[1]);
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
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
				echo '<font color="#4B0082">'.$nj.' '.$x.'学期'.$y.'没有预警信息！</font>';
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
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$nj.' '.$x.'学期 '.$y.'各专业学业预警统计图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警人数"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
	
						labels: {
							items: [{
								html:"学院预警统计图",
								style: {
									left: "-15px",
									top: "-25px",
									color: "black",
								    fontSize:"13"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [5, 10],
							size: 80,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo 'tu.width  = window.innerWidth/1.3;
						  tu.height = window.innerHeight/0.7;';
				echo '</script>';
				
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//带学期    年级    专业    图表统计
	public function njxnqzyChartAction(){
		$str=$this->request->get("xnq");
		//空格占两个字节
		$nj=substr($str,0,8);
		$x=substr($str,8,11);
		$y=substr($str,19);
		try{
			$conn =$this->getDI()->get("db");
			//专业     柱状图参数
			$sql1="select  BJ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQZHUANYE where (nj= '" . $nj ."' and xnq='" . $x ."' and zy like '%" . $y ."') group by bj order by bj";
			$result1 = $conn->query($sql1);
			$i=0;
			$BJ= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			while($usr=$result1->fetch()){
				$BJ[$i]=$usr[0];
				$IJS[$i]=intval($usr[1]);
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
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
				echo '<font color="#4B0082">'.$nj.' '.$x.'学期'.$y.'(专业)没有预警信息！</font>';
			}else{
				/* //判断班级的个数
				 while($i<6){
				 	
					$BJ[$i]="0";
					$IJS[$i]=0;
					$IIJS[$i]=0;
					$IIIJS[$i]=0;
					$i++;
						
					} */
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
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$nj.' '.$x.'学期 '.$y.'专业各班级学业预警统计图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"21"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警人数"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
	
						labels: {
							items: [{
								html:"专业预警统计图",
								style: {
									left: "-15px",
									top: "-20px",
									color: "black",
								    fontSize:"13"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [5, 10],
							size: 70,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo '</script>';
				
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//带学期   年级      班级   图表统计
	public function njxnqbjChartAction(){
		$str=$this->request->get("xnq");
		//空格占两个字节
		$nj=substr($str,0,8);
		$x=substr($str,8,11);
		$y=substr($str,19);
		//echo $nj;
		try{
			$conn =$this->getDI()->get("db");
			//班级     b圆饼 图参数，不应该从 MY_XYJS2 表中 查找，应该在 XNQZHUANYE 表中。
			$sql="select sum(IJS),sum(IIJS),sum(IIIJS)  from XNQZHUANYE where (nj='".$nj ."' and xnq='".$x."' and bj like '%".$y."') group by bj";
			$result = $conn->query($sql);
			/* $sql1="select COUNT(JSJB) from MY_XYJS2 where JSJB like '%Ⅰ级警示%' and nj= '" . $nj ."' and xnq='" . $x ."' and bj like '%" . $y ."' ";
			$sql2="select COUNT(JSJB) from MY_XYJS2 where JSJB like '%Ⅱ级警示%' and nj= '" . $nj ."' and xnq='" . $x ."' and bj like '%" . $y ."' ";
			$sql3="select COUNT(JSJB) from MY_XYJS2 where JSJB like '%Ⅲ级警示%' and nj= '" . $nj ."' and xnq='" . $x ."' and bj like '%" . $y ."' ";
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$result3 = $conn->query($sql3); */
			$IJS= 0;
			$IIJS=0;
			$IIIJS=0;
			while($usr=$result->fetch()){
				$IJS=intval($usr[0]);
				$IIJS=intval($usr[1]);
				$IIIJS=intval($usr[2]);
			}
			/* while($usr=$result1->fetch()){
				$IJS+=intval($usr[0]);
			}
			while($usr=$result2->fetch()){
				$IIJS+=intval($usr[0]);
			}
	
			while($usr=$result3->fetch()){
				$IIIJS+=intval($usr[0]);
			} */
		 /* echo $IJS;
			echo $IIJS;
			echo $IIIJS;  */
			//判断各班是否有警示信息
			if($IJS==0 && $IIJS==0 && $IIIJS==0){
				echo '<br/>';
				echo '<font color="#4B0082">'.$nj.' '.$x.'学期'.$y.'班级  没有预警信息！</font>';
			}
			else{
				//柱状图    参数封装
				
				$IJS =json_encode($IJS);
				$IIJS =json_encode($IIJS);
				$IIIJS =json_encode($IIIJS);
	
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">';
				//饼图
				echo '$(function () {
				    $("#tu").highcharts({
				        chart: {
				            type: "pie",
				            options3d: {
				                enabled: true,
				                alpha: 45,
				                beta: 10
				            }
				        },
				        title: {
				            text: "'.$nj.' '.$x.'学期  '.$y.'(班级) 学业预警统计图",
				            style: {
										color: "black",
										fontWeight:"bold",
										fontSize:"25"
									}
				        },
				        tooltip: {
				            //pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
				        },
				        plotOptions: {
				            pie: {
				                allowPointSelect: true,
				                cursor: "pointer",
				                depth:35,
				                dataLabels: {
				                    enabled: true,
				                    format: "{point.name}"
				                }
				            }
				        },
	            		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
				        series: [{
				            type: "pie",
				            name: "人数",
				            data: [
				            	{
								name: "II级预警",
								color:"rgba( 255, 165, 0,0.6)",
								y: '.$IIJS.'
								},
								{
								name: "III级预警",
								color: "rgba(151,187,255,0.75)",
								y: '.$IIIJS.'
								},
			            		{
			                    name: "I级预警",
			               		color: "rgba(255,10,10,0.6)",
			                    y: '.$IJS.',
			                    sliced: true,
			                    selected: true
				                },
				            ]
				        }]
				    });
				});';
				echo '</script>';
	
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}

//------------------------------------------------------------------------------------------------
//---------------------------------------------比例统计图--------------------------------------------
//----------------------------------------------------------------------------------------------
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////// (1) 不带学期的   图表统计       /////////////////////////////////////////////////////
	
	//不带学期      学校图表统计
	public function BxxChartAction(){
		//$str=$this->request->get("xx");
		try{
			$conn =$this->getDI()->get("db");
			//查询学年期是否为空， 为空  说明后台数据正在更新.......
			$sql="select distinct xnq from XQView order by xnq desc";
			$result = $conn->query($sql);
			$num=0;
			while($usr=$result->fetch()){
				$num=$usr[0];
			}
			if($num==0){
				echo '<br/><br/><br/>';
				echo '<font color="#4B0082">&nbsp&nbsp后台数据正在更新，请耐心等待....................</font>';
			}
			else{
				//全校  柱状图参数
				$sql1="select XY,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS) from XUEXIAO where XY is not null group by xy order by xy";
				//全校   各学期    折线图参数
				$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS)  from XNQXUEXIAO  group by XNQ order by xnq";
				//全校各年级     柱状图参数
				$sql3="select NJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS) from XUEXIAO where NJ is not null group by nj order by nj";
				$result1 = $conn->query($sql1);
				$result2 = $conn->query($sql2);
				$result3 = $conn->query($sql3);
				$i=0;
				$XY= Array();
				$IJS= Array();
				$IIJS= Array();
				$IIIJS= Array();
				$BLIJS= Array();
				$BLIIJS= Array();
				$BLIIIJS= Array();
				$ZRS= Array();
				while($usr=$result1->fetch()){
					$XY[$i]=$usr[0];
					//必须强制转换成整形
					$ZRS[$i]=doubleval($usr[4]);
					$IJS[$i]=intval($usr[1]);
					$BLIJS[$i]=round($usr[1]/$ZRS[$i],4); // 比例数值。
					$IIJS[$i]=intval($usr[2]);
					$BLIIJS[$i]=round($usr[2]/$ZRS[$i],4);
					$IIIJS[$i]=intval($usr[3]);
					$BLIIIJS[$i]=round($usr[3]/$ZRS[$i],4);
					$i++;
				}
				//全校各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
				$k=0;
				$ZXNQ= Array();
				$ZIJS= Array();
				$ZIIJS= Array();
				$ZIIIJS= Array();
				$ZZRS= Array();
				while($usr=$result2->fetch()){
					$ZXNQ[$k]=strval($usr[0]);
					$ZZRS[$k]=doubleval($usr[4]);
					$ZIJS[$k]=round($usr[1]/$ZZRS[$k],4);
					$ZIIJS[$k]=round($usr[2]/$ZZRS[$k],4);
					$ZIIIJS[$k]=round($usr[3]/$ZZRS[$k],4);
					$k++;
				}
					
				//全校  各年级  柱状图  参数
				$k=0;
				$NJ= Array();
				$NIJS= Array();
				$NIIJS= Array();
				$NIIIJS= Array();
				$NZRS= Array();
				while($usr=$result3->fetch()){
					$NJ[$k]=$usr[0];
					$NZRS[$k]=doubleval($usr[4]);
					//必须强制转换成整形
					$NIJS[$k]=round($usr[1]/$NZRS[$k],4);
					$NIIJS[$k]=round($usr[2]/$NZRS[$k],4);
					$NIIIJS[$k]=round($usr[3]/$NZRS[$k],4);
					$k++;
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
					echo '<font color="#4B0082">本学期全校没有预警信息！</font>';
				}else{
					// 必须先用 jason 进行封装！！！
					$rtn = json_encode($XY);
					$arr1 =json_encode($BLIJS);
					$arr2 =json_encode($BLIIJS);
					$arr3 =json_encode($BLIIIJS);
					//折线图 参数封装
					$zrtn =json_encode($ZXNQ);
					$zarr1 =json_encode($ZIJS);
					$zarr2 =json_encode($ZIIJS);
					$zarr3 =json_encode($ZIIIJS);
	
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
	
					// 全校  各年级   参数封装   ！！！
					$nrtn = json_encode($NJ);
					$narr1 =json_encode($NIJS);
					$narr2 =json_encode($NIIJS);
					$narr3 =json_encode($NIIIJS);
					//highchart 柱状图和饼图在一起       examples/combo
					echo '<center>';
					echo '<div id="tu" style="float:left  width:100% height:100%" >';
					echo '</div>';
					echo '<div id="linetu" style="float:left  width:100% height:100%" >';
					echo '</div>';
					echo '<div id="ntu" style="float:left  width:100% height:100%" >';
					echo '</div>';
					echo '</center>';
				 echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text: "全校各学院学业预警比例图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories: '.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警比例"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
					
						labels: {
							items: [{
								html: "全校预警统计图",
								style: {
									left: "0px",
									top: "-35px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				 echo ' </script>';
				 //折线图
				 echo'<script type="text/javascript">
			 		$(function () {
		   		 $("#linetu").highcharts({
			        chart: {
			            type: "line"
			        },
		        title: {
		            text: "全校各学期学业预警比例折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
		        },
	
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警比例"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
	
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
				
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
				
							name: "III级警示",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
			 	</script>';
				 //////////// 以年级为横轴的柱状图-------------------------------------------------------------
				 echo'<script type="text/javascript">
			 		$(function () {
					$("#ntu").highcharts({
						title: {
							text: "全校各年级学业预警比例图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories: '.$nrtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警比例"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
					
						labels: {
							items: [{
								html: "全校预警统计图",
								style: {
									left: "0px",
									top: "-35px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$narr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$narr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$narr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				 echo ' </script>';
				}
		 }
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	
	}
	
	//不带学期      学院  图表统计
	public function BxyChartAction(){
		$x=$this->request->get("xy");
		//echo $x;
		try{
			$conn =$this->getDI()->get("db");
			//学院 柱状图参数
			$sql1="select ZY,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS) from XUEYUAN where xy like '%" . $x ."' group by zy order by zy";
			//某学院   各学期    折线图参数
			$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS)  from XNQXUEYUAN where xy like '%".$x."%' group by XNQ order by xnq";
			$sql3="select NJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS) from XUEYUAN where NJ is not null group by nj order by nj";
	
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$result3 = $conn->query($sql3);
			//柱状图   参数
			$i=0;
			$ZY= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			$BLIJS= Array();
			$BLIIJS= Array();
			$BLIIIJS= Array();
			$ZRS= Array();
			while($usr=$result1->fetch()){
				$ZY[$i]=$usr[0];
				$ZRS[$i]=doubleval($usr[4]);
				$IJS[$i]=intval($usr[1]);
				$BLIJS[$i]=round($usr[1]/$ZRS[$i],4); // 比例数值。
				$IIJS[$i]=intval($usr[2]);
				$BLIIJS[$i]=round($usr[2]/$ZRS[$i],4);
				$IIIJS[$i]=intval($usr[3]);
				$BLIIIJS[$i]=round($usr[3]/$ZRS[$i],4);
				$i++;
			}
	
			//某学院     各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			$ZZRS= Array();
			while($usr=$result2->fetch()){
				$ZXNQ[$k]=$usr[0];
				$ZZRS[$k]=doubleval($usr[4]);
				$ZIJS[$k]=round($usr[1]/$ZZRS[$k],4);
				$ZIIJS[$k]=round($usr[2]/$ZZRS[$k],4);
				$ZIIIJS[$k]=round($usr[3]/$ZZRS[$k],4);
				$k++;
			}
			//全校  各年级  柱状图  参数
			$k=0;
			$NJ= Array();
			$NIJS= Array();
			$NIIJS= Array();
			$NIIIJS= Array();
			$NZRS= Array();
			while($usr=$result3->fetch()){
				$NJ[$k]=$usr[0];
				$NZRS[$k]=doubleval($usr[4]);
				//必须强制转换成整形
				$NIJS[$k]=round($usr[1]/$NZRS[$k],4);
				$NIIJS[$k]=round($usr[2]/$NZRS[$k],4);
				$NIIIJS[$k]=round($usr[3]/$NZRS[$k],4);
				$k++;
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
				echo '<font color="#4B0082">'. $x.'没有预警信息！</font>';
			}
			else{
				// 必须先用 jason 进行封装！！！
				$rtn = json_encode($ZY);
				$arr1 =json_encode($BLIJS);
				$arr2 =json_encode($BLIIJS);
				$arr3 =json_encode($BLIIIJS);
				
				//折线图 参数封装
				$zrtn =json_encode($ZXNQ);
				$zarr1 =json_encode($ZIJS);
				$zarr2 =json_encode($ZIIJS);
				$zarr3 =json_encode($ZIIIJS);
	
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
				// 全校  各年级   参数封装   ！！！
				$nrtn = json_encode($NJ);
				$narr1 =json_encode($NIJS);
				$narr2 =json_encode($NIIJS);
				$narr3 =json_encode($NIIIJS);
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '<div id="ntu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$x.'各专业 学业预警比例图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警比例"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
	
						labels: {
							items: [{
								html:"学院预警统计图",
								style: {
									left: "0px",
									top: "-35px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo '</script>';
				//折线图
			echo'<script type="text/javascript">
			$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text:"'.$x.'各学期 学业预警比例折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
		        },
	
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警比例"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
	
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
			
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
			
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
				</script>';
				/// 以年级为横轴
				echo'<script type="text/javascript">
			 		$(function () {
					$("#ntu").highcharts({
						title: {
							text:"'.$x.'各年级 学业预警比例图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories:'.$nrtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警比例"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
	
						labels: {
							items: [{
								html:"学院预警统计图",
								style: {
									left: "0px",
									top: "-35px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$narr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$narr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$narr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo 'tu.width  = window.innerWidth/1.3;
						  tu.height = window.innerHeight/0.7;';
				echo '</script>';
		 }
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//不带学期      专业    图表统计，饼图状图可以一起画出来, ----------------有的专业 班级 特别多！eg：理学院 财务管理！！！
	public function BzyChartAction(){
		$x=$this->request->get("zy");
		try{
			$conn =$this->getDI()->get("db");
			//专业    柱状图参数
			$sql1="select BJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS) from ZHUANYE where zy like'%".$x."' group by bj order by bj ";
			//某专业    各学期  折线图参数
			$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS)  from XNQZHUANYE where zy like '%" .$x."' group by XNQ order by xnq";
			$sql3="select NJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS) from ZHUANYE where zy like '%" .$x."' and NJ is not null group by nj order by nj";
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$result3 = $conn->query($sql3);
				
			$i=0;
			$BJ= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			$BLIJS= Array();
			$BLIIJS= Array();
			$BLIIIJS= Array();
			$ZRS= Array();
			while($usr=$result1->fetch()){
				$BJ[$i]=$usr[0];
				$ZRS[$i]=doubleval($usr[4]);
				$IJS[$i]=intval($usr[1]);
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
				$BLIJS[$i]=round($usr[1]/$ZRS[$i],4); // 比例数值。
				$BLIIJS[$i]=round($usr[2]/$ZRS[$i],4);
				$BLIIIJS[$i]=round($usr[3]/$ZRS[$i],4);
				$i++;
			}
	
			//某专业     各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			$ZZRS= Array();
			while($usr=$result2->fetch()){
				$ZXNQ[$k]=$usr[0];
				$ZZRS[$k]=doubleval($usr[4]);
				$ZIJS[$k]=round($usr[1]/$ZZRS[$k],4);
				$ZIIJS[$k]=round($usr[2]/$ZZRS[$k],4);
				$ZIIIJS[$k]=round($usr[3]/$ZZRS[$k],4);
				$k++;
			}
			$k=0;
			$NJ= Array();
			$NIJS= Array();
			$NIIJS= Array();
			$NIIIJS= Array();
			$NZRS= Array();
			while($usr=$result3->fetch()){
				$NJ[$k]=$usr[0];
				//必须强制转换成整形
				$NZRS[$k]=doubleval($usr[4]);
				$NIJS[$k]=round($usr[1]/$NZRS[$k],4);
				$NIIJS[$k]=round($usr[2]/$NZRS[$k],4);
				$NIIIJS[$k]=round($usr[3]/$NZRS[$k],4);
				$k++;
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
				echo '<font color="#4B0082">'. $x.'(专业)没有预警信息！</font>';
			}
			else{
				// 必须先用 jason 进行封装！！！
				$rtn = json_encode($BJ);
				$arr1 =json_encode($BLIJS);
				$arr2 =json_encode($BLIIJS);
				$arr3 =json_encode($BLIIIJS);
				//折线图 参数封装
				$zrtn =json_encode($ZXNQ);
				$zarr1 =json_encode($ZIJS);
				$zarr2 =json_encode($ZIIJS);
				$zarr3 =json_encode($ZIIIJS);
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
				// 全校  各年级   参数封装   ！！！
				$nrtn = json_encode($NJ);
				$narr1 =json_encode($NIJS);
				$narr2 =json_encode($NIIJS);
				$narr3 =json_encode($NIIIJS);
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="ntu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '</center>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$x.'专业各班级 学业预警比例图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警比例"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
	
						labels: {
							items: [{
								html:"专业预警统计图",
								style: {
									left: "0px",
									top: "-35px",
									color: "black",
								    fontSize:"14"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo '</script>';
				//折线图
				echo'<script type="text/javascript">
			$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text:"'.$x.'专业 各学期 学业预警比例折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
		        },
	
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警比例"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
	
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
	
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
	
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
			  </script>';
				/// 以年级为横轴：
				echo'<script type="text/javascript">
			 		$(function () {
					$("#ntu").highcharts({
						title: {
							text:"'.$x.'专业各年级 学业预警比例图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
						},
						xAxis: {
							categories:'.$nrtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警比例"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
	
						labels: {
							items: [{
								html:"专业预警统计图",
								style: {
									left: "0px",
									top: "-35px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$narr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$narr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$narr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo '</script>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	//不带学期      班级  只要 图表统计？柱状图也可以要？先不画。。。
	
	public function BbjChartAction(){
		$x=$this->request->get("bj");
		try{
			
			$conn =$this->getDI()->get("db");
			//班级    柱状图参数
			$sql1=" select  sum(IJS),sum(IIJS),sum(IIIJS)  from ZHUANYE where (bj like '%" . $x ."') group by bj";
			$result1 = $conn->query($sql1);
			$sql2=" select  sum(ZRS)  from ZHUANYE where bj like '%" . $x ."' group by bj";
			$result2 = $conn->query($sql2);
			
			$IJS= 0;
			$IIJS=0;
			$IIIJS=0;
			$ZRS=0;	
			while($usr=$result2->fetch()){
				$ZRS+=doubleval($usr[0]);
			}
			while($usr=$result1->fetch()){
				$IJS+=doubleval($usr[0]);
				$IIJS+=doubleval($usr[1]);
				$IIIJS+=doubleval($usr[2]);
			}
			$NoJS=round(($ZRS-($IJS+$IIJS+$IIIJS))/$ZRS,4); //无警示人数；
			
			$IJS=round($IJS/$ZRS,4);
			$IIJS=round($IIJS/$ZRS,4);
			$IIIJS=round($IIIJS/$ZRS,4);
			
			$IJS =json_encode($IJS);
			$IIJS =json_encode($IIJS);
			$IIIJS =json_encode($IIIJS);
			$NoJS=json_encode($NoJS);
			
			//////////////////////////////班级的每个学年期的统计：---画折线图使用。
			//先把学期选择出来。 数据库中表格：XNQZHUANYE
			$sql1="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQZHUANYE where bj like '%".$x."' group by XNQ order by xnq";
			$result1 = $conn->query($sql1);
			//班级各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
			$sql2=" select  ZRS  from ZHUANYE where bj like '%" . $x ."'";
			$result2 = $conn->query($sql2);
			
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			$ZZRS=0;
			while($usr=$result2->fetch()){
				 $ZZRS+=doubleval($usr[0]);
				 }
			
			while($usr=$result1->fetch()){
				$ZXNQ[$k]=$usr[0];
				$ZIJS[$k]=round($usr[1]/$ZZRS,4);
				$ZIIJS[$k]=round($usr[2]/$ZZRS,4);
				$ZIIIJS[$k]=round($usr[3]/$ZZRS,4);
				$k++;
			}
			
			//折线图 参数封装
			$zrtn =json_encode($ZXNQ);
			$zarr1 =json_encode($ZIJS);
			$zarr2 =json_encode($ZIIJS);
			$zarr3 =json_encode($ZIIIJS);
			//判断该班是否有警示信息
			if($IJS==0 && $IIJS==0 && $IIIJS==0){
				echo '<br/>';
				echo '<font color="#4B0082">'. $x.'班级  没有预警信息！</font>';
			}
			else{
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">';
				//饼图
				echo '$(function () {
				    $("#tu").highcharts({
				        chart: {
				            type: "pie",
				            options3d: {
				                enabled: true,
				                alpha: 45,
				                beta: 10
				            }
				        },
				        title: {
				            text: "'.$x.'(班级) 学业预警比例图",
				            style: {
										color: "black",
										fontWeight:"bold",
										fontSize:"25"
									}
	
				        },
				        tooltip: {
				            //pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
				        },
				        plotOptions: {
				            pie: {
				                allowPointSelect: true,
				                cursor: "pointer",
				                depth:35,
				                dataLabels: {
				                    enabled: true,
				                    format: "{point.name}"
				                }
				            }
				        },
	            		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
				        series: [{
				            type: "pie",
				            name: "比例",
				            data: [
				            	{
								name: "II级预警",
								color:"rgba( 255, 165, 0,0.6)",
								y: '.$IIJS.'
								},
								{
								name: "III级预警",
								color: "rgba(151,187,255,0.75)",
								y: '.$IIIJS.'
								},
								 {
			                    name: "无预警",
			               		color: "rgba(124,252,0,0.6)",
			                    y: '.$NoJS.'
				                },
			            		{
			                    name: "I级预警",
			               		color: "rgba(255,10,10,0.6)",
			                    y: '.$IJS.',
			                    sliced: true,
			                    selected: true
				                },
			              
				            ]
				        }]
				    });
				});';
				echo '</script>';
				//折线图,每学期统计，班级已经固定属于某个学年了。。。
				echo'<script type="text/javascript">
				$(function () {
			    $("#linetu").highcharts({
			        chart: {
			            type: "line"
			        },
			        title: {
			            text:"'.$x.'班级 各学期  学业预警比例折线图",
				 		style: {
										color: "black",
										fontWeight:"bold",
										fontSize:"25"
									}
			        },
	
			        xAxis: {
				 		categories:'.$zrtn.'
			        },
			        yAxis: {
			            title: {
			                text: "预警比例"
			            },
			            min: 0
			        },
			 		credits: {
			          			enabled:false
									},
							exporting: {
			            		enabled:true
							},
			        plotOptions: {
			            spline: {
			                marker: {
			                    enabled: true
			                }
			            }
			        },
	
			        series: [{
								name: "I级预警",
								color: "rgba(255,10,10,0.6)",
								data: '.$zarr1.'
							}, {
	
								name: "II级预警",
								color:"rgba( 255, 165, 0,0.6)",
								data: '.$zarr2.'
							}, {
	
								name: "III级预警",
								color: "rgba(151,187,255,0.75)",
								data:'.$zarr3.'
							}]
					    });
					});
						</script>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	////////////////////////////////////////（2） 带学期     图表统计//////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////
	//带学期      学校图表统计
	public function BxnqxxChartAction(){
		$x=$this->request->get("xnq");
		//echo $x;
		try{
			$conn =$this->getDI()->get("db");
			//查询学年期是否为空， 为空  说明后台数据正在更新.......
			$sql="select distinct xnq from XQView order by xnq desc";
			$result = $conn->query($sql);
			$num=0;
			while($usr=$result->fetch()){
				$num=$usr[0];
			}
			if($num==0){
				echo '<br/><br/><br/>';
				echo '<font color="#4B0082">&nbsp&nbsp后台数据正在更新，请耐心等待....................</font>';
			}
			else{
				//全校  柱状图参数$sql1="select ZY,sum(IJS),sum(IIJS),sum(IIIJS) from XUEYUAN where xy like '%" . $x ."' group by zy order by zy";
				$sql1="select XY,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS) from XNQXUEXIAO where xnq like '%" . $x ."' group by xy order by xy";
				//全校   各学期    折线图参数
				$sql2="select NJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS)  from XNQXUEXIAO where xnq like '%" . $x ."' group by nj order by nj";
				//$sql3="select NJ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQXUEXIAO  group by XNQ order by xnq";
				$result1 = $conn->query($sql1);
				$result2 = $conn->query($sql2);
				$i=0;
				$XY= Array();
				$IJS= Array();
				$IIJS= Array();
				$IIIJS= Array();
				$BLIJS= Array();
				$BLIIJS= Array();
				$BLIIIJS= Array();
				$ZRS= Array();
				while($usr=$result1->fetch()){
					$XY[$i]=$usr[0];
					$ZRS[$i]=doubleval($usr[4]);
					$BLIJS[$i]=round($usr[1]/$ZRS[$i],4); // 比例数值。
					$BLIIJS[$i]=round($usr[2]/$ZRS[$i],4);
					$BLIIIJS[$i]=round($usr[3]/$ZRS[$i],4);
					//必须强制转换成整形
					$IJS[$i]=intval($usr[1]);
					$IIJS[$i]=intval($usr[2]);
					$IIIJS[$i]=intval($usr[3]);
					$i++;
				}
				//全校各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
				$k=0;
				$ZXNQ= Array();
				$ZIJS= Array();
				$ZIIJS= Array();
				$ZIIIJS= Array();

				$ZZRS= Array();
				while($usr=$result2->fetch()){
					$ZXNQ[$k]=$usr[0];

					$ZZRS[$k]=doubleval($usr[4]);
					$ZIJS[$k]=round($usr[1]/$ZZRS[$k],4);
					$ZIIJS[$k]=round($usr[2]/$ZZRS[$k],4);
					$ZIIIJS[$k]=round($usr[3]/$ZZRS[$k],4);
					$k++;
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
					echo '<font color="#4B0082">本学期全校 没有预警信息！</font>';
				}else{
					// 必须先用 jason 进行封装！！！
					$rtn = json_encode($XY);
					$arr1 =json_encode($BLIJS);
					$arr2 =json_encode($BLIIJS);
					$arr3 =json_encode($BLIIIJS);
					//折线图 参数封装
					$zrtn =json_encode($ZXNQ);
					$zarr1 =json_encode($ZIJS);
					$zarr2 =json_encode($ZIIJS);
					$zarr3 =json_encode($ZIIIJS);
	
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
					//highchart 柱状图和饼图在一起       examples/combo
					echo '<center>';
					echo '<div id="tu" style="float:left  width:100% height:100%" >';
					echo '</div>';
					echo '<div id="linetu" style="float:left  width:100% height:100%" >';
					echo '</div>';
					echo '</center>';
				 echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text: "'.$x.'学期全校各学院 学业预警比例图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories: '.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警比例"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
					
						labels: {
							items: [{
								html: "全校预警统计图",
								style: {
									left: "0px",
									top: "-35px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				 echo 'tu.width  = window.innerWidth/1.3;
				tu.height = window.innerHeight/0.7;';
				 echo ' </script>';
				 //折线图
				 echo'<script type="text/javascript">
			 		$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text: "'.$x.'学期全校各年级 学业预警比例折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
		        },
	
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警比例"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
	
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
				
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
				
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
			 	</script>';
				}
		 }
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	
	}
	
	//带学期      学院  图表统计
	public function BxnqxyChartAction(){
		$str=$this->request->get("xnq");
		$x=substr($str,0,11);
		$y=substr($str,11);
		try{
			$conn =$this->getDI()->get("db");
			//学院    柱状图参数
			$sql1="select  ZY,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS)  from XNQXUEYUAN where (xnq='" .$x."' and xy like '%" . $y ."') group by zy order by zy";
			//学院       折线图参数  需要修改为    年级
			$sql2="select NJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS)  from XNQXUEYUAN where (xnq='" .$x."' and xy like '%" . $y ."') group by nj order by nj";
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$i=0;
			$ZY= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			$BLIJS= Array();
			$BLIIJS= Array();
			$BLIIIJS= Array();
			$ZRS= Array();
			while($usr=$result1->fetch()){
				$ZY[$i]=$usr[0];
				$ZRS[$i]=doubleval($usr[4]);
				$BLIJS[$i]=round($usr[1]/$ZRS[$i],4); // 比例数值。
				$BLIIJS[$i]=round($usr[2]/$ZRS[$i],4);
				$BLIIIJS[$i]=round($usr[3]/$ZRS[$i],4);
				$IJS[$i]=intval($usr[1]);
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
				$i++;
			}
	
			//某学院     各个年级    IJS IIJS  IIIJS 的人数，用来画折线图
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			$ZZRS= Array();
			while($usr=$result2->fetch()){
				$ZXNQ[$k]=$usr[0];

				$ZZRS[$k]=doubleval($usr[4]);
				$ZIJS[$k]=round($usr[1]/$ZZRS[$k],4);
				$ZIIJS[$k]=round($usr[2]/$ZZRS[$k],4);
				$ZIIIJS[$k]=round($usr[3]/$ZZRS[$k],4);
				$k++;
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
				echo '<font color="#4B0082">'.$x.'学期'.$y.' 没有预警信息！</font>';
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
					$arr1 =json_encode($BLIJS);
					$arr2 =json_encode($BLIIJS);
					$arr3 =json_encode($BLIIIJS);
				//折线图 参数封装
				$zrtn =json_encode($ZXNQ);
				$zarr1 =json_encode($ZIJS);
				$zarr2 =json_encode($ZIIJS);
				$zarr3 =json_encode($ZIIIJS);
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
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$x.'学期'.$y.'各专业 学业预警比例图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警比例"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
	
						labels: {
							items: [{
								html:"学院预警统计图",
								style: {
									left: "-8px",
									top: "-35px",
									color: "black",
								    fontSize:"14"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo 'tu.width  = window.innerWidth/1.3;
						  tu.height = window.innerHeight/0.7;';
				echo '</script>';
				//折线图
				echo'<script type="text/javascript">
			$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text:"'.$x.'学期各年级 学业预警比例折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
		        },
	
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警比例"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
	
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
			
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
			
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
				</script>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//带学期      专业    图表统计
	public function BxnqzyChartAction(){
		$str=$this->request->get("xnq");
		$x=substr($str,0,11);
		$y=substr($str,11);
		try{
			$conn =$this->getDI()->get("db");
			//专业     柱状图参数
			$sql1="select  BJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS)  from XNQZHUANYE where (xnq='" . $x ."' and zy like '%" . $y ."') group by bj order by bj";
			//专业  折线图参数      需要修改为年级
			$sql2="select nj,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS)  from XNQXUEYUAN where (xnq='" . $x ."' and zy like '%" . $y ."') group by nj order by nj";
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$i=0;
			$BJ= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			$BLIJS= Array();
			$BLIIJS= Array();
			$BLIIIJS= Array();
			$ZRS= Array();
			while($usr=$result1->fetch()){
				$BJ[$i]=$usr[0];

				$ZRS[$i]=doubleval($usr[4]);
				$BLIJS[$i]=round($usr[1]/$ZRS[$i],4); // 比例数值。
				$BLIIJS[$i]=round($usr[2]/$ZRS[$i],4);
				$BLIIIJS[$i]=round($usr[3]/$ZRS[$i],4);
				$IJS[$i]=intval($usr[1]);
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
				$i++;
			}
			//某专业   各个年级      IJS IIJS  IIIJS 的人数，用来画折线图
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			$ZZRS= Array();
			while($usr=$result2->fetch()){
				$ZXNQ[$k]=$usr[0];
					$ZZRS[$k]=doubleval($usr[4]);
					$ZIJS[$k]=round($usr[1]/$ZZRS[$k],4);
					$ZIIJS[$k]=round($usr[2]/$ZZRS[$k],4);
					$ZIIIJS[$k]=round($usr[3]/$ZZRS[$k],4);
				$k++;
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
				echo '<font color="#4B0082">'.$x.'学期'.$y.'(专业)没有预警信息！</font>';
			}else{
				/* //判断班级的个数
				 while($i<6){
				 	
					$BJ[$i]="0";
					$IJS[$i]=0;
					$IIJS[$i]=0;
					$IIIJS[$i]=0;
					$i++;
						
					} */
				// 必须先用 jason 进行封装！！！
				$rtn = json_encode($BJ);
				
				$arr1 =json_encode($BLIJS);
				$arr2 =json_encode($BLIIJS);
				$arr3 =json_encode($BLIIIJS);
				//折线图 参数封装
				$zrtn =json_encode($ZXNQ);
				$zarr1 =json_encode($ZIJS);
				$zarr2 =json_encode($ZIIJS);
				$zarr3 =json_encode($ZIIIJS);
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
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$x.'学期'.$y.'专业各班级 学业预警比例图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警比例"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
	
						labels: {
							items: [{
								html:"专业预警统计图",
								style: {
									left: "-8px",
									top: "-25px",
									color: "black",
								    fontSize:"13"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [5, 10],
							size: 80,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo '</script>';
				//折线图
				echo'<script type="text/javascript">
			$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text:"'.$x.'学期'.$y.'专业各年级 学业预警比例折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
		        },
	
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警比例"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
	
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
	
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
	
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
			  </script>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//带学期      班级   图表统计
	public function BxnqbjChartAction(){
		$str=$this->request->get("xnq");
		$x=substr($str,0,11);
		$y=substr($str,11);
		try{
			$conn =$this->getDI()->get("db");
			//班级     柱状图参数
			$sql1=" select  sum(IJS),sum(IIJS),sum(IIIJS)  from XNQZHUANYE where (xnq like '%" . $x ."' and bj like '%" . $y ."') group by bj";
			$result1 = $conn->query($sql1);
			$sql2=" select  sum(ZRS)  from XNQZHUANYE where xnq like '%".$x."' and bj like '%" . $y ."' group by bj";
			$result2 = $conn->query($sql2);
			$IJS= 0;
			$IIJS=0;
			$IIIJS=0;
			$ZRS=0;
			while($usr=$result2->fetch()){
				$ZRS+=doubleval($usr[0]);
			}
			while($usr=$result1->fetch()){
				$IJS+=doubleval($usr[0]);
				$IIJS+=doubleval($usr[1]);
				$IIIJS+=doubleval($usr[2]);
			}
			 
	
			//判断各班是否有警示信息
			if($IJS==0 && $IIJS==0 && $IIIJS==0){
				echo '<br/>';
				echo '<font color="#4B0082">'.$x.'学期'.$y.'班级  没有预警信息！</font>';
			}
			else{
				//饼图    参数封装
				$NoJS=round(($ZRS-($IJS+$IIJS+$IIIJS))/$ZRS,4); //无警示人数；
			
				$IJS=round($IJS/$ZRS,4);
				$IIJS=round($IIJS/$ZRS,4);
				$IIIJS=round($IIIJS/$ZRS,4);
				
				$IJS =json_encode($IJS);
				$IIJS =json_encode($IIJS);
				$IIIJS =json_encode($IIIJS);
				$NoJS=json_encode($NoJS);
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">';
				//饼图
				echo '$(function () {
				    $("#tu").highcharts({
				        chart: {
				            type: "pie",
				            options3d: {
				                enabled: true,
				                alpha: 45,
				                beta: 10
				            }
				        },
				        title: {
				            text: "'.$x.'学期  '.$y.'(班级) 学业预警统计图",
				            style: {
										color: "black",
										fontWeight:"bold",
										fontSize:"25"
									}
	
				        },
				        tooltip: {
				            //pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
				        },
				        plotOptions: {
				            pie: {
				                allowPointSelect: true,
				                cursor: "pointer",
				                depth:35,
				                dataLabels: {
				                    enabled: true,
				                    format: "{point.name}"
				                }
				            }
				        },
	            		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
				        series: [{
				            type: "pie",
				            name: "人数",
				            data: [
				            	{
								name: "II级预警",
								color:"rgba( 255, 165, 0,0.6)",
								y: '.$IIJS.'
								},
								{
								name: "III级预警",
								color: "rgba(151,187,255,0.75)",
								y: '.$IIIJS.'
								},
								 {
			                    name: "无预警",
			               		color: "rgba(124,252,0,0.6)",
			                    y: '.$NoJS.'
				                },
			            		{
			                    name: "I级预警",
			               		color: "rgba(255,10,10,0.6)",
			                    y: '.$IJS.',
			                    sliced: true,
			                    selected: true
				                },
			                  
				            ]
				        }]
				    });
				});';
				echo '</script>';
	
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	/////////////////////////////////////    （3）     选择年级的各种情况             ///////////////////////////////////////////////////// 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	
	///////////////选择年级     未选择学期  ////////////////////////////////////////////////////////////////////////////////
	//年级      不带学期      学校图表统计
	public function BnjxxChartAction(){
		$str=$this->request->get("nj");
		//空格占两个字节
		$x=substr($str,0,8);
		//echo $x;
		try{
			$conn =$this->getDI()->get("db");
			//查询学年期是否为空， 为空  说明后台数据正在更新.......
			$sql="select distinct xnq from XQView order by xnq desc";
			$result = $conn->query($sql);
			$num=0;
			while($usr=$result->fetch()){
				$num=$usr[0];
			}
			if($num==0){
				echo '<br/><br/><br/>';
				echo '<font color="#4B0082">&nbsp&nbsp后台数据正在更新，请耐心等待....................</font>';
			}
			else{
				//全校  柱状图参数
				$sql1="select XY,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS) from XUEXIAO where NJ='".$x."' and XY is not null group by xy order by xy";
				//全校   各学期    折线图参数
				$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS)  from XNQXUEXIAO where NJ='".$x."' group by XNQ order by xnq";
				//$sql3="select NJ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQXUEXIAO  group by XNQ order by xnq";
				$result1 = $conn->query($sql1);
				$result2 = $conn->query($sql2);
				$i=0;
				$XY= Array();
				$IJS= Array();
				$IIJS= Array();
				$IIIJS= Array();

				$BLIJS= Array();
				$BLIIJS= Array();
				$BLIIIJS= Array();
				$ZRS= Array();
				while($usr=$result1->fetch()){
					$XY[$i]=$usr[0];
					//必须强制转换成整形
					$ZRS[$i]=doubleval($usr[4]);
					$BLIJS[$i]=round($usr[1]/$ZRS[$i],4); // 比例数值。
					$BLIIJS[$i]=round($usr[2]/$ZRS[$i],4);
					$BLIIIJS[$i]=round($usr[3]/$ZRS[$i],4);
					$IJS[$i]=intval($usr[1]);
					$IIJS[$i]=intval($usr[2]);
					$IIIJS[$i]=intval($usr[3]);
					$i++;
				}
				//全校各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
				$k=0;
				$ZXNQ= Array();
				$ZIJS= Array();
				$ZIIJS= Array();
				$ZIIIJS= Array();
				$ZZRS= Array();
				while($usr=$result2->fetch()){
					$ZXNQ[$k]=$usr[0];
					$ZZRS[$k]=doubleval($usr[4]);
					$ZIJS[$k]=round($usr[1]/$ZZRS[$k],4);
					$ZIIJS[$k]=round($usr[2]/$ZZRS[$k],4);
					$ZIIIJS[$k]=round($usr[3]/$ZZRS[$k],4);
					$k++;
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
					echo '<font color="#4B0082">本年级全校 没有预警信息！</font>';
				}else{
					// 必须先用 jason 进行封装！！！
					$rtn = json_encode($XY);

					$arr1 =json_encode($BLIJS);
					$arr2 =json_encode($BLIIJS);
					$arr3 =json_encode($BLIIIJS);
					//折线图 参数封装
					$zrtn =json_encode($ZXNQ);
					$zarr1 =json_encode($ZIJS);
					$zarr2 =json_encode($ZIIJS);
					$zarr3 =json_encode($ZIIIJS);
	
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
					//highchart 柱状图和饼图在一起       examples/combo
					echo '<center>';
					echo '<div id="tu" style="float:left  width:100% height:100%" >';
					echo '</div>';
					echo '<div id="linetu" style="float:left  width:100% height:100%" >';
					echo '</div>';
					echo '</center>';
					echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text: "'.$x.'全校各学院 学业预警比例图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories: '.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警比例"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
			
						labels: {
							items: [{
								html: "全校预警统计图",
								style: {
									left: "0px",
									top: "-35px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
					echo 'tu.width  = window.innerWidth/1.3;
				tu.height = window.innerHeight/0.7;';
					echo ' </script>';
					//折线图
					echo'<script type="text/javascript">
			 		$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text: "'.$x.'全校各学期 学业预警比例折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
		        },
	
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警比例"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
	
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
	
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
	
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
			 	</script>';
				}
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	
	}
	
	//年级    不带学期      学院  图表统计
	public function BnjxyChartAction(){
		$str=$this->request->get("xy");
		//空格占两个字节
		$nj=substr($str,0,8);
		$x=substr($str,8);
		try{
			$conn =$this->getDI()->get("db");
			//学院 柱状图参数
			$sql1="select ZY,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS) from XUEYUAN where NJ='".$nj."' and xy like '%" . $x ."' group by zy order by zy";
			//某学院   各学期    折线图参数
			$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS)  from XNQXUEYUAN where NJ='".$nj."' and  xy like '%".$x."%' group by XNQ order by xnq";
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$i=0;
			$ZY= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			$BLIJS= Array();
			$BLIIJS= Array();
			$BLIIIJS= Array();
			$ZRS= Array();
			while($usr=$result1->fetch()){
				$ZY[$i]=$usr[0];
				$ZRS[$i]=doubleval($usr[4]);
				$IJS[$i]=intval($usr[1]);
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
				$BLIJS[$i]=round($usr[1]/$ZRS[$i],4); // 比例数值。
				$BLIIJS[$i]=round($usr[2]/$ZRS[$i],4);
				$BLIIIJS[$i]=round($usr[3]/$ZRS[$i],4);
				$i++;
			}
	
			//某学院     各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			$ZZRS= Array();
			while($usr=$result2->fetch()){
				$ZXNQ[$k]=$usr[0];
				$ZZRS[$k]=doubleval($usr[4]);
				$ZIJS[$k]=round($usr[1]/$ZZRS[$k],4);
				$ZIIJS[$k]=round($usr[2]/$ZZRS[$k],4);
				$ZIIIJS[$k]=round($usr[3]/$ZZRS[$k],4);
				$k++;
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
				echo '<font color="#4B0082">'.$nj.' '. $x.'没有预警信息！</font>';
			}
			else{
				// 必须先用 jason 进行封装！！！
				$rtn = json_encode($ZY);
				$arr1 =json_encode($BLIJS);
				$arr2 =json_encode($BLIIJS);
				$arr3 =json_encode($BLIIIJS);
				//折线图 参数封装
				$zrtn =json_encode($ZXNQ);
				$zarr1 =json_encode($ZIJS);
				$zarr2 =json_encode($ZIIJS);
				$zarr3 =json_encode($ZIIIJS);
	
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
	
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$nj.' '.$x.'各专业 学业预警比例图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警比例"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
	
						labels: {
							items: [{
								html:"学院预警统计图",
								style: {
									left: "0px",
									top: "-35px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo 'tu.width  = window.innerWidth/1.3;
						  tu.height = window.innerHeight/0.7;';
				echo '</script>';
				//折线图
				echo'<script type="text/javascript">
			$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text:"'.$nj.' '.$x.'各学期 学业预警比例折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
		        },
	
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警比例"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
	
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
		
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
		
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
				</script>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//年级      不带学期      专业    图表统计，饼图状图可以一起画出来, ----------------有的专业 班级 特别多！eg：理学院 财务管理！！！
	public function BnjzyChartAction(){
	
		$str=$this->request->get("zy");
		//空格占两个字节
		$nj=substr($str,0,8);
		$x=substr($str,8);
		
		try{
			
			$conn =$this->getDI()->get("db");
			//专业    柱状图参数
			$sql1="select BJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS) from ZHUANYE where  NJ='".$nj."' and zy like '%".$x."'  group by bj  order by bj";
			//某专业    各学期  折线图参数
			$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS)  from XNQZHUANYE where NJ='".$nj."' and zy like '%" .$x."' group by XNQ order by xnq";			
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$i=0;
			$BJ= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			$BLIJS= Array();
			$BLIIJS= Array();
			$BLIIIJS= Array();
			$ZRS= Array();
			while($usr=$result1->fetch()){
				$BJ[$i]=$usr[0];
				$ZRS[$i]=doubleval($usr[4]);
				$IJS[$i]=intval($usr[1]);
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
				$BLIJS[$i]=round($usr[1]/$ZRS[$i],4); // 比例数值。
				$BLIIJS[$i]=round($usr[2]/$ZRS[$i],4);
				$BLIIIJS[$i]=round($usr[3]/$ZRS[$i],4);
				$i++;
			}
			
			//某专业     各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			$ZZRS= Array();
			while($usr=$result2->fetch()){
				$ZXNQ[$k]=$usr[0];
				$ZZRS[$k]=doubleval($usr[4]);
				$ZIJS[$k]=round($usr[1]/$ZZRS[$k],4);
				$ZIIJS[$k]=round($usr[2]/$ZZRS[$k],4);
				$ZIIIJS[$k]=round($usr[3]/$ZZRS[$k],4);
				$k++;
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
				echo '<font color="#4B0082">'. $nj.' '. $x.'(专业)没有预警信息！</font>';
			}
			else{
				// 必须先用 jason 进行封装！！！
				$rtn = json_encode($BJ);
				$arr1 =json_encode($BLIJS);
				$arr2 =json_encode($BLIIJS);
				$arr3 =json_encode($BLIIIJS);
				//折线图 参数封装
				$zrtn =json_encode($ZXNQ);
				$zarr1 =json_encode($ZIJS);
				$zarr2 =json_encode($ZIIJS);
				$zarr3 =json_encode($ZIIIJS);
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
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$nj.' '.$x.'专业各班级  学业预警比例图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警比例"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
	
						labels: {
							items: [{
								html:"专业预警统计图",
								style: {
									left: "-15px",
									top: "-25px",
									color: "black",
								    fontSize:"13"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [5, 10],
							size: 80,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo '</script>';
				//折线图
				echo'<script type="text/javascript">
			$(function () {
		    $("#linetu").highcharts({
		        chart: {
		            type: "line"
		        },
		        title: {
		            text:"'.$nj.' '.$x.'专业各学期 学业预警比例折线图",
			 		style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
		        },
	
		        xAxis: {
			 		categories:'.$zrtn.'
		        },
		        yAxis: {
		            title: {
		                text: "预警比例"
		            },
		            min: 0
		        },
		 		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
		        plotOptions: {
		            spline: {
		                marker: {
		                    enabled: true
		                }
		            }
		        },
	
		        series: [{
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$zarr1.'
						}, {
	
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$zarr2.'
						}, {
	
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$zarr3.'
						}]
		    		});
				});
			  </script>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//不带学期      班级  饼图   折线图
	public function BnjbjChartAction(){
		$str=$this->request->get("bj");
		//空格占两个字节
		$nj=substr($str,0,8);
		$x=substr($str,8);
		try{
			$conn =$this->getDI()->get("db");
			//班级    柱状图参数
			$sql1=" select  sum(IJS),sum(IIJS),sum(IIIJS)  from ZHUANYE where (nj='".$nj ."' and bj like '%" . $x ."') group by bj";
			$result1 = $conn->query($sql1);
			//存在年级为空的现象  总人数需要重新进行统计
			$sql2=" select  sum(ZRS)  from XNQZHUANYE where nj='".$nj ."' and bj like '%" . $x ."' group by bj";
			$result2 = $conn->query($sql2);
			$IJS= 0;
			$IIJS=0;
			$IIIJS=0;
			$ZRS=0;
			while($usr=$result2->fetch()){
				$ZRS+=doubleval($usr[0]);
			}
			while($usr=$result1->fetch()){
				$IJS+=doubleval($usr[0]);
				$IIJS+=doubleval($usr[1]);
				$IIIJS+=doubleval($usr[2]);
			}
			
			$NoJS=round(($ZRS-($IJS+$IIJS+$IIIJS))/$ZRS,4); //无警示人数；
			
			$IJS=round($IJS/$ZRS,4);
			$IIJS=round($IIJS/$ZRS,4);
			$IIIJS=round($IIIJS/$ZRS,4);
			
			$IJS =json_encode($IJS);
			$IIJS =json_encode($IIJS);
			$IIIJS =json_encode($IIIJS);
			$NoJS=json_encode($NoJS);
			//////////////////////////////班级的每个学年期的统计：---画折线图使用。
			//先把学期选择出来。 数据库中表格：XNQZHUANYE
			$sql2="select XNQ,sum(IJS),sum(IIJS),sum(IIIJS)  from XNQZHUANYE where nj='".$nj ."' and bj like '%".$x."' group by XNQ order by xnq";
			//班级各个学期 IJS IIJS  IIIJS 的人数，用来画折线图
			$result2 = $conn->query($sql2);
			$sql3=" select  sum(ZRS)  from XNQZHUANYE where nj='".$nj ."' and bj like '%" . $x ."'  group by bj";
			$result3 = $conn->query($sql3);
			$k=0;
			$ZXNQ= Array();
			$ZIJS= Array();
			$ZIIJS= Array();
			$ZIIIJS= Array();
			$ZZRS=0;
			while($usr=$result3->fetch()){
				$ZZRS+=doubleval($usr[0]);
			}
			while($usr=$result2->fetch()){
				$ZXNQ[$k]=$usr[0];
				$ZIJS[$k]=round($usr[1]/$ZZRS,4);
				$ZIIJS[$k]=round($usr[2]/$ZZRS,4);
				$ZIIIJS[$k]=round($usr[3]/$ZZRS,4);
				$k++;
			}
			
			//折线图 参数封装
			$zrtn =json_encode($ZXNQ);
			$zarr1 =json_encode($ZIJS);
			$zarr2 =json_encode($ZIIJS);
			$zarr3 =json_encode($ZIIIJS);
	
			//判断该班是否有警示信息
			if($IJS==0 && $IIJS==0 && $IIIJS==0){
				echo '<br/>';
				echo '<font color="#4B0082">'. $nj.' '. $x.'班级  没有预警信息！</font>';
			}
			else{
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">';
				//饼图
				echo '$(function () {
				    $("#tu").highcharts({
				        chart: {
				            type: "pie",
				            options3d: {
				                enabled: true,
				                alpha: 45,
				                beta: 10
				            }
				        },
				        title: {
				            text: "'. $nj.' '.$x.'(班级) 学业预警比例图",
				            style: {
										color: "black",
										fontWeight:"bold",
										fontSize:"25"
									}
	
				        },
				        tooltip: {
				            //pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
				        },
				        plotOptions: {
				            pie: {
				                allowPointSelect: true,
				                cursor: "pointer",
				                depth:35,
				                dataLabels: {
				                    enabled: true,
				                    format: "{point.name}"
				                }
				            }
				        },
	            		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
				        series: [{
				            type: "pie",
				            name: "比例",
				            data: [
				            	{
								name: "II级预警",
								color:"rgba( 255, 165, 0,0.6)",
								y: '.$IIJS.'
								},
										

								{
								name: "III级预警",
								color: "rgba(151,187,255,0.75)",
								y: '.$IIIJS.'
								},
								{
			                    name: "无预警",
			               		color: "rgba(124,252,0,0.6)",
			                    y: '.$NoJS.'
				                },
			            		{
			                    name: "I级预警",
			               		color: "rgba(255,10,10,0.6)",
			                    y: '.$IJS.',
			                    sliced: true,
			                    selected: true
				                },
				            ]
				        }]
				    });
				});';
				echo '</script>';
				//折线图,每学期统计，班级已经固定属于某个学年了。。。
				echo'<script type="text/javascript">
				$(function () {
			    $("#linetu").highcharts({
			        chart: {
			            type: "line"
			        },
			        title: {
			            text:"'. $nj.' '.$x.'班级 各学期 学业预警比例折线图",
				 		style: {
										color: "black",
										fontWeight:"bold",
										fontSize:"25"
									}
			        },
	
			        xAxis: {
				 		categories:'.$zrtn.'
			        },
			        yAxis: {
			            title: {
			                text: "预警比例"
			            },
			            min: 0
			        },
			 		credits: {
			          			enabled:false
									},
							exporting: {
			            		enabled:true
							},
			        plotOptions: {
			            spline: {
			                marker: {
			                    enabled: true
			                }
			            }
			        },
	
			        series: [{
								name: "I级预警",
								color: "rgba(255,10,10,0.6)",
								data: '.$zarr1.'
							}, {
	
								name: "II级预警",
								color:"rgba( 255, 165, 0,0.6)",
								data: '.$zarr2.'
							}, {
	
								name: "III级预警",
								color: "rgba(151,187,255,0.75)",
								data:'.$zarr3.'
							}]
					    });
					});
						</script>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	
	//////////// /////////////////////////  （4） 选择年级     并且    选择学期      ///////////////////////////////////////////////////////
	
	//带学期  年级      学校图表统计
	public function BnjxnqxxChartAction(){
		$str=$this->request->get("xnq");
	
		//空格占两个字节
		$nj=substr($str,0,8);
		$x=substr($str,8);
		//echo $x;
		try{
			$conn =$this->getDI()->get("db");
			//查询学年期是否为空， 为空  说明后台数据正在更新.......
			$sql="select distinct xnq from XQView order by xnq desc";
			$result = $conn->query($sql);
			$num=0;
			while($usr=$result->fetch()){
				$num=$usr[0];
			}
			if($num==0){
				echo '<br/><br/><br/>';
				echo '<font color="#4B0082">&nbsp&nbsp后台数据正在更新，请耐心等待....................</font>';
			}
			else{
				//全校  柱状图参数$sql1="select ZY,sum(IJS),sum(IIJS),sum(IIIJS) from XUEYUAN where xy like '%" . $x ."' group by zy order by zy";
				$sql1="select XY,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS) from XNQXUEXIAO where NJ='".$nj."' and xnq like '%" . $x ."' group by xy order by xy";
				$result1 = $conn->query($sql1);
				$i=0;
				$XY= Array();
				$IJS= Array();
				$IIJS= Array();
				$IIIJS= Array();
				$BLIJS= Array();
				$BLIIJS= Array();
				$BLIIIJS= Array();
				$ZRS= Array();
				while($usr=$result1->fetch()){
					$XY[$i]=$usr[0];
					//必须强制转换成整形
					$ZRS[$i]=doubleval($usr[4]);
					$IJS[$i]=intval($usr[1]);
					$IIJS[$i]=intval($usr[2]);
					$IIIJS[$i]=intval($usr[3]);
					$BLIJS[$i]=round($usr[1]/$ZRS[$i],4); // 比例数值。
					$BLIIJS[$i]=round($usr[2]/$ZRS[$i],4);
					$BLIIIJS[$i]=round($usr[3]/$ZRS[$i],4);
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
					echo '<font color="#4B0082">本年级本学期全校 没有预警信息！</font>';
				}else{
					// 必须先用 jason 进行封装！！！
					$rtn = json_encode($XY);
					$arr1 =json_encode($BLIJS);
					$arr2 =json_encode($BLIIJS);
					$arr3 =json_encode($BLIIIJS);
						
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
					//highchart 柱状图和饼图在一起       examples/combo
					echo '<center>';
					echo '<div id="tu" style="float:left  width:100% height:100%" >';
					echo '</div>';
					echo '</center>';
					echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text: "'.$nj.' '.$x.'学期  全校各学院 学业预警比例图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"25"
								}
						},
						xAxis: {
							categories: '.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警比例"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
			
						labels: {
							items: [{
								html: "全校预警统计图",
								style: {
									left: "0px",
									top: "-35px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [26, 10],
							size: 90,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
					echo 'tu.width  = window.innerWidth/1.3;
				tu.height = window.innerHeight/0.7;';
					echo ' </script>';
						
				}
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	
	}
	
	//带学期   年级      学院  图表统计
	public function BnjxnqxyChartAction(){
		$str=$this->request->get("xnq");
		//空格占两个字节
		$nj=substr($str,0,8);
		$x=substr($str,8,11);
		$y=substr($str,19);
	
		try{
			$conn =$this->getDI()->get("db");
			//学院    柱状图参数
			$sql1="select  ZY,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS)  from XNQXUEYUAN where (NJ='".$nj."' and xnq='" .$x."' and xy like '%" . $y ."') group by zy order by zy";
			$result1 = $conn->query($sql1);
			$i=0;
			$ZY= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			$BLIJS= Array();
			$BLIIJS= Array();
			$BLIIIJS= Array();
			$ZRS= Array();
			while($usr=$result1->fetch()){
				$ZY[$i]=$usr[0];
				$ZRS[$i]=doubleval($usr[4]);
				$IJS[$i]=intval($usr[1]);
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
				$BLIJS[$i]=round($usr[1]/$ZRS[$i],4); // 比例数值。
				$BLIIJS[$i]=round($usr[2]/$ZRS[$i],4);
				$BLIIIJS[$i]=round($usr[3]/$ZRS[$i],4);
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
				echo '<font color="#4B0082">'.$nj.' '.$x.'学期'.$y.'没有预警信息！</font>';
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
				$arr1 =json_encode($BLIJS);
				$arr2 =json_encode($BLIIJS);
				$arr3 =json_encode($BLIIIJS);
	
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
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$nj.' '.$x.'学期'.$y.'各专业 学业预警比例图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "警示比例"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
	
						labels: {
							items: [{
								html:"学院预警统计图",
								style: {
									left: "-8px",
									top: "-25px",
									color: "black",
								    fontSize:"15"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [5, 10],
							size: 80,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo 'tu.width  = window.innerWidth/1.3;
						  tu.height = window.innerHeight/0.7;';
				echo '</script>';
	
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//带学期    年级    专业    图表统计
	public function BnjxnqzyChartAction(){
		$str=$this->request->get("xnq");
		//空格占两个字节
		$nj=substr($str,0,8);
		$x=substr($str,8,11);
		$y=substr($str,19);
		try{
			$conn =$this->getDI()->get("db");
			//专业     柱状图参数
			$sql1="select  BJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(ZRS) from XNQZHUANYE where (nj= '" . $nj ."' and xnq='" . $x ."' and zy like '%" . $y ."') group by bj order by bj";
			$result1 = $conn->query($sql1);
			$i=0;
			$BJ= Array();
			$IJS= Array();
			$IIJS= Array();
			$IIIJS= Array();
			$BLIJS= Array();
			$BLIIJS= Array();
			$BLIIIJS= Array();
			$ZRS= Array();
			while($usr=$result1->fetch()){
				$BJ[$i]=$usr[0];
				$ZRS[$i]=doubleval($usr[4]);
				$IJS[$i]=intval($usr[1]);
				$IIJS[$i]=intval($usr[2]);
				$IIIJS[$i]=intval($usr[3]);
				$BLIJS[$i]=round($usr[1]/$ZRS[$i],4); // 比例数值。
				$BLIIJS[$i]=round($usr[2]/$ZRS[$i],4);
				$BLIIIJS[$i]=round($usr[3]/$ZRS[$i],4);
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
				echo '<font color="#4B0082">'.$nj.' '.$x.'学期'.$y.'(专业)没有预警信息！</font>';
			}else{
				/* //判断班级的个数
				 while($i<6){
	
				 $BJ[$i]="0";
				 $IJS[$i]=0;
				 $IIJS[$i]=0;
				 $IIIJS[$i]=0;
				 $i++;
	
				 } */
				// 必须先用 jason 进行封装！！！
				$rtn = json_encode($BJ);
				$arr1 =json_encode($BLIJS);
				$arr2 =json_encode($BLIIJS);
				$arr3 =json_encode($BLIIIJS);
	
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
				//highchart 柱状图和饼图在一起       examples/combo
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				//echo '<canvas id="myChart" width=800% height=400%></canvas>';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">
			 		$(function () {
					$("#tu").highcharts({
						title: {
							text:"'.$nj.''.$x.'学期 '.$y.'专业各班级 学业预警比例图",
			 		        style: {
									color: "black",
									fontWeight:"bold",
									fontSize:"22"
								}
						},
						xAxis: {
							categories:'.$rtn.'
						},
						yAxis: {
						   min: 0,
						   title: {
						   text: "预警比例"
								}
						},
	
						credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
	
						labels: {
							items: [{
								html:"专业预警统计图",
								style: {
									left: "-8px",
									top: "-25px",
									color: "black",
								    fontSize:"13"
								}
							}]
						},
						series: [{
							type:"column",
							name: "I级预警",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr1.'
						}, {
							type:"column",
							name: "II级预警",
							color:"rgba( 255, 165, 0,0.6)",
							data: '.$arr2.'
						}, {
							type: "column",
							name: "III级预警",
							color: "rgba(151,187,255,0.75)",
							data:'.$arr3.'
						},
						 {
							type: "pie",
							name: "人数",
							data: [{
								name: "I级预警",
								y: '.$BIJS.',
								color:"rgba(255,10,10,0.6)"
								}, {
								name: "II级预警",
								y: '.$BIIJS.',
								color:"rgba( 255, 165, 0,0.6)"
							}, {
								name: "III级预警",
								y: '.$BIIIJS.',
								color: "rgba(151,187,255,0.75)"
							}],
							center: [5, 10],
							size: 80,
							showInLegend: false,
							dataLabels: {
							enabled: false
							}
						}]
					});
				});
				';
				echo '</script>';
	
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//带学期   年级      班级   图表统计
	public function BnjxnqbjChartAction(){
		$str=$this->request->get("xnq");
		//空格占两个字节
		$nj=substr($str,0,8);
		$x=substr($str,8,11);
		$y=substr($str,19);
		//echo $nj;
		try{
			$conn =$this->getDI()->get("db");
			//班级     b圆饼 图参数，不应该从 MY_XYJS2 表中 查找，应该在 XNQZHUANYE 表中。
			$sql="select sum(IJS),sum(IIJS),sum(IIIJS)  from XNQZHUANYE where (nj='".$nj ."' and xnq='".$x."' and bj like '%".$y."') group by bj";
			$result = $conn->query($sql);
			//存在年级为空的现象  总人数需要重新进行统计
			$sql2=" select  sum(ZRS)  from XNQZHUANYE where nj='".$nj ."'  and xnq='".$x."' and bj like '%" . $y ."' group by bj";
			$result2 = $conn->query($sql2);
			$IJS= 0;
			$IIJS=0;
			$IIIJS=0;
			$ZRS=0;
			
			while($usr=$result2->fetch()){
				$ZRS+=doubleval($usr[0]);
			}

			while($usr=$result->fetch()){
				$IJS+=doubleval($usr[0]);
				$IIJS+=doubleval($usr[1]);
				$IIIJS+=doubleval($usr[2]);
			}
	
			//判断各班是否有警示信息
			if($IJS==0 && $IIJS==0 && $IIIJS==0){
				echo '<br/>';
				echo '<font color="#4B0082">'.$nj.' '.$x.'学期'.$y.'班级  没有预警信息！</font>';
			}
			else{
				$NoJS=round(($ZRS-($IJS+$IIJS+$IIIJS))/$ZRS,4); //无预警人数；
					
					$IJS=round($IJS/$ZRS,4);
					$IIJS=round($IIJS/$ZRS,4);
					$IIIJS=round($IIIJS/$ZRS,4);
					
					$IJS =json_encode($IJS);
					$IIJS =json_encode($IIJS);
					$IIIJS =json_encode($IIIJS);
					$NoJS=json_encode($NoJS);
	
				echo '<center>';
				echo '<div id="tu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '<div id="linetu" style="float:left  width:100% height:100%" >';
				echo '</div>';
				echo '</center>';
				echo'<script type="text/javascript">';
				//饼图
				echo '$(function () {
				    $("#tu").highcharts({
				        chart: {
				            type: "pie",
				            options3d: {
				                enabled: true,
				                alpha: 45,
				                beta: 10
				            }
				        },
				        title: {
				            text: "'.$nj.' '.$x.'学期  '.$y.'(班级) 学业预警比例图",
				            style: {
										color: "black",
										fontWeight:"bold",
										fontSize:"25"
									}
				        },
				        tooltip: {
				            //pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
				        },
				        plotOptions: {
				            pie: {
				                allowPointSelect: true,
				                cursor: "pointer",
				                depth:35,
				                dataLabels: {
				                    enabled: true,
				                    format: "{point.name}"
				                }
				            }
				        },
	            		credits: {
		          			enabled:false
								},
						exporting: {
		            		enabled:true
						},
				        series: [{
				            type: "pie",
				            name: "比例",
				            data: [
				            	{
								name: "II级预警",
								color:"rgba( 255, 165, 0,0.6)",
								y: '.$IIJS.'
								},
								{
								name: "III级预警",
								color: "rgba(151,187,255,0.75)",
								y: '.$IIIJS.'
								},
								 {
			                    name: "无预警",
			               		color: "rgba(124,252,0,0.6)",
			                    y: '.$NoJS.'
				                },
			            		{
			                    name: "I级预警",
			               		color: "rgba(255,10,10,0.6)",
			                    y: '.$IJS.',
			                    sliced: true,
			                    selected: true
				                },
				            ]
				        }]
				    });
				});';
				echo '</script>';
	
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
//-----------------------------------------------------------------------------------------------	
	public function indexAction()
	{
		 
	}

	public function homeAction(){
		 
	}
}
?>