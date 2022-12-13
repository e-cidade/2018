<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_db_config_classe.php");

db_postmemory($_SESSION);

$cldb_config = new cl_db_config;
$result = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit")));
db_fieldsmemory($result,0);
if($cgc!="87366159000102"){

  $result = db_query("
  select o08_reduz,k12_conta,k12_receit,round(sum(arrec),2) as arrec, round(sum(estorno),2) as estorno
  from orcam
    left outer join
  (
  select k02_estorc,k12_conta,k12_receit,sum(k12_arrec) as arrec, sum(k12_estorno) as estorno
  from
  (select k02_estorc,corrente.k12_conta,cornump.k12_receit,case when cornump.k12_valor > 0 then cornump.k12_valor else 0 end as k12_arrec, case when cornump.k12_valor < 0 then cornump.k12_valor else 0 end as k12_estorno
   from corrente
       left join cornump
	     on corrente.k12_id     = cornump.k12_id
	     and corrente.k12_data   = cornump.k12_data
	     and corrente.k12_autent = cornump.k12_autent
       left join tabrec
	     on cornump.k12_receit = k02_codigo
       left join taborc
	    on tabrec.k02_codigo = taborc.k02_codigo and taborc.k02_anousu = ".$GLOBALS["DB_anousu"]."
   where corrente.k12_instit = " . db_getsession('DB_instit') . " and corrente.k12_data = '$datai'
	 and k12_numpre not in (select k00_numpre from recibo where k00_numpre = k12_numpre)
   order by taborc.k02_estorc
  ) as x
  group by k12_conta,k02_estorc ,k12_receit
  order by k12_conta,k02_estorc
  ) as receitas
  on k02_estorc::char(13) = o02_codigo and o02_anousu =  ".$GLOBALS["DB_anousu"]."
  left outer join receita on o08_codigo::char(13) = orcam.o02_codigo and orcam.o02_anousu = o08_anousu
  where arrec <> 0 or estorno <> 0
  group by k12_conta,o08_reduz,k12_receit
  ");

  $numrows = pg_numrows($result);
  set_time_limit(0);
  $clabre_arquivo =  new cl_abre_arquivo("/prg/opt/zim/".str_replace("-","",$datai));

  if($clabre_arquivo->arquivo!=false){
    if($numrows!=false){
      for($i=0;$i<$numrows;$i++){
	     db_fieldsmemory($result,$i);
	 fputs($clabre_arquivo->arquivo,str_pad($k12_conta,8)."|");
	 fputs($clabre_arquivo->arquivo,str_pad($o08_reduz,8)."|");
	 fputs($clabre_arquivo->arquivo,str_pad($arrec,20)."|");
	 fputs($clabre_arquivo->arquivo,str_pad($estorno,20)."|");
	 fputs($clabre_arquivo->arquivo,str_pad(str_replace("-","",$datai),10)."|");
	 fputs($clabre_arquivo->arquivo,"O"."\n");
      }
    }
  // receita extra
  $result = db_query("
  select c01_reduz,k12_conta,k12_receit,sum(k12_arrec) as arrec, sum(k12_estorno) as estorno
  from
  (select c01_reduz,k02_estpla,corrente.k12_conta,cornump.k12_receit,case when cornump.k12_valor > 0 then cornump.k12_valor else 0 end as k12_arrec, case when cornump.k12_valor < 0 then cornump.k12_valor else 0 end as k12_estorno
   from corrente
	left join cornump
	     on corrente.k12_id     = cornump.k12_id
	     and corrente.k12_data   = cornump.k12_data
	     and corrente.k12_autent = cornump.k12_autent
       left join tabrec
	     on cornump.k12_receit = k02_codigo
       left join tabplan
		 on tabrec.k02_codigo = tabplan.k02_codigo and tabplan.k02_anousu = ".$GLOBALS["DB_anousu"]."
       inner join plano
		 on tabplan.k02_estpla = plano.c01_estrut and plano.c01_anousu = ".$GLOBALS["DB_anousu"]."

   where corrente.k12_instit = " . db_getsession('DB_instit') . " and corrente.k12_data = '$datai'
   order by tabplan.k02_estpla
  ) as x
  group by c01_reduz,k12_conta,k02_estpla ,k12_receit
  order by k12_conta,k02_estpla

  ");

  $numrows = pg_numrows($result);
    if($numrows!=false){
      for($i=0;$i<$numrows;$i++){
	     db_fieldsmemory($result,$i);
	 fputs($clabre_arquivo->arquivo,str_pad($k12_conta,8)."|");
	 fputs($clabre_arquivo->arquivo,str_pad($c01_reduz,8)."|");
	 fputs($clabre_arquivo->arquivo,str_pad($arrec,20)."|");
	 fputs($clabre_arquivo->arquivo,str_pad($estorno,20)."|");
	 fputs($clabre_arquivo->arquivo,str_pad(str_replace("-","",$datai),10)."|");
	 fputs($clabre_arquivo->arquivo,"E"."\n");
      }
  }


  // lista os recibos com os historicos

  $result = db_query("
  select k00_histtxt,o08_reduz,k12_conta,k12_receit,round(sum(arrec),2) as arrec, round(sum(estorno),2) as estorno
  from orcam
    left outer join
  (
  select k00_histtxt,k02_estorc,k12_conta,k12_receit,sum(k12_arrec) as arrec, sum(k12_estorno) as estorno
  from
  (select k12_numpre,k02_estorc,corrente.k12_conta,cornump.k12_receit,case when cornump.k12_valor > 0 then cornump.k12_valor else 0 end as k12_arrec, case when cornump.k12_valor < 0 then cornump.k12_valor else 0 end as k12_estorno
   from corrente
	left join cornump
	     on corrente.k12_id      = cornump.k12_id
	     and corrente.k12_data   = cornump.k12_data
	     and corrente.k12_autent = cornump.k12_autent
       left join tabrec
	     on cornump.k12_receit = k02_codigo
       left join taborc
	     on tabrec.k02_codigo = taborc.k02_codigo and taborc.k02_anousu = ".$GLOBALS["DB_anousu"]."
   where corrente.k12_instit = " . db_getsession('DB_instit') . " and corrente.k12_data = '$datai'
	 and k12_numpre in (select k00_numpre from recibo where k00_numpre = k12_numpre)
   order by taborc.k02_estorc
  ) as x
    left join arrehist on k00_numpre = k12_numpre
  group by k12_conta,k02_estorc ,k12_receit, k00_histtxt
  order by k12_conta,k02_estorc
  ) as receitas
  on k02_estorc::char(13) = o02_codigo and o02_anousu =  ".$GLOBALS["DB_anousu"]."
  left outer join receita on o08_codigo::char(13) = orcam.o02_codigo and orcam.o02_anousu = o08_anousu
  where arrec <> 0 or estorno <> 0
  group by k12_conta,o08_reduz,k12_receit,k00_histtxt
  ");

  $numrows = pg_numrows($result);

    if($numrows!=false){
      for($i=0;$i<$numrows;$i++){
	     db_fieldsmemory($result,$i);
	 fputs($clabre_arquivo->arquivo,str_pad($k12_conta,8)."|");
	 fputs($clabre_arquivo->arquivo,str_pad($o08_reduz,8)."|");
	 fputs($clabre_arquivo->arquivo,str_pad($arrec,20)."|");
	 fputs($clabre_arquivo->arquivo,str_pad($estorno,20)."|");
	 fputs($clabre_arquivo->arquivo,str_pad(str_replace("-","",$datai),10)."|");
	 fputs($clabre_arquivo->arquivo,"O|");
	 fputs($clabre_arquivo->arquivo,str_replace(chr(13),"",str_pad($k00_histtxt,200))."\n");
      }
    }

    fclose($clabre_arquivo->arquivo);
    db_redireciona("db_erros.php?fechar=true&db_erro=Arquivo gerado : ".$clabre_arquivo->nomearq);
  }else{
    db_redireciona("db_erros.php?fechar=true&db_erro=Erro ao gerar arquivo. Verifique Permissoes. Arquivo: ".$clabre_arquivo->nomearq);
  }
}else{

  $result = db_query("
  select k02_estorc,k12_conta,k12_receit,round(sum(arrec),2) as arrec, round(sum(estorno),2) as estorno
  from (
  select k02_estorc,k12_conta,k12_receit,sum(k12_arrec) as arrec, sum(k12_estorno) as estorno
  from
  (select k02_estorc,corrente.k12_conta,cornump.k12_receit,case when cornump.k12_valor > 0 then cornump.k12_valor else 0 end as k12_arrec, case when cornump.k12_valor < 0 then cornump.k12_valor else 0 end as k12_estorno
   from corrente
       left join cornump
	     on corrente.k12_id     = cornump.k12_id
	     and corrente.k12_data   = cornump.k12_data
	     and corrente.k12_autent = cornump.k12_autent
       left join tabrec
	     on cornump.k12_receit = k02_codigo
       left join taborc
	    on tabrec.k02_codigo = taborc.k02_codigo and taborc.k02_anousu = ".$GLOBALS["DB_anousu"]."
   where corrente.k12_instit = " . db_getsession('DB_instit') . " and corrente.k12_data = '$datai'
   order by taborc.k02_estorc
  ) as x
  group by k12_conta,k02_estorc ,k12_receit
  order by k12_conta,k02_estorc
  ) as x
  where arrec <> 0 or estorno <> 0
  group by k12_conta,k02_estorc,k12_receit
  ");

  $numrows = pg_numrows($result);
  set_time_limit(0);
  $clabre_arquivo =  new cl_abre_arquivo("/tmp/".str_replace("-","",$datai));

  if($clabre_arquivo->arquivo!=false){
    if($numrows!=false){
      for($i=0;$i<$numrows;$i++){
	     db_fieldsmemory($result,$i);
	 fputs($clabre_arquivo->arquivo,str_pad($k12_conta,8)."|");
	 fputs($clabre_arquivo->arquivo,str_pad($k12_receit,3)."|");
	 fputs($clabre_arquivo->arquivo,str_pad($k02_estorc,15)."|");
	 fputs($clabre_arquivo->arquivo,str_pad($arrec  ,20,'0',STR_PAD_LEFT)."|");
         fputs($clabre_arquivo->arquivo,str_pad($estorno,20,'0',STR_PAD_LEFT)."|");
	 fputs($clabre_arquivo->arquivo,str_pad(str_replace("-","",$datai),10)."|");
	 fputs($clabre_arquivo->arquivo,"O"."\n");
      }
    }
  }
  fclose($clabre_arquivo->arquivo);
  db_redireciona("db_erros.php?fechar=true&db_erro=Arquivo gerado : ".$clabre_arquivo->nomearq);

}
?>