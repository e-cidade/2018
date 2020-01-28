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
$clmodcarneexcessao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k49_cadmodcarne");
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
     $k36_modcarnepadraotipo = "";
     $k36_ip = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk36_sequencial?>">
       <?=@$Lk36_sequencial?>
    </td>
    <td> 
<?
db_input('k36_sequencial',10,$Ik36_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk36_modcarnepadraotipo?>">
       <?
       db_ancora(@$Lk36_modcarnepadraotipo,"js_pesquisak36_modcarnepadraotipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k36_modcarnepadraotipo',10,$Ik36_modcarnepadraotipo,true,'text',$db_opcao," onchange='js_pesquisak36_modcarnepadraotipo(false);'")
?>
       <?
db_input('k49_cadmodcarne',10,$Ik49_cadmodcarne,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk36_ip?>">
       <?=@$Lk36_ip?>
    </td>
    <td> 
<?
db_input('k36_ip',15,$Ik36_ip,true,'text',$db_opcao,"")
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
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("k36_sequencial"=>@$k36_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clmodcarneexcessao->sql_query_file($k36_sequencial);
	 $cliframe_alterar_excluir->campos  ="k36_sequencial,k36_modcarnepadraotipo,k36_ip";
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
function js_pesquisak36_modcarnepadraotipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_modcarneexcessao','db_iframe_modcarnepadraotipo','func_modcarnepadraotipo.php?funcao_js=parent.js_mostramodcarnepadraotipo1|k49_sequencial|k49_cadmodcarne','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.k36_modcarnepadraotipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_modcarneexcessao','db_iframe_modcarnepadraotipo','func_modcarnepadraotipo.php?pesquisa_chave='+document.form1.k36_modcarnepadraotipo.value+'&funcao_js=parent.js_mostramodcarnepadraotipo','Pesquisa',false);
     }else{
       document.form1.k49_cadmodcarne.value = ''; 
     }
  }
}
function js_mostramodcarnepadraotipo(chave,erro){
  document.form1.k49_cadmodcarne.value = chave; 
  if(erro==true){ 
    document.form1.k36_modcarnepadraotipo.focus(); 
    document.form1.k36_modcarnepadraotipo.value = ''; 
  }
}
function js_mostramodcarnepadraotipo1(chave1,chave2){
  document.form1.k36_modcarnepadraotipo.value = chave1;
  document.form1.k49_cadmodcarne.value = chave2;
  db_iframe_modcarnepadraotipo.hide();
}
</script>