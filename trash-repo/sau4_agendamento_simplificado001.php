<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("libs/db_stdlibwebseller.php");
include("libs/db_jsplibwebseller.php");
include("libs/db_app.utils.php");

include("classes/db_unidades_classe.php" );
include("classes/db_undmedhorario_ext_classe.php");
include("classes/db_agendamentos_ext_classe.php");
include("classes/db_sau_config_ext_classe.php");
include("classes/db_agendaproced_classe.php");

include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clunidades      = new cl_unidades;
$clagendamentos  = new cl_agendamentos_ext;
$clundmedhorario = new cl_undmedhorario_ext;
$clsau_config    = new cl_sau_config_ext;
$clagendaproced  = new cl_agendaproced;

$res_sau_config  = pg_query( $clsau_config->sql_query_ext() );
$booProced       = pg_num_rows( $res_sau_config ) > 0 && pg_result($res_sau_config, 0, "s103_c_agendaproc") == "S";

$sd02_c_centralagenda = "N";
$iUpssolicitante      = db_getsession("DB_coddepto"); 
$lAgendaLiberada      = true;
$result_unidades      = $clunidades->sql_record( $clunidades->sql_query($iUpssolicitante,"sd02_c_centralagenda,descrdepto",null,"") );
if( $clunidades->numrows != 0 ){
  @db_fieldsmemory($result_unidades,0);
}else{
  db_msgbox("Departamento atual não é uma UPS.");
}

$db_opcao_cotas = 1;
$oResult = getCotasAgendamento($iUpssolicitante, null, null, null, null);
if ($oResult->lStatus != 1) {

  $sd02_i_codigo = $iUpssolicitante;
  $db_opcao_cotas = 3;
  
} else {
	 
  $sd02_i_codigo  = "";
  $descrdepto     = ""; 
  	
}

$oAgendaParametros = loadConfig('sau_parametrosagendamento');
if ($oAgendaParametros != null) {
  $s165_formatocomprovanteagend = $oAgendaParametros->s165_formatocomprovanteagend; 
}

if( isset( $chave_diasemana ) && $chave_diasemana != "" ){
	$result = $clundmedhorario->sql_record( $clundmedhorario->sql_query_ext("","*","", "sd30_i_codigo = $sd30_i_codigo and sd30_i_diasemana = $chave_diasemana ") );
	if( $clundmedhorario->numrows == 0 ){
		db_msgbox("Profissional não possui agendamento.");
	}else{
		db_fieldsmemory( $result, 0 );
		$agendados = true;
	}
}

if(isset($confirma)){
    $ano           = substr( $sd23_d_consulta, 6, 4 );
    $mes           = substr( $sd23_d_consulta, 3, 2 );
    $dia           = substr( $sd23_d_consulta, 0, 2 );
    $vet=explode("/",$sd23_d_consulta);
	$sd23_d_consulta2=$vet[2]."-".$vet[1]."-".$vet[0];

    $sAusenciaPorCodGradeHorario = " and sd30_i_codigo not in (select sd06_i_undmedhorario from ausencias  
                                     inner join undmedhorario on sd06_i_undmedhorario = sd30_i_codigo 
                                       where sd06_i_especmed = $sd27_i_codigo
                                         and sd30_i_diasemana = $dia_semana
                                         and '$ano/$mes/$dia' between sd06_d_inicio and sd06_d_fim) ";

    $sCodigoGradeHorario = ' and sd30_i_codigo = '.$sd06_i_undmedhorario; 

	$sql=$clundmedhorario->sql_query_ext(null,
													"*, (select count(sd23_d_consulta)
															from agendamentos
															where sd23_d_consulta = '$ano/$mes/$dia'
		            										and not exists ( select *
		            														from agendaconsultaanula
		            														where s114_i_agendaconsulta = sd23_i_codigo
		            														)		            										
		            										and sd23_i_undmedhor  = undmedhorario.sd30_i_codigo
													    )as total_agendado 
													",
													"sd30_i_diasemana, sd30_c_horaini", 
													"sd27_i_codigo = $sd27_i_codigo 
													and sd30_i_diasemana = $dia_semana
										             and ( sd30_d_valfinal is null or 
													       ( sd30_d_valfinal is not null and sd30_d_valfinal >= '$ano/$mes/$dia' ) 
													      )
													  and ( sd30_d_valinicial is null or 
													       ( sd30_d_valinicial is not null and sd30_d_valinicial <= '$ano/$mes/$dia' ) 
													      )	$sAusenciaPorCodGradeHorario $sCodigoGradeHorario
													      
													");
	$result_undmedhorario = $clundmedhorario->sql_record($sql);
	db_fieldsmemory($result_undmedhorario,0);												      
	$strWhere  = "sd23_i_undmedhor    = $sd30_i_codigo and 
				sd23_i_numcgs       = $z01_i_cgsund  and 
				sd23_d_consulta     = '$sd23_d_consulta2' and
				not exists ( select *
							from agendaconsultaanula
							where s114_i_agendaconsulta = sd23_i_codigo )";
	$strWhere .= ((!isset($s125_i_procedimento))||(@$s125_i_procedimento==""))?"":" and s125_i_procedimento = $s125_i_procedimento";
	$sql=$clagendamentos->sql_query_ext("","*","",$strWhere);
	$clagendamentos->sql_record($sql);
	$iValidaPaciente = $clagendamentos->numrows;
	
    /*
     * =====================================================
     *   TESTA PARA VER SE O AGENDAMENTO É FEITO POR COTAS
     * =====================================================
     */
    $vet = explode("/",$sd23_d_consulta);
    if ($iUpssolicitante != $sd02_i_codigo) {
    	
      $oResult = getCotasAgendamento($iUpssolicitante, $sd02_i_codigo, $rh70_estrutural, $vet[2], $vet[1], 
                                     $sd30_i_codigo); 
      $dIni    = "$vet[2]-$vet[1]-1";
      $dFim    = "$vet[2]-$vet[1]-";
      $dFim   .= date("t", strtotime("$vet[2]-$vet[1]-1"));
      if ($oResult->lStatus == 1) {
  	
  	    $sSubSqlWhere      = " sd27_i_rhcbo = $rh70_sequencial ";
        $sSubSqlWhere     .= " and sd23_i_upssolicitante = $iUpssolicitante ";
        $sSubSqlWhere     .= " and sd04_i_unidade = $sd02_i_codigo ";
        $sSubSqlWhere     .= " and sd23_d_consulta between '$dIni' and '$dFim' ";
        $sSubSqlWhere     .= " and not EXISTS ( select * from agendaconsultaanula where"; 
        $sSubSqlWhere     .= " s114_i_agendaconsulta = sd23_i_codigo ) ";
        $sSubSql           = $clagendamentos->sql_query_consulta_geral("", "count(sd23_i_codigo) as iAgendados", "", 
                                                                       $sSubSqlWhere);
        $rs                = $clagendamentos->sql_record($sSubSql);
        $oAgendamentosAnt  = db_utils::getColectionByRecord($rs, false, false,true);
        
        $sSubSqlWhere     .= " and sd23_i_undmedhor = $sd30_i_codigo";
        $sSubSql           = $clagendamentos->sql_query_consulta_geral("", "count(sd23_i_codigo) as iAgendados", "", 
                                                                       $sSubSqlWhere);
        $rs                  = $clagendamentos->sql_record($sSubSql);
        $oAgendamentosAntMed = db_utils::getColectionByRecord($rs, false, false, true);

        if ($clagendamentos->numrows > 0) {
          if ($oResult->aCotasAgendamento[0]->saldo_medico != null) {
            $iSaldo = (int)$oResult->aCotasAgendamento[0]->saldo_medico - (int)$oAgendamentosAnt[0]->iagendados;
          } else {
            $iSaldo = (int)$oResult->aCotasAgendamento[0]->s163_i_quantidade - (int)$oAgendamentosAnt[0]->iagendados;
          }
        }

        if ($iSaldo <= 0 ) {

          db_msgbox("Saldo Insuficiente para Agendamento");
          $lAgendaLiberada = false;

        } else {
          $lAgendaLiberada = true;
        }

      }

    } else {

      $sCampos  ="fc_saldoCotasPrestEspecComp";
      $sCampos .= "($sd02_i_codigo, '".$rh70_estrutural."', ".$vet[1].", ".$vet[2].") as saldo";
      $sSql     = "SELECT ";
      $sSql    .= $sCampos;
      $rs       = pg_query($sSql);

      if( pg_num_rows($rs) > 0) {
      	
        $oSaldoAgendamento  = db_utils::fieldsMemory($rs, 0);
        $iSaldoCotas = $oSaldoAgendamento->saldo;
        if ($iSaldoCotas <= 0) {
          db_msgbox("Saldo Insuficiente para Agendamento");
          $lAgendaLiberada = false;
        }
        else 
        {
          $lAgendaLiberada = true;
        }
        
      }
    	
    	
    }

	if( $iValidaPaciente != 0) {
		db_msgbox("Paciente ja incluído.");
		unset($incluir);
	}else if($lAgendaLiberada != true){
		unset($incluir);
	}else{
		db_inicio_transacao();
		//agendamentos
		$clagendamentos->sd23_i_undmedhor      = $sd30_i_codigo;
		$clagendamentos->sd23_i_usuario        = db_getsession("DB_id_usuario");
		$clagendamentos->sd23_i_numcgs         = $z01_i_cgsund;
		$clagendamentos->sd23_d_agendamento    = date("Y",db_getsession("DB_datausu")).'-'.date("m",db_getsession("DB_datausu")).'-'.date("d",db_getsession("DB_datausu"));
		$clagendamentos->sd23_d_consulta       = $sd23_d_consulta2;
		$clagendamentos->sd23_i_ficha          = "null"; //$sd23_i_ficha;
		$clagendamentos->sd23_c_hora           = $clagendamentos->proximahora($sd30_i_codigo,"$ano/$mes/$dia");
		$clagendamentos->sd23_i_situacao       = 1;
		$clagendamentos->sd23_i_upssolicitante = db_getsession("DB_coddepto");
		$clagendamentos->incluir(null);
		
		//agendaproced
		if( (int)$s125_i_procedimento > 0){
			$clagendaproced->s125_i_agendamento  = $clagendamentos->sd23_i_codigo;
			$clagendaproced->s125_i_procedimento = $s125_i_procedimento;
			$clagendaproced->incluir(null); 
			if( $clagendaproced->numrows_incluir == 0 ){
				$clagendamentos->erro_status="0";
				$clagendamentos->erro_msg = $clagendaproced->erro_msg; 
			}
		}
		db_fim_transacao($clagendamentos->erro_status=="0");
    $sql_ultcod = "select currval('agendamentos_sd23_codigo_seq'::text) as sd23_i_codigo";
    @$resultado = pg_query($sql_ultcod);
    @db_fieldsmemory($resultado, 0);
	}    
}
if(isset($anular)){
	db_inicio_transacao();
	$clagendamentos->excluir($chavepesquisaagenda);
	db_fim_transacao();	
}
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("/widgets/dbautocomplete.widget.js");
db_app::load("webseller.js");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table align="center" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
    <br><br>
    <center>
        <? include("forms/db_frmagendamento_ext.php"); ?>
    </center>
    </td>
  </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<?
  if(isset($confirma)){
     if($clagendamentos->erro_status!="0"){
	    	db_msgbox("Paciente Agendado!");
	    	?><script>
	    	   document.form1.saldo.value=<?=($saldo-1)?>;
               document.getElementById('saldo_div').innerHTML = <?=($saldo-1)?>+' Fichas';
               document.form1.anula.disabled=false;
               document.form1.consultas.disabled=false;
               document.form1.faa.disabled=false;
               document.form1.prontuario.disabled=false;
               document.form1.comprovante.disabled=false;
               document.form1.confirma.disabled=true;
               document.form1.nova.disabled=false;
               document.form1.nova.focus();
            </script><?
      }else{
	    	db_msgbox("Falha no agendamento \'$clagendamentos->erro_msg\'");
	    	?><script>
	    	   document.form1.saldo.value=<?=($saldo)?>;
               document.getElementById('saldo_div').innerHTML = <?=($saldo)?>+' Fichas';
               document.form1.sd23_i_codigo.value='';
               document.form1.z01_i_cgsund.value='';
               document.form1.z01_v_nome.value='';
               document.form1.consultas.disabled=false;
            </script><?
      }
  }
?>
<script>
  js_tabulacaoforms("form1","sd02_i_codigo",true,1,"sd02_i_codigo",true);
</script>