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

//////////INCLUIR/////////////
if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($HTTP_POST_VARS);
  $naolibclass = (!isset($naolibclass)?'f':'t');
  $naolibfunc  = (!isset($naolibfunc)?'f':'t');
  $naolibprog  = (!isset($naolibprog)?'f':'t');
  $naolibform  = (!isset($naolibform)?'f':'t');
	
  if(!checkdate($dataincl_mes,$dataincl_dia,$dataincl_ano))
    db_erro("Data inválida(insert)");
  else
    $data = $dataincl_ano."-".$dataincl_mes."-".$dataincl_dia;
  db_query("BEGIN");
  $result = db_query("select nextval('db_sysarquivo_codarq_seq')");
  $codarq = pg_result($result,0,0);
  db_query("insert into db_sysarquivo values ($codarq,
			                     '$nomearq',
				             '$descricao',
				             '$sigla',
				             '$data',
				             '$rotulo',
					     $tipotabela,
					     '$naolibclass',
					     '$naolibfunc',
					     '$naolibprog',
					     '$naolibform'
					     )") or die("Erro(31) inserindo em db_sysarquivo");
  db_query("insert into db_sysarqmod values ($modulo,$codarq)") or die("Erro(34) inserindo em db_sysarqmod");
  if($tabelapai!=0){
    db_query("insert into db_sysarqarq values ($tabelapai,$codarq)") or die("Erro(34) inserindo em db_sysarqarq");
  }
  db_query("END");
  db_redireciona();
////////////////ALTERAR////////////////  
} else if(isset($HTTP_POST_VARS["alterar"])) {
  db_postmemory($HTTP_POST_VARS);
  $naolibclass = (isset($naolibclass)?'t':'f');
  $naolibfunc  = isset($naolibfunc)?'t':'f';
  $naolibprog  = isset($naolibprog)?'t':'f';
  $naolibform  = isset($naolibform)?'t':'f';
	
  if(!checkdate($dataincl_mes,$dataincl_dia,$dataincl_ano))
    db_erro("Data inválida(update)");
  else
    $data = $dataincl_ano."-".$dataincl_mes."-".$dataincl_dia;  
  db_query("BEGIN");
  $sql = "update db_sysarquivo set nomearq   = '$nomearq',
                                    descricao = '$descricao',
                                    sigla     = '$sigla',
				    dataincl  = '$data',
				    rotulo  = '$rotulo',
				    tipotabela = $tipotabela,
				    naolibclass = '$naolibclass',
				    naolibfunc = '$naolibfunc',
				    naolibprog = '$naolibprog',
				    naolibform = '$naolibform'
				  where codarq = $codarq";
  db_query($sql) or die("Erro(49) alterando db_sysarquivo");
  db_query("UPDATE db_sysarqmod  SET codmod = $modulo
		   WHERE codarq = $codarq") or die("Erro(51) atualizando db_sysarqmod");
  db_query("delete from db_sysarqarq where codarq = $codarq") or die("Erro(34) deletando db_sysarqarq");
  db_query("insert into db_sysarqarq values($tabelapai,$codarq)") or die("Erro(34) inserindo em db_sysarqarq");
  db_query($conn,"END");
  db_redireciona();
////////////////EXCLUIR//////////////
} else if(isset($HTTP_POST_VARS["excluir"])) {
  db_query("BEGIN");
  db_query("delete from db_sysarqarq  where codarq = ".$HTTP_POST_VARS["codarq"]) or die("Erro(57) escluindo db_sysarqarq");
  db_query("delete from db_sysarqmod  where codarq = ".$HTTP_POST_VARS["codarq"]) or die("Erro(57) escluindo db_sysarqmod");
  db_query("delete from db_sysarquivo where codarq = ".$HTTP_POST_VARS["codarq"]) or die("Erro(58) excluindo em db_sysarquivo");
  db_query("END");
  db_redireciona();
}
parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
if(isset($retorno)) {
  $sql = "select m.codmod,a.codarq,a.nomearq,a.descricao,a.sigla,a.rotulo,aq.codarqpai,a.naolibclass,a.naolibfunc,a.naolibprog,a.naolibform,
          to_char(a.dataincl,'DD') as dataincl_dia,to_char(a.dataincl,'MM') as dataincl_mes,to_char(a.dataincl,'YYYY') as dataincl_ano, a.tipotabela
          from db_sysarquivo a
		       left outer join db_sysarqarq aq on aq.codarq = a.codarq
		  inner join db_sysarqmod m
		  on m.codarq = a.codarq
		  where a.codarq = $retorno";
  $result = db_query($sql);
  db_fieldsmemory($result,0);
}


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
Botao = 'incluir';
function js_submeter(obj) {
  if(Botao != 'procurar') {  
    if(obj.nomearq.value == "") {
      alert("Campo nome da tabela é obrigatório");
	  obj.nomearq.focus();
	  return false;
    }
	if(obj.descricao.value == "") {
      alert("Campo descrição é obrigatório");
	  obj.descricao.focus();
	  return false;
    }
	if(obj.dataincl_dia.value == "" || obj.dataincl_mes.value == "" || obj.dataincl_ano.value == "") {
	  alert("Campo data vazio ou inválido!");
	  obj.dataincl_dia.focus();
	  return false;
	}
	/*
	if(obj.sigla.value == "") {
	  alert("Campo sigla é obrigatório");
	  obj.sigla.focus();
	  return false;
	}
	*/
  }
  return true;
}
function js_iniciar() {
  if(document.form1)
    document.form1.nomearq.focus()
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect();js_iniciar()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br>
<table width="790" height="100%" align='center' border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
	<?
      if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {	  
	     $sql = "SELECT codarq as db_codarq,codarq ,nomearq , descricao ,sigla
              FROM db_sysarquivo
			  WHERE nomearq like '".$HTTP_POST_VARS["nomearq"]."%'
              ORDER BY nomearq";
		db_lov($sql,15,"sys1_tabelas001.php"); 
	  } else {
	 ?>
      <form name="form1" method="post" onSubmit="return js_submeter(this)" action="">
	  <input type="hidden" name="codarq" value="<?=@$codarq?>">
        <table border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td><strong>Nome:</strong></td>
            <td><input type="text" name="nomearq" value="<?=@$nomearq?>"></td>
          </tr>
          <tr> 
            <td><strong>Label:</strong></td>
            <td><input name="rotulo" type="text" id="rotulo" value="<?=@$rotulo?>" size="40" maxlength="50"></td>
          </tr>
          <tr> 
            <td><strong>Descrição:&nbsp;</strong></td>
            <td><textarea name="descricao" rows="7" cols="40"><?=@$descricao?></textarea></td>
          </tr>
          <tr> 
            <td><strong>Data de Inclusão:&nbsp;</strong></td>
            <td> 
              <?
        include("dbforms/db_funcoes.php");
	    $dataincl_dia = date("d");
	    $dataincl_mes = date("m");
	    $dataincl_ano = date("Y");
	    db_inputdata("dataincl",@$dataincl_dia,@$dataincl_mes,@$dataincl_ano,1,'txet',2);
	    ?>
            </td>
          </tr>
          <tr> 
            <td><strong>Sigla:</strong></td>
            <td nowrap> <input type="text" name="sigla" value="<?=@$sigla?>">
              &nbsp;&nbsp; </td>
          </tr>
          <tr>
            <td> <strong>Módulo:&nbsp;</strong> </td>
            <td nowrap><select name="modulo" size="1">
                <?
		  function db_retSelected($valor) {
		    global $HTTP_POST_VARS;
			global $retorno;
			global $codmod;
		    if(isset($HTTP_POST_VARS["modulo"])) {
			  if($valor == $HTTP_POST_VARS["modulo"])
			    return "selected";
			} else if(isset($retorno)) {
			  if($valor == $codmod)
			    return "selected";
			}
             return "";
		  }
		  $result = db_query("select codmod,nomemod from db_sysmodulo where ativo is true order by nomemod");
		  $numrows = pg_numrows($result);
		  for($i = 0;$i < $numrows;$i++)
		    echo "<option value=\"".pg_result($result,$i,"codmod")."\" ".db_retSelected(pg_result($result,$i,"codmod")).">".pg_result($result,$i,"nomemod")."</option>\n";
		  ?>
              </select></td>
          </tr>
          <tr>
            <td title="Tipo da tabela."><strong>Tipo Tabela:</strong></td>
            <td title="Tipo da tabela." nowrap>
	      <select name="tipotabela" size="1" id="tipotabela">
	        <option value="0" <?=(@$tipotabela=="0"?"selected":"")?>>Manutenção</option>
	        <option value="1" <?=(@$tipotabela=="1"?"selected":"")?>>Parâmetro</option>
	        <option value="2" <?=(@$tipotabela=="2"?"selected":"")?>>Dependência</option>
              </select></td>
          </tr>
 
          <tr>
            <td title="Quando a chave primaria for igual da tabela pai."><strong>Tabela Pai:</strong></td>
            <td title="Quando a chave primaria for igual da tabela pai." nowrap>
			<select name="tabelapai" size="1" id="select">
		    <option value="0">Nenhuma...</option>
                <?
		  function db_retSelecteda($valor) {
		    global $HTTP_POST_VARS;
			global $retorno;
			global $codarqpai;
		    if(isset($HTTP_POST_VARS["tabelapai"])) {
			  if($valor == $HTTP_POST_VARS["tabelapai"])
			    return "selected";
			} else if(isset($retorno)) {
			  if($valor == $codarqpai)
			    return "selected";
			}
             return "";
		  }
		  $result = db_query("select codarq,nomearq 
		                     from db_sysarquivo  
							 order by nomearq");
		  $numrows = pg_numrows($result);
		  for($i = 0;$i < $numrows;$i++)
		    echo "<option value=\"".pg_result($result,$i,"codarq")."\" ".db_retSelecteda(pg_result($result,$i,"codarq")).">".pg_result($result,$i,"nomearq")."</option>\n";
		  ?>
              </select></td>
          </tr>
	  <tr>
            <td valign="top"><strong>Gerador Programa:</strong></td>
	  <td>
	        <input name="naolibclass" type="checkbox" value="" <?=(@$naolibclass =='t'?'checked':'')?>>&nbsp <strong>Não Libera Classe </strong><br>
	        <input name="naolibfunc" type="checkbox" value=""  <?=(@$naolibfunc  =='t'?'checked':'')?>>&nbsp <strong>Não Libera Função</strong> <br>
	        <input name="naolibform" type="checkbox" value=""  <?=(@$naolibform  =='t'?'checked':'')?>>&nbsp <strong>Não Libera Formulário </strong><br>
	        <input name="naolibprog" type="checkbox" value=""  <?=(@$naolibprog  =='t'?'checked':'')?>>&nbsp <strong>Não Libera Programa </strong>
          </td>
	  </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <input name="incluir" onClick="Botao = 'incluir'" accesskey="i" type="submit" id="incluir2" value="Incluir" <? echo isset($retorno)?"disabled":"" ?>> 
              &nbsp; <input name="alterar" accesskey="a" type="submit" id="alterar2" value="Alterar" <? echo !isset($retorno)?"disabled":"" ?>> 
              &nbsp; <input name="excluir" accesskey="e" type="submit" id="excluir2" value="Excluir" onClick="return confirm('Quer realmente excluir este registro?')" <? echo !isset($retorno)?"disabled":"" ?>> 
              &nbsp; <input name="procurar" onClick="Botao = 'procurar'" accesskey="p" type="submit" id="procurar2" value="Procurar">	
              <? if(isset($retorno)) { ?>
              &nbsp; <input type="button" onClick="location.href='sys3_campos001.php?<? echo base64_encode("tabelacod=$retorno&manutabela=true") ?>'" value="Mais Detalhes"> 
              &nbsp; <input type="button" onClick="location.href='sys1_camposnovo001.php?iTabela=<?php echo $retorno; ?>'" value="Lançar Campos"> 
              <? } ?>
            </td>
          </tr>
        </table>
      </form>
      <?
	  } // fim do else do if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {
	  
    ?>
    </td>
  </tr>
</table>
<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>	
</body>
</html>