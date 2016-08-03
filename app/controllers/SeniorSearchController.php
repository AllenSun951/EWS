<?php
session_start();
use Phalcon\Mvc\Model\Query;
header("Content-type:text/html;charset=utf-8");
use Phalcon\Db\Adapter\Pdo\Oracle as DbAdapter;
class SeniorSearchController extends ControllerBase
{
	
	//警示结果展示----管理人员高级查询
	public function SeniorSearchAlertAction(){
		//判断非法登录
		if(!isset($_SESSION['userid'])||$_SESSION['userqx']==0){
			//header("Location:../login.php");
			echo '<br/>';echo '<br/>';
			echo '未登录！请点击此处 <a href="../login.php" target="_top">登录</a>';
			exit();
		}
		try{
			$conn =$this->getDI()->get("db");
			$sql="select distinct xnq  from XQView order by xnq desc";
			$result = $conn->query($sql);
			$this->view->setVar("xqs",$result);
	
			$sql2="select distinct condition  from JSCondition";
			$result2 = $conn->query($sql2);
			$this->view->setVar("condition",$result2);
			
			// 获取 session 中的用户ID，传给页面
			$this->view->setVar("sessionName",$_SESSION['username']);
			}catch(Exception $e) {
				echo '<font color="#4B0082">数据库连接错误！</font>';
			}
	}
	
	/////////////////////////////管理员界面--高级查询---界面初始化/////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////
	
	public function InitSearchConditionAction()
	{
		echo '<br/><br/>';
		echo '<p style="font-family:楷体; font-size:24px; color:blue; text-align:left; text-indent: 28px; line-height:200%;">欢迎使用高级查询功能!</p>';
		echo '<p style="font-family:楷体; font-size:24px; color:blue; text-align:left; text-indent: 28px; line-height:200%;">请您先选择查询条件，然后根据查询条件输入相关内容进行查询；</p>';
     	echo '<p style="font-family:楷体; font-size:24px; color:blue; text-align:left; text-indent: 28px; line-height:200%;">如 : 条件 = 学号，内容 = 010111101，一般学号为9位数；如: 条件 = 姓名，内容 = 张三。</p>';
		echo '<br/>';
	}
	
	
	//不带学期
	public function ConditionButtonAction(){
		//学生
		$str=$this->request->get("condition");
		$length=mb_strlen( $str,'utf-8' );
		$x=mb_substr($str,0,2,'utf-8');
		$y=mb_substr($str,2,$length-2,'utf-8'); //$length-2  名字长度
		try{
			$conn =$this->getDI()->get("db");
			if($x=="学号")
			{
				$sql2="select XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGXF,HDXF,NJ from MY_XYJS where XH like '%" . $y ."%'order by xm";
				$result2 = $conn->query($sql2);
				$rows=0;  //用于计算结果集的行数，用于判断是否为空！
				while($usr=$result2->fetch())
				{ $rows++;} //用于计算结果集的行数，用于判断是否为空！
				if ($rows==0)
				{
					echo '<br/><br/><br/><br/>';
					echo '<font size="5" color="red">&nbsp;此“学号”不存在！！！</font>';
					echo '<br/>';
				}
				else
				{
					$sql="select XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGXF,HDXF,NJ from MY_XYJS where XH like '%" . $y ."%'order by xm";
					$result = $conn->query($sql);
					echo '<table align="center" width="80%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
					echo '<caption><font size= "5" face="微软雅黑" >'.$y.' 学生警示结果</font></caption>';
					echo '<br/>';
					while($usr=$result->fetch()){
						{
							echo '<tr>';
							echo
							'<th height=40 style="word-wrap:break-word;">学院</th>
						  <th height=40 style="word-wrap:break-word;">专业</th>
						  <th height=40 style="word-wrap:break-word;">班级</th>
						  <th height=40 style="word-wrap:break-word;">学号</th>
						  <th height=40 style="word-wrap:break-word;">姓名</th>
						  <th height=40 style="word-wrap:break-word;">警示级别</th>
					      <th height=40 style="word-wrap:break-word;">警示原因</th>
						  <th height=40 style="word-wrap:break-word;">总门数</th>
						  <th height=40 style="word-wrap:break-word;">总学分</th>
				 		 ';
							echo '</tr>';
								
							$HCXF=$usr[8]-$usr[15]; //还差学分
							echo '<tr>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[0].'</td>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[1].'</td>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[2].'</td>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[3].'</td>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[4].'</td>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[6].'</td>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[7].'</td>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[8].'</td>';
							echo '<tr>';
							echo
							'<th height=40 style="word-wrap:break-word;">补考门数</th>
						  <th height=40 style="word-wrap:break-word;">补考学分</th>
						  <th height=40 style="word-wrap:break-word;">重修门数</th>
						  <th height=40 style="word-wrap:break-word;">重修学分</th>
						  <th height=40 style="word-wrap:break-word;">不及格门数</th>
						  <th height=40 style="word-wrap:break-word;">不及格学分</th>
						  <th height=40 style="word-wrap:break-word;">获得学分</th>
						  <th height=40 style="word-wrap:break-word;">还差学分</th>
						  <th height=40 style="word-wrap:break-word;">入学年级</th>';
							echo '</tr>';
								
							echo '<tr>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[9].'</td>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[10].'</td>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[11].'</td>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[12].'</td>';
								
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[13].'</td>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[14].'</td>';
								
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[15].'</td>';
								
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$HCXF.'</td>';
								
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[16].'</td>';
								
							echo '</tr>';
						}
					}
					echo "</table>";
				}
				
		}
		//按姓名查找
		else
		{
			$sql2="select XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGXF,HDXF,NJ from MY_XYJS where XM like '%" . $y ."%'order by xh";
			$result2 = $conn->query($sql2);
			$rows=0;//用于计算结果集的行数，用于判断是否为空！
			while($usr=$result2->fetch())
			{ $rows++;}//用于计算结果集的行数，用于判断是否为空！
			if ($rows==0)
			{
				echo '<br/><br/><br/><br/>';
				echo '<font size="5" color="red">&nbsp;“姓名”不存在！！！</font>';
				echo '<br/>';
			}
			else
			{
				$sql="select XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGXF,HDXF,NJ from MY_XYJS where XM like '%" . $y ."%'order by xh";
				//$sql1="select  count(*) from MY_XYJS where XM='" . $x ."'";
				$result = $conn->query($sql);
				echo '<table align="center" width="98%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
				echo '<caption><font size= "5" face="微软雅黑" >'.$x.' : '.$y.'  学生警示结果</font></caption>';
				echo '<br/>';
				echo '<tr>';
				echo
				'<th height=40 style="word-wrap:break-word;">学院</th>
				     <th height=40 style="word-wrap:break-word;">专业</th>
				     <th height=40 style="word-wrap:break-word;">班级</th>
				  	 <th height=40 style="word-wrap:break-word;">学号</th>
				  	 <th height=40 style="word-wrap:break-word;">姓名</th>
				  	 <th height=40 style="word-wrap:break-word;">警示级别</th>
			      	 <th height=40 style="word-wrap:break-word;">警示原因</th>
				  	 <th height=40 style="word-wrap:break-word;">总门数</th>
				  	 <th height=40 style="word-wrap:break-word;">总学分</th>
				     <th height=40 style="word-wrap:break-word;">补考门数</th>
				  	 <th height=40 style="word-wrap:break-word;">补考学分</th>
					  <th height=40 style="word-wrap:break-word;">重修门数</th>
					  <th height=40 style="word-wrap:break-word;">重修学分</th>
					  <th height=40 style="word-wrap:break-word;">不及格门数</th>
					  <th height=40 style="word-wrap:break-word;">不及格学分</th>
					  <th height=40 style="word-wrap:break-word;">获得学分</th>
					  <th height=40 style="word-wrap:break-word;">还差学分</th>
					  <th height=40 style="word-wrap:break-word;">入学年级</th>';
				echo '</tr>';
				while($usr=$result->fetch()){
					{
						$HCXF=$usr[8]-$usr[15]; //还差学分
						echo '<tr>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[0].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[1].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[2].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[3].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[4].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[6].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[7].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[8].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[9].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[10].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[11].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[12].'</td>';
							
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[13].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[14].'</td>';
							
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[15].'</td>';
							
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$HCXF.'</td>';
							
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[16].'</td>';
							
						echo '</tr>';
					}
				}
				echo "</table>";
			}
		}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//带学期
	public function xnqConditionButtonAction(){
		//学生
		$str=$this->request->get("condition");
		$length=mb_strlen( $str,'utf-8' );
		$x=mb_substr($str,0,11,'utf-8');
		$p=mb_substr($str,11,2,'utf-8');
		$y=mb_substr($str,13,$length-13,'utf-8');
	try{
		$conn =$this->getDI()->get("db");
		if($p=="学号")
		{
			$sql2="select  XNQ,XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGMSBL,BJGXF,BJGXFBL,HDXF,NJ  from MY_XYJS2 where xnq='" . $x ."' and XH like '%" . $y ."%'";
			$result2 = $conn->query($sql2);
			$rows=0;//用于计算结果集的行数，用于判断是否为空！
			while($usr=$result2->fetch())
			{ $rows++;}//用于计算结果集的行数，用于判断是否为空！
			if ($rows==0)
			{
				echo '<br/><br/><br/><br/>';
				echo '<font size="5" color="red">&nbsp;“学号”不存在！！！</font>';
				echo '<br/>';
			}
			else
			{
				$sql="select  XNQ,XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGMSBL,BJGXF,BJGXFBL,HDXF,NJ  from MY_XYJS2 where xnq='" . $x ."' and XH like '%" . $y ."%'";
				//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
				$result = $conn->query($sql);
					
				echo '<table align="center" width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB" >';
				echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期   '.$y.'  警示结果</font></caption>';
				echo '<br/>';
	
				while($usr=$result->fetch()){
					{
						echo '<tr>';
						echo '<th height=40 style="word-wrap:break-word;">学年/学期</th>
						  <th height=40 style="word-wrap:break-word;">学院</th>
						  <th height=40 style="word-wrap:break-word;">专业</th>
						  <th height=40 style="word-wrap:break-word;">班级</th>
						  <th height=40 style="word-wrap:break-word;">学号</th>
						  <th height=40 style="word-wrap:break-word;">姓名</th>
						  <th height=40 style="word-wrap:break-word;">警示级别</th>
					      <th height=40 style="word-wrap:break-word;">警示原因</th>
						  <th height=40 style="word-wrap:break-word;">总门数</th>
						  <th height=40 style="word-wrap:break-word;">总学分</th>
							<th height=40 style="word-wrap:break-word;">补考门数</th>';
						echo '</tr>';
							
						$HCXF=$usr[9]-$usr[18]; //还差学分
						echo '<tr>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[0].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[1].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[2].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[3].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[4].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[6].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[7].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[8].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[9].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[10].'</td>';
							
						echo '<tr>';
						echo '
						  <th height=40 style="word-wrap:break-word;">补考学分</th>
						  <th height=40 style="word-wrap:break-word;">重修门数</th>
						  <th height=40 style="word-wrap:break-word;">重修学分</th>
						  <th height=40 style="word-wrap:break-word;">不及格门数</th>
						  <th height=40 style="word-wrap:break-word;">不及格门数比例</th>
						  <th height=40 style="word-wrap:break-word;">不及格学分</th>
						  <th height=40 style="word-wrap:break-word;">不及格学分比例</th>
						  <th height=40 style="word-wrap:break-word;">获得学分</th>
						  <th height=40 style="word-wrap:break-word;">还差学分</th>
						  <th height=40 colspan="2" style="word-wrap:break-word;">入学年级</th>';
						echo '</tr>';
						echo '<tr>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[11].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[12].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[13].'</td>';
	
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[14].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[15].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[16].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[17].'</td>';
						echo '<td align="center" height=40>'.$usr[18].'</td>';
	
						echo '<td align="center" height=40>'.$HCXF.'</td>';
	
						echo '<td align="center" height=40 colspan="2" style="word-wrap:break-word;">'.$usr[19].'</td>';
	
						echo '</tr>';
					}
	
				}
				echo "</table>";
			}
		}
		//按姓名查找  放在一行显示！！！！
		else
		{
			$sql2="select  XNQ,XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGXF,HDXF,NJ  from MY_XYJS2 where xnq='" . $x ."' and XM like '%" . $y ."%'";
			$result2 = $conn->query($sql2);
			$rows=0;//用于计算结果集的行数，用于判断是否为空！
			while($usr=$result2->fetch())
			{ $rows++;}//用于计算结果集的行数，用于判断是否为空！
			if ($rows==0)
			{
				echo '<br/><br/><br/><br/>';
				echo '<font size="5" color="red">&nbsp;“姓名”不存在！！！</font>';
				echo '<br/>';
			}
			else
			{
				$sql="select  XNQ,XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGXF,HDXF,NJ  from MY_XYJS2 where xnq='" . $x ."' and XM like '%" . $y ."%'";
				//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
				$result = $conn->query($sql);
				echo '<table align="center" width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB" >';
				echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期   '.$y.'  警示结果</font></caption>';
				//echo '<caption>'.$x.'</caption>';
				echo '<br/>';
				echo '<tr>';
				echo '<th height=40 style="word-wrap:break-word;">学年/学期</th>
					  <th height=40 style="word-wrap:break-word;">学院</th>
					  <th height=40 style="word-wrap:break-word;">专业</th>
					  <th height=40 style="word-wrap:break-word;">班级</th>
					  <th height=40 style="word-wrap:break-word;">学号</th>
					  <th height=40 style="word-wrap:break-word;">姓名</th>
					  <th height=40 style="word-wrap:break-word;">警示级别</th>
				      <th height=40 style="word-wrap:break-word;">警示原因</th>
					  <th height=40 style="word-wrap:break-word;">总门数</th>
					  <th height=40 style="word-wrap:break-word;">总学分</th>
					  <th height=40 style="word-wrap:break-word;">补考门数</th>
					  <th height=40 style="word-wrap:break-word;">补考学分</th>
					  <th height=40 style="word-wrap:break-word;">重修门数</th>
					  <th height=40 style="word-wrap:break-word;">重修学分</th>
					  <th height=40 style="word-wrap:break-word;">不及格门数</th>
	
					  <th height=40 style="word-wrap:break-word;">不及格学分</th>
					  <th height=40 style="word-wrap:break-word;">获得学分</th>
					  <th height=40 style="word-wrap:break-word;">还差学分</th>
					  <th height=40 style="word-wrap:break-word;">入学年级</th>';
				echo '</tr>';
	
	
				while($usr=$result->fetch()){
					{
						$HCXF=$usr[9]-$usr[16]; //还差学分
						echo '<tr>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[0].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[1].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[2].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[3].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[4].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[6].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[7].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[8].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[9].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[10].'</td>';
	
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[11].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[12].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[13].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[14].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[15].'</td>';
	
						echo '<td align="center" height=40>'.$usr[16].'</td>';
	
						echo '<td align="center" height=40>'.$HCXF.'</td>';
	
						echo '<td align="center" height=40  style="word-wrap:break-word;">'.$usr[17].'</td>';
	
						echo '</tr>';
					}
				}
				echo "</table>";
			}
		}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	/////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////管理员界面--高级查询---未选择学期时导出/////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////
	//
	public function ConditionExcelAction()
	{
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "conditionexcel"
		));
	}
	/////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////管理员界面--高级查询---选择学期时导出/////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////
	//
	public function xnqConditionExcelAction()
	{
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "xnqconditionexcel"
		));
	}

}
