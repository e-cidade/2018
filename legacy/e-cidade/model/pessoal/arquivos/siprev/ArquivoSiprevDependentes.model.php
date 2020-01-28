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
class ArquivoSiprevDependentes extends  ArquivoSiprevBase {

  protected $sNomeArquivo = "02-Dependentes";
  protected $sRegistro    = "dependentes";

  public function __construct() {
    ArquivoSiprevBase::$aErrosProcessamento["02"] = array();
  }

  public function getDados() {

    $sCamposDados  = "distinct z01_nome, rh01_instit,                                                            \n";
    $sCamposDados .= "         rh16_pis,                                                                         \n";
    $sCamposDados .= "         z01_numcgm,                                                                       \n";
    $sCamposDados .= "         rh31_nome,                                                                        \n";
    $sCamposDados .= "         rh31_irf,                                                                         \n";
    $sCamposDados .= "         rh01_admiss,                                                                      \n";
    $sCamposDados .= "         rh31_dtnasc,                                                                      \n";
    $sCamposDados .= "         fc_idade(rh31_dtnasc, '".date('Y-m-d', db_getsession('DB_datausu'))."') as idade, \n";
    $sCamposDados .= "         rh31_depend,                                                                      \n";
    $sCamposDados .= "         z01_cgccpf,                                                                       \n";
    $sCamposDados .= "         case when rh31_dtnasc > rh01_admiss                                               \n";
    $sCamposDados .= "              then rh31_dtnasc                                                             \n";
    $sCamposDados .= "              else rh01_admiss                                                             \n";
    $sCamposDados .= "         end as inicio_depenca                                                             \n";

  	$sSqlDados    = " SELECT {$sCamposDados}                                                                    \n";
    $sSqlDados   .= "   from rhpessoal                                                                          \n";
    $sSqlDados   .= "        inner join rhdepend     on rh31_regist = rh01_regist                               \n";
    $sSqlDados   .= "        inner join rhpessoalmov on rh02_regist = rh01_regist                               \n";
    $sSqlDados   .= "        inner join cgm          on z01_numcgm  = rh01_numcgm                               \n";
    $sSqlDados   .= "        inner join rhpesdoc     on rh16_regist = rh01_regist                               \n";
    $sSqlDados   .= "        inner join rhregime     on rh02_codreg = rh30_codreg                               \n";
    $sSqlDados   .= "  where rh02_anousu     = {$this->iAnoInicial}                                             \n";
    $sSqlDados   .= "        AND rh02_mesusu = {$this->iMesInicial}                                             \n";
    $sSqlDados   .= "        and rh30_regime = 1                                                                \n";
    $sSqlDados   .= "        and (rh31_irf in('1','2','4','5','6','7'))                                         \n";
    $sSqlDados   .= "        AND rh30_vinculo = 'A'                                                             \n";
    $sSqlDados   .= "order by rh01_instit, z01_nome, rh31_nome;                                                 \n";

    $rsDados      = db_query($sSqlDados);
    $aErros       = array();

    $oArquivo    = $this;
    $aListaDados = db_utils::makeCollectionFromRecord($rsDados, function($oDados) use(&$aErros, $oArquivo) {

      /**
       * Caso encontre erros adiciona a variavel de erros e não retorna o registro
       */
      if(!$aErrosRegistro = $oArquivo->validarDadosDependente($oDados)) {
        return $oDados;
      }

      foreach ($aErrosRegistro as $erro) {
        ArquivoSiprevBase::$aErrosProcessamento["02"][] = $erro;
      }

      return;
    });

    $aDados = array();

    foreach ( $aListaDados as $oIndiceDados => $oValorDados ) {

      $aLinhas                  = array();
      $aLinhas["dependencias"]  = $this->preencheDependecias($oValorDados);
      $aLinhas["dadosPessoais"] = $this->preencheDadosPessoais($oValorDados);
      $aDados[]                 = (object) $aLinhas;
    }

  	return $aDados;
  }

  /*
   * Esse método é responsável por definir quais os elementos e suas propriedades que serão
   * repassadas para o arquivo que será gerado.
   */
  public function getElementos() {

    $aDados   = array();
    $aDados[] = $this->atributosDependencias();
    $aDados[] = $this->atributosDadosPessoais();

    return $aDados;
  }

  /**
   * Atributos referentes ao registro dependencias
   * @return array
   */
  private function atributosDependencias() {

    $aDadosDependenciaServidor                 = array();
    $aDadosDependenciaServidor["nome"]         = "servidor";
    $aDadosDependenciaServidor["propriedades"] = array( "nome", "numeroCPF", "numeroNIT" );

    $aDadosDependencia                 = array();
    $aDadosDependencia["nome"]         = "dependencias";
    $aDadosDependencia["propriedades"] = array(
      "tipoDependencia",
      "finsPrevidenciarios",
      "dataInicioDependencia",
      $aDadosDependenciaServidor
    );

    return $aDadosDependencia;
  }

  /**
   * Atributos referentes ao registro dadosPessoais
   * @return array
   */
  private function atributosDadosPessoais() {

    $aDadosPessoais                 = array();
    $aDadosPessoais["nome"]         = "dadosPessoais";
    $aDadosPessoais["propriedades"] = array("nome", "dataNascimento", "nomeMae");

    return $aDadosPessoais;
  }

  /**
   * Preenche os atributos referentes ao registro dependencias
   * @param  stdClass $oValorDados
   * @return array
   */
  private function preencheDependecias($oValorDados) {

    $aDependencias = array();

    if(!empty($oValorDados->rh31_irf)) {

      $aTipoDependencia      = array();
      $aTipoDependencia['1'] = 1;
      $aTipoDependencia['2'] = 3;
      $aTipoDependencia['4'] = 8;
      $aTipoDependencia['5'] = 8;
      $aTipoDependencia['6'] = 5;
      $aTipoDependencia['7'] = 10;

      if(array_key_exists($oValorDados->rh31_irf, $aTipoDependencia)) {
        $aDependencias["tipoDependencia"] = $aTipoDependencia[$oValorDados->rh31_irf];
      }
    }

    if ( ($oValorDados->rh31_depend == 'C' && $oValorDados->idade <= 14 ) || $oValorDados->rh31_depend == 'S' ) {
      $aDependencias["finsPrevidenciarios"] = "0";
    } else if ( ($oValorDados->rh31_depend == 'C' && $oValorDados->idade > 14 ) || $oValorDados->rh31_depend == 'N' ) {
      $aDependencias["finsPrevidenciarios"] = "1";
    }

    if(!empty($oValorDados->inicio_depencia)) {
      $aDependencias["dataInicioDependencia"] = $oValorDados->inicio_depencia;
    }

    $aDependencias["servidor"] = $this->preencheDadosServidor($oValorDados);

    return (object) $aDependencias;
  }

  /**
   * Preenche os atributos do servidor
   * @param  stdClass $oValorDados
   * @return object
   */
  private function preencheDadosServidor($oValorDados) {

    $aDadosServidor["nome"] = DBString::removerCaracteresEspeciais($oValorDados->z01_nome);

    if(trim($oValorDados->z01_cgccpf) != '') {
      $aDadosServidor["numeroCPF"] = $oValorDados->z01_cgccpf;
    }

    if(trim($oValorDados->rh16_pis) != '' && $oValorDados->rh16_pis != '00000000000') {
      $aDadosServidor["numeroNIT"] = $oValorDados->rh16_pis;
    }

    return (object) $aDadosServidor;
  }

  /**
   * Preenche os atributos referentes ao registro dadosPessoais
   * @param  stdClass $oValorDados
   * @return object
   */
  private function preencheDadosPessoais($oValorDados) {

    $aDadosPessoais                   = array();
    $aDadosPessoais["nome"]           = DBString::removerCaracteresEspeciais($oValorDados->rh31_nome);
    $aDadosPessoais["dataNascimento"] = $oValorDados->rh31_dtnasc;
    $aDadosPessoais["nomeMae"]        = DBString::removerCaracteresEspeciais($oValorDados->rh31_nome);

    return (object) $aDadosPessoais;
  }

  /**
   * Realiza as validações dos campos
   * @param  stdClass $oDados
   * @return array
   */
  public function validarDadosDependente($oDados) {

    $aErrosRegistro = array();
    $lPisValido     = DBString::isPIS($oDados->rh16_pis);
    $lCpfValido     = DBString::isCPF($oDados->z01_cgccpf);

    if(!$lPisValido) {

      $erro = $this->getErro($oDados, "PIS '{$oDados->rh16_pis}' é inválido.");

      if (!in_array($erro, ArquivoSiprevBase::$aErrosProcessamento["02"])) {
        $aErrosRegistro[] = $erro;
      }
    }

    if(!$lCpfValido) {

      $erro = $this->getErro($oDados, "CPF '{$oDados->z01_cgccpf}' é inválido.");

      if (!in_array($erro, ArquivoSiprevBase::$aErrosProcessamento["02"])) {
        $aErrosRegistro[] = $erro;
      }
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
