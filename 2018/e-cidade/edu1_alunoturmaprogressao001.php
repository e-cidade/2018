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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js, webseller.js");
    db_app::load("estilos.css, grid.style.css");
  ?>
</head>
<body bgcolor=#CCCCCC>
<center>
  <div style="display:table;margin-top: 25px; width: 70%">
    <fieldset>
      <legend><b>Alunos Vinculados</b></legend>
      <div id='cntGrigAlunosVinculados'>
      </div>
    </fieldset>
  </div>
</center>
</body>
</html>
<script type="text/javascript">

var sRPC = "edu4_vincularalunoturma.RPC.php";
var oGet = js_urlToObject();

var oGridAlunosVinculados          = new DBGrid('cntGrigAlunosVinculados');
oGridAlunosVinculados.nameInstance = "oGridAlunosVinculados";

var aHeadersGrid                   = new Array("Código", "Aluno");
var aCellWidthGrid                 = new Array("20%", "80%");
var aCellAlign                     = new Array("center", "left");

oGridAlunosVinculados.setCellWidth(aCellWidthGrid);
oGridAlunosVinculados.setCellAlign(aCellAlign);
oGridAlunosVinculados.setHeader(aHeadersGrid);
oGridAlunosVinculados.setHeight(130);

oGridAlunosVinculados.show($('cntGrigAlunosVinculados'));

function js_getAlunosVinculados() {

  var oObject  = new Object;
  oObject.exec = 'getAlunosVinculados';
  oObject.iCodigoTurma = oGet.ed60_i_turma;

  js_divCarregando('Buscando Dados ...','msgBox');
  var objAjax   = new Ajax.Request (sRPC,{
                                           method:'post',
                                           parameters:'json='+Object.toJSON(oObject),
                                           asynchronous:false,
                                           onComplete:js_retornoAlunosVinculados
                                          }
                                   );
}

/**
 * Trata o retorno dos dados
 */
function js_retornoAlunosVinculados(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");

  oGridAlunosVinculados.clearAll(true);
  
  if (oRetorno.dados.length > 0 ) {

    oRetorno.dados.each(function (oAlunosVinculados, iLinha) {
    
       var aLinha = new Array();
       aLinha[0]  = oAlunosVinculados.iCodigoAluno;
       aLinha[1]  = oAlunosVinculados.sNomeAluno.urlDecode();
       oGridAlunosVinculados.addRow(aLinha);
    });
    
    oGridAlunosVinculados.renderRows();
  } else {
    oGridAlunosVinculados.setStatus("Sem Alunos Vinculados");
  }
}
js_getAlunosVinculados();
</script>