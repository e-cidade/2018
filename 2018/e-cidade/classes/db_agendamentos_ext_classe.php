<?php
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

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE agendamentos
require_once ("classes/db_agendamentos_classe.php");
class cl_agendamentos_ext extends cl_agendamentos  {
	//
	var $lancar_transf_individual = null;
	var $lancar_transf_geral      = null;
	var $gerar_faa                = null;
	var $opcoes                   = null;
	var $lado_transf              = null;
	var $total_agendado           = 0;
  var $lTrazerAnulados          = false;  // Determina se traz os anulados (e apenas os anulados) ou os não anulados (e apenas os não anulados)
  var $lTrazerAusencias         = false; // Determina se traz ou nao agendamentos se houver alguma ausencia do profissional registrada no dia indicado
  var $lMarcaTodasChekBox       = true;
	
	function sql_query_ext ( $sd23_i_codigo=null,$campos="*",$ordem=null,$dbwhere="", $lFiltraAnulados = true){ 
	     $sql = "select ";
	     if($campos != "*" ){
	       $campos_sql = split("#",$campos);
	       $virgula = "";
	       for($i=0;$i<sizeof($campos_sql);$i++){
	         $sql .= $virgula.$campos_sql[$i];
	         $virgula = ",";
	       }
	     }else{
	       $sql .= $campos;
	     }
	     $sql .= " from agendamentos ";
	     $sql .= "inner join db_usuarios   on  db_usuarios.id_usuario = agendamentos.sd23_i_usuario ";
	     $sql .= "inner join undmedhorario on  undmedhorario.sd30_i_codigo = agendamentos.sd23_i_undmedhor ";
	     $sql .= "inner join especmedico   on  especmedico.sd27_i_codigo = undmedhorario.sd30_i_undmed ";
	     $sql .= " left join sau_tipoficha on  sau_tipoficha.sd101_i_codigo = undmedhorario.sd30_i_tipoficha ";
	     $sql .= "inner join cgs           on  cgs.z01_i_numcgs = agendamentos.sd23_i_numcgs ";
	     $sql .= "inner join cgs_und       on  cgs_und.z01_i_cgsund = cgs.z01_i_numcgs ";
	     
	     $sql .= "inner join rhcbo           on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo ";
	     $sql .= "inner join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed ";
	     $sql .= "inner join medicos         on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico ";
	     $sql .= "inner join cgm             on cgm.z01_numcgm = medicos.sd03_i_cgm ";
	     $sql .= " left join prontagendamento on prontagendamento.s102_i_agendamento = agendamentos.sd23_i_codigo ";
	
	     $sql .= "inner join unidades       on sd02_i_codigo           = sd04_i_unidade ";
	     $sql .= "inner join db_depart      on db_depart.coddepto      = sd04_i_unidade ";
	     $sql .= " left  join db_departender on db_departender.coddepto = db_depart.coddepto ";
	     $sql .= " left  join bairro         on bairro.j13_codi         = db_departender.codbairro ";
	     $sql .= " left  join ruas           on ruas.j14_codigo         = db_departender.codlograd ";
	     $sql .= " left  join ruascep        on ruascep.j29_codigo      = ruas.j14_codigo ";
	     $sql .= " left  join logradcep      on logradcep.j65_lograd    = ruas.j14_codigo ";
	     $sql .= " left  join ceplogradouros on j65_ceplog              = cp06_codlogradouro ";
	     $sql .= " left  join agendaconsultaanula on s114_i_agendaconsulta = sd23_i_codigo ";
	     $sql .= " left  join agendaproced     on agendaproced.s125_i_agendamento = agendamentos.sd23_i_codigo ";
	     $sql .= " left  join sau_procedimento on sau_procedimento.sd63_i_codigo  = agendaproced.s125_i_procedimento ";
	     	     
	     
	     
	     
	     $sql2 = "";
	     if($dbwhere==""){
	       if($sd23_i_codigo!=null ){
	         $sql2 .= " where agendamentos.sd23_i_codigo = $sd23_i_codigo ";
	       } 
	     }else if($dbwhere != ""){
	       $sql2 = " where $dbwhere";
	     }
	     
       if($lFiltraAnulados) {

   	     if( $sql2 == "" ){
           $sql2 = " where s114_i_agendaconsulta is null ";
	       } else {
           $sql2 .= " and s114_i_agendaconsulta is null ";
         }

       }

	     $sql .= $sql2;
	     
	     //$sql .= " and not exists ( select *
		 //           				from agendaconsultaanula
		 //           				where s114_i_agendaconsulta = sd23_i_codigo
		 //           			  )";
	     if($ordem != null ){
	       $sql .= " order by ";
	       $campos_sql = split("#",$ordem);
	       $virgula = "";
	       for($i=0;$i<sizeof($campos_sql);$i++){
	         $sql .= $virgula.$campos_sql[$i];
	         $virgula = ",";
	       }
	     }
	     return $sql;
	}
	// funcao do sql
	function sql_query_ext2 ( $sd23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
	     $sql = "select ";
	     if($campos != "*" ){
	       $campos_sql = split("#",$campos);
	       $virgula = "";
	       for($i=0;$i<sizeof($campos_sql);$i++){
	         $sql .= $virgula.$campos_sql[$i];
	         $virgula = ",";
	       }
	     }else{
	       $sql .= $campos;
	     }
	     $sql .= " from agendamentos ";
	     $sql .= "      inner join db_usuarios   on  db_usuarios.id_usuario = agendamentos.sd23_i_usuario";
	     $sql .= "      inner join undmedhorario on  undmedhorario.sd30_i_codigo = agendamentos.sd23_i_undmedhor ";
	     $sql .= "      inner join especmedico   on  especmedico.sd27_i_codigo = undmedhorario.sd30_i_undmed";
	     //$sql .= "       left join undmedhorario on  undmedhorario.sd30_i_undmed = agendamentos.sd23_i_especmed
	     //                                       and  undmedhorario.sd30_i_diasemana =  ( extract(dow from agendamentos.sd23_d_consulta ) + 1 ) 
	     //        ";
	     $sql .= "       left join sau_tipoficha on  sau_tipoficha.sd101_i_codigo = undmedhorario.sd30_i_tipoficha ";
	     $sql .= "      inner join cgs           on  cgs.z01_i_numcgs = agendamentos.sd23_i_numcgs";
	     $sql .= "      inner join cgs_und       on  cgs_und.z01_i_cgsund = cgs.z01_i_numcgs";
	     
	     $sql .= "      inner join rhcbo           on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";
	     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
	     $sql .= "      inner join medicos         on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
	     $sql .= "      inner join cgm             on cgm.z01_numcgm = medicos.sd03_i_cgm ";
	     $sql .= "       left join prontagendamento on prontagendamento.s102_i_agendamento = agendamentos.sd23_i_codigo ";
	
	     $sql .= "      inner join unidades       on sd02_i_codigo           = sd04_i_unidade ";
	     $sql .= "      inner join db_depart      on db_depart.coddepto      = sd04_i_unidade ";
	     $sql .= "      left  join db_departender on db_departender.coddepto = db_depart.coddepto ";
	     $sql .= "      left  join bairro         on bairro.j13_codi         = db_departender.codbairro ";
	     $sql .= "      left  join ruas           on ruas.j14_codigo         = db_departender.codlograd ";
	     $sql .= "      left  join ruascep        on ruascep.j29_codigo      = ruas.j14_codigo ";
	     $sql .= "      left  join logradcep      on logradcep.j65_lograd    = ruas.j14_codigo ";
	     $sql .= "      left  join ceplogradouros on j65_ceplog              = cp06_codlogradouro ";
	     $sql .= "      left  join agendaconsultaanula on s114_i_agendaconsulta = sd23_i_codigo ";
	     	     
	     
	     
	     
	     $sql2 = "";
	     if($dbwhere==""){
	       if($sd23_i_codigo!=null ){
	         $sql2 .= " where agendamentos.sd23_i_codigo = $sd23_i_codigo "; 
	       } 
	     }else if($dbwhere != ""){
	       $sql2 = " where $dbwhere";
	     }
	     $sql .= $sql2;
	     
	     if($ordem != null ){
	       $sql .= " order by ";
	       $campos_sql = split("#",$ordem);
	       $virgula = "";
	       for($i=0;$i<sizeof($campos_sql);$i++){
	         $sql .= $virgula.$campos_sql[$i];
	         $virgula = ",";
	       }
	     }
	     return $sql;
	}
	/**
	 * Função para reotornar próxima hora disponível.
	 *
	 * @param integer $sd30_i_codigo - código da tabela undmedhoraraio
	 * @param date $sd23_d_consulta - data da consulta
	 * @return string - retorna próxima hora disponível
	 */
	function proximahora($sd30_i_codigo, $sd23_d_consulta ){
		//pega total agendamento
		$str_query         = "select fc_totalagendado( '$sd23_d_consulta', $sd30_i_codigo ); ";
		$res_agendamento   = db_query($str_query)or die($str_query);
		$obj_agendamento   = db_utils::fieldsMemory($res_agendamento,0);
		$arr_totalagendado = explode( ",", $obj_agendamento->fc_totalagendado );
		$arr_valores       = array( 'V_FICHAS'=>0, 
									'V_RESERVAS'=>1,
									'V_HORAINI'=>2,
									'V_HORAFIM'=>3,
									'V_UNDMED'=>4,
									'V_TOTALAGENDADO'=>5,
									'V_DISPONIVEL'=>6,
									'V_TIPOGRADE'=>7
		);
		//Calcula intervalo
		$hora_ini           = trim($arr_totalagendado[ $arr_valores["V_HORAINI"] ]);
		$hora_fim           = trim($arr_totalagendado[ $arr_valores["V_HORAFIM"] ]);
		$minutostrabalhados = $this->minutos($hora_ini,$hora_fim);
		$nro_fichas         = trim($arr_totalagendado[ $arr_valores["V_FICHAS"] ]);
		$intervalo          = 0;
		$mi_interva1 = 0;
		$mi_interva2 = 1;
		
		
		if( $nro_fichas != 0 && trim( $arr_totalagendado[ $arr_valores["V_TIPOGRADE"] ] ) == 'I' ){
			$intervalo   = number_format($minutostrabalhados / $nro_fichas,2,'.','');
			for( $h=0; $h < $arr_totalagendado[ $arr_valores["V_TOTALAGENDADO"] ]; $h++){
			    if( $intervalo != 0){
					$hora_ini    = $this->somahora($hora_ini,($intervalo+$mi_interva2));	
					$mi_interva1 = -1;
					$mi_interva2 = 0;
			    }
			}
		}

		return substr($hora_ini,0,5);
	}
	/**
	 * função retorno minutos  entre duas horas
	 * @author Cristian Tales
	 * @param string $hora1 - hora inicial
	 * @param string $hora2 - hora final
	 * @return string
	 */
	function minutos($hora1,$hora2){
		$Hinicio = substr($hora1,0,2);
		$Minicio = substr($hora1,3,2);
		$Hfim = substr($hora2,0,2);
		$Mfim = substr($hora2,3,2);
		///diferenÃ§a em horas
		$minutosFim = $Mfim+($Hfim*60);
		$minutosIni = $Minicio+($Hinicio*60);
		$horas_trabalhadas = ($minutosFim-$minutosIni)/60;
		$horas_trabalhadas = $horas_trabalhadas>20?$horas_trabalhadas-20:$horas_trabalhadas;
		$decimal =  strstr($horas_trabalhadas,".");
		if($decimal!=""){
			$minutos_decimal = round(($decimal*60));
			$explode = explode(".",$horas_trabalhadas);
			$horas_finais = @str_pad($explode[0],2,0,str_pad_left).":".@str_pad($minutos_decimal,2,0,str_pad_left);
			$minutos_finais = $minutos_decimal + ( $explode[0] * 60 );
		}else{
			$horas_finais = @str_pad($horas_trabalhadas,2,0,str_pad_left).":00";
			$minutos_finais = $horas_trabalhadas * 60;
		}
		$minutos_finais = $minutos_finais<0?$minutos_finais*(-1):$minutos_finais;
		return $minutos_finais;
	}
	
  /*
   * função par somar minutos numa hora
   * @author Cristian Tales
   * @revision Tony F. B. M. Ribeiro
   * @param string $inicio - hora
   * @param string $minutos - minutos a somar na hora
   * @return string
   */
  function somahora($sHoraIni, $iMinutosSomar) {

    $aHoraIni = explode(':', $sHoraIni);
    $iHoraIni = $aHoraIni[0];
    $iMinIni  = $aHoraIni[1];

    $iMinIni  = number_format($iMinIni + $iMinutosSomar, 2, '.', '');

    $aMin     = explode('.', $iMinIni); 
		if ($aMin[1] >= 60) {

	    $iMinIni  = $aMin[0] + 1;
			$iMinIni += ($aMin[1] - 60 ) / 100;

		}
    while ($iMinIni >= 60) {
  
      $iHoraIni++;
      if ($iHoraIni == 24) {
        $iHoraIni = 0;
      }
      $iMinIni -= 60;
  
    }
    if ($iMinIni < 10) {

      $iMinIni = '0'.$iMinIni;
    }
  
    return str_pad($iHoraIni, 2, 0, STR_PAD_LEFT).':'.$iMinIni;

  }

	function cria_table_gera_FA($sd27_i_codigo, $chave_diasemana,$sd23_d_consulta,$clagendamentos,$clundmedhorario,$funcao_js){
		$ano           = substr( $sd23_d_consulta, 6, 4 );
		$mes           = substr( $sd23_d_consulta, 3, 2 );
		$dia           = substr( $sd23_d_consulta, 0, 2 );
		
		$clsau_config  = db_utils::getDao("sau_config_ext");
		$resSau_Config = $clsau_config->sql_record( $clsau_config->sql_query_ext() );
		$objSau_Config = db_utils::fieldsMemory($resSau_Config,0);
		
		$result_undmedhorario = $clundmedhorario->sql_record( $clundmedhorario->sql_query_ext("","*","sd30_i_diasemana, sd30_c_horaini", 
								"sd27_i_codigo = $sd27_i_codigo 
								and sd30_i_diasemana = $chave_diasemana
					             and ( sd30_d_valfinal is null or 
								       ( sd30_d_valfinal is not null and sd30_d_valfinal >= '$ano/$mes/$dia' ) 
								      )
							  and ( sd30_d_valinicial is null or 
							       ( sd30_d_valinicial is not null and sd30_d_valinicial <= '$ano/$mes/$dia' ) 
							      )						      
								      
								 ") ) ;

		echo "<form name='gerafaa' action='' method='post'>";
		echo "<table border='0' cellspacing='2px' width='100%' height='100%' cellpadding='1px' bgcolor='#cccccc'>";
		
		for( $xHora=0; $xHora < $clundmedhorario->numrows; $xHora++ ){
			
			$obj_undmedhorario    = db_utils::fieldsMemory( $result_undmedhorario, $xHora );
			
			//$res_totalficha = $clagendamentos->sql_record("select fc_totalagendado('$ano/$mes/$dia',{$obj_undmedhorario->sd30_i_codigo});");
			//$obj_totalficha = db_utils::fieldsMemory($res_totalficha, 0 );
			//$arr_totalficha = explode(",", $obj_totalficha->fc_totalagendado );
			
			$reservadas = $obj_undmedhorario->sd30_i_reservas;
			$nro_fichas = $obj_undmedhorario->sd30_i_fichas;
			//Calcula intervalo
			$hora_ini   = $obj_undmedhorario->sd30_c_horaini;
			$hora_fim   = $obj_undmedhorario->sd30_c_horafim;
			$minutostrabalhados = $this->minutos($hora_ini,$hora_fim);
			$intervalo          = 0;
			if($nro_fichas!=0 && $obj_undmedhorario->sd30_c_tipograde == 'I' ){
				$intervalo      = number_format($minutostrabalhados / $nro_fichas,2,'.','');
			}
			
			//Agenda
            //and s102_i_agendamento is null
            $strAparecer = $objSau_Config->s103_c_apareceragenda == "S"?"": "and s102_i_agendamento is null";
			$str_query = $clagendamentos->sql_query_ext("","sd23_i_codigo,sd23_i_ano,sd23_i_mes,sd23_i_ficha,z01_i_numcgs,z01_v_nome,sd23_i_undmedhor","sd23_i_codigo", 
													"sd23_d_consulta = '$ano/$mes/$dia'
													$strAparecer
		            								and not exists ( select *
		            													from agendaconsultaanula
		            													where s114_i_agendaconsulta = sd23_i_codigo
		            												)
		            								and sd23_i_undmedhor = {$obj_undmedhorario->sd30_i_codigo} 
		            								and not exists ( select *
		            								                   from ausencias
		            								                  where sd06_i_especmed = $sd27_i_codigo
		            								                    and '$ano/$mes/$dia' between sd06_d_inicio and sd06_d_fim
		            								               ) 
		            								");
			$res_agenda = $clagendamentos->sql_record( $str_query );
			$linhas     = $clagendamentos->numrows;
			$linha      = 0;
			
			$this->total_agendado += $linhas; 
			
			if( $linhas >= $nro_fichas ){
				$reservadas = 0;
			}else{
				$dif = $nro_fichas - $linhas;
				if( $dif < $reservadas ){
					$reservadas = $dif;
				}
			}
			if( $linhas > $nro_fichas ){
				$nro_fichas = $linhas;
			}
			
			$diferenca   = $nro_fichas-$reservadas;
			$mi_interva1 = 0;
			$mi_interva2 = 1;
			
			?>
<tr class='cabec'>
	<td colspan="<?=$iNcolunas?>" align="left">
					<?	
					if( $this->lancar_transf_geral != null ){ ?>					
						<input name="hora_transf" type="radio"
		value="<?=$obj_undmedhorario->sd30_i_codigo?>"
		onclick="js_hora_trans(this.value,'<?=$this->lado_transf?>')">
					<? 
					}
					echo $obj_undmedhorario->sd30_i_codigo." - ".$obj_undmedhorario->sd101_c_descr; 
					?>
					</td>
</tr>
<tr class='cabec'>
					<? if( $this->gerar_faa != null ){ ?>
					<td class='cabec' title='Inverte marcação' align='center'><a
		title='Inverte Marcação' href='' onclick='js_checkbox();return false'>M</a></td>
					<?} ?>
					<td class='cabec' align="center">Ficha</td>
	<td class='cabec' align="center">Hs Inicial</td>
	<td class='cabec' align="center">Hs Final</td>
	<td class='cabec' align="center">Reserva</td>
	<td class='cabec' align="center">Tipo Grade</td>
	<td class='cabec' align="center">CGS</td>
	<td class='cabec' align="center">Nome do Paciente</td>
					<? 
						if( $this->lancar_transf_individual != null || $this->opcoes != null || $this->gerar_faa != null ){ 
							?><td class='cabec' align="center">Opções</td><?
						}
					?>					
				</tr>

<?
			for( $h=0; $h < $nro_fichas; $h++){
			    $nro_ficha = str_pad($h,3,0,STR_PAD_LEFT);
			    $id_ficha  = 0;
			    $codigo    = 0;
			    if($h>=$diferenca && $linha>=$linhas){
			    	$reservada= "Sim";
			    	$paciente = "-- R E S E R V A D A --";
			    	$cgs      = "";
			    	$natend   = "x x x x x";
			    }else{
			    	$reservada= "Não";
			    	$paciente  = "---------";
			    	$cgs      = "";
			    	$natend    = "x x x x x";
			    	if($linha<$linhas){
			    		$obj_agenda = db_utils::fieldsMemory( $res_agenda, $linha );
			    		$id_ficha = $obj_agenda->sd23_i_ficha;
			    		//if( ($id_ficha == ($h+1)) && ($obj_agenda->sd23_i_undmedhor == $obj_undmedhorario->sd30_i_codigo) ){	    		
			    		if( ($obj_agenda->sd23_i_undmedhor == $obj_undmedhorario->sd30_i_codigo) ){	    		
			    			$codigo   = $obj_agenda->sd23_i_codigo;
			    			$cgs      = $obj_agenda->z01_i_numcgs;
				    		$paciente = $obj_agenda->z01_v_nome;
				    		$linha++;
			    		}else if($h>=$diferenca){
			    			$id_ficha = 0;
					    	$reservada= "Sim";
					    	$paciente = "-- R E S E R V A D A --";
					    	$cgs      = "";
					    	$natend   = "x x x x x";    			
			    		}
			    	}
			    }
			    if( $intervalo != 0){
			 		$hora_fim = $clagendamentos->somahora($hora_ini,$intervalo+$mi_interva1);
			    }else{
			    	$hora_fim = "";	
			    }
			    
    			if($paciente == "---------" || $paciente == "-- R E S E R V A D A --"){

            continue;
           }  
				?>
<tr>
	<!--  <td style="border:1px solid #AACCCC;"   class='corpo' title='Inverte a marcação' align='center'><input ' ' type='checkbox' name='CHECK_<?=($h+1)?>' id='CHECK_<?=($h+1)?>'></td> -->
					<? if( $this->gerar_faa != null ) { ?>
					<td style="border: 1px solid #AACCCC;" class='corpo'
		title='Inverte a marcação' align='center'><input
		' ' 
							type='checkbox' name='check[]' id='CHECK_<?=($h+1)?>'
		onclick='js_marcado(<?=$codigo?>);return true;' value='<?=$codigo ?>'
		<?=$codigo==0?"disabled":"" ?>></td>
					<? } ?>
					<td style="border: 1px solid #AACCCC;" class='corpo' align="center"><?=($h+1)?></td>
	<td style="border: 1px solid #AACCCC;" class='corpo' align="center"><?=substr($hora_ini,0,5) ?></td>
	<td style="border: 1px solid #AACCCC;" class='corpo' align="center"><?=substr($hora_fim,0,5) ?></td>
	<td style="border: 1px solid #AACCCC;" class='corpo' align="center"><?=$reservada ?></td>
	<td style="border: 1px solid #AACCCC;" class='corpo' align="center"><?=$obj_undmedhorario->sd30_c_tipograde=="I"?"Intervalo":"Período" ?></td>
	<td style="border: 1px solid #AACCCC;" class='corpo' align="center"><?=$cgs ?></td>
	<td style="border: 1px solid #AACCCC;" class='corpo' align="center"><?=$paciente ?></td>
					<? if( $this->lancar_transf_individual != null || $this->opcoes != null || $this->gerar_faa != null ){ ?> 
						<td class='corpo' nowrap align="center"><a
		title='Anular conteúdo da linha' href='#'
		onclick='js_excluir(<?=$codigo?>);return false;'>&nbsp;A&nbsp;</a></td>
					<? }
						if( $this->lancar_transf_individual != null && (int)$cgs == 0 ){
		       				?>
		       				<td class='corpo' nowrap align="center"><a
		title='LANÇAR CONTEÚDO DA LINHA' href='#'
		onclick="js_lancar(<?=($h+1)?>,'<?=substr($hora_ini,0,5) ?>',<?=$obj_undmedhorario->sd30_i_codigo?>,'<?=$funcao_js ?>');">&nbsp;Lançar&nbsp;</a>
	</td>
		       				<?
						}
					?>
				</tr>
<?
			    if( $intervalo != 0){
					$hora_ini    = $this->somahora($hora_ini,($intervalo+$mi_interva2));	
					$mi_interva1 = -1;
					$mi_interva2 = 0;
			    }
			}
		
		}//fim for
		echo "</table>";
		echo "</form>";
		?>
<!--  -->
<script>

		function js_checkbox(){
			var obj = document.gerafaa;
			for(i=0;i<obj.length;i++){
		    	if(obj.elements[i].type=='checkbox' && obj.elements[i].disabled == false){
		    		obj.elements[i].checked = !( obj.elements[i].checked );	
		    	}
		    }
		}
		
		function js_excluir(id_ficha,z01_i_numcgs){
			if( id_ficha != undefined && id_ficha != 0 ){
				top = ( screen.availHeight-600 ) / 2;
				left = ( screen.availWidth-600 ) / 2;
				x  = 'sau1_agendaconsultaanula001.php';
				x += '?s114_i_agendaconsulta='+id_ficha;
				x += '&db_opcao=1';
				
				js_OpenJanelaIframe('parent','db_iframe_agendamento',x,'Anulação',true, top, left, 600, 250);
			}else{
				alert('Registro não pode ser excluído.');
			}	
		}
		
		</script>
<?
		
				
	}//fim função cria_table

	function cria_table($sd27_i_codigo, $chave_diasemana,$sd23_d_consulta,$clagendamentos,$clundmedhorario,$funcao_js) {

    if($this->lado_transf == 'de' && (int)$this->lancar_transf_geral == 1) {

      $lExibirCheckBox = true;
      $iNcolunas = 9;

    } else {

      $lExibirCheckBox = false;
      $iNcolunas = 8;

    }


		$ano           = substr( $sd23_d_consulta, 6, 4 );
		$mes           = substr( $sd23_d_consulta, 3, 2 );
		$dia           = substr( $sd23_d_consulta, 0, 2 );
		
		$result_undmedhorario = $clundmedhorario->sql_record( $clundmedhorario->sql_query_ext("","*","sd30_i_diasemana, sd30_c_horaini", 
								"sd27_i_codigo = $sd27_i_codigo 
								and sd30_i_diasemana = $chave_diasemana
					             and ( sd30_d_valfinal is null or 
								       ( sd30_d_valfinal is not null and sd30_d_valfinal >= '$ano/$mes/$dia' ) 
								      )
							  and ( sd30_d_valinicial is null or 
							       ( sd30_d_valinicial is not null and sd30_d_valinicial <= '$ano/$mes/$dia' ) 
							      )						      
								      
								 ") ) ;

		//echo "<form name='formtransferencia' action='' method='post'>";
		echo "<table border='0' cellspacing='2px' width='100%' height='100%' cellpadding='1px' bgcolor='#cccccc'>";
		
		for( $xHora=0; $xHora < $clundmedhorario->numrows; $xHora++ ){
			
			$obj_undmedhorario    = db_utils::fieldsMemory( $result_undmedhorario, $xHora );
			
			//$res_totalficha = $clagendamentos->sql_record("select fc_totalagendado('$ano/$mes/$dia',{$obj_undmedhorario->sd30_i_codigo});");
			//$obj_totalficha = db_utils::fieldsMemory($res_totalficha, 0 );
			//$arr_totalficha = explode(",", $obj_totalficha->fc_totalagendado );
			
			$reservadas = $obj_undmedhorario->sd30_i_reservas;
			$nro_fichas = $obj_undmedhorario->sd30_i_fichas;
			//Calcula intervalo
			$hora_ini   = $obj_undmedhorario->sd30_c_horaini;
			$hora_fim   = $obj_undmedhorario->sd30_c_horafim;
			$minutostrabalhados = $this->minutos($hora_ini,$hora_fim);
			$intervalo          = 0;
			if($nro_fichas!=0 && $obj_undmedhorario->sd30_c_tipograde == 'I' ){
				$intervalo      = number_format($minutostrabalhados / $nro_fichas,2,'.','');
			}
			

      if($this->lTrazerAnulados) { // Traz somente os anulados

        $iUsuario = db_getsession('DB_id_usuario');
        $lFiltrarAnulados = false;
        $sFiltroNaoAnulados = " and s114_i_agendaconsulta is not null and s114_i_login = $iUsuario ";

      } else {

        $lFiltrarAnulados = true;
        $sFiltroNaoAnulados = '';

      }

      if(!$this->lTrazerAusencias) { // Nao traz agendamentos se houver ausencia do profissional registrada para todo o dia

        $sAusencias = " and not exists (select *
			              			                      from ausencias
			            				                        where sd06_i_especmed = $sd27_i_codigo
                                                    and '$ano-$mes-$dia' between sd06_d_inicio and sd06_d_fim
                                                    and sd06_c_horainicio is null
                                                    and sd06_c_horafim is null) ";
      
      } else {
        $sAusencias = '';
      }

			//Agenda
			$str_query = $clagendamentos->sql_query_ext("","sd23_i_codigo,sd23_i_ano,sd23_i_mes,sd23_i_ficha,z01_i_numcgs,z01_v_nome,sd23_i_undmedhor","sd30_c_horaini, sd23_i_ficha", 
														"sd23_d_consulta = '$ano/$mes/$dia'
			            								and sd23_i_situacao = 1
			            								and sd23_i_undmedhor = {$obj_undmedhorario->sd30_i_codigo}
                                  $sAusencias
                                  $sFiltroNaoAnulados ",
                                  $lFiltrarAnulados
			            								);
            // die ($str_query);
			$res_agenda = $clagendamentos->sql_record( $str_query );
			$linhas     = $clagendamentos->numrows;
			$linha      = 0;
			
			$this->total_agendado += $linhas; 
			
			if( $linhas >= $nro_fichas ){
				$reservadas = 0;
			}else{
				$dif = $nro_fichas - $linhas;
				if( $dif < $reservadas ){
					$reservadas = $dif;
				}
			}
			if( $linhas > $nro_fichas ){
				$nro_fichas = $linhas;
			}
			
			$diferenca   = $nro_fichas-$reservadas;
			$mi_interva1 = 0;
			$mi_interva2 = 1;
			
			?>
<tr class='cabec'>
	<td colspan="<?=$iNcolunas?>" align="left">
					<?	
					echo $obj_undmedhorario->sd30_i_codigo." - ".$obj_undmedhorario->sd101_c_descr; 
					?>
					</td>
</tr>
<tr class='cabec'>
	<? 
  if($lExibirCheckBox) {
    echo '<td class="cabec" align="acenter"><input type="button" value="M" id="marcarTodos"'.
         ' onclick="js_marcarTodos();"></td>';
  }
  ?>
	<td class='cabec' align="center">Ficha</td>
	<td class='cabec' align="center">Agenda</td>
	<td class='cabec' align="center">Hs Inicial</td>
	<td class='cabec' align="center">Hs Final</td>
	<td class='cabec' align="center">Reserva</td>
	<td class='cabec' align="center">Tipo Grade</td>
	<td class='cabec' align="center">CGS</td>
	<td class='cabec' align="center">Nome do Paciente</td>
					<? 
						if( $this->lancar_transf_individual != null || $this->opcoes != null ){ 
							?><td class='cabec' align="center">Opções</td><?
						}
					 ?>					
				</tr>

<?
			for( $h=0; $h < $nro_fichas; $h++){

			    $nro_ficha = str_pad($h,3,0,STR_PAD_LEFT);
			    $id_ficha  = 0;
			    $codigo    = 0;

			    if($h>=$diferenca && $linha>=$linhas){
			    	$reservada= "Sim";
			    	$paciente = "-- R E S E R V A D A --";
			    	$cgs      = "";
			    	$natend   = "x x x x x";
			    }else{
			    	$reservada= "Não";
			    	$paciente  = "---------";
			    	$cgs      = "";
			    	$natend    = "x x x x x";
			    	if($linha<$linhas){
			    		$obj_agenda = db_utils::fieldsMemory( $res_agenda, $linha );
			    		$id_ficha = $obj_agenda->sd23_i_ficha;
			    		//if( ($id_ficha == ($h+1)) && ($obj_agenda->sd23_i_undmedhor == $obj_undmedhorario->sd30_i_codigo) ){	    		
			    		if( ($obj_agenda->sd23_i_undmedhor == $obj_undmedhorario->sd30_i_codigo) ){	    		
			    			$codigo   = $obj_agenda->sd23_i_codigo;
			    			$cgs      = $obj_agenda->z01_i_numcgs;
				    		$paciente = $obj_agenda->z01_v_nome;
				    		
				    		$linha++;
			    		}else if($h>=$diferenca){
			    			$id_ficha = 0;
					    	$reservada= "Sim";
					    	$paciente = "-- R E S E R V A D A --";
					    	$cgs      = "";
					    	$natend   = "x x x x x";    			
			    		}
			    	}
			    }
			    if( $intervalo != 0){
			 		$hora_fim = $clagendamentos->somahora($hora_ini,$intervalo+$mi_interva1);
			    }else{
			    	$hora_fim = "";	
			    }
			    
				?>
<tr>
	<?
  if(!isset($codigo) || empty($codigo)) {
    $sDisabled = 'disabled';
  } else {
    $sDisabled = '';
  }
  if($lExibirCheckBox) {
    echo '<td style="border: 1px solid #AACCCC;" class="corpo" align="center"> <input type="checkbox" name="ckbox"'.
         '" value="'.$codigo.'" '.$sDisabled.'></td>';
  }
  ?>
	<td style="border: 1px solid #AACCCC;" class='corpo' align="center"><?=($h+1)?></td>
	<td style="border: 1px solid #AACCCC;" class='corpo' align="center"><?=$codigo?></td>
	<td style="border: 1px solid #AACCCC;" class='corpo' align="center"><?=substr($hora_ini,0,5) ?></td>
	<td style="border: 1px solid #AACCCC;" class='corpo' align="center"><?=substr($hora_fim,0,5) ?></td>
	<td style="border: 1px solid #AACCCC;" class='corpo' align="center"><?=$reservada ?></td>
	<td style="border: 1px solid #AACCCC;" class='corpo' align="center"><?=$obj_undmedhorario->sd30_c_tipograde=="I"?"Intervalo":"Período" ?></td>
	<td style="border: 1px solid #AACCCC;" class='corpo' align="center"><?=$cgs ?></td>
	<td style="border: 1px solid #AACCCC;" class='corpo' align="center"><?=$paciente ?></td>
					<?
						if( $this->lancar_transf_individual != null && (int)$cgs == 0 ){
		       				?>
		       				<td class='corpo' nowrap align="center"><a
		title='LANÇAR CONTEÚDO DA LINHA' href='#'
		onclick="js_lancar(<?=($h+1)?>,'<?=substr($hora_ini,0,5) ?>',<?=$obj_undmedhorario->sd30_i_codigo?>,'<?=$funcao_js ?>');">&nbsp;Lançar&nbsp;</a>
	</td>
		       				<?
						}
					?>
				</tr>
<?
			    if( $intervalo != 0){
					$hora_ini    = $this->somahora($hora_ini,($intervalo+$mi_interva2));	
					$mi_interva1 = -1;
					$mi_interva2 = 0;
			    }
			}
		
		}//fim for
		echo "</table>";
		//echo "</form>";
?>
<script>

js_hora_trans2(<?=$obj_undmedhorario->sd30_i_codigo?>, '<?=$this->lado_transf?>');
<?if($lExibirCheckBox && $this->lMarcaTodasChekBox) {?>
  js_marcarTodos();
<?}?>

function js_hora_trans2(valor, lado) {

	x = 'parent.document.form1.lado_'+lado+'.value='+valor;
	eval( x );

}

function js_marcarTodos() {
    
  oElementos = document.getElementsByName('ckbox');
  if(document.getElementById('marcarTodos').value == 'M') {

    for(i = 0; i < oElementos.length; i++) {
     
      if(!oElementos[i].disabled) {
        oElementos[i].checked = true;
      }

    }
    document.getElementById('marcarTodos').value = 'D';

  } else {
 
    for(i = 0; i < oElementos.length; i++) {
     
      if(!oElementos[i].disabled) {
        oElementos[i].checked = false;
      }

    }
    document.getElementById('marcarTodos').value = 'M';

  }

}
</script>
<?
				
	}//fim função cria_table
  
	/**
	 * função retorna a variavel expecifica de um array de valores
	 * 
	 * @param $sd30_i_codigo - código tabela undmedhorario
	 * @param $sd23_d_consulta - data
	 * @param $strVariavel - 'V_FICHAS'=>0, 
							'V_RESERVAS'=>1,
							'V_HORAINI'=>2,
							'V_HORAFIM'=>3,
							'V_UNDMED'=>4,
							'V_TOTALAGENDADO'=>5,
							'V_DISPONIVEL'=>6,
							'V_TIPOGRADE'=>7
	 */
	function fichas($sd30_i_codigo, $sd23_d_consulta, $strVariavel ){
		//pega total agendamento
		$str_query         = "select fc_totalagendado( '$sd23_d_consulta', $sd30_i_codigo ); ";
		$res_agendamento   = db_query($str_query)or die($str_query);
		$obj_agendamento   = db_utils::fieldsMemory($res_agendamento,0);
		$arr_totalagendado = explode( ",", $obj_agendamento->fc_totalagendado );
		$arr_valores       = array( 'V_FICHAS'=>0, 
									'V_RESERVAS'=>1,
									'V_HORAINI'=>2,
									'V_HORAFIM'=>3,
									'V_UNDMED'=>4,
									'V_TOTALAGENDADO'=>5,
									'V_DISPONIVEL'=>6,
									'V_TIPOGRADE'=>7
		);
		$intValor         = trim($arr_totalagendado[ $arr_valores[ $strVariavel ] ]);
		return $intValor; 
		
	}
	
}
?>