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
include("dbforms/db_funcoes.php");
include("classes/db_caitransf_classe.php");
include("classes/db_caitransfdest_classe.php");
db_postmemory($HTTP_POST_VARS);

$clcaitransf     = new cl_caitransf;
$clcaitransfdest = new cl_caitransfdest;

$db_opcao = 1;
$db_botao = true;

if(isset($incluir)){
  $sqlerro = false;	
  db_inicio_transacao();
  $clcaitransf->k91_tipo = $k91_tipo;
  $clcaitransf->k91_anousu = db_getsession("DB_anousu");
  $clcaitransf->k91_instit = db_getsession("DB_instit");
  $clcaitransf->incluir(null);
  if($clcaitransf->erro_status==0){
	  $erro_msg = $clcaitransf->erro_msg; 	
      $sqlerro  = true;
  }
  if($sqlerro==false) {
  	 $clcaitransfdest->k92_transf = $clcaitransf->k91_transf;
	 $clcaitransfdest->incluir($clcaitransf->k91_transf);
	 $k91_transf = $clcaitransf->k91_transf;
	 if($clcaitransfdest->erro_status==0){
		  $erro_msg = $clcaitransfdest->erro_msg; 	
	   	  $sqlerro  = true;
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmcaitransf.php");
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
    $clcaitransf->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcaitransf->erro_campo!=""){
      echo "<script> document.form1.".$clcaitransf->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcaitransf->erro_campo.".focus();</script>";
    };
  }else{
    // $clcaitransf->erro(true,true);
    //  db_redireciona("cai1_caitransf004.php");
    echo " <script>
              parent.location.href='cai1_caitransf002.php?chavepesquisa=$k91_transf';\n
           </script>
         ";
    exit;

  };
};
?>