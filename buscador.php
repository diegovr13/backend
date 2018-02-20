<?php
//Abrir el archivo JSON para tener acceso a los datos
$data = file_get_contents("data-1.json");
$propiedades = json_decode($data);
//Variables para aplicar filtros
$filtroAply = (isset($_POST["fAply"]) && boolval($_POST["fAply"]));
$filtroCiudad = $_POST["fCiudad"];
$filtroTipo = $_POST["fTipo"];
$filtroPrecioIni = $_POST["fPrecioIni"];
$filtroPrecioFin = $_POST["fPrecioFin"];
//Variables para hacer filtros
$matchCiudad = true;
$matchTipo = true;
$matchPrecio = true;
//Correr filtros
try {
	//Lee y Recorre el JSON 
	foreach($propiedades as $key => $json) {
		//variables para filtrar
		$precio = str_ireplace(["$",","], "", $json->Precio);
		$precio = intval($precio);
		$ciudadf = str_ireplace(["$",","], "", $json->Ciudad);
		$tipof= str_ireplace(["$",","], "", $json->Tipo);
		//Filto para precio
		$matchPrecio = ($precio >= intval($filtroPrecioIni) && $precio <= intval($filtroPrecioFin));
		//Condicional para filtrar ciudad y tipo
		if($filtroAply){
			$matchCiudad =  ($ciudadf==$filtroCiudad || $filtroCiudad=="" || (!empty($filtroCiudad) && $json->Ciudad == $filtroCiudad));
			$matchTipo = ($tipof==$filtroTipo || $filtroTipo=="" || (!empty($filtroTipo) && ($json->Tipo == $filtroTipo)));
		}
		//Sí Aplica filtro y no es coincidente continua el ciclo sin imprimir un elemento;
		if($filtroAply && !($matchCiudad && $matchTipo && $matchPrecio)){
			continue;
		}
?>
 <div class="row">
   <div class="col m12">
      <div class="card horizontal itemMostrado">
        <img src="img/home.jpg">
        <div class="card-stacked">
          <div class="card-content">
            <?php
              foreach($json as $keyProp => $prop){
                $class = ($keyProp=="Precio") ? 'class="precioTexto"' : null;
                if($keyProp=="Id"){ continue; }
                echo "<p><strong>$keyProp:</strong> <span $class>$prop</span><p>";
              }
             ?>
          </div>
          <div class="card-action">
            <a href="#" class="precioTexto">VER MÁS</a>
          </div>
        </div>
      </div>
    </div>
 </div>
<?php
  }
}catch (Exception $e) {
  echo $e->getMessage();
}
?>