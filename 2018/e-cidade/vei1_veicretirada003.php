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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_veicretirada_classe.php"));
require_once(modification("classes/db_veicparam_classe.php"));
require_once(modification("classes/db_veictipoabast_classe.php"));
require_once(modification("classes/db_veiculos_classe.php"));
require_once(modification("classes/db_veicdevolucao_classe.php"));
db_app::import("veiculos.*");


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);

$clveicretirada  = new cl_veicretirada;
$clveicparam     = new cl_veicparam;
$clveictipoabast = new cl_veictipoabast;
$clveiculos      = new cl_veiculos;
$clveicdevolucao = new cl_veicdevolucao;

$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){

  /**
   * Verifica se a retirada esta vinculada a um abastecimento
   */
  $clveicabastretirada = new cl_veicabastretirada();
  $sqlVinculoAbastecimento = $clveicabastretirada->sql_query_file(null, "*", null, "ve73_veicretirada = {$ve60_codigo}");
  $clveicabastretirada->sql_record($sqlVinculoAbastecimento);

  /**
   * Verifica se existe alguma manutenção na retirada
   */
  $clveicmanutretirada = new cl_veicmanutretirada();
  $sqlVinculoManutencao = $clveicmanutretirada->sql_query_file(null, "*", null, "ve65_veicretirada = {$ve60_codigo}");
  $clveicmanutretirada->sql_record($sqlVinculoManutencao);


  if($clveicabastretirada->numrows > 0){
    echo "<script>alert('Nao é possível excluir uma retirada vinculada a um abastecimento.')</script>";
  } 
  elseif($clveicmanutretirada->numrows > 0) {
    echo "<script>alert('Nao é possível excluir uma retirada que possuí vinculo de manutenção.')</script>";
  } else {

    db_inicio_transacao();
    $db_opcao = 3;
    $clveicretirada->excluir($ve60_codigo);
    db_fim_transacao();
  }
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clveicretirada->sql_record($clveicretirada->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
   $db_botao = true;

   $result = $clveiculos->sql_record($clveiculos->sql_query($ve60_veiculo,"ve01_veictipoabast"));
   db_fieldsmemory($result,0);

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
<body bgcolor="#CCCCCC" style='margin-top: 25px' leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
	<?
	include(modification("forms/db_frmveicretirada.php"));
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<?
if(isset($excluir)){
  if($clveicretirada->erro_status=="0"){
    $clveicretirada->erro(true,false);
  }else{
    $clveicretirada->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>
