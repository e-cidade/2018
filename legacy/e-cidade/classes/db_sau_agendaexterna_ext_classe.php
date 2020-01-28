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
//CLASSE DA ENTIDADE sau_agendaexterna
require_once 'classes/db_sau_agendaexterna_classe.php';
class cl_sau_agendaexterna_ext extends  cl_sau_agendaexterna { 
   // funcao do sql 
   function sql_query_ext ( $s118_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_agendaexterna ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_agendaexterna.s118_i_login";
     
     $sql .= "      left  join sau_prestadores  on  sau_prestadores.s110_i_codigo = sau_agendaexterna.s118_i_prestador";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = sau_prestadores.s110_i_numcgm";
     
     $sql .= "      inner join cgs      on  cgs.z01_i_numcgs = sau_agendaexterna.s118_i_numcgs";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = cgs.z01_i_numcgs ";
     
     $sql .= "       left join sau_agendaexternaespec on sau_agendaexternaespec.s119_i_agendaexterna = sau_agendaexterna.s118_i_codigo ";
     $sql .= "       left join rhcbo                  on rhcbo.rh70_sequencial                       = sau_agendaexternaespec.s119_i_especialidade ";
     
     $sql .= "       left join sau_agendaexternaexame on sau_agendaexternaexame.s120_i_agendaexterna = sau_agendaexterna.s118_i_codigo ";
     $sql .= "       left join sau_exames             on sau_exames.s108_i_codigo                    = sau_agendaexternaexame.s120_i_exame "; 
     $sql2 = "";
     if($dbwhere==""){
       if($s118_i_codigo!=null ){
         $sql2 .= " where sau_agendaexterna.s118_i_codigo = $s118_i_codigo "; 
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