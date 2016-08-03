<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
<script src="../js/chart/Chart.js"></script>
<script src="../js/highcharts.js"></script>
<script src="../js/exporting.js"></script>
<script type="text/javascript">
						$.ajax({
            					type:'post',
            					url:'nj',
            					data:'',
            					success:function(data){
            	  				 $("#nj").html(data);
            						},
            					dataType:'html',
            					error:function(){
            					}
            			        }); 
            $.ajax({
            	type:'post',
            	url:'xxchart',
            	data:'',
            	success:function(data){
            	   $("#divtu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });

        //-------------------------初始化 控件时候，连带 初始化 其余控件数值---------------------
	$(document).ready(function(){
         $("#college").change(function(){
        	 var mxy=$(this).children('option:selected').val();
         	 $.ajax({
            	type:'post',
            	url:'major',
            	data:'xy='+mxy,
            	success:function(data){
            	   $("#major").html(data);
            		},
            		dataType:'html',
            		error:function(){
            		}
           		 });
        	 });
         	
             $("#major").change(function(){
             var mzy=$(this).children('option:selected').val();
             //学期 控制 班级，判断 班级选？专业+学期 一起控制班级的。
              var mxq=$('#xq').val(); // 获取学期数值
              var mnj=$('#nj').val();
              if(mxq=="00" && mnj=="00"){
             			$.ajax({
            				type:'post',
            				url:'grade',
            				data:'zy='+mzy,
            				success:function(data){
            	  			 $("#grade").html(data);
            				},
            				dataType:'html',
            				error:function(){
            				}
             			 });
         			}
         			else
         			{
         				if (mnj=="00"){ 
         					$.ajax({
            				type:'post',
            				url:'xqgrade',
            				data:'xqzy='+mxq+mzy,
            				success:function(data){
            	  			 $("#grade").html(data);
            				},
            				dataType:'html',
            				error:function(){
            				}
             			 });	
             			 }
             			 else{
             			 	if(mxq=="00"){
             			 		$.ajax({
            				type:'post',
            				url:'njgrade',
            				data:'njzy='+mnj+mzy,
            				success:function(data){
            	  			 $("#grade").html(data);
            				},
            				dataType:'html',
            				error:function(){
            				}
             			   });	
             			 	}else{ // 年级学期 专业 控制班级
             			 		 	$.ajax({
            						type:'post',
            						url:'njxqzygrade',
            						data:'njxqzy='+mnj+mxq+mzy,
            						success:function(data){
            	  					 $("#grade").html(data);
            						},
            						dataType:'html',
            						error:function(){
            					}
             				 });
             			 	}
             			 } 	
         			}
         	});
         //===== 班级 只控制学期，不控制年级，班级变动为未选择状态时，初始化学期
          $("#grade").change(function(){         
            var mbj=$(this).children('option:selected').val();
            var mxq=$('#xq').val();
            var mnj=$('#nj').val(); // 获取学期数值
            if (mbj=="00"){
            	if (mnj=="00")  
            	{        	
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
           	    	else{ // 只通过 年级 控制学期。
           	    		 $.ajax({
            	    	 type:'post',
            	    	 url:'njxq',
            			 data:'njxq='+mnj,
            			 success:function(data){
            	  		 $("#xq").html(data);
            				},
            				dataType:'html',
            				error:function(){
            				}
           	       		}); 
           	    	}
            	}
            else{ // 固定学年期数值
            		if (mxq=="00"){
            		$.ajax({
            		type:'post',
            		url:'bjxnq',
            		data:'bjxnq='+mbj,
            		success:function(data){
            	  	 $("#xq").html(data);
            			},
            			dataType:'html',
            			error:function(){
            	 	}
           	   	 });
            	}
            	}
            // 无论何种情况，直接初始化 学生
            $.ajax({
            	type:'post',
            	url:'student',
            	data:'bj='+mbj,
            	success:function(data){
            	   $("#stuName").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });
          });

   		// 学期 选择  控制年级 ,学期+专业控制 班级
       $("#xq").change(function(){
    	var mxq=$(this).children('option:selected').val();//先获取 学期 数值
    	var mnj=$('#nj').val(); // 获取学期数值
    	var mzy=$('#major').val(); // 获取专业数值，专业如果不为空，学期顺便控制 班级。
 		// 学期控制 年级
 		if (mxq=="00") //学期变动为 未选择状态时，年级初始化
 			{
 			        if(mnj=="00"){
 								$.ajax({
            					type:'post',
            					url:'nj',
            					data:'',
            					success:function(data){
            	  				 $("#nj").html(data);
            						},
            					dataType:'html',
            					error:function(){
            					}
            			        }); 
            			        }
 			        if(mzy=="00"){ 
 			              }
 			         	  else{ // 专业不为空，直接初始化班级
 			         		if(mnj=="00")
 			         	  {
 			         		 $.ajax({
            				type:'post',
            				url:'grade',
            				data:'zy='+mzy,
            				success:function(data){
            	  			 $("#grade").html(data);
            				},
            				dataType:'html',
            				error:function(){
            				}
             			  });
             			  	}
             			  	else{ // 学期为未选择状态，要以年级来确定班级 	
             			  			$.ajax({
            						type:'post',
            							url:'gradenj',
            							data:'zynj='+mnj+mzy,
            							success:function(data){
            	  					 $("#grade").html(data);
            							},
            							dataType:'html',
            							error:function(){
            						}
             			 		});
             			  	}
             			 }
 			// 学期选择为：未选择状态，就不控制年级。
           	}
           else
            {
              if(mnj=="00"){
           	  $.ajax({
            	type:'post',
            	url:'xqnj',
            	data:'xqnj='+mxq,
            	success:function(data){
            	   $("#nj").html(data);
            		},
            		dataType:'html',
            		error:function(){
            		}
           		 });}
           
           	
				if (mzy!="00")
					{
						if (mbj=="00"){
							if(mnj=="00"){
							$.ajax({
            				type:'post',
            				url:'xqgrade',
            				data:'xqzy='+mxq+mzy,
            				success:function(data){
            	  			 $("#grade").html(data);
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
            				url:'njxqzygrade',
            				data:'njxqzy='+mnj+mxq+mzy,
            				success:function(data){
            	  			 $("#grade").html(data);
            				},
            				dataType:'html',
            				error:function(){
            				}
             				 });
             				 }
             			 }	
					}  
			}  	
	  });
	
 		// 年级 选择  控制学期 ,年级+专业控制 班级
 	   $("#nj").change(function(){
    	 var mnj=$(this).children('option:selected').val();//先获取 年级  数值
    	  var mxq=$('#xq').val(); // 获取学期数值
    	   var mzy=$('#major').val(); // 获取专业数值，专业如果不为空，年级初始化 班级。
 		// 年级动了，但是未选择。
 		if (mnj=="00"){
 			// 年级选择为：未选择状态，学期初始化 。
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
 		 			 if(mzy=="00"){
 		 			
 		 			 }
 			          else{ //专业不为空，直接初始化班级
 			         		 $.ajax({
            				type:'post',
            				url:'grade',
            				data:'zy='+mzy,
            				success:function(data){
            	  			 $("#grade").html(data);
            				},
            				dataType:'html',
            				error:function(){
            				}
             			 });
             			 }
 			  }
 		     else{
 		 		 		 $.ajax({
            	    	 type:'post',
            	    	 url:'njxq',
            			 data:'njxq='+mnj,
            			 success:function(data){
            	  		 $("#xq").html(data);
            				},
            				dataType:'html',
            				error:function(){
            				}
           	       		}); 
           	 // 控制班级：
           	 if(mzy=="00"){
 		 			//专业为空，年级直接初始化 学期

 		 		   }
 			       else{ //专业不为空，直接初始化班级
 			         		$.ajax({
            				type:'post',
            				url:'gradenj',
            				data:'zynj='+mnj+mzy,
            				success:function(data){
            	  			 $("#grade").html(data);
            				},
            				dataType:'html',
            				error:function(){
            				}
             			 });
             			 } 
 		   }
 		 });
	});
	</script>

	<script type="text/javascript">
	//数量统计-	
	function DrawbtnClick(){
	 var mnj=$('#nj').val();
 	//未选择年级的情况
  	 if (mnj=="00"){
 		var mxq=$('#xq').val();
   		if (mxq=="00"){
   		var mxy=$('#college').val();
  	 if (mxy=="00"){
	  	 $.ajax({
          	 type:'post',
          	 url:'xxChart',
            	data:'',
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
   			if(mzy=="00"){
   		  	 $.ajax({
          	 type:'post',
          	 url:'xyChart',
            	data:'xy='+mxy,
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
   				if(mbj=="00"){
   				$.ajax({
           			type:'post',
           			url:'zyChart',
            		data:'zy='+mzy,
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
            			data:'bj='+mbj,
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
			if (mxnxy=="00"){
	  	 	$.ajax({
          	 	type:'post',
          	 	url:'xnqxxChart',
            	data:'xnq='+mxq,
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
   					if(mxnzy=="00"){
   		  			 $.ajax({
          		 	 type:'post',
          	 		 url:'xnqxyChart',
            	 	 data:'xnq='+mxq+mxnxy,
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
   					   if(mxnbj=="00"){
   						$.ajax({
           				type:'post',
           				url:'xnqzyChart',
            			data:'xnq='+mxq+mxnzy,
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
            				data:'xnq='+mxq+mxnbj,
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
//选择年级的情况
else{
    var mxq=$('#xq').val();
   	if (mxq=="00"){
   	var mxy=$('#college').val();
   	if (mxy=="00"){
	  	 $.ajax({
          	 type:'post',
          	 url:'njxxChart',
            	data:'nj='+mnj,
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
   			if(mzy=="00"){
   		  	 $.ajax({
          	 type:'post',
          	 url:'njxyChart',
            	data:'xy='+mnj+mxy,
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
   				if(mbj=="00"){
   				$.ajax({
           			type:'post',
           			url:'njzyChart',
            		data:'zy='+mnj+mzy,
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
           				url:'njbjChart',
            			data:'bj='+mnj+mbj,
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
			if (mxnxy=="00"){
	  		 $.ajax({
          	 	type:'post',
          	 	url:'njxnqxxChart',
            	data:'xnq='+mnj+mxq,
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
   					if(mxnzy=="00"){
   		  			 $.ajax({
          		 	 type:'post',
          	 		 url:'njxnqxyChart',
            	 	 data:'xnq='+mnj+mxq+mxnxy,
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
   					   if(mxnbj=="00"){
   						$.ajax({
           				type:'post',
           				url:'njxnqzyChart',
            			data:'xnq='+mnj+mxq+mxnzy,
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
           					url:'njxnqbjChart',
            				data:'xnq='+mnj+mxq+mxnbj,
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
  }	
/////////////////////////////  
//比例统计图--
function BDrawbtnClick(){
 var mnj=$('#nj').val();
 //未选择年级的情况
   if (mnj=="00"){
 	var mxq=$('#xq').val();
   	if (mxq=="00"){
   	var mxy=$('#college').val();
   if (mxy=="00"){
	  	 $.ajax({
          	 type:'post',
          	 url:'BxxChart',
            	data:'',
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
   			if(mzy=="00"){
   		  	 $.ajax({
          	 type:'post',
          	 url:'BxyChart',
            	data:'xy='+mxy,
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
   				if(mbj=="00"){
   				$.ajax({
           			type:'post',
           			url:'BzyChart',
            		data:'zy='+mzy,
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
           				url:'BbjChart',
            			data:'bj='+mbj,
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
			if (mxnxy=="00"){
	  	 	$.ajax({
          	 	type:'post',
          	 	url:'BxnqxxChart',
            	data:'xnq='+mxq,
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
   					if(mxnzy=="00"){
   		  			 $.ajax({
          		 	 type:'post',
          	 		 url:'BxnqxyChart',
            	 	 data:'xnq='+mxq+mxnxy,
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
   					   if(mxnbj=="00"){
   						$.ajax({
           				type:'post',
           				url:'BxnqzyChart',
            			data:'xnq='+mxq+mxnzy,
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
           					url:'BxnqbjChart',
            				data:'xnq='+mxq+mxnbj,
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
//选择年级的情况
else{
    var mxq=$('#xq').val();
   	if (mxq=="00"){
   	var mxy=$('#college').val();
   	if (mxy=="00"){
	  	 $.ajax({
          	 type:'post',
          	 url:'BnjxxChart',
            	data:'nj='+mnj,
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
   			if(mzy=="00"){
   		  	 $.ajax({
          	 type:'post',
          	 url:'BnjxyChart',
            	data:'xy='+mnj+mxy,
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
   				if(mbj=="00"){
   				$.ajax({
           			type:'post',
           			url:'BnjzyChart',
            		data:'zy='+mnj+mzy,
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
           				url:'BnjbjChart',
            			data:'bj='+mnj+mbj,
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
			if (mxnxy=="00"){
	  		 $.ajax({
          	 	type:'post',
          	 	url:'BnjxnqxxChart',
            	data:'xnq='+mnj+mxq,
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
   					if(mxnzy=="00"){
   		  			 $.ajax({
          		 	 type:'post',
          	 		 url:'BnjxnqxyChart',
            	 	 data:'xnq='+mnj+mxq+mxnxy,
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
   					   if(mxnbj=="00"){
   						$.ajax({
           				type:'post',
           				url:'BnjxnqzyChart',
            			data:'xnq='+mnj+mxq+mxnzy,
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
           					url:'BnjxnqbjChart',
            				data:'xnq='+mnj+mxq+mxnbj,
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
  }	
//////////////////
//注销          
function logoutBtnClick(){
    document.getElementById("xq").style.display="none";
	document.getElementById("college").style.display="none";
	document.getElementById("major").style.display="none";
	document.getElementById("grade").style.display="none";
	document.getElementById("Drawbtn").style.display="none";
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
   <font color="#4B0082">年级:</font>
	   <select name="nj" id="nj" >     
	      <option selected="selected" value="00">未选择年级</option> 
         <?php 
            while($mnj=$njs->fetch()){
  			  echo '<option value=' . $mnj[0] . '>' . $mnj[0] . '</option>';
 		    }
        ?> 
        </select>
   <font color="#4B0082">学期:</font>
	   <select name="xq" id="xq" >     
	       <option selected="selected" value="00">未选择学期</option> 
         
           <?php 
            while($xq=$xqs->fetch()){
  			  echo '<option value=' . $xq[0] . '>' . $xq[0] . '</option>';
 		    }
          ?> 	
        </select>
  <font color="#4B0082">学院:</font>
	   <select id="college" name="college" >      
	    <option value="00">未选择学院</option>  
	    <?php 
            while($mxy=$xys->fetch()){
  			  echo '<option value=' . $mxy[0] . '>' . $mxy[0] . '</option>';
 		    }
        ?>  
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
    <input type="button"  style="border-style:solid;border-width:1px;border-color:#388BFF;border-style:solid;border-width:1px;border-color:#388BFF;background:#BCD2EE;width:80px;height:30px;margin-left:10px;margin-right:10px;font-size:15px;" id="Drawbtn" onclick="DrawbtnClick()" value="数量统计"  > 
    <input type="button"  style="border-style:solid;border-width:1px;border-color:#388BFF;border-style:solid;border-width:1px;border-color:#388BFF;background:#BCD2EE;width:80px;height:30px;margin-left:10px;margin-right:80px;font-size:15px;" id="Drawbtn" onclick="BDrawbtnClick()" value="比例统计"  > 
    </div>	
<br/>
<br/>
<div id="divtu">
	 
</div>