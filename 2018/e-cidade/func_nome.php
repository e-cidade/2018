<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

/**
 * No js_OpenJanelaIframe passar o filtro
 * ex:forms/db_frmrhlota.php
 * $filtro=0 ->mostra cpf e cnpj
 * $filtro=1 ->mostra cpf
 * $filtro=2 ->mostra cnpj
 */
$filtro = 0;
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

if(!isset($pesquisar)) {
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
}

$clcgm		= new cl_cgm;
$clrotulo = new rotulocampo;

$clcgm->rotulo->label("z01_numcgm");
$clcgm->rotulo->label("z01_nome");
$clcgm->rotulo->label("z01_cgccpf");

$clrotulo->label("DBtxt30");
$clrotulo->label("DBtxt31");

if (!isset($funcao_js)) {
  $funcao_js = '';
  echo "<script>parent.document.getElementById('Jandb_iframe').style.display = 'none';</script>";
}

$funcao_jscgmalt = $funcao_js;

if (isset($script) && $script != "" && !is_bool($script)) {

  $vals = "";
  $vir  = "";
  $camp = split(",",$valores);

  for($f=0;$f<count($camp);$f++){
  	$vals .= $vir."'".$camp[$f]."'";
  	$vir   = ",";
  }

  echo "<script>";
  if (isset($alterou)) {
    echo "parent.document.form1.alterou.value = {$alterou};";
  }
  echo $script."(".$vals.")";
  echo "</script>";
  exit;
}

if (isset($testanome) && !isset($pesquisa_chave)) {

  $funmat			= split("\|",$funcao_js);
  $func_antes = $funmat[0];
  $valores		= "";
  $camp				= "";
  $vir			  = "";

	for($i=1;$i<count($funmat);$i++){

		if($funmat[$i] == "0")
       $funmat[$i] = "z01_numcgm";
    if($funmat[$i] == "1")
       $funmat[$i] = "z01_nome";

    $valores .= "|".$funmat[$i];
    $camp		 .= $vir.$funmat[$i];
    $vir			= ",";

	}

	$funmat[0] = "js_testanome";
  $funcao_js = $funmat[0]."|z01_numcgm|z01_ender|z01_cgccpf".$valores;

}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
  function js_close(){
    var nome = (window.CurrentWindow || parent.CurrentWindow).corpo.aux.nomeJanela;
    eval('parent.'+nome+'.hide();');
  }

	function js_submit_numcgm_buscanome(numcgm){
    document.form_busca_dados.numcgm_busca_dados.value = numcgm;
    document.form_busca_dados.submit();
  }

	<?
		if(isset($testanome) and $testanome==true and !isset($pesquisa_chave)){
	?>

	function js_testanome(z01_numcgm,ender,cgccpf,<?=$camp?>){

   	  alerta = "";

      if(ender == ""){
        alerta += "Endereço\n";
      }

	  valcpf = true;

	<?

	  if (isset($incproc) && ($incproc!="")) {
		$result_protparam = db_query("select * from protparam where p90_instit = ".db_getsession("DB_instit"));
		if (pg_numrows($result_protparam)>0){
	  	  db_fieldsmemory($result_protparam,0);
		  if ($p90_valcpfcnpj == 'f'){
	?>
	   	    valcpf = false;
    <?
		  }
		}
	  }
    ?>

	if (valcpf == true) {
      if (cgccpf == "") {
        alerta += "CPF/CNPJ\n";
      }
    }

    if(alerta != "" && <?=$testanome?> == true){
	  alert("O Contribuinte não possui o CGM atualizado");
	 <?
      //testa permissao de menu
 	  echo "location.href = 'prot1_cadcgm002.php?chavepesquisa='+z01_numcgm+'&testanome=$func_antes&valores=$valores&funcao_js=".$func_antes.$valores."';";
     ?>
	}else{
	 <?=$func_antes."(".$camp.")"?>;
	}
  }
<?
}
?>

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
      <table width="100%" border="0" cellspacing="0">
				<form name="form2" method="post" action="" >
         <?
            db_input('filtro',6,"",true,'hidden',3);
						db_input('incproc',6,"",true,'hidden',3);
					?>
					<tr>
						<td align="right">
							<label for="numcgmDigitadoParaPesquisa">Código:</label>
						</td>
						<td >
							<?
								db_input('z01_numcgm',6,$Iz01_numcgm,true,'text',4,"onkeyup='js_ValidaCampos(this,1,\"Código\",\"\",\"\",event);'","numcgmDigitadoParaPesquisa");
							?>
						</td>
						<td align="right">
            <? if ($filtro==1 || $filtro==0){?>
                <label for="cpf"><?=$DBtxt30?>:</label>
						</td>
						<td>
							<?
								db_input('z01_cgccpf',20,$Iz01_cgccpf,true,'text',1,"onkeyup='js_ValidaCampos(this,1,\"CPF\",\"\",\"\",event);'",'cpf');
							?>

            <? } ?>
						</td>
					</tr>
          <tr>
						<td align="right">
							<label for="nomeDigitadoParaPesquisa">Nome:</label>
						</td>
						<td>
							<?
								db_input('z01_nome',40,$Iz01_nome,true,'text',4,"",'nomeDigitadoParaPesquisa');
							?>
						</td>
						<td align="right">
            <? if ($filtro==2 || $filtro==0){?>
							<label for="cnpj"><?=$DBtxt31?>:</label>
						</td>
						<td>
							<?
								db_input('z01_cgccpf',20,$Iz01_cgccpf,true,'text',1,"onkeyup='js_ValidaCampos(this,1,\"CNPJ\",\"\",\"\",event);'",'cnpj');
							?>
             <? } ?>
						</td>
					</tr>
					<tr>
						<td colspan="4" align="center"><br>
							<input name="pesquisar" type="submit" id="pesquisar2"			value="Pesquisar">
							<input name="limpar"		type="button" id="naoencontrado2" value="Limpar" onClick="js_limpa()">
							<input name="Fechar"	  type="button" id="limpar"					value="Fechar"
							       onClick="<?=isset($ifrname) ? 'parent.func_nome.hide();' : 'js_close();';?>">
							<?
							if((!isset($nomeDigitadoParaPesquisa) || trim($nomeDigitadoParaPesquisa) == "") && isset($lTelaCgmAlt)){
							?>
								<input name='proccgmalt' type='button' id='proccgmalt' value='Procurar Nomes Alterados' onClick="js_pesquisacgmalt(document.form2.nomeDigitadoParaPesquisa.value);">
							<?
							}

							if(isset($testanome)){

                $sStringFunc = "";
                if(empty($func_antes)){
                  $sStringFunc = $func_antes;
                }
							?>
							<input name="Incluir" type="button" value="Incluir Novo CGM" onClick="location.href = 'prot1_cadcgm001.php?testanome=<?=$sStringFunc?>&valores=<?=$valores?>&funcao_js=<?=$sStringFunc.$valores?>&vldCGM=true'">
								<script>

									var permissao_parcelamento = <?=db_permissaomenu(db_getsession("DB_anousu"),604,1305)?>;

									if(permissao_parcelamento == false){
										document.form2.Incluir.disabled = true;
									}

								</script>
							<?
								}
							?>
						</td>
					</tr>
				</form>
      </table>
		</td>
		<script>

         /*
         Valida para não deixar colar letras nos campos numéricos
         */

        $('numcgmDigitadoParaPesquisa').onpaste = function(event) {
          return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
        }

        $('cpf').onpaste = function(event) {
          return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
        }

        $('cnpj').onpaste = function(event) {
          return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
        }

        $('cpf').maxLength = 11;

				function js_consultacgmoriginal(iNumcgm){
			 	  document.form2.numcgmDigitadoParaPesquisa.value = iNumcgm;
					document.form2.submit();
				}

				function js_pesquisacgmalt(nome){
					document.location.href = "func_nome.php?pesquisa_cgmalt="+nome+"&lTelaCgmAlt=true&funcao_js=<?=$funcao_jscgmalt?>";
				}

				function js_limpa(){
					for(i =0;i < document.form2.elements.length;i++){

						if(document.form2.elements[i].type == 'text'){
							document.form2.elements[i].value = "";
						}
					}
				}

		</script>
  </tr>
	<tr>
    <td align="center" valign="top">
      <?
        if ($filtro==1){
          $sMetodoExecutar="sql_query_cpf";
        }elseif($filtro==2){
          $sMetodoExecutar="sql_query_cgc";
        }else{
           $sMetodoExecutar="sql_query";

           if (!isset($filtro)) {
	           $filtro="";
           }
        }
        if(!isset($pesquisa_chave)){
					echo "<script>
								//js_limpa();
									document.form2.nomeDigitadoParaPesquisa.focus();
								</script>";

					if(isset($campos)==false){
						$campos = "cgm.z01_numcgm, z01_nome,trim(z01_cgccpf) as z01_cgccpf, case when length(trim(z01_cgccpf)) = 14 then 'JURIDICA' else 'FÍSICA' end as tipo, trim(z01_ender) as z01_ender, z01_munic, z01_uf, z01_cep, z01_email";
					}

          if ($filtro==1){
             $campos = "cgm.z01_numcgm, z01_nome,trim(z01_cpf) as z01_cpf,case when length(trim(z01_cpf)) = 11 then 'FISICA' else 'JURIDICA' end as tipo, trim(z01_ender) as z01_ender, z01_munic, z01_uf, z01_cep, z01_email";

           }elseif($filtro==2){
             $campos = "cgm.z01_numcgm, z01_nome,trim(z01_cgc) as z01_cgc, case when length(trim(z01_cgc)) = 14 then 'JURIDICA' else 'FISICA' end as tipo, trim(z01_ender) as z01_ender, z01_munic, z01_uf, z01_cep, z01_email";
          }

          if (isset($lCadTecMunic)) {
            $campos = "cgm.z01_numcgm, z01_nome,trim(z01_cgccpf) as z01_cgccpf, case when length(trim(z01_cgccpf)) = 14 then 'JURIDICA' else 'FÍSICA' end as tipo, trim(z01_ender) as z01_ender, z01_numero, z01_compl, z01_munic, z01_uf, z01_cep, z01_email";
          }

          $clnome   = new cl_cgm;
          $clcgmalt = new cl_cgmalt;

          $lTelaCgmAlt = false;

					if (isset($nomeDigitadoParaPesquisa) && ($nomeDigitadoParaPesquisa!="") ){

							$nomeDigitadoParaPesquisa = strtoupper($nomeDigitadoParaPesquisa);
							$sql = $clnome->sqlnome($nomeDigitadoParaPesquisa,$campos,$filtro);
					}else if(isset($numcgmDigitadoParaPesquisa) && $numcgmDigitadoParaPesquisa != ""){

            if( !is_int((int)$numcgmDigitadoParaPesquisa) ){
              $numcgmDigitadoParaPesquisa = 0;
            }

						$sql = $clnome->$sMetodoExecutar($numcgmDigitadoParaPesquisa,$campos);
					}else if(isset($cpf) && $cpf != ""){
							$sql = $clnome->$sMetodoExecutar("",$campos,""," z01_cgccpf = '$cpf' ");
					}else if(isset($cnpj) && $cnpj != ""){
							$sql = $clnome->$sMetodoExecutar("",$campos,""," z01_cgccpf = '$cnpj' ");
					}else if(isset($pesquisa_cgmalt) && trim($pesquisa_cgmalt) != ""){
							$campos 		 = "z05_numcgm as z01_numcgm, z05_nome as z01_nome,trim(z05_cgccpf) as z05_cgccpf, case when length(trim(z05_cgccpf)) = 14 then 'JURIDICA' else 'FÍSICA' end as tipo, trim(z05_ender) as z05_ender, z05_munic, z05_uf, z05_cep, z05_email,z05_data_alt, z05_hora_alt,login";
							$sql	  		 = $clcgmalt->sql_query("",$campos,"","z05_nome like '%{$pesquisa_cgmalt}%' and z05_tipo_alt = 'A'");
							$lTelaCgmAlt = true;
				      $funcao_js 	 = "js_consultacgmoriginal|z01_numcgm";
					}else{
$sql = "";
							if(isset($z01_numcgm) && $z01_numcgm != ""){
									$sql = $clnome->$sMetodoExecutar($z01_numcgm,$campos);
							}
          }

					if(isset($sql) && trim($sql) != ""){
						 $rsNome = db_query($sql) or die($sql);
						 if( pg_num_rows($rsNome) == 0){
								if(isset($nomeDigitadoParaPesquisa) && trim($nomeDigitadoParaPesquisa!="")){
									?>
									  <table>
										  <tr>
											  <td>
												  <fieldset>
													  <legend align="center">
														  <b>Nenhum registro encontrado!</b>
													  </legend>
													  <table>
														  <tr>
															  <td>
																  Clique no botão abaixo para pesquisar nomes alterados!
															  </td>
														  </tr>
													  </table>
												  </fieldset>
											  </td>
										  </tr>
										  <tr align="center">
											  <td>
												  <input name='proccgmalt' type='button' id='proccgmalt' value='Procurar Nomes Alterados' onClick="js_pesquisacgmalt(document.form2.nomeDigitadoParaPesquisa.value);">
											  </td>
										  </tr>
									  </table>
									<?
							  }else{
									?>
									  <table>
										  <tr>
											  <td>
												  <fieldset>
													  <table>
														  <tr>
															  <td>
																	<b>Nenhum registro encontrado!</b>
																</td>
														  </tr>
													  </table>
												  </fieldset>
											  </td>
										  </tr>
									  </table>
						      <?
								}
						 }else{

							 if($lTelaCgmAlt){
								 ?>
							   <table>
									 <tr>
										 <td>
											 <fieldset>
												 <legend align="center">
													<b>&nbsp;Registros de CGM Alterados&nbsp;</b>
												 </legend>
												 <table>
													 <tr>
														 <td>
															 <?
																 db_lovrot($sql,14,"()","",$funcao_js);
															 ?>
														 </td>
													 </tr>
												 </table>
											 </fieldset>
										 </td>
									 </tr>
								 </table>
								 <?
							 }else{

                 $aVarRepassa = array(
                   "nomeDigitadoParaPesquisa" => "$nomeDigitadoParaPesquisa",
                   "cpf"                      => ( isset($cpf)  ? $cpf  : "" ),
                   "cnpj"                     => ( isset($cnpj) ? $cnpj : "" )
                 );

								 db_lovrot($sql, 14, "()", "", $funcao_js, "", "NoMe", $aVarRepassa);
							 }
						 }
					}
			}else{
				if($pesquisa_chave!=""){
						$result = $clcgm->sql_record($clcgm->$sMetodoExecutar($pesquisa_chave));

						if(!isset($testanome)){
							if(($result!=false) && (pg_numrows($result) != 0)){
								 db_fieldsmemory($result,0);
								   if ($filtro==1){

                        echo "<script>".$funcao_js."(false,\"$z01_nome\",\"$z01_cpf\");</script>";
                   }elseif ($filtro==2){
                        echo "<script>".$funcao_js."(false,\"$z01_nome\",\"$z01_cgc\");</script>";
                   }elseif ($filtro==3){
                        echo "<script>".$funcao_js."(false,\"$z01_nome\",\"$z01_cgccpf\");</script>";
                   }else{
                        echo "<script>".$funcao_js."(false,\"$z01_nome\",\"$z01_cgccpf\");</script>";
                       }
							}else{

                 echo "<script>".$funcao_js."(true,'Código (".$pesquisa_chave.") não Encontrado');</script>";
							}
						}else{
							if(($result!=false) && (pg_numrows($result) != 0)){
								db_fieldsmemory($result,0);
								echo "<script>\n";
								if($z01_ender == '' || $z01_cgccpf == ''){

									echo "alert('Contribuinte com o CGM desatualizado')\n
									".$funcao_js."(true,'Contribuinte com o CGM desatualizado');\n";
								}else{
									if(isset($novosvalores)){
										$novosvalores = str_replace('|',',$',$novosvalores);
										eval('$novosvalores = "'.$novosvalores.'";');
										$novosvalores = str_replace(",","','",$novosvalores);
										$novosvalores = substr($novosvalores,2,strlen($novosvalores)-2);

										echo "".$funcao_js."(false,$novosvalores');\n";
									}else{
                    if ($filtro==1){
										     echo "".$funcao_js."(false,\"$z01_nome\",\"$z01_cpf\");\n";
                    }elseif($filtro==2){
                         echo "".$funcao_js."(false,\"$z01_nome\",\"$z01_cgc\");\n";
                   }elseif ($filtro==3){
                         echo "".$funcao_js."(false,\"$z01_nome\",\"$z01_cgccpf\");\n";
                    }else{
                         echo "".$funcao_js."(false,\"$z01_nome\");\n";
                     }
									}
								}
								echo"</script>\n";
						  }else{
								//echo "<script> alert('Código (".$pesquisa_chave.") não Encontrado')</script>";
								echo "<script>".$funcao_js."(true,'Código (".$pesquisa_chave.") não Encontrado');</script>\n";
							}
					  }
				  }
				}
			?>
    </td>
  </tr>
</table>
</body>
</html>

<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
