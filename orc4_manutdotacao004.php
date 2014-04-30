<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_orcdotacao_classe.php");
include ("classes/db_orcdotacaocontr_classe.php");
include ("classes/db_orcelemento_classe.php");
include ("classes/db_orcparametro_classe.php");
include ("classes/db_orcorgao_classe.php");
include ("classes/db_orcunidade_classe.php");
include ("classes/db_orcfuncao_classe.php");
include ("classes/db_orcsubfuncao_classe.php");
include ("classes/db_orcprograma_classe.php");
include ("classes/db_orcprojativ_classe.php");

include ("classes/db_orctiporec_classe.php");
require ("libs/db_liborcamento.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clorcdotacao      = new cl_orcdotacao;
$clorcdotacaocontr = new cl_orcdotacaocontr;
$clorcelemento     = new cl_orcelemento;
$clorcparametro    = new cl_orcparametro;
$clorcorgao        = new cl_orcorgao;
$clorcunidade      = new cl_orcunidade;
$clorcfuncao       = new cl_orcfuncao;
$clorcsubfuncao    = new cl_orcsubfuncao;
$clorcprograma     = new cl_orcprograma;
$clorcprojativ     = new cl_orcprojativ;
$clorctiporec      = new cl_orctiporec;
$db_opcao          = 1;
$db_botao          = true;
$anousu            = db_getsession("DB_anousu");

if ((isset ($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Incluir") {
	
	$erro_trans = false;

	if ($o58_orgao == "") {
		$erro_trans = true;
		$clorcdotacao->erro_msg = "Orgão não informado.";
		$clorcdotacao->erro_status = 0;
	}
	if ($o58_unidade == "") {
		$erro_trans = true;
		$clorcdotacao->erro_msg = "Unidade não informado.";
		$clorcdotacao->erro_status = 0;
	}
	if ($o58_funcao == "") {
		$erro_trans = true;
		$clorcdotacao->erro_msg = "Função não informado.";
		$clorcdotacao->erro_status = 0;
	}

	if ($o58_subfuncao == "") {
		$erro_trans = true;
		$clorcdotacao->erro_msg = "Sub-Função não informado.";
		$clorcdotacao->erro_status = 0;
	}

	if ($o58_programa == "") {
		$erro_trans = true;
		$clorcdotacao->erro_msg = "Programa não informado.";
		$clorcdotacao->erro_status = 0;
	}
	if ($o58_projativ == "") {
		$erro_trans = true;
		$clorcdotacao->erro_msg = "Projeto/Atividade não informado.";
		$clorcdotacao->erro_status = 0;
	}
	if ($o56_elemento == "") {
		$erro_trans = true;
		$clorcdotacao->erro_msg = "Elemento não informado.";
		$clorcdotacao->erro_status = 0;
	}
	if ($o58_codigo == "") {
		$erro_trans = true;
		$clorcdotacao->erro_msg = "Recurso não informado.";
		$clorcdotacao->erro_status = 0;
	}
  if ($o58_concarpeculiar == "") {
    $erro_trans = true;
    $clorcdotacao->erro_msg = "Você deve selecionar uma C.Peculiar/Cod. de Aplicação antes de incluir a Dotação.";
    $clorcdotacao->erro_status = 0;
  }
	if ($erro_trans ==false) {		
		 db_inicio_transacao();
		 $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null, null, "*", "",
		                    "o58_anousu               = ".db_getsession("DB_anousu")." and 
	                       o58_orgao                = $o58_orgao      and
		        						 o58_unidade              = $o58_unidade    and
											   o58_funcao               = $o58_funcao     and
											   o58_subfuncao            = $o58_subfuncao  and
											   o58_programa             = $o58_programa   and
											   o58_projativ             = $o58_projativ   and
											   orcelemento.o56_elemento = '$o56_elemento' and o58_codele = orcelemento.o56_codele and 
											   orcelemento.o56_anousu   = o58_anousu and
											   o58_codigo               = $o58_codigo and 
											   o58_instit               = $o58_instit
											   and o58_concarpeculiar   = '{$o58_concarpeculiar}'"
											   ));
		if ($clorcdotacao->numrows > 0) {
			$erro_trans = true;
			$clorcdotacao->erro_msg = "Dotação já Cadastrada.";
			$clorcdotacao->erro_status = 0;
		} else {			

			$resultPar = $clorcparametro->sql_record($clorcparametro->sql_query_file($anousu,"o50_subelem"));			
			
			//die($clorcparametro->sql_query_file($anousu,"o50_subelem"));

			db_fieldsmemory($resultPar, 0);

			if ($o50_subelem == 'f') {

				$o56_elemento = substr($o56_elemento, 0, 7)."000000";
				$sSql   = $clorcelemento->sql_query_file(null, null, 'o56_codele', 'o56_elemento', " o56_anousu = ".db_getsession("DB_anousu")." and o56_elemento = '$o56_elemento' ");
			} else {
				$sSql   = $clorcelemento->sql_query_file(null, 
          null, 
          'o56_codele',
          '',
          " o56_anousu = ".db_getsession("DB_anousu")." 
            and  o56_elemento = '$o56_elemento' ");
			}	
			$result = $clorcelemento->sql_record($sSql);
			if ($clorcelemento->numrows > 0) {
				db_fieldsmemory($result, 0);
				$clorcdotacao->o58_codele = $o56_codele;

				$result = $clorcparametro->sql_record("update orcparametro set o50_coddot = o50_coddot + 1 where o50_anousu = ".db_getsession("DB_anousu"));
				$result = $clorcparametro->sql_record($clorcparametro->sql_query_file(db_getsession('DB_anousu'), 'o50_coddot as o58_coddot'));
				if ($clorcparametro->numrows > 0) {
					db_fieldsmemory($result, 0);

					$clorcdotacao->incluir($o58_anousu, $o58_coddot);
					if ($clorcdotacao->erro_status == 0) {
						$erro_trans = true;
						$clorcdotacao->erro_msg = $clorcdotacao->erro_msg;
					}
				} else {
					$erro_trans = true;
					$clorcdotacao->erro_msg = "Erro no código Sequencial.";
					$clorcdotacao->erro_status = 0;
				}
			} else {
				$erro_trans = true;
				$clorcdotacao->erro_msg = "Elemento  ($o56_elemento) não Cadastrado.";
				$clorcdotacao->erro_status = 0;
			}
		}
		
	}
	db_fim_transacao($erro_trans);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.o50_estrutdespesa.focus();" >
<center>
<?
include ("forms/db_frmorcdotacao001.php");
?>
    </center>
</body>
</html>
<?
if ((isset ($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Incluir") {
	if ($clorcdotacao->erro_status == "0") {
		$clorcdotacao->erro(true, false);
		$db_botao = true;
		echo "<script> document.form1.db_opcao.disabled=false;
	                   document.form1.o58_coddot.value = '';
	          </script>  ";
		if ($clorcdotacao->erro_campo != "") {
			echo "<script> document.form1.".$clorcdotacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clorcdotacao->erro_campo.".focus();</script>";
		}
	} else {
	  $clorcdotacao->erro(true,true);
	}
}
?>