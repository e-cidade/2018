<?PHP
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

Require_once("libs/db_stdlib.php");
Require_once("libs/db_conecta.php");
Require_once("libs/db_sessoes.php");
Require_once("libs/db_usuariosonline.php");
Require_once("dbforms/db_funcoes.php");
Require_once("classes/db_orcparamseq_classe.php");
Require_once("classes/db_orcparamseqorcparamseqcoluna_classe.php");
$clorcparamseq = new cl_orcparamseq;
db_postmemory($_POST);
$db_opcao   = 22;
$db_botao   = false;
$lValidaSeq = false;

if(isset($alterar)){
	
	if ($o69_codseq != $o69_codseq_anterior) {
		$lValidaSeq = true;
	}
	
	$iOrigemAntiga = null;
	$iOrigemNova   = $o69_origem;
	
	$sSql = $clorcparamseq->sql_query_file($o69_codparamrel,$o69_codseq);
	$clorcparamseq->sql_record($sSql);	
	
	if ($clorcparamseq->numrows > 0 && $lValidaSeq) {
		
	  $sqlerro  = true;
		$erro_msg = "O código da sequencia da linha fornecida está sendo utilizado! Verifique!";
	
	} else {
	  
	  $sqlerro = false;
	  db_inicio_transacao();
	  
	  $rsOrcParamSeq = $clorcparamseq->sql_record($sSql);
	  $oOrcParamSeq  = db_utils::fieldsMemory($rsOrcParamSeq, 0);
	  $iOrigemAntiga = $oOrcParamSeq->o69_origem;
	  
	  $clorcparamseq->alterar_where(null,null,"o69_codparamrel = $o69_codparamrel and o69_codseq = $o69_codseq_anterior");
	  
	  if($clorcparamseq->erro_status == 0){
	    $sqlerro = true;
	  } 
	  
	  /*
	   * aqui devemos verificar se o cara trocou a origem (o69_origem) 
	   * para que limpamos as formulas na tabela (orcparamseqorcparamseqcoluna)
	   * pelos campos  (o116_codseq) e (o116_codparamrel) 
	   */ 
	  
	  if ($iOrigemAntiga != $iOrigemNova) {
	    
  	  $oDaoOrcParamColuna = db_utils::getDao('orcparamseqorcparamseqcoluna');
  	  $sWhere = " o116_codseq = {$o69_codseq_anterior} and  o116_codparamrel = {$o69_codparamrel} ";
  	  $oDaoOrcParamColuna->o116_formula = '';
  	  $oDaoOrcParamColuna->alterar_where(null, $sWhere);
  	  
  	  if ($oDaoOrcParamColuna->erro_status == 0) {
  	    $sqlerro  = true;
  	    $erro_msg = $oDaoOrcParamColuna->erro_msg;
  	  }
	  }
	  echo "<script>parent.document.formaba.orcparamseqorcparamseqcoluna.disabled = false; </script>";
	  echo "<script>(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_orcparamseqorcparamseqcoluna.location.href='orc1_orcparamseqorcparamseqcoluna001.php?";
	  echo "  o69_origem=".@$o69_origem."&o116_codparamrel=".@$o69_codparamrel."&o116_codseq=".@$o69_codseq."' </script>";
	  
	  db_fim_transacao($sqlerro);
	  $erro_msg = $clorcparamseq->erro_msg; 
    $db_opcao = 2;
	  $db_botao = true;
	}
		
} else if (isset($chavepesquisa)) {
  
   $db_opcao = 2;
   $db_botao = true;
	 $sSql     = $clorcparamseq->sql_query($chavepesquisa,$chavepesquisa1);
	 $result   = $clorcparamseq->sql_record($sSql); 
   db_fieldsmemory($result,0);
   
   
   $o69_observacao = urldecode($o69_observacao);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br />
<center>
	<?
	include(modification("forms/db_frmorcparamseq.php"));
	?>
</center>

</body>
</html>
<?
if (isset($alterar)) {

  if ($sqlerro == true) {

    db_msgbox($erro_msg);
    
    if ($clorcparamseq->erro_campo != "") {

      echo "<script> document.form1.".$clorcparamseq->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcparamseq->erro_campo.".focus();</script>";
    };
  } else { 
   db_msgbox($erro_msg);
  }
}

if (isset($chavepesquisa)) {

 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.orcparamseqorcparamseqcoluna.disabled=false;
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_orcparamseqorcparamseqcoluna.location.href='orc1_orcparamseqorcparamseqcoluna001.php?o116_codparamrel=".@$o69_codparamrel."&o116_codseq=".@$o69_codseq."';
         parent.document.formaba.configuracao.disabled=false;
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_configuracao.location.href='orc4_orcparamseqfiltropadrao.php?o116_codparamrel=".@$o69_codparamrel."&o116_codseq=".@$o69_codseq."';
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