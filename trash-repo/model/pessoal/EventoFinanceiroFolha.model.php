<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * Classe que representa evento financeiro da folha 
 * 
 * @package folha
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 * @author Jeferson Belmiro  <jeferson.belmiro@dbseller.com.br> 
 *
 * @version $id$
 */
class EventoFinanceiroFolha {

  /**
   * Rubrica do evento 
   * 
   * @var Rubrica
   * @access private
   */
  private $oRubrica;

  /**
   * Servidor do evento 
   * 
   * @var Servidor
   * @access private
   */
  private $oServidor;

  /**
   * Quantidade do evento
   * 
   * @var mixed
   * @access private
   */
  private $nQuantidade;

  /**
   * Valor do Evento na Competencia 
   * @var    numeric
   * @access private
   */
  private $nValor;

  /**
   * Verifica   
   * @var integer
   * @access private
   */
  private $iNatureza;

  const PROVENTO = 1;
  const DESCONTO = 2; 
  const BASE     = 3;

  /**
   * Define a Rubrica do Evento 
   * 
   * @param Rubrica $oRubrica 
   * @access public
   * @return void
   */
  public function setRubrica( Rubrica $oRubrica ) {

    $this->oRubrica    = $oRubrica;
    return;
  }

  /**
   * Define o servidor 
   * 
   * @param Servidor $oServidor 
   * @access public
   * @return void
   */
  public function setServidor(Servidor $oServidor) {
    $this->oServidor = $oServidor;
  }

  /**
   * Define se a Natureza do Evento � 
   *  -- Provento
   *  -- Desconto
   *  -- Base de C�lculo
   * 
   * @param integer $iNatureza 
   * @access public
   * @return void
   */
  public function setNatureza( $iNatureza ) {
    $this->iNatureza   = $iNatureza;
    return;
  }          
   
  /**
   * Define a Quantide do Evento 
   * 
   * @param  numeric $nQuantidade 
   * @access public
   * @return void
   */
  public function setQuantidade( $nQuantidade ) {
    $this->nQuantidade = $nQuantidade;
    return;
  }          

  /**
   * Define o Valor ocorrido no Evento 
   * 
   * @param  numeric $nValor 
   * @access public
   * @return void
   */
  public function setValor( $nValor ) {
    $this->nValor      = $nValor;
    return;
  }          

  /**
   * Retorna a Rubrica do evento
   * 
   * @access public
   * @return Rubrica
   */
  public function getRubrica() {
    return $this->oRubrica;
  }

  /**
   * Retorna o Servidor do evento
   * 
   * @access public
   * @return Servidor
   */
  public function getServidor() {
    return $this->oServidor;
  }

  /**
   * Retorna a natureza do evento 
   *  -- Provento
   *  -- Desconto
   *  -- Base de C�lculo
   * 
   * @access public
   * @return integer
   */
  public function getNatureza() {
    return $this->iNatureza;
  }          

  /**
   * Retorna a quantidade do evento 
   * 
   * @access public
   * @return numeric
   */
  public function getQuantidade() {
    return $this->nQuantidade;
  }          
    
  /**
   * Retorna o valor do evento 
   * 
   * @access public
   * @return numeric
   */
  public function getValor() {
    return $this->nValor;
  }          

}