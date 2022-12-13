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

class AssentamentoFuncionalRepository {

  /**
   * Array com os assentamentos
   *
   * @var  array
   */
  private $aAssentamentosFuncionais = array();

  /**
   * Instancia do AssentamentoFuncional
   *
   * @var AssentamentoFuncionalRepository
   */
  private static $oInstance;

  private function __construct(){}

  private function __clone(){}

  /**
   * Retorna uma intancia do AssentamentoFuncionalRepository
   *
   * @return  AssentamentoFuncionalRepository
   */
  protected static function getInstance() {

    if (self::$oInstance === null) {
      self::$oInstance = new AssentamentoFuncionalRepository();
    }

    return self::$oInstance;
  }

  /**
   * Monta um objeto do tipo AssentamentoFunciona a partir do código
   * do assentamento informado por parâmetro
   *
   * @param  integer  $iCodigoAssentamentoFuncional  Código do Assentamento
   * @return AssentamentoFuncional
   */
  public static function make($iCodigoAssentamentoFuncional) {

    $oDaoAssentamentoFuncional = new cl_assentamentofuncional;
    $sSqlAssentamentoFuncional = $oDaoAssentamentoFuncional->sql_query($iCodigoAssentamentoFuncional);
    $rsAssentamentoFuncional   = db_query($sSqlAssentamentoFuncional);

    if(!$rsAssentamentoFuncional) {
      throw new DBException("Erro buscar dados na tabela assentamentofuncional.");
    }

    if(pg_num_rows($rsAssentamentoFuncional) == 0) {
      throw new BusinessException("Assentamento de vida funcional não encontrado.");
    }

    $oAssentamentoFuncional      = new AssentamentoFuncional($iCodigoAssentamentoFuncional);

    if(pg_num_rows($rsAssentamentoFuncional) > 0) {

      $oStdAssentamentoFuncional = db_utils::fieldsMemory($rsAssentamentoFuncional, 0);

      if(!empty($oStdAssentamentoFuncional->rh193_assentamento_efetividade)) {
        $oAssentamento = new Assentamento($oStdAssentamentoFuncional->rh193_assentamento_efetividade);
        $oAssentamentoFuncional->setAssentamentoEfetividade($oAssentamento);
      }

      $oAssentamentoFuncional->setCodigo          ($oStdAssentamentoFuncional->h16_codigo);
      $oAssentamentoFuncional->setMatricula       ($oStdAssentamentoFuncional->h16_regist);
      $oAssentamentoFuncional->setTipoAssentamento($oStdAssentamentoFuncional->h16_assent);
      $oAssentamentoFuncional->setHistorico       ($oStdAssentamentoFuncional->h16_histor);
      $oAssentamentoFuncional->setCodigoPortaria  ($oStdAssentamentoFuncional->h16_nrport);
      $oAssentamentoFuncional->setDescricaoAto    ($oStdAssentamentoFuncional->h16_atofic);
      $oAssentamentoFuncional->setDias            ($oStdAssentamentoFuncional->h16_quant);
      $oAssentamentoFuncional->setPercentual      ($oStdAssentamentoFuncional->h16_perc);
      $oAssentamentoFuncional->setSegundoHistorico($oStdAssentamentoFuncional->h16_hist2);
      $oAssentamentoFuncional->setLoginUsuario    ($oStdAssentamentoFuncional->h16_login);
      $oAssentamentoFuncional->setDataLancamento  ($oStdAssentamentoFuncional->h16_dtlanc);
      $oAssentamentoFuncional->setConvertido      ($oStdAssentamentoFuncional->h16_conver);
      $oAssentamentoFuncional->setAnoPortaria     ($oStdAssentamentoFuncional->h16_anoato);
    }

    return $oAssentamentoFuncional;
  }

  /**
   * Adiciona um AssentamentoFuncional ao array de AssentamentosFuncionais
   *
   * @param  AssentamentoFuncional  $oAssentamentoFuncional
   */
  public static function adicionar(AssentamentoFuncional $oAssentamentoFuncional) {
    self::getInstance()->aAssentamentosFuncionais[$oAssentamentoFuncional->getCodigoAssentamentoFuncional()] = $oAssentamentoFuncional;
  }

  /**
   * Retorna um objeto AssentamentoFuncional a partir de um código informado
   *
   * @param Integer $iCodigoAssentamentoFuncional
   */
  public static function getInstanciaPorCodigo($iCodigoAssentamentoFuncional) {

    if(!array_key_exists($iCodigoAssentamentoFuncional, self::getInstance()->aAssentamentosFuncionais)) {
      self::adicionar(self::make($iCodigoAssentamentoFuncional));
    }

    return self::getInstance()->aAssentamentosFuncionais[$iCodigoAssentamentoFuncional];
  }

  /**
   * Retorna os assentamentos de efetividade
   *
   * @param Tipoassentamento $oTipoAssentamento
   * @param DBDate|null $oDataInicio
   *
   * @return array
   *
   * @throws BusinessException
   * @throws DBException
   */
  public static function getAssentamentosEfetividadePorTipo(Tipoassentamento $oTipoAssentamento, DBDate $oDataInicio = null) {

    if(empty($oTipoAssentamento)) {
      throw new BusinessException("Tipo de assentamento não informado e/ou inválido.");
    }

    $iTipoAssentamento         = $oTipoAssentamento->getSequencial();
    $oDaoAssentamentoFuncional = new cl_assentamentofuncional;
    $sWhereAssentamento        = "     assentamentofuncional.rh193_assentamento_funcional is null";
    $sWhereAssentamento       .= " and assentamentofuncional.rh193_assentamento_efetividade is null";
    $sWhereAssentamento       .= " and h16_assent = {$iTipoAssentamento}";
    $sWhereAssentamento       .= " and h16_regist in (select distinct rh02_regist 
                                                        from rhpessoalmov 
                                                  inner join rhpeslocaltrab on rh56_seqpes = rh02_seqpes 
                                                       where rh02_anousu = ". DBPessoal::getAnoFolha() ."
                                                         and rh02_mesusu = ". DBPessoal::getMesFolha() ."
                                                         and rh02_lota in (select distinct rh157_lotacao 
                                                                                      from db_usuariosrhlota
                                                                                     where rh157_usuario = ". db_getsession("DB_id_usuario") ."))";
    $sJoinAssentamento         = " RIGHT JOIN assenta on rh193_assentamento_funcional = h16_codigo or rh193_assentamento_efetividade = h16_codigo";

    if(!empty($oDataInicio)) {
      $sWhereAssentamento       .= " and h16_dtconc >= '".$oDataInicio->getDate() ."'";
    }

    $sSqlAssentamento          = $oDaoAssentamentoFuncional->sql_query(null, " h16_codigo", " h16_dtconc desc", $sWhereAssentamento, $sJoinAssentamento);
    $rsAssentamento            = db_query($sSqlAssentamento);


    if(!$rsAssentamento) {
      throw new DBException("Erro ao buscar assentamentos.");
    }

    $aAssentamentosEfetividade = array();

    if(pg_num_rows($rsAssentamento) > 0) {

      for ($iIndAssentamentos=0; $iIndAssentamentos < pg_num_rows($rsAssentamento); $iIndAssentamentos++) {

        $oAssentamento               = new Assentamento(db_utils::fieldsMemory($rsAssentamento, $iIndAssentamentos)->h16_codigo);
        $aAssentamentosEfetividade[] = $oAssentamento;
      }
    }

    return $aAssentamentosEfetividade;
  }

  public static function getAssentamentosFuncional($iCodigoAssentamento) {

    $oDaoAssentamentoFuncional = new cl_assentamentofuncional;
    $sWhereAssentamento        = "rh193_assentamento_efetividade = {$iCodigoAssentamento}";
    $sSqlAssentamento          = $oDaoAssentamentoFuncional->sql_query(null, " rh193_assentamento_funcional", null, $sWhereAssentamento);
    $rsAssentamento            = db_query($sSqlAssentamento);


    if(!$rsAssentamento) {
      throw new DBException("Erro ao buscar assentamentos.");
    }

    $aAssentamentosEfetividade = array();

    if(pg_num_rows($rsAssentamento) > 0) {

      for ($iIndAssentamentos=0; $iIndAssentamentos < pg_num_rows($rsAssentamento); $iIndAssentamentos++) {

        $oAssentamento               = new Assentamento(db_utils::fieldsMemory($rsAssentamento, $iIndAssentamentos)->rh193_assentamento_funcional);
        $aAssentamentosEfetividade[] = $oAssentamento;
      }
    }

    return $aAssentamentosEfetividade;
  }

  public static function persist(AssentamentoFuncional $oAssentamentoFuncional) {

    $oDaoAssentamentoFuncional = new cl_assentamentofuncional();
    $oDaoAssentamentoFuncional->rh193_assentamento_efetividade = 'null';

    /**
     * Pega o código do assentamento de efetividade que gerou o assentamento de vida funcional,
     * para salvar na tabela de vinculação de assentamentos de vida funcional
     */
    if($oAssentamentoFuncional->getAssentamentoEfetividade() instanceof Assentamento) {

      $oAssentamentoEfetividade       = $oAssentamentoFuncional->getAssentamentoEfetividade();
      $iCodigoAssentamentoEfetividade = $oAssentamentoEfetividade->getCodigo();

      if(!empty($iCodigoAssentamentoEfetividade)) {
        $oDaoAssentamentoFuncional->rh193_assentamento_efetividade = $iCodigoAssentamentoEfetividade;
      }
    }

    /**
     * Persiste um novo assentamento na tabela assenta e espera o retorno, que pode ser uma
     * mensagem de erro ou uma instância de assentamento no caso de sucesso.
     * Em caso de sucesso seta o códio do assentamento para utilizar posteriormente na tabela
     * assntamententofuncional
     */
    $mResponse                 = $oAssentamentoFuncional->persist();

    if($mResponse instanceof Assentamento) {

      $oAssentamento = $mResponse;
      $oAssentamentoFuncional->setCodigo($oAssentamento->getCodigo());

    } else {
      throw new DBException($mResponse);
    }

    /**
     * Pega o código do assentamento criado para setar um novo registro na tabela assentamento funcional
     */
    $oDaoAssentamentoFuncional->rh193_assentamento_funcional = $oAssentamentoFuncional->getCodigo();
    $iCodigoAssentamentoFuncional = $oAssentamentoFuncional->getCodigoAssentamentoFuncional();

    if(empty($iCodigoAssentamentoFuncional)) {
      $oDaoAssentamentoFuncional->incluir($oAssentamentoFuncional->getCodigo());
    } else {
      $oDaoAssentamentoFuncional->alterar($iCodigoAssentamentoFuncional);
    }

    if ($oDaoAssentamentoFuncional->erro_status == "0") {
      throw new DBException("Erro ao salvar Assentamento Funcional");
    }

    /**
     * Retorna a instancia do assentamento funcional salvo
     */
    $oAssentamentoFuncional->setCodigoAssentamentoFuncional($oDaoAssentamentoFuncional->rh193_assentamento_funcional);

    return $oAssentamentoFuncional;
  }

  public static function adicionarAsssentamento($aAssentamentosFuncional) {

    foreach ($aAssentamentosFuncional as $oAssentamento) {

      AssentamentoRepository::persist($oAssentamento);
      $iCodigoNovoAssentamento = $oAssentamento->getCodigo();
      $oAssentamentoFuncional  = new AssentamentoFuncional($iCodigoNovoAssentamento);
      AssentamentoFuncionalRepository::persist($oAssentamentoFuncional);
    }

    return true;
  }
}