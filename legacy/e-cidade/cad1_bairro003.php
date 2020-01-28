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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("classes/db_bairro_classe.php");
require_once modification("dbforms/db_funcoes.php");
parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);
$clbairro = new cl_bairro;
$db_botao   = false;
$db_opcao   = 33;

$oDaoCgmBairro               = new cl_db_cgmbairro;
$oDaoAguaBase                = new cl_aguabase;
$oDaoAguacoletorexportadados = new cl_aguacoletorexportadados;
$oDaoAguacorresp             = new cl_aguacorresp;
$oDaoAlunobairro             = new cl_alunobairro;
$oDaoAutoexec                = new cl_autoexec;
$oDaoAutolocal               = new cl_autolocal;
$oDaodb_departender          = new cl_db_departender;
$oDaoEmpreendimento          = new cl_empreendimento;
$oDaoEscola                  = new cl_escola;
$oDaoFiscalocal              = new cl_fiscalocal;
$oDaoFiscexec                = new cl_fiscexec;
$oDaoIssbairro               = new cl_issbairro;
$oDaoIsssimulacalculo        = new cl_isssimulacalculo;
$oDaoLiclocal                = new cl_liclocal;
$oDaoLote                    = new cl_lote;
$oDaoObrasender              = new cl_obrasender;
$oDaoRuasbairro              = new cl_ruasbairro;
$oDaoSanitario               = new cl_sanitario;
$oDaoViabilidade             = new cl_viabilidade;
$oDaoVistexec                = new cl_vistexec;
$oDaoVistlocal               = new cl_vistlocal;

$sMsgErro = "tributario.cadastro.cad1_bairro003.";
if (isset($_POST["db_opcao"]) && $_POST["db_opcao"]=="Excluir") {

  db_inicio_transacao();

  try {

    $oDaoCgmBairro->excluir(null, "j13_codi = {$j13_codi}");

    if ( $oDaoCgmBairro->erro_status == 0 ) {
      throw new Exception($oDaoCgmBairro->erro_msg);
    }

    $rs = db_query($oDaoAguaBase->sql_query_file(null, '1',null, "x01_codbairro = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception( "Tributário > Águal > Cadastro de Imóveis/Terrenos > Alteração" );
    }
    $rs = db_query($oDaoAguacorresp->sql_query_file(null, '1',null, "x02_codbairro = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Tributário > Águal > Cadastro de Imóveis/Terrenos > Alteração | Aba Entrega");
    }
    $rs = db_query($oDaoAlunobairro->sql_query_file(null, '1',null, "ed225_i_bairro = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Educação > Escola > Cadastro > Aluno > Alteração");
    }
    $rs = db_query($oDaoAutoexec->sql_query_file(null, '1',null, "y15_codi = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Tributário > Fiscal > Procedimentos > Auto de Infração > Alteração ( Endereço registrado ) ");
    }
    $rs = db_query($oDaoAutolocal->sql_query_file(null, '1',null, "y14_codi = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Tributário > Fiscal > Procedimentos > Auto de Infração > Alteração ( Endereço localizado ) ");
    }
    $rs = db_query($oDaodb_departender->sql_query_file(null, '1',null, "codbairro = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Configuração > Configuração > Cadastro > Cadastro de Departamentos > Alteração | Aba Endereço");
    }
    $rs = db_query($oDaoEmpreendimento->sql_query_file(null, '1',null, "am05_bairro = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Tributário > Meio Ambiente > Cadastro > Empreendimento > Alteração");
    }
    $rs = db_query($oDaoEscola->sql_query_file(null, '1',null, "ed18_i_bairro = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Educação > Escola > Cadastro > Dados da Escola | Aba Geral");
    }
    $rs = db_query($oDaoIssbairro->sql_query_file(null, '1',null, "q13_bairro = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Tributário > ISSQN > Cadastro > Alvará > Alteração | Aba Inscrição");
    }
    $rs = db_query($oDaoIsssimulacalculo->sql_query_file(null, '1',null, "q130_bairro = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Tributário > ISSQN > Cadastro > Alvará > Alteração | Aba Inscrição");
    }
    $rs = db_query($oDaoLiclocal->sql_query_file(null, '1',null, "l26_bairro = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Patrimonial > Cadastro > Cadastro de Locais > Alteração");
    }
    $rs = db_query($oDaoLote->sql_query_file(null, '1',null, "j34_bairro = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Tributário > Cadastro > Procedimentos > Manutenção de Imóveis > Alteração | Aba Lote");
    }
    $rs = db_query($oDaoObrasender->sql_query_file(null, '1',null, "ob07_bairro = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Tributário > Projetos > Cadastro > Obras  | Aba Construção");
    }
    $rs = db_query($oDaoRuasbairro->sql_query_file(null, '1',null, "j16_bairro = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Tributário > Cadastro > Logradouro > Alteração | Aba Bairro");
    }
    $rs = db_query($oDaoSanitario->sql_query_file(null, '1',null, "y80_codbairro = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Tributário > Fiscal > Procedimentos > Alvará Sanitário > Alteração | Aba Sanitário");
    }
    $rs = db_query($oDaoViabilidade->sql_query_file(null, '1',null, "q29_bairro = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception();
    }
    $rs = db_query($oDaoVistexec->sql_query_file(null, '1',null, "y11_codi = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Tributário > Fiscal > Procedimentos > Vistorias > Alteração | Aba Vistorias");
    }
    $rs = db_query($oDaoVistlocal->sql_query_file(null, '1',null, "y10_codi = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Tributário > Fiscal > Procedimentos > Vistorias > Alteração | Aba Vistorias");
    }
    $rs = db_query($oDaoFiscexec->sql_query_file(null, '1',null, "y13_codi = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Tributário > Fiscal > Procedimentos > Notificações > Alteração | Endereço localizado ");
    }
    $rs = db_query($oDaoFiscalocal->sql_query_file(null, '1',null, "y12_codi = {$j13_codi}"));
    if ($rs && pg_num_rows($rs) > 0) {
      throw new Exception("Tributário > Fiscal > Procedimentos > Notificações > Alteração | Endereço registrado ");
    }

    $db_opcao = 3;
    $clbairro->excluir($j13_codi);
    db_fim_transacao();

  } catch( Exception $e ) {

    $oMsg = new \stdClass();
    $oMsg->sMenu = $e->getMessage();

    $clbairro->erro_msg    = _M( $sMsgErro . "bairro_vinculado", $oMsg);
    $clbairro->erro_status = 0;
    db_fim_transacao(true);
  }

} else if (isset($chavepesquisa)) {

   $db_opcao = 3;
   $result   = $clbairro->sql_record($clbairro->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
   $db_botao = true;
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?php
	include(modification("forms/db_frmbairro.php"));
	?>
    </center>
	</td>
  </tr>
</table>
<?php
db_menu();
?>
</body>
</html>
<?php
if(isset($_POST["db_opcao"]) && $_POST["db_opcao"]=="Excluir"){
  if($clbairro->erro_status=="0"){
    $clbairro->erro(true,false);
  }else{
    $clbairro->erro(true,true);
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>