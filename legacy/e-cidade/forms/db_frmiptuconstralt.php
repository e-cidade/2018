<?php
/**
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
?>
<script>

function js_verificaDataInclusao(){

  var sDataInclusao = document.form1.j39_dtlan.value;

  if (sDataInclusao == '' || sDataInclusao == null) {
    alert("Data de inclusão deve ser preenchida.");
    document.form1.j39_dtlan.focus();
    return false;
  }

}

  function js_verificaid(valor){

    num=(document.form1.selid.options.length)-1;
    for(i=1;i<=num;i++){

      selid=document.form1.selid.options[i].value;
      if(valor==selid){

        alert("Construção já cadastrada!");
        document.form1.j39_idcons.value="";
        document.form1.j39_idcons.focus();
        return false;
        break;
      }
    }

    if($('nomebo').name=="alterar"){

      if(document.form1.testaprinc.value=="t" && document.form1.j39_idprinc.value =="f" ){
        alert("Não será possível alterar esta construção para secundária sem antes selecionar outra como principal.");
        return false;
      }
    }

    if ( empty(document.form1.j39_area.value) ) {

      alert("A Área m2 do lote não foi informada!");
      return false;
    }
 }

<?php
  if(isset($j39_matric)) {
    ?>

    function js_trocaid(valor){
      id_setor=document.form1.id_setor2.value;
      id_quadra=document.form1.id_quadra2.value;
      location.href="cad1_iptuconstralt.php?id_setor2="+id_setor+"&id_quadra2="+id_quadra+"&j39_matric="+document.form1.j39_matric.value+"&j39_idcons="+valor+"&z01_nome="+document.form1.z01_nome.value;
    }
    <?php
  }

  if (isset($j39_idcons) && $j39_idcons!="") {

    ?>
    function js_demo(idcons){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptuconstr','db_iframe_demo','cad1_iptuconstrdemo001.php?pesq=si&j39_matric='+document.form1.j39_matric.value+'&z01_nome='+document.form1.z01_nome.value+'&j39_idcons='+idcons,'DEMOLIÇÕES PARCIAIS',true,0);
    }

    function js_fechar_demo(){
      db_iframe_demo.hide();
    }

    function js_habite(idcons) {

      js_OpenJanelaIframe(
        'CurrentWindow.corpo.iframe_iptuconstr',
        'db_iframe_habite',
        'cad1_iptuconstrhabite001.php?j131_matric='+document.form1.j39_matric.value+'&z01_nome='+encodeURIComponent($F('z01_nome').urlEncode())+'&j131_idcons='+idcons,
        'CADASTRO DE HABITE-SE',
        true,
        0
      );
    }

    function js_fechar_habite(){
      db_iframe_habite.hide();
    }

  <?php
  }
?>

function js_confir() {

  retor=confirm("Deseja realmente excluir esta construção?");

  if ( retor == true ) {
    return confirm("Tem certeza?");
  } else {
    return false;
  }
}
</script>

<br>

<form name="form1" method="post"
	onSubmit="return js_atualizaCaracteristica(); return js_verifica_campos_digitados(); "
	action="cad1_iptuconstralt.php">

	<input type="hidden" name="modulo_projetos" id="modulo_projetos"
		value="false"> <input type="hidden" name="j132_obrasconstr"
		id="j132_obrasconstr" value="<?php if(!empty($j132_obrasconstr)) { echo $j132_obrasconstr; } ?>"> <input
		type="hidden" name="id_setor2" id="id_setor2" value="<?=@$id_setor2?>">
	<input type="hidden" name="id_quadra2" id="id_quadra2"
		value="<?=@$id_quadra2?>">

	<fieldset>
		<legend>
			<b>Dados da Matricula</b>
		</legend>
		<table border="0" cellspacing="0" cellpadding="0" width=100%>
			<tr>
				<td width="208"><?=$Lj39_matric?>
				</td>
				<td><?
				db_input('j39_matric',10,0,true,'text',3,"onchange='js_matri(false)'");
				db_input('z01_nome',35,0,true,'text',3,"");
				?>
				</td>
			</tr>
		</table>
	</fieldset>
	<br>
	<fieldset>
		<legend>
			<b>Dados referentes a construção</b>
		</legend>

		<table border="0" width=100%>

			<td width="200px"><?=$Lj39_idcons?>*</td>
			<td><? db_input('j39_idcons',10,$Ij39_idcons,true,'text',$db_opcaoid,""); ?>
			</td>

			<td rowspan="13" valign="top" align="center" width=150><?
			if (isset($j39_matric)) {

        $num = 0;

			  if (!isset($incluir)) {

			    $result = $oDaoIPTUConstr->sql_record($oDaoIPTUConstr->sql_query_file($j39_matric,"","j39_idcons as jj39_idcons,j39_idprinc as jj39_idprinc","",""));

          if ( !empty($result) ) {
			      $num = pg_num_rows($result);
          }
        }

			  if (!empty($num)) {

			    echo "<fieldset>                                            ";
			    echo " <legend><b>Construções Cadastradas :</b></legend>    ";
			    echo "   <table border='0' cellpadding='0' cellspacing='0'> ";
			    echo "     <tr>                                             ";
			    echo "       <td align='center'>                            ";

			    echo "<select id='selid' name='selid' onchange='js_trocaid(this.value)'  size='".($num>5?6:($num+1))."' style=\"width:140px; height:50px\" >";
			    echo "<option value='nova' ".(!isset($j39_idcons)?"selected":"").">Nova</option>";

			    $idcons   = empty($j39_idcons) ? null : $j39_idcons;
			    $testasel = true;

			    for ($i=0; $i < $num; $i++) {

			      db_fieldsmemory($result,$i);

			      if ( $jj39_idcons != $idcons ) {
			        echo "<option  value='".$jj39_idcons."' ".($jj39_idcons==$idcons?"selected":"").">$jj39_idcons ".($jj39_idprinc=="t"?"Principal":"")." </option>";
			      }
			    }
			    echo "     </td>  ";
			    echo "   </tr>    ";
			    echo " </table>   ";
			    echo " </fieldset>";
			  }
			}

			$jj39_idcons="";

			if (isset($j39_idcons) && $j39_idcons!="") {

			  echo "<br>";
			  echo "<fieldset> ";
			  echo "  <legend><b>Dados Complementares</b></legend>";
			  echo "  <table>";
			  echo "    <tr> ";
			  echo "      <td height='5'></td>";
			  echo "    </tr> ";
			  echo "    <tr> ";
			  echo "      <td align=\"center\" > ";
			  echo "        <input type=\"button\" name=\"lanca\" value=\"Demolições Parciais\" onclick=\"js_demo('{$j39_idcons}');\" style=\"width:130px;\">";
			  echo "      </td> ";
			  echo "    </tr>  ";
			  echo "    <tr> ";
			  echo "      <td height='2'></td>";
			  echo "    </tr> ";
			  echo "    <tr> ";
			  echo "      <td align=\"center\" > ";
			  echo "        <input type=\"button\" name=\"lancaHabite\" value=\"Habite-se\" onclick=\"js_habite('{$j39_idcons}', '{$j39_matric}');\" style=\"width:130px;\">";
			  echo "      </td> ";
			  echo "    </tr>  ";
			  echo "  </table>";
			  echo "</fieldset>";

			} else {

			  echo "<fieldset> ";
			  echo "  <legend><b>Cadastros para Importação:</b></legend>";
			  echo "  <table>";
			  echo "    <tr> ";
			  echo "      <td align=\"center\" > ";
			  echo "        <input type=\"button\" name=\"buscaConstrucoes\" value=\"Construções\" onclick=\"js_listaConstrucoesCadastradas();\" style=\"width:130px;\">";
			  echo "      </td> ";
			  echo "    </tr>  ";
			  echo "      <td align=\"center\" > ";
			  echo "        <input type=\"button\" name=\"buscaObras\" value=\"Alvarás Liberados\" onclick=\"js_listaObrasAlvara();\" style=\"width:130px;\">";
			  echo "      </td> ";
			  echo "    </tr>  ";
			  echo "  </table>";
			  echo "</fieldset>";
			}
			?></td>
			</tr>
			<tr>
				<td><?=$Lj39_dtlan?>
				</td>
				<td><? db_inputdata('j39_dtlan',@$j39_dtlan_dia,@$j39_dtlan_mes,@$j39_dtlan_ano,true,'text', 1) ?>
				</td>
			</tr>
			<tr>
				<td><?=$Lj39_ano?>
				</td>
				<td><? db_input('j39_ano',10,$Ij39_ano,true,'text',1,""); ?>
				</td>
			</tr>
			<tr>
				<td><?=$Lj39_area?>
				</td>
				<td><? db_input('j39_area',10,4,true,'text',1,$sOnChangeArea); ?>
				</td>
			</tr>
			<tr>
				<td><?=$Lj39_areap?>
				</td>
				<td><? db_input('j39_areap',10,4,true,'text',1,""); ?>
				</td>
			</tr>
			<tr>
				<td><?=$Lj39_pavim?>
				</td>
				<td><? if ($db_opcao==1 && empty($j39_pavim)) {
				  $j39_pavim=1;
				}
				db_input('j39_pavim',10,$Ij39_pavim,true,'text',1,"");
				?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tj39_codigo?>"><? db_ancora(@$Lj39_codigo,"js_pesquisaj39_codigo(true);",$db_opcao); ?>
				</td>
				<td><?
				db_input('j39_codigo',10,$Ij39_codigo,true,'text',$db_opcao," onchange='js_pesquisaj39_codigo(false);'");
				db_input('j14_nome',34,$Ij14_nome,true,'text',3,'');
				?>

			</tr>
			<tr>
				<td><?=$Lj39_numero?>
				</td>
				<td><? db_input('j39_numero',10,$Ij39_numero,true,'text',1,""); ?>
				</td>
			</tr>
			<tr>
				<td><?=$Lj39_compl?>
				</td>
				<td><? db_input('j39_compl',48,$Ij39_compl,true,'text',1,""); ?>
				</td>
			</tr>
			<tr>
				<td><b><? db_ancora("Características","js_mostracaracteristica();",1); ?>
				</b>
				</td>
				<td><? db_input('caracteristica',15,1,true,'hidden',1,"") ?>

				<td>

			</tr>
			<tr>
				<td nowrap title="<?=@$Tj39_idaument?>"><?=@$Lj39_idaument?>
				</td>
				<td><? db_input('j39_idaument',10,$Ij39_idaument,true,'text',$db_opcao,"") ?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tj39_idprinc?>"><?=@$Lj39_idprinc?>
				</td>
				<td><? if (isset($j39_matric) && $num > 0) {
				  $x = array("f"=>"Construção Secundária","t"=>"Construção Principal");
				} else {
				  $x = array("t"=>"Construção Principal","f"=>"Construção Secundária");
				}
				db_select('j39_idprinc',$x,true,$db_opcao,"");
				?> <input type="hidden" name="testaprinc" value="<?=@$j39_idprinc?>">
				</td>
			</tr>
			<?
			$result_util = $oDaoCfIPTU->sql_record($oDaoCfIPTU->sql_query(db_getsession("DB_anousu")));
			if ($oDaoCfIPTU->numrows>0) {
			  db_fieldsmemory($result_util,0);

			  if ($j18_utilpontos==1) {

			    echo " <tr> ";
			    echo "   <td nowrap title=".@$Tj83_pontos."> ".@$Lj83_pontos." </td>";
			    echo "   <td> ";
			    db_input('j83_pontos',6,$Ij83_pontos,true,'text',$db_opcao,"");
			    echo "   </td> ";
			    echo " </tr>";
			  }
			}
			?>

			<tr>
				<td colspan="3" title="<?=$Tj39_obs ?>">
					<fieldset>
						<legend>
							<?=$Lj39_obs?>
						</legend>
						<? db_textarea('j39_obs', 5, 101, $Ij39_obs, true, 'text', $db_opcao); ?>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center"><br>

					<fieldset style="width: 750px">
						<legend>
							<b>Dados referentes a Demolição da Construção</b>
						</legend>

						<table border="0">
							<tr>
								<td width="210px" title="<?=@$Tj39_dtdemo?>"><?=@$Lj39_dtdemo?>
								</td>
								<td width="620px"><? db_inputdata('j39_dtdemo',@$j39_dtdemo_dia,@$j39_dtdemo_mes,@$j39_dtdemo_ano,true,'text',$db_opcao,"") ?>
								</td>
							</tr>
							<tr>
								<td title="Origem do Processo"><b>Processo do Sistema:</b>
								</td>
								<td><?
								$x = array("S"=>"Sim", "N"=>"Não");
								db_select("lProcesso", $x, true, $db_opcao, "onChange=js_montaCampoProcesso()");
								?>
								</td>
							</tr>
							<tr>
								<td id="Processo" name="Processo">

									<div id="ProcessoId1" style='display: none;'>
										<? db_ancora("<b>Processo :</b>","js_pesquisaProcesso(true)",$db_opcao); ?>
									</div>
									<div id="ProcessoId2" style='display: none;'>
										<strong>Processo :</strong>
									</div>

								</td>
								<td id="ProcessoValor" name="ProcessoValor">
									<div id="ProcessoCod1" style='display: none;'>
										<input id='j39_codprotdemo1' type="text"
											value="<?=@$j39_codprotdemo?>" size='10' maxlength='10'
											onchange="js_pesquisaProcesso(false);"
											onblur="js_ValidaMaiusculo(this,'f',event);"
											onkeyup="js_ValidaCampos(this,4,'Processo do Protocolo','f','f',event);"
								<?=($db_opcao==3)?" readonly style='background: #DEB887;' ":""?>>
										<input type="text" name="p58_requer" value="<?=@$p58_requer?>"
											size="35" readonly style="background: #DEB887;">
									</div>
									<div id="ProcessoCod2" style='display: none;'>
										<input id='j39_codprotdemo2' type="text"
											value="<?=@$j39_codprotdemo?>" size='10' maxlength='15'
											onblur="js_ValidaMaiusculo(this,'f',event);"
											onkeyup="js_ValidaCampos(this,4,'Processo do Protocolo','f','f',event);"
								<?=($db_opcao==3)?" readonly style='background: #DEB887;' ":""?>>
									</div>
								</td>
							</tr>
						</table>

					</fieldset>
				</td>
			</tr>
		</table>
	</fieldset>

	<br> <input id="nomebo" name="<?=($db_botao==1?"incluir":"alterar")?>"
		type="submit" value="<?=($db_botao==1?"Incluir":"Alterar")?>"
		<?=($testasel==true?"onclick=\"return js_verificaid(document.form1.j39_idcons.value)\"":"")?>>
	<?  if ($db_botao!=1) {
	  echo "<input  name='excluir' type=\"submit\" value='Excluir' onclick='return js_confir();'>";
	}
	?>

	<script>

var oGet = new Object();
var lParseGet = false;
if (window.location.search != "") {
  lParseGet = true;
  oGet  = js_urlToObject();
}
var sRPC  = "cad4_iptuconstr.RPC.php";

/**
 * Inicializa Caracteristicas
 */
var iMatriculaCaracteristica = $F('j39_matric');
var iIdConstr                = "0";

if ( oGet.hasOwnProperty('j39_idcons') == true && oGet.j39_idcons != 'nova') {
  iIdConstr = oGet.j39_idcons;
}

var oCaracteristicas = new DBViewCaracteristicasConstrucao('oCaracteristicas', iMatriculaCaracteristica, iIdConstr);

function js_atualizaCaracteristica() {
  $('caracteristica').value = "";
  $('caracteristica').value = oCaracteristicas.getSelecao().join("X");
}

function js_matri(mostra){
  var matri=document.form1.j39_matric.value;
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptuconstr','db_iframe','func_iptubase.php?funcao_js=parent.js_mostra|0|1','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptuconstr','db_iframe','func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostra1','Pesquisa',false,0);
  }
}
function js_mostra(chave1,chave2){
  document.form1.j39_matric.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_mostra1(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.j39_matric.focus();
    document.form1.j39_matric.value = '';
  }
}

function js_mostracaracteristica(){

  oCaracteristicas.show();
  caracteristica=document.form1.caracteristica.value;
}


function js_pesquisaj39_codigo(mostra){
var idsetor  = document.form1.id_setor2.value;
var idquadra = document.form1.id_quadra2.value;

  if(mostra==true){
     js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptuconstr','db_iframe',"func_ruasconstr.php?idsetor="+tagString(idsetor)+"&idquadra="+tagString(idquadra)+"&funcao_js=parent.js_mostraruas1|0|1",'Pesquisa',true,0);
  }else{
     js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptuconstr','db_iframe',"func_ruasconstr.php?idsetor="+tagString(idsetor)+"&idquadra="+tagString(idquadra)+"&pesquisa_chave="+document.form1.j39_codigo.value+"&funcao_js=parent.js_mostraruas",'Pesquisa',false,0);
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.j39_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave;
  if(erro==true){
    document.form1.j39_codigo.focus();
    document.form1.j39_codigo.value = '';
  }
}

function js_pesquisaProcesso(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_processo','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|z01_nome','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('','db_iframe_processo','func_protprocesso.php?pesquisa_chave='+document.form1.j39_codprotdemo.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
    }
  }

function js_mostraprotprocesso(chave,chave1,erro){
  document.form1.p58_requer.value = chave1;
  if(erro==true){
    document.form1.j39_codprotdemo.focus();
    document.form1.j39_codprotdemo.value = '';
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.j39_codprotdemo.value = chave1;
  document.form1.p58_requer.value      = chave2;
  db_iframe_processo.hide();
}


function js_montaCampoProcesso() {

  var lProcesso = document.form1.lProcesso.value;


  if (lProcesso == "S") {

    $("ProcessoId1").style.display  = '';
    $("ProcessoCod1").style.display = '';
    $("j39_codprotdemo1").setAttribute('name','j39_codprotdemo');

    $("ProcessoId2").style.display  = 'none';
    $("ProcessoCod2").style.display = 'none';
    $("j39_codprotdemo2").value     = '';
    $("j39_codprotdemo2").setAttribute('name','');

  }  else {

    $("ProcessoId1").style.display  = 'none';
    $("ProcessoCod1").style.display = 'none';
    $("j39_codprotdemo1").value     = '';
    document.form1.p58_requer.value = "";

    $("j39_codprotdemo1").setAttribute('name','');

    $("ProcessoId2").style.display  = '';
    $("ProcessoCod2").style.display = '';
    $("j39_codprotdemo2").setAttribute('name','j39_codprotdemo');

  }
}

/**
 * Coloca texto de ajuda no campo codigo da construção
 */
var oHint = new DBHint("oHint");
    oHint.setText("Caso este campo não seja preenchido, <BR> o código será gerado automaticamente")
    oHint.make( $('j39_idcons') );

var aFonteDadosFormulario = new Array();


  function js_listaConstrucoesCadastradas() {

    if (typeof(oWindowConstrucoes) == "object") {

      $("matricula").value = "";
      oGridConstrucoes.clearAll(true);

      js_showWindow(oWindowConstrucoes.divWindow);

    } else {

      var sConteudo  = " <center>                                                                                   ";
          sConteudo += "   <div id='headerConstrucoes'></div>                                                       ";
          sConteudo += "   <fieldset>                                                                               ";
          sConteudo += "     <table>                                                                                ";
          sConteudo += "      <tr>                                                                                  ";
          sConteudo += "        <td>                                                                                ";
          sConteudo += "          <a href='#' onclick='js_pesquisaMatriculaImportacao(true)'> <b>Matricula:</b> </a>";
          sConteudo += "        </td>                                                                               ";
          sConteudo += "        <td>                                                                                ";
          sConteudo += "          <input type='text' id='matricula' name='matricula' value='' size='5'>             ";
          sConteudo += "        </td>                                                                               ";
          sConteudo += "        <td>                                                                                ";
          sConteudo += "          <input type='button'                                                              ";
          sConteudo += "                   id='buscarConstrucoesMatricula'                                          ";
          sConteudo += "                 name='buscarConstrucoesMatricula'                                          ";
          sConteudo += "                value='Buscar Construções'                                                  ";
          sConteudo += "              onclick='js_pesquisaMatriculaImportacao(false)'>                              ";
          sConteudo += "        </td>                                                                               ";
          sConteudo += "      </tr>                                                                                 ";
          sConteudo += "     </table>                                                                               ";
          sConteudo += "     <div id='contentConstrucoes'></div>                                                    ";
          sConteudo += "   </fieldset>                                                                              ";
          sConteudo += "   <div id='footerConstrucoes'>                                                             ";
          sConteudo += "   </div>                                                                                   ";
          sConteudo += " </center>                                                                                  ";

      var sMsg      = "Informe a matricula da qual deseja buscar as construções, após selecione uma das construções da Matricula";

      oWindowConstrucoes = new windowAux("oWindowConstrucoes", "Importação de Construções", 800, 360);
      oWindowConstrucoes.setContent(sConteudo);
      oWindowConstrucoes.show(10, window.availWidth);

      $('contentConstrucoes').style.width = oWindowConstrucoes.getWidth() - 30;

      /**
       * Inicia MessageBoard
       */
      oMessage  = new DBMessageBoard('msgboard',
                                    'Construções Cadastradas',
                                    sMsg,
                                    $('headerConstrucoes'));
      oMessage.show();
      js_criaGridCaracateristicasConstrucoes();
    }
  }

  function js_showWindow(oElemento) {
    oElemento.style.display = '';
  }

  /**
   * Renderiza grid vazia
   */
  function js_criaGridCaracateristicasConstrucoes(){

    oGridConstrucoes =   new DBGrid("oGridConstrucoes");
    oGridConstrucoes.nameInstance = "oGridConstrucoes";
    oGridConstrucoes.sName        = "oGridConstrucoes";
    oGridConstrucoes.setCellWidth ( new Array("10%", "10%" ,"10%", "10%", "60%") );
    oGridConstrucoes.setHeader    ( new Array("Cód.", " Principal " ," Area m²", " Ano ", "Logradouro") );
    oGridConstrucoes.setCellAlign ( new Array("center", "left", "center", "center", "left" ));
    oGridConstrucoes.show         ( $("contentConstrucoes") );

  }

  function js_carregaDadosGridConstrucoes() {

    js_divCarregando('Carregando Construções...', 'msgBox');

    var oParam = new Object();

    oParam.sExec                   = "getConstrucoesMatricula";
		oParam.iMatriculaParaAlteracao = $F('j39_matric');
    oParam.iMatricula              = new Number( $F('matricula') );

    var oAjax          = new Ajax.Request(sRPC, {
        method         : 'post',
        parameters     : 'json=' + Object.toJSON(oParam),
        onComplete     : function(oAjax) {

          js_removeObj('msgBox');
          var oRetorno = eval("("+oAjax.responseText+")");

          if (oRetorno.iStatus== "2") {
            alert(oRetorno.sMessage.urlDecode());
          } else {

            oGridConstrucoes.clearAll(true);

            for (var iIndice = 0; iIndice < oRetorno.aConstrucoesMatricula.length; iIndice++) {

              var aCelulas         = new Array();
              var oDadosFormulario = new Object();
              var oDadosRetorno = oRetorno.aConstrucoesMatricula[iIndice];
              with (oRetorno.aConstrucoesMatricula[iIndice]) {

                aCelulas[0]                              = oRetorno.aConstrucoesMatricula[iIndice].iCodigoConstrucao;
                aCelulas[1]                              = (oRetorno.aConstrucoesMatricula[iIndice].lPrincipal=="t")?"SIM":"NAO";
                aCelulas[2]                              = oRetorno.aConstrucoesMatricula[iIndice].nAreaConstrucao;
                aCelulas[3]                              = oRetorno.aConstrucoesMatricula[iIndice].iAnoConstrucao;
                aCelulas[4]                              = oDadosRetorno.sNomeLogradouro + ",  " + oDadosRetorno.iNumeroLogradouro + " - " + oDadosRetorno.sComplementoLogradouro;

                /**
                 * Adiciona dados para serem completados caso o usuario selecione uma opção da grid
                 */
                oDadosFormulario.iCodigoConstrucao       = oRetorno.aConstrucoesMatricula[iIndice].iCodigoConstrucao;
                oDadosFormulario.iAnoConstrucao          = oRetorno.aConstrucoesMatricula[iIndice].iAnoConstrucao;
                oDadosFormulario.nAreaConstrucao         = oRetorno.aConstrucoesMatricula[iIndice].nAreaConstrucao;
                oDadosFormulario.nAreaPrivada            = oRetorno.aConstrucoesMatricula[iIndice].nAreaPrivada;
                oDadosFormulario.lMesmoLote              = oRetorno.aConstrucoesMatricula[iIndice].lMesmoLote;
                oDadosFormulario.iPavimentos             = oRetorno.aConstrucoesMatricula[iIndice].iPavimentos;
                oDadosFormulario.iCodigoLogradouro       = oRetorno.aConstrucoesMatricula[iIndice].iCodigoLogradouro;
                oDadosFormulario.iNumeroLogradouro       = oRetorno.aConstrucoesMatricula[iIndice].iNumeroLogradouro;
                oDadosFormulario.sComplementoLogradouro  = oRetorno.aConstrucoesMatricula[iIndice].sComplementoLogradouro.urlDecode();
                oDadosFormulario.iCodigoOrigemConstrucao = oRetorno.aConstrucoesMatricula[iIndice].iCodigoOrigemConstrucao;
                oDadosFormulario.sObservacaoConstrucao   = oRetorno.aConstrucoesMatricula[iIndice].sObservacaoConstrucao.urlDecode();
                oDadosFormulario.iMatricula              = $F('matricula');
              }

              aFonteDadosFormulario[iIndice]             = oDadosFormulario;
              oGridConstrucoes.addRow(aCelulas);

              oGridConstrucoes.aRows[iIndice].aCells[0].sStyle = " cursor: pointer; ";
              oGridConstrucoes.aRows[iIndice].aCells[1].sStyle = " cursor: pointer; ";
              oGridConstrucoes.aRows[iIndice].aCells[2].sStyle = " cursor: pointer; ";
              oGridConstrucoes.aRows[iIndice].aCells[3].sStyle = " cursor: pointer; ";
              oGridConstrucoes.aRows[iIndice].sEvents          = " onClick =\"js_preencheCampos(" + iIndice + ", 1);\" ";

            }

            oGridConstrucoes.renderRows();
          }
       }
    });
  }


  /**
   * Lista as obras com alvara
   */
    function js_listaObrasAlvara() {

      if (typeof(oWindowObras) == "object") {
        js_showWindow(oWindowObras.divWindow);
      } else {

        var sConteudo  = " <center>                                                                ";
            sConteudo += "   <div id='headerObras'></div>                                          ";
            sConteudo += "   <fieldset>                                                            ";
            sConteudo += "     <legend>                                                            ";
            sConteudo += "       <strong>Obras: </strong>                                          ";
            sConteudo += "     </legend>                                                           ";
            sConteudo += "     <div id='contentObras'></div>                                       ";
            sConteudo += "   </fieldset>                                                           ";
            sConteudo += "   <div id='footerObras'>                                                ";
            sConteudo += "   </div>                                                                ";
            sConteudo += " </center>                                                               ";

        var sMsg      = "Selecione uma das obras listadas abaixo para inseri-la no cadastro de construções";

        oWindowObras = new windowAux("oWindowObras", "Características da Constução", 650, 360);
        oWindowObras.setContent(sConteudo);
        oWindowObras.show(10, window.availWidth);

        $('contentObras').style.width = oWindowObras.getWidth() - 30;

        /**
         * Inicia MessageBoard
         */
        oMessage  = new DBMessageBoard('msgboard',
                                      'Obras com alvará',
                                      sMsg,
                                      $('headerObras'));
        oMessage.show();
        js_criaGridCaracateristicasObras();
      }
    }

  /**
   * Renderiza grid vazia
   */
  function js_criaGridCaracateristicasObras(){

    oGridObras              = new DBGrid("oGridObras");
    oGridObras.nameInstance = "oGridObras";
    oGridObras.sName        = "oGridObras";
    oGridObras.setHeader    ( new Array("Cod. Obra", "Descrição" ,"Alvara", "Cod. Construção") );
    oGridObras.setCellAlign ( new Array("center"   , "left"      , "center", "center" ));
    oGridObras.show         ( $("contentObras") );


    /**
     * Carrega dados para que sejam renderizados
     */

    var aDadosGrid = js_carregaDadosGridObras();
  }

  function js_carregaDadosGridObras() {

    js_divCarregando('Carregando obras...', 'msgBox');

    var oParam         = new Object();
    oParam.sExec       = "getObrasComAlvara";
    oParam.iMatricula  = new Number( $F('j39_matric') );
    var oAjax          = new Ajax.Request(sRPC, {
        method         : 'post',
        parameters     : 'json=' + Object.toJSON(oParam),
        onComplete     : function(oAjax) {

          js_removeObj('msgBox');
          var oRetorno = eval("("+oAjax.responseText+")");

          if (oRetorno.iStatus== "2") {
            alert(oRetorno.sMessage.urlDecode());
          } else {

            oGridObras.clearAll(true);

            for (var iIndice = 0; iIndice < oRetorno.aObrasAlvara.length; iIndice++) {

              var aCelulas         = new Array();
              var oDadosFormulario = new Object();

              with (oRetorno.aObrasAlvara[iIndice]) {

                aCelulas[0]                              = ob01_codobra;
                aCelulas[1]                              = ob01_nomeobra.urlDecode();
                aCelulas[2]                              = ob04_alvara;
                aCelulas[3]                              = ob08_codconstr;
                /**
                 * Adiciona dados para serem completados caso o usuario selecione uma opção da grid
                 */
                oDadosFormulario.iAnoConstrucao          = ano_alvara;
                oDadosFormulario.nAreaConstrucao         = ob08_area;
                oDadosFormulario.iPavimentos             = ob07_pavimentos;
                oDadosFormulario.iCodigoLogradouro       = ob07_lograd;
                oDadosFormulario.iNumeroLogradouro       = ob07_numero;
                oDadosFormulario.sComplementoLogradouro  = ob07_compl.urlDecode();
                oDadosFormulario.iCodigoConstrucao       = ob08_codconstr;
              }

              aFonteDadosFormulario[iIndice]             = oDadosFormulario;
              oGridObras.addRow(aCelulas);

              oGridObras.aRows[iIndice].aCells[0].sStyle = " cursor: pointer; ";
              oGridObras.aRows[iIndice].aCells[1].sStyle = " cursor: pointer; ";
              oGridObras.aRows[iIndice].aCells[2].sStyle = " cursor: pointer; ";
              oGridObras.aRows[iIndice].aCells[3].sStyle = " cursor: pointer; ";
              oGridObras.aRows[iIndice].sEvents          = " onClick =\"js_preencheCampos(" + iIndice + ", 2);\" ";

            }

            oGridObras.renderRows();
          }
       }
    });
  }

  function js_preencheCampos(iIndiceRegistro, iTipo) {

    var oDados = aFonteDadosFormulario[iIndiceRegistro];

    /**
     * Conta quantas obras existem desconsiderando a opção de nova obra
     * por isso e diminiudo 1 da constagem
     */
     if ($('selid')) {
       var iRegistros = new Number($('selid').length - 1);
     } else {
       var iRegistros = 0;
     }
    var sPrincipal = "t";
    if ( iRegistros > 0 ) {
      sPrincipal = "f";
    }

    $('j39_ano').setValue(oDados.iAnoConstrucao);
    $('j39_area').setValue(oDados.nAreaConstrucao);
    $('j39_pavim').setValue(oDados.iPavimentos);

		/**
		 * Se matriculas forem do mesmo lote, preenche campo logradouro do contrario limpa campo
		 */
		if ( oDados.lMesmoLote ) {
			$('j39_codigo').setValue(oDados.iCodigoLogradouro);
		} else {
			$('j39_codigo').setValue('');
		}

		/**
		 * Limpa os campos numero e complemento
		 */
		$('j39_numero').setValue('');
		$('j39_compl').setValue('');

    $('j39_idprinc').setValue(sPrincipal);
    if (iTipo == 2) {
      $('j132_obrasconstr').setValue(oDados.iCodigoConstrucao);
      $('modulo_projetos').setValue("true");

    } else {
      $('j132_obrasconstr').value = " ";
      $('modulo_projetos').setValue("false");

    }
    $('j39_codigo').onchange();
    $('j39_idcons').focus();

    $('j39_area').onchange = function (){
      js_validaArea(oDados.nAreaConstrucao);
    };
    js_buscaCaracteristicasConstrucao(oDados.iCodigoConstrucao, iTipo);

    if (iTipo == 1) {
      oWindowConstrucoes.hide();
      oCaracteristicas.importarDadosConsntrucao(oDados.iMatricula, oDados.iCodigoConstrucao);
    } else {
      oWindowObras.hide();
    }
  }


  function js_buscaCaracteristicasConstrucao(iConstrucao, iTipo) {

    js_divCarregando('Buscando características, aguarde...', 'msgBox');

    var oParam               = new Object();
    oParam.sExec             = "getCaracteristicasConstrucao";
    oParam.iTipoConstrucao   = iTipo;
    if(iTipo == 1) {
      oParam.iMatricula      = $F("matricula");
    }
    oParam.iCodigoConstrucao = iConstrucao;

    var oAjax        = new Ajax.Request(sRPC, {
      method         : 'post',
      parameters     : 'json=' + Object.toJSON(oParam),
      onComplete     : function(oAjax) {

        js_removeObj('msgBox');
        var oRetorno = eval("("+oAjax.responseText+")");

        if (oRetorno.iStatus== "2") {
          alert(oRetorno.sMessage.urlDecode());
        } else {
          $('caracteristica').setValue( oRetorno.aCaracteristicas.join("X") );
        }
      }
    });

  }

function js_pesquisaMatriculaImportacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_matricula','func_iptubase.php?funcao_js=parent.js_mostramatricula1|j01_matric','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_matricula','func_iptubase.php?pesquisa_chave='+$F("matricula")+'&funcao_js=parent.js_mostramatricula','Pesquisa',false);
  }

  $('Jandb_iframe_matricula').style.zIndex = "999999";
}

function js_mostramatricula(chave,erro){
  if(erro==true){
    alert("Matricula "+$F("matricula")+" não cadastrada!");
    $("matricula").value="";
  } else {
    js_carregaDadosGridConstrucoes();
  }
}

function js_mostramatricula1(chave1){
  $("matricula").value = chave1;
  js_carregaDadosGridConstrucoes();
  db_iframe_matricula.hide();
}


  function js_validaArea(nLimite) {

    var nArea = new Number( $F('j39_area').replace(/,/g,'.') );
    nLimite   = new Number( nLimite );

    if ( nArea > nLimite ) {

      $('j39_area').focus();
      alert("Área informada maior que a área permitida \nValor Máximo Permitido : " + nLimite + "m²");
      $('j39_area').setValue(nLimite);
    }
  }
</script>