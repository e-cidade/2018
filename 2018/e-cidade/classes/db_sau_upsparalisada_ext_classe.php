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
//CLASSE DA ENTIDADE sau_upsparalisada
require_once( "classes/db_sau_upsparalisada_classe.php");

class cl_sau_upsparalisada_ext extends cl_sau_upsparalisada { 
   // funcao do sql 
   function sql_query_ext ( $s140_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_upsparalisada ";
     $sql .= "      inner join sau_motivo_ausencia  on  sau_motivo_ausencia.s139_i_codigo = sau_upsparalisada.s140_i_tipo";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = sau_upsparalisada.s140_i_unidade";
     $sql .= "       left join cgm  on  cgm.z01_numcgm = unidades.sd02_i_numcgm and  cgm.z01_numcgm = unidades.sd02_i_diretor";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($s140_i_codigo!=null ){
         $sql2 .= " where sau_upsparalisada.s140_i_codigo = $s140_i_codigo "; 
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
   * Funчуo verifica se unidade esta paralisada
   * @param $iUnidade - Unidade/Departamento
   * @param $dData - Data Paralisaчуo
   */
  
  function UpsParalisada( $iUnidade, $sData, $lMostra=true ){
  	$sWhere  = "     s140_i_unidade = $iUnidade and ";
  	//$sWhere .= " '".date( "Y", $dData )."/";
  	//$sWhere .= date( "m", $dData )."/";
  	//$sWhere .= date( "d", $dData )."'";
  	$sWhere .= " $sData ";
  	$sWhere .= " between s140_d_inicio and s140_d_fim ";
  	
	$rsUpsparalisada     = $this->sql_record( 
							$this->sql_query_ext("", "*",null,$sWhere )
						); 
	//$bRet = $this->numrows == 0;
	$sRet = "";				
	if(  $this->numrows > 0  ){
		$oUpsparalisada = db_utils::fieldsMemory ( $rsUpsparalisada, 0 );
		$sRet = $oUpsparalisada->s139_c_descr;
		if( $lMostra == true ){
			db_msgbox("Unidade $iUnidade Paralisada. \\n\\nMotivo:  {$oUpsparalisada->s139_c_descr}");
		}
	}
	return $sRet;
  }
}
?>