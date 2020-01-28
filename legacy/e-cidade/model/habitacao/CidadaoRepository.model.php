<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
   * Classe repository para classes Cidadao
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package Habitacao
   */
  class CidadaoRepository {

    /**
     * Collection de cidadoes
     * @var array
     */
    private $aCidadoes = array();

    /**
     * Instancia da classe
     * @var CidadaoRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * Retorno uma instancia do cidadao pelo Codigo
     * @param integer $iCodigo Codigo do Cidadao
     * @return Cidadao
     */
    public static function getCidadaoByCodigo($iCodigoCidadao) {

      if (!array_key_exists($iCodigoCidadao, CidadaoRepository::getInstance()->aCidadoes)) {
        CidadaoRepository::getInstance()->aCidadoes[$iCodigoCidadao] = new Cidadao($iCodigoCidadao);
      }
      return CidadaoRepository::getInstance()->aCidadoes[$iCodigoCidadao];
    }

    /**
     * Retorna a instancia da classe
     * @return CidadaoRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {

        self::$oInstance = new CidadaoRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um cidadao dao repositorio
     * @param Cidadao $oCidadao Instancia do cidadao
     * @return boolean
     */
    public static function adicionarCidadao(Cidadao $oCidadao) {

      if(!array_key_exists($oCidadao->getCodigo(), CidadaoRepository::getInstance()->aCidadoes)) {
        CidadaoRepository::getInstance()->aCidadoes[$oCidadao->getCodigo()] = $oCidadao;
      }
      return true;
    }

    /**
     * Remove o cidadao passado como parametro do repository
     * @param Cidadao $oCidadao
     * @return boolean
     */
    public static function removerCidadao(Cidadao $oCidadao) {
       /**
        *
        */
      if (array_key_exists($oCidadao->getCodigo(), CidadaoRepository::getInstance()->aCidadoes)) {
        unset(CidadaoRepository::getInstance()->aCidadoes[$oCidadao->getCodigo()]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalCidadoes() {
      return count(CidadaoRepository::getInstance()->aCidadoes);
    }

    /**
     * Retorna uma coleção de cidadãos por nome, data de nascimento e nome da mae
     *
     * @param string $sNomeCidadao nome do cidadao
     * @param DBDate $oDataNascimento data de nascimento
     * @param string $sNomeMae nome da mae
     * @return Cidadao[] Coleção de Cidadãos
     */
    public static function getCidadaoPorNomeDataNascimento($sNomeCidadao, DBDate $oDataNascimento, $sNomeMae) {

      $oDaoCidadao   = new cl_cidadao();
      $sWhere        = "filho.ov02_nome                = '{$sNomeCidadao}'";
      $sWhere       .= " and filho.ov02_datanascimento = '".$oDataNascimento->convertTo(DBDate::DATA_EN)."'";
      $sWhere       .= " and pais.ov02_nome            = '{$sNomeMae}'";
      $sWhere       .= " and ov29_tipofamiliar         = 4";
      $sSqlFilicacao = $oDaoCidadao->sql_query_filiacao(null, null, "filho.ov02_sequencial", null, $sWhere);
      $rsFiliacao    = $oDaoCidadao->sql_record($sSqlFilicacao);
      $aCidadaos     = array();

      if ($rsFiliacao && $oDaoCidadao->numrows > 0) {

        for ($iLinhas = 0; $iLinhas < $oDaoCidadao->numrows; $iLinhas++) {

          $iCodigoCidadao = db_utils::fieldsMemory($rsFiliacao, $iLinhas)->ov02_sequencial;
          array_push($aCidadaos, CidadaoRepository::getCidadaoByCodigo($iCodigoCidadao));
        }
      }

      return $aCidadaos;
    }
    
    /**
     * Retorna um cidadao pelo aluno que esta vinculado a ele
     * 
     * @param integer $iCodigoAluno - Código do aluno vinculado
     * @return Cidadao
     */
    public static function getCidadaoPeloCodigoAluno($iCodigoAluno) {
      
      $oCidadao         = null;
      $oDaoAlunoCidadao = new cl_alunocidadao();
      $sWhere           = "ed330_aluno = {$iCodigoAluno}";
      $sSqlAlunoCidadao = $oDaoAlunoCidadao->sql_query_file(null, "ed330_cidadao", "ed330_cidadao asc", $sWhere);
      $rsAlunoCidadao   = $oDaoAlunoCidadao->sql_record($sSqlAlunoCidadao);
      
      if ($oDaoAlunoCidadao->numrows > 0) {
        $oCidadao = CidadaoRepository::getCidadaoByCodigo(db_utils::fieldsMemory($rsAlunoCidadao, 0)->ed330_cidadao);
      }
      
      return $oCidadao;
    }
  }
?>