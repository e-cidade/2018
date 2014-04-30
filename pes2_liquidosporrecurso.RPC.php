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

require_once("fpdf151/pdf.php"); 
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");  
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");

$oJson               = new services_json();
$oParametros         = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
$iAnoUsu             = db_getsession('DB_anousu');

define("MENSAGENS", "recursoshumanos.pessoal.pes2_liquidosporrecurso.");

try {
  
  switch ( $oParametros->sExecucao ) {
  
    case "getRecursos":

      /**
       * Lista Recursos
       */
      $sSql  = "select distinct o15_codigo, o15_finali                            ";
      $sSql .= "  from folha                                                      ";
      $sSql .= "       inner join rhlota     on r38_lotac::integer = r70_codigo   ";
      $sSql .= "       inner join rhlotavinc on r70_codigo         = rh25_codigo  ";
      $sSql .= "       inner join orctiporec on o15_codigo         = rh25_recurso ";
      $sSql .= " where rh25_anousu = $iAnoUsu                                     ";

      $rsRecursos = pg_query( $sSql );

      if ( !$rsRecursos ){
        throw new DBException( _M( MENSAGENS . 'erro_buscar_dados_recursos' ) );
      }
      
      if( pg_num_rows($rsRecursos) == 0 ){
        throw new BusinessException( _M( MENSAGENS . 'nenhum_recurso_encontrado' ) );
      }  

      $oRetorno->aRecursos = db_utils::getCollectionByRecord( $rsRecursos );
      
    break;
  }
  
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = $oErro->getMessage();
}

$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
echo $oJson->encode($oRetorno);