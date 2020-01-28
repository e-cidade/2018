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


  require_once("libs/db_stdlib.php");
  require_once("libs/db_utils.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("std/DBDate.php");
  require_once("dbforms/db_funcoes.php");

  define("MENSAGEM","tributario.issqn.iss1_importararquivosimplesnacional002.");

  db_inicio_transacao();

  try {

    /**
     * Valida nome do arquivo, deve seguir o padrão:
     * 02-XXXX(codigo Tom)-AAAAMMDD(ano, mês e dia do arquivo).txt
     */
    $sNomeArquivo    = $_FILES['arquivo']['name'];
    $sCaminhoArquivo = "tmp/" . $sNomeArquivo;

    verificaNomeArquivo($sNomeArquivo);

    $lUploadArquivo  = move_uploaded_file($_FILES['arquivo']['tmp_name'], "tmp/" . $sNomeArquivo);

    if ( !$lUploadArquivo ){

      throw new Exception( _M( MENSAGEM . "erro_importacao" ) );
    }

    importaArquivo($sNomeArquivo, $sCaminhoArquivo);

    db_fim_transacao();
    db_redireciona( "iss4_importararquivosimplesnacional001.php?sMessage=" . _M( MENSAGEM . "sucesso" ) );

  } catch (Exception $oErro) {

    db_fim_transacao(true);
    db_redireciona( "iss4_importararquivosimplesnacional001.php?sMessage=" . $oErro->getMessage() );
  }

  /**
   * Verifica se já não existe um arquivo com o mesmo nome
   * @param  String $sNomeArquivo Nome do Arquivo
   * @return Boolean returna true se não existe e false se existe.
   */
  function verificaNomeArquivo($sNomeArquivo) {

    $oDaoSimplesImportacao = db_utils::getDao('arquivosimplesimportacao');
    $sSqlNomeArquivo       = $oDaoSimplesImportacao->sql_query(null, 1, null, "q64_nomearquivo = '{$sNomeArquivo}'");
    $rsNomeArquivo         = $oDaoSimplesImportacao->sql_record($sSqlNomeArquivo);

    if ($oDaoSimplesImportacao->numrows > 0) {

      throw new BusinessException( _M( MENSAGEM . "nome_igual" ) );
    }

    /**
     * Valida o nome do arquivo.
     */
    if ( !preg_match( '/02-\d{4}-(\d{4})(\d{2})(\d{2})/', $sNomeArquivo, $aData ) ) {

      throw new BusinessException( _M( MENSAGEM . "nome_arquivo_invalido" ) );
    }

    try {

      new DBDate("{$aData[1]}-{$aData[2]}-{$aData[3]}");
    } catch (Exception $sException) {

      throw new BusinessException( _M( MENSAGEM . "nome_arquivo_invalido_data" ) );
    }

    return true;
  }

  /**
   * Salva os dados na tabela arquivosimplesimportacao
   * @param  String $sNomeArquivo
   * @param  String $sCaminhoArquivo
   */
  function importaArquivo($sNomeArquivo, $sCaminhoArquivo){

    $oDaoSimplesImportacao = db_utils::getDao('arquivosimplesimportacao');

    /**
     * Insere os dados na tabela arquivosimplesimportacao
     */
    $oDaoSimplesImportacao->q64_sequencial  = null;
    $oDaoSimplesImportacao->q64_nomearquivo = $sNomeArquivo;
    $oDaoSimplesImportacao->q64_data        = date('Y-m-d');
    $oDaoSimplesImportacao->q64_processado  = 'false';
    $oDaoSimplesImportacao->incluir(null);

    if ($oDaoSimplesImportacao->erro_status == "0") {

      throw new DBException(_M(MENSAGEM."erro_arquivo"));
    }

    importaDetalhes($sCaminhoArquivo, $oDaoSimplesImportacao->q64_sequencial);
  }

  /**
   * Le o arquivo para cada linha chama o metodo inserirDetalhe.
   * @param  String $sCaminhoArquivo
   * @param  integer $iCodigoArquivo
   */
  function importaDetalhes($sCaminhoArquivo, $iCodigoArquivo) {

    /**
     * Valida conteudo do arquivo importado
     */
    if ( !filesize( $sCaminhoArquivo ) ){

      throw new DBException( _M( MENSAGEM . "arquivo_conteudo_vazio" ) );
    }

    $handleArquivo = fopen($sCaminhoArquivo, 'r');

    $iLinha = 1;
    while($sLinhaArquivo = fgets($handleArquivo)) {

      if (empty($sLinhaArquivo)) {
        continue;
      }

      $sCnpj = substr($sLinhaArquivo,  0, 14);
      $sCnae = substr($sLinhaArquivo, 18, 7 );

      /**
       * Valida o layout do arquivo
       */
      if ( strlen(trim($sLinhaArquivo)) != 95 ||  strlen( $sCnpj ) < 14 || strlen( $sCnae ) < 7 ){

        $oParametro         = new StdClass;
        $oParametro->iLinha = $iLinha;

        throw new DBException( _M( MENSAGEM . "arquivo_layout_invalido",  $oParametro) );
      }

      inserirDetalhe($iCodigoArquivo, $sCnpj, $sCnae);

      $iLinha++;
    }
  }

  /**
   * Insere na tabela arquivosimplesimportacaodetalhe
   * @param  integer $iCodigoArquivo
   * @param  String $sCnpj
   * @param  String $sCnae
   */
  function inserirDetalhe($iCodigoArquivo, $sCnpj, $sCnae) {

    $oDaoSimplesImportacaoDetalhe = db_utils::getDao('arquivosimplesimportacaodetalhe');
    $oDaoSimplesImportacaoDetalhe->q142_arquivosimplesimportacao = $iCodigoArquivo;
    $oDaoSimplesImportacaoDetalhe->q142_cnpj = $sCnpj;
    $oDaoSimplesImportacaoDetalhe->q142_cnae = $sCnae;
    $oDaoSimplesImportacaoDetalhe->q142_apto = 'false';

    $oDaoSimplesImportacaoDetalhe->incluir(null);

    if ($oDaoSimplesImportacaoDetalhe->erro_status == "0") {
      throw new DBException(_M(MENSAGEM."erro_arquivo_detalhe"));
    }
  }