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
$clmer_alimento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me30_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tme35_i_codigo?>">
       <?=@$Lme35_i_codigo?>
    </td>
    <td> 
    <?db_input('me35_i_codigo',10,$Ime35_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme35_c_nomealimento?>">
       <?=@$Lme35_c_nomealimento?>
    </td>
    <td> 
     <?db_input('me35_c_nomealimento',60,$Ime35_c_nomealimento,true,'text',$db_opcao,"")?>    
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme35_c_nomecientifico?>">
       <?=@$Lme35_c_nomecientifico?>
    </td>
    <td> 
      <?db_input('me35_c_nomecientifico',60,$Ime35_c_nomecientifico,true,'text',$db_opcao,"")?>    
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme35_i_grupoalimentar?>">
       <?
       db_ancora(@$Lme35_i_grupoalimentar,"js_pesquisame35_i_grupoalimentar(true);",$db_opcao);
       ?>
    </td>
    <td> 
     <?db_input('me35_i_grupoalimentar',10,$Ime35_i_grupoalimentar,true,'text',$db_opcao,
                 " onchange='js_pesquisame35_i_grupoalimentar(false);'"
               )
     ?>
     <?db_input('me30_c_descricao',48,@$Ime30_c_descricao,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme35_c_fonteinformacao?>">
       <?=@$Lme35_c_fonteinformacao?>
    </td>
    <td> 
      <?db_input('me35_c_fonteinformacao',60,$Ime35_c_fonteinformacao,true,'text',$db_opcao,"")?>    
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme35_i_unidade?>">
       <?
       db_ancora(@$Lme35_i_unidade,"js_pesquisame35_i_unidade(true);",$db_opcao);
       ?>
    </td>
    <td> 
     <?db_input('me35_i_unidade',10,$Ime35_i_unidade,true,'text',$db_opcao,
                 " onchange='js_pesquisame35_i_unidade(false);'"
               )
     ?>
     <?db_input('m61_descr',48,@$Im61_descr,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme35_c_quant?>">
       <?=@$Lme35_c_quant?>
    </td>
    <td> 
      <?db_input('me35_c_quant',10,$Ime35_c_quant,true,'text',$db_opcao,"")?>    
    </td>
  </tr> 
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="submit" id="db_opcao"
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
        <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="novo" id="novo" value="Novo" type="button" onclick="js_novo();">
</form>
<script>
function js_pesquisame35_i_grupoalimentar(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('','db_iframe_mer_grupoalimento',
                        'func_mer_grupoalimento.php?funcao_js=parent.js_mostramer_grupoalimento1|me30_i_codigo|me30_c_descricao',
                        'Pesquisa',true
                       );

  } else {

     if (document.form1.me35_i_grupoalimentar.value != '') { 

        js_OpenJanelaIframe('','db_iframe_mer_grupoalimento',
                             'func_mer_grupoalimento.php?pesquisa_chave='+document.form1.me35_i_grupoalimentar.value+
                             '&funcao_js=parent.js_mostramer_grupoalimento',
                             'Pesquisa',false
                           )
     } else {
       document.form1.me30_i_codigo.value = ''; 
     }
  }
}

function js_mostramer_grupoalimento(chave,erro) {

  document.form1.me30_c_descricao.value = chave; 
  if (erro == true) { 

    document.form1.me35_i_grupoalimentar.focus(); 
    document.form1.me35_i_grupoalimentar.value = ''; 

  }

}

function js_mostramer_grupoalimento1(chave1,chave2) {

  document.form1.me35_i_grupoalimentar.value = chave1;
  document.form1.me30_c_descricao.value      = chave2;
  db_iframe_mer_grupoalimento.hide();

}



function js_pesquisame35_i_unidade(mostra) {
	  if (mostra == true) {
	      
	    js_OpenJanelaIframe('','db_iframe_matunid',
	                        'func_matunid.php?funcao_js=parent.js_mostramatunid1|m61_codmatunid|m61_descr',
	                        'Pesquisa',true
	                       );
	    
	  } else {
	    if (document.form1.me35_i_unidade.value != '') {
	        
	      js_OpenJanelaIframe('','db_iframe_matunid',
	                          'func_matunid.php?pesquisa_chave='+document.form1.me35_i_unidade.value+
	                          '&funcao_js=parent.js_mostramatunid','Pesquisa',false
	                         );
	      
	    } else {
	      document.form1.m61_descr.value = '';
	    }
	 }
	}

	function js_mostramatunid(chave,erro) {
	    
	  document.form1.m61_descr.value = chave;
	  if (erro == true) {
	      
	    document.form1.me35_i_unidade.focus();
	    document.form1.me35_i_unidade.value = '';
	    
	  }
	}

	function js_mostramatunid1(chave1,chave2) {
	    
	  document.form1.me35_i_unidade.value = chave1;
	  document.form1.m61_descr.value      = chave2;
	  db_iframe_matunid.hide();
	  
	}

function js_pesquisa() {

  js_OpenJanelaIframe('','db_iframe_mer_alimento',
                       'func_mer_alimento.php?funcao_js=parent.js_preenchepesquisa|me35_i_codigo','Pesquisa',true);

}

function js_preenchepesquisa(chave) {

  db_iframe_mer_alimento.hide();
  <?
  if ($db_opcao != 1) {

    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_novo() {
  parent.location.href='mer1_mer_infnutricionalabas001.php';
}
</script>