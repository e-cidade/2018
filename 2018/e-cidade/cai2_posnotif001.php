<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_lista_classe.php");
include("classes/db_arretipo_classe.php");
include("classes/db_notificacao_classe.php");
$clarretipo    = new cl_arretipo;
$clnotificacao = new cl_notificacao;
$clarretipo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k00_tipo');
$clrotulo->label('k00_descr');
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
db_postmemory($HTTP_POST_VARS);
$db_opcao = 2;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>


function js_sobe() {
  var F = document.getElementById("campos");
  if(F.selectedIndex != -1 && F.selectedIndex > 0) {
    var SI = F.selectedIndex - 1;
    var auxText = F.options[SI].text;
	var auxValue = F.options[SI].value;
	F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
	F.options[SI + 1] = new Option(auxText,auxValue);
	js_trocacordeselect();
	F.options[SI].selected = true;
  }
}
function js_desce() {
  var F = document.getElementById("campos");
  if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {
    var SI = F.selectedIndex + 1;
    var auxText = F.options[SI].text;
	var auxValue = F.options[SI].value;
	F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
	F.options[SI - 1] = new Option(auxText,auxValue);
	js_trocacordeselect();
	F.options[SI].selected = true;
  }
}
function js_excluir() {
  var F = document.getElementById("campos");
  var SI = F.selectedIndex;
  if(F.selectedIndex != -1 && F.length > 0) {
    F.options[SI] = null;
	js_trocacordeselect();
    if(SI <= (F.length - 1))
      F.options[SI].selected = true;
  }
}
function js_insSelect() {
  var texto=document.form1.k60_descr.value;
  var valor=document.form1.k60_codigo.value;
  if(texto != "" && valor != ""){
    var F = document.getElementById("campos");
    var testa = false;

    for(var x = 0; x < F.length; x++){

      if(F.options[x].value == valor || F.options[x].text == texto){
        testa = true;
	break;
      }
    }
    if(testa == false){
      F.options[F.length] = new Option(texto,valor);
      js_trocacordeselect();
    }
 }
   texto=document.form1.k60_descr.value="";
   valor=document.form1.k60_codigo.value="";
 document.form1.lanca.onclick = '';
}

function js_valor(){

  if (document.form1.quebrar.value == 'f'){
    document.getElementById('lordem3').style.visibility='visible';
  }else{
    document.getElementById('lordem3').style.visibility='hidden';
  }

}
function js_verifica(){
  var F = document.getElementById("campos").options;
  for(var i = 0;i < F.length;i++) {
    F[i].selected = true;
  }
  return true;
}
function js_emite(){
  itemselecionado = 0;
  numElems = document.form1.grupo.length;
  for (i=0;i<numElems;i++) {
      if (document.form1.grupo[i].checked) itemselecionado = i;
  }
  grupo = document.form1.grupo[itemselecionado].value;


  itemselecionado = 0;
  numElems = document.form1.ordemtipo.length;
  for (i=0;i<numElems;i++) {
      if (document.form1.ordemtipo[i].checked) itemselecionado = i;
  }
  ordemtipo = document.form1.ordemtipo[itemselecionado].value;


  itemselecionado = 0;
  numElems = document.form1.ordem.length;
  for (i=0;i<numElems;i++) {
      if (document.form1.ordem[i].checked) itemselecionado = i;
  }
  ordem = document.form1.ordem[itemselecionado].value;


  var H = document.getElementById("campos").options;
  if(H.length > 0){
     campo = 'campo=';
     virgula = '';
     for(var i = 0;i < H.length;i++) {
       campo += virgula+H[i].value;
       virgula = '-';
     }
  }else{
     campo = '';
  }

  jan = window.open('cai2_devedores_002.php?'+campo+'&massa='+document.form1.massa.value+'&ordemtipo='+ordemtipo+'&data1='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value+'&data='+document.form1.data_ano.value+'-'+document.form1.data_mes.value+'-'+document.form1.data_dia.value+'&quebrar='+document.form1.quebrar.value+'&grupo='+grupo+'&ordem='+ordem+'&numerolista='+document.form1.numerolista2.value+'&valormaximo='+document.form1.DBtxt11.value+'&valorminimo='+document.form1.DBtxt10.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_emite1(){

  var sSituacoes   = "";
  var sSeparador   = "";
  var aChkSituacao = js_getElementbyClass(form1,"situacao");

	for(var i=0; i < aChkSituacao.length; i++) {
    if (aChkSituacao[i].checked == true) {
      sSituacoes += sSeparador+aChkSituacao[i].name;
      sSeparador = "|";
    }
  }

	var F = document.getElementById("campos").options;
  for(var i = 0;i < F.length;i++) {
    F[i].selected = true;
  }

  var H = document.getElementById("campos").options;
  if(H.length > 0){
     campo = 'campo=';
     virgula = '';
     for(var i = 0;i < H.length;i++) {
       campo += virgula+H[i].value;
       virgula = '-';
     }
  }else{
     campo = '';
  }

  jan = window.open('cai2_posnotif002.php?'+campo+'&situacao='+sSituacoes,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
		<tr>
			<td width="360" height="0">&nbsp;</td>
			<td width="263">&nbsp;</td>
			<td width="25">&nbsp;</td>
			<td width="140">&nbsp;</td>
		</tr>
	</table>
  <table  border="0" align="center">
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
		<?
		if (1==2) {
		?>
     <tr>
        <td title="Data da Geração do Cálculo"><strong>Data do Cálculo:</strong>&nbsp;&nbsp;
         <?
					$DBtxt21_ano = db_getsession("DB_anousu");
					$DBtxt21_mes = '01';
					$DBtxt21_dia = '01';
					db_inputdata('DBtxt21',$DBtxt21_dia,$DBtxt21_mes,$DBtxt21_ano ,true,'text',4)
				 ?>
        </td>
        <td >Até&nbsp;&nbsp;
         <?
          $DBtxt22_ano = date('Y');
          $DBtxt22_mes = date('m');
          $DBtxt22_dia = date('d');

					db_inputdata('DBtxt22',$DBtxt22_dia,$DBtxt22_mes,$DBtxt22_ano,true,'text',4)
				 ?>
        </td>
      </tr>
		<?
		}
		?>
			<tr>
				<td colspan="2">
					<table align="center" >
					 <tr>
						 <td nowrap title="Escolha as notificações a serem listados ou deixe em branco para listar todas" >
							 <br>
								 <fieldset>
									 <Legend>
										 <b>Selecione as Listas</b>
									 </legend>
									 <table border="0">
									   <tr>
											 <td nowrap title="<?=@$Tk60_codigo?>" colspan="2">
												 <?
												 	 db_ancora(@$Lk60_codigo,"js_pesquisalista(true);",$db_opcao);
												 ?>
												 <?
												 	 db_input('k60_codigo',8,$Ik60_codigo,true,'text',$db_opcao," onchange='js_pesquisalista(false);'")
												 ?>
												 <?
												 	 db_input('k60_descr',25,$Ik60_descr,true,'text',3,'')
												 ?>
												 <input name="lanca" type="button" value="Lançar" >
											 </td>
										 </tr>
									   <tr>
											 <td align="right" colspan="" width="80%">
												 <select name="campos[]" id="campos" size="7" style="width:250px" multiple>
													 <?
															if(isset($chavepesquisa)){
																$resulta = $cllista->sql_record($cllista->sql_query("","","","k60_codigo = $chavepesquisa and k60_instit = $instit "));

																if($cllista->numrows!=0){
																	$numrows = $cllista->numrows;
																  for($i = 0;$i < $numrows;$i++) {
																		db_fieldsmemory($resulta,$i);
																	 echo "<option value=\"$k60_codigo \">$k60_descr</option>";
																	}
																}
															}
													 ?>
												 </select>
											 </td>
											 <td align="left" valign="middle" width="20%">
												 <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
                          <br/><br/>
                         <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
                          <br/><br/>
                         <img style="cursor:hand" onClick="js_excluir()" src="skins/img.php?file=Controles/bt_excluir.png" />
											 </td>
										 </tr>
									 </table>
								 </fieldset>
							 </td>
						 </tr>
					 </table>
				 </td>
			 </tr>
			 <tr>
				 <td>
					 <fieldset>
					   <legend>
							<b>Situação</b>
						 </legend>
						 <table>
							 <tr>
								 <td>
									 <table border='0' cellspacing='0' width='100%'>
										 <tr align='left'>
											 <td nowrap align='left' width='52%' style='padding-left:0px' > <input class='todas_opcoes' type="checkbox" checked="true" name="todas_opcoes"  id="todas_opcoes"   onChange="js_marcatodas(true);"><b>Marcar/Desmarcar Todas</b></td>
											 <td nowrap align='left' width='50%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="cda_sem_inicial"   id="cda_sem_inicial"onChange="js_marcatodas(false);"><b>CDA Sem Inicial</b></td>
										 </tr>
									 </table>
								 </td>
							 </tr>
							 <tr>
								 <td>
									 <table border='0' cellspacing='0' width='100%'>
										 <tr align='left'>
											 <td nowrap align='left' width='52%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="parcialmente_pago" id="parcialmente_pago"onChange="js_marcatodas(false);"><b>Parcialmente Pago</b></td>
											 <td nowrap align='left' width='50%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="totalmente_pago"   id="totalmente_pago" onChange="js_marcatodas(false);" ><b>Totalmente Pago 	</b></td>
										 </tr>
									 </table>
								 </td>
							 </tr>
							 <tr>
								 <td>
									 <table border='0' cellspacing='0' width='100%'>
										 <tr align='left'>
											 <td nowrap align='left' width='52%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="totalmente_debito" 	  id="totalmente_debito"  onChange="js_marcatodas(false);" ><b>Totalmente em Débito </b></td>
											 <td nowrap align='left' width='50%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="parcelamento_anulado" id="parcelamento_anulado"onChange="js_marcatodas(false);"><b>Parcelamento Anulado </b></td>
										 </tr>
									 </table>
								 </td>
							 </tr>
							 <tr>
								 <td>
									 <table border='0' cellspacing='0' width='100%'>
										 <tr align='left'>
											 <td nowrap align='left' width='52%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="parcialmente_cancelado" id="parcialmente_cancelado"onChange="js_marcatodas(false);"><b>Parcialmente Cancelado </b></td>
											 <td nowrap align='left' width='50%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="totalmente_cancelado"   id="totalmente_cancelado"onChange="js_marcatodas(false);"><b>Totalmente Cancelado 	</b></td>
										 </tr>
									 </table>
								 </td>
							 </tr>
							 <tr>
								 <td>
									 <table border='0' cellspacing='0' width='100%'>
										 <tr align='left'>
											 <td nowrap align='left' width='52%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="reparc_totalmente_debito"  id="reparc_totalmente_debito"onChange="js_marcatodas(false);"><b>Reparcelado e Totalmente em Débito </b></td>
											 <td nowrap align='left' width='50%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="reparc_parcialmente_pago" id="reparc_parcialmente_pago"onChange="js_marcatodas(false);"><b>Reparcelado e Parcialmente Pago 		</b></td>
										 </tr>
									 </table>
								 </td>
							 </tr>
							 <tr>
								 <td>
									 <table border='0' cellspacing='0' width='100%'>
										 <tr align='left'>
											 <td nowrap align='left' width='52%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="reparc_totalmente_pago"   id="reparc_totalmente_pago"onChange="js_marcatodas(false);"  ><b>Reparcelado e Totalmente Pago 			</b></td>
											 <td nowrap align='left' width='50%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="parc_totalmente_debito"  id="parc_totalmente_debito"onChange="js_marcatodas(false);"><b>Parcelado e Totalmente em Débito </b></td>
										 </tr>
									 </table>
								 </td>
							 </tr>
							 <tr>
								 <td>
									 <table border='0' cellspacing='0' width='100%'>
										 <tr align='left'>
											 <td nowrap align='left' width='52%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="parc_parcialmente_pago" id="parc_parcialmente_pago"onChange="js_marcatodas(false);"><b>Parcelado e Parcialmente Pago 		</b></td>
											 <td nowrap align='left' width='50%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="parc_totalmente_pago"   id="parc_totalmente_pago"onChange="js_marcatodas(false);"  ><b>Parcelado e Totalmente Pago 			</b></td>
										 </tr>
									 </table>
								 </td>
							 </tr>
							 <tr>
								 <td>
									 <table border='0' cellspacing='0' width='100%'>
										 <tr align='left'>
											 <td nowrap align='left' width='52%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="aju_totalmente_debito" id="aju_totalmente_debito"onChange="js_marcatodas(false);"><b>Débito Ajuizado e Totalmente em Débito </b></td>
											 <td nowrap align='left' width='50%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="aju_parcialmente_pago" id="aju_parcialmente_pago"onChange="js_marcatodas(false);"><b>Débito Ajuizado e Parcialmente Pago 		</b></td>
										 </tr>
									 </table>
								 </td>
							 </tr>
							 <tr>
								 <td>
									 <table border='0' cellspacing='0' width='100%'>
										 <tr align='left'>
											 <td nowrap align='left' width='52%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="aju_totalmente_pago"   id="aju_totalmente_pago"onChange="js_marcatodas(false);"  ><b>Débito Ajuizado e Totalmente Pago 			</b></td>
											 <td nowrap align='left' width='50%' style='padding-left:0px' > <input class='situacao' type="checkbox" checked="true" name="aju_parcelado_debito"  id="aju_parcelado_debito"onChange="js_marcatodas(false);" ><b>Débito Ajuizado e Parcelado 					  </b></td>
										 </tr>
									 </table>
								 </td>
							 </tr>
						 </table>
					 </fieldset>
				 </td>
			 </tr>
			 <tr height="40">
         <td align="center" colspan="2">
  	       <input name="imprimir" type="button" id="imprimir" value="Imprimir" onClick="js_emite1();">
				 </td>
       </tr>
		 </form>
   </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_marcatodas(lPri){

  var aChkSituacao = js_getElementbyClass(form1,"situacao");
  var CampPri      =	document.getElementById('todas_opcoes');

	if (lPri) {
		if ( CampPri.checked == false ){
			for(var i=0; i < aChkSituacao.length; i++) {
					aChkSituacao[i].checked = false;
			}
		}else{
			for(var i=0; i < aChkSituacao.length; i++) {
				if (aChkSituacao[i].checked == false) {
					aChkSituacao[i].checked = true;
				}
			}
    }
	}else{
		document.getElementById('todas_opcoes').checked = false;
	}

}


function js_pesquisalista(mostra){
  document.form1.lanca.onclick = "";
  parent.bstatus.document.getElementById('st').innerHTML = '<font size="2" color="darkblue"><b>Processando<blink>...</blink></b></font>' ;
  if(mostra==true){
    db_iframe.jan.location.href = 'func_lista.php?funcao_js=parent.js_mostralista1|k60_codigo|k60_descr';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_lista.php?pesquisa_chave='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostralista';
  }
}
function js_mostralista(chave,erro){
  document.form1.k60_descr.value = chave;
  if(erro==true){
    document.form1.k60_codigo.focus();
    document.form1.k60_codigo.value = '';
  }else{
    document.form1.lanca.onclick = js_insSelect;
  }
    parent.bstatus.document.getElementById('st').innerHTML = "Configuração -> Documentos" ;

}
function js_mostralista1(chave1,chave2){
  document.form1.k60_codigo.value = chave1;
  document.form1.k60_descr.value = chave2;
  db_iframe.hide();
  document.form1.lanca.onclick = js_insSelect;
}
function js_pesquisa(){
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}

</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

if(isset($ordem)){
  echo "<script>
       js_emite();
       </script>";
}
?>