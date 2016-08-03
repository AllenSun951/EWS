<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript">

 $.ajax({
            	type:'post',
            	url:'InitPersonalInfo',
            	data:'',
            	success:function(data){
            	   $("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });
  //提交修改密码 判断是否存在空格符号 
 function confirmPwdBtnClick(){
 	var mchangePwd0=$('#mchangePwd').val();
 	var mchangePwd1=$('#mchangePwd1').val();
 	var mchangePwd2=$('#mchangePwd2').val();
 	if (mchangePwd0=="" || mchangePwd1=="" || mchangePwd2==""){
 		alert("输入不能为空，请重新输入！！！");
 	}
 	else{
 	if (mchangePwd0.indexOf(" ")>=0 || mchangePwd1.indexOf(" ")>=0 || mchangePwd2.indexOf(" ")>=0){
 		alert("输入的密码存在空格，请重新检查输入！！！");
 	}else{
 		if(mchangePwd1.length>10){
 	    alert("输入的新密码超过10位！！！");
 	    } 
 	    else{
 	    
 		if(mchangePwd1==mchangePwd2){
 		$.ajax({
            	type:'post',
            	url:'confirmPwd',
            	data:'mchangePwd='+mchangePwd0+','+mchangePwd1,
            	success:function(data){
            	   $("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });
 		} else{
 		alert("两次输入的新密码不同，请返回重新输入 ！");
 		}
 	   }
 	  }
 	}
 }
 	 // 修改执行密码函数
 function changePwdBtnClick(){
 	$.ajax({
            	type:'post',
            	url:'changePwd',
            	data:'',
            	success:function(data){
            	   $("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });
 }
 //返回查看按钮
function rebackBtnClick(){
$.ajax({
            	type:'post',
            	url:'InitPersonalInfo',
            	data:'',
            	success:function(data){
            	   $("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });
}

 //注销          
function logoutBtnClick(){
	$.ajax({
            	type:'post',
            	url:'logoutBtn',
            	data:'',
            	success:function(data){
            	   $("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });
  } 
</script>   
<div id="divtable">
</div>
 <br/>
 <br/>
<br/>
<div id="divtable2">
 
  <!--?php  var_dump($cont); ?--> 		
		
</div>
<div id="divtable3">
		<!--?php  var_dump($tjcont); ?--> 	
</div>
<br/>

<br/>
