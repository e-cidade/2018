<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
$oRotulo = new rotulocampo();
$oRotulo->label('db101_sequencial');
?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>  
  
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
  <fieldset>
    <legend>
      Manutenção de Formulários
    </legend>
    <table>
      <tr>
        <td>
          <label id="lblFormularios" for="db101_sequencial">
            <a><b>Formulário</b></a>
          </label>
        </td>
        <td>
          <?php
          db_input('db101_sequencial', 10, $Idb101_sequencial, true, 'text', 1, "");
          db_input('db101_descricao', 40, $Idb101_sequencial, true, 'text', 3, "");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input type="button" value="Próximo" id="btnProximo">
  
</div>
</body>
</html>
<?php
db_menu();
?>
<script>
  
  var oLookUpFormulario = new DBLookUp($('lblFormularios'), $('db101_sequencial'), $('db101_descricao'), {
    'sArquivo'      : 'func_avaliacao.php',
    'sObjetoLookUp' : 'db_iframe_formularios',
    'sLabel'        : 'Pesquisar Formulários',
    'sQueryString'  : '&todos=true&editaveis=true',
    'oBotaoParaDesabilitar': $('btnProximo')
  });
  oLookUpFormulario.b 
  $('btnProximo').observe('click', function() {
      
    if (empty($F('db101_sequencial'))) {
      
      alert('Campo Formulário é de preenchimento obrigatório.');
      return;
    }
    location.href='con4_manutencaoformulario001.php?formulario='+$F('db101_sequencial');    
  }); 
</script>
