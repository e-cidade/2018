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
 
require_once(modification("model/pessoal/CalculoFolha.model.php"));
/**
 * Definiões sobre o Calculo de 13º Salario de um servidor em uma competencia
 * 
 * @uses    Ponto
 * @package Pessoal 
 * @author  Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 * @author  Jeferson Belmiro  <Jeferson.belmiro@dbseller.com.br> 
 */
class CalculoFolha13o extends CalculoFolha {
	
	const TABELA       = 'gerfs13';
	const SIGLA_TABELA = 'r35';
  
  public function __construct ( Servidor $oServidor ) {

    parent::__construct($oServidor);

    $this->sTabela = self::TABELA;
    $this->sSigla  = self::SIGLA_TABELA;
    
  } 
  
  /**
   * Função para gerar ponto para o mes selecionado
   */
  public function calcular() {}

  public function gerar()    {}

  /**
   * Método que calcula o valor da isenção para o servidor atual
   *
   * @access public
   * @return Number
   */
  public function ajustarParcelaIsentaAposentadoPensionista($sRubrica, $nValorIsencao, $nValorAtual) {

    $oServidorAtual    = $this->getServidor();
    $nValorMaximoAtual = $nValorAtual;

    LogCalculoFolha::write('');
    LogCalculoFolha::write('Ajustando parcela de isencao para o servidor: '.$oServidorAtual->getMatricula());

    $mValorVinculado = $this->verificarParcelaIsentaAposentadoPensionistaServidorVinculado($oServidorAtual, $sRubrica);

    if($mValorVinculado !== false) {
      return $this->calcularParcelaIsentaAposentadoPensionista($nValorIsencao, $nValorMaximoAtual, $nValorAtual, $mValorVinculado);
    }

    return $this->calcularParcelaIsentaAposentadoPensionista($nValorIsencao, $nValorMaximoAtual, $nValorAtual);
  }
}