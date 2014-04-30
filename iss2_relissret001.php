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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_emite(){
  jmes=document.form1.mes.value;
  /*if(jmes=="mes"){
    alert("Favor selecionar o mês!");
    document.form1.mes.focus();
    return false
  }*/
  var sQueryString = '?ano='+document.form1.ano.value+'&mes='+document.form1.mes.value;
  var aSituacoes   = $$('input[type=checkbox]');
  if (aSituacoes.length == 0) {
  
    alert('Selecione ao menos uma situação para emitir  Relatório');
    return false;
  }
  var sSituacoes = '';
  var sVirgula   = '';
  aSituacoes.each(function(oSituacao, Id) {
      
     if (oSituacao.checked) { 
       
       sSituacoes += sVirgula+""+oSituacao.value;
       sVirgula = ",";
     } 
  });
  sQueryString += '&ordem='+$F('ordernar');
  sQueryString += '&situacoes='+sSituacoes;
  window.open('iss2_relissret002.php'+sQueryString,
             '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');

}

 function js_criames(obj){
   for(i=1;i<document.form1.mes.length;i){
     document.form1.mes.options[i] = null;
   }
   var dth = new Date(<?=date("Y")?>,<?=date("m")?>,'1');
    if(document.form1.ano.options[0].value != obj.value ){
     for(j=1;j<13;j++){
       var dt = new Date(<?=date("Y")?>,j,'1');
       document.form1.mes.options[j] = new Option(db_mes(j),dt.getMonth());
       document.form1.mes.options[j].value = j;
     }
    }else{
     for(j=1;j<dth.getMonth()+1;j++){
       var dt = new Date(<?=date("Y")?>,j,'1');
       document.form1.mes.options[j] = new Option(db_mes(j),dt.getMonth());
       document.form1.mes.options[j].value = j;
     }
   }
 }


</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
  </table>
    <form name="form1" method="post" action="">
      <tr>
        <td colspan=2 >
            <table align="center" >
              <tr>
                <td nowrap title="11" width="400">
                  <fieldset><Legend><strong>Filtros</strong></legend>
                  <table border="0" align="center">
                    <tr>
                      <td><b>
                           Competência:
                           </b></td>
                           <td>
                            <select name="ano" onchange="js_criames(this)">
                            <?
                              $sano = date("Y");
                              if(date("m")==12)
                               $sano ++;
                              //for($ci = $sano; $ci >= ($sano-10); $ci--){
                              for($ci = $sano; $ci >= 2000; $ci--){
                                echo "<option value=".$ci." >$ci</option>";
                              }
                            ?>
                            </select>
                            <select class="digitacgccpf" name="mes" id="mes" >
                              <option value="todos">Todos os Meses</option>
                            </select>
                            <script>
                            js_criames(document.form1.ano);
                            </script>

                      </td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><b>Situacões:</b></td>
                      <td>
                        <fieldset>
                         <input type="checkbox" checked="checked" value='1' id='tipos1'>
                         <label for="tipso1">No Financeiro</label><br>  
                         <input type="checkbox" checked="checked" value='2' id='tipos2'>
                         <label for="tipso2">Anulado</label><br>  
                         <input type="checkbox" checked="checked" value='3' id='tipos3'>
                         <label for="tipso3">Cancelado</label><br>  
                         <input type="checkbox" checked="checked" value='4' id='tipos4'>
                         <label for="tipso4">Já Pago</label><br>  
                         <input type="checkbox" checked="checked" value='5' id='tipos5'>
                         <label for="tipso5">Suspenso</label><br>  
                         <input type="checkbox" checked="checked" value='6' id='tipos5'>
                         <label for="tipso6">Em Aberto</label><br>
                         </fieldset>  
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <b>Ordernar:</b>
                      </td>
                      <td>
                       <?
                         $aOrdem = array('1' => "Competencia", 
                                         '2' => "Nome",
                                         '3' => 'Planilha'  
                                        );
                         db_select("ordernar",$aOrdem, true, 1);
                       ?>
                      </td>
                    </tr>
                  </table>
                  </fieldset>
                </td>
              </tr>
            </table>
       </td>
      </tr>
     </table>
      <center>
      <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
      </center>
    </form>
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

?>
</body>
</html>
<script>
document.getElementById('ordernar').style.width='100%';
</script>