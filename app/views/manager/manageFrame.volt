<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript">

 $.ajax({
            	type:'post',
            	url:'InitManageFrame',
            	data:'',
            	success:function(data){
            	   $("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });

 //修改按钮           
function ModifyBtnClick(){
   
	 $.ajax({
            	type:'post',
            	url:'modify',
            	data:'',
            	success:function(data){
            	   $("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });
  } 
  
  //执行按钮
 function ExecuteBtnClick(){
    	$.ajax({
            	type:'post',
            	url:'execute',
            	data:'',
            	success:function(data){
            	   $("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });
 	}
 //输完执行密码后的确认更新成绩按钮
 function confirmBtnCJBClick()	{
 	var mconfirm=$('#mpwd').val();
 	if (mconfirm==""){
 		alert("输入不能为空，请重新输入！！！");
 	}
 	else{
 	if (mconfirm.indexOf(" ")>=0){
 		alert("输入的密码存在空格，请重新检查输入！！！");
 	}else{
 		if(window.confirm("确认执行？")){
 			// 首先隐藏所有按钮
			document.getElementById('confirmBtnCJB').style.display='none';
			document.getElementById('confirmBtnJS').style.display='none';
			document.getElementById('rebackBtn').style.display='none';
			document.getElementById('changePwd').style.display='none';
			document.getElementById('pwddiv').style.display='none';
			document.getElementById('TimeCJBpsdiv').style.display='none';
			document.getElementById('Timepsdiv').style.display='none';
			$("#TimeCJBpsdiv").hide();
			$("#Timepsdiv").hide();
			//显示提醒 div 内容：
			document.getElementById('CJBpsdiv').style.display='inline';
 			$.ajax({
            	type:'post',
            	url:'confirmCJB',
            	data:'mconfirm='+mconfirm,
            	success:function(data){
            	   $("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
              });
 			}
 		}
 	}
 }
 //输完执行密码后的确认计算警示级别 按钮
 function confirmBtnJSClick()	{
 	var mconfirm=$('#mpwd').val();
 	if (mconfirm==""){
 		alert("输入不能为空，请重新输入！！！");
 	}
 	else{
 	if (mconfirm.indexOf(" ")>=0){
 		alert("输入的密码存在空格，请重新检查输入！！！");
 	}else{
 		if(window.confirm("确认执行？")){
 			// 首先隐藏所有按钮
			document.getElementById('confirmBtnCJB').style.display='none';
			document.getElementById('confirmBtnJS').style.display='none';
			document.getElementById('rebackBtn').style.display='none';
			document.getElementById('changePwd').style.display='none';
			document.getElementById('pwddiv').style.display='none';
		    document.getElementById('TimeCJBpsdiv').style.display='none';
			document.getElementById('Timepsdiv').style.display='none';
			$("#TimeCJBpsdiv").hide();
			$("#Timepsdiv").hide();
			//显示提醒 div 内容：
			document.getElementById('JSpsdiv').style.display='inline';
 			$.ajax({
            	type:'post',
            	url:'confirmJS',
            	data:'mconfirm='+mconfirm,
            	success:function(data){
            	   $("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
              });
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
  //提交修改执行密码 判断是否存在空格符号 
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
 	    else {
 	        var reg=new RegExp("[\\u4E00-\\u9FFF]+","g"); 
 	    	if(reg.test(mchangePwd1))
 	    	{ 
 	    		alert("输入的新密码存在中文字符！！！");
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
 }
 //去空格
 function trim(str){
    return str.replace(/\s|\xA0/g,"");  
}

//提交按钮
 function submitBtnClick(){
    var IJS1 = trim($('#I1').val());
    var IJS2 = trim($('#I2').val());
    var IJS3 = trim($('#I3').val());
    var IJS4 = trim($('#I4').val());
    
    var IIJS1  = trim($('#II1').val());
    var IIJS21 = trim($('#II21').val());
    var IIJS22 = trim($('#II22').val());
    var IIJS31 = trim($('#II31').val());
    var IIJS32 = trim($('#II32').val());
    var IIJS4  = trim($('#II4').val());
    
    var IIIJS1  = trim($('#III1').val());
    var IIIJS21 = trim($('#III21').val());
    var IIIJS22 = trim($('#III22').val());
    var IIIJS31 = trim($('#III31').val());
    var IIIJS32 = trim($('#III32').val());
    var IIIJS4  = 0;
    	 //判断用户输入的是否为空
     	 if(IJS1==""|| IJS2==""|| IJS3==""|| IJS4==""|| IIJS1==""|| IIJS21==""|| IIJS22==""|| IIJS31==""
      	 	||IIJS32==""||IIJS4==""||IIIJS1==""||IIIJS21==""||IIIJS22==""||IIIJS31==""||IIIJS32=="")
    			{
    				alert("输入内容不能为空 ，请检查！");
    		 	}
    	 	else{
    	 	     //判断用户输入的是否为数字字符
    	 		if(isNaN(IJS1)|| isNaN(IJS2)|| isNaN(IJS3)|| isNaN(IJS4)|| isNaN(IIJS1)|| isNaN(IIJS21)|| isNaN(IIJS22)|| isNaN(IIJS31)
       				|| isNaN(IIJS32)|| isNaN(IIJS4)|| isNaN(IIIJS1)|| isNaN(IIIJS21)|| isNaN(IIIJS22)|| isNaN(IIIJS31)|| isNaN(IIIJS32))
   					{
    	 				alert("输入内容存在非数字，请检查！");
    	 			}else{
    	 				//以下对字符串加操作 就可以去除 数字前面的 0 即，015=15
    					var row1=IJS1+","+IJS1+","+IIJS1+","+IIJS1+","+IIIJS1+","+IIIJS1;
    					var row2=IJS2+","+IJS2+","+IIJS21+","+IIJS22+","+IIIJS21+","+IIIJS22;
    					var row3=IJS3+","+IJS3+","+IIJS31+","+IIJS32+","+IIIJS31+","+IIIJS32;
    					var row4=IJS4+","+IJS4+","+IIJS4+","+IIJS4+","+IIIJS4+","+IIIJS4;
    					var sum=row1+","+row2+","+row3+","+row4;
     					 //alert(sum);
   						if(window.confirm("确定提交吗？")){
    						$.ajax({
            				type:'post',
            				url:'submitBtn',
            				data:'sum='+sum,
            				success:function(data){
            	  			 $("#divtable").html(data);
            					},
            				dataType:'html',
            				error:function(){
            					}
            				}); 
   		        		  }
   		        		 else{
    		    			
  			  			}
    	  			}
       			}	 
 }
 //返回查看按钮
function rebackBtnClick(){
$.ajax({
            	type:'post',
            	url:'InitManageFrame',
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
<br/>
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
