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

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

$clsau_prestadores = new cl_sau_prestadores;
$db_botao = false;
$db_opcao = 33;

if( isset( $excluir ) ) {

  db_inicio_transacao();
  try {

    $sCampos  = " array_to_string(array_accum(s111_i_codigo), ',') as exames, ";
    $sCampos .= " array_to_string(array_accum(s113_i_codigo), ',') as agendas, ";
    $sCampos .= " array_to_string(array_accum(age02_sequencial), ',') as grupoexame, ";
    $sCampos .= " array_to_string(array_accum(age02_cotaprestadoraexamemensal), ',') as cotamensal ";

    $sSQlValidaExclusao = $clsau_prestadores->sql_query_prestadora_agenda($s110_i_codigo, $sCampos);
    $rsValidaExclusao   = db_query( $sSQlValidaExclusao );

    if ($rsValidaExclusao && pg_num_rows($rsValidaExclusao) > 0) {

      $oDados = db_utils::fieldsMemory($rsValidaExclusao, 0);
      if ( !empty($oDados->agendas) ) {
        throw new Exception("Prestadora não pode ser excluída pois possui exames agendados.");
      }

      if ( !empty($oDados->exames) ) {

        $oDaoPrestadorHorario  = new cl_sau_prestadorhorarios();
        $oDaoPrestadorHorario->excluir( null, " s112_i_prestadorvinc in ({$oDados->exames}) ");
        if ( $oDaoPrestadorHorario->erro_status == 0 ) {
          throw new Exception( str_replace("\\n", "\n", $oDaoPrestadorHorario->erro_sql));
        }


        if ( !empty($oDados->grupoexame) ) {

          $oDaoGrupoPrestador = new cl_grupoexameprestador();
          $oDaoGrupoPrestador->excluir(null, " age03_grupoexame in ({$oDados->grupoexame}) ");
          if ( $oDaoGrupoPrestador->erro_status == 0 ) {
            throw new Exception( str_replace("\\n", "\n", $oDaoGrupo->erro_sql));
          }

          $oDaoGrupo = new cl_grupoexame();
          $oDaoGrupo->excluir(null, " age02_sequencial in ({$oDados->grupoexame}) ");
          if ( $oDaoGrupo->erro_status == 0 ) {
            throw new Exception( str_replace("\\n", "\n", $oDaoGrupo->erro_sql));
          }
        }

        if ( !empty($oDados->cotamensal) ) {

          $oDaoCotaMensal  = new cl_cotaprestadoraexamemensal();
          $oDaoCotaMensal->excluir( null, " age01_sequencial in ({$oDados->cotamensal}) ");
          if ( $oDaoPrestadorHorario->erro_status == 0 ) {
            throw new Exception( str_replace("\\n", "\n", $oDaoPrestadorHorario->erro_sql));
          }
        }


        $oDaoPrestadorVinculos = new cl_sau_prestadorvinculos();
        $oDaoPrestadorVinculos->excluir(null, " s111_i_codigo in ({$oDados->exames}) " );
        if ( $oDaoPrestadorVinculos->erro_status == 0 ) {
          throw new Exception( str_replace("\\n", "\n", $oDaoPrestadorVinculos->erro_sql));
        }
      }
    }

    $db_opcao = 3;
    $clsau_prestadores->excluir($s110_i_codigo);
    if ( $clsau_prestadores->erro_status == 0 ) {
      throw new Exception( str_replace("\\n", "\n", $clsau_prestadores->erro_sql));
    }

    db_fim_transacao();

  } catch( Exception $oErro) {

    db_fim_transacao(true);
    db_msgbox( $oErro->getMessage() );
    db_redireciona( "sau1_sau_prestadores003.php" );
  }

} else if( isset( $chavepesquisa ) ) {

  $db_opcao = 3;
  $result   = $clsau_prestadores->sql_record( $clsau_prestadores->sql_query( $chavepesquisa ) );
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <?php
  include(modification("forms/db_frmsau_prestadores.php"));
  ?>
</body>
</html>
<?php
if( isset( $excluir ) ) {

  if( $clsau_prestadores->erro_status == "0" ) {
    $clsau_prestadores->erro( true, false );
  } else {
    $clsau_prestadores->erro( true, true );
  }
}

if( $db_opcao == 33 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>
