<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$oDaoTfdAgendaSaida = db_utils::getdao('tfd_agendasaida');

db_postmemory($HTTP_POST_VARS);

$oDaoTfdAgendaSaida->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("tf03_i_codigo");
$oRotulo->label("tf03_c_descr");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load(" prototype.js, datagrid.widget.js, strings.js, webseller.js, scripts.js ");
    db_app::load(" grid.style.css, estilos.css, dbautocomplete.widget.js ");
    ?>
  </head>
  <body bgcolor="#CCCCCC" >
    <center>
      <table width="580">
        <tr> 
          <td style="padding-top: 40px;"> 
            <center>
              <form name="form1" method="post" action="">
                <fieldset style='width: 40%;'> 
                  <legend><b>Critica dos Dados para exportação:</b></legend> 
                  <table align="center">
                    <tr>
                      <td colspan="6" align="center" style="padding-top: 10px;" nowrap>
                        <input type="button" name="relatorio" id="relatorio" value="Gerar" 
                               onclick="js_gerar();">
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
  </body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), 
        db_getsession("DB_anousu"), db_getsession("DB_instit")
       );
?>
<script>
function js_gerar() {

  jan = window.open('hip2_criticadados002.php',
                    '',
                    'width='+(screen.availWidth-5) +',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                   );
  jan.moveTo(0, 0);

}

</script>