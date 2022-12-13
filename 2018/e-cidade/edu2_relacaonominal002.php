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

require_once ("fpdf151/scpdf.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");

db_app::import("exceptions.*");
db_app::import("educacao.*");
db_app::import("educacao.avaliacao.*");

$oDadosFormulario = db_utils::postMemory($_GET);
$oJson            = new Services_JSON();
$aTurmas          = $oJson->decode(str_replace("\\","",$oDadosFormulario->aTurmas));
$iEscola          = db_getsession("DB_coddepto");

/**
 * Concatenamos o tipo e modelo em uma variavel, para comparar no array dos titulos possiveis para o cabecalho
 */
$sTipoModelo = "{$oDadosFormulario->iTipo}{$oDadosFormulario->iModelo}";

/**
 * Array com os titulos que devem ser apresentados no cabecalho, de acordo com o Tipo e Modelo selecionados
 */
$aTituloCabecalho = array(
                          "11" => "RELAÇÃO NOMINAL - MATRICULA INICIAL",
                          "12" => "RELAÇÃO NOMINAL - MATRICULA INICIAL",
                          "21" => "RELAÇÃO NOMINAL - MATRICULA FINAL",
                          "22" => "RELAÇÃO NOMINAL - MATRICULA FINAL"
                         );

/**
 * stdClass com filtros padroes a serem utilizados no relatorio
 */
$oFiltros = new stdClass();

/**
 * Codigo do diretor da escola selecionado
 */
$oFiltros->iDiretor = $oDadosFormulario->iDiretor;

/**
 * Valor que identifica se deve ser exibida Pre-Escola ou nao
 */
$oFiltros->iPreEscola = $oDadosFormulario->iPreEscola;

/**
 * Largura maxima da pagina
 */
$oFiltros->iLarguraMaxima = 280;

/**
 * Altura maxima da pagina
 */
$oFiltros->iAlturaMaxima = 185;

/**
 * Titulo do cabecalho
 */
$oFiltros->sTituloCabecalho = '';

/**
 * Limite de alunos permitidos por pagina
 */
$oFiltros->iMaximoAlunosPorPagina = 35;

/**
 * Altura da linha dos alunos
 */
$oFiltros->iAlturaLinhaPadrao = 4;

/**
 * Altura da linha do cabecalho
 */
$oFiltros->iAlturaLinhaCabecalho = 4;

/**
 * Tamanho maximo das colunas que serao apresentadas em todos os modelos
 */
$oFiltros->iColunaNumero         = 5;
$oFiltros->iColunaAluno          = 194;
$oFiltros->iColunaSexo           = 8;
$oFiltros->iColunaDataNascimento = 25;
$oFiltros->iColunaIdadeMeses     = 18;
$oFiltros->iColunaCorRaca        = 30;

/**
 * Percorremos o array com os titulos, para guardar o titulo que deve ser apresentado
 * Validamos tambem pelo titulo, o tamanho das colunas
 */
if (array_key_exists($sTipoModelo, $aTituloCabecalho)) {

  $oFiltros->sTituloCabecalho = $aTituloCabecalho[$sTipoModelo];
  $oFiltros->sTipoModelo      = $sTipoModelo;

  /**
   * Validacao do tipo e modelo selecionado. Por padrao, quando for '11', nao eh validado pois este eh o padrao ja
   * calculado
   */
  switch($sTipoModelo) {

    /**
     * 1 - Matricula Inicial
     * 2 - Demais Cursos
     */
    case ("12"):

      if ($oFiltros->iPreEscola == 2) {

        $oFiltros->iColunaAluno     = 142;
        $oFiltros->iColunaPreEscola = 20;
      } else {
        $oFiltros->iColunaAluno = 162;
      }
      $oFiltros->iColunaSituacaoMatricula = 30;
      break;

    /**
     * 2 - Matricula Final
     * 1 - Educacao Infantil
     */
    case ("21"):

      $oFiltros->iColunaAluno            = 97;
      tamanhosColunasMatriculaFinal($oFiltros);
      break;

    /**
     * 2 - Matricula Final
     * 2 - Demais Cursos
     */
    case ("22"):

      $oFiltros->iColunaAluno             = 81;
      $oFiltros->iColunaSituacaoMatricula = 16;
      tamanhosColunasMatriculaFinal($oFiltros);
      break;
  }
}

$oPdf = new scpdf();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFillColor(225);

/**
 * Percorremos as turmas selecionadas, imprimindo uma turma por pagina
 */
foreach ($aTurmas as $oTurmaSelecionada) {

  $oTurma = TurmaRepository::getTurmaByCodigo($oTurmaSelecionada->ed57_i_codigo);
  $oEtapa = EtapaRepository::getEtapaByCodigo($oTurmaSelecionada->codigo_etapa);

  $oFiltros->sTurma  = $oTurma->getDescricao();
  $oFiltros->sTurno  = $oTurma->getTurno()->getDescricao();
  $oFiltros->sEtapa  = $oEtapa->getNome();
  $oFiltros->iEnsino = $oEtapa->getEnsino()->getCodigo();
  $oFiltros->iAno    = $oTurma->getCalendario()->getAnoExecucao();

  $oFiltros->aAlunosMatriculados = array();
  $oFiltros->aAlunosMatriculados = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
  $oFiltros->iTotalAlunosTurma   = count($oFiltros->aAlunosMatriculados);
  $oFiltros->aAlunosPorPagina    = array();
  $oFiltros->dtInicio            = new DBDate($oTurma->getCalendario()->getDataInicio()->getDate());

  /**
   * Variaveis de controle do array de alunos por pagina
   */
  $iPagina   = 0;
  $iContador = 1;

  /**
   * Montamos o array com os alunos que devem ser impressos por pagina
   */
  foreach ($oFiltros->aAlunosMatriculados as $oMatricula) {

    $oFiltros->aAlunosPorPagina[$iPagina][$iContador] = $oMatricula;
    if ($iContador > $oFiltros->iMaximoAlunosPorPagina - 1) {

      $iPagina++;
      $iContador = 1;
    }
    $iContador++;
  }

  /**
   * Percorremos os alunos a serem impressos por pagina
   */
  foreach ($oFiltros->aAlunosPorPagina as $iIndice => $oFiltros->aAlunosPorPagina) {

    $oPdf->AddPage("L");
    cabecalhoRelatorio($oPdf, $oFiltros, $oTurma);
    corpoRelatorio($oPdf, $oFiltros, $oTurma);
    rodapeRelatorio($oPdf, $oFiltros, $oTurma);
  }
}

/**
 * Cabecalho padrao para todos os modelos de relatorio
 * @param SCPDF $oPdf
 * @param stdClass $oFiltros
 * @param Turma $oTurma
 */
function cabecalhoRelatorio($oPdf, $oFiltros, $oTurma) {

  $sNomeEscola       = $oTurma->getEscola()->getNome();
  $iCodigoReferencia = $oTurma->getEscola()->getCodigoReferencia();

  if ( $iCodigoReferencia != null ) {
    $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
  }

  $sDadosCabecalho  = $oTurma->getEscola()->getDepartamento()->getInstituicao()->getDescricao()."\n";
  $sDadosCabecalho .= "SECRETÁRIA MUNICIPAL DE EDUCAÇÃO"."\n";
  $sDadosCabecalho .= $sNomeEscola."\n\n";
  $sDadosCabecalho .= $oFiltros->sTituloCabecalho."\n";
  $sDadosCabecalho .= $oTurma->getBaseCurricular()->getCurso()->getEnsino()->getNome()."\n\n\n";

  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->SetXY(90, 10);
  $oPdf->MultiCell(100, 3, $sDadosCabecalho, 0, "C");

  $iPosicaoY = $oPdf->GetY();

  /**
   * Verificamos se existe o logo do municipio para impressao
   */
  if ($oTurma->getEscola()->getLogo() != '') {

    $oPdf->SetXY(30, 10);
    $oPdf->Image("imagens/files/".$oTurma->getEscola()->getLogo(), $oPdf->GetX(), $oPdf->GetY()-6, 18);
  }

  /**
   * Verificamos se existe o logo da escola para impressao
   */
  if ($oTurma->getEscola()->getLogoEscola() != '') {

    $oPdf->SetXY(90, 10);
    $oPdf->Image("imagens/".$oTurma->getEscola()->getLogoEscola(), 230, $oPdf->GetY()-6, 18);
  }

  $oPdf->SetXY(30, $iPosicaoY);
  $oPdf->Cell(60,  4, "Etapa: {$oFiltros->sEtapa}", 0, 0, "C");
  $oPdf->Cell(20,  4, "Ano: {$oFiltros->iAno}",     0, 0, "C");
  $oPdf->Cell(100, 4, "Turma: {$oFiltros->sTurma}", 0, 0, "C");
  $oPdf->Cell(30,  4, "Turno: {$oFiltros->sTurno}", 0, 1, "C");

  $oPdf->SetXY(10, 45);
  $oPdf->SetFont('arial', 'b', 7);
  $oPdf->Cell($oFiltros->iColunaNumero,         $oFiltros->iAlturaLinhaCabecalho, "Nº",                 1, 0, "C");
  $oPdf->Cell($oFiltros->iColunaAluno,          $oFiltros->iAlturaLinhaCabecalho, "Nome do Aluno",      1, 0, "C");
  $oPdf->Cell($oFiltros->iColunaSexo,           $oFiltros->iAlturaLinhaCabecalho, "Sexo",               1, 0, "C");
  $oPdf->Cell($oFiltros->iColunaDataNascimento, $oFiltros->iAlturaLinhaCabecalho, "Data de Nascimento", 1, 0, "C");
  $oPdf->Cell($oFiltros->iColunaIdadeMeses,     $oFiltros->iAlturaLinhaCabecalho, "Idade / Meses",      1, 0, "C");

  switch ($oFiltros->sTipoModelo) {

    /**
     * 1 - Matricula Inicial
     * 2 - Demais Cursos
     */
    case ("12"):

      $oPdf->Cell($oFiltros->iColunaSituacaoMatricula, $oFiltros->iAlturaLinhaCabecalho, "Situação de Matrícula", 1, 0, "C");

      if ($oFiltros->iPreEscola == 2) {
        $oPdf->Cell($oFiltros->iColunaPreEscola, $oFiltros->iAlturaLinhaCabecalho, "Pré-Escola", 1, 0, "C");
      }
      break;

    /**
     * 2 - Matricula Final
     * 1 - Educacao Infantil
     */
    case ("21"):

      colunasMatriculaFinal($oPdf, $oFiltros);
      break;

    /**
     * 2 - Matricula Final
     * 2 - Demais Cursos
     */
    case ("22"):

      $oPdf->MultiCell($oFiltros->iColunaSituacaoMatricula, 6, "Situação de Matrícula", 1, "C");
      $oPdf->SetXY(163, 45);
      colunasMatriculaFinal($oPdf, $oFiltros);
      break;
  }

  $oPdf->Cell($oFiltros->iColunaCorRaca, $oFiltros->iAlturaLinhaCabecalho, "Cor / Raça", 1, 1, "C");

  $oFiltros->iPosicaoX = $oPdf->GetX();
  $oFiltros->iPosicaoY = $oPdf->GetY();
}

/**
 * Metodo para montar o corpo do relatorio, de acordo com os filtros selecionados
 * @param SCPDF $oPdf
 * @param stdClass $oFiltros
 * @param Turma $oTurma
 */
function corpoRelatorio($oPdf, $oFiltros, $oTurma) {

  $oPdf->SetXY($oFiltros->iPosicaoX, $oFiltros->iPosicaoY);
  $iAlunosImpressos = 0;

  /**
   * Pegamos a data atual para calcular a data de nascimento do aluno
   */
  $dtAtual = date("Y-m-d");

  foreach ($oFiltros->aAlunosPorPagina as $oMatricula) {

    /**
     * Caso situacao seja troca de turma, nao apresentamos no relatorio
     */
    if ($oMatricula->getSituacao() == 'TROCA DE TURMA') {
      continue;
    }

    /**
     * Valida quebra de pagina
     */
    if ($oPdf->GetY() > $oFiltros->iAlturaMaxima) {

      $oPdf->AddPage("L");
      cabecalhoRelatorio($oPdf, $oFiltros, $oTurma);
      rodapeRelatorio($oPdf, $oFiltros, $oTurma);
      $iAlunosImpressos = 0;
    }

    /**
     * Verificamos se trata-se de um aluno repetente
     */
    $sSituacao = 'NOVO';
    if (($oMatricula->getTipo() == 'R' && $oMatricula->getResultadoFinalAnterior() == 'R')) {
      $sSituacao = 'REPETENTE';
    }

    $oData           = $oMatricula->getAluno()->getIdadeNaData($dtAtual);
    $oDataNascimento = new DBDate($oMatricula->getAluno()->getDataNascimento());
    $sDataNascimento = $oDataNascimento->convertTo(DBDate::DATA_PTBR);

    $oFiltros->sEvadido     = '';
    $oFiltros->sTransferido = '';

    /**
     * Verificamos se o aluno foi transferido ou evadido, e buscamos o mes para apresentar no relatorio
     */
    if ($oMatricula->getSituacao() == 'TRANSFERIDO FORA' || $oMatricula->getSituacao() == 'TRANSFERIDO REDE') {
      $oFiltros->sEvadido = db_mes($oMatricula->getDataEncerramento()->getMes(), 2);
    } else if ($oMatricula->getSituacao() == 'EVADIDO') {
      $oFiltros->sTransferido = db_mes($oMatricula->getDataEncerramento()->getMes(), 2);
    }

    $oPdf->SetFont('arial', '', 6);
    $oPdf->Cell($oFiltros->iColunaNumero,         $oFiltros->iAlturaLinhaPadrao, $oMatricula->getNumeroOrdemAluno(), 1, 0, "C");
    $oPdf->Cell($oFiltros->iColunaAluno,          $oFiltros->iAlturaLinhaPadrao, $oMatricula->getAluno()->getNome(), 1, 0, "L");
    $oPdf->Cell($oFiltros->iColunaSexo,           $oFiltros->iAlturaLinhaPadrao, $oMatricula->getAluno()->getSexo(), 1, 0, "C");
    $oPdf->Cell($oFiltros->iColunaDataNascimento, $oFiltros->iAlturaLinhaPadrao, $sDataNascimento,                   1, 0, "C");
    $oPdf->Cell($oFiltros->iColunaIdadeMeses,     $oFiltros->iAlturaLinhaPadrao, "{$oData->anos} / {$oData->meses}", 1, 0, "C");

    switch ($oFiltros->sTipoModelo) {

      /**
       * 1 - Matricula Inicial
       * 2 - Demais Cursos
       */
      case ("12"):

        $sPreEscola = 'NÃO';
        if ($oMatricula->getAluno()->temPreEscolaNaRede()) {
          $sPreEscola = 'SIM';
        }

        $oPdf->Cell($oFiltros->iColunaSituacaoMatricula, $oFiltros->iAlturaLinhaPadrao, $sSituacao,  1, 0, "C");
        if ($oFiltros->iPreEscola == 2) {
          $oPdf->Cell($oFiltros->iColunaPreEscola, $oFiltros->iAlturaLinhaPadrao, $sPreEscola, 1, 0, "C");
        }
        break;

      /**
       * 2 - Matricula Final
       * 1 - Educacao Infantil
       */
      case ("21"):

        preencheColunasMatriculaFinal($oPdf, $oFiltros, $oMatricula);
        break;

      /**
       * 2 - Matricula Final
       * 2 - Demais Cursos
       */
      case ("22"):

        $oPdf->Cell($oFiltros->iColunaSituacaoMatricula, $oFiltros->iAlturaLinhaPadrao, $sSituacao, 1, 0, "C");
        preencheColunasMatriculaFinal($oPdf, $oFiltros, $oMatricula);
        break;
    }

    $oPdf->Cell($oFiltros->iColunaCorRaca, $oFiltros->iAlturaLinhaPadrao, $oMatricula->getAluno()->getRaca(), 1, 1, "C");
    $iAlunosImpressos++;
  }
}

/**
 * Metodo para montar o corpo do relatorio, de acordo com os filtros seleciondos
 * @param SCPDF $oPdf
 * @param stdClass $oFiltros
 * @param Turma $oTurma
 */
function rodapeRelatorio($oPdf, $oFiltros, $oTurma) {

  $oPdf->SetFont('arial', '', 7);
  $oPdf->SetXY(10, 190);

  $sMunicipioData  = "{$oTurma->getEscola()->getMunicipio()}, ".date("d", db_getsession("DB_datausu"))." de ";
  $sMunicipioData .= ucfirst(db_mes(date("m", db_getsession("DB_datausu"))))." de ".date("Y", db_getsession("DB_datausu")).".";
  $oPdf->Cell($oFiltros->iLarguraMaxima, $oFiltros->iAlturaLinhaPadrao, $sMunicipioData, 0, 1, "C");

  /**
   * Buscamos as informacoes referentes ao diretor e percorremos os dados
   */
  $sDiretor = '';
  if (!empty($oFiltros->iDiretor)) {

    $aDiretor = $oTurma->getEscola()->getDiretor($oFiltros->iDiretor);
    foreach ($aDiretor as $oDiretor) {

      $sDiretor    = ucwords(strtolower($oDiretor->sNome));
      $sFuncaoAto = "Diretor";

      if (!empty($oDiretor->sAtoLegal) && !empty($oDiretor->iNumero)) {
        $sFuncaoAto .= " - ".ucwords(strtolower($oDiretor->sAtoLegal))." - Nº: {$oDiretor->iNumero}";
      }
    }
    $oPdf->Ln(4);
    $oPdf->Cell($oFiltros->iLarguraMaxima, 3, $sDiretor,   0, 1, "C");
    $oPdf->Cell($oFiltros->iLarguraMaxima, 3, $sFuncaoAto, 0, 1, "C");
  }
}

/**
 * Altera o tamanho das colunas extras quando for tipo 1 - Matricula Final
 * @param stdClass $oFiltros
 */
function tamanhosColunasMatriculaFinal($oFiltros) {

  $oFiltros->iColunaAfastado          = 46;
  $oFiltros->iColunaEvasaoMes         = 23;
  $oFiltros->iColunaTransferenciaMes  = 23;
  $oFiltros->iColunaTransferencia     = 24;
  $oFiltros->iColunaResultadoFinal    = 25;
  $oFiltros->iAlturaLinhaCabecalho    = 12;
  $oFiltros->iMaximoAlunosPorPagina   = 32;
}

/**
 * Colunas extras que devem ser impressas quando for tipo 1 - Matricula Final
 * @param SCPDF $oPdf
 * @param stdClass $oFiltros
 */
function colunasMatriculaFinal($oPdf, $oFiltros) {

  $oPdf->Cell($oFiltros->iColunaAfastado, $oFiltros->iAlturaLinhaPadrao, "Afastado Por:", 1, 0, "C");

  $oPdf->SetXY(163, 49);
  $oPdf->Cell($oFiltros->iColunaEvasaoMes,        $oFiltros->iAlturaLinhaPadrao, "Evasão", 1, 0, "C");
  $oPdf->Cell($oFiltros->iColunaTransferenciaMes, $oFiltros->iAlturaLinhaPadrao, "Transferência", 1, 0, "C");

  $oPdf->SetXY(163, 53);
  $oPdf->Cell($oFiltros->iColunaEvasaoMes,        $oFiltros->iAlturaLinhaPadrao, "Mês", 1, 0, "C");
  $oPdf->Cell($oFiltros->iColunaTransferenciaMes, $oFiltros->iAlturaLinhaPadrao, "Mês", 1, 0, "C");

  $sIngressoTransferencia = "Ingresso com transferência em: ";
  $oPdf->SetXY(209, 45);
  $oPdf->MultiCell($oFiltros->iColunaTransferencia, 6, $sIngressoTransferencia, 1, "C");
  $oPdf->SetXY(233, 45);
  $oPdf->Cell($oFiltros->iColunaResultadoFinal, $oFiltros->iAlturaLinhaCabecalho, "Resultado Final", 1, 0, "C");
}

/**
 * Preenche as colunas extras que devem ser impressas quando for tipo 1 - Matricula Final
 * @param SCPDF $oPdf
 * @param stdClass $oFiltros
 * @param Matricula $oMatricula
 */
function preencheColunasMatriculaFinal($oPdf, $oFiltros, $oMatricula) {

  $sResultadoFinal = '';

  db_inicio_transacao();
  $oDiarioClasse = $oMatricula->getDiarioDeClasse();
  db_fim_transacao();

  /**
   * Buscamos o resultado final de acordo com o termo do ensino
   */
  $aResultadoFinal = DBEducacaoTermo::getTermoEncerramento($oFiltros->iEnsino,
                                                           $oDiarioClasse->getResultadoFinal(),
                                                           $oFiltros->iAno);

  if (count($aResultadoFinal) > 0) {

    foreach ($aResultadoFinal as $oResultadoFinal) {
      $sResultadoFinal = $oResultadoFinal->sDescricao;
    }
  }

  if ($oMatricula->getSituacao() != 'MATRICULADO') {
    $sResultadoFinal = $oMatricula->getSituacao();
  }

  $dtTransferencia = '';
  $oDataMatricula  = $oMatricula->getDataMatricula();

  if (DBDate::calculaIntervaloEntreDatas($oDataMatricula, $oFiltros->dtInicio, 'd') > 0) {
    $dtTransferencia = $oDataMatricula->getDia()."/".$oDataMatricula->getMes();
  }

  $iColunaDividida = $oFiltros->iColunaAfastado / 2;
  $oPdf->Cell($iColunaDividida,                 $oFiltros->iAlturaLinhaPadrao, $oFiltros->sTransferido, 1, 0, "C");
  $oPdf->Cell($iColunaDividida,                 $oFiltros->iAlturaLinhaPadrao, $oFiltros->sEvadido,     1, 0, "C");
  $oPdf->Cell($oFiltros->iColunaTransferencia,  $oFiltros->iAlturaLinhaPadrao, $dtTransferencia,        1, 0, "C");
  $oPdf->Cell($oFiltros->iColunaResultadoFinal, $oFiltros->iAlturaLinhaPadrao, $sResultadoFinal,        1, 0, "C");
}

$oPdf->Output();
?>