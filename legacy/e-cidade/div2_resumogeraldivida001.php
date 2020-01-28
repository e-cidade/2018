<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_proced_classe.php");
require_once("libs/db_libpostgres.php");

$oPost = db_utils::postMemory($_POST);

$clpostgresqlutils = new PostgreSQLUtils;
$clIframeSeleciona = new cl_iframe_seleciona;

$sListaTipo  = "5,6,13,15,18";
$iInstit     = db_getsession('DB_instit');
$dtDataAtual = date('Y-m-d',db_getsession('DB_datausu'));

if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {

	db_msgbox("Problema nos índices da tabela débitos. Entre em contato com CPD.");
	$lIndex   = false;
	$db_botao = false;
	$db_opcao = 3;
} else {

	$lIndex   = true;
	$db_botao = true;
	$db_opcao = 4;
}
?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
		<script type="text/javascript">

				function js_getExecicios() {
					var eleExercicio = exercicio.document.form1;
					var aExercicios  = new Array();

					for( var iInd=0; iInd < eleExercicio.length; iInd++ ) {
						if( eleExercicio.elements[iInd].type == "checkbox") {
							if( eleExercicio.elements[iInd].checked == true) {
								var sValor = eleExercicio.elements[iInd].value.split("_");
								aExercicios.push(sValor);
							}
						}
					}

					return aExercicios.toString();
				}

				function js_imprimir() {
					var sExercicios = js_getExecicios();
					if ( sExercicios.trim() == '' ) {
						alert('Nenhum exercício selecionado!');
						return false;
					}

					var sQuery      = '?dataDebitos='+document.form1.dataDebitos.value;
						sQuery     += '&sExercicios='+sExercicios;
						sQuery     += '&sQuebra='+document.form1.quebra.value;
					jan = window.open('div2_resumogeraldivida002.php'+sQuery,'',
							'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
					jan.moveTo(0,0);
				}

			</script>
		<link href="estilos.css" rel="stylesheet" type="text/css">
	</head>
	<body class="body-default">
		<form class="container"  name="form1" method="post" action="" >
		  <fieldset>
				<legend>Resumo Geral da Dívida</legend>
				<table class="form-container">
				  <tr>
				  	<td>Data do Cálculo : </td>
				  	<td>
				  	 <?php
				  	 	$aDatas    = array();
				  	 	$iNroDatas = 0;
				  	 	if ($lIndex) {

				  	 		$sSqlDataDebitos  = "select k115_data  							";
				  	 		$sSqlDataDebitos .= "	 from datadebitos 				  	";
				  	 		$sSqlDataDebitos .= " where k115_instit = {$iInstit}";
				  	 		$sSqlDataDebitos .= " order by k115_data desc       ";

				  	 		$rsDataDebitos	  = db_query($sSqlDataDebitos);
				  	 		$iNroDatas		    = pg_num_rows($rsDataDebitos);
				  	 		for( $iInd=0; $iInd < $iNroDatas; $iInd++ ){
				  	 			$oDataDebitos = db_utils::fieldsMemory($rsDataDebitos,$iInd);
				  	 			$aDatas[$oDataDebitos->k115_data] = db_formatar($oDataDebitos->k115_data,'d');
				  	 		}
				  	 	}
				  	 	db_select("dataDebitos",$aDatas,true,$db_opcao,"onChange='document.form1.submit()'");
				  	 ?>
				  	</td>
				  </tr>
				  <tr>
				  	<td>Quebra por Tipo:</td>
				  	<td>
				  		<?php
				  			$aQuebraTipo = array("s"=>"SIM","n"=>"NÃO");
				  			db_select('quebra',$aQuebraTipo,true,$db_opcao,'');
				  		?>
				  	</td>
				  </tr>
				  	<?php
				  			if ( isset($oPost->dataDebitos) && trim($oPost->dataDebitos) != '' ) {
				  				$dtDataDebitos = $oPost->dataDebitos;
				  			} else if ( $iNroDatas > 0 ) {
				  				$dtDataDebitos = pg_result($rsDataDebitos,0,'k115_data');
				  			} else {
				  				$dtDataDebitos = '';
				  			}

				  			if ( trim($dtDataDebitos) != '' ) {
				  				?>
				  			<tr>
				  				<td colspan="2">
				  					<?php
				  						$sSqlExerc  = "select distinct                                   ";
				  						$sSqlExerc .= "       k22_exerc                                  ";
				  						$sSqlExerc .= "  from debitos                                    ";
				  						$sSqlExerc .= "       inner join arretipo on k00_tipo = k22_tipo ";
				  						$sSqlExerc .= " where k22_data = '{$dtDataDebitos}'              ";
				  						$sSqlExerc .= "   and k22_instit = {$iInstit}                    ";
				  						$sSqlExerc .= "   and k03_tipo in ({$sListaTipo})                ";
				  						$sSqlExerc .= " order by k22_exerc                               ";

				  						$clIframeSeleciona->campos        = "k22_exerc";
				  						$clIframeSeleciona->legenda       = "Exercício";
				  						$clIframeSeleciona->sql           = $sSqlExerc;
				  						$clIframeSeleciona->textocabec    = "darkblue";
				  						$clIframeSeleciona->textocorpo    = "black";
				  						$clIframeSeleciona->fundocabec    = "#aacccc";
				  						$clIframeSeleciona->fundocorpo    = "#ccddcc";
				  						$clIframeSeleciona->iframe_height = "250";
				  						$clIframeSeleciona->iframe_width  = "200";
				  						$clIframeSeleciona->iframe_nome   = "exercicio";
				  						$clIframeSeleciona->chaves        = "k22_exerc";
				  						$clIframeSeleciona->iframe_seleciona($db_botao);
				  					?>
				  				</td>
				  			</tr>
				  		<?
				  		}
				  		?>
				</table>
			  </fieldset>

 			  <input name="imprimir" type="button" id="imprimir" value="Imprimir" onClick="js_imprimir();"<?=($db_botao ? '' : 'disabled')?> />

		</form>
		<?php
			db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
		?>
	</body>
</html>