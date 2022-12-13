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
//CLASSE DA ENTIDADE ausencias
require_once("classes/db_ausencias_classe.php");
class cl_ausencias_ext extends cl_ausencias  { 
   // funcao do sql 
   function sql_query_ext ( $sd06_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ausencias ";
     $sql .= "      inner join especmedico  on  especmedico.sd27_i_codigo = ausencias.sd06_i_especmed";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";
     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade ";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      inner join cgm m on  m.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "      inner join sau_motivo_ausencia  on  sau_motivo_ausencia.s139_i_codigo = ausencias.sd06_i_tipo";
     
     //$sql .= " from ausencias ";
     //$sql .= "      inner join unidades  on  unidades.sd02_i_codigo = ausencias.sd06_i_unidade";
     //$sql .= "      inner join medicos  on  medicos.sd03_i_codigo = ausencias.sd06_i_medico";
     //$sql .= "      inner join cgm  on  cgm.z01_numcgm = unidades.sd02_i_numcgm and  cgm.z01_numcgm = unidades.sd02_i_diretor";
     //$sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     //$sql .= "      inner join cgm  as a on   a.z01_numcgm = medicos.sd03_i_cgm";
     $sql2 = "";
     if($dbwhere==""){
       if($sd06_i_codigo!=null ){
         $sql2 .= " where ausencias.sd06_i_codigo = $sd06_i_codigo "; 
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