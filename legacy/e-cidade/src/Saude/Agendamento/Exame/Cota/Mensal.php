<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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

namespace ECidade\Saude\Agendamento\Exame\Cota;

abstract class Mensal
{
  /**
   * Quantidade de Fichas liberadas na cota mensal
   */
  protected $iQuantidade;

  /**
   * Mês referente ao uso da cota
   */
  protected $iMes;

  /**
   * Ano referente ao uso da cota
   */
  protected $iAno;

  /**
   * Código que identifica o prestador e o exame pertencentes da cota
   */
  protected $oInformacaoAdicional;

  /**
   * Nome da cota mensal
   */
  protected $sNome;

  /**
   * Busca Quantidade de Fichas liberadas na cota mensal.
   *
   * @return mixed
   */
  public function getQuantidade()
  {
    return $this->iQuantidade;
  }

  /**
   * Altera Quantidade de Fichas liberadas na cota mensal.
   *
   * @param mixed $iQuantidade
   *
   * @return self
   */
  public function setQuantidade($iQuantidade)
  {
    $this->iQuantidade = $iQuantidade;

    return $this;
  }

  /**
   * Busca Mês referente ao uso da cota.
   *
   * @return mixed
   */
  public function getMes()
  {
    return $this->iMes;
  }

  /**
   * Altera Mês referente ao uso da cota.
   *
   * @param mixed $iMes
   *
   * @return self
   */
  public function setMes($iMes)
  {
    $this->iMes = $iMes;

    return $this;
  }

  /**
   * Busca Ano referente ao uso da cota.
   *
   * @return mixed
   */
  public function getAno()
  {
    return $this->iAno;
  }

  /**
   * Altera Ano referente ao uso da cota.
   *
   * @param mixed $iAno
   *
   * @return self
   */
  public function setAno($iAno)
  {
    $this->iAno = $iAno;

    return $this;
  }

  /**
   * Gets the Código que identifica o prestador e o exame pertencentes da cota.
   *
   * @return object
   */
  public function getInformacaoAdicional()
  {
    return $this->oInformacaoAdicional;
  }

  /**
   * Sets the Código que identifica o prestador e o exame pertencentes da cota.
   *
   * @param object $oInformacaoAdicional informacao adicional
   * @return self
   */
  public function setInformacaoAdicional($oInformacaoAdicional)
  {
    $this->oInformacaoAdicional = $oInformacaoAdicional;

    return $this;
  }

  /**
   * Gets the Nome da cota mensal.
   *
   * @return string
   */
  public function getNome()
  {
    return $this->sNome;
  }

  /**
   * Sets the Nome da cota mensal.
   *
   * @param mixed $sNome the s nome
   *
   * @return self
   */
  public function setNome($sNome)
  {
    $this->sNome = $sNome;

    return $this;
  }
}