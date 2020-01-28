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
class ArquivoSiprevCargos extends ArquivoSiprevBase {

  protected $sNomeArquivo = "07-Cargos";
  protected $sRegistro    = "cargos";

  public function __construct() {
    ArquivoSiprevBase::$aErrosProcessamento["07"] = array();
  }


  public function getDados() {

	  $sSqlDados  = " SELECT rh37_funcao,                                  \n";
	  $sSqlDados .= "        rh37_descr,                                   \n";
	  $sSqlDados .= "        cgc,                                          \n";
	  $sSqlDados .= "        nomeinst,                                     \n";
	  $sSqlDados .= "        db21_tipopoder as poder                       \n";
	  $sSqlDados .= "   from rhfuncao                                      \n";
	  $sSqlDados .= "        inner join db_config on rh37_instit = codigo  \n";
	  $sSqlDados .= "  where trim(rh37_descr) <> ''                        \n";
	  $sSqlDados .= "    and rh37_ativo is true                            \n";
	  $sSqlDados .= "  order by rh37_funcao                                \n";

    $rsDados      = db_query($sSqlDados);
    $aListaDados  = db_utils::getCollectionByRecord($rsDados);
    $aDados       = array();

    foreach ($aListaDados as $oIndiceDados => $oValorDados) {

      $aLinhas  = array("dadosCargo" => $this->preencheDadosCargo($oValorDados));
      $aDados[] = (object) $aLinhas;
    }

    return $aDados;
  }

  /*
   * Esse método é responsável por definir quais os elementos e suas propriedades que serão
   * repassadas para o arquivo que será gerado.
   */
  public function getElementos() {
    return array($this->atributosDadosCargo());
  }

  /**
   * Atributos referentes ao registro dadosCargo
   * @return array
   */
  private function atributosDadosCargo() {

    $aDadosCargo = array();
    $aDadosCargo["nome"] = "dadosCargo";
    $aDadosCargo["propriedades"] = array(
      "nome",
      "cargoAcumulacao",
      "contagemEspecial",
      "tecnicoCientifico",
      "dedicacaoExclusiva",
      "aposentadoriaEspecial",
      $this->atributosCarreira()
    );

    return $aDadosCargo;
  }

  /**
   * Atributos referentes ao registro carreira
   * @return array
   */
  private function atributosCarreira() {

    $aCarreira                 = array();
    $aCarreira["nome"]         = "carreira";
    $aCarreira["propriedades"] = array("nome", $this->atributosOrgao());

    return $aCarreira;
  }

  /**
   * Atributos referentes ao registro orgao
   * @return array
   */
  private function atributosOrgao() {

    $aOrgao                 = array();
    $aOrgao["nome"]         = "orgao";
    $aOrgao["propriedades"] = array("nome", "poder");

    return $aOrgao;
  }

  /**
   * Preenche os valores referentes ao registro dadosCargo
   * @param  stdClass $oValorDados
   * @return object
   */
  private function preencheDadosCargo($oValorDados) {

    $aDadosCargo                          = array();
    $aDadosCargo["nome"]                  = DBString::removerCaracteresEspeciais($oValorDados->rh37_descr);
    $aDadosCargo["cargoAcumulacao"]       = 1;
    $aDadosCargo["contagemEspecial"]      = 1;
    $aDadosCargo["tecnicoCientifico"]     = 0;
    $aDadosCargo["dedicacaoExclusiva"]    = 0;
    $aDadosCargo["aposentadoriaEspecial"] = 0;
    $aDadosCargo["carreira"]              = $this->preencheCarreira($oValorDados);

    return (object) $aDadosCargo;
  }

  /**
   * Preenche os valores referentes ao registro carreira
   * @param  stdClass $oValorDados
   * @return object
   */
  private function preencheCarreira($oValorDados) {

    $aCarreira          = array();
    $aCarreira["nome"]  = "Servidor Público";
    $aCarreira["orgao"] = $this->preencheOrgao($oValorDados);

    return (object) $aCarreira;
  }

  /**
   * Preenche os valores referentes ao registro orgao
   * @param  stdClass $oValorDados
   * @return object
   */
  private function preencheOrgao($oValorDados) {

    $aOrgao          = array();
    $aOrgao["nome"]  = DBString::removerCaracteresEspeciais($oValorDados->nomeinst);
    $aOrgao["poder"] = $oValorDados->poder == 0 ? 6 : $oValorDados->poder;

    return (object) $aOrgao;
  }
}
