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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_saniatividade_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_sanitario_classe.php");
require_once("classes/db_sanibaixa_classe.php");
require_once("classes/db_sanibaixaproc_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clsaniatividade    = new cl_saniatividade;
$clsanibaixa        = new cl_sanibaixa;
$clsanibaixaproc    = new cl_sanibaixaproc;
$db_botao           = false;
$cliframe_seleciona = new cl_iframe_seleciona;
$clsanitario        = new cl_sanitario;


$clsaniatividade->rotulo->label();
$clsanibaixa->rotulo->label();
$clsanibaixaproc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("y80_numcgm");
$clrotulo->label("q03_descr");
$clrotulo->label("p58_requer");
$clrotulo->label("p58_codproc");
$clrotulo->label("p58_numero");

$sqlerro = false;

if(isset($HTTP_POST_VARS["db_opcao"]) && $db_opcao == "Baixar"){

  if(isset($chaves) && $chaves != ""){
    db_inicio_transacao();
    $chaves = split("#",$chaves);
    $db_opcao = 2;
    $sqlqtativ = "select * from saniatividade
					where y83_codsani = $y83_codsani and y83_dtfim is null";
    $resultqtativ = pg_query($sqlqtativ);
    $linhasqtativ = pg_num_rows($resultqtativ);
    $linhaschave = sizeof($chaves);


    // ******** se for baixar todas as atividades do alvara, então baixa o alvara tambem**********
    if($linhasqtativ == $linhaschave){
       
      //****************** NORMAL ****************************
      //para permitir ou não a baixa de sanitário que possuem débitos.(q60_sanbaixadiv da parissqn)
      //q60_sanbaixadiv =0 - não permite
      //q60_sanbaixadiv =1 - permite

      $sqlpar = "select * from parfiscal";
      $resultpar = pg_query($sqlpar);
      $linhaspar = pg_num_rows($resultpar);
      if($linhaspar>0){
        db_fieldsmemory($resultpar,0);
      }
      if(($y81_oficio == 'f')&& ($y32_sanbaixadiv==0)){
         
        $sqlinscr = "select * from sanitarioinscr where y18_codsani = $y83_codsani";
        $resultinscr = pg_query($sqlinscr);
        $linhasinscr = pg_num_rows($resultinscr);
        if($linhasinscr>0){
          db_fieldsmemory($resultinscr,0);
          $sqldebito = "
		      select arrecad.k00_numpre 
				from arreinscr 
				inner join issbase on issbase.q02_inscr = arreinscr.k00_inscr 
				inner join cgm on cgm.z01_numcgm = issbase.q02_numcgm 
				inner join arrecad on arrecad.k00_numpre = arreinscr.k00_numpre 
				inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo 
				where  k00_inscr= $y18_inscr 
				   and k00_dtvenc < current_date
				   and k03_tipo not in (2,9,19)";
          $resultdebito = pg_query($sqldebito);
          $linhasdebito = pg_num_rows($resultdebito);
          if($linhasdebito>0){
            $erro_msg = "Não permite baixa de alvara com debitos. (Inscrição: $y18_inscr)";
            $sqlerro = true;
          }else{
            // verificar se tem debitos do tipo 3 vencidos
            $sqldebito3 = "
		      select arrecad.k00_numpre 
				from arreinscr 
				inner join issbase on issbase.q02_inscr = arreinscr.k00_inscr 
				inner join cgm on cgm.z01_numcgm = issbase.q02_numcgm 
				inner join arrecad on arrecad.k00_numpre = arreinscr.k00_numpre 
				inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo 
				where  k00_inscr= $y18_inscr 
				   and k00_dtvenc < current_date
				   and k03_tipo = 3";
            $resultdebito3 = pg_query($sqldebito3);
            $linhasdebito3 = pg_num_rows($resultdebito3);
            if($linhasdebito3>0){
              $erro_msg = "Não permite baixa de alvara com debitos. (Inscrição: $y18_inscr)";
              $sqlerro = true;
            }

          }
        }

      }

      //**********************************************
       
      if($sqlerro == false){
         
        $clsanitario->y80_dtbaixa= $y83_dtfim_ano."-".$y83_dtfim_mes."-".$y83_dtfim_dia;
        $clsanitario->y80_codsani=$y83_codsani;
        $clsanitario->alterar($y83_codsani);
        if($clsanitario->erro_status==0){
          $sqlerro= true;
          $erro_msg ="sanitario".$clsanitario->erro_msg;
        }
      }

    }


    if($sqlerro == false){
      for($i=0;$i<sizeof($chaves);$i++){
        $seq = str_replace("-","",strstr($chaves[$i],"-"));
         
        if ($sqlerro==false){
          $clsaniatividade->y83_databx = $y83_dtfim_ano."-".$y83_dtfim_mes."-".$y83_dtfim_dia;
          $clsaniatividade->y83_seq = $seq;
          $clsaniatividade->alterar($y83_codsani,$seq);
          $clsanibaixa->y81_obs = $y81_obs." ";
          $clsanibaixa->y81_oficio = $y81_oficio;
          $clsanibaixa->y81_data = $y83_dtfim_ano."-".$y83_dtfim_mes."-".$y83_dtfim_dia;
          $clsanibaixa->y81_seq = $seq;
          $clsanibaixa->y81_codsani = $y83_codsani;
          $clsanibaixa->incluir($y83_codsani,$seq);
          if($clsanibaixa->erro_status==0){
            $sqlerro  = true;
            $erro_msg = "sanibaixa".$clsanibaixa->erro_msg;
            break;

          }
        }
        if($p58_codproc!=""){
          if ($sqlerro==false){
            $clsanibaixaproc->y82_codproc = $p58_codproc;
            $clsanibaixaproc->y82_codsani = $y83_codsani;
            $clsanibaixaproc->y82_seq = $seq;
            $clsanibaixaproc->incluir($y83_codsani,$seq);
            if($clsanibaixaproc->erro_status==0){
              $sqlerro= true;
              $erro_msg = "sanibaixaproc".$clsanibaixaproc->erro_msg;
              break;
            }
          }
        }



      }// do for
    }

    /*
     $result = $clsanitario->sql_record($clsanitario->sql_query("","*",""," y80_codsani = $y83_codsani"));
     if($clsanitario->numrows > 0){
     $clsaniatividade->sql_record($clsaniatividade->sql_query("","","*",""," y83_codsani = $y83_codsani"));// and y83_dtfim is null"));
     db_fieldsmemory($result,0);
     if($y80_dtbaixa == "" && $clsaniatividade->numrows == 0){
     if ($sqlerro==false){
     $clsanitario->y80_dtbaixa= $y83_dtfim_ano."-".$y83_dtfim_mes."-".$y83_dtfim_dia;
     $clsanitario->y80_codsani=$y83_codsani;
     $clsanitario->alterar($y83_codsani);
     if($clsanitario->erro_status==0){
     $sqlerro= true;
     $erro_msg ="sanitario".$clsanitario->erro_msg;
     }
     }
     }
     }
     */
    if($sqlerro==false){
      $clsaniatividade->erro(true,false);
    }
    //db_msgbox($erro_msg );
    db_fim_transacao($sqlerro);
    if($sqlerro==false){
      db_redireciona("fis4_baixasaniatividade001.php?y83_codsani=$y83_codsani");
    }
  }else{
    db_redireciona("fis4_baixasaniatividade001.php?erro=$y83_codsani");
  }
}
/*elseif(isset($y83_codsani) && $y83_codsani != ""){
 $result = $clsaniatividade->sql_record($clsaniatividade->sql_query("","","*",""," y83_codsani = $y83_codsani"));// and y83_dtfim is null"));
 if($clsaniatividade->numrows > 0){
 db_fieldsmemory($result,0);
 $db_botao = true;
 }else{
 $result = $clsanitario->sql_record($clsanitario->sql_query("","*",""," y80_codsani = $y83_codsani"));
 if($clsanitario->numrows > 0){
 db_fieldsmemory($result,0);
 if($y80_dtbaixa == ""){
 db_msgbox("akiiiiiiiiii");

 $dia = date("d",db_getsession("DB_datausu"));
 $mes = date("m",db_getsession("DB_datausu"));
 $ano = date("Y",db_getsession("DB_datausu"));
 $clsanitario->y80_dtbaixa= $ano."-".$mes."-".$dia;
 $clsanitario->y80_codsani=$y83_codsani;
 $clsanitario->alterar($y83_codsani);
 }
 }
 }
 }
 */

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript"src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"marginheight="0" onLoad="a=1" style="margin-top: 25px;">

<center>
<fieldset style="width: 600px;">
  <legend>
    <strong>Baixa de Atividade</strong>
  </legend>
  
  <form name="form1" method="post" action="">
    <table border="0">
      <tr>
        <td nowrap title="<?=@$Ty83_codsani?>">
          <?
            db_ancora(@$Ly83_codsani,"js_pesquisay83_codsani(true);",1);
          ?>
        </td>
        <td>
          <?
            db_input('y83_codsani',10,$Iy83_codsani,true,'text',1," onchange='js_pesquisay83_codsani(false);'");
            echo "&nbsp;";
            db_input('z01_nome',35,$Iz01_nome,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ty83_dtfim?>">
          <?=@$Ly83_dtfim;?>        
        </td>
        <td>
          <?
            if ( empty($q07_datafim_dia) ) {
              $y83_dtfim_dia = date("d",db_getsession("DB_datausu"));
              $y83_dtfim_mes = date("m",db_getsession("DB_datausu"));
              $y83_dtfim_ano = date("Y",db_getsession("DB_datausu"));
            }
            db_inputdata('y83_dtfim',@$y83_dtfim_dia,@$y83_dtfim_mes,@$y83_dtfim_ano,true,'text',1,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tp58_codproc?>">
          <?
            db_ancora(@$Lp58_codproc,"js_pesquisap58_codproc(true);",1);
          ?>
        </td>
        <td>
          <?
            db_input('p58_codproc',10,$Ip58_codproc,true,'text',1," onchange='js_pesquisap58_codproc(false);'");
            echo "&nbsp;";
            db_input('p58_requer',40,$Ip58_requer,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ty81_oficio?>">
          <?=@$Ly81_oficio?>        
        </td>
        <td valign="top">
          <?
            $xe = array("f"=>"NORMAL","t"=>"OFÌCIO");
            db_select('y81_oficio',$xe,true,1);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ty81_obs?>">
          <?=@$Ly81_obs;?>
        </td>
        <td> 
          <?
            db_textarea('y81_obs',0,70,@$Iy81_obs,true,'text',1);
          ?>
        </td>
      </tr>
      <tr>
        <td align="center" colspan="2">
          <input name="db_opcao" type="submit" value="Baixar" <?=(!isset($y83_codsani) || $y83_codsani == ""?'disabled':'')?> onClick="return js_gera_chaves();">
        </td>
      </tr>
      <tr>
        <td align="top" colspan="2">
          <?
            if ( isset($y83_codsani) && $y83_codsani != "" ) {
              $cliframe_seleciona->campos  = "y83_seq,y83_dtini,y83_dtfim,y83_area,y83_codsani,q03_descr";
              $cliframe_seleciona->legenda="ATIVIDADES EM FUNCIONAMENTO";
              $cliframe_seleciona->sql=$clsaniatividade->sql_query("",""," saniatividade.*,q03_descr ",""," y83_codsani = $y83_codsani and y83_dtfim is null ");
              $cliframe_seleciona->textocabec ="darkblue";
              $cliframe_seleciona->textocorpo ="black";
              $cliframe_seleciona->fundocabec ="#aacccc";
              $cliframe_seleciona->fundocorpo ="#ccddcc";
              $cliframe_seleciona->iframe_height ="250";
              $cliframe_seleciona->iframe_width ="700";
              $cliframe_seleciona->iframe_nome ="atividades";
              $cliframe_seleciona->chaves ="y83_codsani,y83_seq";
              $cliframe_seleciona->iframe_seleciona(@$db_opcao);
            }
          ?>
        </td>
      </tr>
</table>
</form>
</center>
<script>
function js_pesquisay83_codsani(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_sanitario','func_sanitario.php?funcao_js=parent.js_mostrasanitario1|0|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_sanitario','func_sanitario.php?pesquisa_chave='+document.form1.y83_codsani.value+'&funcao_js=parent.js_mostrasanitario','Pesquisa',false);
  }
}
function js_mostrasanitario(chave,erro){
  document.form1.z01_nome.value = erro;
  if(erro==true){ 
    document.form1.y83_codsani.focus(); 
    document.form1.y83_codsani.value = ''; 
  }
  document.form1.submit();
}
function js_mostrasanitario1(chave1,chave2){
  document.form1.y83_codsani.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_sanitario.hide();
  document.form1.submit();
}
function js_pesquisay82_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_processo','func_protprocesso.php?funcao_js=parent.js_mostraprocesso1|p58_codproc|p58_requer','Pesquisa',true);
  }else{
     if(document.form1.y82_codproc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_processo','func_protprocesso.php?pesquisa_chave='+document.form1.y82_codproc.value+'&funcao_js=parent.js_mostraprocesso','Pesquisa',false);
     }else{
       document.form1.y82_codproc.value = ''; 
     }
  }
}
function js_pesquisap58_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_requer','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_cgm','func_protprocesso.php?pesquisa_chave='+document.form1.p58_codproc.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
  }
}
function js_mostraprotprocesso(chave,chave1,erro){
  document.form1.p58_requer.value = chave1; 
  if(erro==true){ 
    document.form1.p58_codproc.focus(); 
    document.form1.p58_codproc.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.p58_codproc.value = chave1;
  document.form1.p58_requer.value = chave2;
  db_iframe_cgm.hide();
}
</script></center>
		</td>
	</tr>
</table>
				<?
				db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
				?>
</body>
</html>
				<?
				if(isset($erro)){
				  echo "<script>alert('selecione uma atividade antes de dar baixa');</script>";
				  echo "<script>location.href='fis4_baixasaniatividade001.php?y83_codsani=$erro';</script>";
				}

				if(isset($sqlerro)){
				  if($sqlerro== true){
				    db_msgbox($erro_msg );
				  }
				}


				?>