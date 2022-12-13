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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");
require_once ("classes/db_bens_classe.php");
require_once ("classes/db_bensimoveis_classe.php");
require_once ("classes/db_bensmater_classe.php");
require_once ("classes/db_situabens_classe.php");
require_once ("classes/db_clabens_classe.php");
require_once ("classes/db_histbem_classe.php");
require_once ("classes/db_bensplaca_classe.php");
require_once ("classes/db_benscadlote_classe.php");
require_once ("classes/db_benslote_classe.php");
require_once ("classes/db_departdiv_classe.php");
require_once ("classes/db_histbemdiv_classe.php");
require_once ("classes/db_bensdiv_classe.php");
require_once ("classes/db_cfpatriplaca_classe.php");
require_once ("classes/db_cfpatri_classe.php");
require_once ("classes/db_bensmarca_classe.php");
require_once ("classes/db_bensmedida_classe.php");
require_once ("classes/db_bensmodelo_classe.php");
require_once ("classes/db_benscedente_classe.php") ;

$oDaoCfPatri        = db_utils::getDao('cfpatriinstituicao');
$sSqlPatri          = $oDaoCfPatri->sql_query_file(null, 
		                                               "t59_dataimplanatacaodepreciacao", 
		                                               "", 
		                                               "t59_instituicao = ".db_getsession("DB_instit"));
$rsPatri            = $oDaoCfPatri->sql_record($sSqlPatri);

if ($oDaoCfPatri->numrows > 0) {
  
  $sInicioDepreciacao = db_utils::fieldsMemory($rsPatri, 0)->t59_dataimplanatacaodepreciacao;
  if (!empty($sInicioDepreciacao)) {
    db_redireciona('pat1_bensglobalnovo001.php');
  }
}

$clbenscedente = new cl_benscedente();
$cldepartdiv    = new cl_departdiv;
$clbens         = new cl_bens;
$clhistbem      = new cl_histbem;
$clclabens      = new cl_clabens;
$clbensimoveis  = new cl_bensimoveis;
$clbensmater    = new cl_bensmater;
$cldb_estrut    = new cl_db_estrut;
$clsituabens    = new cl_situabens;
$clbensplaca    = new cl_bensplaca;
$clbenscadlote  = new cl_benscadlote;
$clbenslote     = new cl_benslote;
$clhistbemdiv   = new cl_histbemdiv;
$clbensdiv      = new cl_bensdiv;
$clcfpatri      = new cl_cfpatri;
$clcfpatriplaca = new cl_cfpatriplaca;
$clbensmarca    = new cl_bensmarca;
$clbensmedida   = new cl_bensmedida;
$clbensmodelo   = new cl_bensmodelo;


$clrotulo = new rotulocampo;
$clsituabens->rotulo->label();
$clrotulo->label("t56_situac");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$db_opcao = 1;
$db_botao = true;
if (isset($incluir)) {

  if (isset($qtd)) {
    $tipo_inclui="true";
  }

  $sqlerro=false;

  $erro_msg = "";
  $campos_nao_informados = "";

  if($t56_situac == null ){ 
    $campos_nao_informados .= "Situacao - ";
    $clbens->erro_campo = "t56_situac";
    $sqlerro            = true;
  }
  if($t52_depart == null ){ 
    $campos_nao_informados .= "Departamento - ";
    $clbens->erro_campo = "t52_depart";
    $sqlerro            = true;
  }
  if($t52_dtaqu == null ){ 
    $campos_nao_informados .= "Data da aquisicao - ";
    $clbens->erro_campo = "t52_dtaqu";
    $sqlerro            = true;
  }
  if($t52_valaqu == null ){ 
    $campos_nao_informados .= "Valor da aquisicao - ";
    $clbens->erro_campo = "t52_valaqu";
    $sqlerro            = true;
  }
  if($t52_numcgm == null ){ 
    $campos_nao_informados .= "Fornecedor - ";
    $clbens->erro_campo = "t52_numcgm";
    $sqlerro            = true;
  }
  if($t64_class == null ){ 
    $campos_nao_informados .= "Classificacao - ";
    $clbens->erro_campo = "t52_codcla";
    $sqlerro            = true;
  }
  if($t52_descr == null ){ 
    $campos_nao_informados .= "Descricao do bem - ";
    $clbens->erro_campo = "t52_descr";
    $sqlerro            = true;
  }

  if ($campos_nao_informados != "") {
    $erro_msg = "Campos: $campos_nao_informados nao informados!";
  }

  
  $iPlacaInicial = $t52_ident;
  $iPlacaFinal   = $t52_ident;
  
  if (!empty($qtd)) {
    $iPlacaFinal = $iPlacaInicial + ($qtd - 1);
  }
  
  $sWhere  = " t41_placaseq BETWEEN {$iPlacaInicial} and $iPlacaFinal";
  $sWhere .= " and t52_instit = " . db_getsession("DB_instit");
  
  $sSqlVerificaIntervaloPlaca = $clbensplaca->sql_query_placa_bem(null, "t41_codigo", null, $sWhere);
  
  $rsVerificaIntervaloPlaca   = $clbensplaca->sql_record($sSqlVerificaIntervaloPlaca);
  
  if ($clbensplaca->numrows > 0) {
    
    $erro_msg = "Já existe uma placa cadastrada no intervalo de: {$iPlacaInicial} e {$iPlacaFinal} ";
    $sqlerro  = true;
    
  }
  
  $contador = 0;
  db_inicio_transacao();

  if ($sqlerro == false) {

    $result = $clcfpatriplaca->sql_record($clcfpatriplaca->sql_query_file(db_getsession("DB_instit")));
    
    if ($clcfpatriplaca->numrows > 0) {
      db_fieldsmemory($result,0);
    }

    if (trim(@$t52_ident) == "0" or @$t52_ident == "" or @$t52_ident == null){
      $clbens->erro_campo = "t52_ident";
      $sqlerro            = true;
      $erro_msg           = "Bem não incluido. Inclusão Abortada.\\n\\nUsuário: Placa de identificação não pode ser zero.\\n\\n Administrador.";
    }

    if (isset($t64_class) && $t64_class == "" && $sqlerro == false) {
      if (isset($t52_descr) && $t52_descr != '') {
        $sqlerro = true;
        $erro_msg = "Usuário: \\n\\n Campo Classificação do Material nao Informado \\n\\n Administrador.";
        $clbens->erro_campo = 't64_class';
      } else {
        $sqlerro = true;
        $erro_msg = "Usuário: \\n\\n Campo Descrição do Material nao Informado \\n\\n Administrador.";
        $clbens->erro_campo = 't52_descr';
      }
    }

    // Se campo t07_obrigplaca == true, obriga digitacao da placa
    if ($t07_obrigplaca == "t" || ($t07_obrigplaca == "f" && strlen(trim($t52_ident)) > 0) && $sqlerro == false) {
      
      if ($t07_obrigplaca == "t" && strlen(trim(@$t52_ident)) == 0 && !isset($tipo_inclui)){
        $clbens->erro_campo = "t52_ident";
        $sqlerro            = true;
        $erro_msg           = "Bem não incluido. Inclusão Abortada.\\n\\nUsuário: Placa de identificação não informada.\\n\\n Administrador.";
      } else {
        
        if ($sqlerro == false){
          $ident = $t52_ident.@$t52_ident_seq;
          
          if (strlen(trim(@$ident)) > 0){
            if ($t07_confplaca==4) {
              
              $ident = str_pad($ident,$t07_digseqplaca,'0',STR_PAD_LEFT);
              
            }
            
            $result_t52_ident = $clbens->sql_record($clbens->sql_query_file(null,"t52_ident",null,"t52_ident = '".str_replace(".","",$ident)."' and t52_instit = $t52_instit"));
            if ($clbens->numrows>0) {
              $clbens->erro_campo = "t52_ident";
              $sqlerro  = true;
              $erro_msg = "Usuário: \\n\\n Inclusão não concluída, placa de identificação já cadastrada para outro bem\\n\\n Administrador.";
            }
          }
        }
      }
    }
    


    if ($sqlerro == false) {
      //rotina q retira os pontos do estrutural da classe e busca o código do estrutural na tabela clabens
      if ($sqlerro == false && (isset($t64_class) && $t64_class != "")) {
        $t64_class = str_replace(".","",$t64_class);
        $result_t64_codcla = $clclabens->sql_record($clclabens->sql_query_file(null,"t64_codcla as t52_codcla",null,"t64_class = '$t64_class' and t64_instit = ".db_getsession("DB_instit")));
        if ($clclabens->numrows>0) {
          db_fieldsmemory($result_t64_codcla,0);
          
        } else {
          $sqlerro=true;
          $erro_msg = "Usuário: \\n\\n Inclusão não concluída, Classificação Informada nao Existe \\n\\n Administrador.";
          $clbens->erro_campo = 't64_class';
        }
      }
      

      if (!isset($qtd)) {
        $qtd_cont=1;
      } else {
        
        $qtd_cont=$qtd;
        if ($sqlerro == false) {
          
          $clbenscadlote->t42_usuario=db_getsession("DB_id_usuario");
          $clbenscadlote->t42_hora=db_hora();
          $clbenscadlote->t42_data=date('Y-m-d',db_getsession("DB_datausu"));
          $clbenscadlote->t42_descr=$t42_descr;
          $clbenscadlote->incluir(null);
          if ($clbenscadlote->erro_status==0) {

            $erro_msg=$clbenscadlote->erro_msg;
            $sqlerro=true;
          } else {
            $codlote=$clbenscadlote->t42_codigo;
          }
        }
      }

      $placas_geradas = "";

      for ($i=0; $i<$qtd_cont; $i++) {
        
        if ($sqlerro == false) {
          
          $classif = $t64_class;
          $clbens->t52_codcla = $t52_codcla;
          $clbens->t52_numcgm = $t52_numcgm;
          $clbens->t52_valaqu = $t52_valaqu;
          $clbens->t52_dtaqu  = $t52_dtaqu_ano."-".$t52_dtaqu_mes."-".$t52_dtaqu_dia;

          $result_t52_bem = $clbens->sql_record("select nextval('bens_t52_bem_seq') as t52_bem");
          db_fieldsmemory($result_t52_bem, 0);
          $placa="";
          
          if ($t07_confplaca == 1) {
            $placa = $t07_sequencial;
            
          } else if ($t07_confplaca == 2) {
            
            $placa = $t64_class;
            $result_ultseq = $clbensplaca->sql_record($clbensplaca->sql_query_file(null,"max(t41_placaseq)as max_seq",null," t41_placa = '$classif' "));
            
            if ($clbensplaca->numrows > 0) {

              db_fieldsmemory($result_ultseq,0);
              if ($max_seq!="") {
                
                $seq = $max_seq;
                $seq = $seq + 1;
              } else {
                $seq = "1";
              }
            } else {
              $seq = "1";
            }

            $seq      = db_formatar($seq,'f','0',$t07_digseqplaca,'e',0);
            $placaseq = $seq;
            $placa    = $placa.$placaseq;
            
          } else if ($t07_confplaca == 3 ) {
            $t52_ident_seq = db_formatar($t52_ident_seq,'f','0',$t07_digseqplaca,'e',0);
            $placa         = $t52_ident.$t52_ident_seq;
          } else if ($t07_confplaca == 4) {
            
            if ($t07_obrigplaca == "t" || ($t07_obrigplaca == "f" && strlen(trim($t52_ident)) > 0)) {
              $placa=$t52_ident;
              $placa    = str_pad($placa,$t07_digseqplaca,'0',STR_PAD_LEFT);

              if ($sqlerro == false && strlen(trim(@$placa)) > 0) {
                $sqlbensplaca = $clbensplaca->sql_query(null,"t41_bem as codbem,t52_ident as identificacao", 
                                                        null,"t52_ident = '".str_replace(".","",$placa)."' and t52_instit = $t52_instit and t41_bem <> $t52_bem");
                $res_t52_ident = $clbensplaca->sql_record($sqlbensplaca);

                if ($clbensplaca->numrows > 0) {
                  
                  $clbens->erro_campo = "t52_ident";
                  $sqlerro=true;
                  $erro_msg = "Usuário: \\n\\n Inclusão não concluída, placa de identificação já cadastrada para outro bem\\n\\n Administrador.";
                  break;
                }
              }
            }
          }

          //db_msgbox("xx -> ".$t52_ident);

          //db_msgbox($placa);
          $clbens->t52_instit     = $t52_instit;
          $clbens->t52_ident      = str_replace(".","",$placa);
          $clbens->t52_descr      = $t52_descr;
          $clbens->t52_obs        = $t52_obs;
          $clbens->t52_depart     = $t52_depart;
          $clbens->t52_bensmarca  = $t65_sequencial;      
          $clbens->t52_bensmedida = $t67_sequencial;
          $clbens->t52_bensmodelo = $t66_sequencial;
          
          $clbens->incluir($t52_bem);

          $t52_instit = $clbens->t52_instit;
          $t52_bem    = $clbens->t52_bem;
          $erro_msg   = $clbens->erro_msg;
          if ($clbens->erro_status==0) {
            $sqlerro=true;
            break;
          }

          if ($sqlerro == false) {
            if ($i == 0) {
              if (strlen(trim($placa)) > 0){
                $placas_geradas = db_formatar($placa,"s","0",$t07_digseqplaca,"e",0)." a ";
              }
            }

            if ($i > 0) {
              $xx = $i;
              $xx++;

              if ($xx == $qtd_cont) {
                if (strlen(trim($placa)) > 0){
                  $placas_geradas .= db_formatar($placa,"s","0",$t07_digseqplaca,"e",0);
                }
              }
            }

            $placa = "";
            $seq   = "0";
            if ($t07_confplaca==1) {
              
              $placaseq        = $t07_sequencial;
              $t07_sequencial += 1;
            } else if ($t07_confplaca==2) {
              
              $placa=$t64_class;
              $result_ultseq = $clbensplaca->sql_record($clbensplaca->sql_query_file(null,"max(t41_placaseq)as max_seq",null," t41_placa = '$classif' "));
              if ($clbensplaca->numrows>0) {
                db_fieldsmemory($result_ultseq,0);
                if ($max_seq!="") {
                  $seq=$max_seq;
                  $seq=$seq+1;
                } else {
                  $seq="001";
                }
              } else {
                $seq="001";
              }
              $seq      = db_formatar($seq,'f','0',$t07_digseqplaca,'e',0);
              $placaseq = $seq;
            } else if ($t07_confplaca==3) {
              $placa         = $t52_ident;
              $t52_ident_seq = db_formatar($t52_ident_seq,'f','0',$t07_digseqplaca,'e',0);
              $placaseq      = $t52_ident_seq;
              $t52_ident_seq = $t52_ident_seq+1;
            } else if ($t07_confplaca==4) {
              if ($t07_obrigplaca == "t" || ($t07_obrigplaca == "f" && strlen(trim($t52_ident)) > 0)) {
                $placaseq  = $t52_ident;
                $t52_ident = $t52_ident+1;
              }
            }

            //db_msgbox("xxx -> ".$t52_ident);
            //echo $t07_obrigplaca." => |".$placaseq."|<br>";

            $t41_obs = "";
            if (strlen(trim($t52_ident)) == 0){
              $placaseq = 0;
              $t41_obs  = "PLACA NÃO INFORMADA";
            }
 
            $clbensplaca->t41_bem      = $t52_bem;
            $clbensplaca->t41_placa    = $placa;
            $clbensplaca->t41_placaseq = str_replace(".","",$placaseq);
            $clbensplaca->t41_obs      = "$t41_obs";
            $clbensplaca->t41_data     = date('Y-m-d',db_getsession("DB_datausu"));
            $clbensplaca->t41_usuario  = db_getsession("DB_id_usuario");
            $clbensplaca->incluir(null);
            
            if ($clbensplaca->erro_status == 0) {
              $sqlerro  = true;
              $erro_msg = $clbensplaca->erro_msg;
              //db_msgbox("5 -> ".$erro_msg);
            }
          }

          if ($sqlerro == false) {
            $clcfpatriplaca->t07_instit     = $t52_instit;
            $clcfpatriplaca->t07_sequencial = str_replace(".","",$t07_sequencial);
            $clcfpatriplaca->alterar($t52_instit);

            if ($clcfpatriplaca->erro_status==0) {
              $sqlerro  = true;
              $erro_msg = $clcfpatriplaca->erro_msg;
              //db_msgbox("6 -> ".$erro_msg);
            }
          }

          if ($sqlerro == false) {
            if (isset($qtd)) {
              $clbenslote->t43_codlote=$codlote;
              $clbenslote->t43_bem=$t52_bem;
              $clbenslote->incluir(null);
              if ($clbenslote->erro_status==0) {
                $erro_msg=$clbenslote->erro_msg;
                $sqlerro=true;
                //db_msgbox("7 -> ".$erro_msg);
              }
            }
          }
        }

        if ($sqlerro == false) {
          $clhistbem->t56_codbem = $t52_bem;
          $clhistbem->t56_data   = $t52_dtaqu_ano."-".$t52_dtaqu_mes."-".$t52_dtaqu_dia;
          $clhistbem->t56_depart = $t52_depart;
          $clhistbem->t56_situac = $t56_situac;
          $clhistbem->t56_histor = 'Inclusão do bem';
          $clhistbem->incluir(null);
          $erro_msg = $clhistbem->erro_msg;
          
          if ($clhistbem->erro_status==0) {
            $sqlerro=true;
            //db_msgbox("8 -> ".$erro_msg);
            break;
          }
        }
        if ($sqlerro == false) {
          
          if ($t33_divisao!="") {
            if ($sqlerro == false) {
              $clhistbemdiv->t32_histbem=$clhistbem->t56_histbem;
              $clhistbemdiv->t32_divisao=$t33_divisao;
              $clhistbemdiv->incluir(null);
              if ($clhistbemdiv->erro_status == 0) {
                $sqlerro = true;
                $erro_msg = $clhistbemdiv->erro_msg;
                //db_msgbox("1 -> ".$erro_msg);
                break;
              }
            }
            if ($sqlerro == false) {
              $clbensdiv->t33_divisao=$t33_divisao;
              $clbensdiv->incluir($t52_bem);
              if ($clbensdiv->erro_status==0) {
                $sqlerro=true;
                $erro_msg=$clbensdiv->erro_msg;
                //db_msgbox("2 -> ".$erro_msg);
                break;
              }
            }
          }
        }

        if ($sqlerro == false && $t04_sequencial != "") {
          
          $clbenscedente->t09_bem = $t52_bem;
          $clbenscedente->t09_benscadcedente  = $t04_sequencial;
          $clbenscedente->incluir(null);
          db_msgbox($clbenscedente->erro_status);
          $erro_msg = $clbenscedente->erro_msg;
          
          if ($clbenscedente->erro_status==0) {
            
            $sqlerro=true;
            break;
          }
        }

        if ( isset($t54_idbql) || isset($t53_ntfisc) ) {
          
          if ( isset($t54_idbql) && trim($t54_idbql) != '' ) {
            
            $clbensimoveis->t54_codbem = $t52_bem;
            $clbensimoveis->t54_idbql  = $t54_idbql;
            $clbensimoveis->t54_obs    = $t54_obs;
            $clbensimoveis->incluir($t52_bem,$t54_idbql);
            
            $erro_msg = $clbensimoveis->erro_msg;
           
            if ($clbensimoveis->erro_status == 0){
              
              $sqlerro = true;
              break;
            }
          } else if ( isset($t53_ntfisc) && trim($t53_ntfisc) != '' ) {
            
            $clbensmater->t53_codbem = $t52_bem;
            $clbensmater->t53_ntfisc = $t53_ntfisc;
            $clbensmater->t53_empen  = $t53_empen;
            $clbensmater->t53_ordem  = $t53_ordem;
            $clbensmater->t53_garant = $t53_garant;
            $clbensmater->incluir($t52_bem);

            $erro_msg = $clbensmater->erro_msg;

            if ($clbensmater->erro_status == 0) {
              
              $sqlerro = true;
              break;
            }
          }
        }
        
        if ($sqlerro==false) {
          $contador++;
        }
      }

    }

  }

  if ($sqlerro==false) {
    $erro_msg = $clbens->erro_msg;
    //db_msgbox("11 -> ".$erro_msg);
  }
  //$sqlerro = true;
  db_fim_transacao($sqlerro);
  $incluir = "incluir";

  
  if ($contador>1 && $sqlerro==false) {
    if (strlen(trim($placas_geradas)) > 0){
      db_msgbox("Usuário: \\n\\n $qtd registros incluídos com sucesso\\n\\n Placas geradas $placas_geradas\\n\\n Administrador.");
    } else {
      db_msgbox("Usuário: \\n\\n $qtd registros incluídos com sucesso\\n\\n Administrador.");
    }

    db_redireciona("pat1_bensglobal001.php");
  }

  //    exit;
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
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_escondeFieldsetImovel();js_escondeFieldsetMaterial();" >
<br><br>
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
        include ("forms/db_frm_bensglobal.php");
      ?>
    </center>
  </td>
  </tr>
</table>
  <? 
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>

<?
if(isset($incluir)){

  if (trim(@$erro_msg)!=""){
       db_msgbox($erro_msg);
  }
  if($sqlerro==true){
    if($clbens->erro_campo!=""){
      echo "<script> document.form1.".$clbens->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbens->erro_campo.".focus();</script>";
    }
  } else {
    db_redireciona("pat1_bensglobal001.php?".$parametros."liberaaba=true&chavepesquisa=$t52_bem");
  }
}
?>