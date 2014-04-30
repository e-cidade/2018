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

if (isset($gera) || isset($gera1) || isset($gera2)){
  $arq = '/tmp/contra_cheque.txt';

  $arquivo = fopen($arq,'w');  
  
  if(isset($gera1)){
  fputs($arquivo,'CP'.' DECIMO  /'.$ano.str_repeat("PMG", 78)."\r\n");
   $sql = "select * from 
          (
          select distinct 
	         r70_estrut,
                 lpad(rh01_regist,6,'0') as regist,
                 rpad(z01_nome,45,' ') as nome, 
		 rpad(case when rh04_descr is null then rh37_descr else rh04_descr end ,40,' ') as cargo,
		 to_char(rh01_admiss,'ddmmYYYY') as admissao,
		 rpad(z01_cgccpf,11,' ') as cpf,
		 lpad(substr(rh44_codban,1,4),4,'0') as banco,
		 rh44_agencia as agencia,
		 rh44_dvagencia as dvagencia,
     translate(to_char(to_number((case when trim(rh44_conta) = '' then '0' else rh44_conta end ) ,'99999999999'),'99,999999,9'),',','') as conta,
		 rh44_dvconta as dvconta,
		 rpad(rh52_descr,16,' ') as regime ,
		 rh52_regime as cod_regime ,
		 rpad(o40_descr,45,' ') as orgao,
		 rpad(r70_descr,45,' ') as setor,
		 rpad(z01_ender,45,' ') as ender,
		 rpad(substr(z01_compl,1,15),15,' ') as compl,
		 to_char(z01_numero,'999999') as numero ,
		 z01_cep as cep,
		 rpad(substr(z01_bairro,1,25),25,' ') as bairro,
		 rpad(substr(z01_munic,1,25),25,' ') as munic
		 
         from rhpessoalmov 
				 inner join rhpessoal on rh01_regist = rh02_regist			
	       inner join gerfs13 on r35_regist = rh02_regist 
	                         and r35_anousu = rh02_anousu
                           and r35_mesusu = rh02_mesusu
                   				 and r35_instit = rh02_instit
	       inner join cgm on z01_numcgm = rh01_numcgm
               left join rhpesbanco    on rh44_seqpes = rh02_seqpes
	       left join rhfuncao      on rh37_funcao = rh02_funcao
		                      and rh37_instit = rh02_instit 
	       left join  rhpescargo   on rh20_seqpes = rh02_seqpes
	       left join  rhcargo      on rh04_codigo = rh20_cargo
	                              and rh04_instit = rh02_instit
	       inner join rhregime     on rh30_codreg = rh02_codreg
				                        and rh30_instit = rh02_instit
	       inner join rhcadregime  on rh52_regime = rh30_regime
				                        and rh30_instit = rh02_instit  
	       inner join rhlota       on r70_codigo  = rh02_lota
				                        and r70_instit  = rh02_instit
	       left join  rhlotaexe    on rh26_codigo = r70_codigo
	                              and rh26_anousu = rh02_anousu
	       left join orcorgao      on o40_orgao   = rh26_orgao
	                              and o40_anousu  = rh26_anousu
																and o40_instit  = rh02_instit
				 
	 where rh02_anousu = $ano 
	   and rh02_mesusu = $mes 
		 and rh02_instit = ".db_getsession("DB_instit")." ) as xxx
	 order by r70_estrut,nome
	 ";
  }elseif(isset($gera2)){
  fputs($arquivo,'CP'.db_formatar( strtoupper(db_mes($mes)),'s',' ',9,'e',0).'/'.$ano.str_repeat("PMG", 78)."\r\n");
    $sql = "select * from 
          (
          select distinct 
	         r70_estrut,
                 lpad(rh01_regist,6,'0') as regist,
                 rpad(z01_nome,45,' ') as nome, 
		 rpad(case when rh04_descr is null then rh37_descr else rh04_descr end ,40,' ') as cargo,
		 to_char(rh01_admiss,'ddmmYYYY') as admissao,
		 rpad(z01_cgccpf,11,' ') as cpf,
		 lpad(substr(rh44_codban,1,4),4,'0') as banco,
		 rh44_agencia as agencia,
		 rh44_dvagencia as dvagencia,
                 translate(to_char(to_number((case when trim(rh44_conta) = '' then '0' else rh44_conta end ) ,'99999999999'),'99,999999,9'),',','') as conta,
		 rh44_dvconta as dvconta,
		 rpad(rh52_descr,16,' ') as regime ,
		 rh52_regime as cod_regime ,
		 rpad(o40_descr,45,' ') as orgao,
		 rpad(r70_descr,45,' ') as setor,
		 rpad(z01_ender,45,' ') as ender,
		 rpad(substr(z01_compl,1,15),15,' ') as compl,
		 to_char(z01_numero,'999999') as numero ,
		 z01_cep as cep,
		 rpad(substr(z01_bairro,1,25),25,' ') as bairro,
		 rpad(substr(z01_munic,1,25),25,' ') as munic
		 
         from rhpessoalmov
	       inner join rhpessoal on rh01_regist = rh02_regist 
	       inner join gerfcom on r48_regist = rh01_regist 
	                         and r48_anousu = rh02_anousu
				 and r48_mesusu = rh02_mesusu
				 and r48_instit = rh02_instit
	       inner join cgm on z01_numcgm = rh01_numcgm
         left join rhpesbanco    on rh44_seqpes = rh02_seqpes
	       inner join rhfuncao     on rh37_funcao = rh02_funcao
                                and rh37_instit = rh02_instit
	       left join  rhpescargo   on rh20_seqpes = rh02_seqpes
	       left join  rhcargo      on rh04_codigo = rh20_cargo
                                and rh04_instit = rh02_instit
	       inner join rhregime     on rh30_codreg = rh02_codreg
				                        and rh30_instit = rh02_instit
	       inner join rhcadregime  on rh52_regime = rh30_regime
				                        and rh30_instit = rh02_instit  
	       inner join rhlota       on r70_codigo  = rh02_lota
				                        and r70_instit  = rh02_instit
	       left join  rhlotaexe    on rh26_codigo = r70_codigo
	                              and rh26_anousu = rh02_anousu
	       left join orcorgao      on o40_orgao   = rh26_orgao
	                              and o40_anousu  = rh26_anousu
																and o40_instit  = rh02_instit
				 
	 where rh02_anousu = $ano 
     and rh02_mesusu = $mes
     and rh02_instit = ".db_getsession("DB_instit").") as xxx
	 order by r70_estrut,nome
	 ";
  }elseif(isset($gera)){
  fputs($arquivo,'CP'.db_formatar( strtoupper(db_mes($mes)),'s',' ',9,'e',0).'/'.$ano.str_repeat("PMG", 78)."\r\n");
    $sql = "select * from 
          (
          select distinct 
	         r70_estrut,
                 lpad(rh01_regist,6,'0') as regist,
                 rpad(z01_nome,45,' ') as nome, 
		 rpad(case when rh04_descr is null then rh37_descr else rh04_descr end ,40,' ') as cargo,
		 to_char(rh01_admiss,'ddmmYYYY') as admissao,
		 rpad(z01_cgccpf,11,' ') as cpf,
		 lpad(substr(rh44_codban,1,4),4,'0') as banco,
		 rh44_agencia as agencia,
		 rh44_dvagencia as dvagencia,
                 translate(to_char(to_number((case when trim(rh44_conta) = '' then '0' else rh44_conta end ) ,'99999999999'),'99,999999,9'),',','') as conta,
		 rh44_dvconta as dvconta,
		 rpad(rh52_descr,16,' ') as regime ,
		 rh52_regime as cod_regime ,
		 rpad(o40_descr,45,' ') as orgao,
		 rpad(r70_descr,45,' ') as setor,
		 rpad(z01_ender,45,' ') as ender,
		 rpad(substr(z01_compl,1,15),15,' ') as compl,
		 to_char(z01_numero,'999999') as numero ,
		 z01_cep as cep,
		 rpad(substr(z01_bairro,1,25),25,' ') as bairro,
		 rpad(substr(z01_munic,1,25),25,' ') as munic
		 
         from rhpessoalmov
	       inner join rhpessoal on rh01_regist = rh02_regist 
	       inner join gerfsal on r14_regist = rh01_regist 
	                         and r14_anousu = rh02_anousu
				 and r14_mesusu = rh02_mesusu
				 and r14_instit = rh02_instit
	       inner join cgm on z01_numcgm = rh01_numcgm
               left join rhpesbanco    on rh44_seqpes = rh02_seqpes
	       inner join rhfuncao     on rh37_funcao = rh02_funcao
                                      and rh37_instit = rh02_instit
	       left join  rhpescargo   on rh20_seqpes = rh02_seqpes
	       left join  rhcargo      on rh04_codigo = rh20_cargo
                                and rh04_instit = rh02_instit
	       inner join rhregime     on rh30_codreg = rh02_codreg
				                        and rh30_instit = rh02_instit
	       inner join rhcadregime  on rh52_regime = rh30_regime
				                        and rh30_instit = rh02_instit  
	       inner join rhlota       on r70_codigo  = rh02_lota
				                        and r70_instit  = rh02_instit
	       left join  rhlotaexe    on rh26_codigo = r70_codigo
	                              and rh26_anousu = rh02_anousu
	       left join orcorgao      on o40_orgao   = rh26_orgao
	                              and o40_anousu  = rh26_anousu
																and o40_instit  = rh02_instit
				 
	 where rh02_anousu = $ano 
     and rh02_mesusu = $mes
     and rh02_instit = ".db_getsession("DB_instit").") as xxx
	 order by r70_estrut,nome
	 ";
  }
// echo "<br><br><br><br><br>".$sql;
  $result = pg_query($sql);
  for($x = 0;$x < pg_numrows($result);$x++){
    db_fieldsmemory($result,$x);
    fputs($arquivo,'00'.
                 db_formatar($nome ,'s',' ',45,'d',0).
                 $regist.
		 db_formatar($cargo,'s',' ',40,'d',0).
		 $admissao.
		 db_formatar($mes,'s',' ',2,'d',0).
		 db_formatar($ano,'s',' ',2,'d',0).
		 db_formatar($cpf,'s',' ',11,'d',0).
		 db_formatar($banco,'s',' ',4,'d',0).
		 db_formatar($agencia,'s','0',3,'e',0).db_formatar($dvagencia,'s','0',1,'e',0).
		 db_formatar($conta  ,'s','0',11,'e',0).'-'.$dvconta.
		 db_formatar($regime,'s',' ',16,'d',0).
		 db_formatar($orgao,'s',' ',45,'d',0).
		 db_formatar($setor,'s',' ',45,'d',0).
		 db_formatar($x+1,'s','0',4,'e',0).
		 "\r\n");
     $margem = 0;
	   if($cod_regime <> 3 ) {
       $sql_margem = "select sum(r53_valor) as margem  
                      from gerffx 
                      where r53_anousu = $ano    and 
                            r53_mesusu = $mes    and 
                            r53_regist = $regist and
                            r53_rubric in ('0102', 
                                           '0103',
                                           '0109',
                                           '0111',
                                           '0195', 
                                           '0196',
                                           '0197',
                                           '0198',
                                           '0145'
                                          )";
       $result_margem = pg_query($sql_margem);
       db_fieldsmemory($result_margem,0);
	   } 
  if(isset($gera)){
    $sql_ger = "select r14_rubric,
                       r14_quant,
		       round(r14_valor,2) as r14_valor,
		       r14_pd,
		       rh27_descr,
		       case when r14_pd != 3 then 'v' else 'b' end as tipo, 
		       case when rh27_obs like '%PERC%' then 'p' else 
		            case when rh27_obs like '%DIAS%' then 'd' else
			         case when rh27_obs like '%UNID%' then 'u' else ''
				 end
		            end
		       end as perc 
		from gerfsal 
		     inner join rhrubricas on r14_rubric = rh27_rubric
                                  and r14_instit = rh27_instit         
		where r14_anousu = $ano
		  and r14_mesusu = $mes
		  and r14_regist = $regist
			and r14_instit = ".db_getsession("DB_instit")."
		order by r14_regist,r14_rubric
		";

  }elseif(isset($gera2)){
    $sql_ger = "select r48_rubric as r14_rubric,
                       r48_quant as r14_quant,
		       round(r48_valor,2) as r14_valor,
		       r48_pd as r14_pd,
		       rh27_descr,
		       case when r48_pd != 3 then 'v' else 'b' 
           end as tipo, 
		       case when rh27_obs like '%PERC%' then 'p' else 
		            case when rh27_obs like '%DIAS%' then 'd' else
			         case when rh27_obs like '%UNID%' then 'u' else ''
				 end
		            end
		       end as perc 
		from gerfcom 
		     inner join rhrubricas on r48_rubric = rh27_rubric
                              and r48_instit = rh27_instit
		where r48_anousu = $ano
		  and r48_mesusu = $mes
		  and r48_regist = $regist
			and r48_instit = ".db_getsession("DB_instit")."
		order by r48_regist,r48_rubric ";


  }else{
    $sql_ger = "select r35_rubric as r14_rubric,
                       r35_quant as r14_quant,
		       round(r35_valor,2) as r14_valor,
		       r35_pd as r14_pd,
		       rh27_descr,
		       case when r35_pd != 3 then 'v' else 'b' 
           end as tipo, 
		       case when rh27_obs like '%PERC%' then 'p' else 
		            case when rh27_obs like '%DIAS%' then 'd' else
			         case when rh27_obs like '%UNID%' then 'u' else ''
				 end
		            end
		       end as perc 
		from gerfs13 
		     inner join rhrubricas on r35_rubric = rh27_rubric
                              and r35_instit = rh27_instit
		where r35_anousu = $ano
		  and r35_mesusu = $mes
		  and r35_regist = $regist
			and r35_instit = ".db_getsession("DB_instit")."
		order by r35_regist,r35_rubric ";
    }
//    echo "<br><br>".$sql_ger;exit;
    $base_prev = 0;
    $base_irrf = 0;
    $base_fgts = 0;
    $fgts      = 0;
    $bruto     = 0;
    $desc      = 0;
    $margem_c  = 0;
    $res_ger = pg_query($sql_ger);
    for($g = 0;$g < pg_numrows($res_ger);$g++){
      db_fieldsmemory($res_ger,$g);
      if($tipo == 'v'){
	if($perc == 'p'){
	  $inform = '%';
	  $quant = trim(db_formatar($r14_quant,'f')).$inform;
	}elseif($perc == 'd'){
	  $inform = 'D';
	  $quant = $r14_quant.' '.$inform;
	}elseif($perc == 'u'){
	  $inform = 'UN';
	  $quant = $r14_quant.' '.$inform;
	}else{
	  $inform = '';
	  $quant = $r14_quant.' '.$inform;
        }
	if($r14_quant == 0){
	  $quant = '';
        }
        fputs($arquivo,'02'.
                 ($r14_pd == 1?'V':'D').
                 $r14_rubric.
		 db_formatar($rh27_descr,'s',' ',40,'d',0).
		 db_formatar($quant,'s',' ',7,'e',0).
 	         db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar(($r14_pd == 1?$r14_valor:0),'f')))),'s','0',9 ,'e',0).
 	         db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar(($r14_pd == 2?$r14_valor:0),'f')))),'s','0',9 ,'e',0).
		 str_repeat("PMG", 59).
		 'P'.
		 "\r\n");
	if($r14_pd == 1){
          $bruto += $r14_valor;
	}else{
          $desc  += $r14_valor;
	}
      }else{
	if($r14_rubric == 'R992'){
          $base_prev = $r14_valor;
	}elseif($r14_rubric == 'R981' || $r14_rubric == 'R982' || $r14_rubric == 'R983'){
	  $base_irrf = $r14_valor;
	}elseif($r14_rubric == 'R991'){
	  $base_fgts = $r14_valor;
	  $fgts      = $r14_valor / 100 * 8;
	}elseif($r14_rubric == 'R803'){
	  $margem_c = $r14_valor;
	}
      }
    }
    fputs($arquivo,'MS'.str_pad($mensagem1,64,' ',STR_PAD_RIGHT).str_repeat('PMG',61).'P'."\r\n");
    fputs($arquivo,'MS'.str_pad($mensagem2,64,' ',STR_PAD_RIGHT).str_repeat('PMG',61).'P'."\r\n");
    fputs($arquivo,'MS'.str_pad($mensagem3,64,' ',STR_PAD_RIGHT).str_repeat('PMG',61).'P'."\r\n");
    fputs($arquivo,'MS'.str_pad($mensagem4,64,' ',STR_PAD_RIGHT).str_repeat('PMG',61).'P'."\r\n");
    fputs($arquivo,'MS'.str_pad($mensagem5,64,' ',STR_PAD_RIGHT).str_repeat('PMG',61).'P'."\r\n");
    fputs($arquivo,'TT'.
 	   db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($bruto,'f')))),'s','0',9 ,'e',0).
 	   db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($desc,'f')))),'s','0',9 ,'e',0).
 	   db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($bruto - $desc,'f')))),'s','0',9 ,'e',0).
 	   db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($base_prev,'f')))),'s','0',9 ,'e',0).
 	   db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($base_fgts,'f')))),'s','0',9 ,'e',0).
  	   db_formatar($ender,'s',' ',45,'d',0).
  	   db_formatar($compl,'s',' ',15,'d',0).
  	   db_formatar($numero,'s',' ',6,'e',0).
  	   db_formatar($cep,'s',' ',8,'d',0).
  	   db_formatar($bairro,'s',' ',25,'d',0).
  	   db_formatar($munic,'s',' ',25,'d',0).
 	   db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($margem_c,'f')))),'s','0',11,'e',0).
 	   db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($base_irrf,'f')))),'s','0',11,'e',0).
 	   db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($fgts,'f')))),'s','0',11,'e',0).
 	   db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar(0,'f')))),'s','0',11,'e',0).
           str_repeat("PMG", 11).
	   'PM'.
	   "\r\n");

    
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
    <td colspan="2" align="center">
      <fieldset>
        <legend><b>Mensagem</b></legend>
        <table>
          <tr>
            <td nowrap align="right">
	      <b>Linha 1:</b>
            </td>
            <td> 
              <?
              $Mmensagem1 = 64;
              db_input('mensagem1',64,0,true,'text',1,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right">
	      <b>Linha 2:</b>
            </td>
            <td> 
              <?
              $Mmensagem2 = 64;
              db_input('mensagem2',64,0,true,'text',1,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right">
	      <b>Linha 3:</b>
            </td>
            <td> 
              <?
              $Mmensagem3 = 64;
              db_input('mensagem3',64,0,true,'text',1,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right">
	      <b>Linha 4:</b>
            </td>
            <td> 
              <?
              $Mmensagem4 = 64;
              db_input('mensagem4',64,0,true,'text',1,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right">
	      <b>Linha 5:</b>
            </td>
            <td> 
              <?
              $Mmensagem5 = 64;
              db_input('mensagem5',64,0,true,'text',1,"")
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="gera" id="gera" type="submit" value="Salário"  >
          <input  name="gera1" id="gera1" type="submit" value="13o. Salário"  >
          <input  name="gera2" id="gera2" type="submit" value="Complementar"  >
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
  if(isset($gera) || isset($gera1) || isset($gera2)  ){
  	echo "js_montarlista('".$arq."#Arquivo gerado em: ".$arq."','form1');";
  }
  ?>
</script>