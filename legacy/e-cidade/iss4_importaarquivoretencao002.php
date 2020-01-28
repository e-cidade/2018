<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oPost             = db_utils::postMemory($_POST);
$oArquivoImportado = db_utils::postMemory($_FILES);

define('MENSAGENS', 'tributario.issqn.iss4_importaarquivoretencao.');

$sMensagemRetorno = '';

if (isset($_FILES) && !empty($oArquivoImportado->arquivo["tmp_name"])) {

  db_inicio_transacao();

  try {

    if ( !db_utils::inTransaction()) {
      throw new Exception ( _M( MENSAGENS . "sem_transacao" ) );
    }

    $sArquivoImportadoNome           = $oArquivoImportado->arquivo['name'];
    $sArquivoImportadoType           = $oArquivoImportado->arquivo['type'];
    $sArquivoImportadoNomeTemporario = $oArquivoImportado->arquivo['tmp_name'];

    $aArquivoExtensao                = explode( ".", $sArquivoImportadoNome );

    $sArquivoImportadoExtensao       = trim($aArquivoExtensao[count($aArquivoExtensao)-1]);

    /**
     * Validamos se o arquivo possui extensão válida
     */
    if ( $sArquivoImportadoType != 'application/octet-stream' ){
      throw new Exception( _M( MENSAGENS . "arquivo_invalido" ) );
    }

    if ($sArquivoImportadoExtensao <> 'ret' && $sArquivoImportadoExtensao <> 'RET') {
      throw new Exception( _M( MENSAGENS . "extensao_invalida" ) );
    }

    $oDbLayoutReader = new DBLayoutReader( IssArquivoRetencao::CODIGO_LAYOUT, $sArquivoImportadoNomeTemporario );
    $iOid            = DBLargeObject::criaOID( true );
    $lSalvaArquivo   = DBLargeObject::escrita( $sArquivoImportadoNomeTemporario, $iOid );

    $oDadosErro = new stdClass();
    $oDadosErro->sNomeArquivo = $oArquivoImportado->arquivo["name"];
    if (!$lSalvaArquivo) {
      throw new Exception(_M( MENSAGENS . "erro_salvar_arquivo", $oDadosErro ));
    }

    $aLinhasTotal = $oDbLayoutReader->getLines();

    if ( count( $aLinhasTotal ) == 0 ) {
      throw new BusinessException( _M( MENSAGENS . "registros_nao_encontrados" ) );
    }

    $aLinhas[] =  $aLinhasTotal[0];
    $aLinhas[] =  $aLinhasTotal[count($aLinhasTotal) - 1];

    $oIssArquivoRetencao = new IssArquivoRetencao();
    $oIssArquivoRetencao->carregarDados($aLinhas, $iOid, $sArquivoImportadoNome);

    if ( !$oIssArquivoRetencao->validarRemessa() ) {
      throw new BusinessException( _M( MENSAGENS . "arquivo_duplicado" ) );
    }

    $oIssArquivoRetencao->incluir();

    /**
     * Importamos os registros do arquivo
     */
    $oIssArquivoRetencao->incluirRegistros( $aLinhasTotal );

    db_fim_transacao(false);

    db_msgbox( _M( MENSAGENS . "sucesso_importacao" ) );

  } catch (BusinessException $oErro){

    db_fim_transacao(true);
    db_msgbox( $oErro->getMessage() );

  } catch (Exception $oErro) {

    db_fim_transacao(true);
    db_msgbox( $oErro->getMessage() );
  }

}

db_redireciona("iss4_importaarquivoretencao001.php");