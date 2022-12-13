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

  require_once("libs/db_stdlib.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_sql.php");
  require_once("libs/db_utils.php");
  require_once("libs/db_app.utils.php");
  require_once("libs/JSON.php");
  require_once("std/db_stdClass.php");
  require_once("dbforms/db_funcoes.php");
  
  $oJson                      = new services_json();
  $oPost                      = db_utils::postMemory($_POST);
  $oParam                     = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$oPost->json)));
                              
  $oRetorno                   = new stdClass();
   
  switch( $oParam->sExec ) {
    
    case 'getDadosServidorLocaisDeTrabalho':
      
      try {
        
        $oDaoServidorDadosLocaisTrabalho = db_utils::getDao( 'rhpeslocaltrab' );
        
        $sSql  = " select rh55_codigo         , rh56_seq, rh55_estrut,                                          \n";
        $sSql .= "        rh55_descr          , rh56_princ           ,                                          \n";
        $sSql .= "        rh56_quantidadecusto, rh56_percentualcusto                                            \n";
        $sSql .= "   from rhpeslocaltrab                                                                        \n";
        $sSql .= "        inner join rhpessoalmov on rhpessoalmov.rh02_seqpes = rhpeslocaltrab.rh56_seqpes      \n";
        $sSql .= "        inner join rhlocaltrab  on rhlocaltrab.rh55_codigo  = rhpeslocaltrab.rh56_localtrab   \n";
        $sSql .= "                               and rhlocaltrab.rh55_instit  = rhpessoalmov.rh02_instit        \n";
        $sSql .= "  where rh02_regist = {$oParam->iCodigoServidor}                                              \n";
        $sSql .= "    and rh02_anousu = " . db_anofolha() . "                                                   \n";
        $sSql .= "    and rh02_mesusu = " . db_mesfolha() . "                                                   \n";
        $sSql .= "    and rh02_instit = " . db_getsession('DB_instit') . "                                      \n";
        $sSql .= "  order by rh56_princ desc, rh56_seq                                                          \n";
        
        $rsServidorDadosLocaisTrabalho = $oDaoServidorDadosLocaisTrabalho->sql_record($sSql);
        
        $aServidorDadosLocaisTrabalho = array();
        
        for ( $iContador = 0; $iContador < $oDaoServidorDadosLocaisTrabalho->numrows; $iContador++ ) {
         
          $oServidorDadosLocaisTrabalho = db_utils::fieldsMemory( $rsServidorDadosLocaisTrabalho, $iContador );
          
          $aServidorDadosLocaisTrabalho[] = $oServidorDadosLocaisTrabalho;
          
        }
        
        $oRetorno->status      = 1;
        $oRetorno->message     = 1;
        $oRetorno->aLocaisDeTrabalho = $aServidorDadosLocaisTrabalho;
        
        
      } catch ( Exception $eException ) {
       
        $oRetorno->status      = 2;
        $oRetorno->arquivo     = "";
        $oRetorno->message     = urlencode( $eException->getMessage() );
      }
      
    break;
    
    case 'salvarDadosLocaisTrabalho':
    
      try {
        
       db_inicio_transacao();
      
        /**
         * DELETE: Deleta todos os registros antigos do servidor (locais de trabalho)
         */
       
        $oDaoRhPessoalMov = db_utils::getDao( 'rhpessoalmov' );
        
        $sWhereRhPessoalMov  = "     rh02_regist = {$oParam->iCodigoServidor}       \n";
        $sWhereRhPessoalMov .= " and rh02_anousu = " . db_anofolha() . "            \n";
        $sWhereRhPessoalMov .= " and rh02_mesusu = " . db_mesfolha() . "            \n";
        $sWhereRhPessoalMov .= " and rh02_instit = " . db_getsession('DB_instit')." \n";
        
        $sSqlRhPessoalMov = $oDaoRhPessoalMov->sql_query(null, null, "rh02_seqpes", null, $sWhereRhPessoalMov);
        
        $rsServidorDadosLocaisTrabalho = $oDaoRhPessoalMov->sql_record($sSqlRhPessoalMov);
        
        $iCodigoSeqPes = db_utils::fieldsMemory( $rsServidorDadosLocaisTrabalho, 0)->rh02_seqpes;
        
        $oDaoRhPesLocalTrab = db_utils::getDao( 'rhpeslocaltrab', false );
        $oDaoRhPesLocalTrab = new cl_rhpeslocaltrab();
        $oDaoRhPesLocalTrab->excluir(null, " rh56_seqpes = $iCodigoSeqPes");
        
        if ( $oDaoRhPesLocalTrab->erro_status == "0" ) {
           
          throw new Exception($oDaoRhPesLocalTrab->erro_msg);
        }
        
        
        /**
         * INSERT: Inseri todos os itens lançados pelo programa
         */
        foreach ($oParam->oDadosLocaisTrabalho as $iCodigoLocalTrabalho => $oLocalTrabalho) {
         
          if ($oLocalTrabalho == null) {
             
            continue;
          }
         
          $oDaoRhPesLocalTrab                       = new cl_rhpeslocaltrab();
          $oDaoRhPesLocalTrab->rh56_seqpes          = $iCodigoSeqPes;
          $oDaoRhPesLocalTrab->rh56_localtrab       = $oLocalTrabalho->iCodigoLocal;
          $oDaoRhPesLocalTrab->rh56_princ           = $oLocalTrabalho->lPrincipal ? 't' : 'f';
          $oDaoRhPesLocalTrab->rh56_quantidadecusto = $oLocalTrabalho->iQuantidade;
          $oDaoRhPesLocalTrab->rh56_percentualcusto = $oLocalTrabalho->nPercentual;
          $oDaoRhPesLocalTrab->incluir(null);
          
          if ( $oDaoRhPesLocalTrab->erro_status == "0" ) {
            throw new Exception($oDaoRhPesLocalTrab->erro_msg); 
          }
          
        }
        
        db_fim_transacao(false);
        
        $oRetorno->status  = 1;
        $oRetorno->message = '';
        
     } catch ( Exception $eException ) {
       
       $oRetorno->status      = 2;
       $oRetorno->message     = urlencode( $eException->getMessage() );
     }
    
     break;
  }

echo $oJson->encode($oRetorno);
   
?>