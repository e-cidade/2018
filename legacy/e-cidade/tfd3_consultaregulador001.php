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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);

$sCamposRegulador = "z01_nome, rh70_descr, coddepto, descrdepto";
$sWhereRegulador  = "tf34_i_pedidotfd = {$oGet->iPedido}";
$oDaoPedidoTfd    = new cl_tfd_pedidotfd();
$sSqlRegulador    = $oDaoPedidoTfd->sql_query_pedido_regulado( null, $sCamposRegulador, null, $sWhereRegulador );
$rsRegulador      = db_query( $sSqlRegulador );

$oRegulador                 = new stdClass();
$oRegulador->sMedico        = null;
$oRegulador->sEspecialidade = null;
$oRegulador->iDepartamento  = null;
$oRegulador->sDepartamento  = null;

if ( $rsRegulador && pg_num_rows($rsRegulador) > 0 ) {

  $oDadosRegulador            = db_utils::fieldsmemory( $rsRegulador, 0 );
  $oRegulador->sMedico        = $oDadosRegulador->z01_nome;
  $oRegulador->sEspecialidade = $oDadosRegulador->rh70_descr;
  $oRegulador->iDepartamento  = $oDadosRegulador->coddepto;
  $oRegulador->sDepartamento  = $oDadosRegulador->descrdepto;
}

?>

<html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
   <link href="estilos.css" rel="stylesheet" type="text/css">
   <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
   <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  </head>
<body class='body-default'>

<div class="subcontainer">
  <fieldset >
    <legend>Dados do Regulador</legend>
    <table class="form-container">
      <tr>
        <td class="bold">Regulador:</td>
        <td style="background-color:#FFF; border: 1px solid #CCC;" >
          <?= $oRegulador->sMedico ?>
        </td>
      </tr>
      <tr>
        <td  class="bold">Especialidade:</td>
        <td class="field-size9" style="background-color:#FFF; border: 1px solid #CCC;">
          <?= $oRegulador->sEspecialidade ?>
        </td>
      </tr>
      <tr>
        <td  class="bold">Unidade:</td>
        <td class="field-size9" style="background-color:#FFF; border: 1px solid #CCC;">
          <?= $oRegulador->sDepartamento ?>
        </td>
      </tr>
    </table>
  </fieldset>
</div>
</body>
</html>
