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

  if($func == 'F'){
    
  $arq = '/tmp/func_sind.txt';

  $arquivo = fopen($arq,'w');  
  $sql = "select lpad(rh01_regist,6,0)||
                 rpad(z01_nome,40) ||
                 rh30_descr as tipo
          from rhpessoal 
               inner join rhpessoalmov on rh02_anousu = $ano
                                      and rh02_mesusu = $mes
                                      and rh02_regist = rh01_regist
                                      and rh02_instit = ".db_getsession("DB_instit")." 
               left join rhpesrescisao on rh02_seqpes = rh05_seqpes
               left join rhregime      on rh30_codreg = rh02_codreg 
                                      and rh30_instit = rh02_instit
	             inner join cgm          on rh01_numcgm = z01_numcgm
	        where rh05_seqpes is null
	 ";

}elseif($func == 'S'){
  
  $arq = '/tmp/socios_sind.txt';

  $arquivo = fopen($arq,'w');
  
   $sql = "select lpad(rh01_regist,6,0)||
                    rpad(z01_nome,40) ||
  	       lpad(to_char(sum(r53_valor),'999999.99'),15,' ') as tipo 
                from gerffx 
		     inner join rhpessoal    on rh01_regist = r53_regist
		     inner join cgm          on rh01_numcgm = z01_numcgm
		     
		     inner join rhpessoalmov on rh02_regist = rh01_regist
		                            and rh02_anousu = r53_anousu
					                and rh02_mesusu = r53_mesusu
                                    and rh02_instit = ".db_getsession("DB_instit")."
		     left join  rhpesrescisao on rh02_seqpes = rh05_seqpes
		     
		     inner join rhrubricas   on rh27_rubric = r53_rubric 
                                    and rh27_instit = rh02_instit
		where r53_anousu = $ano
		  and r53_mesusu = $mes
		  and rh27_tipo = 1 
		  and rh27_pd = 1 
		  and r53_regist in (select distinct r53_regist
                             from gerffx 
                             where r53_anousu = $ano 
                               and r53_mesusu = $mes 
                               and r53_rubric = '1600' 
                               and r53_instit = ".db_getsession("DB_instit")." )
		  and rh05_seqpes is null
		group by rh01_regist,z01_nome
		  ";


}elseif($func == 'D'){
  
  $arq = '/tmp/desc_sind.txt';

  $arquivo = fopen($arq,'w'); 
  if($mes == 12){
    $xmes = 1;
    $xano = $ano+1;
  }else{
    $xmes = $mes+1;
    $xano = $ano;
  }
  
  $sql = "
select lpad(registro,6,0)
     ||'#'
     ||rpad(nome,50,' ')
     ||'#'
     ||lpad(to_char(sind,'999999.99'),15,' ')
     ||'#'
     ||lpad(to_char(desconto,'999999.99'),15,' ')
     ||'#'
     ||lpad(to_char(sind-desconto,'999999.99'),15,' ')
     ||'#'
     ||$xano
     ||'#'
     ||$xmes 
     ||'#'
     ||fc_sap_afas(registro,$ano,$mes)
     ||'#'
     ||lpad(to_char(emprestimo,'999999.99'),15,' ') as tipo
from 
(
SELECT ano,
       mes,
       registro,
       case when z01_nome is null
            then ' FUNCIONRIO NO CADASTRADO'
	    else z01_nome
       end as nome,
       round(sum(sind),2) as sind,
       round(sum(desconto),2) as desconto,
       round(sum(emprestimo),2) as emprestimo,
       round(sum(socio),2) as socio
from 
	(
	select to_number(substr(r54_anomes,1,4),'9999') as ano,
	       to_number(substr(r54_anomes,5,2),'99') as mes,
	       r54_regist as registro,
	       r54_quant1 as sind, 
	       0 as desconto,
	       0 as emprestimo,
	       0 as socio
	from movrel  
	where r54_anomes = '".$ano.db_formatar($mes,'s','0',2,'e')."' 
	  and r54_codrel = '9000'
      and r54_instit = ".db_getsession("DB_instit")."

	union


	select r14_anousu,
	       r14_mesusu,
	       r14_regist,
	       0,
	       r14_valor,
	       0,
	       0
	from gerfsal 
	where r14_anousu = $ano
	  and r14_mesusu = $mes 
	  and r14_rubric = '1602'
      and r14_instit = ".db_getsession("DB_instit")."

        union
	 
	select r14_anousu,
	       r14_mesusu,
	       r14_regist,
	       0,
	       0,
	       r14_valor,
	       0
	from gerfsal 
	where r14_anousu = $ano
	  and r14_mesusu = $mes 
	  and r14_rubric in ('1710','1715','1720')
      and r14_instit = ".db_getsession("DB_instit")."
	union


	select r14_anousu,
	       r14_mesusu,
	       r14_regist,
	       0,
	       0,
	       0,
	       r14_valor
	from gerfsal 
	where r14_anousu = $ano
	  and r14_mesusu = $mes 
	  and r14_rubric = '1600'
      and r14_instit = ".db_getsession("DB_instit")."
	) as x
	
	left JOIN rhpessoal    on rh01_regist = registro
    left join rhpessoalmov on rh02_regist = rh01_regist
			              and rh02_anousu = ano
			              and rh02_mesusu = mes
                          and rh02_instit = ".db_getsession("DB_instit")."
	left JOIN cgm          on rh01_numcgm = z01_numcgm

GROUP BY ano,mes,z01_nome,registro
) as xxx
where socio+sind > 0
" ;
  }
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
        <td align="right" nowrap title="Digite o Ano / Mes de competncia" >
        <strong>Ano / Ms :&nbsp;&nbsp;</strong>
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
	  $arr = array('D'=>'Desconto','S'=>'Socios','F'=>'Funcionrios');
	  db_select("func",$arr,true,1);
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