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

require_once ("libs/db_utils.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_conplano_classe.php");
require_once ("classes/db_conplanoexe_classe.php");
require_once ("classes/db_orctiporec_classe.php");
require_once ("classes/db_conplanoconta_classe.php");
require_once ("classes/db_conplanocontabancaria_classe.php");
require_once ("classes/db_orcfontes_classe.php");
require_once ("classes/db_orcelemento_classe.php");
require_once ("classes/db_conlancamval_classe.php");
require_once ("classes/db_conplanoreduz_classe.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_conparametro_classe.php");
require_once ("classes/db_db_config_classe.php");
require_once ("libs/db_libcontabilidade.php");

$c63_codigooperacao = "";

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clestrutura_sistema      = new cl_estrutura_sistema;
$clconparametro           = new cl_conparametro;
$clconplanoreduz          = new cl_conplanoreduz;
$clconplanoconta          = new cl_conplanoconta;
$clconplano               = new cl_conplano;
$clconplanoexe            = new cl_conplanoexe;
$cldb_config              = new cl_db_config;
$clorctiporec             = new cl_orctiporec;
$clorcfontes              = new cl_orcfontes;
$clorcelemento            = new cl_orcelemento;
$clconplanocontabancaria  = new cl_conplanocontabancaria;

$c63 = "";
$db_opcao = 22;
$db_botao = true;
$sqlerro  = false;
$anousu   = db_getsession("DB_anousu");

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


//-- inicio da alteração
if (isset ($alterar)) {

  db_inicio_transacao();

  /**
   * Procuramos o ultimo ano e Fizemos as alterações nas contas nos anos cadastradoss em exercicios posteriores.
   */
  $sWhereUsePCASP = "";
  if (USE_PCASP) {
    $sWhereUsePCASP = " and c60_anousu < 2013 ";
  }
  $sSqlUltimoAno = $clconplano->sql_query_file(null,null,"max(c60_anousu) as maximo", null, "c60_codcon = {$c60_codcon} {$sWhereUsePCASP}");
  $rsUltimoAno   = $clconplano->sql_record($sSqlUltimoAno);
  $iUltimoAno    = db_utils::fieldsMemory($rsUltimoAno, 0)->maximo;

  /**
   * Percorremos os anos e alteramos as dados nos anos subsequentes.
   */
  for ($iAno = db_getsession("DB_anousu"); $iAno <= $iUltimoAno; $iAno++) {


  	if ( $lContaBancaria ) {

	    $sWhereConplanoContaBancariaAtual  = "    c56_codcon = {$c60_codcon} ";
	    $sWhereConplanoContaBancariaAtual .= "and c56_anousu = {$iAno}       ";
	    $sSqlConplanoContaBancariaAtual    = $clconplanocontabancaria->sql_query_file(null,"*",null,$sWhereConplanoContaBancariaAtual);
	    $rsConplanoContaBancariaAtual      = $clconplanocontabancaria->sql_record($sSqlConplanoContaBancariaAtual);

	    if ( isset($c56_contabancaria) && trim($c56_contabancaria) != '' ) {

	      if ( $clconplanocontabancaria->numrows > 0 ) {

	        $oConplanoContaBancariaAtual = db_utils::fieldsMemory($rsConplanoContaBancariaAtual,0);

	        $clconplanocontabancaria->c56_sequencial    = $oConplanoContaBancariaAtual->c56_sequencial;
	        $clconplanocontabancaria->c56_contabancaria = $c56_contabancaria;
	        $clconplanocontabancaria->alterar($oConplanoContaBancariaAtual->c56_sequencial);

	      } else {

	        $clconplanocontabancaria->c56_anousu        = $iAno;
	        $clconplanocontabancaria->c56_codcon        = $c60_codcon;
	        $clconplanocontabancaria->c56_contabancaria = $c56_contabancaria;
	        $clconplanocontabancaria->incluir(null);

	      }

	      $erro_msg = $clconplanocontabancaria->erro_msg;
	      if ( $clconplanocontabancaria->erro_status == 0 ) {
	        $sqlerro = true;
	      }

	    } else {

	    	if ( $clconplanocontabancaria->numrows > 0 ) {

	        $oConplanoContaBancariaAtual = db_utils::fieldsMemory($rsConplanoContaBancariaAtual,0);

	        $clconplanocontabancaria->c56_sequencial = $oConplanoContaBancariaAtual->c56_sequencial;
	        $clconplanocontabancaria->excluir($oConplanoContaBancariaAtual->c56_sequencial);

		      if ( $clconplanocontabancaria->erro_status == 0 ) {
		        $erro_msg = $clconplanocontabancaria->erro_msg;
		        $sqlerro  = true;
		      }

	    	}

	    }

  	} else {

	  	//rotina de alteração da tabela conplanoconta
	  	if (isset ($c63_banco) && $c63_banco != "" || isset ($c63_agencia) && $c63_agencia != ""
	  	   || isset ($c63_conta) && $c63_conta != "") {

	  		$clconplanoconta->sql_record($clconplanoconta->sql_query_file($c60_codcon, $iAno, "c63_banco"));

	  		if ($clconplanoconta->numrows > 0) {
	  			$proces = "alterar";
	  		} else {
	  			$proces = "incluir";
	  		}

	  		$clconplanoconta->c63_banco   = $c63_banco;
	  		$clconplanoconta->c63_anousu  = $iAno;
	  		$clconplanoconta->c63_agencia = $c63_agencia;
	  		$clconplanoconta->c63_conta   = $c63_conta;
	  		$clconplanoconta->c63_codcon  = $c60_codcon;
	  		$clconplanoconta->c63_codigooperacao = "".str_pad($c63_codigooperacao,4,"0",STR_PAD_LEFT)."";
	  		if ($proces == "alterar") {
	  			$clconplanoconta->alterar($c60_codcon, $iAno);
	  		} else {
	  			$clconplanoconta->incluir($c60_codcon, $iAno);
	  		}

	  		if ($clconplanoconta->erro_status == 0) {

	  		  $erro_msg = $clconplanoconta->erro_msg;
	  			$sqlerro = true;
	  		}
	  	} else {

	  		$clconplanoconta->sql_record($clconplanoconta->sql_query_file($c60_codcon, $iAno, "c63_banco"));
	  		if ($clconplanoconta->numrows > 0) {

	  			$clconplanoconta->c63_codcon = $c60_codcon;
	  			$clconplanoconta->excluir($c60_codcon, $iAno);

	  			if ($clconplanoconta->erro_status == 0) {
	  			  $erro_msg = $clconplanoconta->erro_msg;
	  				$sqlerro = true;
	  			}
	  		}

	  	}

  	}

  	// --------- *  ----------- * ---------------------
  	if ($sqlerro == false) { // atualiza conplano

  	  $codigo = str_replace(".", "", $c90_estrutcontabil);
      if ($clconplano->db_verifica_conplano($codigo, $iAno) == false) {

        $erro_msg = $clconplano->erro_msg;
        $sqlerro  = true;
        $focar    = "c90_estrutcontabil";

      }

  		$clconplano->c60_finali = $c60_finali;
  		$clconplano->c60_codsis = $c60_codsis;
  		$clconplano->c60_codcla = $c60_codcla;
  		$clconplano->c60_descr  = $c60_descr;
  		$clconplano->c60_anousu = $iAno;
  		$clconplano->c60_codcon = $c60_codcon;
  		$clconplano->alterar($c60_codcon, $iAno);
  		if ($clconplano->erro_status == 0) {

  			$erro_msg = $clconplano->erro_msg;
  			$sqlerro = true;

  		}

  	}
  	//rotina que verifica quando é para incluir no orcelemento ou no orcfontes
  	if ($sqlerro == false) {
  		$arr_tipo = array ("orcelemento" => "3", "orcfontes" => array("4","9"));

  		if (substr($codigo, 0, 1) == $arr_tipo["orcelemento"] || substr($codigo, 0, 3) == '512') {

  			$clorcelemento->o56_codele   = $c60_codcon;
  			$clorcelemento->o56_anousu   = $iAno;
  			$clorcelemento->o56_elemento = substr($codigo, 0, 13);
  			$clorcelemento->o56_descr    = $c60_descr;
  			$clorcelemento->o56_finali   = $c60_finali;
  			$clorcelemento->o56_orcado   = 'true';

  			$clorcelemento->sql_record($clorcelemento->sql_query_file($c60_codcon, $iAno));
  			if ($clorcelemento->numrows > 0) {
  				$clorcelemento->alterar($c60_codcon, $iAno);
  			} else {
  				$clorcelemento->incluir($c60_codcon, $iAno);
  			}

  			if ($clorcelemento->erro_status == 0) {

  			  $erro_msg = $clorcelemento->erro_msg;
  				$sqlerro = true;
  			}
  		} else {
  			if (in_array(substr($codigo, 0, 1),$arr_tipo["orcfontes"]) || substr($codigo, 0, 3) == '612') {

  				$clorcfontes->o57_codfon = $c60_codcon;
  				$clorcfontes->o57_anousu = $iAno;
  				$clorcfontes->o57_fonte  = $codigo;
  				$clorcfontes->o57_descr  = $c60_descr;
          $clorcfontes->o57_finali = (trim($c60_finali)==""?' ':$c60_finali);
  				$clorcfontes->sql_record($clorcfontes->sql_query_file($c60_codcon, $iAno));
  				if ($clorcfontes->numrows > 0) {
  					$clorcfontes->alterar($c60_codcon, $iAno);
  				} else {
  					$clorcfontes->incluir($c60_codcon, $iAno);
  				}

  				if ($clorcfontes->erro_status == 0) {

  				  $erro_msg = $clorcfontes->erro_msg;
  					$sqlerro = true;

  				}
  			}
  		}
  	}
  }
	// -----------
	// $sqlerro = false; // teste de erro
	db_fim_transacao($sqlerro);
	if ($sqlerro) {
	  $db_opcao = 2;
	}

}
else if (isset ($chavepesquisa)) {
	$db_opcao = 2;
	$db_botao = true;
	$result = $clconplano->sql_record($clconplano->sql_query_geral($chavepesquisa, db_getsession("DB_anousu")));

  if ($clconplano->numrows > 0){
    db_fieldsmemory($result, 0);
  	$c90_estrutcontabil = $c60_estrut;

  	$result = $clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null, null, "*", "", "c61_anousu=$anousu and c61_codcon=$c60_codcon"));
  	global $tipo;

    if (!isset($tipo)){
    	if ($clconplanoreduz->numrows > 0) {
  	  	db_fieldsmemory($result, 0);
    		$tipo = 'analitica';
    	} else {
    		$tipo = 'sintetica';
    	}
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

		  $result = $clconplanoconta->sql_record($clconplanoconta->sql_query_file($c60_codcon, $anousu));
	  	if ($clconplanoconta->numrows > 0) {
		  	db_fieldsmemory($result, 0);
	  	}

    }



  	if (isset ($c61_codigo) && $c61_codigo != '' && $c61_codigo != '0') {
	  	$result = $clorctiporec->sql_record($clorctiporec->sql_query_file($c61_codigo, "o15_descr"));
  		if ($clorctiporec->numrows > 0) {
  			db_fieldsmemory($result, 0);
  		}
  	}
	// -- * --
	// se esta conta nao pode ser analitica, então nao libera aba e bloqueia o tipo como sintetica for
	// se a conta tiver uma filha analitica, ela deve ser obrigatoriamente sintetica
  	if ($tipo == 'sintetica') {
  		$nivel = db_le_mae_conplano($c60_estrut, 'true');
	  	$cortado = db_le_corta_conplano($c60_estrut, $nivel);
      if ( $cortado == "" ){
      	$cortado = $c60_estrut;
      }
   		$clconplano->sql_record($clconplano->sql_query_file("", "", "*", "", "c60_anousu=".db_getsession("DB_anousu")." and  c60_estrut like '$cortado%'"));
  		if ($clconplano->numrows > 1 && $nivel >= 9) {
  			 //db_msgbox("Estrutural não pode ser analitica, pos possui conta filha ! ");
  			$tipo = 'sintetica';
  			$bloqueada = 'true';
  		}
  		if (isset ($bloqueada) && $bloqueada == 'true') {
  			echo "<script>
							                         top.corpo.document.formaba.reduzido.style.visibility='hidden';
							                         top.corpo.iframe_reduzido.disable='true';
							                 </script>";
  		} else {
	  		echo "<script>
						                         top.corpo.iframe_reduzido.disable='false';
						                         top.corpo.document.formaba.reduzido.style.visibility='visible';
						                 </script>";
  			echo "<script> top.corpo.iframe_reduzido.location.href='con1_conplano004.php?c60_codcon=$c60_codcon';</script>";
  		}
  	} else {
	  	echo "<script>
              top.corpo.iframe_reduzido.disable='false';
		          top.corpo.document.formaba.reduzido.style.visibility='visible';
  		        top.corpo.iframe_reduzido.location.href='con1_conplano004.php?c60_codcon=$c60_codcon';
              parent.mo_camada('reduzido');
            </script>";
	  }

    echo "<script>
              top.corpo.document.formaba.grupos.style.visibility='visible';
		          top.corpo.iframe_grupos.disable='false';
              top.corpo.iframe_grupos.location.href = 'con1_congrupo004.php?c21_anousu=$anousu&c21_codcon=$c60_codcon';
		      </script>";
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
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
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
			  echo "<script>
                parent.document.formaba.reduzido.disabled ='true';
  	      </script>";
			}
		    include ("forms/db_frmconplano.php");
		  ?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?



if (isset ($alterar)) {
	if ($sqlerro == true) {
		db_msgbox($erro_msg);
		if ($clconplano->erro_campo != "") {
			echo "<script> document.form1.".$clconplano->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clconplano->erro_campo.".focus();</script>";
		} else
			if ($clconplanoreduz->erro_campo != "") {
				echo "<script> document.form1.".$clconplanoreduz->erro_campo.".style.backgroundColor='#99A9AE';</script>";
				echo "<script> document.form1.".$clconplanoreduz->erro_campo.".focus();</script>";
			};
	} else {
		db_redireciona();
	}
}
if ($db_opcao == 22 && !$sqlerro) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>