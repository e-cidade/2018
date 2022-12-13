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

function debitos_tipos_matricula($matricula){
  $sql = "select distinct t.k00_tipo,t.k00_descr,i.k00_numcgm,t.k00_emrec,t.k00_agnum,t.k00_agpar
          from arrematric a
		  inner join arrecad i 
		  on a.k00_matric = $matricula 
		  and a.k00_numpre = i.k00_numpre 
		  ,arretipo t
          where i.k00_tipo = t.k00_tipo ";
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage());
  return (pg_numrows($result)==0?false:$result);
}

function debitos_tipos_inscricao($inscricao){
  $sql = "select distinct t.k00_tipo,t.k00_descr,i.k00_numcgm,t.k00_emrec,t.k00_agnum,t.k00_agpar
          from arreinscr a
		  inner join arrecad i 
		  on a.k00_inscr = $inscricao 
		  and a.k00_numpre = i.k00_numpre 
		  ,arretipo t
          where i.k00_tipo = t.k00_tipo ";
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage());
  return (pg_numrows($result)==0?false:$result);
}
function debitos_tipos_numcgm($numcgm){
  $sql = "select distinct t.k00_tipo,t.k00_descr,a.k00_numcgm,t.k00_emrec,t.k00_agnum,t.k00_agpar
          from arrecad a ,arretipo t
		  where a.k00_numcgm = $numcgm and
		        a.k00_tipo   = t.k00_tipo";		  
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage());
  return (pg_numrows($result)==0?false:$result);
}

function debitos_tipos_numpre($numpre){
  $sql = "select distinct t.k00_tipo,t.k00_descr,a.k00_numcgm,t.k00_emrec,t.k00_agnum,t.k00_agpar
          from arrecad a ,arretipo t
		  where a.k00_numpre = $numpre and
		        a.k00_tipo   = t.k00_tipo";		  
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage());
  return (pg_numrows($result)==0?false:$result);
}

function debitos_matricula($matricula,$limite,$tipo,$datausu,$anousu){
$sql = "select   y.k00_inscr,
		         y.k00_matric,
		  		 y.k00_numcgm ,
		         y.k00_receit ,
		         y.k00_numpre ,
		         y.k00_numpar ,
		         y.k00_numtot ,
		         y.k00_numdig ,
				 y.k02_descr,
                 y.vlrhis,
                 y.vlrcor,
                 y.vlrjuros,
                 y.vlrmulta,
                 y.vlrdesconto,
                 y.total,
				 min(k00_dtvenc)::date as k00_dtvenc,
				 min(k00_dtoper)::date as k00_dtoper,
				 0::float8 as k00_valor,
				 min(k01_descr)::varchar(40) as k01_descr
       from ( select distinct *,
                 substr(fc_calcula,2,13)::float8 as vlrhis,
                 substr(fc_calcula,15,13)::float8 as vlrcor,
                 substr(fc_calcula,28,13)::float8 as vlrjuros,
                 substr(fc_calcula,41,13)::float8 as vlrmulta,
                 substr(fc_calcula,54,13)::float8 as vlrdesconto,
                 (substr(fc_calcula,15,13)::float8+
                 substr(fc_calcula,28,13)::float8+
                 substr(fc_calcula,41,13)::float8-
                 substr(fc_calcula,54,13)::float8) as total
          from (  
          select 0::integer as k00_inscr,a.k00_matric,
		  		 i.k00_numcgm ,
		         i.k00_receit ,
		         i.k00_numpre ,
		         i.k00_numpar ,
		         i.k00_numtot ,
		         i.k00_numdig ,
		         tabrec.k02_descr, fc_calcula(i.k00_numpre,i.k00_numpar,i.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.")
          from arrematric a
			     inner join arrecad i on a.k00_numpre = i.k00_numpre 
				 ,histcalc,tabrec inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm 
          where k00_hist   = k01_codigo and 
		        a.k00_matric = $matricula and
				k00_receit = k02_codigo ";
  if($tipo != 0){
	$sql .= " and k00_tipo   = ".$tipo;
  }
  $sql .= " order by k00_numpre,k00_numpar";
  if ($limite != 0 ) {
     $sql = $sql . " limit ".$limite;
  }
  $sql .= ") as x
   ) as y,arrecad,histcalc
   where y.k00_numpre = arrecad.k00_numpre and
         y.k00_numpar = arrecad.k00_numpar and
		 y.k00_receit = arrecad.k00_receit and
		 arrecad.k00_hist = histcalc.k01_codigo
   group by      y.k00_inscr,
		         y.k00_matric,
		  		 y.k00_numcgm ,
		         y.k00_receit ,
		         y.k00_numpre ,
		         y.k00_numpar ,
		         y.k00_numtot ,
		         y.k00_numdig ,
				 y.k02_descr,
                 y.vlrhis,
                 y.vlrcor,
                 y.vlrjuros,
                 y.vlrmulta,
                 y.vlrdesconto,
                 y.total
	order by k00_numpre,k00_numpar
   ";
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));
  if($limite == 0 ) {
     if(pg_numrows($result) == 0 ) {
        return false;
     }
	 return $result;
  }else{
    if(pg_numrows($result) == 0 ) {
      return false;
	} else {
	  return 1;
	}
  }
}


function debitos_inscricao($inscricao,$limite,$tipo,$datausu,$anousu){
$sql = "select   y.k00_inscr,
		         y.k00_matric,
		  		 y.k00_numcgm ,
		         y.k00_receit ,
		         y.k00_numpre ,
		         y.k00_numpar ,
		         y.k00_numtot ,
		         y.k00_numdig ,
				 y.k02_descr,
                 y.vlrhis,
                 y.vlrcor,
                 y.vlrjuros,
                 y.vlrmulta,
                 y.vlrdesconto,
                 y.total,
				 min(k00_dtvenc) as k00_dtvenc,
				 min(k00_dtoper) as k00_dtoper,
				 0 as k00_valor,
				 min(k01_descr) as k01_descr
       from ( select distinct *,
                 substr(fc_calcula,2,13)::float8 as vlrhis,
                 substr(fc_calcula,15,13)::float8 as vlrcor,
                 substr(fc_calcula,28,13)::float8 as vlrjuros,
                 substr(fc_calcula,41,13)::float8 as vlrmulta,
                 substr(fc_calcula,54,13)::float8 as vlrdesconto,
                 (substr(fc_calcula,15,13)::float8+
                 substr(fc_calcula,28,13)::float8+
                 substr(fc_calcula,41,13)::float8-
                 substr(fc_calcula,54,13)::float8) as total
          from (  
          select 0::integer as k00_matric,
                 a.k00_inscr,
		  		 i.k00_numcgm ,
		         i.k00_receit ,
		         i.k00_numpre ,
		         i.k00_numpar ,
		         i.k00_numtot ,
		         i.k00_numdig ,
				 tabrec.k02_descr,fc_calcula(i.k00_numpre,i.k00_numpar,i.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.") 
          from arreinscr a
			     inner join arrecad i on a.k00_numpre = i.k00_numpre 
				 ,histcalc,tabrec  inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
          where k00_hist   = k01_codigo and 
		        a.k00_inscr = $inscricao and
				k00_receit = k02_codigo " ; 
  if($tipo != 0){
	$sql .= " and k00_tipo   = ".$tipo;
  }
  $sql .= " order by k00_numpre,k00_numpar";
  if ($limite != 0 ) {
     $sql .= " limit ".$limite;
  }
  $sql .= ") as x
   ) as y,arrecad,histcalc
   where y.k00_numpre = arrecad.k00_numpre and
         y.k00_numpar = arrecad.k00_numpar and
		 y.k00_receit = arrecad.k00_receit and
		 arrecad.k00_hist = histcalc.k01_codigo
   group by      y.k00_inscr,
		         y.k00_matric,
		  		 y.k00_numcgm ,
		         y.k00_receit ,
		         y.k00_numpre ,
		         y.k00_numpar ,
		         y.k00_numtot ,
		         y.k00_numdig ,
				 y.k02_descr,
                 y.vlrhis,
                 y.vlrcor,
                 y.vlrjuros,
                 y.vlrmulta,
                 y.vlrdesconto,
                 y.total
	order by k00_numpre,k00_numpar
   ";
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));
  if ($limite == 0 ) {
     if (pg_numrows($result) == 0 ){
        return false;
     }
	 return $result;
  }else{
    if (pg_numrows($result) == 0 ){
      return false;
	}else{
	  return 1;
	}
  }
}

function debitos_numpre($numpre,$limite,$tipo,$datausu,$anousu){
$sql = "select   y.k00_inscr,
		         y.k00_matric,
		  		 y.k00_numcgm ,
		         y.k00_receit ,
		         y.k00_numpre ,
		         y.k00_numpar ,
		         y.k00_numtot ,
		         y.k00_numdig ,
				 y.k02_descr,
                 y.vlrhis,
                 y.vlrcor,
                 y.vlrjuros,
                 y.vlrmulta,
                 y.vlrdesconto,
                 y.total,
				 min(k00_dtvenc) as k00_dtvenc,
				 min(k00_dtoper) as k00_dtoper,
				 0 as k00_valor,
				 min(k01_descr) as k01_descr
       from (
             select distinct *,
                 substr(fc_calcula,2,13)::float8 as vlrhis,
                 substr(fc_calcula,15,13)::float8 as vlrcor,
                 substr(fc_calcula,28,13)::float8 as vlrjuros,
                 substr(fc_calcula,41,13)::float8 as vlrmulta,
                 substr(fc_calcula,54,13)::float8 as vlrdesconto,
                 (substr(fc_calcula,15,13)::float8+
                 substr(fc_calcula,28,13)::float8+
                 substr(fc_calcula,41,13)::float8-
                 substr(fc_calcula,54,13)::float8) as total
          from (  
          select i.k00_inscr,
		         m.k00_matric,
		  		 a.k00_numcgm ,
		         a.k00_receit ,
		         a.k00_numpre ,
		         a.k00_numpar ,
		         a.k00_numtot ,
		         a.k00_numdig ,
				 tabrec.k02_descr,fc_calcula(a.k00_numpre,a.k00_numpar,a.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.") 
          from arrecad a
			     left outer join arreinscr  i on a.k00_numpre = i.k00_numpre 
			     left outer join arrematric m on a.k00_numpre = m.k00_numpre 
				 ,histcalc,tabrec  inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
          where k00_hist   = k01_codigo and 
		        a.k00_numpre = $numpre and
				k00_receit = k02_codigo";
  if($tipo != 0){
	$sql .= " and k00_tipo   = ".$tipo;
  }
  $sql = $sql . " order by k00_numpre,k00_numpar";
  if ($limite != 0 ) {
     $sql .= " limit ".$limite;
  }
  $sql .= ") as x
   ) as y,arrecad,histcalc
   where y.k00_numpre = arrecad.k00_numpre and
         y.k00_numpar = arrecad.k00_numpar and
		 y.k00_receit = arrecad.k00_receit and
		 arrecad.k00_hist = histcalc.k01_codigo
   group by      y.k00_inscr,
		         y.k00_matric,
		  		 y.k00_numcgm ,
		         y.k00_receit ,
		         y.k00_numpre ,
		         y.k00_numpar ,
		         y.k00_numtot ,
		         y.k00_numdig ,
				 y.k02_descr,
                 y.vlrhis,
                 y.vlrcor,
                 y.vlrjuros,
                 y.vlrmulta,
                 y.vlrdesconto,
                 y.total
	order by k00_numpre,k00_numpar
   ";

  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));
  if ($limite == 0 ) {
     if (pg_numrows($result) == 0 ){
       return false;
     }
	 return $result;
  }else{
    if (pg_numrows($result) == 0 ){
      return false;
	}else{
	  return 1;
	}
  }
}



function debitos_numcgm($numcgm,$limite,$tipo,$datausu,$anousu){

/*
		         k00_hist   ,
		         k00_valor  ,
		         k00_tipo   ,
		         k00_dtoper ,
		         k00_dtvenc ,
		         histcalc.*
*/
				 
$sql = "select   y.k00_inscr,
		         y.k00_matric,
		  		 y.k00_numcgm ,
		         y.k00_receit ,
		         y.k00_numpre ,
		         y.k00_numpar ,
		         y.k00_numtot ,
		         y.k00_numdig ,
				 y.k02_descr,
                 y.vlrhis,
                 y.vlrcor,
                 y.vlrjuros,
                 y.vlrmulta,
                 y.vlrdesconto,
                 y.total,
				 min(k00_dtvenc) as k00_dtvenc,
				 min(k00_dtoper) as k00_dtoper,
				 0 as k00_valor,
				 min(k01_descr) as k01_descr
       from (
          select distinct *,
                 substr(fc_calcula,2,13)::float8 as vlrhis,
                 substr(fc_calcula,15,13)::float8 as vlrcor,
                 substr(fc_calcula,28,13)::float8 as vlrjuros,
                 substr(fc_calcula,41,13)::float8 as vlrmulta,
                 substr(fc_calcula,54,13)::float8 as vlrdesconto,
                 (substr(fc_calcula,15,13)::float8+
                 substr(fc_calcula,28,13)::float8+
                 substr(fc_calcula,41,13)::float8-
                 substr(fc_calcula,54,13)::float8) as total
          from 
		  (  
          select i.k00_inscr,
		          m.k00_matric,
		  		 a.k00_numcgm ,
		         a.k00_receit ,
		         a.k00_numpre ,
		         a.k00_numpar ,
		         a.k00_numtot ,
		         a.k00_numdig ,
				 tabrec.k02_descr,fc_calcula(a.k00_numpre,a.k00_numpar,a.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.") 
          from arrecad a
			     left outer join arreinscr  i on a.k00_numpre = i.k00_numpre 
			     left outer join arrematric m on a.k00_numpre = m.k00_numpre 
				 ,histcalc,tabrec  inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
          where k00_hist   = k01_codigo and 
		        a.k00_numcgm = $numcgm and
				k00_receit = k02_codigo" ;
  if($tipo != 0){
	$sql .= " and k00_tipo   = ".$tipo;
  }
  $sql = $sql . " order by k00_numpre,k00_numpar";
  if ($limite != 0 ) {
     $sql .= " limit ".$limite;
  }
  $sql .= ") as x 
   ) as y,arrecad,histcalc
   where y.k00_numpre = arrecad.k00_numpre and
         y.k00_numpar = arrecad.k00_numpar and
		 y.k00_receit = arrecad.k00_receit and
		 arrecad.k00_hist = histcalc.k01_codigo
   group by      y.k00_inscr,
		         y.k00_matric,
		  		 y.k00_numcgm ,
		         y.k00_receit ,
		         y.k00_numpre ,
		         y.k00_numpar ,
		         y.k00_numtot ,
		         y.k00_numdig ,
				 y.k02_descr,
                 y.vlrhis,
                 y.vlrcor,
                 y.vlrjuros,
                 y.vlrmulta,
                 y.vlrdesconto,
                 y.total
	order by k00_numpre,k00_numpar
   ";
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));
  if($limite == 0 ) {
     if(pg_numrows($result) == 0 ){
        return false;
     }
	 return $result;
  } else {
    if(pg_numrows($result) == 0 ){
      return false;
	} else {
	  return 1;
	}
  }
}

function debitos_numcgm_var($numcgm,$limite,$tipo,$datausu,$anousu){
  $sql = "select *,
                 substr(fc_calcula,2,13)::float8 as vlrhis,
                 substr(fc_calcula,15,13)::float8 as vlrcor,
                 substr(fc_calcula,28,13)::float8 as vlrjuros,
                 substr(fc_calcula,41,13)::float8 as vlrmulta,
                 substr(fc_calcula,54,13)::float8 as vlrdesconto,
                 (substr(fc_calcula,15,13)::float8+
                 substr(fc_calcula,28,13)::float8+
                 substr(fc_calcula,41,13)::float8-
                 substr(fc_calcula,54,13)::float8) as total
          from (  
          select v.k00_valor as valor_variavel,i.k00_inscr,m.k00_matric,a.*
				 histcalc.*,tabrec.k02_descr,fc_calcula(a.k00_numpre,a.k00_numpar,a.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.") 
          from arrecad a
			     left outer join arreinscr  i on a.k00_numpre = i.k00_numpre 
			     left outer join arrematric m on a.k00_numpre = m.k00_numpre 
				 left outer join issvar v on v.k00_numpre = a.k00_numpre and v.k00_numpar = a.k00_numpar
				 ,histcalc,tabrec  inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
          where k00_hist   = k01_codigo and 
		        a.k00_numcgm = $numcgm and
				k00_receit = k02_codigo";
  if($tipo != 0){
	$sql .= " and k00_tipo   = ".$tipo;
  }
  $sql = $sql . " order by k00_numpre,k00_numpar,k00_dtvenc";
  if ($limite != 0 ) {
     $sql .= " limit ".$limite;
  }
  $sql .= ") as x";
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));
  if ($limite == 0 ) {
     if (pg_numrows($result) == 0 ){
       return false;
     }
	 return $result;
  }else{
    if (pg_numrows($result) == 0 ){
      return false;
	}else{
	  return 1;
	}
  }
}


function debitos_numpre_var($numpre,$limite,$tipo,$datausu,$anousu){
  $sql = "select *,
                 substr(fc_calcula,2,13)::float8 as vlrhis,
                 substr(fc_calcula,15,13)::float8 as vlrcor,
                 substr(fc_calcula,28,13)::float8 as vlrjuros,
                 substr(fc_calcula,41,13)::float8 as vlrmulta,
                 substr(fc_calcula,54,13)::float8 as vlrdesconto,
                 (substr(fc_calcula,15,13)::float8+
                 substr(fc_calcula,28,13)::float8+
                 substr(fc_calcula,41,13)::float8-
                 substr(fc_calcula,54,13)::float8) as total
          from (  
          select v.k00_valor as valor_variavel,i.k00_inscr,m.k00_matric,a.*,histcalc.*,tabrec.k02_descr,fc_calcula(a.k00_numpre,a.k00_numpar,a.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.") 
          from arrecad a
			     left outer join arreinscr  i on a.k00_numpre = i.k00_numpre 
			     left outer join arrematric m on a.k00_numpre = m.k00_numpre 
				 left outer join issvar v on v.k00_numpre = a.k00_numpre and v.k00_numpar = a.k00_numpar
				 ,histcalc,tabrec  inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
          where k00_hist   = k01_codigo and 
		        a.k00_numpre = $numpre and
				k00_receit = k02_codigo ";
  if($tipo != 0){
	$sql .= " and k00_tipo   = ".$tipo;
  }
  $sql = $sql . " order by k00_numpre,k00_numpar,k00_dtvenc";
  if ($limite != 0 ) {
     $sql .= " limit ".$limite;
  }
  $sql .= ") as x";
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));
  if ($limite == 0 ) {
     if (pg_numrows($result) == 0 ){
       return false;
     }
	 return $result;
  }else{
    if (pg_numrows($result) == 0 ){
      return false;
	}else{
	  return 1;
	}
  }
}


function debitos_inscricao_var($inscricao,$limite,$tipo,$datausu,$anousu){
  $sql = "select *,
                 substr(fc_calcula,2,13)::float8 as vlrhis,
                 substr(fc_calcula,15,13)::float8 as vlrcor,
                 substr(fc_calcula,28,13)::float8 as vlrjuros,
                 substr(fc_calcula,41,13)::float8 as vlrmulta,
                 substr(fc_calcula,54,13)::float8 as vlrdesconto,
                 (substr(fc_calcula,15,13)::float8+
                 substr(fc_calcula,28,13)::float8+
                 substr(fc_calcula,41,13)::float8-
                 substr(fc_calcula,54,13)::float8) as total
          from (  
          select ''::bpchar as k00_matric,v.q05_vlrinf as valor_variavel,a.k00_inscr,i.*,histcalc.*,tabrec.k02_descr,fc_calcula(i.k00_numpre,i.k00_numpar,i.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.") 
          from arreinscr a
			     inner join arrecad i on a.k00_numpre = i.k00_numpre 
				 left outer join issvar v on v.q05_numpre = i.k00_numpre and v.q05_numpar = i.k00_numpar
				 ,histcalc,tabrec  inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
          where k00_hist   = k01_codigo and 
		        a.k00_inscr = $inscricao and
				k00_receit = k02_codigo "; 
  if($tipo != 0){
	$sql .= " and k00_tipo   = ".$tipo;
  }
  $sql .= " order by k00_numpre,k00_numpar,k00_dtvenc";
  if ($limite != 0 ) {
     $sql .= " limit ".$limite;
  }
  $sql .= ") as x";

  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));
  if ($limite == 0 ) {
     if (pg_numrows($result) == 0 ){
       false;
     }
	 return $result;
  }else{
    if (pg_numrows($result) == 0 ){
      return false;
	}else{
	  return 1;
	}
  }
}

// anteriores


function db_mensagem($cabecalho,$rodape) {
  global $DB_mens1, $DB_align1, $DB_mens2, $DB_align2;
  $result = db_query("select mens,alinhamento from db_confmensagem where cod = '$cabecalho' or cod = '$rodape' order by cod");
  if (pg_numrows($result) == 0 ){
     db_msgbox2("Mensagem não encontrado para: $cabecalho $rodape");
     //redireciona("index.php");
	 exit;
  }
  $DB_mens1  = @pg_result($result,0,0);
  $DB_align1 = @pg_result($result,0,1);
  $DB_mens2  = @pg_result($result,1,0);
  $DB_align2 = @pg_result($result,1,1);
}
/*
function debitos_alvara($inscricao,$limite){
  $sql = "select a.k00_inscr,i.*,histcalc.*,tabrec.k02_descr,fc_calcula(k00_numpre,k00_numpar,k00_receit,'".date('Y-m-d')."','".db_vencimento()."',".date("Y").") 
          from arreinscr a
			     inner join arrecad i on a.k00_inscr = $inscricao and
				                         a.k00_numpre = i.k00_numpre 
				 ,histcalc,tabrec 
          where k00_inscr  = '$inscricao' and 
	            k00_hist   = k01_codigo and 
				k00_receit = k02_codigo date("Y")." and 
				k00_tipo   = 1";
				// and( substr(k05_numpre,1,8) = isscalc.q01_npalv or
				//  substr(k05_numpre,1,8) = isscalc.q01_numsan )";
  if ($limite != 0 ) {
     $sql = $sql . " limit $limite";
  }else{
	 $sql = $sql . " order by k00_numpre,k00_numpar,k00_dtvenc";
  }		  
  global $result;		  
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));
  if ($limite == 0 ) {
     if (pg_numrows($result) == 0 ){
        db_msgbox("Sem débitos a Pagar");
        db_logs("","$inscricao",0,"Nao Existem Debitos a Pagar de Alvara. Numero: $inscricao");
        redireciona("opcoesalvara.php?inscricao=".$inscricao);
     }
     db_logs("","$inscricao",0,"Existem Debitos de Alvara. Numero: $inscricao");
	 return 1;
  }else{
    if (pg_numrows($result) == 0 ){
    db_logs("","$inscricao",0,"Nao Existem Debitos de Alvara. Numero: $inscricao");
    return 0;
	}else{
      db_logs("","$inscricao",0,"Existem Debitos de Alvara. Numero: $inscricao");
	  return 1;
	}
  }
}

function debitos_alvara_sanitario($inscricao,$limite){
  $sql = "select a.k00_inscr,i.*,histcalc.*,tabrec.k02_descr,fc_calcula(k00_numpre,k00_numpar,k00_receit,'".date('Y-m-d')."','".db_vencimento()."',".date("Y").") 
          from arreinscr a
			     inner join arrecad i on a.k00_inscr = $inscricao and
				                         a.k00_numpre = i.k00_numpre 
				 ,histcalc,tabrec 
          where k00_hist   = k01_codigo and 
				k00_receit = k02_codigo and k02_anoexe = ".date("Y")." and 
				k00_tipo   = 2;
				// and( substr(k05_numpre,1,8) = isscalc.q01_npalv or
				//  substr(k05_numpre,1,8) = isscalc.q01_numsan )";
  if ($limite != 0 ) {
     $sql = $sql . " limit $limite";
  }else{
	 $sql = $sql . " order by k00_numpre,k00_numpar,k05_dtvenc";
  }		  
  global $result;		  
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));
  if ($limite == 0 ) {
     if (pg_numrows($result) == 0 ){
        db_msgbox("Sem débitos a Pagar");
        db_logs("","$inscricao",0,"Nao Existem Debitos a Pagar de Alvara Sanitário. Numero: $inscricao");
        redireciona("opcoesalvara.php?inscricao=".$inscricao);
     }
     db_logs("","$inscricao",0,"Existem Debitos de Alvara. Numero: $inscricao");
	 return 1;
  }else{
    if (pg_numrows($result) == 0 ){
    db_logs("","$inscricao",0,"Nao Existem Debitos de Alvara. Numero: $inscricao");
    return 0;
	}else{
      db_logs("","$inscricao",0,"Existem Debitos de Alvara. Numero: $inscricao");
	  return 1;
	}
  }
}




function debitos_issqnfixo($inscricao,$limite){
  $sql = "select arreinscr.k00_inscr,arrecad.*,fc_calcula(k00_numpre,k00_numpar,k00_receit,'".date('Y-m-d')."','".db_vencimento()."',".date("Y").")
          from arreinscr a
			     inner join arrecad i on a.k00_inscr = $inscricao and
				                         a.k00_numpre = i.k00_numpre 
				 ,histcalc, tabrec
          where k00_hist  = k01_codigo and 
				k00_receit = k02_codigo and and k02_anoexe = ".date("Y")." and 
				k00_tipo  = 3";
  if ($limite != 0 ) {
     $sql = $sql . " limit $limite";
  }else{
	 $sql = $sql . " order by k00_numpre,k00_nuumpar,k00_dtvenc";
  }		  
  global $result;		  
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));
  if ($limite == 0 ) {
    if (pg_numrows($result) == 0 ){
      db_msgbox("Sem débitos a Pagar");
      db_logs("","$inscricao",0,"Nao Existem Debitos a Pagar de Issqn Fixo. Numero: $inscricao");
      redireciona("opcoesalvara.php?inscricao=".$inscricao);
    }
    db_logs("","$inscricao",0,"Existem Debitos de Issqn Fixo. Numero: $inscricao");
	return 1;
  }else{
    if (pg_numrows($result) == 0 ){
      db_logs("","$inscricao",0,"Nao Existem Debitos de Issqn Fixo. Numero: $inscricao");
      return 0;
	}else{
      db_logs("","$inscricao",0,"Existem Debitos de Issqn Fixo. Numero: $inscricao");
	  return 1;
	}
  }
}

function debitos_issqnvariavel($inscricao,$limite,$recalculo){
  if( $recalculo == 1 ){
    $sql = "select arreinscr.k00_inscr,arrecad.*,fc_calculavar(k00_numpre,k00_numpar,k00_receit,'".date('Y-m-d')."','".db_vencimento()."',".date("Y").") as fc_calcula
            from arreinscr a
			     inner join arrecad i on a.k00_inscr = $inscricao and
				                         a.k00_numpre = i.k00_numpre 
				 ,histcalc 
            where k00_receit = k02_codigo and and k02_anoexe = ".date("Y")." and 
   	              k00_hist   = k01_codigo and 
				  k00_tipo   = 4";
  }else{
    $sql = "select arreinscr.k00_inscr,arrecad.*,fc_calcula(k00_numpre,k00_nuumpar,k00_receit,'".date('Y-m-d')."','".db_vencimento()."',".date("Y").") 
            from arreinscr a
			     inner join arrecad i on a.k00_inscr = $inscricao and
				                         a.k00_numpre = i.k00_numpre and
				                         a.k00_numpar = i.k00_numpar
				 ,histcalc,tabrec
            where k00_hist = k01_codigo
				  k00_receit = k02_codigo and and k02_anoexe = ".date("Y")." 
				  k00_tipo   = 4";
  if ($limite != 0 ) {
     $sql = $sql . " limit $limite";
  }else{
	 $sql = $sql . " order by q04_anomes desc";
  }		  
  global $result;		  
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));
  if ($limite == 0 ) {
    if (pg_numrows($result) == 0 ){
      db_msgbox("Sem débitos a Pagar");
      db_logs("","$inscricao",0,"Nao Existem Debitos a Pagar de Issqn Variavel. Numero: $inscricao");
      redireciona("opcoesalvara.php?inscricao=".$inscricao); 
    }
    db_logs("","$inscricao",0,"Existem Debitos a Pagar de Issqn Variavel. Numero: $inscricao");
	return 1;
  }else{
    if (pg_numrows($result) == 0 ){
      db_logs("","$inscricao",0,"Nao Existem Debitos a Pagar de Issqn Variavel. Numero: $inscricao");
      return 0;
	}else{
      db_logs("","$inscricao",0,"Existem Debitos a Pagar de Issqn Variavel. Numero: $inscricao");
	  return 1;
	}
  }
}




function debitos_dividaalvara($inscricao,$limite){
  $sql = "select arreinscr.k00_inscr,arrecad.*,divida.v01_exerc,divida.v01_proced,proced.*,
                          fc_calcula(k09_numpre,k09_receit,'".date('Y-m-d')."','".db_vencimento()."',".date("Y").",'K09')
			       from
                   (select divpre.*,operac.*
                    from divpre,operac
                    where k09_inscr  = '$inscricao' and 
				          k09_operac = k02_codigo  ";
  if ($limite != 0 ) {
     $sql = $sql . " and k09_numpre = divida.v01_numpre limit $limite";
  }else{		  
     $sql = $sql . " order by k09_numpre,k09_dtvenc";
  }
  $sql = $sql . " ) as divpre, divida,proced
		 	 		where k09_numpre = v01_numpre and
						  v01_proced = v03_codigo";
  if ($limite != 0 ) {
     $sql = $sql . " limit $limite";
  }		  
  global $result;		  
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));
  if ($limite == 0 ) {
    if (pg_numrows($result) == 0 ){
       db_msgbox("Sem débitos a Pagar");
       db_logs("","$inscricao",0,"Nao Existem Debitos a Pagar de Divida Ativa Alvara. Numero: $inscricao");
       redireciona("opcoesalvara.php?inscricao=".$inscricao);  
    }
    db_logs("","$inscricao",0,"Existem Debitos a Pagar de Divida Ativa Alvara. Numero: $inscricao");
	return 1;
  }else{
    if (pg_numrows($result) == 0 ){
      db_logs("","$inscricao",0,"Nao Existem Debitos a Pagar de Divida Ativa Alvara. Numero: $inscricao");
      return 0;
	}else{
      db_logs("","$inscricao",0,"Existem Debitos a Pagar de Divida Ativa Alvara. Numero: $inscricao");
	  return 1;
	}
  }
}

function debitos_dividaparalvara($inscricao,$limite){
  $sql = "select *,fc_calcula(k09_numpre,k09_receit,'".date('Y-m-d')."','".db_vencimento()."',".date("Y").",'K09') 
                   from divpre,operac
                   where k09_inscr = '$inscricao' and 
                         k09_operac = k02_codigo and 
   					    (k09_operac = '34' or k09_operac = '35')";



  if ($limite != 0 ) {

     $sql = $sql . " limit $limite";

  }else{

     $sql = $sql . " order by k09_numpre";

  }		  

  global $result;		  

  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));

  if ($limite == 0 ) {

    if (pg_numrows($result) == 0 ){

      db_msgbox("Sem débitos a Pagar");
      db_logs("","$inscricao",0,"Nao Existem Debitos a Pagar de Divida Parcelada Alvara. Numero: $inscricao");

      redireciona("opcoesalvara.php?inscricao=".$inscricao);

    }

    db_logs("","$inscricao",0,"Existem Debitos a Pagar de Divida Parcelada Alvara. Numero: $inscricao");
	return 1;

  }else{

    if (pg_numrows($result) == 0 ){

      db_logs("","$inscricao",0,"Nao Existem Debitos a Pagar de Divida Parcelada Alvara. Numero: $inscricao");
      return 0;

	}else{

	  db_logs("","$inscricao",0,"Existem Debitos a Pagar de Divida Parcelada Alvara. Numero: $inscricao");
      return 1;

	}

  }

}



function debitos_diversosalvara($inscricao,$limite){



  $sql = "select *,fc_calcula(k05_numpre,k05_receit,'".date('Y-m-d')."','".db_vencimento()."',".date("Y").",'K05') from arrecad,operac

                   where k05_inscr = '$inscricao' and 

				         k05_operac = k02_codigo and

						 not substr(k05_numpre,1,8) in (select q01_numpre from isscalc where q01_inscr = '$inscricao') and

						 not substr(k05_numpre,1,8) in (select q01_npalv from isscalc where q01_inscr = '$inscricao') and

						 not substr(k05_numpre,1,8) in (select q01_numsan from isscalc where q01_inscr = '$inscricao')";

  if ($limite != 0 ) {

     $sql = $sql . " limit $limite";

  }else{

     $sql = $sql . " order by k05_numpre,k05_dtvenc";

  }		  

  global $result;		  

  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));

  if ($limite == 0 ) {

    if (pg_numrows($result) == 0 ){

      db_msgbox("Sem débitos a Pagar");
      db_logs("","$inscricao",0,"Nao Existem Debitos a Pagar de Diversos Alvara. Numero: $inscricao");

      redireciona("opcoesalvara.php?inscricao=".$inscricao);

      exit;

    }

    db_logs("","$inscricao",0,"Existem Debitos a Pagar de Diversos Alvara. Numero: $inscricao");
	return 1;

  }else{

    if (pg_numrows($result) == 0 ){

      db_logs("","$inscricao",0,"Nao Existem Debitos a Pagar de Diversos Alvara. Numero: $inscricao");
      return 0;

	}else{

	  db_logs("","$inscricao",0,"Existem Debitos a Pagar de Diversos Alvara. Numero: $inscricao");
      return 1;

	}

  }

}



function debitos_iptu($matricula,$limite,$anoexe){
  $sql = "select *,fc_calcula(k05_numpre,k05_receit,'".date('Y-m-d')."','".db_vencimento()."',".date("Y").",'K05') 
                   from arrecad,operac,iptucalc
                   where k05_matric = '$matricula' and 
				         k05_operac = k02_codigo and
                         substr(k05_numpre,1,8) = iptucalc.v23_numpre";
  if ( $anoexe != 0 ) {
     global $result_u;		  
     $sql = $sql . " and iptucalc.v23_anoexe = " . $anoexe ;
	 $sqlu = "select v23_numpre from iptucalc where v23_anoexe = $anoexe and v23_matric = '$matricula'";
     $result_u = db_query($sqlu) or die("Sql : ".pg_ErrorMessage($result_u));	  
	 $v23_numpre = pg_result($result_u,0,0);
	 $sqlu = "select fc_calcula_u('$v23_numpre','000','".date('Y-m-d')."','".db_vencimento()."',$anoexe,'K05') as fc_calcula";
     $result_u = db_query($sqlu) or die("Sql : ".pg_ErrorMessage($result_u));
  } 
  
  if ($limite != 0 ) {
     $sql = $sql . " limit $limite";
  }else{
     $sql = $sql . " order by k05_numpre,k05_dtvenc";
  }		  
  global $result;		  
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));
  if ($limite == 0 ) {
    if (pg_numrows($result) == 0 ){
      db_msgbox("Sem débitos a Pagar");
      db_logs("$matricula","",0,"Nao Existem Debitos a Pagar de IPTU Exercicio. Numero: $matricula");
      redireciona("opcoesimovel.php?matricula=".$matricula);
    }
    db_logs("$matricula","",0,"Existem Debitos a Pagar de IPTU Exercicio. Numero: $matricula");
	return 1;
  }else{
    if (pg_numrows($result) == 0 ){
      db_logs("$matricula","",0,"Nao Existem Debitos a Pagar de IPTU Exercicio. Numero: $matricula");
      return 0;
	}else{
	  db_logs("$matricula","",0,"Existem Debitos a Pagar de IPTU Exercicio. Numero: $matricula");
      return 1;
	}
  }
}




function debitos_iptu($matricula,$limite){
  $sql = "select *,fc_calcula(k05_numpre,k05_receit,'".date('Y-m-d')."','".db_vencimento()."',".date("Y").",'K05') from arrecad,operac,iptucalc
                   where k05_matric = '$matricula' and 
				         k05_operac = k02_codigo and
                         substr(k05_numpre,1,8) = iptucalc.v23_numpre";
  if ($limite != 0 ) {
     $sql = $sql . " limit $limite";
  }else{
     $sql = $sql . " order by k05_numpre,k05_dtvenc";
  }		  
  global $result;
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));
  if ($limite == 0 ) {
    if (pg_numrows($result) == 0 ){
      db_msgbox("Sem débitos a Pagar");
      db_logs("$matricula","",0,"Nao Existem Debitos a Pagar de IPTU Exercicio. Numero: $matricula");
      redireciona("opcoesimovel.php?matricula=".$matricula);
    }
    db_logs("$matricula","",0,"Existem Debitos a Pagar de IPTU Exercicio. Numero: $matricula");
	return 1;
  }else{
    if (pg_numrows($result) == 0 ){
      db_logs("$matricula","",0,"Nao Existem Debitos a Pagar de IPTU Exercicio. Numero: $matricula");
      return 0;
	}else{
	  db_logs("$matricula","",0,"Existem Debitos a Pagar de IPTU Exercicio. Numero: $matricula");
      return 1;
	}
  }
}



function debitos_divida($matricula,$limite){



  $sql = "select divpre.*,operac.*,divida.v01_exerc,divida.v01_proced,proced.*,

                           fc_calcula(k09_numpre,k09_receit,'".date('Y-m-d')."','".db_vencimento()."',".date("Y").",'K09') 

                   from divpre,operac,divida,proced

                   where k09_matric = '$matricula' and 

				         k09_operac = k02_codigo and 

						 k09_numpre = v01_numpre and

						 v01_proced = v03_codigo";

  if ($limite != 0 ) {

     $sql = $sql . " limit $limite";

  }else{

     $sql = $sql . " order by v01_proced,v01_exerc";

  }		  

  global $result;		  

  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));

  if ($limite == 0 ) {

    if (pg_numrows($result) == 0 ){

      db_msgbox("Sem débitos a Pagar");
      db_logs("$matricula","",0,"Nao Existem Debitos a Pagar de Divida Ativa Matricula. Numero: $matricula");

      redireciona("opcoesimovel.php?matricula=".$matricula);

    }

    db_logs("$matricula","",0,"Existem Debitos a Pagar de Divida Ativa Matricula. Numero: $matricula");
	return 1;

  }else{

    if (pg_numrows($result) == 0 ){

      db_logs("$matricula","",0,"Nao Existem Debitos a Pagar de Divida Ativa Matricula. Numero: $matricula");
      return 0;

	}else{

      db_logs("$matricula","",0,"Existem Debitos a Pagar de Divida Ativa Matricula. Numero: $matricula");
	  return 1;

	}

  }

}





function debitos_dividaparmat($matricula,$limite){



  $sql = "select *, fc_calcula(k09_numpre,k09_receit,'".date('Y-m-d')."','".db_vencimento()."',".date("Y").",'K09') from divpre,operac

                    where k09_matric = '$matricula' and 

	                      k09_operac = k02_codigo and 

   					    ( k09_operac = '34' or k09_operac = '35')";

  if ($limite != 0 ) {

     $sql = $sql . " limit $limite";

  }else{

     $sql = $sql . " order by k09_numpre";

  }		  

  global $result;		  

  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));

  if ($limite == 0 ) {

    if (pg_numrows($result) == 0 ){

      db_msgbox("Sem débitos a Pagar");
      db_logs("$matricula","",0,"Nao Existem Debitos a Pagar de Divida Parcelamento Matricula. Numero: $matricula");

      redireciona("opcoesimovel.php?matricula=".$matricula);

    }

    db_logs("$matricula","",0,"Existem Debitos a Pagar de Divida Parcelamento Matricula. Numero: $matricula");
	return 1;

  }else{

    if (pg_numrows($result) == 0 ){

      db_logs("$matricula","",0,"Nao Existem Debitos a Pagar de Divida Parcelamento Matricula. Numero: $matricula");
      return 0;

	}else{

	  db_logs("$matricula","",0,"Existem Debitos a Pagar de Divida Parcelamento Matricula. Numero: $matricula");
      return 1;

	}


  }

}





function debitos_diversosmatricula($matricula,$limite){



  $sql = "select arrecad.*,operac.*,iptucalc.v23_numpre,fc_calcula(k05_numpre,k05_receit,'".date('Y-m-d')."','".db_vencimento()."',".date("Y").",'K05') 

          from arrecad

		       left outer join iptucalc on k05_matric = '$matricula' and v23_matric = '$matricula' and substr(k05_numpre,1,8) = v23_numpre

		       ,operac

          where k05_matric = '$matricula' and 

			    k05_operac = k02_codigo and

				v23_numpre is null";

				



				

  if ($limite != 0 ) {

     $sql = $sql . " limit $limite";

  }else{

     $sql = $sql . " order by k05_numpre,k05_dtvenc";



  }		  

  global $result;		  

  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));

  if ($limite == 0 ) {

    if (pg_numrows($result) == 0 ){

      db_msgbox("Sem débitos a Pagar");
      db_logs("$matricula","",0,"Nao Existem Debitos a Pagar de Diversos Matricula. Numero: $matricula");

      redireciona("opcoesimovel.php?matricula=".$matricula);

      exit;

    }

    db_logs("$matricula","",0,"Existem Debitos a Pagar de Diversos Matricula. Numero: $matricula");
	return 1;

  }else{

    if (pg_numrows($result) == 0 ){

      db_logs("$matricula","",0,"Nao Existem Debitos a Pagar de Diversos Matricula. Numero: $matricula");
      return 0;

	}else{

	  db_logs("$matricula","",0,"Existem Debitos a Pagar de Diversos Matricula. Numero: $matricula");
      return 1;

	}
  }
}

function pagamento_fornecedor($cgccpf,$limite){
  $sql = "select e09_numemp,e09_data,e09_valor,e09_obs 
          from empenho,cgm,agenda
          where e01_numcgm = z01_numcgm 
		  and e09_numemp = e01_numemp
		  and e09_situac = 'f' 
		  and e09_data >= CURRENT_DATE
		  and trim(z01_cgccpf) = '$cgccpf'";

  if ($limite != 0 ) {
     $sql = $sql . " limit $limite";
  }else{
	 $sql = $sql . " order by e09_data";
  }		  
  global $result;		  
  $result = db_query($sql) or die("Sql : ".pg_ErrorMessage($result));
  if ($limite == 0 ) {
     if (pg_numrows($result) == 0 ){
        db_msgbox("Não existem pagamentos agendados.");
        db_logs("","",0,"Não existem pagamentos agendados. CGCCPF: $cgccpf");
        redireciona("opcoesfornecedor.php?inscricao=".$inscricao);
     }
     db_logs("","",0,"Existem pagamentos agendados. CGCCPF: $cgccpf");
	 return 1;
  }else{
    if (pg_numrows($result) == 0 ){
      db_logs("","",0,"Não existem pagamentos agendados. CGCCPF: $cgccpf");
    return 0;
	}else{
      db_logs("","",0,"Existem pagamentos agendados. CGCCPF: $cgccpf");
	  return 1;
	}
  }
}
*/

?>