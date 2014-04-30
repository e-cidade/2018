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

//MODULO: recursoshumanos
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrhestagiocriterio->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("h50_sequencial");
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
     $h52_sequencial = "";
     $h52_descr = "";
     $h52_pontos = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
<fieldset><b>Dados do Critério</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Th52_sequencial?>">
       <?=@$Lh52_sequencial?>
    </td>
    <td> 
<?
db_input('h52_sequencial',10,$Ih52_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr style='display:none'>
    <td nowrap title="<?=@$Th52_rhestagio?>">
       <?
       db_ancora(@$Lh52_rhestagio,"js_pesquisah52_rhestagio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h52_rhestagio',10,$Ih52_rhestagio,true,'text',$db_opcao," onchange='js_pesquisah52_rhestagio(false);'")
?>
       <?
db_input('h50_sequencial',10,$Ih50_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th52_descr?>">
       <?=@$Lh52_descr?>
    </td>
    <td> 
<?
db_input('h52_descr',40,$Ih52_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th52_pontos?>">
       <?=@$Lh52_pontos?>
    </td>
    <td> 
<?
db_input('h52_pontos',10,$Ih52_pontos,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </fieldset>
  </tr>
  <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("h52_sequencial"=>@$h52_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clrhestagiocriterio->sql_query_file(null,"*","h52_sequencial","h52_rhestagio={$h52_rhestagio}");
	 $cliframe_alterar_excluir->campos  ="h52_sequencial,h52_rhestagio,h52_descr,h52_pontos";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
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
function js_pesquisah52_rhestagio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhestagiocriterio','db_iframe_rhestagio','func_rhestagio.php?funcao_js=parent.js_mostrarhestagio1|h50_sequencial|h50_sequencial','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.h52_rhestagio.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_rhestagiocriterio','db_iframe_rhestagio','func_rhestagio.php?pesquisa_chave='+document.form1.h52_rhestagio.value+'&funcao_js=parent.js_mostrarhestagio','Pesquisa',false);
     }else{
       document.form1.h50_sequencial.value = ''; 
     }
  }
}
function js_mostrarhestagio(chave,erro){
  document.form1.h50_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.h52_rhestagio.focus(); 
    document.form1.h52_rhestagio.value = ''; 
  }
}
function js_mostrarhestagio1(chave1,chave2){
  document.form1.h52_rhestagio.value = chave1;
  document.form1.h50_sequencial.value = chave2;
  db_iframe_rhestagio.hide();
}
</script>