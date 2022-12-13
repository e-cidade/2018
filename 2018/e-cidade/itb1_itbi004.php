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


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$db_botao=1;
$db_opcao=1;
$clrotulo = new rotulocampo;
$clrotulo->label("j01_matric");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
}
input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="0px" align="center" border="0" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc" style="padding-top:25px;">
    <td align="center" valign="top" bgcolor="#cccccc">
      <form name="form1" method="post" action="itb1_itbidadosimovel001.php?pri=true&abas=1&tipo=<?=@$tipo?>"  onSubmit="return js_verifica_campos_digitados();" >
        <fieldset>
          <legend>
            <strong>I.T.B.I. <?php echo strtoupper($tipo); ?></strong>
          </legend>
       		<table>
         	  <tr>
           		<td>
    	      	  <?php
    	       		  db_ancora("<b>Matrícula :</b>",' js_matri(true); ',1);
    	      	  ?>
    	        </td>
    	        <td>
    	      	  <?php
      	       		db_input('j01_matric',10,$Ij01_matric,true,'text',1,"onchange='js_matri(false)'");
      	      		db_input('z01_nome',40,0,true,'text',3,"","z01_nomematri");
    	      	  ?>
    	       	</td>
    	      </tr>
    		  <script>

    			onLoad = document.form1.j01_matric.focus();

    			function js_testacamp(){
      			  var matri = document.form1.j01_matric.value;

      			  if ( matri == "" ) {
                document.form1.fiscal.disabled = true;
          			alert("Informe um campo para prosseguir!");
          			return false;
      			  }
      			  document.form1.submit();
    			}

    		  </script>
          </table>
        </fieldset>
   		  <br/>
        <input type="button"  name="fiscal" value="Pesquisar" disabled="disabled" onclick="return js_testacamp();" >
  	  </form>
    </td>
  </tr>
</table>
</body>
</html>
<script>
function js_matri(mostra){
  var matri=document.form1.j01_matric.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe3','func_matricitbi.php?valida=true&funcao_js=parent.js_mostramatri|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe3','func_matricitbi.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostramatri1','Pesquisa',false);
  }
}
function js_mostramatri(chave1,chave2){
  document.form1.fiscal.disabled = false;
  document.form1.j01_matric.value = chave1;
  document.form1.z01_nomematri.value = chave2;
  db_iframe3.hide();
}
function js_mostramatri1(chave,erro){

  if ( erro == true ) {

    document.form1.fiscal.disabled = true;
    document.form1.j01_matric.focus();
    document.form1.j01_matric.value = '';
  } else {

    document.form1.fiscal.disabled = false;
    document.form1.z01_nomematri.value = chave;
  }
}
</script>