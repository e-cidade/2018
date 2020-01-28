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
 * Representa a estrura da Configuração da Nota
 * @todo Existe a configuração na Escola e Secretaria, por enquanto só foi implementado SecretariaEstruturalNota
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 *
 */
abstract class EstruturalNota {

  const ESTRUTURAL_NOTA = "educacao.escola.EstruturalNota.";

  /**
   * Codigo da estrutura
   * @var interger
   */
  protected $iCodigo;

  /**
   * Estrutual da nota
   * @var DBEstrutura
   */
  protected $oDBEstrutura;

  protected $lAtivo;

  /**
   * Se deve arredondar a nota.
   * @var boolean
   */
  protected $lArredondaMedia;

  /**
   * Quando $lArredondaMedia for sim, podemos informar uma regra para efeturar o arredondamento
   * @var RegraArredondamentoVO
   */
  protected $oRegraArredondamento = null;

  /**
   * Observação
   * @var string
   */
  protected $sObservacao;

  /**
   * Ano em que a regra é valida
   * @var integer
   */
  protected $iAno;

  /**
   * Getter codigo
   * @param integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Getter estrutural utilizado
   * @param DBEstrutura
   */
  public function getEstrutural() {
    return $this->oDBEstrutura;
  }

  /**
   * Getter ativo
   * @param boolean
   */
  public function isAtivo() {
    return $this->lAtivo;
  }

  /**
   * Getter se deve arredondar a nota
   * @param boolean
   */
  public function deveArredondarMedia() {
    return $this->lArredondaMedia;
  }

  /**
   * Getter regra de arredondamento
   * @param RegraArredondamentoVO
   */
  public function getRegraArredondamento() {
    return $this->oRegraArredondamento;
  }

  /**
   * Getter ano
   * @param integer
   */
  public function getAno() {
    return $this->iAno;
  }

  public function getObservacao() {
    return $this->sObservacao;
  }

}