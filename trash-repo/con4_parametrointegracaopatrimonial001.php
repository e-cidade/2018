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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
?>
<style>

  .bloqueiaCampos {
  
    background-color:  #DEB887;
  
  }

</style>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
  <meta http-equiv="expires" content="0" />
  <link href="estilos.css" rel="stylesheet" type="text/css" />
  <?php db_app::load('prototype.js, scripts.js, strings.js'); ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <center> 

    <fieldset class="container" style="width: 550px;">

      <legend >Integração Patrimonial</legend>

      <fieldset style="margin-top: 10px;">
        <legend>Parâmetro Integração Patrimonial</legend>   
        <table class="form-container">
          <tr>   
            <td width='30%'>
              <strong>Data de Implantação: </strong>
            </td>
            <td> 
              <?php db_inputdata('dtPatrimonial', null, null, null, true, 'text', 1); ?>
            </td>
          </tr>
        </table>
      </fieldset>  
      
      <fieldset style="margin-top: 10px;">
        <legend>Parâmetro Integração Contrato</legend>   
        <table class="form-container" >
          <tr>   
            <td width='30%'>
              <strong>Data de Implantação: </strong>
            </td>
            <td align='left'> 
              <?php db_inputdata('dtContrato', null, null, null, true, 'text', 1); ?>
            </td>
          </tr>
        </table>
      </fieldset>  
      
      <fieldset style="margin-top: 10px;">
        <legend>Parâmetro Integração Material</legend>   
        <table class="form-container">
          <tr>   
            <td width='30%'>
              <strong>Data de Implantação: </strong>
            </td>
            <td> 
              <?php db_inputdata('dtMaterial', null, null, null, true, 'text', 1); ?>
            </td>
          </tr>
        </table>
      </fieldset>        
      
    </fieldset>

    <div style="margin-top: 10px;">
      <input id="salvar" type="button" value="Salvar" onClick="js_salvar();" />
    </div>

  </center>

  <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>

</body>
</html>
<script type="text/javascript">

 var sRPC = 'con4_parametrointegracaopatrimonial001.RPC.php';

function js_salvar() {
  
  var iParametrosParaCadastrar = 0;
  var oParametros = new Object();  

  if (! $('dtMaterial').readOnly && !empty($('dtMaterial').value) ) {

    oParametros.dtMaterial = js_formatar($('dtMaterial').value, 'd');
    iParametrosParaCadastrar++;
  }

  if (! $('dtPatrimonial').readOnly && !empty($('dtPatrimonial').value) ) {

    oParametros.dtPatrimonial = js_formatar($('dtPatrimonial').value, 'd');
    iParametrosParaCadastrar++;
  }
  
  if (!$('dtContrato').readOnly && !empty($('dtContrato').value) ) {

    oParametros.dtContrato = js_formatar($('dtContrato').value, 'd');
    iParametrosParaCadastrar++;
  }

  if ( iParametrosParaCadastrar == 0 ) {

    alert(_M('financeiro.contabilidade.con4_parametrointegracaopatrimonial001.nenhuma_data_informada'));
    return false;
  }

  js_divCarregando('Salvando Alterações...', 'msgBox');  
  oParametros.sExec = 'salvar';  

  new Ajax.Request(sRPC, {
    method     : "post",
    parameters : 'json='+Object.toJSON(oParametros),
    onComplete : js_retornoSalvar
   }); 

}

function js_retornoSalvar(oAjax) {

  js_removeObj('msgBox');
  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMessage = oRetorno.sMessage.urlDecode(); 

  alert(sMessage);

  if ( oRetorno.iStatus > 1 ) {
    return false;
  } 

  js_getDados();
}

function js_getDados() {

  js_divCarregando('Pesquisando Registros...', 'msgBox');
  var oParametros   = new Object();
  oParametros.sExec = 'getDados';  

  new Ajax.Request(sRPC, {
                   method     : "post",
                   parameters : 'json='+Object.toJSON(oParametros),
                   onComplete : js_retornoGetDados
                  });  
}
function js_retornoGetDados(oAjax) {

  js_removeObj('msgBox');
  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMessage = oRetorno.sMessage.urlDecode();

  oRetorno.aDadosRetorno.each (function(oDados, iIndice  ) {

    $('dtContrato').value = js_formatar(oDados.dtContratos  , 'd') ;
    $('dtMaterial').value = js_formatar(oDados.dtMaterial   , 'd') ;
    $('dtPatrimonial').value = js_formatar(oDados.dtPatrimonial, 'd') ;
    var iParametrosParaCadastrar = 3;
    
    if (oDados.dtContratos != '' ) {

      $('dtContrato').readOnly = true;
      $('dtContrato').className = 'bloqueiaCampos';
      document.getElementsByName('dtjs_dtContrato')[0] . style.display = 'none';
      iParametrosParaCadastrar--;
    }

    if (oDados.dtMaterial != '' ) {

      $('dtMaterial').readOnly = true;
      $('dtMaterial').className = 'bloqueiaCampos';
      document.getElementsByName('dtjs_dtMaterial')[0] . style.display = 'none';
      iParametrosParaCadastrar--;
    }

    if (oDados.dtPatrimonial != '' ) {

      $('dtPatrimonial').readOnly = true;
      $('dtPatrimonial').className = 'bloqueiaCampos';
      document.getElementsByName('dtjs_dtPatrimonial')[0] . style.display = 'none';
      iParametrosParaCadastrar--;
    }        
    
    if ( iParametrosParaCadastrar == 0 ) {      
      $('salvar').disabled = true;
    }

  });
  
}

js_getDados();

</script>