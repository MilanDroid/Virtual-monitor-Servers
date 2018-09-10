<?php
	$datos = array();

	switch($_POST['tipo']) {
	    case('disk_io'):
			diskIO();
	    break;
	    case('ram_server'):
			serverMemory_usage();
	    break;
	    case('cpu_load'):
			systemLoad();
	    break;
	    case('bd_conn'):
			bdConnections();
	    break;
	    case('net_conn'):
			networkConnections();
	    break;
	    case('stat_inf'):
			statInf();
	    break;
	    default:
	        unset($cmd);
	}

	function systemCores() {
		
	    $cmd = "uname";
	    $OS = strtolower(trim(shell_exec($cmd)));
	 
	    switch($OS) {
	       case('linux'):
	          $cmd = "cat /proc/cpuinfo | grep processor | wc -l";
	          break;
	       case('freebsd'):
	          $cmd = "sysctl -a | grep 'hw.ncpu' | cut -d ':' -f2";
	          break;
	       default:
	          unset($cmd);
	    }
	 
	    if ($cmd != '') {
	       $cpuCoreNo = intval(trim(shell_exec($cmd)));
	    }
	    
	    return empty($cpuCoreNo) ? 1 : $cpuCoreNo;
	}

	function numberProcesses() {
		
		$proc_count = 0;
		$dh = opendir('/proc');
		
		while ($dir = readdir($dh)) {
			if (is_dir('/proc/' . $dir)) {
				if (preg_match('/^[0-9]+$/', $dir)) {
					$proc_count ++;
				}
			}
		}
		
		return $proc_count;	
	}

	function serverUptime (){
		$str   = file_get_contents('/proc/uptime');
		$num   = floatval($str);
		$secs  = round(fmod($num, 60), 0); 
		$num = round($num/60, 2);
		$mins  = $num % 60;
		$num = round($num/60, 2);
		$hours = $num % 24;
		$num = round($num/24, 0);
		$days  = $num;

		return $days." Dias, ".$hours." hr:".$mins." min:".$secs." seg";
	}

	function memoryUsage() {
		
		$mem = memory_get_usage(true);
		
		if ($mem < 1024) {
			
			$memory = $mem .' B'; 
			
		} elseif ($mem < 1048576) {
			
			$memory = round($mem / 1024, 2) .' KB';
			
		} else {
			
			$memory = round($mem / 1048576, 2) .' MB';
			
		}
		
		return $memory;	
	}

	function cpuLoad() {
		$load = sys_getloadavg();

		/*if ($load[0] > 0.80) {
		    header('HTTP/1.1 503 Too busy, try again later');
		    //die('Server too busy. Please try again later.');
		}*/

		return $load[0]." - ".$load[1]." - ".$load[2];
	}

	function diskUsage() {
		
		$disktotal = disk_total_space ('/');
		$diskfree  = disk_free_space  ('/');
		$diskuse   = round (100 - (($diskfree / $disktotal) * 100)) .'%';
		
		return $diskuse;
	}

	function statInf() {
		$datos['disk'] = diskUsage();
		$datos['load'] = cpuLoad();
		$datos['memory'] = memoryUsage();
		$datos['time'] = serverUptime();

		echo json_encode($datos); 
	}

	function networkConnections() {
		
		if (function_exists('exec')) {
			$unique = array();
			$tmp = array();
			@exec ("sudo netstat -plan| awk {'print $5'} | cut -d: -f 1 | sort | uniq -c | sort -n", $results);
			
			foreach ($results as $result) {				
				if (!in_array($result, $unique)) {
					$tmp = trim($result);
					$tmp = explode(" ", $tmp);
					$unique[] = end($tmp);
				}
			}

			$datos['net_conn'] = count($unique);
			$datos['inf'] = $unique;

			echo json_encode($datos);			
		}
	}

	function bdConnections(){
		//PARA UTILIZAR LA CLASE CONEXION CAMBIAR EL NOMBRE A 'conexion.php' Y ASEGURARSE DE HABER CAMBIADO LAS CREDENCIASLES EN LA CLAS
		include "../class/conexion_work.php";
		$conexion = new Conexion();
		$data = "";

		$sql = "SELECT COUNT(*) AS cant FROM pg_stat_activity;";
		$result = pg_query($sql);
		$result = pg_fetch_array($result);

		$datos['bd_conn'] = $result['cant'];

		$sqlInf = "SELECT pid, usename, application_name, client_addr,
		client_port, backend_start, state, query
		FROM pg_stat_activity;";		
		$result = pg_query($sqlInf);
		while($row = pg_fetch_array($result)){
			$data .= $row['pid']." - ".$row['usename']." - ".$row['application_name']." - ".$row['client_addr']." - ".$row['client_port'];
			$data .= " - ".$row['backend_start']." - ".$row['state']."\n\n".$row['query']."\n\n";
		}

		$datos['bd_inf'] = $data;

		$conexion->closeConnection();
		echo json_encode($datos);
	}

	function systemLoad() {
		$cpu = shell_exec("top -bn 2 -d 0.01 | grep '^%Cpu'");
		$cpu = explode("\n", $cpu);
		$cpu = explode(",", trim($cpu[1]));
		$cpu = explode(" ", trim($cpu[3]));
		$datos['load'] = 100 - $cpu[0];
		unset($cpu);

		echo json_encode($datos);
	}

	function serverMemory_usage() {
	 
		$free = shell_exec('free');
		$free = (string)trim($free);
		$free_arr = explode("\n", $free);
		$mem = explode(" ", $free_arr[1]);
		$mem = array_filter($mem);
		$mem = array_merge($mem);
		$memory_usage = round($mem[2] / $mem[1] * 100, 2);
	 
		$datos['memory'] = $memory_usage;
		unset($memory_usage, $mem);

		echo json_encode($datos);
	}

	function diskIO() {

	    $cmd = "uname";
		$read = array();
		$write = array();
	    $OS = strtolower(trim(shell_exec($cmd)));
	 
	    switch($OS)
	    {
	       case('linux'):
	          $cmd = 'sudo iotop -botqqk --iter=1';
	          //COMO ENSENANZA, ESTA LINEA DE CODIGO SIRVE PARA ENVIAR LO OBTENDO POR CONSOLA A UN ARCHIVO DE TEXTO
	          //$cmd = 'sudo iotop -botqqk --iter=1 > /var/www/Raspinf';
	          break;
	       default:
	          unset($cmd);
	    }

	    //PARA UTILIZAR LA ESCRITURA Y LECTURA DE UN ARCHIVO SE DEBE DE CONFIGURAR LA SIGUIENTE LINEA DEJANDO SOLO EL SHELL
	    //ASI: shell_exec($cmd);
	    $cmd = shell_exec($cmd);
	    $data = explode('K/s', $cmd);
	    //ESTA LINEA DE CODIGO SIRVE PARA LEER EL ARCHIVO DE TEXTO CREADO ANTERIORMENTE
	    //$data = explode('K/s', file_get_contents('/var/www/Raspinf'));

		$read = explode(':',$data[0]);//LINEA CON EL READ
		$write = explode(':',$data[1]);//LINEA CON EL WRITE

		$datos['d_read'] = trim($read[1]);
		$datos['d_write'] = trim($write[1]);
		unset($data, $read, $write);

		echo json_encode($datos);
	}
?>