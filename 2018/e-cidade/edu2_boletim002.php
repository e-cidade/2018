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

require_once(modification("fpdf151/scpdf.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libdocumento.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$clmatricula       = new cl_matricula;
$clturma           = new cl_turma;
$clregenteconselho = new cl_regenteconselho;
$cldiarioavaliacao = new cl_diarioavaliacao;
$clprocavaliacao   = new cl_procavaliacao;
$clpareceraval     = new cl_pareceraval;
$claprovconselho   = new cl_aprovconselho;
$clregenciahorario = new cl_regenciahorario;
$clDBConfig        = new cl_db_config();
$clEscola          = new cl_escola();

$obs1 = base64_decode($obs1);

$oDadosGrade                = new stdClass();
$oDadosGrade->nLarguraGrade = 280;

$escola   = db_getsession("DB_coddepto");


$clobsboletim    = new cl_obsboletim;
$sSqlObsBoletim = $clobsboletim->sql_query( "", "ed252_t_mensagem", "", "ed252_i_escola = {$escola}" );
$resultobs      = $clobsboletim->sql_record( $sSqlObsBoletim );
if ($clobsboletim->numrows > 0  ) {

	$obs1 = db_utils::fieldsMemory($resultobs, 0)->ed252_t_mensagem;
}


$sSqlTurma = $clturma->sql_query_turmaserie( "", "ed57_i_codigo as turma", "", " ed220_i_codigo = {$turma}" );
$result00  = $clturma->sql_record( $sSqlTurma );
db_fieldsmemory( $result00, 0 );

$sOrdenacaoMatricula = "ed60_i_numaluno,to_ascii(ed47_v_nome)";
$sWhereMatricula     = " ed60_i_codigo in ({$alunos}) AND ed60_i_turma = {$turma}";
$sSqlMatricula       = $clmatricula->sql_query( "", "*", $sOrdenacaoMatricula, $sWhereMatricula );
$result              = $clmatricula->sql_record( $sSqlMatricula );

if ($clmatricula->numrows == 0) {?>

  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Nenhuma matrícula para a turma selecionada<br>
      <input type='button' value='Fechar' onclick='window.close()'></b>
     </font>
    </td>
   </tr>
  </table>
 <?
  exit;
}

$sCampos    = "ed09_c_descr as periodoselecionado,ed41_i_sequencia as seq_atual";

$sSqlProcAvaliacao = $clprocavaliacao->sql_query( "", $sCampos, "", " ed41_i_codigo = {$periodo}" );
$result_per        = $clprocavaliacao->sql_record( $sSqlProcAvaliacao );
db_fieldsmemory( $result_per, 0 );

$pdf = new scpdf();
$pdf->Open();
$pdf->AliasNbPages();

$m0         = 9;
$m1         = 14;
$m2         = 18;
$m3         = 22;
$m4         = 26;
$m5         = 30;
$m6         = 33;
$m7         = 3;
$m8         = 5;
$a          = 5;
$f          = 280;
$r          = 37;
$alturahead = $pdf->setY(3);

for ($x = 0; $x < $clmatricula->numrows; $x++) {

  db_fieldsmemory( $result, $x );

  /**
   * Dados Instituição
   */
  $sCamposInstit   = "nomeinst as nome, ender, munic, uf, telef, email, url, logo";
  $sSqlDadosInstit = $clDBConfig->sql_query_file( db_getsession('DB_instit'), $sCamposInstit );
  $rsDadosInstit   = db_query($sSqlDadosInstit);
  $oDadosInstit    = db_utils::fieldsMemory( $rsDadosInstit, 0 );
  $url             = $oDadosInstit->url;
  $nome            = $oDadosInstit->nome;
  $sLogoInstit     = $oDadosInstit->logo;
  $munic           = $oDadosInstit->munic;

  /**
   * Dados Escola
   */
  $sCamposEscola     = "ed18_i_codigo, ed18_c_nome, j14_nome, ed18_i_numero, j13_descr,ed261_c_nome, ed260_c_sigla, ";
  $sCamposEscola    .= "ed18_c_email, ed18_c_logo, ed18_codigoreferencia";
  $sSqlDadosEscola   = $clEscola->sql_query_dados( db_getsession("DB_coddepto"), $sCamposEscola );
  $rsDadosEscola     = db_query($sSqlDadosEscola);
  $oDadosEscola      = db_utils::fieldsMemory( $rsDadosEscola, 0 );
  $sNomeEscola       = $oDadosEscola->ed18_c_nome;
  $sLogoEscola       = $oDadosEscola->ed18_c_logo;
  $iCodigoEscola     = $oDadosEscola->ed18_i_codigo;
  $ruaescola         = $oDadosEscola->j14_nome;
  $numescola         = $oDadosEscola->ed18_i_numero;
  $bairroescola      = $oDadosEscola->j13_descr;
  $cidadeescola      = $oDadosEscola->ed261_c_nome;
  $estadoescola      = $oDadosEscola->ed260_c_sigla;
  $emailescola       = $oDadosEscola->ed18_c_email;
  $iCodigoReferencia = $oDadosEscola->ed18_codigoreferencia;

  if ( $iCodigoReferencia != null ) {
    $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
  }

  $sSqlTelefoneEscola = $clEscola->sql_query_telefone( "", "ed26_i_numero, ed26_i_ddd", "", "ed26_i_escola = {$iCodigoEscola}" );
  $rsTelefoneEscola   = db_query( $sSqlTelefoneEscola );
  $oTelefoneEscola    = db_utils::fieldsMemory( $rsTelefoneEscola, 0 );
  $iTelefoneEscola    = $oTelefoneEscola->ed26_i_numero;
  $iDTelefone         = $oTelefoneEscola->ed26_i_ddd;
  $DadosCabecalho     = $sNomeEscola. " (".$iDTelefone.")" .$iTelefoneEscola;

  $sCamposRegenteConselho = "case when cgmrh.z01_nome is null then cgmrh.z01_nome else cgmcgm.z01_nome end as conselheiro";
  $sSqlRegenteConselho    = $clregenteconselho->sql_query( "", $sCamposRegenteConselho, "", " ed235_i_turma = {$ed60_i_turma}" );
  $result6                = $clregenteconselho->sql_record( $sSqlRegenteConselho );

 if ($clregenteconselho->numrows > 0) {
   db_fieldsmemory($result6,0);
 } else {
   $conselheiro = "";
 }

  $head1    = "BOLETIM DE DESEMPENHO {$periodoselecionado}";
  $head2    = "Nome: {$ed47_v_nome}";
  $head3    = "Curso: {$ed29_i_codigo} - {$ed29_c_descr}";
  $head4    = "Código Aluno: {$ed47_i_codigo}  Matrícula: {$ed60_i_codigo}";
  $head5    = "Etapa: {$ed11_c_descr} Ano: {$ed52_i_ano}";
  $head6    = "Turma: {$ed57_c_descr}";
  $situacao = trim($ed60_c_concluida) == "S"
              && trim($ed60_c_situacao) != 'AVANÇADO'
              && trim($ed60_c_situacao) != 'CLASSIFICADO' ? "CONCLUÍDO" : Situacao( $ed60_c_situacao, $ed60_i_codigo );
  $head7    = "Situação: {$situacao}";

  if ( strlen($nome) > 42 || strlen($sNomeEscola) > 42 ) {
	  $TamFonteNome = 8;
  } else {
	  $TamFonteNome = 9;
  }

  $iPosXLogoEscola = 255;
  $pdf->AddPage('L');
  $pdf->SetFont('arial','b',6);
  $pdf->setfillcolor(225);

  $margemesquerda = $pdf->lMargin;
  $oLibDocumento  = new libdocumento(5001,null);

  if ( $oLibDocumento->lErro ) {
  	db_redireciona("db_erros.php?fechar=true&db_erro={$oLibDocumento->sMsgErro}");
  }

  $aParagrafo = $oLibDocumento->getDocParagrafos();

  foreach ($aParagrafo as $oParagrafo ) {
    eval($oParagrafo->oParag->db02_texto);
  }

  $oGrade = new RelatorioGradeAproveitamento($pdf, MatriculaRepository::getMatriculaByCodigo($ed60_i_codigo), 280 );
  if ($grade=="yes") {

    $oGrade->montarGrade();
    $oGrade->imprimirMinimoParaAprovacao();
  }

  $sWhere              = " ed95_i_aluno = {$ed60_i_aluno} AND ed59_i_turma = {$turma} AND ed72_i_procavaliacao = {$periodo}";
  $sSqlDiarioAvaliacao = $cldiarioavaliacao->sql_query( "", "ed232_c_descr, ed72_t_obs", "", $sWhere );
  $result_obs          = $cldiarioavaliacao->sql_record( $sSqlDiarioAvaliacao );

  $ed72_t_obs   = "";
  $aObservacoes = array();

  for( $iContador = 0; $iContador < pg_num_rows( $result_obs ); $iContador++ ) {

    $oDadosObservacao = db_utils::fieldsMemory( $result_obs, $iContador );

    if( !empty( $oDadosObservacao->ed72_t_obs ) ) {
      $aObservacoes[] = "{$oDadosObservacao->ed72_t_obs}";
    }
  }

  $ed72_t_obs = implode( ". ", $aObservacoes );

  $impresso = false;
  if ($padrao == "yes") {

    $sWhere          = " ed95_i_aluno = {$ed60_i_aluno} AND ed72_i_procavaliacao = {$periodo} AND ed59_i_turma = {$turma}";
    $sSqlParecerAval = $clpareceraval->sql_query( "", "ed93_t_parecer", "ed93_i_codigo", $sWhere );
    $result_par      = $clpareceraval->sql_record( $sSqlParecerAval );

    if ($clpareceraval->numrows > 0) {

      $pdf->cell( $oDadosGrade->nLarguraGrade, 4, "Parecer {$periodoselecionado}: ", 1, 1, "L", 1 );

      if ($padraotipo == "L") {
        $pdf->cell( $oDadosGrade->nLarguraGrade, 4, "Seq - Parecer => Legenda", 1, 1, "L", 0 );
      }

      $seq       = "";
      $sep       = "";
      $parpadrao = "";
      $seppadrao = "";

      for ($g = 0; $g < $clpareceraval->numrows; $g++) {

        db_fieldsmemory( $result_par, $g );

        if (!strstr( $seq, "#" . $ed93_t_parecer. "#" )) {

          $parpadrao .= $seppadrao.$ed93_t_parecer;
          $seq       .= $sep . "#" . $ed93_t_parecer . "#";
          $sep        = ",";
          $seppadrao  = "    ";

          if ($padraotipo == "L") {

            $explode_parecer = explode( "**", $ed93_t_parecer );

            for ($b = 0; $b < count($explode_parecer); $b++) {
              $pdf->cell( $oDadosGrade->nLarguraGrade, 4, trim($explode_parecer[$b]), 1, 1, "L", 0 );
            }
          }
        }
      }

      if ($padraotipo == "C") {
        $pdf->multicell( $oDadosGrade->nLarguraGrade, 4, str_replace( "**", "  ", $parpadrao ), 1, "L", 0, 0 );
      }

      $impresso = true;
    }
  }

  if ($descritivo == "yes") {

    $sWhere              = " ed95_i_aluno = {$ed60_i_aluno} AND ed59_i_turma = {$turma} ";
    $sWhere             .= " AND ed72_i_procavaliacao = {$periodo} AND ed72_t_parecer != ''";
    $sSqlDiarioAvaliacao = $cldiarioavaliacao->sql_query( "", "DISTINCT ed72_t_parecer as pardescr", "", $sWhere );
    $result_pardescr     = $cldiarioavaliacao->sql_record( $sSqlDiarioAvaliacao );

    if ($cldiarioavaliacao->numrows > 0) {

      $pardescr = trim( pg_result( $result_pardescr, 0, 'pardescr' ) );

      if ($pardescr != "") {

        $pdf->setfont( 'arial', 'b', 7 );
        $pdf->cell( $oDadosGrade->nLarguraGrade, 4, "Parecer {$periodoselecionado}:", 1, 1, "L", 1 );
        $pdf->setfont( 'arial', '', 7 );

        for ($g = 0; $g < $cldiarioavaliacao->numrows; $g++) {

          db_fieldsmemory( $result_pardescr, $g );
          $pdf->multicell( $oDadosGrade->nLarguraGrade, 4, $pardescr, 1, "L", 0, 0 );
        }

        $impresso = true;
      }
    }
  }

  if ($impresso == false) {
    $pdf->cell( $oDadosGrade->nLarguraGrade, 4, "", "LTR", 1, "L", 0 );
  }

  $obs2        = "";
  $aConvencoes = $oGrade->getAmparosPorConvencao();
  if ( !empty($aConvencoes) ) {
    $obs2 = implode("\n", $aConvencoes);
  }

  $obs3        = "";
  $sCampos     = "case when cgmrh.z01_nome is null then cgmrh.z01_nome ";
  $sCampos    .= " else cgmcgm.z01_nome end as z01_nome, ed253_i_data, ed232_c_descr as disconselho, ed253_t_obs,";
  $sCampos    .= " ed59_i_ordenacao, ed122_sequencial, ed122_descricao, ed52_i_ano, ed11_c_descr as etapa,";
  $sCampos    .= "ed253_alterarnotafinal,  ed253_avaliacaoconselho";
  $sWhere      = " ed95_i_aluno = {$ed60_i_aluno} AND ed59_i_turma = {$turma} AND ed59_i_serie = {$ed221_i_serie}";

  $sSqlAprovConselho = $claprovconselho->sql_query( "", $sCampos, "ed59_i_ordenacao", $sWhere );
  $result_cons       = $claprovconselho->sql_record( $sSqlAprovConselho );

  $aAprovadoBaixaFrequencia   = array();
  $aAprovadoConselhoRegimento = array();
  $sObservacaoConselho        = '';

  if ($claprovconselho->numrows > 0) {

    for ($g = 0; $g < $claprovconselho->numrows; $g++) {

      db_fieldsmemory( $result_cons, $g );
      $oDadosAprovConselho = db_utils::fieldsMemory( $result_cons, $g );

      switch ($oDadosAprovConselho->ed122_sequencial) {

        /**
        * Valida se a aprovação foi por conselho
        */
        case 1:

          $oDocumento                = new libdocumento( 5013 );
          $oDocumento->disciplina    = $oDadosAprovConselho->disconselho;
          $oDocumento->etapa         = $oDadosAprovConselho->etapa;
          $oDocumento->justificativa = $oDadosAprovConselho->ed253_t_obs;
          $oDocumento->nota          = $oDadosAprovConselho->ed253_avaliacaoconselho;
          $oDocumento->anomatricula  = $oDadosAprovConselho->ed52_i_ano;

          $oDadosObservacao              = new stdClass();
          $oDadosObservacao->aParagrafos = $oDocumento->getDocParagrafos();

          if( trim( $oDadosObservacao->aParagrafos[1]->oParag->db02_texto ) != '' ) {
            $aAprovadoConselhoRegimento[]  = "- {$oDadosObservacao->aParagrafos[1]->oParag->db02_texto}";
          }

          break;

        /**
        * Valida se a aprovação não foi por baixa frequencia
        */
        case 2:

          $sHashSerieAno = $oDadosAprovConselho->etapa.$oDadosAprovConselho->ed52_i_ano;
          if ( !isset( $aAprovadoBaixaFrequencia[$sHashSerieAno] ) ) {
            $aAprovadoBaixaFrequencia[$sHashSerieAno] = $oDadosAprovConselho;
          }

          break;

        /**
        * Valida se a aprovação foi por regimento escolar
        */
        case 3;

          $sObservacao = "- Disciplina {$disconselho}: Aprovado conforme regimento escolar. Justificativa: {$ed253_t_obs}";
          $aAprovadoConselhoRegimento[] = $sObservacao;
          break;
      }
    }

    if ( count( $aAprovadoBaixaFrequencia ) > 0 ) {

      $oDocumento = new libdocumento( 5006 );

      foreach ( $aAprovadoBaixaFrequencia as $oBaixaFrequencia ) {

        $oDocumento->nome_aluno = $ed47_v_nome;
        $oDocumento->ano        = $oBaixaFrequencia->ed52_i_ano;
        $oDocumento->nome_etapa = $oBaixaFrequencia->etapa;
        $aParagrafos            = $oDocumento->getDocParagrafos();

        if ( isset( $aParagrafos[1] ) ) {
          $sObservacaoConselho .= "- {$aParagrafos[1]->oParag->db02_texto}\n";
        }
      }
    }
  }

  $sObservacaoConselho .= implode( "\n", $aAprovadoConselhoRegimento );

  $completar = ( $oDadosGrade->nLarguraGrade / 2 ) - $pdf->getY();
  $pdf->cell( $oDadosGrade->nLarguraGrade, $completar, "", 1	, 1, "L", 0 );
  $pdf->setfont( 'arial', 'b', 7 );
  $pdf->cell( $oDadosGrade->nLarguraGrade / 2, 4, "Observações / Mensagens", 1, 0, "C", 0 );
  $pdf->cell( $oDadosGrade->nLarguraGrade / 2, 4, "Convenções",              1, 1, "C", 0 );

  $posy = $pdf->getY();
  $pdf->Rect( $pdf->getX(), $pdf->getY(), $oDadosGrade->nLarguraGrade / 2, 30, $style = '' );

  $ed60_t_obs = substr( $ed60_t_obs,          0, 70 );
  $ed72_t_obs = substr( $ed72_t_obs,          0, 70 );
  $obs2       = substr( $obs2,                0, 150 );
  $obs3       = substr( $sObservacaoConselho, 0, 310 );

  $pdf->multicell( $oDadosGrade->nLarguraGrade / 2, 4, ( trim( $ed60_t_obs ) != "" ? $ed60_t_obs . "\n" : "" ).
                        ( trim( $ed72_t_obs ) != "" ? $ed72_t_obs . "\n" : "" ).
                        ( trim( str_replace( chr(92), "", $obs1 ) ) != "" ? str_replace( chr(92), "", $obs1 ) . "\n" : "" ).
                        ( trim( $obs2 ) != "" ? $obs2 . "\n" : "" ).
                        ( trim( $obs3 ) != "" ? $obs3 . "\n" : "" ), 0, "L", 0, 0 );

  if ($grade == "yes" ) {

    $pdf->setY( $posy );
    $pdf->setX( $pdf->rMargin + $oDadosGrade->nLarguraGrade / 2 );

    $sTexto  = "TF - Total Faltas | ". (trim( $ed57_c_medfreq ) == "PERÌODOS" ? "AD - Aulas Dadas" : "DL - Dias Letivos" );
    $sTexto .= " | FA - Faltas Abonadas";
    $pdf->cell( $oDadosGrade->nLarguraGrade / 2, 4, $sTexto, 0, 1, "L", 0 );

    $pdf->setX( $pdf->rMargin + $oDadosGrade->nLarguraGrade / 2 );


    $aPeriodosApresentados = array();
    foreach ($oGrade->getElementosApresentados() as $oElemento) {
      $aPeriodosApresentados[] = "{$oElemento->getDescricaoAbreviada()} - {$oElemento->getDescricao()}";
    }

    if ( count($aPeriodosApresentados) > 5 ) {

      $pdf->setY( $posy + 4 );
      $pdf->setX( $oDadosGrade->nLarguraGrade / 2 + 10 );
      $sPeriodos = implode(",  ", $aPeriodosApresentados);
      $pdf->multicell( $oDadosGrade->nLarguraGrade / 2, 4, $sPeriodos, 0, "L", 0, 0 );
    } else {

      foreach ($aPeriodosApresentados as $sPeriodo) {
        $pdf->cell( 70, 4, $sPeriodo, 0, 2, "L", 0 );
      }
    }

  }

  $pdf->Rect( $pdf->rMargin + $oDadosGrade->nLarguraGrade / 2, $posy, $oDadosGrade->nLarguraGrade / 2, 30, $style = '' );
  if ($assinaturaregente == "S") {

    $pdf->setY( $posy + 35 );
    $pdf->cell( $oDadosGrade->nLarguraGrade, 4, "__________________________________________________", "", 1, "C", 0 );
    $pdf->cell( $oDadosGrade->nLarguraGrade, 4, "Professor Conselheiro  {$conselheiro}",              "", 1, "C", 0 );
  } else {

    $pdf->setY( $posy + 35 );
    $pdf->setX( $oDadosGrade->nLarguraGrade );
    $pdf->cell( $oDadosGrade->nLarguraGrade, 4, "", "", 1, "C", 0 );
    $pdf->cell( $oDadosGrade->nLarguraGrade, 4, "", "", 1, "C", 0 );
  }
}
$pdf->Output();
