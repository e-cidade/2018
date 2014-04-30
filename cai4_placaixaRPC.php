<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/JSON.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$iAnoUsu = db_getsession("DB_anousu");
$oJson   = new services_JSON();
$oParam  = $oJson->decode(str_replace("\\","",$_POST["sJson"]));

if ($oParam->exec == 'getCgmConta') {
  
   $aCgm         = array("z01_numcgm" => "", "z01_nome" => ""); //Garantimos que ira ter uma string valida para retorno
   $oDaoreduzCgm = db_utils::getDao("conplanoreduzcgm");
   $rsConta      = $oDaoreduzCgm->sql_record($oDaoreduzCgm->sql_query(null,"c22_numcgm,z01_nome",null,
                                             "c22_reduz={$oParam->iCodReduz} and c22_anousu = {$iAnoUsu}"
                                             ));
   if ($oDaoreduzCgm->numrows > 0) {

      $oReduzCgm = db_utils::fieldsMemory($rsConta, 0);
      $aCgm      = array("z01_numcgm" => $oReduzCgm->c22_numcgm, "z01_nome"=> $oReduzCgm->z01_nome);

   } else {
     
      $oDaoConfig    = db_utils::getDao("db_config");
      $sSqlDbconfig  = "select z01_numcgm,                           ";
      $sSqlDbconfig .= "       z01_nome                              ";
      $sSqlDbconfig .= "  from db_config                             ";
      $sSqlDbconfig .= "       inner join cgm on numcgm = z01_numcgm ";
      $sSqlDbconfig .= " where codigo = ".db_getsession("DB_instit");
      $rsConfig      = $oDaoConfig->sql_record($sSqlDbconfig);
      if ($oDaoConfig->numrows > 0) {

         $oConfig = db_utils::fieldsMemory($rsConfig, 0);
         $aCgm    = array("z01_numcgm" => $oConfig->z01_numcgm, "z01_nome" => $oConfig->z01_nome);
      }
   }
   echo $oJson->encode($aCgm);
}