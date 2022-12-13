<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libcaixa.php");
require_once("classes/db_cfautent_classe.php");

$clcfautent = new cl_cfautent;

  //rotina que verifica se o ip do usuario irá imprimir autenticar ou naum ira fazer nada
      $result99 = $clcfautent->sql_record($clcfautent->sql_query_file(null,"k11_tipautent as tipautent",'',"k11_ipterm = '".$HTTP_SERVER_VARS['REMOTE_ADDR']."'"));
      if($clcfautent->numrows > 0){
      	db_fieldsmemory($result99,0);
      }else{
	      db_msgbox("Cadastre o ip ".$HTTP_SERVER_VARS['REMOTE_ADDR']." como um caixa.");
	      die();
      }

$clautenticar = new cl_autenticar;
$ip           = db_getsession("DB_ip");
$porta        = 5001;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript">
function js_relatorio() {
  var F = document.form1;
  var data = F.data_ano.value+'-'+F.data_mes.value+'-'+F.data_dia.value;
  window.open('cai4_remissbob002.php?id='+F.caixa.value+'&data='+data,'','location=0');
}
function js_imprime(tipo){
  obj = document.form1;
  dia = obj.data_dia.value;
  mes = obj.data_mes.value;
  ano = obj.data_ano.value;
  js_OpenJanelaIframe('top.corpo','db_iframe_imprime','cai4_remissbob003.php?tipo='+tipo+'&dia='+dia+'&mes='+mes+'&ano='+ano,'Impressão',true,150,200,300,200);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form name="form1" method="post">
      <fieldset>
          <legend>Reemissão de Bobina</legend>
          <table border="0">
            <tr>
              <td nowrap colspan="3" align="center"><strong>Data:</strong>&nbsp;&nbsp;<?=db_data("data",date("d"),date("m"),date("Y"))?></td>
            </tr>
            <tr>
              <td height="25" nowrap align="center">
    	          <input name="cabecalho" type="button" id="pesquisar" value="Reemite cabeçalho" onclick="js_imprime('cabecalho');">
              </td>
              <td>
    	          <input name="autenticacao" type="button" id="pesquisar" value="Reemite autenticação" onclick="js_imprime('autenticacao');">
              </td>
              <td>
  	            <input name="fechamento" type="button" id="pesquisar" value="Fechamento" onclick="js_imprime('fechamento');">
              </td>
            </tr>
          </table>
      </fieldset>
    </form>
  </div>
<?php
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?php
if($tipautent==1){

  $clautenticar->verifica($ip,$porta);
  if($clautenticar->erro==true){
   db_msgbox($clautenticar->erro_msg);
  }
}
?>