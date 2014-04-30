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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_utils.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);

$iEscola           = db_getsession("DB_coddepto");
$oDaoRelatModel    = new cl_edu_relatmodel();
$oDaoAlunoCurso    = new cl_alunocurso();
$oDaoHistorico     = new cl_historico();
$oDaoEduParametros = new cl_edu_parametros();

$iModulo        = db_getsession("DB_modulo");
$oDaoEscola     = db_utils::getdao('escola');

if (!isset($iTipoAluno)) {
  $iTipoAluno = "1";
}

$iCodigoAluno = '';
$sNomeAluno   = '';

if (isset($oGet->ed47_i_codigo) && !empty($oGet->ed47_i_codigo)) {

	$iCodigoAluno  = $oGet->ed47_i_codigo;
	$sNomeAluno    = $oGet->ed47_v_nome;
}

$sFiltroReclassificacao = 'display-row;';
$sSqlEduParametros = $oDaoEduParametros->sql_query_file(
                                                         null,
                                                         "ed233_reclassificaetapaanterior",
                                                         null,
                                                         "ed233_i_escola = {$iEscola}"
                                                       );
$rsEduParametros   = db_query( $sSqlEduParametros );

if ( $rsEduParametros && pg_num_rows( $rsEduParametros ) > 0 ) {

  $sMostraEtapaAnterior = db_utils::fieldsMemory( $rsEduParametros, 0 )->ed233_reclassificaetapaanterior;
  
  if ( $sMostraEtapaAnterior == 'f' ) {
    $sFiltroReclassificacao = 'none;';
  }
}
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  
  	.bloqueado {
  		background-color:#DEB887;
  	}
  </style>
 </head>
 <body bgcolor="#CCCCCC" >

   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
  <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
   <tr>
    <td valign="top">
     <br>
     <form name="form1" method="post">
      <fieldset style="width:95%"><legend><b>Relatório Certificado de Conclusão</b></legend>
       <table border="0" >
        <tr colspan='3' >
         <tr>
           <td>
             <table id='filtros'  ><!--Inicio tabela selects escola filtro-->
                 <?
                  if ($iModulo == 7159) {
             
                    echo '<td align="left" colspan= "3">';
                    echo ' <b>Selecione a escola:</b>';
                    echo '</td>';
                    echo '<td>';
             
                    $sSqlEscola     = $oDaoEscola->sql_query_file("", "ed18_i_codigo, ed18_c_nome", "", "");
                    $rsResultEscola = $oDaoEscola->sql_record($sSqlEscola);
                    $iLinhas        = $oDaoEscola->numrows;
                    echo '<select name="escola" id="escola" onChange="js_alunoshist(this.value,document.form1.tipoaluno.value, true);"
                              style="height:18px;font-size:10px;width:290px">';
                    echo ' <option value="">Selecione a Escola</option>';
                    for ($iCont = 0; $iCont < $iLinhas; $iCont++) {
                      $oDadosEscola = db_utils::fieldsmemory($rsResultEscola, $iCont);
                      echo " <option value='$oDadosEscola->ed18_i_codigo'>$oDadosEscola->ed18_c_nome</option> ";
                    }
                    echo ' </select>';
                    echo '</td>';
                  } else {
             
                    $iEscola = db_getsession("DB_coddepto");
                    echo "<input type= 'hidden' id ='escola' value = '$iEscola' >";
             
                  }
                  ?>
               </tr>
               <tr>
                 <td colspan= '3'>
                 <b>Filtro:</b>
                 </td>
                 <td>
                  <select name="tipoaluno" id="tipoaluno" style="height:18px;font-size:10px;width:290px;"
                          onchange="js_alunoshist(document.form1.escola.value, this.value);">
                    <option value="1" <?= $iTipoAluno == "1" ? "selected" : "" ?>>Alunos vinculados nesta escola</option>
                    <option value="2" <?= $iTipoAluno == "2" ? "selected" : "" ?>>Alunos sem vínculos com escolas</option>
                  </select>
                 </td>
               </tr>
             </table><!--fim tabela select escola filtro-->
           </td>
         </tr>
       </tr>
       <tr id='listaAlunos' >
         <td style="width:450px;">
          <b>Alunos com curso concluído:</b><br>
          <select name="alunoshist" id="alunoshist" size="20" onclick="js_desabinc()"
                  multiple style="font-size:9px;width:450px;">
          </select>
         </td>
         <td align="center">
         <br>
           <table>
            <tr>
             <td>
              <input name="incluirum" id= "incluirum" title="Incluir" type="button" value=">" onclick="js_incluir();"
                     style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                     font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
             </td>
            </tr>
            <tr><td height="1"></td></tr>
            <tr>
             <td>
              <input name="incluirtodos" id= "incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();"
                     style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                     font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
             </td>
            </tr>
            <tr><td height="3"></td></tr>
            <tr>
             <td>
              <hr>
             </td>
            </tr>
            <tr><td height="3"></td></tr>
            <tr>
             <td>
              <input name="excluirum" id= "excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();"
                     style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                     font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
             </td>
            </tr>
            <tr><td height="1"></td></tr>
            <tr>
             <td>
              <input name="excluirtodos" id= "excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();"
                     style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                     font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
             </td>
            </tr>
           </table>
         </td>
         <td>
          <b>Alunos para impressão :</b><br>
          <select name="alunos[]" id="alunos" size="20" onclick="js_desabexc()"
                  multiple style="font-size:9px;width:350px;">
          </select>
         </td>
       </tr>
       
        <tr id='alunoHistorico' style="display: <?php echo $sOcultaAluno;?>" >
         <td><label style="width:98px; display: block; float: left;"><b>Aluno:</b></label>
           <input class='bloqueado' type="text" id='aluno' name='aluno' value="<?php echo $iCodigoAluno;?>" size='10' 
                  readonly="readonly" />
           <input class='bloqueado' type="text" id='nome' name='nome' value="<?php echo $sNomeAluno;?>" size='50' 
                  readonly="readonly" />
         </td>
       </tr>
       
    </tr>
    <tr>
    <td colspan = '3'>
    <table> <!-- tabela  dos selects-->
    <tr>
     <td nowrap colspan= '2'>
      <b>Tipo do Modelo:</b>
      </td>
      <td>
      <select name="tipocertificado" id= "tipocertificado" style="font-size:9px;width:290px;" Onchange = "js_Orientacao();">
      </select>
     </td>
    </tr>
    <tr id = 'tdOrientacao'>
     <td colspan='2'>
      <b>Orientação:</b>
      </td>
      <td>
      <select name="orientacao" id="orientacao" style="font-size:9px;width:290px;" onchange='js_validaTipoRegistro()'>
      </select>
     </td>
    </tr>
    <tr id = 'tdCabecalho'>
     <td colspan='2'>
      <b>Disposição do Cabeçalho:</b>
      </td>
      <td>
      <select name="disposicao" id="disposicao" style="font-size:9px;width:290px;" >
       <option value='0'>Selecione</option>
       <option value='1'>Disposição 1</option>
       <option value='2'>Disposição 2</option>
      </select>
     </td>
    </tr>
    <tr>
     <td colspan = '2'>
      <b>Registros:</b>
      </td>
      <td>
      <select name="tiporegistro" id= "tiporegistro" style="font-size:9px;width:290px;">
       <option value='A'>Etapas APROVADAS</option>
       <option value='AR'>Etapas APROVADAS e REPROVADAS</option>
      </select>
     </td>
    </tr>
    
    <tr style="display: <?php echo $sFiltroReclassificacao; ?>">
      <td colspan= '2'><label class="bold">Exibir Reclassificação:</label></td>
      <td>
        <select name="exibir_reclassificacao" id= "exibir_reclassificacao" style="font-size:9px;width:290px;">
          <option value='f'>NÃO</option>
          <option value='t'>SIM</option>
        </select>
      </td>
    </tr>
    
    <tr>
     <td nowrap colspan = '2'>
      <b>Diretor:</b>
      </td>
      <td>
      <select name="diretor" id="diretor" style="font-size:9px;width:290px;">
      </select>
     </td>
    </tr>
    <tr>
     <td colspan = '2'>
      <b>Secretário:</b>
      </td>
      <td>
      <select name="secretario" id="secretario" style="font-size:9px;width:290px;">
      </select>
     </td>
    </tr>
    </table><!--fim tabela selects -->
    </td>
    </tr>
    <tr>
     <td colspan='3'>
      <input name="pesquisar" type="button" id="pesquisar" value="Processar" onclick="js_pesquisa();" disabled>
     </td>
    </tr>
        <tr>
         <td align="center" valign="top"  colspan="3">
          <br>
          <fieldset style="align:center">
            Para selecionar mais de um aluno mantenha pressionada a tecla CTRL e clique sobre o nome dos alunos.

           <div id= 'div'>
            <h4 align= 'left'>Disposições do Cabeçalho</h4>
             1 - Neste modelo o sistema posicionará as informações lado a lado na seguinte ordem :
                 Brasão da República Federativa do Brasil, texto inserido no campo cabeçalho
                 e o nome da escola, mantenedora, endereço e atos legais.<br>
             2 - Neste modelo o sistema posicionará as informações centralizadas uma abaixo da outra:
                 Brasão da República Federativa do Brasil e texto inserido no campo cabeçalho no topo do
                 cabeçalho centralizado, abaixo deste o título do documento e abaixo deste o nome da escola, mantenedora,
                 atos legais e endereço.

           </div>
          </fieldset>
         </td>
        </tr>
       </table>
      </fieldset>
     </td>
    </tr>
   </table>
  </form>
  <div id='mostraMenu'></div>
    <?
    if ( empty($oGet->ed47_i_codigo) ) {
     
      db_menu(db_getsession("DB_id_usuario"),
              db_getsession("DB_modulo"),
              db_getsession("DB_anousu"),
              db_getsession("DB_instit") );

    }
    ?>
 </body>
</html>
<script>
<?
if ($iModulo != 7159) {
  echo "js_alunoshist($iEscola, 1)";
}
?>

var oGet = js_urlToObject();

if (!oGet.ed47_i_codigo) {
  
  $('filtros').style.display       = 'table';
  $('listaAlunos').style.display   = 'table-row';
  $('mostraMenu').style.display    = 'block';
  $('alunoHistorico').style.display = 'none';
}

function js_alunoshist(escola, tipoaluno) {

  var oParam           = new Object();
      oParam.exec      = "PesquisaAlunoCert";
      oParam.escola    = escola;
      oParam.tipoaluno = tipoaluno;

  var url              = 'edu4_escola.RPC.php';

  if ( !js_urlToObject().ed47_i_codigo ) {
    js_webajax(oParam,'js_retornoPesquisaAlunoCert',url);
  } else {

    js_TipoRelatorio();
    js_Orientacao();
  }
  js_limpa();

}


function js_retornoPesquisaAlunoCert(oRetorno) {


  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';

  if (oRetorno.iStatus  != 1) {
    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    for (var i = 0;i < oRetorno.aResultCert.length; i++) {

      with (oRetorno.aResultCert[i]) {

        sHtml += '<option value="'+oRetorno.aResultCert[i].ed47_i_codigo+'">';
        sHtml += oRetorno.aResultCert[i].ed47_v_nome.urlDecode()+" - "+oRetorno.aResultCert[i].ed47_i_codigo;

      }

    }

    $('incluirtodos').disabled            = false;
    $('alunoshist').innerHTML             = sHtml;
    document.form1.alunoshist[0].selected = true;

  }

  $('alunoshist').disabled  = false;
  js_TipoRelatorio();
  js_Orientacao();

  if (oRetorno.iEscola != "") {

    js_Diretor(oRetorno.escola);
    js_Sec(oRetorno.iEscola);

  } else {

    js_Diretor($('escola').value);
    js_Sec($('escola').value);

  }

}

function js_Diretor(escola) {

  var oParam        = new Object();
      oParam.exec   = "getDiretor";
      oParam.escola = $('escola').value;

  var url           = 'edu4_escola.RPC.php';

  js_webajax(oParam,'js_retornoPesquisaDiretor',url);

}

function js_retornoPesquisaDiretor(oRetorno) {


  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';

  if (oRetorno.iStatus  != 1) {
    return false;
  } else {

    sHtml += '<option value="">Selecione o Diretor</option>';

    for (var i = 0;i < oRetorno.aResultDiretor.length; i++) {

      with (oRetorno.aResultDiretor[i]) {

        sFuncao    = oRetorno.aResultDiretor[i].funcao;
        sNome      = oRetorno.aResultDiretor[i].nome;
        sDescricao = oRetorno.aResultDiretor[i].descricao;
        sTipo      = oRetorno.aResultDiretor[i].tipo;
        sValue     = sFuncao+" - "+sNome+" - "+sDescricao;
        sText      = sFuncao+" - "+sNome+" - "+sDescricao;

        sHtml     += '<option value="'+sValue+'">';
        sHtml     += sText.urlDecode()+'</option>';

      }

    }

    $('diretor').innerHTML             = sHtml;
    document.form1.diretor[0].selected = true;

  }

  $('diretor').disabled  = false;

}

function js_Sec(escola) {

  var oParam        = new Object();
      oParam.exec   = "getSecretario";
      oParam.escola = $('escola').value;

  var url           = 'edu4_escola.RPC.php';

  js_webajax(oParam,'js_retornoPesquisaSec',url);

}

function js_retornoPesquisaSec(oRetorno) {

  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';

  if (oRetorno.iStatus  != 1) {
    return false;
  } else {

    sHtml += '<option value="">Selecione o Secretário</option>';

    for (var i = 0;i < oRetorno.aResultSec.length; i++) {

      with (oRetorno.aResultSec[i]) {

        sFuncao    = oRetorno.aResultSec[i].funcao;
        sNome      = oRetorno.aResultSec[i].nome;
        sDescricao = oRetorno.aResultSec[i].descricao;
        sTipo      = oRetorno.aResultSec[i].tipo;
        sValue     = sFuncao+" - "+sNome+" - "+sDescricao;
        sText      = sFuncao+" - "+sNome+" - "+sDescricao;

        sHtml     += '<option value="'+sValue+'">';
        sHtml     += sText.urlDecode()+'</option>';

      }

    }

    $('secretario').innerHTML             = sHtml;
    document.form1.secretario[0].selected = true;


  }

  $('secretario').disabled  = false;

}


function js_TipoRelatorio() {

	var oParam            = new Object();
	    oParam.exec       = "getTipoCertificado";
	    oParam.escola     = $('escola').value;
	    oParam.iRelatorio = $('tipocertificado').value;
	var url               = 'edu4_escola.RPC.php';

	js_webajax(oParam,'js_retornoPesquisaTipoCertificado',url);

}


function js_retornoPesquisaTipoCertificado(oRetorno) {

  var oRetorno = eval("("+oRetorno.responseText+")");
	sHtml        = '';

	if (oRetorno.iStatus  != 1) {

	  alert(oRetorno.sMessage.urlDecode());
	  return false;

	} else {

	  for (var i = 0;i < oRetorno.aResultTipoCertificado.length; i++) {

	    with (oRetorno.aResultTipoCertificado[i]) {

	      iCodigo = oRetorno.aResultTipoCertificado[i].ed217_i_codigo;
	      sNome   = oRetorno.aResultTipoCertificado[i].ed217_c_nome;

	      sHtml   += '<option value="'+iCodigo+'">';
	      sHtml   += sNome.urlDecode()+'</option>';

	    }


	    $('tipocertificado').innerHTML             = sHtml;
	    document.form1.tipocertificado[0].selected = true;

	  }
	  $('tipocertificado').disabled  = false;

  }
}

function js_Orientacao() {

	var oParam            = new Object();
	    oParam.exec       = "getOrientacao";
	    oParam.escola     = $('escola').value;
	    oParam.iRelatorio = $('tipocertificado').value;
	var url               = 'edu4_escola.RPC.php';

	js_webajax(oParam,'js_retornoPesquisaOrientacao',url);

}



function js_retornoPesquisaOrientacao(oRetorno) {

  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';

	if (oRetorno.iStatus  != 1) {

	  $('orientacao').innerHTML       = '';
	  $('tdOrientacao').style.display = 'none';
	  $('tdCabecalho').style.display  = 'none';
	  $('div').style.display          = 'none';
	  js_validaTipoRegistro();
	  return false;

	} else {

	  $('tdOrientacao').style.display = '';
	  $('tdCabecalho').style.display  = '';
	  $('div').style.display          = '';

	  var lTemOrientacaoRetrato = false; //Para identificar se o cliente possui modelo retrato cadastrado
	  
	  for (var i = 0;i < oRetorno.aResultOrientacao.length; i++) {

	    with (oRetorno.aResultOrientacao[i]) {

	      if (oRetorno.aResultOrientacao[i].ed217_orientacao == 2) {
          lTemOrientacaoRetrato = true;
        }
    	  
	      sOrientacao  = oRetorno.aResultOrientacao[i].ed217_orientacao;
	      sNome        = oRetorno.aResultOrientacao[i].nome;

	      sHtml     += '<option value="'+sOrientacao+'">';
	      sHtml     += sNome.urlDecode()+'</option>';
	    }
	  }

	  $('orientacao').innerHTML             = sHtml;
	  document.form1.orientacao[0].selected = true;
	}

  js_validaTipoRegistro();
	$('orientacao').disabled  = false;

}



function js_pesquisa(curso) {

  alunos = "";
  codigo = "";
  sep    = "";
  for (i = 0; i < $('alunos').length; i++) {

    alunos += sep+$('alunos').options[i].value;
    sep     = ",";

  }
   
  if ( !!js_urlToObject().ed47_i_codigo ) {
    alunos = $F('aluno');
  }



  if ($('tipocertificado').value == "") {

    alert("Informe o Tipo do Modelo!");
    return false;

  }


  var sParametros  = 'sAlunos='+alunos+'&iEscola='+$('escola').value+'&iTipoRelatorio='+$('tipocertificado').value;
      sParametros += '&iTipoRegistro='+$('tiporegistro').value+'&sDiretor='+$('diretor').value;
      sParametros += '&sSecretario='+$('secretario').value+'&sDisposicao='+$('disposicao').value;
      sParametros += '&sExibirReclassificacao='+$('exibir_reclassificacao').value;

  if ($('orientacao').value == 2) {

    if ($('disposicao').value == 0) {

      alert('Escolha uma disposição!');
      return false
    }

    jan = window.open('edu2_certificadoconclusaoretrato002.php?' + sParametros, '',
        'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    
  } else {

    jan = window.open('edu2_historico002.php?' + sParametros, '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');

  }
  jan.moveTo(0,0);

}

function js_incluir() {

  var Tam = $('alunoshist').length;
  var F   = document.form1;

  for (x = 0;x < Tam; x++) {

    if (F.alunoshist.options[x].selected == true) {

      F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunoshist.options[x].text,
                                                                                         F.alunoshist.options[x].value)
      F.alunoshist.options[x] = null;
      Tam--;
      x--;

    }

  }

  if ($('alunoshist').length > 0) {
    $('alunoshist').options[0].selected = true;
  } else {

    $('incluirum').disabled    = true;
    $('incluirtodos').disabled = true;

  }

  $('pesquisar').disabled    = false;
  $('excluirtodos').disabled = false;
  $('alunos').focus();

}

function js_incluirtodos() {

  var Tam = $('alunoshist').length;
  var F   = document.form1;
  for (i = 0; i < Tam; i++) {

     F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunoshist.options[0].text,
                                                                                        F.alunoshist.options[0].value)
     F.alunoshist.options[0] = null;
  }

  $('incluirum').disabled    = true;
  $('incluirtodos').disabled = true;
  $('excluirtodos').disabled = false;
  $('pesquisar').disabled    = false;
  $('alunos').focus();

}

function js_excluir() {

  var F = document.getElementById("alunos");
  Tam   = F.length;
  for (x = 0; x < Tam; x++) {

    if (F.options[x].selected == true) {

      document.form1.alunoshist.options[document.form1.alunoshist.length] = new Option(F.options[x].text,
                                                                                       F.options[x].value);
      F.options[x] = null;
      Tam--;
      x--;

    }

  }

  if ($('alunos').length > 0) {
    $('alunos').options[0].selected = true;
  }

  if (F.length == 0) {

    $('pesquisar').disabled    = true;
    $('excluirum').disabled    = true;
    $('excluirtodos').disabled = true;
    $('incluirtodos').disabled = false;

  }

  $('alunoshist').focus();

}

function js_excluirtodos() {

  var Tam = $('alunos').length;
  var F   = document.getElementById("alunos");
  for (i = 0; i < Tam; i++) {

    $('alunoshist').options[$('alunoshist').length] = new Option(F.options[0].text,
                                                                 F.options[0].value);
    F.options[0] = null;

  }

  if (F.length == 0) {

    $('pesquisar').disabled    = true;
    $('excluirum').disabled    = true;
    $('excluirtodos').disabled = true;
    $('incluirtodos').disabled = false;

  }

  $('alunoshist').focus();

}

function js_limpa() {

  var Alunos = document.getElementById("alunos");

  for (var i = 0; i < Alunos.length; i++) {
    Alunos.length = 0;
  }

  if (Alunos.length == 0) {

    $('pesquisar').disabled            = true;
    $('excluirum').disabled            = true;
    $('excluirtodos').disabled         = true;
    $('incluirtodos').disabled         = false;
    $('tipocertificado').selectedIndex = 0;
    $('tiporegistro').selectedIndex    = 0;
    $('diretor').selectedIndex         = 0;
    $('secretario').selectedIndex      = 0;

  }

  $('alunoshist').focus();

}

function js_desabinc() {

  for (i = 0; i < $('alunoshist').length; i++) {

    if ($('alunoshist').length > 0 && $('alunoshist').options[i].selected){

      if ($('alunos').length > 0) {
        $('alunos').options[0].selected = false;
      }

      $('incluirum').disabled = false;
      $('excluirum').disabled = true;

    }

  }

}

function js_desabexc() {

  for (i = 0; i < $('alunos').length; i++) {

    if ($('alunos').length > 0 && $('alunos').options[i].selected) {

      if ($('alunoshist').length > 0) {
        $('alunoshist').options[0].selected = false;
      }

      $('incluirum').disabled = true;
      $('excluirum').disabled = false;
    }

  }

}

function js_OrdenarLista(combo) {

  var lb = document.getElementById(combo);
  arrTexts = new Array();
  arrValues = new Array();
  for (i = 0; i < lb.length; i++) {

    arrValues[i] = lb.options[i].value;
    arrTexts[i] = lb.options[i].text;

  }

  arrTexts.sort();
  for (i = 0; i < lb.length; i++) {

    lb.options[i].text = arrTexts[i];
    lb.options[i].value = arrValues[i];
  }

}

function js_validaTipoRegistro() {

  var iTipoOrientacaoRelatorio = $F('orientacao');
  var sDisplayAprovadosReprovados = 'inline';
  if (iTipoOrientacaoRelatorio == 2) {
     sDisplayAprovadosReprovados = 'none';
  }
  $('tiporegistro').options[1].style.display = sDisplayAprovadosReprovados;

}
js_validaTipoRegistro();




if (oGet.ed47_i_codigo && oGet.ed47_i_codigo != '') {

  $('filtros').style.display       = 'none';
  $('listaAlunos').style.display   = 'none';
  $('mostraMenu').style.display    = 'none';
  $('alunoHistorico').style.display = 'table-row';
  $('pesquisar').disabled  = false;
}
</script>