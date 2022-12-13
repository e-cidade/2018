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
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_limpacampos() {
  for(var i = 0;i < document.form1.elements.length;i++)
    if(document.form1.elements[i].type == 'text')
	  document.form1.elements[i].value = '';
}
</script>
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

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<? if(!isset($HTTP_POST_VARS["consultar"])) { ?>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
<center>
      <form name="form1" method="post" target="consulta">
          <table width="89%" border="0" cellspacing="0" cellpadding="0">
            <tr align="center" valign="middle"> 
              <td height="40" colspan="3"><u><em><strong>Consulta de Logs da Internet 
                e Intranet</strong></em></u></td>
            </tr>
            <tr> 
              <td nowrap>&nbsp;</td>
              <td height="20" colspan="2"> <table width="48%" height="20" border="0" cellpadding="0" cellspacing="0">
                  <tr bgcolor="#CCFF99"> 
                    <td width="12%"><strong>Ordenar:</strong></td>
                    <td width="88%" nowrap> <input type="radio" name="ascdesc" value="asc" <?=@$ascdesc=="asc"?"checked":""?>>
                      ascendente&nbsp;&nbsp; <input name="ascdesc" type="radio" value="desc" <? echo !isset($ascdesc)?"checked":$ascdesc=="desc"?"checked":"" ?>>
                      descendente</td>
                  </tr>
                </table></td>
            </tr>
            <tr> 
              <td width="16%" nowrap><strong>Por IP:</strong></td>
              <td width="4%" align="center" valign="middle" bgcolor="#CCFF99"> 
                <input type="radio" name="ordenar" value="ip" <?=@$ordenar=="ip"?"checked":""?>></td>
              <td width="80%"> <input name="ip" type="text" id="ip" value="<?=@$ip?>" size="50" maxlength="50"></td>
            </tr>
            <tr> 
              <td nowrap><strong>Por Data:</strong></td>
              <td align="center" valign="middle" bgcolor="#CCFF99"> <input name="ordenar" type="radio" value="data" <? echo !isset($ordenar)?"checked":$ordenar=="data"?"checked":"" ?>></td>
              <td> 
	        <?
                db_inputdata('data','','','',true,'text',2);
		?>
		      </td>
            </tr>
            <tr> 
              <td nowrap><strong>Por Hora:</strong></td>
              <td align="center" valign="middle" bgcolor="#CCFF99"> <input type="radio" name="ordenar" value="hora" <?=@$ordenar=="hora"?"checked":""?>></td>
              <td><input name="hora" type="text" id="hora" value="<?=@$hora?>" size="10" maxlength="10"></td>
            </tr>
            <tr> 
              <td nowrap><strong>Por Arquivo:</strong></td>
              <td align="center" valign="middle" bgcolor="#CCFF99"> <input type="radio" name="ordenar" value="arquivo" <?=@$ordenar=="arquivo"?"checked":""?>></td>
              <td><input name="Arquivo" type="text" id="arquivo" value="<?=@$Arquivo?>" size="90"></td>
            </tr>
            <tr> 
              <td nowrap><strong>Por Matricula:</strong></td>
              <td align="center" valign="middle" bgcolor="#CCFF99"> <input type="radio" name="ordenar" value="matricula" <?=@$ordenar=="matricula"?"checked":""?>></td>
              <td><input name="matricula" type="text" id="matricula" value="<?=@$matricula?>" size="8" maxlength="8"></td>
            </tr>
            <tr> 
              <td nowrap><strong>Por Inscri&ccedil;&atilde;o:</strong></td>
              <td align="center" valign="middle" bgcolor="#CCFF99"> <input type="radio" name="ordenar" value="inscricao" <?=@$ordenar=="inscricao"?"checked":""?>></td>
              <td><input name="inscricao" type="text" id="inscricao" value="<?=@$inscricao?>" size="6" maxlength="6"></td>
            </tr>
            <tr> 
              <td nowrap><strong>Por Numcgm:</strong></td>
              <td align="center" valign="middle" bgcolor="#CCFF99"> <input type="radio" name="ordenar" value="numcgm" <?=@$ordenar=="numcgm"?"checked":""?>></td>
              <td height="25"><input name="numcgm" type="text" id="numcgm" value="<?=@$numcgm?>" size="8"></td>
            </tr>
            <tr> 
              <td nowrap><strong>Por Observa&ccedil;&otilde;es:</strong></td>
              <td align="center" valign="middle" bgcolor="#CCFF99"> <input type="radio" name="ordenar" value="obs" <?=@$ordenar=="obs"?"checked":""?>></td>
              <td> <input name="obs" type="text" id="obs" value="<?=@$obs?>" size="50"></td>
            </tr>
            <tr> 
              <td nowrap>&nbsp;</td>
              <td>&nbsp;</td>
              <td><input type="submit" name="consultar" value="Consultar"> 
                <input name="limpa" type="button" id="limpa" value="Limpa Campos" onclick="js_limpacampos()"></td>
            </tr>
          </table>
</form>

  <iframe name="consulta" width="730" height="160"></iframe>
</center>
	<?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </td>
</tr>
</table>	
	</td>
  </tr>
</table>
<? } else { ?>
<?
db_postmemory($HTTP_POST_VARS);


	    $query = "select ip,to_char(data,'DD-MM-YYYY') as data,hora,arquivo,matricula,inscricao,numcgm,obs from db_logs WHERE 2 > 1";	   
		if(!empty($ip))
		  $query .= " AND ip like '".$ip."%'";
		if(!empty($data_dia) && !empty($data_mes) && !empty($data_ano))
		  $query .= " AND data >= '".$data_ano."-".$data_mes."-".$data_dia."'";
		if(!empty($hora))
		  $query .= " AND hora like '".$hora."%'";
		if(!empty($arquivo))
		  $query .= " AND arquivo like '".$Arquivo."%'";
		if(!empty($matricula))
		  $query .= " AND matricula like '".$matricula."%'";
		if(!empty($inscricao))
		  $query .= " AND inscricao like '".$inscricao."%'";
		if(!empty($numcgm))
		  $query .= " AND numcgm like '".$numcgm."%'";
		if(!empty($obs))
		  $query .= " AND obs like '".$obs."%'";
		if(empty($obs) && empty($numcgm) && empty($inscricao) && empty($matricula) && empty($arquivo) && empty($hora) && empty($data_dia) && empty($data_mes) && empty($data_ano) && empty($ip))
		  $query .= " AND data >= (CURRENT_DATE - 5)";
		  
		$query .= " ORDER BY ".(!isset($ordenar)?"data":$ordenar)." ".(!isset($ascdesc)?"desc":$ascdesc);

  $filtro = "ordenar=".(@$ordenar==""?"data":@$ordenar)."&ascdesc=".(@$ascdesc==""?"desc":@$ascdesc)."&ip=".@$ip."&data_dia=".@$data_dia."&data_mes=".@$data_mes."&data_ano=".@$data_ano."&hora=".@$hora."&arquivo=".@$arquivo."&matricula=".@$matricula."&inscricao=".@$inscricao."&numcgm=".@$numcgm."&obs=".@$obs;

/*
    if (!isset($offset))
      db_browse($query,'',10,0,str_replace("'","\\'","1&ordenar=".(@$ordenar==""?"data":@$ordenar)."&ascdesc=".(@$ascdesc==""?"desc":@$ascdesc)."&ip=".@$ip."&data_dia=".@$data_dia."&data_mes=".@$data_mes."&data_ano=".@$data_ano."&hora=".@$hora."&arquivo=".@$arquivo."&matricula=".@$matricula."&inscricao=".@$inscricao."&numcgm=".@$numcgm."&obs=".@$obs));
    else
      db_browse($query,'',10,$offset,str_replace("'","\\'","1&ordenar=".(@$ordenar==""?"data":@$ordenar)."&ascdesc=".(@$ascdesc==""?"desc":@$ascdesc)."&ip=".@$ip."&data_dia=".@$data_dia."&data_mes=".@$data_mes."&data_ano=".@$data_ano."&hora=".@$hora."&arquivo=".@$arquivo."&matricula=".@$matricula."&inscricao=".@$inscricao."&numcgm=".@$numcgm."&obs=".@$obs));

*/

db_lov($query,100);
?>
<? } ?>
</body>
</html>