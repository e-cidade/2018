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
class ArquivoSiprevServidor extends ArquivoSiprevBase {

	protected $sNomeArquivo = "01-Servidores";
  protected $sRegistro    = "servidores";

  public function __construct() {
    ArquivoSiprevBase::$aErrosProcessamento["01"] = array();
  }

	public function getDados() {

	  $sCamposServidor  = "distinct z01_nome, z01_numcgm, z01_estciv, rh01_instru, rh01_admiss, rh05_recis       ";
    $sCamposServidor .= ", rh01_sexo as z01_sexo, z01_mae, z01_pai, rh01_nasc as z01_nasc                      ";
    $sCamposServidor .= ", case when rh05_causa between 60 and 69                                              ";
    $sCamposServidor .= "       then rh05_recis                                                                ";
    $sCamposServidor .= "       else null                                                                      ";
    $sCamposServidor .= "   end as z01_dtfalecimento                                                           ";
    $sCamposServidor .= ", z01_cgccpf, z01_ident, rh16_pis, rh16_ctps_n, rh16_ctps_s, rh16_titele, rh16_zonael ";
    $sCamposServidor .= ", rh16_secaoe, rh01_instit                                                            ";

		$sSqlDados  = "  SELECT {$sCamposServidor}                                                            \n";
    $sSqlDados .= "   from rhpessoalmov                                                                   \n";
    $sSqlDados .= "        inner join rhpessoal     on rh02_regist = rh01_regist                          \n";
    $sSqlDados .= "        inner join cgm           on z01_numcgm  = rh01_numcgm                          \n";
    $sSqlDados .= "        inner join rhregime      on rh02_codreg = rh30_codreg                          \n";
    $sSqlDados .= "        left  join rhpesrescisao on rh05_seqpes = rh02_seqpes                          \n";
    $sSqlDados .= "        inner join rhpesdoc      on rh01_regist = rh16_regist                          \n";
    $sSqlDados .= "  where (rh02_anousu, rh02_mesusu) between ({$this->iAnoInicial},{$this->iMesInicial}) \n";
    $sSqlDados .= "                                       and ({$this->iAnoFinal},{$this->iMesFinal})     \n";
    $sSqlDados .= "    AND rh30_regime  = 1                                                               \n";
    $sSqlDados .= "    AND rh30_vinculo = 'A'                                                             \n";
    $sSqlDados .= "  order by rh01_instit, z01_nome  ";

    $rsDados      = db_query($sSqlDados);
    $aErros       = array();
    $oArquivo     = $this;
    $aListaDados  = db_utils::makeCollectionFromRecord($rsDados, function($oDados) use(&$aErros, $oArquivo) {

      if(!$aErrosRegistro = $oArquivo->validarDadosServidor($oDados)) {
				return $oDados;
			}

			foreach ($aErrosRegistro as $aErro) {
				ArquivoSiprevBase::$aErrosProcessamento["01"][] = $aErro;
			}
      return;
    });

    $aDados = array();

    foreach ($aListaDados as $oIndiceDados => $oValorDados) {

      $aLinhas                  = array("dadosPessoais", "documentos");
      $aLinhas["dadosPessoais"] = $this->preencheDadosPessoais($oValorDados);
      $aLinhas["documentos"]    = $this->preencheDocumentos($oValorDados);

      $aDados[] = (object) $aLinhas;
    }

    $_SESSION['erro_servidores'] = $aErros;

    return $aDados;
  }

  /**
   * Preenche os campos referentes aos dados pessoais do servidor
   * @param  stdClass $oValorDados
   * @return object
   */
  private function preencheDadosPessoais($oValorDados) {

    $aDadosPessoais                   = array();
    $aDadosPessoais["nome"]           = DBString::removerCaracteresEspeciais($oValorDados->z01_nome);
    $aDadosPessoais["dataNascimento"] = $oValorDados->z01_nasc;

    if($oValorDados->z01_sexo == 'M' || $oValorDados->z01_sexo == 'F') {
      $aDadosPessoais["sexo"] = strtoupper($oValorDados->z01_sexo);
    }

    $aDadosPessoais["nomeMae"]        = DBString::removerCaracteresEspeciais($oValorDados->z01_mae);
    $aDadosPessoais["nomePai"]        = DBString::removerCaracteresEspeciais($oValorDados->z01_pai);

    /**
     * Valida se estado civil foi informado. Caso não tenha sido ou não se encontre dentro das opções existentes para o
     * Siprev, o atributo não é enviado
     */
    if(!empty($oValorDados->z01_estciv)) {

      $aEstadoCivil      = array();
      $aEstadoCivil["1"] = 1;
      $aEstadoCivil["2"] = 2;
      $aEstadoCivil["3"] = 3;
      $aEstadoCivil["4"] = 5;
      $aEstadoCivil["5"] = 4;
      $aEstadoCivil["6"] = 4;
      $aEstadoCivil["7"] = 6;

      if(array_key_exists($oValorDados->z01_estciv, $aEstadoCivil)) {
        $aDadosPessoais["estadoCivil"] = $aEstadoCivil[$oValorDados->z01_estciv];
      }
    }

    if(!empty($oValorDados->z01_dtfalecimento)) {
      $aDadosPessoais["dataFalecimento"] = $oValorDados->z01_dtfalecimento;
    }

    /**
     * Valida se a escolaridade foi informada. Caso não tenha sido ou não se encontre dentro das opções existentes para o
     * Siprev, o atributo não é enviado
     */
    if(!empty($oValorDados->rh01_instru)) {

      $aEscolaridade      = array();
      $aEscolaridade["1"] = 1;
      $aEscolaridade["2"] = 3;
      $aEscolaridade["3"] = 3;
      $aEscolaridade["4"] = 3;
      $aEscolaridade["5"] = 4;
      $aEscolaridade["6"] = 5;
      $aEscolaridade["7"] = 6;
      $aEscolaridade["8"] = 7;
      $aEscolaridade["9"] = 8;

      if(array_key_exists($oValorDados->rh01_instru, $aEscolaridade)) {
        $aDadosPessoais["escolaridade"] = $aEscolaridade[$oValorDados->rh01_instru];
      }
    }

    if(!empty($oValorDados->rh01_admiss)) {
      $aDadosPessoais["dataIngressoServicoPublico"] = $oValorDados->rh01_admiss;
    }

    return (object) $aDadosPessoais;
  }

  /**
   * Preenche os campos referentes aos documentos do servidor
   * @param  stdClass $oValorDados
   * @return object
   */
  private function preencheDocumentos($oValorDados) {

    $aDocumentos = array();

    if(trim($oValorDados->z01_cgccpf) != '') {
      $aDocumentos["numeroCPF"] = $oValorDados->z01_cgccpf;
    }

    if(trim($oValorDados->rh16_pis) != '' && $oValorDados->rh16_pis != '00000000000') {
      $aDocumentos["numeroNIT"] = $oValorDados->rh16_pis;
    }

    $aDocumentos["numeroRG"] = $oValorDados->z01_ident;

    if(!empty($oValorDados->rh16_ctps_n)) {
      $aDocumentos["numeroCTPS"] = $oValorDados->rh16_ctps_n;
    }

    $aDocumentos["serieCTPS"] = $oValorDados->rh16_ctps_s;

    if(!empty($oValorDados->rh16_titele)) {
      $aDocumentos["numeroTituloEleitor"] = $oValorDados->rh16_titele;
    }

    if(!empty($oValorDados->rh16_zonael)) {
      $aDocumentos["zonaTituloEleitor"] = $oValorDados->rh16_zonael;
    }

    if(!empty($oValorDados->rh16_secaoe)) {
      $aDocumentos["secaoTituloEleitor"] = $oValorDados->rh16_secaoe;
    }

    return (object) $aDocumentos;
  }

  /*
   * Esse método é responsável por definir quais os elementos e suas propriedades que serão
   * repassadas para o arquivo que será gerado.
   */
  public  function getElementos() {

    $aDados   = array();
    $aDados[] = $this->atributosDadosPessoais();
    $aDados[] = $this->atributosDocumentos();

    return $aDados;
  }

  /**
   * Atributos existentes para os dados pessoais do servidor
   * @return array
   */
  private function atributosDadosPessoais() {

    $aDadosPessoais                 = array();
    $aDadosPessoais["nome"]         = "dadosPessoais";
    $aDadosPessoais["propriedades"] = array(
      "nome",
      "estadoCivil",
      "dataNascimento",
      "dataFalecimento",
      "escolaridade",
      "sexo",
      "nomeMae",
      "nomePai",
      "dataIngressoServicoPublico"
    );

    return $aDadosPessoais;
  }

  /**
   * Atributos existentes para os documentos do servidor
   * @return array
   */
  private function atributosDocumentos() {

    $aDadosDocumentos                 = array();
    $aDadosDocumentos["nome"]         = "documentos";
    $aDadosDocumentos["propriedades"] = array(
      "numeroCPF",
      "numeroNIT",
      "numeroRG",
      "numeroCTPS",
      "serieCTPS",
      "numeroTituloEleitor",
      "zonaTituloEleitor",
      "secaoTituloEleitor"
    );

    return $aDadosDocumentos;
  }

  /**
   * Realiza as validações dos campos
   * @param  stdClass $oDados
   * @return array
   */
  public function validarDadosServidor($oDados) {

    $aErrosRegistro = array();
    $lPisValido     = DBString::isPIS($oDados->rh16_pis);
    $lCpfValido     = DBString::isCPF($oDados->z01_cgccpf);
    $lSexoValido    = $oDados->z01_sexo == "M" || $oDados->z01_sexo == "F";

    if(!$lPisValido) {
      $aErrosRegistro[] = $this->getErro($oDados, "PIS '{$oDados->rh16_pis}' é inválido.");
    }

    if(!$lCpfValido) {
      $aErrosRegistro[] = $this->getErro($oDados, "CPF '{$oDados->z01_cgccpf}' é inválido.");
    }

    if(!$lSexoValido) {
      $aErrosRegistro[] = $this->getErro($oDados, "Sexo '{$oDados->z01_sexo}' é inválido.");
    }

    return $aErrosRegistro;
  }

  /**
   * Monta o array dos erros com os dados para apresentação no relatório
   * @param  stdClass $oDados
   * @param  string $sErro
   * @return array
   */
  private function getErro($oDados, $sErro) {

    return array(
			InstituicaoRepository::getInstituicaoByCodigo($oDados->rh01_instit)->getDescricao(),
      $oDados->z01_numcgm . " - " . $oDados->z01_nome,
			$sErro,
    );
  }
}
