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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_pessoal_classe.php");
include("classes/db_rhestagioagenda_classe.php");
include("classes/db_rhestagioresultado_classe.php");
include("classes/db_rhestagioagendadata_classe.php");
include("classes/db_rhinstrucao_classe.php");
include("classes/db_rhestcivil_classe.php");

$oGet                  = db_utils::postMemory($_GET);
$clpessoal             = new cl_pessoal;
$clrhpessoal           = new cl_rhpessoal;
$clrhestagioagenda     = new cl_rhestagioagenda();
$clrhestagioagendadata = new cl_rhestagioagendadata();
$clrhestagioresultado  = new cl_rhestagioresultado();
$clrhinstrucao         = new cl_rhinstrucao();
$clrhestcivil          = new cl_rhestcivil();
$rsEstagio             = $clrhestagioagenda->sql_record($clrhestagioagenda->sql_query($oGet->h57_sequencial));
$oEstagio              = db_utils::fieldsMemory($rsEstagio,0);

$clrotulo              = new rotulocampo;
$clpessoal->rotulo->label();
$clrhpessoal->rotulo->label();

$clrotulo->label('z01_nome');
$clrotulo->label('z01_ender');
$clrotulo->label('z01_munic');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_cgccpf');

$rh01_numcgm   = $oEstagio->rh01_numcgm;
$rh01_regist   = $oEstagio->rh01_regist;
$rh01_numcgm   = $oEstagio->z01_numcgm;
$z01_nome      = $oEstagio->z01_nome;
$rh01_nascp    = explode("-",$oEstagio->rh01_nasc);
$rh01_nasc_dia = $rh01_nascp[2];
$rh01_nasc_mes = $rh01_nascp[1];
$rh01_nasc_ano = $rh01_nascp[0];
$rh01_instru   = $oEstagio->rh01_instru;
$rh01_sexo     = $oEstagio->rh01_sexo;
$rh01_estciv   = $oEstagio->rh01_estciv;
$z01_munic     = $oEstagio->z01_munic;
$z01_ender     = $oEstagio->z01_ender;
$z01_compl     = $oEstagio->z01_compl;
$z01_uf        = $oEstagio->z01_uf;
$z01_numero    = $oEstagio->z01_numero;

$camposResult  = " case when h65_resultado = 'A' then 'APROVADO' else 'REPROVADO' end AS h65_resultado,";
$camposResult .= " h65_pontos, h31_numero||'/'||h31_anousu as h31_numero, h65_observacao";
$rsResultado   = $clrhestagioresultado->sql_record($clrhestagioresultado->sql_query(null,"$camposResult",null,
                 "h65_rhestagioagenda = {$oGet->h57_sequencial}"));
if ($clrhestagioresultado->numrows > 0) {
   $oResultado = db_utils::fieldsMemory($rsResultado,0);
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<style>
.texto {background-color:white}
.selecionados  {background-color:white;
               text-decoration:none;
               border-right:2px outset #2C7AFE;
               border-bottom:1px outset white;
               display:block;
               padding:3px;
               text-align:center;
               cursor:default;
               color:black
              }
.dados{ display:block;
        background-color:#EEEFF2;
        text-decoration:none;
        border-right:3px outset #A6A6A6;
        border-bottom:3px outset #EFEFEF;
        color:black;
        text-align:center;
        cursor:default;
        padding:3px;
      }  
</style>
<script>
function js_marca(obj){

   lista = document.getElementsByTagName("A");
   for (i = 0;i < lista.length;i++){
     if (lista[i].className == 'selecionados' && lista[i].className != ''){
      lista[i].className = 'dados';
     }
   }
   obj.className = 'selecionados';

}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="360" height="">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table width="90%" align="center" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <fieldset>
      <legend><strong>DADOS PESSOAIS</strong></legend>
      <table>
        <tr>
          <td valign="top" rowspan="6">
            <?
            
            db_foto($rh01_numcgm,1,"js_JanelaAutomatica('cgm','$rh01_numcgm')")
            ?>
          </td>
          <td align="right" nowrap title="<?=$Trh01_regist?>">
            <?
            db_ancora("<b>".$RLrh01_regist.":</b>","js_JanelaAutomatica('cgm','$rh01_numcgm')",1);
            ?>
          </td>
          <td align="left" nowrap colspan="5">
            <?
            db_input('rh01_regist',6,$Irh01_regist,true,'text',3)
            ?>
            <?
            db_input('z01_numcgm',6,$Iz01_numcgm,true,'text',3,'')
            ?>
            <?
            db_input('z01_nome',66,$Iz01_nome,true,'text',3,'')
            ?>
          </td>
        </tr>
        <tr>
          <td align="right" nowrap title="<?=$Trh01_nasc?>">
            <?=@$Lrh01_nasc?>
          </td>
          <td align="left" nowrap>
            <?
            db_inputdata('rh01_nasc',$rh01_nasc_dia,$rh01_nasc_mes,$rh01_nasc_ano,true,'text',3)
            ?>
          </td>
          <td align="right" nowrap title="<?=@$Trh01_instru?>">
            <?
            db_ancora(@$Lrh01_instru,"js_pesquisarh01_instru(true);",3);
            ?>
          </td>
          <td align="left" nowrap colspan="3">
            <?
            $result_instru = $clrhinstrucao->sql_record($clrhinstrucao->sql_query_file());
            db_selectrecord("rh01_instru",$result_instru,"",3);
            ?>
          </td>
        </tr>
        <tr>
          <td align="right" nowrap title="<?=@$Trh01_sexo?>">
            <?=@$Lrh01_sexo?>
          </td>
          <td align="left" nowrap> 
            <?
            $arr_sexo = array('M' => 'Masculino','F'=>'Feminino');
            db_select("rh01_sexo",$arr_sexo,true,3,"");
            ?>
          </td>
          <td align="right" nowrap title="<?=@$Trh01_estciv?>">
            <?
            db_ancora(@$Lrh01_estciv,"js_pesquisarh01_estciv(true);",3);
            ?>
          </td>
          <td align="left" nowrap>
            <?
            $result_estciv = $clrhestcivil->sql_record($clrhestcivil->sql_query_file());
            db_selectrecord("rh01_estciv",$result_estciv,"",3);
            ?>
          </td>
        </tr>
        <tr>
          <td align="right" nowrap title="<?=$Tz01_ender?>">
            <?=@$Lz01_ender?>
          </td>
          <td align="left" nowrap colspan="5">
            <?
            $z01_ender.= ', '.$z01_numero.' '.$z01_compl;
            db_input('z01_ender',84,$Iz01_ender,true,'text',3)
            ?>
        </tr>
        <tr>
          <td align="right" nowrap title="<?=$Tz01_munic?>">
            <?=@$Lz01_munic?>
          </td>
          <td align="left" nowrap colspan="5">
            <?
            $z01_munic.= ' / '.$z01_uf;
            db_input('z01_munic',84,$Iz01_munic,true,'text',3)
            ?>
          </td>
        </tr>
      </table>
      </fieldset>
    </td>
  </tr>
  <?
   if (isset($oResultado)){

       echo "<tr>";
       echo "  <td><fieldset><legend><b>dados do Resultado</b></legend>";
       echo "  <table>";
       echo "     <tr>";
       echo "       <td><b>Resultado:</td>";
       echo "        <td width='150' style='background-color:#DEB887;border:1px solid #999999'>{$oResultado->h65_resultado}</td>";
       echo "       <td><b>Portaria:</td>";
       echo "        <td width='100' style='background-color:#DEB887;border:1px solid #999999'>{$oResultado->h31_numero}</td>";
       echo "       <td><b>Pontuação:</td>";
       echo "        <td width='100' style='background-color:#DEB887;border:1px solid #999999'>{$oResultado->h65_pontos}</td>";
       echo "     </tr>";
       echo "     <tr>";
       echo "       <td><b>Observações:</td>";
       echo "        <td width='100%' colspan='6' style='background-color:#DEB887;border:1px solid #999999'>{$oResultado->h65_observacao}</td>";
       echo "     </tr>";
       echo "   </table>";
       echo "   </fieldset> </td></tr>";
   }
  ?>
  <tr>
    <td width=100%'>
    <fieldset><legend><b>Avaliacões</b></legend>
    <table cellspacing='0'>
     <tr>
     <td valign="top" nowrap style='border:1px inset white;background-color:white'>
     <?
      $sSQLRel  = "select h64_sequencial,";
      $sSQLRel .= "       h64_data, ";
      $sSQLRel .= "       h56_sequencial, ";
      $sSQLRel .= "       fc_calculapontosestagio(h64_sequencial,'a') as pontos";
      $sSQLRel .= "  from rhestagioagenda";
      $sSQLRel .= "       inner join rhestagioagendadata on h64_estagioagenda = h57_sequencial";
      $sSQLRel .= "       left outer join rhestagioavaliacao on h56_rhestagioagenda = h64_sequencial";
      $sSQLRel .= " where h57_sequencial={$oGet->h57_sequencial}";
      $rsAval   = $clrhestagioagendadata->sql_record($sSQLRel);
      $iNumRows = $clrhestagioagendadata->numrows;
      for ($iAtual = 0; $iAtual < $iNumRows; $iAtual++){

        $oAval    = db_utils::fieldsMemory($rsAval,$iAtual);
        $dataAval = db_formatar($oAval->h64_data,"d");
        $sOnClick = "onclick='js_marca(this),js_getDadosExame({$oAval->h64_sequencial},\"\");this.blur()'";
        if ($oAval->h56_sequencial == ''){
           $sOnClick = null;
        }
        echo " <a class='dados' {$sOnClick} ><b>{$dataAval} ({$oAval->pontos} pontos) </b></a>";
     }
     ?>
     </td>
     <td  style='border:1px inset white; height:300px;background-color:white;overflow:scroll' width="100%">
     
       <table cellspacing='0' border=0 width="100%">
       
         <tbody id='response' style='height:300px;overflow:scroll' >
         <td>
          &nbsp;
         </td>
       </tbody> 
       </table>
     </td>
    </tr>
   </table>
  </fieldset>
 </td>
</tr>
</center>
</body>
</html>
<script>
function js_getDadosExame(iCodExame,iCodQuesito){
   
   js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
   strJson = '{"method":"getDadosExame","iCodExame":"'+iCodExame+'","iCodQuesito":"'+iCodQuesito+'","refresh":"1"}';

   $('response').innerHTML    = '';

   url     = 'rec4_rpcexame.php';
   oAjax   = new Ajax.Request(
                            url, 
                              {
                               method: 'post', 
                               parameters: 'json='+strJson, 
                               onComplete: js_saida
                              }
                             );

}

function js_saida(oAjax){

    js_removeObj("msgBox");
  
    obj                              = eval("("+oAjax.responseText+")");
    saida = '';
    $('response').innerHTML   = '&nbsp;';

    if (obj.numquesitos > 0) {

      for (i = 0; i < obj.quesitos.length;i++) {
        
        saida +="<tr><td colspan='4'><b>"+js_urldecode(obj.quesitos[i].h51_descr)+"</b></td></tr>";

        if (obj.quesitos[i].questoes) {
          
          for (j = 0; j < obj.quesitos[i].questoes.length; j++) {
          
             idQuestao = obj.quesitos[i].questoes[j].h53_sequencial;
             saida    += "<tr><td class='table_header' colspan=2 style='text-align:left'>";
             saida    += (j+1)+" - <b>"+js_urldecode(obj.quesitos[i].questoes[j].h53_descr)+"</tr>";
             
             if (obj.quesitos[i].questoes[j].numrespostas > 0) {
                 
                for (x = 0; x < obj.quesitos[i].questoes[j].respostas.length; x++) {
                  
                   var resposta      = '';
                   var styleResposta = '';
                    
                   if (obj.quesitos[i].questoes[j].respostadada == obj.quesitos[i].questoes[j].respostas[x].h54_sequencial){
                     styleResposta = "background-color:#FFFFCC";
                   }
                   
                   idResposta = obj.quesitos[i].questoes[j].respostas[x].h54_sequencial;
                   saida     += "<tr style='"+styleResposta+"' id='tr"+idResposta+"'><td style='padding-left:15px;text-align:justify;' class='linhagrid'>"+(x+1)+") - "+js_urldecode(obj.quesitos[i].questoes[j].respostas[x].h54_descr)+"</td>";
                   saida     += "<td class='linhagrid' style='text-align:left'>";
                   saida     += js_urldecode(obj.quesitos[i].questoes[j].respostas[x].h52_descr); 
                   saida     += "(";
                   saida     += js_urldecode(obj.quesitos[i].questoes[j].respostas[x].h52_pontos)+") </td></tr>";
                   
                }
                
             }
             
             if (obj.h50_confobs == 2 || obj.h50_confobs == 3) {
               
                saida += "<tr><td colspan='2'><u><b>Observacões:</b></u>"+js_urldecode(obj.quesitos[i].questoes[j].obsquestao)+"</td></tr>";
                saida += "<tr><td colspan='2'><u><b>Recomendações:</b></u>"+js_urldecode(obj.quesitos[i].questoes[j].obsrec)+"</td></tr>";
                
             }
          
          }
          
        }
        
        
        if (obj.h50_confobs == 1 || obj.h50_confobs == 3){
           
           saida += "<tr><td colspan='2'><u><b>Observacões:</b></u>"+js_urldecode(obj.quesitos[i].obs)+"</td></tr>";
           saida += "<tr><td style='border-bottom:1px solid black' colspan='2'><u><b>Recomendações:</b></u>"+js_urldecode(obj.quesitos[i].rec)+"<br><br></td></tr>";
        }
        
      }
      
    }
    
    $('response').innerHTML = saida;
    
}

function js_urldecode(str){

  str = str.replace(/\+/g," ");
  str = unescape(str);
  return str;

}

</script>