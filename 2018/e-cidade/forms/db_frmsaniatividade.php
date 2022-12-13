<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clsaniatividade->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("y80_numcgm");
$clrotulo->label("q03_descr");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_saniatividade.location.href='fis1_saniatividade002.php?chavepesquisa=$y83_codsani&chavepesquisa1=$y83_seq'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_saniatividade.location.href='fis1_saniatividade003.php?chavepesquisa=$y83_codsani&chavepesquisa1=$y83_seq'</script>";
}


?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty83_codsani?>">
       <?=
       $Ly83_codsani
       ?>
    </td>
    <td>
<?
if (isset($y83_codsani)&&$y83_codsani!=""){

	$result_nome = $clsanitario->sql_record($clsanitario->sql_query($y83_codsani,"z01_nome"));
	if ($clsanitario->numrows>0){
   		db_fieldsmemory($result_nome,0);
	}

}

db_input('y83_codsani',10,$Iy83_codsani,true,'text',3," onchange='js_pesquisay83_codsani(false);'");
?>
       <?

db_input('z01_nome',35,$Iz01_nome,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty83_seq?>">
       <?=@$Ly83_seq?>
    </td>
    <td>
<?
if($db_opcao == 1){
  $result = $clsaniatividade->sql_record($clsaniatividade->sql_query_max("y83_seq",$y83_codsani));
  if($clsaniatividade->numrows > 0){
    global $y83_seq;
    db_fieldsmemory($result,0);
    $y83_seq = $max + 1;
  }else{
    $y83_seq = 1;
  }
}
db_input('y83_seq',10,$Iy83_seq,true,'text',3,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty83_ativ?>">
       <?
       db_ancora(@$Ly83_ativ,"js_pesquisay83_ativ(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('y83_ativ',8,$Iy83_ativ,true,'text',$db_opcao," onchange='js_pesquisay83_ativ(false);'");
?>
       <?
db_input('q03_descr',40,$Iq03_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty83_ativprinc?>">
       <?=@$Ly83_ativprinc?>
    </td>
    <td>
<?
$arr = array("t"=>"SIM","f"=>"NÃO");
if($db_opcao == 1){
  $clsaniatividade2 = new cl_saniatividade;
  $clsaniatividade2->sql_record($clsaniatividade2->sql_query("","","  saniatividade.*,ativid.q03_descr ",""," y83_codsani = $y83_codsani"));
  if($clsaniatividade2->numrows == 0){
    $change= "onChange='document.form1.y83_ativprinc.options[0].selected = true'";
  }else{
    $change = "";
  }
}else{
  $change = "";
}
db_select('y83_ativprinc',$arr,true,$db_opcao,$change);
if($db_opcao == 1){
  echo "<script>document.form1.y83_ativprinc.options[1].selected = true</script>";
}
?>
    </td>
 </tr>
 <tr>
    <td><b>Permanente ou provisório: </b>
    </td>
    <td>
<?
$xe = array("t"=>"PERMANENTE","f"=>"PROVISÓRIO");
db_select('y83_perman',$xe,true,$db_opcao,"onchange='js_testadata(this.value);'");

?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty83_dtini?>">
       <?=@$Ly83_dtini?>
    </td>
    <td>
<?
if(empty($y83_dtini_dia)){
  $y83_dtini_dia = date("d",db_getsession("DB_datausu"));
  $y83_dtini_mes = date("m",db_getsession("DB_datausu"));
  $y83_dtini_ano = date("Y",db_getsession("DB_datausu"));
}
db_inputdata('y83_dtini',@$y83_dtini_dia,@$y83_dtini_mes,@$y83_dtini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
   <tr>
    <td nowrap title="<?=@$Ty83_dtfim?>">
       <?=@$Ly83_dtfim?>
    </td>
    <td>
<?
db_inputdata('y83_dtfim',@$y83_dtfim_dia,@$y83_dtfim_mes,@$y83_dtfim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty83_area?>">
       <?=@$Ly83_area?>
    </td>
    <td>
<?
db_input('y83_area',10,$Iy83_area,true,'text',$db_opcao);
?>
<input type="hidden" name="area" value="<?=@$y83_arean?>">
<input type="hidden" name="z01_cgccpf" value="<?=@$z01_cgccpf?>">
<input type="hidden" name="opcaoExec" value="">

<script>
function js_calcarea(obj,opcao,seq){
if(opcao==1 ||opcao==2||opcao==22){
	var area = obj;
	var codsani = document.form1.y83_codsani.value;
	//alert('area='+area+'codsani ='+codsani+'opcao'+opcao+'seq'+seq);
	js_OpenJanelaIframe('','db_iframe_verarea','func_verarea.php?area='+area+'&codsani='+codsani+'&opcao='+opcao+'&seq='+seq,'Pesquisa',false);
  }else{
  	js_submet('Excluir')
  }

}
function  js_submet(opcao){
	//alert('gjhgjh');
	document.form1.opcaoExec.value = opcao;
	document.form1.submit();
}
</script>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <input name="db_opcao" type="button" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  onclick='js_calcarea(document.form1.y83_area.value,<?=$db_opcao?>,document.form1.y83_seq.value)'>
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
   <?
//die($clsaniatividade->sql_query("","","  y83_codsani,y83_seq,case when y83_ativprinc is true then 'SIM' else 'NÃO' end as y83_ativprinc,y83_dtini,y83_dtfim,y83_area,y83_ativ,ativid.q03_descr",""," y83_codsani = $y83_codsani"));


    $chavepri= array("y83_codsani"=>$y83_codsani,"y83_seq"=>@$y83_seq);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="y83_seq,y83_ativprinc,y83_dtini,y83_dtfim,y83_area,y83_ativ,q03_descr,y83_perman";
    $cliframe_alterar_excluir->sql=$clsaniatividade->sql_query("","","  y83_codsani,y83_seq,case when y83_perman is true then 'SIM' else 'NÃO' end as y83_perman,case when y83_ativprinc is true then 'SIM' else 'NÃO' end as y83_ativprinc,y83_dtini,y83_dtfim,y83_area,y83_ativ,ativid.q03_descr",""," y83_codsani = $y83_codsani");
  //  $cliframe_alterar_excluir->sql_disabled=$clsaniatividade->sql_query("",""," saniatividade.*,ativid.q03_descr ",""," y83_codsani = $y83_codsani and y83_dtfim is not null");
    $cliframe_alterar_excluir->legenda="ATIVIDADES CADASTRADAS";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum registro encontrado!</font>";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="170";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    $clsaniatividade1 = new cl_saniatividade;
    $clsaniatividade1->sql_record($clsaniatividade1->sql_query("","","  saniatividade.*,ativid.q03_descr ",""," y83_codsani = $y83_codsani"));
    if($clsaniatividade1->numrows == 0){
      echo "<script>document.form1.y83_ativprinc.options[0].selected = true</script>";
    }
    //die($clsaniatividade1->sql_query("","","  saniatividade.*,ativid.q03_descr ",""," y83_codsani = $y83_codsani"));
   ?>
   </td>
 </tr>
  </table>
  </center>
</form>
<script>
function js_verifica(){
 if(document.form1.y83_perman.value=='f'){
    if(document.form1.y83_dtfim.value==''){
    	alert('Data final não informada.');
    }
 }
}

function js_pesquisay83_codsani(mostra){
    js_OpenJanelaIframe('','db_iframe_sanitario','func_sanitario.php?pesquisa_chave='+document.form1.y83_codsani.value+'&funcao_js=parent.js_mostrasanitario','Pesquisa',false);
}
function js_mostrasanitario(chave,erro){
  if(erro==true){
    document.form1.y83_codsani.focus();
    document.form1.y83_codsani.value = '';
  }
}
function js_mostrasanitario1(chave1,chave2){
  document.form1.y83_codsani.value = chave1;
  document.form1.y80_numcgm.value = chave2;
  db_iframe_sanitario.hide();
}
function js_pesquisay83_ativ(mostra) {

  if ( document.form1.z01_cgccpf.value.length == 14 ) {
    tipo='cnpj';
  }else{
    tipo='cpf';
  }
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_ativid','func_ativid.php?tipo_pesquisa='+tipo+'&funcao_js=parent.js_mostraativid1|q03_ativ|q03_descr','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('','db_iframe_ativid','func_ativid.php?tipo_pesquisa='+tipo+'&pesquisa_chave='+document.form1.y83_ativ.value+'&funcao_js=parent.js_mostraativid','Pesquisa',false);
  }
}
function js_mostraativid(chave,erro){
  document.form1.q03_descr.value = chave;
  if(erro==true){
    document.form1.y83_ativ.focus();
    document.form1.y83_ativ.value = '';
  }
}
function js_mostraativid1(chave1,chave2){
  document.form1.y83_ativ.value = chave1;
  document.form1.q03_descr.value = chave2;
  db_iframe_ativid.hide();
}

function js_testadata(valor){

  if (valor=='t'){
    document.form1.y83_dtfim_dia.value="";
    document.form1.y83_dtfim_ano.value="";
    document.form1.y83_dtfim_mes.value="";
    document.form1.y83_dtfim_dia.disabled=true;
    document.form1.y83_dtfim_ano.disabled=true;
    document.form1.y83_dtfim_mes.disabled=true;
    document.form1.y83_dtfim_ano.style.backgroundColor = '#DEB887';
    document.form1.y83_dtfim_dia.style.backgroundColor = '#DEB887';
    document.form1.y83_dtfim_mes.style.backgroundColor = '#DEB887';

    // comentar este paratarefa 8832 e descomentar para 1366
    //document.form1.y83_dtfim.value="";
   // document.form1.y83_dtfim.disabled=true;
   // document.form1.y83_dtfim.style.backgroundColor = '#DEB887';

  }else {
    document.form1.y83_dtfim_dia.disabled=false;
    document.form1.y83_dtfim_ano.disabled=false;
    document.form1.y83_dtfim_mes.disabled=false;
    document.form1.y83_dtfim_ano.style.backgroundColor = '';
    document.form1.y83_dtfim_dia.style.backgroundColor = '';
    document.form1.y83_dtfim_mes.style.backgroundColor = '';

    // comentar este paratarefa 8832 e descomentar para 1366
    //document.form1.y83_dtfim.disabled=false;
    //document.form1.y83_dtfim.style.backgroundColor = '';
  }

}
js_testadata(document.form1.y83_perman.value);

</script>
<?
if(isset($y83_codsani)){
  echo "<script>
          document.form1.y83_codsani.value = '$y83_codsani';
          js_pesquisay83_codsani();
	    </script>";
}
?>