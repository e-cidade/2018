<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("std/db_stdClass.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("dbforms/db_classesgenericas.php");

db_postmemory($_POST);

$fa04_d_dtvalidade_dia = '';
$fa04_d_dtvalidade_mes = '';
$fa04_d_dtvalidade_ano = '';


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

function calcula_data($data, $dias= 0, $meses = 0, $ano = 0) {

  $data     = explode("-", $data);
  $novadata = date("Y-m-d", mktime(0, 0, 0, $data[1] + $meses,   $data[2] + $dias, $data[0] + $ano));
  return $novadata;
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
<body class="body-default">

  <div class="container">
	  <?php
  	  if ($linhas == 0) {

        die ('<center><br><br><b><big>Departamento não está cadastrado como UPS no módulo Ambulatorial!'.
            '</big></b></center>');

      } else {
        include("forms/db_frmfar_retirada.php");
      }
    ?>
  </div>
<?php
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?php
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
  echo "<script> $('$sCampoFoco').focus(); </script>";
?>