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
include("classes/db_db_ordemorigem_classe.php");

db_postmemory($HTTP_POST_VARS);

$cldb_ordemorigem = new cl_db_ordemorigem;

$db_opcao=1;

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
  obj = document.form1;
  data_ini =  obj.data1_ano.value+'-'+obj.data1_mes.value+'-'+obj.data1_dia.value;
  data_fin =  obj.data2_ano.value+'-'+obj.data2_mes.value+'-'+obj.data2_dia.value;
  tipo = obj.tipo.value;
  ordem = obj.ordem.value;
  origem = obj.codorigem.value;
  if (data_fin < data_ini){
     alert('Datas invalidas');
     return;
  } else {
    jan = window.open('conf2_ordem002.php?&data='+data_ini+'&data1='+data_fin+'&tipo='+tipo+'&ordem='+ordem+'&origem='+origem,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
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

  <table  align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td align='center'  colspan=2 >
         <b> Data da Ordem : </b>
         <? 
         db_inputdata('data1','','','',true,'text',1,"");   		          
         echo "<b> a </b> ";
         db_inputdata('data2','','','',true,'text',1,"");
         ?>
        </td>
      </tr>
      <tr>
         <td ><b>Tipo : </b></td>
         <td >
	   <select name=tipo>
              <option value="1">Não Iniciadas</option>
	      <option value="2">Em Andamento</option>
	      <option value="3">Finalizadas</option>           
	      <option value="4">Nao finalizadas</option>
	   </select>
	 </td>
      </tr>
      <tr>
         <td ><b>Origem  : </b></td>
         <td >
         <?
          $result=$cldb_ordemorigem->sql_record($cldb_ordemorigem->sql_query_file(null,"*","or11_codigo"));
          db_selectrecord("codorigem",$result,true,$db_opcao,"","","","0");
         ?>   


         </td>
      </tr> 
      <tr>
         <td ><b>Ordenado por : </b></td>
         <td >
	   <select name=ordem>
              <option value="codordem">Numero da Ordem</option>
	      <option value="dataordem">Data da Ordem</option>
	      <option value="dataprev">Previsao de Conclusao</option>
	      <option value="u.nome">Solicitante</option>           
	      <option value="q.nome">Destinatario</option>           
	   </select>
	 </td>
      </tr>

      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
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