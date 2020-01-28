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

namespace ECidade\Tributario\Juridico\InicialPartilha;

use DateTime;
use \inicial as InicialModel;
use ECidade\Tributario\Juridico\InicialPartilha\InicialPartilhaCustas as InicialPartilhaCustasModel;

/**
 * Class InicialPartilha
 * @package ECidade\Tributario\Juridico\InicialPartilha
 * @author  Davi Busanello <davi@dbseller.com.br>
 */
class InicialPartilha
{
    /**
     * @var int
     */
    private $iCodigo;

    /**
     * @var InicialModel;
     */
    private $oInicial;

    /**
     * @var int
     */
    private $iTipoLancamento;

    /**
     * @var DateTime
     */
    private $oDataPagamento;

    /**
     * @var string
     */
    private $sObservacao;

    /**
     * @var float
     */
    private $nValorPartilha = 0;

    /**
     * @var DateTime
     */
    private $oDataPartilha;

    /**
     * Custas da partilha
     * @var InicialPartilhaCustasModel[]
     */
    private $aCustas = array();

    /**
     * Sequencial da Inicial
     * @var int
     */
    private $iCodigoInicial;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->iCodigo;
    }

    /**
     * @param int $iCodigo
     */
    public function setCodigo($iCodigo)
    {
        $this->iCodigo = $iCodigo;
    }

    /**
     * @return InicialModel
     */
    public function getInicial()
    {
        return $this->oInicial;
    }

    /**
     * @param InicialModel $oInicial
     */
    public function setInicial(InicialModel $oInicial)
    {
        $this->iCodigoInicial = $oInicial->getCodigoInicial();
        $this->oInicial = $oInicial;
    }

    /**
     * @return int
     */
    public function getTipoLancamento()
    {
        return $this->iTipoLancamento;
    }

    /**
     * @param int $iTipoLancamento
     */
    public function setTipoLancamento($iTipoLancamento)
    {
        $this->iTipoLancamento = $iTipoLancamento;
    }

    /**
     * @return DateTime
     */
    public function getDataPagamento()
    {
        return $this->oDataPagamento;
    }

    /**
     * @param DateTime $oDataPagamento
     */
    public function setDataPagamento(DateTime $oDataPagamento)
    {
        $this->oDataPagamento = $oDataPagamento;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->sObservacao;
    }

    /**
     * @param string $sObservacao
     */
    public function setObservacao($sObservacao)
    {
        $this->sObservacao = $sObservacao;
    }

    /**
     * @return float
     */
    public function getValorPartilha()
    {
        return $this->nValorPartilha;
    }

    /**
     * @param float $nValorPartilha
     */
    public function setValorPartilha($nValorPartilha)
    {
        $this->nValorPartilha = $nValorPartilha;
    }

    /**
     * @return DateTime
     */
    public function getDataPartilha()
    {
        return $this->oDataPartilha;
    }

    /**
     * @param DateTime $oDataPartilha
     */
    public function setDataPartilha(DateTime $oDataPartilha)
    {
        $this->oDataPartilha = $oDataPartilha;
    }

    /**
     * @return InicialPartilhaCustas[] $aCustas
     */
    public function getCustas()
    {
        if (empty($this->aCustas)) {
            return array();
        }
        return $this->aCustas;
    }

    /**
     * @param InicialPartilhaCustas $oCustas
     */
    public function addCustas(InicialPartilhaCustasModel $oCustas)
    {
        $this->aCustas[] = $oCustas;
    }

    /**
     * @return int
     */
    public function getCodigoInicial()
    {
        return $this->iCodigoInicial;
    }

    /**
     * @param int $iCodigoInicial
     */
    public function setCodigoInicial($iCodigoInicial)
    {
        $this->iCodigoInicial = $iCodigoInicial;
    }

    /**
     * Limpa as custas da partilha
     */
    public function resetCustas()
    {
        $this->aCustas = array();
    }


}
