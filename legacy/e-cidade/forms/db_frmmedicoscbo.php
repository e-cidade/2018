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
$clmedicoscbo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("rh70_descr");
$clrotulo->label("rh70_estrutural");
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
  <td nowrap title="<?=@$Tsd36_i_codigo?>">
   <?=@$Lsd36_i_codigo?>
  </td>
  <td>
   <?db_input('sd36_i_codigo',10,$Isd36_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd36_i_medico?>">
   <?db_ancora(@$Lsd36_i_medico,"",3);?>
  </td>
  <td>
   <?db_input('sd36_i_medico',10,$Isd36_i_medico,true,'text',3,'')?>
   <?db_input('z01_nome',50,$Iz01_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd36_i_rhcbo?>">
   <?db_ancora(@$Lsd36_i_rhcbo,"js_pesquisasd36_i_rhcbo(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('sd36_i_rhcbo',10,$Isd36_i_rhcbo,true,'hidden',3,'')?>
   <?db_input('rh70_estrutural',10,$Irh70_estrutural,true,'text',3,'')?>
   <?db_input('rh70_descr',50,$Irh70_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd36_c_vinculo?>">
   <?=@$Lsd36_c_vinculo?>
  </td>
  <td>
   <?
   $x = array(''=>'','1'=>'VÍNCULO EMPREGATÍCIO','2'=>'AUTÔNOMO');
   db_select('sd36_c_vinculo',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd36_i_chambul?>">
   <?=@$Lsd36_i_chambul?>
  </td>
  <td>
   <?db_input('sd36_i_chambul',10,$Isd36_i_chambul,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd36_i_choutros?>">
   <?=@$Lsd36_i_choutros?>
  </td>
  <td>
   <?db_input('sd36_i_choutros',10,$Isd36_i_choutros,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd36_i_crm?>">
   <?=@$Lsd36_i_crm?>
  </td>
  <td>
   <?db_input('sd36_i_crm',20,$Isd36_i_crm,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd36_c_crmuf?>">
   <?=@$Lsd36_c_crmuf?>
  </td>
  <td>
   <?db_input('sd36_c_crmuf',2,$Isd36_c_crmuf,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd36_c_crmorgao?>">
   <?=@$Lsd36_c_crmorgao?>
  </td>
  <td>
   <?db_input('sd36_c_crmorgao',50,$Isd36_c_crmorgao,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top"><br>
  <?
  $chavepri= array("sd36_i_codigo"=>@$sd36_i_codigo
                  ,"sd36_i_medico"=>@$sd36_i_medico
                  ,"z01_nome"=>@$z01_nome
                  ,"sd36_i_rhcbo"=>@$sd36_i_rhcbo
                  ,"rh70_descr"=>@$rh70_descr
                  ,"rh70_estrutural"=>@$rh70_estrutural
                  ,"sd36_c_vinculo"=>@$sd36_c_vinculo
                  ,"sd36_i_chambul"=>@$sd36_i_chambul
                  ,"sd36_i_choutros"=>@$sd36_i_choutros
                  ,"sd36_i_crm"=>@$sd36_i_crm
                  ,"sd36_c_crmuf"=>@$sd36_c_crmuf
                  ,"sd36_c_crmorgao"=>@$sd36_c_crmorgao
                  );
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clmedicoscbo->sql_query("","*",""," sd36_i_medico = $sd36_i_medico");
   @$cliframe_alterar_excluir->campos  ="rh70_estrutural,rh70_descr,sd36_i_crm,sd36_c_crmuf,sd36_c_crmorgao,sd36_i_chambul,sd36_i_choutros";
   $cliframe_alterar_excluir->legenda="Registros";
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
</form>
<script>
function js_pesquisasd36_i_rhcbo(mostra){
 js_OpenJanelaIframe('','db_iframe_rhcbo','func_rhcbo.php?funcao_js=parent.js_mostrarhcbo1|rh70_sequencial|rh70_descr|rh70_estrutural','Pesquisa',true);
}
function js_mostrarhcbo1(chave1,chave2,chave3){
 document.form1.sd36_i_rhcbo.value = chave1;
 document.form1.rh70_descr.value = chave2;
 document.form1.rh70_estrutural.value = chave3;
 db_iframe_rhcbo.hide();
}
</script>