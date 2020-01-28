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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ('libs/db_utils.php');
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$cllab_entrega   = new cl_lab_entrega;
$cllab_requiitem = new cl_lab_requiitem;
$clrotulo        = new rotulocampo;

$clrotulo->label("la08_c_descr");
$clrotulo->label("la21_i_codigo");

$db_opcao = 1;
$db_botao = true;

/**
 * Função para descobrir o laboratorio que o usuario esta logado
 * @return inteiro Codigo do laboratorio logado 
 */
function laboratorioLogado(){

  $iUsuario = db_getsession('DB_id_usuario');
  $iDepto   = db_getsession('DB_coddepto');

  $oLab_labusuario = new cl_lab_labusuario();
  $oLab_labdepart  = new cl_lab_labdepart();

  $sWhere  = "la05_i_usuario = {$iUsuario}";
  $sql     = $oLab_labusuario->sql_query( null, 'la02_i_codigo, la02_c_descr', "la02_i_codigo", $sWhere );
  $rResult = $oLab_labusuario->sql_record( $sql );

  if ($oLab_labusuario->numrows == 0) {

    $sWhere  = "la03_i_departamento = {$iDepto}";
    $sql     = $oLab_labdepart->sql_query( null, 'la02_i_codigo, la02_c_descr', "la02_i_codigo", $sWhere );
    $rResult = $oLab_labdepart->sql_record( $sql );

    if ($oLab_labdepart->numrows == 0) {
      return 0;
    }
  }

  $oLab = db_utils::getCollectionByRecord($rResult);
  return $oLab[0]->la02_i_codigo;
}

$iLaboratorioLogado = laboratorioLogado();

if( isset( $incluir ) ) {

  if( empty( $la21_i_codigo ) ) {

    db_msgbox( "Código do exame não informado." );
    $sParametros = "lRedirecionamento=true&iPaciente=" . $la31_i_cgs . "&iRequisicao=" . $la22_i_codigo;
    db_redireciona( "lab4_entregaresult001.php?" . $sParametros );
  }

  db_inicio_transacao();

  $cllab_entrega->la31_i_requiitem = $la21_i_codigo;
  $cllab_entrega->la31_d_data      = date('Y-m-d',db_getsession("DB_datausu"));
  $cllab_entrega->la31_c_hora      = db_hora();
  $cllab_entrega->la31_i_usuario   = db_getsession("DB_id_usuario");
  $cllab_entrega->incluir($la31_i_codigo);

  $cllab_requiitem->la21_c_situacao = '3 - Entregue';  
  $cllab_requiitem->alterar($la21_i_codigo);

  db_msgbox($cllab_requiitem->erro_msg);
  db_fim_transacao();
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
  <div class="container">
  <?php
    if( $iLaboratorioLogado == 0 ) { ?>
      <font color='#FF0000' face='arial'>
        <b>Usuario ou departamento nao consta como laboratorio!<br>
        </b>
      </font>
      <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
      exit;
    }
    include("forms/db_frmlab_entrega.php");
    ?>
  </div>
  <?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script>
js_tabulacaoforms("form1","la23_c_descr",true,1,"la23_c_descr",true);
</script>
<?
if( isset( $incluir ) ) {

  if( $cllab_entrega->erro_status == "0" ) {

    $cllab_entrega->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if( $cllab_entrega->erro_campo != "" ) {

      echo "<script> document.form1.".$cllab_entrega->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllab_entrega->erro_campo.".focus();</script>";
    }
  } else {
    $cllab_entrega->erro(true,true);
  }
}