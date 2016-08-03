<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
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
         			 url:'xxckbutton',
          			 data:'',
          				success:function(data){
           				$("#divtable").html(data);
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
              var mxy=$('#xy').val();
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
    	var mbj=$('#grade').val();
 		// 学期控制 年级
 		if (mxq=="00") //学期变动为 未选择状态时，年级初始化 ， 学期为未选择状态，要以年级来确定班级
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
 //-----------------------------------按钮-------------------------------
	
//---------------------------------------------------------------------------
//查询//
 function btnClick(){
 var mnj=$('#nj').val();
 //未选择年级的情况
 if (mnj=="00"){
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
  	 		else{
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
//选择年级的情况
else{
	var mxq=$('#xq').val();
   	if (mxq=="00"){
   	var mxy=$('#college').val();
   	if (mxy=="00"){
	  	 $.ajax({
          	 type:'post',
          	 url:'njxxckbutton',
            	data:'xx='+mnj,
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
          	 url:'njxyckbutton',
            	data:'xy='+mnj+mxy,
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
           			url:'njzyckbutton',
            		data:'zy='+mnj+mzy,
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
           				url:'njbjckbutton',
            			data:'bj='+mnj+mbj,
            			success:function(data){
            	 	  	$("#divtable").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
           	 });
   			}else
   				{ // 年级确定，学生 也就一个，学生不要判定？
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
          	 	url:'njxnxxckbutton',
            	data:'xnq='+mnj+mxq,
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
          	 		 url:'njxnxyckbutton',
            	 	 data:'xnq='+mnj+mxq+mxnxy,
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
           				url:'njxnzyckbutton',
            			data:'xnq='+mnj+mxq+mxnzy,
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
           					url:'njxnbjckbutton',
            				data:'xnq='+mnj+mxq+mxnbj,
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
}
</script>

<script type="text/javascript">
//---------------------------------------------------------------------------
//导出 excel
function exclbtnClick(){
var mnj=$('#nj').val();
if (mnj=="00"){
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
else{//带年级    的情况
	var mxq=$('#xq').val();
    if (mxq=="00"){
   		var mxy=$('#college').val();
   		if (mxy=="00"){
            window.open('njxxexcel?xx='+mnj);
   		}
   		else
   		{
   			var mzy=$('#major').val();
   			if(mzy=="00"){
   		  	 window.open('njxyexcel?xy='+mnj+mxy);	
   			}else
   			{
   				 var mbj=$('#grade').val();
   				if(mbj=="00"){
   		  	 window.open('njzyexcel?zy='+mnj+mzy);	
   			}else
   				{
   					var mstu=$('#stuName').val();
   					if(mstu=="00"){
   					window.open('njbjexcel?bj='+mnj+mbj);	
   			}else
   				{
   					window.open('njxsexcel?xs='+mnj+mstu);	
   			    }	
   		     }
  	      }
       }
    }
	else
    {  //带学期
      var mxnxy=$('#college').val();
		if (mxnxy=="00"){
	    	window.open('njxnqxxexcel?xnq='+mnj+mxq);	
   		   }
  	 		else {
				   var mxnzy=$('#major').val();
   					if(mxnzy=="00"){
   		  			window.open('njxnqxyexcel?xnq='+mnj+mxq+mxnxy);		
   				 }
   				 else
   					{
   					 var mxnbj=$('#grade').val();
   					   if(mxnbj=="00"){
   						window.open('njxnqzyexcel?xnq='+mnj+mxq+mxnzy);	
   					  }
   					  else
   							{
   							var mxnstu=$('#stuName').val();
   							if(mxnstu=="00"){
   							window.open('njxnqbjexcel?xnq='+mnj+mxq+mxnbj);
   						}	else
   								{
   								window.open('njxnqxsexcel?xnq='+mnj+mxq+mxnstu);
   			  				    }		
   		    		}
  	      		} 
          }	 	
     }
  }
}
//---------------------------------------------------------------------------
// 画图//
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
          	 url:'xyChart',
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
           			url:'zyChart',
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
   					$.ajax({
          		 		type:'post',
           				url:'bjChart',
            			data:'bj='+mbj,
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
			if (mxnxy=="00"){
	  	 	$.ajax({
          	 	type:'post',
          	 	url:'xnqxxChart',
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
          	 		 url:'xnqxyChart',
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
           				url:'xnqzyChart',
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
   							$.ajax({
          		 			type:'post',
           					url:'xnqbjChart',
            				data:'xnq='+mxq+mxnbj,
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
          	 url:'njxyChart',
            	data:'xy='+mnj+mxy,
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
           			url:'njzyChart',
            		data:'zy='+mnj+mzy,
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
           				url:'njbjChart',
            			data:'bj='+mnj+mbj,
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
			if (mxnxy=="00"){
	  		 $.ajax({
          	 	type:'post',
          	 	url:'njxnqxxChart',
            	data:'xnq='+mnj+mxq,
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
          	 		 url:'njxnqxyChart',
            	 	 data:'xnq='+mnj+mxq+mxnxy,
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
           				url:'njxnqzyChart',
            			data:'xnq='+mnj+mxq+mxnzy,
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
           					url:'njxnqbjChart',
            				data:'xnq='+mnj+mxq+mxnbj,
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
	   <select name="xq" id="xq">     
	       <option selected="selected" value="00">未选择学期</option> 
         <?php 
            while($xq=$xqs->fetch()){
  			  echo '<option value=' . $xq[0] . '>' . $xq[0] . '</option>';
 		    }
        ?> 	
         </select>
  <font color="#4B0082">学院:</font>
	   <select id="college" name="college">      
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
