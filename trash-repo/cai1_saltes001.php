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
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("classes/db_saltes_classe.php");
include("classes/db_saltescontrapartida_classe.php");
include("classes/db_saltesextra_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clsaltes = new cl_saltes;
$db_opcao = 1;
$db_botao = true;

if ( isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"] == "Incluir" ) {
	
  $erro=false;
  db_inicio_transacao();
  
  $dtImplantacao = explode("/",$k13_dtimplantacao,3);
  $dtDia         = $dtImplantacao[0];
  $dtMes         = $dtImplantacao[1];
  $dtAno         = $dtImplantacao[2];
  $dtImplantacao = date('Y-m-d', mktime(0,0,0, $dtMes, $dtDia-1, $dtAno));
  
  $clsaltes->k13_dtimplantacao = $dtImplantacao;
  $clsaltes->incluir($k13_reduz);
  
  if ( $clsaltes->erro_status == 0 ) {	
    $erro = true;
  }
  
  if ( !$erro && $k103_contrapartida != '' ) {
    
     $oDaosaltesContra                     = new cl_saltescontrapartida;
     $oDaosaltesContra->k103_contrapartida = $k103_contrapartida;
     $oDaosaltesContra->k103_saltes        = $k13_reduz;
     $oDaosaltesContra->incluir(null);
     
     if ( $oDaosaltesContra->erro_status == 0 ){

       $clsaltes->erro_status = 0;
       $clsaltes->erro_msg    = $oDaosaltesContra->erro_msg;
       $erro                  = true;
       
     }
  }
  if ( !$erro && $k109_saltesextra != '' ) {
    
     $oDaosaltesExtra                  = new cl_saltesextra;
     $oDaosaltesExtra->k109_contaextra = $k109_saltesextra;
     $oDaosaltesExtra->k109_saltes     = $k13_reduz;
     $oDaosaltesExtra->incluir(null);
     
     if ($oDaosaltesExtra->erro_status == 0){

       $clsaltes->erro_status = 0;
       $clsaltes->erro_msg    = $oDaosaltesExtra->erro_msg;
       $erro                  = true;
       
     }
  }
  
  db_fim_transacao($erro);
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
 <center>
	<?
	  include("forms/db_frmsaltes.php");
	?>
 </center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Incluir"){
	
  if ( $clsaltes->erro_status == "0" ) {
  	
      $clsaltes->erro(true,false);
      $db_botao=true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      
      if($clsaltes->erro_campo!=""){
      	
           echo "<script> document.form1.".$clsaltes->erro_campo.".style.backgroundColor='#99A9AE';</script>";
           echo "<script> document.form1.".$clsaltes->erro_campo.".focus();</script>";
      };
  } else {
  	
      $clsaltes->erro(true,true);
  };
};
?>