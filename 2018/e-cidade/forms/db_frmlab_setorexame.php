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

//MODULO: Laboratório
include ("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir ( );
$cllab_setorexame->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la09_i_setor");
$clrotulo->label("la23_c_descr");
$clrotulo->label("la08_c_descr");
$clrotulo->label("la24_i_setor");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tla09_i_codigo?>">
       <?=@$Lla09_i_codigo?>
    </td>
    <td> 
<?
db_input('la09_i_codigo',10,$Ila09_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tla09_i_setor?>">
       <b>Laboratorio:</b>
    </td>
    <td>
<?
db_input('la24_i_laboratorio',10,@$Ila09_i_labsetor,true,'text',3,"")
?>
       <?
db_input('la02_c_descr',50,@$Ila02_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>


  <tr>
    <td nowrap title="<?=@$Tla09_i_setor?>">
       <?
       db_ancora(@$Lla09_i_labsetor,"js_pesquisala09_i_labsetor(true);",$db_opcao);
       ?>
    </td>
    <td> 
       <?
       db_input('la24_i_setor',10,$Ila24_i_setor,true,'text',$db_opcao," onchange='js_pesquisala09_i_labsetor(false);'");
       db_input('la09_i_labsetor',10,'',true,'hidden',3,'');
       db_input('la23_c_descr',50,$Ila23_c_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla09_i_exame?>">
       <?
       db_ancora(@$Lla09_i_exame,"js_pesquisala09_i_exame(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la09_i_exame',10,$Ila09_i_exame,true,'text',$db_opcao," onchange='js_pesquisala09_i_exame(false);'")
?>
       <?
db_input('la08_c_descr',50,$Ila08_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla09_i_ativo?>">
       <?=$Lla09_i_ativo?>
    </td>
    <td>
        
       <?
        $aX = array('1'=>'ATIVO','2'=>'DESATIVADO');
        db_select('la09_i_ativo', $aX, true, $db_opcao, "");
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancela" type="button" id="cancela" value="Cancelar" onclick="js_cancela();" >

<table width="100%">
  <tr>
    <td valign="top"><br>
  <?
    $chavepri = array ("la09_i_codigo" => @$la09_i_codigo,
                       "la24_i_setor" => @$la24_i_setor,
                       "la24_i_laboratorio"=>@$la24_i_laboratorio,
                       "la02_c_descr"=>@$la02_c_descr,
                       "la09_i_exame"=>@$la09_i_exame,
                       "la08_c_descr"=>@$la08_c_descr,
                       "la09_i_labsetor"=>@$la09_i_labsetor,
                       "la23_c_descr"=>@$la23_c_descr,
                       "la09_i_ativo"=>@$la09_i_ativo,    
                       );
    $sOrder = "la09_i_ativo#la08_c_descr";
    $cliframe_alterar_excluir->chavepri = $chavepri;
    @$cliframe_alterar_excluir->sql = $cllab_setorexame->sql_query ("","*", $sOrder," la24_i_laboratorio = $la24_i_laboratorio");
    $cliframe_alterar_excluir->campos = "la09_i_codigo,la24_i_setor,la23_c_descr,la09_i_exame,la08_c_descr,la09_i_ativo";
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
if(document.form1.la09_i_labsetor.value==''){
	   document.form1.la09_i_labsetor.focus();
	}
document.onkeydown = function(evt) {
	if (evt.keyCode == 13 ) {
			eval(" document.getElementById('"+nextfield+"').focus()" );
			return false;
		
	}else if( evt.keyCode == 39 && valor_types ){
		eval(" document.getElementById('"+nextfield+"').focus()" );
	}
}

function js_pesquisala09_i_labsetor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_setor','func_lab_labsetor.php?la24_i_laboratorio=<?=$la24_i_laboratorio?>&funcao_js=parent.js_mostralab_setor1|la24_i_setor|la23_c_descr|la24_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.la24_i_setor.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_setor','func_lab_labsetor.php?la24_i_laboratorio=<?=$la24_i_laboratorio?>&pesquisa_chave='+document.form1.la24_i_setor.value+'&funcao_js=parent.js_mostralab_setor','Pesquisa',false);
     }else{
       document.form1.la23_c_descr.value = ''; 
     }
  }
}
function js_mostralab_setor(chave, erro, chave2){
  document.form1.la23_c_descr.value = chave; 
  document.form1.la09_i_labsetor.value = chave2; 
  if(erro==true){ 
    document.form1.la24_i_setor.focus(); 
    document.form1.la24_i_setor.value = ''; 
  }
}
function js_mostralab_setor1(chave1,chave2, chave3){
  document.form1.la24_i_setor.value = chave1;
  document.form1.la23_c_descr.value = chave2;
  document.form1.la09_i_labsetor.value = chave3;
  db_iframe_lab_setor.hide();
}
function js_pesquisala09_i_exame(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_exame','func_lab_exame.php?funcao_js=parent.js_mostralab_exame1|la08_i_codigo|la08_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la09_i_exame.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_exame','func_lab_exame.php?pesquisa_chave='+document.form1.la09_i_exame.value+'&funcao_js=parent.js_mostralab_exame','Pesquisa',false);
     }else{
       document.form1.la08_c_descr.value = ''; 
     }
  }
}
function js_mostralab_exame(chave,erro){
  document.form1.la08_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la09_i_exame.focus(); 
    document.form1.la09_i_exame.value = ''; 
  }
}
function js_mostralab_exame1(chave1,chave2){
  document.form1.la09_i_exame.value = chave1;
  document.form1.la08_c_descr.value = chave2;
  db_iframe_lab_exame.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_lab_setorexame','func_lab_setorexame.php?funcao_js=parent.js_preenchepesquisa|la09_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_setorexame.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_cancela(){
   location.href='lab1_lab_setorexame001.php?la24_i_laboratorio=<?=$la24_i_laboratorio?>&la02_c_descr=<?=$la02_c_descr?>';
}
</script>