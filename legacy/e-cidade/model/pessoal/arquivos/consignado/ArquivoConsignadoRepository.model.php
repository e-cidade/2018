<?php
/*
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

/**
 * Class ArquivoConsignadoRepository
 */
class ArquivoConsignadoRepository {

  /** Lista de instâncias de arquivos
   *
   * @var ArquivoConsignado[]
   */
  private static $aArquivos = array();

  /**
   * Adiciona uma instancia do objeto na memória
   *
   * @param ArquivoConsignado $oArquivo
   */
  public static function add(ArquivoConsignado $oArquivo ) {
    self::$aArquivos[$oArquivo->getCodigo()] =  $oArquivo;
  }

  /**
   * Remove a instancia da memoria
   *
   * @param  ArquivoConsignado $oArquivo
   * @return boolean
   */
  public static function remove(ArquivoConsignado $oArquivo ) {
    unset(self::$aArquivos[$oArquivo->getCodigo()]);
  }

  /**
   * Retorna uma instância unica do objeto pelo código sequencial
   *
   * @param  Integer $iCodigo Código Indentificador do arquivo
   * @return \ArquivoConsignado
   * @throws \BusinessException
   * @throws \DBException
   */
  public static function getByCodigo($iCodigo) {

    $oDaoConsignadoMovimento = new cl_rhconsignadomovimento();
    $sSqlConsignadoMovimento = $oDaoConsignadoMovimento->sql_query_file($iCodigo, "*");
    $rsConsignadoMovimento   = db_query($sSqlConsignadoMovimento);

    if (!$rsConsignadoMovimento) {
      throw new DBException("Erro ao consultar Dados do Arquivo");
    }

    if (pg_num_rows($rsConsignadoMovimento) == 0) {
      throw new BusinessException("Nao foi encontrado o registro informado");
    }

    $oConsignadoMovimento = db_utils::fieldsMemory($rsConsignadoMovimento, 0);
    return self::make($oConsignadoMovimento);
  }

  /**
   * Retorna uma Instancia do arquivo de Consigancao
   * @param $oConsignadoMovimento
   * @return \ArquivoConsignado
   */
  public static function make($oConsignadoMovimento) {

    $oCompetencia      = new DBCompetencia($oConsignadoMovimento->rh151_ano, $oConsignadoMovimento->rh151_mes);
    $oInstituicao      = InstituicaoRepository::getInstituicaoByCodigo($oConsignadoMovimento->rh151_instit);

    $oArquivoConsignado = new ArquivoConsignado();

    $oArquivoConsignado->setCodigo($oConsignadoMovimento->rh151_sequencial);
    $oArquivoConsignado->setNome($oConsignadoMovimento->rh151_nomearquivo);
    $oArquivoConsignado->setCompetencia($oCompetencia);
    $oArquivoConsignado->setInstituicao($oInstituicao);
    $oArquivoConsignado->setRelatorio($oConsignadoMovimento->rh151_relatorio);
    $oArquivoConsignado->setProcessado($oConsignadoMovimento->rh151_processado == 't' ? true : false);
    $oArquivoConsignado->setArquivo($oConsignadoMovimento->rh151_arquivo);
    if (!empty($oConsignadoMovimento->rh151_banco)) {
      $oArquivoConsignado->setBanco(new Banco($oConsignadoMovimento->rh151_banco));
    }

    return $oArquivoConsignado;
  }

  /**
   * Salva a instancia do arquivo no "banco de dados"
   * @param \ArquivoConsignado $oArquivo
   * @throws \DBException
   */
  public static function persist(ArquivoConsignado $oArquivo) {

    $oDaoConsignadoMovimento = new cl_rhconsignadomovimento();

    $oDaoConsignadoMovimento->rh151_nomearquivo = $oArquivo->getNome();
    $oDaoConsignadoMovimento->rh151_ano         = $oArquivo->getCompetencia()->getAno();
    $oDaoConsignadoMovimento->rh151_mes         = $oArquivo->getCompetencia()->getMes();
    $oDaoConsignadoMovimento->rh151_instit      = $oArquivo->getInstituicao()->getSequencial();
    $oDaoConsignadoMovimento->rh151_relatorio   = $oArquivo->getRelatorio();
    $oDaoConsignadoMovimento->rh151_processado  = $oArquivo->isProcessado() ? "true" : "false";
    $oDaoConsignadoMovimento->rh151_arquivo     = $oArquivo->getArquivo();
    $oDaoConsignadoMovimento->rh151_sequencial  = $oArquivo->getCodigo();
    $oDaoConsignadoMovimento->rh151_tipoconsignado = "A";
    $oDaoConsignadoMovimento->rh151_situacao       = "N";
    if ($oArquivo->getBanco() != '') {
      $oDaoConsignadoMovimento->rh151_banco = $oArquivo->getBanco()->getCodigo();
    }

    if ($oArquivo->getCodigo()) {

      $oDaoConsignadoMovimento->rh151_sequencial = $oArquivo->getCodigo();
      $oDaoConsignadoMovimento->alterar($oArquivo->getCodigo());
    } else {
      $oDaoConsignadoMovimento->incluir(null);
    }

    if ($oDaoConsignadoMovimento->erro_status == "0") {
      throw new DBException("Erro ao salvar os Dados do Arquivo");
    }

    $oArquivo->setCodigo($oDaoConsignadoMovimento->rh151_sequencial);
    self::add($oArquivo);

    $aRegistros = $oArquivo->getRegistros();

    /**
     * Percorre os registros do arquivo pra adiciona-los à base de dados
     */
    foreach ($aRegistros as $oRegistro) {

      $oRegistro->setArquivoConsignado($oArquivo);
      RegistroConsignadoRepository::persist($oRegistro);
    }
  }

  /**
   * @param \Instituicao   $oInstituicao
   * @param \DBCompetencia $oCompetencia
   * @param bool           $lProcessados
   * @return \ArquivoConsignado
   * @throws \DBException
   */
  public static function getUltimoArquivoNaCompetenciaDoBanco(Instituicao $oInstituicao, DBCompetencia $oCompetencia = null, Banco $oBanco, $lProcessados = false) {

    $aWhere = array();

    /**
     * Caso não seja informado a competência, será retornado o último arquivo inportado.
     */
    if ($oCompetencia) {
      $aWhere[] = "rh151_ano = '{$oCompetencia->getAno()}'";
      $aWhere[] = "rh151_mes = '{$oCompetencia->getMes()}'";
    }

    $aWhere[] =  "rh151_instit = {$oInstituicao->getCodigo()}";
    $aWhere[] =  "rh151_banco = '{$oBanco->getCodigo()}'";
    $aWhere[] =  "rh151_tipoconsignado = '".ArquivoConsignado::TIPO_ARQUIVO."'";

    if ($lProcessados) {
      $aWhere[] = " rh151_processado is true";
    }

    $sOrder = "rh151_sequencial DESC LIMIT 1";
    $sWhere = implode(' and ', $aWhere);
    $oDaoConsignetMovimento = new cl_rhconsignadomovimento();
    $sSqlConsignetMovimento = $oDaoConsignetMovimento->sql_query_file(null, "*", $sOrder, $sWhere);
    $rsConsignetMovimento   = db_query($sSqlConsignetMovimento);

    if (!$rsConsignetMovimento) {
      throw new DBException(_M(self::MENSAGEM . 'erro_consignetmovimento'));
    }

    if (pg_num_rows($rsConsignetMovimento) == 0) {
      return null;
    }

    $oConsignetMovimento   = db_utils::fieldsMemory($rsConsignetMovimento, 0);
    return ArquivoConsignadoRepository::make($oConsignetMovimento);
  }
}