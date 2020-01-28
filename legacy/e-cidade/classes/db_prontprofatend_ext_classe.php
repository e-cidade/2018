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
//CLASSE DA ENTIDADE prontprofatend
require("classes/db_prontprofatend_classe.php");
class cl_prontprofatend_ext extends cl_prontprofatend  {

	//Verifica número de registro em prontproced
	function sql_prontproced( $prontuario, $profissional ){

    if ( !empty($prontuario) && !empty($profissional) ) {

  		$result = $this->sql_record( $this->sql_query_ext(null, "*", "sd29_i_codigo", "s104_i_prontuario = $prontuario and sd29_i_profissional = $profissional") );
  		return ($this->numrows == 0);
    }

    return false;
	}
   // funcao do sql
   function sql_query_ext ( $s104_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from prontprofatend ";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = prontprofatend.s104_i_prontuario";
     $sql .= "       left join prontproced  on prontproced.sd29_i_prontuario = prontuarios.sd24_i_codigo ";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";

     $sql .= "      inner join especmedico  on  especmedico.sd27_i_codigo = prontprofatend.s104_i_profissional";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";

     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      inner join cgm m on  m.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "      left join cgs  on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql .= "      left join cgs_und  on  cgs_und.z01_i_cgsund = prontuarios.sd24_i_numcgs";


     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = prontuarios.sd24_i_login";
     $sql .= "      left  join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";

     $sql2 = "";
     if($dbwhere==""){
       if($s104_i_codigo!=null ){
         $sql2 .= " where prontprofatend.s104_i_codigo = $s104_i_codigo ";
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