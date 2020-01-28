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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_base_classe.php");
require_once("classes/db_baseserie_classe.php");
require_once("classes/db_basediscglob_classe.php");
require_once("classes/db_escolabase_classe.php");
require_once("classes/db_baseregimematdiv_classe.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clbase = new cl_base;
$clescolabase = new cl_escolabase;
$clbaseserie = new cl_baseserie;
$clbasediscglob = new cl_basediscglob;
$clbaseregimematdiv = new cl_baseregimematdiv;
$oDaoSerie          = db_utils::getdao('serie');
$oDaoCursoAtoSerie  = db_utils::getdao('cursoatoserie');
$oDaoBaseAto        = db_utils::getdao('baseato');
$oDaoBaseAtoSerie   = db_utils::getdao('baseatoserie');
$db_opcao = 1;
$db_opcao1 = 1;
$db_botao = true;
if (isset($incluir)) {

  db_inicio_transacao();
  $clbase->incluir(null);
  if ($clbase->erro_status != "0") {
 
    $clbaseserie->incluir($clbase->ed31_i_codigo);
    if ($clbaseserie->erro_status != '0') {
    
      if ($ed31_c_contrfreq=="G") {
    
       $clbasediscglob->incluir($clbase->ed31_i_codigo);
       if ($clbasediscglob->erro_status == '0') {
    
         $clbase->erro_status = '0';
         $clbase->erro_msg    = $clbasediscglob->erro_msg;
    
       }
    
      }
    
    } else {
    
      $clbase->erro_status = '0';
      $clbase->erro_msg    = $clbaseserie->erro_msg;
    
    }
    
    if ($clbase->erro_status != '0') {
    
      $clescolabase->ed77_i_escola = db_getsession("DB_coddepto");
      $clescolabase->ed77_i_base = $clbase->ed31_i_codigo;
      $clescolabase->incluir(null);
      if ($clescolabase->erro_status != '0') {
        
        if ($ed218_c_divisao == "S") {
    
          for ($r=0;$r<count($divisao);$r++) {
    
            $clbaseregimematdiv->ed224_i_regimematdiv = $divisao[$r];
            $clbaseregimematdiv->ed224_i_base = $clbase->ed31_i_codigo;
            $clbaseregimematdiv->incluir(null);
            if ($clbaseregimematdiv->erro_status == '0') {
    
              $clbase->erro_status = '0';
              $clbase->erro_msg    = $clbaseregimematdiv->erro_msg;
    
            }
    
          }
    
        }
 
      } else {

        $clbase->erro_status = '0';
        $clbase->erro_msg    = $clescolabase->erro_msg;

      }

    }

    // Obtenção e réplica dos atos legais vinculados às etapas do curso que 
    if ($clbase->erro_status != '0') {

      $iIni    = 0;
      $iFim    = 0;
      $iEns    = 0;
      $sCampos = 'ed11_i_sequencia';
      $sSql    = $oDaoSerie->sql_query_file($ed87_i_serieinicial, $sCampos.', ed11_i_ensino ');
      $rs      = $oDaoSerie->sql_record($sSql);
      if ($oDaoSerie->numrows > 0) {

        $iIni = db_utils::fieldsmemory($rs, 0)->ed11_i_sequencia;
        $iEns = db_utils::fieldsmemory($rs, 0)->ed11_i_ensino;

      }
      $sSql = $oDaoSerie->sql_query_file($ed87_i_seriefinal, $sCampos);
      $rs   = $oDaoSerie->sql_record($sSql);
      if ($oDaoSerie->numrows > 0) {
        $iFim = db_utils::fieldsmemory($rs, 0)->ed11_i_sequencia;
      }

      if (is_numeric($iIni) && $iIni != 0 && is_numeric($iFim) && $iFim != 0 && is_numeric($iEns) && $iEns != 0) {
        
        $iEscola = db_getsession('DB_coddepto');
        $sCampos = 'ed215_i_atolegal, ed216_i_serie';
        $sWhere  = "cursoescola.ed71_i_curso = $ed31_i_curso ";
        $sWhere  = "cursoescola.ed71_i_escola = $iEscola ";
        $sWhere .= " and serie.ed11_i_ensino = $iEns ";
        $sWhere .= " and (serie.ed11_i_sequencia >= $iIni and serie.ed11_i_sequencia <= $iFim) ";
        $sSql    = $oDaoCursoAtoSerie->sql_query(null, $sCampos, 'ed215_i_atolegal, ed216_i_serie', $sWhere);
        $rs      = $oDaoCursoAtoSerie->sql_record($sSql);
        /*
        db_fim_transacao(true);
        die('<br><br>'.$sSql);*/
        if ($oDaoCursoAtoSerie->numrows > 0) {

          $iAto = -1;
          for ($iCont = 0; $iCont < $oDaoCursoAtoSerie->numrows; $iCont++) {

            $oDados = db_utils::fieldsmemory($rs, $iCont);
            
            // Se tenho um novo ato, tenho que criar um novo registro na baseato
            if ($iAto != $oDados->ed215_i_atolegal) {

              $oDaoBaseAto->ed278_i_escolabase = $clescolabase->ed77_i_codigo;
              $oDaoBaseAto->ed278_i_atolegal = $oDados->ed215_i_atolegal;
              $oDaoBaseAto->incluir(null);
              if ($oDaoBaseAto->erro_status == '0') {
              
                $clbase->erro_status = '0';
                $clbase->erro_msg    = $oDaoBaseAto->erro_msg;
                break;
              
              }
              $iAto = $oDados->ed215_i_atolegal;
          
            }

            $oDaoBaseAtoSerie->ed279_i_baseato = $oDaoBaseAto->ed278_i_codigo;
            $oDaoBaseAtoSerie->ed279_i_serie   = $oDados->ed216_i_serie;
            $oDaoBaseAtoSerie->incluir(null);
            if ($oDaoBaseAtoSerie->erro_status == '0') {
           
              $clbase->erro_status = '0';
              $clbase->erro_msg    = $oDaoBaseAtoSerie->erro_msg;
              break;
           
            }

          }

        }

      }

    }

  }
  db_fim_transacao($clbase->erro_status == '0');

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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Inclusão de Base Curricular</b></legend>
    <?require_once("forms/db_frmbase.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if (isset($incluir)) {
 $temerro = false;
 if ($clbase->erro_status=="0") {
  $clbase->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if ($clbase->erro_campo!="") {
   echo "<script> document.form1.".$clbase->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clbase->erro_campo.".focus();</script>";
  };
  $temerro = true;
 }
 if (@$clbaseserie->erro_status=="0") {
  $clbaseserie->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if ($clbaseserie->erro_campo!="") {
   echo "<script> document.form1.".$clbaseserie->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clbaseserie->erro_campo.".focus();</script>";
  };
  $temerro = true;
 }
 if (@$clbasediscglob->erro_status=="0") {
  $clbasediscglob->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if ($clbasediscglob->erro_campo!="") {
   echo "<script> document.form1.".$clbasediscglob->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clbasediscglob->erro_campo.".focus();</script>";
  };
  $temerro = true;
 }
 if (@$clbaseregimematdiv->erro_status=="0") {
  $clbaseregimematdiv->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if ($clbaseregimematdiv->erro_campo!="") {
   echo "<script> document.form1.".$clbaseregimematdiv->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clbaseregimematdiv->erro_campo.".focus();</script>";
  };
  $temerro = true;
 }
 if ($temerro==false) {
  $clbase->erro(true,false);
  db_redireciona("edu1_base002.php?chavepesquisa=".$clbase->ed31_i_codigo);
 } else {
  if ($ed218_c_divisao=="S") {
   ?><script>js_divisoes(<?=$ed31_i_regimemat?>,"I");</script><?
  }
 }
}
?>