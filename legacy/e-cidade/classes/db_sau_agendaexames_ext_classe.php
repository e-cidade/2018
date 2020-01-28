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
require('classes/db_sau_agendaexames_classe.php');
class cl_sau_agendaexames_ext extends cl_sau_agendaexames  { 
   // funcao do sql 
   function sql_query_ext ( $s113_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_agendaexames ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_agendaexames.s113_i_login";
     $sql .= "      inner join sau_prestadorhorarios  on  sau_prestadorhorarios.s112_i_codigo = sau_agendaexames.s113_i_prestadorhorarios";

     
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = sau_agendaexames.s113_i_numcgs";
     $sql .= "      inner join sau_tipoficha  on  sau_tipoficha.sd101_i_codigo = sau_prestadorhorarios.s112_i_tipoficha";
     $sql .= "      inner join sau_prestadorvinculos  on  sau_prestadorvinculos.s111_i_codigo = sau_prestadorhorarios.s112_i_prestadorvinc";

     $sql .= "      INNER JOIN sau_procedimento ON sau_procedimento.sd63_i_codigo = sau_prestadorvinculos.s111_procedimento";
     $sql .= "      inner join sau_prestadores  on  sau_prestadores.s110_i_codigo = sau_prestadorvinculos.s111_i_prestador ";
     $sql .= "      inner join cgm as prestador on  prestador.z01_numcgm = sau_prestadores.s110_i_numcgm ";
     
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = sau_prestadorhorarios.s112_i_diasemana";
     $sql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      inner join cgs  as a on   a.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($s113_i_codigo!=null ){
         $sql2 .= " where sau_agendaexames.s113_i_codigo = $s113_i_codigo "; 
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