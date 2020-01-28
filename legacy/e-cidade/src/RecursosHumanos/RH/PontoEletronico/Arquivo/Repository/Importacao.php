<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Repository;

/**
 * Class Importacao
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Repository
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Importacao {

  /**
   * Retorna a competência e exercício da última efetividade importada
   * @return \_db_fields|null|\stdClass
   * @throws \DBException
   */
  public static function ultimaEfetividadeImportada() {

    $iInstituicao               = db_getsession('DB_instit');
    $oDaoPontoEletronicoArquivo = new \cl_pontoeletronicoarquivo();
    $sSqlPontoEletronicoArquivo = $oDaoPontoEletronicoArquivo->sql_query_file(
      null,
      'rh196_efetividade_competencia as competencia, rh196_efetividade_exercicio as exercicio',
      'rh196_efetividade_exercicio desc, rh196_efetividade_competencia desc limit 1',
      "rh196_instituicao = {$iInstituicao}"
    );
    $rsPontoEletronicoArquivo = db_query($sSqlPontoEletronicoArquivo);

    if(!$rsPontoEletronicoArquivo) {
      throw new \DBException('Erro ao buscar as informações da última importação.');
    }

    if(pg_num_rows($rsPontoEletronicoArquivo) == 0) {
      return null;
    }

    return \db_utils::fieldsMemory($rsPontoEletronicoArquivo, 0);
  }

  /**
   * Retorna um coleção das matrículas a terem o cálculo de horas processado
   * @param int $iCodigoArquivo
   * @return array
   * @throws \DBException
   */
  public static function matriculasParaProcessar($iCodigoArquivo) {

    if(empty($iCodigoArquivo)) {
      throw new \DBException('Arquivo para processamento das matrículas não informado.');
    }

    $oDaoPontoEletronicoArquivoData = new \cl_pontoeletronicoarquivodata();
    $sSqlPontoEletronicoArquivoData = $oDaoPontoEletronicoArquivoData->sql_query_file(
      null,
      'distinct rh197_matricula as matricula',
      'matricula',
      "rh197_pontoeletronicoarquivo = {$iCodigoArquivo}"
    );

    $rsMatriculasProcessar = db_query($sSqlPontoEletronicoArquivoData);

    if(!$rsMatriculasProcessar) {
      throw new \DBException("Não foi possível buscar as matrículas para processar.");
    }

    if(pg_num_rows($rsMatriculasProcessar) == 0) {
      return array();
    }

    return \db_utils::makeCollectionFromRecord($rsMatriculasProcessar, function ($oRetornoMatriculasProcessar) {
      return $oRetornoMatriculasProcessar->matricula;
    });
  }

  /**
   * Retorna um array de matrículas
   *
   * @param array $aPis
   * @return array
   * @throws \DBException
   */
  public static function matriculasParaProcessarPorPis($aPis) {

    $oDaoPontoEletronicoArquivoData = new \cl_pontoeletronicoarquivodata();
    $sSqlPontoEletronicoArquivoData = $oDaoPontoEletronicoArquivoData->sql_query_file(
      null,
      'distinct rh197_matricula as matricula',
      'matricula',
      "rh197_pis IN ('". implode('\', \'', $aPis) ."')"
    );

    $rsMatriculasProcessar = db_query($sSqlPontoEletronicoArquivoData);

    if(!$rsMatriculasProcessar) {
      throw new \DBException("Não foi possível buscar as matrículas para processar.");
    }

    if(pg_num_rows($rsMatriculasProcessar) == 0) {
      return array();
    }

    return \db_utils::makeCollectionFromRecord($rsMatriculasProcessar, function ($oRetornoMatriculasProcessar) {
      return $oRetornoMatriculasProcessar->matricula;
    });
  }

  /**
   * Datas de registros importados do arquivo
   * @param $iCodigoArquivo
   * @return array
   * @throws \DBException
   */
  public static function datasParaProcessar($iCodigoArquivo) {

    if(empty($iCodigoArquivo)) {
      throw new \DBException('Arquivo para processamento das matrículas não informado.');
    }

    $oDaoPontoEletronicoArquivoData = new \cl_pontoeletronicoarquivodata();
    $sSqlPontoEletronicoArquivoData = $oDaoPontoEletronicoArquivoData->sql_query_file(
      null,
      'rh197_data',
      'rh197_data',
      "rh197_pontoeletronicoarquivo = {$iCodigoArquivo}"
    );

    $rsPontoEletronicoArquivoData = db_query($sSqlPontoEletronicoArquivoData);

    if(!$rsPontoEletronicoArquivoData) {
      throw new \DBException("Não foi possível buscar as matrículas para processar.");
    }

    if(pg_num_rows($rsPontoEletronicoArquivoData) == 0) {
      return array();
    }

    return \db_utils::makeCollectionFromRecord($rsPontoEletronicoArquivoData, function ($oRetorno) {
      return $oRetorno->rh197_data;
    });
  }
}