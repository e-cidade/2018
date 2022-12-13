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
namespace ECidade\Configuracao\Formulario\Model;


/**
 * Perguntas de um Formulario
 * Class Pergunta
 * @package ECidade\Configuracao\Formulario\Model
 */
class Pergunta {

  private $codigo;
  private $tipoResposta;
  private $grupo;
  private $descricao;
  private $obrigatoria = false;
  private $ativo = false;
  private $ordem;
  private $identificador;
  private $tipo;
  private $mascara;
  private $dblayoutcampo;
  private $perguntaIdentificadora = false;
  private $campoCarga;
  private $identificadorCampo;

  /**
   * opções de Resposta da pergunta
   * @var array
   */
  private $opcoes = array();

  /**
   * @return mixed
   */
  public function getCodigo() {

    return $this->codigo;
  }

  /**
   * @param mixed $codigo
   */
  public function setCodigo($codigo) {

    $this->codigo = $codigo;
  }

  /**
   * @return mixed
   */
  public function getTipoResposta() {

    return $this->tipoResposta;
  }

  /**
   * @param mixed $tipoResposta
   */
  public function setTipoResposta($tipoResposta) {

    $this->tipoResposta = $tipoResposta;
  }

  /**
   * @return mixed
   */
  public function getGrupo() {

    return $this->grupo;
  }

  /**
   * @param mixed $grupo
   */
  public function setGrupo($grupo) {

    $this->grupo = $grupo;
  }

  /**
   * @return mixed
   */
  public function getDescricao() {

    return $this->descricao;
  }

  /**
   * @param mixed $descricao
   */
  public function setDescricao($descricao) {

    $this->descricao = $descricao;
  }

  /**
   * @return bool
   */
  public function isObrigatoria() {

    return $this->obrigatoria;
  }

  /**
   * @param bool $obrigatoria
   */
  public function setObrigatoria($obrigatoria) {

    $this->obrigatoria = $obrigatoria;
  }

  /**
   * @return bool
   */
  public function isAtivo() {

    return $this->ativo;
  }

  /**
   * @param bool $ativo
   */
  public function setAtivo($ativo) {

    $this->ativo = $ativo;
  }

  /**
   * @return mixed
   */
  public function getOrdem() {

    return $this->ordem;
  }

  /**
   * @param mixed $ordem
   */
  public function setOrdem($ordem) {

    $this->ordem = $ordem;
  }

  /**
   * @return mixed
   */
  public function getIdentificador() {

    return $this->identificador;
  }

  /**
   * @param mixed $identificador
   */
  public function setIdentificador($identificador) {

    $this->identificador = $identificador;
  }

  /**
   * @return mixed
   */
  public function getTipo() {

    return $this->tipo;
  }

  /**
   * @param mixed $tipo
   */
  public function setTipo($tipo) {

    $this->tipo = $tipo;
  }

  /**
   * @return mixed
   */
  public function getMascara() {

    return $this->mascara;
  }

  /**
   * @param mixed $mascara
   */
  public function setMascara($mascara) {

    $this->mascara = $mascara;
  }

  /**
   * @return mixed
   */
  public function getDblayoutcampo() {

    return $this->dblayoutcampo;
  }

  /**
   * @param mixed $dblayoutcampo
   */
  public function setDblayoutcampo($dblayoutcampo) {

    $this->dblayoutcampo = $dblayoutcampo;
  }

  /**
   * @return bool
   */
  public function isPerguntaIdentificadora() {

    return $this->perguntaIdentificadora;
  }

  /**
   * @param bool $perguntaIdentificadora
   */
  public function setPerguntaIdentificadora($perguntaIdentificadora) {

    $this->perguntaIdentificadora = $perguntaIdentificadora;
  }

  /**
   * @return mixed
   */
  public function getCampoCarga() {

    return $this->campoCarga;
  }

  /**
   * @param mixed $campoCarga
   */
  public function setCampoCarga($campoCarga) {
    $this->campoCarga = $campoCarga;
  }

  /**
   * Retorna as opçcoes das Respostas Objetivas e Multipla Escolha
   * @return \ECidade\Configuracao\Formulario\Model\Opcao[]
   */
  public function getOpcoes() {

    if (empty($this->opcoes)) {
       $this->opcoes = \ECidade\Configuracao\Formulario\Repository\Pergunta::getOpcoesDaPergunta($this);
    }
    return $this->opcoes;
  }

  /**
   * @param \ECidade\Configuracao\Formulario\Model\Opcao[] $opcoes
   */
  public function setOpcoes(array $opcoes) {
    $this->opcoes = $opcoes;
  }

    public function setIdentificadorCampo($identificadorCampo)
    {
        $this->identificadorCampo = $identificadorCampo;
    }

    /**
     * Retorna o identificador do campo
     *
     * @return string
     */
    public function getIdentificadorCampo()
    {
        return $this->identificadorCampo;
    }
}
