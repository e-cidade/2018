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

//require("libs/db_stdlib.php");
require_once("fpdf151/scpdf.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_iptucalc_classe.php");
require_once("classes/db_iptunump_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_massamat_classe.php");
require_once("classes/db_iptuender_classe.php");
require_once("fpdf151/impcarne.php");
require_once("libs/db_sql.php");
require_once("classes/db_db_config_classe.php");
require_once("dbforms/db_funcoes.php");

$cliptucalc  = new cl_iptucalc;
$cliptuender = new cl_iptuender;
$cliptunump  = new cl_iptunump;
$clmassamat  = new cl_massamat;


db_postmemory($HTTP_POST_VARS);

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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC" >

<form name="form1" action="" method="post" >

<center>
<fieldset style="margin-top:50px; width: 300px;">
  <legend>
    <strong>Levantamento Cadastral</strong>
  </legend>
  
  <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td nowrap="nowrap" align="center">
          <strong>Formato:</strong>
      
          <?  
            
            $aOpcoes = array (/*"1" => "Geodados"    ,*/ 
                              "2" => "Versão 2"    , 
                              "3" => "Lista Pontos"
                             );
            db_select('formato', $aOpcoes, true, 1);
            ?>
      </td>
    </tr>
    <tr>
      <td> &nbsp;</td>
    </tr>

    <tr>
      <td height="25" align="center" colspan="2"> 
        <input name="geracarnes"  type="button" id="geracarnes" value="Gera Arquivo" onclick="js_geraDados();"> 
      </td>
    </tr>    
    
  </table>
</fieldset>
</center>
</form>


<? 
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>

<script>

//=============================================REFATORAÇAO FONTE 57105


var sUrlRPC = 'cad4_geralayoutgeodados.RPC.php';  
var oParam  = new Object();


function js_geraDados() {

  var iFormato         = $F('formato');
  var oParametros      = new Object();
  var msgDiv           = "Processando Dados. \n Aguarde ...";
  
  oParametros.exec     = 'geraDados';  
  oParametros.iFormato = iFormato;   
  
  js_divCarregando(msgDiv,'msgBox');
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoGeraDados
                                             });   
}

function js_retornoGeraDados(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");

  
  if (oRetorno.iStatus == 1) {

    var listagem  = oRetorno.sNomeArquivo + "# Download do Arquivo " + oRetorno.sNomeArquivo;

    js_montarlista(listagem,'form1'); 
    
  } else {

    alert(oRetorno.sMessage.url_decode());

  }
}

//=================================================================




function js_mostra_processando(){
  
  document.form1.processando.style.visibility='visible';
}

function termo(qual, total, sql){
  
  if (sql==0) {
    document.getElementById('termometro').innerHTML='processando registro... '+qual+' de '+total;
  } else {
    document.getElementById('termometro').innerHTML='processando select...';
  }
}
  
</script>