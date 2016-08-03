<?php
session_start();
//在页首先要开启session,
//error_reporting(2047);
//session_destroy(); //不能毁掉，不然其他页面无法用。。。
//将session去掉，以每次都能取新的session值;
//用seesion 效果不错，也很方便
  class MloginController extends ControllerBase
  {  
     
  	public function registerAction(){
  	
  	}
  	public function mloginAction(){
     	//alert("aaa");
      // 检查请求是否为POST
  		try{
	        if ($this->request->isPost() == true) {
	        		//如果验证码错误，直接跳到登录页面！！！
	        	$mcodeNum = $_SESSION["ValidateCode"];
	        	$mValidateCode=trim($this->request->get("ValidateCode"));
	        	//echo "$mcodeNum";
	        	if ($mcodeNum == $mValidateCode) //检测验证码正确否？
	        		{
	    			$muid=trim($this->request->get("uid"));
	    			$mpwd=trim($this->request->get("pwd"));
	    			$mLoginType=trim($this->request->get("LoginType"));
	    	
			    	$conn =$this->getDI()->get("db");
			    	$sql="select ID,XM,QX,FW,MM from USERS";
			    	//users 中的数据为 ID，XM，MM，QX，FW。账号名，姓名，密码，权限（职位），权限范围
			    	$result = $conn->query($sql);
			    	//usr 中的数据为 ID，XM，QX，FW，MM == usr[0],usr[1],usr[2],usr[3],usr[4]；
					//$this->response->redirect("idx.php");
					//总共有6个权限：0：学生:1：班主任，2：辅导员，3：学院管理人员，4：学校管理人员，5系统维护人员（业务管理人员）
			    	
				    	while($usr=$result->fetch()){
				    				
				    	 if(trim($muid==$usr[0]) && trim($mpwd==$usr[4]) &&trim($mLoginType==$usr[2]))
				    	 //if($muid==$usr[0] && $mpwd==$usr[4])
				    	   { 	//登录成功！
				    	   		$_SESSION['username'] = $usr[1];
	    						$_SESSION['userid'] = $muid; 
	    						$_SESSION['userqx']=$usr[2];
				    	   	// 如果为学生，进入 //权限也要正确才行。
				    	   	/* if ($mquanxian=='0')
				    	   	$this->response->redirect("idx.php");
				    	 	else
				    	 	$this->response->redirect("indexManager.php"); */
				    	   	switch ($mLoginType)
				    	   	{
				    	   		case "0": // 学生
				    	   			$this->response->redirect("idx.php");break;
				    	   		case "5":// 业务管理人员（总管）
				    	   			$this->response->redirect("indexManager.php");break;
				    	   		case "4": //学校管理人员
				    	   			$this->response->redirect("indexUGovernor.php");break;
				    	   		case "3": //学院管理人员
				    	   			$this->response->redirect("indexSchGovernor.php");break;
				    	   		case "2": //辅导员
				    	   			$this->response->redirect("indexCounselor.php");break;
				    	   		case "1": // 班主任
				    	   			$this->response->redirect("indexClaTeacher.php");break;
				    	   	}
				    	   }	 	  
				    	 }
	    	/* 	$this->flash->error("You don't have permission to access this area");
	    	//指向其他控制器：比如注册控制器里的登录功能！！对于“forwards”转发的次数没有限制，只要不会形成循环重定向即可 如果在循环调度里面没有其他action可以分发，分发器将会自动调用由 Phalcon\Mvc\View 管理的MVC的视图层。
	    	$this->dispatcher->forward(array(
	    			"controller" => "signup",
	    			"action" => "index"
	    	));  */
	  		// 如果不存在此用户，或者密码输入有问题，则跳转到默认界面：应该控制到：$this->response->redirect("login.php");
		         } else //验证码错误，返回 输入界面！！！ 
		        	{
		        		$this->response->redirect("login.php"); //�����ʾ��֤�����
		        	}
		         	
		        } 
		        }catch(Exception $e) {
		        //	echo '<font color="#4B0082">数据库连接错误！</font>';
		        }
			// 当登录输入的信息有错的时候，
					     
	     }
	     //注销
	public function outloginAction(){
		//注销登录
		//session_start();
		unset($_SESSION['userid']);
		unset($_SESSION['username']);
		unset($_SESSION['userqx']);
		session_destroy();
		$this->response->redirect("login.php"); //返回登录界面
	   }
	}
	 
?>
