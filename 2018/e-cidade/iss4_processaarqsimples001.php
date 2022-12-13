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
require("libs/db_conecta.php");
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_issarqsimples_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_issarqsimplesdisarq_classe.php");
include("classes/db_disarq_classe.php");
include("classes/db_disbanco_classe.php");
include("classes/db_issarqsimplesregdisbanco_classe.php");
include("classes/db_issarqsimplesregerro_classe.php");
include("classes/db_issarqsimplesregissvar_classe.php");
include("classes/db_issarqsimplesreg_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_arrehist_classe.php");
include("classes/db_arrecad_classe.php");
include("classes/db_arreinscr_classe.php");
include("classes/db_arrenumcgm_classe.php");
include("classes/db_isscalc_classe.php");
include("classes/db_parissqn_classe.php");
include("classes/db_issvar_classe.php");
include("dbforms/db_funcoes.php");

$post                       = db_utils::postmemory($_POST);
$clissarqsimples            = new cl_issarqsimples();
$clissbase                  = new cl_issbase();
$clisscalc                  = new cl_isscalc();
$clissvar                   = new cl_issvar();
$clissarqsimplesdisarq      = new cl_issarqsimplesdisarq();
$clissarqsimplesreg         = new cl_issarqsimplesreg();
$clissarqsimplesregerro     = new cl_issarqsimplesregerro();
$clissarqsimplesregissvar   = new cl_issarqsimplesregissvar();
$clissarqsimplesregdisbanco = new cl_issarqsimplesregdisbanco();
$clparissqn                 = new cl_parissqn();
$cldisbanco                 = new cl_disbanco();
$cldisarq                   = new cl_disarq();              
$clcgm                      = new cl_cgm();              
$clarrehist                 = new cl_arrehist();
$clarrecad                  = new cl_arrecad();
$clarreinscr                = new cl_arreinscr();
$clarrenumcgm               = new cl_arrenumcgm();
(integer)$db_opcao          = 1;
(boolean)$db_botao          = true;
(boolean)$lSqlErro          = false;
(boolean)$lSqlErroreg       = false;
(string) $sErroMsg          = null;
(integer)$iTotalreg         = 0;
(float)  $dVlrReg           = 0;  
(float)  $dVlrTotal         = 0;
$itensErro                  = array();
$itensAviso                 = array();
$iNumpre                    = null;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_emiteinconsistencias(sNomeArquivo,iCodArquivo,totalErros,iTipo){

   if (iTipo == 1){
       sMensagem = "Registros não processados.\n Foram encontrados "+totalErros+" registro(s) com inconsistência(s)\n Emitir Relatório com inconsistências?";
   }else{
       sMensagem = "Registros  processados.\n Foram encontrados "+totalErros+" registro(s) com Avisos(s)\n Emitir Relatório?";
   }
   if (confirm(sMensagem)){
       
       url    = 'iss2_relinconsistenciassimples.php?q17_nomearq='+sNomeArquivo+'&q17_sequencial='+iCodArquivo+'&q49_tipo='+iTipo;
       janRel = window.open(url,'','location=0');
   }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" enctype="form/" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="25">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<?
 include_once("forms/db_frmprocessaarqsimples.php");
?>
 </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset($post->processar)){
 
   $rsPars       = $clparissqn->sql_record($clparissqn->sql_query(null,'*')); 
   echo pg_last_error();
   $oPars        = db_utils::fieldsMemory($rsPars,0);
   $sDeleteErro  = "delete ";
   $sDeleteErro .= "  from issarqsimplesregerro";
   $sDeleteErro .= " using issarqsimplesreg";
   $sDeleteErro .= " where q23_sequencial    = q49_sequencial";
   $sDeleteErro .= "   and q23_issarqsimples = ".$post->q17_sequencial;
   $rsDelete     = pg_query($sDeleteErro);
   db_inicio_transacao();
   echo "<center>";
   db_criatermometro("divterm",'concluido...','blue',1,"Processando registros...");
   echo "</center>";
   $rsReg                   = $clissarqsimplesreg->sql_record($clissarqsimplesreg->sql_query(
                                                              null,"*",null,"q23_issarqsimples=".$post->q17_sequencial)); 
   $iNumRowsReg             = $clissarqsimplesreg->numrows;
   $cldisarq->id_usuario    = db_getsession("DB_id_usuario");
   $cldisarq->k15_codbco    = $post->k15_codbco;
   $cldisarq->k15_codage    = $post->k15_codage;
   $cldisarq->arqret        = $post->q17_nomearq;
   $cldisarq->textoret      = "Object oid";
   $cldisarq->dtretorno     = date("Y-m-d",db_getsession("DB_datausu"));
   $cldisarq->dtarquivo     = date("Y-m-d",db_getsession("DB_datausu"));
   $cldisarq->k00_conta     = $post->k15_conta;  
   $cldisarq->autent        = "false";
   $cldisarq->incluir(null);
   if (!$rsDelete){
       $lSqlErro = true;
       $sErroMsg = "Erro ao Deletar inconsistências.\\n Processamento cancelado.";
          
    }
   if ($cldisarq->erro_status == 0){

       $sErroMsg = "Erro Disarq".$cldisarq->erro_msg;
       $lSqlErro = true;
   }else{

       $clissarqsimplesdisarq->q43_issarqsimples = $post->q17_sequencial;
       $clissarqsimplesdisarq->q43_disarq        = $cldisarq->codret;
       $clissarqsimplesdisarq->incluir(null);
       if ($clissarqsimplesdisarq->erro_status == 0){

           $sErroMsg = $cldisarq->erro_msg;
           $lSqlErro = true;
       }
          
   }

   //deletndo issarqregerro para o arquivo.
   //ira verificar consistencias.
   if (!$lSqlErro){
       for ($i = 0; $i < $iNumRowsReg; $i++){
          
          $lCgmOk      = true;
          $oRegSimples = db_utils::fieldsMemory($rsReg,$i); 
          // $dVlrReg     = ($oRegSimples->q23_vlrprinc + $oRegSimples->q23_vlrjur+$oRegSimples->q23_vlrmul);
          $dVlrReg     = ($oRegSimples->q23_vlrprinc + $oRegSimples->q23_vlrjur+$oRegSimples->q23_vlrmul); 
          $iNumpre     = null;
          $iNumpar     = null;
          $rsCgm       = $clcgm->sql_record($clcgm->sql_query(null,"*",null,"z01_cgccpf='".$oRegSimples->q23_cnpj."'"));
          if ($clcgm->numrows == 0){
            
             $itensErro[$oRegSimples->q23_sequencial] = "CNPJ ".$oRegSimples->q23_cnpj." não existe no cadastro do CGM"; 
             $lSqlErroreg = true;
             $lCgmOk      = false;

          }else if ($clcgm->numrows > 1) {

            $cgmNumRows  = $clcgm->numrows;
            $cv          = "";
            $sCGMS       = "";
            for ($iCgm = 0;$iCgm < $cgmNumRows;$iCgm++){
               
               $oCGM   = db_utils::fieldsMemory($rsCgm,$iCgm);
               $sCGMS .= $cv.$oCGM->z01_numcgm;
               $cv    = ", ";
            }
            $itensErro[$oRegSimples->q23_sequencial] = "CNPJ ".$oRegSimples->q23_cnpj."  cadastro nos cgms ($sCGMS)"; 
            $lSqlErroreg = true;
            unset($oCGM);
            $lCgmOk    = false;
         }
         if ($oRegSimples->q23_acao == 1 and $lCgmOk){
          //inclui um isscomplementar 
            $oCGM   = db_utils::fieldsMemory($rsCgm,0);
            $rsNumpre  = pg_exec("select nextval('numpref_k03_numpre_seq') as k03_numpre");
            $oNumpre   = db_utils::fieldsmemory($rsNumpre,0);
            $iNumpre  = $oNumpre->k03_numpre;
            $iNumpar  = $oRegSimples->q23_mesusu;
            $clissvar2 = new cl_issvar();
            $clissvar2->q05_numpre = $oNumpre->k03_numpre;
            $clissvar2->q05_numpar = $iNumpar;
            $clissvar2->q05_ano    = $oRegSimples->q23_anousu;
            $clissvar2->q05_mes    = $oRegSimples->q23_mesusu;
            $clissvar2->q05_histor = "Referente a Baixa Simples nacional competencia ".$oRegSimples->q23_mesusu."/".$oRegSimples->q23_anousu;
            $clissvar2->q05_aliq   = $oPars->q60_aliq;
            $clissvar2->q05_valor  = "0";
            $clissvar2->q05_bruto  = "0";
            $clissvar2->q05_vlrinf = $dVlrReg;
            $clissvar2->incluir(null);
            $q05_codigo   = $clissvar2->q05_codigo;
            if ($clissvar2->erro_status == 0){
              
              $sErroMsg    = "Iss Complementar<br>".$clissvar2->erro_msg;
              $lSqlErroreg = true;

            }else{
               $clarrecad->k00_numpre = $oNumpre->k03_numpre;
               $clarrecad->k00_numpar = $iNumpar;
               $clarrecad->k00_numcgm = $oCGM->z01_numcgm;
               $clarrecad->k00_dtoper = $oRegSimples->q23_dtvenc;
               $clarrecad->k00_receit = $oPars->q60_receit;
               $clarrecad->k00_hist   = $oPars->q60_histsemmov;
               $clarrecad->k00_valor  = $dVlrReg;
               $clarrecad->k00_dtvenc = $oRegSimples->q23_dtvenc;
               $clarrecad->k00_numtot = 1;
               $clarrecad->k00_numdig = "0";
               $clarrecad->k00_tipo   = $oPars->q60_tipo;
               $clarrecad->k00_tipojm = "0";
               $clarrecad->incluir(null);
               if ($clarrecad->erro_status == 0){
                
                 $sErroMsg    ="ARRECAD <BR>". $clarrecad->erro_msg;
                 $lSqlErroreg = true;
               }
            }
            $itensAviso[$oRegSimples->q23_sequencial]  = " CNPJ ".$oRegSimples->q23_cnpj.", CGM ".$oCGM->z01_numcgm;
            $itensAviso[$oRegSimples->q23_sequencial] .= " lancado  ISS Complementar ( Numpre $iNumpre )  para  ".$oRegSimples->q23_mesusu."/".$oRegSimples->q23_anousu;
         }else if ($oRegSimples->q23_acao == 0 and $lCgmOk){
            // -- testa aqui o cnpj possui mais de uma inscricao. se sim, gera inconsistencia
            $oCGM   = db_utils::fieldsMemory($rsCgm,0);
            $rsBase = $clissbase->sql_record($clissbase->sql_query(null,"q02_inscr,z01_numcgm,z01_cgccpf",null,
                                            "z01_numcgm=".$oCGM->z01_numcgm." 
                                             and (q02_dtbaix is null 
                                                  or q02_dtbaix > '".date("Y-m-d",db_getsession("DB_datausu"))."'::date)" )); 
            if ($clissbase->numrows == 0){

               $itensErro[$oRegSimples->q23_sequencial] = " CNPJ ".$oRegSimples->q23_cnpj." Sem Inscrição Ativa"; 
               $lSqlErroreg = true;

            }else if ($clissbase->numrows > 1){

               $sVi    = ''; 
               $sInscr = '';
               for ($j = 0;$j < $clissbase->numrows; $j++){
                   
                  $oInscr  = db_utils::fieldsMemory($rsBase,$j);
                  $sInscr .= $sVi.$oInscr->q02_inscr;
                  $sVi     = ", ";
                 
               }
               $itensErro[$oRegSimples->q23_sequencial] = "CNPJ ".$oRegSimples->q23_cnpj.", CGM ".$oCGM->z01_numcgm." com mais de um álvara ($sInscr)"; 
               $lSqlErroreg = true;

            }else{
              
               //testando se existe um calculo para o ano da competencia do registro.
               $oInscr  = db_utils::fieldsMemory($rsBase,0);
               $rsCalc = $clisscalc->sql_record($clisscalc->sql_query($oRegSimples->q23_anousu,$oInscr->q02_inscr,3));

               if ( @pg_num_rows($rsCalc) == 0 ) {
                  $itensErro[$oRegSimples->q23_sequencial]  = " CNPJ ".$oRegSimples->q23_cnpj.", CGM ".$oCGM->z01_numcgm;
                  $itensErro[$oRegSimples->q23_sequencial] .= " Álvara ".$oInscr->q02_inscr; 
                  $itensErro[$oRegSimples->q23_sequencial] .= " sem cálculo para ".$oRegSimples->q23_anousu;
                  $lSqlErroreg = true;
               }

               //testando se existe issvar para o mes/competencia
               $oCalc = @db_utils::fieldsMemory($rsCalc,0);
	             $rsVar = $clissvar->sql_record($clissvar->sql_query_arreinscr(null,
	                                                                           "*",
	                                                                           null,
	                                                                           "    q05_ano    = ".$oRegSimples->q23_anousu."
						                                                                  and q05_mes    = ".$oRegSimples->q23_mesusu."
						                                                                  and k00_inscr  = ".$oInscr->q02_inscr));
 
                 if ($clissvar->numrows != 1){
                                      
                    $itensErro[$oRegSimples->q23_sequencial]  = " CNPJ ".$oRegSimples->q23_cnpj.", CGM ".$oCGM->z01_numcgm;
                    $itensErro[$oRegSimples->q23_sequencial] .= " Álvara ".$oInscr->q02_inscr; 
                    $itensErro[$oRegSimples->q23_sequencial] .= " sem lançamento ISS variavel para ".$oRegSimples->q23_mesusu."/".$oRegSimples->q23_anousu;
                    $lSqlErroreg = true;

                 }else{
                    //testando se existe debito na arrecad
                    $oIssVar      = db_utils::fieldsMemory($rsVar,0); 
                    $q05_codigo   = $oIssVar->q05_codigo;
                    $sSqlArrecad  = "select k00_numpre,k00_numpar,k00_hist,k00_dtoper ";
                    $sSqlArrecad .= "  from arrecad " ;
                    $sSqlArrecad .= " where k00_numpre = ".$oIssVar->q05_numpre;
                    $sSqlArrecad .= "   and k00_numpar = ".$oIssVar->q05_numpar;
                    $sSqlArrecad .= "   and k00_receit = ".$oCalc->q01_recei;
                    $rsArrecad    = pg_query($sSqlArrecad);
                    if (pg_num_rows($rsArrecad)  != 1){
                       
                        //inclui um isscomplementar 
                        $rsNumpre  = pg_exec("select nextval('numpref_k03_numpre_seq') as k03_numpre");
                        $oNumpre   = db_utils::fieldsmemory($rsNumpre,0);
                        $iNumpre  = $oNumpre->k03_numpre;
                        $iNumpar  = $oRegSimples->q23_mesusu;
                        $clissvar2 = new cl_issvar();
                        $clissvar2->q05_numpre = $oNumpre->k03_numpre;
                        $clissvar2->q05_numpar = $iNumpar;
                        $clissvar2->q05_ano    = $oRegSimples->q23_anousu;
                        $clissvar2->q05_mes    = $oRegSimples->q23_mesusu;
                        $clissvar2->q05_histor = "Referente a Baixa Simples nacional competencia ".$oRegSimples->q23_mesusu."/".$oRegSimples->q23_anousu;
                        $clissvar2->q05_aliq   = $oPars->q60_aliq;
                        $clissvar2->q05_valor  = "0";
                        $clissvar2->q05_bruto  = "0";
                        $clissvar2->q05_vlrinf = $dVlrReg;
                        $clissvar2->incluir(null);
                        $q05_codigo   = $clissvar2->q05_codigo;
                        if ($clissvar2->erro_status == 0){
                            
                            $sErroMsg    = "Iss Complementar<br>".$clissvar2->erro_msg;
                            $lSqlErroreg = true;

                        }else{
                           $clarrecad->k00_numpre = $oNumpre->k03_numpre;
                           $clarrecad->k00_numpar = $iNumpar;
                           $clarrecad->k00_numcgm = $oCGM->z01_numcgm;
                           $clarrecad->k00_dtoper = $oRegSimples->q23_dtvenc;
                           $clarrecad->k00_receit = $oPars->q60_receit;
                           $clarrecad->k00_hist   = $oPars->q60_histsemmov;
                           $clarrecad->k00_valor  = $dVlrReg;
                           $clarrecad->k00_dtvenc = $oRegSimples->q23_dtvenc;
                           $clarrecad->k00_numtot = 1;
                           $clarrecad->k00_numdig = "0";
                           $clarrecad->k00_tipo   = $oPars->q60_tipo;
                           $clarrecad->k00_tipojm = "0";
                           $clarrecad->incluir(null);
                           if ($clarrecad->erro_status == 0){
                              
                              $sErroMsg    ="ARRECAD <BR>". $clarrecad->erro_msg;
                              $lSqlErroreg = true;
                           }else{

                               $clarreinscr->k00_inscr  = $oInscr->q02_inscr;
                               $clarreinscr->k00_numpre = $iNumpre;
                               $clarreinscr->k00_perc   = 100;
                               $clarreinscr->incluir($iNumpre,$oInscr->q02_inscr);                                                     
                               if ($clarreinscr->erro_status == 0){
                                  
                                  $sErroMsg    ="ARREinscr <BR>". $clarreinscr->erro_msg;
                                  $lSqlErroreg = true;
                               }
                           }


                        }
                        $itensAviso[$oRegSimples->q23_sequencial]  = " CNPJ ".$oRegSimples->q23_cnpj.", CGM ".$oCGM->z01_numcgm;
                        $itensAviso[$oRegSimples->q23_sequencial] .= " Álvara ".$oInscr->q02_inscr; 
                        $itensAviso[$oRegSimples->q23_sequencial] .= " lancado  ISS Complementar ( Numpre $iNumpre )  para  ".$oRegSimples->q23_mesusu."/".$oRegSimples->q23_anousu;
                        //$lSqlErroreg = true
                    }
                    if ($iNumpre == null){

                       $iNumpre = $oIssVar->q05_numpre;
                       $iNumpar = $oIssVar->q05_numpar;
                    }
                 }
            }
         }
   if (!$lSqlErroreg){
      
       //Cadastramos na disbanco e issarqsimplesregdisbanco       
      $cldisbanco->codret     = $cldisarq->codret;
      $cldisbanco->k15_codbco = $post->k15_codbco;
      $cldisbanco->k15_codage = $post->k15_codage;
      $cldisbanco->dtarq      = $oRegSimples->q17_data;
      $cldisbanco->dtpago     = $oRegSimples->q23_dtarrec;
      $cldisbanco->vlrpago    = $dVlrReg;
      $cldisbanco->vlrjuros   = "0";
      $cldisbanco->vlrmulta   = "0";
      $cldisbanco->vlrdesco   = "0";
      $cldisbanco->cedente    = null;
      $cldisbanco->vlrtot     = $dVlrReg;
      $cldisbanco->vlrcalc    = "0";
      $cldisbanco->classi     = 'false';
      $cldisbanco->k00_numpre = $iNumpre;
      $cldisbanco->k00_numpar = $iNumpar;
      $cldisbanco->instit     = db_getsession('DB_instit');
      $cldisbanco->convenio   = null;
      $cldisbanco->incluir(null);
      if ($cldisbanco->erro_status == 0){
         $sErroMsg    = "erro disbanco \\n".$cldisbanco->erro_msg;
         $lSqlErroreg = true;

      }
      if (!$lSqlErroreg){

         $clissarqsimplesregdisbanco->q44_issarqsimplesreg = $oRegSimples->q23_sequencial;
         $clissarqsimplesregdisbanco->q44_disbanco         = $cldisbanco->idret;
         $clissarqsimplesregdisbanco->incluir(null);
         if ($clissarqsimplesregdisbanco->erro_status == 0){
                      
            $sErroMsg    = " ".$clissarqsimplesregdisbanco->erro_msg;
            $lSqlErroreg = true;

         }
      }
      if (!$lSqlErroreg){

         $clissarqsimplesregissvar->q68_issvar           = $q05_codigo;
         $clissarqsimplesregissvar->q68_issarqsimplesreg = $oRegSimples->q23_sequencial;
         $clissarqsimplesregissvar->incluir(null);
         if ($clissarqsimplesregissvar->erro_status == 0){

             $sErroMsg     = " Erro ao incluir issarqsimplesregissvar. Contate o suporte\\n ";
             $sErroMsg    .= $clissarqsimplesregissvar->erro_msg;
             $lSqlErroreg  = true;

          }else{

             $clissvar->q05_vlrinf = $dVlrReg;
             $clissvar->q05_codigo = $q05_codigo;
             $clissvar->alterar($q05_codigo);
             if ($clissvar->erro_status == 0){

                 $sErroMsg    = $clissvar->erro_msg."$dVlrReg";
                 $lSqlErroreg = true;
                            
             }else{
                         
                $clarrehist->k00_numpre     = $iNumpre;      
                $clarrehist->k00_numpar     = $iNumpar;
                $clarrehist->k00_hist       = $oPars->q60_histsemmov;
                $clarrehist->k00_dtoper     = $oRegSimples->q23_dtvenc;
                $clarrehist->k00_hora       = db_hora();
                $clarrehist->k00_id_usuario = db_getsession("DB_id_usuario");
                $clarrehist->k00_histtxt    = "BAIXA SIMPLES NACIONAL COMPETÊNCIA ".str_pad($oRegSimples->q23_mesusu,2,"0",STR_PAD_LEFT)."/".$oRegSimples->q23_anousu;
                $clarrehist->k00_limithist  = date("Y-m-d",db_getsession("DB_datausu"));
                $clarrehist->incluir(null);
                if ($clarrehist->erro_status == 0){
                    $sErroMsg    = "Arrehist:\\n".$clarrehist->erro_msg;
                    $lSqlErroreg = true;
                }
             }
           }
        }
     }
    db_atutermometro($i,$iNumRowsReg,'divterm');
  }
}
      
 if (!$lSqlErroreg){
     reset($itensAviso);
     foreach ($itensAviso as $chave => $valor){
             
           $clissarqsimplesregerro->q49_sequencial = $chave;
           $clissarqsimplesregerro->q49_erro       = $valor;
           $clissarqsimplesregerro->q49_tipo       = 2;
           $clissarqsimplesregerro->incluir($clissarqsimplesregerro->q49_sequencial);
           if ($clissarqsimplesregerro->erro_status == 0){

              echo $clissarqsimplesregerro->erro_msg."<br>";
           }

        }
         db_fim_transacao(false);
         if (count($itensAviso) > 0){
         echo "<script>
               js_emiteinconsistencias('".$post->q17_nomearq."','".$post->q17_sequencial."',".count($itensAviso).",2);
               </script>";
         }else{
           db_msgbox("Processamento concluido com sucesso.");                    
         }
      }else{
         db_fim_transacao(true);
         db_inicio_transacao(); 
        foreach ($itensErro as $chave => $valor){
             
           $clissarqsimplesregerro->q49_sequencial = $chave;
           $clissarqsimplesregerro->q49_erro       = $valor;
           $clissarqsimplesregerro->q49_tipo       = 1;
           $clissarqsimplesregerro->incluir($clissarqsimplesregerro->q49_sequencial);
           if ($clissarqsimplesregerro->erro_status == 0){

              echo $clissarqsimplesregerro->erro_msg."<br>";
           }

        }
        echo $sErroMsg;
        db_fim_transacao(false);
        echo "<script>
               js_emiteinconsistencias('".$post->q17_nomearq."','".$post->q17_sequencial."',".count($itensErro).",1);
               </script>";

      }
   db_redireciona("iss4_processaarqsimples001.php");
   if ($lSqlErro){

       db_msgbox($sErroMsg);
       db_fim_transacao($lSqlErro);
   }

}
?>
<script>
</script>