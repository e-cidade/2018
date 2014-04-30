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
    
  $where = '';
  if($demitidos == 'n'){
    $where = ' and rh05_seqpes is null ';
  }

  $arq = "/tmp/iapep_".$ano.db_formatar($mes,'s','0',2,'e',0).".txt";

  $rub_permanencia = "'0021', '2021', '4021'";

  if($ponto == 's'){
    $xarquivo = 'gerfsal';
    $sigla   = 'r14_';
    $base    = 'B018';
    $tipo_fol= '1';
  }elseif($ponto == 'd'){
    $xarquivo = 'gerfs13';
    $sigla   = 'r35_';
    $base    = 'B020';
    $tipo_fol= '2';
  }elseif($ponto == 'c'){
    $xarquivo = 'gerfcom';
    $sigla   = 'r48_';
    $base    = 'B018';
    $tipo_fol= '3';
  }

  $arquivo = fopen($arq,'w'); 
  $sql = "
  select 
       valor,
       rh01_regist,
       '1'
       ||'5'
       ||rh30_vinculo
       ||lpad(rh01_regist,6,'0')
       ||e_base
       ||lpad(z01_cgccpf,11,'0')
       ||pd
       ||rubric 
       ||rpad(descr_rubric,19,' ')
       ||lpad(translate(trim(to_char(valor,'99999999.99')),'.',''),12,'0')
       ||$ano
       ||lpad($mes,2,'0')
       ||$tipo_fol
       ||case when regist_perm is null then 1 else 2 end
       ||case when rh30_vinculo <> 'A' and rh02_portadormolestia = true then 1 else 2 end

       as tipo
       
from rhpessoal 
     inner join cgm             on rh01_numcgm    = z01_numcgm
     inner join rhpessoalmov    on rh02_anousu    = $ano
                               and rh02_mesusu    = $mes
                               and rh02_regist    = rh01_regist
                               and rh02_instit    = ".db_getsession("DB_instit")."
     left join  rhpesrescisao   on rh05_seqpes    = rh02_seqpes
     inner join (select ".$sigla."regist as regist,
                        ".$sigla."rubric as rubric,
                        ".$sigla."valor  as valor,
                        ".$sigla."pd     as pd,
                        rh27_descr       as descr_rubric,
                        case when ".$sigla."rubric in (select r09_rubric from basesr 
                                                       where r09_anousu = fc_anofolha(".db_getsession("DB_instit").")
                                                         and r09_mesusu = fc_mesfolha(".db_getsession("DB_instit").")
                                                         and r09_base   = '".$base."')
                             then 1 else 2 
                        end as e_base 
                 from ".$xarquivo." 
                 inner join rhrubricas on rh27_rubric = ".$sigla."rubric and rh27_instit = ".db_getsession("DB_instit")."
                 where ".$sigla."pd <> 3
                   and ".$sigla."anousu = $ano
                   and ".$sigla."mesusu = $mes
                   and ".$sigla."instit = ".db_getsession("DB_instit")."
                ) as calculo on rh01_regist = regist
     left join (select ".$sigla."regist as regist_perm from ".$xarquivo." where ".$sigla."anousu = $ano and ".$sigla."mesusu = $mes 
                                                                            and ".$sigla."instit = ".db_getsession("DB_instit")."   
                                                                            and ".$sigla."rubric in ($rub_permanencia)
               ) as calc_perm on regist_perm = rh01_regist
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
where rh30_regime = 1 
$where 
order by rh01_regist, rubric
" ;
  }
// echo "<br><br><br><br><br>".$sql;exit;
  $result = pg_query($sql);
// db_criatabela($result);exit; 
  if($exporta == 'C'){
    fputs($arquivo,"ano;mes;matricula; nome; salario; admissao;rescisao; sexo; nascimento; lotacao; descr_lotacao; funcao; descr_funcao; endereco; numero; complemento; bairro; municipio; uf; cep; telefone; instrucao; estado civil; matr_ipe; titulo; zona; secao; cert_reservista; cat_reserv; ctps_numero; ctps_serie; ctps_digito; ctps_uf; pis;cpf;rg; habilitacao; cat_habilit; validade_habilit; padrao; descr_tipo_vinculo; regime; vinculo; banco; agencia; dig_agencia; conta; dig_conta;estr_local;descr_local;trienio;progressao"."\r\n");
  }
  $total_funcionario = 0;
  $total_registro    = 0;
  $total_valor       = 0;
  $matricula_atual   = '';
  for($x = 0;$x < pg_numrows($result);$x++){
    db_fieldsmemory($result,$x);
    fputs($arquivo,$tipo."\r\n");

    $total_registro ++;
    $total_valor += $valor;
    if($matricula_atual != $rh01_regist){
      $total_funcionario ++;
      $matricula_atual = $rh01_regist;
    }

  }
  fputs($arquivo,
        "2"
       ."5"
       ." "
       .db_formatar($total_funcionario,'s','0',6,'e',0)
       .db_formatar($total_registro,'s','0',8,'e',0)
       .str_repeat(' ',28)
       .str_pad(number_format($total_valor,2,"",""), 12, "0", STR_PAD_LEFT)
       .db_formatar($ano,'s','0',4,'e',0)
       .db_formatar($mes,'s','0',2,'e',0)
       .$tipo_fol
       );
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
	  $arr = array('I'=>'Iapep');
	  db_select("exporta",$arr,true,1);
	?>
        </td>
      </tr>
      <tr>
        <td align="right"><b>Emite Demitidos :&nbsp;&nbsp;<b></td>
	<td align="left">
	<?
	  $arr_1 = array('n'=>'Não','s'=>'Sim');
	  db_select("demitidos",$arr_1,true,1);
	?>
        </td>
      </tr>
      <tr>
        <td align="right"><b>Cálculo :&nbsp;&nbsp;<b></td>
	<td align="left">
	<?
	  $arr_2 = array('s'=>'Salário','c'=>'Complementar', 'd'=>'13o Salário');
	  db_select("ponto",$arr_2,true,1);
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