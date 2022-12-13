<?php
/*
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));


db_app::import("configuracao.inconsistencia.*");
db_app::import("exceptions.*");


$oJson               = new services_json();
$oParam              = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMessage  = '';

switch ($oParam->sExec) {

  case 'pesquisar' :

    $oDaoDb_sysarquivo = db_utils::getDao('db_sysarquivo');
    $sSqlDb_sysarquivo = $oDaoDb_sysarquivo->sql_query_file($oParam->iCodigoTabela);
    $rsDb_sysarquivo   = $oDaoDb_sysarquivo->sql_record($sSqlDb_sysarquivo);

    if ($oDaoDb_sysarquivo->numrows == 0) {
      throw new Exception('Tabela não encontrada no sistema.');
    }

    $oDb_sysarquivo    = db_utils::fieldsMemory($rsDb_sysarquivo, 0);

    $sWhere = '';
    $sAnd   = '';
    $sOrder = null;

    if(isset($oParam->sCampoOrdem) && isset ($oParam->sOrdem)){

    	$sOrder = $oParam->sCampoOrdem.' '.$oParam->sOrdem;
    }

    foreach ($oParam->aTabelas as $oTabela) {

      $iValores = count($oTabela->aValores);

      if ($iValores == '1') {

        if ($oTabela->sTipoCampo == 'integer') {

          $sWhere .= "{$sAnd} {$oTabela->sCampo} = '{$oTabela->aValores[0]}'";

        } else if ($oTabela->sTipoCampo == 'date') {

          $dData = db_formatar($oTabela->aValores[0], 'xxxv');
          $sWhere .= "{$sAnd} {$oTabela->sCampo} = '{$dData}'";

        } else if ($oTabela->sTipoCampo == 'boolean') {

          $sWhere .= "{$sAnd} {$oTabela->sCampo} is {$oTabela->aValores[0]}";

        } else {

          $sValor  = utf8_decode($oTabela->aValores[0]);
          $sWhere .= "{$sAnd} {$oTabela->sCampo} ilike '{$sValor}'";
        }


      } else {

        $iValor1 = (int)$oTabela->aValores[0];
        $iValor2 = (int)$oTabela->aValores[1];
        $sWhere .= "{$sAnd} {$oTabela->sCampo} between '{$iValor1}' and '{$iValor2}'";

      }

      $sAnd = " AND ";
    }

    $oDaoTabela = db_utils::getDao($oDb_sysarquivo->nomearq);
    $sSqlTabela = $oDaoTabela->sql_query_file(null,
                                              "*",
                                              $sOrder,
                                              $sWhere
                                              );

    $rsTabela = $oDaoTabela->sql_record($sSqlTabela);

    $oRetorno->aDados = db_utils::getCollectionByRecord($rsTabela, false, false, true);

  break;

  case 'consultaModulos':

    try {

      $oDaoSysmodulos  = db_utils::getDao('db_sysmodulo');


      $sCamposModulo      = 'codmod, nomemod';
      $sOrdemModulo       = 'nomemod asc';
      $sTabelasPermitidas = implode( ', ', InconsistenciaDados::getTabelasPermitidas() );
      $sWhere             = "exists ( select 1
                                        from db_sysarqmod
                                       where db_sysarqmod.codmod = db_sysmodulo.codmod
                                         and db_sysarqmod.codarq in ($sTabelasPermitidas) ) ";
      $sSqlModulos        = $oDaoSysmodulos->sql_query_file( null,
                                                             $sCamposModulo,
                                                             $sOrdemModulo,
                                                             $sWhere );
      $rsModulos          = $oDaoSysmodulos->sql_record($sSqlModulos);

      if ($oDaoSysmodulos->numrows == 0) {
        throw new BusinessException('Nenhum módulo no sistema');
      }

      $aModulos        = db_utils::getCollectionByRecord($rsModulos);
      $aModulosRetorno = array();

      $oModuloRetorno                = new stdClass();
      $oModuloRetorno->iCodigoModulo = '';
      $oModuloRetorno->sNomeModulo   = 'Selecione';
      $aModulosRetorno[]             = $oModuloRetorno;

      foreach ($aModulos as $oModulo) {

        $oModuloRetorno = new stdClass();

        $oModuloRetorno->iCodigoModulo =         $oModulo->codmod;
        $oModuloRetorno->sNomeModulo   = ucfirst($oModulo->nomemod);

        $aModulosRetorno[] = $oModuloRetorno;
      }

      $oRetorno->aModulos = $aModulosRetorno;

    } catch (BusinessException $oBusinessException) {

      $oRetorno->sMessage = $oBusinessException->getMessage();
      $oRetorno->iStatus  = 2;
    }

    break;

  /**
   * Salva ou altera um lançamento
   */
  case "consultaTabelas":

    try {

      $oDaoDb_sysarquivo = db_utils::getDao('db_sysarquivo');
      $sSqlTabelas       = $oDaoDb_sysarquivo->sql_query_buscaTabelaPorModulo($oParam->iCodigoModulo);
      $rsTabelas         = $oDaoDb_sysarquivo->sql_record($sSqlTabelas);

      $aTabelasRetorno   = db_utils::getCollectionByRecord($rsTabelas, false, false, false);

      $aTabelas          = array();

      foreach ($aTabelasRetorno as $oTabelaRetorno) {


        $oTabela = new stdClass();
        $oTabela->iCodigoTabela = $oTabelaRetorno->codarq;
        $oTabela->sNomeTabela   = $oTabelaRetorno->nomearq;

        $aTabelas[] = $oTabela;

      }

      $oRetorno->aTabelas = $aTabelas;

    } catch (Exception $eErro) {

      $oRetorno->sMessage = url_encode($eErro->getMessage());
      $oRetorno->iStatus  = 2;
    }

    break;

  case 'consultaCampos' :

    $oDaoDb_sysarqcamp = db_utils::getDao('db_sysarqcamp');

    $sCampos    = 'db_syscampo.codcam,
                   exists(select 1
                            from db_sysprikey
                           where db_sysprikey.codarq = db_sysarqcamp.codarq
                             and db_sysprikey.codcam = db_sysarqcamp.codcam
                   ) as primary_key,
                   db_syscampo.nomecam, db_syscampo.conteudo, db_syscampo.tamanho';
    $sSqlCampos = $oDaoDb_sysarqcamp->sql_query(null,
                                                null,
                                                null,
                                                $sCampos,
                                                'seqarq',
                                                "db_sysarqcamp.codarq = {$oParam->iCodigoTabela}"
                                               );

    $rsCampos   = $oDaoDb_sysarqcamp->sql_record($sSqlCampos);

    $aCamposRetorno = db_utils::getCollectionByRecord($rsCampos);
    $aCampos        = array();

    foreach ($aCamposRetorno as $oCampoRetorno) {

      $sTipoCampo = '';

      if (strpos(trim($oCampoRetorno->conteudo), 'int')     !== false ||
          strpos(trim($oCampoRetorno->conteudo), 'numeric') !== false ||
          strpos(trim($oCampoRetorno->conteudo), 'float')   !== false) {

        $sTipoCampo = 'integer';

      } else if (strpos(trim($oCampoRetorno->conteudo), 'char') !== false ||
                 strpos(trim($oCampoRetorno->conteudo), 'text') !== false) {

        $sTipoCampo = 'char';

      } else if (strpos(trim($oCampoRetorno->conteudo), 'bool') !== false) {

        $sTipoCampo = 'boolean';

      } else if (strpos(trim($oCampoRetorno->conteudo), 'date') !== false) {

        $sTipoCampo = 'date';

      } else if (strpos(trim($oCampoRetorno->conteudo), 'oid') !== false) {

        $sTipoCampo = 'oid';

      }

      $oCampo                  = new stdClass();
      $oCampo->iCodigoCampo    = trim($oCampoRetorno->codcam);
      $oCampo->sNomeCampo      = trim($oCampoRetorno->nomecam);
      $oCampo->sTipoCampoBanco = trim($oCampoRetorno->conteudo);
      $oCampo->sTipoCampo      = trim($sTipoCampo);
      $oCampo->iTamanhoCampo   = trim($oCampoRetorno->tamanho);
      $oCampo->lPrimaryKey     = $oCampoRetorno->primary_key == 't' ? true : false;
      $aCampos[]               = $oCampo;
    }

    $oRetorno->aCampos = $aCampos;

  break;

  case 'consultaInconsistencia' :

    $oDaoSysArquivo = db_utils::getDao('db_sysarquivo');
    $sSqlSysArquivo = $oDaoSysArquivo->sql_query_buscaCamposPorTabela($oParam->iCodigoTabela);
    $rsSysArquivo   = $oDaoSysArquivo->sql_record($sSqlSysArquivo);

    $aDadosTabela = db_utils::getCollectionByRecord($rsSysArquivo);

    foreach ($aDadosTabela as $oDadosTabela) {

      $sNomeTabela = $oDadosTabela->nometabela;

      //Campos da tabela
      if ($oDadosTabela->campo_pk == 't') {
        $sCampoPk = $oDadosTabela->nomecampo;
      }
      $aCamposTabela[] =  $oDadosTabela->nomecampo;

    }

    $oDaodb_registrosinconsistentesdados = db_utils::getDao('db_registrosinconsistentesdados');

    $sOrdem  = "db_registrosinconsistentesdados.db137_correto desc";
    $sWhere  = "db_registrosinconsistentesdados.db137_db_registrosinconsistentes = {$oParam->iInconsistencia} ";
    $sWhere .= " and db_registrosinconsistentes.db136_processado = 'f'                                        ";

    $sSqlDb_registrosinconsistentesdados = $oDaodb_registrosinconsistentesdados->sql_query(null, '*', $sOrdem, $sWhere);

    $rsDb_registrosinconsistentesdados   = $oDaodb_registrosinconsistentesdados->sql_record($sSqlDb_registrosinconsistentesdados);

    $aDb_registrosinconsistentesdados    = db_utils::getCollectionByRecord($rsDb_registrosinconsistentesdados);

    $aIn = array();
    foreach ($aDb_registrosinconsistentesdados as $oDb_regIncDadRetorno) {

      $aIn[]  = $oDb_regIncDadRetorno->db137_chave;
      if($oDb_regIncDadRetorno->db137_correto == 't') {
        $oRetorno->iCorreto = $oDb_regIncDadRetorno->db137_chave;
      }

    }

    $sIn = implode(',',$aIn);

    $sSqlInconsistentesDados  = " select *                 ";
    $sSqlInconsistentesDados .= "   from  {$sNomeTabela}   ";
    $sSqlInconsistentesDados .= "   where {$sCampoPk}      ";
    $sSqlInconsistentesDados .= "        in ({$sIn})       ";

    $rsInconsistentesDados = db_query($sSqlInconsistentesDados);

    $aInconsistentesDados = db_utils::getCollectionByRecord($rsInconsistentesDados, false, false, true);

    $oRetorno->aCamposTabela        = $aCamposTabela;
    $oRetorno->aDadosInconsistentes = $aInconsistentesDados;
  break;

  case "incluirInconsistencia" :

    try {

      db_inicio_transacao();

      db_app::import('configuracao/inconsistencia/ProcessamentoInconsistencia');

      /**
       * Procura registros já cadastrados como inconsistências
       */
      $oDaodb_registrosinconsistentesdados = db_utils::getDao('db_registrosinconsistentesdados');

      $aCamposIncorretos = array($oParam->iCorreto);
      foreach ($oParam->aCampos as $oCampo) {
        array_push($aCamposIncorretos, $oCampo->iSequencialCampo);
      }
      $sRegistros       = implode(',', $aCamposIncorretos);
      $sWhereValidacao  = " db137_chave in ({$sRegistros}) ";
      $sWhereValidacao .= " and db136_processado is false";
      $sSqlValidacao    = $oDaodb_registrosinconsistentesdados->sql_query(null, 'db137_chave', null, $sWhereValidacao);
      $rsValidacao      = db_query($sSqlValidacao);

      if (!$rsValidacao) {
        throw new Exception("Erro ao pesquisar inconsistências \n\n".pg_last_error());
      }

      if (pg_num_rows($rsValidacao) > 0) {

        $aCamposValidacao = db_utils::getCollectionByRecord($rsValidacao);
        $sCamposValidacao = '';

        foreach ( $aCamposValidacao as $iIndiceValidacao => $oCampoValidacao ) {

          if ( $iIndiceValidacao > 0 ) {
            $sCamposValidacao .= ", ";
          }

          $sCamposValidacao .= $oCampoValidacao->db137_chave;
        }

        throw new Exception("Registro(s) já incluído(s) ({$sCamposValidacao}) ");
      }

      $oInconsistenciaDados = new InconsistenciaDados();
      $oInconsistenciaDados->setTabela($oParam->iCodigoTabela);

      foreach ($oParam->aCampos as $oCampo) {
        $oInconsistenciaDados->adicionarRegistroInconsistente($oCampo->iSequencialCampo, $oCampo->lExcluir);
      }

      $oInconsistenciaDados->setRegistroCorreto($oParam->iCorreto);
      $oInconsistenciaDados->salvar();

      db_fim_transacao(false);

      $oRetorno->sMessage = urlEncode('Inconsistências cadastradas.');

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->sMessage = urlEncode($eErro->getMessage());
      $oRetorno->iStatus  = 2;
    }

  break;

  case 'processar' :

    try {

      db_app::import('configuracao.inconsistencia.ProcessamentoInconsistencia');
      db_inicio_transacao();

      $oDaoDb_registrosinconsistentes = db_utils::getDao('db_registrosinconsistentes');
      $sWhereInconsistencias          = "db136_processado is false and db136_tabela <> 1010051";

      if ( isset($oParam->lTabelasAluno) && $oParam->lTabelasAluno ) {
      	$sWhereInconsistencias = "db136_processado is false and db136_tabela = 1010051";
      }
      $sSqlIncosistencias = $oDaoDb_registrosinconsistentes->sql_query_file(null, "db136_sequencial", null, $sWhereInconsistencias);
      $rsInconsistencias  = db_query($sSqlIncosistencias);
      $iInconsistencias   = pg_num_rows($rsInconsistencias);

      if ( !$rsInconsistencias ) {
        throw new Exception("Erro ao consultar Inconsistências\n\n" . pg_last_error());
      }

      if ( $iInconsistencias == 0 ) {
        throw new Exception("Inconsistências já processadas.");
      }

      $oIncosistencia   = new ProcessamentoInconsistencia();
      $aInconsistencias = db_utils::getCollectionByRecord($rsInconsistencias);

      foreach ( $aInconsistencias as $oRegistrosInconsistentes ) {

        $oIncosistenciaDados = new InconsistenciaDados($oRegistrosInconsistentes->db136_sequencial);
        $oIncosistencia->adicionarInconsistencia($oIncosistenciaDados);
      }

      $oRetorno->lProcessamentoOK = $oIncosistencia->processar();
      $oRetorno->lPermiteDownload = false;
      $oRetorno->sArquivoLog      = "";
      $sMensagemRetorno           = "Inconsistências processadas.";

      if ( !$oRetorno->lProcessamentoOK ) {

        $sMensagemRetorno           = "Nem todas as inconsistências foram processadas. Contate o suporte.";
        $oRetorno->lProcessamentoOK = false;
        $oUsuario                   = new UsuarioSistema( db_getsession('DB_id_usuario') );

        if ($oUsuario->getLogin() === 'dbseller') {

          $oRetorno->lPermiteDownload = true;
          $oRetorno->sArquivoLog      = urlencode( $oIncosistencia->getNomeArquivoLog() );
          $sMensagemRetorno           = "Nem todas as inconsistências foram processadas. Deseja fazer download do";
          $sMensagemRetorno          .= " arquivo de log?";
        }
      }

      db_fim_transacao(false);
      $oRetorno->sMessage = urlencode($sMensagemRetorno);

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->sMessage = urlEncode($eErro->getMessage());
      $oRetorno->iStatus  = 2;
    }

  break;

  case 'excluir' :

    try {

      db_inicio_transacao();

      db_app::import('configuracao/inconsistencia/InconsistenciaDados');

      $oIncosistencia = new InconsistenciaDados($oParam->iCodigoInconsistencia);
      $oIncosistencia->excluir();

      $oRetorno->sMessage = urlEncode('Inconsistência excluída.');

      db_fim_transacao(false);

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->sMessage = urlEncode($eErro->getMessage());
      $oRetorno->iStatus  = 2;
    }

    break;

}

echo $oJson->encode($oRetorno);