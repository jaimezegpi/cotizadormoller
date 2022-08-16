<?php

if ( isset($_GET['valor_propiedad']) ){$valor_propiedad = $_GET['valor_propiedad'];}else{$valor_propiedad = 3000;}
if ( isset($_GET['valor_solicitado']) ){$valor_solicitado = $_GET['valor_solicitado'];}else{$valor_solicitado = 3000;}
if ( isset($_GET['plazo']) ){$plazo = $_GET['plazo'];}else{$plazo = 30;}
$meses = ($plazo*12);
$tasa = 5;//porcentual
$dividendo_uf = pmt(( $tasa ),$meses,($valor_solicitado));
$seguro_deg = (($dividendo_uf/100)*0.309904);
$seguro_incendio_sismo = ($dividendo_uf/100)*1.29971;
$dividendo_con_seguro = $dividendo_uf+$seguro_deg+$seguro_incendio_sismo;
$cae = ( $tasa/100*0.5)+( $tasa/100*0.65);
$costo_credito = $dividendo_con_seguro;
$total_dividendo_uf = $dividendo_con_seguro;
$total_dividendo_cpc = $total_dividendo_uf*33000;
$renta_liquida = $total_dividendo_cpc*4;
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

function pmt($interest, $months, $loan) {
	$months = $months;
	$interest = $interest / 1200;
	$amount = $interest * -$loan * pow((1 + $interest), $months) / (1 - pow((1 + $interest), $months));
	return number_format($amount, 2);
}
