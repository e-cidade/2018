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
$clorcsuplementacaoparametrocriterio->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o134_anousu");
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
     //$o135_orcsuplementacaoparametro = "";
     $o135_descricao = "";
     $o135_nivel = "";
     $o135_valor = "";
     $o135_fundamentacaolegal = "";
   }
} 
global $o135_sequencial;
?>
<div style="margin-left: 500px;">
<form name="form1" method="post" action="">
<center>
<fieldset style="margin-top: 20px; width: 700px;">
  <legend><b>Critérios para Suplementação</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To135_sequencial?>">
       <?=@$Lo135_sequencial?>
    </td>
    <td> 
<?
db_input('o135_sequencial',10,$Io135_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To135_orcsuplementacaoparametro?>">
       <?
       db_ancora(@$Lo135_orcsuplementacaoparametro,"js_pesquisao135_orcsuplementacaoparametro(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o135_orcsuplementacaoparametro',10,$Io135_orcsuplementacaoparametro,true,'text',3," onchange='js_pesquisao135_orcsuplementacaoparametro(false);'")
?>
       <?
db_input('o134_anousu',10,$Io134_anousu,true,'text',3,'',"","","display:none;")
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To135_descricao?>">
       <?=@$Lo135_descricao?>
    </td>
    <td> 
<?
db_input('o135_descricao',50,$Io135_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To135_nivel?>">
       <?=@$Lo135_nivel?>
    </td>
    <td> 
<?
$x = array('6'=>'Ação','7'=>'Elemento','3'=>'Função','5'=>'Programa','8'=>'Recurso','1'=>'Órgão','4'=>'Subfunção','2'=>'Unidade');
db_select('o135_nivel',$x,true,$db_opcao,"onchange='valida_nivel_valor();'");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To135_valor?>">
       <?=@$Lo135_valor?>
    </td>
    <td> 
<?
db_input('o135_valor',50,$Io135_valor,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center" nowrap title="<?=@$To135_fundamentacaolegal?>"> 
      <fieldset style="width: 460px; margin-top: 20px;">
        <legend><b><?=@$Lo135_fundamentacaolegal?></b></legend> 
          <?
            db_textarea('o135_fundamentacaolegal',10,47,$Io135_fundamentacaolegal,true,'text',$db_opcao,"");
          ?>
          
    </fieldset> 
  </tr>
</fieldset>  
  
  </tr>
</table>  
</fieldset>  
  
  
<table style="margin-top: 15px;">  
  <tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table style="margin-top: 15px;">
  <tr>
    <td valign="top"  align="center">  
    <?
   $iAnoUsu     = db_getsession("DB_anousu");
	 $chavepri= array("o135_sequencial"=>@$o135_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 //$cliframe_alterar_excluir->sql     = $clorcsuplementacaoparametrocriterio->sql_query_file($o135_sequencial);
	 $cliframe_alterar_excluir->sql     = $clorcsuplementacaoparametrocriterio->sql_query_file("", "*", "", "o135_orcsuplementacaoparametro = {$iAnoUsu}");
	 $cliframe_alterar_excluir->campos  ="o135_sequencial,o135_orcsuplementacaoparametro,o135_descricao,o135_nivel,o135_valor,o135_fundamentacaolegal";
	 $cliframe_alterar_excluir->legenda="Critérios Cadastrados";
	 $cliframe_alterar_excluir->alignlegenda="left";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
</div>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisao135_orcsuplementacaoparametro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcsuplementacaoparametrocriterio','db_iframe_orcsuplementacaoparametro','func_orcsuplementacaoparametro.php?funcao_js=parent.js_mostraorcsuplementacaoparametro1|o134_anousu|o134_anousu','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.o135_orcsuplementacaoparametro.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_orcsuplementacaoparametrocriterio','db_iframe_orcsuplementacaoparametro','func_orcsuplementacaoparametro.php?pesquisa_chave='+document.form1.o135_orcsuplementacaoparametro.value+'&funcao_js=parent.js_mostraorcsuplementacaoparametro','Pesquisa',false);
     }else{
       document.form1.o134_anousu.value = ''; 
     }
  }
}
function js_mostraorcsuplementacaoparametro(chave,erro){
  document.form1.o134_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o135_orcsuplementacaoparametro.focus(); 
    document.form1.o135_orcsuplementacaoparametro.value = ''; 
  }
}
function js_mostraorcsuplementacaoparametro1(chave1,chave2){
  document.form1.o135_orcsuplementacaoparametro.value = chave1;
  document.form1.o134_anousu.value = chave2;
  db_iframe_orcsuplementacaoparametro.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcsuplementacaoparametrocriterio','func_orcsuplementacaoparametrocriterio.php?funcao_js=parent.js_preenchepesquisa|o135_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orcsuplementacaoparametrocriterio.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

/*
 * função que trata o campo o135_valor, será usado apenas se o campo o135_nivel
 * for o valor 7 (Elemento)
 *
*/

function valida_nivel_valor(){
  iNivel = document.getElementById('o135_nivel').value;
  document.getElementById('o135_valor').readOnly = true ;
  if(iNivel == 7){
    document.getElementById('o135_valor').readOnly = false;
    document.getElementById('o135_valor').style.backgroundColor = 'rgb(230, 228, 241)';
    //alert('escrita');
  }else{
    document.getElementById('o135_valor').readOnly = true ;
    document.getElementById('o135_valor').value = '' ;
    document.getElementById('o135_valor').style.backgroundColor = 'rgb(222, 184, 135)';
    //alert('leitura');
  }
 
}
document.getElementById('o135_fundamentacaolegal').style.width='100%';
</script>