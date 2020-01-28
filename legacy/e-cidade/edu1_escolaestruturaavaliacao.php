<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_db_depart_classe.php"));
require_once(modification("classes/db_escolaestrutura_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
$iEscola  = db_getsession("DB_coddepto");

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
?>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
 <fieldset style="width:98%;padding:1px; height: 80%">
   <legend><b>Infraestrutura da Escola</b></legend>
     <div id='questionario' style="height: 100%">
    </div>
 </fieldset>
</body>
</html>
<script>
  var sUrlRPCAvaliacaoEscola = "edu4_dadoscensoescola.RPC.php";
  var iCodigoAvaliacao       = '3000000';


  function js_getAvaliacaoEscola() {
     
     var oParametro  = new Object();
     oParametro.exec = "getAvaliacaoEscola";
     js_divCarregando('Aguarde, carregando dados da Avaliação', 'msgBox'); 
     var oAjax = new Ajax.Request(sUrlRPCAvaliacaoEscola,
                                   {method:'post',
                                   parameters:'json='+Object.toJSON(oParametro),
                                   onComplete: js_montarAvaliacao
                                   });
  
  }
  
  function js_montarAvaliacao (oResponse) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oResponse.responseText+")");    
    if (oRetorno.status == 1) {
       
       oAvaliacaoEscola  = new dbViewAvaliacao(iCodigoAvaliacao, oRetorno.iCodigoAvaliacao, $('questionario'));
       oAvaliacaoEscola.show();
       $('btnSalvarPerguntas'+iCodigoAvaliacao).style.display = 'none';
       $('btnSalvarAvaliacao'+iCodigoAvaliacao).value         = 'Salvar';
    } else {
      var sMsg = 'Dados da avaliação não disponíveis.';
      if (oRetorno.message != "") {
        sMsg = oRetorno.message.urlDecode();
      } 
      alert(sMsg);
    }
  }
  
  js_getAvaliacaoEscola();
</script>