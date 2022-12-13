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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/db_stdClass.php"));

db_app::import('exceptions.*');

$oJSON              = new services_json();
$oParametros        = $oJSON->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';
$sCaminhoMensagens  = "configuracao.configuracao.con2_consultaacesso";

try {

  switch ($oParametros->sExecucao) {

    /**
     * Retorna os acessos de acordo com os filtros passados como parâmetro
     *
     * @param string  dDataInicio - "10/10/2000"
     * @param string  dDataFim    - "10/10/2000"
     * @param string  sHoraInicio - "00:00"
     * @param string  sHoraFim    - "00:00"
     * @param string  sUsuario    - Usuário a ser pesquisado
     * @param integer iItemMenu   - Código do item de menu selecionado
     * @param string  sEsquema    - Esquema selecionado
     * @param string  sTabela     - Tabela selecionada
     * @param string  sCampo      - Campo selecionado
     * @param mValor              - Valor a ser pesquisado, seja antigo ou novo
     * @param integer iTipoAcesso
     *                0 - Todas
     *                1 - Apenas Acesso a Rotina
     *                2 - Acesso a Rotina com Modificações no Sistema
     */
    case "getAcessos":

      $aParametros                = array();
      $aParametros["dDataInicio"] = $oParametros->dDataInicio;
      $aParametros["dDataFim"]    = $oParametros->dDataFim;

      if ( !empty( $oParametros->sHoraInicio ) ) {
        $aParametros["sHoraInicio"] = $oParametros->sHoraInicio.":00";
      }

      if ( !empty( $oParametros->sHoraFim ) ) {
        $aParametros["sHoraFim"]    = $oParametros->sHoraFim.":59";
      }

      $aParametros["sUsuario"]    = db_stdClass::normalizeStringJsonEscapeString( $oParametros->sUsuario );
      $aParametros["iUsuario"]    = $oParametros->iUsuario;
      $aParametros["iModulo"]     = $oParametros->iModulo;
      $aParametros["iItemMenu"]   = $oParametros->iItemMenu;
      $aParametros["iTipoAcesso"] = $oParametros->iTipoAcesso;
      $aParametros["sEsquema"]    = db_stdClass::normalizeStringJsonEscapeString( $oParametros->sEsquema );
      $aParametros["sTabela"]     = db_stdClass::normalizeStringJsonEscapeString( $oParametros->sTabela );
      $aParametros["sCampo"]      = db_stdClass::normalizeStringJsonEscapeString( $oParametros->sCampo );
      $aParametros["mValor"]      = db_stdClass::normalizeStringJsonEscapeString( $oParametros->mValor );

      /**
       * Cria acount da pesquisa feita
       */
      if ( $oParametros->lCienteMensagens ) {

        $sMensagemLog  = "Parâmetros de pesquisa:\n";
        $sMensagemLog .= "sUsuario....: " . $aParametros["sUsuario"]    . "\n";
        $sMensagemLog .= "iUsuario....: " . $aParametros["iUsuario"]    . "\n";
        $sMensagemLog .= "iModulo.....: " . $aParametros["iModulo"]     . "\n";
        $sMensagemLog .= "iItemMenu...: " . $aParametros["iItemMenu"]   . "\n";
        $sMensagemLog .= "iTipoAcesso.: " . $aParametros["iTipoAcesso"] . "\n";
        $sMensagemLog .= "sEsquema....: " . $aParametros["sEsquema"]    . "\n";
        $sMensagemLog .= "sTabela.....: " . $aParametros["sTabela"]     . "\n";
        $sMensagemLog .= "sCampo......: " . $aParametros["sCampo"]      . "\n";
        $sMensagemLog .= "mValor......: " . $aParametros["mValor"]      . "\n";
        $sMensagemLog .= "dDataInicio.: " . $aParametros["dDataInicio"] . "\n";
        $sMensagemLog .= "dDataFim....: " . $aParametros["dDataFim"]    . "\n";

        $sMensagemLog .= "\n";

        $sMensagemLog .= "Usuário Logado..: " . db_getsession('DB_login')   . "\n";
        $sMensagemLog .= "Data Sistema....: " . db_getsession('DB_datausu') . "\n";

        db_logsmanual($sMensagemLog);
      }

      $oDaoDBAuditoria      = new cl_db_auditoria();
      $sCamposBuscaAcessos  = "distinct db_logsacessa.*, db_usuarios.login, db_usuarios.nome";
      $sCamposBuscaAcessos .= ", fc_montamenu(db_logsacessa.id_item) as path_menu";
      $sSqlBuscaAcessos     = $oDaoDBAuditoria->sql_query_acessos( $sCamposBuscaAcessos, null, $aParametros );
      $rsBuscaAcessos       = db_query( $sSqlBuscaAcessos );

      if ( !$rsBuscaAcessos ) {
        throw new DBException( "Erro ao buscar os acessos: "._M( "{$sCaminhoMensagens}.registros_nao_encontrados" ) );
      }

      $aAcessos          = array();
      $aCabecalhoAcessos = array();
      $aDetalhesAcesso   = array();
      $aRetornoAcessos   = array();

      if ( pg_num_rows( $rsBuscaAcessos ) > 0 ) {
        $aAcessos = db_utils::getCollectionByRecord($rsBuscaAcessos, false, false, true);
      }

      /**
       * Array para controlar se um logacessa já não foi inserido ao array dos detalhes do acesso, evitando duplicação
       */
      $aControlaLogAcessa = array();

      foreach ( $aAcessos as $oAcesso ) {

        if ( $oParametros->iTipoAcesso == 1 && $oAcesso->modificacoes == "t") {
          continue;
        }
        if ( $oParametros->iTipoAcesso == 2 && $oAcesso->modificacoes == "f") {
          continue;
        }

        /**
         * Montando Cabecalho dos Acessos
         */
        $oCabecalhoAcesso                     = new stdClass();
        $oCabecalhoAcesso->id_item            = $oAcesso->id_item;
        $oCabecalhoAcesso->descricao_menu     = $oAcesso->descricao_menu;
        $oCabecalhoAcesso->arquivo            = $oAcesso->arquivo;
        $oCabecalhoAcesso->path_menu          = $oAcesso->path_menu;
        $aCabecalhoAcessos[$oAcesso->id_item] = $oCabecalhoAcesso;

        if ( in_array( $oAcesso->codsequen, $aControlaLogAcessa ) ) {
          continue;
        }

        /**
         * Detalhes do Acesso
         */
        $oDetalheAcesso                       = new stdClass();
        $oDetalheAcesso->codsequen            = $oAcesso->codsequen;
        $oDetalheAcesso->ip                   = $oAcesso->ip;
        $oDetalheAcesso->{"data"}             = $oAcesso->{"data"};
        $oDetalheAcesso->hora                 = $oAcesso->hora;
        $oDetalheAcesso->obs                  = $oAcesso->obs;
        $oDetalheAcesso->id_usuario           = $oAcesso->id_usuario;
        $oDetalheAcesso->id_modulo            = $oAcesso->id_modulo;
        $oDetalheAcesso->coddepto             = $oAcesso->coddepto;
        $oDetalheAcesso->instit               = $oAcesso->instit;
        $oDetalheAcesso->login                = $oAcesso->login;
        $oDetalheAcesso->nome                 = $oAcesso->nome;
        $oDetalheAcesso->modificacoes         = $oAcesso->modificacoes;
        $aDetalhesAcesso[$oAcesso->id_item][] = $oDetalheAcesso;
        $aControlaLogAcessa[]                 = $oDetalheAcesso->codsequen;
        unset($oCabecalhoAcesso, $oDetalheAcesso);
      }

      /**
       * Constroi um novo objeto adicionando o array de detalhes ao atributo do meu correspondente
       */
      foreach ($aCabecalhoAcessos as $iIdItemMenu => $oCabecalhoAcesso) {

        $oCabecalhoAcesso->aDetalhesAcesso = array();
        if (isset ($aDetalhesAcesso[$iIdItemMenu]) ) {
          $oCabecalhoAcesso->aDetalhesAcesso = $aDetalhesAcesso[$iIdItemMenu];
        }
        $aRetornoAcessos[] = $oCabecalhoAcesso;
        unset($oCabecalhoAcesso);
      }

      $oRetorno->aAcessos = $aRetornoAcessos;

    break;

    case "getModificacoes":

      $oRetorno->aTabelas   = array();
      $oRetorno->aRegistros = array();

      $aParametros                     = array();
      $aParametros['sEsquema']         = db_stdClass::normalizeStringJsonEscapeString($oParametros->sEsquema);
      $aParametros['sCampo']           = db_stdClass::normalizeStringJsonEscapeString($oParametros->sCampo);
      $aParametros['mValor']           = db_stdClass::normalizeStringJsonEscapeString($oParametros->mValor);
      $aParametros['sTabela']          = db_stdClass::normalizeStringJsonEscapeString($oParametros->sTabela);
      /**
       * Removido variavel de hora nos parametros pois alguns logsacessa foram migrados,
       * e os horários não estavam batendo.
       */
      $aParametros['sDataHoraInicial'] = $oParametros->dDataAcesso . ' 00:00:00.000000';
      $aParametros['sDataHoraFim']     = $oParametros->dDataAcesso . ' 23:59:59.999999';
      $aParametros['sUsuario']         = $oParametros->sUsuario;
      $aParametros['iCodigoAcesso']    = $oParametros->iCodigoAcesso;
      $aParametros['iInstituicao']     = $oParametros->iInstituicao;

      $oDaoDBAuditoria       = new cl_db_auditoria();
      $sSqlBuscaModificacoes = $oDaoDBAuditoria->sql_query_modificacoes($aParametros);
      
      $rsBuscaModificacoes   = $oDaoDBAuditoria->sql_record($sSqlBuscaModificacoes);

      if ( !$rsBuscaModificacoes ) {
        throw new DBException( "Erro ao buscar as modificações: "._M( "{$sCaminhoMensagens}.registros_nao_encontrados" ) );
      }

      $aDadosQuery               = db_utils::getCollectionByRecord($rsBuscaModificacoes);
      $aTabelasManipuladas       = array();
      $aTabelaCamposManipulados  = array();
      $aDadosRetorno             = array();

      foreach ( $aDadosQuery as $oRegistroModificacao ) {

        $oTabela                                      = new stdClass();
        $oTabela->codigo_tabela                       = $oRegistroModificacao->codarq;
        $oTabela->nome_tabela                         = urlencode($oRegistroModificacao->tabela);
        $oTabela->rotulo_tabela                       = urlencode($oRegistroModificacao->rotulo);
        $oRetorno->aTabelas[$oTabela->codigo_tabela]  = $oTabela;

        $oCampoManipulado                             = new stdClass();
        $oCampoManipulado->codigo_tabela              = $oRegistroModificacao->codarq;
        $oCampoManipulado->nome_tabela                = urlencode($oRegistroModificacao->tabela);
        $oCampoManipulado->tipo_alteracao             = $oRegistroModificacao->operacao;
        $oCampoManipulado->datahora_servidor          = $oRegistroModificacao->datahora_servidor;
        $oCampoManipulado->rotulo_campo               = urlencode($oRegistroModificacao->nomecam);
        $oCampoManipulado->nome_campo                 = urlencode($oRegistroModificacao->nome_campo);
        $oCampoManipulado->valor_antigo               = urlencode($oRegistroModificacao->valor_antigo);
        $oCampoManipulado->valor_novo                 = urlencode($oRegistroModificacao->valor_novo);

        $oRetorno->aRegistros[]                       = $oCampoManipulado;
        unset($oTabela,$oCampoManipulado);
      }
    break;


    case "getItensMenu":

      $oDaoDBItensMenu      = new cl_db_itensmenu();
      $sSqlItensMenu        = $oDaoDBItensMenu->sql_queryArvoreMenus();
      $rsMenus              = $oDaoDBItensMenu->sql_record($sSqlItensMenu);
      $aItens               = db_utils::getCollectionByRecord($rsMenus, false, false, true);

      $sSqlModulos          = " select db_modulos.id_item,                                                  ";
      $sSqlModulos         .= "        descr_modulo                                                         ";
      $sSqlModulos         .= "   from db_modulos                                                           ";
      $sSqlModulos         .= "        inner join db_itensmenu on db_itensmenu.id_item = db_modulos.id_item ";
      $sSqlModulos         .= "  where libcliente is true                                                   ";
      $sSqlModulos         .= "  order by id_item                                                      ";

      $rsModulos            = db_query($sSqlModulos);
      $oRetorno->aModulos   = db_utils::getCollectionByRecord($rsModulos, false, false, true);
      $oRetorno->aBaseMenus = array();

      foreach ( $aItens as $oItem ) {

        $oBaseMenu              = new stdClass();
        $oBaseMenu->lModulo     = $oItem->is_modulo == 't' ? true : false;
        $oBaseMenu->iIDItemPai  = $oItem->id_parent;
        $oBaseMenu->iIDProprio  = $oItem->id_proprio;
        $oBaseMenu->sDescricao  = $oItem->descricao;
        $oBaseMenu->iIDModulo   = $oItem->id_modulo;
        $oRetorno->aBaseMenus[] = $oBaseMenu;
      }
      break;

    /**
     * Retorna um array de stdClass com os módulos do sistema
     */
    case 'getModulos':

      $oRetorno->aModulos = array();

      $oDaoDbModulos    = new cl_db_modulos();
      $sCamposDbModulos = "id_item, nome_modulo";
      $sOrderDbModulos  = "nome_modulo";
      $sSqlDbModulos    = $oDaoDbModulos->sql_query_file( null, $sCamposDbModulos, $sOrderDbModulos );
      $rsDbModulos      = db_query( $sSqlDbModulos );

      if ( !$rsDbModulos ) {
        throw new DBException( "Erro ao buscar os módulos: "._M( "{$sCaminhoMensagens}.registros_nao_encontrados" ) );
      }

      $iLinhasDbModulos = pg_num_rows( $rsDbModulos );
      if ( $iLinhasDbModulos > 0 ) {

        for ( $iContador = 0; $iContador < $iLinhasDbModulos; $iContador++ ) {

          $oRetornoModulos       = db_utils::fieldsMemory( $rsDbModulos, $iContador );
          $oDadosModulo          = new stdClass();
          $oDadosModulo->iCodigo = $oRetornoModulos->id_item;
          $oDadosModulo->sNome   = urlencode( $oRetornoModulos->nome_modulo );
          $oRetorno->aModulos[]  = $oDadosModulo;
        }
      }

      break;

    /**
     * Retorna um array de stdClass com os esquemas do sistema
     */
    case 'getEsquemas':

      $oRetorno->aEsquemas = array();

      $oDaoDbSysModulo    = new cl_db_sysmodulo();
      $sCamposDbSysModulo = "codmod, nomemod";
      $sWhereDbSysModulo  = "ativo = 't'";
      $sOrderDbSysModulo  = "nomemod";
      $sSqlDbSysModulo    = $oDaoDbSysModulo->sql_query_file( null, $sCamposDbSysModulo, $sOrderDbSysModulo, $sWhereDbSysModulo );
      $rsDbSysModulo      = db_query( $sSqlDbSysModulo );

      if ( !$rsDbSysModulo ) {
        throw new DBException( "Erro ao buscar os esquemas: "._M( "{$sCaminhoMensagens}.registros_nao_encontrados" ) );
      }

      $iLinhasDbSysModulo = pg_num_rows( $rsDbSysModulo );
      if ( $iLinhasDbSysModulo > 0 ) {

        for ( $iContador = 0; $iContador < $iLinhasDbSysModulo; $iContador++ ) {

          $oRetornoModulo        = db_utils::fieldsMemory( $rsDbSysModulo, $iContador );
          $oDadosModulo          = new stdClass();
          $oDadosModulo->iCodigo = $oRetornoModulo->codmod;
          $oDadosModulo->sNome   = urlencode( $oRetornoModulo->nomemod );
          $oRetorno->aEsquemas[] = $oDadosModulo;
        }
      }

      break;

    /**
     * Retorna um array de stdClass com as tabelas vinculadas ao módulo passado como parâmetro
     *
     *  @param integer $oParametros->iModulo
     */
    case 'getTabelasModulo':

      if ( isset( $oParametros->iEsquema ) && !empty( $oParametros->iEsquema ) ) {

        $oRetorno->aTabelas  = array();

        $oDaoDbSysArquivo    = new cl_db_sysarquivo();
        $sCamposDbSysArquivo = "db_sysarquivo.codarq, db_sysarquivo.nomearq, db_sysarquivo.rotulo";
        $sWhereDbSysArquivo  = "db_sysarqmod.codmod = {$oParametros->iEsquema}";
        $sOrderDbSysArquivo  = "db_sysarquivo.rotulo, db_sysarquivo.nomearq";
        $sSqlDbSysArquivo    = $oDaoDbSysArquivo->sql_query_arqmod(
      	                                                            null,
                                                                    $sCamposDbSysArquivo,
                                                                    $sOrderDbSysArquivo,
                                                                    $sWhereDbSysArquivo
                                                                  );
        $rsDbSysArquivo = db_query( $sSqlDbSysArquivo );

        if ( !$rsDbSysArquivo ) {
          throw new DBException( "Erro ao buscar as tabelas do módulo: "._M( "{$sCaminhoMensagens}.registros_nao_encontrados" ) );
        }

        $iLinhasDbSysArquivo = pg_num_rows( $rsDbSysArquivo );

        if ( $iLinhasDbSysArquivo > 0 ) {

          for ( $iContador = 0; $iContador < $iLinhasDbSysArquivo; $iContador++ ) {

            $oRetornoTabelaModulo        = db_utils::fieldsMemory( $rsDbSysArquivo, $iContador );
            $oDadosTabelaModulo          = new stdClass();
            $oDadosTabelaModulo->iCodigo = $oRetornoTabelaModulo->codarq;

            $sNome = $oRetornoTabelaModulo->nomearq;

            $oDadosTabelaModulo->sLabel   = urlencode( $sNome );

            if (!empty($oRetornoTabelaModulo->rotulo)) {
              $sNome = $oRetornoTabelaModulo->rotulo . " (" . $sNome . ")";
            }

            $oDadosTabelaModulo->sNome   = urlencode( $sNome );
            $oRetorno->aTabelas[]        = $oDadosTabelaModulo;
          }
        }
      }

      break;

    /**
     * Retorna um array de stdClass com os campos da tabela passada como parâmetro
     *
     *  @param integer $oParametros->iTabela
     */
    case 'getCamposTabela':

      if ( isset( $oParametros->iTabela ) && !empty( $oParametros->iTabela ) ) {

        $oRetorno->aCampos   = array();

        $oDaoDbSysArqCamp    = new cl_db_sysarqcamp();
        $sCamposDbSysArqCamp = "db_syscampo.codcam, db_syscampo.nomecam, db_syscampo.rotulo";
        $sOrderDbSysArqCamp  = "db_syscampo.rotulo, db_syscampo.codcam";
        $sSqlDbSysArqCamp    = $oDaoDbSysArqCamp->sql_query(
                                                             $oParametros->iTabela,
                                                             null,
                                                             null,
                                                             $sCamposDbSysArqCamp,
                                                             $sOrderDbSysArqCamp
                                                           );
        $rsDbSysArqCamp      = db_query( $sSqlDbSysArqCamp );

        if ( !$rsDbSysArqCamp ) {
          throw new DBException( "Erro ao buscar os campos da tabela: "._M( "{$sCaminhoMensagens}.registros_nao_encontrados" ) );
        }

        $iLinhasDbSysArqCamp = pg_num_rows( $rsDbSysArqCamp );

        if ( $iLinhasDbSysArqCamp > 0 ) {

          for ( $iContador = 0; $iContador < $iLinhasDbSysArqCamp; $iContador++ ) {

            $oRetornoCamposTabela        = db_utils::fieldsMemory( $rsDbSysArqCamp, $iContador );
            $oDadosCamposTabela          = new stdClass();
            $oDadosCamposTabela->iCodigo = $oRetornoCamposTabela->codcam;

            $sNome = $oRetornoCamposTabela->nomecam;
            $oDadosCamposTabela->sLabel   = urlencode( $sNome );

            if ( !empty($oRetornoCamposTabela->rotulo) ) {
              $sNome = $oRetornoCamposTabela->rotulo . " (" . $sNome . ")";
            }

            $oDadosCamposTabela->sNome   = urlencode( $sNome );
            $oRetorno->aCampos[]         = $oDadosCamposTabela;
          }
        }
      }

      break;
  }
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($oErro->getMessage());
}

echo $oJSON->encode($oRetorno);
