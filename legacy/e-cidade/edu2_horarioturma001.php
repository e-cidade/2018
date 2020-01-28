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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_matricula_classe.php");
include("dbforms/db_funcoes.php");
$escola = db_getsession("DB_coddepto");
$clmatricula = new cl_matricula;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<SCRIPT LANGUAGE="JavaScript">

function lista_turmas() {

  $('turmas').innerHTML           = '';
  $('professor').innerHTML        = '';
  $('ctnProfessor').style.display = 'none';

  var oOptionSelecione       = document.createElement('option');
	oOptionSelecione.value     = '';
	oOptionSelecione.innerHTML = '';

	$('turmas').appendChild(oOptionSelecione);
  
  var oParametro         = new Object();
  oParametro.exec        = "pesquisaTurmaTipoGradeHorario";
  oParametro.iCalendario = $F('calendario');
  oParametro.tipoVinculo = 2; // Grade de horario

  var oConfig          = new Object();
  oConfig.method       = 'post';
  oConfig.parameters   = 'json='+Object.toJSON(oParametro);
  oConfig.asynchronous = true;
  oConfig.onComplete   = js_retornoTurmas;

	js_divCarregando("Aguarde, Buscando Turmas!.", "msgBox");
  
  var oAjax = new Ajax.Request ('edu_educacaobase.RPC.php', oConfig);
}

function js_retornoTurmas(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');

  if (oRetorno.dados.length > 0) {

    oRetorno.dados.each(function (oTurma, iSeq) {

      var sDescricao  = oTurma.ed57_c_descr.urlDecode();
      		sDescricao += " - " + oTurma.ed11_c_descr.urlDecode();
			var oOption       = document.createElement('option');
			oOption.value     = oTurma.ed220_i_codigo;
			oOption.innerHTML = sDescricao;

			$('turmas').appendChild(oOption);
    });
  } else {

    var oOption       = document.createElement('option');
		oOption.value     = '';
		oOption.innerHTML = "Sem Turmas";

		$('turmas').appendChild(oOption);
  }
}
//End -->
</script>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post" action="">
<center>
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<br>
<fieldset style="width:95%"><legend><b>Relatório de Horário das Turmas</b></legend>
<table border="0" align="left">
 <tr>
  <td>
   <table border="0" align="left">
    <tr>
     <td>
      <b>Calendário:</b><br>
      <select id='calendario' name="grupo" onChange="lista_turmas();" style="font-size:9px;width:150px;height:18px;">
       <option value = '' selected="selected"></option>
       <?
       #Seleciona todos os grupos para setar os valores no combo
       $sql = "SELECT ed52_i_codigo,ed52_c_descr
               FROM calendario
                inner join calendarioescola on ed38_i_calendario = ed52_i_codigo
               WHERE ed38_i_escola = $escola
               AND ed52_c_passivo = 'N'
               ORDER BY ed52_i_ano DESC";
       $sql_result = db_query($sql);
       while($row=pg_fetch_array($sql_result)){
        $cod_curso=$row["ed52_i_codigo"];
        $desc_curso=$row["ed52_c_descr"];
        ?>
        <option value="<?=$cod_curso;?>" ><?=$desc_curso;?></option>
        <?
       }
       #Popula o segundo combo de acordo com a escolha no primeiro
       ?>
      </select>
     </td>
     <td>
      <b>Turma:</b><br>
      <select id='turmas' name="subgrupo" style="font-size:9px; width:150px; height:18px;" onchange="js_buscaDocentes();">
       <option value=""></option>
      </select>
     </td>
      <td id='ctnProfessor' style="display: none;">
       <b>Selecione o Professor:</b> (Opcional)<br>
       <select name="professor" id='professor' style="font-size:9px;width:250px;height:18px;">
       </select>
       <input id='processar' type="button" value="Processar" name="processar" onclick="js_imprimir();" >
      </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</fieldset>
<iframe name="dados" id="dados" src="" width="750" height="350" frameborder="0"></iframe>
</center>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>

function js_buscaDocentes() {

  $('professor').innerHTML = '';
  
	if ($F('turmas') == '') {

		alert('Selecione uma Turma.');
		return false;
	}

	$('ctnProfessor').style.display = 'table-cell';
  
  var oParametro                  = new Object();
  oParametro.exec                 = "buscaProfessoresTurma";
  oParametro.iTurmaSerieRegimeMat = $F('turmas');

  var oConfig          = new Object();
  oConfig.method       = 'post';
  oConfig.parameters   = 'json='+Object.toJSON(oParametro);
  oConfig.asynchronous = true;
  oConfig.onComplete   = js_retornoProfessor;

	js_divCarregando("Aguarde, Buscando Professores!.", "msgBox");
  
  var oAjax = new Ajax.Request ('edu_educacaobase.RPC.php', oConfig);
  
}       

function js_retornoProfessor(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');

  if (oRetorno.dados.length > 0) {

	  var oOption       = document.createElement('option');
		oOption.value     = '';
		oOption.innerHTML = "TODOS";

		$('professor').appendChild(oOption);
		
    oRetorno.dados.each(function (oProfessor, iSeq) {

			var oOption       = document.createElement('option');
			oOption.value     = oProfessor.ed20_i_codigo;
			oOption.innerHTML = oProfessor.z01_numcgm + ' - ' + oProfessor.z01_nome.urlDecode();

			$('professor').appendChild(oOption);
    });

    $('processar').removeAttribute('disabled');
  } else {

    var oOption       = document.createElement('option');
		oOption.value     = '';
		oOption.innerHTML = "NENHUM PROFESSOR CADASTRADO PARA ESTA TURMA.";

		$('professor').appendChild(oOption);
		$('processar').setAttribute('disabled', 'disabled');
  }
}


function js_imprimir() {

	var sUrl  = "edu2_horarioturma002.php?professor="+$F('professor');
	    sUrl += "&turma="+$F('turmas');

	jan = window.open(sUrl, '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
	jan.moveTo(0,0); 
}

</script>