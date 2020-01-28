<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

  $arq = '/tmp/arq_capsem.txt';

  $arquivo = fopen($arq,'w');  
  $sql = "

select to_char(rh01_regist,'999999')
                ||';'||
                z01_nome
                ||';'||
                to_char(rh01_nasc,'dd-MM-YYYY')
                ||';'||
                rh01_sexo
                ||';'||
                rh37_descr
                ||';'||
                to_char(coalesce(base,0),'999999999.99')
                ||';'||
                to_char(coalesce(remuneracao,0),'999999999.99')
                ||';'||
                '0'
                ||';'||
                to_char(rh01_admiss,'dd-MM-YYYY')
                ||';'||
                to_char(coalesce(conjuge,0),'99')
                ||';'||
                to_char(coalesce(filho1,0),'99')
                ||';'||
                to_char(coalesce(filho2,0),'99')
                ||';'||
                to_char(coalesce(filho3,0),'99')
                ||';'||
                to_char(coalesce(filho4,0),'99')
                ||';'||
                to_char(coalesce(filho5,0),'99')

                as tipo
from (
      select rh02_anousu,
             rh02_mesusu,
             rh01_regist,
             rh01_funcao,
             rh01_nasc  ,
             rh01_sexo  ,
             rh01_admiss,
             rh01_numcgm,
             rh02_instit,
             (select count(*)
              from rhdepend 
              where rh31_regist = rh01_regist
                and rh31_gparen in ('C','F')
             ) as num_depend,
             (select fc_idade(rh31_dtnasc,current_date)
              from rhdepend 
              where rh31_regist = rh01_regist
                and rh31_gparen = 'C'
              order by rh31_codigo
              limit 1
             ) as conjuge,
             
             (select fc_idade(rh31_dtnasc,current_date)
              from rhdepend 
              where rh31_regist = rh01_regist
                and rh31_gparen <> 'C'
              order by rh31_codigo
              limit 1
             ) as filho1,
             (select fc_idade(rh31_dtnasc,current_date)
              from rhdepend 
              where rh31_regist = rh01_regist
                and rh31_gparen <> 'C'
              order by rh31_codigo
              offset 1
              limit 1
             ) as filho2,
             
             (select fc_idade(rh31_dtnasc,current_date)
              from rhdepend 
              where rh31_regist = rh01_regist
                and rh31_gparen <> 'C'
              order by rh31_codigo
              offset 2
              limit 1
             ) as filho3,
             
             (select fc_idade(rh31_dtnasc,current_date)
              from rhdepend 
              where rh31_regist = rh01_regist
                and rh31_gparen <> 'C'
              order by rh31_codigo
              offset 3
              limit 1
             ) as filho4,
             (select fc_idade(rh31_dtnasc,current_date)
              from rhdepend 
              where rh31_regist = rh01_regist
                and rh31_gparen <> 'C'
              order by rh31_codigo
              offset 4
              limit 1
             ) as filho5
      from rhpessoal 
           inner join rhpessoalmov  on rh02_regist = rh01_regist
           inner join rhregime      on rh02_codreg = rh30_codreg
           left join  rhpesrescisao on rh02_seqpes = rh05_seqpes

      where  rh02_anousu = $ano 
      and    rh02_mesusu = $mes
      and    rh02_instit = ".db_getsession("DB_instit")."
      and    rh02_tbprev = 1
      and    rh30_regime = 1
      and    rh05_recis is null
      ) as x
     left join (select r14_regist,
                       round(sum(case when r14_rubric = 'R981' then r14_valor else 0 end ),2) as base,
                       round(sum(case when r14_rubric in ('0001','0011','0012') then r14_valor else 0 end ),2) as remuneracao
                from gerfsal 
		            where r14_anousu = $ano
                  and r14_mesusu = $mes
		              and r14_instit = ".db_getsession("DB_instit")."
                  and r14_rubric in('R981','0001','0011','0012')
		            group by r14_regist ) as gerfsal on r14_regist = rh01_regist 
     inner join rhfuncao on rh37_funcao = rh01_funcao
     inner join cgm      on rh01_numcgm = z01_numcgm 
order by rh01_regist;
	 ";
//  echo "<br><br><br><br><br>".$sql;
  $result = pg_query($sql);
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