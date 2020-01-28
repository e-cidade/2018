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
$clrhestagiocomissaomembro->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("h59_sequencial");
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
     $h60_regist = "";
     //$h60_rhestagiocomissao = "";
     $z01_nome = ''; 
     $h60_tipo = "";
     $h60_sequencial= "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
<fieldset><legend><b>dados do Membro</b></legend> 
<table border="0">
  <tr>
    <td nowrap title="<?=@$Th60_sequencial?>">
       <?=@$Lh60_sequencial?>
    </td>
    <td> 
<?
db_input('h60_sequencial',10,$Ih60_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th60_regist?>">
       <?
       db_ancora(@$Lh60_regist,"js_pesquisah60_regist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h60_regist',6,$Ih60_regist,true,'text',$db_opcao," onchange='js_pesquisah60_regist(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr style='display:none'>
    <td nowrap title="<?=@$Th60_rhestagiocomissao?>">
       <?
       db_ancora(@$Lh60_rhestagiocomissao,"js_pesquisah60_rhestagiocomissao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h60_rhestagiocomissao',10,$Ih60_rhestagiocomissao,true,'text',$db_opcao," onchange='js_pesquisah60_rhestagiocomissao(false);'")
?>
       <?
db_input('h59_sequencial',10,$Ih59_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th60_tipo?>">
       <?=@$Lh60_tipo?>
    </td>
    <td> 
<?
$x = array('1'=>'Presidente','2'=>'Membro');
db_select('h60_tipo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </fieldset>
  </tr>
  <tr>
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
	 $chavepri= array("h60_sequencial"=>@$h60_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clrhestagiocomissaomembro->sql_query(null,"distinct h60_sequencial,h60_regist,
                                        z01_nome,h60_rhestagiocomissao,
                                        case when h60_tipo = 1 then 'Presidente' else 'Membro' end as h60_tipo",null,"h60_rhestagiocomissao=$h60_rhestagiocomissao");
	 $cliframe_alterar_excluir->campos  ="h60_sequencial,h60_regist,z01_nome,h60_rhestagiocomissao,h60_tipo";
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
function js_pesquisah60_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhestagiocomissaomembro','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome','Pesquisa',true,'0');
  }else{
     if(document.form1.h60_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_rhestagiocomissaomembro','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.h60_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostrarhpessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.h60_regist.focus(); 
    document.form1.h60_regist.value = ''; 
  }
}
function js_mostrarhpessoal1(chave1,chave2){
  document.form1.h60_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rhpessoal.hide();
}
function js_pesquisah60_rhestagiocomissao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhestagiocomissaomembro','db_iframe_rhestagiocomissao','func_rhestagiocomissao.php?funcao_js=parent.js_mostrarhestagiocomissao1|h59_sequencial|h59_sequencial','Pesquisa',true,'0');
  }else{
     if(document.form1.h60_rhestagiocomissao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_rhestagiocomissaomembro','db_iframe_rhestagiocomissao','func_rhestagiocomissao.php?pesquisa_chave='+document.form1.h60_rhestagiocomissao.value+'&funcao_js=parent.js_mostrarhestagiocomissao','Pesquisa',false);
     }else{
       document.form1.h59_sequencial.value = ''; 
     }
  }
}
function js_mostrarhestagiocomissao(chave,erro){
  document.form1.h59_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.h60_rhestagiocomissao.focus(); 
    document.form1.h60_rhestagiocomissao.value = ''; 
  }
}
function js_mostrarhestagiocomissao1(chave1,chave2){
  document.form1.h60_rhestagiocomissao.value = chave1;
  document.form1.h59_sequencial.value = chave2;
  db_iframe_rhestagiocomissao.hide();
}
</script>