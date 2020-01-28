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

/**
 * Repositório dos arquivos do Consignet
 *
 * @abstract
 * @author     Renan Melo  <renan@dbseller.com.br>
 * @author     Rafael Nery <rafael.nery@dbseller.com.br>
 * @package    Pessoal
 * @subpackage Arquivos
 */
abstract class ArquivoConsignetRepository {

 const MENSAGEM = 'recursoshumanos.pessoal.ArquivoConsignetRepository.';

 /**
  * Lista de instâncias de arquivos
  *
  * @var ArquvivoConsignet[]
  */
 private static $aArquivos = array();

 /**
  * Adiciona uma instancia do objeto na memória
  *
  * @param ArquivoConsignet $oArquivo
  */
 public static function add( ArquivoConsignet $oArquivo ) {
   self::$aArquivos[$oArquivo->getCodigo()] =  $oArquivo;
 }

 /**
  * Remove a instancia da memoria
  *
  * @param  ArquivoConsignet $oArquivo
  * @return boolean
  */
 public static function remove( ArquivoConsignet $oArquivo ) {
    unset(self::$aArquivos[$oArquivo->getCodigo()]);
 }

 /**
  * Retorna uma instância unica do objeto pelo código sequencial
  *
  * @param  Integer $iCodigo Código Indentificador do arquivo
  * @return ArquivoConsignet
  */
 public static function getByCodigo( $iCodigo ) {

   $oDaoConsignadoMovimento = new cl_rhconsignadomovimento();
   $sSqlConsignadoMovimento = $oDaoConsignadoMovimento->sql_query_file($iCodigo, "*");
   $rsConsignadoMovimento   = db_query($sSqlConsignadoMovimento);

   if (!$rsConsignadoMovimento) {
     throw new DBException(_M(self::MENSAGEM . 'erro_econsigmovimento'));
   }

   if (pg_num_rows($rsConsignadoMovimento) == 0) {
     throw new BusinessException(_M(self::MENSAGEM . 'nenhum_registro_consignetmovimento'));
   }

   $oConsignadoMovimento   = db_utils::fieldsMemory($rsConsignadoMovimento, 0);

   return ArquivoConsignetRepository::make($oConsignadoMovimento);
 }

  /**
   * Verifica se existe algum arquivo de movimentação do Consignet pela competência
   * @param  DBCompetencia $oCompetencia
   * @param \Instituicao   $oInstituicao
   * @return bool
   * @throws \DBException
   */
 public static function hasArquivoCompetencia (DBCompetencia $oCompetencia, Instituicao $oInstituicao) {

   $oDaoConsignetMovimento    = new cl_rhconsignadomovimento();
   $sWhereConsignetMovimento  = "rh151_ano = '{$oCompetencia->getAno()}' and rh151_mes = '{$oCompetencia->getMes()}' and rh151_instit = {$oInstituicao->getCodigo()} and rh151_banco = ''";
   $sSqlConsignetMovimento    = $oDaoConsignetMovimento->sql_query_file(null, 'rh151_sequencial', null, $sWhereConsignetMovimento);
   $rsConsignetMovimento      = db_query($sSqlConsignetMovimento);

   if (!$rsConsignetMovimento) {
     throw new DBException(_M(self::MENSAGEM . 'erro_consignetmovimento'));
   }

   if (pg_num_rows($rsConsignetMovimento) == 0) {
      return false;
   }

   return true;
 }

  /**
   * Monta o Objeto ArquivoConsignet a partir do objeto informado como parâmetro
   *
   * @param  object $oConsignadoMoviemento
   * @return ArquivoConsignet
   */
  public static function make($oConsignadoMovimento){

    $oCompetencia      = new DBCompetencia($oConsignadoMovimento->rh151_ano, $oConsignadoMovimento->rh151_mes);
    $oInstituicao      = InstituicaoRepository::getInstituicaoByCodigo($oConsignadoMovimento->rh151_instit);

    $oArquivoConsignet = new ArquivoConsignet();

    $oArquivoConsignet->setCodigo($oConsignadoMovimento->rh151_sequencial);
    $oArquivoConsignet->setNome($oConsignadoMovimento->rh151_nomearquivo);
    $oArquivoConsignet->setCompetencia($oCompetencia);
    $oArquivoConsignet->setInstituicao($oInstituicao);
    $oArquivoConsignet->setRelatorio($oConsignadoMovimento->rh151_relatorio);
    $oArquivoConsignet->setProcessado($oConsignadoMovimento->rh151_processado);

    return $oArquivoConsignet;
  }

  /**
   * Salva a instancia do arquivo no "banco de dados"
   * @param  ArquivoConsignet $oArquivo
   * @return void
   */
  public static function persist( ArquivoConsignet $oArquivo, $lProcessado = null) {

    $oDaoConsignadoMovimento                    = new cl_rhconsignadomovimento();

    $oDaoConsignadoMovimento->rh151_nomearquivo = $oArquivo->getNome();
    $oDaoConsignadoMovimento->rh151_ano         = $oArquivo->getCompetencia()->getAno();
    $oDaoConsignadoMovimento->rh151_mes         = $oArquivo->getCompetencia()->getMes();
    $oDaoConsignadoMovimento->rh151_instit      = $oArquivo->getInstituicao()->getSequencial();
    $oDaoConsignadoMovimento->rh151_relatorio   = $oArquivo->getRelatorio();
    $oDaoConsignadoMovimento->rh151_arquivo     = 'null';
    $oDaoConsignadoMovimento->rh151_processado  = 'false';

    if ( $lProcessado != null ) {
      $oDaoConsignadoMovimento->rh151_processado = $lProcessado;
    }

    if ($oArquivo->getCodigo()) {
      $oDaoConsignadoMovimento->rh151_sequencial = $oArquivo->getCodigo();
      $oDaoConsignadoMovimento->alterar($oArquivo->getCodigo());
    } else {
      $oDaoConsignadoMovimento->incluir(null);
    }

    if ( $oDaoConsignadoMovimento->erro_status == "0" ) {
      throw new DBException(_M(self::MENSAGEM . "erro_ao_persistir_dados").$oDaoConsignadoMovimento->erro_msg);
    }

    $oArquivo->setCodigo($oDaoConsignadoMovimento->rh151_sequencial);
    self::add( $oArquivo );

    $aRegistros           = $oArquivo->getRegistros();
    $iQuantidadeRegistros = count($aRegistros);

    /**
     * Percorre os registros do arquivo pra adicionálos à base de dados
     */
    for ($iRegistro = 0; $iRegistro  < $iQuantidadeRegistros; $iRegistro++ ) {

      $oRegistro = $aRegistros[$iRegistro];
      $oRegistro->setArquivo($oArquivo);
      $oRegistro->setCodigoArquivo($oArquivo->getCodigo());
      $oRegistro->persist();

    }
  }

  /**
   * Remove um registro do banco
   * @param  ArquivoConsignet $oArquivo
   * @return void
   */
  public static function delete( ArquivoConsignet $oArquivo ) {
     //@TODO - Not Yet
  }

  /**
   * Retorna o último ArquivoConsignet da competência
   *
   * @param Instituicao $oInstituicao
   * @param DBCompetencia $oCompetencia
   * @return ArquivoEConsig
   * @throws DBException
   */
  public static function getUltimoArquivo(Instituicao $oInstituicao, DBCompetencia $oCompetencia, $lProcessado = null) {

    $sWhere  = "rh151_ano = '{$oCompetencia->getAno()}' AND ";
    $sWhere .= "rh151_mes = '{$oCompetencia->getMes()}' AND ";
    $sWhere .= "rh151_instit = {$oInstituicao->getCodigo()} AND ";
    $sWhere .= "rh151_banco  = ''";

    if ( $lProcessado !== null) {

      if ( $lProcessado === false ) {
        $sWhere .= " and rh151_processado = 'f'";
      } else {
        $sWhere .= " and rh151_processado = 't'";
      }
    }

    $sOrder  = "rh151_sequencial DESC LIMIT 1";

    $oDaoConsignetMovimento = new cl_rhconsignadomovimento();
    $sSqlConsignetMovimento = $oDaoConsignetMovimento->sql_query_file(null, "*", $sOrder, $sWhere);
    $rsConsignetMovimento   = db_query($sSqlConsignetMovimento);

    if (!$rsConsignetMovimento) {
      throw new DBException(_M(self::MENSAGEM . 'erro_consignetmovimento'));
    }

    if (pg_num_rows($rsConsignetMovimento) == 0) {
      return new ArquivoConsignet();
    }

    $oConsignetMovimento   = db_utils::fieldsMemory($rsConsignetMovimento, 0);
    return ArquivoConsignetRepository::make($oConsignetMovimento);
  }

  public static function getUltimosArquivos(Instituicao $oInstituicao, DBCompetencia $oCompetencia, $lProcessado = null) {

    $sCamposPreSql        = "distinct(rh151_nomearquivo), max(rh151_sequencial) as sequencial";
    $sWherePreSql         = "     rh151_ano          = '{$oCompetencia->getAno()}' and rh151_banco = ''
                              and rh151_mes          = '{$oCompetencia->getMes()}'";
    $sWherePreSql        .= " and rh151_instit       = {$oInstituicao->getCodigo()} group by rh151_nomearquivo";

    $oDaoResultadoPreSql  = new cl_rhconsignadomovimento();
    $sSqlResultadoPreSql  = $oDaoResultadoPreSql->sql_query_file(null, $sCamposPreSql, null, $sWherePreSql);
    $rsResultadoPreSql    = db_query($sSqlResultadoPreSql);

    if (!$rsResultadoPreSql) {
      throw new DBException(_M(ArquivoConsignetRepository::MENSAGEM . 'erro_consultar_arquivo'));
    }

    if ( is_resource($rsResultadoPreSql) && pg_num_rows($rsResultadoPreSql) > 0 ){

      $sCampos  = "rh151_nomearquivo, rh151_sequencial, rh151_processado, rh151_ano, rh151_mes, rh151_relatorio, rh151_instit ";

      if ( $lProcessado !== null ) {
        $sWhere      = " rh151_processado   = '{$lProcessado}' and (";
      }

      for ($iIndice = 0; $iIndice < pg_num_rows($rsResultadoPreSql); $iIndice++) {

        $oArqResultadoPreSql  = db_utils::fieldsMemory($rsResultadoPreSql, $iIndice);
        $sWhere              .= "rh151_sequencial = {$oArqResultadoPreSql->sequencial}";

        if ( $iIndice < (pg_num_rows($rsResultadoPreSql) -1) ) {
          $sWhere            .= " or ";
        }

        if ( $iIndice == (pg_num_rows($rsResultadoPreSql) -1) && $lProcessado !== null) {
          $sWhere            .= ")";
        }
      }

      $oDaoArquivo  = new cl_rhconsignadomovimento();
      $sSqlArquivos = $oDaoArquivo->sql_query(null, $sCampos, null, $sWhere);
      $rsArquivos   = db_query($sSqlArquivos);

      if (!$rsArquivos) {
        throw new DBException(_M(ArquivoConsignetRepository::MENSAGEM . 'erro_consultar_arquivo'));
      }

      if ( is_resource($rsArquivos) && pg_num_rows($rsArquivos) == 0 ){
        throw new DBException(_M('recursoshumanos.pessoal.ProcessamentoPonto.nenhum_dado_encontrado'));
      }

      $aArquivos = array();

      for ($iIndice = 0; $iIndice < pg_num_rows($rsArquivos); $iIndice++) {

        $oDadoArquivo = db_utils::fieldsMemory($rsArquivos, $iIndice);
        $aArquivos[]  = ArquivoConsignetRepository::make($oDadoArquivo);
      }

      return $aArquivos;

    }

    return false;
  }

  /**
   * Realiza a importação dos dados que estao nas tabelas de
   * consignação para a tabela rhpreponto.
   * @throws \BusinessException
   * @throws \DBException
   */
  public static function importarPrePonto() {

    $oInstituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));
    $oCompetencia = DBPessoal::getCompetenciaFolha();
    $oDaoPrePonto = new cl_rhpreponto();
    $aArquivos    = ArquivoConsignetRepository::getUltimosArquivos($oInstituicao, $oCompetencia, 'false');

    if (!$aArquivos) {
      return false;
    }

    /**
     * Percorre cada arquivo importado, mprocessando seus
     * dados para rhpreponto
     */
    foreach ($aArquivos as $oArquivo) {
      /**
       * Verifica se o arquivo ja foi processado
       */
      if ( !$oArquivo->getProcessado() !== false && !$oArquivo->getProcessado() !== 'f') {
        continue;
      }

      $oArquivo->carregarRegistros(false);


      /**
       * Seta um novo objeto arquivo para persistir, zerando os registros.
       */
      $oArquivoPersistir = new ArquivoConsignet();
      $oArquivoPersistir->setCodigo($oArquivo->getCodigo());
      $oArquivoPersistir->setNome($oArquivo->getNome());
      $oArquivoPersistir->setRelatorio($oArquivo->getRelatorio());
      $oArquivoPersistir->setProcessado($oArquivo->getProcessado());
      $oArquivoPersistir->setCompetencia($oArquivo->getCompetencia());
      $oArquivoPersistir->setInstituicao($oArquivo->getInstituicao());
      $oArquivoPersistir->limparRegistros();

      /**
       * Percorre cada registro do arquivo inserindo em rhpreponto.
       */
      foreach ($oArquivo->getRegistros() as $oRegistro) {

        /**
         * Testa para ver se o registro foi rejeitado por algum motivo e não
         */
        $fMargem = $oRegistro->getServidor()->getMargemConsignavel($oRegistro::RUBRICA_MARGEM_CONSIGNAVEL);
        $fValorConsignar = $oRegistro->getValorParcela();        
        $oDaoPrePonto->rh149_instit     = $oArquivo->getInstituicao()->getSequencial();
        $oDaoPrePonto->rh149_regist     = $oRegistro->getServidor()->getMatricula();
        $oDaoPrePonto->rh149_rubric     = $oRegistro->getRubrica()->getCodigo();
        $oDaoPrePonto->rh149_valor      = $fValorConsignar;
        $oDaoPrePonto->rh149_quantidade = 1;
        $oDaoPrePonto->rh149_tipofolha  = FolhaPagamento::TIPO_FOLHA_SALARIO;
        $oDaoPrePonto->incluir(null);

        if ($oDaoPrePonto->erro_status == '0') {
          throw new BusinessException("Erro ao importar os dados do arquivos de consignados de rondonia");
        }

        $oRegistro->setCodigoArquivo($oArquivo->getCodigo());
        $oRegistro->setArquivo($oArquivo);
        $oRegistro->setValorDescontado($fValorConsignar);

        $oRegistroSalvo = new RegistroArquivoImportacaoConsignet();

        $oRegistroSalvo->setCodigoArquivo($oRegistro->getCodigoArquivo());
        $oRegistroSalvo->setArquivo($oRegistro->getArquivo());
        $oRegistroSalvo->setSequencialMovimentoServidor($oRegistro->getSequencialMovimentoServidor());
        $oRegistroSalvo->setSequencialMovimentoServidorRubrica($oRegistro->getSequencialMovimentoServidorRubrica());
        $oRegistroSalvo->setMatricula($oRegistro->getMatricula());
        $oRegistroSalvo->setNome($oRegistro->getNome());
        $oRegistroSalvo->setValorParcela($oRegistro->getValorParcela());
        $oRegistroSalvo->setParcela($oRegistro->getParcela());
        $oRegistroSalvo->setTotalParcelas($oRegistro->getTotalParcelas());
        $oRegistroSalvo->setMotivo($oRegistro->getMotivo());
        $oRegistroSalvo->setRubric($oDaoPrePonto->rh149_rubric);
        $oRegistroSalvo->setValorDescontado($oDaoPrePonto->rh149_valor);
        $oRegistroSalvo->setServidor(new Servidor($oRegistroSalvo->getMatricula(),
                                                  $oRegistro->getArquivo()->getCompetencia()->getAno(),
                                                  $oRegistro->getArquivo()->getCompetencia()->getMes(),
                                                  $oRegistro->getArquivo()->getInstituicao()->getSequencial()
                                                  ));
        $oRegistroSalvo->setRubrica(new Rubrica($oRegistroSalvo->getRubric(), $oRegistro->getArquivo()->getInstituicao()->getSequencial()));

        $oArquivoPersistir->adicionarRegistro($oRegistroSalvo);

        if ( $fValorConsignar != $oRegistroSalvo->getValorDescontado() ) {
          throw new BusinessException(_M(ArquivoConsignetRepository::MENSAGEM . 'erro_salvar_registro_vlr_consignado'));
        }

      }

      $oArquivoPersistir->setProcessado('true');
      $oArquivoPersistir->setInstituicao(InstituicaoRepository::getInstituicaoSessao());
      ArquivoConsignetRepository::persist($oArquivoPersistir, 'true');

    }
    return;
  }
}
