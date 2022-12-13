<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require_once("classes/db_prontuarios_classe.php");
require_once("classes/db_sau_encaminhamentos_classe.php");
require_once("classes/db_sau_procencaminhamento_classe.php");
require_once("classes/db_sau_encaminhanulado_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_app.utils.php");
require_once('libs/db_utils.php');
require_once('model/encaminhamentos.model.php');

db_app::load("scripts.js, prototype.js, datagrid.widget.js, strings.js");
db_app::load("estilos.css, grid.style.css");

define("URL_MENSAGEM_EMCAMINHAMENTOS001", "saude.ambulatorial.sau4_sau_encaminhamentos001.");

db_postmemory($HTTP_POST_VARS);

$oClprontuarios            = new cl_prontuarios;
$oClsau_encaminhamentos    = new cl_sau_encaminhamentos;
$oClsau_procencaminhamento = new cl_sau_procencaminhamento;
$oClsau_encaminhanulado    = new cl_sau_encaminhanulado;
$oEncaminhamentos          = new encaminhamento;
$oDaoUnidades              = db_utils::getdao('unidades');

/* Verificacao se o departamento que o usuario esta logado eh uma UPS */
$sSql = $oDaoUnidades->sql_query(null, ' descrdepto as departamentoatual ', null,
                                 ' sd02_i_codigo = '.db_getsession('DB_coddepto'));
$oDaoUnidades->sql_record($sSql);
	
if($oDaoUnidades->numrows == 0) {

  db_msgbox( _M( URL_MENSAGEM_EMCAMINHAMENTOS001 . "departamento_nao_ups") );
?>
    <script>
      history.back();
    </script>
<?php
  exit();
}

$dData_atual = date("d/m/Y",db_getsession("DB_datausu"));
$aData_atual = explode('/',$dData_atual);
$iUsuario    = db_getsession("DB_id_usuario");

if ( !isset($db_opcao) ) {
  $db_opcao = 1;
}

$db_botao = true;

if ( isset($confirmar) ) {

  db_inicio_transacao();
  $erro_proced                          = false;
  $oClsau_encaminhamentos->s142_i_login = $iUsuario;
  $oClsau_encaminhamentos->incluir($s142_i_codigo);
  
  for($iCont = 0; $iCont < count($select_procedimento); $iCont++) {
    
    $oClsau_procencaminhamento->s143_i_procedimento   = $select_procedimento[$iCont];
    $oClsau_procencaminhamento->s143_i_encaminhamento = $oClsau_encaminhamentos->s142_i_codigo;
    $oClsau_procencaminhamento->incluir(null);

    if ( $oClsau_procencaminhamento->erro_status == "0" ) {
      $erro_proced = true;
    }
  }
  
  if ( $oClsau_encaminhamentos->erro_status == "0" ) {

    $oClsau_encaminhamentos->erro(true,false);
    $db_botao = true;
    db_fim_transacao(true);
  } else {

    if( $erro_proced ) {

      db_msgbox( _M( URL_MENSAGEM_EMCAMINHAMENTOS001 . "erro_cadastrar_procedimentos") );
      db_fim_transacao(true);
    } else {

      db_fim_transacao();
      $oClsau_encaminhamentos->erro(true,false);
      $db_opcao      = 2;
      $s142_i_codigo = $oClsau_encaminhamentos->s142_i_codigo;
    }
  }
}

if ( isset($alterar) ) {

  $db_opcao = 2;
  db_inicio_transacao();

  if ( isset($lProcedimentosAlterados) && $lProcedimentosAlterados == 'true' ) {

    $lSucesso = $oEncaminhamentos->alteraProcedimentosEncaminhamento($s142_i_codigo, $select_procedimento);
    if ( !$lSucesso ) {
    
      db_msgbox( _M( URL_MENSAGEM_EMCAMINHAMENTOS001 . "erro_alterar_procedimentos") );
      db_fim_transacao(true);
      $lSucesso = 'false';
    }
  }

  if ( db_utils::inTransaction() ) {

    $oClsau_encaminhamentos->s142_i_login = $iUsuario;
    $oClsau_encaminhamentos->alterar2($s142_i_codigo); 

    if ( $oClsau_encaminhamentos->erro_status == "0" ) {

      $oClsau_encaminhamentos->erro(true,false);
      $db_botao = true;
      $lSucesso = 'false';
      db_fim_transacao(true);
    } else {

      db_fim_transacao();
      $oClsau_encaminhamentos->erro(true, false);
      $lSucesso = 'true';
    }
  }
}

if ( isset($cancelar) ) {

  db_inicio_transacao();
  $oClsau_encaminhanulado->s149_i_encaminhamento = $s142_i_codigo;
  $oClsau_encaminhanulado->s149_t_obs            = $s149_t_obs;
  $oClsau_encaminhanulado->s149_i_login          = $iUsuario;
  $oClsau_encaminhanulado->s149_d_data           = date("Y-m-d",db_getsession("DB_datausu"));
  $oClsau_encaminhanulado->s149_c_hora           = date("H:i:s");
  $oClsau_encaminhanulado->incluir(null);
  
  if ( $oClsau_encaminhanulado->erro_status == "0" ) {

      db_msgbox( _M( URL_MENSAGEM_EMCAMINHAMENTOS001 . "erro_cancelamento") );
      $db_botao = true;
      db_fim_transacao(true);
  } else {

    db_fim_transacao();
    db_msgbox( _M( URL_MENSAGEM_EMCAMINHAMENTOS001 . "cancelamento_realizado") );

    if( !isset($lAba) ) {
      echo "<script>location.href='".$oClsau_encaminhanulado->pagina_retorno."'</script>";
    } else {
      echo "<script>location.href='".$oClsau_encaminhanulado->pagina_retorno.
           "?lAba=true&s142_i_cgsund=$s142_i_cgsund&s142_i_prontuario=$s142_i_prontuario'</script>";
    }
  }
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <?php
    require_once("forms/db_frmsau_encaminhamentos.php");
  ?>
  <?php
    if ( !isset($lAba) || !$lAba ) {
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    }
  ?>
</body>
</html>
<script>
js_tabulacaoforms("form1","s142_i_profissional",true,1,"s142_i_profissional",true);
</script>