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

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE prontuarios
class cl_prontuarios {
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
   var $sd24_i_codigo = 0;
   var $sd24_i_ano = 0;
   var $sd24_i_mes = 0;
   var $sd24_i_seq = 0;
   var $sd24_i_unidade = 0;
   var $sd24_i_numcgs = 0;
   var $sd24_v_motivo = null;
   var $sd24_d_cadastro_dia = null;
   var $sd24_d_cadastro_mes = null;
   var $sd24_d_cadastro_ano = null;
   var $sd24_d_cadastro = null;
   var $sd24_c_cadastro = null;
   var $sd24_v_pressao = null;
   var $sd24_f_peso = 0;
   var $sd24_f_temperatura = 0;
   var $sd24_i_profissional = 0;
   var $sd24_t_diagnostico = null;
   var $sd24_i_siasih = 0;
   var $sd24_c_digitada = null;
   var $sd24_i_login = 0;
   var $sd24_i_motivo = 0;
   var $sd24_i_tipo = 0;
   var $sd24_i_acaoprog = 0;
   var $sd24_setorambulatorial = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 sd24_i_codigo = int4 = FAA
                 sd24_i_ano = int4 = Ano
                 sd24_i_mes = int4 = Mês
                 sd24_i_seq = int4 = Sequência
                 sd24_i_unidade = int4 = UPS
                 sd24_i_numcgs = int4 = CGS
                 sd24_v_motivo = varchar(200) = Observação
                 sd24_d_cadastro = date = Data Atendimento
                 sd24_c_cadastro = varchar(20) = Hora Atendimento
                 sd24_v_pressao = varchar(7) = Pressão
                 sd24_f_peso = float4 = Peso
                 sd24_f_temperatura = float4 = Temperatura
                 sd24_i_profissional = int4 = Profissional do Atendimento
                 sd24_t_diagnostico = text = Diagnóstico
                 sd24_i_siasih = int8 = Motivo SIA/SIH
                 sd24_c_digitada = char(1) = Digitada
                 sd24_i_login = int4 = Login
                 sd24_i_motivo = int4 = Motivo
                 sd24_i_tipo = int4 = Tipo de atendimento
                 sd24_i_acaoprog = int4 = Ação programatica
                 sd24_setorambulatorial = int4 = Setor ambulatorial
                 ";
   //funcao construtor da classe
   function cl_prontuarios() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("prontuarios");
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
       $this->sd24_i_codigo = ($this->sd24_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_i_codigo"]:$this->sd24_i_codigo);
       $this->sd24_i_ano = ($this->sd24_i_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_i_ano"]:$this->sd24_i_ano);
       $this->sd24_i_mes = ($this->sd24_i_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_i_mes"]:$this->sd24_i_mes);
       $this->sd24_i_seq = ($this->sd24_i_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_i_seq"]:$this->sd24_i_seq);
       $this->sd24_i_unidade = ($this->sd24_i_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_i_unidade"]:$this->sd24_i_unidade);
       $this->sd24_i_numcgs = ($this->sd24_i_numcgs == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_i_numcgs"]:$this->sd24_i_numcgs);
       $this->sd24_v_motivo = ($this->sd24_v_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_v_motivo"]:$this->sd24_v_motivo);
       if($this->sd24_d_cadastro == ""){
         $this->sd24_d_cadastro_dia = ($this->sd24_d_cadastro_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_d_cadastro_dia"]:$this->sd24_d_cadastro_dia);
         $this->sd24_d_cadastro_mes = ($this->sd24_d_cadastro_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_d_cadastro_mes"]:$this->sd24_d_cadastro_mes);
         $this->sd24_d_cadastro_ano = ($this->sd24_d_cadastro_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_d_cadastro_ano"]:$this->sd24_d_cadastro_ano);
         if($this->sd24_d_cadastro_dia != ""){
            $this->sd24_d_cadastro = $this->sd24_d_cadastro_ano."-".$this->sd24_d_cadastro_mes."-".$this->sd24_d_cadastro_dia;
         }
       }
       $this->sd24_c_cadastro = ($this->sd24_c_cadastro == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_c_cadastro"]:$this->sd24_c_cadastro);
       $this->sd24_v_pressao = ($this->sd24_v_pressao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_v_pressao"]:$this->sd24_v_pressao);
       $this->sd24_f_peso = ($this->sd24_f_peso == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_f_peso"]:$this->sd24_f_peso);
       $this->sd24_f_temperatura = ($this->sd24_f_temperatura == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_f_temperatura"]:$this->sd24_f_temperatura);
       $this->sd24_i_profissional = ($this->sd24_i_profissional == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_i_profissional"]:$this->sd24_i_profissional);
       $this->sd24_t_diagnostico = ($this->sd24_t_diagnostico == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_t_diagnostico"]:$this->sd24_t_diagnostico);
       $this->sd24_i_siasih = ($this->sd24_i_siasih == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_i_siasih"]:$this->sd24_i_siasih);
       $this->sd24_c_digitada = ($this->sd24_c_digitada == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_c_digitada"]:$this->sd24_c_digitada);
       $this->sd24_i_login = ($this->sd24_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_i_login"]:$this->sd24_i_login);
       $this->sd24_i_motivo = ($this->sd24_i_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_i_motivo"]:$this->sd24_i_motivo);
       $this->sd24_i_tipo = ($this->sd24_i_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_i_tipo"]:$this->sd24_i_tipo);
       $this->sd24_i_acaoprog = ($this->sd24_i_acaoprog == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_i_acaoprog"]:$this->sd24_i_acaoprog);
       $this->sd24_setorambulatorial = ($this->sd24_setorambulatorial == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_setorambulatorial"]:$this->sd24_setorambulatorial);
     }else{
       $this->sd24_i_codigo = ($this->sd24_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd24_i_codigo"]:$this->sd24_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd24_i_codigo){
      $this->atualizacampos();
     if($this->sd24_i_ano == null ){
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "sd24_i_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd24_i_mes == null ){
       $this->erro_sql = " Campo Mês não informado.";
       $this->erro_campo = "sd24_i_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd24_i_seq == null ){
       $this->erro_sql = " Campo Sequência não informado.";
       $this->erro_campo = "sd24_i_seq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd24_i_unidade == null ){
       $this->erro_sql = " Campo UPS não informado.";
       $this->erro_campo = "sd24_i_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd24_i_numcgs == null ){
       $this->sd24_i_numcgs = "null";
     }
     if($this->sd24_d_cadastro == null ){
       $this->erro_sql = " Campo Data Atendimento não informado.";
       $this->erro_campo = "sd24_d_cadastro_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd24_c_cadastro == null ){
       $this->erro_sql = " Campo Hora Atendimento não informado.";
       $this->erro_campo = "sd24_c_cadastro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd24_f_peso == null ){
       $this->sd24_f_peso = "null";
     }
     if($this->sd24_f_temperatura == null ){
       $this->sd24_f_temperatura = "null";
     }
     if($this->sd24_i_profissional == null ){
       $this->sd24_i_profissional = "null";
     }
     if($this->sd24_i_siasih == null ){
       $this->sd24_i_siasih = "null";
     }
     if($this->sd24_c_digitada == null ){
       $this->sd24_c_digitada = "N";
     }
     if($this->sd24_i_login == null ){
       $this->erro_sql = " Campo Login não informado.";
       $this->erro_campo = "sd24_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd24_i_motivo == null ){
       $this->sd24_i_motivo = "null";
     }
     if($this->sd24_i_tipo == null ){
       $this->sd24_i_tipo = "null";
     }
     if($this->sd24_i_acaoprog == null ){
       $this->sd24_i_acaoprog = "null";
     }
     if($this->sd24_setorambulatorial == null ){
       $this->erro_sql = " Campo Setor ambulatorial não informado.";
       $this->erro_campo = "sd24_setorambulatorial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd24_i_codigo == "" || $sd24_i_codigo == null ){
       $result = db_query("select nextval('prontuarios_sd24_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: prontuarios_sd24_i_codigo_seq do campo: sd24_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->sd24_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from prontuarios_sd24_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd24_i_codigo)){
         $this->erro_sql = " Campo sd24_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd24_i_codigo = $sd24_i_codigo;
       }
     }
     if(($this->sd24_i_codigo == null) || ($this->sd24_i_codigo == "") ){
       $this->erro_sql = " Campo sd24_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into prontuarios(
                                       sd24_i_codigo
                                      ,sd24_i_ano
                                      ,sd24_i_mes
                                      ,sd24_i_seq
                                      ,sd24_i_unidade
                                      ,sd24_i_numcgs
                                      ,sd24_v_motivo
                                      ,sd24_d_cadastro
                                      ,sd24_c_cadastro
                                      ,sd24_v_pressao
                                      ,sd24_f_peso
                                      ,sd24_f_temperatura
                                      ,sd24_i_profissional
                                      ,sd24_t_diagnostico
                                      ,sd24_i_siasih
                                      ,sd24_c_digitada
                                      ,sd24_i_login
                                      ,sd24_i_motivo
                                      ,sd24_i_tipo
                                      ,sd24_i_acaoprog
                                      ,sd24_setorambulatorial
                       )
                values (
                                $this->sd24_i_codigo
                               ,$this->sd24_i_ano
                               ,$this->sd24_i_mes
                               ,$this->sd24_i_seq
                               ,$this->sd24_i_unidade
                               ,$this->sd24_i_numcgs
                               ,'$this->sd24_v_motivo'
                               ,".($this->sd24_d_cadastro == "null" || $this->sd24_d_cadastro == ""?"null":"'".$this->sd24_d_cadastro."'")."
                               ,'$this->sd24_c_cadastro'
                               ,'$this->sd24_v_pressao'
                               ,$this->sd24_f_peso
                               ,$this->sd24_f_temperatura
                               ,$this->sd24_i_profissional
                               ,'$this->sd24_t_diagnostico'
                               ,$this->sd24_i_siasih
                               ,'$this->sd24_c_digitada'
                               ,$this->sd24_i_login
                               ,$this->sd24_i_motivo
                               ,$this->sd24_i_tipo
                               ,$this->sd24_i_acaoprog
                               ,$this->sd24_setorambulatorial
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Prontuários ($this->sd24_i_codigo) nao Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Prontuários já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Prontuários ($this->sd24_i_codigo) nao Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd24_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd24_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,100172,'$this->sd24_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010134,100172,'','".AddSlashes(pg_result($resaco,0,'sd24_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,1008805,'','".AddSlashes(pg_result($resaco,0,'sd24_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,1008806,'','".AddSlashes(pg_result($resaco,0,'sd24_i_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,1008807,'','".AddSlashes(pg_result($resaco,0,'sd24_i_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,100175,'','".AddSlashes(pg_result($resaco,0,'sd24_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,1008740,'','".AddSlashes(pg_result($resaco,0,'sd24_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,100187,'','".AddSlashes(pg_result($resaco,0,'sd24_v_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,1008910,'','".AddSlashes(pg_result($resaco,0,'sd24_d_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,1008911,'','".AddSlashes(pg_result($resaco,0,'sd24_c_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,11821,'','".AddSlashes(pg_result($resaco,0,'sd24_v_pressao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,11822,'','".AddSlashes(pg_result($resaco,0,'sd24_f_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,11823,'','".AddSlashes(pg_result($resaco,0,'sd24_f_temperatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,11824,'','".AddSlashes(pg_result($resaco,0,'sd24_i_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,11884,'','".AddSlashes(pg_result($resaco,0,'sd24_t_diagnostico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,100179,'','".AddSlashes(pg_result($resaco,0,'sd24_i_siasih'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,12018,'','".AddSlashes(pg_result($resaco,0,'sd24_c_digitada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,12264,'','".AddSlashes(pg_result($resaco,0,'sd24_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,15538,'','".AddSlashes(pg_result($resaco,0,'sd24_i_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,15539,'','".AddSlashes(pg_result($resaco,0,'sd24_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,15540,'','".AddSlashes(pg_result($resaco,0,'sd24_i_acaoprog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010134,20943,'','".AddSlashes(pg_result($resaco,0,'sd24_setorambulatorial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($sd24_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update prontuarios set ";
     $virgula = "";
     if(trim($this->sd24_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_codigo"])){
       $sql  .= $virgula." sd24_i_codigo = $this->sd24_i_codigo ";
       $virgula = ",";
       if(trim($this->sd24_i_codigo) == null ){
         $this->erro_sql = " Campo FAA não informado.";
         $this->erro_campo = "sd24_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd24_i_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_ano"])){
       $sql  .= $virgula." sd24_i_ano = $this->sd24_i_ano ";
       $virgula = ",";
       if(trim($this->sd24_i_ano) == null ){
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "sd24_i_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd24_i_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_mes"])){
       $sql  .= $virgula." sd24_i_mes = $this->sd24_i_mes ";
       $virgula = ",";
       if(trim($this->sd24_i_mes) == null ){
         $this->erro_sql = " Campo Mês não informado.";
         $this->erro_campo = "sd24_i_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd24_i_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_seq"])){
       $sql  .= $virgula." sd24_i_seq = $this->sd24_i_seq ";
       $virgula = ",";
       if(trim($this->sd24_i_seq) == null ){
         $this->erro_sql = " Campo Sequência não informado.";
         $this->erro_campo = "sd24_i_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd24_i_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_unidade"])){
       $sql  .= $virgula." sd24_i_unidade = $this->sd24_i_unidade ";
       $virgula = ",";
       if(trim($this->sd24_i_unidade) == null ){
         $this->erro_sql = " Campo UPS não informado.";
         $this->erro_campo = "sd24_i_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd24_i_numcgs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_numcgs"])){
        if(trim($this->sd24_i_numcgs)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_numcgs"])){
           $this->sd24_i_numcgs = "0" ;
        }
       $sql  .= $virgula." sd24_i_numcgs = $this->sd24_i_numcgs ";
       $virgula = ",";
     }
     if(trim($this->sd24_v_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_v_motivo"])){
       $sql  .= $virgula." sd24_v_motivo = '$this->sd24_v_motivo' ";
       $virgula = ",";
     }
     if(trim($this->sd24_d_cadastro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_d_cadastro_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd24_d_cadastro_dia"] !="") ){
       $sql  .= $virgula." sd24_d_cadastro = '$this->sd24_d_cadastro' ";
       $virgula = ",";
       if(trim($this->sd24_d_cadastro) == null ){
         $this->erro_sql = " Campo Data Atendimento não informado.";
         $this->erro_campo = "sd24_d_cadastro_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd24_d_cadastro_dia"])){
         $sql  .= $virgula." sd24_d_cadastro = null ";
         $virgula = ",";
         if(trim($this->sd24_d_cadastro) == null ){
           $this->erro_sql = " Campo Data Atendimento não informado.";
           $this->erro_campo = "sd24_d_cadastro_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd24_c_cadastro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_c_cadastro"])){
       $sql  .= $virgula." sd24_c_cadastro = '$this->sd24_c_cadastro' ";
       $virgula = ",";
       if(trim($this->sd24_c_cadastro) == null ){
         $this->erro_sql = " Campo Hora Atendimento não informado.";
         $this->erro_campo = "sd24_c_cadastro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd24_v_pressao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_v_pressao"])){
       $sql  .= $virgula." sd24_v_pressao = '$this->sd24_v_pressao' ";
       $virgula = ",";
     }
     if(trim($this->sd24_f_peso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_f_peso"])){
        if(trim($this->sd24_f_peso)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd24_f_peso"])){
           $this->sd24_f_peso = "0" ;
        }
       $sql  .= $virgula." sd24_f_peso = $this->sd24_f_peso ";
       $virgula = ",";
     }
     if(trim($this->sd24_f_temperatura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_f_temperatura"])){
        if(trim($this->sd24_f_temperatura)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd24_f_temperatura"])){
           $this->sd24_f_temperatura = "0" ;
        }
       $sql  .= $virgula." sd24_f_temperatura = $this->sd24_f_temperatura ";
       $virgula = ",";
     }
     if(trim($this->sd24_i_profissional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_profissional"])){
        if(trim($this->sd24_i_profissional)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_profissional"])){
           $this->sd24_i_profissional = "0" ;
        }
       $sql  .= $virgula." sd24_i_profissional = $this->sd24_i_profissional ";
       $virgula = ",";
     }
     if(trim($this->sd24_t_diagnostico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_t_diagnostico"])){
       $sql  .= $virgula." sd24_t_diagnostico = '$this->sd24_t_diagnostico' ";
       $virgula = ",";
     }
     if(trim($this->sd24_i_siasih)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_siasih"])){
        if(trim($this->sd24_i_siasih)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_siasih"])){
           $this->sd24_i_siasih = "0" ;
        }
       $sql  .= $virgula." sd24_i_siasih = $this->sd24_i_siasih ";
       $virgula = ",";
     }
     if(trim($this->sd24_c_digitada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_c_digitada"])){
       $sql  .= $virgula." sd24_c_digitada = '$this->sd24_c_digitada' ";
       $virgula = ",";
     }
     if(trim($this->sd24_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_login"])){
       $sql  .= $virgula." sd24_i_login = $this->sd24_i_login ";
       $virgula = ",";
       if(trim($this->sd24_i_login) == null ){
         $this->erro_sql = " Campo Login não informado.";
         $this->erro_campo = "sd24_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd24_i_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_motivo"])){
        if(trim($this->sd24_i_motivo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_motivo"])){
           $this->sd24_i_motivo = "0" ;
        }
       $sql  .= $virgula." sd24_i_motivo = $this->sd24_i_motivo ";
       $virgula = ",";
     }
     if(trim($this->sd24_i_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_tipo"])){
        if(trim($this->sd24_i_tipo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_tipo"])){
           $this->sd24_i_tipo = "0" ;
        }
       $sql  .= $virgula." sd24_i_tipo = $this->sd24_i_tipo ";
       $virgula = ",";
     }
     if(trim($this->sd24_i_acaoprog)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_acaoprog"])){
        if(trim($this->sd24_i_acaoprog)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_acaoprog"])){
           $this->sd24_i_acaoprog = "0" ;
        }
       $sql  .= $virgula." sd24_i_acaoprog = $this->sd24_i_acaoprog ";
       $virgula = ",";
     }
     if(trim($this->sd24_setorambulatorial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd24_setorambulatorial"])){
       $sql  .= $virgula." sd24_setorambulatorial = $this->sd24_setorambulatorial ";
       $virgula = ",";
       if(trim($this->sd24_setorambulatorial) == null ){
         $this->erro_sql = " Campo Setor ambulatorial não informado.";
         $this->erro_campo = "sd24_setorambulatorial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd24_i_codigo!=null){
       $sql .= " sd24_i_codigo = $this->sd24_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd24_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,100172,'$this->sd24_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_codigo"]) || $this->sd24_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010134,100172,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_i_codigo'))."','$this->sd24_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_ano"]) || $this->sd24_i_ano != "")
             $resac = db_query("insert into db_acount values($acount,1010134,1008805,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_i_ano'))."','$this->sd24_i_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_mes"]) || $this->sd24_i_mes != "")
             $resac = db_query("insert into db_acount values($acount,1010134,1008806,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_i_mes'))."','$this->sd24_i_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_seq"]) || $this->sd24_i_seq != "")
             $resac = db_query("insert into db_acount values($acount,1010134,1008807,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_i_seq'))."','$this->sd24_i_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_unidade"]) || $this->sd24_i_unidade != "")
             $resac = db_query("insert into db_acount values($acount,1010134,100175,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_i_unidade'))."','$this->sd24_i_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_numcgs"]) || $this->sd24_i_numcgs != "")
             $resac = db_query("insert into db_acount values($acount,1010134,1008740,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_i_numcgs'))."','$this->sd24_i_numcgs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_v_motivo"]) || $this->sd24_v_motivo != "")
             $resac = db_query("insert into db_acount values($acount,1010134,100187,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_v_motivo'))."','$this->sd24_v_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_d_cadastro"]) || $this->sd24_d_cadastro != "")
             $resac = db_query("insert into db_acount values($acount,1010134,1008910,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_d_cadastro'))."','$this->sd24_d_cadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_c_cadastro"]) || $this->sd24_c_cadastro != "")
             $resac = db_query("insert into db_acount values($acount,1010134,1008911,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_c_cadastro'))."','$this->sd24_c_cadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_v_pressao"]) || $this->sd24_v_pressao != "")
             $resac = db_query("insert into db_acount values($acount,1010134,11821,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_v_pressao'))."','$this->sd24_v_pressao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_f_peso"]) || $this->sd24_f_peso != "")
             $resac = db_query("insert into db_acount values($acount,1010134,11822,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_f_peso'))."','$this->sd24_f_peso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_f_temperatura"]) || $this->sd24_f_temperatura != "")
             $resac = db_query("insert into db_acount values($acount,1010134,11823,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_f_temperatura'))."','$this->sd24_f_temperatura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_profissional"]) || $this->sd24_i_profissional != "")
             $resac = db_query("insert into db_acount values($acount,1010134,11824,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_i_profissional'))."','$this->sd24_i_profissional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_t_diagnostico"]) || $this->sd24_t_diagnostico != "")
             $resac = db_query("insert into db_acount values($acount,1010134,11884,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_t_diagnostico'))."','$this->sd24_t_diagnostico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_siasih"]) || $this->sd24_i_siasih != "")
             $resac = db_query("insert into db_acount values($acount,1010134,100179,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_i_siasih'))."','$this->sd24_i_siasih',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_c_digitada"]) || $this->sd24_c_digitada != "")
             $resac = db_query("insert into db_acount values($acount,1010134,12018,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_c_digitada'))."','$this->sd24_c_digitada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_login"]) || $this->sd24_i_login != "")
             $resac = db_query("insert into db_acount values($acount,1010134,12264,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_i_login'))."','$this->sd24_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_motivo"]) || $this->sd24_i_motivo != "")
             $resac = db_query("insert into db_acount values($acount,1010134,15538,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_i_motivo'))."','$this->sd24_i_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_tipo"]) || $this->sd24_i_tipo != "")
             $resac = db_query("insert into db_acount values($acount,1010134,15539,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_i_tipo'))."','$this->sd24_i_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_i_acaoprog"]) || $this->sd24_i_acaoprog != "")
             $resac = db_query("insert into db_acount values($acount,1010134,15540,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_i_acaoprog'))."','$this->sd24_i_acaoprog',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd24_setorambulatorial"]) || $this->sd24_setorambulatorial != "")
             $resac = db_query("insert into db_acount values($acount,1010134,20943,'".AddSlashes(pg_result($resaco,$conresaco,'sd24_setorambulatorial'))."','$this->sd24_setorambulatorial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prontuários nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd24_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Prontuários nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd24_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd24_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($sd24_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($sd24_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,100172,'$sd24_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010134,100172,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,1008805,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,1008806,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_i_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,1008807,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_i_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,100175,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,1008740,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,100187,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_v_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,1008910,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_d_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,1008911,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_c_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,11821,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_v_pressao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,11822,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_f_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,11823,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_f_temperatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,11824,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_i_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,11884,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_t_diagnostico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,100179,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_i_siasih'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,12018,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_c_digitada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,12264,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,15538,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_i_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,15539,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,15540,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_i_acaoprog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010134,20943,'','".AddSlashes(pg_result($resaco,$iresaco,'sd24_setorambulatorial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from prontuarios
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sd24_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sd24_i_codigo = $sd24_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prontuários nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd24_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Prontuários nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd24_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd24_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:prontuarios";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($sd24_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from prontuarios ";
     $sql .= "     inner join unidades  on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
     $sql .= "     inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "     inner join db_usuarios on  db_usuarios.id_usuario = prontuarios.sd24_i_login";
     $sql .= "      left join cgs  on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql .= "      left join cgs_und  on  cgs_und.z01_i_cgsund = prontuarios.sd24_i_numcgs";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = unidades.sd02_i_numcgm ";
     $sql .= "      left join cgm d on  d.z01_numcgm = unidades.sd02_i_diretor";
//     $sql .= "      left join unidademedicos  on  unidademedicos.sd04_i_codigo = prontuarios.sd24_i_profissional";
     $sql .= "      left join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
     $sql .= "      left join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
//     $sql .= "      left join rhcbo  on  rhcbo.rh70_sequencial = unidademedicos.sd04_i_cbo";
     $sql .= "      left join rhcbo  on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";
//     $sql .= "      left join unidades  as a on   a.sd02_i_codigo = unidademedicos.sd04_i_unidade";
     $sql .= "      left join medicos  on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      left join cgm m on  m.z01_numcgm = medicos.sd03_i_cgm";
     //$sql .= "      left join sau_cid  on  sau_cid.sd70_i_codigo = prontuarios.sd24_i_cid";
     $sql .= "      left join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
     $sql .= "      left join sau_tipoproc  on  sau_tipoproc.sd93_i_codigo = sau_siasih.sd92_i_tipoproc";
     $sql .= "      left join sau_esferaadmin  on  sau_esferaadmin.sd37_i_cod_esfadm = unidades.sd02_i_cod_esfadm";
     $sql .= "      left join sau_atividadeensino  on  sau_atividadeensino.sd38_i_cod_ativid = unidades.sd02_i_cod_ativ";
     $sql .= "      left join sau_retentributo  on  sau_retentributo.sd39_i_cod_reten = unidades.sd02_i_reten_trib";
     $sql .= "      left join sau_natorg  on  sau_natorg.sd40_i_cod_natorg = unidades.sd02_i_cod_natorg";
     $sql .= "      left join sau_fluxocliente  on  sau_fluxocliente.sd41_i_cod_cliente = unidades.sd02_i_cod_client";
     $sql .= "      left join sau_tipounidade  on  sau_tipounidade.sd42_i_tp_unid_id = unidades.sd02_i_tp_unid_id";
     $sql .= "      left join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = unidades.sd02_i_cod_turnat";
     $sql .= "      left join sau_nivelhier  on  sau_nivelhier.sd44_i_codnivhier = unidades.sd02_i_codnivhier";
     $sql .= "      left join sau_orgaoemissor  on  sau_orgaoemissor.sd51_i_codigo = unidademedicos.sd04_i_orgaoemissor";
     $sql .= "      left join sau_modvinculo  on  sau_modvinculo.sd52_i_vinculacao = unidademedicos.sd04_i_vinculo";
     $sql .= "      left join sau_motivoatendimento  on  prontuarios.sd24_i_motivo = sau_motivoatendimento.s144_i_codigo";
     $sql .= "      left join sau_tiposatendimento  on  prontuarios.sd24_i_tipo = sau_tiposatendimento.s145_i_codigo";
     $sql .= "      left join far_programa  on  far_programa.fa12_i_codigo = prontuarios.sd24_i_acaoprog ";
     $sql .= "      left join prontcid      on prontcid.sd55_i_prontuario = prontuarios.sd24_i_codigo ";
     $sql .= "      left join sau_cid       on sau_cid.sd70_i_codigo      = prontcid.sd55_i_cid ";

     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd24_i_codigo)) {
         $sql2 .= " where prontuarios.sd24_i_codigo = $sd24_i_codigo ";
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
   public function sql_query_file ($sd24_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from prontuarios ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd24_i_codigo)){
         $sql2 .= " where prontuarios.sd24_i_codigo = $sd24_i_codigo ";
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
  * Gera o SQL para a busca das FAAs de um profissional (filtra por unidade tambem)
  */
  function sql_query_faas_profissional($sd24_i_codigo = null, $sd04_i_medico, $sd04_i_unidade, $campos = "*",
                                                                       $ordem = null, $dbwhere = "") {
    $sql = "select distinct ";
    if($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";

      }

    } else {
      $sql .= $campos;
    }
    $sql .= " from prontuarios ";
    $sql .= "     inner join unidades    on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
    $sql .= "     inner join db_depart   on  db_depart.coddepto = unidades.sd02_i_codigo";
    $sql .= "     left join cgs on cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
    $sql .= "     left join cgs_und on cgs_und.z01_i_cgsund = prontuarios.sd24_i_numcgs";
    $sql .= "     inner join unidademedicos on unidademedicos.sd04_i_unidade = prontuarios.sd24_i_unidade";
    $sql .= "     inner join medicos on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
    $sql2 = "      where sd04_i_medico = $sd04_i_medico and sd04_i_unidade = $sd04_i_unidade ";

    if($sd24_i_codigo != null && trim($sd24_i_codigo) != '') {
      $sql2 .=  "and prontuarios.sd24_i_codigo = $sd24_i_codigo";
    }
    if($dbwhere != "") {
      $sql2 .= " and $dbwhere";
    }

    //Não pode estar no prontanulado
    $sql2 .= " and not exists ( select * from prontanulado where prontanulado.sd57_i_prontuario= prontuarios.sd24_i_codigo ) ";

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
   // funcao do sql
function sql_query_prontuarios( $sd24_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
   $sql .= " from prontuarios ";
   $sql .= " inner join cgs_und on z01_i_cgsund=sd24_i_numcgs";
   $sql .= " inner join unidademedicos on unidademedicos.sd04_i_unidade = prontuarios.sd24_i_unidade ";
   $sql .= " inner join medicos on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico ";
   $sql2 = "";
   if($dbwhere==""){
      if($sd24_i_codigo!=null ){
         $sql2 .= " where prontuarios.sd24_i_codigo = $sd24_i_codigo ";
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
   function sql_query_cgs($sd24_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
   $sql .= " from prontuarios ";
   $sql .= " inner join cgs_und on z01_i_cgsund=sd24_i_numcgs";

   $sql2 = "";
   if($dbwhere==""){
      if($sd24_i_codigo!=null ){
         $sql2 .= " where prontuarios.sd24_i_codigo = $sd24_i_codigo ";
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
   function sql_query_faa($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";
      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= ' from prontuarios ';
    $sSql .= " inner join db_usuarios           on id_usuario         = prontuarios.sd24_i_login ";
    $sSql .= " left  join sau_motivoatendimento on s144_i_codigo      = prontuarios.sd24_i_motivo ";
    $sSql .= " inner join cgs_und               on z01_i_cgsund       = prontuarios.sd24_i_numcgs ";
    $sSql .= " inner join unidades              on sd02_i_codigo      = prontuarios.sd24_i_unidade ";
    $sSql .= " inner join cgm as cgm_und        on cgm_und.z01_numcgm = unidades.sd02_i_numcgm ";
    $sSql .= " inner join db_depart             on coddepto           = unidades.sd02_i_codigo ";
    $sSql .= " left  join prontagendamento      on s102_i_prontuario  = prontuarios.sd24_i_codigo ";
    $sSql .= " left  join agendamentos          on sd23_i_codigo      = prontagendamento.s102_i_agendamento ";
    $sSql .= " left  join undmedhorario         on sd30_i_codigo      = agendamentos.sd23_i_undmedhor ";
    $sSql .= " left  join especmedico           on sd27_i_codigo      = undmedhorario.sd30_i_undmed ";
    $sSql .= " left  join rhcbo                 on rh70_sequencial    = especmedico.sd27_i_rhcbo ";
    $sSql .= " left  join unidademedicos        on sd04_i_codigo      = especmedico.sd27_i_undmed ";
    $sSql .= " left  join medicos               on sd03_i_codigo      = unidademedicos.sd04_i_medico ";
    $sSql .= " left  join cgm as cgm_med        on cgm_med.z01_numcgm = medicos.sd03_i_cgm ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where prontuarios.sd24_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

  public function sql_query_atendimentos ($sd24_i_codigo = null, $sCampos = "*", $sOrdem = null, $sWhere = "") {

    $sSql  = " select $sCampos ";
    $sSql .= "   from prontuarios ";
    $sSql .= "  inner join unidades                      on unidades.sd02_i_codigo = prontuarios.sd24_i_unidade ";
    $sSql .= "  inner join setorambulatorial             on sd91_codigo            = sd24_setorambulatorial     ";
    $sSql .= "   left join cgs                           on cgs.z01_i_numcgs       = prontuarios.sd24_i_numcgs  ";
    $sSql .= "   left join cgs_und                       on cgs_und.z01_i_cgsund   = prontuarios.sd24_i_numcgs  ";
    $sSql .= "   left join prontuariosclassificacaorisco on sd101_prontuarios      = sd24_i_codigo              ";
    $sSql .= "   left join classificacaorisco            on sd78_codigo            = sd101_classificacaorisco   ";

    if( empty($sWhere) ) {
      if( !empty($sd24_i_codigo) ) {
        $sSql .= " where prontuarios.sd24_i_codigo = $sd24_i_codigo";
      }
    } else if( !empty($sWhere) ) {
      $sSql .= " where {$sWhere}";
    }

    if( !empty($sOrdem) ) {
      $sSql .= " order by {$sOrdem}";
    }
    return $sSql;
  }

  public function sql_query_requisicao_exames ($sd24_i_codigo = null, $sCampos = "*", $sOrdem = null, $sWhere = "") {

    $sSql  = " select $sCampos ";
    $sSql .= "   from prontuarios ";
    $sSql .= "  inner join requisicaoexameprontuario  on requisicaoexameprontuario.sd103_prontuarios          = prontuarios.sd24_i_codigo ";
    $sSql .= "  inner join medicos                    on medicos.sd03_i_codigo                                = requisicaoexameprontuario.sd103_medicos ";
    $sSql .= "  inner join examerequisicaoexame       on examerequisicaoexame.sd104_requisicaoexameprontuario = requisicaoexameprontuario.sd103_codigo ";
    $sSql .= "  inner join lab_exame                  on lab_exame.la08_i_codigo                              = examerequisicaoexame.sd104_lab_exame ";
    $sSql .= "  left  join cgm                        on cgm.z01_numcgm                                       = medicos.sd03_i_cgm ";
    $sSql .= "  inner join cgs_und                    on cgs_und.z01_i_cgsund                                 = prontuarios.sd24_i_numcgs ";

    if( empty($sWhere) ) {
      if( !empty($sd24_i_codigo) ) {
        $sSql .= " where prontuarios.sd24_i_codigo = $sd24_i_codigo";
      }
    } else if( !empty($sWhere) ) {
      $sSql .= " where {$sWhere}";
    }

    if( !empty($sOrdem) ) {
      $sSql .= " order by {$sOrdem}";
    }
    return $sSql;
  }
}