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

use ECidade\Tributario\Juridico\InicialPartilha\InicialPartilha as InicialPartilhaEntity;
use ECidade\Tributario\Juridico\InicialPartilha\Repository\InicialPartilhaCustas as CustasRepository;
use cl_inicialpartilha;
use DBException;
use DateTime;

/**
 * Class InicialPartilha
 * @package ECidade\Tributario\Juridico\InicialPartilha\Repository
 * @author  Davi Busanello <davi@dbseller.com.br>
 */
class InicialPartilha extends \BaseClassRepository
{
    /**
     * @var InicialPartilhaEntity
     */
    protected static $oInstance;

    /**
     * @param InicialPartilhaEntity $oInicialPartilha
     * @return bool
     * @throws DBException
     */
    public function persist(InicialPartilhaEntity $oInicialPartilha)
    {
        $oDaoInicialPartilha = new cl_inicialpartilha();
        $iSequencial = $oInicialPartilha->getCodigo();

        $oDaoInicialPartilha->v35_inicial = $oInicialPartilha->getCodigoInicial();
        $oDaoInicialPartilha->v35_tipolancamento = $oInicialPartilha->getTipoLancamento();
        $oDaoInicialPartilha->v35_valorpartilha = $oInicialPartilha->getValorPartilha();

        $oDaoInicialPartilha->v35_obs = null;
        if ($oInicialPartilha->getObservacao()) {
            $oDaoInicialPartilha->v35_obs = $oInicialPartilha->getObservacao();
        }

        $oDaoInicialPartilha->v35_dtpagamento = null;
        if ($oInicialPartilha->getDataPagamento()) {
            $oDaoInicialPartilha->v35_dtpagamento = $oInicialPartilha->getDataPagamento()->format('Y-m-d');
        }

        $oDaoInicialPartilha->v35_datapartilha = null;
        if ($oInicialPartilha->getDataPartilha()) {
            $oDaoInicialPartilha->v35_datapartilha = $oInicialPartilha->getDataPartilha()->format('Y-m-d');
        }

        if (!empty($iSequencial)) {
            $oDaoInicialPartilha->v35_sequencial = $iSequencial;
            $lResult = $oDaoInicialPartilha->alterar($iSequencial);
        } else {
            $lResult = $oDaoInicialPartilha->incluir(null);
            $oInicialPartilha->setCodigo($oDaoInicialPartilha->v35_sequencial);
        }

        if (!$lResult) {
            $sMensagem = 'Ocorreu um erro ao ';
            $sMensagem .= (empty($iSequencial) ? 'incluir' : 'alterar');
            $sMensagem .= ' a partilha da inicial. ' . $oDaoInicialPartilha->erro_msg;
            throw new DBException($sMensagem);
        }

        $oInicialPartilha->setCodigo($oDaoInicialPartilha->v35_sequencial);
        if (count($oInicialPartilha->getCustas()) > 0) {

            foreach ($oInicialPartilha->getCustas() as $oCustas) {

                $oCustas->setInicialPartilha($oInicialPartilha);

                $oCustasRepository = CustasRepository::getInstance();
                $oCustasRepository->persist($oCustas);
            }
        }

        return TRUE;
    }


    /**
     * @param \stdClass $oDados
     * @return InicialPartilhaEntity|null
     */
    protected function make($oDados)
    {
        if (empty($oDados)) {
            return NULL;
        }

        $oInicialPartilha = new InicialPartilhaEntity();
        $oInicialPartilha->setCodigo($oDados->v35_sequencial);
        $oInicialPartilha->setCodigoInicial($oDados->v35_inicial);
        $oInicialPartilha->setTipoLancamento($oDados->v35_tipolancamento);
        $oInicialPartilha->setDataPagamento(new DateTime($oDados->v35_dtpagamento));
        $oInicialPartilha->setObservacao($oDados->v35_obs);
        $oInicialPartilha->setValorPartilha($oDados->v35_valorpartilha);
        $oInicialPartilha->setDataPartilha(new DateTime($oDados->v35_datapartilha));

        $oCustasRepository = CustasRepository::getInstance();
        $aCustas = $oCustasRepository->getByInicialPartilha($oDados->v35_sequencial);

        if (count($aCustas) > 0) {
            foreach ($aCustas as $oCustas) {
                $oInicialPartilha->addCustas($oCustas);
            }
        }

        return $oInicialPartilha;
    }


    /**
     * @param $rsResult
     * @return InicialPartilhaEntity[]
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
     * @param int $iCodigo
     * @return InicialPartilhaEntity
     * @throws DBException
     */
    public function getByCodigo($iCodigo)
    {
        $oDao = new cl_inicialpartilha();
        $oDados = $oDao->findBydId($iCodigo);

        if (empty($oDados)) {
            throw new DBException("Houve uma falha ao buscar a Partilha com o código {$iCodigo}.");
        }

        return $this->make($oDados);
    }

    /**
     * @param $iCodigoInicial
     * @return InicialPartilhaEntity|null
     * @throws DBException
     */
    public function getUltimaByInicial($iCodigoInicial)
    {
        $sSql  = "SELECT * FROM inicialpartilha WHERE v35_inicial = {$iCodigoInicial} ";
        $sSql .= "ORDER BY v35_sequencial DESC LIMIT 1";
        $rsResult = db_query($sSql);

        if (!$rsResult) {
            throw new DBException("Ocorreu um erro ao buscar as Partilhas da Inicial: {$iCodigoInicial}.");
        }

        if (pg_num_rows($rsResult) == 0) {
            return NULL;
        }
        return $this->make(pg_fetch_object($rsResult, 0));
    }

    /**
     * Apaga a partilha
     * @param InicialPartilhaEntity $oPartilha
     * @return bool
     * @throws DBException
     */
    public function delete(InicialPartilhaEntity $oPartilha)
    {

        $oDao = new cl_inicialpartilha();
        $lResult = $oDao->excluir($oPartilha->getCodigo());

        if (!$lResult) {
            throw new DBException("Erro ao apagar a partilha {$oPartilha->getCodigo()}.");
        }

        return TRUE;
    }
}
