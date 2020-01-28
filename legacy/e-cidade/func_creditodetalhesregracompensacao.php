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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);
$iCodigoCredito = $oGet->iCodigoCredito;

db_app::load('estilos.css');

$oDaoAbatimentoRegraCompensacao = db_utils::getDao('abatimentoregracompensacao');

$sWhere = "k156_abatimento = {$oGet->iCodigoCredito}";

$sCampos  = "k154_descricao,                     ";
$sCampos .= "k00_descr as dl_Tipo_Débito_Origem, ";
$sCampos .= "k155_percmaxuso,                    ";
$sCampos .= "k155_tempovalidade,                 ";
$sCampos .= "k155_automatica,                    ";    
$sCampos .= "k155_permitetransferencia           ";

$sSql = $oDaoAbatimentoRegraCompensacao->sql_query(null, $sCampos, null, $sWhere);

$rsDadosAbatimentoRegraCompensacao = $oDaoAbatimentoRegraCompensacao->sql_record($sSql);

db_lovrot($sSql, 10);
