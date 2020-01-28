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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_jsplibwebseller.php");
require_once ("libs/db_utils.php");
require_once ("classes/db_undmedhorario_ext_classe.php");
require_once ("classes/db_medicos_classe.php");
require_once ("classes/db_unidademedicos_classe.php");
require_once ("classes/db_sau_tipoficha_classe.php");
require_once ("classes/db_diasemana_classe.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$dHoje                    = date("Y-m-d", db_getsession("DB_datausu"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clmedicos                = new cl_medicos;
$clunidademedicos         = new cl_unidademedicos;
$clsau_tipoficha          = new cl_sau_tipoficha;
$cldiasemana              = new cl_diasemana;
$clundmedhorario          = new cl_undmedhorario_ext;
$oDaoAgendamentos         = db_utils::getdao('agendamentos');

$db_botao      = true;
$db_opcao      = 1;
$db_opcao2     = 1;
$lAgendamentos = true;


$datausu  = date("Y",db_getsession( "DB_datausu" ) )."/".
            date("m",db_getsession( "DB_datausu" ) )."/".
            date("d",db_getsession( "DB_datausu" ) );
            
if ((isset($alterar) || isset($excluir)) && isset($sd30_d_valinicial) && $sd30_d_valinicial != '') {
  
  $dDataUsu  = date("Y",db_getsession( "DB_datausu" ) )."-".
               date("m",db_getsession( "DB_datausu" ) )."-".
               date("d",db_getsession( "DB_datausu" ) );
  $sHora     = substr(db_hora(), 0, 2);
  $sMin      = substr(db_hora(), 3, 2);
  $sWhere    = " ( sd23_d_consulta > '$dDataUsu'::DATE "; 
  $sWhere   .= "   OR (sd23_d_consulta = '$dDataUsu'::DATE ";
  $sWhere   .= "       and substring(sd23_c_hora, 1, 2)::INTEGER > ($sHora)::INTEGER) ";
  $sWhere   .= "   OR (sd23_d_consulta = '$dDataUsu'::DATE ";
  $sWhere   .= "       and substring(sd23_c_hora, 1, 2)::INTEGER = ($sHora)::INTEGER ";
  $sWhere   .= "       and substring(sd23_c_hora, 4, 2)::INTEGER >= ($sMin)::INTEGER) ";
  $sWhere   .= " ) ";
  $sWhere   .= " AND sd04_i_medico =  $sd04_i_medico ";
  $sWhere   .= " AND sd04_i_unidade = $sd04_i_unidade ";
  $sWhere   .= " AND sd23_i_undmedhor = $sd30_i_codigo ";
  $sWhere   .= " AND s114_i_agendaconsulta is null ";
  $sSql      = $oDaoAgendamentos->sql_query_situacao ("","count(sd23_d_consulta) as nro_agendamentos",null, $sWhere);
  $rsAgend   = $oDaoAgendamentos->sql_record($sSql);
  $oAgend    = db_utils::fieldsmemory($rsAgend, 0);
  if ((int)$oAgend->nro_agendamentos > 0) {
      
    $sMsg  = "O Médico ".$z01_nome." possui $oAgend->nro_agendamentos consulta(s) agendada(s).";
    $sMsg .= "\\nData inicial não pode ser alterada!";
    db_msgbox("$sMsg");
    $lAgendamentos = false;
  } 
}
            
if (isset($incluir) || isset($alterar) && $lAgendamentos) {
  
	$iDia  = isset($chk_seg)?$chk_seg.", ":"";
	$iDia .= isset($chk_ter)?$chk_ter.", ":"";
	$iDia .= isset($chk_qua)?$chk_qua.", ":"";
	$iDia .= isset($chk_qui)?$chk_qui.", ":"";
	$iDia .= isset($chk_sex)?$chk_sex.", ":"";
	$iDia .= isset($chk_sab)?$chk_sab.", ":"";
	$iDia .= isset($chk_dom)?$chk_dom.", ":"";
	$iDia  = substr( $iDia, 0, strlen($iDia)-2 );
	
	$datavalidade = "";
  if ( $sd30_d_valinicial_ano != "" ) {
    
    $dtInicial    = $sd30_d_valinicial_ano."-".$sd30_d_valinicial_mes."-".$sd30_d_valinicial_dia;
		$datavalidade = " and sd30_d_valinicial is not null and sd30_d_valinicial >= '$dtInicial' ";
	} else if ( $sd30_d_valfinal_ano != "" || $sd30_d_valinicial_ano != "" ) {
	  
	  $dtFinal      = date("Y", db_getsession("DB_datausu"))."-".date("m", db_getsession("DB_datausu"))."-".date("d", db_getsession("DB_datausu"));
	  $datavalidade = " and sd30_d_valinicial is not null and sd30_d_valinicial >=  '$dtFinal'";
	}

	if ( $sd30_d_valfinal_ano != "" ) {
	  
	  $dtFinal       = $sd30_d_valfinal_ano."-".$sd30_d_valfinal_mes."-".$sd30_d_valfinal_dia;
		$datavalidade .= " and (  
							     ( sd30_d_valfinal is not null and sd30_d_valfinal <= '$dtFinal' ) )";
	} else if ( $sd30_d_valfinal_ano != "" || $sd30_d_valinicial_ano != "" ) {
	  
	  $dtFinal       = date("Y", db_getsession("DB_datausu"))."-".date("m", db_getsession("DB_datausu"))."-".date("d", db_getsession("DB_datausu"));
		$datavalidade .= " and sd30_d_valfinal is not null and sd30_d_valfinal >  '$dtFinal'";
	}
	
	$sCondicaoAlterar = isset($alterar)||@$opcao=="alterar"?" and sd30_i_codigo <> $sd30_i_codigo":"";
	$sWhere           = "     sd04_i_medico = $sd04_i_medico ".$sCondicaoAlterar." and sd30_i_diasemana in ( $iDia )";
	$sWhere          .= " and ( '$sd30_c_horaini' between sd30_c_horaini and sd30_c_horafim";
	$sWhere          .= "       or '$sd30_c_horafim' between sd30_c_horaini and sd30_c_horafim";  
	$sWhere          .= "       or sd30_c_horaini between  '$sd30_c_horaini' and '$sd30_c_horafim'"; 
	$sWhere          .= "       or sd30_c_horafim between  '$sd30_c_horaini' and '$sd30_c_horafim' )";
	$sWhere          .= " $datavalidade ";
	
	$str_query2  = "select * ";
	$str_query2 .= "  from agendamentos ";
	$str_query2 .= " where sd23_i_undmedhor = $sd30_i_codigo ";
	$str_query2 .= "   and sd23_d_consulta >= '$sd30_d_valfinal_ano-$sd30_d_valfinal_mes-$sd30_d_valfinal_dia' "; 
	$str_query2 .= "   and not exists ( select * from agendaconsultaanula where s114_i_agendaconsulta = sd23_i_codigo ) ";
	
	$sMsg         = "<br><br><center>Erro: <font color=\"red\">Ao selecionar a grade de horarios do profissional</font>";
	$sMsg        .= " <br>Contate o administador do sistema!</center>";
	$res_agenda   = @pg_query( $str_query2 );
	$db_opcao     = isset($alterar)? 2 : $db_opcao;	
	$db_opcao2    = isset($alterar)?22 : $db_opcao;

	if ( $sd30_d_valinicial_ano != "" && $sd30_d_valfinal_ano != "") {
	
	  $aVet   = explode("/",$sd30_d_valinicial);
	  $dData1 = $aVet[2].$aVet[1].$aVet[0];
    $aVet   = explode("/",$sd30_d_valfinal);	
    $dData2 = $aVet[2].$aVet[1].$aVet[0];
	}
	
	if ( $sd30_d_valinicial_ano != "" && @pg_num_rows($res_agenda) > 0 ) {
	  
		$clundmedhorario->erro_status = "0";
		$clundmedhorario->erro_msg    = "Profissional ja possui horário agendado nesse intervalo.";
	} else if (( $sd30_d_valinicial_ano != "" && $sd30_d_valfinal_ano != "") && ($dData1 > $dData2)) {
	
	    $clundmedhorario->erro_status = "0";
	    $clundmedhorario->erro_msg    = "Data Inicial maior que Data Final.";
	} else {
	               
		if (isset($incluir)) {
		  
			db_inicio_transacao();
      if ($rad_periodo == 1) {
        
        $aDia = explode(",", $iDia);
        for( $iCont = 0; $iCont < sizeof($aDia); $iCont++ ) {
          
			     $clundmedhorario->sd30_i_diasemana = $aDia[$iCont];
			     $clundmedhorario->incluir(null);
			  }
      } else {
         
         //montando array dia semana
         $aDia           = explode(",", $iDia);
         $dias_da_semana = array();
         for ( $iCont = 0; $iCont < sizeof($aDia); $iCont++ ) {
           $dias_da_semana[$aDia[$iCont]] = 0;
         }
         
         //Verificando se é Quinzenal1(1) ou Mensal(3)
         if ($rad_periodo == 2) {
           $escape = 1;
         } else {
           
           //                 0          1          2          3          4           5         6           7
           $escape = array($semanames, $semanames, $semanames, $semanames, $semanames, $semanames, $semanames, $semanames);
         }
         
         $vet               = explode("/", $sd30_d_valinicial);
         $sd30_d_valinicial = $vet[2]."-".$vet[1]."-".$vet[0];
         $vet               = explode("/",$sd30_d_valfinal);
         $sd30_d_valfinal   = $vet[2]."-".$vet[1]."-".$vet[0];
         $d2                = strtotime($sd30_d_valfinal);
         
         $lErro = false;
         //For percorre o periodo das datas de validades
         for ($d1 = strtotime($sd30_d_valinicial); $d1 <= $d2; $d1 = $d1+86400) {
           
           foreach ($dias_da_semana as $chave => $valor) {
             
             if ($rad_periodo == 2) {
               
               //escape Quinzenal
               $iDiaChave  = (date("w",$d1)+1);
               $iDiaSemana = $dias_da_semana[$chave];
               if (($iDiaChave == $chave) && ($iDiaSemana == 0)) {
                 
                 $dtQuinzenal  = date("Y-m-d",$d1);
                 $sWhere      .= " AND sd30_d_valinicial = '$dtQuinzenal'";
                 $str_query    = $clundmedhorario->sql_query_ext("", "*", null, $sWhere);
                 $res_horario  = @pg_query( $str_query ) or die($sMsg);
                 
                 if( pg_num_rows( $res_horario ) > 0) {
                   
                   $clundmedhorario->erro_status = "0";
                   $clundmedhorario->erro_msg    = "Profissional ja possui horário nesse intervalo.";
                   $lErro = true;
                 } else {
                   
                   if (!$lErro) {
                     
                     $clundmedhorario->sd30_i_diasemana  = (int)trim($chave);
                     $clundmedhorario->sd30_d_valinicial = date("Y-m-d",$d1);
                     $clundmedhorario->sd30_d_valfinal   = date("Y-m-d",$d1);
                     $clundmedhorario->incluir(null);
                     $dias_da_semana[$chave] = $escape;
                   }
                 }
               } else {
                 
                 if ((date("w",$d1)+1) == $chave) {
                   $dias_da_semana[$chave] = $dias_da_semana[$chave]-1;
                 }
               }
             }
             
             //escape mensal
             if ($rad_periodo == 3) {
               
               $iDiaChave  = (date("w",$d1)+1);
               $iDiaSemana = $dias_da_semana[$chave];
               
               if (($iDiaChave == $chave) && (date("m",$d1) != $iDiaSemana)) {
                 
                 if ($escape[trim($chave)] == 0) { // @ para evitar o erro desconhecido
                   
                   $dtMensal     = date("Y-m-d",$d1);
                   $sWhere      .= " AND sd30_d_valinicial = '$dtMensal'";
                   $str_query    = $clundmedhorario->sql_query_ext("", "*", null, $sWhere);
                   $res_horario  = @pg_query( $str_query ) or die($sMsg);
                    
                   if( pg_num_rows( $res_horario ) > 0 && $sd30_c_tipograde == 'P' ) {
                      
                     $clundmedhorario->erro_status = "0";
                     $clundmedhorario->erro_msg    = "Profissional ja possui horário nesse intervalo.";
                     $lErro = true;
                   } else {
                     
                     if (!$lErro) {
                       
                       $clundmedhorario->sd30_i_diasemana  = (int)trim($chave);
                       $clundmedhorario->sd30_d_valinicial = date("Y-m-d",$d1);
                       $clundmedhorario->sd30_d_valfinal   = date("Y-m-d",$d1);
                       $clundmedhorario->incluir(null);
                       $dias_da_semana[$chave] = date("m",$d1);
                       $escape[trim($chave)]   = $semanames;
                     }
                   }
                 } else {
                   
                   if (((date("w",$d1)+1) == $chave) && (date("m",$d1) != $dias_da_semana[$chave])) {
                     $escape[trim($chave)] = $escape[trim($chave)]-1;
                   }
                 }
               }
             }
           }                     
         }
      }
      db_fim_transacao();
		}
		
		if(isset($alterar)){
		  
			//Verifica se foi alterado Intervalo/Fichas com agendamentos posteriores
			$bAlterar = true;
			$sSql     = "select sd23_d_consulta as data,count(*) as icountagenda "; 
			$sSql    .= "	from agendamentos "; 
			$sSql    .= " where sd23_i_undmedhor = $sd30_i_codigo  ";

      if ( $sd30_d_valinicial != "") {
      	
      	$aVet   = explode("/",$sd30_d_valinicial);
        $dData1 = $aVet[2]."-".$aVet[1]."-".$aVet[0];
				$sSql  .= "   and sd23_d_consulta between '".$dHoje."' and '".$dData1."'";
      } else {
      	$dData1 = '';
      }
      
      if ($sd30_d_valfinal != "") {
      	
        $aVet   = explode("/",$sd30_d_valfinal);  
        $dData2 = $aVet[2]."-".$aVet[1]."-".$aVet[0];
        $sSql  .= "   and sd23_d_consulta >= '".$dData2."'";
      } else { 
			  
      	$dData2  = '';
      	$sSql   .= "   and sd23_d_consulta >= '".$dHoje."'";
      }
      
			$sSql .= "   and (extract( dow from sd23_d_consulta )+1) in ( $iDia )";
			$sSql .= "   and not exists ( select * from agendaconsultaanula where s114_i_agendaconsulta = sd23_i_codigo ) "; 
      $sSql .= " group by sd23_d_consulta ";
      $sSql .= " order by icountagenda desc ";
      
			$rsSql = pg_query( $sSql ) or die( "ERRO: $sSql");

			if ( pg_num_rows( $rsSql ) > 0 ) {
			  
				$oCountagenda    = db_utils::fieldsMemory($rsSql,0);
				$sSql            = $clundmedhorario->sql_query_ext($sd30_i_codigo);
				$rsUndmedhorario = $clundmedhorario->sql_record($sSql);
				$oUndmedhorario  = db_utils::fieldsMemory($rsUndmedhorario,0);

				if ( $oUndmedhorario->sd30_c_tipograde <> $sd30_c_tipograde ){
					
					$bAlterar = false;
					$sMsg = "Não pode alterar o Intervalo, pois ja consta agendamentos.";
				} else if( $sd30_i_fichas < $oCountagenda->icountagenda ){
					
					$bAlterar = false;
					$sMsg = "Não pode alterar Quantidade de Fichas, pois no dia ".db_formatar($oCountagenda->data,"d")." ja consta $oCountagenda->icountagenda agendamentos.";
				} else if( $oUndmedhorario->sd30_i_diasemana <> $iDia ){
					
					$bAlterar = false;
					$sMsg = "Não pode alterar Dia da Semana, pois ja consta agendamentos.";
				} else if($sd30_d_valinicial != '' || $sd30_d_valfinal != ''){
					
          if ($oUndmedhorario->sd30_d_valinicial != $dData1 || $oUndmedhorario->sd30_d_valfinal != $dData2){
          
					  $bAlterar = false;
            $sMsg = "Não pode alterar Data de validade, pois ja consta agendamentos.";
          }
				}
			}
			
			if ( $bAlterar == true ) {
			   
				db_inicio_transacao();
				$clundmedhorario->alterar($sd30_i_codigo);
				db_fim_transacao();
			} else {
			  
				$clundmedhorario->erro_status = "0";
				$clundmedhorario->erro_msg    = $sMsg;
			}
		}
	}
}

if (isset($excluir) && $lAgendamentos) {
  
	$str_query = "select * 
	        				from agendamentos 
	         			 where sd23_i_undmedhor = $sd30_i_codigo  "; 
	$result = pg_exec( $str_query );
	
	if( pg_numrows( $result ) > 0 ) {
	  
		$clundmedhorario->erro_status = "0";
		$clundmedhorario->erro_msg    = "Profissional tem agendamentos efetuadas posteriormente. Não permitindo a exclusão do horário.";
	} else {
	  
		db_inicio_transacao();
		$clundmedhorario->excluir($sd30_i_codigo);
		db_fim_transacao();
	}
}


//Botões Alterar/Excluir
if (isset($opcao)) {
  
	$db_botao1 = true;
	$db_opcao  = $opcao == "alterar"? 2: 3;
	$db_opcao2 = $opcao == "alterar"?22: 3;
	
	$result = $clundmedhorario->sql_record($clundmedhorario->sql_query_ext($sd30_i_codigo));
	if( $clundmedhorario->numrows > 0 ){
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        require_once ("forms/db_frmundmedhorario006.php");
        ?>
    </center>
        </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd30_i_undmed",true,1,"sd30_i_undmed",db_true);
</script>
<?
if(isset($incluir)||isset($alterar)){
	if($clundmedhorario->erro_status=="0"){
		$clundmedhorario->erro(true,false);
		$db_botao=true;
		echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		if($clundmedhorario->erro_campo!=""){
			echo "<script> document.form1.".$clundmedhorario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clundmedhorario->erro_campo.".focus();</script>";
		}
	}
	$clundmedhorario->erro(true,false);
	db_redireciona("sau1_undmedhorario006.php?sd04_i_medico=$sd04_i_medico&z01_nome=$z01_nome&sd30_i_undmed=$sd30_i_undmed");
}
if(isset($excluir)){
	$clundmedhorario->erro(true,false);
	db_redireciona("sau1_undmedhorario006.php?sd04_i_medico=$sd04_i_medico&z01_nome=$z01_nome");
}

if( isset($sd30_i_undmed) ){
	echo "<script>js_pesquisasd30_i_undmed(false);</script>";
}
?>