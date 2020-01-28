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
 * classe para processamento da DIRF
 * @package Pessoal
 * @subpackage dirf
 *
 */

class Dirf {
  
  protected $iAno;
  protected $iMes;
  protected $sCnpj;
  protected $sMatriculas;
  protected $iCodigoDirf;
  protected $iInstituicao;
  protected $aInconsistentes = array();
  protected $nValorLimite;
  protected $sCodigoArquivo;
  protected $iCodigoLayout;
  protected $aCredores;
  protected $aDesdobramentos;
  
  /**
   * 
   */
  function __construct($iAno,  $sCnpj) {
    
    $this->iAno        = $iAno;
    $sSqlMes           = "select lpad(max(r11_mesusu),2,0) as mesusu 
                            from cfpess 
                           where r11_instit = ".db_getsession("DB_instit")." 
                             and r11_anousu = {$this->iAno}";
    $rsMesUsu          = db_query($sSqlMes);
    $this->iMes        = db_utils::fieldsMemory($rsMesUsu,0)->mesusu;
    $this->sCnpj       = $sCnpj;
    $this->sMatriculas = "";
    $_SESSION["ignoreAccount"] = true;
  }
  
  /**
   * Seta matriculas selecionadas na geração da dirf.
   * @return $this->sMatriculas
   */
  public function getMatriculas() {
    return $this->sMatriculas;
  }
  
  /**
   * Retorna matriculas selecionadas na geração da dirf.
   * @param string_type $sMatriculas
   */
  public function setMatriculas($sMatriculas) {
    $this->sMatriculas = $sMatriculas;
  }

  /**
   * Define valor limite 
   * 
   * @param float $nValorLimite 
   * @access public
   * @return void
   */
  public function setValorLimite($nValorLimite) {
    $this->nValorLimite = $nValorLimite;
  }

  /**
   * Retorna valor limite 
   * 
   * @access public
   * @return float
   */
  public function getValorLimite() {
    return $this->nValorLimite;
  }

  /**
   * Define codigo do arquivo 
   * 
   * @param string $sCodigoArquivo 
   * @access public
   * @return void
   */
  public function setCodigoArquivo($sCodigoArquivo) {
    $this->sCodigoArquivo = $sCodigoArquivo;
  }

  /**
   * Retorna codigo do arquivo 
   * 
   * @access public
   * @return string
   */
  public function getCodigoArquivo() {
    return $this->sCodigoArquivo;
  }

  /**
   * Define codigo do layout 
   * 
   * @param integer $iCodigoLayout 
   * @access public
   * @return void
   */
  public function setCodigoLayout($iCodigoLayout) {
    $this->iCodigoLayout = $iCodigoLayout; 
  }

  /**
   * Retorna codigo do layout 
   * 
   * @access public
   * @return integer
   */
  public function getCodigoLayout() {
    return $this->iCodigoLayout; 
  }

  /**
   * Define os credores 
   * 
   * @param Array $aCredores 
   * @access public
   * @return void
   */
  public function setCredores(Array $aCredores) {
    $this->aCredores = $aCredores; 
  }
  
  /**
   * Retorna array com os credores 
   * 
   * @access public
   * @return Array
   */
  public function getCredores() {
    return $this->aCredores; 
  }

  /**
   * Define os desdobramentos 
   * 
   * @param Array $aDesdobramentos 
   * @access public
   * @return void
   */
  public function setDesdobramentos(Array $aDesdobramentos) {
    $this->aDesdobramentos = $aDesdobramentos; 
  }

  /**
   * Retorna array com os desdobramentos 
   * 
   * @access public
   * @return array
   */
  public function getDesdobramentos() {
    return $this->aDesdobramentos; 
  }
  
  /**
   * Gera o processamento dos dados para a emissao dos relatorios  e arquivo da Dirf
   *
   * @param boolean $lProcessarEmpenhos rotina deve processar os pagamentos de PF e PJ
   */ 
  public function processar($lProcessarEmpenhos=true) {
    
    $oDaoRhDirfGeracao           = db_utils::getDao("rhdirfgeracao");
    $oDaoRhDirfDadosPessoal      = db_utils::getDao("rhdirfgeracaodadospessoal");
    $oDaoRhDirfDadosPessoalValor = db_utils::getDao("rhdirfgeracaodadospessoalvalor");

    /**
     *  Limpa propriedade de Inconsistentes
     */
    $this->clearInconsistente();
    
    $sWhere               = "rh95_ano               = {$this->iAno} "; 
    $sWhere              .= "and rh95_fontepagadora = '{$this->sCnpj}' "; 
    $sSqlVerificaGeracao  = $oDaoRhDirfGeracao->sql_query_file(null, "*", null, $sWhere);
    $rsVerificacaoGeracao = $oDaoRhDirfGeracao->sql_record($sSqlVerificaGeracao);

    if ($oDaoRhDirfGeracao->numrows > 0) {
      
      $iNumRowsDirf = $oDaoRhDirfGeracao->numrows;
      for ($i = 0; $i < $iNumRowsDirf; $i++) {
                
        $oDirf            = db_utils::fieldsMemory($rsVerificacaoGeracao, $i); 
        $sWhereDados      = "rh96_rhdirfgeracao = {$oDirf->rh95_sequencial}";
        $sSqlDadosPessoal = $oDaoRhDirfDadosPessoal->sql_query_file(null, "*", null, $sWhereDados);
        $rsDadosPessoal   = $oDaoRhDirfDadosPessoal->sql_record($sSqlDadosPessoal);
        $iNumRowsDadosPessoal = $oDaoRhDirfDadosPessoal->numrows;
        /**
         * deletamos da tabela que liga as matriculas ao valor
         */
        $sDeleteMatriculasDirf  = "delete from rhdirfgeracaopessoalregist ";
        $sDeleteMatriculasDirf .= " using rhdirfgeracaodadospessoalvalor,rhdirfgeracaodadospessoal  ";
        $sDeleteMatriculasDirf .= " where rh99_rhdirfgeracaodadospessoalvalor = rh98_sequencial ";
        $sDeleteMatriculasDirf .= "   and rh98_rhdirfgeracaodadospessoal = rh96_sequencial";
        $sDeleteMatriculasDirf .= "   and rh96_rhdirfgeracao={$oDirf->rh95_sequencial}";
        $rsDeleteMatriculas     = db_query($sDeleteMatriculasDirf);
        if (!$rsDeleteMatriculas) {
         throw new Exception("Erro[34] - Erro ao excluir valores da DIRF.\n".pg_last_error());
        }
        
        
        $sDeleteValoresDirf   = "delete from rhdirfgeracaodadospessoalvalor ";
        $sDeleteValoresDirf  .= " using rhdirfgeracaodadospessoal  ";
        $sDeleteValoresDirf  .= " where rh98_rhdirfgeracaodadospessoal = rh96_sequencial";
        $sDeleteValoresDirf  .= "   and rh96_rhdirfgeracao={$oDirf->rh95_sequencial}";
        $rsDeleteValoresDirf  = db_query($sDeleteValoresDirf);
        if (!$rsDeleteValoresDirf) {
         throw new Exception("Erro[1] - Erro ao excluir valores da DIRF.\n".pg_last_error());
        }
        
         $oDaoRhDirfDadosPessoal->excluir(null, "rh96_rhdirfgeracao = {$oDirf->rh95_sequencial}");
         if ($oDaoRhDirfDadosPessoal->erro_status == 0) {
          throw new Exception("Erro[2] - Erro ao excluir valores da DIRF.\n{$oDaoRhDirfDadosPessoalValor->erro_msg}");
        }
        $oDaoRhDirfGeracao->excluir($oDirf->rh95_sequencial);
        if ($oDaoRhDirfGeracao->erro_status == 0) {
          throw new Exception("Erro[3] - Erro ao excluir valores da DIRF.\n{$oDaoRhDirfDadosPessoalValor->erro_msg}");
        }
        unset($oDirf);
      }
    }

    /**
     * inclui uma nova geracao da Dirf
     */
    $oDaoRhDirfGeracao->rh95_ano           = $this->iAno;
    $oDaoRhDirfGeracao->rh95_fontepagadora = $this->sCnpj;
    $oDaoRhDirfGeracao->rh95_datageracao   = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoRhDirfGeracao->rh95_id_usuario    = db_getsession("DB_id_usuario");
    $oDaoRhDirfGeracao->incluir(null);
    if ($oDaoRhDirfGeracao->erro_status == 0) {
      throw new Exception("Erro[4] - Erro ao incluir valores da DIRF.\n{$oDaoRhDirfGeracao->erro_msg}");
    }
    
    $this->iCodigoDirf = $oDaoRhDirfGeracao->rh95_sequencial;
    
    /**
     * processa os dados da folha de pagamento
     */
    $this->processarDadosFolha();
     
    if ($lProcessarEmpenhos) {
      /**
       * processa os pagamentos realizados na contabilidade
       */
      $this->processarDadosContabilidade();
    }
    
  }
  /**
   * processa os dados da folha de pagamento
   * @return void
   */
  public function processarDadosFolha() {
    
    /**
     * processamos os dados da Folha 
     * trecho de codigo Copiado do fonte pes4_geradirf002.php
     * TODO Reescrever o codigo.
     */
    $ano_base = $this->iAno;
    /**
     * carrega dos dados da configuracao do modulo pessoal.
     */
    global $sel_B904, $cfpess, $sel_B905,$sel_B906,$sel_B907,$sel_B908,$sel_B909,$sel_B910,$sel_B911, $sel_B912, $sel_B915, $basesr, $subpes;
    db_selectmax( "cfpess", "select * from cfpess 
                             where r11_mesusu = fc_mesfolha(".db_getsession("DB_instit").") 
                               and r11_anousu = fc_anofolha(".db_getsession("DB_instit").")
                               and r11_instit = ".db_getsession("DB_instit")
                );
    $subpes = $this->iAno.'/'.$this->iMes;
    $subini = $subpes;

    /**
     * 
     * verifica quais são as rubricas possui em cada base utilizada na DIRF.
     */
    $condicaoaux  = " and r09_base = ".db_sqlformat( "B904" );
    $sel_B904 = "0";
    $sWhereBases = " where r09_mesusu = fc_mesfolha(".db_getsession("DB_instit").") 
                       and r09_anousu = fc_anofolha(".db_getsession("DB_instit").") 
                       and r09_instit = ".db_getsession("DB_instit");
    if (db_selectmax( "basesr", "select r09_rubric from basesr $sWhereBases {$condicaoaux}")){
      $sel_B904 = "'";
      for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++) {        
         if($Ibasesr > 0){
            $sel_B904 .= ",'"; 
         } 
         $sel_B904 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
      }
    }
    
    $condicaoaux  = " and r09_base = ".db_sqlformat( "B905" );
    $sel_B905 = "0";
    $sSqlBases = "select r09_rubric from basesr {$sWhereBases} {$condicaoaux}";
    if( db_selectmax( "basesr", $sSqlBases)){
      $sel_B905 = "'";
      for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
         if($Ibasesr > 0){
            $sel_B905 .= ",'"; 
         } 
         $sel_B905 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
      }
    }
    
    $condicaoaux  = " and r09_base = ".db_sqlformat( "B906" );
    $sel_B906 = "0";
    if( db_selectmax( "basesr", "select r09_rubric from basesr {$sWhereBases} {$condicaoaux}")) {
      $sel_B906 = "'";
      for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
         if($Ibasesr > 0){
            $sel_B906 .= ",'"; 
         } 
         $sel_B906 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
      }
    }
    
    $condicaoaux  = " and r09_base = ".db_sqlformat( "B907" );
    $sel_B907 = "0";
    if( db_selectmax( "basesr", "select r09_rubric from basesr {$sWhereBases} {$condicaoaux}")){
      $sel_B907 = "'";
      for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
         if($Ibasesr > 0){
            $sel_B907 .= ",'"; 
         } 
         $sel_B907 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
      }
    }
    
    $condicaoaux  = " and r09_base = ".db_sqlformat( "B908" );
    $sel_B908 = "0";
    if( db_selectmax( "basesr", "select r09_rubric from basesr {$sWhereBases} {$condicaoaux}")) {
      $sel_B908 = "'";
      for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
         if($Ibasesr > 0){
            $sel_B908 .= ",'"; 
         } 
         $sel_B908 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
      }
    }
    
    $condicaoaux  = " and r09_base = ".db_sqlformat( "B909" );
    $sel_B909 = "0";
    if( db_selectmax( "basesr", "select r09_rubric from basesr {$sWhereBases} {$condicaoaux}")){
      $sel_B909 = "'";
      for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
         if($Ibasesr > 0){
            $sel_B909 .= ",'"; 
         } 
         $sel_B909 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
      }
    }
        
    $condicaoaux  = " and r09_base = ".db_sqlformat( "B910" );
    $sel_B910 = "0";
    if( db_selectmax( "basesr", "select r09_rubric from basesr {$sWhereBases} {$condicaoaux}")){
      $sel_B910 = "'";
      for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
         if($Ibasesr > 0){
            $sel_B910 .= ",'"; 
         } 
         $sel_B910 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
      }
    }
    
    $condicaoaux  = " and r09_base = ".db_sqlformat( "B911" );
    $sel_B911 = "0";
    if( db_selectmax( "basesr", "select r09_rubric from basesr {$sWhereBases} {$condicaoaux}")){
      $sel_B911 = "'";
      for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
         if($Ibasesr > 0){
            $sel_B911 .= ",'"; 
         } 
         $sel_B911 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
      }
    }
    
    $condicaoaux  = " and r09_base = ".db_sqlformat( "B915" );
    $sel_B915 = "0";
    if( db_selectmax( "basesr", "select r09_rubric from basesr {$sWhereBases} {$condicaoaux}")){
      $sel_B915 = "'";
      for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
         if($Ibasesr > 0){
            $sel_B915 .= ",'"; 
         } 
         $sel_B915 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
      }
    }
    
    $condicaoaux  = " and r09_base = ".db_sqlformat( "B912" );
    $sel_B912 = "0";
    if( db_selectmax( "basesr", "select r09_rubric from basesr {$sWhereBases} {$condicaoaux}")){
      $sel_B912 = "'";
      for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
         if($Ibasesr > 0){
            $sel_B912 .= ",'"; 
         } 
         $sel_B912 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
      }
    }
    
    $condicaoaux  = " and r09_base = ".db_sqlformat( "B903" );
    $sel_B903 = "0";
    if (db_selectmax( "basesr", "select distinct r09_rubric from basesr {$sWhereBases} {$condicaoaux}")) {
      $sel_B903 = "'";
      for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
         if($Ibasesr > 0){
            $sel_B903 .= ",'"; 
         } 
         $sel_B903 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
      }
    }
    $this->sel_B903 = $sel_B903;
    
    $condicaoaux  = " and r09_base = ".db_sqlformat( "B901" );
    $sel_B901 = "0";
    if (db_selectmax( "basesr", "select distinct r09_rubric from basesr {$sWhereBases} {$condicaoaux}")) {
      $sel_B901 = "'";
      for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
         if($Ibasesr > 0){
            $sel_B901 .= ",'"; 
         } 
         $sel_B901 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
      }
    }
    $this->sel_B901 = $sel_B901;

    $condicaoaux  = " and r09_base = ".db_sqlformat( "B914" );
    $sel_B914 = "0";
    if (db_selectmax( "basesr", "select distinct r09_rubric from basesr {$sWhereBases} {$condicaoaux}")) {
      $sel_B914 = "'";
      for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
         if($Ibasesr > 0){
            $sel_B914 .= ",'"; 
         } 
         $sel_B914 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
      }
    }
    $this->sel_B914 = $sel_B914;

    $condicaoaux  = " and extract(year from rh01_admiss) <= ".db_sqlformat($ano_base);
    $condicaoaux .= " and ( rh05_recis is null ";
    $condicaoaux .= "      or  ( rh05_recis is not null  and extract(year from rh05_recis) >= " .db_sqlformat($ano_base)." ) ) ";
    $condicaoaux .= " and o41_cnpj='{$this->sCnpj}'";
    $condicaoaux .= " order by rh01_numcgm ";
    $sSqlPessoal    = "select distinct(rh01_numcgm),"; 
    $sSqlPessoal   .= "       z01_nome, ";
    $sSqlPessoal   .= "       trim(z01_cgccpf) as z01_cgccpf, "; 
    $sSqlPessoal   .= "       0 as processado "; 
    $sSqlPessoal   .= "  from rhpessoalmov  ";
    $sSqlPessoal   .= "       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist ";
    $sSqlPessoal   .= "       left join rhpesrescisao on rhpesrescisao.rh05_seqpes   = rhpessoalmov.rh02_seqpes "; 
    $sSqlPessoal   .= "       inner join cgm          on z01_numcgm  = rhpessoal.rh01_numcgm ";
    $sSqlPessoal   .= "       inner join rhlota       on rh02_lota   = r70_codigo  ";
    $sSqlPessoal   .= "       inner join rhlotaexe    on r70_codigo  = rh26_codigo ";
    $sSqlPessoal   .= "                              and rh26_anousu = ".db_getsession("DB_anousu");
    $sSqlPessoal   .= "       inner join orcunidade   on o41_unidade = rh26_unidade ";
    $sSqlPessoal   .= "                              and o41_orgao   = rh26_orgao ";
    $sSqlPessoal   .= "                              and o41_anousu  = rh26_anousu ";
    $sSqlPessoal   .= " where rh02_anousu = {$this->iAno} ".$condicaoaux;
    $rsDadosPessoal = db_query($sSqlPessoal);
    $aPessoas       = db_utils::getColectionByRecord($rsDadosPessoal);

    $ant            = $subpes;
    $voltas         = 0;
    
    /**
     * calcula os valores de todos os meses
     */
    for ($ind = 1; $ind <= 12; $ind++) {
      
      global $diversos;
      $subpes = $ano_base . "/" . db_str($ind,2,0,"0");
      $condicaoaux = " and r07_codigo = 'D902'";
      
      if ( db_selectmax( "diversos", "select * from pesdiver ".bb_condicaosubpes( "r07_" ).$condicaoaux )){
        $D902 = $diversos[0]["r07_valor"];
      } else {
        $D902 = 0;
      }
      
      $atual = 0;
      
      if ($ind < 13) {
        
        $diasn = db_str(ndias(db_str($ind,2,0,"0")."/".$ano_base),2,0,"0");
        $datet = db_ctod($diasn."/".db_str($ind,2,0,"0")."/".$ano_base) ;
      }
      
      foreach ($aPessoas as $oPessoa) {
        
        /**
         * incluimos os dados pessoais
         */

        if ( trim($oPessoa->z01_cgccpf) == "" ) {
          
          $this->addInconsistente($oPessoa->rh01_numcgm,$oPessoa->z01_nome,'CPF Inválido');
          
          continue;
        }

        if ($oPessoa->processado == 0) {    
          
          $oDaoRhDirfGeracaoPessoal               = db_utils::getDao("rhdirfgeracaodadospessoal");
          $oDaoRhDirfGeracaoPessoal->rh96_cpfcnpj = $oPessoa->z01_cgccpf;
          $oDaoRhDirfGeracaoPessoal->rh96_numcgm  = $oPessoa->rh01_numcgm;
          $oDaoRhDirfGeracaoPessoal->rh96_regist  = '0';
          $oDaoRhDirfGeracaoPessoal->rh96_tipo    = 1;
          $oDaoRhDirfGeracaoPessoal->rh96_rhdirfgeracao = $this->iCodigoDirf;
          $oDaoRhDirfGeracaoPessoal->incluir(null);
          if ($oDaoRhDirfGeracaoPessoal->erro_status == 0) {
            throw new Exception("Erro[7] - Erro ao incluir valores(CGM: {$oPessoa->rh01_numcgm} com CPF/CNPJ Inválido) da DIRF.\n{$oDaoRhDirfGeracaoPessoal->erro_msg}");
          }
          $oPessoa->codigodirf = $oDaoRhDirfGeracaoPessoal->rh96_sequencial;
          $oPessoa->processado = 1;
        }
        
        global $pess,$Ipes;
        
        $atual += 1;
        $condicaoaux  = " and extract(year from rh01_admiss) <= ".db_sqlformat($ano_base);
        $condicaoaux .= " and ( rh05_recis is null ";
        $condicaoaux .= "      or  ( rh05_recis is not null  and extract(year from rh05_recis) >= " .db_sqlformat($ano_base)." ) ) ";
        $condicaoaux .= " and rh01_numcgm = {$oPessoa->rh01_numcgm}";
        $condicaoaux .= " and o41_cnpj='{$this->sCnpj}'";
        $condicaoaux .= "order by rh01_numcgm ";
        
        $campos_pessoal   = "rh01_regist as r01_regist, ";
        $campos_pessoal  .= "rh01_numcgm as r01_numcgm, ";
        $campos_pessoal  .= "trim(to_char(rh02_lota,'9999')) as r01_lotac, ";
        $campos_pessoal  .= "rh01_nasc     as r01_nasc, ";
        $campos_pessoal  .= "rh01_admiss   as r01_admiss, ";
        $campos_pessoal  .= "rh01_instru   as r01_instru, ";
        $campos_pessoal  .= "rh05_recis    as r01_recis, ";
        $campos_pessoal  .= "rh30_vinculo  as r01_tpvinc, ";
        $campos_pessoal  .= "rh02_tbprev   as r01_tbprev ";

                      
        $sSqlComplementoPessoal  = "select {$campos_pessoal}";
        $sSqlComplementoPessoal .= "  from rhpessoalmov  ";
        $sSqlComplementoPessoal .= "       inner join rhpessoal    on rh01_regist = rhpessoalmov.rh02_regist ";
        $sSqlComplementoPessoal .= "       inner join cgm          on z01_numcgm  = rhpessoal.rh01_numcgm ";
        $sSqlComplementoPessoal .= "       left join rhpesrescisao on rh05_seqpes = rhpessoalmov.rh02_seqpes "; 
        $sSqlComplementoPessoal .= "       left join rhregime      on rh30_codreg = rhpessoalmov.rh02_codreg ";
        $sSqlComplementoPessoal .= "                              and rh30_instit = rhpessoalmov.rh02_instit ";
        $sSqlComplementoPessoal .= "       inner join rhlota       on rh02_lota   = r70_codigo  ";
        $sSqlComplementoPessoal .= "       inner join rhlotaexe    on r70_codigo  = rh26_codigo ";
        $sSqlComplementoPessoal .= "                              and rh26_anousu = ".db_getsession("DB_anousu");
        $sSqlComplementoPessoal .= "       inner join orcunidade   on o41_unidade = rh26_unidade ";
        $sSqlComplementoPessoal .= "                              and o41_orgao   = rh26_orgao ";
        $sSqlComplementoPessoal .= "                              and o41_anousu  = rh26_anousu ";
        
        $sSqlComplementoPessoal .= bb_condicaosubpesproc("rh02_",$this->iAno.'/'.$ind).$condicaoaux;
        
        $rsComplementoPessoal    = db_query($sSqlComplementoPessoal);
        
        $aComplementoPessoal     = db_utils::getColectionByRecord($rsComplementoPessoal);
                
        $oPessoa->aValorGrupo   = array();
        $oPessoa->aValorGrupo[1] = 0;
        if (!empty($aComplementoPessoal[0]->r01_nasc)){
          $oPessoa->idade = ver_idade(db_dtoc($datet),db_dtoc($aComplementoPessoal[0]->r01_nasc));
        } else {
          $oPessoa->idade = null;
        }
        
        $iContador              = 0;
        $oPessoa->vdep13        = 0;
        $oPessoa->vdeducao65_13 = 0;
        $oPessoa->vpensao13     = 0;
        $oPessoa->tributado13   = 0;
        $oPessoa->base13        = 0;
        
        $oPessoa->previdencia13        = 0;
        $oPessoa->previdenciaprivada13 = 0;
        
        if (!isset($oPessoa->aValorGrupo13)) {
          $oPessoa->aValorGrupo13 = array();
        }
        $aMatriculas = array();
        
        foreach ($aComplementoPessoal as $oDados) {
          
          $oPessoa->mtributo     = 0;
          $oPessoa->mtribs13     = 0;
          $oPessoa->deducao65    = 0;
          $oPessoa->deducao65_13 = 0;
          
          if ((db_year($oDados->r01_admiss) <= db_val($ano_base) && 
             (db_empty($oDados->r01_recis) || (!db_empty($oDados->r01_recis)) && 
               db_year($oDados->r01_recis >= db_val($ano_base)))) || $ind == 13) {

            if ($iContador < 9){
              $oPessoa->registros .= db_str($oDados->r01_regist, 6)." / ";
            }
            if (!in_array($oDados->r01_regist, $aMatriculas)) {
              $aMatriculas[] = $oDados->r01_regist;
            }
            $condicaoaux = " and r33_codtab = ".db_sqlformat($oDados->r01_tbprev+2);
            global $inssirf;
            db_selectmax( "inssirf", "select r33_tipo from inssirf ".bb_condicaosubpesproc( "r33_", $subini ).$condicaoaux );

            /**
             * calcula os valores de folha de pagmento para o mes.
             */
            $condicaoaux = " and r14_lotac = '{$oDados->r01_lotac}' and r14_regist = ".$oDados->r01_regist;
            $sSqlFolha = "select * from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux ;
            global $gerfsal;
            if (db_selectmax( "gerfsal", $sSqlFolha)) {
              $this->calculaValoresDirfPessoal($gerfsal, "r14_", $oPessoa);
            }
            
            $condicaoaux = " and r48_lotac = '{$oDados->r01_lotac}' and r48_regist = ".$oDados->r01_regist;
            global $gerfcom;
            if ( db_selectmax( "gerfcom", "select * from gerfcom ".bb_condicaosubpes( "r48_" ).$condicaoaux )){
              $this->calculaValoresDirfPessoal($gerfcom, "r48_", $oPessoa);
            }
             
            $condicaoaux = " and r20_lotac = '{$oDados->r01_lotac}' and r20_regist = ".$oDados->r01_regist;
            global $gerfres;
            if (db_selectmax("gerfres", "select * from gerfres ".bb_condicaosubpes( "r20_" ).$condicaoaux )) {
              $this->calculaValoresDirfPessoal($gerfres, "r20_", $oPessoa);
            }
            
            if (db_empty($cfpess[0]["r11_altfer"] ) || $subpes < $cfpess[0]["r11_altfer"] ) {
              
              $condicaoaux = " and r31_lotac = '{$oDados->r01_lotac}' and r31_regist = {$oDados->r01_regist}";
              global $gerffer;
              if ( db_selectmax( "gerffer", "select * from gerffer ".bb_condicaosubpes( "r31_" ).$condicaoaux )){
                $this->calculaValoresDirfPessoal($gerffer, "r31_", $oPessoa);
              }
            }
            $condicaoaux = " and r35_lotac = '{$oDados->r01_lotac}' and r35_regist = {$oDados->r01_regist}";
            global $gerfs13;
            if( db_selectmax( "gerfs13", "select * from gerfs13 ".bb_condicaosubpes( "r35_" ).$condicaoaux )){
              $this->calculaValoresDirfPessoal($gerfs13, "r35_", $oPessoa);
            }
          }
          $iContador ++;
          
          if (db_at(strtolower($pess[$Ipes]["r01_tpvinc"]),"ip") > 0 && ($idade > 65)) {
            
            if ($oPessoa->aValorGrupo[1] >= $D902) {
              $ina     += $D902;
              $oPessoa->aValorGrupo[1] -= $D902;
              
            } else {
              $ina      += $oPessoa->aValorGrupo[1];
              $tributo  = 0;
            }
            if ($oPessoa->aValorGrupo13[1] >= $D902) {
              
              $ina      += $D902;
              $oPessoa->aValorGrupo[13] -= $D902;
              
            } else if ($oPessoa->aValorGrupo13[1] > 0) {
              
              $ina     += $oPessoa->aValorGrupo[13];
              $mtribs13 = 0;
            }
          }
        }
        
        /**
         * inclui o mes para a pessoa valor base
         */
        
        $oDaoRhDirfGeracaoPessoalValor = db_utils::getDao('rhdirfgeracaodadospessoalvalor');
        foreach ($oPessoa->aValorGrupo as $iIndice => $nValor) {
          
          $oDaoRhDirfGeracaoPessoalValor->rh98_mes                       = $ind;
          $oDaoRhDirfGeracaoPessoalValor->rh98_rhdirftipovalor           = $iIndice;
          $oDaoRhDirfGeracaoPessoalValor->rh98_tipoirrf                  = '0561';
          $oDaoRhDirfGeracaoPessoalValor->rh98_instit                    = db_getsession("DB_instit");
          $oDaoRhDirfGeracaoPessoalValor->rh98_rhdirfgeracaodadospessoal = $oPessoa->codigodirf;
          $oDaoRhDirfGeracaoPessoalValor->rh98_valor                     = "{$nValor}";
          $oDaoRhDirfGeracaoPessoalValor->incluir(null);
          if ($oDaoRhDirfGeracaoPessoalValor->erro_status == 0) {
           throw new Exception("Erro[8] - Erro ao incluir valores bases da DIRF.\n{$oDaoRhDirfGeracaoPessoalValor->erro_msg}");
          }
          
          /**
           * vincula as matriculas ao valor calculado para o cpf.
           */
          foreach ($aMatriculas as $iMatricula) {
            
            $oDaoRhDirfGeracaoPessoalMatricula = db_utils::getDao("rhdirfgeracaopessoalregist");
            $oDaoRhDirfGeracaoPessoalMatricula->rh99_regist =  $iMatricula;
            $oDaoRhDirfGeracaoPessoalMatricula->rh99_rhdirfgeracaodadospessoalvalor = $oDaoRhDirfGeracaoPessoalValor->rh98_sequencial;
            $oDaoRhDirfGeracaoPessoalMatricula->incluir(null);
            if ($oDaoRhDirfGeracaoPessoalMatricula->erro_status == 0) {
              
              $sMsg  = "Erro[18] - Erro ao incluir matriculas para calculo da DIRF.";
              $sMsg .= "\n{$oDaoRhDirfGeracaoPessoalMatricula->erro_msg}";
              throw new Exception($sMsg);
            }
          }
        }
        
        if ($ind == 12) {
          
          foreach ($oPessoa->aValorGrupo13 as $iIndice => $nValor) {
            
            $oDaoRhDirfGeracaoPessoalValor->rh98_mes                       = 13;
            $oDaoRhDirfGeracaoPessoalValor->rh98_rhdirftipovalor           = $iIndice;
            $oDaoRhDirfGeracaoPessoalValor->rh98_tipoirrf                  = '0561';
            $oDaoRhDirfGeracaoPessoalValor->rh98_rhdirfgeracaodadospessoal = $oPessoa->codigodirf;
            $oDaoRhDirfGeracaoPessoalValor->rh98_valor                     = "{$nValor}";
            $oDaoRhDirfGeracaoPessoalValor->rh98_instit                    = db_getsession("DB_instit");
            $oDaoRhDirfGeracaoPessoalValor->incluir(null);
            if ($oDaoRhDirfGeracaoPessoalValor->erro_status == 0) {
             throw new Exception("Erro[9] - Erro ao incluir valores bases da DIRF para 13.\n{$oDaoRhDirfGeracaoPessoalValor->erro_msg}");
            }
            
            /**
             * vincula as matriculas ao valor calculado para o cpf.
             */
            foreach ($aMatriculas as $iMatricula) {
              
              $oDaoRhDirfGeracaoPessoalMatricula = db_utils::getDao("rhdirfgeracaopessoalregist");
              $oDaoRhDirfGeracaoPessoalMatricula->rh99_regist =  $iMatricula;
              $oDaoRhDirfGeracaoPessoalMatricula->rh99_rhdirfgeracaodadospessoalvalor = $oDaoRhDirfGeracaoPessoalValor->rh98_sequencial;
              $oDaoRhDirfGeracaoPessoalMatricula->incluir(null);
              if ($oDaoRhDirfGeracaoPessoalMatricula->erro_status == 0) {
                
                $sMsg  = "Erro[19] - Erro ao incluir matriculas para calculo da DIRF.";
                $sMsg .= "\n{$oDaoRhDirfGeracaoPessoalMatricula->erro_msg}";
                throw new Exception($sMsg);
              }
            }
          }
        }
      }
    }
  }
  /**
   * calcula os valores para a matricula 
   *
   * @param string $arq 
   * @param unknown_type $sigla
   * @param unknown_type $oPessoa
   */
  protected function calculaValoresDirfPessoal($arq, $sigla, $oPessoa) {
  
    global $tributo,$vlrdep,$retido,$subpes,$cfpess,$subini,$inssirf,$pess,$Ipes,
           $previd ,$pensao,$tribs13, $ind,
           $vdep13, $rets13,$prev13,$vdeducao65,
           $pensao13,$vdeducao65_13, $vdeducao65_13, $mtributo,$mtribs13,$basesr;
    
    global $sel_B904,$sel_B905,$sel_B906,$sel_B907,$sel_B908,$sel_B909,$sel_B910,$sel_B911, $sel_B912, $sel_B915,$sel_B903, $basesr;
    
    
    // situacao de ferias novas e nova forma de ver as bases da complementar;
    $lercomplementar = ($subpes >= $cfpess[0]["r11_altfer"]?true:false);
    
    for ($Iarq = 0; $Iarq < count($arq); $Iarq++) {
    //  echo "<BR> rubric --> ".$arq[$Iarq][$sigla."rubric"]." sigla --> $sigla";
       // salario + ferias (base bruta p/ irf);
       if (!isset($oPessoa->aValorGrupo[1])) {
           $oPessoa->aValorGrupo[1] = 0;
       }
       if( $arq[$Iarq][$sigla."rubric"] == "R981" || $arq[$Iarq][$sigla."rubric"] == "R983") {
          if (( $sigla != "r48_" || ( $sigla == "r48_" && $lercomplementar ) )){
            
            if (!isset($oPessoa->aValorGrupo[1])) {
                $oPessoa->aValorGrupo[1] = 0;
            }
            $oPessoa->aValorGrupo[1] += $arq[$Iarq][$sigla."valor"]; 
            $oPessoa->mtributo += $arq[$Iarq][$sigla."valor"];
          }
       } else {
          // 13o salario (base bruta p/ irf);
          if ($arq[$Iarq][$sigla."rubric"] == "R982") {
            
            if (!isset($oPessoa->aValorGrupo13[1])) {
               $oPessoa->aValorGrupo13[1] = 0;
             }
             if ($sigla != "r48_" || ( $sigla == "r48_" && $lercomplementar )) {

                $oPessoa->aValorGrupo13[1] += $arq[$Iarq][$sigla."valor"]; 
                $oPessoa->mtribs13         += $arq[$Iarq][$sigla."valor"];
                
                if (!isset($oPessoa->aValorGrupo[1])) {
                    $oPessoa->aValorGrupo[1] = 0;
                }
                //$oPessoa->aValorGrupo[1] += $arq[$Iarq][$sigla."valor"]; 
                //$oPessoa->mtributo += $arq[$Iarq][$sigla."valor"];                
             }
          } else {
            
             // vlr ref dependentes p/ irf;
             if ($arq[$Iarq][$sigla."rubric"] == "R984") {

               if (!isset($oPessoa->aValorGrupo[4])) {
                    $oPessoa->aValorGrupo[4] = 0; 
               }
               if (!isset($oPessoa->aValorGrupo13[4])) {
                    $oPessoa->aValorGrupo13[4] = 0; 
               }
               if ($sigla == "r35_") {
                 if (!db_empty($oPessoa->aValorGrupo13[4]) && !db_empty($arq[$Iarq][$sigla."valor"]) ){
                   $oPessoa->aValorGrupo13[4] = 0;
                 }
                 $oPessoa->aValorGrupo13[4] += $arq[$Iarq][$sigla."valor"];
                  
               } else if ($sigla == "r20_" && $oPessoa->mtribs13 > 0 ) {

                 if (!isset($oPessoa->aValorGrupo13[4])) {
                   $oPessoa->aValorGrupo13[4] = 0;
                 }
                 if (!db_empty($oPessoa->aValorGrupo13[4]) && !db_empty($arq[$Iarq][$sigla."valor"]) ){
                   $oPessoa->aValorGrupo13[4] = 0;
                 }
                 $oPessoa->aValorGrupo13[4]  += $arq[$Iarq][$sigla."valor"];
                   
                } else if ($sigla == "r48_" && $lercomplementar) {

                  // somente ler o dependente da complementar se este nao;
                  // estiver no salario ( que foi lido primeiro );
                  if (db_empty($oPessoa->aValorGrupo[4])){
                    $oPessoa->aValorGrupo[4] += $arq[$Iarq][$sigla."valor"];
                  }
                } else if ($sigla != "r48_") {

                  if (db_empty($oPessoa->aValorGrupo[4] )) {
                    $oPessoa->aValorGrupo[4] += $arq[$Iarq][$sigla."valor"];
                  }
                }
              }
             // deducao +65 anos para salario e 13.salario;
             if ($lercomplementar && $arq[$Iarq][$sigla."rubric"] == "R997" || $arq[$Iarq][$sigla."rubric"] == "R999" ) {
                
               if (!isset($oPessoa->aValorGrupo[7])) {
                 $oPessoa->aValorGrupo[7] = 0; 
               }
               if (!isset($oPessoa->aValorGrupo13[7])) {
                 $oPessoa->aValorGrupo13[7] = 0; 
               }
               if ( $sigla == "r35_" ||  $arq[$Iarq][$sigla."rubric"] == "R999") {

                 if( !db_empty($oPessoa->aValorGrupo13[7]) && !db_empty($arq[$Iarq][$sigla."valor"])) {
                   $oPessoa->aValorGrupo13[7] = 0;
                 }
                 $oPessoa->aValorGrupo13[7]  += $arq[$Iarq][$sigla."valor"];
               } else if($sigla == "r48_" && $lercomplementar) {

                 if ( db_empty( $oPessoa->aValorGrupo[7] )) {
                  $oPessoa->aValorGrupo[7] += $arq[$Iarq][$sigla."valor"];
                 }
               } else if ($sigla == "r31_") {
                  
                 if ( db_empty($oPessoa->vdeducao65 )) {
                      $oPessoa->aValorGrupo[7] += $arq[$Iarq][$sigla."valor"];
                 }
               /**
                * Alteracoes tarefa 43944
                * Soma valor complementar(r48_) ou salario(r14_) ou rescisao(r20_)
                */
               } else if ($sigla == "r48_" || $sigla == "r14_" || $sigla == "r20_") {
                 $oPessoa->aValorGrupo[7] += $arq[$Iarq][$sigla."valor"];
               }
             }
             $mrubr = $arq[$Iarq][$sigla."rubric"];
             
             if ($mrubr == "R975") {
               
               if (!isset($oPessoa->aValorGrupo[12])) {
                 $oPessoa->aValorGrupo[12] = 9;
               }
               if ( $arq[$Iarq][$sigla."pd"] == 2 ) {
                 
                 $oPessoa->aValorGrupo[12] -= $arq[$Iarq][$sigla."valor"];
               } else {
                 $oPessoa->aValorGrupo[12] += $arq[$Iarq][$sigla."valor"];
               }
             }
             //*** o arquivo bases e lido a partir do mes de processamento (inicial);
             // busca valores de base bruta fora da folha (menos 13o salario);
             // exemplos: precatorios, dsd de riogrande. se lancar o precatorio;
             // como base de ir, esta nao devera estar marcada nesta base. ;
             if (db_at($mrubr, $sel_B911) > 0) {
               if ( $arq[$Iarq][$sigla."pd"] == 2 ) {
                 $oPessoa->aValorGrupo[1] -= $arq[$Iarq][$sigla."valor"];
               } else {
                 $oPessoa->aValorGrupo[1] += $arq[$Iarq][$sigla."valor"];
               }
             }
             if (($sigla != "r48_" || ($sigla == "r48_" && $lercomplementar ) )) {
    
                // busca irf (menos 13o salario);
                if ( db_at($mrubr,$sel_B906) > 0){
                  
                  if (!isset($oPessoa->aValorGrupo[6])) {
                    $oPessoa->aValorGrupo[6] = 0;
                  }
                  if ($arq[$Iarq][$sigla."pd"] == 2 ){
                    $oPessoa->aValorGrupo[6] += $arq[$Iarq][$sigla."valor"];
                  } else {
                    $oPessoa->aValorGrupo[6] -= $arq[$Iarq][$sigla."valor"];
                  }
                }
                // busca irf (13o salario);
                if (db_at($mrubr, $sel_B909) > 0) {
                 
                  if (!isset($oPessoa->aValorGrupo13[6])) {
                    $oPessoa->aValorGrupo13[6] = 0;
                  }
                  if ($arq[$Iarq][$sigla."pd"] == 2 ) {
                    $oPessoa->aValorGrupo13[6] += $arq[$Iarq][$sigla."valor"];
                  } else {
                    $oPessoa->aValorGrupo13[6] -= $arq[$Iarq][$sigla."valor"];
                  }
                }
                
                // prev 13o salario;
                if (db_at($mrubr, $sel_B908) > 0) {
                  
                  if (!isset($oPessoa->aValorGrupo13[2])) {
                    $oPessoa->aValorGrupo13[2] = 0;
                  }
                  if ( $arq[$Iarq][$sigla."pd"] == 2 ) {
                    
                    $oPessoa->aValorGrupo13[2]+= $arq[$Iarq][$sigla."valor"];
                  } else {
                    $oPessoa->aValorGrupo13[2] -= $arq[$Iarq][$sigla."valor"];
                  }
                }
                // busca previd (menos de 13o salario);
                if (db_at($mrubr, $sel_B907) > 0) {
                  
                  
                  if (!isset($oPessoa->aValorGrupo[2])) {
                    $oPessoa->aValorGrupo[2] = 0;
                  }
                  if (strtolower($inssirf[0]["r33_tipo"]) == "o" && $pess[$Ipes]["r01_tbprev"] != '0') {
                    if($arq[$Iarq][$sigla."pd"] == 2) {
                      $oPessoa->aValorGrupo[2] += $arq[$Iarq][$sigla."valor"];
                    }else{
                      $oPessoa->aValorGrupo[2] -= $arq[$Iarq][$sigla."valor"];
                    }
                  } else {
                    
                     // previdencia privada ;
                     // mantive como no comprovante porem neste todas as previdencia;
                     // ficam como deducoes ;
                    if (!isset($oPessoa->aValorGrupo[3])) {
                      $oPessoa->aValorGrupo[3] = 0;
                    }
                    if ($arq[$Iarq][$sigla."pd"] == 2) {
                      $oPessoa->aValorGrupo[3] += $arq[$Iarq][$sigla."valor"];
                    } else {
                      $oPessoa->aValorGrupo[3] -= $arq[$Iarq][$sigla."valor"];
                    }
                  }
                }
                
                // nao estava lendo esta base na dirf e no comprovante estava...;
                // previdencia privada tambem e deducao;
                if (db_at($mrubr, $sel_B910) > 0) {
                  if (!isset($oPessoa->aValorGrupo[3])) {
                    $oPessoa->aValorGrupo[3] = 0;
                  }
                  if ($arq[$Iarq][$sigla."pd"] == 2){
                    $oPessoa->aValorGrupo[3] += $arq[$Iarq][$sigla."valor"];
                  } else {
                     $oPessoa->aValorGrupo[3] -= $arq[$Iarq][$sigla."valor"];
                  }
                }
                if (db_at($mrubr, $this->sel_B903) > 0) {
                   
                  if (!isset($oPessoa->aValorGrupo[8])) {
                    $oPessoa->aValorGrupo[8] = 0;
                  }
                  if ($arq[$Iarq][$sigla."pd"] == 1){
                    $oPessoa->aValorGrupo[8] += $arq[$Iarq][$sigla."valor"];
                  } else {
                     $oPessoa->aValorGrupo[8] -= $arq[$Iarq][$sigla."valor"];
                  }
                }
                if (db_at($mrubr, $sel_B912) > 0) {
                  
                 if (!isset($oPessoa->aValorGrupo[10])) {
                   $oPessoa->aValorGrupo[10] = 0;
                 }
                 if ($arq[$Iarq][$sigla."pd"] == 2){
                   $oPessoa->aValorGrupo[10] -= $arq[$Iarq][$sigla."valor"];
                 } else {
                   $oPessoa->aValorGrupo[10] += $arq[$Iarq][$sigla."valor"];
                 }
               }
                if (db_at($mrubr, $sel_B915) > 0) {
                  
                 if (!isset($oPessoa->aValorGrupo[15])) {
                   $oPessoa->aValorGrupo[15] = 0;
                 }
                 if ($arq[$Iarq][$sigla."pd"] == 2){
                   $oPessoa->aValorGrupo[15] -= $arq[$Iarq][$sigla."valor"];
                 } else {
                   $oPessoa->aValorGrupo[15] += $arq[$Iarq][$sigla."valor"];
                 }
               }
             }
             //                    ;
             // busca vlrs pensao alimenticia ;
             if (db_at($mrubr, $sel_B905) > 0) {
    
               if ($sigla == "r35_" || (db_val($mrubr) >= 4000 && db_val($mrubr) < 6000 )) {
                 if (!isset($oPessoa->aValorGrupo13[5])) {
                   $oPessoa->aValorGrupo13[5] = 0;
                 } 
                 if ($arq[$Iarq][$sigla."pd"] == 1) {
                   $oPessoa->aValorGrupo13[5] -= $arq[$Iarq][$sigla."valor"];
                 } else {
                   $oPessoa->aValorGrupo13[5] += $arq[$Iarq][$sigla."valor"];
                 }
               } else {
                 
                 if (!isset($oPessoa->aValorGrupo[5])) {
                   $oPessoa->aValorGrupo[5] = 0;
                 }  
                 if ($arq[$Iarq][$sigla."pd"] == 1) {
                      
                   $oPessoa->aValorGrupo[5] -= $arq[$Iarq][$sigla."valor"];
                 } else {
                   $oPessoa->aValorGrupo[5] += $arq[$Iarq][$sigla."valor"];
                 }
                   // se for forma antiga (R994) deve levar em conta que a;
                   // pensao ja estava descontada na base "bruta";
                if (db_at($mrubr,$sel_B904) > 0) {

                  if (!isset($oPessoa->aValorGrupo[1])) {
                    $oPessoa->aValorGrupo[1] = 0;
                  }
                  if ( $arq[$Iarq][$sigla."pd"] == 1) {
                    $oPessoa->aValorGrupo[1] -= $arq[$Iarq][$sigla."valor"];
                  } else {
                   $oPessoa->aValorGrupo[1] += $arq[$Iarq][$sigla."valor"];
                 }
               }
             }
          }
          if (db_at($mrubr, $this->sel_B901) > 0) {
                
            if (!isset($oPessoa->aValorGrupo[13])) {
              $oPessoa->aValorGrupo[13] = 0;
            }
            if ($arq[$Iarq][$sigla."pd"] == 1){
              $oPessoa->aValorGrupo[13] -= $arq[$Iarq][$sigla."valor"];
            } else {
              $oPessoa->aValorGrupo[13] += $arq[$Iarq][$sigla."valor"];
            }
          }
          if (db_at($mrubr, $this->sel_B914) > 0) {
                
            if (!isset($oPessoa->aValorGrupo[14])) {
              $oPessoa->aValorGrupo[14] = 0;
            }
            if ($arq[$Iarq][$sigla."pd"] == 1){
              $oPessoa->aValorGrupo[14] -= $arq[$Iarq][$sigla."valor"];
            } else {
              $oPessoa->aValorGrupo[14] += $arq[$Iarq][$sigla."valor"];
            }
          }
        }
      }
    }
  }
  
  /**
   * processa todos os pagamentos e retencoes na contabilidade
   *
   */
  public function processarDadosContabilidade() {
     
    
    $sSqlDadosContabilidade  = "SELECT z01_numcgm, ";
    $sSqlDadosContabilidade .= "       trim(z01_cgccpf) as z01_cgccpf, ";
    $sSqlDadosContabilidade .= "       trim(z01_nome)   as z01_nome,   ";
    $sSqlDadosContabilidade .= "       coalesce(sum(case when c53_tipo = 30 then c70_valor else 0 end),0) as valor_pago, ";
    $sSqlDadosContabilidade .= "       coalesce(sum(case when c53_tipo = 31 then c70_valor else 0 end),0) as valor_estornado, ";
    $sSqlDadosContabilidade .= "       extract(month from c70_data) as mes, ";
     $sSqlDadosContabilidade .= "      case when (select e30_codigo";
     $sSqlDadosContabilidade .= "         from retencaopagordem";
     $sSqlDadosContabilidade .= "              inner join retencaoreceitas on e23_retencaopagordem = e20_sequencial";
     $sSqlDadosContabilidade .= "                                         and e23_recolhido is true";
     $sSqlDadosContabilidade .= "              inner join retencaotiporec  on e23_retencaotiporec = e21_sequencial";
     $sSqlDadosContabilidade .= "                                         and e21_retencaotipocalc in(1,2)";
     $sSqlDadosContabilidade .= "                                         and e21_retencaotiporecgrupo = 1";
     $sSqlDadosContabilidade .= "              inner  join  retencaonaturezatiporec on e31_retencaotiporec = e21_sequencial";
     $sSqlDadosContabilidade .= "              inner  join  retencaonatureza        on e31_retencaonatureza = e30_sequencial";
     $sSqlDadosContabilidade .= "                                                 and e31_retencaonatureza is not null";
     $sSqlDadosContabilidade .= "        where e20_pagordem = c80_codord limit 1";
     $sSqlDadosContabilidade .= "        )";
     $sSqlDadosContabilidade .= "        is not null then";
     $sSqlDadosContabilidade .= "        (select e30_codigo";
     $sSqlDadosContabilidade .= "         from retencaopagordem";
     $sSqlDadosContabilidade .= "              inner join retencaoreceitas on e23_retencaopagordem = e20_sequencial";
     $sSqlDadosContabilidade .= "                                         and e23_recolhido is true";
     $sSqlDadosContabilidade .= "              inner join retencaotiporec  on e23_retencaotiporec = e21_sequencial";
     $sSqlDadosContabilidade .= "                                         and e21_retencaotipocalc in(1,2)";
     $sSqlDadosContabilidade .= "                                         and e21_retencaotiporecgrupo = 1";
     $sSqlDadosContabilidade .= "              inner  join  retencaonaturezatiporec on e31_retencaotiporec = e21_sequencial";
     $sSqlDadosContabilidade .= "              inner  join  retencaonatureza        on e31_retencaonatureza = e30_sequencial";
     $sSqlDadosContabilidade .= "                                                 and e31_retencaonatureza is not null";
     $sSqlDadosContabilidade .= "        where e20_pagordem = c80_codord limit 1)";
     $sSqlDadosContabilidade .= "        else";
     $sSqlDadosContabilidade .= "           case when length(z01_cgccpf) = 14 then '1708' else '0588' end end as tipo,";
    $sSqlDadosContabilidade .= "       c80_codord ";
    $sSqlDadosContabilidade .= "  from conlancam ";
    $sSqlDadosContabilidade .= "       inner join conlancamdoc on c70_codlan = c71_codlan ";
    $sSqlDadosContabilidade .= "       inner join conlancamord on c70_codlan = c80_codlan ";
    $sSqlDadosContabilidade .= "       inner join conlancamemp on c75_codlan = c70_Codlan ";
    $sSqlDadosContabilidade .= "       inner join empempenho on e60_numemp = c75_numemp ";
    $sSqlDadosContabilidade .= "       inner join cgm on z01_numcgm = e60_numcgm ";
    $sSqlDadosContabilidade .= "       left  join rhpessoal on z01_numcgm = rh01_numcgm ";
    $sSqlDadosContabilidade .= "       inner join conhistdoc on c71_coddoc = c53_coddoc ";
    $sSqlDadosContabilidade .= "       inner join orcdotacao       on e60_coddot           = o58_coddot  ";
    $sSqlDadosContabilidade .= "                                   and e60_anousu           = o58_anousu  ";
    $sSqlDadosContabilidade .= "       inner join orcunidade       on o41_unidade          = o58_unidade  ";
    $sSqlDadosContabilidade .= "                                   and o41_anousu           = o58_anousu  ";
    $sSqlDadosContabilidade .= "                                   and o41_orgao            = o58_orgao  ";
    $sSqlDadosContabilidade .= " where c70_data between '{$this->iAno}-01-01' and '{$this->iAno}-12-31'";
    $sSqlDadosContabilidade .= "   and o41_cnpj = '{$this->sCnpj}'";
    $sSqlDadosContabilidade .= "   and c53_tipo in (30,31)";
    $sSqlDadosContabilidade .= "   and rh01_regist is null";
    $sSqlDadosContabilidade .= "   and e60_instit = ".db_getsession("DB_instit");
    $sSqlDadosContabilidade .= " group by z01_numcgm,z01_cgccpf,z01_nome,6,7,c80_codord ";
    $sSqlDadosContabilidade .= " order by z01_numcgm, 6, c80_codord ";
    
    $rsDadosContabilidade    = db_query($sSqlDadosContabilidade);
    $aDadosDirf              = array();
    $aOrdensPagamento        = db_utils::getColectionByRecord($rsDadosContabilidade);
    $aOrdensIndex            = array();
    
    /**
     * processa as anulações de empenho reduzindo os valores de pagamentos anteriores.
     */
    foreach ($aOrdensPagamento as $oPagamento) {
      
      if ($oPagamento->tipo == 0) {
        $oPagamento->tipo = '1708';
      }
      if ($oPagamento->valor_pago >= $oPagamento->valor_estornado) {
        
        $oPagamento->valor_pago     -= $oPagamento->valor_estornado;
        $oPagamento->valor_estornado = 0;
        
      } else {
        
        $nDeducao = $oPagamento->valor_pago;
        $oPagamento->valor_pago = 0;
        $oPagamento->valor_estornado -= $nDeducao;
        while ($oPagamento->valor_estornado > 0) {
          
          foreach ($aOrdensPagamento as $oPagamento2) {
            if ($oPagamento->c80_codord == $oPagamento2->c80_codord && $oPagamento->mes >= $oPagamento2->mes) {
              
              if ($oPagamento2->valor_pago >= $oPagamento->valor_estornado) {
        
                $oPagamento2->valor_pago     -= $oPagamento->valor_estornado;
                $oPagamento->valor_estornado  = 0;
              } else {
                
                $nDeducao2 = $oPagamento2->valor_pago;
                $oPagamento2->valor_pago      = 0;
                $oPagamento->valor_estornado -= $nDeducao;
              } 
            }
          }
        }
      }
    }
    
    $aDadosDirf = array();
    
    foreach ($aOrdensPagamento as $oContribuinte) {
      
      if ($oContribuinte->valor_pago == 0) {
        continue;
      }
      if (!isset($aDadosDirf[$oContribuinte->z01_numcgm])) {
            
         $oDeclaracaoDirf  = new stdClass();
         $oDeclaracaoDirf->cnpj         = $oContribuinte->z01_cgccpf;
         $oDeclaracaoDirf->nome         = $oContribuinte->z01_nome;
         $oDeclaracaoDirf->valores      = array();
         $oDeclaracaoDirf->retencaomes  = array();
         $aDadosDirf[$oContribuinte->z01_numcgm] = $oDeclaracaoDirf;
       }
       /**
         * Agrupamos os valores por tipo .
         * 1 - Valores de base de calculo (o valor pago total)
         */
       $oValorMesBase           = new stdClass();
       $oValorMesBase->valor    = $oContribuinte->valor_pago;
       $oValorMesBase->mes      = $oContribuinte->mes;
       $oValorMesBase->retencao = $oContribuinte->tipo;
          
       /**
        * total do valor retido para o mes.
        * calculamos apenas se o valor retido no mes ainda nao foi calculado 
        */
       if (!in_array($oContribuinte->mes, $aDadosDirf[$oContribuinte->z01_numcgm]->retencaomes)) {
       
         $sSqlDadosIRRF  = " SELECT coalesce(sum(e23_valorretencao), 0) as retido "; 
         $sSqlDadosIRRF .= "  from retencaotiporec ";
         $sSqlDadosIRRF .= "       inner join retencaoreceitas         on e21_sequencial  = e23_retencaotiporec  ";
         $sSqlDadosIRRF .= "       inner join retencaocorgrupocorrente on e23_sequencial  = e47_retencaoreceita  ";
         $sSqlDadosIRRF .= "       inner join corgrupocorrente         on k105_sequencial = e47_corgrupocorrente ";
         $sSqlDadosIRRF .= "       inner join retencaopagordem         on e20_sequencial  = e23_retencaopagordem ";
         $sSqlDadosIRRF .= "       inner join pagordem                 on e50_codord      = e20_pagordem ";
         $sSqlDadosIRRF .= "       inner join empempenho               on e50_numemp      = e60_numemp ";
         $sSqlDadosIRRF .= "       inner join orcdotacao               on e60_coddot      = o58_coddot ";
         $sSqlDadosIRRF .= "                                          and o58_anousu      = e60_anousu ";
         $sSqlDadosIRRF .= "       inner join orcunidade               on o41_unidade     = o58_unidade ";
         $sSqlDadosIRRF .= "                                          and o58_orgao       = o41_orgao   ";
         $sSqlDadosIRRF .= "                                          and o41_anousu = o58_anousu       "; 
         $sSqlDadosIRRF .= "  where e21_retencaotiporecgrupo = 1  "; 
         $sSqlDadosIRRF .= "    and e23_recolhido is true         ";
         $sSqlDadosIRRF .= "    and e23_ativo     is true         ";         
         $sSqlDadosIRRF .= "    and e21_retencaotipocalc in(1, 2) ";
         $sSqlDadosIRRF .= "    and o41_cnpj = '{$this->sCnpj}'   ";
         $sSqlDadosIRRF .= "    and extract(month from k105_data) = {$oContribuinte->mes} ";
         $sSqlDadosIRRF .= "    and e60_numcgm                    = {$oContribuinte->z01_numcgm} ";
         $sSqlDadosIRRF .= "    and extract(year from k105_data)  = {$this->iAno} ";
         $sSqlDadosIRRF .= "    and e60_instit = ".db_getsession("DB_instit");
         $rsDadosIRRF    = db_query($sSqlDadosIRRF);
         
         /**
          * calculamos o valor total de inss para o mes, do cgm.
          */
         $sSqlDadosInss  = " SELECT coalesce(sum(e23_valorretencao), 0) as retido "; 
         $sSqlDadosInss .= "  from retencaotiporec ";
         $sSqlDadosInss .= "       inner join retencaoreceitas         on e21_sequencial  = e23_retencaotiporec  ";
         $sSqlDadosInss .= "       inner join retencaocorgrupocorrente on e23_sequencial  = e47_retencaoreceita  ";
         $sSqlDadosInss .= "       inner join corgrupocorrente         on k105_sequencial = e47_corgrupocorrente ";
         $sSqlDadosInss .= "       inner join retencaopagordem         on e20_sequencial  = e23_retencaopagordem ";
         $sSqlDadosInss .= "       inner join pagordem                 on e50_codord      = e20_pagordem ";
         $sSqlDadosInss .= "       inner join empempenho               on e50_numemp      = e60_numemp ";
         $sSqlDadosInss .= "       inner join orcdotacao               on e60_coddot      = o58_coddot ";
         $sSqlDadosInss .= "                                          and o58_anousu      = e60_anousu ";
         $sSqlDadosInss .= "       inner join orcunidade               on o41_unidade     = o58_unidade ";
         $sSqlDadosInss .= "                                          and o58_orgao       = o41_orgao   ";
         $sSqlDadosInss .= "                                          and o41_anousu = o58_anousu       "; 
         $sSqlDadosInss .= "  where e21_retencaotiporecgrupo = 1  "; 
         $sSqlDadosInss .= "    and e23_recolhido is true         ";
         $sSqlDadosInss .= "    and e23_ativo     is true         ";
         $sSqlDadosInss .= "    and e21_retencaotipocalc in(3, 7) ";
         $sSqlDadosInss .= "    and o41_cnpj = '{$this->sCnpj}'";
         $sSqlDadosInss .= "    and extract(month from k105_data) = {$oContribuinte->mes} ";
         $sSqlDadosInss .= "    and e60_numcgm                    = {$oContribuinte->z01_numcgm} ";
         $sSqlDadosInss .= "    and extract(year from k105_data)  = {$this->iAno} ";
         $sSqlDadosInss .= "    and e60_instit = ".db_getsession("DB_instit");
         
         $rsDadosInss    = db_query($sSqlDadosInss);
         $nValorInss     = db_utils::fieldsMemory($rsDadosInss, 0)->retido;
         if ($nValorInss > 0) {
              
           $oValorMesPrevidencia           = new stdClass();
           $oValorMesPrevidencia->valor    = $nValorInss;
           $oValorMesPrevidencia->mes      = $oContribuinte->mes;
           $oValorMesPrevidencia->retencao = $oContribuinte->tipo;
           $aDadosDirf[$oContribuinte->z01_numcgm]->valores[2][] = $oValorMesPrevidencia;
         }
         $nValorRetido              = db_utils::fieldsMemory($rsDadosIRRF, 0)->retido;
         if ($nValorRetido > 0) {
              
           $oValorMesRetido           = new stdClass();
           $oValorMesRetido->valor    = $nValorRetido;
           $oValorMesRetido->mes      = $oContribuinte->mes;
           $oValorMesRetido->retencao = $oContribuinte->tipo;
           $aDadosDirf[$oContribuinte->z01_numcgm]->valores[6][] = $oValorMesRetido;
         }
         
       }
       $aDadosDirf[$oContribuinte->z01_numcgm]->valores[1][]  = $oValorMesBase;
       $aDadosDirf[$oContribuinte->z01_numcgm]->retencaomes[] = $oContribuinte->mes;
    }
    /**
     * realizamos a inclusão conforme do tipo.
     */
    foreach ($aDadosDirf as $iNumCgm => $oDirf) {

      if ( trim($oDirf->cnpj) == "" ) {
        
        $this->addInconsistente($iNumCgm,$oDirf->nome,'CPF Inválido');
        
        continue;
      }

      $oDaoRhDirfGeracaoPessoal               = db_utils::getDao("rhdirfgeracaodadospessoal");
      $oDaoRhDirfGeracaoPessoal->rh96_cpfcnpj = $oDirf->cnpj;
      $oDaoRhDirfGeracaoPessoal->rh96_numcgm  = $iNumCgm;
      $oDaoRhDirfGeracaoPessoal->rh96_regist  = '0';
      $oDaoRhDirfGeracaoPessoal->rh96_tipo    = 2;
      $oDaoRhDirfGeracaoPessoal->rh96_rhdirfgeracao = $this->iCodigoDirf;
      $oDaoRhDirfGeracaoPessoal->incluir(null);
      
      if ($oDaoRhDirfGeracaoPessoal->erro_status == 0) {
        $sMsg  = "Erro[10] -  Erro ao incluir valores(CGM: {$iNumCgm} com CPF/CNPJ Inválido) da DIRF.\n";
        $sMsg .= "{$oDaoRhDirfGeracaoPessoal->erro_msg}";
        throw new Exception($sMsg);
      }
      
      $oDirf->codigodirf = $oDaoRhDirfGeracaoPessoal->rh96_sequencial;
      $oDaoRhDirfGeracaoPessoalValor  = db_utils::getDao("rhdirfgeracaodadospessoalvalor");
      foreach ($oDirf->valores as $iTipo  => $aValor) {
        
        foreach ($aValor as $oValor) {
          
          $oDaoRhDirfGeracaoPessoalValor->rh98_mes                       = $oValor->mes;
          $oDaoRhDirfGeracaoPessoalValor->rh98_rhdirftipovalor           = $iTipo;
          $oDaoRhDirfGeracaoPessoalValor->rh98_tipoirrf                  = "$oValor->retencao";
          $oDaoRhDirfGeracaoPessoalValor->rh98_rhdirfgeracaodadospessoal = $oDirf->codigodirf;
          $oDaoRhDirfGeracaoPessoalValor->rh98_instit                    = db_getsession("DB_instit");
          $oDaoRhDirfGeracaoPessoalValor->rh98_valor                     = "{$oValor->valor}";
          $oDaoRhDirfGeracaoPessoalValor->incluir(null);
          if ($oDaoRhDirfGeracaoPessoalValor->erro_status == 0) {
            
            $sMsg  = "Erro[11] - Erro ao incluir valores bases da DIRF para .\n";
            $sMsg .= $oDaoRhDirfGeracaoPessoalValor->erro_msg;
            throw new Exception($sMsg);
          }
        }
      }
    }
  } 

  
  public function gerarArquivo($oDados, $lGerarContabil=true) {

    $aArquivosGerar     = array("Dirf", 
                                "DECPJ", 
                                "RESPO", 
                                "IDREC", 
                                "BPFDEC", 
                                "BPJDEC",
                                "RTRT", 
                                "FIMDirf",
                                "PSE", 
                                "RIO", 
                                "OPSE",
                                "TPSE");
    /**
     * tipo de registros de valores gerados.
     * os tipos usados por mes vao de 1 até 11. 
     * os demais são valores unicos, em outro registro.
     */
    $aSiglasTipoArquivo = array( 1 => "RTRT",
                                 2 => "RTPO" ,
                                 3 => "RTPP",
                                 4 => "RTDP",
                                 5 => "RTPA",
                                 6 => "RTIRF",
                                 7 => "RIP65",
                                 8 => "RIDAC",
                                 9 => "RIIRP",
                                10 => "RIAP",
                                11 => "MOLA",
                                12 => "RIMOG", 
                                13 => "SAUDE1", 
                                14 => "SAUDE2", 
                                15 => "RIO", 
                                );
     $aMeses            = array( 1 => "janeiro",
                                 2 => "fevereiro" ,
                                 3 => "marco",
                                 4 => "abril",
                                 5 => "maio",
                                 6 => "junho",
                                 7 => "julho",
                                 8 => "agosto",
                                 9 => "setembro",
                                10 => "outubro",
                                11 => "novembro",
                                12 => "dezembro", 
                                13 => "decimo_terceiro", 
                                ); 

    foreach ($aSiglasTipoArquivo as $sSigla) {
       //$aArquivosGerar[] = $sSigla;                               
    }
    

    $sSqlDadosInstituicao = " select z01_cgccpf as cgc,                
                                     z01_nome   as nomeinst,             
                                     z01_ender  as ender,               
                                     z01_telef  as telef,               
                                     z01_munic  as munic 
                                from orcunidade  
                               inner join rhlotaexe on rh26_orgao   = o41_orgao 
                                                   and rh26_unidade = o41_unidade 
                                                   and o41_anousu   = rh26_anousu 
                               inner join rhlota    on r70_codigo   = rh26_codigo
                               inner join cgm       on r70_numcgm   = z01_numcgm 
                               where o41_cnpj   = '{$this->sCnpj}'
                                 and z01_cgccpf = '{$this->sCnpj}'";
    $rsDadosInstituicao    = db_query($sSqlDadosInstituicao);
    $iNumRowsDadosInstituicao = pg_num_rows($rsDadosInstituicao);
    if ($iNumRowsDadosInstituicao > 0) {
  
     $oDadosInstituicao  = db_utils::fieldsMemory($rsDadosInstituicao, 0);

    } else {
        
     $oDadosInstituicao = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
     
    }
    
    require_once("dbforms/db_layouttxt.php");
    /**
     * processamos os pagamentos dos fornecedores
     */
    $sTipo = "1";
    
    if ($lGerarContabil) {
      $sTipo .= ", 2";
    }
    
    $sSqlTipoReceitas  = " SELECT distinct rh98_tipoirrf,                 ";
    $sSqlTipoReceitas .= "        length(trim(z01_cgccpf)) as tipopessoa, ";
    $sSqlTipoReceitas .= "        rh96_numcgm,                            ";
    $sSqlTipoReceitas .= "        trim(z01_cgccpf) as z01_cgccpf,         ";
    $sSqlTipoReceitas .= "        z01_nome,                               ";
    $sSqlTipoReceitas .= "        rh95_sequencial,                        ";
    $sSqlTipoReceitas .= "        case                                    ";
    $sSqlTipoReceitas .= "          when exists ( select 1               ";
    $sSqlTipoReceitas .= "                           from rhdirfgeracaodadospessoalvalor z ";
    $sSqlTipoReceitas .= "                                inner join rhdirfgeracaodadospessoal x on x.rh96_sequencial = z.rh98_rhdirfgeracaodadospessoal ";
    $sSqlTipoReceitas .= "                          where x.rh96_rhdirfgeracao   = rhdirfgeracaodadospessoal.rh96_rhdirfgeracao ";
    $sSqlTipoReceitas .= "                            and z.rh98_rhdirftipovalor = rhdirfgeracaodadospessoalvalor.rh98_rhdirftipovalor ";
    $sSqlTipoReceitas .= "                            and x.rh96_numcgm          = rhdirfgeracaodadospessoal.rh96_numcgm   ";
    $sSqlTipoReceitas .= "                            and x.rh96_tipo in (1,2)                                             ";
    $sSqlTipoReceitas .= "                            and z.rh98_tipoirrf < rhdirfgeracaodadospessoalvalor.rh98_tipoirrf   ";
    $sSqlTipoReceitas .= "                       ) then false                                                              ";
    $sSqlTipoReceitas .= "          else true                                                                              ";
    $sSqlTipoReceitas .= "        end as sem_retencao                                                                      ";
    $sSqlTipoReceitas .= "   from rhdirfgeracaodadospessoalvalor                                                           ";
    $sSqlTipoReceitas .= "        inner join rhdirfgeracaodadospessoal  on rh98_rhdirfgeracaodadospessoal      = rh96_sequencial ";
    $sSqlTipoReceitas .= "        left  join rhdirfgeracaopessoalregist on rh99_rhdirfgeracaodadospessoalvalor = rh98_sequencial ";
    $sSqlTipoReceitas .= "        inner join rhdirfgeracao              on rh96_rhdirfgeracao                  = rh95_sequencial ";
    $sSqlTipoReceitas .= "        inner join cgm                        on z01_numcgm                          = rh96_numcgm     ";
    $sSqlTipoReceitas .= "  where rh95_ano = {$this->iAno}                ";
    $sSqlTipoReceitas .= "    and (rh98_rhdirftipovalor in (6) ";

    if ($oDados->sAcima6000 == "S") {

      $sSqlTipoReceitas .= "      or  ((select sum(case when rh98_rhdirftipovalor <> 7 then z.rh98_valor else z.rh98_valor*(-1) end) as valor         ";
      $sSqlTipoReceitas .= "          from  rhdirfgeracaodadospessoalvalor z   ";        
      $sSqlTipoReceitas .= "                inner join rhdirfgeracaodadospessoal a ";
      $sSqlTipoReceitas .= "                          on z.rh98_rhdirfgeracaodadospessoal = a.rh96_sequencial ";
      $sSqlTipoReceitas .= "           inner join rhdirfgeracao b            on a.rh96_rhdirfgeracao  = b.rh95_sequencial ";
      $sSqlTipoReceitas .= "          where a.rh96_numcgm = rhdirfgeracaodadospessoal.rh96_numcgm ";
      $sSqlTipoReceitas .= "            and b.rh95_fontepagadora   = '{$this->sCnpj}' ";
      $sSqlTipoReceitas .= "            and b.rh95_ano   = {$this->iAno} "; 
      $sSqlTipoReceitas .= "            and z.rh98_rhdirftipovalor  in (1, 7, 12)"; 
      $sSqlTipoReceitas .= "            and a.rh96_tipo  = 1) >= ". $this->getValorLimite() .")";

    } else {

      $sSqlTipoReceitas .= "      or  ((select sum(z.rh98_valor) as valor         ";
      $sSqlTipoReceitas .= "          from  rhdirfgeracaodadospessoalvalor z   ";        
      $sSqlTipoReceitas .= "                inner join rhdirfgeracaodadospessoal a ";
      $sSqlTipoReceitas .= "                          on z.rh98_rhdirfgeracaodadospessoal = a.rh96_sequencial ";
      $sSqlTipoReceitas .= "           inner join rhdirfgeracao b            on a.rh96_rhdirfgeracao  = b.rh95_sequencial ";
      $sSqlTipoReceitas .= "          where a.rh96_numcgm = rhdirfgeracaodadospessoal.rh96_numcgm ";
      $sSqlTipoReceitas .= "            and b.rh95_fontepagadora   = '{$this->sCnpj}' ";
      $sSqlTipoReceitas .= "            and b.rh95_ano   = {$this->iAno} "; 
      $sSqlTipoReceitas .= "            and z.rh98_rhdirftipovalor  in (1)"; 
      $sSqlTipoReceitas .= "            and a.rh96_tipo  = 1) >= 0.01)";
    }

    $sSqlTipoReceitas .= "         ) ";
    $sSqlTipoReceitas .= "    and rh96_tipo in({$sTipo})                  ";
    $sSqlTipoReceitas .= "    and rh95_fontepagadora   = '{$this->sCnpj}' ";
    $sSqlTipoReceitas .= "    and rh95_ano             = {$this->iAno}    "; 
    
    $sMatriculaSelecionadas = $this->getMatriculas();
    if (!empty($sMatriculaSelecionadas)) {
      $sSqlTipoReceitas .= "  and rh99_regist in({$this->sMatriculas})";
    }
    
    $sSqlTipoReceitas .= "   order by rh98_tipoirrf,1,                    ";
    $sSqlTipoReceitas .= "            z01_cgccpf                          ";
    $rsTipoReceitas    = db_query($sSqlTipoReceitas);
    $iTotalLinhas      = pg_num_rows($rsTipoReceitas);
    
    $aLinhasDirf       = array();
    
    for ($i = 0; $i < $iTotalLinhas; $i++) {
       
      $oTipoReceita = db_utils::fieldsMemory($rsTipoReceitas, $i);
      
      if (!isset($aLinhasDirf[$oTipoReceita->rh98_tipoirrf])) {

        $oLinhaDirf = new stdClass();
        $oLinhaDirf->receita  = $oTipoReceita->rh98_tipoirrf;
        $oLinhaDirf->fisica   = array();
        $oLinhaDirf->juridica = array();
        
        $aLinhasDirf[$oTipoReceita->rh98_tipoirrf] = $oLinhaDirf;
      }
      
      $oPessoa = new stdClass();
      $oPessoa->nome        = $oTipoReceita->z01_nome;
      $oPessoa->cgm         = $oTipoReceita->rh96_numcgm;
      $oPessoa->totalsaude1 = 0;
      $oPessoa->totalsaude2 = 0;
      $oPessoa->totaloutros = 0;
      
      $this->calculaValoresMensaisTipo($oTipoReceita->rh95_sequencial, $oPessoa, $oTipoReceita->rh98_tipoirrf,($oTipoReceita->sem_retencao=='t'?true:false));
      
      if ($oTipoReceita->tipopessoa == 11) {
        
        if (!isset($aLinhasDirf[$oTipoReceita->rh98_tipoirrf]->fisica[$oTipoReceita->rh96_numcgm])) {

          $oPessoa->portadormolestia = false;
          $oPessoa->deficientefisico = false;
          $oPessoa->datalaudo        = '';
          
          $sSqlMolestias  = "SELECT rh02_deficientefisico, ";
          $sSqlMolestias .= "       rh02_portadormolestia, ";
          $sSqlMolestias .= "       rh02_datalaudomolestia ";
          $sSqlMolestias .= "  from rhpessoal ";
          $sSqlMolestias .= "       inner join rhpessoalmov on rh01_regist = rh02_regist ";
          $sSqlMolestias .= " where rh02_anousu = ".$this->iAno;
          $sSqlMolestias .= "   and rh02_mesusu = ".$this->iMes;
          $sSqlMolestias .= "   and rh01_numcgm = {$oTipoReceita->rh96_numcgm}";
          
          $rsMolestias   = db_query($sSqlMolestias);
          
          if ($rsMolestias && pg_num_rows($rsMolestias) > 0) {
            
            $oDadosMolestia = db_utils::fieldsMemory($rsMolestias, 0);
            $oPessoa->portadormolestia = $oDadosMolestia->rh02_portadormolestia=="t"?true:false;
            $oPessoa->deficientefisico = $oDadosMolestia->rh02_deficientefisico=="t"?true:false;
            $oPessoa->datalaudo        = $oDadosMolestia->rh02_datalaudomolestia;
          }
          
          $oPessoa->cpf        = $oTipoReceita->z01_cgccpf;
          $oPessoa->data_laudo = "";
          $aLinhasDirf[$oTipoReceita->rh98_tipoirrf]->fisica[$oTipoReceita->rh96_numcgm] = $oPessoa;
        }  
      } else if ($oTipoReceita->tipopessoa == 14) {
        
        if (!isset($aLinhasDirf[$oTipoReceita->rh98_tipoirrf]->juridica[$oTipoReceita->rh96_numcgm])) {
          
          $oPessoa->cnpj = $oTipoReceita->z01_cgccpf;
          $aLinhasDirf[$oTipoReceita->rh98_tipoirrf]->juridica[$oTipoReceita->rh96_numcgm] = $oPessoa;
        }  
      }
      unset($oTipoReceita);
    }
    
    $sNomeArquivo  = "dirf_{$this->iAno}_{$this->sCnpj}.txt";
    $iCodigoLayout = $this->getCodigoLayout(); 
    $oLayout       = new db_layouttxt($iCodigoLayout, "tmp/{$sNomeArquivo}", implode(" ", $aArquivosGerar));
    
    /**
     * escrevemos o header do txt
     */
    $oLayout->setCampoTipoLinha(1);
    $oLayout->setCampoIdentLinha("Dirf");
    $oLayout->setCampo("identificador_registro", 'Dirf');
    $oLayout->setCampo("ano_referencia", $this->iAno+1);
    $oLayout->setCampo("ano_calendario", $this->iAno);
    
    $sRetificadora = 'N';
    
    if ($oDados->TipoDeclaracao == "R") {
      $sRetificadora = 'S';
    }
    
    $oLayout->setCampo("idetificador_retificadora", $sRetificadora);
    $oLayout->setCampo("numero_recibo", $oDados->iNumeroRecibo);
    $oLayout->setCampo("identificador_estrutura_layout", $this->getCodigoArquivo());
    $oLayout->geraDadosLinha();

    $oLayout->setCampoTipoLinha(3);
    $oLayout->setCampoIdentLinha("RESPO");
    $oLayout->setCampo("identificador_registro", 'RESPO');
    $oLayout->setCampo("cpf", $oDados->sCpfResponsavel);
    $oLayout->setCampo("nome", urldecode(db_stdClass::db_stripTagsJson($oDados->sNomeResponsavel)));
    $oLayout->setCampo("ddd", $oDados->sDDDResponsavel);
    $oLayout->setCampo("telefone", $oDados->sFoneResponsavel);
    $oLayout->geraDadosLinha();
    
    $oLayout->setCampoTipoLinha(3);
    $oLayout->setCampoIdentLinha("DECPJ");
    $oLayout->setCampo("identificador_registro", 'DECPJ');
    $oLayout->setCampo("responsavel_perante_cnpj", $oDados->sCpfResponsavelCNPJ);
    $oLayout->setCampo("cnpj", $this->sCnpj);
    $oLayout->setCampo("nome_empresarial", $oDadosInstituicao->nomeinst);
    if ($oDados->iNumeroANS > 0) {
      $oLayout->setCampo("plano_privado_assistencia", "S");
    }
    $oLayout->geraDadosLinha();
    foreach ($aLinhasDirf as $oLinhaDirf) {
      
      $oLayout->setCampoTipoLinha(3);
      $oLayout->setCampoIdentLinha("IDREC");
      
      $oLayout->setCampo("identificador_registro", 'IDREC');
      $oLayout->setCampo("codigo_receita", $oLinhaDirf->receita);
      $oLayout->geraDadosLinha();
      
      foreach ($oLinhaDirf->fisica as $oPessoaFisica) {
        
        $oLayout->setCampoTipoLinha(3);
        $oLayout->setCampoIdentLinha("BPFDEC");
        $oLayout->setCampo("identificador_registro", 'BPFDEC');
        $oLayout->setCampo("nome", $oPessoaFisica->nome);
        $oLayout->setCampo("cpf",  $oPessoaFisica->cpf);
        $oLayout->setCampo("data_laudo",  $oPessoaFisica->data_laudo);
        $oLayout->geraDadosLinha();
        
        /**
         * carregamos as informações dos pagamentos
         */
        foreach ($oPessoaFisica->pagamentos as $iTipo => $oPagamento) {

          $oLayout->setCampoTipoLinha(3);
          $oLayout->setCampoIdentLinha("RTRT");
          $iSiglaRegistro = $aSiglasTipoArquivo[$iTipo];
          $oLayout->setCampo("idetificador_registro", $iSiglaRegistro);
          
          /**
           * escreve os meses com cada valor
           */
          for ($iMes = 1; $iMes <= 13; $iMes++) {
        
            $aMes[$iMes] = '';
            foreach ($oPagamento as $oMes) {
              
              if ($oMes->rh98_mes == $iMes) {

                $nValorDeducao65 = 0;
                
                if ($oMes->rh98_rhdirftipovalor == 1) {
                  $nValorDeducao65 = $this->getValorDeducaoRIP65($iMes,$oPessoaFisica->pagamentos);                 
                }
                
                $nValorLancar = ( ( $oMes->valor - $nValorDeducao65 ) > 0 ? ( $oMes->valor - $nValorDeducao65 ) : 0  );
                                
                $aMes[$iMes] = db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($nValorLancar,'f')))),'s','0',8,'e',2); 
              }
            }
            $oLayout->setCampo($aMeses[$iMes], $aMes[$iMes]);
          }
          $oLayout->geraDadosLinha();
        }
        /*
         * Outros dados.
         */
        if($oPessoaFisica->totaloutros > 0){
          $nValorAno = db_formatar(str_replace(',','',str_replace('.','', 
                                                      trim(db_formatar($oPessoaFisica->totaloutros,'f')))),'s','0',13,'e',2);

          $oLayout->setCampoTipoLinha(3);
          $oLayout->setCampoIdentLinha("RIO");
          $oLayout->setCampo("identificador_registro", "RIO");
          $oLayout->setCampo("valor_anual", $nValorAno);               
          $oLayout->setCampo("descricao_rend_isentos", "");
          $oLayout->geraDadosLinha();
        }
      }
      
      foreach ($oLinhaDirf->juridica as $oPessoaFisica) {
        
        $oLayout->setCampoTipoLinha(3);
        $oLayout->setCampoIdentLinha("BPJDEC");
        $oLayout->setCampo("identificador_registro", 'BPJDEC');
        $oLayout->setCampo("nome", $oPessoaFisica->nome);
        $oLayout->setCampo("cnpj", $oPessoaFisica->cnpj);
        $oLayout->geraDadosLinha();
       /**
        * carregamos as informações dos pagamentos
        */
       foreach ($oPessoaFisica->pagamentos as $iTipo => $oPagamento) {
        
          $oLayout->setCampoTipoLinha(3);
          $oLayout->setCampoIdentLinha("RTRT");
          
          $oLayout->limpaCampos ();
          
          $iSiglaRegistro = $aSiglasTipoArquivo[$iTipo];
        
          
          $oLayout->setCampo("idetificador_registro", $iSiglaRegistro);
          /**
           * escreve os meses com cada valor
           */
          foreach ($oPagamento as $oMes) {
            $oLayout->setCampo($aMeses[$oMes->rh98_mes],db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($oMes->valor,'f')))),'s','0',8,'e',2));
          }
          $oLayout->geraDadosLinha();
        }
      }
    }
    
    /**
     * geramos as linhas do plano de saude
     */
    
    if (trim($oDados->iNumeroANS) != "" || trim($oDados->iNumeroANS2) != "") {

      $oLayout->setCampoTipoLinha(3);
      $oLayout->setCampoIdentLinha("PSE");
      $oLayout->setCampo("identificador_registro", 'PSE');
      $oLayout->geraDadosLinha();
     
      if (trim($oDados->iNumeroANS) != "") {
      
        $oDaoCgm   = db_utils::getDao("cgm");
        $sSqlNome  = $oDaoCgm->sql_query_file($oDados->iCcgmSaude, "z01_nome, z01_cgccpf");
        $rsNome    = $oDaoCgm->sql_record($sSqlNome);
        $oOperador = db_utils::fieldsMemory($rsNome, 0);
        
        $oLayout->setCampoTipoLinha(3);
        $oLayout->setCampoIdentLinha("OPSE");
        $oLayout->setCampo("identificador_registro", 'OPSE');
        $oLayout->setCampo("cnpj", str_pad($oOperador->z01_cgccpf, 14, "0", STR_PAD_LEFT));
        $oLayout->setCampo("nome", $oOperador->z01_nome);
        $oLayout->setCampo("registro_ans", str_pad($oDados->iNumeroANS, 6, "0", STR_PAD_LEFT));
        $oLayout->geraDadosLinha();
        
        /**
         * geramos todas as pessoas que possuem valor do plano de saude maior que zero.
         */
        foreach ($aLinhasDirf as $oLinhaDirf) {
          
          foreach ($oLinhaDirf->fisica as $oPessoaFisica) {
      
            if ($oPessoaFisica->totalsaude1 > 0) {
              
              $nValorAno = db_formatar(str_replace(',','',str_replace('.','', 
                                                          trim(db_formatar($oPessoaFisica->totalsaude1,'f')))),'s','0',13,'e',2);
              $oLayout->setCampoTipoLinha(3);
              $oLayout->setCampoIdentLinha("TPSE");
              $oLayout->setCampo("identificador_registro", 'TPSE');
              $oLayout->setCampo("cnpj", str_pad($oPessoaFisica->cpf, 11, "0", STR_PAD_LEFT));
              $oLayout->setCampo("nome", $oPessoaFisica->nome);
              $oLayout->setCampo("valor_ano", $nValorAno);               
              $oLayout->geraDadosLinha();
            }
            /*
            if ($oPessoaFisica->totalsaude2 > 0) {
              
              $nValorAno = db_formatar(str_replace(',','',str_replace('.','', 
                                                          trim(db_formatar($oPessoaFisica->totalsaude2,'f')))),'s','0',15,'e',2);
              $oLayout->setCampoTipoLinha(3);
              $oLayout->setCampoIdentLinha("TPSE");
              $oLayout->setCampo("identificador_registro", 'TPSE');
              $oLayout->setCampo("cnpj", str_pad($oPessoaFisica->cpf, 11, "0", STR_PAD_LEFT));
              $oLayout->setCampo("nome", $oPessoaFisica->nome);
              $oLayout->setCampo("valor_ano", $nValorAno);               
              $oLayout->geraDadosLinha();
            }
            */
          }
        }
      }
      if (trim($oDados->iNumeroANS2) != "") {
      
        $oDaoCgm   = db_utils::getDao("cgm");
        $sSqlNome  = $oDaoCgm->sql_query_file($oDados->iCcgmSaude2, "z01_nome, z01_cgccpf");
        $rsNome    = $oDaoCgm->sql_record($sSqlNome);
        $oOperador = db_utils::fieldsMemory($rsNome, 0);
        
        $oLayout->setCampoTipoLinha(3);
        $oLayout->setCampoIdentLinha("OPSE");
        $oLayout->setCampo("identificador_registro", 'OPSE');
        $oLayout->setCampo("cnpj", str_pad($oOperador->z01_cgccpf, 14, "0", STR_PAD_LEFT));
        $oLayout->setCampo("nome", $oOperador->z01_nome);
        $oLayout->setCampo("registro_ans", str_pad($oDados->iNumeroANS2, 6, "0", STR_PAD_LEFT));
        $oLayout->geraDadosLinha();
        
        /**
         * geramos todas as pessoas que possuem valor do plano de saude maior que zero.
         */
        foreach ($aLinhasDirf as $oLinhaDirf) {
          
          foreach ($oLinhaDirf->fisica as $oPessoaFisica) {
      
            if ($oPessoaFisica->totalsaude2 > 0) {
              
              $nValorAno = db_formatar(str_replace(',','',str_replace('.','', 
                                                          trim(db_formatar($oPessoaFisica->totalsaude2,'f')))),'s','0',13,'e',2);
              $oLayout->setCampoTipoLinha(3);
              $oLayout->setCampoIdentLinha("TPSE");
              $oLayout->setCampo("identificador_registro", 'TPSE');
              $oLayout->setCampo("cnpj", str_pad($oPessoaFisica->cpf, 11, "0", STR_PAD_LEFT));
              $oLayout->setCampo("nome", $oPessoaFisica->nome);
              $oLayout->setCampo("valor_ano", $nValorAno);               
              $oLayout->geraDadosLinha();
            }
          }
        }
      }
    }
    
    $oLayout->setCampoTipoLinha(4);
    $oLayout->setCampoIdentLinha("FIMDirf");
    $oLayout->setCampo("identificador_registro", 'FIMDirf');
    $oLayout->geraDadosLinha();
    return $sNomeArquivo;
  }
  
  protected function calculaValoresMensaisTipo($iCodigoDirf, $oPessoa, $iTipoIRRF,$lSemRetencao=true) {
    
    $sSqlPagamentos  = " select rh98_rhdirftipovalor,                 "; 
    $sSqlPagamentos .= "        sum(rh98_valor) as valor,             ";
    $sSqlPagamentos .= "        rh98_mes                              ";
    $sSqlPagamentos .= "  from  rhdirfgeracaodadospessoalvalor        ";
    $sSqlPagamentos .= "        inner join rhdirfgeracaodadospessoal on rh98_rhdirfgeracaodadospessoal = rh96_sequencial";
    $sSqlPagamentos .= "  where rh96_numcgm        = {$oPessoa->cgm}  ";
    $sSqlPagamentos .= "    and rh96_rhdirfgeracao = {$iCodigoDirf}   ";
    
    if ( $lSemRetencao ) {
      $sSqlPagamentos .= "  and rh98_tipoirrf in ('0','{$iTipoIRRF}') ";
    } else {
      $sSqlPagamentos .= "  and rh98_tipoirrf in ('{$iTipoIRRF}')     ";
    }
    
    $sSqlPagamentos .= "  group by rh98_rhdirftipovalor,              ";
    $sSqlPagamentos .= "        rh98_mes                              ";
    $sSqlPagamentos .= "        having sum(rh98_valor) > 0            "; 
    $sSqlPagamentos .= "  order by rh98_rhdirftipovalor,rh98_mes      ";
    
    $rsPagamentos = db_query($sSqlPagamentos);
    $aPagamentos  = db_utils::getColectionByRecord($rsPagamentos);
    
    foreach ($aPagamentos as $oPagamento) {
      
      /*
       * 13 é pagamento de plano de saude.
       */
      if (   $oPagamento->rh98_rhdirftipovalor != 13 
          && $oPagamento->rh98_rhdirftipovalor != 14 
          && $oPagamento->rh98_rhdirftipovalor != 15 
         ) {
        
        if (!isset($oPessoa->pagamentos[$oPagamento->rh98_rhdirftipovalor])) {
          $oPessoa->pagamentos[$oPagamento->rh98_rhdirftipovalor] = array();
        }
         
        $oPessoa->pagamentos[$oPagamento->rh98_rhdirftipovalor][] = $oPagamento;
        
      } elseif($oPagamento->rh98_rhdirftipovalor == 13) {
      
        $oPessoa->totalsaude1 += $oPagamento->valor;
      
      } elseif($oPagamento->rh98_rhdirftipovalor == 14) {
      
        $oPessoa->totalsaude2 += $oPagamento->valor;
      } elseif($oPagamento->rh98_rhdirftipovalor == 15) {
      
        $oPessoa->totaloutros += $oPagamento->valor;
      }
    }
    
    unset($aPagamentos);
  }
  
  
  protected function getValorDeducaoRIP65($iMes,$aPagamentos) {
    
    foreach ($aPagamentos as $aDadosMeses) {
          
      foreach ($aDadosMeses as $oDadosMes) {
                
        if ($oDadosMes->rh98_mes == $iMes && $oDadosMes->rh98_rhdirftipovalor == 7) {              
          return $oDadosMes->valor;
        }
        
      } 
                       
    }
    return 0;
          
  }
  
  
  /**
   *  Adiciona inconsistências 
   */
  protected function addInconsistente($iNumCgm,$sNome,$sMotivo) {
    
    $oInconsistencia = new stdClass();
    $oInconsistencia->iNumCgm = $iNumCgm;
    $oInconsistencia->sNome   = $sNome;
    $oInconsistencia->sMotivo = $sMotivo;
    
    $this->aInconsistentes[$iNumCgm] = $oInconsistencia;
  }
  
  protected function clearInconsistente() {

    $this->aInconsistentes = array();
  }  
  
  /**
   * Verifica se existe inconsistências no processamento 
   */
  public function hasInconsistencias() {
    
    if ( count($this->aInconsistentes) > 0  ) {
      return true;
    } else {
      return false;
    }
  }
  
  /**
   *  Gerar relatório de Inconsistências
   */
  public function geraArquivoInconsistencias(){

    global $head2;
    
    $sNomeArquivo = "tmp/inconsistencias_dirf_".date('Ymdi').".pdf"; 
    
    $oPdf = new PDF();
    $oPdf->Open();
    $oPdf->AliasNbPages();
    $oPdf->SetFillColor(235);
    
    $iFonte    = 8;
    $iAlt      = 5;
    $iPreenche = 1;
    
    $head2 = "Relatório de Inconsistências";
    
    $oPdf->AddPage();
    $oPdf->SetFont('Arial','b',$iFonte);
    $oPdf->Cell(30 ,$iAlt,"CGM"   ,1,0,'C',1);
    $oPdf->Cell(120,$iAlt,"Nome"  ,1,0,'C',1);
    $oPdf->Cell(40 ,$iAlt,"Motivo",1,1,'C',1);    
    
    foreach ($this->aInconsistentes as $oInconsistencia ) {

      if ($oPdf->gety() > $oPdf->h - 30) {
        
        $oPdf->AddPage();
        $oPdf->SetFont('Arial','b',$iFonte);
        $oPdf->Cell(30 ,$iAlt,"CGM"   ,1,0,'C',1);
        $oPdf->Cell(120,$iAlt,"Nome"  ,1,0,'C',1);
        $oPdf->Cell(40 ,$iAlt,"Motivo",1,1,'C',1);        
      }
      
      if ($iPreenche == 1 ) {
        $iPreenche = 0;
      } else {
        $iPreenche = 1;
      }
      
      $oPdf->SetFont('Arial','',$iFonte);
      $oPdf->Cell(30 ,$iAlt,$oInconsistencia->iNumCgm ,0,0,'C',$iPreenche);
      $oPdf->Cell(120,$iAlt,$oInconsistencia->sNome   ,0,0,'L',$iPreenche);
      $oPdf->Cell(40 ,$iAlt,$oInconsistencia->sMotivo ,0,1,'L',$iPreenche);
    }
    
    ob_start();
    $oPdf->Output($sNomeArquivo);    
    ob_end_clean();
    
    return $sNomeArquivo;
  }

  /**
   * retorna os dados das unidades com cnj diferente da instituição
   *
   * @return array
   */
  function retornarUnidadesCnpjInvalido() {

    $sSqlVerificaUnidades  = "select * from (SELECT distinct ";
    $sSqlVerificaUnidades .= "                      o41_orgao, ";
    $sSqlVerificaUnidades .= "                      o41_unidade, ";
    $sSqlVerificaUnidades .= "                      o41_descr, ";
    $sSqlVerificaUnidades .= "                      o41_cnpj , ";
    $sSqlVerificaUnidades .= "                      cgc as cnpj_instituicao, ";
    $sSqlVerificaUnidades .= "                      codigo, ";
    $sSqlVerificaUnidades .= "                      o41_anousu ";
    $sSqlVerificaUnidades .= "                 from orcunidade ";
    $sSqlVerificaUnidades .= "                      inner join orcdotacao on o41_orgao   = o58_orgao ";
    $sSqlVerificaUnidades .= "                                           and o41_unidade = o58_unidade ";
    $sSqlVerificaUnidades .= "                                           and o41_anousu  = o58_anousu ";
    $sSqlVerificaUnidades .= "                      inner join empempenho on o58_coddot  = e60_coddot ";
    $sSqlVerificaUnidades .= "                                           and o58_anousu  = e60_anousu ";
    $sSqlVerificaUnidades .= "                      inner join db_config  on o41_instit  = codigo ";
    $sSqlVerificaUnidades .= "                order by o41_orgao,o41_unidade) as x ";
    $sSqlVerificaUnidades .= " where o41_cnpj <> cnpj_instituicao ";
    $rsVerificacao         = db_query($sSqlVerificaUnidades);
    return db_utils::getColectionByRecord($rsVerificacao, false, false, true);
  }
  
  function retornaMatriculasDirf($lGerarContabil=true) {
    
    $sTipo = "1";
    if ($lGerarContabil) {
      $sTipo .= ", 2";
    }
    
    $sSqlMatriculasDirf  = " select distinct rh99_regist, ";
    $sSqlMatriculasDirf .= "        z01_nome              ";
    $sSqlMatriculasDirf .= "   from rhdirfgeracaodadospessoalvalor                                                                 ";
    $sSqlMatriculasDirf .= "        inner join rhdirfgeracaodadospessoal  on rh98_rhdirfgeracaodadospessoal      = rh96_sequencial ";
    $sSqlMatriculasDirf .= "        left  join rhdirfgeracaopessoalregist on rh99_rhdirfgeracaodadospessoalvalor = rh98_sequencial ";
    $sSqlMatriculasDirf .= "        inner join rhdirfgeracao              on rh96_rhdirfgeracao                  = rh95_sequencial ";
    $sSqlMatriculasDirf .= "        inner join cgm                        on z01_numcgm                          = rh96_numcgm     ";
    $sSqlMatriculasDirf .= "  where rh95_ano = {$this->iAno}                ";
    $sSqlMatriculasDirf .= "    and (rh98_rhdirftipovalor in (6) ";

    if ($oDados->sAcima6000 == "S") {

      $sSqlMatriculasDirf .= "      or  ((select sum(case when rh98_rhdirftipovalor <> 7 then z.rh98_valor else z.rh98_valor*(-1) end) as valor         ";
      $sSqlMatriculasDirf .= "          from  rhdirfgeracaodadospessoalvalor z   ";        
      $sSqlMatriculasDirf .= "                inner join rhdirfgeracaodadospessoal a ";
      $sSqlMatriculasDirf .= "                          on z.rh98_rhdirfgeracaodadospessoal = a.rh96_sequencial ";
      $sSqlMatriculasDirf .= "           inner join rhdirfgeracao b            on a.rh96_rhdirfgeracao  = b.rh95_sequencial ";
      $sSqlMatriculasDirf .= "          where a.rh96_numcgm = rhdirfgeracaodadospessoal.rh96_numcgm ";
      $sSqlMatriculasDirf .= "            and b.rh95_fontepagadora   = '{$this->sCnpj}' ";
      $sSqlMatriculasDirf .= "            and b.rh95_ano   = {$this->iAno} "; 
      $sSqlMatriculasDirf .= "            and z.rh98_rhdirftipovalor  in (1, 7, 12)"; 
      $sSqlMatriculasDirf .= "            and a.rh96_tipo  = 1) >= ". $this->getValorLimite() .")";

    } else {

      $sSqlMatriculasDirf .= "      or  ((select sum(z.rh98_valor) as valor         ";
      $sSqlMatriculasDirf .= "          from  rhdirfgeracaodadospessoalvalor z   ";        
      $sSqlMatriculasDirf .= "                inner join rhdirfgeracaodadospessoal a ";
      $sSqlMatriculasDirf .= "                          on z.rh98_rhdirfgeracaodadospessoal = a.rh96_sequencial ";
      $sSqlMatriculasDirf .= "           inner join rhdirfgeracao b            on a.rh96_rhdirfgeracao  = b.rh95_sequencial ";
      $sSqlMatriculasDirf .= "          where a.rh96_numcgm = rhdirfgeracaodadospessoal.rh96_numcgm ";
      $sSqlMatriculasDirf .= "            and b.rh95_fontepagadora   = '{$this->sCnpj}' ";
      $sSqlMatriculasDirf .= "            and b.rh95_ano   = {$this->iAno} "; 
      $sSqlMatriculasDirf .= "            and z.rh98_rhdirftipovalor  in (1)"; 
      $sSqlMatriculasDirf .= "            and a.rh96_tipo  = 1) >= 0.01)";
    }

    $sSqlMatriculasDirf .= "          ) ";
    $sSqlMatriculasDirf .= "    and rh96_tipo in({$sTipo})                  ";
    $sSqlMatriculasDirf .= "    and rh95_fontepagadora   = '{$this->sCnpj}' ";
    $sSqlMatriculasDirf .= "    and rh95_ano             = {$this->iAno}    "; 
    $sSqlMatriculasDirf .= "   order by rh99_regist,                        ";
    $sSqlMatriculasDirf .= "            z01_nome                            ";
    
    $rsMatriculasDirf    = db_query($sSqlMatriculasDirf);
    $iTotalLinhas        = pg_num_rows($rsMatriculasDirf);
    $aMatriculasDirf     = array();
    if ($iTotalLinhas > 0) {
      $aMatriculasDirf = db_utils::getColectionByRecord($rsMatriculasDirf);
    }
    
    return $aMatriculasDirf;
  }

}
?>