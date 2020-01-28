<?php
/**
 *     E-cidade Software protectedo para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca protecteda Geral GNU, conforme
 *  protectedada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca protecteda Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca protecteda Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */


namespace ECidade\Tributario\Integracao\JuntaComercial\Repository;

use ECidade\Tributario\Integracao\JuntaComercial\Model\QSA\Contador;
use ECidade\Tributario\Integracao\JuntaComercial\Model\QSA\Socio;
use ECidade\Tributario\Integracao\JuntaComercial\Repository;

/**
 * Class Empresa
 * @package ECidade\Tributario\Integracao\JuntaComercial\Repository
 */
class Empresa
{
  /**
   * Persiste os dados na ISSBASE
   * @param \Empresa $oEmpresa
   * @return int
   * @throws \Exception
   */
  public static function persist(\Empresa $oEmpresa)
  {
    $oDaoEmpresa = new \cl_issbase();
    $oDaoEmpresa->q02_inscr   = $oEmpresa->getInscricao();
    $oDaoEmpresa->q02_numcgm  = $oEmpresa->getCgmEmpresa()->getCodigo();
    $oDaoEmpresa->q02_dtcada  = $oEmpresa->getDataCadastramento()->format("Y-m-d");
    $oDaoEmpresa->q02_dtjunta = $oEmpresa->getDataJunta()->format("Y-m-d");

    if ($oEmpresa->getCgmEmpresa() instanceof \CgmJuridico) {
      $oDaoEmpresa->q02_regjuc  = $oEmpresa->getCgmEmpresa()->getNire();
    }

    $oDaoEmpresa->q02_dtinic  = $oEmpresa->getDataCadastramento()->format("Y-m-d");
    $oDaoEmpresa->q02_cep     = $oEmpresa->getCep();
    $oDaoEmpresa->q02_tiplic  = "0";
    $oDaoEmpresa->q02_capit   = $oEmpresa->getValorCapital();
    $oDaoEmpresa->q02_ultalt  = date("Y-m-d");
    $oDaoEmpresa->q02_dtalt   = date("Y-m-d");
    $oDaoEmpresa->q02_obs     = $oEmpresa->getObservacao();

    if (!empty($oDaoEmpresa->q02_inscr)) {

      $sqlVerificaExistenciaEmpresa = $oDaoEmpresa->sql_query($oDaoEmpresa->q02_inscr);
      $oDaoEmpresa->sql_record($sqlVerificaExistenciaEmpresa);

      if ($oDaoEmpresa->numrows == 0) {
        throw new \Exception("Empresa com inscrição {$oDaoEmpresa->q02_inscr} não cadastrada.");
      }

      $oDaoEmpresa->alterar($oDaoEmpresa->q02_inscr);
    }

    if (empty($oDaoEmpresa->q02_inscr)) {
      $oDaoEmpresa->incluirNumeracaoContinua(null);
    }

    if ($oDaoEmpresa->erro_status == 0) {
      throw new \Exception($oDaoEmpresa->erro_msg);
    }

    $oEmpresa->setInscricao($oDaoEmpresa->q02_inscr);

    self::persistQuantidade($oEmpresa);
    self::persistPorte($oEmpresa);
    self::persistRua($oEmpresa);
    self::persistBairro($oEmpresa);
    self::persistMatricula($oEmpresa);
    self::persistAtividade($oEmpresa);
    self::persistQsa($oEmpresa);

    return $oDaoEmpresa->q02_inscr;
  }

  protected static function persistQuantidade(\Empresa $oEmpresa)
  {
    $oDaoQuantidade = new \cl_issquant();
    $oDaoQuantidade->excluir(null, $oEmpresa->getInscricao());

    $oDaoQuantidade->q30_anousu = date("Y");
    $oDaoQuantidade->q30_inscr = $oEmpresa->getInscricao();
    $oDaoQuantidade->q30_quant = 1;
    $oDaoQuantidade->q30_mult = 1;
    $oDaoQuantidade->q30_area  = "{$oEmpresa->getArea()}";
    $oDaoQuantidade->incluir(date("Y"), $oEmpresa->getInscricao());

    if ($oDaoQuantidade->erro_status == 0) {
      throw new \Exception($oDaoQuantidade->erro_msg);
    }
  }

  /**
   * Portes vindos do REGIN
   * Cod. porte
   * 1- ME
   * 2- EPP
   * 3- NO (Normal)
   * @param \Empresa $oEmpresa
   * @throws \Exception
   */
  protected static function persistPorte(\Empresa $oEmpresa)
  {
    $oDaoPorte = new \cl_issbaseporte();
    $oDaoPorte->excluir($oEmpresa->getInscricao());

    $iPorte = null;

    switch ($oEmpresa->getPorte()) {
      case 1:
        $iPorte = 2;
        break;
      case 2:
        $iPorte = 3;
        break;
      case 3:
        $iPorte = 1;
        break;
    }

    if ($iPorte == null) {
      throw new \Exception("Código de porte {$oEmpresa->getPorte()} não cadastrado.");
    }

    $oDaoPorte->q45_inscr    = $oEmpresa->getInscricao();
    $oDaoPorte->q45_codporte = $iPorte;
    $oDaoPorte->incluir($oEmpresa->getInscricao());

    if ($oDaoPorte->erro_status == 0) {
      throw new \Exception($oDaoPorte->erro_msg);
    }
  }

  protected static function persistRua(\Empresa $oEmpresa)
  {
    $oDaoRuas = new \cl_issruas();
    $oDaoRuas->excluir($oEmpresa->getInscricao());

    $oDaoRuas->q02_inscr  = $oEmpresa->getInscricao();
    $oDaoRuas->j14_codigo = $oEmpresa->getCodigoLogradouro();
    $oDaoRuas->q02_numero = (int) $oEmpresa->getNumero();
    $oDaoRuas->q02_compl  = $oEmpresa->getComplemento();
    $oDaoRuas->q02_cxpost = "";
    $oDaoRuas->z01_cep    = $oEmpresa->getCep();
    $oDaoRuas->incluir($oEmpresa->getInscricao());

    if ($oDaoRuas->erro_status==0) {
      throw new \Exception($oDaoRuas->erro_msg);
    }
  }

  protected static function persistBairro(\Empresa $oEmpresa)
  {
    $oDaoBairro = new \cl_issbairro();
    $oDaoBairro->excluir($oEmpresa->getInscricao());

    $oDaoBairro->q13_inscr  = $oEmpresa->getInscricao();
    $oDaoBairro->q13_bairro = $oEmpresa->getBairro();
    $oDaoBairro->incluir($oEmpresa->getInscricao());

    if($oDaoBairro->erro_status==0){
      throw new \Exception($oDaoBairro->erro_msg);

    }
  }

  protected static function persistMatricula(\Empresa $oEmpresa)
  {
    $oDaoMatricula = new \cl_issmatric();

    $iMatricula = $oEmpresa->getMatricula();

    if ( !empty($iMatricula) ) {

      $oDaoMatricula->excluir($oEmpresa->getInscricao(), $iMatricula);

      $oDaoMatricula->q05_inscr  = $oEmpresa->getInscricao();
      $oDaoMatricula->q05_matric = $iMatricula;
      $oDaoMatricula->q05_idcons = 1;
      $oDaoMatricula->incluir($oEmpresa->getInscricao(), $iMatricula);

      if ($oDaoMatricula->erro_status==0) {
        throw new \Exception($oDaoMatricula->erro_msg);
      }
    }
  }
  protected  static function criarLog(\Empresa $oEmpresa, $iLogTipo = null)
  {
    $iCgm  = $oEmpresa->getCgmEmpresa()->getCodigo();
    $cllogincricao = new \loginscricao();
    $cllogincricao->identificaAlteracao($oEmpresa->getInscricao(),1,1);

    if (!empty($iCgm)) {
      $cllogincricao->identificaAlteracao($oEmpresa->getInscricao(),1,9,$iCgm);
    }

    if ( !is_null($iLogTipo) ) {
      $cllogincricao->identificaAlteracao($oEmpresa->getInscricao(),1,$iLogTipo,$iCgm);
    }

    $cllogincricao->gravarLog();
  }

  private static function persistAtividade(\Empresa $oEmpresa)
  {
    $oDaoAtividadePrincipal = new \cl_ativprinc();
    $oDaoAtividadePrincipal->excluir($oEmpresa->getInscricao());
    if ($oDaoAtividadePrincipal->erro_status == "0") {
      throw new \Exception("Não foi possível excluir os dados da atividade principal da inscrição {$oEmpresa->getInscricao()}");
    }

    $oDaoAtividade = new \cl_tabativ();
    $oDaoAtividade->excluir($oEmpresa->getInscricao());
    if ($oDaoAtividade->erro_status == "0") {
      throw new \Exception("Não foi possível excluir os dados da atividade da inscrição {$oEmpresa->getInscricao()}");
    }

    $iSequencial = 1;

    foreach ($oEmpresa->getAtividades() as $oAtividade) {

      $oDaoAtividade->q07_inscr = $oEmpresa->getInscricao();
      $oDaoAtividade->q07_ativ  = $oAtividade->getCodigo();

      $oDaoAtividade->q07_datain  = $oAtividade->getDataInicio()->format("Y-m-d");

      $oDataFimAtividade = $oAtividade->getDataFim();

      if (!empty($oDataFimAtividade)) {
        $oDaoAtividade->q07_datafi  = $oAtividade->getDataFim()->format("Y-m-d");
      }

      $oDataBaixaAtividade = $oAtividade->getDataBaixa();

      if (!empty($oDataBaixaAtividade)) {
        $oDaoAtividade->q07_databx = $oAtividade->getDataBaixa()->format("Y-m-d");
      }
      $oDaoAtividade->q07_tipbx   = $oAtividade->getTipoBaixa();

      if($oAtividade->isPermanente()) {
        $oDaoAtividade->q07_perman  = "true";
      } else {
        $oDaoAtividade->q07_perman  = "false";
      }

      $oDaoAtividade->q07_quant   = $oAtividade->getQuantidade();
      $oDaoAtividade->q07_horaini = $oAtividade->getHoraInicio();
      $oDaoAtividade->q07_horafim = $oAtividade->getHoraFim();

      $oDaoAtividade->incluir($oEmpresa->getInscricao(), $iSequencial);

      if ($oDaoAtividade->erro_status == 0) {
        throw new \Exception($oDaoAtividade->erro_msg);
      }

      $oAtividade->setSequencial($iSequencial);

      if ($oAtividade->isAtividadePrincipal()) {

        $oDaoAtividadePrincipal->q88_inscr = $oEmpresa->getInscricao();
        $oDaoAtividadePrincipal->q88_seq = $iSequencial;

        $oDaoAtividadePrincipal->incluir($oEmpresa->getInscricao());

        if ($oDaoAtividadePrincipal->erro_status == 0) {
          throw new \Exception($oDaoAtividadePrincipal->erro_msg);
        }
      }

      $iSequencial++;
    }
  }

  /**
   * @param \Empresa $empresa
   */
  private static function persistQsa(\Empresa $empresa)
  {
    $socios = array();
    $contadores = array();

    foreach ($empresa->getQsa() as $qsa){

      $oRepository = new Repository();
      $oRepository->persistCgm($qsa->getCgm());

      if (Contador::TIPO_RELACIONAMENTO == $qsa->getTipoRelacionamento()) {
        $contadores[] = $qsa;
      } else {
        $socios[] = $qsa;
      }
    }

    self::persistSocio($empresa, $socios);
    self::persistContador($empresa, $contadores);
  }

  /**
   * @param \Empresa $empresa
   * @param array $contadores
   * @throws \DBException
   */
  private static function persistContador(\Empresa $empresa, array $contadores)
  {
    $daoEscrito = new \cl_escrito();
    $daoEscrito->excluir(null, "q10_inscr = " . $empresa->getInscricao());

    self::validarProcessamento($daoEscrito, "Erro ao remover o escritório contábil da empresa.");

    if ( !empty($contadores) ) {

      $cgmEscritorio = null;
      $daoCadescrito = new \cl_cadescrito();

      foreach ( $contadores as $contador ) {

        $sqlEscritorio = $daoCadescrito->sql_query_file($contador->getCgm()->getCodigo());
        $rsEscritorio  = db_query($sqlEscritorio);

        if ( !$rsEscritorio ) {
          throw new \DBException("Erro ao consultar escritório contábil.");
        }

        if ( pg_num_rows($rsEscritorio) > 0 ) {

          $cgmEscritorio = $contador->getCgm()->getCodigo();
          break;
        }
      }

      if ( is_null($cgmEscritorio) ) {

        $cgmEscritorio = $contadores[0]->getCgm()->getCodigo();
        $daoCadescrito->incluir($cgmEscritorio);
        self::validarProcessamento($daoCadescrito, "Erro ao incluir escritório contábil.");
      }

      $daoCadescritoresp = new \cl_cadescritoresp();
      $daoCadescritoresp->excluir(null, "q84_cadescrito = " . $cgmEscritorio);

      self::validarProcessamento($daoCadescritoresp, "Erro ao excluir os responsáveis do escritório contábil.");

      foreach ($contadores as $contador) {

        $daoCadescritoresp->q84_cadescrito = $cgmEscritorio;
        $daoCadescritoresp->q84_numcgm     = $contador->getCgm()->getCodigo();
        $daoCadescritoresp->incluir(null);

        self::validarProcessamento($daoCadescritoresp, "Erro ao incluir responsável do escritório contábil.");
      }

      $daoEscrito->q10_inscr = $empresa->getInscricao();
      $daoEscrito->q10_numcgm = $cgmEscritorio;
      $daoEscrito->incluir(null);

      self::validarProcessamento($daoEscrito, "Erro ao incluir escritório contábil para a empresa.");
      self::criarLog($empresa, \loginscricao::INCLUSAO_ESCRITORIO_CONTABIL);
    }
  }

  /**
   * @param \Empresa $empresa
   * @param array $socios
   */
  private static function persistSocio(\Empresa $empresa, array $socios)
  {
    $daoSocio = new \cl_socios();
    $daoSocio->excluir($empresa->getCgmEmpresa()->getCodigo());

    self::validarProcessamento($daoSocio, "Erro ao excluir sócio.");
    self::criarLog($empresa, \loginscricao::INGRESSO_RETIRADA_SOCIO);

    foreach ($socios as $socio) {

      $daoSocio = new \cl_socios();
      $daoSocio->q95_cgmpri = $empresa->getCgmEmpresa()->getCodigo();
      $daoSocio->q95_numcgm = $socio->getCgm()->getCodigo();
      $daoSocio->q95_perc   = $socio->getValorCapital();
      $daoSocio->q95_tipo   = Socio::TIPO_SOCIO;
      $daoSocio->incluir($empresa->getCgmEmpresa()->getCodigo(), $socio->getCgm()->getCodigo());

      self::validarProcessamento($daoSocio, "Erro ao incluir sócio");
      self::criarLog($empresa, \loginscricao::INGRESSO_RETIRADA_SOCIO);
    }
  }

  /**
   * @param $dao
   * @throws \DBException
   */
  private static function validarProcessamento($dao, $mensagem)
  {
    if ($dao->erro_status == 0) {
      throw new \DBException($mensagem);
    }
  }
}
