<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_protprocesso_classe.php"));

db_postmemory($_POST);
db_postmemory($_GET);

$oGet = db_utils::postMemory($_GET);

$clprotprocesso = new cl_protprocesso;
$clprotprocesso->rotulo->label("p58_codproc");
$clprotprocesso->rotulo->label("p58_requer");
$clprotprocesso->rotulo->label("p58_numero");

// se func for chamada da rotina de reimpressao de capa de processo, variavel = 1
// mais abaixo testa se for 1 utiliza campo p58_coddepto da tabela protprocesso, e senao utiliza p61_coddepto da procandam
// isso porque na reimpressao da capa nao pode obrigar a ter andamento no processo, e nos outros casos sim, e a func sÃ³pode trazer se estiver no mesmo
// depto que o processo estÃ¡atualmente
if (!isset($reimprime)) {
  $reimprime = 0;
}

if (isset($usuariorecebeu)) {
  $usuariorecebeu = 1;
}


$sWhereDepartamento = "(p61_coddepto = ".db_getsession("DB_coddepto").")";
if ($reimprime == 1) {
  $sWhereDepartamento = '';
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tp58_codproc?>">
              <?=$Lp58_codproc?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("p58_codproc",10,$Ip58_codproc,true,"text",4,"","chave_p58_codproc");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tp58_numero?>">
              <?=$Lp58_numero?>
            </td>
            <td width="96%" align="left" nowrap> 
                <?
                 db_input("p58_numero",10, $Ip58_numero,true,"text",4,"","chave_p58_numero");
               ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tp58_requer?>">
              <?=$Lp58_requer?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("p58_requer",50,$Ip58_requer,true,"text",4,"","chave_p58_requer");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?

      $where = '';
      if (!empty($sWhereDepartamento)) {
        $where .= " {$sWhereDepartamento} and ";
      }
      $where .= "p51_tipoprocgrupo = {$grupo}";
        
      if (!isset($pesquisa_chave)) {
      	
        if(isset($chave_p58_codproc) && (trim($chave_p58_codproc)!="") ){
	        $where .= " and p58_codproc=$chave_p58_codproc  ";
        } else if(isset($chave_p58_requer) && (trim($chave_p58_requer)!="") ){
	        $where .= " and p58_requer like '$chave_p58_requer%' ";
        } else if (isset($chave_p58_numero) && (trim($chave_p58_numero)!="")) {
           
          $aPartesNumero = explode("/", $chave_p58_numero);
          $iAno = db_getsession("DB_anousu");
          if (count($aPartesNumero) > 1) {
             $iAno = $aPartesNumero[1];
          }
          $iNumero = $aPartesNumero[0];
          $where  .= " and p58_ano = {$iAno} and p58_numero = '{$iNumero}'"; 
        }

        /**  join feito para  pegar somente o processo  que foi transferido para um usuario espeficico de departamento**/
        $join = "";
        if ($usuariorecebeu) { 
          $where  .= "and p62_coddeptorec   = ".db_getsession("DB_coddepto")."
                     and ( p62_id_usorec   = ".db_getsession("DB_id_usuario")."
                      or   p62_id_usorec   = 0 )";

          $join = " inner  join proctransand  on  proctransand.p64_codandam  = protprocesso.p58_codandam
                                     inner join proctransfer on  p62_codtran = p64_codtran" ;           
        }

        $sql = "select p58_codproc as dl_cod_processo, 
		                   p58_numero  as dl_processo, 
		                   z01_nome    as dl_nome_ou_Razão_social,   
		                   p58_dtproc  , 
		                   p58_obs, 
		                   p68_codproc 
		               from ( select p58_codproc,
                			              p58_numero||'/'||p58_ano as p58_numero,
                				            z01_nome,
		                                p58_dtproc,
                                    p58_obs,                                    
                				            arqproc.p68_codproc
                			         from protprocesso
                			               inner join tipoproc on p51_codigo = p58_codigo
                				              
                                     inner join cgm on p58_numcgm = z01_numcgm
                				             " . ($reimprime==1?"left":"inner" ) . " join procandam on p58_codandam = p61_codandam
                				             left join arqproc on arqproc.p68_codproc = protprocesso.p58_codproc 
                                     $join
                			       where $where ) as x
			           where   x.p68_codproc is null order by x.p58_codproc desc";
                
            
        db_lovrot($sql,15,"()","",$funcao_js);
      } else {

        if ( $pesquisa_chave != null && $pesquisa_chave != "") {

          $iAnoUsu         = db_getsession("DB_anousu");
          $aChavePesquisa  = explode('/', $pesquisa_chave);
          $iNumeroProcesso = $aChavePesquisa[0];
          if ( count($aChavePesquisa) > 1 ) {
          	$iAnoUsu = $aChavePesquisa[1];
          }
          $where = '';
          if (!empty($sWhereDepartamento)) {
            $where .= " {$sWhereDepartamento} and ";
          }
	        $where .= " p58_numero = '{$iNumeroProcesso}' and p58_ano = {$iAnoUsu} ";
	        $sql = "select * from (
                     				   select p58_codproc,
                     				          p58_numero, 
                                      p58_ano,
                           					  p58_requer,
                           					  z01_nome,
		       		                        p58_numcgm,
                           					  p61_id_usuario,
                           					  arqproc.p68_codproc
                     				     from protprocesso
                     				          inner join tipoproc on p51_codigo = p58_codigo
                           					  inner join cgm on p58_numcgm = z01_numcgm
                           					  " . ( $reimprime==1?"left":"inner" ) . " join procandam on p58_codandam = p61_codandam
                           					  left join arqproc on arqproc.p68_codproc = protprocesso.p58_codproc
                     				    where $where ) as x
		   	  	   where   x.p68_codproc is null order by x.p58_codproc desc";

          $result = db_query($sql);
			    if(pg_numrows($result) != 0){
			
			      db_fieldsmemory($result,0);
			      $z01_nome=addslashes($z01_nome);

              $sCampoRetorno = $p58_numero . '/' . $p58_ano;

              if ( !empty($oGet->sCampoRetorno) ) {
                
                $sCampoRetorno = $oGet->sCampoRetorno;
                $sCampoRetorno = $$sCampoRetorno;
              }

			      echo "<script>".$funcao_js."('$sCampoRetorno' , '$p58_numcgm' , '$z01_nome',false);</script>";
            
			    } else {
				    echo "<script>".$funcao_js."('','Chave(".$pesquisa_chave.") não Encontrado','',true);</script>";
			    }
			    
        } else {
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
document.form2.chave_p58_codproc.focus();
document.form2.chave_p58_codproc.select();
  </script>
  <?
}
?>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
