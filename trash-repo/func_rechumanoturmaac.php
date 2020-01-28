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
include("classes/db_atividaderh_classe.php");
include("classes/db_ensino_classe.php");
include("classes/db_disciplina_classe.php");
include ("classes/db_rechumanoturmaac_ext_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clatividaderh = new cl_atividaderh;
$clensino = new cl_ensino;
$cldisciplina = new cl_disciplina;
$clrechumano = new cl_rechumanoturmaac_ext;
$clrotulo = new rotulocampo;
$clrechumano->rotulo->label("ed20_i_codigo");
$clrotulo->label("z01_nome");
$clrotulo->label("ed12_i_ensino");
$clrotulo->label("ed23_i_disciplina");
$escola = db_getsession("DB_coddepto");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
 function submete(valor,ativ) {
  location.href = "func_rechumanoturmaac.php?funcao_js=parent.js_preenchepesquisa|ed20_i_codigo&ensino="+valor+"&ativ="+ativ;
 }
 function atividade(valor){
  regencias = document.form1.regente.value.split("|");
  tamanho = regencias.length;
  for(i = 0; i<tamanho; i++){
   if(valor==regencias[i]){
    document.form1.grupo.disabled = false;
    document.form1.subgrupo.disabled = false;
    fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));
    break;
   }else{
    document.form1.grupo.disabled = true;
    document.form1.subgrupo.disabled = true;
    document.form1.grupo.value = "";
    document.form1.subgrupo.value = "";
   }
  }
 }
 team = new Array(
 <?
 # Seleciona todos os calendários
 $sql = "SELECT DISTINCT ed10_i_codigo,ed10_c_descr,ed10_c_abrev
         FROM ensino
          inner join disciplina on ed12_i_ensino = ed10_i_codigo
          inner join relacaotrabalho on ed23_i_disciplina = ed12_i_codigo
          inner join rechumanoescola on ed75_i_codigo = ed23_i_rechumanoescola
         WHERE ed75_i_escola = $escola
         ORDER BY ed10_c_abrev";
 $sql_result = pg_query($sql);
 $num = pg_num_rows($sql_result);
 $conta = "";
 while ($row=pg_fetch_array($sql_result)){
  $conta = $conta+1;
  $cod_curso = $row["ed10_i_codigo"];
  echo "new Array(\n";
  $sub_sql = "SELECT DISTINCT ed12_i_codigo,ed232_c_descr
              FROM disciplina
               inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina
               inner join relacaotrabalho on ed23_i_disciplina = ed12_i_codigo
               inner join rechumanoescola on ed75_i_codigo = ed23_i_rechumanoescola
              WHERE ed12_i_ensino = $cod_curso
              AND ed75_i_escola = $escola
              ORDER BY ed59_i_ordenacao
             ";
  $sub_result = pg_query($sub_sql);
  $num_sub = pg_num_rows($sub_result);
  if ($num_sub>=1){
   # Se achar alguma base para o curso, marca a palavra Todas
   echo "new Array(\"\", ''),\n";
   $conta_sub = "";
   while ($rowx=pg_fetch_array($sub_result)){
    $codigo_base=$rowx["ed12_i_codigo"];
    $base_nome=$rowx["ed232_c_descr"];
    $conta_sub=$conta_sub+1;
    if ($conta_sub==$num_sub){
     echo "new Array(\"$base_nome\", $codigo_base)\n";
     $conta_sub = "";
    }else{
     echo "new Array(\"$base_nome\", $codigo_base),\n";
    }
   }
  }else{
   #Se nao achar base para o curso selecionado...
   echo "new Array(\"Ensino sem disciplinas.\", '')\n";
  }
  if ($num>$conta){
   echo "),\n";
  }
}
echo ")\n";
echo ");\n";
?>
//Inicio da função JS
function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem){
 var i, j;
 var prompt;
 // empty existing items
 for (i = selectCtrl.options.length; i >= 0; i--) {
  selectCtrl.options[i] = null;
 }
 prompt = (itemArray != null) ? goodPrompt : badPrompt;
 if (prompt == null) {
  document.form1.subgrupo.disabled = true;
  j = 0;
 }else{
  selectCtrl.options[0] = new Option(prompt);
  j = 1;
 }
 if (itemArray != null) {
  // add new items
  for (i = 0; i < itemArray.length; i++){
   selectCtrl.options[j] = new Option(itemArray[i][0]);
   if (itemArray[i][1] != null){
    selectCtrl.options[j].value = itemArray[i][1];
   }
   j++;
  }
  selectCtrl.options[0].selected = true;
  document.form1.subgrupo.disabled = false;
 }
}
function fillSelectFromArray2(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem){
 var i, j;
 var prompt;
 // empty existing items
 for (i = selectCtrl.options.length; i >= 0; i--) {
  selectCtrl.options[i] = null;
 }
 prompt = (itemArray != null) ? goodPrompt : badPrompt;
 if (prompt == null) {
  document.form1.subgrupo.disabled = true;
  j = 0;
 }else{
  selectCtrl.options[0] = new Option(prompt);
  j = 1;
 }
 if (itemArray != null) {
  // add new items
  for (i = 0; i < itemArray.length; i++){
   selectCtrl.options[j] = new Option(itemArray[i][0]);
   if (itemArray[i][1] != null){
    selectCtrl.options[j].value = itemArray[i][1];
   }
   <?if(isset($subgrupo)){?>
    if(<?=trim($subgrupo)?>==itemArray[i][1]){
     indice = i;
    }
   <?}?>
   j++;
  }
  <?if(isset($subgrupo)){?>
   selectCtrl.options[indice].selected = true;
  <?}else{?>
   selectCtrl.options[0].selected = true;
  <?}?>
  document.form1.subgrupo.disabled = false;
 }
}
//End -->
</script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" align="center" valign="top">
   <table width="750" border="0" align="center" cellspacing="0">
    <form name="form1" method="post" action="" >
    <tr>
     <td nowrap title="<?=$Ted20_i_codigo?>">
      <?=$Led20_i_codigo?>
      <?db_input("ed20_i_codigo",10,$Ied20_i_codigo,true,"text",4,"","chave_ed20_i_codigo");?>
     <td colspan="2">
      <?=$Lz01_nome?>
      <?db_input("z01_nome",50,$Iz01_nome,true,"text",4,"","chave_z01_nome");?>
     </td>
    </tr>
    <tr>
     <td width="33%" nowrap>
      <b>Atividade:</b>
      <?
      $result_ativ = $clatividaderh->sql_record($clatividaderh->sql_query_file("","ed01_i_codigo,ed01_c_descr","ed01_c_descr"));
      $linhas_ativ = pg_num_rows($result_ativ);
      ?>
      <select name="atividaderh" id="atividaderh" onchange="atividade(this.value)" style="font-size:10px;width:150px">
       <option value='' selected></option>
       <?
        for($x=0;$x<$linhas_ativ;$x++){
         db_fieldsmemory($result_ativ,$x);
         echo "<option value='$ed01_i_codigo' ".(@$atividaderh==$ed01_i_codigo?"selected":"").">$ed01_c_descr</option>";
        }
       ?>
      </select>
     </td>
     <td width="34%" nowrap>
      <?=$Led12_i_ensino?>
      <select name="grupo" onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:200px;height:18px;">
       <option></option>
       <?
       #Seleciona todos os grupos para setar os valores no combo
       $sql = "SELECT DISTINCT ed10_i_codigo,ed10_c_descr,ed10_c_abrev
               FROM ensino
                inner join disciplina on ed12_i_ensino = ed10_i_codigo
                inner join relacaotrabalho on ed23_i_disciplina = ed12_i_codigo
                inner join rechumanoescola on ed75_i_codigo = ed23_i_rechumanoescola
               WHERE ed75_i_escola = $escola
               ORDER BY ed10_c_abrev";
       $sql_result = pg_query($sql);
       while($row=pg_fetch_array($sql_result)){
        $cod_curso=$row["ed10_i_codigo"];
        $desc_curso=$row["ed10_c_descr"];
        ?>
        <option value="<?=$cod_curso;?>" <?=$cod_curso==@$grupo?"selected":""?>><?=$desc_curso;?></option>
        <?
       }
       #Popula o segundo combo de acordo com a escolha no primeiro
       ?>
      </select>
     </td>
     <td width="33%" nowrap>
      <?=$Led23_i_disciplina?>
      <select name="subgrupo" style="font-size:9px;width:200px;height:18px;" disabled>
       <option value=""></option>
      </select>
      <?if(isset($subgrupo)){?>
       <script>fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));</script>
      <?}?>
     </td>
    </tr>
    <tr>
     <td align="center" colspan="3">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rechumanoturmaac.hide();">
      <input name="regente" type="hidden" value="">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <?
 $result_regente = $clatividaderh->sql_record($clatividaderh->sql_query_file("","ed01_i_codigo as reg","ed01_c_descr"," ed01_c_regencia = 'S'"));
 if($clatividaderh->numrows>0){
  $sep = "";
  $regencias = "";
  for($x=0;$x<$clatividaderh->numrows;$x++){
   db_fieldsmemory($result_regente,$x);
   $regencias .= $sep.$reg;
   $sep = "|";
  }
 }else{
  $regencias = 0;
 }
 ?>
 <script>
  document.form1.regente.value = "<?=$regencias?>";
  <?if(!isset($grupo)){?>
   document.form1.grupo.disabled = true;
   document.form1.subgrupo.disabled = true;
  <?}?>
  <?if(isset($ativ)){?>
   document.form1.atividaderh.value = <?=$ativ?>;
  <?}?>
  js_tabulacaoforms("form1","chave_ed20_i_codigo",true,1,"chave_ed20_i_codigo",true);
 </script>
 <tr>
  <td align="center" valign="top">
   <?
   $escola = db_getsession("DB_coddepto");
   if(!isset($pesquisa_chave)){
    $campos = "ed20_i_codigo,
               z01_nome,
               z01_cgccpf,
               rh37_descr
              ";
    $where = "";
    if(isset($chave_ed20_i_codigo) && (trim($chave_ed20_i_codigo)!="") ){
     $where .= " AND ed20_i_codigo = $chave_ed20_i_codigo";
    }
    if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
     $where .= " AND z01_nome like '$chave_z01_nome%'";
    }
    if(isset($atividaderh) && (trim($atividaderh)!="") ){
     $where .= " AND ed22_i_atividade = $atividaderh";
    }
    if(isset($grupo) && (trim($grupo)!="") ){
     $where .= " AND ed12_i_ensino = $grupo";
    }
    if(isset($subgrupo) && (trim($subgrupo)!="") ){
     $where .= " AND ed23_i_disciplina = $subgrupo";
    }    
    $sql = $clrechumano->sql_query_ext(""," distinct ".$campos,"z01_nome"," ed75_i_escola = $escola AND ed01_c_regencia = 'S' AND ed17_i_escola = $escola".$where);
    $repassa = array();
    if(isset($chave_ed20_i_codigo)){
     $repassa = array("chave_ed20_i_codigo"=>$chave_ed20_i_codigo,"chave_z01_nome"=>$chave_z01_nome,"subgrupo"=>@$subgrupo,"grupo"=>@$grupo,"atividaderh"=>@$atividaderh);
     db_lovrot(@$sql,15,"()","",$funcao_js,"","NoMe",$repassa);
    }

   }else{
    if($pesquisa_chave!=null && $pesquisa_chave!=""){
     $campos = "ed20_i_codigo,
                z01_nome,
                z01_cgccpf,
                rh37_descr
               ";     
      $result = $clrechumano->sql_record($clrechumano->sql_query_ext("",$campos,"z01_nome"," ed20_i_codigo = $pesquisa_chave AND ed75_i_escola = $escola"));
     if($clrechumano->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$z01_nome',false);</script>";
     }else{
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
     }
    }else{
     echo "<script>".$funcao_js."('',false);</script>";
    }
   }
   ?>
   </td>
  </tr>
</table>
</body>
</html>