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
//CLASSE DA ENTIDADE prontuarios

require_once("classes/db_prontuarios_classe.php");
class cl_prontuarios_ext extends cl_prontuarios { 
   // funcao do sql 
   function sql_query_ext ( $sd24_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prontuarios ";
     $sql .= "     inner join unidades    on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
     $sql .= "     inner join db_depart   on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "     inner join db_usuarios on  db_usuarios.id_usuario = prontuarios.sd24_i_login";
     $sql .= "      left join cgs  on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql .= "      left join cgs_und  on  cgs_und.z01_i_cgsund = prontuarios.sd24_i_numcgs";
     
     $sql .= "      left join cgm  on  cgm.z01_numcgm = unidades.sd02_i_numcgm ";
     $sql .= "      left join cgm d on  d.z01_numcgm = unidades.sd02_i_diretor";

     $sql .= "      left join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
     $sql .= "      left join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql .= "      left join rhcbo  on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";

     $sql .= "      left join medicos  on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      left join cgm m on  m.z01_numcgm = medicos.sd03_i_cgm";

     //$sql .= "      left join sau_cid  on  sau_cid.sd70_i_codigo = prontuarios.sd24_i_cid";
     $sql .= "      left join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
     $sql .= "      left join sau_tipoproc  on  sau_tipoproc.sd93_i_codigo = sau_siasih.sd92_i_tipoproc";
     $sql .= "      left join sau_esferaadmin  on  sau_esferaadmin.sd37_i_cod_esfadm = unidades.sd02_i_cod_esfadm";
     $sql .= "      left join sau_atividadeensino  on  sau_atividadeensino.sd38_i_cod_ativid = unidades.sd02_i_cod_ativ";
     $sql .= "      left join sau_retentributo  on  sau_retentributo.sd39_i_cod_reten = unidades.sd02_i_reten_trib";
     $sql .= "      left join sau_natorg  on  sau_natorg.sd40_i_cod_natorg = unidades.sd02_i_cod_natorg";
     $sql .= "      left join sau_fluxocliente  on  sau_fluxocliente.sd41_i_cod_cliente = unidades.sd02_i_cod_client";
     $sql .= "      left join sau_tipounidade  on  sau_tipounidade.sd42_i_tp_unid_id = unidades.sd02_i_tp_unid_id";
     $sql .= "      left join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = unidades.sd02_i_cod_turnat";
     $sql .= "      left join sau_nivelhier  on  sau_nivelhier.sd44_i_codnivhier = unidades.sd02_i_codnivhier";
     $sql .= "      left join sau_orgaoemissor  on  sau_orgaoemissor.sd51_i_codigo = unidademedicos.sd04_i_orgaoemissor";
     $sql .= "      left join sau_modvinculo  on  sau_modvinculo.sd52_i_vinculacao = unidademedicos.sd04_i_vinculo";

     $sql .= "      left join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      left join familia  on  familia.sd33_i_codigo = familiamicroarea.sd35_i_familia";
     $sql .= "      left join microarea  on  microarea.sd34_i_codigo = familiamicroarea.sd35_i_microarea";
     $sql .= "      left join sau_motivoatendimento  on  prontuarios.sd24_i_motivo = sau_motivoatendimento.s144_i_codigo";
     $sql .= "      left join sau_tiposatendimento  on  prontuarios.sd24_i_tipo = sau_tiposatendimento.s145_i_codigo";
     $sql .= "      left join far_programa  on  far_programa.fa12_i_codigo = prontuarios.sd24_i_acaoprog ";
     $sql .= "      left join prontcid      on prontcid.sd55_i_prontuario = prontuarios.sd24_i_codigo ";
     $sql .= "      left join sau_cid       on sau_cid.sd70_i_codigo      = prontcid.sd55_i_cid ";
     $sql .= "      left join sau_triagemavulsaprontuario  on sau_triagemavulsaprontuario.s155_i_prontuario = prontuarios.sd24_i_codigo ";
     $sql .= "      left join sau_triagemavulsa  on sau_triagemavulsa.s152_i_codigo = sau_triagemavulsaprontuario.s155_i_triagemavulsa ";
     $sql .= "      left join far_cbosprofissional on far_cbosprofissional.fa54_i_codigo = sau_triagemavulsa.s152_i_cbosprofissional ";
     $sql .= "      left join unidademedicos as unidademedicos2 on  unidademedicos2.sd04_i_codigo = far_cbosprofissional.fa54_i_unidademedico";
     $sql .= "      left join medicos as medicos2 on  medicos2.sd03_i_codigo = unidademedicos2.sd04_i_medico";
     $sql .= "      left join cgm as cgm2 on  cgm2.z01_numcgm = medicos2.sd03_i_cgm";

     
     
     $sql2 = "";
          
     if($dbwhere==""){
       if($sd24_i_codigo!=null ){
         $sql2 .= " where prontuarios.sd24_i_codigo = $sd24_i_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     if( $sql2 == "" ){
	  	db_msgbox("Classe requer uma condiчуo.");
	  	exit;
	 }
     
     //Nуo pode estar no prontanulado
     $sql2 .= " and not exists ( select * from prontanulado where prontanulado.sd57_i_prontuario= prontuarios.sd24_i_codigo ) ";
     
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

   function sql_query_nolote_ext ( $sd24_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prontuarios ";
     $sql .= "     inner join unidades  on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
     $sql .= "     inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "      left join cgs  on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql .= "      left join cgs_und  on  cgs_und.z01_i_cgsund = prontuarios.sd24_i_numcgs";
     
     $sql .= "      left join cgm  on  cgm.z01_numcgm = unidades.sd02_i_numcgm ";
     $sql .= "      left join cgm d on  d.z01_numcgm = unidades.sd02_i_diretor";

     $sql .= "      left join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
     $sql .= "      left join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql .= "      left join rhcbo  on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";

     $sql .= "      left join medicos  on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      left join cgm m on  m.z01_numcgm = medicos.sd03_i_cgm";

     //$sql .= "      left join sau_cid  on  sau_cid.sd70_i_codigo = prontuarios.sd24_i_cid";
     $sql .= "      left join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
     $sql .= "      left join sau_tipoproc  on  sau_tipoproc.sd93_i_codigo = sau_siasih.sd92_i_tipoproc";
     $sql .= "      left join sau_esferaadmin  on  sau_esferaadmin.sd37_i_cod_esfadm = unidades.sd02_i_cod_esfadm";
     $sql .= "      left join sau_atividadeensino  on  sau_atividadeensino.sd38_i_cod_ativid = unidades.sd02_i_cod_ativ";
     $sql .= "      left join sau_retentributo  on  sau_retentributo.sd39_i_cod_reten = unidades.sd02_i_reten_trib";
     $sql .= "      left join sau_natorg  on  sau_natorg.sd40_i_cod_natorg = unidades.sd02_i_cod_natorg";
     $sql .= "      left join sau_fluxocliente  on  sau_fluxocliente.sd41_i_cod_cliente = unidades.sd02_i_cod_client";
     $sql .= "      left join sau_tipounidade  on  sau_tipounidade.sd42_i_tp_unid_id = unidades.sd02_i_tp_unid_id";
     $sql .= "      left join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = unidades.sd02_i_cod_turnat";
     $sql .= "      left join sau_nivelhier  on  sau_nivelhier.sd44_i_codnivhier = unidades.sd02_i_codnivhier";
     $sql .= "      left join sau_orgaoemissor  on  sau_orgaoemissor.sd51_i_codigo = unidademedicos.sd04_i_orgaoemissor";
     $sql .= "      left join sau_modvinculo on  sau_modvinculo.sd52_i_vinculacao = unidademedicos.sd04_i_vinculo";
     
     $sql .= "      left join sau_lotepront on sau_lotepront.sd59_i_prontuario = prontuarios.sd24_i_codigo ";
     $sql .= "      left join sau_lote      on sau_lote.sd58_i_codigo = sau_lotepront.sd59_i_lote ";
     $sql .= "      left join prontcid      on prontcid.sd55_i_prontuario = prontuarios.sd24_i_codigo ";
     $sql .= "      left join sau_cid       on sau_cid.sd70_i_codigo      = prontcid.sd55_i_cid ";
     
     $sql2 = "";     
     if($dbwhere==""){
       if($sd24_i_codigo!=null ){
         $sql2 .= " where prontuarios.sd24_i_codigo = $sd24_i_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     
     //Nуo pode estar no lote
     //$sql2 .= " and not exists ( select * from sau_lotepront where sau_lotepront.sd59_i_prontuario = prontuarios.sd24_i_codigo ) ";
     
     //Nуo pode estar no prontanulado
     $sql2 .= " and not exists ( select * from prontanulado where prontanulado.sd57_i_prontuario= prontuarios.sd24_i_codigo ) ";
     
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
 
  function sql_query_faas_por_profissional($sd24_i_codigo = null, $sd04_i_medico, $sd04_i_unidade, $campos = "*", $ordem = null, $dbwhere = "") { 
     $sql = "select distinct ";
     if($campos != "*" ) {

       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++) {

         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";

       }

     } else {
       $sql .= $campos;
     }
     $sql .= " from prontuarios ";
     $sql .= "     inner join unidades    on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
     $sql .= "     inner join db_depart   on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "     left join cgs on cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql .= "     left join cgs_und on cgs_und.z01_i_cgsund = prontuarios.sd24_i_numcgs";
     $sql .= "     inner join unidademedicos on unidademedicos.sd04_i_unidade = prontuarios.sd24_i_unidade";
     $sql .= "     inner join medicos on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql2 = "      where sd04_i_medico = $sd04_i_medico and sd04_i_unidade = $sd04_i_unidade "; 
    
     if($sd24_i_codigo != null && trim($sd24_i_codigo) != '') {
       $sql2 .=  "and prontuarios.sd24_i_codigo = $sd24_i_codigo";
     }   
     if($dbwhere != "") {
       $sql2 .= " and $dbwhere";
     }
     
     //Nуo pode estar no prontanulado
     $sql2 .= " and not exists ( select * from prontanulado where prontanulado.sd57_i_prontuario= prontuarios.sd24_i_codigo ) ";
     
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