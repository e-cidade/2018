<?php
/**
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\Repository;

use \ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto as MarcacaoPontoModel;

/**
 * Class MarcacaoPonto
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\Repository
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class MarcacaoPonto
{

    /**
     * @var bool
     */
    public $lBuscaJustificativa = false;
    /**
     * @var \cl_pontoeletronicoarquivodataregistro
     */
    private $oDao;
    /**
     * @var Jornada
     */
    private $oJornada;

    public function __construct()
    {
        $this->oDao = new \cl_pontoeletronicoarquivodataregistro();
    }

    /**
     * @param $lBuscaJustificativa
     */
    public function setBuscaJustificativa($lBuscaJustificativa)
    {
        $this->lBuscaJustificativa = $lBuscaJustificativa;
    }

    /**
     * @param Jornada $oJornada
     */
    public function setJornada(Jornada $oJornada)
    {
        $this->oJornada = $oJornada;
    }

    /**
     * @param DiaTrabalho $oDiaTrabalho
     * @return MarcacoesPontoCollection|null
     * @throws \BusinessException
     */
    public function getCollectionMarcacaoPonto(DiaTrabalho $oDiaTrabalho)
    {

        if ($this->oJornada == null) {
            throw new \BusinessException('Jornada não informada.');
        }

        $rsMarcacao = $this->getMarcacoesNaData($oDiaTrabalho);

        if (is_null($rsMarcacao)) {
            $rsMarcacao = $this->getMarcacoesEntrePeriodos($oDiaTrabalho);
        }

        if ($rsMarcacao == null) {
            return null;
        }

        /**
         * Pega a primeira data retornada nos registros do dia, validando se é igual a data do dia de trabalho
         * Esta validação é necessário, devido a casos como:
         * - Lançadas marcações para o dia 28/06/2017
         *   => São criadas as 6 marcações
         * - Pesquiso o período 27/06/2017 à 28/06/2017
         * Essa pesquisa, retornará os registros do dia 28, pois a query pesquisa 2 dias devido as jornadas que começam em
         * um dia e terminam no dia seguinte.
         * Com esta validação, essas marcações não serão vinculadas de maneira errada ao dia errado
         */
        $sDataBase = \db_utils::fieldsMemory($rsMarcacao, 0)->rh197_data;
        if ($oDiaTrabalho->getData()->getDate() != $sDataBase) {
            return null;
        }

        $this->lBuscaJustificativa = true;
        $oMarcacaoPontoRepository  = $this;

        $aMarcacoes = \db_utils::makeCollectionFromRecord(
            $rsMarcacao,
            function ($oRetorno) use ($oMarcacaoPontoRepository) {

                $aDados = array(
                  "codigo"        => $oRetorno->rh198_sequencial,
                  "data"          => $oRetorno->rh198_data,
                  "hora"          => $oRetorno->rh198_registro,
                  "manual"        => $oRetorno->rh198_registro_manual,
                  'justificativa' => null
                );

                $oMarcacaoPonto = new MarcacaoPontoModel();
                $oMarcacaoPonto->setCodigo($oRetorno->rh198_sequencial);

                if ($oMarcacaoPontoRepository->lBuscaJustificativa) {
                    $aDados['justificativa'] = $oMarcacaoPontoRepository->getJustificativasPorMarcacao($oMarcacaoPonto);
                }

                return (object)$aDados;
            }
        );

        return MarcacoesPontoCollection::getCollectionMarcacoesFromArray($aMarcacoes);
    }

    /**
     * @param DiaTrabalho $oDiaTrabalho
     * @return bool|null|resource
     * @throws \DBException
     */
    private function getMarcacoesNaData(DiaTrabalho $oDiaTrabalho)
    {

        $aCampos = array(
          'rh197_data',
          'rh198_registro',
          'rh198_sequencial',
          'rh198_registro_manual::int',
          'rh198_ordem',
          'rh198_data'
        );

        $oDaoMarcacao    = new \cl_pontoeletronicoarquivodataregistro();
        $sWhereMarcacao  = "     rh197_matricula = {$oDiaTrabalho->getServidor()->getMatricula()}";
        $sWhereMarcacao .= " AND rh197_data      = '{$oDiaTrabalho->getData()->getDate()}'";

        $sSqlMarcacao = $oDaoMarcacao->sql_query(null, implode(' , ', $aCampos), 'rh197_data, rh198_ordem', $sWhereMarcacao);
        $rsMarcacao   = db_query($sSqlMarcacao);

        if (!$rsMarcacao) {
            throw new \DBException("Erro ao buscar as marcações do servidor.");
        }

        if (pg_num_rows($rsMarcacao) == 6) {
            return $rsMarcacao;
        }

        return null;
    }

    /**
     * @param DiaTrabalho $oDiaTrabalho
     * @return bool|null|resource
     * @throws \DBException
     */
    private function getMarcacoesEntrePeriodos(DiaTrabalho $oDiaTrabalho)
    {

        $aHoras      = $this->oJornada->getHoras();
        $oDataInicio = new \DateTime("{$oDiaTrabalho->getData()->getDate()}");
        $oDataFim    = new \DateTime("{$oDiaTrabalho->getData()->getDate()}");

        if (!empty($aHoras)) {

            $oUltimaHora = end($aHoras);

            $oDataInicio = clone $aHoras[0]->oHora;
            $oDataFim    = clone $oUltimaHora->oHora;
        }

        $iHoraToleranciaAntes  = BaseHora::TOLERANCIA_BUSCA_MARCACOES_ANTES;
        $iHoraToleranciaDepois = BaseHora::TOLERANCIA_BUSCA_MARCACOES_DEPOIS;

        $oDataInicio->modify("-{$iHoraToleranciaAntes} hour");
        $oDataFim->modify("+{$iHoraToleranciaDepois} hour");

        $sWhereInicio  = "(     rh198_data = '" . $oDataInicio->format('Y-m-d') . "'";
        $sWhereInicio .= "  AND (rh198_registro = '' OR rh198_registro >= '" . $oDataInicio->format('H:i:s') . "'))";

        $sWhereFim  = "(     rh198_data = '" . $oDataFim->format('Y-m-d') . "'";
        $sWhereFim .= "  AND (rh198_registro = '' OR rh198_registro <= '" . $oDataFim->format('H:i:s') . "'))";

        $sWhereMarcacao  = "     rh197_matricula = {$oDiaTrabalho->getServidor()->getMatricula()}";
        $sWhereMarcacao .= " AND ({$sWhereInicio} OR {$sWhereFim})";
        $sWhereMarcacao .= " AND rh197_data >= '{$oDiaTrabalho->getData()->getDate()}'";

        $aCampos = array(
          'rh197_data',
          'rh198_registro',
          'rh198_sequencial',
          'rh198_registro_manual::int',
          'rh198_ordem',
          'rh198_data'
        );

        $oDao         = new \cl_pontoeletronicoarquivodataregistro();
        $sSqlMarcacao = $oDao->sql_query(null, implode(' , ', $aCampos), 'rh197_data, rh198_ordem', $sWhereMarcacao);
        $rsMarcacao   = db_query($sSqlMarcacao);

        if (!$rsMarcacao) {
            throw new \DBException("Erro ao buscar as marcações do servidor.");
        }

        if (pg_num_rows($rsMarcacao) == 0) {
            return null;
        }

        return $rsMarcacao;
    }

    /**
     * @param MarcacaoPontoModel $oMarcacaoPonto
     * @return Justificativa|null
     * @throws \DBException
     */
    public function getJustificativasPorMarcacao(MarcacaoPontoModel $oMarcacaoPonto)
    {

        $sWhereJustificativa = "rh199_pontoeletronicoarquivodataregistro = {$oMarcacaoPonto->getCodigo()}";
        $oDaoJustificativa   = new \cl_pontoeletronicoregistrojustificativa();
        $sSqlJustificativa   = $oDaoJustificativa->sql_query(null, '*', null, $sWhereJustificativa);
        $rsJustificativa     = db_query($sSqlJustificativa);

        if (!$rsJustificativa) {
            throw new \DBException('Erro ao buscar justificativa lançada para marcação.');
        }

        if (pg_num_rows($rsJustificativa) == 0) {
            return null;
        }

        return \db_utils::makeFromRecord($rsJustificativa, function ($oRetorno) {

            $oJustificativa = new Justificativa();
            $oJustificativa->setCodigo($oRetorno->rh194_sequencial);
            $oJustificativa->setDescricao($oRetorno->rh194_descricao);
            $oJustificativa->setAbreviacao($oRetorno->rh194_sigla);

            return $oJustificativa;
        });
    }
}
