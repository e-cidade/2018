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

//MODULO: Laboratório
include ("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir ( );
$cllab_labsetor->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la06_i_codigo");
$clrotulo->label("la23_c_descr");
$clrotulo->label("la02_c_descr");

?>
<form name="form1" method="post"  enctype="multipart/form-data">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tla24_i_codigo?>">
       <?=@$Lla24_i_codigo?>
    </td>
    <td> 
<?
db_input('la24_i_codigo',10,$Ila24_i_codigo,true,'text',3,"")
?>
    </td>
     <td rowspan="6">
    <iframe name="frame_imagem" id="frame_imagem" src="lab4_mostraimagem.php" width="150" height=150" frameborder="0" scrolling="no"></iframe>
    <?
    if((isset($chavepesquisa) || isset($opcao) || isset($alterar)) && isset($la24_c_nomearq)){
     if($la24_o_assinatura!=0){
      $arquivo = "tmp/".$la24_c_nomearq;
      pg_exec("begin");
      pg_loexport($la24_o_assinatura,$arquivo);
      pg_exec("end");
     }else{
      $arquivo = "imagens/semmarca.jpg";
     }
    ?>
    <script>
     frame_imagem.location.href="lab4_mostraimagem.php?imagem_gerada=<?=$arquivo?>";
    </script>
    <?}?>
   </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla24_i_laboratorio?>">
       <?
       db_ancora(@$Lla24_i_laboratorio,"js_pesquisala24_i_laboratorio(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('la24_i_laboratorio',10,$Ila24_i_laboratorio,true,'text',3," onchange='js_pesquisala24_i_laboratorio(false);'")
?>
       <?
db_input('la02_c_descr',40,$Ila02_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tla24_i_resp?>">
       <?
       db_ancora(@$Lla24_i_resp,"js_pesquisala24_i_resp(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la24_i_resp',10,$Ila24_i_resp,true,'text',$db_opcao," onchange='js_pesquisala24_i_resp(false);'")
?>
       <?
db_input('z01_nome',40,@$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla24_i_setor?>">
       <?
       db_ancora(@$Lla24_i_setor,"js_pesquisala24_i_setor(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la24_i_setor',10,$Ila24_i_setor,true,'text',$db_opcao," onchange='js_pesquisala24_i_setor(false);'")
?>
       <?
db_input('la23_c_descr',40,@$Ila23_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
   <tr>
    <td nowrap title="<?=@$Tla24_o_assinatura?>">
       <?=@$Lla24_o_assinatura?>
    </td>
    <td> 
<iframe name="frame_file" id="frame_file" src="lab1_framefile.php" width="100%" height="25" frameborder="0" scrolling="no"></iframe>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancela" type="button" id="cancela" value="Cancelar" onclick="js_cancela();" >
 <input name="la24_o_assinatura" type="hidden" id="la24_o_assinatura" value="<?=@$la24_c_nomearq?>" size="30">

<table width="100%">
  <tr>
    <td valign="top"><br>
  <?
    $chavepri = array ("la24_i_codigo" => @$la05_i_codigo,"z01_nome"=>@$z01_nome ,"la23_c_descr"=>@$la23_c_descr, "la24_i_laboratorio" => @$la24_i_laboratorio, "la24_i_resp" => @$la24_resp, "la24_i_setor" => @$la24_i_setor, "la24_o_assinatura" => @$la24_o_assinatura,"la24_c_nomearq" => @$la24_c_nomearq);
    $cliframe_alterar_excluir->chavepri = $chavepri;
    @$cliframe_alterar_excluir->sql = $cllab_labsetor->sql_query ("","*",""," la24_i_laboratorio = $la24_i_laboratorio");
    $cliframe_alterar_excluir->campos = "la24_i_codigo,la24_i_resp,z01_nome,la24_i_setor,la23_c_descr";
    $cliframe_alterar_excluir->legenda = "Registros";
    $cliframe_alterar_excluir->msg_vazio = "Não foi encontrado nenhum registro.";
    $cliframe_alterar_excluir->textocabec = "#DEB887";
    $cliframe_alterar_excluir->textocorpo = "#444444";
    $cliframe_alterar_excluir->fundocabec = "#444444";
    $cliframe_alterar_excluir->fundocorpo = "#eaeaea";
    $cliframe_alterar_excluir->iframe_height = "200";
    $cliframe_alterar_excluir->iframe_width = "100%";
    $cliframe_alterar_excluir->tamfontecabec = 9;
    $cliframe_alterar_excluir->tamfontecorpo = 9;
    $cliframe_alterar_excluir->formulario = false;
    $cliframe_alterar_excluir->iframe_alterar_excluir ( $db_opcao );
  ?>
  </td>
  </tr>
</table>

</form>
<script>
function js_pesquisala24_i_resp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_labresp','func_lab_labresp.php?la24_i_laboratorio=<?=$la24_i_laboratorio?>&funcao_js=parent.js_mostralab_labresp1|la06_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.la24_i_resp.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_labresp','func_lab_labresp.php?la24_i_laboratorio=<?=$la24_i_laboratorio?>&pesquisa_chave='+document.form1.la24_i_resp.value+'&funcao_js=parent.js_mostralab_labresp','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostralab_labresp(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.la24_i_resp.focus(); 
    document.form1.la24_i_resp.value = ''; 
  }
}
function js_mostralab_labresp1(chave1,chave2){
  document.form1.la24_i_resp.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_lab_labresp.hide();
}
function js_pesquisala24_i_setor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_setor','func_lab_setor.php?funcao_js=parent.js_mostralab_setor1|la23_i_codigo|la23_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la24_i_setor.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_setor','func_lab_setor.php?pesquisa_chave='+document.form1.la24_i_setor.value+'&funcao_js=parent.js_mostralab_setor','Pesquisa',false);
     }else{
       document.form1.la23_c_descr.value = ''; 
     }
  }
}
function js_mostralab_setor(chave,erro){
  document.form1.la23_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la24_i_setor.focus(); 
    document.form1.la24_i_setor.value = ''; 
  }
}
function js_mostralab_setor1(chave1,chave2){
  document.form1.la24_i_setor.value = chave1;
  document.form1.la23_c_descr.value = chave2;
  db_iframe_lab_setor.hide();
}
function js_pesquisala24_i_laboratorio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_laboratorio','func_lab_laboratorio.php?funcao_js=parent.js_mostralab_laboratorio1|la02_i_codigo|la02_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la24_i_laboratorio.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_laboratorio','func_lab_laboratorio.php?pesquisa_chave='+document.form1.la24_i_laboratorio.value+'&funcao_js=parent.js_mostralab_laboratorio','Pesquisa',false);
     }else{
       document.form1.la02_c_descr.value = ''; 
     }
  }
}
function js_mostralab_laboratorio(chave,erro){
  document.form1.la02_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la24_i_laboratorio.focus(); 
    document.form1.la24_i_laboratorio.value = ''; 
  }
}
function js_mostralab_laboratorio1(chave1,chave2){
  document.form1.la24_i_laboratorio.value = chave1;
  document.form1.la02_c_descr.value = chave2;
  db_iframe_lab_laboratorio.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_lab_labsetor','func_lab_labsetor.php?funcao_js=parent.js_preenchepesquisa|la24_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_labsetor.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_cancela(){
       location.href='lab1_lab_labsetor001.php?la24_i_laboratorio=<?=$la24_i_laboratorio?>&la02_c_descr=<?=$la02_c_descr?>';
}
</script>