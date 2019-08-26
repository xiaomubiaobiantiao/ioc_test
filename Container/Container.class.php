<?php

namespace Container;
use ReflectionClass;

class Container
{


	public $bindings = array();
	public $associated = '';

	public function bind( $abstract, $concrete = null ) {

		if ( is_array( $concrete )) {
			$this->bindings[$abstract][$concrete[0]][$concrete[1]] = $concrete[1];
		} else {
			$this->bindings[$abstract][$concrete] = $concrete;
		}

	}

	//建立实例
	public function make( $concrete, $associated = null, $params = null ) {

		$alias = $this->bindAlias();
		switch ( func_num_args() ) {
			case 1:
				if ( $alias[$concrete] )
					$concrete = $alias[$concrete];
				break;
			case 2:
				if ( $alias[$concrete] && $alias[$associated] )
					list( $concrete, $associated ) = array( $alias[$concrete], $alias[$associated]);
				break;
		}

		// dump( $concrete );
		// dump( $associated );
		// die();
		//别名系统
		$alias = $this->bindAlias();
		if ( $alias[$concrete] )
			$concrete = $alias[$concrete];

		//返回实例
		return $this->getInstance( $concrete, $associated, $params );

	}

	public function getInstance( $className, $associated = null, $params = null ) {

		$reflecter = new ReflectionClass( $className );
		
		$constructor = $reflecter->getConstructor();
		
		$instance = array();

		if ( false == $constructor && false == $reflecter->isInterface() )
			return $reflecter->newInstanceArgs( $instance );

		$parameters = $constructor->getParameters();


		// 测试三
		foreach ( $parameters as $param ) {

			$class = $param->getClass();
			$paramName = $param->getClass()->name;
			
			//类存在并且不是接口的时候执行
			if ( $class && false == interface_exists( $paramName )) {
				$instance[] = $this->getInstance( $class->name );
			} else {
				$instance[] = $this->make( $this->bindings[$paramName][$associated] );
			}


		}

		// 测试二
		// foreach ( $parameters as $param ) {
		// 	$paramName = $param->getClass()->name;
		// 	if ( interface_exists( $paramName ) && $this->bindings[$className][$paramName] ) {
		// 		$instance[] = $this->getInstance( $this->bindings[$paramName][$associated] );
		// 	} else {
		// 		$class = $param->getClass();
		// 		if ( $class )
		// 			$instance[] = $this->getInstance( $class->name );
		// 	}
		// }


		// 测试一
		// foreach ( $parameters as $param ) {

			// $alias = $this->bindAlias();
			// if ( $alias[$param->name] )
			// 	$paramName = $alias[$param->name];

			// if ( interface_exists( $paramName ) ) {

			// 	if ( $this->bindings[$className][$paramName] )
			// 		$paramName = $this->bindings[$paramName][$associated];

			// 	$instance[] = $this->getInstance( $paramName );
			// 	dump( $instance );

			// } else {
			// 	$class = $param->getClass();
			// 	if ( $class )
			// 		$instance[] = $this->getInstance( $class->name );
			// }
		// }

		
		
		return $reflecter->newInstanceArgs( $instance );

		// $this->dump( $reflecter );
		// $this->dump( $constructor );
		// $this->dump( $parameters );
	}

	// 绑定别名
	public function bindAlias() {
		return array(
			'IndexController' => 'Controller\IndexController',
			'ModelInterface' => 'Interfaces\ModelInterface',
			'TestModel' => 'Model\TestModel',
		);
	}

	public function getBinds() {
		return $this->bindings;
	}


}