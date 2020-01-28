<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_matricula_classe.php");
require_once("classes/db_calendario_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_escoladiretor_classe.php");
require_once("libs/db_utils.php");
$oDaoEscolaDiretor = db_utils::getdao('escoladiretor');
db_postmemory($HTTP_POST_VARS);
$oDaoMatricula     = db_utils::getdao('matricula');
$oDaoCalendario    = db_utils::getdao('calendario');
$db_opcao          = 1;
$db_botao          = true;
$sNomeEscola       = db_getsession("DB_nomedepto");
$iModulo           = db_getsession('DB_modulo');
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body class="body-default" >

  <div class="container">
  <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
  <a name="topo"></a>

  <form name="form1" method="post" action="">

      <fieldset>
        <legend>Expansão de Matrícula</legend>
       <table border="0">
        <tr>
         <?
           if ($iModulo == 7159) {

             echo '<td align="left">';
             echo ' <strong>Selecione a escola:</strong></td><td>';
                      $oDaoEscola     = db_utils::getdao('escola');
                      $sSqlEscola     = $oDaoEscola->sql_query_file("", "ed18_i_codigo, ed18_c_nome", "", "");
                      $rsEscola       = $oDaoEscola->sql_record($sSqlEscola);
                      $iLinhas        = $oDaoEscola->numrows;
                      echo '<select name="escola" id="escola" onChange="js_escola(this.value);"
                                    class="field-size9">';
                      echo ' <option value="">Selecione a Escola</option>';

                              for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

                                 $oDadosEscola = db_utils::fieldsmemory($rsEscola, $iCont);
                                 echo " <option value='$oDadosEscola->ed18_i_codigo'>$oDadosEscola->ed18_c_nome</option>";

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
           <td align="left">
            <strong>Selecione o Calendário:</strong>
           </td>
           <td>
            <select name="calendario" id= "select_calendario" onchange="js_tipoensino(this.value)" class="field-size9">
            </select>
           </td>
          </tr>
          <tr>
           <td>
            <strong>Selecione o Mês:</strong>
           </td>
           <td>
            <select name="mes" class="field-size9">
             <option value=""></option>
             <option value="1">JANEIRO</option>
             <option value="2">FEVEREIRO</option>
             <option value="3">MARÇO</option>
             <option value="4">ABRIL</option>
             <option value="5">MAIO</option>
             <option value="6">JUNHO</option>
             <option value="7">JULHO</option>
             <option value="8">AGOSTO</option>
             <option value="9">SETEMBRO</option>
             <option value="10">OUTUBRO</option>
             <option value="11">NOVEMBRO</option>
             <option value="12">DEZEMBRO</option>
            </select>
           </td>
          </tr>
         </tr>
         <td>
          <strong>Modalidade de Ensino:</strong>
         </td>
         <td>
          <select name="modalidade" id ="modalidade" onchange="js_nivelensino(this.value)" class="field-size9">
          </select>
         </td>
        </tr>
        <tr>
         <td>
          <strong>Nível de Ensino:</strong><br/>
         </td>
         <td>
          <select name="nivelensino" id="select_nivelensino" multiple onchange="js_Assinatura()" class="field-size9" disabled>
          </select>
         </td>
         <td>
          <fieldset style="align:center">
           Para selecionar mais de um nível de ensino,<br/>mantenha pressionada a tecla CTRL
           <br/>e clique sobre o nome dos níveis de ensinos.
          </fieldset>
         </td>
        </tr>
     <tr>
         <td>
          <strong>Emissor:</strong>
         </td>
         <td>
          <select name="diretor" id="diretor" class="field-size9">
          </select>
         </td>
        </tr>
     <tr>
      <td>
       <strong>Rendimento do Aluno:</strong>
      </td>
      <td>
       <select name="resultado" class="field-size9">
        <option value="1">SITUAÇÃO ATUAL (Calendário selecionado acima)</option>
        <option value="2">SITUAÇÃO ANTERIOR (Calendário anterior ao selecionado acima)</option>
       </select>
      </td>
     </tr>
     <tr>
      <td colspan="2">
       <input type="checkbox" name="imprime_lista" value="" checked> <strong>Imprimir listagem de alunos</strong>
      </td>
     </tr>
      <tr>
       <td valign="bottom" class="text-center" colspan="3">
        <input type="button" name="procurar" value="Processar"
               onclick="js_procurar(document.form1.calendario.value,document.form1.mes.value,
               document.form1.diretor.value,document.form1.resultado.value,document.form1.modalidade.value,
               document.form1.nivelensino.value)">
       </td>
      </tr>
     </table>
    </fieldset>
</form>
</div>
 <?php
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
           db_getsession("DB_anousu"),db_getsession("DB_instit")
          );
 ?>
</body>
</html>
<script>
function js_procurar(calendario,mes,diretor,resultado,modalidade,nivelensino) {

  if (calendario == "") {

    alert("Informe o Calendário!");
    return false;

  }

  if (mes == "") {

    alert("Informe o Mês!");
    return false;

  }

  if (modalidade == "") {

    alert("Informe a Modalidade de Ensino!");
    return false;

  }

  if (nivelensino == "") {

    alert("Informe o Nível de Ensino!");
    return false;

  }

  qtde        = document.form1.nivelensino.length;
  nivelensino = "";
  sep         = "";

  for (i = 0; i <  qtde; i++) {

    if (document.form1.nivelensino.options[i].selected == true) {

  	  nivelensino += sep+document.form1.nivelensino.options[i].value;
      sep          = ",";

    }

  }

  if (document.form1.imprime_lista.checked == true) {
    imprime_lista = "yes";
  } else {
    imprime_lista = "no";
  }

  jan = window.open('edu2_expomatricula_seriesexo002.php?sCalendario='+calendario+'&iMes='+mes+'&iResultado='+resultado+
		                '&sDiretor='+diretor+'&sModalidade='+modalidade+'&iNivelEnsino='+nivelensino+
		                '&sImprimeLista='+imprime_lista+'&iEscola='+$('escola').value,'',
		                'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
		               );
  jan.moveTo(0,0);

}


function js_escola(escola) {

  var oParam    = new Object();

  oParam.exec   = "PesquisaCalendario";
  oParam.escola =  escola;

  var url       = 'edu4_escola.RPC.php';
  js_webajax(oParam,'js_retornoPesquisaCalendario',url);

}

function js_retornoPesquisaCalendario(oRetorno) {


  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';

  if (oRetorno.iStatus  != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    sHtml += '<option value="">Selecione o Calendário</option>';

    for (var i = 0;i < oRetorno.aResult.length; i++) {

      with (oRetorno.aResult[i]) {

        sHtml += '<option value="'+oRetorno.aResult[i].ed52_i_codigo+'">';
        sHtml += oRetorno.aResult[i].ed52_c_descr.urlDecode()+'</option>';

      }

    }

    $('select_calendario').innerHTML             = sHtml;
    document.form1.select_calendario[0].selected = true;

  }

  $('select_calendario').disabled  = false;

}


function js_tipoensino(escola) {

  var oParam    = new Object();

  oParam.exec   = "PesquisaTipoEnsino";
  oParam.escola =  escola;

  var url       = 'edu4_escola.RPC.php';

  js_webajax(oParam,'js_retornoPesquisaTipoEnsino',url);

}

function js_retornoPesquisaTipoEnsino(oRetorno) {

  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';

  if (oRetorno.iStatus  != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    sHtml += '<option value="">Selecione a Modalidade</option>';

    for (var i = 0;i < oRetorno.aResultTipoEnsino.length; i++) {

      with (oRetorno.aResultTipoEnsino[i]) {

        sHtml += '<option value="'+oRetorno.aResultTipoEnsino[i].ed36_i_codigo+'">';
        sHtml += oRetorno.aResultTipoEnsino[i].ed36_c_descr.urlDecode()+'</option>';

      }

    }

    $('modalidade').innerHTML             = sHtml;
    document.form1.modalidade[0].selected = true;

  }

  $('modalidade').disabled  = false;

}



function js_nivelensino(tipoensino) {

  var oParam            = new Object();
      oParam.exec       = "PesquisaNivelEnsino";
      oParam.tipoensino =  tipoensino;

  var url       = 'edu4_escola.RPC.php';

  js_webajax(oParam,'js_retornoPesquisaNivelEnsino',url);

}

function js_retornoPesquisaNivelEnsino(oRetorno) {


  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';

  if (oRetorno.iStatus  != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    //sHtml += '<option value="">Selecione o Calendário</option>';

    for (var i = 0;i < oRetorno.aResultNivelEnsino.length; i++) {

      with (oRetorno.aResultNivelEnsino[i]) {

        sHtml += '<option value="'+oRetorno.aResultNivelEnsino[i].ed10_i_codigo+'">';
        sHtml += oRetorno.aResultNivelEnsino[i].ed10_c_descr.urlDecode()+'</option>';

      }

    }

    $('select_nivelensino').innerHTML             = sHtml;
    document.form1.select_nivelensino[0].selected = true;

  }

  $('select_nivelensino').disabled  = false;

}

function js_Assinatura(escola) {

  var oParam        = new Object();
	  oParam.exec   = "getAssinatura";
	  oParam.escola =  $('escola').value;

  var url           = 'edu4_escola.RPC.php';
	  js_webajax(oParam,'js_retornoPesquisaAssinatura',url);

}

function js_retornoPesquisaAssinatura(oRetorno) {

  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';

  if (oRetorno.iStatus  != 1) {

	alert(oRetorno.sMessage.urlDecode());
	return false;

  } else {

	sHtml += '<option value="">Selecione</option>';

	for (var i = 0;i < oRetorno.aResultAssinatura.length; i++) {

	  with (oRetorno.aResultAssinatura[i]) {

	    sFuncao    = oRetorno.aResultAssinatura[i].funcao;
	    sNome      = oRetorno.aResultAssinatura[i].nome;
	    sDescricao = oRetorno.aResultAssinatura[i].descricao;
	    sValue     = sFuncao+" - "+sNome+" - "+sDescricao;
	    sText      = sFuncao+" - "+sNome+" - "+sDescricao;

	    sHtml += '<option value="'+sValue+'">';
	    sHtml += sText.urlDecode()+'</option>';

	  }

	}

	$('diretor').innerHTML   = sHtml;
	$('diretor')[0].selected = true;

  }

  $('diretor').disabled  = false;


}
</script>
<?
if ($iModulo != 7159) {
?>
  <script>
    js_escola(<?=$iEscola?>);
  </script>

<?

}
?>