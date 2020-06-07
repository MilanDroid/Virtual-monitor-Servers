/*
	VMON-Be+  --- VIRTUAL MONITOR BY Be+ Developments
	SISTEMA DE MONITOREO VIRTUAL PARA MAS INFORMACION CONTACTAR CON beplusdevelopment@gmail.com
*/
var movimiento = 4;
var alertAlto = document.documentElement.clientHeight*0.23;

var canvas = {
	canvasDisk_io: document.getElementById("diskStats"),
	canvasRam: document.getElementById("ram"),
	canvasCpu: document.getElementById("cpu"),
	canvasNet: document.getElementById("server_conn"),
	canvasBd: document.getElementById("bd_conn"),
}

var sections = {
	diskIo: {
		lienzo: canvas.canvasDisk_io.getContext("2d"),
		alto: canvas.canvasDisk_io.height,
		ancho: canvas.canvasDisk_io.width,
		linea: "#00f2ff",
		linea2: "#ff5500",
		sombra: "#2e1102",
		sombra2: "#03292b",
		read: 0,
		write: 0,
		lmin: 0,
		lmax: 0,
		rdata: new Array(),
		wdata: new Array()
	},
	ram: {
		lienzo: canvas.canvasRam.getContext("2d"),
		alto: canvas.canvasRam.height,
		ancho: canvas.canvasRam.width,
		linea: "#ff5500",
		sombra: "#141121",
		lmax: 0,
		lmin: 0,
		rdata: new Array()
	},
	cpuPercent: {
		lienzo: canvas.canvasCpu.getContext("2d"),
		alto: canvas.canvasCpu.height,
		ancho: canvas.canvasCpu.width,
		linea: "#ff5500",
		sombra: "#2e1102",
		lmax: 0,
		lmin: 0,
		rdata: new Array()
	},
	netConnections: {
		lienzo: canvas.canvasNet.getContext("2d"),
		alto: canvas.canvasNet.height,
		ancho: canvas.canvasNet.width,
		linea: "#65ff24",
		sombra: "#141121",
		lmax: 0,
		lmin: 0,
		rdata: new Array()
	},
	bdConnections: {
		lienzo: canvas.canvasBd.getContext("2d"),
		alto: canvas.canvasBd.height,
		ancho: canvas.canvasBd.width,
		linea: "#65ff24",
		sombra: "#141121",
		lmax: 0,
		rdata: new Array()
	},
};

var alerts = {
	cpu_act: 0,
	ram_act: 0,
	r_disk_io: 0,
	w_disk_io: 0,
	net_act: 0,
	bd_act: 0,
	max_cpu: 0,
	max_ram: 0,
	max_net: 0,
	max_bd: 0,
	max_w: 0,
	max_r: 0
};

function dibujarLinea(color, xinicial, yinicial, xfinal, yfinal, lienzo, line)
{
	lienzo.beginPath();
  	lienzo.strokeStyle = color;
  	lienzo.lineWidth = line;
  	lienzo.moveTo(xinicial, yinicial);
  	lienzo.lineTo(xfinal, yfinal);
  	lienzo.stroke();
  	lienzo.closePath();
}

function dibujarArea(lienzo, xinicial, alto, yinicial, yfinal, sombra)
{
	for (var i = 0; i <= (movimiento-1); i++) {
  		lienzo.beginPath();
  		lienzo.strokeStyle = sombra;
	  	lienzo.lineWidth = 1;
	  	lienzo.moveTo(xinicial, alto);
	  	lienzo.lineTo(xinicial, yinicial+((yfinal-yinicial)*i/movimiento));
	  	lienzo.stroke();
  		lienzo.closePath();

  		xinicial++;
  	}
}

function secciones(lienzo, ancho, alto)
{
	for (var i = 1; i <= 4; i++) {
		lienzo.beginPath();
	  	lienzo.strokeStyle = "#1b172e";
  		lienzo.lineWidth = 1;
	  	lienzo.moveTo(0, alto*0.20*i);
	  	lienzo.lineTo(ancho, alto*0.20*i);
	  	lienzo.stroke();
	}

	lienzo.closePath();
}

function calculos(array, color, lienzo, lmax, alto, sombra)
{
	if(array.length >= 76){
		array.pop();
	}

	var x = 0;
	var dsize= array.length - 1;

	array.forEach(function(item, index, data) {
		if(dsize!= index){
			yinicial = alto - (data[dsize- index]*alto)/lmax;
			yfinal = alto - (data[dsize- index - 1]*alto)/lmax;
			xfinal = x + movimiento;

			dibujarLinea(color, x, yinicial, xfinal, yfinal, lienzo, 2);
			dibujarArea(lienzo, x, alto, yinicial, yfinal, sombra);
			x = xfinal;
		}
	});
}

function alerta(limit, value, object)
{
	if(value > limit){
		$('#'+object).addClass('alert-red');
		alerts[object] += 1;
	}
	else if($('#'+object).hasClass('alert-red')){
		$('#'+object).removeClass('alert-red');
	}
}

function diskIo_monitor()
{
	$.ajax({
		url:"../app/Controllers/stats.php",
		method: "POST",
		data: ({tipo: 'diskIo'}),
		dataType: "json",
		success:function(datos){
			sections.diskIo.wdata.unshift(datos.d_write);
			sections.diskIo.rdata.unshift(datos.d_read);

			sections.diskIo.read = Math.max.apply(null, sections.diskIo.rdata);
			sections.diskIo.write = Math.max.apply(null, sections.diskIo.wdata);

			if(sections.diskIo.write > sections.diskIo.read){
				sections.diskIo.lmax = sections.diskIo.write + 0.1;
			}
			else{
				sections.diskIo.lmax = sections.diskIo.read + 0.1;
			}

			sections.diskIo.read = Math.min.apply(null, sections.diskIo.rdata);
			sections.diskIo.write = Math.min.apply(null, sections.diskIo.wdata);

			if(sections.diskIo.write < sections.diskIo.read){
				sections.diskIo.lmin = sections.diskIo.write;
			}
			else{
				sections.diskIo.lmin = sections.diskIo.read;
			}	

			sections.diskIo.lienzo.clearRect(0, 0, sections.diskIo.ancho, sections.diskIo.alto);

			secciones(sections.diskIo.lienzo, sections.ram.ancho, sections.ram.alto);
			calculos(sections.diskIo.rdata, sections.diskIo.linea2, sections.diskIo.lienzo, sections.diskIo.lmax, sections.diskIo.alto, sections.diskIo.sombra);
			calculos(sections.diskIo.wdata, sections.diskIo.linea, sections.diskIo.lienzo, sections.diskIo.lmax, sections.diskIo.alto, sections.diskIo.sombra2);

			$('#w_disk_io').html("Escritura: "+parseFloat(datos.d_write).toFixed(2)+" Kb/s");
			$('#r_disk_io').html("Lectura: "+parseFloat(datos.d_read).toFixed(2)+" Kb/s");

			$('#w_disk_io-top').html(parseFloat(sections.diskIo.lmax).toFixed(2)+"Kb/s");
			$('#w_disk_io-bot').html(parseFloat(sections.diskIo.lmin).toFixed(2)+"Kb/s").css('margin-top',(alertAlto-(sections.diskIo.lmin*alertAlto/sections.diskIo.lmax))+'px');

			if(alerts.max_w < datos.d_write){alerts.max_w = datos.d_write;}
			if(alerts.max_r < datos.d_read){alerts.max_r = datos.d_read;}

			alerta(7000, datos.d_write, 'w_disk_io');
			alerta(2000, datos.d_read, 'r_disk_io');
		},
		error:function(e){
			console.log("Error: "+JSON.stringify(e));
		}
	});
}

function ramUsage_monitor()
{
	$.ajax({
		url:"../app/Controllers/stats.php",
		method: "POST",
		data: ({tipo: 'ram_server'}),
		dataType: "json",
		success:function(datos){
			sections.ram.rdata.unshift(datos.memory);
			sections.ram.lmax = Math.max.apply(null, sections.ram.rdata);
			sections.ram.lmin = parseFloat(Math.min.apply(null, sections.ram.rdata)).toFixed(2);

			sections.ram.lienzo.clearRect(0, 0, sections.ram.ancho, sections.ram.alto);
			dibujarLinea('red', 0, sections.ram.alto*0.50, sections.ram.ancho, sections.ram.alto*0.50, sections.ram.lienzo, 2);

			secciones(sections.ram.lienzo, sections.ram.ancho, sections.ram.alto);
			calculos(sections.ram.rdata, sections.ram.linea, sections.ram.lienzo, 100, sections.ram.alto, sections.ram.sombra);
			
			$('#ram_act').html("Uso actual: "+parseFloat(datos.memory).toFixed(2)+" %");
			$('#ram-top').html(parseFloat(sections.ram.lmax).toFixed(2)+"%").css('margin-top',((100-3-sections.ram.lmax)*alertAlto)/100+'px');
			
			//EN ESTA PARTE 'sections.ram.alto+5' SE LE SUMA 5 PARA QUE NUNCA QUEDEN EN EL MISMO LUGAR EL TOP Y EL BOT
			$('#ram-bot').html(sections.ram.lmin+"%").css('margin-top',((100+2-sections.ram.lmin)/100)*alertAlto+'px');
			
			if(alerts.max_ram < datos.memory){alerts.max_ram = datos.memory;}
			alerta(50, datos.memory, 'ram_act');
		},
		error:function(e){
			console.log("Error: "+JSON.stringify(e));
		}
	});	
}

function cpu_monitor()
{
	$.ajax({
		url:"../app/Controllers/stats.php",
		method: "POST",
		data: ({tipo: 'cpu_load'}),
		dataType: "json",
		success:function(datos){
			sections.cpuPercent.rdata.unshift(datos.load);
			sections.cpuPercent.lmax = Math.max.apply(null, sections.cpuPercent.rdata);
			sections.cpuPercent.lmin = parseFloat(Math.min.apply(null, sections.cpuPercent.rdata)).toFixed(2);

			sections.cpuPercent.lienzo.clearRect(0, 0, sections.cpuPercent.ancho, sections.cpuPercent.alto);
			dibujarLinea('red', 0, sections.cpuPercent.alto*0.40, sections.cpuPercent.ancho, sections.cpuPercent.alto*0.40, sections.cpuPercent.lienzo, 2);

			secciones(sections.cpuPercent.lienzo, sections.ram.ancho, sections.ram.alto);
			calculos(sections.cpuPercent.rdata, sections.cpuPercent.linea, sections.cpuPercent.lienzo, 100, sections.cpuPercent.alto, sections.cpuPercent.sombra);
			
			$('#cpu_act').html("Uso actual: "+parseFloat(datos.load).toFixed(2)+" %");
			$('#cpu-top').html(parseFloat(sections.cpuPercent.lmax).toFixed(2)+"%").css('margin-top',((100-sections.cpuPercent.lmax)*alertAlto)/100+'px');
			$('#cpu-bot').html(sections.cpuPercent.lmin+"%").css('margin-top',((100-sections.cpuPercent.lmin)/100)*alertAlto+'px');
			
			if(alerts.max_cpu < datos.load){alerts.max_cpu = datos.load;}
			alerta(60, datos.load, 'cpu_act');
		},
		error:function(e){
			console.log("Error: "+JSON.stringify(e));
		}
	});	
}

function netConnections_monitor()
{
	$.ajax({
		url:"../app/Controllers/stats.php",
		method: "POST",
		data: ({tipo: 'net_conn'}),
		dataType: "json",
		success:function(datos){
			sections.netConnections.rdata.unshift(datos.net_conn);
			sections.netConnections.lmax = Math.max.apply(null, sections.netConnections.rdata);
			sections.netConnections.lmin = Math.min.apply(null, sections.netConnections.rdata);

			$('#net_act').html("Conexiones actuales: "+datos.net_conn);
			$('#tot_net').html("TOTAL CONEXIONES: "+datos.sum);

			sections.netConnections.lienzo.clearRect(0, 0, sections.netConnections.ancho, sections.netConnections.alto);
			secciones(sections.netConnections.lienzo, sections.ram.ancho, sections.ram.alto);
			calculos(sections.netConnections.rdata, sections.netConnections.linea, sections.netConnections.lienzo, sections.netConnections.lmax, sections.netConnections.alto, sections.netConnections.sombra);
			
			$('#net-top').html(sections.netConnections.lmax);
			$('#net-bot').html(sections.netConnections.lmin).css('margin-top',(alertAlto-(sections.netConnections.lmin*alertAlto/sections.netConnections.lmax))+'px');
			
			if(alerts.max_net < datos.net_conn){alerts.max_net = datos.net_conn;}
			$('#inf_net_conn').html(datos.inf);
			alerta(100, datos.net_conn, 'net_act');
		},
		error:function(e){
			console.log("Error: "+JSON.stringify(e));
		}
	});	
}

function bdConnections_monitor()
{
	$.ajax({
		url:"../app/Controllers/stats.php",
		method: "POST",
		data: ({tipo: 'bd_conn'}),
		dataType: "json",
		success:function(datos){
			sections.bdConnections.rdata.unshift(datos.bd_conn);
			sections.bdConnections.lmax = Math.max.apply(null, sections.bdConnections.rdata);
			sections.bdConnections.lmin = Math.min.apply(null, sections.bdConnections.rdata);

			$('#bd_act').html("Conexiones actuales: "+datos.bd_conn);

			sections.bdConnections.lienzo.clearRect(0, 0, sections.bdConnections.ancho, sections.bdConnections.alto);

			secciones(sections.bdConnections.lienzo, sections.ram.ancho, sections.ram.alto);
			calculos(sections.bdConnections.rdata, sections.bdConnections.linea, sections.bdConnections.lienzo, sections.bdConnections.lmax, sections.bdConnections.alto, sections.bdConnections.sombra);
			$('#bd-top').html(sections.bdConnections.lmax);
			$('#bd-bot').html(sections.bdConnections.lmin).css('margin-top',(sections.bdConnections.alto-(sections.bdConnections.lmin*sections.bdConnections.alto/sections.bdConnections.lmax))+'px');
			$('#inf_bd_conn').html(datos.bd_inf);

			if(alerts.max_bd < datos.bd_conn){alerts.max_bd = datos.bd_conn;}
			alerta(25, datos.bd_conn, 'bd_act');
		},
		error:function(e){
			console.log("Error: "+JSON.stringify(e));
		}
	});	
}
function staticInf_monitor()
{
	$.ajax({
		url:"../app/Controllers/stats.php",
		method: "POST",
		data: ({tipo: 'stat_inf'}),
		dataType: "json",
		success:function(datos){
			$('#uptime').html(datos.time[3]+" Dias "+datos.time[2]+" hr "+datos.time[1]+" min "+datos.time[0]+" seg");

			if(datos.time[1] < 30 && datos.time[2] == 0 && datos.time[3] == 0){
				$('#uptime_data').addClass('alert-blue');
			}
			else if($('#uptime_data').hasClass('alert-blue')){
				$('#uptime_data').removeClass('alert-blue');
			}

			$('#cpu_load').html(datos.load[0]+" - "+datos.load[1]+" - "+datos.load[2]);

			if(datos.load[0] > 8){
				$('#average_data').addClass('alert-red');
			}
			else if($('#average_data').hasClass('alert-red')){
				$('#average_data').removeClass('alert-red');
			}

			$('#usage_ram').html(datos.memory);
			$('#usage_hdd').html(datos.disk);

			if(datos.disk > 75){
				$('#hdd_data').addClass('alert-red');
			}
			else if($('#hdd_data').hasClass('alert-red')){
				$('#hdd_data').removeClass('alert-red');
			}

			$('#max_disk').html(alerts.max_w+" - "+alerts.max_r);
			$('#alert_cpu').html(alerts.cpu_act);
			$('#max_cpu').html(alerts.max_cpu);
			$('#alert_ram').html(alerts.ram_act);
			$('#max_ram').html(alerts.max_ram);
			$('#alert_net').html(alerts.net_act);
			$('#max_http').html(alerts.max_net);
			$('#alert_bd').html(alerts.bd_act);
			$('#max_bd').html(alerts.max_bd);
		},
		error:function(e){
			console.log("Error: "+JSON.stringify(e));
		}
	});	
}

function canvasLoader()
{
	sections.diskIo.lienzo.globalAlpha = 10;
  	sections.diskIo.lienzo.globalCompositeOperation = "lighter";
  	sections.ram.lienzo.globalAlpha = 10;
  	sections.ram.lienzo.globalCompositeOperation = "lighter";
  	sections.cpuPercent.lienzo.globalAlpha = 10;
  	sections.cpuPercent.lienzo.globalCompositeOperation = "lighter";
  	sections.netConnections.lienzo.globalAlpha = 10;
  	sections.netConnections.lienzo.globalCompositeOperation = "lighter";
	sections.bdConnections.lienzo.globalAlpha = 10;
  	sections.bdConnections.lienzo.globalCompositeOperation = "lighter";
}

function initializer()
{
	alertAlto = document.documentElement.clientHeight*0.23;

	diskIo_monitor();
	ramUsage_monitor();
	cpu_monitor();
	netConnections_monitor();
	bdConnections_monitor();
	staticInf_monitor();
}

//INICIALIZANDO EL GRAFICO PARA EL DISK/IO MONITOR
canvasLoader();
setInterval('initializer()',1000);