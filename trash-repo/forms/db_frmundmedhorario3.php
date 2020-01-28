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
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clundmedhorario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed32_i_codigo");
$clrotulo->label("sd04_i_unidade");
$clrotulo->label("sd04_i_medico");
$clrotulo->label("sd04_i_codigo");
$clrotulo->label("sd30_i_undmed");
$clrotulo->label("descrdepto");
$clrotulo->label("z01_nome");
$clrotulo->label("ed32_c_descr");

$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd30_i_codigo?>">
       <?=@$Lsd30_i_codigo?>
    </td>
    <td> 
<?
db_input('sd30_i_codigo',10,$Isd30_i_codigo,true,'text',3,"")
?>
<?
db_input('sd30_i_undmed',10,$Isd30_i_undmed,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd04_i_unidade?>">
       <?
       db_ancora(@$Lsd04_i_unidade,"js_pesquisasd04_i_unidade(true);",3);
       ?>
    </td>
    <td>
<?
db_input('sd04_i_unidade',10,$Isd04_i_unidade,true,'text',3," onchange='js_pesquisasd04_i_unidade(false);'")
?>
       <?
db_input('descrdepto',80,$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  </tr>
    <tr>
    <td nowrap title="<?=@$Tsd04_i_medico?>">
       <?
       db_ancora(@$Lsd04_i_medico,"js_pesquisasd04_i_medico(true);",3);
       ?>
    </td>
    <td>
<?
db_input('sd04_i_medico',10,$Isd04_i_medico,true,'text',3," onchange='js_pesquisasd04_i_medico(false);'")
?>
       <?
db_input('z01_nome',80,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tsd30_i_diasemana?>">
       <?
       db_ancora(@$Lsd30_i_diasemana,"js_pesquisasd30_i_diasemana(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd30_i_diasemana',5,$Isd30_i_diasemana,true,'text',$db_opcao," onchange='js_pesquisasd30_i_diasemana(false);'")
?>
       <?
db_input('ed32_c_descr',10,$Ied32_c_descr,true,'text',3,'')
       ?>
       <?=@$Lsd30_c_horaini?>
<?
db_input('sd30_c_horaini',5,$Isd30_c_horaini,true,'text',$db_opcao,"onKeyPress='formata_hora(this)'")
?>
       <?=@$Lsd30_c_horafim?>
<?
db_input('sd30_c_horafim',5,$Isd30_c_horafim,true,'text',$db_opcao,"onKeyPress='formata_hora(this)'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd30_i_fichas?>" colspan="2">
       <?=@$Lsd30_i_fichas?>
<?
db_input('sd30_i_fichas',5,$Isd30_i_fichas,true,'text',$db_opcao,"")
?>
       <?=@$Lsd30_i_reservas?>
<?
db_input('sd30_i_reservas',5,$Isd30_i_reservas,true,'text',$db_opcao,"")
?>
            <? if ( $db_opcao == 1 ){ ?>
              <?=$Lsd30_i_turno?>
              <?
                 $x = array("1"=>"Manhã","2"=>"Tarde","3"=>"Noite");
                 db_select('sd30_i_turno',$x,true,$db_opcao, "","",2);
              ?>
            <?}?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<table>
 <tr>
  <td valign="top"><br>
  <?
   $chavepri= array("sd30_i_codigo"=>@$sd30_i_codigo,"sd30_i_undmed"=>@$sd30_i_undmed,"sd30_i_diasemana"=>@$sd30_i_diasemana,"sd30_c_horaini"=>@$sd30_c_horaini,"sd30_c_horafim"=>@$sd30_c_horafim,"sd30_i_fichas"=>@$sd30_i_fichas,"sd30_i_reservas"=>@$sd30_i_reservas,"ed32_c_descr"=>@$ed32_c_descr,"sd30_i_turno"=>@$sd30_i_turno);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clundmedhorario->sql_query($sd30_i_codigo,"sd30_i_codigo,sd30_i_undmed,sd30_i_diasemana,sd30_i_turno,sd30_c_horaini,sd30_c_horafim,sd30_i_fichas,sd30_i_reservas,sd04_i_unidade,sd04_i_medico,ed32_c_descr","","sd30_i_undmed = $sd30_i_undmed");
   $cliframe_alterar_excluir->campos  ="sd30_i_codigo,ed32_c_descr,sd30_i_turno,sd30_c_horaini,sd30_c_horafim,sd30_i_fichas,sd30_i_reservas";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="200";
   $cliframe_alterar_excluir->iframe_width ="650";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   //$cliframe_alterar_excluir->opcoes = 3;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
<script>
function js_pesquisasd30_i_diasemana(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_diasemana','func_diasemana.php?funcao_js=parent.js_mostradiasemana1|ed32_i_codigo|ed32_c_descr','Pesquisa',true);
  }else{
     if(document.form1.sd30_i_diasemana.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_diasemana','func_diasemana.php?pesquisa_chave='+document.form1.sd30_i_diasemana.value+'&funcao_js=parent.js_mostradiasemana','Pesquisa',false);
     }else{
       document.form1.ed32_i_codigo.value = ''; 
     }
  }
}
function js_mostradiasemana(chave,erro){
  document.form1.ed32_c_descr.value = chave;
  if(erro==true){ 
    document.form1.sd30_i_diasemana.focus();
    document.form1.sd30_i_diasemana.value = '';
  }
}
function js_mostradiasemana1(chave1,chave2){
  document.form1.sd30_i_diasemana.value = chave1;
  document.form1.ed32_c_descr.value = chave2;
  db_iframe_diasemana.hide();
}
function js_pesquisasd30_i_undmed(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_unidademedicos','func_unidademedicos.php?funcao_js=parent.js_mostraunidademedicos1|sd04_i_codigo|sd04_i_medico','Pesquisa',true);
  }else{
     if(document.form1.sd30_i_undmed.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_unidademedicos','func_unidademedicos.php?pesquisa_chave='+document.form1.sd30_i_undmed.value+'&funcao_js=parent.js_mostraunidademedicos','Pesquisa',false);
     }else{
       document.form1.sd04_i_medico.value = ''; 
     }
  }
}
function js_mostraunidademedicos(chave,erro){
  document.form1.sd04_i_medico.value = chave; 
  if(erro==true){ 
    document.form1.sd30_i_undmed.focus(); 
    document.form1.sd30_i_undmed.value = ''; 
  }
}
function js_mostraunidademedicos1(chave1,chave2){
  document.form1.sd30_i_undmed.value = chave1;
  document.form1.sd04_i_medico.value = chave2;
  db_iframe_unidademedicos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_undmedhorario','func_undmedhorario.php?funcao_js=parent.js_preenchepesquisa|sd30_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_undmedhorario.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function formata_hora(campo){
 digitos = campo.value.length;
 valor = campo.value;
 if(digitos==2){
  campo.value = valor+':';
 }
}

document.form1.descrdepto.value = parent.iframe_a1.document.form1.descrdepto.value;
</script>