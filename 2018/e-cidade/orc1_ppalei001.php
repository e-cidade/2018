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
include("classes/db_ppalei_classe.php");
include("classes/db_ppaversao_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clppalei    = new cl_ppalei;
$clppaversao = new cl_ppaversao();
$db_opcao    = 1;
$db_botao    = true;
$sqlerro     = false;
if(isset($incluir)){
  db_inicio_transacao();
  $clppalei->o01_instit = db_getsession("DB_instit");
  $clppalei->incluir(null);
  if ($clppalei->erro_status != 0) {

    $clppaversao->o119_datainicio  = date("Y-m-d", db_getsession("DB_datausu"));
    $clppaversao->o119_datatermino = "";
    $clppaversao->o119_finalizada  = "false";
    $clppaversao->o119_versaofinal = "false";
    $clppaversao->o119_ppalei      = $clppalei->o01_sequencial;
    $clppaversao->o119_versao      = 1;
    $clppaversao->o119_idusuario   = db_getsession("DB_id_usuario");
    $clppaversao->o119_ativo       = "true";
    $clppaversao->incluir(null);
    if ($clppaversao->erro_status == 0) {

       $sqlerro = true;
       $clppalei->erro_msg    = $clppaversao->erro_msg;
       $clppalei->erro_status = "0";
    }

  }

  db_fim_transacao($sqlerro);
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
    <center>
	<?
	include("forms/db_frmppalei.php");
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
<script>
js_tabulacaoforms("form1","o01_anoinicio",true,1,"o01_anoinicio",true);
</script>
<?
if(isset($incluir)){
  if($clppalei->erro_status=="0"){
    $clppalei->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clppalei->erro_campo!=""){
      echo "<script> document.form1.".$clppalei->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clppalei->erro_campo.".focus();</script>";
    }
  }else{
    $clppalei->erro(true,true);
  }
}
?>