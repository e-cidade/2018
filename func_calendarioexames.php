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
include("libs/db_utils.php");

include("classes/db_sau_prestadorhorarios_classe.php");

include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']); // ta com o globals desativado no php -- Crestani

class calendario{ 
   var $sem;//Array com os dias da semana como índice 
   var $mes;//Array com os meses do ano 
   var $nome_objeto_data;
   var $shutdown_function = "";

   function inicializa(){//Atribui valores para $sem e $mes.
       $this->sem=array('Sun'=>1,'Mon'=>2,'Tue'=>3,'Wed'=>4,'Thu'=>5,'Fri'=>6,'Sat'=>7);
       $this->mes=array('1'=>'JANEIRO','2'=>'FEVEREIRO','3'=>'MARÇO','4'=>'ABRIL','5'=>'MAIO','6'=>'JUNHO','7'=>'JULHO','8'=>'AGOSTO','9'=>'SETEMBRO','10'=>'OUTUBRO','11'=>'NOVEMBRO','12'=>'DEZEMBRO');
       //$this->clsau_prestadorhorarios  = new cl_sau_prestadorhorarios();
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
   
	function cria($dia,$mes,$ano,$marca=0,$s111_i_codigo, $fechar=false){
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
		$strSql = cl_sau_prestadorhorarios::sql_query(null,"s112_i_diasemana","s112_i_diasemana, s112_c_horaini",
				"  s112_i_prestadorvinc = $s111_i_codigo
				 and ( ( s112_d_valinicial is null or s112_d_valfinal is null ) 
				  or  '".date("$ano")."/".date("$mes")."/".date("d",db_getsession("DB_datausu"))."' between s112_d_valinicial and s112_d_valfinal) "
		);
		$result_unidademedico = pg_query( $strSql ) or die( "<p> $strSql<p>".pg_errormessage() );
	               
		for( $x=0; $x < pg_num_rows( $result_unidademedico ); $x++){
			$obj_result = db_utils::fieldsMemory($result_unidademedico,$x);
			$arr_diasem[] = $obj_result->s112_i_diasemana;
		}
		
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

			if($this->sem[$diasem] == 1 || $this->sem[$diasem] == 7){
				//$str.="  bgcolor=#CCCCCC ";
			}
			
			$str .=" width=\"25\" ";
			
			if( ( $ano.str_pad($mes,2,'0',STR_PAD_LEFT).str_pad($i,2,'0',STR_PAD_LEFT) >= date("Ymd") ) &&
				( isset($arr_diasem) && $this->arr_search( $arr_diasem, $this->sem[$diasem] ) )
			) {
				$strSql = cl_sau_prestadorhorarios::sql_query(
												null,
												"s112_c_horaini,
												s112_c_horafim,
												s112_i_fichas,
												s112_i_reservas,
												s111_i_codigo,
												sd101_c_descr,
												( select count(s113_d_exame)
													from sau_agendaexames 
												   where s113_d_exame = '$ano/$mes/$s'
												     and s113_i_situacao = 1
												     and s113_i_prestadorhorarios = s112_i_codigo
												 ):: integer as total_agendado 
												",
												null,
												"   s112_i_prestadorvinc = $s111_i_codigo
												and s112_i_diasemana = {$this->sem[$diasem]}
												and ( s112_d_valfinal is null or
												     ( s112_d_valfinal is not null and s112_d_valfinal > '$ano/$mes/$s' ) 
							      					) 
												"
											);
             	
				$result_undmedhorario = pg_query( $strSql ) or die( "ERRO: <p> $strSql <p>".pg_errormessage() );
				/*
				$str_query = "select *, case when sd06_i_tipo = 1 then
                                        'Folga'
                                     else
                                        'Férias'
                                     end as sd06_c_tipo
           					from ausencias
							where sd06_i_especmed = $s111_i_codigo
            				  and '$ano/$mes/$s' between sd06_d_inicio and sd06_d_fim";
            				  
				$result_ausencias = pg_query( $str_query );
				$booMostradiv     = false;
				if( pg_num_rows($result_ausencias) > 0 ){
					$obj_ausencias    = db_utils::fieldsMemory($result_ausencias,0);
					$str .= " bgcolor=white > ";  // marcar o dia atual
					//$str .="<font size='4'> $s";
					$str .=" <a href=\"\" onclick=\"return janela($s,$mes,$ano,$fechar);\"><font size='4'> $s</a> ";
					$str_msg = "Profissional não esta atendendo.<br> Situação: {$obj_ausencias->sd06_c_tipo} ";
					$booMostradiv = true;
				}else 
				*/
				if( pg_num_rows($result_undmedhorario) != 0 ) {
					//Pega mais de uma hora para o mesmo dia
					$str_msg           = "";
					$int_sd112_i_fichas = 0;
					$int_total_agendado= 0;
					$str_br            = "";
					for( $xHora=0; $xHora < pg_num_rows($result_undmedhorario); $xHora++){
						$obj_undmedhorario   = db_utils::fieldsMemory($result_undmedhorario,$xHora);
						$int_total_agendado += $obj_undmedhorario->total_agendado;
						$int_sd112_i_fichas += $obj_undmedhorario->s112_i_fichas;
						$str_msg            .= $str_br."{$obj_undmedhorario->sd101_c_descr} {$obj_undmedhorario->s112_c_horaini} as {$obj_undmedhorario->s112_c_horafim} - Saldo: ".($obj_undmedhorario->s112_i_fichas - $obj_undmedhorario->total_agendado );
						$str_br              = "<br>";
					}
					if( $this->arr_search( $arr_diasem, $this->sem[$diasem] ) ){
						if( ( $int_sd112_i_fichas - $int_total_agendado ) == 0 ){
							$str_cor = " bgcolor=red > ";  // marcar o dia atual
						}else if( $int_total_agendado > 0 ){
							$str_cor = " bgcolor=yellow > ";  // marcar o dia atual
						}else{
							$str_cor = " bgcolor=#00FF00 > ";  // marcar o dia atual
						}
					}
					
					$str    .= $str_cor;
					$str    .=" <a href=\"\" onclick=\"return janela($s,$mes,$ano,$fechar);\"><font size='4'> $s</a> ";
					$booMostradiv = true;
				}
				
				if( $booMostradiv ){
					//*Div
					$str .= "
			               <div  onmouseover=js_oculta('parecerr$i',event)  name='parecerr$i' id='parecerr$i' style='position:absolute;visibility:hidden;left: 0px;top: 0px'>
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
			               ";
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
		}//fim for
		
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
			           <a href=\"func_calendarioexames.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&mes_solicitado=".($mes)."&ano_solicitado=".($ano-1)."&s111_i_codigo=".@$s111_i_codigo." \"> << </a>
			           	   $ano
				         <a href=\"func_calendarioexames.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&mes_solicitado=".($mes)."&ano_solicitado=".($ano+1)."&s111_i_codigo=".@$s111_i_codigo." \"> >> </a>   
			        </font>
			       </td>
			     </tr>
			     <tr align=\"center\">
			       <td width=\"100%\" colspan=\"7\" nowrap>
			        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
			         <a href=\"func_calendarioexames.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&ano_solicitado=".($ano)."&mes_solicitado=".($mes-1)."&s111_i_codigo=".@$s111_i_codigo." \"> << </a>
			         ".$this->mes[$mes]."
			         <a href=\"func_calendarioexames.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&ano_solicitado=".($ano)."&mes_solicitado=".($mes+1)."&s111_i_codigo=".@$s111_i_codigo." \"> >> </a>
			
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
			       <td bgcolor='#00FF00'><font size=1>Liberado</td>
			       <td bgcolor='yellow'><font size=1>Marcado</td>
			       <td bgcolor='red'><font size=1>Lotado</td>			       			       			       
			     </tr>
			    </table>
			    
			    
			     ";
		echo $str; 
	} //fim function 
} 


$clcalendario=new calendario; 
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




$clcalendario->nome_objeto_data = $nome_objeto_data;

if( isset($s111_i_codigo) && (int)@$s111_i_codigo != 0 ){
	$fechar = isset($fechar)?true:0;
	$clcalendario->cria(date("d",db_getsession("DB_datausu")),
						date("$mes_solicitado"),
						date("$ano_solicitado"),
						1,
						$s111_i_codigo, 
						$fechar 
					);
}else{
	echo "Não foi informado Profissional."; 
}

?>

<script>

function js_mostra(nomepar,event){
    PosMouseX = event.layerX;
    PosMouseY = event.layerY;
    //alert( PosMouseY );
	if( PosMouseY > 80 ){
		document.getElementById(nomepar).style.top = 0;
	}else{
		document.getElementById(nomepar).style.top = 100;
	}
	document.getElementById(nomepar).style.visibility = "visible";
   
  // alert(event.layerY);
}
function js_oculta(nomepar,event){
   document.getElementById(nomepar).style.visibility = "hidden";
}


function janela(d,m,a,fechar){
	data = new Date(a,m-1,d);
	dia= data.getDay();
	semana=new Array(6);
	semana[0]='Domingo';
	semana[1]='Segunda-Feira';
	semana[2]='Terça-Feira';
	semana[3]='Quarta-Feira';
	semana[4]='Quinta-Feira';
	semana[5]='Sexta-Feira';
	semana[6]='Sábado';
	parent.document.form1.diasemana.value = semana[dia];

 
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