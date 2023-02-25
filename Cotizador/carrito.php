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