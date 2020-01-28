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
  include("libs/db_sql.php");
  include("classes/db_arrecad_classe.php");
  include("classes/db_arrehist_classe.php");
  include("dbforms/db_funcoes.php");
  //parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  db_postmemory($HTTP_POST_VARS);
  db_postmemory($HTTP_GET_VARS);
  $clrotulo = new rotulocampo;
  $clarrehist = new cl_arrehist;
  $clarrecad = new cl_arrecad;
 
  if (isset($desconto)) {
  	
    if (!empty($DBtxt9)) {
      $record = debitos_numpre($k00_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"), 0, "", "", "");
      if (pg_numrows($record) != 0) {  
        $numpar = $k00_numpar;
        $receit = $k00_receit;
        $ttotal = 0;
        for ($i=0; $i<pg_numrows($record); $i++) {
          db_fieldsmemory($record,$i);
          if ($numpar!=0 && $numpar == $k00_numpar ) {
            if ($receit!=0 && $receit == $k00_receit) {
              $ttotal += $vlrcor;
            } else if ($receit==0) {
              $ttotal += $vlrcor;
            }
          } else if ($numpar==0) {
            if (($receit!=0) && ($receit == $k00_receit)) {
              $ttotal += $vlrcor;
            } else if ($receit==0) {
              $ttotal += $vlrcor;
            }
          }
        }
        $destot = 0;
        $vlrdes = 0;

				db_inicio_transacao();

        $erro = false;
        for ($i=0; $i<pg_numrows($record); $i++) {
          db_fieldsmemory($record,$i);
          $processa = false;
          if ($numpar!=0 && $numpar == $k00_numpar ) {
            if ($receit!=0 && $receit == $k00_receit) {
              $processa = true;
              $desconto = round($vlrcor * ( 100 / $ttotal ),2);
              $vlrdes = round($DBtxt9 * ($desconto/100),2);
              $destot = $destot + $vlrdes;
            } else if ($receit==0) {
              $processa = true;
              $desconto = round($vlrcor * ( 100 / $ttotal ),2);
              $vlrdes = round($DBtxt9 * ($desconto/100),2);
              $destot = $destot + $vlrdes;
            }
          } else if ($numpar==0) {
            if (($receit!=0) && ($receit == $k00_receit)) {
              $processa = true;
              $desconto = round($vlrcor * ( 100 / $ttotal ),2);
              $vlrdes = round($DBtxt9 * ($desconto/100),2);
              $destot = $destot + $vlrdes;
            } else if ($receit==0) {
              $processa = true;
              $desconto = round($vlrcor * ( 100 / $ttotal ),2);
              $vlrdes = round($DBtxt9 * ($desconto/100),2);
              $destot = $destot + $vlrdes;
            }
          }
          if ($processa == true) {
            $valorcompl = 0;
            if ($destot>$DBtxt9) {
              $valorcompl = $DBtxt9 - $destot;
            }
            $vlrdes += $valorcompl;
            if (round($vlrdes,4) == 0) {
              continue;
            }
						$clarrecad->k00_numcgm = $k00_numcgm ;
						$clarrecad->k00_dtoper = $k00_dtoper ;
						$clarrecad->k00_receit = $k00_receit ;
						$clarrecad->k00_hist   = 918         ;
						$clarrecad->k00_valor  = (float)$vlrdes*-1  ;
						$clarrecad->k00_dtvenc = $k00_dtvenc ;
						$clarrecad->k00_numpre = $k00_numpre ;
						$clarrecad->k00_numpar = $k00_numpar ;
						$clarrecad->k00_numtot = $k00_numtot ;
						$clarrecad->k00_numdig = $k00_numdig ;
						$clarrecad->k00_tipo   = $k00_tipo   ;
						$clarrecad->k00_tipojm = '0';
						$clarrecad->incluir();
            if ($clarrecad->erro_status == 0) {
              $erro = true;
              db_msgbox($clarrecad->erro_msg);
							break;
            }
          }
        }
        
        $clarrehist->k00_numpre     = $k00_numpre;
        $clarrehist->k00_numpar     = ($numpar!=0?$numpar:0);
        $clarrehist->k00_hist       = $k00_hist;
        $clarrehist->k00_dtoper     = date("Y-m-d",db_getsession("DB_datausu"));
        $clarrehist->k00_hora       = db_hora();
        $clarrehist->k00_id_usuario = db_getsession("DB_id_usuario");
        $clarrehist->k00_histtxt    = $k00_histtxt;
        $clarrehist->k00_limithist  = null;
        $clarrehist->incluir(null);
//				db_msgbox("fdasdfgasdghkfgasdgfgasdgfkagsdfakj");
        if ( $clarrehist->erro_status == 0 ) {
          $erro = true;
        }
		   	db_fim_transacao($erro);
        
      }

      if ($erro == false) {
        db_msgbox("Processamento concluído");
        db_redireciona("cai4_descnumpre001.php");
      } else {
        db_msgbox("Processamento nao efetuado");
      }

    }
  }


?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_validaValor() {

    
}

function js_calcula() {

  var perce = new Number(document.form1.DBtxt8.value);
  var perce = new Number(document.form1.DBtxt8.value);
  var valor = new Number(document.form1.k00_valor.value);

  if (perce >=100 ) {
    alert('Para cancelamento total de um debito, use a opção Cancelamento de débito \n na consulta geral financeira.');
    document.form1.DBtxt8.value = '';
    return false;
  }

  valor = valor * (perce/100);
  document.form1.DBtxt9.value = valor.toFixed(2) ;
  document.getElementById('executa_desconto').style.visibility = 'visible';
}

function js_calculavalor(){

  var valor = new Number(document.form1.DBtxt9.value);

  if (valor>document.form1.k00_valor.value) {
    alert('Valor maior que o débito.');
    document.form1.DBtxt9.value = "";
    return false;
  }
  var valor1 = new Number(document.form1.k00_valor.value);
  var valor  = new Number(document.form1.DBtxt9.value);
  if (valor1.toFixed(2) <= valor.toFixed(2)) {
    alert("Valor do desconto igual ou maior que o valor do debito, \n Para cancelamento total de um debito, use a opção Cancelamento de débito \n na consulta geral financeira.");
    document.form1.DBtxt9.value = "";
    return false;
  }
  perce =  (valor*100)/valor1;
  document.form1.DBtxt8.value = perce.toFixed(2) ;

  var nValorCalculado = new Number(document.form1.DBtxt9.value);
  var nValorHistorico = new Number(document.form1.k00_valor.value);
  if (nValorCalculado >= nValorHistorico) {
    alert('Valor calculado para o desconto igual ao valor histórico. \n Para cancelamento total de um debito, use a opção Cancelamento de débito \n na consulta geral financeira.');    
    document.form1.DBtxt9.value = "";
    return false;
  }

  document.getElementById('executa_desconto').style.visibility = 'visible';

}

function js_verifica()
{

  var nValorCalculado = new Number(document.form1.DBtxt9.value);
  var nValorHistorico = new Number(document.form1.k00_valor.value);
  if (nValorCalculado >= nValorHistorico) {
    alert('Valor calculado para o desconto igual ao valor histórico. \n Para cancelamento total de um debito, use a opção Cancelamento de débito \n na consulta geral financeira.');    
    document.form1.DBtxt9.value = "";
    return false;
  }

  if (document.form1.k00_histtxt.value=="") {
    alert('O histórico do débito deverá ser preenchido.');
    return false;
  }
  var valor = new Number(document.form1.DBtxt9.value);
  if (valor==0) {
    alert('Valor Zerado.');
    document.form1.DBtxt9.focus();
    return false;
  }
  return true;
  
}
function js_caljuros()
{
  
  var valor = new Number(document.form1.tvlrjuros.value);
  var valortot = new Number(document.form1.DBtxt9.value);
  if (document.form1.descontojuros.checked) {
    valor = valor + valortot;
    document.form1.DBtxt9.value = valor.toFixed(2);
  } else {
    valor =  valortot - valor ;
    document.form1.DBtxt9.value = valor.toFixed(2);
  }
  
}
function js_calmulta()
{
  
  var valor = new Number(document.form1.tvlrmulta.value);
  var valortot = new Number(document.form1.DBtxt9.value);
  if (document.form1.descontojuros.checked) {
    valor = valor + valortot;
    document.form1.DBtxt9.value = valor.toFixed(2);
  } else {
    valor =  valortot - valor ;
    document.form1.DBtxt9.value = valor.toFixed(2);
  }
  
  
}
</script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.k00_numpre.focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<tr>
<td width="360" height="18">&nbsp;
</td>
<td width="263">&nbsp;
</td>
<td width="25">&nbsp;
</td>
<td width="140">&nbsp;
</td>
</tr>
</table>
<?
if (isset($k00_numpre)) {
  ?>
  </center>
  <table width="790" border="0" cellspacing="0" cellpadding="0">
  <form name="form1" action="" method="post" onSubmit="return js_verifica()">
  <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
  <table width="686" height="27" border="0" cellpadding="0" cellspacing="0">
  <?
  if (!isset($k00_numpar)) {
    $numpar = 0 ;
  } else {
    $numpar = $k00_numpar;
  }
  if (isset($k00_receit)) {
    $receit = $k00_receit;
  } else {
    $receit = 0;
  }
  
  $record = debitos_numpre($k00_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),0,'',''," and y.k00_hist <> 918");
 
  if ($record!=false) {
    if (pg_numrows($record) != 0) {
      $matrec=array();
      $matpar["0"]="Todas Parcelas ...";
      $matrec["0"]="Todas Receitas ...";
      $valor = 0;
      $tvlrcor= 0;
      $tvlrjuros= 0;
      $tvlrmulta= 0;
      $tvlrdesconto= 0;
	  $tvlrhist = 0;
      $ttotal = 0;
      for ($i=0; $i<pg_numrows($record); $i++) {
        db_fieldsmemory($record,$i);
        $matpar[$k00_numpar]= "$k00_numpar";
        if ($numpar!=0 && $k00_numpar == $numpar) {
          $matrec[$k00_receit] ="$k02_descr";
          if ($receit!=0 && $k00_receit == $receit) {
            $valor += $total;
			$tvlrhist += $vlrhis;
            $tvlrcor+= $vlrcor;
            $tvlrjuros+= $vlrjuros;
            $tvlrmulta+= $vlrmulta;
            $tvlrdesconto+= $vlrdesconto;
            $ttotal+= $total;
          } else if ($receit==0) {
            $valor += $total;
			$tvlrhist += $vlrhis;
            $tvlrcor+= $vlrcor;
            $tvlrjuros+= $vlrjuros;
            $tvlrmulta+= $vlrmulta;
            $tvlrdesconto+= $vlrdesconto;
            $ttotal+= $total;
          }
        } else if ($numpar==0) {
          $matrec[$k00_receit] ="$k02_descr";
          if ($receit!=0 && $k00_receit == $receit) {
            $valor += $total;
			$tvlrhist += $vlrhis;
            $tvlrcor+= $vlrcor;
            $tvlrjuros+= $vlrjuros;
            $tvlrmulta+= $vlrmulta;
            $tvlrdesconto+= $vlrdesconto;
            $ttotal+= $total;
          } else if ($receit==0) {
            $valor += $total;
		    $tvlrhist += $vlrhis;
            $tvlrcor+= $vlrcor;
            $tvlrjuros+= $vlrjuros;
            $tvlrmulta+= $vlrmulta;
            $tvlrdesconto+= $vlrdesconto;
            $ttotal+= $total;
          }
        }
      }
      
      $k00_valor = $valor;
      $clarrecad = new cl_arrecad;
      $result = $clarrecad->sql_record($clarrecad->sql_query("","cgm.z01_nome#arretipo.k00_descr",""," arrecad.k00_numpre = $k00_numpre and arreinstit.k00_instit = ".db_getsession('DB_instit') ));
      $linhas  = $clarrecad->numrows;
	  if($linhas>0) {
        db_fieldsmemory($result,0);
      }
      ?>
	  <tr>
      <td colspan="4">&nbsp;</td>
      </tr>
	  
	  
      <tr>
      <td width="20%">Nome</td>
      <td width="30%">
      <?
      $clrotulo->label("z01_nome");
      db_input('z01_nome',40,$Iz01_nome,true,'text',3)
      ?>
      </td>
      <td width="20%">Valor Historico:</td>
      <td width="30%">
      
	  <?
      $clrotulo->label("K00_valor");
     db_input('k00_valor',15,"",true,'text',3,'','tvlrhist')
      ?>
	  
      </td>
      </tr>
      <tr>
      <td>Tipo Débito:</td>
      <td>
      <?
      $clrotulo->label("k00_descr");
      db_input('k00_descr',40,$Ik00_descr,true,'text',3)
      ?>
      </td>
      <td>Valor Corrigido:</td>
      <td>
      <?
      $clrotulo->label("k00_valor");
      db_input('k00_valor',15,$Ik00_valor,true,'text',3,'','tvlrcor')
      ?>
      </td>
      </tr>
	  
      <tr>
      <td>Código:</td>
      <td>
      <?
      $clrotulo->label("k00_numpre");
      db_input('k00_numpre',8,$Ik00_numpre,true,'text',3)
      ?>
      </td>
      <td>Juros:</td>
      <td>
      <?
      $clrotulo->label("k00_valor");
      db_input('k00_valor',15,$Ik00_valor,true,'text',3,'','tvlrjuros')
      ?>
      </td>
      </tr>
	  
      <tr>
      <td>Parcela:</td>
      <td>
      <?
      $clrotulo->label("k00_numpar");
      $k00_numpar = $numpar;
      db_select('k00_numpar',$matpar,true,2," onchange='document.form1.submit();' ");
      ?>
      </td>
      <td>Multa:</td>
      <td>
      <?
      $clrotulo->label("k00_valor");
      db_input('k00_valor',15,$Ik00_valor,true,'text',3,'','tvlrmulta')
      ?>
      </td>
      </tr>
     
	  <tr>
      <td>Receita:</td>
      <td>
      <?
      $clrotulo->label("k00_receit");
      $k00_receit = $receit;
      db_select('k00_receit',$matrec,true,2," onchange='document.form1.submit();' ")
      ?>
      </td>
      <td>Desconto:</td>
      <td>
      <?
      $clrotulo->label("k00_valor");
      db_input('k00_valor',15,$Ik00_valor,true,'text',3,'','tvlrdesconto')
      ?>
      </td>
      </tr>
	  
	  <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>Total:</td>
      <td><?
      $clrotulo->label("k00_valor");
      db_input('k00_valor',15,$Ik00_valor,true,'text',3,"",'ttotal');
      ?></td>
      </tr>
	  
      <tr>
      <td><div align="right"></div></td>
      <td>&nbsp;
      </td>
      <td>&nbsp;
      </td>
      <td>&nbsp;
      </td>
      </tr>
      <tr>
      <td> <div align="right"></div></td>
      <td align="right">Total Liberado Para desconto:</td>
      <td>
      <?
      $clrotulo->label("k00_valor");
      $k00_valor = $tvlrhist;
      db_input('k00_valor',15,$Ik00_valor,true,'text',3)
      ?>
      </td>
      <td>&nbsp;
      </td>
      </tr>
      <tr>
      <td>&nbsp;
      </td>
      <td align="right">Percentual: </td>
      <td>
      <?
      $clrotulo->label("DBtxt8");
      db_input('DBtxt8',15,$IDBtxt8,true,'text',2," onchange='js_calcula()'")
      ?>
      </td>
      <td>&nbsp;
      </td>
      </tr>
      <tr>
      <td>&nbsp;
      </td>
      <td align="right">Valor</td>
      <td>
      <?
      $clrotulo->label("DBtxt9");
      db_input('DBtxt9',15,$IDBtxt9,true,'text',2," onchange='js_calculavalor()'")
      ?>
      </td>
      <td>&nbsp;
      </td>
      </tr>
      <tr align="center">
      <td colspan="4"><input name="calcular" type="button" id="calcular" value="Calcular Desconto"></td>
      </tr>
      <tr align="center">
      <td colspan="4">&nbsp;
      </td>
      </tr>
      <tr align="center">
      <td colspan="4">&nbsp;
      </td>
      </tr>
      <tr align="center">
      <td colspan="4" ><table id="executa_desconto" style="visibility:hidden" width="77%" border="0" cellspacing="0">
      <tr>
      <td width="16%">Histórico:</td>
      <td width="84%">
      <?
      
      $clrotulo->label("k00_hist");
      $record = pg_exec("select * from histcalc order by k01_descr");
      db_selectrecord('k00_hist',$record,true,2,"","","");
      ?>
      </td>
      </tr>
      <tr>
      <td>Observação:</td>
      <td>
      <?
      $clrotulo->label("k00_histtxt");
      db_textarea('k00_histtxt',5,70,$Ik00_histtxt,true,'text',2)
      ?>
      </td>
      </tr>
      <tr>
      <td>&nbsp;
      </td>
      <td><input name="desconto" type="submit" id="desconto3" value="Lan&ccedil;ar Desconto" onClick='return js_validaValor();'></td>
      </tr>
      </table></td>
      </tr>
      <tr align="center">
      <td colspan="4">&nbsp;
      </td>
      </tr>
      <tr align="center">
      <td colspan="4"></td>
      </tr>
      <?
    }
  } else {
    $mostra=true;
  }
  ?>
  </table>
  </td>
  </tr>
  </form>
  </table>
  </center>
  <?
} else {
  ?>
  <table width="100%" height="100%" border="0" cellspacing="0" bgcolor="#CCCCCC">
  <form name="form1" action="" method="post">
  <tr>
  <td height="20" align="right">&nbsp;
  </td>
  <td>&nbsp;
  </td>
  </tr>
  <tr>
  <td width="52%" height="20" align="right">Numpre:</td>
  <td width="48%">
  <?
  $clrotulo->label("k00_numpre");
  db_input('k00_numpre',8,$Ik00_numpre,true,'text',2)
  ?>
  </td>
  </tr>
  <tr align="center">
  <td height="19" colspan="2"><input type="submit" name="Submit" value="Enviar"></td>
  </tr>
  <tr align="center">
  <td colspan="2">&nbsp;
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
<?
if (isset($mostra)) {
  echo "<script>alert('Código de arrecadação sem débito');</script>";
  db_redireciona("cai4_descnumpre001.php");
}
?>