<?
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
 * @fileoverview Controla Exclusão e Reemissão de TXT para o INSS - SISOBRA
 * @version   $Revision: 1.3 $
 * @revision  $Author: dbrafael.nery $
 */
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");  

require_once ("dbforms/db_funcoes.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$aDadosRetorno          = array();
/**
 * Camada de Tentativas do RPC
 */
try {
  
  switch ($oParam->exec) {
    /**
     * Reemissao do TXT
     */
    case "reemitirTXT":
      
      $oDaoObrasEnvio = db_utils::getDao("obrasenvio");
      $sSqlArquivoTXT = $oDaoObrasEnvio->sql_query_file ( $oParam->iCodigoTxt, "ob16_nomearq, ob16_arq"); 
      $rsArquivoTXT   = $oDaoObrasEnvio->sql_record($sSqlArquivoTXT);
      if ( !$rsArquivoTXT || ($rsArquivoTXT && pg_num_rows($rsArquivoTXT) == 0 ) ) {
        throw new Exception(_M('tributario.projetos.pro4_txtINSS.erro_buscar_conteudo'));
      }
      
      $oArquivoTXT    = db_utils::fieldsMemory($rsArquivoTXT,0);
      
      /**
       * Tenta abrir o arquivo
       */
      $fArquivo       = fopen($oArquivoTXT->ob16_nomearq, "w");
      if (!$fArquivo) {
        throw new Exception(_M('tributario.projetos.pro4_txtINSS.erro_abrir_arquivo'));
      }
      
      /**
       * Tenta Escrever no arquivo 
       */
      $lWriteTXT      = fputs( $fArquivo, $oArquivoTXT->ob16_arq);
      if (!$lWriteTXT) {
        throw new Exception(_M('tributario.projetos.pro4_txtINSS.erro_escrever_arquivo'));
      }

      /**
       * Tenta Fechar o Arquivo
       */
      $lFechaArquivo  = fclose($fArquivo);
      if (!$lFechaArquivo) {
        throw new Exception(_M('tributario.projetos.pro4_txtINSS.erro_fechar_arquivo'));
      }     

      $oRetorno->sArquivoTXT = $oArquivoTXT->ob16_nomearq;
    break;
    /**
     * Exclusão do Txt
     */
    case "excluirTXT":
      
      $oDaoObrasEnvio       = db_utils::getDao("obrasenvio");
      $oDaoObrasEnvioReg    = db_utils::getDao("obrasenvioreg");
      $oDaoObrasEnvioRegHab = db_utils::getDao("obrasenvioreghab");
      /**
       * Camada de Tentativa de exclusão do banco
       */       
      try {
        
        db_inicio_transacao();
        /**
         * Busca todos os Registros do txt informado
         */
        $sSqlRegistrosTXT = $oDaoObrasEnvioReg->sql_query_file(null,
                                                               "ob17_codobrasenvioreg",
                                                               null,
                                                               "ob17_codobrasenvio={$oParam->iCodigoTxt}");
        
        $rsRegistrosTXT   = db_query($sSqlRegistrosTXT);
        
        if (!$rsRegistrosTXT) {
          throw new Exception(_M('tributario.projetos.pro4_txtINSS.erro_buscar_dados'));
        }
        /**
         * Caso haja registros busca os dados do habite-se desses regitros
         */
        if (pg_num_rows($rsRegistrosTXT) > 0) {
          
          $aRegistrosTXT          = array();
          $aObjRegistrosTXT       = db_utils::getCollectionByRecord($rsRegistrosTXT);
          
          /**
           * Cria um array dos registros do txt selecionado para ser melhor manipulado posteriormente
           */
          foreach ($aObjRegistrosTXT as $oRegistrosTXT) {
            $aRegistrosTXT[$oRegistrosTXT->ob17_codobrasenvioreg] = $oRegistrosTXT->ob17_codobrasenvioreg;
          }
          
          $sRegistrosTXT          = implode(", ", $aRegistrosTXT);
          $sSqlHabiteRegistrosTXT = $oDaoObrasEnvioRegHab->sql_query_file(null, 
                                                                          "ob18_codigo", 
                                                                          null, 
                                                                          "ob18_codobraenvioreg in ({$sRegistrosTXT})"); 
          $rsHabiteRegistrosTXT   = db_query($sSqlHabiteRegistrosTXT);
          
          if (!$rsHabiteRegistrosTXT) {
            throw new Exception(_M('tributario.projetos.pro4_txtINSS.erro_buscar_dados_habitese'));
          }
          /**
           * Caso existam registros de habite-se tenta excluir
           */
          if ( pg_num_rows($rsHabiteRegistrosTXT) > 0 ) { 
            
            $aHabiteRegistrosTXT    = array();
            $aObjHabiteRegistrosTXT = db_utils::getCollectionByRecord($rsHabiteRegistrosTXT);
            
            /**
             * Percorre dados de habite-se dos registros do txt, tentando exclui-los
             * Tabela: obrasenvioreghab
             */
            foreach ($aObjHabiteRegistrosTXT as $oHabiteRegistrosTXT) {
              
              $lExclusaoHabiteRegistrosTXT = $oDaoObrasEnvioRegHab->excluir($oHabiteRegistrosTXT->ob18_codigo);
              if (!$lExclusaoHabiteRegistrosTXT) {
                throw new Exception(_M('tributario.projetos.pro4_txtINSS.erro_excluir_habitesse'));
              }
            }
          }
          /**
           * Tenta Excluir Registros do TXT - Tabela: obrasenvioreg
           */
          $lExclusaoRegistrosTXT = $oDaoObrasEnvioReg->excluir(null, "ob17_codobrasenvio={$oParam->iCodigoTxt}");
          if (!$lExclusaoRegistrosTXT) {
            throw new Exception(_M('tributario.projetos.pro4_txtINSS.erro_excluir_registros'));
          }
        }
        /**
         * Tenta Excluir TXT - Tabela: obrasenvio
         */
        $lExclusaoTXT = $oDaoObrasEnvio->excluir($oParam->iCodigoTxt);
        if (!$lExclusaoTXT) {
          throw new Exception(_M('tributario.projetos.pro4_txtINSS.erro_excluir_txt'));
        }
        
        db_fim_transacao(false);
        $oRetorno->sMessage = _M('tributario.projetos.pro4_txtINSS.exclusao_arquivo_efetuada');
        
      } catch (Exception $eErroExclusao) {

        db_fim_transacao(true);
        throw new Exception($eErroExclusao->getMessage());
      }
      
    break;
    
    default:
      throw new Exception(_M('tributario.projetos.pro4_txtINSS.defina_opcao'));
    break;
  }
  
  
  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);
  
} catch (Exception $eErro){
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}

?>