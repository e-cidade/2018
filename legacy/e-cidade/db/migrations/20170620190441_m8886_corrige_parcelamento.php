<?php

use Classes\PostgresMigration;

class M8886CorrigeParcelamento extends PostgresMigration
{
    public function up() {

        $this->execute(
          <<<STRING
set check_function_bodies to on;
create or replace function fc_corrigeparcelamento()
returns void
as $$
declare

  begin

    begin

			create temporary table arrecad_corrigidos as
				select arrecad.k00_numpre,
							 arrecad.k00_numpar,
							 arrecad.k00_numcgm,
							 min(arrecad.k00_dtoper) as k00_dtoper,
							 arrecad.k00_receit,
							 (select k00_hist
									from arrecad arrecad_ori
								 where arrecad_ori.k00_numpre = arrecad.k00_numpre
									 and case
									       when arrecad_ori.k00_numpar = 0 then true
												 when arrecad_ori.k00_numpar <> 0 then arrecad_ori.k00_numpar = arrecad.k00_numpar
											 end
									 and arrecad_ori.k00_receit = arrecad.k00_receit
								 order by k00_hist desc limit 1) as k00_hist,
							 sum(k00_valor) as k00_valor,
							 min(arrecad.k00_dtvenc) as k00_dtvenc,
							 min(arrecad.k00_numtot) as k00_numtot,
							 min(arrecad.k00_numdig) as k00_numdig,
							 arrecad.k00_tipo,
							 min(arrecad.k00_tipojm) as k00_tipojm
					from arrecad
							 inner join numpres_parc on arrecad.k00_numpre = numpres_parc.k00_numpre
																			and case
																			      when numpres_parc.k00_numpar = 0 then true
																						when numpres_parc.k00_numpar <> 0 then
																			        arrecad.k00_numpar = numpres_parc.k00_numpar
																					end
				 group by arrecad.k00_numpre,
									arrecad.k00_numpar,
									arrecad.k00_numcgm,
									arrecad.k00_receit,
									arrecad.k00_tipo ;
	  exception
		  when duplicate_table then
    end;

    delete from arrecad
     using arrecad_corrigidos
     where arrecad.k00_numpre = arrecad_corrigidos.k00_numpre
       and arrecad.k00_numpar = arrecad_corrigidos.k00_numpar
       and arrecad.k00_receit = arrecad_corrigidos.k00_receit;

    insert into arrecad (k00_numpre,k00_numpar,k00_numcgm,k00_dtoper,k00_receit,k00_valor,k00_dtvenc,k00_numtot,k00_numdig,k00_tipo,k00_tipojm,k00_hist)
    select k00_numpre,
           k00_numpar,
           k00_numcgm,
           k00_dtoper,
           k00_receit,
           k00_valor,
           k00_dtvenc,
           k00_numtot,
           k00_numdig,
           k00_tipo,
           k00_tipojm,
           k00_hist
      from arrecad_corrigidos;

    /* Verificamos se existe registros duplicados na divida */
    perform v01_numpre
            ,v01_numpar
            ,count(*)
       from divida
      where v01_numpre in (select k00_numpre
                             from numpres_parc)
      group by v01_numpre, v01_numpar
     having count(*) > 1;
    if found then

      /*
       * Cria uma tabela com os numpres/numpars duplicados na dívida,
       * isolando somente os numpres envolvidos no parcelamento
       */
      create temporary table divida_duplicado as
      select  v01_numpre
             ,v01_numpar
             ,count(*)
        from divida
       where v01_numpre in (select k00_numpre
                              from numpres_parc)
       group by v01_numpre, v01_numpar
      having count(*) > 1;

      /*
       * Cria uma tabela com os dados que serão mantidos na base somando os valores da divida que será excluida a seguir
       */
      create temporary table divida_correta as
      select min(v01_coddiv) as v01_coddiv
             ,v01_numcgm
             ,min(v01_dtinsc) as v01_dtinsc
             ,min(v01_exerc) as v01_exerc
             ,v01_numpre
             ,v01_numpar
             ,v01_numtot
             ,sum(v01_vlrhis) as v01_vlrhis
             ,v01_proced
             ,v01_livro
             ,v01_folha
             ,max(v01_dtvenc) as v01_dtvenc
             ,min(v01_dtoper) as v01_dtoper
             ,sum(v01_valor) as v01_valor
             ,v01_obs
             ,v01_numdig
             ,v01_instit
             ,min(v01_dtinclusao) as v01_dtinclusao
             ,v01_processo
             ,v01_dtprocesso
             ,v01_titular
        from divida
             inner join numpres_parc on numpres_parc.k00_numpre = divida.v01_numpre
       where (divida.v01_numpre in (select v01_numpre from divida_duplicado))
       group by  v01_numcgm
                ,v01_numpre
                ,v01_numpar
                ,v01_numtot
                ,v01_proced
                ,v01_livro
                ,v01_folha
                ,v01_obs
                ,v01_numdig
                ,v01_instit
                ,v01_processo
                ,v01_dtprocesso
                ,v01_titular;

      /*
       * Cria uma tabela para termos os coddivs que serão excluidos do sistema
       */
      create temporary table divida_excluir as
      select  max(divida.v01_coddiv) as coddiv_excluir
             ,divida.v01_numpre
             ,divida.v01_numpar
        from divida
             inner join divida_correta on divida_correta.v01_numpre = divida.v01_numpre
                                      and divida_correta.v01_numpar = divida.v01_numpar
       group by  divida.v01_numpre
                ,divida.v01_numpar;



      create temporary table bkp_certdiv            as select * from certdiv            where v14_coddiv in (select v01_coddiv from divida_correta);
      create temporary table bkp_divcontr           as select * from divcontr           where v01_coddiv in (select v01_coddiv from divida_correta);
      create temporary table bkp_dividaprotprocesso as select * from dividaprotprocesso where v88_divida in (select v01_coddiv from divida_correta);
      create temporary table bkp_divimportareg      as select * from divimportareg      where v04_coddiv in (select v01_coddiv from divida_correta);
      create temporary table bkp_divinscr           as select * from divinscr           where v01_coddiv in (select v01_coddiv from divida_correta);
      create temporary table bkp_divmatric          as select * from divmatric          where v01_coddiv in (select v01_coddiv from divida_correta);
      create temporary table bkp_divold             as select * from divold             where k10_coddiv in (select v01_coddiv from divida_correta);
      create temporary table bkp_issvardiv          as select * from issvardiv          where q19_coddiv in (select v01_coddiv from divida_correta);
      create temporary table bkp_termodiv           as select * from termodiv           where coddiv     in (select v01_coddiv from divida_correta);

      /* Exclui as dividas para serem inseridas com os valores corretos */
      delete from certdiv            where v14_coddiv in (select v01_coddiv from divida_correta);
      delete from divcontr           where v01_coddiv in (select v01_coddiv from divida_correta);
      delete from dividaprotprocesso where v88_divida in (select v01_coddiv from divida_correta);
      delete from divimportareg      where v04_coddiv in (select v01_coddiv from divida_correta);
      delete from divinscr           where v01_coddiv in (select v01_coddiv from divida_correta);
      delete from divmatric          where v01_coddiv in (select v01_coddiv from divida_correta);
      delete from divold             where k10_coddiv in (select v01_coddiv from divida_correta);
      delete from issvardiv          where q19_coddiv in (select v01_coddiv from divida_correta);
      delete from termodiv           where coddiv     in (select v01_coddiv from divida_correta);
      delete from divida             where v01_coddiv in (select v01_coddiv from divida_correta);

      /* Exclui as dividas que que estariam duplicadas */
      delete from certdiv            where v14_coddiv in (select coddiv_excluir from divida_excluir);
      delete from divcontr           where v01_coddiv in (select coddiv_excluir from divida_excluir);
      delete from dividaprotprocesso where v88_divida in (select coddiv_excluir from divida_excluir);
      delete from divimportareg      where v04_coddiv in (select coddiv_excluir from divida_excluir);
      delete from divinscr           where v01_coddiv in (select coddiv_excluir from divida_excluir);
      delete from divmatric          where v01_coddiv in (select coddiv_excluir from divida_excluir);
      delete from divold             where k10_coddiv in (select coddiv_excluir from divida_excluir);
      delete from issvardiv          where q19_coddiv in (select coddiv_excluir from divida_excluir);
      delete from termodiv           where coddiv     in (select coddiv_excluir from divida_excluir);
      delete from divida             where v01_coddiv in (select coddiv_excluir from divida_excluir);

      /* insere os valores encontrados anteriormente */
      insert into divida             select * from divida_correta;
      insert into certdiv            select * from bkp_certdiv;
      insert into divcontr           select * from bkp_divcontr;
      insert into dividaprotprocesso select * from bkp_dividaprotprocesso;
      insert into divimportareg      select * from bkp_divimportareg;
      insert into divinscr           select * from bkp_divinscr;
      insert into divmatric          select * from bkp_divmatric;
      insert into divold             select * from bkp_divold;
      insert into issvardiv          select * from bkp_issvardiv;
      insert into termodiv           select * from bkp_termodiv;
    end if;

--		drop table arrecad_corrigidos;

  end;
$$ language 'plpgsql';

STRING
        );
    }

    public function down() {

        $this->execute(
          <<<STRING
set check_function_bodies to on;
create or replace function fc_corrigeparcelamento()
returns void 
as $$
declare
  
  begin

    begin

			create temporary table arrecad_corrigidos as
				select arrecad.k00_numpre, 
							 arrecad.k00_numpar, 
							 arrecad.k00_numcgm, 
							 min(arrecad.k00_dtoper) as k00_dtoper, 
							 arrecad.k00_receit, 
							 (select k00_hist 
									from arrecad arrecad_ori 
								 where arrecad_ori.k00_numpre = arrecad.k00_numpre 
									 and case 
									       when arrecad_ori.k00_numpar = 0 then true
												 when arrecad_ori.k00_numpar <> 0 then arrecad_ori.k00_numpar = arrecad.k00_numpar 
											 end
									 and arrecad_ori.k00_receit = arrecad.k00_receit 
								 order by k00_hist desc limit 1) as k00_hist, 
							 sum(k00_valor) as k00_valor, 
							 min(arrecad.k00_dtvenc) as k00_dtvenc, 
							 min(arrecad.k00_numtot) as k00_numtot, 
							 min(arrecad.k00_numdig) as k00_numdig, 
							 arrecad.k00_tipo, 
							 min(arrecad.k00_tipojm) as k00_tipojm
					from arrecad 
							 inner join numpres_parc on arrecad.k00_numpre = numpres_parc.k00_numpre 
																			and case 
																			      when numpres_parc.k00_numpar = 0 then true 
																						when numpres_parc.k00_numpar <> 0 then
																			        arrecad.k00_numpar = numpres_parc.k00_numpar
																					end
				 group by arrecad.k00_numpre, 
									arrecad.k00_numpar, 
									arrecad.k00_numcgm, 
									arrecad.k00_receit, 
									arrecad.k00_tipo ;
	  exception
		  when duplicate_table then
    end;

    delete from arrecad 
     using arrecad_corrigidos
     where arrecad.k00_numpre = arrecad_corrigidos.k00_numpre 
       and arrecad.k00_numpar = arrecad_corrigidos.k00_numpar 
       and arrecad.k00_receit = arrecad_corrigidos.k00_receit;

    insert into arrecad (k00_numpre,k00_numpar,k00_numcgm,k00_dtoper,k00_receit,k00_valor,k00_dtvenc,k00_numtot,k00_numdig,k00_tipo,k00_tipojm,k00_hist)
    select k00_numpre,
           k00_numpar,
           k00_numcgm,
           k00_dtoper,
           k00_receit,
           k00_valor,
           k00_dtvenc,
           k00_numtot,
           k00_numdig,
           k00_tipo,
           k00_tipojm,
           k00_hist 
      from arrecad_corrigidos;
		
		
--		drop table arrecad_corrigidos;

  end;
$$ language 'plpgsql';


STRING
        );
    }
}
