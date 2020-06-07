<?php
/**
* @author Be+ MilanDroid beplusdev@gmail.com
* @copyright 2019 Be+
* @license http://www.fsf.org/licensing/licenses/gpl.txt GPL 2 or later
* @version Alpha v0.0.2
* @link https://github.com/BePlusDevelopments/VIO
*/
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Virtual Monitor</title>
	<link rel="stylesheet" href="../resources/css/styles.css">
</head>
<body>
	<div class="container" align="center">
		<div class="row color-brand">
			<span>Virtual Monitor Be+</span>
		</div>
		<div>
			<br>
		</div>
		<div class="row">
			<div class="section" align="left">
				<div class="section-title p-15" align="center">
					<div>HDD<br><br></div>
					<div class="section-general-data">
						<span id="w_disk_io"></span>&emsp;
	  					<span id="r_disk_io"></span>
					</div>
				</div>
				<div class="stats-bar relative">
					<span id="w_disk_io-top" class="label-top"></span>
		  			<span id="w_disk_io-bot" class="label-bot"></span>
				</div>
				<canvas id="diskStats"></canvas>
			</div>
			<div class="section" align="left">
				<div class="section-title p-15" align="center">
					<div>RAM Memory<br><br></div>
					<div class="section-general-data">
						<span id="ram_act"></span>
					</div>
				</div>
				<div class="stats-bar relative">
					<span id="ram-top" class="label-top"></span>
		  			<span id="ram-bot" class="label-bot"></span>
				</div>
				<canvas id="ram"></canvas>
			</div>
			<div class="section" align="left">
				<div class="section-title p-15" align="center">
					<div>Cpu<br><br></div>
					<div class="section-general-data">
						<span id="cpu_act"></span>
					</div>
				</div>
				<div class="stats-bar relative">
					<span id="cpu-top" class="label-top"></span>
		  			<span id="cpu-bot" class="label-bot"></span>
				</div>
				<canvas id="cpu"></canvas>			
			</div>
		</div>
		<div class="row">
			<div class="section" align="left">
				<div class="section-title p-15" align="center">
	            	<div>Network<br><br></div>
	            	<div class="section-general-data">
	              		<span id="net_act"></span>
	            	</div>
	          	</div>
				<div class="stats-bar relative">
					<span id="net-top" class="label-top"></span>
		            <span id="net-bot" class="label-bot"></span>
				</div>
				<canvas id="server_conn"></canvas>				
			</div>
			<div class="section" align="left">
				<div class="section-title p-15" align="center">
		            <div>Postgres Connections<br><br></div>
		            <div class="section-general-data">
		              	<span id="bd_act"></span>
		            </div>
		         </div>
				<div class="stats-bar relative">					
	            	<span id="bd-top" class="label-top"></span>
	              	<span id="bd-bot" class="label-bot"></span>
				</div>
				<canvas id="bd_conn"></canvas>			
			</div>
			<div class="section" align="right">
				<table class="table-general-report p-15">
					<thead>
						<tr align="center">
							<td colspan="4">
								<span><br>General Information</span>
								<br><br>
							</td>
						</tr>
					</thead>
					<tbody>
						<tr align="left" id="uptime_data">
							<td>Uptime: </td>
							<td class="table-data" colspan="3"><span id="uptime"></span></td>
						</tr>
						<tr align="left" id="average_data">
							<td>Cpu Average: </td>
						<td class="table-data" colspan="3"><span id="cpu_load"></span></td>
						</tr>
						<tr align="left">
							<td>Apache Memory Usage: </td>
							<td class="table-data" colspan="3"><span id="usage_ram"></span></td>
						</tr>
						<tr align="left" id="hdd_data">
							<td>HDD Usage: </td>
							<td class="table-data-alerts"><span id="usage_hdd"></span></td>
				        <td>Max IO: </td>
				        <td class="table-data-min"><span id="max_disk">0</span></td>
						</tr>
						<tr align="left">
							<td>CPU Alerts: </td>
							<td class="table-data-alerts"><span id="alert_cpu">0</span></td>
				        <td>Max CPU: </td>
				        <td class="table-data-min"><span id="max_cpu">0</span></td>
						</tr>
						<tr align="left">
							<td>RAM Alerts: </td>
							<td class="table-data-alerts"><span id="alert_ram">0</span></td>
				        <td>Max RAM: </td>
				        <td class="table-data-min"><span id="max_ram">0</span></td>
						</tr>
						<tr align="left">
				        <td>Net Alerts: </td>
				        <td class="table-data-alerts"><span id="alert_net">0</span></td>
							<td>Max Net: </td>
							<td class="table-data-min"><span id="max_http">0</span></td>
						</tr>
						<tr align="left">
							<td>Postgres Alerts: </td>
							<td class="table-data-alerts"><span id="alert_bd">0</span></td>
							<td>Max Postgres: </td>
							<td class="table-data-min"><span id="max_bd">0</span></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="section">
				<div class="section-title p-15" align="center">
					<div><br>Network Connections<br><br></div>
					<div class="section-general-data">
						<span id="tot_net"></span>
					</div>
				</div>
				<table class="table-general-report p-15">
					<tbody id="inf_net_conn"></tbody>
				</table>
			</div>
			<div class="section double-section">
				<div class="section-title">
					Postgres Querys
				</div>
				<table class="table-general-report p-7">
					<tbody id="inf_bd_conn"></tbody>
				</table>
			</div>
		</div>
	</div>

	<script src="../resources/js/jquery.min.js"></script>
	<script src="../resources/js/index.js"></script>	
</body>
</html>