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
class ArquivoSiprevTempoContribuicaoRGPS extends ArquivoSiprevBase {

  protected $sNomeArquivo = "12-TempoContribuicaoRGPS";

  public function __construct() {
    ArquivoSiprevBase::$aErrosProcessamento["12"] = array();
  }

  public function getDados() {

    $sCamposTempoContribuicao  = "rh02_regist, rh02_instit, rh01_numcgm, z01_nome, z01_cgccpf, z01_pis, z01_nasc";
    $sCamposTempoContribuicao .= ", z01_mae, rh37_descr, h31_dtportaria, h16_dtconc, h16_dtterm, h16_quant, h31_numero";
    $sCamposTempoContribuicao .= ", h16_codigo";

    $sCondicaoAssenta  = "    (((rh02_anousu, rh02_mesusu) = (extract(year from h16_dtconc), extract(month from h16_dtconc))) ";
    $sCondicaoAssenta .= " OR (     (rh02_anousu, rh02_mesusu) >= (extract(year from h16_dtconc), extract(month from h16_dtconc)) ";
    $sCondicaoAssenta .= "      and h16_dtterm is null) ";
    $sCondicaoAssenta .= " OR (     (rh02_anousu, rh02_mesusu) between (extract(year from h16_dtconc), extract(month from h16_dtconc))";
    $sCondicaoAssenta .= "      and (extract(year from h16_dtterm), extract(month from h16_dtterm)) ))";

    $sSqlTempoContribuicao  = "select {$sCamposTempoContribuicao}                                            \n";
    $sSqlTempoContribuicao .= "  from rhpessoal                                                              \n";
    $sSqlTempoContribuicao .= "       inner join rhpessoalmov     on rh02_regist = rh01_regist               \n";
    $sSqlTempoContribuicao .= "       inner join rhregime         on rh30_codreg = rh02_codreg               \n";
    $sSqlTempoContribuicao .= "       inner join cgm              on z01_numcgm  = rh01_numcgm               \n";
    $sSqlTempoContribuicao .= "       inner join rhparam          on h36_instit  = rh01_instit               \n";
    $sSqlTempoContribuicao .= "       inner join assenta          on h16_regist  = rh02_regist               \n";
    $sSqlTempoContribuicao .= "                                  AND h16_assent  = h36_tempocontribuicaorgps \n";
    $sSqlTempoContribuicao .= "                                  AND {$sCondicaoAssenta}                     \n";
    $sSqlTempoContribuicao .= "       inner join portariaassenta  on h33_assenta  = h16_codigo               \n";
    $sSqlTempoContribuicao .= "       inner join portaria         on h33_portaria = h31_sequencial           \n";
    $sSqlTempoContribuicao .= "       inner join rhfuncao         on rh37_funcao  = rh01_funcao              \n";
    $sSqlTempoContribuicao .= "                                  AND rh37_instit  = rh01_instit              \n";
    $sSqlTempoContribuicao .= " where rh02_anousu  = {$this->iAnoInicial}                                    \n";
    $sSqlTempoContribuicao .= "   AND rh02_mesusu  = {$this->iMesInicial}                                    \n";
    $rsDadosRetorno         = db_query($sSqlTempoContribuicao);

    if (!$rsDadosRetorno) {
      throw new DBException('Erro ao buscar os dados de tempo de contribuição RGPS.');
    }

    $aErros        = array();
    $aDadosRetorno = db_utils::makeCollectionFromRecord($rsDadosRetorno, function ($oDadosRetorno) use (&$aErros) {

      if ($aErrosRegistro = $this->validarDados($oDadosRetorno)) {

        while (list($chave, $erro) = each($aErrosRegistro)) {
          $aErros[] = $erro;
        }

        return null;
      }

      return $oDadosRetorno;
    });

    if (count($aErros) > 0) {
      ArquivoSiprevBase::$aErrosProcessamento["12"] = array_merge($aErros, ArquivoSiprevBase::$aErrosProcessamento["12"]);
    }

    $aDadosTempoContribuicao = array();

    foreach($aDadosRetorno as $oDadosRetorno) {

      $oTempoContribuicaoRGPS = $this->preencheTempoContribuicaoRGPS($oDadosRetorno);
      if ($oTempoContribuicaoRGPS != null) {

        $aLinhas                   = array('tempoContribuicaoRGPS' => $oTempoContribuicaoRGPS);
        $aDadosTempoContribuicao[] = (object)$aLinhas;
      }
    }

    return $aDadosTempoContribuicao;
  }

  /**
   * Retorna os elementos e propriedades do arquivo
   * @return array
   */
  public function getElementos() {
    return array($this->atributosTempoContribuicaoRGPS());
  }

  /**
   * Atributos referentes ao registro tempoContribuicaoRGPS
   * @return array
   */
  private function atributosTempoContribuicaoRGPS() {

    $aTempoContribuicao                 = array();
    $aTempoContribuicao['nome']         = 'tempoContribuicaoRGPS';
    $aTempoContribuicao['propriedades'] = array(
      'operacao',
      'numCertidao',
      'dtEmissao',
      'numeroNIT',
      'dtInicial',
      'dtFinal',
      'tempoLiqAnoMesDia',
      'numeroDias',
      'cargo',
      $this->atributosServidor()
    );

    return $aTempoContribuicao;
  }

  /**
   * Atributos referentes ao registro servidor
   * @return array
   */
  private function atributosServidor() {

    $aServidor                 = array();
    $aServidor['nome']         = 'servidor';
    $aServidor['propriedades'] = array('nome', 'numeroCPF', 'numeroNIT', 'dataNascimento', 'nomeMae');

    return $aServidor;
  }

  /**
   * Preenche os valores dos atributos do registro tempoContribuicaoRGPS
   * @param  stdClass $oDadosRetorno
   * @return object
   */
  private function preencheTempoContribuicaoRGPS($oDadosRetorno) {

    $aDatasIntervalo = DBDate::getIntervaloEntreDatas(
      new DBDate($oDadosRetorno->h16_dtconc),
      new DBDate($oDadosRetorno->h16_dtterm)
    );

    $iAno = str_pad($aDatasIntervalo->format('%y'), 2, '0', STR_PAD_LEFT);
    $iMes = str_pad($aDatasIntervalo->format('%m'), 2, '0', STR_PAD_LEFT);
    $iDia = str_pad($aDatasIntervalo->format('%d'), 2, '0', STR_PAD_LEFT);

    $aTempoContribuicao                      = array();
    $aTempoContribuicao['operacao']          = 'I';
    $aTempoContribuicao['numCertidao']       = $oDadosRetorno->h31_numero;
    $aTempoContribuicao['dtEmissao']         = $oDadosRetorno->h31_dtportaria;
    $aTempoContribuicao['numeroNIT']         = $oDadosRetorno->z01_pis;
    $aTempoContribuicao['dtInicial']         = $oDadosRetorno->h16_dtconc;
    $aTempoContribuicao['dtFinal']           = $oDadosRetorno->h16_dtterm;
    $aTempoContribuicao['tempoLiqAnoMesDia'] = "{$iAno}/{$iMes}/{$iDia}";
    $aTempoContribuicao['numeroDias']        = $oDadosRetorno->h16_quant;
    $aTempoContribuicao['cargo']             = $oDadosRetorno->rh37_descr;
    $aTempoContribuicao['servidor']          = $this->preencheServidor($oDadosRetorno);

    return (object) $aTempoContribuicao;
  }

  /**
   * Preenche os valores dos atributos do registro servidor
   * @param stdClass $oDadosRetorno
   * @return object
   */
  private function preencheServidor($oDadosRetorno) {

    $aServidor                   = array();
    $aServidor['nome']           = $oDadosRetorno->z01_nome;
    $aServidor['dataNascimento'] = $oDadosRetorno->z01_nasc;
    $aServidor['nomeMae']        = $oDadosRetorno->z01_mae;

    if($oDadosRetorno->z01_cgccpf != '') {
      $aServidor['numeroCPF'] = $oDadosRetorno->z01_cgccpf;
    }

    if($oDadosRetorno->z01_pis != '') {
      $aServidor['numeroNIT'] = $oDadosRetorno->z01_pis;
    }

    return (object) $aServidor;
  }

  /**
   * Realiza as validações dos campos
   * @param stdClass $oDadosRetorno
   * @return array
   */
  private function validarDados($oDadosRetorno) {

    $aErrosRegistro = array();
    $lPisValido     = DBString::isPIS($oDadosRetorno->z01_pis);
    $lCpfValido     = DBString::isCPF($oDadosRetorno->z01_cgccpf);

    if(!$lPisValido) {
      $aErrosRegistro[] = $this->getErro($oDadosRetorno, "PIS inválido.");
    }

    if(!$lCpfValido) {
      $aErrosRegistro[] = $this->getErro($oDadosRetorno, "CPF inválido.");
    }

    if($oDadosRetorno->z01_nasc == '') {
      $aErrosRegistro[] = $this->getErro($oDadosRetorno, "Data de nascimento não informada.");
    }

    if($oDadosRetorno->z01_mae == '') {
      $aErrosRegistro[] = $this->getErro($oDadosRetorno, "Nome da mãe não informado.");
    }

    if($oDadosRetorno->h16_dtterm == '') {
      $aErrosRegistro[] = $this->getErro($oDadosRetorno, "Data final do assentamento não informada.");
    }

    return $aErrosRegistro;
  }

  /**
   * Monta o array dos erros com os dados para apresentação no relatório
   * @param stdClass $oDadosRetorno
   * @param $sErro
   * @return array
   */
  private function getErro($oDadosRetorno, $sErro) {

    return array(
      "assentamento" => $oDadosRetorno->h16_codigo,
      "instituicao"  => InstituicaoRepository::getInstituicaoByCodigo($oDadosRetorno->rh02_instit)->getDescricao(),
      "nome"         => $oDadosRetorno->rh01_numcgm . " - " . $oDadosRetorno->z01_nome,
      "erro"         => $sErro,
    );
  }
}
