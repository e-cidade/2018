<?
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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
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
 <?
 if(isset($GLOBALS["_FILES"]["ma01_o_imagem"])){
  db_postmemory($GLOBALS["_FILES"]["ma01_o_imagem"]);
  if($error==0){
   if($type!="image/jpeg" and $type!="image/pjpeg"){
    db_msgbox("Imagem não é um formato válido!\\n\\nUtilize somente imagens no formato JPG!");
   }elseif($size > 300000){
    db_msgbox("Tamanho da imagem é maior que o permitido!\\n\\nUtilize imagens até 300 kb!");
   }else{
    // Pega o tamanho da imagem e proporção de resize
    $img_size = getimagesize($tmp_name);
    $scale = @min(200/$img_size[0], 200/$img_size[1]);

    $imagem_gerada = "tmp/".rand(0,999999999).".jpg";
    // Se a imagem não está no tmp/ ela é criada
    if(!file_exists($imagem_gerada)){
     // Se a imagem é maior que o permitido(200x200), encolhe ela
     if($scale<1){
      $new_width = floor($scale * $img_size[0]);
      $new_height = floor($scale * $img_size[1]);
     }else{//senão fica o mesmo tamanho
      $new_width = $img_size[0];
      $new_height = $img_size[1];
     }
     $imagem = @ImageCreateFromJPEG($tmp_name);
     $img_new = @ImageCreateTrueColor($new_width, $new_height);

     // Copia e resize a imagem velha na nova
     @ImageCopyResampled($img_new, $imagem, 0, 0, 0, 0, $new_width, $new_height, $img_size[0], $img_size[1]);
     @ImageJPEG($img_new, $imagem_gerada);
     @ImageDestroy($imagem);
     @ImageDestroy($img_new);
    }
    //retira o 'tmp/' do nome da imagem para gravar no bd
    $parentname = str_replace("tmp/","",$imagem_gerada);
    echo "<center>";
    ?>
      <img src="<?=$imagem_gerada?>">
    <?
    if( empty( $scale ) ){
       echo "<p>Visualização não disponível";
       $parentname = "";
    }
    ?>
     <script>
      parent.document.form1.ma01_o_imagem.value = "<?=$parentname?>";
     </script>
    <?
    echo "</center>";
   }
  }else{?>
   <script>
    alert("Erro na importação da imagem");
   </script>
  <?
  }
 }
 if(isset($_GET["imagem_gerada"])){?>
  <img src="<?=$imagem_gerada?>">
 <?}?>
 </td>
</tr>
<table>
</body>
</html>