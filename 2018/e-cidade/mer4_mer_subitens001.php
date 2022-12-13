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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_mer_subitem_classe.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
include("libs/db_jsplibwebseller.php");
db_postmemory($HTTP_POST_VARS);
$clmer_subitem = new cl_mer_subitem;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$escola= db_getsession("DB_coddepto");
if (!isset($db_opcao)) {
 $db_opcao = 1;
}
$db_botao = true;
if (isset($opcao)) {
	
  $campos = " mer_subitem.*, ";
  $campos.= " mer_cardapio.me01_c_nome, ";
  $campos.= " mer_alimento.me35_c_nomealimento, ";
  $campos.= " mer_alimento2.me35_c_nomealimento as me35_c_nomealimento2 ";
  $sql1=$clmer_subitem->sql_query("",$campos,"","me29_i_codigo = $me29_i_codigo");
  $result1 = $clmer_subitem->sql_record($sql1);
  if ($clmer_subitem->numrows>0) {
    db_fieldsmemory($result1,0);
  }
  if ( $opcao == "alterar") {
  	
    $db_opcao = 2;
    $db_botao1 = true;
    
  } else {
  	
    if ( $opcao=="excluir" || isset($db_opcao) && $db_opcao==3) {
    	
      $db_opcao = 3;
      $db_botao1 = true;
      
    } else {
      if (isset($alterar)) {
      	
        $db_opcao = 2;
        $db_botao1 = true;
        
      }
    }
    
  }
  
}
if (isset($incluir)) {
	
  db_inicio_transacao();
 if ($me29_d_fim=="") {
 	
   $me29_d_fim                = $me29_d_inicio;
   $clmer_subitem->me29_d_fim = $me29_d_inicio;
   
 }
 $hoje   = date("d/m/Y",db_getsession("DB_datausu"));
 $hoje   = mktime(0,0,0,substr($hoje,3,2),substr($hoje,0,2),substr($hoje,6,4));
 $dat    = explode("/",$me29_d_inicio);
 $inicio = mktime(0,0,0,$dat[1],$dat[0],$dat[2]);
 $dat    = explode("/",$me29_d_fim);
 $fim    = mktime(0,0,0,$dat[1],$dat[0],$dat[2]);
 if ($fim>=$inicio) {
 	
  if ($inicio>=$hoje) {
    $clmer_subitem->incluir($me29_i_codigo);
  } else {
    db_msgbox("Período Invalido!");
  }
 } else {
   db_msgbox("Data inicial maior que data final!");
 }
 db_fim_transacao();
}
if (isset($alterar)) {
	
  db_inicio_transacao();
  $db_opcao = 2; 
  $hoje     = date("d/m/Y",db_getsession("DB_datausu"));
  $oje      = mktime(0,0,0,substr($hoje,3,2),substr($hoje,0,2),substr($hoje,6,4));
  $at       = explode("/",$me29_d_inicio);
  $inicio   = mktime(0,0,0,$dat[1],$dat[0],$dat[2]);
  $dat      = explode("/",$me29_d_fim);
  $fim      = mktime(0,0,0,$dat[1],$dat[0],$dat[2]);
  if ($inicio<$hoje) {
  	
    if ($fim>$hoje) {    	    	
      $clmer_subitem->alterar($me29_i_codigo);  //Troca em progresso
    } else {
   	
      db_msgbox("Será possível fazer a alteração da substituição somente na data inferior a data final");
      echo "<script>location.href='mer4_mer_subitens001.php'</script>";
     
   }
  } else {
    $clmer_subitem->alterar($me29_i_codigo); //troca ainda não iniciada
  }
  db_fim_transacao(); 
}
if (isset($excluir)) {
	
  db_inicio_transacao();
  $db_opcao  = 3;
  $db_opcao1 = 3;
  $hoje      = date("d/m/Y",db_getsession("DB_datausu"));
  $hoje      = mktime(0,0,0,substr($hoje,3,2),substr($hoje,0,2),substr($hoje,6,4));
  $dat       = explode("/",$me29_d_inicio);
  $inicio    = mktime(0,0,0,$dat[1],$dat[0],$dat[2]);
  $dat       = explode("/",$me29_d_fim);
  $fim       = mktime(0,0,0,$dat[1],$dat[0],$dat[2]);
  if ($inicio<$hoje) {
  	
   if ($fim>$hoje) {
   	
     $clmer_subitem->me29_d_fim=date("Y-m-d",db_getsession("DB_datausu")); //Troca em progresso
     $clmer_subitem->alterar($me29_i_codigo);
     
   } else {
   	
     db_msgbox("Será possível fazer a exclusão da substituição somente na data inferior a data final");
     echo "<script>location.href='mer4_mer_subitens001.php'</script>";
     
  }
 } else {
   $clmer_subitem->excluir($me29_i_codigo); //troca ainda não iniciada
 }
 db_fim_transacao();
}

if(isset($me29_i_codigo)){

   $db_opcao = 2;
   $sCampos  = "mer_subitem.*,mer_alimento.me35_c_nomealimento as me35_c_nomealimento2,alimento.me35_c_nomealimento";
   $sSql     = $clmer_subitem->sql_query($me29_i_codigo,$sCampos);
   $result   = $clmer_subitem->sql_record($sSql);
   if ($clmer_subitem->numrows>0) {

      db_fieldsmemory($result,0);
      $db_botao = true;

   } else {
   	
      $db_opcao = 1;

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
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript">
function js_carrega() {
  if(document.form1.me29_i_alimentonovo.value != ''){	
    new Ajax.Request('mer4_mer_subitem_ajax004.php?item='+document.form1.me29_i_alimentonovo.value,
	  	            {
                     method : 'get',
                     onComplete : function(transport){
                      document.form1.unidade.value = transport.responseText;
                     }
                    }
                  );
  }
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" >
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">   
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Substituição de alimentos</b></legend>
    <?include("forms/db_frmmer_subitem.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","me29_i_refeicao",true,1,"me29_i_refeicao",true);
</script>
<?
if (isset($opcao)) {
  echo"<script>js_carrega(); js_validadata(1); </script>";
}
if (isset($incluir)) {
  if ($clmer_subitem->erro_status=="0") {
  	
    $clmer_subitem->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($clmer_subitem->erro_campo!="") {
    	
      echo "<script> document.form1.".$clmer_subitem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmer_subitem->erro_campo.".focus();</script>";
      
    }
  } else {
    $clmer_subitem->erro(true,true);
 }
}
if (isset($alterar)) {
	
 if ($clmer_subitem->erro_status=="0") {
 	
   $clmer_subitem->erro(true,false);
   $db_botao=true;
   echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
   
   if ($clmer_subitem->erro_campo!="") {
   	
     echo "<script> document.form1.".$clmer_subitem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
     echo "<script> document.form1.".$clmer_subitem->erro_campo.".focus();</script>";
     
   }
 } else { 	
   $clmer_subitem->erro(true,true);  
 } 
}
if (isset($excluir)) {
	
  if ($clmer_subitem->erro_status=="0") {
    $clmer_subitem->erro(true,false);
  } else {
    $clmer_subitem->erro(true,true);
  }
 
}
?>