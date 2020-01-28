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

db_postmemory( $_POST );

$clprocavaliacao  = new cl_procavaliacao;
$clformaavaliacao = new cl_formaavaliacao;

$db_opcao  = 1;
$db_opcao1 = 1;
$db_botao  = true;

$sPossuiTurmasEncerradas = isset($_GET['possuiTurmasEncerradas']) ? $_GET['possuiTurmasEncerradas'] : '';
$lPossuiTurmasEncerradas = $sPossuiTurmasEncerradas === 'S';

try {

  if( isset( $incluir ) ) {

    db_inicio_transacao();

    $sql = "SELECT ed41_i_sequencia
              FROM procavaliacao
             WHERE ed41_i_procedimento = {$ed41_i_procedimento}
            UNION
            SELECT ed43_i_sequencia
              FROM procresultado
             WHERE ed43_i_procedimento = {$ed41_i_procedimento}
             ORDER BY ed41_i_sequencia";

    $result = db_query($sql);

    if ( !$result ) {
      throw new DBException('Falha ao buscar os dados do procedimento de avaliação.');
    }

    $linhas = pg_num_rows($result);

    if( $linhas == 0 ) {
      $max = 0;
    } else {
      $max = pg_result( $result, $linhas - 1, "ed41_i_sequencia" );
    }

    if( $tipoVinculo == "A" ) {

      $procavalvinc   = @$vinculado[0];
      $procresultvinc = 0;
    } else {

      $procavalvinc   = 0;
      $procresultvinc = @$vinculado[0];
    }

    $clprocavaliacao->ed41_i_procavalvinc   = $procavalvinc;
    $clprocavaliacao->ed41_i_procresultvinc = $procresultvinc;
    $clprocavaliacao->ed41_i_sequencia      = ( $max + 1 );
    $clprocavaliacao->incluir( $ed41_i_codigo );

    if ( $clprocavaliacao->erro_status == '0' ) {
      throw new DBException("Falha ao incluir o procedimento de avaliação: \n{$clprocavaliacao->erro_msg}");
    }

    db_fim_transacao();

    $db_botao = false;
  }

  if( isset( $alterar ) ) {

    $db_opcao  = 2;
    $db_opcao1 = 3;

    db_inicio_transacao();

    if( $tipoVinculo == "A" ) {

      $procavalvinc   = $vinculado[0] == '' ? '0' : $vinculado[0];
      $procresultvinc = '0';
    } else {

      $procavalvinc   = '0';
      $procresultvinc = $vinculado[0] == '' ? '0' : $vinculado[0];
    }

    $clprocavaliacao->ed41_i_procavalvinc      = $procavalvinc;
    $clprocavaliacao->ed41_i_procresultvinc    = $procresultvinc;
    $clprocavaliacao->alterar($ed41_i_codigo);

    if ( $clprocavaliacao->erro_status == '0' ) {
      throw new DBException("Falha ao alterar o procedimento de avaliação: \n{$clprocavaliacao->erro_msg}");
    }

    db_fim_transacao();
  }

  if( isset( $excluir ) ) {

    $db_opcao  = 3;
    $db_opcao1 = 3;

    $sCampos  = " exists( select 1 from avalcompoeres   where ed44_i_procavaliacao = ed41_i_codigo) as avalcompoeres,";
    $sCampos .= " exists( select 1 from avalfreqres     where ed67_i_procavaliacao = ed41_i_codigo) as avalfreqres,";
    $sCampos .= " exists( select 1 from diarioavaliacao where ed72_i_procavaliacao = ed41_i_codigo) as diarioavaliacao,";
    $sCampos .= " exists( select 1 from regenciaperiodo where ed78_i_procavaliacao = ed41_i_codigo) as regenciaperiodo ";

    $sSqlValida = $clprocavaliacao->sql_query_file($ed41_i_codigo, $sCampos);
    $rsValida   = db_query($sSqlValida);

    if ( !$rsValida ) {
      throw new Exception("Erro ao validar elemento de avaliação.");
    }

    $oDados = db_utils::fieldsMemory($rsValida, 0);

    if ($oDados->diarioavaliacao == 't') {
      throw new Exception("O elemento de avaliação não pode ser excluído, pois possui vínculo com diário(s) de classe.");
    }

    if ($oDados->avalfreqres == 't') {

      $sMsg = "O elemento de avaliação não pode ser excluído, pois compõe o cálculo da frequência do procedimento de avaliação.";
      throw new Exception($sMsg);
    }

    if ($oDados->avalcompoeres == 't') {

      $sMsg = "O elemento de avaliação não pode ser excluído, pois compõe o cálculo de um resultado do procedimento de avaliação.";
      throw new Exception($sMsg);
    }

    db_inicio_transacao();

    $oDaoPeriodo = new cl_regenciaperiodo();
    $oDaoPeriodo->excluir(null, "ed78_i_procavaliacao = {$ed41_i_codigo}");
    if ($oDaoPeriodo->erro_status == '0') {
      throw new DBException("Falha ao excluir o vínculo com período: \n{$clprocavaliacao->erro_msg}");
    }

    $clprocavaliacao->excluir($ed41_i_codigo);

    if ( $clprocavaliacao->erro_status == '0' ) {
      throw new DBException("Falha ao excluir o procedimento de avaliação: \n{$clprocavaliacao->erro_msg}");
    }

    db_fim_transacao();
  }

  if( isset( $chavepesquisa) && ( !isset( $alterar ) && !isset( $excluir ) ) ) {

    $db_opcao1 = 3;

    if( $tarefa == "alterar" ) {
      $db_opcao = 2;
    } else {
      $db_opcao = 3;
    }

    $sSqlPesquisaProcAvaliacao = $clprocavaliacao->sql_query( $chavepesquisa );
    $rsPesquisaProcAvaliacao   = db_query($sSqlPesquisaProcAvaliacao);

    if ( !$rsPesquisaProcAvaliacao ) {
      throw new DBException('Falha ao pesquisar os procedimentos de avaliação.');
    }

    db_fieldsmemory( $rsPesquisaProcAvaliacao, 0 );
    $db_botao = true;
  }

} catch (Exception $oErro) {

  db_fim_transacao( true );

  $sMessage = urlencode($oErro->getMessage());
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMessage}");
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%">
    <legend>
     <b><?=($db_opcao==1?"Inclusão":($db_opcao==2?"Alteração":"Exclusão"))?> da Avaliação Periódica <?=@$ed09_c_descr?></b>
    </legend>
    <?include(modification("forms/db_frmprocavaliacao.php"));?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed41_i_formaavaliacao",true,1,"ed41_i_formaavaliacao",true);
</script>
<?php
if( isset( $chavepesquisa ) ) {
  ?><script>iframe_aval.location.href = "edu1_procedimento004.php?codigo=<?=$ed41_i_formaavaliacao?>";</script><?
}

if( isset( $incluir ) ) {

  if( $clprocavaliacao->erro_status == "0" ) {

    $clprocavaliacao->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>";

    if( $clprocavaliacao->erro_campo != "" ) {

      echo "<script> document.form1.".$clprocavaliacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocavaliacao->erro_campo.".focus();</script>";
    }
  } else {

    $clprocavaliacao->erro(true,false);
    ?>
    <script>
       parent.location.href = 'edu1_avaliacoes.php?procedimento=<?=$ed41_i_procedimento?>&ed40_c_descr=<?=$ed40_c_descr?>'+
                              '&forma=<?=$forma?>&possuiTurmasEncerradas=<?=$sPossuiTurmasEncerradas?>';
    </script>
    <?php
  }
}

if( isset( $alterar ) ) {

  if( $clprocavaliacao->erro_status == "0" ) {

    $clprocavaliacao->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>";

    if( $clprocavaliacao->erro_campo != "" ) {

      echo "<script> document.form1.".$clprocavaliacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocavaliacao->erro_campo.".focus();</script>";
    }
  } else {

    $clprocavaliacao->erro(true,false);
    ?>
    <script>
      parent.location.href = 'edu1_avaliacoes.php?procedimento=<?=$ed41_i_procedimento?>&ed40_c_descr=<?=$ed40_c_descr?>'+
                             '&forma=<?=$forma?>&possuiTurmasEncerradas=<?=$sPossuiTurmasEncerradas?>';
    </script>
    <?php
  }
}

if( isset( $excluir ) ) {

  if( $clprocavaliacao->erro_status == "0" ) {
    $clprocavaliacao->erro( true, false );
  } else {

    $clprocavaliacao->erro(true,false);
    ?>
    <script>
      parent.location.href = 'edu1_avaliacoes.php?procedimento=<?=$ed41_i_procedimento?>&ed40_c_descr=<?=$ed40_c_descr?>'+
                             '&forma=<?=$forma?>&possuiTurmasEncerradas=<?=$sPossuiTurmasEncerradas?>';
    </script>
    <?
  }
}

if( isset( $cancelar ) ) {
  echo "<script>location.href='".$clprocavaliacao->pagina_retorno."'</script>";
}

function AvalResultList( $nome, $procedimento, $disabled, $sequencia, $avalvinc, $resultvinc ) {

  if( $sequencia != "" ) {

    $where1 = "AND ed41_i_sequencia < {$sequencia}";
    $where2 = "AND ed43_i_sequencia < {$sequencia}";
  } else {

    $where1 = "";
    $where2 = "";
  }

  $sql = "SELECT ed41_i_codigo as codigo,
                 ed09_c_descr as avaliacao,
                 case
                   when ed41_i_codigo > 0
                     then 'A'
                  end as tipo,
                 ed41_i_sequencia,
                 ed37_c_tipo
            FROM procavaliacao
                 inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao
                 inner join formaavaliacao   on formaavaliacao.ed37_i_codigo   = procavaliacao.ed41_i_formaavaliacao
           WHERE ed41_i_procedimento = {$procedimento}
           {$where1}
          UNION
          SELECT ed43_i_codigo as codigo,
                 ed42_c_descr as resultado,
                 case
                   when ed43_i_codigo > 0
                     then 'R'
                  end as tipo,
                 ed43_i_sequencia,
                 ed37_c_tipo
            FROM procresultado
                 inner join resultado      on resultado.ed42_i_codigo      = procresultado.ed43_i_resultado
                 inner join formaavaliacao on formaavaliacao.ed37_i_codigo = procresultado.ed43_i_formaavaliacao
           WHERE ed43_i_procedimento = {$procedimento}
           {$where2}
           ORDER BY ed41_i_sequencia desc";

  $query  = db_query($sql);
  $query1 = db_query($sql);
  $linhas = pg_num_rows($query);
  ?>
  <select name="<?=$nome?>[]"
          id="<?=$nome?>"
          size="1"
          style="width: 100%">
    <option value=''></option>
    <?php
    for( $i = 0; $i < $linhas; $i++ ) {

      $dados1 = pg_fetch_array($query1);
      if( $avalvinc == 0 && $resultvinc != 0 ) {
        $tipoaval1 = "R";
      } else if( $resultvinc == 0 && $avalvinc != 0 ) {
        $tipoaval1 = "A";
      } else if( $avalvinc == 0 && $resultvinc == 0 ) {
        $tipoaval1 = "";
      }

      $tipoaval  = $avalvinc != 0 ? $avalvinc : $resultvinc;
      $selected1 = trim( $tipoaval ) == trim( $dados1["codigo"] ) && $tipoaval1 == trim( $dados1["tipo"] ) ? " selected " : "";

      $sHtml  = "<option value='{$dados1["codigo"]}'";
      $sHtml .= "        tipo='" . trim($dados1["tipo"]) . "'";
      $sHtml .= "        forma_avaliacao='" . trim($dados1["ed37_c_tipo"]) . "'";
      $sHtml .= "        {$selected1}>" . trim($dados1["tipo"]) . " - " . trim($dados1["avaliacao"]);
      $sHtml .= "</option>";
      echo $sHtml;
    }
    ?>
  </select>
  <?
}