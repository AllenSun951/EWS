<?php

use Phalcon\Mvc\Model;

class StudyAlert extends Model{
	
	public function getSource(){
		return "MY_XYJS";
	}
	public function initialize(){
		$this->setSource("MY_XYJS");
	}
	
	
	
	
}