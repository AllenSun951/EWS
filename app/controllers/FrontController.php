<?php
session_start();
use Phalcon\Mvc\Model\Query;
header("Content-type:text/html;charset=utf-8");
use Phalcon\Db\Adapter\Pdo\Oracle as DbAdapter;
//include '/../../app/library/PHPExcel.php';
class FrontController extends ControllerBase{
	 //学生主界面
	public function indexAction(){
		//$this->response->redirect("idx.php");
	} 
	//    管理人员主界面
	public function adminAction(){
	
		$this->response->redirect("indexManager.php");
	
	}

	//警示结果展示----管理人员
	public function resultAlertAction(){
		//判断非法登录
		if(!isset($_SESSION['userid'])||$_SESSION['userqx']<>5){
			//header("Location:../login.php");
			echo '<br/>';echo '<br/>';
			echo '未登录！请点击此处 <a href="../login.php" target="_top">登录</a>';
			exit();
		}
			try{
			$conn =$this->getDI()->get("db");
			$sql="select distinct xnq from XQView order by xnq desc"; //没起作用！！！ 被下面初始化后，被覆盖了
			$result = $conn->query($sql); // 初始化 学期
			$this->view->setVar("xqs",$result);
			$sql1="select distinct nj  from XQView where nj is not null order by nj  desc";
			$result1= $conn->query($sql1); //初始化 年级，与 画图部分出现一样的毛病，初始化年级必须放在 .volt文件里。
			$this->view->setVar("njs",$result1);
			$sql2="select distinct(xy) from QUERYVIEW order by xy";
			$result2= $conn->query($sql2); //初始化 学院
			$this->view->setVar("xys",$result2);
			// 获取 session 中的用户ID，传给页面 
			$this->view->setVar("sessionName",$_SESSION['username']);
			}catch(Exception $e) {
				echo '<font color="#4B0082">数据库连接错误！</font>';
			}
		}

	public function testAction(){
		
	}
	
	//警示结果统计----管理人员图标显示
	public function statAlertAction(){
	
	}
	//警示结果统计----年级初始化
	public function njAction(){
		//$x=$this->request->get("xy");
		try{
			$conn =$this->getDI()->get("db");
			$sql1="select distinct nj  from XQView where nj is not null order by nj  desc";
			$njresult= $conn->query($sql1);
			echo '<option value=\'00\'>未选择年级</option>';
			while($usr=$njresult->fetch()){
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
			$xqresult= $conn->query($sql1);
			echo '<option value=\'00\'>未选择学期</option>';
			while($usr=$xqresult->fetch()){
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
		}
		}catch(Exception $e) {
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
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//警示结果统计----班级初始化
	public function gradeAction(){
		try{
			$x=$this->request->get("zy");
			$conn =$this->getDI()->get("db");
			$sql="select distinct(bj) from QUERYVIEW where zy='" .$x ."'order by bj";
			$result = $conn->query($sql);
			echo '<option value=\'00\'>未选择班级</option>';
			while($usr=$result->fetch()){
				echo '<option value=\'' . $usr[0] . '\'>' . $usr[0] . '</option>';
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//警示结果统计----学生初始化
	public function studentAction(){
		try{
			$x=$this->request->get("bj");
			$conn =$this->getDI()->get("db");
			$sql="select  distinct xh,xm from MY_XYJS2 where bj='" . $x ."' order by xm";
			$result = $conn->query($sql);
			echo '<option value=\'00\'>未选择学生</option>';
			while($usr=$result->fetch()){
				echo '<option value=\'' . $usr[0] . '\'>' . $usr[1] . '</option>';
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
			$sql1="select distinct bj  from QUERYVIEW where nj ='".$x."' and zy like '%".$y."' order by bj";
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
// -----------------------------------------------------------------------------------------------
	//警示结果统计----管理员高级查询初始化 -- 不可删除
	public function conditionAction(){
		
	}
	
	public function JSchartAction(){
		//echo 'aaa';
		echo '<br/>';
		$this->dispatcher->forward(array(
				"controller" => "Chart",
				"action" => "JSchart"
		));
		echo '<br/>'; 
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////    管理人员主界面         //////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
/////////////////////////////////////////不带学期的  管理人员   警示 情况统计。。
////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	/**
	 * 
	 * 不带学期的 预警情况统计。。
	 */
	// 学校
	public function xxckbuttonAction(){
		//学院。
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
					//$sql="select XH,XM,ZY,BJ,JSJB,JSYY,HDXF,NJ from MY_XYJS where xy='" . $x ."'";
					//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO  order by xy";
					$sql="select XY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS) from XUEXIAO where XY is not null group by xy order by xy";
					$result = $conn->query($sql);
					
					echo '<table align="center" width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB" >';
					echo '<caption><font size= "5" face="微软雅黑" >学校警示结果</font></caption>';
					//echo '<caption>'.$x.'</caption>';
					echo '<br/>';
					echo '<tr>';
					echo '<th height=40 style="word-wrap:break-word;">学院</th>
					  <th height=40 style="word-wrap:break-word;">I级警示</th>
					  <th height=40 style="word-wrap:break-word;">II级警示</th>
				      <th height=40 style="word-wrap:break-word;">III级警示</th>
			 	      <th height=40 style="word-wrap:break-word;">警示总人数</th>
			 	      <th height=40 style="word-wrap:break-word;">总人数</th>';
					echo '</tr>';
					
					$a=array("总计",0,0,0,0,0);
					while($usr=$result->fetch()){
						{
							echo '<tr>';
							echo '<td align="center" height=40>'.$usr[0].'</td>';
							echo '<td align="center" height=40>'.$usr[1].'</td>';
							echo '<td align="center" height=40>'.$usr[2].'</td>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[3].'</td>';
							echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[4].'</td>';
							echo '<td align="center" height=40>'.$usr[5].'</td>';
							echo '</tr>';
							$a[1]+=$usr[1];$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];
						}
					}
					echo '<tr>';
					echo '<td align="center" height=40><b>'.$a[0].'</b></td>';
					echo '<td align="center" height=40>'.$a[1].'</td>';
					echo '<td align="center" height=40>'.$a[2].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[3].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[4].'</td>';
					echo '<td align="center" height=40>'.$a[5].'</td>';
					echo '</tr>';
					echo "</table>";
				
			}
			}catch(Exception $e) {
				echo '<font color="#4B0082">数据库连接错误！</font>';
			}
	}

	// 学院
	public function xyckbuttonAction(){
		//学院。
		try{
			$x=$this->request->get("xy");
			$conn =$this->getDI()->get("db");
			//$sql="select XH,XM,ZY,BJ,JSJB,JSYY,HDXF,NJ from MY_XYJS where xy='" . $x ."'";
			$sql="select XY,ZY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS) from XUEYUAN where xy like '%" . $x ."%' group by XY,ZY order by zy";
			$result = $conn->query($sql);
			echo '<table align="center" width="90%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB" >';
			echo '<caption><font size= "5" face="微软雅黑" >'.$x.'警示结果</font></caption>';
			//echo '<caption>'.$x.'</caption>';
			echo '<br/>';
			echo '<tr>';
			echo '<th height=40 style="word-wrap:break-word;">学院</th>
				  <th height=40 style="word-wrap:break-word;">专业</th>
				  <th height=40 style="word-wrap:break-word;">I级警示</th>
				  <th height=40 style="word-wrap:break-word;">II级警示</th>
			      <th height=40 style="word-wrap:break-word;">III级警示</th>
		 	      <th height=40 style="word-wrap:break-word;">警示总人数</th>
		 	      <th height=40 style="word-wrap:break-word;">总人数</th>';
			echo '</tr>';
			$a=array("总计",0,0,0,0,0,0);
			while($usr=$result->fetch()){
				{
					echo '<tr>';
					echo '<td align="center" height=40>'.$usr[0].'</td>';
					echo '<td align="center" height=40>'.$usr[1].'</td>';
					echo '<td align="center" height=40>'.$usr[2].'</td>';
					echo '<td align="center" height=40>'.$usr[3].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[4].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
					echo '<td align="center" height=40>'.$usr[6].'</td>';
					echo '</tr>';
					$a[1]+=1;$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];
				}
			}
			echo '<tr>';
			echo '<td align="center" height=40><b>'.$a[0].'</b></td>';
			echo '<td align="center" height=40>'.$a[1].'</td>';
			echo '<td align="center" height=40>'.$a[2].'</td>';
			echo '<td align="center" height=40>'.$a[3].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[4].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[5].'</td>';
			echo '<td align="center" height=40>'.$a[6].'</td>';
			echo '</tr>';
			echo "</table>";
			}catch(Exception $e) {
				echo '<font color="#4B0082">数据库连接错误！</font>';
			}
	}
	
	// 专业
	public function zyckbuttonAction(){
		//专业
		try {
			$x=$this->request->get("zy");
			$conn =$this->getDI()->get("db");
			//$sql="select XH,XM,BJ,JSJB,JSYY,HDXF,NJ from MY_XYJS where zy='" . $x ."'";
			$sql="select BJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from ZHUANYE where zy = '".$x."' group by BJ order by bj";
			$result = $conn->query($sql);
			echo '<table align="center" width="80%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB" >';
			echo '<caption><font size= "5" face="微软雅黑" >'.$x.'专业警示结果</font></caption>';
			//echo '<caption>'.$x.'</caption>';
			echo '<tr>';
			echo '
				  <th height=40 style="word-wrap:break-word;">班级</th>
				  <th height=40 style="word-wrap:break-word;">I级警示</th>
				  <th height=40 style="word-wrap:break-word;">II级警示</th>
			      <th height=40 style="word-wrap:break-word;">III级警示</th>
		 	      <th height=40 style="word-wrap:break-word;">警示总人数</th>
		 	      <th height=40 style="word-wrap:break-word;">总人数</th>';
			echo '</tr>';
			$a=array("总计",0,0,0,0,0);
			while($usr=$result->fetch()){
				{
					echo '<tr>';
					echo '<td align="center" height=40>'.$usr[0].'</td>';
					echo '<td align="center" height=40>'.$usr[1].'</td>';
					echo '<td align="center" height=40>'.$usr[2].'</td>';
						
					echo '<td align="center"  height=40 style="word-wrap:break-word;">'.$usr[3].'</td>';
					echo '<td align="center"  height=40 style="word-wrap:break-word;">'.$usr[4].'</td>';
					echo '<td align="center" height=40>'.$usr[5].'</td>';
					echo '</tr>';
					$a[1]+=$usr[1];$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];
		
				}
		
			}
			echo '<tr>';
			echo '<td align="center" height=40><b>'.$a[0].'</b></td>';
			echo '<td align="center" height=40>'.$a[1].'</td>';
			echo '<td align="center" height=40>'.$a[2].'</td>';
			echo '<td align="center" height=40>'.$a[3].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[4].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[5].'</td>';
			echo '</tr>';
			echo "</table>";
			}catch(Exception $e) {
				echo '<font color="#4B0082">数据库连接错误！</font>';
			}
	}

	//班级
	public function bjckbuttonAction(){ //班级
		//班级
		try{
			$x=$this->request->get("bj");
			$conn =$this->getDI()->get("db");
		
			$sql="select XY,ZY,XH,XM,JSJB,JSYY,HDXF,NJ from MY_XYJS where bj like '%" . $x ."%'order by xh";
			$result = $conn->query($sql);
			$sql1="select count (distinct xh) from MY_XYJS   where bj='" . $x ."'";
			$number = $conn->query($sql1);
			$bjnum=0; //记录总人数
			while($numb=$number->fetch()){
				$bjnum+=$numb[0];
			}
			echo '<table align="center" width="95%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
			echo '<caption><font size= "5" face="微软雅黑" >'.$x.'班级警示结果（'.$bjnum.' 人）</font></caption>';
			echo '<tr>';
			echo '<th height=40 style="word-wrap:break-word;">学院</th>
				  <th height=40 style="word-wrap:break-word;">专业</th>
				  <th height=40 style="word-wrap:break-word;">学号</th>
				  <th height=40 style="word-wrap:break-word;">姓名</th>
	         	  <th height=40 style="word-wrap:break-word;">警示级别</th>
		 	 	  <th height=40 style="word-wrap:break-word;">警示原因</th>
		 	 	  <th height=40 style="word-wrap:break-word;">获得学分</th>
			 	  <th height=40 style="word-wrap:break-word;">年级</th>';
			echo '</tr>';
			$a=array("总计","-",0,"-",0,"-","-","-");
			while($usr=$result->fetch()){
				{
					echo '<tr>';
					echo '<td align="center" height=40>'.$usr[0].'</td>';
					echo '<td align="center" height=40>'.$usr[1].'</td>';
					echo '<td align="center" height=40>'.$usr[2].'</td>';
					echo '<td align="center" height=40>'.$usr[3].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;" >'.$usr[4].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
					echo '<td align="center" height=40>'.$usr[6].'</td>';
					echo '<td align="center" height=40>'.$usr[7].'</td>';
					echo '</tr>';
					//$a[2]+=1;
					if($usr[4]!="无"){
						$a[4]+=1;
					}
					
				}
			}
			$a[2]=$bjnum;
			echo '<tr>';
			echo '<td align="center" height=40>'.$a[0].'</td>';
			echo '<td align="center" height=40>'.$a[1].'</td>';
			echo '<td align="center" height=40>'.$a[2].'</td>';
			echo '<td align="center" height=40>'.$a[3].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;" >'.$a[4].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[5].'</td>';
			echo '<td align="center" height=40>'.$a[6].'</td>';
			echo '<td align="center" height=40>'.$a[7].'</td>';
			echo '</tr>';
			echo "</table>";
			}catch(Exception $e) {
				echo '<font color="#4B0082">数据库连接错误！</font>';
			}
	}
	//学生
	public function xsckbuttonAction(){
		//学生
		try{
			$x=$this->request->get("xs");
			//$x="钱蕊";
			$conn =$this->getDI()->get("db");
			$sql="select XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGXF,HDXF,NJ from MY_XYJS where XH like '%" . $x ."%'order by xh";
			//$sql1="select  count(*) from MY_XYJS where XM='" . $x ."'";
			$result = $conn->query($sql);
			echo '<table align="center" width="80%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
		
			echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学生警示结果</font></caption>';
			//echo '<caption>'.$x.'</caption>';
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
			}catch(Exception $e) {
				echo '<font color="#4B0082">数据库连接错误！</font>';
			}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////										带学期的	  统计结果					////////////////
// 输出整个学校的 各个学院的 统计情况：
	public function xnxxckbuttonAction(){
		//学年学期
		try{
			$x=$this->request->get("xnq");
			$conn =$this->getDI()->get("db");
			$sql="select  XNQ,XY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from XNQXUEXIAO  where xy is not null and xnq='" . $x ."' group by xnq,xy order by xy";
			//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
			$result = $conn->query($sql);
			echo '<table align="center" width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
			echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期全校警示结果</font></caption>';
			//echo '<caption>'.$x.'</caption>';
			echo '<br/>';
			echo '<tr>';
			echo '<th height=40 style="word-wrap:break-word;">学年/学期</th>
				  <th height=40 style="word-wrap:break-word;">学院</th>
				  <th height=40 style="word-wrap:break-word;">I级警示</th>
				  <th height=40 style="word-wrap:break-word;">II级警示</th>
			      <th height=40 style="word-wrap:break-word;">III级警示</th>
		 	      <th height=40 style="word-wrap:break-word;">警示总人数</th>
		 	      <th height=40 style="word-wrap:break-word;">总人数</th>';
			echo '</tr>';
		
			$a=array("总计","-",0,0,0,0,0);
			while($usr=$result->fetch()){
				{
					echo '<tr>';
					echo '<td align="center" height=40>'.$usr[0].'</td>';
					echo '<td align="center" height=40>'.$usr[1].'</td>';
					echo '<td align="center" height=40>'.$usr[2].'</td>';
					echo '<td align="center" height=40>'.$usr[3].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[4].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
					echo '<td align="center" height=40>'.$usr[6].'</td>';
					echo '</tr>';
					$a[1]+=1;$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];
				}
			}
			echo '<tr>';
			echo '<td align="center" height=40><b>'.$a[0].'</b></td>';
			echo '<td align="center" height=40>'.$a[1].'</td>';
			echo '<td align="center" height=40>'.$a[2].'</td>';
			echo '<td align="center" height=40>'.$a[3].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[4].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[5].'</td>';
			echo '<td align="center" height=40>'.$a[6].'</td>';
			echo '</tr>';
			echo "</table>";
			}catch(Exception $e) {
				echo '<font color="#4B0082">数据库连接错误！</font>';
			}
	}	
// 输出某个学院的 各个专业的 统计情况：	
	public function xnxyckbuttonAction(){
		//学年学期
		try{
			$str=$this->request->get("xnq");	
			$x=substr($str,0,11);
			$y=substr($str,11);
			//echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期'.$y.'警示结果</font></caption>';
			 $conn =$this->getDI()->get("db");
			$sql="select  XNQ,XY,ZY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from XNQXUEYUAN where xnq='" . $x ."' and xy like '%" . $y ."%' group by XNQ,XY,ZY order by zy";
			//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
			$result = $conn->query($sql);
		
			echo '<table align="center" width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB" >';
			echo '<caption><font size= "5" face="微软雅黑" >'.$x.' 学期    '.$y.'  警示结果</font></caption>';
			//echo '<caption>'.$x.'</caption>';
			echo '<br/>';
			echo '<tr>';
			echo '<th height=40 style="word-wrap:break-word;">学年/学期</th>
				  <th height=40 style="word-wrap:break-word;">学院</th>
				  <th height=40 style="word-wrap:break-word;">专业</th>
				  <th height=40 style="word-wrap:break-word;">I级警示</th>
				  <th height=40 style="word-wrap:break-word;">II级警示</th>
			      <th height=40 style="word-wrap:break-word;">III级警示</th>
		 	      <th height=40 style="word-wrap:break-word;">警示总人数</th>
		 	      <th height=40 style="word-wrap:break-word;">总人数</th>';
			echo '</tr>';
			$a=array("总计","-",0,0,0,0,0,0);
			while($usr=$result->fetch()){
				{
					echo '<tr>';
					echo '<td align="center" height=40>'.$usr[0].'</td>';
					echo '<td align="center" height=40>'.$usr[1].'</td>';
					echo '<td align="center" height=40>'.$usr[2].'</td>';
					echo '<td align="center" height=40>'.$usr[3].'</td>';
					echo '<td align="center" height=40>'.$usr[4].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[6].'</td>';
					echo '<td align="center" height=40>'.$usr[7].'</td>';
					echo '</tr>';
					$a[2]+=1;$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];$a[7]+=$usr[7];
				}
			}
			echo '<tr>';
			echo '<td align="center" height=40><b>'.$a[0].'</b></td>';
			echo '<td align="center" height=40>'.$a[1].'</td>';
			echo '<td align="center" height=40>'.$a[2].'</td>';
			echo '<td align="center" height=40>'.$a[3].'</td>';
			echo '<td align="center" height=40>'.$a[4].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[5].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[6].'</td>';
			echo '<td align="center" height=40>'.$a[7].'</td>';
			echo '</tr>';
			echo "</table>"; 
			}catch(Exception $e) {
				echo '<font color="#4B0082">数据库连接错误！</font>';
			}
	}
	
    // 输出某个专业的 各个班级的 统计情况：
	public function xnzyckbuttonAction(){
		//学年学期
		try{
			$str=$this->request->get("xnq");
			$x=substr($str,0,11);
			$y=substr($str,11);
		
			//echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期'.$y.'警示结果</font></caption>';
			$conn =$this->getDI()->get("db");
			$sql="select  XNQ,ZY,BJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from XNQZHUANYE where xnq='" . $x ."' and zy = '". $y ."' group by XNQ,ZY,BJ order by bj";
			//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
			$result = $conn->query($sql);
		
			echo '<table align="center" width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
			echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期  '.$y.'  专业警示结果</font></caption>';
			//echo '<caption>'.$x.'</caption>';
			echo '<br/>';
			echo '<tr>';
			echo '<th height=40 style="word-wrap:break-word;">学年/学期</th>
				  <th height=40 style="word-wrap:break-word;">专业</th>
				  <th height=40 style="word-wrap:break-word;">班级</th>
				  <th height=40 style="word-wrap:break-word;">I级警示</th>
				  <th height=40 style="word-wrap:break-word;">II级警示</th>
			      <th height=40 style="word-wrap:break-word;">III级警示</th>
		 	      <th height=40 style="word-wrap:break-word;">警示总人数</th>
		 	      <th height=40 style="word-wrap:break-word;">总人数</th>';
			echo '</tr>';
			
			$a=array("总计","-",0,0,0,0,0,0);
			while($usr=$result->fetch()){
				{
					echo '<tr>';
					echo '<td align="center" height=40>'.$usr[0].'</td>';
					echo '<td align="center" height=40>'.$usr[1].'</td>';
					echo '<td align="center" height=40>'.$usr[2].'</td>';
					echo '<td align="center" height=40>'.$usr[3].'</td>';
					echo '<td align="center" height=40>'.$usr[4].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[6].'</td>';
					echo '<td align="center" height=40>'.$usr[7].'</td>';
					echo '</tr>';
					$a[2]+=1;$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];$a[7]+=$usr[7];
				}
			}
			echo '<tr>';
			echo '<td align="center" height=40><b>'.$a[0].'</b></td>';
			echo '<td align="center" height=40>'.$a[1].'</td>';
			echo '<td align="center" height=40>'.$a[2].'</td>';
			echo '<td align="center" height=40>'.$a[3].'</td>';
			echo '<td align="center" height=40>'.$a[4].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[5].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[6].'</td>';
			echo '<td align="center" height=40>'.$a[7].'</td>';
			echo '</tr>';
			echo "</table>";
			}catch(Exception $e) {
				echo '<font color="#4B0082">数据库连接错误！</font>';
			}
	}
// 输出某班级里的 所有学生的 统计情况：	
	public function xnbjckbuttonAction(){
		//学年学期
		try{
			$str=$this->request->get("xnq");
			$x=substr($str,0,11);
			$y=substr($str,11);
			//echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期'.$y.'警示结果</font></caption>';
			$conn =$this->getDI()->get("db");
			$sql="select  XNQ,ZY,BJ,XH,XM,JSJB,JSYY,NJ  from MY_XYJS2 where xnq='" . $x ."' and bj like '%" . $y ."%' order by xh";
			//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
			$result = $conn->query($sql);
			//统计班级人数
			$sql1="select count (distinct xh) from MY_XYJS2 where xnq='" . $x ."' and bj='" . $y ."'";
			$number = $conn->query($sql1);
			$bjnum=0; //记录总人数
			while($numb=$number->fetch()){
				$bjnum+=$numb[0];
			}
			echo '<table align="center" width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
			echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期   '.$y.'  班级警示结果（'.$bjnum.' 人）</font></caption>';
			//echo '<caption>'.$x.'</caption>';
			echo '<br/>';
			echo '<tr>';
			echo '<th height=40 style="word-wrap:break-word;">学年/学期</th>
				  <th height=40 style="word-wrap:break-word;">专业</th>
				  <th height=40 style="word-wrap:break-word;">班级</th>
				  <th height=40 style="word-wrap:break-word;">学号</th>
				  <th height=40 style="word-wrap:break-word;">姓名</th>
				  <th height=40 style="word-wrap:break-word;">警示级别</th>
			      <th height=40 style="word-wrap:break-word;">警示原因</th>
				  <th height=40 style="word-wrap:break-word;">年级</th>';
			echo '</tr>';
			$a=array("总计","-","-",0,"-",0,"-","-");
			while($usr=$result->fetch()){
				{
					echo '<tr>';
					echo '<td align="center" height=40>'.$usr[0].'</td>';
					echo '<td align="center" height=40>'.$usr[1].'</td>';
					echo '<td align="center" height=40>'.$usr[2].'</td>';
					echo '<td align="center" height=40>'.$usr[3].'</td>';
					echo '<td align="center" height=40>'.$usr[4].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[6].'</td>';
					echo '<td align="center" height=40>'.$usr[7].'</td>';
					echo '</tr>';
				
					if($usr[5]!="无"){
						$a[5]+=1;
					}
				}
	
			}
			$a[3]=$bjnum;
			echo '<tr>';
			echo '<td align="center" height=40>'.$a[0].'</td>';
			echo '<td align="center" height=40>'.$a[1].'</td>';
			echo '<td align="center" height=40>'.$a[2].'</td>';
			echo '<td align="center" height=40>'.$a[3].'</td>';
			echo '<td align="center" height=40>'.$a[4].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[5].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[6].'</td>';
			echo '<td align="center" height=40>'.$a[7].'</td>';
			echo '</tr>';
			echo "</table>";
			}catch(Exception $e) {
				echo '<font color="#4B0082">数据库连接错误！</font>';
			}
	}
	
	// 学生
	public function xnxsckbuttonAction(){
		//学年学期
		try{
			$str=$this->request->get("xnq");
			$x=substr($str,0,11);
			$y=substr($str,11);	
			//echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期'.$y.'警示结果</font></caption>';
			$conn =$this->getDI()->get("db");
			$sql="select  XNQ,XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGMSBL,BJGXF,BJGXFBL,HDXF,NJ  from MY_XYJS2 where xnq='" . $x ."' and XH like '%" . $y ."%' order by xh";
			//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
			$result = $conn->query($sql);
		
			echo '<table align="center" width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB" >';
			echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期   '.$y.'  学生警示结果</font></caption>';
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
					<th height=40 style="word-wrap:break-word;">补考门数</th>';
			echo '</tr>';
	
			while($usr=$result->fetch()){
				{
						
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
			}catch(Exception $e) {
				echo '<font color="#4B0082">数据库连接错误！</font>';
			}
	}
	
	/**----------------------------------------------------------------------------------------
	 * 带 年级的---------------------------------------带年级--------------------------
	 * 不带学期的 预警情况统计。。---------------------------------------------------------------------
	 */
	// 学校
	public function njxxckbuttonAction(){
		//学院。
		$x=$this->request->get("xx");
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
				$sql="select XY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS) from XUEXIAO where XY is not null and nj='".$x."' group by xy order by xy";
				$result = $conn->query($sql);
					
				echo '<table align="center" width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB" >';
				echo '<caption><font size= "5" face="微软雅黑" >'.$x.' 学校警示结果</font></caption>';
				//echo '<caption>'.$x.'</caption>';
				echo '<br/>';
				echo '<tr>';
				echo '<th height=40 style="word-wrap:break-word;">学院</th>
					  <th height=40 style="word-wrap:break-word;">I级警示</th>
					  <th height=40 style="word-wrap:break-word;">II级警示</th>
				      <th height=40 style="word-wrap:break-word;">III级警示</th>
			 	      <th height=40 style="word-wrap:break-word;">警示总人数</th>
			 	      <th height=40 style="word-wrap:break-word;">总人数</th>';
				echo '</tr>';
					
				$a=array("总计",0,0,0,0,0);
				while($usr=$result->fetch()){
					{
						echo '<tr>';
						echo '<td align="center" height=40>'.$usr[0].'</td>';
						echo '<td align="center" height=40>'.$usr[1].'</td>';
						echo '<td align="center" height=40>'.$usr[2].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[3].'</td>';
						echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[4].'</td>';
						echo '<td align="center" height=40>'.$usr[5].'</td>';
						echo '</tr>';
						$a[1]+=$usr[1];$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];
					}
				}
				echo '<tr>';
				echo '<td align="center" height=40><b>'.$a[0].'</b></td>';
				echo '<td align="center" height=40>'.$a[1].'</td>';
				echo '<td align="center" height=40>'.$a[2].'</td>';
				echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[3].'</td>';
				echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[4].'</td>';
				echo '<td align="center" height=40>'.$a[5].'</td>';
				echo '</tr>';
				echo "</table>";
	
			}
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	// 学院
	public function njxyckbuttonAction(){
		//学院。
		try{
			$str=$this->request->get("xy");
			$y=substr($str,0,8);
			$x=substr($str,8);
			$conn =$this->getDI()->get("db");
			//$sql="select XH,XM,ZY,BJ,JSJB,JSYY,HDXF,NJ from MY_XYJS where xy='" . $x ."'";
			$sql="select XY,ZY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS) from XUEYUAN where nj='".$y."' and xy like '%" . $x ."%' group by XY,ZY order by zy";
			$result = $conn->query($sql);
			echo '<table align="center" width="90%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB" >';
			echo '<caption><font size= "5" face="微软雅黑" >'.$y.' '.$x.'警示结果</font></caption>';
			//echo '<caption>'.$x.'</caption>';
			echo '<br/>';
			echo '<tr>';
			echo '<th height=40 style="word-wrap:break-word;">学院</th>
				  <th height=40 style="word-wrap:break-word;">专业</th>
				  <th height=40 style="word-wrap:break-word;">I级警示</th>
				  <th height=40 style="word-wrap:break-word;">II级警示</th>
			      <th height=40 style="word-wrap:break-word;">III级警示</th>
		 	      <th height=40 style="word-wrap:break-word;">警示总人数</th>
		 	      <th height=40 style="word-wrap:break-word;">总人数</th>';
			echo '</tr>';
			$a=array("总计",0,0,0,0,0,0);
			while($usr=$result->fetch()){
				{
					echo '<tr>';
					echo '<td align="center" height=40>'.$usr[0].'</td>';
					echo '<td align="center" height=40>'.$usr[1].'</td>';
					echo '<td align="center" height=40>'.$usr[2].'</td>';
					echo '<td align="center" height=40>'.$usr[3].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[4].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
					echo '<td align="center" height=40>'.$usr[6].'</td>';
					echo '</tr>';
					$a[1]+=1;$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];
				}
			}
			echo '<tr>';
			echo '<td align="center" height=40><b>'.$a[0].'</b></td>';
			echo '<td align="center" height=40>'.$a[1].'</td>';
			echo '<td align="center" height=40>'.$a[2].'</td>';
			echo '<td align="center" height=40>'.$a[3].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[4].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[5].'</td>';
			echo '<td align="center" height=40>'.$a[6].'</td>';
			echo '</tr>';
			echo "</table>";
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	// 专业
	public function njzyckbuttonAction(){
		//专业
		try {
			$str=$this->request->get("zy");
			$y=substr($str,0,8);
			$x=substr($str,8);
			$conn =$this->getDI()->get("db");
			//$sql="select XH,XM,BJ,JSJB,JSYY,HDXF,NJ from MY_XYJS where zy='" . $x ."'";
			$sql="select BJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from ZHUANYE where nj='".$y."' and  zy = '".$x."' group by BJ order by bj";
			$result = $conn->query($sql);
			echo '<table align="center" width="80%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB" >';
			echo '<caption><font size= "5" face="微软雅黑" >'.$y.' '.$x.'专业警示结果</font></caption>';
			//echo '<caption>'.$x.'</caption>';
			echo '<tr>';
			echo '
				  <th height=40 style="word-wrap:break-word;">班级</th>
				  <th height=40 style="word-wrap:break-word;">I级警示</th>
				  <th height=40 style="word-wrap:break-word;">II级警示</th>
			      <th height=40 style="word-wrap:break-word;">III级警示</th>
		 	      <th height=40 style="word-wrap:break-word;">警示总人数</th>
		 	      <th height=40 style="word-wrap:break-word;">总人数</th>';
			echo '</tr>';
			$a=array("总计",0,0,0,0,0);
			while($usr=$result->fetch()){
				{
					echo '<tr>';
					echo '<td align="center" height=40>'.$usr[0].'</td>';
					echo '<td align="center" height=40>'.$usr[1].'</td>';
					echo '<td align="center" height=40>'.$usr[2].'</td>';
	
					echo '<td align="center"  height=40 style="word-wrap:break-word;">'.$usr[3].'</td>';
					echo '<td align="center"  height=40 style="word-wrap:break-word;">'.$usr[4].'</td>';
					echo '<td align="center" height=40>'.$usr[5].'</td>';
					echo '</tr>';
					$a[1]+=$usr[1];$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];
	
				}
	
			}
			echo '<tr>';
			echo '<td align="center" height=40><b>'.$a[0].'</b></td>';
			echo '<td align="center" height=40>'.$a[1].'</td>';
			echo '<td align="center" height=40>'.$a[2].'</td>';
			echo '<td align="center" height=40>'.$a[3].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[4].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[5].'</td>';
			echo '</tr>';
			echo "</table>";
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	//班级
	public function njbjckbuttonAction(){ //班级
		//班级
		try{
			$str=$this->request->get("bj");

			$y=substr($str,0,4); // 表中保存的 2012 不是 2012 级。
			$x=substr($str,8);
			$conn =$this->getDI()->get("db");
	
			$sql="select XY,ZY,XH,XM,JSJB,JSYY,HDXF,NJ from MY_XYJS where nj='".$y."'and bj like '%". $x ."'order by xh";
			$result = $conn->query($sql);
			$sql1="select count (distinct xh) from MY_XYJS  where nj='".$y."'and bj='" . $x ."'";
			$number = $conn->query($sql1);
			$bjnum=0; //记录总人数
			while($numb=$number->fetch()){
				$bjnum+=$numb[0];
			}
			echo '<table align="center" width="95%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
			echo '<caption><font size= "5" face="微软雅黑" >'.$y.'级 '.$x.'班级警示结果（'.$bjnum.' 人）</font></caption>';
			echo '<tr>';
			echo '<th height=40 style="word-wrap:break-word;">学院</th>
				  <th height=40 style="word-wrap:break-word;">专业</th>
				  <th height=40 style="word-wrap:break-word;">学号</th>
				  <th height=40 style="word-wrap:break-word;">姓名</th>
	         	  <th height=40 style="word-wrap:break-word;">警示级别</th>
		 	 	  <th height=40 style="word-wrap:break-word;">警示原因</th>
		 	 	  <th height=40 style="word-wrap:break-word;">获得学分</th>
			 	  <th height=40 style="word-wrap:break-word;">年级</th>';
			echo '</tr>';
			$a=array("总计","-",0,"-",0,"-","-","-");
			while($usr=$result->fetch()){
				{
					echo '<tr>';
					echo '<td align="center" height=40>'.$usr[0].'</td>';
					echo '<td align="center" height=40>'.$usr[1].'</td>';
					echo '<td align="center" height=40>'.$usr[2].'</td>';
					echo '<td align="center" height=40>'.$usr[3].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;" >'.$usr[4].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
					echo '<td align="center" height=40>'.$usr[6].'</td>';
					echo '<td align="center" height=40>'.$usr[7].'</td>';
					echo '</tr>';
					//$a[2]+=1;
					if($usr[4]!="无"){
						$a[4]+=1;
					}
						
				}
			}
			$a[2]=$bjnum;
			echo '<tr>';
			echo '<td align="center" height=40>'.$a[0].'</td>';
			echo '<td align="center" height=40>'.$a[1].'</td>';
			echo '<td align="center" height=40>'.$a[2].'</td>';
			echo '<td align="center" height=40>'.$a[3].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;" >'.$a[4].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[5].'</td>';
			echo '<td align="center" height=40>'.$a[6].'</td>';
			echo '<td align="center" height=40>'.$a[7].'</td>';
			echo '</tr>';
			echo "</table>";
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	//-------------------------学生只考虑有没有学期，不考虑 属于哪个年级的--不写学生的了----------------------
	//////////////					带年纪、带学期的	  统计结果	
	// 输出整个学校的 各个学院的 统计情况：
	public function njxnxxckbuttonAction(){
		//学年学期
		try{
			$str=$this->request->get("xnq");
			$nj=substr($str,0,8);
			$x=substr($str,8);
			$conn =$this->getDI()->get("db");
			$sql="select  XNQ,XY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from XNQXUEXIAO  where nj='".$nj."' and xy is not null and xnq='" . $x ."' group by xnq,xy order by xy";
			$result = $conn->query($sql);
			echo '<table align="center" width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
			echo '<caption><font size= "5" face="微软雅黑" >'.$nj.' '.$x.'学期全校警示结果</font></caption>';
			echo '<br/>';
			echo '<tr>';
			echo '<th height=40 style="word-wrap:break-word;">学年/学期</th>
				  <th height=40 style="word-wrap:break-word;">学院</th>
				  <th height=40 style="word-wrap:break-word;">I级警示</th>
				  <th height=40 style="word-wrap:break-word;">II级警示</th>
			      <th height=40 style="word-wrap:break-word;">III级警示</th>
		 	      <th height=40 style="word-wrap:break-word;">警示总人数</th>
		 	      <th height=40 style="word-wrap:break-word;">总人数</th>';
			echo '</tr>';
	
			$a=array("总计","-",0,0,0,0,0);
			while($usr=$result->fetch()){
				{
					echo '<tr>';
					echo '<td align="center" height=40>'.$usr[0].'</td>';
					echo '<td align="center" height=40>'.$usr[1].'</td>';
					echo '<td align="center" height=40>'.$usr[2].'</td>';
					echo '<td align="center" height=40>'.$usr[3].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[4].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
					echo '<td align="center" height=40>'.$usr[6].'</td>';
					echo '</tr>';
					$a[1]+=1;$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];
				}
			}
			echo '<tr>';
			echo '<td align="center" height=40><b>'.$a[0].'</b></td>';
			echo '<td align="center" height=40>'.$a[1].'</td>';
			echo '<td align="center" height=40>'.$a[2].'</td>';
			echo '<td align="center" height=40>'.$a[3].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[4].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[5].'</td>';
			echo '<td align="center" height=40>'.$a[6].'</td>';
			echo '</tr>';
			echo "</table>";
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	// 输出某个学院的 各个专业的 统计情况：
	public function njxnxyckbuttonAction(){
		//学年学期
		try{
			$str=$this->request->get("xnq");
			$nj=substr($str,0,8);
			$x=substr($str,8,11);
			$y=substr($str,19);
			//echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期'.$y.'警示结果</font></caption>';
			$conn =$this->getDI()->get("db");
			$sql="select  XNQ,XY,ZY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from XNQXUEYUAN where nj='".$nj."' and xnq='" . $x ."' and xy like '%" . $y ."%' group by XNQ,XY,ZY order by zy";
			//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
			$result = $conn->query($sql);
	
			echo '<table align="center" width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB" >';
			echo '<caption><font size= "5" face="微软雅黑" >'.$nj.' '.$x.' 学期    '.$y.'  警示结果</font></caption>';
			//echo '<caption>'.$x.'</caption>';
			echo '<br/>';
			echo '<tr>';
			echo '<th height=40 style="word-wrap:break-word;">学年/学期</th>
				  <th height=40 style="word-wrap:break-word;">学院</th>
				  <th height=40 style="word-wrap:break-word;">专业</th>
				  <th height=40 style="word-wrap:break-word;">I级警示</th>
				  <th height=40 style="word-wrap:break-word;">II级警示</th>
			      <th height=40 style="word-wrap:break-word;">III级警示</th>
		 	      <th height=40 style="word-wrap:break-word;">警示总人数</th>
		 	      <th height=40 style="word-wrap:break-word;">总人数</th>';
			echo '</tr>';
			$a=array("总计","-",0,0,0,0,0,0);
			while($usr=$result->fetch()){
				{
					echo '<tr>';
					echo '<td align="center" height=40>'.$usr[0].'</td>';
					echo '<td align="center" height=40>'.$usr[1].'</td>';
					echo '<td align="center" height=40>'.$usr[2].'</td>';
					echo '<td align="center" height=40>'.$usr[3].'</td>';
					echo '<td align="center" height=40>'.$usr[4].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[6].'</td>';
					echo '<td align="center" height=40>'.$usr[7].'</td>';
					echo '</tr>';
					$a[2]+=1;$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];$a[7]+=$usr[7];
				}
			}
			echo '<tr>';
			echo '<td align="center" height=40><b>'.$a[0].'</b></td>';
			echo '<td align="center" height=40>'.$a[1].'</td>';
			echo '<td align="center" height=40>'.$a[2].'</td>';
			echo '<td align="center" height=40>'.$a[3].'</td>';
			echo '<td align="center" height=40>'.$a[4].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[5].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[6].'</td>';
			echo '<td align="center" height=40>'.$a[7].'</td>';
			echo '</tr>';
			echo "</table>";
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	
	// 输出某个专业的 各个班级的 统计情况：
	public function njxnzyckbuttonAction(){
		//学年学期
		try{
			$str=$this->request->get("xnq");

			$nj=substr($str,0,8);
			$x=substr($str,8,11);
			$y=substr($str,19);
	
			//echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期'.$y.'警示结果</font></caption>';
			$conn =$this->getDI()->get("db");
			$sql="select  XNQ,ZY,BJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from XNQZHUANYE where nj='".$nj."' and xnq='" . $x ."' and zy = '". $y ."' group by XNQ,ZY,BJ order by bj";
			//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
			$result = $conn->query($sql);
	
			echo '<table align="center" width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
			echo '<caption><font size= "5" face="微软雅黑" >'.$nj.' '.$x.'学期  '.$y.'  专业警示结果</font></caption>';
			//echo '<caption>'.$x.'</caption>';
			echo '<br/>';
			echo '<tr>';
			echo '<th height=40 style="word-wrap:break-word;">学年/学期</th>
				  <th height=40 style="word-wrap:break-word;">专业</th>
				  <th height=40 style="word-wrap:break-word;">班级</th>
				  <th height=40 style="word-wrap:break-word;">I级警示</th>
				  <th height=40 style="word-wrap:break-word;">II级警示</th>
			      <th height=40 style="word-wrap:break-word;">III级警示</th>
		 	      <th height=40 style="word-wrap:break-word;">警示总人数</th>
		 	      <th height=40 style="word-wrap:break-word;">总人数</th>';
			echo '</tr>';
				
			$a=array("总计","-",0,0,0,0,0,0);
			while($usr=$result->fetch()){
				{
					echo '<tr>';
					echo '<td align="center" height=40>'.$usr[0].'</td>';
					echo '<td align="center" height=40>'.$usr[1].'</td>';
					echo '<td align="center" height=40>'.$usr[2].'</td>';
					echo '<td align="center" height=40>'.$usr[3].'</td>';
					echo '<td align="center" height=40>'.$usr[4].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[6].'</td>';
					echo '<td align="center" height=40>'.$usr[7].'</td>';
					echo '</tr>';
					$a[2]+=1;$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];$a[7]+=$usr[7];
				}
			}
			echo '<tr>';
			echo '<td align="center" height=40><b>'.$a[0].'</b></td>';
			echo '<td align="center" height=40>'.$a[1].'</td>';
			echo '<td align="center" height=40>'.$a[2].'</td>';
			echo '<td align="center" height=40>'.$a[3].'</td>';
			echo '<td align="center" height=40>'.$a[4].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[5].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[6].'</td>';
			echo '<td align="center" height=40>'.$a[7].'</td>';
			echo '</tr>';
			echo "</table>";
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
	// 输出某班级里的 所有学生的 统计情况：
	public function njxnbjckbuttonAction(){
		//学年学期
		try{
			$str=$this->request->get("xnq");

			$nj=substr($str,0,4);
			$x=substr($str,8,11);
			$y=substr($str,19);
			//echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期'.$y.'警示结果</font></caption>';
			$conn =$this->getDI()->get("db");
			$sql="select  XNQ,ZY,BJ,XH,XM,JSJB,JSYY,NJ  from MY_XYJS2 where nj='".$nj."' and xnq='" . $x ."' and bj like '%" . $y ."%' order by xh";
			//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
			$result = $conn->query($sql);
			//统计班级人数
			$sql1="select count (distinct xh) from MY_XYJS2 where nj='".$nj."' and xnq='" . $x ."' and bj='" . $y ."'";
			$number = $conn->query($sql1);
			$bjnum=0; //记录总人数
			while($numb=$number->fetch()){
				$bjnum+=$numb[0];
			}
			echo '<table align="center" width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
			echo '<caption><font size= "5" face="微软雅黑" >'.$nj.'级 '.$x.'学期   '.$y.'  班级警示结果（'.$bjnum.' 人）</font></caption>';
			//echo '<caption>'.$x.'</caption>';
			echo '<br/>';
			echo '<tr>';
			echo '<th height=40 style="word-wrap:break-word;">学年/学期</th>
				  <th height=40 style="word-wrap:break-word;">专业</th>
				  <th height=40 style="word-wrap:break-word;">班级</th>
				  <th height=40 style="word-wrap:break-word;">学号</th>
				  <th height=40 style="word-wrap:break-word;">姓名</th>
				  <th height=40 style="word-wrap:break-word;">警示级别</th>
			      <th height=40 style="word-wrap:break-word;">警示原因</th>
				  <th height=40 style="word-wrap:break-word;">年级</th>';
			echo '</tr>';
			$a=array("总计","-","-",0,"-",0,"-","-");
			while($usr=$result->fetch()){
				{
					echo '<tr>';
					echo '<td align="center" height=40>'.$usr[0].'</td>';
					echo '<td align="center" height=40>'.$usr[1].'</td>';
					echo '<td align="center" height=40>'.$usr[2].'</td>';
					echo '<td align="center" height=40>'.$usr[3].'</td>';
					echo '<td align="center" height=40>'.$usr[4].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[5].'</td>';
					echo '<td align="center" height=40 style="word-wrap:break-word;">'.$usr[6].'</td>';
					echo '<td align="center" height=40>'.$usr[7].'</td>';
					echo '</tr>';
	
					if($usr[5]!="无"){
						$a[5]+=1;
					}
				}
	
			}
			$a[3]=$bjnum;
			echo '<tr>';
			echo '<td align="center" height=40>'.$a[0].'</td>';
			echo '<td align="center" height=40>'.$a[1].'</td>';
			echo '<td align="center" height=40>'.$a[2].'</td>';
			echo '<td align="center" height=40>'.$a[3].'</td>';
			echo '<td align="center" height=40>'.$a[4].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[5].'</td>';
			echo '<td align="center" height=40 style="word-wrap:break-word;">'.$a[6].'</td>';
			echo '<td align="center" height=40>'.$a[7].'</td>';
			echo '</tr>';
			echo "</table>";
		}catch(Exception $e) {
			echo '<font color="#4B0082">数据库连接错误！</font>';
		}
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////未选择学期时 ，导出excel的各种情况///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///选择学期   全学校警示结果导出
	public function xxexcelAction()
	{
		//指向 ExcelController中的xxexcelAction() 动作
		$this->dispatcher->forward(array(
				"controller" => "excel",
				//"action" => "index"
				"action" => "xxexcel"
		));  
	}
	 
	//选择学期  学院警示结果导出
	public function xyexcelAction()
	{
		//创建一个新的excel对象,不能有 echo 语句。
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "xyexcel"
		));
	}
	
	//选择学期  专业警示结果导出
	public function zyexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "zyexcel"
		));
	}
	
	//选择学期   班级警示结果导出
	public function bjexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "bjexcel"
		));
	}
	
	//选择学期   学生警示结果导出
	public function xsexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "xsexcel"
		));
	}
	
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////////选择学期之后 ，导出excel的各种情况///////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//选择学期   全学校警示结果导出
	public function xnqxxexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "xnqxxexcel"
		));
	}
	 
	
	//选择学期  学院警示结果导出
	public function xnqxyexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "xnqxyexcel"
		));
	}
	
	//选择学期  专业警示结果导出
	public function xnqzyexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "xnqzyexcel"
		));
	}
	
	//选择学期   班级警示结果导出
	public function xnqbjexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "xnqbjexcel"
		));
	}
	
	//选择学期   学生警示结果导出
	public function xnqxsexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "xnqxsexcel"
		));
	}
//--------------------------------------选择 年级 以后------------------------------------------------
/**
 * excel
 * 
 */
	////////////////////////////////////////未选择学期时 ，导出excel的各种情况///////////////////////////////////////////////////////////////
	///  全学校警示结果导出
	public function njxxexcelAction()
	{
		//指向 ExcelController中的xxexcelAction() 动作
		$this->dispatcher->forward(array(
				"controller" => "excel",
				//"action" => "index"
				"action" => "njxxexcel"
		));
	}
	
	//选择学期  学院警示结果导出
	public function njxyexcelAction()
	{
		//创建一个新的excel对象,不能有 echo 语句。
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "njxyexcel"
		));
	}
	
	//选择学期  专业警示结果导出
	public function njzyexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "njzyexcel"
		));
	}
	
	//选择学期   班级警示结果导出
	public function njbjexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "njbjexcel"
		));
	}
	
	//选择学期   学生警示结果导出
	public function njxsexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "njxsexcel"
		));
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////选择学期之后 ，导出excel的各种情况///////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//选择学期   全学校警示结果导出
	public function njxnqxxexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "njxnqxxexcel"
		));
	}
	
	
	//选择学期  学院警示结果导出
	public function njxnqxyexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "njxnqxyexcel"
		));
	}
	
	//选择学期  专业警示结果导出
	public function njxnqzyexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "njxnqzyexcel"
		));
	}
	
	//选择学期   班级警示结果导出
	public function njxnqbjexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "njxnqbjexcel"
		));
	}
	
	//选择学期   学生警示结果导出
	public function njxnqxsexcelAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "excel",
				"action" => "njxnqxsexcel"
		));
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////图表统计  /////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////未选择学期时  进行图表统计 /////////////////////////////////////////////////////

//未选择学期   全校  柱形图
	public function xxChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "xxChart"
		));
		
	}
	
	//未选择学期   学院  柱形图
	public function xyChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "xyChart"
		));
	}
	
	//未选择学期   专业  柱形图
	public function zyChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "zyChart"
		));
	}

	//未选择学期   班级  柱形图
	public function bjChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "bjChart"
		));
	}
	
//////////////////////////   选择学期时  进行图表统计        ///////////////////////////////////////////////////////
	
	//选择学期时   全校  柱形图
	public function xnqxxChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "xnqxxChart"
		));
	}
	
	//选择学期时   学院  柱形图
	public function xnqxyChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "xnqxyChart"
		));
	}
	
	//未选择学期   专业  柱形图
	public function xnqzyChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "xnqzyChart"
		));
	}
	
	//选择学期时   班级  柱形图
	public function xnqbjChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "xnqbjChart"
		));
	}
//////////选择年级之后---------------------------------------------------------------------------
	//未选择学期   全校  柱形图
	public function njxxChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "njxxChart"
		));
	
	}
	
	//未选择学期   学院  柱形图
	public function njxyChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "njxyChart"
		));
	}
	
	//未选择学期   专业  柱形图
	public function njzyChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "njzyChart"
		));
	}
	
	//未选择学期   班级  柱形图
	public function njbjChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "njbjChart"
		));
	}
	
	//////////////////////////   选择学期时  进行图表统计        ///////////////////////////////////////////////////////
	
	//选择学期时   全校  柱形图
	public function njxnqxxChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "njxnqxxChart"
		));
	}
	
	//选择学期时   学院  柱形图
	public function njxnqxyChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "njxnqxyChart"
		));
	}
	
	//未选择学期   专业  柱形图
	public function njxnqzyChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "njxnqzyChart"
		));
	}
	
	//选择学期时   班级  柱形图
	public function njxnqbjChartAction()
	{
		//创建一个新的excel对象
		$this->dispatcher->forward(array(
				"controller" => "ChartJS",
				"action" => "njxnqbjChart"
		));
	}

}	
/* 从resultalert中删除的分页显示
 * <a href="#" onclick="">首页</a>&nbsp;
<a href="#" onclick="">上一页</a>&nbsp;
<a href="#" onclick="">下一页</a>&nbsp;
<a href="#" onclick="">尾页</a>&nbsp;
 */
?>