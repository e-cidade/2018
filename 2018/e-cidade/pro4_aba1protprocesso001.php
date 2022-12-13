<?
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_protprocesso_classe.php"));
require_once(modification("classes/db_procdoctipo_classe.php"));
require_once(modification("classes/db_procvar_classe.php"));
require_once(modification("classes/db_andpadrao_classe.php"));
require_once(modification("classes/db_proctipovar_classe.php"));
require_once(modification("classes/db_db_syscampo_classe.php"));
require_once(modification("classes/db_procprocessodoc_classe.php"));
require_once(modification("classes/db_arrenumcgm_classe.php"));
require_once(modification("classes/db_protparam_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_app::import("protocolo.ProcessoProtocoloNumeracao");
//db_postmemory($HTTP_SERVER_VARS);
//db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST,0);
$oGet  = db_utils::postMemory($_GET,0);

$clprotprocesso    = new cl_protprocesso;
$clprotparam       = new cl_protparam;
$clprotprocesso    = new cl_protprocesso;
$clprotprocesso    = new cl_protprocesso;
$clprocprocessodoc = new cl_procprocessodoc;
$clproctipovar     = new cl_proctipovar;
$clandpadrao       = new cl_andpadrao;
$clarrenumcgm      = new cl_arrenumcgm;

$db_opcao = 1;
$sqlerro  = false;
$db_botao = false;

$p58_dtproc_dia = date("d", db_getsession("DB_datausu"));
$p58_dtproc_mes = date("m", db_getsession("DB_datausu"));
$p58_dtproc_ano = date("Y", db_getsession("DB_datausu"));

if (isset($oGet->incpro) && $oGet->incpro != "") {
  $sOnLoad  = "";
  $incpro   = false;
  $db_botao = true;
} else {
  $sOnLoad  = "onLoad='js_pesquisa();'";
  $incpro   = true;
}

if (isset($oPost->btnincluir) && $oPost->btnincluir == 1) {
  $incpro = false;
  if($sqlerro == false){
   $lSqlErro = false;
   db_inicio_transacao();

     $iNumeroProcesso = '';
     try {
       $iNumeroProcesso = ProcessoProtocoloNumeracao::getProximoNumero();
     } catch (Exception $eErro) {

       $lSqlErro = true;
       $sMsgErro = $eErro->getMessage();
     }
     if ($lSqlErro == false) {
       $clprotprocesso->p58_hora       = db_hora();
       $clprotprocesso->p58_id_usuario = db_getsession("DB_id_usuario");
       $clprotprocesso->p58_coddepto   = db_getsession("DB_coddepto");
       $clprotprocesso->p58_interno    = 'false' ;
       $clprotprocesso->p58_publico    = 'false' ;
       $clprotprocesso->p58_instit     = db_getsession("DB_instit");
       $clprotprocesso->p58_numero     = "{$iNumeroProcesso}";
       $clprotprocesso->p58_ano        = db_getsession("DB_anousu");
       $clprotprocesso->incluir($p58_codproc);

       $p58_codproc = $clprotprocesso->p58_codproc;

       if ( $clprotprocesso->erro_status == '0' ) {
         $lSqlErro = true;
         $sMsgErro = $clprotprocesso->erro_msg;
       }
     }

     if (isset($oPost->docs) && $oPost->docs != "") {
       if ($lSqlErro == false) {
          $chaves = split("#",$oPost->docs);
          $chave  = count($chaves);
          for($x = 0; $x < $chave-1; $x++){
             $clprocprocessodoc->p81_codproc = $p58_codproc;
             $clprocprocessodoc->p81_coddoc  = $chaves[$x];
             $clprocprocessodoc->p81_doc     = 't';
             $clprocprocessodoc->incluir($p58_codproc,$chaves[$x]);
          }
          if ( $clprocprocessodoc->erro_status == '0' ) {
            $lSqlErro = true;
            $sMsgErro = $clprocprocessodoc->erro_msg;
          }
       }
     }

     if (isset($oPost->ndocs) && $oPost->ndocs != "") {
       if ($lSqlErro == false) {
         $chaves = split("#",$oPost->ndocs);
         $chave  = count($chaves);

         for( $i = 0; $i < $chave-1; $i++){
            $HTTP_POST_VARS['p81_doc']      = 'f';
            $clprocprocessodoc->p81_codproc = $p58_codproc;
            $clprocprocessodoc->p81_coddoc  = $chaves[$i];
            $clprocprocessodoc->p81_doc     = 'f';
            $clprocprocessodoc->incluir($p58_codproc,$chaves[$i]);
         }

         if ( $clprocprocessodoc->erro_status == '0' ) {
           $lSqlErro = true;
           $sMsgErro = $clprocprocessodoc->erro_msg;
         }
       }
     }

     if ($lSqlErro == false) {
       $sSql  = "select p54_codigo, p54_codcam from procvar where p54_codigo = $p58_codigo;";
       $rsSql = db_query($sSql);
       $iSql  = pg_num_rows($rsSql);
       if ($iSql > 0) {
          while ($ln = pg_fetch_array($rsSql)){
              $sSqlCam = "select nomecam,rotulo from db_syscampo where codcam = ".$ln["p54_codcam"];
              $rsSqlCam = db_query($sSqlCam);
              if (pg_numrows($rsSqlCam) > 0) {
                 $nomecam = trim(pg_result($rsSqlCam,0,"nomecam"));
                 $rotulo = trim(pg_result($rsSqlCam,0,"rotulo"));

                 $p55_codproc = $clprotprocesso->p58_codproc;
                 $p55_codvar = $ln["p54_codigo"];
                 $p55_codcam = $ln["p54_codcam"];

                 $clproctipovar->p55_conteudo = $$nomecam;
                 $clproctipovar->incluir($p55_codproc,$p55_codvar,$p55_codcam);

                if ( $clproctipovar->erro_status == '0' ) {
                   $lSqlErro = true;
                   $sMsgErro = "INFORMAR OS DADOS COMPLEMENTARES - Campo: $rotulo";
                }
              }
          }
       }

       if ( $clprocprocessodoc->erro_status == '0' ) {
         $lSqlErro = true;
         $sMsgErro = $clprocprocessodoc->erro_msg;
       }
     }

/** Extensão : Inicio [tramite_inicial_automatico] */
/** Extensão : Fim [tramite_inicial_automatico] */


    db_fim_transacao($lSqlErro);

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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" <?=$sOnLoad;?> >
<form name="form1" method="post" action="">
<br /><br />
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
      <?php
         require_once(modification("forms/db_frmprotprocesso.php"));
      ?>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_mostratipoproc1(chave1,chave2){
  var sUrl = "pro4_aba1protprocesso001.php?incpro=1&p58_codigo="+chave1+"&p51_descr="+chave2;
  parent.iframe_dadosprocesso.location.href = sUrl;
  parent.document.formaba.dadosprocesso.disabled      = false;
  parent.document.formaba.processosapensados.disabled = true;
}

function js_pesquisa(){
  db_iframe.jan.location.href = "func_tipoproc.php?grupo=1&funcao_js=parent.js_mostratipoproc1|0|1";
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
  parent.document.formaba.dadosprocesso.disabled      = false;
  parent.document.formaba.processosapensados.disabled = true;
}
</script>
</body>
<?

if (isset($oPost->btnincluir) && $oPost->btnincluir == 1) {
   if ( isset($sMsgErro) && $lSqlErro === true) {
      db_msgbox($sMsgErro);
      if (isset($oPost->p58_codigo) && $oPost->p58_codigo == "") {
      	 db_redireciona("pro4_aba1protprocesso001.php");
      }
   } else {

      if ( isset($lSqlErro) && $lSqlErro === false) {

        $sMsg  = "Inclusao efetuada com Sucesso\\n";
        $sMsg .= "Processo  : {$iNumeroProcesso}/".db_getsession("DB_anousu")."\\n";

        if (!empty($oPost->docs)) {
          $sMsg .= "Documento : {$oPost->docs} \\n";
        }
        $sMsg .= "Administrador: 1";
        db_msgbox($sMsg);

        echo "<script> window.open('pro4_capaprocesso.php?codproc=$p58_codproc','','location=0'); </script>";

        $result_param = $clprotparam->sql_record($clprotparam->sql_query_file());
        if ($clprotparam->numrows> 0) {
           db_fieldsmemory($result_param,0);

           if ($p90_emiterecib == "t") {
              echo " <script>
                        var sUrl1 = 'pro4_aba2protprocesso001.php?p58_codproc=$clprotprocesso->p58_codproc';
                  			var sUrl2 = 'pro4_aba1protprocesso002.php?alt=1&chavepesquisa=$clprotprocesso->p58_codproc';

                        if (confirm('Deseja Emitir Recibo?')) {
                           location.href='cai4_recibo001.php?p58_codproc=$p58_codproc&codtipo=$p58_codigo&incproc=true&mostramenu=true&sIframe=iframe_dadosprocesso';
                        } else if (confirm('Tem Processos à Apensar?')) {
                     			parent.iframe_processosapensados.location.href      = sUrl1;
                     			parent.iframe_dadosprocesso.location.href           = sUrl2;
                     			parent.document.formaba.processosapensados.disabled = false;
                     			parent.mo_camada('processosapensados');
                  			} else {
                     			parent.iframe_dadosprocesso.location.href           = sUrl2;
                        }
                     </script> ";

           }

        } else {
        	echo "<script>
        	        parent.iframe_dadosprocesso.location.href = 'pro4_aba1protprocesso001.php';
        	      </script>";
        }

      } else {
      	db_msgbox($sMsgErro);
      	db_redireciona("pro4_aba1protprocesso001.php");
      }
   }
}
?>
</html>