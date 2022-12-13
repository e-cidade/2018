<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBSeller Servicos de Informatica
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
$_SESSION["DB_itemmenu_acessado"] = "0";

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

if(!session_is_registered("DB_instit")) {

  session_destroy();
  echo "<script>location.href='index.php'</script>";
  exit;
}

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("dbforms/db_funcoes.php"));

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

if(!session_is_registered("DB_nome_modulo")){
  session_register("DB_nome_modulo");
}

if(!session_is_registered("DB_anousu")){
  session_register("DB_anousu");
}

if(!session_is_registered("DB_datausu")) {

  session_register("DB_datausu");
  db_putsession("DB_datausu",time());
}

if(!isset($HTTP_POST_VARS["formAnousu"]) && !isset($retorno)) {

  db_putsession("DB_modulo",$modulo);
  db_putsession("DB_nome_modulo",$nomemod);
  db_putsession("DB_anousu",$anousu);
} else if(isset($HTTP_POST_VARS["formAnousu"]) && $HTTP_POST_VARS["formAnousu"] != ""){
  db_putsession("DB_anousu",$HTTP_POST_VARS["formAnousu"]);
}

// se o exercicio nao for selecionado no modulo, esta acessando o módulo
if( !isset($HTTP_POST_VARS["formAnousu"])) {

  // se o ano da data do exercicio for diferente  do anousu registrado, o sistema utiliza como padrao o anousu da data
  if( db_getsession("DB_anousu") != date("Y",db_getsession("DB_datausu")) ){
    db_putsession("DB_anousu" , date("Y",db_getsession("DB_datausu")) );
  }
}

$nomemod = db_getsession("DB_nome_modulo");

/**
 * Update na usuariosonline
 */
$sSqlUsuariosOnline  = "update db_usuariosonline                  ";
$sSqlUsuariosOnline .= "   set uol_arquivo = '',                  ";
$sSqlUsuariosOnline .= "       uol_modulo  = '" . $nomemod . "',  ";
$sSqlUsuariosOnline .= "       uol_inativo = " . time();
$sSqlUsuariosOnline .= " where uol_id   = " . db_getsession("DB_id_usuario");
$sSqlUsuariosOnline .= "   and uol_ip   = '" . (isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR']) . "'";
$sSqlUsuariosOnline .= "   and uol_hora = " . db_getsession("DB_uol_hora");
db_query($sSqlUsuariosOnline) or die("Erro(26) atualizando db_usuariosonline");

/**
 * Verifica registro na usumod
 * Insere caso não exista ou atualiza o registro
 */
$sSqlUsuariosModulo  = "select id_item   ";
$sSqlUsuariosModulo .= "  from db_usumod ";
$sSqlUsuariosModulo .= " where id_usuario = " . db_getsession("DB_id_usuario");
$sSqlUsuariosModulo .= "   and id_item    = " . db_getsession("DB_modulo");
$result              = db_query($sSqlUsuariosModulo);

if(pg_numrows($result) == 0) {

  $sSqlInsertUsariosModulo = "insert into db_usumod values(".db_getsession("DB_modulo").",".db_getsession("DB_anousu").",".db_getsession("DB_id_usuario").")";
  db_query($sSqlInsertUsariosModulo) or die("Erro(40) inserindo em db_usumod: ".pg_errormessage());
} else {

  $sSqlUpdateUsariosModulo  = "update db_usumod                                  ";
  $sSqlUpdateUsariosModulo .= "   set id_item = " . db_getsession("DB_modulo") . ",";
  $sSqlUpdateUsariosModulo .= "       anousu  = " . db_getsession("DB_anousu");
  $sSqlUpdateUsariosModulo .= " where id_usuario = " . db_getsession("DB_id_usuario");
  $sSqlUpdateUsariosModulo .= "   and id_item    = " . db_getsession("DB_modulo");
  db_query($sSqlUpdateUsariosModulo);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_mostramodulo(chave1,chave2){
  location.href="modulos.php?coddepto="+chave1+"&retorno=true&nomedepto="+chave2;
}
function js_atualizacao_versao(){
  js_OpenJanelaIframe('CurrentWindow.corpo','dbiframe_atualiza','con3_versao004.php?id_item=<?=db_getsession("DB_modulo")."&tipo_consulta=M"?>',"Atualizacoes");
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body class="body-default">

<form name="form1" method="post">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td valign="top">
<?
if (db_getsession("DB_id_usuario") == 1) {

  $sSql  = "select id_usuario, anousu   ";
  $sSql .= "  from db_permissao         ";
  $sSql .= " where id_usuario = ".db_getsession("DB_id_usuario");
  $sSql .= "group by id_usuario, anousu ";
  $sSql .= "order by anousu desc        ";
} else {

  $sSql  = " select distinct on (anousu) anousu, id_usuario                                     ";
  $sSql .= "   from (select id_usuario, anousu                                                  ";
  $sSql .= "           from db_permissao                                                        ";
  $sSql .= "          where id_usuario = ".db_getsession("DB_id_usuario");
  $sSql .= "       group by id_usuario, anousu                                                  ";
  $sSql .= "       union all                                                                    ";
  $sSql .= "         select db_permissao.id_usuario, anousu                                     ";
  $sSql .= "           from db_permissao                                                        ";
  $sSql .= "                inner join db_permherda h on h.id_perfil  = db_permissao.id_usuario ";
  $sSql .= "                inner join db_usuarios  u on u.id_usuario = h.id_perfil             ";
  $sSql .= "                                         and u.usuarioativo = '1'                   ";
  $sSql .= "          where h.id_usuario = ".db_getsession("DB_id_usuario");
  $sSql .= "         group by db_permissao.id_usuario, anousu                                   ";
  $sSql .= "         ) as x                                                                     ";
  $sSql .= "order by anousu desc                                                                ";
}

$result = db_query($sSql);
if(pg_numrows($result) == 0) {
  echo "Você não tem permissão de acesso para exercício ".db_getsession("DB_anousu").". <br/>
  Contate o administrador para maiores informações ou selecione outro exercício.\n";
}

$sSqlModulos      = "select nome_modulo, descr_modulo from db_modulos where id_item = ".db_getsession("DB_modulo");
$rsModulos        = db_query($sSqlModulos);
$sNomeModulo      = pg_result($rsModulos,0,0);
$sDescricaoModulo = pg_result($rsModulos,0,1);

$sSqlUsuarioLogado = "select login, nome from db_usuarios where id_usuario = ".db_getsession("DB_id_usuario");
$rsUsuarioLogado   = db_query($sSqlUsuarioLogado);
$sLogin            = pg_result($rsUsuarioLogado,0,0);
$sNome             = pg_result($rsUsuarioLogado,0,1);
?>
<table border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td>Módulo:</td>
    <td nowrap>
      <?=$sNomeModulo?>
      &nbsp;&nbsp;<font style="font-size:10px">(<?=$sDescricaoModulo?>)</font>
    </td>
  </tr>

  <tr>
    <td>Leia as Atualizações:</td>
    <td nowrap>
      <font style="font-size:10px"><strong><a href='#' onclick='js_atualizacao_versao();'>Clique Aqui</a></strong></font>
    </td>
  </tr>

  <tr>
    <td>Usuário:</td>
    <td nowrap>
      <?=$sLogin?>
      &nbsp;&nbsp;<font style="font-size:10px">(<?=$sNome?>)</font>
    </td>
  </tr>

  <tr>
    <td>Exercício:</td>
    <td>
      <?
      if(db_getsession("DB_anousu") != date("Y",db_getsession("DB_datausu"))){
        echo "<span class='bold' style='font-size:15px;'>".db_getsession("DB_anousu")."</span>";
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

  <tr>
    <td>
<?

  $mostra_menu = false;

  $sSql  = " select distinct d.coddepto, d.descrdepto, u.db17_ordem               ";
  $sSql .= "   from db_depusu u                                                   ";
  $sSql .= "        inner join db_depart d   on u.coddepto     = d.coddepto       ";
  $sSql .= "        left join db_departorg o on u.coddepto     = o.db01_coddepto  ";
  $sSql .= "        left join orcdotacao     on o58_anousu     = ".db_getsession("DB_anousu");
  $sSql .= "                                and o.db01_orgao   = o58_orgao        ";
  $sSql .= "                                and o.db01_unidade = o58_unidade      ";
  $sSql .= "   where u.id_usuario = ".db_getsession("DB_id_usuario");
  $sSql .= "     and o58_instit   = ".db_getsession("DB_instit");
  $sSql .= "     and o58_anousu   = ".db_getsession("DB_anousu");
  $sSql .= "order by u.db17_ordem";

  /**
   * Se o usuario tiver departamento, aparecem os departamentos
   * Se não tiver, aparecem todos e monta os menus que tiver permissao
   */
  $sSql   = "  select distinct d.coddepto, d.descrdepto, u.db17_ordem   ";
  $sSql  .= "    from db_depusu u                                       ";
  $sSql  .= "         inner join db_depart d on u.coddepto = d.coddepto ";
  $sSql  .= "   where instit       = ".db_getsession("DB_instit");
  $sSql  .= "     and u.id_usuario = ".db_getsession("DB_id_usuario");
	$sSql  .= "     and (d.limite is null or d.limite >= '" . date("Y-m-d",db_getsession("DB_datausu")) . "')";
  $sSql  .= "order by u.db17_ordem ";
  $result = db_query($sSql) or die($sSql);

  if(pg_numrows($result) == 0){

    echo "<hr>";
    echo "Usuário sem departamento para acesso cadastrado!";
  }else{

		// Caso o usuario entre no modulo e mude o departamento na lista entra aqui
    if(isset($coddepto)){

      db_putsession("DB_coddepto",$coddepto);
      $result    = db_query("select descrdepto from ($sSql) as x where coddepto = $coddepto");
      $nomedepto = pg_result($result,0,0);
      db_putsession("DB_nomedepto",$nomedepto);

		// Caso o usuario acesse o modulo pela primeira vez
    }else if(session_is_registered("DB_coddepto")){

      global $coddepto;
      $coddepto       = db_getsession("DB_coddepto");
			$sSqlVerifica   = "select instit from db_depart where coddepto = $coddepto";
			$resultverifica = db_query($sSqlVerifica) or die($sSqlVerifica);

      if (pg_result($resultverifica,0,"instit") != db_getsession("DB_instit")) {

				$result = db_query($sSql) or die($sSql);
				db_fieldsmemory($result,0);
				db_putsession("DB_coddepto",$coddepto);
				db_putsession("DB_nomedepto",$descrdepto);
			}

      $sSqlDepusu   = "select *                                                        ";
			$sSqlDepusu  .= "  from db_depusu                                                ";
			$sSqlDepusu  .=	"	  	inner join db_depart d on db_depusu.coddepto = d.coddepto  ";
			$sSqlDepusu  .=	"	where db_depusu.id_usuario = " . db_getsession("DB_id_usuario");
			$sSqlDepusu  .= "    and db_depusu.coddepto  = " . $coddepto;
			$sSqlDepusu  .=	"	  and (d.limite is null or d.limite >= '" . date("Y-m-d",db_getsession("DB_datausu")) . "')";
			$resultdepusu = db_query($sSqlDepusu) or die($sSqlDepusu);

			if (pg_numrows($resultdepusu) == 0) {

				$result = db_query($sSql) or die($sSql);
				db_fieldsmemory($result,0);
				db_putsession("DB_coddepto",$coddepto);
				db_putsession("DB_nomedepto",$descrdepto);
			}
    }

    echo "Departamento:&nbsp;&nbsp;</td><td>";
    $mostra_menu = true;
    $result      = db_query($sSql) or die($sSql);
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

  $sSql   = "select * from db_datausuarios where id_usuario = ".db_getsession("DB_id_usuario");
  $resusu = db_query($sSql);

  if( pg_numrows($resusu) > 0 ){

    if ( date("Y-m-d",db_getsession("DB_datausu")) != pg_result($resusu,0,'data') ){

      if ( db_permissaomenu(db_getsession("DB_anousu"), 1, 3896) == true ) {
        db_redireciona("con4_trocadata.php");
      }else{

        $sSql = "delete from db_datausuarios where id_usuario = ".db_getsession("DB_id_usuario");
        $resusu = db_query($sSql);
      }
    }
  }
  ?>
      </td>
    </tr>
  </table>
  </td>

  <td width="390"valign="top">
  <?
  if($mostra_menu==true){
    ?>
    <div id="acessosModulo">
      <table width="100%">
        <tr>
          <td colspan="3" class="text-center bold">Últimos acessos ao Módulo</td>
        </tr>
      <?
      $sSql  = "select * from (                                                                          ";
      $sSql .= "                select descricao,                                                        ";
      $sSql .= "                       data,                                                             ";
      $sSql .= "                       hora,                                                             ";
      $sSql .= "                       id_item,                                                          ";
      $sSql .= "                       help,                                                             ";
      $sSql .= "                funcao from ( select distinct on (funcao) d.descricao,                   ";
      $sSql .= "                                                          x.data,                        ";
      $sSql .= "                                                          x.hora,                        ";
      $sSql .= "                                                          x.id_item,                     ";
      $sSql .= "                                                          help,                          ";
      $sSql .= "                                                          case when m.id_item is null    ";
      $sSql .= "                                                               then d.funcao else null   ";
      $sSql .= "                                                             end as funcao               ";
      $sSql .= "                              from ( select *                                            ";
      $sSql .= "                                       from db_logsacessa a                              ";
      $sSql .= "                                      where	a.id_modulo  = ".db_getsession("DB_modulo");
      $sSql .= "                                        and a.id_usuario = ".db_getsession("DB_id_usuario");
      $sSql .= "                                        and a.instit     = ".db_getsession("DB_instit");

      $dDataFim = date('Y-m-d');
      $dDataIni = date('Y-m-d', strtotime($dDataFim . ' -365 day'));

      $sSql .= "                                        and a.data between '{$dDataIni}' and '{$dDataFim}' ";
      $sSql .= "                                        and a.id_item <> 0 ";
      $sSql .= "                                      order by a.data desc, a.hora desc                  ";
      $sSql .= "                                         limit 20                                        ";
      $sSql .= "                                   ) as x                                                ";
      $sSql .= "                                   inner join db_itensmenu d    on x.id_item = d.id_item ";
      $sSql .= "                                   left outer join db_modulos m on m.id_item = d.id_item ";
      $sSql .= "                             where d.itemativo = '1'                                     ";
      $sSql .= "                               and d.libcliente is true                                  ";
      $sSql .= "                             ) as x                                                      ";
      $sSql .= "                                    ) as x                                               ";
      $sSql .= "                        order by data desc, hora desc                                    ";

      $result = db_query($sSql);

      if( $result > 0 ){

        for( $i=0; $i < pg_numrows($result); $i++){

          db_fieldsmemory($result,$i,true);
          ?>
          <tr>
            <td width="70%" title="<?=$help?>">
              <?
              if($funcao==""){
                echo "<a href=\"\" >$descricao</a>";
              }else{

      	        $sSql  = "select descricao                                                                  ";
                $sSql .= "           from db_menu                                                           ";
                $sSql .= "                inner join db_itensmenu on db_menu.id_item = db_itensmenu.id_item ";
                $sSql .= "          where id_item_filho = " . $id_item;
                $sSql .= "            and modulo        = ".db_getsession("DB_modulo");
      	        $resultpai = db_query($sSql);

      	        $descrpai = "";
                if( pg_numrows($resultpai) > 0 ){
                   $descrpai = pg_result($resultpai,0,0);
      	        }
                echo "<a href=\"$funcao\" title=\"".$descrpai.">".$descricao."\"onclick=\"return js_verifica_objeto('DBmenu_$id_item');\">$descricao</a>";
              }
              ?>
            </td>
            <td align="center" width="10%"><?=$data?></td>
            <td align="center" width="20%"><?=$hora?></td>
          </tr>
          <?
        }
      }
      echo "</table>";
      echo "</div>";
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
  </html>
  <script type="text/javascript">
  (window.CurrentWindow || parent.CurrentWindow).bstatus.document.getElementById('dtatual').innerHTML = '<?=date("d/m/Y",db_getsession("DB_datausu"))?>' ;
  (window.CurrentWindow || parent.CurrentWindow).bstatus.document.getElementById('dtanousu').innerHTML = '<?=(db_getsession("DB_modulo")!=952?db_getsession("DB_anousu"):db_anofolha()."/".db_mesfolha())?>' ;
  <?
  if(db_getsession("DB_anousu")!= date("Y",db_getsession("DB_datausu"))){
    echo "alert('Exercício diferente do exercício da data. Verifique!');";
  }
  ?>
  </script>
