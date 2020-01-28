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
class ArquivoSiprevPensionistas extends ArquivoSiprevBase {

  protected $sNomeArquivo         = "07-Pensionistas";
  protected $sRegistro            = "pensionistas";
  private   $aIRFNaoPermitidos    = array('0', '3', '8');
  private   $aMatriculasNaoEnviar = array();

  public function __construct() {
    ArquivoSiprevBase::$aErrosProcessamento["07"] = array();
  }

  public function getDados() {

    $sCamposPensionistas  = "rh02_regist, rh02_instit";
    $sSqlPensionistas     = "select {$sCamposPensionistas}                               \n";
    $sSqlPensionistas    .= "  from rhpessoal                                            \n";
    $sSqlPensionistas    .= "       inner join rhpessoalmov on rh02_regist = rh01_regist \n";
    $sSqlPensionistas    .= "       inner join rhregime     on rh02_codreg = rh30_codreg \n";
    $sSqlPensionistas    .= " where rh02_anousu  = {$this->iAnoInicial}                  \n";
    $sSqlPensionistas    .= "   AND rh02_mesusu  = {$this->iMesInicial}                  \n";
    $sSqlPensionistas    .= "   AND rh30_vinculo = 'P'                                   \n";
    $rsPensionistas       = db_query($sSqlPensionistas);

    if(!$rsPensionistas) {
      throw new DBException("Erro ao buscar os dados de pensionistas.");
    }

    $self                 = $this;
    $aErros               = array();
    $aRetornoPensionistas = db_utils::makeCollectionFromRecord($rsPensionistas, function($oDados) use(&$aErros, $self) {

      $oServidor = ServidorRepository::getInstanciaByCodigo(
        $oDados->rh02_regist,
        $self->getAnoInicial(),
        $self->getMesInicial(),
        $oDados->rh02_instit
      );

      if(!$aErrosRegistro = $self->validarDadosPensionista($oServidor)) {
        return $oServidor;
      }

      foreach ($aErrosRegistro as $erro) {
        ArquivoSiprevBase::$aErrosProcessamento["07"][] = $erro;
      }
      return;
    });

    $aDadosPensionistas = array();

    foreach($aRetornoPensionistas as $oDadosPensionista) {

      /**
       * Validação para servidores que estão como dependentes, porém com IRF não existentes no SIPREV
       */
      if(in_array($oDadosPensionista->getMatricula(), $this->aMatriculasNaoEnviar)) {
        continue;
      }

      $aRegistros                  = array();
      $aRegistros["dependencias"]  = $this->preencheDependencias($oDadosPensionista);
      $aRegistros["dadosPessoais"] = $this->preencheDadosPessoais($oDadosPensionista);
      $aDadosPensionistas[]        = (object) $aRegistros;
    }

    return $aDadosPensionistas;
  }

  /**
   * Retorna os elementos e propriedades do arquivo
   * @return array
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

    $aServidor                 = array();
    $aServidor['nome']         = 'servidor';
    $aServidor['propriedades'] = array('nome', 'numeroCPF', 'numeroNIT');

    $aDadosDependencia                 = array();
    $aDadosDependencia["nome"]         = "dependencias";
    $aDadosDependencia["propriedades"] = array("tipoDependencia", $aServidor);

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
   * Preenche os valores dos atributos do registro dependencias
   * @param Servidor $oServidor
   * @return object
   */
  private function preencheDependencias(Servidor $oServidor) {

    $aDependencias    = array();
    $iTipoDependencia = null;
    $oServidorOrigem  = $oServidor->getServidorOrigem();

    foreach($oServidorOrigem->getDependentes() as $oDependente) {

      if($oDependente->getNome() == $oServidor->getCgm()->getNome()) {
        $iTipoDependencia = $oDependente->getTipo();
      }
    }

    if(!empty($iTipoDependencia)) {

      $aTipoDependencia      = array();
      $aTipoDependencia['1'] = 1;
      $aTipoDependencia['2'] = 3;
      $aTipoDependencia['4'] = 8;
      $aTipoDependencia['5'] = 8;
      $aTipoDependencia['6'] = 5;
      $aTipoDependencia['7'] = 10;

      if(array_key_exists($iTipoDependencia, $aTipoDependencia)) {
        $aDependencias["tipoDependencia"] = $aTipoDependencia[$iTipoDependencia];
      }
    }

    $aDependencias['servidor'] = $this->preencheDadosServidor($oServidorOrigem);

    return (object) $aDependencias;
  }

  /**
   * Preenche os valores dos atributos referentes ao servidor o qual o pensionista é dependente
   * @param Servidor $oServidorOrigem
   * @return object
   */
  private function preencheDadosServidor(Servidor $oServidorOrigem) {

    $aServidor              = array();
    $aServidor['nome']      = DBString::removerCaracteresEspeciais($oServidorOrigem->getCgm()->getNome());

    if($oServidorOrigem->getCgm()->getCpf() != '') {
      $aServidor["numeroCPF"] = $oServidorOrigem->getCgm()->getCpf();
    }

    if($oServidorOrigem->getCgm()->getPIS() != '') {
      $aServidor["numeroNIT"] = $oServidorOrigem->getCgm()->getPIS();
    }

    return (object) $aServidor;
  }

  /**
   * Preenche os valores dos atributos do registro dadosPessoais
   * @param Servidor $oServidor
   * @return object
   */
  private function preencheDadosPessoais(Servidor $oServidor) {

    $oCgm                             = $oServidor->getCgm();
    $aDadosPessoais                   = array();
    $aDadosPessoais["nome"]           = DBString::removerCaracteresEspeciais($oCgm->getNome());
    $aDadosPessoais["dataNascimento"] = $oCgm->getDataNascimento();
    $aDadosPessoais["nomeMae"]        = DBString::removerCaracteresEspeciais($oCgm->getNomeMae());

    return (object) $aDadosPessoais;
  }

  /**
   * Realiza as validações dos campos
   * @param Servidor $oServidor
   * @return array
   */
  public function validarDadosPensionista(Servidor $oServidor) {

    $aErrosRegistro  = array();
    $oServidorOrigem = $oServidor->getServidorOrigem();

    if(is_bool($oServidorOrigem)) {
      $aErrosRegistro[] = $this->getErro($oServidor, "Pensionista sem Matrícula de Origem informada.");
    }

    if(!is_bool($oServidorOrigem)) {

      if(count($oServidorOrigem->getDependentes()) == 0) {
        $aErrosRegistro[] = $this->getErro($oServidor, "Pensionista não consta como Dependente do servidor da matrícula de origem.");
      }

      $lDependente = false;

      foreach($oServidorOrigem->getDependentes() as $oDependente) {

        if($oDependente->getNome() == $oServidor->getCgm()->getNome()) {

          $lDependente = true;

          $tipoDependente = $oDependente->getTipo();
          if (empty($tipoDependente)) {
            $aErrosRegistro[] = $this->getErro($oServidor, "Tipo de dependência(IRF) do Pensionista dependente do servidor de origem, não informado.");
          }

          if(in_array($oDependente->getTipo(), $this->aIRFNaoPermitidos)) {
            $this->aMatriculasNaoEnviar[] = $oServidor->getMatricula();
          }
        }
      }

      if(!$lDependente) {
        $aErrosRegistro[] = $this->getErro($oServidor, "Pensionista não consta como Dependente do servidor da matrícula de origem.");
      }
    }

    if($oServidor->getCgm()->getDataNascimento() == '') {
      $aErrosRegistro[] = $this->getErro($oServidor, "Data de nascimento do pensionista não informado.");
    }

    if($oServidor->getCgm()->getNomeMae() == '') {
      $aErrosRegistro[] = $this->getErro($oServidor, "Nome da mãe do pensionista não informado.");
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
      "cgm"         => $oServidor->getCgm()->getCodigo() ." - ". $oServidor->getCgm()->getNome(),
      "erro"        => $sErro,
    );
  }
}
