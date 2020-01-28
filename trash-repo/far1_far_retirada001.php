<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("std/db_stdClass.php");
include("dbforms/db_funcoes.php");
require("libs/db_app.utils.php");
require("libs/db_utils.php");
require("libs/db_stdlibwebseller.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);

$clfar_retirada           = db_utils::getdao('far_retirada');
$clfar_retiradaitens      = db_utils::getdao('far_retiradaitens');
$clrotulo                 = new rotulocampo;
$clfar_tiporeceita        = db_utils::getdao('far_tiporeceita');
$clmatparam               = db_utils::getdao('matparam');
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$fa04_i_unidades          = DB_getsession("DB_coddepto");
$departamento             = DB_getsession("DB_coddepto");
$descrdepto               = DB_getsession("DB_nomedepto");
$login                    = DB_getsession("DB_id_usuario");
$db_opcao                 = 1;
$db_botao                 = false;
$dHoje                    = date("Y-m-d",db_getsession("DB_datausu"));
$sSql                     = "select * from unidades ";
$sSql                    .= " inner join db_depart on db_depart.coddepto = unidades.sd02_i_codigo ";
$sSql                    .= " where sd02_i_codigo = ".db_getsession("DB_coddepto");
$resultado                = @db_query($sSql) or die(pg_errormessage());
$linhas                   = @pg_num_rows($resultado);

$oConfigFarmacia = loadConfig('far_parametros');
if (isset($oConfigFarmacia) && $oConfigFarmacia->fa02_i_acaoprog != 0 && $oConfigFarmacia->fa02_i_acaoprog != null) {
	$fa10_i_programa = $oConfigFarmacia->fa02_i_acaoprog;
}
?>
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
db_app::load("/widgets/dbautocomplete.widget.js");
db_app::load("webseller.js");

function calcula_data($data, $dias= 0, $meses = 0, $ano = 0) {

  $data     = explode("-", $data);
  $novadata = date("Y-m-d", mktime(0, 0, 0, $data[1] + $meses,   $data[2] + $dias, $data[0] + $ano));
  return $novadata;

}

?>
<style type="text/css">
.classContinuado {
  background-color: #87CEEB;
}
.classAlterar {
  background-color: #BEBEBE;
}

.classExcluir {
  background-color: #FF0000;
}

.classNull {
  background-color: #FFFFFF;
}
[disabled] {
   background-color: #DEB887;
   color:#696969;
 }
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">

	  <?
  	  if ($linhas == 0) {

        die ('<center><br><br><b><big>Departamento não está cadastrado como UPS no módulo Ambulatorial!'.
            '</big></b></center>');

      } else {

        ?>
        <br><br>
        <center>
        <? include("forms/db_frmfar_retirada.php"); ?>
        </center>

      <?
      }
      ?>
    </td>
  </tr>
</table>
<?
  db_menu (db_getsession ( "DB_id_usuario" ), db_getsession ( "DB_modulo" ), db_getsession ( "DB_anousu" ),
           db_getsession ( "DB_instit" ));
?>
</body>
</html>
<?
if (isset($oConfigFarmacia)) {
   switch ($oConfigFarmacia->fa02_i_cursor) {
     case 1:
            $sCampoFoco = "fa04_i_cgsund";
            break;
     case 2:
            $sCampoFoco = "s115_c_cartaosus";
            break;
     case 3:
            $sCampoFoco = "z01_v_nome";
            break;
     default:
             $sCampoFoco = "fa04_i_cgsund";
   }
} else {
  $sCampoFoco = "fa04_i_cgsund";
}
 echo "<script>
         $('$sCampoFoco').focus();
       </script>";


?>