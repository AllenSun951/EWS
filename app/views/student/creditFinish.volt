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
  
</script> 

<script type="text/javascript">
	$.ajax({
           		type:'post',
           		url:'xfckbutton',
            	data:'',
            	success:function(data){
            	   $("#divstu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });
</script>  

<script type="text/javascript">   
   function cfbtnClick(){
   var mxq=$('#xq').val();
		 if (mxq=="00"){
   	     	
   	     	$.ajax({
           		type:'post',
           		url:'xfckbutton',
            	data:'',
            	success:function(data){
            	   $("#divstu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });
          }
         else{
             	$.ajax({
           		type:'post',
           		url:'xnqxfckbutton',
				data:'user='+mxq,
            	success:function(data){
            	   $("#divstu").html(data);
            	},
            	dataType:'html',
            	error:function(){
           	    }
             });
          
          }
            
	}
	//注销          
function logoutBtnClick(){
    document.getElementById("xq").style.display="none";
	document.getElementById("cfbtn").style.display="none";
	$.ajax({
            	type:'post',
            	url:'logoutBtn',
            	data:'',
            	success:function(data){
            	   $("#divstu").html(data);
            	},
            	dataType:'html',
            	error:function(){
            	}
            });
  }
 $(document).ready(function(){ 
	$("select").bind("keyup",function(){ 
		if (event.keyCode == 13) { 
		 cfbtnClick(); 
		 event.returnValue = false;
	 }	
	}); 
});
</script>
<br/>
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
<div id="divstu">	
</div>
<br/>