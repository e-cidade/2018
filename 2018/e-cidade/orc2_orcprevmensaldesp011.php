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


require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("libs/db_liborcamento.php");

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

variavel = 1;
function js_emite(){
 // pega dados da func_selorcdotacao_aba.php
 document.form1.filtra_despesa.value = parent.iframe_filtro.js_atualiza_variavel_retorno();
 jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 document.form1.target = 'safo' + variavel++;
 setTimeout("document.form1.submit()",1000);
 return true;
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table  align="center">
    <form name="form1" method="post" action="orc2_orcprevmensaldesp002.php">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" ><strong>Nível :</strong></td>
        <td>
	  <?


$xy = array ('1' => 'Orgão', '2' => 'Unidade','3' => 'Função', '4' => 'Subfunção','5' => 'Programa', '6' => 'Proj/Ativ','7' => 'Elemento','8' => 'Recurso');
db_select('nivel', $xy, true, 2, "");
$db_selinstit = db_getsession("DB_instit");
db_input("db_selinstit",10,0,true,"hidden",3);
?>
        </td>
      </tr>
      <tr>
        <td align="right" ><strong>Bimestre :</strong></td>
        <td>
        <?
        $xx = array ('1' => 'Primeiro', '2' => 'Segundo','3' => 'Terceiro', '4' => 'Quarto','5' => 'Quinto', '6' => 'Sexto');
        db_select('bimestre', $xx, true, 2, "");
        ?>
        </td>
      </tr>
      <tr>
        <td align="right" ><strong>Tipo Impressão :</strong></td>
        <td>
        <?
        $aTipoImp = array ('B' => 'Bimestral', 'M' => 'Mensal');
        db_select('tipoimp', $aTipoImp, true, 2, "");
        ?>
        </td>
      </tr>

       <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
          <input  name="orgaos" id="orgaos" type="hidden" value="" >
          <input  name="vernivel" id="vernivel" type="hidden" value="" >
          <input  name="filtra_despesa" id="filtra_despesa" type="hidden" value="" >
        </td>
      </tr>

  </form>
</table>
</body>
</html>