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
//CLASSE DA ENTIDADE sau_proccbo
require_once("classes/db_sau_proccbo_classe.php");
class cl_sau_proccbo_ext extends cl_sau_proccbo  { 
   function sql_query_ext ( $sd96_i_codigo=null,$campos="*",$ordem=null,$dbwhere="", $intUnidade, $lFiltraServico = true){
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
     $sql .= " from sau_proccbo ";
     $sql .= "      inner join rhcbo              on  rhcbo.rh70_sequencial = sau_proccbo.sd96_i_cbo";
     $sql .= "      inner join sau_procedimento   on  sau_procedimento.sd63_i_codigo = sau_proccbo.sd96_i_procedimento";
     $sql .= "       left join sau_procmodalidade on  sau_procmodalidade.sd83_i_procedimento = sau_procedimento.sd63_i_codigo";
     $sql .= "       left join sau_modalidade     on  sau_modalidade.sd82_i_codigo = sau_procmodalidade.sd83_i_modalidade";
     $sql .= "       left join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento";
     $sql .= "       left join sau_rubrica        on  sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica";
     $sql .= "       left join sau_complexidade   on  sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd96_i_codigo!=null ){
         $sql2 .= " where sau_proccbo.sd96_i_codigo = $sd96_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     
     //Sau_Config
     $clsau_config  = db_utils::getDao("sau_config_ext");
     $resSau_config = $clsau_config->sql_record($clsau_config->sql_query_ext());
     $objSau_config = db_utils::fieldsMemory($resSau_config,0 );
     if( $objSau_config->s103_i_modalidade > 0 ){
         $sql2 .= " and sd82_c_modalidade = '{$objSau_config->sd82_c_modalidade}'";
	     //sau_atualiza
	     $clsau_atualiza  = db_utils::getDao("sau_atualiza");
	     $resSau_atualiza = $clsau_atualiza->sql_record($clsau_atualiza->sql_query(null, "*", "s100_i_codigo desc limit 1"));
	     $objSau_atualiza = db_utils::fieldsMemory($resSau_atualiza,0);
	      
	     $sql2 .= " and sd96_i_anocomp    = {$objSau_atualiza->s100_i_anocomp}"; 
	     $sql2 .= " and sd96_i_mescomp    = {$objSau_atualiza->s100_i_mescomp}"; 
     }
     if( $objSau_config->s103_c_servicoproc == 'S' && $lFiltraServico){
     	$intUnidade = (int)$intUnidade==0?DB_getsession("DB_coddepto"):$intUnidade; 
     	$sql2 .= "and sau_procedimento.sd63_i_codigo in ( ";     	
     	$sql2 .="select sd88_i_procedimento
                 from sau_procservico
                 inner join (select (select sd87_i_codigo
                                     from sau_servclassificacao
                                     where sd87_c_classificacao = x.sd87_c_classificacao
                                     and sd87_i_servico in (select sd86_i_codigo
                                                            from sau_servico
                                                            where sd86_c_servico = x.sd86_c_servico
                                                            order by  sd86_i_anocomp desc ,
                                                                      sd86_i_mescomp desc
                                                            limit 1
                                                           )
                                     ) as sd87_i_codigo
                            from (select sd87_c_classificacao,
                                          sd86_c_servico
                                   from unidadeservicos
                                   inner join sau_servclassificacao on sau_servclassificacao.sd87_i_codigo = unidadeservicos.s126_i_servico
                                   inner join sau_servico           on sau_servico.sd86_i_codigo = sau_servclassificacao.sd87_i_servico
                                   where unidadeservicos.s126_i_unidade = $intUnidade
                                  ) as x
                            ) as y on sau_procservico.sd88_i_classificacao = y.sd87_i_codigo)";
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