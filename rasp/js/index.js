/*
	VMON-Be+  --- VIRTUAL MONITOR BY Be+ Developments
	SISTEMA DE MONITOREO VIRTUAL PARA MAS INFORMACION CONTACTAR CON beplusdevelopment@gmail.com
*/
var movimiento = 4;

var canvas = {
	canvasDisk_io: document.getElementById("disk_io"),
	canvasRam: document.getElementById("ram"),
	canvasCpu: document.getElementById("cpu"),
	canvasBd: document.getElementById("bd_conn"),
	canvasNet: document.getElementById("server_conn")
}

var sections = {
	diskIo: {
		lienzo: canvas.canvasDisk_io.getContext("2d"),
		alto: canvas.canvasDisk_io.height,
		ancho: canvas.canvasDisk_io.width,
		linea: "#00c600",
		linea2: "red",
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
		linea: "#00c600",
		lmax: 0,
		lmin: 0,
		rdata: new Array()
	},
	cpuPercent: {
		lienzo: canvas.canvasCpu.getContext("2d"),
		alto: canvas.canvasCpu.height,
		ancho: canvas.canvasCpu.width,
		linea: "#00c600",
		lmax: 0,
		lmin: 0,
		rdata: new Array()
	},
	bdConnections: {
		lienzo: canvas.canvasBd.getContext("2d"),
		alto: canvas.canvasBd.height,
		ancho: canvas.canvasBd.width,
		linea: "#00c600",
		lmax: 0,
		rdata: new Array()
	},
	netConnections: {
		lienzo: canvas.canvasNet.getContext("2d"),
		alto: canvas.canvasNet.height,
		ancho: canvas.canvasNet.width,
		linea: "#00c600",
		lmax: 0,
		lmin: 0,
		rdata: new Array()
	}
};

function dibujarLinea(color, xinicial, yinicial, xfinal, yfinal, lienzo) {
	lienzo.beginPath();
  	lienzo.strokeStyle = color;
	lienzo.globalAlpha=0.7;
  	lienzo.lineWidth = 3;
  	lienzo.moveTo(xinicial, yinicial);
  	lienzo.lineTo(xfinal, yfinal);
  	lienzo.stroke();
  	lienzo.closePath();
}

function calculos(array, color, lienzo, lmax, alto) {
	if(array.length >= 88){
		array.pop();
	}

	var x = 0;
	var dsize= array.length - 1;

	array.forEach(function(item, index, data) {
		if(dsize!= index){
			dibujarLinea(color, x, alto - (data[dsize- index]*alto)/lmax, x + movimiento, alto - (data[dsize- index - 1]*alto)/lmax, lienzo);
			x = x + movimiento;
		}
	});
}

function diskIo_monitor() {
	$.ajax({
		url:"ajax/datos.php",
		method: "POST",
		data: ({tipo: 'disk_io'}),
		dataType: "json",
		success:function(datos){
			sections.diskIo.wdata.unshift(datos.d_write);
			sections.diskIo.rdata.unshift(datos.d_read);

			sections.diskIo.read = Math.max(...sections.diskIo.rdata);
			sections.diskIo.write = Math.max(...sections.diskIo.wdata);

			if(sections.diskIo.write > sections.diskIo.read){
				sections.diskIo.lmax = sections.diskIo.write + 0.1;
			}
			else{
				sections.diskIo.lmax = sections.diskIo.read + 0.1;
			}

			sections.diskIo.read = Math.min(...sections.diskIo.rdata);
			sections.diskIo.write = Math.min(...sections.diskIo.wdata);

			if(sections.diskIo.write < sections.diskIo.read){
				sections.diskIo.lmin = sections.diskIo.write;
			}
			else{
				sections.diskIo.lmin = sections.diskIo.read;
			}	

			sections.diskIo.lienzo.clearRect(0, 0, sections.diskIo.ancho, sections.diskIo.alto);
			calculos(sections.diskIo.rdata, sections.diskIo.linea2, sections.diskIo.lienzo, sections.diskIo.lmax, sections.diskIo.alto);
			calculos(sections.diskIo.wdata, sections.diskIo.linea, sections.diskIo.lienzo, sections.diskIo.lmax, sections.diskIo.alto);

			$('#w_disk_io').html("Escritura: "+parseFloat(datos.d_write).toFixed(2)+" Kb/s");
			$('#r_disk_io').html("Lectura: "+parseFloat(datos.d_read).toFixed(2)+" Kb/s");

			$('#w_disk_io-top').html(parseFloat(sections.diskIo.lmax).toFixed(2)+"Kb/s");
			$('#w_disk_io-bot').html(parseFloat(sections.diskIo.lmin).toFixed(2)+"Kb/s").css('margin-top',(sections.diskIo.alto-(sections.diskIo.lmin*sections.diskIo.alto/sections.diskIo.lmax))+'px');
			/*console.log("DISK W: "+datos.d_write+"\n"+"DISK R: "+datos.d_read);*/
		},
		error:function(e){
			console.log("Error: "+e);
		}
	});
}

function ramUsage_monitor(){
	$.ajax({
		url:"ajax/datos.php",
		method: "POST",
		data: ({tipo: 'ram_server'}),
		dataType: "json",
		success:function(datos){
			sections.ram.rdata.unshift(datos.memory);
			sections.ram.lmax = Math.max(...sections.ram.rdata);
			sections.ram.lmin = parseFloat(Math.min(...sections.ram.rdata)).toFixed(2);

			$('#ram_act').html("Uso actual: "+parseFloat(datos.memory).toFixed(2)+" %");

			sections.ram.lienzo.clearRect(0, 0, sections.ram.ancho, sections.ram.alto);
			calculos(sections.ram.rdata, sections.ram.linea, sections.ram.lienzo, sections.ram.lmax, sections.ram.alto);
			
			$('#ram-top').html(parseFloat(sections.ram.lmax).toFixed(2)+"%");
			//EN ESTA PARTE 'sections.ram.alto+5' SE LE SUMA 5 PARA QUE NUNCA QUEDEN EN EL MISMO LUGAR EL TOP Y EL BOT
			$('#ram-bot').html(sections.ram.lmin+"%").css('margin-top',(sections.ram.alto+5-(sections.ram.lmin*sections.ram.alto/sections.ram.lmax))+'px');
			/*console.log("RAM: "+datos.memory);*/
		},
		error:function(e){
			console.log("Error: "+e);
		}
	});	
}

function cpu_monitor(){
	$.ajax({
		url:"ajax/datos.php",
		method: "POST",
		data: ({tipo: 'cpu_load'}),
		dataType: "json",
		success:function(datos){
			sections.cpuPercent.rdata.unshift(datos.load);
			sections.cpuPercent.lmax = Math.max(...sections.cpuPercent.rdata);
			sections.cpuPercent.lmin = parseFloat(Math.min(...sections.cpuPercent.rdata)).toFixed(2);

			sections.cpuPercent.lienzo.clearRect(0, 0, sections.cpuPercent.ancho, sections.cpuPercent.alto);
			calculos(sections.cpuPercent.rdata, sections.cpuPercent.linea, sections.cpuPercent.lienzo, 100, sections.cpuPercent.alto);
			
			$('#cpu_act').html("Uso actual: "+parseFloat(datos.load).toFixed(2)+" %");

			$('#cpu-top').html(parseFloat(sections.cpuPercent.lmax).toFixed(2)+"%").css('margin-top',((100-sections.cpuPercent.lmax)*sections.cpuPercent.alto)/100+'px');
			$('#cpu-bot').html(sections.cpuPercent.lmin+"%").css('margin-top',((100-sections.cpuPercent.lmin)/100)*sections.cpuPercent.alto+'px');
			/*console.log("CPU%: "+datos.load);*/
		},
		error:function(e){
			console.log("Error: "+e);
		}
	});	
}

function bdConnections_monitor(){
	$.ajax({
		url:"ajax/datos.php",
		method: "POST",
		data: ({tipo: 'bd_conn'}),
		dataType: "json",
		success:function(datos){
			sections.bdConnections.rdata.unshift(datos.bd_conn);
			sections.bdConnections.lmax = Math.max(...sections.bdConnections.rdata);
			sections.bdConnections.lmin = Math.min(...sections.bdConnections.rdata);

			$('#bd_act').html("Conexiones actuales: "+datos.bd_conn);

			sections.bdConnections.lienzo.clearRect(0, 0, sections.bdConnections.ancho, sections.bdConnections.alto);
			calculos(sections.bdConnections.rdata, sections.bdConnections.linea, sections.bdConnections.lienzo, sections.bdConnections.lmax, sections.bdConnections.alto);
			$('#bd-top').html(sections.bdConnections.lmax);
			$('#bd-bot').html(sections.bdConnections.lmin).css('margin-top',(sections.bdConnections.alto-(sections.bdConnections.lmin*sections.bdConnections.alto/sections.bdConnections.lmax))+'px');
			$('#inf_bd_conn').val(datos.bd_inf);
			/*console.log("BD_Connections: "+datos.bd_conn);*/
		},
		error:function(e){
			console.log("Error: "+e);
		}
	});	
}

function netConnections_monitor(){
	$.ajax({
		url:"ajax/datos.php",
		method: "POST",
		data: ({tipo: 'net_conn'}),
		dataType: "json",
		success:function(datos){
			var list = "";

			sections.netConnections.rdata.unshift(datos.net_conn);
			sections.netConnections.lmax = Math.max(...sections.netConnections.rdata);
			sections.netConnections.lmin = Math.min(...sections.netConnections.rdata);

			$('#net_act').html("Conexiones actuales: "+datos.net_conn);

			sections.netConnections.lienzo.clearRect(0, 0, sections.netConnections.ancho, sections.netConnections.alto);
			calculos(sections.netConnections.rdata, sections.netConnections.linea, sections.netConnections.lienzo, sections.netConnections.lmax, sections.netConnections.alto);
			$('#net-top').html(sections.netConnections.lmax);
			$('#net-bot').html(sections.netConnections.lmin).css('margin-top',(sections.netConnections.alto-(sections.netConnections.lmin*sections.netConnections.alto/sections.netConnections.lmax))+'px');
			
			datos.inf.forEach(function(item, index, data) {
				list += index+" -- "+item+"\n";
			});

			$('#inf_net_conn').val(list);
		},
		error:function(e){
			console.log("Error: "+e);
		}
	});	
}

function staticInf_monitor(){
	$.ajax({
		url:"ajax/datos.php",
		method: "POST",
		data: ({tipo: 'stat_inf'}),
		dataType: "json",
		success:function(datos){
			$('#uptime').html(datos.time);
			$('#cpu_load').html(datos.load);
			$('#usage_ram').html(datos.memory);
			$('#usage_hdd').html(datos.disk);
			/*console.log("Net_Connections: "+datos.net_conn);*/
		},
		error:function(e){
			console.log("Error: "+e);
		}
	});	
}

function initializer(){
	ramUsage_monitor();
	diskIo_monitor();
	cpu_monitor();
	bdConnections_monitor();
	netConnections_monitor();
	staticInf_monitor();
}

//INICIALIZANDO EL GRAFICO PARA EL DISK/IO MONITOR
setInterval('initializer()',1000);