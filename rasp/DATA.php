<?php 
/*
$resp = system('top -n 1');
print $resp;
echo $resp;


//USO DE LA MEMORIA POR PHP
ini_set('memory_limit', '1M');
$x = '';
while(true) {
  echo "not real: ".(memory_get_peak_usage(false)/1024/1024)." MiB\n";
  echo "real: ".(memory_get_peak_usage(true)/1024/1024)." MiB\n\n";
  $x .= str_repeat(' ', 1024*25); //store 25kb more to string
}

echo "Memory Usage: " . (memory_get_usage()/1048576) . " MB \n";

$output = shell_exec('top -n 1');
echo "<pre>$output</pre>";

// Example use of getenv()
$ip = getenv('REMOTE_ADDR');

// Or simply use a Superglobal ($_SERVER or $_ENV)
$ip = $_SERVER['REMOTE_ADDR'];

// Safely get the value of an environment variable, ignoring whether 
// or not it was set by a SAPI or has been changed with putenv
$ip = getenv('REMOTE_ADDR', true) ?: getenv('REMOTE_ADDR');

echo $ip;

$output = shell_exec('top -n 1');
echo "<pre>$output</pre>";

exec('top -n 1',$output);
var_dump($output);
*/

$load = sys_getloadavg();
if ($load[0] > 0.80) {
    header('HTTP/1.1 503 Too busy, try again later');
    //die('Server too busy. Please try again later.');
}
echo "LOAD AVERAGE: ";
echo $load[0]." - ";
echo $load[1]." - ";
echo $load[2]."<br>";

function shapeSpace_system_load($coreCount = 4, $interval = 0) {
	$rs = sys_getloadavg();
	$interval = $interval >= 1 && 3 <= $interval ? $interval : 1;
	$load = $rs[$interval];
	return round(($load * 100) / $coreCount,2);
}

function shapeSpace_system_cores() {
	
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

function shapeSpace_http_connections() {
	
	if (function_exists('exec')) {
		$www_unique_count = 0;
		$www_total_count = 0;
		$unique = array();
		@exec ('netstat -an | egrep \':80|:443\' | awk \'{print $5}\' | grep -v \':::\*\' |  grep -v \'0.0.0.0\'', $results);
		
		foreach ($results as $result) {
			$array = explode(':', $result);
			$www_total_count ++;
			
			if (preg_match('/^::/', $result)) {
				$ipaddr = $array[3];
			} else {
				$ipaddr = $array[0];
			}
			
			if (!in_array($ipaddr, $unique)) {
				$unique[] = $ipaddr;
				$www_unique_count ++;
			}
		}
		
		unset ($results);
		
		return count($unique);
		
	}
}


function shapeSpace_server_memory_usage() {
 
	$free = shell_exec('free');
	$free = (string)trim($free);
	$free_arr = explode("\n", $free);
	$mem = explode(" ", $free_arr[1]);
	$mem = array_filter($mem);
	$mem = array_merge($mem);
	$memory_usage = round($mem[2] / $mem[1] * 100, 2);
 
	return $memory_usage;
}

function shapeSpace_disk_usage() {
	
	$disktotal = disk_total_space ('/');
	$diskfree  = disk_free_space  ('/');
	$diskuse   = round (100 - (($diskfree / $disktotal) * 100)) .'%';
	
	return $diskuse;
}

function shapeSpace_kernel_version() {
	
	$kernel = explode(' ', file_get_contents('/proc/version'));
	$kernel = $kernel[2];
	
	return $kernel;
}

function shapeSpace_number_processes() {
	
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

function shapeSpace_memory_usage() {
	
	$mem = memory_get_usage(true);
	
	if ($mem < 1024) {
		
		$$memory = $mem .' B'; 
		
	} elseif ($mem < 1048576) {
		
		$memory = round($mem / 1024, 2) .' KB';
		
	} else {
		
		$memory = round($mem / 1048576, 2) .' MB';
		
	}
	
	return $memory;	
}

function shapeSpace_disk_usage_speed() {
	$read = array();
	$write = array();
    $cmd = "uname";
    $OS = strtolower(trim(shell_exec($cmd)));
 
    switch($OS) {
       case('linux'):
          $cmd = 'sudo iotop -botqqk --iter=1 > /var/www/Raspinf';
          break;
       default:
          unset($cmd);
    }

    shell_exec($cmd);
    $kernel = explode('K/s', file_get_contents('/var/www/Raspinf'));

	$read = explode(': ',$kernel[0]);//LINEA CON EL READ
	$write = explode(': ',$kernel[1]);//LINEA CON EL WRITE

	echo "READ DISK: ".$read[1]."<br>";
	echo "WRITE DISK: ".$write[1]."<br>";
}

function shapeSpace_server_uptime (){
	$str   = file_get_contents('/proc/uptime');
	$num   = floatval($str);
	$secs  = round(fmod($num, 60), 0); 
	$num = round($num/60, 2);
	$mins  = $num % 60;
	$num = round($num/60, 2);
	$hours = $num % 24;
	$num = round($num/24, 0);
	$days  = $num;

	return $days." ".$hours.":".$mins.":".$secs;
}

echo "LOAD USE: ".shapeSpace_system_load()."<br>";
echo "No Cores: ".shapeSpace_system_cores()."<br>";
echo "SERVER MEMORY USE: ".shapeSpace_server_memory_usage()."<br>";
echo "HTTP CONNECTIONS: ".shapeSpace_http_connections()."<br>";
echo "DISK USAGE: ".shapeSpace_disk_usage()."<br>";
echo "KERNEL: ".shapeSpace_kernel_version()."<br>";
echo "PROCESSES: ".shapeSpace_number_processes()."<br>";
echo "MEMORY USAGE: ".shapeSpace_memory_usage()."<br>";
echo "UPTIME: ".shapeSpace_server_uptime()."<br>";

shapeSpace_disk_usage_speed();
?>