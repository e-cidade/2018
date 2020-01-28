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

require_once(modification("fpdf151/pdfwebseller.php"));
require_once(modification("libs/db_utils.php"));

$oDaoMatricula       = new cl_matricula();
$oDaoCalendario      = new cl_calendario();
$oDaoEduParametros   = new cl_edu_parametros();
$oDaoRegenteConselho = new cl_regenteconselho();
$oDaoTurma           = new cl_turma();
$oDaoEscola          = new cl_escola();
$oDaoTipoSanguineo   = new cl_tiposanguineo();
$iEscola             = db_getsession("DB_coddepto");
$cabecalho           = utf8_decode(base64_decode($cabecalho));
$campos              = base64_decode($campos);

$sCampos  = "distinct                                                              \n";
$sCampos .= "ed57_i_codigo, ed57_c_descr, ed29_i_codigo,                           \n";
$sCampos .= "ed29_c_descr, ed52_c_descr, ed11_c_descr, ed15_c_nome, ed223_i_serie  \n";

$sSqlTurmaSerie = $oDaoTurma->sql_query_turmaserie("", $sCampos, "ed57_c_descr", " ed220_i_codigo in ($turmas)");
$rsTurmaSerie   = $oDaoTurma->sql_record($sSqlTurmaSerie);

if ($oDaoTurma->numrows == 0) { ?>

  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Nenhuma turma para o curso selecionado<br>
          <input type='button' value='Fechar' onclick='window.close()'></b>
        </font>
      </td>
    </tr>
  </table>

<?
  exit;
}

$sSqlCalendario   = $oDaoCalendario->sql_query("", "ed52_i_ano as ano_calendario", "", "ed52_i_codigo = {$codcalendario}");
$rsCalendario     = $oDaoCalendario->sql_record($sSqlCalendario);
$oDadosCalendario = db_utils::fieldsmemory($rsCalendario, 0);

$dtSistema = date('Y-m-d', db_getsession("DB_datausu"));

$campos = str_replace(chr(92), "", $campos);
$campos = str_replace("fc_idade()", "fc_idade(ed47_d_nasc,'$dtSistema'::date)", $campos);
$campos = str_replace("fc_idade_mes()", "fc_idade_anomesdia(ed47_d_nasc,'$dtSistema')", $campos);
$campos = str_replace("fc_idade_dia()", "fc_idade_anomesdia(ed47_d_nasc,'$dtSistema')", $campos);

$oPdf   = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();

$aMeses           = array("JAN", "FEV", "MAR", "ABR", "MAI", "JUN", "JUL", "AGO", "SET", "OUT", "NOV", "DEZ");
$aCamposCabecalho = explode( "|", $cabecalho );
$aCamposLargura   = explode( "|", $colunas );
$aCamposAlinha    = explode( "|", $alinhamento );
$aCamposImpressao = explode( "__", $campos );

$campos  = implode(", ", $aCamposImpressao);
$iLinhas = $oDaoTurma->numrows;

$iLarguraMaxima      = $orientacao == "P" ? 195 : 280;
$aCamposTexto        = array( "Nome do Aluno", "Endereço/Bairro", "Email", "Nome do Pai", "Nome da Mãe" );
$aCamposData         = array( "ed47_d_nasc", "ed60_d_datamatricula", "ed60_d_datasaida", "ed76_d_data" );
$iSomaColunas        = array_sum( $aCamposLargura );
$aCabecalhosTexto    = array_intersect( $aCamposCabecalho, $aCamposTexto );
$iTamanhoIncrementar = floor( ( $iLarguraMaxima - $iSomaColunas ) / count( $aCabecalhosTexto ) );
$aLarguraCorrigida   = array();
$aCamposConcatenados = array(
                              "Endereço/Bairro",
                              "Telefones",
                              "Naturalidade",
                              "Transporte Escolar",
                              "Bolsa Família",
                              "Rep",
                              "Certidão",
                              "Local de Procedência",
                              "Assinatura 1",
                              "Assinatura 2",
                              "Assinatura 3",
                              "Meses",
                              "Idade",
                              "Meses da Idade",
                              "Dias da Idade",
                              "Foto"
                            );

for ($iContFor = 0; $iContFor < $iLinhas; $iContFor++) {

  $oDadosTurmaSerie    = db_utils::fieldsmemory($rsTurmaSerie, $iContFor);

  $sSqlRegenteConselho = $oDaoRegenteConselho->sql_query("",
                                                         "case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome
                                                          else cgmcgm.z01_nome end as regente",
                                                         "",
                                                         " ed235_i_turma = $oDadosTurmaSerie->ed57_i_codigo "
                                                        );
  $rsRegenteConselho   = $oDaoRegenteConselho->sql_record($sSqlRegenteConselho);

  $regente = "";
  if($oDaoRegenteConselho->numrows > 0) {
    $regente = db_utils::fieldsMemory( $rsRegenteConselho, 0 )->regente;
  }

  $oPdf->setfillcolor(223);
  $head1 = $titulorel == "" ? "LISTA OFICIAL DAS TURMAS" : $titulorel;
  $head2 = "Turma: $oDadosTurmaSerie->ed57_c_descr";
  $head3 = "Curso: $oDadosTurmaSerie->ed29_i_codigo - $oDadosTurmaSerie->ed29_c_descr";
  $head4 = "Calendário: $oDadosTurmaSerie->ed52_c_descr";
  $head5 = "Etapa: $oDadosTurmaSerie->ed11_c_descr";
  $head6 = "Turno: $oDadosTurmaSerie->ed15_c_nome";

  $head7 = "";
  if ($nomeregente == "S") {
    $head7 = "Regente: $regente";
  }

  $oPdf->addpage($orientacao);
  $oPdf->ln(5);
  $oPdf->setfont('arial', 'b', $tamfonte);
  $somacampos = 0;

  for ($iContFor1 = 0; $iContFor1 < count($aCamposCabecalho); $iContFor1++) {

    $next = 0;
    if ($iContFor1 == (count($aCamposCabecalho)-1)) {
      $next = 1;
    }

    $aLarguraCorrigida[$iContFor1] = $aCamposLargura[$iContFor1];
    if( in_array( $aCamposCabecalho[$iContFor1], $aCabecalhosTexto ) ) {
      $aLarguraCorrigida[$iContFor1] = $aCamposLargura[$iContFor1] + $iTamanhoIncrementar;
    }

    if (trim($aCamposCabecalho[$iContFor1]) == "Meses") {

      for ($iContFor2 = 0; $iContFor2 < 12; $iContFor2++) {

        $next_mes = $next;
        if ($iContFor2 < 11) {
          $next_mes = 0;
        }

        $oPdf->cell($aLarguraCorrigida[$iContFor1]/12, 4, $aMeses[$iContFor2], 1, $next_mes, "C", 0);
      }
    } else {
      $oPdf->cell($aLarguraCorrigida[$iContFor1], 4, $aCamposCabecalho[$iContFor1], 1, $next, "C", 0);
    }

    $somacampos += $aLarguraCorrigida[$iContFor1];
  }

  $condicao = "";
  if ($active == "SIM") {
    $condicao=" AND ed60_c_situacao = 'MATRICULADO' ";
  }

  if ($trocaTurma == 1) {
    $condicao .= " AND ed60_c_situacao != 'TROCA DE TURMA' ";
  }

  $sOrdenacao       = $ordenacao.", ed60_c_ativa";
  $sWhereMatricula  = "    ed60_i_turma = {$oDadosTurmaSerie->ed57_i_codigo}";
  $sWhereMatricula .= " AND ed221_i_serie = {$oDadosTurmaSerie->ed223_i_serie} {$condicao}";
  $sSqlMatricula    = $oDaoMatricula->sql_query_naturalidade_aluno("", $campos, $sOrdenacao, $sWhereMatricula);
  $rsMatricula      = $oDaoMatricula->sql_record($sSqlMatricula);
  $iLinha2          = $oDaoMatricula->numrows;

  if ($iLinha2 == 0) {

    $oPdf->cell(195, 4, "Turma não possui nenhum aluno matriculado.", "", $next, "C", 0);
    continue;
  }

  $limite = $orientacao == "P" ? 55 : 34;
  $cont   = 0;

  for ($iContFor3 = 0; $iContFor3 < $iLinha2; $iContFor3++) {

    $oDadosAluno = db_utils::fieldsMemory( $rsMatricula, $iContFor3 );

    for ($iContFor1 = 0; $iContFor1 < count($aCamposCabecalho); $iContFor1++) {

      $next = 0;
      if ($iContFor1 == (count($aCamposCabecalho)-1)) {
        $next = 1;
      }

      if (trim($aCamposCabecalho[$iContFor1]) == "Meses") {

        for ($iContFor2 = 1; $iContFor2 <= 12; $iContFor2++) {

          $next_mes = $next;
          if ($iContFor2 < 12) {
            $next_mes = 0;
          }

          $oPdf->cell($aLarguraCorrigida[$iContFor1]/12, 4, "", 1, $next_mes, "C", 0);
        }
      } else if (pg_field_name($rsMatricula, $iContFor1) == "ed47_certidaomatricula") {

        $iMatricula = pg_result($rsMatricula, $iContFor3, $iContFor1);
        $sMatricula = substr($iMatricula, 0, 6)." ".substr($iMatricula, 6, 2)." ".
                      substr($iMatricula, 8, 2)." ".substr($iMatricula, 10, 4)." ".
                      substr($iMatricula, 14, 1)." ".substr($iMatricula, 15, 5)." ".
                      substr($iMatricula, 20, 3)." ".substr($iMatricula, 23, 7)." ".
                      substr($iMatricula, 30, 2);
        $oPdf->cell($aLarguraCorrigida[$iContFor1], 4, $sMatricula, 1, $next, $aCamposAlinha[$iContFor1], 0);
      } else if (pg_field_name($rsMatricula, $iContFor1) == "anomes") {

        $sMes = pg_result($rsMatricula, $iContFor3, $iContFor1);
        $aMes = explode(",",$sMes);
        $iMes = str_replace("meses"," ",$aMes[1]);
        $oPdf->cell($aLarguraCorrigida[$iContFor1], 4, $iMes, 1, $next, $aCamposAlinha[$iContFor1], 0);
      } else if (pg_field_name($rsMatricula, $iContFor1) == "idadedia") {

        $sDia = pg_result($rsMatricula, $iContFor3, $iContFor1);
        $aDia = explode(",",$sDia);
        $iDia = str_replace("dias"," ",$aDia[2]);
        $oPdf->cell($aLarguraCorrigida[$iContFor1], 4, $iDia, 1, $next, $aCamposAlinha[$iContFor1], 0);
      } else if (pg_field_name($rsMatricula, $iContFor1) == "ed47_tiposanguineo") {

        $sTipoSanguineo = "Não informado";
        $iTipoSanguineo = pg_result($rsMatricula, $iContFor3, $iContFor1);

        if ( !empty( $iTipoSanguineo ) ) {

          $sSqlTipoSanguineo = $oDaoTipoSanguineo->sql_query_file(null, "sd100_tipo", null, " sd100_sequencial = {$iTipoSanguineo}");
          $rsTipoSanguineo   = $oDaoTipoSanguineo->sql_record($sSqlTipoSanguineo);
          $sTipoSanguineo    = db_utils::fieldsMemory( $rsTipoSanguineo, 0 )->sd100_tipo;
        }

        $oPdf->cell($aLarguraCorrigida[$iContFor1], 4, $sTipoSanguineo, 1, $next, $aCamposAlinha[$iContFor1], 0);
      } else {

        $sValor = "";

        if( in_array( $aCamposCabecalho[$iContFor1], $aCamposConcatenados ) ) {
          $sValor = pg_result($rsMatricula, $iContFor3, $iContFor1);
        } else if( in_array( $aCamposImpressao[$iContFor1], $aCamposData ) ) {

          $sValor = pg_result($rsMatricula, $iContFor3, $iContFor1);
          if ( !empty($sValor)) {
            $sValor = db_formatar( $sValor, 'd' );
          }
        } else {
          $sValor = isset($oDadosAluno->$aCamposImpressao[$iContFor1]) ? $oDadosAluno->$aCamposImpressao[$iContFor1] : "";
        }

        $oPdf->Cell( $aLarguraCorrigida[$iContFor1], 4, $sValor, 1, $next, $aCamposAlinha[$iContFor1], 0 );
      }
    }

    if ($limite == $cont) {

      $oPdf->cell($somacampos, 4, "* Aluno repetindo a Etapa", 1, 1, "L", 0);
      $oPdf->line(10, 44, $somacampos + 10, 44);
      $oPdf->addpage($orientacao);
      $oPdf->ln(5);
      $oPdf->setfont('arial', 'b', $tamfonte);

      for ($iContFor1 = 0; $iContFor1 < count($aCamposCabecalho); $iContFor1++) {

        $next = 0;
        if ($iContFor1 == (count($aCamposCabecalho)-1)) {
          $next = 1;
        }

        if (trim($aCamposCabecalho[$iContFor1]) == "Meses") {

          for ($iContFor2 = 0; $iContFor2 < 12; $iContFor2++) {

            $next_mes = $next;
            if ($iContFor2 < 11) {
              $next_mes = 0;
            }

            $oPdf->cell($aLarguraCorrigida[$iContFor1]/12, 4, $aMeses[$iContFor2], 1, $next_mes, "C", 0);
          }
        } else {
          $oPdf->cell($aLarguraCorrigida[$iContFor1], 4, $aCamposCabecalho[$iContFor1], 1, $next, "C", 0);
        }
      }

      $cont = -1;
    }

    $cont++;
  }

  $comeco = $cont-1;

  for ($iContFor3 = $comeco; $iContFor3 < $limite; $iContFor3++) {

    for ($iContFor1 = 0; $iContFor1 < count($aCamposCabecalho); $iContFor1++) {

      $next = 0;
      if ($iContFor1 == (count($aCamposCabecalho)-1)) {
        $next = 1;
      }

      if (trim($aCamposCabecalho[$iContFor1]) == "Meses") {

        for ($iContFor2 = 1; $iContFor2 <= 12; $iContFor2++) {

          $next_mes = $next;
          if ($iContFor2 < 12) {
            $next_mes = 0;
          }

          $oPdf->cell($aLarguraCorrigida[$iContFor1]/12, 4, "", "LR", $next_mes, "C", 0);
        }
      } else {
        $oPdf->cell($aLarguraCorrigida[$iContFor1], 4, "", "LR", $next, "C", 0);
      }
    }
  }

  $oPdf->cell($somacampos, 5, "* Aluno repetindo a Etapa", 1, 1, "L", 0);
  $oPdf->line(10, 44, $somacampos + 10, 44);
}

$oPdf->Output();