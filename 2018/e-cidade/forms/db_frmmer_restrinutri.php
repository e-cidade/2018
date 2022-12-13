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
$clmer_restrinutri->rotulo->label();
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo = new rotulocampo;
$clrotulo->label("me25_i_codigo");
$clrotulo->label("me09_i_codigo");
$clrotulo->label("me09_c_descr");
$clrotulo->label("me14_i_aluno");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tme26_i_codigo?>">
   <?=@$Lme26_i_codigo?>
  </td>
  <td>
   <?db_input('me26_i_codigo',10,$Ime26_i_codigo,true,'text',3,"");?>
   <?db_input('me26_i_restricao',10,$Ime26_i_restricao,true,'hidden',3,'');?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted47_i_codigo?>">
   <?=@$Lme14_i_aluno?>
  </td>
  <td>
   <?
   $ed47_i_codigo=@$me24_i_aluno;
   db_input('ed47_i_codigo',10,@$Ied47_i_codigo,true,'text',3," onchange='js_pesquisame25_i_restricao(false);'")
   ?>
   <?db_input('ed47_v_nome',40,@$Ied47_v_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme26_i_nutriente?>">
   <?db_ancora(@$Lme26_i_nutriente,"js_pesquisame26_i_nutriente(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('me26_i_nutriente',10,$Ime26_i_nutriente,true,'text',$db_opcao," 
               onchange='js_pesquisame26_i_nutriente(false);'"
             )
   ?>
   <?db_input('me09_c_descr',40,$Ime09_c_descr,true,'text',3,'')?>
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
$chavepri= array("me26_i_codigo"=>@$me26_i_codigo);
$cliframe_alterar_excluir->chavepri=$chavepri;
if (isset($me26_i_restricao)&&@$me26_i_restricao!="") {
 @$cliframe_alterar_excluir->sql = $clmer_restrinutri->sql_query(null,'*',null," me26_i_restricao = $me26_i_restricao");
}
$cliframe_alterar_excluir->campos  ="me09_i_codigo,me09_c_descr";
$cliframe_alterar_excluir->legenda       ="Nutrientes Restritos";
$cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
$cliframe_alterar_excluir->textocabec    = "darkblue";
$cliframe_alterar_excluir->textocorpo    = "black";
$cliframe_alterar_excluir->fundocabec    = "#aacccc";
$cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
$cliframe_alterar_excluir->iframe_width  = "100%";
$cliframe_alterar_excluir->iframe_height = "200";
$cliframe_alterar_excluir->opcoes = 1;
$cliframe_alterar_excluir->iframe_alterar_excluir(1);
?>
</center>
</form>
<script>
function js_pesquisame26_i_nutriente(mostra) {
	
  if (mostra==true) {
    js_OpenJanelaIframe('','db_iframe_mer_nutriente',
    	                'func_mer_nutriente.php?funcao_js=parent.js_mostramer_nutriente1|me09_i_codigo|me09_c_descr',
    	                'Pesquisa',true
    	               );
  } else {
	  
    if (document.form1.me26_i_nutriente.value != '') {
        
      js_OpenJanelaIframe('','db_iframe_mer_nutriente',
    	                  'func_mer_nutriente.php?pesquisa_chave='+document.form1.me26_i_nutriente.value+
    	                  '&funcao_js=parent.js_mostramer_nutriente','Pesquisa',false
    	                 );
      
    } else {
      document.form1.me09_i_codigo.value = '';
    }
  }
}

function js_mostramer_nutriente(chave,erro) {
	
  document.form1.me09_c_descr.value = chave;
  if(erro==true){
	  
    document.form1.me26_i_nutriente.focus();
    document.form1.me26_i_nutriente.value = '';
    
  }
}

function js_mostramer_nutriente1(chave1,chave2) {
	
  document.form1.me26_i_nutriente.value = chave1;
  document.form1.me09_c_descr.value = chave2;
  db_iframe_mer_nutriente.hide();
  
}

function js_cancela() {
  location.href='mer1_mer_restrinutri001.php?me26_i_restricao=<?=$me26_i_restricao?>&me24_i_aluno=<?=$me24_i_aluno?>'+
                 '&ed47_v_nome=<?=$ed47_v_nome?>';
}
</script>