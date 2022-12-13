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

require_once(modification('model/pessoal/arquivos/siprev/ArquivoSiprevBase.model.php'));

class ArquivoSiprevOrgao extends  ArquivoSiprevBase {

  protected $sNomeArquivo = "03-Orgaos";
  protected $sRegistro    = "orgaos";

  public function __construct() {
    ArquivoSiprevBase::$aErrosProcessamento["03"] = array();
  }
  public function getDados() {


	  $sSqlDados  = <<<SQL
SELECT codigo,
       nomeinst,
       cgc,
       nomeinstabrev,
       db21_esfera,
       db21_tipopoder,
       db21_tipoinstit
  from db_config;
SQL;

    $rsDados      = db_query($sSqlDados);

    $aErros       = array();

    if (strlen($this->iNumeroAto) > 12) {
      $aErros[] = $this->getErro("", "Número do Ato Legal é maior que 12 caracteres.");
    }
    $oArquivo    = $this;
    $aListaDados = db_utils::makeCollectionFromRecord($rsDados, function($dados) use(&$aErros, $oArquivo) {

      if (!$aErrosRegistro = $oArquivo->validarDadosInstituicao($dados)) {
        return $dados;
      }

      foreach ($aErrosRegistro as $erro) {
        ArquivoSiprevBase::$aErrosProcessamento["03"][] = $erro;
      }

      return;

    });

    /**
     * Caso existam erros, não retorna registros.
     */
    if (count($aErros) > 0) {

      ArquivoSiprevBase::$aErrosProcessamento["03"] = array_merge($aErros, ArquivoSiprevBase::$aErrosProcessamento["03"]);
      return array();
    }

    $aDados       = array();

    foreach ($aListaDados as $oIndiceDados => $oValorDados) {
      $aDados[] = (object)array("dadosOrgao" => $this->preencheDadosOrgao($oValorDados));
    }

    return $aDados;
  }

  public function validarDadosInstituicao($oValorDados) {

    $aErrosRegistro   = array();
    $oValorDados->cgc = trim($oValorDados->cgc);

    if( !empty($oValorDados->cgc) && !DBString::isCNPJ($oValorDados->cgc)) {
      $aErrosRegistro[] = $this->getErro("{$oValorDados->codigo} - {$oValorDados->nomeinst}", "O CNPJ '{$oValorDados->cgc}' é inválido.");
    }

    return $aErrosRegistro;
  }

  private function getErro($Nome, $sErro) {
    return array($Nome, $sErro);
  }

  /*
   * Esse método é responsável por definir quais os elementos e suas propriedades que serão
   * repassadas para o arquivo que será gerado.
   */
  public function getElementos() {
    return array($this->atributosDadosOrgao());
  }

  /**
   * Retorna os atributos referentes ao registro dadosOrgao
   * @return array
   */
  private function atributosDadosOrgao() {

    return array(
      "nome"  => "dadosOrgao",
      "propriedades" => array(
        "nome",
        "razaoSocial",
        "sigla",
        "cnpj",
        "esfera",
        "poder",
        "gestora",
        "naturezaJuridica",
        $this->atributosUnidadeGestora()
      )
    );
  }

  /**
   * Retorna os atributos referentes ao registro unidadeGestora
   * @return array
   */
  private function atributosUnidadeGestora() {

    return array(
      "nome" => "unidadeGestora",
      "propriedades" => array(
        $this->atributosAtoLegal(),
        $this->atributosRepresentanteLegal(),
      )
    );
  }

  /**
   * Retorna os atributos referentes ao registro atoLegal
   * @return array
   */
  private function atributosAtoLegal() {

    return array(
      "nome"         => "atoLegal",
      "propriedades" => array(
        "tipoAto",
        "numero",
        "ano",
        "dataInicioVigencia",
        "dataPublicacao",
      ),
    );
  }

  /**
   * Retorna os atributos referentes ao registro representanteLegal
   * @return array
   */
  private function atributosRepresentanteLegal() {

    return array(
      "nome"         => "representanteLegal",
      "propriedades" => array(
        "nome",
      )
    );
  }

  /**
   * Preenche os valores do registro dadosOrgao
   * @param  stdClass $oValorDados
   * @return object
   */
  private function preencheDadosOrgao($oValorDados) {

    $sAbreviatura = strlen($oValorDados->nomeinstabrev) > 10 ? substr($oValorDados->nomeinstabrev, 0, 10) : $oValorDados->nomeinstabrev;

    $aDadosOrgao                     = array();
    $aDadosOrgao["nome"]             = DBString::removerCaracteresEspeciais($oValorDados->nomeinst);
    $aDadosOrgao["razaoSocial"]      = DBString::removerCaracteresEspeciais($oValorDados->nomeinst);
    $aDadosOrgao["sigla"]            = DBString::removerCaracteresEspeciais($sAbreviatura);

    if(!empty($oValorDados->cgc)) {
      $aDadosOrgao["cnpj"]             = $oValorDados->cgc;
    }
    $aDadosOrgao["esfera"]           = $oValorDados->db21_esfera    != 0 ? $oValorDados->db21_esfera : 3;
    $aDadosOrgao["poder"]            = $oValorDados->db21_tipopoder == 0 ? 6 : $oValorDados->db21_tipopoder;
    $aDadosOrgao["naturezaJuridica"] = $oValorDados->db21_tipoinstit > 6 ? 99 : $oValorDados->db21_tipoinstit;
    $aDadosOrgao["gestora"]          = 0;

    if($oValorDados->codigo == $this->iUnidadeGestora) {

      $aDadosOrgao["gestora"]        = 1;
      $aDadosOrgao["unidadeGestora"] = $this->preencheUnidadeGestora();
    }

    return (object) $aDadosOrgao;
  }

  /**
   * Preenche os valores do registro unidadeGestora
   * @return object
   */
  private function preencheUnidadeGestora() {

    $aUnidadeGestora                       = array();
    $aUnidadeGestora["atoLegal"]           = $this->preencheAtoLegal();
    $aUnidadeGestora["representanteLegal"] = $this->preencheRepresentanteLegal();

    return (object) $aUnidadeGestora;
  }

  /**
   * Preenche os valores do registro atoLegal
   * @return object
   */
  private function preencheAtoLegal() {

    $aAtoLegal      = array();
    $DataPublicacao = substr($this->dDataAto,6,4).'-'.substr($this->dDataAto,3,2).'-'.substr($this->dDataAto,0,2);

    $aAtoLegal["tipoAto"]            = $this->iTipoAto;
    $aAtoLegal["numero"]             = $this->iNumeroAto;
    $aAtoLegal["ano"]                = $this->iAnoAto;
    $aAtoLegal["dataInicioVigencia"] = $DataPublicacao;
    $aAtoLegal["dataPublicacao"]     = $DataPublicacao;

    return (object) $aAtoLegal;
  }

  /**
   * Preenche os valores do registro representanteLegal
   * @return object
   */
  private function preencheRepresentanteLegal() {
    return (object) array(
      "nome" => $this->cRepresentante
    );
  }
}
