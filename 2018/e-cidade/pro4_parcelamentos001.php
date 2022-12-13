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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_procvar_classe.php");
include("classes/db_proctipovar_classe.php");
include("classes/db_cgm_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_arretipo_classe.php");
include("classes/db_tabrec_classe.php");
include("classes/db_histcalc_classe.php");
$clarretipo = new cl_arretipo;
$cltabrec = new cl_tabrec;
$clhistcalc = new cl_histcalc;
$clcgm = new cl_cgm;
$clcgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');
$clrotulo->label('k02_descr');
$clrotulo->label('k02_codigo');
$clrotulo->label('k01_codigo');
$clrotulo->label('k01_descr');
$clrotulo->label('k00_descr');
$clrotulo->label('k00_tipo');
$clrotulo->label('v07_hist');
$db_opcao = 1;

if(isset($processar)){
  
  db_postmemory($HTTP_POST_VARS);

  if ($z01_numcgm == "") {

    if ($j01_matric != "") {
      
      $sql = "select j01_numcgm as z01_numcgm from iptubase where j01_matric = $j01_matric";
      $result = pg_exec($sql);
      db_fieldsmemory($result,0);
      
    } elseif ($q02_inscr != "") {

      $sql = "select q02_numcgm as z01_numcgm from issbase where q02_inscr = $q02_inscr";
      $result = pg_exec($sql);
      db_fieldsmemory($result,0);
      
    }
    
  }

  $sql = "select nextval('numpref_k03_numpre_seq') as numpre";
  $result = pg_exec($sql);
  db_fieldsmemory($result,0);

  $dtvenc = $dtvenc_ano . "-" . $dtvenc_mes . "-" . $dtvenc_dia;

  $sql = "select '$dtvenc'::date - '$parcini months'::interval as dtvenc";
  $result = pg_exec($sql);
  db_fieldsmemory($result,0);

//  $sql = "select '$dtvenc'::date - '1 months'::interval as dtvenc";
//  $result = pg_exec($sql);
//  db_fieldsmemory($result,0);

  for($i=$parcini;$i<($parcelas + 1);$i++){

    $parcmarcada = "parc$i";

    if ($$parcmarcada == 't') {

      $sql = "select '$dtvenc'::date + '$i months'::interval as k00_dtvenc";
      $result = pg_exec($sql);
      db_fieldsmemory($result,0);

      $sql = "insert into arrecad (k00_numcgm,
				   k00_dtoper,
				   k00_receit,
				   k00_hist,
				   k00_valor,
				   k00_dtvenc,
				   k00_numpre,
				   k00_numpar,
				   k00_numtot,
				   k00_numdig,
				   k00_tipo,
				   k00_tipojm)
		  values ($z01_numcgm,'" .
			  $dtoper_ano . "-" . $dtoper_mes . "-" . $dtoper_dia . "',
			  $k02_codigo,
			  $k01_codigo,
			  $valor,
			  '$k00_dtvenc',
			  $numpre,
			  $i,
			  $parcelas,
			  0,
			  $k00_tipo,
			  0)";
      $result = pg_exec($sql);

    }
      
  }

  $sql = "select nextval('termo_v07_parcel_seq') as parcel";
  $result = pg_exec($sql);
  db_fieldsmemory($result,0);

  $sql = "insert into termo (v07_parcel,
			     v07_dtlanc,
			     v07_valor,
			     v07_numpre,
			     v07_totpar,
			     v07_vlrpar,
			     v07_dtvenc,
			     v07_vlrent,
			     v07_datpri,
			     v07_login,
			     v07_numcgm,
			     v07_hist)
	      values (
			      $parcel,'" .
			      date("Y-m-d",db_getsession("DB_datausu")) . "',
			      $valor * ($parcelas - $parcini),
			      $numpre,
			      $parcelas,
			      $valor,
			      '" . $dtvenc_ano . "-" . $dtvenc_mes . "-" . $dtvenc_dia . "',
			      $valor,
			      '" . $dtvenc_ano . "-" . $dtvenc_mes . "-" . $dtvenc_dia . "'," .
			      db_getsession("DB_id_usuario") . ",
			      $z01_numcgm,
			      '$v07_hist')";
  $result = pg_exec($sql);

  if ($j01_matric != "") {
    
    $sql = "insert into arrematric values ($numpre, $j01_matric)";
    $result = pg_exec($sql);
    
  } elseif ($q02_inscr != "") {

    $sql = "insert into arreinscr values ($numpre, $q02_inscr)";
    $result = pg_exec($sql);
    
  }

  echo "<script>alert('Processamento concluído!');</script>";
  db_redireciona("pro4_parcelamentos001.php");
  
}

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>

function js_receitas(chave){
    if(chave){
      js_OpenJanelaIframe('','iframa_tabrec','func_tabrec.php?funcao_js=parent.js_mostra|k02_codigo|k02_descr','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('','iframa_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.k02_codigo.value+'&funcao_js=parent.js_mostra1','Pesquisa',false);
    }
}
function js_mostra(chave,chave1){
   document.form1.k02_codigo.value = chave;
   document.form1.k02_descr.value = chave1;
   iframa_tabrec.hide();
}
function js_mostra1(chave,chave1){
   document.form1.k02_descr.value = chave;
   if(chave1){
     document.form1.k02_codigo.select();
     document.form1.k02_codigo.focus();
   }
}

function js_histcalc(chave){
    if(chave){
      js_OpenJanelaIframe('','iframa_histcalc','func_histcalc.php?funcao_js=parent.js_mostrahist|k01_codigo|k01_descr','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('','iframa_histcalc','func_histcalc.php?pesquisa_chave='+document.form1.k01_codigo.value+'&funcao_js=parent.js_mostrahist1','Pesquisa',false);
    }
}
function js_mostrahist(chave,chave1){
   document.form1.k01_codigo.value = chave;
   document.form1.k01_descr.value = chave1;
   iframa_histcalc.hide();
}
function js_mostrahist1(chave,chave1){
   document.form1.k01_descr.value = chave;
   if(chave1){
     document.form1.k01_codigo.select();
     document.form1.k01_codigo.focus();
   }
}

function js_mostranomes(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_nome.php?funcao_js=parent.js_preenche|z01_numcgm|z01_nome';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
    func_nome.jan.location.href = 'func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_preenche1';	
  }
}
 function js_preenche(chave,chave1){
   document.form1.z01_numcgm.value = chave;
   document.form1.z01_nome.value = chave1;
   func_nome.hide();
 }
 function js_preenche1(chave,chave1){
   document.form1.z01_nome.value = chave1;
   if(chave1==true){
      document.form1.z01_numcgm.value = "";
      document.form1.z01_numcgm.focus();
   }
}


function js_mostramatricula(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_preenchematricula|j01_matric|z01_nome';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
    func_nome.jan.location.href = 'func_iptubase.php?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_preenchematricula';	
  }
}
 function js_preenchematricula(chave,chave1){
   document.form1.j01_matric.value = chave;
   document.form1.z01_nome.value = chave1;
   func_nome.hide();
 }
function js_mostrainscricao(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_issbase.php?funcao_js=parent.js_preencheinscricao|0|1';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
    func_nome.jan.location.href = 'func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_preencheinscricao';	
  }
}
 function js_preencheinscricao(chave,chave1){
   document.form1.q02_inscr.value = chave;
   document.form1.z01_nome.value = chave1;
   func_nome.hide();
 }
function js_mostratipo(chave){
    if(chave){
         js_OpenJanelaIframe('','iframe_arretipo','func_arretipo.php?funcao_js=parent.js_funcaotipo|k00_tipo|k00_descr','Pesquisa',true);
	  }else{
	       js_OpenJanelaIframe('','iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.k00_tipo.value+'&funcao_js=parent.js_funcaotipo1','Pesquisa',false);
	        }
}
function js_funcaotipo(chave,chave1){
   document.form1.k00_tipo.value = chave;
    document.form1.k00_descr.value = chave1;
     iframe_arretipo.hide();
}
function js_funcaotipo1(chave,chave1){
   document.form1.k00_descr.value = chave;
    if(chave1){
         document.form1.k00_tipo.select();
	    document.form1.k00_tipo.focus();
		   }
		    iframe_arretipo.hide();
}


</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <table>
    <form name="form1" method="post">
	
          <table border="0" cellspacing="0" cellpadding="0">
	  <?
	  if(!isset($z01_numcgm) || !isset($j01_matric) || !isset($q02_inscr)){
	  ?>
            <tr> 
              <td height="25" title="<?=$Tz01_nome?>"> 
                <?
				db_ancora($Lz01_nome,'js_mostranomes(true);',4)
				?>
              </td>
              <td height="25"> 
                <?
				db_input("z01_numcgm",6,$Iz01_numcgm,true,'text',4," onchange='js_mostranomes(false);'")
				?>
                <?
				db_input("z01_nome",40,$Iz01_nome,true,'text',5)
				?>
              </td>
            </tr>
            <tr> 
              <td height="25" title="<?=$Tj01_matric?>"> 
                <?
				db_ancora($Lj01_matric,'js_mostramatricula(true);',2)
				?>
              </td>
              <td height="25"> 
                <?
				db_input("j01_matric",6,$Ij01_matric,true,'text',4)
				?>
              </td>
            </tr>
            <tr> 
              <td height="25" title="<?=$Tq02_inscr?>"> 
                <?
				db_ancora($Lq02_inscr,'js_mostrainscricao(true);',4)
				?>
              </td>
              <td height="25"> 
                <?
				db_input("q02_inscr",6,$Iq02_inscr,true,'text',4)
				?>
              </td>
            </tr>
	    <tr>
	      <td>
	      &nbsp;
	      </td>
	      <td>
	        <input  type="submit" value="Processar" name="proc" onClick="return js_entrada()">   
	      </td>
	    </tr>
	    <script>
	    function js_entrada(){
	      F = document.form1;
	      if(F.q02_inscr.value == "" && F.j01_matric.value == "" && F.z01_numcgm.value == ""){
	        alert('Preencha um dos campos para prosseguir!')
		return false;
	      }else{
	        return true;
	      }
	      return false;
	    }
	    </script>
	    <?
	    }else{
	      $result = $clarretipo->sql_record($clarretipo->sql_query("6","k00_tipo,k00_descr","k00_tipo"));
	      if($clarretipo->numrows > 0){
		db_fieldsmemory($result,0);
	      }
	      if(empty($k02_codigo)){
   	        $result = $cltabrec->sql_record($cltabrec->sql_query("184","k02_codigo,k02_descr","k02_codigo"));
	        if($cltabrec->numrows > 0){
  	   	  db_fieldsmemory($result,0);
	        }
	      }	
	      $result = $clhistcalc->sql_record($clhistcalc->sql_query("51","k01_codigo,k01_descr","k01_codigo"));
	      if($clhistcalc->numrows > 0){
		db_fieldsmemory($result,0);
	      }
	      db_input("z01_numcgm",6,$Iz01_numcgm,true,'hidden',4);
	      db_input("j01_matric",6,$Ij01_matric,true,'hidden',4);
	      db_input("q02_inscr",6,$Iq02_inscr,true,'hidden',4);
	    ?>
            <tr> 
              <td height="25" title="<?=$Tk02_codigo?>"> 
                <?
	          db_ancora($Lk02_descr,'js_receitas(true);',4)
  	        ?>
              </td>
              <td height="25"> 
                <?
				db_input("k02_codigo",6,$Ik02_codigo,true,'text',4," onchange='js_receitas(false);'")
				?>
                <?
				db_input("k02_descr",40,$Ik02_descr,true,'text',3)
				?>
              </td>
            </tr>

            <tr> 
              <td height="25" title="<?=$Tk01_codigo?>"> 
                <?
	          db_ancora($Lk01_descr,'js_histcalc(true);',4)
  	        ?>
              </td>
              <td height="25"> 
                <?
				db_input("k01_codigo",6,$Ik01_codigo,true,'text',4," onchange='js_histclac(false);'")
				?>
                <?
				db_input("k01_descr",40,$Ik01_descr,true,'text',3)
				?>
              </td>
            </tr>
	    
            <tr> 
              <td height="25" title="<?=$Tk00_tipo?>"> 
                	<?
				db_ancora($Lk00_descr,'js_mostratipo(true);',4)
				?>
              </td>
              <td height="25"> 
                <?
				db_input("k00_tipo",6,$Ik00_tipo,true,'text',4," onchange='js_mostratipo(false);'")
				?>
                <?
				db_input("k00_descr",40,$Ik00_descr,true,'text',3)
				?>
              </td>
            </tr>
	    <tr>
	      <td>
	        <b>Total de parcelas: </b>
	      </td>
	      <td>
                <?
			db_input("parcelas",6,"",true,'text',4,"")
				?>
	      </td>
	    </tr>

	    <tr>
	      <td>
	        <b>Parcela inicial: </b>
	      </td>
	      <td>
                <?
		if (!isset($parcini)) {
		  $parcini = 2;
		}
		db_input("parcini",6,"",true,'text',4,"")
		?>
	      </td>
	    </tr>

	    
	    <tr>
	      <td>
	        <b>Valor de cada parcela: </b>
	      </td>
	      <td>
                <?
			db_input("valor",6,"",true,'text',4,"onKeyUp='this.value = this.value.replace(\",\",\".\")'")
				?>
	      </td>
	    </tr>
	    <tr>
	      <td>
	        <b>Primeiro vencimento: </b>
	      </td>
	      <td>
                <?
if(empty($dtvenc_dia)){
//  $dtvenc_dia = date("d",db_getsession("DB_datausu"));
//  $dtvenc_mes = date("m",db_getsession("DB_datausu"));
//  $dtvenc_ano = date("Y",db_getsession("DB_datausu"));
} 
db_inputdata('dtvenc',@$dtvenc_dia,@$dtvenc_mes,@$dtvenc_ano,true,'text',$db_opcao,"")

				?>
	      </td>
	    </tr>
	    <tr>
	      <td>
	        <b>Data de operação: </b>
	      </td>
	      <td>
                <?
if(empty($dtoper_dia)){
//  $dtoper_dia = date("d",db_getsession("DB_datausu"));
//  $dtoper_mes = date("m",db_getsession("DB_datausu"));
//  $dtoper_ano = date("Y",db_getsession("DB_datausu"));
} 
db_inputdata('dtoper',@$dtoper_dia,@$dtoper_mes,@$dtoper_ano,true,'text',$db_opcao,"")

				?>
	      </td>
	    </tr>
            <tr> 
              <td height="25" title="<?=$Tv07_hist?>"> 
                <?=$Lv07_hist?>
              </td>
              <td height="25"> 
                <?
                db_textarea('v07_hist',3,50,$Iv07_hist,true,'text',$db_opcao,"")
				?>
              </td>
            </tr>
	      <?
	      if(isset($parcelar)){
	      ?>  
              <tr> 
                <td height="25">&nbsp;</td>
                <td height="25"><input  type="submit" value="Parcelar" name="processar"></td>
              </tr>
	      <?
	      }else{
	      ?>
              <tr> 
                <td height="25">&nbsp;</td>
                <td height="25"><input  type="submit" value="Processar" name="parcelar"></td>
              </tr>
	      <?
	      }
	      ?>
	    <?
	    }
	    ?>
	    <?
	    if(isset($parcelar)){
	      echo "<table cellpading='0' cellspacing='2' bgcolor='#6699cc'>
		     <tr >
		       <td><b>S</b></td><td><b>N</b></td></td><td><b>Parcela(s)</b></td>
		       <td><b>Valor ".db_formatar($valor,'f')." </b></td>
		     </tr>
		     ";
//	      $valor = $valor / $parcelas;
	      for($i=$parcini;$i<($parcelas + 1);$i++){
		$cor = ($i%2 == 0?"#ffddaa":"#aaddff");
	        echo "
		     <tr bgcolor='$cor'>
		       <td><input name='parc$i' value='t' type='radio' checked onClick=\"this.value = 't'\"></td><td><input name='parc$i' value='f' type='radio'></td><td><b>Parcela $i </b></td>
		       <td><b>Valor ".db_formatar($valor,'f')." </b></td>
		     </tr>
		     ";
	      }
	      echo "</table>";
	    }
	    ?>
          </table>
        </form>
    </table>
 </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?

$func_nome = new janela('func_nome','');
$func_nome ->posX=1;
$func_nome ->posY=20;
$func_nome ->largura=780;
$func_nome ->altura=430;
$func_nome ->titulo="Pesquisa";
$func_nome ->iniciarVisivel = false;
$func_nome ->mostrar();

$fnome = new janela('fnome','');
$fnome ->posX=20;
$fnome ->posY=20;
$fnome ->largura=770;
$fnome ->altura=430;
$fnome ->titulo="Pesquisa";
$fnome ->iniciarVisivel = false;
$fnome ->mostrar();

?>