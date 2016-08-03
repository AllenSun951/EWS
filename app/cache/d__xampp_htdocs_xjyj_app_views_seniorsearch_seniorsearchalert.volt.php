<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>

<script type="text/javascript">
//初始化查询界面
 $.ajax({
            	type:'post',
            	url:'InitSearchCondition',
            	data:'',
            	success:function(data){
            	   $("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            
            });
            
function initXq(){
    
    	  $.ajax({
            	type:'post',
            	url:'xnq',
            	data:'',
            	success:function(data){
            	   $("#xq").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            
            });
    
    }  
     
function btnClick(){
   var mycondition=$('#condition').val();
   if (mycondition=="00"){
       alert("请选择查询条件");}
   else
   		{  
         var mycontent=$('#content').val();
         if (mycontent==""){ 
           alert("请输入"+mycondition);}
          else{
               if(mycondition=="学号"){
                 if(mycontent.length<8){
                 alert("学号位数错误，不少于8位！");
                 }
                 else{
             		  var mxq=$('#xq').val();
   			  		 if (mxq=="00"){
   			   		  $.ajax({
          	 		 type:'post',
          	 		 url:'ConditionButton',
            		 data:'condition='+mycondition+mycontent,
            		 success:function(data){
            		 $("#divtable").html(data);
            		},
            		dataType:'html',
            		error:function(){
            		}
               	 	});
              		}
               		 else{
                 			$.ajax({
          	 	 			type:'post',
          	 	 			url:'xnqConditionButton',
            	 			data:'condition='+mxq+mycondition+mycontent,
            	 			success:function(data){
            				 $("#divtable").html(data);
            				},
            				dataType:'html',
            				error:function(){
            				}
              			 });	
           				}
                }
               
             } else{
             		  var mxq=$('#xq').val();
   			  		 if (mxq=="00"){
   			   		  $.ajax({
          	 		 type:'post',
          	 		 url:'ConditionButton',
            		 data:'condition='+mycondition+mycontent,
            		 success:function(data){
            		 $("#divtable").html(data);
            		},
            		dataType:'html',
            		error:function(){
            		}
               	 	});
              		}
               		 else{
                 			$.ajax({
          	 	 			type:'post',
          	 	 			url:'xnqConditionButton',
            	 			data:'condition='+mxq+mycondition+mycontent,
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
 	} 
 
 function exclbtnClick(){
   var mycondition=$('#condition').val();
   if (mycondition=="00"){
       alert("请选择导出条件内容");}
   else
   		{  
         var mycontent=$('#content').val();
         if (mycontent==""){
           alert("请输入"+mycondition);}
         else{
               var mxq=$('#xq').val();
   			   if (mxq=="00"){
   			   window.open('ConditionExcel?condition='+mycondition+mycontent);
              }
               else{
               window.open('xnqConditionExcel?condition='+mxq+mycondition+mycontent);	
           }
         }
   	}     
}
//注销          
function logoutBtnClick(){
    document.getElementById("xq").style.display="none";
	document.getElementById("condition").style.display="none";
	document.getElementById("content").style.display="none";
	document.getElementById("exclbutton").style.display="none";
	document.getElementById("btn").style.display="none";
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
$(document).ready(function(){ 
	$("input").bind("keyup",function(){ 
		if (event.keyCode == 13) { 
		 btnClick(); 
	 }	
	}); 
}); 
</script>   
 <style>
SELECT
{	border:1px solid #4169E1;
    FONT-SIZE: 12px;
    FONT-FAMILY: "宋体","Verdana";
    BACKGROUND-COLOR: #FFFFF0;
    color: ;
    MARGIN-LEFT: 3px;
}
</style>
  <br/>
  <font color="#4B0082"> 学期:</font>
	<select name="xq" id="xq">     
	   <option selected="selected" value="00">全学期统计</option> 
         
       <?php 
            while($xq=$xqs->fetch()){
  			  echo '<option value=' . $xq[0] . '>' . $xq[0] . '</option>';
 		    }
        ?> 	
     </select>
 <span style="padding:10px;"></span> 
  <font color="#4B0082">条件:</font>
	<select  name="condition" id="condition" >     
	    <option value="00">请选择条件</option>
	    <?php 
            while($tj=$condition->fetch()){
  			  echo '<option value=' . $tj[0] . '>' . $tj[0] . '</option>';
 		    }
        ?> 	
	      
    </select>
<span style="padding:10px;"></span>   
 <font color="#4B0082">内容:</font>
	<input type="text" name="content" id="content" style="border-style:solid;border-width:1px;border-color:#388BFF;" size="12"/>    
	<input type="button" name="btn" id="btn" style="border-style:solid;border-width:1px;border-color:#388BFF;width:50px;height:30px;margin-left:10px;margin-right:20px;font-size:16px;"  onclick="btnClick()" value="查询"  > 
	<input type="button"  style="border-style:solid;border-width:1px;border-color:#388BFF;width:85px;height:30px;font-size:16px" value="导出Excel" name="exclbutton" id="exclbutton" onclick="exclbtnClick()">     
	
<br/>

<div id="divtable">
	
</div>

<br/>

<div id="divexcl">
	
</div>
<br/>
