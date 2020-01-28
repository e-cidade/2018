<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
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
      $sSql  = "select distinct o15_codigo, o15_descr                             ";
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