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
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/ParameterException.php");

require_once("classes/db_inventariobem_classe.php");

$oDaoInventarioBem = new cl_inventariobem();
$oGet              = db_utils::postMemory($_GET, false);

$sCampos  = "t77_inventario,                        ";
$sCampos .= "descrdepto as dl_Departamento_Destino, ";
$sCampos .= "t30_descr  as dl_Divis�o_Destino,      ";
$sCampos .= "t70_descr  as dl_Situa��o,             ";
$sCampos .= "t77_valordepreciavel,                  ";
$sCampos .= "t77_valorresidual,                     ";
$sCampos .= "t77_vidautil                           ";


$sSqlInventarioBem = $oDaoInventarioBem->sql_query(null, $sCampos, null, "t77_bens = {$oGet->t52_bem}");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
  <body>
    <center>
      <fieldset>
        <legend><strong>Movimenta��es de Invent�rio</strong></legend>
        <?php 
          db_lovrot($sSqlInventarioBem  , 15, "", "");
        ?>
      </fieldset>
    </center>
  </body>
</html>