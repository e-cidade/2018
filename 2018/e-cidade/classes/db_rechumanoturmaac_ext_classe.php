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
//CLASSE DA ENTIDADE rechumano
require_once("classes/db_rechumano_classe.php");

class cl_rechumanoturmaac_ext extends cl_rechumano  { 
function sql_query_ext ( $ed20_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $instit = db_getsession("DB_instit");
     $ano = db_anofolha();
     $mes = db_mesfolha();
     $sql .= " from rechumano ";
     $sql .= "      inner join pais                          on pais.ed228_i_codigo                    = rechumano.ed20_i_pais";
     $sql .= "      left  join censouf as censoufident       on censoufident.ed260_i_codigo            = rechumano.ed20_i_censoufident";
     $sql .= "      left  join censouf as censoufnat         on censoufnat.ed260_i_codigo              = rechumano.ed20_i_censoufnat";
     $sql .= "      left  join censouf as censoufcert        on censoufcert.ed260_i_codigo             = rechumano.ed20_i_censoufcert";
     $sql .= "      left  join censouf as censoufender       on censoufender.ed260_i_codigo            = rechumano.ed20_i_censoufender";
     $sql .= "      left  join censomunic as censomunicnat   on censomunicnat.ed261_i_codigo           = rechumano.ed20_i_censomunicnat";
     $sql .= "      left  join censomunic as censomunicender on censomunicender.ed261_i_codigo         = rechumano.ed20_i_censomunicender";
     $sql .= "      left  join censoorgemissrg               on censoorgemissrg.ed132_i_codigo         = rechumano.ed20_i_censoorgemiss";
     $sql .= "      left  join rechumanopessoal              on rechumanopessoal.ed284_i_rechumano     = rechumano.ed20_i_codigo";
     $sql .= "      left  join rhpessoal                     on rhpessoal.rh01_regist                  = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left  join cgm as cgmrh                  on cgmrh.z01_numcgm                       = rhpessoal.rh01_numcgm";
     $sql .= "      left  join db_config                     on db_config.codigo                       = rhpessoal.rh01_instit";
     $sql .= "      left  join rhpessoalmov                  on rh02_anousu                            = $ano
                                                            and rh02_mesusu                            = $mes
                                                            and rh02_regist                            = rh01_regist
                                                            and rh02_instit                            = $instit";
     $sql .= "      left  join rhlota                       on  rhlota.r70_codigo                      = rhpessoal.rh01_lotac";
     $sql .= "      left  join rhpesdoc                     on  rhpesdoc.rh16_regist                   = rhpessoal.rh01_regist";
     $sql .= "      left  join rhestcivil                   on  rhestcivil.rh08_estciv                 = rhpessoal.rh01_estciv";
     $sql .= "      left  join rhraca                       on  rhraca.rh18_raca                       = rhpessoal.rh01_raca";
     $sql .= "      left  join rhfuncao                     on  rhfuncao.rh37_funcao                   = rhpessoal.rh01_funcao and rh37_instit  = rh02_instit";
     $sql .= "      left  join rhinstrucao                  on  rhinstrucao.rh21_instru                = rhpessoal.rh01_instru";
     $sql .= "      left  join rhnacionalidade              on  rhnacionalidade.rh06_nacionalidade     = rhpessoal.rh01_nacion";
     $sql .= "      left  join rechumanocgm                 on  rechumanocgm.ed285_i_rechumano         = rechumano.ed20_i_codigo";
     $sql .= "      left  join cgm as cgmcgm                on  cgmcgm.z01_numcgm                      = rechumanocgm.ed285_i_cgm";
     $sql .= "      inner join rechumanoescola              on  rechumanoescola.ed75_i_rechumano       = rechumano.ed20_i_codigo";
     $sql .= "      inner join escola                       on  escola.ed18_i_codigo                   = rechumanoescola.ed75_i_escola";
     $sql .= "      left  join relacaotrabalho              on  relacaotrabalho.ed23_i_rechumanoescola = rechumanoescola.ed75_i_codigo";
     $sql .= "      left  join rechumanoativ                on  rechumanoativ.ed22_i_rechumanoescola   = rechumanoescola.ed75_i_codigo";
     $sql .= "      inner join atividaderh                  on  atividaderh.ed01_i_codigo              = rechumanoativ.ed22_i_atividade";
     $sql .= "      left  join disciplina                   on  disciplina.ed12_i_codigo               = relacaotrabalho.ed23_i_disciplina";
     $sql .= "      left  join caddisciplina                on ed232_i_codigo                          = ed12_i_caddisciplina";
     $sql .= "      left  join ensino                       on  ensino.ed10_i_codigo                   = disciplina.ed12_i_ensino";
     $sql .= "      inner join rechumanoescola              on  rechumanoescola.ed75_i_rechumano       = rechumano.ed20_i_codigo";
     $sql .= "      inner join rechumanohoradisp            on  rechumanohoradisp.ed33_rechumanoescola = rechumanoescola.ed75_i_codigo";
     $sql .= "      inner join periodoescola                on  periodoescola.ed17_i_codigo            = rechumanohoradisp.ed33_i_periodo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed20_i_codigo!=null ){
         $sql2 .= " where rechumano.ed20_i_codigo = $ed20_i_codigo ";
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