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

//MODULO: agua
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$claguacortemat->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("x01_numcgm");
$clrotulo->label("x40_dtinc");
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
     $x41_matric = "";
     //$x41_codcorte = $x40_codcorte;
	 $x41_codcortemat = "";
     $x41_dtprazo = "";
	 $x41_dtprazo_dia = "";
	 $x41_dtprazo_mes = "";
	 $x41_dtprazo_ano = "";
	 $x01_numcgm = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tx41_codcortemat?>">
       <?=@$Lx41_codcortemat?>
    </td>
    <td> 
<?
db_input('x41_codcortemat',5,$Ix41_codcortemat,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx41_matric?>">
       <?
       db_ancora(@$Lx41_matric,"js_pesquisax41_matric(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x41_matric',10,$Ix41_matric,true,'text',$db_opcao," onchange='js_pesquisax41_matric(false);'")
?>
       <?
db_input('x01_numcgm',40,$Ix01_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx41_codcorte?>">
       <?=@$Lx41_codcorte
       //db_ancora(@$Lx41_codcorte,"js_pesquisax41_codcorte(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
//db_input('x41_codcorte',10,$Ix41_codcorte,true,'text',$db_opcao," onchange='js_pesquisax41_codcorte(false);'")
db_input('x41_codcorte',10,$Ix41_codcorte,true,'text',3," onchange='js_pesquisax41_codcorte(false);'")
?>
       <?
$data = strtotime($x40_dtinc);
$x40_dtinc_dia = date("d", $data);
$x40_dtinc_mes = date("m", $data);
$x40_dtinc_ano = date("Y", $data);

db_inputdata('x40_dtinc',@$x40_dtinc_dia,@$x40_dtinc_mes,@$x40_dtinc_ano,true,'text',3,"")
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx41_dtprazo?>">
       <?=@$Lx41_dtprazo?>
    </td>
    <td> 
<?
db_inputdata('x41_dtprazo',@$x41_dtprazo_dia,@$x41_dtprazo_mes,@$x41_dtprazo_ano,true,'text',$db_opcao,"")
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
	 $chavepri= array("x41_codcortemat"=>@$x41_codcortemat);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 //$cliframe_alterar_excluir->sql     = $claguacortemat->sql_query_file($x41_codcortemat);
	 $cliframe_alterar_excluir->sql     = $claguacortemat->sql_query(null, "*", "a.j14_nome, x01_numero", "x41_codcorte=$x41_codcorte");
	 //echo $cliframe_alterar_excluir->sql;
	 $cliframe_alterar_excluir->campos  ="x41_matric,j14_nome,x01_numero,x41_codcorte,x41_dtprazo";
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
function js_pesquisax41_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacortemat','db_iframe_aguabase','func_aguabase.php?funcao_js=parent.js_mostraaguabase1|x01_matric|x01_numcgm','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x41_matric.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacortemat','db_iframe_aguabase','func_aguabase.php?pesquisa_chave='+document.form1.x41_matric.value+'&funcao_js=parent.js_mostraaguabase','Pesquisa',false);
     }else{
       document.form1.x01_numcgm.value = ''; 
     }
  }
}
function js_mostraaguabase(chave,erro){
  document.form1.x01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.x41_matric.focus(); 
    document.form1.x41_matric.value = ''; 
  }
}
function js_mostraaguabase1(chave1,chave2){
  document.form1.x41_matric.value = chave1;
  document.form1.x01_numcgm.value = chave2;
  db_iframe_aguabase.hide();
}
function js_pesquisax41_codcorte(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacortemat','db_iframe_aguacorte','func_aguacorte.php?funcao_js=parent.js_mostraaguacorte1|x40_codcorte|x40_dtinc','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x41_codcorte.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacortemat','db_iframe_aguacorte','func_aguacorte.php?pesquisa_chave='+document.form1.x41_codcorte.value+'&funcao_js=parent.js_mostraaguacorte','Pesquisa',false);
     }else{
       document.form1.x40_dtinc.value = ''; 
     }
  }
}
function js_mostraaguacorte(chave,erro){
  document.form1.x40_dtinc.value = chave; 
  if(erro==true){ 
    document.form1.x41_codcorte.focus(); 
    document.form1.x41_codcorte.value = ''; 
  }
}
function js_mostraaguacorte1(chave1,chave2){
  document.form1.x41_codcorte.value = chave1;
  document.form1.x40_dtinc.value = chave2;
  db_iframe_aguacorte.hide();
}
</script>