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

$oDaoDocumento = db_utils::getDao('db_documentotemplate');
$oDaoDocumento = new cl_db_documentotemplate();
$sCampos       = " db82_sequencial, db82_descricao";

$sSqlDocumentoTemplate = $oDaoDocumento->sql_query_file(null, $sCampos, null, "db82_templatetipo = 19");
$rsDocumentoTemplate   = $oDaoDocumento->sql_record($sSqlDocumentoTemplate);

if ($oDaoDocumento->erro_status == "0") {
  db_msgbox("N�o h� templates cadastrados no Banco de Dados.");
}


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
          <td style="font-weight: bolder;" nowrap="nowrap">
            <? db_ancora("Processos de Compra de : ","js_pesquisaProcessoCompras(true, true);",1);?>
          </td>
          <td nowrap="nowrap">
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
        </tr>
        <tr>
          <td nowrap="nowrap" style="font-weight: bolder;"> 
            <b>Documento Template:</b>
          </td>
          <td nowrap="nowrap">
            <?
             db_selectrecord('documentotemplate',$rsDocumentoTemplate,true,1,'');
            ?>
          </td>
        </tr>
      </table>
    </fieldset >
    <input type="button" id="btnImprimir" value="Imprimir" onclick="js_imprimeProcessoCompras();" 
           style="margin-top: 10px;" />
    </form>
  </center>
</body>
<?php 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script type="text/javascript">

/**
 * Fun��o que chama a lookup de Processos de compra
 */
function js_pesquisaProcessoCompras(mostra, lInicial) {

  var sFuncaoRetorno         = 'js_mostraProcessoInicial';
  var sFuncaoRetornoOnChange = 'js_mostraProcessoInicialChange';
  var sCampo                 = 'pc80_codprocini';
  if (mostra) {
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_processo',
                        'func_pcproc.php?funcao_js=parent.'+sFuncaoRetorno+'|'+'pc80_codproc&iAtivo=2',
                        'Pesquisa Processo de Compras',true);
  } else {
     
     var sValorCampo = $F(sCampo); 
     if (sValorCampo != '') {
        js_OpenJanelaIframe('top.corpo', 
                            'db_iframe_processo',
                            'func_pcproc.php?&iAtivo=2&pesquisa_chave='+sValorCampo+'&funcao_js=parent.'+sFuncaoRetornoOnChange,
                            'Pesquisa Processo de Compras', 
                            false);
     } else {
       $F(sCampo).value = '';
     }
  }
}

/**
 * Fun��o que trata o retorno da fun��o que pesquisa os processos de compra
 */
function js_mostraProcessoInicial(iProcesso) {
  
  $('pc80_codprocini').value = iProcesso;  
  db_iframe_processo.hide();
}

/**
 * Fun��o que trata o retorno da fun��o que pesquisa os processos de compra
 */
function js_mostraProcessoInicialChange(iProcesso, lErro) {
  
  if (lErro) {
    $('pc80_codprocini').value = '';
  } 
}

/**
 * Fun��o que emite o 
 */
function js_imprimeProcessoCompras() {
  
  if ($('pc80_codprocini') == "") {
    
    alert("Selecione um processo antes de imprimir o documento.")
    return false;
  }

  var sUrl  = 'com2_autorizacaoprocessocompras002.php';
  sUrl     += '?iCodigoProcesso='+$F('pc80_codprocini'); 
  sUrl     += '&iModeloImpressao='+$F('documentotemplate'); 
 
  var jan = window.open(sUrl, '', 
                        'location=0, width='+(screen.availWidth - 5)+'width='+(screen.availWidth - 5)+', scrollbars=1'); 
      jan.moveTo(0, 0);  
}
</script>