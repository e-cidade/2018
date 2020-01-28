<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");

$oGet   = db_utils::postMemory($_GET);
$aWhere = array();

if(!empty($oGet->iEscola)) {
  $aWhere[] = " ed18_i_codigo = {$oGet->iEscola} ";
}
if(!empty($oGet->iCalendario)) {
  $aWhere[] = " ed52_i_codigo = {$oGet->iCalendario} ";
}
if(!empty($oGet->iTurma)) {
  $aWhere[] = " ed57_i_codigo = {$oGet->iTurma} ";
}

$oPdf = new scpdf();
 
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);

/**
 * Instancia um stdClass para armazenar todos os dados do relat�rio
 */
$oPdf->oDadosRelatorio                    = new stdClass();
$oPdf->oCalculosRelatorio                 = new stdClass();
/**
 * Recebe o n�mero de avalia��es, e incrementa 1 para imprimir o campo M�DIA
 */
$oPdf->oDadosRelatorio->iAvaliacoes       = $oGet->iAvaliacoes + 1;
/**
 * Recebe o n�mero de avalia��es para c�lculo das avalia��es a serem impressas no rodap�
 */
$oPdf->oDadosRelatorio->iAvaliacoesRodape = $oGet->iAvaliacoes;

$oPdf->oDadosRelatorio->iAlturaLinha = 4;
$iAlturaLinha = $oPdf->oDadosRelatorio->iAlturaLinha;

$aDisciplinas = explode(",", $oGet->aDisciplinas);

/**
 * Busca os dados dos alunos da turma 
 */
$oDaoAlunos                     = db_utils::getDao("matricula");
$sWhereAlunos                   = " ed57_i_codigo = {$oGet->iTurma} ";
$sOrderAlunos                   = " ed60_i_numaluno, ed47_v_nome ";
$sSqlAlunos                     = $oDaoAlunos->sql_query_alunomatriculado(null, "*", $sOrderAlunos, $sWhereAlunos);
$rsAlunos                       = $oDaoAlunos->sql_record($sSqlAlunos);
$iTotalLinhas                   = $oDaoAlunos->numrows;
$oPdf->oDadosRelatorio->aAlunos = array();
if($iTotalLinhas > 0) {
  
  for($iIndiceAlunos = 0; $iIndiceAlunos < $iTotalLinhas; $iIndiceAlunos++) {
    
    $oRetornoAlunos                   = db_utils::fieldsMemory($rsAlunos, $iIndiceAlunos);
    
    if ($trocaTurma == 1 && $oRetornoAlunos->ed60_c_situacao == "TROCA DE TURMA") {
      continue;
    }
      
    $oDadosAlunos                     = new stdClass();
    $oDadosAlunos->numero_matricula   = $oRetornoAlunos->ed60_matricula;
    $oDadosAlunos->ordem              = $oRetornoAlunos->ed60_i_numaluno;
    $oDadosAlunos->nome_aluno         = $oRetornoAlunos->ed47_v_nome;
    $oPdf->oDadosRelatorio->aAlunos[] = $oDadosAlunos;
    unset($oDadosAlunos);
  }
}

/**
 * Busca os dados da escola para preenchimento do cabe�alho
 */
$oDaoEscola                      = db_utils::getDao("escola");
$sSqlEscola                      = $oDaoEscola->sql_query(null, "*", null, "ed18_i_codigo = {$oGet->iEscola}");
$rsEscola                        = $oDaoEscola->sql_record($sSqlEscola);
$oPdf->oDadosRelatorio->aEscolas = array();
if($oDaoEscola->numrows > 0) {
  
  $oRetornoEscola                    = db_utils::fieldsMemory($rsEscola, 0);
  $oDadosEscola                      = new stdClass();
  $oDadosEscola->nome                = $oRetornoEscola->ed18_c_nome;
  $oDadosEscola->endereco            = $oRetornoEscola->j14_nome;
  $oDadosEscola->numero              = $oRetornoEscola->ed18_i_numero;
  $oDadosEscola->complemento         = $oRetornoEscola->ed18_c_compl;
  $oDadosEscola->bairro              = $oRetornoEscola->j13_descr;
  $oDadosEscola->municipio           = $oRetornoEscola->ed261_c_nome;
  $oDadosEscola->uf                  = $oRetornoEscola->ed260_c_sigla;
  $oDadosEscola->estado              = $oRetornoEscola->ed260_c_nome;
  $oDadosEscola->depto               = $oRetornoEscola->nomeinst;
  $oPdf->oDadosRelatorio->aEscolas[] = $oDadosEscola;
  unset($oDadosEscola);
}

/**
 * Busca os dados da turma
 */
$oDaoTurma     = db_utils::getDao("regencia");
$sCamposTurma  = " calendario.ed52_i_ano, turma.ed57_c_descr, periodoavaliacao.ed09_c_descr ";
$sCamposTurma .= " , turno.ed15_c_nome, disciplina.ed12_i_codigo, caddisciplina.ed232_c_descr ";
$oPdf->oDadosRelatorio->aDadosDisciplinas = array();
$oPdf->oDadosRelatorio->aDadosTurma       = array();

for($iIndiceTurma = 0; $iIndiceTurma < count($aDisciplinas); $iIndiceTurma++) {
  
  $sWhereTurma   = "ed57_i_codigo = {$oGet->iTurma} AND disciplina.ed12_i_caddisciplina = {$aDisciplinas[$iIndiceTurma]}";
  $sWhereTurma  .= " AND periodoavaliacao.ed09_i_codigo = {$oGet->iPeriodo}";
  $sSqlTurma     = $oDaoTurma->sql_query_avaliacao(null, "*", null, $sWhereTurma);
  $rsTurma       = $oDaoTurma->sql_record($sSqlTurma);
  if($oDaoTurma->numrows > 0) {
    
    $oRetornoTurma        = db_utils::fieldsMemory($rsTurma, 0);
    $oDadosTurma          = new stdClass();
    $oDadosTurma->ano     = $oRetornoTurma->ed52_i_ano;
    $oDadosTurma->turma   = $oRetornoTurma->ed57_c_descr;
    $oDadosTurma->periodo = $oRetornoTurma->ed09_c_descr;
    $oDadosTurma->turno   = $oRetornoTurma->ed15_c_nome;
    
    $oPdf->oDadosRelatorio->aDadosDisciplinas[] = $oRetornoTurma->ed232_c_descr;
  }
}
$oPdf->oDadosRelatorio->aDadosTurma[] = $oDadosTurma;
unset($oDadosTurma);

/**
 * Total de registros poss�veis por p�gina
 */
$oPdf->oCalculosRelatorio->iQuantidadeRegistrosPorPagina = 48;

/**
 * Calcula se ser� necess�rio criar uma nova p�gina, caso exceda o limite de registros por p�gina
 */
$oPdf->oCalculosRelatorio->iPaginasAdicionais            = floor(count($oPdf->oDadosRelatorio->aAlunos)/
                                                                       $oPdf->oCalculosRelatorio->iQuantidadeRegistrosPorPagina);

/**
 * Guarda a quantidade de registros total
 */
$oPdf->oCalculosRelatorio->iQuantidadeRegistros          = $oPdf->oCalculosRelatorio->iQuantidadeRegistrosPorPagina +
                                                          ($oPdf->oCalculosRelatorio->iQuantidadeRegistrosPorPagina * 
                                                           $oPdf->oCalculosRelatorio->iPaginasAdicionais);

/**
 * Largura da coluna "NOME DO ALUNO"
 */
$oPdf->oCalculosRelatorio->iLarguraColunaAluno           = 102;

/**
 * Largura de cada coluna de avalia��o
 */
$oPdf->oCalculosRelatorio->iLarguraColunaAvaliacao       = 10;

/**
 * Calcula o valor a ser multiplicado, de acordo com o n�mero de avalia��es desejadas
 */
$oPdf->oCalculosRelatorio->iMultiplicadorTamanhoColuna   = 6 - $oPdf->oDadosRelatorio->iAvaliacoes;

/**
 * Define a nova largura da coluna "NOME DO ALUNO"
 */
$oPdf->oCalculosRelatorio->iLarguraColunaAluno          += $oPdf->oCalculosRelatorio->iLarguraColunaAvaliacao * 
                                                           $oPdf->oCalculosRelatorio->iMultiplicadorTamanhoColuna;

/**
 * Define a largura da coluna "AVALIA��ES"
 */
$oPdf->oCalculosRelatorio->iLarguraColunaAvaliacoes      = $oPdf->oCalculosRelatorio->iLarguraColunaAvaliacao * 
                                                           $oPdf->oDadosRelatorio->iAvaliacoes;

/**
 * Quantidade de caracteres a mais, para cada avalia��o a menos que foi selecionada
 */
$oPdf->oCalculosRelatorio->iQuantidadeCaracteresPorAvaliacao = 4;
$oPdf->oCalculosRelatorio->iQuantidadeCaracteresPorAvaliacao = $oPdf->oCalculosRelatorio->iQuantidadeCaracteresPorAvaliacao * 
                                                               $oPdf->oDadosRelatorio->iAvaliacoesRodape;

/**
 * Quantidade m�nima de caracteres para coluna "NOME DO ALUNO"
 */
$oPdf->oCalculosRelatorio->iQuantidadeMaximaCaracteres   = 76;

/**
 * Total de caracteres permitidos para coluna "NOME DO ALUNO"
 */
$oPdf->oCalculosRelatorio->iTotalCaracteresPermitidos    = $oPdf->oCalculosRelatorio->iQuantidadeMaximaCaracteres -
                                                           $oPdf->oCalculosRelatorio->iQuantidadeCaracteresPorAvaliacao - 5;

/**
 * Inicia a montagem do PDF
 * Para cada disciplina selecionada, � gerada uma nova p�gina
 */
for($iIndiceDisciplinas = 0; $iIndiceDisciplinas < count($oPdf->oDadosRelatorio->aDadosDisciplinas); $iIndiceDisciplinas++) {
  

  headerRelatorio($oPdf, $iIndiceDisciplinas);
  
  $iContadorRegistros = 0;
  /**
   * Imprime os alunos da turma
   */
  for($iIndiceAvaliacao = 0; $iIndiceAvaliacao < $oPdf->oCalculosRelatorio->iQuantidadeRegistrosPorPagina; $iIndiceAvaliacao++) {
  
    $iNumeroMatricula = '';
    $iOrdemChamada    = '';
    $sNomeAluno       = '';
    
    /**
     * Se a quantidade de registros (fixada em 48) for igual ao contador de registros,
     * uma nova p�gina � gerada para cada disciplina
     */
    if ($oPdf->oCalculosRelatorio->iQuantidadeRegistrosPorPagina == $iContadorRegistros) {
      
      headerRelatorio($oPdf, $iIndiceDisciplinas);
      Footer($oPdf);
    }

    /**
     * Guarda os valores do aluno no relat�rio, caso o �ndice do array esteja setado
     */
    if(isset($oPdf->oDadosRelatorio->aAlunos[$iIndiceAvaliacao])) {
      
      $oDadosAvaliacoes = $oPdf->oDadosRelatorio->aAlunos[$iIndiceAvaliacao];
      $iNumeroMatricula = $oDadosAvaliacoes->numero_matricula;
      $iOrdemChamada    = $oDadosAvaliacoes->ordem;
      $sNomeAluno       = $oDadosAvaliacoes->nome_aluno;
      if(strlen($oDadosAvaliacoes->nome_aluno) > $oPdf->oCalculosRelatorio->iTotalCaracteresPermitidos) {
        $sNomeAluno = substr($oDadosAvaliacoes->nome_aluno, 0, $oPdf->oCalculosRelatorio->iTotalCaracteresPermitidos);
      }
    }
    
    $oPdf->setfont('times', '', 8);
    $oPdf->Cell(16,                                             $iAlturaLinha,  "$iNumeroMatricula", 1, 0, "R", 0);
    $oPdf->Cell(14,                                             $iAlturaLinha,  "$iOrdemChamada",    1, 0, "C", 0);
    $oPdf->setfont('times', '', 6);
    $oPdf->Cell($oPdf->oCalculosRelatorio->iLarguraColunaAluno, $iAlturaLinha,  "$sNomeAluno",       1, 0, "L", 0);
    
    /**
     * Monta a coluna das avalia��es
     */
    for ($iIndiceColuna = 1; $iIndiceColuna <= $oPdf->oDadosRelatorio->iAvaliacoes; $iIndiceColuna++) {
    
      $iQuebraLinha = 0;
    
      if ($iIndiceColuna == $oPdf->oDadosRelatorio->iAvaliacoes) {
        $iQuebraLinha = 1;
      }
      $oPdf->Cell($oPdf->oCalculosRelatorio->iLarguraColunaAvaliacao, $iAlturaLinha,  "", 1, $iQuebraLinha, "C", 0);
    }
    $iContadorRegistros++;
  }
  
  Footer($oPdf);
}

/**
 * Fun��o que monta o rodap� do relat�rio 
 */
function Footer($oPdf) {
  
  $oPdf->SetLeftMargin(10);

  $oPdf->Cell(170,  $oPdf->oDadosRelatorio->iAlturaLinha,  "", 0, 1, "L", 0);
  $oPdf->SetFont('times', 'b', 8);
  $oPdf->Cell(170, $oPdf->oDadosRelatorio->iAlturaLinha, "DISCRIMINA��O DAS AVALIA��ES", 0, 1, "L", 0);
  
  for ($iTotalAvaliacoes = 1; $iTotalAvaliacoes <= $oPdf->oDadosRelatorio->iAvaliacoesRodape; $iTotalAvaliacoes++) {
  
    $oPdf->Cell(22,  $oPdf->oDadosRelatorio->iAlturaLinha,  "AVALIA��O {$iTotalAvaliacoes}", 1, 0, "L", 0);
    $oPdf->Cell(170, $oPdf->oDadosRelatorio->iAlturaLinha,  "",                              1, 1, "L", 0);
  }
  
  $oPdf->SetFont('times','b', 8);
  $oPdf->text(40, 285, 'ASSINATURA DO PROFESSOR: __________________________________________________________________');
}

/**
 * Gera o cabe�alho do relat�rio 
 */
function headerRelatorio($oPdf, $iContador) {

  $oPdf->setfont('times', 'b', 11);
  $oPdf->SetTopMargin(7);
  $oPdf->setfillcolor(235);
  
  $oPdf->AddPage();
  
  /**
   * Imprime os dados da escola
   */
  foreach ($oPdf->oDadosRelatorio->aEscolas as $oEscolas) {
  
    /**
     * Verifica se h� complemento, para impress�o da "/", separando endere�o/complemento
     */
    if($oEscolas->complemento == '') {
      $sEnderecoCompleto  = "{$oEscolas->endereco}, {$oEscolas->numero}";
    } else {
      $sEnderecoCompleto  = "{$oEscolas->endereco}, {$oEscolas->numero}/{$oEscolas->complemento}";
    }
    $sEnderecoCompleto .= " - {$oEscolas->bairro} - {$oEscolas->municipio}/{$oEscolas->uf}";
  
    /**
     * Imprime o cabe�alho com os dados da escola
     */
    $oPdf->Cell(200, $oPdf->oDadosRelatorio->iAlturaLinha, "{$oEscolas->nome}",    0, 1, "C", 0);
    $oPdf->Cell(200, $oPdf->oDadosRelatorio->iAlturaLinha, "{$sEnderecoCompleto}", 0, 1, "C", 0);
    $oPdf->Cell(200, $oPdf->oDadosRelatorio->iAlturaLinha, "{$oEscolas->estado}",  0, 1, "C", 0);
    $oPdf->Cell(200, $oPdf->oDadosRelatorio->iAlturaLinha, "{$oEscolas->depto}",   0, 1, "C", 0);
    $sSecretaria = "SECRETARIA MUNICIPAL DE EDUCA��O E CULTURA";
    $oPdf->Cell(200, $oPdf->oDadosRelatorio->iAlturaLinha, $sSecretaria,           0, 1, "C", 0);
  }
  $oPdf->ln();
  $oPdf->setfont('times', 'b', 12);
  $oPdf->setfillcolor(235);
  $oPdf->Cell(200, $oPdf->oDadosRelatorio->iAlturaLinha, "REGISTRO DE AVALIA��ES POR PER�ODO", 0, 1, "C", 0);
  $oPdf->ln();
  
  /**
   * Imprime os dados da turma
   */
  foreach ($oPdf->oDadosRelatorio->aDadosTurma as $oDados) {
  
    $sDisciplina = $oPdf->oDadosRelatorio->aDadosDisciplinas[$iContador];

    /**
     * Imprime o cabe�alho com os dados da turma
     */
    $oPdf->setfont('times', 'b', 8);
    $oPdf->setfillcolor(235);
    $oPdf->Cell(40,  $oPdf->oDadosRelatorio->iAlturaLinha,  "Ano Letivo: {$oDados->ano}",        0, 0, "L", 0);
    $oPdf->Cell(40,  $oPdf->oDadosRelatorio->iAlturaLinha,  "Turma: {$oDados->turma}",           0, 0, "L", 0);
    $oPdf->Cell(80,  $oPdf->oDadosRelatorio->iAlturaLinha,  "Per�odo: {$oDados->periodo}",       0, 0, "L", 0);
    $oPdf->Cell(40,  $oPdf->oDadosRelatorio->iAlturaLinha,  "Turno: {$oDados->turno}",           0, 1, "L", 0);
    $oPdf->Cell(100, $oPdf->oDadosRelatorio->iAlturaLinha,  "Disciplina (�rea): {$sDisciplina}", 0, 0, "L", 0);
    $oPdf->Cell(100, $oPdf->oDadosRelatorio->iAlturaLinha,  "Professor: ",                       0, 1, "L", 0);
  }
  
  /**
   * Imprime os t�tulos das colunas
   */
  $oPdf->setfont('times', 'b', 8);
  $oPdf->Cell(16, $oPdf->oDadosRelatorio->iAlturaLinha*2,  "N� MATR.",  1, 0, "L", 0);
  $oPdf->Cell(14, $oPdf->oDadosRelatorio->iAlturaLinha*2,  "ORDEM",     1, 0, "L", 0);
  $oPdf->Cell($oPdf->oCalculosRelatorio->iLarguraColunaAluno, 
              $oPdf->oDadosRelatorio->iAlturaLinha*2,  "NOME DO ALUNO", 1, 0, "L", 0);
  
  $oPdf->Cell($oPdf->oCalculosRelatorio->iLarguraColunaAvaliacoes, 
              $oPdf->oDadosRelatorio->iAlturaLinha,    "AVALIA��ES",    1, 1, "C", 0);
  
  /**
   * Define a margem das colunas de avalia��o
   */
  $iMargemAvaliacoes = $oPdf->getX() + 30 + $oPdf->oCalculosRelatorio->iLarguraColunaAluno;
  $oPdf->setX($iMargemAvaliacoes);
  
  /**
    * Monta a coluna dos t�tulos das avalia��es, de acordo com a quantidade solicitada
    */
  for ($iIndiceColuna = 1; $iIndiceColuna <= $oPdf->oDadosRelatorio->iAvaliacoes; $iIndiceColuna++) {
  
    $iQuebraLinha = 0;
    $sAval        = "AVAL. {$iIndiceColuna}";
  
    if ($iIndiceColuna == $oPdf->oDadosRelatorio->iAvaliacoes) {
      
      $iQuebraLinha = 1;
      $sAval        = "M�DIA";
    }
    $oPdf->setfont('times', 'b', 7);
    $oPdf->Cell($oPdf->oCalculosRelatorio->iLarguraColunaAvaliacao, 
                $oPdf->oDadosRelatorio->iAlturaLinha,  
                $sAval, 1, $iQuebraLinha, "C", 0);
  }
}

$oPdf->Output();