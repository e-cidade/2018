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
include(modification("dbforms/db_classesgenericas.php"));
include(modification("classes/db_gerfcom_classe.php"));
include(modification("dbforms/db_layouttxt.php"));
include(modification("libs/db_sql.php"));
$aux = new cl_arquivo_auxiliar;
$clgerfcom = new cl_gerfcom;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
$clrotulo->label('r48_semest');
$clrotulo->label("rh56_localtrab");
$clrotulo->label("rh55_descr");
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_filtra(){
  document.form1.submit();
}
</script>  
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
  <tr>
    <td>
      <table>
        <tr>
          <td align="left" nowrap title="Digite o Ano / Mes de competência">
            <strong>Ano / Mês :&nbsp;&nbsp;</strong>
          </td>
          <td>
            <?
            if(!isset($ano) || (isset($ano) && (trim($ano) == "" || $ano == 0))){
              $ano = db_anofolha();
            }
            db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'onchange="js_anomes();"', "ano")
            ?>
            &nbsp;/&nbsp;
            <?
            if(!isset($mes) || (isset($mes) && trim($mes) == "" || $mes == 0)){
              $mes = db_mesfolha();
            }
            db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'onchange="js_anomes();"',"mes");
            db_input('rodape',40,0,true,'hidden',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td><strong>Tipo de Folha :</strong></td>
          <td>
            <select name="folha" onchange="js_tipofolha();">
              <option value = 'salario'       <?=((isset($folha)&&$folha=="salario")?"selected":"")?>>Salário
              <option value = 'complementar'  <?=((isset($folha)&&$folha=="complementar")?"selected":"")?>>Complementar
              <option value = 'rescisao'      <?=((isset($folha)&&$folha=="rescisao")?"selected":"")?>>Rescisão
              <option value = '13salario'     <?=((isset($folha)&&$folha=="13salario")?"selected":"")?>>13o. Salário
              <option value = 'adiantamento'  <?=((isset($folha)&&$folha=="adiantamento")?"selected":"")?>>Adiantamento
          </td>
        </tr>
        <?
        if(isset($folha) && $folha == "complementar"){
          $result_semest = $clgerfcom->sql_record($clgerfcom->sql_query_file($ano,$mes,null,null,"distinct r48_semest"));
          if($clgerfcom->numrows > 0){
            echo "
                  <tr>
                    <td align='left' title='".$Tr48_semest."'><strong>Nro. Complementar:</strong></td>
                    <td>
                      <select name='r48_semest'>
                        <option value = '0'>Todos
                 ";
                 for($i=0; $i<$clgerfcom->numrows; $i++){
                   db_fieldsmemory($result_semest, $i);
                   echo "<option value = '$r48_semest'>$r48_semest";
                 }
            echo "
                    </td>
                  </tr>
	         ";
          }else{
        ?>
        <tr>
          <td colspan="2" align="center">
            <font color="red">Sem complementar para este período.</font>
            <?
            $r48_semest = 0;
            db_input("r48_semest", 2,0, true, 'hidden', 3);
            ?>
          </td>
        </tr>
        <?
          }
        }
        ?>
	<tr>
	  <td><strong>Filtro:</strong></td>
	  <td>
	  <?
	  $arr=array("N"=>"Nenhum","M"=>"Matrícula","L"=>"Lotação","T"=>"Locais de trabalho");
	  db_select("filtro",$arr,true,2,"onchange='js_filtra();'"); 
	  ?>
	  </td>
	</tr>
	<?
	if (isset($filtro)&&$filtro!=""&&$filtro!="N"){
	?>
	<tr>
	  <td><strong>Filtrar por:</strong></td>
	  <td>
	  <?
	  $arr1=array("."=>"------------","I"=>"Intervalo","S"=>"Selecionados");
	  db_select("filtrar",$arr1,true,2,"onchange='js_filtra();'"); 
	  ?>
	  </td>
	</tr>
	<?
	}
	?>
      </table>
    </td>
  </tr>
  <?
  if(isset($filtrar)&&isset($filtro)&&$filtro!="N"){
    if($filtro=='M'){
      $func='func_rhpessoal.php';
      $info='Matrícula';
      $cod='rh01_regist';
      $descr='z01_nome';
    }else if ($filtro=='L'){
      $func='func_rhlota.php';
      $info='Lotação';
      $cod='r70_codigo';
      $descr='r70_descr';
    }else if ($filtro=='T'){
      $func='func_rhlocaltrab.php';
      $info='Locais';
      $cod='rh55_codigo';
      $descr='rh55_descr';
    }
    if($filtrar=='I'){
  ?>
  <tr>
    <td nowrap>
      <table>
        <tr>
          <td>
            <strong><?=@$info?> de</strong>
          </td>
          <td> 
            <? db_input('cod_ini',8,'',true,'text',1," onchange='js_copiacampo();'","")  ?>
            <strong> à </strong> 
            <? db_input('cod_fim',8,'',true,'text',1,"","")  ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <?
    }else if ($filtrar=='S'){
  ?>
  <tr>
    <td colspan=2 >
    <?
    $aux->cabecalho = "<strong>$info</strong>";
    $aux->codigo = "$cod"; //chave de retorno da func
    $aux->descr  = "$descr";   //chave de retorno
    $aux->nomeobjeto = 'lista';
    $aux->funcao_js = 'js_mostra';
    $aux->funcao_js_hide = 'js_mostra1';
    $aux->sql_exec  = "";
    $aux->func_arquivo = "$func";  //func a executar
    $aux->nomeiframe = "db_iframe_lista";
    $aux->localjan = "";
    $aux->onclick = "";
    $aux->db_opcao = 2;
    $aux->tipo = 2;
    $aux->top = 0;
    $aux->linhas = 5;
    $aux->vwhidth = 200;
    $aux->funcao_gera_formulario();
    ?>
    </td>
  </tr>
  <?
  }
  }
  ?>
<!--     
  <tr>
    <td><strong>Tipo de emissão:</strong>
    </td>
    <td>
    <?
    $arr1=array("1"=>"Laser","2"=>"Matricial");
    db_select("tipoimpress",$arr1,true,2); 
    ?>
    </td>
  </tr>
-->														       
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
              db_input('mensagem1',105,0,true,'text',1,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right">
	      <b>Linha 2:</b>
            </td>
            <td> 
              <?
              db_input('mensagem2',105,0,true,'text',1,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right">
	      <b>Linha 3:</b>
            </td>
            <td> 
              <?
              db_input('mensagem3',105,0,true,'text',1,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right">
	      <b>Linha 4:</b>
            </td>
            <td> 
              <?
              db_input('mensagem4',105,0,true,'text',1,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right">
	      <b>Linha 5:</b>
            </td>
            <td> 
              <?
              db_input('mensagem5',105,0,true,'text',1,"")
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
  for(x=0;x<document.form1.lista.length;x++){
    document.form1.lista.options[x].selected = true;
  }
  return true;
}
function js_tipofolha(){
  if(document.form1.folha.value == "complementar" || document.form1.r48_semest){
    document.form1.submit();
  }
}
function js_anomes(){
  if(document.form1.folha.value == "complementar"){
    document.form1.submit();
  }
}
function js_copiacampo(){
  if(document.form1.cod_fim.value== ""){
    document.form1.cod_fim.value = document.form1.cod_ini.value;
  }
  document.form1.cod_fim.focus();
}
function js_emite(){
  js_controlarodape(true);
  obj=document.form1;
  vir="";
  dados="";
  query="";
  if (document.form1.lista){
    for(x=0;x<document.form1.lista.length;x++){
      dados+=vir+document.form1.lista.options[x].value;
      vir=",";
    }
  }
  if (document.form1.cod_ini){
    if (document.form1.cod_fim.value==""){
      document.form1.cod_fim.value=document.form1.cod_ini.value;    
    }
    query='&codini='+document.form1.cod_ini.value+'&codfim='+document.form1.cod_fim.value;      
  }
  if (dados!=""){
    query+='&dados='+dados;
  }
  
  if(document.form1.r48_semest){
    query+= "&semest="+document.form1.r48_semest.value;
  }
  query+="&local="+document.form1.rh56_localtrab.value;
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_geracontra',
  'pes2_alecontramatricial002.php?opcao='+document.form1.folha.value+
        '&ano='+document.form1.DBtxt23.value+'&mes='+document.form1.DBtxt25.value+
	'&filtro='+document.form1.filtro.value+'&msg='+document.form1.mensagem1.value+query,'Gerando Arquivo',true);
}
function js_detectaarquivo(arquivo,erro,mensagem){
  js_controlarodape(false);
  if(erro == true){
    alert(mensagem);
  }else{
    js_arquivo_abrir(arquivo);
  }
}
function js_controlarodape(mostra){
  if(mostra == true){
    document.form1.rodape.value = (window.CurrentWindow || parent.CurrentWindow).bstatus.document.getElementById('st').innerHTML;
    (window.CurrentWindow || parent.CurrentWindow).bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;<blink><strong><font color="red">GERANDO ARQUIVO</font></strong></blink>' ;
  }else{
    (window.CurrentWindow || parent.CurrentWindow).bstatus.document.getElementById('st').innerHTML = document.form1.rodape.value;
  }
}
</script>
<?
if(isset($emite2)){
  $sql = "select * from db_config where codigo = ".db_getsession("DB_instit");
  $result = db_query($sql);
  db_fieldsmemory($result,0);

  $xtipo = "'x'";
  $qualarquivo = '';
  if ( $folha == 'salario' ){
    $sigla   = 'r14';
    $qualarquivo = 'Salário';
  }elseif ( $folha == 'ferias' ){
    $sigla   = 'r31';
    $arquivo = 'gerffer';
    $qualarquivo = 'Férias';
    $xtipo   = ' r31_tpp ';
  }elseif ( $folha == 'rescisao' ){
    $sigla   = 'r20';
    $arquivo = 'gerfres';
    $qualarquivo = 'Rescisão';
    $xtipo   = ' r31_tpp ';
  }elseif ($folha == 'adiantamento'){
    $sigla   = 'r22';
    $qualarquivo = 'Adiantamento';
  }elseif ($folha == '13salario'){
    $sigla   = 'r35';
    $qualarquivo = '13o. Salário';
  }elseif ($folha == 'complementar'){
    $sigla   = 'r48';
    $qualarquivo = 'Complementar';
  }elseif ($folha == 'fixo'){
    $sigla   = 'r53';
    $qualarquivo = 'Fixo';
  }elseif ($folha == 'previden'){
    $sigla   = 'r60';
    $qualarquivo = 'Ajuste da Previdência';
  }elseif ($folha == 'irf'){
    $sigla   = 'r61';
    $qualarquivo = 'Ajuste do IRRF';
  }

  $txt_where="";
  $localtrabprinc = " rh56_princ = 't'";
  if (isset($filtro)&&$filtro!='N'){
    if ($filtro=='M'){
      $campo="rh01_regist";
    }else if ($filtro=='L'){
      $campo="r70_codigo";
    }else if($filtro == "T"){
      $campo = "rh56_localtrab";
      $localtrabprinc = "";
    }
    if (isset($lista)&&count($lista)>0){
      $dados = "";
      $vir = "";
      for($i=0; $i<count($lista); $i++){
	$dados .= $vir.$lista[$i];
	$vir = ",";
      }
      $txt_where=" $campo in ($dados) ";
    }elseif (isset($cod_ini)){
      $txt_where=" $campo between $cod_ini and $cod_fim ";
    }
  }
  if($localtrabprinc != "" && $txt_where != ""){
    $localtrabprinc = " and ".$localtrabprinc;
  }


  $wheresemest = "";
  if(isset($r48_semest) && trim($r48_semest) != 0){
    $wheresemest = " r48_semest = ".$r48_semest;
  }

  $clgeradorsql = new cl_gera_sql_folha;
  $clgeradorsql->usar_cgm = true;
  $clgeradorsql->usar_fun = true;
  $clgeradorsql->usar_lot = true;
  $clgeradorsql->usar_car = true;
  $clgeradorsql->usar_tra = true;
  $sqlDentro = $clgeradorsql->gerador_sql(null,
                                          $ano,
					  $mes,
					  null,
					  null,
					  "
					   distinct
					   rh55_estrut,rh55_descr,rh55_codigo,
					   rhpessoal.*,rh37_descr,r70_descr,
					   cgm.*,
					   substr(r70_estrut,1,7) as estrut,
					   substr(db_fxxx(rh01_regist,$ano,$mes,".db_getsession("DB_instit")."),111,11) as f010,
					   substr(db_fxxx(rh01_regist,$ano,$mes,".db_getsession("DB_instit")."),221,8) as padrao
					  ",
					  "rh55_estrut,z01_nome",
					  $txt_where.$localtrabprinc
					 );
  // die($sqlDentro);
  $res = db_query($sqlDentro);
  $num = pg_numrows($res);
  if($num == 0){
    $erro_msg = "Não existe Cálculo no período de $mes / $ano";
    $sqlerro = true;
  }else{
    $sqlerro = false;
    $competencia = $ano."/".$mes;
    $arquivoimprime = "/tmp/alecontra".$mes."-".$ano.".txt";
    $db_layouttxt = new db_layouttxt(4,$arquivoimprime);

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
    $clgeradorsql->usar_tra = false;
    $contadorContra         = 0;
    $contapaginas           = 0;
    $quantidade             = 0;
    for($i=0;$i<$num;$i++){
      db_fieldsmemory($res,$i);
      db_atutermometro($i,$num,"termometro");
      $z01_cgccpf = db_formatar($z01_cgccpf,"cpf");
      $sql = $clgeradorsql->gerador_sql($sigla,
                                        $ano,
                                        $mes,
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
      $res_env = db_query($sql);
      if(pg_num_rows($res_env) > 0){
        $quantidade ++;
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
          $valorrubrica   = trim(db_formatar($valorrubrica,"f"));
          $quantrubrica   = trim(db_formatar($quantrubrica,"f"));
          $conprev = trim(db_formatar($somaconprev,"f"));
          $baseirrf = trim(db_formatar($somabaseirrf,"f"));
          $fgts = trim(db_formatar($somafgts,"f"));
          $dependentesirfq = trim(db_formatar($somadependentesirfq,"f"));
          $dependentesirfv = trim(db_formatar($somadependentesirfv,"f"));
          $faixairrf = trim(db_formatar($somafaixairrf,"f"));
          $baseprevidencia = trim(db_formatar($somabaseprevidencia,"f"));
          $baseliquida = trim(db_formatar($somabaseliquida,"f"));
          if($provdesc != "B"){
            $linhastesta ++;
          }

	}

        for($x=0,$linhas=0; $x<pg_num_rows($res_env); $x++){
          db_fieldsmemory($res_env, $x);

          if(($x == 0) || (($linhas % ($db_layouttxt->_quantLinhasLay * $multiplic)) == 0 && $linhastesta > $db_layouttxt->_quantLinhasLay)){
            if($x != 0){
              $multiplic ++;
              $totalproventos = "";
              $totaldescontos = "";
              $totalprovdesc  = "";
              db_setaPropriedadesLayoutTxt($db_layouttxt,RODAPEARQUIVO);
	      $contapaginas ++;
            }
            db_setaPropriedadesLayoutTxt($db_layouttxt,CABECALHOARQUIVO);
          }
       
          if($provdesc != "B"){
            $linhas ++;
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

    $arr_posicoes = array();
    $posicaocorrente = 0;
    $passar  = 0;
    $contador = 0;

    $ponteiro = fopen("$arquivoimprime","r");
    while(!feof($ponteiro)){
      $poslinha = fgets($ponteiro,4096);
      if($passar == 0){
        $passar = 1;
        continue;
      }

      $poslinha = str_replace("\r","",$poslinha);
      $poslinha = str_replace("\n","",$poslinha);

      $posicaocorrente ++;
      if($posicaocorrente == 45){
        $posicaocorrente = 1;
        $contador ++; 
        if($contador == $contapaginas){
          break;
        }
      }

      $arr_posicoes[$contador][$posicaocorrente] = "$poslinha";

    }


    $nome_arquivo = "/tmp/Contra_$ano-$mes.txt";
    $impressao_de_arquivo1 = fopen($nome_arquivo,"w");
    fputs($impressao_de_arquivo1,chr(15)."\r\n");

    for($i=0; $i<$contapaginas; $i++){
      for($ix=1; $ix<45; $ix++){
        $variavelimprime = $arr_posicoes[$i][$ix];
        $variavelimprime = db_formatar($variavelimprime,"s"," ",105,"d");
        if(isset($arr_posicoes[($i + 1)][$ix])){
          $helpvariavelimprime = $arr_posicoes[($i + 1)][$ix];
          $helpvariavelimprime = db_formatar($helpvariavelimprime,"s"," ",105,"d");
          $variavelimprime .= $helpvariavelimprime;
        }
        $variavelimprime.= "\r\n";
        fputs($impressao_de_arquivo1, $variavelimprime);
      }
      $i ++;
    }
    fputs($impressao_de_arquivo1,chr(18));
    fclose($impressao_de_arquivo1);

  }
  $qry = "?impressao_de_arquivo=$nome_arquivo&contadorcontra=$contadorContra";
  if($sqlerro == true){
    db_msgbox($erro_msg);
    $qry = "";
  }
  echo "
        <script>
          location.href = 'pes2_alecontramatricial001.php".$qry."';
        </script>
       ";
}
if(isset($impressao_de_arquivo)){
  echo "<script>alert('Número de contra cheques gerados: $contadorcontra')</script>";
  echo "<script>js_arquivo_abrir('$impressao_de_arquivo');</script>";
}
?>