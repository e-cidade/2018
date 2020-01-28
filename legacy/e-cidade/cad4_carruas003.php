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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_caracter_classe.php");
include("classes/db_face_classe.php");
include("classes/db_carface_classe.php");
include("dbforms/db_funcoes.php");
$clcarface = new cl_carface;
$clface = new cl_face;
$clcaracter = new cl_caracter;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$db_opcao = 1;
$db_botao = true;
$clrotulo = new rotulocampo;
$clface->rotulo->label();
$clrotulo->label("j31_descr");

if(isset($faces)){
   $where58="j37_codigo=$j14_codigo and j37_face in($faces)";
   $result77  = $clcaracter->sql_record($clcaracter->sql_query_file("","j31_codigo as codigo,j31_descr","","j31_grupo=$j32_grupo"));
   $numrows77 = $clcaracter->numrows;
   $matriz = array();
   $matriz[0]="...";
   for($i=0; $i<$numrows77; $i++){
     db_fieldsmemory($result77,$i);
     $matriz[$codigo]=$j31_descr;
   }
}else{
  $selecionar=true;
   $where58="j37_codigo=$j14_codigo";
}

$result58  = $clface->sql_record($clface->sql_query_file("","j37_face,j37_setor,j37_quadra,j37_lado,j37_zona","",$where58));
$numrows58 = $clface->numrows;

if(isset($atualizar) && $atualizar!=""){
  $sqlerro=false;
  db_inicio_transacao();

  $matriz01 = split(",",$faces);
  for($i=0; $i<sizeof($matriz01); $i++){

    $x="caracter_".$matriz01[$i];
    if($$x!="0"){

      $result52=$clcarface->sql_record($clcarface->sql_query("","","j38_caract, j38_datalancamento","","j32_grupo=$j32_grupo and j38_face=".$matriz01[$i] ));
      if($clcarface->numrows>0){

	      db_fieldsmemory($result52,0);
        $clcarface->j38_caract=$j38_caract;
        $clcarface->j38_face=$matriz01[$i];
	      $clcarface->excluir($matriz01[$i],$j38_caract);
	      if($clcarface->erro_status==0){
	         $sqlerro=true;
	      }
      }

      $clcarface->j38_caract=$$x;
      $clcarface->j38_face=$matriz01[$i];

      /**
       * Caso haja alteraçao de caracteristica, alterar a data para a data atual do usuario
       * Senao, mantem a que ja estava
       */
      $oCarFace = db_utils::fieldsMemory($result52, 0);

      $clcarface->j38_datalancamento = date("Y-m-d", db_getsession("DB_datausu"));
      if ($oCarFace->j38_caract == $$x) {
        $clcarface->j38_datalancamento = $oCarFace->j38_datalancamento;
      }

      $clcarface->incluir($matriz01[$i],$$x);
      $erro_msg=$clcarface->erro_msg;
      if($clcarface->erro_status==0){
        $sqlerro=true;
      }
    }else{
      $result52=$clcarface->sql_record($clcarface->sql_query("","","j38_caract","","j32_grupo=$j32_grupo and j38_face=".$matriz01[$i] ));
      if($clcarface->numrows>0){
	db_fieldsmemory($result52,0);
        $clcarface->j38_caract=$j38_caract;
        $clcarface->j38_face=$matriz01[$i];
	$clcarface->excluir($matriz01[$i],$j38_caract);
        $erro_msg=$clcarface->erro_msg;
	if($clcarface->erro_status==0){
	  $sqlerro=true;
	}
      }
    }
  }
  db_fim_transacao($sqlerro);

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_trocar(obj){
  elem=document.form1.elements;
  for(i=0;i<elem.length;i++){
     nome = elem[i].name.substr(0,8);
    if(nome == 'caracter'){
       for(q=0; q<elem[i].options.length; q++){
	  if(elem[i].options[q].value==obj.value){
	    elem[i].options[q].selected=true;
	    break;
	  }
       }
    }
  }
}
function js_marca(obj){
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox'){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);
     }
   }
   return false;
}
function js_seleciona(){
   obj = document.form1;
   var face="";
   var virg="";
   for(i=0;i<obj.length;i++){
     if(obj.elements[i].type=='checkbox' && obj.elements[i].checked ){
       face += virg+obj.elements[i].name.substr(6);
         virg=",";
     }
   }
   document.form1.faces.value=face;
   document.form1.submit();
}
function js_atualizar(){
  document.form1.atualizar.value="ok";
  document.form1.submit();
}
</script>
<style>
a:hover {
color:blue;
}
a:visited {text-decoration:;
color: black;
font-weight: bold;
}
a:active {
color: black;
font-weight: bold;
}
.cabec {
text-align: center;
font-size: 10;
color: darkblue;
background-color:#aacccc ;
border-color: darkblue;
}
.corpo {
text-align: center;
font-size: 9;
color: black;
background-color:#ccddcc;
}

</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="cad4_carruas003.php">
<center>
<table border="1" width="100%" height="100%">
 <tr>
   <td  bgcolor="#cccccc" valign="top" align="center">
     <table border="1" >
<?
     db_input('j14_codigo',50,"",true,'hidden',3);
     db_input('j32_grupo',50,"",true,'hidden',3);
     db_input('faces',50,"",true,'hidden',3);
     db_input('atualizar',10,"",true,'hidden',3);
    if(isset($selecionar)){
?>

       <tr>
         <td title="Principal" colspan="5">
	   <b>Selecione as faces desejadas</b>
	 </td>
       </tr>

<?
    }else{
?>

       <tr>
         <td title="Principal" colspan="5">
	 <b>Característica principal </b>
	 <?
            db_select('j31_codigo',$matriz,true,2,"onchange='js_trocar(this);'");
         ?>
	 </td>
       </tr>

<?
    }
?>
       <tr class="cabec">
         <td title="<?=$Tj37_zona?>"><?=$RLj37_zona?></td>
         <td title="<?=$Tj37_setor?>"><?=$RLj37_setor?></td>
         <td title="<?=$Tj37_quadra?>"><?=$RLj37_quadra?></td>
         <td title="<?=$Tj37_face?>"><?=$RLj37_face?></td>
<?
    if(isset($selecionar)){
?>
       <td align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
<?
    }else{
?>
         <td title="<?=$Tj31_descr?>"><?=$RLj31_descr?></td>
<?
    }
?>
       </tr>
      <?
        for($i=0; $i<$numrows58; $i++){
	  db_fieldsmemory($result58,$i);
	  $result45=$clcarface->sql_record($clcarface->sql_query("","","j38_caract","","j32_grupo=$j32_grupo and j38_face=$j37_face "));
          if($clcarface->numrows>0){
            db_fieldsmemory($result45,0);
	    $x="caracter_$j37_face";
	    $$x=$j38_caract;
	  }
      ?>
       <tr class="corpo">
         <td title="<?=$Tj37_zona?>">&nbsp;<?=$j37_zona?></td>
         <td title="<?=$Tj37_setor?>">&nbsp;<?=$j37_setor?></td>
         <td title="<?=$Tj37_quadra?>">&nbsp;<?=$j37_quadra?></td>
         <td title="<?=$Tj37_face?>">&nbsp;<?=$j37_face?></td>
<?
    if(isset($selecionar)){
?>
       <td align='left'><input id='CHECK_<?=$j37_face?>' name='CHECK_<?=$j37_face?>'   checked type='checkbox'></td>
<?
    }else{
?>
         <td title="Principal">
	 <?

            db_select("caracter_$j37_face",$matriz,true,2);
         ?>
	 </td>
<?
    echo "
     <script>
        parent.document.form1.selecionar.style.visibility='hidden';
       parent.document.form1.atualizar.style.visibility='visible';
     </script>

    ";
    }
?>
       </tr>
      <?
        }
      ?>
     </table>
   </td>
  </tr>
</table>
</form>
</body>
</html>
<?
if(isset($atualizar) && $atualizar!=""){
  db_msgbox($erro_msg);
}
?>