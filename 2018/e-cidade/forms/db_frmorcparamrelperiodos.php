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

//MODULO: orcamento
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clorcparamrelperiodos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o114_descricao");
$clrotulo->label("o42_descrrel");
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
     $o113_periodo = "";
		 $o114_descricao = "";
		 $o113_sequencial = "";
   }
} 
?>
<form name="form1" method="post" action="">
	<fieldset style="width:700px">
<center>
<table border="0">
  <tr>
		<?
		db_input('o113_sequencial',10,$Io113_sequencial,true,'hidden',3,"")
		?>
  </tr>
  
	<tr>
  <!--
	  <td nowrap title="<?=@$To113_orcparamrel?>">
       <?
     //  db_ancora(@$Lo113_orcparamrel,"js_pesquisao113_orcparamrel(true);",$db_opcao);
       ?>
    </td>
		-->
  <td> <b> Código do relátorio </b> </td>
	  <td> 
			<?
			db_input('o113_orcparamrel',8,$Io113_orcparamrel,true,'text',3," onchange='js_pesquisao113_orcparamrel(false);'");
			db_input('o42_descrrel',50,$Io42_descrrel,true,'text',3,'')
      ?>
    </td>
  </tr>
	
  <tr>
    <td nowrap title="<?=@$To113_periodo?>">
       <?
       db_ancora(@$Lo113_periodo,"js_pesquisao113_periodo(true);",$db_opcao);
       ?>
    </td>
    <td> 
			<?
			db_input('o113_periodo',8,$Io113_periodo,true,'text',$db_opcao," onchange='js_pesquisao113_periodo(false);'");
			db_input('o114_descricao',50,$Io114_descricao,true,'text',3,'')
      ?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
	
	</fieldset>
	
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("o113_sequencial"=>@$o113_sequencial);
	 $cliframe_alterar_excluir->chavepri = $chavepri;
	 $cliframe_alterar_excluir->sql      = $clorcparamrelperiodos->sql_query(null,"*",
                                                                           null,
		 																																			 "o113_orcparamrel = $o113_orcparamrel");

	 $cliframe_alterar_excluir->campos  ="o113_sequencial,o114_descricao,o113_orcparamrel";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
	</fieldset>
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
function js_pesquisao113_periodo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcparamrelperiodos','db_iframe_periodo','func_periodo.php?funcao_js=parent.js_mostraperiodo1|o114_sequencial|o114_descricao','Pesquisa',true,'0','1');
  }else{
     if(document.form1.o113_periodo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_orcparamrelperiodos','db_iframe_periodo','func_periodo.php?pesquisa_chave='+document.form1.o113_periodo.value+'&funcao_js=parent.js_mostraperiodo','Pesquisa',false);
     }else{
       document.form1.o114_descricao.value = ''; 
     }
  }
}
function js_mostraperiodo(chave,erro){
  document.form1.o114_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o113_periodo.focus(); 
    document.form1.o113_periodo.value = ''; 
  }
}
function js_mostraperiodo1(chave1,chave2){
  document.form1.o113_periodo.value = chave1;
  document.form1.o114_descricao.value = chave2;
  db_iframe_periodo.hide();
}
function js_pesquisao113_orcparamrel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcparamrelperiodos','db_iframe_orcparamrel','func_orcparamrel.php?funcao_js=parent.js_mostraorcparamrel1|o42_codparrel|o42_descrrel','Pesquisa',true,'0','1');
  }else{
		
		alert('else');
		
     if(document.form1.o113_orcparamrel.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_orcparamrelperiodos','db_iframe_orcparamrel','func_orcparamrel.php?pesquisa_chave='+document.form1.o113_orcparamrel.value+'&funcao_js=parent.js_mostraorcparamrel','Pesquisa',false);
     }else{
       document.form1.o42_descrrel.value = ''; 
     }
  }
}
function js_mostraorcparamrel(chave,erro){
  document.form1.o42_descrrel.value = chave; 
  if(erro==true){ 
    document.form1.o113_orcparamrel.focus(); 
    document.form1.o113_orcparamrel.value = ''; 
  }
}
function js_mostraorcparamrel1(chave1,chave2){
  document.form1.o113_orcparamrel.value = chave1;
  document.form1.o42_descrrel.value = chave2;
  db_iframe_orcparamrel.hide();
}


</script>