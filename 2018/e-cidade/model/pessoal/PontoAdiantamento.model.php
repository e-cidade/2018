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
 
require_once 'model/pessoal/Ponto.model.php';

/**
 * Definições sobrte ponto adiantamento do Servidors
 * 
 * @uses    Ponto
 * @package Pessoal 
 * @author  Jeferson Belmiro <jeferson.belmiro@dbseller.com.br> 
 */
class PontoAdiantamento extends Ponto {

  /**
   * Nome da tabela para ponto adiantamento 
   */
  const TABELA = Ponto::ADIANTAMENTO;

  /**
   * Sigla da tabela do ponto adiantamento 
   */
  const SIGLA_TABELA = 'r21';

  /**
   * Construtor da classe
   *
   * @param Servidor $oServidor
   * @access public
   * @return void
   */
  public function __construct( Servidor $oServidor) {

    parent::__construct($oServidor);

    $this->sTabela = self::TABELA;
    $this->sSigla  = self::SIGLA_TABELA;
  }

  /**
   * Função para gerar ponto para o mes selecionado
   */
  public function gerar() {

  }

  /**
   * Funcao para retornar as movimentacoes das rubricas do ponto
   */
  public function getMovimentacoes($iCodigoRubrica = null) {
  }

  /**
   * Funcao para retornar as rubricas utilizadas no ponto
   */
  public function getRubricas() {
  }
  

}