<?php

	function bootLaravel()
	{
		require 'bootstrap/autoload.php';
		$app = require_once 'bootstrap/start.php';
		$app->boot();
	}

	function getEloquentClasses()
	{
		$dir = 'app/models/';
		$classes = array();
		foreach(scandir($dir) as $file)
		{
			if(($file == '.') || ($file == '..'))
				continue;

			$className = ucfirst(basename($file, ".php"));
			if(!class_exists($className))
				continue;

			$class = new ReflectionClass($className);
			$object = $class->newInstance();

			if(!is_a($object, 'Eloquent'))
				continue;

			$classes[] = $className;
		}
		return $classes;
	}

	function getAttributes($className)
	{
		$attrs = array();
		try
		{
			$result = $className::take(1)->get()->toArray()[0];
			foreach($result as $key => $value)
			{
				$attrs[] = $key;
			}
		}
		catch(Illuminate\Database\QueryException $ex) {}
		return $attrs;
	}



	/*
	|--------------------------------------------------------------------------
	| Main
	|--------------------------------------------------------------------------
	|
	| Print all classes extending `Eloquent` and their properties and relations
	|
	*/

	bootLaravel();
	foreach(getEloquentClasses() as $className)
	{
		echo $className."\n";
		foreach(getAttributes($className) as $attr)
		{
			echo "  - $attr\n";
		}
		echo "\n";
	}

?>
