<?php
require_once 'Conexion.php';

class Estados
{
	private $datos = null;

	public function __construct()
	{
		$this->datos = array();
	}

	public function selection($selection)
	{
		switch ($selection) {
			case 'diskIo':
				return $this->diskStats();
			break;
			case 'ram_server':
				return $this->memoryStats();
			break;
			case 'cpu_load':
				return $this->system();
			break;
			case 'net_conn':
				return $this->network();
			break;
			case 'bd_conn':
				return $this->database();
			break;
			case 'stat_inf':
				return $this->stats();
			break;
		}
	}

    private function uptime()
    {
        $time = array();
		$str   = file_get_contents('/proc/uptime');
		$num   = floatval($str);
		$time[]  = round(fmod($num, 60), 0); 
		$num = round($num/60, 2);
		$time[]  = $num % 60;
		$num = round($num/60, 2);
		$time[] = $num % 24;
		$num = round($num/24, 0);
		$time[]  = $num;

		return $time;
    }

    private function memory()
    {
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

    private function cpu()
    {
    	$load = sys_getloadavg();
		return $load;
        
    }

    private function disk()
    {
    	$disktotal = disk_total_space ('/');
		$diskfree  = disk_free_space  ('/');
		$diskuse   = round (100 - (($diskfree / $disktotal) * 100)) .'%';
		
		return $diskuse;        
    }

    private function stats()
    {
    	$this->datos['disk'] = $this->disk();
		$this->datos['load'] = $this->cpu();
		$this->datos['memory'] = $this->memory();
		$this->datos['time'] = $this->uptime();

		return $this->datos;        
    }

    private function network()
    {
        if (function_exists('exec')) {
			$tmp = array();
			$current_ip = "";
			$ip = "";
			$sum = 0;

			@exec ("sudo netstat -anp |grep 'ESTABLISHED' | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -n", $results);
			foreach ($results as $result) {
				$tmp = trim($result);
				$tmp = explode(" ", $tmp);
				$current_ip = end($tmp);
				$ip .= "<tr><td>".$tmp[0]."</td><td align='right'>".$current_ip."</td></tr>";
				$sum += $tmp[0];
			}

			$this->datos['net_conn'] = count($results);
			$this->datos['inf'] = $ip;
			$this->datos['sum'] = $sum;

			return $this->datos;
		}
    }

    private function database()
    {
		$conexion = new Conexion();
		
		$data = "<tr><td>PID</td><td>USENAME</td><td>APPNAME</td><td>CLIEND_ADDR</td><td>CLIENT_PORT</td><td>TIME_START</td><td>QUERY</td></tr>";

		$sql = "SELECT COUNT(*) AS cant FROM pg_stat_activity;";
		$result = pg_query($sql);
		$result = pg_fetch_array($result);

		$this->datos['bd_conn'] = $result['cant'];

		//PARA EXTRAER EL TIEMPO QUE LLEVA LEVANTADO EL QUERY
		$sqlInf = "SELECT pid AS pid, usename, application_name, client_addr,
		client_port, backend_start, query AS query
		FROM pg_stat_activity
		ORDER BY backend_start DESC;";		
		$result = pg_query($sqlInf) or die(pg_last_error());
		while($row = pg_fetch_array($result)){
			$data .= "<tr><td>".$row['pid']."</td><td>".$row['usename']."</td><td>".$row['application_name']."</td><td>".$row['client_addr']."</td><td>".$row['client_port']."</td><td>".$row['backend_start']."</td><td class='query-column'>".$row['query']."</td></tr>";
		}

		$this->datos['bd_inf'] = $data;

		$conexion->closeConnection();
		return $this->datos;       
    }

    private function system()
    {
        //REPLACE %Cpu FOR Cpu(s)
		$cpu = shell_exec("top -bn 2 -d 0.01 | grep '^%Cpu'");
		$cpu = explode("\n", $cpu);
		$cpu = explode(",", trim($cpu[1]));
		$cpu = explode(" ", trim($cpu[3]));
		$this->datos['load'] = 100 - $cpu[0];
		unset($cpu);

		return $this->datos;
    }

    private function memoryStats()
    {
        $free = shell_exec('free');
		$free = (string)trim($free);
		$free_arr = explode("\n", $free);
		$mem = explode(" ", $free_arr[1]);
		$mem = array_filter($mem);
		$mem = array_merge($mem);
		$memory_usage = round($mem[2] / $mem[1] * 100, 2);
	 
		$this->datos['memory'] = $memory_usage;
		unset($memory_usage, $mem);

		return $this->datos;
    }

    private function diskStats()
    {
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
	    $cmd = shell_exec($cmd);
	    $data = explode('K/s', $cmd);

		$read = explode(':',$data[0]);//LINEA CON EL READ
		$write = explode(':',$data[1]);//LINEA CON EL WRITE

		$this->datos['d_read'] = trim($read[3]);
		$this->datos['d_write'] = trim($write[1]);
		unset($data, $read, $write);

		return $this->datos;
    }
}