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
include("classes/db_mer_tipocardapio_classe.php");
include("classes/db_mer_cardapio_classe.php");
include("classes/db_mer_cardapiodata_classe.php");
include("classes/db_mer_cardapiodia_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmer_tipocardapio = new cl_mer_tipocardapio;
$clmer_cardapio = new cl_mer_cardapio;
$clmer_cardapiodata = new cl_mer_cardapiodata;
$clmer_cardapiodia = new cl_mer_cardapiodia;
$db_opcao = 22;
$db_botao = false;
$naopode  = false;
if (isset($alterar)) {
	
  $db_opcao = 2;
  db_inicio_transacao();
  $clmer_tipocardapio->alterar($me27_i_codigo);
  db_fim_transacao();
  
} elseif (isset($chavepesquisa)) {
	
  $db_opcao = 2;
  $result   = $clmer_tipocardapio->sql_record($clmer_tipocardapio->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  $db_botao = true;
  $dataatual = date("Y-m-d",db_getsession("DB_datausu"));
  $horaatual = date("H:i");
  $result_verif1 = $clmer_cardapiodata->sql_record($clmer_cardapiodata->sql_query("",
                                                                                  "*",
                                                                                  "",
                                                                                  "me01_i_tipocardapio = $chavepesquisa"
                                                                                 ));
                                                                                 
  $sWhere         = " me01_i_tipocardapio = $chavepesquisa AND (me12_d_data < '$dataatual' OR (me12_d_data = '$dataatual' "; 
  $sWhere        .= " AND me03_c_fim < '$horaatual')) AND not exists ";
  $sWhere        .= "                                       (select * from mer_cardapiodata inner join mer_cardapiodiaescola on me37_i_codigo = me13_i_cardapiodiaescola";
  $sWhere        .= "                                        where me37_i_codigo = me13_i_cardapiodia)";
  $result_verif2  = $clmer_cardapiodia->sql_record($clmer_cardapiodia->sql_query("",
                                                                                 "*",
                                                                                 "",
                                                                                 $sWhere
                                                                                ));
  
  $msg_error = "$me27_i_codigo - $me27_c_nome - Versão: $me27_f_versao<br><br>";
  if ($clmer_cardapiodata->numrows>0) {
    
    $msg_error .= "<font color=red>-> Cardápio já contem refeiçoes com registro de baixa no estoque;</font><br>";
    $db_botao   = false;
    $naopode    = true;
    
  }
  if ($clmer_cardapiodia->numrows>0) {
    
    $msg_error .= "<font color=red>-> Cardápio contém refeições já consumidas em uma data inferior a data corrente;</font><br><br>";
    $db_botao   = false;
    $naopode    = true;
    
  }
  $msg_error .= " Alteração não permitida.<br><br> ";
  $msg_error .= " Para poder modificar alguma informação deste cardápio,<br>clique no botão Nova Versão.<br>";
  $msg_error .= " Este procedimento irá replicar este cardápio,<br> ";
  $msg_error .= " criando uma nova versão com a mesma estrutura da versão $me27_f_versao.";
  ?>
  <script>
    parent.document.formaba.a2.disabled    = false;
    top.corpo.iframe_a2.location.href      = 'mer1_mer_cardapioescola001.php?me32_i_tipocardapio=<?=$chavepesquisa?>'+
                                             '&me27_c_nome=<?=$me27_c_nome?>';
    parent.document.formaba.a3.disabled    = false;
    top.corpo.iframe_a3.location.href      = 'mer1_mer_tpcardapioturma001.php?me32_i_tipocardapio=<?=$chavepesquisa?>'+
                                             '&me27_c_nome=<?=$me27_c_nome?>';  
  </script>
  <?

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
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <br>
      <center>
      <fieldset style="width:95%"><legend><b>Alteração de Cardápio</b></legend>
        <?include("forms/db_frmmer_tipocardapio.php");?>
      </fieldset>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($alterar)) {
	
  if ($clmer_tipocardapio->erro_status=="0") {
 	
    $clmer_tipocardapio->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
   
    if ($clmer_tipocardapio->erro_campo!="") {
  	
      echo "<script> document.form1.".$clmer_tipocardapio->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmer_tipocardapio->erro_campo.".focus();</script>";
    
    }
    
  } else {
 	
    $clmer_tipocardapio->erro(true,false);
    ?>
    <script>
      parent.document.formaba.a2.disabled    = false;
      top.corpo.iframe_a2.location.href      = 'mer1_mer_cardapioescola001.php?me32_i_tipocardapio=<?=$chavepesquisa?>'+
                                               '&me27_c_nome=<?=$me27_c_nome?>';
      parent.document.formaba.a3.disabled    = false;
      top.corpo.iframe_a3.location.href      = 'mer1_mer_tpcardapioturma001.php?me32_i_tipocardapio=<?=$chavepesquisa?>'+
                                               '&me27_c_nome=<?=$me27_c_nome?>';  
      parent.mo_camada('a2');   
    </script>
    <?
    db_redireciona("mer1_mer_tipocardapio002.php?chavepesquisa=$me27_i_codigo");
    
  }
  
}

if ($db_opcao==22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","me27_c_nome",true,1,"me27_c_nome",true);
function js_msg_nao_altera(mensagem,id) {
	    
  var expReg              = /\\n\\n/gm;
  mensagem                = mensagem.replace(expReg,'<br>');
  var camada              = document.createElement("DIV");
  camada.setAttribute("id",id);
  camada.setAttribute("align","center");
  camada.style.position   = "absolute"; 
  camada.style.left       = ((screen.availWidth-400)/2)+'px';
  camada.style.top        = ((screen.availHeight-550)/2)+'px';
  camada.style.zIndex     = "1000";
  camada.style.visibility = 'visible';
  camada.style.width      = "450px";
  camada.style.height     = "250px";
  camada.style.fontFamily = 'Verdana, Arial, Helvetica, sans-serif';
  camada.style.fontSize   = '15px';
  camada.style.border     = '1px solid'; 
  camada.innerHTML = ' <table border="0" width= "100%" height="100%" style="background-color: #FFFFCC; border-collapse: collapse;"> '
                     +'    <tr> '
                     +'      <td align= "center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #000000;font-weight: bold;"> '
                     +'        <br>'+mensagem+''
                     +'        <br><br><input type="button" onclick="js_removeMsg()" value="Fechar"><br><br>'
                     +'      </td> '
                     +'    </tr> '
                     +' </table> ';
  document.body.appendChild(camada);
}

function js_removeMsg(idObj) {
    
  obj = document.getElementById("MsgBox");
  document.body.removeChild(obj);
  
}
<?if ($naopode == true) {?>
 js_msg_nao_altera("<?=$msg_error?>","MsgBox");
<?}?>
</script>