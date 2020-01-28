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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("libs/db_stdlibwebseller.php");
include("classes/db_prontuarios_ext_classe.php");
include("classes/db_cgs_classe.php");
include("classes/db_cgs_und_ext_classe.php");
include("classes/db_cgs_cartaosus_classe.php");
include("classes/db_prontanulado_classe.php");
include("classes/db_sau_config_ext_classe.php");
include("classes/db_agendamentos_ext_classe.php");
include("classes/db_prontagendamento_classe.php");
include("classes/db_prontprofatend_ext_classe.php");

include("dbforms/db_funcoes.php");

$z01_d_cadast_dia = date("d",db_getsession("DB_datausu"));
$z01_d_cadast_mes = date("m",db_getsession("DB_datausu"));
$z01_d_cadast_ano = date("Y",db_getsession("DB_datausu"));
$z01_i_login      = DB_getsession("DB_id_usuario");
$nome             = DB_getsession("DB_login");
$sd24_i_unidade   = db_getsession("DB_coddepto");
db_postmemory($HTTP_POST_VARS);

$clprontuarios      = new cl_prontuarios_ext;
$clcgs              = new cl_cgs;
$clcgs_und          = new cl_cgs_und_ext;
$clcgs_cartaosus    = new cl_cgs_cartaosus;
$clprontanulado     = new cl_prontanulado;
$clsau_config       = new cl_sau_config_ext;
$clagendamentos     = new cl_agendamentos_ext;
$clprontagendamento = new cl_prontagendamento;
$clprontprofatend   = new cl_prontprofatend_ext;

$result = $clcgs->sql_record( "select descrdepto 
                                 from db_depart
                                inner join unidades on unidades.sd02_i_codigo = db_depart.coddepto  
                                where coddepto = ".db_getsession("DB_coddepto") );
if ($clcgs->numrows == 0) {
  
  echo "<table width='100%'>
          <tr>
           <td align='center'><font  face='arial'><b>
           <p>Departamento ".db_getsession("DB_coddepto")." não cadastrado como UPS. 
           <p> Selecione um departamento válido.</b></font></td>
          </tr>
         </table>";
  exit;  

}
db_fieldsmemory($result,0);

$result = $clcgs->sql_record( "select munic as z01_v_munic, uf as z01_v_uf 
                                 from db_config
                                where codigo = ".db_getsession("DB_instit") );
db_fieldsmemory($result,0);

$db_opcao = 1;
$db_botao = true;

if (isset($incluir)) {

  try {

    $z01_i_login = DB_getsession("DB_id_usuario");
    if( !isset($sd24_i_codigo) || (int)$sd24_i_codigo == 0 ){

      //Gera Numero  do Atendimento
      $sql_fc    = "select fc_numatend()";
      $query_fc  = pg_query($sql_fc) or die(pg_errormessage().$sql_fc);
      if($query_fc == false){
        throw new Exception('Erro ao selecio sequancia do prontuario!');
      }
      $fc_numatend = explode(",",pg_result($query_fc,0,0));

    }

    db_inicio_transacao();

    if (!isset($z01_i_cgsund) || empty($z01_i_cgsund)) {

      //Incluir CGS
      $clcgs->incluir("");
      if ($clcgs_und->erro_status == "0") {
        throw new Exception('CGS: '.$clcgs->erro_msg);
      }

      //Incluir CGS_UND
      $GLOBALS["HTTP_POST_VARS"]["z01_i_login"] = DB_getsession("DB_id_usuario");
      $clcgs_und->z01_i_login = DB_getsession("DB_id_usuario");
      $z01_i_cgsund = $clcgs->z01_i_numcgs;
      if ((int)$z01_i_familiamicroarea == 0) {
        $z01_i_familiamicroarea='null';
      }
      $clcgs_und->incluir($z01_i_cgsund);
      if ($clcgs_und->erro_status == "0") {
        throw new Exception('CGS_UND: '.$clcgs_und->erro_msg);
       }
    
      //Incluir Cart�o SUS
      if ($s115_c_cartaosus != '') {

        $clcgs_cartaosus->s115_i_cgs       = $clcgs->z01_i_numcgs;
        $clcgs_cartaosus->s115_c_cartaosus = $s115_c_cartaosus;
        $clcgs_cartaosus->s115_c_tipo      = $s115_c_tipo;
        $clcgs_cartaosus->incluir("");
        if ($clcgs_cartaosus->erro_status == "0") {
          throw new Exception('Cart�o SUS: '.$clcgs_cartaosus->erro_msg);
        }

      }

    }else{

      //Aleterar CGS
      $clcgs->z01_i_numcgs = $z01_i_cgsund;
      $clcgs->alterar($z01_i_cgsund);
      if ($clcgs->erro_status == "0") {
        throw new Exception('CGS: '.$clcgs->erro_msg);
      }

      //Aletar CGS_UND
      if ((int)$z01_i_familiamicroarea == 0) {
        $z01_i_familiamicroarea = 'null';
      }
      $GLOBALS["HTTP_POST_VARS"]["z01_i_login"] = DB_getsession("DB_id_usuario");
      $clcgs_und->z01_i_login = DB_getsession("DB_id_usuario");
      $clcgs_und->z01_i_cgsund = $z01_i_cgsund;
      $clcgs_und->alterar($z01_i_cgsund);
      if ($clcgs_und->erro_status == "0") {
        throw new Exception('CGS_UND: '.$clcgs_und->erro_msg);
      }
    
      //Alterar/Incluir Cart�o SUS
      if ($s115_c_cartaosus != '') {

        $clcgs_cartaosus->s115_c_cartaosus = $s115_c_cartaosus;
        $clcgs_cartaosus->s115_c_tipo      = $s115_c_tipo;  
        if (isset($s115_i_codigo) && $s115_i_codigo != "") {

          $clcgs_cartaosus->alterar($s115_i_codigo);
          if ($clcgs_cartaosus->erro_status == "0") {
            throw new Exception('Cart�o SUS: '.$clcgs_cartaosus->erro_msg);
          }

        }else{

          $clcgs_cartaosus->s115_i_cgs = $z01_i_cgsund;
          $clcgs_cartaosus->incluir(null);  
          if ($clcgs_cartaosus->erro_status == "0") {
            throw new Exception('Cart�o SUS: '.$clcgs_cartaosus->erro_msg);
          }

        }

      }

    }

    //Incluir/Alterar Prontuario
    if( !isset($sd24_i_codigo) || (int)$sd24_i_codigo == 0 ){

      $clprontuarios->sd24_i_ano = trim($fc_numatend[0]);
      $clprontuarios->sd24_i_mes = trim($fc_numatend[1]);
      $clprontuarios->sd24_i_seq = trim($fc_numatend[2]);
      $clprontuarios->sd24_i_unidade = $sd24_i_unidade;
      $clprontuarios->sd24_i_numcgs = $z01_i_cgsund;
      $clprontuarios->sd24_d_cadastro = date("Y-m-d",db_getsession("DB_datausu"));
      $clprontuarios->sd24_c_cadastro = db_hora();
      $clprontuarios->sd24_i_login = DB_getsession("DB_id_usuario");
      $clprontuarios->sd24_c_digitada = 'N';
      $clprontuarios->sd24_i_motivo = $sd24_i_motivo;
      $clprontuarios->sd24_i_tipo = $sd24_i_tipo;
      $clprontuarios->sd24_i_acaoprog = $sd24_i_acaoprog;
      $clprontuarios->incluir("");
      if ($clprontuarios->erro_status == "0") {
        throw new Exception('Prontuarios: '.$clprontuarios->erro_msg);
      }

      //Profissional de Atendimento - entrada do profissional na 1a aba
      if($sd27_i_codigo!=""){

        $clprontprofatend->s104_i_prontuario   = $clprontuarios->sd24_i_codigo;
        $clprontprofatend->s104_i_profissional = $sd27_i_codigo;
        $clprontprofatend->incluir("");
        if ($clprontprofatend->erro_status == "0") {
          throw new Exception('Prof Atend:'.$clprontprofatend->erro_msg);
        }

      }

      //Promtu�rio Agendamento
      $clprontagendamento->s102_i_agendamento = $sd23_i_codigo;
      $clprontagendamento->s102_i_prontuario  = $clprontuarios->sd24_i_codigo;
      $clprontagendamento->incluir("");
      if ($clprontuarios->erro_status == "0") {
        throw new Exception('Prontuarios: '.$clprontuarios->erro_msg);
      }

      $chavepesquisaprontuario = $clprontuarios->sd24_i_codigo;

    }else{

      $clprontuarios->alterar($sd24_i_codigo);
      if ($clprontuarios->erro_status == "0") {
        throw new Exception('Prontuarios: '.$clprontuarios->erro_msg);
      }
      //Profissional de Atendimento - entrada do profissional na 1a aba
      $sql="select s104_i_codigo from prontprofatend where s104_i_prontuario = $sd24_i_codigo";
      $result_prof=$clprontprofatend->sql_record($sql);
      if(($clprontprofatend->numrows > 0) && ($sd27_i_codigo != "")){

        db_fieldsmemory($result_prof,0);
        $clprontprofatend->s104_i_prontuario   = $sd24_i_codigo;
        $clprontprofatend->s104_i_profissional = $sd27_i_codigo;
        $clprontprofatend->s104_i_codigo = $s104_i_codigo;
        $clprontprofatend->alterar($s104_i_codigo);
        if ($clprontprofatend->erro_status == "0") {
          throw new Exception('Prof Atendimento: '.$clprontprofatend->erro_msg);
        }

      }else if($sd27_i_codigo != ""){

        $clprontprofatend->s104_i_prontuario   = $sd24_i_codigo;
        $clprontprofatend->s104_i_profissional = $sd27_i_codigo;
        $clprontprofatend->incluir("");
        if ($clprontprofatend->erro_status == "0") {
          throw new Exception('Pront Patendimento'.$clprontprofatend->erro_msg);
        }

      }
    }

    db_fim_transacao(false);
  } catch (Exception $eException) {

    db_fim_transacao(true);
    $clprontuarios->erro_status = "0";
    $clprontuarios->erro_msg    = $eException->getMessage();

  }
  

}else if( isset($chavepesquisaprontuario) && (int)$chavepesquisaprontuario != 0) {

  $result = $clprontuarios->sql_record($clprontuarios->sql_query_nolote_ext($chavepesquisaprontuario));
  if ($clprontuarios->numrows > 0) {

    $obj_prontuario = db_utils::fieldsMemory($result, 0);       
    if ($obj_prontuario->sd59_i_prontuario != "") {   

      db_msgbox("Impossível alteração de FAA incluída via Lote.");
      $sd24_i_codigo = null;

    }else{

      $res_pronproced = $clprontuarios->sql_record("select * 
                                                    from sau_fechapront
                                                    inner join prontproced on prontproced.sd29_i_codigo = sau_fechapront.sd98_i_prontproced
                                                    inner join prontuarios on prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario
                                                    where prontuarios.sd24_i_codigo = $chavepesquisaprontuario 
                                                  ");
      if( $clprontuarios->numrows > 0  ){

        db_msgbox("Impossível alteração de FAA fechada.");
        $sd24_i_codigo = null;

      } else {

        db_fieldsmemory($result,0);    
        $z01_nome="";
        $result = $clcgs_und->sql_record($clcgs_und->sql_query_ext($sd24_i_numcgs));
        db_fieldsmemory($result,0);

        //Verifica se FAA tem agendamento
        $result_prontagendamento = pg_query( $clprontagendamento->sql_query_ext(null,"*",null, 
                            "s102_i_prontuario = $chavepesquisaprontuario "
                            ) );
                  if( pg_num_rows($result_prontagendamento) > 0 ){
                      db_fieldsmemory( $result_prontagendamento, 0 );
                   }          
                  //Pega 1o profissional de atendimento - prontprofatend
                  $result_prontprofatend = pg_query( $clprontprofatend->sql_query_ext(null,"m.*, rhcbo.*, especmedico.*, medicos.*, prontproced.sd29_i_profissional", "s104_i_codigo", "s104_i_prontuario = $chavepesquisaprontuario"));
                  if(pg_num_rows($result_prontprofatend) > 0 ){
                      db_fieldsMemory($result_prontprofatend,0);
                  }                    
               
              }
          
          }
   }else{

       //Verifica se FAA esta anulada
       $clprontanulado->sql_record($clprontanulado->sql_query("","*","", "sd57_i_prontuario = $chavepesquisaprontuario "));
        if( $clprontanulado->numrows > 0  ){

            db_msgbox("Impossível alteração de FAA Cancelada.");
            $sd24_i_codigo = null;

        }    
   }
}else if (isset($chavepesquisacgs) && (int)$chavepesquisacgs != 0){

     $result = $clcgs_und->sql_record($clcgs_und->sql_query_ext($chavepesquisacgs));
     db_fieldsmemory($result,0);

}else if (isset($chavepesquisaagenda) && (int)$chavepesquisaagenda != 0){

     $result_prontagendamento = pg_query( $clprontagendamento->sql_query_ext($chavepesquisaagenda) );
     db_fieldsmemory( $result_prontagendamento, 0 );

}
if (isset($z01_d_cadast) && empty($z01_d_cadast)){

     $z01_d_cadast_dia = date("d",db_getsession("DB_datausu"));
     $z01_d_cadast_mes = date("m",db_getsession("DB_datausu"));
     $z01_d_cadast_ano = date("Y",db_getsession("DB_datausu"));
}
if (isset($chavepesquiamunicipio)) {  

     $z01_c_municipio = $chavepesquiamunicipio;
     if ($z01_c_municipio == "N") {

        $z01_v_cep = "";
        $z01_v_ender = "";
        $z01_i_numero = "";
        $z01_v_compl = "";
        $z01_v_bairro = "";

     }else{
        $z01_v_cep = "";    
     }
}
//Configuração/Parâmetros
$obj_sau_config = loadConfig("sau_config");
if ($obj_sau_config == false) {
  echo "<table width='100%'>
          <tr>
           <td align='center'><font  face='arial'><b><p>Tabela sau_config sem registro.</b></font></td>
          </tr>
         </table>";
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
<script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br><br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmsau_recepcao.php");
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
<script>
js_tabulacaoforms("form1","z01_v_cgccpf",true,1,"z01_v_cgccpf",true);
</script>

<?
if(isset($incluir)){

  if ($clprontuarios->erro_status == "0") {

    $clprontuarios->erro(true,false);
    $db_botao=true;

  }else{
    db_redireciona("sau4_recepcao001.php?chavepesquisaprontuario=$chavepesquisaprontuario");
  }

}
?>