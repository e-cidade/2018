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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_devolucaoacervo_classe.php");
require_once ("classes/db_emprestimo_classe.php");
require_once ("classes/db_emprestimoacervo_classe.php");
require_once ("classes/db_bib_parametros_classe.php");
require_once ("classes/db_reserva_classe.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$cldevolucaoacervo  = new cl_devolucaoacervo;
$clemprestimo       = new cl_emprestimo;
$clemprestimoacervo = new cl_emprestimoacervo;
$clbib_parametros   = new cl_bib_parametros;
$clreserva          = new cl_reserva;
$db_opcao           = 1;
$db_botao           = true;
$depto              = db_getsession("DB_coddepto");

$sql                = "SELECT bi17_codigo,bi17_nome FROM biblioteca WHERE bi17_coddepto = $depto";
$result             = pg_query($sql);;
$linhas             = pg_num_rows($result);

if ($linhas != 0) {
	
  db_fieldsmemory($result,0);
  $sSqlBibParametros = $clbib_parametros->sql_query("", "bi26_leitorbarra", "", " bi26_biblioteca = $bi17_codigo");
  $result1           = $clbib_parametros->sql_record($sSqlBibParametros);
  
  if ($clbib_parametros->numrows > 0) {
    db_fieldsmemory($result1,0);
  } else {
    $bi26_leitorbarra = "N";
  }
}

if (isset($submitrenovar)) {
	
  db_inicio_transacao();
  //fecha emprestimo anterior
  $array_devolve = explode("|",$renovaemprestimo);
  for ($x = 0; $x < count($array_devolve); $x++) {
  	 
    $array_x = explode(";",$array_devolve[$x]);
    $cldevolucaoacervo->bi21_emprestimoacervo = $array_x[0];
    $cldevolucaoacervo->bi21_entrega          = date("Y-m-d",db_getsession("DB_datausu"));
    $cldevolucaoacervo->bi21_usuario          = db_getsession("DB_id_usuario");
    $cldevolucaoacervo->incluir($array_x[0]);
    if ($cldevolucaoacervo->erro_status == "0") {
      $cldevolucaoacervo->erro_msg;
    }
  }
  
  //grava novo emprestimo
  $clemprestimo->bi18_retirada  = $bi18_retirada_ano."-".$bi18_retirada_mes."-".$bi18_retirada_dia;
  $clemprestimo->bi18_devolucao = $bi18_devolucao_ano."-".$bi18_devolucao_mes."-".$bi18_devolucao_dia;
  $clemprestimo->bi18_carteira  = $leitor;
  $clemprestimo->bi18_usuario   = db_getsession("DB_id_usuario");
  $clemprestimo->incluir(null);
  $bi19_emprestimo = $clemprestimo->bi18_codigo;
  
  //grava novo emprestimoacervo
  $array_emprestimo = explode("|", $renovaemprestimo);
  
  for ($i = 0; $i < count($array_emprestimo); $i++) {
  	
    $cod_exemplar = explode(";",$array_emprestimo[$i]);
    
    $clemprestimoacervo->emite           = "false";
    $clemprestimoacervo->bi19_emprestimo = $bi19_emprestimo;
    $clemprestimoacervo->bi19_exemplar   = $cod_exemplar[1];
    $clemprestimoacervo->incluir(null);
    if ($clemprestimoacervo->erro_status == "0") {
      $clemprestimoacervo->erro_msg;
    }
  }
  
  db_fim_transacao();
  ?>
  <script>
   alert("Renovação efetuada com sucesso!");
   parent.db_iframe_renovacao.hide();
   parent.location.href = "bib1_devolucao001.php?bi18_carteira=<?=$leitor?>&ov02_nome=<?=$nome?>";
  </script>
  <?
  exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Renovação de Empréstimo</b></legend>
   <?
   $cod_emprest = explode("|",trim($renovaemprestimo));
   $cod_exemp   = "";
   $sep_exemp   = "";
   $codacervos  = '';
   for ($x = 0; $x < count($cod_emprest); $x++) {
   	
     $linha       = explode(";",$cod_emprest[$x]);
     $cod_exemp  .= $sep_exemp.$linha[0];    
     $codacervos .= $sep_exemp.$linha[3];
     $sep_exemp   = ",";
         
   }
   
   $campos  = " ov02_nome as nome, ";
   $campos .= " bi23_codigo, ";
   $campos .= " bi23_codbarras, ";
   $campos .= " bi06_titulo, ";
   $campos .= " bi18_retirada, ";
   $campos .= " bi18_devolucao, ";
   $campos .= " bi07_tempo as tempo, ";
   $campos .= " bi18_carteira as leitor ";
   
   $sSqlDevolucaoAcervo = "select $campos 
                             from emprestimoacervo
                                  inner join emprestimo       on bi18_codigo               = bi19_emprestimo
                                  inner join carteira         on bi16_codigo               = bi18_carteira
                                  inner join leitorcategoria  on bi07_codigo               = bi16_leitorcategoria
                                  inner join leitor           on bi10_codigo               = bi16_leitor
                                  left  join leitorcidadao    on leitorcidadao.bi28_leitor = leitor.bi10_codigo                   
                                  left  join cidadao          on cidadao.ov02_sequencial   = leitorcidadao.bi28_cidadao_sequencial
                                                             and cidadao.ov02_seq          = leitorcidadao.bi28_cidadao_seq
                                  inner join exemplar         on bi23_codigo               = bi19_exemplar
                                  inner join acervo           on bi06_seq                  = bi23_acervo
                            where bi19_codigo in ($cod_exemp)
                              and bi07_biblioteca = $bi17_codigo 
                              and not exists(select *
                                               from reserva
                                              where bi14_acervo in ($codacervos) AND bi14_retirada is null
                                            )";
   $result = $cldevolucaoacervo->sql_record($sSqlDevolucaoAcervo);
   echo pg_errormessage();
   
   $sSqlReserva = $clreserva->sql_query("", 
                                        "bi14_acervo,bi06_titulo", 
                                        "", 
                                        " bi14_acervo in ($codacervos) AND bi14_retirada is null"
                                       );
   $result1 = $clreserva->sql_record($sSqlReserva);
   if ($clreserva->numrows > 0) {
   
     $codacervos = "";
     $cod        = "";
     $sep        = "";
     $sep1       = "";
     $sTitulos   = '';
     for ($x = 0; $x < $clreserva->numrows; $x++) {
   
       db_fieldsmemory($result1,$x);
       $sTitulos   .= $sep1.'"'.$bi06_titulo.'"';
       $sep1        = ', ';
       
     }
   
     die("<center><b>O(s) exemplar(es) $sTitulos possui(em) reserva. Não é possível efetuar a renovação.</b></center>");
   
   } 
   ?>
   <table border="0" width="90%" align="center">
    <tr>
     <td colspan="2">
      <fieldset width="100%"><legend><b>Empréstimos selecionados para renovação:</b></legend>
       <table border="1" width="100%">
        <tr>
         <td><b>Leitor</b></td>
         <td><b>Exemplar</b></td>
         <td><b>Cod. Barras</b></td>
         <td><b>Título</b></td>
         <td><b>Emprestado</b></td>
         <td><b>Devolver até</b></td>
        </tr>
        <?
        for ($x = 0; $x < $cldevolucaoacervo->numrows; $x++) {

          db_fieldsmemory($result, $x);
          ?>
          <tr>
           <td><?=trim($nome)?></td>
           <td><?=$bi23_codigo?></td>
           <td><?=$bi23_codbarras?></td>
           <td><?=$bi06_titulo?></td>
           <td><?=db_formatar($bi18_retirada,'d')?></td>
           <td><?=db_formatar($bi18_devolucao,'d')?></td>
          </tr>
          <?
        }
        ?>
       </table>
       <?
       $bi18_retirada_dia  = date("d",db_getsession("DB_datausu"));
       $bi18_retirada_mes  = date("m",db_getsession("DB_datausu"));
       $bi18_retirada_ano  = date("Y",db_getsession("DB_datausu"));
       $bi18_devolucao_dia = "";
       $bi18_devolucao_mes = "";
       $bi18_devolucao_ano = "";
       ?>
       <br>
       <form name="form1" method="post" action="">
       <table border="0">
        <tr>
         <td>
          <b>Tempo de Empréstimo (dias):</b>
         </td>
         <td>
          <?db_input('tempo', 10, "", true, 'text', 3, "")?>
         </td>
        </tr>
        <tr>
         <td>
          <b>Data de Retirada:</b>
         </td>
         <td>
          <?db_inputdata('bi18_retirada', @$bi18_retirada_dia, @$bi18_retirada_mes, @$bi18_retirada_ano, true, 'text', 3, "")?>
         </td>
        </tr>
        <tr>
         <td>
          <b>Data de Devolução:</b>
         </td>
         <td>
          <?db_inputdata('bi18_devolucao', 
                         @$bi18_devolucao_dia, 
                         @$bi18_devolucao_mes, 
                         @$bi18_devolucao_ano,
                         true,
                         'text',
                         1,
                         " onchange=\"js_diasemana();\"","",""," parent.js_diasemana();")?>
          <?db_input('diasemana', 10, "", true, 'text', 3, "")?>
         </td>
        </tr>
       </table>
       <iframe src="" name="iframe_verificadata" id="iframe_verificadata" width="0" height="0" frameborder="0"></iframe>
       <input type="submit" name="submitrenovar" value="Confirmar Renovação" onclick="return js_valida();">
       <input type="hidden" name="renovaemprestimo" value="<?=$renovaemprestimo?>">
       <input type="hidden" name="leitor" value="<?=@$leitor?>">
       <input type="hidden" name="nome" value="<?=trim(@$nome)?>">
       </form>
      </fieldset>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1", "bi18_devolucao", true, 1, "bi18_devolucao", true);
function somadata(dias) {
  
  var dia = "<?=date('d')?>";
  var mes = "<?=date('m')?>";
  var ano = "<?=date('Y')?>";
  var i   = dias;
  
  for(i = 0; i < dias; i++) {
    
    if (mes == "01" || mes == "03" || mes == "05" || mes == "07" || mes == "08" || mes == "10" || mes == "12") {
      
      if (mes == "12" && dia == "31") {
        
        mes = "01";
        ano++;
        dia = "00";
      }
      
      if (dia == "31" && mes != "12") {
        
        mes++;
        dia = "00";
      }
    }
    
    if (mes == "04" || mes == "06" || mes == "09" || mes == "11") {
      
      if (dia == "30") { 
        
        dia =  "00";
        mes++;
      }
    }
    
    if (mes == "02") {
      
      if (ano % 4 == 0) {
        
        if (dia == "29") {
          
          dia = "00";
          mes++;
        }
      } else {
        
        if (dia == "28") {
          
          dia = "00";
          mes++;
        }
      }
    }
    dia++;
  }
  if (dia == 1) {
	  dia = "01";
  }
  if (dia == 2) {
	  dia = "02";
  }
  if (dia == 3) {
	  dia = "03";
  }
  if (dia == 4) {
	  dia = "04"; 
  }
  if (dia == 5) { 
	  dia = "05"; 
  }
  if (dia == 6) {
	  dia = "06";
  }
  if (dia == 7) {
	  dia = "07";
  }
  if (dia == 8) {
	  dia = "08";
  }
  if (dia == 9) {
	  dia = "09";
  }
  if (mes == 1) {
	  mes = "01";
  }
  if (mes == 2) {
	  mes = "02";
  }
  if (mes == 3) {
	  mes = "03";
  }
  if (mes == 4) {
	  mes = "04";
  }
  if (mes == 5) {
	  mes = "05";
  }
  if (mes == 6) {
	  mes = "06";
  }
  if (mes == 7) {
	  mes = "07";
  }
  if (mes == 8) {
	  mes = "08";
  }
  if (mes == 9) {
	  mes = "09";
  }
  iframe_verificadata.location = "bib1_emprestimo002.php?ano="+ano+"&mes="+mes+"&dia="+dia;
}

function js_diasemana() {
  
  if (document.form1.bi18_devolucao_ano.value != "") {
    
    d1 = document.form1.bi18_devolucao_dia.value;
    m1 = document.form1.bi18_devolucao_mes.value;
    a1 = document.form1.bi18_devolucao_ano.value;
    if (d1 == "" || m1 == "" || a1 == "") {
      alert("Preencha todos os campos da data!");
    } else {
      
      dev = parseInt(a1+m1+d1);
      ret = parseInt(document.form1.bi18_retirada_ano.value+document.form1.bi18_retirada_mes.value+document.form1.bi18_retirada_dia.value);
      if (dev < ret) {
        
        alert("Data de Devolução deve ser maior ou igual a Data de Retirada!");
        document.form1.diasemana.value          = "";
        document.form1.bi18_devolucao.value     = "";
        document.form1.bi18_devolucao_dia.value = "";
        document.form1.bi18_devolucao_mes.value = "";
        document.form1.bi18_devolucao_ano.value = "";
      } else {
        iframe_verificadata.location = "bib1_emprestimo002.php?ano="+a1+"&mes="+m1+"&dia="+d1;
      }
    }
  } else {
    document.form1.diasemana.value = "";
  }
}

function js_valida() {
  
  if (document.form1.bi18_devolucao.value == "") {
    
    alert("Informe a data de devolução!");
    document.form1.bi18_devolucao.focus();
    return false;
  }
  return true;
}
somadata(document.form1.tempo.value);
</script>