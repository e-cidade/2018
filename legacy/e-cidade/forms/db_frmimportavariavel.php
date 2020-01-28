<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo = new rotulocampo;
$clrotulo = new rotulocampo;
$clrotulo->label("q07_inscr");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");

	$lMostraProcedTipo = false;

	if (isset($q07_inscr)&&$q07_inscr != ""){
		$tab   = "arreinscr";
		$campo = "k00_inscr";
		$valor = $q07_inscr;
	}else if(isset($z01_numcgm) && $z01_numcgm != ""){
		$tab   = "arrenumcgm";
		$campo = "k00_numcgm";
		$valor = $z01_numcgm;
	}

	if (isset($q07_inscr) && $q07_inscr != "" || isset($z01_numcgm) && $z01_numcgm != "" ) {
		
		$sql  = " select distinct																					    \n";
		$sql .= "				 arrecad.k00_dtoper,																    \n";
		$sql .= "				 k00_dtvenc,																		    \n";
		$sql .= "				 issvar.q05_numpre,																	    \n";
		$sql .= "				 issvar.q05_numpar,																	    \n";
		$sql .= "			 	 issvar.q05_ano,																	    \n";
		$sql .= "				 issvar.q05_mes,																	    \n";
		$sql .= "				 issvar.q05_codigo,																	    \n";
		$sql .= "        case																						    \n";
		$sql .= "          when issvarlev.q18_codigo is not null													    \n";
		$sql .= "            then case																				    \n";
		$sql .= "                   when y60_espontaneo is false													    \n";
		$sql .= "                     then ( select v03_codigo  													    \n";
		$sql .= "                              from proced															    \n";
		$sql .= "                                   inner join parfiscal on parfiscal.y32_proced = proced.v03_codigo    \n";
		$sql .= "                            limit 1)																	\n";
		$sql .= "                     else ( select v03_codigo															\n";
		$sql .= "                              from proced																\n";
		$sql .= "                                   inner join parfiscal on parfiscal.y32_procedexp = proced.v03_codigo \n";
		$sql .= "                            limit 1)																	\n";
		$sql .= "                  end																					\n";
		$sql .= "            else ( select v03_codigo																	\n";
		$sql .= "                     from proced																		\n";
		$sql .= "                          inner join cissqn on cissqn.q04_proced = proced.v03_codigo					\n";
		$sql .= "                                           and q04_anousu 				= ".db_getsession('DB_anousu')."\n";
		$sql .= "                     limit 1)																			\n";
		$sql .= "        end as v03_codigo,																				\n";
		$sql .= "        case																							\n";
		$sql .= "          when issvarlev.q18_codigo is not null														\n";
		$sql .= "            then case																					\n";
		$sql .= "                   when y60_espontaneo is false														\n";
		$sql .= "                     then ( select v03_descr															\n";
		$sql .= "                              from proced																\n";
		$sql .= "                                   inner join parfiscal on parfiscal.y32_proced = proced.v03_codigo    \n";
		$sql .= "                            limit 1)																	\n";
		$sql .= "                     else ( select v03_descr															\n";
		$sql .= "                              from proced																\n";
		$sql .= "                                   inner join parfiscal on parfiscal.y32_procedexp = proced.v03_codigo \n";
		$sql .= "                            limit 1)																	\n";
		$sql .= "                  end																					\n";
		$sql .= "            else ( select v03_descr																	\n";
		$sql .= "                     from proced																		\n";
		$sql .= "                          inner join cissqn on cissqn.q04_proced = proced.v03_codigo					\n";
		$sql .= "                                           and q04_anousu 				= ".db_getsession('DB_anousu')."\n";
		$sql .= "                     limit 1)																			\n";
		$sql .= "        end as v03_descr,																				\n";
		$sql .= "				 case																					\n";
		$sql .= "	         when arrecad.k00_valor > 0																	\n";
		$sql .= "		         then	arrecad.k00_valor																\n";
		$sql .= "					 else																				\n";
		$sql .= "			 			 issvar.q05_vlrinf																\n";
		$sql .= "				 end as k00_valor																		\n";
		$sql .= "		from {$tab}																						\n";
		$sql .= "				 inner join arrecad		   on {$tab}.k00_numpre		  = arrecad.k00_numpre				\n";
		$sql .= "				 inner join arreinstit   on arreinstit.k00_numpre = arrecad.k00_numpre					\n";
		$sql .= "				 										    and arreinstit.k00_instit = ".db_getsession('DB_instit')."\n";
		$sql .= "				 inner join issvar			 on issvar.q05_numpre		  = arrecad.k00_numpre			\n";
		$sql .= "				 										    and issvar.q05_numpar		  = arrecad.k00_numpar\n";
		$sql .= "				 left  join issvarlev    on issvarlev.q18_codigo	= issvar.q05_codigo					\n";
        $sql .= "				 left  join levanta      on levanta.y60_codlev    = issvarlev.q18_codlev				\n";
		$sql .= "				 left  join issvardiv    on issvar.q05_codigo		  = issvardiv.q19_issvar			\n";
		$sql .= "				 left  join divida			 on q05_numpre					  = v01_numpre				\n";
		$sql .= "															  and q05_numpar					  = v01_numpar\n";
		$sql .= "															  and v01_instit					  = ".db_getsession('DB_instit')."\n";
		$sql .= "	where {$tab}.{$campo} = {$valor}																	\n";
		$sql .= "	  and v01_coddiv is null																			\n";
		$sql .= "		and q19_issvar is null																			\n";
		$sql .= "		and ( issvar.q05_vlrinf > 0 or arrecad.k00_valor > 0 )											\n";
		$sql .= "   order by issvar.q05_numpre,issvar.q05_numpar";

		//echo($sql);

		$cliframe_seleciona->sql = $sql;
    
		$rsIssvar 		  = db_query($sql) or die($sql);
		$iNumRowsIssvar = pg_num_rows($rsIssvar);	
			
		if ($iNumRowsIssvar > 0) {
			$oIssvar           = db_utils::fieldsMemory($rsIssvar,0);
			$sSqlIssVar        = $clprocedarretipo->sql_query_file(null,"v06_arretipo",null,"v06_proced = {$oIssvar->v03_codigo}");
			$rsProcedArretipo  = $clprocedarretipo->sql_record($sSqlIssVar);
			if ($clprocedarretipo->numrows > 0) {
				$oProcedArretipo = db_utils::fieldsMemory($rsProcedArretipo,0);
				$k00_tipo = $oProcedArretipo->v06_arretipo;
			}
			$lMostraProcedTipo = true;
		}
	
	}

?>
<script>

function js_verifica() {
  
	inscr = new Number(document.form1.q07_inscr.value);
  cgm   = new Number(document.form1.z01_numcgm.value);
  
	if(inscr=="" || inscr=='0'|| isNaN(inscr)==true){
  	if(cgm=="" || cgm=='0'|| isNaN(cgm)==true){
      alert('Verifique a inscrição');
      return false;
  	}
  }
  if(inscr!=document.form1.inscricao.value){
     return false;
  }
  obj = atividades.document.getElementsByTagName("INPUT");
  var marcado = false;
  for(i = 0; i < obj.length; i++){
     if(obj[i].type == 'checkbox'){
       if(obj[i].checked == true){
          id = obj[i].id.substr(6);     
          marcado = true;
       }
     }
  }
  if(!marcado){
    alert('Selecione uma registro!');
    return false;
  }
  return  js_gera_chaves();
}

</script>

<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<form name="form1" method="post" action="">
<center>

<fieldset style="margin-top: 50px;">
<legend>
<strong>
  Importação de ISS Variável
</strong>
</legend>
<table border="0">
  <tr>
    <td align="center"> 
			<fieldset>
				<table border="0">
					<tr>   
						<td title="<?=$Tq07_inscr?>" >
							<?
								db_ancora($Lq07_inscr,' js_inscr(true); ',1);
							?>
						</td>    
						<td title="<?=$Tq07_inscr?>" colspan="4">
							<?
							  db_input('q07_inscr',10,$Iq07_inscr,true,'text',1,"onchange='js_inscr(false)'");
							  isset($q07_inscr)?$inscricao=$q07_inscr:"";
							  db_input('inscricao',10,$Iq07_inscr,true,'hidden',1);
							  db_input('z01_nome',60,0,true,'text',3);
							?>
						</td>
					</tr>
					<tr>   
						<td>
							<?
							  db_ancora($Lz01_numcgm,' js_cgm(true); ',1);
							?>
						</td>
						<td> 
							<?
							 db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',1,"onchange='js_cgm(false)'");
							 db_input('z01_nome',60,0,true,'text',3,"","z01_nomecgm");
							?>
						</td>
					</tr>
					<tr id="idTipoDebito" style="display:none;">
						<td>
							<b>Tipo de Débito:</b>
						</td>
						<td>
							<?
							  $sWhereAretipo = "k03_tipo = 5 and k00_instit = ".db_getsession('DB_instit');
							  $sSqlArretipo  = $clarretipo->sql_query_file(null, "k00_tipo, k00_descr", "k00_descr", $sWhereAretipo);
								$rsArretipo    = $clarretipo->sql_record($sSqlArretipo);
								db_selectrecord('k00_tipo', $rsArretipo, true, $db_opcao);
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
							db_select('lProcessoSistema', array(true=>'SIM', false=>'NÃO'), true, 1, "onchange='js_processoSistema()' style='width: 95px'")
						?>
					</td>
				</tr>

				<tr id="processoSistema">
					<td nowrap title="<?=@$Tp58_codproc?>">
					  <strong>
						<?
							db_ancora('Processo:', 'js_pesquisaProcesso(true)', 1);
						?>
					  </strong>
					</td>
					<td nowrap>
						<? 
						  db_input('v01_processo', 10, false, true, 'text', 1, 'onchange="js_pesquisaProcesso(false)"') ;
						  db_input('p58_requer', 60, false, true, 'text', 3);
						?>
					</td>
				</tr>

				<tr id="processoExterno1" style="display: none;">
					<td nowrap title="Número do processo externo">
						<strong>Processo:</strong>
					</td>
					<td nowrap>
						<? 
						  db_input('v01_processoExterno', 10, "", true, 'text', 1, null, null, null, "background-color: rgb(230, 228, 241);") ;
						?>
					</td>
				</tr>

				<tr id="processoExterno2" style="display: none;">
					<td nowrap title="Número do processo externo">
						<strong>
  						Titular do Processo:
						</strong>
					</td>
					<td nowrap>
					<? 
						db_input('v01_titular', 74, 'false', true, 'text', 1) ;
					?>
					</td>
				</tr>

				<tr id="processoExterno3" style="display: none;">
					<td nowrap title="Número do processo externo">
					  <strong>
					    Data do Processo:
					  </strong>
					</td>
					<td nowrap>
						<? 
						  db_inputdata('v01_dtprocesso', @$v01_dtprocesso_dia, @$v01_dtprocesso_mes, @$v01_dtprocesso_ano, true, 'text', 1);
						?>
					</td>
				</tr>					
					
				</table>
			</fieldset>
			<input name="lancar" style="margin-top: 10px;" type="submit" onclick="return js_verifica();" id="db_opcao" value="Lançar" <?=($db_botao==false?"disabled":"")?> >
		</td>
	</tr>
<tr>
<td>  
  <tr>   
    <td align="center" colspan="2"> 
		  <?
				
				$cliframe_seleciona->legenda 		 = "ISSVAR";
				$cliframe_seleciona->campos			 = "q05_codigo,q05_numpre,q05_numpar,q05_mes,v03_descr,q05_ano";
				$cliframe_seleciona->textocabec		 = "darkblue";
				$cliframe_seleciona->textocorpo		 = "black";
				$cliframe_seleciona->fundocabec		 = "#aacccc";
				$cliframe_seleciona->fundocorpo		 = "#ccddcc";
				$cliframe_seleciona->iframe_height   = "250";
				$cliframe_seleciona->iframe_width    = "400";
				$cliframe_seleciona->iframe_nome     = "atividades";
				$cliframe_seleciona->chaves			 = "q05_numpre,q05_numpar,q05_ano,q05_codigo,q05_mes";
				$cliframe_seleciona->iframe_seleciona($db_opcao);    

			?>
    </td>
  </tr>
  </table>
  
</fieldset>
  
  </center>
</form>
<script>

function js_inscr(mostra) {
  
  var inscr = document.form1.q07_inscr.value;
  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
  } else {
    
    if (inscr != "") {
      js_OpenJanelaIframe('top.corpo','db_iframe','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
    } else {
      
      document.form1.z01_nome.value = "";
      document.form1.submit();  
    }
  }
}

function js_cgm(mostra) {
  
  var cgm = document.form1.z01_numcgm.value;
  if (mostra == true) {
    js_OpenJanelaIframe('', 'db_iframe2', 'func_nome.php?funcao_js=parent.js_mostracgm|0|1', 'Pesquisa', true);
  } else {
    js_OpenJanelaIframe('', 'db_iframe2', 'func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1', 'Pesquisa', false);
  }
}
function js_mostracgm(chave1, chave2) {
  
  document.form1.z01_numcgm.value  = chave1;
  document.form1.z01_nomecgm.value = chave2;
  if (document.form1.q07_inscr.value == "") {
    document.form1.submit();
  }
  db_iframe2.hide();
}

function js_mostracgm1(erro, chave) {
  
  document.form1.z01_nomecgm.value = chave; 
  if (erro == true) {
     
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  } else {
    
  	if (document.form1.q07_inscr.value == "") {
  	  document.form1.submit();
  	}
  }
}

function js_mostrainscr(chave1, chave2) {
  
  document.form1.q07_inscr.value = chave1;
  document.form1.z01_nome.value  = chave2;
  atividades.location.href = "iss1_tabativbaixaiframe.php?q07_inscr="+chave1+"&z01_nome="+chave2;
  document.form1.submit(); 
  db_iframe.hide();
}

function js_mostrainscr1(chave,erro) {
  
  document.form1.z01_nome.value = chave; 
  if (erro == true) {
     
    document.form1.q07_inscr.focus(); 
    document.form1.q07_inscr.value = ''; 
  } else {
    document.form1.submit();
  }
}

<?
  if ($lMostraProcedTipo) {
    
    echo "document.form1.lancar.disabled = false;";
	  echo "document.getElementById('idTipoDebito').style.display = '';";
	} else {
    echo "document.form1.lancar.disabled = true;";
  }	
?>	

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

  document.form1.v01_processo.value = iCodProcesso;
  document.form1.p58_requer.value  = sRequerente;
  db_iframe_matric.hide();
  
}

function js_mostraProcessoHidden(iCodProcesso, sNome, lErro) {

  if(lErro == true) {
    document.form1.v01_processo.value = "";
    document.form1.p58_requer.value  = sNome;
  } else {
    document.form1.p58_requer.value  = sNome;
  }

}    
/*
  funcao que trata se o processo é externo ou interno
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
  }

}
</script>