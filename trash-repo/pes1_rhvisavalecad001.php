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
include("classes/db_rhvisavalecad_classe.php");
include("classes/db_rhvisavale_classe.php");
include("classes/db_db_sysfuncoes_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrhvisavalecad = new cl_rhvisavalecad;
$clrhvisavale    = new cl_rhvisavale;
$db_opcao = 1;
$db_botao = true;
$instit=db_getsession('DB_instit');
if(isset($incluir)){
  db_inicio_transacao();
  $clrhvisavalecad->rh49_instit = db_getsession("DB_instit");
  $clrhvisavalecad->incluir($rh49_codigo);
  db_fim_transacao();
}else if (isset($rh49_regist) && trim($rh49_regist) != "" ){
   $rhVisaValeCad=$clrhvisavalecad->sql_record($clrhvisavalecad->sql_query_file("$instit","*",null,"rh49_anousu=$rh49_anousu and rh49_mesusu=$rh49_mesusu and rh49_regist=$rh49_regist"));
   if ($clrhvisavalecad->numrows > 0){
     db_fieldsmemory($rhVisaValeCad,0);
   }
}else{
$rhVisaVale=$clrhvisavale->sql_record($clrhvisavale->sql_query_file("$instit","*",null,null));
   if ($clrhvisavale->numrows>0){
       db_fieldsmemory($rhVisaVale,0);
       $rh49_perc    = $rh47_perc;
    }
$rh49_percdep = 100; 
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
	include("forms/db_frmrhvisavalecad.php");
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
if(isset($incluir)){
  if($clrhvisavalecad->erro_status=="0"){
    $clrhvisavalecad->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clrhvisavalecad->erro_campo!=""){
      echo "<script> document.form1.".$clrhvisavalecad->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhvisavalecad->erro_campo.".focus();</script>";
    };
  }else{
    $clrhvisavalecad->erro(true,true);
  };
};
?>
<script>
js_tabulacaoforms("form1","rh49_regist",true,1,"rh49_regist",true);
</script>