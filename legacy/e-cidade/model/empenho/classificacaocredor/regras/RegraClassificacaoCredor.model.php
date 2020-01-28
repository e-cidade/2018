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
 * Class RegraClassificacaoCredor
 * Padrão Chain of Responsability
 * Classe responsável por definir uma regra a ser aplicada a Classificação de Credores.
 */
abstract class RegraClassificacaoCredor {

  /**
   * @var RegraClassificacaoCredor
   */
  protected $oProxima;

  /**
   * @var AtributosEmpenho
   */
  protected $oAtributosEmpenho;

  /**
   * @var ListaClassificacaoCredor
   */
  protected $oListaClassificacaoCredor;

  /**
   * Configura a próxima regra a ser aplicada.
   * @param RegraClassificacaoCredor $oRegra
   */
  public function setProximo(RegraClassificacaoCredor $oRegra) {
    $this->oProxima = $oRegra;
  }

  /**
   * Configura o AtributosEmpenho
   * @param AtributosEmpenho $oAtributoEmpenho
   */
  public function setAtributoEmpenho(AtributosEmpenho $oAtributoEmpenho) {
    $this->oAtributosEmpenho = $oAtributoEmpenho;
  }

  /**
   * Configura a ListaClassificacaoCredor
   * @param ListaClassificacaoCredor $oListaClassificacaoCredor
   */
  public function setListaClassificador(ListaClassificacaoCredor $oListaClassificacaoCredor) {
    $this->oListaClassificacaoCredor = $oListaClassificacaoCredor;
  }

  /**
   * Valida se o AtributosEmpenho repeita a regra da ListaClassificacaoCredor.
   * @return bool
   */
  protected abstract function regra();

  /**
   * Aplica a regra conforme padrão Chain of Responsability.
   * @return bool
   */
  public function aplicarRegras() {

    if (!$this->regra()) {
      return false;
    }
    return $this->proximo();
  }

  /**
   *  Chama o próximo teste, se existir.
   * @return bool
   */
  protected function proximo() {

    if ($this->oProxima) {
      return $this->oProxima->aplicarRegras();
    }
    return true;
  }
}