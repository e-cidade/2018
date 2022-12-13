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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_selecao_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

$clselecao = new cl_selecao;
$db_opcao = 1;
$db_botao = true;

$iInstituicao = db_getsession('DB_instit');

if( isset($incluir)) {

  db_inicio_transacao();
  $clselecao->r44_where = pg_escape_string(str_replace(array("\r\n", "\\"), array("\n", ""), $r44_where));
  $clselecao->incluir($r44_selec, $iInstituicao);
  db_fim_transacao();
}

if ( empty($r44_selec) ) {

  $iProximaNumeracao = 1;
  $sSqlSelec = $clselecao->sql_query_file(null, $iInstituicao, 'r44_selec', 'r44_selec desc');
  $rsSelec   = $clselecao->sql_record($sSqlSelec);

  if ( $clselecao->numrows > 0 ) {
    $iProximaNumeracao = db_utils::fieldsMemory($rsSelec, 0)->r44_selec + 1; 
  }

  $r44_selec = $iProximaNumeracao;
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

	<?php include(modification("forms/db_frmselecao.php")); ?>
  <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>

</body>
</html>
<?php
if(isset($incluir)){
  if($clselecao->erro_status=="0"){
    $clselecao->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clselecao->erro_campo!=""){
      echo "<script> document.form1.".$clselecao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clselecao->erro_campo.".focus();</script>";
    };
  }else{
    $clselecao->erro(true,true);
  };
};
?>