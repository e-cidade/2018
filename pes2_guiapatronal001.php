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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhrubricas_classe.php");
include("dbforms/db_classesgenericas.php");
$gform = new cl_formulario_rel_pes;
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
  selecionados = "";
  virgula_ssel = "";
  if(document.form1.sselecionados.length == 0 ){
    alert('Você deve selecionar pelo menos 1 item!');
    return;
  }
  for(var i=0; i<document.form1.sselecionados.length; i++){
    selecionados+= virgula_ssel + document.form1.sselecionados.options[i].value;
    virgula_ssel = ",";
  }
  qry  = "?ano="+document.form1.anofolha.value;
  qry += "&mes="+document.form1.mesfolha.value;
  qry += "&selecao="+document.form1.selecao.value;
  qry += "&tipo="+document.form1.tipo.value;
  qry += "&cod_pagto="+document.form1.cod_pagto.value;
  qry += "&previdencia="+ selecionados;
  jan = window.open('pes2_guiapatronal002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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

  <table  align="center" >
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="right" nowrap title="Digite o Ano / Mes de competência" >
        <?
        $gform->selecao = true;
        $gform->desabam = false;
        $gform->manomes = true;
        $gform->gera_form(db_anofolha(),db_mesfolha());
        ?>
        </td>
      </tr>
      
      <tr>
        <td align="right" >
          <b>Arquivo :</b>
        </td>
        <td >
         <?
           $xx = array("s"=>"Salário","d"=>"13o. Salário");
           db_select('tipo',$xx,true,4,"");
         ?>
	
	      </td>
      </tr>
      <tr>
        <td align="right">
           <strong>Código de Pagamento :</strong>
        </td>
        <td align="left" >
          <?
          $cod_pagto = '2402';
          db_input('cod_pagto',4,'2402',true,'text',2,'');
          ?>
        </td>
      </tr>
      <tr >
        <td align="center" colspan="2" >
         <?
	       $sql = "select distinct r33_codtab,
                        r33_nome 
                 from inssirf 
                 where r33_anousu = $anofolha
                   and r33_mesusu = $mesfolha
                   and r33_codtab > 2 
                   and r33_instit = ".db_getsession('DB_instit') ;
	        $res = pg_query($sql);
        db_multiploselect("r33_codtab", "r33_nome", "nselecionados", "sselecionados", $res, array(), 4, 250);
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
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>