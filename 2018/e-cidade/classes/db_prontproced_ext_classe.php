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
//CLASSE DA ENTIDADE prontproced
require_once(modification("classes/db_prontproced_classe.php"));
class cl_prontproced_ext extends cl_prontproced {
   function sql_query_ext ( $sd29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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

     $sql = <<<SQL
  select {$campos}
    from prontproced
         inner join prontuarios        on prontuarios.sd24_i_codigo         = prontproced.sd29_i_prontuario
         left  join especmedico        on especmedico.sd27_i_codigo         = prontproced.sd29_i_profissional
         left  join unidademedicos     on unidademedicos.sd04_i_codigo      = especmedico.sd27_i_undmed
         left  join unidades           on unidades.sd02_i_codigo            = unidademedicos.sd04_i_unidade
         left  join db_depart          on db_depart.coddepto                = unidades.sd02_i_codigo
         left  join rhcbo              on rhcbo.rh70_sequencial             = especmedico.sd27_i_rhcbo
         inner join medicos            on medicos.sd03_i_codigo             = unidademedicos.sd04_i_medico
         inner join cgm m              on m.z01_numcgm                      = medicos.sd03_i_cgm
         inner join db_usuarios        on db_usuarios.id_usuario            = prontproced.sd29_i_usuario
         left  join sau_procedimento   on sau_procedimento.sd63_i_codigo    = prontproced.sd29_i_procedimento
         left  join sau_financiamento  on sau_financiamento.sd65_i_codigo   = sau_procedimento.sd63_i_financiamento
         left  join sau_rubrica        on sau_rubrica.sd64_i_codigo         = sau_procedimento.sd63_i_rubrica
         left  join sau_complexidade   on sau_complexidade.sd69_i_codigo    = sau_procedimento.sd63_i_complexidade
         left  join sau_orgaoemissor   on sau_orgaoemissor.sd51_i_codigo    = unidademedicos.sd04_i_orgaoemissor
         left  join sau_modvinculo     on sau_modvinculo.sd52_i_vinculacao  = unidademedicos.sd04_i_vinculo
         left  join sau_siasih         on sau_siasih.sd92_i_codigo          = prontuarios.sd24_i_siasih
         inner join cgs                on cgs.z01_i_numcgs                  = prontuarios.sd24_i_numcgs
         left  join cgs_und            on cgs_und.z01_i_cgsund              = cgs.z01_i_numcgs
         left  join cgs_cartaosus      on cgs.z01_i_numcgs                  = cgs_cartaosus.s115_i_cgs
                                      and cgs_cartaosus.s115_c_tipo         = 'D'
         left  join sau_lotepront      on sau_lotepront.sd59_i_prontuario   = prontuarios.sd24_i_codigo
         left  join sau_lote           on sau_lote.sd58_i_codigo            = sau_lotepront.sd59_i_lote
         left  join prontprocedcid     on prontprocedcid.s135_i_prontproced = prontproced.sd29_i_codigo
         left  join sau_cid            on sau_cid.sd70_i_codigo             = prontprocedcid.s135_i_cid
SQL;
     $sql2 = "";
     if($dbwhere==""){
       if($sd29_i_codigo!=null ){
         $sql2 .= " where prontproced.sd29_i_codigo = $sd29_i_codigo ";
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

  /**
   * Função retorna as FAA's q não estejam no lote
   *
   * @param Código Sequencial $sd29_i_codigo
   * @param Campos $campos
   * @param Ordenação $ordem
   * @param Condição $dbwhere
   * @return String SQL
   */
  function sql_query_nolote_ext ( $sd29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from prontproced ";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario";
     $sql .= "      left join especmedico  on  especmedico.sd27_i_codigo = prontproced.sd29_i_profissional";
     $sql .= "      left join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql .= "      left join rhcbo  on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";

     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      inner join cgm m on  m.z01_numcgm = medicos.sd03_i_cgm";

     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = prontproced.sd29_i_usuario";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = prontproced.sd29_i_procedimento";
     $sql .= "      inner join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento";
     $sql .= "      left join sau_rubrica  on  sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica";
     $sql .= "      left join sau_complexidade  on  sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade";
     $sql .= "      left join sau_orgaoemissor  on  sau_orgaoemissor.sd51_i_codigo = unidademedicos.sd04_i_orgaoemissor";
     $sql .= "      left join sau_modvinculo  on  sau_modvinculo.sd52_i_vinculacao = unidademedicos.sd04_i_vinculo";
     //$sql .= "      left join sau_cid  on  sau_cid.sd70_i_codigo = prontuarios.sd24_i_cid";
     $sql .= "      left join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql .= "      left join cgs_und on cgs_und.z01_i_cgsund = cgs.z01_i_numcgs";
     //prontproced cid
     $sql .= "       left join prontprocedcid on prontprocedcid.s135_i_prontproced = prontproced.sd29_i_codigo ";
     $sql .= "       left join sau_cid        on sau_cid.sd70_i_codigo = prontprocedcid.s135_i_cid ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd29_i_codigo!=null ){
         $sql2 .= " where prontproced.sd29_i_codigo = $sd29_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     //Não pode estar no lote
     $sql2 .= " and not exists ( select * from sau_lotepront where sau_lotepront.sd59_i_prontuario = prontuarios.sd24_i_codigo ) ";

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

   function sql_query_prontuario ( $sd29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from prontproced ";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario";
     //$sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = prontproced.sd29_i_profissional";
     //$sql .= "      inner join unidades  on  unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade";
     //$sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = unidademedicos.sd04_i_cbo";
     $sql .= "      left join especmedico  on  especmedico.sd27_i_codigo = prontproced.sd29_i_profissional";
     $sql .= "      left join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql .= "      left join rhcbo  on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";

     //Unidade/Departamento
     $sql .= "      left join unidades  on unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade ";
     $sql .= "      left join db_depart on db_depart.coddepto = unidades.sd02_i_codigo ";

     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      inner join cgm m on  m.z01_numcgm = medicos.sd03_i_cgm";

     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = prontproced.sd29_i_usuario";
     $sql .= "      inner join db_usuarios as a on  a.id_usuario = prontuarios.sd24_i_login";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = prontproced.sd29_i_procedimento";
     $sql .= "      inner join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento";
     $sql .= "      left join sau_rubrica  on  sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica";
     $sql .= "      left join sau_complexidade  on  sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade";
     $sql .= "      left join sau_orgaoemissor  on  sau_orgaoemissor.sd51_i_codigo = unidademedicos.sd04_i_orgaoemissor";
     $sql .= "      left join sau_modvinculo  on  sau_modvinculo.sd52_i_vinculacao = unidademedicos.sd04_i_vinculo";

     $sql .= "      left join (select distinct(sd55_i_prontuario),sd55_i_cid,sd55_b_principal ";
     $sql .= "                   from prontcid ";
     $sql .= "                  where sd55_b_principal = 't' ";
     $sql .= "                     or sd55_b_principal is null ) as _prontcid  ";
     $sql .= "             on  _prontcid.sd55_i_prontuario = prontuarios.sd24_i_codigo";

     $sql .= "      left join sau_cid     on  sau_cid.sd70_i_codigo = _prontcid.sd55_i_cid";
     $sql .= "      left join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";

     $sql .= "      inner join cgs     on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql .= "       left join cgs_und on cgs_und.z01_i_cgsund = cgs.z01_i_numcgs";

     $sql .= "      left join sau_lotepront on sau_lotepront.sd59_i_prontuario = prontuarios.sd24_i_codigo ";
     $sql .= "      left join sau_lote      on sau_lote.sd58_i_codigo = sau_lotepront.sd59_i_lote ";

     $sql .= "      left join sau_triagemavulsaprontuario  on sau_triagemavulsaprontuario.s155_i_prontuario = prontuarios.sd24_i_codigo ";
     $sql .= "      left join sau_triagemavulsa  on sau_triagemavulsa.s152_i_codigo = sau_triagemavulsaprontuario.s155_i_triagemavulsa ";

     $sql .= "      left join sau_motivoatendimento on s144_i_codigo = sd24_i_motivo";
     $sql2 = "";
     if($dbwhere==""){
       if($sd29_i_codigo!=null ){
         $sql2 .= " where prontproced.sd29_i_codigo = $sd29_i_codigo ";
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

  function sql_query_prontuario2 ( $sd29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
         $sql .= " from prontproced ";
         $sql .= " left JOIN prontuarios      on prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario ";
         $sql .= " left JOIN db_usuarios       on db_usuarios.id_usuario = prontuarios.sd24_i_login ";
         $sql .= " left JOIN especmedico      on especmedico.sd27_i_codigo = prontproced.sd29_i_profissional ";
         $sql .= " left JOIN unidademedicos   on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed ";
         $sql .= " left JOIN medicos          on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico ";
         $sql .= " left JOIN cgm              on cgm.z01_numcgm = medicos.sd03_i_cgm ";
         $sql .= " left JOIN rhcbo            on rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo ";
         $sql .= " left JOIN unidades         on unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade ";
         $sql .= " left JOIN db_depart        on db_depart.coddepto = unidades.sd02_i_codigo ";
         $sql .= " left JOIN sau_procedimento on sau_procedimento.sd63_i_codigo = prontproced.sd29_i_procedimento ";
         $sql .= " LEFT JOIN prontprocedcid   on prontprocedcid.s135_i_prontproced = prontproced.sd29_i_codigo ";
         $sql .= " LEFT JOIN sau_cid          on sau_cid.sd70_i_codigo = prontprocedcid.s135_i_cid ";
         $sql .= " left join prontagendamento on prontagendamento.s102_i_prontuario = prontproced.sd29_i_prontuario ";
         $sql2 = "";
         if($dbwhere==""){
            if($sd29_i_codigo!=null ){
                $sql2 .= " where prontproced.sd29_i_codigo = $sd29_i_codigo ";
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
