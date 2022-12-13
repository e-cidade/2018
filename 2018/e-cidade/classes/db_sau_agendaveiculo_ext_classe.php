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
//CLASSE DA ENTIDADE sau_agendaexames
require('classes/db_sau_agendaveiculo_classe.php');
class cl_sau_agendaveiculo_ext extends cl_sau_agendaveiculo  { 
   // funcao do sql 
  // funcao do sql 
   function sql_query_ext ( $s121_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_agendaveiculo ";
     $sql .= "	inner join sau_agendaveiculocgs  on  sau_agendaveiculocgs.s122_i_agendaveiculo = sau_agendaveiculo.s121_i_codigo";
     $sql .= "	inner join sau_agendaexterna on sau_agendaexterna.s118_i_codigo = sau_agendaveiculocgs.s122_i_agendaexterna";
     $sql .= "  inner join cgs_und on cgs_und.z01_i_cgsund = sau_agendaexterna.s118_i_numcgs";
     $sql .= "  inner join veicmotoristas on veicmotoristas.ve05_codigo = sau_agendaveiculo.s121_i_motorista";     
     $sql2 = "";
     if($dbwhere==""){
       if($s121_i_codigo!=null ){
         $sql2 .= " where sau_agendaveiculo.s121_i_codigo = $s121_i_codigo "; 
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