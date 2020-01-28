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
include("dbforms/db_classesgenericas.php");

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
  var obj      = document.form1;
  var data_ini =  obj.data1_ano.value+'-'+obj.data1_mes.value+'-'+obj.data1_dia.value;
  var data_fin =  obj.data2_ano.value+'-'+obj.data2_mes.value+'-'+obj.data2_dia.value;
  var tipo     = obj.tipo.value;
  var itens    = obj.db_usuario.options.length;
  var sQuery   = '';               
  var sUsuSel  = '';
  var sVrg     = '';
  
  for (i = 0;i < itens;i++) {
      sUsuSel = sUsuSel+sVrg+obj.db_usuario.options[i].value;
      sVrg =',';
  }
  
  if (data_fin < data_ini) {
     alert('Datas invalidas');
     return false;
  } else {
    sQuery += '&data1='+data_ini;
    sQuery += '&data2='+data_fin;
    sQuery += '&tipo='+tipo;
    sQuery += '&ususel='+sUsuSel;
    
    jan = window.open('pro2_relcgmaltinc002.php?'+sQuery,
                      '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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

  <table  align="center" border="0">
    <form name="form1" method="post" action="">
      <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td colspan="1">
            <?
              $aux = new cl_arquivo_auxiliar;
              $aux->cabecalho      = "<strong>USUÁRIOS SELECIONADOS PARA ESTE RELATÓRIO</strong>";
              $aux->codigo         = "id_usuario";
              $aux->descr          = "nome";
              $aux->nomeobjeto     = 'db_usuario';
              $aux->funcao_js      = 'js_mostra';
              $aux->funcao_js_hide = 'js_mostra1';
              $aux->sql_exec       = "";
              $aux->func_arquivo   = "func_db_usuarios.php";
              $aux->nomeiframe     = "db_iframe_itens_db_usuario";
              $aux->localjan       = "";
              $aux->db_opcao       = 2;
              $aux->tipo           = 2;
              $aux->top            = 0;
              $aux->linhas         = 5;
              $aux->vwhidth        = 400;
              $aux->funcao_gera_formulario();
            ?>
         </td>
      </tr>
      <tr>
        <td align="left">
         <b> Período : </b>
        </td>
        <td>
         <? 
	         db_inputdata('data1','','','',true,'text',1,"");                 
	         echo "<b> a </b> ";
	         db_inputdata('data2','','','',true,'text',1,"");
         ?>        
        </td>
      </tr>
      <tr>
         <td align="left">
           <b>Tipo modificação: </b>
         </td>
         <td >
	         <select name=tipo>
             <option value="T">Todos</option>
	           <option value="I">Inclusões</option>
	           <option value="A">Alterações</option>           
	         </select>
	       </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>
    </table>
  </form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>