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

//MODULO: saude
//CLASSE DA ENTIDADE sau_lotepront
require_once("classes/db_sau_lotepront_classe.php"); 
class cl_sau_lotepront_ext extends cl_sau_lotepront  { 
   // funcao do sql 
   function sql_query_ext ( $sd59_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_lotepront ";
     $sql .= "      inner join sau_lote  on  sau_lote.sd58_i_codigo = sau_lotepront.sd59_i_lote";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = sau_lotepront.sd59_i_prontuario";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_lote.sd58_i_login";
     $sql .= "       left join db_usuarios  as a on   a.id_usuario = prontuarios.sd24_i_login";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = cgs.z01_i_numcgs";
     $sql .= "       left join prontcid on  prontcid.sd55_i_prontuario = prontuarios.sd24_i_codigo";
     $sql .= "       left join sau_cid  on  sau_cid.sd70_i_codigo = prontcid.sd55_i_cid";
     $sql2 = "";
     if($dbwhere==""){
       if($sd59_i_codigo!=null ){
         $sql2 .= " where sau_lotepront.sd59_i_codigo = $sd59_i_codigo "; 
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