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
class ArquivoSiprevBeneficiosPensionistas extends ArquivoSiprevBase {

  protected $sNomeArquivo = "11-BeneficiosPensionistas";

  public function __construct() {
    ArquivoSiprevBase::$aErrosProcessamento["11"] = array();
  }

  public function getDados() {

    $sCamposPensionistas = "distinct rh02_regist, rh02_instit, rh01_numcgm, z01_nome, z01_cgccpf, z01_pis";

    $sSqlPensionistas  = "select {$sCamposPensionistas}                                \n";
    $sSqlPensionistas .= "  from rhpessoal                                             \n";
    $sSqlPensionistas .= "       inner join rhpessoalmov on rh02_regist = rh01_regist  \n";
    $sSqlPensionistas .= "       inner join rhregime     on rh30_codreg = rh02_codreg  \n";
    $sSqlPensionistas .= "       inner join cgm          on z01_numcgm  = rh01_numcgm  \n";
    $sSqlPensionistas .= " where rh02_anousu  = {$this->iAnoInicial}                   \n";
    $sSqlPensionistas .= "   AND rh02_mesusu  = {$this->iMesInicial}                   \n";
    $sSqlPensionistas .= "   AND rh30_vinculo = 'P'                                    \n";
    $rsDadosRetorno    = db_query($sSqlPensionistas);

    if(!$rsDadosRetorno) {
      throw new DBException('Erro ao buscar os benefícios do pensionista.');
    }

    $self          = $this;
    $aErros        = array();
    $aPensionistas = db_utils::makeCollectionFromRecord($rsDadosRetorno, function($oDadosRetorno) use(&$aErros, $self) {

      /**
       * As instâncias de CGM e Servidor são feitas sem passar a chave, para evitar que a cada registro seja feita uma
       * nova query, sendo apenas setados os dados necessários e evitando estouro de memória
       */
      $oCgm = new CgmFisico();
      $oCgm->setCodigo($oDadosRetorno->rh01_numcgm);
      $oCgm->setNome($oDadosRetorno->z01_nome);
      $oCgm->setCpf($oDadosRetorno->z01_cgccpf);
      $oCgm->setPIS($oDadosRetorno->z01_pis);

      $oServidor = new Servidor(null, $self->getAnoFinal(), $self->getMesFinal(), $oDadosRetorno->rh02_instit);
      $oServidor->setMatricula($oDadosRetorno->rh02_regist);
      $oServidor->setCgm($oCgm);

      /**
       * Valida se os dados do pensionista estão corretos
       */
      if(!$aErrosRegistro = $self->validarDadosBeneficiosPensionista($oServidor)) {
        return $oServidor;
      }

      foreach ($aErrosRegistro as $aErro) {
        ArquivoSiprevBase::$aErrosProcessamento["11"][] = $aErro;
      }
      return;
    });

    $aDadosBeneficiosPensionistas = array();

    foreach($aPensionistas as $oServidor) {

      $oBeneficioPensionista = $self->preencheBeneficioPensionista($oServidor);
      if($oBeneficioPensionista != null) {

        $aLinhas                         = array('beneficioPensionista' => $oBeneficioPensionista);
        $aDadosBeneficiosPensionistas[] = (object)$aLinhas;
      }
    }

    return $aDadosBeneficiosPensionistas;
  }

  /**
   * Realiza o cálculo dos benefícios do pensionista
   * @param Servidor $oServidor
   * @return mixed
   */
  private function calculoBeneficios(Servidor $oServidor) {

    $oCalculoFolhaSalario = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO);
    $nValorBeneficio      = array_reduce($oCalculoFolhaSalario->getEventosFinanceiros(), function($nValor, $oEventoFinanceiro) {
      return $nValor + $oEventoFinanceiro->getValor();
    }, 0);

    return $nValorBeneficio;
  }

  /**
   * Retorna os elementos e propriedades do arquivo
   * @return array
   */
  public function getElementos() {
    return array($this->atributosBeneficioPensionista());
  }

  /**
   * Atributos referentes ao registro beneficioPensionista/quota/pensionista
   * @return array
   */
  private function atributosBeneficioPensionista() {

    $aPensionista                 = array();
    $aPensionista['nome']         = "pensionista";
    $aPensionista["propriedades"] = array( "nome", "numeroCPF", "numeroNIT" );

    $aQuota                 = array();
    $aQuota['nome']         = 'quota';
    $aQuota['propriedades'] = array('matricula', $aPensionista);

    $aBeneficioPensionista                 = array();
    $aBeneficioPensionista['nome']         = 'beneficioPensionista';
    $aBeneficioPensionista['propriedades'] = array(
      'operacao',
      'tipoBeneficio',
      'vlAtualBeneficio',
      'dataUltimaAtualizacao',
      $aQuota
    );

    return $aBeneficioPensionista;
  }

  /**
   * Preenche os valores dos atributos do registro beneficioPensionista
   * @param Servidor $oServidor
   * @return object
   */
  public function preencheBeneficioPensionista(Servidor $oServidor) {

    $nValorTotal                                    = $this->calculoBeneficios($oServidor);
    $aBeneficioPensionista                          = array();
    $aBeneficioPensionista['operacao']              = "I";
    $aBeneficioPensionista['tipoBeneficio']         = 12;
    $aBeneficioPensionista['vlAtualBeneficio']      = number_format($nValorTotal, 2, '.', '');
    $aBeneficioPensionista['dataUltimaAtualizacao'] = "{$this->iAnoFinal}-{$this->iMesFinal}-01";
    $aBeneficioPensionista["quota"]                 = $this->preencheDadosQuota($oServidor);

    return (object) $aBeneficioPensionista;
  }

  /**
   * Preenche os valores dos atributos do registro quota
   * @param Servidor $oServidor
   * @return object
   */
  private function preencheDadosQuota(Servidor $oServidor) {

    $aQuota                = array();
    $aQuota['matricula']   = $oServidor->getMatricula();
    $aQuota['pensionista'] = $this->preencheDadosPensionista($oServidor);

    return (object) $aQuota;
  }

  /**
   * Preenche os valores dos atributos do registro pensionista
   * @param  Servidor $oServidor
   * @return object
   */
  private function preencheDadosPensionista(Servidor $oServidor) {

    $oCgm              = $oServidor->getCgm();
    $aServidor         = array();
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
  public function validarDadosBeneficiosPensionista(Servidor $oServidor) {

    $oCgm           = $oServidor->getCgm();
    $aErrosRegistro = array();

    $lPisValido = DBString::isPIS($oCgm->getPIS());
    $lCpfValido = DBString::isCPF($oCgm->getCpf());

    if(is_bool($oServidor->getServidorOrigem())) {
      $aErrosRegistro[] = $this->getErro($oServidor, "Pensionista sem Matrícula de Origem informada.");
    }

    if(!$lPisValido) {
      $aErrosRegistro[] = $this->getErro($oServidor, "PIS '{$oCgm->getPIS()}' é inválido.");
    }

    if(!$lCpfValido) {
      $aErrosRegistro[] = $this->getErro($oServidor, "CPF '{$oCgm->getCpf()}' é inválido.");
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
        "instituicao" => $oServidor->getInstituicao()->getDescricao(),
        "cgm"         => $oServidor->getCgm()->getCodigo() . " - " . $oServidor->getCgm()->getNome(),
        "erro"        => $sErro,
    );
  }
}
