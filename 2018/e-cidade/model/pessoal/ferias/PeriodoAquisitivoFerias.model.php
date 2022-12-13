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


require_once(modification('std/db_stdClass.php'));

/**
 * Classe que manipula os períodos aquisitivos de férias de um servidor
 *
 * @author Alberto Ferri Neto <alberto@dbseller.com.br>
 * @package pessoal
 * @revision $Author: dbleonardo.malia $
 * @version  $Revision: 1.30 $
 */
class PeriodoAquisitivoFerias
{
    /**
     * caminho do arquivo JSON das mensagens do model
     */
    const MENSAGENS = 'recursoshumanos.pessoal.PeriodoAquisitivoFerias.';

    /**
     * Código sequencial do período aquisitivo
     * @var integer
     */
    private $iCodigo;

    /**
     * Instância do objeto Servidor
     * @var Servidor
     */
    private $oServidor;

    /**
     * Instância do objeto DBDate com a data inicial do período aquisitivo
     * @var DBDate
     */
    private $oDataInicial;

    /**
     * Instância do objeto DBDate com a data final do período aquisitivo
     * @var DBDate
     */
    private $oDataFinal;

    /**
     * Quantidade de dias de direito a férias
     * @var integer
     */
    private $iDiasDireito = 0;

    /**
     * Quantidade de faltas durante o período aquisitivo
     * @var integer
     */
    private $iFaltasPeriodoAquisitivo = 0;

    /**
     * Observição do registro de férias
     * @var string
     */
    private $sObservacao;

    /**
     * Flag para validar se servidor perdeu periodo aquisitivo
     *
     * @var integer
     */
    private $iPerdeuDireitoFerias;

    /**
     * Construtor da classe
     *
     * @param integer|null $iCodigo
     * @return mixed
     * @throws BusinessException
     */
    public function __construct($iCodigo = null)
    {
        if (empty($iCodigo)) {
            return;
        }

        /**
         * Define o código do periodo e valida se é integer
         */
        $this->setCodigo($iCodigo);

        db_utils::getDao('rhferias', true);

        $oDaoRhFerias = new cl_rhferias();
        $sSqlRhFerias = $oDaoRhFerias->sql_query_file($iCodigo);
        $rsRhFerias = db_query($sSqlRhFerias);

        /**
         * Erro na query de pesquisa
         */
        if (!$rsRhFerias) {
            throw new BusinessException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'erro_buscar_periodo_aquisitivo',
                (object)array('sErroBanco' => pg_last_error())));
        }

        /**
         * Nenhum registro encontrado pelo condigo
         */
        if (pg_num_rows($rsRhFerias) == 0) {
            throw new BusinessException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'busca_periodo_aquisitivo_pelo_codigo',
                (object)array('iCodigo' => $iCodigo)));
        }

        $oDadosPeriodoAquisitivo = db_utils::fieldsMemory($rsRhFerias, 0);

        $this->setServidor(new Servidor($oDadosPeriodoAquisitivo->rh109_regist));
        $this->setDataInicial(new DBDate($oDadosPeriodoAquisitivo->rh109_periodoaquisitivoinicial));
        $this->setDataFinal(new DBDate($oDadosPeriodoAquisitivo->rh109_periodoaquisitivofinal));
        $this->setDiasDireito($oDadosPeriodoAquisitivo->rh109_diasdireito);
        $this->setFaltasPeriodoAquisitivo($oDadosPeriodoAquisitivo->rh109_faltasperiodoaquisitivo);
        $this->setObservacao($oDadosPeriodoAquisitivo->rh109_observacao);
        $this->setPerdeuDireitoFerias($oDadosPeriodoAquisitivo->rh109_perdeudireitoferias);

        return true;
    }

    /**
     * Retorna o código sequencial do período aquisitivo
     * @return integer
     */
    public function getCodigo()
    {
        return $this->iCodigo;
    }

    /**
     * Define o código sequencial do período aquisitivo
     * @param $iCodigo
     * @throws ParameterException
     */
    public function setCodigo($iCodigo)
    {
        if (!DBNumber::isInteger($iCodigo)) {
            throw new ParameterException('Código sequencial do período aquisitivo inválido.');
        }

        $this->iCodigo = $iCodigo;
    }

    /**
     * Retorna uma instância do objeto Servidor que pertence o período aquisitivo
     * @return Servidor
     */
    public function getServidor()
    {
        return $this->oServidor;
    }

    /**
     * Define uma instância do objeto Servidor que pertence o período aquisitivo
     * @param $oServidor
     */
    public function setServidor(Servidor $oServidor)
    {
        $this->oServidor = $oServidor;
    }

    /**
     * Define se servidor tem direito ao periodo aquisitivo.
     *
     * @param integer $iPerdeuDireitoFerias
     */
    public function setPerdeuDireitoFerias($iPerdeuDireitoFerias)
    {
        $this->iPerdeuDireitoFerias = $iPerdeuDireitoFerias;
    }

    /**
     * Retorna flag que verifica se o servidor perdeu periodo aquisitivo.
     *
     * @return integer
     */
    public function getPerdeuDireitoFerias()
    {
        return $this->iPerdeuDireitoFerias;
    }

    /**
     * Retorna uma instância do objeto DBDate com a data inicial do período aquisitivo
     * @return DBDate
     */
    public function getDataInicial()
    {
        return $this->oDataInicial;
    }

    /**
     * Define uma instância do objeto DBDate com a data inicial do período aquisitivo
     * @param $oDataInicial
     */
    public function setDataInicial(DBDate $oDataInicial = null)
    {
        $this->oDataInicial = $oDataInicial;
    }

    /**
     * Retorna uma instância do objeto DBDate com a data final do período aquisitivo
     * @return DBDate
     */
    public function getDataFinal()
    {
        return $this->oDataFinal;
    }

    /**
     * Define uma instância do objeto DBDate com a data final do período aquisitivo
     * @param $oDataFinal
     */
    public function setDataFinal(DBDate $oDataFinal = null)
    {
        $this->oDataFinal = $oDataFinal;
    }

    /**
     * Retorna a quantidade de dias de direito de férias de um servidor
     * @return integer
     */
    public function getDiasDireito()
    {
        return $this->iDiasDireito;
    }

    /**
     * Retorna o saldo de dias de direito de um servidor
     * @return integer
     */
    public function getSaldoDiasDireito()
    {
        $iSaldoDiasDireito = $this->getDiasDireito() - ($this->getDiasAbonados() + $this->getDiasGozados());
        return $iSaldoDiasDireito;
    }

    /**
     * Define a quantidade de dias de direito de férias de um servidor/
     * @param integer $iDiasDireito
     * @throws ParameterException
     */
    public function setDiasDireito($iDiasDireito)
    {
        if (!DBNumber::isInteger($iDiasDireito)) {
            throw new ParameterException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'dias_direito_ferias_invalido'));
        }

        $this->iDiasDireito = $iDiasDireito;
    }

    /**
     * Retorna a quantidade de dias que o servidor faltou durante o período aquisitivo
     * @return integer
     */
    public function getFaltasPeriodoAquisitivo()
    {
        return $this->iFaltasPeriodoAquisitivo;
    }

    /**
     * Define a quantidade de dias que o servidor faltou durante o período aquisitivo
     * @param integer $iFaltasPeriodoAquisitivo
     * @throws ParameterException
     */
    public function setFaltasPeriodoAquisitivo($iFaltasPeriodoAquisitivo)
    {
        if (!DBNumber::isInteger($iFaltasPeriodoAquisitivo)) {
            throw new ParameterException('Número de faltas no período aquisitivo inválido.');
        }

        $this->iFaltasPeriodoAquisitivo = $iFaltasPeriodoAquisitivo;
    }

    /**
     * Define a observação do Periodo Aquisitivo
     * @param string $sObservacao
     */
    public function setObservacao($sObservacao)
    {
        $this->sObservacao = $sObservacao;
    }

    /**
     * Retorna a observação do periodo aquisitivo
     * @return string sObservacao
     */
    public function getObservacao()
    {
        return $this->sObservacao;
    }

    /**
     * Salvar periodo aquisitivo
     *
     * @access public
     * @return bool
     * @throws DBException
     * @throws ParameterException
     */
    public function salvar()
    {
        if (!db_utils::inTransaction()) {
            throw new DBException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'nenhuma_transacao_banco'));
        }

        if (empty($this->oServidor)) {
            throw new DBException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'servidor_nao_informado'));
        }

        if (empty($this->oDataInicial)) {
            throw new DBException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'periodo_aquisitivo_inicial_nao_informado'));
        }

        if (empty($this->oDataFinal)) {
            throw new DBException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'periodo_aquisitivo_final_nao_informado'));
        }

        $this->setDiasDireito(PeriodoAquisitivoFerias::calculaDiasDireito($this->getServidor(),
            $this->getFaltasPeriodoAquisitivo()));

        $oDaoRhFerias = new cl_rhferias();
        $oDaoRhFerias->rh109_regist = $this->getServidor()->getMatricula();
        $oDaoRhFerias->rh109_periodoaquisitivoinicial = $this->getDataInicial()->getDate();
        $oDaoRhFerias->rh109_periodoaquisitivofinal = $this->getDataFinal()->getDate();
        $oDaoRhFerias->rh109_diasdireito = "{$this->getDiasDireito()}";
        $oDaoRhFerias->rh109_faltasperiodoaquisitivo = "{$this->getFaltasPeriodoAquisitivo()}";
        $oDaoRhFerias->rh109_observacao = pg_escape_string($this->getObservacao());
        $oDaoRhFerias->rh109_perdeudireitoferias = $this->getPerdeuDireitoFerias() ? 'true' : 'false';

        /**
         * Incluir periodo aquisitivo
         */
        if (empty($this->iCodigo)) {
            $oDaoRhFerias->rh109_sequencial = null;
            $oDaoRhFerias->incluir(null);

            /**
             * Erro ao incluir periodo aquisitivo
             */
            if ($oDaoRhFerias->erro_status == "0") {
                $oMensagemErro = (object)array('sErroBanco' => $oDaoRhFerias->erro_banco);
                throw new DBException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'erro_incluir_periodo_aquisitivo',
                    $oMensagemErro));
            }

            $this->setCodigo($oDaoRhFerias->rh109_sequencial);

            return true;
        }

        /**
         * Alterar periodo aquisitivo
         */
        $oDaoRhFerias->rh109_sequencial = $this->getCodigo();
        $oDaoRhFerias->alterar($this->getCodigo());

        /**
         * Erro ao alterar periodo aquisitivo
         */
        if ($oDaoRhFerias->erro_status == "0") {
            $oMensagemErro = (object)array('sErroBanco' => $oDaoRhFerias->erro_banco);
            throw new DBException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'erro_alterar_periodo_aquisitivo',
                $oMensagemErro));
        }

        return true;
    }

    /**
     * Retorna o Proximo Periodo Aquisitivo disponivel para a matricula informada
     *
     * @param Servidor $oServidor
     * @return object PeriodoAquisitivoFerias
     * @throws BusinessException
     * @internal param int $iMatricula MAtricula do Servidor
     */
    public static function getDisponivel(Servidor $oServidor)
    {
        $oDaoRhFerias = new cl_rhferias();
        $sSqlRhferias = $oDaoRhFerias->sql_query_proximo_periodo_aquisitivo($oServidor->getMatricula(),
            'rh109_sequencial');
        $rsRhFerias = db_query($sSqlRhferias);

        /**
         * Erro na query de pesquisa
         */
        if (!$rsRhFerias) {
            throw new BusinessException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'erro_buscar_periodo_aquisitivo',
                (object)array('sErroBanco' => pg_last_error())));
        }

        /**
         * Nenhum registro encontrado pela matricula informada
         */
        if (pg_num_rows($rsRhFerias) == 0) {
            throw new BusinessException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'busca_periodo_aquisitivo_pela_matricula',
                (object)array('iCodigo' => $oServidor->getMatricula())));
        }

        $oDadosPeriodoAquisitivo = db_utils::fieldsMemory($rsRhFerias, 0);

        /**
         * Cria uma instância de PeriodoAquisitivoFerias para o periodo aquisitivo disponível
         */
        $oPeriodoAquisitivo = new PeriodoAquisitivoFerias($oDadosPeriodoAquisitivo->rh109_sequencial);

        return $oPeriodoAquisitivo;
    }

    /**
     * Seta o número de dias de direito de acordo com as faltas
     * Cálculo baseado em dias de gozo de 30 dias
     *
     * @static
     * @param  Servidor $oServidor
     * @param  Integer $iQuantidadeFaltas
     * @return number
     * @throws DBException
     */
    public static function calculaDiasDireito(Servidor $oServidor, $iQuantidadeFaltas)
    {
        $iDias = 30;
        $iFaltas = $iQuantidadeFaltas;
        $iRegime = $oServidor->getTipoRegime();
        $oDaoFaltas = new cl_rhcadregimefaltasperiodoaquisitivo();
        $sSql = $oDaoFaltas->sql_query_file(null, "rh125_diasdesconto", null,
            "rh125_rhcadregime = {$iRegime} and {$iFaltas} between rh125_faixainicial and rh125_faixafinal");

        $rsSql = db_query($sSql);

        if (!$rsSql) {
            throw new DBException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'erro_ao_buscar_quatidade_faltas',
                (object)array('sErroBanco' => pg_last_error())));
        }

        if (pg_num_rows($rsSql) == 0) {
            return $iDias;
        }

        $iDesconto = db_utils::fieldsMemory($rsSql, 0)->rh125_diasdesconto;
        $iDias -= $iDesconto;
        return $iDias;
    }

    /**
     * Retorna o número de dias gozados do período aquisitivo
     *
     * @access public
     * @return integer
     */
    public function getDiasGozados()
    {
        $iDiasGozados = 0;

        foreach ($this->getPeriodosGozo() as $oPeriodoGozo) {
            $iDiasGozados += $oPeriodoGozo->getDiasGozo();
        }

        return $iDiasGozados;
    }

    /**
     * Retorna o número de dias abonadoss do período aquisitivo
     *
     * @access public
     * @return integer
     */
    public function getDiasAbonados()
    {
        $iDiasAbonados = 0;

        foreach ($this->getPeriodosGozo() as $oPeriodoGozo) {
            $iDiasAbonados += $oPeriodoGozo->getDiasAbono();
        }

        return $iDiasAbonados;
    }

    /**
     * Retorna os períodos de gozo do período aquisitivo
     *
     * @access public
     * @return PeriodoGozoFerias[]
     * @throws DBException
     */
    public function getPeriodosGozo()
    {
        $oDaoRhFeriasPeriodo = new cl_rhferiasperiodo();
        $sSqlPeriodosAquisitivos = $oDaoRhFeriasPeriodo->sql_query_file(null, "rh110_sequencial", null,
            "rh110_rhferias = $this->iCodigo");
        $rsPeriodos = db_query($sSqlPeriodosAquisitivos);

        if (!$rsPeriodos) {
            $oMensagemErro = (object)array('sErroBanco' => pg_last_error());
            throw new DBException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'erro_buscar_periodo_gozo', $oMensagemErro));
        }

        $aPeriodoGozo = array();

        foreach (db_utils::getCollectionByRecord($rsPeriodos) as $oDadoPeriodoGozo) {
            $aPeriodoGozo[] = new PeriodoGozoFerias($oDadoPeriodoGozo->rh110_sequencial);
        }

        return $aPeriodoGozo;
    }

    /**
     * Retorna todas os períodos aquisitivos do servidor
     *
     * @access public
     * @param Servidor $oServidor
     * @return return getPeriodosPorServidor[]
     * @throws BusinessException
     */
    static function getPeriodosPorServidor(Servidor $oServidor)
    {
        $oDaoRhFerias = new cl_rhferias();
        $sSqlRhFerias = $oDaoRhFerias->sql_query_file(null, 'rh109_sequencial', 'rh109_periodoaquisitivoinicial',
            ' rh109_regist = ' . $oServidor->getMatricula());
        $rsRhFerias = db_query($sSqlRhFerias);

        /**
         * Erro na query de pesquisa
         */
        if (!$rsRhFerias) {
            throw new BusinessException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'erro_buscar_periodo_aquisitivo',
                (object)array('sErroBanco' => pg_last_error())));
        }

        $oDadosPeriodo = db_utils::getCollectionByRecord($rsRhFerias);

        $aPeriodoAquisito = array();

        foreach (db_utils::getCollectionByRecord($rsRhFerias) as $oDadoPeriodo) {
            $aPeriodoAquisito[] = new PeriodoAquisitivoFerias($oDadoPeriodo->rh109_sequencial);
        }

        return $aPeriodoAquisito;
    }

    /**
     * Retorna se o servidor tem direito a férias para o período aquisitivo informado
     * @access public
     * @return return bool
     */
    public function hasDireitoFerias()
    {
        return PeriodoAquisitivoFerias::calculaDiasDireito($this->getServidor(),
            $this->getFaltasPeriodoAquisitivo()) == 0 ? false : true;
    }

    /**
     * Retorna a competencia do período de gozo que foi pago o 1/3 de férias
     * @access public
     * @return DBCompetencia
     */
    public function getCompetenciaPagamentoTerco()
    {
        foreach ($this->getPeriodosGozo() as $oPeriodoGozo) {
            if ($oPeriodoGozo->isPagaTerco()) {
                return new DBCompetencia($oPeriodoGozo->getAnoPagamento(), $oPeriodoGozo->getMesPagamento());
            }
        }

        return;
    }

    /**
     * @param $iCodigo
     * @return \PeriodoGozoFerias
     */
    public function getPeriodoDeFeriasPorCodigo($iCodigo)
    {
        foreach ($this->getPeriodosGozo() as $oPeriodoGozo) {
            if ($oPeriodoGozo->getCodigoPeriodo() == $iCodigo) {
                return $oPeriodoGozo;
            }
        }
    }
}
