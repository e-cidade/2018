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

use \DateTime;
use \Exception;
use \DBException;
use \BusinessException;
use \Taxa as TaxaModel;
use \cl_processoforopartilhacusta;
use \cl_inicialnumpre;
use \Recibo;
use \ECidade\Tributario\Arrecadacao\Repository\Taxa;
use \ECidade\Tributario\Juridico\InicialPartilha\Repository\InicialPartilha as InicialPartilhaRepository;
use \ECidade\Tributario\Juridico\InicialPartilha\Repository\InicialPartilhaCustas as InicialPartilhaCustasRepository;
use \ECidade\Tributario\Juridico\InicialPartilha\InicialPartilha;
use \ECidade\Tributario\Juridico\InicialPartilha\InicialPartilhaCustas;


/**
 * Class Custas
 * @package ECidade\Tributario\Arrecadacao\Custas
 * @author  Davi Busanello <davi@dbseller.com.br>
 */
class Custas
{

    /**
     * @var int
     */
    private $iNumpre;

    /**
     * @var int
     */
    private $iArreTipo;

    /**
     * @var array
     */
    private $aProcessosForo;

    /**
     * @var int
     */
    private $iInicial;

    /**
     * @var int
     */
    private $iProcessoForo;

    /**
     * @var int[];
     */
    private $aNumpres;

    /**
     * @var InicialPartilha
     */
    private $oInicialPartilha;

    /**
     * Modelo de Recibo
     * @const int
     */
    const TIPO_MODELO_RECIBO = 19;

    /**
     * Modelo de Carne
     * @const int
     */
    const TIPO_MODELO_CARNE = 20;

    const TIPO_DEBITO = null;

    /**
     * Codigo de historico de custas
     * @const int
     */
    const CODIGO_HISTORICO = 11403;


    /**
     * Custas constructor.
     * @param int $iInicial
     * @param int $iArreTipo
     * @throws DBException
     */
    public function __construct($iInicial = null, $iArreTipo = null)
    {
        if (!empty($iInicial)) {
            $this->iInicial = $iInicial;
            $this->aNumpres = $this->getNumpresInicial();
        }
        if (!empty($iArreTipo)) {
            $this->iArreTipo = $iArreTipo;
        }

    }

    /**
     * @param Recibo $oRecibo
     * @return Recibo
     * @throws Exception
     * @throws BusinessException
     */
    public function processar(Recibo $oRecibo)
    {
        if ($this->isDebitoTemCustas() && $this->isDebitoTemProcesso()) {
            /*NOTE DEBITO COM CUSTAS JURIDICAS*/

            throw new BusinessException("Geração de custas de Processo do Foro não implementada. Processo: {$this->iProcessoForo}");

        } elseif ($this->isDebitoTemCustas()) {

            if (!$this->inInicialNumpre($oRecibo->getDebitosRecibo())) {
                throw new BusinessException("Nenhum numpre do recibo na Inicial");
            }

            /* NOTE: DEBITO COM CUSTAS ADMINISTRATIVAS */
            $this->processarCustasAdministrativas($oRecibo);
        }

        $aCustas = $this->oInicialPartilha->getCustas();
        if (!empty($aCustas)) {
            $oRecibo = $this->adicionaCustasRecibo($oRecibo);
        }
        return $oRecibo;
    }

    public function getInicialPartilha()
    {
        return $this->oInicialPartilha;
    }

    /**
     * Verifica se o debito tem inicial
     * @return bool
     * @throws DBException
     */
    private function isDebitoTemCustas()
    {
        if (!empty($this->iInicial)) {
            return TRUE;
        }
        if (!empty($this->iNumpre)) {

            $sSql = "select * from inicialnumpre where v59_numpre = {$this->iNumpre}";
            $rsResult = db_query($sSql);
            if (!$rsResult) {
                throw new DBException("Ocorreu um erro ao verificar custas do Numpre: {$this->iNumpre}");
            }

            if (pg_num_rows($rsResult) > 0) {
                $oInicialNumpre = pg_fetch_object($rsResult);
                $this->iInicial = $oInicialNumpre->v59_inicial;
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Verifica se o débito tem processo do foro
     * @return bool
     * @throws DBException
     */
    private function isDebitoTemProcesso()
    {
        if (!empty($this->iProcessoForo)) {
            return TRUE;
        }
        if (!empty($this->iInicial)) {
            $sSql = "select * from processoforoinicial where v71_inicial = {$this->iInicial}";
            $rsResult = db_query($sSql);
            if (!$rsResult) {
                throw new DBException("Ocorreu um erro ao verificar se o débito tem processo.");
            }

            if (pg_num_rows($rsResult) > 0) {
                $oProcessoForoInicial = pg_fetch_object($rsResult);
                $this->iProcessoForo = $oProcessoForoInicial->v71_processoforo;
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * FIXME verifiacr metodo
     * @return int[]
     */
    public function getProcessosForo()
    {
        if (isset($this->aProcessosForo) && !empty($this->aProcessosForo)) {
            return $this->aProcessosForo;
        }

        $oDaoProcessoForoPartilhaCusta = new cl_processoforopartilhacusta();
        $this->aProcessosForo = $oDaoProcessoForoPartilhaCusta->getProcessoForoByNumprePacelamento($this->iNumpre, $this->iArreTipo);

        return $this->aProcessosForo;
    }

    /**
     * Valida se é para utilizar a regra de emissao de recibo/carne com custas
     * @return bool
     */
    public function usaRegraEmissao()
    {
        $sExisteRegraModelos  = "select 1 from modcarnepadrao where k48_cadtipomod in (";
        $sExisteRegraModelos .= self::TIPO_MODELO_RECIBO . ", " . self::TIPO_MODELO_CARNE . ")";
        $sExisteRegraModelos .= " AND k48_datafim >= '" . date('Y-m-d', db_getsession("DB_datausu")) . "'";

        $rsExisteRegraModelos = db_query($sExisteRegraModelos);

        if (!$rsExisteRegraModelos) {
            throw new DBException("Erro ao verificar se existem regras para emissão com custas.");
        }

        $iNumRows = pg_num_rows($rsExisteRegraModelos);

        if ($iNumRows == 0) {
            return FALSE;
        } else if ($this->isDebitoTemCustas() || $this->isDebitoTemProcesso()) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Processa custas administrativas
     * @param Recibo $oRecibo
     * @throws BusinessException
     */
    private function processarCustasAdministrativas(Recibo $oRecibo)
    {
        $oInicialPartilhaRepository = InicialPartilhaRepository::getInstance();
        $oInicialPartilha = $oInicialPartilhaRepository->getUltimaByInicial($this->iInicial);
        $oTaxaRepository = Taxa::getInstance();
        $aTaxas = $oTaxaRepository->getTodasSemProcesso();

        /*
         * Removida validacao pois sera validado se e necessario obrigatoriedade
        if (empty($aTaxas)) {
            throw new BusinessException("Sem Taxas/Custas configuradas para cobrança administrativa.");
        }*/

        $fValor = $this->getValorBaseCustas($oRecibo);
        if (empty($oInicialPartilha)) {
            $oInicialPartilha = new InicialPartilha();
        }

        $this->oInicialPartilha = $this->manipulaPartilha($oInicialPartilha, $fValor, $aTaxas, $oRecibo->getNumpreRecibo());
    }

    /**
     * @param InicialPartilha $oInicialPartilha
     * @param float $fValor
     * @param TaxaModel[] $aTaxas
     * @param int $iNumnov
     * @return InicialPartilha
     * @throws DBException
     */
    private function manipulaPartilha(InicialPartilha $oInicialPartilha, $fValor, $aTaxas, $iNumnov)
    {
        $oInicialPartilhaRepository = InicialPartilhaRepository::getInstance();
        $aPartilhaCustas = $oInicialPartilha->getCustas();

        if (empty($aPartilhaCustas)) {

            $oInicialPartilha->setCodigoInicial($this->iInicial);
            $oInicialPartilha->setTipoLancamento(1);
            $oDataPartilha = new DateTime(date('Y-m-d', db_getsession("DB_datausu")));
            $oInicialPartilha->setDataPartilha($oDataPartilha);
        }

        $oInicialPartilha = $this->manipulaPartilhaCustas($oInicialPartilha, $fValor, $aTaxas, $iNumnov);

        $aPartilhaCustas = $oInicialPartilha->getCustas();

        if (empty($aPartilhaCustas) && $oInicialPartilha->getCodigo()) {
            if (!$oInicialPartilhaRepository->delete($oInicialPartilha)) {
                throw new DBException("Erro ao remover a Partilha de Custas.");
            }
            return new InicialPartilha();
        } else if (!empty($aPartilhaCustas)) {
            if (!$oInicialPartilhaRepository->persist($oInicialPartilha)) {
                throw new DBException("Erro ao criar a Partilha.");
            }
        }

        return $oInicialPartilha;
    }

    /**
     * @param InicialPartilha $oInicialPartilha
     * @param float $fValor
     * @param TaxaModel[] $aTaxas
     * @param int $iNumnov
     * @return InicialPartilha
     */
    private function manipulaPartilhaCustas(InicialPartilha $oInicialPartilha, $fValor, $aTaxas, $iNumnov)
    {
        $aPartilhaCustas = $oInicialPartilha->getCustas();
        $oInicialPartilha->resetCustas();
        $fValorTotalPartilha = 0;

        /* Se for um recibo que ja possui partilha cria um indice das taxas ja existentes */
        $aTaxasExistentes = array();
        if (!empty($aPartilhaCustas)) {
            foreach ($aPartilhaCustas as $oPartilhaCustas) {
                $aTaxasExistentes[] = $oPartilhaCustas->getCodigoTaxa();
            }
        }

        /**
         * Se não tem nenhuma taxa configurada remove todas as instancias
         * de InicialPartilhaCustas das Partilha
         */
        if (empty($aTaxas) && !empty($aPartilhaCustas)) {
            $this->removeCustasPartilha($aPartilhaCustas);
        } else if (!empty($aTaxas)) {
            /**
             * Percorre as taxas configuradas para criar as custas da partilha
             */
            foreach ($aTaxas as $oTaxa) {

                $oPartilhaCustas = new InicialPartilhaCustas();
                $oPartilhaCustas->setDispensaLancamentoRecibo(FALSE);
                $oPartilhaCustas->setCodigoTaxa($oTaxa->getCodigoTaxa());

                if (in_array($oTaxa->getCodigoTaxa(), $aTaxasExistentes)) {

                    $iKey = array_search($oTaxa->getCodigoTaxa(), $aTaxasExistentes);
                    unset($aTaxasExistentes[$iKey]);
                    $oPartilhaCustas = $aPartilhaCustas[$iKey];

                    /* As custas que ficarem serão removidas da partilha*/
                    unset($aPartilhaCustas[$iKey]);
                }

                $fValorCustas = $this->calculaValorCustas($fValor, $oTaxa);
                $oPartilhaCustas->setValor($fValorCustas);
                $oPartilhaCustas->setNumnov($iNumnov);
                $oInicialPartilha->addCustas($oPartilhaCustas);

                if ($oPartilhaCustas->isDispensaLancamentoRecibo()) {
                    continue;
                }

                $fValorTotalPartilha += $fValorCustas;
            }

            $oInicialPartilha->setValorPartilha($fValorTotalPartilha);

            /**
             * Apaga todas as IniciaisPartilhaCustas que nao pertencem mais a partilha
             */
            $this->removeCustasPartilha($aPartilhaCustas);
        }

        return $oInicialPartilha;
    }

    /**
     * @return int[]
     * @throws DBException
     */
    private function getNumpresInicial()
    {
        $oDaoInicialNumpre = new cl_inicialnumpre();
        $sWhere = "v59_inicial = {$this->iInicial}";
        $sSql = $oDaoInicialNumpre->sql_query_file(null, 'v59_numpre', null, $sWhere);

        $rsNumpres = db_query($sSql);

        if (!$rsNumpres) {
            throw new DBException("Erro ao obter os numpres da Inicial {$this->iInicial}");
        }

        $aNumpres = pg_fetch_all_columns($rsNumpres,0);
        return $aNumpres;
    }

    /**
     * Verifica se algum dos numpres pertence a inicial
     * @param $aNumpres
     * @return bool
     */
    private function inInicialNumpre($aNumpres)
    {
        foreach ($aNumpres as $oDebito) {
            if (in_array($oDebito->k00_numpre, $this->aNumpres)) {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * @param float $fValorDebito
     * @param TaxaModel $oTaxa
     * @return float $fValorCustas;
     */
    private function calculaValorCustas($fValorDebito,TaxaModel $oTaxa)
    {
        $fPorcentagemTaxa = $oTaxa->getPercentual();
        $fValorCustas = $oTaxa->getValor();

        if (!empty($fPorcentagemTaxa) && $fPorcentagemTaxa > 0) {

            $fValorCustas =  ($fValorDebito * ($fPorcentagemTaxa / 100));

            if ($fValorCustas < $oTaxa->getValorMinimo()) {
                $fValorCustas = $oTaxa->getValorMinimo();
            } elseif ($fValorCustas > $oTaxa->getValorMaximo()) {
                $fValorCustas = $oTaxa->getValorMaximo();
            }
        }

        return round($fValorCustas, 2);
    }


    /**
     * @param Recibo $oRecibo
     * @return float
     * @throws DBException
     */
    private function getValorBaseCustas(Recibo $oRecibo)
    {
        $iAnoVencimento = substr($oRecibo->getDataVencimento(), 0, 4);
        $aNumpres = $this->aNumpres;
        $sNumpresQuery = implode(",", $aNumpres);

        $sSql        = " select sum( substr( fc_calcula, 15, 13)::numeric(10,2) )  as vlr_corrigido,                        \n";
        $sSql       .= "        sum( substr( fc_calcula, 28, 13)::numeric(10,2) )  as vlr_juros,                            \n";
        $sSql       .= "        sum( substr( fc_calcula, 41, 13)::numeric(10,2) )  as vlr_multa,                            \n";
        $sSql       .= "        sum( substr( fc_calcula, 54, 13)::numeric(10,2) )  as vlr_desconto                          \n";
        $sSql       .= "  from (  select k00_numpre,                                                                        \n";


        if (isset($this->iInicial) && !empty($this->iInicial)) {
            $sSql     .= "fc_calcula(k00_numpre, k00_numpar, k00_receit, '{$oRecibo->getDataVencimento()}', '{$oRecibo->getDataVencimento()}', $iAnoVencimento) \n";
            $sMensagem = "da Inicial {$this->iInicial}";
        } else {
            $sSql     .= "fc_calcula(k00_numpre, k00_numpar, k00_receit, k00_dtvenc, k00_dtvenc, extract(year from k00_dtvenc)::integer ) \n";
            $sMensagem = "do Processo {$this->iProcessoForo}";
        }

        $sSql       .= "            from arrecad                                                                            \n";
        $sSql       .= "           where k00_numpre in ({$sNumpresQuery})                                                \n";
        $sSql       .= "        group by k00_numpre,                                                                        \n";
        $sSql       .= "                 k00_numpar,                                                                        \n";
        $sSql       .= "                 k00_receit,                                                                        \n";
        $sSql       .= "                 k00_dtvenc                                                                         \n";
        $sSql       .= "       ) as calculo;     ";

        $rsValor          = db_query($sSql);

        if (!$rsValor) {
            throw new DBException("Não foi possivel obter o valor base de calculo das custas ". $sMensagem);
        }

        $oValor           = pg_fetch_object($rsValor,0);

        $nCustasProcesso  = $oValor->vlr_corrigido + $oValor->vlr_juros + $oValor->vlr_multa - $oValor->vlr_desconto;

        /* FIXME falta immplementar o desconto */

        $aDescontos = $oRecibo->getTodosDescontosReciboWeb();

        $nValorDesconto = 0;
        foreach ($aDescontos as $oDesconto) {

            if (in_array($oDesconto->iNumpre, $this->aNumpres)) {
                $nValorDesconto += $oDesconto->nValorDesconto;
            }
        }

        $nCustasProcesso = ($nCustasProcesso - $nValorDesconto);

        return $nCustasProcesso;
    }

    /**
     * @param Recibo $oRecibo
     * @return Recibo
     */
    private function adicionaCustasRecibo(Recibo $oRecibo)
    {
        $aPartilhaCustas = $this->oInicialPartilha->getCustas();

        foreach ($aPartilhaCustas as $oPartilhaCustas) {
            if ($oPartilhaCustas->isDispensaLancamentoRecibo()) {
                continue;
            }

            $oTaxa = new TaxaModel($oPartilhaCustas->getCodigoTaxa());
            $iReceita = $oTaxa->getReceita();
            $oRecibo->adicionarReceita($iReceita, $oPartilhaCustas->getValor(), 0, null, self::CODIGO_HISTORICO);
        }

        return $oRecibo;
    }

    /**
     * Retorna os tipos de debitos de iniciais
     * @return array
     */
    public static function getTiposDebitosIniciais()
    {
        return array(18, 12, 13);
    }

    /**
     * @param InicialPartilhaCustas[] $aCustas
     * @throws DBException
     */
    private function removeCustasPartilha($aCustas)
    {
        $oInicialPartilhaCustasRepository = InicialPartilhaCustasRepository::getInstance();
        foreach ($aCustas as $oCustasApagar) {
            if (!$oInicialPartilhaCustasRepository->delete($oCustasApagar)) {
                throw new DBException("Erro ao apagar a custas da taxa {$oCustasApagar->getCodigoTaxa()}.");
            }
        }
    }

    /**
     * @return int
     */
    public function getInicial()
    {
        return $this->iInicial;
    }
}
