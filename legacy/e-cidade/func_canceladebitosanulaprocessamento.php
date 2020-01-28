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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_cancdebitos_classe.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_cgm_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clcancdebitos = new cl_cancdebitos;
$cliptubase    = new cl_iptubase;
$clissbase     = new cl_issbase;
$clcgm         = new cl_cgm;

$clcancdebitos->rotulo->label("k20_codigo");
$clcancdebitos->rotulo->label("k20_data");
$clcancdebitos->rotulo->label("k20_descr");
$instit = db_getsession("DB_instit");

$cliptubase ->rotulo->label();
$clissbase  ->rotulo->label("q02_inscr");
$clcgm      ->rotulo->label("z01_numcgm");
$clcgm      ->rotulo->label("z01_nome");
$clcgm      ->rotulo->label("v07_parcel");

if (count($_POST ) > 0) {
  $lPesquisar = 'true';
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript"
	src="scripts/prototype.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

	<table height="100%" border="0" align="center" cellspacing="0">
		<tr>
			<td height="63" align="center" valign="top">


				<fieldset style="margin-top: 20px;">
					<legend>
						<strong> Filtros de Pesquisa </strong>
					</legend>
					<table border="0" align="center" cellspacing="0">

						<form name="form2" method="post" id="form2" action="">
						
						
						<tr>
							<td><?
							db_ancora($Lz01_nome,' js_cgm(true); ',1);
							?> 
							<input type='hidden' id='sPesquisar' name='sPesquisar' />
							</td>
							<td><?
							db_input('z01_numcgm', 10, $Iz01_numcgm,true,'text',1,"class='pesquisa' onchange='js_cgm(false);js_Limpa(this.value, this.id);'", 'chave_z01_numcgm');
							db_input('z01_nome',60,0,true,'text',3,"class='label'","z01_nomecgm");
							?>
							</td>
						</tr>
						<tr>
							<td><?
							db_ancora($Lj01_matric,' js_matri(true); ',1);
							?>
							</td>
							<td><?
							db_input('k00_matric',10,$Ij01_matric,true,'text',1,"class='pesquisa' onchange='js_matri(false);js_Limpa(this.value, this.id);'", 'chave_k00_matric');
							db_input('z01_nome',60,0, true,'text',3,"class='label'","z01_nomematri");
							?>
							</td>
						</tr>

						<tr>
							<td><?
							db_ancora($Lq02_inscr,' js_inscr(true); ',1);
							?>
							</td>
							<td><?
							db_input('k00_inscr', 10, $Iq02_inscr,true,'text',1,"class='pesquisa' onchange='js_inscr(false);js_Limpa(this.value, this.id);'", 'chave_k00_inscr');
							db_input('z01_nome', 60, 0          ,true,'text',3,"class='label'","z01_nomeinscr");
							?>
							</td>
						</tr>



						<tr>
							<td width="4%" align="left" nowrap title="<?=$Tk20_codigo?>"><strong>Código
									do Cancelamento:</strong>
							</td>
							<td width="96%" align="left" nowrap><?
							db_input("k20_codigo", 10, $Ik20_codigo, true,"text", 4, "", "chave_k20_codigo");
							?>
							</td>
						</tr>
						<tr>
							<td width="4%" align="left" nowrap title="<?=$Tk20_data?>"><strong>Data
									do Cancelamento:</strong>
							</td>
							<td width="96%" align="left" nowrap><?
							//db_input("k20_data",10,$Ik20_data,true,"text",1,"","chave_k20_data");
							db_inputdata("k20_data", null, null, null, true, null, 1, null, 'chave_k20_data' )
							?> à <? db_inputdata("k20_dataFim", null, null, null, true, null, 1, null, 'chave_k20_dataFim' )?>
							</td>
						</tr>
						<tr>
							<td width="4%" align="left" nowrap title="<?=$Tk20_descr?>"><?=$Lk20_descr?>
							</td>
							<td width="96%" align="left" nowrap><?db_input("k20_descr", 74, $Ik20_descr, true, "text", 4, "", "chave_k20_descr");?>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center"></td>
						</tr>
					</table>

				</fieldset>

				<div style="margin-top: 20px;">
					<input name="pesquisar" type="button" onclick="js_setPesquisa();"
						id="pesquisar2" value="Pesquisar"> <input name="limpar"
						type="reset" id="limpar" value="Limpar"> <input name="Fechar"
						type="button" id="fechar" value="Fechar"
						onClick="parent.db_iframe_cancdebitos.hide();">
				</div>
				</form> <br>

			</td>
		</tr>
		<tr>
			<td align="center" valign="top"><?
			if (!isset($pesquisa_chave)) {

			  $sql  = "select distinct                                                                                               ";
			  $sql .= "       k20_codigo,                                                                                            ";
			  $sql .= "       cgm.z01_numcgm ,                                                                                       ";
			  $sql .= "       cgm.z01_nome ,                                                                                         ";
			  if (isset($chave_k00_matric) && (trim($chave_k00_matric) != "")) {
			    $sql .= "       iptubase.j01_matric ,                                                                                ";
			  } else if (isset($chave_k00_inscr) && (trim($chave_k00_inscr) != "")) {
			    $sql .= "       issbase.q02_inscr ,                                                                                  ";
			  }
			  $sql .= "       to_char(k20_data,'dd/mm/yyyy') as k20_data,                                                            ";
			  $sql .= "       k20_descr,                                                                                             ";
			  $sql .= "	      nome                                                                                                   ";
			  $sql .= "  from cancdebitos                                                                                            ";
			  $sql .= "       inner join cancdebitosreg     on cancdebitosreg.k21_codigo             = cancdebitos.k20_codigo        ";
			  $sql .= "       inner join cancdebitosprocreg on cancdebitosprocreg.k24_cancdebitosreg = cancdebitosreg.k21_sequencia  ";
			  $sql .= "       inner join cancdebitosproc    on cancdebitosproc.k23_codigo            = cancdebitosprocreg.k24_codigo ";
			  $sql .= "       inner join arrecant           on arrecant.k00_numpre                   = cancdebitosreg.k21_numpre     ";
			  $sql .= "			                             and cancdebitosreg.k21_numpar             = arrecant.k00_numpar             ";
			  $sql .= "			inner join db_usuarios        on db_usuarios.id_usuario                = cancdebitos.k20_usuario         ";
			  $sql .= "			                                                                                                         ";
        if (isset($chave_k00_matric) && (trim($chave_k00_matric) != "")) {
			    
          $sql .= "			inner join arrematric on arrematric.k00_numpre = arrecant.k00_numpre                                   ";
			    $sql .= "			inner join iptubase on iptubase.j01_matric  = arrematric.k00_matric                                    ";
        } else if (isset($chave_k00_inscr) && (trim($chave_k00_inscr) != "")) {
			    
          $sql .= "			inner join arreinscr  on arreinscr.k00_numpre  = arrecant.k00_numpre                                   ";
			    $sql .= "			inner join issbase  on issbase.q02_inscr    = arreinscr.k00_inscr                                      ";
        }
			  $sql .= "			left join arrenumcgm on arrenumcgm.k00_numpre = arrecant.k00_numpre                                      ";
			  $sql .= "			left join cgm      on cgm.z01_numcgm       = arrenumcgm.k00_numcgm                                       ";
			  $sql .= " where not exists(select q78_cancdebitos                                                                      ";
			  $sql .= "                    from cancdebitosissplan                                                                   ";
			  $sql .= "                   where q78_cancdebitos = cancdebitos.k20_codigo)                                            ";

			  if (isset($chave_k20_codigo) && (trim($chave_k20_codigo) != "") ) {
			    $sql .= " and k20_codigo = $chave_k20_codigo   and k20_instit = $instit";
			  } else if (isset($chave_z01_numcgm) && (trim($chave_z01_numcgm) != "")) {
			    $sql .= " and arrecant.k00_numcgm = $chave_z01_numcgm   and k20_instit = $instit";
			  } else if (isset($chave_k00_inscr) && (trim($chave_k00_inscr) != "")) {
			    $sql .= " and arreinscr.k00_inscr = $chave_k00_inscr   and k20_instit = $instit";
			  } else if (isset($chave_k00_matric) && (trim($chave_k00_matric) != "")) {
			    $sql .= " and arrematric.k00_matric = $chave_k00_matric   and k20_instit = $instit";
			  }else if (isset($chave_k20_data) && (trim($chave_k20_data) != "") ) {

			    $dtPesquisaIni = implode("-", array_reverse(explode("/",$chave_k20_data)));
			    $dtPesquisaFim = date("Y-m-d", db_getsession("DB_datausu"));
			    if (isset($chave_k20_dataFim ) && (trim($chave_k20_dataFim) != "")) {
			      $dtPesquisaFim = implode("-", array_reverse(explode("/",$chave_k20_dataFim)));
			    }
			    $sql .= " and k20_data between '$dtPesquisaIni' and '$dtPesquisaFim' and k20_instit = $instit";

			  } else if (isset($chave_k20_descr) && (trim($chave_k20_descr) != "") ) {
			    $sql .= " and k20_descr like '%$chave_k20_descr%' and k20_instit = $instit";
			  } else {
			    $sql .= " and k20_instit =  $instit";
			  }

			  $sql .= " order by k20_codigo, k20_data ";
 			  if (isset($lPesquisar) && @$lPesquisar == 'true') {
			      db_lovrot($sql, 15, "()", "", $funcao_js);
 			  }

			  //die();
			} else {

			  if ($pesquisa_chave != null && $pesquisa_chave != "") {

			    $sSqlCancDebitos = $clcancdebitos->sql_query("", "", "", " k20_codigo = $pesquisa_chave and k20_instit = $instit");
			    $result          = $clcancdebitos->sql_record($sSqlCancDebitos);
			    if ($clcancdebitos->numrows != 0) {

			      db_fieldsmemory($result, 0);
			      echo "<script>".$funcao_js."('$k20_data',false);</script>";

			    } else {
			      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
			    }

			  } else {
			    echo "<script>".$funcao_js."('',false);</script>";
			  }
			}
			?></td>
		</tr>
	</table>

</body>
</html>


<?
if(!isset($pesquisa_chave)){

  echo "<script>";
  echo "</script>";
}
?>
<script>

iWidthJanela = screen.availWidth - 50;

/*
   função para controlar a exibição do lovrot sem filtros de pesquisa
   pois se não há filtros selecionados a pesquisa poderá se tornar lenta. 
 
 */
function js_setPesquisa(){

  var iCgm       = $F("chave_z01_numcgm");
  var iMatricula = $F("chave_k00_matric");
  var iInscr     = $F("chave_k00_inscr");
  var iCodigo    = $F("chave_k20_codigo");
  var dtPesquisa = $F("chave_k20_data");
  var sDescr     = $F("chave_k20_descr");

  if (iCgm       == '' && 
      iMatricula == '' &&
      iInscr     == '' &&
      iCodigo    == '' &&
      dtPesquisa == '' &&
      sDescr     == '') {

     if (confirm('Nenhum filtro selecionado, a pesquisa poderá se tornar lenta. \nContinuar sem filtros ?')) {

       $('sPesquisar').value = 'true';
       $('form2').submit();
     }  
  } else {
    
    $('sPesquisar').value = 'true';
    $('form2').submit();
  }    

}

//==================================== pesquisa por matricula ===============
function js_matri(mostra){
  
  var matri = $F("chave_k00_matric");
  if (mostra == true) {
    js_OpenJanelaIframe('','db_iframe3','func_iptubase.php?funcao_js=parent.js_mostramatri|0|2',
                       'Pesquisa',true, null, null, iWidthJanela, null);
  }else{
    if (matri != "") {
	    js_OpenJanelaIframe('','db_iframe3','func_iptubase.php?pesquisa_chave='+matri+
	                                                                  '&funcao_js=parent.js_mostramatri1','Pesquisa',false);
    }                                                                 
  }
}
function js_mostramatri(chave1, chave2){
  
  $("chave_k00_matric") .value = chave1;
  $("z01_nomematri")    .value = chave2;
  $('chave_z01_numcgm') .value = '';
  $('chave_k00_inscr')  .value = '';
  $('z01_nomecgm')      .value = '';
  $('z01_nomeinscr')    .value = '';  
  db_iframe3.hide();
}
function js_mostramatri1(chave, erro) {
  
  $("z01_nomematri")    .value = chave;
  $('chave_z01_numcgm') .value = '';
  $('chave_k00_inscr')  .value = '';   
  $('z01_nomecgm')      .value = '';
  $('z01_nomeinscr')    .value = '';
  
  if (erro == true) {
     
    $("chave_k00_matric").focus(); 
    $("chave_k00_matric").value = ''; 
  }
}


//==================================== pesquisa por inscricao ===============
function js_inscr(mostra){
  
  var inscr = $F("chave_k00_inscr");

  if (mostra == true) {
    js_OpenJanelaIframe('',
                        'db_iframe',
                        'func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome',
                        'Pesquisa',
                        true, 
                        null, 
                        null, 
                        iWidthJanela, 
                        null);
  } else {
    js_OpenJanelaIframe('','db_iframe','func_issbase.php?pesquisa_chave='+inscr+
                                                                  '&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
  }
}
function js_mostrainscr(chave1, chave2){
  
  $("chave_k00_inscr")  .value = chave1;
  $("z01_nomeinscr")    .value = chave2;
  $('chave_z01_numcgm') .value   = '';
  $('chave_k00_matric') .value = '';
  $('z01_nomecgm')      .value = '';
  $('z01_nomematri')    .value = '';
  db_iframe.hide();
}
function js_mostrainscr1(chave,erro){

  $("z01_nomeinscr")    .value = chave; 
  $('chave_z01_numcgm') .value   = '';
  $('chave_k00_matric') .value = '';
  $('z01_nomecgm')      .value = '';
  $('z01_nomematri')    .value = '';
  if (erro == true) { 

    $("chave_k00_inscr").focus(); 
    $("chave_k00_inscr").value = ''; 
  }
}



//====================================  pesquisa por CGM =================
function js_cgm(mostra) {

  var cgm = $("chave_z01_numcgm").value;
  if (mostra == true) {
    js_OpenJanelaIframe('', 'db_iframe2', 'func_nome.php?funcao_js=parent.js_mostracgm|0|1', 'Pesquisa', true, null, null, iWidthJanela, null);
  } else {
    js_OpenJanelaIframe('','db_iframe2','func_nome.php?pesquisa_chave=' + cgm 
                                                                  + '&funcao_js=parent.js_mostracgm1','Pesquisa',false);
  }
}
function js_mostracgm(chave1, chave2){
  
  $('chave_z01_numcgm') .value = chave1;
  $('z01_nomecgm')      .value = chave2;
  $('chave_k00_matric') .value = '';
  $('chave_k00_inscr')  .value = '';
  $('z01_nomematri')    .value = '';
  $('z01_nomeinscr')    .value = '';
  
  db_iframe2.hide();
}

function js_mostracgm1(erro, chave){
  
  $('z01_nomecgm')      .value = chave; 
  $('chave_k00_matric') .value = '';
  $('chave_k00_inscr')  .value = '';  
  $('z01_nomematri')    .value = '';
  $('z01_nomeinscr')    .value = '';  
  if(erro==true){ 
    $('chave_z01_numcgm').focus(); 
    $('chave_z01_numcgm').value = ''; 
  }
}


function js_Limpa(iValorCampo, iIdCampo) {

  var aText = $('form2').getInputs('text');   
      aText.each(function (oText, id) {  
        $(oText).value = '';
      });  
  $(iIdCampo).value = iValorCampo
}
</script>