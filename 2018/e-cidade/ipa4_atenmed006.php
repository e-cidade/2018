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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
nome = '<?=$nome?>';  
RO = '<?=$readonly?>';
function js_iniciar() {
  if(RO == '1')
    document.getElementById("texto").value = window.opener.form1.elements[nome].value;
  else
    document.getElementById("texto").value = window.opener.atendimento.form1.elements[nome].value;
}
function js_fechar() {
  if(RO == '1')
    window.opener.form1.elements[nome].value = document.getElementById("texto").value;
  window.close();
}
</script>
</head>

<body bgcolor=#CCCCCC onLoad="js_iniciar()">
<textarea name="texto" cols="70" rows="25" id="texto" <? echo $readonly=="0"?"readonly":"" ?>></textarea>
<br>
<input name="fechar" type="button" onClick="js_fechar()" id="fechar" value="Fechar">
</body>
</html>
<script>
document.getElementById("texto").focus();
</script>