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
//CLASSE DA ENTIDADE prontagendamento
require_once("classes/db_prontagendamento_classe.php");
class cl_prontagendamento_ext extends cl_prontagendamento  { 
   // funcao do sql 
   function sql_query_ext ( $s102_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prontagendamento ";
     $sql .= "      inner join agendamentos  on  agendamentos.sd23_i_codigo = prontagendamento.s102_i_agendamento";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = prontagendamento.s102_i_prontuario";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = agendamentos.sd23_i_usuario";
     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = agendamentos.sd23_i_numcgs";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = prontuarios.sd24_i_login";
     $sql .= "      left  join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
     //$sql .= "      left  join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
     $sql .= "      left  join cgs  as b on   b.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql .= "      left join agendaconsultaanula on agendaconsultaanula.s114_i_agendaconsulta = agendamentos.sd23_i_codigo";
     
     $sql .= "      inner join undmedhorario  on undmedhorario.sd30_i_codigo = agendamentos.sd23_i_undmedhor";
     $sql .= "      inner join especmedico    on especmedico.sd27_i_codigo = undmedhorario.sd30_i_undmed ";
     $sql .= "      inner join rhcbo          on rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo ";      
     $sql .= "      inner join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed ";      
     $sql .= "      inner join medicos        on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico ";
     $sql .= "      inner join cgm            on cgm.z01_numcgm = medicos.sd03_i_cgm ";         
     
     $sql2 = ' where agendaconsultaanula.s114_i_codigo is null ';

     if($dbwhere==""){
       if($s102_i_codigo!=null ){
         $sql2 .= " and prontagendamento.s102_i_codigo = $s102_i_codigo ";
       } 
     }else if($dbwhere != ""){
       $sql2 .= " and $dbwhere";
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