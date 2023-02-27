<?php
	require_once 'dompdf/autoload.inc.php';
	use Dompdf\Dompdf;
	function crearPDF($productos){
		// Generar el contenido HTML de la tabla
		date_default_timezone_set('America/El_Salvador');
		$fechaActual = date('d-m-Y');
		$horaActual = date('H:i:s');
	$html = '
		<html>      
			<body
			<div style= "border-color:blue; padding: 10; " >
				<center style = "font-family: Arial, Helvetica, sans-serif; font-size: 30px; font-weight: bold;">Cotización</center>
				<center style = "font-family: Arial, Helvetica, sans-serif; font-size: 15px">Fecha de creación: '.$fechaActual.'</center>
				<center style = "font-family: Arial, Helvetica, sans-serif; font-size: 15px">Hora de creación '.$horaActual.'</center>
			</div>
			<div style = "margin-top: 10px">
				<table style = "width: 100%; border-style: solid;>
				<thead style = "background-color: black;">
				<tr style = "border-style:solid; background-color: black; color: white; font-weight: bold">
					<th style="font-family: Arial, Helvetica, sans-serif;">Código</th>
					<th style="font-family: Arial, Helvetica, sans-serif;">Producto</th>
					<th style="font-family: Arial, Helvetica, sans-serif;">Cantidad</th>
					<th style="font-family: Arial, Helvetica, sans-serif;">Precio($)</th>
					<th style="font-family: Arial, Helvetica, sans-serif;">Total($)</th>
				</tr>
				</thead>			
				<tbody>';
				$suma = 0;
				foreach($productos as $producto){
					$html.='	
						<tr>
							<td><center style="font-family: Arial, Helvetica, sans-serif;">'.$producto[0].'</center></td>
							<td><center style="font-family: Arial, Helvetica, sans-serif;">'.$producto[1].'</center></td>
							<td><center style="font-family: Arial, Helvetica, sans-serif;">'.$producto[3].'</center></td>
							<td><center style="font-family: Arial, Helvetica, sans-serif;">'.$producto[2].'</center></td>					
							<td><center style="font-family: Arial, Helvetica, sans-serif;">'.$producto[2]*$producto[3].'</center></td>
						</tr>
					';
					$suma = $suma + $producto[2]*$producto[3];
				}		 
				$html.=' 			
				</tbody>
			</table>
				<hr>
				<div class="div" style = "margin-top:10px; font-size:19px; text-align: right; float:right; font-size: 23px">
					<p style="font-family: Arial, Helvetica, sans-serif">El monto total a pagar es de: <b>$'.round($suma,2).'</b></p>
				</div>
			</div>
			</body>
		</html>';




		//Creando instancia de Dompdf
		$dompdf = new Dompdf();
		
		//Cargar el html en Dompdf
		$dompdf->loadHtml($html);
		
		//Renderizar el html como pdf
		$dompdf->render();

		$pdf = $dompdf->output();
		file_put_contents('pdf/factura_compra.pdf',$pdf);

		echo "
			<div class='container-fluid' style = 'margin-top: 20px'>
				<div class='alert alert-success' role='alert'>
					Se ha generado el pdf
				</div>
			</div>
		";	

	}

?>