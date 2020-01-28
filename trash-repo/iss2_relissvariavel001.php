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
<script>
    function js_emite(){
      var data1, data2;
      data1 = document.form1.data1_ano.value+'/'+document.form1.data1_mes.value+'/'+document.form1.data1_dia.value;
      data2 = document.form1.data2_ano.value+'/'+document.form1.data2_mes.value+'/'+document.form1.data2_dia.value;
      if( data1 == "//" || data2 == "//"){
        alert("Favor selecionar o período.");
        document.form1.dtjs_data1.click();
        return false;
      }
      window.open('iss2_relissvariavel002.php?data1='+data1+'&data2='+data2+'&ordenar='+document.form1.ordenar.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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

  <table  align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
      </tr>
       <tr>
         <td colspan=2  align="center">
         </td>
       </tr>
      <tr >
        <td colspan=2 >
            <table align="center" >
              <tr>
                <td nowrap title="11" width="550">
                  <fieldset><Legend><strong>&nbsp;Preencha as Informações&nbsp;</strong></legend>
                  <table border="0" align="center">
                    <tr>
                      <td>&nbsp;</td>
                        <td align="left" nowrap title="Período">
                        <strong>Período:&nbsp;&nbsp;</strong>
                        </td>
                        <td>
                          <?db_inputdata('data1',@$dia1,@$mes1,@$ano1,true,'text',1,"")?>
                          Até
                          <?db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"")?>
                        </td>
                      <td>&nbsp;</td>
                    </tr>
                    <!--
                    <tr>
                        <td>&nbsp;</td>
                        <td align="left" nowrap title="Usuário">
                            <strong>Tipo de Usuário:&nbsp;&nbsp;</strong>
                        </td>
                        <td>
                            <select class="digitacgccpf" name="tipousuario" id="tipousuario" >
                              <option value="">Geral</option>
                              <option value="0">Interno</option>
                              <option value="1">Externo</option>
                            </select>
                        </td>
                        <td>&nbsp;</td>
                    </tr>-->
                    
                    <tr>
                        <td>&nbsp;</td>
                        <td align="left" nowrap title="Ordem">
                            <strong>Ordenar por:&nbsp;&nbsp;</strong>
                        </td>
                        <td>
                            <select class="digitacgccpf" name="ordenar" id="ordernar" >
                              <option value="1">Data/Hora de Lançamento</option>
                              <option value="2">Inscrição</option>
                            </select>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                  </table>
                  </fieldset>
                </td>
              </tr>
            </table>
       </td>
      </tr>
      <tr>
        <td colspan="2" align = "center">
          <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>
    </form>
   </table>
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

?>
</body>
</html>