<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript">
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
    
 function initCollege(){
    
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
  
    function initGrade(){
    
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
    
    	  $.ajax({
            	type:'post',
            	url:'student',
            	data:'',
            	success:function(data){
            	   $("#stuName").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            
            });
    
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
         initStu();
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
            
            initStu();
         });
         
          $("#grade").change(function(){
         
            var p=$(this).children('option:selected').val();
            $.ajax({
            	type:'post',
            	url:'student',
            	data:'bj='+p,
            	success:function(data){
            	   $("#stuName").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });
         });
    });

</script>
 
<script type="text/javascript">
	$.ajax({
          type:'post',
          url:'xxckbutton',
          data:'',
          success:function(data){
           $("#divtable").html(data);
            },
           dataType:'html',
           error:function(){
            }
            });		


 function btnClick(){
   var mxq=$('#xq').val();
   if (mxq=="00"){
   var mxy=$('#college').val();
   if (mxy=="00"){
	  	 $.ajax({
          	 type:'post',
          	 url:'xxckbutton',
            	data:'',
            	success:function(data){
            	   $("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });		
   		}
   		else
   		{
   			var mzy=$('#major').val();
   			if(mzy=="00"){
   		  	 $.ajax({
          	 type:'post',
          	 url:'xyckbutton',
            	data:'xy='+mxy,
            	success:function(data){
            	   $("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });		
   			}else
   			{
   				 var mbj=$('#grade').val();
   				if(mbj=="00"){
   				$.ajax({
           			type:'post',
           			url:'zyckbutton',
            		data:'zy='+mzy,
            		success:function(data){
            	  	 $("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
           	 });
   			}else
   				{
   					var mstu=$('#stuName').val();
   					if(mstu=="00"){
   					$.ajax({
          		 		type:'post',
           				url:'bjckbutton',
            			data:'bj='+mbj,
            			success:function(data){
            	 	  	$("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
           	 });
   			}else
   				{
   					$.ajax({
           			type:'post',
           			url:'xsckbutton',
            		data:'xs='+mstu,
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
else
    {
      var mxnxy=$('#college').val();
		if (mxnxy=="00"){
	  	 $.ajax({
          	 	type:'post',
          	 	url:'xnxxckbutton',
            	data:'xnq='+mxq,
            	success:function(data){
            	   $("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });		
   		   }
  	 	
  	 		else {
				   var mxnzy=$('#major').val();
   					if(mxnzy=="00"){
   		  			 $.ajax({
          		 	 type:'post',
          	 		 url:'xnxyckbutton',
            	 	 data:'xnq='+mxq+mxnxy,
            	 	 success:function(data){
            		 $("#divtable").html(data);
            		},
            		dataType:'html',
            		error:function(){
            		  }
            		});		
   				 }
   				 else
   					{
   					 var mxnbj=$('#grade').val();
   					   if(mxnbj=="00"){
   						$.ajax({
           				type:'post',
           				url:'xnzyckbutton',
            			data:'xnq='+mxq+mxnzy,
            			success:function(data){
            	  		$("#divtable").html(data);
            		   },
            			dataType:'html',
            			error:function(){
            			}
           	 			});
   					  }
   					  
   					  else
   							{
   							var mxnstu=$('#stuName').val();
   							if(mxnstu=="00"){
   							$.ajax({
          		 			type:'post',
           					url:'xnbjckbutton',
            				data:'xnq='+mxq+mxnbj,
            				success:function(data){
            	 	  		$("#divtable").html(data);
            				},
            				dataType:'html',
            				error:function(){
            				}
           	 				});
           	 				
   						}	else
   								{
   									$.ajax({
           							type:'post',
           							url:'xnxsckbutton',
            						data:'xnq='+mxq+mxnstu,
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
   }
</script>

<script type="text/javascript">

function exclbtnClick(){
   var mxq=$('#xq').val();
   if (mxq=="00"){
   		var mxy=$('#college').val();
   		if (mxy=="00"){
            window.open('xxexcel');
   		}
   		else
   		{
   			var mzy=$('#major').val();
   			if(mzy=="00"){
   		  	 window.open('xyexcel?xy='+mxy);	
   			}else
   			{
   				 var mbj=$('#grade').val();
   				if(mbj=="00"){
   		  	 window.open('zyexcel?zy='+mzy);	
   			}else
   				{
   					var mstu=$('#stuName').val();
   					if(mstu=="00"){
   					window.open('bjexcel?bj='+mbj);	
   			}else
   				{
   					window.open('xsexcel?xs='+mstu);	
   			    }	
   		     }
  	      }
       }
    }
else
    {  //带学期
      var mxnxy=$('#college').val();
		if (mxnxy=="00"){
	    	window.open('xnqxxexcel?xnq='+mxq);	
   		   }
  	 		else {
				   var mxnzy=$('#major').val();
   					if(mxnzy=="00"){
   		  			window.open('xnqxyexcel?xnq='+mxq+mxnxy);		
   				 }
   				 else
   					{
   					 var mxnbj=$('#grade').val();
   					   if(mxnbj=="00"){
   						window.open('xnqzyexcel?xnq='+mxq+mxnzy);	
   					  }
   					  else
   							{
   							var mxnstu=$('#stuName').val();
   							if(mxnstu=="00"){
   							window.open('xnqbjexcel?xnq='+mxq+mxnbj);
   						}	else
   								{
   								window.open('xnqxsexcel?xnq='+mxq+mxnstu);
   			  				    }		
   		    		}
  	      		} 
          }	 	
     }
}
	var xqold="00";
	var xyold="00";
	var zyold="00";
	var bjold="00";
	var xnqxyold="00";
	var xnqzyold="00";
	var xnqbjold="00";
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
            	   $("#divtable").html(data);
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
            	   $("#divtable").html(data);
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
            	  	 $("#divtable").html(data);
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
            	   $("#divtable").html(data);
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
            		 $("#divtable").html(data);
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
            	  		$("#divtable").html(data);
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
		
  function DrawOldbtnClick(){
 /*alert("测试");
   if (window.xqold=="00"){
   if (window.xyold=="00"){
	  	 $.ajax({
          	 type:'post',
          	 url:'xxChart',
            	data:'xx=z',
            	success:function(data){
            	   $("#divtable").html(data);
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
            	   $("#divtable").html(data);
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
            	  	 $("#divtable").html(data);
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
else	 
    {
		if (window.xnqxyold=="00"){
	  	 $.ajax({
          	 	type:'post',
          	 	url:'xnqxxChart',
            	data:'xnq=z'+window.xqold,
            	success:function(data){
            	   $("#divtable").html(data);
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
            		 $("#divtable").html(data);
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
            	  		$("#divtable").html(data);
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
            	 	  		$("#divtable").html(data);
            				},
            				dataType:'html',
            				error:function(){
            				}
           	 				});
   		    		}
  	      		} 
        }		 	
     }	*/
}
//注销          
function logoutBtnClick(){
    document.getElementById("xq").style.display="none";
	document.getElementById("college").style.display="none";
	document.getElementById("major").style.display="none";
	document.getElementById("grade").style.display="none";
	document.getElementById("stuName").style.display="none";
	document.getElementById("Drawbtn").style.display="none";
	document.getElementById("exclbutton").style.display="none";
	document.getElementById("btnClk").style.display="none";
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
$(document).keypress(function(e) { 
	if (e.which == 13) { 
	 btnClick(); 
	} 	
});
$(document).ready(function(){ 
	$("select").bind("keyup",function(){ 
		if (event.keyCode == 13) { 
		 btnClick(); 
		 event.returnValue = false;
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

 <font color="#4B0082">学期:
	   <select name="xq" id="xq">     
	       <option selected="selected" value="00">全学期统计</option> 
         
         <?php 
            while($xq=$xqs->fetch()){
  			  echo '<option value=' . $xq[0] . '>' . $xq[0] . '</option>';
 		    }
        ?> 	
         </select>
  <font color="#4B0082">学院:</font>
	   <select id="college" name="college">      
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
    <font color="#4B0082">学生:</font>
	<select name="stuName" id="stuName">       
	    <option value="00">未选择学生</option>  
    </select>
    <br/>
    <br/>
    <div style="float:right;width:55px;height:30px;">
    </div>
    <div style="float:right;">
    <input type="button"  style="border-style:solid;border-width:1px;border-color:#388BFF;width:60px;height:30px;margin-left:20px;margin-right:20px;font-size:16px;" id="Drawbtn" onclick="DrawbtnClick()" value="统计图"  > 
    </div>	
    <div style="float:right;">
    <input type="button"  style="border-style:solid;border-width:1px;border-color:#388BFF;width:85px;height:30px;font-size:16px" value="导出Excel" name="exclbutton" id="exclbutton" onclick="exclbtnClick()">     
	</div>
    <div style="float:right;">
    <input type="button"  style="border-style:solid;border-width:1px;border-color:#388BFF;width:50px;height:30px;margin-left:10px;margin-right:20px;font-size:16px;" id="btnClk"  onclick="btnClick()" value="查询"  > 
    </div>	
    
<br/>

<div id="divtable">
	
</div>

<br/>

<div id="divexcl">
	
</div>

<br/>
