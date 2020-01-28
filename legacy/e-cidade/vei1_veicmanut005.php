<?php
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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

db_postmemory($_POST);

$clveicmanut         = new cl_veicmanut;
$clveicmanutoficina  = new cl_veicmanutoficina;
$clveicmanutretirada = new cl_veicmanutretirada;
$clveiculos          = new cl_veiculos;
$clveictipoabast     = new cl_veictipoabast;
$clveicretirada      = new cl_veicretirada;

$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;

$clveicmanut->ve62_vlrmobra = null;
$clveicmanut->ve62_vlrpecas = null;

if (isset($alterar)) {

  if (isset($ve62_codigo)) {
    $oManutencao = VeiculoManutencao::getInstanciaPorCodigo($ve62_codigo);
  }

  if ($sqlerro == false) {

    db_inicio_transacao();

    if (!empty($ve65_veicretirada)) {

      $clveicmanut->ve62_veicmotoristas = null;
      $ve62_veicmotoristas              = null;
    }

    if (empty($ve62_veicmotoristas)) {
      $clveicmanut->ve62_veicmotoristas = "null";
    }

    $lFksValidas = true;
    if(!empty($ve62_veicmotoristas) && $ve62_veicmotoristas !== "null") {

      $sSqlFk = "select ve05_codigo from veicmotoristas where ve05_codigo = {$ve62_veicmotoristas}";
      $rsFk   = db_query($sSqlFk);
      if (pg_num_rows($rsFk) == 0) {

        $erro_msg    = "O valor informado para o campo Motorista é inválido.";
        $lFksValidas = false;
      }
    }
    if(!empty($ve62_veiccadtiposervico)) {

      $sSqlFk = "select ve28_codigo from veiccadtiposervico where ve28_codigo = {$ve62_veiccadtiposervico}";
      $rsFk   = db_query($sSqlFk);
      if (pg_num_rows($rsFk) == 0) {

        $erro_msg    = "O valor informado para o campo Tipo de Serviço é inválido.";
        $lFksValidas = false;
      }
    }
    if(!empty($ve62_veiculos)) {

      $sSqlFk = "select ve01_codigo from veiculos where ve01_codigo = {$ve62_veiculos}";
      $rsFk   = db_query($sSqlFk);
      if (pg_num_rows($rsFk) == 0) {

        $ve62_veiculos = $oManutencao->getCodigoVeiculo();
        $erro_msg      = "O valor informado para o campo Veículo é inválido.";
        $lFksValidas   = false;
      }
    }
    if(!empty($ve66_veiccadoficinas)) {

      $sSqlFk = "select ve27_codigo from veiccadoficinas where ve27_codigo = {$ve66_veiccadoficinas}";
      $rsFk   = db_query($sSqlFk);
      if (pg_num_rows($rsFk) == 0) {

        $erro_msg    = "O valor informado para o campo Oficina é inválido.";
        $lFksValidas = false;
      }
    }
    if(!empty($ve65_veicretirada)) {

      $sSqlFk = "select ve60_codigo from veicretirada where ve60_codigo = {$ve65_veicretirada}";
      $rsFk   = db_query($sSqlFk);
      if (pg_num_rows($rsFk) == 0) {

        $erro_msg    = "O valor informado para o campo Retirada é inválido.";
        $lFksValidas = false;
      }
    }

    if ($lFksValidas) {
      $clveicmanut->alterar($ve62_codigo);
    }

    if($clveicmanut->erro_status==0){
      $sqlerro=true;
    }

    if (!empty($clveicmanut->erro_msg)) {
      $erro_msg = $clveicmanut->erro_msg;
    }
    if ($lFksValidas && $sqlerro==false){
      $result_oficina=$clveicmanutoficina->sql_record($clveicmanutoficina->sql_query(null,"ve66_codigo",null,"ve66_veicmanut=$ve62_codigo"));
      if (isset($ve66_veiccadoficinas)&&$ve66_veiccadoficinas!=""){
        if($clveicmanutoficina->numrows>0){
          db_fieldsmemory($result_oficina,0);
          $clveicmanutoficina->ve66_codigo=$ve66_codigo;
          $clveicmanutoficina->alterar($ve66_codigo);
          if ($clveicmanutoficina->erro_status=="0"){
            $erro_msg=$clveicmanutoficina->erro_msg;
            $sqlerro=true;
          }
        }else{
          $clveicmanutoficina->ve66_veicmanut=$clveicmanut->ve62_codigo;
          $clveicmanutoficina->incluir(null);
          if ($clveicmanutoficina->erro_status=="0"){
            $erro_msg=$clveicmanutoficina->erro_msg;
            $sqlerro=true;
          }
        }
      }else{
        if($clveicmanutoficina->numrows>0){
          $clveicmanutoficina->excluir(null,"ve66_veicmanut=$ve62_codigo");
          if ($clveicmanutoficina->erro_status=="0"){
            $erro_msg=$clveicmanutoficina->erro_msg;
            $sqlerro=true;
          }
        }
      }
    }
    if ($lFksValidas && $sqlerro==false){
      $result_retirada=$clveicmanutretirada->sql_record($clveicmanutretirada->sql_query(null,"ve65_codigo",null,"ve65_veicmanut=$ve62_codigo"));
      if (isset($ve65_veicretirada)&&$ve65_veicretirada!=""){
        if($clveicmanutretirada->numrows>0){
          db_fieldsmemory($result_retirada,0);
          $clveicmanutretirada->ve65_codigo=$ve65_codigo;
          $clveicmanutretirada->alterar($ve65_codigo);
          if ($clveicmanutretirada->erro_status=="0"){
            $erro_msg=$clveicmanutretirada->erro_msg;
            $sqlerro=true;
          }
        }else{
          $clveicmanutretirada->ve65_veicmanut=$clveicmanut->ve62_codigo;
          $clveicmanutretirada->incluir(null);
          if ($clveicmanutretirada->erro_status=="0"){
            $erro_msg=$clveicmanutretirada->erro_msg;
            $sqlerro=true;
          }
        }
      }else{
        if($clveicmanutretirada->numrows>0){
          $clveicmanutretirada->excluir(null,"ve65_veicmanut=$ve62_codigo");
          if ($clveicmanutretirada->erro_status=="0"){
            $erro_msg=$clveicmanutretirada->erro_msg;
            $sqlerro=true;
          }
        }
      }
    }
    db_fim_transacao($sqlerro);
  }
  $db_opcao = 2;
  $db_botao = true;
} else if (isset($chavepesquisa) && $chavepesquisa != "0") {

  $db_opcao = 2;
  $db_botao = true;
  $sCampos  = " *, motorista.z01_nome as descricao_motorista ";
  $result   = $clveicmanut->sql_record($clveicmanut->sql_query($chavepesquisa, $sCampos));
  if ($result != false && $clveicmanut->numrows > 0) {

    db_fieldsmemory($result, 0);

    $result_oficina = $clveicmanutoficina->sql_record($clveicmanutoficina->sql_query(null, "*", null, " ve66_veicmanut = {$chavepesquisa} "));
    if ($result_oficina != false && $clveicmanutoficina->numrows > 0) {
      db_fieldsmemory($result_oficina, 0);
    }

    $result_retirada = $clveicmanutretirada->sql_record($clveicmanutretirada->sql_query(null, "*", null, " ve65_veicmanut = {$ve62_codigo} "));
    if ($result_retirada != false && $clveicmanutretirada->numrows > 0) {
      db_fieldsmemory($result_retirada, 0);
    }

    $result = $clveiculos->sql_record($clveiculos->sql_query($ve62_veiculos, "ve01_veictipoabast"));
    if ($result != false && $clveiculos->numrows > 0) {
      db_fieldsmemory($result, 0);
    }

    $result_veictipoabast = $clveictipoabast->sql_record($clveictipoabast->sql_query($ve01_veictipoabast, "ve07_sigla"));
    if ($result_veictipoabast != false && $clveictipoabast->numrows > 0) {
      db_fieldsmemory($result_veictipoabast, 0);
    }
  } else {

    $erro_msg = "A Manutenção de Veículo informada é inválida.";
    $alterar  = false;
    $db_botao = false;
    $db_opcao = 22;
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
  <body class="body-default">
  	<?php include modification("forms/db_frmveicmanut.php"); ?>
  </body>
</html>
<?php
if(isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clveicmanut->erro_campo!=""){
      echo "<script> document.form1.".$clveicmanut->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clveicmanut->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
  }
}
if(isset($chavepesquisa) && $chavepesquisa != "0"){
 echo "
  <script>

      js_pesquisa_medida();

      function js_db_libera(){
         parent.document.formaba.veicmanutitem.disabled=false;
         top.corpo.iframe_veicmanutitem.location.href='vei1_veicmanutitem001.php?ve63_veicmanut=".@$ve62_codigo."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('veicmanutitem');";
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
