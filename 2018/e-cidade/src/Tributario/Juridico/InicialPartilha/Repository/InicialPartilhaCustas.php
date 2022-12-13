<?php
/*
*     E-cidade Software Publico para Gestao Municipal
*  Copyright (C) 2016  DBselller Servicos de Informatica
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

namespace ECidade\Tributario\Juridico\InicialPartilha\Repository;

use ECidade\Tributario\Juridico\InicialPartilha\InicialPartilhaCustas as InicialPartilhaCustasEntity;
use ECidade\Tributario\Arrecadacao\Repository\Taxa as TaxaRepository;
use cl_inicialpartilhacustas;
use DBException;
use \db_utils;

class InicialPartilhaCustas extends \BaseClassRepository
{

    /**
     * @var InicialPartilhaCustasEntity
     */
    protected static $oInstance;

    /**
     * @param InicialPartilhaCustasEntity $oCustas
     * @return bool
     * @throws DBException
     */
    public function persist(InicialPartilhaCustasEntity $oCustas)
    {
        $oDaoCustas = new cl_inicialpartilhacustas();
        $iSequencial = $oCustas->getCodigo();

        $oDaoCustas->v36_taxa = $oCustas->getCodigoTaxa();
        $oDaoCustas->v36_inicialpartilha = $oCustas->getCodigoInicialPartilha();
        
        $oInicialPartilha = $oCustas->getInicialPartilha();
        
        if (!empty($oInicialPartilha)) {
            $oDaoCustas->v36_inicialpartilha = $oInicialPartilha->getCodigo();
        }
        $oDaoCustas->v36_valor = $oCustas->getValor();
        $oDaoCustas->v36_numnov = $oCustas->getNumnov();
        $oDaoCustas->v36_dispensalancamentorecibo = ($oCustas->isDispensaLancamentoRecibo() ? 't' : 'f');

        if (!empty($iSequencial)) {
            $oDaoCustas->v36_sequencial = $iSequencial;
            $lResult = $oDaoCustas->alterar($iSequencial);
        } else {
            $lResult = $oDaoCustas->incluir(null);
            $oCustas->setCodigo($oDaoCustas->v36_sequencial);
        }

        if (!$lResult) {
            $sMensagem  = 'Ocorreu um erro ao ';
            $sMensagem .= (empty($iSequencial) ? 'incluir' : 'alterar');
            $sMensagem .= ' a custas da partilha. ' . $oDaoCustas->erro_msg;
            throw new DBException($sMensagem);
        }

        return TRUE;
    }

    /**
     * @param \stdClass $oDados
     * @return InicialPartilhaCustasEntity|null
     */
    protected function make( $oDados)
    {
        if (empty($oDados)) {
            return NULL;
        }

        $oCustas = new InicialPartilhaCustasEntity();
        $oCustas->setCodigo($oDados->v36_sequencial);
        $oCustas->setCodigoTaxa($oDados->v36_taxa);
        $oCustas->setCodigoInicialPartilha($oDados->v36_inicialpartilha);
        $oCustas->setValor($oDados->v36_valor);
        $oCustas->setNumnov($oDados->v36_numnov);
        $oCustas->setDispensaLancamentoRecibo($oDados->v36_dispensalancamentorecibo == 't');

        $oTaxaRepository = TaxaRepository::getInstance();
        $oTaxa = $oTaxaRepository->getByCodigo($oDados->v36_taxa);
        $oCustas->setTaxa($oTaxa);

        return $oCustas;
    }

    /**
     * Monta uma collection
     * @param $rsResult
     * @return InicialPartilhaCustasEntity[]
     */
    private function makeCollection($rsResult)
    {
        $aCollection = array();
        $aResult = pg_fetch_all($rsResult);

        if (empty($aResult)) {
            return array();
        }

        foreach ($aResult as $oResult) {
            $aCollection[] = $this->make((object) $oResult);
        }

        return $aCollection;
    }

    /**
     * Obtem todas as Custas de uma Partilha
     * @param $iCodigoPartilha
     * @return InicialPartilhaCustasEntity[]|null
     * @throws DBException
     */
    public function getByInicialPartilha($iCodigoPartilha)
    {
        $oDao = new cl_inicialpartilhacustas();
        $sWhere = "v36_inicialpartilha = {$iCodigoPartilha}";
        $sSql = $oDao->sql_query_file(null, '*', null, $sWhere);

        $rsResult = db_query($sSql);

        if (!$rsResult) {
            throw new DBException("Ocorreu um erro ao buscar as Custas da Partilha {$iCodigoPartilha}.");
        }

        return $this->makeCollection($rsResult);
    }

    /**
     * Obtem a Custas de uma Partilha
     * @param $iCodigoCusta
     * @param $iCodigoPartilha
     * @return InicialPartilhaCustasEntity[]|null
     * @throws DBException
     */
    public function getByCustaInicialPartilha($iCodigoCusta, $iCodigoPartilha)
    {
        $oDao = new cl_inicialpartilhacustas();
        $sWhere = "v36_taxa = {$iCodigoCusta} and v36_inicialpartilha = {$iCodigoPartilha}";
        $sSql = $oDao->sql_query_file(null, '*', null, $sWhere);

        $rsResult = db_query($sSql);

        if (!$rsResult) {
            throw new DBException("Ocorreu um erro ao buscar as Custas {$iCodigoCusta} da Partilha {$iCodigoPartilha}.");
        }

        return $this->make(pg_fetch_object($rsResult, 0));
    }

    /**
     * @param $iNumnov
     * @return InicialPartilhaCustasEntity[]|null
     * @throws DBException
     */
    public function getByNumnov($iNumnov)
    {
        $oDao = new cl_inicialpartilhacustas();
        $sWhere = "v36_numnov = {$iNumnov}";
        $sSql = $oDao->sql_query_file(null, '*', null, $sWhere);

        $rsResult = db_query($sSql);

        if (!$rsResult) {
            throw new DBException("Ocorreu um erro ao buscar as Custas de Partilha do Numpre {$iNumnov}.");
        }

        return $this->makeCollection($rsResult);
    }

    /**
     * @param $iNumnov
     * @return \stdClass[]
     * @throws DBException
     */
    public function getDadosRecibo($iNumnov)
    {
        $oDao = new cl_inicialpartilhacustas();
        $sSql = $oDao->sql_query_recibo_custas($iNumnov);

        $rsResult = db_query($sSql);

        if (!$rsResult) {
            throw new DBException("Erro ao buscar os dados de custas do recibo.");
        }

        return db_utils::getCollectionByRecord($rsResult);
    }

    /**
     * Apaga a Custas da partilha
     * @param InicialPartilhaCustasEntity $oCustas
     * @return bool
     * @throws DBException
     */
    public function delete(InicialPartilhaCustasEntity $oCustas)
    {

        $oDao = new cl_inicialpartilhacustas();
        $lResult = $oDao->excluir($oCustas->getCodigo());

        if (!$lResult) {
            throw new DBException("Erro ao apagar a custas da taxa {$oCustas->getCodigoTaxa()} da partilha {$oCustas->getCodigoInicialPartilha()}");
        }

        return TRUE;
    }
}
