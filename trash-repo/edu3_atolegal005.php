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


require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
            
    <?
      $sLib  = "scripts.js,prototype.js,webseller.js,strings.js,DBTreeView.widget.js,";
      $sLib .= "estilos.css";
      db_app::load($sLib);
    ?>
                                      
  </head>
  <body>
                                          
    <div id="arvoreAnexos" name="arvoreAnexos">
                                              
    </div>
                                            
  </body>
</html>

<script language="JavaScript">

var oTreeViewAnexos = new DBTreeView('treeViewAnexos');

function js_inicializa() {

  oTreeViewAnexos.show($('arvoreAnexos'));
  js_buscaAnexos();

}

function js_buscaAnexos() {

  var oParam       = new Object();

  oParam.exec      = "getAnexosAtoLegal";
  oParam.iAtoLegal = <?=$iAtoLegal?>;

  sUrl             = "edu4_escola.RPC.php";

  js_webajax(oParam, 'js_retornoBuscaAnexos', sUrl);

}

function js_retornoBuscaAnexos(oRetorno) {

  var oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    oNo = oTreeViewAnexos.addNode("0", "Nenhum arquivo vinculado a este anexo.");
    return false;

  } else {

    if (oRetorno.aResultado.length < 1) {
      oNo = oTreeViewAnexos.addNode("0", "Nenhum arquivo vinculado a este anexo.");
    } else {

      oNoPrincipal = oTreeViewAnexos.addNode("0", "Arquivos");
      for (var iCont = 0; iCont < oRetorno.aResultado.length; iCont++) {

        oNode = oTreeViewAnexos.addNode(iCont,
                                        "<a onclick='js_downloadFile("+
                                          oRetorno.aResultado[iCont].ed292_sequencial+");'>"+
                                          oRetorno.aResultado[iCont].ed292_nomearquivo.urlDecode()+"</a>",
                                        "0"
                                       );
    
      }  

    }

  }

}

function js_downloadFile(iAnexo) {

  var oParam    = new Object();

  oParam.exec   = "getDownloadAnexoAtoLegal";
  oParam.iAnexo = iAnexo;

  var sUrl      = "edu4_escola.RPC.php";

  js_webajax(oParam, 'js_retornoDownloadFile', sUrl);

}

function js_retornoDownloadFile(oRetorno) {

  var oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {
    
    jan = window.open('db_download.php?arquivo='+oRetorno.sArquivo.urlDecode(), '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);

  }

}

js_inicializa();

</script>