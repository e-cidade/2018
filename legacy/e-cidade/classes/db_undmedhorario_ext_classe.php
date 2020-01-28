<?
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
//CLASSE DA ENTIDADE undmedhorario
require_once("classes/db_undmedhorario_classe.php");
class cl_undmedhorario_ext extends cl_undmedhorario{
	/**
	 * Monta select dos profissionais que estão atendendo numa unidade
	 *
	 * @param unknown_type $ano - Ano
	 * @param unknown_type $mes - Mês
	 * @param unknown_type $dia - Dia
	 * @param unknown_type $str_where - sd27_i_codigo = $sd27_i_codigo
	 *                               ou sd27_i_rhcbo = $sd27_i_rhcbo and sd02_c_centralagenda = 'S'
	 * @param unknown_type $chave_diasemana - Dia da semana
	 * @return unknown - variável str_query com select montado
	 */
	function sql_calendario2($ano,$mes,$dia, $str_where, $chave_diasemana,$centralagenda){
		$str_query =  cl_undmedhorario_ext::sql_query_ext( null,
								" sd02_i_codigo, 
								descrdepto,
								sd27_i_codigo,
								sd03_i_codigo,
								z01_nome,
								sd30_i_fichas,
				                  sd30_i_reservas,
				                  sd30_c_horaini,
				                  sd30_c_horafim,
				                  sd04_i_codigo,
				                  sd101_c_descr,
				                  sd30_i_codigo,
				               ( select count(sd23_d_consulta)
				                   from agendamentos
				                   where sd23_d_consulta = '$ano/$mes/$dia'
				                    and not exists ( select *
		            									from agendaconsultaanula
		            									where s114_i_agendaconsulta = sd23_i_codigo
		            								)
				                    and sd23_i_undmedhor= sd30_i_codigo				                    
				                  group by sd23_d_consulta
				               )::integer as total_agendado",
				               "sd02_i_codigo, z01_nome, sd30_c_horaini",
				               " $str_where
				               $centralagenda
				               and sd30_i_diasemana = $chave_diasemana
				               and ( sd30_d_valfinal is null or 
							          ( sd30_d_valfinal is not null and sd30_d_valfinal > '$ano/$mes/$dia' ) 
							       )
							  and ( sd30_d_valinicial is null or 
							       ( sd30_d_valinicial is not null and sd30_d_valinicial <= '$ano/$mes/$dia' ) 
							      )						      
							       
							   and not exists ( select *
							   					from ausencias 
							   					where sd06_i_especmed = sd27_i_codigo
							   					 and '$ano/$mes/$dia' between sd06_d_inicio and sd06_d_fim
							   					) 
							   "    				
							);
		
		return $str_query;
	}
   // funcao do sql 
   function sql_query_ext ( $sd30_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from undmedhorario ";
     $sql .= "      inner join sau_tipoficha   on  sau_tipoficha.sd101_i_codigo = undmedhorario.sd30_i_tipoficha";
     $sql .= "      inner join especmedico     on  especmedico.sd27_i_codigo = undmedhorario.sd30_i_undmed";
     $sql .= "      inner join diasemana       on  diasemana.ed32_i_codigo = undmedhorario.sd30_i_diasemana";
     $sql .= "      inner join rhcbo           on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";
     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql .= "      inner join medicos         on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      inner join cgm             on cgm.z01_numcgm = medicos.sd03_i_cgm ";
     $sql .= "      inner join unidades        on  unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade";
     $sql .= "      inner join db_depart       on  db_depart.coddepto = unidades.sd02_i_codigo";
     
     $sql .= "       left join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = unidades.sd02_i_cod_turnat";
     
     $sql2 = "";
     if($dbwhere==""){
       if($sd30_i_codigo!=null ){
         $sql2 .= " where undmedhorario.sd30_i_codigo = $sd30_i_codigo "; 
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

}
?>