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
include("classes/db_cfpess_classe.php");
include("classes/db_inssirf_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clcfpess = new cl_cfpess;
$clinssirf = new cl_inssirf;
$db_opcao = 2;
$db_botao = true;
if(isset($alterar)){

  include("pes1_cfpess002.php");

}
//$sql = $clcfpess->sql_query_file(db_anofolha(),db_mesfolha(),db_getsession("DB_instit"),"*");
$sql = $clcfpess->sql_query_parametro(db_anofolha(),db_mesfolha(),db_getsession("DB_instit"),"
                                                             r11_rubdec,a.rh27_descr as rh27_descr1,
                                                             r11_ferias,b.rh27_descr as rh27_descr2,
                                                             r11_fer13, c.rh27_descr as rh27_descr3,
                                                             r11_ferabo,d.rh27_descr as rh27_descr4,
                                                             r11_feradi,e.rh27_descr as rh27_descr5,
                                                             r11_fadiab,f.rh27_descr as rh27_descr6,
                                                             r11_ferant,g.rh27_descr as rh27_descr7,
                                                             r11_feabot,h.rh27_descr as rh27_descr8,
                                                             r11_palime,i.rh27_descr as rh27_descr9,
                                                             r11_fer13a,j.rh27_descr as rh27_descr10,
                                                             r11_desliq, r11_rubpgintegral
                                                             ");
//echo "<BR> $sql";
$result = $clcfpess->sql_record($sql);
if($result != false && $clcfpess->numrows > 0){
  db_fieldsmemory($result,0);
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <?
      include("forms/db_frmcfpessrubricasespeciais.php");
      ?>
      </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($clcfpess->erro_status == "0"){
    $clcfpess->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcfpess->erro_campo != ""){
      echo "<script> document.form1.".$clcfpess->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcfpess->erro_campo.".focus();</script>";
    }
  }else{
    $clcfpess->erro(true,true);
  }
}
if($db_opcao == 22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>