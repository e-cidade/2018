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

$clselecao->rotulo->label();
$clpontofx->rotulo->label();
$clpontofs->rotulo->label();
$clpontofa->rotulo->label();
$clpontofe->rotulo->label();
$clpontofr->rotulo->label();
$clpontof13->rotulo->label();
$clpontocom->rotulo->label();
$clrhrubricas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("r90_datlim");

$TRTDA = "
          <tr>
         ";
$TRTDF = "
          </tr>
         ";
$tagaf = true;

if(isset($valores_campos_rel)){
	$arr_pontos = split(",",$valores_campos_rel);
	$pontosselecionados = "<table cellspacing='10' width='100%'>";
	for($ii=0; $ii<count($arr_pontos); $ii++){
		$ponto = $arr_pontos[$ii];
		if($ponto == "fa"){
			$chkfa = "";
			if(isset($fa) || !isset($processar)){
				$chkfa = "checked";
			}
			$valoresdatag = "
	                     <td align='left' valign='top'>
	                       <input type='checkbox' name='fa' value='fa' ".$chkfa.">Ponto Adiantamento
	                     </td>
	                    ";
			if($tagaf == true){
				$pontosselecionados .= $TRTDA.$valoresdatag;
				$tagaf = false;
			}else{
				$pontosselecionados .= $valoresdatag.$TRTDF;
				$tagaf = true;
			}
		}else if($ponto == "fc"){
			$chkfc = "";
			if(isset($fc) || !isset($processar)){
				$chkfc = "checked";
			}
			$valoresdatag = "
	                     <td align='left' valign='top'>
	                       <input type='checkbox' name='fc' value='fc' ".$chkfc.">Ponto Complementar
	                     </td>
	                    ";
			if($tagaf == true){
				$pontosselecionados .= $TRTDA.$valoresdatag;
				$tagaf = false;
			}else{
				$pontosselecionados .= $valoresdatag.$TRTDF;
				$tagaf = true;
			}
		}else if($ponto == "f3"){
			$chkf3 = "";
			if(isset($f3) || !isset($processar)){
				$chkf3 = "checked";
			}
			$valoresdatag = "
	                     <td align='left' valign='top'>
	                       <input type='checkbox' name='f3' value='f3' ".$chkf3.">Ponto 13o Salário
	                     </td>
	                    ";
			if($tagaf == true){
				$pontosselecionados .= $TRTDA.$valoresdatag;
				$tagaf = false;
			}else{
				$pontosselecionados .= $valoresdatag.$TRTDF;
				$tagaf = true;
			}
		}else if($ponto == "fe"){
			$chkfe = "";
			if(isset($fe) || !isset($processar)){
				$chkfe = "checked";
			}
			$valoresdatag = "
	                     <td align='left' valign='top'>
	                       <input type='checkbox' name='fe' value='fe' ".$chkfe.">Ponto Férias
	                     </td>
	                    ";
			if($tagaf == true){
				$pontosselecionados .= $TRTDA.$valoresdatag;
				$tagaf = false;
			}else{
				$pontosselecionados .= $valoresdatag.$TRTDF;
				$tagaf = true;
			}
		}else if($ponto == "fx"){
			$chkfx = "";
			if(isset($fx) || !isset($processar)){
				$chkfx = "checked";
			}
			$valoresdatag = "
	                     <td align='left' valign='top'>
	                       <input type='checkbox' name='fx' value='fx'  ".$chkfx." onclick='js_datlim(false);'>Ponto Fixo
	                     </td>
	                    ";
			if($tagaf == true){
				$pontosselecionados .= $TRTDA.$valoresdatag;
				$tagaf = false;
			}else{
				$pontosselecionados .= $valoresdatag.$TRTDF;
				$tagaf = true;
			}
		}else if($ponto == "fr"){
			$chkfr = "";
			if(isset($fr) || !isset($processar)){
				$chkfr = "checked";
			}
			$valoresdatag = "
	                     <td align='left' valign='top'>
	                       <input type='checkbox' name='fr' value='fr'  ".$chkfr.">Ponto Rescisão
	                     </td>
	                    ";
			if($tagaf == true){
				$pontosselecionados .= $TRTDA.$valoresdatag;
				$tagaf = false;
			}else{
				$pontosselecionados .= $valoresdatag.$TRTDF;
				$tagaf = true;
			}
		}else if($ponto == "fs"){
			$chkfs = "";
			if(isset($fs) || !isset($processar)){
				$chkfs = "checked";
			}
			$valoresdatag = "
	                     <td align='left' valign='top'>
	                       <input type='checkbox' name='fs' value='fs'  ".$chkfs." onclick='js_datlim(false);'>Ponto Salário
	                     </td>
	                    ";
			if($tagaf == true){
				$pontosselecionados .= $TRTDA.$valoresdatag;
				$tagaf = false;
			}else{
				$pontosselecionados .= $valoresdatag.$TRTDF;
				$tagaf = true;
			}
		}
	}
}
if($tagaf == false){
	$pontosselecionados .= $TRTDF;
	$tagaf = true;
}
$pontosselecionados .= "</table>";

$result_rubrica = $clrhrubricas->sql_record($clrhrubricas->sql_query_file($rubrica,db_getsession("DB_instit"),"rh27_descr,rh27_limdat,rh27_form"));
if($clrhrubricas->numrows > 0){
	db_fieldsmemory($result_rubrica,0);
}

$arr_opcao = array('so' => 'Somar','su'=>'Subtrair');
?>
<form name="form1" method="post" action="">
<table border="0" cellspacing="8" cellpadding="0">
  <tr>
    <td align='center'>
      <hr>
      <b>Rubrica: <?=$rh27_rubric." - ".$rh27_descr?><i><?=str_repeat("&nbsp;",5)?>(<?=($iae=="i"?"Inclusão":($iae=="a"?"Alteração":"Exclusão"))?>)</i></b>
      <hr>
    </td>
  </tr>
  <tr>
    <td align='center'>
      <fieldset>
        <Legend align="left">
          <b>Pontos Selecionados</b>
        </Legend>
        <table width="100%">
				  <tr>
				    <td align='left'>
				      <?=$pontosselecionados?>
				    </td>
				  </tr>
	      </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2" align='center'>
    <?
    if($iae != "e"){ 
    ?>
      <fieldset>
        <Legend align="left">
          <b>Lançar Valor</b>
        </Legend>
        <center>
	        <table cellspacing="8" cellpadding="0">
					  <tr>
					    <td align='center' width='33%'>
					      <b>Valor Atual</b>
					    </td>
					    <td align='center' width='33%'>
					      <b>Novo Valor</b>
					    </td>
					    <td align='center' width='33%'>
					      <b>(%)</b>
					    </td>
					  </tr>
					  <tr>
					    <td align='center'>
					      <table>
								  <tr>
								    <td>
								      <?
			                db_input('r90_valor',15, $Ir90_valor, true, 'text', 1, "onchange='js_desabcampos(this.name);'", 'valoratu');
			                ?>
								    </td>
								  </tr>
					      </table>
					    </td>
					    <td align='center'>
					      <table>
								  <tr>
								    <td>
								      <?
			                db_input('r90_valor',15, $Ir90_valor, true, 'text', 1, "onchange='js_desabcampos(this.name);'", 'valornov');
			                ?>
								    </td>
								  </tr>
					      </table>
					    </td>
					    <td align='center'>
					      <table>
								  <tr>
								    <td>
								      <?
			                db_input('porcentv', 3, $Ir90_quant, true, 'text', 1, "onchange='js_desabcampos(this.name);'");
			                ?>
								    </td>
								    <td>
								      <?
				              db_select("sosuv",$arr_opcao,true,1,"");
                      ?>
								    </td>
								  </tr>
					      </table>
					    </td>
					  </tr>
		      </table>
        </center>
      </fieldset>
      <?
       }
       ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align='center'>
    <?
    if($iae != "e"){ 
    ?>
      <fieldset>
        <Legend align="left">
          <b>Lançar Quantidade</b>
        </Legend>
        <center>
	        <table cellspacing="8" cellpadding="0">
					  <tr>
					    <td align='center' width='33%'>
					      <b>Quantidade Atual</b>
					    </td>
					    <td align='center' width='33%'>
					      <b>Nova Quantidade</b>
					    </td>
					    <td align='center' width='33%'>
					      <b>(%)</b>
					    </td>
					  </tr>
					  <tr>
					    <td align='center'>
					      <table>
								  <tr>
								    <td>
								      <?
			                db_input('r90_quant',15, $Ir90_quant, true, 'text', 1, "onchange='js_desabcampos(this.name);'", 'quantatu');
			                ?>
								    </td>
								  </tr>
					      </table>
					    </td>
					    <td align='center'>
					      <table>
								  <tr>
								    <td>
								      <?
			                db_input('r90_quant',15, $Ir90_quant, true, 'text', 1, "onchange='js_desabcampos(this.name);'", 'quantnov');
			                ?>
								    </td>
								  </tr>
					      </table>
					    </td>
					    <td align='center'>
					      <table>
								  <tr>
								    <td>
								      <?
			                db_input('porcentq', 3, $Ir90_quant, true, 'text', 1, "onchange='js_desabcampos(this.name);'");
			                ?>
								    </td>
								    <td>
								      <?
				              db_select("sosuq",$arr_opcao,true,1,"");
                      ?>
								    </td>
								  </tr>
					      </table>
					    </td>
					  </tr>
		      </table>
        </center>
      </fieldset>
      <?
      }
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align='center'>
    <?
    if($iae != "e"){ 
    ?>
      <fieldset>
        <Legend align="left">
          <b>Lançar Ano/Mês</b>
        </Legend>
        <center>
	        <table cellspacing="8" cellpadding="0">
					  <tr>
					    <td align='center' width='33%'>
					      <b>Ano/Mês Atual</b>
					    </td>
					    <td align='center' width='33%'>
					      <b>Novo Ano/Mês</b>
					    </td>
					  </tr>
					  <tr>
					    <td align='center'>
					      <table>
								  <tr>
								    <td>
								      <?
			                db_input('r90_datlim',15, $Ir90_datlim, true, 'text', 1, "onKeyUp='js_mascaradata(this.value, this.name, false);' onchange='js_desabcampos(this.name);'", "datlimatu");
			                ?>
								    </td>
								  </tr>
					      </table>
					    </td>
					    <td align='center'>
					      <table>
								  <tr>
								    <td>
								      <?
			                db_input('r90_datlim',15, $Ir90_datlim, true, 'text', 1, "onKeyUp='js_mascaradata(this.value, this.name, true);' onchange='js_desabcampos(this.name);'", "datlimnov");
			                ?>
								    </td>
								  </tr>
					      </table>
					    </td>
					  </tr>
		      </table>
        </center>
      </fieldset>
      <?    
      }
      ?>
    </td>
  </tr>
  <tr>
    <td colspan='2' align='center'>
      <input type="submit" name="processar" value="Processar" onclick="return js_testecampos();">
      <input type="button" name="limpar"    value="Limpar" onclick="js_limparcampos();">
      <input type="button" name="voltar"    value="Voltar" onclick="location.href='pes1_aturubricas001.php'" onblur="js_chamafuncao(true);">
    </td>
  </tr>
</table>
<?
db_input('rh27_rubric',15,"",true,'hidden',3);
db_input('valores_campos_rel',15, "", true, 'hidden', 3);
db_input('r44_selec',15, "", true, 'hidden', 3);
db_input('iae',15, "", true, 'hidden', 3);
db_input('testdtlim',15, "", true, 'hidden', 3);
db_input('rh27_limdat',15, "", true, 'hidden', 3);
?>
</form>
<script>
function js_testecampos(){
	xy = document.form1;
	erro = 0;
	if(document.form1.iae.value == "i"){
		if(xy.valornov.value != ""){
			erro ++;
		}
		if(xy.quantnov.value != ""){
			erro ++;
		}
	}else if(document.form1.iae.value == "a"){
		obrigav = false;
		obrigaq = false;
		obrigad = false;
	
		obrigaV = false;
		obrigaQ = false;
		obrigaD = false;

		if(xy.valoratu.value != ""){
			erro ++;
		  obrigav = true;
		}
		if(xy.valornov.value != ""){
			erro ++;
			obrigaV = true;
		}
		if(xy.porcentv.value != ""){
			erro ++;
			obrigaV = true;
		}
		if(xy.quantatu.value != ""){
			erro ++;
			obrigaq = true;
		}
		if(xy.quantnov.value != ""){
			erro ++;
			obrigaQ = true;
		}
		if(xy.porcentq.value != ""){
			erro ++;
			obrigaQ = true;
		}
		if(xy.datlimatu.value != "" && xy.testdtlim.value == 'true'){
			retorno = js_verificaposicoes(xy.datlimatu.value,'true','datlimatu', false);
			if(retorno == false){
				return false;
			}
			erro ++;
			obrigad = true;
		}
		if((xy.datlimnov.value != "" && xy.testdtlim.value == 'true') || xy.datlimatu.value != ""){
			retorno = js_verificaposicoes(xy.datlimnov.value,'true','datlimnov', true);
			if(retorno == false){
				return false;
			}
			erro ++;
			obrigaD = true;
		}
	
	  if(obrigav == true && obrigaV == false){
	  	alert("Informe o novo valor ou uma porcentagem a acrescentar ao valor.");
	  	return false;
	  }else if(obrigaq == true && obrigaQ == false){
	  	alert("Informe a nova quantidade ou uma porcentagem a acrescentar à quantidade.");
	  	return false;
	  }else if(obrigad == true && obrigaD == false){
	  	alert("Informe o novo Ano/Mês.");
	  	return false;
	  }
  }else{
    erro ++;  
  }

  if(erro == 0){
  	alert("Sem dados para atualização.");
  	js_datlim(true);
  	return false;
  }else{
  	if(document.form1.iae.value == "i"){
  		if(confirm("Incluir para Todos funcionarios ou somente para quem tem ponto?\n\nOK: Todos\nCancel: Somente quem tem ponto")){
        obj=document.createElement('input');
        obj.setAttribute('name','ativos');
        obj.setAttribute('type','hidden');
        obj.setAttribute('value','ativos');
        document.form1.appendChild(obj);
  		}
  	}
  	return true;
  }
}
function js_chamafuncao(focar){
	if(document.form1.valoratu.readOnly == false){
	  js_tabulacaoforms("form1","valoratu",focar,1,"quantatu",true);
	}else if(document.form1.quantatu.readOnly == false){
	  js_tabulacaoforms("form1","quantatu",focar,1,"valoratu",true);
	}else if(document.form1.iae.value == "i"){
		if(document.form1.valornov.readOnly == false){
			js_tabulacaoforms("form1","valornov",focar,1,"valornov",true);
		}else{
			js_tabulacaoforms("form1","quantnov",focar,1,"quantnov",true);
		}
	}else{
	  js_tabulacaoforms("form1","datlimatu",focar,1,"datlimatu",true);
	}
}
function js_trancaval(atu,nov,prc,sus){
	if(atu == true || document.form1.iae.value == "i"){
		document.form1.valoratu.value    = "";
    document.form1.valoratu.readOnly = true;
    document.form1.valoratu.style.backgroundColor="#DEB887";
	}else if(atu == false){
    document.form1.valoratu.readOnly = false;
    document.form1.valoratu.style.backgroundColor="";
	}

  if(nov == true || document.form1.iae.value == "e"){
		document.form1.valornov.value    = "";
    document.form1.valornov.readOnly = true;
    document.form1.valornov.style.backgroundColor="#DEB887";
  }else if(nov == false){
    document.form1.valornov.readOnly = false;
    document.form1.valornov.style.backgroundColor="";
  }

  if(prc == true || document.form1.iae.value == "i" || document.form1.iae.value == "e"){
		document.form1.porcentv.value    = "";
    document.form1.porcentv.readOnly = true;
    document.form1.porcentv.style.backgroundColor="#DEB887";
  }else if(prc == false){
    document.form1.porcentv.readOnly = false;
    document.form1.porcentv.style.backgroundColor="";
  }

  if(sus == true || document.form1.iae.value == "i" || document.form1.iae.value == "e"){
    document.form1.sosuv.disabled = true;
  }else if(sus == false){
    document.form1.sosuv.disabled = false;
  }
}
function js_trancaqtd(atu,nov,prc,sus){
	if(atu == true || document.form1.iae.value == "i"){
		document.form1.quantatu.value    = "";
    document.form1.quantatu.readOnly = true;
    document.form1.quantatu.style.backgroundColor="#DEB887";
	}else if(atu == false){
    document.form1.quantatu.readOnly = false;
    document.form1.quantatu.style.backgroundColor="";
	}

  if(nov == true || document.form1.iae.value == "e"){
		document.form1.quantnov.value    = "";
    document.form1.quantnov.readOnly = true;
    document.form1.quantnov.style.backgroundColor="#DEB887";
  }else if(nov == false){
    document.form1.quantnov.readOnly = false;
    document.form1.quantnov.style.backgroundColor="";
  }

  if(prc == true || document.form1.iae.value == "i" || document.form1.iae.value == "e"){
		document.form1.porcentq.value    = "";
    document.form1.porcentq.readOnly = true;
    document.form1.porcentq.style.backgroundColor="#DEB887";
  }else if(prc == false){
    document.form1.porcentq.readOnly = false;
    document.form1.porcentq.style.backgroundColor="";
  }

  if(sus == true || document.form1.iae.value == "i" || document.form1.iae.value == "e"){
    document.form1.sosuq.disabled = true;
  }else if(sus == false){
    document.form1.sosuq.disabled = false;
  }
}
function js_trancadtl(atu,nov){
	if(document.form1.testdtlim.value == "true"){
		if(atu == true){
			document.form1.datlimatu.value    = "";
	    document.form1.datlimatu.readOnly = true;
	    document.form1.datlimatu.style.backgroundColor="#DEB887";
		}else if(atu == false){
	    document.form1.datlimatu.readOnly = false;
	    document.form1.datlimatu.style.backgroundColor="";
		}
	
	  if(nov == true){
			document.form1.datlimnov.value    = "";
	    document.form1.datlimnov.readOnly = true;
	    document.form1.datlimnov.style.backgroundColor="#DEB887";
	  }else if(nov == false){
	    document.form1.datlimnov.readOnly = false;
	    document.form1.datlimnov.style.backgroundColor="";
	  }
	}
	js_datlim(false);
}
function js_desabcampos(campo){

	xw = document.form1;
	y = eval("xw."+campo);
	if(campo.search("quant") != -1 || campo == "porcentq"){
		if(campo != "quantatu"){
			js_trancaval(true,true,true,true);
			js_trancadtl(true,true);
			if(y.value != ""){
				if(campo == "quantnov"){
				  js_trancaqtd(false,false,true,true);
				}else{
				  js_trancaqtd(false,true,false,false);
				}
			}else{
				js_trancaqtd(false,false,false,false);
				if(xw.quantatu.value == ""){
					js_trancaval(false,false,false,false);
					js_trancadtl(false,false);
				}
			}
		}else if(campo == "quantatu" && y.value != ""){
      js_trancaval(true,true,true,true);
      js_trancadtl(true,true);
			if(xw.quantnov.value == "" && xw.porcentq.value == ""){
			  js_trancaqtd(false,false,false,false);
			}
		}else{
			if(xw.quantnov.value == "" && xw.porcentq.value == ""){
				js_trancaval(false,false,false,false);
			  js_trancaqtd(false,false,false,false);
			  js_trancadtl(false,false);
			}
		}
	}else if(campo.search("valor") != -1 || campo == "porcentv"){
		if(campo != "valoratu"){
			js_trancaqtd(true,true,true,true);
      js_trancadtl(true,true);
			if(y.value != ""){
				if(campo == "valornov"){
				  js_trancaval(false,false,true,true);
				}else{
				  js_trancaval(false,true,false,false);
				}
			}else{
				js_trancaval(false,false,false,false);
				if(xw.valoratu.value == ""){
					js_trancaqtd(false,false,false,false);
					js_trancadtl(false,false);
				}
			}
		}else if(campo == "valoratu" && y.value != ""){
			js_trancaqtd(true,true,true,true);
			js_trancadtl(true,true);
			if(xw.valornov.value == "" && xw.porcentv.value == ""){
			  js_trancaval(false,false,false,false);
			}
		}else{
			if(xw.valornov.value == "" && xw.porcentv.value == ""){
				js_trancaqtd(false,false,false,false);
			  js_trancaval(false,false,false,false);
			  js_trancadtl(false,false);
			}
		}
	}else if(campo.search("datlim") != -1){
		js_trancadtl(false,false);
		if(xw.datlimatu.value != "" || xw.datlimnov.value != ""){
			js_trancaval(true,true,true,true);
			js_trancaqtd(true,true,true,true);
		}else{
			js_trancaqtd(false,false,false,false);
		  js_trancaval(false,false,false,false);
		  js_trancadtl(false,false);
		}
	}
	js_chamafuncao(false);
	testar = false;
	for(ib=0; ib<xw.length; ib++){
		if(testar == true){
			if(xw.elements[ib].type == 'text' && (xw.elements[ib].readOnly == false && xw.elements[ib].disabled == false)){
				break;
			}else if((xw.elements[ib].type == 'select-one' || xw.elements[ib].type == 'button' || xw.elements[ib].type == 'submit') && xw.elements[ib].disabled == false){
				break;
			}
		}
		if(xw.elements[ib].name == campo){
			if(xw.elements[(ib+1)]){
				if(xw.elements[(ib+1)].type == 'text' && (xw.elements[(ib+1)].readOnly == false && xw.elements[(ib+1)].disabled == false)){
          ib ++;
					break;
				}else if((xw.elements[(ib+1)].type == 'select-one' || xw.elements[(ib+1)].type == 'button' || xw.elements[(ib+1)].type == 'submit') && xw.elements[(ib+1)].disabled == false){
					ib ++;
					break;
				}
			}
			testar = true;
		}
	}
	if(xw.elements[ib].type == 'text' || (xw.elements[ib].readOnly == false && xw.elements[ib].disabled == false)){
	  xw.elements[ib].select();
		xw.elements[ib].focus();
	}else if((xw.elements[ib].type == 'select-one' || xw.elements[ib].type == 'button' || xw.elements[ib].type == 'submit') && xw.elements[ib].disabled == false){
		xw.elements[ib].focus();
	}
}

function js_limparcampos(){
	document.form1.valoratu.value = "";
	document.form1.valornov.value = "";
	document.form1.porcentv.value = "";
  js_trancaval(false,false,false,false);

	document.form1.quantatu.value = "";
	document.form1.quantnov.value = "";
	document.form1.porcentq.value = "";
	js_trancaqtd(false,false,false,false);

	document.form1.datlimatu.value = "";
	document.form1.datlimnov.value = "";
  js_trancadtl(false,false);

  js_datlim(true);
}
function js_mascaradata(valor,campo,VERANOMES){

  xz = eval("document.form1."+campo);

  total = valor.length;
  if(total > 0){
    digit = valor.substr(total-1,1);
    if(digit != "/"){
      if(total == 4){
        valor += "/";
  	  }
    }
  }
  
  xz.value = valor;
  return js_verificaposicoes(valor,'false',campo,VERANOMES);

}
function js_verificaposicoes(valor,TorF,campo,VERANOMES){

  x = eval("document.form1."+campo);

  var expr = new RegExp("[^0-9]+");
  localbarra = valor.search("/");
  erro = 0;
  errm = "";
  if(localbarra == -1){
   	if(valor.match(expr)){
      erro ++;
  	}else if(TorF == "true" && x.readOnly == false){
  	  erro ++;
  	}
  }else{
    ano = valor.substr(0,4);
    mes = valor.substr(5,2);
    anoi = new Number(ano);
    mesi = new Number(mes);
    anot = new Number("<?=db_anofolha();?>");
    mest = new Number("<?=db_mesfolha();?>");
    
   	if(ano.match(expr)){
      erro ++;
  	}else if(mes.match(expr)){
      erro ++;
  	}else if((anoi < anot || (anoi <= anot && mesi < mest)) && VERANOMES == true){
  	  if(mesi > 1 || anoi < anot || TorF == 'true'){
        errm = "\nAno e mês devem ser maior ou igual ao corrente da folha.";
        erro ++;
      }
  	}else if(mesi > 12){
      errm = "\nMês inexistente.";
      erro ++;
  	}else if(TorF == 'true' && mes == 0){
      errm = "\nMês não informado.";
      erro ++;
  	}
  }
  // alert(x);
  if(erro > 0 || (x.value == "" && TorF == 'true')){
    alert("Campo Ano/mês deve ser preenchido com números e uma '/' no seguinte formato (aaaa/mm)! " + errm);
    x.select();
    x.focus();
    return false;
  }
  return true;

}
function js_datlim(opcao){
  x = document.form1;
  erroX = 1;
  erroS = 1;
  if(x.fx){
  	if(x.fx.checked == true){
  		erroX = 0;
  	}
  }
  if(x.fs){
  	if(x.fs.checked == true){
  		erroS = 0;
  	}
  }

  testelimdat = document.form1.rh27_limdat.value;
  if(testelimdat == "f"){
  	erroX = 1;
  	erroS = 1;
  }

  branco = false;
  if(document.form1.valoratu.value == "" && document.form1.valornov.value == "" && document.form1.porcentv.value == "" && document.form1.quantatu.value == "" && document.form1.quantnov.value == "" && document.form1.porcentq.value == ""){
  	branco = true;
  }

  if((erroX == 0 || erroS == 0) && document.form1.iae.value != "i" && branco == true){
    document.form1.testdtlim.value = "true";

  	document.form1.datlimatu.readOnly = false;
    document.form1.datlimatu.style.backgroundColor="";

  	document.form1.datlimnov.readOnly = false;
    document.form1.datlimnov.style.backgroundColor="";
  }else{
    document.form1.testdtlim.value = "false";

  	document.form1.datlimatu.readOnly = true;
  	document.form1.datlimnov.value    = "";
    document.form1.datlimatu.style.backgroundColor="#DEB887";

  	document.form1.datlimnov.readOnly = true;
  	document.form1.datlimatu.value    = "";
    document.form1.datlimnov.style.backgroundColor="#DEB887";

    if(branco == true){
		  js_trancaqtd(false,false,false,false);
	    js_trancaval(false,false,false,false);
    }
  }
  js_chamafuncao(opcao);
}
</script>