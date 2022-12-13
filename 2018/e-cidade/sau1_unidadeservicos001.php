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
include("classes/db_unidadeservicos_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

$clunidadeservicos        = new cl_unidadeservicos;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$db_opcao = 1;
$db_botao = true;

@$s126_i_unidade = $chavepesquisa;

if( isset($s126_i_codigo) && isset($opcao) ){
	$resUnidadeservicos = $clunidadeservicos->sql_record( $clunidadeservicos->sql_query($s126_i_codigo) );
	db_fieldsmemory($resUnidadeservicos,0);
	$db_opcao = $opcao=='alterar'?2:3;
}

if(isset($incluir)){
  db_inicio_transacao();
  $clunidadeservicos->incluir($s126_i_codigo);
  db_fim_transacao();
}
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $clunidadeservicos->alterar($s126_i_codigo);
  db_fim_transacao();
}elseif(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clunidadeservicos->excluir($s126_i_codigo);
  db_fim_transacao();
}
/*
elseif(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clunidadeservicos->sql_record($clunidadeservicos->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
}
*/
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmunidadeservicos.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","s126_i_unidade",true,1,"s126_i_unidade",true);
</script>
<?
if(isset($incluir) || isset($cancelar) || isset($alterar) || isset($excluir)){
  if($clunidadeservicos->erro_status=="0"){
    $clunidadeservicos->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clunidadeservicos->erro_campo!=""){
      echo "<script> document.form1.".$clunidadeservicos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clunidadeservicos->erro_campo.".focus();</script>";
    }
  }else{
    //$clunidadeservicos->erro(true,true);
    db_redireciona("sau1_unidadeservicos001.php?chavepesquisa=$s126_i_unidade");
  }
}
?>