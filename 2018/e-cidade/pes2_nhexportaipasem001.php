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

  if($exporta == 'I'){
  
    if ($folha == 'r14'){
         $xarquivo = 'salario';
         $_arquivo = 'gerfsal';
    }elseif ($folha == 'r20'){
         $xarquivo = 'rescisao';
         $_arquivo = 'gerfres';
    }elseif ($folha == 'r35'){
         $xarquivo = '13o_salario';
         $_arquivo = 'gerfs13';
    }elseif ($folha == 'r48'){
         $xarquivo = 'complementar';
         $_arquivo = 'gerfcom';
    }
    
  $arq = "/tmp/ipasem_".$xarquivo."_".db_formatar($mes,'s','0',2,'e',0).$ano.".txt";

  $arquivo = fopen($arq,'w');  

  $sql = "
  select  
                lpad(coalesce(rh01_regist,'0'),6,'0')
       ||       coalesce($ano,0)
       ||       lpad(coalesce($mes,0),2,'0')
       ||       lpad(trim(translate(to_char(base_prev,'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(base_co_part,'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(base_assist,'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(desc_co_part,'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(salario_beneficio,'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(substr(db_fxxx(rh02_regist, $ano, $mes , rh02_instit),111,11)::float8, '9999999999.99'),'.', '')),11, '0')
       ||       lpad(trim(translate(to_char(padrao,'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(classe,'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(vantagem_pessoal_166,'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(vantagem_pessoal_662,'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(outras_vantagens,'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(valor_cc_adp_v,'999999.99'),'.','')),11,'0')
       ||       lpad(r70_estrut,12,' '::text)
       ||       lpad(rh02_funcao::char,7,' '::text)
       ||       lpad(' ',5 ,' '::text)
       ||       lpad(valor_cc_adp_r, 4, ' '::text)
       ||       lpad(trim(translate(to_char(round(base_prev/100*13.97,2),'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(round(base_prev/100*1.3,2),'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(round(base_assist/100*4.9,2),'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(round(base_assist/100*0.6,2),'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(round(base_prev/100*10.3,2),'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(round(base_prev/100*0.7,2),'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(round(base_assist/100*4.9,2),'999999.99'),'.','')),11,'0')
       ||       lpad(trim(translate(to_char(round(base_assist/100*0.6,2),'999999.99'),'.','')),11,'0')
       as tipo
from rhpessoal
     inner join cgm             on rh01_numcgm    = z01_numcgm
     inner join rhpessoalmov    on rh02_regist    = rh01_regist
                               and rh02_instit    = ".db_getsession("DB_instit")."
     inner join rhlota          on r70_codigo     = rh02_lota and r70_instit = ".db_getsession("DB_instit")."
     inner join rhfuncao        on rh01_funcao    = rh37_funcao and rh37_instit = ".db_getsession("DB_instit")." 
     inner join rhinstrucao     on rh01_instru    = rh21_instru
     inner join rhestcivil      on rh01_estciv    = rh08_estciv
     left join  rhpesdoc        on rh16_regist    = rh01_regist
     left join  rhpespadrao     on rh02_seqpes    = rh03_seqpes
     inner join rhregime        on rh30_codreg    = rh02_codreg
     left join  rhpesbanco      on rh44_seqpes    = rh02_seqpes
     inner join (select ".$folha."_regist as regist,
                        round(sum(case when ".$folha."_rubric in ('R985', 'R987') then ".$folha."_valor else 0 end),2) as base_prev,
                        round(sum(case when ".$folha."_rubric in ('0014', '2014') then ".$folha."_valor else 0 end),2) as base_co_part,
                        round(sum(case when ".$folha."_rubric in ('0014', '2014') then ".$folha."_valor else 0 end),2) as base_assist,
                        round(sum(case when ".$folha."_rubric = '0164' then ".$folha."_valor else 0 end),2) as desc_co_part,
                        round(sum(case when ".$folha."_rubric = 'R985' then ".$folha."_valor else 0 end),2) as salario_beneficio,
                        round(sum(case when ".$folha."_rubric in ('0117', '0234', '0235', '0237', '0238') then ".$folha."_valor else 0 end),2) as padrao,
                        round(sum(case when ".$folha."_rubric in ('0130', '0131', '0144', '0145') then ".$folha."_valor else 0 end),2) as classe,
                        round(sum(case when ".$folha."_rubric = '0177' then ".$folha."_valor else 0 end),2) as vantagem_pessoal_166,
                        round(sum(case when ".$folha."_rubric = '0177' then ".$folha."_valor else 0 end),2) as vantagem_pessoal_662,
                        round(sum(case when ".$folha."_rubric in ('0117', '0234', '0235', '0237', '0238', '0130', '0131', '0144', 
                                                            '0145', '0115', '0126', '0221') then ".$folha."_valor else 0 end),2) as outras_vantagens,
                        round(sum(case when ".$folha."_rubric in ('0115', '0126', '0221') then ".$folha."_valor else 0 end),2) as valor_cc_adp_v,
                        max(case when ".$folha."_rubric in ('0115', '0126', '0221') then ".$folha."_rubric else '0000' end)as valor_cc_adp_r
                 from $_arquivo
                 where ".$folha."_anousu = $ano
                   and ".$folha."_mesusu = $mes
                   and ".$folha."_instit = ".db_getsession("DB_instit")." 
                 group by ".$folha."_regist) as arquivo   on regist     = rh01_regist
where rh02_anousu    = $ano 
  and rh02_mesusu    = $mes
  and rh02_codreg    = 1
  and rh02_instit    = ".db_getsession("DB_instit")."
order by z01_nome";


  }
// echo "<br><br><br><br><br>".$sql;
// exit;
  $result = pg_query($sql);
// db_criatabela($result);exit; 
  if($exporta == 'E'){
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
	  $arr_f = array('r14'=>'Salário',
                 'r20'=>'Rescisão',
                 'r35'=>'13o. Salário',
                 'r48'=>'Complementar'
                 );
	  db_select("folha",$arr_f,true,1);
	?>
        </td>
      </tr>
      <tr>
        <td align="right"><b>Tipo :&nbsp;&nbsp;<b></td>
	<td align="left">
	<?
	  $arr = array('I'=>'IPASEM',
                 
                 );
	  db_select("exporta",$arr,true,1);
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