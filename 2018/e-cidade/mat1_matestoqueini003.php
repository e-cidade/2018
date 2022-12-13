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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_matestoque_classe.php"));
require_once(modification("classes/db_matestoqueitem_classe.php"));
require_once(modification("classes/db_matestoqueini_classe.php"));
require_once(modification("classes/db_matestoqueinil_classe.php"));
require_once(modification("classes/db_matestoqueinill_classe.php"));
require_once(modification("classes/db_matestoqueinimei_classe.php"));
require_once(modification("classes/materialestoque.model.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

require_once(modification("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteBase.model.php"));
require_once(modification("model/financeiro/ContaBancaria.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlano.model.php"));
require_once(modification("model/contabilidade/planoconta/ClassificacaoConta.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaCorrente.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaOrcamento.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlanoPCASP.model.php"));


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


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmatestoque = new cl_matestoque;
$clmatestoqueitem = new cl_matestoqueitem;
$clmatestoqueini = new cl_matestoqueini;
$clmatestoqueinil = new cl_matestoqueinil;
$clmatestoqueinill = new cl_matestoqueinill;
$clmatestoqueinimei = new cl_matestoqueinimei;
$db_botao = false;
$db_opcao = 33;

$iCodidoMovimentacaoEstoque = '';

if (isset($excluir)) {

  $sqlerro = false;
  $m80_codigo = (isset($m80_codigo)&&!empty($m80_codigo))?$m80_codigo:'null';
  $sSql = $clmatestoqueini->sql_query_mater(null,"matestoqueini.m80_codigo,
                                                                                            m70_codigo,
                                                                                            m71_codlanc,
                                                                                            m70_valor,
                                                                                            m70_codmatmater,
                                                                                            m70_coddepto,
                                                                                            m70_quant,
                                                                                            m71_quant,
                                                                                            m71_quantatend"
    ,"","matestoqueini.m80_codigo=$m80_codigo
                                                                                            and m70_codigo=$m70_codigo
                                                                                            and m71_codlanc=$m71_codlanc
                                                                                            and m71_quantatend=0");
//  dump_sql($sSql);die;
  $result_matestoque = $clmatestoqueini->sql_record($sSql);

  if ($clmatestoqueini->numrows>0) {

    db_inicio_transacao();
    db_fieldsmemory($result_matestoque,0);
    MaterialEstoque::bloqueioMovimentacaoItem($m70_codmatmater, $m70_coddepto);
    if($m71_quantatend==0){

      $clmatestoqueinil->m86_matestoqueini = $m80_codigo;
      $clmatestoqueinil->incluir(null);
      $vaipromatestoqueinill = $clmatestoqueinil->m86_codigo;
      if($clmatestoqueinil->erro_status==0){
        $erro_msg = $clmatestoqueinil->erro_msg;
        $sqlerro=true;
      }

      /*
       * busca a data e hora do registro
      */
      $result_data_registro = $clmatestoqueini->sql_record($clmatestoqueini->sql_query_file("","m80_data, m80_hora","","m80_codigo=$m80_codigo"));
      if($clmatestoqueini->numrows>0){
        db_fieldsmemory($result_data_registro,0);

        /*
         * compara se a data do sistema é menor ou igual(se for igual testa hora) que a data do registro, se for menor não cencela o registro, gerar erro e mensagem
        */
        if ( date("Y-m-d",db_getsession("DB_datausu")) < $m80_data ){

          $erro_msg = 'Data atual é anterior a data do registro, cancelamento abortado!';
          $sqlerro=true;

        } else {

          if ( date("Y-m-d",db_getsession("DB_datausu")) == $m80_data ){
        	   if ( db_hora() <=  $m80_hora){

        	   	 $erro_msg = 'Hora atual dever ser posterior a hora e data do registro, cancelamento abortado!';
        	   	 $sqlerro=true;

        	   }
          }

        }

      }



     if ($m80_codtipo == 1) {
       $m80_codtipo = 2;
     } else if($m80_codtipo == 3) {
	     $m80_codtipo = 4;
     }

      $m80_login = db_getsession("DB_id_usuario");
      $m80_data  = date("Y-m-d",db_getsession("DB_datausu"));
      $m80_hora  = date('H:i:s');
      $m80_coddepto = db_getsession("DB_coddepto");
      if ($sqlerro==false) {

				$clmatestoqueini->m80_login          = $m80_login;
		    $clmatestoqueini->m80_data           = $m80_data;
				$clmatestoqueini->m80_hora           = $m80_hora;
				$clmatestoqueini->m80_obs            = $m80_obs;
				$clmatestoqueini->m80_codtipo        = $m80_codtipo;
				$clmatestoqueini->m80_coddepto       = $m80_coddepto;
				$clmatestoqueini->incluir(null);

				$iCodidoMovimentacaoEstoque = $clmatestoqueini->m80_codigo;
				$matestoqueininovo          = $clmatestoqueini->m80_codigo;
				$erro_msg = $clmatestoqueini->erro_msg;
				if($clmatestoqueini->erro_status==0){
				  $sqlerro=true;
				  $erro_msg = $clmatestoqueini->erro_msg;
				}
		  }

      if($sqlerro==false){
				$clmatestoqueinill->m87_matestoqueini  = $matestoqueininovo;
				$clmatestoqueinill->m87_matestoqueinil = $vaipromatestoqueinill;
				$clmatestoqueinill->incluir($vaipromatestoqueinill);
				if($clmatestoqueinill->erro_status==0){
				  $erro_msg = $clmatestoqueinill->erro_msg;
				  $sqlerro=true;
				}
			}

      $quantestoque = $m70_quant-$m71_quant;
      $valorestoque = $m70_valor-$m71_valor;

      if($sqlerro==false){
				$clmatestoque->m70_codigo = $m70_codigo;
			  $clmatestoque->m70_valor  = "$valorestoque";
				$clmatestoque->m70_quant  = "$quantestoque";
				$clmatestoque->alterar($m70_codigo);
				if($clmatestoque->erro_status==0){
				  $erro_msg = $clmatestoque->erro_msg;
				  $sqlerro=true;
				}
			}

      if($sqlerro==false){
        $clmatestoqueitem->m71_codlanc    = $m71_codlanc;
        $clmatestoqueitem->m71_quantatend = $m71_quant;
				$clmatestoqueitem->alterar($m71_codlanc);
				if($clmatestoqueitem->erro_status==0){
				  $erro_msg = $clmatestoqueitem->erro_msg;
				  $sqlerro=true;
				}
      }

      if($sqlerro == false){
        $clmatestoqueinimei->m82_matestoqueitem = $m71_codlanc;
        $clmatestoqueinimei->m82_matestoqueini  = $matestoqueininovo;
        $clmatestoqueinimei->m82_quant          = $m71_quant;
        $clmatestoqueinimei->incluir(null);
        if($clmatestoqueinimei->erro_status==0){

          $erro_msg = $clmatestoqueiniimei->erro_msg;
          $sqlerro=true;
        }
      }


      $oInstituicao = new Instituicao(db_getsession("DB_instit"));
      $dtAtual      = date("Y-m-d", db_getsession("DB_datausu"));
      $oDataAtual   = new DBDate($dtAtual);

      /**
       * Efetua os Lancamentos Contabeis de entrada no estoque
       */
      if ($sqlerro == false && USE_PCASP  &&  (ParametroIntegracaoPatrimonial::possuiIntegracaoMaterial($oDataAtual, $oInstituicao) ))  {

        try {

          $oDadosEntrada                       = new stdClass();
          $sSqlBuscaValorEntrada = "select m89_valorfinanceiro 
                                      from matestoqueinimei 
                                           inner join matestoqueinimeipm on m89_matestoqueinimei =  m82_codigo 
                                     where m82_matestoqueini = {$m80_codigo} ";
          $rsBuscaValorEntrada = db_query($sSqlBuscaValorEntrada);
          $nValor = db_utils::fieldsMemory($rsBuscaValorEntrada, 0)->m89_valorfinanceiro;

          $oMaterialEstoque = new materialEstoque($m60_codmater);
          $oDadosEntrada->iMovimentoEstoque    = $clmatestoqueinimei->m82_codigo;
          $oDadosEntrada->sObservacaoHistorico = $m80_obs;
          $oDadosEntrada->nValorLancamento     = round($nValor, 2);
          $oDadosEntrada->iContaPCASP          = $m66_codcon;
          $oDadosEntrada->iCodigoMaterial      = $m60_codmater;
          $oAlmoxarifado = new Almoxarifado(db_getsession('DB_coddepto'));
          $oAlmoxarifado->saidaManual($oDadosEntrada);

        } catch (BusinessException $eErro) {

          $sqlerro  = true;
          $erro_msg = ($eErro->getMessage());
        } catch (Exception $eErro) {

          $sqlerro  = true;
          $erro_msg = ($eErro->getMessage());
        } catch (ParameterException $eErro) {

          $sqlerro  = true;
          $erro_msg = $eErro->getMessage();
        }
      }
//	  $sqlerro=true;
      db_fim_transacao($sqlerro);
    }else{
      $msgalert = "Usuário:\\n\\nLançamento já atendido.\\nCancelamento não efetuado.\\n\\nAdministrador:";
      unset($excluir);
    }
  }else{
    $msgalert= "Usuário:\\n\\nRegistro não encontrado.\\nContate o suporte.\\n\\nAdministrador:";
    unset($excluir);
  }
} else if(isset($chavepesquisa)) {
   $db_opcao = 3;
//   die($clmatestoqueini->sql_query_mater(null,"matestoqueini.m80_codigo,m70_codigo,m71_codlanc,m71_quantatend,m70_quant,m60_codmater,m60_descr,coddepto,descrdepto,m71_quant,m71_valor,(m71_valor/m71_quant) as m71_valorunit,matestoqueini.m80_obs","","matestoqueini.m80_codigo=$chavepesquisa and m71_quantatend=0"));
   $result = $clmatestoqueini->sql_record($clmatestoqueini->sql_query_mater(
                                          null,
                                          "matestoqueini.m80_codigo,
                                          m70_codigo,
                                          m71_codlanc,
                                          m71_quantatend,
                                          m70_quant,
                                          m60_codmater,
                                          m60_descr,
                                          coddepto,
                                          descrdepto,
                                          m71_quant,
                                          m77_lote,
                                          m77_dtvalidade,
                                          m78_matfabricante,
                                          m76_nome,
                                          m71_valor,
                                          m79_sequencial,
                                          m79_notafiscal,
                                          m79_data,
                                          (m71_valor/m71_quant) as m71_valorunit,
                                          matestoqueini.m80_obs",
                                          "",
                                          "matestoqueini.m80_codigo={$chavepesquisa} and m71_quantatend=0"));
   if($clmatestoqueini->numrows>0){
     db_fieldsmemory($result,0);
     if ($m77_dtvalidade != "") {
        list($m77_dtvalidade_ano,$m77_dtvalidade_mes,$m77_dtvalidade_dia) = explode("-",$m77_dtvalidade);
      }
     $db_botao = true;
   }else{
     $msgalert = "Usuário:\\n\\nLançamento não encontrado ou já atendido.\\nExclusão cancelada.\\n\\nAdministrador:";
     $db_opcao = 33;
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
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
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
	include(modification("forms/db_frmmatestoqueini.php"));
	?>
    </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  db_msgbox($erro_msg);
};
if(isset($msgalert)){
  db_msgbox($msgalert);
  $db_opcao=3;
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>