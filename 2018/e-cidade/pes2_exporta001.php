<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
  

if (isset($gera)){

if($tipofun == 'E'){
 $xwhere = ' and rh30_regime = 1 ';
}elseif($tipofun == 'C'){
 $xwhere = ' and rh30_regime = 3 ';
}elseif($tipofun == 'L'){
 $xwhere = ' and rh30_regime = 2 ';
}else{
 $xwhere = ' ';
}

  if($exporta == 'B'){
    
  $arq = '/tmp/folha.csv';


  $arquivo = fopen($arq,'w');  
  $sql = "
          select
           coalesce(r38_regist,0)||';'||
           coalesce(r38_nome,'')||';'||
           coalesce(r38_numcgm,0)||';'||
           coalesce(r38_regime,0)||';'||
           coalesce(r38_lotac,'')||';'||
           coalesce(r38_vincul,'')||';'||
           coalesce(r38_padrao,'')||';'||
           coalesce(r38_salari,0)||';'||
           coalesce(r38_funcao,'')||';'||
           coalesce(r38_banco,'')||';'||
           coalesce(r38_agenc,'')||';'||
           coalesce(r38_conta,'')||';'||
           coalesce(r38_situac,0)||';'||
           coalesce(r38_previd,0)||';'||
           coalesce(r38_liq,0)||';'||
           coalesce(r38_prov,0)||';'||
           coalesce(r38_desc,0) as tipo
          from folha
               inner join rhpessoalmov    on rh02_anousu    = $ano
                                         and rh02_mesusu    = $mes
                                         and rh02_regist    = r38_regist
                                         and rh02_instit    = ".db_getsession("DB_instit")."
               inner join rhregime        on rh30_codreg    = rh02_codreg
                                         and rh30_instit    = rh02_instit
          where 1 = 1  
          $xwhere
	 ";
  }elseif($exporta == 'D'){
    
  
   if($tipofun == 'E'){
     $arq = '/tmp/cef_estatutarios.csv';
   }elseif($tipofun == 'C'){
     $arq = '/tmp/cef_extraquadro.csv';
   }elseif($tipofun == 'L'){
     $arq = '/tmp/cef_clt.csv';
   }else{
     $arq = '/tmp/cef.csv';
   }

  $arquivo = fopen($arq,'w');  

  $sql = "
  select        rpad(coalesce(z01_nome,''),70,' ')
       ||';'||  rpad(coalesce(z01_nome,''),32,' ')
       ||';'||  rpad(coalesce(z01_cgccpf,''),11,'0')
       ||';'||  rpad(coalesce(rh16_pis,''),11,'0')
       ||';'||  lpad(trim(to_char(coalesce(rh16_ctps_n,0),'9999999')),'7','0')
            ||  lpad(trim(to_char(coalesce(rh16_ctps_s,0),'99999')),5,'0')
       ||';'||  rpad(coalesce(to_char(rh01_nasc,'DDMMYYYY'),''),8)
       ||';'||  rpad(coalesce(rh01_natura,''),25,' ')
       ||';'||  '  '
       ||';'||  coalesce(case rh01_estciv
                    when 1 then '1'
                    when 2 then '2'
                    when 3 then '7'
                    when 4 then '6'
                    when 5 then '5'
                end,'')  

       ||';'||  rpad(coalesce(conjuge,''),40,' ')
       ||';'||  rpad(coalesce(z01_pai,''),32,' ')
       ||';'||  rpad(coalesce(z01_mae,''),32,' ')
       ||';'||  coalesce(case rh01_sexo
                    when 'F' then '1'
                    when 'M' then '2'
                end,'')  
       ||';'||  lpad(coalesce(z01_ident,''),15,'0')
       ||';'||  lpad(coalesce(z01_identorgao,''),5,'0')
       ||';'||  'RS'           
       ||';'||  rpad(coalesce(to_char(z01_identdtexp,'DDMMYYYY'),''),8) 
       ||';'||  '298'
       ||';'||  to_char(coalesce(rh01_admiss,'01-01-1999'),'DDMMYYYY')
       ||';'||  rpad(trim(coalesce(z01_ender,''))||','||coalesce(z01_numero::char(4),''),40,' ')
       ||';'||  rpad(coalesce(z01_bairro,''),25,' ')
       ||';'||  rpad(coalesce(z01_munic,''),25,' ')
       ||';'||  rpad(coalesce(z01_uf,''),2,' ')
       ||';'||  rpad(coalesce(z01_cep,''),8,' ')
       ||';'||  '51'
       ||';'||  lpad(coalesce(z01_telef,''),12,'0')
       ||';'||  rpad(coalesce(z01_email,''),50,' ')
       ||';'||  coalesce(case rh21_instru
                    when 1 then '8'
                    when 2 then '1'
                    when 3 then '1'
                    when 4 then '1'
                    when 5 then '2'
                    when 6 then '3'
                    when 7 then '4'
                    when 8 then '5'
                    when 9 then '6'
                end,'')
       ||';'||  lpad(translate(trim(coalesce(provento,'')),'.',','),19,'0')  as tipo

from rhpessoal
     inner join cgm             on rh01_numcgm    = z01_numcgm
     inner join rhpessoalmov    on rh02_anousu    = $ano
                               and rh02_mesusu    = $mes
                               and rh02_regist    = rh01_regist
                               and rh02_instit    = ".db_getsession("DB_instit")."
     left join  rhpesrescisao   on rh05_seqpes    = rh02_seqpes
     inner join rhlota          on r70_codigo     = rh02_lota and r70_instit = ".db_getsession("DB_instit")."
     inner join rhfuncao        on rh01_funcao    = rh37_funcao and rh37_instit = ".db_getsession("DB_instit")." 
     inner join rhinstrucao     on rh01_instru    = rh21_instru
     inner join rhestcivil      on rh01_estciv    = rh08_estciv
     left join  rhpesdoc        on rh16_regist    = rh01_regist
     left join  rhpespadrao     on rh02_seqpes    = rh03_seqpes
     inner join rhregime        on rh30_codreg    = rh02_codreg
                               and rh30_instit    = rh02_instit
     left join  rhpesbanco      on rh44_seqpes    = rh02_seqpes
     left join (select rh31_regist,
	               max(case when rh31_gparen = 'C' then rh31_nome else '' end) as conjuge
		from rhdepend
		where rh31_gparen in ('C')
		group by rh31_regist) as dep on dep.rh31_regist = rh01_regist
     left join (select r14_regist,
                        to_char(sum(case when r14_pd = 1 then r14_valor else 0 end ),'99999999.99') as provento
                   from gerfsal 
                        inner join rhrubricas on rh27_rubric = r14_rubric
                                             and rh27_instit = ".db_getsession("DB_instit")."
                   where r14_anousu = $ano
                     and r14_mesusu = $mes
                     and r14_pd != 3 group by r14_regist) as xxx on xxx.r14_regist = rhpessoalmov.rh02_regist
where rh05_seqpes is null  
      $xwhere
order by z01_nome";

  }elseif($exporta == 'F'){
    
  $arq = '/tmp/cadastro_ban.csv';

  $arquivo = fopen($arq,'w');  

  $sql = "
  select
       '0001'
       ||';'||rpad(coalesce(substr(z01_nome,1,35),''),35,' ')
       ||';'||rpad(coalesce(z01_cgccpf,''),11,'0')
       ||';'||rpad(substr(trim(coalesce(z01_ender,''))||','||coalesce(z01_numero::char(4),''),1,35),35,' ')
       ||';'||rpad(coalesce(z01_cep,''),8,' ')
       ||';'||rpad(substr(coalesce(z01_bairro,''),1,20),20,' ')
       ||';'||rpad(coalesce(z01_telef,''),10,' ')
       ||';'||' '
       ||';'||rpad(coalesce(to_char(rh01_nasc,'DDMMYYYY'),''),8)
       ||';'||rh01_sexo
       ||';'||coalesce(case rh01_estciv
                  when 1 then '6'
                  when 2 then '1'
                  when 3 then '4'
                  when 4 then '3'
                  when 5 then '2'
              end,'')  
       ||';'||case when rh01_estciv = 2 then 1 else 4 end 
       ||';'||rpad(coalesce(rh01_natura,''),25,' ')
       ||';'||rpad(substr(coalesce(pai,''),1,35),35,' ')
       ||';'||rpad(substr(coalesce(mae,''),1,35),35,' ')
       ||';'||rpad(coalesce(z01_ident,''),10,'0')
       ||';'||'SSPRS'
       ||';'||'        '
       ||';'||lpad(translate(trim(coalesce(provento,'')),'.',''),15,'0') 
       ||';'||to_char(coalesce(rh01_admiss,'01-01-1999'),'DDMMYYYY')
       ||';'||rpad(substr(coalesce(conj,''),1,35),35,' ')
       ||';'||'           '
       ||';'||rpad(coalesce(substr(z01_nome,1,19),''),19,' ')
       ||';'||'0110' 
       ||';'||rh01_regist
       ||';'||case rh30_regime
                   when 1 then 'Estatutário'
                   when 2 then 'CLT'
                   when 3 then 'Extra-Quadro'
              end
       ||';'||nomeinstabrev as tipo

from rhpessoal
     inner join cgm             on rh01_numcgm    = z01_numcgm
     inner join rhpessoalmov    on rh02_anousu    = $ano
                               and rh02_mesusu    = $mes
                               and rh02_regist    = rh01_regist
     left join  rhpesrescisao   on rh05_seqpes    = rh02_seqpes
     inner join rhlota          on r70_codigo     = rh02_lota and 
                                   r70_instit     = rh02_instit
     inner join rhfuncao        on rh01_funcao    = rh37_funcao and 
                                   rh37_instit    = rh02_instit  
     inner join rhinstrucao     on rh01_instru    = rh21_instru
     inner join rhestcivil      on rh01_estciv    = rh08_estciv
     left join  rhpesdoc        on rh16_regist    = rh01_regist
     left join  rhpespadrao     on rh02_seqpes    = rh03_seqpes
     inner join rhregime        on rh30_codreg    = rh02_codreg
                               and rh30_instit    = rh02_instit
     inner join db_config       on codigo         = rh02_instit
     left join  rhpesbanco      on rh44_seqpes    = rh02_seqpes
     left join (select rh31_regist,
                       max(case when rh31_gparen = 'P' then rh31_nome else '' end) as pai,
	                     max(case when rh31_gparen = 'M' then rh31_nome else '' end) as mae,
	                     max(case when rh31_gparen = 'C' then rh31_nome else '' end) as conj
		from rhdepend
		where rh31_gparen in ('P','M','C')
		group by rh31_regist) as dep on dep.rh31_regist = rh01_regist
     left join (select r14_regist,
                        to_char(sum(case when r14_pd = 1 then r14_valor else 0 end ),'99999999.99') as provento
                   from gerfsal 
                        inner join rhrubricas on rh27_rubric = r14_rubric
                                             and rh27_instit = ".db_getsession("DB_instit")."
                   where r14_anousu = $ano
                     and r14_mesusu = $mes
                     and r14_pd != 3 group by r14_regist) as xxx on xxx.r14_regist = rhpessoalmov.rh02_regist
where rh05_seqpes is null
      $xwhere
order by z01_nome";

  }elseif($exporta == 'R'){
    
  $arq = '/tmp/cadastro_bradesco.csv';

  $arquivo = fopen($arq,'w');  

  $sql = "
  select
       '1'
       ||';'||lpad(coalesce(rh01_regist,'0'),8,'0')
       ||';'||substr(rpad(coalesce(z01_cgccpf,''),11,'0'),1,9)
       ||';'||'0000'
       ||';'||substr(rpad(coalesce(z01_cgccpf,''),11,'0'),10,2)
       ||';'||'1'
       ||';'||'1'
       ||';'||rpad(coalesce(z01_nome,''),70,' ')
       ||';'||rpad(trim(coalesce(z01_ender,'')),40,' ')
       ||';'||rpad(coalesce(coalesce(z01_numero,0)::char(4),''),7,' ')
       ||';'||rpad(trim(coalesce(z01_compl,'')),20,' ')
       ||';'||rpad(substr(coalesce(z01_bairro,''),1,20),20,' ')
       ||';'||substr(rpad(coalesce(z01_cep,''),8,' '),1,5)
       ||';'||substr(rpad(coalesce(z01_cep,''),8,' '),6,3)
       ||';'||'0051'
       ||';'||case when substr(lpad(case when trim(translate(z01_telef,'-.();/,\\ ','')) = ''
                      or z01_telef is null then '0'
                 else trim(translate(z01_telef,'-.();/,\\ ',''))
                 end,12,'0'),5,8) = '00000000'
            then '34801255'
            else substr(lpad(case when trim(translate(z01_telef,'-.();/,\\ ','')) = ''
                      or z01_telef is null then '0'
                 else trim(translate(z01_telef,'-.();/,\\ ',''))
            end,12,'0'),5,8)
       end
       ||';'||'0000'
       ||';'||'00000000'
       ||';'||rpad(trim(coalesce(z01_ender,'')),40,' ')
       ||';'||rpad(coalesce(coalesce(z01_numero,0)::char(4),''),7,' ')
       ||';'||rpad(trim(coalesce(z01_compl,'')),20,' ')
       ||';'||rpad(substr(coalesce(z01_bairro,''),1,20),20,' ')
       ||';'||substr(rpad(coalesce(z01_cep,''),8,' '),1,5)
       ||';'||substr(rpad(coalesce(z01_cep,''),8,' '),6,3)
       ||';'||'00298'
       ||';'||rpad(coalesce(to_char(rh01_nasc,'DDMMYYYY'),''),8)
       ||';'||rpad(coalesce(rh01_natura,''),30,' ')
       ||';'||'RS'
       ||';'||case rh01_sexo
              when 'F' then '2'
              when 'M' then '1'
         end
       ||';'||rpad(case when trim(z01_pai) = '' or z01_pai is null then trim(coalesce(pai,'')) else z01_pai end,40,' ')
       ||';'||rpad(case when trim(z01_mae) = '' or z01_mae is null then trim(coalesce(mae,'')) else z01_mae end,40,' ')
       ||';'||'1'
       ||';'||'BRASILEIRA'
       ||';'||case when rh08_estciv > 2 then 3 else rh08_estciv end
       ||';'||rpad('RG',30,' ')
       ||';'||rpad(coalesce(z01_ident,''),15,' ')
       ||';'||'        '
       ||';'||rpad('SSP-RS',20,' ')
       ||';'||rpad(substr(nomeinst,1,40),40,' ')
       ||';'||rpad(rh37_descr,40,' ')
       ||';'||lpad(translate(trim(coalesce(provento,'0')),'.,',''),15,'0')
       ||';'||lpad(fc_idade(rh01_admiss,current_date),4,'0')
       ||';'||rpad(ender,40,' ')
       ||';'||substr(rpad(cep,8,' '),1,5)
       ||';'||substr(rpad(cep,8,' '),6,3)
       ||';'||rpad(coalesce(conj,''),40,' ')
       ||';'||'   '
       ||';'||'    '
       ||';'||' '
       ||';'||'   '
       ||';'||'    '
       ||';'||'       '
       ||';'||' '
       ||';'||'  '
       ||';'||'   '
       ||';'||'    '
       ||';'||' '
       ||';'||'   '
       ||';'||'    '
       ||';'||'           '
       ||';'||' '
       ||';'||'  '
       ||';'||'        '
       ||';'||'        '
       ||';'||'        '
       ||';'||'    '
       ||';'||'    '
       ||';'||' '
       ||';'||' '
       ||';'||' '      
       ||';'||'   '
       ||';'||'   '
       ||';'||'     '
       ||';'||' '
       ||';'||'             '
       ||';'||rpad('',76,' ') as tipo
from rhpessoal
     inner join cgm             on rh01_numcgm    = z01_numcgm
     inner join rhpessoalmov    on rh02_anousu    = $ano
                               and rh02_mesusu    = $mes
                               and rh02_regist    = rh01_regist
                               and rh02_instit    = ".db_getsession("DB_instit")."
     inner join db_config       on codigo         = rh02_instit
     left join  rhpesrescisao   on rh05_seqpes    = rh02_seqpes
     inner join rhlota          on r70_codigo     = rh02_lota   and r70_instit  = rh02_instit
     inner join rhfuncao        on rh01_funcao    = rh37_funcao and rh37_instit = rh02_instit
     inner join rhinstrucao     on rh01_instru    = rh21_instru
     inner join rhestcivil      on rh01_estciv    = rh08_estciv
     left join  rhpesdoc        on rh16_regist    = rh01_regist
     left join  rhpespadrao     on rh02_seqpes    = rh03_seqpes
     inner join rhregime        on rh30_codreg    = rh02_codreg
                               and rh30_instit    = rh02_instit
     left join  rhpesbanco      on rh44_seqpes    = rh02_seqpes
     left join (select rh31_regist,
                       max(case when rh31_gparen = 'P' then rh31_nome else '' end) as pai,
                       max(case when rh31_gparen = 'M' then rh31_nome else '' end) as mae,
                       max(case when rh31_gparen = 'C' then rh31_nome else '' end) as conj
    from rhdepend
    where rh31_gparen in ('P','M','C')
    group by rh31_regist) as dep on dep.rh31_regist = rh01_regist
     left join (select r14_regist,
                        to_char(sum(case when r14_pd = 1 then r14_valor else 0 end ),'99999999.99') as provento
                   from gerfsal 
                        inner join rhrubricas on rh27_rubric = r14_rubric
                                             and rh27_instit = ".db_getsession("DB_instit")." 
                   where r14_anousu = $ano
                     and r14_mesusu = $mes
                     and r14_pd != 3 group by r14_regist) as xxx on xxx.r14_regist = rhpessoalmov.rh02_regist
where rh05_seqpes is null
      $xwhere
order by z01_nome";

  }elseif($exporta == 'C'){
  
  $arq = '/tmp/cadastro.csv';

  $arquivo = fopen($arq,'w'); 
  $sql = "
  select $ano||';'||
       $mes||';'||
       rh01_regist||';'||
       z01_nome||';'|| 
       coalesce(to_char(rh01_admiss,'DD-MM-YYYY'),'')||';'||
       coalesce(to_char(rh05_recis,'DD-MM-YYYY'),'')||';'||
       rh01_sexo||';'||
       coalesce(to_char(rh01_nasc,'DD-MM-YYYY'),'')||';'||
       r70_estrut||';'||
       r70_descr||';'||
       rh01_funcao||';'||
       trim(rh37_descr)||';'||
       coalesce(z01_ender,'')||';'||
       coalesce(z01_numero,0)||';'||
       coalesce(z01_compl,'')||';'||
       coalesce(z01_bairro,'')||';'||
       coalesce(z01_munic,'')||';'||
       coalesce(z01_uf,'')||';'||
       coalesce(z01_cep,'')||';'||
       coalesce(z01_telef,'')||';'||
       rh21_descr||';'||
       rh08_descr||';'||
       coalesce(rh14_matipe,'0')||';'||
       coalesce(rh16_titele,'0')||';'||
       coalesce(rh16_zonael,'0')||';'||
       coalesce(rh16_secaoe,'0')||';'||
       coalesce(rh16_reserv,'0')||';'||
       coalesce(rh16_catres,'0')||';'||
       coalesce(rh16_ctps_n,0)||';'||
       coalesce(rh16_ctps_s,0)||';'||
       coalesce(rh16_ctps_d,0)||';'||
       coalesce(rh16_ctps_uf,'')||';'||
       coalesce(rh16_pis,'')||';'||
       coalesce(z01_cgccpf,'')||';'||
       coalesce(z01_ident,'')||';'||
       coalesce(rh16_carth_n,0)||';'||
       coalesce(r16_carth_cat,'')||';'||
       coalesce(to_char(rh16_carth_val,'DD-MM-YYYY'),'')||';'||
       coalesce(rh03_padrao,'')||';'||
       coalesce(rh30_descr,'')||';'||
       coalesce(rh30_regime,0)||';'||
       coalesce(rh30_vinculo,'')||';'||
       coalesce(rh44_codban,'')||';'||
       coalesce(rh44_agencia,'')||';'||
       coalesce(rh44_dvagencia,'')||';'||
       coalesce(rh44_conta,'')||';'||
       coalesce(rh44_dvconta,'')||';'||
       coalesce(rh55_estrut,'')||';'||
       coalesce(rh55_descr,'')||';'||
       coalesce(to_char(rh01_trienio,'DD-MM-YYYY'),'')||';'||
       coalesce(to_char(rh01_progres,'DD-MM-YYYY'),'')
       
       as tipo
       
from rhpessoal 
     inner join cgm             on rh01_numcgm    = z01_numcgm
     inner join rhpessoalmov    on rh02_anousu    = $ano
                               and rh02_mesusu    = $mes
                               and rh02_regist    = rh01_regist
                               and rh02_instit    = ".db_getsession("DB_instit")."
     left join  rhpesrescisao   on rh05_seqpes    = rh02_seqpes
     inner join rhlota          on r70_codigo     = rh02_lota and r70_instit = ".db_getsession("DB_instit")."
     inner join rhfuncao        on rh01_funcao    = rh37_funcao and rh37_instit = ".db_getsession("DB_instit")."    
     inner join rhinstrucao     on rh01_instru    = rh21_instru
     inner join rhestcivil      on rh01_estciv    = rh08_estciv
     left join rhiperegist      on rh62_regist    = rh01_regist
     left join rhipe            on rh14_sequencia = rh62_sequencia and rh14_instit = ".db_getsession("DB_instit")."
     left join  rhpeslocaltrab  on rh56_seqpes    = rh02_seqpes
                               and rh56_princ     = 't'
     left join  rhlocaltrab     on rh56_localtrab = rh55_codigo and rh55_instit = ".db_getsession("DB_instit")."
     left join  rhpesdoc        on rh16_regist    = rh01_regist
     left join  rhpespadrao     on rh02_seqpes    = rh03_seqpes
     inner join rhregime        on rh30_codreg    = rh02_codreg and rh30_instit = ".db_getsession("DB_instit")."
     left join  rhpesbanco      on rh44_seqpes    = rh02_seqpes
where 1 = 1 
$xwhere
order by z01_nome
" ;
  }elseif($exporta == 'E'){
    
  $arq = '/tmp/cadastro_banespa.txt';

  $arquivo = fopen($arq,'w');  

$sql= "select rpad(coalesce(z01_cgccpf,''),11,'0')||
       rpad(coalesce(z01_nome,''),64,' ')||                             
       rpad(coalesce(to_char(rh01_nasc,'DD/MM/YYYY'),''),15)||          
       rpad(coalesce(rh01_sexo,''),4)||                                                      
       rpad(coalesce(case rh01_estciv
            when 1 then '1'
            when 2 then '2'
            when 3 then '3'
            when 4 then '5'
            when 5 then '4'
       end,''),12)||                                                        
       rpad('1',14)||                                                            
       lpad(trim(to_char(coalesce(rh16_ctps_n,0),'9999999')),16,'0')|| 
       rpad('  /  /    ',25)||                                                    
       lpad(trim(to_char(coalesce(rh16_ctps_s,0),'99999')),15,'0')||     
       rpad(coalesce(rh16_ctps_uf,''),12,' ')||                                                   
       rpad(coalesce(z01_cep,''),8,' ')||                               
       rpad(coalesce(z01_telef,''),12,'0')||                            
       rpad(coalesce(rh37_funcao::char(4),''),9,' ')||                  
       lpad(translate(trim(coalesce(provento,'')),'.',''),19,'0')||     
       rpad('1',13)||                                                            
       rpad(to_char(coalesce(rh01_admiss,'01-01-1999'),'DD/MM/YYYY'),16)||       
       rpad(coalesce(z01_pai,''),64,' ')||                              
       rpad(coalesce(z01_mae,''),64,' ')||                              
       rpad(coalesce(rh01_nacion::char(1),''),13,' ')||                  
       rpad(coalesce(rh01_natura::char(20),''),20,' ')||                
       rpad(trim(coalesce(z01_ender,'')),30)||                          
       rpad(coalesce(z01_numero::char(4),''),4,' ')||                   
       rpad(' ',15)||                                              
       rpad(coalesce(z01_bairro,''),15,' ')||                           
       rpad(coalesce(z01_munic,''),20,' ')||                            
       rpad(coalesce(z01_uf,''),2,' ') as tipo                               

from rhpessoal
     inner join cgm             on rh01_numcgm    = z01_numcgm
     inner join rhpessoalmov    on rh02_anousu    = $ano
                               and rh02_mesusu    = $mes
                               and rh02_regist    = rh01_regist
                               and rh02_instit    = ".db_getsession('DB_instit')."
     left join  rhpesrescisao   on rh05_seqpes    = rh02_seqpes
     inner join rhlota          on r70_codigo     = rh02_lota 
                               and r70_instit     = rh02_instit
     inner join rhfuncao        on rh01_funcao    = rh37_funcao
                               and rh37_instit    = rh02_instit
     inner join rhinstrucao     on rh01_instru    = rh21_instru
     inner join rhestcivil      on rh01_estciv    = rh08_estciv
     left join  rhpesdoc        on rh16_regist    = rh01_regist
     left join  rhpespadrao     on rh02_seqpes    = rh03_seqpes
     inner join rhregime        on rh30_codreg    = rh02_codreg
                               and rh30_instit    = rh02_instit
     left join  rhpesbanco      on rh44_seqpes    = rh02_seqpes
     left join (select rh31_regist,
                       max(case when rh31_gparen = 'P' then rh31_nome else '' end) as pai,
	               max(case when rh31_gparen = 'M' then rh31_nome else '' end) as mae
		from rhdepend
		where rh31_gparen in ('P','M')
		group by rh31_regist) as dep on dep.rh31_regist = rh01_regist
     left join (select r14_regist,
                        to_char(sum(case when r14_pd = 1 then r14_valor else 0 end ),'99999999.99') as provento
                   from gerfsal 
                        inner join rhrubricas on rh27_rubric = r14_rubric
                                             and rh27_instit = ".db_getsession("DB_instit")." 
                   where r14_anousu = $ano
                     and r14_mesusu = $mes
                     and r14_instit = ".db_getsession("DB_instit")."
                     and r14_pd != 3 group by r14_regist) as xxx on xxx.r14_regist = rhpessoalmov.rh02_regist
where rh05_seqpes is null 
$xwhere
order by z01_nome";

  }elseif($exporta == 'G'){
    
  $arq = '/tmp/cdc_banrisul.txt';

  $arquivo = fopen($arq,'w');  

$sql= "select rpad(coalesce(z01_cgccpf,''),11,'0')||
       rpad(coalesce(z01_nome,''),46,' ')||                             
       lpad(translate(trim(coalesce(provento,'0')),'.',''),17,'0')||     
       lpad(trim(to_char(rh01_regist,'999999')),12,'0')||
       rh44_codban||
       rh44_agencia||
       lpad(trim(to_char(to_number(case when trim(rh44_conta) = '' or rh44_conta is null then '0' else rh44_conta end ,'99999999999999'),'9999999999')),10,'0')||
       lpad(translate(trim(coalesce(liquido,'0')),'.',''),17,'0')||
       rpad(coalesce(rh37_descr,''),60,' ')                             
       as tipo
from rhpessoal
     inner join cgm             on rh01_numcgm    = z01_numcgm
     inner join rhpessoalmov    on rh02_anousu    = $ano
                               and rh02_mesusu    = $mes
                               and rh02_instit    = ".db_getsession('DB_instit')."
                               and rh02_regist    = rh01_regist
     left join  rhpesrescisao   on rh05_seqpes    = rh02_seqpes
     inner join rhlota          on r70_codigo     = rh02_lota 
                               and r70_instit     = rh02_instit
     inner join rhfuncao        on rh01_funcao    = rh37_funcao 
                               and rh37_instit    = rh02_instit
     inner join rhinstrucao     on rh01_instru    = rh21_instru
     inner join rhestcivil      on rh01_estciv    = rh08_estciv
     left join  rhpesdoc        on rh16_regist    = rh01_regist
     left join  rhpespadrao     on rh02_seqpes    = rh03_seqpes
     inner join rhregime        on rh30_codreg    = rh02_codreg
                               and rh30_instit    = rh02_instit
     left join  rhpesbanco      on rh44_seqpes    = rh02_seqpes
     left join (select r14_regist,
                        to_char(sum(case when r14_pd = 1 then r14_valor else 0 end ),'99999999.99') as provento,
                        to_char(sum(case when r14_pd = 2 then r14_valor else 0 end ),'99999999.99') as desco,
                        to_char(sum(case when r14_pd = 1 then r14_valor else r14_valor*(-1) end ),'99999999.99') as liquido
                   from gerfsal 
                        inner join rhrubricas on rh27_rubric = r14_rubric
                                             and rh27_instit = ".db_getsession("DB_instit")."
                   where r14_anousu = $ano
                     and r14_mesusu = $mes
                     and r14_pd != 3 
                     and r14_instit = ".db_getsession('DB_instit')."
                     group by r14_regist) as xxx on xxx.r14_regist = rhpessoalmov.rh02_regist
where rh05_seqpes is null and rh02_instit = ".db_getsession('DB_instit')." 
      $xwhere
order by z01_nome";
  
  }elseif($exporta == 'P'){
    
    $arq = '/tmp/transparencia.csv';
  
    $arquivo = fopen($arq,'w');
  
    $sql  = "select        rh01_regist                                        ";
    $sql .= "       ||';'||trim(z01_nome)                                     ";
    $sql .= "       ||';'||rh02_codreg                                        ";
    $sql .= "       ||';'||coalesce(rh02_funcao,0)                            ";
    $sql .= "       ||';'||coalesce(rh37_descr,' ')                           ";
    $sql .= "       ||';'||coalesce(rh20_cargo,0)                             ";
    $sql .= "       ||';'||coalesce(rh04_descr,' ') as tipo                   ";
    $sql .= "  from pessoal.rhpessoalmov                                      ";
    $sql .= " inner join pessoal.rhpessoal     on rh02_regist = rh01_regist   ";
    $sql .= " inner join protocolo.cgm         on rh01_numcgm = z01_numcgm    ";
    $sql .= "  left join pessoal.rhpesrescisao on rh02_seqpes = rh05_seqpes   ";
    $sql .= " inner join pessoal.rhregime      on rh02_codreg = rh30_codreg   ";
    $sql .= "                                 and rh02_instit = rh30_instit   ";
    $sql .= " inner join pessoal.rhfuncao      on rh02_funcao = rh37_funcao   ";
    $sql .= "                                 and rh02_instit = rh37_instit   ";
    $sql .= "  left  join pessoal.rhpescargo   on rh02_seqpes = rh20_seqpes   ";
    $sql .= "                                 and  rh02_instit = rh20_instit  ";
    $sql .= "  left join pessoal.rhcargo       on rh04_codigo = rh20_cargo    ";
    $sql .= "                                 and rh04_instit = rh20_instit   ";
    $sql .= " where rh05_seqpes is null                                       ";
    $sql .= "   and rh02_anousu = {$ano}                                      ";
    $sql .= "   and rh02_mesusu = {$mes}                                      ";
    $sql .= "   and rh02_instit = ".db_getsession('DB_instit').$xwhere ;             
    $sql .= " order by z01_nome                                               "; 

 
  }
// echo "<br><br><br><br><br>".$sql;
 //exit;
  $result = db_query($sql);
// db_criatabela($result);exit; 
  if($exporta == 'C'){
    fputs($arquivo,"ano;mes;matricula; nome; admissao;rescisao; sexo; nascimento; lotacao; descr_lotacao; funcao; descr_funcao; endereco; numero; complemento; bairro; municipio; uf; cep; telefone; instrucao; estado civil; matr_ipe; titulo; zona; secao; cert_reservista; cat_reserv; ctps_numero; ctps_serie; ctps_digito; ctps_uf; pis;cpf;rg; habilitacao; cat_habilit; validade_habilit; padrao; descr_tipo_vinculo; regime; vinculo; banco; agencia; dig_agencia; conta; dig_conta;estr_local;descr_local;trienio;progressao"."\r\n");
  }elseif($exporta == 'P'){
    fputs($arquivo,"matricula; nome; cod_regime; cod_cargo; descr_cargo; cod_funcao; descr_funcao"."\r\n");
  }elseif($exporta == 'B'){
    fputs($arquivo,"matricula;nome;numcgm;regime;lotacao;vinculo;padrao;salari;funcao;banco;agencia;conta;situacao;previdencia;liquido;proventos;descontos;processamento"."\r\n");
  }elseif($exporta == 'R'){
    fputs($arquivo,"TIPO DE REGISTRO;Nº DA MATRICULA FUNCIONARIO;Nº DO CPF/CNPJ;FILIAL;CONTROLE;CAPACIDADE CIVIL;TIPO DE MOVIMENTO;NOME DO FUNCIONARIO;ENDEREÇO RESIDENCIAL;NUMERO;COMPLEMENTO;BAIRRO;CEP;SUFIXO;FONE (DDD);FONE NÚMERO;FAX (DDD);FAX NÚMERO;ENDEREÇO PARA CORRESPONDÊNCIA;NUMERO DO ENDEREÇO;COMPLEMENTO DO ENDEREÇO;BAIRRO DO ENDER;CEP NUMERO;SUFIXO DO CEP;CÓDIGO DE OCUPAÇÃO;DATA DE NASCIMENTO;NATURALIDADE;UF DE NASCIMENTO;SEXO;NOME DO PAI;NOME DA MÃE;BRASILEIRO/ESTRANGEIRO;NACIONALIDADE;ESTADO CIVIL;TIPO DE DOCUMENTO;NÚMERO DO DOCUMENTO;DATA DE EMISSÃO;ORGÃO EMISSOR;NOME DA EMPRESA;CARGO;RENDA;TEMPO DE SERVIÇO;ENDEREÇO DA EMPRESA;NUMERO CEP;SUFIXO CEP;NOME DO CONJUGÊ;DESTINO DO BANCO;DESTINO DA AGÊNCIA;DÍGITO;DESTINO DO PAB;DESTINO RAZÃO;DESTINO CONTA;DESTINO DIGITO;DESTINO TIPO DA CONTA;FILLER;BANCO ORIGEM;AGENCIA ORIGEM;DÍGITO AG ORIGEM;PAB DE ORIGEM;RAZÀO DE ORIGEM;CONTA DE ORIGEM;DÍGITO CONTA ORIGEM;TIPO DA CONTA;DATA DE RECADASTRAMENTO;DATA DE ABERTURA;DATA DE ÚLTIMO ACERTO;FILLER;AGÊNCIA GESTORA;EMITE CARTÀO;CLIENTE PRIME;CLIENTE PRIVATE;PAB PRIME;BANCO PARA;AGÊNCIA PARA;CONTA PARA;DÍGITO DA CTA PARA;FILLER"."\r\n");
  }elseif($exporta == 'F'){
    fputs($arquivo,"TIPO_REG;NOME;CPF;ENDEREÇO;CEP;BAIRRO;FONE;RAMAL;DT_NASC;SEXO;EST_CIVIL;REG_CASAMENTO;NATURALIDADE;PAI;MAE;IDENTIDADE;ORGAO_EXPE;DT_EMISSÃO;SALÁRIO;DT_ADMISSÃO;CONJUGE;CPF_CONJUGE;NOME_CARTÃO;AG_CONTA;MATRICULA;REGIME;ÓRGAO_PAG"."\r\n");
  }elseif($exporta == 'E'){
    $sql1 = str_pad('CPF',11).
       str_pad('Nome Titular',64).
       str_pad('Data Nascimento',15).       
       str_pad('Sexo',4). 
       str_pad('Estado_Civil',12). 
       str_pad('Tipo Documento',14).
       str_pad('Numero Documento',16).
       str_pad('Data Emissao do Documento',25). 
       str_pad('Serie Documento',15). 
       str_pad('UF Documento',12).
       str_pad('CEP',8).
       str_pad('Telefone',12).
       str_pad('Profissao',9).
       str_pad('Valor_da_Renda',19).
       str_pad('Tipo de Renda',13). 
       str_pad('Data de Admissao',16).
       str_pad('Nome_do_Pai',64).
       str_pad('Nome_da_Mae',64).
       str_pad('Nacionalidade',13).
       str_pad('Naturalidade',20).
       str_pad('Endereco',30).
       str_pad('Numero',4). 
       str_pad('Complemento',15).  
       str_pad('Bairro',15).
       str_pad('Cidade',20).
       str_pad('UF',2);
       fputs($arquivo,$sql1."\r\n");
  }

  for($x = 0;$x < pg_numrows($result);$x++){
    db_fieldsmemory($result,$x);
    fputs($arquivo,$tipo."\r\n");
  }
  fclose($arquivo);

}


?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="right" nowrap title="Digite o Ano / Mes de competência" >
        <strong>Ano / Mês :&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
           $ano = db_anofolha();
           db_input('ano',4,$IDBtxt23,true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
           $mes = db_mesfolha();
           db_input('mes',2,$IDBtxt25,true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr>
        <td align="right"><b>Tipo de Arquivo :&nbsp;&nbsp;<b></td>
	<td align="left">
	<?
	  $arr = array('C'=>'Cadastro',
                 'B'=>'Bancos',
                 'D'=>'Cadastro CEF',
                 'E'=>'Cadastro Banespa',
                 'R'=>'Cadastro Bradesco',
                 'F'=>'Cadastro Banrisul',
                 'G'=>'CDC Banrisul',
                 'P'=>'Portal Transparência'
                 
                 );
	  db_select("exporta",$arr,true,1);
	?>
        </td>
      </tr>
      <tr>
        <td align="right"><b>Tipo de Servidor :&nbsp;&nbsp;<b></td>
	<td align="left">
	<?
	  $arr_f = array('T'=>'Todos',
                   'E'=>'Estatutário', 
                   'C'=>'Comissionados',
                   'L'=>'CLT'
                 );
	  db_select("tipofun",$arr_f,true,1);
	?>
        </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="gera" id="gera" type="submit" value="Processar"  >
 <!--         <input name="verificar" type="submit" value="Download" > -->
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
  <?
  if(isset($gera)){
  	echo "js_montarlista('".$arq."#Arquivo gerado em: ".$arq."','form1');";
  }
  ?>
</script>