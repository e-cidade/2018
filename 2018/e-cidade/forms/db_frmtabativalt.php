<?php
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

require_once("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clIssAlvara              = new cl_issalvara;

$cltabativ->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("q03_descr");
$clrotulo->label("q07_seq");
$clrotulo->label("q11_tipcalc");
$clrotulo->label("q81_descr");
$clrotulo->label("q88_inscr");
$clrotulo->label("q07_horaini");
$clrotulo->label("q07_horafim");

if(isset($opcao) && $opcao=="alterar"){
    $db_opcao = 2;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
    $db_opcao = 3;
    if(isset($db_opcaoal)){
	$db_opcao=33;
    }
}else{
    $db_opcao = 1;
}
if(empty($excluir) && empty($alterar) && isset($opcao) && $opcao!=""){
  $result19=$cltabativ->sql_record($cltabativ->sql_query($q07_inscr,$q07_seq,'q07_ativ,q07_perman,q03_descr,q07_inscr,z01_nome,q07_quant,q07_datain,q07_datafi,q07_horaini,q07_horafim'));
  db_fieldsmemory($result19,0);
  $result20=$cltabativtipcalc->sql_record($cltabativtipcalc->sql_query($q07_inscr,$q07_seq,'q81_descr,q11_tipcalc'));
  if($cltabativtipcalc->numrows>0){
    db_fieldsmemory($result20,0);
  }else{
    $q81_descr="";
    $q11_tipcalc="";
  }
  $result21=$clativprinc->sql_record($clativprinc->sql_query($q07_inscr,"q88_seq"));
  if($clativprinc->numrows>0){
    db_fieldsmemory($result21,0);
    if($q07_seq==$q88_seq){
        $princ='t';
        if($db_opcao==3){
	  $db_botao=false;
	  $excluprinc=false;
	}
    }else{
        $princ='f';
    }
  }else{
    $princ='f';
  }
}



?>
<form name="form1" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>" onload='js_testadata();' >
<fieldset>
<legend>Vincular Atividades</legend>
<table>
  <tr>
    <td nowrap title="<?=@$Tq07_seq?>">
       <?=$Lq07_seq?>
    </td>
    <td>
		<?
			db_input('q07_seq',10,$Iq07_seq,true,'text',3);
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq07_inscr?>">
       <?=$Lq07_inscr?>
    </td>
    <td>
			<?
				db_input('q07_inscr',10,$Iq07_inscr,true,'text',3);
			?>
       <?
					$z01_nome = stripslashes($z01_nome);
					db_input('z01_nome',50,$Iz01_nome,true,'text',3);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq07_ativ?>">
       <?
       	db_ancora(@$Lq07_ativ,"js_pesquisaq07_ativ(true);",$db_opcao);
       ?>
    </td>
    <td>
			<?
				db_input('q07_ativ',10,$Iq07_ativ,true,'text',$db_opcao," onchange='js_pesquisaq07_ativ(false);'")
			?>
       <?
       	db_input('q03_descr',50,$Iq03_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Atividade principal">
      <b>Atividade principal:</b>
    </td>
    <td>
<?
if(isset($princ) && $princ=="t" && $db_opcao==2){
  $db_opcao_02=3;
  $npods=false;
}else{
  $db_opcao_02=1;
}
$xq = array("f"=>"NÃO","t"=>"SIM");
 db_select('princ',$xq,true,$db_opcao_02);
if(isset($npods)){
 echo "<small><b>Não será possível alterar este campo.</b></small>";
}
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq07_quant?>">
       <?=@$Lq07_quant?>
    </td>
    <td>
			<?
				if(empty($q07_quant)){
				  $q07_quant=1;
				}
				db_input('q07_quant',10,$Iq07_quant,true,'text',$db_opcao,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq07_perman?>">
       <?=@$Lq07_perman?>
    </td>
    <td>
			<?
				$xe = array("t"=>"PERMANENTE","f"=>"PROVISÓRIO");
				db_select('q07_perman',$xe,true,$db_opcao,"onchange='js_testadata(this.value);'");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq07_datain?>">
       <?=@$Lq07_datain?>
    </td>
    <td>
			<?
				if(empty($q07_datain_dia)){

				  $q07_datain_dia = date("d",db_getsession("DB_datausu"));
				  $q07_datain_mes = date("m",db_getsession("DB_datausu"));
				  $q07_datain_ano = date("Y",db_getsession("DB_datausu"));
				}
				db_inputdata('q07_datain',@$q07_datain_dia,@$q07_datain_mes,@$q07_datain_ano,true,'text',$db_opcao);
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq07_datafi?>">
       <?=@$Lq07_datafi?>
    </td>
    <td>
			<?
				db_inputdata('q07_datafi',@$q07_datafi_dia,@$q07_datafi_mes,@$q07_datafi_ano,true,'text',$db_opcao,"");
			?>
    </td>
  </tr>
    <td>
    </td>
  <!--
    <td nowrap title="<?=@$Tq11_tipcalc?>">

       <?
   //  db_ancora(@$Lq11_tipcalc,"js_tipcalc(true);",$db_opcao);
       ?>
    </td>
   -->

    <td>
			<?
			  db_input('q11_tipcalc',10,$Iq07_inscr,true,'hidden',$db_opcao,'onchange="js_tipcalc(false);"');
			?>
       <?
       	db_input('q81_descr',50,$Iz01_nome,true,'hidden',3,"","","#E6E4F1");
       ?>
    </td>
  </tr>

<tr>
    <td nowrap title="<?=@$Tq07_horaini?>">
           <?=@$Lq07_horaini?>
    </td>
    <td colspan="2">
           <?
             db_input('q07_horaini',5,$Iq07_horaini,true,'text',$db_opcao,"onchange='js_verifica_hora(this.value,this.name)';");
          ?>
    </td>
</tr>

<tr>
    <td nowrap title="<?=@$Tq07_horafim?>">
           <?=@$Lq07_horafim?>
    </td>
    <td colspan="2">
           <?
             db_input('q07_horafim',5,$Iq07_horafim,true,'text',$db_opcao,"onchange='js_verifica_hora(this.value,this.name)';");
           ?>
    </td>
</tr>


  </table>
</fieldset>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onClick="return js_dtfim()" >
<input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
<script>

function js_verifica_hora(valor,campo){
  erro= 0;
  ms  = "";
  hs  = "";

  tam = "";
  pos = "";
  tam = valor.length;
  pos = valor.indexOf(":");
  if(pos!=-1){
    if(pos==0 || pos>2){
      erro++;
    }else{
      if(pos==1){
  hs = "0"+valor.substr(0,1);
  ms = valor.substr(pos+1,2);
      }else if(pos==2){
        hs = valor.substr(0,2);
        ms = valor.substr(pos+1,2);
      }
      if(ms==""){
  ms = "00";
      }
    }
  }else{
    if(tam>=4){
      hs = valor.substr(0,2);
      ms = valor.substr(2,2);
    }else if(tam==3){
      hs = "0"+valor.substr(0,1);
      ms = valor.substr(1,2);
    }else if(tam==2){
      hs = valor;
      ms = "00";
    }else if(tam==1){
      hs = "0"+valor;
      ms = "00";
    }
  }
if(ms!="" && hs!=""){
    if(hs>24 || hs<0 || ms>60 || ms<0){
      erro++
    }else{
      if(ms==60){
  ms = "59";
      }
      if(hs==24){
  hs = "00";
      }
      hora = hs;
      minu = ms;
    }
  }
 if (document.form1.q07_horafim.value != "" && erro == 0) {
       var botao   = document.getElementById("db_opcao");
       var val_ini = document.form1.q07_horaini.value;
       var pos_ini = val_ini.indexOf(":");
       var hs_ini  = "";

       if (pos_ini == 1){
          hs_ini = "0" + val_ini.substr(0,1);
       } else if (pos_ini == 2){
            hs_ini = val_ini.substr(0,2);
       }

       if (valor!=""){
            eval("document.form1."+campo+".value='"+hora+":"+minu+"';");
       }

       var val_fin = document.form1.q07_horafim.value;
       var pos_fin = val_fin.indexOf(":");
       var ms_fin  = "";

       if (pos_fin == 1){
          hs_fin = "0" + val_fin.substr(0,1);
       } else if (pos_fin == 2){
            hs_fin = val_fin.substr(0,2);
       }

  }
  if(erro>0){
    if (erro < 99){
         alert("Informe uma hora válida.");
    }
  }
  if(valor!=""){
    eval("document.form1."+campo+".focus();");
    eval("document.form1."+campo+".value='"+hora+":"+minu+"';");
  }

}


function js_dtfim(){
  if(document.form1.q07_ativ.value == ''){
      alert('Codigo da atividade não preenchido!');
      return false;
  }
  if(document.form1.q07_datafi_dia.value == "" && document.form1.q07_perman.value == "f" ){
    alert('Informe a data final para atividade provisória');
    document.form1.q07_datafi.focus();
    return false;
  }else{
    return true;
  }


  return true;
}
</script>

 <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;">
 <tr>
   <td >
   <?
    $chavepri= array("q07_inscr"=>$q07_inscr,"q07_seq"=>@$q07_seq);
    $campos="q07_inscr,q07_seq,q88_inscr,q03_ativ,q03_descr,q07_datain,q07_horaini,q07_horafim,q07_datafi,q07_databx,q07_perman,q07_quant, q81_descr,q11_tipcalc";
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->sql     = $cltabativ->sql_query_atividade_inscr($q07_inscr,"$campos", "");
    $cliframe_alterar_excluir->sql_disabled  = $cltabativ->sql_query_atividade_inscr($q07_inscr,"$campos","q07_seq","q07_databx is not null and q07_inscr=$q07_inscr");
    $cliframe_alterar_excluir->campos  ="$campos";
    $cliframe_alterar_excluir->legenda="ATIVIDADES CADASTRADAS";
    $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_width="1000";
    $cliframe_alterar_excluir->iframe_height ="170";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);


    //echo "<br>". $cliframe_alterar_excluir->sql  . "<br>";
   ?>
   </td>
 </tr>
 <table>
 <input name="numativ" type="hidden" value="">
</form>
<script>
<?
$result = $cltabativ->sql_record($cltabativ->sql_query_atividade_inscr($q07_inscr,"*","q07_seq","q07_databx is null and q07_inscr = $q07_inscr"));
if($cltabativ->numrows > 0){
  echo "document.form1.numativ.value = '".$cltabativ->numrows."';\n";
}
if(isset($q07_inscr)){
  $clativprinc->sql_record($clativprinc->sql_query_file($q07_inscr,"q88_inscr"));
  if($clativprinc->numrows==0){
  echo "
        function js_re(){
          document.getElementById('princ').options[1].selected=true;
	}
	js_re();
      ";
  }
}
if(isset($pods) && $pods=="nops"){
    echo "document.form1.princ.disabled=true;\n\n";
}

if(isset($q07_inscr) && $q07_inscr!=""){
?>
function js_cancelar(){
  location.href="iss1_tabativ004.php?q07_inscr=<?=$q07_inscr?>&z01_nome=<?=$z01_nome?>";
}
<?
}
?>
function js_tipcalc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_atividades','db_iframe_ativid','func_tipcalcalt.php?funcao_js=parent.js_mostratip1|0|1','Pesquisa',true,0);
  }else{
   js_OpenJanelaIframe('top.corpo.iframe_atividades','db_iframe_ativid','func_tipcalcalt.php?pesquisa_chave='+document.form1.q11_tipcalc.value+'&funcao_js=parent.js_mostratip','Pesquisa',false,0);
  }
}
function js_mostratip(chave,erro){
  document.form1.q81_descr.value = chave;
  if(erro==true){
    document.form1.q11_tipcalc.focus();
    document.form1.q11_tipcalc.value = '';
  }
}
function js_mostratip1(chave1,chave2){
  document.form1.q11_tipcalc.value = chave1;
  document.form1.q81_descr.value = chave2;
  db_iframe_ativid.hide();
}
function js_pesquisaq07_ativ(mostra){

  if ( top.corpo.iframe_issbase.document.getElementById('z01_cgccpf').value.length == 14 ){
    tipo='cnpj';
  }else{
    tipo='cpf';
  }
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_atividades','db_iframe_ativid','func_atividade.php?tipo_pesquisa='+tipo+'&funcao_js=parent.js_mostraativid1|q03_ativ|q03_descr|q03_horaini|q03_horafim','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_atividades','db_iframe_ativid','func_atividade.php?tipo_pesquisa='+tipo+'&pesquisa_chave='+document.form1.q07_ativ.value+'&funcao_js=parent.js_mostraativid','Pesquisa',false,0);
  }

}
function js_mostraativid(chave,chave1,chave2,erro){
  document.form1.q03_descr.value = chave;
  document.form1.q07_horaini.value = chave1;
  document.form1.q07_horafim.value = chave2;
  if(erro==true){
    document.form1.q07_ativ.focus();
    document.form1.q07_ativ.value = '';
    document.form1.q07_horaini.value = '';
    document.form1.q07_horafim.value='';
  }
}
function js_mostraativid1(chave1,chave2,chave3,chave4){
  document.form1.q07_ativ.value = chave1;
  document.form1.q03_descr.value = chave2;

  document.form1.q07_horaini.value = chave3;
  document.form1.q07_horafim.value = chave4;

  db_iframe_ativid.hide();
}
<?
if(isset($excluprinc)){
  echo "\n\nalert('Esta é a atividade principal. Exclusão não permitida.');";
}
?>
//-----------------------------
function js_testadata(valor){

  if (valor=='t'){
    document.form1.q07_datafi_dia.value="";
    document.form1.q07_datafi_ano.value="";
    document.form1.q07_datafi_mes.value="";
//    document.form1.q07_datafi_dia.disabled=true;
//    document.form1.q07_datafi_ano.disabled=true;
//    document.form1.q07_datafi_mes.disabled=true;
    document.form1.q07_datafi_ano.style.backgroundColor = '#DEB887';
    document.form1.q07_datafi_dia.style.backgroundColor = '#DEB887';
    document.form1.q07_datafi_mes.style.backgroundColor = '#DEB887';

    // comentar este paratarefa 8832 e descomentar para 1366
    document.form1.q07_datafi.value="";
    document.form1.q07_datafi.disabled=true;
    document.form1.q07_datafi.style.backgroundColor = '#DEB887';

  }else {

    document.form1.q07_datafi_dia.disabled=false;
    document.form1.q07_datafi_ano.disabled=false;
    document.form1.q07_datafi_mes.disabled=false;
    document.form1.q07_datafi_ano.style.backgroundColor = '';
    document.form1.q07_datafi_dia.style.backgroundColor = '';
    document.form1.q07_datafi_mes.style.backgroundColor = '';

    // comentar este paratarefa 8832 e descomentar para 1366
    document.form1.q07_datafi.disabled=false;
    document.form1.q07_datafi.style.backgroundColor = '';

  }

}
</script>
<script>
js_testadata(document.form1.q07_perman.value);
</script>