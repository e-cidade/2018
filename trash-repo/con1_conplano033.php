<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_libcontabilidade.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_conplano_classe.php");
require_once ("classes/db_conlancamval_classe.php");
require_once ("classes/db_orctiporec_classe.php");
require_once ("classes/db_orcreceita_classe.php");
require_once ("classes/db_orcfontes_classe.php");
require_once ("classes/db_orcelemento_classe.php");
require_once ("classes/db_conplanosis_classe.php");
require_once ("classes/db_conplanoconta_classe.php");
require_once ("classes/db_conplanocontabancaria_classe.php");
require_once ("classes/db_conplanoreduz_classe.php");
require_once ("classes/db_conplanoexe_classe.php");
require_once ("classes/db_conparametro_classe.php");
require_once ("classes/db_db_config_classe.php");
require_once ("classes/db_conplanogrupo_classe.php");
require_once ("classes/db_conplanoref_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clestrutura_sistema     = new cl_estrutura_sistema;
$clconparametro          = new cl_conparametro;
$clconplanoreduz         = new cl_conplanoreduz;
$clconplanoexe           = new cl_conplanoexe;
$clconplanoconta         = new cl_conplanoconta;
$clconplanocontabancaria = new cl_conplanocontabancaria;
$clconplanosis           = new cl_conplanosis;
$clconplano              = new cl_conplano;
$clconlancamval          = new cl_conlancamval;
$cldb_config             = new cl_db_config;
$clorctiporec            = new cl_orctiporec;
$clorcfontes             = new cl_orcfontes;
$clorcelemento           = new cl_orcelemento;
$clconplanoref           = new cl_conplanoref;
$clorcreceita            = new cl_orcreceita;
$clconplanogrupo         = new cl_conplanogrupo;

$anousu = db_getsession("DB_anousu");

$db_opcao = 33;
$db_botao = false;

//////////////////////////

  $sSqlConParametro = " select c90_utilcontabancaria from conparametro";
  $rsConParametro   = db_query($sSqlConParametro);
  $oConParametro    = db_utils::fieldsMemory($rsConParametro,0);

  if ( $oConParametro->c90_utilcontabancaria == 't' ) {
    $lContaBancaria = true;
  } else {
    $lContaBancaria = false;
  }

/////////////////////////

function temContaSinteticas($estrutural, $anousu) {

  $stringnova = "";
  $string = strrev($estrutural);
  for ($i = 0;  $i < strlen($string);$i++) {

    $stringnova =  substr($string, $i,1);
    if ($stringnova != '0') {

      $string = (substr(strrev($string),0,strlen($string)-$i));
      break;
    }
  }

  $sWhere = "c60_estrut like '{$string}%' and c60_estrut <> '{$estrutural}'";
  $result= db_query("select c60_estrut from conplano where {$sWhere} and c60_anousu={$anousu}");
  if (pg_num_rows($result)) {
    return false;
   } else {
    return true;
   }
}

$erro_msg="";
/* se conta possuir reduzidos, não excluir  */

if (isset ($excluir)) {

	$erro_msg=" Exclusão feita com sucesso!";

  $sSqlUltimoAno = $clconplano->sql_query_file(null,null,"max(c60_anousu) as maximo", null, "c60_codcon = {$c60_codcon}");
  $rsUltimoAno   = $clconplano->sql_record($sSqlUltimoAno);
  $iUltimoAno    = db_utils::fieldsMemory($rsUltimoAno, 0)->maximo;

	$sqlerro = false;
	for ($iAno = db_getsession("DB_anousu"); $iAno <= $iUltimoAno; $iAno++) {

  	$codigo = str_replace(".", "", $c90_estrutcontabil);
  	if (temContaSinteticas($codigo, $iAno) == false) {

  		$erro_msg  =  "Você está tentando excluir uma conta que possui níveis mais analíticos abaixo. ";
      $erro_msg .= "Se realmente deseja fazer a exclusão, comece pelas analíticas.";
  		$sqlerro = true;
  	} else {
  		db_inicio_transacao();
  		// verifica se tem reduzidos
  		if ($sqlerro == false) {
  			$result = $clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null, null, "c61_reduz", '', "c61_codcon=$c60_codcon and c61_anousu=$iAno"));
  			if ($clconplanoreduz->numrows > 0) {
  				db_msgbox("Não posso excluir conta com reduzidos. Entre em modo de alteração primeiramente ! ");
  				$sqlerro = true;
  			}
  		}
  		if ($sqlerro == false) {
  			$result = $clorcreceita->sql_record($clorcreceita->sql_query_file(null, null, "*", null," o70_codfon = $c60_codcon and o70_anousu=$iAno "));
  			if ($clorcreceita->numrows > 0) {
  				db_msgbox("Esta conta possui previsão de receita no Orçamento. Exclusão não permitida ! ");
  				$sqlerro = true;
  			}
  		}

  		/**
  		 * Verificamos se a conta possui configurações nos cenarios enconomicos
  		 */
  		$oDaoOrcCenarioEconomico = db_utils::getDao("orccenarioeconomicoconplano");
  		$sWhereCenario           = "o04_conplano = {$c60_codcon} and o04_anousu ={$iAno}";
  		$sSqlVerificaCenario     = $oDaoOrcCenarioEconomico->sql_query_file(null,"*",null, $sWhereCenario);
  		$rsVerificaCenario       = $oDaoOrcCenarioEconomico->sql_record($sSqlVerificaCenario);
  		if ($oDaoOrcCenarioEconomico->numrows > 0) {

  		  $sMsg  = "A exclusão não foi realizada porque esta conta está associada a um Parâmetro Macroeconômico do PPA. ";
  		  $sMsg .= "Se você desejar realmente excluí-la, deverá retirá-la desta configuração acessando o menu ";
  		  $sMsg .= "ORÇAMENTO > PROCEDIMENTOS > ASSOCIAÇÃO DOS PARÂMETROS MACROECONÔMICOS > RECEITA.\\n";
  		  $sMsg .= "Caso necessário, contate suporte.";
  		  db_msgbox("{$sMsg}");
        $sqlerro = true;
        break;

  		}

  		/**
       * Verificamos se a conta possui desdobramentos cadastrados
       */
  		$oDaoOrcFontesDes         = db_utils::getDao("orcfontesdes");
  	  $sWhereDesdobramentos     = "o60_codfon = {$c60_codcon} and o60_anousu = {$iAno}";
  	  $sSqlVerificaOrcfontesDes = $oDaoOrcFontesDes->sql_query_file(null, null, "*", null, $sWhereDesdobramentos);
  	  $rsVerificaOrFontesDes    = $oDaoOrcFontesDes->sql_record($sSqlVerificaOrcfontesDes);
  	  if ($oDaoOrcFontesDes->numrows > 0) {

  	    $sMsg  = "A exclusão não foi realizada porque esta conta possui configuração de desdobramento de receita no Orçamento.";
        $sMsg .= "Se você realmente deseja excluir, deverá acessar o menu ";
        $sMsg .= "ORÇAMENTO > CADASTROS > DESDOBRAMENTO DA RECEITA > EXCLUSÃO.\\n";
        $sMsg .= "Caso necessário, contate suporte.";
        db_msgbox("{$sMsg}");
        $sqlerro = true;
        break;

  	  }
  		/*rotina que exclui do conplanoconta*/
  		if ($sqlerro == false) {

  			if ( $lContaBancaria ) {

	        $sWhereConplanoContaBancariaAtual  = "    c56_codcon = {$c60_codcon} ";
	        $sWhereConplanoContaBancariaAtual .= "and c56_anousu = {$iAno}       ";
	        $sSqlConplanoContaBancariaAtual    = $clconplanocontabancaria->sql_query_file(null,"*",null,$sWhereConplanoContaBancariaAtual);
	        $rsConplanoContaBancariaAtual      = $clconplanocontabancaria->sql_record($sSqlConplanoContaBancariaAtual);

	        if ( $clconplanocontabancaria->numrows > 0 ) {

	          $oConplanoContaBancariaAtual = db_utils::fieldsMemory($rsConplanoContaBancariaAtual,0);

	          $clconplanocontabancaria->c56_sequencial = $oConplanoContaBancariaAtual->c56_sequencial;
	          $clconplanocontabancaria->excluir($oConplanoContaBancariaAtual->c56_sequencial);

	          if ( $clconplanocontabancaria->erro_status == 0 ) {
	            $erro_msg = $clconplanocontabancaria->erro_msg;
	            $sqlerro  = true;
	            db_msgbox("Não consegui excluir a conta bancária (conplanocontabancaria)");
	          }
	        }
  			} else {

	        $clconplanoconta->sql_record($clconplanoconta->sql_query_file($c60_codcon, $iAno, "c63_banco"));
	        if ($clconplanoconta->numrows > 0) {
	          $clconplanoconta->c63_codcon = $c60_codcon;
	          $clconplanoconta->c63_anousu = $iAno;
	          $clconplanoconta->excluir($c60_codcon,$iAno);
	          //$clconplanoconta->erro(true,false);
	          if ($clconplanoconta->erro_status == 0) {

	            $erro_msg = $clconplanoconta->erro_msg;
	            $sqlerro = true;
	            db_msgbox("Não consegui excluir a conta bancária (conplanoconta)");
	          }
	        }

  			}



        $clconplanogrupo->sql_record($clconplanogrupo->sql_query_file(null,"*",null,"c21_codcon = $c60_codcon and c21_anousu = $iAno"));
        if ($clconplanogrupo->numrows > 0){

          $clconplanogrupo->excluir(null,"c21_codcon = $c60_codcon and c21_anousu = $iAno");
          if ($clconplanogrupo->erro_status == 0){

            $erro_msg = $clconplanogrupo->erro_msg;
            $sqlerro = true;
            db_msgbox("Não foi possivel excluir registros de grupos associados a esta conta. Verifique.");
          }
        }
  		}
  		/*rotina que exclui do conplano*/
  		if ($sqlerro == false) {

  			$clconplano->c60_anousu = $iAno;
  			$clconplano->c60_estrut = $codigo;
  			$clconplano->excluir($c60_codcon, $iAno);
  			//$clconplano->erro(true,false);
  			if ($clconplano->erro_status == 0) {

  			  $erro_msg = $clconplano->erro_msg;
  				$sqlerro = true;
  				db_msgbox("Não consegui excluir do plano de contas (conplano)");

  			}

  		}
  		//rotina que verifica se foi  incluido no orcelemento ou no orcfontes
  		if ($sqlerro == false) {
  			$arr_tipo = array ("orcelemento" => "3", "orcfontes" => array("4","9"));
  			if (substr($codigo, 0, 1) == $arr_tipo["orcelemento"] || substr($codigo, 0, 3) == '512') {
  				$clorcelemento->sql_record($clorcelemento->sql_query_file($c60_codcon,$iAno));
  				if ($clorcelemento->numrows > 0) {
  					$clorcelemento->o56_codele = $c60_codcon;
  					$clorcelemento->o56_anousu = $iAno;
  					$clorcelemento->excluir($c60_codcon,$iAno);
  					$erro_msg = $clorcelemento->erro_msg;
  					if ($clorcelemento->erro_status == 0) {
  						db_msgbox("Não consegui excluir da tabela Orcelemento ");
  						$sqlerro = true;
  					}
  				}
  			} else {

  				if (in_array(substr($codigo, 0, 1),$arr_tipo["orcfontes"]) || substr($codigo, 0, 3) == '612') {

  					$clorcfontes->o57_codfon = $c60_codcon;
  					$clorcfontes->o57_anousu = $iAno;
  					$clorcfontes->sql_record($clorcfontes->sql_query_file($c60_codcon,$iAno));
  					if ($clorcfontes->numrows > 0) {
  						$clorcfontes->excluir($c60_codcon,$iAno);
  						$erro_msg = $clorcfontes->erro_msg;
  						if ($clorcfontes->erro_status == 0) {
  							db_msgbox("Não consegui excluir da tabela Orcfontes ");
  							$sqlerro = true;
  						}
  					}
  				}
  			}
  		}
  	}
	}

	db_fim_transacao($sqlerro);
} else
	if (isset ($chavepesquisa)) {
		$db_opcao = 3;
		$db_botao = true;
		$result = $clconplano->sql_record($clconplano->sql_query_geral($chavepesquisa, $anousu));
		db_fieldsmemory($result, 0);
		$c90_estrutcontabil = $c60_estrut;

		$result = $clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null, "*", "", "c61_codcon=$c60_codcon and c61_anousu=$anousu  and c61_instit=".db_getsession("DB_instit")));
		global $tipo;
		if ($clconplanoreduz->numrows > 0) {
			db_fieldsmemory($result, 0);
			$tipo = 'analitica';
		} else {
			$tipo = 'sintetica';
		}

		if ( $lContaBancaria ) {
		  $sWhereConplanoContaBancaria   = "    c56_codcon = {$c60_codcon} ";
	    $sWhereConplanoContaBancaria  .= "and c56_anousu = {$anousu}     ";

	    $sCamposConplanoContaBancaria  = "conplanocontabancaria.*,";
	    $sCamposConplanoContaBancaria .= "contabancaria.*,        ";
	    $sCamposConplanoContaBancaria .= "bancoagencia.*          ";

	    $sSqlConplanoContaBancaria     = $clconplanocontabancaria->sql_query( null,
	                                                                          $sCamposConplanoContaBancaria,
	                                                                          null,
	                                                                          $sWhereConplanoContaBancaria);

	    $rsConplanoContaBancaria       = $clconplanocontabancaria->sql_record($sSqlConplanoContaBancaria);

	    if ( $clconplanocontabancaria->numrows > 0 ) {
	      db_fieldsmemory($rsConplanoContaBancaria,0);
	    }
		} else {
			$result = $clconplanoconta->sql_record($clconplanoconta->sql_query_file($c60_codcon));
			if ($clconplanoconta->numrows > 0) {
				db_fieldsmemory($result, 0);
			}
		}

		if (isset ($c61_codigo) && $c61_codigo != '' && $c61_codigo != '0') {
			$result = $clorctiporec->sql_record($clorctiporec->sql_query_file($c61_codigo, "o15_descr"));
			if ($clconplanosis->numrows > 0) {
				db_fieldsmemory($result, 0);
			}
		}
	}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
    <br>
	<?

	if (USE_PCASP && db_getsession("DB_anousu") >= 2013) {

	  $sMensagem  = "Esta rotina está desabilitada para o ano de 2013.\\n";
	  $sMensagem .= "Para cadastrar uma nova conta acesse o menu:\\n\\n";
	  $sMensagem .= "Contabilidade > Cadastros > Plano de Contas (PCASP)";
	  db_msgbox($sMensagem);
	  $db_botao = false;
	}
include ("forms/db_frmconplano.php");
echo "<script>
                parent.document.formaba.reduzido.disabled ='true';
		parent.document.formaba.reduzido.style.visibility= 'hidden';

  	      </script>";
?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?



if (isset ($excluir)) {
	if ($sqlerro == true) {
	  if ($erro_msg != "") {
 	    db_msgbox($erro_msg);
	  }
		if ($clconplano->erro_campo != "") {

			echo "<script> document.form1.".$clconplano->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clconplano->erro_campo.".focus();</script>";

		} else {

			if ($clconplanoreduz->erro_campo != "") {
				echo "<script> document.form1.".$clconplanoreduz->erro_campo.".style.backgroundColor='#99A9AE';</script>";
				echo "<script> document.form1.".$clconplanoreduz->erro_campo.".focus();</script>";

			}
		}
	} else {
		db_msgbox($erro_msg);
		db_redireciona();
	}
}
if ($db_opcao == 33) {
	echo "<script>document.form1.pesquisar.click();</script>";
}
?>