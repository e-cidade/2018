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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_issarqsimples_classe.php"));
require_once(modification("classes/db_issarqsimplesreg_classe.php"));
require_once(modification("classes/db_issarqsimplesregissbase_classe.php"));
require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("classes/db_issbase_classe.php"));

require_once(modification("dbforms/db_funcoes.php"));

$objPost                     = db_utils::postmemory($_POST);
$clissarqsimples             = new cl_issarqsimples();
$clissarqsimplesreg          = new cl_issarqsimplesreg();
$oDaoIssarqSimplesRegIssbase = new cl_issarqsimplesregissbase();
$oDaoCgm                     = new cl_cgm();
$oDaoIssBase                 = new cl_issbase();
(integer)$db_opcao           = 1;
(boolean)$db_botao           = true;
(boolean)$sSqlErro           = false;
(string) $sErroMsg           = null;
(integer)$iTotalreg          = 0;
$dVlrTotal                    = 0;
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
<div class="container">
  <form name='form1' enctype="multipart/form-data" method='post'>

    <fieldset >
      <legend>Importar Arquivo de retorno - Simples Nacional</legend>

      <table>
         <tr>
           <td nowrap="nowrap">
              <strong>Arquivo de Retorno:</strong>
           </td>
           <td>
             <?
               db_input('arquivo',30,'',true,'file',$db_opcao,"onChange=\"if (this.value != ''){\$('db_opcao').disabled=false};\"",'','','');
             ?>
           </td>
         </tr>
      </table>
    </fieldset>
    <input name="importar" type="submit" id="db_opcao" value="Importar Arquivo" disabled />

    <div id='message'></div>
  </form>

</div>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?php
if (isset($objPost->importar)) {

   echo " <script>
            js_divCarregando('Validando Arquivo. Aguarde...', 'msgBox');
          </script>";

   $sMensagem = '';
   $linhaArq  = file( $_FILES["arquivo"]["tmp_name"]);
   $nomearq   = $_FILES["arquivo"]["name"];
   $totLinhas = count($linhaArq);

   for ($i = 0; $i < count($linhaArq); $i++){

     $tamLinha = strlen($linhaArq[$i]);
     if (($tamLinha != 502 && $tamLinha != 501 && $tamLinha != 500)){

       $sErroMsg  = "Linha $i Inconsistente. Tamanho de linha inválido.";
       break;
     }
   }

   if ($sErroMsg == null){

     reset($linhaArq);
     if (substr($linhaArq[0],0,1) != 1){
       $sErroMsg  = "Arquivo sem Header! Arquivo Inválido.";
     }

     if (substr($linhaArq[count($linhaArq)-1],0,1) != 9){
       $sErroMsg  = "Arquivo sem trailer! Arquivo Inválido.";
     }
   }

     if ($sErroMsg == null) {

       echo "<script> \$('message').innerHTML = 'importando Arquivo...'</script>";
       echo "<center>";
	       db_criatermometro("divterm",'concluido...','blue',1,null);
       echo "</center>";
       reset($linhaArq);
       $sSqlErro = false;
       db_inicio_transacao();

       for ($j = 0; $j < $totLinhas; $j++){

           $iTipoReg = substr($linhaArq[$j],0,1);
           if ($iTipoReg == 1){

              $clissarqsimples->q17_data        = substr($linhaArq[$j],29,8);
              $clissarqsimples->q17_nroremessa  = substr($linhaArq[$j],37,6);
              $clissarqsimples->q17_versao      = substr($linhaArq[$j],43,2);
              $clissarqsimples->q17_codbco      = substr($linhaArq[$j],75,3);
              $clissarqsimples->q17_qtdreg      = substr($linhaArq[$totLinhas-1],9,6);
              $clissarqsimples->q17_vlrtot      = (substr($linhaArq[$totLinhas-1],15,17)/100);
              $clissarqsimples->q17_instit      = db_getsession("DB_instit");
              $clissarqsimples->q17_nomearq     = $_FILES["arquivo"]["name"];

              $rsSimplesheader = $clissarqsimples->sql_record($clissarqsimples->sql_query(
                                                              null,"q17_sequencial,q17_nroremessa",null,
                                                             "q17_nroremessa=".$clissarqsimples->q17_nroremessa));
              if ($clissarqsimples->numrows > 0){

                 $sErroMsg = "Arquivo já importado.";
                 $sSqlErro = true;
                 break;
              } else {

                 $oid = pg_lo_import($_FILES["arquivo"]["tmp_name"]);
                 $clissarqsimples->q17_oidarq = $oid;
                 $clissarqsimples->Incluir(null);
                 if ($clissarqsimples->erro_status == 0){

                    $sSqlErro = true;
                    $sErroMsg = "erro:".$clissarqsimples->erro_msg;
                    break;
                 }
                 $iTotalreg++;
    			       db_atutermometro($j,$totLinhas,'divterm');
                 //registros da baixa
              }
            }
            if ($iTipoReg == 2 and !$sSqlErro){

              $clissarqsimplesreg->q23_issarqsimples = $clissarqsimples->q17_sequencial;
              $clissarqsimplesreg->q23_seqreg        = substr($linhaArq[$j] ,1  ,8);
              $clissarqsimplesreg->q23_dtarrec       = substr($linhaArq[$j] ,9  ,8);
              $clissarqsimplesreg->q23_dtvenc        = substr($linhaArq[$j] ,9  ,8);
              $clissarqsimplesreg->q23_cnpj          = substr($linhaArq[$j] ,74 ,14);
              $clissarqsimplesreg->q23_tiporec       = substr($linhaArq[$j] ,99 ,1);
              $clissarqsimplesreg->q23_anousu        = substr($linhaArq[$j] ,100,4);
              $clissarqsimplesreg->q23_mesusu        = substr($linhaArq[$j] ,104,2);
              $clissarqsimplesreg->q23_vlrprinc      = (float)(substr($linhaArq[$j],106,17)/100);
              $clissarqsimplesreg->q23_vlrmul        = (float)(substr($linhaArq[$j],123,17)/100);
              $clissarqsimplesreg->q23_vlrjur        = (float)(substr($linhaArq[$j],140,17)/100);
              $clissarqsimplesreg->q23_data          = substr($linhaArq[$j] ,174,8);
              $clissarqsimplesreg->q23_vlraut        = (substr($linhaArq[$j],204,17)/100);
              $clissarqsimplesreg->q23_nroaut        = substr($linhaArq[$j] ,221,23);
              $clissarqsimplesreg->q23_codbco        = substr($linhaArq[$j] ,244,3);
              $clissarqsimplesreg->q23_codage        = substr($linhaArq[$j] ,247,4);
              $clissarqsimplesreg->q23_codsiafi      = substr($linhaArq[$j] ,455,6);
              $clissarqsimplesreg->q23_codserpro     = substr($linhaArq[$j] ,461,17);
              $clissarqsimplesreg->q23_acao          = "0";
              $clissarqsimplesreg->incluir(null);
              $totalpar   = ((float)$clissarqsimplesreg->q23_vlrprinc +
                            (float)$clissarqsimplesreg->q23_vlrmul    +
                            (float)$clissarqsimplesreg->q23_vlrjur);
              $dVlrTotal += $totalpar;
              if ($clissarqsimplesreg->erro_status == 0) {

                  $sErroMsg = "registros:".$clissarqsimplesreg->erro_msg;
                  $sSqlErro = true;
                  break;
              }// end if erro simplesreg
              $iTotalreg++;

              /**
               * apos inclusao verificamos se o cnpj possui somente um cgm,
               * e se o cgm encontrado possui somente uma inscrição na issbase
               * se existir somente um registro em ambos select, inserimos o registro na issarqsimplesregissbase
               */
              $iIssArqSimplesReg     = $clissarqsimplesreg->q23_sequencial;
              $sIssArqSimplesRegCnpj = substr($linhaArq[$j] ,74 ,14);
              $sSqlCgm               = $oDaoCgm->sql_query(null,
                                                           "z01_numcgm",
                                                           null,
                                                           "z01_cgccpf = '{$sIssArqSimplesRegCnpj}'");
              $rsCgm                 = $oDaoCgm->sql_record($sSqlCgm);

              if ( $oDaoCgm->numrows == 1) {

                $oCompetencia = new DBDate($clissarqsimplesreg->q23_anousu . '-' . $clissarqsimplesreg->q23_mesusu . '-01');

                $iCgm        = db_utils::fieldsMemory($rsCgm, 0)->z01_numcgm;

                $sWhere  = " q02_numcgm  = {$iCgm} and ( q02_dtbaix is null ";
                $sWhere .= " or q02_dtbaix >=  '{$oCompetencia->getDate()}' ) ";

                $sSqlIssbase = $oDaoIssBase->sql_query_file(null, "q02_inscr", null, $sWhere);
                $rsIssBase   = $oDaoIssBase->sql_record($sSqlIssbase);

                if ($oDaoIssBase->numrows > 0) {

                  for ($iInscr = 0; $iInscr < $oDaoIssBase->numrows; $iInscr++) {

                    $iInscricao = db_utils::fieldsMemory($rsIssBase, $iInscr)->q02_inscr;

                    $oDaoIssarqSimplesRegIssbase->q134_issarqsimplesreg = $iIssArqSimplesReg;
                    $oDaoIssarqSimplesRegIssbase->q134_inscr            = $iInscricao;
                    $oDaoIssarqSimplesRegIssbase->incluir(null);
                    if ($oDaoIssarqSimplesRegIssbase->erro_status == '0'){

                      $sErroMsg = "issarqsimplesregissbase :".$oDaoIssarqSimplesRegIssbase->erro_msg;
                      $sSqlErro = true;
                      break;
                    }
                  }
                }
              }
           }

    		   db_atutermometro($j,$totLinhas,'divterm');
       }             //end for
       $totalclasse  = $clissarqsimples->q17_vlrtot;

       if ((round($dVlrTotal,2) !== round($totalclasse,2) && !$sSqlErro)){

          $sSqlErro  = true;
          $sErroMsg  = "Valor total não confere.";
       }

       if (($iTotalreg != (int)($clissarqsimples->q17_qtdreg-1)) && !$sSqlErro){

          $sSqlErro  = true;
          $sErroMsg  = "Número de registros não confere.";
       }

       db_fim_transacao($sSqlErro);
       $sMensagem = "Registros importados com Sucesso (Arquivo ".$nomearq.")";
       if ($sSqlErro){
         $sMensagem = $sErroMsg." (Arquivo ".$nomearq.")";
       }

   } else {
     $sMensagem = $sErroMsg ."(Arquivo ".$nomearq.")";
   }

   db_msgbox($sMensagem);
   echo " <script>js_removeObj('msgBox');</script>";
   db_redireciona("iss4_importaarqsimples001.php");
}