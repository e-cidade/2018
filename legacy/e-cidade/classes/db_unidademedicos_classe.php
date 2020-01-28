<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

//MODULO: ambulatorial
//CLASSE DA ENTIDADE unidademedicos
class cl_unidademedicos { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $sd04_i_codigo = 0; 
   var $sd04_i_unidade = 0; 
   var $sd04_i_medico = 0; 
   var $sd04_i_vinculo = 0; 
   var $sd04_i_tipovinc = 0; 
   var $sd04_i_subtipovinc = 0; 
   var $sd04_i_horaamb = null; 
   var $sd04_i_horahosp = null; 
   var $sd04_i_horaoutros = null; 
   var $sd04_i_orgaoemissor = 0; 
   var $sd04_c_situacao = null; 
   var $sd04_v_registroconselho = null; 
   var $sd04_c_sus = null; 
   var $sd04_i_numerodias = 0; 
   var $sd04_d_folgaini_dia = null; 
   var $sd04_d_folgaini_mes = null; 
   var $sd04_d_folgaini_ano = null; 
   var $sd04_d_folgaini = null; 
   var $sd04_d_folgafim_dia = null; 
   var $sd04_d_folgafim_mes = null; 
   var $sd04_d_folgafim_ano = null; 
   var $sd04_d_folgafim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd04_i_codigo = int4 = Código 
                 sd04_i_unidade = int4 = Unidade 
                 sd04_i_medico = int4 = Profissional 
                 sd04_i_vinculo = int4 = Vinculação 
                 sd04_i_tipovinc = int4 = Tipo Vinculo 
                 sd04_i_subtipovinc = int4 = Sub Tipo 
                 sd04_i_horaamb = char(5) = Hora Ambulatorial 
                 sd04_i_horahosp = char(5) = Hora Hospitalar 
                 sd04_i_horaoutros = char(5) = Outras Horas 
                 sd04_i_orgaoemissor = int4 = Órgão Emissor 
                 sd04_c_situacao = char(1) = Situação 
                 sd04_v_registroconselho = varchar(13) = Registro 
                 sd04_c_sus = char(1) = SUS 
                 sd04_i_numerodias = int4 = Dias para Agendar 
                 sd04_d_folgaini = date = Início da Folga 
                 sd04_d_folgafim = date = Fim da Folga 
                 ";
   //funcao construtor da classe 
   function cl_unidademedicos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("unidademedicos"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->sd04_i_codigo = ($this->sd04_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_i_codigo"]:$this->sd04_i_codigo);
       $this->sd04_i_unidade = ($this->sd04_i_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_i_unidade"]:$this->sd04_i_unidade);
       $this->sd04_i_medico = ($this->sd04_i_medico == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_i_medico"]:$this->sd04_i_medico);
       $this->sd04_i_vinculo = ($this->sd04_i_vinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_i_vinculo"]:$this->sd04_i_vinculo);
       $this->sd04_i_tipovinc = ($this->sd04_i_tipovinc == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_i_tipovinc"]:$this->sd04_i_tipovinc);
       $this->sd04_i_subtipovinc = ($this->sd04_i_subtipovinc == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_i_subtipovinc"]:$this->sd04_i_subtipovinc);
       $this->sd04_i_horaamb = ($this->sd04_i_horaamb == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_i_horaamb"]:$this->sd04_i_horaamb);
       $this->sd04_i_horahosp = ($this->sd04_i_horahosp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_i_horahosp"]:$this->sd04_i_horahosp);
       $this->sd04_i_horaoutros = ($this->sd04_i_horaoutros == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_i_horaoutros"]:$this->sd04_i_horaoutros);
       $this->sd04_i_orgaoemissor = ($this->sd04_i_orgaoemissor == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_i_orgaoemissor"]:$this->sd04_i_orgaoemissor);
       $this->sd04_c_situacao = ($this->sd04_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_c_situacao"]:$this->sd04_c_situacao);
       $this->sd04_v_registroconselho = ($this->sd04_v_registroconselho == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_v_registroconselho"]:$this->sd04_v_registroconselho);
       $this->sd04_c_sus = ($this->sd04_c_sus == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_c_sus"]:$this->sd04_c_sus);
       $this->sd04_i_numerodias = ($this->sd04_i_numerodias == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_i_numerodias"]:$this->sd04_i_numerodias);
       if($this->sd04_d_folgaini == ""){
         $this->sd04_d_folgaini_dia = ($this->sd04_d_folgaini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_d_folgaini_dia"]:$this->sd04_d_folgaini_dia);
         $this->sd04_d_folgaini_mes = ($this->sd04_d_folgaini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_d_folgaini_mes"]:$this->sd04_d_folgaini_mes);
         $this->sd04_d_folgaini_ano = ($this->sd04_d_folgaini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_d_folgaini_ano"]:$this->sd04_d_folgaini_ano);
         if($this->sd04_d_folgaini_dia != ""){
            $this->sd04_d_folgaini = $this->sd04_d_folgaini_ano."-".$this->sd04_d_folgaini_mes."-".$this->sd04_d_folgaini_dia;
         }
       }
       if($this->sd04_d_folgafim == ""){
         $this->sd04_d_folgafim_dia = ($this->sd04_d_folgafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_d_folgafim_dia"]:$this->sd04_d_folgafim_dia);
         $this->sd04_d_folgafim_mes = ($this->sd04_d_folgafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_d_folgafim_mes"]:$this->sd04_d_folgafim_mes);
         $this->sd04_d_folgafim_ano = ($this->sd04_d_folgafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_d_folgafim_ano"]:$this->sd04_d_folgafim_ano);
         if($this->sd04_d_folgafim_dia != ""){
            $this->sd04_d_folgafim = $this->sd04_d_folgafim_ano."-".$this->sd04_d_folgafim_mes."-".$this->sd04_d_folgafim_dia;
         }
       }
     }else{
       $this->sd04_i_codigo = ($this->sd04_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd04_i_codigo"]:$this->sd04_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($sd04_i_codigo){ 
      $this->atualizacampos();
     if($this->sd04_i_unidade == null ){ 
       $this->erro_sql = " Campo Unidade não informado.";
       $this->erro_campo = "sd04_i_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd04_i_medico == null ){ 
       $this->erro_sql = " Campo Profissional não informado.";
       $this->erro_campo = "sd04_i_medico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd04_i_vinculo == null ){ 
       $this->sd04_i_vinculo = "null";
     }
     if($this->sd04_i_tipovinc == null ){ 
       $this->sd04_i_tipovinc = "null";
     }
     if($this->sd04_i_subtipovinc == null ){ 
       $this->sd04_i_subtipovinc = "null";
     }
     if($this->sd04_i_horaamb == null ){ 
       $this->erro_sql = " Campo Hora Ambulatorial não informado.";
       $this->erro_campo = "sd04_i_horaamb";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd04_i_horahosp == null ){ 
       $this->erro_sql = " Campo Hora Hospitalar não informado.";
       $this->erro_campo = "sd04_i_horahosp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd04_i_horaoutros == null ){ 
       $this->erro_sql = " Campo Outras Horas não informado.";
       $this->erro_campo = "sd04_i_horaoutros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd04_i_orgaoemissor == null ){ 
       $this->sd04_i_orgaoemissor = "0";
     }
     if($this->sd04_c_situacao == null ){ 
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "sd04_c_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd04_c_sus == null ){ 
       $this->erro_sql = " Campo SUS não informado.";
       $this->erro_campo = "sd04_c_sus";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd04_i_numerodias == null ){ 
       $this->sd04_i_numerodias = "0";
     }
     if($this->sd04_d_folgaini == null ){ 
       $this->sd04_d_folgaini = "null";
     }
     if($this->sd04_d_folgafim == null ){ 
       $this->sd04_d_folgafim = "null";
     }
     if($sd04_i_codigo == "" || $sd04_i_codigo == null ){
       $result = db_query("select nextval('unidademedicos_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: unidademedicos_codigo_seq do campo: sd04_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd04_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from unidademedicos_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd04_i_codigo)){
         $this->erro_sql = " Campo sd04_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd04_i_codigo = $sd04_i_codigo; 
       }
     }
     if(($this->sd04_i_codigo == null) || ($this->sd04_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd04_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into unidademedicos(
                                       sd04_i_codigo 
                                      ,sd04_i_unidade 
                                      ,sd04_i_medico 
                                      ,sd04_i_vinculo 
                                      ,sd04_i_tipovinc 
                                      ,sd04_i_subtipovinc 
                                      ,sd04_i_horaamb 
                                      ,sd04_i_horahosp 
                                      ,sd04_i_horaoutros 
                                      ,sd04_i_orgaoemissor 
                                      ,sd04_c_situacao 
                                      ,sd04_v_registroconselho 
                                      ,sd04_c_sus 
                                      ,sd04_i_numerodias 
                                      ,sd04_d_folgaini 
                                      ,sd04_d_folgafim 
                       )
                values (
                                $this->sd04_i_codigo 
                               ,$this->sd04_i_unidade 
                               ,$this->sd04_i_medico 
                               ,$this->sd04_i_vinculo 
                               ,$this->sd04_i_tipovinc 
                               ,$this->sd04_i_subtipovinc 
                               ,'$this->sd04_i_horaamb' 
                               ,'$this->sd04_i_horahosp' 
                               ,'$this->sd04_i_horaoutros' 
                               ,$this->sd04_i_orgaoemissor 
                               ,'$this->sd04_c_situacao' 
                               ,'$this->sd04_v_registroconselho' 
                               ,'$this->sd04_c_sus' 
                               ,$this->sd04_i_numerodias 
                               ,".($this->sd04_d_folgaini == "null" || $this->sd04_d_folgaini == ""?"null":"'".$this->sd04_d_folgaini."'")." 
                               ,".($this->sd04_d_folgafim == "null" || $this->sd04_d_folgafim == ""?"null":"'".$this->sd04_d_folgafim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Médicos para Unidade ($this->sd04_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Médicos para Unidade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Médicos para Unidade ($this->sd04_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd04_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd04_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11695,'$this->sd04_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,100028,11695,'','".AddSlashes(pg_result($resaco,0,'sd04_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100028,11694,'','".AddSlashes(pg_result($resaco,0,'sd04_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100028,100129,'','".AddSlashes(pg_result($resaco,0,'sd04_i_medico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100028,11447,'','".AddSlashes(pg_result($resaco,0,'sd04_i_vinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100028,11448,'','".AddSlashes(pg_result($resaco,0,'sd04_i_tipovinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100028,11449,'','".AddSlashes(pg_result($resaco,0,'sd04_i_subtipovinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100028,11450,'','".AddSlashes(pg_result($resaco,0,'sd04_i_horaamb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100028,15069,'','".AddSlashes(pg_result($resaco,0,'sd04_i_horahosp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100028,11452,'','".AddSlashes(pg_result($resaco,0,'sd04_i_horaoutros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100028,11453,'','".AddSlashes(pg_result($resaco,0,'sd04_i_orgaoemissor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100028,1009058,'','".AddSlashes(pg_result($resaco,0,'sd04_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100028,11454,'','".AddSlashes(pg_result($resaco,0,'sd04_v_registroconselho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100028,11446,'','".AddSlashes(pg_result($resaco,0,'sd04_c_sus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100028,100077,'','".AddSlashes(pg_result($resaco,0,'sd04_i_numerodias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100028,100078,'','".AddSlashes(pg_result($resaco,0,'sd04_d_folgaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100028,100079,'','".AddSlashes(pg_result($resaco,0,'sd04_d_folgafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($sd04_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update unidademedicos set ";
     $virgula = "";
     if(trim($this->sd04_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_codigo"])){ 
       $sql  .= $virgula." sd04_i_codigo = $this->sd04_i_codigo ";
       $virgula = ",";
       if(trim($this->sd04_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "sd04_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd04_i_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_unidade"])){ 
       $sql  .= $virgula." sd04_i_unidade = $this->sd04_i_unidade ";
       $virgula = ",";
       if(trim($this->sd04_i_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade não informado.";
         $this->erro_campo = "sd04_i_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd04_i_medico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_medico"])){ 
       $sql  .= $virgula." sd04_i_medico = $this->sd04_i_medico ";
       $virgula = ",";
       if(trim($this->sd04_i_medico) == null ){ 
         $this->erro_sql = " Campo Profissional não informado.";
         $this->erro_campo = "sd04_i_medico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd04_i_vinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_vinculo"])){ 
        if(trim($this->sd04_i_vinculo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_vinculo"])){ 
           $this->sd04_i_vinculo = "null" ;
        } 
       $sql  .= $virgula." sd04_i_vinculo = $this->sd04_i_vinculo ";
       $virgula = ",";
     }
     if(trim($this->sd04_i_tipovinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_tipovinc"])){ 
        if(trim($this->sd04_i_tipovinc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_tipovinc"])){ 
           $this->sd04_i_tipovinc = "null" ;
        } 
       $sql  .= $virgula." sd04_i_tipovinc = $this->sd04_i_tipovinc ";
       $virgula = ",";
     }
     if(trim($this->sd04_i_subtipovinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_subtipovinc"])){ 
        if(trim($this->sd04_i_subtipovinc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_subtipovinc"])){ 
           $this->sd04_i_subtipovinc = "null" ;
        } 
       $sql  .= $virgula." sd04_i_subtipovinc = $this->sd04_i_subtipovinc ";
       $virgula = ",";
     }
     if(trim($this->sd04_i_horaamb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_horaamb"])){ 
       $sql  .= $virgula." sd04_i_horaamb = '$this->sd04_i_horaamb' ";
       $virgula = ",";
       if(trim($this->sd04_i_horaamb) == null ){ 
         $this->erro_sql = " Campo Hora Ambulatorial não informado.";
         $this->erro_campo = "sd04_i_horaamb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd04_i_horahosp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_horahosp"])){ 
       $sql  .= $virgula." sd04_i_horahosp = '$this->sd04_i_horahosp' ";
       $virgula = ",";
       if(trim($this->sd04_i_horahosp) == null ){ 
         $this->erro_sql = " Campo Hora Hospitalar não informado.";
         $this->erro_campo = "sd04_i_horahosp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd04_i_horaoutros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_horaoutros"])){ 
       $sql  .= $virgula." sd04_i_horaoutros = '$this->sd04_i_horaoutros' ";
       $virgula = ",";
       if(trim($this->sd04_i_horaoutros) == null ){ 
         $this->erro_sql = " Campo Outras Horas não informado.";
         $this->erro_campo = "sd04_i_horaoutros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd04_i_orgaoemissor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_orgaoemissor"])){ 
        if(trim($this->sd04_i_orgaoemissor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_orgaoemissor"])){ 
           $this->sd04_i_orgaoemissor = "0" ; 
        } 
       $sql  .= $virgula." sd04_i_orgaoemissor = $this->sd04_i_orgaoemissor ";
       $virgula = ",";
     }
     if(trim($this->sd04_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_c_situacao"])){ 
       $sql  .= $virgula." sd04_c_situacao = '$this->sd04_c_situacao' ";
       $virgula = ",";
       if(trim($this->sd04_c_situacao) == null ){ 
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "sd04_c_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd04_v_registroconselho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_v_registroconselho"])){ 
       $sql  .= $virgula." sd04_v_registroconselho = '$this->sd04_v_registroconselho' ";
       $virgula = ",";
     }
     if(trim($this->sd04_c_sus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_c_sus"])){ 
       $sql  .= $virgula." sd04_c_sus = '$this->sd04_c_sus' ";
       $virgula = ",";
       if(trim($this->sd04_c_sus) == null ){ 
         $this->erro_sql = " Campo SUS não informado.";
         $this->erro_campo = "sd04_c_sus";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd04_i_numerodias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_numerodias"])){ 
        if(trim($this->sd04_i_numerodias)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_numerodias"])){ 
           $this->sd04_i_numerodias = "0" ; 
        } 
       $sql  .= $virgula." sd04_i_numerodias = $this->sd04_i_numerodias ";
       $virgula = ",";
     }
     if(trim($this->sd04_d_folgaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_d_folgaini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd04_d_folgaini_dia"] !="") ){ 
       $sql  .= $virgula." sd04_d_folgaini = '$this->sd04_d_folgaini' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd04_d_folgaini_dia"])){ 
         $sql  .= $virgula." sd04_d_folgaini = null ";
         $virgula = ",";
       }
     }
     if(trim($this->sd04_d_folgafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd04_d_folgafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd04_d_folgafim_dia"] !="") ){ 
       $sql  .= $virgula." sd04_d_folgafim = '$this->sd04_d_folgafim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd04_d_folgafim_dia"])){ 
         $sql  .= $virgula." sd04_d_folgafim = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($sd04_i_codigo!=null){
       $sql .= " sd04_i_codigo = $this->sd04_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd04_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,11695,'$this->sd04_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_codigo"]) || $this->sd04_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,100028,11695,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_i_codigo'))."','$this->sd04_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_unidade"]) || $this->sd04_i_unidade != "")
             $resac = db_query("insert into db_acount values($acount,100028,11694,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_i_unidade'))."','$this->sd04_i_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_medico"]) || $this->sd04_i_medico != "")
             $resac = db_query("insert into db_acount values($acount,100028,100129,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_i_medico'))."','$this->sd04_i_medico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_vinculo"]) || $this->sd04_i_vinculo != "")
             $resac = db_query("insert into db_acount values($acount,100028,11447,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_i_vinculo'))."','$this->sd04_i_vinculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_tipovinc"]) || $this->sd04_i_tipovinc != "")
             $resac = db_query("insert into db_acount values($acount,100028,11448,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_i_tipovinc'))."','$this->sd04_i_tipovinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_subtipovinc"]) || $this->sd04_i_subtipovinc != "")
             $resac = db_query("insert into db_acount values($acount,100028,11449,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_i_subtipovinc'))."','$this->sd04_i_subtipovinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_horaamb"]) || $this->sd04_i_horaamb != "")
             $resac = db_query("insert into db_acount values($acount,100028,11450,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_i_horaamb'))."','$this->sd04_i_horaamb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_horahosp"]) || $this->sd04_i_horahosp != "")
             $resac = db_query("insert into db_acount values($acount,100028,15069,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_i_horahosp'))."','$this->sd04_i_horahosp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_horaoutros"]) || $this->sd04_i_horaoutros != "")
             $resac = db_query("insert into db_acount values($acount,100028,11452,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_i_horaoutros'))."','$this->sd04_i_horaoutros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_orgaoemissor"]) || $this->sd04_i_orgaoemissor != "")
             $resac = db_query("insert into db_acount values($acount,100028,11453,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_i_orgaoemissor'))."','$this->sd04_i_orgaoemissor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_c_situacao"]) || $this->sd04_c_situacao != "")
             $resac = db_query("insert into db_acount values($acount,100028,1009058,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_c_situacao'))."','$this->sd04_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_v_registroconselho"]) || $this->sd04_v_registroconselho != "")
             $resac = db_query("insert into db_acount values($acount,100028,11454,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_v_registroconselho'))."','$this->sd04_v_registroconselho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_c_sus"]) || $this->sd04_c_sus != "")
             $resac = db_query("insert into db_acount values($acount,100028,11446,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_c_sus'))."','$this->sd04_c_sus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_i_numerodias"]) || $this->sd04_i_numerodias != "")
             $resac = db_query("insert into db_acount values($acount,100028,100077,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_i_numerodias'))."','$this->sd04_i_numerodias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_d_folgaini"]) || $this->sd04_d_folgaini != "")
             $resac = db_query("insert into db_acount values($acount,100028,100078,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_d_folgaini'))."','$this->sd04_d_folgaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd04_d_folgafim"]) || $this->sd04_d_folgafim != "")
             $resac = db_query("insert into db_acount values($acount,100028,100079,'".AddSlashes(pg_result($resaco,$conresaco,'sd04_d_folgafim'))."','$this->sd04_d_folgafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Médicos para Unidade não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd04_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Médicos para Unidade não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd04_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd04_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($sd04_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($sd04_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,11695,'$sd04_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,100028,11695,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100028,11694,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100028,100129,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_i_medico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100028,11447,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_i_vinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100028,11448,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_i_tipovinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100028,11449,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_i_subtipovinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100028,11450,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_i_horaamb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100028,15069,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_i_horahosp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100028,11452,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_i_horaoutros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100028,11453,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_i_orgaoemissor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100028,1009058,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100028,11454,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_v_registroconselho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100028,11446,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_c_sus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100028,100077,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_i_numerodias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100028,100078,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_d_folgaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100028,100079,'','".AddSlashes(pg_result($resaco,$iresaco,'sd04_d_folgafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from unidademedicos
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sd04_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sd04_i_codigo = $sd04_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Médicos para Unidade não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd04_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Médicos para Unidade não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd04_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd04_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:unidademedicos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($sd04_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= " from unidademedicos ";
     $sql .= "      left join sau_orgaoemissor  on  sau_orgaoemissor.sd51_i_codigo = unidademedicos.sd04_i_orgaoemissor";
     $sql .= "      left join sau_modvinculo  on  sau_modvinculo.sd52_i_vinculacao = unidademedicos.sd04_i_vinculo";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = unidades.sd02_i_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "      left join sau_esferaadmin  on  sau_esferaadmin.sd37_i_cod_esfadm = unidades.sd02_i_cod_esfadm";
     $sql .= "      left join sau_atividadeensino  on  sau_atividadeensino.sd38_i_cod_ativid = unidades.sd02_i_cod_ativ";
     $sql .= "      left join sau_retentributo  on  sau_retentributo.sd39_i_cod_reten = unidades.sd02_i_reten_trib";
     $sql .= "      left join sau_natorg  on  sau_natorg.sd40_i_cod_natorg = unidades.sd02_i_cod_natorg";
     $sql .= "      left join sau_fluxocliente  on  sau_fluxocliente.sd41_i_cod_cliente = unidades.sd02_i_cod_client";
     $sql .= "      left join sau_tipounidade  on  sau_tipounidade.sd42_i_tp_unid_id = unidades.sd02_i_tp_unid_id";
     $sql .= "      left join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = unidades.sd02_i_cod_turnat";
     $sql .= "      left join sau_nivelhier  on  sau_nivelhier.sd44_i_codnivhier = unidades.sd02_i_codnivhier";
     $sql .= "      left join sau_tpmodvinculo on sau_tpmodvinculo.sd53_i_vinculacao = sd04_i_vinculo
                                               and sau_tpmodvinculo.sd53_i_tpvinculo  = sd04_i_tipovinc
             ";
     $sql .= "       left join sau_subtpmodvinculo on sau_subtpmodvinculo.sd54_i_vinculacao = unidademedicos.sd04_i_vinculo
                                                  and sau_subtpmodvinculo.sd54_i_tpvinculo  = unidademedicos.sd04_i_tipovinc
                                                  and sau_subtpmodvinculo.sd54_i_tpsubvinculo = unidademedicos.sd04_i_subtipovinc
             ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd04_i_codigo)) {
         $sql2 .= " where unidademedicos.sd04_i_codigo = $sd04_i_codigo "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($sd04_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from unidademedicos ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd04_i_codigo)){
         $sql2 .= " where unidademedicos.sd04_i_codigo = $sd04_i_codigo "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

  /**
   * Query para buscar código do médico vinculado a unidademedicos através do CBO Profissionao da triagem avulsa
   */
  function sql_query_medico ( $sd04_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from unidademedicos ";
    $sql .= " inner join far_cbosprofissional on far_cbosprofissional.fa54_i_unidademedico = unidademedicos.sd04_i_codigo ";
    $sql .= " inner join sau_triagemavulsa    on sau_triagemavulsa.s152_i_cbosprofissional = far_cbosprofissional.fa54_i_codigo ";
    $sql2 = "";
    if($dbwhere==""){
      if($sd04_i_codigo!=null ){
        $sql2 .= " where unidademedicos.sd04_i_codigo = $sd04_i_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  /**
   * Query para buscar o CBOS dos Médicos
   */
  function sql_query_cbos ( $sd04_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from unidademedicos ";
    $sql .= "      left  join far_cbosprofissional  on  unidademedicos.sd04_i_codigo = far_cbosprofissional.fa54_i_unidademedico";
    $sql .= "      left  join far_cbos              on far_cbos.fa53_i_codigo        = far_cbosprofissional.fa54_i_cbos";
    $sql .= "      inner join unidades              on  unidades.sd02_i_codigo       = unidademedicos.sd04_i_unidade";
    $sql .= "      inner join medicos               on  medicos.sd03_i_codigo        = unidademedicos.sd04_i_medico";
    $sql2 = "";
    if($dbwhere==""){
      if($sd04_i_codigo!=null ){
        $sql2 .= " where unidademedicos.sd04_i_codigo = $sd04_i_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
