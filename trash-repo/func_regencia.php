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

//MODULO: educação
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_regencia_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clregencia = new cl_regencia;
$clregencia->rotulo->label("ed59_i_codigo");
$result = $clregencia->sql_record($clregencia->sql_query("","*","ed232_c_descr"," ed59_i_turma = $turma AND ed59_i_serie = $serieregencia"));
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<script>
 quantos = <?=$clregencia->numrows?>;
</script>
<form name="form2" method="post" action="">
<table width="700" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td align="center" valign="top">
   <br>
   <?if(!isset($pesquisa_chave)){?>
    <b>Disciplinas da Turma <?=pg_result($result,0,'ed57_c_descr')?> para marcar horários:</b>
    <table border="1" cellspacing="1" cellpading="0">
     <tr>
      <td colspan="7">
       <input type="submit" name="pri" value="Início" disabled>
       <input type="submit" name="ant" value="Anterior" disabled>
       <input type="submit" name="prox" value="Próximo" disabled>
       <input type="submit" name="ult" value="Último" disabled>
      </td>
     </tr>
     <tr bgcolor="#CDCDFF" id="cabec">
      <td>Código</td>
      <td>Turma</td>
      <td>Disciplina</td>
      <td>Abreviatura</td>
      <td>Qtd. de Períodos</td>
      <td>Marcados</td>
      <td>Restantes</td>
     </tr>
     <?
     $cor1 = "#97B5E6";
     $cor2 = "#E796A4";
     $cor = "";
     for($f=0;$f<$clregencia->numrows;$f++){
      db_fieldsmemory($result,$f);
      if($cor==$cor1){
       $cor = $cor2;
      }else{
       $cor = $cor1;
      }
      ?>
      <script>
       contador = 0;
       for(i=0;i<parent.document.getElementById("contp").value;i++){
        for(x=0;x<parent.document.getElementById("contd").value;x++){
         codreg = parent.document.getElementById("valorQ"+i+x).value.split("|");
         if(codreg[0]==<?=$ed59_i_codigo?>){
          contador++;
         }
        }
       }
       restantes = <?=$ed59_i_qtdperiodo?>-parseInt(contador);
      </script>
      <tr id="linha<?=$f?>" bgcolor="<?=$cor?>" onclick="parent.js_mostraregencia1(<?=$ed59_i_codigo?>,'<?=$ed232_c_descr?>','<?=$ed232_c_abrev?>')">
       <td><?=$ed59_i_codigo?></td>
       <td><?=$ed57_c_descr?></td>
       <td><?=$ed232_c_descr?></td>
       <td><?=$ed232_c_abrev?></td>
       <td><?=$ed59_i_qtdperiodo?></td>
       <td><input type="text" name="marcados<?=$f?>" value="" size="5" style="background:<?=$cor?>;border:0px;"></td>
       <td><input type="text" name="restantes<?=$f?>" value="" size="5" style="background:<?=$cor?>;border:0px;"></td>
      </tr>
      <script>
      document.form2.marcados<?=$f?>.value = contador;
      document.form2.restantes<?=$f?>.value = restantes;
      if(restantes==0){
       document.getElementById("linha<?=$f?>").style.visibility = "hidden";
       document.getElementById("linha<?=$f?>").style.position = "absolute";
       quantos--;
      }
      </script>
      <?
     }
     ?>
     <tr id="nenhum" style="visibility:hidden;">
      <td colspan="7" align="center">
       Nenhum registro encontrado.
      </td>
     </tr>
     <script>
      if(quantos==0){
       document.getElementById("nenhum").style.visibility = "visible";
       document.getElementById("cabec").style.visibility = "hidden";
       document.getElementById("cabec").style.position = "absolute";
      }
     </script>
    </table>
   <?}?>
  </td>
 </tr>
</table>
</form>
</body>
</html>