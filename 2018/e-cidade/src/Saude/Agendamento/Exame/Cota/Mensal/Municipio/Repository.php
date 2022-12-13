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
namespace ECidade\Saude\Agendamento\Exame\Cota\Mensal\Municipio;

use ECidade\Saude\Agendamento\Exame\Cota\Mensal;
use ECidade\Saude\Agendamento\Exame\Cota\Mensal\Factory;
use ECidade\Saude\Agendamento\Exame\Cota\Mensal\RepositoryInterface;
use ECidade\Saude\Agendamento\Exame\Cota\Mensal\Repository as AbstractRepository;
use \DBException;
use \DBDate;

class Repository extends AbstractRepository implements RepositoryInterface
{
  /**
   * @param Mensal $oMensal
   * @throws \DBException
   */
  public function add(Mensal $oMensal)
  {
    $iGrupo  = parent::add($oMensal);
    $aExames = $oMensal->getProcedimentoArray();

    foreach ($aExames as $iIndice => $iProcedimento) {

      $oDaoGrupoExamePrestador = new \cl_grupomunicipio();
      $oDaoGrupoExamePrestador->age04_grupoexame   = $iGrupo;
      $oDaoGrupoExamePrestador->age04_procedimento = $iProcedimento;
      $oDaoGrupoExamePrestador->incluir(null);

      if ($oDaoGrupoExamePrestador->erro_status == '0') {
          throw new DBException("Erro ao cadastrar cota para o municipio.");
      }
    }
  }

  /**
   * Remove os grupo
   * @param Mensal
   */
  public function remove(Mensal $oMensal)
  {
    $oInfoAdicional = $oMensal->getInformacaoAdicional();

    /**
     * Excluimos os vínculos dos grupos com o procedimento
     */
    $oDaoGrupoMunicipio =  new \cl_grupomunicipio();

    $sWhere    = " age04_grupoexame = {$oInfoAdicional->iGrupo}";
    $lExclusao = $oDaoGrupoMunicipio->excluir(null, $sWhere);

    if ( !$lExclusao ) {
      throw new DBException("Erro ao exlcuir os exames da cota mensal do município.");
    }

    parent::remove($oMensal);
  }

  /**
   * Buscamos a cota pelo id do grupo
   *
   * @param  integer $iGrupo
   *
   * @return \Mensal
   */
  public function getCotaByIdGrupo($iGrupo)
  {
    $oCotaGrupo = parent::getCotaByIdGrupo($iGrupo);
    return $oCotaGrupo;
  }

  /**
   * Buscamos grupos de cotas do municipio
   *
   * @param $iPrestador
   * @return \ECidade\Saude\Agendamento\Exame\Cota\Mensal[]    Cotas
   * @throws \DBException
   * @internal param int $iPrestaddr Prestador
   */
  public function getGrupos()
  {
    $oDaoCotas = new \cl_cotaprestadoraexamemensal();

    $sCampos = implode(', ', array(
      'age01_tipo',
      'age01_ano',
      'age01_mes',
      'age01_quantidade',
      'age02_sequencial',
      'age02_nome'
    ));

    $sGroupBy = implode(',', array(
      'age01_tipo',
      'age01_ano',
      'age01_mes',
      'age01_quantidade',
      'age02_sequencial',
      'age02_nome'
    ));

    $sOrderBy = implode(',', array(
      'age01_ano',
      'age01_mes',
      'age02_nome'
    ));

    $sSqlCotas = $oDaoCotas->getAllGrupoMunicipio($sCampos, $sOrderBy, $sGroupBy);
    $rsCotas   = db_query($sSqlCotas);

    if (!$rsCotas) {
      throw new DBException('Erro ao buscar as cotas do municipio.');
    }

    $aRetorno = array();

    while ($oCota = pg_fetch_object($rsCotas)) {

      $oFactory = new Factory();

      $oFactotyParametros = new \stdClass();
      $oFactotyParametros->iQuantidade = $oCota->age01_quantidade;
      $oFactotyParametros->iMes        = $oCota->age01_mes;
      $oFactotyParametros->iAno        = $oCota->age01_ano;
      $oFactotyParametros->sNome       = $oCota->age02_nome;

      $oMensal  = $oFactory->getCotaMensal($oCota->age01_tipo, $oFactotyParametros);

      $oInfoAdicional         = new \stdClass();
      $oInfoAdicional->iGrupo = $oCota->age02_sequencial;

      $oMensal->setInformacaoAdicional($oInfoAdicional);

      $aRetorno[] = $oMensal;
    }

    return $aRetorno;
  }

  /**
   * Checamos se há cota do exame desejado numa determinada competência
   *
   * @param  integer $iMes
   * @param  integer $iAno
   * @param  mixed (integer, array)  $procedimento
   *
   * @return boolean
   */
  public function checkByCompetenciaProcedimento($iMes, $iAno, $procedimento)
  {
    $sProcedimento = $procedimento;

    if ( is_array($procedimento) ) {
      $sProcedimento = implode(',',$procedimento);
    }

    $aWhere   = array();
    $aWhere[] = "age04_procedimento in ($sProcedimento)";
    $aWhere[] = "age01_mes = $iMes";
    $aWhere[] = "age01_ano = $iAno";

    $oDaoCotas = new \cl_cotaprestadoraexamemensal();
    $sSqlCheck = $oDaoCotas->getQueryMunicipio('age01_sequencial', implode(' and ',$aWhere));

    $rsCheck   = db_query($sSqlCheck);

    if ( !$rsCheck ) {
      throw new DBException("Erro ao validar disponibilidade de cotas na competência para o grupo de exames desejado.");
    }

    if (pg_num_rows($rsCheck) > 0) {
      return false;
    }

    return true;
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
  public function getQueryByGrupo($iGrupo, $sCampos, $sGroupBy)
  {
    $oDaoCotas = new \cl_cotaprestadoraexamemensal();
    $sSqlGrupo = $oDaoCotas->getQueryByGrupoMunicipio($iGrupo, $sCampos, null, $sGroupBy);

    return $sSqlGrupo;
  }
}
