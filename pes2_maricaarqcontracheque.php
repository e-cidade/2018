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
include("dbforms/db_classesgenericas.php");
include("classes/db_selecao_classe.php");
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$gform = new cl_formulario_rel_pes;
$clselecao = new cl_selecao();

if (isset($gera) || isset($gera1)){

  
  if(isset($gera1)){
    $xarquivo = 'gerfs13';
    $sigla   = 'r35_';
    $arq = "/tmp/CHK-13salario-".db_formatar($mesfolha,'s','0',2,'e',0)."$anofolha.txt"  ;
  }else{
    $xarquivo = 'gerfsal';
    $sigla   = 'r14_';
    $arq = "/tmp/CHK-mensal-".db_formatar($mesfolha,'s','0',2,'e',0)."$anofolha.txt"  ;
  }

  $where = " ";
  if(trim($selecao) != ""){
    $result_selecao = $clselecao->sql_record($clselecao->sql_query_file($selecao,db_getsession("DB_instit"),"*"));
    if($clselecao->numrows > 0){
      db_fieldsmemory($result_selecao, 0);
      $where = " and ".$r44_where;
      $head8 = "SELEÇÃO: ".$selecao." - ".$r44_descr;
    }
  }


  $arquivo = fopen($arq,'w');  

    $sql = "select * from 
          (
          select
	               r70_estrut,
                 lpad(rh01_regist,6,'0') as regist,
                 rpad(z01_nome,45,' ') as nome, 
		             rpad(case when rh04_descr is null then rh37_descr else rh04_descr end ,40,' ') as cargo,
		             to_char(rh01_admiss,'dd/mm/YYYY') as admissao,
		             rpad(z01_cgccpf,11,' ') as cpf,
		             lpad(substr(rh44_codban,1,3),3,'0') as banco,
		             rh44_agencia as agencia,
		             rh44_dvagencia as dvagencia,
                 translate(to_char(to_number((case when trim(rh44_conta) = '' then '0' else rh44_conta end ) ,'99999999999'),'99,999999,9'),',','') as conta,
		             substr(rh44_dvconta,1,1) as dvconta,
		             rpad(rh52_descr,16,' ') as regime ,
		             rh52_regime as cod_regime ,
		             rpad(o40_descr,45,' ') as orgao,
		             rpad(r70_descr,48,' ') as setor,
		             rpad(z01_ender,45,' ') as ender,
		             rpad(substr(z01_compl,1,15),15,' ') as compl,
		             to_char(z01_numero,'999999') as numero ,
		             z01_cep as cep,
		             rpad(substr(z01_bairro,1,25),25,' ') as bairro,
		             rpad(substr(z01_munic,1,25),25,' ') as munic,
                 lpad(coalesce(rh16_pis,'0'),14,'0') as rh16_pis,
                 translate(coalesce(round(rh02_salari,2),0),'. ',', ') as salario,
		             rh55_descr,
                 rh30_descr,
                 rh21_descr,
                 translate(substr(db_fxxx(matricula,$anofolha,$mesfolha,".db_getsession("DB_instit")."),111,11),'. ',', ') as salario_base,
                 lpad(substr(db_fxxx(matricula,$anofolha,$mesfolha,".db_getsession("DB_instit")."),45,11)::numeric(1),2,'0') as dep_irrf,
                 lpad(substr(db_fxxx(matricula,$anofolha,$mesfolha,".db_getsession("DB_instit")."),56,11)::numeric(1),2,'0') as dep_sf,
                 translate(proventos,'. ',', ') as proventos,
                 translate(descontos,'. ',', ') as descontos,
                 translate(round(proventos - descontos,2),'. ',', ') as liquido,
                 translate(baseirrf,'. ',', ') as baseirrf,
                 translate(baseprev,'. ',', ') as baseprev,
                 translate(basefgts,'. ',', ') as basefgts,
                 translate(round((basefgts/100*8),2),'. ',', ') as valor_fgts,
                 translate(margem_consignavel,'. ',', ') as margem_consignavel
         from rhpessoalmov
	       inner join rhpessoal   on rh01_regist = rh02_regist
         left  join rhinstrucao on rh21_instru = rh01_instru 
	       inner join (select ".$sigla."regist as matricula, 
                            round(sum(case when ".$sigla."pd = 1 then ".$sigla."valor else 0 end),2) as proventos,
                            round(sum(case when ".$sigla."pd = 2 then ".$sigla."valor else 0 end),2) as descontos,
                            round(sum(case when ".$sigla."rubric in ('R981', 'R982') then ".$sigla."valor else 0  end),2) as baseirrf,
                            round(sum(case when ".$sigla."rubric in ('R992') then ".$sigla."valor else 0  end),2) as baseprev,
                            round(sum(case when ".$sigla."rubric in ('R991') then ".$sigla."valor else 0  end),2) as basefgts,
                            round(sum( case when ".$sigla."rubric in ('R803') then ".$sigla."valor else 0  end),2) as margem_consignavel

                     from ".$xarquivo."
                     where ".$sigla."anousu = $anofolha
                       and ".$sigla."mesusu = $mesfolha
                       and ".$sigla."instit = ".db_getsession("DB_instit")."
                     group by ".$sigla."regist
                    ) as arquivo 
                     on matricula = rh01_regist 
	       inner join cgm on z01_numcgm = rh01_numcgm
         left join rhpesbanco    on rh44_seqpes = rh02_seqpes
	       inner join rhfuncao     on rh37_funcao = rh01_funcao
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
         left join rhpesdoc      on rh16_regist = rh01_regist
         left join rhpeslocaltrab on rh56_seqpes = rh02_seqpes
                                 and rh56_princ = true
         left join rhlocaltrab    on rh55_codigo = rh56_localtrab 
                                 and rh56_princ  = 't'

				 
	 where rh02_anousu = $anofolha 
     and rh02_mesusu = $mesfolha
     and rh02_instit = ".db_getsession("DB_instit")."
     $where
     ) as xxx
	 order by r70_estrut, nome
	 ";
// echo "<br><br><br><br><br>".$sql;exit;
  $result = pg_query($sql);
  for($x = 0;$x < pg_numrows($result);$x++){
    db_fieldsmemory($result,$x);
    $dados_pessoais = 
                      db_formatar(db_mes($mesfolha,2).'/'.$anofolha,'s',' ', 20 ,'d',0).
                      db_formatar($regist, 's','0', 12 ,'e',0).
                      db_formatar($nome ,'s',' ',48,'d',0).
		                  db_formatar($cargo,'s',' ',48,'d',0).
		                  db_formatar($setor,'s',' ',48,'d',0).
		                  db_formatar($admissao,'s',' ', 10,'d',0).
		                  db_formatar($banco,'s',' ',3,'d',0).
                      "/".
		                  db_formatar($agencia,'s','0',4,'e',0).
                      "/".
		                  db_formatar($conta  ,'s','0',8,'e',0).
                      '-'.
                      $dvconta.
		                  db_formatar(db_formatar($cpf,'cpf'),'s',' ',20,'e',0).
                      substr($rh16_pis, 0, 3).".".substr($rh16_pis, 3, 5).".".substr($rh16_pis, 8, 2)."/".substr($rh16_pis, 10, 1).
		                  db_formatar($salario_base,'s','0',15,'e',0).
		                  db_formatar($baseprev,'s','0',15,'e',0).
		                  db_formatar($basefgts,'s','0',15,'e',0).
		                  db_formatar($valor_fgts,'s','0',15,'e',0).
		                  db_formatar($baseirrf,'s','0',15,'e',0).
		                  db_formatar($margem_consignavel,'s','0',15,'e',0).
		                  db_formatar($dep_irrf,'s','0',2,'e',0).
		                  db_formatar($dep_sf,'s','0',2,'e',0).
		                  db_formatar($proventos,'s','0',15,'e',0).
		                  db_formatar($descontos,'s','0',15,'e',0).
		                  db_formatar($liquido,'s','0',15,'e',0).
		                  db_formatar($rh55_descr,'s',' ',40,'d',0).
		                  db_formatar($rh30_descr,'s',' ',25,'d',0).
		                  db_formatar($rh21_descr,'s',' ',25,'d',0);

    $sql_ger = "select substr(".$sigla."rubric,2,3) as rubric,
                       translate(round(".$sigla."quant,2),'.',',') as quant,
		                   translate(round(".$sigla."valor,2),'.',',') as valor,
		                   case when ".$sigla."pd = 1 then 'P' else 'D' end as pd,
		                   rh27_descr as descr,
                       ".$sigla."pd as tipo
								from ".$xarquivo." 
								     inner join rhrubricas on ".$sigla."rubric = rh27_rubric
                                              and ".$sigla."instit = rh27_instit         
								where ".$sigla."anousu = $anofolha
								  and ".$sigla."mesusu = $mesfolha
								  and ".$sigla."regist = $regist
									and ".$sigla."instit = ".db_getsession("DB_instit")."
								order by ".$sigla."regist, ".$sigla."rubric
		";
//    echo "<br><br>".$sql_ger;exit;
    $base_prev = 0;
    $base_irrf = 0;
    $base_fgts = 0;
    $fgts      = 0;
    $bruto     = 0;
    $desc      = 0;
    $res_ger = pg_query($sql_ger);
    $dados_financeiros = ''; 
    for($g = 0;$g < pg_numrows($res_ger);$g++){
      db_fieldsmemory($res_ger,$g);
      if($tipo == 3){
        continue;
      }
      $dados_financeiros .=  
                            $rubric.
		                        db_formatar($descr,'s',' ',48,'d',0).
                            $pd.
                            db_formatar(trim(substr($quant,0,5)),'s','0',5,'e',0).
                            ' '.
		                        db_formatar($valor,'s','0',15,'e',0);
 
    }
    fputs($arquivo, $dados_pessoais.$dados_financeiros.'FIM'."\r\n");
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
    <?
  $gform->selecao = true;
  $gform->desabam = false;
  $gform->manomes = true;
  $gform->gera_form(db_anofolha(),db_mesfolha());
  ?>
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
  if(isset($gera) || isset($gera1)){
  	echo "js_montarlista('".$arq."#Arquivo gerado em: ".$arq."','form1');";
  }
  ?>
</script>