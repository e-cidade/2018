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

session_start();
parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

//var_dump(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
//die();
if(!session_is_registered("DB_instit")) {
  session_destroy();
  echo "<script>location.href='index.php'</script>";
  exit;
}
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require("dbforms/db_funcoes.php");
require("model/configuracao/ItensMenu.php");

if(isset($modulo) and is_numeric($modulo)){

  $sSqlAreaModulo  = " select at26_codarea ";
  $sSqlAreaModulo .= "   from atendcadareamod ";
  $sSqlAreaModulo .= "  where at26_id_item = $modulo ";

  $rsSqlAreaModulo = @db_query($sSqlAreaModulo);

  $iNumAreaModulo = @pg_num_rows($rsSqlAreaModulo);

  if($iNumAreaModulo > 0){

    db_fieldsMemory($rsSqlAreaModulo,0);
    db_putsession("DB_Area",$at26_codarea);
  }

}

if(!session_is_registered("DB_modulo")){
  session_register("DB_modulo");
}

//db_putsession("DB_Area",$modulo);

if(!session_is_registered("DB_nome_modulo"))
session_register("DB_nome_modulo");
if(!session_is_registered("DB_anousu"))
session_register("DB_anousu");
if(!session_is_registered("DB_datausu")) {
  session_register("DB_datausu");
  db_putsession("DB_datausu",time());
}
//if(session_is_registered("DB_coddepto")) {
  //  session_unregister("DB_coddepto");
//}

if(!isset($HTTP_POST_VARS["formAnousu"]) && !isset($retorno)) {
  db_putsession("DB_modulo",$modulo);
  db_putsession("DB_nome_modulo",$nomemod);
  db_putsession("DB_anousu",$anousu);
} else if(isset($HTTP_POST_VARS["formAnousu"]) && $HTTP_POST_VARS["formAnousu"] != ""){
  db_putsession("DB_anousu",$HTTP_POST_VARS["formAnousu"]);
  //============================================================
  //descomentar esta linha para colocar as data em 2005
  //   db_putsession("DB_datausu",mktime(0, 0, 0, 1, 2, 2005));

}

// se o exercicio nao for selecionado no modulo, esta acessando o módulo
if( !isset($HTTP_POST_VARS["formAnousu"])) {

  // se o ano da data do exercicio for diferente  do anousu registrado, o sistema utiliza como padrao o anousu da data

  if( db_getsession("DB_anousu") != date("Y",db_getsession("DB_datausu")) ){
    db_putsession("DB_anousu" , date("Y",db_getsession("DB_datausu")) );
  }

}
$nomemod = db_getsession("DB_nome_modulo");
db_query("update db_usuariosonline set
                                      uol_arquivo = '',
                                      uol_modulo = '".$nomemod."',
                                      uol_inativo = ".time()."
          where uol_id = ".db_getsession("DB_id_usuario")."
            and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."'
            and uol_hora = ".db_getsession("DB_uol_hora")) or die("Erro(26) atualizando db_usuariosonline");

$result = db_query("select id_item from db_usumod
                    where id_usuario = ".db_getsession("DB_id_usuario")."
                      and id_item = ".db_getsession("DB_modulo"));
if(pg_numrows($result) == 0) {
  db_query("insert into db_usumod values(".db_getsession("DB_modulo").",".db_getsession("DB_anousu").",".db_getsession("DB_id_usuario").")") or die("Erro(40) inserindo em db_usumod: ".pg_errormessage());
} else {
  db_query("update db_usumod set id_item = ".db_getsession("DB_modulo").",
  anousu = ".db_getsession("DB_anousu")."
  where id_usuario = ".db_getsession("DB_id_usuario")." and id_item = ".db_getsession("DB_modulo"));
}

/**
 * Salva cache com os menus do modulo, caso não existam ainda.
 */
$oItensMenu = new ItensMenu(db_getsession("DB_modulo"), db_getsession('DB_id_usuario'));
$oItensMenu->salvarArquivo();
db_putsession('DB_menus', null);

?>
<html><!-- InstanceBegin template="/Templates/corpo.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<!-- InstanceEndEditable -->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_mostramodulo(chave1,chave2){
  location.href="modulos.php?coddepto="+chave1+"&retorno=true&nomedepto="+chave2;
}
function js_atualizacao_versao(){
  js_OpenJanelaIframe('top.corpo','dbiframe_atualiza','con3_versao004.php?id_item=<?=db_getsession("DB_modulo")."&tipo_consulta=M"?>',"Atualizacoes");
}
</script>
<!-- InstanceBeginEditable name="head" -->
<link href="estilos.css" rel="stylesheet" type="text/css">
<!-- InstanceEndEditable -->
<!-- InstanceParam name="leftmargin" type="text" value="0" -->
<!-- InstanceParam name="onload" type="text" value="a=1" -->
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<tr>
<td width="360" height="18">&nbsp;</td>
<td width="263">&nbsp;</td>
<td width="25">&nbsp;</td>
<td width="140">&nbsp;</td>
</tr>
</table>
<?php

/**
 * Validamos se o módulo que está sendo acessado é o MATERIAL
 *
 * Quando for o módulo material, comparamos a data do servidor com a data da sessão do usuário.
 * Caso seja diferente não permitimos o acesso ao módulo. Isso foi feito para que não funcione
 * o "retorna data" para o módulo material.
 */

/**
 * REMOVIDO VALIDAÇÃO DEVIDO A SOLICITAÇÃO DE CLIENTE (TAREFA: 70170)
 */
// $iCodigoUsuario = db_getsession("DB_id_usuario");
// $sLoginUsuario  = db_getsession("DB_login");
// if (isset($modulo) && $modulo == 480 && ($iCodigoUsuario != 1 && $sLoginUsuario != "dbseller")) {

//   $dtServidor = date("Y-m-d");
//   $dtSessao   = date("Y-m-d", db_getsession("DB_datausu"));

//   if ($dtServidor != $dtSessao) {

//     $dtConfiguradaServidor = db_formatar($dtServidor, "d");
//     $dtConfiguradaSessao   = db_formatar($dtSessao, "d");

//     $sMensagem  = "Módulo {$nomemod}\\n";
//     $sMensagem .= "A data do servidor está diferente da data da sessão. Verifique:\\n\\n";
//     $sMensagem .= "Data Servidor: {$dtConfiguradaServidor}\\n";
//     $sMensagem .= "Data Sessão: {$dtConfiguradaSessao}\\n\\n";
//     $sMensagem .= "Acesso não permitido.";
//     db_msgbox($sMensagem);
//     db_redireciona("corpo.php");exit;
//   }
// }
?>
<form name="form1" method="post">

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC">
<tr>
<td valign="top" ><!-- InstanceBeginEditable name="corpo" -->
<!--AAAAAAAAAAAAAAAAAAAAAAAaaa-->
<?
if (db_getsession("DB_id_usuario") == 1) {
  $sql = 	"select id_usuario,anousu
  from db_permissao
  where id_usuario = ".db_getsession("DB_id_usuario")."
  group by id_usuario,anousu
  order by anousu desc";
} else {
  $sql = 	" select distinct on (anousu) anousu,id_usuario from
  (select id_usuario,anousu
  from db_permissao
  where id_usuario = ".db_getsession("DB_id_usuario")."
  group by id_usuario,anousu"
  . " union all
  select db_permissao.id_usuario,anousu
  from db_permissao
  inner join db_permherda h on h.id_perfil = db_permissao.id_usuario
  inner join db_usuarios u on u.id_usuario = h.id_perfil and u.usuarioativo = '1'
  where h.id_usuario = ".db_getsession("DB_id_usuario")."
  group by db_permissao.id_usuario,anousu
  ) as x
  order by anousu desc";
}
$result = db_query($sql);
if(pg_numrows($result) == 0) {
  echo "Voce não tem permissão de acesso para exercício ".db_getsession("DB_anousu").". <br>
  Contate o administrador para maiores informações ou selecione outro exercicio.\n";
}

//	$result = db_query("select id_usuario,anousu from db_permissao where id_usuario = ".db_getsession("DB_id_usuario")." group by id_usuario,anousu order by anousu desc");

$u = db_query("select nome_modulo,descr_modulo from db_modulos where id_item = ".db_getsession("DB_modulo"));
$mod = pg_result($u,0,0);
$des = pg_result($u,0,1);
$u = db_query("select login,nome from db_usuarios where id_usuario = ".db_getsession("DB_id_usuario"));
$log = pg_result($u,0,0);
$nom = pg_result($u,0,1);
?>
<br><br>
<table border="0" cellspacing="0" cellpadding="10">
<tr>
<td>Módulo:</td>
<td nowrap>
<?=$mod?>
&nbsp;&nbsp;<font style="font-size:10px">(
<?=$des?>
)</font></td>
</tr>
<tr>
<td>Leia as Atualizacoes</td>
<td nowrap>
<font style="font-size:10px"><strong><a href='#' onclick='js_atualizacao_versao();'>Clique Aqui</a></strong>
</font></td>
</tr>

<tr>
<td>Usuário:</td>
<td nowrap>
<?=$log?>
&nbsp;&nbsp;<font style="font-size:10px">(
<?=$nom?>
)</font></td>
</tr>
<tr>
<td>Exercício:</td>
<td>
<?
if(db_getsession("DB_anousu")!= date("Y",db_getsession("DB_datausu"))){
  echo "<font size='5'>".db_getsession("DB_anousu")."</font>";
}else{
  echo db_getsession("DB_anousu");
}
?>
</td>
</tr>

<tr>
<td>Alternar exercício:</td>
<td>
<select name="formAnousu" size="1" onChange="document.form1.submit()">
<option value="">&nbsp;</option>
<?
for($i = 0;$i < pg_numrows($result);$i++) {
  echo "<option value=\"".pg_result($result,$i,"anousu")."\">".pg_result($result,$i,"anousu")."</option>\n";
}
?>
</select>
</td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="10">
<tr>
<Td >
<?
//if(!isset($retorno)){
  $mostra_menu = false;
  // sql abaixo desativado
  $sql = "select distinct d.coddepto,d.descrdepto,u.db17_ordem
  from db_depusu u
  inner join db_depart d on u.coddepto = d.coddepto
  inner join db_departorg o on u.coddepto = o.db01_
  inner join orcdotacao on o.db01_anousu = o58_anousu and
  o.db01_orgao  = o58_orgao  and
  o.db01_unidade = o58_unidade
  where u.id_usuario = ".db_getsession("DB_id_usuario") . " and
  o.db01_anousu = ".db_getsession("DB_anousu")." and
  o58_instit = ".db_getsession("DB_instit")." order by u.db17_ordem";

  $sql = "select distinct d.coddepto,d.descrdepto,u.db17_ordem
  from db_depusu u
  inner join db_depart d on u.coddepto = d.coddepto
  left join db_departorg o on u.coddepto = o.db01_coddepto
  left join orcdotacao on o58_anousu  = ".db_getsession("DB_anousu")." and
  o.db01_orgao   = o58_orgao  and
  o.db01_unidade = o58_unidade
  where u.id_usuario = ".db_getsession("DB_id_usuario") . "  and
  o58_instit = ".db_getsession("DB_instit")."
  and o58_anousu = ".db_getsession("DB_anousu")."
  order by u.db17_ordem";

  /* se o usuario tiver departamento, aparecem os departamentos
  se não tiver, aparecem todos e monta os menus que tiver permissao*/


  //$sql = "select distinct d.coddepto,d.descrdepto
  $sql = "select distinct d.coddepto,d.descrdepto,u.db17_ordem
            from db_depusu u
                 inner join db_depart d on u.coddepto = d.coddepto
           where instit = ".db_getsession("DB_instit")."
             and u.id_usuario = ".db_getsession("DB_id_usuario") . "
	         and (d.limite is null or d.limite >= '" . date("Y-m-d",db_getsession("DB_datausu")) . "')

           order by u.db17_ordem ";
  $result = db_query($sql) or die($sql);
  if(pg_numrows($result) == 0){
    echo "<hr>";
    echo "Usuário sem departamento para acesso cadastrado!";
  }else{

		// caso o usuario entre no modulo e mude o departamento na lista entra aqui
    if(isset($coddepto)){
      db_putsession("DB_coddepto",$coddepto);
      $result = db_query("select descrdepto from ($sql) as x where coddepto = $coddepto");
      $nomedepto = pg_result($result,0,0);
      db_putsession("DB_nomedepto",$nomedepto);
		// caso o usuario acesse o modulo pela primeira vez
    }else if(session_is_registered("DB_coddepto")){
      global $coddepto;
      $coddepto = db_getsession("DB_coddepto");
			$sqlverifica = "select instit from db_depart where coddepto = $coddepto";
			$resultverifica = db_query($sqlverifica) or die($sqlverifica);
      if (pg_result($resultverifica,0,"instit") != db_getsession("DB_instit")) {
				$result = db_query($sql) or die($sql);
				db_fieldsmemory($result,0);
				db_putsession("DB_coddepto",$coddepto);
				db_putsession("DB_nomedepto",$descrdepto);
			}
			$sqldepusu="select * from db_depusu
									inner join db_depart d on db_depusu.coddepto = d.coddepto
									where db_depusu.id_usuario = " . db_getsession("DB_id_usuario") . "
			              and db_depusu.coddepto = $coddepto
									  and (d.limite is null or d.limite >= '" . date("Y-m-d",db_getsession("DB_datausu")) . "')";
			$resultdepusu = db_query($sqldepusu) or die($sqldepusu);
			if (pg_numrows($resultdepusu) == 0) {
				$result = db_query($sql) or die($sql);
				db_fieldsmemory($result,0);
				db_putsession("DB_coddepto",$coddepto);
				db_putsession("DB_nomedepto",$descrdepto);
			}
    }
    echo "Departamento:&nbsp;&nbsp;</td><td>";
    $mostra_menu = true;
    $result = db_query($sql) or die($sql);
    db_selectrecord('coddepto',$result,true,2,'','','','','js_mostramodulo(document.form1.coddepto.value,document.form1.coddeptodescr.options.text)');

    if(!session_is_registered("DB_coddepto")){
      db_putsession("DB_coddepto",pg_result($result,0,0));
      db_putsession("DB_nomedepto",pg_result($result,0,1));
    }

    db_logsmanual_demais("Acesso ao Módulo - Login: ".db_getsession("DB_login"),db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),0,db_getsession("DB_coddepto"),db_getsession("DB_instit"));
  }
  if(db_getsession("DB_modulo")==1){
    $mostra_menu = true;
  }

  $sql = "select * from db_datausuarios where id_usuario = ".db_getsession("DB_id_usuario");
  $resusu = db_query($sql);
  if(pg_numrows($resusu)>0){
    //db_criatabela($resusu);exit;
    if ( date("Y-m-d",db_getsession("DB_datausu")) != pg_result($resusu,0,'data') ){
      if ( db_permissaomenu(db_getsession("DB_anousu"), 1, 3896) == true ) {
        db_redireciona("con4_trocadata.php");
      }else{
        $sql = "delete from db_datausuarios where id_usuario = ".db_getsession("DB_id_usuario");
        $resusu = db_query($sql);
      }
    }
  }




  ?>
  </Td>
  </tr>
  </table>
  <?
  if($mostra_menu == true){
    //db_logsmanual('Acesso Módulo',db_getsession('DB_modulo'),db_getsession('DB_modulo'),0,0);
  }
  ?>
  <!--BBBBBBBBBBBBBBBBBBBBBBBBBBB-->
  <!-- InstanceEndEditable -->
  </td>
  <td width="390"valign="top">
  <?
  if($mostra_menu==true){
    ?>
    <table>
    <tr bgcolor="#CCCC00">
    <td align="center"> Últimos acessos ao Módulo
    </td>
    </tr>
    <?
    $sql = "select * from (
    select descricao,
           data,
           hora,
           id_item,
           help,
    funcao from (
                select distinct on (funcao)	d.descricao,
                       x.data,
                       x.hora,
                       x.id_item,
                       help,
                       case when m.id_item is null then d.funcao else null end as funcao
                  from (
                        select * from db_logsacessa a
                         where 	a.id_modulo = ".db_getsession("DB_modulo")." and
                                a.id_usuario = ".db_getsession("DB_id_usuario")."
                          order by a.data desc, a.hora desc limit 20 offset 1
                       ) as x
                       inner join db_itensmenu d on x.id_item = d.id_item
                       left outer join db_modulos m on m.id_item = d.id_item
	               where d.itemativo = '1' and d.libcliente is true
                 ) as x
                        ) as x
            order by data desc ,hora desc
            ";

    $result = db_query($sql);
    if($result>0){
      $cor='';
      for($i=0;$i<pg_numrows($result);$i++){
        db_fieldsmemory($result,$i,true);
        if($cor=="#CCCC66")
        $cor="#CCCC99";
        else
        $cor="#CCCC66";
        ?>
        <tr>
        <td bgcolor="<?=$cor?>">
        <table cellspacing="0" cellpadding="0">
        <tr>
        <td width="70%" title="<?=$help?>">
        <?
        if($funcao==""){
          echo "<a href=\"\" >$descricao</a>";
        }else{
	  $sql = "select descricao from db_menu inner join db_itensmenu on db_menu.id_item = db_itensmenu.id_item where id_item_filho = $id_item and modulo = ".db_getsession("DB_modulo");
	  $resultpai = db_query($sql);
          if(pg_numrows($resultpai)>0){
             $descrpai = pg_result($resultpai,0,0);
	  }else{
	     $descrpai = "";
	  }
          echo "<a href=\"$funcao\" title=\"".$descrpai.">".$descricao."\"onclick=\"return js_verifica_objeto('DBmenu_$id_item');\">$descricao</a>";
        }
        ?>
        </td>
        <td align="center" width="10%">
        <?=$data?>
        </td>
        <td align="center" width="20%">
        <?=$hora?>
        </td>
        </tr>
        </table>
        </td>
        </tr>
        <?
      }
    }
    echo "</table>";
  }
  ?>
  </td>
  </tr>
  </table>
  </form>

  <?
  if(isset($mostra_menu) && $mostra_menu == true){
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  }
  ?>
  </body>
  <!-- InstanceEnd --></html>
  <script>
  parent.bstatus.document.getElementById('dtatual').innerHTML = '<?=date("d/m/Y",db_getsession("DB_datausu"))?>' ;
  parent.bstatus.document.getElementById('dtanousu').innerHTML = '<?=(db_getsession("DB_modulo")!=952?db_getsession("DB_anousu"):db_anofolha()."/".db_mesfolha())?>' ;
  <?
  if(db_getsession("DB_anousu")!= date("Y",db_getsession("DB_datausu"))){
    echo "alert('Exercício diferente do exercício da data. Verifique!');";
  }
  ?>
  </script>