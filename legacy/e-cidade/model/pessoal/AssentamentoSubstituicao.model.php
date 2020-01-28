<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2015  DBSeller Servicos de Informatica             
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

/**
 * Classe responsavél por controlar os Assentamentos que 
 * possuem a natureza configurada como substituição.
 *
 * @author Renan Melo <renan@dbseller.com.br>
 * @package Pessoal
 */
class AssentamentoSubstituicao extends Assentamento {

  const CODIGO_NATUREZA = 2;

  const MENSAGEM = 'recursoshumanos.pessoal.AssentamentoSubstituicao.';

  /**
   * Servidor substituído
   */
  private $oServidor;
  
  function __construct($iCodigo) {

    parent::__construct($iCodigo);

    if(!empty($iCodigo)) {

      $oDaoAssentamentoSubstituicao = new cl_assentamentosubstituicao();
      $sSqlAssentamentoSubstituicao = $oDaoAssentamentoSubstituicao->sql_query_file($iCodigo);
      $rsAssentamentoSubstituicao   = $oDaoAssentamentoSubstituicao->sql_record($sSqlAssentamentoSubstituicao);

      if(!$sSqlAssentamentoSubstituicao) {
        throw new BusinessException(_M(self::MENSAGEM . "erro_buscar_assentamento_substituicao"));
      }

      if($oDaoAssentamentoSubstituicao->numrows > 0) {
        $iMatriculaServidor = db_utils::fieldsMemory($rsAssentamentoSubstituicao, 0)->rh161_regist;
        $oCompetenciaFolha = DBPessoal::getCompetenciaFolha();
        $this->setSubstituido(ServidorRepository::getInstanciaByCodigo($iMatriculaServidor, $oCompetenciaFolha->getAno(), $oCompetenciaFolha->getMes()));
      }
    }
  }

  /**
   * Define o servidor substituído
   * @param Servidor $oServidor
   */
  public function setSubstituido(Servidor $oServidor) {
    $this->oServidor = $oServidor;
  }

  /**
   * Retorna o servidor substituído
   * @return Servidor
   */
  public function getSubstituido() {
    return $this->oServidor;
  }

  /**
   * Persiste na base de dados um assentamento de substituição, 
   * salvando nas tabelas de assentamento e de assentamentosubstituição
   */
  public function persist() {

    $oDaoAssentamentoSubstituicao = new cl_assentamentosubstituicao();

    $oDaoAssentamentoSubstituicao->rh161_assentamento = $this->getCodigo();
    $oDaoAssentamentoSubstituicao->rh161_regist       = $this->getSubstituido()->getMatricula();

    $rsDaoVerificaAssentamentoSubstituicao = $oDaoAssentamentoSubstituicao->sql_record($oDaoAssentamentoSubstituicao->sql_query_file($this->getCodigo()));

    if($oDaoAssentamentoSubstituicao->numrows == 0) {
      $oDaoAssentamentoSubstituicao->incluir($this->getCodigo());
    } else {
      $oDaoAssentamentoSubstituicao->alterar($this->getCodigo());
    }

    if($oDaoAssentamentoSubstituicao->erro_status == "0") {
      return $oDaoAssentamentoSubstituicao->erro_msg;
    }

    return true;
  }

  /**
   * Informa se o o assentamento está vinculado a algum lote, se estiver retorna o lote
   * @return mixed false | LoteRegistrosPonto
   */
  public function hasLote() {

    $oCompetenciaFolha    = DBPessoal::getCompetenciaFolha();
    $oDaoAssentamentoLote = new cl_assentaloteregistroponto();
    $rsAssentamentoLote   = db_query($oDaoAssentamentoLote->sql_query_file(null, "rh160_loteregistroponto", null, "rh160_assentamento = {$this->getCodigo()}"));

    if(!$rsAssentamentoLote){
      
      throw new BusinessException(_M(self::MENSAGEM."erro_buscar_lote_para_assentamento"));

    } else {

      if(pg_num_rows($rsAssentamentoLote) > 0) {
        
        $oStdAssentamentoLote = db_utils::fieldsMemory($rsAssentamentoLote, 0);
        $oLote = LoteRegistrosPontoRepository::getInstanceByCodigo($oStdAssentamentoLote->rh160_loteregistroponto);

        if($oCompetenciaFolha->comparar($oLote->getCompetencia(), DBCompetencia::COMPARACAO_IGUAL)) {
          return $oLote;
        }
      }

    }

    return false;
  }


  /**
   * toJSON
   *
   */
  public function toJSON() {
    
    $oDados = json_decode(parent::toJSON(), false);
    
    if (empty($oDados)) {
      $oDados = new \stdClass();
    }
    $oDados->natureza                       = "substituicao";
    $oDados->matricula_servidor_substituido = ($this->getSubstituido() instanceof Servidor ? $this->getSubstituido()->getMatricula() : '');
    $oDados->cgm_servidor_substituido       = ($this->getSubstituido() instanceof Servidor ? $this->getSubstituido()->getCgm()->getCodigo() : '');
    $oDados->nome_servidor_substituido      = ($this->getSubstituido() instanceof Servidor ? $this->getSubstituido()->getCgm()->getNome() : '');
    $oDados->valor_substituicao             = $this->getValorCalculado();
    return json_encode($oDados);
  }

  /**
   * Retorn o valor de substituição para o assentamento
   * @return  float $nValor
   */
  public function getValorCalculado() {

    $nValorSubstituto = $this->getValorSubstituto();
    $nValorSubstituido= $this->getValorSubstituido();

    $nValor           = $nValorSubstituido - $nValorSubstituto;
    
    if ($nValor < 0) {
      return 0;
    }

    return $nValor;
  }

  /**
   * Retorna o valor da remuneração do servidor substituto
   * @return float $nValor
   */
  public function getValorSubstituto() {

    $iAno      = $this->getDataConcessao()->getAno();
    $iMes      = $this->getDataConcessao()->getMes();
    $iMesAtual = DBPessoal::getMesFolha();
    $iAnoAtual = DBPessoal::getAnoFolha();

    $oServidor = ServidorRepository::getInstanciaByCodigo($this->getMatricula(), $iAno, $iMes);

    $oBase     = ServidorRepository::getInstanciaByCodigo($this->getMatricula(), $iAnoAtual, $iMesAtual)
                 ->getVinculo()
                 ->getRegime()
                 ->getBaseServidorSubstituto();
    
    if ( empty($oBase) ) {
      return 0;
    }         

    $aRubricas = $oBase->getRubricas();

    $aEventos           = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_PONTO_FIXO)->getEventosFinanceiros(null, $aRubricas);
    $iQuantidadeEventos = count($aEventos);
    $nValor             = 0;

    for ( $iEvento = 0; $iEvento < $iQuantidadeEventos; $iEvento++) {
         
      $oEvento = $aEventos[$iEvento];

      if ( $oEvento->getNatureza() == EventoFinanceiroFolha::PROVENTO ) {
        $nValor += $oEvento->getValor();
      } elseif ( $oEvento->getNatureza() == EventoFinanceiroFolha::DESCONTO ) {
        $nValor -= $oEvento->getValor();
      }
      /**
       * EventoFinanceiroFolha::BASE não é considereda no calculo
       */
    }
    
    $iQuantidadeDias = $this->getDias();
    $iDiasNoMes      = 30;
    if ($this->possuiCalculoPorDiaDoMes()) {
      $iDiasNoMes = cal_days_in_month(CAL_GREGORIAN, $iMes, $iAno);
    }
    $nCoeficiente = $iQuantidadeDias / $iDiasNoMes;
    $nValor       = $nValor * $nCoeficiente;
    
    return $nValor;
  }

  /**
   * Retorna o valor de remuneração do servidor substituido
   * 
   * @return float $nValor
   */
  public function getValorSubstituido() {

    $iAno       = $this->getDataConcessao()->getAno();
    $iMes       = $this->getDataConcessao()->getMes();
    $iMesAtual  = DBPessoal::getMesFolha();
    $iAnoAtual  = DBPessoal::getAnoFolha();
    
    if ( !$this->oServidor ) {

      return 0;
    }
    
    $iMatricula = $this->oServidor->getMatricula();
    
    $oServidor  = ServidorRepository::getInstanciaByCodigo($iMatricula, $iAno, $iMes);
    $oBase      = ServidorRepository::getInstanciaByCodigo($this->getMatricula(), $iAnoAtual, $iMesAtual)
                 ->getVinculo()
                 ->getRegime()
                 ->getBaseServidorSubstituido();
    
    if ( empty($oBase) ) {
      return 0;
    }         
            
    $aRubricas = $oBase->getRubricas();         

    $aEventos           = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_PONTO_FIXO)->getEventosFinanceiros(null, $aRubricas);
    $iQuantidadeEventos = count($aEventos);
    $nValor             = 0;

    for ( $iEvento = 0; $iEvento < $iQuantidadeEventos; $iEvento++) {
         
      $oEvento = $aEventos[$iEvento];

      if ( $oEvento->getNatureza() == EventoFinanceiroFolha::PROVENTO ) {

        $nValor += $oEvento->getValor();
      } elseif ( $oEvento->getNatureza() == EventoFinanceiroFolha::DESCONTO ) {

        $nValor -= $oEvento->getValor();
      }
      /**
       * EventoFinanceiroFolha::BASE não é considereda no calculo
       */
    }

    $iQuantidadeDias = $this->getDias();

    $iDiasNoMes = 30;
    if ($this->possuiCalculoPorDiaDoMes()) {
      $iDiasNoMes = cal_days_in_month(CAL_GREGORIAN, $iMes, $iAno);
    }

    $nCoeficiente = $iQuantidadeDias / $iDiasNoMes;
    $nValor       = $nValor * $nCoeficiente;
    return $nValor;
  }
}
