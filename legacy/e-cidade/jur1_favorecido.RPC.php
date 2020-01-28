<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once('libs/db_conn.php');
require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/JSON.php');
require_once('libs/db_utils.php');
require_once('dbforms/db_funcoes.php');

require_once("classes/db_db_bancos_classe.php");
require_once("classes/db_bancoagencia_classe.php");
require_once("classes/db_contabancaria_classe.php");
require_once("classes/db_favorecido_classe.php");
require_once("model/juridico/Favorecido.model.php");


$oDaoDb_Bancos       = new cl_db_bancos();
$oDaoBancoAgencia    = new cl_bancoagencia();
$oDaoContaBancaria   = new cl_contabancaria();
$oDaoFavorecido      = new cl_favorecido();
$oJson               = new services_json();

$oParam              = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->status    = 1;
$oRetorno->message   = '';

switch ($oParam->exec) {
   
   case "salvarDados":

    /**
     * Busca na tabela favorecido pelo cgm
     */
    $sSqlFavorecido                 = $oDaoFavorecido->sql_query_dados(null,"v86_sequencial",""," z01_numcgm = {$oParam->oDados->z01_numcgm}");
    $rsSqlFavorecido                = $oDaoFavorecido->sql_record($sSqlFavorecido);
    $iNumRowsFavorecido             = $oDaoFavorecido->numrows;
    
    /**
     * Caso acho favorecido com o cgm selecionado seta a variável ou deixa como nula
     */
    if ($iNumRowsFavorecido > 0) {  
       
      $oFavorecido    = db_utils::fieldsMemory($rsSqlFavorecido,0);
      $iSeqFavorecido = $oFavorecido->v86_sequencial;
    } else {
       $iSeqFavorecido = null;
    }
    
    $oFavorecido = new Favorecido();
    $oFavorecido->setCodigoFavorecido($iSeqFavorecido)
                ->setDVAgencia       ($oParam->oDados->inputDvAgencia)
                ->setNumeroAgencia   ($oParam->oDados->inputNumeroAgencia  )
                ->setCodigoBanco     ($oParam->oDados->inputCodigoBanco)
                ->setNumeroConta     ($oParam->oDados->inputNumeroConta)
                ->setDVConta         ($oParam->oDados->inputDvConta)
                ->setIdentificador   ($oParam->oDados->inputIdentificador)
                ->setCodigoOperacao  ($oParam->oDados->inputOperacao)
                ->setTipoConta       ($oParam->oDados->cboTipoConta)          
                ->setNumCgm          ($oParam->oDados->z01_numcgm)
                ->setContaInterna    ($oParam->oDados->v86_containterna)
                ->salvar(); 
     $oRetorno->status        = $oFavorecido->getErrorStatusFavorecido();
     $oRetorno->message       = $oFavorecido->getErrorMessageFavorecido(); 
     $oRetorno->registroSalvo = $oFavorecido->getCodigoFavorecido(); 
     
  break;
  case "getDadosFavorecido":
      
      $sSqlFavorecido                 = $oDaoFavorecido->sql_query_dados(null,"*",""," z01_numcgm = {$oParam->iCgm}");
      $rsSqlFavorecido                = $oDaoFavorecido->sql_record($sSqlFavorecido);
      $iNumRowsFavorecido             = $oDaoFavorecido->numrows;
      $oRetorno->numrows              = $iNumRowsFavorecido;
      
    if ($iNumRowsFavorecido > 0) {

       $oFavorecido                    = db_utils::fieldsMemory($rsSqlFavorecido,0);
       $oRetorno->z01_numcgm           = $oFavorecido->z01_numcgm;
       $oRetorno->db89_db_bancos       = $oFavorecido->db89_db_bancos;
       $oRetorno->db90_descr           = $oFavorecido->db90_descr;
       $oRetorno->db89_codagencia      = $oFavorecido->db89_codagencia;
       $oRetorno->db89_digito          = $oFavorecido->db89_digito;
       $oRetorno->db83_conta           = $oFavorecido->db83_conta;
       $oRetorno->db83_dvconta         = $oFavorecido->db83_dvconta;
       $oRetorno->db83_identificador   = $oFavorecido->db83_identificador;
       $oRetorno->db83_codigooperacao  = $oFavorecido->db83_codigooperacao;
       $oRetorno->db83_tipoconta       = $oFavorecido->db83_tipoconta;
       $oRetorno->v86_containterna     = $oFavorecido->v86_containterna;
       $oRetorno->v86_sequencial       = $oFavorecido->v86_sequencial;
       $oRetorno->v86_contabancaria    = $oFavorecido->v86_contabancaria;
       
    }
   break;
  case "excluir":
    
    $oFavorecido = new Favorecido();
    $oFavorecido->setNumCgm($oParam->oDados->z01_numcgm)
                ->excluir();
    $oRetorno->message = $oFavorecido->getErrorMessageFavorecido();
    $oRetorno->status  = $oFavorecido->getErrorStatusFavorecido();
    break;
}
$oRetorno->message = urlencode($oRetorno->message);
echo($oJson->encode($oRetorno));

?>