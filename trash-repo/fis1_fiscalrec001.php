<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_fiscalrec_classe.php");
include("classes/db_fiscalprocrec_classe.php");
include("classes/db_fiscaltipo_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clfiscalrec      = new cl_fiscalrec;
$clfiscalprocrec  = new cl_fiscalprocrec;
$clfiscaltipo     = new cl_fiscaltipo;
$db_opcao = 1;
$db_botao = true;
$lSqlErro = false;
global $y42_codnoti;
if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir") {
	
  db_inicio_transacao();

  $clfiscalrec->excluir($y42_codnoti, $y42_receit);
  if ($clfiscalrec->erro_status == 0) {
    $lSqlErro = true;
  }
  
  $clfiscalrec->incluir($y42_codnoti, $y42_receit);
  if ($clfiscalrec->erro_status == 0) {
    $lSqlErro = true;	
  }
  db_fim_transacao($lSqlErro);
} else if (isset($y42_codnoti) && $y42_codnoti != "" && !isset($receitasdofiscal)) {
	
	$sSqlFiscalProcRec = $clfiscalprocrec->sql_query_fiscaltipo("",""," distinct y45_receit,y45_codtipo,y45_descr,y45_valor",""," y31_codnoti = $y42_codnoti");
  $result = $clfiscalprocrec->sql_record($sSqlFiscalProcRec);
  if ($clfiscalprocrec->numrows > 0 && !$lSqlErro) {
  	
  	db_inicio_transacao();
  	
    for ($j = 0; $j < $clfiscalprocrec->numrows; $j++) {
    	
      db_fieldsmemory($result, $j);
      
      $clfiscalrec->y42_descr=($y45_descr != ""?$y45_descr:"0");
      $clfiscalrec->y42_valor=$y45_valor;
      $clfiscalrec->incluir($y42_codnoti, $y45_receit);
      if ($clfiscalrec->erro_status == 0) {
      	
      	$lSqlErro = true;
      	break;
      }
    }
    
    db_fim_transacao($lSqlErro);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
			<?
			  include("forms/db_frmfiscalrec.php");
			?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
js_setatabulacao();
</script>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($clfiscalrec->erro_status=="0"){
    $clfiscalrec->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clfiscalrec->erro_campo!=""){
      echo "<script> document.form1.db_opcao.value='Incluir';</script>  ";
      echo "<script> document.form1.".$clfiscalrec->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clfiscalrec->erro_campo.".focus();</script>";
    }else{
      echo "<script>parent.iframe_receitas.location.href='fis1_fiscalrec001.php?y42_codnoti=".$y42_codnoti."&abas=1';</script>\n";
    }
  }else{
    $clfiscalrec->erro(true,false);
    echo "<script>parent.iframe_receitas.location.href='fis1_fiscalrec001.php?y42_codnoti=".$y42_codnoti."&abas=1';</script>\n";
  };
};
if(isset($y42_codnoti) && $y42_codnoti != ""){
  $clfiscalrec->sql_record($clfiscalrec->sql_query($y42_codnoti)); 
  if($clfiscalrec->numrows == 0){
    echo "<script>parent.document.formaba.receitas.disabled=true;</script>";
  }
}
?>