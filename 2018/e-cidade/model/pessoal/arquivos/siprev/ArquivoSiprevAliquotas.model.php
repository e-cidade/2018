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
class ArquivoSiprevAliquotas extends ArquivoSiprevBase {

  protected $sNomeArquivo = "04-Aliquotas";

  public function __construct() {
    ArquivoSiprevBase::$aErrosProcessamento["04"] = array();
  }
  
  public function getDados() {

    $this->iAnoInicial  = str_pad($this->iAnoInicial, 4, "0", STR_PAD_LEFT);
    $this->iAnoFinal    = str_pad($this->iAnoFinal,   4, "0", STR_PAD_LEFT);
    $this->iMesInicial  = str_pad($this->iMesInicial, 2, "0", STR_PAD_LEFT);
    $this->iMesFinal    = str_pad($this->iMesFinal,   2, "0", STR_PAD_LEFT);

    $aDadosAliquotas     = array();

    $sCamposRhRegime     = "distinct rh02_tbprev,                                ";
    $sCamposRhRegime    .= "case                                                 ";
    $sCamposRhRegime    .= "     when rh30_vinculo = 'A'                         ";
    $sCamposRhRegime    .= "          then 1                                     ";
    $sCamposRhRegime    .= "     when rh30_vinculo = 'I'                         ";
    $sCamposRhRegime    .= "          then 2                                     ";
    $sCamposRhRegime    .= "     else 3                                          ";
    $sCamposRhRegime    .= " end                       as publico_alvo,          ";
    $sCamposRhRegime    .= "r33_ppatro                 as aliquota_ente,         ";
    $sCamposRhRegime    .= "r33_perc                   as aliquota_beneficiario, ";
    $sCamposRhRegime    .= "rh180_atolegal             as ato_tipo,              ";
    $sCamposRhRegime    .= "rh180_numero               as ato_numero,            ";
    $sCamposRhRegime    .= "rh180_ano                  as ato_ano,               ";
    $sCamposRhRegime    .= "rh180_datapublicacao       as ato_datapublicacao,    ";
    $sCamposRhRegime    .= "rh180_datainiciovigencia   as ato_iniciovigencia     ";

    $sWhereRhRegime      = "     rh30_utilizacao in (1, 2)";

    $sSqlDadosAliquotas  = "select {$sCamposRhRegime} ";
    $sSqlDadosAliquotas .= "  from rhpessoal ";
    $sSqlDadosAliquotas .= "       inner join rhpessoalmov                 on rh02_regist = rh01_regist ";
    $sSqlDadosAliquotas .= "                                              and rh02_anousu = {$this->iAnoInicial} ";
    $sSqlDadosAliquotas .= "                                              AND rh02_mesusu = {$this->iMesInicial} ";
    $sSqlDadosAliquotas .= "       inner join rhregime                     on rh30_codreg                = rh02_codreg ";
    $sSqlDadosAliquotas .= "       inner join inssirf                      on r33_codtab                 = rh02_tbprev ";
    $sSqlDadosAliquotas .= "                                              and r33_codtab                 > 2 ";
    $sSqlDadosAliquotas .= "                                              and r33_anousu = {$this->iAnoInicial}";
    $sSqlDadosAliquotas .= "                                              AND r33_mesusu = {$this->iMesInicial}";
    $sSqlDadosAliquotas .= "                                              and r33_instit = ".db_getsession("DB_instit");


    $sSqlDadosAliquotas .= "       left join atolegalprevidenciainssirf   on (rh180_inssirf, rh180_instituicao) = (r33_codigo, r33_instit)";
    $sSqlDadosAliquotas .= "       left join atolegalprevidencia          on rh180_atolegal                     = rh179_sequencial";
    $sSqlDadosAliquotas .= " where {$sWhereRhRegime} ";
    $sSqlDadosAliquotas .= " group by rh02_tbprev,                                      ";
    $sSqlDadosAliquotas .= "          publico_alvo,                                     ";
    $sSqlDadosAliquotas .= "          aliquota_ente,                                    ";
    $sSqlDadosAliquotas .= "          aliquota_beneficiario,                            ";
    $sSqlDadosAliquotas .= "          ato_tipo,                                         ";
    $sSqlDadosAliquotas .= "          ato_numero,                                       ";
    $sSqlDadosAliquotas .= "          ato_ano,                                          ";
    $sSqlDadosAliquotas .= "          ato_datapublicacao,                               ";
    $sSqlDadosAliquotas .= "          ato_iniciovigencia                                ";
    $sSqlDadosAliquotas .= " order by rh02_tbprev, publico_alvo, aliquota_beneficiario; ";

    $rsDadosAliquotas = db_query($sSqlDadosAliquotas);

    if(!$rsDadosAliquotas) {
      throw new DBException("Erro ao buscar as alíquotas.");
    }

    $aRetornoAliquotas = db_utils::getCollectionByRecord($rsDadosAliquotas);
    foreach($aRetornoAliquotas as $dia => $oAliquotas) {

      $aLinhas           = array("aliquotaEnte" => $this->preencheAliquotaEnte($oAliquotas, str_pad($dia+1, 2 , "0", STR_PAD_LEFT)));
      $aDadosAliquotas[] = (object) $aLinhas;
    }
    return $aDadosAliquotas;
  }

  /**
   * Retorna os elementos e propriedades do arquivo
   * @return array
   */
  public function getElementos() {
    return array($this->atributosAliquotaEnte());
  }

  /**
   * Atributos referentes ao registro aliquotaEnte
   * @return array
   */
  private function atributosAliquotaEnte() {

    $aAliquotaEnte                 = array();
    $aAliquotaEnte["nome"]         = "aliquotaEnte";
    $aAliquotaEnte["propriedades"] = array(
      "operacao",
      "publicoAlvo",
      "aliquotaBeneficiario",
      "aliquotaEnte",
      "dataInicioAliquota",
      $this->atributosAtoLegal()
    );

    return $aAliquotaEnte;
  }

  /**
   * Atributos referentes ao registro atoLegal
   * @return array
   */
  private function atributosAtoLegal() {

    $aAtoLegal                 = array();
    $aAtoLegal["nome"]         = "atoLegal";
    $aAtoLegal["propriedades"] = array("tipoAto", "numero", "ano", "dataPublicacao", "dataInicioVigencia");

    return $aAtoLegal;
  }

  /**
   * Preenche os valores do registro aliquotaEnte
   * @param  stdClass $oAliquotas
   * @return object
   */
  private function preencheAliquotaEnte($oAliquotas, $dia) {

    $aAliquotas                         = array();
    $aAliquotas["operacao"]             = "I";
    $aAliquotas["publicoAlvo"]          = $oAliquotas->publico_alvo;
    $aAliquotas["aliquotaBeneficiario"] = number_format($oAliquotas->aliquota_beneficiario, 2, '.', '');

    if($oAliquotas->publico_alvo == 1) {
      $aAliquotas["aliquotaEnte"] = number_format($oAliquotas->aliquota_ente, 2, '.', '');
    }

    $aAliquotas["dataInicioAliquota"] = "{$this->iAnoInicial}-{$this->iMesInicial}-$dia";
    $aAliquotas["atoLegal"]           = $this->preencheAtoLegal($oAliquotas);

    return (object) $aAliquotas;
  }

  /**
   * Preenche os valores referenes ao registro atoLegal
   * @return object
   */
  private function preencheAtoLegal($oAliquota) {

    $aAtoLegal                       = array();
    $aAtoLegal["tipoAto"]            = $oAliquota->ato_tipo;
    $aAtoLegal["numero"]             = $oAliquota->ato_numero;
    $aAtoLegal["ano"]                = $oAliquota->ato_ano;
    $aAtoLegal["dataPublicacao"]     = $oAliquota->ato_datapublicacao;
    $aAtoLegal["dataInicioVigencia"] = $oAliquota->ato_iniciovigencia;
    return (object) $aAtoLegal;
  }
}
