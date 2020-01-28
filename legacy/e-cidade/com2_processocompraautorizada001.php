<?php
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_solicita_classe.php");

$oRotulo = new rotulocampo;
$oRotulo->label("pc80_codproc");

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
<body bgcolor="#cccccc" style="margin-top: 25px" onload="">
<center>
  <form name="form1" method="post" action="">
    <fieldset style="width:450px;">
      <legend><b>Processo de Compras Autorizadas</b></legend>
      <table>
        <tr>
          <td style="font-weight: bolder;">
            <? db_ancora("Processos de Compra de : ","js_pesquisaProcessoCompras(true, true);",1);?>
          </td>
          <td>
            <?
              db_input("pc80_codproc", 10, $Ipc80_codproc, 
                       true, 
                       "text", 
                       4,
                       "onchange='js_pesquisaProcessoCompras(false, true);'",
                       "pc80_codprocini"
                      ); 
            ?>
          </td>
          <td style="font-weight: bolder;">
            <? db_ancora("<b>Até:</b> ","js_pesquisaProcessoCompras(true, false);",1);?> 
          </td>
          <td>
            <?
              db_input("pc80_codproc_fim", 10, $Ipc80_codproc, 
                       true, 
                       "text", 
                       4,
                       "onchange='js_pesquisaProcessoCompras(false, false);'",
                       "pc80_codprocfim"
                      ); 
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <b>Data de:</b>
          </td>
          <td>
            <?
              db_inputdata('datainicial',null ,null, null,true,'text',1);
            ?>
          </td>
          <td>
            <b> até:</b>
          </td>
          <td>
            <?
              db_inputdata('datafinal',null ,null, null,true,'text',1);
            ?>
          </td>
        </tr>
        <tr>
          <td> 
            <b>Situação:</b>
          </td>
          <td>
            <?
              $x = array("0"=>"Todos",
              					 "1"=>"Em Análise",
                         "2"=>"Autorizado",
                         "3"=>"Não Autorizado");
              db_select('situacao', $x, true, 1,"");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" id ='imprimir' name='Imprimir' value='imprimir' onclick="js_pesquisarProcessos();">
  </form>
</center>
</body>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script type="text/javascript">
function js_pesquisaProcessoCompras(mostra, lInicial) {

  var sFuncaoRetorno         = 'js_mostraProcessoInicial';
  var sFuncaoRetornoOnChange = 'js_mostraProcessoInicialChange';
  var sCampo                 = 'pc80_codprocini';
  if (!lInicial) {
   
    var sFuncaoRetorno         = 'js_mostraProcessoFinal';
    var sFuncaoRetornoOnChange = 'js_mostraProcessoFinalChange';
    var sCampo                 = 'pc80_codprocfim';
  }
  
  if (mostra) {
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_processo',
                        'func_pcproc.php?funcao_js=parent.'+sFuncaoRetorno+'|'+
                        'pc80_codproc','Pesquisa Processo de Compras',true);
  } else {
     
     var sValorCampo = $F(sCampo); 
     if (sValorCampo != '') {
        js_OpenJanelaIframe('top.corpo', 
                            'db_iframe_processo',
                            'func_pcproc.php?pesquisa_chave='+sValorCampo+
                            '&funcao_js=parent.'+sFuncaoRetornoOnChange,
                            'Pesquisa Processo de Compras', 
                            false);
     } else {
       $F(sCampo).value = '';
     }
  }
}

function js_mostraProcessoInicial(iProcesso) {
  
  $('pc80_codprocini').value = iProcesso;  
  db_iframe_processo.hide();
}

function js_mostraProcessoInicialChange(iProcesso, lErro) {
  
  if (lErro) {
    $('pc80_codprocini').value = '';
  } 
}

function js_mostraProcessoFinal(iProcesso) {
  
  db_iframe_processo.hide();
  $('pc80_codprocfim').value = iProcesso;  
}

function js_mostraProcessoFinalChange(iProcesso, lErro) {
  
  if (lErro) {
    $('pc80_codprocfim').value = '';
  } 
}

/**
 * Função que valida o formulário e envia os dados para a consulta que gera o relatório
 */
function js_pesquisarProcessos() {

  /**
   * Testamos se o intervalo de datas está correto. Isso se as datas foram informadas.
   * Caso o intervalo estiver errado, nós o corrigimos.
   */
  if (($F('datainicial') !== '') && ($F('datafinal') !== '')) {

    if (js_comparadata($F('datainicial'), $F('datafinal'), ' > ')) {
  
			var sDataInicial       = $F('datainicial');
			var sDataFinal         = $F('datafinal');
			$('datainicial').value = sDataFinal;
			$('datafinal').value   = sDataInicial;
    }
  }

  /**
   * Testamos se o intervalo de processos de compra está correto. Isso se os mesmo forem informados.
   * Caso o intervalo estiver errado, nós o corrigimos.
   */
  if (($F('pc80_codprocini') !== '') && ($F('pc80_codprocfim') !== '')) {

    if ($F('pc80_codprocini') > $F('pc80_codprocfim')) {

      var iProcessoInicial       = $F('pc80_codprocini');
      var iProcessoFinal         = $F('pc80_codprocfim');
      $('pc80_codprocini').value = iProcessoFinal;
      $('pc80_codprocfim').value = iProcessoInicial;
    }
  }

  /**
   * Enviamos, de fato os dados para a pesquisa e emitimos o relatório.
   */
  var sUrl  = 'com2_processocompraautorizada002.php';
  sUrl     += '?iProcessoInicial='+$F('pc80_codprocini'); 
  sUrl     += '&iProcessoFinal='+$F('pc80_codprocfim'); 
  sUrl     += '&dtInicial='+$F('datainicial'); 
  sUrl     += '&dtFinal='+$F('datafinal');
  sUrl     += '&iSituacao='+$F('situacao');
 
  var jan = window.open(sUrl, '', 
                        'location=0, width='+(screen.availWidth - 5)+'width='+(screen.availWidth - 5)+', scrollbars=1'); 
      jan.moveTo(0, 0);
}
</script>