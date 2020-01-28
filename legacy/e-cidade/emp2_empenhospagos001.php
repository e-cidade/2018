<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oRotuloSaltes = new rotulo('saltes');
$oRotuloSaltes->label();

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    </script>  
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
      #ctnEmpenhosPagos select {
        width: 150px;
      }
    </style>
  </head>
  <body style="margin-top: 25px; background-color: #cccccc;">
  <div id="ctnEmpenhosPagos">
  <form name="form1" id="form1">
    <center>
      <fieldset style="width: 500px;">
        <legend><b>Empenhos Pagos</b></legend>
        <table>
          <tr id="trFieldsetCredor">
            <td colspan="1" >
              <?php
                /*
                 * Seleção de Credor
                 */
                $oPluginCredor                 = new cl_arquivo_auxiliar();
                $oPluginCredor->cabecalho      = "<strong>Credor</strong>";
                $oPluginCredor->codigo         = "z01_numcgm"; //chave de retorno da func
                $oPluginCredor->descr          = "z01_nome";   //chave de retorno
                $oPluginCredor->nomeobjeto     = 'credor';
                $oPluginCredor->funcao_js      = 'js_pesquisaCredor';
                $oPluginCredor->funcao_js_hide = 'js_pesquisaCredor1';
                $oPluginCredor->sql_exec       = "";
                $oPluginCredor->func_arquivo   = "func_movimentacaoempenhopago.php";  //func a executar
                $oPluginCredor->nomeiframe     = "db_iframe_cgm";
                $oPluginCredor->localjan       = "";
                $oPluginCredor->db_opcao       = 2;
                $oPluginCredor->tipo           = 2;
                $oPluginCredor->top            = 0;
                $oPluginCredor->linhas         = 5;
                $oPluginCredor->vwidth         = 400;
                $oPluginCredor->nome_botao     = 'db_lanca';
                $oPluginCredor->fieldset       = false;
                $oPluginCredor->Labelancora    = "Credor:";
                $oPluginCredor->funcao_gera_formulario();
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap="nowrap">
              <?php
                db_ancora("<b>Conta Pagadora</b>", "js_pesquisaContaPagadora(true);", 1); 
              ?>
            </td>
            <td>
              <?php 
                db_input("k13_conta", 10, $Ik13_conta, true, 'text', 1, "onchange='js_pesquisaContaPagadora(false);'");
                db_input("k13_descr", 30, $Ik13_descr, true, 'text', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td><b>Período:</b></td>
            <td>
              <?php 
                $aPeriodoDatas = explode('-', date('Y-m-d', db_getsession('DB_datausu')));
                list($iAnoInicial, $iMesInicial, $iDiaInicial) = $aPeriodoDatas;
                echo "<b>De: </b>";
                db_inputdata("dtDataInicial", $iDiaInicial, $iMesInicial, $iAnoInicial, true, 'text', 1);
                echo "<b> até </b>";
                db_inputdata("dtDataFinal", $iDiaInicial, $iMesInicial, $iAnoInicial, true, 'text', 1);
              ?>
            </td>
          </tr>
          <tr>
            <td><b>Ordem:</b></td>
            <td>
              <?php 
                $aOrdem = array("empenho" => "Empenho", "autenticacao" => "Autenticação");
                db_select("sTipoOrdem", $aOrdem, true, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td><b>Quebra por Conta:</b></td>
            <td>
              <?php 
                $aQuebraConta = array("t" => "Sim", "f" => "Não");
                db_select("lQuebraConta", $aQuebraConta, true, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td><b>Lista Empenho:</b></td>
            <td>
              <?php 
                $aListaEmpenho = array(0 => "Geral", 1 => "Exercício", 2 => "Restos à Pagar");
                db_select("iListaEmpenho", $aListaEmpenho, true, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td><b>Baixa:</b></td>
            <td>
              <?php 
                $aTipoBaixa = array(1 => "Todas", 2 => "Valor Líquido Pago", 3 => "Valor Retido");
                db_select("iTipoBaixa", $aTipoBaixa, true, 1);
              ?>
            </td>
          </tr>
        </table>      
      </fieldset>
      <br />
      <input type="button" id="btnImprimir" value="Imprimir" />
    </center>
  </form>
  </div>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
  </body>
</html>
<script>
  $('btnImprimir').observe('click', function() {

    var iTotalCredores        = $('credor').length;
    var aCredoresSelecionados = $('credor');
    var sCredoresSelecionados = "";
    var sVirgula              = "";
    for (var iRowCredor = 0; iRowCredor < iTotalCredores; iRowCredor++) {

      var oDadosCredor = aCredoresSelecionados[iRowCredor];
      sCredoresSelecionados += sVirgula+oDadosCredor.value;
      sVirgula               = ", ";
    }

    var sDataInicialBanco = js_formatar($F('dtDataInicial'), 'd');
    var sDataFinalBanco   = js_formatar($F('dtDataFinal'), 'd');

    if (sDataInicialBanco > sDataFinalBanco) {
      alert("A data inicial é maior que a data final. Verifique!");
      return false;
    }

    var sQueryLocation  = "emp2_empenhospagos002.php?";
    sQueryLocation     += "sCredoresSelecionados="+sCredoresSelecionados;
    sQueryLocation     += "&iContaPagadora="+$F('k13_conta');
    sQueryLocation     += "&dtDataInicial="+$F('dtDataInicial');
    sQueryLocation     += "&dtDataFinal="+$F('dtDataFinal');
    sQueryLocation     += "&sTipoOrdem="+$F('sTipoOrdem');
    sQueryLocation     += "&lQuebraConta="+$F('lQuebraConta');
    sQueryLocation     += "&iListaEmpenho="+$F('iListaEmpenho');
    sQueryLocation     += "&iTipoBaixa="+$F('iTipoBaixa');

    var oJanela = window.open(sQueryLocation,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    oJanela.moveTo(0,0);   
  });


  function js_pesquisaContaPagadora(lMostra){

    var sUrlQueryConta = "func_saltes.php?funcao_js=parent.js_preencheContaPagadora|k13_conta|k13_descr";
    if(!lMostra){
      sUrlQueryConta = "func_saltes.php?pesquisa_chave="+$F("k13_conta")+"&funcao_js=parent.js_completaContaPagadora";
    }
    js_OpenJanelaIframe('top.corpo', 'db_iframe_saltes', sUrlQueryConta, 'Pesquisa Conta Pagadora', lMostra);
  }
  function js_completaContaPagadora(chave,erro){

    $('k13_descr').value = chave; 
    if(erro==true){ 
      $('k13_conta').value = ''; 
    }
  }
  function js_preencheContaPagadora(chave1,chave2){
    $('k13_conta').value = chave1;
    $('k13_descr').value = chave2;
    db_iframe_saltes.hide();
  }
  
</script>