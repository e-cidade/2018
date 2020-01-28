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
parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
?>
<HTML>
<HEAD>
<TITLE>Pesquia de campos</TITLE>
</HEAD>
<BODY WIDTH="100%" COLOR="#FFFFFF" TEXT="#000000" LINK="#0000FF" VLINK="#000080" ALINK="#FF0000" >
<FORM METHOD="post" NAME="consulta">
<center>
<TABLE WIDTH="100%">
<TR>
<TD >
<SELECT NAME="sel_cliente" SIZE="10" WIDTH="150">
<?
$result = pg_exec($conn,"
	select c.nomecam,c.codcam
	from db_syscampo c
	inner join db_sysarqcamp a
	on a.codcam = c.codcam
	where a.codarq = $tabela
	order by a.seqarq");
$num_linhas = pg_numrows($result);
for($i = 0;$i < $num_linhas;$i++) {
	$nome_campo = pg_fieldname($result,0);
//	$codi_campo = pg_fieldname($result,1);
//	$valor_campo = trim(pg_result($result,$i,$codi_campo));
	$nome_campo = "#".trim(pg_result($result,$i,$nome_campo))."#";
	echo "<OPTION value='$nome_campo'>$nome_campo</OPTION>\n";
}
?>
</SELECT>
<BR>
<INPUT TYPE="submit" NAME="enviar" VALUE="Enviar" onclick="retorna(document.consulta.sel_cliente.options[document.consulta.sel_cliente.selectedIndex].value,'$camp','$tab')">
<INPUT TYPE="button" NAME="cancelar" VALUE="Cancelar" onclick="cancela()">
<script language="javascript">
function retorna(valor) {
	if(!valor)
		alert("Selecione algum campo");
	else {
		aux = new String(parent.document.forms[0].alt_ind.value);
		//aux.replace("\n"," ");
		if(aux.indexOf(valor) != -1)
			alert("Este valor ja existe");
		else {
			valor = valor + '\n';
			parent.document.forms[0].alt_ind.value = parent.document.forms[0].alt_ind.value + valor ;
			parent.db_iframe_campo.hide();
		}
	}	
}
function cancela() {
	parent.db_iframe_campo.hide();
}	
</script>
</TD>
</TR>
</TABLE>
</center>
</form>
</BODY>
</HTML>