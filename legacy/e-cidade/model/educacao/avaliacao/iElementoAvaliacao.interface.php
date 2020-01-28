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
 * Interface para os elementos de avaliacao
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.3 $
 */
interface IElementoAvaliacao {
  
  /**
   * Codigo do Elemento de Avaliacao
   * @return integer
   */
  public function getCodigo();
  
  /**
   * Descricao do Elemento de Avaliacao
   * @return string
   */
  public function getDescricao();

  /**
   * Retorna uma instancia da Forma de Avaliacao da escola
   * @return FormaAvaliacao
   */
  public function getFormaDeAvaliacao(); 
  
  /**
   * Retorna a ordem de apresentacao da Avaliacao
   * @return integer
   */
  public function getOrdemSequencia();
  
  /**
   * Retorna se o elemento de Avaliacao e um resultado.
   */
  public function isResultado();

  /**
   * Retorna a quantidade maxima de disciplinas que o aluno pode reprovar para estar apto a recuperação.
   * @return integer
   */
  public function quantidadeMaximaDisciplinasParaRecuperacao();
  
}