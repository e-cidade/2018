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

//MODULO: saude

$clprontuariomedico->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_i_numcgs");
$clrotulo->label("sd03_i_crm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_v_nome");
$clrotulo->label("sd33_v_descricao");
$clrotulo->label("sd34_v_descricao");
$clrotulo->label("z01_i_familiamicroarea");
$clrotulo->label("sd35_i_microarea");
$clrotulo->label("sd35_i_familia");
?>
   <SCRIPT LANGUAGE="JavaScript">
    team = new Array(
    <?
    # Seleciona todos os calendï¿½rios
    $sql1 = "SELECT sd34_i_codigo,sd34_v_descricao
             FROM microarea
             ORDER BY sd34_v_descricao";
    $sql_result = pg_query($sql1);
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
     $sub_result = pg_query($sub_sql);
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
   //Inicio da funï¿½ï¿½o JS
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

<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd32_i_codigo?>">
       <?=@$Lsd32_i_codigo?>
    </td>
    <td> 
<?
db_input('sd32_i_codigo',10,$Isd32_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd32_i_unidade?>">
       <?db_ancora(@$Lsd32_i_unidade,"js_pesquisasd32_i_unidade(true);",$db_opcao);?>
    </td>
    <td> 
<?
//     $sd32_i_unidade = db_getsession("DB_coddepto");
//     db_input('sd32_i_unidade',10,$Isd32_i_unidade,true,'text',3,"");
//     $descrdepto=db_getsession("DB_nomedepto");
     db_input('sd32_i_unidade',10,$Isd32_i_unidade,true,'text',$db_opcao," onchange='js_pesquisasd32_i_unidade(false);'");
     @db_input('descrdepto',60,$Idescrdepto,true,'text',3,"");

?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd32_i_numcgs?>">
       <?
       db_ancora(@$Lsd32_i_numcgs,"js_pesquisasd32_i_numcgs(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd32_i_numcgs',10,$Isd32_i_numcgs,true,'text',3," onchange='js_pesquisasd32_i_numcgs(false);'")
?>
      <?
        db_input('z01_v_nome',60,$Iz01_v_nome,true,'text',3,'')
      ?>
      <!--input name="novo_cgs" type="button" id="novo_cgs" value="Novo CGS" <?=($db_botao1==true?"disabled":"")?> onclick="js_novo_cgs();" -->
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd35_i_microarea?>">
     <?//db_ancora(@$Lz01_i_familiamicroarea,"js_pesquisasd35_i_familiamicroarea(true);",$db_opcao);?>
     <?=@$Lsd35_i_microarea?>
    </td>
    <td>
      <select name="z01_v_micro" onChange="fillSelectFromArray(this.form.z01_i_familiamicroarea, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:200px;height:18px;">
       <option></option>
       <?
       $sql1 = "SELECT sd34_i_codigo,sd34_v_descricao
               FROM microarea
               ORDER BY sd34_v_descricao";
       $sql_result = pg_query($sql1);
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
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd35_i_familia?>">
     <?=@$Lsd35_i_familia?>
    </td>
    <td>
      <select name="z01_i_familiamicroarea" style="font-size:9px;width:200px;height:18px;" onchange="if(this.value=='')document.form1.z01_v_micro.value='';">
       <option value=""></option>
      </select>
      <?if(isset($z01_i_familiamicroarea)&&$z01_i_familiamicroarea!=""){?>
       <script>fillSelectFromArray(document.form1.z01_i_familiamicroarea, team[document.form1.z01_v_micro.selectedIndex-1]);</script>
      <?}?>
    </td>
  </tr>


  <tr>
    <td nowrap title="<?=@$Tsd32_i_medico?>">
       <?
       db_ancora(@$Lsd32_i_medico,"js_pesquisasd32_i_medico(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd32_i_medico',10,$Isd32_i_medico,true,'text',$db_opcao," onchange='js_pesquisasd32_i_medico(false);'")
?>
       <?
db_input('z01_nome',60,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>




  <tr>
    <td nowrap title="<?=@$Tsd32_d_atendimento?>">
       <?=@$Lsd32_d_atendimento?>
    </td>
    <td> 
<?
db_inputdata('sd32_d_atendimento',@$sd32_d_atendimento_dia,@$sd32_d_atendimento_mes,@$sd32_d_atendimento_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd32_c_horaatend?>">
       <?=@$Lsd32_c_horaatend?>
    </td>
    <td> 
<?
db_input('sd32_c_horaatend',5,$Isd32_c_horaatend,true,'text',$db_opcao,"OnKeyUp=mascara_hora(this.value,'sd32_c_horaatend')")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd32_t_descricao?>">
       <?=@$Lsd32_t_descricao?>
    </td>
    <td> 
       <?
         $sd32_t_descricao=!isset($sd32_t_descricao)?' ':$sd32_t_descricao;
         db_textarea('sd32_t_descricao',3,75,@$sd32_t_descricao,true,'text',$db_opcao," style='text-transform:uppercase;'
          onKeyDown='textCounter(document.form1.sd32_t_descricao,document.form1.remLen1,3000)'
          onKeyUp='textCounter(document.form1.sd32_t_descricao,document.form1.remLen1,3000)'
         ");
       ?>
       <input readonly type="text" name="remLen1" size="4" maxlength="4" value="3000">
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="button" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> onclick="location.href='sau1_prontuariomedico001.php?chavepesquisaprontuario=<?=$sd32_i_numcgs?>&z01_v_nome=<?=$z01_v_nome?>&z01_i_familiamicroarea=<?=$z01_i_familiamicroarea?>'">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" <?=($db_botao1==true?"disabled":"")?> onclick="js_pesquisa();//js_pesquisasd32_i_numcgs(true);" >
<input name="relatorio" type="button" id="relatorio" value="Prontuário Médico" <?=($db_botao1==true?"disabled":"")?> onclick="js_relatorio();" >
</form>
<script>

function textCounter(field,cntfield,maxlimit) {
     if (field.value.length > maxlimit){
          //alert('Foi atingido o máximo de ('+maslimit+') caracteres do prontuário.' );
          field.value = field.value.substring(0, maxlimit);
     }else
          cntfield.value = maxlimit - field.value.length;
}



function js_retorna13(){
  if( event.keyCode==13 ){
     return false;
  }
  //alert( event.keyCode );
}

function js_pesquisasd35_i_familiamicroarea(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_familiamicroarea','func_familiamicroarea.php?funcao_js=parent.js_mostrafamiliamicroarea1|sd35_i_codigo|sd33_v_descricao|sd34_v_descricao','Pesquisa',true);
  }else{
     if(document.form1.z01_i_familiamicroarea.value != ''){
        js_OpenJanelaIframe('','db_iframe_familiamicroarea','func_familiamicroarea.php?pesquisa_chave='+document.form1.z01_i_familiamicroarea.value+'&funcao_js=parent.js_mostrafamiliamicroarea','Pesquisa',false );
     }else{
       document.form1.z01_i_familiamicroarea.value = '';
       document.form1.sd33_v_descricao.value = '';
       document.form1.sd34_v_descricao.value = '';
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


function js_pesquisasd32_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.sd32_i_unidade.value != ''){
        js_OpenJanelaIframe('','db_iframe_unidades','func_unidades.php?pesquisa_chave='+document.form1.sd32_i_unidade.value+'&funcao_js=parent.js_mostraunidades','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = '';
     }
  }
}
function js_mostraunidades(chave,erro){
  document.form1.descrdepto.value = chave;
  if(erro==true){
    document.form1.sd32_i_unidade.focus();
    document.form1.sd32_i_unidade.value = '';
  }
}
function js_mostraunidades1(chave1,chave2){
  document.form1.sd32_i_unidade.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_unidades.hide();
}

function js_novo_cgs(){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgs_und','sau1_cgs_und000.php?id=1&db_menu=false','CGS',true);
}




function js_pesquisasd32_i_numcgs(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_mostracgs1|z01_i_cgsund|z01_v_nome|z01_i_familiamicroarea|sd33_v_descricao|sd34_v_descricao&retornacgs=p.p.document.form1.sd32_i_numcgs.value&retornanome=p.p.document.form1.z01_v_nome.value','Pesquisa',true);
    //js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_preenchecgs|z01_i_cgsund','Pesquisa',true);
  }else{
     if(document.form1.sd32_i_numcgs.value != ''){ 
        //js_OpenJanelaIframe('top.corpo','db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+document.form1.sd32_i_numcgs.value+'&funcao_js=parent.js_mostracgs','Pesquisa',true);
        js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+document.form1.z01_i_cgsund.value+'&funcao_js=parent.js_preenchecgs|z01_i_cgsund','Pesquisa',false);
     }else{
       document.form1.z01_v_nome.value = '';
     }
  }
}
function js_mostracgs(chave,erro){
  document.form1.z01_v_nome.value = chave;
  if(erro==true){ 
    document.form1.sd32_i_numcgs.focus(); 
    document.form1.sd32_i_numcgs.value = '';
  }else{
     location.href='sau1_prontuariomedico001.php?chavepesquisaprontuario='+document.form1.sd32_i_numcgs.value+'&z01_v_nome='+document.form1.z01_v_nome.value;
  }
}
function js_mostracgs1(chave1,chave2,chave3,chave4,chave5){

  document.form1.sd32_i_numcgs.value = chave1;
  document.form1.z01_v_nome.value = chave2;
  //document.form1.z01_i_familiamicroarea.value = chave3;
  //document.form1.sd33_v_descricao.value = chave4;
  //document.form1.sd34_v_descricao.value = chave5;
  db_iframe_cgs_und.hide();
  location.href='sau1_prontuariomedico001.php?chavepesquisaprontuario='+document.form1.sd32_i_numcgs.value+
                '&z01_v_nome='+document.form1.z01_v_nome.value+
                '&z01_i_familiamicroarea='+chave3+
                '&sd33_v_descricao='+chave4+
                '&sd34_v_descricao='+chave5;
}

function js_pesquisasd32_i_medico(mostra){
  if(mostra==true){
    //js_OpenJanelaIframe('top.corpo','db_iframe_unidademedicos','func_unidademedicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome','Pesquisa',true);
    js_OpenJanelaIframe('top.corpo','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.sd32_i_medico.value != ''){ 
        //js_OpenJanelaIframe('top.corpo','db_iframe_unidademedicos','func_unidademedicos.php?pesquisa_chave='+document.form1.sd32_i_medico.value+'&funcao_js=parent.js_mostramedicos','Pesquisa',false);
        js_OpenJanelaIframe('top.corpo','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.sd32_i_medico.value+'&funcao_js=parent.js_mostramedicos','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostramedicos(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){ 
    document.form1.sd32_i_medico.focus(); 
    document.form1.sd32_i_medico.value = ''; 
  }
}
function js_mostramedicos1(chave1,chave2){
  document.form1.sd32_i_medico.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_medicos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_prontuariomedico','func_prontuariomedico.php?funcao_js=parent.js_preenchepesquisa|z01_i_cgsund|z01_v_nome|z01_i_familiamicroarea','Pesquisa',true);
}
function js_relatorio(){
  if( document.form1.sd32_i_numcgs.value != '' ){
    // window.open('sau4_prontuariomedico002.php?cgs='+document.form1.sd32_i_numcgs.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
     window.open('sau4_prontuariomedico003.php?cgs='+document.form1.sd32_i_numcgs.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }else{
     alert('Deverá informar um CGS.' );
  }
}
function js_preenchepesquisa(chave1, chave2, chave3){
	//alert(chave3);
  db_iframe_prontuariomedico.hide();
  location.href='sau1_prontuariomedico001.php?chavepesquisaprontuario='+chave1+'&z01_v_nome='+chave2+'&z01_i_familiamicroarea='+chave3;

  <?
  //if($db_opcao!=1){
  //  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  //}
  ?>

}
</script>