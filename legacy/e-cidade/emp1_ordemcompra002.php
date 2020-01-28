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

//echo ($HTTP_SERVER_VARS['QUERY_STRING']);exit;
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("libs/db_libdocumento.php");
include("dbforms/db_funcoes.php");
include("classes/db_matparam_classe.php");
include("classes/db_pcparam_classe.php");
include("classes/db_empempitem_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_matordem_classe.php");
include("classes/db_matordemitem_classe.php");
include("classes/db_empempenho_classe.php");
include("classes/db_matordemmail_classe.php");

$clmatparam = new cl_matparam;
$clpcparam = new cl_pcparam;
$clmatordem = new cl_matordem;
$clmatordemitem = new cl_matordemitem;
$clempempenho = new cl_empempenho;
$clempempitem = new cl_empempitem;
$clcgm = new cl_cgm;
$clmatordemmail = new cl_matordemmail;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

if (isset($valores)&&isset($incluir)){
db_inicio_transacao();
$sqlerro = false;
$valor_total="";
$sqlerro = false;
$dados=split("quant_","$valores");
$valordoitem=split("valor_","$val");


for ($i=1;$i<sizeof($dados);$i++){
   if ($sqlerro==false){
      $numero=split("_",$dados[$i]);
      $numemp=$numero[0];
      $sequen=$numero[1];
      $quanti=$numero[3];
      $vlsoitem=split("_",$valordoitem[$i]);
      $vl_soma_item=$vlsoitem[3];
      $vl_soma_item = str_replace(",",".","$vl_soma_item"); 
//      $valor_total+=$vl_soma_item;
   }
   $result_valuni=$clempempitem->sql_record($clempempitem->sql_query_file($numemp,$sequen));
   if ($clempempitem->numrows!=0){
     db_fieldsmemory($result_valuni,0);
     $valuni=$e62_vltot/$e62_quant;
     $valitem=$valuni*$quanti;
     $valor_total+=$valitem;
   }
}
$m51_data="$m51_data_ano-$m51_data_mes-$m51_data_dia";

$clmatordem->m51_data = $m51_data; 
$clmatordem->m51_depto = @$coddepto;
$clmatordem->m51_numcgm = $e60_numcgm;
$clmatordem->m51_obs = $m51_obs;
$clmatordem->m51_valortotal = $valor_total;
$clmatordem->incluir(null);
if($clmatordem->erro_status==0){
  $sqlerro=true;
}
$erro_msg = $clmatordem->erro_msg;
$codigo=$clmatordem->m51_codordem;
if ($sqlerro==false){
	if (isset($manda_mail)&&$manda_mail!=""){
		$clmatordemmail->m55_codordem = $codigo;
		$clmatordemmail->m55_email = $z01_email;
		$clmatordemmail->incluir(null);
		if($clmatordemmail->erro_status==0){
			$sqlerro=true;
			$erro_msg = $clmatordemmail->erro_msg;
		}
	}
}

for ($i=1;$i<sizeof($dados);$i++){
  if ($sqlerro==false){
    $numero=split("_",$dados[$i]);
    $numemp=$numero[0];
    $sequen=$numero[1];
    $quanti=$numero[3];
/*    $vlsoitem=split("_",$valordoitem[$i]);
    $vl_soma_item=$vlsoitem[3];
    $vl_soma_item = str_replace(",",".","$vl_soma_item"); */
   $result_valuni=$clempempitem->sql_record($clempempitem->sql_query_file($numemp,$sequen));
   if ($clempempitem->numrows!=0){
     db_fieldsmemory($result_valuni,0);
     $valuni=$e62_vltot/$e62_quant;
     $valitem=$valuni*$quanti;
   }
       
    $clmatordemitem->m52_codordem = $codigo;
    $clmatordemitem->m52_numemp = $numemp;
    $clmatordemitem->m52_sequen = $sequen;
    $clmatordemitem->m52_quant = $quanti; 
    $clmatordemitem->m52_valor = $valitem;
    $clmatordemitem->m52_vlruni = $e62_vlrun;
    $clmatordemitem->incluir(null);
    if ($clmatordemitem->erro_status==0){
      $sqlerro=true;
      break;
    }
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <?
    include("forms/db_frmmatordemcgm.php");
    ?>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<?
if (isset($incluir)){
  if($sqlerro == true){ 
    db_msgbox($erro_msg);
    if($clmatordem->erro_campo!=""){
      echo "<script> document.form1.".$clmatordem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatordem->erro_campo.".focus();</script>";
    } 
   }else{ 
   db_msgbox($erro_msg);
     	echo "
         <script>
           if(confirm('Deseja imprimir a ordem de compra?')){
             jan = window.open('emp2_ordemcompra002.php?cods=$codigo','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
             jan.moveTo(0,0);
	      } 
         </script>
";
  if (isset($manda_mail)&&$manda_mail!=""){
  	$headers  = "Content-Type:text/html;";  	  	
		$objteste = new libdocumento(1750);
		$corpo    = $objteste->emiteDocHTML();
  	$mail     = mail($z01_email,"Ordem de Compra Nº $codigo",$corpo,$headers);
  	if ($mail){
  		db_msgbox("E-mail enviado com sucesso!!");  		
  	}else{
  		db_msgbox("Erro ao enviar e-mail!!E-mail não foi enviado!!");
  	}
		
  }
  echo "<script>
	   location.href='emp1_ordemcompra001.php';
         </script>
   "; 
  }
}
?>
</body>
</html>