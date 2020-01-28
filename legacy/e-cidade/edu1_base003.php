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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$ed31_c_ativo = '';
parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

$iModulo = db_getsession('DB_modulo');
$iEscola = db_getsession('DB_coddepto');

$clbase             = new cl_base;
$clbasemps          = new cl_basemps;
$clbasediscglob     = new cl_basediscglob;
$clbaseserie        = new cl_baseserie;
$clescolabase       = new cl_escolabase;
$clbaseregimematdiv = new cl_baseregimematdiv;
$clbaseato          = new cl_baseato;
$clbaseatoserie     = new cl_baseatoserie;
$clturma            = new cl_turma;
$clalunocurso       = new cl_alunocurso;
$clatestvaga        = new cl_atestvaga;

$db_botao     = false;
$db_opcao     = 33;
$db_opcao1    = 3;

if( !isset( $excluir ) && isset( $chavepesquisa ) ) {

  $db_opcao  = 3;
  $db_opcao1 = 3;

  $campos = "base.*,
             baseserie.*,
             basediscglob.*,
             regimemat.*,
             si.ed11_c_descr as ed11_c_descrini,
             sf.ed11_c_descr as ed11_c_descrfim,
             disciplina.*,
             cursoedu.*,
             ensino.*";
  $result = $clbase->sql_record( $clbase->sql_query_base2( "", $campos, "", "ed31_i_codigo = {$chavepesquisa}" ) );

  echo pg_errormessage();
  db_fieldsmemory( $result, 0 );
  $db_botao = true;
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC">
  <?php
  require_once(modification( "forms/db_frmbase.php" ));
  ?>
</body>
</html>
<?php

if( isset( $excluir ) ) {

  $sSqlTurma = $clturma->sql_query_file( "", "ed57_i_codigo", "", "ed57_i_base = {$ed31_i_codigo}");
  $result11  = $clturma->sql_record($sSqlTurma  );

  $sSqlAlunoCurso = $clalunocurso->sql_query_file( "", "ed56_i_codigo", "", "ed56_i_base = {$ed31_i_codigo}");
  $result12       = $clalunocurso->sql_record( $sSqlAlunoCurso );

  $sSqlAtestVaga = $clatestvaga->sql_query_file( "", "ed102_i_codigo", "", "ed102_i_base = {$ed31_i_codigo}");
  $result13      = $clatestvaga->sql_record( $sSqlAtestVaga );

  if( $clturma->numrows > 0 ) {

    $clbase->erro_status = "0";
    $clbase->erro_msg    = "Exclusão Não Permitida. Base já está vinculada em Turma(s).\\nMude a opção Ativa para NÃO! ";
  } else if( $clalunocurso->numrows > 0 ) {

    $clbase->erro_status = "0";
    $clbase->erro_msg    = "Exclusão Não Permitida. Base já está vinculada em Aluno(s).\\nMude a opção Ativa para NÃO! ";
  } else if( $clatestvaga->numrows > 0 ) {

    $clbase->erro_status = "0";
    $clbase->erro_msg    = "Exclusão Não Permitida. Base já está vinculada em Atestado(s).\\nMude a opção Ativa para NÃO! ";
  } else {

    db_inicio_transacao();

    $db_opcao = 3;

    $clbasemps->excluir( "", "ed34_i_base = {$ed31_i_codigo}" );
    $clbaseserie->excluir( "", "ed87_i_codigo = {$ed31_i_codigo}" );

    $sWhereBaseAtoSerie  = "ed279_i_baseato in (select ed278_i_codigo ";
    $sWhereBaseAtoSerie .= "                      from baseato ";
    $sWhereBaseAtoSerie .= "                     where ed278_i_escolabase in (select ed77_i_codigo ";
    $sWhereBaseAtoSerie .= "                                                    from escolabase ";
    $sWhereBaseAtoSerie .= "                                                   where ed77_i_base = {$ed31_i_codigo} ";
    $sWhereBaseAtoSerie .= "                                                  ) ";
    $sWhereBaseAtoSerie .= "                   )";
    $clbaseatoserie->excluir( "", $sWhereBaseAtoSerie );

    $sWhereBaseAto = "ed278_i_escolabase in ( select ed77_i_codigo from escolabase where ed77_i_base = {$ed31_i_codigo} )";
    $clbaseato->excluir( "", $sWhereBaseAto );

    $clescolabase->excluir( "", "ed77_i_base = {$ed31_i_codigo}" );
    $clbaseregimematdiv->excluir( "", "ed224_i_base = {$ed31_i_codigo}" );

    $sSqlEscolaBase  = "UPDATE escolabase ";
    $sSqlEscolaBase .= "   SET ed77_i_basecont = null ";
    $sSqlEscolaBase .= " WHERE ed77_i_basecont = {$ed31_i_codigo}";
    db_query( $sSqlEscolaBase );

    $clbase->excluir( $ed31_i_codigo );
    db_fim_transacao();
  }
}
if( isset( $chavepesquisa ) ) {

  if( $ed218_c_divisao == "S" ) {

    ?>
    <script>js_divisoes(<?=$ed31_i_regimemat?>,"A");</script>
    <?php
  }
}

if( isset( $excluir ) ) {

  if( $clbase->erro_status == "0" ) {
    $clbase->erro( true, false );
  } else {
    $clbase->erro( true, true );
  };
};

if( $db_opcao == 33 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}