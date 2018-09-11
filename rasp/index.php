<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>VMON-Be+</title>
	<link rel="stylesheet" type="text/css" href="css/styles.css">
  </head>
  <body>
	<table class="container">
		<tbody>
			<tr>
				<td class="section">
					<div class="block section-title">
						<div class="title">DISCO - VELOCIDAD ESCRITURA Y LECTURA</div>
						<div class="section-general-data">
							<span id="w_disk_io"></span>
		  					<span id="r_disk_io"></span>
						</div>
					</div>					
					<div class="inline-block medidor relative">						
		  				<span id="w_disk_io-top" class="absolute top"></span>
		  				<span id="w_disk_io-bot" class="absolute bot"></span>
					</div>
					<canvas id="disk_io" class="inline-block"></canvas>
				</td>
				<td class="section">
					<div class="block section-title">
						<div class="title">RAM - CONSUMO</div>
						<div class="section-general-data">
							<span id="ram_act"></span>
						</div>
					</div>				
					<div class="inline-block medidor relative">
						<span id="ram-top" class="absolute top"></span>
		  				<span id="ram-bot" class="absolute bot"></span>
					</div>
					<canvas id="ram" class="inline-block"></canvas>
				</td>
				<td class="section">
					<div class="block section-title">
						<div class="title">CPU - RENDIMIENTO</div>
						<div class="section-general-data">
							<span id="cpu_act"></span>
						</div>
					</div>			
					<div class="inline-block medidor relative">
						<span id="cpu-top" class="absolute top"></span>
		  				<span id="cpu-bot" class="absolute bot"></span>
					</div>
					<canvas id="cpu" class="inline-block"></canvas>
				</td>
			</tr>
			<tr>
				<td class="section">					
					<div class="block section-title">
						<div class="title">BD - CONEXIONES</div>
						<div class="section-general-data">
							<span id="bd_act"></span>
						</div>
					</div>
					<div class="inline-block medidor relative">
						<span id="bd-top" class="absolute top"></span>
		  				<span id="bd-bot" class="absolute bot"></span>
					</div>
					<canvas id="bd_conn" class="inline-block"></canvas>
				</td>
				<td class="section">
					<div class="block section-title">
						<div class="title">RED - CONEXIONES</div>
						<div class="section-general-data">
							<span id="net_act"></span>
						</div>
					</div>
					<div class="inline-block medidor relative">
						<span id="net-top" class="absolute top"></span>
		  				<span id="net-bot" class="absolute bot"></span>
					</div>
					<canvas id="server_conn" class="inline-block"></canvas>
				</td>
				<td class="section">
					<div class="block section-title">
						<div class="title">INFORMACION GENERAL - ALERTAS</div>
						<div class="section-general-data">
							<span id="tot_alert"></span>
						</div>
					</div>
					<div class="inline-block container-general-report">
						<table class="table-general-report">
							<tbody>
								<tr align="left">
			  						<td>Uptime: </td>
			  						<td class="table-data"><span id="uptime"></span></td>
			  					</tr>
			  					<tr align="left">
			  						<td>CPU AVERAGE: </td>
									<td class="table-data"><span id="cpu_load"></span></td>
			  					</tr>
			  					<tr align="left">
			  						<td>Uso de memoria APACHE: </td>
			  						<td class="table-data"><span id="usage_ram"></span></td>
			  					</tr>
			  					<tr align="left">
			 						<td>Uso HDD: </td>
			 						<td class="table-data"><span id="usage_hdd"></span></td>
			  					</tr>
			  					<tr align="left">
			  						<td>Alertas CPU: </td>
			  						<td class="table-data"><span id="alert_cpu">0</span></td>
			  					</tr>
			  					<tr align="left">
			  						<td>Alertas RAM: </td>
			  						<td class="table-data"><span id="alert_ram">0</span></td>
			  					</tr>
			  					<tr align="left">
			  						<td>Maximo conexiones red: </td>
			  						<td class="table-data"><span id="max_http">0</span></td>
			  					</tr>
			  					<tr align="left">
			  						<td>Maximo conexiones BD: </td>
			  						<td class="table-data"><span id="max_bd">0</span></td>
								</tr>
							</tbody>
						</table>
					</div>
				</td>
			<tr>
			<tr>
				<td class="section">
					<div class="block section-title">
						<div class="title">LISTA DE CONEXIONES ACTUALES EN RED</div>
						<div class="section-general-data">
							<span id="net_alert"></span>
						</div>
					</div>
					<div class="container-inf-data">
						<div class="inf-data-area">
							<table class="table-general-report">
								<tbody id="inf_net_conn"></tbody>
							</table>
						</div>
					</div>
				</td>
				<td class="section" colspan="2">
					<div class="block section-title">
						<div class="title">LISTA DE PROCESOS ACTIVOS EN BASE DE DATOS</div>
						<div class="section-general-data">
							<span id="net_alert"></span>
						</div>
					</div>
					<div class="container-inf-data">
						<div class="inf-data-area">
							<table class="table-general-report" wrap="off">
								<tbody id="inf_bd_conn"></tbody>
							</table>
						</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>

	<script src="js/jquery.min.js"></script>
    <script src="js/index.js"></script>
  </body>
</html>