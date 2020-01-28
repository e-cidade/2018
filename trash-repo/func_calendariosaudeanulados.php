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
 
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("classes/db_undmedhorario_ext_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']); // ta com o globals desativado no php -- Crestani


class calendario{ 
   var $sem;//Array com os dias da semana como índice 
   var $mes;//Array com os meses do ano 
   var $nome_objeto_data;
   var $shutdown_function = "";
   var $centralagenda="N";

   function inicializa(){//Atribui valores para $sem e $mes.
       $this->sem=array('Sun'=>1,'Mon'=>2,'Tue'=>3,'Wed'=>4,'Thu'=>5,'Fri'=>6,'Sat'=>7);
       $this->mes=array('1'=>'JANEIRO','2'=>'FEVEREIRO','3'=>'MARÇO','4'=>'ABRIL','5'=>'MAIO','6'=>'JUNHO','7'=>'JULHO','8'=>'AGOSTO','9'=>'SETEMBRO','10'=>'OUTUBRO','11'=>'NOVEMBRO','12'=>'DEZEMBRO');
   } 

   function aux($i){//Complementa a tabela com espaços em branco 
      $retval=""; 
      for($k=0;$k < $i;$k++){ 
         $retval.="<td width=\"20\">&nbsp;</td>"; 
      } 
      return $retval; 
   }
   
   function arr_search( $array, $valor ){
      	for( $x=0; $x < count($array); $x++){
      		if( $array[$x] == $valor ){
      			return true;
      		}
      	}
      	return false;      	
   }
   
	function cria($dia,$mes,$ano,$marca=0,$str_where, $result_unidademedico,$fechar=false){
		$this->inicializa();
		$last  =date ("d", mktime (0,0,0,$mes+1,0,$ano));/*Inteiro do ultimo dia do mês*/
		if($last<$dia) {
			$dia = $last;
		}
		$verf=date ("d/n/Y", mktime (0,0,0,$mes,$dia,$ano));/*Corrige qualquer data invalida*/
		$pieces=explode("/",$verf);
		$dia=$pieces[0];
		$mes=$pieces[1];
		$ano=$pieces[2];
		$diasem=date ("D", mktime (0,0,0,$mes,1,$ano));/*String com dia da semana em inglês*/
		$str = "";
		if($this->sem[$diasem] != 1){/*Se dia semana diferente de domingo,completa com colunas em branco*/
			$valor=$this->sem[$diasem]-1;
			$str="<tr align=center >".$this->aux($valor);
		}
		
		//Pega os dias da semana
		for( $x=0; $x < pg_num_rows( $result_unidademedico ); $x++){
			$obj_result = db_utils::fieldsMemory($result_unidademedico,$x);
			$arr_diasem[] = $obj_result->sd30_i_diasemana;
		}
		//Verifica se é para aparecer agendamentos anteriores
		$clsau_config  = db_utils::getDao("sau_config_ext");
		$resSau_Config = $clsau_config->sql_record( $clsau_config->sql_query_ext() );
		$objSau_Config = db_utils::fieldsMemory($resSau_Config,0);
		
		
		for($i=1;$i < ($last+1);$i++){       //; pega todos os dias do mes informado....
			$diasem=date ("D", mktime (0,0,0,$mes,$i,$ano));
			if($this->sem[$diasem] == 1){
				$str.="<tr align=\"center\" >";
				$s="$i";
			}else{
				$s="$i";
			}
			$data_script = "$ano-$mes-$s";
			$str.="<td     ";
			
			if($marca != 0){  // marca o dia atual em laranja
				$str .= "onmouseover=\"js_mostra('parecerr".$i."',event); \" onmouseout=\"js_oculta('parecerr".$i."',event);\" ";
			}

			//liberar fds.
			//if($this->sem[$diasem] == 1 || $this->sem[$diasem] == 7){
			//	$str.="  bgcolor=#CCCCCC ";
			//}
			
			$str .=" width=\"25\" ";
			
			if( ( $ano.str_pad($mes,2,'0',STR_PAD_LEFT).str_pad($i,2,'0',STR_PAD_LEFT) >= date("Ymd") || $objSau_Config->s103_c_cancelafa == 'S' 
			    ) &&
				( isset($arr_diasem) && $this->arr_search( $arr_diasem, $this->sem[$diasem] ) )
			) {
				$booMostradiv     = false;
        
        $iUsuario = db_getsession('DB_id_usuario');
				
        $str_query = "select sd30_i_turno,
				                  sd30_i_fichas,
				                  sd30_i_reservas,
				                  sd30_c_horaini,
				                  sd30_c_horafim,
				                  sd04_i_codigo,
				                  sd101_c_descr,
				                  sd30_i_codigo,
                          sd06_c_horainicio,
						  sau_motivo_ausencia.s139_c_descr as sd06_c_tipo,				                  
				               ( select count(sd23_d_consulta)
				                   from agendamentos
				                   where sd23_d_consulta = '$ano/$mes/$s'
				                    and exists ( select *
		            													from agendaconsultaanula
		            													where s114_i_agendaconsulta = sd23_i_codigo
                                            and s114_i_login = $iUsuario
		            								)
				                    and sd23_i_undmedhor= sd30_i_codigo
				                    
				                  group by sd23_d_consulta
				               )::integer as total_agendado
				            from undmedhorario
				           inner join especmedico    on sd27_i_codigo  = sd30_i_undmed
				           inner join unidademedicos on sd04_i_codigo  = sd27_i_undmed
				           inner join medicos        on sd03_i_codigo  = sd04_i_medico
                   inner join agendamentos on sd23_i_undmedhor = sd30_i_codigo
                   inner join agendaconsultaanula on s114_i_agendaconsulta = sd23_i_codigo and s114_i_login = $iUsuario
				            left join sau_tipoficha  on sau_tipoficha.sd101_i_codigo = undmedhorario.sd30_i_tipoficha
				            left join ausencias      on ausencias.sd06_i_especmed = especmedico.sd27_i_codigo
                                                    and '$ano/$mes/$s' between ausencias.sd06_d_inicio and ausencias.sd06_d_fim				             
				           left join sau_motivo_ausencia on ausencias.sd06_i_tipo = sau_motivo_ausencia.s139_i_codigo
				           where $str_where
				             and sd30_i_diasemana = {$this->sem[$diasem]}
				              and ( sd30_d_valfinal is null or 
							       ( sd30_d_valfinal is not null and sd30_d_valfinal >= '$ano/$mes/$s' ) 
							      )
							  and ( sd30_d_valinicial is null or 
							       ( sd30_d_valinicial is not null and sd30_d_valinicial <= '$ano/$mes/$s' ) 
							      )						      
				             ";
					
          
          $result_undmedhorario = pg_query( $str_query ) or die( "ERRO: <p> $str_query ");
					if( pg_num_rows($result_undmedhorario) > 0 ){
						$obj_undmedhorario  = db_utils::fieldsMemory($result_undmedhorario,0);
					}
				
				if( $booMostradiv == false && pg_num_rows($result_undmedhorario) > 0 ) {
				    //Pega mais de uma hora para o mesmo dia
					$str_msg           = "";
					$int_sd30_i_fichas = 0;
					$int_total_agendado= 0;
					$str_br            = "";
					for( $xHora=0; $xHora < pg_num_rows($result_undmedhorario); $xHora++){
						$obj_undmedhorario  = db_utils::fieldsMemory($result_undmedhorario,$xHora);
						$int_total_agendado+= $obj_undmedhorario->total_agendado;
						$int_sd30_i_fichas += $obj_undmedhorario->sd30_i_fichas + $obj_undmedhorario->sd30_i_reservas;
						$str_msg           .= $str_br."{$obj_undmedhorario->sd101_c_descr} {$obj_undmedhorario->sd30_c_horaini} as {$obj_undmedhorario->sd30_c_horafim} - Saldo: ".($obj_undmedhorario->sd30_i_fichas - $obj_undmedhorario->total_agendado );
						$str_br             = "<br>";
					}
          $lFlagTemAnulado = false;
					$str_cor = " bgcolor='' > ";
					if( $this->arr_search( $arr_diasem, $this->sem[$diasem] ) && $int_sd30_i_fichas > 0 ){
						if($obj_undmedhorario->total_agendado > 0) {
							$str_cor = " bgcolor='#00FF00' > ";  // marcar o dia atual
              $lFlagTemAnulado = true;
						}else{
							$str_cor = " > ";  // marcar o dia atual
						}
					}
					
					$str    .= $str_cor;
          if($lFlagTemAnulado) {
  					$str    .=" <a href=\"\" onclick=\"return janela($s,$mes,$ano,$fechar,".($obj_undmedhorario->sd30_i_fichas - $obj_undmedhorario->total_agendado ).");\"><font size='4'> $s </a> ";
          } else {
            $str .= '<font size=\'4\'> '.$s;
          }
					$booMostradiv = true;
				}
				
				if( $booMostradiv ){
					//*Div
					$str .= "";
			     /*          <div  onmouseover=js_oculta('parecerr$i',event)  name='parecerr$i' id='parecerr$i' style='position:absolute;visibility:hidden;left: 0px;top: 0px'>
			                <table  height='100%' width='100%' border='0' cellspacing='0' cellpadding='0'>
			                 <tr>
			                  <td height='1'>
			                  </td>
			                 </tr>
			                </table>
			                <table align='left' height='100%' width='100%' border='1' cellspacing='1' cellpadding='1'>
			                 <tr>
			                  <td  bgcolor='#f3f3f3'   align='justify'>
			                    <FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>
			                    <center>Data: $s/$mes/$ano  &nbsp;&nbsp;&nbsp; <a title='Fechar' href='' onclick=js_oculta('parecerr$i',event)>F</a> </center> <hr>
			                    $str_msg
			                    </FONT>
			                   </td>
			                 </tr>
			                </table>
			               </div>
			               ";*/
			         	//Fim Div
				}else{
						$str .="><font size='4'> $s";
				}
			}else{
					$str .="><font size='4'> $s";
			}
			
			$str .="</font> </td>";
			
			if($this->sem[$diasem] == 7){
				$str.="</tr>";
			}
		}
		
		$diasem=date ("D", mktime (0,0,0,$mes,$last,$ano));

		if($this->sem[$diasem] != 7){
			$valor=7-$this->sem[$diasem];
			$str=$str.$this->aux($valor)."</tr>";
		}

		$str = "
			       <center>
			  <table border=\"1\"  cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" height=\"100%\">
			  <tr>
			   <td colspan='4'>
			     <table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" height=\"100%\" >
			     <tr align=\"center\">
			       <td width=\"100%\" colspan=\"7\" nowrap>
			        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
			         <a href=\"func_calendariosaudeanulados.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&mes_solicitado=".($mes)."&ano_solicitado=".($ano-1)."&".$str_where."&sd02_c_centralagenda={$this->centralagenda} \"> << </a>
			           	   $ano
				     <a href=\"func_calendariosaudeanulados.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&mes_solicitado=".($mes)."&ano_solicitado=".($ano+1)."&".$str_where."&sd02_c_centralagenda={$this->centralagenda} \"> >> </a>   
			        </font>
			       </td>
			     </tr>
			     <tr align=\"center\">
			       <td width=\"100%\" colspan=\"7\" nowrap>
			        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
			         <a href=\"func_calendariosaudeanulados.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&ano_solicitado=".($ano)."&mes_solicitado=".($mes-1)."&".$str_where."&sd02_c_centralagenda={$this->centralagenda} \"> << </a>
			         ".$this->mes[$mes]."
			         <a href=\"func_calendariosaudeanulados.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&ano_solicitado=".($ano)."&mes_solicitado=".($mes+1)."&".$str_where."&sd02_c_centralagenda={$this->centralagenda} \"> >> </a>
			
				</FONT> 
			       </td>
			     </tr>
			     <tr align=\"center\">
			       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>D </font></td>
			       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>S </font></td>
			       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>T </font></td>
			       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>Q </font></td>
			       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>Q </font></td>
			       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>S </font></td>
			       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>S </font></td>
			       </tr>
			       ".$str."
			     </table>
			     </td>
			     </tr>
			     <tr>
			    ";
			$str .= " <td bgcolor='#00FF00' colspan='4' align='center'><font size=1>Dias que possuem agendamentos anulados</td>	    
			     </tr>
			    </table>
			     ";
		echo $str; 
	} //fim function 
} 

//Inicializa classe
$clcalendario = new calendario; 

$clcalendario->nome_objeto_data = $nome_objeto_data;
$clcalendario->centralagenda    = @$sd02_c_centralagenda!="S"?"N":"S";

$str_centralagenda = "";

if( $clcalendario->centralagenda == "N" ){
	$str_where = "sd27_i_codigo=$sd27_i_codigo";
}else{
	$str_where         = "sd27_i_rhcbo=$sd27_i_rhcbo ";
	$str_centralagenda = "and sd02_c_centralagenda='S'";
}

if (!isset($mes_solicitado)){
  $mes_solicitado = date("n",db_getsession("DB_datausu"));
}elseif( $mes_solicitado > 12 || $mes_solicitado < 1 ){
	$ano_solicitado = $mes_solicitado > 12?($ano_solicitado+1):($ano_solicitado-1);
	$mes_solicitado = $mes_solicitado > 12?1:12;
}
if (!isset($ano_solicitado)){
  $ano_solicitado = date("Y",db_getsession("DB_datausu"));
}
if(isset($shutdown_function)){
  $clcalendario->shutdown_function = $shutdown_function;
}

if( ( isset($sd27_i_rhcbo) && (int)@$sd27_i_rhcbo != 0 ) ||( isset($sd27_i_codigo) && (int)@$sd27_i_codigo != 0 ) ) {
	//Pega total de dias para agendamentos
	$strData  = date("$ano_solicitado")."/";
	$strData .= date("$mes_solicitado")."/";
	//$strData .= date( "t", mktime(0, 0, 0, $mes_solicitado, 1, $ano_solicitado) );
	$strData .= date("d",db_getsession("DB_datausu"));
	
	if( checkdate($mes_solicitado, date("d",db_getsession("DB_datausu")), $ano_solicitado) == false ){
		$strData  = date("$ano_solicitado")."/".date("$mes_solicitado")."/01";
	}
	
	$str_query = "select distinct sd30_i_diasemana
	                from undmedhorario 
	               inner join especmedico    on sd27_i_codigo = sd30_i_undmed  
	               inner join unidademedicos on sd04_i_codigo = sd27_i_undmed
	               inner join unidades       on sd02_i_codigo = sd04_i_unidade 
	               where $str_where
	                 $str_centralagenda 
					 --and ( ( sd30_d_valinicial is null or sd30_d_valfinal is null ) or  
					 --       '$strData' between sd30_d_valinicial and sd30_d_valfinal)	               
	               order by sd30_i_diasemana   
	              ";

	$result     = pg_query($str_query) or die("ERRO: nos hor&aacute;rios do profissional.");
	if( pg_num_rows($result) > 0 ){
		$obj_result = db_utils::fieldsMemory($result,0);
		$fechar     = isset($fechar)?true:0;
		$clcalendario->cria(date("d",db_getsession("DB_datausu")),date("$mes_solicitado"),date("$ano_solicitado"),1,$str_where, $result, $fechar );
	}else{
		echo "Nehuma informa&ccedil;&atilde;o encontrada";
	}
}else{
	echo "N&atilde;o foi informado Profissional."; 
}

?>

<script>

function js_mostra(nomepar,event){
    PosMouseX = event.layerX;
    PosMouseY = event.layerY;
    //alert( PosMouseY );
    
    if( nomepar != undefined && document.getElementById(nomepar) != undefined){
		if( PosMouseY > 80 ){
			document.getElementById(nomepar).style.top = 0;
		}else{
			document.getElementById(nomepar).style.top = 100;
		}
		document.getElementById(nomepar).style.visibility = "visible";
	}
   
  // alert(event.layerY);
}
function js_oculta(nomepar,event){

	if( nomepar != undefined && document.getElementById(nomepar) != undefined ){
		document.getElementById(nomepar).style.visibility = "hidden";
	}
}


function janela(d,m,a,fechar,saldo){

	data = new Date(a,m-1,d);
	dia= data.getDay();
	semana=new Array(6);
	semana[0]='Domingo';
	semana[1]='Segunda-Feira';
	semana[2]='Terça-Feira';
	semana[3]='Quarta-Feira';
	semana[4]='Quinta-Feira';
	semana[5]='Sexta-Feira';
	semana[6]='S&aacute;bado';
	parent.document.form1.diasemana.value = semana[dia];

  if(parent.document.form1.saldo!=undefined){
     parent.document.form1.saldo.value=saldo;
  }       
 
  <?
  echo "parent.document.getElementById('".$nome_objeto_data."_dia').value = (d<10?'0'+d:d);\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_mes').value = (m<10?'0'+m:m);\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').value = a;\n";
  echo "parent.js_comparaDatas".$nome_objeto_data."((d<10?'0'+d:d),(m<10?'0'+m:m),a);\n";
  
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').setfocus;\n";
  
  if (isset($shutdown_function) && ($shutdown_function!='none')){
      echo $shutdown_function."\n";
  }
  ?>
  if( fechar == true ){
  	x = 'parent.iframe_data_<?=$nome_objeto_data?>.hide();\n';
  	eval( x );
  }

  //Trexo anexado Guilherme 01/12/2009 T29243 [tag]
  //Rotina: Agendamento de Consultas Simplificado
  //if(parent.document.form1.saldo!='undefined'){
     //parent.document.form1.saldo.value=saldo;
     //parent.document.getElementById('saldo_div').innerHTML = saldo+' Fichas';
     //parent.document.form1.consultas.disabled=false;
     //parent.js_load_consulta();
     //parent.iframe_data_sd23_d_consulta.hide();
  //}

}


function janela_zera(){ 
  <?
  echo "parent.document.getElementById('".$nome_objeto_data."_dia').value = '';\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_mes').value = '';\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').value = '';\n";
  //echo "parent.iframe_data_".$nome_objeto_data.".hide();\n";

  if (isset($shutdown_function) && ($shutdown_function!='none')){
      echo $shutdown_function."\n";
  }

  ?>
}

</script>