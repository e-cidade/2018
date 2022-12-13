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
 * Classe para pesquisas em avaliacoes
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @package configuracao
 * @subpackage avaliacao
 */
class FiltroAvaliacao {

  /**
   * Instancia da avaliacao selecioanda
   * @var Avaliacao
   */
  private $oAvaliacaoSelecionada  = null;

  /**
   * Grupo de avaliacao selecionada
   * @var AvaliacaoGrupo
   */
  private $oGrupoAvaliacao        = null;

  /**
   * Pergunta selecionada
   * @var AvaliacaoPergunta
   */
  private $oPerguntaSelecionada = null;

  /**
   * Array com as avaliacoes encontradas pelo filro
   * @var array
   */
  private $aAvaliacoes = array();

  /**
   * Controle de utilizacao de subfiltros E.g método E()
   * @var boolean
   */
  private $lSubFiltro = false;

  /**
   * define a avaliacao na qual vai ser realizado a pesquisa
   * @param string $sAvaliacao identificador da avaliacao
   * @throws ParameterException
   * @return FiltroAvaliacao
   */
  public function daAvaliacao($sAvaliacao) {

    if (!$oAvaliacaoSelecionada = AvaliacaoRepository::getAvaliacaoByIdentificador($sAvaliacao)) {
       throw new ParameterException("Não existe avaliacao com o identificador {$sAvaliacao}");
    }
    $this->oAvaliacaoSelecionada = $oAvaliacaoSelecionada;
    return $this;
  }

  /**
   * Retorna o grupo da Avaliacao conforme o identificadort selecionado.
   * depende do metodo Avaliacao::daAvaliacao()
   *
   * @see FiltroAvaliacao::daAvaliacao()
   * @param string $sIdentificador identificador do grupo
   * @throws ParameterException
   * @return FiltroAvaliacao
   */
  public function doGrupo($sIdentificador) {

    if (!$this->hasAvaliacaoSelecionada()) {

      $sExceptionMessage  = "Não existe avaliação selecionada. ";
      $sExceptionMessage .= "Antes de chamar o esse método invocar 'FiltroAvaliacao::daAvaliacao()'";
      throw new ParameterException($sExceptionMessage);
    }
    /**
     * selecionamos o Grupo da pergunta
     */
    foreach ($this->oAvaliacaoSelecionada->getGruposPerguntas() as $oGrupoPergunta) {

      if ($oGrupoPergunta->getIdentificador() == $sIdentificador) {

        $this->oGrupoAvaliacao = $oGrupoPergunta;
        break;
      }
    }
    return $this;
  }

  /**
   * Verifica se foi selecionado alguma avaliacao para a pesquisa
   * @return boolean
   */
  public function hasAvaliacaoSelecionada() {
    return $this->oAvaliacaoSelecionada instanceof Avaliacao;
  }

  /**
   * verifica se um grupo foi selecionado para pesquisa
   * @return boolean
   */
  public function hasGrupoAvaliacaoSelecionada() {
    return $this->oGrupoAvaliacao instanceof AvaliacaoGrupo;
  }

  /**
   * Verifica se existe alguma pergunta selecionada
   * @return boolean
   */
  public function hasAvaliacaoPerguntaSelecionada() {
      return $this->oPerguntaSelecionada instanceof AvaliacaoPergunta;
  }

  /**
   * Seleciona uma pergunta para a pesquisa
   * @param string $sPergunta
   * @return FiltroAvaliacao
   */
  public function comPergunta($sPergunta) {

    if (!$this->hasGrupoAvaliacaoSelecionada()) {

      $sExceptionMessage  = "Não existe nenhum grupo  selecionada. ";
      $sExceptionMessage .= "Antes de chamar o esse método invocar 'FiltroAvaliacao::doGrupo()'";
      throw new ParameterException($sExceptionMessage);
    }

    foreach ($this->oGrupoAvaliacao->getPerguntas() as $oPergunta) {

      if ($oPergunta->getIdentificador() == $sPergunta) {

        $this->oPerguntaSelecionada = $oPergunta;
        break;
      }
    }
    return $this;
  }

  /**
   * Retorna as avaliacoes que possuem as Respostas para a pergunta
   * @param array $aRespostas array com as respostas
   * @param array $aRespostaDissertativas Respostas dissertativas que a pergunta pode ter
   * @return FiltroAvaliacao
   */
  public function comRespostas(array $aRespostas, array $aRespostaDissertativas = null) {

    $aCodigoRespostas   = array();
    $aAvaliacoesRetorno = array();
    foreach ($this->oPerguntaSelecionada->getRespostas() as $oResposta) {

      if (in_array($oResposta->identificador, $aRespostas)) {
        $aCodigoRespostas[] = $oResposta->codigoresposta;
      }
    }

    $aAvaliacoes = array();
    if (!$this->lSubFiltro) {
      foreach ($aCodigoRespostas as $iCodigoResposta) {
       $aAvaliacoes =  array_merge($aAvaliacoes,
                                   AvaliacaoPergunta::getAvaliacacoesComResposta($iCodigoResposta,
                                                                                 $aRespostaDissertativas
                                                                                )
                                  );
      }
    } else {
      $aAvaliacoes = $this->aplicarFiltroAnd($aCodigoRespostas, $aRespostaDissertativas);
    }

    foreach ($aAvaliacoes as $oAvaliacao) {

      if (!array_key_exists($oAvaliacao->getAvaliacaoGrupo(), $aAvaliacoesRetorno)) {
        $aAvaliacoesRetorno[$oAvaliacao->getAvaliacaoGrupo()] = $oAvaliacao;
      }
    }

    $this->aAvaliacoes = $aAvaliacoesRetorno;
    return $this;
  }

  /**
   * Cria uma condicao AND no filtro
   * @return FiltroAvaliacao
   */
  public function e() {

    $this->lSubFiltro = true;
    return $this;
  }

  /**
   * Retorna as avaliacoes encontradas pelo filtro
   * @return array com as avaliacoes que foram encontradas pelo filtro
   * @return Avaliacao
   */
  public function retornarAvaliacoes() {
    return $this->aAvaliacoes;

  }

  /**
   * Realiza  a aplicação dos filtros and, dentro das avaliações já existentes
   * @param array  $aRespostas array com as respostas
   * @return array:Avaliacao
   */
  protected function aplicarFiltroAnd(array $aRespostas, array $aRespostasDissertativas = null) {

    $aAvaliacoes  = array();
    foreach ($this->aAvaliacoes as $oAvaliacao) {

      foreach ($oAvaliacao->getPerguntas($this->oGrupoAvaliacao->getGrupo()) as $oPergunta) {
        foreach ($oPergunta->getRespostas() as $oResposta) {

          if (in_array($oResposta->codigoresposta, $aRespostas) && $oResposta->marcada === true) {
            if (!empty($aRespostasDissertativas) && count($aRespostasDissertativas) > 0) {
              if (!in_array($oResposta->textoresposta, $aRespostasDissertativas)) {
                continue;
              }
            }
            $aAvaliacoes[] = $oAvaliacao;
          }
        }
      }
    }
    return $aAvaliacoes;
  }
}