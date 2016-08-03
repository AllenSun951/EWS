<?php
session_start();
use Phalcon\Mvc\Model\Query;
header("Content-type:text/html;charset=utf-8");
use Phalcon\Db\Adapter\Pdo\Oracle as DbAdapter;
class StudentController extends ControllerBase
{
/* 	//学生主界面
	public function indexAction(){
		$this->response->redirect("idx.php");
	}
	 */
	
	//警示结果统计----学期初始化
	public function xnqAction(){
		//$x=$this->request->get("xy");
		try{
		$conn =$this->getDI()->get("db");
		$sql1="select distinct xnq  from XQView order by xnq desc";
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
	try{
		$conn =$this->getDI()->get("db");
		$sql1="select distinct(xy) from QUERYVIEW order by xy";
		$xyresult= $conn->query($sql1);
		echo '<option value=\'00\'>未选择学院</option>';
		while($usr=$xyresult->fetch()){
			echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
		}}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//警示结果统计----专业初始化
	public function majorAction(){
		try{
		$x=$this->request->get("xy");
		$conn =$this->getDI()->get("db");
		$sql="select distinct(zy) from QUERYVIEW where xy='" . $x ."' order by zy";
		$result = $conn->query($sql);
		echo '<option value=\'00\'>未选择专业</option>';
		while($usr=$result->fetch()){
			echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
		}}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//警示结果统计----学院初始化
	public function gradeAction(){
		try{
		$x=$this->request->get("zy");
		$conn =$this->getDI()->get("db");
		$sql="select distinct(bj) from QUERYVIEW where zy='" . $x ."'order by bj";
		$result = $conn->query($sql);
		echo '<option value=\'00\'>未选择班级</option>';
		while($usr=$result->fetch()){
			echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
		}}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	//警示结果统计----学生初始化
	public function studentAction(){
		try{
		$x=$this->request->get("bj");
		$conn =$this->getDI()->get("db");
		$sql="select  distinct xh,xm from MY_XYJS where bj='" . $x ."' order by xm";
		$result = $conn->query($sql);
		echo '<option value=\'00\'>未选择学生</option>';
		while($usr=$result->fetch()){
			echo '<option value=\'' . $usr[0] . '\'>' . $usr[1] . '</option>';
		}}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	//警示结果展示----学生---views/front/result.volt
	public function resultAction(){
		if(!isset($_SESSION['userid'])||$_SESSION['userqx']<>0){
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
		$this->view->setVar("sessionName",$_SESSION['username']);
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//警示结果统计----学生不及格课程
	public function failCourseAction(){
		if(!isset($_SESSION['userid'])||$_SESSION['userqx']<>0){
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
		$this->view->setVar("sessionName",$_SESSION['username']);
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//警示结果统计----学分完成情况
	public function creditFinishAction(){
		if(!isset($_SESSION['userid'])||$_SESSION['userqx']<>0){
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
		$this->view->setVar("sessionName",$_SESSION['username']);
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/**
	 *
	*  学生情况
	*/
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//学生界面警示结果查询
	
	public function jsjgckbuttonAction(){
		//学生
		$x=$_SESSION['userid'];
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
				
			//echo '<center>';
			echo '<br/><br/><br/>';
			echo '<font color="#4B0082">&nbsp&nbsp后台数据正在更新，请耐心等待....................</font>';
			//echo '</center>';
		}
		else{
				echo '<center><font size= "5" face="微软雅黑" >学号：'.$x.'&nbsp;&nbsp;&nbsp;姓名：'.$_SESSION['username'].' 同学的警示结果</font></center>';
				echo '<br/>';
				echo '<table align="center" width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB" >';
				echo '<tr>';
				echo '<th height=40 style="word-wrap:break-word;">学年/学期</th>
					  <th height=40 style="word-wrap:break-word;">学院</th>
					  <th height=40 style="word-wrap:break-word;">专业</th>
					  <th height=40 style="word-wrap:break-word;">班级</th>
					  <th height=40 style="word-wrap:break-word;">警示级别</th>
				 	  <th height=40 style="word-wrap:break-word;">警示原因</th>
					  <th height=40 style="word-wrap:break-word;">入学年级</th>';
				echo '</tr>';
				$sql="select  XNQ,XY,ZY,BJ,XM,JSJB,JSYY,NJ  from MY_XYJS2 where  xh like '%" . $x ."%' order by xnq";
				//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
				$result = $conn->query($sql);
			
				while($usr=$result->fetch())
				{
					{
							
					echo '<tr>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[0].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[1].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[2].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[3].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[6].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[7].'</td>';
						
						echo '</tr>';
						
					}
			
				}
			
				$sql2="select  XY,ZY,BJ,XM,JSJB,JSYY,NJ  from MY_XYJS where  xh like '%" . $x ."%'";
				//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
				$result2 = $conn->query($sql2);
				while($usr=$result2->fetch()){
					{
							
							
						echo '<tr>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">当前总警示</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[0].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[1].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[2].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[4].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[6].'</td>';
			
						echo '</tr>';
					}
			
				}
				echo "</table>";
		}}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	//学生界面不及格课程查询
	public function bjgckbuttonAction(){
		//alert("不及格课程调用成功！");
		$x=$_SESSION['userid'];
		//$x="王珊珊";
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
			
			//echo '<center>';
			echo '<br/><br/><br/>';
			echo '<font color="#4B0082">&nbsp&nbsp后台数据正在更新，请耐心等待....................</font>';
			//echo '</center>';
		}
		else{
			    $sql="select XN,XQ, KCMC,ZSCJ from CJB where xh like '%" . $x ."%' and ZSCJ<'60' order by XN,XQ";
				$result = $conn->query($sql);
				echo '<br/>';
				echo '<center><font size= "5" face="微软雅黑" >学号：'.$x.'&nbsp;&nbsp;&nbsp;姓名：'.$_SESSION['username'].' 同学不及格课程统计</font></center>';
				echo '<br/>';
				echo '<table align="center" width="80%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
				//echo '<caption><h2>不及格课程</h2></caption>';
				//echo '<caption>'.$x.'</caption>';
				
				echo '<tr>';
				echo '<th height=40 style="word-wrap:break-word;">学年</th>
				 <th height=40 style="word-wrap:break-word;">课程名称</th>
			 	 <th height=40 style="word-wrap:break-word;">成绩</th>';
				echo '</tr>';
				$a=array("总计",0,"-");
				while($usr=$result->fetch()){
					{
						echo '<tr>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[0].'-'.$usr[1].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[2].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[3].'</td>';
						echo '</tr>';
						$a[1]+=1;
					}
				}
				echo '<tr>';
				echo '<td align="center" height=40><b>'.$a[0].'</b></td>';
				echo '<td align="center" height=40>'.$a[1].'</td>';
				echo '<td align="center" height=40>'.$a[2].'</td>';
				echo '</tr>';
				echo "</table>";
		}}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	//学生界面学分情况查询
	public function xfckbuttonAction(){
		//学生
		$x=$_SESSION['userid'];
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
				
			//echo '<center>';
			echo '<br/><br/><br/>';
			echo '<font color="#4B0082">&nbsp&nbsp后台数据正在更新，请耐心等待....................</font>';
			//echo '</center>';
		}
		else{   echo '<br/>';
				echo '<center><font size= "5" face="微软雅黑" > 学号：'.$x.'&nbsp;&nbsp;&nbsp;姓名：'.$_SESSION['username'].' 同学的学分统计表</font></center>';
				echo '<br/>';
				echo '<table align="center" width="80%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
				echo '<tr>';
				echo '<th height=40>学期</th>
				 <th height=40 style="word-wrap:break-word;">总门数</th>
			     <th height=40 style="word-wrap:break-word;">总学分</th>
				 <th height=40 style="word-wrap:break-word;">获得学分</th>
				 <th height=40 style="word-wrap:break-word;">补考门数</th>
				 <th height=40 style="word-wrap:break-word;">补考学分</th>
				 <th height=40 style="word-wrap:break-word;">重修门数</th>
			 	 <th height=40 style="word-wrap:break-word;">重修学分</th>
				 <th height=40 style="word-wrap:break-word;">还差学分</th>';
				echo '</tr>';
				
				$sql="select XNQ,ZMS,ZXF,HDXF,BKMS,BKXF,CXMS,CXXF from MY_XYJS2 where xh like '%" . $x ."%' order by XNQ";
				$result = $conn->query($sql);
				while($usr=$result->fetch()){
					{
						$HCXF=$usr[2]-$usr[3];
						echo '<tr>';
						echo '<td align="center" height=40>'.$usr[0].'</td>';
						echo '<td align="center" height=40>'.$usr[1].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[2].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[3].'</td>';
						echo '<td align="center" height=40 >'.$usr[4].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
						echo '<td align="center" height=40>'.$usr[6].'</td>';
						echo '<td align="center" height=40>'.$usr[7].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$HCXF.'</td>';
						echo '</tr>';
					}
				}
			
				$sql2="select  ZMS,ZXF,HDXF,BKMS,BKXF,CXMS,CXXF from MY_XYJS where xh like '%" . $x ."%'";
				$result2 = $conn->query($sql2);
					
				while($usr=$result2->fetch()){
					{
						$HCXF=$usr[1]-$usr[2];
						echo '<tr>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">当前总学分完成情况</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[0].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[1].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[2].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[3].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[4].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[6].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$HCXF.'</td>';
							
						echo '</tr>';
					}	
				}
				echo "</table>";
		}}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
/* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////// 带学期的 学生 个人信息统计情况！///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function xnqjsjgckbuttonAction(){
		//每学期学生的警示结果
		$y=$this->request->get("user");
		$x=$_SESSION['userid'];
	 try{
		echo '<table align="center" width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
		echo '<caption><font size= "3" face="微软雅黑" >学期'.$y.'&nbsp;&nbsp;&nbsp;学号：'.$x.'&nbsp;&nbsp;&nbsp;'.$_SESSION['username'].' 同学的警示结果</font></caption>';
		//echo '<caption>'.$x.'</caption>';
		echo '<br/>';
		echo '<tr>';
		echo '<th height=40 style="word-wrap:break-word;">学年/学期</th>
			  <th height=40 style="word-wrap:break-word;">学院</th>
			  <th height=40 style="word-wrap:break-word;">专业</th>
			  <th height=40 style="word-wrap:break-word;">班级</th>
			  	<th height=40 style="word-wrap:break-word;">警示级别</th>
		      <th height=40 style="word-wrap:break-word;">警示原因</th>
			  <th height=40 style="word-wrap:break-word;">入学年级</th>';
		echo '</tr>';
		$conn =$this->getDI()->get("db");
		$sql="select  XNQ,XY,ZY,BJ,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGXF,HDXF,NJ  from MY_XYJS2 where  xnq='" . $y ."'and xh like '%".$x."%'";
		$result = $conn->query($sql);
		while($usr=$result->fetch()){
			{

	
				echo '<tr>';
				echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[0].'</td>';
				echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[1].'</td>';
				echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[2].'</td>';
				echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[3].'</td>';
				echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
				echo '</tr>';
			}
		}
		echo "</table>";
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}

	public function xnqbjgckbuttonAction(){
		//每学期学生的不及格
		//alert("警告");
		$y=$this->request->get("user");
		$x=$_SESSION['userid'];
    try{
		$conn =$this->getDI()->get("db");
		$sql="select  XNQ, KCMC,ZSCJ from XNQ_CJB_VIEW  where xnq='". $y ."'and xh like '%".$x."%'	and ZSCJ<'60'";
		$result = $conn->query($sql);
		echo '<table align="center" width="80%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
		//echo '<caption><h2>不及格课程</h2></caption>';
		echo '<caption><font size= "3" face="微软雅黑" > 学期'.$y.'&nbsp;&nbsp;&nbsp;学号：'.$x.'&nbsp;&nbsp;&nbsp;'.$_SESSION['username'].'同学不及格课程统计表</font></caption>';
		//echo '<caption>'.$x.'</caption>';
		echo '<br/>';
		echo '<tr>';
		echo '<th height=40 style="word-wrap:break-word;">学年</th>
		 <th height=40 style="word-wrap:break-word;">课程名称</th>
	 	 <th height=40 style="word-wrap:break-word;">成绩</th>';
		
		echo '</tr>';
		$a=array("总计",0,"-");
		while($usr=$result->fetch()){
			{
				echo '<tr>';
				echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[0].'</td>';
				echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[1].'</td>';
				echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[2].'</td>';
				echo '</tr>';
				$a[1]+=1;
			}

		}
		echo '<tr>';
		echo '<td align="center" height=40><b>'.$a[0].'</b></td>';
		echo '<td align="center" height=40>'.$a[1].'</td>';
		echo '<td align="center" height=40>'.$a[2].'</td>';
		echo '</tr>';
		echo "</table>";
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	// 每学期的学分完成情况！
	public function xnqxfckbuttonAction(){
		//每学期学生的警示结果
		$y=$this->request->get("user");
		$x=$_SESSION['userid'];
    try{
		$conn =$this->getDI()->get("db");
		$sql="select  XNQ,ZMS,ZXF,HDXF,BKMS,BKXF,CXMS,CXXF from MY_XYJS2 where xnq='".$y."' and xh like '%".$x."%'";
		$result = $conn->query($sql);
		echo '<table align="center" width="80%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
		//echo '<caption><h2>不及格课程</h2></caption>';
		echo '<caption><font size= "3" face="微软雅黑" > 学期'.$y.'&nbsp;&nbsp;&nbsp;学号：'.$x.'&nbsp;&nbsp;&nbsp;'.$_SESSION['username'].'同学学分统计表</font></caption>';
		echo '<br/>';
		echo '<tr>';
		echo '<th height=40>学期</th>
		 <th height=40 style="word-wrap:break-word;">总门数</th>
	     <th height=40 style="word-wrap:break-word;">总学分</th>
		 <th height=40 style="word-wrap:break-word;">获得学分</th>
		 <th height=40 style="word-wrap:break-word;">补考门数</th>
		 <th height=40 style="word-wrap:break-word;">补考学分</th>
		 <th height=40 style="word-wrap:break-word;">重修门数</th>
	 	 <th height=40 style="word-wrap:break-word;">重修学分</th>
		 <th height=40 style="word-wrap:break-word;">还差学分</th>';
		echo '</tr>';

		while($usr=$result->fetch()){
			{
				$HCXF=$usr[2]-$usr[3];
				echo '<tr>';
				echo '<td align="center" height=40>'.$usr[0].'</td>';
				echo '<td align="center" height=40>'.$usr[1].'</td>';
				echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[2].'</td>';
				echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[3].'</td>';
				echo '<td align="center" height=40 >'.$usr[4].'</td>';
				echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
				echo '<td align="center" height=40>'.$usr[6].'</td>';
				echo '<td align="center" height=40>'.$usr[7].'</td>';
				echo '<td align="center" height=40 style="word-wrap:break-word;">'.$HCXF.'</td>';
				echo '</tr>';
			}
		 }
		echo "</table>";
        }catch(Exception $e) {
		echo '<font color="#4B0082">数据库连接错误！</font>';
		}
   } */
   
   /////////////////////////////////////////////////////////////////////////////////////////////////
   ////////////////////////////      学生统计图         /////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////////////////////////////////////////////
   //  学生  柱形图
   public function xsChartAction()
   {
   	//创建一个新的excel对象
   	$this->dispatcher->forward(array(
   			"controller" => "ChartStu",
   			"action" => "xsChart"
   	));
   	//echo 'hello';
   }
}

