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

//MODULO: licitação
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clpccflicitapar->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("l03_codigo");
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
     $l25_anousu = "";
     $l25_numero = "";
   }
} 
?>
<style>
td {
  white-space: nowrap
}
fieldset table td:first-child {
              width: 80px;
              white-space: nowrap
}
</style>
<form name="form1" method="post" action="">
<fieldset>
<legend><b>Numeração</b></legend>
<table border="0" align="left">
  <tr>
    <td nowrap title="<?//=@$Tl25_codigo?>">
       <?//=@$Ll25_codigo?>
    </td>
    <td> 
<?
db_input('l25_codigo',6,$Il25_codigo,true,'hidden',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?//=@$Tl25_codcflicita?>">
       <?
       //db_ancora(@$Ll25_codcflicita,"js_pesquisal25_codcflicita(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('l25_codcflicita',6,$Il25_codcflicita,true,'hidden',3," onchange='js_pesquisal25_codcflicita(false);'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl25_anousu?>">
       <?=@$Ll25_anousu?>
    </td>
    <td> 
<?

db_input('l25_anousu',8,$Il25_anousu,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl25_numero?>">
       <?=@$Ll25_numero?>
    </td>
    <td> 
<?
db_input('l25_numero',8,$Il25_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
 </table>
</fieldset>
 <table cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
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
  <tr>
    <td>&nbsp;</td>
  </tr>
 </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("l25_codigo"=>@$l25_codigo);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clpccflicitapar->sql_query_file(@$l25_codigo,"*",null,"l25_codcflicita=$l25_codcflicita");
	 $cliframe_alterar_excluir->campos  ="l25_codigo,l25_codcflicita,l25_anousu,l25_numero";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="550";
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
function js_pesquisal25_codcflicita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_pccflicitapar','db_iframe_cflicita','func_cflicita.php?funcao_js=parent.js_mostracflicita1|l03_codigo|l03_codigo','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.l25_codcflicita.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_pccflicitapar','db_iframe_cflicita','func_cflicita.php?pesquisa_chave='+document.form1.l25_codcflicita.value+'&funcao_js=parent.js_mostracflicita','Pesquisa',false);
     }else{
       document.form1.l03_codigo.value = ''; 
     }
  }
}
function js_mostracflicita(chave,erro){
  document.form1.l03_codigo.value = chave; 
  if(erro==true){ 
    document.form1.l25_codcflicita.focus(); 
    document.form1.l25_codcflicita.value = ''; 
  }
}
function js_mostracflicita1(chave1,chave2){
  document.form1.l25_codcflicita.value = chave1;
  document.form1.l03_codigo.value = chave2;
  db_iframe_cflicita.hide();
}
</script>