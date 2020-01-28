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
include("libs/db_libdicionario.php");
include("libs/db_usuariosonline.php");
include("classes/db_parcustos_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");

$oPost        = db_utils::postmemory($_POST);
$clparcustos  = new cl_parcustos;
$db_botao     = false;
$lSqlErro     = false;

if(isset($oPost->incluir)){
	
  db_inicio_transacao();
    
	$clparcustos->cc09_anousu 			  = db_getsession("DB_anousu");
	$clparcustos->cc09_instit       	  = $oPost->cc09_instit;
    $clparcustos->cc09_mascaracustoplano  = $oPost->cc09_mascaracustoplano;
    $clparcustos->cc09_tipocontrole  	  = $oPost->cc09_tipocontrole;
  	$clparcustos->incluir($cc09_anousu);

  	if ($clparcustos->erro_status == 0) {
  	  $lSqlErro = true;
  	  $sMsgErro = $clparcustos->erro_msg;
  	}
  
  db_fim_transacao($lSqlErro);
  
  $db_opcao = 2;
  
} else if(isset($oPost->alterar)){
	
  db_inicio_transacao();
   
	$clparcustos->cc09_anousu 			  = db_getsession("DB_anousu");
	$clparcustos->cc09_instit       	  = $oPost->cc09_instit;
    $clparcustos->cc09_mascaracustoplano  = $oPost->cc09_mascaracustoplano;
    $clparcustos->cc09_tipocontrole  	  = $oPost->cc09_tipocontrole;
    $clparcustos->alterar($cc09_anousu);
  	
    if ($clparcustos->erro_status == 0) {
  	  $lSqlErro = true;
  	  $sMsgErro = $clparcustos->erro_msg;
  	}
    
  db_fim_transacao($lSqlErro);
  
  $db_opcao = 2;
  
} else {
       
   $sWhere  = "cc09_anousu = ".db_getsession("DB_anousu");
   $sWhere .= " and "; 
   $sWhere .= "cc09_instit = ".db_getsession("DB_instit");
   
   $rsConsultaParam = $clparcustos->sql_record($clparcustos->sql_query(null,"*",null,$sWhere));

   if ( $clparcustos->numrows > 0 ) {
	 
   	 $db_opcao = 2;
     $db_botao = true;   	 
     $oParam   = db_utils::fieldsMemory($rsConsultaParam,0);
   	
	 $cc09_anousu               = db_getsession("DB_anousu");
     $cc09_instit            	= db_getsession("DB_instit");
	 $cc09_mascaracustoplano 	= $oParam->cc09_mascaracustoplano; 
	 $cc09_tipocontrole    	    = $oParam->cc09_tipocontrole; 
   	  	    	 
   } else {
     $cc09_instit            = db_getsession('DB_instit');  	 
     $db_opcao    = 1;
     $db_botao    = true;
     
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" 
      onLoad="a=1,js_pesquisacc09_instit(false),js_pesquisacc09_mascaracustoplano(false)" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<center>
  <table style="padding-top:15px;">
    <tr> 
      <td> 
	    <?
	 	  include("forms/db_frmparcustos.php");
		?>
	  </td>
    </tr>
  </table>
</center>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($oPost->alterar) || isset($oPost->incluir)){
  
  if($clparcustos->erro_status=="0"){
    $clparcustos->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clparcustos->erro_campo!=""){
      echo "<script> document.form1.".$clparcustos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clparcustos->erro_campo.".focus();</script>";
    }
  }else{
    $clparcustos->erro(true,true);
  }
}

?>