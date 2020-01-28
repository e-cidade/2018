<?php
/**
 *         E-cidade Software Publico para Gestao Municipal
 *      Copyright (C) 2016  DBSeller Servicos de Informatica
 *                       www.dbseller.com.br
 *                    e-cidade@dbseller.com.br
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
class AvaliacaoAdapter {

  private $oAvaliacao;
  
  protected $iCodigoGrupoResposta;

  public function __construct(\Avaliacao $oAvaliacao) {
    $this->oAvaliacao = $oAvaliacao;
  }

  public function getObject() {

    $oFormulario = new \stdClass();
    $oFormulario->codigo     = $this->oAvaliacao->getAvaliacao();
    $oFormulario->id         = $this->oAvaliacao->getIdentificador();
    $oFormulario->label      = $this->oAvaliacao->getDescricao();
    $oFormulario->observacao = $this->oAvaliacao->getObservacao();
    $oFormulario->grupos     = $this->getGrupos();
    return (object)$oFormulario;
  }

  private function getGrupos() {

    $aGruposPerguntas      = $this->oAvaliacao->getGruposPerguntas();
    $aDadosGruposPerguntas = array();

    foreach ($aGruposPerguntas as $oGrupoPergunta) {

      $oGrupo             = new \stdClass();
      $oGrupo->codigo     = $oGrupoPergunta->getGrupo();
      $oGrupo->id         = $oGrupoPergunta->getIdentificador();
      $oGrupo->label      = $oGrupoPergunta->getDescricao();
      $oGrupo->perguntas  = $this->getPerguntas($oGrupoPergunta);

      $aDadosGruposPerguntas[] = $oGrupo;
    }
    return $aDadosGruposPerguntas;
  }

  protected function getPerguntas(\AvaliacaoGrupo $avaliacaoGrupo) {
    $aPerguntas = array();

    foreach ($avaliacaoGrupo->getPerguntas() as $pergunta) {
      
      
      $pergunta->setAvaliacao($this->getCodigoGrupoResposta());
      $oPerguntaRetorno = new \StdClass();          
      $oPerguntaRetorno->codigo        = $pergunta->getCodigo();
      $oPerguntaRetorno->id            = $pergunta->getIdentificador();
      $oPerguntaRetorno->label         = $pergunta->getDescricao();
      $oPerguntaRetorno->tipo_resposta = $pergunta->getTipo();
      $oPerguntaRetorno->tipo          = $pergunta->getTipoComponente();//$pergunta->getCodigo();
      $oPerguntaRetorno->ordem         = 1;//$pergunta->getCodigo();
      $oPerguntaRetorno->obrigatoria   = $pergunta->isObrigatoria();
      $oPerguntaRetorno->ativo         = $pergunta->isAtivo();
      $oPerguntaRetorno->formato       = $pergunta->getCodigoFormula();
      $oPerguntaRetorno->mascara       = $pergunta->getMascara();
      $oPerguntaRetorno->respostas     = $this->getRespostas($pergunta);
      $aPerguntas[] = $oPerguntaRetorno;
    }    

    return $aPerguntas;
  }


  protected function getRespostas(\AvaliacaoPergunta $avaliacaoPergunta ){
    $respostas  = array();

       
      foreach ($avaliacaoPergunta->getRespostas() as $resposta) {         
        
        $oResposta                = new \stdClass();
        $oResposta->codigo        = $resposta->codigoresposta;
        $oResposta->id            = $resposta->identificador;
        $oResposta->peso          = $resposta->peso;//$avaliacaoPergunta->get
        $oResposta->valor         = $resposta->textoresposta;
        $oResposta->permiteTexto  = $resposta->texto;//$avaliacaoPergunta->get
        $oResposta->label         = $resposta->descricaoresposta;//$avaliacaoPergunta->get

        $respostas[] = $oResposta;
      }

    return $respostas;
  }
  

  /**
   * @return mixed
   */
  public function getCodigoGrupoResposta() {
    return $this->iCodigoGrupoResposta;
  }

  /**
   * @param mixed $iCodigoGrupoResposta
   */
  public function setCodigoGrupoResposta($iCodigoGrupoResposta) {
    $this->iCodigoGrupoResposta = $iCodigoGrupoResposta;
  }
  
    
}






