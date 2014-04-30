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
    
    <div id="arvoreCursos" name="arvoreCursos">

    </div>

  </body>
</html>

<script language="JavaScript">

var oTreeViewCursos = new DBTreeView('treeViewCalendarios');

function js_inicializa() {

  oTreeViewCursos.show($('arvoreCursos'));
  js_buscaCursos();

}

function js_buscaCursos() {

  var oParam       = new Object();
  
  oParam.exec      = "getDadosCursosTreeViewAtoLegal";
  oParam.iAtoLegal = <?=$iAtoLegal?>;
  oParam.iEscola   = <?=db_getsession("DB_coddepto")?>;

  sUrl             = "edu4_escola.RPC.php";

  js_webajax(oParam, 'js_retornoBuscaCursos', sUrl);

}

function js_retornoBuscaCursos(oRetorno) {

  var oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    oNoPrincipal = oTreeViewCursos.addNode("0", "Este Ato Legal não está anexado a nenhum Curso.");
    return false;
    
  } else {

	if (oRetorno.iRegistros == 0) {

      oNoPrincipal = oTreeViewCursos.addNode("0", "Nenhum curso vinculado a este Ato Legal.");

	} else {

      //Preenche os Cursos na árvore
      for (var iCont = 0; iCont < oRetorno.aCursos.length; iCont++) {
        oNoCurso = oTreeViewCursos.addNode(oRetorno.aCursos[iCont].codigo, 
                                           oRetorno.aCursos[iCont].descricao.urlDecode()
                                          );
      }

      for (var iCont = 0; iCont < oRetorno.aBases.length; iCont++) {
        oNoBase = oTreeViewCursos.addNode(oRetorno.aBases[iCont].codigo,
                                          "Base: "+oRetorno.aBases[iCont].descricao.urlDecode(),
                                          oRetorno.aBases[iCont].node_pai
                                         ); 
      }

      for (var iCont = 0; iCont < oRetorno.aEtapas.length; iCont++) {
        oNoEtapa = oTreeViewCursos.addNode(oRetorno.aEtapas[iCont].codigo,
                                           oRetorno.aEtapas[iCont].descricao.urlDecode(),
                                           oRetorno.aEtapas[iCont].node_pai
                                          );
      }

	}

  }

}

js_inicializa();

</script>