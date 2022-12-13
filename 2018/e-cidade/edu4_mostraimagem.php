<?
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table align="center" border="0" cellspacing="0" cellpading="0">
<tr>
<td>
<?php
  if(isset($GLOBALS["_FILES"]["ed47_o_oid"])) {

    db_postmemory($GLOBALS["_FILES"]["ed47_o_oid"]);
    if ( $error == 0 ) {

      $aTipos = array('image/jpeg', 'image/pjpeg', 'image/png');
      if (!in_array($type, $aTipos)) {

        db_msgbox("Imagem não é um formato válido!\\n\\nUtilize somente imagens no formato JPG!");
        ?><script>parent.frame_file.document.form3.ed47_o_oid.value = "";</script><?
      } elseif ($size > 102400) {

        db_msgbox("Tamanho da imagem é maior que o permitido!\\n\\nUtilize imagens até 100 kb!");
        ?><script>parent.frame_file.document.form3.ed47_o_oid.value = "";</script><?php
      } else {

        // Pega o tamanho da imagem e proporção de resize
        $img_size = getimagesize($tmp_name);
        $scale = @min(105/$img_size[0], 120/$img_size[1]);

        $sExtencao = ".jpg";
        if ( $type == 'image/png') {
          $sExtencao = ".png";
        }

        $imagem_gerada = "tmp/".rand(0,999999999)."$sExtencao";
        // Se a imagem não está no tmp/ ela é criada
        if (!file_exists($imagem_gerada)) {

          // Se a imagem é maior que o permitido(200x200), encolhe ela
          if ($scale < 1) {

            $new_width  = floor($scale * $img_size[0]);
            $new_height = floor($scale * $img_size[1]);
          } else {//senão fica o mesmo tamanho
            $new_width = $img_size[0];
            $new_height = $img_size[1];
          }

          //cria uma nova imagem com o novo tamanho
          $img_new = imagecreatetruecolor($new_width, $new_height);
          switch ($type){

            case 'image/jpeg':
            case 'image/pjpeg': // jpg
              $origem = imagecreatefromjpeg($tmp_name);
              imagecopyresampled($img_new, $origem, 0, 0, 0, 0, $new_width, $new_height, $img_size[0], $img_size[1]);
              imagejpeg($img_new, $imagem_gerada);
              break;
            case 'image/png': // png
              $origem = imagecreatefrompng($tmp_name);
              imagecopyresampled($img_new, $origem, 0, 0, 0, 0, $new_width, $new_height, $img_size[0], $img_size[1]);
              imagepng($img_new, $imagem_gerada);
              break;
          }
          imagedestroy($origem);
          imagedestroy($img_new);
        }
        //retira o 'tmp/' do nome da imagem para gravar no bd
        $parentname = str_replace("tmp/","",$imagem_gerada);
        echo "<center>";
        ?>
        <img src="<?=$imagem_gerada?>">
        <?php
        if( empty( $scale ) ){

          echo "<p>Visualização não disponível";
          $parentname = "";
        }
        ?>
         <script>
          parent.document.form1.ed47_o_oid.value = "<?=$parentname?>";
         </script>
        <?php
        echo "</center>";
      }
    } else {
      ?>
        <script>
          alert("Erro na importação da imagem");
        </script>
      <?
    }
  }
  if(isset($_GET["imagem_gerada"])){?>
    <img src="<?=$imagem_gerada?>">
  <?php
  } else { ?>
    <img src="imagens/none1.jpeg">
 <?php
  } ?>
 </td>
</tr>
<table>
</body>
</html>