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
include("classes/db_editalproj_classe.php");
include("classes/db_edital_classe.php");
include("classes/db_parcontrib_classe.php");
include("classes/db_tabrec_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_editaldoc_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$cledital     = new cl_edital;
$cltabrec     = new cl_tabrec;
$cleditaldoc  = new cl_editaldoc;
$clparcontrib = new cl_parcontrib;
$cleditalproj = new cl_editalproj;
$db_opcao     = 1;
$db_botao     = true;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  $sqlerro=false;
  db_inicio_transacao();
  $cledital->d01_idlog=db_getsession("DB_id_usuario");
  $cledital->d01_data= date("Y-m-d",db_getsession("DB_datausu"));
  $cledital->incluir($d01_codedi);  
  $codedi = $cledital->d01_codedi;
  
  if( isset( $d13_db_documento ) && $d13_db_documento != "" ) {
   
    $cleditaldoc->d13_db_documento = $d13_db_documento;
    $cleditaldoc->d13_edital       = $cledital->d01_codedi;
    $cleditaldoc->incluir(null);
    if ( $cleditaldoc->erro_status == '0' ) {
      $sqlerro = true;
    }

  
  }
  
  $dados=split("XX",$codigo);
  for($r=0; $r<sizeof($dados); $r++){
      if($dados[$r]!=""){
        $cleditalproj->d10_codedi=$codedi;
        $cleditalproj->d10_codigo=$dados[$r];
        $cleditalproj->incluir($codedi,$dados[$r]);
       if($cleditalproj->erro_status=='0'){
          $sqlerro=true;
          break;
       }
     }  
  }
  db_fim_transacao($sqlerro);
}
if(empty($HTTP_POST_VARS["db_opcao"])){ 
  $result01 = $clparcontrib->sql_record($clparcontrib->sql_query("","d12_perc as d01_perc,d12_perunica as d01_perunica,d12_receita as d01_receit,k02_descr,d12_numtot as d01_numtot"));
  db_fieldsmemory($result01,0);
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
    <center>
	<?
	include("forms/db_frmeditalalt.php");
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
if($cledital->erro_status=="0"){
  $cledital->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cledital->erro_campo!=""){
    echo "<script> document.form1.".$cledital->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$cledital->erro_campo.".focus();</script>";
  };
}else{
  $cledital->erro(true,true);
};
if(empty($HTTP_POST_VARS["db_opcao"])){ 
  $data=date("d,m,Y",db_getsession('DB_datausu'));
  echo "
    <script>
     function js_nextmes(){//pega a data daqui a um mes
       x=js_retornadata($data);
       document.form1.d01_privenc_dia.value=x.getDate();
       document.form1.d01_privenc_mes.value=x.getMonth()+1;
       document.form1.d01_privenc_ano.value=x.getFullYear();
     }  
     js_nextmes();
    </script>
    ";	
}    
?>