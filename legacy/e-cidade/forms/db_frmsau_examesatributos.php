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
$clsau_examesatributos->rotulo->label();
$clrotulo = new rotulocampo;
//$clrotulo->label("s108_i_codigo");
//$clrotulo->label("s107_i_codigo");
$clrotulo->label("s108_c_exame");
$clrotulo->label("s131_c_descricao");
$clrotulo->label("s131_i_codigo");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ts132_i_codigo?>">
       <?=@$Ls132_i_codigo?>
    </td>
    <td> 
     <?
      db_input('s132_i_codigo',10,$Is132_i_codigo,true,'text',3,"")
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts108_c_exame?>">
      <?=@$Ls108_c_exame?>
    </td>
    <td> 
    <?
     db_input('s131_i_exames',10,@$Is131_i_exames,true,'text',3,"")
    ?>
    <?
     db_input('s108_c_exame',40,@$Is108_c_exame,true,'text',3,'')
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts131_i_codigo?>">
       <?
       db_ancora(@$Ls131_i_codigo,"js_pesquisas132_i_atributoexames(true);",$db_opcao);
       ?>
    </td>
    <td> 
     <?
       db_input('s132_i_atributoexames',10,$Is132_i_atributoexames,true,'text',$db_opcao," onchange='js_pesquisas132_i_atributoexames(false);'")
     ?>
     <?
       db_input('s131_c_descricao',40,@$Is131_c_descricao,true,'text',3,'')
     ?>
    </td>
  </tr>
  </table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
<table>
<tr>
	<td>
		<?
		$chavepri= array("s132_i_atributoexames"=>@$s132_i_atributoexames,"s132_i_codigo"=>@$s132_i_codigo);
		$cliframe_alterar_excluir->chavepri=$chavepri;
		@$cliframe_alterar_excluir->sql = $clsau_examesatributos->sql_query(null,'*',null,"s131_i_exames=$s131_i_exames");
		$cliframe_alterar_excluir->campos  ="s132_i_codigo,s131_c_descricao";
		$cliframe_alterar_excluir->legenda       ="ATRIBUTOS LANÇADOS";
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
function js_pesquisas132_i_atributoexames(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a3','db_iframe_sau_examesatributos','func_sau_atributoexames.php?funcao_js=parent.js_mostrasau_atributos1|s131_i_codigo|s131_c_descricao','Pesquisa',true);
  }else{
     if(document.form1.s132_i_atributoexames.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_a3','db_iframe_sau_examesatributos','func_sau_atributoexames.php?pesquisa_chave='+document.form1.s132_i_atributoexames.value+'&funcao_js=parent.js_mostrasau_atributos','Pesquisa',false);
     }else{
       document.form1.s131_i_codigo.value = ''; 
     }
  }
}
function js_mostrasau_atributos(chave,erro){
  document.form1.s131_c_descricao.value = chave; 
  if(erro==true){ 
    document.form1.s132_i_atributoexames.focus(); 
    document.form1.s132_i_atributoexames.value = ''; 
  }
}
function js_mostrasau_atributos1(chave1,chave2){
  document.form1.s132_i_atributoexames.value = chave1;
  document.form1.s131_c_descricao.value = chave2;
  db_iframe_sau_examesatributos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_a3','db_iframe_sau_examesatributos','func_sau_examerequisitos.php?funcao_js=parent.js_preenchepesquisa|s132_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_examesatributos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
</script>