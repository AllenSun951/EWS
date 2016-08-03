<?php
//session_start();
include '/../../app/library/PHPExcel.php';

class ExcelController extends ControllerBase
{
	public function indexAction()
	{  
	}
	/**
	 *不计算学期
	 *
	 */
	///学校
	public function xxexcelAction()
	{   
		//$x=$this->request->get("mychart");
		$conn =$this->getDI()->get("db");
		$sql="select XY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS) from XUEXIAO where XY is not null group by xy order by xy";
		$result = $conn->query($sql);
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle( '全校警示结果统计');
	
		// 设置默认字体和大小
		$letter = array('A','B','C','D','E','F');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:F1');      //合并
		$ObjPhp->getActiveSheet()->setCellValue('A1','全校警示结果');
		$tableheader = array('学院','I级警示','II级警示','III级警示','警示总人数','总人数');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		// $a 表示每列的 统计情况。
		$a=array("总计",0,0,0,0,0);
		$j = 3;
		while($usr=$result->fetch())
		{
	
			for($i = 0; $i <count($tableheader); $i++){
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");  //用这个函数进行赋值时，会自动将学号和专业前面的0去掉
				//将数字字符转换成字符串输出    避免了上述情况
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
					
			}
			$j++;
			$a[1]+=$usr[1];$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];
	
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
				
		}
			
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:F2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
	
		$ObjPhp->getActiveSheet()->getStyle('A3:F'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(21);
	
		//设置类型及格式   直接在浏览器中输出
		$time = time(); //按时间保存
		$x="全校警示结果";
		$x=iconv("utf-8", "gb2312", $x);
		$filename=$x;
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp,'Excel5');
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$x.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		exit;
	}
	
	///学院
	public function xyexcelAction()
	{
		$x=$this->request->get("xy");
		//$x="计算机科学与工程学院";
		$conn =$this->getDI()->get("db");
		$sql="select XY,ZY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS) from XUEYUAN where xy like '%" . $x ."%' group by XY,ZY order by zy";
			$result = $conn->query($sql);
	
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($x."警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F','G');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:G1');      //合并
	
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$x.'警示结果');
		$tableheader = array('学院','专业','I级警示','II级警示','III级警示','警示总人数','总人数');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
		$a=array("总计",0,0,0,0,0,0);
		$j = 3;
		while($usr=$result->fetch())
		{
			for($i = 0; $i <count($tableheader); $i++){
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");//用这个函数进行赋值时，会自动将学号和专业前面的0去掉
				//将数字字符转换成字符串输出    避免了上述情况
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
					
			}
			$j++;
			$a[1]+=1;$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
		}
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:G2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A3:G'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(45);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(21);
		//设置类型及格式   直接在浏览器中输出
		$time = time(); //按时间保存。
		$x=iconv("utf-8", "gb2312", $x);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp,'Excel5');
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$x.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		exit;
	}
	
	//专业
	public function zyexcelAction()
	{
			
		$x=$this->request->get("zy");
		$conn =$this->getDI()->get("db");
		//$sql="select XH,XM,BJ,JSJB,JSYY,HDXF,NJ from MY_XYJS where zy='" . $x ."'";
		$sql="select BJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from ZHUANYE where zy = '".$x."' group by BJ order by bj";
			$result = $conn->query($sql);
	
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($x."警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:F1');      //合并
	
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$x.'专业 警示结果');
		$tableheader = array('班级','I级警示','II级警示','III级警示','警示总人数','总人数');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		$a=array("总计",0,0,0,0,0);
		$j = 3;
		while($usr=$result->fetch())
		{
	
			for($i = 0; $i <count($tableheader); $i++){
	
				//将数字字符转换成字符串输出    避免了上述情况
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
					
			}
			$j++;
			$a[1]+=$usr[1];$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];
	
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
	
		}
	
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:F2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
	
		$ObjPhp->getActiveSheet()->getStyle('A3:F'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(45);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(21);
	
		//设置类型及格式   直接在浏览器中输出
		/* 	header('Content-Type: application/vnd.ms-excel');
			header('Cache-Control: max-age=0'); */
		$time = time(); //按时间保存。
		$x=iconv("utf-8", "gb2312", $x);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$x.$time.".xls");//直接输出到浏览器
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$x.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save("D:/download/".$x.$time.".xls");//直接输出到浏览器
		//echo "<script>alert('专业警示统计结果保存成功！')</script>";
		exit;
	
	}
	
	///班级：
	public function bjexcelAction()
	{
			
		$x=$this->request->get("bj");
		$conn =$this->getDI()->get("db");
	
		$sql="select XY,ZY,XH,XM,JSJB,JSYY,HDXF,NJ from MY_XYJS where bj like '%" . $x ."%'order by xh";
		$result = $conn->query($sql);
		$sql1="select count (distinct xh) from MY_XYJS   where bj='" . $x ."'";
		$number = $conn->query($sql1);
		$numb=$number->fetch();
	
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($x."班级 警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F','G','H');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:H1');      //合并
	
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$x.'班级  警示结果');
		$tableheader = array('学院','专业','学号','姓名','警示级别','警示原因','获得学分','年级');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		$a=array("总计","-",0,"-",0,"-","-","-");
		$j = 3;
		while($usr=$result->fetch())
		{
	
			for($i = 0; $i <count($tableheader); $i++){
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
					
			}
			$j++;
			$a[2]+=1;
			if($usr[4]!="无"){
				$a[4]+=1;
			}
	
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
	
		}
	
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:H2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A3:H'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(45);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(21);
		//设置类型及格式   直接在浏览器中输出
		/* 	header('Content-Type: application/vnd.ms-excel');
			header('Cache-Control: max-age=0'); */
		$time = time(); //按时间保存。
		$x=$x."班级";
		$x=iconv("utf-8", "gb2312", $x);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$x.$time.".xls");//直接输出到浏览器
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$x.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save("D:/download/".$x.$time.".xls");//直接输出到浏览器
		//echo "<script>alert('班级警示统计结果保存成功！')</script>";
		exit;
	
	}
	
	///学生：
	public function xsexcelAction()
	{
		//学生
		$x=$this->request->get("xs");
		//$x="钱蕊";
		$conn =$this->getDI()->get("db");
		$sql="select XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGXF,HDXF,NJ from MY_XYJS where XH like '%" . $x ."%'order by xm";
		//$sql1="select  count(*) from MY_XYJS where XM='" . $x ."'";
		$result = $conn->query($sql);
	
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($x."学生警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F','G','H','I');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:H1');      //合并
		//表头数组
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$x.'学生警示结果');
		$tableheader = array('学院','专业','班级','学号','姓名','警示级别','警示原因','总门数','总学分',
				'补考门数','补考学分','重修门数','重修学分','不及格门数','不及格学分','获得学分','还差学分','入学年级');//表头数组
		//学生个人信息表属性较长，分成两行输出
		for($i = 0;$i < count($tableheader)/2;$i++) { //填充表头信息第 一 行
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		for($y = 0;$y < count($tableheader)/2;$y++) { //填充表头信息第 2 行
				
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$y]5","$tableheader[$i]");
			$i++;
		}
	
		$j = 3;
		while($usr=$result->fetch())
		{
			$HCXF=$usr[8]-$usr[15]; //还差学分;
			//表中第3行数据，从 usr中截取一半
			for($i = 0; $i <count($tableheader)/2; $i++){
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
			}
			$j=6; //表中j!=4,第4 行是空一行 ...表示插入表中第6行
			//表中第6行数据，从 usr中截取一半
			for($y = 0;$y < count($tableheader)/2-2;$y++) {
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$y]$j","$usr[$i]");
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$y]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
				$i++;
			}
			//还差学分的计算
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$y]$j","$HCXF");
			//表中最后一个数据，入学年级的填充
			$y=count($tableheader)/2-1;
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$y]$j","$usr[$i]");
	
		}
			
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:I2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
	
		$ObjPhp->getActiveSheet()->getStyle('A3:I'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A5:I5')->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(19);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('I')->setWidth(21);
		//设置类型及格式   直接在浏览器中输出
		/* header('Content-Type: application/vnd.ms-excel');
			header('Cache-Control: max-age=0'); */
		$time = time(); //按时间保存。
		$x=$x."学生";
		$x=iconv("utf-8", "gb2312", $x);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$x.$time.".xls");//直接输出到浏览器
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$x.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save("D:/download/".$x.$time.".xls");//直接输出到浏览器
		//echo "<script>alert('学生警示统计结果保存成功！')</script>";
		exit;
	
	}
	/**
	 * 加上 学年/学期 的 excel 导出操作
	 */
	///学校
	public function xnqxxexcelAction()
	{
		$x=$this->request->get("xnq");
		$conn =$this->getDI()->get("db");
		$sql="select  XNQ,XY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from XNQXUEXIAO  where xy is not null and xnq='" . $x ."' group by xnq,xy order by xy";
		$result = $conn->query($sql);
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($x .'学期全校警示结果统计');
		// 设置默认字体和大小
	
		$letter = array('A','B','C','D','E','F','G');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:G1');      //合并
		$ObjPhp->getActiveSheet()->setCellValue('A1','全校警示结果');
		$tableheader = array('学年/学期','学院','I级警示','II级警示','III级警示','警示总人数','总人数');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		// $a 表示每列的 统计情况。
		$a=array("总计","-",0,0,0,0,0);
		$j = 3;
		while($usr=$result->fetch())
		{
	
			for($i = 0; $i <count($tableheader); $i++){
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
					
			}
			$j++;
			$a[1]+=1;$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];
	
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
	
		}
			
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:G2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
	
		$ObjPhp->getActiveSheet()->getStyle('A3:G'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(21);
	
		//设置类型及格式   直接在浏览器中输出
		/* header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename=$x."学期全校警示.xls"');
		header('Cache-Control: max-age=0'); */
		//$ObjPhp->getActiveSheet()->setTitle(iconv('gbk', 'utf-8', 'phpexcel测试'));
		$time = time(); //按时间保存。
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$x."XueXiao".$time.".xls");//直接输出到浏览器
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$x."XueXiao".$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save("D:/download/".$x."XueXiao".$time.".xls");//直接输出到浏览器
		//echo "<script>alert('一学期全校警示统计结果保存成功！')</script>";
		exit;
	
	}
	
	///学院
	public function xnqxyexcelAction()
	{
		$str=$this->request->get("xnq");
		$x=substr($str,0,11);
		$y=substr($str,11);
		$conn =$this->getDI()->get("db");
		$sql="select  XNQ,XY,ZY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from XNQXUEYUAN where xnq='" . $x ."' and xy like '%" . $y ."%' group by XNQ,XY,ZY order by zy";
		$result = $conn->query($sql);
	
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($str."警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F','G','H');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:H1');      //合并
	
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$str.'警示结果');
		$tableheader = array('学年/学期','学院','专业','I级警示','II级警示','III级警示','警示总人数','总人数');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		$a=array("总计","-",0,0,0,0,0,0);
		$j = 3;
		while($usr=$result->fetch())
		{
	
			for($i = 0; $i <count($tableheader); $i++){
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
					
			}
			$j++;
			$a[2]+=1;$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];$a[7]+=$usr[7];
	
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
	
		}
	
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:H2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
	
		$ObjPhp->getActiveSheet()->getStyle('A3:H'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(45);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(16);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(16);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(16);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(16);
		$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(16);
		//设置类型及格式   直接在浏览器中输出
		/* header('Content-Type: application/vnd.ms-excel');
			header('Cache-Control: max-age=0'); */
		$time = time(); //按时间保存。
		$xnq=iconv("utf-8", "gb2312", $str);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$xnq.$time.".xls");//直接输出到浏览器
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$xnq.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save("D:/download/".$xnq.$time.".xls");//直接输出到浏览器
		//echo "<script>alert('一学期学院警示统计结果保存成功！')</script>";
		exit;
	
	}
	
	///专业
	public function xnqzyexcelAction()
	{
			
		$str=$this->request->get("xnq");
		$x=substr($str,0,11);
		$y=substr($str,11);
		$conn =$this->getDI()->get("db");
		$sql="select  XNQ,ZY,BJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from XNQZHUANYE where xnq='" . $x ."' and zy = '". $y ."' group by XNQ,ZY,BJ order by bj";
		$result = $conn->query($sql);
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($str."专业  警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F','G','H');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:H1');      //合并
	
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$str.'警示结果');
		$tableheader = array('学年/学期','专业','班级','I级警示','II级警示','III级警示','警示总人数','总人数');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		$a=array("总计","-",0,0,0,0,0,0);
		$j = 3;
		while($usr=$result->fetch())
		{
	
			for($i = 0; $i <count($tableheader); $i++){
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
			}
			$j++;
			$a[2]+=1;$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];$a[7]+=$usr[7];
	
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
	
		}
	
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:H2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
	
		$ObjPhp->getActiveSheet()->getStyle('A3:H'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(45);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(14);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(18);
	
		//设置类型及格式   直接在浏览器中输出
		/* 		header('Content-Type: application/vnd.ms-excel');
			header('Cache-Control: max-age=0'); */
		$time = time(); //按时间保存。
		$str=iconv("utf-8", "gb2312", $str);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$str.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
		//echo "<script>alert('一学期专业警示统计结果保存成功！')</script>";
		exit;
	
	}
	
	
	///班级：
	public function xnqbjexcelAction()
	{
			
		//学年学期
		$str=$this->request->get("xnq");
		$x=substr($str,0,11);
		$y=substr($str,11);
	
		//echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期'.$y.'警示结果</font></caption>';
		$conn =$this->getDI()->get("db");
		$sql="select  XNQ,ZY,BJ,XH,XM,JSJB,JSYY,NJ  from MY_XYJS2 where xnq='" . $x ."' and bj like '%" . $y ."%' order by xh";
		//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
		$result = $conn->query($sql);
	
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($str."警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F','G','H');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:H1');      //合并
	
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$str.'班级 警示结果');
		$tableheader = array('学年/学期','专业','班级','学号','姓名','警示级别','警示原因','年级');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		$a=array("总计","-","-",0,"-","-","-","-");
		$j = 3;
		while($usr=$result->fetch())
		{
	
			for($i = 0; $i <count($tableheader); $i++){
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
			}
			$j++;
			$a[3]+=1;
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
	
		}
	
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:H2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A3:H'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		//设置类型及格式   直接在浏览器中输出
		/* header('Content-Type: application/vnd.ms-excel');
			header('Cache-Control: max-age=0'); */
		$time = time(); //按时间保存。
		$str=$str."班级";
		$str=iconv("utf-8", "gb2312", $str);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$str.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
		//echo "<script>alert('一学期班级警示统计结果保存成功！')</script>";
		exit;
	
	}
	
	///学生：
	public function xnqxsexcelAction()
	{
		$str=$this->request->get("xnq");
		$x=substr($str,0,11);
		$y=substr($str,11);
		//echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期'.$y.'警示结果</font></caption>';
		$conn =$this->getDI()->get("db");
		$sql="select  XNQ,XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGMSBL,BJGXF,BJGXFBL,HDXF,NJ  from MY_XYJS2 where xnq='" . $x ."' and XH like '%" . $y ."%'";
		//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
		$result = $conn->query($sql);
	
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($str."学生警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F','G','H','I','J','K');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:K1');      //合并
		// 合并最后两个 单元格
		$ObjPhp->getActiveSheet()->mergeCells('J5:K5');
		$ObjPhp->getActiveSheet()->mergeCells('J6:K6');
	
		//表头数组
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$str.'学生警示结果');
		$tableheader = array('学年/学期','学院','专业','班级','学号','姓名','警示级别','警示原因','总门数','总学分','补考门数'
				,'补考学分','重修门数','重修学分','不及格门数','不及格门数比例','不及格学分','不及格学分比例','获得学分','还差学分','入学年级');//表头数组
		//学生个人信息表属性较长，分成两行输出
		for($i = 0;$i < ceil(count($tableheader)/2);$i++) { //填充表头信息第 一 行
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		for($y = 0;$y < intval(count($tableheader)/2);$y++) { //填充表头信息第 2 行
	
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$y]5","$tableheader[$i]");
			$i++;
		}
	
		$j = 3;
		while($usr=$result->fetch())
		{
			$HCXF=$usr[9]-$usr[18];//还差学分;
			//表中第3行数据，从 usr中截取一半
			for($i = 0; $i <ceil(count($tableheader)/2); $i++){  // 向上取整
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
			}
			$j=6; //表中j!=4,第4 行是空一行 ...表示插入表中第6行
			//表中第6行数据，从 usr中截取一半
			for($y = 0;$y < intval(count($tableheader)/2)-2;$y++) { // 向下取整
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$y]$j","$usr[$i]");
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$y]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
				$i++;
			}
			//还差学分的计算
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$y]$j","$HCXF");
			//表中最后一个数据，入学年级的填充
			$y=intval(count($tableheader)/2)-1;
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$y]$j","$usr[$i]");
	
		}
	
		// 表 格式设定
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:K2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A3:K'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A5:K5')->applyFromArray(   //第5行 放大  加粗
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('I')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		$ObjPhp->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		//设置类型及格式   直接在浏览器中输出
		/* 	header('Content-Type: application/vnd.ms-excel');
			header('Cache-Control: max-age=0'); */
		$time = time(); //按时间保存。
		$str=$str."学生";
		$str=iconv("utf-8", "gb2312", $str);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$str.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
		//echo "<script>alert('一学期学生警示统计结果保存成功！')</script>";
		exit;
	
	}
	
	
	//----------------------------------------------------------------------------------------------------------------------
	//---------------------------------- 年级          excel    导出的各种情况        -------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	///// 学校
	public function njxxexcelAction()
	{
		$nj=$this->request->get("xx");
		$conn =$this->getDI()->get("db");
		$sql="select XY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS) from XUEXIAO where NJ='" . $nj ."' and XY is not null group by xy order by xy";
		$result = $conn->query($sql);
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle( $nj.' 全校警示结果统计');
	
		// 设置默认字体和大小
		$letter = array('A','B','C','D','E','F');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:F1');      //合并
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$nj.' 全校警示结果');
		$tableheader = array('学院','I级警示','II级警示','III级警示','警示总人数','总人数');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		// $a 表示每列的 统计情况。
		$a=array("总计",0,0,0,0,0);
		$j = 3;
		while($usr=$result->fetch())
		{
	
			for($i = 0; $i <count($tableheader); $i++){
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");  //用这个函数进行赋值时，会自动将学号和专业前面的0去掉
				//将数字字符转换成字符串输出    避免了上述情况
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
					
			}
			$j++;
			$a[1]+=$usr[1];$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];
	
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
	
		}
			
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:F2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
	
		$ObjPhp->getActiveSheet()->getStyle('A3:F'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(21);
	
		//设置类型及格式   直接在浏览器中输出
		$time = time(); //按时间保存
		$x=$nj." 全校警示结果";
		$x=iconv("utf-8", "gb2312", $x);
		$filename=$x;
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp,'Excel5');
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$x.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		exit;
	}
	
	/// 学院
	public function njxyexcelAction()
	{
		$str=$this->request->get("xy");
		$nj=substr($str,0,8); //年级
		$x=substr($str,8);   //学院
		
		//$x="计算机科学与工程学院";
		$conn =$this->getDI()->get("db");
		$sql="select XY,ZY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS) from XUEYUAN where NJ='" . $nj ."' and xy like '%" . $x ."%' group by XY,ZY order by zy";
		$result = $conn->query($sql);
	
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($x."警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F','G');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:G1');      //合并
	
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$nj.' '.$x.'警示结果');
		$tableheader = array('学院','专业','I级警示','II级警示','III级警示','警示总人数','总人数');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
		$a=array("总计",0,0,0,0,0,0);
		$j = 3;
		while($usr=$result->fetch())
		{
			for($i = 0; $i <count($tableheader); $i++){
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");//用这个函数进行赋值时，会自动将学号和专业前面的0去掉
				//将数字字符转换成字符串输出    避免了上述情况
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
					
			}
			$j++;
			$a[1]+=1;$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
		}
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:G2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A3:G'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(45);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(21);
		//设置类型及格式   直接在浏览器中输出
		$time = time(); //按时间保存。
		$x=iconv("utf-8", "gb2312", $nj." ".$x);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp,'Excel5');
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$x.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		exit;
	}
	
	//专业
	public function njzyexcelAction()
	{
			
		$str=$this->request->get("zy");
		$nj=substr($str,0,8); //年级
		$x=substr($str,8);//专业
		$conn =$this->getDI()->get("db");
		//$sql="select XH,XM,BJ,JSJB,JSYY,HDXF,NJ from MY_XYJS where zy='" . $x ."'";
		$sql="select BJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from ZHUANYE where NJ='" . $nj ."' and zy = '".$x."' group by BJ order by bj";
		$result = $conn->query($sql);
	
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($x."警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:F1');      //合并
	
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$nj.' '.$x.'专业 警示结果');
		$tableheader = array('班级','I级警示','II级警示','III级警示','警示总人数','总人数');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		$a=array("总计",0,0,0,0,0);
		$j = 3;
		while($usr=$result->fetch())
		{
	
			for($i = 0; $i <count($tableheader); $i++){
	
				//将数字字符转换成字符串输出    避免了上述情况
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
					
			}
			$j++;
			$a[1]+=$usr[1];$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];
	
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
	
		}
	
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:F2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
	
		$ObjPhp->getActiveSheet()->getStyle('A3:F'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(45);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(21);
	
		//设置类型及格式   直接在浏览器中输出
		/* 	header('Content-Type: application/vnd.ms-excel');
		 header('Cache-Control: max-age=0'); */
		$time = time(); //按时间保存。
		$x=iconv("utf-8", "gb2312", $nj." ".$x);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$x.$time.".xls");//直接输出到浏览器
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$x.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save("D:/download/".$x.$time.".xls");//直接输出到浏览器
		//echo "<script>alert('专业警示统计结果保存成功！')</script>";
		exit;
	
	}
	
	///////班级：
	public function njbjexcelAction()
	{
			
		$str=$this->request->get("bj");
		$nj=substr($str,0,4); // 表中保存的 2012 不是 2012 级。
		$x=substr($str,8);  //班级
		
		$conn =$this->getDI()->get("db");
	
		$sql="select XY,ZY,XH,XM,JSJB,JSYY,HDXF,NJ from MY_XYJS where NJ='" . $nj ."' and bj like '%" . $x ."%'order by xh";
		$result = $conn->query($sql);
		$sql1="select count (distinct xh) from MY_XYJS   where bj='" . $x ."'";
		$number = $conn->query($sql1);
		$numb=$number->fetch();
	
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($x."警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F','G','H');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:H1');      //合并
	
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$nj.'级 '.$x.'班级 警示结果');
		$tableheader = array('学院','专业','学号','姓名','警示级别','警示原因','获得学分','年级');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		$a=array("总计","-",0,"-",0,"-","-","-");
		$j = 3;
		while($usr=$result->fetch())
		{
	
			for($i = 0; $i <count($tableheader); $i++){
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
					
			}
			$j++;
			$a[2]+=1;
			if($usr[4]!="无"){
				$a[4]+=1;
			}
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
	
		}
	
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:H2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A3:H'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(45);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(21);
		//设置类型及格式   直接在浏览器中输出
		/* 	header('Content-Type: application/vnd.ms-excel');
		 header('Cache-Control: max-age=0'); */
		$time = time(); //按时间保存。
		$x=$nj."级 ".$x."班级";
		$x=iconv("utf-8", "gb2312", $x);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$x.$time.".xls");//直接输出到浏览器
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$x.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save("D:/download/".$x.$time.".xls");//直接输出到浏览器
		//echo "<script>alert('班级警示统计结果保存成功！')</script>";
		exit;
	
	}
	
	///////学生：
	public function njxsexcelAction()
	{
		//学生
		$str=$this->request->get("xs");
		$nj=substr($str,0,4);//年级
		$x=substr($str,8);//学生
		$conn =$this->getDI()->get("db");
		$sql="select XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGXF,HDXF,NJ from MY_XYJS where NJ='" . $nj ."' and
		 XH like '%" . $x ."%'order by xm";
		$result = $conn->query($sql);
	
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($x."学生警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F','G','H','I');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:H1');      //合并
		//表头数组 
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$nj.' '.$x.'学生 警示结果');
		$tableheader = array('学院','专业','班级','学号','姓名','警示级别','警示原因','总门数','总学分',
				'补考门数','补考学分','重修门数','重修学分','不及格门数','不及格学分','获得学分','还差学分','入学年级');//表头数组
		//学生个人信息表属性较长，分成两行输出
		for($i = 0;$i < count($tableheader)/2;$i++) { //填充表头信息第 一 行
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		for($y = 0;$y < count($tableheader)/2;$y++) { //填充表头信息第 2 行
	
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$y]5","$tableheader[$i]");
			$i++;
		}
	
		$j = 3;
		while($usr=$result->fetch())
		{
			$HCXF=$usr[8]-$usr[15]; //还差学分;
			//表中第3行数据，从 usr中截取一半
			for($i = 0; $i <count($tableheader)/2; $i++){
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
			}
			$j=6; //表中j!=4,第4 行是空一行 ...表示插入表中第6行
			//表中第6行数据，从 usr中截取一半
			for($y = 0;$y < count($tableheader)/2-2;$y++) {
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$y]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
				$i++;
			}
			//还差学分的计算
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$y]$j","$HCXF");
			//表中最后一个数据，入学年级的填充
			$y=count($tableheader)/2-1;
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$y]$j","$usr[$i]");

		}
			
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:I2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
	
		$ObjPhp->getActiveSheet()->getStyle('A3:I'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		
		$ObjPhp->getActiveSheet()->getStyle('A5:I5')->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);

		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(19);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('I')->setWidth(21);
		//设置类型及格式   直接在浏览器中输出
		/* header('Content-Type: application/vnd.ms-excel');
		 header('Cache-Control: max-age=0'); */
		$time = time(); //按时间保存。
		$x=$str."学生";
		$x=iconv("utf-8", "gb2312", $x);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$x.$time.".xls");//直接输出到浏览器
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$x.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//echo "<script>alert('学生警示统计结果保存成功！')</script>";
		exit;
	
	}
	/**
	 * 加上 学年/学期 的 excel 导出操作
	 */
	///// 学校
	public function njxnqxxexcelAction()
	{
		$str=$this->request->get("xnq");
		$nj=substr($str,0,8);//年级
		$x=substr($str,8);//学期
		$conn =$this->getDI()->get("db");
		$sql="select  XNQ,XY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from XNQXUEXIAO  where NJ='" . $nj ."' and xy is not null and xnq='" . $x ."' group by xnq,xy order by xy";
		$result = $conn->query($sql);
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($nj." ".$x.'学期 全校警示结果统计');
		// 设置默认字体和大小
	
		$letter = array('A','B','C','D','E','F','G');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:G1');      //合并
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$nj.' '.$x.'学期  全校警示结果');
		$tableheader = array('学年/学期','学院','I级警示','II级警示','III级警示','警示总人数','总人数');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		// $a 表示每列的 统计情况。
		$a=array("总计","-",0,0,0,0,0);
		$j = 3;
		while($usr=$result->fetch())
		{
	
			for($i = 0; $i <count($tableheader); $i++){
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
					
			}
			$j++;
			$a[1]+=1;$a[2]+=$usr[2];$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];
	
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
	
		}
			
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:G2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
	
		$ObjPhp->getActiveSheet()->getStyle('A3:G'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(21);
	
		//设置类型及格式   直接在浏览器中输出
		/* header('Content-Type: application/vnd.ms-excel');
		 header('Content-Disposition: attachment;filename=$x."学期全校警示.xls"');
		header('Cache-Control: max-age=0'); */
		//$ObjPhp->getActiveSheet()->setTitle(iconv('gbk', 'utf-8', 'phpexcel测试'));
		$time = time(); //按时间保存。
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$x."XueXiao".$time.".xls");//直接输出到浏览器

		$x=$nj." ".$x."学期";
		$x=iconv("utf-8", "gb2312", $x);
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$x." 全校警示统计".$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save("D:/download/".$x."XueXiao".$time.".xls");//直接输出到浏览器
		//echo "<script>alert('一学期全校警示统计结果保存成功！')</script>";
		exit;
	
	}
	
	/// 学院
	public function njxnqxyexcelAction()
	{
		$str=$this->request->get("xnq");
		$nj=substr($str,0,8);//年级
		$x=substr($str,8,11);//学期
		$y=substr($str,19);//学院
		$conn =$this->getDI()->get("db");
		$sql="select  XNQ,XY,ZY,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from XNQXUEYUAN where NJ='" . $nj ."' and xnq='" . $x ."' and xy like '%" . $y ."%' group by XNQ,XY,ZY order by zy";
		$result = $conn->query($sql);
	
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($y." 警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F','G','H');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:H1');      //合并
	
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$nj.' '.$x.'学期 '.$y.' 警示结果');
		$tableheader = array('学年/学期','学院','专业','I级警示','II级警示','III级警示','警示总人数','总人数');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		$a=array("总计","-",0,0,0,0,0,0);
		$j = 3;
		while($usr=$result->fetch())
		{
	
			for($i = 0; $i <count($tableheader); $i++){
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
					
			}
			$j++;
			$a[2]+=1;$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];$a[7]+=$usr[7];
	
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
	
		}
	
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:H2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
	
		$ObjPhp->getActiveSheet()->getStyle('A3:H'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(45);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(16);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(16);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(16);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(16);
		$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(16);
		//设置类型及格式   直接在浏览器中输出
		/* header('Content-Type: application/vnd.ms-excel');
		 header('Cache-Control: max-age=0'); */
		$time = time(); //按时间保存。
		$str=$nj." ".$x."学期 ".$y;
		$xnq=iconv("utf-8", "gb2312", $str);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$xnq.$time.".xls");//直接输出到浏览器
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$xnq.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save("D:/download/".$xnq.$time.".xls");//直接输出到浏览器
		//echo "<script>alert('一学期学院警示统计结果保存成功！')</script>";
		exit;
	
	}
	
	//专业
	public function njxnqzyexcelAction()
	{
			
		$str=$this->request->get("xnq");
		$nj=substr($str,0,8);//年级
		$x=substr($str,8,11);//学期
		$y=substr($str,19);//专业
		$conn =$this->getDI()->get("db");
		$sql="select  XNQ,ZY,BJ,sum(IJS),sum(IIJS),sum(IIIJS),sum(JSZRS),sum(ZRS)  from XNQZHUANYE where NJ='" . $nj ."' and xnq='" . $x ."' and zy = '". $y ."' group by XNQ,ZY,BJ order by bj";
		$result = $conn->query($sql);
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($y."专业 警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F','G','H');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:H1');      //合并
	
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$nj.' '.$x.'学期  '.$y.'专业  警示结果');
		$tableheader = array('学年/学期','专业','班级','I级警示','II级警示','III级警示','警示总人数','总人数');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		$a=array("总计","-",0,0,0,0,0,0);
		$j = 3;
		while($usr=$result->fetch())
		{
	
			for($i = 0; $i <count($tableheader); $i++){
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
			}
			$j++;
			$a[2]+=1;$a[3]+=$usr[3];$a[4]+=$usr[4];$a[5]+=$usr[5];$a[6]+=$usr[6];$a[7]+=$usr[7];
	
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
	
		}
	
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:H2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
	
		$ObjPhp->getActiveSheet()->getStyle('A3:H'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(45);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(14);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(18);
	
		//设置类型及格式   直接在浏览器中输出
		/* 		header('Content-Type: application/vnd.ms-excel');
		 header('Cache-Control: max-age=0'); */
		$time = time(); //按时间保存。
		$str=iconv("utf-8", "gb2312", $str);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$str.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
		//echo "<script>alert('一学期专业警示统计结果保存成功！')</script>";
		exit;
	
	}
	
	
	///////班级：
	public function njxnqbjexcelAction()
	{
			
		//学年学期
		$str=$this->request->get("xnq");
		$nj=substr($str,0,4);//年级
		$x=substr($str,8,11);//学期
		$y=substr($str,19);//班级
				
	
		//echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期'.$y.'警示结果</font></caption>';
		$conn =$this->getDI()->get("db");
		$sql="select  XNQ,ZY,BJ,XH,XM,JSJB,JSYY,NJ  from MY_XYJS2 where NJ='" . $nj ."' and xnq='" . $x ."' and bj like '%" . $y ."%' order by xh";
		//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
		$result = $conn->query($sql);
	
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($y."警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F','G','H');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:H1');      //合并
	
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$nj.' '.$x.'学期 '.$y.'班级  警示结果');
		$tableheader = array('学年/学期','专业','班级','学号','姓名','警示级别','警示原因','年级');//表头数组
		for($i = 0;$i < count($tableheader);$i++) {//填充表头信息
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		$a=array("总计","-","-",0,"-",0,"-","-");
		$j = 3;
		while($usr=$result->fetch())
		{
	
			for($i = 0; $i <count($tableheader); $i++){
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
			}
			$j++;
			$a[3]+=1;
			if($usr[5]!="无"){
				$a[5]+=1;
			}
		}
			
		for($i = 0; $i < count($tableheader); $i++){
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$a[$i]");
	
		}
	
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:H2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A3:H'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A'.$j)->applyFromArray(   //总计加粗
				array(
						'font' => array (
								'bold' => true
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		//设置类型及格式   直接在浏览器中输出
		/* header('Content-Type: application/vnd.ms-excel');
		 header('Cache-Control: max-age=0'); */
		$time = time(); //按时间保存。
		$str=$str."班级";
		$str=iconv("utf-8", "gb2312", $str);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$str.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
		//echo "<script>alert('一学期班级警示统计结果保存成功！')</script>";
		exit;
	
	}
	
	///////学生：
	public function njxnqxsexcelAction()
	{
		$str=$this->request->get("xnq");
		$nj=substr($str,0,4);//年级
		$x=substr($str,8,11);//学期
		$y=substr($str,19);//学生
		//echo '<caption><font size= "5" face="微软雅黑" >'.$x.'学期'.$y.'警示结果</font></caption>';
		$conn =$this->getDI()->get("db");
		$sql="select  XNQ,XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGMSBL,BJGXF,BJGXFBL,HDXF,NJ  from MY_XYJS2 where NJ='" . $nj ."' and xnq='" . $x ."' and XH like '%" . $y ."%'";
		//$sql="select XY,IJS,IIJS,IIIJS,JSZRS,ZRS from XUEXIAO";
		$result = $conn->query($sql);
	
		$ObjPhp= new PHPExcel();
	
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		$ObjPhp->setActiveSheetIndex(0);
		$ObjPhp->getActiveSheet()->setTitle($y."学生警示结果");
	
		// 设置默认字体和大小
		$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
		$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
		$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$letter = array('A','B','C','D','E','F','G','H','I','J','K');//excle表格式  6列
		$ObjPhp->getActiveSheet()->mergeCells('A1:K1');      //合并
		// 合并最后两个 单元格
		$ObjPhp->getActiveSheet()->mergeCells('J5:K5');
		$ObjPhp->getActiveSheet()->mergeCells('J6:K6');
	
		//表头数组
		$ObjPhp->getActiveSheet()->setCellValue('A1',''.$x.'学期 '.$y.' 学生警示结果');
		$tableheader = array('学年/学期','学院','专业','班级','学号','姓名','警示级别','警示原因','总门数','总学分','补考门数'
				,'补考学分','重修门数','重修学分','不及格门数','不及格门数比例','不及格学分','不及格学分比例','获得学分','还差学分','入学年级');//表头数组
		//学生个人信息表属性较长，分成两行输出
		for($i = 0;$i < ceil(count($tableheader)/2);$i++) { //填充表头信息第 一 行
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
		}
	
		for($y = 0;$y < intval(count($tableheader)/2);$y++) { //填充表头信息第 2 行
	
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$y]5","$tableheader[$i]");
			$i++;
		}
	
		$j = 3;
		while($usr=$result->fetch())
		{
			$HCXF=$usr[9]-$usr[18];//还差学分;
			//表中第3行数据，从 usr中截取一半
			for($i = 0; $i <ceil(count($tableheader)/2); $i++){  // 向上取整
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
				//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
			}
			$j=6; //表中j!=4,第4 行是空一行 ...表示插入表中第6行
			//表中第6行数据，从 usr中截取一半
			for($y = 0;$y < intval(count($tableheader)/2)-2;$y++) { // 向下取整
				//$ObjPhp->getActiveSheet()->setCellValue("$letter[$y]$j","$usr[$i]");
				$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$y]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
				$i++;
			}
			//还差学分的计算
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$y]$j","$HCXF");
			//表中最后一个数据，入学年级的填充
			$y=intval(count($tableheader)/2)-1;
			$ObjPhp->getActiveSheet()->setCellValue("$letter[$y]$j","$usr[$i]");
	
		}
	
		// 表 格式设定
		$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 20
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
		$ObjPhp->getActiveSheet()->getStyle('A2:K2')->applyFromArray( //第一行：加粗，居中
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A3:K'.$j)->applyFromArray( //字体放大 居中
				array(
						'font' => array (
								'bold' => FALSE,
								'size' => 15
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
						)
				)
		);
	
		$ObjPhp->getActiveSheet()->getStyle('A5:K5')->applyFromArray(   //第5行 放大  加粗
				array(
						'font' => array (
								'bold' => true,
								'size' => 15
						)
				)
		);
		$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(21);
		$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('I')->setWidth(18);
		$ObjPhp->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		$ObjPhp->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		//设置类型及格式   直接在浏览器中输出
		/* 	header('Content-Type: application/vnd.ms-excel');
		 header('Cache-Control: max-age=0'); */
		$time = time(); //按时间保存。
		$str=$str."学生";
		$str=iconv("utf-8", "gb2312", $str);
		$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
		//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="'.$str.$time.'.xls"');
		header('Cache-Control:max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
		//echo "<script>alert('一学期学生警示统计结果保存成功！')</script>";
		exit;
	
	}
	
	
	
	//////管理员界面----高级查询----  未选择学期
	///////
	public function conditionexcelAction()
	{
		$str=$this->request->get("condition");
		$length=mb_strlen( $str,'utf-8' );
		$x=mb_substr($str,0,2,'utf-8');
		$y=mb_substr($str,2,$length-2,'utf-8');
		$conn =$this->getDI()->get("db");
	
		$ObjPhp= new PHPExcel();
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		if($x=="学号")
		{
			$sql="select XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGXF,HDXF,NJ from MY_XYJS where XH like '%" . $y ."%'order by xm";
			$result = $conn->query($sql);
			$ObjPhp->setActiveSheetIndex(0);
			$ObjPhp->getActiveSheet()->setTitle($str."学生警示结果");
	
			// 设置默认字体和大小
			$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
			$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
			$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$letter = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R');//excle表格式 18列
			$ObjPhp->getActiveSheet()->mergeCells('A1:R1');      //合并
				
			//表头数组
			$ObjPhp->getActiveSheet()->setCellValue('A1',''.$str.'学生警示结果');
			$tableheader = array('学院','专业','班级','学号','姓名','警示级别','警示原因','总门数','总学分','补考门数'
					,'补考学分','重修门数','重修学分','不及格门数','不及格学分','获得学分','还差学分','入学年级');//表头数组
			//学生个人信息  表头信息
			for($i = 0;$i < count($tableheader);$i++) { //填充表头信息第 一 行
				$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
			}
	
	
			$j = 3;
			while($usr=$result->fetch())
			{
				$HCXF=$usr[8]-$usr[15];//还差学分;
				//插入信息
				for($i = 0; $i <count($tableheader)-2; $i++){
					//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
					//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
					$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
				}
				//还差学分的计算
				$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$HCXF");
				//表中最后一个数据，入学年级的填充
				$i++;//i=17 表格中第17列
				$q=$i-1;//usr[16]放在表格中第i=17列
				$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$q]");
				$j++;
			}
			$j--;
			// 表 格式设定
			$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
					array(
							'font' => array (
									'bold' => true,
									'size' => 20
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
							)
					)
			);
			$ObjPhp->getActiveSheet()->getStyle('A2:R2')->applyFromArray( //第一行：加粗，居中
					array(
							'font' => array (
									'bold' => true,
									'size' => 15
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
							)
					)
			);
	
	
			$ObjPhp->getActiveSheet()->getStyle('A3:R'.$j)->applyFromArray( //字体放大 居中
					array(
							'font' => array (
									'bold' => FALSE,
									'size' => 15
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
							)
					)
			);
	
			$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(35);
			$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
			$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(21);
			$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('I')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('J')->setWidth(15);
			$ObjPhp->getActiveSheet()->getColumnDimension('K')->setWidth(15);
			//设置类型及格式   直接在浏览器中输出
			/* 	header('Content-Type: application/vnd.ms-excel');
				header('Cache-Control: max-age=0'); */
			$time = time(); //按时间保存。
			$str=$str."学生";
			$str=iconv("utf-8", "gb2312", $str);
			$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
			//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
			header('Content-Type:application/vnd.ms-excel');
			header('Content-Disposition:attachment;filename="'.$str.$time.'.xls"');
			header('Cache-Control:max-age=0');
			$objWriter->save('php://output');
			//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
			//echo "<script>alert('全学期学生警示统计结果保存成功！')</script>";
			exit;
		}
		else{
			$sql="select XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGXF,HDXF,NJ from MY_XYJS where XM like '%" . $y ."%'order by xh";
			//$sql1="select  count(*) from MY_XYJS where XM='" . $x ."'";
			$result = $conn->query($sql);
			$ObjPhp->setActiveSheetIndex(0);
			$ObjPhp->getActiveSheet()->setTitle($y."警示结果");
			// 设置默认字体和大小
			$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
			$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
			$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$letter = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R');//excle表格式  18列
			$ObjPhp->getActiveSheet()->mergeCells('A1:R1');      //合并
			//表头数组
			$ObjPhp->getActiveSheet()->setCellValue('A1',''.$y.'警示结果');
			$tableheader = array('学院','专业','班级','学号','姓名','警示级别','警示原因','总门数','总学分','补考门数'
					,'补考学分','重修门数','重修学分','不及格门数','不及格学分','获得学分','还差学分','入学年级');//表头数组
	
			for($i = 0;$i < count($tableheader);$i++) { //填充表头信息第 一 行
				$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
			}
	
			$j = 3;
			while($usr=$result->fetch())
			{
				$HCXF=$usr[8]-$usr[15];//还差学分;
				for($i = 0; $i <count($tableheader)-2; $i++){
					//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
					//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
					$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
				}
					
				//插入还差学分
				$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$HCXF");//还差学分放在第i=16列
				$i++;//i=17 表格中第17列
				$q=$i-1;//usr[16]放在表格中第i=17列
					
				$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$q]");
				$j++;
			}
			$j--;
			// 表 格式设定
			$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
					array(
							'font' => array (
									'bold' => true,
									'size' => 20
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
							)
					)
			);
			$ObjPhp->getActiveSheet()->getStyle('A2:R2')->applyFromArray( //第一行：加粗，居中
					array(
							'font' => array (
									'bold' => true,
									'size' => 15
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
							)
					)
			);
			$ObjPhp->getActiveSheet()->getStyle('A3:R'.$j)->applyFromArray( //字体放大 居中
					array(
							'font' => array (
									'bold' => FALSE,
									'size' => 15
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
							)
					)
			);
	
			$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(35);
			$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
			$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(21);
			$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('I')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('J')->setWidth(15);
			$ObjPhp->getActiveSheet()->getColumnDimension('K')->setWidth(15);
			//设置类型及格式   直接在浏览器中输出
			/* 			 header('Content-Type: application/vnd.ms-excel');
			 header('Cache-Control: max-age=0'); */
			$time = time(); //按时间保存。
			$y=$y."学生";
			$y=iconv("utf-8", "gb2312", $y);
			$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
			// $objWriter->save("D:/download/".$y.$time.".xls");//直接输出到浏览器
			header('Content-Type:application/vnd.ms-excel');
			header('Content-Disposition:attachment;filename="'.$y.$time.'.xls"');
			header('Cache-Control:max-age=0');
			$objWriter->save('php://output');
			//$objWriter->save("D:/download/".$y.$time.".xls");//直接输出到浏览器
			//echo "<script>alert('全学期学生警示统计结果保存成功！')</script>";
			exit;
		}
	
	}
	//////管理员界面----高级查询----  选择学期
	///////
	public function xnqconditionexcelAction()
	{
		$str=$this->request->get("condition");
		$length=mb_strlen( $str,'utf-8' );
		$x=mb_substr($str,0,11,'utf-8');
		$p=mb_substr($str,11,2,'utf-8');
		$y=mb_substr($str,13,$length-13,'utf-8');
	
		$conn =$this->getDI()->get("db");
	
		$ObjPhp= new PHPExcel();
		$ObjPhp->getProperties()->setCreator('Test') //创建人
		->setLastModifiedBy('t')   //最后修改人
		->setTitle('office2007')   //标题
		->setSubject('office 2007') //题目
		->setDescription('Testdd')  //描述
		->setKeywords('office 2007') //关键字
		->setCategory('test result');//种类
	
		if($p=="学号")
		{
			$sql="select  XNQ,XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGMSBL,BJGXF,BJGXFBL,HDXF,NJ  from MY_XYJS2 where xnq='" . $x ."' and XH like '%" . $y ."%'";
			$result = $conn->query($sql);
			$ObjPhp->setActiveSheetIndex(0);
			$ObjPhp->getActiveSheet()->setTitle($str."学生警示结果");
			// 设置默认字体和大小
			$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
			$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
			$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$letter = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U');//excle表格式  6列
			$ObjPhp->getActiveSheet()->mergeCells('A1:U1');      //合并
	
			//表头数组
			$ObjPhp->getActiveSheet()->setCellValue('A1',''.$str.'学生警示结果');
			$tableheader = array('学年/学期','学院','专业','班级','学号','姓名','警示级别','警示原因','总门数','总学分','补考门数'
					,'补考学分','重修门数','重修学分','不及格门数','不及格门数比例','不及格学分','不及格学分比例','获得学分','还差学分','入学年级');//表头数组
			//学生个人信息表属性较长，分成两行输出
			for($i = 0;$i < count($tableheader);$i++) { //填充表头信息第 一 行
				$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
			}
	
	
	
			$j = 3;
			while($usr=$result->fetch())
			{
				$HCXF=$usr[9]-$usr[18];//还差学分;
					
				for($i = 0; $i <count($tableheader)-2; $i++){
					//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
					//将数字字符转换成字符串输出    避免将学号和班级号前面的0去掉
					$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
				}
					
				//还差学分的计算
				$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$HCXF");
					
				$i++;//i=17 表格中第17列
				$q=$i-1;//usr[16]放在表格中第i=17列
				$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$q]");
				$j++;
			}
			$j--;
			// 表 格式设定
			$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
					array(
							'font' => array (
									'bold' => true,
									'size' => 20
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
							)
					)
			);
			$ObjPhp->getActiveSheet()->getStyle('A2:U2')->applyFromArray( //第一行：加粗，居中
					array(
							'font' => array (
									'bold' => true,
									'size' => 15
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
							)
					)
			);
	
	
			$ObjPhp->getActiveSheet()->getStyle('A3:U'.$j)->applyFromArray( //字体放大 居中
					array(
							'font' => array (
									'bold' => FALSE,
									'size' => 15
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
							)
					)
			);
	
			$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(35);
			$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
			$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(21);
			$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('I')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('J')->setWidth(15);
			$ObjPhp->getActiveSheet()->getColumnDimension('K')->setWidth(15);
			//设置类型及格式   直接在浏览器中输出
			/* header('Content-Type: application/vnd.ms-excel');
			 header('Cache-Control: max-age=0'); */
			$time = time(); //按时间保存。
			$str=$str."学生";
			$str=iconv("utf-8", "gb2312", $str);
			$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
			//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
			header('Content-Type:application/vnd.ms-excel');
			header('Content-Disposition:attachment;filename="'.$str.$time.'.xls"');
			header('Cache-Control:max-age=0');
			$objWriter->save('php://output');
			//$objWriter->save("D:/download/".$str.$time.".xls");//直接输出到浏览器
			//echo "<script>alert('一学期学生警示统计结果保存成功！')</script>";
			exit;
		}
		else{
			$sql="select XNQ,XY,ZY,BJ,XH,XM,JSJB,JSYY,ZMS,ZXF,BKMS,BKXF,CXMS,CXXF,BJGMS,BJGXF,HDXF,NJ from MY_XYJS2 where xnq='" . $x ."' and XM like '%" . $y ."%'order by xh";
			//$sql1="select  count(*) from MY_XYJS where XM='" . $x ."'";
			$result = $conn->query($sql);
			$ObjPhp->setActiveSheetIndex(0);
			$ObjPhp->getActiveSheet()->setTitle($y."警示结果");
			// 设置默认字体和大小
			$ObjPhp->getDefaultStyle()->getFont()->setName('宋体');
			$ObjPhp->getDefaultStyle()->getFont()->setSize(10);
			$ObjPhp->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$letter = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S');//excle表格式  18列
			$ObjPhp->getActiveSheet()->mergeCells('A1:S1');      //合并
			//表头数组
			$ObjPhp->getActiveSheet()->setCellValue('A1',''.$y.'警示结果');
			$tableheader = array('学年/学期','学院','专业','班级','学号','姓名','警示级别','警示原因','总门数','总学分','补考门数'
					,'补考学分','重修门数','重修学分','不及格门数','不及格学分','获得学分','还差学分','入学年级');//表头数组
	
			for($i = 0;$i < count($tableheader);$i++) { //填充表头信息第 一 行
				$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]2","$tableheader[$i]");
			}
	
			$j = 3;
			while($usr=$result->fetch())
			{
				$HCXF=$usr[9]-$usr[16];//还差学分;
				for($i = 0; $i <count($tableheader)-2; $i++){
					//$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$i]");
					$ObjPhp->getActiveSheet()->setCellValueExplicit("$letter[$i]$j","$usr[$i]",PHPExcel_Cell_DataType::TYPE_STRING);
				}
					
				//插入还差学分
				$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$HCXF");//还差学分放在第i=16列
				$i++;//i=17 表格中第17列
				$q=$i-1;//usr[16]放在表格中第i=17列
					
				$ObjPhp->getActiveSheet()->setCellValue("$letter[$i]$j","$usr[$q]");
				$j++;
			}
			$j--;
			// 表 格式设定
			$ObjPhp->getActiveSheet()->getStyle('A1')->applyFromArray( //表头  加粗 居中
					array(
							'font' => array (
									'bold' => true,
									'size' => 20
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
							)
					)
			);
			$ObjPhp->getActiveSheet()->getStyle('A2:S2')->applyFromArray( //第一行：加粗，居中
					array(
							'font' => array (
									'bold' => true,
									'size' => 15
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
							)
					)
			);
			$ObjPhp->getActiveSheet()->getStyle('A3:S'.$j)->applyFromArray( //字体放大 居中
					array(
							'font' => array (
									'bold' => FALSE,
									'size' => 15
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
							)
					)
			);
			$ObjPhp->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$ObjPhp->getActiveSheet()->getColumnDimension('B')->setWidth(35);
			$ObjPhp->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('D')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('E')->setWidth(21);
			$ObjPhp->getActiveSheet()->getColumnDimension('F')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('G')->setWidth(21);
			$ObjPhp->getActiveSheet()->getColumnDimension('H')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('I')->setWidth(18);
			$ObjPhp->getActiveSheet()->getColumnDimension('J')->setWidth(15);
			$ObjPhp->getActiveSheet()->getColumnDimension('K')->setWidth(15);
			//设置类型及格式   直接在浏览器中输出
			/* header('Content-Type: application/vnd.ms-excel');
				header('Cache-Control: max-age=0'); */
			$time = time(); //按时间保存。
			$y=$y."学生";
			$y=iconv("utf-8", "gb2312", $y);
			$objWriter = PHPExcel_IOFactory::createWriter($ObjPhp, 'Excel5');
			//$objWriter->save("D:/download/".$y.$time.".xls");//直接输出到浏览器
			header('Content-Type:application/vnd.ms-excel');
			header('Content-Disposition:attachment;filename="'.$y.$time.'.xls"');
			header('Cache-Control:max-age=0');
			$objWriter->save('php://output');
			//$objWriter->save("D:/download/".$y.$time.".xls");//直接输出到浏览器
			//echo "<script>alert('一学期学生警示统计结果保存成功！')</script>";
			exit;
		}
	
	}
	
}
