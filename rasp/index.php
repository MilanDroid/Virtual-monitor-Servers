<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>VMON-Be+</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
  </head>
  <body>
	<table  id="container" class="container">
		<tbody>
			<tr align="center">
				<td class="block frame" align="center">
		  			<p align="center" class="canvas-title">DISCO - VELOCIDAD ESCRITURA Y LECTURA</p>
		  			<div align="center" class="container-info-canvas">
		  				<span id="w_disk_io" class="info-canvas"></span>&emsp;
		  				<span id="r_disk_io" class="info-canvas"></span>
		  			</div>
		  			<div>
		  				<div class="block medidor-canvas relative" align="center">
		  					<span id="w_disk_io-top" class="top"></span>
		  					<span id="w_disk_io-bot" class="bot"></span>
		  				</div>
		  				<canvas id="disk_io" class="block"></canvas>
		  			</div>			
		  		</td>
		  		<td class="block frame">
		  			<p align="center" class="canvas-title">RAM - CONSUMO</p>
		  			<div align="center" class="container-info-canvas">
		  				<span id="ram_act" class="info-canvas">&emsp;</span>
		  			</div>
		  			<div>
		  				<div class="block medidor-canvas relative" align="center">
		  					<span id="ram-top" class="top"></span>
		  					<span id="ram-bot" class="bot"></span>
		  				</div>
		    			<canvas id="ram"></canvas>
		  			</div>
		  		</td>
		  		<td class="block frame">
		  			<p align="center" class="canvas-title">CPU - RENDIMIENTO</p>
		    		<div align="center" class="container-info-canvas">
		  				<span id="cpu_act" class="info-canvas">&emsp;</span>
		  			</div>
		  			<div>
		  				<div class="block medidor-canvas relative" align="center">
		  					<span id="cpu-top" class="top"></span>
		  					<span id="cpu-bot" class="bot"></span>
		  				</div>
		    			<canvas id="cpu"></canvas>
		  			</div>
		  		</td>
			</tr>
			<tr  align="center">
				<td class="block frame">
		  			<p align="center" class="canvas-title">BD - CONEXIONES</p>
		  			<div align="center" class="container-info-canvas">
		  				<span id="bd_act" class="info-canvas">&emsp;</span>
		  			</div>
		  			<div>
		  				<div class="block medidor-canvas relative" align="center">
		  					<span id="bd-top" class="top"></span>
		  					<span id="bd-bot" class="bot"></span>
		  				</div>
		    			<canvas id="bd_conn"></canvas>
		    		</div>
		  		</td>
		  		<td class="block frame">
		  			<p align="center" class="canvas-title">RED - CONEXIONES</p>
		  			<div align="center" class="container-info-canvas">
		  				<span id="net_act" class="info-canvas">&emsp;</span>
		  			</div>
		  			<div>
		  				<div class="block medidor-canvas relative" align="center">
		  					<span id="net-top" class="top"></span>
		  					<span id="net-bot" class="bot"></span>
		  				</div>
		    			<canvas id="server_conn"></canvas>
		    		</div>
		  		</td>
		  		<td class="block frame inf-frame">
		  			<div id="head-inf">
		  				<p align="center" class="inf-title">INFORMACION GENERAL - ALERTAS</p>
		  				<div align="center" class="container-info-canvas">
			  				<span id="tot_alert" class="info-canvas">&emsp;</span>
			  			</div>
		  			</div>
		  			<div id="body-inf">
		  				<table class="inf-content">
		  					<tbody>
		  						<tr>
		  							<td>Uptime: </td>
		  							<td class="inf-data"><span id="uptime"></span></td>
		  						</tr>
		  						<tr>
		  							<td>CPU AVERAGE: </td>
		  							<td class="inf-data"><span id="cpu_load"></span></td>
		  						</tr>
		  						<tr>
		  							<td>Uso de memoria APACHE: </td>
		  							<td class="inf-data"><span id="usage_ram"></span></td>
		  						</tr>
		  						<tr>
		  							<td>Uso HDD: </td>
		  							<td class="inf-data"><span id="usage_hdd"></span></td>
		  						</tr>
		  						<tr>
		  							<td>Alertas CPU: </td>
		  							<td class="inf-data"><span id="alert_cpu">0</span></td>
		  						</tr>
		  						<tr>
		  							<td>Alertas RAM: </td>
		  							<td class="inf-data"><span id="alert_ram">0</span></td>
		  						</tr>
		  						<tr>
		  							<td>Maximo conexiones red: </td>
		  							<td class="inf-data"><span id="max_http">0</span></td>
		  						</tr>
		  						<tr>
		  							<td>Maximo conexiones BD: </td>
		  							<td class="inf-data"><span id="max_bd">0</span></td>
		  						</tr>
		  					</tbody>
		  				</table>
		  			</div>
		  		</td>
			</tr>
			<tr>
				<td class="block frame inf-frame left-marging-80">
					<div>
		  			<p align="center" class="inf-title left-marging-80">LISTA DE CONEXIONES ACTUALES EN RED</p>
		  			<div align="center" class="container-info-canvas">
			  			<textarea name="inf_net_conn" id="inf_net_conn" class="info-canvas data-list"></textarea>
			 			</div>
					</div>
	  		</td>
        <td class="block frame inf-frame" colspan="2">
          <div>
            <p align="center" class="inf-title left-marging-300">BD ACTIVIDAD ACTUAL</p>
            <div align="center" class="container-info-canvas">
              <textarea name="inf_bd_conn" id="inf_bd_conn" class="info-canvas data-list-l"></textarea>
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
