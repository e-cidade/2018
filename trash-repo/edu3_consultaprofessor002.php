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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_libpessoal.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rechumano_classe.php");
include("classes/db_regenciahorario_classe.php");
include("classes/db_rechumanohoradisp_classe.php");
include("classes/db_telefonerechumano_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_cgm_classe.php");
db_postmemory($HTTP_POST_VARS);
$clrechumano         = new cl_rechumano;
$clrhpessoal         = new cl_rhpessoal;
$cltelefonerechumano = new cl_telefonerechumano;
$clregenciahorario   = new cl_regenciahorario;
$clrechumanohoradisp = new cl_rechumanohoradisp;
$clcgm               = new cl_cgm;
$clrechumano->rotulo->label();
$clcgm->rotulo->label();
$clrhpessoal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_numcgm");
$clrotulo->label("z01_nome");
$db_opcao = 1;
unset($_SESSION["sess_corhorario"]);
unset($_SESSION["sess_cordisp"]);

if (isset($chavepesquisa)) {
	
  $escola = db_getsession("DB_coddepto");
  include("funcoes/db_func_rechumanonovo.php");
  $sWhere = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end = $chavepesquisa";
  $result = $clrechumano->sql_record($clrechumano->sql_query_escola("",
                                                                    "distinct ".$camposrechumano,
                                                                    "",
                                                                    $sWhere
                                                                   )
                                    );
  db_fieldsmemory($result,0);
  
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
.cabec{
 text-align: center;
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
 font-weight: bold;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td valign="top" bgcolor="#CCCCCC">
    <table border="0" width="100%" cellspacing="0" cellpading="0" bgcolor="#f3f3f3">
     <tr>
      <td>
       <fieldset style="background:#f3f3f3;padding:0px;border:2px solid #000000"><legend class="cabec"><b>Nome</b></legend>
       <table border="0" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="0">
        <tr>
         <td style="font-size:18px;font-weight:bold;font-family:verdana;">
          &nbsp;&nbsp;CGM: <?=$z01_numcgm?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=$z01_nome?>
         </td>
         <td align="right">
          <b>Matrícula/CGM:</b>
          <select id="cod_matricula" name="cod_matricula" onchange="js_trocaMatricula(this.value,<?=$chavepesquisa?>);">
           <option value="">TODOS</option>
           <?
           for ($x = 0; $x < $clrechumano->numrows; $x++) {
           	
             db_fieldsmemory($result,$x);
             ?>
             <option value="<?=$ed20_i_codigo?>" <?=$ed20_i_codigo==@$cod_matricula?"selected":""?>>
              <?=$ed20_i_tiposervidor==1?"Matrícula":"CGM"?>-<?=$identificacao?></option>
             <?
             
           }
           ?>
          </select>
         </td>
         <td align="right">
          <input type="button" value="Imprimir" onclick="js_imprimir(<?=$chavepesquisa?>)">&nbsp;&nbsp;
         </td>
        </tr>
       </table>
       </fieldset>
      </td>
     </tr>
     <tr>
      <td>
       <table border="0" width="100%" cellspacing="0" cellpading="0">
        <tr>
         <td width="16%">
          <fieldset style="height:139px;background:#f3f3f3;padding:0px;border:4px outset #000000">
            <legend class="cabec"><b>Foto</b></legend>
          <table border="0" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="0">
           <tr>
            <td align="center">
             <?
             db_foto($rh01_numcgm,1,"")
             ?>
            </td>
           </tr>
          </table>
          </fieldset>
         </td>
         <td valign="top">
          <fieldset style="background:#f3f3f3;border:2px solid #000000"><legend class="cabec">
             <b>Dados Pessoais</b></legend>
          <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="4">
           <tr>
            <td>
             <?=$Lrh01_nasc?> <?=db_formatar($rh01_nasc,'d')?>
             &nbsp;&nbsp;
             <?=$Lrh01_natura?> <?=$rh01_natura==""?"Não Informado":$rh01_natura?>
            </td>
           </tr>
           <tr>
            <td>
             <?=$Lz01_ender?> <?=$z01_ender==""?"Não Informado":$z01_ender?>
             &nbsp;&nbsp;
             <?=$Lz01_numero?> <?=$z01_numero==""?"Não Informado":$z01_numero?>
            </td>
           </tr>
           <tr>
            <td>
             <?=$Lz01_bairro?> <?=$z01_bairro==""?"Não Informado":$z01_bairro?>
             &nbsp;&nbsp;
             <?=$Lz01_compl?> <?=$z01_compl?>
            </td>
           </tr>
           <tr>
            <td>
             <?=$Lz01_munic?> <?=$z01_munic==""?"Não Informado":$z01_munic?>
             &nbsp;&nbsp;
             <?=$Lz01_uf?> <?=$z01_uf==""?"Não Informado":$z01_uf?>
             &nbsp;&nbsp;
             <?=$Lz01_cep?> <?=$z01_cep==""?"Não Informado":$z01_cep?>
            </td>
           </tr>
           <tr>
            <td>
             <?=$Lz01_sexo?> <?=$z01_sexo=="M"?"Masculino":"Feminino"?>
             &nbsp;&nbsp;
             <?=$Lz01_estciv?>
             <?
             if ($z01_estciv == 1) {
               echo "Solteiro";
             } else if ($z01_estciv == 2) {
               echo "Casado";
             } else if ($z01_estciv == 3) {
               echo "Viúvo";
             } else if ($z01_estciv == 4) {
               echo "Divorciado";
             }
             ?>
            </td>
           </tr>
           <tr>
            <td>
             <?=$Lz01_telef?>
             <?
             $result = $cltelefonerechumano->sql_record($cltelefonerechumano->sql_query("",
                                                                                        "*",
                                                                                        "",
                                                                                        "ed30_i_rechumano = $chavepesquisa"
                                                                                       )
                                                       );
             if ($cltelefonerechumano->numrows > 0) {
             	
               for ($x = 0; $x < $cltelefonerechumano->numrows; $x++) {
               	
                 db_fieldsmemory($result,$x);
                 $ed30_i_ramal = $ed30_i_ramal==0?"":$ed30_i_ramal;
                 echo $ed13_c_descr." - ".$ed30_i_numero."&nbsp;&nbsp;&nbsp;";
               }
               
             } else {
               echo "Não informado";
             }
             ?>
            </td>
           </tr>
          </table>
          </fieldset>
         </td>
        </tr>
        <tr>
         <td valign="top">
          <table border="0" width="100%">
           <tr>
            <td id="menu1" bgcolor="#444444" style="border:2px outset #f3f3f3" 
                onmouseover="document.getElementById('menu1').style.border='2px inset #f3f3f3'" 
                onmouseout="document.getElementById('menu1').style.border='2px outset #f3f3f3'">
             <?
             if (isset($cod_matricula)) {
               $destino = "&cod_matricula=$cod_matricula";
             } else {
               $destino = "";
             }
             ?>
             <a style="color:#DEB887;font-weight:bold;" 
                href="edu3_consultaprofessor003.php?chavepesquisa=<?=$chavepesquisa?>&evento=1<?=$destino?>"  
                target="iframe_dados">
             Dados Admissionais
             </a>
            </td>
           </tr>
           <tr>
            <td id="menu2" bgcolor="#444444" style="border:2px outset #f3f3f3" 
                onmouseover="document.getElementById('menu2').style.border='2px inset #f3f3f3'" 
                onmouseout="document.getElementById('menu2').style.border='2px outset #f3f3f3'">
             <a style="color:#DEB887;font-weight:bold;" 
                href="edu3_consultaprofessor003.php?chavepesquisa=<?=$chavepesquisa?>&evento=2<?=$destino?>"  
                target="iframe_dados">
             Documentos
             </a>
            </td>
           </tr>
           <tr>
            <td id="menu3" bgcolor="#444444" style="border:2px outset #f3f3f3" 
                onmouseover="document.getElementById('menu3').style.border='2px inset #f3f3f3'" 
                onmouseout="document.getElementById('menu3').style.border='2px outset #f3f3f3'">
             <a style="color:#DEB887;font-weight:bold;" 
                href="edu3_consultaprofessor003.php?chavepesquisa=<?=$chavepesquisa?>&evento=3<?=$destino?>"  
                target="iframe_dados">
             Escolas
             </a>
            </td>
           </tr>
           <tr>
            <td id="menu5" bgcolor="#444444" style="border:2px outset #f3f3f3" 
                onmouseover="document.getElementById('menu4').style.border='2px inset #f3f3f3'" 
                onmouseout="document.getElementById('menu5').style.border='2px outset #f3f3f3'">
             <a style="color:#DEB887;font-weight:bold;" 
                href="edu3_consultaprofessor003.php?chavepesquisa=<?=$chavepesquisa?>&evento=5<?=$destino?>"  
                target="iframe_dados">
             Disponibilidade
             </a>
            </td>
           </tr>
           <tr>
            <td id="menu4" bgcolor="#444444" style="border:2px outset #f3f3f3" 
                onmouseover="document.getElementById('menu4').style.border='2px inset #f3f3f3'" 
                onmouseout="document.getElementById('menu4').style.border='2px outset #f3f3f3'">
             <a style="color:#DEB887;font-weight:bold;" 
                href="edu3_consultaprofessor003.php?chavepesquisa=<?=$chavepesquisa?>&evento=4<?=$destino?>"  
                target="iframe_dados">
             Horários
             </a>
            </td>
           </tr>
           <tr>
            <td id="menu6" bgcolor="#444444" style="border:2px outset #f3f3f3" 
                onmouseover="document.getElementById('menu6').style.border='2px inset #f3f3f3'" 
                onmouseout="document.getElementById('menu6').style.border='2px outset #f3f3f3'">
             <a style="color:#DEB887;font-weight:bold;" 
                href="edu3_professormovimentacao003.php?cgm=<?=$chavepesquisa?><?=$destino?>"  
                target="iframe_dados">
             		Movimentação
             </a>
            </td>
           </tr>
          </table>
         </td>
         <td valign="top">
          <iframe name="iframe_dados" src="" frameborder="0" width="99%" height="1000"></iframe>
         </td>
        </tr>
       </table>
      </td>
     </tr>
    </table>
  </td>
 </tr>
</table>
</body>
</html>
<script>
function js_imprimir(chave) {
	
  jan = window.open('edu2_professor002.php?professor='+chave,'',
		            'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
  
}

function js_trocaMatricula(matricula,cgm) {
	
  if (matricula == "") {
    location.href = "edu3_consultaprofessor002.php?chavepesquisa="+cgm;
  } else {
    location.href = "edu3_consultaprofessor002.php?chavepesquisa="+cgm+"&cod_matricula="+matricula;
  }
}

</script>
<?
$array_cores     = array("#FFCC99","#CCCCFF","#99FFCC","#CCFF66","#CC9933","#FF99FF","#996699","#66CC99","#FFCCCC","#9999FF");
$sess_corhorario = array();
$sess_cordisp    = array();
$sWhere          = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end = $chavepesquisa and ed58_ativo is true  ";
$result1         = $clregenciahorario->sql_record($clregenciahorario->sql_query("",
                                                                                "DISTINCT ed18_i_codigo,ed18_c_nome",
                                                                                "ed18_c_nome",
                                                                                $sWhere
                                                                               )
                                                 );
if ($clregenciahorario->numrows > 0) {
	
  for ($y = 0; $y < $clregenciahorario->numrows; $y++) {
  	
    db_fieldsmemory($result1,$y);
    $sess_corhorario[$ed18_i_codigo] = $array_cores[$y];
    
  }
  @session_register("sess_corhorario");
}

$sWhere  = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end = $chavepesquisa";
$result1 = $clrechumanohoradisp->sql_record($clrechumanohoradisp->sql_query("",
                                                                            "DISTINCT ed18_i_codigo,ed18_c_nome",
                                                                            "ed18_c_nome",
                                                                            $sWhere
                                                                           )
                                           );
if ($clrechumanohoradisp->numrows > 0) {
	
  for ($y = 0; $y < $clrechumanohoradisp->numrows; $y++) {
  	
    db_fieldsmemory($result1,$y);
    $sess_cordisp[$ed18_i_codigo] = $array_cores[$y];
    
  }
  @session_register("sess_cordisp");
}

?>
<!--<table style='position:absolute;top:600px'>
 <tr>
 <?
 $array_cores = array("#FFCC99","#CCCCFF","#99FFCC","#CCFF66","#CC9933","#FF99FF","#996699","#66CC99","#FFCCCC","#9999FF");
 for ($y = 0; $y < count($array_cores); $y++) {
   echo "<td width='30' heigth='30' bgcolor='".$array_cores[$y]."'>&nbsp;</td>";
 }
 ?>
 </tr>
</table>-->