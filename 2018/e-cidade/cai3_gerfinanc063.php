<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//echo($HTTP_SERVER_VARS['QUERY_STRING']);

?>
<link href="estilos.css" rel="stylesheet" type="text/css">
<form name='form1' action=''>
<table border="1">
<tr align='center'>
<td colspan="3">
<input type='hidden' name='vt' value='<?=$valor?>'>
<strong>Valor do parcelamento sem desconto: </strong><font id="vt"></font>
<?
echo "<script>document.getElementById('vt').innerHTML = \"$valor\"</script>";
?>
<br>
<input type='hidden' name='vtcomdesconto' value='<?=$valorcomdesconto?>'>
<input type='hidden' name='temdesconto' value='<?=$temdesconto?>'>
<strong>Valor do parcelamento com desconto: </strong><font id="vtcomdesconto"></font>
<?
echo "<script>document.getElementById('vtcomdesconto').innerHTML = \"$valorcomdesconto\"</script>";
echo "<script>document.getElementById('temdesconto').innerHTML = \"$temdesconto\"</script>";
?>
</td>
</tr>

<?

echo "<tr bgcolor='#6699cc'> <pre>";
$linha = -1;
$tipo1 = split("-",$tiposparc);

//print_r($tipo1);
//echo "<br><br>";

$ultmaxparc = null;
$ultparc    = null;

for ($contatipo1 = 0; $contatipo1 < sizeof($tipo1); $contatipo1++) {
  $tipo2 = split("=", $tipo1[$contatipo1]);

  $tipoparc = $tipo2[0];
  $maxparc  = $tipo2[1];
  $descmul  = $tipo2[2];
  $descjur  = $tipo2[3];
  $forma		= $tipo2[5];
  $descvlr  = $tipo2[6];
  $vlrmin		= $tipo2[7];
  $tipovlr  = $tipo2[8];
  $minparc  = $tipo2[9];

  if ($ultparc == null) {
    $ultparc  = $tipo2[9];
  }

  if ($forma == 2) {
    $ultparc = 3;
  }

	$registros=split("=", $valoresportipo);

	$valtotal			= 0;

	for ($x=0; $x < sizeof($registros); $x++) {

		if ($registros[$x] == "") {
			continue;
		}

		$valores = split("-", $registros[$x]);
	  $valdesconto	= 0;

		$k03_tipo					= $valores[0];
		$k00_cadtipoparc	= $valores[1];
		$k00_vlrhis				= $valores[2];
		$k00_vlrcor				= $valores[3];
		$k00_juros 				= $valores[4];
		$k00_multa 				= $valores[5];
		$k00_desconto			= $valores[6];
		$k00_total   			= $valores[7];

    if ($k00_cadtipoparc > 0 && $k40_aplicacao != '2') {

      $valdescontocorrecao = 0;
      if ($tipovlr == 1) {
        $valdescontocorrecao = ($k00_vlrcor - $k00_vlrhis) * $descvlr / 100;
      } else if ($tipovlr == 2) {
        $valdescontocorrecao = ($k00_vlrcor) * $descvlr / 100;
      }

			$valdesconto	+= $valdescontocorrecao + ($k00_juros * $descjur / 100) + ($k00_multa * $descmul / 100);

			//echo "<br>Desconto: ".$valdescontocorrecao." + (".$k00_juros." * ".$descjur." / 100) + (".$k00_multa." * ".$descmul." / 100) - VALOR:".$k00_vlrcor." + (".$k00_juros." + ".$k00_multa.") - ".$valdesconto."<br>";
			$valtotal			+= $k00_vlrcor + ($k00_juros + $k00_multa) - $valdesconto;

		} else {
			$valtotal			+= $k00_vlrcor + $k00_juros + $k00_multa;
		}
		//echo "k03_tipo: $k03_tipo - k00_cadtipoparc: $k00_cadtipoparc - descjur: $descjur - k00_vlrcor: $k00_vlrcor - valtotal: $valtotal - vlrmin: $vlrmin<br>";
	}

  if ($ultmaxparc == null) {
    $ultmaxparc = $ultparc;
  }

  // adiciona desconto de valor k42_minentrada, pois como é obrigado a dar essa entrada deve se descontar
  // do resto da divida e entao gerar as parcelas respeitando as regras de parcelamento

  $entradaminima = 0;

  if ($tipo2[4] > 0) {
    $entradaminima = $valtotal * $tipo2[4] / 100;
  }

  $valtotal = $valtotal - $entradaminima;

  if ((round($valtotal/$ultparc,2) >= $vlrmin ) and ($ultparc >= $ultmaxparc)) {

    for ($parcela = $ultparc; $parcela <= $maxparc; $parcela++) {

      $i = $parcela;

      if ($i < $minparc) {
      	continue;
      }
      if (round($valtotal/$i,2) < $vlrmin) {

        //exit;
        break;
      }

      if($i%2 == 0){
        $cor='#6699cc';
      }else{
        $cor='#99ccaa';
      }
      if($linha == 2){
        echo "</tr>";
        echo "<tr bgcolor='$cor'>";
      }

      if ($tipo2[4] > 0) {
        $entradaminima = number_format($valtotal * $tipo2[4] / 100,2,",",".");

      } else {
        $entradaminima = number_format(($valtotal/$i),2,",",".");
      }

      echo "<td nowrap align='left' valign='top'>
              <input type='radio' name='val' id='val$i' onClick=\"parent.document.form1.parc.value='".($i - 1)."';parent.js_valparc($i, $vlrmin,$temdesconto)\" value=''>$i X R$<font id='$i'></font>
            </td>";
      if ($linha == 2) {
        $linha = 0;
      } else{
        $linha +=1;
      }

      echo "<script>document.getElementById('$i').innerHTML = \"".str_pad(number_format(($valtotal/$i),2,",","."),strlen(round($valtotal/$i)),",",STR_PAD_LEFT)."\"</script>";

    }

    $ultparc = $parcela;
    $ultmaxparc = $maxparc;

  }

}

?>
</table>
</form>
