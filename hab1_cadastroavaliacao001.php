<?
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
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$iCodigoAvaliacao = ''; 
$aParametros      = db_stdClass::getParametro("habitparametro", array(db_getsession("DB_anousu")));
if (count($aParametros) > 0) {
  $iCodigoAvaliacao = $aParametros[0]->ht16_avaliacao;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
               dbViewAvaliacoes.classe.js,dbmessageBoard.widget.js,dbautocomplete.widget.js,dbcomboBox.widget.js,
               datagrid.widget.js");
  db_app::load("estilos.css,grid.style.css");
?></head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br>
<br>
<center>
<div id='questionario'>
</div>
</center>
</body>
</html>
<script>

var sUrlRPC = 'hab4_inscricaocanditado.RPC.php';
var iCodigoAvaliacao = '<?=$iCodigoAvaliacao?>';

function js_abreAvaliacao() {
  
  if (iCodigoAvaliacao == '') {
    alert('Codigo de avaliacao não configurado nos parametros da Habitação!');
    return false;
  }
  
  iCadastroEconomico = new dbViewAvaliacao(iCodigoAvaliacao, '', $('questionario'));
  iCadastroEconomico.mostrarMensagensSucesso(false);
  iCadastroEconomico.setCompleteFunction(function() {
    js_SalvarCandidato();
  });
  
  iCadastroEconomico.show();
  $('btnSalvarAvaliacao'+iCodigoAvaliacao).value='Salvar Cadastro';
  
}

function js_SalvarCandidato() {

  var aProgramas            = parent.iframe_grupoprograma.$$('input[name=programahabitacao]');
  var aGrupos               = parent.iframe_grupoprograma.$$('input[name=grupohabitacao]');
  
  var oParam                = new Object();
  oParam.aGrupoInteresse    = new Array();
  oParam.aProgramaInteresse = new Array();
  oParam.iPrograma          = '';
  oParam.iAvaliacao         = iCadastroEconomico.iGrupoResposta;
  oParam.exec               = 'salvarCandidato';
  
  aGrupos.each(function (oCheckbox){
    if (oCheckbox.checked) {
      oParam.aGrupoInteresse.push(oCheckbox.value);
    }
  });
  
  aProgramas.each(function (oCheckbox){
    if (oCheckbox.checked) {
      oParam.aProgramaInteresse.push(oCheckbox.value);
    }
  });
 
  oParam.iCgm         = parent.iframe_grupoprograma.$F('z01_numcgm');
  oParam.iSituacaoCpf = parent.iframe_grupoprograma.$F('situacaocpf');
  
  if (oParam.aGrupoInteresse.length == 0) {
  
    alert('Informe um grupo de interesse!');
    return false;
  } 
  if (oParam.iCgm == '') {
  
    alert('informe um Candidato!');
    return false;
  }
  /**
   * Pega todos os familiares do CGM
   */
  aFamiliares =   parent.iframe_composicaofamiliar.aFamiliares;
  oParam.aFamiliares = new Array();
  aFamiliares.each(function(aFamiliar, id) {
    
    var oFamiliar   = new Object();
    oFamiliar.iCgm  = aFamiliar[0];
    oFamiliar.iTipo = aFamiliar[2];
    oParam.aFamiliares.push(oFamiliar);
  }); 
  
  js_divCarregando('Aguarde, Salvando dados do candidato...', 'msgBox');
  var oAjax   = new Ajax.Request(sUrlRPC,
                               {method:"post",
                                parameters:'json='+Object.toJSON(oParam),
                                onComplete: js_retornoSalvarCandidato
                               }
                              )
} 


function js_retornoSalvarCandidato(oAjax) {
 
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.iStatus == 1) {
    alert('Candidado salvo com sucesso!');
  } else {
   alert(oRetorno.sMsg.urlDecode()); 
  }
}

function setDados(iAvaliacao) {
  
  iCadastroEconomico = new dbViewAvaliacao(iCodigoAvaliacao, iAvaliacao, $('questionario'));
  iCadastroEconomico.mostrarMensagensSucesso(false);
  iCadastroEconomico.setCompleteFunction(function() {
    js_SalvarCandidato();
  });
  
  iCadastroEconomico.show();
  
  $('btnSalvarAvaliacao'+iCodigoAvaliacao).value='Salvar Cadastro';
}

js_abreAvaliacao();

</script>