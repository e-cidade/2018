<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


class DeclaracaoQuitacao {

  /**
   * Exercicio da declaração de quitação
   * @var integer
   */
  protected $iExercicio;

  /**
   * Origem da declaração
   * 1 - Cgm
   * 2 - Matricula
   * 3 - Inscrição 
   * @var integer
   */
  protected $iOrigem;

  /**
   * Tipo Cgm
   * true  - somente cgm
   * false - cgm geral
   * @var boolean
   */
  protected $lTipoCgm = false;

  /**
   * Codigo da origem generico. Pode ser nro da matricula, inscrição ou cgm
   * @var integer
   */
  protected $iCodOrigem;

  /**
   * Codigo da declaracao de quitacao
   * @var integer
   */
  protected $iCodDeclaracao;

  /**
   * Numero da matricula
   * @var integer
   */
  protected $iMatricula;

  /**
   * Numero do cgm
   * @var integer
   */
  protected $iCgm;

  /**
   * Numero da inscricao
   * @var integer
   */
  protected $iInscricao;

  protected $iSituacao;

  protected $sUsuario;

  protected $dData;

  protected $sNomeOrigem;

  protected $sNomeCgm;

  /**
   * lista dos debitos da declaracao de quitacao
   * @var array
   */
  protected $aDebitos = array();

  /**
   * array com o codigo de todas as declarações requisitadas na operação
   * @var array
   */
  protected $aDeclaracoes = array();
  
  protected $aDebitosDeclaracao = array();

  /**
   * Método construtor do model declaracao de quitacao
   * Caso seja passado o parametro código, carrega a declaracao informada 
   * @param integer $iCodDeclaracao
   */
  public function __construct($iCodDeclaracao = null) {
     
    if(!empty($iCodDeclaracao)) {

      return $this->carregar($iCodDeclaracao);

    }
  }

  public function getDataDeclaracao() {
    
    return $this->dData;
    
  }
  
  public function getNomeCgm() {
    
    return $this->sNomeCgm;
    
  }

  public function getNomeOrigem() {
    
    return $this->sNomeOrigem;
    
  }

  public function getSituacao() {
    
    return $this->iSituacao;
    
  }
  
  public function getAnoMesImpressao() {
  
  	return $this->sAnoMesImpressao;
  
  }

  public function getUsuario() {
    
    return $this->sUsuario;
    
  }
  
  public function getCodDeclaracao() {
    
    return $this->iCodDeclaracao;
    
  }

  public function setExercicio($iExercicio = null) {
    
    $this->iExercicio = $iExercicio;
    
  }
  
  public function getExercicio() {
    
    return $this->iExercicio;
    
  }
  
  public function getDebitosDeclaracao() {

    return $this->aDebitosDeclaracao;
    
  }

  /**
   * Seta o codigo da origem genericamente.
   * Pode ser o nro do cgm, matricula ou inscricao
   * @param integer $iCodOrigem
   */
  public function setCodOrigem($iCodOrigem) {
    
    $this->iCodOrigem = $iCodOrigem;
    
  }
  
  public function getCodOrigem() {
    
    return $this->iCodOrigem;
    
  }

  /**
   * Origem da declaração 1 - Cgm 2 - Matricula 3 - Inscrição 
   * @param integer $iOrigem
   */
  public function setOrigem($iOrigem) {
    
    $this->iOrigem = $iOrigem;
    
  }

  public function getOrigem(){
    
    return $this->iOrigem;
    
  }

  public function setMatricula($iMatricula) {
    
    $this->iMatricula = $iMatricula;

    $this->iCodOrigem = $iMatricula;
    
  }

  public function setCgm($iCgm) {
    
    $this->iCgm = $iCgm;

    $this->iCodOrigem = $iCgm;
    
  }

  public function setInscricao($iInscricao) {
    
    $this->iInscricao = $iInscricao;

    $this->iCodOrigem = $iInscricao;
    
  }

  public function setTipoCgm($lTipoCgm) {
    
    $this->lTipoCgm = $lTipoCgm;
    
  }

  /**
   * Grava o registro da declaracao de quitacao
   */
  public function salvar() {

    if(empty($this->iExercicio)) {

      throw new Exception('Exercício da declaração não informado');

    }

    if($this->declaracaoGerada()) {
      
      $this->setArrayDeclaracoes($this->iCodDeclaracao);

      return false;

    }


    $oDaoDeclQuitacao = db_utils::getDao('declaracaoquitacao');

    $oDaoDeclQuitacao->ar30_exercicio  = $this->iExercicio;
    $oDaoDeclQuitacao->ar30_situacao   = 1;
    $oDaoDeclQuitacao->ar30_data       = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoDeclQuitacao->ar30_id_usuario = db_getsession('DB_id_usuario');
    $oDaoDeclQuitacao->ar30_instit     = db_getsession('DB_instit');
    $oDaoDeclQuitacao->incluir(null);

    if($oDaoDeclQuitacao->erro_status == '0') {

      $sErro = "Erro ao incluir na tabela declaracaoquitacao. Operação abortada. ERRO:{$oDaoDeclQuitacao->erro_msg}";
      
      throw new Exception($sErro);

    }

    $this->iCodDeclaracao = $oDaoDeclQuitacao->ar30_sequencial;

    $this->setArrayDeclaracoes($this->iCodDeclaracao);

    $this->salvarOrigem();

    $this->salvarDebitos();

  }

  /**
   * testa se ja foi registrada a declaracao referente ao ano, origem e instituição informada. 
   * Caso tenha sido registrada retorna o codigo da declaracao caso contrário, retorna falso
   */
  public function declaracaoGerada(){

    if(empty($this->iExercicio)) {

      throw new Exception('Exercicio da declaração não informado.');

    }

    if(empty($this->iOrigem)) {

      throw new Exception('Origem não informada.');

    }

    if($this->iOrigem == 1) {

      $oDaoDeclQuitacaoCgm = db_utils::getDao('declaracaoquitacaocgm');

      $sWhere  = '     ar30_exercicio  = ' .  $this->iExercicio;
      $sWhere .= ' and ar30_situacao   = 1';
      $sWhere .= ' and ar30_instit     = ' .  db_getsession('DB_instit');
      $sWhere .= ' and ar34_numcgm     = ' .  $this->iCgm;
      $sWhere .= ' and ar34_somentecgm = ' . ($this->lTipoCgm == true ? 'true' : 'false');
      
      
      $sSql   = $oDaoDeclQuitacaoCgm->sql_query(null, 'ar30_sequencial', null, $sWhere);

    } else if($this->iOrigem == 2) {

      $oDaoDeclQuitacaoMatric = db_utils::getDao('declaracaoquitacaomatric');
      
      $sWhere  = '     ar30_exercicio = ' . $this->iExercicio; 
      $sWhere .= ' and ar30_situacao  = 1';
      $sWhere .= ' and ar30_instit    = ' . db_getsession('DB_instit');
      $sWhere .= ' and ar33_matric    = ' . $this->iMatricula;
      
      $sSql = $oDaoDeclQuitacaoMatric->sql_query(null, 'ar30_sequencial', null, $sWhere);

    } else if($this->iOrigem == 3) {

      $oDaoDeclQuitacaoInscr = db_utils::getDao('declaracaoquitacaoinscr');
      
      $sWhere  = '     ar30_exercicio = '. $this->iExercicio;
      $sWhere .= ' and ar30_situacao  = 1';
      $sWhere .= ' and ar30_instit    = '. db_getsession('DB_instit');
      $sWhere .= ' and ar35_inscr     = '. $this->iInscricao;
      
      $sSql = $oDaoDeclQuitacaoInscr->sql_query(null, 'ar30_sequencial', null, $sWhere);

    }

    $oDaoDeclaracaoQuitacao = db_utils::getDao('declaracaoquitacao');

    $rDaoDeclaracaoQuitacao = $oDaoDeclaracaoQuitacao->sql_record($sSql);

    if($oDaoDeclaracaoQuitacao->numrows > 0) {

      $this->iCodDeclaracao = db_utils::fieldsMemory($rDaoDeclaracaoQuitacao, 0)->ar30_sequencial;

      return true;
       
    } else {

      return false;
    }

  }

  /**
   * lista os débitos informados na declaracao de quitacao
   */
  public function carregaDebitos() {

    $oDaoDeclQuitacao  = db_utils::getDao('declaracaoquitacao');
    
    $sSqlDeclQuitacao  = $oDaoDeclQuitacao->sql_query_debitos_arrecad($this->iExercicio, 
                                                                      $this->iOrigem, 
                                                                      $this->iCodOrigem, 
                                                                      $this->lTipoCgm); 
    
    $rDaoDeclQuitacao = $oDaoDeclQuitacao->sql_record($sSqlDeclQuitacao);

    if($oDaoDeclQuitacao->numrows > 0) {

      $this->aDebitos = db_utils::getColectionByRecord($rDaoDeclQuitacao, false, false, true);;

    }

  }

  /**
   * salva os débitos da declaracao de quitacao referente ao ano
   */
  public function salvarDebitos() {

    $oDaoDeclQuitacaoReg = db_utils::getDao('declaracaoquitacaoreg');
    
    $this->carregaDebitos();

    if(empty($this->iCodDeclaracao)) {
      throw new Exception('Código da declaração de quitação não informado');
    }
    
    if(count($this->aDebitos) > 0) {

       
      for($i = 0; $i < count($this->aDebitos); $i++) {
         
        $oDaoDeclQuitacaoReg->ar31_declaracaoquitacao  = $this->iCodDeclaracao;
        $oDaoDeclQuitacaoReg->ar31_numpre              = $this->aDebitos[$i]->numpre;
        $oDaoDeclQuitacaoReg->ar31_numpar              = $this->aDebitos[$i]->numpar;
        $oDaoDeclQuitacaoReg->ar31_receita             = $this->aDebitos[$i]->receita;
        $oDaoDeclQuitacaoReg->incluir(null);

        if($oDaoDeclQuitacaoReg->erro_status == '0') {
          
          $sErro  = "Erro ao incluir na tabela declaraçãoquitacaoreg. Operação abortada. ";
          $sErro .= "ERRO: {$oDaoDeclQuitacaoReg->erro_msg}";
          
          throw new Exception($sErro);
          
        }

      }
       
    }

  }

  /**
   * Constroi um array com todas as declarações solicitadas para geração de arquivo pdf ou txt
   */
  public function setArrayDeclaracoes($iCodigoDeclaracao) {
    
    
    $this->aDeclaracoes[] = $iCodigoDeclaracao;

  }

  public function getArrayDeclaracoes() {

    return $this->aDeclaracoes;

  }

  public function salvarOrigem() {

    if(empty($this->iOrigem)) {

      throw new Exception('Tipo de origem da declaração não definida.');

    }

    if($this->iOrigem == 1) {
      //cgm
      try {

        $this->salvarCgm();

      }catch (Exception $sException) {

        throw new Exception($sException->getMessage());

      }

    } elseif($this->iOrigem == 2) {
      //matricula
      try {

        $this->salvarMatricula();
         
      } catch (Exception $sException) {

        throw new Exception($sException->getMessage());

      }

    } elseif($this->iOrigem == 3) {
      //inscrição
      try {

        $this->salvarInscricao();

      } catch (Exception $sException) {

        throw new Exception($sException->getMessage());

      }

    }
  }

  public function salvarInscricao() {

    $this->setCgmInscr();

    if(empty($this->iCgm)) {

      throw new Exception('Cgm não informado');

    }

    if(empty($this->iInscricao)) {

      throw new Exception('Inscrição não informada');

    }

    try{

      $this->salvarCgm();

    } catch (Exception $sException) {

      throw new Exception($sException->getMessage());

    }

    $oDaoDeclQuitacaoInscr = db_utils::getDao('declaracaoquitacaoinscr');

    $oDaoDeclQuitacaoInscr->ar35_inscr              = $this->iInscricao;
    $oDaoDeclQuitacaoInscr->ar35_declaracaoquitacao = $this->iCodDeclaracao;
    $oDaoDeclQuitacaoInscr->incluir(null);

    if($oDaoDeclQuitacaoInscr->erro_status == '0') {

      $sErro  = "Erro ao incluir na tabela declaracaoquitacaomatric. Operação abortada. ";
      $sErro .= "ERRO:{$oDaoDeclQuitacaoInscr->erro_msg}";
      
      throw new Exception($sErro);

    }

  }

  public function setCgmInscr() {

    if(empty($this->iInscricao)) {
      throw new Exception('Inscrição não informada.');
    }

    $oDaoIssbase = db_utils::getDao('issbase');

    $rDaoIssbase = $oDaoIssbase->sql_record($oDaoIssbase->sql_query_file($this->iInscricao));

    if($oDaoIssbase->numrows > 0){

      $this->iCgm = db_utils::fieldsMemory($rDaoIssbase, 0)->q02_numcgm;

    } else {

      throw new Exception('Cgm da inscrição não encontrado');

    }
  }

  public function salvarMatricula() {

    $this->setCgmMatric();

    if(empty($this->iCgm)) {

      throw new Exception('Cgm não informado');

    }

    if(empty($this->iMatricula)) {

      throw new Exception('Matrícula não informada');

    }

    try{

      $this->salvarCgm();

    } catch (Exception $sException) {

      throw new Exception($sException->getMessage());

    }

    $oDaoDeclQuitacaoMatric = db_utils::getDao('declaracaoquitacaomatric');
    $oDaoDeclQuitacaoMatric->ar33_matric             = $this->iMatricula;
    $oDaoDeclQuitacaoMatric->ar33_declaracaoquitacao = $this->iCodDeclaracao;
    $oDaoDeclQuitacaoMatric->incluir(null);

    if($oDaoDeclQuitacaoMatric->erro_status == '0') {

      $sErro  = "Erro ao incluir na tabela declaracaoquitacaomatric. Operação abortada. ";
      $sErro .= "ERRO:{$oDaoDeclQuitacaoMatric->erro_msg}";
      
      throw new Exception($sErro);

    }

  }

  public function setCgmMatric() {

    if(empty($this->iMatricula)) {
      throw new Exception('Matricula não informada');
    }

    $oDaoIptuBase = db_utils::getDao('iptubase');

    $rDaoIptuBase = $oDaoIptuBase->sql_record($oDaoIptuBase->sql_query_file($this->iMatricula));

    if($oDaoIptuBase->numrows > 0){

      $this->iCgm = db_utils::fieldsMemory($rDaoIptuBase, 0)->j01_numcgm;

    } else {

      throw new Exception('Cgm da matricula não encontrado');

    }

  }

  public function salvarCgm() {

    if(empty($this->iCgm)) {

      throw new Exception('Cgm não informado');

    }

    $oDaoDeclQuitacaoCgm = db_utils::getDao('declaracaoquitacaocgm');

    $oDaoDeclQuitacaoCgm->ar34_numcgm             = $this->iCgm;
    $oDaoDeclQuitacaoCgm->ar34_declaracaoquitacao = $this->iCodDeclaracao;
    $oDaoDeclQuitacaoCgm->ar34_somentecgm         = $this->lTipoCgm == true ? 'true' : 'false';
    $oDaoDeclQuitacaoCgm->incluir(null);

    if($oDaoDeclQuitacaoCgm->erro_status == '0') {

      $sErro  = "Erro ao incluir na tabela declaracaoquitacaocgm. Operação abortada. ";
      $sErro .= "ERRO:{$oDaoDeclQuitacaoCgm->erro_msg}";
      
      throw new Exception($sErro);

    }

  }

  public function carregaOrigem() {
    
    if(empty($this->iOrigem)) {
      
      throw new Exception('Origem não informada');
      
    }

    if(empty($this->iExercicio)) {
      
      throw new Exception('Exercicio não informado');
      
    }    
    
    $oDaoDeclQuitacao = db_utils::getDao('declaracaoquitacao');
    
    $sSqlDeclQuitacao = $oDaoDeclQuitacao->sql_query_lista_origens($this->iOrigem, $this->iExercicio, $this->lTipoCgm);
    
    return $oDaoDeclQuitacao->sql_record($sSqlDeclQuitacao);

  }

  public function carregar($iCodDeclaracao) {

    $this->iCodDeclaracao = $iCodDeclaracao;

    if(empty($this->iCodDeclaracao)) {
      throw new Exception('Código da declaração não informado');
    }

    $oDaoDeclQuitacao = db_utils::getDao('declaracaoquitacao');
    
    $sSqlDeclQuitacao = $oDaoDeclQuitacao->sql_query_detalhes_declaracao($this->iCodDeclaracao);

    $rDaoDeclQuitacao = $oDaoDeclQuitacao->sql_record($sSqlDeclQuitacao);

    if($oDaoDeclQuitacao->numrows > 0) {

      $oDeclQuitacao = db_utils::fieldsMemory($rDaoDeclQuitacao, 0, true);

      $this->iCodDeclaracao    = $oDeclQuitacao->codigodeclaracao;
      $this->iExercicio        = $oDeclQuitacao->exercicio;
      $this->sNomeCgm          = $oDeclQuitacao->nomecgm;
      $this->sNomeOrigem       = $oDeclQuitacao->origem;
      $this->iCodOrigem        = $oDeclQuitacao->codigoorigem;
      $this->iOrigem           = $oDeclQuitacao->numeroorigem;
      $this->dData             = $oDeclQuitacao->data;
      $this->sUsuario          = $oDeclQuitacao->usuario;
      $this->iSituacao         = $oDeclQuitacao->situacao;
      $this->sAnoMesImpressao  = $oDeclQuitacao->anomesimpressao;

      if($this->iOrigem == 1) {

        $this->iCgm = $this->iCodOrigem;

      } elseif ($this->iOrigem == 2) {

        $this->iMatricula = $this->iCodOrigem;

      } elseif ($this->iOrigem == 3) {

        $this->iInscricao = $this->iCodOrigem;

      }
      
      $this->carregaDebitosDeclaracao($this->iCodDeclaracao);
    }

  }

  public function carregaDebitosDeclaracao($iCodDeclaracao) {
    
    $oDaoDeclQuitacao = db_utils::getDao('declaracaoquitacao');
    
    $rDaoDeclQuitacao = $oDaoDeclQuitacao->sql_record($oDaoDeclQuitacao->sql_query_declaracao_debitos($iCodDeclaracao));
    
    if($oDaoDeclQuitacao->numrows > 0) {
      
      for($i = 0; $i < $oDaoDeclQuitacao->numrows; $i++) {
        
	      $this->aDebitosDeclaracao[] = db_utils::fieldsMemory($rDaoDeclQuitacao, $i);
	       
      }
    }
    
    
  }
  
  public function getExerciciosDeclaracao() {

    $oDaoDeclQuitacao = db_utils::getDao('declaracaoquitacao');

    $sSqlDeclQuitacao = $oDaoDeclQuitacao->sql_query_exercicios($this->iOrigem, $this->iCodOrigem, $this->lTipoCgm);
    
    $rDaoDeclQuitacao = $oDaoDeclQuitacao->sql_record($sSqlDeclQuitacao);

    $aExerciciosDeclaracao = array();

    if($oDaoDeclQuitacao->numrows > 0) {

      for($i = 0; $i < $oDaoDeclQuitacao->numrows; $i++) {

        $aExerciciosDeclaracao[] = db_utils::fieldsMemory($rDaoDeclQuitacao, $i);

      }

    }

    return $aExerciciosDeclaracao;

  }

  public function getDeclaracoesOrigem() {

    $oDaoDeclQuitacao = db_utils::getDao('declaracaoquitacao');

    $sSqlDeclQuitacao = $oDaoDeclQuitacao->sql_query_lista_declaracoes($this->iOrigem, 
                                                                       $this->iCodOrigem, 
                                                                       $this->lTipoCgm);
    
    $rDaoDeclQuitacao = $oDaoDeclQuitacao->sql_record($sSqlDeclQuitacao);

    $aDeclaracaoesOrigem = array();

    if($oDaoDeclQuitacao->numrows > 0) {

      for($i = 0; $i < $oDaoDeclQuitacao->numrows; $i++) {

        $aDeclaracaoesOrigem[] = db_utils::fieldsMemory($rDaoDeclQuitacao, $i);

      }

    }

    return $aDeclaracaoesOrigem;

  }

}