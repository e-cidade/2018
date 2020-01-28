<?
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_empageconf_classe.php");
include("classes/db_empagemov_classe.php");
include("classes/db_empagemovret_classe.php");
include("classes/db_empageconfgera_classe.php");
include("classes/db_empageconfcanc_classe.php");
include("classes/db_db_bancos_classe.php");
include("classes/db_empagedadosret_classe.php");
include("classes/db_empagedadosretmov_classe.php");
include("classes/db_errobanco_classe.php");
$clempageconf         = new cl_empageconf;
$clempagemov          = new cl_empagemov;
$clempagemovret       = new cl_empagemovret;
$clempageconfgera     = new cl_empageconfgera;
$clempageconfcanc     = new cl_empageconfcanc;
$cldb_bancos          = new cl_db_bancos;
$clempagedadosret     = new cl_empagedadosret;
$clempagedadosretmov  = new cl_empagedadosretmov;
$clerrobanco          = new cl_errobanco;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<div style="margin-top:50px;"></div>
	<form name="form1" id='form1' enctype="multipart/form-data" method="post">
    <center>
      <fieldset style="width: 530px; padding: 20px;">
        <legend><b>Processar Retorno</b></legend>
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap  align='center'><br><b>Indique o caminho do arquivo</b></td>
          </tr>
      	  <tr>
      	    <td nowrap align='center'><br><br>
              <input name="flArquivo" id='flArquivo' type="file" size='50'>
              <input name="nomearquivoservidor" id='nomearquivoservidor' type="hidden" size='10'>
      	    </td>
      	  </tr> 
        </table>
      </fieldset>
      <br>
      <input name="processa" id='btnProcessar' value='Processar' type="button">
    </center>
  </form>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<div id='uploadIframeBox' style='display:none'></div>
</body>
</html>
<script>
$('flArquivo').observe('change', function() {
   js_criarIframeBox('flArquivo', 'nomearquivoservidor');
});
function retornoUploadArquivo(sArquivo) {

  $('nomearquivoservidor').value = sArquivo;
}
function js_criarIframeBox(sIdCampo, sCampoRetorno) {
 
  js_divCarregando('Aguarde... carregando arquivo...', 'msgbox');
 
  var iFrame      = document.createElement("iframe");
  var sParametros = "clone=form1&idcampo="+sIdCampo+"&function=retornoUploadArquivo&camporetorno="+sCampoRetorno;
  iFrame.src      = "func_iframeupload.php?"+sParametros;
  iFrame.id       = 'uploadIframe';
  iFrame.width    = '100%';
   
  $('uploadIframeBox').appendChild(iFrame);
}
function js_endloading() {
 
  js_removeObj('msgbox');
  $('uploadIframeBox').removeChild($('uploadIframe'));
}

function js_processarArquivo() {

	js_divCarregando('Aguarde... processando...', 'msgbox');
  var oParametro         = new Object();
  oParametro.exec        = 'processarArquivo';
  oParametro.nomearquivo = $F('nomearquivoservidor');
  
  var oAjax = new Ajax.Request('emp4_pagamentofornecedor.RPC.php',
                               {method:'post',
                                parameters:'json='+Object.toJSON(oParametro),
                                onComplete: js_retornoProcessamentoArquivo
                               } 
                              );

}

function js_retornoProcessamentoArquivo(oAjax) {

	js_removeObj("msgbox");
  var oRetorno = eval("("+oAjax.responseText+")");

  /**
   * Verifica se houve erro no processamento do arquivo
   */
  if (oRetorno.status == 2) {
    
    alert(oRetorno.message.urlDecode());
    return false;
  }

  if (oRetorno.lArquivoProcessado) {

    var sMensagemProcessado  = "Identificamos que este arquivo possui um retorno ["+oRetorno.iRetornoProcessado+"].\n\n";
    sMensagemProcessado     += "Deseja acessar a rotina para baixar as movimentações deste retorno?";
    if (confirm(sMensagemProcessado)) {
      location.href = "emp4_selarquivo001.php?conf=true";
    }
    return false;
  }

  var sMensagem  = "Processamento concluído com sucesso!\n";
  sMensagem     += "Arquivos Gerados: "+oRetorno.aArquivosGerados.toString()+"\n\n";

  if (oRetorno.aMovimentosDescartados.length > 0) {

    sMensagem += "Identificamos que um ou mais movimentos já foram processados em outro momento, por este motivo\n";
    sMensagem += "desconsideramos os seguintes movimentos: "+oRetorno.aMovimentosDescartados.toString()+"\n\n";
  }
  
  if (oRetorno.aMovimentosCancelados.length > 0) {
    
    sMensagem += "Alguns movimentos foram cancelados após o envio ao banco. ";
    sMensagem += "Você deverá então emitir o relatório de arquivos cancelados, "; 
    sMensagem += "identificar os movimentos e realizar a baixa na Manutenção de Pagamentos na forma DEB.\n";
    sMensagem += "Movimentos Cancelados: "+oRetorno.aMovimentosCancelados.toString()+"\n\n";
  }
  
  if (oRetorno.aMovimentosNaoProcessados.length > 0) {

    sMensagem += "Alguns arquivos não foram processados devido a alguma inconsistência.\n\n";
    sMensagem += "Deseja emitir o relatório?";

    if (confirm(sMensagem)) {
      js_impressaoRelatorioInconsistencia(oRetorno.aArquivosGerados.toString());
    }
    js_redirecionaUsuario(oRetorno.aArquivosGerados);
    return false;
  }

  alert(sMensagem);
  js_redirecionaUsuario(oRetorno.aArquivosGerados);
}

/**
 *  Função criada para redirecionar o usuário para o Arquivo de Retorno devolvido pelo Banco de Dados
 *  Modificação: parâmetro é Array de códigos
 *  Gera url que possui parametro array codificado como string
 */
function js_redirecionaUsuario(aCodigoRetorno) {

  
  var sPaginaRedirecionada            = "emp4_empageretornoconf001.php?";
  var sCodificacaoArrayCodigoRetorno  = encodeURI(escape(aCodigoRetorno)); //aCodigoRetorno[(aCodigoRetorno.length-1)];
  sParametroGetArrayCodigoRetorno     = "retornoarq="+sCodificacaoArrayCodigoRetorno;
	location.href = sPaginaRedirecionada+sParametroGetArrayCodigoRetorno;
}

function js_impressaoRelatorioInconsistencia(iArquivoRetorno) {
  
  var sDireciona  = "emp4_inconsistenciaarquivoretorno002.php?";
      sDireciona += "iArquivoRetorno="+iArquivoRetorno;
      sDireciona += "&sDataInicial=&sDataFinal=";

  var oWinOpen = window.open(sDireciona,'',
                            'width='+(screen.availWidth-5)+
                            ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
}
$('btnProcessar').observe('click', js_processarArquivo);
</script>