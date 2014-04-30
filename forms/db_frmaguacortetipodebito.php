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
$claguacortetipodebito->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("x40_dtinc");
$clrotulo->label("k00_descr");
$clrotulo->label("x43_descr");
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
	 $x45_codcortetipodebito = "";
     $x45_tipo = "";
     $x45_parcelas = "";
     $x45_dtvenc = "";
     $x45_vlrminimo = "";
     $x45_dtvenc = "";
     $x45_dtvenc_dia = "";
     $x45_dtvenc_mes = "";
     $x45_dtvenc_ano = "";
     $x45_dtopini = "";
     $x45_dtopini_dia = "";
     $x45_dtopini_mes = "";
     $x45_dtopini_ano = "";
     $x45_dtopfim = "";
     $x45_dtopfim_dia = "";
     $x45_dtopfim_mes = "";
     $x45_dtopfim_ano = "";
	 $k00_descr = "";
	 $x43_descr = "";


   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">

  <tr>
    <td nowrap title="<?=@$Tx45_codcortetipodebito?>">
       <?=@$Lx45_codcortetipodebito
       //db_ancora(@$Lx45_codcorte,"js_pesquisax45_codcorte(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
//db_input('x45_codcorte',10,$Ix45_codcorte,true,'text',$db_opcao," onchange='js_pesquisax45_codcorte(false);'")
db_input('x45_codcortetipodebito',10,$Ix45_codcortetipodebito,true,'text',3,"")
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tx45_codcorte?>">
       <?=@$Lx45_codcorte
       //db_ancora(@$Lx45_codcorte,"js_pesquisax45_codcorte(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
//db_input('x45_codcorte',10,$Ix45_codcorte,true,'text',$db_opcao," onchange='js_pesquisax45_codcorte(false);'")
db_input('x45_codcorte',10,$Ix45_codcorte,true,'text',3," onchange='js_pesquisax45_codcorte(false);'")
?>
       <?
$data = strtotime($x40_dtinc);
$x40_dtinc_dia = date("d", $data);
$x40_dtinc_mes = date("m", $data);
$x40_dtinc_ano = date("Y", $data);

db_inputdata('x40_dtinc',@$x40_dtinc_dia,@$x40_dtinc_mes,@$x40_dtinc_ano,true,'text',3,"");
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx45_tipo?>">
       <?
       db_ancora(@$Lx45_tipo,"js_pesquisax45_tipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x45_tipo',5,$Ix45_tipo,true,'text',$db_opcao," onchange='js_pesquisax45_tipo(false);'");

db_input('k00_descr',40,$Ik00_descr,true,'text',3,'');
?>
    </td>
  </tr>


  
  <tr>
    <td nowrap title="<?=@$Tx45_parcelas?>">
       <?=@$Lx45_parcelas?>
    </td>
    <td> 
<?
db_input('x45_parcelas',4,$Ix45_parcelas,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx45_dtvenc?>">
       <?=@$Lx45_dtvenc?>
    </td>
    <td> 
<?
db_inputdata('x45_dtvenc',@$x45_dtvenc_dia,@$x45_dtvenc_mes,@$x45_dtvenc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tx45_dtopini?>">
       <?=@$Lx45_dtopini?>
    </td>
    <td> 
<?
db_inputdata('x45_dtopini',@$x45_dtopini_dia,@$x45_dtopini_mes,@$x45_dtopini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tx45_dtopfim?>">
       <?=@$Lx45_dtopfim?>
    </td>
    <td> 
<?
db_inputdata('x45_dtopfim',@$x45_dtopfim_dia,@$x45_dtopfim_mes,@$x45_dtopfim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

  
  <tr>
    <td nowrap title="<?=@$Tx45_vlrminimo?>">
       <?=@$Lx45_vlrminimo?>
    </td>
    <td> 
<?
db_input('x45_vlrminimo',15,$Ix45_vlrminimo,true,'text',$db_opcao,"")
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
	 $chavepri= array("x45_codcortetipodebito"=>@$x45_codcortetipodebito);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $claguacortetipodebito->sql_query(null,"*",null,"x45_codcorte=$x45_codcorte");
	 $cliframe_alterar_excluir->campos  ="x45_codcortetipodebito,x45_tipo,k00_descr,x45_parcelas,x45_dtvenc,x45_dtopini,x45_dtopfim,x45_vlrminimo";
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
function js_pesquisax45_codcorte(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacortetipodebito','db_iframe_aguacorte','func_aguacorte.php?funcao_js=parent.js_mostraaguacorte1|x40_codcorte|x40_dtinc','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x45_codcorte.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacortetipodebito','db_iframe_aguacorte','func_aguacorte.php?pesquisa_chave='+document.form1.x45_codcorte.value+'&funcao_js=parent.js_mostraaguacorte','Pesquisa',false);
     }else{
       document.form1.x40_dtinc.value = ''; 
     }
  }
}

function js_pesquisax45_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacortetipodebito','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true,'0','1','800','400');
  }else{
     if(document.form1.x45_tipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacortetipodebito','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.x45_tipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false);
     }else{
       document.form1.k00_descr.value = ''; 
     }
  }
}

function js_mostraaguacorte(chave,erro){
  document.form1.x40_dtinc.value = chave; 
  if(erro==true){ 
    document.form1.x45_codcorte.focus(); 
    document.form1.x45_codcorte.value = ''; 
  }
}

function js_mostraaguacorte1(chave1,chave2){
  document.form1.x45_codcorte.value = chave1;
  document.form1.x40_dtinc.value = chave2;
  db_iframe_aguacorte.hide();
}

function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave; 
  if(erro==true){ 
    document.form1.x45_tipo.focus(); 
    document.form1.x45_tipo.value = ''; 
  }
}

function js_mostraarretipo1(chave1,chave2){
  document.form1.x45_tipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
}

</script>