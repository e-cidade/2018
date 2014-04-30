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
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("classes/db_isscadsimplesbaixa_classe.php");
include("dbforms/db_funcoes.php");

$clisscadsimplesbaixa = new cl_isscadsimplesbaixa();
$clisscadsimplesbaixa->rotulo->label();
$post     = db_utils::postmemory($_POST);
$clrotulo = new rotulocampo;
$db_opcao = 1;

if (isset($post->processar)){

    if (isset($post->inscr) && is_array($post->inscr)){
      
			db_inicio_transacao();
			$lSqlErro = false;
      foreach ($post->inscr as $key => $val){

         $clisscadsimplesbaixa->q39_isscadsimples = $val;
         $clisscadsimplesbaixa->incluir(null);
				 if ($clisscadsimplesbaixa->erro_status == 0){

            $lSqlErro = true;
						$sErro    = $clisscadsimplesbaixa->erro_msg;
						break;
				 }
			}
		  db_fim_transacao($lSqlErro);
		  if ($lSqlErro == true){
         db_msgbox($sErro);
		  }else{
         db_msgbox("Baixa(s) Efetuadas com Sucesso!");
         db_redireciona("iss4_baixageralsimples001.php");
          
			}
		}else{
			 db_msgbox("Selecione ao menos uma inscrição!");
		}
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cab  {font-weight:bold;text-align:center;
       padding:2px;
			 border-bottom:1px solid white;
			 border-left:1px solid white;           
       background-color:#EEEFF2;          
	
	}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name='form1' method='post'>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
    <center>
		<br>
    <table><td>
		 <fieldset><legend><b>Dados da Baixa</b></legend>
		 <table>
  <tr>
    <td nowrap title="<?=@$Tq39_dtbaixa?>">
       <?=@$Lq39_dtbaixa?>
    </td>
    <td> 
<?
if (!isset($q39_dtbaixa)){
  
	$q39_dtbaixa_dia = 31;
	$q39_dtbaixa_mes = 12;
	$q39_dtbaixa_ano = db_getsession("DB_anousu");

}
db_inputdata('q39_dtbaixa',@$q39_dtbaixa_dia,@$q39_dtbaixa_mes,@$q39_dtbaixa_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq39_issmotivobaixa?>">
       <?=@$Lq39_issmotivobaixa?>
    </td>
    <td> 
       <?
       include("classes/db_issmotivobaixa_classe.php");
       $clissmotivobaixa = new cl_issmotivobaixa;
       $result = $clissmotivobaixa->sql_record($clissmotivobaixa->sql_query("","*"));
       db_selectrecord("q39_issmotivobaixa",$result,true,$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq39_obs?>">
       <?=@$Lq39_obs?>
    </td>
    <td> 
<?
db_textarea('q39_obs',3,60,$Iq39_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    
</table>
</fieldset></td>

</table>
 <input name="processar" type="submit" id="db_opcao" value="Processar" onclick="return js_valida()">
<br>
<br>
<table width='80%' cellspacing='0' style='border:2px inset white'>
   <tr>
       <td class='cab'>
         	<input type='checkbox' style='display:none' id='mtodos' onclick='js_marca()'>
	         <a onclick='js_marca()' style='cursor:pointer'>M</a></b></td>
       <td class='cab'>Inscrição</td>
       <td class='cab'>Nome/Razão Social</td>
       <td class='cab'>Data Inicial</td>
   </tr>
    <tbody  style='height:250;overflow:scroll;border:1px inset black;overflow-x:hidden'>
    <?
       $sSql = "select z01_nome,
			                  q38_sequencial,
					 					    q38_dtinicial,
											  q38_inscr 
								   from isscadsimples 
								          inner join issbase on q38_inscr                      = q02_inscr
										  		inner join cgm     on q02_numcgm                     = z01_numcgm
											  	left outer join isscadsimplesbaixa on q38_sequencial = q39_isscadsimples 
  								where q39_sequencial is null
									order by q38_inscr";				
    $rs   = pg_query ($sSql);
		if (pg_num_rows($rs)> 0 ){
			 for ($i = 0;$i < pg_num_rows($rs);$i++){

				 $bgcolor = $i % 2 == 0?"#FFFFFF":"#EFEFEF";
       		$oIss = db_utils::fieldsMemory($rs,$i);
       
			    echo "\n<tr style='background-color:$bgcolor;height:1em'>\n";
					echo "    <td style='text-align:center'><input type='checkbox' class='chkimp'";
					echo "         name='inscr[]' value='".$oIss->q38_sequencial."'></td>\n" ;
					echo "    <td style='text-align:center'>".$oIss->q38_inscr."</td>\n";
					echo "    <td style='text-align:left'>".$oIss->z01_nome."</td>\n";
					echo "    <td style='text-align:center'>".db_formatar($oIss->q38_dtinicial,"d")."</td>\n";
			    echo " </tr>\n";


       }
		}
    ?>
    <tr style='height:auto'><td>&nbsp;</td></tr>
   </tbody>
	 </table>
	 </form>
 </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_marca(){
  
	 obj = document.getElementById('mtodos');
	 if (obj.checked){
		 obj.checked = false;
	}else{
		 obj.checked = true;
	}
   itens = js_getElementbyClass(form1,'chkimp');
	 for (i = 0;i < itens.length;i++){

        if (obj.checked == true){
					itens[i].checked=true;
       }else{
					itens[i].checked=false;
			 }
	 }
}

function js_valida(){

  data = document.getElementById('q39_dtbaixa');
  if (data.value == ''){

    alert('Data da baixa não pode ser Vazio.');
		data.focus();
		return false;
	}

}
</script>