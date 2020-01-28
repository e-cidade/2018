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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_utils.php");
require_once ("classes/db_arrecad_classe.php");
require_once ("classes/db_arrecant_classe.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($_POST);

$clArrecad  = new cl_arrecad();
$clArrecant = new cl_arrecant();

$aDados  = array();

if(!isset($k00_numpre)){
  db_redireciona("cai4_cancnumpre001.php");
  exit;
}
$sHtml   = "";
?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  <style type="text/css">
    .no-style{
      border: none;
      background-color: #CCC;
    }
  </style>
</head>
<body bgcolor=#CCCCCC onLoad="js_buscaDados(<?=$k00_numpre?>);" >
  <div style="height: 40px;"  ></div>
  <center>
    <form id="form1" name="form1" method="post" action="">
      <div id = "showGrid" style="width: 600px;">
      </div>
  	 
      <div>  
        <input type="button" name="cancelar" value="Cancelar" onclick="js_processa();">
        <input id="k00_numpre" name="k00_numpre" type="hidden" value="<?=$k00_numpre?>">
    	</div>
    </form>
  </center>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script type="text/javascript">
  
  var oGrid = new DBGrid('grid');
  
  function js_newGrid(aDados) {
  
    oGrid.nameInstance = 'oGrid';
    oGrid.setCellAlign(new Array('right','left','center', 'center'));
    oGrid.allowSelectColumns(false);
    oGrid.setCheckbox(0);
    oGrid.setHeader(new Array('oid', 'Tipo', 'Situação', 'Data Lançam.', 'Receita', 'Descrição da Receita', 'Valor', 'Parcela'));
    
    oGrid.aHeaders[1].lDisplayed = false;
    oGrid.aHeaders[2].lDisplayed = false;
    oGrid.iHeight = 300;
    
    oGrid.show($('showGrid'));
    oGrid.clearAll(true);
    
    for(var i = 0; i < aDados.length; i++) {
      
      var sTipo;
      if (aDados[i].tipo == "a") {
        sTipo = "Débito Pendente";
      } else {
        sTipo = "Débito Quitado";}

      oGrid.addRow(new Array(aDados[i].oid,
                             aDados[i].tipo,
                             sTipo,
                             js_formatar(aDados[i].k00_dtoper, 'd'),
                             aDados[i].k00_receit,
                             aDados[i].k02_drecei.urlDecode(),
                             js_formatar(aDados[i].k00_valor, 'f', 2),
                             aDados[i].k00_numpar
                            )
                      );
    }
    oGrid.renderRows();
    
  }
  
  function js_buscaDados(k00_numpre) {
  
    var url             = 'cai4_cancnumpre.RPC.php';
    var oObject         = new Object();
    oObject.exec        = "buscaDados"
    oObject.k00_numpre  = k00_numpre;
    
    js_divCarregando('Buscando ...','msgBox');
    var objAjax   = new Ajax.Request (url,{
                                           method:'post',
                                           parameters:'json='+Object.toJSON(oObject), 
                                           onComplete:js_retornoBusca
                                          }
                                     );
  }
  
  function js_retornoBusca(oJson) {
  
  
    js_removeObj("msgBox");  
    var oRetorno = eval("("+oJson.responseText+")");
    
    if (oRetorno.status == 2) {
    
      alert('Sem débitos a serem cancelados.'); 
      location.href = "cai4_cancnumpre001.php";
    } else {
      js_newGrid(oRetorno.aDados);
      
    }
  }
  
  function js_processa() {
  
    var url        = 'cai4_cancnumpre.RPC.php';
    var aDados     = new Array();
    var aDadosGrid = new Array();
    var oObject    = new Object();
    
    js_divCarregando('Processando ...','msgBox');
    
    oObject.exec   = "cancela"
    
    aDadosGrid          = oGrid.getSelection();
    
    for (var i=0; i < aDadosGrid.length; i++) {
    
      var oDados        = new Object();   
      oDados.k00_numpre = $F("k00_numpre");  
      oDados.oid        = aDadosGrid[i][1];
      oDados.tipo       = aDadosGrid[i][2]
      oDados.k00_valor  = aDadosGrid[i][7];
      oDados.k00_numpar = aDadosGrid[i][8];
      aDados[i]         = oDados;
      
    }
    
    oObject.dados       = aDados;  
   
    var objAjax   = new Ajax.Request (url,{
                                           method:'post',
                                           parameters:'json='+Object.toJSON(oObject), 
                                           onComplete: js_retornoCancelamento
                                          }
                                     );
   }

  function js_retornoCancelamento(oJson){
  
    js_removeObj("msgBox");
    var oRetorno = eval("("+oJson.responseText+")");
    
    if (oRetorno.status == 2) {
      alert(oRetorno.message.urlDecode()); 
    } else {
    
      alert("Cancelado Com Sucesso!");
      location.href = "cai4_cancnumpre001.php";
    }
    
  }      
  </script>

</body>
</html>