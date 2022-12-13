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
class ArquivoSiprevBeneficiosServidores extends ArquivoSiprevBase {

  protected $sNomeArquivo                  = "10-BeneficiosServidores";
  public    $aTipoAposentadoriaServidor    = array();
  public    $aServidoresSalarioFamilia     = array();
  public    $aServidoresSalarioMaternidade = array();
  public    $aServidoresAuxilioDoenca      = array();
  public    $aRubricasAuxilioDoenca        = array();
  public    $aRubricasSalarioMaternidade   = array();

  public function __construct() {
    ArquivoSiprevBase::$aErrosProcessamento["10"] = array();
  }

  public function getDados() {

    $sCamposBeneficioServidor = "distinct rh02_regist, rh02_instit, rh01_numcgm, z01_nome, z01_cgccpf, z01_pis, rh02_codreg";

    /**
     * SQL servidores inativos
     */
    $sSqlInativos  = "select {$sCamposBeneficioServidor}                           \n";
    $sSqlInativos .= "  from rhpessoal                                             \n";
    $sSqlInativos .= "       inner join rhpessoalmov on rh02_regist = rh01_regist  \n";
    $sSqlInativos .= "       inner join rhregime     on rh30_codreg = rh02_codreg  \n";
    $sSqlInativos .= "       inner join cgm          on z01_numcgm  = rh01_numcgm  \n";
    $sSqlInativos .= " where rh02_anousu = {$this->iAnoInicial}                    \n";
    $sSqlInativos .= "   AND rh02_mesusu = {$this->iMesInicial}                    \n";
    $sSqlInativos .= "   AND rh30_vinculo = 'I'                                    \n";

    /**
     * SQL servidores ativos
     */
    $sSqlAuxilioDoenca  = "select {$sCamposBeneficioServidor}                                                                 \n";
    $sSqlAuxilioDoenca .= "  from rhpessoal                                                                                   \n";
    $sSqlAuxilioDoenca .= "       inner join rhpessoalmov on rh02_regist = rh01_regist                                        \n";
    $sSqlAuxilioDoenca .= "                              and rh02_anousu = {$this->iAnoInicial}                               \n";
    $sSqlAuxilioDoenca .= "                              and rh02_mesusu = {$this->iMesInicial}                               \n";
    $sSqlAuxilioDoenca .= "       inner join rhregime     on rh30_codreg = rh02_codreg                                        \n";
    $sSqlAuxilioDoenca .= "       inner join cgm          on z01_numcgm  = rh01_numcgm                                        \n";
    $sSqlAuxilioDoenca .= "       inner join afasta       on r45_regist  = rh01_regist                                        \n";
    $sSqlAuxilioDoenca .= "       inner join inssirf      on r33_codtab  = rh02_tbprev + 2                                    \n";
    $sSqlAuxilioDoenca .= "                              and r33_anousu = {$this->iAnoInicial}                                \n";
    $sSqlAuxilioDoenca .= "                              and r33_mesusu = {$this->iMesInicial}                                \n";
    $sSqlAuxilioDoenca .= "                              and ((r33_rubsau <> '' AND r45_situac in(6, 8)) OR r33_rubmat <> '') \n";
    $sSqlAuxilioDoenca .= "                              and r33_instit = rh02_instit                                         \n";
    $sSqlAuxilioDoenca .= " where rh30_vinculo = 'A'                                                                          \n";

    $sSqlServidores  = $sSqlInativos;
    $sSqlServidores .= " UNION ";
    $sSqlServidores .= $sSqlAuxilioDoenca;
    $rsDadosRetorno  = db_query($sSqlServidores);

    if(!$rsDadosRetorno) {
      throw new DBException('Erro ao buscar os benefícios do servidor.');
    }

    $aErros      = array();
    $oInstancia  = $this;
    $iAnoFinal   = $this->iAnoFinal; 
    $iMesFinal   = $this->iMesFinal;
    $aServidores = db_utils::makeCollectionFromRecord($rsDadosRetorno, function($oDadosRetorno) use(&$aErros, $oInstancia, $iAnoFinal, $iMesFinal) {

      /**
       * As instâncias de CGM e Servidor são feitas sem passar a chave, para evitar que a cada registro seja feita uma
       * nova query, sendo apenas setados os dados necessários e evitando estouro de memória
       */
      $oCgm = new CgmFisico();
      $oCgm->setCodigo($oDadosRetorno->rh01_numcgm);
      $oCgm->setNome($oDadosRetorno->z01_nome);
      $oCgm->setCpf($oDadosRetorno->z01_cgccpf);
      $oCgm->setPIS($oDadosRetorno->z01_pis);

      $oServidor = new Servidor(null, $iAnoFinal, $iMesFinal, $oDadosRetorno->rh02_instit);
      $oServidor->setMatricula($oDadosRetorno->rh02_regist);
      $oServidor->setCgm($oCgm);
      $oServidor->setCodigoRegime($oDadosRetorno->rh02_codreg);

      if(!$oServidor->isAtivo()) {
        $oInstancia->buscarTipoAposentadoria($oServidor);
      }

      /**
       * Valida se os dados do servidor estão corretos
       */
      if(!$aErrosRegistro = $oInstancia->validarDadosBeneficiosServidores($oServidor)) {
        return $oServidor;
      }

      foreach ($aErrosRegistro as $aErro) {
        ArquivoSiprevBase::$aErrosProcessamento["10"][] = $aErro;
      }
      return;
    });

    /**
     * Busca as rubricas cadastradas para auxílio doença e salário maternidade
     */
    $this->rubricasDoencaMaternidade();

    $aDadosBeneficiosServidores = array();

    foreach($aServidores as $oServidor) {

      $oBeneficioServidor = $this->preencheBeneficioServidor($oServidor);
      if($oBeneficioServidor != null) {

        $aLinhas                      = array('beneficioServidor' => $oBeneficioServidor);
        $aDadosBeneficiosServidores[] = (object)$aLinhas;
      }
    }

    return $aDadosBeneficiosServidores;
  }

  /**
   * Busca o tipo de aposentadoria do servidor quando inativo
   * @param Servidor $oServidor
   * @throws BusinessException
   * @throws DBException
   */
  public function buscarTipoAposentadoria(Servidor $oServidor) {

    $oDaoRhPessoalMov    = new cl_rhpessoalmov();
    $sWhereRhPessoalMov  = "     rh02_regist = {$oServidor->getMatricula()}";
    $sWhereRhPessoalMov .= " AND (rh02_anousu, rh02_mesusu) between ({$this->iAnoInicial},{$this->iMesInicial})";
    $sWhereRhPessoalMov .= "                                    and ({$this->iAnoFinal},{$this->iMesFinal})    ";
    $sSqlRhPessoalMov    = $oDaoRhPessoalMov->sql_query_file(
      null,
      $oServidor->getInstituicao()->getCodigo(),
      'rh02_rhtipoapos',
      null,
      $sWhereRhPessoalMov
    );

    $rsRhPessoalMov = db_query($sSqlRhPessoalMov);

    if(!$rsRhPessoalMov) {
      throw new DBException('Erro ao buscar o tipo de aposentadoria.');
    }

    if(pg_num_rows($rsRhPessoalMov) == 0) {
      throw new BusinessException("Tipo de aposentadoria do servidor {$oServidor->getCgm()->getNome()} não encontrado.");
    }

    $this->aTipoAposentadoriaServidor[$oServidor->getMatricula()] = db_utils::fieldsMemory($rsRhPessoalMov, 0)->rh02_rhtipoapos;
  }

  /**
   * Busca as rubricas de auxílio doença e salário maternidade, preenchendo o array conforme o tipo
   * @throws DBException
   */
  private function rubricasDoencaMaternidade() {

    $oDaoInssIrf    = new cl_inssirf();
    $sCamposInssIrf = 'r33_rubmat, r33_rubsau';
    $sWhereInssIrf  = "r33_anousu = {$this->iAnoInicial} AND r33_mesusu = {$this->iMesInicial}";
    $sSqlInssIrf    = $oDaoInssIrf->sql_query_file(null, null, $sCamposInssIrf, null, $sWhereInssIrf);
    $rsInssIrf      = db_query($sSqlInssIrf);

    if(!$rsInssIrf) {
      throw new DBException('Erro ao buscar as rubricas de auxílio doença e salário maternidade.');
    }

    $iLinhasInssIrf = pg_num_rows($rsInssIrf);

    for($iContador = 0; $iContador < $iLinhasInssIrf; $iContador++) {

      $oDadosRetorno = db_utils::fieldsMemory($rsInssIrf, $iContador);

      if(!empty($oDadosRetorno->r33_rubmat) && !in_array($oDadosRetorno->r33_rubmat, $this->aRubricasSalarioMaternidade)) {
        $this->aRubricasSalarioMaternidade[] = $oDadosRetorno->r33_rubmat;
      }

      if(!empty($oDadosRetorno->r33_rubsau) && !in_array($oDadosRetorno->r33_rubsau, $this->aRubricasAuxilioDoenca)) {
        $this->aRubricasAuxilioDoenca[] = $oDadosRetorno->r33_rubsau;
      }
    }
  }

  /**
   * Realiza o cálculo dos benefícios do servidor
   * @param Servidor $oServidor
   * @param int $iCalculo - Tipo de cálculo a ser feito
   * @return mixed
   */
  private function calculoBeneficios(Servidor $oServidor, $iCalculo) {

    $oCalculoFolhaSalario = $oServidor->getCalculoFinanceiro($iCalculo);
    $oInstancia           = &$this;

    $nValorBeneficio = array_reduce($oCalculoFolhaSalario->getEventosFinanceiros(), function($nValor, $oEventoFinanceiro) use($oServidor, $iCalculo, $oInstancia) {

      if($iCalculo == CalculoFolha::CALCULO_SALARIO) {

        /**
         * Valida se o servidor possui rubrica de salário família
         */
        if(in_array($oEventoFinanceiro->getRubrica()->getCodigo(), array('R918', 'R919', 'R920'))) {

          $oInstancia->aServidoresSalarioFamilia[] = $oServidor->getMatricula();
          return $oEventoFinanceiro->getValor();
        }

        /**
         * Valida se o servidor possui rubrica de auxílio doença
         */
        if(in_array($oEventoFinanceiro->getRubrica()->getCodigo(), $oInstancia->aRubricasAuxilioDoenca)) {

          $oInstancia->aServidoresAuxilioDoenca[] = $oServidor->getMatricula();
          return $oEventoFinanceiro->getValor();
        }

        /**
         * Valida se o servidor possui rubrica de salário maternidade
         */
        if(in_array($oEventoFinanceiro->getRubrica()->getCodigo(), $oInstancia->aRubricasSalarioMaternidade)) {

          $oInstancia->aServidoresSalarioMaternidade[] = $oServidor->getMatricula();
          return $oEventoFinanceiro->getValor();
        }
      }

      if($oEventoFinanceiro->getNatureza() != Rubrica::TIPO_PROVENTO) {
        return $nValor;
      }

      return $nValor + $oEventoFinanceiro->getValor();
    }, 0);

    return $nValorBeneficio;
  }

  /**
   * Retorna os elementos e propriedades do arquivo
   * @return array
   */
  public function getElementos() {
    return array($this->atributosBeneficioServidor());
  }

  /**
   * Atributos referentes ao registro beneficioServidor e servidor
   * @return array
   */
  private function atributosBeneficioServidor() {

    $aServidor                 = array();
    $aServidor["nome"]         = "servidor";
    $aServidor["propriedades"] = array( "nome", "numeroCPF", "numeroNIT" );

    $aBeneficioServidor                 = array();
    $aBeneficioServidor['nome']         = 'beneficioServidor';
    $aBeneficioServidor['propriedades'] = array(
      'operacao',
      'tipoBeneficio',
      'vlAtualBeneficio',
      'dtUltimaAtualizacao',
      $aServidor
    );

    return $aBeneficioServidor;
  }

  /**
   * Preenche os valores dos atributos do registro beneficioServidor
   * @param  Servidor $oServidor
   * @return object
   */
  private function preencheBeneficioServidor(Servidor $oServidor) {

    $iTipoBeneficio     = null;
    $nValorSalario      = $this->calculoBeneficios($oServidor, CalculoFolha::CALCULO_SALARIO);
    $nValorComplementar = $this->calculoBeneficios($oServidor, CalculoFolha::CALCULO_COMPLEMENTAR);
    $nValorDecimo       = $this->calculoBeneficios($oServidor, CalculoFolha::CALCULO_13o);

    if(!$oServidor->isAtivo()) {
      $iTipoBeneficio = $this->aTipoAposentadoriaServidor[$oServidor->getMatricula()];
    }

    if(in_array($oServidor->getMatricula(), $this->aServidoresAuxilioDoenca)) {
      $iTipoBeneficio = 13;
    }

    if(in_array($oServidor->getMatricula(), $this->aServidoresSalarioFamilia)) {
      $iTipoBeneficio = 14;
    }

    if(in_array($oServidor->getMatricula(), $this->aServidoresSalarioMaternidade)) {
      $iTipoBeneficio = 15;
    }

    $nValorTotal                               = $nValorSalario + $nValorComplementar + $nValorDecimo;
    $aBeneficioServidor                        = array();
    $aBeneficioServidor['operacao']            = "I";
    $aBeneficioServidor['tipoBeneficio']       = $iTipoBeneficio;
    $aBeneficioServidor['vlAtualBeneficio']    = number_format($nValorTotal, 2, '.', '');
    $aBeneficioServidor['dtUltimaAtualizacao'] = "{$this->iAnoFinal}-{$this->iMesFinal}-01";
    $aBeneficioServidor["servidor"]            = $this->preencheDadosServidor($oServidor);

    return (object) $aBeneficioServidor;
  }

  /**
   * Preenche os valores dos atributos do registro servidor
   * @param  Servidor $oServidor
   * @return object
   */
  private function preencheDadosServidor(Servidor $oServidor) {

    $oCgm              = $oServidor->getCgm();
    $aServidor["nome"] = DBString::removerCaracteresEspeciais($oCgm->getNome());

    if($oCgm->getCpf() != '') {
      $aServidor["numeroCPF"] = $oCgm->getCpf();
    }

    if($oCgm->getPIS() != '') {
      $aServidor["numeroNIT"] = $oCgm->getPIS();
    }

    return (object) $aServidor;
  }

  /**
   * Realiza as validações dos campos
   * @param  Servidor $oServidor
   * @return array
   */
  public function validarDadosBeneficiosServidores(Servidor $oServidor) {

    $oCgm           = $oServidor->getCgm();
    $aErrosRegistro = array();

    $lPisValido = DBString::isPIS($oCgm->getPIS());
    $lCpfValido = DBString::isCPF($oCgm->getCpf());

    if(!$lPisValido) {
      $aErrosRegistro[] = $this->getErro($oServidor, "PIS '{$oCgm->getPIS()}' é inválido.");
    }

    if(!$lCpfValido) {
      $aErrosRegistro[] = $this->getErro($oServidor, "CPF '{$oCgm->getCpf()}' é inválido.");
    }

    if(!$oServidor->isAtivo() && empty($this->aTipoAposentadoriaServidor[$oServidor->getMatricula()])) {
      $aErrosRegistro[] = $this->getErro($oServidor, "Tipo de aposentadoria não informado.");
    }

    return $aErrosRegistro;
  }

  /**
   * Monta o array dos erros com os dados para apresentação no relatório
   * @param Servidor $oServidor
   * @param $sErro
   * @return array
   */
  private function getErro(Servidor $oServidor, $sErro) {

    return array(
      $oServidor->getInstituicao()->getDescricao(),
      $oServidor->getCgm()->getCodigo() . " - " . $oServidor->getCgm()->getNome(),
      $sErro,
    );
  }
}
