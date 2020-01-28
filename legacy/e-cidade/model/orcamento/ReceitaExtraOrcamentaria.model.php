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
 * Model para controle de Receitas Extras Orçamentárias
 * @author Acácio Schneider <acacio.schneider@dbseller.com.br>
 * @package orcamento
 * @version $Revision: 1.3 $
 */
class ReceitaExtraOrcamentaria {

  /**
   * Codigo da receita (tabrec)
   * @var integer
   */
  protected $iCodigoReceita;

  /**
   * Ano da receita (tabplan.k02_anousu)
   * @var integer
   * @access protected
   */
  protected $iAnoUsu;

  /**
   * Objeto contendo a conta associada a receita extra-orçamentária
   * @var ContaPlanoPCASP
   */
  protected $oContaPlanoPCASP;

  /**
   * Constrói um objeto referente a receita extra-orçamentária
   * @param integer $iCodigoReceita
   * @throws BusinessException
   */
  public function __construct($iCodigoReceita = null, $iAnoReceita = null) {

    $this->iCodigoReceita = $iCodigoReceita;
    if (!empty($iCodigoReceita)) {

      if (empty($iAnoReceita)) {
        $iAnoReceita = db_getsession("DB_anousu");
      }
      $oDaoTabRec     = db_utils::getDao("tabrec");
      $sWhereReceita  = "     tabplan.k02_codigo = {$iCodigoReceita}";
      $sWhereReceita .= " and tabplan.k02_anousu = {$iAnoReceita}";

      $sSqlBuscaReceita = $oDaoTabRec->sql_query_receita_extra_orcamentaria(null, "tabplan.*", null, $sWhereReceita);
      $rsBuscaReceita   = $oDaoTabRec->sql_record($sSqlBuscaReceita);

      if ($oDaoTabRec->erro_status == "0") {

        $sMensagem  = "Não foi possível localizar a receita extra-orçamentária {$iCodigoReceita} ";
        $sMensagem .= "para o ano de {$iAnoReceita}";
        throw new BusinessException($sMensagem);
      }

      $oStdDadosReceita       = db_utils::fieldsMemory($rsBuscaReceita, 0);
      $iCodigoReduzido        = $oStdDadosReceita->k02_reduz;
      $this->iAnoUsu          = $oStdDadosReceita->k02_anousu;
      $oPlanoConta            = new ContaPlanoPCASP(null, $iAnoReceita, $iCodigoReduzido);
      $this->oContaPlanoPCASP = $oPlanoConta;
    }
  }

  /**
   * Retorna o ano da receita extra-orçamentária
   * @access public
   * @return integer
   */
  public function getAno() {
    return $this->iAnoUsu;
  }

  /**
   * Seta o ano da receita extra-orçamentária
   * @param integer $iAno
   * @access public
   */
  public function setAno($iAno) {
    $this->iAnoUsu = $iAno;
  }

  /**
   * Seta o código da receita
   * @param unknown $iCodigoReceita
   */
  public function setCodigoReceita($iCodigoReceita) {
    $this->iCodigoReceita = $iCodigoReceita;
  }

  /**
   * Retorna o código da receita
   * @return integer
   */
  public function getCodigoReceita() {
    return $this->iCodigoReceita;
  }

  /**
   * Seta a conta do plano PCASP para a receita
   * @param ContaPlanoPCASP $oPlanoConta
   */
  public function setContaPlanoPCASP(ContaPlanoPCASP $oPlanoConta) {
    $this->oContaPlanoPCASP = $oPlanoConta;
  }

  /**
   * Retorna a conta do plano PCASP associada a receita
   * @return ContaPlanoPCASP
   */
  public function getContaPlanoPCASP() {
    return $this->oContaPlanoPCASP;
  }
}
?>