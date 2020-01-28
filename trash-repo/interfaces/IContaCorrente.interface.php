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
 * Interface com os comportamentos e caracter�sticas obrigat�rias para as Contas Correntes
 * @author Ac�cio Schneider <acacio.schneider@dbseller.com.br>
 * @version $Revision: 1.3 $
 */
interface IContaCorrente{

  /**
   * Respons�vel por criar um objeto do tipo conta corrente descoberto atrav�s do c�digo reduzido
   * @param integer $iCodigoLancamento - c�digo dos valores e contas da tabela conlancamval
   * @param integer $iCodigoReduzido - c�digo reduzido da conplanoreduz
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar - Objeto que extenda um lan�amento auxiliar
   */
  public function __construct($iCodigoLancamento, $iCodigoReduzido, ILancamentoAuxiliar $oLancamentoAuxiliar);

  /**
   * Salvar os dados da conta corrente
   */
  public function salvar();

  /**
   * Retorna a Institui��o da conta corrente
   * @return Instituicao
   */
  public function getInstituicao();

  /**
   * Seta a institui��o da conta corrente
   * @param Instituicao $oEntidade
   */
  public function setInstituicao(Instituicao $oEntidade);

  /**
   * Retorna a Conta do Plano de contas PCASP
   * @return ContaPlanoPCASP
   */
  public function getContaPlano();

  /**
   * Seta a Conta do Plano de contas PCASP
   * @param ContaPlanoPCASP $oContaPlano
   */
  public function setContaPlano(ContaPlanoPCASP $oContaPlano);

  /**
   * Retorna a data do lan�amento
   * @return date
   */
  public function getDataLancamento();

  /**
   * Seta a data de lan�amento
   * @param date $dtLancamento
   */
  public function setDataLancamento($dtLancamento);
}
?>