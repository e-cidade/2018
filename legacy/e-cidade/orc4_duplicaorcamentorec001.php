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
include ("libs/db_libcontabilidade.php");
include ("libs/db_liborcamento.php");

include ("dbforms/db_funcoes.php");
include ("dbforms/db_classesgenericas.php");

include ("classes/db_orcfontes_classe.php");
include ("classes/db_orcfontesdes_classe.php");
include ("classes/db_orcreceita_classe.php");

$cliframe_seleciona = new cl_iframe_seleciona;

$clorcfontes = new cl_orcfontes;
$clorcfontesdes = new cl_orcfontesdes;
$clorcreceita = new cl_orcreceita;

db_postmemory($HTTP_POST_VARS);
$debug = false;

$anousu = db_getsession("DB_anousu");
$dt_ini = $anousu.'-01-01';
$dt_fin = $anousu.'-12-31';

$anousu_ant = (db_getsession("DB_anousu") - 1);
$dt_ini_ant = $anousu_ant.'-01-01';
$dt_fin_ant = $anousu_ant.'-12-31';

$sqlerro = false;
$doc = "";
$db_opcao = 22;
$db_botao = false;
$erro = false;

// orgaos
if (isset ($processa_fontes) && ($processa_fontes == 'Processar')) {
	// obtem uma matriz de chaves
	$chaves = split('#', $chaves);
	if (count($chaves) > 0) {
		db_inicio_transacao();
		for ($i = 0; $i < count($chaves); $i ++) {
			if ($chaves[$i] == "")
				continue;
			// seleciona orgaos e insere no exercicio alvo	
			$res = $clorcfontes->sql_record($clorcfontes->sql_query_file($chaves[$i],$anousu_ant ));
			if (($clorcfontes->numrows) > 0) {
				db_fieldsmemory($res, 0);
				$clorcfontes->o57_anousu = $anousu;
				$clorcfontes->o57_codfon  = $o57_codfon;
				$clorcfontes->o57_fonte    = $o57_fonte;
				$clorcfontes->o57_descr  = $o57_descr;
				$clorcfontes->o57_finali    = $o57_finali;				
				$clorcfontes->incluir($o57_codfon,$anousu);
				if ($clorcfontes->erro_status == '0') {
					db_msgbox($clorcfontes->erro_msg);
					$erro = true;
					break;
				}
			}
		} // END FOR
		db_fim_transacao($erro);
	} // END IF
}
// unidades
if (isset ($processa_fontesdes) && ($processa_fontesdes == 'Processar')) {
	$chaves = split('#', $chaves);
	if (count($chaves) > 0) {
		db_inicio_transacao();
		for ($i = 0; $i < count($chaves); $i ++) {
			if ($chaves[$i] == "")
				continue;
			$res = $clorcfontesdes->sql_record($clorcfontesdes->sql_query_file($anousu_ant,$chaves[$i]));
			if (($clorcfontesdes->numrows) > 0) {
				db_fieldsmemory($res, 0);
				$clorcfontesdes->o60_anousu = $anousu;
				$clorcfontesdes->o60_codfon  = $o60_codfon;
				$clorcfontesdes->o60_perc      = $o60_perc;				
				$clorcfontesdes->incluir($anousu,$o60_codfon);
				if ($clorcfontesdes->erro_status == '0') {
					db_msgbox($clorcfontesdes->erro_msg);
					$erro = true;
					break;
				}
			}
		} // 
		db_fim_transacao($erro);
	} //
} //
// programa
if (isset ($processa_receitas) && ($processa_receitas == 'Processar')) {
	$chaves = split('#', $chaves);
	if (count($chaves) > 0) {
		db_inicio_transacao();
		for ($i = 0; $i < count($chaves); $i ++) {
			if ($chaves[$i] == "")
				continue;
			$res = $clorcreceita->sql_record($clorcreceita->sql_query_file($anousu_ant, $chaves[$i]));
			if (($clorcreceita->numrows) > 0) {
				db_fieldsmemory($res, 0);
				$clorcreceita->o70_anousu = $anousu;
				$clorcreceita->o70_codrec  = $o70_codrec;
				$clorcreceita->o70_codfon  = $o70_codfon;
				$clorcreceita->o70_codigo  = $o70_codigo;
				$clorcreceita->o70_valor     = $o70_valor;						
				$clorcreceita->o70_reclan   = $o70_reclan;				
				$clorcreceita->o70_instit     = $o70_instit;
				$clorcreceita->incluir($anousu, $o70_codrec);
				if ($clorcreceita->erro_status == '0') {
					db_msgbox($clorcreceita->erro_msg);
					$erro = true;
					break;
				}
			}
		} // 
		db_fim_transacao($erro);
	} //
} //
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br><br>
<form name="form1" action="" method="POST">

<table border=1 width=100% height=80% cellspacing="0" cellpadding="0" bgcolor="#CCCCCC" valign=top>
<tr>
  <td width=50% valign=top>
  <table width=100% border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC">
  <tr>
   <td>
     <table border=0 align=center>
     <tr>
       <td colspan=3><h3>Duplicação de Receitas ( do exerício <?=(db_getsession("DB_anousu")-1)?> para <?=db_getsession("DB_anousu")?> ) </h3></td>
     </tr>    
     <tr>
       <td colspan=3><b>Cadastros </b> </td>
     </tr>
     <tr>
       <td width=40px> &nbsp; </td>
       <td>Fontes  </td>
       <td><input type='submit' name='processa_fontes' value='Selecionar'   ></td>
     </tr>
     <tr>
       <td width=40px> &nbsp; </td>
       <td>Desdobramentos </td>
       <td><input type='submit' name='processa_fontesdes' value='Selecionar'   ></td>
     </tr>
    
     <tr>
       <td colspan=3><b>Receitas</b> </td>
     </tr>
     <tr>
       <td width=40px> &nbsp; </td>
       <td>Receitas </td>
       <td><input type='submit' name='processa_receitas' value='Selecionar' ></td>
     </tr>   
    </table>
   </td>
  </tr>  
  </table> 
  
   
</td>
<td height=100% width=50% valign=top align=center >
<?


$size_iframe = 400;
if (isset ($processa_fontes) && $processa_fontes == "Selecionar") {
	$sql = "select o57_codfon,o57_fonte,o57_descr
	        	   from orcfontes
		           where o57_anousu=". (db_getsession("DB_anousu") - 1)."                       
		           EXCEPT
		           select o57_codfon,o57_fonte,o57_descr
		           from orcfontes
		           where o57_anousu=".db_getsession("DB_anousu")."                       
		           order by o57_fonte
	          ";
	$sql_marca = "";
	$cliframe_seleciona->campos = "o57_codfon,o57_fonte,o57_descr";
	$cliframe_seleciona->legenda = "Fontes";
	$cliframe_seleciona->sql = $sql;
	$cliframe_seleciona->sql_marca = $sql_marca;
	$cliframe_seleciona->iframe_height = $size_iframe;
	$cliframe_seleciona->iframe_width = "100%";
	$cliframe_seleciona->iframe_nome = "cta_fontes";
	$cliframe_seleciona->chaves = "o57_codfon";
	$cliframe_seleciona->iframe_seleciona(1);
?><table border=0 width=100%>
      <tr><td width=100% align=center><input type=button value=Processar onClick="js_processa('fontes');"></td></tr>
      </table>               
    <? 

}
if (isset ($processa_fontesdes) && $processa_fontesdes == "Selecionar") {
	$sql = "select o60_codfon,o57_fonte,o57_descr
	               from orcfontesdes
	           			 inner join orcfontes on o57_codfon=o60_codfon and o57_anousu=o60_anousu
		           where o60_anousu=". (db_getsession("DB_anousu") - 1)."                       
		           EXCEPT
		           select o60_codfon,o57_fonte,o57_descr
		           from orcfontesdes
						 inner join orcfontes on o57_codfon=o60_codfon and o57_anousu=o60_anousu
		           where o60_anousu=".db_getsession("DB_anousu")."                       
		           order by o57_fonte
	          ";
	$sql_marca = "";
	$cliframe_seleciona->campos = "o60_codfon,o57_fonte,o57_descr";
	$cliframe_seleciona->legenda = "Desdobramentos";
	$cliframe_seleciona->sql = $sql;
	$cliframe_seleciona->sql_marca = $sql_marca;
	$cliframe_seleciona->iframe_height = $size_iframe;
	$cliframe_seleciona->iframe_width = "100%";
	$cliframe_seleciona->iframe_nome = "cta_desdobramento";
	$cliframe_seleciona->chaves = "o60_codfon";
	$cliframe_seleciona->iframe_seleciona(1);
?><table border=0 width=100%>
          <tr><td width=100% align=center><input type=button value=Processar onClick="js_processa('fontesdes');"></td></tr>
          </table>               
       <? 

}
if (isset ($processa_receitas) && $processa_receitas == "Selecionar") {
	$sql = "select o70_codrec,o57_fonte,o70_valor,o70_instit
	            from orcreceita
					 inner join orcfontes on o57_anousu=o70_anousu and o70_codfon=o57_codfon
		        where o70_anousu=". (db_getsession("DB_anousu") - 1)."                       
		        EXCEPT
		        select  o70_codrec,o57_fonte,o70_valor,o70_instit
		        from orcreceita
					inner join orcfontes on o57_anousu=o70_anousu and o70_codfon=o57_codfon
		        where o70_anousu=".db_getsession("DB_anousu")."                       
		        order by o57_fonte
	          ";
	$sql_marca = "";
	$cliframe_seleciona->campos = "o70_codrec,o57_fonte,o70_valor,o70_instit";
	$cliframe_seleciona->legenda = "Receitas";
	$cliframe_seleciona->sql = $sql;
	$cliframe_seleciona->sql_marca = $sql_marca;
	$cliframe_seleciona->iframe_height = $size_iframe;
	$cliframe_seleciona->iframe_width = "100%";
	$cliframe_seleciona->iframe_nome = "cta_receitas";
	$cliframe_seleciona->chaves = "o70_codrec";
	$cliframe_seleciona->iframe_seleciona(1);
?><table border=0 width=100%>
          <tr><td width=100% align=center><input type=button value=Processar onClick="js_processa('receitas');"></td></tr>
          </table>               
       <? 

}
?>
</td>
</tr>
</table>


</form>
<?


db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
<script>

function js_processa(tipo){
  js_gera_chaves();
  // cria um objeto que indica o tipo de processamento
  obj=document.createElement('input');
  obj.setAttribute('name','processa_'+tipo);  
  obj.setAttribute('type','hidden');
  obj.setAttribute('value','Processar');
  document.form1.appendChild(obj);
  document.form1.submit();
}
</script>

</body>
</html>