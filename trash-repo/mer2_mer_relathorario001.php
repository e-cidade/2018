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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_mer_tipocardapio_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmer_tipocardapio = new cl_mer_tipocardapio;
$escola             = db_getsession("DB_coddepto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
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
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">   
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Relatório de Cardápios</b></legend>
   <table border="0" align="left" width="100%">
    <tr>
     <td align="center">
      <table>
       <tr> 
        <td>
         <b>Cardápio:</b>
        </td>
        <td> 
         <?          
         $hoje = date("Y-m-d",db_getsession("DB_datausu"));
         $result_tipocardapio = $clmer_tipocardapio->sql_record($clmer_tipocardapio->sql_query("",
                                                                                          "me27_i_codigo,me27_c_nome,me27_f_versao,me27_i_id",
                                                                                          "me27_i_id,me27_f_versao desc",
                                                                                          "((me27_d_inicio is not null 
                                                                                             and me27_d_fim is null
                                                                                             and me27_d_inicio <= '$hoje') 
                                                                                            or (me27_d_fim is not null and '$hoje'
                                                                                                between me27_d_inicio and me27_d_fim))"
                                                                                         ));
                                                                                         ?>
         <select name="cardapio" id="cardapio" onChange="js_cardapio(this.value);" 
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
      <tr>
       <td>
        <b>Escola:</b>
       </td>
       <td>
        <select name="select_escola" id="select_escola" onchange="js_escola(this.value)" 
                style="width:450px;height:18px;font-size:10px;;" disabled>
        </select>
       </td>
      </tr>
      <tr>
       <td><b>Mês:</b></td>
       <td>
         <select name="mes" id="mes" onchange="js_carrega();"  style="font-size:9px;width:100px;height:18px;">
          <option value="0" <?=@$mes=="0"?"selected":""?>></option>
          <option value="01" <?=@$mes=="01"?"selected":""?>>JANEIRO</option>
          <option value="02" <?=@$mes=="02"?"selected":""?>>FEVEREIRO</option>
          <option value="03" <?=@$mes=="03"?"selected":""?>>MARÇO</option>
          <option value="04" <?=@$mes=="04"?"selected":""?>>ABRIL</option>
          <option value="05" <?=@$mes=="05"?"selected":""?>>MAIO</option>
          <option value="06" <?=@$mes=="06"?"selected":""?>>JUNHO</option>
          <option value="07" <?=@$mes=="07"?"selected":""?>>JULHO</option>
          <option value="08" <?=@$mes=="08"?"selected":""?>>AGOSTO</option>
          <option value="09" <?=@$mes=="09"?"selected":""?>>SETEMBRO</option>
          <option value="10" <?=@$mes=="10"?"selected":""?>>OUTUBRO</option>
          <option value="11" <?=@$mes=="11"?"selected":""?>>NOVEMBRO</option>
          <option value="12" <?=@$mes=="12"?"selected":""?>>DEZEMBRO</option>
         </select>
        </td>
       </tr>
       <tr> 
        <td><b>Semana:</b><br></td>
        <td>
         <div name="div_semana">
          <select name="semana" id="semana" style="font-size:9px;width:200px;height:18px;">
           <option value="0">
           </option>
          </select>
         </div>
        </td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
   <input name="incluir" type="button" value="Processar" onclick="js_processa();">
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
</body>
</html>
<script>
function js_cardapio(cardapio) {

  $('select_escola').innerHTML         = "";
  $('select_escola').disabled          = true;
  js_divCarregando("Aguarde, carregando registro(s)","msgBox");
  var sAction = 'PesquisaEscola';
  var url     = 'mer2_mer_relathorarioRPC.php';
  var oAjax = new Ajax.Request(url,
                               {
                                 method    : 'post',
                                 parameters: 'cardapio='+cardapio+
                                             '&sAction='+sAction,
                                 onComplete: js_retornoPesquisaEscola
                               }
                              );
    
}
function js_retornoPesquisaEscola(oAjax) {
    
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml = '';
  if (oRetorno.length==0) {
    sHtml += '<option value="">Nenhum escola vinculada ao cardápio selecionado!</option>';
    $('select_escola').innerHTML = sHtml;
  } else {

    if (oRetorno.length>1) {
     sHtml += '<option value="">TODAS</option>';
    }
    for (var i = 0;i < oRetorno.length; i++) {
            
      with (oRetorno[i]) {
        sHtml += '<option value="'+me32_i_codigo+'">'+ed18_c_nome.urlDecode()+'</option>';
      }
      
    }
    $('select_escola').innerHTML = sHtml;
       
  }  
  $('select_escola').disabled  = false;
  
}


function js_carrega() {
    
  new Ajax.Request('mer4_mer_cardapiodia_combo003.php?mes='+document.form1.mes.value+
                   '&cardapio='+document.form1.cardapio.value,
                    {
                     method : 'get',
                     onComplete : function(transport) {
                       document.form1.semana.innerHTML = transport.responseText;
                     }
                    } 
                  );
}

function js_processa() {
    
  cardapio   = document.form1.cardapio.value;
  mes        = document.form1.mes.value;
  semana     = document.form1.semana.value;
  if ((cardapio!='0') && (semana!='') && (mes!='0')) {
      
    page ='mer2_mer_relathorario002.php?semana='+semana+'&mes='+mes+'&cardapio='+cardapio+'&codescola='+document.form1.select_escola.value;
    jan  = window.open(page,'',
                       'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0'
                      );
    jan.moveTo(0,0);
    
  }  
}
<?//if ($clmer_cardapio->numrows>0) {?>

    //document.form1.cardapio.options[1].selected = true;
    //js_cardapio(document.form1.cardapio.value);
    
<?//}?>
</script>