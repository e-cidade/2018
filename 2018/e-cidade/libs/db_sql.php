<?php
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

function debitos_tipos_matricula($matricula,$instit=null) {

	if($instit==null){
		$instit=db_getsession('DB_instit');
	}

  $sql = "select distinct                                                                                \n";
  $sql.= "       arretipo.k00_tipo,                                                                      \n";
  $sql.= "       arretipo.k00_descr,                                                                     \n";
  $sql.= "       arretipo.k00_marcado,                                                                   \n";
  $sql.= "       (select j01_numcgm                                                                      \n";
  $sql.= "          from iptubase                                                                        \n";
  $sql.= "         where j01_matric = {$matricula}                                                       \n";
  $sql.= "         limit 1) as k00_numcgm,                                                               \n";
  $sql.= "       arretipo.k00_emrec,                                                                     \n";
  $sql.= "       arretipo.k00_agnum,                                                                     \n";
  $sql.= "       arretipo.k00_agpar                                                                      \n";
  $sql.= "  from arretipo                                                                                \n";
  $sql.= " where arretipo.k00_instit = {$instit}                                                         \n";
  $sql.= "   and exists (select arrematric.*                                                             \n";
  $sql.= "                 from arrematric                                                               \n";
  $sql.= "                      inner join arreinstit  on arreinstit.k00_numpre = arrematric.k00_numpre  \n";
  $sql.= "                                            and arreinstit.k00_instit = {$instit}              \n";
  $sql.= "                where arrematric.k00_matric = {$matricula}                                     \n";
  $sql.= "                  and exists (select arrecad.k00_numpre                                        \n";
  $sql.= "                                from arrecad                                                   \n";
  $sql.= "                               where arrecad.k00_numpre = arrematric.k00_numpre                \n";
  $sql.= "                                 and arrecad.k00_tipo   = arretipo.k00_tipo))                  \n";


//	echo "debitos_tipos_matricula : $sql <br>";
  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql <br> $sql");
  return (pg_numrows($result)==0?false:$result);
}

function debitos_tipos_inscricao($inscricao,$instit=null){
	if($instit==null){
		$instit=db_getsession('DB_instit');
	}
   $sql = "select distinct t.k00_tipo,t.k00_descr,t.k00_marcado,b.q02_numcgm as k00_numcgm,t.k00_emrec,t.k00_agnum,t.k00_agpar
          from arreinscr a
		           inner join arrecad i  on a.k00_numpre = i.k00_numpre
			         inner join arreinstit on arreinstit.k00_numpre = i.k00_numpre
			                              and arreinstit.k00_instit = $instit
		           inner join issbase b  on b.q02_inscr = a.k00_inscr
		           inner join arretipo t on t.k00_tipo = i.k00_tipo
          where a.k00_inscr = $inscricao ";

//	echo "debitos_tipos_inscricao : $sql <br>";
  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");

  return (pg_numrows($result)==0?false:$result);


}
function debitos_tipos_numcgm($numcgm,$instit=null ){
	if($instit==null){
		$instit=db_getsession('DB_instit');
	}
  $sql = "select distinct t.k00_tipo,t.k00_descr,t.k00_marcado,b.k00_numcgm,t.k00_emrec,t.k00_agnum,t.k00_agpar
          from arrenumcgm b
		inner join arrecad a  on b.k00_numpre = a.k00_numpre
		inner join arreinstit on arreinstit.k00_numpre = a.k00_numpre
		                     and arreinstit.k00_instit = $instit
		inner join arretipo t on t.k00_tipo = a.k00_tipo
		  where b.k00_numcgm = $numcgm ";

//	echo "debitos_tipos_matricula : $sql <br>";

  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");
  return (pg_numrows($result)==0?false:$result);
}

function debitos_tipos_numpre($numpre,$instit=null ){
	if($instit==null){
		$instit=db_getsession('DB_instit');
	}
  $sql = "select distinct t.k00_tipo,t.k00_descr,t.k00_marcado,b.k00_numcgm,t.k00_emrec,t.k00_agnum,t.k00_agpar
          from arrecad a
					  inner join arreinstit on arreinstit.k00_numpre = a.k00_numpre
						                     and arreinstit.k00_instit = $instit
	          inner join arrenumcgm b on a.k00_numpre = b.k00_numpre
	          ,arretipo t
		  where a.k00_numpre = $numpre and
		        a.k00_tipo   = t.k00_tipo limit 1";

//	echo "debitos_tipos_numpre : $sql <br>";

  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");
  return (pg_numrows($result)==0?false:$result);
}

function debitos_matricula($matricula,$limite,$tipo,$datausu,$anousu,$totaliza="",$totalizaordem="",$db_where="",$justific=false,$instit=null ){
	if($instit==null){
		$instit=db_getsession('DB_instit');
	}
$sql = "select   y.k00_inscr,
	               y.k00_matric,
  		           y.k00_numcgm ,
                 y.k00_tipo,
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
								 0::float8 as k00_valor,";
                 if($justific==true){
					          $sql .=" datajust,";
					       }
									  $sql .="
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


          select 0::integer as k00_inscr,
	               a.k00_matric,
  		           i.k00_numcgm ,
                 i.k00_tipo,
				         i.k00_receit ,
				         i.k00_numpre ,
				         i.k00_numpar ,
				         i.k00_numtot ,
				         i.k00_numdig ,";
       if($justific==true){
          $sql .=" datajust,";
       }
				  $sql .="
          tabrec.k02_descr, fc_calcula(i.k00_numpre,i.k00_numpar,i.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.")
		     from arrematric a
					inner join arrecad i on a.k00_numpre = i.k00_numpre
					inner join arreinstit on arreinstit.k00_numpre = i.k00_numpre
					                     and arreinstit.k00_instit = $instit ";

       if($justific==true){
          $sql .="
          left join ( select k28_sequencia,k28_arrejust,k28_numpre,k28_numpar,k27_dias,k27_data,(k27_data+k27_dias) as datajust
                                  from ( select max(k28_sequencia) as k28_sequencia,
                                                max(k28_arrejust) as k28_arrejust,
                                                k28_numpar,
                                                k28_numpre
                                           from arrejustreg
                                          inner join arrematric on arrematric.k00_numpre = arrejustreg.k28_numpre
                                          where arrematric.k00_matric = $matricula
                                           group by k28_numpre,
                                                    k28_numpar
                                       ) as subarrejust
                                       inner join arrejust on arrejust.k27_sequencia = subarrejust.k28_arrejust
                              ) as arrejustreg on arrejustreg.k28_numpre = i.k00_numpre
                                              and arrejustreg.k28_numpar = i.k00_numpar ";
        }
         $sql .="
					,histcalc
					,tabrec
					inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
          where k00_hist   = k01_codigo and
	              a.k00_matric = $matricula and
		            k00_receit = k02_codigo
		"
   ;
  if($tipo != 0){
  	$sql .= " and k00_tipo   = ".$tipo;
  }
  $sql .= " order by k00_numpre,k00_numpar,k00_receit";
  if ($limite != 0 ) {
     $sql = $sql . " limit ".$limite;
  }
  $sql .= ") as x
   ) as y,arrecad,histcalc
   where y.k00_numpre = arrecad.k00_numpre and
         (y.k00_numpar = arrecad.k00_numpar or y.k00_numpar = 0)and
		 (y.k00_receit = arrecad.k00_receit or y.k00_receit = 0)and
		 arrecad.k00_hist = histcalc.k01_codigo
	   		" ;
		if ($db_where!=""){
			$sql .= $db_where;
		}
				$sql .= "
   group by  y.k00_inscr,
		         y.k00_matric,
		  		   y.k00_numcgm ,
				     y.k00_tipo,
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
             y.vlrdesconto,";
				if($justific==true){
           $sql .="  datajust,";
				}
           $sql .="   y.total
   ";
  if($totaliza!=""){
    $sql = "select $totaliza,sum(y.vlrhis) as vlrhis,
                             sum(y.vlrcor) as vlrcor,
                             sum(y.vlrjuros) as vlrjuros,
                             sum(y.vlrmulta) as vlrmulta,
                             sum(y.vlrdesconto) as vlrdesconto,
                             sum(y.total) as total
                        from ( ".$sql." ) y
                        group by $totaliza";

    if($totalizaordem!=""){
      $sql .= " order by $totalizaordem";
    }else{
      $sql .= " order by $totaliza";
    }
  }else {
    if($totalizaordem!=""){
      $sql .= " order by $totalizaordem";
    }else{
      $sql .= " order by k00_numpre,k00_numpar, k00_receit";
    }
  }

	//echo "debitos_matricula : $sql <br>";
  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");
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

function debitos_inscricao($inscricao,$limite,$tipo,$datausu,$anousu,$totaliza="",$totalizaordem="",$db_where="",$justific=false,$instit=null){
	if($instit==null){
		$instit=db_getsession('DB_instit');
	}
$sql = "select   y.k00_inscr,
	               y.k00_matric,
  		           y.k00_numcgm ,
		             y.k00_tipo,
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
								 0 as k00_valor,";
if($justific==true){
  $sql .=" datajust, ";
}
				$sql .="
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
		             i.k00_tipo,
				         i.k00_receit ,
				         i.k00_numpre ,
				         i.k00_numpar ,
				         i.k00_numtot ,
				         i.k00_numdig ,";
				if($justific==true){
				  $sql .=" datajust, ";
				}
				$sql .="
		             tabrec.k02_descr,fc_calcula(i.k00_numpre,i.k00_numpar,i.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.")
          from arreinscr a
		inner join arrecad i on a.k00_numpre = i.k00_numpre
		inner join arreinstit on arreinstit.k00_numpre = i.k00_numpre
		                     and arreinstit.k00_instit = $instit
		";
if($justific==true){
  			$sql .= "  left join ( select k28_sequencia,k28_arrejust,k28_numpre,k28_numpar,k27_dias,k27_data, (k27_data+k27_dias) as datajust
                                  from ( select max(k28_sequencia) as k28_sequencia,
                                                max(k28_arrejust) as k28_arrejust,
                                                k28_numpar,
                                                k28_numpre
                                           from arrejustreg
                                          inner join arreinscr on arreinscr.k00_numpre = arrejustreg.k28_numpre
                                          where arreinscr.k00_inscr = $inscricao
                                           group by k28_numpre,
                                                    k28_numpar
                                       ) as subarrejust
                                       inner join arrejust on arrejust.k27_sequencia = subarrejust.k28_arrejust
                              ) as arrejustreg on arrejustreg.k28_numpre = i.k00_numpre
                                              and arrejustreg.k28_numpar = i.k00_numpar";
}
        $sql .="
		,histcalc,
		tabrec
		inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
 where k00_hist   = k01_codigo and
		a.k00_inscr = $inscricao and
		k00_receit = k02_codigo " ;
  if($tipo != 0){
	$sql .= " and k00_tipo   = ".$tipo;
  }
  $sql .= " order by k00_numpre,k00_numpar,k00_receit";
  if ($limite != 0 ) {
     $sql .= " limit ".$limite;
  }
  $sql .= ") as x
   ) as y,arrecad,histcalc
   where 	y.k00_numpre = arrecad.k00_numpre and
         	y.k00_numpar = arrecad.k00_numpar and
	 	y.k00_receit = arrecad.k00_receit and
	 	arrecad.k00_hist = histcalc.k01_codigo
" ;
		if ($db_where!=""){
			$sql .= $db_where;
		}
				$sql .= "
   group by	y.k00_inscr,
		y.k00_matric,
		y.k00_numcgm ,
		y.k00_tipo ,
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
		y.vlrdesconto,";
		if($justific==true){
		  $sql .=" datajust, ";
		}
		$sql .="
		y.total
   ";
  if($totaliza!=""){
    $sql = "select $totaliza,sum(y.vlrhis) as vlrhis,
                             sum(y.vlrcor) as vlrcor,
                             sum(y.vlrjuros) as vlrjuros,
                             sum(y.vlrmulta) as vlrmulta,
                             sum(y.vlrdesconto) as vlrdesconto,
                             sum(y.total) as total
                        from ( ".$sql." ) y
                        group by $totaliza";


    if($totalizaordem!=""){
      $sql .= " order by $totalizaordem";
    }else{
      $sql .= " order by $totaliza";
    }
  }else {
    if($totalizaordem!=""){
      $sql .= " order by $totalizaordem";
    }else{
      $sql .= " order by k00_numpre,k00_numpar,k00_receit";
    }
  }
  //echo "$sql";
	//echo "debitos_inscricao : $sql <br>";
  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");
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

function debitos_inscricao_retido($inscricao,$limite,$tipo,$datausu,$anousu,$totaliza="",$totalizaordem="",$db_where="",$justific=false,$instit=null){
	if($instit==null){
		$instit=db_getsession('DB_instit');
	}
$sql = "select   y.k00_inscr,
	               y.k00_matric,
  		           y.k00_numcgm ,
		             y.k00_tipo,
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
								 0 as k00_valor,";
if($justific==true){
  $sql .=" datajust, ";
}
				$sql .="
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
		             i.k00_tipo,
				         i.k00_receit ,
				         i.k00_numpre ,
				         i.k00_numpar ,
				         i.k00_numtot ,
				         i.k00_numdig ,";
				if($justific==true){
				  $sql .=" datajust, ";
				}
				$sql .="
		             tabrec.k02_descr,fc_calcula(i.k00_numpre,i.k00_numpar,i.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.")
          from arreinscr a
		inner join arrecad i on a.k00_numpre = i.k00_numpre
		inner join arreinstit on arreinstit.k00_numpre = i.k00_numpre
		                     and arreinstit.k00_instit = $instit
		left	join issplan on q20_numpre = a.k00_numpre and q20_situacao <> 5
		left  join issplanit on q20_planilha = q21_planilha and q21_status = 1	";
if($justific==true){
  			$sql .= "  left join ( select k28_sequencia,k28_arrejust,k28_numpre,k28_numpar,k27_dias,k27_data, (k27_data+k27_dias) as datajust
                                  from ( select max(k28_sequencia) as k28_sequencia,
                                                max(k28_arrejust) as k28_arrejust,
                                                k28_numpar,
                                                k28_numpre
                                           from arrejustreg
                                          inner join arreinscr on arreinscr.k00_numpre = arrejustreg.k28_numpre
                                          where arreinscr.k00_inscr = $inscricao
                                           group by k28_numpre,
                                                    k28_numpar
                                       ) as subarrejust
                                       inner join arrejust on arrejust.k27_sequencia = subarrejust.k28_arrejust
                              ) as arrejustreg on arrejustreg.k28_numpre = i.k00_numpre
                                              and arrejustreg.k28_numpar = i.k00_numpar";
}
        $sql .="
		,histcalc,
		tabrec
		inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
 where k00_hist   = k01_codigo and
		a.k00_inscr = $inscricao and
		k00_receit = k02_codigo " ;
  if($tipo != 0){
	$sql .= " and k00_tipo   = ".$tipo;
  }
  $sql .= " order by k00_numpre,k00_numpar,k00_receit";
  if ($limite != 0 ) {
     $sql .= " limit ".$limite;
  }
  $sql .= ") as x
   ) as y,arrecad,histcalc
   where 	y.k00_numpre = arrecad.k00_numpre and
         	y.k00_numpar = arrecad.k00_numpar and
	 	y.k00_receit = arrecad.k00_receit and
	 	arrecad.k00_hist = histcalc.k01_codigo
" ;
		if ($db_where!=""){
			$sql .= $db_where;
		}
				$sql .= "
   group by	y.k00_inscr,
		y.k00_matric,
		y.k00_numcgm ,
		y.k00_tipo ,
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
		y.vlrdesconto,";
		if($justific==true){
		  $sql .=" datajust, ";
		}
		$sql .="
		y.total
   ";
  if($totaliza!=""){
    $sql = "select $totaliza,sum(y.vlrhis) as vlrhis,
                             sum(y.vlrcor) as vlrcor,
                             sum(y.vlrjuros) as vlrjuros,
                             sum(y.vlrmulta) as vlrmulta,
                             sum(y.vlrdesconto) as vlrdesconto,
                             sum(y.total) as total
                        from ( ".$sql." ) y
                        group by $totaliza";


    if($totalizaordem!=""){
      $sql .= " order by $totalizaordem";
    }else{
      $sql .= " order by $totaliza";
    }
  }else {
    if($totalizaordem!=""){
      $sql .= " order by $totalizaordem";
    }else{
      $sql .= " order by k00_numpre,k00_numpar,k00_receit";
    }
  }

  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");
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

function debitos_numpre_old($numpre,$limite,$tipo,$datausu,$anousu,$numpar=0,$totaliza="",$totalizaordem="",$instit=null ){
	if($instit==null){
		$instit=db_getsession('DB_instit');
	}

$sql = "select   y.k00_inscr,
	         y.k00_matric,
  	 	 y.k00_numcgm ,
		 y.k00_receit ,
                 y.k00_tipo,
                 y.k00_tipojm,
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
		 min(k01_descr) as k01_descr,
     max(k02_drecei) as k02_drecei
       from (
             select distinct *,
                 substr(fc_calculaold,2,13)::float8 as vlrhis,
                 substr(fc_calculaold,15,13)::float8 as vlrcor,
                 substr(fc_calculaold,28,13)::float8 as vlrjuros,
                 substr(fc_calculaold,41,13)::float8 as vlrmulta,
                 substr(fc_calculaold,54,13)::float8 as vlrdesconto,
                 (substr(fc_calculaold,15,13)::float8+
                 substr(fc_calculaold,28,13)::float8+
                 substr(fc_calculaold,41,13)::float8-
                 substr(fc_calculaold,54,13)::float8) as total
          from (
          select i.k00_inscr,
	         m.k00_matric,
  		 a.k00_numcgm ,
	         a.k00_receit ,
                 a.k00_tipo,
                 a.k00_tipojm,
	         a.k00_numpre ,
	         a.k00_numpar ,
	         a.k00_numtot ,
	         a.k00_numdig ,
		 tabrec.k02_descr,tabrec.k02_drecei,fc_calculaold(a.k00_numpre,a.k00_numpar,a.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.")
          from arreold a
					inner join arreinstit on arreinstit.k00_numpre = a.k00_numpre
					                     and arreinstit.k00_instit = $instit
		left outer join arreinscr  i on a.k00_numpre = i.k00_numpre
	     	left outer join arrematric m on a.k00_numpre = m.k00_numpre
	 	,histcalc,tabrec  inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
          where k00_hist   = k01_codigo and
		a.k00_numpre = $numpre and
		k00_receit = k02_codigo";
  if($numpar!=0){
    $sql .= " and k00_numpar = $numpar ";
  }

  if($tipo != 0){
	$sql .= " and k00_tipo   = ".$tipo;
  }
  $sql = $sql . " order by k00_numpre,k00_numpar";
  if ($limite != 0 ) {
     $sql .= " limit ".$limite;
  }
  $sql .= ") as x
   ) as y,arreold,histcalc
   where y.k00_numpre = arreold.k00_numpre and
         y.k00_numpar = arreold.k00_numpar and
		 y.k00_receit = arreold.k00_receit and
		 arreold.k00_hist = histcalc.k01_codigo
   group by      y.k00_inscr,
	         y.k00_matric,
  		 y.k00_numcgm ,
	         y.k00_receit ,
 	         y.k00_tipo,
                 y.k00_tipojm,
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
   ";
  if($totaliza!=""){
    $sql = "select $totaliza,sum(y.vlrhis) as vlrhis,
                             sum(y.vlrcor) as vlrcor,
                             sum(y.vlrjuros) as vlrjuros,
                             sum(y.vlrmulta) as vlrmulta,
                             sum(y.vlrdesconto) as vlrdesconto,
                             sum(y.total) as total
                        from ( ".$sql." ) y
                        group by $totaliza";

    if($totalizaordem!=""){
      $sql .= " order by $totalizaordem";
    }else{
      $sql .= " order by $totaliza";
    }
  }else {
    if($totalizaordem!=""){
     $sql .= " order by $totalizaordem";
    }else{
     $sql .= " order by k00_numpre,k00_numpar";
    }
  }
//	echo "debitos_numpre_old : $sql <br>";
  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");
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


function debitos_numpre($numpre,$limite,$tipo,$datausu,$anousu,$numpar=0,$totaliza="",$totalizaordem="",$db_where="",$justific=false,$instit=null ){
	if($instit==null){
		$instit=db_getsession('DB_instit');
	}
 $sql = "select
  	 	           y.k00_numcgm ,
		             y.k00_receit ,
		             min(y.k00_hist) as k00_hist,
                 y.k00_tipo,
                 coalesce( y.k00_tipojm, 0) as k00_tipojm,
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
								 max(k01_descr) as k01_descr, ";
				if($justific==true){
				$sql .=" datajust, ";
				}
				$sql .="
								 max(k02_drecei) as k02_drecei
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
          select 	 a.k00_numcgm ,
	                 a.k00_receit ,
					         a.k00_hist,
                   a.k00_tipo,
                   coalesce( a.k00_tipojm, 0) as k00_tipojm,
					         a.k00_numpre ,
					         a.k00_numpar ,
					         a.k00_numtot ,
					         a.k00_numdig , ";
        if($justific==true){
				  $sql .=" datajust, ";
				}
				  $sql .="
		               tabrec.k02_descr,tabrec.k02_drecei,fc_calcula(a.k00_numpre,a.k00_numpar,a.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.")
          from arrecad a
					inner join arreinstit on arreinstit.k00_numpre = a.k00_numpre
					                     and arreinstit.k00_instit = $instit


					";
if($justific==true){
 $sql .="
          left join ( select k28_sequencia,k28_arrejust,k28_numpre,k28_numpar,k27_dias,k27_data, (k27_data+k27_dias) as datajust
                                  from ( select max(k28_sequencia) as k28_sequencia,
                                                max(k28_arrejust) as k28_arrejust,
                                                k28_numpar,
                                                k28_numpre
                                           from arrejustreg
                                          where arrejustreg.k28_numpre = $numpre
                                           group by k28_numpre,
                                                    k28_numpar
                                       ) as subarrejust
                                       inner join arrejust on arrejust.k27_sequencia = subarrejust.k28_arrejust
                              ) as arrejustreg on arrejustreg.k28_numpre = a.k00_numpre
                                              and arrejustreg.k28_numpar = a.k00_numpar";
}
 $sql .="
	 	           ,histcalc,tabrec
               inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
          where   k00_hist   = k01_codigo and
	             	a.k00_numpre = $numpre and
		              k00_receit = k02_codigo";
  if($numpar!=0){
    $sql .= " and k00_numpar = $numpar ";
  }

  if($tipo != 0){
	$sql .= " and k00_tipo   = ".$tipo;
  }
  $sql = $sql . " order by k00_numpre,k00_numpar,k00_receit";
  if ($limite != 0 ) {
     $sql .= " limit ".$limite;
  }
  $sql .= ") as x
   ) as y,arrecad,histcalc
   where y.k00_numpre = arrecad.k00_numpre and
         y.k00_numpar = arrecad.k00_numpar and
		 y.k00_receit = arrecad.k00_receit and
		 arrecad.k00_hist = histcalc.k01_codigo
" ;
		if ($db_where!=""){
			$sql .= $db_where;
		}
				$sql .= "
   group by  y.k00_numcgm ,
		         y.k00_receit ,
	 	         y.k00_tipo,
             y.k00_tipojm,
				     y.k00_numpre ,
		         y.k00_numpar ,
		         y.k00_numtot ,
		         y.k00_numdig ,
				     y.k02_descr,
             y.vlrhis,
             y.vlrcor,
             y.vlrjuros,
             y.vlrmulta,
             y.vlrdesconto,";
        if($justific==true){
				  $sql .=" datajust, ";
				}
				  $sql .="
             y.total
   ";
  if($totaliza!=""){
    $sql = "select $totaliza,sum(y.vlrhis) as vlrhis,
                             sum(y.vlrcor) as vlrcor,
                             sum(y.vlrjuros) as vlrjuros,
                             sum(y.vlrmulta) as vlrmulta,
                             sum(y.vlrdesconto) as vlrdesconto,
                             sum(y.total) as total
                        from ( ".$sql." ) y
                        group by $totaliza";

    if($totalizaordem!=""){
      $sql .= " order by $totalizaordem";
    }else{
      $sql .= " order by $totaliza";
    }
  }else {
    if($totalizaordem!=""){
     $sql .= " order by $totalizaordem";
    }else{
     $sql .= " order by k00_numpre,k00_numpar,k00_receit";
    }
  }

  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");
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

function retornaRegraDescontoParcelamento($sNumpre) {

  $sSql = "select k38_cadtipoparc from arredesconto where k38_numpre = $sNumpre";
  $rRes = db_query($sSql) or die("Erro(28) não encontrado no arredesconto: ".pg_errormessage());

  if (pg_numrows($rRes) > 0) {

    // se existe regra cadastrada gera a variavel
    return $regra_desconto = pg_result($rRes, 0, 0);
  }

  return 0;
}

function debitos_numpre_carne($numpre,$numpar,$datausu,$anousu,$instit=null,$DB_DATACALC=null,$forcarvencimento=false ){

  global $k03_numpre,$k00_dtvenc;

	if($instit==null){
		$instit=db_getsession('DB_instit');
	}
  // verifica se existe regra de desconto cadastrada para o numpre que será gerado o carne
  $sql = "select k38_cadtipoparc from arredesconto where k38_numpre = $numpre";
  $res = db_query($sql) or die("Erro(28) não encontrado no arredesconto: ".pg_errormessage());

  if (pg_numrows($res) > 0) {

    // se existe regra cadastrada gera a variavel
    $regra_desconto = pg_result($res,0,0);

    try {
    	$oRecibo = new recibo(2,null);
      $oRecibo->addNumpre($numpre,$numpar);
      $oRecibo->setDescontoReciboWeb($numpre, $numpar, $regra_desconto);
    } catch ( Exception $eException ) {
      db_fim_transacao(true);
      db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
      exit;
    }

    /* REGRAS PARA DATA DE CALCULO */
    $aDebitosRecibo = $oRecibo->getDebitosRecibo();
    $minvenc        = "";
    if (isset($forcarvencimento) && $forcarvencimento == 'true') {

      $minvenc = date("Y-m-d",$DB_DATACALC);
      $exerc   = substr($minvenc,0,4);

    } else {

	    foreach ( $aDebitosRecibo as $oDebito ) {

	      $sSqlVenc  = " select min(k00_dtvenc) as k00_dtvenc      ";
	      $sSqlVenc .= "    from arrecad                           ";
	      $sSqlVenc .= " where k00_numpre = {$oDebito->k00_numpre} ";
	      $sSqlVenc .= "   and k00_numpar = {$oDebito->k00_numpar} ";

	      $rsVencimento = db_query($sSqlVenc);
	      $dtDataVenc   = db_utils::fieldsMemory($rsVencimento,0)->k00_dtvenc;

	      if ( $dtDataVenc < $minvenc or $minvenc == "" ) {
	        $minvenc = $dtDataVenc;
	      }

	    }

      $exerc = substr($minvenc,0,4);
      /* se o menor vencimento do numpre for menor que a data para pagamento(data informada na CGF) menor vencimento = data para pagamento */
      if ($minvenc < date("Y-m-d",$DB_DATACALC)) {
        $minvenc = date("Y-m-d",$DB_DATACALC);
      }
      /* se menor vencimento do numpre for maior que 31-12 do ano corrente menor vencimento = 31-12 do ano corrente */
      if ($minvenc > db_getsession('DB_anousu')."-12-31") {
        $minvenc = db_getsession('DB_anousu')."-12-31";
      }
    }

    db_inicio_transacao();

    try {

      $oRecibo->setDataRecibo($minvenc);
      $oRecibo->setDataVencimentoRecibo($minvenc);
      $oRecibo->setExercicioRecibo(substr($minvenc,0,4));
      $oRecibo->emiteRecibo();
      $k03_numpre = $oRecibo->getNumpreRecibo();

    } catch ( Exception $eException ) {

      db_fim_transacao(true);
      db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
      exit;

    }

    db_fim_transacao();

    // db_criatabela($Recibo);
    // verifica e calcula as variaveis receita e vlrtot
    // receita = é necessário para podermos verificar qual inflator utilizar e calcular a quantidade de inflatores a ser impressa no
    //           carne no momento da baixa do banco neste caso devemos verificar a baixa de banco para possibilitar a baixa pelo
    //           numpre do arrecad, quando o valor do banco for diferente do recibopaga gerado.
    // vlrtot  = valor total gerado no recibopaga para calcular a quantidade de inflator
    $sql = "select * from (
            select arrecad.k00_receit as receita
            from recibopaga
                 inner join arrecad on arrecad.k00_numpre = recibopaga.k00_numpre
            where k00_numnov = $k03_numpre
            limit 1) as x
            union all
            select sum(k00_valor) as vlrtot
            from recibopaga
            where k00_numnov = $k03_numpre";

    $res = db_query($sql);
    if(pg_numrows($res)==0){
      echo "Erro ao gerar recibo. Contate suporte (erro:9998)";
      exit;
    }
    $receita = pg_result($res,0,0);
    $vlrtot  = pg_result($res,1,0);

    // pesquisa qual inflator gerar o calculo da quantidade a ser impressa no carne
    $sql_inflator = "select k02_corr
                     from tabrec
                          inner join tabrecjm on tabrec.k02_codjm = tabrecjm.k02_codjm
                     where k02_codigo = $receita";
    $res = db_query($sql_inflator);
    if(pg_numrows($res)==0){
      echo "Inflator não cadastrado para receita ($receita). Contate suporte (erro:9999)";
      exit;
    }

    $inflator = pg_result($res,0,0);
    // pesquisa a quantidade de inflator a ser gerado
    $res = db_query(" select fc_vlinf('".$inflator."','$minvenc')");
    if(pg_numrows($res)==0){
      echo "Não encontrado valor para o Inflator ($inflator) na data ($minvenc). Contate suporte (erro:9997)";
      exit;
    }

    $v_calculoinfla = pg_result($res,0,0);

    if ( $v_calculoinfla == 0 ){
       $vlrinflator = 0;
    } else {
       $vlrinflator = round($vlrtot/$v_calculoinfla,6);
    }
    $sql = "select k00_numpre,
                   k00_numpar,
                   0 as vlhist,
                   sum(k00_valor) as vlrcor,
                   0 as vlrjuros,
                   0 as vlrmulta,
                   0 as vlrdesconto,
                   sum(k00_valor) as total,
                   $vlrinflator as qinfla ,
                   '$inflator' as ninfla
         from recibopaga
         where k00_numnov = $k03_numpre
         group by k00_numpre,k00_numpar
                    ";

  } else {

    $sql = "select   k00_numpre,
                   k00_numpar,
                   sum(y.vlrhis) as vlrhist,
                   sum(y.vlrcor) as vlrcor,
                   sum(y.vlrjuros) as vlrjuros,
                   sum(y.vlrmulta) as vlrmulta,
                   sum(y.vlrdesconto) as vlrdesconto,
                   sum(y.total) as total,
                   sum(y.qinfla) as qinfla ,
                   min(y.ninfla) as ninfla
         from (
               select distinct k00_numpre,k00_numpar, k00_receit,
                   substr(fc_calcula,2,13)::float8 as vlrhis,
                   substr(fc_calcula,15,13)::float8 as vlrcor,
                   substr(fc_calcula,28,13)::float8 as vlrjuros,
                   substr(fc_calcula,41,13)::float8 as vlrmulta,
                   substr(fc_calcula,54,13)::float8 as vlrdesconto,
                   (substr(fc_calcula,15,13)::float8+
                   substr(fc_calcula,28,13)::float8+
                   substr(fc_calcula,41,13)::float8-
                   substr(fc_calcula,54,13)::float8) as total,
                   substr(fc_calcula,77,17)::float8 as qinfla,
                   substr(fc_calcula,94,4)::varchar(5) as ninfla
            from (select arrecad.k00_numpre,
                         k00_numpar,
                         k00_receit,
                         fc_calcula(arrecad.k00_numpre,k00_numpar,k00_receit,case when k00_dtvenc > '".db_vencimento($datausu)."' then k00_dtvenc else '".db_vencimento($datausu)."' end, case when k00_dtvenc > '".db_vencimento($datausu)."' then k00_dtvenc else '".db_vencimento($datausu)."' end, ".$anousu.")
                    from arrecad
                         inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
                                              and arreinstit.k00_instit = $instit
                   where arrecad.k00_numpre = $numpre
                     and k00_numpar = $numpar ) as x
         ) as y
           group by k00_numpre,
                    k00_numpar ";

    // echo "debitos_numpre_carne : $sql <br>";
    // die("debitos_numpre_carne : $sql");

  }

  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");
  if (pg_numrows($result) == 0 ){
     return false;
  }
  return $result;

}


function debitos_numcgm($numcgm,$limite,$tipo,$datausu,$anousu,$totaliza="",$totalizaordem="",$db_where="",$justific=false,$instit=null ){
	if($instit==null){
		$instit=db_getsession('DB_instit');
	}

  $sql = "select y.k00_numcgm ,
                 y.k00_tipo,
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
                 min(k01_descr) as k01_descr,";
					if($justific==true){
					  $sql .= " y.datajust,";
					}
					$sql .= "
                 y.k00_origem

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
                          select a.k00_tipo,
                                 b.k00_numcgm ,
                                 a.k00_receit ,
                                 a.k00_numpre ,
                                 a.k00_numpar ,
                                 a.k00_numtot ,
                                 a.k00_numdig ,
                                 tabrec.k02_descr,";
										if($justific==true){
					            $sql .= "  arrejustreg.datajust,";
					          }
					          $sql .= "    fc_calcula(a.k00_numpre,a.k00_numpar,a.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu."),
                                 case
                                   when (select min(k00_matric) from arrematric where arrematric.k00_numpre = b.k00_numpre) is not null then 'M-'||(select min(k00_matric) from arrematric where arrematric.k00_numpre = b.k00_numpre)
                                   when (select min(k00_inscr) from arreinscr where arreinscr.k00_numpre = b.k00_numpre) is not null then 'I-'||(select min(k00_inscr) from arreinscr where arreinscr.k00_numpre = b.k00_numpre)
                                   else 'C-'||a.k00_numcgm
                                 end as k00_origem
                            from arrenumcgm b
                      inner join arrecad a  on a.k00_numpre          = b.k00_numpre
											inner join arreinstit on arreinstit.k00_numpre = a.k00_numpre
											                     and arreinstit.k00_instit = $instit
                      inner join histcalc   on histcalc.k01_codigo   = a.k00_hist
                      inner join tabrec     on tabrec.k02_codigo     = a.k00_receit
                      inner join tabrecjm   on tabrecjm.k02_codjm    = tabrec.k02_codjm";
											if($justific==true){
												  $sql .= "
												           left join ( select k28_sequencia,k28_arrejust,k28_numpre,k28_numpar,k27_dias,k27_data,(k27_data+k27_dias) as datajust
												                                  from ( select max(k28_sequencia) as k28_sequencia,
												                                                max(k28_arrejust) as k28_arrejust,
												                                                k28_numpar,
												                                                k28_numpre
												                                           from arrejustreg
												                                           inner join arrenumcgm on arrenumcgm.k00_numpre = arrejustreg.k28_numpre
                                                                   where arrenumcgm.k00_numcgm = $numcgm
												                                           group by k28_numpre,
												                                                    k28_numpar
												                                       ) as subarrejust
												                                       inner join arrejust on arrejust.k27_sequencia = subarrejust.k28_arrejust
												                              ) as arrejustreg on arrejustreg.k28_numpre = a.k00_numpre
												                                              and arrejustreg.k28_numpar = a.k00_numpar

												          ";
											}
                    $sql .= "      where b.k00_numcgm = $numcgm" ;
  if ($tipo != 0) {
    $sql .= " and k00_tipo   = ".$tipo;
  }
  $sql = $sql . " order by k00_numpre,k00_numpar,k00_receit";
  if ($limite != 0 ) {
     $sql .= " limit ".$limite;
  }
  $sql .= "    ) as x
           ) as y
           inner join arrecad  on arrecad.k00_numpre = y.k00_numpre
                              and arrecad.k00_numpar = y.k00_numpar
                              and arrecad.k00_receit = y.k00_receit
					 inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
					                      and arreinstit.k00_instit = $instit
           inner join histcalc on histcalc.k01_codigo = arrecad.k00_hist
" ;
		if ($db_where!=""){
			$sql .= " where 1=1 ".$db_where;
		}
				$sql .= "
             group by y.k00_numcgm ,
                      y.k00_tipo,
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
                      y.total,";
        if($justific==true){
           $sql .="   y.datajust,";
        }
        $sql .="      y.k00_origem";

  if ($totaliza!="") {
    $sql = "select $totaliza,
                   sum(y.vlrhis) as vlrhis,
                   sum(y.vlrcor) as vlrcor,
                   sum(y.vlrjuros) as vlrjuros,
                   sum(y.vlrmulta) as vlrmulta,
                   sum(y.vlrdesconto) as vlrdesconto,
                   sum(y.total) as total
              from ( ".$sql." ) y
          group by $totaliza";

    if ($totalizaordem!="") {
      $sql .= " order by $totalizaordem";
    } else {
      $sql .= " order by $totaliza";
    }
  }else {
    if($totalizaordem!=""){
      $sql .= " order by $totalizaordem";
    }else{
      $sql .= " order by k00_numpre,k00_numpar,k00_receit";
    }
  }

  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");
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

function debitos_numcgm_var($numcgm,$limite,$tipo,$datausu,$anousu,$totaliza="",$justific=false,$instit=null ){
	if($instit==null){
		$instit=db_getsession('DB_instit');
	}
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
          from ( select q05_aliq,''::bpchar as k00_matric,v.q05_vlrinf as valor_variavel,a.k00_inscr,i.*,histcalc.*,tabrec.k02_descr,";
                        if($justific==true){
                          $sql .="datajust, ";
                        }
                        $sql .=" fc_calcula(i.k00_numpre,i.k00_numpar,i.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.")
	from arrenumcgm b
		inner join arrecad i on i.k00_numpre = b.k00_numpre
		inner join arreinstit on arreinstit.k00_numpre = i.k00_numpre
		                     and arreinstit.k00_instit = $instit
		left outer join arreinscr a on a.k00_numpre = i.k00_numpre
		left outer join issvar v on v.q05_numpre = i.k00_numpre
                            and v.q05_numpar = i.k00_numpar";
    if($justific==true){
       $sql .="
    left join ( select k28_sequencia,k28_arrejust,k28_numpre,k28_numpar,k27_dias,k27_data,(k27_data+k27_dias) as datajust
                                  from ( select max(k28_sequencia) as k28_sequencia,
                                                max(k28_arrejust) as k28_arrejust,
                                                k28_numpar,
                                                k28_numpre
                                           from arrejustreg
                                          inner join arrenumcgm on arrenumcgm.k00_numpre = arrejustreg.k28_numpre
                                          where arrenumcgm.k00_numcgm = $numcgm
                                           group by k28_numpre,
                                                    k28_numpar
                                       ) as subarrejust
                                       inner join arrejust on arrejust.k27_sequencia = subarrejust.k28_arrejust
                              ) as arrejustreg on arrejustreg.k28_numpre = i.k00_numpre
                                              and arrejustreg.k28_numpar = i.k00_numpar";
    }
    $sql .="
		,histcalc,tabrec
    inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
 where k00_hist     = k01_codigo and
		    b.k00_numcgm = $numcgm and
		    k00_receit   = k02_codigo ";
  if($tipo != 0){
	$sql .= " and k00_tipo   = ".$tipo;
  }
  $sql .= " order by k00_numpre,k00_numpar,k00_dtvenc";
  if ($limite != 0 ) {
     $sql .= " limit ".$limite;
  }
  $sql .= ") as x";
  if($totaliza!=""){
    $sql = "select $totaliza,sum(y.vlrhis) as vlrhis,
                             sum(y.vlrcor) as vlrcor,
                             sum(y.vlrjuros) as vlrjuros,
                             sum(y.vlrmulta) as vlrmulta,
                             sum(y.vlrdesconto) as vlrdesconto,
                             sum(y.total) as total
			from ( ".$sql." ) y
			group by $totaliza";
  }
  //echo "sql = $sql";
//	echo "debitos_numcgm_var : $sql <br>";
  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");
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

function debitos_numcgm_var_cometado($numcgm,$limite,$tipo,$datausu,$anousu,$instit=null){
	if($instit==null){
		$instit=db_getsession('DB_instit');
	}
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
          select v.k00_valor as valor_variavel,i.k00_inscr,m.k00_matric,a.*,
	         histcalc.*,tabrec.k02_descr,
                 fc_calcula(a.k00_numpre,a.k00_numpar,a.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.")
          from arrecad a
					inner join arreinstit on arreinstit.k00_numpre = a.k00_numpre
					                     and arreinstit.k00_instit = $instit
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
//echo  $sql = $sql . " order by k00_numpre,k00_numpar,k00_dtvenc";
  if ($limite != 0 ) {
     $sql .= " limit ".$limite;
  }
  $sql .= ") as x";
  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");
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
function debitos_numpre_var($numpre,$limite,$tipo,$datausu,$anousu,$justific=false,$instit=null ){
	if($instit==null){
		$instit=db_getsession('DB_instit');
	}
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
          select q05_aliq,''::bpchar as k00_matric,v.q05_vlrinf as valor_variavel,a.k00_inscr,i.*,histcalc.*,tabrec.k02_descr, ";
               if($justific==true){
                 $sql .="datajust, ";
               }
                 $sql .="
                 fc_calcula(i.k00_numpre,i.k00_numpar,i.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.")
          from arrecad i
					inner join arreinstit on arreinstit.k00_numpre = i.k00_numpre
					                     and arreinstit.k00_instit = $instit
	     left outer join arreinscr a on a.k00_numpre = i.k00_numpre
	     left outer join issvar v on v.q05_numpre = i.k00_numpre and v.q05_numpar = i.k00_numpar";
       if($justific==true){
       $sql .="
       left join ( select k28_sequencia,k28_arrejust,k28_numpre,k28_numpar,k27_dias,k27_data, (k27_data+k27_dias) as datajust
                                  from ( select max(k28_sequencia) as k28_sequencia,
                                                max(k28_arrejust) as k28_arrejust,
                                                k28_numpar,
                                                k28_numpre
                                           from arrejustreg
                                          where arrejustreg.k28_numpre = $numpre
                                           group by k28_numpre,
                                                    k28_numpar
                                       ) as subarrejust
                                       inner join arrejust on arrejust.k27_sequencia = subarrejust.k28_arrejust
                              ) as arrejustreg on arrejustreg.k28_numpre = i.k00_numpre
                                              and arrejustreg.k28_numpar = i.k00_numpar ";
       }
       $sql .="
	      ,histcalc,tabrec
       inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
          where k00_hist   = k01_codigo and
		i.k00_numpre = $numpre and
		k00_receit = k02_codigo ";
  if($tipo != 0){
	$sql .= " and k00_tipo   = ".$tipo;
  }
  $sql .= " order by k00_numpre,k00_numpar,k00_dtvenc";
  if ($limite != 0 ) {
     $sql .= " limit ".$limite;
  }
  $sql .= ") as x";
//echo "$sql";
//	echo "debitos_numpre_var : $sql <br>";
  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");
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



function debitos_numpre_var_comentado($numpre,$limite,$tipo,$datausu,$anousu,$instit=null ){
	if($instit==null){
		$instit=db_getsession('DB_instit');
	}
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
					inner join arreinstit on arreinstit.k00_numpre = a.k00_numpre
					                     and arreinstit.k00_instit = $instit
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
  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");
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


function debitos_inscricao_var($inscricao,$limite,$tipo,$datausu,$anousu,$justific=false,$instit=null){

	if($instit==null){
		$instit=db_getsession('DB_instit');
	}

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
            select q05_aliq,''::bpchar as k00_matric,v.q05_vlrinf as valor_variavel,a.k00_inscr,i.*,histcalc.*,tabrec.k02_descr,";
			if($justific==true){
			  $sql .= " datajust, ";
			}
			  $sql .= "
            fc_calcula(i.k00_numpre,i.k00_numpar,i.k00_receit,'".date('Y-m-d',$datausu)."','".db_vencimento($datausu)."',".$anousu.")
          from arreinscr a
			      inner join arrecad i on a.k00_numpre = i.k00_numpre
						inner join arreinstit on arreinstit.k00_numpre = i.k00_numpre
						                     and arreinstit.k00_instit = $instit
				    left outer join issvar v on v.q05_numpre = i.k00_numpre and v.q05_numpar = i.k00_numpar";

			  $sql .= "
            left join ( select k28_sequencia,k28_arrejust,k28_numpre,k28_numpar,k27_dias,k27_data, (k27_data+k27_dias) as datajust
                                  from ( select max(k28_sequencia) as k28_sequencia,
                                                max(k28_arrejust) as k28_arrejust,
                                                k28_numpar,
                                                k28_numpre
                                           from arrejustreg
                                          inner join arreinscr on arreinscr.k00_numpre = arrejustreg.k28_numpre
                                          where arreinscr.k00_inscr = $inscricao
                                           group by k28_numpre,
                                                    k28_numpar
                                       ) as subarrejust
                                       inner join arrejust on arrejust.k27_sequencia = subarrejust.k28_arrejust
                              ) as arrejustreg on arrejustreg.k28_numpre = i.k00_numpre
                                              and arrejustreg.k28_numpar = i.k00_numpar";
        $sql .= "
				    ,histcalc,tabrec
            inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
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
//echo "$sql";
//	echo "debitos_inscricao_var : $sql <br>";
  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");
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

class cl_gera_sql_folha {
  var $inicio_rh = true; // True se for comeï¿½ar o SQL pelo rhpessoal, false para comeï¿½ar pelas tabelas GERF's

  var $inner_ger = true; // True se darï¿½ inner join com as tabelas GERF's, false para left join.
  var $inner_pes = true; // True se darï¿½ inner join com a tabela rhpessoalmov, false para left join.
  var $inner_doc = false; // True se darï¿½ inner join com a tabela rhpesdoc, false para left join.
  var $inner_pad = false; // True se darï¿½ inner join com a tabela rhpespadrao e padroes, false para left join.
  var $inner_cgm = true; // True se darï¿½ inner join com a tabela CGM, false para left join.
  var $inner_fun = true; // True se darï¿½ inner join com a tabela rhfuncao, false para left join.
  var $inner_lot = true; // True se darï¿½ inner join com a tabela rhlota, false para left join.
  var $inner_exe = false; // True se darï¿½ inner join com a tabela rhlotaexe, false para left join.
  var $inner_vin = false; // True se darï¿½ inner join com a tabela rhlotavinc, false para left join.
  var $inner_org = false; // True se darï¿½ inner join com a tabela orcunidade, false para left join.
  var $inner_atv = true; // True se darï¿½ inner join com a tabela rhregime, false para left join.
  var $inner_rub = true; // True se darï¿½ inner join com a tabela rhrubricas, false para left join.
  var $inner_cfp = true; // True se darï¿½ inner join com a tabela rhcfpess, false para left join.
  var $inner_res = false;// True se darï¿½ inner join com a tabela rhpesrescisao, false para left join.
  var $inner_rel = false; // True se darï¿½ inner join com a tabela rhrubelemento, false para left join.
  var $inner_fgt = false; // True se darï¿½ inner join com a tabela rhpesfgts, false para left join.
  var $inner_ins = false; // True se darï¿½ inner join com a tabela rhinssoutros, false para left join.
  var $inner_tpc = true; // True se darï¿½ inner join com a tabela tpcontra, false para left join.
  var $inner_rcs = false; // True se darï¿½ inner join com a tabela rescisao, false para left join.
  var $inner_cad = true; // True se darï¿½ inner join com as tabelas rhinstru, rhestcivil e rhnacionalidade, false para left join.
  var $inner_tra = false; //True se darï¿½ inner join com a tabela rhpeslocaltrab, false para left join.
  var $inner_car = false; //True se darï¿½ inner join com a tabela rhpescargo, false para left join.
  var $inner_ban = false; //True se darï¿½ inner join com a tabela rhpesbanco, false para left join.
  var $inner_pro = false; //True se darï¿½ inner join com a tabela orcprojativ, false para left join.
  var $inner_rec = false; //True se darï¿½ inner join com a tabela orctiporec, false para left join.
  var $inner_afa = false; //True se darï¿½ inner join com a tabela afasta, false para left join.
  var $inner_inf = false; // True se darï¿½ inner join com a tabela infla, false para left outer join.

  var $usar_ger = false; // Se usarï¿½ inner ou left join com as tabelas GERF's.
  var $usar_pes = false; // Se usarï¿½ inner ou left join com a tabela rhpessoalmov.
  var $usar_doc = false; // Se usarï¿½ inner ou left join com a tabela rhpesdoc.
  var $usar_pad = false; // Se usarï¿½ inner ou left join com a tabela rhpespadrao e padroes.
  var $usar_cgm = false; // Se usarï¿½ inner ou left join com a tabela CGM.
  var $usar_fun = false; // Se usarï¿½ inner ou left join com a tabela rhfuncao.
  var $usar_lot = false; // Se usarï¿½ inner ou left join com a tabela rhlota.
  var $usar_exe = false; // Se usarï¿½ inner ou left join com a tabela rhlotaexe.
  var $usar_vin = false; // Se usarï¿½ inner ou left join com a tabela rhlotavinc.
  var $usar_org = false; // Se usarï¿½ inner ou left join com a tabela orcunidade.
  var $usar_atv = false; // Se usarï¿½ inner ou left join com a tabela rhregime.
  var $usar_rub = false; // Se usarï¿½ inner ou left join com a tabela rhrubricas.
  var $usar_cfp = false; // Se usarï¿½ inner ou left join com a tabela cfpess.
  var $usar_res = false; // Se usarï¿½ inner ou left join com a tabela rhpesrescisao.
  var $usar_rel = false; // Se usarï¿½ inner ou left join com a tabela rhrubelemento.
  var $usar_fgt = false; // Se usarï¿½ inner ou left join com a tabela rhpesfgts.
  var $usar_ins = false; // Se usarï¿½ inner ou left join com a tabela rhinssoutros.
  var $usar_tpc = false; // Se usarï¿½ inner ou left join com a tabela tpcontra.
  var $usar_rcs = false; // Se usarï¿½ inner ou left join com a tabela rescisao.
  var $usar_cad = false; // Se usarï¿½ inner ou left join com as tabelas rhinstru, rhestcivil e rhnacionalidade, false para left join.
  var $usar_tra = false; // Se usarï¿½ inner ou left join com a tabela rhpeslocaltrab.
  var $usar_car = false; // Se usarï¿½ inner ou left join com a tabela rhpescargo.
  var $usar_ban = false; // Se usarï¿½ inner ou left join com a tabela rhpesbanco.
  var $usar_pro = false; // Se usarï¿½ inner ou left join com a tabela orcprojativ.
  var $usar_rec = false; // Se usarï¿½ inner ou left join com a tabela orctiporec.
  var $usar_afa = false; // Se usarï¿½ inner ou left join com a tabela afasta.
  var $usar_inf = false; // Se usarï¿½ inner ou left join com a tabela infla;

  var $vinculo_inner = ""; // Vï¿½nculo utilizado para filtro
  var $local_trab_princ = true; // Selecionar local de trabalho principal

  var $subsql = "";
  var $subsqlano = "";
  var $subsqlmes = "";
  var $subsqlreg = "";
  var $subsqlrub = "";
  var $trancaGer = false;
  var $codigo_inflator = "";
  var $somente_subsql = false;

  var $numrows_exec = 0;

  // $sigla : Sigla da tabela GERF que o programador deseja utilizar.
  // $ano   : Anousu referente ao campo rh02_anousu da tabela rhpessoalmov.
  // $mes   : Mesusu referente ao campo rh02_mesusu da tabela rhpessoalmov.
  // $regist: Registro especï¿½fico que o programador deseja trazer.
  // $campos: Campos que o programador deseja trazer.
  //          Se quer trazer algum campo das tabelas GERF's, substituir a sigla por '#s#'.
  //          Ou seja, #s#_rubric ï¿½ o mesmo que $sigla."_rubric" que ï¿½ o mesmo que r31_rubric ou r20_rubric ou r35_rubric.
  // $order : Order by
  // $where : Clï¿½usula que o programador deseja adicionar ao WHERE do SQL sendo que poderï¿½ colocar ao mesmo tempo o
  //          $ano, $mes, $regist e $where.

  // EX.: $classe->sql_gera("r31","2005","11","2870","#s#_anousu,#s#_rubric,rh01_regist","","rh05_seqpes is null ");
  // A sigla serï¿½ r31, entï¿½o, a tabela GERF serï¿½ a tabela GERFFER
  // O ano serï¿½ o de 2005
  // O mï¿½s serï¿½ o 11 (novembro)
  // O regist serï¿½ o 2870
  // Os campos serï¿½o: r31_anousu, r31_rubric e rh01_regist (Trocarï¿½ '#s#' por 'r31')
  // Nï¿½o terï¿½ order by
  // A VARIï¿½VEL where serï¿½: rh05_seqpes is null... Mas o where do SQL serï¿½:
  // where rh02_anousu = 2005 and rh02_mesusu = 11 and rh02_regist = 2870 and rh05_seqpes is null

  function gerador_sql($sigla,$ano=null,$mes=null,$regist=null,$rubric=null,$campos=" * ",$order="",$where="",$instit=null, $sWhereSup = ''){
    if($sigla == 'r14'){
      $arquivo = ' gerfsal';
      $iTipoFolha = 1;
    }elseif($sigla == 'sup'){
      $campos  = str_replace($sigla, 'r14' , $campos);
      $order   = str_replace($sigla, 'r14' , $order);
      $arquivo = ' gerfsal';
      $sigla   = 'r14';
      $iTipoFolha = 6;
    }elseif($sigla == 'r20'){
      $arquivo = ' gerfres';
    }elseif($sigla == 'r35'){
      $arquivo = ' gerfs13';
    }elseif($sigla == 'r22'){
      $arquivo = ' gerfadi';
    }elseif($sigla == 'r48'){
      $arquivo = ' gerfcom';
      $iTipoFolha = 3;
    }elseif($sigla == 'r53'){
      $arquivo = ' gerffx';
    }elseif($sigla == 'r31'){
      $arquivo = ' gerffer';
    }elseif($sigla == 'r47'){
      $arquivo = ' pontocom';
      $iTipoPonto = 3;
    }elseif($sigla == 'r34'){
      $arquivo = ' pontof13';
    }elseif($sigla == 'r21'){
      $arquivo = ' pontofa';
    }elseif($sigla == 'r29'){
      $arquivo = ' pontofe';
    }elseif($sigla == 'r19'){
      $arquivo = ' pontofr';
    }elseif($sigla == 'r10'){
      $arquivo = ' pontofs';
      $iTipoPonto = 1;
    }elseif($sigla == 'r90'){
      $arquivo = ' pontofx';
    }elseif($sigla == 'r60'){
      $arquivo = ' previden';
    }elseif($sigla == 'r61'){
      $arquivo = ' ajusteir';
    }

    $iInstit = db_getsession('DB_instit');

    if(trim($campos) != ""){
      $campos = str_replace("#s#",$sigla,$campos);
      $campos = str_replace("#S#",$sigla,$campos);
    }else{
      $campos = " * ";
    }
    if(trim($order) != ""){
      $order = str_replace("#s#",$sigla,$order);
      $order = str_replace("#S#",$sigla,$order);
    }
    if(trim($where) != ""){
      $where = str_replace("#s#",$sigla,$where);
      $where = str_replace("#S#",$sigla,$where);
    }

    $sql = " select ".$campos;

    if(($this->usar_rub == true && $sigla != "" && $sigla != null) || ($rubric != null && trim($rubric) != "") || $this->usar_rel == true){
      $this->usar_ger = true;
      $this->usar_rub = true;
    }
    if($this->usar_pro == true || $this->usar_rec){
      $this->usar_vin = true;
    }
    if($this->usar_doc == true || $this->usar_cgm == true || $this->usar_fun == true || $this->usar_lot == true ||
       $this->usar_res == true || $this->usar_ins == true || $this->usar_exe == true || $this->usar_org == true ||
       $this->usar_atv == true || $this->usar_fgt == true || $this->usar_pad == true || $this->usar_cad == true ||
       $this->usar_tra == true || $this->usar_car == true || $this->usar_vin == true){
       $this->usar_pes = true;
    }
    if($this->usar_rcs == true){
      $this->usar_pes = true;
      $this->usar_res = true;
      $this->usar_atv = true;
      if($this->inner_rcs == false){
        $this->inner_res = false;
        $this->inner_atv = false;
      }
    }
    if($this->usar_org == true || $this->usar_exe == true || $this->usar_vin == true){
      $this->usar_exe = true;
      $this->usar_lot = true;
    }
    if($this->usar_vin == true){
      $this->usar_atv = true;
    }
    if(trim($this->subsql) != "" && $this->somente_subsql == true){
       	$sql.= " from (".$this->subsql.") x ";
    }else if($this->inicio_rh == true){
      if(trim($this->subsql) == ""){
        $sql.= " from rhpessoal ";
        $sql.= "      inner join rhpessoalmov on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist ";
        if($ano != "" && $ano != null){
          $sql.= "                           and rhpessoalmov.rh02_anousu = ".$ano."
                                             and rhpessoalmov.rh02_mesusu = ".$mes."
																						 and rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")." ";
        }
        $sql.= "                           and rhpessoalmov.rh02_instit = ".db_getsession('DB_instit');
      }else{
       	$sql.= " from (".$this->subsql.") x ";
        $sql.= "      inner join rhpessoalmov on rhpessoalmov.rh02_anousu = x." . ($this->subsqlano == "" ? "anousu" : $this->subsqlano) . "
                                             and rhpessoalmov.rh02_mesusu = x." . ($this->subsqlmes == "" ? "mesusu" : $this->subsqlmes) . "
                                             and rhpessoalmov.rh02_instit = " .db_getsession('DB_instit'). "
                                             and rhpessoalmov.rh02_regist = x." . ($this->subsqlreg == "" ? "regist" : $this->subsqlreg) . "
																						 and rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")." ";
        $sql.= "      inner join rhpessoal    on rhpessoal.rh01_regist = rhpessoalmov.rh02_regist ";
      }
      if($this->usar_ger == true && $this->trancaGer == false){
        $inner = " inner join ";
        if($this->inner_ger == false){
          $inner = " left join ";
        }

        if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() && isset($iTipoFolha)) {

          $sql .= "  {$inner}                                     ";
          $sql .= "  (                                            ";
          $sql .= "   SELECT rh141_instit     AS {$sigla}_instit, ";
          $sql .= "          rh141_mesusu     AS {$sigla}_mesusu, ";
          $sql .= "          rh141_anousu     AS {$sigla}_anousu, ";
          $sql .= "          rh143_valor      AS {$sigla}_valor,  ";
          $sql .= "          rh143_rubrica    AS {$sigla}_rubric, ";
          $sql .= "          rh143_quantidade AS {$sigla}_quant,  ";
          $sql .= "          rh143_tipoevento AS {$sigla}_pd,     ";
          $sql .= "          rh143_regist     AS {$sigla}_regist, ";
          $sql .= "          rh141_codigo     AS {$sigla}_semest  ";
          $sql .= "    FROM rhfolhapagamento                      ";
          $sql .= "      INNER JOIN rhhistoricocalculo ON rh141_sequencial = rh143_folhapagamento ";
          $sql .= "   WHERE rh141_tipofolha = {$iTipoFolha}                                       ";
          $sql .= "     AND rh141_anousu    = {$ano}                                              ";
          $sql .= "     AND rh141_mesusu    = {$mes}                                              ";
          $sql .= "     AND rh141_instit    = {$iInstit}                                          ";
          $sql .= "     {$sWhereSup}                                                              ";
          $sql .= "  ) AS {$arquivo} ON  {$arquivo}.{$sigla}_anousu = rhpessoalmov.rh02_anousu    ";
          $sql .= "                  AND {$arquivo}.{$sigla}_mesusu = rhpessoalmov.rh02_mesusu    ";
          $sql .= "                  AND {$arquivo}.{$sigla}_regist = rhpessoalmov.rh02_regist    ";
          $sql .= "                  AND {$arquivo}.{$sigla}_instit = rhpessoalmov.rh02_instit    ";
          $sql .= "                  AND   rhpessoalmov.rh02_instit = {$iInstit}                  ";

        } else if(DBPessoal::verificarUtilizacaoEstruturaSuplementar() && isset($iTipoPonto)) {

          $sql .= "  {$inner}                                     ";
          $sql .= "  (                                            ";
          $sql .= "   SELECT rh141_instit     as {$sigla}_instit, ";
          $sql .= "          rh141_mesusu     as {$sigla}_mesusu, ";
          $sql .= "          rh141_anousu     as {$sigla}_anousu, ";
          $sql .= "          rh144_valor      as {$sigla}_valor,  ";
          $sql .= "          rh144_rubrica    as {$sigla}_rubric, ";
          $sql .= "          rh144_quantidade as {$sigla}_quant,  ";
          $sql .= "          rh144_regist     as {$sigla}_regist, ";
          $sql .= "          rh141_codigo     as {$sigla}_semest, ";
          $sql .= "          rh02_lota        as {$sigla}_lotac,  ";
          $sql .= "          null             as r10_datlim       ";
          $sql .= "    from rhfolhapagamento                      ";
          $sql .= "      inner join rhhistoricoponto ON rh141_sequencial = rh144_folhapagamento              ";
          $sql .= "      inner join rhpessoalmov on rhpessoalmov.rh02_regist = rhhistoricoponto.rh144_regist ";
          $sql .= "                             and rhpessoalmov.rh02_anousu = {$ano}                        ";
          $sql .= "                             and rhpessoalmov.rh02_mesusu = {$mes}                        ";
          $sql .= "                             and rhpessoalmov.rh02_instit = {$iInstit}                    ";
          $sql .= "   where rh141_tipofolha = {$iTipoPonto}                                       ";
          $sql .= "     and rh141_anousu    = {$ano}                                              ";
          $sql .= "     and rh141_mesusu    = {$mes}                                              ";
          $sql .= "     and rh141_instit    = {$iInstit}                                          ";
          $sql .= "     {$sWhereSup}                                                              ";
          $sql .= "  ) as {$arquivo} on  {$arquivo}.{$sigla}_anousu = rhpessoalmov.rh02_anousu    ";
          $sql .= "                  and {$arquivo}.{$sigla}_mesusu = rhpessoalmov.rh02_mesusu    ";
          $sql .= "                  and {$arquivo}.{$sigla}_regist = rhpessoalmov.rh02_regist    ";
          $sql .= "                  and {$arquivo}.{$sigla}_instit = rhpessoalmov.rh02_instit    ";
          $sql .= "                  and   rhpessoalmov.rh02_instit = {$iInstit}                  ";

        } else {
          $sql.= $inner.$arquivo." on ".$arquivo.".".$sigla."_anousu = rhpessoalmov.rh02_anousu
                                and ".$arquivo.".".$sigla."_mesusu = rhpessoalmov.rh02_mesusu
                                and rhpessoalmov.rh02_instit = ".db_getsession('DB_instit')."
                                and ".$arquivo.".".$sigla."_regist = rhpessoalmov.rh02_regist
                                and ".$arquivo.".".$sigla."_instit = rhpessoalmov.rh02_instit ";
        }

      }
    }else{
      if(trim($this->subsql) == ""){

        /**
         * Modificado a estrutura do código, por causa da implantação da suplementar na geração do empenho da folha.
         */

        $sTabela = " from ".$arquivo;

        if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() && isset($iTipoFolha)) {

          $sTabela = " from  (
                              SELECT rh141_instit     AS {$sigla}_instit,
                                     rh141_mesusu     AS {$sigla}_mesusu,
                                     rh141_anousu     AS {$sigla}_anousu,
                                     rh143_valor      AS {$sigla}_valor,
                                     rh143_rubrica    AS {$sigla}_rubric,
                                     rh143_quantidade AS {$sigla}_quant,
                                     rh143_tipoevento AS {$sigla}_pd,
                                     rh143_regist     AS {$sigla}_regist,
                                     rh141_codigo     AS {$sigla}_semest
                               FROM rhfolhapagamento
                                 INNER JOIN rhhistoricocalculo ON rh141_sequencial = rh143_folhapagamento
                              WHERE rh141_tipofolha = {$iTipoFolha}
                                AND rh141_anousu    = {$ano}
                                AND rh141_mesusu    = {$mes}
                                AND rh141_instit    = {$iInstit}
                                {$sWhereSup}
                             ) AS {$arquivo}";
        }

        $sql .= $sTabela;
      }else{
        $sql.= " from (".$this->subsql.") x ";
        $sql.= "      inner join  on ".$arquivo.".".$sigla."_regist = x." . ($this->subsqlreg == "" ? "regist" : $this->subsqlreg) . "
                                 and ".$arquivo.".".$sigla."_anousu = x." . ($this->subsqlano == "" ? "anousu" : $this->subsqlano) . "
                                 and ".$arquivo.".".$sigla."_mesusu = x." . ($this->subsqlmes == "" ? "mesusu" : $this->subsqlmes) . "
																 and ".$arquivo.".".$sigla."_instit = ".db_getsession("DB_instit")." ";
      }
      if($this->usar_pes == true){
        $inner = " inner join ";
        if($this->inner_pes == false){
          $inner = " left join ";
        }
        $sql.=       $inner." rhpessoalmov on rhpessoalmov.rh02_anousu = " . ($this->subsqlrub == "" ? $arquivo . "." . $sigla."_anousu" : "x." . $this->subsqlano) . "
                                          and rhpessoalmov.rh02_mesusu = " . ($this->subsqlrub == "" ? $arquivo . "." . $sigla."_mesusu" : "x." . $this->subsqlmes) . "
                                          and rhpessoalmov.rh02_regist = " . ($this->subsqlrub == "" ? $arquivo . "." . $sigla."_regist" : "x." . $this->subsqlreg) . "
																					and rhpessoalmov.rh02_instit = " . db_getsession("DB_instit")." ";
        $sql.=       $inner." rhpessoal    on rhpessoal.rh01_regist = rhpessoalmov.rh02_regist ";
      }
    }

    if($this->usar_rub == true){
      $inner = " inner join ";
      if($this->inner_rub == false){
    	  $inner = " left join ";
      }
      $sql.=       $inner." rhrubricas on rhrubricas.rh27_rubric = " . ($this->subsqlrub == "" ? $arquivo.".".$sigla."_rubric::varchar " : "x.".$this->subsqlrub);
      $sql.=              "           and rhrubricas.rh27_instit = " . db_getsession("DB_instit")." ";
      if($this->usar_rel == true){
      	$inner = " inner join ";
        if($this->inner_rel == false){
          $inner = " left join ";
        }
        $sql.=     $inner." rhrubelemento on rhrubelemento.rh23_rubric = rhrubricas.rh27_rubric ";
        $sql.=            "              and rhrubelemento.rh23_instit = rhrubricas.rh27_instit ";
      }
    }

    if($this->usar_cfp == true){
      $inner = " inner join ";
      if($this->inner_cfp == false){
        $inner = " left join ";
      }
      if($this->usar_pes == true || $this->inicio_rh == true){
        $sql.=       $inner." cfpess on cfpess.r11_anousu = rhpessoalmov.rh02_anousu
                                    and cfpess.r11_mesusu = rhpessoalmov.rh02_mesusu
																		and cfpess.r11_instit = ".db_getsession("DB_instit")." ";
      }else if($this->usar_ger == true){
        $sql.=       $inner." cfpess on cfpess.r11_anousu = ".$arquivo.".".$sigla."_anousu
                                    and cfpess.r11_mesusu = ".$arquivo.".".$sigla."_mesusu
																		and cfpess.r11_instit = ".db_getsession("DB_instit")." ";
      }
    }

    if($this->usar_doc == true){
      $inner = " inner join ";
      if($this->inner_doc == false){
        $inner = " left join ";
      }
      $sql.=       $inner." rhpesdoc on rhpesdoc.rh16_regist = rhpessoal.rh01_regist ";
    }

    if($this->usar_ban == true){
      $inner = " inner join ";
      if($this->inner_ban == false){
        $inner = " left join ";
      }
      $sql.=       $inner." rhpesbanco on rhpesbanco.rh44_seqpes = rhpessoalmov.rh02_seqpes ";
    }

    if($this->usar_tra == true){
      $inner = " inner join ";
      if($this->inner_tra == false){
        $inner = " left join ";
      }
      $sql.=       $inner." rhpeslocaltrab on rhpeslocaltrab.rh56_seqpes = rhpessoalmov.rh02_seqpes ";
      if($this->local_trab_princ == true){
	$sql.= " and rhpeslocaltrab.rh56_princ = 't'";
      }
      $sql.=       $inner." rhlocaltrab    on rhlocaltrab.rh55_codigo    = rhpeslocaltrab.rh56_localtrab ";
      $sql.=              "               and rhlocaltrab.rh55_instit    = rhpessoalmov.rh02_instit ";
    }

    if($this->usar_car == true){
      $inner = " inner join ";
      if($this->inner_car == false){
        $inner = " left join ";
      }
      $sql.=       $inner." rhpescargo  on rhpescargo.rh20_seqpes = rhpessoalmov.rh02_seqpes ";
      $sql.=       $inner." rhcargo     on rhcargo.rh04_codigo    = rhpescargo.rh20_cargo ";
      $sql.=              "            and rhcargo.rh04_instit    = rhpessoalmov.rh02_instit ";
    }

    if($this->usar_cad == true){
      $inner = " inner join ";
      if($this->inner_cad == false){
        $inner = " left join ";
      }
      $sql.=       $inner." rhinstrucao     on rhinstrucao.rh21_instru            = rhpessoal.rh01_instru ";
      $sql.=       $inner." rhestcivil      on rhestcivil.rh08_estciv             = rhpessoal.rh01_estciv ";
      $sql.=       $inner." rhnacionalidade on rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion ";
    }

    if($this->usar_fgt == true){
      $inner = " inner join ";
      if($this->inner_fgt == false){
        $inner = " left join ";
      }
      $sql.=       $inner." rhpesfgts on rhpesfgts.rh15_regist = rhpessoal.rh01_regist ";
      $sql.=       $inner." db_bancos on db_bancos.db90_codban = rhpesfgts.rh15_banco ";
    }

    if($this->usar_cgm == true){
      $inner = " inner join ";
      if($this->inner_cgm == false){
        $inner = " left join ";
      }
      $sql.=       $inner." cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm ";
    }
    if($this->usar_fun == true){
      $inner = " inner join ";
      if($this->inner_fun == false){
        $inner = " left join ";
      }
      $sql.=       $inner." rhfuncao on rhfuncao.rh37_funcao = rhpessoalmov.rh02_funcao ";
      $sql.=              "         and rhfuncao.rh37_instit = ".db_getsession("DB_instit")." ";
    }
    if($this->usar_lot == true){
      $inner = " inner join ";
      if($this->inner_lot == false){
        $inner = " left join ";
      }
      $sql.=       $inner." rhlota on rhlota.r70_codigo = rhpessoalmov.rh02_lota ";
      $sql.=              "       and rhlota.r70_instit = rhpessoalmov.rh02_instit ";
    }
    if($this->usar_atv == true){
      $inner = " inner join ";
      if($this->inner_atv == false){
        $inner = " left join ";
      }
      $sql.=       $inner." rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg ";
      $sql.=              "         and rhregime.rh30_instit = rhpessoalmov.rh02_instit ";
      if(trim($this->vinculo_inner) != ""){
	$sql.= " and rhregime.rh30_vinculo = '".$this->vinculo_inner."'";
      }
    }
    if($this->usar_afa == true){
      $inner = " inner join ";
      if($this->inner_afa == false){
        $inner = " left join ";
      }
      $sql.=       $inner." afasta on afasta.r45_anousu = rhpessoalmov.rh02_anousu
                                  and afasta.r45_mesusu = rhpessoalmov.rh02_mesusu
                                  and afasta.r45_regist = rhpessoalmov.rh02_regist ";
    }
    if($this->usar_exe == true){
      $inner = " inner join ";
      if($this->inner_exe == false){
        $inner = " left join ";
      }
      $sql.=       $inner." rhlotaexe on rhlotaexe.rh26_anousu = rhpessoalmov.rh02_anousu
                                     and rhlotaexe.rh26_codigo = rhlota.r70_codigo ";
      if($this->usar_org == true){
        $inner = " inner join ";
        if($this->inner_org == false){
          $inner = " left join ";
        }
        $sql.=       $inner." orcunidade on orcunidade.o41_anousu  = rhlotaexe.rh26_anousu
                                        and orcunidade.o41_orgao   = rhlotaexe.rh26_orgao
                                        and orcunidade.o41_unidade = rhlotaexe.rh26_unidade";
        $sql.=       $inner." orcorgao   on orcorgao.o40_anousu    = orcunidade.o41_anousu
                                        and orcorgao.o40_orgao     = orcunidade.o41_orgao
					";
      }

      if($this->usar_vin == true){
	$inner = " inner join ";
	if($this->inner_vin == false){
	  $inner = " left join ";
	}
	$sql.=       $inner.=" rhlotavinc on rhlotavinc.rh25_codigo  = rhlotaexe.rh26_codigo
                                         and rhlotavinc.rh25_anousu  = rhpessoalmov.rh02_anousu
                                         and rhlotavinc.rh25_vinculo = rhregime.rh30_vinculo ";
        if($this->usar_pro == true){
          $inner = " inner join ";
          if($this->inner_pro == false){
            $inner = " left join ";
          }
          $sql.=       $inner.=" orcprojativ on orcprojativ.o55_anousu   = rhpessoalmov.rh02_anousu
                                            and orcprojativ.o55_projativ = rhlotavinc.rh25_projativ ";
        }
        if($this->usar_rec == true){
          $inner = " inner join ";
          if($this->inner_rec == false){
            $inner = " left join ";
          }
          $sql.=       $inner.=" orctiporec on orctiporec.o15_codigo = rhlotavinc.rh25_recurso ";
        }
      }
    }
    if($this->usar_res == true){
      $inner = " inner join ";
      if($this->inner_res == false){
        $inner = " left join ";
      }
      $sql.=       $inner." rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
    }
    if($this->usar_rcs == true){
      $inner = " inner join ";
      if($this->inner_rcs == false){
        $inner = " left join ";
      }
      $sql.=       $inner." rescisao on rescisao.r59_anousu = rhpessoalmov.rh02_anousu
                                    and rescisao.r59_mesusu = rhpessoalmov.rh02_mesusu
                         				    and rescisao.r59_regime = rhregime.rh30_regime
                        				    and rescisao.r59_causa  = rhpesrescisao.rh05_causa
                        				    and rescisao.r59_caub   = rhpesrescisao.rh05_caub::char(2)
                        				    and case when (rhpesrescisao.rh05_recis - rhpessoal.rh01_admiss) >= 365
                              				             then 'N' else 'S' end  = rescisao.r59_menos1
																		and rescisao.r59_instit = rhpessoalmov.rh02_instit ";
    }
    if($this->usar_pad == true){
      $inner = " inner join ";
      if($this->inner_pad == false){
            $inner = " left join ";
      }
      $sql.=       $inner." rhpespadrao on rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes ";
      	    if($ano != ""){
        $sql.= "                  and rhpespadrao.rh03_anousu = $ano ";
      	    }else{
        $sql.= "                  and rhpespadrao.rh03_anousu = rhpessoalmov.rh02_anousu ";
      	    }
      	    if($mes != ""){
        $sql.= "                  and rhpespadrao.rh03_mesusu = $mes ";
      	    }else{
        $sql.= "                  and rhpespadrao.rh03_mesusu = rhpessoalmov.rh02_mesusu ";
      	    }
      $inner = " inner join ";
      if($this->inner_pad == false){
            $inner = " left join ";
      }
      $sql.=       $inner." padroes on padroes.r02_anousu = rhpespadrao.rh03_anousu ";
      $sql.= "                     and padroes.r02_mesusu = rhpespadrao.rh03_mesusu ";
      $sql.= "                     and padroes.r02_regime = rhpespadrao.rh03_regime ";
      $sql.= "                     and padroes.r02_codigo = rhpespadrao.rh03_padrao ";
      $sql.= "                     and padroes.r02_instit = ".db_getsession("DB_instit")." ";

    }
    if($this->usar_ins == true){
      $inner = " inner join ";
      if($this->inner_ins == false){
        $inner = " left join ";
      }
      $sql.=       $inner." rhinssoutros on rhinssoutros.rh51_seqpes = rhpessoalmov.rh02_seqpes ";
    }

    if($this->usar_tpc == true){
      $inner = " inner join ";
      if($this->inner_tpc == false){
        $inner = " left join ";
      }
      $sql.=       $inner." tpcontra on tpcontra.h13_codigo = rhpessoalmov.rh02_tpcont ";
    }

    if($this->usar_inf == true){
      $inner = " inner join ";
      if($this->inner_inf == false){
        $inner = " left outer join ";
      }
      $sql.=       $inner." infla on infla.i02_codigo = '".$this->codigo_inflator."'
                                 and extract(year  from infla.i02_data) = rhpessoalmov.rh02_anousu
                                 and extract(month from infla.i02_data) = rhpessoalmov.rh02_mesusu ";
    }

    $valor_where = " where ";
    if($this->inicio_rh == true){
      if($ano != null && trim($ano) != ""){
       	$sql.= $valor_where." rhpessoalmov.rh02_anousu = ".$ano;
        $valor_where = " and ";
      }
      if($mes != null && trim($mes) != ""){
        $sql.= $valor_where." rhpessoalmov.rh02_mesusu = ".$mes;
        $valor_where = " and ";
      }
      if($regist != null && trim($regist) != ""){
        $sql.= $valor_where." rhpessoalmov.rh02_regist = ".$regist;
        $valor_where = " and ";
      }
      if($instit != null && trim($instit) != ""){
        $sql.= $valor_where." rhpessoalmov.rh02_instit = ".$instit;
        $valor_where = " and ";
//      }else{
//        $sql.= $valor_where." rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")." ";
//        $valor_where = " and ";
      }
    }else{
      if($ano != null && trim($ano) != ""){
        $sql.= $valor_where.$arquivo.".".$sigla."_anousu = ".$ano;
        $valor_where = " and ";
      }
      if($mes != null && trim($mes) != ""){
        $sql.= $valor_where.$arquivo.".".$sigla."_mesusu = ".$mes;
        $valor_where = " and ";
      }
      if($regist != null && trim($regist) != ""){
        $sql.= $valor_where.$arquivo.".".$sigla."_regist = ".$regist;
        $valor_where = " and ";
      }
      if($instit != null && trim($instit) != ""){
        $sql.= $valor_where.$arquivo.".".$sigla."_instit = ".$instit;
        $valor_where = " and ";
      }else{
        $sql.= $valor_where.$arquivo.".".$sigla."_instit = ".db_getsession("DB_instit")." ";
        $valor_where = " and ";

			}
    }

    if($rubric != null && trim($rubric) != ""){
      $sql.= $valor_where.$arquivo.".".$sigla."_rubric = '".$rubric."'";
      $valor_where = " and ";
    }

    if(trim($where) != ""){
      $sql.= $valor_where." ".$where;
    }

    if(trim($order) != ""){
      $sql.= " order by ".$order;
    }

    return $sql;
  }

  function sql_record($sql){
    $result = @db_query($sql);
    if($result !== false){
      $this->numrows_exec = pg_numrows($result);
    }

    return $result;
  }
}

function recprocandsol($codtran){
	$clprotprocesso = new cl_protprocesso;
	$clproctransferproc = new cl_proctransferproc;
	$clproctransfer = new cl_proctransfer;
	$clproctransand = new cl_proctransand;
	$clsolicitemprot = new cl_solicitemprot;
	$clsolandam = new cl_solandam;
	$clsolandamand = new cl_solandamand;
	$clsolandpadraodepto = new cl_solandpadraodepto;
	$clsolordemtransf = new cl_solordemtransf;
	$clprocandam = new cl_procandam;
	global $p63_codproc;
	global $pc49_solicitem;
	global $p63_codtran;
	global $pc41_ordem;
	db_inicio_transacao();
	$sqlerro = false;
	$sql = "select *
         	from proctransferproc
				inner join proctransfer on p63_codtran = p62_codtran
          	where p63_codtran = $codtran and
				  p63_codtran not in(select p64_codtran from proctransand) and
                  p62_coddeptorec = ".db_getsession("DB_coddepto")." and
                  p63_codproc not in( select p68_codproc from arqproc)";

	$rs = db_query($sql);
	$erro = 0;
	if (pg_num_rows($rs) > 0) {
		for ($i = 0; $i < pg_num_rows($rs); $i ++) {
			db_fieldsmemory($rs, $i);
			$sqlproc = "select p58_despacho,p58_publico  from protprocesso where p58_codproc = $p63_codproc";
			$rsproc = db_query($sqlproc);
			//inclui o andamento
			$despach = pg_result($rsproc, 0, "p58_despacho");
			$publico = pg_result($rsproc, 0, "p58_publico");
			$despach = str_replace("'", "", $despach);
			$publico = str_replace("'", "", $publico);
			$publico = ($publico == 'f' ? "false" : "true");

			$result = $clprocandam->sql_record($clprocandam->sql_query_file(null, "*", null, "p61_codproc=$p63_codproc"));
			$numrows = $clprocandam->numrows;

			if ($numrows == 0) {
				$clprocandam->p61_despacho = 'Andamento Inicial';
			} else
				$clprocandam->p61_despacho = $despach;

			$clprocandam->p61_publico = $publico;
			$clprocandam->p61_codproc = $p63_codproc;
			$data = date('Y-m-d');
			$hora = db_hora();
			$clprocandam->p61_dtandam = $data;
			$clprocandam->p61_hora = $hora;
			$clprocandam->p61_id_usuario = db_getsession("DB_id_usuario");
			$clprocandam->p61_coddepto = db_getsession("DB_coddepto");
			$clprocandam->incluir(null);

			if ($clprocandam->erro_status == "1") {
				$erro = 0;
			} else {
				$clprocandam->erro(true, false);
				$erro = 1;
				$sqlerro = true;
				break;

			}

			//inclui a transferencia e o andamento do processo na tabela proctransand
			$clproctransand->p64_codtran = $codtran;
			$clproctransand->p64_codandam = $clprocandam->p61_codandam;
			$clproctransand->incluir(null);

			if ($clproctransand->erro_status == "1") {
				$erro = 0;
			} else {
				$clproctransand->erro(true, false);
				$erro = 1;
				$sqlerro = true;
				break;
			}

			//atualiza codandam da tabela protprocesso;
			$clprotprocesso->p58_codproc = $p63_codproc;
			$clprotprocesso->p58_codandam = $clprocandam->p61_codandam;
			$clprotprocesso->p58_despacho = " ";
			$clprotprocesso->alterar($p63_codproc);
			if ($clprotprocesso->erro_status == "1") {
				$erro = 0;
			} else {
				$clprotprocesso->erro(true, false);
				$sqlerro = true;
				$erro = 1;
				break;
			}
		    $result_item=$clsolicitemprot->sql_record($clsolicitemprot->sql_query_file(null,"*",null,"pc49_protprocesso=$p63_codproc"));
			if ($clsolicitemprot->numrows>0){
				db_fieldsmemory($result_item,0);
				if ($sqlerro == false) {
					$result_ord=$clsolordemtransf->sql_record($clsolordemtransf->sql_query_file(null,"*",null,"pc41_solicitem=$pc49_solicitem and pc41_codtran=$p63_codtran"));
					if ($clsolordemtransf->numrows>0){
						db_fieldsmemory($result_ord,0);
					}else{
						$pc41_ordem=2;
					}
					$clsolandam->pc43_depto=db_getsession("DB_coddepto");
					$clsolandam->pc43_ordem=$pc41_ordem;
					$clsolandam->pc43_solicitem=$pc49_solicitem;
					$clsolandam->incluir(null);
					if ($clsolandam->erro_status==0){
						$sqlerro = true;
						break;
					}
			    }
			    if ($sqlerro == false) {
				    $clsolandamand->pc42_codandam=$clprocandam->p61_codandam;
					$clsolandamand->incluir($clsolandam->pc43_codigo);
					if ($clsolandamand->erro_status==0){
						$sqlerro = true;
						break;
					}
				}
			}
		}
	}
	db_inicio_transacao($sqlerro);
	return $sqlerro;
}

class cl_gera_subsql_folha{
  var $inner_sem = true; // True se darï¿½ inner join com a tabela rhpessoalmov, false para left join (SOMENTE PARA GERAR PELO PONGER).
  var $inner_pes = true; // True se darï¿½ inner join com a tabela rhpessoalmov, false para left join (SOMENTE PARA GERAR PELO PONGER).
  var $inner_cgm = true; // True se darï¿½ inner join com a tabela CGM, false para left join.
  var $inner_fun = true; // True se darï¿½ inner join com a tabela rhfuncao, false para left join.
  var $inner_lot = true; // True se darï¿½ inner join com a tabela rhlota, false para left join.
  var $inner_exe = true; // True se darï¿½ inner join com a tabela rhlotaexe, false para left join.
  var $inner_org = true; // True se darï¿½ inner join com a tabela orcunidade, false para left join.
  var $inner_atv = true; // True se darï¿½ inner join com a tabela rhregime, false para left join.
  var $inner_res = true; // True se darï¿½ inner join com a tabela rhpesrescisao, false para left join.
  var $inner_ban = true; // True se darï¿½ inner join com a tabela rhpesbanco, false para left join.
  var $inner_pad = true; // True se darï¿½ inner join com a tabela rhpespadrao, false para left join.
  var $inner_inf = false; // True se darï¿½ inner join com a tabela infla, false para left outer join.

  var $usar_pes = false; // Se usarï¿½ inner ou left join com a tabela rhpessoalmov (SOMENTE PARA GERAR PELO PONGER).
  var $usar_cgm = false; // Se usarï¿½ inner ou left join com a tabela CGM.
  var $usar_fun = false; // Se usarï¿½ inner ou left join com a tabela rhfuncao.
  var $usar_lot = false; // Se usarï¿½ inner ou left join com a tabela rhlota.
  var $usar_exe = false; // Se usarï¿½ inner ou left join com a tabela rhlotaexe.
  var $usar_org = false; // Se usarï¿½ inner ou left join com a tabela orcunidade.
  var $usar_atv = false; // Se usarï¿½ inner ou left join com a tabela rhregime.
  var $usar_res = false; // Se usarï¿½ inner ou left join com a tabela rhpesrescisao.
  var $usar_ban = false; // Se usarï¿½ inner ou left join com a tabela rhpesbanco.
  var $usar_pad = false; // Se usarï¿½ inner ou left join com a tabela rhpespadrao.
  var $usar_inf = false; // Se usarï¿½ inner ou left join com a tabela infla;

  function gera_subsql($sqlDENTRO,$camposFORA=" * ",$orderbFORA="",$wheresFORA="",$ALIAS=" x ",$ano="",$mes=""){

    if(trim($camposFORA) == "" || $camposFORA == null){
    	$camposFORA = " * ";
    }
    if(trim($ALIAS) == "" || $ALIAS == null){
    	$ALIAS = " x ";
    }

	  $subsql = " select ".$camposFORA;
	  $subsql.= " from (".$sqlDENTRO.") as ".$ALIAS;

    if($this->usar_res == true || $this->usar_atv == true || $this->usar_ban == true || $this->usar_pad == true){
    	$this->usar_pes  = true;
    	$this->inner_sem = false;
    }
	  if($this->usar_exe == true || $this->usar_org == true){
	  	if($this->usar_org == true){
	  		$this->usar_exe = true;
	  	}
	  	$this->usar_lot = true;
    }

    if($this->usar_inf == true){
      $inner = " inner join ";
      if($this->inner_inf == false){
        $inner = " left outer join ";
      }
      $subsql.=       $inner." infla on infla.i02_codigo = ".$ALIAS.".i02_codigo
                                    and extract(year  from infla.i02_data) = ".$ALIAS.".anousu
                                    and extract(month from infla.i02_data) = ".$ALIAS.".mesusu
				    ";
    }

    if($this->inner_sem == false){
	    $inner = " inner join ";
	    if($this->inner_pes == false){
	  	  $inner = " left join ";
	    }
		  $subsql.= $inner." rhpessoal    on rhpessoal.rh01_regist = ".$ALIAS.".regist ";
		  $subsql.=        "             and rhpessoal.rh01_regist = ".db_getsession("DB_instit")." ";

	    if($this->usar_pes == true){
		    $subsql.= $inner." rhpessoalmov on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist ";
		    if($ano != ""){
          $subsql.= "                  and rhpessoalmov.rh02_anousu = $ano ";
		    }
		    if($mes != ""){
          $subsql.= "                  and rhpessoalmov.rh02_mesusu = $mes ";
				}
        $subsql.= "                  and rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")." ";

		  }
      if($this->usar_res == true){
        $inner = " inner join ";
        if($this->inner_res == false){
  	      $inner = " left join ";
        }
        $subsql.=       $inner." rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
      }
      if($this->usar_atv == true){
        $inner = " inner join ";
        if($this->inner_atv == false){
  	      $inner = " left join ";
        }
        $subsql.=       $inner." rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg ";
        $subsql.=              "         and rhregime.rh30_instit = rhpessoalmov.rh02_instit ";
      }
      if($this->usar_ban == true){
        $inner = " inner join ";
        if($this->inner_ban == false){
  	      $inner = " left join ";
        }
        $subsql.=       $inner." rhpesbanco on rhpesbanco.rh44_seqpes = rhpessoalmov.rh02_seqpes ";
      }
      if($this->usar_pad == true){
        $inner = " inner join ";
        if($this->inner_pad == false){
  	      $inner = " left join ";
        }
        $subsql.=       $inner." rhpespadrao on rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes ";
		    if($ano != ""){
          $subsql.= "                  and rhpespadrao.rh03_anousu = $ano ";
		    }else{
          $subsql.= "                  and rhpespadrao.rh03_anousu = rhpessoalmov.rh02_anousu ";
		    }
		    if($mes != ""){
          $subsql.= "                  and rhpespadrao.rh03_mesusu = $mes ";
		    }else{
          $subsql.= "                  and rhpespadrao.rh03_mesusu = rhpessoalmov.rh02_mesusu ";
		    }
        $inner = " inner join ";
        if($this->inner_pad == false){
  	      $inner = " left join ";
        }
        $subsql.=       $inner." padroes on padroes.r02_anousu = rhpespadrao.rh03_anousu ";
        $subsql.= "                     and padroes.r02_mesusu = rhpespadrao.rh03_mesusu ";
        $subsql.= "                     and padroes.r02_regime = rhpespadrao.rh03_regime ";
        $subsql.= "                     and padroes.r02_codigo = rhpespadrao.rh03_padrao ";

      }
	    if($this->usar_cgm == true){
		    $inner = " inner join ";
		    if($this->inner_cgm == false){
		  	  $inner = " left join ";
		    }
		    $subsql.=       $inner." cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm ";
		  }
		  if($this->usar_fun == true){
		    $inner = " inner join ";
		    if($this->inner_fun == false){
		  	  $inner = " left join ";
		    }
		    $subsql.=       $inner." rhfuncao on rhfuncao.rh37_funcao = rhpessoalmov.rh02_funcao ";
		    $subsql.=              "         and rhfuncao.rh37_instit =  ".db_getsession("DB_instit")." ";
		  }
		  if($this->usar_lot == true){
		    $inner = " inner join ";
		    if($this->inner_lot == false){
		  	  $inner = " left join ";
		    }
		    $subsql.=       $inner." rhlota on rhlota.r70_codigo = rhpessoalmov.rh02_lota ";
		    $subsql.=              "       and rhlota.r70_instit = rhpessoalmov.rh02_instit";
		  }
			if($this->usar_exe == true){
			  $inner = " inner join ";
			  if($this->inner_exe == false){
				  $inner = " left join ";
			  }
	      $subsql.=       $inner." rhlotaexe on rhlotaexe.rh26_anousu = ".$ano."
                                          and rhlotaexe.rh26_codigo = rhlota.r70_codigo ";
  	  }
			if($this->usar_org == true){
			  $inner = " inner join ";
			  if($this->inner_org == false){
				  $inner = " left join ";
			  }
			  $subsql.=       $inner." orcunidade on orcunidade.o41_anousu  = rhlotaexe.rh26_anousu
                                           and orcunidade.o41_orgao   = rhlotaexe.rh26_orgao
                                           and orcunidade.o41_unidade = rhlotaexe.rh26_unidade";
			  $subsql.=       $inner." orcorgao   on orcorgao.o40_anousu    = orcunidade.o41_anousu
                                           and orcorgao.o40_orgao     = orcunidade.o41_orgao
																					 and orcorgao.o40_instit    = ".db_getsession("DB_instit")." ";
			}
    }
	  $subsql.= $wheresFORA;
	  $subsql.= $orderbFORA;
	  return $subsql;
  }
}

function debitos_numpre_carne_recibopaga($numpre,$numpar,$datausu,$anousu,$instit=null,$where="" ){

  global $k03_numpre,$k00_dtvenc;

	if($instit==null){
		$instit=db_getsession('DB_instit');
	}

  $iNumpreArrecad = $numpre;

  $sSqlNumpreNovo  = " select max(k00_numnov) as k00_numnov from recibopaga where k00_numpre = $numpre ";
  if ($numpar <> 0 ) {
	  $sSqlNumpreNovo .= " and k00_numpar = $numpar limit 1";
	}

	$rsNumpreNovo   = db_query($sSqlNumpreNovo);
	$numpre         = pg_result($rsNumpreNovo,0,"k00_numnov");

  $sql  = " select distinct k00_numpre,                                                                 ";
  if ($numpar <> 0 ) {
   	$sql .= "        k00_numpar,                                                                        ";
	}else{
   	$sql .= "       0 as k00_numpar,                                                                    ";
	}
	$sql .= "        k00_numnov,                                                                          ";
	$sql .= "        sum(y.vlrhis)         as vlrhist,                                                    ";
	$sql .= "        sum(y.vlrcor)         as vlrcor,                                                     ";
	$sql .= "        sum(y.vlrjuros)       as vlrjuros,                                                   ";
	$sql .= "        sum(y.vlrmulta)       as vlrmulta,                                                   ";
	$sql .= "        sum(y.vlrdesconto)    as vlrdesconto,                                                ";
	$sql .= "        sum(y.total)          as total,                                                      ";
	$sql .= "        sum(y.qinfla)         as qinfla,                                                     ";
	$sql .= "        min(y.ninfla)         as ninfla                                                      ";
	$sql .= "   from ( select distinct                                                                    ";
	$sql .= "	                k00_numnov,                                                                 ";
	$sql .= "	                k00_numpre,                                                                 ";
	$sql .= "	  						  k00_numpar,                                                                 ";
	$sql .= "	  							k00_receit,                                                                 ";
	$sql .= "                 substr(fc_calcula,2,13)::float8 as vlrhis,                                  ";
	$sql .= "                 substr(fc_calcula,15,13)::float8 as vlrcor,                                 ";
	$sql .= "                 substr(fc_calcula,28,13)::float8 as vlrjuros,                               ";
	$sql .= "                 substr(fc_calcula,41,13)::float8 as vlrmulta,                               ";
	$sql .= "                 substr(fc_calcula,54,13)::float8 as vlrdesconto,                            ";
	$sql .= "                 (substr(fc_calcula,15,13)::float8+                                          ";
	$sql .= "                 substr(fc_calcula,28,13)::float8+                                           ";
	$sql .= "                 substr(fc_calcula,41,13)::float8-                                           ";
	$sql .= "                 substr(fc_calcula,54,13)::float8) as total,                                 ";
	$sql .= "                 substr(fc_calcula,77,17)::float8 as qinfla,                                 ";
	$sql .= "                 substr(fc_calcula,94,4)::varchar(5) as ninfla                               ";
	$sql .= "          from (select $iNumpreArrecad       as k00_numpre,                                  ";
	$sql .= "                      	recibopaga.k00_numnov as k00_numnov,                                  ";
	$sql .= "                       arrecad.k00_numpar,                                                   ";
	$sql .= "                       arrecad.k00_receit,                                                   ";
	$sql .= "                       fc_calcula(arrecad.k00_numpre,                                        ";
  $sql .= "	                                 arrecad.k00_numpar,                                        ";
  $sql .= "																	 arrecad.k00_receit,                                        ";
  $sql .= "																	 case                                                       ";
  $sql .= "																	   when arrecad.k00_dtvenc > '".db_vencimento($datausu)."'  ";
  $sql .= "																		   then arrecad.k00_dtvenc                                ";
  $sql .= "																		 else '".db_vencimento($datausu)."'                       ";
  $sql .= "																	 end,                                                       ";
  $sql .= "																	 case                                                       ";
  $sql .= "																	   when arrecad.k00_dtvenc > '".db_vencimento($datausu)."'  ";
  $sql .= "                                       then arrecad.k00_dtvenc                               ";
  $sql .= "																		 else '".db_vencimento($datausu)."'                       ";
  $sql .= "																	 end, ".$anousu.")                                          ";
	$sql .= "                  from arrecad                                                               ";
	$sql .= "                       inner join recibopaga on recibopaga.k00_numpre = arrecad.k00_numpre   ";
	$sql .= "                                            and recibopaga.k00_numpar = arrecad.k00_numpar   ";
	$sql .= "                       inner join arreinscr  on arreinscr.k00_numpre  = arrecad.k00_numpre   ";
	$sql .= "                       inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre   ";
	$sql .= "                                            and arreinstit.k00_instit = $instit              ";
	$sql .= "                 where recibopaga.k00_numnov = $numpre                                       ";
  if ($numpar <> 0 ) {
	  $sql .= "                   and arrecad.k00_numpar    = $numpar                                     ";
	}

	$sql .= "".($where!=""?"    and $where ":"")."                                                        ";
	$sql .= "                ) as x                                                                       ";
	$sql .= "         ) as y                                                                              ";
	$sql .= "  group by k00_numpre,                                                                       ";
	$sql .= "           k00_numnov                                                                        ";

  if ($numpar <> 0 ) {
  	$sql .= "        ,k00_numpar                                                                        ";
	}

  $result = db_query($sql) or die("<br><br><blink><font color=red>VERIFIQUE INFLATORES!!!<br></blink><font color=black> <br> $sql");

  if (pg_numrows($result) == 0 ){
  	die("<br><br> $sql");
     return false;
  }
  return $result;

}
?>
