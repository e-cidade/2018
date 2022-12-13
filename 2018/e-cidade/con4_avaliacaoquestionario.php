<?
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,
                widgets/dbtextField.widget.js, dbmessageBoard.widget.js,dbautocomplete.widget.js,
                dbcomboBox.widget.js, datagrid.widget.js, prototype.maskedinput.js, 
                DBViewEstruturaValor.js, DBViewMaterialGrupo.js,
                DBTreeView.widget.js, AjaxRequest.js, classes/questionario/FiltroQuestionario.js");
  db_app::load("estilos.css,grid.style.css");
?>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<style type="text/css">
  td.line{
    border: 1px solid #000;
  }
  ul{
    list-style: none;
  }
  td{
    vertical-align: initial;
  }
</style>
<center>
  <br>
  <table id="FiltroQuestionario" width="790" border="0" cellspacing="0" cellpadding="0">  
  </table>
  <table width="790" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>
      <center>
        <br>
        <input type="button" value="Salvar" id="btn_grupo_areas">
      </center>
      </td>
    </tr>
  </table>
</center>
</body>
</html>
<?
db_postmemory($_POST);
db_postmemory($_GET);
?>

<script type="text/javascript">

  document.observe('dom:loaded', function () {

    oFiltroQuestionario = new FiltroQuestionario('FiltroQuestionario', true, <?php echo $db101_avaliacao;?>);
    $('btn_grupo_areas').observe('click', function(){
      oFiltroQuestionario.save();
    });
  });
</script>