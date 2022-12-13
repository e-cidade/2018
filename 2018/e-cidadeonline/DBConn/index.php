<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

  $sDados = file_get_contents( "../libs/db_conn.php" );
  $sDados = trim( $sDados );
?>
<html>
  <head>
    <link rel="stylesheet" href="http://dev09.dbseller.com.br/codemirror-3.19/doc/docs.css">
    <link rel="stylesheet" href="http://dev09.dbseller.com.br/codemirror-3.19/lib/codemirror.css">
    
    <script src="http://dev09.dbseller.com.br/codemirror-3.19/lib/codemirror.js"></script>
    <script src="http://dev09.dbseller.com.br/codemirror-3.19/addon/edit/trailingspace.js"></script>
    <script src="http://dev09.dbseller.com.br/codemirror-3.19/mode/javascript/javascript.js"></script>
    <script src="http://dev09.dbseller.com.br/codemirror-3.19/mode/css/css.js"></script>
    <script src="http://dev09.dbseller.com.br/codemirror-3.19/mode/clike/clike.js"></script>
    <script src="http://dev09.dbseller.com.br/codemirror-3.19/mode/php/php.js"></script>

    <script src="../scripts/prototype.js"></script>
    <script src="../scripts/strings.js"></script>
  </head>
  <body>
    <h3>EDITOR DE ARQUIVO: db_conn.php</h3>
    <form method="post" action="">
      <div id="textarea_editor" style="padding-top: 10px; margin-left: 27%;">
        <textarea id="editor"><?php echo $sDados; ?></textarea>
      </div>
      <div id="botao" style="padding: 10px; padding-bottom: 10px; text-align: center;">
        <input id="btnSalvar"    type="button" value="Salvar" />
        <input id="btnRestaurar" type="button" value="Restaurar Configurações Originais" />
      </div>
    </form>
  </body>
</html>
<script>

var sRpc = "manipuladbconn.RPC.php";
/**
 * Objeto com as propriedades a serem setadas no CodeMirror
 */
var oAtributos                = {};
    oAtributos.mode           = "application/x-httpd-php";
    oAtributos.lineNumbers    = true;
    oAtributos.indentUnit     = 2;
    oAtributos.indentWithTabs = true;
    oAtributos.enterMode      = "keep";
    oAtributos.tabMode        = "shift";

/**
 * Instancia do CodeMirror a ser carregado dentro do textarea
 * Recebe o elemento em que deve ser carregado e um objeto com os parâmetros setados
 */
var oCodeMirror = CodeMirror.fromTextArea( $('editor'), oAtributos );
    oCodeMirror.setSize( "60%", "70%" );

/**
 * Salva as alterações no código. Recebe como parâmetro o conteúdo do textarea
 */
$('btnSalvar').onclick = function() {

  var oParametro           = {};
      oParametro.sExecuta  = "alterar";
      oParametro.sConteudo = tagString( oCodeMirror.getValue().urlEncode() );

  var oDados            = {};
      oDados.method     = "post";
      oDados.parameters = "json=" + Object.toJSON( oParametro );
      oDados.onComplete = confirmaAlteracao;

  new Ajax.Request( sRpc, oDados );
};

/**
 * Confirmação da alteração
 */
function confirmaAlteracao( oResponse ) {

  var oRetorno = eval( "(" + oResponse.responseText + ")" );
  alert( oRetorno.sMensagem.urlDecode() );
  location.reload();
}

/**
 * Restaura o arquivo db_conn com as configurações originais
 */
$('btnRestaurar').onclick = function() {

  var sMensagem = "Ao restaurar as configurações originais, as alterações realizadas serão perdidas. Deseja continuar?";
  
  if ( !confirm( sMensagem ) ) {
    return;
  }
  
  var oParametro           = {};
      oParametro.sExecuta  = "restaurarArquivoOriginal";

  var oDados            = {};
      oDados.method     = "post";
      oDados.parameters = "json=" + Object.toJSON( oParametro );
      oDados.onComplete = confirmaRestauracao;

  new Ajax.Request( sRpc, oDados );
}

/**
 * Retorno da restauração. Apresenta a mensagem de confirmação da restauração ou de algum erro ocorrido
 */
function confirmaRestauracao( oResponse ) {

  var oRetorno = eval( '(' + oResponse.responseText + ')' );

  if ( oRetorno.iStatus == 2 ) {
    alert( oRetorno.sMensagem.urlDecode() );
  }
  
  location.reload();
}
</script>
