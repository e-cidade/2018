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
include("libs/db_sessoes.php");
include("libs/db_sql.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_aguacorte_classe.php");
include("classes/db_aguacortemat_classe.php");
include("classes/db_aguacortematmov_classe.php");
include("classes/db_aguacortematnumpre_classe.php");
include("classes/db_aguacortetipodebito_classe.php");
include("classes/db_aguabasecar_classe.php");

$claguacorte           = new cl_aguacorte;
$claguacortemat        = new cl_aguacortemat;
$claguacortematmov     = new cl_aguacortematmov;
$claguacortematnumpre  = new cl_aguacortematnumpre;
$claguacortetipodebito = new cl_aguacortetipodebito;
$claguabasecar         = new cl_aguabasecar;

db_postmemory($HTTP_SERVER_VARS);

//echo "Corte: $x40_codcorte<br>";
//echo "Sit1: $x43_codsituacao<br>";
//echo "Sit2: $x43_codsituacao2<br>";

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.x01_matric.focus();">

<br>
<br>

<?

db_criatermometro('termometro', 'Concluido...', 'blue', 1);
flush();

/***
 *
 * Rotina que Gera Lista de Corte com base nos criterios cadastrados
 *
 */
db_inicio_transacao();
if(empty($x40_codcorte)) {
  
  db_msgbox("E necessario selecionar um Procedimento de Corte!");
  
} else {

  // SQL pra buscar NUMPRES da Lista e seus pagamentos
  $sSqlDebitos     = $claguacorte->sql_query_listapag($x40_codcorte);
  $rsResultDebitos = $claguacorte->sql_record($sSqlDebitos);
  $iNumRowsDebitos = $claguacorte->numrows;


  $sHistoricoCompleto = '';
  for($x = 0; $x < $iNumRowsDebitos; $x++) {

    $oDebitos     = db_utils::fieldsMemory($rsResultDebitos, $x);
    $oDebitosProx = db_utils::fieldsMemory($rsResultDebitos, ($x + 1));

    db_atutermometro($x, $iNumRowsDebitos, 'termometro');

    // Se procedimento finalizado entao nao processa nada e nao gera historico
    if( $claguacortemat->sql_query_finalizado($x40_codcorte, $oDebitos->x41_matric) ) {
      continue;
    }

    $iDif  = $oDebitos->x99_parcelas - $oDebitos->x99_parcelas_pagas; // Somente Pagamento

    $sHist = "";
    if($oDebitos->x99_recibopaga > 0) {
      $sHist = "(";
      $sSep  = "";

      if($oDebitos->x99_arrecad == 0) {
        $sHist .= $sSep."PGTO";
        $sSep   = " / ";
      }

      $sSqlReciboPaga = $claguacorte->sql_query_recibopaga($oDebitos->x41_codcortemat);
      $rsReciboPaga   = $claguacorte->sql_record($sSqlReciboPaga);
      $iNumRowsRecibo = $claguacorte->numrows;

      if($iNumRowsRecibo > 0) {
        db_fieldsmemory($rsReciboPaga, 0);
        list($iAno, $iMes, $iDia) = split("-", $x99_dtoper);
        $dtOper = "{$iDia}/{$iMes}/{$iAno}";

        $sHist .= $sSep."RECIBO [Numpre: {$x99_numnov}  Vencimento: {$dtOper}] )";

      } else {
        $sHist .= $sSep."RECIBO EMITIDO";
      }
    }
    // Se parcelas pagas - parcelas em aberto maior ou igual que parcelas do filtro...
    if($iDif < $oDebitos->x45_parcelas) {

      // ... entao regularizou...
      $sRegularizado       = "REGULARIZADO ".$sHist;
      $iSituacaoreg        = $x43_codsituacao; // Situacao para Regularizacao

      $sHistoricoCompleto .= " [$sHist] ";

    } else if( $oDebitos->x99_recibopaga > 0 ) {

      // ... entao regularizou mas com recibos emitidos
      $sAguardando     = "AGUARDANDO REGULARIZAÇÃO ".$sHist;
      $iSituacaoAguard = $x43_codsituacao3; // Situacao para Recibo Emitido

      $sHistoricoCompleto .= " [$sHist] ";

    } else {

      // ... caso contrario nao regularizou ...
      $sNaoRegularizado    = "NAO REGULARIZADO";
      $iSituacaoNaoReg     = $x43_codsituacao2; // Situacao para Nao Regularização
      $sHistoricoCompleto .= " [$oDebitos->k00_tipo - $oDebitos->k00_descr] " ;


    }

    if($oDebitos->x41_codcortemat != $oDebitosProx->x41_codcortemat) {

      // Insere em AGUACORTEMATMOV
      $claguacortematmov->x42_codcortemat = $oDebitos->x41_codcortemat;
      $claguacortematmov->x42_data_dia    = date("d", db_getsession("DB_datausu"));
      $claguacortematmov->x42_data_mes    = date("m", db_getsession("DB_datausu"));
      $claguacortematmov->x42_data_ano    = date("Y", db_getsession("DB_datausu"));
      $claguacortematmov->x42_usuario     = db_getsession("DB_id_usuario");
       
      if($sNaoRegularizado != '') {
        $sHistorico = $sNaoRegularizado."(".$sHistoricoCompleto.")";
        $iSituacao  = $iSituacaoNaoReg;
      }elseif($sAguardando != '') {
        $sHistorico = $sAguardando."(".$sHistoricoCompleto.")";
        $iSituacao  = $iSituacaoAguard;
      }else {
        $sHistorico = $sRegularizado."(".$sHistoricoCompleto.")";
        $iSituacao  = $iSituacaoreg;
      }
      
      $claguacortematmov->x42_historico   = $sHistorico;
      $claguacortematmov->x42_codsituacao = $iSituacao; // Situacao para Regularizacao
       
      $claguacortematmov->incluir(null);
       
      if($claguacortematmov->erro_msg == '0') {
        break;
      }
      $sNaoRegularizado   = '';
      $iSituacaoNaoReg    = '';
      $sAguardando        = '';
      $iSituacaoAguard    = '';
      $sRegularizado      = '';
      $iSituacaoreg       = '';
      $sHistoricoCompleto = '';
    }
  }
}
?>
</body>
</html>
<?
if($claguacortematmov->erro_status == '0') {
  db_msgbox($claguacortematmov->erro_msg);
  db_fim_transacao(true);
}else {
  db_msgbox('Processamento realizado com sucesso.');
  db_fim_transacao();
}
?>