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
include("classes/db_selecao_classe.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0"  topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table>
<tr height=25><td>&nbsp;</td></tr>
</table>
<?
db_postmemory($HTTP_POST_VARS);
db_sel_instit();
db_criatermometro('termometro','Concluido...','blue',1);
flush();
$wh = '';

if ($_POST["r44_selec"] != ''){

 $clselecao = new cl_selecao;
 $rsselec   =  $clselecao->sql_record($clselecao->sql_query($r44_selec, db_getsession('DB_instit')));
 db_fieldsmemory($rsselec,0);
 $wh  =  "and $r44_where";

}

if ($_POST["vinculo"] == "A"){
  /**
   * Ativos
   */
  $arq = 'tmp/calc_aticef.csv';

  $arquivo = fopen($arq,'w');  

  if ($versao == 1) { 

    $sSqlAtivos = "
              select distinct(rh01_regist) as matricula,
                     trim(substr(z01_nome,1,40))
                     ||'#'
                     ||trim(substr(z01_cgccpf,1,11))
                     ||'#'
                     ||'".str_pad($nomeinstabrev,20,' ',STR_PAD_RIGHT)."'
                     ||'#'
                     ||trim(to_char(rh01_regist,'999999'))
                     ||'#'
                     ||trim(to_char(rh30_regime,'9'))
                     ||'#'
                     ||case when rh30_regime = 1 then 'S' else 'N' end 
                     ||'#'
                     ||rh01_sexo
                     ||'#'
                     ||to_char(rh01_nasc,'DD/MM/YYYY') 
                     ||'#'
                     ||to_char(rh01_admiss,'DD/MM/YYYY')
                     ||'#'
                     ||to_char(rh01_admiss,'DD/MM/YYYY')
                     ||'#'
                     ||trim(translate(to_char(round(base,2),'99999999,99'),',',''))
                     ||'#'
                     ||trim(translate(to_char(round(prov-desco,2),'99999999,99'),',',''))
                     ||'#'
                     ||case when r70_codigo in (802,804,805) then '2' else '4' end
                     ||'0' as todo
                from rhpessoal 
          inner join cgm          on rh01_numcgm = z01_numcgm 
          inner join rhpessoalmov on rh02_regist = rh01_regist 
                              and rh02_anousu = $ano 
                              and rh02_mesusu = $mes 
                              and rh02_instit   = ".db_getsession('DB_instit')."
          inner join rhlota    on r70_codigo = rh02_lota 
          inner join rhregime  on rh30_codreg = rh02_codreg
          inner join (select r14_regist,
                             sum( case 
                                    when r14_pd = 1 then r14_valor 
                                    else 0 
                                  end ) as prov,
    			                   sum( case 
    			                          when r14_pd = 2 then r14_valor 
     			                          else 0 
     			                         end ) as desco,
     			                   sum( case 
    			                          when r14_rubric = 'R992' then r14_valor 
    			                          else 0 
    			                        end ) as base  
                        from gerfsal 
                       where r14_anousu = $ano and  
                             r14_mesusu = $mes and
                             r14_instit = ".db_getsession('DB_instit')."
                    group by r14_regist ) as sal on r14_regist = rh01_regist 
                       where rh30_vinculo = 'A' and rh30_regime = 1 $wh";
                  
  } elseif ($versao == 2) {

    $sSqlAtivos = "select distinct(rh01_regist) as matricula,
                   (select trim(nomeinstabrev) from db_config where rh02_instit = db_config.codigo)
                    ||'#'
                    ||trim(to_char(rh01_regist,'999999'))
                    ||'#'
                    ||trim(to_char(rh30_regime,'9'))
                    ||'#'
                    ||case when rh30_regime = 3 then 'N' else 'S' end
                    ||'#'
                    ||rh01_sexo
                    ||'#'
                    ||to_char(rh01_nasc,'DD/MM/YYYY') 
                    ||'#'
                    ||to_char(rh01_admiss,'DD/MM/YYYY')
                    ||'#'
                    ||to_char(rh01_admiss,'DD/MM/YYYY')
                    ||'#'
                    ||trim(translate(round(base,2),',',''))
                    ||'#'
                    ||
                      case 
                        when rhfuncao.rh37_funcaogrupo = 0 then 4 else rhfuncao.rh37_funcaogrupo
                      end 
                    ||'#'
                    ||
  	                  case
                        when rhpessoal.rh01_tipadm = 1 
                          then '0'
                        when rhpessoal.rh01_tipadm = 2 
                          then 
  	                        case
  	                          when ( select sum(h16_quant)
																	     from assenta 
																		        inner join tipoasse on h16_assent = h12_codigo 
																		  where h16_regist = rhpessoal.rh01_regist and h12_reltot > 1 
															  	 ) is not null
															  	   then ( select trim(cast(sum(h16_quant) as varchar))
                                            from assenta 
                                                 inner join tipoasse on h16_assent = h12_codigo 
                                            where h16_regist = rhpessoal.rh01_regist and h12_reltot > 1 
                                          )
															else ''
													  end
                        else ''
                      end as todo
              from rhpessoal 
        inner join cgm          on rh01_numcgm = z01_numcgm 
        inner join rhpessoalmov on rh02_regist = rh01_regist 
                               and rh02_anousu = $ano 
                               and rh02_mesusu = $mes 
                               and rh02_instit = ".db_getsession('DB_instit')."
         left join rhfuncao on rhfuncao.rh37_funcao = rhpessoalmov.rh02_funcao
                           and rhfuncao.rh37_instit = rhpessoalmov.rh02_instit
        inner join rhlota       on r70_codigo = rh02_lota 
        inner join rhregime on rh30_codreg = rh02_codreg
        inner join (select r14_regist,
                           sum( case 
                                  when r14_pd = 1 then r14_valor 
                                  else 0 
                                end ) as prov,
                     			sum( case 
                     			       when r14_pd = 2 then r14_valor 
                     			       else 0 
                     			      end ) as desco,
                    			sum( case 
                    			       when r14_rubric = 'R992' then r14_valor 
                    			       else 0 
                  			       end ) as base  
              from gerfsal 
             where r14_anousu = $ano 
               and r14_mesusu = $mes
               and r14_instit = ".db_getsession('DB_instit')."
          group by r14_regist ) as sal on r14_regist = rh01_regist
             where rh30_vinculo = 'A' 
               and rh30_regime = 1 $wh";
  }

  $rsAtivos      = db_query($sSqlAtivos);
  $iLinhasAtivos = pg_numrows($rsAtivos);
  for($x = 0; $x < $iLinhasAtivos; $x++){
    
    db_atutermometro($x,$iLinhasAtivos,'termometro');
    flush();
    $iMatricula = pg_result($rsAtivos,$x,'matricula');
    
    ////  verifica se tem conjuge
    
    $sSqlConjuge = "select rh31_regist,
                           to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
                      from rhdepend
                     where rh31_gparen = 'C' 
                       and rh31_regist = $iMatricula 
                     limit 1";
         
    $rsConjuge = db_query($sSqlConjuge);
    
    if(pg_numrows($rsConjuge) > 0){
      $dtconj  = pg_result($rsConjuge,0,'nasc');
      $temconj = 'S'; 
    }else{
      $dtconj = '';
      $temconj = 'N'; 
    }
    
    ////  verifica se tem filhos especiais
    
    $sSqlFilhoEspecial = "select rh31_regist,
                                 to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
                            from rhdepend
                           where rh31_gparen = 'F' 
                             and rh31_depend = 'S'
                             and rh31_regist = $iMatricula 
                        order by rh31_dtnasc desc
                           limit 1";
         
    $rsFilhoEspecial = db_query($sSqlFilhoEspecial);
    
    if(pg_numrows($rsFilhoEspecial) > 0){
      $dtespec = pg_result($rsFilhoEspecial,0,'nasc');
    }else{
      $dtespec = '';
    }
    
    ////  verifica se tem filhos nao especiais
    
    $sSqlFilhoNaoEspecial = "select rh31_regist,
                                    to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
                               from rhdepend
                              where rh31_gparen = 'F' 
                                and rh31_depend <> 'S'
                                and rh31_regist = $iMatricula 
                           order by rh31_dtnasc desc
                              limit 1";
         
    $rsFilhoNaoEspecial = db_query($sSqlFilhoNaoEspecial);
    
    if(pg_numrows($rsFilhoNaoEspecial) > 0){
      $dtnespec = pg_result($rsFilhoNaoEspecial,0,'nasc');
    }else{
      $dtnespec = '';
    }
  fputs($arquivo,pg_result($rsAtivos,$x,'todo')."#".$temconj."#".$dtconj."#".$dtespec."#".$dtnespec."#\r\n");
  }
  fclose($arquivo);

} else if ($_POST["vinculo"] == "I"){
  /**
   * Inativos - Aposentados
   */
  $arq = 'tmp/calc_inacef.csv';

  $arquivo = fopen($arq,'w');  

  if ($versao == 1) {

    $sSqlVersaoInativos = "
        select rh01_regist as matricula,
               trim(substr(z01_nome,1,40))
               ||'#'
               ||trim(substr(z01_cgccpf,1,11))
               ||'#'
               ||trim(to_char(rh01_regist,'999999'))
               ||'#'
               ||rh01_sexo
               ||'#'
               ||to_char(rh01_nasc,'DD/MM/YYYY') 
               ||'#'
               ||trim(translate(to_char(round(prov,2),'99999999,99'),',',''))
               ||'#'
               ||trim(translate(to_char(round(prov-desco,2),'99999999,99'),',',''))
               ||'#'
               ||'2'
               ||'#'
               ||to_char(rh01_admiss,'DD/MM/YYYY') as todo
          from rhpessoal 
    inner join cgm          on rh01_numcgm = z01_numcgm 
    inner join rhpessoalmov on rh02_regist = rh01_regist 
           and rh02_anousu = $ano 
           and rh02_mesusu = $mes 
           and rh02_instit = ".db_getsession('DB_instit')."
    inner join rhlota       on r70_codigo = rh02_lota 
    inner join rhregime on rh30_codreg = rh02_codreg
    inner join (select r14_regist,
                       sum( case 
                              when r14_pd = 1 then r14_valor 
                              else 0 
                            end ) as prov,
                       sum( case 
                              when r14_pd = 2 then r14_valor 
                              else 0 
                            end ) as desco,
                       sum( case 
                              when r14_rubric = 'R992' then r14_valor 
                              else 0 
                            end ) as base 
                  from gerfsal 
                 where r14_anousu = $ano
                   and r14_mesusu = $mes
                   and r14_instit = ".db_getsession('DB_instit')." 
              group by r14_regist ) as sal on r14_regist = rh01_regist 
                 where rh30_vinculo = 'I' $wh";

  } elseif ($versao == 2) {

    $sSqlVersaoInativos = "
        select rh01_regist as matricula,
               trim(to_char(rh01_regist,'999999'))
               ||'#'
               ||rh01_sexo
               ||'#'
               ||to_char(rh01_nasc,'DD/MM/YYYY') 
               ||'#'
               ||trim(translate(round(prov,2),',',''))
               ||'#'   
               ||case
                   when rhpessoalmov.rh02_rhtipoapos = 4 then 1
                   when rhpessoalmov.rh02_rhtipoapos = 2 then 2
                   when rhpessoalmov.rh02_rhtipoapos = 3 then 3
                   when rhpessoalmov.rh02_rhtipoapos = 5 then 4
                 end   
               ||'#'
               ||to_char(rh01_admiss,'DD/MM/YYYY')
               ||'#'
               ||to_char(rh01_admiss,'DD/MM/YYYY') as todo   
          from rhpessoal 
    inner join cgm          on rh01_numcgm = z01_numcgm 
    inner join rhpessoalmov on rh02_regist = rh01_regist 
                            and rh02_anousu = $ano 
                            and rh02_mesusu = $mes 
                            and rh02_instit   = ".db_getsession('DB_instit')."
    inner join rhlota       on r70_codigo = rh02_lota 
    inner join rhregime     on rh30_codreg = rh02_codreg
    inner join (select r14_regist,
                       sum( case 
                              when r14_pd = 1 then r14_valor 
                              else 0 
                            end ) as prov,
                       sum( case 
                              when r14_pd = 2 then r14_valor 
                              else 0 
                            end ) as desco,
                       sum( case 
                              when r14_rubric = 'R992' then r14_valor 
                              else 0 
                            end ) as base 
                  from gerfsal 
                 where r14_anousu = $ano
                   and r14_mesusu = $mes
                   and r14_instit = ".db_getsession('DB_instit')."      
              group by r14_regist ) as sal on r14_regist = rh01_regist 
         where rh30_vinculo = 'I'  
           and rhpessoalmov.rh02_rhtipoapos <> 1 $wh";
   
  }
  
  $rsVersaoInativos = db_query($sSqlVersaoInativos);
  $iLinhasInativos  = pg_numrows($rsVersaoInativos);
  
  for($x = 0; $x < $iLinhasInativos; $x++){
    
    db_atutermometro($x,$iLinhasInativos,'termometro');
    flush();
    $iMatricula = pg_result($rsVersaoInativos,$x,'matricula');
    
    ////  verifica se tem conjuge
    
    $sSqlConjuge = "select rh31_regist,
                           to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
                      from rhdepend
                     where rh31_gparen = 'C' 
                       and rh31_regist = $iMatricula 
                     limit 1";
         
    $rsConjuge = db_query($sSqlConjuge);
    
    if(pg_numrows($rsConjuge) > 0) {
      $dtconj  = pg_result($rsConjuge, 0, 'nasc');
      $temconj = 'S'; 
    }else{
      $dtconj = '';
      $temconj = 'N'; 
    }
    
    ////  verifica se tem filhos especiais
    
    $sSqlFilhosEspecial = "select rh31_regist,
                                  to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
                             from rhdepend
                            where rh31_gparen = 'F' 
                              and rh31_depend = 'S'
                              and rh31_regist = $iMatricula 
                         order by rh31_dtnasc desc
                            limit 1";
         
    $rsFilhoEspecial = db_query($sSqlFilhosEspecial);
    
    if(pg_numrows($rsFilhoEspecial) > 0){
      $dtespec = pg_result($rsFilhoEspecial,0,'nasc');
    }else{
      $dtespec = '';
    }
    
    ////  verifica se tem filhos nao especiais
    
    $sSqlFilhoNaoEspecial = "select rh31_regist,
                                    to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
                               from rhdepend
                              where rh31_gparen = 'F' 
                                and rh31_depend <> 'S'
                                and rh31_regist = $iMatricula 
                           order by rh31_dtnasc desc
                              limit 1";
         
    $rsFilhoNaoEspecial = db_query($sSqlFilhoNaoEspecial);
    
    if(pg_numrows($rsFilhoNaoEspecial) > 0){
      $dtnespec = pg_result($rsFilhoNaoEspecial,'nasc');
    }else{
      $dtnespec = '';
    }
  fputs($arquivo,pg_result($rsVersaoInativos,$x,'todo')."#".$temconj."#".$dtconj."#".$dtespec."#".$dtnespec."#\r\n");
  }
  fclose($arquivo);


}else if ($_POST["vinculo"] == "P"){
  /**
   * Pensionistas
   */
  $arq = 'tmp/calc_penscef.csv';

  $arquivo = fopen($arq,'w');  

  if ($versao == 1) {

    $sSqlVersaoPensionistas = "
    select p.rh01_regist as matricula,
   case when c.z01_nome is null then 'NAO CADASTRADO' else trim(substr(c.z01_nome,1,40)) end
   ||'#'
   ||lpad(cgm.z01_cgccpf,11,0)
   ||'#'
   ||trim(to_char(p.rh01_regist,'999999'))
   ||'#'
   ||trim(translate(to_char(round(prov,2),'99999999,99'),',',''))
   ||'#'
   ||trim(translate(to_char(round(prov-desco,2),'99999999,99'),',',''))
   ||'#'
   ||'2'
   ||'#'
   ||p.rh01_sexo as todo
    from rhpessoal p
       inner join rhpesorigem  on rh21_regist   = p.rh01_regist
       left  join rhpessoal q  on q.rh01_regist = rh21_regpri
       left  join cgm c        on c.z01_numcgm  = q.rh01_numcgm
       inner join cgm          on p.rh01_numcgm = cgm.z01_numcgm
       inner join rhpessoalmov on rh02_regist   = p.rh01_regist
            and rh02_anousu   = $ano
            and rh02_mesusu   = $mes
            and rh02_instit   = ".db_getsession('DB_instit')."
       inner join rhlota       on r70_codigo    = rh02_lota
       inner join rhregime     on rh30_codreg   = rh02_codreg
       inner join (select r14_regist,
                          sum( case 
                                 when r14_pd = 1 then r14_valor 
                                 else 0 
                               end ) as prov,
                          sum( case 
                                 when r14_pd = 2 then r14_valor 
                                 else 0 
                               end ) as desco,
                          sum( case 
                                 when r14_rubric = 'R992' then r14_valor 
                                 else 0 
                               end ) as base
       from gerfsal
       where r14_anousu = $ano
   and r14_mesusu = $mes
   and r14_instit = ".db_getsession('DB_instit')."
   group by r14_regist ) as sal on r14_regist = p.rh01_regist
    where rh30_vinculo = 'P'
    $wh
    ";

  } elseif ($versao == 2) {

    $sSqlVersaoPensionistas = "
      select p.rh01_regist as matricula,
        case when c.z01_nome is null then 'NAO CADASTRADO' else trim(substr(c.z01_nome,1,40)) end
          ||'#'
          ||trim(to_char(p.rh01_regist,'999999'))
          ||'#'
          ||trim(translate(round(prov,2),',',''))
          ||'#'
          ||case
              when rhpessoalmov.rh02_validadepensao is not null then 1 
              else 2              
            end
          ||'#'
          ||p.rh01_sexo 
          ||'#'
          ||to_char(p.rh01_admiss,'DD/MM/YYYY')
          ||'#'
          ||to_char(p.rh01_admiss,'DD/MM/YYYY')
          ||'#'
          ||to_char(p.rh01_nasc,'DD/MM/YYYY') as todo
        from rhpessoal p
  inner join rhpesorigem  on rh21_regist   = p.rh01_regist
   left join rhpessoal q  on q.rh01_regist = rh21_regpri
   left join cgm c        on c.z01_numcgm  = q.rh01_numcgm
  inner join cgm          on p.rh01_numcgm = cgm.z01_numcgm
  inner join rhpessoalmov on rh02_regist   = p.rh01_regist
                          and rh02_anousu  = $ano
                          and rh02_mesusu  = $mes
                          and rh02_instit  = ".db_getsession('DB_instit')."
  inner join rhlota       on r70_codigo    = rh02_lota
  inner join rhregime     on rh30_codreg   = rh02_codreg
  inner join (select r14_regist,
                     sum( case 
                             when r14_pd = 1 then r14_valor 
                             else 0 
                          end ) as prov,
                     sum( case 
                             when r14_pd = 2 then r14_valor 
                             else 0 
                          end ) as desco,
                     sum( case 
                             when r14_rubric = 'R992' then r14_valor 
                             else 0 
                          end ) as base
                from gerfsal
               where r14_anousu     = $ano
                     and r14_mesusu = $mes
                     and r14_instit = ".db_getsession('DB_instit')."
            group by r14_regist ) as sal on r14_regist = p.rh01_regist
       where rh30_vinculo = 'P' $wh";
  }
  
  $rsQueryPensionistas = db_query($sSqlVersaoPensionistas);
  $iLinhasPensionistas = pg_numrows($rsQueryPensionistas);

  for($x = 0; $x < $iLinhasPensionistas; $x++){
    
    db_atutermometro($x,$iLinhasPensionistas,'termometro');
    flush();
    $sMatricula = pg_result($rsQueryPensionistas,$x,'matricula');
    
    ////  verifica se tem conjuge
    $sSqlConjuge = "select rh31_regist,
                           to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
                      from rhdepend
                     where rh31_gparen = 'C' and 
                           rh31_regist = $sMatricula 
                     limit 1";
         
    $rsSqlConjuge = db_query($sSqlConjuge);
    
    if(pg_numrows($rsSqlConjuge) > 0){
      $dtconj  = pg_result($rsSqlConjuge,0,'nasc');
      $temconj = 'S'; 
    }else{
      $dtconj = '';
      $temconj = 'N'; 
    }
    
    ////  verifica se tem filhos especiais    
    $sSqlFilhoEspecial = "select rh31_regist,
                                 to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
                            from rhdepend
                           where rh31_gparen = 'F' 
                                 and rh31_depend = 'S'
                                 and rh31_regist = $sMatricula 
                        order by rh31_dtnasc desc
                           limit 1";
         
    $rsSqlFilhoEspecial = db_query($sSqlFilhoEspecial);
    
    if(pg_numrows($rsSqlFilhoEspecial) > 0) {
      $dtespec = pg_result($rsSqlFilhoEspecial, 0, 'nasc');
    } else {
      $dtespec = '';
    }
    
    ////  verifica se tem filhos nao especiais
    
    $sSqlFilhoNaoEspecial = "select rh31_regist,
                                    to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
                               from rhdepend
                              where rh31_gparen = 'F' and 
                                    rh31_depend <> 'S' and
                                    rh31_regist = $sMatricula 
                           order by rh31_dtnasc desc
                              limit 1";
         
    $rsSqlFilhoNaoEspecial = db_query($sSqlFilhoNaoEspecial);
    
    if(pg_numrows($rsSqlFilhoNaoEspecial) > 0){
      $dtnespec = pg_result($rsSqlFilhoNaoEspecial,0,'nasc');
    }else{
      $dtnespec = '';
    }
  fputs($arquivo,pg_result($rsQueryPensionistas,$x,'todo')."#"."#".$dtespec."#".$dtnespec."#\r\n");
  }
  fclose($arquivo);

}
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

?>
<form name='form1' id='form1'></form>
<script>
js_montarlista("<?=$arq?>#Arquivo gerado em: <?=$arq?>",'form1');

function js_manda(){
  location.href='pes4_geracalcaturial001.php?banco=104';
}

setTimeout(js_manda,300);
</script>
</body>
</html>