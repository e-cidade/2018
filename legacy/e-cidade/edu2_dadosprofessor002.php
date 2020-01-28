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

require_once("fpdf151/pdfwebseller.php");
define('MSG_EDU2_DADOSPROFESSOR002', 'educacao.escola.edu2_dadosprofessor002.');

$oCgm                = null;
$aRechumano          = array();
$aProfissionalEscola = array();

$oConfig               = new stdClass();
$oConfig->iTMargin     = 35;
$oConfig->iYMaximo     = 248;
$oConfig->iXMaximo     = 192;
$oConfig->iXMaximoLine = 202;
$oConfig->iAlturaLinha = 4;

$oConfig->aDiasSemana = array(
                               1 => 'Domingo',
                               2 => 'Segunda-feira',
                               3 => 'Terça-feira',
                               4 => 'Quarta-feira',
                               5 => 'Quinta-feira',
                               6 => 'Sexta-feira',
                               7 => 'Sábado'
                             );

try {

  if ( empty($iProfessor) ) {
    throw new Exception( _M( MSG_EDU2_DADOSPROFESSOR002 . "cgm_nao_informado") );
  }

  $oCgm                = CgmFactory::getInstanceByCgm( $iProfessor );
  $oDocente            = DocenteRepository::getDocenteByCodigo( $iProfessor );
  $aProfissionalEscola = ProfissionalEscolaRepository::getEscolasProfissionalByCGM( $oDocente->getCgm() );

  $oDaoRechumano  = new cl_rechumano;
  $sSqlRecHumano  = $oDaoRechumano->sql_query_consulta_rechumano( $oCgm->getCodigo() );
  $rsRechumanoCgm = db_query($sSqlRecHumano);

  if ( !$rsRechumanoCgm ) {

    $oMsgErro        = new stdClass;
    $oMsgErro->sErro = pg_last_error();
    throw new Exception( _M( MSG_EDU2_DADOSPROFESSOR002 . "erro_buscar_rechumano", $oMsgErro) );
  }

  $iLinhas = pg_num_rows($rsRechumanoCgm);

  for ($i=0; $i < $iLinhas; $i++) {
    $aRechumano[] = db_utils::fieldsMemory($rsRechumanoCgm, $i)->rechumano;
  }
} catch(Exception $oErro) {
  db_redireciona( 'db_erros.php?fechar=true&db_erro='.trim($oErro->getMessage()) );
}

$head1 = "RELATÓRIO DOS HORÁRIOS DO PROFESSOR";
$head3 = !empty($oCgm) ? "Professor: {$oCgm->getNome()} " : "Professor: ";

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetMargins(10, 10);
$oPdf->SetAutoPageBreak(false, 15);
$oPdf->setfillcolor(223);
$oPdf->AddPage('P');


/**
 * Valida a quebra de pagina e desenha a borda
 * @param  FPDF     $oPdf
 * @param  StdClass $oConfig
 * @return boolean           true se quebrou a pagina
 */
function validaQuerbraPagina(FPDF $oPdf, $oConfig) {

  if ($oPdf->getY() >= ($oPdf->h - 15) ) {

    desenhaBordaPagina($oPdf, $oConfig, true);
    $oPdf->AddPage();
    return true;
  }

  return false;
}


/**
 * Desenha a borda
 * @param  FPDF     $oPdf
 * @param  stdClass $oConfig
 * @param  boolean  $lQuebraPagina
 * @return void
 */
function desenhaBordaPagina(FPDF $oPdf, $oConfig, $lQuebraPagina = true) {

  $iAltura = $oConfig->iYMaximo;

  if ( !$lQuebraPagina ) {
    $iAltura = $oPdf->getY() - $oConfig->iTMargin;
  }
  $oPdf->Rect( $oPdf->lMargin, $oConfig->iTMargin, $oConfig->iXMaximo, $iAltura);
}


imprimeDadosPessoais( $oPdf, $oConfig, $oCgm );
imprimeDocumentos( $oPdf, $oConfig, new Docente( $iProfessor ) );
imprimirMovimentacao( $oPdf, $aRechumano, $oConfig);
imprimeEscolas( $oPdf, $aRechumano, $oConfig);
imprimeDadosAusencia($oPdf, $oCgm, $oConfig);
dadosAdmissionais( $oPdf, $oConfig, $aProfissionalEscola );


/**
 * Imprime os dados da ausências/licença/substituições
 * @param  FPDF      $oPdf
 * @param  CgmFisico $oCgm
 * @param  stdClass  $oConfig
 * @return void
 */
function imprimeDadosAusencia(FPDF $oPdf, CgmFisico $oCgm, $oConfig) {

  $oDaoRecHumano    = new cl_rechumano;
  $sSqlMovimentacao = $oDaoRecHumano->sql_query_movimentacao_professor_cgm($oCgm->getCodigo());
  $rsMovimentacao   = $oDaoRecHumano->sql_record($sSqlMovimentacao);
  $iRegistro        = $oDaoRecHumano->numrows;

  $aMovimentos = array();

  $oPdf->setfont('arial','b',7);
  $oPdf->cell(192, 4,"AUSÊNCIAS/LICENÇAS/SUBSTITUIÇÕES", 1, 1, "C",1);
  $oPdf->cell(192, 4, '', "LR", 1);
  $oPdf->setfont('arial','b',7);
  $oPdf->cell(20,  4,"Data Inicial",   "LB", 0, "C", 0);
  $oPdf->cell(20,  4,"Data Final ",     "B", 0, "C", 0);
  $oPdf->cell(152, 4,"Ação Executada", "RB", 1, "C", 0);

  if ($iRegistro > 0) {

    for ($i = 0; $i < $iRegistro; $i++) {

      $oMovimento = db_utils::fieldsMemory($rsMovimentacao, $i);

      if ($oMovimento->tipo == 'A') {

        $oAusencia            = new AusenciaDocente($oMovimento->codigo);
        $oMovimento->dtInicio = $oAusencia->getDataInicial()->getDate(DBDate::DATA_PTBR);

        $oMovimento->dtFinal  = '';
        if ($oAusencia->getDataFinal() != null) {
          $oMovimento->dtFinal  = $oAusencia->getDataFinal()->getDate(DBDate::DATA_PTBR);
        }

        $sTipo = "Ausência ";
        if ( $oAusencia->getTipoAusencia()->isLicenca() ) {
          $sTipo = "Licença ";
        }

        $sMsg  = "{$sTipo} - Tipo: {$oAusencia->getTipoAusencia()->getDescricao()}";
        if ($oAusencia->getObservacao() != '') {
          $sMsg .= " Observação: {$oAusencia->getObservacao()}";
        }

        $oMovimento->sMessage = $sMsg;

      } elseif ($oMovimento->tipo == 'S') {

        $oSubstituicao        = new DocenteSubstituto($oMovimento->codigo);
        $oMovimento->dtInicio = $oSubstituicao->getPeriodoInicial()->getDate(DBDate::DATA_PTBR);
        $oMovimento->dtFinal  = '';
        if ($oSubstituicao->getPeriodoFinal() != null) {
          $oMovimento->dtFinal  = $oSubstituicao->getPeriodoFinal()->getDate(DBDate::DATA_PTBR);
        }

        $sTipo = $oSubstituicao->getTipoVinculo() == 2 ? "PERMANENTE" : "TEMPORARIO";

        $sMsg  = "Professor Substituido : {$oSubstituicao->getAusente()->getDocente()->getProfessor()->getNome()}, ";
        $sMsg .= "Disciplina: {$oSubstituicao->getRegencia()->getDisciplina()->getNomeDisciplina()} ";
        $sMsg .= "Substituição: {$sTipo}";

        $oMovimento->sMessage = $sMsg;
      }
      $aMovimentos[] = $oMovimento;
    }

    $oPdf->setfont('arial','',7);
    foreach ($aMovimentos as $oMovimento) {

      $oPdf->cell(20,  4, $oMovimento->dtInicio,                 "L", 0, "C");
      $oPdf->cell(20,  4, $oMovimento->dtFinal,                    0, 0, "C");
      $oPdf->cell(152, 4, substr($oMovimento->sMessage, 0, 105), "R", 1, "L");
    }

    desenhaBordaPagina($oPdf, $oConfig, false);

  }
}

/**
 * Percorre os dados das movimentações do regente e as escreve na tela
 * @param  PDF     $oPdf
 * @param  integer $iCgm
 */
function imprimirMovimentacao( $oPdf, $aRechumano, $oConfig ) {

  escreveCabecalhoMovimentacao( $oPdf );

  $aMovimentacoes = buscaMovimentacoes( $aRechumano);

  if ( count($aMovimentacoes) > 0 ) {

    $oPdf->setfont('arial','',6);
    foreach ($aMovimentacoes as $oMovimentacao) {

      if ( validaQuerbraPagina($oPdf, $oConfig) )  {
        escreveCabecalhoMovimentacao( $oPdf );
      }

      $sEscola       = validaTamanhoNomeEscola( $oMovimentacao->ed118_escola );
      $sUsuario      = validaTamanhoNomeUsuario( $oMovimentacao->ed118_usuario );
      $iLinhasResumo = $oPdf->NbLines(80, $oMovimentacao->ed118_resumo);

      $oPdf->cell(52,      4 * $iLinhasResumo, $sEscola,                     "LB",  0, "C", 0);
      $oPdf->cell(40,      4 * $iLinhasResumo, $sUsuario,                     "B",  0, "C", 0);
      $oPdf->cell(10,      4 * $iLinhasResumo, $oMovimentacao->ed118_data,    "B",  0, "C", 0);
      $oPdf->cell(10,      4 * $iLinhasResumo, $oMovimentacao->ed118_hora,    "B",  0, "C", 0);
      $oPdf->MultiCell(80, 4,                  $oMovimentacao->ed118_resumo, "RB", "L");
    }
  } else {

    $oPdf->setfont('arial','',7);
    $oPdf->cell(192,4,"Nenhum registro de movimentação.", "LRB", 1, "C", 0);
  }

}

/**
 * Retorna as movimentações do profissional
 * @param  array      $aRechumano
 * @return stdClass[]
 */
function buscaMovimentacoes( $aRechumano ) {

  $oDaoRecHumanoMovimentacao   = new cl_rechumanomovimentacao();
  $sWhereRecHumanoMovimentacao = " ed118_rechumano in (" . implode(", ", $aRechumano) . ")";
  $sOrderRecHumanoMovimentacao = "ed118_data, ed118_hora";
  $sSqlRecHumanoMovimentacao   = $oDaoRecHumanoMovimentacao->sql_query_file('','*',$sOrderRecHumanoMovimentacao,$sWhereRecHumanoMovimentacao);
  $rsRecHumanoMovimentacao     = pg_query( $sSqlRecHumanoMovimentacao );
  $iLinhas                     = pg_num_rows( $rsRecHumanoMovimentacao );

  $aMovimentacoes = array();
  if ( $iLinhas > 0 ) {

    for ( $iContador = 0; $iContador < $iLinhas; $iContador++ ) {
      $aMovimentacoes[] = db_utils::fieldsMemory( $rsRecHumanoMovimentacao, $iContador, true);
    }
  }

  return $aMovimentacoes;
}

/**
 * Escreve o cabeçalho da tabela de movimentações do regente
 */
function escreveCabecalhoMovimentacao ( $oPdf ) {

  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(192, 4,"MOVIMENTAÇÕES", 1,   1, "C", 1);
  $oPdf->cell(192, 4, '',            "LR", 1);
  $oPdf->cell(52,  4, "Escola",      "LB", 0, "C", 0);
  $oPdf->cell(40,  4, "Usuário",     "B",  0, "C", 0);
  $oPdf->cell(10,  4, "Data",        "B",  0, "C", 0);
  $oPdf->cell(10,  4, "Hora",        "B",  0, "C", 0);
  $oPdf->cell(80,  4, "Resumo",      "RB", 1, "C", 0);
  $oPdf->setfont('arial', '', 6);
}

/**
 * Valida se escola possui nome abreviado, caso não haja
 * Valida se nome possui a quantidade máxima de caractéres, caso possua mais o que o permitido, corta o nome da escola
 * @param  integer $iEscola
 * @return string
 */
function validaTamanhoNomeEscola( $iEscola ) {

  $oEscola = EscolaRepository::getEscolaByCodigo( $iEscola );

  if ( $oEscola->getAbreviatura() != "" ) {
    return $oEscola->getAbreviatura();
  }

  $sNomeEscola       = $oEscola->getNome();
  $iLetrasNomeEscola = strlen( $sNomeEscola );

  if ( $iLetrasNomeEscola > 45 ) {
    $sNomeEscola = substr( $sNomeEscola, 0, 45 - $iLetrasNomeEscola);
  }

  return $sNomeEscola;
}

/**
 * Valida o tamanho do nome do usuário, caso ultrapasse o limite de carectéres, o nome é cortado
 * @param  integer $iUsuario
 * @return string
 */
function validaTamanhoNomeUsuario( $iUsuario ) {

  $oUsuario           = UsuarioSistemaRepository::getPorCodigo( $iUsuario );
  $sNomeUsuario       = $oUsuario->getNome();
  $iLetrasNomeUsuario = strlen( $sNomeUsuario );

  if ( $iLetrasNomeUsuario > 35 ) {
    $sNomeUsuario = substr( $sNomeUsuario, 0, 35 - $iLetrasNomeUsuario );
  }

  return $sNomeUsuario;
}

function imprimeDocumentos( $oPdf, $oConfig, $oDocente ) {

  $oPdf->setfont('arial','b',7);
  $oPdf->cell( 192, 4, "DOCUMENTOS",    "TB", 1, "C", 1 );

  $oPdf->Ln();

  $oPdf->cell(5);

  $oPdf->setfont('arial','',7);
  $oPdf->cell( 20, 4, "CPF: ", 0, 0, "L", 0);
  $oPdf->setfont('arial','b',7);
  $oPdf->cell( 30, 4, $oDocente->getCpf(), 0, 0, "L", 0);

  $oPdf->setfont('arial','',7);
  $oPdf->cell( 23, 4, "NIS: ", 0, 0, "L", 0);
  $oPdf->setfont('arial','b',7);
  $oPdf->cell( 54, 4, $oDocente->getNis(), 0, 0, "L", 0);

  $oPdf->setfont('arial','',7);
  $oPdf->cell( 20, 4, "N° Passaporte: ", 0, 0, "L", 0);
  $oPdf->setfont('arial','b',7);
  $oPdf->cell( 10, 4, $oDocente->getPassaporte(), 0, 1, "L", 0);

  $oPdf->cell(5);

  $oPdf->setfont('arial','',7);
  $oPdf->cell( 20, 4, "Identidade: ", 0, 0, "L", 0);
  $oPdf->setfont('arial','b',7);
  $oPdf->cell( 30, 4, $oDocente->getIdentidade(), 0, 0, "L", 0);

  $oPdf->setfont('arial','',7);
  $oPdf->cell( 23, 4, "UF da Identidade: ", 0, 0, "L", 0);
  $oPdf->setfont('arial','b',7);
  $oPdf->cell( 10, 4, $oDocente->getUfIdentidadeSigla(), 0, 0, "L", 0);

  $oPdf->setfont('arial','',7);
  $oPdf->cell( 24, 4, "Data de Expedição: ", 0, 0, "L", 0);
  $oPdf->setfont('arial','b',7);
  $oDataExpedicao = $oDocente->getDataExpedicaoIdentidade();
  $oPdf->cell( 20, 4, empty($oDataExpedicao) ? $oDataExpedicao : $oDataExpedicao->getDate( DBDate::DATA_PTBR ), 0, 0, "L", 0);

  $oPdf->setfont('arial','',7);
  $oPdf->cell( 20, 4, "Complemento: ", 0, 0, "L", 0);
  $oPdf->setfont('arial','b',7);
  $oPdf->cell( 38, 4,  $oDocente->getComplemento(), 0, 1, "L", 0);

  $oPdf->cell(5);

  $oPdf->setfont('arial','',7);
  $oPdf->cell( 20, 4, "Órgão Emissor: ", 0, 0, "L", 0);
  $oPdf->setfont('arial','b',7);
  $oPdf->cell( 107, 4, $oDocente->getOrgaoEmissorIdentidade(), 0, 1, "L", 0);

  $oPdf->cell(5);

  $oPdf->setfont('arial','',7);
  $oPdf->cell( 20, 4, "Título: ", 0, 0, "L", 0);
  $oPdf->setfont('arial','b',7);
  $oPdf->cell( 30, 4, $oDocente->getTituloEleitoral(), 0, 0, "L", 0);

  $oPdf->setfont('arial','',7);
  $oPdf->cell( 23, 4, "Zona: ", 0, 0, "L", 0);
  $oPdf->setfont('arial','b',7);
  $oPdf->cell( 10, 4, $oDocente->getZonaEleitoral(), 0, 0, "L", 0);

  $oPdf->setfont('arial','',7);
  $oPdf->cell( 24, 4, "Seção: ", 0, 0, "L", 0);
  $oPdf->setfont('arial','b',7);
  $oPdf->cell( 20, 4, $oDocente->getSecaoEleitoral(), 0, 1, "L", 0);

  $oPdf->cell(5);

  $oPdf->setfont('arial','',7);
  $oPdf->cell( 20, 4, "CTPS: ", 0, 0, "L", 0);
  $oPdf->setfont('arial','b',7);
  $oPdf->cell( 30, 4, $oDocente->getCtps(), 0, 0, "L", 0);

  $oPdf->setfont('arial','',7);
  $oPdf->cell( 23, 4, "Série: ", 0, 0, "L", 0);
  $oPdf->setfont('arial','b',7);
  $oPdf->cell( 10, 4, $oDocente->getSerieCtps(), 0, 0, "L", 0);

  $oPdf->setfont('arial','',7);
  $oPdf->cell( 24, 4, "UF da CTPS: ", 0, 0, "L", 0);
  $oPdf->setfont('arial','b',7);
  $oPdf->cell( 20, 4, $oDocente->getSiglaUfCtps(), 0, 0, "L", 0);

  $oPdf->setfont('arial','',7);
  $oPdf->cell( 20, 4, "Pis/Pasep/CI: ", 0, 0, "L", 0);
  $oPdf->setfont('arial','b',7);
  $oPdf->cell( 38, 4, $oDocente->getPisPasep(), 0, 1, "L", 0);

  $oPdf->Ln();

  desenhaBordaPagina( $oPdf, $oConfig, false );
}

/**
 * Imprime os vínculos das escolas
 * @param  FPDF     $oPdf
 * @param  array    $aRechumano
 * @param  stdClass $oConfig
 * @return void
 */
function imprimeEscolas(FPDF $oPdf, $aRechumano, $oConfig) {

  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(192, 4, "ESCOLAS",   1, 1, "C",1);
  $oPdf->cell(192, 4, "" ,      "LR", 1);

  $aEscolasVinculado = buscaEscolas($aRechumano);
  foreach( $aEscolasVinculado as $iIndex => $aDadosVinculo) {

    $sTipo = "CGM:" ;
    if ($iIndex == 1) {
      $sTipo = "MATRÍCULA:" ;
    }
    $oDadosVinculo = $aDadosVinculo[0];

    $oPdf->setfont('arial', 'b', 7);
    $oPdf->cell(25, 4, "{$sTipo}" ,                         "B", 0);
    $oPdf->setfont('arial', '', 7);
    $oPdf->cell(15, 4, "{$oDadosVinculo->identificacao}" ,  "B", 0);
    $oPdf->cell(50, 4, "" ,                                 "B", 0);
    $oPdf->setfont('arial', 'b', 7);
    $oPdf->cell(40, 4, "Regime:" ,                          "B", 0, 'R');
    $oPdf->setfont('arial', '', 7);
    $oPdf->cell(62, 4, "{$oDadosVinculo->rh30_descr}",      "B", 1);

    $oPdf->setfont('arial', 'b', 7);
    $oPdf->cell(132, 4, "Escola" ,           0, 0, 'C');
    $oPdf->cell(30,  4, "Data Ingresso" ,    0, 0, 'C');
    $oPdf->cell(30,  4, "Data Saída",        0, 1, 'C');
    $oPdf->setfont('arial', '', 7);
    foreach ($aDadosVinculo as $oDadosEscola) {

      $sEscola = str_pad($oDadosEscola->ed75_i_escola, 5, " ", STR_PAD_LEFT) ." - ". trim($oDadosEscola->ed18_c_nome);
      $dtSaida = !empty($oDadosEscola->ed75_i_saidaescola) ? db_formatar($oDadosEscola->ed75_i_saidaescola, 'd') : "";

      $oPdf->cell(132, 4, $sEscola,                                         0, 0, 'L');
      $oPdf->cell(30,  4, db_formatar($oDadosEscola->ed75_d_ingresso, 'd'), 0, 0, 'C');
      $oPdf->cell(30,  4, $dtSaida,                                         0, 1, 'C');

    }
  }
  $oPdf->cell(192, 4, "" ,      "LR", 1);

  desenhaBordaPagina($oPdf, $oConfig, false);
}

function buscaEscolas($aRechumano) {

  $sCampos  = " distinct                                    ";
  $sCampos .= " rechumanoescola.ed75_i_codigo,              ";
  $sCampos .= " rechumanoescola.ed75_i_escola,              ";
  $sCampos .= " rechumanoescola.ed75_i_rechumano,           ";
  $sCampos .= " rechumanoescola.ed75_d_ingresso,            ";
  $sCampos .= " rechumanoescola.ed75_i_saidaescola,         ";
  $sCampos .= " escola.ed18_c_nome,                         ";
  $sCampos .= " rechumano.ed20_i_tiposervidor,              ";
  $sCampos .= " rechumano.ed20_i_rhregime,                  ";
  $sCampos .= " rhregime.rh30_descr,                        ";
  $sCampos .= " case                                        ";
  $sCampos .= "   when ed20_i_tiposervidor = 1              ";
  $sCampos .= "     then rechumanopessoal.ed284_i_rhpessoal ";
  $sCampos .= "   else rechumanocgm.ed285_i_cgm             ";
  $sCampos .= " end as identificacao                        ";
  $sWhere   = " ed75_i_rechumano in (" . implode(', ',$aRechumano). ") ";

  $oDaoRechumanoEscola = new cl_rechumanoescola;
  $sSql = $oDaoRechumanoEscola->sql_query_relacao_trabalho(null, $sCampos, null, $sWhere);
  $rs   = db_query($sSql);
  try {

    if ( !$rs ) {

      $oMsgErro        = new stdClass;
      $oMsgErro->sErro = pg_last_error();
      throw new Exception( _M( MSG_EDU2_DADOSPROFESSOR002 . "erro_buscar_escolas", $oMsgErro) );
    }
  } catch(Exception $oErro) {
    db_redireciona( 'db_erros.php?fechar=true&db_erro='.trim($oErro->getMessage()) );
  }

  $iLinhas = pg_num_rows($rs);

  $aEscolas = array();
  for ($i=0; $i < $iLinhas; $i++) {

    $oDados = db_utils::fieldsMemory($rs, $i);
    $aEscolas[$oDados->ed20_i_tiposervidor][] = $oDados;
  }
  return $aEscolas;
}

/**
 * Responsável pela impressão do quadro de Dados Admissionais
 * @param FPDF $oPdf
 * @param $oConfig
 * @param $aProfissionalEscola
 */
function dadosAdmissionais( FPDF $oPdf, $oConfig, $aProfissionalEscola ) {

  escreveCabecalhoDadosAdmissionais( $oPdf, $oConfig );

  foreach( $aProfissionalEscola as $oProfissionalEscola ) {

    if ( $oProfissionalEscola->getDataSaida() instanceof DBDate) {
      continue;
    }

    $oPdf->setfont( 'arial', 'b', 7 );
    $oPdf->Cell( $oConfig->iXMaximo, $oConfig->iAlturaLinha, '',                'LR', 1, 'L' );
    $oPdf->Cell( $oConfig->iXMaximo, $oConfig->iAlturaLinha, $oProfissionalEscola->getEscola()->getNome(),  'LR', 1, 'C' );
    $oPdf->Cell( $oConfig->iXMaximo, $oConfig->iAlturaLinha, '',                'LR', 1, 'L' );


    $oPdf->Cell( $oConfig->iXMaximo, $oConfig->iAlturaLinha, '', 'LR', 1, 'C' );

    dadosFuncoesExercidasEscola( $oPdf, $oConfig, $oProfissionalEscola );
    imprimeRegimeTrabalho( $oPdf, $oConfig, $oProfissionalEscola );
    dadosHorariosRegencia( $oPdf, $oConfig, $oProfissionalEscola);
    desenhaBordaPagina($oPdf, $oConfig, false);
  }

}

/**
 * Percorre os dados das funções exercidas, agrupando por função/turno/tipo hora, para impressão dos dados
 * @param FPDF $oPdf
 * @param $oConfig
 * @param ProfissionalEscola $oProfissionalEscola
 */
function dadosFuncoesExercidasEscola( FPDF $oPdf, $oConfig, ProfissionalEscola $oProfissionalEscola ) {

  if( count( $oProfissionalEscola->getAtividades() ) == 0 ) {
    return false;
  }

  $aRegistrosAtividade = array();
  $iTotalAtividades    = 0;

  /**
   * Monta a estrutura, agrupando os dados para impressão
   */
  foreach( $oProfissionalEscola->getAtividades() as $oAtividade ) {

    foreach ($oAtividade->getAgenda() as $oAgendaProfissional) {

      $iChave  = $oAtividade->getCodigo() . '#' . $oAgendaProfissional->getTurnoReferente();
      $iChave .= '#' . $oAgendaProfissional->getTipoHoraTrabalho()->getCodigo();

      if (!array_key_exists( $iChave, $aRegistrosAtividade ) ) {

        $oDadosRegistro = new stdClass();
        $oDadosRegistro->sFuncao = $oAtividade->getAtividadeEscolar()->getDescricao();
        $oDadosRegistro->sTurno = AgendaAtividadeProfissional::$aTurnos[$oAgendaProfissional->getTurnoReferente()];
        $oDadosRegistro->sTipoHora = $oAgendaProfissional->getTipoHoraTrabalho()->getAbreviatura();
        $oDadosRegistro->aDiasSemana = array();
        $aRegistrosAtividade[$iChave] = $oDadosRegistro;
      }

      $oDadosDiasemana              = new stdClass();
      $oDadosDiasemana->sDiaSemana  = $oConfig->aDiasSemana[$oAgendaProfissional->getDiaSemana()];
      $oDadosDiasemana->sHoraInicio = $oAgendaProfissional->getHoraInicio();
      $oDadosDiasemana->sHoraFim    = $oAgendaProfissional->getHoraFim();

      $aRegistrosAtividade[$iChave]->aDiasSemana[] = $oDadosDiasemana;
    }
  }

  if (count($aRegistrosAtividade) > 0) {

    $oPdf->setfont( 'arial', 'b', 7 );
    $oPdf->Cell( $oConfig->iXMaximo, $oConfig->iAlturaLinha, 'Função Exercida', 'LR', 1, 'L' );
    $oPdf->Cell( $oConfig->iXMaximo, $oConfig->iAlturaLinha, '',                'LR', 1, 'L' );

  }

  /**
   * Imprime os dados agrupados
   */
  foreach( $aRegistrosAtividade as $oDadosAtividade ) {

    $iValidaAltura = $oPdf->getY() + 4;
    $iAlturaQuebra = $oPdf->h - 15;
    if( $iValidaAltura >= $iAlturaQuebra ) {

      desenhaBordaPagina($oPdf, $oConfig, true);
      $oPdf->AddPage();
      escreveCabecalhoDadosAdmissionais( $oPdf, $oConfig );
      $oPdf->setfont( 'arial', 'b', 7 );
      $oPdf->Cell( $oConfig->iXMaximo, $oConfig->iAlturaLinha, 'Função Exercida', 'LR', 1, 'L' );
      $oPdf->Cell( $oConfig->iXMaximo, $oConfig->iAlturaLinha, '',                'LR', 1, 'L' );
    }

    $oPdf->setfont( 'arial', '', 7 );
    $oPdf->Cell( 22, $oConfig->iAlturaLinha, 'Função/Atividade: ', 'L', 0, 'L' );
    $oPdf->setfont( 'arial', 'b', 7 );
    $oPdf->Cell( 50, $oConfig->iAlturaLinha, $oDadosAtividade->sFuncao, 0, 0, 'L' );

    $oPdf->setfont( 'arial', '', 7 );
    $oPdf->Cell( 20, $oConfig->iAlturaLinha, 'Turno: ', 0, 0, 'L' );
    $oPdf->setfont( 'arial', 'b', 7 );
    $oPdf->Cell( 40, $oConfig->iAlturaLinha, $oDadosAtividade->sTurno, 0, 0, 'L' );

    $oPdf->setfont( 'arial', '', 7 );
    $oPdf->Cell( 20, $oConfig->iAlturaLinha, 'Tipo Hora: ', 0, 0, 'L' );
    $oPdf->setfont( 'arial', 'b', 7 );
    $oPdf->Cell( 40, $oConfig->iAlturaLinha, $oDadosAtividade->sTipoHora, 'R', 1, 'L' );

    $oPdf->setfont( 'arial', 'b', 7 );
    $oPdf->Cell( 64, $oConfig->iAlturaLinha, 'Dia',         1, 0, 'C', 1 );
    $oPdf->Cell( 64, $oConfig->iAlturaLinha, 'Hora Início', 1, 0, 'C', 1 );
    $oPdf->Cell( 64, $oConfig->iAlturaLinha, 'Hora Fim',    1, 1, 'C', 1 );

    foreach( $oDadosAtividade->aDiasSemana as $oDadosDiaSemana ) {

      if( validaQuerbraPagina($oPdf, $oConfig) ) {
        escreveCabecalhoDadosAdmissionais( $oPdf, $oConfig );
      }

      $oPdf->setfont( 'arial', '', 7 );
      $oPdf->Cell( 64, $oConfig->iAlturaLinha, $oDadosDiaSemana->sDiaSemana,  1, 0, 'L' );
      $oPdf->Cell( 64, $oConfig->iAlturaLinha, $oDadosDiaSemana->sHoraInicio, 1, 0, 'C' );
      $oPdf->Cell( 64, $oConfig->iAlturaLinha, $oDadosDiaSemana->sHoraFim,    1, 1, 'C' );
    }

    $iTotalAtividades++;

    if( $iTotalAtividades < count( $aRegistrosAtividade ) ) {
      $oPdf->Cell( $oConfig->iXMaximo, $oConfig->iAlturaLinha, '', 'LR', 1, 'C' );
    }
  }
}

function imprimeRegimeTrabalho( FPDF $oPdf, $oConfig, ProfissionalEscola $oProfissionalEscola ) {

  $sCampos = "ed24_c_descr, ed10_c_descr, ed25_c_descr, ed128_abreviatura ";
  $sWhere  = " ed23_i_rechumanoescola = {$oProfissionalEscola->getCodigo()} ";
  $sWhere .= " and ed23_ativo is true ";
  $sWhere .= " group by {$sCampos} ";
  $oDaoRelacaoTrabalho = new cl_relacaotrabalho();
  $sSqlRelacaoTrabalho = $oDaoRelacaoTrabalho->sql_query_relacaotrabalho(null, $sCampos, null, $sWhere);
  $rsRelacaoTrabalho   = db_query($sSqlRelacaoTrabalho);

  try {

    if (!$rsRelacaoTrabalho) {

      $oMsgErro        = new stdClass;
      $oMsgErro->sErro = pg_last_error();
      throw new Exception( _M( MSG_EDU2_DADOSPROFESSOR002 . "erro_buscar_regimetrabalho", $oMsgErro ) );
    }
  } catch(Exception $oErro) {
    db_redireciona( 'db_erros.php?fechar=true&db_erro='.trim($oErro->getMessage()) );
  }

  $iLinhas = pg_num_rows($rsRelacaoTrabalho);

  if ( $iLinhas == 0 ) {
    return false;
  }

  $iValidaAltura = $oPdf->getY() + ($oConfig->iAlturaLinha * 4); // conta as linhas em branco
  $iAlturaQuebra = $oPdf->h - 15;

  if( $iValidaAltura >= $iAlturaQuebra ) {

    $oPdf->AddPage();
    escreveCabecalhoDadosAdmissionais( $oPdf, $oConfig );
  }

  $oPdf->setfont( 'arial', 'B', 7 );
  $oPdf->cell(192, 8, "", 0, 1);
  $oPdf->cell(192, $oConfig->iAlturaLinha, "Regime Trabalho", 0, 1);
  $oPdf->ln();

  for ($i=0; $i < $iLinhas; $i++) {

    if( validaQuerbraPagina($oPdf, $oConfig) ) {

      escreveCabecalhoDadosAdmissionais( $oPdf, $oConfig );
      $oPdf->cell(192, $oConfig->iAlturaLinha, "Regime Trabalho", 0, 1);
      $oPdf->ln();
    }

    $oDados = db_utils::fieldsMemory($rsRelacaoTrabalho, $i);
    $oPdf->setfont( 'arial', '', 7 );
    $oPdf->cell(20, $oConfig->iAlturaLinha, "Regime: ", 0, 0);
    $oPdf->setfont( 'arial', 'B', 7 );
    $oPdf->cell(70, $oConfig->iAlturaLinha, $oDados->ed24_c_descr, 0, 0);
    $oPdf->setfont( 'arial', '', 7 );
    $oPdf->cell(20, $oConfig->iAlturaLinha, "Tipo de Hora: ", 0, 0);
    $oPdf->setfont( 'arial', 'B', 7 );
    $oPdf->cell(50, $oConfig->iAlturaLinha, $oDados->ed128_abreviatura, 0, 1);

    if ( !empty($oDados->ed25_c_descr) ) {

      $oPdf->setfont( 'arial', '', 7 );
      $oPdf->cell(20, $oConfig->iAlturaLinha, "Nível de Ensino: ", 0, 0);
      $oPdf->setfont( 'arial', 'B', 7 );
      $oPdf->cell(70, $oConfig->iAlturaLinha, $oDados->ed10_c_descr, 0, 1);
      $oPdf->setfont( 'arial', '', 7 );
      $oPdf->cell(20, $oConfig->iAlturaLinha, "Área de Trabalho: ", 0, 0);
      $oPdf->setfont( 'arial', 'B', 7 );
      $oPdf->cell(70, $oConfig->iAlturaLinha, $oDados->ed25_c_descr, 0, 1);

    }
    $oPdf->ln(0.5);
  }
}

/**
 * Imprime as informações referentes aos Dados Pessoais
 * @param FPDF $oPdf
 * @param $oConfig
 * @param CgmFisico $oCgm
 */
function imprimeDadosPessoais( FPDF $oPdf, $oConfig, CgmFisico $oCgm ) {

  $iTamanhoCampoColuna1     = 30;
  $iTamanhoCampoColuna2     = 30;
  $iTamanhoDescricaoColuna1 = 75;
  $iTamanhoDescricaoColuna2 = 65;

  $sDataNascimento = '';

  if ( $oCgm->getDataNascimento() != '' ) {

    $oDataNascimento = new DBDate( $oCgm->getDataNascimento() );
    $sDataNascimento = $oDataNascimento->getDate( DBDate::DATA_PTBR );
  }

  $sSexo        = $oCgm->getSexo() == 'M' ? 'MASCULINO' : 'FEMININO';
  $sMunicipioUf = $oCgm->getMunicipio() . ' / ' . $oCgm->getUf();

  $sEnderecoCompleto  = $oCgm->getLogradouro() . ', ' . $oCgm->getNumero();
  $sEnderecoCompleto .= $oCgm->getComplemento() != '' ? ' / ' . $oCgm->getComplemento() : '';

  $aEstadoCivil = array(
                         1 => 'SOLTEIRO',
                         2 => 'CASADO',
                         3 => 'VIÚVO',
                         4 => 'DIVORCIADO',
                         5 => 'SEPARADO CONSENSUAL',
                         6 => 'SEPARADO JUDICIAL',
                         7 => 'UNIÃO ESTÁVEL'
                       );

  $oPdf->setfont( 'arial', 'b', 7 );
  $oPdf->Cell( $oConfig->iXMaximo, $oConfig->iAlturaLinha, 'DADOS PESSOAIS', 1, 1, 'C', 1 );
  $oPdf->Cell( $oConfig->iXMaximo, 4, "", "LR", 1, "C" );

  $iPosicaoY = $oPdf->GetY();
  $oPdf->Cell( 5, 32, "", "LB", 0, "C" );

  $oPdf->setfont( 'arial', '', 7 );
  $oPdf->Cell( $iTamanhoCampoColuna1, $oConfig->iAlturaLinha, "CGM:",            0, 2, "L" );
  $oPdf->Cell( $iTamanhoCampoColuna1, $oConfig->iAlturaLinha, "Nome:",           0, 2, "L" );
  $oPdf->Cell( $iTamanhoCampoColuna1, $oConfig->iAlturaLinha, "Endereço:",       0, 2, "L" );
  $oPdf->Cell( $iTamanhoCampoColuna1, $oConfig->iAlturaLinha, "Bairro:",         0, 2, "L" );
  $oPdf->Cell( $iTamanhoCampoColuna1, $oConfig->iAlturaLinha, "Município / UF:", 0, 2, "L" );
  $oPdf->Cell( $iTamanhoCampoColuna1, $oConfig->iAlturaLinha, "CEP:",            0, 2, "L" );
  $oPdf->Cell( $iTamanhoCampoColuna1, $oConfig->iAlturaLinha, "Email:",          0, 2, "L" );

  $oPdf->setfont( 'arial', 'b', 7 );
  $oPdf->SetXY( $iTamanhoCampoColuna1 + 5, $iPosicaoY );
  $oPdf->Cell( $iTamanhoDescricaoColuna1, $oConfig->iAlturaLinha, $oCgm->getCodigo(),        0, 2, "L" );
  $oPdf->Cell( $iTamanhoDescricaoColuna1, $oConfig->iAlturaLinha, $oCgm->getNomeCompleto(),  0, 2, "L" );
  $oPdf->Cell( $iTamanhoDescricaoColuna1, $oConfig->iAlturaLinha, $sEnderecoCompleto,        0, 2, "L" );
  $oPdf->Cell( $iTamanhoDescricaoColuna1, $oConfig->iAlturaLinha, $oCgm->getBairro(),        0, 2, "L" );
  $oPdf->Cell( $iTamanhoDescricaoColuna1, $oConfig->iAlturaLinha, $sMunicipioUf,             0, 2, "L" );
  $oPdf->Cell( $iTamanhoDescricaoColuna1, $oConfig->iAlturaLinha, $oCgm->getCep(),           0, 2, "L" );
  $oPdf->Cell( 167,                       $oConfig->iAlturaLinha, $oCgm->getEmail(),       "R", 2, "L" );

  $oPdf->SetXY( $iTamanhoCampoColuna1 + $iTamanhoDescricaoColuna1, $iPosicaoY );

  $oPdf->setfont('arial','',7);
  $oPdf->cell( $iTamanhoCampoColuna2, $oConfig->iAlturaLinha, "Nascimento:",       0, 2, "L" );
  $oPdf->cell( $iTamanhoCampoColuna2, $oConfig->iAlturaLinha, "Naturalidade:",     0, 2, "L" );
  $oPdf->cell( $iTamanhoCampoColuna2, $oConfig->iAlturaLinha, "Sexo:",             0, 2, "L" );
  $oPdf->cell( $iTamanhoCampoColuna2, $oConfig->iAlturaLinha, "Estado Civil:",     0, 2, "L" );
  $oPdf->cell( $iTamanhoCampoColuna2, $oConfig->iAlturaLinha, "Telefone:",         0, 2, "L" );
  $oPdf->cell( $iTamanhoCampoColuna2, $oConfig->iAlturaLinha, "Telefone Celular:", 0, 2, "L" );

  $oPdf->setfont( 'arial', 'b', 7 );
  $oPdf->SetXY( $iTamanhoCampoColuna1 + $iTamanhoDescricaoColuna1 + $iTamanhoCampoColuna2 + 2, $iPosicaoY );
  $oPdf->cell( $iTamanhoDescricaoColuna2, $oConfig->iAlturaLinha, $sDataNascimento,                       'R', 2, "L" );
  $oPdf->cell( $iTamanhoDescricaoColuna2, $oConfig->iAlturaLinha, $oCgm->getNaturalidade(),               'R', 2, "L" );
  $oPdf->cell( $iTamanhoDescricaoColuna2, $oConfig->iAlturaLinha, $sSexo,                                 'R', 2, "L" );
  $oPdf->cell( $iTamanhoDescricaoColuna2, $oConfig->iAlturaLinha, $aEstadoCivil[$oCgm->getEstadoCivil()], 'R', 2, "L" );
  $oPdf->cell( $iTamanhoDescricaoColuna2, $oConfig->iAlturaLinha, $oCgm->getTelefone(),                   'R', 2, "L" );
  $oPdf->cell( $iTamanhoDescricaoColuna2, $oConfig->iAlturaLinha, $oCgm->getCelular(),                    'R', 2, "L" );

  $oPdf->SetXY( 15, $oPdf->GetY() + 4 );
  $oPdf->Cell( $oConfig->iXMaximo - 5, $oConfig->iAlturaLinha, "", "BR", 1, "L" );
}

/**
 * Responsável pela impressão do cabeçalho dos Dados Admissionais, pois em caso de quebra de página destes dados, é
 * necessário imprimí-lo novamente
 * @param FPDF $oPdf
 * @param $oConfig
 */
function escreveCabecalhoDadosAdmissionais( FPDF $oPdf, $oConfig ) {

  $oPdf->setfont( 'arial', 'b', 7 );
  $oPdf->Cell( $oConfig->iXMaximo, $oConfig->iAlturaLinha, 'DADOS ADMISSIONAIS', 1, 1, 'C', 1 );

}

/**
 * Imprime os dados referente aos horários da regência do docente
 * @param  FPDF               $oPdf
 * @param  stdClas            $oConfig
 * @param  ProfissionalEscola $oProfissionalEscola
 */
function dadosHorariosRegencia( FPDF $oPdf, $oConfig, ProfissionalEscola $oProfissionalEscola ) {

  $aHorariosRegencia = $oProfissionalEscola->getHorariosRegencia();

  if ( count($aHorariosRegencia) == 0 ) {
    return;
  }

  $lPrimeiraImpressao = true;

  foreach ( $aHorariosRegencia as $iDiaSemana => $aDiasSemanaRegencia ) {

    $iYTamanho      = $oPdf->getY() - $oConfig->iTMargin;
    $iTotalPeriodos = array_sum(array_map("count", $aDiasSemanaRegencia)) + 3;
    $iYTamanho      = $iYTamanho + ($iTotalPeriodos * $oConfig->iAlturaLinha );

    if ( $iYTamanho > ($oConfig->iYMaximo -3) ) {

      $lPrimeiraImpressao = true;
      desenhaBordaPagina($oPdf, $oConfig, false);
      $oPdf->AddPage();
      escreveCabecalhoDadosAdmissionais(  $oPdf, $oConfig  );
    }

    if ( $lPrimeiraImpressao ) {

      $oPdf->setfont( 'arial', 'b', 7 );
      $oPdf->Cell( $oConfig->iXMaximo, $oConfig->iAlturaLinha, '',                     'LR', 1, 'L' );
      $oPdf->Cell( $oConfig->iXMaximo, $oConfig->iAlturaLinha, 'Horários de Regência', 'LR', 1, 'L' );
    }

    $oPdf->setfont( 'arial', 'b', 7 );
    $oPdf->Cell( $oConfig->iXMaximo, $oConfig->iAlturaLinha, '',                'LR', 1, 'L' );
    $oPdf->Cell( $oConfig->iXMaximo, $oConfig->iAlturaLinha, $oConfig->aDiasSemana[$iDiaSemana], 'LR', 1, 'L' );


    $oPdf->Cell( 38.4, $oConfig->iAlturaLinha, "Turno",        1, 0, 'C', 1 );
    $oPdf->Cell( 38.4, $oConfig->iAlturaLinha, "Período",      1, 0, 'C', 1 );
    $oPdf->Cell( 38.4, $oConfig->iAlturaLinha, "Horário",      1, 0, 'C', 1 );
    $oPdf->Cell( 38.4, $oConfig->iAlturaLinha, "Tipo Hora",    1, 0, 'C', 1 );
    $oPdf->Cell( 38.4, $oConfig->iAlturaLinha, "H. Atividade", 1, 1, 'C', 1 );

    $oPdf->setfont( 'arial', '', 7 );

    foreach ( $aDiasSemanaRegencia as $sTurno => $aPeriodos ) {

      ksort($aPeriodos);
      $iPeriodos = count( $aPeriodos );
      $oPdf->Cell( 38.4, $iPeriodos * $oConfig->iAlturaLinha, $sTurno, 1, 0, 'C');

      $lImprimeColunaBranco = false;

      foreach ($aPeriodos as $oPeriodo ) {

        if ( $lImprimeColunaBranco ) {
          $oPdf->Cell( 38.4, $oConfig->iAlturaLinha, '', 0, 0, 'L' );
        }

        $oPdf->Cell( 38.4, $oConfig->iAlturaLinha, $oPeriodo->sPeriodo,                                          1, 0, 'L' );
        $oPdf->Cell( 38.4, $oConfig->iAlturaLinha, "{$oPeriodo->sHorarioInicial} às {$oPeriodo->sHorarioFinal}", 1, 0, 'L' );
        $oPdf->Cell( 38.4, $oConfig->iAlturaLinha, $oPeriodo->sTipoHoraAbreviatura,                                         1, 0, 'L' );
        $oPdf->Cell( 38.4, $oConfig->iAlturaLinha, $oPeriodo->sHoraAtividade,                                    1, 1, 'L' );
        $lImprimeColunaBranco = true;
      }
    }

    $lPrimeiraImpressao = false;
  }


  desenhaBordaPagina($oPdf, $oConfig, false);
}


$oPdf->Output();