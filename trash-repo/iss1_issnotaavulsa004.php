<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_issnotaavulsa_classe.php");
include("classes/db_parissqn_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_issnotaavulsaservico_classe.php");

(integer)$q51_sequencial = null;
(integer)$db_opcao       = 1;
(integer)$db_botao       = true;
(integer)$iNota          = 0;;
(boolean)$lSqlErro       = false;
(string) $erro_msg       = null;
$clissnotaavulsa         = new cl_issnotaavulsa();
$clparissqn              = new cl_parissqn();
$clissbase               = new cl_issbase();
$rsParametros            = $clparissqn->sql_record($clparissqn->sql_query(null,"*"));
$oParametros             = db_utils::fieldsMemory($rsParametros,0);
$post                    = db_utils::postmemory($_POST);

if (isset($post->incluir)){

   $rsIssBase    = $clissbase->sql_record($clissbase->sql_query($post->q51_inscr));
   $oIssBase     = db_utils::fieldsMemory($rsIssBase,0);
   if ($oParametros->q60_notaavulsapesjur == 't' and strlen(trim($oIssBase->z01_cgccpf)) == 14){
     
        $rsTotalNotas = $clissnotaavulsa->sql_record($clissnotaavulsa->sql_query_baixa(null,"count(*) as numnotas",
                                                     null,"q51_inscr=".$post->q51_inscr
                                                    ." and q63_sequencial is null"));
        $oTotalNotas  =  db_utils::fieldsMemory($rsTotalNotas,0);
        if ($oTotalNotas->numnotas >= $oParametros->q60_notaavulsamax){

          $lSqlErro = true;
          $erro_msg = "Contribuinte já atingiu o limite máximo \\nde notas permitidas para pessoa juridíca";
          

        }

   }else if ($oParametros->q60_notaavulsapesjur == 'f' and strlen(trim($oIssBase->z01_cgccpf)) == 14){

      
      $lSqlErro = true;
      $erro_msg = "Não é permitido liberar notas avulsas para pessoas jurídicas.";

   }
   if (!$lSqlErro){
     db_inicio_transacao();
     $oPars                            = db_utils::fieldsMemory($clparissqn->sql_record($clparissqn->sql_query(null,'*')),0);
     $iNota                            = $oPars->q60_notaavulsaultimanota + 1;
     $clissnotaavulsa->q51_usuario     = db_getsession("DB_id_usuario");
     $clissnotaavulsa->q51_hora        = date("h:i");
     $clissnotaavulsa->q51_numnota     = $iNota;
     $clissnotaavulsa->q51_data        = date("Y-m-d",db_getsession("DB_datausu"));
     $clissnotaavulsa->q51_inscr       = $post->q51_inscr;
     $clissnotaavulsa->q51_obs         = $post->q51_obs;
     if (isset($post->q51_dtemiss)){
        $dtparte                          = split("/",$post->q51_dtemiss);
        $clissnotaavulsa->q51_dtemiss_dia = $dtparte[0];
        $clissnotaavulsa->q51_dtemiss_mes = $dtparte[1];
        $clissnotaavulsa->q51_dtemiss_ano = $dtparte[2];
        $clissnotaavulsa->q51_pdfnota     = '0';
     }else{

        $clissnotaavulsa->q51_dtemiss_dia = $post->q51_dtemiss_dia;
        $clissnotaavulsa->q51_dtemiss_mes = $post->q51_dtemiss_mes;
        $clissnotaavulsa->q51_dtemiss_ano = $post->q51_dtemiss_ano;
        $clissnotaavulsa->q51_pdfnota     = '0';
     }
     $clissnotaavulsa->incluir(null);
     
     if ($clissnotaavulsa->erro_status == 0){
         
         $lSqlErro = true;
         $erro_msg = $clissnotaavulsa->erro_msg;

     }
     if (!$lSqlErro){

        $clparissqn->q60_notaavulsaultimanota = $iNota;
        $clparissqn->alterarParametro();
     }
     db_fim_transacao($lSqlErro);
     if (!$lSqlErro){
         $q51_sequencial= $clissnotaavulsa->q51_sequencial;
     }
   }
}
$q51_dtemiss_dia = date("d",db_getsession("DB_datausu"));
$q51_dtemiss_mes = date("m",db_getsession("DB_datausu")); 
$q51_dtemiss_ano = date("Y",db_getsession("DB_datausu")); 

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
	<?
	include("forms/db_frmissnotaavulsaalt.php");
	?>
    </center>
</body>
</html>
<?
if(isset($post->incluir)){
  if($lSqlErro==true){
    db_msgbox($erro_msg);
    if($clissnotaavulsa->erro_campo!=""){
      echo "<script> document.form1.".$clissnotaavulsa->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clissnotaavulsa->erro_campo.".focus();</script>";
    };
  }else{
   if ($erro_msg != ''){ 
      db_msgbox($erro_msg); 
   }
   db_redireciona("iss1_issnotaavulsa005.php?liberaaba=true&chavepesquisa=$q51_sequencial");
  }
}
?>