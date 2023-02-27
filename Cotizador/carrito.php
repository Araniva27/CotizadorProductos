<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="css/styles.css">
    <title>Document</title>
</head>
<body>    
	<header>
		<div class="container-fluid" style = "margin-top: 20px">
			<div class="card" style = "background-color: black">
				<div class="card-body text-center" style = "font-size: 25px; color: white">
					Carrito de compra
				</div>
			</div>
		</div>
	</header>
	<main>		
		<?php
			
			session_start();	
			use PHPMailer\PHPMailer\PHPMailer;
			use PHPMailer\PHPMailer\Exception;

			require 'PHPMailer/src/Exception.php';
			require 'PHPMailer/src/PHPMailer.php';
			require 'PHPMailer/src/SMTP.php';

			if(isset($_POST['borrarCarrito'])){				
				$_SESSION['productos'] = array();
				echo "
					<div class='container-fluid' style = 'margin-top: 20px'>
						<div class='alert alert-success' role='alert'>
							Se ha eliminado el carrito de compras
						</div>
					</div>
				";														
			}else{
				echo "";
			}	
			
			if(isset($_POST['procesarFactura'])){
				include 'funciones.php';
				crearPDF($_SESSION['productos']);				
			}


			if(isset($_POST['enviarFactura']))
			{
				include 'funciones.php';
				crearPDF($_SESSION['productos']);
				$correo = $_REQUEST["correo"];
				if($correo != null)
				{
					//Create an instance; passing `true` enables exceptions
					$mail = new PHPMailer(true);

					try 
					{
						//Server settings
						//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
						$mail->isSMTP();                                            //Send using SMTP
						$mail->Host       = 'smtp.gmail.com';    					//Set the SMTP server to send through
						$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
						$mail->Username   = 'correoscotizacionesphp@gmail.com';                     //SMTP username
						$mail->Password   = 'rwfhqawserqodctq';                               //SMTP password
						$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
						$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

						//Recipients
						$mail->setFrom('correoscotizacionesphp@gmail.com', 'COTIZACIONES ONLINE');
						$mail->addAddress($correo); 


						//Attachments
						$mail->addAttachment('pdf/factura_compra.pdf');         //Add attachments

						//Content
						$mail->isHTML(true);                                  //Set email format to HTML
						$mail->Subject = 'COTIZACION';
						$mail->Body    = '<b>Envio de cotización</b>';

						$mail->send();
						echo "
							<div class='container-fluid' style = 'margin-top: 20px'>
								<div class='alert alert-success' role='alert'>
									Se ha enviado el correo
								</div>
							</div>
						";	
									} 
					catch (Exception $e) 
					{
						echo "
						<div class='container-fluid' style = 'margin-top: 20px'>
							<div class='alert alert-danger' role='alert'>
							Message could not be sent. Mailer Error: {$mail->ErrorInfo}
							</div>
						</div>
					";
					}			
				}
				else
				{
					echo "
						<div class='container-fluid' style = 'margin-top: 20px'>
							<div class='alert alert-danger' role='alert'>
								Debe ingresar un correo electronico
							</div>
						</div>
					";	
				}
				
			}

		?>	
		

		</div>
		<div class="container-fluid" style = "margin-top: 10px">
			<div class="row">
				<div class="col-lg-8">
				<table class="table">
					<thead>
						<tr>
							<th scope="col"class = "table-dark">Código</th>
							<th scope="col"class = "table-dark">Producto</th>							
							<th scope="col"class = "table-dark">Cantidad</th>
							<th scope="col"class = "table-dark">Precio($)</th>
							<th scope="col"class = "table-dark">Total($)</th>
						</tr>
					</thead>
					<tbody>
						<?php							
							foreach($_SESSION['productos'] as $producto){
								echo "
									<tr>										
										<td>".$producto[0]."</td>	
										<td>".$producto[1]."</td>										
										<td>".$producto[3]."</td>
										<td".$producto[2]."</td>
										<td>".$producto[2]."</td>
										<td>".$producto[2]*$producto[3]."</td>
									</tr>								
								";
							}
						
						
						?>												
					</tbody>
				</table>
				</div>
				<div class="col-lg-4">
					<div class="container">
						<div class="card" style = "border-color: black;">
							<div class="card-header text-center">
								Monto
							</div>
							<div class="card-body">
								<?php									
									$suma = 0;
									foreach($_SESSION['productos'] as $producto){
										$suma = $suma + ($producto[2]*$producto[3]);
									}
									echo "<h3>El total a pagar es de: $".$suma."</h3>"
								?>
								<hr>
								<form method="POST">
									<div class="row">
										<div class="mb-3">
											<label for="correo" class="form-label">Correo al que se enviará la factura</label>
											<input type="email" class="form-control" id="correo" name = "correo" placeholder="Ingrese el correo electrónico">
										</div>
									</div>
									<div class="container">
										<div class="row">								
											<button type = "submit" class='btn btn-primary'  name ="procesarFactura">Generar PDF</button>
										</div>
										<div class="row" style = "margin-top: 10px">								
											<button type = "submit" class='btn btn-primary'  name ="enviarFactura">Enviar factura</button>
										</div>
									</div>
								</form>
							</div>
							</div>
					</div>
				</div>
			</div>
		</div>
		<form method="POST">
			<div class="container-fluid" style = "margin-top: 20px;">
				<div class="container-fluid">
					<div class = "row">			
						<a href='index.php' class='btn btn-success' role='button'>Volver a comprar</a>
					</div>
				</div>
				<div class="container-fluid" style = "margin-top: 10px">
					<div class = "row">			
						<button class='btn btn-danger'  name ="borrarCarrito">Borrar carrito de compras</button>
					</div>
				</div>					
			</div>
		</form>			
	 </main>
</body>
</html>