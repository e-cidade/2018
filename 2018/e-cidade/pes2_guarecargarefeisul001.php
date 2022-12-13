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

$lotacao_filtro = "select r02_codigo from padroes where r02_anousu = $ano and r02_instit = 1 and r02_mesusu = $mes and r02_descr ilike '*%'";

if (isset($gera)){

  $arq = '/tmp/'.$ano.db_formatar($mes,'s','0',2 ,'e',0).'rf.txt';

  $arquivo = fopen($arq,'w');  
  $sql = "
  select rpad(z01_nome,42,' ')||
           lpad(z01_cgccpf,11,'0')||
           lpad(translate(to_char(total,'999999.99'),'.',','),12,' ') as tipo
    from
    (
    select z01_nome,
           z01_cgccpf,
            sum(valor1+valor2) as total
           from
           (
           select s.r14_regist,
                  z01_nome,
                  z01_cgccpf,
                  case when (trim(rh03_padrao) in ( $lotacao_filtro )
                         or rh30_regime = 2) and g.r14_regist is null 
                       then $maior
                       else case when (trim(rh03_padrao) in ( $lotacao_filtro )
                                   or rh30_regime = 2) and g.r14_regist is not null
                                 then ($maior + 10) - $menor
                                 else 0
                            end
                  end as valor1,
                  case when trim(rh03_padrao) not in ( $lotacao_filtro )
                             and rh30_regime <> 2 and g.r14_regist is null 
                       then $menor
                       else case when trim(rh03_padrao) not in ( $lotacao_filtro )
                                  and rh30_regime <> 2 and g.r14_regist is not null
                            then 0
                            else 0
                       end
                  end as valor2
           from gerfsal s
                 inner join rhpessoal    on rh01_regist = s.r14_regist
                 inner join rhpessoalmov on rh02_anousu = s.r14_anousu
                                        and rh02_mesusu = s.r14_mesusu
                                        and rh02_regist = s.r14_regist
                 inner join rhregime     on rh30_codreg  = rh02_codreg
                                        and rh30_instit  = rh02_instit
                 left join rhpespadrao   on rh02_seqpes = rh03_seqpes
                 inner join cgm on rh01_numcgm = z01_numcgm
           left  join (select r14_regist, 
                              sum(r14_valor) 
                       from gerfsal
                       where r14_anousu = $ano
                         and r14_mesusu = $mes
                         and r14_rubric in ('1228','1229')
                         and r14_instit = ".db_getsession('DB_instit')."
                       group by r14_regist
                      ) as g on g.r14_regist = s.r14_regist
           where s.r14_anousu = $ano
             and s.r14_mesusu = $mes
             and s.r14_instit = ".db_getsession("DB_instit")."
             and s.r14_rubric = '1249'
           ) as x
    group by z01_nome,
             z01_cgccpf
    order by z01_nome
    ) as dd
  	 ";

 
///  echo "<br><br><br><br><br>".$sql;
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
      <tr >
        <td align="right" nowrap title="Maior Valor a ser pago" >
        <strong>Maior Valor :&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
           if(!isset($maior) || $maior == 0){
             $maior = 0;
           }
           db_input('maior',4,$maior,true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap title="Maior Valor a ser pago" >
        <strong>Menor Valor :&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
           if(!isset($menor) || $menor == 0){
             $menor = 0;
           }
           db_input('menor',4,$menor,true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr >
        <td align="right" ><strong>TOTALIZACAO :&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            $arr_totais = array("t"=>"Tudo","s"=>"Somente Totais");
            db_select('totais',$arr_totais ,true,4,"");
	        ?>
	      </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="gera" id="gera" type="submit" value="Arquivo"  >
          <input  name="emite2" id="emite2" type="button" value="Relatorio" onclick="js_emite();" >
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
function js_emite(){
  qry =   '&ano='+document.form1.ano.value+
	        '&mes='+document.form1.mes.value+
	     '&totais='+document.form1.totais.value+
	      '&maior='+document.form1.maior.value+
	      '&menor='+document.form1.menor.value;
  jan = window.open('pes2_guarecargarefeisul002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
  <?
  if(isset($gera)){
  	echo "js_montarlista('".$arq."#Arquivo gerado em: ".$arq."','form1');";
  }
  ?>
</script>