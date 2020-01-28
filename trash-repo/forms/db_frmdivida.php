<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: dividaativa
$cldivida->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("v03_descr");
$dia=date('d',db_getsession("DB_datausu"));
$ano=date('Y',db_getsession("DB_datausu"));
?>
<script>
function js_sub(){

    var dia    = new Number(document.form1.v01_dtvenc_dia.value); 
    var mes    = new Number(document.form1.v01_dtvenc_mes.value); 
    var ano    = new Number(document.form1.v01_dtvenc_ano.value); 
    var vlrhis = document.form1.v01_vlrhis.value; 

    if (dia == "" || mes == "" || ano == ""){ 
      alert("Preencha a data do vencimento.");
    } else {
      
      if (document.form1.v01_proced.value == '') {
         alert("Selecione proced�ncia.");
      } else { 
	      if (vlrhis=="") {
	       alert("Preencha o campo valor hist�rico.");
	      }else{  

	        //$('v01_valor').value = vlrhis;
            document.form1.subtes.value="ok";
            document.form1.submit();  
	      }  
      }  
   }  
}
</script>
  <?
     if($db_opcao==1){
       $p="04";
     }else if($db_opcao==2 || $db_opcao==22){
       $p="05";
     }else{
       $p="06";
     }  
  ?>
  
<form name="form1" method="post" action="div1_divida0<?=$p?>.php">
<fieldset style="margin-top: 30px;">

  <legend>
    <strong>
      Cadastro de D�vida
    </strong>
  </legend>





<?

if (!isset($existeumacda)) {
  $existeumacda=false;
}

if (isset($j01_matric)) {
  db_input('j01_matric',10,2,true,'hidden',1);
}
if (isset($q02_inscr)) {
  db_input('q02_inscr',10,2,true,'hidden',1);
}
?>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tv01_coddiv?>">
       <?db_input('subtes',10,2,true,'hidden',1)?>
       <?=@$Lv01_coddiv?>
    </td>
    <td> 
      <?
      db_input('v01_coddiv',10,$Iv01_coddiv,true,'text',3)
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv01_numcgm?>">
       <?
       db_ancora(@$Lv01_numcgm,"js_pesquisav01_numcgm(true);",($existeumacda == true?3:$db_opcao));
       ?>
    </td>
    <td> 
      <?
        db_input('v01_numcgm',10,$Iv01_numcgm,true,'text',3," onchange='js_pesquisav01_numcgm(false);'")
      ?>
       <?
        db_input('z01_nome',60,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv01_dtinsc?>">
       <?=@$Lv01_dtinsc?>
    </td>
    <td> 
      <?
      if(empty($v01_dtinsc_dia) && $db_opcao==1){
        $v01_dtinsc_dia=$dia;
        $v01_dtinsc_mes=$mes;
        $v01_dtinsc_ano=$ano;
      }
      db_inputdata('v01_dtinsc',@$v01_dtinsc_dia,@$v01_dtinsc_mes,@$v01_dtinsc_ano,true,'text',($existeumacda == true?3:$db_opcao),"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv01_exerc?>">
       <?=@$Lv01_exerc?>
    </td>
    <td> 
    <?
    if(!isset($v01_exerc) && $db_opcao==1){
        $v01_exerc=db_getsession("DB_anousu");
      }
    db_input('v01_exerc',10,$Iv01_exerc,true,'text',($existeumacda == true?3:$db_opcao),"")
    ?>
    </td>
  </tr>
  
  
  
				<tr>
					<td nowrap title="Processos registrado no sistema?">
						<strong>Processo do Sistema:</strong>
					</td>
					<td nowrap>
						<?
						  $lProcessoSistema = true;
							db_select('lProcessoSistema', array(true=>'SIM', false=>'N�O'), true, $db_opcao, "onchange='js_processoSistema()' style='width: 95px'")
						?>
					</td>
				</tr>

				<tr id="processoSistema">
					<td nowrap title="<?=@$Tp58_codproc?>">
					  <strong>
						<?
							db_ancora('Processo:', 'js_pesquisaProcesso(true)', $db_opcao);
						?>
					  </strong>
					</td>
					<td nowrap>
						<? 
						db_input('v01_processo', 10, false, true, 'text', $db_opcao, 'onchange="js_pesquisaProcesso(false)"') ;
	
						db_input('p58_requer', 60, false, true, 'text', 3);
						?>
					</td>
				</tr>

				<tr id="processoExterno1" style="display: none;">
					<td nowrap title="N�mero do processo externo">
						<strong>Processo:</strong>
					</td>
					<td nowrap>
						<? 
						db_input('v01_processoExterno', 10, "", true, 'text', $db_opcao, null, null, null, "background-color: rgb(230, 228, 241);") ;
						?>
					</td>
				</tr>

				<tr id="processoExterno2" style="display: none;">
					<td nowrap title="N�mero do processo externo">
						<strong>
  						Titular do Processo:
						</strong>
					</td>
					<td nowrap>
					<? 
						db_input('v01_titular', 74, 'false', true, 'text', $db_opcao) ;
					?>
					</td>
				</tr>

				<tr id="processoExterno3" style="display: none;">
					<td nowrap title="N�mero do processo externo">
					  <strong>
					    Data do Processo:
					  </strong>
					</td>
					<td nowrap>
						<? 
						db_inputdata('v01_dtprocesso', @$v01_dtprocesso_dia, @$v01_dtprocesso_mes, @$v01_dtprocesso_ano, true, 'text', $db_opcao);
						?>
					</td>
				</tr>  
     
  
  
  <tr>
    <td colspan="2">
   <fieldset style="margin-top: 20px;">
     <Legend>
       <strong>
         <font color="blue">C�lculo do valor total</font>
       </strong>
     </Legend>   
   <table>
     <tr>
      <td nowrap title="<?=@$Tv01_dtoper?>">
       <?=@$Lv01_dtoper?>
      </td>
      <td> 
        <?
        if(empty($v01_dtoper_dia) && $db_opcao==1){
          $v01_dtoper_dia=$dia;
          $v01_dtoper_mes=$mes;
          $v01_dtoper_ano=$ano;
        }
        db_inputdata('v01_dtoper',@$v01_dtoper_dia,@$v01_dtoper_mes,@$v01_dtoper_ano,true,'text',($existeumacda == true?3:$db_opcao),"");
        ?>
     </td>
     </tr>
     <tr>
        <td nowrap title="<?=@$Tv01_dtvenc?>">
         <?=@$Lv01_dtvenc?>
        </td>
        <td> 
          <?
          if(empty($v01_dtvenc_dia) && $db_opcao==1){
            $data=split("-",verifica_data($dia,$mes,$ano));
            $v01_dtvenc_dia=$dia; 
            $v01_dtvenc_mes=$mes; 
            $v01_dtvenc_ano=$ano; 
          }  
          db_inputdata('v01_dtvenc',@$v01_dtvenc_dia,@$v01_dtvenc_mes,@$v01_dtvenc_ano,true,'text',($existeumacda == true?3:$db_opcao),"")
          ?>
       </td>
     </tr>
     <tr>
       <td nowrap title="<?=@$Tv01_proced?>">
         <?
          db_ancora(@$Lv01_proced,"js_pesquisav01_proced(true);",($existeumacda == true?3:$db_opcao));
         ?>
       </td>
       <td> 
          <?
            db_input("v01_proced", 10, false, true, 'text', 1, "onchange='js_selecionaOpcao(this.value); js_pesquisav01_proced(false)'");
            db_input("v03_descr", 60, false, true, 'text', 3, "");
            if(isset($v01_proced)){
            	$proced=$v01_proced;
            }
          ?>
       
       
       </td>
     </tr>
     <tr>
       <td nowrap title="Tipo de debitos">
			   <b>Tipo de debito : </b>
       </td>
       <td> 
				<?

					$sqlTipo    = " select k00_tipo  as tipodebito, ";
					$sqlTipo   .= "        k00_descr as descricao   ";
					$sqlTipo   .= "	  from arretipo        ";
					$sqlTipo   .= "  where k03_tipo   = 5  ";
					$sqlTipo   .= "    and k00_instit = ".db_getsession('DB_instit'); 
					$rsTipo     = pg_query($sqlTipo);
					$iTipo      = pg_numrows($rsTipo);
					$arrTipo    = array();
					$arrTipo[0] = "Selecione";

					for($w=0; $w < $iTipo; $w++) {
						db_fieldsmemory($rsTipo,$w);
						$arrTipo[$tipodebito]= $descricao; 
					}
					
					// select 
					db_select("k00_tipo",$arrTipo,true,($existeumacda == true?3:$db_opcao));
				?>
       </td>
     </tr>
     <tr>
       <td nowrap title="<?=@$Tv01_vlrhis?>">
       <?=@$Lv01_vlrhis?>
       </td>
       <td> 
          <?
          db_input('v01_vlrhis',15,$Iv01_vlrhis,true,'text',($existeumacda == true?3:$db_opcao),"")
          ?>
       </td>
     </tr>
     <tr>
       <td nowrap title="<?=@$Tv01_valor?>">
         <?=@$Lv01_valor?>
       </td>
       <td> 
          <?
            if(isset($subtes) && $subtes=="ok" && !isset($chavepesquisa)){
              
              $oper     = $v01_dtoper_ano."-".$v01_dtoper_mes."-".$v01_dtoper_dia;
              $venc     = $v01_dtvenc_ano."-".$v01_dtvenc_mes."-".$v01_dtvenc_dia;
              $sql      = $clproced->sql_query($proced,"tabrecjm.k02_corr, proced.v03_receit");
              $result03 = $clproced->sql_record(($sql));
              db_fieldsmemory($result03,0); 
                $sql      = "select fc_corre($v03_receit,'$oper',$v01_vlrhis,'".date('Y-m-d',db_getsession("DB_datausu"))."',".db_getsession("DB_anousu").",'$venc')";
                $result08 = db_query($sql);
                db_fieldsmemory($result08, 0);
                if ($fc_corre == "-1") {
                  $xxx = "ok"; 
                } else {
                  $v01_valor = $fc_corre;
                }
            }
          db_input('v01_valor',15,$Iv01_valor,true,'text',($existeumacda == true?3:$db_opcao),"");
          ?>
        <input type="button" name="calcula" onclick="js_sub()" value="Calcular" <?=($db_botao==false?"disabled":"")?> <?=($db_opcao==22 || $db_opcao==33 || $db_opcao==3 || $existeumacda == true?"disabled":"")?> >
      </td>
     </tr>
   </table>  
   </fieldset>
   </td>
  </tr> 
  
  <tr>
    <td nowrap title="<?=@$Tv01_numpar?>">
       <?=@$Lv01_numpar?>
    </td>
    <td> 
      <?
      if(empty($v01_numpar) && $db_opcao==1){
        $v01_numpar=1;
      }
      db_input('v01_numpar',10,$Iv01_numpar,true,'text',($existeumacda == true?3:$db_opcao),"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv01_livro?>">
       <?=@$Lv01_livro?>
    </td>
    <td> 
      <?
      db_input('v01_livro',10,$Iv01_livro,true,'text',($existeumacda == true?3:$db_opcao),"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv01_folha?>">
       <?=@$Lv01_folha?>
    </td>
    <td> 
      <?
      db_input('v01_folha',10,$Iv01_folha,true,'text',($existeumacda == true?3:$db_opcao),"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv01_obs?>">
       <?=@$Lv01_obs?>
    </td>
    <td> 
      <?
      db_textarea('v01_obs', 5, 75, $Iv01_obs, true, 'text', ($existeumacda == true?1:$db_opcao),"")
      ?>
    </td>
  </tr>
  </table>
    
    
</fieldset>    
<br>
<center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_valida();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <?
      if($db_opcao==1){
    ?>
       <input name="voltar" type="button" id="voltar" value="Voltar" onclick="js_volta();" >
    <?
      }
    ?>  
</center>      


</form>


<script>

/*
 * FUNCOES DE PESQUISA
 */

function js_pesquisaProcesso(lMostra) {

  if (lMostra) {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?funcao_js=parent.js_mostraProcesso|p58_codproc|z01_nome','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?pesquisa_chave='+document.form1.v01_processo.value+'&funcao_js=parent.js_mostraProcessoHidden','Pesquisa',false);
  }
   
}
function js_mostraProcesso(iCodProcesso, sRequerente) {

  //alert('iCodProcesso -> ' + iCodProcesso + '  sRequerente -> ' + sRequerente);
  document.form1.v01_processo.value = iCodProcesso;
  document.form1.p58_requer.value  = sRequerente;
  db_iframe_matric.hide();
  
}

function js_mostraProcessoHidden(iCodProcesso, sNome, lErro) {

  //alert('iCodProcesso -> ' + iCodProcesso + '  sNome -> ' + sNome + "  erro -> " + lErro);
  if(lErro == true) {
    document.form1.v01_processo.value = "";
    document.form1.p58_requer.value  = sNome;
  } else {
    document.form1.p58_requer.value  = sNome;
  }

}    

    /*
      funcao que trata se o processo � externo ou interno
    */
    

function js_processoSistema() {

  var lProcessoSistema = $F('lProcessoSistema');
  
	if (lProcessoSistema == 1) {
		
		document.getElementById('processoExterno1').style.display = 'none';
		document.getElementById('processoExterno2').style.display = 'none';
		document.getElementById('processoExterno3').style.display = 'none';
		document.getElementById('processoSistema').style.display  = '';
		$('v01_processo').value = "";
		$('p58_requer').value = "";
	}	else {
		
		document.getElementById('processoExterno1').style.display = '';
		document.getElementById('processoExterno2').style.display = '';
		document.getElementById('processoExterno3').style.display = '';
		document.getElementById('processoSistema').style.display  = 'none';

		$('v01_processo').value = "";
		$('v01_processoExterno').value = "";
		$('v01_titular').value = "";
		$('v01_dtprocesso').value = "";
	}
		
}
    
    

function js_selecionaOpcao(valor) {

  js_divCarregando("Aguarde, buscando registros","msgBox");
   
  strJson = '{"option":"'+valor+'"}';

  var url     = 'div4_buscatipoRPC.php';
  var oAjax   = new Ajax.Request( 
                                  url, 
							        {
                                      method: 'post', 
                                      parameters: 'json='+strJson, 
                                      onComplete: js_retornoAjax
                                    }
                                 );
}

function js_retornoAjax(oAjax) {

	var obj = eval("(" + oAjax.responseText + ")");
	
	if (obj.option != null) {

    document.getElementById("k00_tipo").value = obj.option;
  }
	
	if (obj.option == "null") {
	 document.getElementById("k00_tipo").value = '0';
	}
	
	js_removeObj("msgBox");

}


function js_pesquisav01_proced(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_proced','func_proced.php?funcao_js=parent.js_mostraproced1|v03_codigo|v03_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_proced','func_proced.php?pesquisa_chave='+document.form1.v01_proced.value+'&funcao_js=parent.js_mostraproced','Pesquisa',false);
  }
}
function js_mostraproced(chave,erro){

  document.form1.v03_descr.value = chave; 
  if(erro==true){ 
    document.form1.v01_proced.focus(); 
    document.form1.v01_proced.value = ''; 
  }
}
function js_mostraproced1(chave1,chave2){

  document.form1.v01_proced.value = chave1;
  document.form1.v03_descr.value = chave2;
  db_iframe_proced.hide();
}


function js_valida(){
	
  var proced   = document.form1.v01_proced.value;
  var tpdebito = document.form1.k00_tipo.value;

	if (proced == '0') {
		alert('Selecione a proced�ncia!');
		return false;
	}

	if ( tpdebito == '0') {
		alert('Selecione o tipo de debito!');
		return false;
	}
	 
	return true;		
}

function js_volta(){
  location.href="div1_divida001.php";
}

function js_pesquisav01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.v01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.v01_numcgm.focus(); 
    document.form1.v01_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.v01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}




function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_divida','func_divida.php?funcao_js=parent.js_preenchepesquisa|v01_coddiv','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_divida.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
<?
if(isset($xxx) && $xxx=="ok" && empty($incluir) && empty($alterar) && empty($excluir)){
  echo "
        function js_xxx(){
	  document.form1.v01_valor.value='';
	  document.form1.v01_valor.focus();
         alert('N�o h� dados suficientes para corrigir o valor.\\n\\n Informe o valor corrigido total.');
	}
	js_xxx();
  ";
}


?>
</script>
<?php 
if ($db_opcao == 1 and (isset($incluir) || isset($alterar) || isset($excluir))) {
  
  echo "<script>";
  echo "  $('v01_proced').value = ''; ";
  echo "  $('v03_descr').value = ''; ";
  echo "</script>";
}

?>