<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_stdlibwebseller.php");
include("classes/db_mer_cardapioaluno_classe.php");
include("classes/db_calendario_classe.php");
include("classes/db_mer_cardapio_classe.php");
include("classes/db_mer_tipocardapio_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmer_cardapioaluno = new cl_mer_cardapioaluno;
$clmer_cardapio      = new cl_mer_cardapio;
$clcalendario        = new cl_calendario;
$clmer_tipocardapio        = new cl_mer_tipocardapio;
$clrotulo            = new rotulocampo;
$clrotulo->label("me11_d_data");
$db_opcao            = 1;
$db_botao            = true;
$escola              = db_getsession("DB_coddepto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">   
   <br>
   <center>
   <form name="form1" method="post" action="">
   <fieldset style="width:95%;"><legend><b>Relatório Consumo</b></legend>
    <table border="0" align="left" width="100%">
     <tr>
      <td>
       <table>
        <tr>  
         <td>
           <b>Tipo de lançamento:</b>          
          <select name="lancamento" id="lancamento" onChange="js_lancamento(this.value);"
                 style="height:18px;font-size:10px;">
           <option value=""></option>
           <option value="A">Por Aluno</option>
           <option value="T">Por Turma</option>                
          </select>
         </td>
         <td>
          <b>Cardápio:</b>
           <?          
           $hoje = date("Y-m-d",db_getsession("DB_datausu"));
           $result_tipocardapio = $clmer_tipocardapio->sql_record(
                                   $clmer_tipocardapio->sql_query("",
                                                                  "me27_i_codigo,me27_c_nome,me27_f_versao,me27_i_id",
                                                                  "me27_i_id,me27_f_versao desc",
                                                                  "((me27_d_inicio is not null 
                                                                     and me27_d_fim is null
                                                                     and me27_d_inicio <= '$hoje') 
                                                                     or (me27_d_fim is not null and '$hoje'
                                                                     between me27_d_inicio and me27_d_fim))
                                                                   AND exists(select * from mer_cardapioescola
                                                                              where me32_i_tipocardapio = me27_i_codigo
                                                                              and me32_i_escola = $escola)
                                                                  "
                                                                 ));?>
         <select name="cardapio" id="cardapio"   onChange="js_cardapio(this.value);"
                  style="height:18px;font-size:10px;">
           <option value="0"></option>
           <?for ($t=0;$t<$clmer_tipocardapio->numrows;$t++) {
        
             db_fieldsmemory($result_tipocardapio,$t);
             ?>
             <option value="<?=$me27_i_codigo?>"><?=$me27_c_nome?> - Versão: <?=$me27_f_versao?></option>
      
           <?}?>
          </select>
         </td>
        </tr>
       </table>
      </td>
     </tr>
     <tr>
      <td>
       <table>
        <tr>
         <td>
          <b>Refeição:</b>
         </td>
         <td>
          <select name="select_refeicao" id="select_refeicao" style="width:450px;height:18px;font-size:10px;" onChange="js_refeicao(this.value);" disabled>
          </select>
         </td>
        </tr>
        <tr>
         <td>
          <b>Turma(s):</b>
         </td>
         <td>
          <select name="select_turma" id="select_turma" style="width:450px;font-size:10px;" multiple size="15" disabled>
          </select>
         </td>
        </tr>
       </table>
      </td>
     </tr>
     <tr>
      <td>
       <b>Data Inicial:</b>
       <?db_inputdata('data_ini',@$data_ini_dia,@$data_ini_mes,@$data_ini_ano,true,'text',1,"")?>
       <b>Data Final:</b>
       <?db_inputdata('data_fim',@$data_fim_dia,@$data_fim_mes,@$data_fim_ano,true,'text',1,"")?>
      </td>
     </tr>
     <tr>
      <td>
       <br>
       <input type="button" name="processar" value="Processar" onclick="js_processar();">
      </td>
     </tr>
    </table>
   </fieldset>
   </form>
   </center>
  </td>
 </tr>
</table>
</center>
<?db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
</body>
</html>
<script>
function js_lancamento() {

  $('cardapio').value   = "0";
  $('select_turma').innerHTML    = "";
  $('select_turma').disabled     = true;
  $('select_refeicao').innerHTML    = "";
  $('select_refeicao').disabled     = true;

}
function js_cardapio(cardapio) {

  if ($('lancamento').value=="") {
	alert("Informe o Tipo de Lançamento!");
	$('cardapio').value = "0";
    return false;
  }
  $('select_turma').innerHTML    = "";
  $('select_turma').disabled     = true;
  $('select_refeicao').innerHTML    = "";
  $('select_refeicao').disabled     = true;
  if (cardapio=="0") {
	return false;
  }
  js_divCarregando("Aguarde, carregando registro(s)","msgBox");
  var sAction = 'PesquisaRefeicao';
  var url     = 'mer2_mer_refalunoRPC.php';
  var oAjax = new Ajax.Request(url,
		                          {
                                    method    : 'post',
                                    parameters: 'lancamento='+$('lancamento').value+'&cardapio='+cardapio+'&sAction='+sAction,
                                    onComplete: js_retornoPesquisaRefeicao
                                  }
                               );
  
}

function js_retornoPesquisaRefeicao(oAjax) {
	
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml = '';
  if (oRetorno.length==0) {
    sHtml += '<option value="">Nenhuma refeição consumida para o cardápio e/ou escola selecionados!</option>';
  } else {
      
    sHtml += '<option value=""></option>';
    for (var i = 0;i < oRetorno.length; i++) {
        
      with (oRetorno[i]) {
          
        sHtml += '<option value="'+me12_i_codigo.urlDecode()+'">'+me12_d_data.urlDecode()+' - '+me03_c_tipo.urlDecode()+
                                 ' - '+me01_c_nome.urlDecode()+' - Versão: '+me01_f_versao.urlDecode()+'</option>';
         
      }
      
    }
    
  }  
  $('select_refeicao').innerHTML = sHtml;
  $('select_refeicao').disabled  = false;
  
}
function js_refeicao(refeicao) {

  $('select_turma').innerHTML    = "";
  $('select_turma').disabled     = true;
  if (refeicao=="") {
    return false;
  }
  js_divCarregando("Aguarde, carregando registro(s)","msgBox");
  var sAction = 'PesquisaTurma';
  var url     = 'mer2_mer_refalunoRPC.php';
  var oAjax = new Ajax.Request(url,
                                  {
                                    method    : 'post',
                                    parameters: 'lancamento='+$('lancamento').value+'&cardapio='+document.form1.cardapio.value+'&refeicao='+refeicao+'&sAction='+sAction,
                                    onComplete: js_retornoPesquisaTurma
                                  }
                               );
	  
}

function js_retornoPesquisaTurma(oAjax) {
    
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml = '';
  if(oRetorno.length==0){
    sHtml += '  <option value="">Nenhuma turma vinculada ao cardápio selecionado!</option>';
  }else{
	      
    for (var i = 0;i < oRetorno.length; i++) {
	        
      with (oRetorno[i]) {
	          
        sHtml += '  <option value="'+ed57_i_codigo+'">'+ed57_c_descr.urlDecode()+' - '+ed11_c_descr.urlDecode()+'</option>';
	        
      }
	            
    }
	    
  }
  $('select_turma').innerHTML = sHtml;
  $('select_turma').disabled = false;
	  
}


function js_processar() {
	
  if ($('lancamento').value=="") {
	  
    alert("informe o Tipo de Lançamento!");
    return false;
    
  }  
  if ($('select_refeicao').value!="" && $('data_ini').value!="" && $('data_ini').value!="") {

	alert("Não preencher datas quando alguma refeição estiver selecionada!");
	return false;

  }
  if ($('select_refeicao').value=="") {  

    if ($('data_ini').value=="") {
      
      alert("informe a Data Inicial!");
      return false;
	    
    }
    if ($('data_fim').value=="") {
      
      alert("informe a Data Final!");
      return false;
	        
    }
    
  }
  turma = "";
  sep = "";
  tam = document.form1.select_turma.length;
  for (i=0;i<tam;i++) {

	if (document.form1.select_turma[i].selected==true) {
		
	  turma += sep+document.form1.select_turma[i].value;
	  sep = ",";

	}  
	
  }
  data_ini   = $('data_ini').value.substr(6,4)+"-"+$('data_ini').value.substr(3,2)+"-"+$('data_ini').value.substr(0,2);
  if ($('data_fim').value!=""){
    data_fim = $('data_fim').value.substr(6,4)+"-"+$('data_fim').value.substr(3,2)+"-"+$('data_fim').value.substr(0,2);
  } else{
    data_fim = '';
  }
  if ($('lancamento').value=="A") {
    str = "mer2_mer_refaluno001.php?refeicao="+$('select_refeicao').value+"&cardapio="+$('cardapio').value+"&turma="+turma+"&datainicio="+data_ini+"&datafim="+data_fim;
  } else {
    str = "mer2_mer_refaluno002.php?refeicao="+$('select_refeicao').value+"&cardapio="+$('cardapio').value+"&turma="+turma+"&datainicio="+data_ini+"&datafim="+data_fim;
  }
  jan = window.open(str,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
  
}
</script>