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
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
include_once("classes/db_sau_upsparalisada_classe.php");
include_once("classes/db_ausencias_classe.php");
include_once("libs/JSON.php");
$osau_paralisacao    = new cl_sau_upsparalisada;
$oausencias           = new cl_ausencias;
$objJson             = new services_json();
$objParam            = $objJson->decode(str_replace("\\","",$_POST["json"]));
$objRetorno          = new stdClass();
$objRetorno->status  = 1;
$objRetorno->message = '';
$departamento = db_getsession("DB_coddepto");

if($objParam->exec == 'consulta'){
	$sql = "select sd23_i_codigo from agendamentos
	                inner join undmedhorario on undmedhorario.sd30_i_codigo=agendamentos.sd23_i_undmedhor
	                inner join especmedico       on especmedico.sd27_i_codigo=undmedhorario.sd30_i_undmed
	             where sd23_i_numcgs=$objParam->cgs
	                   and sd23_d_consulta='$objParam->data'
	                   and sd27_i_codigo=$objParam->medico
	                   and sd23_i_codigo not in (select s114_i_agendaconsulta from agendaconsultaanula where s114_i_agendaconsulta=sd23_i_codigo)";
	$result=pg_query($sql);
    if(pg_num_rows($result)>0){
        $objRetorno->cod_consulta  = pg_result($result,0,0);
    }else{
    	$objRetorno->status  = 2;
    	$objRetorno->message = "Nenhuma consulta encontrada SQL[$sql]";
    }
}
if($objParam->exec == 'medico'){
	$linhas=0;
	if(($objParam->medico!="")&&($objParam->medico!=null)){
	   $sql = "select especmedico.sd27_i_codigo from especmedico 
	              inner join unidademedicos on sd04_i_codigo = especmedico.sd27_i_undmed
	          where sd27_c_situacao='A'
	              and sd04_c_situacao='A'
	              and sd04_i_unidade=$departamento
	              and unidademedicos.sd04_i_medico=$objParam->medico
	          limit 1";
	   $result=pg_query($sql);
       $linhas=pg_num_rows($result);
    }
    if($linhas>0){
        $objRetorno->sd27_i_codigo  = pg_result($result,0,0);
    }else{
    	$objRetorno->status  = 2;
    	$objRetorno->message = "Nenhuma consulta encontrada SQL[$sql]";
    }
}
if($objParam->exec == 'getSaldoconsulta'){
  $vet=explode("/",$objParam->sd23_d_consulta);
  $data=$vet[2]."-".$vet[1]."-".$vet[0];
  
  $sWhere            = " s140_i_unidade = $departamento ";
  $sWhere           .= " and '$data' between s140_d_inicio and s140_d_fim ";
  $sWhere           .= " and ( s140_c_horaini between  sd30_c_horaini and sd30_c_horafim ";
  $sWhere           .= "      or s140_c_horafim between  sd30_c_horaini and sd30_c_horafim ";
  $sWhere           .= "      or sd30_c_horaini between  s140_c_horaini and s140_c_horafim ";
  $sWhere           .= "      or sd30_c_horafim between  s140_c_horaini and s140_c_horafim ) limit 1";
  $sParalizacao      = $osau_paralisacao->sql_query_file("","s140_c_horaini||'|'||s140_c_horafim",null, $sWhere);
  $sWhere            = " sd06_i_especmed = $objParam->sd27_i_codigo ";
  $sWhere           .= " and '$data' between sd06_d_inicio and sd06_d_fim ";
  $sWhere           .= " and ( sd06_c_horainicio between  sd30_c_horaini and sd30_c_horafim ";
  $sWhere           .= "      or sd06_c_horafim between  sd30_c_horaini and sd30_c_horafim ";
  $sWhere           .= "      or sd30_c_horaini between  sd06_c_horainicio and sd06_c_horafim ";
  $sWhere           .= "      or sd30_c_horafim between  sd06_c_horainicio and sd06_c_horafim ) limit 1";
  $sAusencia         = $oausencias->sql_query_file("","sd06_c_horainicio||'|'||sd06_c_horafim",null, $sWhere);
  $sAusenciaPorCodGradeHorario = " and sd30_i_codigo not in (select sd06_i_undmedhorario from ausencias  
                                     inner join undmedhorario on sd06_i_undmedhorario = sd30_i_codigo 
                                       where sd06_i_especmed = $objParam->sd27_i_codigo
                                         and sd30_i_diasemana = $objParam->dia_semana
                                         and '$data' between sd06_d_inicio and sd06_d_fim) ";
  
  $sCodigoGradeHorario = ' and sd30_i_codigo = '.$objParam->sd06_i_undmedhorario; 

    $sql = "select ( select count(sd23_d_consulta)
				                   from agendamentos
				                   where sd23_d_consulta = '$data'
				                    and not exists ( select *
                                                     from agendaconsultaanula
		                                              where s114_i_agendaconsulta = sd23_i_codigo
		                                           )
				                    and sd23_i_undmedhor= sd30_i_codigo
				                    
				                  group by sd23_d_consulta
				               )::integer as agendado,
				               sd30_i_fichas as total,
				               ($sParalizacao) as paralizacao,
				               sd30_c_horaini as inicio,
				               sd30_c_horafim as fim,
				               ($sAusencia) as ausencia
				            from undmedhorario
				           inner join especmedico    on sd27_i_codigo  = sd30_i_undmed
				           inner join unidademedicos on sd04_i_codigo  = sd27_i_undmed
				           inner join medicos        on sd03_i_codigo  = sd04_i_medico
				            left join sau_tipoficha  on sau_tipoficha.sd101_i_codigo = undmedhorario.sd30_i_tipoficha
				            left join ausencias      on ausencias.sd06_i_especmed = especmedico.sd27_i_codigo
                                                    and '$data' between ausencias.sd06_d_inicio and ausencias.sd06_d_fim				             
				           where sd27_i_codigo= $objParam->sd27_i_codigo
				             and sd30_i_diasemana = $objParam->dia_semana
				              and ( sd30_d_valfinal is null or 
							       ( sd30_d_valfinal is not null and sd30_d_valfinal >= '$data' ) 
							      )
							  and ( sd30_d_valinicial is null or 
							       ( sd30_d_valinicial is not null and sd30_d_valinicial <= '$data' ) 
							      ) $sAusenciaPorCodGradeHorario $sCodigoGradeHorario
				             ";
	$result=pg_query($sql);
	db_fieldsmemory($result,0);
	$objRetorno->saldo=$total;
	$paralizado = 0;

    for($iX=0; $iX<2; $iX++){

	  if ($paralizacao != null){
      
        $aparalizado       = explode("|",$paralizacao);
        $aparalizadoini    = explode(":",$aparalizado[0]);
        $sParalizadoIniMin = ((int)$aparalizadoini[0]*60)+(int)$aparalizadoini[1];
        $aparalizadofim    = explode(":",$aparalizado[1]);
        $iParalizadoFimMin = ((int)$aparalizadofim[0]*60)+(int)$aparalizadofim[1];
        $iDifParalisado    = $iParalizadoFimMin-$sParalizadoIniMin;
        $aini              = explode(":",$inicio);
        $iIniMin           = ((int)$aini[0]*60)+(int)$aini[1];
        $afim              = explode(":",$fim);
        $iFimMin           = ((int)$afim[0]*60)+(int)$afim[1];
        $iDifhorario       = $iFimMin-$iIniMin;
        $paralizado       += ($total/$iDifhorario)*$iDifParalisado;
      
      }
      $paralizacao = $ausencia;
    }
    $paralizado = ceil($paralizado);
    if ($agendado != null) { 
      $iSaldo = $total-($agendado+$paralizado); 
    }else{
      $iSaldo = $total-$paralizado;
    }
    if ($iSaldo < 0) {
      $iSaldo = 0;
    }
    $objRetorno->saldo = $iSaldo;
}

if($objParam->exec == 'getProcedimento'){
    $sql = "select *
              from ( select distinct sau_proccbo.sd96_i_procedimento,
                                     sau_procedimento.sd63_c_procedimento,  
                                     sau_procedimento.sd63_c_nome
                       from sau_proccbo
                         inner join rhcbo on rhcbo.rh70_sequencial = sau_proccbo.sd96_i_cbo
                         inner join sau_procedimento on sau_procedimento.sd63_i_codigo = sau_proccbo.sd96_i_procedimento
                         left join sau_procmodalidade on sau_procmodalidade.sd83_i_procedimento = sau_procedimento.sd63_i_codigo
                         left join sau_modalidade on sau_modalidade.sd82_i_codigo = sau_procmodalidade.sd83_i_modalidade
                         left join sau_financiamento on sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento
                         left join sau_rubrica on sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica
                         left join sau_complexidade on sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade
                           where sd63_c_procedimento = '$objParam->sd63_c_procedimento' and sd96_i_cbo = $objParam->rh70_sequencial
                             limit 1 ) as xx";

	$result = pg_query($sql);

  if(pg_num_rows($result) < 1) {

    $objRetorno->status = 2;
    $objRetorno->message = 'Nenhum resultado encontrado';
    //echo "SQL = $sql";

  } else {

    db_fieldsmemory($result,0);
	  $objRetorno->sd96_i_procedimento = $sd96_i_procedimento;
    $objRetorno->sd63_c_procedimento = $sd63_c_procedimento;
    $objRetorno->sd63_c_nome = $sd63_c_nome;

  }

}

echo $objJson->encode($objRetorno);
?>