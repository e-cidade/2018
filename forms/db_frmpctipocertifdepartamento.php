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

//MODULO: Compras
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clpctipocertifdepartamento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc70_codigo");
$clrotulo->label("pc70_descr");
$clrotulo->label("descrdepto");

if(isset($opcaoal)){
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
     $pc34_pctipocertif = "";
     $pc34_coddepto = "";
   }
} 

if(isset($pc34_pctipocertif) && $pc34_pctipocertif!="") {
	$oPctipocertif  = new cl_pctipocertif();
	$sSql           = $oPctipocertif->sql_query_file($pc34_pctipocertif, "pc70_descr");
	$rsPctipocertif = $oPctipocertif->sql_record($sSql);
	db_fieldsmemory($rsPctipocertif,0);
}
?>
<form name="form1" method="post" action="">
<center>

<table align=center width=630 style="margin-top: 15px;">
<tr><td align=center>

<fieldset>
<legend><b>Departamentos</b></legend>

<table border="0">     
<?
db_input('pc34_sequencial',10,$Ipc34_sequencial,true,'hidden',3,"")
?>   
  <tr>
    <td nowrap title="<?=@$Tpc34_pctipocertif?>">
       <?=$Lpc34_pctipocertif?>
    </td>
    <td> 
<?
db_input('pc34_pctipocertif',10,$Ipc34_pctipocertif,true,'text',3,"")
?>
       <?
db_input('pc70_descr',40,$Ipc70_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc34_coddepto?>">
       <?
       db_ancora(@$Lpc34_coddepto,"js_pesquisapc34_coddepto(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('pc34_coddepto',10,$Ipc34_coddepto,true,'text',$db_opcao," onchange='js_pesquisapc34_coddepto(false);'")
?>
       <?
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  </tr>
 </table>
 
 </fieldset>
 
 </td></tr>
 </table>
 
 
 <table>
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
	 $chavepri= array("pc34_sequencial"=>@$pc34_sequencial);
	 $cliframe_alterar_excluir->chavepri= $chavepri;
	 $sSqlIframe = $clpctipocertifdepartamento->sql_query(null, "*", "", "pc34_pctipocertif = {$pc34_pctipocertif}");
	 $cliframe_alterar_excluir->sql     = $sSqlIframe; 
	 $cliframe_alterar_excluir->campos  = "descrdepto";
	 $cliframe_alterar_excluir->legenda = "ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height = "160";
	 $cliframe_alterar_excluir->iframe_width = "600";
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
function js_pesquisapc34_coddepto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_pctipocertifdepartamento','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true,'0','1');
  }else{
     if(document.form1.pc34_coddepto.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_pctipocertifdepartamento','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.pc34_coddepto.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.pc34_coddepto.focus(); 
    document.form1.pc34_coddepto.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.pc34_coddepto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
</script>