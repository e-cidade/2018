<?php
/**
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
class ArquivoSiprevCarreira extends  ArquivoSiprevBase {

  protected $sNomeArquivo = "06-Carreiras";
  protected $sRegistro    = "carreiras";

  public function __construct() {
    ArquivoSiprevBase::$aErrosProcessamento["06"] = array();
  }
  /*
   * Essa classe não possui um metodo getDados Proprio,
   * para tanto, percorremos o retorno do metodo getDados da classe ArquivoSiprevOrgao
   */
  public function getDados() {

  	$oDadosOrgao    = new ArquivoSiprevOrgao();
  	$aDadosOrgao    = $oDadosOrgao->getDados();
  	$aDadosCarreira = array();

  	foreach ($aDadosOrgao as $oIndiceDados => $oValorDados) {

      $aLinhas          = array("dadosCarreira" => $this->preencheDadosCarreira($oValorDados));
      $aDadosCarreira[] = (object) $aLinhas;
  	}

  	return $aDadosCarreira;
  }

  /*
   * Esse método é responsável por definir quais os elementos e suas propriedades que serão
   * repassadas para o arquivo que será gerado
   */
  public function getElementos() {
    return array($this->atributosDadosCarreira());
  }

  /**
   * Atributos referentes ao registro dadosCarreira
   * @return array
   */
  private function atributosDadosCarreira() {

    $aDadosCarreira                 = array();
    $aDadosCarreira["nome"]         = "dadosCarreira";
    $aDadosCarreira["propriedades"] = array("nome", $this->atributosOrgao());

    return $aDadosCarreira;
  }

  /**
   * Atributos referentes ao registro orgao
   * @return array
   */
  private function atributosOrgao() {

    $aDadosOrgao                 = array();
    $aDadosOrgao["nome"]         = "orgao";
    $aDadosOrgao["propriedades"] = array("nome", "poder");

    return $aDadosOrgao;
  }

  /**
   * Preenche os valores do registro dadosCarreira
   * @param  stdClass $oValorDados
   * @return object
   */
  private function preencheDadosCarreira($oValorDados) {

    $aDadosCarreira          = array();
    $aDadosCarreira["nome"]  = "Servidor Público";
    $aDadosCarreira["orgao"] = $this->preencheOrgao($oValorDados);

    return (object) $aDadosCarreira;
  }

  /**
   * Preenche os valores do registro orgao
   * @param  stdClass $oValorDados
   * @return object
   */
  private function preencheOrgao($oValorDados) {

    $aOrgao          = array();
    $aOrgao["nome"]  = DBString::removerCaracteresEspeciais($oValorDados->dadosOrgao->nome);
    $aOrgao["poder"] = $oValorDados->dadosOrgao->poder;

    return (object) $aOrgao;
  }
}
