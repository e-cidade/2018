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
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory( $_POST );

$clbase             = new cl_base;
$clescolabase       = new cl_escolabase;
$clbaseserie        = new cl_baseserie;
$clbasediscglob     = new cl_basediscglob;
$clbaseregimematdiv = new cl_baseregimematdiv;
$oDaoSerie          = new cl_serie;
$oDaoCursoAtoSerie  = new cl_cursoatoserie;
$oDaoBaseAto        = new cl_baseato;
$oDaoBaseAtoSerie   = new cl_baseatoserie;

$db_opcao  = 1;
$db_opcao1 = 1;
$db_botao  = true;

$iModulo = db_getsession('DB_modulo');
$iEscola = db_getsession('DB_coddepto');

$oErro           = new stdClass();
$oErro->lErro    = false;
$oErro->sMessage = '';

/**
 * Atenção... ao refatorar as mensagens foi criado o seguinte padrão:
 * a mensagem deve inicar com colchetes [] e em seguida a mensagem de erro.
 * dentro dos colchetes tem o nome do campo que deu erro na classe (DAO).
 * Ficando assim por exemplo:........................................... [ed41_i_codigo] Campo Código não informado.
 * Quando o erro não foi por falta de informar um campo obrigatório..... [] Erro ao salvar.... msg da classe ou sua
 */
try {

  if ( isset($incluir) ) {

    db_inicio_transacao();
    $clbase->incluir(null);

    if ( $clbase->erro_status == 0 ) {

      $sMsgErro = "[{$clbase->erro_campo}] {$clbase->erro_msg}";
      throw new Exception($sMsgErro);
    }

    $clbaseserie->incluir( $clbase->ed31_i_codigo );

    // inclui as series das base
    if( $clbaseserie->erro_status == '0' ) {

      $sMsgErro = "[{$clbaseserie->erro_campo}] {$clbaseserie->erro_msg}";
      throw new Exception($sMsgErro);
    }

    // se haver divisão
    if( $ed218_c_divisao == "S" ) {

      for( $r = 0; $r < count( $divisao ); $r++ ) {

        $clbaseregimematdiv->ed224_i_regimematdiv = $divisao[$r];
        $clbaseregimematdiv->ed224_i_base         = $clbase->ed31_i_codigo;
        $clbaseregimematdiv->incluir(null);

        if( $clbaseregimematdiv->erro_status == '0' ) {

          $sMsgErro = "[{$clbaseregimematdiv->erro_campo}] {$clbaseregimematdiv->erro_msg}";
          throw new Exception($sMsgErro);
        }
      }
    }

    /**
     * Ao incluir base escolar na secretaria de educação, não vincula com a escola e não inclui os atos dos cursos na escola
     */
    if ($iModulo != 7159) {

      $clescolabase->ed77_i_escola = $iEscola;
      $clescolabase->ed77_i_base   = $clbase->ed31_i_codigo;
      $clescolabase->incluir(null);

      if( $clescolabase->erro_status == '0' ) {

        $sMsgErro = "[{$clescolabase->erro_campo}] {$clescolabase->erro_msg}";
        throw new Exception($sMsgErro);
      }

      /**
       * Busca o(s) ato(s) do curso para suas etapas e inclui na base curricular
       */
      $sSqlSequenciaInicio = $oDaoSerie->sql_query_file( $ed87_i_serieinicial, 'ed11_i_sequencia' );
      $sSqlSequenciaFim    = $oDaoSerie->sql_query_file( $ed87_i_seriefinal, 'ed11_i_sequencia' );

      $sCampos = 'ed215_i_atolegal, ed216_i_serie';
      $sWhere  = "cursoescola.ed71_i_curso = {$ed31_i_curso} ";
      $sWhere .= "and cursoescola.ed71_i_escola = {$iEscola} ";
      $sWhere .= "and serie.ed11_i_sequencia between ({$sSqlSequenciaInicio}) and ({$sSqlSequenciaFim})";

      $sSqlAtoSerie = $oDaoCursoAtoSerie->sql_query(null, $sCampos, 'ed215_i_atolegal, ed216_i_serie', $sWhere);
      $rsAtoSerie   = db_query($sSqlAtoSerie);

      if (!$rsAtoSerie) {
        throw new Exception("[] " . pg_last_error());
      }

      $iLinhas  = pg_num_rows($rsAtoSerie);
      $iAtoNovo = -1;
      for ( $i = 0; $i < $iLinhas; $i++ ) {

        $oDados = db_utils::fieldsmemory($rsAtoSerie, $i);
        /**
         * inclui o ato do curso...
         */
        if( $iAtoNovo != $oDados->ed215_i_atolegal ) {

          $oDaoBaseAto->ed278_i_escolabase = $clescolabase->ed77_i_codigo;
          $oDaoBaseAto->ed278_i_atolegal   = $oDados->ed215_i_atolegal;
          $oDaoBaseAto->incluir(null);

          if( $oDaoBaseAto->erro_status == '0' ) {

            $sMsgErro = "[{$oDaoBaseAto->erro_campo}] {$oDaoBaseAto->erro_msg}";
            throw new Exception($sMsgErro);
          }

          $iAtoNovo = $oDados->ed215_i_atolegal;
        }

        /**
         * Inclui as séries vinculadas ao ato do curso
         */
        $oDaoBaseAtoSerie->ed279_i_baseato = $oDaoBaseAto->ed278_i_codigo;
        $oDaoBaseAtoSerie->ed279_i_serie   = $oDados->ed216_i_serie;
        $oDaoBaseAtoSerie->incluir(null);

        if( $oDaoBaseAtoSerie->erro_status == '0' ) {

          $sMsgErro = "[{$oDaoBaseAtoSerie->erro_campo}] {$oDaoBaseAtoSerie->erro_msg}";
          throw new Exception($sMsgErro);
        }
      }

      if ( !empty($iBaseCurricularImportacao) ) {

        $oDaoBaseMps = new cl_basemps();
        $sSqlBaseMps = $oDaoBaseMps->sql_query_file( null, '*', null, "ed34_i_base = {$iBaseCurricularImportacao}" );
        $rsBaseMps   = db_query( $sSqlBaseMps );

        if ( !$rsBaseMps ) {
          throw new DBException("[] Erro as disciplinas vinculadas a base curricular.");
        }

        $iTotalDisciplinas = pg_num_rows( $rsBaseMps );

        for( $iContador = 0; $iContador < $iTotalDisciplinas; $iContador++ ) {

          $oDadosDisciplina = db_utils::fieldsMemory( $rsBaseMps, $iContador );

          $oDaoBaseMps->ed34_i_codigo               = null;
          $oDaoBaseMps->ed34_i_base                 = $clbase->ed31_i_codigo;
          $oDaoBaseMps->ed34_i_serie                = $oDadosDisciplina->ed34_i_serie;
          $oDaoBaseMps->ed34_i_disciplina           = $oDadosDisciplina->ed34_i_disciplina;
          $oDaoBaseMps->ed34_i_qtdperiodo           = $oDadosDisciplina->ed34_i_qtdperiodo;
          $oDaoBaseMps->ed34_i_chtotal              = $oDadosDisciplina->ed34_i_chtotal;
          $oDaoBaseMps->ed34_c_condicao             = $oDadosDisciplina->ed34_c_condicao;
          $oDaoBaseMps->ed34_i_ordenacao            = $oDadosDisciplina->ed34_i_ordenacao;
          $oDaoBaseMps->ed34_lancarhistorico        = $oDadosDisciplina->ed34_lancarhistorico        == 'f' ? 'false' : 'true';
          $oDaoBaseMps->ed34_disiciplinaglobalizada = $oDadosDisciplina->ed34_disiciplinaglobalizada == 'f' ? 'false' : 'true';
          $oDaoBaseMps->ed34_caracterreprobatorio   = $oDadosDisciplina->ed34_caracterreprobatorio   == 'f' ? 'false' : 'true';
          $oDaoBaseMps->ed34_basecomum              = $oDadosDisciplina->ed34_basecomum              == 'f' ? 'false' : 'true';
          $oDaoBaseMps->incluir(null);

          if ( $oDaoBaseMps->erro_status == "0" ) {
            throw new DBException("[] {$oDaoBaseMps->erro_msg}");
          }
        }
      }
    }
  }
  db_fim_transacao( );
} catch (Exception $e) {

  db_fim_transacao( true );
  $oErro->lErro    = true;
  $oErro->sMessage = $e->getMessage();
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
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC">
  <?php
  require_once(modification("forms/db_frmbase.php"));
  ?>
</body>
</html>
<?php

if ( isset( $incluir ) ) {

  if ( $oErro->lErro ) {

    preg_match("/\[(?<campo>\w+|)\](?:\s+|)(?<mensagem>.*)/", $oErro->sMessage, $aSaida);

    $db_botao = true;
    db_msgbox($aSaida['mensagem']);
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ( !empty($aSaida['campo']) ) {

      echo "<script> document.form1.".$aSaida['campo'].".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$aSaida['campo'].".focus();</script>";
    }

    //se tem erro e regime possui divisões
    if( $ed218_c_divisao == "S" ) {
      ?> <script>js_divisoes(<?=$ed31_i_regimemat?>,"I");</script>  <?php
    }
  } else {

    $clbase->erro( true, false );
    db_redireciona("edu1_base002.php?chavepesquisa=".$clbase->ed31_i_codigo);
  }
}