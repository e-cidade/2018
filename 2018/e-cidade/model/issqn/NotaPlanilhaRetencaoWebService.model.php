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

require_once(modification("model/issqn/NotaPlanilhaRetencao.model.php"));
require_once(modification("std/DBDate.php"));

/**
 * Classe responsavel por realizar os Lançamentos do prestador,
 * recebidos pelo WebService
 * @package webservices
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @author Renan Melo <renan@dbseller.com.br>
 */
class NotaPlanilhaRetencaoWebService extends NotaPlanilhaRetencao{

  /**
   * CPF do prestador
   * @var string
   */
  private $sCpfPrestador;

  /**
   * CNPJ do prestador
   * @var string
   */
  private $sCnpjPrestador;

  /**
   * Número de inscrição do Prestador
   * @var integer
   */
  private $iInscricaoPrestador;

  /**
   * Número da Nota Fiscal
   * @var integer
   */
  private $iNumeroNf;

  /**
   * Data da nota fiscal
   * @var date
   */
  private $oDataNf;

  /**
   * Descrição do serviço prestado
   * @var string
   */
  private $sServicoPrestado;

  /**
   * Valor do Serviço prestado
   * @var float
   */
  private $nValorServicoPrestado;

  /**
   * Valor da dedução
   * @var float
   */
  private $nValorDeducao;

  /**
   * Valor para base de cálculo
   * @var float
   */
  private $nValorBaseCalculo;

  /**
   * Valor da Aliquota
   * @var integer
   */
  private $fAliquota;

  /**
   * Valor do Imposto Recebido
   * @var float
   */
  private $nValorImpostoRetido;

  /**
   * Mes Competencia
   * @var string
   */
  private $uMesCompetencia;

  /**
   * ano Competencia
   * @var string
   */
  private $uAnoCompetencia;

  /**
   * Data do pagamento
   * @var date
   */
  private $oDataPagamento;

  /**
   * Codigo da Nota (sequencial Planit)
   * @var integer
   */
  private $iCodigoNotaPlanilha;

  /**
   * Codigo da Planilha
   * @var integer
   */
  private $iCodPlanilha;

  /**
   * Nome do Prestador
   * @var string
   */
  private $sNome;

  /**
   * Contrutor responsavel por iniciar a transação
   */
  public function __construct($iCodigoNotaPlanilha = null) {
    parent::__construct($iCodigoNotaPlanilha);
  }

  public function getNotaPlanilha ($iCodigoNotaPlanilha) {

    $this->iCodigoNotaPlanilha = $iCodigoNotaPlanilha;
    parent::__construct($iCodigoNotaPlanilha);
  }

  /**
   * Processa os dados da nota e realiza os seus lancamentos na Planilha
   * @throws BusinessException
   */
  public function lancaValorPlanilha() {

    try {

      if (!db_utils::inTransaction()) {
        throw new Exception("Sem transação ativa");
      }

      db_app::import('CgmFactory');
      db_app::import('issqn.Empresa');

      /**
       * Verifica se foi fornecido Inscrição Municipal, CNPJ, CPF ou Inscrição Municipal
       * para retornar o numero do CGM.
       */
      if ($this->isRetido() == true) {

          if ($this->iInscricaoPrestador) {

            $oEmpresa = new Empresa($this->iInscricaoPrestador);
            $oCGM     = $oEmpresa->getCgmEmpresa();

            if ( $oCGM instanceof CgmFisico ) {
              $sCpfCnpj = $oCGM->getCpf();
            } else {
              $sCpfCnpj = $oCGM->getCnpj();
            }

          } else if ($this->sCnpjPrestador) {

            $sCpfCnpj = $this->sCnpjPrestador;

          } else if ($this->sCpfPrestador) {

            $oCGM = CgmFactory::getInstanceByCnpjCpf($this->sCpfPrestador);

            if (!is_object($oCGM)) {
              throw new Exception("Documento não encontrado no cadastro geral do municipio.(CPF: $this->sCpfPrestador)");
            }

            $sCpfCnpj = $oCGM->getCpf();
          }


      } else {

        if ($this->getTipoLancamento() == parent::SERVICO_PRESTADO) {
          $sCpfCnpj = (strlen($this->sCnpjPrestador) >= 11) ?  $this->sCnpjPrestador : 0;
        } else {

          if (strlen($this->sCnpjPrestador) < 14) {
            throw new Exception('CPF ou CNPJ nao encontrado');
          }
        }
      }

      /**
       * Seta os dados necessários para salvar a Nota
       */
      $this->setCodigoPlanilha  ( $this->iCodPlanilha );
      $this->setNome            ( utf8_decode($this->sNome) );
      $this->setDataOperacao    ( $this->oDataNf );
      $this->setTipoLancamento  ( $this->getTipoLancamento() );
      $this->setRetido          ( $this->isRetido() );
      $this->setStatus          ( $this->getStatus() );
      $this->setSituacao        ( $this->getSituacao() );
      $this->setDataNota        ( $this->oDataPagamento );
      $this->setCNPJ            ( $sCpfCnpj );
      $this->setNumeroNota      ( $this->iNumeroNf );
      $this->setValorServico    ( $this->nValorServicoPrestado );
      $this->setValorRetencao   ( $this->nValorImpostoRetido );
      $this->setAliquota        ( $this->fAliquota );
      $this->setValorDeducao    ( $this->nValorDeducao );
      $this->setValorBase       ( $this->nValorBaseCalculo );
      $this->setValorImposto    ( $this->nValorImpostoRetido );
      $this->setDescricaoServico( utf8_decode($this->sServicoPrestado) );

      /**
       * Salva os dados da nota
       */
      $this->salvar($this->iCodigoNotaPlanilha);

      return $this->getCodigoNotaPlanilha();
    } catch ( Exception $oErro ) {

      throw new Exception($oErro->getMessage());
    }
  }

  /**
   * Anula os valores lançados na planilha de retenção
   * @throws BusinessException
   */
  public function anularValorPlanilha() {

    try {

      if (!db_utils::inTransaction()) {
        throw new Exception("Sem transação ativa");
      }

      if ($this->iCodigoNotaPlanilha == NULL) {
        throw new Exception("Codigo da Nota não informado!");
      }

      $this->setSituacao(1);
      $this->setStatus (NotaPlanilhaRetencao::STATUS_INATIVO_EXCLUSAO);
      $this->salvar($this->iCodigoNotaPlanilha);

    } catch (Exception $oErro) {
      throw new Exception($oErro->getMessage());
    }
  }

  /**
   * seta o Cpf do Prestador
   * @param string $sCpfPrestador
   */
  public function setCpfPrestador($sCpfPrestador) {
    $this->sCpfPrestador = $sCpfPrestador;
  }

  /**
   * seta o Cnpj do Prestador
   * @param string $sCnpjPrestador
   */
  public function setCnpjPrestador($sCnpjPrestador) {
    $this->sCnpjPrestador= $sCnpjPrestador;
  }

  /**
   * seta a Inscrição do Prestador
   * @param integer $iInscricaoPrestador
   */
  public function setInscricaoPrestador($iInscricaoPrestador) {
    $this->iInscricaoPrestador = $iInscricaoPrestador;
  }

  /**
   * seta o número da Nota Fiscal
   * @param integer $iNumeroNf
   */
  public function setNumeroNf($iNumeroNf) {
    $this->iNumeroNf = $iNumeroNf;
  }

  /**
   * Seta a data da Nota Fiscal
   * @param date $dDataNf
   */
  public function setDataNf($dDataNf) {
    $this->oDataNf = new DBDate($dDataNf);
  }

  /**
   * seta o serviço prestado
   * @param string $sServicoPrestado
   */
  public function setServicoPrestado($sServicoPrestado) {
      $this->sServicoPrestado = $sServicoPrestado;
  }

  /**
   * Seta o Valor do serviço prestado
   * @param float $nValorServicoPrestado
   */
  public function setValorServicoPrestado($nValorServicoPrestado) {
    $this->nValorServicoPrestado = $nValorServicoPrestado;
  }

  /**
   * Seta o Valor da Dedução
   * @param float $nValorDeducao
   */
  public function setValorDeducaoNota($nValorDeducao) {
    $this->nValorDeducao = $nValorDeducao;
  }

  /**
   * seta o valor para a base de calculo
   * @param float $nValorBaseCalculo
   */
  public function setValorBaseCalculoNota($nValorBaseCalculo) {
    $this->nValorBaseCalculo = $nValorBaseCalculo;
  }

  /**
   * seta o valor da Aliquota
   * @param float $fAliquota
   */
  public function setAliquotaNota($fAliquota) {
    $this->fAliquota = $fAliquota;
  }

  /**
   * seta o  valor do imposto retido
   * @param float $nValorImpostoRetido
   */
  public function setValorImpostoRetido($nValorImpostoRetido) {
    $this->nValorImpostoRetido = $nValorImpostoRetido;
  }

  /**
   * Seta a competencia a partir do mes e do ano da competencia
   * @param integer $iMesCompetencia
   * @param integer $iAnoCompetencia
   */
  public function setCompetencia($iMesCompetencia, $iAnoCompetencia) {

    $this->iMesCompetencia = $iMesCompetencia;
    $this->iAnoCompetencia = $iAnoCompetencia;
    return true;
  }

  /**
   * Seta a data de pagamento
   * @param date $dDataPAgamento
   */
  public function setDataPagamento($dDataPagamento) {
    $this->oDataPagamento = new DBDate($dDataPagamento);
  }

  /**
   * Seta o Codigo da planilha
   * @param integer $iCodPlanilha
   */
  public function setCodigoNotaPlanilha($iCodigoNotaPlanilha) {
    $this->iCodigoNotaPlanilha = $iCodigoNotaPlanilha;
  }

  public function setNomePlanilha($nome) {
  	$this->sNome = $nome;
  }


  /**
   * Seta o Código da planilha
   * @param integer $iCodigoPlanilha
   */
  public function setCodPlanilha($iCodPlanilha) {
    $this->iCodPlanilha = $iCodPlanilha;
  }

  /**
   * Valida se pelo menos um dos dados(CPF, CNPJ, Inscrição) foi preenchido
   * @throws BusinessException
   */
  public function validarDados() {
    if ( (empty($this->sCpfPrestador) && empty($this->sCnpjPrestador) && empty($this->iInscricaoPrestador)) && $this->isRetido() == true) {
      throw new BusinessException('Cpf, Cnpj ou numero de inscricao do tomador devem ser preenchidos');
    }
  }
}
