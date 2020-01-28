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
include("classes/db_prontanulado_classe.php");
include("classes/db_prontproced_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clprontanulado = new cl_prontanulado;
$clprontproced  = new cl_prontproced;

$db_opcao = 1;
$db_botao = true;

$sd57_d_data_dia  = date("d",db_getsession("DB_datausu"));
$sd57_d_data_mes  = date("m",db_getsession("DB_datausu"));
$sd57_d_data_ano  = date("Y",db_getsession("DB_datausu"));
$sd57_c_hora  = date("H",db_getsession("DB_datausu")).":".date("m",db_getsession("DB_datausu"));
$sd57_i_login = DB_getsession("DB_id_usuario");
$nome = DB_getsession("DB_login");
//$sd57_i_prontuario = $chavepesquisaprontuario;


//Verifica se FAA foi digitada
/*
$clprontproced->sql_record($clprontproced->sql_query("","*","", "sd29_i_prontuario = $chavepesquisaprontuario "));
if( $clprontproced->numrows > 0  ){
	?>
		<script>
			alert('FAA possui procedimento, n�o podendo ser anulada.'); 
    	    parent.db_iframe_prontanulado.hide();
		</script>
	<?
	exit;
}
*/

if(isset($incluir)){
	db_inicio_transacao();
	$clprontanulado->incluir("");
	db_fim_transacao();
}else if(isset($excluir)){
	db_inicio_transacao();
	$clprontanulado->excluir($sd57_i_codigo);
	db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/jquery.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table height="20" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td  height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
</td>
</tr>
</table>
<center>
<table  border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
		<?
		include("forms/db_frmprontanulado.php");
		?>
    </center>
	</td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd57_i_prontuario",true,1,"sd57_i_prontuario",true);
</script>
<?
if(isset($incluir)||isset($excluir)){
  if($clprontanulado->erro_status=="0"){
    $clprontanulado->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clprontanulado->erro_campo!=""){
      echo "<script> document.form1.".$clprontanulado->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprontanulado->erro_campo.".focus();</script>";
    }
  }else{
    $clprontanulado->erro(true,true);
  }
}
?>