<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
 *  junto com este programa; se nao, escreva para a Free Softwareb
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Tributario\Cadastro\Iptu\Recadastramento;

use ECidade\Tributario\Cadastro\Iptu\Recadastramento\Repository\Lote as LoteRepository;
use ECidade\Tributario\Cadastro\Iptu\Recadastramento\Repository\Construcao as ConstrucaoRepository;
use ECidade\Tributario\Integracao\Civitas\Model\Situacao;

class Processamento
{

    /**
     *  Nome da Importacao dos dados para recadastramento
     * @var [type]
     */
    private $nomeImportacao;

    private $oDataArquivo = null;

    private $aArquivosImportados = array();

    private $iCodigoSchema = null;

    private $aMatriculasImportadas = array();

    private $importacaoManual;

    const MATRICULA_INCLUIDA = 0;
    const MATRICULA_NOVA = 1;
    const MATRICULA_ATUALIZADA = 2;
    const MATRICULA_REJEITADA = 3;

    /**
     *
     * @param [type] $nomeImportacao [description]
     * @param bool $importacaoManual
     */
    function __construct($nomeImportacao, $importacaoManual = true) {
      $this->nomeImportacao   = $nomeImportacao;
      $this->importacaoManual = $importacaoManual;
    }

    /**
     * @return null
     */
    public function getDataArquivo() {

      return $this->oDataArquivo;
    }

    /**
     * @param null $oDataArquivo
     */
    public function setDataArquivo($oDataArquivo) {

      $this->oDataArquivo = $oDataArquivo;
    }

    /**
     * @return array
     */
    public function getArquivosImportados() {

      return $this->aArquivosImportados;
    }

    /**
     * @param array $aArquivosImportados
     */
    public function setArquivosImportados($aArquivosImportados) {

      $this->aArquivosImportados = $aArquivosImportados;
    }

    /**
     * @return null
     */
    public function getCodigoSchema() {

      return $this->iCodigoSchema;
    }

    /**
     * @param null $iCodigoSchema
     */
    public function setCodigoSchema($iCodigoSchema) {

      $this->iCodigoSchema = $iCodigoSchema;
    }



    public function processar() {

     /**
      * validar se existe schema
      *       -> nao tem = criar
      *       -> tem apenas atualiza os dados
      *       -> gravar na tabela o nome do schema novo
      */
     if (!$this->processarEstrutura()) {
       return ;
     }

    }

  /**
   *
   * Processa os dados da Esrutura no banco
   * @return bool [type] [description]
   * @throws \DBException
   * @throws \Exception
   */
    private function processarEstrutura() {

      /**
       * verificar se existe o esquema o nome informado
       */
      $sSqlVerificaSchema = "select j142_sequencial from atualizacaoiptuschema where j142_schema = '{$this->nomeImportacao}'";
      $rsSchema           = db_query($sSqlVerificaSchema);

      if (!$rsSchema) {
        throw new \Exception("Não foi possivel importar os arquivos!");
      }


      if (pg_num_rows($rsSchema) > 0) {

        $oDaoAtualizacaoiptuschemaarquivo = new \cl_atualizacaoiptuschemaarquivo();
        $this->iCodigoSchema = \db_utils::fieldsMemory($rsSchema,0)->j142_sequencial;

        foreach ($this->aArquivosImportados as $sArquivo) {

          $sWhere = " j143_arquivo = '{$sArquivo}' and j143_atualizacaoiptuschema = {$this->iCodigoSchema} ";
          $sSqlAtualizacao = $oDaoAtualizacaoiptuschemaarquivo->sql_query_file(null, 'j143_sequencial', null, $sWhere);
          $rsAtualizacao   = db_query($sSqlAtualizacao);

          if (!$rsAtualizacao) {
              throw new \DBException('Erro ao buscar arquivos importados.');
          }

          if (pg_num_rows($rsAtualizacao) > 0) {

            $iCodigoSchemaArquivo = \db_utils::fieldsMemory($rsAtualizacao,0)->j143_sequencial;
            $oDaoAtualizacaoiptuschemaarquivo->j143_sequencial            = $iCodigoSchemaArquivo;
            $oDaoAtualizacaoiptuschemaarquivo->j143_dataimportacao        = date("Y-m-d");
            $oDaoAtualizacaoiptuschemaarquivo->j143_arquivo               = $sArquivo;
            $oDaoAtualizacaoiptuschemaarquivo->j143_atualizacaoiptuschema = $this->iCodigoSchema;
            $oDaoAtualizacaoiptuschemaarquivo->alterar();

            if ($oDaoAtualizacaoiptuschemaarquivo->erro_status == '0') {
              throw new \DBException("Erro ao atualizar o arquivo.");
            }

            continue;
          }

          $oDaoAtualizacaoiptuschemaarquivo->j143_sequencial            = null;
          $oDaoAtualizacaoiptuschemaarquivo->j143_arquivo               = $sArquivo;
          $oDaoAtualizacaoiptuschemaarquivo->j143_dataimportacao        = date("Y-m-d");
          $oDaoAtualizacaoiptuschemaarquivo->j143_atualizacaoiptuschema = $this->iCodigoSchema;
          $oDaoAtualizacaoiptuschemaarquivo->incluir();

          if ($oDaoAtualizacaoiptuschemaarquivo->erro_status == '0') {
              throw new \DBException("Erro ao cadastrar nome do arquivo.");
          }

        }
        return true;
      }

      $rsNovoSchema = db_query("CREATE SCHEMA {$this->nomeImportacao};");

      if (!$rsNovoSchema) {
        throw new \Exception("Erro ao criar novo Schema");
      }

      $aTabelasParaDuplicar = json_decode(file_get_contents('config/configuracaoTabelasRecadastramentoIptu.json'));
      $this->processaTabelas($aTabelasParaDuplicar);


      $oDaoAtualizacaoiptuschema                   = new \cl_atualizacaoiptuschema();
      $oDaoAtualizacaoiptuschema->j142_schema      = $this->nomeImportacao;
      $oDaoAtualizacaoiptuschema->j142_dataarquivo = $this->oDataArquivo->getDate();
      $oDaoAtualizacaoiptuschema->incluir();

      if ($oDaoAtualizacaoiptuschema->erro_status == '0') {
        throw new \DBException("Erro ao cadastrar nome da atualização.");
      }
      $this->iCodigoSchema = $oDaoAtualizacaoiptuschema->j142_sequencial;
      $oDaoAtualizacaoiptuschemaarquivo = new \cl_atualizacaoiptuschemaarquivo();

      foreach ($this->aArquivosImportados as $sArquivo) {

        $oDaoAtualizacaoiptuschemaarquivo->j143_sequencial            = null;
        $oDaoAtualizacaoiptuschemaarquivo->j143_arquivo               = $sArquivo;
        $oDaoAtualizacaoiptuschemaarquivo->j143_dataimportacao        = date("Y-m-d");
        $oDaoAtualizacaoiptuschemaarquivo->j143_atualizacaoiptuschema = $this->iCodigoSchema;
        $oDaoAtualizacaoiptuschemaarquivo->incluir();

        if ($oDaoAtualizacaoiptuschemaarquivo->erro_status == '0') {
          throw new \DBException("Erro ao cadastrar nome do arquivo.");
        }

      }

      return true;
    }

  /**
   * Processa os dados das tabelas no esquema criado
   * @param  [type] $aTabelasParaDuplicar [description]
   * @throws \Exception
   */
    private function processaTabelas( $aTabelasParaDuplicar) {

       foreach ($aTabelasParaDuplicar as $tabela) {

          $sSqlCriaTabela = " CREATE TABLE {$this->nomeImportacao}.{$tabela->nome} AS SELECT * from {$tabela->schema}.{$tabela->nome} ";
          if (!$tabela->incluir_dados) {
            $sSqlCriaTabela .= " limit 0";
          }
          $rsCriarTabela = db_query($sSqlCriaTabela);
          if (!$rsCriarTabela) {
             throw new \Exception("Erro ao processar dados da tabela {$tabela->schema}.{$tabela->nome}");
          }

          if ($tabela->sequence != '') {

             $sSqlultimoValorSequence = "select last_value + 1 as valor from {$tabela->schema}.{$tabela->sequence}";
             $rsULtimoValorSequence = db_query($sSqlultimoValorSequence);
             if (!$rsULtimoValorSequence) {
                throw new \Exception("Erro ao processar valores da sequence da tabela {$tabela->schema}.{$tabela->nome} ");
             }
             $iValorSequence     = \db_utils::fieldsMemory($rsULtimoValorSequence, 0)->valor;
             $sSqlCriarSequence = "create sequence {$this->nomeImportacao}.{$tabela->sequence} START {$iValorSequence}";
             $rsCriarSequence   = db_query($sSqlCriarSequence);
             if (!$rsCriarSequence) {
                throw new \Exception("Erro ao processar sequence da tabela {$tabela->schema}.{$tabela->nome} ");
             }
          }

      if ( isset($tabela->index) && is_array($tabela->index) ) {

        foreach ( $tabela->index as $sCampo) {

          $rs = db_query("create index {$this->nomeImportacao}_{$tabela->nome}_{$sCampo} on {$this->nomeImportacao}.{$tabela->nome}({$sCampo})");
          if ( !$rs ) {
            throw new \Exception("Não foi possível criar index no schema '{$this->nomeImportacao}' na tabela '{$tabela->nome}' para o campo '{$sCampo}'.");
          }
        }
      }
       }
    }

    public function calcularIptu( $aMatriculasImportadas, $iAno) {

      if ( empty($this->nomeImportacao) ) {
        throw new \ParameterException("Nome da importação não informada.");
      }

      if ( empty($this->iCodigoSchema) ) {
        throw new \ParameterException("Código da importação não informada.");
      }

      if ( empty($iAno) ) {
        throw new \ParameterException("Ano para cálculo de IPTU não informado.");
      }

      $this->aMatriculasImportadas = $aMatriculasImportadas;

      db_query("set search_path=public,{$this->nomeImportacao}");


      foreach ($this->aMatriculasImportadas as $oMatricula) {

        $sSqlCalculo = "select fc_calculoiptu({$oMatricula->iMatricula}::integer,{$iAno}::integer,true::boolean,false::boolean,false::boolean,false::boolean,false::boolean,array['0','0','0'])";
        $rsCalculo   = db_query($sSqlCalculo);

        if ( !$rsCalculo ) {
          $excecao = new \DBException("Erro ao executar o cálculo de IPTU da matrícula {$oMatricula->iMatricula}.");
          Situacao::lancarExcecao($excecao, $this->importacaoManual);
        }
      }
      db_query("select fc_set_pg_search_path();");

    }

    public function incluirMatriculasImportadas() {

      $oDaoAtualizacaoiptuschemamatricula = new \cl_atualizacaoiptuschemamatricula();

      foreach ($this->aMatriculasImportadas as $oMatricula) {

        $oDaoAtualizacaoiptuschemamatricula->j144_sequencial            = null;
        $oDaoAtualizacaoiptuschemamatricula->j144_matricula             = $oMatricula->iMatricula;
        $oDaoAtualizacaoiptuschemamatricula->j144_atualizacaoiptuschema = $this->iCodigoSchema;
        $oDaoAtualizacaoiptuschemamatricula->j144_situacao              = $oMatricula->iStatus;

        $sWhere = "j144_matricula = {$oMatricula->iMatricula} and  j144_atualizacaoiptuschema = {$this->iCodigoSchema} ";
        $sSqlAtualizacaoMatricula = $oDaoAtualizacaoiptuschemamatricula->sql_query_file(null, 'j144_sequencial', null, $sWhere);
        $rsAtualizacaoMatricula = db_query($sSqlAtualizacaoMatricula);

        if (!$rsAtualizacaoMatricula) {
          $excecao = new \DBException("Erro ao buscar matrícula importada {$oMatricula->iMatricula}.");
          Situacao::lancarExcecao($excecao, $this->importacaoManual);
          continue;
        }

        if ( pg_num_rows($rsAtualizacaoMatricula) > 0) {

          $iCodigoImportacaoMatricula = \db_utils::fieldsMemory($rsAtualizacaoMatricula, 0)->j144_sequencial;
          $oDaoAtualizacaoiptuschemamatricula->alterar($iCodigoImportacaoMatricula);
        } else {
          $oDaoAtualizacaoiptuschemamatricula->incluir(null);
        }

        if ($oDaoAtualizacaoiptuschemamatricula->erro_status == '0') {
          $excecao = new \DBException("Erro ao atualizar a situaçao da matrícula {$oMatricula->iMatricula}.");
          Situacao::lancarExcecao($excecao, $this->importacaoManual);
          continue;
        }
      }
    }

    /**
     * @param $iCodigoMatricula
     * @throws \DBException
     */
    public function rejeitarMatricula($iCodigoMatricula, $iAno) {

      /**
       * Verificar se a matricula tem outros lotes, e um deles é novo.
       * Caso verdadeiro, devemos recalcular iiptu de tddas as matriculas
       */
      $lMatriculaNova = $this->isMatriculaNova($iCodigoMatricula);

      if ( $lMatriculaNova ) {

        db_query("set search_path=public,{$this->nomeImportacao}");

        $oDaoIptuBase = new \cl_iptubase();
        $oMatricula   = $oDaoIptuBase->findBydId( $iCodigoMatricula );

        $oDaoAtualizacaoIptuMatriculas = new \cl_atualizacaoiptuschemamatricula();
        $aMatriculasNoLote             = $oDaoAtualizacaoIptuMatriculas->matriculaNoLoteDaImportacao( $this->nomeImportacao, $oMatricula->j01_idbql);
        $aMatriculas                   = array_map( function( $oMatriculasNoLote){

          $oMatricula             = new \stdClass();
          $oMatricula->iMatricula = $oMatriculasNoLote->matricula;
          return $oMatricula;
        }, $aMatriculasNoLote);

        /**
         * cadastramos a matricula rejeitada como baixada
         */
        $oDaoIptuBase->j01_matric = $oMatricula->j01_matric;
        $oDaoIptuBase->j01_baixa = date('Y-m-d');
        $oDaoIptuBase->alterar($oMatricula->j01_matric);

        if ( $oDaoIptuBase->erro_status == '0' ) {
          throw new \DBException("Erro ao realizar a baixa da matrícula: " . $oMatricula->j01_matric);
        }

        $this->calcularIptu( $aMatriculas, $iAno );
        db_query("select fc_set_pg_search_path();");
      }

      $this->atualizaSituacaoMatricula($iCodigoMatricula, self::MATRICULA_REJEITADA);
    }

  /**
   *
   * Atualiza os dados da matricula
   * Caso a matricula for nova, o metodo ira retornar o codigo incluido para a matricula;
   * @param $iCodigoMatricula
   * @return int
   */
    public function atualizarMatricula($iCodigoMatricula) {

      //Schema com os dados atualizados para recadastramento
      $lMatriculaNova = $this->isMatriculaNova($iCodigoMatricula);

      db_query("set search_path=public,{$this->nomeImportacao}");
      $lote        = LoteRepository::getLotePorMatricula($iCodigoMatricula);
      $oConstrucao = ConstrucaoRepository::getConstrucaoPorMatricula($iCodigoMatricula);

      db_query("select fc_set_pg_search_path();");
      $lote->atualizar();

      if (!empty($oConstrucao)) {

        if ($lMatriculaNova) {
          $oConstrucao->setMatricula('');
        }
        $oConstrucao->salvar();
      }

      /**
       * Atualizar situacoa da matricula
       */
      $this->atualizaSituacaoMatricula($iCodigoMatricula, self::MATRICULA_ATUALIZADA);
      if ($lMatriculaNova) {
        return $oConstrucao->getMatricula();
      }
    }

  /**
   * Atualiza a situação da matricula
   *  2 - Atualizada
   *  3- Rejeitada
   * @param $iCodigoMatricula
   * @param $iSituacao
   * @throws \DBException
   */
    private function atualizaSituacaoMatricula ($iCodigoMatricula, $iSituacao){

      $oDaoAtualizacaoiptuschemamatricula                 = new \cl_atualizacaoiptuschemamatricula();
      $sWhere = "j144_matricula = {$iCodigoMatricula} and  j144_atualizacaoiptuschema = {$this->iCodigoSchema}";
      $sSqlDadoMatricula = $oDaoAtualizacaoiptuschemamatricula->sql_query_file(null, 'j144_sequencial', null, $sWhere);
      $rsDadosMatricula = db_query($sSqlDadoMatricula);

      if(!$rsDadosMatricula && pg_num_rows($rsDadosMatricula) == 0) {
        throw  new \DBException("Não foi possível verificar registros para recadstramento da matrícula {$iCodigoMatricula}. Verifique se foi realizada uma importação de arquivo de recadastramento para matrícula.");
      }

      $iCodigoSequencialMatricula = \db_utils::fieldsMemory($rsDadosMatricula,0)->j144_sequencial;
      $oDaoAtualizacaoiptuschemamatricula->j144_matricula             = $iCodigoMatricula;
      $oDaoAtualizacaoiptuschemamatricula->j144_situacao              = $iSituacao;
      $oDaoAtualizacaoiptuschemamatricula->j144_sequencial            = $iCodigoSequencialMatricula;
      $oDaoAtualizacaoiptuschemamatricula->j144_atualizacaoiptuschema = $this->iCodigoSchema;
      $oDaoAtualizacaoiptuschemamatricula->alterar($iCodigoSequencialMatricula);

      $sSituacao = 'Atualizar';

      if($iSituacao == 3) {
        $sSituacao = 'Rejeitar';
      }

      if ($oDaoAtualizacaoiptuschemamatricula->erro_status == 0) {
        throw new \DBException("Erro ao {$sSituacao} os dados da matrícula.\n{$oDaoAtualizacaoiptuschemamatricula->erro_msg}");
      }
    }

  /**
   * @param $codigoMatricula
   * @return bool
   * @throws \DBException
   */
    private function isMatriculaNova($codigoMatricula) {

      $oDaoAtualizacaoMatricula = new \cl_atualizacaoiptuschemamatricula();
      $sWhereMatricula          = "j144_matricula = {$codigoMatricula} and j144_atualizacaoiptuschema = {$this->iCodigoSchema} and j144_situacao = 1";
      $sSqlMatricula            = $oDaoAtualizacaoMatricula->sql_query_file(null, 'j144_sequencial', null, $sWhereMatricula);
      $rsMatriculaNova          = db_query($sSqlMatricula);
      if (!$rsMatriculaNova) {
        throw new \DBException("Erro ao verificar situação da  matrícula {$codigoMatricula}.");
      }
      return pg_num_rows($rsMatriculaNova) > 0;
    }

}

