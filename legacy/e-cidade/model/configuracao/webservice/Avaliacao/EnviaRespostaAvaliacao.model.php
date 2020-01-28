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
require_once modification("libs/db_stdlib.php");
require_once modification("model/Avaliacao.model.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");


/**
 * Model para recuperar as respostas da avaliacao (questionario cadastrado pela DBSeller) via webservice
 * @author Fabio Egidio <fabio.egidio@dbseller.com.br>
 *
 */

class EnviaRespostaAvaliacao {

  /**
   * Quantidade de Respostas encontradas
   * @var integer 
   */
  private $iQtdRespostas;

  /**
   * Codigo da avaliacao
   * @var integer
   */
  private $iAvaliacao;

  /**
   * Seta o codigo da avaliacao
   * @param integer $iAvaliacao 
   */
  public function setAvaliacao($iAvaliacao){

    $this->iAvaliacao = $iAvaliacao;
  }

  /**
   * Retorna o codigo da avaliacao
   * @return integer $this->iAvaliacao
   */
  public function getAvaliacao(){

    return $this->iAvaliacao;
  }

  /**
   * Retorna a quantidade de respostas
   * @return integer $this->iQtdRespostas
   */
  public function getQtdRespostas(){

    return $this->iQtdRespostas;
  }

  public function buscaRespostas(){

    $sSql = "
      select distinct 
        login, 
        db_usuarios.nome,
        db_usuarios.email,
        id_usuario
      from 
        avaliacaogruporesposta
        inner join db_usuarios on
          id_usuario = db107_usuario
        inner join avaliacaogrupoperguntaresposta on
          db108_avaliacaogruporesposta = db107_sequencial
        inner join avaliacaoresposta on
          db106_sequencial = db108_avaliacaoresposta 
        inner join avaliacaoperguntaopcao on
          db104_sequencial = db106_avaliacaoperguntaopcao
        inner join avaliacaopergunta on
          db103_sequencial = db104_avaliacaopergunta 
        inner join avaliacaogrupopergunta on
          db102_sequencial = db103_avaliacaogrupopergunta
        inner join avaliacao on
          db101_sequencial = db102_avaliacao
      where
        db101_sequencial = {$this->getAvaliacao()};
    ";

    $rsResult = db_query($sSql);
    if(!$rsResult){

      return array('erro' => 'Erro ao buscar informações de usuários');
    }              

    $aQuestionariosRespondidos = db_utils::getCollectionByRecord($rsResult, false, false, false);

    $aUsuarios = array();

    if(!empty($aQuestionariosRespondidos)){

      foreach ($aQuestionariosRespondidos as $oQuestionarioRespondido) {
      
        $sSqlMatricula = "
          select distinct
            rh01_regist as matricula,
            rh01_instit as instint,
            nomeinst,
            munic
        FROM db_usuacgm
            INNER JOIN avaliacaogruporesposta ON id_usuario = db107_usuario
            INNER JOIN avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = db107_sequencial
            INNER JOIN avaliacaogruporespostarhpessoal ON eso02_avaliacaogruporesposta = db107_sequencial
            INNER JOIN avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta
            INNER JOIN avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao
            INNER JOIN avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta
            INNER JOIN avaliacaogrupopergunta ON db102_sequencial = db103_avaliacaogrupopergunta
            INNER JOIN avaliacao ON db101_sequencial = db102_avaliacao
            INNER JOIN cgm ON z01_numcgm = db_usuacgm.cgmlogin
            INNER JOIN rhpessoal ON rh01_numcgm = z01_numcgm
            AND eso02_rhpessoal = rh01_regist
            INNER JOIN db_config ON codigo = rh01_instit
            WHERE db_usuacgm.id_usuario = {$oQuestionarioRespondido->id_usuario}
              AND db101_sequencial = {$this->getAvaliacao()}
        ";

        $rsUsuario = db_query($sSqlMatricula);   

        if(!$rsUsuario){

          return array('erro' => 'Erro ao buscar informações da matricula');
        }        

        $sSqlDataResposta = "
          select 
            db107_datalancamento as data_resposta
          from    
            avaliacaogruporesposta
            inner join db_usuarios on
              id_usuario = db107_usuario
            inner join avaliacaogrupoperguntaresposta on
              db108_avaliacaogruporesposta = db107_sequencial
            inner join avaliacaoresposta on
              db106_sequencial = db108_avaliacaoresposta 
            inner join avaliacaoperguntaopcao on
              db104_sequencial = db106_avaliacaoperguntaopcao
            inner join avaliacaopergunta on
              db103_sequencial = db104_avaliacaopergunta 
            inner join avaliacaogrupopergunta on
              db102_sequencial = db103_avaliacaogrupopergunta
            inner join avaliacao on
              db101_sequencial = db102_avaliacao
          where
            db101_sequencial = {$this->getAvaliacao()} 
            and db107_usuario = $oQuestionarioRespondido->id_usuario
            order by db107_sequencial desc limit 1;";
        $rsDataResposta = db_query($sSqlDataResposta);   
        
        if(!$rsDataResposta){

          return array('erro' => 'Data do preenchimento do questionário, não encontrada');
        }      
        $aDataResposta = db_utils::getCollectionByRecord($rsDataResposta, false, false, false); 
        $aUsuarioTmp   = db_utils::getCollectionByRecord($rsUsuario, false, false, false);

        $oDataResposta = $aDataResposta[0];

        if(!empty($aUsuarioTmp)){
          
          $aUsuarioTmp             = $aUsuarioTmp[0];
          $oUsuario                = $oQuestionarioRespondido;
          $oUsuario->matricula     = $aUsuarioTmp->matricula; 
          $oUsuario->instint       = $aUsuarioTmp->instint;
          $oUsuario->nomeinst      = $aUsuarioTmp->nomeinst;
          $oUsuario->munic         = $aUsuarioTmp->munic;
          $oUsuario->data_resposta = $oDataResposta->data_resposta;
          $aUsuarios[] = $oUsuario;          
        } else {

          return array('erro' => 'nenhum usuario localizado');
        }
      }
    } else {

      return array('erro' => 'nenhuma resposta encontrada');
    }

    $aResult = array();
    
    if(!empty($aUsuarios)){

      foreach ($aUsuarios as $oUsuario) {
  
        $oServidor  = ServidorRepository::getInstanciaByCodigo($oUsuario->matricula,null, null, $oUsuario->instint);
        $oAvaliacao = new AvaliacaoQuestionarioAdapter(AvaliacaoRepository::getAvaliacaoByCodigo($this->getAvaliacao()));

        $oAvaliacao->setServidor($oServidor);
        $oAvaliacao->trazerSugestoes(1);

        $oQuestionario         = $oAvaliacao->getObject();
        $oRetorno              = new stdClass();
        $oUsuario->nome        = utf8_encode($oUsuario->nome);
        $oRetorno->login       = utf8_encode($oUsuario->login);
        $oRetorno->nome        = utf8_encode($oUsuario->nome);
        $oRetorno->matricula   = $oUsuario->matricula;
        $oRetorno->email       = utf8_encode($oUsuario->email);
        $oRetorno->prefeitura  = utf8_encode($oUsuario->munic . ' -> ' . $oUsuario->nomeinst);
        $oRetorno->municipio   = utf8_encode($oUsuario->munic);
        $oRetorno->instituicao = utf8_encode($oUsuario->nomeinst);
        $oRetorno->aResposta   = array();

        foreach ($oQuestionario->grupos as $oGrupo) {

          foreach ($oGrupo->perguntas as $oPergunta) {

            foreach ($oPergunta->respostas as $oResposta) {

              $oRespostaTmp                = new stdClass();
              $oRespostaTmp->pergunta      = $oPergunta->codigo;
              $oRespostaTmp->data_resposta = $oUsuario->data_resposta;
              
              // Resposta tipo data
              if($oPergunta->tipo == 5){ 
              
                $oResposta->valor = date("d/m/Y",strtotime($oResposta->valor));
              }
              $oRespostaTmp->resposta = utf8_encode($oResposta->valor);
              $oRespostaTmp->label    = utf8_encode($oResposta->label);
              $oRetorno->aResposta[]  = $oRespostaTmp;
            }
          }
        }
        $aResult[] = $oRetorno;
      }
    } else {

      return array('erro' => utf8_encode('nenhum usuário encontrado!'));
    }

    return $aResult;
  }
}