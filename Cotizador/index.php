<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
   <title>Cotizador</title>
</head>
<body>
   <header>
		<div class="container" style = "margin-top: 20px">
			<div class="card" style = "background-color: black">
				<div class="card-body text-center" style = "font-size: 25px; color: white">
					Cotizador de productos
				</div>
			</div>
		</div>
	</header>
	<main>
		<div class="container" style = "margin-top:20px">
			<div class = "container-fluid">
				<div class = "row">			
					<a href='carrito.php' class='btn btn-success' role='button'>Carrito de compras</a>
				</div>
			</div>
			<div class="card" style = "margin-top: 10px">
				<div class="card-header text-center" style = "font-size: 20px">
					Productos
				</div>
				<div class="card-body">	
					<form method="POST">
						<?php
							//Inicio de sesión
							session_start();
							//Validando si esta definida la variable de sesión
							if(!isset($_SESSION['productos'])){
								$_SESSION['productos'] = array();
							}

							$xml = simplexml_load_file("Productos.xml");

							if(isset($_POST['agregarProducto'])){
								$codigoProducto = $_POST['agregarProducto'];	
								$nombreP = $_POST['nombreProducto'.$codigoProducto];
								$precioP	 = $_POST['precioProducto'.$codigoProducto];
								$cantidad = $_POST['cantidadProducto'.$codigoProducto];
								if($cantidad >= 1){
									//Seleccionando el producto por su código
									$productoSeleccionado = $xml->xpath('/productos/producto[@codigo="'.$codigoProducto.'"]');
																	
									if(!empty($productoSeleccionado)){
										//Tomando los datos del producto seleccionado
										$codigo = $productoSeleccionado[0]->codigo;
										$nombre = $productoSeleccionado[0]->nombre;
										$precio = $productoSeleccionado[0]->precio;
										$total = $precio * $cantidad;										
									}
										
										array_push($_SESSION['productos'], array($codigoProducto,$nombreP,$precioP,$cantidad, $total));												
										echo "
											<div class='alert alert-success' role='alert'>
												El producto ".$nombre." ha sido agregado al carrito de compras
											</div>
										";																																																																												
									}
								}else{
									echo "
										<div class='alert alert-warning' role='alert'>
											Debe de ingresar una cantidad de producto mayor a cero
								 		</div>
									";
								}															

							foreach($xml ->producto as $producto){
								echo "
									<div class='card' style = 'border-color: black; margin-top: 10px'>
										<div class='card-body'>
											<div class='row'>
												<p><b>Codigo: </b>".$producto->codigo."</p>
											</div>
											<div class='row'>
												<input type = 'hidden' name = 'nombreProducto".$producto->codigo."' value = '".$producto->nombre."'>
												<p><b>Nombre del producto: </b>".$producto->nombre."</p>
											</div>
											<div class='row'>
												<input type = 'hidden' name = 'precioProducto".$producto->codigo."' value = '".$producto->precio."'>
												<p><b>Precio ($): </b>".$producto->precio."</p>
											</div>
											<div class='row'>
												<div class='col-lg-2' style = 'padding-right: 0'>
													<b>Cantidad ($): </b>
												</div>
												<div class='col-lg-6' style = 'padding: 0'>
													<div class='mb-3'>													
														<input type='number' class='form-control' id='cantidadProducto' name = 'cantidadProducto".$producto->codigo."' placeholder='Ingrese cantidad del producto'>
													</div>
												</div>
											</div>
											<div class='container'>
												<div class='row'>										
													<button type='submit' class='btn btn-primary' name = 'agregarProducto' value = '".$producto->codigo."'>Agregar al carrito</button>
												</div>
											</div>
										</div>
									</div>
								";
							}							
						?>
					</form>				
				</div>
			</div>
		</div>
	</main>
</body>
</html>