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

//MODULO: saude
$clcgs->rotulo->label();
$clcgs_und->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd33_v_descricao");
$clrotulo->label("sd34_v_descricao");

$clrotulo->label("s200_codigo");
$clrotulo->label("s200_identificador");
$clrotulo->label("s200_descricao");



$escola = db_getsession("DB_coddepto");
?>
<form name="form1" method="post" action="" enctype="multipart/form-data">
<center>
<fieldset style="width:80%"><legend><b>Outros Dados</b></legend>
<table border="0" width="90%">
  <tr>
    <td colspan="4" height="18">&nbsp;</td>
  </tr>
 <tr>
  <td nowrap title="<?=@$Tz01_i_cgsund?>" width="10%">
   <?db_ancora(@$Lz01_i_cgsund,"",$db_opcao1);?>
  </td>
  <td colspan="4" width="60%">
   <?
   $z01vnome = $z01_i_cgsund .' - '.$z01_v_nome;
   db_input('z01vnome',83,$Iz01_v_nome,true,'text',$db_opcao1,"");
   db_input('localrecebefoto',6,0,true,'hidden',3,"");
   db_input('z01_i_cgsund',20,0,true,'hidden',3,"");
   db_input('z01_v_nome',60,$Iz01_v_nome,true,'hidden',3,"");
   ?>
  </td>
  <td nowrap="6" valign="top" align="left" title="<?=@$Trh50_oid?>" rowspan="7" id='fotofunc'>
   <?
     global $oid;
 
     if(trim($z01_i_cgsund) != "" && $z01_i_cgsund != null){
       $result_foto = db_query("select z01_o_oid as oid from cgs_und where z01_i_cgsund = $z01_i_cgsund");
       if(pg_numrows($result_foto) > 0){
          db_fieldsmemory($result_foto, 0);
       }
     }
     $mostrarimagem = "imagens/none1.jpeg";
     if($oid != null){
        $mostrarimagem = "func_mostrarimagem.php?oid=".$oid;
     }
     $href = "<img src='".$mostrarimagem."' border=0 width='95' height='120'>";
     db_ancora("$href","js_alterafoto();","$db_opcao");
  
   ?>
  </td>
 </tr>
   <SCRIPT LANGUAGE="JavaScript">
    team = new Array(
    <?
    # Seleciona todos os calend�rios
    $sql1 = "SELECT sd34_i_codigo,sd34_v_descricao
             FROM microarea
             ORDER BY sd34_v_descricao";
    $sql_result = db_query($sql1);
    $num = pg_num_rows($sql_result);
    $conta = "";
    while ($row=pg_fetch_array($sql_result)){
     $conta = $conta+1;
     $cod_micro = $row["sd34_i_codigo"];
     echo "new Array(\n";
     $sub_sql = "SELECT sd35_i_codigo,sd33_v_descricao
                 FROM familiamicroarea
                  inner join familia on sd33_i_codigo = sd35_i_familia
                 WHERE sd35_i_microarea = '$cod_micro'
                 ORDER BY sd33_v_descricao
                ";
     $sub_result = db_query($sub_sql);
     $num_sub = pg_num_rows($sub_result);
     if ($num_sub>=1){
      echo "new Array(\"\", ''),\n";
      $conta_sub = "";
      while ($rowx=pg_fetch_array($sub_result)){
       $codigo_fam=$rowx["sd35_i_codigo"];
       $nome_fam=$rowx["sd33_v_descricao"];
       $conta_sub=$conta_sub+1;
       if ($conta_sub==$num_sub){
        echo "new Array(\"$nome_fam\", $codigo_fam)\n";
        $conta_sub = "";
       }else{
        echo "new Array(\"$nome_fam\", $codigo_fam),\n";
       }
      }
     }else{
      echo "new Array(\"Microarea sem familias cadastradas.\", '')\n";
     }
     if ($num>$conta){
      echo "),\n";
     }
   }
   echo ")\n";
   echo ");\n";
   ?>
   //Inicio da fun��o JS
   function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem){
    var i, j;
    var prompt;
    // empty existing items
    for (i = selectCtrl.options.length; i >= 0; i--) {
     selectCtrl.options[i] = null;
    }
    prompt = (itemArray != null) ? goodPrompt : badPrompt;
    if (prompt == null) {
     selectCtrl.options[0] = new Option('','');
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
      <?if(isset($z01_i_familiamicroarea)&&$z01_i_familiamicroarea!=""){?>
       if(<?=trim($z01_i_familiamicroarea)?>==itemArray[i][1]){
        indice = i;
       }
      <?}?>
      j++;
     }
     <?if(isset($z01_i_familiamicroarea)&&$z01_i_familiamicroarea!=""){?>
      selectCtrl.options[indice].selected = true;
     <?}else{?>
      selectCtrl.options[0].selected = true;
     <?}?>
    }
   }
   </script>
 <tr>
  <td nowrap >
      <b>Micro:</b>&nbsp;&nbsp;&nbsp;
 </td>
  <td nowrap >
      <select name="z01_v_micro" onChange="fillSelectFromArray(this.form.z01_i_familiamicroarea, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:200px;height:18px;">
       <option></option>
       <?
       $sql1 = "SELECT sd34_i_codigo,sd34_v_descricao
               FROM microarea
               ORDER BY sd34_v_descricao";
       $sql_result = db_query($sql1);
       while($row=pg_fetch_array($sql_result)){
        $cod_micro=$row["sd34_i_codigo"];
        $desc_micro=$row["sd34_v_descricao"];
        ?>
        <option value="<?=$cod_micro;?>" <?=$cod_micro==@$sd34_i_codigo?"selected":""?>><?=$desc_micro;?></option>
        <?
       }
       ?>
      </select>
 </td>
  <td align="right" nowrap >
      <b>Fam�lia:</b>
 </td>
  <td nowrap >
      <select name="z01_i_familiamicroarea" style="font-size:9px;width:200px;height:18px;" onchange="if(this.value=='')document.form1.z01_v_micro.value='';">
       <option value=""></option>
      </select>
      <?if(isset($z01_i_familiamicroarea)&&$z01_i_familiamicroarea!=""){?>
       <script>fillSelectFromArray(document.form1.z01_i_familiamicroarea, team[document.form1.z01_v_micro.selectedIndex-1]);</script>
      <?}?>
    </td>
  </tr>
  <tr>
    <td>
     <?=@$Lz01_i_fatorrh?>
    </td>
    <td>
     <?
       $x = array('0'=>'','1'=>'POSITIVO','2'=>'NEGATIVO');
       db_select('z01_i_fatorrh',$x,true,$db_opcao,"");
     ?>
     <td align="right" >
     <?=@$Lz01_i_tiposangue?>
    </td>
    <td>
     <?
       $x = array('0'=>'','1'=>'A','2'=>'B','3'=>'O','4'=>'AB');
       db_select('z01_i_tiposangue',$x,true,$db_opcao,"");
     ?>
     </td>
    </td>
  </tr>
 </tr>
 <tr>
  <td nowrap title="Respons�vel:">
  <strong>Respons�vel:</strong> 
  </td>
  <td colspan="3" >
   <?db_input('z01_c_nomeresp',83,$Iz01_c_nomeresp,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td>
   <?=@$Lz01_c_raca?>
  </td>
  <td>
   <?
   $x = array('N�O DECLARADA'=>'N�O DECLARADA','BRANCA'=>'BRANCA','PRETA'=>'PRETA','PARDA'=>'PARDA','AMARELA'=>'AMARELA','IND�GENA'=>'IND�GENA','SEM INFORMACAO'=>'SEM INFORMACAO');
   db_select('z01_c_raca',$x,true,$db_opcao," onchange='js_validaRaca();'");
   ?>
  </td>
  <td align="right" >
   <?=@$Lz01_c_bolsafamilia?>
  </td>
  <td >
   <?
   $x = array('N'=>'N�O','S'=>'SIM');
   db_select('z01_c_bolsafamilia',$x,true,$db_opcao,"");
   ?>
   <!--<?=@$Lz01_c_passivo?>-->
   <?
   $x = array('N'=>'N�O','S'=>'SIM');
   db_select('z01_c_passivo',$x,true,$db_opcao," style='visibility:hidden;'");
   ?>
  </td>
 </tr>
 
 <tr id='selecionaEtnia' style="display: none;">
   <td nowrap="nowrap" >
    <?db_ancora('Etnia',"js_buscaEtnia();", $db_opcao);?>&nbsp;
   </td>
   <td nowrap>
    <?db_input('s200_codigo',        10, $Is200_codigo,        true, 'hidden', $db_opcao);
      db_input('s200_identificador', 10, $Is200_identificador, true, 'text',   3);
      db_input('s200_descricao',     40, $Is200_descricao,     true, 'text',   3);
    ?>
   </td>
 </tr>
 
 <tr>
  <td nowrap title="<?=@$Tz01_t_obs?>">
   <?=@$Lz01_t_obs?>
  </td>
  <td colspan="4">
   <?db_textarea('z01_t_obs',2,80,$Iz01_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
  <tr>
    <td colspan="4" height="18">&nbsp;</td>
  </tr>
</table>
</fieldset>
</center>
<input name="alterar" type="submit" value="<?=$db_value?>" <?=($db_botao==false?"disabled":"")?>>
<?
if( isset( $retornacgs ) ){
	echo "<input name='fechar' type='submit' value='Fechar''";
}
?>

</form>

<script>

function js_alterafoto(){
  js_OpenJanelaIframe('','db_iframe_localfoto','func_localfoto.php','Foto do funcion�rio',true,0);
}
function js_cartaosus(){
  if( document.form1.z01_c_cartaosus.value != '' ){
    document.form1.z01_c_cartaosus.value = preenche( document.form1.z01_c_cartaosus.value, '0', 16, 'l' );
  }
}

function js_pesquisasd35_i_familiamicroarea(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_familiamicroarea','func_familiamicroarea.php?funcao_js=parent.js_mostrafamiliamicroarea1|sd35_i_codigo|sd33_v_descricao|sd34_v_descricao','Pesquisa',true);
  }else{
     if(document.form1.sd35_i_familia.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_familia','func_familia.php?pesquisa_chave='+document.form1.z01_i_familiamicroarea.value+'&funcao_js=parent.js_mostrafamiliamicroarea','Pesquisa',false );
     }else{
       document.form1.z01_i_familiamicroarea.value = '';
     }
  }
}
function js_mostrafamiliamicroarea(chave,erro){
  document.form1.sd33_v_descricao.value = chave;
  if(erro==true){
    document.form1.z01_i_familiamicroarea.focus();
    document.form1.z01_i_familiamicroarea.value = '';
  }
}
function js_mostrafamiliamicroarea1(chave1,chave2,chave3){
  document.form1.z01_i_familiamicroarea.value = chave1;
  document.form1.sd33_v_descricao.value = chave2;
  document.form1.sd34_v_descricao.value = chave3;
  db_iframe_familiamicroarea.hide();
}


function js_naturalidade(){
  js_OpenJanelaIframe('','db_iframe_ceplocalidades','func_ceplocalidades.php?funcao_js=parent.js_preenchepesquisanaturalidade|cp05_sigla|cp05_localidades','Pesquisa',true);
 }
 function js_preenchepesquisanaturalidade(chave,chave1){
   document.form1.z01_c_naturalidade.value = chave1;
   db_iframe_ceplocalidades.hide();
 }


function js_transporte(transporte){
 if(transporte==""){
  document.form1.z01_c_zona.value="";
 }
}
function js_transporte1(transporte,zona){
 if(transporte==""){
  document.form1.z01_c_zona.value="";
 }
 if(zona==""){
  document.form1.z01_c_transporte.value="";
 }
}
function js_novo(){
 parent.location="edu1_cgs_undabas001.php";
}



function js_validaRaca() {

  if ($F('z01_c_raca') == 'IND�GENA') {
    $('selecionaEtnia').style.display = 'table-row';
  } else {
    $('selecionaEtnia').style.display = 'none';
  }
}

function js_buscaEtnia() {

  var sURL  = 'func_etnia.php?';
      sURL += 'funcao_js=parent.js_mostraEtnia|s200_codigo|s200_identificador|s200_descricao';
  js_OpenJanelaIframe('', 'db_iframe_etnia', sURL, 'Pesquisa Etnia', true);
}

function js_mostraEtnia(iCodigo, iIdentificador, sDescricao) {
  
  $('s200_codigo').value        = iCodigo;
  $('s200_identificador').value = iIdentificador;
  $('s200_descricao').value     = sDescricao;
  db_iframe_etnia.hide();
}
js_validaRaca();

</script>