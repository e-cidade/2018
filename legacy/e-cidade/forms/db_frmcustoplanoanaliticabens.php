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

//MODULO: custos
$clcustoplanoanaliticabens->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cc05_sequencial");
?>
<form name="form1" method="post" action="<?$db_action?>">
<center>
<fieldset>
  <table border="0">
   <tr>
   	 <td>
   	 	<?=$Lcc05_custoplanoanalitica?>
	 </td>
     <td> 
        <?
          db_input("cc05_sequencial",10,"",true,'hidden',$db_opcao,"");
          db_input('cc05_custoplanoanalitica',10,$Icc05_custoplanoanalitica,true,'text',3,"");   
        ?>
     </td>
   </tr>  	
   <tr>
     <td> 
        <?
	      db_ancora(@$Lcc05_bens,"js_pesquisacc05_bens(true);",$db_opcao);
        ?>
     </td>
     <td>
     	<?
           db_input('cc05_bens',10,$Icc05_bens,true,'text',$db_opcao,"onChange='js_pesquisacc05_bens(false);'");
           db_input('t52_descr',40,$Icc05_bens,true,'text',3,"");
        ?>
     </td>
   </tr>
  </table>
 </fieldset>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
 </center>
 
 <br /> 
 
 <?
    //função altera_exclui
	include("dbforms/db_classesgenericas.php");
    $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

	 $chavepri = array("cc05_sequencial"=>@$cc05_sequencial);
	 $cliframe_alterar_excluir->chavepri = $chavepri;
	 $cliframe_alterar_excluir->sql      = $clcustoplanoanaliticabens->sql_query(null,'cc05_sequencial, cc05_bens, t52_descr',null,"  cc05_custoplanoanalitica = ".@$cc05_custoplanoanalitica);
	 $cliframe_alterar_excluir->campos   = "cc05_bens, t52_descr";
	 $cliframe_alterar_excluir->legenda  = "ITENS LANÇADOS";
	 $cliframe_alterar_excluir->opcoes   = 3;
	 $cliframe_alterar_excluir->iframe_height = "300";
	 $cliframe_alterar_excluir->iframe_width  = "800";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
?>	


</form>

<script>
	
function js_pesquisacc05_bens(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_bens','func_bens.php?funcao_js=parent.js_mostrabens1|t52_bem|t52_descr','Pesquisa',true);
  }else{
     if(document.form1.cc05_bens.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_bens','func_bens.php?pesquisa_chave='+document.form1.cc05_bens.value+'&funcao_js=parent.js_mostrabens','Pesquisa',false);
     }else{
       document.form1.t52_descr.value = ''; 
     }
  }
}
function js_mostrabens(chave,erro){
  document.form1.t52_descr.value = chave; 
  if(erro==true){ 
    document.form1.cc05_bens.focus(); 
    document.form1.cc05_bens.value = ''; 
  }
}
	function js_mostrabens1(chave1,chave2){
  document.form1.cc05_bens.value = chave1;
  document.form1.t52_descr.value = chave2;
  db_iframe_bens.hide();
}
function js_pesquisacc05_custoplanoanaliticabens(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_custoplanoanaliticabens','func_custoplanoanaliticabens.php?funcao_js=parent.js_mostracustoplanoanaliticabens1|cc05_sequencial|cc05_custoplanoanaliticabens','Pesquisa',true);
  }else{
     if(document.form1.cc05_custoplanoanaliticabens.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_custoplanoanaliticabens','func_custoplanoanaliticabens.php?pesquisa_chave='+document.form1.cc05_custoplanoanaliticabens.value+'&funcao_js=parent.js_mostracustoplanoanaliticabens','Pesquisa',false);
     }else{
       document.form1.cc05_custoplanoanaliticabens.value = ''; 
     }
  }
}
function js_mostracustoplanoanaliticabens(chave,erro){
  document.form1.cc05_custoplanoanaliticabens.value = chave; 
  if(erro==true){ 
    document.form1.cc05_custoplanoanaliticabens.focus(); 
    document.form1.cc05_custoplanoanaliticabens.value = ''; 
  }
}
function js_mostracustoplanoanaliticabens1(chave1,chave2){
  document.form1.cc05_custoplanoanaliticabens.value = chave1;
  document.form1.cc05_custoplanoanaliticabens.value = chave2;
  db_iframe_custoplanoanaliticabens.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_custoplanoanaliticabens','func_custoplanoanaliticabens.php?funcao_js=parent.js_preenchepesquisa|cc05_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_custoplanoanaliticabens.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>