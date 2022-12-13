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
 * Repositório dos arquivos do e-Consig
 *
 * @abstract 
 * @author     Renan Melo  <renan@dbseller.com.br>
 * @author     Rafael Nery <rafael.nery@dbseller.com.br>
 * @package    Pessoal
 * @subpackage Arquivos
 */
abstract class ArquivoEConsigRepository {
  
 const MENSAGEM = 'recursoshumanos.pessoal.ArquivoEConsigRepository.';
 
 /**
  * Lista de instâncias de arquivos
  * 
  * @var ArquvivoEConsig[]
  */
 private static $aArquivos = array();

 /**
  * Adiciona uma instancia do objeto na memória
  * 
  * @param ArquivoEConsig $oArquivo
  */
 public static function add( ArquivoEConsig $oArquivo ) {
   self::$aArquivos[$oArquivo->getCodigo()] =  $oArquivo;
 }

 /**
  * Remove a instancia da memoria
  * 
  * @param  ArquivoEConsig $oArquivo 
  * @return boolean
  */
 public static function remove( ArquivoEConsig $oArquivo ) {
    unset(self::$aArquivos[$oArquivo->getCodigo()]);
 }
 
 /**
  * Retorna uma instância unica do objeto pelo código sequencial
  * 
  * @param  Integer $iCodigo Código Indentificador do arquivo
  * @return ArquivoEConsig 
  */
 public static function getByCodigo( $iCodigo ) {

   $oDaoEconsigMovimento = new cl_econsigmovimento(); 
   $sSqlEconsigMovimento = $oDaoEconsigMovimento->sql_query_file($iCodigo, "*");
   $rsEconsigMovimento   = db_query($sSqlEconsigMovimento);

   if (!$rsEconsigMovimento) {
     throw new DBException(_M(self::MENSAGEM . 'erro_econsigmovimento'));
   }

   if (pg_num_rows($rsEconsigMovimento) == 0) {
     throw new BusinessException(_M(self::MENSAGEM . 'nenhum_registro_econsgmovimento'));
   }

   $oEconsigMoviemento   = db_utils::fieldsMemory($rsEconsigMovimento, 0);

   return ArquivoEConsigRepository::make($oEconsigMoviemento);
 }

 /**
  * Verifica se existe algum arquivo de movimentação do E-Consig pela competência 
  * @param  DBCompetencia $oCompetencia 
  * @return boolean                     
  */
 public static function hasArquivoCompetencia (DBCompetencia $oCompetencia, Instituicao $oInstituicao) {

   $oDaoEconsigMovimento   = new cl_econsigmovimento();
   $sWhereEconsigMovimento = "rh133_ano = {$oCompetencia->getAno()} and rh133_mes = {$oCompetencia->getMes()} and rh133_instit = {$oInstituicao->getCodigo()}";
   $sSqlEconsigMovimento   = $oDaoEconsigMovimento->sql_query_file(null, 'rh133_sequencial', null, $sWhereEconsigMovimento);
   $rsEconsigMovimento     = db_query($sSqlEconsigMovimento);

   if (!$rsEconsigMovimento) {
     throw new DBException(_M(self::MENSAGEM . 'erro_econsigmovimento'));
   }

   if (pg_num_rows($rsEconsigMovimento) == 0) {
      return false;
   }

   return true;
 }

 /**
  * Monta o Objeto ArquivoEconsig a partir do objeto informado como parâmetro
  * 
  * @param  object $oEconsigMoviemento
  * @return ArquivoEConsig
  */
 public static function make($oEconsigMoviemento){

   $oCompetencia    = new DBCompetencia($oEconsigMoviemento->rh133_ano, $oEconsigMoviemento->rh133_mes); 
   $oInstituicao    = InstituicaoRepository::getInstituicaoByCodigo($oEconsigMoviemento->rh133_instit);
   $oArquivoEconsig = new ArquivoEConsig();

   $oArquivoEconsig->setCodigo($oEconsigMoviemento->rh133_sequencial);
   $oArquivoEconsig->setNome($oEconsigMoviemento->rh133_nomearquivo);
   $oArquivoEconsig->setCompetencia($oCompetencia);
   $oArquivoEconsig->setInstituicao($oInstituicao);
   $oArquivoEconsig->setRelatorio($oEconsigMoviemento->rh133_relatorio);
   
   return $oArquivoEconsig;
 }

 /**
  * Salva a instancia do arquivo no "banco de dados"
  * @param  ArquivoEConsig $oArquivo
  * @return void
  */
 public static function persist( ArquivoEConsig $oArquivo ) {
   
   $oDaoEconsigMovimento                    = new cl_econsigmovimento();
   $oDaoEconsigMovimentoServidor            = new cl_econsigmovimentoservidor();
   $oDaoEconsigMovimentoRubrica             = new cl_econsigmovimentoservidorrubrica();

   $oDaoEconsigMovimento->rh133_nomearquivo = $oArquivo->getNome();
   $oDaoEconsigMovimento->rh133_instit      = $oArquivo->getInstituicao()->getSequencial();
   $oDaoEconsigMovimento->rh133_ano         = $oArquivo->getCompetencia()->getAno();
   $oDaoEconsigMovimento->rh133_mes         = $oArquivo->getCompetencia()->getMes();
   $oDaoEconsigMovimento->rh133_relatorio   = $oArquivo->getRelatorio();

   if ($oArquivo->getCodigo()) {

     $oDaoEconsigMovimento->rh133_sequencial = $oArquivo->getCodigo();
     $oDaoEconsigMovimento->alterar($oArquivo->getCodigo());
   } else {
     $oDaoEconsigMovimento->incluir(null);
   }

   if ( $oDaoEconsigMovimento->erro_status == "0") {
     throw new DBException(_M(self::MENSAGEM . "erro_ao_persistir_dados"));
   }

   $oArquivo->setCodigo($oDaoEconsigMovimento->rh133_sequencial);
   self::add( $oArquivo );
   
   /**
    * Remove as rubricas para sanar as dependencias do servidor
    */
   $oDaoEconsigMovimentoRubrica->excluir(null, "rh135_econsigmovimentoservidor in (select rh134_sequencial from econsigmovimentoservidor where rh134_econsigmovimento = {$oArquivo->getCodigo()})");
   
   if ($oDaoEconsigMovimentoRubrica->erro_status == "0") {
     throw new DBException(_M(self::MENSAGEM . 'erro_excluir_movimentoservidorrubrica'));
   }

   $oDaoEconsigMovimentoServidor->excluir(null, "rh134_econsigmovimento = {$oArquivo->getCodigo()}");
   
   /**
    * Remove os servidores sanando as dependencias do arquivo
    */
   if ($oDaoEconsigMovimentoServidor->erro_status == "0") {
     throw new DBException(_M(self::MENSAGEM . 'erro_excluir_movimentoservidor'));
   }
   
   $aRegistros           = $oArquivo->getRegistros();
   $iQuantidadeRegistros = count($aRegistros);
   $aServidores          = array();

   /**
    * Pré-processa os dados dos registros passados para melhor adaptação da estrutura
    */
   for ($iRegistro = 0; $iRegistro  < $iQuantidadeRegistros; $iRegistro++ ) { 

     $oRegistro = $aRegistros[$iRegistro];

    if ( $oRegistro->getMotivo() > 0) {      
      $aServidores[$oRegistro->getServidor()->getMatricula()]['aRubricasInconsistentes'][$oRegistro->getRubrica()->getCodigo()] = $oRegistro->getRubrica()->getCodigo();
    }

     $aServidores[$oRegistro->getServidor()->getMatricula()]['aRegistros'][] = $oRegistro;
   }

   /**
    * Percorre os servidores da matriz criada e insere na tabela, para que, com os códigos gerados sejam incluidas as 
    * rubricas.
    */
   while ( list($iMatricula, $aRegistros) = each($aServidores) ) {

    foreach ($aRegistros['aRegistros'] as $oItemRegistro) {

      $iMotivo = $oItemRegistro->getMotivo();

      $oDaoEconsigMovimentoServidor->rh134_sequencial       = null;
      $oDaoEconsigMovimentoServidor->rh134_regist           = (!empty($iMatricula)) ? $iMatricula : "0";
      $oDaoEconsigMovimentoServidor->rh134_nome             = $oItemRegistro->getNome();
      $oDaoEconsigMovimentoServidor->rh134_econsigmovimento = $oArquivo->getCodigo();
      $oDaoEconsigMovimentoServidor->rh134_econsigmotivo    = (empty($iMotivo) ? 'null' : $iMotivo);

      if( isset($aRegistros['aRubricasInconsistentes']) && is_array($aRegistros['aRubricasInconsistentes']) 
          && in_array($oItemRegistro->getRubrica()->getCodigo(), $aRegistros['aRubricasInconsistentes']) ){

        $oDaoEconsigMovimentoServidor->incluir(null);
    
        if ($oDaoEconsigMovimentoServidor->erro_status == "0") {
          throw new DBException(_M(self::MENSAGEM."erro_incluir_servidor"));
        }
        
        $aRegistros['aRubricasInconsistentes'][$oItemRegistro->getRubrica()->getCodigo()] = $oDaoEconsigMovimentoServidor->rh134_sequencial;

      } elseif( !isset($lSalvouMovimentoServidorConsistente) || $lSalvouMovimentoServidorConsistente == false) {
        $lSalvouMovimentoServidorConsistente = true;
        $oDaoEconsigMovimentoServidor->incluir(null);
        $iSequencialServidorSemInconsitencias = $oDaoEconsigMovimentoServidor->rh134_sequencial;
      }
    }
    $lSalvouMovimentoServidorConsistente = false;


    /**
     * Percorre os dados das rubricas persistindos
     */
     for ($iRegistroServidor = 0; $iRegistroServidor < count($aRegistros['aRegistros']); $iRegistroServidor++) {
      $oRegistro                                                   = $aRegistros['aRegistros'][$iRegistroServidor];
      if ($oRegistro->getMotivo() == 0) {
        $iSequencialServidor = $iSequencialServidorSemInconsitencias;
      }else{
        $iSequencialServidor = $aRegistros['aRubricasInconsistentes'][$oRegistro->getRubrica()->getCodigo()];
      }

      $sRubrica                                                    = $oRegistro->getRubrica()->getCodigo();
      $oDaoEconsigMovimentoRubrica->rh135_sequencial               = null;
      $oDaoEconsigMovimentoRubrica->rh135_econsigmovimentoservidor = $iSequencialServidor;
      $oDaoEconsigMovimentoRubrica->rh135_rubrica                  = $sRubrica;
      $oDaoEconsigMovimentoRubrica->rh135_instit                   = $oArquivo->getInstituicao()->getSequencial();
      $oDaoEconsigMovimentoRubrica->rh135_valor                    = $oRegistro->getValor();
      $oDaoEconsigMovimentoRubrica->incluir(null);

      if ($oDaoEconsigMovimentoRubrica->erro_status == "0") {
       throw new DBException(_M(self::MENSAGEM."erro_incluir_rubrica"));
      }
    }

   }
 } 

 /**
  * Remove um registro do banco
  * @param  ArquivoEConsig $oArquivo
  * @return void
  */
 public static function delete( ArquivoEConsig $oArquivo ) {
    //@TODO - Not Yet
 }

  /**
   * Retorna o último ArquivoEconsig da competência
   * 
   * @param Instituicao $oInstituicao
   * @param DBCompetencia $oCompetencia
   * @return ArquivoEConsig
   * @throws DBException
   */
  public static function getUltimoArquivo(Instituicao $oInstituicao, DBCompetencia $oCompetencia) {
    
    $sWhere  = "rh133_ano = {$oCompetencia->getAno()} AND ";
    $sWhere .= "rh133_mes = {$oCompetencia->getMes()} AND ";
    $sWhere .= "rh133_instit = {$oInstituicao->getCodigo()}";
    $sOrder  = "rh133_sequencial DESC LIMIT 1"; 
    
    $oDaoEconsigMovimento = new cl_econsigmovimento(); 
    $sSqlEconsigMovimento = $oDaoEconsigMovimento->sql_query_file(null, "*", $sOrder, $sWhere);
    $rsEconsigMovimento   = db_query($sSqlEconsigMovimento);
  
    if (!$rsEconsigMovimento) {
      throw new DBException(_M(self::MENSAGEM . 'erro_econsigmovimento'));
    }
  
    if (pg_num_rows($rsEconsigMovimento) == 0) {
      return new ArquivoEConsig();
    }
  
    $oEconsigMoviemento   = db_utils::fieldsMemory($rsEconsigMovimento, 0);
    return ArquivoEConsigRepository::make($oEconsigMoviemento);
  }  
}
