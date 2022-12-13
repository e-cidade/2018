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

require_once modification("libs/db_libpessoal.php");
require_once modification("libs/exceptions/DBException.php");

/**
 * Composicao do ponto de ferias
 * 
 * @package Pessoal
 * @subpackage Ferias
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br> 
 */
class ComposicaoPontoFerias {

  /**
   * Caminho do JSON com as mensagens 
   */
  const MENSAGENS = 'recursoshumanos.pessoal.ComposicaoPontoFerias.'; 

  /**
   * Array com os periodos de gozo da composicao
   * @var PeriodoGozoFerias[]
   */
  private $aPeriodosGozo;

  /**
   * Servidor da Composicao do Ponto
   * 
   * @var Servidor
   */
  private $oServidor;
  
  /**
   * Data inicial usada para calculo de media
   * 
   * @var DBdate
   * @access private
   */
  private $oDataInicialCalculoMedia;
  
  /**
   * Data final usada para calculo de media
   * 
   * @var DBdate
   * @access private
   */
  private $oDataFinalCalculoMedia;   
   
  /**
   * Construtor da Classe
   * 
   * @param Servidor $oServidor
   */
  public function __construct( Servidor $oServidor ) {
    $this->oServidor = $oServidor;
  }

  /**
   * Adiciona periodo gozo
   *
   * @param  PeriodoGozoFerias $oPeriodoGozo
   * @return void
   */
  public function adicionarPeriodoGozo ( PeriodoGozoFerias $oPeriodoGozo ) {
    $this->aPeriodosGozo[ $oPeriodoGozo->getCodigoPeriodo() ] = $oPeriodoGozo;
  }

  /**
   * Gera registros do ponto
   * - Percorre os periodos de gozo
   * - Busca competencias do periodo
   * - Calcula valor e quantidade da media(CalculoMediaRubrica)
   * - salva na rhferiasperiodopontofe
   *
   * @access public
   * @return bool
   * @throws DBException
   */
  public function gerarRegistrosPonto() {

    $oDaoRhferiasperiodopontofe = new cl_rhferiasperiodopontofe;

    /**
     * Percorre os periodos do gozo
     * - Procura as rubricas do perido aquisitivo ou especifico
     * - inclue na tabela de composicao do ponto, rhferiasperiodopontofe 
     */

    foreach( $this->aPeriodosGozo as $oPeriodoGozo ) {

      $oDaoRhferiasperiodopontofe->excluir(null, "rh112_rhferiasperiodo = {$oPeriodoGozo->getCodigoPeriodo()}");
      if ($oDaoRhferiasperiodopontofe->erro_status == 0) {

        $sMensagemErro = _M(self::MENSAGENS . 'erro_remover_ponto_ferias');
        throw new DBException($sMensagemErro);
      }

      /**
       * Retorna o cálculo das médias de rubricas dentro do periodo de gozo 
       * @var CalculoMediaRubrica[]
       */
      $aCalculoRubricas = $oPeriodoGozo->calcularMediaRubricas();

      /**
       * Define Como Será a Proporcao do Pagamento
       *
       * - O pagamento padrão sempre é 30 dias, mas podendo se dividir em:
       * --- Dias Gozados    - Quando Os dias de Gozo são na mesma competencia do pagameto
       * --- Dias Abonado    - Quando Ocorre o abono de Salário
       * --- Dias Adiantados - Quando a compentencia de pagamento é menor que o início do periodo de gozo
       *
       * Exemplo:
       * Periodo de Gozo de Férias de 20/09/2013 - 09/10/2013, sendo 10 dias de Abono.
       *  - Dias Abonados   - 10 Dias
       *  - Dias Gozados    - 11 Dias
       *  - Dias Adiantados - 09 Dias
       *  
       * O pagamento Será da Seguinte Forma
       *  - Rubrica 0001 - Vencimento S/ Férias 
       *    - Quantidade Calculada - 30.00 
       *    - Valor Calculado      -  0.00
       *  + Na composicao do Ponto de Férias será Lançado
       *  |
       *  +--Abono
       *  |  |
       *  |  +--Rubrica         : 0001 
       *  |  +--Quantidade      : 10 Resultado = ( ( Quantidade / 30 ) * Dias Abonados)
       *  |  +--Valor           : 0  Resultado = ( ( Valor      / 30 ) * Dias Abonados)
       *  |  +--Tipo Pgto (TPP) : 'A' ( "A"BONO )        || Usar constante PontoFerias::TIPO_PAGAMENTO_ABONO
       *  +--Adiantamento
       *  |  |
       *  |  +--Rubrica         : 0001 
       *  |  +--Quantidade      : 09 Resultado = ( ( Quantidade / 30 ) * Dias Adiantados)
       *  |  +--Valor           : 00 Resultado = ( ( Valor      / 30 ) * Dias Adiantados)
       *  |  +--Tipo Pgto (TPP) : 'D' ( A"D"IANTAMENTO ) || Usar Constante PontoFerias::TIPO_PAGAMENTO_ADIANTAMENTO
       *  +--Gozo
       *     |
       *     +--Rubrica         : 0001 
       *     +--Quantidade      : 11 Resultado = ( ( Quantidade / 30 ) * Dias Gozados)
       *     +--Valor           : 0  Resultado = ( ( Valor      / 30 ) * Dias Gozados)
       *     +--Tipo Pgto (TPP) : 'F' ( "F"ÉRIAS )       || Usar Constante PontoFerias::TIPO_PAGAMENTO_FERIAS
       *   
       */
      $aDiasProporcaoPagamento[PontoFerias::TIPO_PAGAMENTO_FERIAS]       = $oPeriodoGozo->getDiasGozo();
      $aDiasProporcaoPagamento[PontoFerias::TIPO_PAGAMENTO_ABONO]        = $oPeriodoGozo->getDiasAbono();
      $aDiasProporcaoPagamento[PontoFerias::TIPO_PAGAMENTO_ADIANTAMENTO] = 0; //$oPeriodoGozo->getDiasAdiantamento(); 

      /**
       * Percorremos os cálculos de média de rubricas e lançamos os valores proporcionais a cada tipo de pagamento
       */

      foreach ($aCalculoRubricas as $oCalculoMediaRubrica) { 

        $nValorBase      = $oCalculoMediaRubrica->getValorCalculado(); 
        $nQuantidadeBase = $oCalculoMediaRubrica->getQuantidadeCalculada();

        foreach ( $aDiasProporcaoPagamento as $sTipoPagamento => $iDiasProporcionalidade ) {

          if ( $iDiasProporcionalidade == 0 ) {
            continue;
          }

          /**
           * Efetua calculo baseado na formula
           */
          $nValorCalculado      = ( $nValorBase / 30 )      * $iDiasProporcionalidade;
          $nQuantidadeCalculada = ( $nQuantidadeBase / 30 ) * $iDiasProporcionalidade;

          /**
           * Codigo da rubrica de ferias
           * - soma mais 2000 pois é uma rubrica de ferias 
           * Exemplo: rubrica 0002 ficaria 2002
           */
          $sCodigoRubricaFerias       = str_pad((int) $oCalculoMediaRubrica->getRubrica()->getCodigo() + 2000, 4, "0", STR_PAD_LEFT);
          $oDaoRhferiasperiodopontofe = new cl_rhferiasperiodopontofe();
          $oDaoRhferiasperiodopontofe->rh112_rhferiasperiodo = $oPeriodoGozo->getCodigoPeriodo();
          $oDaoRhferiasperiodopontofe->rh112_anousu          = $this->oServidor->getAnoCompetencia();
          $oDaoRhferiasperiodopontofe->rh112_mesusu          = $this->oServidor->getMesCompetencia();
          $oDaoRhferiasperiodopontofe->rh112_regist          = $this->oServidor->getMatricula();
          $oDaoRhferiasperiodopontofe->rh112_rubric          = $sCodigoRubricaFerias; 
          $oDaoRhferiasperiodopontofe->rh112_tpp             = $sTipoPagamento;
          $oDaoRhferiasperiodopontofe->rh112_quantidade      = "$nQuantidadeCalculada";
          $oDaoRhferiasperiodopontofe->rh112_valor           = "$nValorCalculado";
          $oDaoRhferiasperiodopontofe->incluir(null); 
          /**
           * Erro ao incluir composicao do ponto de ferias(rhferiasperiodopontofe) 
           */
          if ( $oDaoRhferiasperiodopontofe->erro_status == "0" ) {

            $oCamposErro   = (object) array('sErroBanco' => $oDaoRhferiasperiodopontofe->erro_banco);
            $sMensagemErro = _M(self::MENSAGENS . 'erro_incluir_composicao', $oCamposErro);
            throw new DBException($sMensagemErro);
          }
        }
      }
    }
    return true;
  }

  /**
   * Retorna registros do ponto
   * - soma valor e quantidade por rubrica
   *
   * @access public
   * @return array - registrod do ponto de ferias
   */
  public function getRegistros() {

    $oDaoRhferiasperiodopontofe = db_utils::getDao('rhferiasperiodopontofe');

    /**
     * Campos 
     * - codigo da rubrica
     * - tipo de pagamento
     * - quantidade
     * - dias de gozo
     */
    $sCamposComposicao  = "rh112_rubric          as codigo_rubrica, ";
    $sCamposComposicao .= "rh112_tpp             as tipo_pagamento, ";
    $sCamposComposicao .= "sum(rh112_valor)      as valor,          ";
    $sCamposComposicao .= "sum(rh112_quantidade) as quantidade,     ";
    $sCamposComposicao .= "sum(rh110_dias)       as dias            ";

    /**
     * where
     * por ano, mes e servidor 
     */
    $sWhereComposicao  = "     rh112_anousu = {$this->oServidor->getAnoCompetencia()} ";
    $sWhereComposicao .= " and rh112_mesusu = {$this->oServidor->getMesCompetencia()} ";
    $sWhereComposicao .= " and rh112_regist = {$this->oServidor->getMatricula()}      ";

    /**
     * Agrupando pelos campos
     */
    $sWhereComposicao .= " group by rh112_rubric, ";
    $sWhereComposicao .= "          rh112_tpp,    ";
    $sWhereComposicao .= "          rh112_regist  ";

    $sSqlComposicao = $oDaoRhferiasperiodopontofe->sql_query(null, $sCamposComposicao, null, $sWhereComposicao);
    $rsComposicao   = db_query($sSqlComposicao);

    /**
     * Erro na query da composicao 
     */
    if ( !$rsComposicao ) {

      $oCamposErro   = (object) array('sErroBanco' => pg_last_error());
      $sMensagemErro = _M(self::MENSAGENS . 'erro_incluir_composicao', $oCamposErro);

      throw new DBException($sMensagemErro);
    }

    /**
     * Nenhum registro encontrado para competencia atual 
     */
    if ( pg_num_rows($rsComposicao) == 0 ) {
      return array();
    }

    $aRegistrosPonto = array();
    $aDadosRegistros = db_utils::getCollectionByRecord($rsComposicao, true);

    foreach( $aDadosRegistros as $oDadosRegistro ) {

      $nQuantidadeProporcional = $oDadosRegistro->quantidade;

      if ( $nQuantidadeProporcional > 0 ) {
        //$nQuantidadeProporcional = round($nQuantidadeProporcional/30 * $oDadosRegistro->dias, 2);
      }
     
      $oRegistroPontoFerias = new RegistroPontoFerias();
      $oRegistroPontoFerias->setRubrica(RubricaRepository::getInstanciaByCodigo($oDadosRegistro->codigo_rubrica));
      $oRegistroPontoFerias->setValor($oDadosRegistro->valor);
      $oRegistroPontoFerias->setQuantidade($nQuantidadeProporcional);
      $oRegistroPontoFerias->setTipoPagamento($oDadosRegistro->tipo_pagamento);

      $aRegistrosPonto[] = $oRegistroPontoFerias;
    }

    return $aRegistrosPonto;
  }

  /**
   * Exclui a composicao do ponto pelo periodo de gozo
   *
   * @param PeriodoGozoFerias $oPeriodoGozoFerias
   * @access public
   * @return boolean
   */
  public function excluir(PeriodoGozoFerias $oPeriodoGozoFerias) {

    $oDaoRhferiasperiodopontofe = db_utils::getDao('rhferiasperiodopontofe');
    $oDaoRhferiasperiodopontofe->excluir(null, "rh112_rhferiasperiodo = " . $oPeriodoGozoFerias->getCodigoPeriodo()); 

    /**
     * Erro ao excluir composicao do ponto 
     */
    if ( $oDaoRhferiasperiodopontofe->erro_status == "0" ) {

      $oCamposErro   = (object) array('sErroBanco' => $oDaoRhferiasperiodopontofe->erro_banco);
      $sMensagemErro = _M(self::MENSAGENS . 'erro_incluir_composicao', $oCamposErro);

      throw new DBException($sMensagemErro);
    }

    return true;
  }

}