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
include("classes/db_rhrubricas_classe.php");
$clrhrubricas = new cl_rhrubricas;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_verifica(){
  var anoi = new Number(document.form1.datai_ano.value);
  var anof = new Number(document.form1.dataf_ano.value);
  if(anoi.valueOf() > anof.valueOf()){
    alert('Intervalo de data invalido. Velirique !.');
    return false;
  }
  return true;
}


function js_emite(){
  erro = 0;

  datInici = document.form1.datai_ano.value + "-" + document.form1.datai_mes.value + "-" + document.form1.datai_dia.value;

  dtDatInici = new Date(document.form1.datai_ano.value, document.form1.datai_mes.value, document.form1.datai_dia.value);
  if(document.form1.datai_ano.value == "" || document.form1.datai_mes.value == "" || document.form1.datai_dia.value == ""){
    datFinal = "";
    alert("Informe a data inicial!");
    erro ++;
    document.form1.datai_dia.focus();
  }else if(document.form1.dataf_ano.value != "" && document.form1.dataf_mes.value != "" && document.form1.dataf_dia.value != ""){
    datFinal = document.form1.dataf_ano.value + "-" + document.form1.dataf_mes.value + "-" + document.form1.dataf_dia.value;
    dtDatFinal = new Date(document.form1.dataf_ano.value, document.form1.dataf_mes.value, document.form1.dataf_dia.value);
    if(dtDatInici > dtDatFinal){
      alert("Período de data inválido!");
      erro ++;
      document.form1.dataf_dia.focus();
    }
  }

  if(erro == 0){
    qry = "?dataIni=" + datInici;
    qry+= "&dataFim=" + datFinal;
    qry += "&tipo="+document.form1.h12_assent.value;
  }
  jan = window.open('rec2_alerelportaria002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table align="center">
      <tr>
         <td >&nbsp;</td>
      </tr>
  <tr>
  <td>
   <fieldset>
    <Legend align="left">
     <b>Portarias Por Período/Tipo</b>
    </Legend>
  <table  align="center" border="0">
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr> 
        <td nowrap>
          <b>Período:</b>
        </td>
        <td nowrap>
          <?
          db_inputdata('datai',@$datai_dia,@$datai_mes,@$datai_ano,true,'text',1,"")
          ?>
          &nbsp;<b>a</b>&nbsp;
          <?
          db_inputdata('dataf',@$dataf_dia,@$dataf_mes,@$dataf_ano,true,'text',1,"")
          ?>
        </td>
      </tr>
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>

      <tr >
        <td align="center" colspan="2" >
         <?
	       $sql = "select h12_assent, h12_descr
                 from tipoasse 
                 order by h12_descr"; 
	        $res = pg_query($sql);
        db_selectrecord("h12_assent", $res, true, 4);
         ?>
        </td>
      </tr>
      
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
 </fieldset>
 </td>
 </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>