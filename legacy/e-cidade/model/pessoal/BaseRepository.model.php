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

/**
 *
 *
 * @package Pessoal
 * @author $Author: dbrafael.nery $
 * @version $Revision: 1.7 $
 */

abstract class BaseRepository {

  const MENSAGEM = 'recursoshumanos.pessoal.BaseRepository.';

  /**
   *Array com instancia de Base
   *
   * @var Base
   */
  static $aBases = array();


  /**
   * Remove a base no repository
   *
   * @param Base $oBase
   * @return Boolean
   */
  public static function removeBase(Base $oBase) {

    $iAno         = $oBase->getCompetencia()->getAno();
    $iMes         = $oBase->getCompetencia()->getMes();
    $iCodigo      = $oBase->getCodigo();
    $iInstituicao =  $oBase->getInstituicao()->getCodigo();
    $sChave       = "{$iAno}{$iMes}{$iCodigo}{$iInstituicao}";

    if(array_key_exists($sChave, BaseRepository::$aBases)) {
      unset(BaseRepository::$aBases[$sChave]);
    }

    return true;
  }

  /**
   * Adiciona a base no repository
   *
   * @param Base $oBase
   * @return Boolean
   */
  public static function adicionarBase(Base $oBase) {

    $iAno         = $oBase->getCompetencia()->getAno()+0;
    $iMes         = $oBase->getCompetencia()->getMes()+0;
    $iCodigo      = $oBase->getCodigo();
    $iInstituicao = $oBase->getInstituicao()->getCodigo()+0;
    $sChave       = "{$iAno}{$iMes}{$iCodigo}{$iInstituicao}";

    if(!array_key_exists($sChave, BaseRepository::$aBases)) {
      BaseRepository::$aBases[$sChave] = $oBase;
    }

    return true;
  }

  /**
   * Retorna a base
   *
   * @param String $sCodigo
   * @param DBCompetencia $oCompetencia
   * @param Instituicao $oInstituicao
   * @return Base
   */
  public static function getBase($sCodigo, DBCompetencia $oCompetencia = null, Instituicao $oInstituicao = null) {


    if (empty($sCodigo)) {
      throw new ParameterException(_M(self::MENSAGEM . 'codigo_base_nao_informado'));
    }

    if(empty($oCompetencia)) {
      $oCompetencia = DBPessoal::getCompetenciaFolha();
    }

    $iAno         = $oCompetencia->getAno()+0;
    $iMes         = $oCompetencia->getMes()+0;
    $iCodigo      = $sCodigo;

    if(empty($oInstituicao)) {
      $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
    }

    $iInstituicao = $oInstituicao->getCodigo()+0;
    $sChave       = "{$iAno}{$iMes}{$iCodigo}{$iInstituicao}";

    if (array_key_exists($sChave, self::$aBases)) {
      return self::$aBases[$sChave];
    }

    return BaseRepository::procurarBase($sCodigo, $oCompetencia, $oInstituicao);
  }

  /**
   * Procura a base conforme os parâmetros passados
   *
   * @param String $sCodigo
   * @param DBCompetencia $oCompetencia
   * @param Instituicao $oInstituicao
   * @return Base
   * @throws DBException
   */
  private static function procurarBase($sCodigo, DBCompetencia $oCompetencia, Instituicao $oInstituicao) {

    $oDaoBases = new cl_bases();
    $sSqlBases = $oDaoBases->sql_query($oCompetencia->getAno(), $oCompetencia->getMes(), $sCodigo, $oInstituicao->getCodigo());
    $rsBases   = db_query($sSqlBases);

    if (!$rsBases) {
      throw new DBException(_M(self::MENSAGEM . 'erro_pesquisar_bases'));
    }

    if (pg_num_rows($rsBases) == 0) {
      throw new BusinessException(_M(self::MENSAGEM . 'base_nao_encontrada'));
    }

    $oDadosBases = db_utils::fieldsMemory($rsBases, 0);

    $oBase = new Base($oDadosBases->r08_codigo, $oCompetencia, $oInstituicao);
    $oBase->setNome($oDadosBases->r08_descr);
    $oBase->setCalculoPontoFixo($oDadosBases->r08_pfixo);
    $oBase->setCalculoQuantidade($oDadosBases->r08_calqua);
    $oBase->setValorMesAnterior($oDadosBases->r08_mesant);

    $aRubricas = RubricaRepository::getRubricasByBase($oBase);
    $oBase->setRubricas($aRubricas);

    BaseRepository::adicionarBase($oBase);
    return $oBase;
  }

  /**
   * Verifica se as rubricas da base são só de desconto.
   *
   * @return Boolean
   */
  public static function verificaRubricasDesconto($sCodigo, DBCompetencia $oCompetencia, Instituicao $oInstituicao) {

    $oBase            = new Base($sCodigo, $oCompetencia, $oInstituicao);
    $aRubricasBase    = RubricaRepository::getRubricasByBase($oBase);
    $lApenasDescontos = true;

    foreach ($aRubricasBase as $oRubricaBase ) {

      if ($oRubricaBase->getTipo() != 2 && $lApenasDescontos) {
        $lApenasDescontos = false;
      }
    }

    if ($lApenasDescontos) {
      return true;
    } else {
      return false;
    }
  }


}
