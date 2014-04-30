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
 * Interface para arredondamento na educacao
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package educacao
 */
interface IEducacaoArredondamento {
  
  /**
   * Realiza o arredondamento do valor passado, conforme as regras ativas.
   * caso nao exista nenhuma regra ativa ou valor seja um numero inteiro,
   * apenas retorna ele mesmo;
   * @param float   $nValor
   * @param integer $iAno
   */
  public static function arredondar($nValor, $iAno);
  
  /**
   * Retorna as faixas de arredondamento ativo da escola;
   * @param  integer $iAno - Ano da configuracao
   * @return integer;
   */
  public static function getFaixasDeArredondamento($iAno);
  
  /**
   * Retorna as faixas de arredondamento ativo da escola;
   * @return integer;
   */
  public static function getMascara($iAno);
  
  /**
   * Retorna o numero de casas decimais que a regra utiliza.
   * @param  integer - $iAno Ano da configuracao
   * @return integer;
   */
  public static function getNumeroCasasDecimais($iAno);
  
  /**
   * Verifica se a configuração permite o arredondamento do valor
   * @param integer $iAno - Ano da configuracao
   * @return boolean
   */
  public static function arredondaValor($iAno);
  
  /**
   * Formata o numero conforme mascara
   * @param float $nValor - Valor a ser formatado
   * @param integer $iAno - Ano para buscar as regras de formatacao
   * @return string retorna o valor formatado
   */
  public static function formatar($nValor, $iAno);
}