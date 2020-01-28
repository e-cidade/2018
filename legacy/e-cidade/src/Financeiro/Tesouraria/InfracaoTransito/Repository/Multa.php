<?php

namespace ECidade\Financeiro\Tesouraria\InfracaoTransito\Repository;

use ECidade\Financeiro\Tesouraria\InfracaoTransito\Multa as MultaEntity;
use cl_arquivoinfracaomulta;
use DBException;

/**
 * Class Multa
 * Classe que representa o repository do model Multa
 * @package ECidade\Financeiro\Tesouraria\InfracaoTransito\Repository
 */
class Multa extends \BaseClassRepository
{

    /**
     * Sobrescreve o atributo da classe pai para
     * manter apenas as referências da classe atual
     * @var Multa
     */
    protected static $oInstance;

    /**
     * Persiste o objeto da classe Multa no banco de dados.
     * @param MultaEntity $oMulta
     * @return bool
     * @throws DBException
     */
    public function persist(MultaEntity $oMulta)
    {
        $oDaoMulta = new cl_arquivoinfracaomulta;
        $oDaoMulta->i08_sequencial      = $oMulta->getCodigo();
        $oDaoMulta->i08_nivel           = $oMulta->getNivel();
        $oDaoMulta->i08_vlfunset        = $oMulta->getValorFunset();
        $oDaoMulta->i08_vldetran        = $oMulta->getValorDetran();
        $oDaoMulta->i08_arquivoinfracao = $oMulta->getIdArquivoInfracao();
        $oDaoMulta->i08_vlbruto         = $oMulta->getValorBruto();
        $oDaoMulta->i08_codigoinfracao  = $oMulta->getCodigoInfracaoTransito();
        $oDaoMulta->i08_nossonumero     = $oMulta->getNossoNumero();
        $oDaoMulta->i08_autoinfracao    = $oMulta->getAutoInfracao();
        $oDaoMulta->i08_vlprefeitura    = $oMulta->getValorPrefeitura();
        $oDaoMulta->i08_duplicado       = $oMulta->isDuplicado() ? 't' : 'f';

        if ($oMulta->getDataPagamento()) {
            $oDaoMulta->i08_dtpagamento = $oMulta->getDataPagamento()->format('Y-m-d');
        }

        if ($oMulta->getDataRepasse()) {
            $oDaoMulta->i08_dtrepasse = $oMulta->getDataRepasse()->format('Y-m-d');
        }

        if ($oDaoMulta->i08_sequencial) {
            $oDaoMulta->alterar();
        } else {
            $oDaoMulta->incluir();
            $oMulta->setCodigo($oDaoMulta->i08_sequencial);
        }

        if ($oDaoMulta->erro_status == '0') {
            throw new DBException('Ocorreu um erro ao persistir a multa.');
        }

        return TRUE;
    }

    /**
     * Retorna uma instância da classe
     *
     * @param \stdClass $oDados stdClass com os oDados do registro a ser construido
     * @return MultaEntity
     */
    protected function make($oDados)
    {
        if (empty($oDados)) {
            return null;
        }

        $oMulta = new MultaEntity();
        $oMulta->setCodigo($oDados->i08_sequencial);
        $oMulta->setIdArquivoInfracao($oDados->i08_arquivoinfracao);
        $oMulta->setCodigoInfracaoTransito($oDados->i08_codigoinfracao);
        $oMulta->setDataPagamento(new \DateTime($oDados->i08_dtpagamento));
        $oMulta->setDataRepasse(new \DateTime($oDados->i08_dtrepasse));
        $oMulta->setNivel($oDados->i08_nivel);
        $oMulta->setValorFunset($oDados->i08_vlfunset);
        $oMulta->setValorDetran($oDados->i08_vldetran);
        $oMulta->setValorPrefeitura($oDados->i08_vlprefeitura);
        $oMulta->setValorBruto($oDados->i08_vlbruto);
        $oMulta->setNossoNumero($oDados->i08_nossonumero);
        $oMulta->setAutoInfracao($oDados->i08_autoinfracao);
        $oMulta->setDuplicado($oDados->i08_duplicado == 't');

        return $oMulta;
    }

    /**
     * Retorna as informações da multa importada
     * @param $iCodigo
     * @return MultaEntity
     * @throws DBException
     */
    public function getByCodigo($iCodigo)
    {
        $oDao = new \cl_arquivoinfracaomulta();
        $oDados = $oDao->findBydId($iCodigo);

        if (empty($oDados)) {
            throw new \DBException("Houve uma falha ao buscar a multa com o código {$iCodigo}.");
        }

        return $this->make($oDados);
    }

}