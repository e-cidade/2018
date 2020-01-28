<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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

namespace ECidade\Saude\Agendamento\Exame\Cota\Mensal;

use ECidade\Saude\Agendamento\Exame\Cota\Mensal;
use ECidade\Saude\Agendamento\Exame\Cota\Mensal\RepositoryInterface;
use ECidade\Saude\Agendamento\Exame\Cota\Mensal\Factory;
use \DBException;
use \DBDate;

abstract class Repository implements RepositoryInterface
{
  /**
   * @param Mensal $oMensal
   * @throws \DBException
   */
  public function add(Mensal $oMensal)
  {
    $oDaoExaMensal = new \cl_cotaprestadoraexamemensal();

    $oDaoExaMensal->age01_quantidade = $oMensal->getQuantidade();
    $oDaoExaMensal->age01_mes        = $oMensal->getMes();
    $oDaoExaMensal->age01_ano        = $oMensal->getAno();
    $oDaoExaMensal->age01_tipo       = $oMensal::TIPO_COTA;
    $oDaoExaMensal->incluir(null);

    if ($oDaoExaMensal->erro_status == 0) {
      throw new DBException("Erro ao adicionar a Cota Mensal.");
    }

    $oDaoGrupo = new \cl_grupoexame();

    $oDaoGrupo->age02_nome                      = $oMensal->getNome();
    $oDaoGrupo->age02_cotaprestadoraexamemensal = $oDaoExaMensal->age01_sequencial;
    $oDaoGrupo->incluir(null);

    if ($oDaoGrupo->erro_status ==  0) {
      throw new DBException("Erro ao cadastrar Grupo Exame.");
    }

    return $oDaoGrupo->age02_sequencial;
  }

  /**
   * Remove os dados do Exame
   */
  public function remove(Mensal $oMensal)
  {
    $oInfoAdicional = $oMensal->getInformacaoAdicional();

    /**
     * Excluimos o grupo
     */
    $oDaoGrupoExame =  new \cl_grupoexame();
    $lGrupoExame    = $oDaoGrupoExame->excluir($oInfoAdicional->iGrupo);

    if ( !$lGrupoExame ) {
      throw new DBException("Erro ao excluir a grupo da cota mensal.");
    }

    /**
     * Excluir  cota de exame mensal
     */
    $oDaoCotaExameMensal =  new \cl_cotaprestadoraexamemensal();
    $lCotaExameMensal    = $oDaoCotaExameMensal->excluir($oInfoAdicional->iCota);

    if ( !$lCotaExameMensal ) {
      throw new DBException("Erro ao excluir a cota de exame mensal.");
    }
  }

  public function getCotaByIdGrupo($iGrupo)
  {
    $sCampos = implode(', ', array(
      'age01_tipo',
      'age01_ano',
      'age01_mes',
      'age01_quantidade',
      'age01_sequencial',
      'age02_nome'
    ));

    $sGroupBy = implode(',', array(
      'age01_tipo',
      'age01_ano',
      'age01_mes',
      'age01_quantidade',
      'age01_sequencial',
      'age02_nome'
    ));

    $sSqlGrupo = $this->getQueryByGrupo($iGrupo, $sCampos, $sGroupBy);
    $rsGrupo   = db_query($sSqlGrupo);

    if (!$rsGrupo) {
      throw new DBException("Erro ao buscar os dados da conta mensal.");
    }

    if (pg_num_rows($rsGrupo) == 0 || pg_num_rows($rsGrupo) > 1) {
      throw new DBException("Erro ao buscar os dados da conta mensal.");
    }

    $oGrupo = \db_utils::fieldsMemory($rsGrupo, 0);

    $oFactoryParametros  = new \stdClass();
    $oFactoryParametros->iQuantidade = $oGrupo->age01_quantidade;
    $oFactoryParametros->iAno        = $oGrupo->age01_ano;
    $oFactoryParametros->iMes        = $oGrupo->age01_mes;
    $oFactoryParametros->sNome       = $oGrupo->age02_nome;

    $oFactory   = new Factory();
    $oCotaGrupo = $oFactory->getCotaMensal($oGrupo->age01_tipo, $oFactoryParametros);

    $oObjGrupo         = new \stdClass;
    $oObjGrupo->iGrupo = $iGrupo;
    $oObjGrupo->iCota  = $oGrupo->age01_sequencial;

    $oCotaGrupo->setInformacaoAdicional($oObjGrupo);

    return $oCotaGrupo;
  }

  /**
   * Criamos a query que consulta os dados do grupo de cotas
   *
   * @param  integer $iGrupo
   * @param  string  $sCampos
   * @param  string  $sGroupBy
   *
   * @return string           sql query
   */
  public function getQueryByGrupo($iGrupo, $sCampos, $sGroupBy){}
}
