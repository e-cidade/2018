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
$clmer_restriitem->rotulo->label();
include("dbforms/db_classesgenericas.php");
$clrotulo = new rotulocampo;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo->label("me24_i_codigo");
$clrotulo->label("me35_c_nomealimento");
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed47_v_nome");
$clrotulo->label("me14_i_aluno");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tme25_i_codigo?>">
   <?=@$Lme25_i_codigo?>
  </td>
  <td>
   <?db_input('me25_i_codigo',10,$Ime25_i_codigo,true,'text',3,"");?>
   <?db_input('me25_i_restricao',10,$Ime25_i_restricao,true,'hidden',3,'');?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted47_i_codigo?>">
   <?=@$Lme14_i_aluno?>
  </td>
  <td>
   <?
   $ed47_i_codigo=@$me24_i_aluno;
   db_input('ed47_i_codigo',10,@$Ied47_i_codigo,true,'text',3,"")
   ?>
   <?db_input('ed47_v_nome',40,@$Ied47_v_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme25_i_alimento?>">
   <?db_ancora(@$Lme25_i_alimento,"js_pesquisame25_i_alimento(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('me25_i_alimento',10,$Ime25_i_alimento,true,'text',$db_opcao,
              " onchange='js_pesquisame25_i_alimento(false);'"
             )
   ?>
   <?db_input('me35_c_nomealimento',40,@$Ime35_c_nomealimento,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme25_i_alimentosub?>">
   <?db_ancora(@$Lme25_i_alimentosub,"js_pesquisame25_i_alimentosub(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('me25_i_alimentosub',10,@$Ime25_i_alimentosub,true,'text',$db_opcao,
               "onchange='js_pesquisame25_i_alimentosub(false);'"
             )
   ?>
   <?db_input('me35_c_nomealimento2',40,@$Ime35_c_nomealimento2,true,'text',3,'')?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="button" id="cancela" value="Cancelar" 
       onclick="js_cancela();"  <?=($db_botao==false?"disabled":"")?> >
<br><br>
<?
$chavepri                           = array("me25_i_codigo"=>@$me25_i_codigo);
$cliframe_alterar_excluir->chavepri = $chavepri;
if (isset($me25_i_restricao) && @$me25_i_restricao != "") {
 $campos                        = " me25_i_codigo,me25_i_alimento,mer_alimento.me35_c_nomealimento,";
 $campos                       .= " alimento.me35_c_nomealimento,me25_i_alimentosub,";
 $campos                       .= " alimento.me35_c_nomealimento as me33_c_descr";
 @$cliframe_alterar_excluir->sql = $clmer_restriitem->sql_query(null,
                                                               $campos,
                                                               null,
                                                               " me25_i_restricao = $me25_i_restricao"
                                                              );
}
@$cliframe_alterar_excluir->campos        ="me25_i_codigo,me35_c_nomealimento,me25_i_alimentosub,me33_c_descr ";
$cliframe_alterar_excluir->legenda       ="Itens Restritos";
$cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
$cliframe_alterar_excluir->textocabec    = "darkblue";
$cliframe_alterar_excluir->textocorpo    = "black";
$cliframe_alterar_excluir->fundocabec    = "#aacccc";
$cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
$cliframe_alterar_excluir->iframe_width  = "100%";
$cliframe_alterar_excluir->iframe_height = "200";
$cliframe_alterar_excluir->opcoes        = 1;
$cliframe_alterar_excluir->iframe_alterar_excluir(1);
?>
</center>
</form>
<script>
function js_pesquisame25_i_alimento(mostra) {
	
 if (mostra==true) {
	 
   js_OpenJanelaIframe('','db_iframe_mer_alimento',
		               'func_mer_alimento.php?funcao_js=parent.js_mostraalimento1|me35_i_codigo|me35_c_nomealimento',
		               'Pesquisa',true
		              );
   
 } else {
	 
   if (document.form1.me25_i_alimento.value != '') {
	   
     js_OpenJanelaIframe('','db_iframe_mer_alimento',
    	                 'func_mer_alimento.php?pesquisa_chave='+document.form1.me25_i_alimento.value+
		                 '&funcao_js=parent.js_mostraalimento','Pesquisa',false
		                );
     
   } else {
     document.form1.me35_c_nomealimento.value = '';
   }
 }
}

function js_mostraalimento(chave,erro) {
	
  document.form1.me35_c_nomealimento.value = chave;
  if (erro==true) {
	 
    document.form1.me25_i_alimento.focus();
    document.form1.me25_i_alimento.value = '';
  
  } 
}

function js_mostraalimento1(chave1,chave2) {
	
  document.form1.me25_i_alimento.value     = chave1;
  document.form1.me35_c_nomealimento.value = chave2;
  db_iframe_mer_alimento.hide();
  
}

function js_pesquisame25_i_alimentosub(mostra) {
	
  if (mostra==true) {
	  
    js_OpenJanelaIframe('','db_iframe_mer_alimento',
    	                'func_mer_alimento.php?funcao_js=parent.js_mostramatmater3|me35_i_codigo|me35_c_nomealimento',
    	                'Pesquisa',true
    	               );
    
  } else {
	  
    if (document.form1.me25_i_alimentosub.value != '') {
        
      js_OpenJanelaIframe('','db_iframe_mer_alimento',
    	                  'func_mer_alimento.php?pesquisa_chave='+document.form1.me25_i_alimentosub.value+
    	                  '&funcao_js=parent.js_mostramatmater2','Pesquisa',false
    	                 );
      
    } else {
      document.form1.me35_c_nomealimento2.value = '';
    }
  }
}

function js_mostramatmater2(chave,erro) {
	
  document.form1.me35_c_nomealimento2.value = chave;
  if (erro==true) {
	  
    document.form1.me25_i_alimentosub.focus();
    document.form1.me25_i_alimentosub.value = '';
    
  }
}

function js_mostramatmater3(chave1,chave2) {
	
  document.form1.me25_i_alimentosub.value   = chave1;
  document.form1.me35_c_nomealimento2.value = chave2;
  db_iframe_mer_alimento.hide();
  
}

function js_cancela() {
	
  location.href='mer1_mer_restriitem001.php?me25_i_restricao=<?=$me25_i_restricao?>&me24_i_aluno=<?=$me24_i_aluno?>'+
                '&ed47_v_nome=<?=$ed47_v_nome?>';
  
}
</script>