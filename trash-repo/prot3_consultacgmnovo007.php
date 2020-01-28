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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("model/CgmFactory.model.php");

$oGet = db_utils::postMemory($_GET, false);

if (isset($oGet->cgm)) {
  
  $oCgm = CgmFactory::getInstance('', $oGet->cgm);
}

$oDaoCgm      = db_utils::getDao('cgm');
$sSqlBuscaCGM = $oDaoCgm->sql_query_file($oCgm->getCodigo(), "*", null, "");
$rsBuscaCGM   = $oDaoCgm->sql_record($sSqlBuscaCGM);
$oRegistroCgm = db_utils::fieldsMemory($rsBuscaCGM, 0);

$oDaoDbUsuarios   = db_utils::getDao('db_usuarios');
$sSqlBuscaUsuario = $oDaoDbUsuarios->sql_query_file($oRegistroCgm->z01_login, 'nome');
$rsBuscaUsuario   = $oDaoDbUsuarios->sql_record($sSqlBuscaUsuario);
$oRegistroUsuario = db_utils::fieldsMemory($rsBuscaUsuario, 0);
?>
<html>
<head>
<title>Dados do Cadastro de Veículos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type='text/css'>
.valores {background-color:#FFFFFF; width:35%;}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<table>
		<tr>
			<td>
				<strong>Login: </strong>
			</td>
			<td class="valores">
				<?php 
				  echo $oRegistroUsuario->nome;
				?>
			</td>
			<td>
				<strong>Data Cadastro: </strong>
			</td>
			<td class="valores">
				<?php 
				  echo implode('/', array_reverse(explode('-', $oRegistroCgm->z01_cadast)));
				?>
			</td>
		</tr>
		<tr>
			<td>
				<strong>Ultima Alteração: </strong>
			</td>
			<td class="valores">
				<?php 
				  echo implode('/', array_reverse(explode('-', $oRegistroCgm->z01_ultalt)));
				?>
			</td>
		</tr>
	</table>
</body>
</html>