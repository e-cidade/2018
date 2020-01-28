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

//MODULO: cadastro
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cliptunaogeracarnesetqua->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j66_data");
$clrotulo->label("j30_descr");
$clrotulo->label("j37_face");
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
     $j37_face = "";
		 $j67_sequencial = "";
     $j67_setor = "";
     $j67_quadra = "";
		 $j30_descr = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?//=@$Tj67_sequencial?>">
       <?//=@$Lj67_sequencial?>
    </td>
    <td> 
<?
db_input('j67_sequencial',10,$Ij67_sequencial,true,'hidden',3,"");
db_input('j67_naogeracarne',10,$Ij67_naogeracarne,true,'hidden',3,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj37_face?>">
       <?
       db_ancora(@$Lj37_face,"js_pesquisaj37_face(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j37_face',4,$Ij37_face,true,'text',$db_opcao," onchange='js_pesquisaj37_face(false);'")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tj67_setor?>">
       <?=@$Lj67_setor?>
    </td>
    <td> 
<?
db_input('j67_setor',4,$Ij67_setor,true,'text',3,"")
?>
       <?
db_input('j30_descr',40,$Ij30_descr,true,'text',3,'')
       ?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tj67_quadra?>">
       <?=@$Lj67_quadra?>
    </td>
    <td> 
<?
db_input('j67_quadra',4,$Ij67_quadra,true,'text',3,"")
?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
 <?if ($db_opcao==1){?>
 <input name="selface" type="button" id="selface" value="Selecionar Faces" onclick="js_selface();">
 <?}?>
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("j67_sequencial"=>@$j67_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $cliptunaogeracarnesetqua->sql_query(null,"*",null,"j67_naogeracarne=$j67_naogeracarne");
	 $cliframe_alterar_excluir->campos  ="j67_setor,j67_quadra";
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
function js_selface(){
  js_OpenJanelaIframe('top.corpo.iframe_iptunaogeracarnesetqua','db_iframe_selface','cad4_selfaces001.php?j67_naogeracarne='+document.form1.j67_naogeracarne.value,'Seleciona faces',true,0);	
}
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisaj37_face(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_iptunaogeracarnesetqua','db_iframe_face','func_facealt.php?funcao_js=parent.js_mostraface1|j37_face|j37_setor|j30_descr|j37_quadra','Pesquisa');
  }else{
     if(document.form1.j37_face.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_iptunaogeracarnesetqua','db_iframe_face','func_facealt.php?pesquisa_chave='+document.form1.j37_face.value+'&funcao_js=parent.js_mostraface','Pesquisa',false);
     }else{
       document.form1.j67_setor.value = ''; 
       document.form1.j30_descr.value = ''; 
       document.form1.j67_quadra.value = ''; 
     }
  }
}
function js_mostraface(setor,descr,quadra,erro){
  document.form1.j67_setor.value = setor;
  document.form1.j30_descr.value = descr; 
  document.form1.j67_quadra.value = quadra;
  if(erro==true){ 
    document.form1.j37_face.focus(); 
    document.form1.j37_face.value = ''; 
    document.form1.j67_setor.value = ''; 
    document.form1.j30_descr.value = ''; 
    document.form1.j67_quadra.value = ''; 
  }
}
function js_mostraface1(face,setor,descr,quadra){
  document.form1.j37_face.value = face;
  document.form1.j67_setor.value = setor;
  document.form1.j30_descr.value = descr; 
  document.form1.j67_quadra.value = quadra;
  db_iframe_face.hide();
}
</script>