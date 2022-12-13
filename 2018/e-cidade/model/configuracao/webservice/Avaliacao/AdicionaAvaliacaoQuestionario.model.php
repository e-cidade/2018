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

require_once modification("model/Avaliacao.model.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

/**
 * Model para inclusao de avaliacao (questionario cadastrado pela DBSeller) via webservice
 * @author Fabio Egidio         <fabio.egidio@dbseller.com.br>
 *
 */
class AdicionaAvaliacaoQuestionario {
  
  /**
   * Descricao da avaliacao
   * @var string
   */
  private $sDescricao;
  
  /**
   * Observacao da avaliacao
   * @var string
   */
  private $sObs;
  
  /**
   * Identificador da avaliacao
   * @var string
   */
  private $sIdentificador;
  
  /**
   * sequencial da nova avaliacao
   */
  private $iAvaliacao;

  /**
   * array de grupos de perguntas
   * @var array
   */
  private $aGrupoPergunta;

  /**
   * array de menus do questionario
   * @var array
   */
  private $aMenu;

  /**
   * Variavel de controle de vinculos
   * Ela e responsavel por retornar os novos IDs
   * das informacoes de grupos, perguntas e opcoes
   * @var array
   */
  private $aVinculo;

  /**
   * Codigo de controle do questionario
   * esse codigo e gerado pelo sistema que transmite os questionarios
   * @var integer
   */

  private $iCodigoQuestionario;

  /**
   * Construtor da classe
   */
  public function __construct() {}

  /**
   * Define o sequencial da avaliacao
   * @param integer $iAvaliacao 
   */
  public function setAvaliacao($iAvaliacao){

    $this->iAvaliacao = $iAvaliacao;
  }
  
  /**
   * Seta como vazio o array de vinculos
   * @return array $this->aVinculo
   */
  private function resetVinculo(){

    $this->aVinculo = array();
  }

  private function getVinculo(){

    return $this->aVinculo;
  }

  // Adiciona elemento no array de vinculo
  private function addVinculo($type, $old, $new){

    $this->aVinculo[$type][] = array('old' => $old, 'new' =>$new);
  }

  /**
   * Seta o codigo do questionario
   * @param integer $iCodigoQuestionario
   */
  public function setCodigoQuestionario($iCodigoQuestionario){

    $this->iCodigoQuestionario = $iCodigoQuestionario;
  }

  /**
   * Retorna o codigo do questionario
   * @return integer $this->iCodigoQuestionario
   */
  public function getCodigoQuestionario(){

    return $this->iCodigoQuestionario;
  }

  /**
   * Retorna o sequencial da avaliacao
   * @return integer $this->iAvaliacao
   */
  public function getAvaliacao(){
    return $this->iAvaliacao;
  }

  /**
   * Define a descricao da avaliacao
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna a Descricao
   * @return string $this->sDescricao
   */
  public function getDescricao(){

    return $this->sDescricao;
  }

  /**
   * Define o campo observacao da avaliacao
   * @param string $sObs
   */
  public function setObs($sObs) {
    
    $this->sObs = $sObs;
  }
  
  /**
   * Retorna a Observacao
   * @return string $this->sObs
   */
  public function getObs(){

    return $this->sObs;
  }

  /**
   * Define o identificador da avaliacao
   * @param string $sIdentificador
   */
  public function setIdentificador($sIdentificador) {
    $this->sIdentificador = $sIdentificador;
  }

  /**
   * Retorna o identificador da avaliacao
   * @return string $this->sIdentificador
   */
  public function getIdentificador(){
    
    return $this->sIdentificador;
  }

  /**
   * Define o array de grupos de perguntas
   * @param array $aGrupoPergunta $this->aGrupoPergunta
   */
  public function setGrupoPergunta($aGrupoPergunta){

    $this->aGrupoPergunta = $aGrupoPergunta;
  }

  /**
   * Retorna o grupo de perguntas
   * @return array $this->aGrupoPergunta
   */
  public function getGrupoPergunta(){

    return $this->aGrupoPergunta;
  }

  /**
   * Seta o array de Menus
   * @param array $aMenu 
   */
  public function setMenu($aMenu){

    $this->aMenu = $aMenu;
  }

  /**
   * Retorna o array de menus
   * @return array $this->aMenu
   */
  public function getMenu(){

    return $this->aMenu;
  }


  /**
   * Finalidade apenas de teste de conexao com o SOAP
   * Apenas retorna true
   */
  public function getStatus(){

    return true;
  }

  public function desativaQuestionario(){

    $oRetorno   = new stdClass();

    $oDaoAvaliacao = db_utils::getDao("avaliacao");
    $sSqlAvaliacao = $oDaoAvaliacao->sql_query_file($this->getAvaliacao());
    $rsAvaliacao = db_query($sSqlAvaliacao);    

    if(!$rsAvaliacao){

      return array('erro' => 'Avaliacao nao encontrada');
    }          

    $aAvaliacao = db_utils::getCollectionByRecord($rsAvaliacao, false, false, true);

    if(!$aAvaliacao){
    
      return array('erro' => 'Avaliacao nao encontrada');
    }

    $oAvaliacao = $aAvaliacao[0];

    $oDaoAvaliacao->db101_sequencial = $oAvaliacao->db101_sequencial;
    $oDaoAvaliacao->db101_ativo      = 'false'; 

    try {
  
      $oDaoAvaliacao->alterar($oAvaliacao->db101_sequencial);
    } catch (Exception $e) {
      
      return array('erro' => 'Ocorreu algum erro ao salvar a Avaliacao');
    }

    return array('sucesso'=> 'Avaliacao desativada com sucesso');
  }
    
  /**
   * Salva os dados no cgm
   * @throws BusinessException
   * @return Object
   */
  public function salvar() {

    $oRetorno   = new stdClass();
    $oAvaliacao = db_utils::getDao("avaliacao");

    db_inicio_transacao();

    $oAvaliacao->db101_sequencial    = $this->getAvaliacao();
    $oAvaliacao->db101_descricao     = $this->getDescricao();
    $oAvaliacao->db101_obs           = $this->getObs();
    $oAvaliacao->db101_identificador = $this->getIdentificador();
    $oAvaliacao->db101_avaliacaotipo = 6;
    $oAvaliacao->db101_ativo         = 't';

    $oAvaliacao->incluir($oAvaliacao->db101_sequencial);

    if($oAvaliacao->erro_status != "0"){

      $oAvaliacaoInterno = db_utils::getDao('avaliacaoquestionariointerno');
      
      $oAvaliacaoInterno->db170_avaliacao   = $oAvaliacao->db101_sequencial;
      $oAvaliacaoInterno->db170_codigo      = $this->getCodigoQuestionario();       
      $oAvaliacaoInterno->db170_transmitido = 't';
      $oAvaliacaoInterno->db170_ativo       = 't';

      $oAvaliacaoInterno->incluir(null);

      if($oAvaliacaoInterno->erro_status == "0"){

        $oRetorno->error = utf8_encode($oAvaliacaoInterno->erro_msg);
        db_fim_transacao(true);
        return $oRetorno;
      }
      $aMenus = $this->getMenu();

      if(empty($aMenus)){

        $oRetorno->error = utf8_encode("Nenhum menu configurado para o questionario");
        db_fim_transacao(true);
        return $oRetorno;
      }

      foreach ($aMenus as $oMenu) {

        $oAvaliacaoMenu = db_utils::getDao('avaliacaoquestionariointernomenu');
        $oAvaliacaoMenu->db171_questionario = $oAvaliacaoInterno->db170_sequencial;
        $oAvaliacaoMenu->db171_menu         = $oMenu->db171_menu;
        $oAvaliacaoMenu->db171_modulo       = $oMenu->db171_modulo;
        $oAvaliacaoMenu->incluir(null);
          
        if($oAvaliacaoMenu->erro_status == "0"){

          $oRetorno->error = utf8_encode($oAvaliacaoMenu->erro_msg);
          db_fim_transacao(true);
          return $oRetorno;
        }
      }

      $oSave = $this->salvarGrupos();

      if(!empty($oSave->error)){

        db_fim_transacao(true);
        $oRetorno->msg = utf8_encode($oSave->erro_msg);
      } else {

        $oRetorno->msg = utf8_encode('Avaliação salva com sucesso');  
        db_fim_transacao();
      }
    } else {

      db_fim_transacao(true);
      try {
        
        $oRetorno->error = utf8_encode($oAvaliacao->erro_msg);  
      } catch (Exception $e) {
        
        $oRetorno->error = $oAvaliacao->erro_msg;  
      }
      }
    return $oRetorno;  
  }

  private function salvarGrupos(){

    $aGrupos = $this->getGrupoPergunta();
    
    if(!empty($aGrupos)){

      foreach ($aGrupos as $oGrupo) {

        $oAvaliacaoGrupo = db_utils::getDao("avaliacaogrupopergunta");
        
        $oAvaliacaoGrupo->db102_sequencial    = $oGrupo->db102_sequencial;
        $oAvaliacaoGrupo->db102_avaliacao     = $this->getAvaliacao();
        $oAvaliacaoGrupo->db102_descricao     = utf8_decode($oGrupo->db102_descricao);
        $oAvaliacaoGrupo->db102_identificador = $oGrupo->db102_identificador;
        $oAvaliacaoGrupo->incluir($oGrupo->db102_sequencial); 

        if($oAvaliacaoGrupo->erro_status == "0"){

          $oAvaliacaoGrupo->error = 1;
          return $oAvaliacaoGrupo;
        }
        // Adiciona na lista o ID de retorno para a DBSeller
        $this->addVinculo('AvaliacaoGrupo', $oGrupo->db102_sequencial, $oAvaliacaoGrupo->db102_sequencial);

        // Verifica se existem perguntas
        if(!empty($oGrupo->perguntas)){

          foreach ($oGrupo->perguntas as $oPergunta) {

            $oAvaliacaoPergunta = db_utils::getDao("avaliacaopergunta");

            // Tratamento de boolean
            if(empty($oPergunta->db103_obrigatoria)){

              $oPergunta->db103_obrigatoria = 'false';
            } 

            $oAvaliacaoPergunta->db103_sequencial             = $oPergunta->db103_sequencial;
            $oAvaliacaoPergunta->db103_avaliacaotiporesposta  = $oPergunta->db103_avaliacaotiporesposta;
            $oAvaliacaoPergunta->db103_avaliacaogrupopergunta = $oAvaliacaoGrupo->db102_sequencial;
            $oAvaliacaoPergunta->db103_descricao              = utf8_decode($oPergunta->db103_descricao);
            $oAvaliacaoPergunta->db103_obrigatoria            = $oPergunta->db103_obrigatoria;
            $oAvaliacaoPergunta->db103_ativo                  = $oPergunta->db103_ativo;
            $oAvaliacaoPergunta->db103_ordem                  = $oPergunta->db103_ordem;
            $oAvaliacaoPergunta->db103_identificador          = $oPergunta->db103_identificador;
            $oAvaliacaoPergunta->db103_tipo                   = $oPergunta->db103_tipo;
            $oAvaliacaoPergunta->db103_mascara                = $oPergunta->db103_mascara;
            $oAvaliacaoPergunta->incluir($oPergunta->db103_sequencial);

            if($oAvaliacaoPergunta->erro_status == "0"){

              $oAvaliacaoPergunta->error = 1;
              return $oAvaliacaoPergunta;
            }       

            // Verifica se a pergunta possui opcoes
            if(!empty($oPergunta->opcao)){

              foreach ($oPergunta->opcao as $oOpcao) {

                $oAvaliacaoPerguntaOpcao = db_utils::getDao("avaliacaoperguntaopcao");
                $oAvaliacaoPerguntaOpcao->db104_sequencial        = $oOpcao->db104_sequencial;
                $oAvaliacaoPerguntaOpcao->db104_avaliacaopergunta = $oAvaliacaoPergunta->db103_sequencial;
                // $oAvaliacaoPerguntaOpcao->db104_descricao         = utf8_decode($oOpcao->db104_descricao);
                $oAvaliacaoPerguntaOpcao->db104_descricao         = pg_escape_string(utf8_decode($oOpcao->db104_descricao));
                // $oAvaliacaoPerguntaOpcao->db104_descricao         = $oOpcao->db104_identificador;
  
                // Tratamento de boolean
                if (empty($oOpcao->db104_aceitatexto)) {

                  $oOpcao->db104_aceitatexto = 'false';
                }
                
                $oAvaliacaoPerguntaOpcao->db104_aceitatexto   = $oOpcao->db104_aceitatexto;
                $oAvaliacaoPerguntaOpcao->db104_identificador = $oOpcao->db104_identificador;
                $oAvaliacaoPerguntaOpcao->db104_peso          = $oOpcao->db104_peso;
                $oAvaliacaoPerguntaOpcao->incluir($oOpcao->db104_sequencial);
              
                if($oAvaliacaoPerguntaOpcao->erro_status == "0"){

                  $oAvaliacaoPerguntaOpcao->error = 1;
                  return $oAvaliacaoPerguntaOpcao;
                }
              }
            }
            // Verifica se a pergunta possui formula
            if(!empty($oPergunta->formula)){

              // OBRIGATORIO 
              // criar a formula antes do relacionamento
              // tratamento de boolean
              if (empty($oPergunta->formula->db148_ambiente)) {

                $oPergunta->formula->db148_ambiente = 'false';
              } 

              // Formula
              $oFormula = db_utils::getDao("db_formulas");
              $oFormula->db148_sequencial= $oPergunta->formula->db148_sequencial;
              $oFormula->db148_nome      = utf8_decode($oPergunta->formula->db148_nome);
              $oFormula->db148_descricao = utf8_decode($oPergunta->formula->db148_descricao);
              $oFormula->db148_formula   = pg_escape_string($oPergunta->formula->db148_formula);
              $oFormula->db148_ambiente  = $oPergunta->formula->db148_ambiente;
              $oFormula->incluir($oFormula->db148_sequencial);

              if($oFormula->erro_status == "0"){

                $oFormula->error = 1;
                return $oFormula;
              }

              // Caso tenha salvo, cria o relacionamento
              if(!empty($oFormula->db148_sequencial)){

                $oAvaliacaoFormula = db_utils::getDao("avaliacaoperguntadb_formulas");
                $oAvaliacaoFormula->eso01_sequencial        = $oPergunta->formula->eso01_sequencial;
                $oAvaliacaoFormula->eso01_db_formulas       = $oPergunta->formula->db148_sequencial;
                $oAvaliacaoFormula->eso01_avaliacaopergunta = $oAvaliacaoPergunta->db103_sequencial;
                $oAvaliacaoFormula->incluir(null);

                if($oAvaliacaoFormula->erro_status == "0"){

                  $oAvaliacaoFormula->error = 1;
                  return $oAvaliacaoFormula;
                }
              }
            }
          }
        }
      }
    }
  }
}
