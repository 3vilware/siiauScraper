<script type="text/javascript">
  setTimeout("location.reload()", 2000);
</script>

<?php 
date_default_timezone_set('America/Mexico/Jalisco'); 
$fecha = getdate(); 
$hora = ($fecha["mday"]."/". $fecha["mon"]."/". $fecha["year"]." - ". $fecha["hours"].":". $fecha["minutes"].""); 
$txt="IP: ".$_SERVER['REMOTE_ADDR']." entro: ".$hora . "\n"; //."  | Con el navegador: ".$_SERVER['HTTP_USER_AGENT'];
$fd = fopen ("visitantes.txt", "a+"); 
fputs($fd,$txt); 
fclose($fd); 

			PRINT <<<HERE
		<h5>Los resultados en verde significan que hay cupos, mientras que los resultados en rojo quieren decir que no hay.</h5>
HERE;
	header("Content-type: text/html; charset=utf8");
	$materia = $_GET['materia'];
	$buscable = $_GET['nrc'];

	$url = 'http://consulta.siiau.udg.mx/wco/sspseca.consulta_oferta?ciclop=201720&cup=D&crsep=' . $materia;

	$html = file_get_contents($url);

	preg_match_all("(<TD class=\"tdprofesor\">(.*?)</TD>)", $html, $matches1);
	preg_match_all("(<TD class=tddatos>(.*?)</TD>)", $html, $matches);

	$cupo = $matches[1][6];
	$nrc = $matches[1][0];
	$materia = $matches[1][2];
	$clave = $matches[1][8];
	$profe = $matches1[1][0];
	$total = (sizeof($matches, COUNT_RECURSIVE)/2)-2;
	$cont = 0;
	$encontrado = false;
	
	for($i=0; $i<$total; $i=$i+7)
	{	
		if($matches[1][$i] == $buscable)
		{
			$nrc = $matches[1][$i];
			$cupo = $matches[1][$i+6];
			$encontrado = true;
		}
		else
		{
			if($encontrado==false)
			{
				$cont=$cont+1;
				$profe = $matches1[1][$cont];
			}
		}
		
	}
	
	if($cupo>0)
	{
		$background=mediumseagreen;
		{ ?>
 
			<audio src="alarma.ogg" autoplay></audio>
 
		<?php }

	}
	else
	{
		$background=red;
	}

?>

<style>
.container
{
	background: <?php echo $background; ?>;
	font-family: Helvetica;
	font-size: 19px;
	border-radius: 1em;
	box-shadow: 0px 5px 5px rgba(0,0,0,0.5);
	margin: 1em auto;
	padding: 1em;
	width: 40%;
}
</style>

<div class="container">
        <?="RESULTADOS</br>"?>
        <?="Profesor: <b>" . $profe .'</b><br>'?>
        <?="NRC: <b>" . $nrc .'</b>'?>
        <?="</br>Cupo: <b>" . $cupo .'</b><br>'?>
</div>

