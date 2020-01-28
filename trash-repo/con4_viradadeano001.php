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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;
db_postmemory($HTTP_POST_VARS);

$anoorigem  = db_getsession("DB_anousu");
$anodestino = $anoorigem + 1;
//$sqlitem = "select * from db_viradacaditem where ";
$sqlitem = " select distinct c33_sequencial, c33_descricao from db_viradacaditem order by c33_sequencial";
        /*
				left join db_viradaitem on c31_db_viradacaditem= c33_sequencial 
				left join db_virada on  c31_db_virada = c30_sequencial 
				                   and c30_anoorigem  = $anoorigem 
				                   and c30_anodestino = $anodestino 
				where c30_sequencial is null";*/


$sqlitem_disabled = "
 select distinct c33_sequencial,c33_descricao 
				from db_viradacaditem 
				left join db_viradaitem on c31_db_viradacaditem= c33_sequencial 
				left join db_virada on  c31_db_virada = c30_sequencial 
				                   and c30_anoorigem  = $anoorigem 
				                   and c30_anodestino = $anodestino 
				where case when $anoorigem = 2012 and c33_sequencial in (11,14,26,27) then true else c30_sequencial is not null and c33_sequencial not in (13, 14, 23) end"; //acrescentado o item 23 CONFIGURAÇÕES PADRÃO DOS RELATÓRIOS LEGAIS

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_processa(){
  objForm = caracteristicas.document.getElementsByTagName('input');
  document.form1.itensprocessa.value ='';
  for (i = 0; i < objForm.length; i++) {
     if (objForm[i].type == 'checkbox') {
       if(objForm[i].checked == true){
         document.form1.itensprocessa.value = document.form1.itensprocessa.value+'_'+objForm[i].value

       }
     }    
  }
}
/*
function js_processa(obj){
//  alert('dfasdfasdfasdf');
     if(obj.checked == true){
     
     document.form1.itensprocessa.value = document.form1.itensprocessa.value+'_'+obj.value
     }else{
     alert('fal');
     }
}
*/
function js_emite(){
  obj = document.form1;
  js_OpenJanelaIframe('','db_iframe_relatorio','con4_viradadeano002.php?&lista='+document.form1.itensprocessa.value+'&anoorigem='+document.form1.anoorigem.value+'&anodestino='+document.form1.anodestino.value,'Processamento da Virada '+document.form1.anoorigem.value+' para '+document.form1.anodestino.value,true);
     
} 
 

</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center" border="0" width="800" >
    <form name="form1" method="post" action="">
      <tr>
         <td > <? db_input("itensprocessa", 20, "", "", "hidden", 1)?> </td>
         <td >&nbsp; </td>
      </tr>
      <tr>
        <td align='center'  >
         <b> Ano origem :    <? db_input("anoorigem", 10, "", true, "text", 3)?> </b>
        </td>
		<td><b> Ano destino :<? db_input("anodestino", 10, "", true, "text",3)?> </b></td>		
      </tr>
      <tr>
        <td colspan="2">
      <?
      $cliframe_seleciona->campos  = "c33_sequencial,c33_descricao";
      $cliframe_seleciona->legenda="Itens";
      $cliframe_seleciona->sql=$sqlitem;
      $cliframe_seleciona->sql_disabled=$sqlitem_disabled;
      $cliframe_seleciona->iframe_height ="400";
      $cliframe_seleciona->iframe_width ="700";
      $cliframe_seleciona->iframe_nome ="caracteristicas";
      $cliframe_seleciona->chaves ="c33_sequencial";
      $cliframe_seleciona->dbscript =  "onClick='parent.js_processa()'";
      $cliframe_seleciona->marcador = true;
      $cliframe_seleciona->js_marcador = "parent.js_processa()";
      $cliframe_seleciona->iframe_seleciona(@$db_opcao);    
   ?>
      </td>
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