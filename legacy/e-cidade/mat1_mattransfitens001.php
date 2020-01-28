<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_matestoque_classe.php");
require_once("classes/db_matestoqueitem_classe.php");
require_once("classes/db_matestoqueini_classe.php");
require_once("classes/db_matestoqueinimei_classe.php");
require_once("classes/db_matestoqueinimeipm_classe.php");
require_once("classes/db_matestoquetransf_classe.php");
require_once("classes/db_db_depart_classe.php");
require_once("classes/db_matmater_classe.php");
require_once("classes/db_db_usuarios_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/materialestoque.model.php");
require_once("libs/db_utils.php");
require_once("classes/db_matparam_classe.php");
require_once("libs/db_app.utils.php");

db_app::import("contabilidade.contacorrente.ContaCorrenteFactory");
db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.contacorrente.*");

(float) $quantlanc   = 0;
$valores             = null;
$departamentodestino = null;
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clmatestoque         = new cl_matestoque;
$clmatestoqueitem     = new cl_matestoqueitem;
$clmatestoqueini      = new cl_matestoqueini;
$clmatestoqueinimei   = new cl_matestoqueinimei;
$clmatestoqueinimeipm = new cl_matestoqueinimeipm;
$cldb_depart          = new cl_db_depart;
$cldb_usuarios        = new cl_db_usuarios;
$clmatestoquetransf   = new cl_matestoquetransf;

$db_opcao    = 1;
$db_botao    = true;
$lBotaoTermo = true;

/**
 * Verifica se retorna álgum valor dentro de $valores
 */
if ((empty($valores)) || ($valores == null)){
  $lBotaoTermo = false;
}

if (isset ($incluir) || isset ($alterar) || isset ($excluir)) {

	$where_valores = "";
	$sqlerro = false;
	db_inicio_transacao();

	if (isset ($alterar) || isset ($excluir)) {
                $valores = (isset($valores)&&!empty($valores))?$valores:'null';
		$sSqlMatEstoqueIni = $clmatestoqueini->sql_query_mater(null, " distinct m82_codigo,
		                                                               m70_codigo,
		                                                               m70_quant,
		                                                               m70_valor,
		                                                               m71_codlanc,
		                                                               m71_quant,
		                                                               (m71_valor/m71_quant) as valorunitarioitem,
		                                                               m71_quantatend,
		                                                               m82_quant",
		                                                               "",
		                                                               "matestoqueini.m80_codigo=$valores and m60_codmater=$m60_codmater ");

		$result_matestoque = $clmatestoqueini->sql_record($sSqlMatEstoqueIni);
		$numrows_matestoque = $clmatestoqueini->numrows;
		if ($numrows_matestoque > 0) {

			for ($i = 0; $i < $numrows_matestoque; $i ++) {

				db_fieldsmemory($result_matestoque, $i);
				if ($sqlerro == false) {

				  $quantidadeatender = $m71_quantatend - $m82_quant;
					$clmatestoqueitem->m71_codlanc = $m71_codlanc;
					$clmatestoqueitem->m71_quantatend = "$quantidadeatender";
					$clmatestoqueitem->alterar($m71_codlanc);
					if ($clmatestoqueitem->erro_status == 0) {
						$erro_msg = $clmatestoqueitem->erro_msg;
						$sqlerro = true;
					}
				}

				if ($sqlerro == false) {
					$clmatestoque->m70_codigo = $m70_codigo;
					$clmatestoque->m70_quant = $m70_quant + $m82_quant;
					$clmatestoque->m70_valor = $m70_valor + ($m82_quant * $valorunitarioitem);
					$clmatestoque->alterar($m70_codigo);
					if ($clmatestoque->erro_status == 0) {
						$erro_msg = $clmatestoque->erro_msg;
						$sqlerro = true;
					}
				}

			  if ($sqlerro == false && isset ($excluir)) {
			    $sWhereExcluiPrecoMedio = " m89_matestoqueinimei = {$m82_codigo} ";
			    $clmatestoqueinimeipm->excluir(null, $sWhereExcluiPrecoMedio);
          $erro_msg = $clmatestoqueinimeipm->erro_msg;
          if ($clmatestoqueinimeipm->erro_status == 0) {
           $sqlerro = true;
          }
        }

				if ($sqlerro == false && isset ($excluir)) {
			      $clmatestoqueinimei->excluir($m82_codigo);
				  $erro_msg = $clmatestoqueinimei->erro_msg;
				  if ($clmatestoqueinimei->erro_status == 0) {
					 $sqlerro = true;
				  }
				}
			}
		}
		   if ($sqlerro == false && isset ($excluir)) {

				$where_valores = "&valores=".@ $valores;

				$result_testmei=$clmatestoqueinimei->sql_record($clmatestoqueinimei->sql_query_file(null,"*",null,"m82_matestoqueini=$valores"));
				if ($numrows_matestoque <= 1 && $clmatestoqueinimei->numrows == 0) {
				  $clmatestoquetransf->excluir($valores);
					$erro_msg = $clmatestoquetransf->erro_msg;
					if ($clmatestoquetransf->erro_status == 0) {
					  $sqlerro=true;
					}

					if ($sqlerro == false) {

					  $clmatestoqueini->excluir($valores);
				  	$erro_msg = $clmatestoqueini->erro_msg;
					  if ($clmatestoqueini->erro_status == 0) {
					    $sqlerro=true;
					  } else {
				      $where_valores = "";
					  }
					}
				}
			}
	}

	if (isset ($incluir)) {

	  $nomecampo    = 'quantlanc';
	  $m80_codtipo  = 7; // Em transferência
	  $m80_login    = db_getsession("DB_id_usuario");
	  $m80_data     = date("Y-m-d", db_getsession("DB_datausu"));
	  $m80_coddepto = db_getsession("DB_coddepto");
	  $m80_hora     = date('H:i:s');

	  try {

      $oMaterialEstoque = new materialEstoque($m60_codmater);
	    $aItensDepto      = $oMaterialEstoque->transferirMaterial($quantlanc,
	                                                              $departamentoorigem,
	                                                              $departamentodestino,
	                                                              $valores,
	                                                              $m80_obs);

	    $where_valores = "&valores=".$oMaterialEstoque->getiCodMovimento();
	    $oMaterialEstoque->cancelarLoteSession();
	    $erro_msg  = "Transferência incluída com sucesso.\\n\\n";
	    $erro_msg .= "Código da Transferência: {$oMaterialEstoque->getiCodMovimento()}.";

	  } catch (Exception  $eErro) {

	    $sqlerro = true;
	    $erro_msg = str_replace("\n", "\\n",$eErro->getMessage());
	    $oMaterialEstoque->cancelarLoteSession();
	  }
  }
	db_fim_transacao($sqlerro);
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <center>
	<?


include ("forms/db_frmmattransfitens.php");
?>
    </center>
</body>
</html>
<?


if (isset ($incluir) || isset ($alterar) || isset ($excluir)) {
  db_msgbox($erro_msg);
	if ($sqlerro == false) {

		echo "
		    <script>
		      top.corpo.iframe_depart.document.form1.enviar.disabled = true;
		    </script>
		    ";
	}
	if (isset ($incluir) || isset ($excluir)) {
		echo "<script>document.location.href='mat1_mattransfitens001.php?departamentoorigem=".$departamentoorigem."&departamentodestino=".$departamentodestino.$where_valores."'</script>";
	}
}
?>