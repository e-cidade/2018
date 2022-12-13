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


namespace ECidade\Tributario\Integracao\JuntaComercial;

use ECidade\Tributario\Integracao\JuntaComercial\Model\Licenca;
use ECidade\Tributario\Integracao\JuntaComercial\Model\Protocolo;
use ECidade\Tributario\Integracao\JuntaComercial\Repository\Empresa as EmpresaRepository;
use ECidade\Tributario\Integracao\JuntaComercial\Repository\Protocolo as ProtocoloRepository;

class Repository
{
  public function persistCgm(\CgmBase $cgm)
  {
    $sUF = $cgm->getUf();

    if (empty($sUF)) {
      throw new \Exception("UF não informada para {$cgm->getNome()}");
    }
    $cgm->save();
    $iCodigoCgm = $cgm->getCodigo();

    if (!empty($iCodigoCgm)) {
      $oDaoCgmTipoEmpresa = new \cl_cgmtipoempresa();

      $sWhereCgmTipoEmpresa = "z03_numcgm = {$iCodigoCgm}";
      $sSqlVerificaTipoEmpresa = $oDaoCgmTipoEmpresa->sql_query(null, "*", null, $sWhereCgmTipoEmpresa);
      $lExisteTipoEmpresa = db_query($sSqlVerificaTipoEmpresa);

      if (pg_num_rows($lExisteTipoEmpresa) > 0) {
        $oDaoCgmTipoEmpresa->z03_sequencial  = \db_utils::fieldsMemory($lExisteTipoEmpresa, 0)->z03_sequencial;
      }

      $oDaoCgmTipoEmpresa->z03_numcgm      = $iCodigoCgm;
      $oDaoCgmTipoEmpresa->z03_tipoempresa = $cgm->getCodigoTipoEmpresa();

      if (!empty($oDaoCgmTipoEmpresa->z03_sequencial)) {
        $oDaoCgmTipoEmpresa->alterar($oDaoCgmTipoEmpresa->z03_sequencial);
      }

      if (empty($oDaoCgmTipoEmpresa->z03_sequencial)) {
        $oDaoCgmTipoEmpresa->incluir(null);
      }

      if ($oDaoCgmTipoEmpresa->erro_status == "0") {
        throw new \Exception("Não foi possível incluir o tipo de CGM.");
      }
    }

    return $iCodigoCgm;
  }

  public function persistCabecalho(Protocolo $protocolo)
  {
    ProtocoloRepository::persist($protocolo);
  }

  public function persistEmpresa(\Empresa $oEmpresa)
  {
    EmpresaRepository::persist($oEmpresa);
  }

  public function getMunicipio($codigoMunicipio)
  {
    $municipioDao = new \cl_municipiosiafi();
    $municipioSql = $municipioDao->sql_query_file(null, "q110_descricao", null, "q110_codigo = '$codigoMunicipio'");
    $resultado = $municipioDao->sql_record($municipioSql);

    if ( !$resultado ) {
      return null;
    }

    return \db_utils::fieldsMemory($resultado, 0)->q110_descricao;
  }

  public function persistAlvara(\Alvara $oAlvara)
  {
    $aLicencas = array();

    $oDaoIssalvara = new \cl_issalvara();
    $oDaoIssalvara->q123_isstipoalvara = $oAlvara->getTipoAlvara();
    $oDaoIssalvara->q123_inscr         = $oAlvara->getEmpresa()->getInscricao();
    $oDaoIssalvara->q123_dtinclusao    = $oAlvara->getDataInclusao()->getDate();
    $oDaoIssalvara->q123_situacao      = $oAlvara->getSituacao();
    $oDaoIssalvara->q123_usuario       = $oAlvara->getUsuario()->getCodigo();
    $oDaoIssalvara->q123_geradoautomatico = (string) $oAlvara->getGeradoAltomatico();
    $oDaoIssalvara->incluir(null);

    if($oDaoIssalvara->erro_status == '0'){
      throw new \DBException($oDaoIssalvara->erro_msg);
    }

    $oAlvara->setCodigo($oDaoIssalvara->q123_sequencial);

    $oDaoIssmovalvara = new \cl_issmovalvara();
    $oDaoIssmovalvara->q120_issalvara        = $oDaoIssalvara->q123_sequencial;
    $oDaoIssmovalvara->q120_isstipomovalvara = 1 ;// liberação
    $oDaoIssmovalvara->q120_dtmov            = $oAlvara->getDataInclusao()->getDate();
    $oDaoIssmovalvara->q120_validadealvara   = 0;
    $oDaoIssmovalvara->q120_usuario          = $oAlvara->getUsuario()->getCodigo();
    $oDaoIssmovalvara->q120_obs              = "GERACAO AUTOMATICA ATRAVÉS DA INTEGRAÇÃO COM O REGIN";
    $oDaoIssmovalvara->incluir(null);

    if($oDaoIssmovalvara->erro_status == '0'){
      throw new \DBException($oDaoIssmovalvara->erro_msg);
    }


    $oDAoSanitario = new \cl_sanitario();
    $oDAoSanitario->y80_codsani   = $oAlvara->getEmpresa()->getInscricao();
    $oDAoSanitario->y80_numbloco  = 0;
    $oDAoSanitario->y80_numcgm    = $oAlvara->getEmpresa()->getCgmEmpresa()->getCodigo();
    $oDAoSanitario->y80_data      = $oAlvara->getDataInclusao()->getDate();
    $oDAoSanitario->y80_obs       = "";
    $oDAoSanitario->y80_depto     = db_getsession('DB_coddepto');
    $oDAoSanitario->y80_area      = $oAlvara->getEmpresa()->getArea();
    $oDAoSanitario->y80_codrua = $oAlvara->getEmpresa()->getCodigoLogradouro();
    $oDAoSanitario->y80_codbairro = $oAlvara->getEmpresa()->getBairro();
    $oDAoSanitario->y80_numero = (string) $oAlvara->getEmpresa()->getNumero();
    $oDAoSanitario->y80_compl = substr($oAlvara->getEmpresa()->getComplemento(), 0 , 20);

    $sqlVerificaSanitario = $oDAoSanitario->sql_query_file(null, '*', null, "y80_codsani = {$oAlvara->getEmpresa()->getInscricao()}");
    $oDAoSanitario->sql_record($sqlVerificaSanitario );

    if ($oDAoSanitario->numrows > 0) {
      $oDAoSanitario->alterar($oDAoSanitario->y80_codsani);
    }

    if ($oDAoSanitario->numrows == 0) {
      $oDAoSanitario->incluir_sem_seq($oAlvara->getEmpresa()->getInscricao());
    }

    if ($oDAoSanitario->erro_status==0) {
      throw new \DBException("Não foi possível incluir ou alterar o alvará sanitário.");
    }

    $oDaoSanitarioinscr = new \cl_sanitarioinscr();
    $oDaoSanitarioinscr->y18_codsani = $oAlvara->getEmpresa()->getInscricao();
    $oDaoSanitarioinscr->y18_inscr = $oAlvara->getEmpresa()->getInscricao();

    $sqlVerificaSanitarioInscricao = $oDaoSanitarioinscr->sql_query_file($oDAoSanitario->y80_codsani, $oAlvara->getEmpresa()->getInscricao());
    $oDaoSanitarioinscr->sql_record($sqlVerificaSanitarioInscricao);

    if ($oDaoSanitarioinscr->numrows > 0) {
      $oDaoSanitarioinscr->alterar($oDaoSanitarioinscr->y18_codsani);
    }

    if ($oDaoSanitarioinscr->numrows == 0) {
      $oDaoSanitarioinscr->incluir($oAlvara->getEmpresa()->getInscricao(), $oAlvara->getEmpresa()->getInscricao()) ;
    }

    if($oDaoSanitarioinscr->erro_status==0){
      throw new \DBException($oDaoSanitarioinscr->erro_msg);
    }

    $oDaoSaniatividade = new \cl_saniatividade();

    foreach ($oAlvara->getEmpresa()->getAtividades() as $indice => $oAtividade) {

      $oDaoSaniatividade->y83_codsani   = $oAlvara->getEmpresa()->getInscricao();
      $oDaoSaniatividade->y83_seq       = $oAtividade->getSequencial();
      $oDaoSaniatividade->y83_ativ      = $oAtividade->getCodigo();
      $oDaoSaniatividade->y83_area      = 0;
      $oDaoSaniatividade->y83_perman  = 'true';
      $oDaoSaniatividade->y83_dtini     = $oAlvara->getDataInclusao()->getDate();
      $oDaoSaniatividade->y83_dtfim     = null;

      if ($oAtividade->isAtividadePrincipal()) {
        $oDaoSaniatividade->y83_ativprinc = 'true';
      } else {
        $oDaoSaniatividade->y83_ativprinc = 'false';
      }

      $sqlVerificaSanitarioAtividade = $oDaoSaniatividade->sql_query_file($oDAoSanitario->y80_codsani, $oDaoSaniatividade->y83_seq);
      $oDaoSaniatividade->sql_record($sqlVerificaSanitarioAtividade);

      if ($oDaoSaniatividade->numrows > 0) {
        $oDaoSaniatividade->alterar($oDAoSanitario->y80_codsani, $oDaoSaniatividade->y83_seq);
      }

      if ($oDaoSaniatividade->numrows == 0) {
        $oDaoSaniatividade->incluir($oDaoSaniatividade->y83_codsani, $oDaoSaniatividade->y83_seq);
      }

      if ($oDaoSaniatividade->erro_status==0) {
        throw  new \DBException("Não foi possível vincular o alvará sanitário com a atividade {$oAtividade->getCodigo()}");
      }
    }

    $aLicencas[] = new Licenca(0 , Licenca::LICENCA_ALVARA, Licenca::TIPO_LICENCA_DEFINITIVA, $oAlvara->getEmpresa()->getInscricao());
    $aLicencas[] = new Licenca(0 , Licenca::LICENCA_MOBILIARIO, Licenca::TIPO_LICENCA_DEFINITIVA, $oAlvara->getEmpresa()->getInscricao());

    return $aLicencas;
  }
}
