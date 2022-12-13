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
include("classes/db_cgs_classe.php");
include("classes/db_cgs_und_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_stdlibwebseller.php");
db_postmemory($HTTP_POST_VARS);
$clcgs = new cl_cgs;
$clcgs_und = new cl_cgs_und;
$db_opcao = 1;
$db_opcao1 = 1;
$db_botao = true;

$z01_d_cadast_dia = date("d");
$z01_d_cadast_mes = date("m");
$z01_d_cadast_ano = date("Y");
$z01_d_ultalt_dia = date("d");
$z01_d_ultalt_mes = date("m");
$z01_d_ultalt_ano = date("Y");


if( !isset($z01_v_munic ) ){
  $query_dbconfig =  "select munic as z01_v_munic, uf as z01_v_uf  from db_config where prefeitura is true";
  $result_dbconfig = pg_query($query_dbconfig);
  db_fieldsmemory($result_dbconfig,0);
}


if(isset($incluir) || isset($alterar) ){
  db_inicio_transacao();
  $clcgs_und->z01_i_login = DB_getsession("DB_id_usuario");

  $clcgs->incluir("");
  $z01_i_cgsund = $clcgs->z01_i_numcgs;

  $clcgs_und->incluir($z01_i_cgsund);
  db_fim_transacao();
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%">
    <?include("forms/db_frmsaudoc.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
 if($clcgs->erro_status=="0"){
  $clcgs->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clcgs->erro_campo!=""){
   echo "<script> document.form1.".$clcgs->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clcgs->erro_campo.".focus();</script>";
  };
 }else {
      if($clcgs_und->erro_status=="0"){
       $clcgs_und->erro(true,false);
       $db_botao=true;
       echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
       if($clcgs_und->erro_campo!=""){
        echo "<script> document.form1.".$clcgs_und->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clcgs_und->erro_campo.".focus();</script>";
       };
      }else{
       $clcgs_und->erro(true,false);
       ?>
           <script>
                 parent.document.formaba.a1.disabled = true;
                 parent.document.formaba.a1.style.color = "black";
                 top.corpo.iframe_a1.location.href="sau1_cgs_und001.php";
                 parent.mo_camada('a1');
                </script>
       <?
      }
  }
}
?>