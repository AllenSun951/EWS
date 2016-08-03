<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
<script src="../js/chart/Chart.js"></script>
<script type="text/javascript">
function initXq(){
//document.getElementById("Drawbtn").style.display="inline";
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
    
 function initCollege(){
 //document.getElementById("Drawbtn").style.display="inline";
    	  $.ajax({
            	type:'post',
            	url:'college',
            	data:'',
            	success:function(data){
            	   $("#college").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            
            });
    
    }
    
  function initMajor(){
  //document.getElementById("Drawbtn").style.display="inline";
    	  $.ajax({
            	type:'post',
            	url:'major',
            	data:'',
            	success:function(data){
            	   $("#major").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            
            });
    
    }
    
  	// 画图到班级，隐藏分统计，只有总统计。
    function initGrade(){
    	//document.getElementById("Drawbtn").style.display="none";
    	  $.ajax({
            	type:'post',
            	url:'grade',
            	data:'',
            	success:function(data){
            	   $("#grade").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            
            });
    
    }
      
    function initStu(){
    }
    $(document).ready(function(){
         initXq();
         initCollege(); 
         $("#college").change(function(){

         var p=$(this).children('option:selected').val();
         $.ajax({
            	type:'post',
            	url:'major',
            	data:'xy='+p,
            	success:function(data){
            	   $("#major").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            
            });
        
         initGrade();
         });
          $("#major").change(function(){
            var p=$(this).children('option:selected').val();
            $.ajax({
            	type:'post',
            	url:'grade',
            	data:'zy='+p,
            	success:function(data){
            	   $("#grade").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            }); 
         });
       
    });
</script>

<script type="text/javascript">
//控制图 缩放   防止件变动后， 未点击图统计按钮就进行画图
	var xqold="00";
	var xyold="00";
	var zyold="00";
	var bjold="00";
	
	var xnqxyold="00";
	var xnqzyold="00";
	var xnqbjold="00";
    $.ajax({
          type:'GET',
          url:'xxChart',
		  dataType: "text", 
          data:'xx=z',
          success:function(data){
          $("#divtu").html(data);
            },
          dataType:'html',
          error:function(){
          }
         });

function DrawbtnClick(){
 var mxq=$('#xq').val();
   window.xqold=mxq;
   if (mxq=="00"){
   var mxy=$('#college').val();
       window.xyold=mxy;
   if (mxy=="00"){
	  	 $.ajax({
          	 type:'post',
          	 url:'xxChart',
            	data:'xx=z',
            	success:function(data){
            	   $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });		
   		}
   		else
   		{
   			var mzy=$('#major').val();
   			    window.zyold=mzy;
   			if(mzy=="00"){
   		  	 $.ajax({
          	 type:'post',
          	 url:'xyChart',
            	data:'xy=z'+mxy,
            	success:function(data){
            	   $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });		
   			}else
   			{
   				 var mbj=$('#grade').val();
   				     window.bjold=mbj;
   				if(mbj=="00"){
   				$.ajax({
           			type:'post',
           			url:'zyChart',
            		data:'zy=z'+mzy,
            		success:function(data){
            	  	 $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
           	 });
   			}else
   				{
   					$.ajax({
          		 		type:'post',
           				url:'bjChart',
            			data:'bj=z'+mbj,
            			success:function(data){
            	 	  	$("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
           	 });
   			 	
   		   }
  	      }
        }
      }
else
    {
      var mxnxy=$('#college').val();
	      window.xnqxyold=mxnxy;
		if (mxnxy=="00"){
	  	 $.ajax({
          	 	type:'post',
          	 	url:'xnqxxChart',
            	data:'xnq=z'+mxq,
            	success:function(data){
            	   $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });		
   		   }
  	 	
  	 		else {
				   var mxnzy=$('#major').val();
				     window.xnqzyold=mxnzy;
   					if(mxnzy=="00"){
   		  			 $.ajax({
          		 	 type:'post',
          	 		 url:'xnqxyChart',
            	 	 data:'xnq=z'+mxq+mxnxy,
            	 	 success:function(data){
            		 $("#divtu").html(data);
            		},
            		dataType:'html',
            		error:function(){
            		  }
            		});		
   				 }
   				 else
   					{
   					 var mxnbj=$('#grade').val();
   					   window.xnqbjold=mxnbj;
   					   if(mxnbj=="00"){
   						$.ajax({
           				type:'post',
           				url:'xnqzyChart',
            			data:'xnq=z'+mxq+mxnzy,
            			success:function(data){
            	  		$("#divtu").html(data);
            		   },
            			dataType:'html',
            			error:function(){
            			}
           	 			});
   					  }
   					  
   					  else
   							{
   							$.ajax({
          		 			type:'post',
           					url:'xnqbjChart',
            				data:'xnq=z'+mxq+mxnbj,
            				success:function(data){
            	 	  		$("#divtu").html(data);
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
		
  function DrawOldbtnClick(){
   if (window.xqold=="00"){
   if (window.xyold=="00"){
	  	 $.ajax({
          	 type:'post',
          	 url:'xxChart',
            	data:'xx=z',
            	success:function(data){
            	   $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });		
   		}
   		else
   		{
   			if(window.zyold=="00"){
   		  	 $.ajax({
          	 type:'post',
          	 url:'xyChart',
            	data:'xy=z'+window.xyold,
            	success:function(data){
            	   $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });		
   			}else
   			{
   				if(window.bjold=="00"){
   				$.ajax({
           			type:'post',
           			url:'zyChart',
            		data:'zy=z'+window.zyold,
            		success:function(data){
            	  	 $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
           	 });
   			}else
   				{
   					$.ajax({
          		 		type:'post',
           				url:'bjChart',
            			data:'bj=z'+window.bjold,
            			success:function(data){
            	 	  	$("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
           	 });
   			 	
   		   }
  	      }
        }
      }
else	 
    {
		if (window.xnqxyold=="00"){
	  	 $.ajax({
          	 	type:'post',
          	 	url:'xnqxxChart',
            	data:'xnq=z'+window.xqold,
            	success:function(data){
            	   $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });		
   		   }
  	 		else { 
   					if(window.xnqzyold=="00"){
   		  			 $.ajax({
          		 	 type:'post',
          	 		 url:'xnqxyChart',
            	 	 data:'xnq=z'+window.xqold+window.xnqxyold,
            	 	 success:function(data){
            		 $("#divtu").html(data);
            		},
            		dataType:'html',
            		error:function(){
            		  }
            		});		
   				 }
   				 else
   					{
   					   if(window.xnqbjold=="00"){
   						$.ajax({
           				type:'post',
           				url:'xnqzyChart',
            			data:'xnq=z'+window.xqold+window.xnqzyold,
            			success:function(data){
            	  		$("#divtu").html(data);
            		   },
            			dataType:'html',
            			error:function(){
            			}
           	 			});
   					  }
   					  else
   							{
   							$.ajax({
          		 			type:'post',
           					url:'xnqbjChart',
            				data:'xnq=z'+window.xqold+window.xnqbjold,
            				success:function(data){
            	 	  		$("#divtu").html(data);
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
//注销          
function logoutBtnClick(){
    document.getElementById("xq").style.display="none";
	document.getElementById("college").style.display="none";
	document.getElementById("major").style.display="none";
	document.getElementById("grade").style.display="none";
	document.getElementById("Drawbtn").style.display="none";
	document.getElementById("Drawbtn2").style.display="none";
	 $.ajax({
            	type:'post',
            	url:'logoutBtn',
            	data:'',
            	success:function(data){
            	   $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            }); 
       /* <!--?php
        session_start();
        unset($_SESSION['userid']);
		unset($_SESSION['username']);
		session_destroy();
		?-->*/
     //window.open('../login.php');	
  } 	
$(document).keypress(function(e) { 
	if (e.which == 13) { 
	 DrawbtnClick(); 
	} 	
});	
$(document).ready(function(){ 
	$("select").bind("keyup",function(){ 
		if (event.keyCode == 13) { 
		 DrawbtnClick(); 
		 event.returnValue = false;
	 }	
	}); 
});
// 总统计图：即极值坐标=圆饼
function BDrawbtnClick(){
 var mxq=$('#xq').val();
   window.xqold=mxq;
   if (mxq=="00"){
   var mxy=$('#college').val();
       window.xyold=mxy;
   if (mxy=="00"){
	  	 $.ajax({
          	 type:'post',
          	 url:'xxChart',
            	data:'xx=b',
            	success:function(data){
            	   $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });		
   		}
   		else
   		{
   			var mzy=$('#major').val();
   			    window.zyold=mzy;
   			if(mzy=="00"){
   		  	 $.ajax({
          	 type:'post',
          	 url:'xyChart',
            	data:'xy=b'+mxy,
            	success:function(data){
            	   $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });		
   			}else
   			{
   				 var mbj=$('#grade').val();
   				     window.bjold=mbj;
   				if(mbj=="00"){
   				$.ajax({
           			type:'post',
           			url:'zyChart',
            		data:'zy=b'+mzy,
            		success:function(data){
            	  	 $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
           	 });
   			}else
   				{
   					$.ajax({
          		 		type:'post',
           				url:'bjChart',
            			data:'bj=b'+mbj,
            			success:function(data){
            	 	  	$("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
           	 });
   			 	
   		   }
  	      }
        }
      }
else
    {
      var mxnxy=$('#college').val();
	      window.xnqxyold=mxnxy;
		if (mxnxy=="00"){
	  	 $.ajax({
          	 	type:'post',
          	 	url:'xnqxxChart',
            	data:'xnq=b'+mxq,
            	success:function(data){
            	   $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });		
   		   }
  	 	
  	 		else {
				   var mxnzy=$('#major').val();
				     window.xnqzyold=mxnzy;
   					if(mxnzy=="00"){
   		  			 $.ajax({
          		 	 type:'post',
          	 		 url:'xnqxyChart',
            	 	 data:'xnq=b'+mxq+mxnxy,
            	 	 success:function(data){
            		 $("#divtu").html(data);
            		},
            		dataType:'html',
            		error:function(){
            		  }
            		});		
   				 }
   				 else
   					{
   					 var mxnbj=$('#grade').val();
   					   window.xnqbjold=mxnbj;
   					   if(mxnbj=="00"){
   						$.ajax({
           				type:'post',
           				url:'xnqzyChart',
            			data:'xnq=b'+mxq+mxnzy,
            			success:function(data){
            	  		$("#divtu").html(data);
            		   },
            			dataType:'html',
            			error:function(){
            			}
           	 			});
   					  }
   					  
   					  else
   							{ 
   							$.ajax({
          		 			type:'post',
           					url:'xnqbjChart',
            				data:'xnq=b'+mxq+mxnbj,
            				success:function(data){
            	 	  		$("#divtu").html(data);
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
		
  function BDrawOldbtnClick(){
   if (window.xqold=="00"){
   if (window.xyold=="00"){
	  	 $.ajax({
          	 type:'post',
          	 url:'xxChart',
            	data:'xx=b',
            	success:function(data){
            	   $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });		
   		}
   		else
   		{
   			if(window.zyold=="00"){
   		  	 $.ajax({
          	 type:'post',
          	 url:'xyChart',
            	data:'xy=b'+window.xyold,
            	success:function(data){
            	   $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });		
   			}else
   			{
   				if(window.bjold=="00"){
   				$.ajax({
           			type:'post',
           			url:'zyChart',
            		data:'zy=b'+window.zyold,
            		success:function(data){
            	  	 $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
           	 });
   			}else
   				{
   					$.ajax({
          		 		type:'post',
           				url:'bjChart',
            			data:'bj=b'+window.bjold,
            			success:function(data){
            	 	  	$("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
           	 });
   			 	
   		   }
  	      }
        }
      }
else	 
    {
		if (window.xnqxyold=="00"){
	  	 $.ajax({
          	 	type:'post',
          	 	url:'xnqxxChart',
            	data:'xnq=b'+window.xqold,
            	success:function(data){
            	   $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });		
   		   }
  	 		else { 
   					if(window.xnqzyold=="00"){
   		  			 $.ajax({
          		 	 type:'post',
          	 		 url:'xnqxyChart',
            	 	 data:'xnq=b'+window.xqold+window.xnqxyold,
            	 	 success:function(data){
            		 $("#divtu").html(data);
            		},
            		dataType:'html',
            		error:function(){
            		  }
            		});		
   				 }
   				 else
   					{
   					   if(window.xnqbjold=="00"){
   						$.ajax({
           				type:'post',
           				url:'xnqzyChart',
            			data:'xnq=b'+window.xqold+window.xnqzyold,
            			success:function(data){
            	  		$("#divtu").html(data);
            		   },
            			dataType:'html',
            			error:function(){
            			}
           	 			});
   					  }
   					  else
   						{
   							$.ajax({
          		 			type:'post',
           					url:'xnqbjChart',
            				data:'xnq=b'+window.xqold+window.xnqbjold,
            				success:function(data){
            	 	  		$("#divtu").html(data);
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

   <font color="#4B0082">学期:</font>
	   <select name="xq" id="xq" >     
	       <option selected="selected" value="00">全学期统计</option> 
         
           <?php 
            while($xq=$xqs->fetch()){
  			  echo '<option value=' . $xq[0] . '>' . $xq[0] . '</option>';
 		    }
          ?> 	
        </select>
  <font color="#4B0082">学院:</font>
	   <select id="college" name="college" >      
	    <option value="00">未选择学院</option>  
       </select>
   <font color="#4B0082">专业:</font>
	   <select  name="major" id="major" >     
	     <option value="00">未选择专业</option>  
       </select>
   <font color="#4B0082">班级:</font>
	   <select  name="grade" id="grade">    
	     <option value="00">未选择班级</option>  
       </select>
    <br/>
    <br/>
   
    <div style="float:right;">
    <input type="button"  style="border-style:solid;border-width:1px;border-color:#388BFF;border-style:solid;border-width:1px;border-color:#388BFF;background:#BCD2EE;width:65px;height:30px;margin-left:10px;margin-right:10px;font-size:15px;" id="Drawbtn" onclick="DrawbtnClick()" value="分统计"  > 
    <input type="button"  style="border-style:solid;border-width:1px;border-color:#388BFF;border-style:solid;border-width:1px;border-color:#388BFF;background:#BCD2EE;width:65px;height:30px;margin-left:10px;margin-right:80px;font-size:15px;" id="Drawbtn2" onclick="BDrawbtnClick()" value="总统计"  > 
    </div>	
<br/>

<div id="divtu" style="width:100%;height:90%;">
	 
</div>