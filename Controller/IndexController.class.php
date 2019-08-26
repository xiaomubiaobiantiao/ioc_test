<?php
namespace Controller;

use Interfaces\ModelInterface;

class IndexController
{

	public function __construct( ModelInterface $aaaaa ) {
		dump( $aaaaa->connection() );
	}

	public function connection() {
		
	}
	// public function __construct() {
	// 	echo 123;
	// }

	// public function connection() {

	// }

}