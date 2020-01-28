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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));
db_postmemory($_POST);
$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$oDaoExemplar         = new cl_exemplar;
$oDaoEmprestimoAcervo = new cl_emprestimoacervo;
$oDaoItemExemplar     = new cl_impexemplaritem;
$oDaoLocalExemplar    = new cl_localexemplar;

require_once(modification("classes/db_baixabib_classe.php"));
$oDaoBaixa            = new cl_baixabib;

$db_opcao = 1;
$db_botao = true;
if ( isset($incluir) ) {

  $oDaoExemplar->pagina_retorno = "bib1_exemplar001.php?bi23_acervo=$bi23_acervo&bi06_titulo=$bi06_titulo";
  db_inicio_transacao();

  for ($i = 0; $i < $oPost->quantidade; $i++) {

    $oDaoExemplar->bi23_situacao  = 'S';
    $oDaoExemplar->bi23_exemplar  = $oPost->bi23_exemplar;
    $oDaoExemplar->bi23_codbarras = $oPost->bi23_codbarras;
    $oDaoExemplar->incluir(null);

    $oPost->bi23_codbarras ++;
    $oPost->bi23_exemplar ++;
  }
  db_fim_transacao();
}
if (isset($alterar)) {

  $db_opcao = 2;
  $oDaoExemplar->pagina_retorno = "bib1_exemplar001.php?bi23_acervo=$bi23_acervo&bi06_titulo=$bi06_titulo";
  db_inicio_transacao();
  $oDaoExemplar->alterar($bi23_codigo);
  db_fim_transacao();
}
if (isset($excluir)) {

  $db_opcao = 3;
  $oDaoExemplar->pagina_retorno = "bib1_exemplar001.php?bi23_acervo=$bi23_acervo&bi06_titulo=$bi06_titulo";
  $result  = $oDaoBaixa->sql_record($oDaoBaixa->sql_query_file("","*",""," bi08_exemplar = $bi23_codigo"));
  $result1 = $oDaoEmprestimoAcervo->sql_record($oDaoEmprestimoAcervo->sql_query_file("","*",""," bi19_exemplar = $bi23_codigo"));
  if ($oDaoBaixa->numrows > 0) {

    $oDaoExemplar->erro_status = "0";
    $oDaoExemplar->erro_msg = "Exemplar $bi23_codbarras não pode ser excluído, pois contém registro de baixa.";
  } elseif ($oDaoEmprestimoAcervo->numrows > 0) {

    $oDaoExemplar->erro_status = "0";
    $oDaoExemplar->erro_msg = "Exemplar $bi23_codbarras não pode ser excluído, pois contém registro de empréstimo.";
  } else {

    db_inicio_transacao();

    $lErro = false;

    $oDaoBaixa->excluir("","bi08_exemplar = $bi23_codigo");

    if ($oDaoBaixa->erro_status == 0) {
      $lErro = true;
    }

    if ( !$lErro ) {

      $oDaoEmprestimoAcervo->excluir("","bi19_exemplar = $bi23_codigo");
      if ($oDaoEmprestimoAcervo->erro_status == 0) {
        $lErro = true;
      }
    }

    if ( !$lErro ) {

      $oDaoItemExemplar->excluir("","bi25_exemplar = $bi23_codigo");
      if ($oDaoItemExemplar->erro_status == 0) {
        $lErro = true;
      }
    }
    if ( !$lErro ) {

      $oDaoLocalExemplar->excluir("","bi27_exemplar = $bi23_codigo");
      if ($oDaoLocalExemplar->erro_status == 0) {
        $lErro = true;
      }
    }

    if ( !$lErro ) {

      $oDaoExemplar->excluir($bi23_codigo);
      if ($oDaoExemplar->erro_status == 0) {
        $lErro = true;
      }
    }
    db_fim_transacao();
  }
}

if ( empty($oPost->bi23_exemplar) ) {
  $bi23_exemplar = $oDaoExemplar->buscarProximoExemplar($oGet->bi23_acervo);
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
<body  >

  <div class="container">
    <?php include(modification("forms/db_frmexemplar.php"));?>
  </div>

</body>
</html>
<script>
js_tabulacaoforms("form1","bi23_dataaquisicao_dia",true,1,"bi23_dataaquisicao_dia",true);
</script>
<?php
if (isset($incluir)) {

  if ($oDaoExemplar->erro_status=="0") {

    $oDaoExemplar->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($oDaoExemplar->erro_campo != "") {

     echo "<script> document.form1.".$oDaoExemplar->erro_campo.".style.backgroundColor='#99A9AE';</script>";
     echo "<script> document.form1.".$oDaoExemplar->erro_campo.".focus();</script>";
    }
  } else {

   ?>
   <script>
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acervo5.location.href='bib1_localacervo001.php?bi20_acervo=<?=$bi23_acervo?>&bi06_titulo=<?=$bi06_titulo?>';
   </script>
   <?php
   $oDaoExemplar->erro(true,true);
  }
}
if (isset($alterar)) {

  if($oDaoExemplar->erro_status=="0") {

    $oDaoExemplar->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($oDaoExemplar->erro_campo!="") {
      echo "<script> document.form1.".$oDaoExemplar->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoExemplar->erro_campo.".focus();</script>";
    }
  } else {
    ?>
    <script>
     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acervo5.location.href='bib1_localacervo001.php?bi20_acervo=<?=$bi23_acervo?>&bi06_titulo=<?=$bi06_titulo?>';
    </script>
    <?
    $oDaoExemplar->erro(true,true);
  }
}
if (isset($excluir)) {

  if ($oDaoExemplar->erro_status=="0") {
    $oDaoExemplar->erro(true,false);
  } else {
   ?>
   <script>
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acervo5.location.href='bib1_localacervo001.php?bi20_acervo=<?=$bi23_acervo?>&bi06_titulo=<?=$bi06_titulo?>';
   </script>
   <?
   $oDaoExemplar->erro(true,true);
  }
}
if (isset($cancelar)) {

  $oDaoExemplar->pagina_retorno = "bib1_exemplar001.php?bi23_acervo=$bi23_acervo&bi06_titulo=$bi06_titulo";
  echo "<script>location.href='".$oDaoExemplar->pagina_retorno."'</script>";
}
?>