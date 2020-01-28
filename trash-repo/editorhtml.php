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
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>

</script>
<style type="text/css">
<!--
fieldset {
	border: 1px solid #000000;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
	
<form method="post" enctype="multipart/form-data" name="form1">
  <table width="72%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td nowrap>
       <fieldset><legend>Texto de Entrada:</legend>
        <input name="t1" type="text" id="t14" size="90">
		</fieldset>
	</td>
    </tr>
    <tr> 
      <td><table width="86%" border="0" cellspacing="3" cellpadding="0">
          <tr align="left" valign="top"> 
            <td width="19%" nowrap> 
              <fieldset><legend>Estilo:</legend>
              <input name="negrito" type="checkbox" id="negrito3" value="N">
              Negrito<br>
              <input name="italico" type="checkbox" id="italico3" value="I">
              Itálico<br>
              <input name="sublinhado" type="checkbox" id="sublinhado3" value="S">
              Sublinhado </fieldset></td>
            <td width="26%" nowrap> 
              <fieldset>
              <legend>Alinhamento:</legend>
              <input name="RG_alinhamento" type="radio" value="left" onClick="js_alinhamento()">
              E 
              <input name="RG_alinhamento" type="radio" value="center" onClick="js_alinhamento()">
              C 
              <input name="RG_alinhamento" type="radio" value="right" onClick="js_alinhamento()">
              D 
              <input name="RG_alinhamento" type="radio" value="justify" onClick="js_alinhamento()">
              J </fieldset></td>
            <td width="55%" nowrap> 
              <fieldset>
              <legend>Mensagem de:</legend>
              <label> 
              <input name="RG_cabrod" type="radio" value="CB" onClick="js_cabrod()">
              Cabeçalho</label>
              <br>
              <label> 
              <input type="radio" name="RG_cabrod" value="RP" onClick="js_cabrod()">
              Rodapé</label>
              <br>
              </fieldset></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td> <fieldset><legend>Fonte:</legend>
        <table width="61%" border="0" cellpadding="0" cellspacing="0">
          <tr align="left" valign="baseline" class="tamFonte"> 
            <td width="37%">tipo:</td>
            <td width="15%">cor:</td>
            <td width="48%">tamanho:</td>
          </tr>
          <tr align="left" valign="top"> 
            <td nowrap> 
              <select name="fonte" id="select5">
                <option value="FP">Fonte Padrão</option>
                <option value="Arial, Helvetica, sans-serif">Arial, Helvetica, 
                sans-serif</option>
                <option value="Times New Roman, Times, serif">Times New Roman, 
                Times, serif</option>
                <option value="Courier New, Courier, mono">Courier New, Courier, 
                mono</option>
                <option value="Georgia, Times New Roman, Times, serif">Georgia, 
                Times New Roman, Times, serif</option>
                <option value="Verdana, Arial, Helvetica, sans-serif">Verdana, 
                Arial, Helvetica, sans-serif</option>
                <option value="Geneva, Arial, Helvetica, san-serif">Geneva, Arial, 
                Helvetica, san-serif</option>
              </select>
              &nbsp;</td>
            <td nowrap> 
              <select name="cor" id="select4">
                <option value="CD">Cor Default</option>
                <option value="black" style="background-color:black;color:white">Preto</option>
                <option value="blue" style="background-color:blue;color:white">Azul</option>
                <option value="yellow" style="background-color:yellow;color:white">Amarelo</option>
                <option value="green" style="background-color:green;color:white">Verde</option>
                <option value="red" style="background-color:red;color:white">Vermelho</option>
                <option value="aqua" style="background-color:aqua;color:white">Aqua</option>
                <option value="lime" style="background-color:lime;color:white">Lime</option>
                <option value="maroon" style="background-color:maroon;color:white">Marron</option>
                <option value="navy" style="background-color:navy;color:white">Navy</option>
                <option value="olive" style="background-color:olive;color:white">Oliva</option>
                <option value="purple" style="background-color:purple;color:white">Purple</option>
                <option value="silver" style="background-color:silver;color:white">Prata</option>
                <option value="teal" style="background-color:teal;color:white">teal</option>
              </select>
              &nbsp;</td>
            <td nowrap> 
              <input name="tamanho" type="text" id="tamanho" value="15" size="3" maxlength="3">
              px </td>
          </tr>
        </table>
        </fieldset>
		</td>
    </tr>
    <tr>
      <td> 
        <fieldset><legend>Imagem:</legend>
        <table width="86%" border="0" cellspacing="0" cellpadding="0">
          <tr align="left" valign="baseline"> 
            <td width="46%" nowrap class="tamFonte">arquivo:</td>
            <td width="4%" nowrap class="tamFonte">alt:</td>
            <td width="5%" nowrap class="tamFonte">larg:</td>
            <td width="3%" nowrap class="tamFonte">borda:</td>
            <td width="13%" nowrap class="tamFonte">alinhamento:</td>
            <td width="29%" nowrap class="tamFonte">&nbsp;</td>
          </tr>
          <tr align="left" valign="top"> 
            <td nowrap> 
              <input name="arq_img" type="file" id="arq_img2" size="35" accept="image/gif"></td>
            <td nowrap> 
              <input name="img_altura" type="text" id="img_altura2" size="3" maxlength="3">
              &nbsp;</td>
            <td nowrap> 
              <input name="img_largura" type="text" id="img_largura2" size="3" maxlength="3">
              &nbsp;</td>
            <td nowrap> 
              <input name="img_borda" type="text" id="img_borda2" value="0" size="2" maxlength="2">
              &nbsp;</td>
            <td nowrap> 
              <select name="img_align" id="select3">
                <option value="left">Esquerda</option>
                <option value="right">Direita</option>
                <option value="top">Em Cima</option>
                <option value="middle">No Meio</option>
                <option value="bottom" selected>Em Baixo</option>
              </select></td>
            <td nowrap> 
              <input name="ins_imagem" type="button" id="ins_imagem" value="Inserir Imagem" onClick="js_ins_imagem()"></td>
          </tr>
        </table>
        </fieldset>
      </td>
    </tr>
    <tr> 
      <td>
	  <fieldset><legend>Comandos:</legend>
	  <input name="visualizar" type="button" id="visualizar" value="Visualizar" onClick="js_visualizar()">
        <input name="delete" type="button" id="delete2" value="Del" onClick="js_delete()"> 
        <input name="apagar" type="button" id="apagar2" value="Apagar Tudo" onClick="js_apagar()"> 
        <input name="nova_linha" type="button" id="nova_linha2" value="Nova Linha" onClick="js_novalinha()"> 
        <input name="interpretar" type="button" id="interpretar" value="Interpretar" onClick="js_interpretar()">
        <input name="enviar" type="submit" id="enviar2" value="Salvar">
         <br>
		</fieldset>
      </td>
    </tr>
	<tr> 
      <td height="29"> 
        <fieldset><legend>Sa&iacute;da Interpretado:</legend>
		<p align="center">
        <table border="1" width="60%">
          <tr> 
            <td><div id="di"></div></td>
          </tr>
        </table>
		</p>
		</fieldset>
        </td>
    </tr>
    <tr> 
      <td>
	  <fieldset><legend>Sa&iacute;da Fonte:</legend>
	    <textarea name="resultado" cols="83" rows="10" wrap="VIRTUAL" id="textarea"></textarea>
	  </fieldset>
	  </td>
    </tr>    
  </table>  
  <input type="hidden" name='result_text'>
</form>
	
	
<?      
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
	</td>
  </tr>
</table>
</body>
</html>