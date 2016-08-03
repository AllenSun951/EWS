<?php
use Phalcon\Mvc\Model\Query;
header("Content-type:text/html;charset=utf-8");
use Phalcon\Db\Adapter\Pdo\Oracle as DbAdapter;
class ChartStuController extends ControllerBase
{
	///////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////      学生界面   学生各个学期警示结果    柱状图            ////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////
	//带学期      学生 图表统计
	public function xsChartAction(){
		//session_start();
		$xh= $_SESSION['userid'];
		$xm= $_SESSION['username'];
		try{
			$conn =$this->getDI()->get("db");
			$sql="select XNQ,JSJB from MY_XYJS2 where XH='" .$xh ."' order by XNQ ";
			$result = $conn->query($sql);
			$xnq= Array();
			$jsjb= Array();
			$i=0;
			$str="无";
			while($usr=$result->fetch()){
				$xnq[$i]=$usr[0];
				$jsjb[$i]=$usr[1];
				$i++;
			}

			//判断各学期是否有警示信息
			$j=0;
			while($j<$i){
				if($jsjb[$j]==$str){
					$j++;
				}
				else
				break;
			}
			if($j==$i){
				echo '<br/>';
				echo '<font color="#4B0082">'.$xh.$xm.' 各学期没有警示信息！</font>';
			}
			else{
				for($j=0;$j<$i;$j++){ //数组初始化

					$jg[$j]=0;
				}
				$j=0;
				
			while($j<$i){
				if($jsjb[$j]=="Ⅰ级警示"){
					$jg[$j]=3;
				}
				else {
					if($jsjb[$j]=="Ⅱ级警示"){
						$jg[$j]=2;
					}
					else{
						if($jsjb[$j]=="Ⅲ级警示"){
							$jg[$j]=1;
						}
					}
				}
				$j++;
			}
				// 必须先用 jason 进行封装！！！
				$rtn = json_encode($xnq);
				$arr4 =json_encode($jg);
				echo '<center>';
				echo '<br/>';
				echo '<caption ><font size=5><b>学号：'.$xh.' 姓名：'.$xm.' 各学期   学业警示统计图</b></font></caption>';
				echo '</center>';
				echo '<br/>';

		  		echo '<center><font color=red>代号3：I级警示;  代号2：II级警示;   代号1：III级警示;    代号0：无警示</font></center>';
				echo '<script src="../js/chart/Chart.js"></script>';
				echo '<center>'; 
				echo '<div id="tu" style="float:left  width:400% height:300%" >';
				echo '</div>';
				echo '</center>';
				echo '<br/>';

				// 利用 highchart 话 折线图
				echo'<script type="text/javascript">
				$(function () {
		    	$("#tu").highcharts({
		        chart: {
		            type: "line"
		       	 },
		        title: {
		            text:" ",
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
			 	    min:1,  
                    max:3,
			 		allowDecimals:false,
		            title: {
		                text: "警示代号"
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
							name: "警示代号",
							color: "rgba(255,10,10,0.6)",
							data: '.$arr4.'
						}]
		    		});
				});
				</script>';
 
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