<?php
namespace Model;

use Interfaces\ModelInterface;

class TestModel implements ModelInterface
{

	public function connection() {
		echo 'connect sqlserver success!';
	}



}