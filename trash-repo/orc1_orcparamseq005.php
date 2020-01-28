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
include("classes/db_orcparamseq_classe.php");
include("classes/db_orcparamseqorcparamseqcoluna_classe.php");
$clorcparamseq = new cl_orcparamseq;
  /*
$clorcparamseqorcparamseqcoluna = new cl_orcparamseqorcparamseqcoluna;
  */
db_postmemory($_POST);
$db_opcao = 22;
$db_botao = false;
$lValidaSeq = false;

if(isset($alterar)){
	
	if ($o69_codseq != $o69_codseq_anterior) {
		$lValidaSeq = true;
	}
	
	$sSql = $clorcparamseq->sql_query_file($o69_codparamrel,$o69_codseq);
	$clorcparamseq->sql_record($sSql);	
		
	if ($clorcparamseq->numrows > 0 && $lValidaSeq) {
		
	  $sqlerro  = true;
		$erro_msg = "O c�digo da sequencia da linha fornecida est� sendo utilizado! Verifique!";
	
	} else {
	  $sqlerro=false;
	  db_inicio_transacao();
	  $clorcparamseq->alterar_where(null,null,"o69_codparamrel = $o69_codparamrel and o69_codseq = $o69_codseq_anterior");
	  if($clorcparamseq->erro_status==0){
	    $sqlerro=true;
	  } 
	  $erro_msg = $clorcparamseq->erro_msg; 
	  db_fim_transacao($sqlerro);
    $db_opcao = 2;
	  $db_botao = true;
	}
		
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $db_botao = true;
	 
	 $sSql = $clorcparamseq->sql_query($chavepesquisa,$chavepesquisa1);
	 $result = $clorcparamseq->sql_record($sSql); 
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
<br />
<center>
	<?
	include("forms/db_frmorcparamseq.php");
	?>
</center>

</body>
</html>
<?
if(isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clorcparamseq->erro_campo!=""){
      echo "<script> document.form1.".$clorcparamseq->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcparamseq->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.orcparamseqorcparamseqcoluna.disabled=false;
         top.corpo.iframe_orcparamseqorcparamseqcoluna.location.href='orc1_orcparamseqorcparamseqcoluna001.php?o116_codparamrel=".@$o69_codparamrel."&o116_codseq=".@$o69_codseq."';
         parent.document.formaba.configuracao.disabled=false;
         top.corpo.iframe_configuracao.location.href='orc4_orcparamseqfiltropadrao.php?o116_codparamrel=".@$o69_codparamrel."&o116_codseq=".@$o69_codseq."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('orcparamseqorcparamseqcoluna');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>