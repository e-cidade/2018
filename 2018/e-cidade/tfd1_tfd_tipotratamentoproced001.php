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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_tfd_tipotratamentoproced_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oIframeAE = new cl_iframe_alterar_excluir();

db_postmemory($HTTP_POST_VARS);
$cltfd_tipotratamentoproced = new cl_tfd_tipotratamentoproced;
$db_opcao = 1;
$db_botao = true;

if(isset($opcao)) {
  
  $tf05_i_ativo = ($tf05_i_ativo == 'SIM') ? 1 : 2; 
  if($opcao == 'alterar') {
    $db_opcao = 2;
  } else {
    $db_opcao = 3;
  }

}

if(isset($incluir)){
  db_inicio_transacao();
  $cltfd_tipotratamentoproced->incluir($tf05_i_codigo);
  db_fim_transacao($cltfd_tipotratamentoproced->erro_status == '0' ? true : false);
}
if(isset($alterar)) {
 
  $db_opcao = 2;
  $opcao = 'alterar';
  db_inicio_transacao();
  $cltfd_tipotratamentoproced->alterar($tf05_i_codigo);
  db_fim_transacao($cltfd_tipotratamentoproced->erro_status == '0' ? true : false);

}
if(isset($excluir)) {
 
  $db_opcao = 3;
  $opcao = 'excluir';
  db_inicio_transacao();
  $cltfd_tipotratamentoproced->excluir($tf05_i_codigo);
  db_fim_transacao($cltfd_tipotratamentoproced->erro_status == '0' ? true : false);

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
<center>
<br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <fieldset style='width: 75%;'> <legend><b>Procedimentos</b></legend>
	      <?
       	require_once("forms/db_frmtfd_tipotratamentoproced.php");
	      ?>
      </fieldset>
    </center>
	</td>
  </tr>
</table>
</center>
<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
//js_tabulacaoforms("form1","tf05_i_tipotratamento",true,1,"tf05_i_tipotratamento",true);
</script>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($cltfd_tipotratamentoproced->erro_status=="0"){
    $cltfd_tipotratamentoproced->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cltfd_tipotratamentoproced->erro_campo!=""){
      echo "<script> document.form1.".$cltfd_tipotratamentoproced->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltfd_tipotratamentoproced->erro_campo.".focus();</script>";
    }
  }else{
    $cltfd_tipotratamentoproced->erro(true,false);
    db_redireciona("tfd1_tfd_tipotratamentoproced001.php?tf05_i_tipotratamento=$tf05_i_tipotratamento&tf04_c_descr=$tf04_c_descr");
  }
}
?>