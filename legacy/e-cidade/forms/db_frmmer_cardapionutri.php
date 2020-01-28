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
$clmer_cardapionutri->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me02_i_codigo");
$clrotulo->label("me01_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tme04_i_codigo?>">
   <?=@$Lme04_i_codigo?>
  </td>
  <td>
   <?db_input('me04_i_codigo',10,$Ime04_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme04_i_cardapio?>">
   <?db_ancora(@$Lme04_i_cardapio,"",3);?>
  </td>
  <td>
   <?db_input('me04_i_cardapio',10,$Ime04_i_cardapio,true,'text',3,"")?>
   <?db_input('me01_c_nome',40,@$Ime01_c_nome,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
   <td nowrap title="<?=@$Tme04_i_nutricionista?>">
    <?db_ancora(@$Lme04_i_nutricionista,"js_pesquisame04_i_nutricionistacardapio(true);",$db_opcao);?>
   </td>
   <td>
    <?db_input('me04_i_nutricionista',10,$Ime04_i_nutricionista,true,'text',$db_opcao,
               " onchange='js_pesquisame04_i_nutricionistacardapio(false);'"
              )
    ?>
    <?db_input('z01_nome',40,@$Iz01_nome,true,'text',3,'')?>
   </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" 
       id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
              <?=($db_botao==false?"disabled":"")?> >
<input name="cancela" 
       type="button" 
       id="cancela" 
       value="Cancelar" 
       onclick="js_cancela();" 
       <?=($db_botao==false?"disabled":"")?> >
<br><br>
<?
  $chavepri= array( "me02_i_codigo"=>@$me02_i_codigo,
                    "me02_i_cgm"=>@$me02_i_cgm,
                    "me02_c_crn"=>@$me02_c_crn
                  );
  $cliframe_alterar_excluir->chavepri=$chavepri;
  if (isset($me04_i_cardapio)&&@$me04_i_cardapio!="") {
    $cliframe_alterar_excluir->sql = $clmer_cardapionutri->sql_query(null,'*',null,"me04_i_cardapio=$me04_i_cardapio");
  }
  $cliframe_alterar_excluir->campos        = "me02_i_cgm,z01_nome,me02_c_crn";
  $cliframe_alterar_excluir->legenda       = "Nutricionista";
  $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
  $cliframe_alterar_excluir->textocabec    = "darkblue";
  $cliframe_alterar_excluir->textocorpo    = "black";
  $cliframe_alterar_excluir->fundocabec    = "#aacccc";
  $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
  $cliframe_alterar_excluir->iframe_width  = "100%";
  $cliframe_alterar_excluir->iframe_height = "200";
  $cliframe_alterar_excluir->opcoes = $opcao_frame;
  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
?>
</center>
</form>
<script>
function js_pesquisame04_i_nutricionistacardapio(mostra) {
	
  if (mostra==true) {
	  
    js_OpenJanelaIframe('','db_iframe_mer_nutricionistacardapio',
    	                'func_mer_nutricionistacardapio.php?funcao_js=parent.js_mostramer_nutricionista1|'+
    	                'me02_i_codigo|z01_nome','Pesquisa',true
    	               );
    
  } else {
	  
    if (document.form1.me04_i_nutricionista.value != '') {
        
      js_OpenJanelaIframe('','db_iframe_mer_nutricionistacardapio',
    	                  'func_mer_nutricionistacardapio.php?pesquisa_chave='+document.form1.me04_i_nutricionista.value+
    	                  '&funcao_js=parent.js_mostramer_nutricionista','Pesquisa',false
    	                 );
      
    } else {
      document.form1.z01_nome.value = '';
    }
  }
}

function js_mostramer_nutricionista(chave,erro) {
	
  document.form1.z01_nome.value = chave;
  if (erro==true) {
	  
    document.form1.me04_i_nutricionista.focus();
    document.form1.me04_i_nutricionista.value = '';
    
  }
}

function js_mostramer_nutricionista1(chave1,chave2) {
	
  document.form1.me04_i_nutricionista.value = chave1;
  document.form1.z01_nome.value             = chave2;
  db_iframe_mer_nutricionistacardapio.hide();
  
}

function js_cancela(me04_i_cardapio,me01_c_nome) {
	
  location.href='mer1_mer_cardapionutri001.php?me04_i_cardapio=<?=$me04_i_cardapio?>&me01_c_nome=<?=$me01_c_nome?>'+
                '&naopode=<?=@$naopode?>';
	                                           
}
</script>