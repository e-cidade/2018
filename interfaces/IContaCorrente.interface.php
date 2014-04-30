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
 * Interface com os comportamentos e características obrigatórias para as Contas Correntes
 * @author Acácio Schneider <acacio.schneider@dbseller.com.br>
 * @version $Revision: 1.3 $
 */
interface IContaCorrente{

  /**
   * Responsável por criar um objeto do tipo conta corrente descoberto através do código reduzido
   * @param integer $iCodigoLancamento - código dos valores e contas da tabela conlancamval
   * @param integer $iCodigoReduzido - código reduzido da conplanoreduz
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar - Objeto que extenda um lançamento auxiliar
   */
  public function __construct($iCodigoLancamento, $iCodigoReduzido, ILancamentoAuxiliar $oLancamentoAuxiliar);

  /**
   * Salvar os dados da conta corrente
   */
  public function salvar();

  /**
   * Retorna a Instituição da conta corrente
   * @return Instituicao
   */
  public function getInstituicao();

  /**
   * Seta a instituição da conta corrente
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
   * Retorna a data do lançamento
   * @return date
   */
  public function getDataLancamento();

  /**
   * Seta a data de lançamento
   * @param date $dtLancamento
   */
  public function setDataLancamento($dtLancamento);
}
?>