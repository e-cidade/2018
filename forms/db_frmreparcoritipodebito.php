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

//MODULO: caixa
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
//$clmodcarnepadraotipo->rotulo->label();
$clrecreparcarretipo->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("k00_descr");
//$clrotulo->label("k48_cadmodcarne");

if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $k72_arretipo = "";
     $k00_descr = "";
   }
} 
?>
<form name="form1" method="post" action="">
<? 
  db_input('k72_codigo',50,$Ik72_codigo,true,'hidden',3,'');
  db_input('k72_sequencial',50,$Ik72_codigo,true,'hidden',3,'');
?>
<center>
<table>
<tr>
<td>
<fieldset style="width: 700px;">
  <table border="0" align="center">
    <tr>
		  <td nowrap title="<?=@$Tk49_tipo?>">
			  <?
			  db_ancora(@$Lk72_arretipo,"js_pesquisak72_arretipo(true);",$db_opcao);
			  ?>
			</td>
			<td> 
			  <?
				db_input('k72_arretipo',10,$Ik72_arretipo,true,'text',$db_opcao," onchange='js_pesquisak72_arretipo(false);'");
				db_input('k00_descr',50,$Ik00_descr,true,'text',3,'');
				?>
			</td>	
    </tr>
    </table>
</fieldset>
<table align="center">
  <tr>
  <td colspan="2" align="center">
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
         type="submit" id="db_opcao" 
         value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
                <?=($db_botao==false?"disabled":"")?>  >
  <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" 
                <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
  </td>
  </tr>
</table>
</td>
</tr>
</table>
<table>
  <tr>
    <td valign="top"  align="center">  
	    <?
		  $chavepri                              = array("k72_sequencial"=>@$k72_sequencial);
		  $cliframe_alterar_excluir->chavepri	   = $chavepri;
		  $cliframe_alterar_excluir->sql     	   = $clrecreparcarretipo->sql_query(null, "*",
		                                                                                 " k72_sequencial ",
		                                                                                 " k72_codigo = ".$k72_codigo);
		  $cliframe_alterar_excluir->campos  	   = "k72_arretipo, k00_descr";
		  $cliframe_alterar_excluir->legenda	   = "ITENS LANÇADOS";
		  $cliframe_alterar_excluir->iframe_height = "160";
		  $cliframe_alterar_excluir->iframe_width  = "700";
		  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
	    ?>
    </td>
  </tr>
</table>
</center>
</form>
<script>

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisak72_arretipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_arretipo',
                        'func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr',
                        'Pesquisa',true,0);
  }else{
     if(document.form1.k72_arretipo.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_arretipo',
                            'func_arretipo.php?pesquisa_chave='+document.form1.k72_arretipo.value+
                            '&funcao_js=parent.js_mostraarretipo',
                            'Pesquisa',false);
     }else{
       document.form1.k00_descr.value = ''; 
     }
  }
}
function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave; 
  if(erro==true){ 
    document.form1.k72_arretipo.focus(); 
    document.form1.k72_arretipo.value = ''; 
  }
}
function js_mostraarretipo1(chave1,chave2){
  document.form1.k72_arretipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
}
function js_pesquisak49_modcarnepadrao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_modcarnepadraotipo',
                        'db_iframe_modcarnepadrao',
                        'func_modcarnepadrao.php?funcao_js=parent.js_mostramodcarnepadrao1|k48_sequencial|k48_cadmodcarne',
                        'Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.k49_modcarnepadrao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_modcarnepadraotipo',
                            'db_iframe_modcarnepadrao',
                            'func_modcarnepadrao.php?pesquisa_chave='+
                                  document.form1.k49_modcarnepadrao.value+
                                  '&funcao_js=parent.js_mostramodcarnepadrao',
                            'Pesquisa',false);
     }else{
       document.form1.k48_cadmodcarne.value = ''; 
     }
  }
}
function js_mostramodcarnepadrao(chave,erro){
  document.form1.k48_cadmodcarne.value = chave; 
  if(erro==true){ 
    document.form1.k49_modcarnepadrao.focus(); 
    document.form1.k49_modcarnepadrao.value = ''; 
  }
}
function js_mostramodcarnepadrao1(chave1,chave2){
  document.form1.k49_modcarnepadrao.value = chave1;
  document.form1.k48_cadmodcarne.value = chave2;
  db_iframe_modcarnepadrao.hide();
}
</script>