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
require_once('libs/db_utils.php');
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
    <table valign="top" marginwidth="0" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
          <center>
            <br><br><br>
            <fieldset style='width: 35%;'> <legend><b>Relatório de Laboratórios</b></legend>
              <form name='form1'>
                <table>
                  <br>
                  <tr>  
                    <td align="right">
                      <b>Imprimir Exames:</b>   
                    </td>
                    <td align="left">
                      <input type="checkbox" name="imprimirexames" id="imprExames" value="imprimir" checked> 
                    </td>
                  </tr>
                  <tr>  
                    <td align="right">
                      <b>Situação Exames:</b>
                    </td>
                    <td align="left">
                      <?$aX = array(0=>'TODOS', 1=>'ATIVOS', 2=>'DESATIVADOS');
                      db_select('situacao',$aX,true,1,"");?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" align='center' >
                      <br>
                      <input name='start' type='button' value='Gerar Relatório' onclick="js_mandaDados()">
                    </td>
                  </tr>
                </form>
              </table>
            </fieldset>
          </center>
        </td>
      </tr>
    </table>
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

  function js_mandaDados() {

    imprimir = 'imprimirExames=';
    if (document.form1.imprExames.checked == true) {
      imprimir += "1";
    } else {
      imprimir += "0";
    }
    situacao = '&situacaoExames=' + document.form1.situacao.value;
    jan = window.open('lab2_laboratorioexame002.php?'+ imprimir + situacao,
                      '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                     );
    jan.moveTo(0,0);
 
  }

</script>