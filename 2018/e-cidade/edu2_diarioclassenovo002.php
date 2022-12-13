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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification( "fpdf151/pdf.php" ));

$oGet                = db_utils::postmemory( $_GET );
$oTurma              = TurmaRepository::getTurmaByCodigo( $oGet->iTurma );
$oEtapa              = EtapaRepository::getEtapaByCodigo( $oGet->iEtapa );
$oAvaliacaoPeriodica = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo( $oGet->iPeriodo );

try {

  /**
   * Verifica o modelo selecionado, e seta as propriedades específicas de cada um
   */
  switch ( $oGet->iModelo ) {

    /**
     * Modelo 1 - Uma disciplina por página (Área)
     */
    case '1':

      $oRelatorio = new RelatorioDiarioClasseDisciplina( $oTurma, $oEtapa, $oAvaliacaoPeriodica );
      $oRelatorio->setExibirAvaliacao( $oGet->avaliacoes == "true" );
      $oRelatorio->setExibirFaltas( $oGet->totalFaltas == "true");
      $oRelatorio->setExibirDataPeriodo( $oGet->dataPeriodo == "true");
      break;

    /**
     * Modelo 2 - Todas disciplinas em uma página (Currículo)
     */
    case '2':

      $oRelatorio = new RelatorioDiarioClasseGlobalizada( $oTurma, $oEtapa, $oAvaliacaoPeriodica );
      break;

    /**
     * Modelo 3 - Duas páginas por disciplina (Página 1 - Presenças / Página 2 - Avaliações)
     */
    case '3':

      $oRelatorio = new RelatorioDiarioClasseCompleto( $oTurma, $oEtapa, $oAvaliacaoPeriodica );
      $oRelatorio->setExibirTotalFaltas( $oGet->totalFaltas == "true" );
      $oRelatorio->setExibirSexo( $oGet->sexo == "true" );
      $oRelatorio->setExibirIdadeSegundaPagina( $oGet->idade == "true" );
      $oRelatorio->setExibirFaltasAbonadas( $oGet->faltasAbonadas == "true" );
      $oRelatorio->setExibirCodigo( $oGet->codigo == "true" );
      $oRelatorio->setExibirNascimento( $oGet->nascimento == "true" );
      $oRelatorio->setExibirResultadoAnterior( $oGet->resultadoAnterior == "true" );
      $oRelatorio->setExibirParecer( $oGet->parecer == "true" );
      break;

    /**
     * Modelo 4 - Turma EJA
     */
    case '4':

      $oRelatorio = new RelatorioDiarioClasseEja( $oTurma, $oEtapa, $oAvaliacaoPeriodica );
      $oRelatorio->setExibirAvaliacao( $oGet->avaliacoes == "true" );
      $oRelatorio->setExibirFaltas( $oGet->totalFaltas == "true");
      break;
  }

  /**
   * Propriedades padrão independente do modelo selecionado
   */
  $oRelatorio->setRegistroManual( $oGet->sRegistro == 'M' );
  $oRelatorio->setExibirPontos( $oGet->sExibirPontos == 'S' );
  $oRelatorio->setInformarDiasLetivos( $oGet->sDiasLetivos == 'S' );
  $oRelatorio->setDiasLetivos( $oGet->iQuantidadeColunas );
  $oRelatorio->setSomenteMatriculados( $oGet->sAlunosAtivos == 'S' );
  $oRelatorio->setExibirTrocaTurma( $oGet->sTrocaTurma == 'S' );

  /**
   * Adiciona as regências selecionadas ao array da classe
   */
  $aRegencias = explode( ",", $oGet->aRegencias );
  foreach ( $aRegencias as $iRegencia ) {
    $oRelatorio->adicionarRegencias( new Regencia( $iRegencia ) );
  }

  $oRelatorio->escrever();
  $oRelatorio->Output();
} catch ( Exception $oErro ) {

  $sMsg = urlencode($oErro->getMessage());
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
}