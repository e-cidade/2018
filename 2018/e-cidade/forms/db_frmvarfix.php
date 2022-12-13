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

//MODULO: issqn
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("p58_requer");
$clrotulo->label("p58_codproc");
$clrotulo->label("i01_descr");
$clrotulo->label("q36_processo");
$clrotulo->label("q37_notifica");
$clrotulo->label("y30_nome");
$clrotulo->label("y100_sequencial");
$clrotulo->label("z01_nome");
$clvarfix->rotulo->label();

$get="origem=N";
include(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$data=date("d-m-Y",db_getsession("DB_datausu"));
$data=split('-',$data);
$dia=$data[0];
$mes=$data[1];
$ano=$data[2];
?>
<form name="form1" method="post" action="" >
<center>
<table border="0">
  <tr>
    <td align='center'>
       <fieldset><legend align='center'><b>ISSQN VARIÁVEL FIXADO  </b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq33_codigo?>">
       <?=@$Lq33_codigo?>
    </td>
    <td>
<?
db_input('q33_codigo',7,$Iq33_codigo,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq33_inscr?>">
       <?
       db_ancora(@$Lq33_inscr,"js_pesquisaq33_inscr(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('q33_inscr',10,$Iq33_inscr,true,'text',$db_opcao," onchange='js_pesquisaq33_inscr(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>


 <tr>
  <td nowrap title="<?=@$Tq36_processo?>">
<? db_ancora(@$Lq36_processo,"js_pesquisaq36_processo(true);",$db_opcao);?>
   </td>
   <td>
<? db_input('q36_processo',10,$Iq36_processo,true,'text',$db_opcao," onchange='js_pesquisaq36_processo(false);'")?>
<? db_input('p58_requer',40,$Ip58_requer,true,'text',3,'')?>
   </td>
</tr>

<tr>
    <td nowrap title="<?=@$Tq37_notifica?>">
    <?
     db_ancora(@$Lq37_notifica,"js_pesquisaq37_notifica(true);",$db_opcao);
    ?>
    </td>
    <td>
    <?
    db_input('q37_notifica',10,$Iq37_notifica,true,'text',$db_opcao," onchange='js_pesquisaq37_notifica(false);'")
    ?>
    <?
    db_input('y30_nome',40,$Iy30_nome,true,'text',3,'')
    ?>
    </td>
   </tr>


 <tr>
    <td nowrap title="<?=@$Tq33_tiporeg?>">
       <?=@$Lq33_tiporeg?>
    </td>
    <td>

<?
$x = array('e'=>'Estimado','a'=>'Arbitrado');
db_select('q33_tiporeg',$x,true,$db_opcao,"");
?>
<?
if (isset($q33_hora)&& $q33_hora!=''){

}else{
  $q33_hora=db_hora();
}


if (isset($q33_data)&& $q33_data!=''){

}else{
  $q33_data_dia=date('d',db_getsession('DB_datausu'));
  $q33_data_mes=date('m',db_getsession('DB_datausu'));
  $q33_data_ano=date('Y',db_getsession('DB_datausu'));
}

?>

<?=@$Lq33_hora?>

<?
db_input('q33_hora',5,@$Iq33_hora,true,'text',3);
?>


<?=@$Lq33_data?>
<?
db_inputdata('q33_data',@$q33_data_dia,@$q33_data_mes,@$q33_data_ano,true,'text',3);
?>


    </td>
  </tr>


<tr>
   <td nowrap title="<?=@$Tq33_obs?>">
   <?=@$Lq33_obs?>
   </td>
   <td>
   <? db_textarea('q33_obs',8,47,$Iq33_obs,true,'text',$db_opcao,"") ?>
   </td>
   </tr>

	<tr>
	    <td nowrap title="<?=@$Ty100_sequencial?>">
	       <?
	       db_ancora(@$Ly100_sequencial,"js_pesquisaprocfiscal(true);",$db_opcao);
	       ?>
	    </td>
	    <td>
	<?
	db_input('procfiscal',10,$Iy100_sequencial,true,'text',$db_opcao," onchange='js_pesquisaprocfiscal(false);'")
	?>
	       <?
	db_input('nome',40,$Iz01_nome,true,'text',3,'')
	       ?>
	    </td>
	  </tr>


  <tr>
    <td colspan='2' align='center'>
       <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
       <input name="pesquisar" type="button" value="Pesquisar" onclick="js_pesquisa();" >
    </td>
  </tr>
 </table>
    </fieldset>
   </td>
  </tr>
<?
  if($db_opcao==3 || $db_opcao==2){//so entra quando tiver alterando ou excluindo o varfix
    $clvarfixval->rotulo->label();
?>
  <tr>
    <td align='center'>
       <fieldset><legend align='center'><b>VALORES DO VARIÁVEL FIXADO </b></legend>
       <table>
	  <tr>
         <td nowrap title="<?=@$Tq34_numpar?>">
	        <?=@$Lq34_numpar?>
	    </td>
	    <td>
          <?
		  if(empty($q34_mes)){
     		 $q34_numpar = date("m",db_getsession('DB_datausu'));
	      }
		  $par = array("1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9","10"=>"10","11"=>"11","12"=>"12");
		  db_select("q34_numpar",$par,true,$db_opcao05,"","","","","");
		  ?>
       </td>


	    <td nowrap title="<?=@$Tq34_mes?>">
	       <?=@$Lq34_mes?>
	    </td>
	    <td>
		<?
		if(empty($q34_mes)){
		 $q34_mes=date("m",db_getsession('DB_datausu'));
		}
		$resultw=array("1"=>"Janeiro","2"=>"Fevereiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
		  db_select("q34_mes",$resultw,true,$db_opcao05,"","","","","");
		?>
	    </td>
	    <td nowrap title="<?=@$Tq34_ano?>">
	       <?=@$Lq34_ano?>
	    </td>
	    <td>
		<?
		$anos=array();
		$anoatual=date("Y",db_getsession("DB_datausu"));
		for($i=$anoatual; $i>($anoatual-10); $i--){
		 $anos[$i]=$i;
		}
		db_select("q34_ano",$anos,true,$db_opcao05,"","","","","");
		?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tq34_dtval?>">
	       <?=@$Lq34_dtval?>
	    </td>
	    <td>
	<?
	if(empty($q34_dtval_dia)){
	    $q34_dtval_dia=$dia;
	    $q34_dtval_mes=$mes;
	    $q34_dtval_ano=$ano;
	}
	db_inputdata('q34_dtval',@$q34_dtval_dia,@$q34_dtval_mes,@$q34_dtval_ano,true,'text',$db_opcao04,"");
	?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tq34_valor?>">
	       <?=@$Lq34_valor?>
	    </td>
	    <td>
	<?
	db_input('q34_valor',10,$Iq34_valor,true,'text',$db_opcao04,"")
	?>
	    </td>
	    <td nowrap title="<?=@$Tq34_inflat?>">
	       <?
                db_ancora(@$Lq34_inflat,"js_pesquisainflat(true);",$db_opcao04);
		?>
	    </td>
	    <td colspan='3'>
	<?
             db_input('q34_inflat',10,$Iq34_inflat,true,'text',$db_opcao04,"onchange='js_pesquisainflat(false)'")
	?>
       <?
             db_input('i01_descr',40,$Ii01_descr,true,'text',3,'')
       ?>
	    </td>
       <?
       ?>
          </tr>
	  <tr>
	    <td colspan='6' align='center'>
              <input name="<?=($db_opcao02==1?"inc":($db_opcao02==2||$db_opcao02==22?"alt":"exc"))?>" type="submit" id="db_opcao" value="<?=($db_opcao02==1?"Incluir":($db_opcao02==2||$db_opcao02==22?"Alterar":"Excluir"))?>" <?=($db_botao02==false?"disabled":"")?> >
              <input name="todos" type="button" value="Lançar para todos meses" onclick="js_todosmeses();" >
              <input name="todosmeses" type="hidden" value="" onclick="" >
<?
if(isset($opcao)){
?>
<input name="novo" type="button" value="Novo" onclick="js_novo();" >
<?
}
?>
	    </td>
	  </tr>
       </table>
       </fieldset>
    </td>
  </tr>
  <tr>
    <td valign="top" >
     <?
      $cliframe_alterar_excluir->sql = $clvarfixval->sql_query_file(null,"q34_numpar, q34_codigo,q34_mes,q34_ano,q34_valor,q34_inflat,q34_dtval","q34_mes"," q34_codigo = $q33_codigo");
      $chavepri = array("q34_codigo"=>$q33_codigo,"q34_mes"=>@$q34_mes,"q34_ano"=>@$q34_ano);
      $cliframe_alterar_excluir->chavepri=$chavepri;
      $cliframe_alterar_excluir->campos  =" q34_numpar, q34_mes, q34_ano, q34_valor, q34_inflat, q34_dtval ";
      $cliframe_alterar_excluir->legenda="VALORES DE LANÇADOS";
      $cliframe_alterar_excluir->iframe_height ="140";
      $cliframe_alterar_excluir->iframe_width ="700";
      $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao02);
     ?>
     </td>
    </tr>
<?
    }
?>
  </table>
  </center>
</form>
<script>
function js_pesquisainflat(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_inflan','func_inflan.php?funcao_js=parent.js_mostrainflan1|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_inflan','func_inflan.php?pesquisa_chave='+document.form1.q34_inflat.value+'&funcao_js=parent.js_mostrainflan','Pesquisa',false);
  }
}
function js_mostrainflan(chave,erro){
  document.form1.i01_descr.value = chave;
  if(erro==true){
    document.form1.q34_inflat.focus();
    document.form1.q34_inflat.value = '';
  }
}
function js_mostrainflan1(chave1,chave2){
  document.form1.q34_inflat.value = chave1;
  document.form1.i01_descr.value = chave2;
  db_iframe_inflan.hide();
}
function js_todosmeses(){
  if(confirm('Voce deseja realmente replicar os valores para todo ano?')){
      document.form1.todosmeses.value = 't';
      document.form1.submit();
  }else{
      return false;
  }
}

function js_novo(){
  obj=document.createElement('input');
  obj.setAttribute('name','novo');
  obj.setAttribute('type','hidden');
  obj.setAttribute('value','ok');
  document.form1.appendChild(obj);
  document.form1.submit();
}
function js_tipcalc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_ativid','func_tipcalcalt.php?funcao_js=parent.js_mostratip1|0|1','Pesquisa',true);
  }else{
   js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_ativid','func_tipcalcalt.php?pesquisa_chave='+document.form1.q33_tipcalc.value+'&funcao_js=parent.js_mostratip','Pesquisa',false);
  }
}


function js_pesquisaq36_processo(mostra){
 if(mostra==true){
   js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_requer','Pesquisa',true);
 }else{
  if(document.form1.q36_processo.value != ''){
   js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.q36_processo.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
  }else{
    document.form1.p58_codproc.value = '';
  }
  }
 }
function js_mostraprotprocesso(chave,erro){
  document.form1.p58_codproc.value = chave;
 if(erro==true){
  document.form1.q36_processo.focus();
  document.form1.q36_processo.value = '';
		  }
}
function js_mostraprotprocesso1(chave1,chave2){
    document.form1.q36_processo.value = chave1;
    document.form1.p58_requer.value = chave2;
    db_iframe_protprocesso.hide();
}

function js_pesquisaq37_notifica(mostra){
    if(mostra==true){
     js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_fiscal','func_fiscal.php?funcao_js=parent.js_mostrafiscal1|y30_codnoti|y30_nome','Pesquisa',true);
     }else{
    if(document.form1.q37_notifica.value != ''){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_fiscal','func_fiscal.php?pesquisa_chave='+document.form1.q37_notifica.value+'&funcao_js=parent.js_mostrafiscal','Pesquisa',false);
    }else{
     document.form1.y30_nome.value = '';
   }
  }
}
function js_mostrafiscal(chave,erro){
    document.form1.y30_data.value = chave;
      if(erro==true){
        document.form1.q37_notifica.focus();
        document.form1.q37_notifica.value = '';
		  }
}
function js_mostrafiscal1(chave1,chave2){
    document.form1.q37_notifica.value = chave1;
    document.form1.y30_nome.value = chave2;
    db_iframe_fiscal.hide();
}



function js_mostratip(chave,erro){
  document.form1.q81_descr.value = chave;
  if(erro==true){
    document.form1.q33_tipcalc.focus();
    document.form1.q33_tipcalc.value = '';
  }
}
function js_mostratip1(chave1,chave2){
  document.form1.q33_tipcalc.value = chave1;
  document.form1.q81_descr.value = chave2;
  db_iframe_ativid.hide();
}
function js_pesquisaq33_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.q33_inscr.value+'&funcao_js=parent.js_mostraissbase','Pesquisa',false);
  }
}
function js_mostraissbase(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.q33_inscr.focus();
    document.form1.q33_inscr.value = '';
  }
}
function js_mostraissbase1(chave1,chave2){
  document.form1.q33_inscr.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_issbase.hide();
}

function js_pesquisaprocfiscal(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_alt.php?funcao_js=parent.js_mostraprocfiscal1|y100_sequencial|z01_nome|db_depart_protocolo|db_descr_depart|db_depart_atual<?=$get?>','Pesquisa',true);
  }else{
     if(document.form1.procfiscal.value != ''){
        js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_alt.php?pesquisa_chave='+document.form1.procfiscal.value+'&funcao_js=parent.js_mostraprocfiscal','Pesquisa',false,'0','1','775','390');
     }else{
		 	 document.form1.nome.value = '';
		 }
  }
}
function js_mostraprocfiscal(chave,erro,dep_prot,depart,dep_atual){

 if (dep_prot == dep_atual) {
  	document.form1.nome.value = chave;
    if(erro==true){
      document.form1.procfiscal.focus();
      document.form1.procfiscal.value = '';
    }
  }
  else {
    alert('Processo de protocolo não está neste departamento atualmente! \nDepartamento atual do processo:'+depart);
		document.form1.procfiscal.focus();
    document.form1.procfiscal.value = '';
		document.form1.nome.value = '';
		return false;
  }
}
function js_mostraprocfiscal1(chave1,chave2,dep_prot,depart,dep_atual){
   //alert('dep_prot='+dep_prot+' dep_atual='+dep_atual+'depart='+depart);
  if (dep_prot == dep_atual) {
  	document.form1.procfiscal.value = chave1;
  	document.form1.nome.value = chave2;
  	db_iframe_procfiscal.hide();
  }
  else {
    alert('Processo de protocolo não está neste departamento atualmente! \nDepartamento atual do processo:'+depart);
		return false;
  }
}


function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_varfix','func_varfix.php?funcao_js=parent.js_preenchepesquisa|q33_codigo','Pesquisa',true);
}


function js_preenchepesquisa(chave){
  db_iframe_varfix.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}



</script>