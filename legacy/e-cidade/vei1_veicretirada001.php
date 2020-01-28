<?
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_veicretirada_classe.php");
require_once ("classes/db_veiculos_classe.php");
require_once ("classes/db_veicparam_classe.php");
require_once ("classes/db_veictipoabast_classe.php");
require_once ("std/DBDate.php");

db_postmemory($_POST);

$clveicretirada  = new cl_veicretirada;
$clveiculos      = new cl_veiculos;
$clveicparam     = new cl_veicparam;
$clveictipoabast = new cl_veictipoabast;

$db_opcao = 1;
$db_botao = true;
$pesq     = false;

if(isset($incluir)){

  db_inicio_transacao();

  $clveicretirada->ve60_coddepto   = !empty($ve60_coddepto) ? $ve60_coddepto : db_getsession("DB_coddepto");
  $clveicretirada->ve60_passageiro = $ve60_passageiro;
  $clveicretirada->ve60_data       = date("Y-m-d",db_getsession("DB_datausu"));
  $clveicretirada->ve60_hora       = db_hora();
  $clveicretirada->ve60_usuario    = db_getsession("DB_id_usuario");
  $clveicretirada->incluir(null);
  db_fim_transacao(false);

} else if(isset($veiculo)){

   $result_util = $clveicretirada->sql_record($clveicretirada->sql_query_devol(null,"*",null,"ve60_veiculo=$veiculo and ve61_codigo is null"));
   if ($clveicretirada->numrows>0){
   		echo "<script>
 				alert('Este veiculo ja está sendo utilizado!!');
				location.href='vei1_veicretirada001.php';
			  </script>";
		exit;
   }
   $result = $clveiculos->sql_record($clveiculos->sql_query($veiculo,"ve01_codigo,ve01_placa,ve01_veictipoabast"));
   db_fieldsmemory($result,0);
   $ve60_veiculo=$ve01_codigo;
   $pesq=true;

   $result_veictipoabast = $clveictipoabast->sql_record($clveictipoabast->sql_query($ve01_veictipoabast,"ve07_sigla"));
   if ($clveictipoabast->numrows > 0){
     db_fieldsmemory($result_veictipoabast,0);
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
<body bgcolor="#CCCCCC" style="margin-top: 25px" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
	<?
	include("forms/db_frmveicretirada.php");
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ve60_veiculo",true,1,"ve60_veiculo",true);
</script>
<?
if(isset($incluir)){
  if($clveicretirada->erro_status=="0"){
    $clveicretirada->erro(true,false);
    $db_botao = true;
    $pesq     = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clveicretirada->erro_campo!=""){
      echo "<script> document.form1.".$clveicretirada->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clveicretirada->erro_campo.".focus();</script>";
    }
  }else{
    if ($sqlerro==false){
      $clveicretirada->erro(true,true);
    } else {
      db_msgbox($erro_msg);
      $db_botao = true;
      $pesq     = true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      if($clveicretirada->erro_campo!=""){
        echo "<script> document.form1.".$clveicretirada->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clveicretirada->erro_campo.".focus();</script>";
      }
    }
  }
}
if($pesq==false){
///	echo "<script>js_pesquisaveiculo();</script>";
}

?>