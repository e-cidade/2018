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

require_once('libs/db_stdlib.php');
require_once('libs/db_utils.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/JSON.php');
require_once('dbforms/db_funcoes.php');
require_once('libs/db_stdlibwebseller.php');

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$iLinhas  = 0;
$aRetorno = array();


/********************************************
 * RETORNA OS DESTINOS PARA UM PEDIDO TFD            
 * CAMPO FILTRADO: tf03_c_descr
 * TABELA        : tfd_destino
 * OBS           : 
 *******************************************/
if ($oParam->exec == "DesinoPedidoTFD") {

  $oDaoTfdAgendaSaida = db_utils::getdao('tfd_destino');
  $sCampos            = " distinct tf03_c_descr as label,  tf03_i_codigo as cod ";
  $sWhere             = " tf03_c_descr like upper('".$oParam->string."%') ";
  $sSql               = $oDaoTfdAgendaSaida->sql_query_file (null, $sCampos, null, $sWhere);
  $rs                 = $oDaoTfdAgendaSaida->sql_record($sSql); 
  $iLinhas            = $oDaoTfdAgendaSaida->numrows;

}

if ($iLinhas > 0) {

  $aRetorno = db_utils::getColectionByRecord($rs, false, false, true);
  
}
echo $oJson->encode($aRetorno);
?>