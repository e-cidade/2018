<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_matestoque_classe.php");
require_once("classes/db_matestoqueitem_classe.php");
require_once("classes/db_matestoqueini_classe.php");
require_once("classes/db_matestoqueinimei_classe.php");
require_once("classes/db_db_depart_classe.php");
require_once("classes/db_transmater_classe.php");
require_once("classes/db_empempitem_classe.php");
require_once("classes/db_empparametro_classe.php");
require_once("classes/db_matestoqueitemnotafiscalmanual_classe.php");
require_once("classes/materialestoque.model.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

require_once("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteBase.model.php");
require_once("model/financeiro/ContaBancaria.model.php");
require_once("model/contabilidade/planoconta/ContaPlano.model.php");
require_once("model/contabilidade/planoconta/ClassificacaoConta.model.php");
require_once("model/contabilidade/planoconta/ContaCorrente.model.php");
require_once("model/contabilidade/planoconta/ContaOrcamento.model.php");
require_once("model/contabilidade/planoconta/ContaPlanoPCASP.model.php");

db_app::import("exceptions.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("estoque.*");
db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");

db_app::import("contabilidade.contacorrente.*");

db_postmemory($HTTP_POST_VARS);

$clempparametro     = new cl_empparametro;
$clmatestoque       = new cl_matestoque;
$clmatestoqueitem   = new cl_matestoqueitem;
$clmatestoqueini    = new cl_matestoqueini;
$clmatestoqueinimei = new cl_matestoqueinimei;
$cldb_depart        = new cl_db_depart;
$cltransmater       = new cl_transmater;
$clempempitem       = new cl_empempitem;
$oDaoMatEstoqueItemNotaFiscal = db_utils::getDao("matestoqueitemnotafiscalmanual");

$res_empparametro = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu"),"e30_numdec"));
if ($clempparametro->numrows > 0){
  db_fieldsmemory($res_empparametro,0);
  if (trim($e30_numdec) == "" || $e30_numdec == 0){
    $numdec = 2;
  } else {
    $numdec = $e30_numdec;
  }
} else {
  $numdec = 2;
}

$db_opcao = 1;
$db_botao = true;
$passou   = false;

$iCodidoMovimentacaoEstoque = '';

/**
 * @todo refatorar para usar classe
 */

if (isset($incluir)) {

  if (isset($m60_codmater) && trim($m60_codmater)!=""){

		if ($m71_valor == 0 or $m71_quant == 0) {
			$sqlerro = true;
			$erro_msg = "Valores zerados!";
		} else {
			$sqlerro = false;
			db_inicio_transacao();
			$result_matestoque = $clmatestoque->sql_record($clmatestoque->sql_query_file(null,"m70_codigo,m70_quant,m70_valor","","m70_codmatmater=$m60_codmater and m70_coddepto=$coddepto"));
			if($clmatestoque->numrows>0){
				db_fieldsmemory($result_matestoque,0);
				$quant = 0;
				$valor = 0;
				$quant = $m70_quant+$m71_quant;
				if ($quant > 0){
					$valor = $m70_valor+$m71_valor;
				}
				$clmatestoque->m70_valor = "$valor";
				$clmatestoque->m70_quant = "$quant";
				$clmatestoque->m70_codigo= $m70_codigo;
				$clmatestoque->alterar($m70_codigo);
				if($clmatestoque->erro_status==0){
					$sqlerro=true;
				}
				$erro_msg = $clmatestoque->erro_msg;
			}else{
				$clmatestoque->m70_codmatmater = $m60_codmater;
				$clmatestoque->m70_coddepto    = $coddepto;
				$clmatestoque->m70_valor       = $m71_valor;
				$clmatestoque->m70_quant       = $m71_quant;
				$clmatestoque->incluir(null);
				if($clmatestoque->erro_status==0){
					$sqlerro=true;
				}
				$m70_codigo = $clmatestoque->m70_codigo;
				$erro_msg   = $clmatestoque->erro_msg;
			}
			if($sqlerro == false){
				$clmatestoqueini->m80_login          = db_getsession("DB_id_usuario");
				$clmatestoqueini->m80_data           = date("Y-m-d",db_getsession("DB_datausu"));
				$clmatestoqueini->m80_hora           = date('H:i:s');
				$clmatestoqueini->m80_obs            = $m80_obs;
				$clmatestoqueini->m80_codtipo        = $m80_codtipo;
				$clmatestoqueini->m80_coddepto       = $coddepto;
				$clmatestoqueini->incluir(@$m80_codigo);
				if($clmatestoqueini->erro_status==0){
					$sqlerro=true;
				}

				$iCodidoMovimentacaoEstoque = $clmatestoqueini->m80_codigo;
				$m82_matestoqueini          = $clmatestoqueini->m80_codigo;
				$erro_msg                   = $clmatestoqueini->erro_msg;
			}
			if($sqlerro == false){
				if(isset($m70_codigo) && trim($m70_codigo)!=""){
					$clmatestoqueitem->m71_codmatestoque = $m70_codigo;
					$clmatestoqueitem->m71_data          = date("Y-m-d",db_getsession("DB_datausu"));
					$clmatestoqueitem->m71_valor         = $m71_valor;
					$clmatestoqueitem->m71_quant         = $m71_quant;
					$clmatestoqueitem->m71_quantatend    = '0';
					$clmatestoqueitem->incluir(null);
					if($clmatestoqueitem->erro_status==0){
						$sqlerro=true;
					}
					$m80_matestoqueitem = $clmatestoqueitem->m71_codlanc;
					$erro_msg           = $clmatestoqueitem->erro_msg;
				}

				if (!$sqlerro) {

				  /**
				   * Inclui nota fiscal manual
				   */
				  if (!empty($m79_notafiscal) && !empty($m79_data)) {

  				  $oDaoMatEstoqueItemNotaFiscal->m79_sequencial     = null;
  				  $oDaoMatEstoqueItemNotaFiscal->m79_matestoqueitem = $m80_matestoqueitem;
  				  $oDaoMatEstoqueItemNotaFiscal->m79_notafiscal     = $m79_notafiscal;
  				  $oDaoMatEstoqueItemNotaFiscal->m79_data           = $m79_data;
				    $oDaoMatEstoqueItemNotaFiscal->incluir(null);
				  }
				}

				if ($sqlerro == false) {

				  if (trim($m77_lote) != "") {

				    $clmatestoqueitemlote = db_utils::getDao("matestoqueitemlote");
				    $clmatestoqueitemlote->m77_lote = $m77_lote;
				    $clmatestoqueitemlote->m77_dtvalidade = implode("-",array_reverse(explode("/", $m77_dtvalidade)));
				    $clmatestoqueitemlote->m77_matestoqueitem = $m80_matestoqueitem;
				    $clmatestoqueitemlote->incluir(null);
				    if ($clmatestoqueitemlote->erro_status == 0){

				      $erro_msg = $clmatestoqueitemlote->erro_msg;
					    $sqlerro  = true;
				    }
				  }
				}
				if (!$sqlerro) {

				  if (trim($m78_matfabricante) != "") {

				    $clmatestoqueitemfabric = db_utils::getDao("matestoqueitemfabric");
				    $clmatestoqueitemfabric->m78_matestoqueitem = $m80_matestoqueitem;
				    $clmatestoqueitemfabric->m78_matfabricante  = $m78_matfabricante;
				    $clmatestoqueitemfabric->incluir(null);
				    if ($clmatestoqueitemfabric->erro_status  == 0) {

				      $erro_msg = $clmatestoqueitemfabric->erro_msg;
					    $sqlerro  = true;
				    }
				  }
				}
				if ($sqlerro == false) {

					$clmatestoqueinimei->m82_matestoqueitem = $m80_matestoqueitem;
					$clmatestoqueinimei->m82_matestoqueini  = $m82_matestoqueini;
					$clmatestoqueinimei->m82_quant          = $m71_quant;
					$clmatestoqueinimei->incluir(@$m82_codigo);
					if($clmatestoqueinimei->erro_status==0){

						$erro_msg = $clmatestoqueinimei->erro_msg;
						$sqlerro=true;
					}
				}
			}

			if ($sqlerro==false){
				$passou=true;
			}
      
      if ($sqlerro == false) {

        $oDataImplantacao = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
        $oInstituicao     = new Instituicao(db_getsession('DB_instit'));

        /**
         * Efetua os Lancamentos Contabeis de entrada no estoque
         * - valida parametro de integracao da contabilidade com material
         */
        if ( USE_PCASP && ParametroIntegracaoPatrimonial::possuiIntegracaoMaterial($oDataImplantacao, $oInstituicao) ) {

          try {

            $oDadosEntrada                       = new stdClass();
            $oDadosEntrada->iMovimentoEstoque    = $clmatestoqueinimei->m82_codigo;
            $oDadosEntrada->sObservacaoHistorico = $m80_obs;
            $oDadosEntrada->nValorLancamento     = $m71_valor;
            $oDadosEntrada->iContaPCASP          = $m66_codcon;
            $oDadosEntrada->iCodigoMaterial      = $m60_codmater;

            $oAlmoxarifado = new Almoxarifado(db_getsession('DB_coddepto'));

            if (isset($entrada) && $entrada == "true") {
              $oAlmoxarifado->entradaManual($oDadosEntrada);
            } else {
              $oAlmoxarifado->implantacaoEstoque($oDadosEntrada);
            }

          } catch (BusinessException $eErro) {

            $sqlerro  = true;
            $erro_msg = $eErro->getMessage();

          } catch (ParameterException $eErro) {

            $sqlerro  = true;
            $erro_msg = ($eErro->getMessage());
            /**
             * Erro Originado por conta corrente:
             */
            if ($eErro->getCode() == '1010') {

              $erro_msg .= "\nDicas: Verifique o cadastro das contas do grupo do material.";
            }

          } catch (Exception $eErro) {

            $sqlerro  = true;
            $erro_msg = ($eErro->getMessage());
          }

        }
      }
       
			db_fim_transacao($sqlerro);
			//exit;
		}
  }else{
    $sqlerro = true;
    $erro_msg = "Usuário: \\n\\nCódigo do material não informado.\\n\\nAdministrador:";
  }
}
if (!isset($coddepto)||$coddepto==""){
  $result_departamento = $cldb_depart->sql_record($cldb_depart->sql_query_file(db_getsession("DB_coddepto"),"coddepto,descrdepto"));
  db_fieldsmemory($result_departamento,0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.m60_codmater.focus();" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<tr>
<td width="360" height="18">&nbsp;</td>
<td width="263">&nbsp;</td>
<td width="25">&nbsp;</td>
<td width="140">&nbsp;</td>
</tr>
</table>
<center>
<?
  include("forms/db_frmmatestoqueini.php");
?>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir)){
  db_msgbox($erro_msg);
  if($sqlerro==false){
    $location = "";
    if($m80_codtipo==3) {
      $location = "?entrada=true";
    }
    echo "
    <script>
    location.href = 'mat1_matestoqueini001.php$location';
    </script>
    ";
  }
}
?>