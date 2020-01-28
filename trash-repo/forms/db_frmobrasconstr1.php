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

//MODULO: projetos
include("dbforms/db_classesgenericas.php");
include("classes/db_caracter_classe.php");
include("classes/db_parobrasocup_classe.php");
include("classes/db_parobrastipocons_classe.php");
include("classes/db_parobrastipolanc_classe.php");
include("classes/db_obras_classe.php");
include("classes/db_obraslote_classe.php");
include("classes/db_obraslotei_classe.php");

$clparobrasocup     = new cl_parobrasocup;
$clparobrastipocons = new cl_parobrastipocons;
$clparobrastipolanc = new cl_parobrastipolanc;
$clcaracter         = new cl_caracter;
$clobras            = new cl_obras;
$clobraslote        = new cl_obraslote;
$clobraslotei       = new cl_obraslotei;
$clobrasconstr      = new cl_obrasconstr;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$clobrasconstr->rotulo->label();
$clobrasender->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ob01_nomeobra");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>location.href='pro1_obrasconstr002.php?func_alvara=1&chavepesquisa=$ob08_codconstr&chavepesquisa1=$ob08_codobra'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>location.href='pro1_obrasconstr003.php?func_alvara=1&chavepesquisa=$ob08_codconstr&chavepesquisa1=$ob08_codobra'</script>";
}
$result = $clobras->sql_record($clobras->sql_query(@$ob08_codobra));
db_fieldsmemory($result,0);
$result = $clobraslote->sql_record($clobraslote->sql_query(@$ob08_codobra,"".($db_opcao == 1?"ob05_idbql,j34_totcon as ob07_areaatual":"ob05_idbql").""));
if($clobraslote->numrows > 0){
  db_fieldsmemory($result,0);
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tob08_codconstr?>">
       <?=@$Lob08_codconstr?>
    </td>
    <td> 
<?
db_input('ob08_codconstr',10,$Iob08_codconstr,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tob08_codobra?>">
       <?
       db_ancora(@$Lob08_codobra,"js_pesquisaob08_codobra(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('ob08_codobra',10,$Iob08_codobra,true,'text',3," onchange='js_pesquisaob08_codobra(false);'")
?>
       <?
db_input('ob01_nomeobra',55,$Iob01_nomeobra,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tob08_area?>">
       <?=@$Lob08_area?>
    </td>
    <td> 
<?
db_input('ob08_area',10,$Iob08_area,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tob08_ocupacao?>">
	       <?=@$Lob08_ocupacao?>
	    </td>
	    <td> 
	<?
	$result = $clparobrasocup->sql_record($clparobrasocup->sql_query("","*"));
	if($clparobrasocup->numrows > 0){
	  db_fieldsmemory($result,0);
	}
	db_selectrecord("ob08_ocupacao",$clcaracter->sql_record($clcaracter->sql_query("","*",""," j32_grupo = $ob11_grupo")),true,$db_opcao,"","ob08_ocupacao");
	?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tob08_tipoconstr?>">
	       <?=@$Lob08_tipoconstr?>
	    </td>
	    <td> 
	<?
	$result = $clparobrastipocons->sql_record($clparobrastipocons->sql_query("","*"));
	if($clparobrastipocons->numrows > 0){
	  db_fieldsmemory($result,0);
	}
	db_selectrecord("ob08_tipoconstr",$clcaracter->sql_record($clcaracter->sql_query("","*",""," j32_grupo = $ob12_grupo")),true,$db_opcao,"","ob08_tipoconstr");
	?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tob08_tipolanc?>">
	       <?=@$Lob08_tipolanc?>
	    </td>
	    <td> 
	<?
	$result = $clparobrastipolanc->sql_record($clparobrastipolanc->sql_query("","*"));
	if($clparobrastipolanc->numrows > 0){
	  db_fieldsmemory($result,0);
	}
	db_selectrecord("ob08_tipolanc",$clcaracter->sql_record($clcaracter->sql_query("","*",""," j32_grupo = $ob13_grupo")),true,$db_opcao,"","ob08_tipolanc");
	?>
	    </td>
    </tr>
	  <tr>
	    <td nowrap title="<?=@$Tob07_lograd?>">
	       <?
	       db_ancora(@$Lob07_lograd,"js_pesquisaob07_lograd(true);",$db_opcao);
	       ?>
	    </td>
	    <td> 
	<?
	db_input('ob07_lograd',7,$Iob07_lograd,true,'text',(@$ob01_regular == "f"?$db_opcao:3)," onchange='js_pesquisaob07_lograd(false);'")
	?>
	       <?
	db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
	       ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tob07_numero?>">
	       <?=@$Lob07_numero?>
	    </td>
	    <td> 
	<?
	db_input('ob07_numero',10,$Iob07_numero,true,'text',$db_opcao,"")
	?>
	       <?=@$Lob07_compl?>
	<?
	db_input('ob07_compl',20,$Iob07_compl,true,'text',$db_opcao,"")
	?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tob07_bairro?>">
	       <?
	       db_ancora(@$Lob07_bairro,"js_pesquisaob07_bairro(true);",(@$ob01_regular == "f"?$db_opcao:3));
	       ?>
	    </td>
	    <td> 
	<?
	//db_input('ob07_bairro',4,$Iob07_bairro,true,'text',(@$ob01_regular == "f"?$db_opcao:3)," onchange='js_pesquisaob07_bairro(false);'")
	db_input('ob07_bairro',4,$Iob07_bairro,true,'text',2," onchange='js_pesquisaob07_bairro(false);'")
	?>
	       <?
	db_input('j13_descr',40,$Ij13_descr,true,'text',3,'')
	       ?>
	    </td>
    </tr>  
  <tr>
    <td nowrap title="<?=@$Tob07_areaatual?>">
       <?=@$Lob07_areaatual?>
    </td>
    <td>
<?
db_input('ob07_areaatual',10,$Iob07_areaatual,true,'text',$db_opcao,"")
?>
       <?=@$Lob07_unidades?>
<?
db_input('ob07_unidades',10,$Iob07_unidades,true,'text',$db_opcao,"")
?>
       <?=@$Lob07_pavimentos?>
<?
db_input('ob07_pavimentos',10,$Iob07_pavimentos,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tob07_inicio?>">
       <?=@$Lob07_inicio?>
    </td>
    <td> 
<?
db_inputdata('ob07_inicio',@$ob07_inicio_dia,@$ob07_inicio_mes,@$ob07_inicio_ano,true,'text',$db_opcao,"")
?>
       <?=@$Lob07_fim?>
<?
db_inputdata('ob07_fim',@$ob07_fim_dia,@$ob07_fim_mes,@$ob07_fim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <input name="db_opcao" type="submit" id="db_opcao" onClick="return js_verifica_data2();" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":($db_opcao == 3?"Excluir":"Incluir")))?>" <?=($db_botao==false?"disabled":"")?> >
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
   <?
    $chavepri= array("ob08_codconstr"=>@$ob08_codconstr,"ob08_codobra"=>@$ob08_codobra);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="ob08_codconstr,ob08_codobra,ob01_nomeobra";
    $cliframe_alterar_excluir->sql=$clobrasconstr->sql_query("","*",""," ob08_codobra = ".@$ob08_codobra."");
    $cliframe_alterar_excluir->legenda="Construções";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhuma Construção Cadastrada!</font>";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="170";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
   ?>
   </td>
 </tr>  
  </table>
  </center>
</form>
<script>
function js_pesquisaob07_lograd(mostra){
  if(mostra==true){
    <?
    if(@$ob01_regular == "t"){
    ?>
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruasobras.php?pesquisa_chave=<?=@$ob05_idbql?>&funcao_js=parent.js_mostraruas2|j36_codigo|j14_nome|j13_codi|j13_descr','Pesquisa',true);
    <?
    }else{
    ?>
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome','Pesquisa',true);
    <?
    }
    ?>
  }else{
     if(document.form1.ob07_lograd.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.ob07_lograd.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false);
     }else{
       document.form1.j14_nome.value = ''; 
     }
  }
}

/*
*  impede o usuário de incluir uma data final menor do que a inicial.
*  exemplo de caso que será impedido de ser incluído:
*  Data inicio:  01/01/2009                  Data final:  01/12/2001
*/
function js_verifica_data2() {
   
  var aDataInicio = document.form1.ob07_inicio.value.split('/');
  var aDataFim    = document.form1.ob07_fim.value.split('/');
  var sDataInicio = aDataInicio[2] + '-' + aDataInicio[1] + '-' + aDataInicio[0]; 
  var sDataFim    = aDataFim[2] + '-' + aDataFim[1] + '-' + aDataFim[0]; 
  var lComparaData = js_diferenca_datas(sDataInicio, sDataFim, 3)

  if(lComparaData == 'i'){
	  return true;
  }

  if ( lComparaData ) {
    alert("Data final não pode ser menor que a data inicial!");
    document.form1.ob07_fim.value = '';
	return false;
  }
  
  return true;
} 
 
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.ob07_lograd.focus(); 
    document.form1.ob07_lograd.value = ''; 
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.ob07_lograd.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe_ruas.hide();
}
function js_mostraruas2(chave1,chave2,cod,bai){
  document.form1.ob07_lograd.value = chave1;
  document.form1.j14_nome.value = chave2;
  document.form1.ob07_bairro.value = cod;
  document.form1.j13_descr.value = bai;
  db_iframe_ruas.hide();
}
function js_pesquisaob07_bairro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|j13_codi|j13_descr','Pesquisa',true);
  }else{
     if(document.form1.ob07_bairro.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?pesquisa_chave='+document.form1.ob07_bairro.value+'&funcao_js=parent.js_mostrabairro','Pesquisa',false);
     }else{
       document.form1.j13_descr.value = ''; 
     }
  }
}
function js_mostrabairro(chave,erro){
  document.form1.j13_descr.value = chave; 
  if(erro==true){ 
    document.form1.ob07_bairro.focus(); 
    document.form1.ob07_bairro.value = ''; 
  }
}
function js_mostrabairro1(chave1,chave2){
  document.form1.ob07_bairro.value = chave1;
  document.form1.j13_descr.value = chave2;
  db_iframe_bairro.hide();
}
function js_pesquisaob08_codobra(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obras.php?funcao_js=parent.js_mostraobras1|ob01_codobra|ob01_nomeobra','Pesquisa',true);
  }else{
     if(document.form1.ob08_codobra.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obras.php?pesquisa_chave='+document.form1.ob08_codobra.value+'&funcao_js=parent.js_mostraobras','Pesquisa',false);
     }else{
       document.form1.ob01_nomeobra.value = ''; 
     }
  }
}
function js_mostraobras(chave,erro){
  document.form1.ob01_nomeobra.value = chave; 
  if(erro==true){ 
    document.form1.ob08_codobra.focus(); 
    document.form1.ob08_codobra.value = ''; 
  }
}
function js_mostraobras1(chave1,chave2){
  document.form1.ob08_codobra.value = chave1;
  document.form1.ob01_nomeobra.value = chave2;
  db_iframe_obras.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_obrasconstr','func_obrasconstr.php?funcao_js=parent.js_preenchepesquisa|ob08_codconstr','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_obrasconstr.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
<?
if(isset($ob08_codobra) && $ob08_codobra != ""){
?>
js_OpenJanelaIframe('','db_iframe_obras','func_obras.php?pesquisa_chave=<?=@$ob08_codobra?>&funcao_js=parent.js_mostraobras','Pesquisa',false);
<?
}
?>
if(document.form1.ob08_codconstr.value == 0){
  document.form1.ob08_codconstr.value = '';
}
</script>