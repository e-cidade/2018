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
$ed31_c_ativo = '';

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

$clbase         = new cl_base;
$clturma        = new cl_turma;
$clregencia     = new cl_regencia;
$clbaseserie    = new cl_baseserie;
$clbasemps      = new cl_basemps;
$clbasediscglob = new cl_basediscglob;

$db_opcao = 22;
if (isset( $chavepesquisa ) ) {
  $db_opcao = 2;
}

$db_opcao1 = 3;
$db_botao  = false;

$iModulo = db_getsession('DB_modulo');
$iEscola = db_getsession('DB_coddepto');

if( !isset( $alterar ) && isset( $chavepesquisa ) ) {

  $db_opcao  = 2;
  $db_opcao1 = 3;
  $campos    = "base.*,
                baseserie.*,
                basediscglob.*,
                regimemat.*,
                si.ed11_c_descr as ed11_c_descrini,
                sf.ed11_c_descr as ed11_c_descrfim,
                caddisciplina.ed232_c_descr,
                disciplina.*,
                cursoedu.*,
                ensino.*";

  $result = $clbase->sql_record( $clbase->sql_query_base2( "", $campos, "", "ed31_i_codigo = {$chavepesquisa}" ) );
  db_fieldsmemory( $result, 0 );
  $db_botao = true;

  $sDisciplinaGlobal = $ed31_c_contrfreq == "G" ? 'S' : 'N';
?>
<script type="text/javascript">
parent.document.formaba.a2.disabled = false;
parent.document.formaba.a3.disabled = false;
parent.document.formaba.a4.disabled = false;


var lEscola = <?=$iModulo?> == 1100747;

// na Secretaria de Educação não da manutenção nas abas Base de Continuação e Legislação
if ( !lEscola ) {

  parent.document.formaba.a3.disabled = true;
  parent.document.formaba.a4.disabled = true;
}


var sParametros  = 'iBase=<?=$ed31_i_codigo?>&sBase=<?=$ed31_c_descr?>&iCurso=<?=$ed31_i_curso?>';
    sParametros += '&cadastroBase=S&iTurma=';
    sParametros += '&lModuloEscola='+lEscola;

var sDisciplinaGlobal = '&sDisciplinaGlobal=<?=$sDisciplinaGlobal?>';

(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href = 'edu1_disciplinaetapa001.php?' + sParametros + sDisciplinaGlobal;

if (lEscola) {

  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href = 'edu1_escolabase001.php?ed77_i_base=<?=$ed31_i_codigo?>'
                                                           +'&ed31_c_descr=<?=$ed31_c_descr?>';
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href = 'edu1_baseato001.php?ed77_i_base=<?=$ed31_i_codigo?>'
                                                           +'&ed31_c_descr=<?=$ed31_c_descr?>';
}
</script>
 <?
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


if( isset( $alterar ) ) {

  /**
   * Buscamos os codigos das etapas permitidas para base curricular selecionada, e validamos se a etapa sera
   * compativel com o permitido
   */
  $lSerieInicialCompativel = false;
  $lSerieFinalCompativel   = false;
  $oDaoSerieRegimeMat      = db_utils::getDao("serieregimemat");
  $sWhereSerieRegimeMat    = " ed11_i_ensino = {$ed29_i_ensino} and ed223_i_regimemat = {$ed31_i_regimemat} ";
  $sSqlSerieRegimeMat      = $oDaoSerieRegimeMat->sql_query(null, "ed11_i_codigo", null, $sWhereSerieRegimeMat);
  $rsSerieRegimeMat        = $oDaoSerieRegimeMat->sql_record($sSqlSerieRegimeMat);
  $iTotalSerieRegimeMat    = $oDaoSerieRegimeMat->numrows;

  if( $iTotalSerieRegimeMat > 0 ) {

    for( $iContador = 0; $iContador < $iTotalSerieRegimeMat; $iContador++ ) {

      $iSerie = db_utils::fieldsMemory($rsSerieRegimeMat, $iContador)->ed11_i_codigo;

      if( $ed87_i_serieinicial == $iSerie ) {
        $lSerieInicialCompativel = true;
      }

      if( $ed87_i_seriefinal == $iSerie ) {
        $lSerieFinalCompativel = true;
      }
    }
  }

  if( !$lSerieInicialCompativel ) {

    $sMensagem = "Etapa inicial não permitida para esta base curricular (Etapa selecionada: {$ed87_i_serieinicial}).";
    db_msgbox($sMensagem);
    db_redireciona("edu1_base002.php?chavepesquisa={$ed31_i_codigo}");
  } else if( !$lSerieFinalCompativel ) {

    $sMensagem = "Etapa final não permitida para esta base curricular (Etapa selecionada: {$ed87_i_seriefinal}).";
    db_msgbox($sMensagem);
    db_redireciona("edu1_base002.php?chavepesquisa={$ed31_i_codigo}");
  }

  $db_opcao = 2;
  $db_botao = true;

  db_inicio_transacao();

  $clbase->alterar($ed31_i_codigo);
  $clbaseserie->ed87_i_codigo = $ed31_i_codigo;
  $clbaseserie->alterar($ed31_i_codigo);


  if ($clbase->erro_status != 0 && $clbaseserie->erro_status != 0) {

    $sCamposBaseSerie = "si.ed11_i_sequencia as seqini, sf.ed11_i_sequencia as seqfim, si.ed11_i_ensino as ensino";
    $sSqlBaseSerie    = $clbaseserie->sql_query_etapa_base( "", $sCamposBaseSerie, "", "ed87_i_codigo = {$ed31_i_codigo}");
    $result2          = $clbaseserie->sql_record( $sSqlBaseSerie );

    db_fieldsmemory( $result2, 0 );

    $sWhereBaseMps  = "     ed11_i_ensino = {$ensino} AND ed34_i_base = {$ed31_i_codigo}";
    $sWhereBaseMps .= " AND (ed11_i_sequencia < {$seqini} OR ed11_i_sequencia > {$seqfim})";
    $sSqlBaseMps    = $clbasemps->sql_query( "", "ed34_i_codigo", "", $sWhereBaseMps );
    $result3        = $clbasemps->sql_record( $sSqlBaseMps );
    $linhas3        = $clbasemps->numrows;

    for( $a = 0; $a < $linhas3; $a++ ) {

      db_fieldsmemory( $result3, $a );
      $clbasemps->excluir( $ed34_i_codigo );
    }

    /**
     * Caso esteja alterando a base para controle de frequencia individual, devemos remover as disciplinas
     * globalizadas
     */
    if( $ed31_c_contrfreq == "I" ) {

      $sql    = "UPDATE basemps ";
      $sql   .= "   SET ed34_disiciplinaglobalizada = false";
      $sql   .= " WHERE ed34_i_base = {$ed31_i_codigo} ";

      $query  = db_query( $sql );
    }

    $sDisciplinaGlobal = $ed31_c_contrfreq == "G" ? 'S' : 'N';
  }

  db_fim_transacao();
}

if( isset( $chavepesquisa ) ) {

  if( $ed218_c_divisao == "S" ) {

    ?>
    <script>
      js_divisoes( <?=$ed31_i_regimemat?>, "A" );
    </script>
    <?php
  }
}

if( isset( $alterar ) ) {

  $temerro = false;
  if( $clbase->erro_status == "0" ) {

    $clbase->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if( $clbase->erro_campo != "" ) {

      echo "<script> document.form1.".$clbase->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbase->erro_campo.".focus();</script>";
    };

    $temerro = true;
  }

  if( @$clbaseserie->erro_status == "0" ) {

    $clbaseserie->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if( $clbaseserie->erro_campo != "" ) {

      echo "<script> document.form1.".$clbaseserie->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbaseserie->erro_campo.".focus();</script>";
    };

    $temerro = true;
  }

  if( $temerro == false ) {

    db_msgbox("Alteração efetuada com Sucesso.");
    ?>
    <script>
    parent.document.formaba.a2.disabled = false;
    parent.document.formaba.a3.disabled = false;
    parent.document.formaba.a4.disabled = false;

    /**
     * na Secretaria de Educação não da manutenção nas abas Base de Continuação e Legislação
     * 7159..... é o código do módulo Secretaria de Educação
     * 1100747.. é o código do módulo Escola
     */
    var lEscola = <?=$iModulo?> == 1100747;
    if ( !lEscola ) {

      parent.document.formaba.a3.disabled = true;
      parent.document.formaba.a4.disabled = true;
    }

    var sParametros  = 'iBase=<?=$ed31_i_codigo?>&sBase=<?=$ed31_c_descr?>&iCurso=<?=$ed31_i_curso?>';
        sParametros += '&cadastroBase=S&iTurma=';
        sParametros += '&lModuloEscola='+lEscola;

    var sDisciplinaGlobal = '&sDisciplinaGlobal=<?=$sDisciplinaGlobal?>';

    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href = 'edu1_disciplinaetapa001.php?' + sParametros + sDisciplinaGlobal;
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href = 'edu1_escolabase001.php?ed77_i_base=<?=$ed31_i_codigo?>&ed31_c_descr=<?=$ed31_c_descr?>';

    if ( lEscola ) {
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href = 'edu1_baseato001.php?ed77_i_base=<?=$ed31_i_codigo?>&ed31_c_descr=<?=$ed31_c_descr?>';
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a1.location.href = 'edu1_base002.php?chavepesquisa=<?=$ed31_i_codigo?>';
    }
    </script>
    <?
  }
};

if( $db_opcao == 22 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
