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

//MODULO: Farmacia
$clfar_listaprescricao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("fa20_i_codigo");
$clrotulo->label("fa15_i_codigo");
$clrotulo->label("fa20_c_prescricao");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tfa21_i_codigo?>">
       <?=@$Lfa21_i_codigo?>
    </td>
    <td> 
<?
db_input('fa21_i_codigo',10,$Ifa21_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa20_c_prescricao?>">
      <?=@$Lfa20_c_prescricao?>
    </td>
    <td> 
    <?
     db_input('fa21_i_prescricaomedica',10,@$Ifa21_i_prescricaomedica,true,'text',3,"")
    ?>
    <?
     db_input('fa20_c_prescricao',40,@$Ifa20_c_prescricao,true,'text',3,'')
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa21_i_listacontrolado?>">
       <?
       db_ancora(@$Lfa21_i_listacontrolado,"js_pesquisafa21_i_listacontrolado(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('fa21_i_listacontrolado',10,$Ifa21_i_listacontrolado,true,'text',$db_opcao," onchange='js_pesquisafa21_i_listacontrolado(false);'")
?>
       <?
db_input('fa15_c_listacontrolado',40,@$Ifa15_c_listacontrolado,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<table>
<tr>
	<td>
		<?
		$chavepri= array("fa21_i_listacontrolado"=>@$fa21_i_listacontrolado,"fa21_i_codigo"=>@$fa21_i_codigo);
		$cliframe_alterar_excluir->chavepri=$chavepri;
		@$cliframe_alterar_excluir->sql = $clfar_listaprescricao->sql_query(null,'*',null,"fa21_i_prescricaomedica=$fa21_i_prescricaomedica");
		$cliframe_alterar_excluir->campos  ="fa21_i_codigo,fa15_c_listacontrolado";
		$cliframe_alterar_excluir->legenda       ="ITENS DA PRESCRIÇÃO MÉDICA";
		$cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
		$cliframe_alterar_excluir->textocabec    = "darkblue";
		$cliframe_alterar_excluir->textocorpo    = "black";
		$cliframe_alterar_excluir->fundocabec    = "#aacccc";
		$cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
		$cliframe_alterar_excluir->iframe_width  = "710";
		$cliframe_alterar_excluir->iframe_height = "130";
		$cliframe_alterar_excluir->opcoes = 1;
		$cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
        ?>
	</td>
</tr>	
</table>
</center>
</form>
<script>
function js_pesquisafa21_i_listacontrolado(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_far_listaprescricao','func_far_listacontrolado.php?funcao_js=parent.js_mostrafar_listaprescricao1|fa15_i_codigo|fa15_c_listacontrolado','Pesquisa',true);
  }else{
     if(document.form1.fa21_i_listacontrolado.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_far_listaprescricao','func_far_listacontrolado.php?pesquisa_chave='+document.form1.fa21_i_listacontrolado.value+'&funcao_js=parent.js_mostrafar_listaprescricao','Pesquisa',false);
     }else{
       document.form1.fa15_c_listacontrolado.value = ''; 
     }
  }
}
function js_mostrafar_listaprescricao(chave,erro){
  document.form1.fa15_c_listacontrolado.value = chave; 
  if(erro==true){ 
    document.form1.fa21_i_listacontrolado.focus(); 
    document.form1.fa21_i_listacontrolado.value = ''; 
  }
}
function js_mostrafar_listaprescricao1(chave1,chave2){
  document.form1.fa21_i_listacontrolado.value = chave1;
  document.form1.fa15_c_listacontrolado.value = chave2;
  db_iframe_far_listaprescricao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_far_listaprescricao','func_far_listaprescricao.php?funcao_js=parent.js_preenchepesquisa|fa21_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_far_listaprescricao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>