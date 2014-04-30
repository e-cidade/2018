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


require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_app.utils.php");
require_once ("classes/db_aguacorte_classe.php");

$claguacorte = new cl_aguacorte();

$claguacorte->rotulo->label();

$oPost = db_utils::postMemory($_POST);

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" content="0">
	<?
    db_app::load('scripts.js, estilos.css');
  ?>
		
  <script type="text/javascript">
		function js_pesquisax40_codcorte(mostra){
		  if(mostra==true){
		    js_OpenJanelaIframe('top.corpo','db_iframe_aguacorte','func_aguacorte.php?funcao_js=parent.js_mostraaguacorte1|x40_codcorte|x40_dtinc','Pesquisa',true,20);
		  }else{
		     if(document.form1.x40_codcorte.value != ''){ 
		        js_OpenJanelaIframe('top.corpo','db_iframe_aguacorte','func_aguacorte.php?pesquisa_chave='+document.form1.x40_codcorte.value+'&funcao_js=parent.js_mostraaguacorte','Pesquisa',false);
		     }else{
		       document.form1.x40_dtinc.value = ''; 
		     }
		  }
		}
		function js_mostraaguacorte(chave,erro){
		  document.form1.x40_dtinc.value = chave; 
		}
		function js_mostraaguacorte1(chave1,chave2){
		  document.form1.x40_codcorte.value = chave1;
		  document.form1.x40_dtinc.value = chave2;
		  db_iframe_aguacorte.hide();
		}
	</script>
</head>

<body bgcolor="#CCCCCC">
<fieldset style="width: 300px; margin: 50px auto;">
  <legend><strong>Arquivo TXT para impressão de etiquetas</strong></legend>
  
	<form name="form1" action="" method="POST">
	<? 
	  db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'), db_getsession('DB_anousu'), db_getsession('DB_instit'));
	?>
	
	<table align="center">
	 <tr>
	   <td nowrap title="<?=$Tx40_codcorte?>">
        <?  
          db_ancora($Lx40_codcorte, 'js_pesquisax40_codcorte(true)', 1 , true)
        ?>
	   </td>
	   <td>
        <?
          db_input('x40_codcorte', 10, $Ix40_codcorte, true, 'text', 1, ' onchange="js_pesquisax40_codcorte(false)"')
        ?>
	   </td>
	 </tr>
	 
   <tr>
     <td nowrap title="<?=$Tx40_dtinc?>">
        <?=$Lx40_dtinc?>
     </td>
     <td>
        <?
          db_input('x40_dtinc', 10, $Ix40_dtinc, true, 3);
        ?>
     </td>
   </tr>
   
   <tr>
     <td nowrap title="Delimitador dos campos. Caracter utilizado para separar valores">
        <strong>Delimitador</strong>
     </td>
     <td>
        <?
          $delimitador = ';';
          db_input('delimitador', 10, $Ix40_delimitador, false)
        ?>
     </td>
   </tr>  
   
   <tr>
    <td colspan="2" align="center"><br/>
      <input type="submit" name="gerar" value="Gerar Arquivo" />
    </td>
   </tr> 
	 
	</table>
	</form>
	</fieldset>
	</body>
</html>


<?
if(isset($gerar)) {
  
  $sSql  = "select j01_matric, z01_nome, codpri, tipopri, nomepri, j39_numero, substr(x01_orientacao,1,1) as x01_orientacao, j39_compl "; 
  $sSql .= "  from aguacortemat "; 
  $sSql .= " inner join proprietario on j01_matric = x41_matric "; 
  $sSql .= " inner join aguabase on x01_matric = x41_matric ";
  $sSql .= " where x41_codcorte in ('{$oPost->x40_codcorte}') "; 
  $sSql .= " order by nomepri, j39_numero, x01_orientacao, j39_compl ";
  
  $rs    = db_query($sSql);
  
  $sFile = '/tmp/arquivos_etiquetas_' . $oPost->x40_codcorte;
  
  $rFile = fopen($sFile, 'w');
  
  if(pg_num_rows($rs) > 0) {
    
    for($i = 0; $i < pg_num_rows($rs); $i++) {
      
      $oListaCorte = db_utils::fieldsMemory($rs, $i);
      
      $sLinha  = $oListaCorte->j01_matric     . $oPost->delimitador;
      $sLinha .= $oListaCorte->z01_nome       . $oPost->delimitador;
      $sLinha .= $oListaCorte->codpri         . $oPost->delimitador;
      $sLinha .= $oListaCorte->tipopri        . $oPost->delimitador;
      $sLinha .= $oListaCorte->nomepri        . $oPost->delimitador;
      $sLinha .= $oListaCorte->j39_numero     . $oPost->delimitador;
      $sLinha .= $oListaCorte->x01_orientacao . $oPost->delimitador;
      $sLinha .= $oListaCorte->j39_compl;
      
      $sLinha .= "\n";
      
      fwrite($rFile, $sLinha);
      
    }
    
  } 
  
  if(fclose($rFile)) {

    db_msgbox('Arquivo gerado com sucesso.');
    
    echo "<script>";
    echo "  var arquivos;";
    echo "  arquivos = '$sFile#Download arquivo TXT (lista de corte)|';";
    echo "  js_montarlista(arquivos,'form1')";
    echo "</script>";
    
  }
  
  
  
}
?>