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

//MODULO: educação
$clserieequiv->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed11_i_codigo");
$clrotulo->label("ed11_i_codigo");
?>
<script>
 team = new Array(
 <?
 #Seleciona todos os calendários
 $sql_result = $clensino->sql_record($clensino->sql_query("","ed10_i_codigo,ed10_c_descr","ed10_c_abrev",""));
 $num = pg_num_rows($sql_result);
 $conta = "";
 while ($row=pg_fetch_array($sql_result)){
  $conta = $conta+1;
  $cod_curso = $row["ed10_i_codigo"];
  echo "new Array(\n";
  $sub_result = $clserie->sql_record($clserie->sql_query("","ed11_i_codigo,ed11_c_descr","ed11_i_sequencia"," ed11_i_ensino = '$cod_curso'"));
  $num_sub = !$sub_result ? 0 : pg_num_rows($sub_result);
  if ($num_sub>=1){
   echo "new Array(\"\", ''),\n";
   $conta_sub = "";
   while ($rowx=pg_fetch_array($sub_result)){
    $codigo_base=$rowx["ed11_i_codigo"];
    $base_nome=$rowx["ed11_c_descr"];
    $conta_sub=$conta_sub+1;
    if ($conta_sub==$num_sub){
     echo "new Array(\"$base_nome\", $codigo_base)\n";
     $conta_sub = "";
    }else{
     echo "new Array(\"$base_nome\", $codigo_base),\n";
    }
   }
  }else{
   echo "new Array(\"Ensino sem etapas cadastradas\", '')\n";
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
  document.form1.subgrupo.disabled = false;
 }
}
</script>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td>
   <table border="0" align="left">
    </tr>
     <td>
      <b>Selecione o nível de ensino:</b><br>
      <select name="grupo" onChange="js_zerar();fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:250px;height:18px;">
       <option></option>
       <?
       $sql_result = $clensino->sql_record($clensino->sql_query("","ed10_i_codigo,ed10_c_descr","ed10_c_abrev",""));
       while($row=pg_fetch_array($sql_result)){
        $cod_curso=$row["ed10_i_codigo"];
        $desc_curso=$row["ed10_c_descr"];
        ?>
        <option value="<?=$cod_curso;?>" <?=$cod_curso==@$ensino?"selected":""?>><?=$desc_curso;?></option>
        <?
       }
       ?>
      </select>
     </td>
     <td>
      <b>Selecione a Etapa:</b><br>
      <select name="subgrupo" style="font-size:9px;width:200px;height:18px;" disabled onchange="js_procurar(document.form1.grupo.value,document.form1.subgrupo.value)">
       <option value=""></option>
      </select>
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <?if(isset($serie)){?>
  <tr>
   <td colspan="2" align="center">
    <?
    $result = $clserie->sql_record($clserie->sql_query_equiv("","*","ed10_i_codigo,ed11_i_sequencia"," ed11_i_ensino != $ensino OR (ed11_i_ensino = $ensino AND ed11_i_codigo != $serie)"));
    ?>
    <b>Etapas equivalentes a <span id="nomeserie"></span> em outros ensinos:</b><br>
    <select name="serieequiv[]" id="serieequiv" style="font-size:9px;width:400px;height:350px" multiple>
     <?
     for($x=0;$x<$clserie->numrows;$x++){
      db_fieldsmemory($result,$x);
      $result2 = $clserieequiv->sql_record($clserieequiv->sql_query_file("","ed234_i_serie",""," ed234_i_serie = $serie AND ed234_i_serieequiv = $ed11_i_codigo"));
      if($clserieequiv->numrows>0){
       $selected = "selected";
      }else{
       $selected = "";
      }
      ?>
      <option value="<?=$ed11_i_codigo?>" <?=$selected?>><?=$ed10_c_descr?> - <?=$ed11_c_descr?></option>
      <?
     }
     ?>
    </select>
    <?
    ?>
   </td>
  </tr>
  <tr>
   <td colspan="2" align="center">
    <br>
    <input type="submit" value="Salvar" name="salvar">
    <input type="button" value="Limpar" name="limpar" onclick="js_limpar();">
    <input type="button" value="Ver Quadro Geral" name="geral" onclick="js_geral();">
    <input type="hidden" value="<?=$serie?>" name="serie">
    <input type="hidden" value="<?=$ensino?>" name="ensino">
   </td>
  </tr>
  <script>
  fillSelectFromArray(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));
  document.form1.subgrupo.value = <?=$serie?>;
  document.getElementById("nomeserie").innerHTML = document.form1.subgrupo[document.form1.subgrupo.selectedIndex].text+" - "+document.form1.grupo[document.form1.grupo.selectedIndex].text;
  </script>
  <tr>
   <td colspan="2" align="center">
    <fieldset style="width:400px;align:center">
    Para selecionar mais de uma série<br>mantenha pressionada a tecla CTRL <br>e clique sobre os nomes das séries.
    </fieldset>
   </td>
  </tr>
 <?}?>
</table>
</center>
</form>
<script>
function js_procurar(ensino,serie){
 if(serie!=""){
  location.href = "edu1_serieequiv001.php?ensino="+ensino+"&serie="+serie;
 }else{
  location.href = "edu1_serieequiv001.php?ensino="+ensino;
 }
}
function js_limpar(){
 tam = document.form1.serieequiv.length;
 for(i=0;i<tam;i++){
  document.form1.serieequiv[i].selected = false;
 }
}
function js_zerar(){
 <?if(isset($serie)){?>
 qtd = document.form1.serieequiv.length;
 for (i = 0; i < qtd; i++) {
  document.form1.serieequiv.options[0] = null;
 }
 document.getElementById("nomeserie").innerHTML = "_________";
 <?}?>
}
function js_geral(){
 js_OpenJanelaIframe('','db_iframe_geral','func_serieequiv.php','Quadro Geral de Etapas Equivalentes',true);
}
</script>