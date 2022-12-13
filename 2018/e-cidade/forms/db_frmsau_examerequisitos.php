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

//MODULO: Ambulatorial
$clsau_examerequisitos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("s108_i_codigo");
$clrotulo->label("s107_i_codigo");
$clrotulo->label("s108_c_exame");
$clrotulo->label("s107_c_requisito");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ts109_i_codigo?>">
       <?=@$Ls109_i_codigo?>
    </td>
    <td> 
     <?
      db_input('s109_i_codigo',10,$Is109_i_codigo,true,'text',3,"")
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts108_c_exame?>">
      <?=@$Ls108_c_exame?>
    </td>
    <td> 
    <?
     db_input('s109_i_exame',10,@$Is109_i_exame,true,'text',3,"")
    ?>
    <?
     db_input('s108_c_exame',40,@$Is108_c_exame,true,'text',3,'')
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts109_i_requisito?>">
       <?
       db_ancora(@$Ls109_i_requisito,"js_pesquisas109_i_requisito(true);",$db_opcao);
       ?>
    </td>
    <td> 
     <?
       db_input('s109_i_requisito',10,$Is109_i_requisito,true,'text',$db_opcao," onchange='js_pesquisas109_i_requisito(false);'")
     ?>
     <?
       db_input('s107_c_requisito',40,@$Is107_c_requisito,true,'text',3,'')
     ?>
    </td>
  </tr>
  </table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<table>
<tr>
	<td>
		<?
		$chavepri= array("s109_i_requisito"=>@$s109_i_requisito,"s109_i_codigo"=>@$s109_i_codigo);
		$cliframe_alterar_excluir->chavepri=$chavepri;
		@$cliframe_alterar_excluir->sql = $clsau_examerequisitos->sql_query(null,'*',null,"s109_i_exame=$s109_i_exame");
		$cliframe_alterar_excluir->campos  ="s109_i_codigo,s107_c_requisito";
		$cliframe_alterar_excluir->legenda       ="ITENS DO EXAME";
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
function js_pesquisas109_i_requisito(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_requisitos','func_sau_requisitos.php?funcao_js=parent.js_mostrasau_requisitos1|s107_i_codigo|s107_c_requisito','Pesquisa',true);
  }else{
     if(document.form1.s109_i_requisito.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_sau_requisitos','func_sau_requisitos.php?pesquisa_chave='+document.form1.s109_i_requisito.value+'&funcao_js=parent.js_mostrasau_requisitos','Pesquisa',false);
     }else{
       document.form1.s107_i_codigo.value = ''; 
     }
  }
}
function js_mostrasau_requisitos(chave,erro){
  document.form1.s107_c_requisito.value = chave; 
  if(erro==true){ 
    document.form1.s109_i_requisito.focus(); 
    document.form1.s109_i_requisito.value = ''; 
  }
}
function js_mostrasau_requisitos1(chave1,chave2){
  document.form1.s109_i_requisito.value = chave1;
  document.form1.s107_c_requisito.value = chave2;
  db_iframe_sau_requisitos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_sau_examerequisitos','func_sau_examerequisitos.php?funcao_js=parent.js_preenchepesquisa|s109_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_examerequisitos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>