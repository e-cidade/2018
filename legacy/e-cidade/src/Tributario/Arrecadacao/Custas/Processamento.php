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

namespace ECidade\Tributario\Arrecadacao\Custas;

use BusinessException;
use DBException;
use \ECidade\Tributario\Juridico\InicialPartilha\Repository\InicialPartilhaCustas as InicialPartilhaCustasRepository;
use Exception;
use stdClass;
use \Recibo;

/**
 * Classe responsavel por controlar o processamento de Custas
 * @package ECidade\Tributario\Arrecadacao\Custas
 * @author  Davi Busanello <davi@dbseller.com.br>
 */
class Processamento
{
    /**
     * @var Custas[]
     */
    private $aCustas;

    /**
     * @var stdClass
     */
    private $oDebitosFormulario;

    /**
     * @var array
     */
    private $aNumpresFormulario;

    /**
     * @var Recibo
     */
    private $oRecibo;

    /**
     * @var stdClass
     */
    private $oRegraEmissao;

    /**
     * @var int
     */
    private $iTipoDebitos;

    /**
     * Numnov
     * @var int
     */
    private $codigoRecibo;

    /**
     * Processamento constructor.
     * @param stdClass $oDebitosFormulario
     * @param int $iTipo
     */
    public function __construct(stdClass $oDebitosFormulario, $iTipo)
    {
        $this->oDebitosFormulario = $oDebitosFormulario;
        $this->aNumpresFormulario = $oDebitosFormulario->aValidaNumpre;
        $this->iTipoDebitos       = $iTipo;
    }


    /**
     * @return bool|null
     * @throws BusinessException
     * @throws Exception
     */
    public function validaUsoDeCustas()
    {
        try {

            $lUsaRegraCustas = null;
            $aIniciais = $this->oDebitosFormulario->aIniciais;
            if (!isset($aIniciais) || empty($aIniciais)) {
                return FALSE;
            }

            foreach ($aIniciais as $iIniciai) {

                $oCustas = new Custas($iIniciai, $this->iTipoDebitos);

                $lUsaRegraCustas = $oCustas->usaRegraEmissao();
//            $aProcessosForo = $oCustas->getProcessosForo();

                $this->aCustas[] = $oCustas;

//            if (count($aProcessosForo) > 1) {
//                throw new BusinessException("Existe mais de um processo do foro encontrado para os débitos selecionados");
//            }
            }

            return $lUsaRegraCustas;
        } catch (DBException $oErro) {
            throw new Exception($oErro->getMessage());
        }
    }

    /**
     * @param Recibo $oRecibo
     */
    public function setRecibo(Recibo $oRecibo)
    {
        $this->oRecibo = $oRecibo;
    }

    /**
     * @param stdClass $oRegraEmissao
     */
    public function setRegraEmissao($oRegraEmissao)
    {
        $this->oRegraEmissao = $oRegraEmissao;
    }

    /**
     * @return bool
     * @throws BusinessException
     * @throws DBException
     * @throws Exception
     */
    public function processar()
    {
        if (empty($this->oRegraEmissao)) {
            throw new BusinessException("Não foi definida a regra de emissão.");
        }

        /**
         *  Se os tipos de debitos de Custas nao estao configurados na regra
         *  de emissao parra o processamento e continua uma emissao de recibos
         */
        if (!in_array($this->oRegraEmissao->k03_tipo, Custas::getTiposDebitosIniciais()) || $this->oRegraEmissao->ar11_cadtipoconvenio != 7) {
            return FALSE;
        }

        if (empty($this->oRecibo)) {
            throw new BusinessException("Não foi definido o recibo que recebera as custas.");
        }

        foreach ($this->aCustas as $iCustas => $oCustas) {

            try {
                $this->oRecibo = $oCustas->processar($this->oRecibo);
                $this->aCustas[$iCustas] = $oCustas;

            } catch (BusinessException $oErro) {
                unset($this->aCustas[$iCustas]);
                continue;
            } catch (DBException $oErro) {
                throw new DBException("Inicial {$oCustas->getInicial()} - " . $oErro->getMessage());
            } catch (Exception $oErro) {
                throw new Exception("Inicial {$oCustas->getInicial()} - " . $oErro->getMessage());
            }
        }

        if (count($this->aCustas) == 0) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * @return Recibo
     */
    public function getRecibo()
    {
        return $this->oRecibo;
    }

    /**
     * Obtem o valor total de custas a serem cobradas
     * @return float
     */
    public function getValorTotalCustas()
    {
        $nValorCustas = 0;
        foreach ($this->aCustas as $oCustas) {

            $oPartilha = $oCustas->getInicialPartilha();
            $CustasCollection = $oPartilha->getCustas();

            foreach ($CustasCollection as $Custas) {

                if ($Custas->isDispensaLancamentoRecibo()) {
                    continue;
                }
                $nValorCustas += $Custas->getValor();
            }
        }

        return $nValorCustas;
    }

    /**
     * @return mixed
     */
    public function getCodigoRecibo()
    {
        return $this->codigoRecibo;
    }

    /**
     * @param mixed $codigoRecibo
     */
    public function setCodigoRecibo($codigoRecibo)
    {
        $this->codigoRecibo = $codigoRecibo;
    }

    /**
     * Atualiza os numnov das partilhas
     * @param $codigoRecibo
     */
    public function atualizarCodigoDoReciboNasCustas($codigoRecibo)
    {
        $custaRepository = InicialPartilhaCustasRepository::getInstance();
        foreach ($this->aCustas as $oCustas) {

            $custasDaPartilha = $oCustas->getInicialPartilha()->getCustas();
            foreach ($custasDaPartilha as $custaPartilha) {
                $custaPartilha->setNumnov($codigoRecibo);
                $custaRepository->persist($custaPartilha);
            }
        }
    }

}
