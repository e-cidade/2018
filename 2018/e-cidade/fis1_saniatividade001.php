<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
include("classes/db_saniatividade_classe.php");
include("classes/db_sanitario_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clsaniatividade = new cl_saniatividade;
$clsanitario     = new cl_sanitario;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$db_opcao = 1;
$db_botao = true;
if(!isset($y83_codsani)){
  exit;
}

$sSql       = $clsanitario->sql_query($y83_codsani, 'z01_cgccpf');
$z01_cgccpf = db_utils::fieldsMemory($clsanitario->sql_record($sSql), 0)->z01_cgccpf;

if((isset($HTTP_POST_VARS["opcaoExec"]) && $HTTP_POST_VARS["opcaoExec"])=="Incluir"){

  db_inicio_transacao();
  $sqlerro = false;
  if($y83_ativprinc == 't'){
    $result = $clsaniatividade->sql_record($clsaniatividade->sql_query("","","y83_codsani as cod,y83_seq as seq",""," y83_codsani = $y83_codsani"));

    if($clsaniatividade->numrows > 0){
      db_fieldsmemory($result,0);
      $clsaniatividade->y83_codsani = $cod;
      $clsaniatividade->y83_estado = 'f';
      $clsaniatividade->alterar_atividade($cod,'f');
      if ($clsaniatividade->erro_status=="0"){
				$sqlerro=true;
				$erro_msg=$clsaniatividade->erro_msg;
				//db_msgbox("saniatividade");
	  }
    }
  }

  $clsaniatividade->y83_perman    = $y83_perman;
  $clsaniatividade->incluir($y83_codsani,$y83_seq);
  //db_msgbox("sani $y83_codsani - $y83_seq");
  if ($clsaniatividade->erro_status=="0"){
				$sqlerro=true;
				$erro_msg=$clsaniatividade->erro_msg;
				db_msgbox("saniatividade $y83_codsani - $y83_seq");
	  }

  $result = $clsanitario->sql_record($clsanitario->sql_query("","*",""," y80_codsani = $y83_codsani"));

  if($clsanitario->numrows > 0){
    db_fieldsmemory($result,0);
    if($y80_dtbaixa != ""){
      $clsaniatividade->sql_record($clsaniatividade->sql_query("","","*",""," y83_codsani = $y83_codsani and y83_dtfim is null"));
      if($clsaniatividade->numrows > 0){
        $HTTP_POST_VARS["y80_dtbaixa_dia"] = "";
        $HTTP_POST_VARS["y80_dtbaixa_mes"] = "";
        $HTTP_POST_VARS["y80_dtbaixa_ano"] = "";
        $clsanitario->y80_dtbaixa = "";
        $clsanitario->y80_codsani = $y80_codsani;
        $clsanitario->alterar($y83_codsani);
        if ($clsanitario->erro_status=="0"){
				$sqlerro=true;
				$erro_msg=$clsanitario->erro_msg;
				db_msgbox("sanitario");
	  }
	echo " <script>
			     parent.iframe_sanitario.location.href='fis1_sanitario002.php?chavepesquisa=".$clsaniatividade->y83_codsani."';
	       </script>
	     ";
$db_opcao = 1;
      }
    }
  }
  if ($sqlerro==true){
  	db_msgbox($erro_msg);
  }
  db_fim_transacao($sqlerro);
  if (!$sqlerro)	{
	echo " <script>
					parent.iframe_calculo.location.href='fis1_sanicalc001.php?y80_codsani=".$clsaniatividade->y83_codsani."';
				 </script>";
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
        //$db_opcao = 1;
	include("forms/db_frmsaniatividade.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["opcaoExec"]) && $HTTP_POST_VARS["opcaoExec"])=="Incluir"){
  if($clsaniatividade->erro_status=="0"){
    echo "<script>parent.iframe_saniatividade.location.href='fis1_saniatividade001.php?y83_codsani=".$clsaniatividade->y83_codsani."&opcao=Incluir';\n</script>";
    echo "<script> document.form1.db_opcao.disabled=false;</script>";
    if($clsaniatividade->erro_campo!=""){
      echo "<script> document.form1.".$clsaniatividade->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsaniatividade->erro_campo.".focus();</script>";
    };
  }else{
    $clsaniatividade->erro(true,false);
    echo "<script> document.form1.db_opcao.value='Incluir';</script>";
    echo "
         <script>
         function js_src(){
         parent.iframe_saniatividade.location.href='fis1_saniatividade001.php?y83_codsani=".$y83_codsani."&opcao=Incluir';\n
         }
         js_src();
         </script>
     ";
  };
};
?>