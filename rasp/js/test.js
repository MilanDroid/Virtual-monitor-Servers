var canvasDisk_io = document.getElementById("disk_io");
var canvasRam = document.getElementById("ram");
var canvasCpu = document.getElementById("cpu");
var canvasBd = document.getElementById("bd_conn");
var canvasNet = document.getElementById("server_conn");
var movimiento = 4;

var diskIo = {
	lienzo: canvasDisk_io.getContext("2d"),
	alto: canvasDisk_io.height,
	ancho: canvasDisk_io.width,
	linea: "#00c600",
	linea2: "red",
	read: 0,
	write: 0,
	lmax: 0,
	rdata: new Array(),
	wdata: new Array(),
};

var ram = {
	lienzo: canvasRam.getContext("2d"),
	alto: canvasRam.height,
	ancho: canvasRam.width,
	linea: "#00c600",
	lmax: 0,
	rdata: new Array()
};

var cpuPercent = {
	lienzo: canvasCpu.getContext("2d"),
	alto: canvasCpu.height,
	ancho: canvasCpu.width,
	linea: "#00c600",
	lmax: 0,
	rdata: new Array()
};

var bdConnections = {
	lienzo: canvasBd.getContext("2d"),
	alto: canvasBd.height,
	ancho: canvasBd.width,
	linea: "#00c600",
	lmax: 0,
	rdata: new Array()
};

var netConnections = {
	lienzo: canvasNet.getContext("2d"),
	alto: canvasNet.height,
	ancho: canvasNet.width,
	linea: "#00c600",
	lmax: 0,
	rdata: new Array()
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
	if(array.length >= 86){
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
			diskIo.wdata.unshift(datos.d_write);
			diskIo.rdata.unshift(datos.d_read);

			diskIo.read = Math.max(...diskIo.rdata);
			diskIo.write = Math.max(...diskIo.wdata);

			if(diskIo.write > diskIo.read){
				diskIo.lmax = diskIo.write + 1;
			}
			else{
				diskIo.lmax = diskIo.read + 1;
			}

			$('#w_disk_io').html("Escritura: "+datos.d_write+" Kb/s");
			$('#r_disk_io').html("Lectura: "+datos.d_read+" Kb/s");

			diskIo.lienzo.clearRect(0, 0, diskIo.ancho, diskIo.alto);
			calculos(diskIo.rdata, diskIo.linea2, diskIo.lienzo, diskIo.lmax, diskIo.alto);
			calculos(diskIo.wdata, diskIo.linea, diskIo.lienzo, diskIo.lmax, diskIo.alto);
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
			ram.rdata.unshift(datos.memory);
			ram.lmax = Math.max(...ram.rdata) + 1;

			ram.lienzo.clearRect(0, 0, ram.ancho, ram.alto);
			calculos(ram.rdata, ram.linea, ram.lienzo, ram.lmax, ram.alto);
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
			cpuPercent.rdata.unshift(datos.load);
			cpuPercent.lmax = Math.max(...cpuPercent.rdata) + 1;

			cpuPercent.lienzo.clearRect(0, 0, cpuPercent.ancho, cpuPercent.alto);
			calculos(cpuPercent.rdata, cpuPercent.linea, cpuPercent.lienzo, cpuPercent.lmax, cpuPercent.alto);
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
			bdConnections.rdata.unshift(datos.bd_conn);
			bdConnections.lmax = Math.max(...bdConnections.rdata) + 1;

			bdConnections.lienzo.clearRect(0, 0, bdConnections.ancho, bdConnections.alto);
			calculos(bdConnections.rdata, bdConnections.linea, bdConnections.lienzo, bdConnections.lmax, bdConnections.alto);
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
			netConnections.rdata.unshift(datos.net_conn);
			netConnections.lmax = Math.max(...netConnections.rdata) + 1;

			netConnections.lienzo.clearRect(0, 0, netConnections.ancho, netConnections.alto);
			calculos(netConnections.rdata, netConnections.linea, netConnections.lienzo, netConnections.lmax, netConnections.alto);
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
}

//INICIALIZANDO EL GRAFICO PARA EL DISK/IO MONITOR
setInterval('initializer()',1000);