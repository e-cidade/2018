<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_setor_classe.php");
require_once("classes/db_cfiptu_classe.php");
require_once("classes/db_pontofs_classe.php");
require_once("classes/db_rhpessoalmov_classe.php");
require_once("classes/db_assenta_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0"  topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table>
<tr height=25><td>&nbsp;</td></tr>
</table>
<?

if ($_POST) {
	
	db_criatermometro('termometro','Concluido...','blue',1);
	echo "<br><br>";
  flush();
  
  $erro = false;
  
  $oPost  = db_utils::postMemory($_POST);
  $ano    = $oPost->ano_folha;
  $mes    = $oPost->mes_folha;
  
  $oFile  = $_FILES['arquivo_importacao'];
  
  if ($oFile['type'] == "text/csv" || strtolower(substr($oFile["name"],-3)) == "csv") {
  
    $instit  = db_getsession("DB_instit");
    $login   = db_getsession("DB_id_usuario");
    $datausu = db_getsession("DB_datausu");
    $anousu  = db_getsession("DB_anousu");
  
    if (isset($oPost->acao) && $oPost->acao == "processar") {
      
      $aAtestado = file($oFile['tmp_name']);
      
      
      db_inicio_transacao();
      
      /*
       * EXCLUI OS ATESTADOS JA CADASTRADOS ANTES DE PROCESSAR 
       */
      $oPontoFs  = new cl_pontofs();
      $sMatriculas = "";
      for ($i=1; $i<count($aAtestado); $i++) {
          
        $oAtestado = explode(";",$aAtestado[$i]);
        if ($oAtestado[0] > 0) {
          if ($i > 1) {
            $sMatriculas .= ",";
          }
          $sMatriculas .= $oAtestado[0];
        }
      }
      
      $oPontoFs->excluir(null, null, null, null, "r10_anousu={$ano} AND r10_mesusu={$mes} AND r10_rubric='0017'  
                                                   AND r10_regist IN ({$sMatriculas})");

      /*
       * INICIA O PROCESSAMENTO DO ARQUIVO
       */     
      for ($i = 1; $i < count($aAtestado); $i++) {
        
        if ($i < (count($aAtestado)-1)) {

          if($aAtestado != "") {
          
            $aAtestadoTmp = explode(";", $aAtestado[$i]);
            
            $oAtestado = new stdClass();
            $oAtestado->matricula = $aAtestadoTmp[0];
            $oAtestado->dias      = $aAtestadoTmp[6];
            $oAtestado->dataini   = $aAtestadoTmp[2];
            $oAtestado->datafim   = $aAtestadoTmp[4];       
            
            $oPontoFs  = new cl_pontofs();
            
            $sSql      = $oPontoFs->sql_query_file($ano, $mes, $oAtestado->matricula, '0017', "*");
            $rsPontoFs = $oPontoFs->sql_record($sSql);
            
            if ($rsPontoFs) {
              
              $oPontoFsAnt = db_utils::fieldsMemory($rsPontoFs,0);
              
              $oPontoFs->r10_valor  = '0';
              $oPontoFs->r10_quant  = ($oAtestado->dias + $oPontoFsAnt->r10_quant);
              $oPontoFs->r10_lotac  = $oPontoFsAnt->r10_lotac;                                   
              $oPontoFs->r10_datlim = ''; 
              $oPontoFs->r10_instit = $instit;
              
              $oPontoFs->excluir($ano, $mes, $oPontoFsAnt->r10_regist,"0017");        
              $oPontoFs->incluir($ano, $mes, $oAtestado->matricula, '0017');        
              if ($oPontoFs->erro_status == "0") {
                $erro = true;
echo "555";
              }
              
            } else {
              
              $sSqlRhPessoalMov  = "SELECT rh02_lota "; 
              $sSqlRhPessoalMov .= "  FROM rhpessoalmov ";
              $sSqlRhPessoalMov .= " WHERE rh02_anousu = {$ano} AND rh02_mesusu = {$mes} AND rh02_regist = {$oAtestado->matricula}";
              
              $rsRhPessoalMov = db_query($sSqlRhPessoalMov);   
              if(pg_numrows($rsRhPessoalMov) == 0){
              	echo "Matricula $oAtestado->matricula não encontrada no cadastro de servidores. Registro não processado. <br>  $sSqlRhPessoalMov  <br>";exit;
              	continue;
              }
              $r10_lotac      = db_utils::fieldsMemory($rsRhPessoalMov,0)->rh02_lota;
              
              $oPontoFs->r10_valor  = '0';
              $oPontoFs->r10_quant  = $oAtestado->dias;
              $oPontoFs->r10_lotac  = $r10_lotac;                                   
              $oPontoFs->r10_datlim = ''; 
              $oPontoFs->r10_instit = $instit;
              
              $oPontoFs->incluir($ano, $mes, $oAtestado->matricula, '0017');
              if ($oPontoFs->erro_status == "0") {
                $erro = true;
echo "444";
              }
            }
            
            $oAssenta = new cl_assenta();
            $oAssenta->h16_regist = $oAtestado->matricula;
            $oAssenta->h16_assent = '2';
            $oAssenta->h16_dtconc = date('Y-m-d', strtotime(str_replace("'",'',implode("-", array_reverse(explode("/", $oAtestado->dataini))))));
            $oAssenta->h16_histor = '';
            $oAssenta->h16_nrport = '';
            $oAssenta->h16_atofic = '';
            $oAssenta->h16_quant = $oAtestado->dias;
            $oAssenta->h16_perc = '0';
            $oAssenta->h16_dtterm = date('Y-m-d', strtotime(str_replace("'",'',implode("-", array_reverse(explode("/", $oAtestado->datafim))))));
            $oAssenta->h16_hist2 = '';      
            $oAssenta->h16_login = $login;
            $oAssenta->h16_dtlanc = date('Y-m-d', $datausu);
            $oAssenta->h16_anoato = $anousu;

            @$GLOBALS["HTTP_POST_VARS"]["h16_conver"] = 'f';

            $oAssenta->incluir(null);
            if ($oAssenta->erro_status == "0") {
              $erro = true;
echo "333 -->  ".$oAssenta->query_sql."      "; 
            }     
          }   
        }
        db_atutermometro($i, count($aAtestado), 'termometro');
      }

      db_fim_transacao();
      
    } else if (isset($oPost->acao) && $oPost->acao == "consistencia") {
      
      $aAtestado   = file($oFile['tmp_name']);
      $sMatriculas = "";
      
      for ($i=1; $i<count($aAtestado)-7; $i++) {
        
        $aAtestadoTmp = explode(";", $aAtestado[$i]);
          
        $oAtestado = new stdClass();
        $oAtestado->matricula = $aAtestadoTmp[0];
        
        if($oAtestado->matricula != "") {
          if ($i > 1) {
            $sMatriculas .= ",";
          }          
          $sMatriculas .= $oAtestado->matricula;
        }
        db_atutermometro($i, count($aAtestado)-8, 'termometro');        
      }
      
      $oPontoFs    = new cl_pontofs();
      $sSqlPontoFs = $oPontoFs->sql_query_file($ano,$mes,null,'0017',"*","","r10_rubric = '0017' 
                                                                             AND r10_anousu = {$ano} 
                                                                             AND r10_mesusu = {$mes} 
                                                                             AND r10_regist IN ({$sMatriculas})");
      //die($sSqlPontoFs);
      $rsPontoFs   = $oPontoFs->sql_record($sSqlPontoFs);
      
      if ($rsPontoFs) {       
        
        //continue;
        
      } else {        
        $erro = true;
echo "222";
      }                                                                                
    }
    
  } else {
echo "111";
    $erro = true;
  }
}

db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
  
</body>
<script>
function js_relatorio(iMes, iAno, sMatriculas) {
  var url      = "pes2_importaatestados003.php";
  var qstring  = "?anousu="+iAno;
      qstring += "&mesusu="+iMes;
      qstring += "&regist="+sMatriculas;  
  window.open(url+qstring, "Relatório de Importação", "height = 600, width = 800");
}
</script>
<?
if($_POST){
  
  if(isset($oPost->acao) && $oPost->acao == "processar") {

    if ($erro) {
      
      echo "<script> alert('Erro ao efetuar importação.');</script>";   
    }else{
  
      echo "<script>alert('Importação efetuada com sucesso.');</script>"; 
    }
  } else if(isset($oPost->acao) && $oPost->acao == "consistencia") {
    
    if ($erro) {
      
      echo "<script>alert('Nenhum registro do arquivo foi processado para este periodo.');</script>";   
    }else{
      
      echo "<script>js_relatorio({$mes}, {$ano}, '{$sMatriculas}')</script>";       
    }
  }
}
db_redireciona("pes2_importaatestados001.php");
?>

</html>