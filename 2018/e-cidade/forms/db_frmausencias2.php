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
$clausencias->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd06_i_codigo");
$clrotulo->label("sd06_i_unidade");
$clrotulo->label("sd06_i_medico");
$clrotulo->label("sd06_d_data");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
 $sd06_d_data_dia = substr($sd06_d_data,0,2);
 $sd06_d_data_mes = substr($sd06_d_data,3,2);
 $sd06_d_data_ano = substr($sd06_d_data,6,4);
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 $sd06_d_data_dia = substr($sd06_d_data,0,2);
 $sd06_d_data_mes = substr($sd06_d_data,3,2);
 $sd06_d_data_ano = substr($sd06_d_data,6,4);
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
    <td nowrap title="<?=@$Tsd06_i_codigo?>">
       <?=@$Lsd06_i_codigo?>
    </td>
    <td> 
<?
db_input('sd06_i_codigo',10,$Isd06_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd06_i_unidade?>">
       <?
       db_ancora(@$Lsd06_i_unidade,"js_pesquisasd06_i_unidade(true);",3);
       ?>
    </td>
    <td>
<?
db_input('sd06_i_unidade',10,$Isd06_i_unidade,true,'text',3," onchange='js_pesquisasd06_i_unidade(false);'")
?>
       <?
db_input('descrdepto',80,@$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  </tr>
    <tr>
    <td nowrap title="<?=@$Tsd06_i_medico?>">
       <?
       db_ancora(@$Lsd06_i_medico,"js_pesquisasd06_i_medico(true);",3);
       ?>
    </td>
    <td>
<?
db_input('sd06_i_medico',10,$Isd06_i_medico,true,'text',3," onchange='js_pesquisasd06_i_medico(false);'")
?>
       <?
db_input('z01_nome',80,@$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd06_d_data?>">
       <?=@$Lsd06_d_data?>
    </td>
    <td>
     <?db_inputdata('sd06_d_data',@$sd06_d_data_dia,@$sd06_d_data_mes,@$sd06_d_data_ano,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
</form>
<table>
 <tr>
  <td valign="top"><br>
  <?
   $chavepri= array("sd06_i_codigo"=>@$sd06_i_codigo,"sd06_i_unidade"=>@$sd06_i_unidade,"descrdepto"=>@$descrdepto,"sd06_i_medico"=>@$sd06_i_medico,"z01_nome"=>@$z01_nome,"sd06_d_data"=>@$sd06_d_data);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   //echo $clausencias->sql_query("","*","","sd06_i_unidade = $sd06_i_unidade and sd06_i_medico = $sd06_i_medico");
   @$cliframe_alterar_excluir->sql = $clausencias->sql_query("","*","sd06_d_data desc","sd06_i_unidade = $sd06_i_unidade and sd06_i_medico = $sd06_i_medico");
   $cliframe_alterar_excluir->campos  ="sd06_i_codigo,sd06_i_unidade,sd06_i_medico,sd06_d_data";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="200";
   $cliframe_alterar_excluir->iframe_width ="100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>

<script>
function js_pesquisasd06_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd04_i_codigo|sd04_i_medico','Pesquisa',true);
  }else{
     if(document.form1.sd30_i_undmed.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_unidademedicos','func_unidademedicos.php?pesquisa_chave='+document.form1.sd30_i_undmed.value+'&funcao_js=parent.js_mostraunidademedicos','Pesquisa',false);
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