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

//MODULO: educação
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_serie_classe.php"));
include(modification("classes/db_historico_classe.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clserie     = new cl_serie;
$clhistorico = new cl_historico;

$sNome = isset($z01_nome) ? $z01_nome : '';

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td align="center" valign="top">
   <?php
   $result = $clhistorico->sql_record($clhistorico->sql_query("","ed61_i_curso,ed61_i_aluno,ed47_v_nome,ed29_c_descr",""," ed61_i_codigo = $historico"));
   db_fieldsmemory($result,0);
   echo "<b>Etapas disponíveis para o aluno {$sNome} no curso {$ed29_c_descr}:</b>";

  if ( !isset($pesquisa_chave) ) {

    if ( isset($campos) == false ) {

      if ( file_exists("funcoes/db_func_serie.php") == true ) {
        include( modification("funcoes/db_func_serie.php") );
      } else {
        $campos = "serie.*";
      }
    }

    $sql1 = "SELECT ed11_i_codigo
               FROM historicomps
                    inner join serie on ed11_i_codigo = ed62_i_serie
              WHERE ed62_i_historico = {$historico}
                AND ed62_c_resultadofinal != 'R'
             UNION
             SELECT ed11_i_codigo
               FROM historicompsfora
                    inner join serie on ed11_i_codigo = ed99_i_serie
              WHERE ed99_i_historico = {$historico}
                AND ed99_c_resultadofinal != 'R'
                AND ( ed99_c_situacao = 'CONCLUÍDO' OR ed99_c_situacao = 'RECLASSIFICADO' )
            ";

    $query1  = db_query($sql1);

    if ( !$query1 ) {

      db_msgbox('Erro ao buscar etapas que ainda não foram cursadas pelo aluno.');
      echo "<script>parent.db_iframe_serie.hide();</script>";
      exit;
    }

    $linhas1 = pg_num_rows($query1);

    if ( $linhas1 > 0 ) {

      $ser_jatem = "";
      $sep       = "";

      for ( $x = 0; $x < $linhas1; $x++ ) {

       db_fieldsmemory( $query1, $x );
       $ser_jatem .= $sep.$ed11_i_codigo;
       $sep        = ",";
      }
    } else {
      $ser_jatem = 0;
    }

    $aWhere   = array();
    $aWhere[] = "ed29_i_codigo = {$ed61_i_curso}";
    $aWhere[] = "ed11_i_codigo not in ( {$ser_jatem} )";

    $lExecutaConsulta = true;

    /* Verifica qual etapas o departamento logado pode visualizar */
    if ( isset($iStatusAlteracaoHistorico) && isset($iOrdemEtapaAtual) ) {

      switch ($iStatusAlteracaoHistorico) {

        case HistoricoEscolar::PERMITE_MANUTENCAO:
        case HistoricoEscolar::PERMITE_MANUTENCAO_ETAPAS_MAIORES_OU_IGUAIS && $iOrdemEtapaAtual === 'undefined':
          break;

        case HistoricoEscolar::PERMITE_MANUTENCAO_ETAPAS_MAIORES_OU_IGUAIS:
          $aWhere[] = "ed11_i_sequencia >= {$iOrdemEtapaAtual}";
          break;

        case HistoricoEscolar::PERMITE_MANUTENCAO_ETAPAS_MENORES:
          $aWhere[] = "ed11_i_sequencia < {$iOrdemEtapaAtual}";
          break;

        case HistoricoEscolar::NAO_PERMITE_MANUTENCAO:
          $lExecutaConsulta = false;
          break;
      }
    }

    $sWhere = implode(" AND ", $aWhere);

    $campos .= ", ed11_i_ensino as db_ensino";
    $sql = "SELECT $campos
              FROM serie
                   inner join ensino   on ed10_i_codigo = ed11_i_ensino
                   inner join cursoedu on ed29_i_ensino = ed10_i_codigo
             WHERE {$sWhere}
             ORDER BY ed11_i_sequencia
           ";

    if ($lExecutaConsulta) {
      db_lovrot($sql,15,"()","",$funcao_js);
    } else {
      echo "<br><br>Nenhuma etapa encontrada.";
    }
  }
  ?>
  </td>
 </tr>
</table>
</body>
</html>