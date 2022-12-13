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
require_once(modification("libs/db_jsplibwebseller.php"));

parse_str( $_SERVER["QUERY_STRING"] );

define( 'MENSAGEM_ALUNOTRANSF002', 'educacao.escola.edu1_alunotransfturma002.' );

$oGet                  = db_utils::postMemory($_GET);
$ed69_d_datatransf_dia = date("d",db_getsession("DB_datausu"));
$ed69_d_datatransf_mes = date("m",db_getsession("DB_datausu"));
$ed69_d_datatransf_ano = date("Y",db_getsession("DB_datausu"));

db_postmemory( $_POST );

$resultedu = eduparametros(db_getsession("DB_coddepto"));

$clalunotransfturma             = new cl_alunotransfturma;
$clregencia                     = new cl_regencia;
$clturma                        = new cl_turma;
$clturmaserieregimemat          = new cl_turmaserieregimemat;
$clprocavaliacao                = new cl_procavaliacao;
$clmatricula                    = new cl_matricula;
$clmatriculamov                 = new cl_matriculamov;
$clmatriculaserie               = new cl_matriculaserie;
$cldiario                       = new cl_diario;
$cldiarioavaliacao              = new cl_diarioavaliacao;
$cldiarioresultado              = new cl_diarioresultado;
$cldiariofinal                  = new cl_diariofinal;
$oDaoDiarioAvaliacaoAlternativa = new cl_diarioavaliacaoalternativa();
$clpareceraval                  = new cl_pareceraval;
$clparecerresult                = new cl_parecerresult;
$clabonofalta                   = new cl_abonofalta;
$clamparo                       = new cl_amparo;
$cltransfescolarede             = new cl_transfescolarede;
$cltransfescolafora             = new cl_transfescolafora;
$cltransfaprov                  = new cl_transfaprov;
$clalunocurso                   = new cl_alunocurso;
$claprovconselho                = new cl_aprovconselho;
$clserieequiv                   = new cl_serieequiv;
$oDaoMatriculaTurnoReferente    = new cl_matriculaturnoreferente();
$oDaoTurmaTurnoReferente        = new cl_turmaturnoreferente();
$oDaoDiarioResultadoRecuperacao = new cl_diarioresultadorecuperacao();

$clalunotransfturma->rotulo->label();
$db_opcao = 22;
$db_botao = false;

?>
<table width="300"
       height="100"
       id="tab_aguarde"
       style="border:2px solid #444444;position:absolute;top:100px;left:250px;" cellspacing="1" cellpading="2">
  <tr>
    <td bgcolor="#DEB887" align="center" style="border:1px solid #444444;">
      <b>Aguarde...Carregando.</b>
    </td>
  </tr>
</table>
<?php
if( !isset( $incluir ) && !isset( $incluir2 ) ) {

  ?>
  <html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <form class="container" name="form1" METHOD="POST" action="">
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
           <td>
             <input id="sTurno" name="sTurno" type="hidden" value="<?=$oGet->sTurno;?>" />
           </td>
          <td colspan="4" valign="top" bgcolor="#CCCCCC">
            <br>
            <b>
            Caso as turmas de origem e destino tenham disciplinas e/ou períodos de avaliação diferentes,
            informe abaixo quais disciplinas e períodos de avaliação da turma de destino que vão receber
            as informações do aluno.
            </b>
            <br><br>
          </td>
        </tr>
        <tr>
          <td width="28%" valign="top" bgcolor="#CCCCCC">
            <b>Disciplinas TURMA DE ORIGEM:</b>
          </td>
          <td width="20"></td>
          <td width="28%" valign="top" bgcolor="#CCCCCC">
            <b>Disciplinas TURMA DE DESTINO:</b>
          </td>
          <td valign="top" bgcolor="#CCCCCC">
            <b>Aproveitamento na TURMA DE ORIGEM:</b>
          </td>
        </tr>
        <?php

        /**
         * Variáveis para controle das etapas das progressões de um aluno
         */
        $aEtapasProgressao = array();
        $sEtapasProgressao = "";
        $oAluno            = AlunoRepository::getAlunoByMatricula( $matricula );

        /**
         * Percorre as progressões do aluno, caso existam, e adicione ao array para em seguida adicionar a string
         */
        foreach ( $oAluno->getProgressaoParcial() as $oProgressaoParcial ) {
          $aEtapasProgressao[] = $oProgressaoParcial->getEtapa()->getCodigo();
        }

        $sEtapasProgressao = implode( ", ", $aEtapasProgressao );

        $sCamposMatricula = "ed60_i_aluno, ed221_i_serie as etapaorigem";
        $sSqlMatricula    = $clmatricula->sql_query( "", $sCamposMatricula, "", " ed60_i_codigo = {$matricula}" );
        $result_cod       = $clmatricula->sql_record( $sSqlMatricula );
        db_fieldsmemory( $result_cod, 0 );

        /**
         * Existindo etapas de progressão, adiciona a variável $etapaorigem
         */
        if ( !empty( $sEtapasProgressao ) ) {
          $etapaorigem = $etapaorigem.", ".$sEtapasProgressao;
        }

        $sCamposSerieEquiv = "ed234_i_serieequiv as equivorig";
        $sWhereSerieEquiv  = "ed234_i_serie in ({$etapaorigem})";

        $sSqlSerieEquiv   = $clserieequiv->sql_query( "", $sCamposSerieEquiv, "", $sWhereSerieEquiv );
        $result_equivorig = $clserieequiv->sql_record( $sSqlSerieEquiv );

        $aSerieEquivalente = array($etapaorigem);
        for ( $ww = 0; $ww < $clserieequiv->numrows; $ww++ ) {

          db_fieldsmemory( $result_equivorig, $ww );
          $aSerieEquivalente[] = $equivorig;
        }

        $codequivorig = implode(", ", $aSerieEquivalente);

        $sCamposProcOrigem  = "ed59_i_codigo, ed232_i_codigo, ed232_c_descr, ed232_c_abrev, ed220_i_procedimento as procorigem";
        $sCamposProcOrigem .= ", ed59_i_ordenacao";
        $sWhereProcOrigem   = " ed59_i_turma = {$turmaorigem} AND ed59_i_serie in ({$etapaorigem})";
        $sSqlProcOrigem     = $clregencia->sql_query( "", $sCamposProcOrigem, "ed59_i_ordenacao", $sWhereProcOrigem );
        $result             = $clregencia->sql_record( $sSqlProcOrigem );

        $procorigem = "";
        if ( $result && pg_num_rows( $result ) > 0 ) {
          $procorigem = pg_result( $result, 0, 'procorigem' );
        }

        $linhas = $clregencia->numrows;

        $sCamposProcDestino  = "ed59_i_codigo as regdestino, ed232_i_codigo as coddestino, ed232_c_descr as descrdestino";
        $sCamposProcDestino .= ", ed220_i_procedimento as procdestino, ed59_i_ordenacao";
        $sWhereProcDestino   = " ed59_i_turma = {$turmadestino} AND ed59_i_serie in ({$codequivorig})";
        $sSqlProcDestino     = $clregencia->sql_query( "", $sCamposProcDestino, "ed59_i_ordenacao", $sWhereProcDestino );
        $result1             = $clregencia->sql_record( $sSqlProcDestino );

        $procdestino = "";
        if ( $result1 && pg_num_rows( $result1 ) > 0 ) {
          $procdestino = pg_result( $result1, 0, 'procdestino' );
        }

        $linhas1     = $clregencia->numrows;
        $regmarcadas = "";

        for ( $t = 0; $t < $linhas; $t++ ) {

         db_fieldsmemory( $result, $t );
         ?>
         <tr>
          <td valign="top" bgcolor="#CCCCCC">
           <input name="regenciaorigem" type="text" value="<?=$ed59_i_codigo?>" size="10" readonly style="width:75px">
           <input name="regorigemdescr" type="text" value="<?=$ed232_c_descr?>" size="30" readonly style="width:180px">
          </td>
          <td align="center">--></td>
          <td>
           <?php
           $temreg = false;
           for( $w = 0; $w < $linhas1; $w++ ) {

             db_fieldsmemory( $result1, $w );

             if( $ed232_i_codigo == $coddestino ) {

               $temreg          = true;
               $regenciadestino = $regdestino;
               $regdestinodescr = $descrdestino;
               $regmarcadas    .= "#".$regdestino."#";
             }
           }

           if( $temreg == true ) {

             ?>
              <input name="regenciadestino" type="text" value="<?=$regenciadestino?>" size="10" readonly style="width:75px">
              <input name="regdestinodescr" type="text" value="<?=$regdestinodescr?>" size="30" readonly style="width:180px">
             <?
           } else {

             $sSelectWhere  = "select ed232_i_codigo                                                    \n";
             $sSelectWhere .= "  from regencia                                                          \n";
             $sSelectWhere .= "       inner join disciplina    on ed12_i_codigo  = ed59_i_disciplina    \n";
             $sSelectWhere .= "       inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina \n";
             $sSelectWhere .= " where ed59_i_turma = {$turmaorigem}                                     \n";
             $sSelectWhere .= "   and ed59_i_serie in ({$etapaorigem})";

             $sql2  = "select ed59_i_codigo as regsobra,trim(ed232_c_descr) as descrsobra       \n";
             $sql2 .= "  from regencia                                                          \n";
             $sql2 .= "       inner join disciplina    on ed12_i_codigo  = ed59_i_disciplina    \n";
             $sql2 .= "       inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina \n";
             $sql2 .= " where ed59_i_turma = {$turmadestino}                                    \n";
             $sql2 .= "   and ed59_i_serie in ($codequivorig)                                   \n";
             $sql2 .= "   and ed232_i_codigo not in ( $sSelectWhere )";

             $result2 = db_query($sql2);
             $linhas2 = pg_num_rows($result2);
             ?>
             <select name="regenciadestino"
                     style="padding:0px;width:75px;height:16px;font-size:12px;"
                     onchange="js_eliminareg(this.value,<?=$t?>)">
               <option value=""></option>
               <?php
               for( $w = 0; $w < $linhas2; $w++ ) {

                 db_fieldsmemory( $result2, $w );
                 echo "<option value='$regsobra'>$regsobra</option>";
               }
               ?>
             </select>
             <select name="regdestinodescr"
                     style="padding:0px;width:180px;height:16px;font-size:12px;"
                     onchange="js_eliminareg(this.value,<?=$t?>)">
               <option value=""></option>
               <?php
               for( $w = 0; $w < $linhas2; $w++ ) {

                 db_fieldsmemory($result2,$w);
                 echo "<option value='$regsobra'>$descrsobra</option>";
               }
               ?>
             </select>
             <input type="hidden" name="combo" value="<?=$t?>">
             <input type="hidden" name="comboselect<?=$t?>" value="">
             <?php
           }
           ?>
         </td>
         <td>
           <table border="1" cellspacing="0" cellpadding="0">
             <tr>
               <?php
               $sCamposDiarioAvaliacao = "ed09_c_abrev, ed72_i_valornota, ed72_c_valorconceito, ed72_t_parecer, ed37_c_tipo";
               $sWhereDiarioAvaliacao  = "     ed95_i_aluno = {$ed60_i_aluno} AND ed95_i_regencia = {$ed59_i_codigo}";
               $sWhereDiarioAvaliacao .= " AND ed09_c_somach = 'S'";
               $sSqlDiarioAvaliacao = $cldiarioavaliacao->sql_query(
                                                                     "",
                                                                     $sCamposDiarioAvaliacao,
                                                                     "ed41_i_sequencia ASC",
                                                                     $sWhereDiarioAvaliacao
                                                                   );
               $result_diario = $cldiarioavaliacao->sql_record( $sSqlDiarioAvaliacao );

               echo "<td width='60px' style='background:#444444;color:#DEB887;font-size:9px;'><b>$ed232_c_abrev</b></td>";

               if( $cldiarioavaliacao->numrows == 0 ) {
                 echo "<td width='160px' style='background:#f3f3f3;'>Nenhum registro.</td>";
               } else {

                 for( $v = 0; $v < $cldiarioavaliacao->numrows; $v++ ) {

                   db_fieldsmemory( $result_diario, $v );

                   if( trim( $ed37_c_tipo ) == "NOTA" ) {

                     if( $resultedu == 'S' ) {
                       $aproveitamento = $ed72_i_valornota != "" ? number_format( $ed72_i_valornota, 2, ",", "." ) : "";
                     } else {
                       $aproveitamento = $ed72_i_valornota != "" ? number_format( $ed72_i_valornota, 0 ) : "";
                     }
                   } else if( trim( $ed37_c_tipo ) == "NIVEL" ) {
                     $aproveitamento = $ed72_c_valorconceito;
                   } else {
                     $aproveitamento = $ed72_t_parecer != "" ? "<font size='1'>Parecer</font>" : "";
                   }
                   echo "<td width='80px' style='background:#f3f3f3;font-size:9px;'><b>$ed09_c_abrev:</b></td>
                         <td width='80px' style='font-size:9px;' align='center'>".($aproveitamento==""?"&nbsp;":$aproveitamento)."</td>";
                 }
               }
               ?>
             </tr>
           </table>
         </td>
        </tr>
         <?php
        }
        ?>
        <tr>
         <td valign="top" bgcolor="#CCCCCC">
          <b>Períodos de Avaliação TURMA DE ORIGEM:</b>
         </td>
         <td width="10"></td>
         <td valign="top" bgcolor="#CCCCCC">
          <b>Períodos de Avaliação  TURMA DE DESTINO:</b>
         </td>
        </tr>
        <?php
        $sCamposProcAvaliacao = "ed41_i_codigo,ed09_i_codigo,ed09_c_descr,ed37_c_tipo,ed37_i_menorvalor,ed37_i_maiorvalor";
        $sSqlProcAvaliacao    = $clprocavaliacao->sql_query(
                                                             "",
                                                             $sCamposProcAvaliacao,
                                                             "ed41_i_sequencia",
                                                             "ed41_i_procedimento = {$procorigem}"
                                                           );
        $result = $clprocavaliacao->sql_record( $sSqlProcAvaliacao );
        $linhas = $clprocavaliacao->numrows;

        $sCamposProcAvaliacaoDestino  = "ed41_i_codigo as codaval, ed09_i_codigo as codperaval, ed09_c_descr as descraval";
        $sCamposProcAvaliacaoDestino .= ", ed37_c_tipo as tipodest, ed37_i_menorvalor as menordest, ed37_i_maiorvalor as maiordest";
        $sWhereProcAvaliacaoDestino   = " ed41_i_procedimento = {$procdestino}";
        $sSqlProcAvaliacaoDestino     = $clprocavaliacao->sql_query(
                                                                     "",
                                                                     $sCamposProcAvaliacaoDestino,
                                                                     "ed41_i_sequencia",
                                                                     $sWhereProcAvaliacaoDestino
                                                                   );
        $result1 = $clprocavaliacao->sql_record( $sSqlProcAvaliacaoDestino );
        $linhas1 = $clprocavaliacao->numrows;

        for ( $t = 0; $t < $linhas; $t++ ) {

          db_fieldsmemory( $result, $t );
          $tipoavaliacao = $ed37_c_tipo . ( $ed37_c_tipo == 'NOTA' ? ' (' . $ed37_i_menorvalor . ' a ' . $ed37_i_maiorvalor . ')' : '' );
          ?>
          <tr>
            <td valign="top" bgcolor="#CCCCCC">
              <input name="periodoorigem" type="text" value="<?=$ed41_i_codigo?>" size="10" readonly style="width:75px">
              <input name="perorigemdescr" type="text" value="<?=$ed09_c_descr.' - '.$tipoavaliacao?>" size="30" readonly style="width:180px">
            </td>
            <td align="center">--></td>
            <td>
            <?php
            $temper = false;

            for( $w = 0; $w < $linhas1; $w++ ) {

              db_fieldsmemory( $result1, $w );

              if( $ed09_i_codigo == $codperaval ) {

                $temper          = true;
                $periododestino  = $codaval;
                $tipoavaliacao1  = $tipodest.($tipodest=='NOTA'?' ('.$menordest.' a '.$maiordest.')':'');
                $perdestinodescr = $descraval.' - '.$tipoavaliacao1;
              }
            }

            if( $temper == true ) {

              ?>
               <input name="periododestino" type="text" value="<?=$periododestino?>" size="10" readonly style="width:75px">
               <input name="perdestinodescr" type="text" value="<?=$perdestinodescr?>" size="30" readonly style="width:180px">
              <?php
            } else {

              $sSelectWhere  = "select ed09_i_codigo                                                                    \n";
              $sSelectWhere .= "  from procavaliacao                                                                    \n";
              $sSelectWhere .= "       inner join periodoavaliacao    on ed09_i_codigo        = ed41_i_periodoavaliacao \n";
              $sSelectWhere .= "       inner join procedimento        on ed40_i_codigo        = ed41_i_procedimento     \n";
              $sSelectWhere .= "       inner join turmaserieregimemat on ed220_i_procedimento = ed40_i_codigo           \n";
              $sSelectWhere .= "       inner join serieregimemat      on ed223_i_codigo       = ed220_i_serieregimemat  \n";
              $sSelectWhere .= "       inner join turma               on ed57_i_codigo        = ed220_i_turma           \n";
              $sSelectWhere .= " where ed57_i_codigo = {$turmaorigem}                                                   \n";
              $sSelectWhere .= "   and ed223_i_serie in ({$etapaorigem})";

              $sql2  = "select ed41_i_codigo as persobra,                                                       \n";
              $sql2 .= "       ed09_c_descr as descrsobra,                                                      \n";
              $sql2 .= "       ed37_c_tipo as tipodest,                                                         \n";
              $sql2 .= "       ed37_i_menorvalor as menordest,                                                  \n";
              $sql2 .= "       ed37_i_maiorvalor as maiordest                                                   \n";
              $sql2 .= "  from procavaliacao                                                                    \n";
              $sql2 .= "       inner join periodoavaliacao    on ed09_i_codigo        = ed41_i_periodoavaliacao \n";
              $sql2 .= "       inner join formaavaliacao      on ed37_i_codigo        = ed41_i_formaavaliacao   \n";
              $sql2 .= "       inner join procedimento        on ed40_i_codigo        = ed41_i_procedimento     \n";
              $sql2 .= "       inner join turmaserieregimemat on ed220_i_procedimento = ed40_i_codigo           \n";
              $sql2 .= "       inner join serieregimemat      on ed223_i_codigo       = ed220_i_serieregimemat  \n";
              $sql2 .= "       inner join turma               on ed57_i_codigo        = ed220_i_turma           \n";
              $sql2 .= " where ed57_i_codigo = {$turmadestino}                                                  \n";
              $sql2 .= "   and ed223_i_serie in ({$etapaorigem})                                                \n";
              $sql2 .= "   and ed09_i_codigo not in ( {$sSelectWhere} )                                         \n";
              $sql2 .= " order by ed41_i_sequencia";
              $result2 = db_query($sql2);
              $linhas2 = pg_num_rows($result2);
              ?>
              <select name="periododestino" style="padding:0px;width:75px;height:16px;font-size:12px;" onchange="js_eliminaper(this.value,<?=$t?>)">
                <option value=""></option>
                <?php
                for( $w = 0; $w < $linhas2; $w++ ) {

                  db_fieldsmemory( $result2, $w );
                  echo "<option value='$persobra'>$persobra</option>";
                }
                ?>
              </select>
              <select name="perdestinodescr" style="padding:0px;width:180px;height:16px;font-size:12px;" onchange="js_eliminaper(this.value,<?=$t?>)">
                <option value=""></option>
                <?php
                for( $w = 0; $w < $linhas2; $w++ ) {

                  db_fieldsmemory( $result2, $w );
                  $tipoavaliacao2 = $tipodest . ( $tipodest == 'NOTA' ? ' (' . $menordest . ' a ' . $maiordest . ')' : '' );
                  echo "<option value='$persobra'>$descrsobra - $tipoavaliacao2</option>";
                }
                ?>
              </select>
              <input type="hidden" name="pcombo" value="<?=$t?>">
              <input type="hidden" name="pcomboselect<?=$t?>" value="">
              <?php
            }
            ?>
            </td>
            <td></td>
          </tr>
          <?php
        }
        ?>
        <tr>
          <td height="10" colspan="3"></td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted69_d_datatransf?>" colspan="3">
            <?=$Led69_d_datatransf?>
            <?php
            $ed69_d_datatransf_dia = isset( $ed69_d_datatransf_dia ) ? $ed69_d_datatransf_dia : "";
            $ed69_d_datatransf_mes = isset( $ed69_d_datatransf_mes ) ? $ed69_d_datatransf_mes : "";
            $ed69_d_datatransf_ano = isset( $ed69_d_datatransf_ano ) ? $ed69_d_datatransf_ano : "";
            db_inputdata(
                          'ed69_d_datatransf',
                          $ed69_d_datatransf_dia,
                          $ed69_d_datatransf_mes,
                          $ed69_d_datatransf_ano,
                          true,
                          'text',
                          1,
                          ""
                        );
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="3">
            <b>Importar aproveitamento da turma de origem:</b>
            <select name="import" id='import' onchange="js_importar(this.value);">
              <option value="S">SIM</option>
              <option value="N">NÃO</option>
            </select>
          </td>
        </tr>
        <?php
        $sCamposTurmaSerieRegimeMat = "ed223_i_serie, ed11_c_descr as descretapa";
        $sSqlTurmaSerieRegimeMat    = $clturmaserieregimemat->sql_query(
                                                                         "",
                                                                         $sCamposTurmaSerieRegimeMat,
                                                                         "ed223_i_ordenacao",
                                                                         "ed220_i_turma = {$turmadestino}"
                                                                       );
        $result_etp = $clturmaserieregimemat->sql_record( $sSqlTurmaSerieRegimeMat );

        if( $clturmaserieregimemat->numrows > 1 ) {

          ?>
          <tr>
            <td colspan="3">
              <?php
              $tem = false;

              for( $c = 0; $c < $clturmaserieregimemat->numrows; $c++ ) {

                db_fieldsmemory( $result_etp, $c );

                if( $ed223_i_serie == $etapaorigem ) {

                  $tem = true;
                  break;
                }
              }

              if( $tem == true ) {

                ?>
                <input name="codetapadestino" type="hidden" value="<?=$ed223_i_serie?>">
                <?php
              } else {

                ?>
                <b>Informe a Etapa na turma de destino:</b>
                <select name="codetapadestino">
                  <?php
                  $sSqlSerieEquiv = $clserieequiv->sql_query( "", "ed234_i_serieequiv", "", "ed234_i_serie = {$etapaorigem}" );
                  $result_equiv   = $clserieequiv->sql_record( $sSqlSerieEquiv );

                  for( $r = 0; $r < $clturmaserieregimemat->numrows; $r++ ) {

                    db_fieldsmemory( $result_etp, $r );

                    $selected = "";
                    $disabled = "disabled";

                    if( $clserieequiv->numrows > 0 ) {

                      for( $w = 0; $w < $clserieequiv->numrows; $w++ ) {

                        db_fieldsmemory( $result_equiv, $w );

                        if( $ed234_i_serieequiv == $ed223_i_serie ) {

                          $selected = "selected";
                          $disabled = "";
                          break;
                        }
                      }
                    }
                    ?>
                    <option value="<?=$ed223_i_serie?>" <?=$selected?> <?=$disabled?>><?=$descretapa?></option>
                    <?php
                  }
                  ?>
                </select>
                <?php
              }
              ?>
            </td>
          </tr>
          <?php
        } else {

          db_fieldsmemory($result_etp,0);
          ?>
          <input name="codetapadestino" type="hidden" value="<?=$ed223_i_serie?>">
          <?php
        }
        ?>
        <tr>
          <td height="10" colspan="3"></td>
        </tr>
        <tr>
          <td colspan="3">
           <?php
           $data = $ed69_d_datatransf_ano . "-" . $ed69_d_datatransf_mes . "-" . $ed69_d_datatransf_dia;
           $sqld = "select (ed52_d_inicio) as inicio,
                           (ed52_d_fim)    as fim
                      from calendario
                           inner join turma on ed57_i_calendario = ed52_i_codigo
                     where ed57_i_codigo= " . $turmaorigem;
           $resultd = db_query( $sqld );
           db_fieldsmemory( $resultd, 0 );
           ?>
           <input type="button"
                  name="incluir"
                  value="Incluir"
                  onclick="js_processar('<?=$inicio?>','<?=$fim?>');"
                  <?=isset($incluir)||isset($incluir2)?"style='visibility:hidden;'":"style='position:absolute;visibility:visible;'"?>>
           <input type="button"
                  name="incluir2"
                  value="Incluir"
                  onclick="js_processar2('<?=$inicio?>','<?=$fim?>');"
                  <?=isset($incluir)||isset($incluir2)?"style='visibility:hidden;'":"style='position:absolute;visibility:hidden;'"?>>
          </td>
        </tr>
      </table>
    </form>
  </body>
</html>
<script>
function js_eliminareg( valor, seq ) {

  C        = document.form1.combo;
  RD       = document.form1.regenciadestino;
  RDC      = document.form1.regdestinodescr;
  tamC     = C.length;
  tamC     = tamC==undefined?1:tamC;
  campo    = "comboselect"+seq;
  valorant = eval("document.form1."+campo+".value");

  if( tamC == 1 ) {

    tamRD = RD.length;

    for( r = 0; r < tamRD; r++ ) {

      if( parseInt(RD.options[r].value) == parseInt(valor) || parseInt(RDC.options[r].value) == parseInt(valor) ) {

        RD.options[r].selected  = true;
        RDC.options[r].selected = true;
      }

      if( parseInt(RD.options[r].value) == parseInt(valorant) || parseInt(RDC.options[r].value) == parseInt(valorant) ) {

        RD.options[r].selected  = false;
        RDC.options[r].selected = false;
      }
    }
  } else {

    for( i = 0; i < tamC; i++ ) {

      tamRD = RD[C[i].value].length;

      if( parseInt(C[i].value) != parseInt(seq) ) {

        for( r = 0; r < tamRD; r++ ) {

          if(    parseInt( RD[C[i].value].options[r].value ) == parseInt( valor )
              || parseInt( RDC[C[i].value].options[r].value ) == parseInt( valor ) ) {

            RD[C[i].value].options[r].disabled  = true;
            RDC[C[i].value].options[r].disabled = true;
          }

          if(    parseInt( RD[C[i].value].options[r].value ) == parseInt( valorant )
              || parseInt( RDC[C[i].value].options[r].value ) == parseInt( valorant ) ) {

            RD[C[i].value].options[r].disabled  = false;
            RDC[C[i].value].options[r].disabled = false;
          }
        }
      } else {

        for( r = 0; r < tamRD; r++ ) {

          if(    parseInt( RD[C[i].value].options[r].value ) == parseInt( valor )
              || parseInt( RDC[C[i].value].options[r].value ) == parseInt( valor ) ) {

            RD[C[i].value].options[r].selected  = true;
            RDC[C[i].value].options[r].selected = true;
          }

          if(    parseInt( RD[C[i].value].options[r].value ) == parseInt( valorant )
              || parseInt( RDC[C[i].value].options[r].value ) == parseInt( valorant ) ) {

            RD[C[i].value].options[r].selected  = false;
            RDC[C[i].value].options[r].selected = false;
          }
        }
      }
    }
  }

  eval("document.form1."+campo+".value = valor");
}

function js_eliminaper( valor, seq ) {

  C        = document.form1.pcombo;
  PD       = document.form1.periododestino;
  PDC      = document.form1.perdestinodescr;
  tamC     = C.length;
  tamC     = tamC == undefined ? 1 : tamC;
  campo    = "pcomboselect" + seq;
  valorant = eval("document.form1."+campo+".value");

  if( tamC == 1 ) {

    tamPD = PD.length;

    for( r = 0; r < tamPD; r++ ) {

      if( parseInt( PD.options[r].value ) == parseInt( valor ) || parseInt( PDC.options[r].value ) == parseInt( valor ) ) {

        PD.options[r].selected  = true;
        PDC.options[r].selected = true;
      }

      if(    parseInt( PD.options[r].value ) == parseInt( valorant )
          || parseInt( PDC.options[r].value ) == parseInt( valorant ) ) {

        PD.options[r].selected  = false;
        PDC.options[r].selected = false;
      }
    }
  } else {

    for( i = 0; i < tamC; i++ ) {

      tamPD = PD[C[i].value].length;

      if( parseInt( C[i].value ) != parseInt( seq ) ) {

        for( r = 0; r < tamPD; r++ ) {

          if(    parseInt( PD[C[i].value].options[r].value ) == parseInt( valor )
              || parseInt( PDC[C[i].value].options[r].value ) == parseInt( valor ) ) {

             PD[C[i].value].options[r].disabled  = true;
             PDC[C[i].value].options[r].disabled = true;
          }

          if(    parseInt( PD[C[i].value].options[r].value ) == parseInt( valorant )
              || parseInt( PDC[C[i].value].options[r].value ) == parseInt( valorant ) ) {

            PD[C[i].value].options[r].disabled  = false;
            PDC[C[i].value].options[r].disabled = false;
          }
        }
      } else {

        for( r = 0; r < tamPD; r++ ) {

          if(    parseInt( PD[C[i].value].options[r].value ) == parseInt( valor )
              || parseInt( PDC[C[i].value].options[r].value ) == parseInt( valor ) ) {

            PD[C[i].value].options[r].selected  = true;
            PDC[C[i].value].options[r].selected = true;
          }

          if(    parseInt( PD[C[i].value].options[r].value ) == parseInt( valorant )
              || parseInt( PDC[C[i].value].options[r].value ) == parseInt( valorant ) ) {

            PD[C[i].value].options[r].selected  = false;
            PDC[C[i].value].options[r].selected = false;
          }
        }
      }
    }
  }

  eval("document.form1."+campo+".value = valor");
}

/**
 * Compara se a data informada é maior que a data atual
 * @param  {Date} dtFormulario
 * @return {boolean}
 */
function js_comparaComDataAtual( dtFormulario ) {

  var dtAtual               = new Date();
  var dtFormularioFormatada = (dtFormulario).split("/");
  var dtTransferencia       = new Date(dtFormularioFormatada[2], dtFormularioFormatada[1]-1, dtFormularioFormatada[0]);

  if ( dtTransferencia > dtAtual ) {
    return false;
  }

  return true;
}

function js_processar( inicio, fim ) {

  var lImportarDisciplinas = $F('import') == 'S' ? true : false;

  if ( document.form1.codetapadestino.value == "" ) {

    alert("Informe a Etapa na turma de destino!");
    return false;
  }

  RO = document.form1.regenciaorigem;
  RD = document.form1.regenciadestino;
  RC = document.form1.regorigemdescr;
  PO = document.form1.periodoorigem;
  PD = document.form1.periododestino;
  PC = document.form1.perorigemdescr;

  tamRO    = RO.length;
  tamRO    = tamRO == undefined ? 1 : tamRO;
  regequiv = "";
  sepreg   = "";
  msgreg   = "Atenção:\nAs informações das seguintes disciplinas não serão transportadas, pois as mesmas não contêm";
  msgreg  += " contêm disciplinas equivalentes na turma de destino:\n\n";
  regnull  = false;

  for ( i = 0; i < tamRO; i++ ) {

    if ( tamRO == 1 ) {

      if ( RD.value != "" ) {

        regequiv += sepreg + RO.value + "|" + RD.value;
        sepreg    = "X";
      } else {

        msgreg += RC.value+"\n";
        regnull = true;
      }
    } else {

      if ( RD[i].value != "" ) {

        regequiv += sepreg + RO[i].value + "|" + RD[i].value;
        sepreg    = "X";
      } else {
        msgreg += RC[i].value + "\n";
        regnull = true;
      }
    }
  }

  tamPO = PO.length;
  tamPO = tamPO == undefined ? 1 : tamPO;

  perequiv = "";
  sepper   = "";
  msgper   = "Atenção:\nAs informações dos seguintes períodos de avaliação não serão transportadas, pois os mesmos não";
  msgper  += " contêm períodos de avaliação equivalentes na turma de destino:\n\n";
  pernull  = false;

  for ( i=0; i < tamPO; i++ ) {

    if ( tamPO == 1 ) {

      if ( PD.value != "" ) {

        perequiv += sepper+PO.value+"|"+PD.value;
        sepper    = "X";
      } else {

        msgper += PC.value + "\n";
        pernull = true;
      }
    } else {

      if ( PD[i].value != "" ) {

        perequiv += sepper + PO[i].value + "|" + PD[i].value;
        sepper    = "X";
      } else {

        msgper += PC[i].value + "\n";
        pernull = true;
      }
    }
  }

  msggeral = "";

  if ( regnull == true ) {
    msggeral += msgreg + "\n";
  }

  if ( pernull == true ) {
    msggeral += msgper;
  }

  tamRO    = RO.length;
  tamRO    = tamRO == undefined ? 1 : tamRO;
  regselec = false;

  for ( t = 0; t < tamRO; t++ ) {

    if ( tamRO == 1 ) {

      if ( RD.value != "" ) {

        regselec = true;
        break;
      }
    } else {

      if ( RD[t].value != "" ) {
        regselec = true;
        break;
      }
    }
  }

  if ( regselec == false && lImportarDisciplinas ) {

    alert("Informe alguma disciplina da turma de destino para receber as informações da origem!");
    return false;
  }

  tamPO    = PO.length;
  tamPO    = tamPO == undefined ? 1 : tamPO;
  perselec = false;

  for ( t = 0; t < tamPO; t++ ) {

    if ( tamPO == 1 ) {

      if ( PD.value != "" ) {

        perselec = true;
        break;
      }
    } else {

      if ( PD[t].value != "" ) {

        perselec = true;
        break;
      }
    }
  }

  if ( perselec == false && lImportarDisciplinas ) {

    alert("Informe algum período de avaliação da turma de destino para receber as informações da origem!");
    return false;
  }

  if ( document.form1.ed69_d_datatransf.value == "" ) {

    alert("Informe a Data da Transferência!");
    return false;
  }

  if ( !js_comparaComDataAtual( document.form1.ed69_d_datatransf.value ) ) {

    alert("Data de Transferência não pode ser maior do que a data atual.");
    return false;
  }

  datat = document.form1.ed69_d_datatransf.value;
  datat = datat.substr(6,4) + "-" + datat.substr(3,2) + "-" + datat.substr(0,2);
  check = js_validata(datat,inicio,fim);

  if ( check == false ) {

    alert("Data da transferência fora do período do calendário!");
    return false;
  }

  datat   = document.form1.ed69_d_datatransf.value;
  datat   = datat.substr(6,4) + "" + datat.substr(3,2) + "" + datat.substr(0,2);
  datamat = parent.document.form1.datamatricula.value;
  datamat = datamat.substr(6,4) + "" + datamat.substr(3,2) + "" + datamat.substr(0,2);

  if ( parseInt(datat) < parseInt(datamat) ) {

    alert("Data da Transferência menor que a Data da Matrícula!");
    return false;
  }

  if ( msggeral != "" ) {

    if ( confirm( msggeral + "\n\nConfirmar Troca de Turma para o aluno?" ) ) {

     document.form1.incluir.style.visibility = "hidden";
     location.href = "edu1_alunotransfturma002.php?incluir"
                                                +"&regequiv="+regequiv
                                                +"&perequiv="+perequiv
                                                +"&matricula=<?=$matricula?>"
                                                +"&turmaorigem=<?=$turmaorigem?>"
                                                +"&turmadestino=<?=$turmadestino?>"
                                                +"&data="+document.form1.ed69_d_datatransf.value
                                                +"&codetapadestino="+document.form1.codetapadestino.value
                                                +"&iMatriculaOrigem=<?=$oGet->iMatriculaOrigem?>"
                                                +"&sTurno=" + $F('sTurno' );
    }
  } else {

   document.form1.incluir.style.visibility = "hidden";
   location.href = "edu1_alunotransfturma002.php?incluir"
                                              +"&regequiv="+regequiv
                                              +"&perequiv="+perequiv
                                              +"&matricula=<?=$matricula?>"
                                              +"&turmaorigem=<?=$turmaorigem?>"
                                              +"&turmadestino=<?=$turmadestino?>"
                                              +"&data="+document.form1.ed69_d_datatransf.value
                                              +"&codetapadestino="+document.form1.codetapadestino.value
                                              +"&iMatriculaOrigem=<?=$oGet->iMatriculaOrigem?>"
                                              +"&sTurno=" + $F('sTurno' );
  }
}

function js_importar( valor ) {

  if( valor == "N" ) {

    document.form1.incluir.style.visibility  = "hidden";
    document.form1.incluir2.style.visibility = "visible";

    var sMensagem  = "Importar aproveitamento da turma de origem está marcado como NÃO. Caso este aluno tenha algum";
        sMensagem += " aproveitamento na turma de origem, este terá quer ser digitado manualmente!";
    alert( sMensagem );
  } else {

    document.form1.incluir.style.visibility = "visible";
    document.form1.incluir2.style.visibility = "hidden";
  }
}

function js_processar2( inicio, fim ) {

  if( document.form1.codetapadestino.value == "" ) {

    alert("Informe a Etapa na turma de destino!");
    return false;
  }

  if( document.form1.ed69_d_datatransf.value == "" ) {

    alert("Informe a data da transferência!");
    return false;
  }

  if ( !js_comparaComDataAtual( document.form1.ed69_d_datatransf.value ) ) {

    alert("Data de Transferência não pode ser maior do que a data atual.");
    return false;
  }

  datat = document.form1.ed69_d_datatransf.value;
  datat = datat.substr(6,4)+"-"+datat.substr(3,2)+"-"+datat.substr(0,2);
  check = js_validata(datat,inicio,fim);

  if( check == false ) {

    alert("Data da transferência fora do período do calendário!");
    return false;
  }

  datat   = document.form1.ed69_d_datatransf.value;
  datat   = datat.substr(6,4)+""+datat.substr(3,2)+""+datat.substr(0,2);
  datamat = parent.document.form1.datamatricula.value;
  datamat = datamat.substr(6,4)+""+datamat.substr(3,2)+""+datamat.substr(0,2);

  if( parseInt( datat ) < parseInt( datamat ) ) {

    alert("Data da Transferência menor que a Data da Matrícula!");
    return false;
  }

  location.href = "edu1_alunotransfturma002.php?incluir2"
                                             +"&matricula=<?=$matricula?>"
                                             +"&turmaorigem=<?=$turmaorigem?>"
                                             +"&turmadestino=<?=$turmadestino?>"
                                             +"&iMatriculaOrigem=<?=$oGet->iMatriculaOrigem?>"
                                             +"&data="+document.form1.ed69_d_datatransf.value
                                             +"&codetapadestino="+document.form1.codetapadestino.value
                                             +"&sTurno=" + $F('sTurno');
}
</script>
 <?php
}

if (isset($incluir) ) {

  $lErroTransacao                    = false;
  $lPossuiMesmoProcedimentoAvaliacao = true;
  $aRegencias                        = explode("X", $regequiv);

  if (isset($import) && $import == 'N') {

    $aRegencias = array();
    $periodos   = array();
  }

  foreach ($aRegencias as $sRegencia) {

    $aPartesRegencia = explode("|", $sRegencia);

    if ( !empty($aPartesRegencia[0]) && !empty($aPartesRegencia[1]) ) {

      $oRegenciaOrigem  = RegenciaRepository::getRegenciaByCodigo( $aPartesRegencia[0] );
      $oRegenciaDestino = RegenciaRepository::getRegenciaByCodigo( $aPartesRegencia[1] );

      if ( !$oRegenciaOrigem->possuiMesmoProcedimentoAvaliacao($oRegenciaDestino) ) {

        $lPossuiMesmoProcedimentoAvaliacao = false;
        break;
      }
    }
  }

  if ( !$lPossuiMesmoProcedimentoAvaliacao ) {

    $sGet  = '';
    $sGet .= "matricula={$matricula}";
    $sGet .= "&turmaorigem={$turmaorigem}";
    $sGet .= "&turmadestino={$turmadestino}";
    if ( isset($codetapaorigem) ) {
      $sGet .= "&codetapaorigem={$codetapaorigem}";
    }
    $sGet .= "&iMatriculaOrigem={$iMatriculaOrigem}";
    $sGet .= "&sTurno={$sTurno}";

    db_msgbox( _M( MENSAGEM_ALUNOTRANSF002 . "procedimento_diferente_entre_regencias") );
    db_redireciona("edu1_alunotransfturma002.php?{$sGet}");
  }

  db_inicio_transacao();

  $sDataBanco     = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
  $sCampos        = "ed60_i_aluno,ed60_c_rfanterior as rfanterior, ed60_i_numaluno";
  $sSqlMatricula  = $clmatricula->sql_query( "", $sCampos, "", " ed60_i_codigo = $matricula" );
  $result         = $clmatricula->sql_record($sSqlMatricula);

  db_fieldsmemory($result,0);

  $iNumAtualAluno         = $ed60_i_numaluno;
  $sWhereMatricula        = " ed60_i_turma = $turmadestino AND ed60_i_aluno = $ed60_i_aluno AND ed60_c_ativa = 'S'";
  $sMatriculaTurmaDestino = $clmatricula->sql_query_file("","ed60_i_codigo as iAlunoComMatriculaNaTurma","", $sWhereMatricula);
  $result0                = $clmatricula->sql_record($sMatriculaTurmaDestino);

  $iAlunoComMatriculaNaTurma = "";

  if ( $clmatricula->numrows > 0 ) {
    db_fieldsmemory($result0,0);
  }

  if ( $iAlunoComMatriculaNaTurma != "" ) {
    $transfmatricula = $iAlunoComMatriculaNaTurma;
  }else{
    $transfmatricula = $matricula;
  }

  $clalunotransfturma->ed69_i_matricula    = $transfmatricula;
  $clalunotransfturma->ed69_i_turmaorigem  = $turmaorigem;
  $clalunotransfturma->ed69_i_turmadestino = $turmadestino;
  $clalunotransfturma->ed69_d_datatransf   = $sDataBanco;
  $clalunotransfturma->incluir(null);

  $sSqlTurma = $clturma->sql_query( "", "ed57_i_calendario, ed57_i_escola", "", "ed57_i_codigo = {$turmadestino}" );
  $result    = $clturma->sql_record( $sSqlTurma );

  db_fieldsmemory( $result, 0 );

  $periodos            = explode("X",$perequiv);
  $msg_conversao       = "";
  $sep_conversao       = "";
  $aRegenciasConverter = array();

  foreach ( $aRegencias as $sRegencia ) {

    $aPartesRegencia = explode("|", $sRegencia);
    $iCodigoRegencia = $aPartesRegencia[1];

    $oDaoDiario              = new cl_diario();
    $sCamposDiarioTurmaIgual = "ed95_i_codigo";
    $sWhereDiarioTurmaIgual  = "     ed60_i_turma = {$turmadestino} AND ed95_i_aluno = {$ed60_i_aluno}";
    $sWhereDiarioTurmaIgual .= " AND ed95_i_regencia = {$iCodigoRegencia}";
    $sSqlDiarioTurmaIgual    = $oDaoDiario->sql_query_diario_classe(null,
                                                                    $sCamposDiarioTurmaIgual,
                                                                    null,
                                                                    $sWhereDiarioTurmaIgual);
    $rsDiarioTurmaIgual      = $oDaoDiario->sql_record($sSqlDiarioTurmaIgual);
    $iTotalDiarioTurmaIgual  = $oDaoDiario->numrows;

    if ( $iTotalDiarioTurmaIgual > 0 ) {

      $iLinhasExclusao = $oDaoDiario->numrows;
      for ( $z = 0; $z < $iLinhasExclusao; $z++ ) {

        $iCodigoDiario = db_utils::fieldsmemory($rsDiarioTurmaIgual, $z)->ed95_i_codigo;
        $clamparo->excluir( "", "ed81_i_diario = {$iCodigoDiario}" );
        $cldiariofinal->excluir( "", "ed74_i_diario = {$iCodigoDiario}" );

        $sWhereParecerResult  = " ed63_i_diarioresultado in( select ed73_i_codigo   \n";
        $sWhereParecerResult .= "                              from diarioresultado \n";
        $sWhereParecerResult .= "                             where ed73_i_diario = {$iCodigoDiario})";

        $clparecerresult->excluir( "", $sWhereParecerResult );

        $sWhereDiarioResultadoRecuperacao  = "ed116_diarioresultado in( select ed73_i_codigo   \n";
        $sWhereDiarioResultadoRecuperacao .= "                            from diarioresultado \n";
        $sWhereDiarioResultadoRecuperacao .= "                           where ed73_i_diario = {$iCodigoDiario})";

        $oDaoDiarioResultadoRecuperacao->excluir( null, $sWhereDiarioResultadoRecuperacao );
        $cldiarioresultado->excluir( "", "ed73_i_diario = {$iCodigoDiario}" );
        $clpareceraval->excluir( "", "ed93_i_diarioavaliacao in (select ed72_i_codigo
                                                                   from diarioavaliacao
                                                                  where ed72_i_diario = {$iCodigoDiario})" );
        $clabonofalta->excluir( "", "ed80_i_diarioavaliacao in (select ed72_i_codigo
                                                                  from diarioavaliacao
                                                                 where ed72_i_diario = {$iCodigoDiario})" );
        $cldiarioavaliacao->excluir( "", "ed72_i_diario = {$iCodigoDiario}" );
        $claprovconselho->excluir( "", "ed253_i_diario = {$iCodigoDiario}" );
        $oDaoDiarioAvaliacaoAlternativa->excluir( "", "ed136_diario = {$iCodigoDiario}" );
        $cldiario->excluir( "", "ed95_i_codigo = {$iCodigoDiario}" );
      }
    }
  }

  for ( $x = 0; $x < count($periodos); $x++ ) {

    $divideperiodos = explode("|",$periodos[$x]);
    $periodoorigem  = $divideperiodos[0];
    $periododestino = $divideperiodos[1];

    $regencias = explode("X", $regequiv);
    for ($r = 0; $r < count($regencias); $r++) {

      $divideregencias  = explode("|",$regencias[$r]);
      $regenciaorigem   = $divideregencias[0];
      $regenciadestino  = $divideregencias[1];

      $oRegenciaOrigem  = RegenciaRepository::getRegenciaByCodigo( $regenciaorigem );
      $oRegenciaDestino = RegenciaRepository::getRegenciaByCodigo( $regenciadestino );

      $aElementosRegenciaOrigem  = $oRegenciaOrigem->getProcedimentoAvaliacao()->getElementos();
      $aElementosRegenciaDestino = $oRegenciaDestino->getProcedimentoAvaliacao()->getElementos();

      $oElementoPeriodoOrigem = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo( $periodoorigem );
      $iOrdemPeridoOrigem     = $oElementoPeriodoOrigem->getOrdemSequencia();

      $oElementoPeriodoDestino = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo( $periododestino );
      $iOrdemPeridoDestino     = $oElementoPeriodoDestino->getOrdemSequencia();

      $oElementoPeriodoRegenciaOrigem  = null;
      $oElementoPeriodoRegenciaDestino = null;

      foreach ($aElementosRegenciaOrigem  as $oPeriodoAvaliacao) {

        if (   $oPeriodoAvaliacao instanceof AvaliacaoPeriodica
            && $oPeriodoAvaliacao->getOrdemSequencia() == $iOrdemPeridoOrigem){
          $oElementoPeriodoRegenciaOrigem = $oPeriodoAvaliacao;
        }
      }

      $periodoorigem  = $oElementoPeriodoRegenciaOrigem->getCodigo();
      $tipoorigem     = $oElementoPeriodoRegenciaOrigem->getFormaDeAvaliacao()->getTipo();
      $mvorigem       = $oElementoPeriodoRegenciaOrigem->getFormaDeAvaliacao()->getMaiorValor();

      foreach ($aElementosRegenciaDestino as $oPeriodoAvaliacao) {

        if (   $oPeriodoAvaliacao instanceof AvaliacaoPeriodica
            && $oPeriodoAvaliacao->getOrdemSequencia() == $iOrdemPeridoDestino){
          $oElementoPeriodoRegenciaDestino = $oPeriodoAvaliacao;
        }
      }

      $periododestino = $oElementoPeriodoRegenciaDestino->getCodigo();
      $tipodestino    = $oElementoPeriodoRegenciaDestino->getFormaDeAvaliacao()->getTipo();
      $mvdestino      = $oElementoPeriodoRegenciaDestino->getFormaDeAvaliacao()->getMaiorValor();


      $sCamposOrigem    = "ed95_i_codigo as coddiarioorigem";
      $sWhereOrigem     = " ed95_i_regencia = $regenciaorigem AND ed95_i_aluno = $ed60_i_aluno";
      $sSqlDiarioOrigem = $cldiario->sql_query_file("",$sCamposOrigem,"",$sWhereOrigem);
      $result11         = $cldiario->sql_record($sSqlDiarioOrigem);

      if ($cldiario->numrows > 0) {
        db_fieldsmemory($result11, 0);
      } else {
        $coddiarioorigem = 0;
      }

      $sCamposDestino    = "ed95_i_codigo";
      $sWhereDestino     = " ed95_i_regencia = $regenciadestino AND ed95_i_aluno = $ed60_i_aluno";
      $sSqlDiarioDestino = $cldiario->sql_query_file("", $sCamposDestino, "", $sWhereDestino);
      $rsDiarioAvaliacao = $cldiario->sql_record($sSqlDiarioDestino);

      if ($cldiario->numrows == 0) {

        $cldiario->ed95_c_encerrado  = "N";
        $cldiario->ed95_i_escola     = $ed57_i_escola;
        $cldiario->ed95_i_calendario = $ed57_i_calendario;
        $cldiario->ed95_i_aluno      = $ed60_i_aluno;
        $cldiario->ed95_i_serie      = $codetapadestino;
        $cldiario->ed95_i_regencia   = $regenciadestino;
        $cldiario->incluir(null);

        $ed95_i_codigo = $cldiario->ed95_i_codigo;
      } else {
        $ed95_i_codigo = db_utils::fieldsMemory($rsDiarioAvaliacao, 0)->ed95_i_codigo;
      }

      $sSqlDiarioAvaliacaoAlternativa = $oDaoDiarioAvaliacaoAlternativa->sql_query_file(
                                                                                         null,
                                                                                         "ed136_procavalalternativa",
                                                                                         null,
                                                                                         "ed136_diario = {$coddiarioorigem}"
                                                                                       );
      $rsDiarioAvaliacaoAlternativa = db_query( $sSqlDiarioAvaliacaoAlternativa );

      if( $rsDiarioAvaliacaoAlternativa && pg_num_rows( $rsDiarioAvaliacaoAlternativa ) > 0 ) {

        $iProcAvalAlternativa = db_utils::fieldsMemory( $rsDiarioAvaliacaoAlternativa, 0 )->ed136_procavalalternativa;

        $oDaoAvaliacaoAlternativa    = new cl_diarioavaliacaoalternativa();
        $sWhereAvaliacaoAlternativa  = "     ed136_diario = {$ed95_i_codigo}";
        $sWhereAvaliacaoAlternativa .= " AND ed136_procavalalternativa = {$iProcAvalAlternativa}";

        $sSqlAvaliacaoAlternativa = $oDaoAvaliacaoAlternativa->sql_query_file( null, "1", null, $sWhereAvaliacaoAlternativa );
        $rsAvaliacaoAlternativa   = db_query( $sSqlAvaliacaoAlternativa );

        if( $rsAvaliacaoAlternativa && pg_num_rows( $rsAvaliacaoAlternativa ) == 0 ) {

          $oDaoDiarioAvaliacaoAlternativa->ed136_diario              = $ed95_i_codigo;
          $oDaoDiarioAvaliacaoAlternativa->ed136_procavalalternativa = $iProcAvalAlternativa;
          $oDaoDiarioAvaliacaoAlternativa->incluir( null );
        }
      }

      $sCamposAmparo  = "ed81_i_codigo as codamparoorigem, ed81_i_justificativa, ed81_i_convencaoamp, ed81_c_todoperiodo";
      $sCamposAmparo .= ", ed81_c_aprovch";

      $sSqlAmparo = $clamparo->sql_query_file( "", $sCamposAmparo, "", "ed81_i_diario = {$coddiarioorigem}");
      $result6    = $clamparo->sql_record( $sSqlAmparo );

      if ($clamparo->numrows > 0) {

        db_fieldsmemory( $result6, 0 );

        $sSqlAmparo = $clamparo->sql_query_file( "", "ed81_i_codigo", "", "ed81_i_diario = {$ed95_i_codigo}" );
        $result7    = $clamparo->sql_record( $sSqlAmparo );

        $clamparo->ed81_i_diario        = $ed95_i_codigo;
        $clamparo->ed81_c_aprovch       = $ed81_c_aprovch;
        $clamparo->ed81_c_todoperiodo   = $ed81_c_todoperiodo;
        $clamparo->ed81_i_justificativa = $ed81_i_justificativa;
        $clamparo->ed81_i_convencaoamp  = $ed81_i_convencaoamp;

        if ($clamparo->numrows == 0) {
          $clamparo->incluir(null);
        } else {

          db_fieldsmemory($result7,0);
          $clamparo->ed81_i_codigo = $ed81_i_codigo;
          $clamparo->alterar($ed81_i_codigo);
        }
      }

      $sSqlDiarioFinal = $cldiariofinal->sql_query_file( "", "ed74_i_diario", "", "ed74_i_diario = {$ed95_i_codigo}" );
      $result9         = $cldiariofinal->sql_record( $sSqlDiarioFinal );

      if ($cldiariofinal->numrows == 0) {

        $cldiariofinal->ed74_i_diario = $ed95_i_codigo;
        $cldiariofinal->incluir(null);
      }

      $sCamposDiarioAvaliacao  = "ed72_i_codigo as codavalorigem, ed72_i_numfaltas, ed72_i_valornota";
      $sCamposDiarioAvaliacao .= ", ed72_c_valorconceito, ed72_t_parecer, ed72_c_aprovmin, ed72_c_amparo, ed72_t_obs";
      $sCamposDiarioAvaliacao .= ", ed72_i_escola, ed72_c_tipo";
      $sWhereDiarioAvaliacao   = " ed72_i_diario = {$coddiarioorigem} AND ed72_i_procavaliacao = {$periodoorigem}";

      $sSqlDiarioAvaliacao = $cldiarioavaliacao->sql_query_file( "", $sCamposDiarioAvaliacao, "", $sWhereDiarioAvaliacao );
      $result3             = $cldiarioavaliacao->sql_record( $sSqlDiarioAvaliacao );

      if ($cldiarioavaliacao->numrows > 0) {
        db_fieldsmemory($result3,0);
      } else {

        $codavalorigem        = "";
        $ed72_i_numfaltas     = null;
        $ed72_i_valornota     = null;
        $ed72_c_valorconceito = "";
        $ed72_t_parecer       = "";
        $ed72_c_aprovmin      = "N";
        $ed72_c_amparo        = "N";
        $ed72_t_obs           = "";
        $ed72_i_escola        = db_getsession("DB_coddepto");
        $ed72_c_tipo          = "M";
      }

      $ed72_c_convertido = "N";
      if (    trim($tipoorigem) != trim($tipodestino)
           || ( trim($tipoorigem) == trim($tipodestino) && $mvorigem != $mvdestino) ) {

        $ed72_c_convertido = "S";
        if ( $ed72_i_valornota == "" && $ed72_c_valorconceito == "" && $ed72_t_parecer == "") {
          $ed72_c_convertido = "N";
        }

        $aRegenciasConverter[$oRegenciaDestino->getCodigo()] = $oRegenciaDestino->getDisciplina()->getNomeDisciplina();
      }

      $sWhereDiarioAvaliacao = " ed72_i_diario = {$ed95_i_codigo} AND ed72_i_procavaliacao = {$periododestino}";
      $sSqlDiarioAvaliacao   = $cldiarioavaliacao->sql_query_file( "", "ed72_i_codigo", "", $sWhereDiarioAvaliacao );
      $result4               = $cldiarioavaliacao->sql_record( $sSqlDiarioAvaliacao );

      $cldiarioavaliacao->ed72_i_diario        = $ed95_i_codigo;
      $cldiarioavaliacao->ed72_i_procavaliacao = $periododestino;
      $cldiarioavaliacao->ed72_i_numfaltas     = $ed72_i_numfaltas;
      $cldiarioavaliacao->ed72_i_valornota     = $ed72_i_valornota;
      $cldiarioavaliacao->ed72_c_valorconceito = $ed72_c_valorconceito;
      $cldiarioavaliacao->ed72_t_parecer       = pg_escape_string($ed72_t_parecer);
      $cldiarioavaliacao->ed72_c_aprovmin      = $ed72_c_aprovmin;
      $cldiarioavaliacao->ed72_c_amparo        = $ed72_c_amparo;
      $cldiarioavaliacao->ed72_t_obs           = $ed72_t_obs;
      $cldiarioavaliacao->ed72_i_escola        = $ed72_i_escola;
      $cldiarioavaliacao->ed72_c_tipo          = $ed72_c_tipo;
      $cldiarioavaliacao->ed72_c_convertido    = $ed72_c_convertido;

      if ($cldiarioavaliacao->numrows == 0) {

        $cldiarioavaliacao->incluir(null);
        $ed72_i_codigo = $cldiarioavaliacao->ed72_i_codigo;
      } else {

        db_fieldsmemory($result4,0);
        $cldiarioavaliacao->ed72_i_codigo        = $ed72_i_codigo;
        $cldiarioavaliacao->alterar($ed72_i_codigo);
      }

      if( $codavalorigem != "" ) {

        $sSqlTransAprov     = $cltransfaprov->sql_query_file(
                                                              "",
                                                              "ed251_i_codigo",
                                                              "",
                                                              "ed251_i_diariodestino = {$codavalorigem}"
                                                            );
        $result_transfaprov = $cltransfaprov->sql_record( $sSqlTransAprov );

        if ($cltransfaprov->numrows > 0) {

          db_fieldsmemory($result_transfaprov,0);
          $cltransfaprov->ed251_i_diariodestino = $ed72_i_codigo;
          $cltransfaprov->ed251_i_codigo        = $ed251_i_codigo;
          $cltransfaprov->alterar($ed251_i_codigo);
        } else {

          if($ed72_c_convertido == "S") {

            $cltransfaprov->ed251_i_diariodestino = $ed72_i_codigo;
            $cltransfaprov->ed251_i_diarioorigem  = $codavalorigem;
            $cltransfaprov->incluir(null);
          }
        }
      }

      if ($codavalorigem != "") {

        $sSqlPareceres = $clpareceraval->sql_query_file(
                                                         "",
                                                         "ed93_t_parecer",
                                                         "",
                                                         "ed93_i_diarioavaliacao = {$codavalorigem}"
                                                       );

        $result41 = $clpareceraval->sql_record($sSqlPareceres);
        $linhas41 = $clpareceraval->numrows;
        if($linhas41 > 0) {

          $clpareceraval->excluir(""," ed93_i_diarioavaliacao = $ed72_i_codigo");
          for($w = 0; $w <$linhas41; $w++) {

            db_fieldsmemory($result41,$w);
            $clpareceraval->ed93_i_diarioavaliacao = $ed72_i_codigo;
            $clpareceraval->ed93_t_parecer         = $ed93_t_parecer;
            $clpareceraval->incluir(null);
          }
        }

        $sSqlAbonoFalta = $clabonofalta->sql_query_file(
                                                         "",
                                                         "ed80_i_codigo",
                                                         "",
                                                         "ed80_i_diarioavaliacao = {$codavalorigem}"
                                                       );
        $result42 = $clabonofalta->sql_record( $sSqlAbonoFalta );
        $linhas42 = $clabonofalta->numrows;

        if( $linhas42 > 0 ) {

          for( $w = 0; $w < $linhas42; $w++ ) {

            db_fieldsmemory( $result42, $w );

            $clabonofalta->ed80_i_diarioavaliacao = $ed72_i_codigo;
            $clabonofalta->ed80_i_codigo          = $ed80_i_codigo;
            $clabonofalta->alterar($ed80_i_codigo);
          }
        }
      }
    }
  }

  $sCamposTurmaOrigem  = "ed57_c_descr as ed57_c_descrorig, ed57_i_base as baseorig, ed57_i_calendario as calorig";
  $sCamposTurmaOrigem .= ", ed57_i_turno as turnoorig, ed10_i_codigo as ensinorigem";

  $sSqlTurmaOrigem = $clturma->sql_query( "", $sCamposTurmaOrigem, "", "ed57_i_codigo = {$turmaorigem}" );
  $result_orig     = $clturma->sql_record( $sSqlTurmaOrigem );

  db_fieldsmemory( $result_orig, 0 );

  $sSqlAlunoCurso = $clalunocurso->sql_query_file( "", "ed56_i_codigo", "", "ed56_i_aluno = {$ed60_i_aluno}" );
  $result_alu     = $clalunocurso->sql_record( $sSqlAlunoCurso );

  db_fieldsmemory( $result_alu, 0 );

  $sCamposTurmaDestino  = "ed57_c_descr as ed57_c_descrdest, ed57_i_base as basedest, ed57_i_calendario as caldest";
  $sCamposTurmaDestino .= ", ed57_i_turno as turnodest, ed10_i_codigo as ensinodestino";

  $sSqlTurmaDestino = $clturma->sql_query( "", $sCamposTurmaDestino, "", "ed57_i_codigo = {$turmadestino}" );
  $result_dest      = $clturma->sql_record( $sSqlTurmaDestino );

  db_fieldsmemory( $result_dest, 0 );

  $sSqlMatricula = $clmatricula->sql_query_file( "", "max(ed60_i_numaluno)", "", "ed60_i_turma = {$turmadestino}" );
  $result1       = $clmatricula->sql_record( $sSqlMatricula );

  db_fieldsmemory( $result1, 0 );

  $max = $max == "" ? "null" : ( $max + 1 );

  $ed60_d_datamodif = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);

  $sSqlTurmaSerieRegimeMat = $clturmaserieregimemat->sql_query(
                                                                "",
                                                                "ed223_i_serie",
                                                                "ed223_i_ordenacao",
                                                                "ed220_i_turma = {$turmadestino}"
                                                              );
  $result_etp = $clturmaserieregimemat->sql_record( $sSqlTurmaSerieRegimeMat );

  if( $ensinorigem == $ensinodestino ) {

    if (empty($oGet->iMatriculaOrigem)) {
      $oGet->iMatriculaOrigem = null;
    }

    if ($iAlunoComMatriculaNaTurma!="") {

      $sql  = " UPDATE matricula                            ";
      $sql .= "    SET ed60_c_situacao  = 'TROCA DE TURMA', ";
      $sql .= "        ed60_c_ativa     = 'N',              ";
      $sql .= "        ed60_d_datasaida = '".$sDataBanco."'";
      $sql .= "  WHERE ed60_i_codigo    = {$iAlunoComMatriculaNaTurma}";
      $query = db_query($sql);

      trocaTurma($matricula, $turmadestino, false, $oGet->iMatriculaOrigem, $sTurno);
      $matrmov = $iAlunoComMatriculaNaTurma;
      LimpaResultadofinal($iAlunoComMatriculaNaTurma);
    } else {

      $sql  = " UPDATE matricula                            ";
      $sql .= "    SET ed60_c_situacao  = 'TROCA DE TURMA', ";
      $sql .= "        ed60_c_ativa     = 'N',              ";
      $sql .= "        ed60_d_datasaida = '".$sDataBanco."', ";
      $sql .= "        ed60_d_datamodif = '".$sDataBanco."'";
      $sql .= "  WHERE ed60_i_codigo    = {$matricula}      ";
      $query = db_query($sql);
      $matrmov = $matricula;

      trocaTurma($matricula, $turmadestino, false, $oGet->iMatriculaOrigem, $sTurno);
      LimpaResultadofinal($matricula);
    }
  } else {     ///termina ensino igual else

    $sql   = "UPDATE matricula                                    \n";
    $sql  .= "   SET ed60_d_datamodif    = '{$ed60_d_datamodif}', \n";
    $sql  .= "       ed60_c_situacao     = 'TROCA DE MODALIDADE', \n";
    $sql  .= "       ed60_d_datamodifant = '{$ed60_d_datamodif}', \n";
    $sql  .= "       ed60_d_datasaida    = '{$sDataBanco}',       \n";
    $sql  .= "       ed60_c_concluida    = 'S'                    \n";
    $sql  .= " WHERE ed60_i_codigo = {$matricula}";
    $query = db_query($sql);

    $sql21    = "UPDATE diario                                                    \n";
    $sql21   .= "   SET ed95_c_encerrado = 'S'                                    \n";
    $sql21   .= " WHERE ed95_i_regencia in (select ed59_i_codigo                  \n";
    $sql21   .= "                             from regencia                       \n";
    $sql21   .= "                            where ed59_i_turma = {$turmaorigem}) \n";
    $sql21   .= "   AND ed95_i_aluno = {$ed60_i_aluno}";
    $result21 = db_query($sql21);

    LimpaResultadofinal($matricula);

    $clmatricula->ed60_d_datamodif     = $sDataBanco;
    $clmatricula->ed60_d_datamatricula = $sDataBanco;
    $clmatricula->ed60_d_datamodifant  = null;
    $clmatricula->ed60_d_datasaida     = null;
    $clmatricula->ed60_t_obs           = "";
    $clmatricula->ed60_i_aluno         = $ed60_i_aluno;
    $clmatricula->ed60_i_turma         = $turmadestino;
    $clmatricula->ed60_i_turmaant      = $turmaorigem;
    $clmatricula->ed60_c_rfanterior    = $rfanterior;
    $clmatricula->ed60_i_numaluno      = $max;
    $clmatricula->ed60_c_situacao      = "MATRICULADO";
    $clmatricula->ed60_c_concluida     = "N";
    $clmatricula->ed60_c_ativa         = "S";
    $clmatricula->ed60_c_tipo          = "N";
    $clmatricula->ed60_c_parecer       = "N";
    $clmatricula->ed60_matricula       = $oGet->iMatriculaOrigem;
    $clmatricula->incluir(null);

    $matrmov = $clmatricula->ed60_i_codigo;

    $oTurmaDestino    = TurmaRepository::getTurmaByCodigo( $turmadestino );
    $aVagasTurma      = $oTurmaDestino->getVagasDisponiveis();
    $lVagaDisponivel  = true;
    $aTurnosValidacao = explode( ',', $aTurnosSelecionados );

    foreach( $aTurnosValidacao as $iTurno ) {

      if( !array_key_exists( $iTurno, $aVagasTurma ) ) {
        continue;
      }

      if( $aVagasTurma[ $iTurno ] <= 0 ) {
        $lVagaDisponivel = false;
      }
    }

    if ( !$lVagaDisponivel ) {

      $lErroTransacao  = true;
      $sMensagem       = "Não há vagas para o(s) turno(s) selecionado(s).";
      db_msgbox( $sMensagem );
    }

    $sWhereTurmaTurnoReferente = "ed336_turma = {$turmadestino} AND ed336_turnoreferente in ( {$sTurno} )";
    $sSqlTurmaTurnoReferente = $oDaoTurmaTurnoReferente->sql_query_file(
                                                                         null,
                                                                         "ed336_codigo",
                                                                         null,
                                                                         $sWhereTurmaTurnoReferente
                                                                       );
    $rsTurmaTurnoReferente     = db_query( $sSqlTurmaTurnoReferente );
    $iTotalTurmaTurnoReferente = pg_num_rows( $rsTurmaTurnoReferente );

    for( $iContador = 0; $iContador < $iTotalTurmaTurnoReferente; $iContador++ ) {

      $iCodigoTurmaTurnoReferente = db_utils::fieldsMemory( $rsTurmaTurnoReferente, $iContador )->ed336_codigo;
      $oDaoMatriculaTurnoReferente->ed337_matricula           = $matrmov;
      $oDaoMatriculaTurnoReferente->ed337_turmaturnoreferente = $iCodigoTurmaTurnoReferente;
      $oDaoMatriculaTurnoReferente->incluir( null );
    }

    for( $rr = 0; $rr < $clturmaserieregimemat->numrows; $rr++ ) {

      db_fieldsmemory( $result_etp, $rr );

      if ($codetapadestino == $ed223_i_serie) {
       $origem = "S";
      }else{
       $origem = "N";
      }

      $clmatriculaserie->ed221_i_matricula = $matrmov;
      $clmatriculaserie->ed221_i_serie     = $ed223_i_serie;
      $clmatriculaserie->ed221_c_origem    = $origem;
      $clmatriculaserie->incluir(null);
    }
  }

  if ($ensinorigem == $ensinodestino) {

    $sDescricao  = "ALUNO TROCOU DE TURMA, PASSANDO DA TURMA ".trim($ed57_c_descrorig)." PARA A TURMA ";
    $sDescricao .= trim($ed57_c_descrdest);

    $clmatriculamov->ed229_c_procedimento = "TROCAR ALUNO DE TURMA";
    $clmatriculamov->ed229_t_descr        = $sDescricao;
  } else {

    $sDescricao  = "ALUNO TROCOU DE MODALIDADE, PASSANDO DA TURMA ".trim($ed57_c_descrorig)." PARA A TURMA ";
    $sDescricao .= trim($ed57_c_descrdest);

    $clmatriculamov->ed229_c_procedimento = "TROCAR ALUNO DE MODALIDADE";
    $clmatriculamov->ed229_t_descr        = $sDescricao;
  }

  $clmatriculamov->ed229_i_matricula  = $matrmov;
  $clmatriculamov->ed229_i_usuario    = db_getsession("DB_id_usuario");
  $clmatriculamov->ed229_d_dataevento = $sDataBanco;
  $clmatriculamov->ed229_c_horaevento = date("H:i");
  $clmatriculamov->ed229_d_data       = date("Y-m-d",db_getsession("DB_datausu"));
  $clmatriculamov->incluir(null);

  $sql_alunocurso    = "UPDATE alunocurso                       \n";
  $sql_alunocurso   .= "   SET ed56_i_base       = {$basedest}, \n";
  $sql_alunocurso   .= "       ed56_i_calendario = {$caldest}   \n";
  $sql_alunocurso   .= " WHERE ed56_i_codigo = {$ed56_i_codigo}";
  $result_alunocurso = db_query($sql_alunocurso);

  $sql_alunopossib    = "UPDATE alunopossib                        \n";
  $sql_alunopossib   .= "   SET ed79_i_serie = {$codetapadestino}, \n";
  $sql_alunopossib   .= "       ed79_i_turno = {$turnodest}        \n";
  $sql_alunopossib   .= " WHERE ed79_i_alunocurso = {$ed56_i_codigo}";
  $result_alunopossib = db_query($sql_alunopossib);

  if (!empty($iNumAtualAluno)) {

    $oDaoEduNumalunoBloqueado                   = new cl_edu_numalunobloqueado();
    $oDaoEduNumalunoBloqueado->ed289_i_numaluno = $iNumAtualAluno;
    $oDaoEduNumalunoBloqueado->ed289_i_turma    = $turmaorigem;
    $oDaoEduNumalunoBloqueado->incluir(null);
  }

  db_fim_transacao( $lErroTransacao );

  if ( !$lErroTransacao && $msg_conversao != "") {

    $mensagem  = "ATENÇÃO!\\n\\n Caso o aluno tenha algum aproveitamento nos períodos abaixo relacionados, os mesmos";
    $mensagem .= " deverão ser convertidos no Diário de Classe, devido a forma de avaliação da turma de origem ser";
    $mensagem .= " diferente da turma de destino:\\n\\n{$msg_conversao}";

    db_msgbox( $mensagem );
  }

  if( $clalunotransfturma->erro_status == 0 ) {
    $clalunotransfturma->erro( true, false );
  } else {

    $sMsgSucesso = "Troca de turma realizada com sucesso.\n";
    $oMatricula  = MatriculaRepository::getMatriculaByCodigo($matricula);

    if( count( $oMatricula->getAluno()->getProgressaoParcial() ) > 0 ) {

      $sMsgSucesso .= "Aluno {$oMatricula->getAluno()->getNome()} com Progressão Parcial ATIVA. \n";
      $sMsgSucesso .= "Acesse:\n";
      $sMsgSucesso .= "\tMatrícula > Progressão Parcial > Ativar / Inativar: para alterar a situação da progressão parcial\n";
      $sMsgSucesso .= "\tMatrícula > Progressão Parcial > Vincular Aluno / Turma: para vincular a progressão do aluno";
      $sMsgSucesso .= " em uma turma";
    }

    db_msgbox($sMsgSucesso);
  }

  ?><script>parent.location.href = "edu1_alunotransfturma001.php";</script><?
}

if( isset( $incluir2 ) ) {

  db_inicio_transacao();

  $sDataBanco = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);

  $sCamposMatricula = "ed60_i_aluno, ed60_c_rfanterior as rfanterior";
  $sSqlMatricula    = $clmatricula->sql_query( "", $sCamposMatricula, "", "ed60_i_codigo = {$matricula}" );
  $result           = $clmatricula->sql_record( $sSqlMatricula );

  db_fieldsmemory( $result, 0 );

  $sWhereMatricula = " ed60_i_turma = {$turmadestino} AND ed60_i_aluno = {$ed60_i_aluno} AND ed60_c_ativa = 'S'";

  $sSqlMatricula = $clmatricula->sql_query_file( "", "ed60_i_codigo as iAlunoComMatriculaNaTurma", "", $sWhereMatricula );
  $result0       = $clmatricula->sql_record( $sSqlMatricula );

  $iAlunoComMatriculaNaTurma = "";

  if( $clmatricula->numrows > 0 ) {

    db_fieldsmemory( $result0, 0 );
    $iAlunoComMatriculaNaTurma = $ialunocommatriculanaturma;
  }

  if( $iAlunoComMatriculaNaTurma !="" ) {
    $transfmatricula = $iAlunoComMatriculaNaTurma;
  } else {
    $transfmatricula = $matricula;
  }

  $clalunotransfturma->ed69_i_matricula    = $transfmatricula;
  $clalunotransfturma->ed69_i_turmaorigem  = $turmaorigem;
  $clalunotransfturma->ed69_i_turmadestino = $turmadestino;
  $clalunotransfturma->ed69_d_datatransf   = $sDataBanco;
  $clalunotransfturma->incluir(null);

  $sCamposTurma = "ed57_i_calendario, ed57_i_escola";
  $sSqlTurma    = $clturma->sql_query( "", $sCamposTurma, "", "ed57_i_codigo = {$turmadestino}" );
  $result       = $clturma->sql_record( $sSqlTurma );

  db_fieldsmemory( $result, 0 );

  $sCamposTurmaOrigem  = "ed57_c_descr as ed57_c_descrorig, ed57_i_base as baseorig, ed57_i_calendario as calorig";
  $sCamposTurmaOrigem .= ", ed57_i_turno as turnoorig, ed10_i_codigo as ensinorigem";

  $sTurmaOrigem = $clturma->sql_query( "", $sCamposTurmaOrigem, "", "ed57_i_codigo = {$turmaorigem}" );
  $result_orig  = $clturma->sql_record( $sTurmaOrigem );

  db_fieldsmemory( $result_orig, 0 );

  $sSqlAlunoCurso = $clalunocurso->sql_query_file( "", "ed56_i_codigo", "", "ed56_i_aluno = {$ed60_i_aluno}" );
  $result_alu     = $clalunocurso->sql_record( $sSqlAlunoCurso );

  db_fieldsmemory( $result_alu, 0 );

  $sCamposTurmaDestino  = "ed57_c_descr as ed57_c_descrdest, ed57_i_base as basedest, ed57_i_calendario as caldest";
  $sCamposTurmaDestino .= ", ed57_i_turno as turnodest, ed10_i_codigo as ensinodestino";

  $sSqlTurmaDestino = $clturma->sql_query( "", $sCamposTurmaDestino, "", "ed57_i_codigo = {$turmadestino}" );
  $result_dest      = $clturma->sql_record( $sSqlTurmaDestino );

  db_fieldsmemory( $result_dest, 0 );

  $sSqlMatricula1 = $clmatricula->sql_query_file( "", "max(ed60_i_numaluno)", "", "ed60_i_turma = {$turmadestino}" );
  $result1        = $clmatricula->sql_record( $sSqlMatricula1 );

  db_fieldsmemory( $result1, 0 );

  $max              = $max == "" ? "null" : ( $max + 1 );
  $ed60_d_datamodif = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);

  $sSqlTurmaSerieRegimeMat = $clturmaserieregimemat->sql_query(
                                                                "",
                                                                "ed223_i_serie",
                                                                "ed223_i_ordenacao",
                                                                "ed220_i_turma = {$turmadestino}"
                                                              );
  $result_etp = $clturmaserieregimemat->sql_record( $sSqlTurmaSerieRegimeMat );

  if( $ensinorigem == $ensinodestino ) {

    $sWhereDiario  = "     ed95_i_aluno = $ed60_i_aluno ";
    $sWhereDiario .= " AND ed95_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = {$turmaorigem})";

    $sSqlDiario = $cldiario->sql_query_file( "", "ed95_i_codigo as coddiariodel", "", $sWhereDiario );
    $result_del = $cldiario->sql_record( $sSqlDiario );
    $linhas_del = $cldiario->numrows;

    for( $z = 0; $z < $linhas_del; $z++ ) {

      db_fieldsmemory($result_del,$z);
      $clamparo->excluir( "", "ed81_i_diario = {$coddiariodel}" );
      $cldiariofinal->excluir( "", "ed74_i_diario = {$coddiariodel}" );

      $sWhereDiarioResultado  = "ed63_i_diarioresultado in (select ed73_i_codigo   \n";
      $sWhereDiarioResultado .= "                             from diarioresultado \n";
      $sWhereDiarioResultado .= "                            where ed73_i_diario = {$coddiariodel})";

      $clparecerresult->excluir( "", $sWhereDiarioResultado );
      $cldiarioresultado->excluir( "", "ed73_i_diario = {$coddiariodel}" );

      $sWhereDiarioAvaliacao  = "ed93_i_diarioavaliacao in (select ed72_i_codigo   \n";
      $sWhereDiarioAvaliacao .= "                             from diarioavaliacao \n";
      $sWhereDiarioAvaliacao .= "                            where ed72_i_diario = {$coddiariodel})";

      $clpareceraval->excluir( "", $sWhereDiarioAvaliacao );

      $sWhereAbonoFalta  = "ed80_i_diarioavaliacao in (select ed72_i_codigo   \n";
      $sWhereAbonoFalta .= "                             from diarioavaliacao \n";
      $sWhereAbonoFalta .= "                            where ed72_i_diario = {$coddiariodel})";

      $clabonofalta->excluir( "", $sWhereAbonoFalta );
      $cldiarioavaliacao->excluir( "", "ed72_i_diario = {$coddiariodel}" );
      $claprovconselho->excluir( "", "ed253_i_diario = {$coddiariodel}" );
     $oDaoDiarioAvaliacaoAlternativa->excluir( "", "ed136_diario = {$coddiariodel}" );
      $cldiario->excluir( "", "ed95_i_codigo = {$coddiariodel}" );
    }

    if (empty($oGet->iMatriculaOrigem)) {
      $oGet->iMatriculaOrigem = null;
    }

    if ($iAlunoComMatriculaNaTurma != "") {

      $sql   = " UPDATE matricula                            ";
      $sql  .= "    SET ed60_c_situacao  = 'TROCA DE TURMA', ";
      $sql  .= "        ed60_c_ativa     = 'N',              ";
      $sql  .= "        ed60_d_datasaida = '".$sDataBanco."'";
      $sql  .= "  WHERE ed60_i_codigo    = {$iAlunoComMatriculaNaTurma}";
      $query = db_query($sql);

      trocaTurma($matricula, $turmadestino, false, $oGet->iMatriculaOrigem, $sTurno);
      LimpaResultadofinal($iAlunoComMatriculaNaTurma);
      $matrmov = $matricula;
    } else {

      $sql   = " UPDATE matricula                            ";
      $sql  .= "    SET ed60_c_situacao  = 'TROCA DE TURMA', ";
      $sql  .= "        ed60_c_ativa     = 'N',              ";
      $sql  .= "        ed60_d_datasaida = '".$sDataBanco."', ";
      $sql  .= "        ed60_d_datamodif = '".$sDataBanco."'";
      $sql  .= "  WHERE ed60_i_codigo    = {$matricula}      ";
      $query = db_query($sql);

      trocaTurma($matricula, $turmadestino, false, $oGet->iMatriculaOrigem, $sTurno);
      LimpaResultadofinal($matricula);
      $matrmov = $matricula;
    }
  } else{     ///termina ensino igual else

    $sql   = "UPDATE matricula                                    \n";
    $sql  .= "   SET ed60_d_datamodif    = '{$ed60_d_datamodif}', \n";
    $sql  .= "       ed60_c_situacao     = 'TROCA DE MODALIDADE', \n";
    $sql  .= "       ed60_d_datamodifant = '{$ed60_d_datamodif}', \n";
    $sql  .= "       ed60_d_datasaida    = '{$sDataBanco}',       \n";
    $sql  .= "       ed60_c_concluida    = 'S'                    \n";
    $sql  .= " WHERE ed60_i_codigo = {$matricula}";
    $query = db_query($sql);

    $sql21    = "UPDATE diario                                                    \n";
    $sql21   .= "   SET ed95_c_encerrado = 'S'                                    \n";
    $sql21   .= " WHERE ed95_i_regencia in (select ed59_i_codigo                  \n";
    $sql21   .= "                             from regencia                       \n";
    $sql21   .= "                            where ed59_i_turma = {$turmaorigem}) \n";
    $sql21   .= "   AND ed95_i_aluno = {$ed60_i_aluno}";
    $result21 = db_query( $sql21 );

    LimpaResultadofinal($matricula);

    $clmatricula->ed60_d_datamodif     = $sDataBanco;
    $clmatricula->ed60_d_datamatricula = $sDataBanco;
    $clmatricula->ed60_d_datamodifant  = null;
    $clmatricula->ed60_d_datasaida     = null;
    $clmatricula->ed60_t_obs           = "";
    $clmatricula->ed60_i_aluno         = $ed60_i_aluno;
    $clmatricula->ed60_i_turma         = $turmadestino;
    $clmatricula->ed60_i_turmaant      = $turmaorigem;
    $clmatricula->ed60_c_rfanterior    = $rfanterior;
    $clmatricula->ed60_i_numaluno      = $max;
    $clmatricula->ed60_c_situacao      = "MATRICULADO";
    $clmatricula->ed60_c_concluida     = "N";
    $clmatricula->ed60_c_ativa         = "S";
    $clmatricula->ed60_c_tipo          = "N";
    $clmatricula->ed60_matricula       = $oGet->iMatriculaOrigem;
    $clmatricula->ed60_c_parecer       = "N";
    $clmatricula->incluir(null);

    $matrmov = $clmatricula->ed60_i_codigo;

    $sWhereTurmaTurnoReferente = "ed336_turma = {$turmadestino} AND ed336_turnoreferente in ( {$sTurno} )";
    $sSqlTurmaTurnoReferente   = $oDaoTurmaTurnoReferente->sql_query_file(
                                                                           null,
                                                                           "ed336_codigo",
                                                                           null,
                                                                           $sWhereTurmaTurnoReferente
                                                                         );
    $rsTurmaTurnoReferente     = db_query( $sSqlTurmaTurnoReferente );
    $iTotalTurmaTurnoReferente = pg_num_rows( $rsTurmaTurnoReferente );

    for( $iContador = 0; $iContador < $iTotalTurmaTurnoReferente; $iContador++ ) {

      $iCodigoTurmaTurnoReferente = db_utils::fieldsMemory( $rsTurmaTurnoReferente, $iContador )->ed336_codigo;
      $oDaoMatriculaTurnoReferente->ed337_matricula           = $matrmov;
      $oDaoMatriculaTurnoReferente->ed337_turmaturnoreferente = $iCodigoTurmaTurnoReferente;
      $oDaoMatriculaTurnoReferente->incluir( null );
    }

    for( $rr = 0; $rr < $clturmaserieregimemat->numrows; $rr++ ) {

      db_fieldsmemory( $result_etp, $rr );

      if( $codetapadestino == $ed223_i_serie ) {
        $origem = "S";
      } else {
        $origem = "N";
      }

      $clmatriculaserie->ed221_i_matricula = $matrmov;
      $clmatriculaserie->ed221_i_serie     = $ed223_i_serie;
      $clmatriculaserie->ed221_c_origem    = $origem;
      $clmatriculaserie->incluir(null);
    }
  }

  if( $ensinorigem == $ensinodestino ) {

    $sDescricao  = "ALUNO TROCOU DE TURMA, PASSANDO DA TURMA " . trim( $ed57_c_descrorig ) . " PARA A TURMA ";
    $sDescricao .= trim( $ed57_c_descrdest );

    $clmatriculamov->ed229_c_procedimento = "TROCAR ALUNO DE TURMA";
    $clmatriculamov->ed229_t_descr        = $sDescricao;
  } else {

    $sDescricao  = "ALUNO TROCOU DE MODALIDADE, PASSANDO DA TURMA " . trim( $ed57_c_descrorig ) . " PARA A TURMA ";
    $sDescricao .= trim( $ed57_c_descrdest );

    $clmatriculamov->ed229_c_procedimento = "TROCAR ALUNO DE MODALIDADE";
    $clmatriculamov->ed229_t_descr        = $sDescricao;
  }

  $clmatriculamov->ed229_i_matricula  = $matrmov;
  $clmatriculamov->ed229_i_usuario    = db_getsession("DB_id_usuario");
  $clmatriculamov->ed229_d_dataevento = $sDataBanco;
  $clmatriculamov->ed229_c_horaevento = date("H:i");
  $clmatriculamov->ed229_d_data       = date("Y-m-d",db_getsession("DB_datausu"));
  $clmatriculamov->incluir(null);

  $sql_alunocurso    = "UPDATE alunocurso                       \n";
  $sql_alunocurso   .= "   SET ed56_i_base       = {$basedest}, \n";
  $sql_alunocurso   .= "       ed56_i_calendario = {$caldest}   \n";
  $sql_alunocurso   .= " WHERE ed56_i_codigo = {$ed56_i_codigo}";
  $result_alunocurso = db_query( $sql_alunocurso );

  $sql_alunopossib    = "UPDATE alunopossib                        \n";
  $sql_alunopossib   .= "   SET ed79_i_serie = {$codetapadestino}, \n";
  $sql_alunopossib   .= "       ed79_i_turno = {$turnodest}        \n";
  $sql_alunopossib   .= " WHERE ed79_i_alunocurso = {$ed56_i_codigo}";
  $result_alunopossib = db_query($sql_alunopossib);

  db_fim_transacao();

  if( $clalunotransfturma->erro_status == 0 ) {
    $clalunotransfturma->erro(true,false);
  } else {

    $sMsgSucesso = "Troca de turma realizada com sucesso.\n";
    $oMatricula  = MatriculaRepository::getMatriculaByCodigo($matricula);

    if ( count( $oMatricula->getAluno()->getProgressaoParcial() ) > 0 ) {

      $sMsgSucesso .= "Aluno {$oMatricula->getAluno()->getNome()} com Progressão Parcial ATIVA. \n";
      $sMsgSucesso .= "Acesse:\n";
      $sMsgSucesso .= "\tMatrícula > Progressão Parcial > Ativar / Inativar: para alterar a situação da progressão parcial\n";
      $sMsgSucesso .= "\tMatrícula > Progressão Parcial > Vincular Aluno / Turma: para vincular a progressão do aluno";
      $sMsgSucesso .= " em uma turma";
    }

    db_msgbox($sMsgSucesso);
  }

  ?>
  <script>
  parent.location.href = "edu1_alunotransfturma001.php";
  </script>
  <?php
}
?>
<script>document.getElementById("tab_aguarde").style.visibility = "hidden";</script>