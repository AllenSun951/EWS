<?php
session_start();
use Phalcon\Mvc\Model\Query;
header("Content-type:text/html;charset=utf-8");
use Phalcon\Db\Adapter\Pdo\Oracle as DbAdapter;
class PersonalController extends ControllerBase
{
	public function PersonalInfoAction(){ // MVC 默认的 界面 初始化 ，根据 session中的数据，对个人信息初始化，但是 用不到了
		if(!isset($_SESSION['userid'])){ // 公用的，不要判断权限 问题
			//header("Location:../login.php");
			echo '<br/>';echo '<br/>';
			echo '未登录！请点击此处 <a href="../login.php" target="_top">登录</a>';
			exit();
		}
	}
	///// 根据 session中的数据，对个人信息初始化，假设当前用户名为 李四
	public function InitPersonalInfoAction(){
		try{
			$sessionNameID=$_SESSION['userid']; // 学号
			$conn =$this->getDI()->get("db");
			$sql1="select count(*)  from XQView";
			$result1 = $conn->query($sql1);
			$num=0;
			while($usr=$result1->fetch()){
				$num=$usr[0];
			}
			if($num==0){
				echo '<br/>';
				echo '<br/>';
				echo '后台数据正在更新，请耐心等待....................';
			}
			else  // 只有 数据库中数据存在（执行真正结束后）才运行以下
			{
				echo ' <style>
							input
							{	border:0 solid #4169E1;
							    FONT-FAMILY: "宋体","Verdana";
								width:70px;
								height:30px;
								font-size:14px;
								COLOR: #4B0082;
								BACKGROUND-COLOR: transparent;
							}
							</style>
	   					<div id="top2" style="width:100%;height:6%;">
	   						<div id="top21" style="float:left;width:60%;height:30px;">
	   						<!--span style="text-align:left;FONT-SIZE:17px;"><font color="#4B0082">&nbsp;&nbsp;&nbsp;&nbsp;当前用户名:  '.$_SESSION['username'].'</font></span>
	   						</span-->
	  	   					</div>
	   	   					<div id="top22" style="float:right;width:15%;height:30px;">
	   						<span style="text-align: right;">
	   						<input type="button" name="changePwd" id="changePwd"  value="修改密码" onclick="changePwdBtnClick()" >
	   						<!--input type="button" name="rebackBtn" id="rebackBtn"  value="注销" onclick="logoutBtnClick()" -->
	   						<!--a href="Personal/PersonalInfo"  target="mainFrame">注销</a-->	
	   						</span>
	   	   					</div>
	   					</div>';
			//$tjsql="select XM,QX,FW  from users";
		    //从学生基本信息表中将学生基本信息全部选择出来。
			// mm 面貌=头像 如何显示？
			//学号，姓名，性别，出生日期，政治面貌，民族，籍贯，学院，专业，班级，学制，学籍状态，当前所在年级，方向，入学日期，邮箱地址，身份证号，备注，专业代码，层次，是否在校，考生类别，来源省份，联系电话，可申请毕业。
			//$tjsql="select xh,xm,xb,csrq,zzmm,mz,jg,xy,zymc,xzb,xz,xjzt,dqszj,pyfx,rxrq,dzyxdz,sfzh,bz,zydm,cc,sfzx,kslb,lys,telnumber,byf from xsjbxxb";
			//调一下显示顺序: 4行X6列表格显示。
			//学号，姓名，性别，民族，出生日期，政治面貌，
			//来源省份，籍贯，身份证号，联系电话，邮箱地址，考生类别，
			//学院，专业，班级，学制，入学日期，当前所在年级，
			//方向，学籍状态，备注，层次，是否在校，可申请毕业。
			$tjsql="select xh,xm,xb,mz,csrq,zzmm,lys,jg,sfzh,telnumber,dzyxdz,kslb,xy,zymc,xzb,xz,rxrq,dqszj,pyfx,xjzt,bz,cc,sfzx,byf from xsjbxxb where xh='".$sessionNameID."'";
			$tjcon = $conn->query($tjsql);
			//$tjcont=array("","","","");
			// 填充个人表格信息。
			//$this->view->setVar("cont",$tjcont);  //将数据库中获取的信息传入个人空间表中。
			echo '<table align="center" width="90%" border="1" cellpadding="0" cellspacing="0" bordercolor="#6699BB">';
			echo '<caption><font size= "5" face="微软雅黑">个人基本信息</font></caption>';
			while($usr=$tjcon->fetch()){ //姓名出问题？
			echo '<tr>';//学号，姓名，性别，民族，出生日期，政治面貌，
			echo '<th align="center" height=40>学号</th>
				  <th align="center" height=40>姓名</th>
				  <th align="center" height=40>性别</th>
	    		  <th align="center" height=40>民族</th>
				  <th align="center" height=40>出生日期</th>
	    		  <th align="center" height=40>政治面貌</th>';
			echo '</tr>';
			//echo '<tr>';//学号，姓名，性别，民族，出生日期，政治面貌，
			echo '<td align="center" height=40>'.$usr[0].'</td>
				  <td align="center" height=40>'.$usr[1].'</td>
				  <td align="center" height=40>'.$usr[2].'</td>
	    		  <td align="center" height=40>'.$usr[3].'</td>
				  <td align="center" height=40>'.$usr[4].'</td>
	    		  <td align="center" height=40>'.$usr[5].'</td>';
			echo '</tr>';
			//来源省份，籍贯，身份证号，联系电话，邮箱地址，考生类别，
			echo '<th align="center" height=40>来源省份</th>
				  <th align="center" height=40>籍贯</th>
				  <th align="center" height=40>身份证号</th>
	    		  <th align="center" height=40>联系电话</th>
				  <th align="center" height=40>邮箱地址</th>
	    		  <th align="center" height=40>考生类别</th>';
			echo '</tr>';
			echo '<td align="center" height=40>'.$usr[6].'</td>
				  <td align="center" height=40>'.$usr[7].'</td>
				  <td align="center" height=40>'.$usr[8].'</td>
	    		  <td align="center" height=40>'.$usr[9].'</td>
				  <td align="center" height=40>'.$usr[10].'</td>
	    		  <td align="center" height=40>'.$usr[11].'</td>';
			echo '</tr>';
			//学院，专业，班级，学制，入学日期，当前所在年级，
			echo '<th align="center" height=40>学院</th>
				  <th align="center" height=40>专业</th>
				  <th align="center" height=40>班级</th>
	    		  <th align="center" height=40>学制</th>
				  <th align="center" height=40>入学日期</th>
	    		  <th align="center" height=40>当前所在年级</th>';
			echo '</tr>';
			echo '<td align="center" height=40>'.$usr[12].'</td>
				  <td align="center" height=40>'.$usr[13].'</td>
				  <td align="center" height=40>'.$usr[14].'</td>
	    		  <td align="center" height=40>'.$usr[15].'</td>
				  <td align="center" height=40>'.$usr[16].'</td>
	    		  <td align="center" height=40>'.$usr[17].'</td>';
			echo '</tr>';
			//方向，学籍状态，备注，层次，是否在校，可申请毕业。
			echo '<th align="center" height=40>方向</th>
				  <th align="center" height=40>学籍状态</th>
				  <th align="center" height=40>备注</th>
	    		  <th align="center" height=40>层次</th>
				  <th align="center" height=40>是否在校</th>
	    		  <th align="center" height=40>可申请毕业</th>';
			echo '</tr>';
			echo '<td align="center" height=40>'.$usr[18].'</td>
				  <td align="center" height=40>'.$usr[19].'</td>
				  <td align="center" height=40>'.$usr[20].'</td>
	    		  <td align="center" height=40>'.$usr[21].'</td>
				  <td align="center" height=40>'.$usr[22].'</td>
	    		  <td align="center" height=40>'.$usr[23].'</td>';
			echo '</tr>';
			}
			echo "</table>";
			//echo "<input type="." \" "."button "." \" "."style="." \" "."width:85px;height:30px;font-size:16px"." \" "." value="." \" "."提交修改"." \" "." name="." \" "."sumbit"." \" ". " id="." \" "."sumbit"." \" "." onclick="." \" "."submitBtnClick()"." \" "." >";
			}
			}catch(Exception $e) {
				echo '<font color="#4B0082">数据库连接错误！</font>';
			}
	}
	    //修改个人密码界面
	public function changePwdAction(){
	    	echo '<br/>
	    		  <br/>';
	    	echo '<div style= "text-align:center;">
	    	 	  <font FONT-FAMILY = "宋体" color="#4B0082">修改密码</font>
	    		  </div>';
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
    			<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;width:50px;height:30px;margin-left:30px;font-size:16px;" value="取消" onclick="rebackBtnClick()" >
			  </div>
			  </div>';
	    }
	    //提交修改后的个人密码，并进行验证。改完之后相当于注销按钮。
	     public function confirmPwdAction(){ 
	     	try{
		     	$sessionNameID=$_SESSION['userid']; // 学号
		     	$mchangePwd=$this->request->get("mchangePwd");
		     	$conn =$this->getDI()->get("db");
		     	//echo $mchangePwd;
		     	$array=Array("","","");
		     	$array=explode(",",$mchangePwd);
		     	//echo '<?php var_dump('.$array.');?-->';
		     		$sql="select mm  from users where ID='".$sessionNameID."'";
		     		$execpwd = $conn->query($sql);
		     		$mexecpwd="";
		     		while($usr=$execpwd->fetch()){
		     			$mexecpwd=$usr[0];
		     		}
		     		// 如果数据为空，此处会出现错误，所以要对 $mexecpwd 初始。
		     		if($mexecpwd==$array[0]){
		     			$sql1="update users set mm ='".$array[1]."' where ID='".$sessionNameID."'";  //  密码就默认吧。保存在独一张execPwd表中
		     			$execpwd1 = $conn->execute($sql1);
		     			echo '<br/>';
		     			echo '<br/>';
		     			echo '<font FONT-FAMILY = "宋体" color="#4B0082">执行密码修改成功，请返回 ！！！！！ </font>';
		     			echo '<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;width:80px;height:30px;font-size:16px;" value="返回" name="sumbit" id="sumbit" onclick="rebackBtnClick()" >';
		     		}else{
		     			echo '<br/>';
		     			echo '<br/>';
		     			echo '<font FONT-FAMILY = "宋体" color="#4B0082">原始密码校验错误，请返回重新输入！！！！！</font>';
		     			echo '<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;width:80px;height:30px;font-size:16px;" value="返回输入" name="sumbit" id="sumbit" onclick="changePwdBtnClick()" >';
		     		}
		     		}catch(Exception $e) {
		     			echo '<font color="#4B0082">数据库连接错误！</font>';
		     		}
	     }
	
}

