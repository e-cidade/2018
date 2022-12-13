<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_lote_classe.php"); // face
include("classes/db_carface_classe.php"); // face
include("classes/db_carlote_classe.php"); // lote
include("classes/db_carconstr_classe.php"); // nao escrituradas
include("classes/db_constrcar_classe.php"); // escrituradas
include("classes/db_caracter_classe.php"); // escrituradas
include("classes/db_carpadrao_classe.php"); // escrituradas
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//$tipogrupo = 'F';
//$codigo = 1;
//echo "<br>".$HTTP_SERVER_VARS["QUERY_STRING"]."<br>";
$clcaracter  = new cl_caracter;
$clcarpadrao = new cl_carpadrao;
$result = $clcaracter->sql_record($clcaracter->sql_query("","caracter.*#cargrup.*","j31_grupo#j31_codigo"," j32_tipo = '$tipogrupo'"));
//echo "<br>".$clcaracter->sql_query("","caracter.*#cargrup.*","j31_grupo#j31_codigo"," j32_tipo = '$tipogrupo'")."<br>";
if (isset($nomeiframe)) {
  $db_iframe = $nomeiframe;
} else {
  $db_iframe="db_iframe";
}

if (isset($nomeobj)) {
  $nomeobj = $nomeobj;
} else {
  $nomeobj = "caracteristica";
}
if (isset($enviar)) {
  $caracte = "";
  $car     = "X";
  for ($i=0; $i<$clcaracter->numrows; $i++) {
    db_fieldsmemory($result,$i);
    if (isset($HTTP_POST_VARS['G'.$j31_grupo]) && ($j31_codigo == $HTTP_POST_VARS['G'.$j31_grupo]) ) {
      $caracte .= $car.$HTTP_POST_VARS['G'.$j31_grupo];
      $car      = "X";
    }
  }
  $caracte .= $car;
  echo "<script>parent.document.form1.".$nomeobj.".value = '".$caracte."';</script>";
  echo "<script>parent.".$db_iframe.".hide();</script>";
  // echo "<script>parent.alert(parent.document.form1.caracteristica.value);</script>";
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
 <div class="container">
 <?
  if ($clcaracter->numrows != 0) {
    ?>
    <form name="form1" method="post" action="">
    <fieldset>
    <legend>Caracteristicas</legend>
    <table width="22%"  cellspacing="0">
    <?
    $clcarface = new cl_carface;
    $clcarlote = new cl_carlote;
    $grupo     = 0;
    $xgrupo    = 0;
    $coluna    = 0;

    for ($i = 0; $i < $clcaracter->numrows; $i++) {
      db_fieldsmemory($result, $i);
      $cheque = null;
      if (!isset($enviar)) {
        if (isset($caracteristica) && ($caracteristica != "") ) {
          //echo "<br>X{$j31_codigo}X - $caracteristica<br>";
          if (strpos("XXX".$caracteristica, "X".$j31_codigo."X") != 0) {
            $cheque = ' selected ';
          }
        } else {
          if ($db_opcao != 1 ) {
            if ($tipogrupo == 'F') {
              //die($clcarface->sql_query($codigo,$j31_codigo));
              $resultcar = $clcarface->sql_record($clcarface->sql_query($codigo, $j31_codigo));
              if ($clcarface->numrows != 0) {
                $cheque = ' selected ';
              }
            } else {
              if ($tipogrupo == 'L') {
                $resultcar = $clcarlote->sql_record($clcarlote->sql_query($codigo, $j31_codigo));
                if ($clcarlote->numrows != 0) {
                  $cheque = ' selected ';
                }
              }
            }
          }
        }

      } else {
        if ($db_opcao != 1) {
          if (isset($HTTP_POST_VARS['G'.$j31_grupo]) && ($j31_codigo == $HTTP_POST_VARS['G'.$j31_grupo])) {
            $cheque = ' selected ';
          }
        }
      }

      if ($grupo != $j31_grupo) {
        if ($grupo != 0) {
          ?>
            </select>
            </td></tr><tr>
          <?
        }
        $grupo = $j31_grupo;
        if ($coluna == 0) {
          $coluna = 1;
          ?>
            </tr>
            <tr>
          <?
        } else {
          $coluna = 0;
        }
        ?>
        <td nowrap align="Left"><label for="<?='G'.$j31_codigo?>"><?=str_pad($j32_grupo."-".$j32_descr,20)?></label></td>
        <td nowrap align="left">
        <select id="<?='G'.$j31_codigo?>"  name="<?='G'.$j31_grupo?>">
        <?

        // Verifica Caracteristica padrao se nao foi selecionada uma previamente
        if (is_null($cheque)) {
          $re = $clcarpadrao->sql_record($clcarpadrao->sql_query_file("","j33_codcaracter","","j33_codgrupo=$j31_grupo"));
          if ( $clcarpadrao->numrows > 0 ) {
            db_fieldsmemory($re, 0);
            //echo "<td>j31_grupo=$j31_grupo  j33_codcaracter=$j33_codcaracter  j31_codigo=$j31_codigo</td>";
            if ($j33_codcaracter == $j31_codigo) {
              if ($db_opcao == 1) {
                $cheque = ' selected ';
              }
            }
          }
        }
      }


      if ($xgrupo != $j31_grupo) {
        $xgrupo = $j31_grupo;
        ?>
        <option value="0" >Nenhuma...</option>
        <?
      }

      ?>
      <option value="<?=$j31_codigo?>" <?=$cheque?>><?=str_pad($j31_codigo."-".trim($j31_descr),20)?> </option>
      <?
    }
    ?>
    </table>
    </fieldset>
    <input type="submit" name="enviar" value="Enviar" >
    <input type="button" name="Fechar" value="Fechar" onClick="parent.<?=$db_iframe?>.hide();" >
    </form>
    <?
  }
?>
  </div>
</body>
</html>