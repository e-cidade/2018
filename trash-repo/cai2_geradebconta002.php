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

//require("libs/db_stdlib.php");

require("fpdf151/scpdf.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");

include("classes/db_debcontapedido_classe.php");
include("classes/db_debcontaarquivo_classe.php");
include("classes/db_debcontaarquivotipo_classe.php");
include("classes/db_db_config_classe.php");

$cldebcontapedido = new cl_debcontapedido;
$cldebcontaarquivo = new cl_debcontaarquivo;
$cldebcontaarquivotipo = new cl_debcontaarquivotipo;
$cldb_config = new cl_db_config;

$clrotulo = new rotulocampo;
$clrotulo->label("d72_data");

db_postmemory($HTTP_POST_VARS);
//echo "formatoArq = $formatoArq .... linhasBranco= $linhasBranco"; exit;
$instit = db_getsession("DB_instit");

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.x01_matric.focus();" >

<br>
<br>

<?

$jaexiste = 0;

/*$sqlverifica = "
  select d72_nome, 
         d72_conteudo 
    from debcontaarquivo
         inner join debcontaarquivoreg on d72_codigo = d73_codigo
   where d72_arretipo = $tipodebito 
     and d72_numpar = $numpar 
     and d72_banco = $banco";*/

//$resultverifica = pg_exec($sqlverifica);
//if (pg_numrows($resultverifica) > 0) {
if (1==0) {
  $d72_conteudo = pg_result($resultverifica, 0, "d72_conteudo");
  
  die("x: $d72_conteudo\nx");
  exit;
  
  db_fieldsmemory($resultverifica, 0);
  
  $processados = pg_numrows($resultverifica);
  
  $jaexiste = 1;
  
  $sqlerro=false;
  db_inicio_transacao();
  
  $arqgerado = $d72_nome;
  $fd = fopen($arqgerado,'w+');
  
  fwrite($fd, $d72_conteudo);
  fclose($fd);
  
} else {
  
  //pg_exec("begin");

  db_criatermometro('termometro', 'Concluido...', 'blue', 1);
  flush();

  $sqlarretipo = "select k00_tipoagrup from arretipo where k00_tipo = $tipodebito and k00_instit = ".db_getsession("DB_instit");

  $resultarretipo = pg_query($sqlarretipo);

  if(pg_numrows($resultarretipo) > 0) {
    db_fieldsmemory($resultarretipo, 0);
  } else {
    $k00_tipoagrup = 0;
  }

  $_anousu = db_getsession("DB_anousu");

  $sqlprinc = "
    select d68_codigo,
           arrecad.k00_dtvenc,
           d68_matric,
           d63_agencia,
           d63_conta,
           d63_idempresa,
           arrecad.k00_numpre,
           arrecad.k00_numpar,
           arrecad.k00_tipo,
           sum(arrecad.k00_valor) as k00_valor
      from debcontapedido
           inner join debcontapedidotipo   on d66_codigo            = d63_codigo
                                          and d66_arretipo          = $tipodebito
           inner join debcontapedidomatric on d63_codigo            = d68_codigo
           inner join arrematric           on k00_matric            = d68_matric
           inner join arrecad              on arrecad.k00_numpre    = arrematric.k00_numpre
                                          and extract(month from arrecad.k00_dtvenc) = $mesvenc
                                          and extract(year  from arrecad.k00_dtvenc) = $anovenc
                                          and arrecad.k00_tipo      = $tipodebito
           inner join arreinstit           on arreinstit.k00_numpre = arrecad.k00_numpre
                                          and arreinstit.k00_instit = $instit
     where d63_status = 2  
       and d63_banco  = $banco
       and d63_instit = $instit
  group by d68_codigo,
           k00_dtvenc,
           d68_matric,
           d63_agencia,
           d63_conta,
           d63_idempresa,
           arrecad.k00_numpre,
           arrecad.k00_tipo,
           arrecad.k00_numpar ";
 /* 
  $sqlprinc = "select d68_codigo,
    k00_dtvenc,
    d68_matric,
    d63_agencia,
    d63_conta,
    d63_idempresa,
    arrecad.k00_numpre,
    k00_numpar,
    sum(k00_valor) as k00_valor
    from debcontapedido
    inner join debcontapedidomatric on d63_codigo = d68_codigo
    inner join arrematric on k00_matric = d68_matric
    inner join arrecad on arrecad.k00_numpre = arrematric.k00_numpre
    where 	k00_tipo = $tipodebito and
    k00_numpar = $numpar and
    d63_status = 2 and
    d63_banco = $banco
    group by 	d68_codigo,
    k00_dtvenc,
    d68_matric,
    d63_agencia,
    d63_conta,
    d63_idempresa,
    arrecad.k00_numpre,
    k00_numpar
";
*/
  if($k00_tipoagrup == 2) {

    /*$sqlmesvenc = "
      select extract(month from q82_venc)::integer as mesvenc,
             extract(year  from q82_venc)::integer as anovenc
        from cadvencdesc
             inner join cadvenc on q82_codigo = q92_codigo
       where q92_tipo = $tipodebito
         and q82_parc = $numpar ";
    $resmesvenc = pg_query($sqlmesvenc);

    if(pg_num_rows($resmesvenc)>0) {
      db_fieldsmemory($resmesvenc,0);
    } else {
      $mesvenc = $numpar+1;
      $anovenc = $_anousu;
    }*/
    
    $sqlprinc .= "
      union all
      select d68_codigo,
             arrecad.k00_dtvenc,
             d68_matric,
             d63_agencia,
             d63_conta,
             d63_idempresa,
             arrecad.k00_numpre,
             arrecad.k00_numpar,
             arrecad.k00_tipo,
             sum(arrecad.k00_valor) as k00_valor
        from debcontapedido
             inner join debcontapedidotipo   on d66_codigo            = d63_codigo
                                            and d66_arretipo          = $tipodebito
             inner join debcontapedidomatric on d63_codigo            = d68_codigo
             inner join arrematric           on k00_matric            = d68_matric
             inner join arrecad              on arrecad.k00_numpre    = arrematric.k00_numpre
                                            and extract(month from arrecad.k00_dtvenc) = $mesvenc
                                            and extract(year  from arrecad.k00_dtvenc) = $anovenc
                                            and arrecad.k00_tipo   <> $tipodebito
             inner join arreinstit           on arreinstit.k00_numpre = arrecad.k00_numpre
                                            and arreinstit.k00_instit = $instit
             left  join arrenaoagrupa        on arrenaoagrupa.k00_numpre = arrecad.k00_numpre
       where d63_status = 2  
         and d63_banco  = $banco
         and d63_instit = $instit
         and arrenaoagrupa.k00_numpre is null
    group by d68_codigo,
             arrecad.k00_dtvenc,
             d68_matric,
             d63_agencia,
             d63_conta,
             d63_idempresa,
             arrecad.k00_numpre,
             arrecad.k00_numpar,
             arrecad.k00_tipo";
  }

  $sqlprinc = "select * from ( $sqlprinc ) as x order by d68_matric, k00_numpar";

  //die($sqlprinc);
  $resultprinc = pg_exec($sqlprinc) or die($sqlprinc);
  //db_criatabela($resultprinc);
  //die();
  if ($resultprinc == false or pg_numrows($resultprinc) == 0) {
    $erro = true;
    $descricao_erro = "Não existem registros a processar!";
  } else {
    
    $sqlini = "begin;";
    $resultini = pg_exec($sqlini) or die($sqlini);
    
    $resultmunic = pg_exec("select nomeinst, nomedebconta from db_config where codigo = " . db_getsession("DB_instit"));
    $nomeinst = pg_result($resultmunic,0);
    $munic    = pg_result($resultmunic,1);
    
    $data =  "$d72_data_ano-$d72_data_mes-$d72_data_dia";
    
    $sqlparam = "	
      select * 
        from debcontaparam
             inner join db_bancos on to_number(db90_codban, '999') = d62_banco
       where d62_instituicao = " . db_getsession("DB_instit") . "
         and d62_banco = $banco";

    $resultparam = pg_exec($sqlparam) or die($sqlparam);

    if (pg_numrows($resultparam) > 0) {
      db_fieldsmemory($resultparam, 0);
      
      $nextdebcontaarquivo = "select nextval('debcontaarquivo_d72_codigo_seq') as debcontaarquivo";
      $resultnextdebcontaarquivo = pg_exec($nextdebcontaarquivo) or die($nextdebcontaarquivo);
      db_fieldsmemory($resultnextdebcontaarquivo,0);
      
      $arqgerado = "tmp/debconta_" . str_pad($banco, 3, "0", STR_PAD_LEFT) . "_nsa_" . str_pad($d62_ultimonsa, 10, "0", STR_PAD_LEFT) . "_" . date("Y-m-d_His",db_getsession("DB_datausu")) . ".txt";

      $fd = fopen($arqgerado,'w+');
      $linhas = "";
      
      $updateparam = "
        update debcontaparam 
           set d62_ultimonsa = $d62_ultimonsa + 1
         where d62_instituicao = " . db_getsession("DB_instit") . "
           and d62_banco = $banco";
      
      $resultupdateparam = pg_exec($updateparam) or die($updateparam);
      
      $insertarquivo = 	"
        insert into debcontaarquivo (
          d72_codigo,
          d72_nsa,
          d72_tipo,
          d72_data,
          d72_hora,
          d72_usuario,
          d72_nome,
          d72_numpar,
          d72_arretipo,
          d72_banco,
          d72_instit
        ) values (
          $debcontaarquivo,
          $d62_ultimonsa,
          1,
          '" . date("Y-m-d", db_getsession("DB_datausu")) . "','" .
          db_hora() . "'," .
          db_getsession("DB_id_usuario") . ",
          '$arqgerado',
          $numpar,
          $tipodebito,
          $banco,
          ".db_getsession("DB_instit")."
        )";

      $resultarquivo = pg_exec($insertarquivo) or die($insertarquivo);

      
      $linhas .= "A";
      $linhas .= "1";
      $linhas .= str_pad(substr($d62_convenio,0,20), 20, " ", STR_PAD_RIGHT);
      $linhas .= str_pad(substr($nomeinst,0,20), 20, " ", STR_PAD_RIGHT);
      $linhas .= str_pad($banco, 3, "0", STR_PAD_LEFT);
      $linhas .= str_pad(substr($db90_descr,0,20), 20);
      $linhas .= date("Ymd",db_getsession("DB_datausu"));
      $linhas .= str_pad($d62_ultimonsa, 6, "0", STR_PAD_LEFT);
      $linhas .= "04";
      $linhas .= "DEBITO AUTOMATICO";
      $linhas .= str_repeat(" ",52);
	  if($formatoArq=="U"){
	  	$linhas .= "\n";
	  }else{
	  	$linhas .= "\r\n";
	  }
      
      
      $valortotal = 0;
      
      $numrowsprinc = pg_numrows($resultprinc);
      for ($i = 0; $i < $numrowsprinc; $i++) {
        db_fieldsmemory($resultprinc, $i);
        
        db_atutermometro($i, $numrowsprinc, 'termometro');

        // Verifica se existe DEBCONTAARQUIVOTIPO
        $sqldebcontaarquivotipo = $cldebcontaarquivotipo->sql_query_file(null, "*", null, "d79_codigo = $debcontaarquivo and d79_arretipo = $k00_tipo");
        //die($sqldebcontaarquivotipo);
        $cldebcontaarquivotipo->sql_record($sqldebcontaarquivotipo);
        if($cldebcontaarquivotipo->numrows==0){
          $cldebcontaarquivotipo->d79_sequencial = null;
          $cldebcontaarquivotipo->d79_codigo     = $debcontaarquivo;
          $cldebcontaarquivotipo->d79_arretipo   = $k00_tipo;
          $cldebcontaarquivotipo->incluir(null);
        }
        
        $tipomov = 0;
        
        $linhas .= "E";
        
        $linhas .= str_pad(trim($d63_idempresa), 25, " ", STR_PAD_RIGHT);
        
        $linhas .= str_pad(trim($d63_agencia), 4, "0", STR_PAD_LEFT);
        $linhas .= str_pad(trim($d63_conta),  14, " ", STR_PAD_RIGHT);
        
        if ($k00_dtvenc < $data) {
          $data_debito = $data;
        } else {
          $data_debito = $k00_dtvenc;
        }
        
        //die("x: $data  #  $data_debito");
        
        $linhas .= str_replace("-","",$data_debito);
        $linhas .= str_pad(trim(db_formatar($k00_valor, 'valsemform', '0', 15)), 15, "0", STR_PAD_LEFT);
        $linhas .= "03";
        
        // VERSAO - NUMPRE - NUMPAR - MATRIC - CODIGO_PEDIDO
        $linhas .= str_pad("001-" . str_pad($k00_numpre, 8, "0", STR_PAD_LEFT) . "-" . str_pad($k00_numpar, 3, "0", STR_PAD_LEFT) . "-" . str_pad($d68_matric, 10, "0", STR_PAD_LEFT) . "-" . str_pad($d68_codigo, 10, "0", STR_PAD_LEFT), 60, " ", STR_PAD_RIGHT);
        
        $linhas .= str_repeat(" ", 20);
        $linhas .= substr($tipomov,0,1);
        if($formatoArq=="U"){
	  	  $linhas .= "\n";
	    }else{
	  	  $linhas .= "\r\n";
	    }
		
        $valortotal += $k00_valor;
        
        $nextarquivoreg = "select nextval('debcontaarquivoreg_d73_sequencial_seq') as debcontaarquivoreg";
        $resultnextarquivoreg = pg_exec($nextarquivoreg) or die($nextarquivoreg);
        db_fieldsmemory($resultnextarquivoreg ,0);
        
        $insertarquivoreg = "insert into debcontaarquivoreg select $debcontaarquivoreg, $debcontaarquivo, '1'";
        $resultarquivoreg = pg_exec($insertarquivoreg) or die($insertarquivoreg);
        
        $insertarquivoregmov =  "
          insert into debcontaarquivoregmov
            select nextval('debcontaarquivoregmov_d75_sequencial_seq'),
                   $debcontaarquivoreg,
                   '$data_debito',
                   $k00_valor,
                   $k00_numpar";
        $resultarquivomov = pg_exec($insertarquivoregmov) or die($insertarquivoregmov);
        
        $insertarquivoregcad = "
          insert into debcontaarquivoregcad
            select nextval('debcontaarquivoregcad_d74_sequencial_seq'),
                   $debcontaarquivoreg,
                   $tipomov,
                   '$data'";
        $resultarquivoregcad = pg_exec($insertarquivoregcad) or die($insertarquivoregcad);
        
        $insertarquivoregped = 	"
          insert into debcontaarquivoregped
            select nextval('debcontaarquivoregped_d80_sequencial_seq'),
                   $debcontaarquivoreg,
                   $d68_codigo";
        $resultarquivoregped = pg_exec($insertarquivoregped) or die($insertarquivoregped);
        
      }
      
      $linhas .= "Z";
      $linhas .= str_pad(pg_numrows($resultprinc) + 2, 6, "0", STR_PAD_LEFT);
      $linhas .= str_pad(trim(db_formatar($valortotal, 'valsemform', '0', 17)), 17, "0", STR_PAD_LEFT);
      $linhas .= str_repeat(" ", 126);
     
	  
	   if($formatoArq=="U"){
	  	if($linhasBranco>0){
      	  $LB ="";
      	  for($l=0;$linhasBranco>$l;$l++){
      		$LB .= "\n";
      	  }
		  $linhas .= $LB;
        }
	  }else{
	  	if($linhasBranco>0){
      	  $LB ="";
      	  for($l=0;$linhasBranco>$l;$l++){
      		$LB .= "\r\n";
      	  }
		  $linhas .= $LB;
        }
	  }
	  
      
	  
	  
      fputs($fd,$linhas);
      fclose($fd);
      
      $sqlconteudo = "update debcontaarquivo set d72_conteudo = '$linhas' where d72_codigo = $debcontaarquivo";
      $resultconteudo = pg_exec($sqlconteudo) or die($sqlconteudo);
      
      $sqlfim = "commit;";
      $resultfim = pg_exec($sqlfim) or die($sqlfim);
      
      $processados = pg_numrows($resultprinc);
      
    }
    
  }
  
}

?>
<table style='border-collapse: collapse; border:1px solid #525252;' cellspacing=0 cellpadding=0>
<tr><td align=center>
<?

echo "<br><br><br>";

if ($jaexiste == 1) {
  echo "<br><strong><a style='color:black'>FOI ENCONTRADO UM ARQUIVO GERADO PARA ESTA ESPECIFICAÇÃO!</a></strong><br><br>";
}
echo "<br><strong><a style='color:black'>$processados registros processados</a></strong><br><br>";
echo "<br><strong><a style='color:black' href='$arqgerado'> Arquivo gerado em: $arqgerado\nPara salvar, clique com o botão direito e escolha a opção \"Salvar destino como\"</a></strong><br><br>";
 

?>
</td>
</tr>
</table>
</body>
</html>