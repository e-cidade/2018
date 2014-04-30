<?php
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

require_once("classes/db_favorecido_classe.php");
require_once("classes/db_taxa_classe.php");
require_once("classes/db_favorecidotaxa_classe.php");

//require_once("model/juridico/Favorecido.model.php");

$oDaoFavorecidoTaxa  = new cl_favorecidotaxa();
$oDaoTaxa            = new cl_taxa();
$oDaoFavorecido      = new cl_favorecido();
$oJson               = new services_json();

$oParam              = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->status    = 1;
$oRetorno->message   = '';
$lErro               = false;

switch ($oParam->exec) {
  case "getTaxas" :
    
    $oRetorno->aTaxas    = array();
    $sSqlFavorecidoTaxa  = $oDaoFavorecidoTaxa->sql_query("","ar36_descricao,ar36_sequencial,v87_sequencial  ","","v87_favorecido = {$oParam->v87_favorecido}");
    $rsSqlFavorecidoTaxa = $oDaoFavorecidoTaxa->sql_record($sSqlFavorecidoTaxa);
    
    if ($oDaoFavorecidoTaxa->numrows > 0) {
      $oRetorno->aTaxas = db_utils::getColectionByRecord($rsSqlFavorecidoTaxa);
    }
  break;
    
  case "Incluir" :
    
    db_inicio_transacao();
    $oDaoFavorecidoTaxa->v87_favorecido = $oParam->v87_favorecido;
    $oDaoFavorecidoTaxa->v87_taxa       = $oParam->v87_taxa;
    $oDaoFavorecidoTaxa->incluir(null);
    if ($oDaoFavorecidoTaxa->erro_status == "0") {
      
      $lErro = true;
      $oRetorno->status    = 2;
      if(strpos($oDaoFavorecidoTaxa->erro_msg, "unicidade")){
        $oRetorno->message   = "Taxa Vinculada a Outro Favorecido";   
      } else {
        $oRetorno->message   = $oDaoFavorecidoTaxa->erro_msg;
      }
    } else {
      
      $oRetorno->message   = $oDaoFavorecidoTaxa->erro_msg;
    }
    db_fim_transacao($lErro);
  break;
  
  case "Excluir":
    
    db_inicio_transacao();
    $oDaoFavorecidoTaxa->excluir($oParam->v87_sequencial);
    if ($oDaoFavorecidoTaxa->erro_status == "0") {
      
      $lErro = true;
      $oRetorno->status    = 2;
      $oRetorno->message   = $oDaoFavorecidoTaxa->erro_msg;
    } else {
      
      $oRetorno->message   = $oDaoFavorecidoTaxa->erro_msg;
    }
    db_fim_transacao($lErro);
    
  break;
}
$oRetorno->message = urlencode(str_replace("\\n", "\n", $oRetorno->message));
echo($oJson->encode($oRetorno));

?>