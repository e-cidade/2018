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
//CLASSE DA ENTIDADE sau_agendatransporte
require_once 'classes/db_sau_agendatransporte_classe.php';
class cl_sau_agendatransporte_ext extends cl_sau_agendatransporte  { 
   // funcao do sql 
   function sql_query_ext ( $s124_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_agendatransporte ";
     $sql .= "      inner join cgs           on  cgs.z01_i_numcgs = sau_agendatransporte.s124_i_numcgs";
     $sql .= "      inner join cgs_und       on  cgs_und.z01_i_cgsund = cgs.z01_i_numcgs ";
     
     $sql .= "       left join sau_agendaveiculo on sau_agendaveiculo.s121_i_agendatransporte = sau_agendatransporte.s124_i_codigo ";
     $sql .= "       left join veiculos          on veiculos.ve01_codigo = sau_agendaveiculo.s121_i_veiculo ";
     $sql .= "       left join veiccadmodelo     on veiccadmodelo.ve22_codigo = veiculos.ve01_veiccadmodelo ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($s124_i_codigo!=null ){
         $sql2 .= " where sau_agendatransporte.s124_i_codigo = $s124_i_codigo "; 
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