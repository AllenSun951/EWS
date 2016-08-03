<html>
<meta http-equiv="Content-Type" content="text/html;  charset="utf-8"/>    <!-- 设置页面的编码格式为utf-8-->
<script language="javascript" src="script/trim.js"></script>  <!-- 导入javascript脚本应用程序 -->
<script language="javascript">
  function fcheck()
  {
	var uid = document.all.uid.value.trim();  <!-- 得到用户输入的用户名 并去掉空格-->
    var pwd = document.all.pwd.value.trim(); <!-- 得到用户输入的用户密码并去掉空格-->
    var ValidateCode = document.all.ValidateCode.value.trim();

    if(uid=="")
    {
      alert("用户名不能为空!!!");
      return;
    }
    if(pwd=="")
    {
      alert("密码不能为空!!!");
      return;
    }
    if(pwd.length<6)
    {
      alert("密码长度不得小于6!!!");
      return;
    }    
  	if(ValidateCode.length<4)
    {
       alert("验证码长度不得小于4");
      
      return;
    }
    document.loginform.submit();    <!-- 提交表单  -->
  }
</script>
<form name="loginform" action="Mlogin/mlogin" method="post">
  <center>
  <table border="0" bgcolor="#E4EDFA" width="468px" height="223px" cellspacing="20" cellpadding="0">
    <tr  align="center" height="20" >
      <td width="120px" align="right"><font color="#4B0082">用户名:</font></td>
      <td width="25px" align="left"><input type="text" id="uid" name="uid" size="12"/></td>
    </tr>
    <tr align="center" height="20">
      <td width="120px" align="right"><font color="#4B0082">密&nbsp;码:</font></td>
      <td width="25px" align="left"><input type="password" id="pwd" name="pwd" size="12"/></td>
    </tr>
	<tr align="center" height="20">
      <td width="120px" align="right"><font color="#4B0082">验证码:</font></td>
      <td width="25px" align="left">
	    <input type="text" id="ValidateCode" name="ValidateCode" size="5"/>
		<span>
		<img src="img/code_num.php" id="getcode_num" title="看不清，点击换一张" align="absmiddle"  onclick="this.src='img/code_num.php?'+Math.random();"></p>
		<!-- </span> 
		<span>
		<img src="img/code_num.php" id="getcode_num" title="看不清，点击换一张" align="absmiddle"></p>
		</span> -->
		
	  </td>
    </tr>
      <tr align="center" height="20">
      <td width="120px" align="right"><font color="#4B0082">类&nbsp;别:</font></td>
      <td  width="25px" align="left">
	    <!--<input  id="category" name="category" size="12"/>  -->
	    <select name="LoginType"  id="LoginType" onchange="checkLoginType()" >
		  <option selected="selected" align="center" value="0" >学生</option>
		  <option value="1">班主任</option>
		  <option value="2">辅导员</option>
		  <option value="3">学院管理人员</option>
		  <option value="4">学校管理人员</option>
		  <option value="5">业务管理员</option>
		</select>
	  </td>
    </tr>
	<tr align="center">
      <td colspan="2" height="20" align="center" >

        <span id="UpdatePanel2">
       <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="btLogin" tabindex="4" title="登录系统" href=" " >登录</a> -->
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" style="border-style:solid;border-width:1px;border-color:#388BFF;" id="denglu" name="denglu" value="登录" onclick="fcheck()"/>
        </span id="UpdatePanel3">
		<span>
        &nbsp;&nbsp;<a id="aHelp" href="helplogin.html"  tabindex="6" title="系统使用帮助" target="">帮助</a>
		</span>
        <span id="UpdatePanel4">
        &nbsp;&nbsp;<a id="hykPwd" title="忘记登录密码" href="fpw.html" target="">忘密</a>
        </span>
        <br/>
  	    <br/>
  	    <div style="float:right;width:60px;height:30px;">
    	</div>
    	
    	<span>
        &nbsp;&nbsp;<a id="mRegister" href="signup"  tabindex="6" target="" style="float:right;">注册 </a>
		</span>
  		 
      </td>
    </tr>
    <tr>
        
      <td colspan="2" height="20" align="center">&nbsp;&nbsp;&nbsp;<font color="#4B0082">友情提示：如无法登录系统，请您点击“帮助”按钮，检查用户名和密码以及类别的选择！</font></td>
    </tr>
  </table>
  </center>
</form>
</html>