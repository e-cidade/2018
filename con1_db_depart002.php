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
include("classes/db_db_depart_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcorgao_classe.php");
include("classes/db_orcunidade_classe.php");
include("classes/db_db_departorg_classe.php");
include("classes/db_db_config_classe.php");
$clorcorgao     = new cl_orcorgao;
$clorcunidade   = new cl_orcunidade;
$cldb_depart    = new cl_db_depart;
$cldb_departorg = new cl_db_departorg;
$cldb_config    = new cl_db_config;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

if(isset($troca)){
  $db_opcao = 2;
  $db_botao = true;
}else { 
  $db_opcao = 22;
  $db_botao = false;
}  
$anousu = db_getsession("DB_anousu");
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  $sqlerro=false;
  db_inicio_transacao();
  $db_opcao = 2;
  $cldb_depart->alterar($coddepto);
  if($cldb_depart->erro_status==0){
    $sqlerro=true;
  }
  
  if (isset($o40_orgao) && $o40_orgao != "") {
    $result = $cldb_departorg->sql_record($cldb_departorg->sql_query_file($coddepto,$anousu,'db01_orgao , db01_unidade')); 
    if ($cldb_departorg->numrows>0) {
      if ($sqlerro==false) {
        $cldb_departorg->db01_coddepto= $coddepto;
        $cldb_departorg->db01_anousu  = $anousu; 
        $cldb_departorg->db01_orgao   = $o40_orgao;
        $cldb_departorg->db01_unidade = $o41_unidade;
        $cldb_departorg->alterar($coddepto,$anousu);
        if ($cldb_departorg->erro_status==0){
	        $sqlerro=true;
        }
      }   
    } else {
      if ($sqlerro==false) {
        $cldb_departorg->db01_coddepto= $coddepto;
        $cldb_departorg->db01_anousu  = $anousu; 
        $cldb_departorg->db01_orgao   = $o40_orgao;
        $cldb_departorg->db01_unidade = $o41_unidade;
        $cldb_departorg->incluir($coddepto,$anousu);
        if ($cldb_departorg->erro_status==0) {
        	$sqlerro=true;
        }
      }
    }
  }
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $cldb_depart->sql_record($cldb_depart->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);

   $result = $cldb_departorg->sql_record($cldb_departorg->sql_query_file($chavepesquisa,$anousu,'db01_orgao as o40_orgao,db01_anousu as anousu, db01_unidade as o41_unidade')); 
   if($cldb_departorg->numrows>0){
     db_fieldsmemory($result,0);
   }  
   $db_botao = true;
}
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdb_depart.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  if($cldb_depart->erro_status=="0"){
    $cldb_depart->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cldb_depart->erro_campo!=""){
      echo "<script> document.form1.".$cldb_depart->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_depart->erro_campo.".focus();</script>";
    };
  }else{
    $cldb_depart->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>