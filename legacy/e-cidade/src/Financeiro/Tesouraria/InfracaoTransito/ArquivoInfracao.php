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

namespace ECidade\Financeiro\Tesouraria\InfracaoTransito;

/**
 * Class ArquivoInfracao
 * Classe que representa o arquivo importado.
 * @package ECidade\Financeiro\Tesouraria\InfracaoTransito
 */
class ArquivoInfracao
{

    /**
     * @var int
     */
    private $iId;

    /**
     * @var \DateTime
     */
    private $dtImportacao;

    /**
     * @var \DateTime
     */
    private $dtPagamento;

    /**
     * @var \DateTime
     */
    private $dtRepasse;

    /**
     * @var int
     */
    private $iRegistro;

    /**
     * @var float
     */
    private $vlBruto;

    /**
     * @var float
     */
    private $vlPrefeitura;

    /**
     * @var float
     */
    private $vlDuplicado;

    /**
     * @var float
     */
    private $vlFunset;

    /**
     * @var float
     */
    private $vlDetran;

    /**
     * @var float
     */
    private $vlPrestacaoContas;

    /**
     * @var float
     */
    private $vlOutros;

    /**
     * @var string
     */
    private $sConvenio;

    /**
     * @var string
     */
    private $sRemessa;

    /**
     * @var \DateTime
     */
    private $dtMovimento;

    /**
     * Colecao de multas do arquivo
     * @var Multa[]
     */
    private $aMultas;

    public function __construct()
    {

    }

    /**
     * Seta o Id do Objeto
     * @param int $iId [i07_sequencial]
     */
    public function setId($iId)
    {
        $this->iId = $iId;
    }

    /**
     * Retorna o Id do Objeto
     * @return int [i07_sequencial]
     */
    public function getId()
    {
        return $this->iId;
    }

    /**
     * Seta a data de Importacao do arquivo
     * @param [date] $dtImportacao [i07_dtimportacao]
     */
    public function setDataImportacao($dtImportacao)
    {
        $this->dtImportacao = $dtImportacao;
    }

    /**
     * Retorna a data da Importacao
     * @return \DateTime [description]
     */
    public function getDataImportacao()
    {
        return $this->dtImportacao;
    }

    /**
     * Seta a data de pagamento
     * @param \DateTime $dtPagamento [i07_dtpagamento]
     */
    public function setDataPagamento($dtPagamento)
    {
        $this->dtPagamento = $dtPagamento;
    }

    /**
     * Retorna a data de pagamento
     * @return \DateTime [i07_dtpagamento]
     */
    public function getDataPagamento()
    {
        return $this->dtPagamento;
    }

    /**
     * seta a data de Repasse
     * @param [date] $dtRepasse [i07_dtrepasse]
     */
    public function setDataRepasse($dtRepasse)
    {
        $this->dtRepasse = $dtRepasse;
    }

    /**
     * retorna da data de Repasse
     * @return \DateTime [i07_dtrepasse]
     */
    public function getDataRepasse()
    {
        return $this->dtRepasse;
    }


    /**
     * Seta a quantidade de registros do arquivo
     * @param int $iRegistro [i07_registro]
     */
    public function setRegistro($iRegistro)
    {
        $this->iRegistro = $iRegistro;
    }

    /**
     * Retorna a quantidade de registros do arquivo
     * @return int [i07_registro]
     */
    public function getRegistro()
    {
        return $this->iRegistro;
    }

    /**
     * Seta o valor bruto arrecadado no arquivo
     * @param float $vlBruto [i07_vlbruto]
     */
    public function setValorBruto($vlBruto)
    {
        $this->vlBruto = $vlBruto;
    }

    /**
     * Retorna o valor bruto arrecadado no arquivo
     * @return float [i07_vlbruto]
     */
    public function getValorBruto()
    {
        return $this->vlBruto;
    }

    /**
     * seta do valor arrecadado pela prefeitura
     * @param float $vlPrefeitura [i07_vlprefeitura]
     */
    public function setValorPrefeitura($vlPrefeitura)
    {
        $this->vlPrefeitura = $vlPrefeitura;
    }

    /**
     * Retorna o valor arrecadado pela Prefeitura
     * @return float [i07_vlprefeitura]
     */
    public function getValorPrefeitura()
    {
        return $this->vlPrefeitura;
    }

    /**
     * Seta o valor de pagamentos em duplicidade
     * @param float $vlDuplicado [i07_duplicado]
     */
    public function setValorDuplicado($vlDuplicado)
    {
        $this->vlDuplicado = $vlDuplicado;
    }

    /**
     * Retorna o valor de pagamentos em duplicidade
     * @return float [i07_duplicado]
     */
    public function getValorDuplicado()
    {
        return $this->vlDuplicado;
    }


    /**
     * Seta o valor repassado ao FUNSET
     * @param float $vlFunset [i07_vlfunset]
     */
    public function setValorFunset($vlFunset)
    {
        $this->vlFunset = $vlFunset;
    }


    /**
     * Retorna o valor repassado ao FUNSET
     * @return float [i07_vlfunset]
     */
    public function getValorFunset()
    {
        return $this->vlFunset;
    }

    /**
     * Seta o valor arrecadado pelo DETRAN
     * @param float $vlDetran [i07_vldetran]
     */
    public function setValorDetran($vlDetran)
    {
        $this->vlDetran = $vlDetran;
    }

    /**
     * Retorna o valor arrecadado pelo Detran
     * @return float [i07_vldetran]
     */
    public function getValorDetran()
    {
        return $this->vlDetran;
    }

    /**
     * Seta o Valor da Prestacao de Contas
     * @param float $vlPrestacaoContas [i07_vlprestacaocontas]
     */
    public function setValorPrestacaoContas($vlPrestacaoContas)
    {
        $this->vlPrestacaoContas = $vlPrestacaoContas;
    }

    /**
     * Retorna o valor da prestacao de contas
     * @return float [i07_vlprestacaocontas]
     */
    public function getValorPrestacaoContas()
    {
        return $this->vlPrestacaoContas;
    }

    /**
     * Seta o valor de Outros
     * @param float $vlOutros [i07_vloutros]
     */
    public function setValorOutros($vlOutros)
    {
        $this->vlOutros = $vlOutros;
    }

    /**
     * Retorna o valor de outros
     * @return float [i07_vloutros]
     */
    public function getValorOutros()
    {
        return $this->vlOutros;
    }

    /**
     * Seta o codigo de convenio
     * @param [string] $sConvenio [i07_convenio]
     */
    public function setConvenio($sConvenio)
    {
        $this->sConvenio = $sConvenio;
    }

    /**
     * Retorna o código de convenio
     * @return string [i07_convenio]
     */
    public function getConvenio()
    {
        return $this->sConvenio;
    }

    /**
     * Seta o codigo de Remessa
     * @param [string] $sRemessa [i07_remessa]
     */
    public function setRemessa($sRemessa)
    {
        $this->sRemessa = $sRemessa;
    }

    /**
     * Retorna o codigo de Remessa
     * @return string [i07_remessa]
     */
    public function getRemessa()
    {
        return $this->sRemessa;
    }

    /**
     * Seta a data do Movimento
     * @param [date] $dtMovimento [i07_dtmovimento]
     */
    public function setDataMovimento($dtMovimento)
    {
        $this->dtMovimento = $dtMovimento;
    }

    /**
     * Retorna a data do Movimento
     * @return \DateTime [i07_dtmovimento]
     */
    public function getDataMovimento()
    {
        return $this->dtMovimento;
    }

    /**
     * Retorna uma colecao de Multas do arquivo
     * @return Multa[]
     */
    public function getMultas()
    {
        if (empty($this->aMultas)) {
            return NULL;
        }

        return $this->aMultas;
    }

    /**
     * Adiciona a multa ao arquivo
     * @param Multa $oMulta
     */
    public function adicionaMulta(Multa $oMulta)
    {
        $this->aMultas[] = $oMulta;
    }

}