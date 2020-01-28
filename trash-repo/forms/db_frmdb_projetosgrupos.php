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

//MODULO: atendimento
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cldb_projetosgrupos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at62_descr");
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
//     $at63_projeto = "";
		$at63_sequencial = "";
	    $at63_grupo      = "";
    	$at62_descr      = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<?
db_input('at63_sequencial',10,$Iat63_sequencial,true,'hidden',3,"")
?>
  <tr>
    <td nowrap title="<?=@$Tat63_projeto?>">
       <?=@$Lat63_projeto?>
    </td>
    <td> 
<?
db_input('at63_projeto',10,$Iat63_projeto,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat63_grupo?>">
       <?
       db_ancora("<b>Grupo:</b>","js_pesquisaat63_grupo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at63_grupo',10,$Iat63_grupo,true,'text',$db_opcao," onchange='js_pesquisaat63_grupo(false);'")
?>
       <?
db_input('at62_descr',40,$Iat62_descr,true,'text',3,'')
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
	 $chavepri= array("at63_sequencial"=>@$at63_sequencial,"at63_projeto"=>@$at63_projeto);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $cldb_projetosgrupos->sql_query(null,"*","at63_sequencial","at63_projeto=$at63_projeto");
	 $cliframe_alterar_excluir->campos  ="at62_descr";
	 $cliframe_alterar_excluir->legenda ="GRUPOS";
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
function js_pesquisaat63_grupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_projetosgrupos','db_iframe_db_projetoscadgrupos','func_db_projetoscadgrupos.php?funcao_js=parent.js_mostradb_projetoscadgrupos1|at62_codigo|at62_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.at63_grupo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_projetosgrupos','db_iframe_db_projetoscadgrupos','func_db_projetoscadgrupos.php?pesquisa_chave='+document.form1.at63_grupo.value+'&funcao_js=parent.js_mostradb_projetoscadgrupos','Pesquisa',false);
     }else{
       document.form1.at62_descr.value = ''; 
     }
  }
}
function js_mostradb_projetoscadgrupos(chave,erro){
  document.form1.at62_descr.value = chave; 
  if(erro==true){ 
    document.form1.at63_grupo.focus(); 
    document.form1.at63_grupo.value = ''; 
  }
}
function js_mostradb_projetoscadgrupos1(chave1,chave2){
  document.form1.at63_grupo.value = chave1;
  document.form1.at62_descr.value = chave2;
  db_iframe_db_projetoscadgrupos.hide();
}
</script>