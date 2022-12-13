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

db_app::import("exceptions.*");

/**
 * Classe responsavel por Retornar os trabalhos conforme o instante solicitado
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @revision $Author: dbjeferson.belmiro $
 * @version $Revision: 1.10 $
 */
class Agenda
{
    private $iInstante;

    /**
     * Periodicidade unica da agenda
     */
    const PERIODICIDADE_UNICA = 0;

    /**
     * Periodicidade diáriaunica da agenda
     */
    const PERIODICIDADE_DIARIA = 1;

    /**
     * Periodicidade semanal da agenda
     */
    const PERIODICIDADE_SEMANAL = 2;

    /**
     * Periodicidade mensal da agenda
     */
    const PERIODICIDADE_MENSAL = 3;

    /**
     * Periodicidade personalizada da agenda
     */
    const PERIODICIDADE_PERSONALIZADA = 4;

    /**
     * COnstrutor da classe
     * @param $iInstante - Momento da Atualizacao
     */
    public function __construct() {
        db_app::import("configuracao.Job");
    }

    /**
     * @param integer $iInstante
     * @return Job[]
     */
    public  function getTarefas($iInstante)
    {
        $aTrabalhos = array();

        $this->iInstante = $iInstante;

        $iDiaAgenda          = date("d", $this->iInstante);
        $iDiaSemanaAgenda    = date("w", $this->iInstante);
        $iMesAgenda          = date("m", $this->iInstante);
        $iAnoAgenda          = date("Y", $this->iInstante);
        $iHoraAgenda         = date("H", $this->iInstante);
        $iMinutoAgenda       = date("i", $this->iInstante);

        foreach ( $this->importarTarefas() as $oTrabalho ) {

            switch ( $oTrabalho->getTipoPeriodicidade() ) {

                case Agenda::PERIODICIDADE_UNICA:

                    if (strtotime(date("YmdHi")) >= strtotime(date("YmdHi", $oTrabalho->getMomentoCricao()))) {
                        $aTrabalhos[] = $oTrabalho;
                    }

                break;

                case Agenda::PERIODICIDADE_DIARIA:

                    $aExecucoes = $this->getExecucoes($oTrabalho, Agenda::PERIODICIDADE_DIARIA);
                    foreach ( $oTrabalho->getPeriodicidades() as $sHorario ) {

                        $lExisteExecucaoHoje    = isset($aExecucoes[$iAnoAgenda][$iMesAgenda][$iDiaAgenda]);
                        $lExisteExecucaoMomento = isset($aExecucoes[$iAnoAgenda][$iMesAgenda][$iDiaAgenda][$sHorario]);
                        $aExecucoesDiarias      = $lExisteExecucaoHoje
                            ? $aExecucoes[$iAnoAgenda][$iMesAgenda][$iDiaAgenda]
                            : array();

                        if ( ( !$lExisteExecucaoHoje && ((int)($iHoraAgenda.$iMinutoAgenda) >= (int)$sHorario) ) ||
                            ( $lExisteExecucaoHoje && ((int)($iHoraAgenda.$iMinutoAgenda) >= (int)$sHorario)  && !$lExisteExecucaoMomento ) )  {

                            $oTrabalho->setPeriodicidadeExecucao($sHorario);
                            $aTrabalhos[] = $oTrabalho;
                            break 2; // sai do case
                        }
                    }
                break;

                case Agenda::PERIODICIDADE_SEMANAL:

                    $aExecucoes = $this->getExecucoes($oTrabalho, Agenda::PERIODICIDADE_SEMANAL);
                    foreach ( $oTrabalho->getPeriodicidades() as $iDiaSemana ) {

                        $lExisteExecucaoDiaSemana  = isset($aExecucoes[$iAnoAgenda][$iMesAgenda][$iDiaSemanaAgenda]);
                        $lExisteExecucaoHoje       = isset($aExecucoes[$iAnoAgenda][$iMesAgenda][$iDiaSemanaAgenda][$iDiaSemana]);
                        $aExecucoesSemanais        = $lExisteExecucaoDiaSemana
                            ? array_keys($aExecucoes[$iAnoAgenda][$iMesAgenda][$iDiaSemanaAgenda])
                            : array();
                        $lSemExecucao              = true;
                        foreach ($aExecucoesSemanais as $iDiaMesExecucao ) {

                            if ( $iDiaMesExecucao <= $iDiaAgenda ) {

                                $lSemExecucao = false;
                                break;//Sai do Foreach
                            }
                        }


                        if ( ( !$lExisteExecucaoDiaSemana && ($iDiaSemanaAgenda == $iDiaSemana ) ) ||
                            ( $lExisteExecucaoDiaSemana && ($iDiaSemanaAgenda == $iDiaSemana ) && !$lExisteExecucaoHoje ) )  {
                            $oTrabalho->setPeriodicidadeExecucao($iDiaSemana);
                            $aTrabalhos[] = $oTrabalho;
                            break 2;// sai do case
                        }
                    }
                break;

                case Agenda::PERIODICIDADE_MENSAL:

                    $aExecucoes = $this->getExecucoes($oTrabalho, Agenda::PERIODICIDADE_MENSAL);

                    foreach ( $oTrabalho->getPeriodicidades() as $iDiaMes ) {

                        $lExisteExecucaoMes     = isset($aExecucoes[$iAnoAgenda][$iMesAgenda]);
                        $lExisteExecucaoMomento = isset($aExecucoes[$iAnoAgenda][$iMesAgenda][$iDiaMes]);
                        $aExecucoesDiarias      = $lExisteExecucaoMes ? $aExecucoes[$iAnoAgenda][$iMesAgenda] : array();

                        if ( ( !$lExisteExecucaoMes && ($iDiaAgenda >= $iDiaMes) ) ||
                            (  $lExisteExecucaoMes && ($iDiaAgenda >= $iDiaMes)  && !$lExisteExecucaoMomento ) )  {

                            $oTrabalho->setPeriodicidadeExecucao($iDiaMes);
                            $aTrabalhos[] = $oTrabalho;
                            break 2;// sai do case
                        }
                    }
                break;

                //@TODO implentar
                case Agenda::PERIODICIDADE_PERSONALIZADA:
                break;
            }

        }

        return $aTrabalhos;
    }

    /**
     * Retorna as Execuções de Uma tarefa
     * @param Job $oJob
     * @param integer $iTipoPeriodicidade
     */
    public  function getExecucoes( Job $oJob, $iTipoPeriodicidade) {

        $aRetorno        = array();
        $sCaminho        = TaskManager::PATH_TAREFAS_EXECUTADAS . $oJob->getNome() . Job::SUFIXO_NOME_TAREFA;

        if ( file_exists($sCaminho) ) {

            $oXML               = new DOMDocument('1.0', 'ISO-8859-1');
            $oXML->load($sCaminho);
            $oXML->formatOutput = true;
            $oListaExecucoes    = $oXML->getElementsByTagName("Execucao");

            foreach ($oListaExecucoes as $oExecucao) {

                $oDetalheExecucao                = new stdClass();
                $oDetalheExecucao->sExecucao     = $oExecucao->getAttribute("Execucao");
                $oDetalheExecucao->iMomento      = (int)$oExecucao->getAttribute("MomentoExecucao");
                $iAnoExecucao                    = date("Y", $oDetalheExecucao->iMomento);
                $iMesExecucao                    = date("m", $oDetalheExecucao->iMomento);
                $iSemanaExecucao                 = date("w", $oDetalheExecucao->iMomento);
                $iDiaExecucao                    = date("d", $oDetalheExecucao->iMomento);
                $sExecucao                       = $oDetalheExecucao->sExecucao;

                switch ($iTipoPeriodicidade) {

                    case Agenda::PERIODICIDADE_DIARIA:
                        $aRetorno[$iAnoExecucao]
                            [$iMesExecucao]
                            [$iDiaExecucao]
                            [$sExecucao]     = $oDetalheExecucao;
                    break;
                    case Agenda::PERIODICIDADE_SEMANAL:
                        $aRetorno[$iAnoExecucao]
                            [$iMesExecucao]
                            [$iSemanaExecucao]
                            [$sExecucao]     = $oDetalheExecucao;
                    break;
                    case Agenda::PERIODICIDADE_MENSAL:
                        $aRetorno[$iAnoExecucao]
                            [$iMesExecucao]
                            [$sExecucao]     = $oDetalheExecucao;
                    break;
                }
            }
        }
        return $aRetorno;
    }

    /**
     * Retorna as Tarefas do Diretorio
     * @return Job[]
     */
    public function importarTarefas() {

        db_app::import('configuracao.TaskManager');

        $aJobs               = array();
        $sDiretorioTarefas   = TaskManager::PATH_FILA_TAREFAS;
        $sDiretorioConclusao = TaskManager::PATH_TAREFAS_EXECUTADAS;

        if ( is_dir($sDiretorioTarefas) ) {

            if ( $hDiretorio = opendir($sDiretorioTarefas) ) {

                while ( ($sArquivo = readdir($hDiretorio)) !== false ) {

                    if ( strripos( $sArquivo, '.swp') ) {
                        continue;
                    }
                    if ( strripos( $sArquivo, '~') ) {
                        continue;
                    }
                    if ( strripos( $sArquivo, '.php') ) {
                        continue;
                    }
                    if ( is_dir($sDiretorioTarefas."/".$sArquivo) ) {
                        continue;
                    }

                    $sArquivo   = str_replace(Job::SUFIXO_NOME_TAREFA, "", $sArquivo);
                    $aJobs[]    = new Job($sArquivo);
                }
                closedir($hDiretorio);
            }
        }
        return $aJobs;
    }

    public function getDescricaoPeriodicidade($iTipoPeriodicidade)
    {
        switch ($iTipoPeriodicidade) {

            case Agenda::PERIODICIDADE_UNICA:
                return 'Única';
            break;
            case Agenda::PERIODICIDADE_DIARIA:
                return 'Diária';
            break;
            case Agenda::PERIODICIDADE_SEMANAL:
                return 'Semanal';
            break;
            case Agenda::PERIODICIDADE_MENSAL:
                return 'Mensal';
            break;
            case PERIODICIDADE_PERSONALIZADA:
                return 'Personalizada';
            break;
        }
    }
}
