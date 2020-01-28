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
include("classes/db_db_depart_classe.php");
include("classes/db_db_departorg_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcorgao_classe.php");
include("classes/db_orcunidade_classe.php");
include("classes/db_db_config_classe.php");
$cldb_depart    = new cl_db_depart;
$cldb_departorg = new cl_db_departorg;
$clorcorgao     = new cl_orcorgao;
$clorcunidade   = new cl_orcunidade;
$cldb_config    = new cl_db_config;

db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  $anousu = db_getsession("DB_anousu");
  if (isset($datalimite)){
    $datalimite = $limite_ano.'-'.$limite_mes.'-'.$limite_dia;
    if($datalimite == '--'){
       $datalimite = '';
     }
  }else{
     $datalimite = '';   
  }
  $sqlerro=false;
  db_inicio_transacao();
  $cldb_depart->descrdepto       = $descrdepto;
  $cldb_depart->nomeresponsavel  = $nomeresponsavel;
  $cldb_depart->emailresponsavel = $emailresponsavel;
  $cldb_depart->limite           = $datalimite;
  $cldb_depart->emaildepto       = $emaildepto;
  $cldb_depart->fonedepto        = $fonedepto;
  $cldb_depart->ramaldepto       = $ramaldepto;
  $cldb_depart->faxdepto         = $faxdepto;
  $cldb_depart->instit           = $instit;
  $cldb_depart->incluir($coddepto);
  if($cldb_depart->erro_status==0){
    $sqlerro=true;
  }else{
     $coddepto = $cldb_depart->coddepto;
  }
  if(!$sqlerro){
    $cldb_departorg->db01_coddepto= $coddepto;
    $cldb_departorg->db01_anousu  = $anousu; 
    $cldb_departorg->db01_orgao   = $o40_orgao;
    $cldb_departorg->db01_unidade = $o41_unidade;
    $cldb_departorg->incluir($coddepto,$anousu);
    if($cldb_departorg->erro_status==0){
      $sqlerro=false;
    }
  }
  db_fim_transacao($sqlerro);
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
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($cldb_depart->erro_status=="0"){
    $cldb_depart->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cldb_depart->erro_campo!=""){
      echo "<script> document.form1.".$cldb_depart->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_depart->erro_campo.".focus();</script>";
    };
  }else{
      echo "<script> 
                  parent.iframe_g2.location.href='con1_db_departender001.php?coddepto=".$cldb_depart->coddepto."'
                  parent.iframe_g1.location.href='con1_db_depart002.php?chavepesquisa=".$cldb_depart->coddepto."&abas=2';\n
		     </script>";
  };
};
?>