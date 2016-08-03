<?php
class IndexController extends ControllerBase
{

    public function indexAction()
    {
    	$muid=$this->request->get("uid").trim();
    	$mpwd=$this->request->get("pwd").trim();
    	$mLoginType=$this->request->get("LoginType").trim();
    	
    	$conn =$this->getDI()->get("db");
    	$sql="select ID,XM,QX,FW,MM from USERS";
    	//users 中的数据为 ID，XM，MM，QX，FW。账号名，姓名，密码，权限（职位），权限范围
    	$result = $conn->query($sql);
    	//usr 中的数据为 ID，XM，QX，FW，MM == usr[0],usr[1],usr[2],usr[3],usr[4]；
		//$this->response->redirect("idx.php");
		//总共有6个权限：0：学生:1：班主任，2：辅导员，3：学院管理人员，4：学校管理人员，5系统维护人员（业务管理人员）
  /*   	switch ($mLoginType)
    	{
    	case "0":  
    	 	$mquanxian="0";break;  
    	case "5":  
    	 	$mquanxian="5";break;
    	case "4":
    	 	$mquanxian="4";break;
    	 case "3":
    	 	$mquanxian="3";break;
    	 case "2":
    	 	$mquanxian="2";break;
    	 case "1":
    	 	$mquanxian="1";break;
    	} */
    	
    	while($usr=$result->fetch()){
    				
    	 if($muid==$usr[0].trim() && $mpwd==$usr[4].trim() && $mLoginType==$usr[2].trim())
    	 //if($muid==$usr[0] && $mpwd==$usr[4])
    	   {
    	   	// 如果为学生，进入 //权限也要正确才行。
    	   	if ($mquanxian=='0')
    	   	$this->response->redirect("idx.php");
    	 	else
    	 	$this->response->redirect("indexManager.php");
    	   }	 	  
    	 }
  		// 如果不存在此用户，或者密码输入有问题，则跳转到默认界面：应该控制到：$this->response->redirect("login.php");
    }
    public function homeAction(){
    	
    }

}


