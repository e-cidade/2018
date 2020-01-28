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
          <fieldset style="width: 300px;">
            <legend><b>Filtros do Relatório</b></legend>
            <table>
              <tr>
                <td>
                  <?php
                    db_input('bpc_deficiente', 20, '', true, 'checkbox', $db_opcao);
                  ?>
                  <label for="bpc_deficiente"><b>BPC Deficiente</b></label>
                  <?php
                    db_input('bpc_idoso', 20, '', true, 'checkbox', $db_opcao);
                  ?>
                  <label for="bpc_idoso"><b>BPC Idoso</b></label>
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
var sUrlRPCBase = 'soc4_importabasemunicipio.RPC.php';

function js_pesquisaFamiliasSemAvaliacao() {

  var oParametro  = new Object();
  oParametro.exec = 'getTotalCidadoesFamiliasSemAvaliacao';

  var oAjax = new Ajax.Request(sUrlRPCBase,
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornaPesquisaFamiliasSemAvaliacao
                               }
                              );
}

/**
 * Caso existam familias ou cidadaos com avaliacoes nao processadas, apresenta a mensagem ao usuario
 */
function js_retornaPesquisaFamiliasSemAvaliacao(oResponse) {

  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.qtdFamiliaSemAvaliacao > 0 || oRetorno.qtdCidadaoSemAvaliacao > 0) {

    sMsg  = 'Existem avaliações ainda não processadas.';
    sMsg += '\nAvaliações de Famílias: '+oRetorno.qtdFamiliaSemAvaliacao;
    sMsg += '\nAvaliações de Cidadãos: '+oRetorno.qtdCidadaoSemAvaliacao;
    sMsg += '\nPara um relatório completo, processe as demais avaliações em: ';
    sMsg += '\nProcedimentos -> Cadastro Único -> Processar Avaliação Sócio Econômica';
    alert(sMsg);
  }
}

/**
 * Validamos se algum dos checkbox foi selecionado
 */
function js_imprimeRelatorio() {

  if ($('bpc_deficiente').checked == false && $('bpc_idoso').checked == false) {

    alert('Deve ser selecionado ao menos um dos filtros.');
    return false;
  }
  return true;
}

/**
 * Imprime o formulario caso passe na validacao
 */
$('btnImprimir').observe("click", function() {

  if (js_imprimeRelatorio()) {

    var sLocation  = "soc2_idosospossuembpc002.php?";
    sLocation += "&sBpcDeficiente="+$('bpc_deficiente').checked;
    sLocation += "&sBpcIdoso="+$('bpc_idoso').checked;
    jan = window.open(sLocation,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0); 
  }
});

js_pesquisaFamiliasSemAvaliacao()
</script>