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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_libdicionario.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_matmater_classe.php");
require_once ("classes/db_transmater_classe.php");
require_once ("classes/db_matmaterunisai_classe.php");
require_once ("classes/db_pcmater_classe.php");
require_once ("classes/db_empempitem_classe.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_matmatermaterialestoquegrupo_classe.php");

db_postmemory($_POST);
$clmatmater                     = new cl_matmater;
$cltransmater                   = new cl_transmater;
$clmatmaterunisai               = new cl_matmaterunisai;
$clpcmater                      = new cl_pcmater;
$clempempitem                   = new cl_empempitem;
$clmatmatermaterialestoquegrupo = new cl_matmatermaterialestoquegrupo;

$db_opcao = 1;
$sqlerro  = false;
$db_botao = true;

if(isset($incluir)) {
  
  db_inicio_transacao();
  $m60_descr=addslashes($m60_descr);
  $m60_ativo="t";
  $clmatmater->m60_descr = $m60_descr;
  $clmatmater->m60_codmatunid = $m60_codmatunid;
  $clmatmater->m60_quantent = $m60_quantent;
  $clmatmater->m60_codant = $m60_codant;
  $clmatmater->m60_ativo = $m60_ativo;
  $clmatmater->incluir($m60_codmater);
  $erro = $clmatmater->erro_msg;
  $codigo=$clmatmater->m60_codmater;
  $clmatmaterunisai->incluir($codigo,$m62_codmatunid);
  if ($clmatmaterunisai->erro_status==0){
     $sqlerro=true;
     
  }
  $cltransmater->m63_codpcmater=$m63_codpcmater;
  $cltransmater->m63_codmatmater=$codigo;
  $cltransmater->incluir(null);
  if ($cltransmater->erro_status==0){
     $erro=$cltransmater->erro_msg."erro2";
     $sqlerro=true;
  }
  
  $clmatmatermaterialestoquegrupo->m68_matmater             = $clmatmater->m60_codmater;
  $clmatmatermaterialestoquegrupo->m68_materialestoquegrupo = $m65_sequencial;
  $clmatmatermaterialestoquegrupo->incluir(null);
  $erro_msg=$clmatmatermaterialestoquegrupo->erro_msg;
  if ($clmatmatermaterialestoquegrupo->erro_status==0){
     
    $sqlerro = true;
    $erro    = $clmatmatermaterialestoquegrupo->erro_msg;
  }
  
//  db_msgbox($clmatmaterunisai->erro_msg);  
  db_fim_transacao($sqlerro);
}
if (isset($numemp)&&$numemp!=""&&isset($sequen)&&$sequen!=""){
  
	$result_resum=$clempempitem->sql_record($clempempitem->sql_query_file(null,null,"*",null,"e62_sequencial={$sequen}"));
	if($clempempitem->numrows>0){
		db_fieldsmemory($result_resum,0);
		$resumo=$e62_descr;
	}
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
</table>
    <center>
	<?
	include("forms/db_frmmatmaterent.php");
	?>
    </center>
</body>
</html>
<?
if(isset($incluir)){
  db_msgbox($erro);
  if($clmatmater->erro_status=="0"||$sqlerro==true){
    $clmatmater->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clmatmater->erro_campo!=""){
      echo "<script> document.form1.".$clmatmater->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatmater->erro_campo.".focus();</script>";
    };
  }else{
    
    if (isset($lLotes)) {
      echo "<script>
              location.href='mat1_matmater011.php?m63_codpcmater=$m63_codpcmater&pc01_descrmater=$pc01_descrmater';
	      parent.iframe_material.hide();
	      oOption = new Option('{$clmatmater->m60_codmater}','{$clmatmater->m60_codmater}');
	      oOption.controlaEstoque = {$clmatmater->m60_controlavalidade};
	      oOption.descr           = '{$clmatmater->m60_descr}';
	      parent.$('matmater').add(oOption,null);
	      parent.$('matmater').value  = {$clmatmater->m60_codmater};
	      oOption = new Option('{$clmatmater->m60_descr}','{$clmatmater->m60_codmater}');
	      oOption.controlaEstoque = {$clmatmater->m60_controlavalidade};
	      oOption.descr           = '{$clmatmater->m60_descr}';
	      parent.$('matmaterdescr').add(oOption,null);
	      parent.$('matmaterdescr').value    = {$clmatmater->m60_codmater};
	      parent.$('matmater').disabled      = false;
	      parent.$('matmaterdescr').disabled = false;
	    
          </script>";
    } else {

        echo "<script>
              location.href='mat1_matmater011.php?m63_codpcmater=$m63_codpcmater&pc01_descrmater=$pc01_descrmater';
	      parent.iframe_material.hide();
	      parent.document.form1.submit();
	     
          </script>"; 
    }
  };
};
?>