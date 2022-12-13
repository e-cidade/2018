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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_cadtipo_classe.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
db_postmemory($HTTP_GET_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("classes/dbViewAvaliacoes.classe.js");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("widgets/dbmessageBoard.widget.js");
  db_app::load("dbcomboBox.widget.js");
  db_app::load("DBHint.widget.js");

$oCadTipo = new cl_cadtipo;

/*
 * Se vier pesquisa_chave vier setada
 * buscamos os registros por ela
 */
if(isset($pesquisa_chave)){

	$sCamposGerado  = " ar40_sequencial,                                          \n";
  $sCamposGerado .= " case when ar40_tipogeracao = 'I' then 'Individual' else   \n";
  $sCamposGerado .= "                                      'Geral'              \n";
  $sCamposGerado .= " end as ar40_tipogeracao,                                  \n";
  $sCamposGerado .= " ar40_dtvencimento,	                                      \n";
  $sCamposGerado .= " ar40_dtoperacao,                                          \n";
  $sCamposGerado .= " ar40_percentualdesconto,                                  \n";
  $sCamposGerado .= " ar40_observacao                                           \n";

	$sSqlGerado   = "select {$sCamposGerado} from recibounicageracao where ar40_sequencial = {$pesquisa_chave}";

  $rsGerado     = db_query($sSqlGerado);

  if(pg_num_rows($rsGerado) > 0 && $rsGerado){

    $oDadosGerado = db_utils::fieldsMemory($rsGerado, 0);

	  echo "<script>";
	  echo "  parent.$('ar40_sequencial').value = '{$oDadosGerado->ar40_sequencial}';                        ";
	  echo "  parent.$('sTipoGeracao').value    = '{$oDadosGerado->ar40_tipogeracao}';                       ";
	  echo "  parent.$('dtVencimento').value    = '".db_formatar($oDadosGerado->ar40_dtvencimento, 'd')."';  ";
	  echo "  parent.$('dtLancamento').value    = '".db_formatar($oDadosGerado->ar40_dtoperacao  , 'd')."';  ";
	  echo "  parent.$('desconto').value        = '{$oDadosGerado->ar40_percentualdesconto}';                ";
	  echo "  parent.$('obs').value             = '{$oDadosGerado->ar40_observacao}';                        ";
	  //echo "  parent.db_iframe_lista.hide();";

	  echo "</script>";

	}else{ //se nao acha dados na chave_pesquisa nao vier registros devolvemos o erro nao encontrada

	  echo "<script>";
		echo "  alert('Chave {$pesquisa_chave} não encontrada '); ";
		echo "  parent.$('ar40_sequencial').value = '';           ";
    echo "  parent.$('sTipoGeracao').value    = '';           ";
    echo "  parent.$('dtVencimento').value    = '';           ";
    echo "  parent.$('dtLancamento').value    = '';           ";
    echo "  parent.$('desconto').value        = '';           ";
    echo "  parent.$('obs').value             = '';           ";
		//echo "  parent.db_iframe_lista.hide();";
	  echo "</script>";
	}

	//echo $sSqlGerado; die();

} else{// pesquisa_chave vindo nula seguimos a lookup normal

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">

</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_gridGeracao();">

  <table align="center" width="600" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center" valign="top" bgcolor="#CCCCCC"  style="padding-top:30px;">

        <fieldset>
          <legend align="left">
            <b>Filtros</b>
          </legend>

          <form name="form2" id="form2">
            <table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
		           <tr>
		              <td nowrap="nowrap" width="150">
		                <b><? db_ancora ( "CGM :", "js_pesquisacgm(true)", 1 )?></b>
		              </td>
		              <td>
		                <?
		                  db_input ( "z01_numcgm", 10, "", true, "text", 1, " onchange='js_pesquisacgm(false);' " );
		                  db_input ( "z01_nome", 40, "", true, "text", 3 );
		                ?>

		            </td>
		           </tr>

			        <tr>
			          <td>
									<b><? db_ancora("Matricula :",' js_matri(true); ',1); ?></b>
			          </td>
			          <td>
									<?
									  db_input('j01_matric', 10, 0, true, 'text', 1, "onchange='js_matri(false)'");
									  //db_input('z01_nome'  , 40, 0, true, 'text', 3, "");
									?>
			          </td>
			        </tr>

						  <tr>
						    <td>
						     <b>
						       <?db_ancora("Inscrição :",' js_inscr(true); ',1); ?>
						     </b>
						    </td>
						    <td>
						     <?
						      db_input('q02_inscr',10,"",true,'text',1,"onchange='js_inscr(false)'");
						      //db_input('z01_nome',40,0,true,'text',3,"");
						     ?>
						    </td>
						  </tr>


						  <tr>
                <td>
                 <b>
                   Tipo de Geração :
                 </b>
                </td>
                <td>
                 <?
                   $aTipoGera = array ("I" => "Individual",
                                       "G" => "Geral"
                                      );
                   db_select('tipoGeracao', $aTipoGera, true, 1,"style='width:100px;'");
                 ?>
                </td>
              </tr>

              <tr>
                <td>
                 <b>
                   Tipo de Débito :
                 </b>
                </td>
                <td>
                 <?
                   $sSqlTipos    = $oCadTipo->sql_query_file(null, "*", "k03_tipo", null);
                   $rsTipoDebito = $oCadTipo->sql_record($sSqlTipos);
                   db_selectrecord("tipoDebito", $rsTipoDebito, true, 1);
                 ?>
                </td>
              </tr>

               <tr>
                  <td nowrap="nowrap" width="150">
                    <b>Numpre :</b>
                  </td>
                  <td>
                    <?
                      db_input ( "numpre", 10, "", true, "text", 1 );
                    ?>

                </td>
               </tr>

		            <tr>
		              <td ><b>Data da Geração :</b></td>
		              <td ><?db_inputdata("dtGeracao", "", "", "", true, "", 1); ?></td>
		            </tr>

            </table>
          </form>
         </fieldset>

       </td>
     </tr>
     <tr>
       <td align="center">
         <input style="margin-top: 10px;" name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="lista_geradas();">
         <input name="Fechar" type="button" id="limpar" value="Fechar">
       </td>
     </tr>
</table>

<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="100%" align="center" valign="top" bgcolor="#CCCCCC"  style="padding-top:30px;">

        <fieldset>
          <legend align="left">
            Dados
          </legend>

          <table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">

            <tr>
              <td >
                <div id='ctnDados'></div>
              </td>
            </tr>

          </table>

         </fieldset>

       </td>
     </tr>

   </table>

</body>
</html>

<script>

var sUrlRPC = "func_geracao.RPC.php";

function completaGerados(iIdGeracao, sTipoGeracao, dtVencimento, dtLancamento, iPercentual, sObs){

  parent.$('ar40_sequencial').value = iIdGeracao;
  parent.$('sTipoGeracao')   .value = sTipoGeracao;
  parent.$('dtVencimento')   .value = dtVencimento;
  parent.$("dtLancamento")   .value = dtLancamento;
  parent.$('desconto')       .value = iPercentual;
  parent.$('obs')            .value = sObs;
  parent.db_iframe_lista.hide();

}


function lista_geradas() {

   var msgDiv             = "Aguarde ...";
   var oParametros        = new Object();
   /*
      definimos as variaveis de filtro
   */
   var iCgm         = $F('z01_numcgm');
   var iMatricula   = $F('j01_matric');
   var iInscricao   = $F('q02_inscr');
   var iTipoGeracao = $F('tipoGeracao');
   var iTipoDebito  = $F('tipoDebito');
   var iNumpre      = $F('numpre');
   var dtGeracao    = $F('dtGeracao');
   if(iTipoDebito == 0 || iTipoDebito == '0'){
     iTipoDebito = '';
   }
   oParametros.exec         = 'geracao';
   oParametros.iCgm         = iCgm;
   oParametros.iMatricula   = iMatricula;
   oParametros.iInscricao   = iInscricao;
   oParametros.iTipoGeracao = iTipoGeracao;
   oParametros.iTipoDebito  = iTipoDebito;
   oParametros.iNumpre      = iNumpre;
   oParametros.dtGeracao    = dtGeracao;

   js_divCarregando(msgDiv,'msgBox');

   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoCompletaGeradas
                                             });

}
/*
 * funcao para montar a grid com os registros de geradas
 *  retornado do RPC
 *
 */
function js_retornoCompletaGeradas(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 1) {

    oGridGeracao.clearAll(true);
    if ( oRetorno.dados.length == 0 ) {

      alert('Nenhum registro encontrado!');
      return false;
    }
    oRetorno.dados.each(function (oDado, iInd) {

      var aRow    = new Array();
      var sObs    = oDado.ar40_observacao.urlDecode();
          aRow[0] = oDado.ar40_sequencial ;
          aRow[1] = oDado.ar40_tipogeracao;
          aRow[2] = oDado.sOrigem ;
          aRow[3] = oDado.z01_nome.urlDecode();
          aRow[4] = oDado.sTipoDebito;
          aRow[5] = oDado.k00_numpre;
          aRow[6] = oDado.ar40_dtoperacao ;

      oGridGeracao.addRow(aRow);
      oGridGeracao.aRows[iInd].sEvents = "onClick='completaGerados("+oDado.ar40_sequencial+",\""+oDado.ar40_tipogeracao+"\",\""+oDado.k00_dtvenc+"\",\""+oDado.ar40_dtoperacao+"\",\""+oDado.k00_percdes+"\",\""+sObs+"\");'";
    });

    oGridGeracao.renderRows();
  }

  if(oRetorno.status == 0){
    alert(oRetorno.sMessage)
  }
}



function js_gridGeracao() {

  oGridGeracao = new DBGrid('Geracao');
  oGridGeracao.nameInstance = 'oGridGeracao';
  //oGridGeracao.setCheckbox(0);
  oGridGeracao.allowSelectColumns(true);
  oGridGeracao.setCellWidth(new Array(' 5%',
                                      '10%',
                                      '15%',
                                      '30%',
                                      '20%',
                                      '10%',
                                      '10%'
                                   ));

  oGridGeracao.setCellAlign(new Array( 'left' ,
                                       'left' ,
                                       'left' ,
                                       'left' ,
                                       'left' ,
                                       'right' ,
                                       'center'
                                      ));

  oGridGeracao.setHeader(new Array( 'Código',
                                    'Tipo de Geração',
                                    'Origem',
                                    'Nome',
                                    'Tipo de Débito',
                                    'Numpre',
                                    'Data da Geração'
                                ));

  oGridGeracao.setHeight(150);
  oGridGeracao.show($('ctnDados'));
  oGridGeracao.clearAll(true);
}

// PESQUISA DE INSCRICAO

function js_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframeInscr','func_issbase.php?funcao_js=parent.js_mostraInscr|q02_inscr|z01_nome|q02_dtbaix','Pesquisa Inscriçao',true);
  }else{
    js_OpenJanelaIframe('','db_iframeInscr','func_issbase.php?pesquisa_chave='+$F("q02_inscr")+'&funcao_js=parent.js_mostraInscr1','Pesquisa Inscriçao',false);
  }
}
function js_mostraInscr(chave1,chave2,baixa){
  if (baixa!=""){
    db_iframeInscr.hide();
    alert("Inscrição já  Baixada");
  }else{

    $("q02_inscr").value  = chave1;
    $("z01_nome").value   = chave2;
    $("j01_matric").value = "";
    $("z01_numcgm").value = "";
    db_iframeInscr.hide();
  }
}
function js_mostraInscr1(chave,erro,baixa){

  if(erro==true){
    $("q02_inscr").focus();
    $("z01_nome").value  = chave;
    $("q02_inscr").value = '';
  }else if (baixa!=""){
    alert("Inscrição já  Baixada");
    $("q02_inscr").value = "" ;
  }else{
    $("z01_nome").value   = chave;
    $("j01_matric").value = '';
    $("z01_numcgm").value = "";
  }
}



// PESQUISA MATRICULA

function js_matri(mostra){

  var matri = $("j01_matric").value;
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframeMatric','func_iptubase.php?funcao_js=parent.js_mostraMatric|0|2','Pesquisa Matricula',true);
  }else{
    js_OpenJanelaIframe('','db_iframe','func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostraMatric1','Pesquisa Matricula',false);
  }
}

function js_mostraMatric(chave1,chave2){

  $("j01_matric").value = chave1;
  $('z01_nome').value   = chave2;
  $("q02_inscr").value  = "";
  $("z01_numcgm").value = "";
  db_iframeMatric.hide();
}

function js_mostraMatric1(chave,erro){

  $("z01_nome").value   = chave;
  $("q02_inscr").value  = "";
  $("z01_numcgm").value = "";

  if(erro == true){
    $("j01_matric").focus();
    $("j01_matric").value = '';
  }
}

// PESQUISA CGM

function js_pesquisacgm(lMostra){

    if (lMostra) {
       js_OpenJanelaIframe('',
                           'db_iframe_cgm',
                           'func_nome.php?funcao_js=parent.js_mostracgm1|z01_nome|z01_numcgm',
                           'Pesquisar CGM',
                           true,'0');
    } else {
      if($('z01_numcgm').value != ''){
         js_OpenJanelaIframe('',
                             'db_iframe_acordogrupo',
                             'func_nome.php?pesquisa_chave='+$F('z01_numcgm')+
                             '&funcao_js=parent.js_mostracgm',
                             'Pesquisa',
                             false,
                             '0');
      } else {
        $("z01_numcgm").value = "";
      }
    }
  }

  function js_mostracgm(erro, chave){

    if(erro == true) {

      $('z01_numcgm').focus();
      $("z01_numcgm").value = "";
      $('z01_nome').value = chave;
    } else {

      $('z01_nome').value = chave;
      $("j01_matric").value = '';
      $("q02_inscr").value = "";

    }
  }

  function js_mostracgm1(chave1, chave2) {

    $('z01_nome').value   = chave1;
    $('z01_numcgm').value = chave2;
    $("j01_matric").value = '';
    $("q02_inscr").value = "";
    db_iframe_cgm.hide();
  }

</script>
<?} ?>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
