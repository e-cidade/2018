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
include("classes/db_fiscalocal_classe.php");
include("classes/db_fiscexec_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_procfiscalnotificacao_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(!isset($abas)){
  echo "<script>location.href='fis1_fiscal005.php?db_opcao=2'</script>";
  exit;
}
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clfiscal     = new cl_fiscal;
$clfiscalocal = new cl_fiscalocal;
$clfiscexec   = new cl_fiscexec;
$clprocfiscalnotificacao = new cl_procfiscalnotificacao;
$db_opcao = 22;
$db_botao = false;
echo "
<script>
  parent.document.formaba.fiscaltipo.disabled=true; 
  parent.document.formaba.receitas.disabled=true; 
  parent.document.formaba.fiscais.disabled=true; 
  parent.document.formaba.test.disabled=true; 
  parent.document.formaba.artigos.disabled=true; 
</script>
";
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  db_inicio_transacao();
  $db_opcao = 2;
  $clfiscal->alterar($y30_codnoti);
  $result = $clfiscalocal->sql_record($clfiscalocal->sql_query($y30_codnoti)); 
  if($clfiscalocal->numrows > 0){
    $clfiscalocal->y12_codnoti=$y30_codnoti;
    $clfiscalocal->y12_codigo=$y12_codigo;
    $clfiscalocal->y12_codi=$y12_codi;
    $clfiscalocal->y12_numero=$y12_numero;
    $clfiscalocal->y12_compl=$y12_compl;
    $clfiscalocal->alterar($y30_codnoti);
  }else{
    $clfiscalocal->incluir($y30_codnoti);
  }
  $result = $clfiscexec->sql_record($clfiscexec->sql_query($y30_codnoti)); 
  if($clfiscexec->numrows > 0){
    $clfiscexec->y13_codnoti=$y30_codnoti;
    $clfiscexec->y13_codigo=$y13_codigo;
    $clfiscexec->y13_codi=$y13_codi;
    $clfiscexec->y13_numero=$y13_numero;
    $clfiscexec->y13_compl=$y13_compl;
    $clfiscexec->alterar($y30_codnoti);
  }else{
    $clfiscexec->incluir($y30_codnoti);
  }
	
	
// exclui e inclui no procfiscalnotificacao
	 $sqlprocfiscalv = "select y110_sequencial from procfiscalnotificacao where y110_notificacaofiscal = $y30_codnoti ";
	 $resultprocfiscalv = pg_query($sqlprocfiscalv);
	 $linhasprocfiscalv = pg_num_rows($resultprocfiscalv);
	 if($linhasprocfiscalv>0){
	 	 db_fieldsmemory($resultprocfiscalv,0);
	   $clprocfiscalnotificacao->y110_sequencial =$y110_sequencial;
		 $clprocfiscalnotificacao->excluir($y110_sequencial);
		 if($clprocfiscalnotificacao->erro_status==0){
				$erro=$clprocfiscalnotificacao->erro_msg;
	      $sqlerro = true;
		 }
	 }
	 if($procfiscal!=""){
		  $clprocfiscalnotificacao->y110_notificacaofiscal = $y30_codnoti;
		  $clprocfiscalnotificacao->y110_procfiscal        = $procfiscal ;
		  $clprocfiscalnotificacao->incluir(null);
			if($clprocfiscalnotificacao->erro_status==0){
				$erro=$clprocfiscalnotificacao->erro_msg;
	      $sqlerro = true;
	    }
		}
	
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clfiscal->sql_record($clfiscal->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $result = $clfiscalocal->sql_record($clfiscalocal->sql_query($chavepesquisa,"*")); 
   if($clfiscalocal->numrows > 0){
     db_fieldsmemory($result,0);
   }else{   		
   		$result_ender=$clfiscal->sql_record($clfiscal->sql_query_ender($chavepesquisa));
   		if ($clfiscal->numrows>0){
   			     db_fieldsmemory($result_ender,0);
   			     $y12_codigo=$codrua;
   			     $y12_codi=$codbairro;
   			     $j14_nome=$nomerua;
   			     $j13_descr=$nomebairro;
   			     $y12_compl=$compl;
   			     $y12_numero=$numero;
   			     
   		}
   }
   $result = $clfiscexec->sql_record($clfiscexec->sql_query($chavepesquisa,"*")); 
   if($clfiscexec->numrows > 0){
     db_fieldsmemory($result,0);
   }
   $db_botao = true;
echo "
<script>
  parent.iframe_fiscaltipo.location.href='fis1_fiscaltipo001.php?y31_codnoti=".$chavepesquisa."&abas=1';\n
  parent.iframe_receitas.location.href='fis1_fiscalrec001.php?y42_codnoti=".$chavepesquisa."&abas=1';\n
  parent.iframe_fiscais.location.href='fis1_fiscalusuario001.php?y38_codnoti=".$chavepesquisa."&abas=1';\n
  parent.iframe_test.location.href='fis1_fisctestem001.php?y23_codnoti=".$chavepesquisa."&abas=1';\n
  parent.iframe_artigos.location.href='fis1_fiscarquivos001.php?y26_codnoti=".$chavepesquisa."&abas=1';\n
  parent.document.formaba.fiscaltipo.disabled=false; 
  parent.document.formaba.receitas.disabled=false; 
  parent.document.formaba.fiscais.disabled=false; 
  parent.document.formaba.test.disabled=false; 
  parent.document.formaba.artigos.disabled=false; 
</script>  
  ";
	
$sqlprocfiscal = " select y110_procfiscal as procfiscal,z01_nome as nome
                     from procfiscalnotificacao 
                     inner join procfiscalcgm on y110_procfiscal = y101_procfiscal 
										 inner join cgm           on y101_numcgm     = z01_numcgm 
  								 where y110_notificacaofiscal = $chavepesquisa
									";
	 $resultprocfiscal = pg_query($sqlprocfiscal);
	 $linhasprocfiscal = pg_num_rows($resultprocfiscal);
	 if($linhasprocfiscal>0){
	 	 db_fieldsmemory($resultprocfiscal,0);
	 }else{
	 	 $nome="";
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
	include("forms/db_frmfiscal.php");
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
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  if($clfiscal->erro_status=="0"){
    $clfiscal->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clfiscal->erro_campo!=""){
      echo "<script> document.form1.".$clfiscal->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clfiscal->erro_campo.".focus();</script>";
    };
  }else{
    $clfiscal->erro(true,false);
    echo "
         <script>
         function js_src(){
           parent.iframe_fiscal.location.href='fis1_fiscal002.php?chavepesquisa=".$clfiscal->y30_codnoti."&abas=1';\n
           parent.iframe_fiscaltipo.location.href='fis1_fiscaltipo001.php?y31_codnoti=".$clfiscal->y30_codnoti."&abas=1';\n
           parent.iframe_receitas.location.href='fis1_fiscalrec002.php?chavepesquisa=".$clfiscal->y30_codnoti."&abas=1';\n
           parent.iframe_fiscais.location.href='fis1_fiscalusuario001.php?y38_codnoti=".$clfiscal->y30_codnoti."&abas=1';\n
           parent.iframe_test.location.href='fis1_fisctestem001.php?y23_codnoti=".$clfiscal->y30_codnoti."&abas=1';\n
           parent.iframe_artigos.location.href='fis1_fiscarquivos001.php?y26_codnoti=".$clfiscal->y30_codnoti."&abas=1';\n
           parent.mo_camada('fiscaltipo');
	   parent.document.formaba.fiscaltipo.disabled=false; 
	   parent.document.formaba.receitas.disabled=true; 
	   parent.document.formaba.fiscais.disabled=false; 
	   parent.document.formaba.test.disabled=false; 
         }
         js_src();
         </script>
       ";
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>