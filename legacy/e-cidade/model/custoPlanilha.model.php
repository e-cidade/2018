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

/**
 * Planilha de custos
 * @package Custos
 */

class custoPlanilha {
  
  /**
   * Codigo da planilha
   *
   * @var integer
   */
  protected  $iPlanilha = null;
  
  /**
   * mes da planilha
   *
   * @var integer
   */
  protected  $iMesUsu = null;
  
  /**
   * ano da planilha
   *
   * @var integer
   */
  protected  $iAnoUsu = null;
  
  protected $iSituacao = null;
  protected $sFiltros = "";
  function __construct($iMesUsu, $iAnoUsu) {

     $this->iAnoUsu = $iAnoUsu;
     $this->iMesUsu = $iMesUsu;
     $oDaoPlanilha  = db_utils::getDao("custoplanilha");
     $sWhere        = " cc15_anousu = {$this->getAnoUsu()}";
     $sWhere       .= " and cc15_mesusu = {$this->getMesusu()}";
     $sSqlPlanilha  = $oDaoPlanilha->sql_query_file(null,"*", null, $sWhere);
     $rsPlanilha    = $oDaoPlanilha->sql_record($sSqlPlanilha);
     
     if ($oDaoPlanilha->numrows  > 0) {

       $oPlanilha = db_utils::fieldsMemory($rsPlanilha, 0);
       $this->iPlanilha = $oPlanilha->cc15_sequencial;
       $this->iSituacao = $oPlanilha->cc15_situacao;
       
     }
  }
  /**
   * @return integer
   */
  public function getAnoUsu() {

    return $this->iAnoUsu;
  }
  
  /**
   * @param integer $iAnousu
   */
  public function setAnoUsu($iAnoUsu) {

    $this->iAnoUsu = $iAnoUsu;
  }
  
  /**
   * @return integer
   */
  public function getMesusu() {

    return $this->iMesUsu;
  }
  
  /**
   * @param integer $iMesusu
   */
  public function setMesUsu($iMesUsu) {

    $this->iMesUsu = $iMesUsu;
  }
  
  /**
   * @return integer
   */
  public function getPlanilha() {

    return $this->iPlanilha;
  }
  
  /**
   * Retorna a situacao da planilha 1 - Ativa 2 - Encerrada
   *
   * @return integer
   */
  public function getSituacao() {
    return $this->iSituacao;
  }
  
  /**
   * Enter description here...
   *
   * @param array $aNiveisProcessar array com os niveis a processar
   * @return custoPlanilha
   */
  function processarPlanilha($aNiveisProcessar) {
  
    if (!is_array($aNiveisProcessar) && count($aNiveisProcessar)== 0) {
      throw new Exception('Informe um nivel para processar!');
    }
    
    $this->save();
    
    foreach ($aNiveisProcessar as $oNivel) {
      
      switch ($oNivel->nivel) {
        
        case 1:
          
          require_once("model/custoPlanilhaFolha.model.php");
          $oCustosFolha  = new custoPlanilhaFolha();
          $oCustosFolha->processarDados($this->getMesusu(), $this->getAnousu())->save($this->getPlanilha());
          break;
          
        case 2:
          
          require_once("model/custoPlanilhaProvisao.model.php");
          $oCustosFolha  = new custoPlanilhaProvisao();
          $oCustosFolha->processarDados($this->getMesusu(), $this->getAnousu())->save($this->getPlanilha());
          break;
        case 3:
          
          require_once("model/custoPlanilhaConsumoAlmox.model.php");
          $oCustosAlmoxarifado = new custoPlanilhaConsumoAlmox();
          $oCustosAlmoxarifado->processarDados($this->getMesusu(), $this->getAnousu())->save($this->getPlanilha());
          
          break;
          
        case 4 :
          
          require_once("model/custoPlanilhaDiarias.model.php");
          $oCustosDiarias = new custoPlanilhaDiarias();
          $oCustosDiarias->processarDados($this->getMesusu(), $this->getAnousu())->save($this->getPlanilha());
          break;
          
        case 5 :
          
          require_once("model/custoPlanilhaServicoPessoaFisica.model.php");
          $oCustosServicoPF = new custoPlanilhaServicoPessoaFisica();
          $oCustosServicoPF->processarDados($this->getMesusu(), $this->getAnousu())->save($this->getPlanilha());
          break;

        case 6 :
          
          require_once("model/custoPlanilhaServicoPessoaJuridica.model.php");
          $oCustosServicoPJ = new custoPlanilhaServicoPessoaJuridica();
          $oCustosServicoPJ->processarDados($this->getMesusu(), $this->getAnousu())->save($this->getPlanilha());
          break;
          
        case 7 :
          
          require_once("model/custoPlanilhaOutros.model.php");
          $oCustosOutros = new custoPlanilhaOutros();
          $oCustosOutros->processarDados($this->getMesusu(), $this->getAnousu())->save($this->getPlanilha());
          break;  
      }
      
    }
    return $this;
  }
  
  /**
   * Retorna os dados dos Custos da Planilha
   *
   * @return unknown
   */
  public function getCustosPlanilha() {
    
    $sWhere = "";
    if ($this->getFiltros()  != "") {
      
      $sWhere = " and {$this->getFiltros()}"; 
    }

    $aCustos = array();
    if (empty($this->iPlanilha)) {
      return $aCustos; 	
    }
    $sSqlCustos   = "SELECT cc17_custoplanilhaorigem, ";
    $sSqlCustos  .= "       cc17_custoplanoanalitica, ";
    $sSqlCustos  .= "       cc01_descricao, ";
    $sSqlCustos  .= "       cc17_sequencial, ";
    $sSqlCustos  .= "       cc01_estrutural, ";
    $sSqlCustos  .= "       cc01_descricao, ";
    $sSqlCustos  .= "       cc14_descricao, ";
    $sSqlCustos  .= "       cc17_quantidade, ";
    $sSqlCustos  .= "       cc17_valor, ";
    $sSqlCustos  .= "       cc19_automatico, ";
    $sSqlCustos  .= "       m81_descr, ";
    $sSqlCustos  .= "       o56_codele, ";
    $sSqlCustos  .= "       o56_elemento, ";
    $sSqlCustos  .= "       o56_descr, ";
    $sSqlCustos  .= "       rh55_descr, ";
    $sSqlCustos  .= "       z01_nome, ";
    $sSqlCustos  .= "       case when cc17_custoplanilhaorigem = 3 then cast(m80_codtipo as varchar) ";
    $sSqlCustos  .= "            when cc17_custoplanilhaorigem in(4,5,6,7) ";
    $sSqlCustos  .= "            then cast(e60_codemp||'/'||e60_anousu as varchar) ";
    $sSqlCustos  .= "            when  cc17_custoplanilhaorigem in(1,2) then rh56_seqpes||' - '||rh56_localtrab end as origem, ";
    $sSqlCustos  .= "       case when pc01_descrmater is null then m60_descr else pc01_descrmater end as material ";
    $sSqlCustos  .= "  from custoplanilhaapuracao ";
    $sSqlCustos  .= "       inner join custoplanilha         on cc17_custoplanilha = cc15_sequencial ";
    $sSqlCustos  .= "       inner join custoplanilhaorigem   on cc17_custoplanilhaorigem = cc14_sequencial ";
    $sSqlCustos  .= "       inner join custoplanoanalitica on cc04_sequencial    = cc17_custoplanoanalitica ";
    $sSqlCustos  .= "       inner join custoplano          on cc04_custoplano    = cc01_sequencial ";
    $sSqlCustos  .= "       left  join custoplanilhaapuracaoelemento on cc19_custoplanilhaapuracao = cc17_sequencial ";
    $sSqlCustos  .= "       left  join orcelemento               on cc19_codele           = o56_codele ";
    $sSqlCustos  .= "                                           and cc19_anousu           = o56_anousu ";
    $sSqlCustos  .= "       left join custoplanilhacustoapropria on cc17_sequencial       = cc18_custoplanilhaapuracao ";
    $sSqlCustos  .= "       left join custoapropria              on cc18_custoapropria    = cc12_sequencial ";
    $sSqlCustos  .= "       left join matestoqueinimei           on cc12_matestoqueinimei = m82_codigo ";
    $sSqlCustos  .= "       left join matestoqueitem             on m71_codlanc           = m82_matestoqueitem ";
    $sSqlCustos  .= "       left join matestoque                 on m71_codmatestoque     = m70_codigo ";
    $sSqlCustos  .= "       left join matmater                   on m70_codmatmater       = m60_codmater ";
    $sSqlCustos  .= "       left join matestoqueini              on m82_matestoqueini     = m80_codigo ";
    $sSqlCustos  .= "       left join matestoquetipo             on m80_codtipo           = m81_codtipo ";
    $sSqlCustos  .= "       left join custoplanilhamatordemitem  on cc20_custoplanilhaapuracao = cc17_sequencial ";
    $sSqlCustos  .= "       left join matordemitemcustocriterio  on cc11_sequencial            = cc20_matordemitemcustocriterio ";
    $sSqlCustos  .= "       left join matordemitem               on cc11_matordemitem          = m52_codlanc ";
    $sSqlCustos  .= "       left join empempitem                 on m52_sequen = e62_sequen ";
    $sSqlCustos  .= "                                           and m52_numemp = e62_numemp ";
    $sSqlCustos  .= "       left join pcmater                    on e62_item   = pc01_codmater ";
    $sSqlCustos  .= "       left join empempenho                 on m52_numemp = e60_numemp ";
    $sSqlCustos  .= "       left join custoplanilhaapuracaolocaltrab on cc21_custoplanilhaapuracao = cc17_sequencial";
    $sSqlCustos  .= "       left join rhpeslocaltrab                 on cc21_rhpeslocaltrab        = rh56_seq";
    $sSqlCustos  .= "       left join rhlocaltrab                    on rh56_localtrab             = rh55_codigo";
    $sSqlCustos  .= "       left join rhpessoalmov                   on rh56_seqpes                = rh02_seqpes";
    $sSqlCustos  .= "       left join rhpessoal                      on rh02_regist                = rh01_regist";
    $sSqlCustos  .= "       left join cgm                            on rh01_numcgm                = z01_numcgm";
    $sSqlCustos  .= " where cc15_sequencial = {$this->getPlanilha()} {$sWhere}";
    $sSqlCustos  .= "order by cc01_estrutural ";
    $rsCustos     = db_query($sSqlCustos);
    $aCustos      = db_utils::getCollectionByRecord($rsCustos, false, false, true);
    $iTotalRowsCusto = pg_num_Rows($rsCustos);
    return $aCustos;
  }
  /**
   * Salva os dados Da planilha
   *
   */
  public function save() {
    
    if ($this->iPlanilha == null) {
      
      $oDaoPlanilha = db_utils::getDao("custoplanilha");
      $oDaoPlanilha->cc15_id_usuario  = db_getsession("DB_id_usuario"); 
      $oDaoPlanilha->cc15_situacao    = 1; 
      $oDaoPlanilha->cc15_anousu      = $this->getAnoUsu(); 
      $oDaoPlanilha->cc15_mesusu      = $this->getMesusu(); 
      $oDaoPlanilha->incluir(null);
      if ($oDaoPlanilha->erro_status == 0) {
        throw new Exception("aquiququiquiqiu\nNão foi possivel salvar dados da planilha de custos!\n".pg_last_error());
      }
      $this->iPlanilha = $oDaoPlanilha->cc15_sequencial;
    }
  }
  
  /**
   * Define os filtros para os gettes de consulta
   *
   * @param string $sWhere clausula Where
   * @return custoPlanilha
   */
  public function setFiltros($sWhere) {
    
    $this->sFiltros = $sWhere;
    return $this;
  }

  /**
   * Retorna os Filtros 
   *
   * @return string
   */
  public function getFiltros() {
    return $this->sFiltros;
  }
}

?>
