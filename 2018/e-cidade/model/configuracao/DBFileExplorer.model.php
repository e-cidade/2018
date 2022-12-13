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
 * 
 * @author dbseller
 * @abstract
 */
abstract class DBFileExplorer {

  /**
   * Mostra os Itens de Um diretório
   * @param string  $sDiretorio        -> Diretório a ser pesquisado
   * @param boolean $lMostraDiretorios -> mostra pastas
   * @param boolean $lMostraArquivos   -> mostra arquivos
   * @param string  $sRegexpIgnorar    -> expressão regular para ignorar casos Ex. $sRegexpIgnorar = "/CVS/";
   * @param boolean $lRecursivo        -> pesquisar diretório recursivamente
   * @return $aRetorno - Array contendo a string dos itens encontrados nos diretórios 
   */
  public static function listarDiretorio( $sDiretorio, $lMostraDiretorios = true, $lMostraArquivos = true, $sRegexpIgnorar = null, $lRecursivo = false) {
  
    $aRetorno = array();
    if ( !is_dir( $sDiretorio ) ) {
      throw new Exception("Nao e um diretorio.");
    }
  
    if ( !is_readable( $sDiretorio ) ) {
      throw new Exception("Diretorio não Pode ser Lido.");
    }
  
    $rDiretorio = opendir( $sDiretorio );
  
    if ( !$rDiretorio ) {
      throw new Exception('Nao foi possivel abrir o Diretorio');
    }

    while ( ( $sArquivo = readdir( $rDiretorio ) ) !== false ) {
      
      $lAchouExpressao = is_null( $sRegexpIgnorar ) ? false : preg_match( $sRegexpIgnorar, $sArquivo );
      
      if ( $sArquivo == "." || $sArquivo == ".." || $lAchouExpressao ) {
        continue;
      }
      
      if ( is_dir( "$sDiretorio/$sArquivo" ) && is_readable( "$sDiretorio/$sArquivo" ) && $lRecursivo ) {
        $aRetorno = array_merge($aRetorno, DBFileExplorer::listarDiretorio( "$sDiretorio/$sArquivo", $lMostraDiretorios ,$lMostraArquivos, $sRegexpIgnorar, $lRecursivo ) );
      }
  
      $lDiretorio       =  is_dir("$sDiretorio/$sArquivo");
      $lMostraDiretorio =  is_dir("$sDiretorio/$sArquivo") && $lMostraDiretorios;
      $lMostraArquivo   = !is_dir("$sDiretorio/$sArquivo") && $lMostraArquivos;
  
      if ( $lMostraArquivo || $lMostraDiretorio ) {
        $aRetorno[] = "{$sDiretorio}/{$sArquivo}";
      }
    }
    return $aRetorno;
  }
  
  
  /**
   * Retorna o Caminho onde o arquivo foi encontrado
   * @param string $sDiretorio   -> Diretório Raiz a ser pesquisado
   * @param string $sNomeArquivo -> Nome do Arquivo a ser pesquisado    
   * @return $sRetorno - String com o caminho completo do arquivo solicitado
   */
  public static function getCaminhoArquivo ($sDiretorioRaiz, $sNomeArquivo) {
    
    if ( !is_dir( $sDiretorioRaiz ) ) {
      throw new Exception("Não é um diretório.");
    }
    
    if ( !$sNomeArquivo ) {
      throw new Exception('Não foi Informado o nome do arquivo!');
    }
    
    $sDirectoryScripts = $sDiretorioRaiz;
    
    if (is_file( "{$sDiretorioRaiz}/{$sNomeArquivo}" ) && filesize("{$sDiretorioRaiz}/{$sNomeArquivo}") > 0 )
      return "{$sDiretorioRaiz}/{$sNomeArquivo}";
    
    $aDiretorios       = DBFileExplorer::listarDiretorio( $sDiretorioRaiz, true, true, null, true );
    
    sort($aDiretorios);
   
    foreach ( $aDiretorios as $sDiretorio ) {

      $lExisteArquivo = is_file( "{$sDiretorio}/{$sNomeArquivo}" );
      
      $aArquivosExecucao = array();
      
      if ( $lExisteArquivo && filesize("{$sDiretorio}") > 0 )
        return "{$sDiretorio}/{$sNomeArquivo}";
    }
    
    return null;
  }
}