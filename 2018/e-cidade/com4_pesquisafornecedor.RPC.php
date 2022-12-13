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

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'libs/JSON.php';
require_once 'libs/db_utils.php';
require_once ("classes/db_cgm_classe.php");

$oJson   = new services_json();
$oCgm    = new cl_cgm;
$sName   = $_POST["string"];
$sSqlCgm = $oCgm->sql_query_file(null, "distinct z01_numcgm as cod,
                                            z01_nome ||
                                            case 
                                              when z01_nome <> '' 
                                              then ' - '|| z01_nome 
                                              else ''
                                            end as label",
                                       "2",
                                       "z01_nome ilike '".$sName."%'");
$rsCgm    = db_query($sSqlCgm);
$iNumRows = pg_num_rows($rsCgm);
$array    = db_utils::getColectionByRecord($rsCgm, false, false, true);
echo $oJson->encode($array);

?>