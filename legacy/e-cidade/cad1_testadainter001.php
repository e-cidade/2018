<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("j14_nome");

$cllote = new cl_lote;
$cllote->rotulo->label();
$cltesinter = new cl_tesinter;
$cltesinter->rotulo->label();
$cltesinterlote = new cl_tesinterlote;
$cltesinterlote->rotulo->label();

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$intNumLinhasVolta = 0;

if (isset ($enviar)) {

	$rsLotes = $cllote->sql_record($cllote->sql_query('', '*', '', " j34_setor = '".str_pad($j34_setor,4,"0",STR_PAD_LEFT)."' and j34_quadra = '".str_pad($j34_quadra,4,"0",STR_PAD_LEFT)."'"));
	$matriz  = "";
	$car     = "";
	for ($i=0; $i<$cllote->numrows; $i++){
		db_fieldsmemory($rsLotes, $i);
		$idbql      = "idbql".$i;
		$orientacao = "origem".$i;
		$outro      = "outro".$i;
		$testad     = "j39_testad".$i;
		$testle     = "j39_testle".$i;

		if( $$testad != '0' || $$testle != '0' && ( $$idbql != "0" || $$outro != "0" ) ){
		  $matriz    .= $car.$$idbql."-".$$orientacao."-".$$testad."-".$$testle."-".$$outro;
		}

		$car = "X";
	}

  echo "<script> parent.document.form1.testadainter.value = '".$matriz."'; </script>";
	echo "<script> parent.db_iframe.hide(); </script>";
}

if ($idbql != '') {
  $rsDadosLote = $cllote->sql_record($cllote->sql_query(null,'j34_lote',null," j34_idbql = {$idbql} "));
  if ($cllote->numrows > 0) {
    db_fieldsmemory($rsDadosLote,0);
  }
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<script>
function js_controlaIdbql(obj,idlinha){

  if ( obj.value != '0' ){

    document.getElementById('outro'+idlinha).disabled = true;

  } else {

    document.getElementById('outro'+idlinha).disabled = false;

  }

	selects = document.getElementsByTagName('select');
	for(i=0;i<selects.length;i++){
	 	if(selects[i] != obj && selects[i].value != 0 && selects[i].name.substr(0,5)=='idbql'){
		  if(selects[i].value == obj.value){
		    alert('Código do lote já selecionado !');
		    obj.value = 0;
		    document.getElementById('outro'+idlinha).disabled = false;
		  }
		}
	}
}

function js_controlaOutros(obj,idlinha){

  if ( obj.value != '0' ){

    document.getElementById('idbql'+idlinha).disabled = true;

  } else {

    document.getElementById('idbql'+idlinha).disabled = false;

  }

  selects = document.getElementsByTagName('select');

	for(i=0;i<selects.length;i++){

	 	if(selects[i] != obj && selects[i].value != 0 && selects[i].name.substr(0,5)=='idbql'){

		  if(selects[i].value == obj.value){

		    alert('Codigo do lote ja selecionado !');
		    obj.value = 0;
		  }
		}
	}
}

function js_controlaOrigem(obj,idlinha){
	selects = document.getElementsByTagName('select');
	for(i=0;i<selects.length;i++){
	 	if(selects[i] != obj && selects[i].value != 0 && selects[i].name.substr(0,6)=='origem'){
		  if(selects[i].value == obj.value){
		    alert('Não pode ser cadastrado mais de um lote para a mesma orientação !');
		    obj.value = 0;
		  }
		}
	}
}

function js_addLinhaZero(){
  document.getElementById("linha0").style.display = "";
}
function js_addLinha(idlinha){
	if(idlinha > 0){
    eval('document.getElementById("nlinha'+(idlinha-1)+'").disabled = true;');
    eval('document.getElementById("nlinha'+(idlinha-1)+'").disabled = true;');
	}else{
    document.getElementById("mostrarlinhas").disabled = true;
	}
	// seta "" para o display para mostrar a linha
  eval('document.getElementById("linha'+idlinha+'").style.display = "";');
}
function js_delLinha(idlinha){
	if(idlinha > 0){
    eval('document.getElementById("nlinha'+(idlinha-1)+'").disabled = false;');
	}else{
    document.getElementById("mostrarlinhas").disabled = false;
	}
  eval('document.getElementById("linha'+idlinha+'").style.display = "none";');
	js_limpaCamposLinha(idlinha);
}
function js_limpaCamposLinha(idlinha){
  eval('document.getElementById("j39_testad'+(idlinha)+'").value = 0;');
  eval('document.getElementById("j39_testle'+(idlinha)+'").value = 0;');
  eval('document.getElementById("idbql'+(idlinha)+'").value = 0;');
  eval('document.getElementById("origem'+(idlinha)+'").value = 0;');
  eval('document.getElementById("outro'+(idlinha)+'").value = 0;');
}
function js_checaNum(obj){
	if (obj.value == ''){
		obj.value = 0;
	}
  var valor = new Number(obj.value);
  if (isNaN(valor)) {
		alert('Valor invalido para Medida');
		obj.value = '';
    obj.focus();
  }
}
function js_checa3(){
	var objForm = document.form1;
	var bValidacao = false;
	for(i=0;i<objForm.length;i++){
    bValidacao = js_validaLinha(i);
	 	if(!bValidacao){
		  return false;
	  }
	}
}
function js_validaLinha(idLinha){
	var testad = 0;
	var testle = 0;
	var idbql  = 0;
	var origem = 0;
  eval('testad = document.getElementById("j39_testad'+(idLinha)+'").value;');
  eval('testle = document.getElementById("j39_testle'+(idLinha)+'").value;');
  eval('idbql  = document.getElementById("idbql'+(idLinha)+'").value;');
  eval('origem = document.getElementById("origem'+(idLinha)+'").value;');
	if( (testad != 0 || testle != 0 || idbql != 0) && origem == 0){
		alert('Campo orientação é obrigatorio !');
		return false;
	}else{
    return true;
	}

}

function js_desabilita(dis){

  alert('dfgasdfasdfhaksd');

}

</script>
</head>
<body class="body-default">

<div class="container">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <form name="form1" method="post" >
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?php
	$rsLotes = $cllote->sql_record($cllote->sql_query(null,' * ',null," j34_setor = '".str_pad($j34_setor,4,"0",STR_PAD_LEFT)."' and j34_quadra = '".str_pad($j34_quadra,4,"0",STR_PAD_LEFT)."' "));

	if ($cllote->numrows > 0) {

		echo "<table width='30%' border='0' cellspacing='0' align='left'>";
		echo "  <tr align='left' width='100%'>"."\n";
		echo "    <td align='left' >$Lj34_setor</td>";
		echo "    <td align='left' >$j34_setor</td>";
		echo "    <td align='left' >$Lj34_quadra</td>";
		echo "    <td align='left' >$j34_quadra</td>";
		echo "  </tr>\n";
		echo "</table> <br><br>\n";

		echo "<table width='60%' cellspacing='0' style='border: 1px solid #000000;'>";
		echo "<tr align='center' width='100%' style='border: 1px solid #000000;'>"."\n";
		echo "  <td class='table_header' align='center' width='15%'>$Lj34_idbql</td>";
		echo "  <td class='table_header' align='center' ><b>Outro:</b></td>";
		echo "  <td class='table_header' align='center' ><b>Orientação:</b></td>";
		echo "  <td class='table_header' align='center' ><b>Testada MI:</b></td>";
		echo "  <td class='table_header' align='center' ><b>Testada Medida:</b></td>";
		echo "  <td class='table_header' align='center' width='20%'><b>Ação</b>";
		echo "    <input type='button' id='mostrarlinhas' name='mostrarlinhas' value='incluir Novo' disabled onClick='js_addLinhaZero();'> "; // botao nova linha
		echo "  </td>";
		echo "</tr>\n";

		$sqlOutros   = " select 0 as j92_sequencial, 'Nenhum' as j92_descr  ";
    $sqlOutros  .= " union ";
		$sqlOutros  .= " select j92_sequencial,j92_descr from tesintertipo ";
		$rsOutros    = db_query($sqlOutros);
		$intOutros   = pg_numrows($rsOutros);
		for($iOutros=0;$iOutros<$intOutros;$iOutros++){
			db_fieldsmemory($rsOutros,$iOutros);
			$arrayOutros[$j92_sequencial] = $j92_descr;
		}

		$sqlIdbql   = " select 0 as j34_idbql, 'Nenhum' as descr from lote union ";
		$sqlIdbql  .= " select j34_idbql, j34_lote::text as descr ";
		$sqlIdbql  .= "   from lote ";
		$sqlIdbql  .= " where j34_setor  = '".@str_pad($j34_setor,4,"0",STR_PAD_LEFT)."'";
    $sqlIdbql  .= "   and j34_quadra = '".@str_pad($j34_quadra,4,"0",STR_PAD_LEFT)."'";
    $sqlIdbql  .= "   and j34_lote  != '".@str_pad($j34_lote,4,"0",STR_PAD_LEFT)."'";
		$rsIdbql    = db_query($sqlIdbql);
		$intIdbql   = pg_numrows($rsIdbql);
		for($iIdbql=0;$iIdbql<$intIdbql;$iIdbql++){
			db_fieldsmemory($rsIdbql,$iIdbql);
			$arrayIdbql[$j34_idbql] = $descr;
		}

		$sqlOri   = "select 0 as j64_sequencial, 'Nenhum' as j64_descricao ";
		$sqlOri  .= " union ";
		$sqlOri  .= "select j64_sequencial, j64_descricao from orientacao ";

		$rsOri    = db_query($sqlOri);
		$intOri   = pg_numrows($rsOri);
		for($iOri=0;$iOri<$intOri;$iOri++){
			db_fieldsmemory($rsOri,$iOri);
			$arrayOri[$j64_sequencial] = $j64_descricao;
		}

		if (isset ($matrizvolta)) {
	    $matrizvolta = split("X", $matrizvolta);
	    $intNumLinhasVolta = sizeof($matrizvolta);
		}

    echo "<tbody style='background-color:#FFFFFF'>";

    $aDesabilitar = array();

    for ($fq = 0; $fq < 20; $fq ++) {

			$temvalor = false;
			if($fq < $intNumLinhasVolta) {
				$matrizdados = split("-", $matrizvolta[$fq]);
				$temvalor = true;
			} else {
				$temvalor = false;
			}

			if($fq == 0){
				$stylelinha = '';
			}else{
				$stylelinha = "style='display:none'";
			}

   			$disabled = '';
      		$disabledIdbql = '';
      		$disabledOutros = '';

			if (isset($temvalor) && $temvalor == true && $intNumLinhasVolta > $fq) {

				$stylelinha = '';
			  $x = "idbql".$fq;
			  $$x = $matrizdados[0];

			  $x = "origem".$fq;
			  $$x = $matrizdados[1];

			  $x = "j39_testad".$fq;
			  $$x = $matrizdados[2];

			  $x = "j39_testle".$fq;
	    	$$x = $matrizdados[3];

        $x = "outro".$fq;
	    	$$x = $matrizdados[4];

        if($fq < $intNumLinhasVolta-1){
				  $disabled = 'disabled';
				}

        if ($matrizdados[0] != "" && $matrizdados[0] != "0" ){
          array_push($aDesabilitar,"outro$fq");
        }

        if ($matrizdados[4] != "" && $matrizdados[4] != "0" ){
          array_push($aDesabilitar,"idbql$fq");
        }

			}

			$aux = "j39_testad".$fq;
			if(!isset($$aux) || $$aux==''){
			  $$aux = 0;
			}
			$aux = "j39_testle".$fq;
			if(!isset($$aux) || $$aux==''){
			  $$aux = 0;
			}

			$x = "j39_testle".$fq;
			echo "<tr id='linha".$fq."' $stylelinha >\n";

      echo "<td class='linhagrid' align='center'>";
      db_select("idbql$fq",$arrayIdbql,true,$db_opcao,"onChange='js_controlaIdbql(this,".$fq.")'; ");
			echo "</td>";

			echo "<td class='linhagrid' align='center'>";
      db_select("outro$fq",$arrayOutros,true,$db_opcao,"onChange='js_controlaOutros(this,".$fq.");'");
			echo "</td>";
			echo "<td class='linhagrid' align='center'>";
      db_select("origem$fq",$arrayOri,true,$db_opcao,"onChange='js_controlaOrigem(this,".$fq.");'");
			echo "</td>";

			echo "<td class='linhagrid' align='center'>";
			db_input('j39_testad', 16,4, true, 'text', '','onChange="js_checaNum(this);"', 'j39_testad'.$fq);
			echo "</td>";
			echo "<td class='linhagrid' align='center'>";
			db_input('j39_testle', 16,4, true, 'text', '','onChange="js_checaNum(this);"', 'j39_testle'.$fq);
			echo "</td>";
			echo "<td class='linhagrid' align='center' nowrap>";
			echo "<input type='button' id='nlinha".$fq."' name='nlinha".$fq."' $disabled value='Novo' onClick='js_addLinha(".($fq+1).");'> "; // botao nova linha
			echo "<input type='button' id='elinha".$fq."' name='elinha".$fq."' value='Excluir' onClick='js_delLinha(".$fq.");'> ";  // botao exclui linha
			echo "</td>";
			echo "</tr>\n";
		}

		$fq--;
    echo "</tbody>"; ?>

	       <tr>
	         <td class='table_header' colspan="9" align="center" style='border: 1px solid #000000;'>
	           <input type="submit" name="enviar" value="Enviar" onclick="return js_checa3();">
	           <input type="button" name="Fechar" value="Fechar" onClick="parent.db_iframe.hide();">
			       <?
             db_input('idbql', 16,"", true, 'hidden', '','', '');
             ?>
	         </td>
	       </tr>
	     </table>

			<?
	} else {
		db_msgbox("Não existem lotes cadastrados para quadra selecionada !");
		echo "<script>parent.db_iframe.hide();</script>";
	}

?>
    </center>
	</td>
  </tr>
  </form>
</table>

</div>
</body>
</html>
<script>
<?
  //
  // Percorre o array com o ID dos db_select que tem que desabilitar
  //
  foreach($aDesabilitar as $valor) {

    echo "document.getElementById('{$valor}').disabled = true;";

  }

?>
</script>