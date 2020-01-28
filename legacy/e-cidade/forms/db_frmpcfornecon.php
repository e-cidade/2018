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

//MODULO: compras
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clpcfornecon->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc60_dtlanc");
$clrotulo->label("nome");
$clrotulo->label("z01_nome");
$clrotulo->label("pc64_contabanco");

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
  if(isset($novo) || (isset($alterar) ||   isset($excluir) || isset($incluir) && $sqlerro==false )){
    $pc63_banco = "";
    $pc63_agencia = "";
    $pc63_agencia_dig = "";
    $pc63_conta = "";
    $pc63_conta_dig = "";
    $pc63_id_usuario = "";
    $pc63_cnpjcpf = "";
    $pc63_tipoconta = "1";
    unset($pc64_contabanco);
  }
}

if($db_opcao == 1){
  $resultx =  $clpcforne->sql_record($clpcforne->sql_query($pc63_numcgm,"z01_cgccpf as pc63_cnpjcpf, z01_nome"));
  if($clpcforne->numrows>0){
    db_fieldsmemory($resultx,0);
  }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc63_numcgm?>">
      <?
      db_ancora(@$Lpc63_numcgm,"js_pesquisapc63_numcgm(true);",3);
      ?>
    </td>
    <td colspan="3">
      <?
      if(isset($submita)){
        db_input('submita',6,0,true,'hidden',3,"");
      }
      db_input('pc63_contabanco',6,$Ipc63_contabanco,true,'hidden',3,"");
      db_input('pc63_numcgm',8,$Ipc63_numcgm,true,'text',3," onchange='js_pesquisapc63_numcgm(false);'");
      db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc63_banco?>">
      <?=@$Lpc63_banco?>
    </td>
    <td colspan="3">
      <?
      db_input('pc63_banco',5,$Ipc63_banco,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc63_agencia?>">
      <?=@$Lpc63_agencia?>
    </td>
    <td>
      <?
      db_input('pc63_agencia',5,$Ipc63_agencia,true,'text',$db_opcao,"")
      ?>
    <td nowrap title="<?=@$Tpc63_agencia_dig?>">
       <b><?=@$RLpc63_agencia_dig?>:</b>
    </td>
    <td>
      <?
      db_input('pc63_agencia_dig',2,$Ipc63_agencia_dig,true,'text',$db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc63_conta?>">
      <?=@$Lpc63_conta?>
    </td>
    <td>
      <?
      db_input('pc63_conta',17,$Ipc63_conta,true,'text',$db_opcao,"")
      ?>
    </td>
    <td nowrap title="<?=@$Tpc63_conta_dig?>">
      <b><?=@$RLpc63_conta_dig?>:</b>
    </td>
    <td>
      <?
      db_input('pc63_conta_dig',2,$Ipc63_conta_dig,true,'text',$db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc63_codigooperacao?>">
      <b><?=@$RLpc63_codigooperacao?>:</b>
    </td>
    <td>
      <?
      db_input('pc63_codigooperacao',2,$Ipc63_codigooperacao,true,'text',$db_opcao,"");
      ?>
    </td>
    <td nowrap title="<?=@$Tpc63_tipoconta?>">
      <b><?=@$RLpc63_tipoconta?>:</b>
    </td>
    <td>
      <?
      db_select('pc63_tipoconta', array("1" =>"Conta Corrente", "2"=> "Conta poupança"), true,'text',$db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc63_cnpjcpf?>">
      <?=@$Lpc63_cnpjcpf?>
    </td>
    <td>
      <?
      db_input('pc63_cnpjcpf',17,@$Ipc63_cnpjcpf,true,'text',$db_opcao," onBlur='js_verificaCGCCPF(this)'")
      ?>
    </td>
    <td nowrap title="<?=@$Tpc63_identcli?>">
      <?=@$Lpc63_identcli?>
    </td>
    <td>
      <?
      db_input('pc63_identcli',17,@$Ipc63_identcli,true,'text',$db_opcao)
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc64_contabanco?>">
      <b>Conta padrão:</b>
    </td>
    <td>
      <?
      if(!isset($pc64_contabanco)){
      	// echo($clpcfornecon->sql_query_padrao(null,"pc63_contabanco","","pc63_numcgm=$pc63_numcgm"));
      	$result_contapadrao = $clpcfornecon->sql_record($clpcfornecon->sql_query_padrao(null,"pc63_contabanco","","pc63_numcgm=$pc63_numcgm"));
      	if($clpcfornecon->numrows > 0){
      	  $pc64_contabanco = "f";
      	}else{
      	  $pc64_contabanco = "t";
      	}
      }
      $x = array("t"=>"SIM","f"=>"NÃO");
      db_select('pc64_contabanco',$x,true,$db_opcao,"")
      ?>
      <input type="hidden" name="padrao" value="<?=$pc64_contabanco?>">
    </td>
    <td nowrap title="<?=@$Tpc63_dataconf?>">
      <b><?//=@$Lpc63_dataconf?>Conferido:</b>
    </td>
    <td>
      <?
      $checked = "";
      if(isset($pc63_dataconf) && trim($pc63_dataconf)!=""){
        $checked = "checked";
      }

      $disabled = "";
      if($db_opcao==3 || $db_opcao==33){
        $disabled = "disabled";
      }
      ?>
    <input type='checkbox' name="conferido" <?=@$checked?> <?=@$disabled?>>
    </td>
  </tr>
  <tr>
    <td nowrap>
    </td>
    <td>
      <?
      global $pc63_id_usuario ;
      $pc63_id_usuario = db_getsession("DB_id_usuario") ;

      db_input('pc63_id_usuario',5,$Ipc63_id_usuario,true,'hidden',3," onchange='js_pesquisapc63_id_usuario(false);'")
      ?>
    </td>
  <tr>
  </tr>
    <td colspan="4" align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_ver()"  >
      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
      <?
      if(isset($submita)){
      ?>
        <input name="fechar" type="button" value="Fechar" onclick="parent.db_iframe_pcfornecon.hide();">
      <?
      }
      ?>
    </td>
  </tr>
</table>
<table width="90%" height="50%" border="0">
  <tr>
    <td valign="top"  align="center">
      <?
      $sql = $clpcfornecon->sql_query_lefpadrao(null,"pc63_contabanco,pc63_cnpjcpf,pc63_numcgm,pc63_banco,pc63_agencia||' - '||pc63_agencia_dig as pc63_agencia,pc63_conta||' - '||pc63_conta_dig as pc63_conta,pc63_dataconf,case when pc64_contabanco is not null then 'Padrão' else '' end as pc64_contabanco","pc63_contabanco"," pc63_numcgm = $pc63_numcgm ");

	  $chavepri= array("pc63_numcgm"=>@$pc63_numcgm,"pc63_contabanco"=>@$pc63_contabanco);
      $cliframe_alterar_excluir->chavepri=$chavepri;
 	  $cliframe_alterar_excluir->sql     = $sql;
	  $cliframe_alterar_excluir->campos  ="pc63_contabanco,pc63_cnpjcpf,pc63_banco,pc63_agencia,pc63_conta,pc63_dataconf,pc64_contabanco";
	  $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	  $cliframe_alterar_excluir->iframe_height ="100%";
	  $cliframe_alterar_excluir->iframe_width ="100%";
	  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
      ?>
    </td>
  </tr>
</table>
</center>
<?
if(isset($submita)){
  db_input('submita',10,0,true,'hidden',3,"");
}
?>
</form>

<script>
function js_ver(){
  if(document.form1.pc63_cnpjcpf.value != 0 &&document.form1.pc63_cnpjcpf.value != '' && <?=$db_opcao?> !=3){
    return  js_verificaCGCCPF(document.form1.pc63_cnpjcpf);
  }else{
    return true;
  }
}

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisapc63_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_pcfornecon','db_iframe_pcforne','func_pcforne.php?funcao_js=parent.js_mostrapcforne1|pc60_numcgm|pc60_dtlanc','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.pc63_numcgm.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_pcfornecon','db_iframe_pcforne','func_pcforne.php?pesquisa_chave='+document.form1.pc63_numcgm.value+'&funcao_js=parent.js_mostrapcforne','Pesquisa',false);
     }else{
       document.form1.pc60_dtlanc.value = '';
     }
  }
}
function js_mostrapcforne(chave,erro){
  document.form1.pc60_dtlanc.value = chave;
  if(erro==true){
    document.form1.pc63_numcgm.focus();
    document.form1.pc63_numcgm.value = '';
  }
}
function js_mostrapcforne1(chave1,chave2){
  document.form1.pc63_numcgm.value = chave1;
  document.form1.pc60_dtlanc.value = chave2;
  db_iframe_pcforne.hide();
}
function js_pesquisapc63_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_pcfornecon','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.pc63_id_usuario.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_pcfornecon','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.pc63_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = '';
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.pc63_id_usuario.focus();
    document.form1.pc63_id_usuario.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.pc63_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}

function js_validaBanco() {

  if (document.getElementById('pc63_banco').value != '104') {

    document.getElementById('pc63_codigooperacao').value    = '';
    document.getElementById('pc63_codigooperacao').disabled = true;
    document.getElementById('pc63_codigooperacao').style.backgroundColor='';
  } else {

    document.getElementById('pc63_codigooperacao').disabled = false;
    document.getElementById('pc63_codigooperacao').style.backgroundColor='#E6E4F1';
  }


}
js_validaBanco();
$('pc63_banco').observe('change', js_validaBanco);
</script>
