<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * @desc Livros de Certidoes de divida ativa
 * @version $Revision: 1.6 $
 */
class cdaLivro {
  
  /**
   * Numero do livro
   *
   * @var integer
   */
  protected $iNumeroLivro    = null;
  
  /**
   * Codigo da instituicao
   *
   * @var integer
   */
  protected $iInstituicao    = null;
  
  /**
   * codigo sequencial do livro
   *
   * @var integer
   */
  protected $iCodigoLivro    = null;
  
  /**
   * Objeto dao do livro
   *
   * @var cl_certidlivro
   */
  protected $oDaoCertidLivro = null;
  
  /**
   * Numero de Folhas do Livro
   * @var integer
   */
  protected  $iNumeroFolhas = 0;
  
  /**
   * Numero de CDA por página
   *
   */
  
  const CDAPORPAGINA = 30;
  
  protected $dtLivro  = null;
  /**
   * Livros de Certidoes de divida ativa
   *
   * @param instituicao $iInstit código da instituicao
   * @param integer $numeroLivro numero do livro
   */
  public function __construct($iInstit, $numeroLivro) {

     require_once("classes/db_certidlivro_classe.php");
     $this->oDaoCertidLivro = new cl_certidlivro;
     $this->iInstituicao    = $iInstit;
     
     /**
      * Verificamos se o livro existe para a instituição.
      * senao existir criamos um novo, ao processar os dados a primeira vez.
      */
     $sWhere     = "v25_instit      = ".$this->getInstituicao();
     $sWhere    .= " and v25_numero = {$numeroLivro}";  
     $sSqlLivro  = $this->oDaoCertidLivro->sql_query(null,"*",null, $sWhere);
     $rsLivro    = $this->oDaoCertidLivro->sql_record($sSqlLivro);
     if ($this->oDaoCertidLivro->numrows == 1) {
       
        $oLivroCDA          = db_utils::fieldsMemory($rsLivro, 0);
        $this->iCodigoLivro = $oLivroCDA->v25_sequencial;
        $this->iNumeroLivro = $oLivroCDA->v25_numero;
        $this->dtLivro      = $oLivroCDA->v25_datainc;
              
     } else {
       $this->iNumeroLivro = $numeroLivro;
     }
  }

  /**
   * Retorna o codigo sequencial do livro
   * @return integer
   */
  public function getCodigoLivro() {
    return $this->iCodigoLivro;
  }
  
  /**
   * Retorna a Instituição do livro
   * @return integer
   */
  public function getInstituicao() {
    return $this->iInstituicao;
  }
  
  /**
   * @desc Retorna o numero do livro
   * @return integer
   */
  public function getNumeroLivro() {
    return $this->iNumeroLivro;
  }
  
  /**
   * Retorna a data de emissao do livro
   *
   * @return string data de emissao
   */
  public function getDataEmissao() {
    return $this->dtLivro;
  }
  /**
   * Retorna a proxima página a ser utilizada
   *
   * @return integer numero da página
   */
  public function getProximaPagina() {
    
    if ($this->getCodigoLivro() == null) {
      $iProximaPagina = 1;   
    } else {

      $iProximaPagina     = 1;  
      $sWhere             = "v26_certidlivro = ".$this->getCodigoLivro();
      $sWhere            .= "group by v26_numerofolha";
      require_once('classes/db_certidlivrofolha_classe.php');
      $oDaoCDALivroFolha  = new cl_certidlivrofolha();
      $sSqlProximaFolha   = $oDaoCDALivroFolha->sql_query_file(null,
                                                              "count(*) as total_cdas, 
                                                               coalesce(max(v26_numerofolha),1) as proximapagina",
                                                               null,
                                                               $sWhere
                                                              );
      $sSqlProximaFolha  = " select coalesce(max(proximapagina),1) as pagina from ({$sSqlProximaFolha}) as x";                                                       
      $sSqlProximaFolha .= " where total_cdas < ".self::CDAPORPAGINA;                                                       
      $rsProximaFolha    = $oDaoCDALivroFolha->sql_record($sSqlProximaFolha);
      if ($oDaoCDALivroFolha->numrows > 0) {
         $iProximaPagina = db_utils::fieldsMemory($rsProximaFolha, 0)->pagina;                                                                 
      }
    }
    
    return $iProximaPagina;
  }
  
  /**
   * Adiciona as CDAS ao livro
   *
   * @param mixed $aCDA cda, ou cdas a serem adicionadas
   */
  public function addCDA($aCDA) {
    
    if (!db_utils::inTransaction()) {
      throw new Exception('Não existe transação ativa com o banco de dados.');
    }
    
    if (!is_array($aCDA) || count($aCDA) == 0) {
      throw new Exception("CDA's nao informadas");
    }
    
    $oDaoCertidLivrofolha = db_utils::getDao("certidlivrofolha");
    $iNumeroFolha         = $this->getProximaPagina();
    $iTotalCDA            = $this->getTotalCdaPorPagina($iNumeroFolha)+1;
    foreach ($aCDA as $iCDA) {

      $oDaoCertidLivrofolha->v26_certid       = $iCDA->v13_certid;
      $oDaoCertidLivrofolha->v26_certidlivro  = $this->getCodigoLivro();
      $oDaoCertidLivrofolha->v26_numerofolha  = $iNumeroFolha;
      $oDaoCertidLivrofolha->incluir(null);
      if ($oDaoCertidLivrofolha->erro_status == 0) {
        throw new Exception("Erro ao adicionar CDA {$iCDA->v13_certid} ao livro ({$this->iNumeroLivro})!\n{$oDaoCertidLivrofolha->erro_msg}");
      }
     if ($iTotalCDA >= self::CDAPORPAGINA) {
        $iNumeroFolha++;
        $iTotalCDA = 0; 
      } 
      $iTotalCDA++;
    }
    return true;
  }
  
     
  public function processaLivro($oParams) {
     
    
    if (!isset($oParams->tipo) || $oParams->tipo == "") {
      throw new Exception('Tipo do livro de Divida nao Informado!');
    }
    
    if (!isset($oParams->tipolivro) || $oParams->tipolivro == "") {
      throw new Exception('Tipo de processamento do livro de Divida nao Informado!');
    }
    
    if (!db_utils::inTransaction()) {
      throw new Exception('Não existe transação ativa com o banco de dados.');
    }
    
    /**
     * Verificamos o tipo de Certidao, e criamos o where conforme o tipo da mesma
     * 1 - CDAs de divida/parcelamento (padrao) 
     * 2 - CDAs de divida 
     * 3 - CDAs de parcelamento 
     */
    
    $sWhere = "";
    switch ($oParams->tipo) {
      
      case 2:
        
        $sWhere .= " certdiv.v14_certid is not null";
        break;

      case 3:
        
        $sWhere .= " certter.v14_certid is not null";
        break;  
      
    }
    
    /**
     * Verificamos se o usuario digitou filros de CDA/Data
     */
    if (isset($oParams->v14_inicial) && $oParams->v14_inicial != "") {
      
      $sWhere .= $sWhere != ""?" and ":"";
      if (isset($oParams->v14_final) && $oParams->v14_final != "") {
        $sWhere  .= " v13_certid between {$oParams->v14_inicial} and {$oParams->v14_final}";
      } else {
        $sWhere  .= " v13_certid  =  {$oParams->v14_inicial}";
      }
    }
    
   if (isset($oParams->datainicial) && $oParams->datainicial != "") {
      
      $sWhere .= $sWhere != ""?" and ":"";
      $oParams->datainicial = implode("-",array_reverse(explode("/", $oParams->datainicial)));
      if (isset($oParams->datafinal) && $oParams->datafinal != "") {
        
        $oParams->datafinal = implode("-",array_reverse(explode("/", $oParams->datafinal)));
        $sWhere  .= " v13_dtemis between '{$oParams->datainicial}' and '{$oParams->datafinal}'";
      } else {
        $sWhere  .= " v13_dtemis =  '{$oParams->datainicial}'";
      }
    }
    
    $sWhere  .= $sWhere != ""?" and ":"";
    $sWhere  .= " v26_certid is null";
    $sWhere  .= "  and (certter.v14_certid is not null or certdiv.v14_certid is not null)";  
    $sSqlCDA  = $this->oDaoCertidLivro->sql_query_livro(null,"distinct v13_certid", "v13_certid", $sWhere);
    $rsCda    = $this->oDaoCertidLivro->sql_record($sSqlCDA);
    $aCDAs    = db_utils::getColectionByRecord($rsCda);
    if (count($aCDAs) == 0) {
       throw new Exception("Não Existem CDA's para adicionar ao Livro {$this->iNumeroLivro}.");
    }
    
    /**
     * incluimos o livro
     */
    if ($this->getCodigoLivro() == null) {
      
      $this->oDaoCertidLivro->v25_datainc    = date("Y-m-d", db_getsession("DB_datausu"));
      $this->oDaoCertidLivro->v25_usuario    = db_getsession("DB_instit");     
      $this->oDaoCertidLivro->v25_instit     = $this->getInstituicao();     
      $this->oDaoCertidLivro->v25_hora       = db_hora();
      $this->oDaoCertidLivro->v25_numero     = $this->getNumeroLivro();
      $this->oDaoCertidLivro->v25_tipolivro  = $oParams->tipo;
      $this->oDaoCertidLivro->incluir(null);
      if ($this->oDaoCertidLivro->erro_status == 0) {
        throw new Exception("Erro ao processar livro ({$this->iNumeroLivro})!\n{$this->oDaoCertidLivro->erro_msg}");
      }
      $this->iCodigoLivro = $this->oDaoCertidLivro->v25_sequencial;
    }
    /**
     * Adicionamos as CDA
     */
    $this->addCDA($aCDAs);
  }
  
  public function getTotalCdaPorPagina($iNumeroPagina) {
    
    require_once('classes/db_certidlivrofolha_classe.php');
    $oDaoCDALivroFolha  = new cl_certidlivrofolha();
    $sSqlTotalCDA       = $oDaoCDALivroFolha->sql_query_file(null,
                                                             "coalesce(count(*),0) as total_cdas",
                                                             null,
                                                             "v26_certidlivro = {$this->iCodigoLivro}
                                                              and v26_numerofolha = {$iNumeroPagina}"
                                                            ); 
    $rsTotalCDA = $oDaoCDALivroFolha->sql_record($sSqlTotalCDA);
    $iTotaLCDA  = db_utils::fieldsMemory($rsTotalCDA, 0)->total_cdas;
     
    if ($iTotaLCDA == 0) {
      $iTotaLCDA = 0;
    }
    return $iTotaLCDA;
                                                                
  }
  
  /**
   * Traz as cdas que compoem o livro
   *
   * @return array  cdas do livro
   */
  public function getCDA() {
        
   $oDadosInstit    = db_stdClass::getDadosInstit(); 
   $iRegraIPTU      = $oDadosInstit->db21_regracgmiptu;
   $iRegraISS       = $oDadosInstit->db21_regracgmiss;
   
   $sSqlDadosLivro  = "select v13_certid as certidao, ";
   $sSqlDadosLivro .= "    v13_dtemis  as dtemissao, ";
   $sSqlDadosLivro .= "    v26_numerofolha as numerofolha, ";
   $sSqlDadosLivro .= "    round(sum(v14_vlrhis), 2) as vlrhis, ";
   $sSqlDadosLivro .= "    round(sum(v14_vlrcor), 2) as vlrcor, ";
   $sSqlDadosLivro .= "    round(sum(v14_vlrmul), 2) as vlrmul, ";
   $sSqlDadosLivro .= "    round(sum(v14_vlrjur), 2) as vlrjur, ";
   $sSqlDadosLivro .= "    origem, ";
   $sSqlDadosLivro .= "    (select rvNome||'|'||coalesce(rinumcgm,0)||'|'||coalesce(rimatric,0)||'|'||coalesce(riinscr,0)";
   $sSqlDadosLivro .= "      from fc_socio_promitente(numpre,true,{$iRegraIPTU},{$iRegraISS}) limit 1) as nome ";
   $sSqlDadosLivro .= "from ( ";
   $sSqlDadosLivro .= "    SELECT v13_certid,  ";
   $sSqlDadosLivro .= "           v13_dtemis, ";
   $sSqlDadosLivro .= "           v26_numerofolha, ";
   $sSqlDadosLivro .= "           (case when certter.v14_certid is null  ";
   $sSqlDadosLivro .= "                 then '1'  else '2' end) as origem, ";
   $sSqlDadosLivro .= "           (case when certter.v14_certid is null  ";
   $sSqlDadosLivro .= "                 then certdiv.v14_vlrhis else certter.v14_vlrhis end) as v14_vlrhis, ";
   $sSqlDadosLivro .= "           (case when certter.v14_certid is null   ";
   $sSqlDadosLivro .= "                 then certdiv.v14_vlrcor else certter.v14_vlrcor end) as v14_vlrcor, ";
   $sSqlDadosLivro .= "           (case when certter.v14_certid is null   ";
   $sSqlDadosLivro .= "                 then certdiv.v14_vlrjur else certter.v14_vlrjur end) as v14_vlrjur, ";
   $sSqlDadosLivro .= "           (case when certter.v14_certid is null  ";
   $sSqlDadosLivro .= "                 then certdiv.v14_vlrmul else certter.v14_vlrmul end) as v14_vlrmul, ";
   $sSqlDadosLivro .= "           (case when certter.v14_certid is null ";
   $sSqlDadosLivro .= "                 then divida.v01_numpre else termo.v07_numpre end ) as numpre";
   $sSqlDadosLivro .= "     from certid ";
   $sSqlDadosLivro .= "          left join certdiv            on certdiv.v14_certid = v13_certid ";
   $sSqlDadosLivro .= "          left join divida             on v01_coddiv          = v14_coddiv ";
   $sSqlDadosLivro .= "          left join certter            on certter.v14_certid = v13_certid ";
   $sSqlDadosLivro .= "          left join termo              on certter.v14_parcel = v07_parcel ";
   $sSqlDadosLivro .= "          left join certidlivrofolha   on v13_certid         =  v26_certid ";
   $sSqlDadosLivro .= "    where certidlivrofolha.v26_certidlivro = {$this->iCodigoLivro}";
   $sSqlDadosLivro .= "      and (certter.v14_certid is not null or certdiv.v14_certid is not null)";
   $sSqlDadosLivro .= " ) as x ";
   $sSqlDadosLivro .= " group by v13_certid, ";
   $sSqlDadosLivro .= "          v13_dtemis, ";
   $sSqlDadosLivro .= "          v26_numerofolha,";
   $sSqlDadosLivro .= "          origem,";
   $sSqlDadosLivro .= "          nome ";
   $sSqlDadosLivro .= " order by v26_numerofolha, v13_certid";
   $rsDadosLivro    = $this->oDaoCertidLivro->sql_record($sSqlDadosLivro);
   $aDadosLivro     = array();
   if ($this->oDaoCertidLivro->numrows > 0) {

      for ($i = 0; $i < $this->oDaoCertidLivro->numrows; $i++) {

        $oDadosCda = db_utils::fieldsMemory($rsDadosLivro, $i);
        $aOrigem   = explode("|", $oDadosCda->nome);
        if ($aOrigem[2] != 0) {
          
          $oDadosCda->origemdebito     = "Matrícula {$aOrigem[2]}";     
          $oDadosCda->tipoorigemdebito = 2;
               
        } else if ($aOrigem[3] != 0) {
          
          $oDadosCda->origemdebito     = "Inscrição {$aOrigem[3]}";
          $oDadosCda->tipoorigemdebito = 3;
          
        } else {
          
          $oDadosCda->origemdebito     = "Numcgm {$aOrigem[1]}";
          $oDadosCda->tipoorigemdebito = 1;
          
        }
        $oDadosCda->nome       = $aOrigem[0];
        $oDadosCda->valortotal = $oDadosCda->vlrcor+$oDadosCda->vlrmul+$oDadosCda->vlrjur;
        $aDadosLivro[]         = $oDadosCda;
      }
    }
    return $aDadosLivro;
  }
  
  /**
   * Retornas as Dividas/Parcelamentos da CDA
   *
   * @param integer $iCda número da CDA
   */
  public function detalhaCDA ($iCda) {
    
    $aItensCDa     = array();
    $sSqlDadosCda  = "    SELECT v13_certid, ";
    $sSqlDadosCda .= "           v13_dtemis, ";
    $sSqlDadosCda .= "           v01_proced, ";
    $sSqlDadosCda .= "           v01_dtinsc, ";
    $sSqlDadosCda .= "           v07_dtlanc, ";
    $sSqlDadosCda .= "           v03_descr , ";
    $sSqlDadosCda .= "           v01_exerc , ";
    $sSqlDadosCda .= "           (case when certter.v14_certid is null  ";
    $sSqlDadosCda .= "                 then v01_numpar else v07_totpar end) as parcela,";
    $sSqlDadosCda .= "           (case when certter.v14_certid is null  ";
    $sSqlDadosCda .= "                 then v01_dtvenc else v07_dtvenc end) as dtvenc,";
    $sSqlDadosCda .= "           (case when certter.v14_certid is null  ";
    $sSqlDadosCda .= "                 then v01_coddiv else v07_parcel end) as codigo,";
    $sSqlDadosCda .= "           (case when certter.v14_certid is null  ";
    $sSqlDadosCda .= "                 then '1'  else '2' end) as origem, ";
    $sSqlDadosCda .= "           (case when certter.v14_certid is null  ";
    $sSqlDadosCda .= "                 then certdiv.v14_vlrhis::float8 else certter.v14_vlrhis::float8 end)::float8 as vlrhis, ";
    $sSqlDadosCda .= "           (case when certter.v14_certid is null   ";
    $sSqlDadosCda .= "                 then certdiv.v14_vlrcor else certter.v14_vlrcor end) as vlrcor, ";
    $sSqlDadosCda .= "           (case when certter.v14_certid is null   ";
    $sSqlDadosCda .= "                 then certdiv.v14_vlrjur else certter.v14_vlrjur end) as vlrjur, ";
    $sSqlDadosCda .= "           (case when certter.v14_certid is null  ";
    $sSqlDadosCda .= "                 then certdiv.v14_vlrmul else certter.v14_vlrmul end) as vlrmul, ";
    $sSqlDadosCda .= "           (case when certter.v14_certid is null ";
    $sSqlDadosCda .= "                 then divida.v01_numpre else termo.v07_numpre end ) as numpre";
    $sSqlDadosCda .= "     from certid ";
    $sSqlDadosCda .= "          left join certdiv            on certdiv.v14_certid = v13_certid ";
    $sSqlDadosCda .= "          left join divida             on v01_coddiv         = v14_coddiv ";
    $sSqlDadosCda .= "          left join proced             on v01_proced         = v03_codigo ";
    $sSqlDadosCda .= "          left join certter            on certter.v14_certid = v13_certid ";
    $sSqlDadosCda .= "          left join termo              on certter.v14_parcel = v07_parcel ";
    $sSqlDadosCda .= "    where V13_certid = {$iCda}";
    $sSqlDadosCda .= "    order by 7";
    $rsDadosCDA    = $this->oDaoCertidLivro->sql_record($sSqlDadosCda);
    if ($this->oDaoCertidLivro->numrows > 0) {
      
      for ($i = 0; $i < $this->oDaoCertidLivro->numrows; $i++) {
        
        $oDadosCda = db_utils::fieldsMemory($rsDadosCDA, $i);
        $oDadosCda->valortotal = $oDadosCda->vlrcor+$oDadosCda->vlrmul+$oDadosCda->vlrjur;   
        $aItensCDa[] = $oDadosCda;
      }
    }
    return $aItensCDa;
  }
}

?>