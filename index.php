
<?php
$debug = false;
if ( isset($_POST['valor_propiedad']) ){$valor_propiedad = $_POST['valor_propiedad'];}else{$valor_propiedad = 3000;}
if ( isset($_POST['valor_solicitado']) ){$valor_solicitado = $_POST['valor_solicitado'];}else{$valor_solicitado = 3000;}
if ( isset($_POST['plazo']) ){$plazo = $_POST['plazo'];}else{$plazo = 30;}
$uf = base_contactUFSource();
$meses = ($plazo*12);
$tasa = 5;//porcentual
$dividendo_uf = base_pmt(( $tasa ),$meses,($valor_solicitado));
$seguro_deg = (($dividendo_uf/100)*0.309904);
$seguro_incendio_sismo = ($dividendo_uf/100)*1.29971;
$dividendo_con_seguro = $dividendo_uf+$seguro_deg+$seguro_incendio_sismo;
$cae = ( $tasa/100*0.5)+( $tasa/100*0.65);
$costo_credito = $dividendo_con_seguro;
$total_dividendo_uf = $dividendo_con_seguro;
$total_dividendo_cpc = $total_dividendo_uf*$uf;
$renta_liquida = $total_dividendo_cpc*4;
if ( $debug ){
	echo $dividendo_uf;
	echo '<br>Dividendo UF:'.$dividendo_uf;
	echo '<br>Seguro Deg:'.$seguro_deg;
	echo '<br>seguro_incendio_sismo:'.$seguro_incendio_sismo;
	echo '<br>dividendo_con_seguro:'.$dividendo_con_seguro;
	echo '<br>cae:'.$cae;
	echo '<br>costo_credito:'.$costo_credito;
	echo '<br>total_dividendo_uf:'.$total_dividendo_uf;
	echo '<br>total_dividendo_cpc:'.$total_dividendo_cpc;
	echo '<br>Renta Liquida:'.$renta_liquida;
	echo $uf;
}

function base_pmt($interest, $months, $loan) {
	$months = $months;
	$interest = $interest / 1200;
	$amount = $interest * -$loan * pow((1 + $interest), $months) / (1 - pow((1 + $interest), $months));
	return number_format($amount, 2);
}

function base_contactUFSource(){
	$apiUrl = 'https://mindicador.cl/api';
	//Es necesario tener habilitada la directiva allow_url_fopen para usar file_get_contents
	if ( ini_get('allow_url_fopen') ) {
		$json = file_get_contents($apiUrl);
	} else {
		//De otra forma utilizamos cURL
		$curl = curl_init($apiUrl);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$json = curl_exec($curl);
		curl_close($curl);
	}
	$dailyIndicators = json_decode($json);
	$uf = $dailyIndicators->uf->valor;

	if (!$uf){
		return 33333;
	}else{
		return $uf;
	}
	
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cotizador</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/desktop.css">
</head>
<body>
<div id="moller-cotizador-container" class="cotizador-container" >
	<div class="cotizador-container-title text-align-center"><h1>Simula tu dividendo<h1></div>
	<div class="cotizador-container-body">
		<div class="legend">
			<p>Simula tu crédito si ya conoces el valor de la vivienda<br>Valor UF al día $<span class="cotizador-container-uf"><?php echo number_format($uf, 1, ',', '.'); ?></span></p>
		</div>
		<div class="cotizador-container-form">
			<form action="" method="POST">
				<div class="form-item row">
					<div class="col-sm-12 col-md-3">Monto a financiar</div>
					<div class="col-sm-12 col-md-9 cotizador-container-amount">
						<div class="row">
							<div class="col-sm-2"><b>UF</b> 1.000</div>
							<div class="col-sm-8"><input type="range" id="input-amount" name="valor_propiedad" min="1000" max="30000" step="100" value='<?php echo $valor_propiedad; ?>' onChange="base_refreshOnChangeValues()" onmousemove="base_onTouchOrMouseMove(this,'.input-amount-value')" ontouchmove="base_onTouchOrMouseMove(this,'.input-amount-value')"></div>
							<div class="col-sm-2"><b>UF</b> <span class="input-amount-value"><?php echo number_format($valor_propiedad, 1, ',', '.'); ?></span></div>
						</div>
					</div>

				</div>
				<div class="form-item row">
					<div class="col-sm-12 col-md-3">Valor Solicitado</div>
					<div class="col-sm-12 col-md-9 cotizador-container-maount-s">
						<div class="row">
							<div class="col-sm-2"><b>UF</b> 1.000</div>
							<div class="col-sm-8"><input type="range" id="input-amount-s" name="valor_solicitado" min="1000" max="30000" step="100" value='<?php echo $valor_solicitado; ?>' onChange="base_refreshOnChangeValues()" onmousemove="base_onTouchOrMouseMove(this,'.input-amount-s-value')" ontouchmove="base_onTouchOrMouseMove(this,'.input-amount-s-value')" ></div>
							<div class="col-sm-2"><b>UF</b> <span class="input-amount-s-value"><?php echo number_format($valor_solicitado, 1, ',', '.'); ?></span></div>
						</div>
					</div>

				</div>
				<div class="form-item row">
					<div class="col-sm-12 col-md-3">Plazo</div>
					<div class="col-sm-12 col-md-9 cotizador-container-years">
						<div class="row">
							<div class="col-sm-2">10 Años</div>
							<div class="col-sm-8"><input type="range" id="input-years" name="plazo" onChange="base_refreshOnChangeValues()" min="10" max="30" step="5" value='<?php echo $plazo; ?>'  onmousemove="base_onTouchOrMouseMove(this,'.input-years-value')" ontouchmove="base_onTouchOrMouseMove(this,'.input-years-value')"></div>
							<div class="col-sm-2"><span class="input-years-value"><?php echo $plazo; ?></span> Años</div>
						</div>
					</div>
				</div>
				<div class="form-item row">
					<div class="col-sm-12 col-md-12"><small>*El valor del dividendo es preferencial y no incluye los costos asociados de los seguros.</small></div>
				</div>
				<div class="form-item row footer">
					<div class="col-sm-12 col-md-12 flex">
						<div class="flex ">
							<button><h3>Calcular Dividendo</h3></button>
						</div>
						<div class="flex flex-column flex-center">
							<div class="flex result">
								<h3>Tu dividendo a : <b>$<span class="result-dividendo"><?php echo number_format($total_dividendo_cpc, 1, ',', '.'); ?></span></b></h3>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>

<script type="text/javascript" src="js/main.js"></script>
</body>
</html>