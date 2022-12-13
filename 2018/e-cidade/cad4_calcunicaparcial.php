<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_iptunump_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("dbforms/db_funcoes.php");

require_once("libs/db_utils.php");
require_once ("classes/db_recibounica_classe.php");

$oDaoReciboUnica = new cl_recibounica();


db_postmemory($HTTP_POST_VARS);
$erro = false;
$descricao_erro = false;
$quantos = 0;

$k00_dtoper_dia = date("d",db_getsession("DB_datausu"));
$k00_dtoper_mes = date("m",db_getsession("DB_datausu"));
$k00_dtoper_ano = date("Y",db_getsession("DB_datausu"));

$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label("j01_matric");
$clrotulocampo = new rotulocampo;
$clrotulocampo->label('k00_histtxt');
if(isset($calculaunica)){
  set_time_limit(0);
  $cliptunump = new cl_iptunump;
  $result = $cliptunump->sql_record($cliptunump->sql_query_file($anousu,$j01_matric,'j20_matric#j20_numpre'));
  if($result==false || $cliptunump->numrows == 0 ){
    $erro = true;
    $descricao_erro =  "Não existe cálculo efetuado para esta Matrícula ($j01_matric).";
  }else{
    $sqlunica = pg_query("BEGIN");
    
                 /*
                 * alteracao para incluir no cabeçalho criado para recibo unica
                 * recibounicageracao
                 */
                require_once("classes/db_recibounicageracao_classe.php");
                $oDaoReciboUnicaGeracao = new cl_recibounicageracao();
                
                $oDaoReciboUnicaGeracao->ar40_db_usuarios        = db_getsession("DB_id_usuario");
                $oDaoReciboUnicaGeracao->ar40_dtoperacao         = "$k00_dtoper_ano-$k00_dtoper_mes-$k00_dtoper_dia";
                $oDaoReciboUnicaGeracao->ar40_dtvencimento       = "$k00_dtvenc_ano-$k00_dtvenc_mes-$k00_dtvenc_dia";
                $oDaoReciboUnicaGeracao->ar40_percentualdesconto = $k00_percdes;
                $oDaoReciboUnicaGeracao->ar40_tipogeracao        = "G";
                $oDaoReciboUnicaGeracao->ar40_ativo              = 'true';
                $oDaoReciboUnicaGeracao->ar40_observacao         = 'Inclusao pela PRORROGACAO  parcela unica (cad4_calculaunicaparcial.php)';
                $oDaoReciboUnicaGeracao->incluir(null);
                if($oDaoReciboUnicaGeracao->erro_status == 0){
                  
                  $descricao_erro = $oDaoReciboUnicaGeracao->erro_msg;
                  $sqlerro = true;
                }    
    
    
    
	for($i=0;$i<$cliptunump->numrows;$i++){
      db_fieldsmemory($result,$i);
      $sqlunica = pg_query("select k00_dtvenc,k00_percdes
	                        from recibounica 
	                        where k00_numpre = $j20_numpre and k00_dtvenc = '$k00_dtvenc_ano-$k00_dtvenc_mes-$k00_dtvenc_dia'");
      $erro = true;
	  $perc = 0;
      if(pg_numrows($sqlunica)!=0){
        $perc = pg_result($sqlunica,0,'k00_percdes');
	    $sqlresultunica = "delete from recibounica where k00_numpre = $j20_numpre and k00_dtvenc = '$k00_dtvenc_ano-$k00_dtvenc_mes-$k00_dtvenc_dia'";
	    $resultunica = pg_query($sqlresultunica );
        $descricao_erro = "Vencimento Excluído.";
      }
      if(($perc!=$k00_percdes) || (pg_numrows($sqlunica)==0)){
      	
		    //$sqlresultunica = "insert into recibounica values($j20_numpre,'$k00_dtvenc_ano-$k00_dtvenc_mes-$k00_dtvenc_dia','$k00_dtoper_ano-$k00_dtoper_mes-$k00_dtoper_dia',$k00_percdes)";
		    //$resultunica = pg_query($sqlresultunica );
	
	      $oDaoReciboUnica->k00_numpre             = $j20_numpre;
	      $oDaoReciboUnica->k00_dtvenc             = "$k00_dtvenc_ano-$k00_dtvenc_mes-$k00_dtvenc_dia";
	      $oDaoReciboUnica->k00_dtoper             = "$k00_dtoper_ano-$k00_dtoper_mes-$k00_dtoper_dia";
	      $oDaoReciboUnica->k00_percdes            = $k00_percdes;
	      $oDaoReciboUnica->k00_tipoger            = "G";
	      $oDaoReciboUnica->k00_recibounicageracao = $oDaoReciboUnicaGeracao->ar40_sequencial;
	      $oDaoReciboUnica->incluir(null); 	    
		    
		    if($oDaoReciboUnica->erro_status == 0){
	          $descricao_erro = $oDaoReciboUnica->erro_msg; //"Erro ao incluir no arquivo recibounica";
			  }else{
	          $descricao_erro = "Vencimento Incluído.";
			  }
		  
		  
	   }
    $histd  = "Data: ".$k00_dtoper_dia."-".$k00_dtoper_mes."-".$k00_dtoper_ano;
	  $histd .= " Perc: ".$k00_percdes." Usuário: ".db_getsession("DB_login");
	  $histd .= $k00_histtxt;


      $sqlresultunica = "insert into arrehist(k00_numpre,
                                              k00_numpar,
					      k00_hist,
					      k00_dtoper,
					      k00_hora,
					      k00_id_usuario,
					      k00_histtxt,
					      k00_idhist) 
                                      values ($j20_numpre,
           			              0,
					      890,
					      '".date("Y-m-d",db_getsession("DB_datausu"))."',
					      '".date("G:i")."',
					      ".db_getsession("DB_id_usuario").",
					      '$histd',
					      nextval('arrehist_k00_idhist_seq'))";
//	echo $sqlresultunica; exit;					      
	  $resultunica = pg_query($sqlresultunica );
	  if($resultunica==false){
        $descricao_erro = "Erro ao incluir no arquivo historicos";
	  } 
	}
    $sqlunica = pg_query("COMMIT");
  }
}

$clrotulo = new rotulocampo;
$clrotulo->label('k00_dtvenc');
$clrotulo->label('k00_dtoper');
$clrotulo->label('k00_percdes');

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_verifica_campos(){
  if(document.form1.j01_matric.value == "" ){
    alert('Matricula Zerada.');
    document.form1.j01_matric.focus();
	return false;
  }

  if(document.form1.k00_dtvenc_dia.value =="" || document.form1.k00_dtvenc_mes.value ==""|| document.form1.k00_dtvenc_ano.value == ""){
    alert('Data de vencimento inválida.');
    document.form1.k00_dtvenc_dia.focus();
	return false;
  }
  if(document.form1.k00_dtoper_dia.value =="" || document.form1.k00_dtoper_mes.value ==""|| document.form1.k00_dtoper_ano.value == ""){
    alert('Data de operação inválida.');
    document.form1.k00_dtoper_dia.focus();
    return false;
  }
  if(document.form1.k00_percdes.value =="" ){
    alert('Percentual Inválido.');
    document.form1.k00_percdes.focus();
    return false;
  }
  js_mostra_processando();
  return true;
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
   <form name="form1" action="" method="post" onsubmit="return js_verifica_campos();">
	    <table width="292" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td height="25">&nbsp;</td>
            <td height="25">&nbsp;</td>
          </tr>
          <tr> 
            <td height="25"> 
              <?
				db_ancora($Lj01_matric,'js_mostramatricula(true);',2)
				?>
            </td>
            <td height="25"> 
              <?
				db_input("j01_matric",8,$Ij01_matric,true,'text',4)
				?>
            </td>

            <td height="25">
	      Ano:
            </td>

            <td height="25">
              <?
	      $result=pg_query("select distinct j18_anousu from cfiptu order by j18_anousu desc");
	      if(pg_numrows($result) > 0){
		?>
		<select name="anousu">
		<?
  	        for($i=0;$i<pg_numrows($result);$i++){
		db_fieldsmemory($result,$i);
	        ?>
	        <option value='<?=$j18_anousu?>'><?=$j18_anousu?></option>
	        <?
	        }
		?>
		</select>
		<?
	      }
	        ?>
            </td>
          </tr>
          <tr> 
            <td width="131" height="25">Data vencimento:</td>
            <td width="161" height="25"> 
              <?
			db_inputdata('k00_dtvenc','','','',true,'text',4)
			?>
            </td>
          </tr>
          <tr> 
            <td height="25">Data Lan&ccedil;amento:</td>
            <td height="25"> 
              <?
//			db_inputdata('k00_dtoper','','','',true,'text',4)
			db_inputdata('k00_dtoper',$k00_dtoper_dia,$k00_dtoper_mes,$k00_dtoper_ano,true,'text',4)
			?>
            </td>
          </tr>
          <tr> 
            <td height="25">Percentual Desconto:</td>
            <td height="25"> 
              <?
			db_input('k00_percdes','','','',true,'text',4)
			?>
            </td>
          </tr>
          <tr>
            <td height="25">Hist&oacute;rico:</td>
            <td height="25">
              <?
			$k00_histtxt = trim(@$k00_histtxt);
			db_textarea('k00_histtxt',5,30,$Ik00_histtxt,true,'text',4);
			?>
            </td>
          </tr>
          <tr align="center"> 
            <td height="25" colspan="2"> <input name="calculaunica"  type="submit" id="calculaunica" value="Calcula Parcela &Uacute;nica"> 
            </td>
          </tr>
          <tr align="center"> 
            <td height="25" colspan="2">&nbsp;</td>
          </tr>
          <script>
		  function js_mostra_processando(){
		     document.form1.processando.style.visibility='visible';
		  }
		  </script>
          <tr align="center" > 
            <td height="25" colspan="2" > <input name="processando" id="processando" style='color:red;border:none;visibility:hidden' type="button"  readonly value="Processando. Aguarde..."> 
            </td>
          </tr>
        </table>
      </form>
     </td>
  </tr>
</table>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($erro==true){
  echo "<script>alert('$descricao_erro');</script>";
}
?>
<script>
function js_mostramatricula(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_preenchematricula|0';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
    func_nome.jan.location.href = 'func_iptubase.php?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_preenchematricula';	
  }
}
function js_preenchematricula(chave){
   document.form1.j01_matric.value = chave;
   func_nome.hide();
}
</script>
<?
$func_nome = new janela('func_nome','');
$func_nome ->posX=1;
$func_nome ->posY=20;
$func_nome ->largura=770;
$func_nome ->altura=430;
$func_nome ->titulo="Pesquisa";
$func_nome ->iniciarVisivel = false;
$func_nome ->mostrar();
?>