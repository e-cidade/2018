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
require("libs/db_utils.php");
include("dbforms/db_funcoes.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_termo_classe.php");

$cltermo   = new cl_termo();
$oGet    = db_utils::postmemory($_GET);
//exit;

/* select na termo */

//echo ($cltermo->sql_query_consulta(null,"cgm.z01_nome as z01_nome,resp.z01_nome as nomecontr,termo.*",null," v07_parcel = {$oGet->parcelamento}"));

$rsTermo   = $cltermo->sql_record($cltermo->sql_query_consulta(null,"cgm.z01_numcgm as z01_numcgm,cgm.z01_nome,resp.z01_nome as nomecontr,resp.z01_numcgm as cgmcontr,termo.*",null," v07_parcel = {$oGet->parcelamento}"));

if ( $cltermo->numrows > 0 ) {
  $oTermo  = db_utils::fieldsMemory($rsTermo,0);    
}else{
  db_msgbox("Parcelamento não encontrado");  
  echo " <script> parent.db_iframe_consultaparc".$oGet->parcelamento.".hide(); </script>";
  exit;
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.texto {background-color:white}
.selecionados  {background-color:white;
               text-decoration:none;
               border-right:2px outset #2C7AFE;
               border-bottom:1px outset white;
               display:block;
               padding:3px;
               text-align:center;
               color:black
              }
.dados{ display:block;
        background-color:#CCCCCC;
        text-decoration:none;
        border-right:3px outset #A6A6A6;
        border-bottom:3px outset #EFEFEF;
        color:black;
        text-align:center;
        padding:3px;
      }  
</style>
<script>
function js_marca(obj){

   lista = document.getElementsByTagName("A");

   for (i = 0;i < lista.length;i++){

     if (lista[i].className == 'selecionados' && lista[i].className != '') {
       lista[i].className = 'dados';
     }

   }

   obj.className = 'selecionados';

}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" id='teste'>
<center>
<table width='100%' cellspacing=0>
<tr>
<td colspan='2'>
<fieldset>
  <legend><b>Dados do Parcelamento - <?=$oGet->parcelamento?>  </b></legend>
    <table border='0'>
      <tr>
          <td><b>Código Parcelamento :</b>                             </td>
          <td class='texto'><?=$oTermo->v07_parcel?>                   </td>
          <td align='right'><b>Data Parcelamento :</b>                 </td>
          <td class='texto'><?=db_formatar($oTermo->v07_dtlanc,'d') ?> </td>
      </tr>
      <tr>
          <td><b>Total de Parcelas </b>                                </td>
          <td class='texto'><?=$oTermo->v07_totpar?>                   </td>
          <td align='right'><b>Valor Total Parcelado :</b>             </td>
          <td class='texto'><?=db_formatar($oTermo->v07_valor,'f')?>   </td>
      </tr>
      <tr>
          <td><b>Primeiro vencimento : </b>                            </td>
          <td class='texto'><?=db_formatar($oTermo->v07_datpri,'d')?>  </td>
          <td align='right'><b>Valor da parcela :</b>                  </td>
          <td class='texto'><?=db_formatar($oTermo->v07_vlrpar,'f')?>  </td>
      </tr>    
      <tr>
          <td>
          <?
            db_ancora("<b>Contribuinte:</b>","js_pesquisaNome({$oTermo->cgmcontr});",1);
          ?>
          </td>
          <td class='texto'><?=$oTermo->nomecontr?></td>
          <td align='right'>
          <?
           db_ancora("<b>Responsável :</b>","js_pesquisaNome({$oTermo->z01_numcgm});",1);
          ?>
          </td>
          <td class='texto'><?=$oTermo->z01_nome?>   </td>
      </tr>    
      <tr>
        <td><b>Historico :</b></td>
        <td colspan="3" class='texto'><?=$oTermo->v07_hist?>   </td>
      </tr>    
    </table>
</fieldset>
</td>
</tr>

<tr>
<td colspan='2'>
  <fieldset>
   <legend><b>Detalhamento : </b></legend>
     <table width='100%'>
 
       <tr>
         <td width='20%' valign='top' height='100%' rowspan='2'>
           <a class='selecionados' onclick='js_marca(this);this.blur()' href='div3_consultaParcOrigem.php?parcelamento=<?=$oGet->parcelamento;?>'    target='dados'><b> Origem     </b></a> 
           <a class='dados'        onclick='js_marca(this);this.blur()' href='div3_consultaParcParcelas.php?parcelamento=<?=$oGet->parcelamento;?>'  target='dados'><b> Parcelas   </b></a>
           <a class='dados'        onclick='js_marca(this);this.blur()' href='div3_consultaParcExercicios.php?parcelamento=<?=$oGet->parcelamento;?>'target='dados'><b> Exercícios </b></a> 
           <a class='dados'        onclick='js_marca(this);this.blur()' href='div3_consultaParcEnvolvidos.php?parcelamento=<?=$oGet->parcelamento;?>'target='dados'><b> Envolvidos </b></a> 
         </td>
         <td valign='top' height='100%' style='border:1px inset white'>
           <iframe height='300' name='dados' frameborder='0' width='100%' src='div3_consultaParcOrigem.php?parcelamento=<?=$oGet->parcelamento;?>' style='background-color:#CCCCCC'>
           </iframe>
         </td>
       </tr>
 
     </table>
  </fieldset>
</td>
</tr>
</table>
<center>
  <input type='button' value='Voltar'  onclick='parent.db_iframe_consultaparc<?=$oGet->parcelamento?>.hide()'>
</center>
</body>
</html>
<script>
function js_pesquisaNome(codigoOrigem){

  var arquivo    = '';
  var parametros = '';
  var nomeIframe = '';

  arquivo    = 'prot3_conscgm002.php';
  parametros = 'numcgm='+codigoOrigem+'&fechar=db_iframe_consultacgm';    
  nomeIframe = 'db_iframe_consultacgm';

  js_OpenJanelaIframe('top.corpo',nomeIframe,arquivo+'?'+parametros,'Detalhes da Pesquisa',true);

}
</script>