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
?>
<html>
<head>
<title>Dados do Cadastro de Veículos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type='text/css'>
.valores {background-color:#FFFFFF; width: 35%;}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<table>
		<tr>
			<td>
				<strong>Logradouro: </strong>
			</td>
			<td class="valores">
				<?php 
				  echo $oCgm->getLogradouroComercial();
				?>
			</td>
			<td>
				<strong>Complemento: </strong>
			</td>
			<td class="valores">
				<?php 
				  echo $oCgm->getComplementoComercial();
				?>
			</td>
		</tr>
		<tr>
			<td>
				<strong>Bairro: </strong>
			</td>
			<td class="valores">
				<?php 
				  echo $oCgm->getBairroComercial();
				?>
			</td>
			<td>
				<strong>Município: </strong>
			</td>
			<td class="valores">
				<?php 
				  echo $oCgm->getMunicipioComercial();
				?>
			</td>
		</tr>
		<tr>
			<td>
				<strong>CEP: </strong>
			</td>
			<td class="valores">
				<?php 
				  echo $oCgm->getCepComercial();
				?>
			</td>
			<td>
				<strong>UF: </strong>
			</td>
			<td class="valores">
				<?php 
				  echo $oCgm->getUfComercial();
				?>
			</td>
		</tr>
		<tr>
			<td>
				<strong>Telefone: </strong>
			</td>
			<td class="valores">
				<?php 
				  echo $oCgm->getTelefoneComercial();
				?>
			</td>
			<td>
			  <strong>Celular: </strong>
			</td>
			<td class="valores">
				<?php 
				  echo $oCgm->getCelularComercial();
				?>
			</td>
		</tr>
		<tr>
		  <td>
		  	<strong>Email: </strong>
		  </td>
		  <td class="valores">
		  	<?php 
		  	  echo $oCgm->getEmailComercial();
		  	?>
		  </td>
			<td>
				<strong>Caixa Postal: </strong>
			</td>
			<td class="valores">
				<?php 
				  echo $oCgm->getCaixaPostalComercial();
				?>
			</td>
		</tr>
	</table>
</body>
</html>