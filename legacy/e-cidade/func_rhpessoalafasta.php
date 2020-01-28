<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_sql.php");
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhpessoal_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrhpessoal = new cl_rhpessoal;
$clgersubsql = new cl_gera_sql_folha;
$clrotulo    = new rotulocampo;

$clrhpessoal->rotulo->label("rh01_regist");
$clrhpessoal->rotulo->label("rh01_numcgm");
$clrotulo->label("z01_nome");

if( isset($valor_testa_rescisao) ){

  $chave_rh01_regist = $valor_testa_rescisao;
  $retorno           = db_alerta_dados_func( $testarescisao, $valor_testa_rescisao, db_anofolha(), db_mesfolha() );

  if($retorno != ""){
    db_msgbox($retorno);
  }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
    function js_recebe_click(value){

      obj = document.createElement('input');
      obj.setAttribute('type','hidden');
      obj.setAttribute('name','funcao_js');
      obj.setAttribute('id','funcao_js');
      obj.setAttribute('value','<?=$funcao_js?>');
      document.form2.appendChild(obj);

      obj = document.createElement('input');
      obj.setAttribute('type','hidden');
      obj.setAttribute('name','valor_testa_rescisao');
      obj.setAttribute('id','valor_testa_rescisao');
      obj.setAttribute('value',value);
      document.form2.appendChild(obj);

      document.form2.submit();
    }
  </script>
  <?
}
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Trh01_regist?>">
              <?=$Lrh01_regist?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("rh01_regist",10,$Irh01_regist,true,"text",4,"","chave_rh01_regist");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Trh01_numcgm?>">
              <?=$Lrh01_numcgm?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("rh01_numcgm",10,$Irh01_numcgm,true,"text",4,"","chave_rh01_numcgm");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
            <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap colspan='3'>
            <?
            db_input("z01_nome",80,$Iz01_nome,true,"text",4,"","chave_z01_nome");
	        ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" />
              <input name="limpar" type="reset" id="limpar" value="Limpar" />
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhpessoal.hide();" />
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?

      $anofolha = db_anofolha();
      $mesfolha = db_mesfolha();

      $dbwhere  = " and rh02_anousu = ".$anofolha." and rh02_mesusu = ".$mesfolha;
     	$dbwhere .= " and rh02_instit = ".db_getsession("DB_instit");

      if(isset($afasta)){
      	$dbwhere .= " and rh30_vinculo = 'A' ";
      }

      if(isset($lativos)){
        $dbwhere .= " and rh05_recis is null ";
      }

      if(!isset($pesquisa_chave)){

        $campos1  = " rhpessoal.rh01_regist, ";
        $campos1 .= " rhpessoal.rh01_numcgm, ";
        $campos1 .= " cgm.z01_nome,          ";
        $campos1 .= " rhlota.r70_codigo,     ";
        $campos1 .= " rhlota.r70_descr,      ";
        $campos1 .= " rhfuncao.rh37_funcao,  ";
        $campos1 .= " rhfuncao.rh37_descr,   ";
        $campos1 .= " r30_perai,             ";
        $campos1 .= " r30_per1i,             ";
        $campos1 .= " rh05_recis,            ";
        $campos1 .= " r45_dtafas,            ";
        $campos1 .= " r45_dtreto,            ";
        $campos1 .= " rh02_anousu as anousu, ";
        $campos1 .= " rh02_mesusu as mesusu  ";

        $campos2  = " distinct on (x.rh01_regist) x.rh01_regist,";
        $campos2 .= " x.rh01_numcgm,                            ";
        $campos2 .= " z01_nome,                                 ";
        $campos2 .= " r70_codigo,                               ";
        $campos2 .= " r70_descr,                                ";
        $campos2 .= " rh37_funcao,                              ";
        $campos2 .= " rh37_descr,                               ";
        $campos2 .= " r30_perai  as db_r30_perai,               ";
        $campos2 .= " r30_per1i  as db_r30_per1i,               ";
        $campos2 .= " rh05_recis as db_rh05_recis,              ";
        $campos2 .= " r45_dtafas as db_r45_dtafas,              ";
        $campos2 .= " r45_dtreto as db_r45_dtreto               ";

        $clgersubsql->subsqlano = "anousu";
        $clgersubsql->subsqlmes = "mesusu";
        $clgersubsql->subsqlreg = "rh01_regist";

        if(!isset($chave_z01_nome)){
          $chave_z01_nome = '';
        }

        if(!isset($chave_rh01_regist)){
          $chave_rh01_regist = '';
        }

        if(!isset($chave_rh01_numcgm)){
          $chave_rh01_numcgm = '';
        }

        if(!isset($instit)){
          $instit = '';
        }

        $repassa = array( "chave_z01_nome"    => $chave_z01_nome,
                          "chave_rh01_regist" => $chave_rh01_regist,
                          "chave_rh01_numcgm" => $chave_rh01_numcgm,
                          "rh02_instit"       => $instit );

        $dbwhereValida  = " and rh02_anousu = ".$anofolha." and rh02_mesusu = ".$mesfolha;
        $dbwhereValida .= " and rh02_instit = ".db_getsession("DB_instit");

        if(isset($chave_rh01_regist) && (trim($chave_rh01_regist)!="") ){

           $sql             = $clrhpessoal->sql_query_afasta(null,$campos1,"rh01_regist,r30_perai desc"," rh01_regist = $chave_rh01_regist $dbwhere");
           $sqlValidaAtivos = $clrhpessoal->sql_query_afasta(null,$campos1,"rh01_regist,r30_perai desc"," rh01_regist = $chave_rh01_regist $dbwhereValida");
        }else if(isset($chave_rh01_numcgm) && (trim($chave_rh01_numcgm)!="") ){

	         $sql             = $clrhpessoal->sql_query_afasta(null,$campos1,"rh01_numcgm,r30_perai desc"," rh01_numcgm = $chave_rh01_numcgm $dbwhere ");
           $sqlValidaAtivos = $clrhpessoal->sql_query_afasta(null,$campos1,"rh01_numcgm,r30_perai desc"," rh01_numcgm = $chave_rh01_numcgm $dbwhereValida");
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){

	         $sql             = $clrhpessoal->sql_query_afasta(null,$campos1,"z01_nome,r30_perai desc"," z01_nome like '$chave_z01_nome%' $dbwhere ");
           $sqlValidaAtivos = $clrhpessoal->sql_query_afasta(null,$campos1,"z01_nome,r30_perai desc"," z01_nome like '$chave_z01_nome%' $dbwhereValida");
        }

	      if(isset($sql) && trim($sql) != ""){

          $clgersubsql->subsql = $sql;
	        $sql = $clgersubsql->gerador_sql("", $anofolha, $mesfolha, "x.rh01_regist", null, $campos2);

          /**
           * Se for realizado busca por algum filtro, é efetuada a verificação se o servidor está rescindido.
           */
          if ( (isset($chave_rh01_regist) || isset($chave_rh01_numcgm) || isset($chave_z01_nome)) && $lativos){

            $rsServidores = db_query($sqlValidaAtivos);

            if (pg_num_rows($rsServidores) < 2) {

              $oServidor    = db_utils::fieldsMemory($rsServidores, 0);
              $retorno = db_alerta_dados_func($testarescisao,$oServidor->rh01_regist, db_anofolha(), db_mesfolha());
              
              if ($retorno != "") {
                db_msgbox($retorno);
              }
            }
          }

          db_lovrot($sql,15,"()","",(isset($testarescisao) && !isset($valor_testa_rescisao) ? "js_recebe_click|rh01_regist" : $funcao_js),"","NoMe",$repassa);
	      }

      }else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $result = $clrhpessoal->sql_record($clrhpessoal->sql_query_afasta(null,"*","r30_perai desc"," rh01_regist = $pesquisa_chave $dbwhere"));

          if($clrhpessoal->numrows!=0){
            db_fieldsmemory($result,0);

	          if(isset($testarescisao)){

              $retorno = db_alerta_dados_func($testarescisao,$pesquisa_chave,db_anofolha(), db_mesfolha());
              if($retorno != ""){
                db_msgbox($retorno);
              }
	          }

            echo "<script>".$funcao_js."('$z01_nome','$r45_dtafas','$r45_dtreto',false);</script>";
          }else{

            $retorno = db_alerta_dados_func($testarescisao,$pesquisa_chave,db_anofolha(), db_mesfolha());

            if(empty($retorno)){
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','','',true);</script>";
            } else {
              echo "<script>".$funcao_js."('','','',true);</script>";
              db_msgbox($retorno);
            }  
          }
        }else{
	       echo "<script>".$funcao_js."('','','',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>