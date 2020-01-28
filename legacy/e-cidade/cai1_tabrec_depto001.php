<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("classes/db_tabdesc_classe.php"));
require_once (modification("classes/db_tabdescarretipo_classe.php"));
require_once (modification("classes/db_tabrecdepto_classe.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("dbforms/db_classesgenericas.php"));
$cliframe_seleciona = new cl_iframe_seleciona;
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$cltabdesc = new cl_tabdesc;
$cltabrecdepto = new cl_tabrecdepto;
$cltabdescarretipo = new cl_tabdescarretipo;
$cltabdesc->rotulo->label();
$clrotulo = new rotulocampo;
$cltabdesc->k07_instit = db_getsession("DB_instit");
$db_botao = true;
if(!isset($db_opcao)){
	$db_opcao = 2;
}

if(isset($incluir) or isset($alterar)){
	db_inicio_transacao();
	$erro = false;
	$db_opcao= 2 ;

	$cltabrecdepto->k98_receit  = $k98_receit;
	$cltabrecdepto->excluir(null, " k98_receit = $k98_receit ");
	if($cltabrecdepto->erro_status="0"){
  	    $erro = true;
		    $msgerro = $cltabrecdepto->erro_msg;
	}

	$depto = split("#",$chaves);
  for($w=0;$w<count($depto);$w++){
    if($erro==false){

			$cltabrecdepto->k98_receit  = $k98_receit;
	    $cltabrecdepto->k98_coddepto = $depto[$w];
      $cltabrecdepto->incluir(null);
      if($cltabrecdepto->erro_status="0"){
  	    $erro = true;
		    $msgerro = $cltabrecdepto->erro_msg;
				break;
      }
	  }
 	}
  db_fim_transacao($erro);
}else if(isset($excluir)){
	db_inicio_transacao();
	$erro = false;
	$db_opcao= 3 ;

	$cltabrecdepto->k98_receit  = $k98_receit;
	$cltabrecdepto->excluir(null, " k98_receit = $k98_receit ");
	if($cltabrecdepto->erro_status="0"){
  	    $erro = true;
		    $msgerro = $cltabrecdepto->erro_msg;
	}
	db_fim_transacao($erro);
}

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
	<center>
<form name="form1" >

<table width="680" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td  colspan="2">&nbsp;
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcodsubrec?>"   >
       <?=@$Lcodsubrec?>
    </td>
    <td>
			<?
			db_input('k98_receit',10,$Icodsubrec,true,'text',3,"")
			?>
			<?
      db_input('k02_descr',50,$Ik07_descr,true,'text',3,"")
      ?>
    </td>
  </tr>
	<tr>
		<td colspan="2" align="center">
		   <?
			$sql = "select coddepto,descrdepto from db_depart where instit = ".db_getsession("DB_instit")." order by descrdepto";
			$sqlmarca = "select coddepto,descrdepto
			             from db_depart
			             inner join tabrecdepto on coddepto = k98_coddepto
			             where instit      = ".db_getsession("DB_instit")."
									   and k98_receit = $k98_receit
									 order by descrdepto";
			$cliframe_seleciona->chaves  = "coddepto";
      $cliframe_seleciona->campos  = "coddepto,descrdepto";
      $cliframe_seleciona->legenda = "Departamentos";
      $cliframe_seleciona->sql     = $sql;
      $cliframe_seleciona->sql_marca = $sqlmarca;
      $cliframe_seleciona->iframe_height ="300";
      $cliframe_seleciona->iframe_width  ="400";
      //$cliframe_seleciona->dbscript      = "";
      $cliframe_seleciona->iframe_nome ="deptos";
			if (!empty($k98_receit)) {
				$cliframe_seleciona->iframe_seleciona($db_opcao);
			}
      ?>
	  </td>
	</tr>

	<?php if (!empty($k98_receit)) : ?>
	<tr>
		<td colspan="2" align="center">
		<? if($db_opcao==3){ $db_botao = false; }?>
 			<input name="<?=($db_opcao==1?"incluir":($db_opcao==2?"alterar":"excluir"))?>" type="submit" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick='js_gera_chaves();' >
		</td>
	</tr>
	<?php endif ?>
</table>
</form>
</center>
</body>
</html>
<?
if(isset($incluir) or isset($alterar) or isset($excluir)){
  if($erro==true){
	  db_msgbox($msgerro);
  }else{
  	if(isset($incluir)){
  		db_msgbox("Influsão efetuada com sucesso!");
  	}elseif(isset($alterar)){
  		db_msgbox("Alteração efetuada com sucesso!");
  	}elseif(isset($excluir)){
  		db_msgbox("Exclusão efetuada com sucesso!");
  	}
  }
}
?>