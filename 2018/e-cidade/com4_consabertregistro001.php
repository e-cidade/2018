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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_liborcamento.php");
require_once ("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$db_opcao = 3;
$clrotulo->label("pc10_numero");
$clrotulo->label("pc10_depto");
$clrotulo->label("descrdepto");
$clrotulo->label("pc67_motivo");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?
db_app::load("scripts.js, strings.js, prototype.js,datagrid.widget.js, widgets/dbautocomplete.widget.js");
db_app::load("widgets/windowAux.widget.js");
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>

 .fora {background-color: #d1f07c;}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
  </table>
  <center>
    <table style="margin-top: 20px;">
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Consultar Abertura RP</b>
            </legend>
            <table>
            <tr>
              <td nowrap title="" width="130">
                 <?
                  db_ancora("<b>Abertura do Registro:</b>","js_pesquisar();",1);
                 ?>
              </td>
              <td colspan="2">
                <?
                db_input('pc10_numero',10,$Ipc10_numero,true,'text',3)
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="" width="130">
                 <?
                  db_ancora("<b>Estimativa do Registro:</b>","js_pesquisarEstimativas();",1);
                 ?>
              </td>
              <td colspan="2">
                <?
                db_input('pc54_estimativa',10,$Ipc10_numero,true,'text',3)
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="" >
                 <?
                  db_ancora("<b>Compilação do Registro:</b>","js_pesquisarCompilacao();",1);
                 ?>
              </td>
              <td colspan="2">
                <?
                db_input('pc54_compilacao',10,$Ipc10_numero,true,'text',3)
                ?>
              </td>
            </tr>
            <tr>
              <td><b>Data:</b></td>
              <td>
                <?
                  $dtini     = "";
                  $dtini_dia = "";
                  $dtini_mes = "";
                  $dtini_ano = "";
                  db_inputdata("dtini",$dtini_dia,$dtini_mes,$dtini_ano,true,"text",1);
                ?>
              </td>
              <td>
                <b>Até:</b>
                <?
                  $dtfim     = "";
                  $dtfim_dia = "";
                  $dtfim_mes = "";
                  $dtfim_ano = "";
                  db_inputdata("dtfim", $dtfim_dia, $dtfim_mes, $dtfim_ano, true, "text", 1);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <?
                  db_ancora("<b>Departamento:</b>", "js_departamento(true);", 1);
                ?>
              </td>
              <td>
                <?
                 db_input('pc10_depto', 14, $Ipc10_depto, true, 'text', 1, "onchange='js_departamento(false);'");
                ?>
              </td>
              <td align="left">
                <?
                 db_input('descrdepto', 40, $Idescrdepto, true, 'text', 3);
                ?>
              </td>
            </tr>
          </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td style="text-align: center;">
          <input type='button' value='Pesquisar' onclick="js_abrir();" >

        </td>
      </tr>
    </table>
  </center>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

var sUrlRC = 'com4_solicitacaoComprasRegistroPreco.RPC.php';

function js_pesquisar() {

  js_OpenJanelaIframe('',
                      'db_iframe_solicita',
                      'func_solicitaregistropreco.php?formacontrole=1,2&funcao_js=parent.js_mostraPesquisa|pc54_solicita&departamento=false',
                      'Abertura Registro de Preço',
                      true
                      );
}

function js_mostraPesquisa(chave1,chave2){

   $('pc10_numero').value = chave1;
   db_iframe_solicita.hide();
}

function js_pesquisarEstimativas() {

  js_OpenJanelaIframe('',
                      'db_iframe_estimativa',
                      'func_solicitaestimativa.php?funcao_js=parent.js_mostraPesquisaEstimativa|'+
                      'pc10_numero',
                      'Estimativas de Registro de Preço',
                      true
                      );
}

function js_mostraPesquisaEstimativa(chave1,chave2){

   $('pc54_estimativa').value = chave1;
   db_iframe_estimativa.hide();
}

function js_pesquisarCompilacao() {

  js_OpenJanelaIframe('',
                      'db_iframe_solicita',
                      'func_solicitacompilacao.php?funcao_js=parent.js_mostraPesquisaCompilacao|'+
                      'pc10_numero',
                      'Compilações de Registro de Preço',
                      true
                      );
}

function js_mostraPesquisaCompilacao(chave1,chave2){

   $('pc54_compilacao').value = chave1;
   db_iframe_solicita.hide();
}
function js_limpar() {

  $('pc10_numero').value = '';
  $('pc67_motivo').value = '';

}

function js_abrir(){

 var dtfim           = "";
 var dtini           = "";
 var pc10_depto      = "";
 var pc10_numero     = "";
 var funcao_js       = "";
 var sQuery          = "";
 var pc54_estimativa = "";
 if ($F('dtini') != "") {
  dtini = js_formatar($F('dtini'),'d',0);
 }

 if ($F('dtfim') != "") {
  dtfim = js_formatar($F('dtfim'),'d',0);
 }

 if ($F('pc10_numero') != "") {
  pc10_numero = $F('pc10_numero');
 }

 if ($F('pc10_depto') != "") {
  pc10_depto = $F('pc10_depto');
 }

 funcao_js = 'parent.retornoSelecao|pc10_numero';

 sQuery += "pc10_numero="+pc10_numero;
 sQuery += "&pc10_depto="+pc10_depto;
 sQuery += "&dtini="+dtini;
 sQuery += "&dtfim="+dtfim;
 sQuery += "&funcao_js="+funcao_js;
 sQuery += "&pc54_estimativa="+$F('pc54_estimativa');
 sQuery += "&pc54_compilacao="+$F('pc54_compilacao');

 js_OpenJanelaIframe('','db_iframe_consulta',
                     'com4_consabertregistro002.php?'+sQuery,
                     'Pesquisa',true);
}

function retornoSelecao(iNumero) {
  db_iframe_consulta.hide();
  js_exibeSelecao(iNumero);
}

function js_exibeSelecao(iNumero){

 var sQuery = "";
 var pc10_numero = iNumero;
 sQuery = "pc10_numero="+pc10_numero;

 js_OpenJanelaIframe('','db_iframe_consultaabertura',
                     'com4_consabertregistro003.php?'+sQuery,
                     'Pesquisa',true);
}

function js_departamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_depart',
                        'func_db_depart.php?funcao_js=parent.js_mostradepart1|coddepto|descrdepto',
                        'Pesquisa',true);
  }else{
     if($F('pc10_depto').trim() != ''){
        js_OpenJanelaIframe('','db_iframe_depart',
                            'func_db_depart.php?pesquisa_chave='+$F('pc10_depto')+'&funcao_js=parent.js_mostradepart',
                            'Pesquisa',false);
     }else{
       $('descrdepto').value = '';
     }
  }
}
function js_mostradepart(chave,erro){
  $('descrdepto').value = chave;
  if(erro==true){
    $('pc10_depto').focus();
    $('pc10_depto').value = '';
  }
}
function js_mostradepart1(chave1,chave2){
  $('pc10_depto').value = chave1;
  $('descrdepto').value = chave2;
  db_iframe_depart.hide();
}



</script>