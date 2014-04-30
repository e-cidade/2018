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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_orcparamrelopcre_classe.php");
include("classes/db_orcparamseq_classe.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");

$oGet                     = db_utils::postmemory($_GET);
$oPost                    = db_utils::postmemory($_POST);
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clorcparamrelopcre       = new cl_orcparamrelopcre;
$clorcparamseq            = new cl_orcparamseq;
$db_opcao                 = 1;
$db_botao                 = true;
$lLimpar                  = false;   
if (isset($oPost->novo)) {
  
  unset($_POST);
  unset($oPost);
  $db_opcao   = 1;
  $db_botao   = true;
    
  
}
if (isset($oPost->opcao)) {
  
   if ($oPost->opcao == "alterar") {  
   
      $db_opcao                 = 2;
      $db_botao                 = true;
      
   }else if ($oPost->opcao == 'excluir'){
     
      $db_opcao                 = 3;
      $db_botao                 = true;
     
   }
  
}

if (isset($oPost->incluir)) {
  
  db_inicio_transacao();
  $clorcparamrelopcre->o98_orcparamrel = $oGet->c83_codrel;
  $clorcparamrelopcre->o98_instit      = db_getsession("DB_instit");
  $clorcparamrelopcre->incluir($o98_sequencial);
  db_fim_transacao();
  $lLimpar = true;
  
}else if (isset($oPost->alterar)) {
  
  db_inicio_transacao();
    $clorcparamrelopcre->alterar($oPost->o98_sequencial);
  db_fim_transacao();
  $lLimpar = true;
  
}else if (isset($oPost->excluir)) {
  
  db_inicio_transacao();
  $clorcparamrelopcre->excluir($oPost->o98_sequencial);
  db_fim_transacao();
  $lLimpar = true;
}
if (isset($oPost->o98_sequencial) && $oPost->o98_sequencial != ''){
  
  $rsOrcParam = $clorcparamrelopcre->sql_record($clorcparamrelopcre->sql_query($oPost->o98_sequencial));
  if ($clorcparamrelopcre->numrows > 0) {
   
     db_fieldsMemory($rsOrcParam, 0 );    
   }
}
if ($lLimpar){
  
  $o98_sequencial    = null;
  $o98_orcparamseq   = null;
  $o98_credor        = null;
  $o98_periodo       = null;
  $o98_identificacao = null;
  $o98_valor         = null;  
  
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
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
 <center>
	<?
	include("forms/db_frmorcparamrelopcre.php");
	?>
  </center>
</body>
</html>
<script>
js_tabulacaoforms("form1","o98_orcparamseq",true,1,"o98_orcparamseq",true);
</script>
<?
if(isset($incluir)){
  if($clorcparamrelopcre->erro_status=="0"){
    $clorcparamrelopcre->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clorcparamrelopcre->erro_campo!=""){
      echo "<script> document.form1.".$clorcparamrelopcre->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcparamrelopcre->erro_campo.".focus();</script>";
    }
  }else{
    $clorcparamrelopcre->erro(true,false);
  }
}
?>