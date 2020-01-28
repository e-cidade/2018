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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_versao_classe.php");
include("classes/db_db_versaocpd_classe.php");
include("classes/db_db_versaousu_classe.php");
$cldb_versao = new cl_db_versao;
$cldb_versaocpd = new cl_db_versaocpd;
$cldb_versaousu = new cl_db_versaousu;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  if(isset($versao) && $versao == 's'){
     $result = $cldb_versao->sql_record($cldb_versao->sql_query_file('','max(db30_codversao)+1 as xversao'));
     db_fieldsmemory($result,0);
     if($xversao == '')
        $xversao = 1;
     $db30_codversao = $xversao ;
     $db30_codrelease = 1;
  }elseif(isset($versao) && $versao == 'n'){
  //  echo $cldb_versao->sql_query_file('',"max(db30_codversao||'P'||db30_codrelease+1)");
     $result = $cldb_versao->sql_record($cldb_versao->sql_query_file('',"max(db30_codversao::text||'P'::text||(db30_codrelease+1)::text)"));
     db_fieldsmemory($result,0);
     if(trim($max) == ''){
      echo "<script>alert('Não existe versão para esta release! Cadastre primeiro uma versao')</script>";
      exit;
     }
     $matriz= split("P",$max);
//     $db30_codversao = $matriz[0]; 
     $db30_codrelease = $matriz[1];
  }
  $cldb_versao->db30_codversao = $db30_codversao;
  $cldb_versao->db30_codrelease = $db30_codrelease;
  $cldb_versao->incluir($db30_codver);
  if($cldb_versao->erro_status==0){
  echo 'versao : '.$db30_codversao."<br>";
  echo 'release  '.$db30_codrelease;
    $sqlerro=true;
  } 
  $erro_msg = $cldb_versao->erro_msg; 
  db_fim_transacao($sqlerro);
   $db30_codver= $cldb_versao->db30_codver;
   $db_opcao = 1;
   $db_botao = true;
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdb_versao.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($cldb_versao->erro_campo!=""){
      echo "<script> document.form1.".$cldb_versao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_versao->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("con1_db_versao005.php?liberaaba=true&chavepesquisa=$db30_codver");
  }
}
?>