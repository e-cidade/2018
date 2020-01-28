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
   * Classe repository para classes ContaPlanoPCASP
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package contabilidade
   * @subpackage planoconta
   */
  class ContaPlanoPCASPRepository {

    /**
     * Collection de ContaPlanoPCASP
     * @var array
     */
    private $aContas = array();

    /**
     * Instancia da classe
     * @var ContaPlanoPCASPRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * Retorno uma instancia do ContaPlanoPCASP pelo Codigo
     * @param integer $iCodigo Codigo do ContaPlanoPCASP
     * @return ContaPlanoPCASP
     */
    public static function getContaByCodigo($iCodigoContaPlanoPCASP, $iAno, $iCodigoReduzido = null,
                                            $iInstituicaoSessao=null) {

      $sChave = "{$iCodigoContaPlanoPCASP}{$iAno}{$iCodigoReduzido}{$iInstituicaoSessao}";
      if (!array_key_exists($sChave, ContaPlanoPCASPRepository::getInstance()->aContas)) {
        ContaPlanoPCASPRepository::getInstance()->aContas[$sChave] = new ContaPlanoPCASP($iCodigoContaPlanoPCASP,
                                                                                         $iAno,
                                                                                         $iCodigoReduzido,
                                                                                         $iInstituicaoSessao
                                                                                        );
      }
      return ContaPlanoPCASPRepository::getInstance()->aContas[$sChave];
    }

    /**
     * Retorna a instancia da classe
     * @return ContaPlanoPCASPRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {

        self::$oInstance = new ContaPlanoPCASPRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um ContaPlanoPCASP dao repositorio
     * @param ContaPlanoPCASP $oContaPlanoPCASP Instancia do ContaPlanoPCASP
     * @return boolean
     */
    public static function adicionarContaPlanoPCASP(ContaPlanoPCASP $oContaPlanoPCASP) {

      if (!array_key_exists($oContaPlanoPCASP->getCodigo(), ContaPlanoPCASPRepository::getInstance()->aContas)) {
        ContaPlanoPCASPRepository::getInstance()->aContas[$oContaPlanoPCASP->getCodigo()] = $oContaPlanoPCASP;
      }
      return true;
    }

    /**
     * Remove o ContaPlanoPCASP passado como parametro do repository
     * @param ContaPlanoPCASP $oContaPlanoPCASP
     * @return boolean
     */
    public static function removerContaPlanoPCASP(ContaPlanoPCASP $oContaPlanoPCASP) {
       /**
        *
        */
      if (array_key_exists($oContaPlanoPCASP->getCodigo(), ContaPlanoPCASPRepository::getInstance()->aContas)) {
        unset(ContaPlanoPCASPRepository::getInstance()->aContas[$oContaPlanoPCASP->getCodigo()]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalContaPlanoPCASP() {
      return count(ContaPlanoPCASPRepository::getInstance()->aContas);
    }
    
    /**
     * Retorna uma conta do plano orcament�rio atrav�s de seu estrutural
     * @param string  $sEstrutural estrutural da conta
     * @param integer $iAno
     * @return false | ContaOrcamento
     */
    public static function getContaPorEstrutural($sEstrutural, $iAno,  Instituicao $oInstituicao = null) {
    
      foreach (ContaPlanoPCASPRepository::getInstance()->aContas as $oConta) {

        if ($oConta->getEstrutural() == $sEstrutural && $oConta->getAno() == $iAno) {
          return $oConta;
        }
      }
       
      $oDaoPlano = new cl_conplano;
    
      $sWhere  = "c60_estrut      = '{$sEstrutural}'";
      $sWhere .= " and c60_anousu = {$iAno}";
      if (!empty($oInstituicao)) {
        $sWhere .= " and (c61_instit is null or c61_instit = {$oInstituicao->getSequencial()})";
      }
      $sSqlPlanoPcasp = $oDaoPlano->sql_query_geral(null, null,
                                                    'c60_codcon, c60_anousu, c61_instit, c61_reduz',
                                                    'c60_estrut',
                                                    $sWhere
                                                   );
      $rsContaPCasp = $oDaoPlano->sql_record($sSqlPlanoPcasp);
      if ($oDaoPlano->numrows > 0) {
    
        $oContaPCASP = db_utils::fieldsMemory($rsContaPCasp, 0);
        return ContaPlanoPCASPRepository::getContaByCodigo(
                                                          $oContaPCASP->c60_codcon,
                                                          $oContaPCASP->c60_anousu,
                                                          $oContaPCASP->c61_reduz,
                                                          $oContaPCASP->c61_instit
                                                         );
      }
    
      return false;
    }
  }