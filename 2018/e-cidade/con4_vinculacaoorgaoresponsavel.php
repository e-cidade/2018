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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_app.utils.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
     db_app::load("scripts.js, strings.js, prototype.js,datagrid.widget.js, widgets/dbautocomplete.widget.js");
     db_app::load("widgets/windowAux.widget.js");
    ?>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" style='margin-top:25px' leftmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <center>
      <form name='form1' id='form1'>
      <div style="display: table">
        <fieldset>
          <legend><b>Vincular responáveis aos órgãos</b></legend>
          <table>
            <tr>
              <td><b><? db_ancora('Órgão:', 'js_pesquisaOrgao()', 1); ?></b></td>
              <td>
                <? db_input('o40_orgao', 10, '', true, 'text', 1); ?>
                <? db_input('o40_descr', 40, '', true, 'text', 3); ?>
              </td>
            </tr>
            <tr>
              <td><b>CPF Responsável:</b></td>
              <td><? db_input('cpfresponsavel', 10, '', true, 'text', 1); ?></td>
            </tr>
            <tr>
              <td><b>Tipo de gestão de créditos:</b></td>
              <td>
                <?
                  $aTiposGestaoCredito = array(
                                           "1" => "Total",
                                           "2" => "Parcial"
                                         );
                  db_select('tipogestaocreditos', $aTiposGestaoCredito, true, 1);
                ?>
              </td>
            </tr>
            <tr>
              <td><b>Data de início da gestão:</b></td>
              <td><? db_inputdata('datainiciogestao', '', '', '', true, 'text', 1, ''); ?></td>
            </tr>
            <tr>
              <td><b>Tipo de Ordenador:</b></td>
              <td><? db_input('tipoordenador', 10, '', true, 'text', 1); ?></td>
            </tr>
            <tr height="15px">
              <td colspan="2"></td>
            </tr>
          </table>
        </fieldset>
      </div>
      <br>
      <input name="salvar" id="salvar" type="button" value="Salvar" align="center">
      </form>
    </center>
  </body>
</html>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

  /**
   * exibe a consulta de orgão
   */
  function js_pesquisaOrgao() {
  
    js_OpenJanelaIframe('',
                        'db_iframe_conorgao',
                        'func_orcorgao.php?funcao_js=parent.js_retornoOrgao|o40_orgao|o40_descr',
                        'Selecione o órgão desejado', true);
  }
  
  function js_pesquisaOrgaoQuery() {
  
    js_OpenJanelaIframe('', 'db_iframe_conorgao',
                        'func_orcorgao.php?pesquisa_chave='+$F('o40_orgao')+'&funcao_js=parent.js_retornoOrgao',
                        'Selecione o órgão desejado', false);
  }
  
  /**
   * tratamento do retorno da js_pesquisaOrgao
   */
  function js_retornoOrgao() {
    
    if (typeof(arguments[1]) == 'boolean') {
       
      if (arguments[1]) {
         
         $('o40_descr').value        = arguments[0];
         $('o40_orgao').value        = '';
         $('cpfresponsavel').value   = '';
         $('datainiciogestao').value = '';
         $('tipoordenador').value    = '';
       } else {
       
         $('o40_descr').value = arguments[0];
         js_buscaOrgao();
       }
    } else {
  
      $('o40_orgao').value = arguments[0];
      $('o40_descr').value = arguments[1];   
      db_iframe_conorgao.hide();
      js_buscaOrgao();
    } 
  }
  
  function js_buscaOrgao() {
    js_divCarregando('Aguarde, avaliando se há um vinculo ao Órgão no arquivo de configuração.', 'msgBox');
    var oParam              = new Object();
        oParam.exec         = 'consultaVinculosOrgao';
        oParam.iCodigoOrgao = $F('o40_orgao');
    var oAjax               = new Ajax.Request('con4_vinculacaoorgaoresponsavel.RPC.php',
                                               {method:'POST',
                                                parameters:'json='+Object.toJSON(oParam),
                                                onComplete:js_popularFormulario})
  }
  
  function js_popularFormulario(oAjax){
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 3) {
      $('cpfresponsavel').value     = oRetorno.dadosRecuperados['iCpfResponsavel'];
      $('tipogestaocreditos').value = oRetorno.dadosRecuperados['iTipoGestaoCreditos'];
      $('datainiciogestao').value   = oRetorno.dadosRecuperados['sDataInicioGestao'];
      $('tipoordenador').value      = oRetorno.dadosRecuperados['iTipoOrdenador'];
    }
  };
  
  $('o40_orgao').observe("change", function() {
     js_pesquisaOrgaoQuery($('o40_descr').value);
  });
  
  $('salvar').observe('click', function() {
     
     var iCodigoOrgao        = $('o40_orgao').value;
     var iCpfResponsavel     = $('cpfresponsavel').value;
     var iTipoGestaoCreditos = $('tipogestaocreditos').value;
     var sDataInicioGestao   = $('datainiciogestao').value;
     var iTipoOrdenador      = $('tipoordenador').value;
     
     if (iCodigoOrgao == '') {
     
       alert('Informe o Órgao.');
       return false;
     }
     
     if (iCpfResponsavel == '') {
     
       alert('Informe o CPF do responsável.');
       return false;
     }
     if (sDataInicioGestao == '') {
     
       alert('Informe a data do início da gestão do responsável.');
       return false;
     }
     if (iTipoOrdenador == '') {
     
       alert('Informe o tipo de ordenador.');
       return false;
     }
     
     js_divCarregando('Aguarde, vinculando Reponsáveis a Órgãos', 'msgBox');
     var oParam                     = new Object();
		     oParam.exec                = 'vincularOrgaos';
		     oParam.iCodigoOrgao        = iCodigoOrgao;
		     oParam.iCpfResponsavel     = iCpfResponsavel;
		     oParam.iTipoGestaoCreditos = iTipoGestaoCreditos;
		     oParam.sDataInicioGestao   = sDataInicioGestao;
		     oParam.iTipoOrdenador      = iTipoOrdenador;
		     var oAjax                  = new Ajax.Request('con4_vinculacaoorgaoresponsavel.RPC.php',
		                                                   {method: 'POST',
		                                                   parameters:'json='+Object.toJSON(oParam),
		                                                   onComplete:js_retornoVinculoOrgao} 
		                                                  ) 
  });
  
  function js_retornoVinculoOrgao(oAjax) {
  
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {
      alert (oRetorno.message.urlDecode());
      $('form1').reset();
    } else {
      alert (oRetorno.message.urlDecode());
    }
  }
  
</script>