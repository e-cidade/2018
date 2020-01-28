<?php
/**
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

/**
 * Classe representa a base de rubricas
 * 
 * @package Pessoal
 * @author $Author: dbrenan.silva $
 * @version $Revision: 1.5 $
 */
class Base {
  
  /**
   * Código da base
   * 
   * @var String
   */
  private $sCodigo;
  
  /**
   * Nome da base
   * 
   * @var String
   */
  private $sNome;
  
  /**
   * Objeto representa a data que foi criado a base
   * 
   * @var DBCompetencia
   */
  private $oCompetencia;
  
  /**
   * Objeto instituição
   * 
   * @var Instituicao 
   */
  private $oInstituicao;
  
  /**
   * Conjunto de rubricas
   * 
   * @var Rubrica[]
   */
  private $aRubricas;
  
  /**
   * Calcular pela quantidade
   * 
   * @var Boolean
   */
  private $lCalculoQuantidade;
 
  /**
   * Calcular pelo ponto fixo
   * 
   * @var Boolean
   */
  private $lCalculoPontoFixo;
  
  /**
   * Pesquisar valores mês anterior
   * 
   * @var Boolean
   */
  private $lValorMesAnterior;
  
  /**
   * Construtor da classe
   * 
   * @param String $sCodigo
   * @param DBCompetencia $oCompetencia
   * @param Instituicao $oInstituicao
   */
  function __construct($sCodigo, DBCompetencia $oCompetencia, Instituicao $oInstituicao) {
        
    if ( !empty($sCodigo) ) {
      
      $this->setCodigo($sCodigo);
      $this->setInstituicao($oInstituicao);
      $this->setCompetencia($oCompetencia);
      
      $oDaoBases = db_utils::getDao('bases');
      $sSqlBases = $oDaoBases->sql_query_file($this->getCompetencia()->getAno(), $this->getCompetencia()->getMes(), $this->getCodigo(), $this->getInstituicao()->getCodigo());
      $rsBases   = $oDaoBases->sql_record($sSqlBases);

      if ( !$rsBases || $oDaoBases->numrows == 0 ) {
        throw new BusinessException("Nenhuma base encontrada para código {$this->getCodigo()}");
      }
    }
  }
  
  /**
   * Retorna o código da base
   * 
   * @access public
   * @return String
   */
  public function getCodigo() {
    return $this->sCodigo;
  }

  /**
   * Retorna o nome da base
   * 
   * @access public
   * @return String
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * Retorna a data que foi criado a base
   * 
   * @access public
   * @return DBCompetencia
   */
  public function getCompetencia() {
    return $this->oCompetencia;
  }

  /**
   * Retorna a instituição da base
   * 
   * @access public
   * @return Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Retona as rubricas que pertence a base
   * 
   * @access public
   * @return Rubrica[]
   */
  public function getRubricas() {
    return $this->aRubricas;
  }

  /**
   * Permitir calcular pela quantidade
   * 
   * @access public
   * @return Boolean
   */
  public function isCalculoQuantidade() {
    return $this->lCalculoQuantidade;
  }

  /**
   * Permitir calcular pelo ponto fixo
   * 
   * @access public
   * @return Boolean
   */
  public function isCalculoPontoFixo() {
    return $this->lCalculoPontoFixo;
  }

  /**
   * Permitir pesquisar valores do mês anterior
   * 
   * @access public
   * @return Boolean
   */
  public function isValorMesAnterior() {
    return $this->lValorMesAnterior;
  }

  /**
   * Seta o código da base
   * 
   * @access private
   * @param String $sCodigo
   */
  private function setCodigo($sCodigo) {
    $this->sCodigo = $sCodigo;
  }

  /**
   * Seta o nome da base
   * 
   * @access public
   * @param String $sNome
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }

  /**
   * Seta a data que foi criado a base
   * 
   * @access private
   * @param DBCompetencia $oCompetencia
   */
  private function setCompetencia(DBCompetencia $oCompetencia) {
    $this->oCompetencia = $oCompetencia;
  }

  /**
   * Seta a instituição da base
   * 
   * @access private
   * @param Instituicao $oInstituicao
   */
  private function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Seta as rubricas que pertencem a base
   * 
   * @access public
   * @param Rubrica[] $aRubricas
   */
  public function setRubricas($aRubricas) {
    $this->aRubricas = $aRubricas;
  }

  /**
   * Permitir calcular pela quantidade
   * 
   * @access public
   * @param Boolean $lCalculoQuantidade
   */
  public function setCalculoQuantidade($lCalculoQuantidade) {
    $this->lCalculoQuantidade = $lCalculoQuantidade;
  }

  /**
   * Permitir calcular pelo ponto fixo
   * 
   * @access public
   * @param Boolean $lCalculoPontoFixo
   */
  public function setCalculoPontoFixo($lCalculoPontoFixo) {
    $this->lCalculoPontoFixo = $lCalculoPontoFixo;
  }

  /**
   * Permitir pesquisar valores do mês anterior
   * 
   * @access public
   * @param Boolean $lValorMesAnterior
   */
  public function setValorMesAnterior($lValorMesAnterior) {
    $this->lValorMesAnterior = $lValorMesAnterior;
  } 
  
  /**
   * Retorna todas as rubricas da base do servidor
   * 
   * @param Servidor $oServidor
   * @return Rubrica[]
   */
  public function getRubricasBaseServidor(Servidor $oServidor) {
    return RubricaRepository::getRubricaByBaseServidor($this, $oServidor);
    
  }

  /**
   * Retorna o valor total das rubricas de uma base
   * 
   * @param  CalculoFolha $oCalculo
   * @return Float
   */
  public function getValorTotalNoCalculo(CalculoFolha $oCalculo, $lConsideraAbsoluto = false, $aRubricas = null) {

    $nTotal    = 0;

    if(empty($aRubricas)) {
      $aRubricas = $this->getRubricas();
    }

    if(!empty($aRubricas)) {

      $aEventosFinanceiros = $oCalculo->getEventosFinanceiros(null, $aRubricas);

      if(!empty($aEventosFinanceiros)) {

        foreach ($aEventosFinanceiros as $oEventoFinanceiro) {

          if($lConsideraAbsoluto === false) {
            
            switch ($oEventoFinanceiro->getRubrica()->getTipo()) {
              case Rubrica::TIPO_PROVENTO:
              case Rubrica::TIPO_BASE:
                $nTotal += $oEventoFinanceiro->getValor();
                break;
              
              case Rubrica::TIPO_DESCONTO:
                $nTotal -= $oEventoFinanceiro->getValor();
                break;
            }
          }

          if($lConsideraAbsoluto) {
            $nTotal += $oEventoFinanceiro->getValor();
          }
        }
      }
    }
    return $nTotal;
  }

}