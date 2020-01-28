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

//MODULO: Merenda
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clmer_cardapioitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me01_i_codigo");
$clrotulo->label("me35_i_codigo");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $result2 = $clmer_cardapioitem->sql_record($clmer_cardapioitem->sql_query($me07_i_codigo));
 db_fieldsmemory($result2,0);
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || (isset($db_opcao) && $db_opcao==3) && !isset($excluir)){
 $result2 = $clmer_cardapioitem->sql_record($clmer_cardapioitem->sql_query($me07_i_codigo));
 db_fieldsmemory($result2,0);
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
    <td nowrap title="<?=@$Tme07_i_codigo?>">
       <?=@$Lme07_i_codigo?>
    </td>
    <td> 
    <?db_input('me07_i_codigo',10,$Ime07_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme07_i_cardapio?>">
     <?db_ancora(@$Lme07_i_cardapio,"js_pesquisame07_i_cardapio(true);",3);?>
    </td>
    <td> 
    <?db_input('me07_i_cardapio',10,$Ime07_i_cardapio,true,'text',3)?>
    <?db_input('me01_c_nome',40,@$Ime01_c_nome,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme07_i_alimento?>">
      <?db_ancora(@$Lme07_i_alimento,"js_pesquisame07_i_alimento(true);",$db_opcao);?>
    </td>
    <td> 
      <?db_input('me07_i_alimento',10,$Ime07_i_alimento,true,'text',$db_opcao,"onchange='js_pesquisame07_i_alimento(false);'")?>
      <?db_input('me35_c_nomealimento',40,@$Ime35_c_nomealimento,true,'text',3,'')?>
      <?db_input('count',40,@$Icount,true,'hidden',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme07_i_unidade?>">
     <?db_ancora("<b>Unidade:</b>","js_pesquisame07_i_unidade(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('me07_i_unidade',10,@$Ime07_i_unidade,true,'text',$db_opcao,"onchange='js_pesquisame07_i_unidade(false);'")?>
     <?db_input('m61_descr',40,@$Im61_descr,true,'text',3,'')?>     
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme07_f_quantidade?>">
      <?=@$Lme07_f_quantidade?>
    </td>
    <td> 
      <?db_input('me07_f_quantidade',10,$Ime07_f_quantidade,true,'text',$db_opcao,"")?>    
   </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme07_c_medida?>">
       <?=@$Lme07_c_medida?>
    </td>
    <td> 
    <?db_input('me07_c_medida',52,$Ime07_c_medida,true,'text',$db_opcao,"")?>    
  </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="submit" id="db_opcao"
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
        <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=$db_botao1==false?"disabled":""?>>
<input type ="submit" name ="teste"  value="Teste" style="visibility:hidden;">
<table width='100%'>
 <tr>
  <td valign="top">
  <?
   $chavepri= array("me07_i_codigo"=>@$me07_i_codigo);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clmer_cardapioitem->sql_query("","*",""," me07_i_cardapio = $me07_i_cardapio");
   $cliframe_alterar_excluir->campos  ="me07_i_codigo,me35_c_nomealimento,m61_descr,me07_f_quantidade";
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
   $cliframe_alterar_excluir->opcoes = $opcao_frame;   
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
<script>
function js_pesquisame07_i_unidade(mostra) {

  if (mostra == true) {

	js_OpenJanelaIframe('','db_iframe_matunid',
	                    'func_matunid.php?funcao_js=parent.js_mostramer_unidade1|m61_codmatunid|m61_descr',
	                     'Pesquisa',true
	                   );

  } else {

	if (document.form1.me07_i_unidade.value != '') { 

	  js_OpenJanelaIframe('','db_iframe_matunid',
	                             'func_matunid.php?pesquisa_chave='+document.form1.me07_i_unidade.value+
	                             '&funcao_js=parent.js_mostramatunid',
	                             'Pesquisa',false
	                           )
	} else {
	  document.form1.m61_codmatunid.value = ''; 
	}
  }
}

function js_mostramer_unidade(chave,erro) {

  document.form1.m61_descr.value = chave; 
  if (erro == true) { 

	document.form1.me07_i_unidade.focus(); 
    document.form1.me07_i_unidade.value = ''; 

  }

}

function js_mostramer_unidade1(chave1,chave2) {

  document.form1.me07_i_unidade.value = chave1;
  document.form1.m61_descr.value   = chave2;
  db_iframe_matunid.hide();

}



function js_pesquisame07_i_cardapio(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('','db_iframe_mer_cardapio',
                        'func_mer_cardapio.php?funcao_js=parent.js_mostramer_cardapio1|me01_i_codigo|me01_c_nome',
                        'Pesquisa',true
                       );

  } else {

     if (document.form1.me07_i_cardapio.value != '') { 

        js_OpenJanelaIframe('','db_iframe_mer_cardapio',
                             'func_mer_cardapio.php?pesquisa_chave='+document.form1.me07_i_cardapio.value+
                             '&funcao_js=parent.js_mostramer_cardapio',
                             'Pesquisa',false
                           )
     } else {
       document.form1.me01_i_codigo.value = ''; 
     }
  }
}

function js_mostramer_cardapio(chave,erro) {

  document.form1.me01_c_nome.value = chave; 
  if (erro == true) { 

    document.form1.me07_i_cardapio.focus(); 
    document.form1.me07_i_cardapio.value = ''; 

  }

}

function js_mostramer_cardapio1(chave1,chave2) {

  document.form1.me07_i_cardapio.value = chave1;
  document.form1.me01_c_nome.value   = chave2;
  db_iframe_mer_cardapio.hide();

}

function js_pesquisame07_i_alimento(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('','db_iframe_mer_alimento',
                        'func_mer_alimento.php?funcao_js=parent.js_mostramer_alimento1|me35_i_codigo|me35_c_nomealimento|count',
                        'Pesquisa',true
                       );

  } else {

     if (document.form1.me07_i_alimento.value != '') { 

        js_OpenJanelaIframe('','db_iframe_mer_alimento',
                             'func_mer_alimento.php?pesquisa_chave='+document.form1.me07_i_alimento.value+
                             '&funcao_js=parent.js_mostramer_alimento',
                             'Pesquisa',false
                           )
     } else {
       document.form1.me35_i_codigo.value = ''; 
     }
  }
}

function js_mostramer_alimento(chave,erro) {

  document.form1.me35_c_nomealimento.value = chave; 
  if (erro == true) { 

    document.form1.me07_i_alimento.focus(); 
    document.form1.me07_i_alimento.value = ''; 

  }

}

function js_mostramer_alimento1(chave1,chave2,chave3) {

  document.form1.me07_i_alimento.value     = chave1;
  document.form1.me35_c_nomealimento.value = chave2;
  document.form1.count.value               = chave3;
  db_iframe_mer_alimento.hide();
  if (chave3 == 0) {
	if (confirm("O Alimento selecionado não esta vinculado a nenhum item do materiais, deseja realizar o vinculo agora?")) {
		js_OpenJanelaIframe('','db_iframe_mer_alimento1',
                'mer1_mer_alimentomatmater001.php?me36_i_alimento='+chave1+
                '&nomealimento='+chave2,'Pesquisa',true);
	}else{
	} 
  } 
}

function js_pesquisa() {

  js_OpenJanelaIframe('top.corpo','db_iframe_mer_cardapioitem',
                       'func_mer_cardapioitem.php?funcao_js=parent.js_preenchepesquisa|me07_i_codigo','Pesquisa',true);

}

function js_preenchepesquisa(chave) {

  db_iframe_mer_cardapioitem.hide();
  <?
  if ($db_opcao != 1) {

    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>