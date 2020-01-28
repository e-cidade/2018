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
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$oDaocgs_und       = db_utils::getdao('cgs_und');
$oDaotfd_pedidotfd = db_utils::getdao('tfd_pedidotfd');
$db_opcao          = 1;

db_postmemory($HTTP_POST_VARS);

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load(" prototype.js, datagrid.widget.js, strings.js, webseller.js, scripts.js ");
    db_app::load(" grid.style.css, estilos.css ");
    ?>
  </head>
  <body bgcolor="#CCCCCC" onLoad="a=1" >
    <center>
      <table width="790">
        <tr> 
          <td style="padding-top: 20px;"> 
            <center>
              <fieldset style='width: 90%; padding: 4px;'> 
                <legend><b>Indique a Prestadora:</b></legend>
    	          <?
	              require_once("forms/db_frmtfd_agendaprestsaida.php");
	              ?>
              </fieldset>
            </center>
	        </td>
        </tr>
      </table>
    </center>
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
  js_tabulacaoforms("form1", "tf01_i_cgsund", true, 1, "tf01_i_cgsund", true);
</script>