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

//MODULO: saude
//CLASSE DA ENTIDADE cgs_und
require("db_cgs_und_classe.php");

class cl_cgs_und_ext extends cl_cgs_und  { 
   function sql_query_ext ( $z01_i_cgsund=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
      }
     }else{
       $sql .= $campos;// = "cgs_und.*,cgs.*,familiamicroarea.*,familia.*,microarea.*,cgs_cartaosus_p.s115_c_cartaosus as provisorio,cgs_cartaosus_d.s115_c_cartaosus as definitivo,cgs_cartaosus_p.s115_i_codigo as codigo_provisorio,cgs_cartaosus_d.s115_i_codigo as codigo_definitivo";
     }
     $sql .= " from cgs_und ";
     $sql .= "  left join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= " inner join cgs               on  cgs.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql .= "  left join familia           on  familia.sd33_i_codigo = familiamicroarea.sd35_i_familia";
     $sql .= "  left join microarea         on  microarea.sd34_i_codigo = familiamicroarea.sd35_i_microarea";
     $sql .= "  left join cgs_cartaosus     on  s115_i_cgs = cgs_und.z01_i_cgsund ";
     //$sql .= "  left join cgs_cartaosus as cgs_cartaosus_p     on  cgs_cartaosus_p.s115_i_cgs = cgs_und.z01_i_cgsund and cgs_cartaosus_p.s115_c_tipo='P' ";
     //$sql .= "  left join cgs_cartaosus as cgs_cartaosus_d     on  cgs_cartaosus_d.s115_i_cgs = cgs_und.z01_i_cgsund and cgs_cartaosus_d.s115_c_tipo='D' ";
     $sql2 = "";
     if($dbwhere==""){
       if($z01_i_cgsund!=null ){
         $sql2 .= " where cgs_und.z01_i_cgsund = $z01_i_cgsund "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by s115_c_tipo ";
       $campos_sql = split("#",$ordem);
       $virgula = ", ";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         //$virgula = ",";
       }
     }
     return $sql;
  }
}
?>