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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");

$oDaoBaseMunicipal = db_utils::getDao('cadastrounicobasemunicipal');
$sSqlBaseMunicipal = $oDaoBaseMunicipal->sql_query_file();

?>
<html>
<head>
	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<?
    db_app::load("scripts.js, prototype.js, strings.js, arrays.js, dbcomboBox.widget.js"); 
    db_app::load("estilos.css");
  ?>
  <style type="text/css">
    .bold{
      font-weight: bold;
    }
    .hr {
      border:none;
      border-top: 1px outset ;
    }
  </style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <center>
    <fieldset style="margin-top: 30px; width: 370px;">
      <legend class='bold'>Processar Avaliação Sócio Econômica</legend>
      <fieldset class="hr">
        <legend class='bold'>Sem avaliação atualizada</legend>
        <table>
          <tr>
            <td class='bold'>Familia:</td>
            <td><input type="text" id='familia' readonly="readonly" /></td>
          </tr>
          <tr>
            <td class='bold'>Cidadão:</td>
            <td><input type="text" id='cidadao' readonly="readonly" /></td>
          </tr>
          <tr>
            <td class='bold' colspan="2">
              <label>O processo de importação poderá demorar algumas horas.</label><br>
              <label>Recomendamos executa-lo no final do expediente.</label>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id='processarAvaliacao' name='processar' value='Processar'  />
    </fieldset>
  </center>
</body>  
<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</html>
<script type="text/javascript">
var url             = 'soc4_importabasemunicipio.RPC.php';

/**
 * Processa o arquivo
 */
function js_bucarNumeroCidadaoSemAvaliacao() { 

  var oObject         = new Object();
  oObject.exec        = "getTotalCidadoesFamiliasSemAvaliacao";
  var objAjax   = new Ajax.Request (url,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject), 
                                         onComplete:js_retornoNumeroCidadaoSemAvaliacao
                                        }
                                   );
}

function js_retornoNumeroCidadaoSemAvaliacao(oJson) {

  var oRetorno = eval("("+oJson.responseText+")");
  var oFamilia = $('familia');
  var oCidadao = $('cidadao');
  oFamilia.value = oRetorno.qtdFamiliaSemAvaliacao;
  oCidadao.value = oRetorno.qtdCidadaoSemAvaliacao;
}

function js_processar() { 

  var oObject         = new Object();
  oObject.exec        = "processaAvaliacao";
  js_divCarregando('Aguarde<br>Esse processo poderá demorar algumas horas ...','msgBox');
  var objAjax   = new Ajax.Request (url,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject), 
                                         onComplete:js_retornoProcessar
                                        }
                                   );
}

function js_retornoProcessar(oJson) {

  js_removeObj("msgBox");  
  var oRetorno = eval("("+oJson.responseText+")");
  alert(oRetorno.message.urlDecode());
}



$('processarAvaliacao').observe('click', function() {

  if (!confirm('Confirma o processamento?\nO processamento poderá levar algumas horas.')) {
    return false;
  }
  js_processar();
});

js_bucarNumeroCidadaoSemAvaliacao();
</script>