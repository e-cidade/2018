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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("classes/db_extratolinha_classe.php");
include("classes/db_extrato_classe.php");
include("classes/db_extratosaldo_classe.php");
include("classes/db_concilia_classe.php");
include("classes/db_conciliapendextrato_classe.php");
include("classes/db_conciliaextrato_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clextratolinha    = new cl_extratolinha;
$clextrato         = new cl_extrato;
$clextratosaldo    = new cl_extratosaldo;
$clconcilia        = new cl_concilia;
$clconciliaextrato = new cl_conciliaextrato;
$clconciliapendextrato = new cl_conciliapendextrato;


$db_opcao = 22;
$db_botao = false;
if (isset($alterar) || isset($excluir) || isset($incluir)) {
  $sqlerro = false;

	$clextratolinha->k86_extrato       = $k86_extrato;
	$clextratolinha->k86_bancohistmov  = $k86_bancohistmov;
	$clextratolinha->k86_contabancaria = $k86_contabancaria;
	$clextratolinha->k86_data          = $k86_data_ano."-".$k86_data_mes."-".$k86_data_dia;
	$clextratolinha->k86_valor         = $k86_valor;
	$clextratolinha->k86_tipo          = $k86_tipo;
	$clextratolinha->k86_historico     = $k86_historico;
	$clextratolinha->k86_documento     = $k86_documento;
	
	$clextratolinha->k86_observacao    = $k86_observacao;
	
	$clextratolinha->k86_lote          = 1;
	$clextratolinha->k86_loteseq       = 1;
    $dData = $k86_data_ano."-".$k86_data_mes."-".$k86_data_dia;
}

if (isset($incluir)) {
	
  db_inicio_transacao();
  
  if ($sqlerro == false) {
    $clextratolinha->incluir(null);
    $erro_msg = $clextratolinha->erro_msg;
    if ($clextratolinha->erro_status==0) {
      $sqlerro = true;
    }
  }
  
  /*
   * Recalculamos o saldo do extrato 
   */
  if ($sqlerro == false && $recalcula == "t") { 
	 /*
    * Verificamos o extrato saldo da data para efetuarmos a manutenção nos valores.
    * 
    */
    $sWhere = " k97_dtsaldofinal = '{$dData}' and k97_contabancaria = $k86_contabancaria";
    
    $rsExtratoSaldo = $clextratosaldo->sql_record($clextratosaldo->sql_query(null, "*", null, $sWhere));
    if ($clextratosaldo->numrows > 0) {
      db_fieldsmemory($rsExtratoSaldo,0);
      
      $clextratosaldo->recriarSaldoGeral($k86_contabancaria,$dData);
      
    } else {
    	
    	$sWhere = " k97_dtsaldofinal < '{$dData}' and k97_contabancaria = $k86_contabancaria ";
    	$rsSaldoAnterior = $clextratosaldo->sql_record($clextratosaldo->sql_query_file(null, 
    	                                                                               "coalesce(k97_saldofinal,0) as k97_saldofinal", 
    	                                                                               "k97_dtsaldofinal desc limit 1", 
    	                                                                               $sWhere));
    	$oSaldoAnterior = @db_utils::fieldsMemory($rsSaldoAnterior,0,"k97_saldofinal");
    	
      $clextratosaldo->k97_contabancaria  = $clextratolinha->k86_contabancaria;
      $clextratosaldo->k97_dtsaldofinal   = $clextratolinha->k86_data;
      $clextratosaldo->k97_extrato        = $clextratolinha->k86_extrato;
    
      if ($clextratolinha->k86_tipo == "C") {
        $clextratosaldo->k97_valorcredito   = $clextratolinha->k86_valor;
        $clextratosaldo->k97_valordebito    = 0;
      } else {
        $clextratosaldo->k97_valorcredito   = 0;
        $clextratosaldo->k97_valordebito    = $clextratolinha->k86_valor;        
      }
    
      $clextratosaldo->k97_qtdregistros   = 1;
      $clextratosaldo->k97_posicao        = 'F';
      $clextratosaldo->k97_situacao       = 'D';
      $clextratosaldo->k97_saldobloqueado = 0;
      $clextratosaldo->k97_saldofinal     = round($clextratolinha->k86_valor + @$oSaldoAnterior->k97_saldofinal,2);
      $clextratosaldo->k97_limite         = 0;
      $clextratosaldo->incluir(null);
      if ($clextratosaldo->erro_status == 0) {
        $sqlerro = true;
        $erromsg = "extrato saldo - " . $clextratosaldo->erro_msg;
      }
      
    }
    
  }  
    
  /*
   * Geramos as pendencias
   */
  if ($sqlerro == false) {
    /*
     * Verifica se tem conciliacoes fechada com data superior para inclusao das pendencias
    *
    */
    $rsPendencias = $clconcilia->sql_record($clconcilia->sql_query_file(null,
                                                                        " k68_sequencial ",
                                                                        null,
                                                                        "    k68_data >= '{$dData}'
                                                                        and k68_contabancaria = $k86_contabancaria")
                                                                        );
    $intNumrowsConcilia = $clconcilia->numrows;
     
    for ($i = 0; $i < $intNumrowsConcilia; $i ++) {
    	db_fieldsmemory($rsPendencias,$i);
    
    	$clconciliapendextrato->k88_conciliaorigem = "3";
    	$clconciliapendextrato->k88_concilia       = $k68_sequencial;
    	$clconciliapendextrato->k88_extratolinha   = $clextratolinha->k86_sequencial;
    	$clconciliapendextrato->incluir(null);
    	if ($clconciliapendextrato->erro_status == 0) {
    		$erro_msg = "conciliapendextrato - ".$clconciliapendextrato->erro_msg;
    		$sqlerro  = true;
    		break;
    	}
    	
    }
   
  }
       
  db_fim_transacao($sqlerro);

} else if (isset($alterar)) {
	
   /*  
	  * Verificamos se o movimento já está conciliado
	  */
	 $clconciliaextrato->sql_record($clconciliaextrato->sql_query(null,"*",null,"k87_extratolinha = $k86_sequencial"));
	 if ($clconciliaextrato->numrows > 0) {
	 	$erro_msg = "Aviso:\\n\\nLinha do Extrato Manual já conciliada.\\nAlteração não permitida!";
	 	$sqlerro = true;
	 }
	 
	 if ($sqlerro == false) {
	  	
    db_inicio_transacao();
    
    if ($sqlerro==false) {
      $clextratolinha->alterar($k86_sequencial);
      $erro_msg = $clextratolinha->erro_msg;
      if ($clextratolinha->erro_status==0) {
        $sqlerro=true;
      }
    }
    
    if ($sqlerro==false) {
      $clconciliapendextrato->excluir(null, "k88_extratolinha = {$k86_sequencial}");
      if ($clconciliapendextrato->erro_status == 0) {
      		$erro_msg = "conciliapendextrato - ".$clconciliapendextrato->erro_msg;
      		$sqlerro  = true;
      
      }
    }
    
    
    if ($sqlerro == false && $recalcula == "t") {
    	
      /*
       * Verificamos o extrato saldo da data para efetuarmos a manutenção nos valores.
       * 
       */
       $sWhere = " k97_dtsaldofinal = '{$dData}' and k97_contabancaria = {$k86_contabancaria}";
       $rsExtratoSaldo = $clextratosaldo->sql_record($clextratosaldo->sql_query(null, "*", null, $sWhere));
       if ($clextratosaldo->numrows > 0) {
         db_fieldsmemory($rsExtratoSaldo,0);
         
         $clextratosaldo->recriarSaldoGeral($k86_contabancaria,$dData);
         
       } else {
         
       	$sWhere = " k97_dtsaldofinal < '{$dData}' and k97_contabancaria = $k86_contabancaria ";
         $rsSaldoAnterior = $clextratosaldo->sql_record($clextratosaldo->sql_query_file(null, 
                                                                                        "coalesce(k97_saldofinal,0) as k97_saldofinal", 
                                                                                        "k97_dtsaldofinal desc limit 1", 
                                                                                        $sWhere));
         $oSaldoAnterior = @db_utils::fieldsMemory($rsSaldoAnterior,0,"k97_saldofinal");
       	
         $clextratosaldo->k97_contabancaria  = $clextratolinha->k86_contabancaria;
         $clextratosaldo->k97_dtsaldofinal   = $clextratolinha->k86_data;
         $clextratosaldo->k97_extrato        = $clextratolinha->k86_extrato;
       
         if ($clextratolinha->k86_tipo == "C") {
           $clextratosaldo->k97_valorcredito   = $clextratolinha->k86_valor;
           $clextratosaldo->k97_valordebito    = 0;
         } else {
           $clextratosaldo->k97_valorcredito   = 0;
           $clextratosaldo->k97_valordebito    = $clextratolinha->k86_valor;        
         }
       
         $clextratosaldo->k97_qtdregistros   = 1;
         $clextratosaldo->k97_posicao        = 'F';
         $clextratosaldo->k97_situacao       = 'D';
         $clextratosaldo->k97_saldobloqueado = 0;
         $clextratosaldo->k97_saldofinal     = round($clextratolinha->k86_valor + @$oSaldoAnterior->k97_saldofinal,2);
         $clextratosaldo->k97_limite         = 0;
         $clextratosaldo->incluir(null);
         if ($clextratosaldo->erro_status == 0) {
           $sqlerro = true;
           $erromsg = "extrato saldo - " . $clextratosaldo->erro_msg;
         }
         
       }
    }
	  
    /*
     * Geramos as pendencias
    */
    if ($sqlerro == false) {
    	/*
    	 * Verifica se tem conciliacoes fechada com data superior para inclusao das pendencias
    	*
    	*/
    	$rsPendencias = $clconcilia->sql_record($clconcilia->sql_query_file(null,
    			                                                                " k68_sequencial ",
    			                                                                null,
    			                                                                "    k68_data >= '{$dData}'
    			                                                                and k68_contabancaria = $k86_contabancaria")
    			                                                                );
    	$intNumrowsConcilia = $clconcilia->numrows;
    	 
    	for ($i = 0; $i < $intNumrowsConcilia; $i ++) {
    		db_fieldsmemory($rsPendencias,$i);
    
    		$clconciliapendextrato->k88_conciliaorigem = "3";
    		$clconciliapendextrato->k88_concilia       = $k68_sequencial;
    		$clconciliapendextrato->k88_extratolinha   = $clextratolinha->k86_sequencial;
    		$clconciliapendextrato->incluir(null);
    		if ($clconciliapendextrato->erro_status == 0) {
    			$erro_msg = "conciliapendextrato - ".$clconciliapendextrato->erro_msg;
    			$sqlerro  = true;
    			break;
    		}
    		 
    	}
    	 
    }
 
	 }
	  
  db_fim_transacao($sqlerro);
    
} else if (isset($excluir)) {
	
  if ($sqlerro==false) {
  	
    db_inicio_transacao();
    /*
     * Verificamos se o movimento possui pendencia para esta data ou posterior
     * Caso o movimento não seja de pendencia, atualiza a extratosaldo  
     */
    $rsPendencias = $clconciliapendextrato->sql_record($clconciliapendextrato->sql_query(null,
                                                                                         "*",
                                                                                         null,
                                                                                         "    conciliapendextrato.k88_extratolinha = $k86_sequencial 
                                                                                          and concilia.k68_data >= '".$k86_data_ano."-".$k86_data_mes."-".$k86_data_dia."'"));
    $iNumRows = $clconciliapendextrato->numrows;                                                                                           
    if ($iNumRows > 0) {
       for ($x = 0; $x < $iNumRows; $x++) {
       	 db_fieldsmemory($rsPendencias,$x);
       	 
       	 $clconciliapendextrato->excluir($k88_sequencial);
         if ($clconciliapendextrato->erro_status == 0) {
           $erro_msg = "Erro ao excluir pendencia posterior (conciliapendextrato) Sequencial: - $k88_sequencial ".$clconciliapendextrato->erro_msg;
           $sqlerro  = true;
           break;
         }
       	
       }
    }
    
    $clextratolinha->excluir($k86_sequencial);
    $erro_msg = $clextratolinha->erro_msg;
    if ($clextratolinha->erro_status==0) {
      $sqlerro=true;
    }
    
    
   /*
    * Verificamos o extrato saldo da data para efetuarmos a manutenção nos valores caso o movimento não esteja pendente.
    * 
    */
    if ($iNumRows > 0 && $k88_conciliaorigem != 3 || $iNumRows == 0) {
     
      $sWhere = " k97_dtsaldofinal = '{$dData}' and k97_contabancaria = {$k86_contabancaria}";
      $rsExtratoSaldo = $clextratosaldo->sql_record($clextratosaldo->sql_query(null, "*", null, $sWhere));
      if ($clextratosaldo->numrows > 0) {
        db_fieldsmemory($rsExtratoSaldo,0);
        $clextratosaldo->recriarSaldoGeral($k86_contabancaria,$dData);
      }
     
    } 
  
    db_fim_transacao($sqlerro);
  }
  
} else if (isset($opcao)) {
	
   $result = $clextratolinha->sql_record($clextratolinha->sql_query($k86_sequencial));
   if ($result!=false && $clextratolinha->numrows>0) {
     db_fieldsmemory($result,0);
   }
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
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
	<?
	include("forms/db_frmextratolinha.php");
	?>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clextratolinha->erro_campo!=""){
        echo "<script> document.form1.".$clextratolinha->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clextratolinha->erro_campo.".focus();</script>";
    }
}
?>