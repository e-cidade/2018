<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("dbforms/db_layouttxt.php");
db_postmemory($HTTP_SERVER_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$sql = "select * from db_config where codigo = ".db_getsession("DB_instit");
$result = db_query($sql);
db_fieldsmemory($result,0);

$xtipo = "'x'";
$qualarquivo = '';
if ( $opcao == 'salario' ){
  $sigla   = 'r14_';
  $arquivo = 'gerfsal';
  $qualarquivo = 'Salário';
}elseif ( $opcao == 'ferias' ){
  $sigla   = 'r31_';
  $arquivo = 'gerffer';
  $xtipo   = ' r31_tpp ';
  $qualarquivo = 'Férias';
}elseif ( $opcao == 'rescisao' ){
  $sigla   = 'r20_';
  $arquivo = 'gerfres';
  $xtipo   = ' r20_tpp ';
  $qualarquivo = 'Rescisão';
}elseif ($opcao == 'adiantamento'){
  $sigla   = 'r22_';
  $arquivo = 'gerfadi';
  $qualarquivo = 'Adiantamento';
}elseif ($opcao == '13salario'){
  $sigla   = 'r35_';
  $arquivo = 'gerfs13';
  $qualarquivo = '13o. Salário';
}elseif ($opcao == 'complementar'){
  $sigla   = 'r48_';
  $arquivo = 'gerfcom';
  $qualarquivo = 'Complementar';
}elseif ($opcao == 'fixo'){
  $sigla   = 'r53_';
  $arquivo = 'gerffx';
  $qualarquivo = 'Fixo';
}elseif ($opcao == 'previden'){
  $sigla   = 'r60_';
  $arquivo = 'previden';
  $qualarquivo = 'Ajuste da Previdência';
}elseif ($opcao == 'irf'){
  $sigla   = 'r61_';
  $arquivo = 'ajusteir';
  $qualarquivo = 'Ajuste do IRRF';
}

$txt_where="";
if (isset($filtro)&&$filtro!='N'){
  if ($filtro=='M'){
    $campo=$sigla."regist";
  }else if ($filtro=='L'){
    $campo=$sigla."lotac";
  }
  if (isset($dados)&&$dados!=""){
    $txt_where=" and $campo in ($dados) ";
  }elseif (isset($codini)){
    $txt_where=" and $campo between $codini and $codfim ";
  }
}

if(isset($local) && trim($local) != ""){
  $txt_where.= " and rh56_localtrab = ".$local;
}

$wheresemest = "";
$localtrabprinc = " and rh56_princ = 't' ";
if(isset($semest) && trim($semest) != 0){
  $wheresemest = " and r48_semest = ".$semest;
  $localtrabprinc = "";
}

$sql = "select distinct
          z01_nome,
					z01_cgccpf,
       		r37_descr,
       		r70_descr,
      		substr(r70_estrut,1,7) as estrut,
          ".$sigla."regist as regist,
        	substr(db_fxxx(".$sigla."regist,$ano,$mes,".db_getsession("DB_instit")."),111,11) as f010, 
      		substr(db_fxxx(".$sigla."regist,$ano,$mes,".db_getsession("DB_instit")."),221,8) as padrao,
          rhlocaltrab.*
          from (select distinct ".$sigla."regist,".$sigla."anousu,".$sigla."mesusu 
	                from ".$arquivo."
           	      where ".bb_condicaosubpesproc($sigla,$ano."/".$mes)." ".$wheresemest." ) ".$arquivo." 
            inner join rhpessoalmov on rh02_regist = ".$sigla."regist 
                               and rh02_anousu = $ano 
                               and rh02_mesusu = $mes 
                               and rh02_instit = ".db_getsession("DB_instit")."
						inner join rhpessoal on rh01_regist = rh02_regist									 
            inner join cgm     on rh01_numcgm  = z01_numcgm
            left join rhfuncao on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao
            left join rhlota on  rh02_lota = r70_codigo
                             and	r70_instit = rhpessoalmov.rh02_instit
            left join rhpeslocaltrab on rh56_seqpes = rh02_seqpes
            left join rhlocaltrab    on rh55_codigo = rh56_localtrab
		                                and rh55_instit = rhpessoalmov.rh02_instit
		" . $localtrabprinc . "
	where ".$sigla."anousu = $ano 
    and ".$sigla."mesusu = $mes
	  $txt_where
	order by estrut,z01_nome ";
$res = db_query($sql);
$num = pg_numrows($res);
if($num == 0){
  echo "<script>parent.js_detectaarquivo('',true,'Não existe Cálculo no período de $mes / $ano')</script>";
  exit;
}
$competencia = $ano."/".$mes;
$arquivoimprime = "/tmp/alecontra".$mes."-".$ano.".txt";
$db_layouttxt = new db_layouttxt(4,$arquivoimprime);

define("CABECALHOARQUIVO",1);
define("REGISTROSARQUIVO",3);
define("RODAPEARQUIVO",5);

$db_layouttxt->adicionaLinha(chr(15));
for($i=0;$i<$num;$i++){
  db_fieldsmemory($res,$i);
  $z01_cgccpf = db_formatar($z01_cgccpf,"cpf");
  $sql = "
  select ".$sigla."rubric as rh27_rubric,
         rh27_descr as rh27_descr, 
         round(".$sigla."valor,2) as valorrubrica,
         round(".$sigla."quant,2) as quantrubrica, 
         ".$xtipo." as tipo , 
         case when rh27_pd = 3 then 'B' 
              else case when ".$sigla."pd = 1 then 'P' 
                   else 'D' 
              end 
         end as provdesc
 
  from ".$arquivo." 
     inner join rhrubricas on rh27_rubric = ".$sigla."rubric 
  where ".$sigla."regist = $regist
    and ".$sigla."anousu = $ano 
    and ".$sigla."mesusu = $mes
    $wheresemest
  order by ".$sigla."rubric  ";

  $multiplic = 1;

  $salario = db_formatar($f010,"f");
  $somaconprev = 0;

  $somaproventos = 0;
  $somadescontos = 0;
  $somaprovdesc = 0;
  $somafgts = 0;
  $somabaseirrf = 0;
  $somadependentesirfq = 0;
  $somadependentesirfv = 0;
  $somafaixairrf = 0;

  $res_env = db_query($sql);
  for($x=0; $x<pg_num_rows($res_env); $x++){
    db_fieldsmemory($res_env, $x);

    if($provdesc == "P"){
      $somaproventos += $valorrubrica;
      $somaprovdesc += $valorrubrica;
    }else if($provdesc == "D"){
      $somadescontos += $valorrubrica;
      $somaprovdesc -= $valorrubrica;
    }

    if($rh27_rubric > "R900" && $rh27_rubric < "R910"){
      $somaconprev+= $valorrubrica;
    }

    if($rh27_rubric == "R991"){
      $somafgts+= $valorrubrica;
    }

    if($rh27_rubric >= "R981" || $rh27_rubric < "R984"){
      $somabaseirrf+= $valorrubrica;
    }

    if($rh27_rubric == "R984"){
      $somadependentesirfv+= $valorrubrica;
      $somadependentesirfq+= $quantrubrica;
    }

    if($rh27_rubric >= "R913" || $rh27_rubric <= "R915"){
      $somafaixairrf += $quantrubrica;
    }

    $totalproventos = trim(db_formatar($somaproventos,"f"));
    $totaldescontos = trim(db_formatar($somadescontos,"f"));
    $totalprovdesc  = trim(db_formatar($somaprovdesc,"f"));
    $valorrubrica   = trim(db_formatar($valorrubrica,"f"));
    $quantrubrica   = trim(db_formatar($quantrubrica,"f"));
    $conprev = trim(db_formatar($somaconprev,"f"));
    $baseirrf = trim(db_formatar($somabaseirrf,"f"));
    $fgts = trim(db_formatar($somafgts,"f"));
    $dependentesirfq = trim(db_formatar($somadependentesirfq,"f"));
    $dependentesirfv = trim(db_formatar($somadependentesirfv,"f"));
    $faixairrf = trim(db_formatar($somafaixairrf,"f"));

    if(($x==0) || ((($x+1) % $db_layouttxt->_quantLinhasLay) == 0)){
      if(($x+1) % $db_layouttxt->_quantLinhasLay == 0){
        $multiplic ++;
        db_setaPropriedadesLayoutTxt(&$db_layouttxt,RODAPEARQUIVO);
      }
      db_setaPropriedadesLayoutTxt(&$db_layouttxt,CABECALHOARQUIVO);
    }

    if($provdesc != "B"){
      db_setaPropriedadesLayoutTxt(&$db_layouttxt,REGISTROSARQUIVO);
    }

  }

  $multiplic *= $db_layouttxt->_quantLinhasLay;
  $multiplic -= pg_num_rows($res_env);

  $db_layouttxt->quebraLinha($multiplic);

  db_setaPropriedadesLayoutTxt(&$db_layouttxt,RODAPEARQUIVO);

}
$db_layouttxt->adicionaLinha(chr(18));

echo "<script>parent.js_detectaarquivo('$arquivoimprime',false,'Arquivo gerado com sucesso.')</script>";
?>