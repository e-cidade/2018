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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clrhpessoal   = new cl_rhpessoal;
$clpessoal     = new cl_pessoal;
$clgerfsal     = new cl_gerfsal;
$clgerfres     = new cl_gerfres;
$clgerfs13     = new cl_gerfs13;
$clgerfcom     = new cl_gerfcom;
$clrhrubricas  = new cl_rhrubricas;
$cllotacao     = new cl_lotacao;
$db_opcao      = 1;
$db_botao      = true;

// Se variáveis anouso e mesusu não existem, ele pegará as variáveis atuais da folha
if(!isset($r14_anousu) || (isset($r14_anousu) && trim($r14_anousu) == "")){
  $r14_anousu = db_anofolha();
}
if(!isset($r14_mesusu) || (isset($r14_mesusu) && trim($r14_mesusu) == "")){
  $r14_mesusu = db_mesfolha();
}
////////////
$aFolhasComNovaEstrutura = array("fs", "supl","com");
if ( DBPessoal::verificarUtilizacaoEstruturaSuplementar() && in_array($gerf, $aFolhasComNovaEstrutura) ) {
  require_once("pes4_implantacaoponto001.php");
  exit;
}

if(isset($incluir) || isset($confirmado) || isset($gerfnovo)){

  $gerfteste = 0;
  if($gerf == "fs" || $gerf == "Rfs"){

    // Rotina que verifica se já existe, na tabela gerfsal, algum registro com o mesmo anousu, mesusu e rubrica
    $result_folhasalario = $clgerfsal->sql_record($clgerfsal->sql_query_seleciona(null,null,null,null,"r14_valor as valoranterior,r14_quant as quantidadeanterior",null,"r14_anousu = $r14_anousu and r14_mesusu = $r14_mesusu and r14_regist = $r14_regist and r14_rubric = $r14_rubric and r14_instit = ".db_getsession("DB_instit")));
    if($clgerfsal->numrows > 0){
      db_fieldsmemory($result_folhasalario,0);
      $gerfteste = 1;
    }
    //////////

  }else if($gerf == "fr" || $gerf == "Rfr"){

    // Rotina que verifica se já existe, na tabela gerfres, algum registro com o mesmo anousu, mesusu, rubrica e tipo
//    echo ($clgerfres->sql_query_seleciona($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric,$r20_tpp,"r20_valor as valoranterior,r20_quant as quantidadeanterior"));
    $result_folharescisao= $clgerfres->sql_record($clgerfres->sql_query_seleciona(null,null,null,null,null,"r20_valor as valoranterior,r20_quant as quantidadeanterior",null,"r20_anousu = $r14_anousu and r20_mesusu = $r14_mesusu and r20_regist = $r14_regist and r20_rubric = $r14_rubric and r20_tpp = $r20_tpp and r20_instit = ".db_getsession("DB_instit")));
    if($clgerfres->numrows > 0){
      db_fieldsmemory($result_folharescisao,0,1,1);
      $gerfteste = 1;
    }
    //////////

  }else if($gerf == "f13" || $gerf == "Rf13"){

    // Rotina que verifica se já existe, na tabela gerfs13, algum registro com o mesmo anousu, mesusu e rubrica
    $result_folhadecimo = $clgerfs13->sql_record($clgerfs13->sql_query_seleciona(null,null,null,null,"r35_valor as valoranterior,r35_quant as quantidadeanterior",null,"r35_anousu = $r14_anousu and r35_mesusu = $r14_mesusu and r35_regist = $r14_regist and r35_rubric = $r14_rubric and r35_instit = ".db_getsession("DB_instit")));
    if($clgerfs13->numrows > 0){
      db_fieldsmemory($result_folhadecimo,0);
      $gerfteste = 1;
    }
    //////////

  }else if($gerf == "com" || $gerf == "Rcom"){

    // Rotina que verifica se já existe, na tabela gerfcom, algum registro com o mesmo anousu, mesusu e rubrica
    $result_gerfcomplementar = $clgerfcom->sql_record($clgerfcom->sql_query_seleciona(null,null,null,null,"r48_valor as valoranterior,r48_quant as quantidadeanterior",null,"r48_anousu = $r14_anousu and r48_mesusu = $r14_mesusu and r48_regist = $r14_regist and r48_rubric = $r14_rubric and r48_instit = ".db_getsession("DB_instit")));
    if($clgerfcom->numrows > 0){
      db_fieldsmemory($result_gerfcomplementar,0);
      $gerfteste = 1;
    }
    //////////

  }

  $ok = false;

  if($gerfteste > 0){
    if(!isset($confirmado)){
      $alertconfirma = true;
      $db_opcao = 22;
    }else if(isset($confirmado) && $confirmado == "true"){
      $ok = true;
      $r14_valor += $valoranterior;
      $r14_quant += $quantidadeanterior;
      $alterar = "alterar";
      unset($incluir);
    }else{
      $alterar = "alterar";
      unset($incluir);
    }
  }

}

if(isset($incluir) && !isset($alertconfirma)){
  db_inicio_transacao();
  $sqlerro = false;

  $valor_em_branco = false;
  $quant_em_branco = false;

  if(trim($r14_quant) == ""){
    $r14_quant = "0";
    $quant_em_branco = true;
  }
  if(trim($r14_valor) == ""){
    $r14_valor = "0";
    $valor_em_branco = true;
  }

  $result_verifica_rubrica_com_formula = $clrhrubricas->sql_record($clrhrubricas->sql_query_file(null,db_getsession('DB_instit'),"rh27_form as formula_testa, rh27_propq as proporcionalizar,rh27_pd","","rh27_rubric = '".$r14_rubric."'"));
  if($clrhrubricas->numrows > 0){
    db_fieldsmemory($result_verifica_rubrica_com_formula,0);
  }else{
    $sqlerro = true;
    $erro_msg = "Rubrica ".$r14_rubric." não encontrada. Verifique.";
    $r14_rubric = "";
    $rh27_descr = "";
  }

  // Se for folha de salário
  if($sqlerro == false && ($gerf == "fs" || $gerf == "Rfs")){

    $clgerfsal->r14_anousu = $r14_anousu;
    $clgerfsal->r14_mesusu = $r14_mesusu;
    $clgerfsal->r14_regist = $r14_regist;
    $clgerfsal->r14_rubric = $r14_rubric;
    $clgerfsal->r14_valor  = "$r14_valor";
    $clgerfsal->r14_pd     = $r14_pd;
    $clgerfsal->r14_quant  = "$r14_quant";
    $clgerfsal->r14_lotac  = $r14_lotac;
    $clgerfsal->r14_semest = "0";
    $clgerfsal->r14_instit = db_getsession("DB_instit");
    $clgerfsal->incluir($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric);
    $erro_msg = $clgerfsal->erro_msg;
    $repassa = true;
    if($clgerfsal->erro_status==0){
      $sqlerro=true;
      unset($repassa);
    }
  //////////

  // Se for folha de rescisão
  }else if($sqlerro == false && ($gerf == "fr" || $gerf == "Rfr")){

    $clgerfres->r20_anousu = $r14_anousu;
    $clgerfres->r20_mesusu = $r14_mesusu;
    $clgerfres->r20_regist = $r14_regist;
    $clgerfres->r20_valor  = "$r14_valor";
    $clgerfres->r20_pd     = $rh27_pd;
    $clgerfres->r20_quant  = "$r14_quant";
    $clgerfres->r20_lotac  = $r14_lotac;
    $clgerfres->r20_rubric = $r14_rubric;
    $clgerfres->r20_semest = "0";
    $clgerfres->r20_tpp    = $r20_tpp;
    $clgerfres->r20_instit = db_getsession("DB_instit");
    $clgerfres->incluir($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric,$r20_tpp);
    $erro_msg = $clgerfres->erro_msg;
    if($clgerfres->erro_status==0){
      $sqlerro=true;
    }
  //////////

  // Se for folha de 13o terceiro
  }else if($sqlerro == false && ($gerf == "f13" || $gerf == "Rf13")){

    $clgerfs13->r35_anousu = $r14_anousu;
    $clgerfs13->r35_mesusu = $r14_mesusu;
    $clgerfs13->r35_regist = $r14_regist;
    $clgerfs13->r35_rubric = $r14_rubric;
    $clgerfs13->r35_valor  = "$r14_valor";
    $clgerfs13->r35_pd     = $rh27_pd;
    $clgerfs13->r35_quant  = "$r14_quant";
    $clgerfs13->r35_lotac  = $r14_lotac;
    $clgerfs13->r35_semest = "0";
    $clgerfs13->r35_instit = db_getsession("DB_instit");
    $clgerfs13->incluir($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric);
    $erro_msg = $clgerfs13->erro_msg;
    if($clgerfs13->erro_status==0){
      $sqlerro=true;
    }
  ////////

  // Se for folha complementar
  }else if($sqlerro == false && ($gerf == "com" || $gerf == "Rcom")){

    $clgerfcom->r48_anousu = $r14_anousu;
    $clgerfcom->r48_mesusu = $r14_mesusu;
    $clgerfcom->r48_regist = $r14_regist;
    $clgerfcom->r48_rubric = $r14_rubric;
    $clgerfcom->r48_valor  = "$r14_valor";
    $clgerfcom->r48_pd     = $r14_pd;
    $clgerfcom->r48_quant  = "$r14_quant";
    $clgerfcom->r48_lotac  = $r14_lotac;
    $clgerfcom->r48_semest = "0";
    $clgerfcom->r48_instit = db_getsession("DB_instit");
    $clgerfcom->incluir($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric);
    $erro_msg = $clgerfcom->erro_msg;
    if($clgerfcom->erro_status==0){
      $sqlerro=true;
    }
  //////////

  }

  db_fim_transacao($sqlerro);
}else if(isset($alterar)){
  db_inicio_transacao();
  $sqlerro = false;

  $valor_em_branco = false;
  $quant_em_branco = false;

  if(trim($r14_quant) == ""){
    $r14_quant = "0";
    $quant_em_branco = true;
  }
  if(trim($r14_valor) == ""){
    $r14_valor = "0";
    $valor_em_branco = true;
  }

  $result_verifica_rubrica_com_formula = $clrhrubricas->sql_record($clrhrubricas->sql_query_file(null,db_getsession('DB_instit'),"rh27_form as formula_testa, rh27_propq as proporcionalizar,rh27_pd","","rh27_rubric = '".$r14_rubric."'"));
  if($clrhrubricas->numrows > 0){
    db_fieldsmemory($result_verifica_rubrica_com_formula,0);
  }else{
    $sqlerro = true;
    $erro_msg = "Rubrica ".$r14_rubric." não encontrada. Verifique.";
    $r14_rubric = "";
    $rh27_descr = "";
  }

  // Se for folha de salário
  if($sqlerro == false && ($gerf == "fs" || $gerf == "Rfs")){

    $clgerfsal->r14_anousu = $r14_anousu;
    $clgerfsal->r14_mesusu = $r14_mesusu;
    $clgerfsal->r14_regist = $r14_regist;
    $clgerfsal->r14_rubric = $r14_rubric;
    $clgerfsal->r14_valor  = "$r14_valor";
    $clgerfsal->r14_pd     = $r14_pd;
    $clgerfsal->r14_quant  = "$r14_quant";
    $clgerfsal->r14_lotac  = $r14_lotac;
    $clgerfsal->r14_instit = db_getsession("DB_instit");
    $clgerfsal->alterar($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric);
    $erro_msg = $clgerfsal->erro_msg;
    $repassa = true;
    if($clgerfsal->erro_status==0){
      $sqlerro=true;
      unset($repassa);
    }
  //////////

  // Se for folha de rescisão
  }else if($sqlerro == false && ($gerf == "fr" || $gerf == "Rfr")){

    $clgerfres->excluir($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric,$r20_tpp);
    $erro_msg = $clgerfres->erro_msg;
    if($clgerfres->erro_status==0){
      $sqlerro=true;
    }else{
      $r20_tpp = "P";
      $clgerfres->r20_anousu = $r14_anousu;
      $clgerfres->r20_mesusu = $r14_mesusu;
      $clgerfres->r20_regist = $r14_regist;
      $clgerfres->r20_valor  = "$r14_valor";
      $clgerfres->r20_pd     = $rh27_pd;
      $clgerfres->r20_quant  = "$r14_quant";
      $clgerfres->r20_lotac  = $r14_lotac;
      $clgerfres->r20_rubric = $r14_rubric;
      $clgerfres->r20_semest = "0";
      $clgerfres->r20_tpp    = $r20_tpp;
      $clgerfres->r20_instit = db_getsession("DB_instit");
      $clgerfres->incluir($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric,$r20_tpp);
      $erro_msg = $clgerfres->erro_msg;
      if($clgerfres->erro_status==0){
        $sqlerro=true;
      }
    }

    $erro_msg = str_replace("Inclusao","Alteracao",$erro_msg);
    $erro_msg = str_replace("Exclusao","Alteracao",$erro_msg);
  //////////

  // Se for folha de 13o
  }else if($sqlerro == false && ($gerf == "f13" || $gerf == "Rf13")){

    $clgerfs13->r35_anousu = $r14_anousu;
    $clgerfs13->r35_mesusu = $r14_mesusu;
    $clgerfs13->r35_regist = $r14_regist;
    $clgerfs13->r35_rubric = $r14_rubric;
    $clgerfs13->r35_valor  = "$r14_valor";
    $clgerfs13->r35_pd     = $rh27_pd;
    $clgerfs13->r35_quant  = "$r14_quant";
    $clgerfs13->r35_lotac  = $r14_lotac;
    $clgerfs13->r35_instit = db_getsession("DB_instit");
    $clgerfs13->alterar($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric);
    $erro_msg = $clgerfs13->erro_msg;
    if($clgerfs13->erro_status==0){
      $sqlerro=true;
    }
  //////////

  // Se for folha complementar
  }else if($sqlerro == false && ($gerf == "com" || $gerf == "Rcom")){
    $clgerfcom->r48_anousu = $r14_anousu;
    $clgerfcom->r48_mesusu = $r14_mesusu;
    $clgerfcom->r48_regist = $r14_regist;
    $clgerfcom->r48_rubric = $r14_rubric;
    $clgerfcom->r48_valor  = "$r14_valor";
    $clgerfcom->r48_pd     = $r14_pd;
    $clgerfcom->r48_quant  = "$r14_quant";
    $clgerfcom->r48_lotac  = $r14_lotac;
    $clgerfcom->r48_semest = "0";
    $clgerfcom->r48_instit = db_getsession("DB_instit");
    $clgerfcom->alterar($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric);
    $erro_msg = $clgerfcom->erro_msg;
    if($clgerfcom->erro_status==0){
      $sqlerro=true;
    }
  //////////

  }

  if(isset($ok) && $ok == true){

    $r14_valor -= $valoranterior;
    $r14_quant -= $quantidadeanterior;

  }

  db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  db_inicio_transacao();
  $sqlerro = false;

  // Se for folha de salário
  if($gerf == "fs" || $gerf == "Rfs"){

    $clgerfsal->excluir($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric);
    $erro_msg = $clgerfsal->erro_msg;
    if($clgerfsal->erro_status==0){
      $sqlerro=true;
    }
  //////////

  // Se for folha de rescisão
  }else if($gerf == "fr" || $gerf == "Rfr"){
    $clgerfres->excluir($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric,$r20_tpp);
    $erro_msg = $clgerfres->erro_msg;
    if($clgerfres->erro_status==0){
      $sqlerro=true;
    }
  //////////

  // Se for folha de 13o
  }else if($gerf == "f13" || $gerf == "Rf13"){
    $clgerfs13->excluir($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric);
    $erro_msg = $clgerfs13->erro_msg;
    if($clgerfs13->erro_status==0){
      $sqlerro=true;
    }
  //////////

  // Se for folha complementar
  }else if($gerf == "com" || $gerf == "Rcom"){
    $clgerfcom->excluir($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric);
    $erro_msg = $clgerfcom->erro_msg;
    if($clgerfcom->erro_status==0){
      $sqlerro=true;
    }
  //////////

  }

  db_fim_transacao($sqlerro);
}else if(isset($opcao)){

  if($opcao == "alterar"){
    $db_opcao = 2;
  }else if($opcao == "excluir"){
    $db_opcao = 3;
  }

  ///////////////////////////////////////////////////
  // Rotina para buscar os dados da folha selecionado
  ///////////////////////////////////////////////////

  $campoextra = "";
  $whereextra = "";
  if($gerf == "fs" || $gerf == "Rfs"){
    $sigla = "r14_";
  }else if($gerf == "fr" || $gerf == "Rfr"){
    $sigla = "r20_";
    $campoextra = ", r20_tpp as r20_tpp";
    $whereextra = " and r20_tpp = '$r20_tpp' ";
  }else if($gerf == "f13" || $gerf == "Rf13"){
    $sigla = "r35_";
  }else if($gerf == "com" || $gerf == "Rcom"){
    $sigla = "r48_";
  }

  $dbwhere = $sigla."regist = $r14_regist and ".$sigla."anousu = $r14_anousu and ".$sigla."mesusu = $r14_mesusu and ".$sigla."rubric = '$r14_rubric' $whereextra and ".$sigla."instit=".db_getsession("DB_instit");
  $campos  = "rh01_regist as r14_regist,z01_nome,rh27_rubric as r14_rubric,rh27_form,rh27_descr ".$campoextra.",r70_codigo as r14_lotac,r70_descr,".$sigla."quant as r14_quant,".$sigla."valor as r14_valor,".$sigla."pd as r14_pd";

  // Se for folha de salário
  if($gerf == "fs" || $gerf == "Rfs"){
    $result_folhasalario = $clgerfsal->sql_record($clgerfsal->sql_query_seleciona(null,null,null,null,$campos,"",$dbwhere));
    if($clgerfsal->numrows > 0){
      db_fieldsmemory($result_folhasalario,0);
    }
  //////////

  // Se for folha de rescisão
  }else if($gerf == "fr" || $gerf == "Rfr"){
    $result_folharecisao = $clgerfres->sql_record($clgerfres->sql_query_seleciona(null,null,null,null,null,$campos,"",$dbwhere));
    if($clgerfres->numrows > 0){
      db_fieldsmemory($result_folharecisao,0);
    }
  //////////

  // Se for folha de 13o
  }else if($gerf == "f13" || $gerf == "Rf13"){
    $result_folhadecimo = $clgerfs13->sql_record($clgerfs13->sql_query_seleciona(null,null,null,null,$campos,"",$dbwhere));
    if($clgerfs13->numrows > 0){
      db_fieldsmemory($result_folhadecimo,0);
    }
  //////////

  // Se for folha complementar
  }else if($gerf == "com" || $gerf == "Rcom"){
    $result_gerfcomplementar = $clgerfcom->sql_record($clgerfcom->sql_query_seleciona(null,null,null,null,$campos,"",$dbwhere));
    if($clgerfcom->numrows > 0){
      db_fieldsmemory($result_gerfcomplementar,0);
    }
  }
  //////////

  if(isset($rh27_form)){
    if(trim($rh27_form) != ""){
      $rh27_form = "t";
    }else{
      $rh27_form = "f";
    }
  }

  ///////////////////////////////////////////////////
  ///////////////////////////////////////////////////
}else if(isset($r14_regist)){

  // Rotina para buscar os dados da matrícula
  $dbwhere = " rh01_regist = $r14_regist ";
  $result_registro = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm(null,"rh01_regist as r14_regist,rh01_admiss as data_de_admissao,z01_nome,rh02_lota as r14_lotac,r70_descr","",$dbwhere));
  if($clrhpessoal->numrows > 0){
    db_fieldsmemory($result_registro,0);
  }
  ////////////

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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="25%" height="18">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<?
include("forms/db_frmgerffx.php");
?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>js_campos('outros');</script>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if(isset($sqlerro) && $sqlerro == true){
    db_msgbox($erro_msg);
  }
  if(!isset($alertconfirma)){
    echo "
          <script>
            document.form1.r14_rubric.value = '';
            document.form1.r14_valor.value  = '0';
            document.form1.r14_quant.value  = '0';
            document.form1.rh27_descr.value = '';
          </script>
         ";
  }
};
if((isset($sqlerro) && $sqlerro == false) || !isset($sqlerro)){
  if(isset($alertconfirma)){
    // Pergunta se o usuário quer ou não somar o valor e quantidade informados com os já existentes para a matrícula
    echo "
          <script>
            confirmado = 'false';
            if(confirm('Usuário:\\n\\nRubrica $r14_rubric ($rh27_descr) já cadastrada para a matrícula $r14_regist ($z01_nome).\\n\\nSomar com valor e quantidade informados? \\n\\nOK para somar e CANCEL para substituir valores.')){
              confirmado = 'true';
            }
            obj = document.createElement('input');
            obj.setAttribute('name','confirmado');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value',confirmado);
            document.form1.appendChild(obj);
            document.form1.submit();
          </script>
         ";
  }
}
?>
