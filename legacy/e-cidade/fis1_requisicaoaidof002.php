<?php
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

  require_once ("libs/db_utils.php");
  require_once ("libs/db_stdlib.php");
  require_once ("libs/db_conecta.php");
  require_once ("libs/db_sessoes.php");
  require_once ("std/db_stdClass.php");
  require_once ("libs/db_usuariosonline.php");
  require_once ("classes/db_requisicaoaidof_classe.php");
  require_once ("dbforms/db_funcoes.php");
  
  
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  db_postmemory($HTTP_POST_VARS);
  
  $clrequisicaoaidof = new cl_requisicaoaidof;
  $clnotasiss        = new cl_notasiss();
  $clcgm             = new cl_cgm();
  $db_opcao          = 1;
   
  if (isset($bOpcao)) {
    
    switch ($bOpcao) {
      
      case 'liberar' :

        db_inicio_transacao();
        
        $oModelRequisicaoAidof = new RequisicaoAidof($y116_id);
        $oModelRequisicaoAidof->setQuantidadeLiberada($y116_quantidadeLiberada);
        $oModelRequisicaoAidof->setObservacao($y116_observacao);
        
        $oRetorno = $oModelRequisicaoAidof->gerarAidof();
        
        db_msgbox($oRetorno->sMensagem);
        
        if ($oRetorno->bStatus) { 
         
          db_fim_transacao();
          db_redireciona('fis1_requisicaoaidof001.php');
        } else {
          
          db_fim_transacao(true);
        }
        
        break;
        
      case 'bloquear' :
        
        db_inicio_transacao();
        
        $oModelRequisicaoAidof = new RequisicaoAidof($y116_id);
        $oModelRequisicaoAidof->setObservacao($y116_observacao);
        $oModelRequisicaoAidof->setQuantidadeLiberada(null);
        
        $oRetorno = $oModelRequisicaoAidof->bloquearRequisicao();
        
        db_msgbox($oRetorno->sMensagem);
        
        if ($oRetorno->bStatus) { 
         
          db_fim_transacao();
          db_redireciona('fis1_requisicaoaidof001.php');
        } else {
          
          db_fim_transacao(true);
        }
        
        break;   
    }
  }
   
  $sCampos = "";
  $sSqlCampos  = " y116_id, y116_inscricaomunicipal, y116_tipodocumento, q09_descr, y116_quantidadesolicitada, \n";
  $sSqlCampos .= " y116_datalancamento, z01_nome, nome, y116_idusuario, y116_status, y116_quantidadeliberada,  \n";
  $sSqlCampos .= " y116_observacao                                                                             \n";
  
  $sSql             = $clrequisicaoaidof->sql_query_dadosRequisicao($idRequisicao, $sSqlCampos);
  
  $rsRecord         = $clrequisicaoaidof->sql_record($sSql);
  $oDadosRequisicao = db_utils::fieldsMemory($rsRecord, 0);
?>
<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
    body                       { background-color:#CCC; margin:0; }
    div.botoes                 { margin-top:10px; }
    fieldset.requisicao_aidof  { margin-top:30px; width:600px; text-align:left; }
    fieldset legend            { font-weight:bold; }
    textarea                   { width:100%; height:100px; }
    table                      { border:0; width:100%; }
    </style>
  </head>
  <body onLoad="a=1">
    <?php include("forms/db_frmrequisicaoaidof.php"); ?>
    <?php
      db_menu(
        db_getsession("DB_id_usuario"),
        db_getsession("DB_modulo"),
        db_getsession("DB_anousu"),
        db_getsession("DB_instit")
      );
    ?>
  </body>
</html>
<?php
  
  if (isset($alterar)) {
    
    
    if ($clrequisicaoaidof->erro_status == "0") {

      $clrequisicaoaidof->erro(true, false);
      $db_botao = true;
      
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      
      if ($clrequisicaoaidof->erro_campo != "") {
        
        echo "<script> document.form1." . $clrequisicaoaidof->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1." . $clrequisicaoaidof->erro_campo . ".focus();</script>";
      }
      
    } else {
      
      $clrequisicaoaidof->erro(true, true);
    }
  }
  
  if ($db_opcao == 22) {
    
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>
<script>
  js_tabulacaoforms("form1", "y116_tipodocumento", true, 1, "y116_tipodocumento", true);
</script>