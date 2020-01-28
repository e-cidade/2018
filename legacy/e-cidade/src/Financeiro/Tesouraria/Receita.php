<?php
/*
*     E-cidade Software Publico para Gestao Municipal
*  Copyright (C) 2016  DBselller Servicos de Informatica
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
namespace ECidade\Financeiro\Tesouraria;

use \ECidade\Financeiro\Tesouraria\Repository\Receita as ReceitaRepository;

class Receita {

  /**
   * @var integer
   */
  protected $codigo;

  /**
   * @var string
   */
  protected $nome;

  /**
   * Receita de Multa
   * @var int
   */
  protected $receitaMulta;

  /**
   * Receita de Juros
   * @var int
   */
  protected $receitaJuros;

  /**
   * @var string
   */
  protected $descricao;

  /**
   * @var stdClass
   */
  protected $dadosReceita;

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->codigo;
  }

  /**
   * @param int $codigo
   */
  public function setCodigo($codigo) {
    $this->codigo = $codigo;
  }

  /**
   * @return string
   */
  public function getDescricao() {
    return $this->descricao;
  }

  /**
   * @param string $descricao
   */
  public function setDescricao($descricao) {
    $this->descricao = $descricao;
  }

  /**
   * @return string
   */
  public function getNome() {
    return $this->nome;
  }

  /**
   * @param string $nome
   */
  public function setNome($nome) {
    $this->nome = $nome;
  }

  /**
   * @return \ECidade\Financeiro\Tesouraria\Receita
   */
  public function getReceitaMulta() {

    if (!empty($this->dadosReceita->k02_recmul) && empty($this->receitaMulta)) {

      try {

        $oReceitaMulta      = ReceitaRepository::getById($this->dadosReceita->k02_recmul);
        $this->receitaMulta = $oReceitaMulta;
      } catch (\BusinessException $businessException) {
        $this->dadosReceita->k02_recmul = null;
      }
    }
    return $this->receitaMulta;
  }

  /**
   * @param \ECidade\Financeiro\Tesouraria\Receita $receitaMulta
   */
  public function setReceitaMulta($receitaMulta) {
    $this->receitaMulta = $receitaMulta;
  }

  /**
   * @return \ECidade\Financeiro\Tesouraria\Receita
   */
  public function getReceitaJuros() {

    if (!empty($this->dadosReceita->k02_recjur) && empty($this->receitaJuros)) {

      try {

        $oReceitaJuros = ReceitaRepository::getById($this->dadosReceita->k02_recjur);
        $this->receitaJuros = $oReceitaJuros;
      } catch (\BusinessException $businessException) {
        $this->dadosReceita->k02_recjur = null;
      }
    }

    return $this->receitaJuros;
  }

  /**
   * @param \ECidade\Financeiro\Tesouraria\Receita $receitaJuros
   */
  public function setReceitaJuros($receitaJuros) {
    $this->receitaJuros = $receitaJuros;
  }

  /**
   * @param mixed $dadosReceita
   */
  public function setDadosReceita($dadosReceita) {
    $this->dadosReceita = $dadosReceita;
  }

  /**
   * Retorna dados da receita
   */
  public function getDadosReceita() {
    return $this->dadosReceita;
  }
   
}
