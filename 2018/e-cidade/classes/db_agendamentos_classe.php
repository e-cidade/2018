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
//CLASSE DA ENTIDADE agendamentos
class cl_agendamentos {
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
   var $sd23_i_codigo = 0;
   var $sd23_i_undmedhor = 0;
   var $sd23_i_usuario = 0;
   var $sd23_i_numcgs = 0;
   var $sd23_d_agendamento_dia = null;
   var $sd23_d_agendamento_mes = null;
   var $sd23_d_agendamento_ano = null;
   var $sd23_d_agendamento = null;
   var $sd23_d_consulta_dia = null;
   var $sd23_d_consulta_mes = null;
   var $sd23_d_consulta_ano = null;
   var $sd23_d_consulta = null;
   var $sd23_i_ficha = 0;
   var $sd23_c_hora = null;
   var $sd23_c_pessoa = null;
   var $sd23_i_situacao = 0;
   var $sd23_d_cadastro_dia = null;
   var $sd23_d_cadastro_mes = null;
   var $sd23_d_cadastro_ano = null;
   var $sd23_d_cadastro = null;
   var $sd23_c_cadastro = null;
   var $sd23_i_presenca = 0;
   var $sd23_t_obs = null;
   var $sd23_i_upssolicitante = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 sd23_i_codigo = int4 = Agenda Nº
                 sd23_i_undmedhor = int4 = Horário do Médico
                 sd23_i_usuario = int4 = Usuário
                 sd23_i_numcgs = int4 = CGS
                 sd23_d_agendamento = date = Data do Agendamento
                 sd23_d_consulta = date = Agenda
                 sd23_i_ficha = int4 = Ficha
                 sd23_c_hora = char(5) = Hora
                 sd23_c_pessoa = char(40) = Pessoa
                 sd23_i_situacao = int4 = Situação
                 sd23_d_cadastro = date = Data Cadastro
                 sd23_c_cadastro = varchar(20) = Hora Cadastro
                 sd23_i_presenca = int4 = Presença
                 sd23_t_obs = text = Observação
                 sd23_i_upssolicitante = int4 = Unidade
                 ";
   //funcao construtor da classe
   function cl_agendamentos() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("agendamentos");
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
       $this->sd23_i_codigo = ($this->sd23_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_i_codigo"]:$this->sd23_i_codigo);
       $this->sd23_i_undmedhor = ($this->sd23_i_undmedhor == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_i_undmedhor"]:$this->sd23_i_undmedhor);
       $this->sd23_i_usuario = ($this->sd23_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_i_usuario"]:$this->sd23_i_usuario);
       $this->sd23_i_numcgs = ($this->sd23_i_numcgs == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_i_numcgs"]:$this->sd23_i_numcgs);
       if($this->sd23_d_agendamento == ""){
         $this->sd23_d_agendamento_dia = ($this->sd23_d_agendamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_d_agendamento_dia"]:$this->sd23_d_agendamento_dia);
         $this->sd23_d_agendamento_mes = ($this->sd23_d_agendamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_d_agendamento_mes"]:$this->sd23_d_agendamento_mes);
         $this->sd23_d_agendamento_ano = ($this->sd23_d_agendamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_d_agendamento_ano"]:$this->sd23_d_agendamento_ano);
         if($this->sd23_d_agendamento_dia != ""){
            $this->sd23_d_agendamento = $this->sd23_d_agendamento_ano."-".$this->sd23_d_agendamento_mes."-".$this->sd23_d_agendamento_dia;
         }
       }
       if($this->sd23_d_consulta == ""){
         $this->sd23_d_consulta_dia = ($this->sd23_d_consulta_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_d_consulta_dia"]:$this->sd23_d_consulta_dia);
         $this->sd23_d_consulta_mes = ($this->sd23_d_consulta_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_d_consulta_mes"]:$this->sd23_d_consulta_mes);
         $this->sd23_d_consulta_ano = ($this->sd23_d_consulta_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_d_consulta_ano"]:$this->sd23_d_consulta_ano);
         if($this->sd23_d_consulta_dia != ""){
            $this->sd23_d_consulta = $this->sd23_d_consulta_ano."-".$this->sd23_d_consulta_mes."-".$this->sd23_d_consulta_dia;
         }
       }
       $this->sd23_i_ficha = ($this->sd23_i_ficha == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_i_ficha"]:$this->sd23_i_ficha);
       $this->sd23_c_hora = ($this->sd23_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_c_hora"]:$this->sd23_c_hora);
       $this->sd23_c_pessoa = ($this->sd23_c_pessoa == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_c_pessoa"]:$this->sd23_c_pessoa);
       $this->sd23_i_situacao = ($this->sd23_i_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_i_situacao"]:$this->sd23_i_situacao);
       if($this->sd23_d_cadastro == ""){
         $this->sd23_d_cadastro_dia = ($this->sd23_d_cadastro_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_d_cadastro_dia"]:$this->sd23_d_cadastro_dia);
         $this->sd23_d_cadastro_mes = ($this->sd23_d_cadastro_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_d_cadastro_mes"]:$this->sd23_d_cadastro_mes);
         $this->sd23_d_cadastro_ano = ($this->sd23_d_cadastro_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_d_cadastro_ano"]:$this->sd23_d_cadastro_ano);
         if($this->sd23_d_cadastro_dia != ""){
            $this->sd23_d_cadastro = $this->sd23_d_cadastro_ano."-".$this->sd23_d_cadastro_mes."-".$this->sd23_d_cadastro_dia;
         }
       }
       $this->sd23_c_cadastro = ($this->sd23_c_cadastro == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_c_cadastro"]:$this->sd23_c_cadastro);
       $this->sd23_i_presenca = ($this->sd23_i_presenca == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_i_presenca"]:$this->sd23_i_presenca);
       $this->sd23_t_obs = ($this->sd23_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_t_obs"]:$this->sd23_t_obs);
       $this->sd23_i_upssolicitante = ($this->sd23_i_upssolicitante == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_i_upssolicitante"]:$this->sd23_i_upssolicitante);
     }else{
       $this->sd23_i_codigo = ($this->sd23_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd23_i_codigo"]:$this->sd23_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd23_i_codigo){
      $this->atualizacampos();
     if($this->sd23_i_undmedhor == null ){
       $this->erro_sql = " Campo Horário do Médico nao Informado.";
       $this->erro_campo = "sd23_i_undmedhor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd23_i_usuario == null ){
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "sd23_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd23_i_numcgs == null ){
       $this->erro_sql = " Campo CGS nao Informado.";
       $this->erro_campo = "sd23_i_numcgs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd23_d_agendamento == null ){
       $this->erro_sql = " Campo Data do Agendamento nao Informado.";
       $this->erro_campo = "sd23_d_agendamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd23_d_consulta == null ){
       $this->erro_sql = " Campo Agenda nao Informado.";
       $this->erro_campo = "sd23_d_consulta_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd23_i_ficha == null ){
       $this->erro_sql = " Campo Ficha nao Informado.";
       $this->erro_campo = "sd23_i_ficha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd23_c_hora == null ){
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "sd23_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd23_i_situacao == null ){
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "sd23_i_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd23_d_cadastro == null ){
       $this->sd23_d_cadastro = "now()";
     }
     if($this->sd23_c_cadastro == null ){
       $this->sd23_c_cadastro = "'||current_time||'";
     }
     if($this->sd23_i_presenca == null ){
       $this->sd23_i_presenca = "0";
     }
     if($this->sd23_i_upssolicitante == null ){
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "sd23_i_upssolicitante";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd23_i_codigo == "" || $sd23_i_codigo == null ){
       $result = db_query("select nextval('agendamentos_sd23_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: agendamentos_sd23_codigo_seq do campo: sd23_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->sd23_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from agendamentos_sd23_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd23_i_codigo)){
         $this->erro_sql = " Campo sd23_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd23_i_codigo = $sd23_i_codigo;
       }
     }
     if(($this->sd23_i_codigo == null) || ($this->sd23_i_codigo == "") ){
       $this->erro_sql = " Campo sd23_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into agendamentos(
                                       sd23_i_codigo
                                      ,sd23_i_undmedhor
                                      ,sd23_i_usuario
                                      ,sd23_i_numcgs
                                      ,sd23_d_agendamento
                                      ,sd23_d_consulta
                                      ,sd23_i_ficha
                                      ,sd23_c_hora
                                      ,sd23_c_pessoa
                                      ,sd23_i_situacao
                                      ,sd23_d_cadastro
                                      ,sd23_c_cadastro
                                      ,sd23_i_presenca
                                      ,sd23_t_obs
                                      ,sd23_i_upssolicitante
                       )
                values (
                                $this->sd23_i_codigo
                               ,$this->sd23_i_undmedhor
                               ,$this->sd23_i_usuario
                               ,$this->sd23_i_numcgs
                               ,".($this->sd23_d_agendamento == "null" || $this->sd23_d_agendamento == ""?"null":"'".$this->sd23_d_agendamento."'")."
                               ,".($this->sd23_d_consulta == "null" || $this->sd23_d_consulta == ""?"null":"'".$this->sd23_d_consulta."'")."
                               ,$this->sd23_i_ficha
                               ,'$this->sd23_c_hora'
                               ,'$this->sd23_c_pessoa'
                               ,$this->sd23_i_situacao
                               ,".($this->sd23_d_cadastro == "null" || $this->sd23_d_cadastro == ""?"null":"'".$this->sd23_d_cadastro."'")."
                               ,'$this->sd23_c_cadastro'
                               ,$this->sd23_i_presenca
                               ,'$this->sd23_t_obs'
                               ,$this->sd23_i_upssolicitante
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Agendamentos ($this->sd23_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Agendamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Agendamentos ($this->sd23_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd23_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd23_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008743,'$this->sd23_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,100007,1008743,'','".AddSlashes(pg_result($resaco,0,'sd23_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100007,100159,'','".AddSlashes(pg_result($resaco,0,'sd23_i_undmedhor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100007,1008713,'','".AddSlashes(pg_result($resaco,0,'sd23_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100007,1008751,'','".AddSlashes(pg_result($resaco,0,'sd23_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100007,100164,'','".AddSlashes(pg_result($resaco,0,'sd23_d_agendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100007,100165,'','".AddSlashes(pg_result($resaco,0,'sd23_d_consulta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100007,100166,'','".AddSlashes(pg_result($resaco,0,'sd23_i_ficha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100007,100169,'','".AddSlashes(pg_result($resaco,0,'sd23_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100007,100171,'','".AddSlashes(pg_result($resaco,0,'sd23_c_pessoa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100007,100167,'','".AddSlashes(pg_result($resaco,0,'sd23_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100007,1008940,'','".AddSlashes(pg_result($resaco,0,'sd23_d_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100007,1008941,'','".AddSlashes(pg_result($resaco,0,'sd23_c_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100007,17502,'','".AddSlashes(pg_result($resaco,0,'sd23_i_presenca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100007,17504,'','".AddSlashes(pg_result($resaco,0,'sd23_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100007,18030,'','".AddSlashes(pg_result($resaco,0,'sd23_i_upssolicitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($sd23_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update agendamentos set ";
     $virgula = "";
     if(trim($this->sd23_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_codigo"])){
       $sql  .= $virgula." sd23_i_codigo = $this->sd23_i_codigo ";
       $virgula = ",";
       if(trim($this->sd23_i_codigo) == null ){
         $this->erro_sql = " Campo Agenda Nº nao Informado.";
         $this->erro_campo = "sd23_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd23_i_undmedhor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_undmedhor"])){
       $sql  .= $virgula." sd23_i_undmedhor = $this->sd23_i_undmedhor ";
       $virgula = ",";
       if(trim($this->sd23_i_undmedhor) == null ){
         $this->erro_sql = " Campo Horário do Médico nao Informado.";
         $this->erro_campo = "sd23_i_undmedhor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd23_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_usuario"])){
       $sql  .= $virgula." sd23_i_usuario = $this->sd23_i_usuario ";
       $virgula = ",";
       if(trim($this->sd23_i_usuario) == null ){
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "sd23_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd23_i_numcgs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_numcgs"])){
       $sql  .= $virgula." sd23_i_numcgs = $this->sd23_i_numcgs ";
       $virgula = ",";
       if(trim($this->sd23_i_numcgs) == null ){
         $this->erro_sql = " Campo CGS nao Informado.";
         $this->erro_campo = "sd23_i_numcgs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd23_d_agendamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd23_d_agendamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd23_d_agendamento_dia"] !="") ){
       $sql  .= $virgula." sd23_d_agendamento = '$this->sd23_d_agendamento' ";
       $virgula = ",";
       if(trim($this->sd23_d_agendamento) == null ){
         $this->erro_sql = " Campo Data do Agendamento nao Informado.";
         $this->erro_campo = "sd23_d_agendamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_d_agendamento_dia"])){
         $sql  .= $virgula." sd23_d_agendamento = null ";
         $virgula = ",";
         if(trim($this->sd23_d_agendamento) == null ){
           $this->erro_sql = " Campo Data do Agendamento nao Informado.";
           $this->erro_campo = "sd23_d_agendamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd23_d_consulta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd23_d_consulta_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd23_d_consulta_dia"] !="") ){
       $sql  .= $virgula." sd23_d_consulta = '$this->sd23_d_consulta' ";
       $virgula = ",";
       if(trim($this->sd23_d_consulta) == null ){
         $this->erro_sql = " Campo Agenda nao Informado.";
         $this->erro_campo = "sd23_d_consulta_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_d_consulta_dia"])){
         $sql  .= $virgula." sd23_d_consulta = null ";
         $virgula = ",";
         if(trim($this->sd23_d_consulta) == null ){
           $this->erro_sql = " Campo Agenda nao Informado.";
           $this->erro_campo = "sd23_d_consulta_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd23_i_ficha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_ficha"])){
       $sql  .= $virgula." sd23_i_ficha = $this->sd23_i_ficha ";
       $virgula = ",";
       if(trim($this->sd23_i_ficha) == null ){
         $this->erro_sql = " Campo Ficha nao Informado.";
         $this->erro_campo = "sd23_i_ficha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd23_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd23_c_hora"])){
       $sql  .= $virgula." sd23_c_hora = '$this->sd23_c_hora' ";
       $virgula = ",";
       if(trim($this->sd23_c_hora) == null ){
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "sd23_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd23_c_pessoa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd23_c_pessoa"])){
       $sql  .= $virgula." sd23_c_pessoa = '$this->sd23_c_pessoa' ";
       $virgula = ",";
     }
     if(trim($this->sd23_i_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_situacao"])){
       $sql  .= $virgula." sd23_i_situacao = $this->sd23_i_situacao ";
       $virgula = ",";
       if(trim($this->sd23_i_situacao) == null ){
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "sd23_i_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd23_d_cadastro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd23_d_cadastro_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd23_d_cadastro_dia"] !="") ){
       $sql  .= $virgula." sd23_d_cadastro = '$this->sd23_d_cadastro' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_d_cadastro_dia"])){
         $sql  .= $virgula." sd23_d_cadastro = null ";
         $virgula = ",";
       }
     }
     if(trim($this->sd23_c_cadastro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd23_c_cadastro"])){
       $sql  .= $virgula." sd23_c_cadastro = '$this->sd23_c_cadastro' ";
       $virgula = ",";
     }
     if(trim($this->sd23_i_presenca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_presenca"])){
        if(trim($this->sd23_i_presenca)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_presenca"])){
           $this->sd23_i_presenca = "0" ;
        }
       $sql  .= $virgula." sd23_i_presenca = $this->sd23_i_presenca ";
       $virgula = ",";
     }
     if(trim($this->sd23_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd23_t_obs"])){
       $sql  .= $virgula." sd23_t_obs = '$this->sd23_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->sd23_i_upssolicitante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_upssolicitante"])){
       $sql  .= $virgula." sd23_i_upssolicitante = $this->sd23_i_upssolicitante ";
       $virgula = ",";
       if(trim($this->sd23_i_upssolicitante) == null ){
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "sd23_i_upssolicitante";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd23_i_codigo!=null){
       $sql .= " sd23_i_codigo = $this->sd23_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd23_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008743,'$this->sd23_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_codigo"]) || $this->sd23_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,100007,1008743,'".AddSlashes(pg_result($resaco,$conresaco,'sd23_i_codigo'))."','$this->sd23_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_undmedhor"]) || $this->sd23_i_undmedhor != "")
           $resac = db_query("insert into db_acount values($acount,100007,100159,'".AddSlashes(pg_result($resaco,$conresaco,'sd23_i_undmedhor'))."','$this->sd23_i_undmedhor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_usuario"]) || $this->sd23_i_usuario != "")
           $resac = db_query("insert into db_acount values($acount,100007,1008713,'".AddSlashes(pg_result($resaco,$conresaco,'sd23_i_usuario'))."','$this->sd23_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_numcgs"]) || $this->sd23_i_numcgs != "")
           $resac = db_query("insert into db_acount values($acount,100007,1008751,'".AddSlashes(pg_result($resaco,$conresaco,'sd23_i_numcgs'))."','$this->sd23_i_numcgs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_d_agendamento"]) || $this->sd23_d_agendamento != "")
           $resac = db_query("insert into db_acount values($acount,100007,100164,'".AddSlashes(pg_result($resaco,$conresaco,'sd23_d_agendamento'))."','$this->sd23_d_agendamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_d_consulta"]) || $this->sd23_d_consulta != "")
           $resac = db_query("insert into db_acount values($acount,100007,100165,'".AddSlashes(pg_result($resaco,$conresaco,'sd23_d_consulta'))."','$this->sd23_d_consulta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_ficha"]) || $this->sd23_i_ficha != "")
           $resac = db_query("insert into db_acount values($acount,100007,100166,'".AddSlashes(pg_result($resaco,$conresaco,'sd23_i_ficha'))."','$this->sd23_i_ficha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_c_hora"]) || $this->sd23_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,100007,100169,'".AddSlashes(pg_result($resaco,$conresaco,'sd23_c_hora'))."','$this->sd23_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_c_pessoa"]) || $this->sd23_c_pessoa != "")
           $resac = db_query("insert into db_acount values($acount,100007,100171,'".AddSlashes(pg_result($resaco,$conresaco,'sd23_c_pessoa'))."','$this->sd23_c_pessoa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_situacao"]) || $this->sd23_i_situacao != "")
           $resac = db_query("insert into db_acount values($acount,100007,100167,'".AddSlashes(pg_result($resaco,$conresaco,'sd23_i_situacao'))."','$this->sd23_i_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_d_cadastro"]) || $this->sd23_d_cadastro != "")
           $resac = db_query("insert into db_acount values($acount,100007,1008940,'".AddSlashes(pg_result($resaco,$conresaco,'sd23_d_cadastro'))."','$this->sd23_d_cadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_c_cadastro"]) || $this->sd23_c_cadastro != "")
           $resac = db_query("insert into db_acount values($acount,100007,1008941,'".AddSlashes(pg_result($resaco,$conresaco,'sd23_c_cadastro'))."','$this->sd23_c_cadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_presenca"]) || $this->sd23_i_presenca != "")
           $resac = db_query("insert into db_acount values($acount,100007,17502,'".AddSlashes(pg_result($resaco,$conresaco,'sd23_i_presenca'))."','$this->sd23_i_presenca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_t_obs"]) || $this->sd23_t_obs != "")
           $resac = db_query("insert into db_acount values($acount,100007,17504,'".AddSlashes(pg_result($resaco,$conresaco,'sd23_t_obs'))."','$this->sd23_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd23_i_upssolicitante"]) || $this->sd23_i_upssolicitante != "")
           $resac = db_query("insert into db_acount values($acount,100007,18030,'".AddSlashes(pg_result($resaco,$conresaco,'sd23_i_upssolicitante'))."','$this->sd23_i_upssolicitante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agendamentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd23_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agendamentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($sd23_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd23_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008743,'$sd23_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,100007,1008743,'','".AddSlashes(pg_result($resaco,$iresaco,'sd23_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100007,100159,'','".AddSlashes(pg_result($resaco,$iresaco,'sd23_i_undmedhor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100007,1008713,'','".AddSlashes(pg_result($resaco,$iresaco,'sd23_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100007,1008751,'','".AddSlashes(pg_result($resaco,$iresaco,'sd23_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100007,100164,'','".AddSlashes(pg_result($resaco,$iresaco,'sd23_d_agendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100007,100165,'','".AddSlashes(pg_result($resaco,$iresaco,'sd23_d_consulta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100007,100166,'','".AddSlashes(pg_result($resaco,$iresaco,'sd23_i_ficha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100007,100169,'','".AddSlashes(pg_result($resaco,$iresaco,'sd23_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100007,100171,'','".AddSlashes(pg_result($resaco,$iresaco,'sd23_c_pessoa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100007,100167,'','".AddSlashes(pg_result($resaco,$iresaco,'sd23_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100007,1008940,'','".AddSlashes(pg_result($resaco,$iresaco,'sd23_d_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100007,1008941,'','".AddSlashes(pg_result($resaco,$iresaco,'sd23_c_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100007,17502,'','".AddSlashes(pg_result($resaco,$iresaco,'sd23_i_presenca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100007,17504,'','".AddSlashes(pg_result($resaco,$iresaco,'sd23_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100007,18030,'','".AddSlashes(pg_result($resaco,$iresaco,'sd23_i_upssolicitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from agendamentos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd23_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd23_i_codigo = $sd23_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agendamentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd23_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agendamentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:agendamentos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $sd23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from agendamentos ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = agendamentos.sd23_i_usuario";
     $sql .= "      inner join undmedhorario  on  undmedhorario.sd30_i_codigo = agendamentos.sd23_i_undmedhor";
     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = agendamentos.sd23_i_numcgs";
     $sql .= "      inner join sau_tipoficha  on  sau_tipoficha.sd101_i_codigo = undmedhorario.sd30_i_tipoficha";
     $sql .= "      inner join especmedico  on  especmedico.sd27_i_codigo = undmedhorario.sd30_i_undmed";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = undmedhorario.sd30_i_diasemana";
     $sql2 = "";
     if($dbwhere==""){
       if($sd23_i_codigo!=null ){
         $sql2 .= " where agendamentos.sd23_i_codigo = $sd23_i_codigo ";
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
   // funcao do sql
   function sql_query_file ( $sd23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from agendamentos ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd23_i_codigo!=null ){
         $sql2 .= " where agendamentos.sd23_i_codigo = $sd23_i_codigo ";
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
   /*
Query utilizada na consulta geral da saúde
*/
function sql_query_consulta_geral ( $sd23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from agendamentos ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = agendamentos.sd23_i_usuario";
     $sql .= "      inner join undmedhorario  on  undmedhorario.sd30_i_codigo = agendamentos.sd23_i_undmedhor";
     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = agendamentos.sd23_i_numcgs";
     $sql .= "      inner join sau_tipoficha  on  sau_tipoficha.sd101_i_codigo = undmedhorario.sd30_i_tipoficha";
     $sql .= "      inner join especmedico  on  especmedico.sd27_i_codigo = undmedhorario.sd30_i_undmed";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = undmedhorario.sd30_i_diasemana";
     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      inner join cgm  on cgm.z01_numcgm =  medicos.sd03_i_cgm";
     $sql2 = "";
     if($dbwhere==""){
       if($sd23_i_codigo!=null ){
         $sql2 .= " where agendamentos.sd23_i_codigo = $sd23_i_codigo ";
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
   function sql_query_marcados( $sd23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
   $sql .= " from agendamentos ";
   $sql .= " inner join undmedhorario on undmedhorario.sd30_i_codigo = agendamentos.sd23_i_undmedhor ";
   $sql .= " inner join sau_tipoficha on sau_tipoficha.sd101_i_codigo = undmedhorario.sd30_i_tipoficha ";
   $sql .= " inner join especmedico on especmedico.sd27_i_codigo = undmedhorario.sd30_i_undmed ";
   $sql .= " inner join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
   $sql .= " left join  agendaconsultaanula on agendaconsultaanula.s114_i_agendaconsulta = agendamentos.sd23_i_codigo ";
   $sql2 = "";
   if($dbwhere==""){
      if($sd23_i_codigo!=null ){
         $sql2 .= " where agendamentos.sd23_i_codigo = $sd23_i_codigo ";
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
  /*
  * @descr verifica se o agendamento possui prontuario. Se possuir, exclui o prontuario e suas ligacoes,
  * seta o atributo sd23_i_codigo com o codigo do prontuario e retorna true, senao, retorna false.
  * Seta o atributo erro_status para '0' se ocorrer algum erro
  * @param int $iAgendamento codigo do agendamento que se deseja excluir o prontuario se existir
  * @return boolean indica se o agendamento indicado possuia ou nao um prontuario
  */

  function excluir_prontuario_agendamento($iAgendamento) {

    $oDaoprontproced      = new cl_prontproced_ext();
    $oDaoprontprocedcid   = new cl_prontprocedcid();
    $oDaoprontagendamento = new cl_prontagendamento();
    $oDaoprontprofatend   = new cl_prontprofatend();
    $oDaoprontuarios      = new cl_prontuarios();
    $oDaoFechaPront       = new cl_sau_fechapront();

    $sSql = cl_prontagendamento::sql_query_ext(null, "*", null, "s102_i_agendamento = $iAgendamento and sd57_i_prontuario is null limit 1");
  	$rsProntagendamento = db_query($sSql);

  	if(pg_num_rows($rsProntagendamento) > 0) {

	    $oDadosProntagendamento = db_utils::fieldsmemory($rsProntagendamento, 0);
      $this->sd23_i_codigo    = $oDadosProntagendamento->s102_i_prontuario;

	    $rsProntproced = $oDaoprontproced->sql_record(
                                                    $oDaoprontproced->sql_query_ext(null, "*", null,
                                                    "sd29_i_prontuario = $oDadosProntagendamento->s102_i_prontuario")
                                                   );

	    $oDaoprontagendamento->excluir($oDadosProntagendamento->s102_i_codigo);
	    if($oDaoprontagendamento->erro_status == "0" && $oDaoprontagendamento->numrows_excluir == 0) {
        $this->erro_status = "0";
	      $this->erro_msg    = $oDaoprontagendamento->erro_msg;
  	  }

	    $oDaoprontprofatend->excluir(null, "s104_i_prontuario = $oDadosProntagendamento->s102_i_prontuario");
	    if($oDaoprontprofatend->erro_status == "0" && $oDaoprontprofatend->numrows_excluir == 0) {
	      $this->erro_status = "0";
	      $this->erro_msg    = $oDaoprontprofatend->erro_msg;
	    }

      /* Exclusao de todos os registros da protprocedcid */
      for($iCont = 0; $iCont < $oDaoprontproced->numrows; $iCont++) {

        $oDadosProntproced = db_utils::fieldsmemory($rsProntproced, $iCont);

        $oDaoFechaPront->excluir( null, "sd98_i_prontproced = {$oDadosProntproced->sd29_i_codigo}" );
        if( $oDaoFechaPront->erro_status == "0" && $oDaoFechaPront->numrows_excluir == 0 ) {

          $this->erro_status = "0";
          $this->erro_msg    = $oDaoFechaPront->erro_msg;
        }

        $oDaoprontprocedcid->excluir(null, "s135_i_prontproced = $oDadosProntproced->sd29_i_codigo");
        if($oDaoprontprocedcid->erro_status == "0" && $oDaoprontprocedcid->numrows_excluir == 0) {
	        $this->erro_status = "0";
	        $this->erro_msg    = $oDaoprontprocedcid->erro_msg;
	  	  }
      }

  	  if($oDaoprontproced->numrows > 0) {
	      $oDaoprontproced->excluir(null, "sd29_i_prontuario = $oDadosProntagendamento->s102_i_prontuario");
        if($oDaoprontproced->erro_status == "0" && $oDaoprontproced->numrows_excluir == 0) {
	        $this->erro_status = "0";
	        $this->erro_msg    = $oDaoprontproced->erro_msg;
	  	  }
	    }

      $oDaoprontuarios->excluir($oDadosProntagendamento->s102_i_prontuario);
  	  if($oDaoprontuarios->erro_status == "0" && $oDaoprontuarios->numrows_excluir == 0) {
         $this->erro_status = "0";
         $this->erro_msg    = $oDaoprontuarios->erro_msg;
  	  }
      return true;

    }

    return false;
  }

/*Cria/estrutura o sql para a busca no banco de dados a partir dos parâmetros recebidos
*@param {String} $sd23_i_codigo código sequenciado do agendamento;
*@param {String} $sCampos String de campos, recebe o valor padrão "*";
*@param {String} $sDbwhere string que receberá no codigo a condição para o sql;
*@return String,  retorna o comando sql estruturado com todas as informações necessária para realizar a consulta;
*/
function sql_query_prontuarios ($sd23_i_codigo = null, $sCampos = "*", $sOrdem = null, $sDbwhere = "") {

  $sSql = "select ";
  if($sCampos != "*" ) {

    $sCampos_sql = split("#", $sCampos);
    $sVirgula = "";

    for ($iCont = 0; $iCont < sizeof ($sCampos_sql); $iCont++){

      $sSql .= $sVirgula.$sCampos_sql[$iCont];
      $sVirgula = ",";

    }

  }else{
    $sSql .= $sCampos;
  }

   $sSql .= "  from agendamentos ";
   $sSql .= " inner join db_usuarios         on db_usuarios.id_usuario = agendamentos.sd23_i_usuario ";
   $sSql .= " inner join undmedhorario       on undmedhorario.sd30_i_codigo = agendamentos.sd23_i_undmedhor ";
   $sSql .= " inner join cgs                 on cgs.z01_i_numcgs = agendamentos.sd23_i_numcgs";
   $sSql .= " inner join cgs_und             on cgs_und.z01_i_cgsund = cgs.z01_i_numcgs";
   $sSql .= " inner join sau_tipoficha       on sau_tipoficha.sd101_i_codigo = undmedhorario.sd30_i_tipoficha ";
   $sSql .= " inner join especmedico         on especmedico.sd27_i_codigo = undmedhorario.sd30_i_undmed ";
   $sSql .= "  left join agendaconsultaanula on agendaconsultaanula.s114_i_agendaconsulta = agendamentos.sd23_i_codigo ";
   $sSql .= "  left join prontagendamento    on prontagendamento.s102_i_agendamento = agendamentos.sd23_i_codigo ";
   $sSql .= "  left join prontuarios         on prontuarios.sd24_i_codigo = prontagendamento.s102_i_prontuario";
   $sSql2 = "";

   if($sDbwhere == "") {

      if($sd23_i_codigo != null ) {
        $sSql2 .= " where agendamentos.sd23_i_codigo = $sd23_i_codigo ";
      }

   }else if($sDbwhere != "") {
     $sSql2 = " where $sDbwhere";
   }

   $sSql .= $sSql2;

   if($sOrdem != null ) {

     $sSql .= "order by";
     $sCampos_sql = split("#", $sOrdem);
     $sVirgula = "";

     for($iCont = 0; $iCont < sizeof($sCampos_sql); $iCont++) {

       $sSql .= $sVirgula.$sCampos_sql[$iCont];
       $sVirgula = ",";

     }

   }

  return $sSql;
}



function sql_query_comprovante($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= ' from agendamentos ';
    $sSql .= " inner join db_usuarios    on id_usuario         = agendamentos.sd23_i_usuario ";
    $sSql .= " inner join cgs_und        on z01_i_cgsund       = agendamentos.sd23_i_numcgs ";
    $sSql .= " inner join undmedhorario  on sd30_i_codigo      = agendamentos.sd23_i_undmedhor ";
    $sSql .= " inner join diasemana      on ed32_i_codigo      = undmedhorario.sd30_i_diasemana ";
    $sSql .= " inner join especmedico    on sd27_i_codigo      = undmedhorario.sd30_i_undmed ";
    $sSql .= " inner join rhcbo          on rh70_sequencial    = especmedico.sd27_i_rhcbo ";
    $sSql .= " inner join unidademedicos on sd04_i_codigo      = especmedico.sd27_i_undmed ";
    $sSql .= " inner join unidades       on sd02_i_codigo      = unidademedicos.sd04_i_unidade ";
    $sSql .= " inner join cgm as cgm_und on cgm_und.z01_numcgm = unidades.sd02_i_numcgm ";
    $sSql .= " inner join db_depart      on coddepto           = unidades.sd02_i_codigo ";
    $sSql .= " inner join medicos        on sd03_i_codigo      = unidademedicos.sd04_i_medico ";
    $sSql .= " inner join cgm as cgm_med on cgm_med.z01_numcgm = medicos.sd03_i_cgm ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where agendamentos.sd23_i_codigo = $iCodigo ";
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

  function sql_query_situacao ($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= ' from agendamentos';
    $sSql .= '   inner join undmedhorario on undmedhorario.sd30_i_codigo = agendamentos.sd23_i_undmedhor ';
    $sSql .= '   inner join especmedico on especmedico.sd27_i_codigo = undmedhorario.sd30_i_undmed ';
    $sSql .= '   inner join rhcbo on rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo ';
    $sSql .= '   inner join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed ';
    $sSql .= '   inner join medicos on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico ';
    $sSql .= '   left join agendaconsultaanula on s114_i_agendaconsulta = sd23_i_codigo ';
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where agendamentos.sd23_i_codigo = $iCodigo ";
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

  public function sql_query_agendamento_sms( $sd23_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

     $sql  = " select {$campos} ";
     $sql .= "   from agendamentos ";
     $sql .= "   join undmedhorario            on undmedhorario.sd30_i_codigo               = agendamentos.sd23_i_undmedhor";
     $sql .= "   join cgs_und                  on cgs_und.z01_i_cgsund                      = agendamentos.sd23_i_numcgs";
     $sql .= "   join sau_tipoficha            on sau_tipoficha.sd101_i_codigo              = undmedhorario.sd30_i_tipoficha";
     $sql .= "   join especmedico              on especmedico.sd27_i_codigo                 = undmedhorario.sd30_i_undmed";
     $sql .= "   join rhcbo                    on rhcbo.rh70_sequencial                     = especmedico.sd27_i_rhcbo";
     $sql .= "   join unidademedicos           on unidademedicos.sd04_i_codigo              = especmedico.sd27_i_undmed";
     $sql .= "   join medicos                  on medicos.sd03_i_codigo                     = unidademedicos.sd04_i_medico";
     $sql .= "   join db_depart                on db_depart.coddepto                        = unidademedicos.sd04_i_unidade";
     $sql .= "   join cgm                      on cgm.z01_numcgm                            = medicos.sd03_i_cgm";
     $sql .= "   left join agendaconsultaanula on agendaconsultaanula.s114_i_agendaconsulta = agendamentos.sd23_i_codigo";

     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd23_i_codigo)){
         $sql2 .= " where agendamentos.sd23_i_codigo = $sd23_i_codigo ";
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
}
?>