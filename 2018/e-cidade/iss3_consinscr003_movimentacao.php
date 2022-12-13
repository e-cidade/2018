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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_issalvara_classe.php");
require_once("classes/db_issmovalvara_classe.php");
require_once("model/issqn/AlvaraMovimentacao.model.php");

$oGet = db_utils::postMemory($_GET);
$clMovAlvara    = new cl_issmovalvara();
/**
 * Campos que serão selecionados na grid de movmentação
 * 
 * ordem
 * 
 * 1 Tipo de Movim.
 * 2 Data da Mov.
 * 3 Tipo de Alvara
 * 4 Situação
 * 5 validade
 * 6 processo
 * 7 login
 * 8 obs
 * 
 * 
 */
$sCampoMov  = "q120_sequencial,                "; // 1
$sCampoMov  = "q121_descr as dl_Movimentação,                "; // 1
$sCampoMov .= "q120_dtmov as dl_data,                        "; // 2
//$sCampoMov .= "q98_descricao as dl_Tipo_Alvará,              "; // 3 removido pois nao temos o tipo guardado na movimentação
$sCampoMov .= "case                                          "; // 4
$sCampoMov .= "  when q123_situacao = 1                      "; // 4
$sCampoMov .= "    then  'Ativo'                             "; // 4
$sCampoMov .= "  else 'Inativo'                              "; // 4
$sCampoMov .= "end as dl_situação,                           "; // 4
$sCampoMov .= "q120_validadealvara ||' Dias' as dl_validade, "; // 5
$sCampoMov .= "q124_codproc as dl_processo,                  "; // 6
$sCampoMov .= "login as dl_Login,                            "; // 7
$sCampoMov .= "q120_obs                                      "; // 8

$sSqlMovAlvara  = "	select {$sCampoMov} ";  
$sSqlMovAlvara .= "   from issmovalvara "; 
$sSqlMovAlvara .= "		 inner join isstipomovalvara on isstipomovalvara.q121_sequencial = issmovalvara.q120_isstipomovalvara  ";  
$sSqlMovAlvara .= "		 inner join issalvara on issalvara.q123_sequencial = issmovalvara.q120_issalvara                       "; 
$sSqlMovAlvara .= "		 inner join issbase on issbase.q02_inscr = issalvara.q123_inscr                                        "; 
$sSqlMovAlvara .= "		 inner join isstipoalvara on isstipoalvara.q98_sequencial = issalvara.q123_isstipoalvara               "; 
$sSqlMovAlvara .= "    inner join db_usuarios on id_usuario = q120_usuario                                                   ";
$sSqlMovAlvara .= "     left join issmovalvaraprocesso on q124_issmovalvara = q120_sequencial  ";
$sSqlMovAlvara .= "		 where q123_inscr = {$oGet->inscricao}                                                                 "; 
$sSqlMovAlvara .= "		order by q120_sequencial desc                                                                          "; 

$rsMovAlvara    = $clMovAlvara->sql_record($sSqlMovAlvara);

?>

<html>
<head>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>

<?
/**
 * Se retornou algum registro imprime a grid
 */
if ($clMovAlvara->numrows > 0) {
  db_lovrot($sSqlMovAlvara,15,"","","");
}   
?>

</body>
</html>