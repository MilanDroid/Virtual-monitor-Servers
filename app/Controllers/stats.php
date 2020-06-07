<?php
require_once '../Models/Estados.php';

$tipos = [
	'diskIo',
	'ram_server',
	'cpu_load',
	'net_conn',
	'bd_conn',
	'stat_inf',
];

if(in_array($_POST['tipo'], $tipos))
{
	$estados = new Estados();
	$datos = $estados->selection($_POST['tipo']);

	echo json_encode($datos);
}