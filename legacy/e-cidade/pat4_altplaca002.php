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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_bens_classe.php");
include ("classes/db_bensplaca_classe.php");
include ("classes/db_cfpatriplaca_classe.php");
include ("classes/db_histbensocorrencia_classe.php");

parse_str ( $HTTP_SERVER_VARS ["QUERY_STRING"] );
db_postmemory ( $HTTP_POST_VARS );

$clbensplaca = new cl_bensplaca ();
$clbens = new cl_bens ();
$clcfpatriplaca = new cl_cfpatriplaca ();
$clhistbemocorrencia = new cl_histbensocorrencia ();

$db_opcao = 22;
$db_botao = false;
$opc = 1;

if (isset ( $alterar )) {
  
  db_inicio_transacao ();
  $db_opcao = 2;
  $sqlerro = false;
  $placa = "";
  
  if (trim ( @$t52_ident ) == "0") {
    
    $clbens->erro_campo = "t52_ident";
    $sqlerro = true;
    $erro_msg = _M('patrimonial.patrimonio.db_frmaltplaca.placa_igual_zero');
  }
  
  /**
   * Proximo sequencial da placa
   */
  if ($sqlerro == false) {
    
    $sOrdenacao = 't07_sequencial desc limit 1';
    $iInstituicao = null;
    
    if ( BensParametroPlaca::controlaPlacaPorInstituicao() ) {
      $iInstituicao = db_getsession("DB_instit");
    }     
  
    $result = $clcfpatriplaca->sql_record ( $clcfpatriplaca->sql_query_file ($iInstituicao, '*', $sOrdenacao) );
   
    if ($clcfpatriplaca->numrows > 0) {
      
      db_fieldsmemory ( $result, 0 );
      
      if ($t07_obrigplaca == "t" && strlen ( trim ( @$t52_ident ) ) == 0) {
        
        $clbens->erro_campo = "t52_ident";
        $sqlerro = true;
        $erro_msg = _M('patrimonial.patrimonio.db_frmaltplaca.informe_placa');
      }
      
      if ($t07_confplaca == 1) {
        
        $placaseq = $t07_sequencial;
        
      } else if ($t07_confplaca == 2) {
        
        $placa = $t52_ident;
        $placaseq = $t52_ident_seq;
        $placaseq = db_formatar ( $placaseq, 'f', '0', $t07_digseqplaca, 'e', 0 );
        
      } else if ($t07_confplaca == 3) {
        
        $placa = $t52_ident;
        $placaseq = $t52_ident_seq;
        $placaseq = db_formatar ( $placaseq, 'f', '0', $t07_digseqplaca, 'e', 0 );
        
      } else if ($t07_confplaca == 4) {
        
        if ($sqlerro == false) {
          
          $placaseq = $t52_ident;
          
          if (strlen ( trim ( $placaseq ) ) > 0) {
            
            $sWhereInstituicao = null;
            
            if ( BensParametroPlaca::controlaPlacaPorInstituicao() ) {
              $sWhereInstituicao = " and  t52_instit = " . db_getsession("DB_instit");
            }
            
            $result_t52_ident = $clbens->sql_record ( $clbens->sql_query_file ( null, "t52_ident", null, "t52_ident = '$t52_ident' and t52_bem<>$t52_bem {$sWhereInstituicao}" ) );
            if ($clbens->numrows > 0) {
              
              $clbens->erro_campo = "t52_ident";
              $sqlerro = true;
              $erro_msg = _M('patrimonial.patrimonio.db_frmaltplaca.palca_ja_acadastrada');
            }
          }
        }
      }      
      
    }
  }

  /**
   * Verifica se placa ja existe
   */
  if ($sqlerro == false) {
    
    if (strlen ( trim ( $placa ) ) > 0 && strlen ( trim ( $placaseq ) ) > 0) {
      $where .= "t41_placa='$placa' and t41_placaseq=$placaseq ";
    } else {
      $where = "";
    }

    if (strlen ( trim ( $where ) ) > 0) {
      
      $sWhereInstituicao = null;
      
      if ( BensParametroPlaca::controlaPlacaPorInstituicao() ) {
        $sWhereInstituicao = " and  t52_instit = " . db_getsession("DB_instit");
      }

      $result_placa = $clbensplaca->sql_record ( $clbensplaca->sql_query ( null, "*", null, "$where and t41_bem <> $t52_bem $sWhereInstituicao") );
      
      if ($clbensplaca->numrows > 0) {
        
        $clbens->erro_campo = "t52_ident";
        $sqlerro = true;
        $erro_msg = _M('patrimonial.patrimonio.db_frmaltplaca.palca_ja_acadastrada');
      }
    }
  }
  
  if ($sqlerro == false) {
    
    if (strlen ( trim ( @$placaseq ) ) == 0) {
      $placaseq = "0";
    }
    
    $clbensplaca->t41_bem = $t52_bem;
    $clbensplaca->t41_placa = $placa;
    $clbensplaca->t41_placaseq = $placaseq;
    $clbensplaca->t41_obs = @$t41_obs;
    $clbensplaca->t41_data = date ( 'Y-m-d', db_getsession ( "DB_datausu" ) );
    $clbensplaca->t41_usuario = db_getsession ( "DB_id_usuario" );
    $clbensplaca->incluir ( null );
    if ($clbensplaca->erro_status == "0") {
      $sqlerro = true;
      $erro_msg = $clbensplaca->erro_msg;
    }
  }
  
  if ($sqlerro == false) {
    
    if ($t07_confplaca == 4) {
      $clbens->t52_ident = $placa;
    } else {
      $clbens->t52_ident = $placa . $placaseq;
    }
    $clbens->t52_bem = $t52_bem;
    $clbens->alterar ( $t52_bem );
    $erro_msg = $clbens->erro_msg;
    if ($clbens->erro_status == "0") {
      $erro_msg = $clbens->erro_msg;
      $sqlerro = true;
    }
    
    //atualiza o sequencial para próximo valor
    if (! $sqlerro) {
      $clcfpatriplaca->t07_sequencial = $placa . $placaseq + 1;
      $clcfpatriplaca->t07_instit = db_getsession ( "DB_instit" );
      $clcfpatriplaca->alterar ( db_getsession ( "DB_id_usuario" ) );
    }
    $erro_msg = $clcfpatriplaca->erro_msg;
    if ($clcfpatriplaca->erro_status == "0") {
      $erro_msg = $clcfpatriplaca->erro_msg;
      $sqlerro = true;
    }
    //Inseri historico de alteração de placa na histbensocorrencias
    if ($sqlerro == false) {
      //$t56_codbem	
      //$this->t69_sequencial 			= null; 
      $placa1 = $t52_ident;
      $placa2 = $clbens->t52_ident;
      $clhistbemocorrencia->t69_codbem = $t52_bem;
      $clhistbemocorrencia->t69_ocorrenciasbens = 3; // valor vem direto da tabela
      $clhistbemocorrencia->t69_obs = substr ( "Placa do Bem alterada de $placa1 para $placa2", 0, 50 );
      $clhistbemocorrencia->t69_dthist = date ( 'Y-m-d', db_getsession ( 'DB_datausu' ) );
      $clhistbemocorrencia->t69_hora = db_hora ();
      $clhistbemocorrencia->incluir ( null );
      if ($clhistbemocorrencia->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $clhistbemocorrencia->erro_msg;
      }
    }
  }
  
  //  $sqlerro = true;
  db_fim_transacao ( $sqlerro );

} else if (isset ( $chavepesquisa )) {
  $db_opcao = 2;
  $db_botao = true;
  if (isset ( $chavepesquisa )) {
    $t52_bem = $chavepesquisa;
  }
  $result = $clbens->sql_record ( $clbens->sql_query ( null, "*", null, "t52_bem = $t52_bem and t52_instit = " . db_getsession ( "DB_instit" ) ) );
  if ($clbens->numrows > 0) {
    db_fieldsmemory ( $result, 0 );
  }
  
  $result_placa = $clbensplaca->sql_record ( $clbensplaca->sql_query_file ( null, "*", " t41_codigo desc limit 1 ", " t41_bem=$t52_bem " ) );
  if ($clbensplaca->numrows > 0) {
    db_fieldsmemory ( $result_placa, 0 );
    $result = $clcfpatriplaca->sql_record ( $clcfpatriplaca->sql_query_file ( db_getsession ( "DB_instit" ) ) );
    if ($clcfpatriplaca->numrows > 0) {
      db_fieldsmemory ( $result, 0 );
      db_sel_instit ( null, "db21_usasisagua" );
      if ($db21_usasisagua == 't') {
        $t52_ident = $t52_ident;
      } elseif ($t07_confplaca == 1) {
        $t52_ident = $t41_placa . $t41_placaseq;
      } else if ($t07_confplaca == 2) {
        $t52_ident = $t64_class;
        $t52_ident_seq = db_formatar ( $t41_placaseq, 'f', '0', $t07_digseqplaca, 'e', 0 );
      } else if ($t07_confplaca == 3) {
        $t52_ident = $t41_placa;
        $t52_ident_seq = db_formatar ( $t41_placaseq, 'f', '0', $t07_digseqplaca, 'e', 0 );
      } else if ($t07_confplaca == 4) {
        //						$t52_ident =$t41_placa.$t41_placaseq;
      }
    }
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
<body bgcolor=#CCCCCC>
	<?php 
	  include ("forms/db_frmaltplaca.php"); 
	  db_menu ( db_getsession ( "DB_id_usuario" ), db_getsession ( "DB_modulo" ), db_getsession ( "DB_anousu" ), db_getsession ( "DB_instit" ) ); 
	?>
</body>
</html>
<?

if (isset ( $alterar )) {
  if ($clbensplaca->erro_status == "0") {
    $clbensplaca->erro ( true, false );
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clbensplaca->erro_campo != "") {
      echo "<script> document.form1." . $clbensplaca->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $clbensplaca->erro_campo . ".focus();</script>";
    }
  } else {
    //$clbensplaca->erro(true, true);
    db_msgbox ( $erro_msg );
    if ($sqlerro == false) {
      $result = $clcfpatriplaca->sql_record ( $clcfpatriplaca->sql_query_file ( db_getsession ( "DB_instit" ) ) );
      if ($clcfpatriplaca->numrows > 0) {

        db_fieldsmemory ( $result, 0 );
        $sParms            = new stdClass();
        $sParms->sPlaca    = $placa;
        $sParms->nPlacaseq = $placaseq;
        
        if ($t07_confplaca == 1) {
          db_msgbox ( _M('patrimonial.patrimonio.db_frmaltplaca.nova_placa', $sParms));
        } else if ($t07_confplaca == 2) {
          
          $sParms->nPlacaseqFormatada = db_formatar ( $placaseq, 'f', '0', $t07_digseqplaca, 'e', 0 );
          db_msgbox ( _M('patrimonial.patrimonio.db_frmaltplaca.nova_placa_formatada', $sParms));
          //db_msgbox ( "A nova placa do bem :" . $placa . db_formatar ( $placaseq, 'f', '0', $t07_digseqplaca, 'e', 0 ) );
          
        } else if ($t07_confplaca == 3) {
          
          $sParms->nPlacaseqFormatada = db_formatar ( $placaseq, 'f', '0', $t07_digseqplaca, 'e', 0 );
          db_msgbox ( _M('patrimonial.patrimonio.db_frmaltplaca.nova_placa_formatada', $sParms));
          //db_msgbox ( "A nova placa do bem :" . $placa . db_formatar ( $placaseq, 'f', '0', $t07_digseqplaca, 'e', 0 ) );
          
        } else if ($t07_confplaca == 4) {
          if (strlen ( trim ( $t52_ident ) ) > 0) {
            $msg = _M('patrimonial.patrimonio.db_frmaltplaca.nova_placa', $sParms);
          } else {
            $msg = _M('patrimonial.patrimonio.db_frmaltplaca.informe_nova_placa');
          }
          
          db_msgbox ( $msg );
        }
      }
    }
    echo "<script>location.href='pat4_altplaca002.php';</script>";
  }
}
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>