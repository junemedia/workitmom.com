<?php 

/**
 *	Vardump utilities
 *
 *	Var_dumps all arguments and returns first argument.
 */
function v() {
	$args = func_get_args();
	foreach ($args as $arg) {
		var_dump($arg);
	}
	return reset($args);
}

/**
 *	Var_dumps all arguments then dies.
 */
function vd() {
	$args = func_get_args();
	foreach ($args as $arg) {
		var_dump($arg);
	}
	exit;
}

?>