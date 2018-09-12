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
				<td colspan="3" class="color-brand">VIRTUAL MONITOR - Be+</td>
			</tr>
			<tr>
				<td class="section">
					<div class="block section-title">
						<div class="title">DISCO - VELOCIDAD ESCRITURA Y LECTURA</div>
						<div class="section-general-data">
							<span id="w_disk_io"></span>&emsp;
		  					<span id="r_disk_io"></span>
						</div>
					</div>					
					<div class="inline-block medidor relative">						
		  				<span id="w_disk_io-top" class="top"></span>
		  				<span id="w_disk_io-bot" class="bot"></span>
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
						<span id="ram-top" class="top"></span>
		  				<span id="ram-bot" class="bot"></span>
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
						<span id="cpu-top" class="top"></span>
		  				<span id="cpu-bot" class="bot"></span>
					</div>
					<canvas id="cpu" class="inline-block"></canvas>
				</td>
			</tr>
			<tr>
				<td class="section">
          <div class="block section-title">
            <div class="title">RED - CONEXIONES</div>
            <div class="section-general-data">
              <span id="net_act"></span>
            </div>
          </div>
          <div class="inline-block medidor relative">
            <span id="net-top" class="top"></span>
              <span id="net-bot" class="bot"></span>
          </div>
          <canvas id="server_conn" class="inline-block"></canvas>
				</td>
				<td class="section">
          <div class="block section-title">
            <div class="title">BD - CONEXIONES</div>
            <div class="section-general-data">
              <span id="bd_act"></span>
            </div>
          </div>
          <div class="inline-block medidor relative">
            <span id="bd-top" class="top"></span>
              <span id="bd-bot" class="bot"></span>
          </div>
          <canvas id="bd_conn" class="inline-block"></canvas>
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
								<tr align="left" id="uptime_data">
			  						<td>Uptime: </td>
			  						<td class="table-data" colspan="3"><span id="uptime"></span></td>
			  					</tr>
			  					<tr align="left" id="average_data">
			  						<td>CPU AVERAGE: </td>
									<td class="table-data" colspan="3"><span id="cpu_load"></span></td>
			  					</tr>
			  					<tr align="left">
			  						<td>Uso de memoria APACHE: </td>
			  						<td class="table-data" colspan="3"><span id="usage_ram"></span></td>
			  					</tr>
			  					<tr align="left" id="hdd_data">
			 						  <td>Uso HDD: </td>
			 						  <td class="table-data-alerts"><span id="usage_hdd"></span></td>
                    <td>MAX W-R: </td>
                    <td class="table-data-min"><span id="max_disk">0</span></td>
			  					</tr>
			  					<tr align="left">
			  						<td>Alertas CPU: </td>
			  						<td class="table-data-alerts"><span id="alert_cpu">0</span></td>
                    <td>MAX CPU: </td>
                    <td class="table-data-min"><span id="max_cpu">0</span></td>
			  					</tr>
			  					<tr align="left">
			  						<td>Alertas RAM: </td>
			  						<td class="table-data-alerts"><span id="alert_ram">0</span></td>
                    <td>Max RAM: </td>
                    <td class="table-data-min"><span id="max_ram">0</span></td>
			  					</tr>
			  					<tr align="left">
                    <td>Alertas NET: </td>
                    <td class="table-data-alerts"><span id="alert_net">0</span></td>
			  						<td>Maximo net: </td>
			  						<td class="table-data-min"><span id="max_http">0</span></td>
			  					</tr>
			  					<tr align="left">
                    <td>Alertas BD: </td>
                    <td class="table-data-alerts"><span id="alert_bd">0</span></td>
			  						<td>Maximo bd: </td>
			  						<td class="table-data-min"><span id="max_bd">0</span></td>
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
							<span id="tot_net"></span>
						</div>
					</div>
					<div class="container-data-area">
						<table class="table-general-report">
							<tbody id="inf_net_conn"></tbody>
						</table>
					</div>
				</td>
				<td class="section" colspan="2">
					<div class="block section-title">
						<div class="title">LISTA DE PROCESOS ACTIVOS EN BASE DE DATOS</div>
					</div>
					<div class="container-data-area">
						<table class="table-general-report">
							<tbody id="inf_bd_conn"></tbody>
						</table>
					</div>
				</td>
			</tr>
		</tbody>
	</table>

	<script src="js/jquery.min.js"></script>
    <script src="js/index.js"></script>
  </body>
</html>