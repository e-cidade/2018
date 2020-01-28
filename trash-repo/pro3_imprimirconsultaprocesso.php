<?php
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oGet     = db_utils::postMemory($_GET);
$sDisplay = 'table-row';
if (!empty($oGet->processo_principal)) {
  $sDisplay = 'none';
}

?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php 
     db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js, DBHint.widget.js');
     db_app::load('estilos.css, grid.style.css');
    ?>
  </head>
  <body style='background-color: #cccccc'>
    <center>
    <div style="width: 200px; ">
      <form action="" method="post">
        <fieldset>
          <legend>
            <b>Imprimir</b>
          </legend>
          <table >
            <tr>
              <td>
                <input type='checkbox' id='movimentacao' value='movimentacao' name='movimentacao' checked="checked">
              <td>
              <td>
                <label for='movimentacao'><b>Movimenta��es</b></label>
              <td>
            </tr>
            <tr style="display: <?php echo $sDisplay?>">
              <td>
                <input type='checkbox' id='apensado' value='apensado' name='apensado' checked="checked">
              <td>
              <td>
                <label for='apensado'><b>Apensados</b></label>
              <td>
            </tr>
          </table>
        </fieldset>
        <br>
        <input type="button" id='imprimir' value='imprimir' name='Imprimir' onclick="js_imprimeRelatorio()">
      </form>
    </div>
    </center>
  </body>
</html>
<script>

function js_imprimeRelatorio() {

  var iCodigoProcesso = '<?php echo $oGet->codigo_processo;?>';

  var sUrl  = "pro2_relconspro002.php?codproc="+iCodigoProcesso;
      sUrl += "&movimentacao="+$F('movimentacao');
      sUrl += "&apensado="+ $F('apensado');

  jan = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

</script>