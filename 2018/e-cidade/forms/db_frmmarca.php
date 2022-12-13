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

//MODULO: marcas
$clmarca->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("ma04_c_descr");

?>
<form name="form1" method="post" enctype="multipart/form-data">
<center>
 <table border="0" width="98%">
  <tr>
   <td nowrap title="<?=@$Tma01_i_codigo?>" width="20%">
    <?=@$Lma01_i_codigo?>
   </td>
   <td>
    <?db_input('ma01_i_codigo',10,$Ima01_i_codigo,true,'text',3,"")?>
   </td>
   <td rowspan="6" width="235">
    <iframe name="frame_imagem" id="frame_imagem" src="mar4_mostraimagem.php" width="230" height="200" frameborder="0"></iframe>
    <?
    if((isset($chavepesquisa) || isset($alterar)) && isset($ma01_c_nomeimagem)){
     if($ma01_o_imagem!=0){
      $arquivo = "tmp/".$ma01_c_nomeimagem;
      pg_exec("begin");
      pg_loexport($ma01_o_imagem,$arquivo);
      pg_exec("end");
     }else{
      $arquivo = "imagens/semmarca.jpg";
     }
    ?>
    <script>
     frame_imagem.location.href="mar4_mostraimagem.php?imagem_gerada=<?=$arquivo?>";
    </script>
    <?}?>
   </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tma01_i_cgm?>">
     <?db_ancora(@$Lma01_i_cgm,"js_pesquisama01_i_cgm(true);",$db_opcao==2?3:$db_opcao);?>
    </td>
    <td>
     <?db_input('ma01_i_cgm',10,$Ima01_i_cgm,true,'text',$db_opcao," onchange='js_pesquisama01_i_cgm(false);'")?>
     <?db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')?>
    </td>
   </tr>
   <!--
   <tr>
    <td nowrap title="<?=@$Tma01_i_localmarca?>">
     <?db_ancora(@$Lma01_i_localmarca,"js_pesquisama01_i_localmarca(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('ma01_i_localmarca',10,$Ima01_i_localmarca,true,'text',$db_opcao," onchange='js_pesquisama01_i_localmarca(false);'")?>
     <?db_input('ma04_c_descr',40,$Ima04_c_descr,true,'text',3,'')?>
    </td>
   </tr>
   -->
   <tr>
    <td nowrap title="<?=@$Tma01_d_data?>">
     <?=@$Lma01_d_data?>
    </td>
    <td> 
     <?db_inputdata('ma01_d_data',@$ma01_d_data_dia,@$ma01_d_data_mes,@$ma01_d_data_ano,true,'text',$db_opcao,"")?>
    </td>
   </tr>
   <tr>
    <td nowrap title="<?=@$Tma01_i_livro?>">
     <?=@$Lma01_i_livro?>
    </td>
    <td>
     <?db_input('ma01_i_livro',10,$Ima01_i_livro,true,'text',$db_opcao,"")?>
    </td>
   </tr>
   <tr>
    <td nowrap title="<?=@$Tma01_i_folha?>">
     <?=@$Lma01_i_folha?>
    </td>
    <td>
     <?db_input('ma01_i_folha',10,$Ima01_i_folha,true,'text',$db_opcao,"")?>
    </td>
   </tr>
   <tr>
    <td nowrap title="<?=@$Tma01_o_imagem?>">
     <?=@$Lma01_o_imagem?>
    </td>
    <td>
     <iframe name="frame_file" id="frame_file" src="mar1_framefile.php" width="100%" height="25" frameborder="0" scrolling="no"></iframe>
    </td>
   </tr>
  </table>
  <fieldset><legend><b> Características da Marca <b></legend>
    <table>
     <tr>
      <td nowrap title="<?=@$Tma01_c_figura1?>" colspan="3">
       <?=@$Lma01_c_figura1?>
       <?db_input('ma01_c_figura1',20,$Ima01_c_figura1,true,'text',$db_opcao,"")?>&nbsp;
       <?=@$Lma01_c_figura2?>
       <?db_input('ma01_c_figura2',20,$Ima01_c_figura2,true,'text',$db_opcao,"")?>&nbsp;
       <?=@$Lma01_c_figura3?>
       <?db_input('ma01_c_figura3',20,$Ima01_c_figura3,true,'text',$db_opcao,"")?>&nbsp;
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tma01_c_objeto1?>" colspan="3">
       <?=@$Lma01_c_objeto1?>
       <?db_input('ma01_c_objeto1',20,$Ima01_c_objeto1,true,'text',$db_opcao,"")?>&nbsp;
       <?=@$Lma01_c_objeto2?>
       <?db_input('ma01_c_objeto2',20,$Ima01_c_objeto2,true,'text',$db_opcao,"")?>&nbsp;
       <?=@$Lma01_c_objeto3?>
       <?db_input('ma01_c_objeto3',20,$Ima01_c_objeto3,true,'text',$db_opcao,"")?>&nbsp;
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tma01_c_letra1?>" colspan="3">
       <?=@$Lma01_c_letra1?>
       <?db_input('ma01_c_letra1',1,$Ima01_c_letra1,true,'text',$db_opcao,"")?>&nbsp;&nbsp;
       <?=@$Lma01_c_letra2?>
       <?db_input('ma01_c_letra2',1,$Ima01_c_letra2,true,'text',$db_opcao,"")?>&nbsp;&nbsp;
       <?=@$Lma01_c_letra3?>
       <?db_input('ma01_c_letra3',1,$Ima01_c_letra3,true,'text',$db_opcao,"")?>&nbsp;&nbsp;
       <?=@$Lma01_c_letra4?>
       <?db_input('ma01_c_letra4',1,$Ima01_c_letra4,true,'text',$db_opcao,"")?>&nbsp;&nbsp;
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tma01_v_sinal?>" colspan="3">
       <?=@$Lma01_v_sinal?>
       <?db_input('ma01_v_sinal',60,$Ima01_v_sinal,true,'text',$db_opcao,"")?>&nbsp;&nbsp;
      </td>
     </tr>
    </table>
  </fieldset>
 </center>
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
 <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=($db_opcao==1?"disabled":"")?> ><br>
 <input name="ma01_o_imagem" type="hidden" id="ma01_o_imagem" value="<?=@$ma01_c_nomeimagem?>" size="30">
</form>
<script>
function js_pesquisama01_i_cgm(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
 }else{
  if(document.form1.ma01_i_cgm.value != ''){
   js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.ma01_i_cgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }else{
   document.form1.z01_nome.value = '';
  }
 }
}
function js_mostracgm(erro,chave){
 document.form1.z01_nome.value = chave;
 if(erro==true){
  document.form1.ma01_i_cgm.focus();
  document.form1.ma01_i_cgm.value = '';
 }
}
function js_mostracgm1(chave1,chave2){
 document.form1.ma01_i_cgm.value = chave1;
 document.form1.z01_nome.value = chave2;
 db_iframe_cgm.hide();
}
function js_pesquisama01_i_localmarca(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_localmarca','func_localmarca.php?funcao_js=parent.js_mostralocalmarca1|ma04_i_codigo|ma04_c_descr','Pesquisa',true);
 }else{
  if(document.form1.ma01_i_localmarca.value != ''){
   js_OpenJanelaIframe('','db_iframe_localmarca','func_localmarca.php?pesquisa_chave='+document.form1.ma01_i_localmarca.value+'&funcao_js=parent.js_mostralocalmarca','Pesquisa',false);
  }else{
   document.form1.ma04_c_descr.value = '';
  }
 }
}
function js_mostralocalmarca(chave,erro){
 document.form1.ma04_c_descr.value = chave;
 if(erro==true){
  document.form1.ma01_i_localmarca.focus();
  document.form1.ma01_i_localmarca.value = '';
 }
}
function js_mostralocalmarca1(chave1,chave2){
 document.form1.ma01_i_localmarca.value = chave1;
 document.form1.ma04_c_descr.value = chave2;
 db_iframe_localmarca.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_marca','func_marca.php?funcao_js=parent.js_preenchepesquisa|ma01_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
 db_iframe_marca.hide();
 <?if($db_opcao!=1){
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 }?>
}
</script>