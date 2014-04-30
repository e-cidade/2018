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

//MODULO: TFD
$cltfd_destino->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("tf24_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ttf03_i_codigo?>">
       <?=@$Ltf03_i_codigo?>
    </td>
    <td> 
      <?db_input('tf03_i_codigo',10,$Itf03_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf03_c_descr?>">
       <?=@$Ltf03_c_descr?>
    </td>
    <td> 
      <?db_input('tf03_c_descr',52,$Itf03_c_descr,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf03_i_tipodistancia?>">
       <?db_ancora(@$Ltf03_i_tipodistancia,"js_pesquisatf03_i_tipodistancia(true);",$db_opcao);?>
    </td>
    <td> 
      <?db_input('tf03_i_tipodistancia',10,@$Itf03_i_tipodistancia,true,'text',$db_opcao," onchange='js_pesquisatf03_i_tipodistancia(false);'")?>
      <?db_input('tf24_c_descr',40,@$Itf24_c_descr,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf03_f_distancia?>">
       <?=@$Ltf03_f_distancia?>
    </td>
    <td> 
       <?db_input('tf03_f_distancia',10,$Itf03_f_distancia,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf03_d_validadeini?>">
       <?=@$Ltf03_d_validadeini?>
    </td>
    <td> 
      <?db_inputdata('tf03_d_validadeini',@$tf03_d_validadeini_dia,@$tf03_d_validadeini_mes,@$tf03_d_validadeini_ano,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf03_d_validadefim?>">
       <?=@$Ltf03_d_validadefim?>
    </td>
    <td> 
      <?db_inputdata('tf03_d_validadefim',@$tf03_d_validadefim_dia,@$tf03_d_validadefim_mes,@$tf03_d_validadefim_ano,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=($db_opcao != 3 ? " onclick=\"return js_validadata();\" " : "")?>>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=($db_opcao == 1 ? 'disabled' : '')?>>
</form>
<script>
function js_validadata() {

  if(document.form1.tf03_d_validadeini.value == '') {

	  alert('A data de início deve ser preenchida.');
		return false;

	}

  if(document.form1.tf03_d_validadefim.value !=  '') {

    aIni = document.form1.tf03_d_validadeini.value.split('/');
	  aFim = document.form1.tf03_d_validadefim.value.split('/');
	  dIni = new Date(aIni[2], aIni[1], aIni[0]);
	  dFim = new Date(aFim[2], aFim[1], aFim[0]);

		if(dFim < dIni) {
				
		  alert("Data final nao pode ser menor que a data inicial.");
		  document.form1.tf03_d_validadefim.value = '';
			document.form1.tf03_d_validadefim.focus();
		  return false;

		}	

  }
	return true;						

}

function js_pesquisatf03_i_tipodistancia(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tfd_tipodistancia','func_tfd_tipodistancia.php?funcao_js=parent.js_mostratfd_tipodistancia1|tf24_i_codigo|tf24_c_descr','Pesquisa',true);
  }else{
     if(document.form1.tf03_i_tipodistancia.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_tfd_tipodistancia','func_tfd_tipodistancia.php?pesquisa_chave='+document.form1.tf03_i_tipodistancia.value+'&funcao_js=parent.js_mostratfd_tipodistancia','Pesquisa',false);
     }else{
       document.form1.tf24_c_descr.value = ''; 
     }
  }
}
function js_mostratfd_tipodistancia(chave,erro){
  document.form1.tf24_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.tf03_i_tipodistancia.focus(); 
    document.form1.tf03_i_tipodistancia.value = ''; 
  }
}
function js_mostratfd_tipodistancia1(chave1,chave2){
  document.form1.tf03_i_tipodistancia.value = chave1;
  document.form1.tf24_c_descr.value = chave2;
  db_iframe_tfd_tipodistancia.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_tfd_destino','func_tfd_destino.php?funcao_js=parent.js_preenchepesquisa|tf03_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tfd_destino.hide();
  <?
  if($db_opcao!=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>