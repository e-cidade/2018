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
include("libs/db_liborcamento.php");
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">


<style>
  select {
    width: 100%;
  }
</style>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<br><br>
<center>
  <form name="form1" id="form1" method="post" action="orc2_despsecretaria002.php">
    <fieldset style="width: 400px;">
      <legend><strong>Despesa por Orgão/Unidade/Elemento</strong></legend>
      <table align="center" border="0">
        <tr>
          <td align="center" colspan="2">
            <?
              db_selinstit('parent.js_limpa',300,100);
            ?>
          </td>
        </tr>
        <tr>
          <td><strong>Fases da Despesa:</strong></td>
          <td>
            <?
              $aFasesDespesa = array('2'=>'Empenhado','3'=>'Liquidado','4'=>'Pago');
              db_select('tipo_balanco', $aFasesDespesa, true, 2);
            ?>
          </td>
        </tr>
        <tr>
          <td><strong>Níveis:</strong></td>
          <td>
            <?
              $aNiveis = array('0' => 'Geral',
                               '3' => 'Função',
                               '4' => 'Subfunção',
                               '5' => 'Programa',
                               '6' => 'Projeto/Atividade',
                               '7' => 'Elemento',
                               '8' => 'Recurso',
                               '9' => 'Desdobramento');
              db_select('nivelele', $aNiveis, true, 2);
            ?>
          </td>
        </tr>
        <tr>
          <td><strong>Agrupar:</strong></td>
          <td>
            <?
              $aTipoAgrupa = array('1' => 'Não',
                                   '2' => 'Órgão',
                                   '3' => 'Unidade');
              db_select('tipoagrupa', $aTipoAgrupa, true, 2);
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center"> 
            <input name="orgaos"      id="orgaos"      type="hidden" value="" >
            <input name="orgaosele"   id="orgaosele"   type="hidden" value="" >
            <input name="vernivel"    id="vernivel"    type="hidden" value="" >
            <input name="vernivelele" id="vernivelele" type="hidden" value="" >
            <input name="filtra_despesa" id="filtra_despesa" type="hidden" value="" >
          </td>
        </tr>
      </table>
    </fieldset>
    <p align="center">
      <input name="emiterelatorio" id="emiterelatorio" value="Gera Relatório" type="button" > 
    </p>
  </form>
</center>


<script>

  var variavel = 0;
  $('emiterelatorio').observe('click', function() {
    
    jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+
                      ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    document.form1.target = 'safo' + variavel++;
    var iNivel                = $('nivelele').value;
    var iFasesDespesa         = $('tipo_balanco').value;
    $('filtra_despesa').value = parent.iframe_filtro.js_atualiza_variavel_retorno();
    
    
    
    setTimeout("document.form1.submit()",1000);
    //$('form1').submit();
    return false;
  });
  
  function js_limpa(){
  
  }

</script>
</body>
</html>