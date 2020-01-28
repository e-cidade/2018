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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
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
<?php
db_postmemory( $_POST );

$clsau_prestadorvinculos = new cl_sau_prestadorvinculos;

$db_opcao = 1;
$db_botao = true;
$oPost    = db_utils::postMemory( $_POST );

$lExameComAgenda = false;

// verifica se exame tem agenda
if ( !empty($s111_i_codigo) ) {

  $oDaoPrestadorHorario = new cl_sau_prestadorhorarios();
  $sSqlValidaAgenda     = $oDaoPrestadorHorario->sql_query_existe_agenda(null, "1", null, " s112_i_prestadorvinc = {$s111_i_codigo} ");
  $rsValidaAgenda       = db_query( $sSqlValidaAgenda );

  if ( $rsValidaAgenda && pg_num_rows($rsValidaAgenda) > 0) {
    $lExameComAgenda = true;
  }
}

if( isset( $opcao ) ) {

  $db_botao1 = true;
  $db_opcao  = $opcao == "alterar" ? 2 : 3;
  $result    = $clsau_prestadorvinculos->sql_record( $clsau_prestadorvinculos->sql_query( $s111_i_codigo ) );

  if( $clsau_prestadorvinculos->numrows > 0 ) {
    db_fieldsmemory( $result, 0 );
  }

}

/**
 * Verifica se o exame selecionado para ser incluso, já não está vinculado ao prestador
 * @param $db_opcao
 * @param $oPost
 */
function validaExame( $db_opcao, $oPost ) {

  $oDaoSauPrestadorVinculo    = new cl_sau_prestadorvinculos();
  $sWhereSauPrestadorVinculo  = "     sd63_c_procedimento = '{$oPost->sd63_c_procedimento}'";
  $sWhereSauPrestadorVinculo .= " AND s111_i_prestador = {$oPost->s111_i_prestador}";
  $sSqlSauPrestadorVinculo    = $oDaoSauPrestadorVinculo->sql_query( null, "1", null, $sWhereSauPrestadorVinculo );
  $$rsSauPrestadorVinculo      = db_query( $sSqlSauPrestadorVinculo );

  if( $rsSauPrestadorVinculo && pg_num_rows( $rsSauPrestadorVinculo ) > 0 ) {

    db_msgbox( "Exame já vinculado ao prestador." );
    db_redireciona( "sau1_sau_prestadorvinculos001.php?op={$db_opcao}&z01_nome={$oPost->z01_nome}&s111_i_prestador={$oPost->s111_i_prestador}" );
  }
}

try {

  $lReload = false;
  if( isset( $incluir ) ) {

    validaExame( $db_opcao, $oPost );

    db_inicio_transacao();

    $clsau_prestadorvinculos->s111_c_situacao   = $s111_c_situacao;
    $clsau_prestadorvinculos->s111_i_prestador  = $s111_i_prestador;
    $clsau_prestadorvinculos->s111_procedimento = $sd63_i_codigo;
    $clsau_prestadorvinculos->incluir( null );

    if( $clsau_prestadorvinculos->erro_status == 0 ) {
      throw new DBException( $clsau_prestadorvinculos->erro_msg );
    }

    db_fim_transacao();
    db_msgbox( 'Inclusão realizada com sucesso.' );
    $lReload = true;

  } else if( isset( $alterar ) ) {

    $db_opcao   = 2;
    $booRetorno = true;
    db_inicio_transacao();

    $clsau_prestadorvinculos->s111_c_situacao   = $s111_c_situacao;
    $clsau_prestadorvinculos->s111_i_prestador  = $s111_i_prestador;
    $clsau_prestadorvinculos->s111_procedimento = $sd63_i_codigo;
    $clsau_prestadorvinculos->s111_i_codigo     = $s111_i_codigo;
    $clsau_prestadorvinculos->alterar( $s111_i_codigo );

    if( $clsau_prestadorvinculos->erro_status == 0 ) {
      throw new DBException( $clsau_prestadorvinculos->erro_msg );
    }

    db_fim_transacao();
    db_msgbox( 'Alteração realizada com sucesso.' );
    $lReload = true;

  } else if( isset( $excluir ) ) {

    $db_opcao = 3;
    db_inicio_transacao();

    if ( $lExameComAgenda ) {

      $sMsgErro  = "Existe agendamento para o exame {$oPost->sd63_c_nome}.\n";
      $sMsgErro .= "Você pode alterar a situação do exame para Inativo para não permitir mais agendamentos.";
      throw new Exception( $sMsgErro );
    }

    /**
     * Verifica se já existe agendameto para o exame
     *
     * @var $oDaoGrupoExamePrestador
     * @var $sWhereGrupoExamePrestador
     * @var $sSqlGrupoExamePrestador
     * @var $rsGrupoExamePrestador
     */

    $oDaoGrupoExamePrestador   = new cl_grupoexameprestador();
    $sWhereGrupoExamePrestador = "sau_prestadorvinculos.s111_i_codigo = grupoexameprestador.age03_prestadorvinculos";
    $sSqlGrupoExamePrestador   = $oDaoGrupoExamePrestador->sql_query( null, "1", null, $sWhereGrupoExamePrestador);
    $rsGrupoExamePrestador     = db_query($sSqlGrupoExamePrestador);

    if ($rsGrupoExamePrestador && pg_num_rows($rsGrupoExamePrestador) > 0) {
        throw new DBException("Existe agendamento para o exame {$oPost->sd63_c_nome}.\n Você pode alterar a situação do exame para Inativo para não permitir mais agendamentos.");
    }

    $oDaoPrestadorHorario->excluir( null, " s112_i_prestadorvinc = {$s111_i_codigo}");
    $clsau_prestadorvinculos->excluir( $s111_i_codigo );

    if( $clsau_prestadorvinculos->erro_status == 0 ) {
      throw new DBException( $clsau_prestadorvinculos->erro_msg );
    }

    db_fim_transacao();
    db_msgbox( 'Exclusão realizada com sucesso.' );
    $lReload = true;

  }

  if ( $lReload ) {

    echo "<script>parent.iframe_a3.location.href = 'sau1_sau_prestadorhorarios001.php?s111_i_prestador=$s111_i_prestador&z01_nome=$z01_nome'</script>";
    db_redireciona( "sau1_sau_prestadorvinculos001.php?op={$db_opcao}&z01_nome={$z01_nome}&s111_i_prestador={$s111_i_prestador}" );
  }
} catch( Exception $oErro ) {

  db_msgbox( $oErro->getMessage() );
  db_fim_transacao( true );
  db_redireciona( "sau1_sau_prestadorvinculos001.php?op={$db_opcao}&z01_nome={$z01_nome}&s111_i_prestador={$s111_i_prestador}" );
}
?>

<body class="body-default">
  <?php
  include(modification("forms/db_frmsau_prestadorvinculos.php"));
  ?>
</body>
</html>
