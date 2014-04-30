<?php
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
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$oGet = db_utils::postMemory($_GET);
if ($oGet->e42_sequencial != "") {
  
  $oDaoOpAuxiliar = db_utils::getDao("empageordem");
  $sSqlOpAuxiliar = $oDaoOpAuxiliar->sql_query_file($oGet->e42_sequencial);
  $rsOpAuxiliar   = $oDaoOpAuxiliar->sql_record($sSqlOpAuxiliar);
  $oOPAuxiliar    = db_utils::fieldsMemory($rsOpAuxiliar, 0);
  $e42_sequencial = $oOPAuxiliar->e42_sequencial; 
  $e42_data       = explode("-", $oOPAuxiliar->e42_dtpagamento);
   
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("datagrid.widget.js");
    db_app::load("strings.js");
    db_app::load("grid.style.css");
    db_app::load("estilos.css");
    ?>
<style type="">
    .configurada {
    background-color: #d1f07c;
}
.ComMov {
    background-color: rgb(222, 184, 135);
}
.naOPAuxiliar {
    background-color: #ffff99;
}
.configuradamarcado {
    background-color: #EFEFEF;
}
.ComMovmarcado {
    background-color: #EFEFEF;
}
.naOPAuxiliarmarcado {
    background-color: #EFEFEF;
}
.normalmarcado{ background-color:#EFEFEF}
</style>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
    <center>
    <?
     require("forms/db_frmempageautoriza.php");
    ?>
    </center>
  </body>
</html>