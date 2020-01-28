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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("dbforms/db_layouttxt.php"));
include(modification("libs/db_libpessoal.php"));
include(modification("libs/db_sql.php"));
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<?if(!isset($emite2)){?>
<table align="center">
  <form name="form1" method="post" action="">
  <?
  if(!isset($anofolha) || (isset($anofolha) && trim($anofolha) == "")){
    $anofolha = db_anofolha();
  }
  if(!isset($mesfolha) || (isset($mesfolha) && trim($mesfolha) == "")){
    $mesfolha = db_mesfolha();
  }

  $ncodini = "cod_ini";
  $ncodfim = "cod_fim";
  $nlista  = "lista";

  include(modification("dbforms/db_classesgenericas.php"));
  $geraform = new cl_formulario_rel_pes;

  db_input('rodape',40,0,true,'hidden',3,'');
  $geraform->js_anomes = "onchange='js_anomes();'";
  $geraform->usalota = true;                     // PERMITIR SELEÇÃO DE LOTAÇÕES
  $geraform->usaregi = true;                     // PERMITIR SELEÇÃO DE MATRÍCULAS
  $geraform->usaorga = true;                     // PERMITIR SELEÇÃO DE MATRÍCULAS

  $geraform->lo1nome = $ncodini;                  // NOME DO CAMPO DA LOTAÇÃO INICIAL
  $geraform->lo2nome = $ncodfim;                  // NOME DO CAMPO DA LOTAÇÃO FINAL
  $geraform->lo3nome = $nlista;                   // NOME DO CAMPO DE SELEÇÃO DE LOTAÇÕES

  $geraform->or1nome = "$ncodini";                // NOME DO CAMPO DO ÓRGÃO INICIAL
  $geraform->or2nome = "$ncodfim";                // NOME DO CAMPO DO ÓRGÃO FINAL
  $geraform->or3nome = "$nlista";                 // NOME DO CAMPO DE SELEÇÃO DE ÓRGÃOS
  
  $geraform->re1nome = $ncodini;                  // NOME DO CAMPO DA MATRÍCULA INICIAL
  $geraform->re2nome = $ncodfim;                  // NOME DO CAMPO DA MATRÍCULA FINAL
  $geraform->re3nome = $nlista;                   // NOME DO CAMPO DE SELEÇÃO DE MATRÍCULAS

  $geraform->tfinome = "filtro";                  // NOME DO CAMPO TIPO DE FILTRO
  $geraform->strngtipores = "gmlo";               // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
                                                  //                                       m - matrícula,
                                                  //                                       l - lotação,
                                                  //                                       o - órgão,

  $geraform->tipofol = true;                      // NOME DO CAMPO PARA TIPO DE FOLHA
  $geraform->arr_tipofol = array(
                                 "salario"=>"Salário",
                                 "complementar"=>"Complementar",
                                 "rescisao"=>"Rescisão",
                                 "13salario"=>"13o. Salário",
                                 "adiantamento"=>"Adiantamento"
                                );
  $geraform->complementar = "complementar";                // VALUE DA COMPLEMENTAR PARA BUSCAR SEMEST 

  $geraform->campo_auxilio_lota = "faixa_lotac";  // NOME DO DAS LOTAÇÕES SELECIONADAS
  $geraform->campo_auxilio_regi = "faixa_regis";  // NOME DO DAS MATRÍCULAS SELECIONADOS

  $geraform->trenome = "tipo";                    // NOME DO CAMPO TIPO DE RESUMO
  $geraform->mostord = true;
  $geraform->arr_mostord = Array("a"=>"Alfabética", "n"=>"Numérica");

  $geraform->onchpad = true;                      // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
  $geraform->gera_form($anofolha,$mesfolha);
  ?>
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
              db_input('mensagem1',70,0,true,'text',1,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right">
	      <b>Linha 2:</b>
            </td>
            <td> 
              <?
              db_input('mensagem2',70,0,true,'text',1,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right">
	      <b>Linha 3:</b>
            </td>
            <td> 
              <?
              db_input('mensagem3',70,0,true,'text',1,"")
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center"> 
      <input name="emite2" id="emite2" type="submit" value="Processar" onclick="return js_selecionaselect();">
    </td>
  </tr>
  </form>
</table>
<?}else{?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <form name="form1">
  <tr>
    <td>
    <?
    db_criatermometro("termometro");
    ?>
    </td>
  </tr>
  </form>
</table>
<?
}
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_selecionaselect(){
  if(document.form1.lista){
    for(x=0;x<document.form1.lista.length;x++){
      document.form1.lista.options[x].selected = true;
    }
  }
  return true;
}
function js_anomes(){
  if(document.form1.tipofol.value == "complementar"){
    document.form1.submit();
  }
}
</script>
<?
if(isset($emite2)){
  db_sel_instit();

  $xtipo = "'x'";
  $qualarquivo = '';
  if ( $tipofol == 'salario' ){
    $sigla   = 'r14';
    $qualarquivo = 'Salário';
  }elseif ( $tipofol == 'ferias' ){
    $sigla   = 'r31';
    $arquivo = 'gerffer';
    $qualarquivo = 'Férias';
    $xtipo   = ' r31_tpp ';
  }elseif ( $tipofol == 'rescisao' ){
    $sigla   = 'r20';
    $arquivo = 'gerfres';
    $qualarquivo = 'Rescisão';
    $xtipo   = ' r31_tpp ';
  }elseif ($tipofol == 'adiantamento'){
    $sigla   = 'r22';
    $qualarquivo = 'Adiantamento';
  }elseif ($tipofol == '13salario'){
    $sigla   = 'r35';
    $qualarquivo = '13o. Salário';
  }elseif ($tipofol == 'complementar'){
    $sigla   = 'r48';
    $qualarquivo = 'Complementar';
  }elseif ($tipofol == 'fixo'){
    $sigla   = 'r53';
    $qualarquivo = 'Fixo';
  }elseif ($tipofol == 'previden'){
    $sigla   = 'r60';
    $qualarquivo = 'Ajuste da Previdência';
  }elseif ($tipofol == 'irf'){
    $sigla   = 'r61';
    $qualarquivo = 'Ajuste do IRRF';
  }

  $ordenacao = "z01_nome";
  if($mostord == "n"){
    $ordenacao = "rh01_regist";
  }

  $txt_where="";
  if (isset($tipo)&&$tipo!='g'){
    if ($tipo=='m'){
      $campo="rh01_regist";
    }else if ($tipo=='l'){
      $ordenacao = "r70_estrut, ".$ordenacao;
      $campo="r70_estrut";
      if (isset($cod_ini)){
        $cod_ini = "'$cod_ini'";
        $cod_fim = "'$cod_fim'";
      }
    }else if ($tipo=='o'){
      $ordenacao = " o40_orgao, ".$ordenacao;
      $campo=" o40_orgao ";
      if (isset($cod_ini)){
        $cod_ini = "'$cod_ini'";
        $cod_fim = "'$cod_fim'";
      }
    }
    if (isset($lista)&&count($lista)>0){
      $dados = "";
      $vir = "";
      for($i=0; $i<count($lista); $i++){
        $dados .= $vir.$lista[$i];
        $vir = ",";
      }
      if($tipo == "l"){
        $dados = "'".str_replace(",","','",$dados)."'";
      }
      $txt_where=" $campo in ($dados) ";
    }elseif (isset($cod_ini)){
      $txt_where=" $campo between $cod_ini and $cod_fim ";
    }
  }

  $wheresemest = "";
  if(isset($r48_semest) && trim($r48_semest) != 0){
    $wheresemest = " r48_semest = ".$r48_semest;
  }

  $clgeradorsql = new cl_gera_sql_folha;
  $clgeradorsql->usar_cgm = true;
  $clgeradorsql->usar_org = true;
  $clgeradorsql->usar_fun = true;
  $clgeradorsql->usar_lot = true;
  $clgeradorsql->usar_car = true;
  $clgeradorsql->usar_ban = true;
  $clgeradorsql->inner_ban = false;

  $sqlDentro = $clgeradorsql->gerador_sql(
                                          null,
                                          $anofolha,
                                          $mesfolha,
                                          null,
                                          null,
                                          "
                                           distinct
                                           rhpessoal.*,
                                           rh37_descr,
                                           r70_estrut,
																					 o40_orgao,
																					 o40_descr,
                                           r70_descr,
					   r70_codigo,
                                           cgm.*,rh44_conta,
                                           substr(r70_estrut,1,7) as estrut
                                          ",
                                          $ordenacao,
                                          $txt_where
                                         );
	echo $sqlDentro;
  $res = db_query($sqlDentro);
  $num = pg_numrows($res);
  if($num == 0){
    $erro_msg = "Não existe cálculo no período de $mesfolha / $anofolha";
    $sqlerro = true;
  }else{
    $sqlerro = false;
    $nome_arquivo = "/tmp/Contra_$anofolha-$mesfolha.txt";
    $db_layouttxt = new db_layouttxt(16,$nome_arquivo);

    define("CABECALHOARQUIVO",1);
    define("REGISTROSARQUIVO",3);
    define("RODAPEARQUIVO",5);

    $db_layouttxt->adicionaLinha(chr(15));
    $db_layouttxt->adicionaLinha($db_layouttxt->quebraLinha(1));
    $clgeradorsql->inicio_rh = false;
    $clgeradorsql->usar_pes = false;
    $clgeradorsql->usar_rub = true;
    $clgeradorsql->usar_cgm = false;
    $clgeradorsql->usar_fun = false;
    $clgeradorsql->usar_lot = false;
    $clgeradorsql->usar_car = false;
    $clgeradorsql->usar_ban = false;
    
    $contadorContra = 0;
    $contapaginas = 0;
    for($i=0;$i<$num;$i++){
      db_fieldsmemory($res,$i);
      db_atutermometro($i,$num,"termometro");
      $z01_cgccpf = db_formatar($z01_cgccpf,"cpf");

      db_retorno_variaveis($anofolha, $mesfolha, $rh01_regist);

      $sql = $clgeradorsql->gerador_sql($sigla,
                                        $anofolha,
                                        $mesfolha,
      	 		                $rh01_regist,
      	 			        null,
      	 			        "
      	 			         rh27_rubric,
              			         rh27_descr,
              			         round(#s#_valor,2) as valorrubrica,
              			         round(#s#_quant,2) as quantrubrica,
                                         ".$xtipo." as tipo ,
                                         case when rh27_pd = 3 then 'B' 
                                              else case when #s#_pd = 1 then 'P' 
                                                   else 'D' 
                                              end 
                                         end as provdesc
      	 			        ",
      	 			        "rh27_rubric",
      	 			        $wheresemest
      	 			       );
 
      $multiplic = 1;

      $salario = trim(db_formatar($f010,"f"));

      $somaconprev = 0;
      $somaproventos = 0;
      $somadescontos = 0;
      $somaprovdesc = 0;
      $somafgts = 0;
      $somabaseirrf = 0;
      $somadependentesirfq = 0;
      $somadependentesirfv = 0;
      $somafaixairrf = 0;
      $somabaseprevidencia = 0;
      $somabaseliquida = 0;
      $fgtsmes = 0;
      $res_env = db_query($sql);
      if(pg_num_rows($res_env) > 0){
	$contadorContra ++;
	$contapaginas ++;
        for($x = 0,$linhastesta=0; $x<pg_num_rows($res_env); $x++){
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
       
          if($rh27_rubric >= "R981" && $rh27_rubric < "R984"){
            $somabaseirrf+= $valorrubrica;
          }
       
          if($rh27_rubric == "R984"){
            $somadependentesirfv+= $valorrubrica;
            $somadependentesirfq+= $quantrubrica;
          }
       
          if($rh27_rubric >= "R913" && $rh27_rubric <= "R915"){
            $somafaixairrf += $quantrubrica;
          }
       
          if($rh27_rubric >= "R985" && $rh27_rubric <= "R987"){
            $somabaseprevidencia += $valorrubrica;
          }
       
          $somabaseliquida = $somabaseirrf - $somadependentesirfv - $somaconprev;
       
          $antestotalproventos = trim(db_formatar($somaproventos,"f"));
          $antestotaldescontos = trim(db_formatar($somadescontos,"f"));
          $antestotalprovdesc  = trim(db_formatar($somaprovdesc,"f"));
          $valorrubrica = trim(db_formatar($valorrubrica,"f"));
          $quantrubrica = trim(db_formatar($quantrubrica,"f"));
          $conprev = trim(db_formatar($somaconprev,"f"));
          $baseirrf = trim(db_formatar($somabaseirrf,"f"));
          $fgts = trim(db_formatar($somafgts,"f"));
          $dependentesirfq = trim(db_formatar($somadependentesirfq,"f"));
          $dependentesirfv = trim(db_formatar($somadependentesirfv,"f"));
          $faixairrf = trim(db_formatar($somafaixairrf,"f"));
          $baseprevidencia = trim(db_formatar($somabaseprevidencia,"f"));
          $baseliquida = trim(db_formatar($somabaseliquida,"f"));
          $fgtsmes = trim(db_formatar($somafgts,"f"));
          if($provdesc != "B"){
            $linhastesta ++;
          }

	}

        for($x=0,$linhas=0; $x<pg_num_rows($res_env); $x++){
          db_fieldsmemory($res_env, $x);
          if(($x == 0) || (($linhas % ($db_layouttxt->_quantLinhasLay * $multiplic)) == 0 && $linhastesta > $db_layouttxt->_quantLinhasLay)){
            if($x != 0){
              $multiplic ++;
              $totalproventos = $antestotalproventos;
              $totaldescontos = $antestotaldescontos;
              $totalprovdesc  = $antestotalprovdesc;
              db_setaPropriedadesLayoutTxt($db_layouttxt,RODAPEARQUIVO);
              $contapaginas ++;
            }
            $periodo = "01/$mesfolha/$anofolha a ".db_dias_mes($anofolha,$mesfolha)."/$mesfolha/$anofolha";
            $matricula = $rh01_regist."-".db_CalculaDV($rh01_regist);
            $contabancaria = $rh44_conta;
            db_setaPropriedadesLayoutTxt($db_layouttxt,CABECALHOARQUIVO);
          }

          if($provdesc != "B"){
            $linhas ++;
            $valordesc = trim(db_formatar($valorrubrica,"f"));
            $valorprov = "";
            $quantrubrica = trim(db_formatar($quantrubrica,"f"));
            if($provdesc == "P"){
              $valorprov = trim(db_formatar($valorrubrica,"f"));
              $valordesc = "";
            }
            db_setaPropriedadesLayoutTxt($db_layouttxt,REGISTROSARQUIVO);
          }
        }

        $totalproventos = $antestotalproventos;
        $totaldescontos = $antestotaldescontos;
        $totalprovdesc  = $antestotalprovdesc;

        $multiplic *= $db_layouttxt->_quantLinhasLay;
        $multiplic -= $linhas;
        $db_layouttxt->quebraLinha($multiplic);
       
        db_setaPropriedadesLayoutTxt($db_layouttxt,RODAPEARQUIVO);
      }

    }
    $db_layouttxt->adicionaLinha(chr(18));

  }
  $qry = "?arquivoimprime=/tmp/Contra_$anofolha-$mesfolha.txt";
  if($sqlerro == true){
    db_msgbox($erro_msg);
    $qry = "";
  }else{
    echo "<script>alert('Número de contra cheques gerados: $contadorContra');</script>";
    if($contadorContra == 0){
      $qry = "";
    }
  }
  echo "<script>location.href = 'pes2_bagcontramatricial001.php".$qry."'</script>";
}else if(isset($arquivoimprime)){
 echo "<script>js_arquivo_abrir('$arquivoimprime');</script>";
}
?>
