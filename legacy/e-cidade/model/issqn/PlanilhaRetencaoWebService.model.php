<?php
/*
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

require_once(modification("model/planilhaRetencao.model.php"));

/**
 * Classe responsavel por realizar os Lançamentos do prestador,
 * recebidos pelo WebService
 * @author Renan Melo <renan@dbseller.com.br>
 * @package ISSQN
 */
class PlanilhaRetencaoWebService extends planilhaRetencao {

  /**
   * Tipo de Imposto.
   * @var string
   */
  private $sTipoImposto;

  /**
   * CPF do Tomador.
   * @var string
   */
  private $sCpf;

  /**
   * CNPJ do Tomador
   * @var string
   */
  private $sCnpj;

  /**
   * Número de inscrição do Tomador
   * @var integer
   */
  private $iInscricaoTomador;

  /**
   * N]umero do Cgm do Tomador
   * @var integer
   */
  private $iCgmTomador;

  /**
   * Ano da competencia
   * @var integer
   */
  private $iAnoCompetencia;

  /**
   * Motivo anualacao da planilha
   */
  private $sMotivoAnulacao;

  /**
   * Sobrecarregado para não executar validação de parametros.
   */
  public function __construct( $iCodigoPlanilha = null){

    if ( !empty($iCodigoPlanilha) ) {
      parent::__construct($iCodigoPlanilha);
    }
  }

  /**
   * Retornar mes competencia
   *
   * @access public
   * @return integer
   */
  public function getMesCompetencia() {
    return $this->iMesCompetencia;
  }

  /**
   * Persisite os Dados da Nota, e retorna o código gerado.
   * @throws BusinessException
   */
  public function salvar() {

    try {

      //Se houver inscricao nao precisa cgm e verifica o numero do CGM de acordo com o Cpf ou o Cnpj informados
      db_app::import('CgmFactory');
      db_app::import('issqn.Empresa');

      $oCGM = null;

      if ( empty($this->iInscricaoTomador) ) {

        if (!empty($this->sCnpj)) {
          $oCGM = CgmFactory::getInstanceByCnpjCpf($this->sCnpj);
        } else if (!empty($this->sCpf)) {
          $oCGM = CgmFactory::getInstanceByCnpjCpf($this->sCpf);
        }

        if (!$oCGM && empty($this->iCgmTomador)) {
          throw new BusinessException("CPF ou CNPJ nao cadastrados");
        }
      } else {

        $oEmpresa = new Empresa($this->iInscricaoTomador);
        $oCGM     = $oEmpresa->getCgmEmpresa();
      }

      $iNumCgm = ($oCGM) ? $oCGM->getCodigo() : null;

      // Verificação caso o prestador seja eventual e não exista uma inscrição municipal no NFS-e
      if (empty($iNumCgm)) {
        $iNumCgm = $this->iCgmTomador;
      }

      parent::__construct($this->getCodigoPlanilha(),
                          $iNumCgm,
                          $this->iAnoCompetencia,
                          $this->iMesCompetencia,
                          $this->iInscricaoTomador);

    } catch ( Exception $eErro ) {
      throw new Exception($eErro->getMessage());
    }

    return $this->getCodigoPlanilha();
  }

  public function anularPlanilha() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Sem transação ativa");
    }

    try {

      parent::anularPlanilha($this->getMotivoAnulacao());
    } catch ( Exception $eErro ) {
      throw new Exception($eErro->getMessage());
    }

    return true;
  }


  /**
   * Seta o Tipo de Imposto
   * @param string $sTipoImposto
   */
  public function setTipoImposto($sTipoImposto) {
    $this->sTipoImposto = $sTipoImposto;
  }

  /**
   * Seta o nÃºmero do cpf
   * @param string $sCpf
   */
  public function setCpf($sCpf) {
    $this->sCpf = $sCpf;
  }

  /**
   * Seta o nÃºmero de Cnpj
   * @param string $sCnpj
   */
  public function setCnpj($sCnpj) {
    $this->sCnpj = $sCnpj;
  }

  /**
   * Seta o numero de inscricao do Tomador
   * @param integer $sInscricaoTomador
   */
  public function setInscricaoTomador($iInscricaoTomador) {
    $this->iInscricaoTomador = $iInscricaoTomador;
  }

  /**
   * Seta o número de Cgm do Tomador
   * @param integer $iCgmTomador
   */
  public function setCgmTomador($iCgmTomador) {
    $this->iCgmTomador = $iCgmTomador;
  }

  /**
   * Define a Compertencia do Imposto Retido
   *
   * @param integer $iMesCompetencia
   * @param integer $iAnoCompetencia
   */
  public function setCompetencia($iMesCompetencia, $iAnoCompetencia) {

    $this->iMesCompetencia = $iMesCompetencia;
    $this->iAnoCompetencia = $iAnoCompetencia;
  }

  /**
   * define o motivo para anulação da planilha
   * @param string $sMotivoAnulacao
   * @return void
   */
  public function setMotivoAnulacao($sMotivoAnulacao) {

    $this->sMotivoAnulacao = $sMotivoAnulacao;
  }

  /**
   * retorna o motivo da anulacao
   * @return string
   */
  public function getMotivoAnulacao () {
    return $this->sMotivoAnulacao;
  }

  /**
   * define o codigo da planilha
   * @return integer
   */
  public function setCodigoPlanilha ($iCodigoPlanilha) {
    $this->iCodigoPlanilha = $iCodigoPlanilha;
  }

  /**
   * Verifica se Cpf, Cnpj ou numero de inscrição estão preenchidos
   * @throws BusinessException
   */
  public function validaDados() {

    if( empty($this->sCpf) && empty($this->sCnpj) && empty($this->iInscricaoTomador) ) {
      throw new BusinessException('Cpf, Cnpj ou numero de inscricao do tomador devem ser preenchidos');
    }
  }

}
