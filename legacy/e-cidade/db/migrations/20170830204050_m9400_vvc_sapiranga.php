<?php

use Classes\PostgresMigration;

class M9400VvcSapiranga extends PostgresMigration
{
    public function up()
    {
        $sFuncao = <<<STRING
create or replace function fc_iptu_calculavvc_sapiranga_2017(integer,integer,boolean,boolean) returns tp_iptu_calculavvc as
$$

 declare

     iMatricula       alias for $1;
     iAnousu          alias for $2;
     lMostrademo      alias for $3;
     lRaise           alias for $4;

     nDescontop       numeric default 0;
     nAreatc          numeric default 0;
     nVm2c            numeric default 0;
     nVvcp            numeric default 0;
     nVvc             numeric default 0;

     iPontos          integer default 0;
     iAnoconstrucao   integer default 0;
     iUso             integer default 0;
     iTipoconstrucao  integer default 0;
     iCondominio      integer default 0;
     iNumerocontr     integer default 0;
     lAtualiza        boolean default true;

     rConstr          record;
     rIdconstr        record;

     rtp_iptu_calculavvc tp_iptu_calculavvc%ROWTYPE;

 begin
     perform fc_debug('INICIANDO CALCULO VVC ...', lRaise);

     rtp_iptu_calculavvc.rnVvc       := 0;
     rtp_iptu_calculavvc.rnTotarea   := 0;
     rtp_iptu_calculavvc.riNumconstr := 0;
     rtp_iptu_calculavvc.rtDemo      := '';
     rtp_iptu_calculavvc.rtMsgerro   := 'Retorno ok' ;
     rtp_iptu_calculavvc.rbErro      := 'f';
     rtp_iptu_calculavvc.riCodErro   := 0;
     rtp_iptu_calculavvc.rtErro      := '';

     -- Valor do metro quadrado padrão
     nVm2c := 461.44;
     iNumerocontr := 0;


     select j35_caract
       into iCondominio
       from carlote
            inner join caracter on j31_codigo = j35_caract
       where j31_grupo = 34
         and j35_idbql = (select j01_idbql from iptubase where j01_matric = iMatricula limit 1);

       if iCondominio = 106 then -- condominio
         nVm2c := 692.16;
       end if;

     for rConstr in select * from iptuconstr
                     where j39_matric = iMatricula
                       and j39_dtdemo is null
     loop

       iPontos        := 0;
       iAnoconstrucao := iAnousu - rConstr.j39_ano;
       iNumerocontr   := iNumerocontr + 1;

       for rIdconstr in select j48_caract,j31_pontos::numeric, j31_grupo, j31_descr, j32_descr
         from carconstr
              inner join caracter on j48_caract = j31_codigo
              inner join cargrup  on j31_grupo  = j32_grupo
         where j48_matric = rConstr.j39_matric
           and j48_idcons = rConstr.j39_idcons
       loop

         nDescontop := 0;
         iPontos    := iPontos + rIdconstr.j31_pontos;

         -- uso do solo (se industria por exemplo)
         if rIdconstr.j31_grupo = 5 then
           iUso := rIdconstr.j48_caract;
         end if;

         -- tipo de construcao (alvenaria ou mista)
         if rIdconstr.j31_grupo = 11 then
           iTipoconstrucao := rIdconstr.j48_caract;
         end if;

       end loop; --primeiro

       -- desconto se diferente de industria
       if iUso <> 20 then

         if iAnoconstrucao >= 6 and iAnoconstrucao <= 10 then
           if iTipoconstrucao = 53 or iTipoconstrucao = 54 then
             nDescontop := 5::numeric;
           end if;
           if iTipoconstrucao = 55 then
             nDescontop := 10::numeric;
           end if;
         end if;

         if iAnoconstrucao >= 11 and iAnoconstrucao <= 20 then
           if iTipoconstrucao = 53 or iTipoconstrucao = 54 then
             nDescontop := 10::numeric;
           end if;
           if iTipoconstrucao = 55 then
             nDescontop := 20::numeric;
           end if;
         end if;

         if iAnoconstrucao >= 21 and iAnoconstrucao <= 30 then
           if iTipoconstrucao = 53 or iTipoconstrucao = 54 then
             nDescontop := 20::numeric;
           end if;
           if iTipoconstrucao = 55 then
             nDescontop := 30::numeric;
           end if;
         end if;

         if iAnoconstrucao >= 31 and iAnoconstrucao <= 40 then
           if iTipoconstrucao = 53 or iTipoconstrucao = 54 then
             nDescontop := 30::numeric;
           end if;
           if iTipoconstrucao = 55 then
             nDescontop := 40::numeric;
           end if;
         end if;

         if iAnoconstrucao > 40 then
           nDescontop := 50::numeric;
         end if;

       else
         nDescontop := 50::numeric;
         nVm2c      := 230.72::numeric;
       end if;

       nAreatc := sum(nAreatc::numeric + rConstr.j39_area::numeric)::numeric;

       if iPontos between 0 and 33 then
         iPontos := 33;
       elsif iPontos between 34 and 56 then
         iPontos := 56;
       elsif iPontos between 57 and 85 then
         iPontos := 85;
       elsif iPontos between 86 and 95 then
         iPontos := 95;
       elsif iPontos > 95 then
         iPontos := 100;
       end if;

       perform fc_debug('iPontos:     '||iPontos||' - iAnoconstrucao: '||iAnoconstrucao||' - IDConstr:  '||rConstr.j39_idcons, lRaise);

       nVvcp   := ( iPontos::numeric * nVm2c::numeric * (rConstr.j39_area::numeric/100::numeric)::numeric)::numeric;

       perform fc_debug('1 - nVvcp - '||nVvcp||' descontop - '||nDescontop, lRaise);

       nVvcp   := round(nVvcp - (nVvcp * nDescontop / 100::numeric),2)::numeric;

       nVvc    := round(nVvc + nVvcp,2)::numeric;

       perform fc_debug('2 - nVvcp - '||nVvcp||' descontop - '||nDescontop||' nVvc - '||nVvc, lRaise);
       perform fc_debug('total - '||nVvc||' nVm2c - '||nVm2c||' nVvcp - '||nVvcp, lRaise);

       insert into tmpiptucale (anousu, matric,idcons,areaed,vm2,pontos,valor)
                        values (iAnousu,iMatricula,rConstr.j39_idcons,rConstr.j39_area,nVm2c,iPontos,nVvcp);
       if lAtualiza then
         update tmpdadosiptu set predial = true;
         lAtualiza := false;
       end if;

     end loop;

     rtp_iptu_calculavvc.rnVvc       := nVvc::numeric;
     rtp_iptu_calculavvc.rnTotarea   := nAreatc::numeric;
     rtp_iptu_calculavvc.riNumconstr := iNumerocontr;
     rtp_iptu_calculavvc.rtDemo      := '';
     rtp_iptu_calculavvc.rbErro      := 'f';

     update tmpdadosiptu set vvc = rtp_iptu_calculavvc.rnVvc;

     return rtp_iptu_calculavvc;

 end;

$$  language 'plpgsql';
STRING;

        $this->execute($sFuncao);
    }

    public function down()
    {
        $this->execute("drop function fc_iptu_calculavvc_sapiranga_2017(integer,integer,boolean,boolean);");
    }
}
