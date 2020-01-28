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
include("classes/db_fiscal_classe.php");
include("classes/db_fiscaltipo_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clfiscal = new cl_fiscal;
$clfiscaltipo = new cl_fiscaltipo;
$db_opcao = 1;
$db_botao = false;
if(isset($salvar) && $salvar == "Salvar"){
  db_inicio_transacao();
  $db_opcao = 2;
  $clfiscal->y30_codnoti = $y30_codnoti;
  $clfiscal->alterar($y30_codnoti);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
<?
//MODULO: fiscal
$clrotulo = new rotulocampo;
$clrotulo->label("y30_dtvenc");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty30_dtvenc?>">
       <?=@$Ly30_dtvenc?>
    </td>
    <td>
<?
/*
$result = $clfiscaltipo->sql_record($clfiscaltipo->sql_query("","","y29_dias","y31_codnoti limit 1"," fiscaltipo.y31_codnoti = $y30_codnoti"));
if($clfiscaltipo->numrows > 0){
  db_fieldsmemory($result,0);
}
*/
$result = $clfiscal->sql_record($clfiscal->sql_query("","*",""," y30_dtvenc is not null and y30_codnoti = $y30_codnoti"));
if($clfiscal->numrows > 0){
  db_fieldsmemory($result,0);
  $dia = substr($y30_dtvenc,8,2);
  $mes = substr($y30_dtvenc,5,2);
  $ano = substr($y30_dtvenc,0,4);
  $y30_dtvenc_dia = $dia;
  $y30_dtvenc_mes = $mes;
  $y30_dtvenc_ano = $ano;
}

db_inputdata('y30_dtvenc',@$y30_dtvenc_dia,@$y30_dtvenc_mes,@$y30_dtvenc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center"> 
      <input name="salvar" type="submit" value="Salvar">
    </td>
  </tr>  
</table>
</center>
</form>
</body>
</html>
<?
if(isset($salvar) && $salvar == "Salvar"){
  if($clfiscal->erro_status=="0"){
    $clfiscal->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.salvar.disabled=false;</script>  ";
    if($clfiscal->erro_campo!=""){
      echo "<script> document.form1.".$clfiscal->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clfiscal->erro_campo.".focus();</script>";
    };
  }else{
    $clfiscal->erro(true,false);
    echo "
         <script>
           parent.iframe_venc.location.href='fis1_vencimento001.php?y30_codnoti=".$y30_codnoti."&abas=1';\n
         </script>
       ";
  };
};
?>