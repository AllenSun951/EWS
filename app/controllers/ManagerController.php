<?php
session_start();
use Phalcon\Mvc\Model\Query;
header("Content-type:text/html;charset=utf-8");
use Phalcon\Db\Adapter\Pdo\Oracle as DbAdapter;
class ManagerController extends ControllerBase
{
	public function manageFrameAction(){ // MVC 默认的 界面 初始化 ！！！
		//判断非法登录
		if(!isset($_SESSION['userid'])||$_SESSION['userqx']<>5){
			//header("Location:../login.php");
			echo '<br/>';echo '<br/>';
			echo '未登录！请点击此处 <a href="../login.php" target="_top">登录</a>';
			exit();
		}
		
		try{
		$conn =$this->getDI()->get("db");
		//获取警示管理表的表头信息。
			$tjsql="select JSCOND  from JSCONDITION";
			$tjcond = $conn->query($tjsql);
				
			while($usr=$tjcond->fetch()){
				$tjcont[$i]=$usr[0];
				$i++;
			}
			$this->view->setVar("tjcont",$tjcont);
			// 获取 session 中的用户ID，传给页面
			$this->view->setVar("sessionName",$_SESSION['username']);
		}catch(Exception $e) {
	   		   		echo '<font color="#4B0082">数据库连接错误！</font>';
	   		   	}
		
	}
	///// 页面初次加载时，进行调用！
	public function InitManageFrameAction(){
		try{
		    $conn =$this->getDI()->get("db");
				$tjsql="select JSCOND  from JSCONDITION";
				$tjcond = $conn->query($tjsql);
				$tjcont=array("","","","");
				$i=0;
				while($usr=$tjcond->fetch()){
					$tjcont[$i]=$usr[0];
					$i++;
				}
				//$this->view->setVar("tjcont",$tjcont);
			
				// 填充警示规则管理表格信息。
				$sql="select IJSMIN,IJSMAX,IIJSMIN,IIJSMAX,IIIJSMIN,IIIJSMAX  from JSCONDITION";
				$jscond = $conn->query($sql);
			
				//$this->view->setVar("jscond",$result);
				$cont=array(0,0,0,0,0,0,
						0,0,0,0,0,0,
						0,0,0,0,0,0,
						0,0,0,0,0,0); //数值个数 来自 oracle数据库中的 JSCONDITION 表。
				$i=0;
				while($usr=$jscond->fetch()){
					$cont[$i]+=$usr[0];
					$i++;
					$cont[$i]+=$usr[1];
					$i++;
					$cont[$i]+=$usr[2];
					$i++;
					$cont[$i]+=$usr[3];
					$i++;
					$cont[$i]+=$usr[4];
					$i++;
					$cont[$i]+=$usr[5];
					$i++;
				}
				//$this->request->post($content2);
				//$this->view->setVar("content",$jscond);
				$this->view->setVar("cont",$cont);
		}catch(Exception $e) {
	   		   		echo '<font color="#4B0082">数据库连接错误！</font>';
	   		   	}
	   		 
	   	//echo '<br/><center><font size= "5" face="微软雅黑">警示规则管理</font></center>';
	   	echo '<br/>';
		echo '<table align="center" width="90%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
		echo '<caption><font size= "5" face="微软雅黑">警示规则管理</font></caption>';
		//echo '<caption>'.$x.'</caption>';
		//echo '<br/>';
		echo '<tr>';
		echo '<th height=40>级别<font  size=5>\</font>条件</th>
			  <th height=40>'.$tjcont[0].'</th>
			  <th height=40>' .$tjcont[1]. '</th>
    		  <th height=40>' .$tjcont[2]. '</th>
    		  <th height=40>' .$tjcont[3]. '</th>';
		echo '</tr>';
		echo '<tr>';
		echo '<th height=40> I级警示 </th>
			  <td align="center" height=40>T >= '.$cont[0]. ' </td>
    		  <td align="center" height=40>T >= '.$cont[6].' </td>
    		  <td align="center" height=40>T >= '.$cont[12].' </td>
    		  <td align="center" height=40>T = '.$cont[18].' </td>
			';
		echo '</tr>';
	
		echo '<tr>';
		echo '<th height=40> II级警示 </th>
			  <td align="center" height=40>T = '.$cont[2].' </td>
    		  <td align="center" height=40>'.$cont[8].' <= T < '.$cont[9]. ' </td>
    		  <td align="center" height=40>'.$cont[14].' <= T < '.$cont[15]. ' </td>
    		  <td align="center" height=40>T = '.$cont[20].' </td>
			';
		echo '</tr>';
	
		echo '<tr>';
		echo '<th height=40> III级警示 </th>
    		<td align="center" height=40>T = '.$cont[4].' </td>
    		<td align="center" height=40>'.$cont[10].' <= T < '.$cont[11]. ' </td>
    		<td align="center" height=40>'.$cont[16].' <= T < '.$cont[17]. ' </td>
    		<td align="center" height=40>T = '.$cont[22].'  </td>
			';
		echo '</tr>';
	
		echo "</table>";
		//echo "<input type="." \" "."button "." \" "."style="." \" "."width:85px;height:30px;font-size:16px"." \" "." value="." \" "."提交修改"." \" "." name="." \" "."sumbit"." \" ". " id="." \" "."sumbit"." \" "." onclick="." \" "."submitBtnClick()"." \" "." >";
		echo '<br/>';
		//echo '<input type="button" style="width:50px;height:30px;margin-left:880px;margin-right:20px;font-size:16px;" value="修改" onclick="ModifyBtnClick()" >';
		//echo '<div>';
		echo '<div style="float:right;">
    			<input type="button" id ="executeBtn" name="executeBtn" style="border-style:solid;border-width:1px;border-color:#388BFF;width:50px;height:30px;font-size:16px;margin-right:120px;" value="执行" onclick="ExecuteBtnClick()" > 
		      </div>
    		  <div style="float:right;">
    			<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;width:50px;height:30px;margin-left:10px;margin-right:50px;font-size:16px;" value="修改" onclick="ModifyBtnClick()" > 
			  </div>';
		
}
	
	//警示规则管理   修改
	public function modifyAction(){
	try{
		    $conn =$this->getDI()->get("db");
			$tjsql="select JSCOND  from JSCONDITION";
			$tjcond = $conn->query($tjsql);
			$tjcont=array("","","","");
			$i=0;
		
			//表头条件信息
			while($usr=$tjcond->fetch()){
				$tjcont[$i]=$usr[0];
				$i++;
			}
		
			//警示规则设定的数值
			$sql="select IJSMIN,IJSMAX,IIJSMIN,IIJSMAX,IIIJSMIN,IIIJSMAX  from JSCONDITION";
			$jscond = $conn->query($sql);
		    
			//$this->view->setVar("jscond",$result);
			$cont=array(0,0,0,0,0,0,
					0,0,0,0,0,0,
					0,0,0,0,0,0,
					0,0,0,0,0,0); //数值个数 来自 oracle数据库中的 JSCONDITION 表。
			$i=0;
			while($usr=$jscond->fetch()){
				$cont[$i]+=$usr[0];
				$i++;
				$cont[$i]+=$usr[1];
				$i++;
				$cont[$i]+=$usr[2];
				$i++;
				$cont[$i]+=$usr[3];
				$i++;
				$cont[$i]+=$usr[4];
				$i++;
				$cont[$i]+=$usr[5];
				$i++;
			}
		}catch(Exception $e) {
	   		   		echo '<font color="#4B0082">数据库连接错误！</font>';
	   		   	}
		echo '<div class="table-a">';
		echo '<link rel="stylesheet" href="../../public/css/input.css" type="text/css"/>';
		echo '<br/>';
		echo '<table align="center" width="90%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
		echo '<caption><font size= "5" face="微软雅黑">警示规则管理</font></caption>';
		//echo '<caption>'.$x.'</caption>';
		//echo '<br/>'; // 此处影响 按下修改按键 后 表格位置会变动！ 
		echo '<tr>';
		echo '<th height=40>级别<font  size=5>\</font>条件</th>
			  <th height=40>'.$tjcont[0].'</th>
			  <th height=40>' .$tjcont[1]. '</th>
    		  <th height=40>' .$tjcont[2]. '</th>
    		  <th height=40>' .$tjcont[3]. '</th>';
		echo '</tr>';
		echo '<tr>';
		
		//设置input 边框颜色
		/* echo '<style>
		input
		{	
			border-width:1px;
			border-color:#388BFF;
		}
		</style>'; */
		echo '<th height=40> I级警示 </th>
			  <td align="center" height=40>T >= <input type="text" name="I1" id="I1" style= "text-align:center;"  size="2" value='.$cont[0]. ' />  </td>
    		  <td align="center" height=40>T >= <input type="text" name="I2" id="I2" style= "text-align:center;" size="2" value='.$cont[6].' />  </td>
    		  <td align="center" height=40>T >= <input type="text" name="I3" id="I3" style= "text-align:center;" size="2" value='.$cont[12].' />  </td>
    		  <td align="center" height=40>T = <input type="text" name="I4" id="I4" style= "text-align:center;" size="2" value='.$cont[18].' />  </td>
			';
		echo '</tr>';
	
		echo '<tr>';
		echo '<th height=40> II级警示 </th>
			  <td align="center" height=40>T = <input type="text" name="II1" id="II1" style= "text-align:center;" size="2" value='.$cont[2].' />  </td>
    		  <td align="center" height=40><input type="text" name="II21" id="II21" style= "text-align:center;" size="2" value='.$cont[8].' /> <= T < <input type="text" name="II22" id="II22" style= "text-align:center;" size="2" value='.$cont[9]. ' />  </td>
    		  <td align="center" height=40><input type="text" name="II31" id="II31" style= "text-align:center;" size="2" value='.$cont[14].' /> <= T < <input type="text" name="II32" id="II32" style= "text-align:center;" size="2" value='.$cont[15]. ' />  </td>
    		  <td align="center" height=40>T = <input type="text" name="II4" id="II4" style= "text-align:center;" size="2" value='.$cont[20].' />  </td>
			';
		echo '</tr>';
	
		echo '<tr>';
		echo '<th height=40> III级警示 </th>
    		<td align="center" height=40>T = <input type="text" name="III1" id="III1" style= "text-align:center;" size="2" value='.$cont[4].' />  </td>
    		<td align="center" height=40><input type="text" name="III21" id="III21" style= "text-align:center;" size="2" value='.$cont[10].' /> <= T < <input type="text" name="III22" id="III22" style= "text-align:center;" size="2" value='.$cont[11]. ' />  </td>
    		<td align="center" height=40><input type="text" name="III31" id="III31" style= "text-align:center;" size="2" value='.$cont[16].' /> <= T < <input type="text" name="III32" id="III32" style= "text-align:center;" size="2" value='.$cont[17]. ' />  </td>
    		<td align="center" height=40>T = '.$cont[22].'   </td>
			';
		echo '</tr>';
	
		echo "</table>";
		echo '<div>';
		//echo "<input type="." \" "."button "." \" "."style="." \" "."width:85px;height:30px;font-size:16px"." \" "." value="." \" "."提交修改"." \" "." name="." \" "."sumbit"." \" ". " id="." \" "."sumbit"." \" "." onclick="." \" "."submitBtnClick()"." \" "." >";
		echo '<script type="text/javascript">
				$(document).ready(function(){ 
				$("input").bind("keyup",function(){ 
					if (event.keyCode == 13) { 
						submitBtnClick();
	 					}	
						}); 
						});
				</script>
				<br/>';
		echo '<div style="float:right;">
    			<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;width:50px;height:30px;font-size:16px;margin-right:120px;" value="返回" onclick="rebackBtnClick()" > 
		      </div>
    		  <div style="float:right;">
    			<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;width:50px;height:30px;margin-left:10px;margin-right:50px;font-size:16px;" value="提交" onclick="submitBtnClick()" > 
			  </div>';
	}
	// 执行  Oracle 中警示 存储过程，操作
	public function executeAction(){ 
		try{
		    $conn =$this->getDI()->get("db");
			$sql="select EXECCJBTime,EXECTime  from execPwd";  //  密码就默认吧。保存在独一张execPwd表中
			$execpwd = $conn->query($sql);
			while($usr=$execpwd->fetch()){
				$mExecTime=$usr[1];
				$mExecCJBTime=$usr[0];
			}
		}catch(Exception $e) {
	   		   		echo '<font color="#4B0082">数据库连接错误！</font>';
	   		   	}
		echo '<br/>';
		echo '<br/>';
		echo '<div id="pwddiv" style= "text-align:center;">';
		echo '<font FONT-FAMILY = "宋体" color="#4B0082">请输入执行密码：</font> <input type="password" name="mpwd" id="mpwd" style= "text-align:center;border:1px solid #4169E1;" size="16" value="" />
			  </div>';
		echo '<div id="CJBpsdiv" style= "text-align:center;display:none;" >';
		echo '<font FONT-FAMILY = "宋体" color="red">后台正对“成绩表、学生基本信息表”等进行更新运算，请勿进行页面刷新，一般耗时450 秒左右！</font>
			  </div>';
		echo '<div id="JSpsdiv" style= "text-align:center;display:none;" >';
		echo '<font FONT-FAMILY = "宋体" color="red">后台运算中，请勿进行页面刷新，等待执行成功的提示，一般耗时 100 秒左右！</font>
			  </div>';
		echo '<div>';
		//echo "<input type="." \" "."button "." \" "."style="." \" "."width:85px;height:30px;font-size:16px"." \" "." value="." \" "."提交修改"." \" "." name="." \" "."sumbit"." \" ". " id="." \" "."sumbit"." \" "." onclick="." \" "."submitBtnClick()"." \" "." >";
		echo '<br/>';
				echo '<script type="text/javascript">
				$(document).ready(function(){ 
				$("input").bind("keyup",function(){ 
					if (event.keyCode == 13) { 
						confirmBtnJSClick();
	 					}	
						}); 
						});
				</script>
				<br/>';
		echo '<div style= "text-align:center;">
    			<input type="button" name="confirmBtn" id="confirmBtnCJB" style="border-style:solid;border-width:1px;border-color:#388BFF;width:80px;height:30px;font-size:16px;" value="成绩更新" onclick="confirmBtnCJBClick()" >
    			<input type="button" name="confirmBtn" id="confirmBtnJS" style="border-style:solid;border-width:1px;border-color:#388BFF;width:80px;height:30px;margin-left:20px;font-size:16px;" value="警示计算" onclick="confirmBtnJSClick()" >
				<input type="button" name="rebackBtn" id="rebackBtn" style="border-style:solid;border-width:1px;border-color:#388BFF;width:50px;height:30px;margin-left:20px;font-size:16px;" value="返回" onclick="rebackBtnClick()" >
			    <input type="button" name="changePwd" id="changePwd" style="border-style:solid;border-width:1px;border-color:#388BFF;width:120px;height:30px;margin-left:30px;font-size:16px;" value="修改执行密码" onclick="changePwdBtnClick()" >
			  </div>
			  </div>';
		echo '<br/> <br/> <br/>
			 <div id="TimeCJBpsdiv" style= "text-align:center;">';
		echo '<font FONT-FAMILY = "宋体" color="#4B0082">成绩最近更新时间： '.$mExecCJBTime.'</font>
			  </div>';
		echo '<br/>
			  <div id="Timepsdiv" style= "text-align:center;" >';
		echo '<font FONT-FAMILY = "宋体" color="#4B0082">警示最近计算时间： '.$mExecTime.'</font>
			  </div>';
	    }
	    //修改执行密码界面
	    public function changePwdAction(){
	    	echo '<br/>';
	    	echo '<br/>';
	    	echo '<div style= "text-align:center;">';
	    	echo '<font FONT-FAMILY = "宋体" color="#4B0082">请输入原密码：</font> <input type="password" name="mchangePwd" id="mchangePwd" style= "text-align:center;border:1px solid #4169E1;" size="16" value="" />
			  </div>';
	    	echo '<br/>';
	    	echo '<div style= "text-align:center;">';
	    	echo '<font FONT-FAMILY = "宋体" color="#4B0082">请输入新密码：</font> <input type="password" name="mchangePwd1" id="mchangePwd1" style= "text-align:center;border:1px solid #4169E1;" size="16" value="" />
			  </div>';
	    	echo '<br/>';
	    	echo '<div style= "text-align:center;">';
	    	echo '<font FONT-FAMILY = "宋体" color="#4B0082">再确认新密码：</font> <input type="password" name="mchangePwd2" id="mchangePwd2" style= "text-align:center;border:1px solid #4169E1;" size="16" value="" />
			  </div>';
	    	echo '<div>';
	    	//echo "<input type="." \" "."button "." \" "."style="." \" "."width:85px;height:30px;font-size:16px"." \" "." value="." \" "."提交修改"." \" "." name="." \" "."sumbit"." \" ". " id="." \" "."sumbit"." \" "." onclick="." \" "."submitBtnClick()"." \" "." >";
	    	echo '<br/>';
	    			echo '<br/>';
			echo '<script type="text/javascript">
				$(document).ready(function(){ 
				$("input").bind("keyup",function(){ 
					if (event.keyCode == 13) { 
						confirmPwdBtnClick();
	 					}	
						}); 
						});
				</script>
				<br/>';
	    	echo '<div style= "text-align:center;">
    			<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;width:50px;height:30px;font-size:16px;" value="确认" onclick="confirmPwdBtnClick()" >
    			<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;width:50px;height:30px;margin-left:30px;font-size:16px;" value="取消" onclick="ExecuteBtnClick()" >
			  </div>
			  </div>';
	    }
	    //提交修改后的执行密码，并进行验证。
	    public function confirmPwdAction(){  
	     	$mchangePwd=$this->request->get("mchangePwd");
	     	try{
		     	$conn =$this->getDI()->get("db");
		     	//echo $mchangePwd;
		     	$array=Array("","","");
		     	$array=explode(",",$mchangePwd);
		     	//echo '<?php var_dump('.$array.');?-->';
	     		$sql="select mm  from execPwd";  //  密码就默认吧。保存在独一张execPwd表中
	     		$execpwd = $conn->query($sql);
	     		while($usr=$execpwd->fetch()){
	     			$mexecpwd=$usr[0];
	     		}
	     		if($mexecpwd==$array[0]){
	     			$sql1="update execPwd  set mm ='".$array[1]."'";  //  密码就默认吧。保存在独一张execPwd表中
	     			$execpwd1 = $conn->execute($sql1);
	     			echo '<br/>';
	     			echo '<br/>';
	     			echo '<font FONT-FAMILY = "宋体" color="#4B0082">执行密码修改成功，请返回 ！！！！！ </font>';
	     			echo '<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;width:80px;height:30px;font-size:16px;" value="返回" name="sumbit" id="sumbit" onclick="ExecuteBtnClick()" >';
	     		}else{
	     			echo '<br/>';
	     			echo '<br/>';
	     			echo '<font FONT-FAMILY = "宋体" color="#4B0082">原始密码输入有误，请返回重新输入！！！！！</font>';
	     			echo '<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;width:80px;height:30px;font-size:16px;" value="返回输入" name="sumbit" id="sumbit" onclick="changePwdBtnClick()" >';
	     		}
	     	}catch(Exception $e) {
	   		   		echo '<font color="#4B0082">数据库连接错误！</font>';
	   		   	}
	     }
	     // 输入执行密码以后，确认执行，更新成绩表。数据准备工作。。。
	   public function confirmCJBAction(){  
	   	$mconfirmPwd=$this->request->get("mconfirm");
	   	try{
	   		$conn =$this->getDI()->get("db");
		   	$sql="select mm  from execPwd";  //  密码就默认吧。保存在独一张execPwd表中 
		   	$execpwd = $conn->query($sql);
		   	while($usr=$execpwd->fetch()){
		   		$mexecpwd=$usr[0];
		   	}
		   	//判断输入的执行密码是否正确
		   	if ($mconfirmPwd==$mexecpwd){
		   		$t1 = microtime(true);
		   		//$conn =$this->getDI()->get("db");
		   		// 一步一步调用存储过程
		   	 	//首先：清空所有表格数据，优先清理 学期表，这是网站判断数据库内部是否运行警示算法的依据。
		   	
			   		$sql_sp1 = "BEGIN my_truncateCJB; END;";
			   		$jscond1 = $conn->execute($sql_sp1);
			   		//从教务处远程数据库导入成绩表。
			   		$sql_sp2 = "BEGIN BasicTablePreCJB; END;";
			   		$jscond2 = $conn->execute($sql_sp2);
			   		//从教务处远程数据库导入学生基本信息表。
			   		$sql_sp3 = "BEGIN BasicTablePreXSJBXXB; END;";
			   		$jscond3 = $conn->execute($sql_sp3);
			   		//针对成绩数据表中的"中文等级"数据进行清理
			   		$sql_sp4 = "BEGIN UPDATECJBHZ; END;";
			   		$jscond4 = $conn->execute($sql_sp4);
			   		//创建所需视图
			   		$sql_sp5 = "BEGIN my_createview; my_CreateXNQView; END;";
			   		$jscond5 = $conn->execute($sql_sp5);
			   		//根据视图填充 警示统计表 MY_XYJS（总学期） 和MY_XYJS2（每学期）
			   		$sql_sp6 = "BEGIN my_cjb2; END;"; //MY_XYJS（总学期）
			   		$jscond6 = $conn->execute($sql_sp6);
			   		$sql_sp7 = "BEGIN my_cjb1; JSXNQ; END;";//MY_XYJS1（上学期）
			   		$jscond7 = $conn->execute($sql_sp7);
			   		$sql_sp8 = "BEGIN my_XNQ_CJB; END;"; //MY_XYJS2（每学期）
			   		$jscond8 = $conn->execute($sql_sp8);

		   		echo '<br/>';
		   		echo '<br/>';
		   		echo ("成绩等信息统计表已更新完成，请继续执行警示规则的计算！！！ " );
		   		echo '<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;width:80px;height:30px;font-size:16px;" value="返回执行" name="sumbit" id="sumbit" onclick="ExecuteBtnClick()" >';   	
		   		$t2 = microtime(true);
		   		echo '<br/>';
		   		echo '<br/>';
		   		echo '耗时: '.round($t2-$t1,3).' 秒'; 
		   		//更新执行时间。
		   		//$time=date("Y-m-d h:i:s");
		   		//$sql_sp9 = "update execPwd set EXECCJBTime=to_char(sysdate,'yyyy-mm-dd hh24:mi:ss');";
		   		$sql_sp9 = "update execPwd set EXECCJBTime=to_char(sysdate,'yyyy-mm-dd hh24:mi:ss')";
		   		$jscond9 = $conn->execute($sql_sp9);
	   			}	   		   
	   		else 
	   		{
	   		echo '<br/>';
	   		echo '<br/>';
	   		echo ("执行密码错误，请返回重新输入  ！！！！！ " );
	   		echo '<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;width:80px;height:30px;font-size:16px;" value="返回输入" name="sumbit" id="sumbit" onclick="ExecuteBtnClick()" >';
	   	    }
	   	}
	   	catch(Exception $e) {
	   		echo '<font color="#4B0082">数据库连接错误！</font>';
	   	}
  }
	   
 // 输入执行密码以后，确认执行，运行警示规则，计算警示级别。
	   public function confirmJSAction(){  
	   	$mconfirmPwd=$this->request->get("mconfirm");
	   	try{
	   		$conn =$this->getDI()->get("db");
		   	$sql="select mm  from execPwd";  //  密码就默认吧。保存在独一张execPwd表中 
		   	$execpwd = $conn->query($sql);
		   	while($usr=$execpwd->fetch()){
		   		$mexecpwd=$usr[0];
		   	}
		   	//判断输入的执行密码是否正确
		   	if ($mconfirmPwd==$mexecpwd){
		   		$t1 = microtime(true);
		   		//$conn =$this->getDI()->get("db");
		   		// 一步一步调用存储过程
		   	 	//首先：清空所有表格数据，优先清理 学期表，这是网站判断数据库内部是否运行警示算法的依据。

	   			$sql_sp1 = "BEGIN my_truncateJS; END;";
		   		$jscond1 = $conn->execute($sql_sp1); 
     	   		//警示算法
		   		$sql_sp9 = "BEGIN my_js; END;";
		   		$jscond9 = $conn->execute($sql_sp9);
		   		$sql_sp10 = "BEGIN my_XNQ_js; END;";
		   		$jscond10 = $conn->execute($sql_sp10);
		   		//填充网站用的统计表，不带学期
		   		$sql_sp11 = "BEGIN XUEXIAOTABLE; XUEYUANTABLE; ZHUANYETABLE; END;";
		   		$jscond11 = $conn->execute($sql_sp11);
		   		//填充网站用的统计表，带学期 学校统计表
		   		$sql_sp12 = "BEGIN XNQXUEXIAOTABLE; END;";
		   		$jscond12 = $conn->execute($sql_sp12);
		   		//填充网站用的统计表，带学期各学院统计表
		   		$sql_sp13 = "BEGIN XNQXUEYUANTABLE; END;";
		   		$jscond13 = $conn->execute($sql_sp13);
		   		//填充网站用的统计表，带学期 各专业统计表
		   		$sql_sp14 = "BEGIN XNQZHUANYETABLE; END;";
		   		$jscond14 = $conn->execute($sql_sp14);
		   		//最后填充本网站用到的学院、专业、班级表以及最后的 “学期表”
		   		$sql_sp15 = "BEGIN JSqueryview; JSXNQ; END;";
		   		$jscond15 = $conn->execute($sql_sp15);
	   		echo '<br/>';
	   		echo '<br/>';
	   		echo ("警示信息计算已结束，请返回查看最新数据！！！ " );
	   		echo '<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;width:80px;height:30px;font-size:16px;" value="返回查看" name="sumbit" id="sumbit" onclick="rebackBtnClick()" >';
	 
	   		$t2 = microtime(true);
	   		echo '<br/>';
	   		echo '<br/>';
	   		echo '耗时: '.round($t2-$t1,3).' 秒'; 
	   		// 更新执行时间：
	   		$sql_sp2 = "update execPwd set EXECTime=to_char(sysdate, 'yyyy-mm-dd hh24:mi:ss')";
	   		$jscond2 = $conn->execute($sql_sp2);
	   		}
	   	else 
	   	{
	   		echo '<br/>';
	   		echo '<br/>';
	   		echo ("执行密码错误，请返回重新输入  ！！！！！ " );
	   		echo '<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;width:80px;height:30px;font-size:16px;" value="返回输入" name="sumbit" id="sumbit" onclick="ExecuteBtnClick()" >';
	   	}
	  }catch(Exception $e) {
	   		echo '<font color="#4B0082">数据库连接错误！</font>';
	   }
}
	// 提交修改信息。
	public function submitBtnAction(){
		$submitSum=$this->request->get("sum");
		try{
			$conn =$this->getDI()->get("db");
		// 针对传递过来的参数进行截取，以 逗号为分节符。
		//按照数据库中要求，规定循环次数为 i
			$sql="select IJSMIN from JSCONDITION";
			$jscond = $conn->query($sql);
			$i=0;
			while($usr=$jscond->fetch()){
				$i++;
			}
			//// 针对 submitSum 参数进行截取，以 逗号为分节符。
			//echo $i;
			//echo $submitSum;
			$arr = explode(",",$submitSum); // arr 个数 24
			//echo $arr[0];
			// 更新数据库，
			for ($j=0;$j<$i;$j++)
			{//$submitsql="insert into JSCONDITION(IJSMIN,IJSMAX,IIJSMIN,IIJSMAX,IIIJSMIN,IIIJSMAX) values(".$arr[j*6].",".$arr[j*6+1].",".$arr[j*6+2].",".$arr[j*6+3].",".$arr[j*6+4].",".$arr[j*6+5].")";
				$row =$j+1; // where 条件使用
				$submitsql="update JSCONDITION set IJSMIN=".$arr[$j*6]."
				,IJSMAX=".$arr[$j*6+1].",IIJSMIN=".$arr[$j*6+2].",IIJSMAX=".$arr[$j*6+3]."
				,IIIJSMIN=".$arr[$j*6+4].",IIIJSMAX=".$arr[$j*6+5]." where ID=".$row;
				 	$submitcond = $conn->execute($submitsql);
			}
		}catch(Exception $e) {
	   		echo '<font color="#4B0082">数据库连接错误！</font>';
	   }
		//在 divtable 中重新返回数值，覆盖掉 各个控件。并给出操作结束提示符！！
		echo '<br/>';
		echo '<br/>';
		echo ("提交成功，请返回查看  ！        " );
		echo '<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;width:80px;height:30px;font-size:16px;" value="返回查看" name="sumbit" id="sumbit" onclick="rebackBtnClick()" >';
		
	}
	
}

