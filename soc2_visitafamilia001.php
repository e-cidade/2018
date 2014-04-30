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
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

$db_opcao = 1;

$oRotuloCampos = new rotulocampo();
$oRotuloCampos->label("as05_profissional");
$oRotuloCampos->label("as02_nis");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js, prototype.js, strings.js");
      db_app::load("estilos.css");
    ?>
  </head>
  <body style="margin-top: 25px; background-color: #CCCCCC;">
    <div>
      <center>
        <form action="" name="form1">
          <fieldset style="width: 500px;">
            <legend><b>Filtros do Relatório</b></legend>
            <table>
              <tr>
                <td>
                  <label><b>Data da Visita: </b></label>
                </td>
                <td>
                  <?php
                    db_inputdata('dataInicial', null, null, null, true, 'text', $db_opcao);
                  ?>
                  <b> até </b>
                  <?php
                    db_inputdata('dataFinal', null, null, null, true, 'text', $db_opcao);
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap="nowrap" style="font-weight: bold;">
                  <? db_ancora("Cidadão: ","js_pesquisaCidadao(true, false);",1);?>
                <td nowrap="nowrap">
                  <?php
                    db_input("codigoCidadao", 10, '', true, "text", 1, "onchange='js_pesquisaCidadao(false, false);'");
                    db_input("nome",          40, '', true, "text", 3);
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap="nowrap" style="font-weight: bold;">
                  <?php
                    db_ancora("NIS:", "js_pesquisaCidadao(true, true);", 1);
                  ?>
                </td>
                <td>
                <?php 
                  db_input("as02_nis", 10, $Ias02_nis, true, "text", 1, "onchange='js_pesquisaCidadao(false, true);'");
                ?>
                </td>
              </tr>
            </table>
          </fieldset>
          <input type="button" value="Imprimir Relatório" name='imprimir' id='btnImprimir'>
        </form>
      </center>
    </div>
    <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
</html>
<script type="text/javascript">

/**
 * Função para busca e validação do NIS 
 */
function js_pesquisaCidadao(lMostra, lNis) {

  var sUrl = 'func_cidadaofamiliacompleto.php?';
  
  if (lMostra) {

    sUrl += 'funcao_js=parent.js_mostraCidadao|ov02_sequencial|ov02_nome|as02_nis'; 
    js_OpenJanelaIframe('top.corpo', 'db_iframe_cidadaofamilia', sUrl, 'Pesquisa Cidadão',true);
  } else {

    if ($F('as02_nis') != '' && lNis) {
      sUrl += 'pesquisa_chave='+$F('as02_nis');
      sUrl += '&lNis=true';
    }
    
    if ($F('codigoCidadao') != ''  && !lNis) {
      
      sUrl += 'pesquisa_chave='+$F('codigoCidadao');
      sUrl += '&lCidadao=true';
    }

    if (($F('as02_nis') == '' && lNis) || ($F('codigoCidadao') == '' && !lNis)) {
      sUrl += 'pesquisa_chave=';
    }
    
    sUrl += '&funcao_js=parent.js_mostraCidadao2';

    if ($F('as02_nis') != '' || $F('codigoCidadao') != '') {

     js_OpenJanelaIframe('top.corpo', 'db_iframe_cidadaofamilia', sUrl, 'Pesquisa Cidadão', false);
    } else {
      
      $('codigoCidadao').value = "";
      $('nome').value          = "";
      $('as02_nis').value     = "";
    }
  }
}
 
function js_mostraCidadao (iCidadao, sCidadao, iNis) {

  if (iCidadao != "") {
    
    $('codigoCidadao').value   = iCidadao;
    $('nome').value            = sCidadao;
    $('as02_nis').value        = iNis;
  }
  db_iframe_cidadaofamilia.hide();
}

function js_mostraCidadao2(lErro, iCidadao, sCidadao, iNis) {

  $('nome').value            = sCidadao;
  $('codigoCidadao').value   = iCidadao;
  $('as02_nis').value        = iNis;
  
  if (lErro) {
    
    $('codigoCidadao').value = "";
    $('as02_nis').value      = "";
    $('nome').value          = iCidadao;
    
    if (iCidadao == '') {
      
      $('as02_nis').value      = iCidadao;
      $('codigoCidadao').value = iCidadao;
    }
  }
}



/**
 * Validamos o intervalo entre as datas selecionadas
 */
function js_validaData() {

  if ($('dataInicial').value != '' && $('dataFinal').value != '') {

    var aDataInicial = new Array();
    var aDataFinal   = new Array();

    aDataInicial[0]      = $F('dataInicial').substr(0, 2);
    aDataInicial[1]      = $F('dataInicial').substr(3, 2);
    aDataInicial[2]      = $F('dataInicial').substr(6, 4);
    var sNovaDataInicial = aDataInicial[2]+'-'+aDataInicial[1]+'-'+aDataInicial[0];

    aDataFinal[0]      = $F('dataFinal').substr(0, 2);
    aDataFinal[1]      = $F('dataFinal').substr(3, 2);
    aDataFinal[2]      = $F('dataFinal').substr(6, 4);
    var sNovaDataFinal = aDataFinal[2]+'-'+aDataFinal[1]+'-'+aDataFinal[0];

    if (js_diferenca_datas(sNovaDataInicial, sNovaDataFinal, 3) == true) {

      alert('Intervalo de datas inválido. Data final menor que a data inicial.');
      return false;
    }
  }
  return true;
}

/**
 * Imprime o formulario caso passe na validacao
 */
$('btnImprimir').observe("click", function() {

  if (js_validaData()) {

    var sLocation  = "soc2_visitafamilia002.php?";
    sLocation     += "&sOrigem=relatorio";
    sLocation     += "&sDataInicial="+$('dataInicial').value;
    sLocation     += "&sDataFinal="+$('dataFinal').value;
    sLocation     += "&iCodigoCidadao="+$('codigoCidadao').value;
    jan            = window.open(sLocation, 
                                 '', 
                                 'width='+(screen.availWidth-5)+
                                 ',height='+(screen.availHeight-40)+
                                 ',scrollbars=1,location=0');
    jan.moveTo(0,0);
  }
});
</script>