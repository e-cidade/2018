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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/JSON.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");

$objJSON = new Services_JSON();
$oPost   = db_utils::postMemory($_POST);

$oJson   = $objJSON->decode(str_replace("\\","",$oPost->json));


$sSqlUsuarios = "select 0 as id_usuario,                                                   ";
$sSqlUsuarios .= "       'Selecione o Usuário' as nome                                      ";
$sSqlUsuarios .= "union all                                                                 ";
$sSqlUsuarios .= "select id_usuario, nome                                                   ";
$sSqlUsuarios .= "  from ( select id_usuario, nome                                          ";
$sSqlUsuarios .= "          from (                                                          ";
$sSqlUsuarios .= "               select distinct U.id_usuario, nome                         ";
$sSqlUsuarios .= "                 from db_usuarios U                                       ";
$sSqlUsuarios .= "               inner join db_depusu D    on U.id_usuario  = D.id_usuario  ";
$sSqlUsuarios .= "               inner join db_permissao P on U.id_usuario  = P.id_usuario  ";
$sSqlUsuarios .= "                                        and P.id_item     = 2182          ";
$sSqlUsuarios .= "               where D.coddepto = {$oJson->icoddepto} and  usuarioativo=1 ";
$sSqlUsuarios .= "               union                                                      ";
$sSqlUsuarios .= "               select distinct U.id_usuario, nome                         ";
$sSqlUsuarios .= "                 from db_usuarios U                                       ";
$sSqlUsuarios .= "               inner join db_depusu D    on U.id_usuario  = D.id_usuario  ";
$sSqlUsuarios .= "               inner join db_permherda H on U.id_usuario  = H.id_usuario  ";
$sSqlUsuarios .= "               inner join db_permissao P on P.id_usuario  = H.id_perfil   ";
$sSqlUsuarios .= "                                        and P.id_item     = 2182          ";
$sSqlUsuarios .= "               where D.coddepto = {$oJson->icoddepto} and usuarioativo=1  ";
$sSqlUsuarios .= "order by nome                                                             ";
$sSqlUsuarios .= "              ) as x                                                      ";
$sSqlUsuarios .= " ) as y                                                                   ";
$rsUsuarios = pg_query($sSqlUsuarios);
$iNumRows   = pg_num_rows($rsUsuarios);

$aUsuarios  = array();
for ($i = 0; $i < $iNumRows; $i++) {

  $oUsuario = db_utils::fieldsMemory($rsUsuarios,$i,false,false,true);
  $aUsuarios[] = $oUsuario;
  
}

$sRetorno = $objJSON->encode($aUsuarios);

echo $sRetorno;

?>