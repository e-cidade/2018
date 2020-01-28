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
$clcadtipoitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k03_tipo");
$clrotulo->label("k37_descr");
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
//     $k09_cadtipo = "";
     $k09_cadtipoitemgrupo = "";
     $k37_descr = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap >
    </td>
    <td> 
<?
db_input('k09_sequencial',10,$Ik09_sequencial,true,'hidden',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk09_cadtipo?>">
       <?
       db_ancora(@$Lk09_cadtipo,"js_pesquisak09_cadtipo(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('k09_cadtipo',3,$Ik09_cadtipo,true,'text',3," onchange='js_pesquisak09_cadtipo(false);'")
?>
       <?
db_input('k03_tipo',3,$Ik03_tipo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk09_cadtipoitemgrupo?>">
       <?
       db_ancora(@$Lk09_cadtipoitemgrupo,"js_pesquisak09_cadtipoitemgrupo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k09_cadtipoitemgrupo',10,$Ik09_cadtipoitemgrupo,true,'text',$db_opcao," onchange='js_pesquisak09_cadtipoitemgrupo(false);'")
?>
       <?
db_input('k37_descr',40,$Ik37_descr,true,'text',3,'')
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
	 $chavepri= array("k09_sequencial"=>@$k09_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clcadtipoitem->sql_query_file(null,"*",null," k09_cadtipo = $k09_cadtipo ");
	 $cliframe_alterar_excluir->campos  ="k09_sequencial,k09_cadtipo,k09_cadtipoitemgrupo";
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
function js_pesquisak09_cadtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_cadtipoitem','db_iframe_cadtipo','func_cadtipo.php?funcao_js=parent.js_mostracadtipo1|k03_tipo|k03_tipo','Pesquisa',true,'0');
  }else{
     if(document.form1.k09_cadtipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_cadtipoitem','db_iframe_cadtipo','func_cadtipo.php?pesquisa_chave='+document.form1.k09_cadtipo.value+'&funcao_js=parent.js_mostracadtipo','Pesquisa',false);
     }else{
       document.form1.k03_tipo.value = ''; 
     }
  }
}
function js_mostracadtipo(chave,erro){
  document.form1.k03_tipo.value = chave; 
  if(erro==true){ 
    document.form1.k09_cadtipo.focus(); 
    document.form1.k09_cadtipo.value = ''; 
  }
}
function js_mostracadtipo1(chave1,chave2){
  document.form1.k09_cadtipo.value = chave1;
  document.form1.k03_tipo.value = chave2;
  db_iframe_cadtipo.hide();
}
function js_pesquisak09_cadtipoitemgrupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_cadtipoitem','db_iframe_cadtipoitemgrupo','func_cadtipoitemgrupo.php?funcao_js=parent.js_mostracadtipoitemgrupo1|k37_sequencial|k37_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.k09_cadtipoitemgrupo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_cadtipoitem','db_iframe_cadtipoitemgrupo','func_cadtipoitemgrupo.php?pesquisa_chave='+document.form1.k09_cadtipoitemgrupo.value+'&funcao_js=parent.js_mostracadtipoitemgrupo','Pesquisa',false);
     }else{
       document.form1.k37_descr.value = ''; 
     }
  }
}
function js_mostracadtipoitemgrupo(chave,erro){
  document.form1.k37_descr.value = chave; 
  if(erro==true){ 
    document.form1.k09_cadtipoitemgrupo.focus(); 
    document.form1.k09_cadtipoitemgrupo.value = ''; 
  }
}
function js_mostracadtipoitemgrupo1(chave1,chave2){
  document.form1.k09_cadtipoitemgrupo.value = chave1;
  document.form1.k37_descr.value = chave2;
  db_iframe_cadtipoitemgrupo.hide();
}
</script>