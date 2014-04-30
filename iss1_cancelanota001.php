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
include("classes/db_issnotaavulsacanc_classe.php");
include("classes/db_issnotaavulsanumpre_classe.php");
include("classes/db_issnotaavulsa_classe.php");
include("classes/db_cancdebitos_classe.php");
include("classes/db_cancdebitosreg_classe.php");
include("classes/db_cancdebitosproc_classe.php");
include("classes/db_cancdebitosprocreg_classe.php");
include("classes/db_arrepaga_classe.php");
include("classes/db_arrecant_classe.php");
include("classes/db_arrecad_classe.php");
include("dbforms/db_funcoes.php");
include ("libs/db_sql.php");

$post                  = db_utils::postmemory($_POST);
$clissnotaavulsacanc   = new cl_issnotaavulsacanc();
$clissnotaavulsanumpre = new cl_issnotaavulsanumpre();
$clcancdebitos         = new cl_cancdebitos();
$clcancdebitosreg      = new cl_cancdebitosreg();
$clcancdebitosproc     = new cl_cancdebitosproc();
$clcancdebitosprocreg  = new cl_cancdebitosprocreg();
$clarrepaga            = new cl_arrepaga();
$clarrecant            = new cl_arrecant();
$clarrecad             = new cl_arrecad();
(integer)$db_opcao     = 1;
(boolean)$db_botao     = true;
(boolean)$lSqlErro     = false;
(string)$erro_msg      = null;
(integer)$q52_numpre   = null;

if(isset($post->incluir)){
     
    //Verificando se a nota já foi paga, ou cancelada;
    $rsNumpre = $clissnotaavulsanumpre->sql_record($clissnotaavulsanumpre->sql_query(null,"*",
                                                   null,"q52_issnotaavulsa=".$post->q63_issnotaavulsa));
    if ($clissnotaavulsanumpre->numrows > 0){
     
      $oNumpre    = db_utils::fieldsMemory($rsNumpre,0);
      $q52_numpre = $oNumpre->q52_numpre;
      //Nota paga
      $rsPago     = $clarrepaga->sql_record($clarrepaga->sql_query(null,"*",null,"k00_numpre = ".$oNumpre->q52_numpre));
      if ($clarrepaga->numrows > 0){

          $lSqlErro = true;
          $erro_msg = "Nota já paga. Não pode ser Cancelada";
      }
      //Nota Cancelada
      $rsPago  = $clarrecant->sql_record($clarrecant->sql_query(null,"*",null,"k00_numpre = ".$oNumpre->q52_numpre));
      if ($clarrecant->numrows > 0){

          $lSqlErro = true;
          $erro_msg = "Nota já Cancelada. Não pode ser Cancelada";
      }
          
   }
   if (!$lSqlErro){ 

     db_inicio_transacao();
     $clissnotaavulsacanc->q63_usuario       = db_getsession("DB_id_usuario");
     $clissnotaavulsacanc->q63_data          = date("Y/m/d",db_getsession("DB_datausu"));
     $clissnotaavulsacanc->q63_hora          = date("h:i"); 
     $clissnotaavulsacanc->q63_motivo        = $post->q63_motivo; 
     $clissnotaavulsacanc->q63_issnotaavulsa = $post->q63_issnotaavulsa; 
     $clissnotaavulsacanc->incluir(null);
     if ($clissnotaavulsacanc->erro_status == 0){
        
        $lSqlErro = true;
        $erro_msg = $clissnotaavulsacanc->erro_msg." Nota";
      
     }
   }
   //se possuir numpre, incluimos o debito na cancdebitos, e excluimos da arrecad e incluimos na arrecant
   if (!$lSqlErro and $q52_numpre != null){
    
    //Incluindo na cancdebitos
      $clcancdebitos->k20_descr           = $post->q63_motivo;
      $clcancdebitos->k20_data            = date("Y/m/d",db_getsession("DB_datausu"));
      $clcancdebitos->k20_hora            = date("h:i");
      $clcancdebitos->k20_instit          = db_getsession("DB_instit");
      $clcancdebitos->k20_usuario         = db_getsession("DB_id_usuario");
      $clcancdebitos->k20_cancdebitostipo = 1;
      $clcancdebitos->incluir(null);
      if ($clcancdebitos->erro_status == 0){

          $lSqlErro = true;
          $erro_msg = $clcancdebitos->erro_msg." Cancdebitos";
          
      }else{
       
          //incluindo na cancdebitosrec 
          $rsArrecad                    = $clarrecad->sql_record($clarrecad->sql_query(null,"*",null,"arrecad.k00_numpre = $oNumpre->q52_numpre"));
          $oArrecad                     = db_utils::fieldsMemory($rsArrecad,0);
          $clcancdebitosreg->k21_codigo = $clcancdebitos->k20_codigo;
          $clcancdebitosreg->k21_numpre = $oArrecad->k00_numpre;
          $clcancdebitosreg->k21_numpar = $oArrecad->k00_numpar;
          $clcancdebitosreg->k21_receit = $oArrecad->k00_receit;
          $clcancdebitosreg->k21_data   = date("Y-m-d",db_getsession("DB_datausu"));
          $clcancdebitosreg->k21_hora   = date("H:i");
          $clcancdebitosreg->k21_obs    = $post->q63_motivo; 
          $clcancdebitosreg->incluir(null);

          if ($clcancdebitosreg->erro_status == 0){
        
             $lSqlErro = true;
             $erro_msg = $clcancdebitosreg->erro_msg."";

          }
          if (!$lSqlErro){

            //incluindo na cancdebitosproc
            $clcancdebitosproc->k23_data            = date ("Y-m-d",db_getsession("DB_datausu")); 
            $clcancdebitosproc->k23_hora            = date ("h-i"); 
            $clcancdebitosproc->k23_usuario         = db_getsession("DB_id_usuario"); 
            $clcancdebitosproc->k23_obs             = $post->q63_motivo;
            $clcancdebitosproc->k23_cancdebitostipo = 1;
            $clcancdebitosproc->incluir(null); 
            if ($clcancdebitosproc->erro_status == 0){

              $lSqlErro = true;
              $erro_msg = $clcancdebitosproc->erro_msg."cancdebitosproc";

            }else{
               
              //incluindo na cancdebitosprocreg 
              $result_deb = debitos_numpre($q52_numpre, 0, 0, db_getsession("DB_anousu"), db_getsession("DB_anousu"),1);
              $oDeb       = db_utils::fieldsMemory($result_deb,0,1);
              $clcancdebitosprocreg->k24_codigo         = $clcancdebitosproc->k23_codigo;
              $clcancdebitosprocreg->k24_cancdebitosreg = $clcancdebitosreg->k21_sequencia;
              $clcancdebitosprocreg->k24_vlrhis         = $oDeb->vlrhis;
              $clcancdebitosprocreg->k24_vlrcor         = $oDeb->vlrcor;
              $clcancdebitosprocreg->k24_juros          = $oDeb->vlrjuros;
              $clcancdebitosprocreg->k24_multa          = $oDeb->vlrmulta;
              $clcancdebitosprocreg->k24_desconto       = $oDeb->vlrdesconto;
              $clcancdebitosprocreg->incluir(null);
              if ($clcancdebitosprocreg->erro_status == 0){
                 
                 db_msgbox($clcancdebitosprocreg->erro_msg);
                 $lSqlErro = true;
                 $erro_msg = $clcancdebitosprocreg->erro_msg."cancdebitosprocreg";

               }
               if (!$lSqlErro){

                   $clarrecant->incluir_arrecant($oArrecad->k00_numpre, $oArrecad->k00_numpar, $oArrecad->k00_receit, true);
                   if ($clarrecant->erro_status == 0){

                       $lSqlErro = true;
                       $erro_msg = $clarrecant->erro_msg."Arrecant";

                  }
               }
            }
         }
     }
  }
  if ($lSqlErro){

      $clissnotaavulsacanc->erro_msg    = $erro_msg;
      $clissnotaavulsacanc->erro_status = 0;

  }
  db_fim_transacao($lSqlErro);
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
<table  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
    <center>
	<?
	include("forms/db_frmissnotaavulsacanc.php");
	?>
    </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
</script>
<?
if(isset($post->incluir)){
  if($clissnotaavulsacanc->erro_status=="0"){
    $clissnotaavulsacanc->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clissnotaavulsacanc->erro_campo!=""){
      echo "<script> document.form1.".$clissnotaavulsacanc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clissnotaavulsacanc->erro_campo.".focus();</script>";
    }
  }else{
    $clissnotaavulsacanc->erro(true,true);
  }
}
?>