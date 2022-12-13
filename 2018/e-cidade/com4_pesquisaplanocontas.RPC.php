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

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'libs/JSON.php';
require_once 'libs/db_utils.php';
require_once("classes/db_conplano_classe.php");

$oJson         = new services_json();
$oDaoConplano  = new cl_conplano;
$sName         = $_POST["string"];
$sql           = $oDaoConplano->sql_query_geral(null, null,  
                                                " c60_codcon as cod, c60_estrut||' - '||c60_descr as label",
                                                '2',
                                                "c60_anousu = ".db_getsession("DB_anousu") ." 
                                                 and (c60_estrut like '{$sName}%'
                                                  or c60_descr like '{$sName}%') "
                                                 );
$result   = db_query($sql);
$iNumRows = pg_num_rows($result);
$array    = db_utils::getColectionByRecord($result,false,false, true);
echo $oJson->encode($array);

?>