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

//MODULO: merenda
$clmer_modpreparo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me01_i_codigo");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $result2 = $clmer_modpreparo->sql_record($clmer_modpreparo->sql_query($me05_i_codigo));
 db_fieldsmemory($result2,0);
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || (isset($db_opcao) && $db_opcao==3) && !isset($excluir)){
 $result2 = $clmer_modpreparo->sql_record($clmer_modpreparo->sql_query($me05_i_codigo));
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
  <td nowrap title="<?=@$Tme05_i_codigo?>">
   <?=@$Lme05_i_codigo?>
  </td>
  <td>
   <?db_input('me05_i_codigo',10,$Ime05_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme05_i_cardapio?>">
   <?db_ancora(@$Lme05_i_cardapio,"",3);?>
  </td>
  <td>
   <?db_input('me05_i_cardapio',10,$Ime05_i_cardapio,true,'text',3,"")?>
   <?db_input('me01_c_nome',40,@$Ime01_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme05_i_alimento?>">
   <?
   if (($db_opcao != 2) && ($db_opcao != 22)) {
     db_ancora(@$Lme05_i_alimento,"js_pesquisame05_i_alimento(true);",$db_opcao);
   } else {
     db_ancora(@$Lme05_i_alimento,"",3);
   }
   ?>
  </td>
  <td>
   <?
   $opcao = $db_opcao;
   if (($db_opcao == 2) || ($db_opcao == 22)) {
   	 $opcao = 3;
   }
   db_input('me05_i_alimento',10,$Ime05_i_alimento,true,'text',$opcao," onchange='js_pesquisame05_i_alimento(false);'")
   ?>
   <?db_input('me35_c_nomealimento',40,@$Ime35_c_nomealimento,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme05_f_porcao?>">
   <?=@$Lme05_f_porcao?>
  </td>
  <td>
   <?db_input('me05_f_porcao',10,$Ime05_f_porcao,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme05_c_natureza?>">
   <?=@$Lme05_c_natureza?>
  </td>
  <td>
   <?db_input('me05_c_natureza',52,$Ime05_c_natureza,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme05_t_obs?>">
   <?=@$Lme05_t_obs?>
  </td>
  <td>
   <?db_textarea('me05_t_obs',5,50,$Ime05_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" 
       id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
              <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="button" id="cancela" value="Cancelar" onclick="js_cancela();"  
       <?=($db_botao1 == false?"disabled":"")?> >
<br><br>
<?
 $chavepri= array("me05_i_codigo"=>@$me05_i_codigo);
 $cliframe_alterar_excluir->chavepri = $chavepri;
 if (isset($me05_i_cardapio) && @$me05_i_cardapio != "") {
   $cliframe_alterar_excluir->sql = $clmer_modpreparo->sql_query(null,'*',null," me05_i_cardapio = $me05_i_cardapio");
 }
 $cliframe_alterar_excluir->campos        = "me05_i_codigo,me35_c_nomealimento,me05_c_natureza,me05_t_obs,me05_f_porcao";
 $cliframe_alterar_excluir->legenda       ="MODO DE PREPARO";
 $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
 $cliframe_alterar_excluir->textocabec    = "darkblue";
 $cliframe_alterar_excluir->textocorpo    = "black";
 $cliframe_alterar_excluir->fundocabec    = "#aacccc";
 $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
 $cliframe_alterar_excluir->iframe_width  = "100%";
 $cliframe_alterar_excluir->iframe_height = "130";
 $cliframe_alterar_excluir->opcoes        = $db_opcao;
 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
?>
</center>
</form>
<script>
function js_pesquisame05_i_alimento(mostra) {
	
  if (mostra == true) {
	  
    js_OpenJanelaIframe('','db_iframe_mer_alimento',
    	                'func_mer_cardapioitem_mod.php?cardapio='+document.form1.me05_i_cardapio.value+
    	                '&funcao_js=parent.js_mostramer_cardapio1|me07_i_alimento|me35_c_nomealimento','Pesquisa',true
    	               );
    
  } else {
	  
    if (document.form1.me05_i_item.value != '') {
        
      js_OpenJanelaIframe('','db_iframe_mer_alimento',
    	                  'func_mer_cardapioitem_mod.php?cardapio='+document.form1.me05_i_cardapio.value+
    	                  '&pesquisa_chave='+document.form1.me05_i_alimento.value+'&funcao_js=parent.js_mostramer_cardapio',
    	                  'Pesquisa',false
    	                 );
      
    } else {
      document.form1.me35_c_nomealimento.value = '';
    }
  }
}

function js_mostramer_cardapio(chave,erro){
	
  document.form1.me35_c_nomealimento.value = chave;
  if (erro == true) {
	  
    document.form1.me05_i_alimento.focus();
    document.form1.me05_i_alimento.value = '';
    
  }
}

function js_mostramer_cardapio1(chave1,chave2) {
	
  document.form1.me05_i_alimento.value     = chave1;
  document.form1.me35_c_nomealimento.value = chave2;
  db_iframe_mer_alimento.hide();
  
}

function js_cancela() {
	
  location.href='mer1_mer_modpreparo001.php?me05_i_cardapio=<?=$me05_i_cardapio?>&me01_c_nome=<?=$me01_c_nome?>'+ 
	             '&naopode=<?=$naopode?>';
  
}
</script>