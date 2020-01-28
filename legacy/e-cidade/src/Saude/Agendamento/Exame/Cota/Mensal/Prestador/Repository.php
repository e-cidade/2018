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
namespace ECidade\Saude\Agendamento\Exame\Cota\Mensal\Prestador;

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
    $iGrupo = parent::add($oMensal);

    $aExames = $oMensal->getPrestadorExameArray();

    foreach ($aExames as $iIndice => $iPrestadorExame) {

      $oDaoGrupoExamePrestador = new \cl_grupoexameprestador();
      $oDaoGrupoExamePrestador->age03_grupoexame        = $iGrupo;
      $oDaoGrupoExamePrestador->age03_prestadorvinculos = $iPrestadorExame;
      $oDaoGrupoExamePrestador->incluir(null);

      if ($oDaoGrupoExamePrestador->erro_status == '0') {
          throw new DBException("Erro ao cadastrar Grupo Exame Prestador.");
      }

      $iDiaFinal = DBDate::getQuantidadeDiasMes($oMensal->getMes(), $oMensal->getAno());

      $oDataInicial = new DBDate($oMensal->getAno() . "-" . $oMensal->getMes() . "-01");
      $oDataFinal   = new DBDate($oMensal->getAno() . "-" . $oMensal->getMes() . "-" . $iDiaFinal);

      $sInsertDiasSemana = $oDaoGrupoExamePrestador->getQueryInsertDiasSemanaParaMes(
        $oDataInicial, $oDataFinal, $iPrestadorExame
      );

      $rsInsertDiasSemana = db_query($sInsertDiasSemana);

      if (!$rsInsertDiasSemana) {
        throw new DBException("Erro ao inserir dias da semana para a cota mensal.");
      }
    }
  }

  /**
   * Remove os dados do Exame
   * @param Mensal
   */
  public function remove(Mensal $oMensal)
  {
    $oInfoAdicional = $oMensal->getInformacaoAdicional();

    /**
     * Removemos os horários de cada dia da semana que fora incluido para a cota
     */
    $oDaoHorarios    = new \cl_sau_prestadorhorarios();
    $aPrestadorExame = $oMensal->getPrestadorExameArray();

    foreach ($aPrestadorExame as $iIndice => $iPrestadorExame) {

      $sDataInicial  = $oMensal->getAno() . '-';
      $sDataInicial .= str_pad($oMensal->getMes(), 2, '0', STR_PAD_LEFT) . '-';
      $sDataInicial .= '01';

      $sDataFinal  = $oMensal->getAno() . '-';
      $sDataFinal .= str_pad($oMensal->getMes(), 2, '0', STR_PAD_LEFT) . '-';
      $sDataFinal .= DBDate::getQuantidadeDiasMes($oMensal->getMes(), $oMensal->getAno());

      $sWhereHorarios  = " s112_i_prestadorvinc = $iPrestadorExame ";
      $sWhereHorarios .= "and s112_d_valinicial = '$sDataInicial' ";
      $sWhereHorarios .= "and s112_d_valfinal   = '$sDataFinal' ";
      $sWhereHorarios .= "and s112_c_tipograde  = 'M' ";

      $lHorarios = $oDaoHorarios->excluir(null, $sWhereHorarios);

      if (!$lHorarios) {
        throw new DBException("Prestadora possui exames agendados utilizando esta cota.");
      }
    }

    /**
     * Excluimos os vínculos dos grupos com o prestador e seus exames
     */
    $oDaoGrupoExamePrestador =  new \cl_grupoexameprestador();

    $sWhere                = " age03_grupoexame = {$oInfoAdicional->iGrupo}";
    $lGrupoExamePrestador = $oDaoGrupoExamePrestador->excluir(null, $sWhere);

    if ( !$lGrupoExamePrestador ) {
      throw new DBException("Erro ao exlcuir os exames da cota mensal.");
    }

    parent::remove($oMensal);
  }


  public function getCotaByIdGrupo($iGrupo)
  {
    $oCotaGrupo = parent::getCotaByIdGrupo($iGrupo);

    /**
     *  Buscamos o(s) código(s) do(s) vínculo(s) entre prestador e exame(s)
     */
    $oDaoCotas     = new \cl_cotaprestadoraexamemensal();
    $sCampos       = 'age03_prestadorvinculos';
    $sSqlPrestador = $oDaoCotas->getQueryByGrupo($iGrupo, $sCampos);
    $rsPrestador   =  db_query($sSqlPrestador);

    if (!$rsPrestador) {
      throw new DBException("Erro ao buscar os exames da cota.");
    }

    if(pg_num_rows($rsPrestador) == 0) {
      throw new DBException("Erro ao buscar os exames da cota.");
    }

    $aGrupoExames    = \db_utils::getCollectionByRecord($rsPrestador);
    $aPrestadorExame = array();

    foreach ($aGrupoExames as $iIndice => $oGrupoExame) {
      $aPrestadorExame[] = $oGrupoExame->age03_prestadorvinculos;
    }

    $oCotaGrupo->setPrestadorExame($aPrestadorExame);

    return $oCotaGrupo;
  }

  /**
   * Buscamos as cotas do prestador desejado
   *
   * @param $iPrestador
   * @return \ECidade\Saude\Agendamento\Exame\Cota\Mensal[]    Cotas
   * @throws \DBException
   * @internal param int $iPrestaddr Prestador
   */
  public function getGrupoByPrestador($iPrestador)
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

    $sSqlCotas = $oDaoCotas->getQueryByPrestador($iPrestador, $sCampos, $sOrderBy, $sGroupBy);

    $rsCotas   = db_query($sSqlCotas);
    if (!$rsCotas) {
      throw new DBException('Erro ao buscar as cotas do prestador desejado.');
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

  public function checkByCompetenciaPrestadorExame($iMes, $iAno, $prestadorExame)
  {
    $sPrestadorExame = $prestadorExame;

    if ( is_array($prestadorExame) ) {
      $sPrestadorExame = implode(',',$prestadorExame);
    }

    $aWhere   = array();
    $aWhere[] = "age03_prestadorvinculos in ($sPrestadorExame)";
    $aWhere[] = "age01_mes = $iMes";
    $aWhere[] = "age01_ano = $iAno";

    $oDaoCotas = new \cl_cotaprestadoraexamemensal();
    $sSqlCheck = $oDaoCotas->getQuery('age01_sequencial', implode(' and ',$aWhere));
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
    $sSqlGrupo = $oDaoCotas->getQueryByGrupo($iGrupo, $sCampos, null, $sGroupBy);

    return $sSqlGrupo;
  }
}
