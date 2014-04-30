<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_mer_cardapio_classe.php");
include("classes/db_mer_cardapiodata_classe.php");
include("classes/db_mer_cardapiodia_classe.php");
include("classes/db_mer_cardapioitem_classe.php");
include("classes/db_mer_cardapionutri_classe.php");
include("classes/db_mer_cardapiotipo_classe.php");
include("classes/db_mer_caractpreparo_classe.php");
include("classes/db_mer_modpreparo_classe.php");
include("classes/db_mer_subitem_classe.php");
include("classes/db_mer_tprefeicao_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmer_cardapio      = new cl_mer_cardapio;
$clmer_cardapiodata  = new cl_mer_cardapiodata;
$clmer_cardapiodia   = new cl_mer_cardapiodia;
$clmer_cardapioitem  = new cl_mer_cardapioitem;
$clmer_cardapionutri = new cl_mer_cardapionutri;
$clmer_cardapiotipo  = new cl_mer_cardapiotipo;
$clmer_caractpreparo = new cl_mer_caractpreparo;
$clmer_modpreparo    = new cl_mer_modpreparo;
$clmer_subitem       = new cl_mer_subitem;
$clmer_tprefeicao    = new cl_mer_tprefeicao;
$db_botao            = false;
$naopode             = false;
$db_opcao            = 33 ;
$me01_i_escola       = db_getsession("DB_coddepto");
$ed18_c_nome         = db_getsession("DB_nomedepto");
if (isset($excluir)) {
	
  db_inicio_transacao();
  $db_opcao = 3;
  $clmer_caractpreparo->excluir(""," me06_i_cardapio = $me01_i_codigo");
  $clmer_cardapiodia->excluir(""," me12_i_cardapio   = $me01_i_codigo");
  $clmer_cardapioitem->excluir(""," me07_i_cardapio  = $me01_i_codigo");
  $clmer_cardapionutri->excluir(""," me04_i_cardapio = $me01_i_codigo");
  $clmer_cardapiotipo->excluir(""," me21_i_cardapio  = $me01_i_codigo");
  $clmer_modpreparo->excluir(""," me05_i_cardapio    = $me01_i_codigo");
  $clmer_subitem->excluir(""," me29_i_refeicao       = $me01_i_codigo");
  $clmer_cardapio->excluir($me01_i_codigo);
  db_fim_transacao();
  
} else if (isset($chavepesquisa)) {
	
  $db_opcao  = 3;
  $result    = $clmer_cardapio->sql_record($clmer_cardapio->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  $db_botao  = true; 
  $dataatual = date("Y-m-d",db_getsession("DB_datausu"));
  $horaatual = date("H:i");
  $result_verif1 = $clmer_cardapiodata->sql_record($clmer_cardapiodata->sql_query("",
                                                                                  "*",
                                                                                  "",
                                                                                  "me12_i_cardapio = $chavepesquisa"
                                                                                 ));
                                                                                 
  $sWhere        =  " me12_i_cardapio = $chavepesquisa AND (me12_d_data < '$dataatual' OR (me12_d_data = '$dataatual' "; 
  $sWhere       .=  "                                  AND me03_c_fim < '$horaatual')) ";
  $sWhere       .=  "                                  AND not exists(select * from mer_cardapiodata inner join mer_cardapiodiaescola on me37_i_codigo = me13_i_cardapiodiaescola";
  $sWhere       .=  "                                      where me12_i_codigo = me37_i_cardapiodia)";                                                                               
  $result_verif2 = $clmer_cardapiodia->sql_record($clmer_cardapiodia->sql_query("",
                                                                                "*",
                                                                                "",
                                                                                $sWhere
                                                                               ));
  $msg_error  = " $me01_i_codigo - $me01_c_nome - Versão: $me01_f_versao ";
  $msg_error .= " <br><br>Exclusão não permitida para esta refeição.<br><br>";
  if ($clmer_cardapiodata->numrows>0) {
  	
    $msg_error .= "<font color=red>-> Refeição já contem registro de baixa no estoque;</font><br>";
    $db_botao = false;
    $naopode = true;
    
  }
  if ($clmer_cardapiodia->numrows>0) {
  	
    $msg_error .= "<font color=red>-> Refeição já foi consumida em uma data inferior a data corrente;</font><br><br>";
    $db_botao = false;
    $naopode = true;
    
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:97%"><legend><b>Exclusão de Refeição </b></legend>
    <?include("forms/db_frmmer_cardapio.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if (isset($excluir)) {
	
  if ($clmer_cardapio->erro_status=="0") {
    $clmer_cardapio->erro(true,false);
  } else{
    $clmer_cardapio->erro(true,true);
  }
}
if ($db_opcao==33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
function js_msg_nao_altera(mensagem,id) {
	
  var expReg = /\\n\\n/gm;
  mensagem = mensagem.replace(expReg,'<br>');
  var camada = document.createElement("DIV");
  camada.setAttribute("id",id);
  camada.setAttribute("align","center");
  camada.style.position   = "absolute";
  camada.style.left       = ((screen.availWidth-400)/2)+'px';
  camada.style.top        = ((screen.availHeight-500)/2)+'px';
  camada.style.zIndex     = "1000";
  camada.style.visibility = 'visible';
  camada.style.width      = "400px";
  camada.style.height     = "200px";
  camada.style.fontFamily = 'Verdana, Arial, Helvetica, sans-serif';
  camada.style.fontSize   = '15px';
  camada.style.border     = '1px solid';
  camada.innerHTML = ' <table border="0" width= "100%" height="100%" style="background-color: #FFFFCC; border-collapse: collapse;"> '
                     +'    <tr> '
                     +'      <td align= "center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; font-weight: bold;"> '
                     +'        '+mensagem+''
                     +'        <br><br><input type="button" onclick="js_removeMsg()" value="Fechar">'
                     +'      </td> '
                     +'    </tr> '
                     +' </table> ';
  document.body.appendChild(camada);
  
}

function js_removeMsg(idObj) {
	
  obj = document.getElementById("MsgBox");
  document.body.removeChild(obj);
 
}

<?if ($naopode==true) {?>
    js_msg_nao_altera("<?=$msg_error?>","MsgBox");
<?}?>
</script>