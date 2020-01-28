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
//CLASSE DA ENTIDADE undmedhorario
class cl_undmedhorario {
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
   var $sd30_i_codigo = 0;
   var $sd30_i_undmed = 0;
   var $sd30_i_diasemana = 0;
   var $sd30_c_horaini = null;
   var $sd30_c_horafim = null;
   var $sd30_i_fichas = 0;
   var $sd30_i_reservas = 0;
   var $sd30_i_turno = 0;
   var $sd30_c_tipograde = null;
   var $sd30_i_tipoficha = 0;
   var $sd30_d_valinicial_dia = null;
   var $sd30_d_valinicial_mes = null;
   var $sd30_d_valinicial_ano = null;
   var $sd30_d_valinicial = null;
   var $sd30_d_valfinal_dia = null;
   var $sd30_d_valfinal_mes = null;
   var $sd30_d_valfinal_ano = null;
   var $sd30_d_valfinal = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 sd30_i_codigo = int4 = Código
                 sd30_i_undmed = int4 = Vínculo
                 sd30_i_diasemana = int4 = Dia da Semana
                 sd30_c_horaini = char(5) = Hora Início
                 sd30_c_horafim = char(5) = Hora Fim
                 sd30_i_fichas = int4 = Fichas
                 sd30_i_reservas = int4 = Reservas
                 sd30_i_turno = int4 = Turno
                 sd30_c_tipograde = char(1) = Tipo Grade
                 sd30_i_tipoficha = int4 = Tipo Ficha
                 sd30_d_valinicial = date = Início
                 sd30_d_valfinal = date = Fim
                 ";
   //funcao construtor da classe
   function cl_undmedhorario() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("undmedhorario");
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
       $this->sd30_i_codigo = ($this->sd30_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_i_codigo"]:$this->sd30_i_codigo);
       $this->sd30_i_undmed = ($this->sd30_i_undmed == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_i_undmed"]:$this->sd30_i_undmed);
       $this->sd30_i_diasemana = ($this->sd30_i_diasemana == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_i_diasemana"]:$this->sd30_i_diasemana);
       $this->sd30_c_horaini = ($this->sd30_c_horaini == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_c_horaini"]:$this->sd30_c_horaini);
       $this->sd30_c_horafim = ($this->sd30_c_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_c_horafim"]:$this->sd30_c_horafim);
       $this->sd30_i_fichas = ($this->sd30_i_fichas == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_i_fichas"]:$this->sd30_i_fichas);
       $this->sd30_i_reservas = ($this->sd30_i_reservas == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_i_reservas"]:$this->sd30_i_reservas);
       $this->sd30_i_turno = ($this->sd30_i_turno == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_i_turno"]:$this->sd30_i_turno);
       $this->sd30_c_tipograde = ($this->sd30_c_tipograde == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_c_tipograde"]:$this->sd30_c_tipograde);
       $this->sd30_i_tipoficha = ($this->sd30_i_tipoficha == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_i_tipoficha"]:$this->sd30_i_tipoficha);
       if($this->sd30_d_valinicial == ""){
         $this->sd30_d_valinicial_dia = ($this->sd30_d_valinicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_d_valinicial_dia"]:$this->sd30_d_valinicial_dia);
         $this->sd30_d_valinicial_mes = ($this->sd30_d_valinicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_d_valinicial_mes"]:$this->sd30_d_valinicial_mes);
         $this->sd30_d_valinicial_ano = ($this->sd30_d_valinicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_d_valinicial_ano"]:$this->sd30_d_valinicial_ano);
         if($this->sd30_d_valinicial_dia != ""){
            $this->sd30_d_valinicial = $this->sd30_d_valinicial_ano."-".$this->sd30_d_valinicial_mes."-".$this->sd30_d_valinicial_dia;
         }
       }
       if($this->sd30_d_valfinal == ""){
         $this->sd30_d_valfinal_dia = ($this->sd30_d_valfinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_d_valfinal_dia"]:$this->sd30_d_valfinal_dia);
         $this->sd30_d_valfinal_mes = ($this->sd30_d_valfinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_d_valfinal_mes"]:$this->sd30_d_valfinal_mes);
         $this->sd30_d_valfinal_ano = ($this->sd30_d_valfinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_d_valfinal_ano"]:$this->sd30_d_valfinal_ano);
         if($this->sd30_d_valfinal_dia != ""){
            $this->sd30_d_valfinal = $this->sd30_d_valfinal_ano."-".$this->sd30_d_valfinal_mes."-".$this->sd30_d_valfinal_dia;
         }
       }
     }else{
       $this->sd30_i_codigo = ($this->sd30_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd30_i_codigo"]:$this->sd30_i_codigo);
     }
   }
   // funcao para Inclusao
   function incluir ($sd30_i_codigo){
      $this->atualizacampos();
     if($this->sd30_i_undmed == null ){
       $this->erro_sql = " Campo Vínculo não informado.";
       $this->erro_campo = "sd30_i_undmed";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd30_i_diasemana == null ){
       $this->erro_sql = " Campo Dia da Semana não informado.";
       $this->erro_campo = "sd30_i_diasemana";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd30_c_horaini == null ){
       $this->erro_sql = " Campo Hora Início não informado.";
       $this->erro_campo = "sd30_c_horaini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd30_c_horafim == null ){
       $this->erro_sql = " Campo Hora Fim não informado.";
       $this->erro_campo = "sd30_c_horafim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd30_i_fichas == null ){
       $this->erro_sql = " Campo Fichas não informado.";
       $this->erro_campo = "sd30_i_fichas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd30_i_reservas == null ){
       $this->erro_sql = " Campo Reservas não informado.";
       $this->erro_campo = "sd30_i_reservas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd30_i_turno == null ){
       $this->erro_sql = " Campo Turno não informado.";
       $this->erro_campo = "sd30_i_turno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd30_c_tipograde == null ){
       $this->erro_sql = " Campo Tipo Grade não informado.";
       $this->erro_campo = "sd30_c_tipograde";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd30_i_tipoficha == null ){
       $this->erro_sql = " Campo Tipo Ficha não informado.";
       $this->erro_campo = "sd30_i_tipoficha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd30_d_valinicial == null ){
       $this->sd30_d_valinicial = "null";
     }
     if($this->sd30_d_valfinal == null ){
       $this->sd30_d_valfinal = "null";
     }
     if($sd30_i_codigo == "" || $sd30_i_codigo == null ){
       $result = db_query("select nextval('undmedhorario_sd30_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: undmedhorario_sd30_codigo_seq do campo: sd30_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->sd30_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from undmedhorario_sd30_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd30_i_codigo)){
         $this->erro_sql = " Campo sd30_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd30_i_codigo = $sd30_i_codigo;
       }
     }
     if(($this->sd30_i_codigo == null) || ($this->sd30_i_codigo == "") ){
       $this->erro_sql = " Campo sd30_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into undmedhorario(
                                       sd30_i_codigo
                                      ,sd30_i_undmed
                                      ,sd30_i_diasemana
                                      ,sd30_c_horaini
                                      ,sd30_c_horafim
                                      ,sd30_i_fichas
                                      ,sd30_i_reservas
                                      ,sd30_i_turno
                                      ,sd30_c_tipograde
                                      ,sd30_i_tipoficha
                                      ,sd30_d_valinicial
                                      ,sd30_d_valfinal
                       )
                values (
                                $this->sd30_i_codigo
                               ,$this->sd30_i_undmed
                               ,$this->sd30_i_diasemana
                               ,'$this->sd30_c_horaini'
                               ,'$this->sd30_c_horafim'
                               ,$this->sd30_i_fichas
                               ,$this->sd30_i_reservas
                               ,$this->sd30_i_turno
                               ,'$this->sd30_c_tipograde'
                               ,$this->sd30_i_tipoficha
                               ,".($this->sd30_d_valinicial == "null" || $this->sd30_d_valinicial == ""?"null":"'".$this->sd30_d_valinicial."'")."
                               ,".($this->sd30_d_valfinal == "null" || $this->sd30_d_valfinal == ""?"null":"'".$this->sd30_d_valfinal."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Unidade/Médico/Horário ($this->sd30_i_codigo) nao Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Unidade/Médico/Horário já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Unidade/Médico/Horário ($this->sd30_i_codigo) nao Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd30_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd30_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008811,'$this->sd30_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010138,1008811,'','".AddSlashes(pg_result($resaco,0,'sd30_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010138,1008812,'','".AddSlashes(pg_result($resaco,0,'sd30_i_undmed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010138,1008706,'','".AddSlashes(pg_result($resaco,0,'sd30_i_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010138,1008707,'','".AddSlashes(pg_result($resaco,0,'sd30_c_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010138,1008708,'','".AddSlashes(pg_result($resaco,0,'sd30_c_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010138,1008709,'','".AddSlashes(pg_result($resaco,0,'sd30_i_fichas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010138,1008710,'','".AddSlashes(pg_result($resaco,0,'sd30_i_reservas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010138,1008865,'','".AddSlashes(pg_result($resaco,0,'sd30_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010138,12508,'','".AddSlashes(pg_result($resaco,0,'sd30_c_tipograde'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010138,12509,'','".AddSlashes(pg_result($resaco,0,'sd30_i_tipoficha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010138,12510,'','".AddSlashes(pg_result($resaco,0,'sd30_d_valinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010138,12511,'','".AddSlashes(pg_result($resaco,0,'sd30_d_valfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($sd30_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update undmedhorario set ";
     $virgula = "";
     if(trim($this->sd30_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd30_i_codigo"])){
       $sql  .= $virgula." sd30_i_codigo = $this->sd30_i_codigo ";
       $virgula = ",";
       if(trim($this->sd30_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "sd30_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd30_i_undmed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd30_i_undmed"])){
       $sql  .= $virgula." sd30_i_undmed = $this->sd30_i_undmed ";
       $virgula = ",";
       if(trim($this->sd30_i_undmed) == null ){
         $this->erro_sql = " Campo Vínculo não informado.";
         $this->erro_campo = "sd30_i_undmed";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd30_i_diasemana)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd30_i_diasemana"])){
       $sql  .= $virgula." sd30_i_diasemana = $this->sd30_i_diasemana ";
       $virgula = ",";
       if(trim($this->sd30_i_diasemana) == null ){
         $this->erro_sql = " Campo Dia da Semana não informado.";
         $this->erro_campo = "sd30_i_diasemana";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd30_c_horaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd30_c_horaini"])){
       $sql  .= $virgula." sd30_c_horaini = '$this->sd30_c_horaini' ";
       $virgula = ",";
       if(trim($this->sd30_c_horaini) == null ){
         $this->erro_sql = " Campo Hora Início não informado.";
         $this->erro_campo = "sd30_c_horaini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd30_c_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd30_c_horafim"])){
       $sql  .= $virgula." sd30_c_horafim = '$this->sd30_c_horafim' ";
       $virgula = ",";
       if(trim($this->sd30_c_horafim) == null ){
         $this->erro_sql = " Campo Hora Fim não informado.";
         $this->erro_campo = "sd30_c_horafim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd30_i_fichas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd30_i_fichas"])){
       $sql  .= $virgula." sd30_i_fichas = $this->sd30_i_fichas ";
       $virgula = ",";
       if(trim($this->sd30_i_fichas) == null ){
         $this->erro_sql = " Campo Fichas não informado.";
         $this->erro_campo = "sd30_i_fichas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd30_i_reservas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd30_i_reservas"])){
       $sql  .= $virgula." sd30_i_reservas = $this->sd30_i_reservas ";
       $virgula = ",";
       if(trim($this->sd30_i_reservas) == null ){
         $this->erro_sql = " Campo Reservas não informado.";
         $this->erro_campo = "sd30_i_reservas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd30_i_turno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd30_i_turno"])){
       $sql  .= $virgula." sd30_i_turno = $this->sd30_i_turno ";
       $virgula = ",";
       if(trim($this->sd30_i_turno) == null ){
         $this->erro_sql = " Campo Turno não informado.";
         $this->erro_campo = "sd30_i_turno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd30_c_tipograde)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd30_c_tipograde"])){
       $sql  .= $virgula." sd30_c_tipograde = '$this->sd30_c_tipograde' ";
       $virgula = ",";
       if(trim($this->sd30_c_tipograde) == null ){
         $this->erro_sql = " Campo Tipo Grade não informado.";
         $this->erro_campo = "sd30_c_tipograde";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd30_i_tipoficha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd30_i_tipoficha"])){
       $sql  .= $virgula." sd30_i_tipoficha = $this->sd30_i_tipoficha ";
       $virgula = ",";
       if(trim($this->sd30_i_tipoficha) == null ){
         $this->erro_sql = " Campo Tipo Ficha não informado.";
         $this->erro_campo = "sd30_i_tipoficha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd30_d_valinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd30_d_valinicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd30_d_valinicial_dia"] !="") ){
       $sql  .= $virgula." sd30_d_valinicial = '$this->sd30_d_valinicial' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd30_d_valinicial_dia"])){
         $sql  .= $virgula." sd30_d_valinicial = null ";
         $virgula = ",";
       }
     }
     if(trim($this->sd30_d_valfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd30_d_valfinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd30_d_valfinal_dia"] !="") ){
       $sql  .= $virgula." sd30_d_valfinal = '$this->sd30_d_valfinal' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd30_d_valfinal_dia"])){
         $sql  .= $virgula." sd30_d_valfinal = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($sd30_i_codigo!=null){
       $sql .= " sd30_i_codigo = $this->sd30_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd30_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008811,'$this->sd30_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd30_i_codigo"]) || $this->sd30_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010138,1008811,'".AddSlashes(pg_result($resaco,$conresaco,'sd30_i_codigo'))."','$this->sd30_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd30_i_undmed"]) || $this->sd30_i_undmed != "")
             $resac = db_query("insert into db_acount values($acount,1010138,1008812,'".AddSlashes(pg_result($resaco,$conresaco,'sd30_i_undmed'))."','$this->sd30_i_undmed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd30_i_diasemana"]) || $this->sd30_i_diasemana != "")
             $resac = db_query("insert into db_acount values($acount,1010138,1008706,'".AddSlashes(pg_result($resaco,$conresaco,'sd30_i_diasemana'))."','$this->sd30_i_diasemana',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd30_c_horaini"]) || $this->sd30_c_horaini != "")
             $resac = db_query("insert into db_acount values($acount,1010138,1008707,'".AddSlashes(pg_result($resaco,$conresaco,'sd30_c_horaini'))."','$this->sd30_c_horaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd30_c_horafim"]) || $this->sd30_c_horafim != "")
             $resac = db_query("insert into db_acount values($acount,1010138,1008708,'".AddSlashes(pg_result($resaco,$conresaco,'sd30_c_horafim'))."','$this->sd30_c_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd30_i_fichas"]) || $this->sd30_i_fichas != "")
             $resac = db_query("insert into db_acount values($acount,1010138,1008709,'".AddSlashes(pg_result($resaco,$conresaco,'sd30_i_fichas'))."','$this->sd30_i_fichas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd30_i_reservas"]) || $this->sd30_i_reservas != "")
             $resac = db_query("insert into db_acount values($acount,1010138,1008710,'".AddSlashes(pg_result($resaco,$conresaco,'sd30_i_reservas'))."','$this->sd30_i_reservas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd30_i_turno"]) || $this->sd30_i_turno != "")
             $resac = db_query("insert into db_acount values($acount,1010138,1008865,'".AddSlashes(pg_result($resaco,$conresaco,'sd30_i_turno'))."','$this->sd30_i_turno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd30_c_tipograde"]) || $this->sd30_c_tipograde != "")
             $resac = db_query("insert into db_acount values($acount,1010138,12508,'".AddSlashes(pg_result($resaco,$conresaco,'sd30_c_tipograde'))."','$this->sd30_c_tipograde',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd30_i_tipoficha"]) || $this->sd30_i_tipoficha != "")
             $resac = db_query("insert into db_acount values($acount,1010138,12509,'".AddSlashes(pg_result($resaco,$conresaco,'sd30_i_tipoficha'))."','$this->sd30_i_tipoficha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd30_d_valinicial"]) || $this->sd30_d_valinicial != "")
             $resac = db_query("insert into db_acount values($acount,1010138,12510,'".AddSlashes(pg_result($resaco,$conresaco,'sd30_d_valinicial'))."','$this->sd30_d_valinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd30_d_valfinal"]) || $this->sd30_d_valfinal != "")
             $resac = db_query("insert into db_acount values($acount,1010138,12511,'".AddSlashes(pg_result($resaco,$conresaco,'sd30_d_valfinal'))."','$this->sd30_d_valfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidade/Médico/Horário nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd30_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Unidade/Médico/Horário nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd30_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd30_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($sd30_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($sd30_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008811,'$sd30_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010138,1008811,'','".AddSlashes(pg_result($resaco,$iresaco,'sd30_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010138,1008812,'','".AddSlashes(pg_result($resaco,$iresaco,'sd30_i_undmed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010138,1008706,'','".AddSlashes(pg_result($resaco,$iresaco,'sd30_i_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010138,1008707,'','".AddSlashes(pg_result($resaco,$iresaco,'sd30_c_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010138,1008708,'','".AddSlashes(pg_result($resaco,$iresaco,'sd30_c_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010138,1008709,'','".AddSlashes(pg_result($resaco,$iresaco,'sd30_i_fichas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010138,1008710,'','".AddSlashes(pg_result($resaco,$iresaco,'sd30_i_reservas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010138,1008865,'','".AddSlashes(pg_result($resaco,$iresaco,'sd30_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010138,12508,'','".AddSlashes(pg_result($resaco,$iresaco,'sd30_c_tipograde'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010138,12509,'','".AddSlashes(pg_result($resaco,$iresaco,'sd30_i_tipoficha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010138,12510,'','".AddSlashes(pg_result($resaco,$iresaco,'sd30_d_valinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010138,12511,'','".AddSlashes(pg_result($resaco,$iresaco,'sd30_d_valfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from undmedhorario
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sd30_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sd30_i_codigo = $sd30_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidade/Médico/Horário nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd30_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Unidade/Médico/Horário nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd30_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd30_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:undmedhorario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($sd30_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from undmedhorario ";
     $sql .= "      inner join sau_tipoficha  on  sau_tipoficha.sd101_i_codigo = undmedhorario.sd30_i_tipoficha";
     $sql .= "      inner join especmedico  on  especmedico.sd27_i_codigo = undmedhorario.sd30_i_undmed";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = undmedhorario.sd30_i_diasemana";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";
     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd30_i_codigo)) {
         $sql2 .= " where undmedhorario.sd30_i_codigo = $sd30_i_codigo ";
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
   public function sql_query_file ($sd30_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from undmedhorario ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd30_i_codigo)){
         $sql2 .= " where undmedhorario.sd30_i_codigo = $sd30_i_codigo ";
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


  /*
  Função SQL utilizado para gerar o relatorio com as grades de horário dos profissionais e seus agendamentos
  (Agendamento -> Relatórios -> Agendamento)
  */
  function sql_query_grade_relatorio ( $sd30_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from undmedhorario ";
     $sql .= "      inner join sau_tipoficha  on  sau_tipoficha.sd101_i_codigo = undmedhorario.sd30_i_tipoficha";
     $sql .= "      inner join especmedico  on  especmedico.sd27_i_codigo = undmedhorario.sd30_i_undmed";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = undmedhorario.sd30_i_diasemana";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";
     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo =  unidademedicos.sd04_i_unidade";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql2 = "";
     if($dbwhere==""){
       if($sd30_i_codigo!=null ){
         $sql2 .= " where undmedhorario.sd30_i_codigo = $sd30_i_codigo ";
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
   * Busca a agenda de um médico na unidade para as suas especialidades em um período informado
   *
   * @param  String  $dataInicial
   * @param  String  $dataFinal
   * @param  Integer $codigoMedico
   * @param  Integer $codigoUnidade
   * @param  array   $codigosEspecialidade  Array com os códigos das especialidades
   * @return String                         Consulta SQL
   */
  public function sql_query_agenda_medico($dataInicial, $dataFinal, $codigoMedico, $codigoUnidade, array $codigosEspecialidade, $campos = null) {

    $dataInicial          = "'{$dataInicial}'::date";
    $dataFinal            = "'{$dataFinal}'::date";
    $codigoMedico         = +$codigoMedico;
    $codigoUnidade        = +$codigoUnidade;
    $codigosEspecialidade = implode(", ", $codigosEspecialidade);

    $campos               = !!$campos ? $campos : "
      agenda_medica.data_atendimento,
      agenda_medica.especialidade,
      agenda_medica.tipo_ficha,
      agenda_medica.dia_semana,
      agenda_medica.hora_inicial,
      agenda_medica.hora_final,
      agenda_medica.fichas,
      agenda_medica.reservas,
      resultado_ausencias.data_afastamento,
      resultado_ausencias.hora_inicio_afastamento,
      resultado_ausencias.hora_fim_afastamento,
      resultado_ausencias.motivo_afastamento
    ";



    $sSql = " select $campos ";
    $sSql.= "   from (select distinct ";
    $sSql.= "                ($dataInicial + dias_soma) as data_atendimento, ";
    $sSql.= "                sd04_i_unidade   as codigo_unidade, ";
    $sSql.= "                rh70_descr       as especialidade, ";
    $sSql.= "                rh70_sequencial  as codigo_especialidade, ";
    $sSql.= "                sd101_c_descr    as tipo_ficha, ";
    $sSql.= "                ed32_c_descr     as dia_semana, ";
    $sSql.= "                sd30_i_fichas    as fichas, ";
    $sSql.= "                sd30_i_reservas  as reservas, ";
    $sSql.= "                sd30_c_horaini   as hora_inicial, ";
    $sSql.= "                sd30_c_horafim   as hora_final ";
    $sSql.= "           from generate_series(0, abs($dataInicial - $dataFinal)) as dias_soma, ";
    $sSql.= "                undmedhorario ";
    $sSql.= "                inner join sau_tipoficha  on sau_tipoficha.sd101_i_codigo     = undmedhorario.sd30_i_tipoficha ";
    $sSql.= "                inner join especmedico    on especmedico.sd27_i_codigo        = undmedhorario.sd30_i_undmed ";
    $sSql.= "                inner join diasemana      on diasemana.ed32_i_codigo          = undmedhorario.sd30_i_diasemana ";
    $sSql.= "                inner join rhcbo          on rhcbo.rh70_sequencial            = especmedico.sd27_i_rhcbo ";
    $sSql.= "                inner join unidademedicos on unidademedicos.sd04_i_codigo     = especmedico.sd27_i_undmed ";
    $sSql.= "                inner join medicos        on medicos.sd03_i_codigo            = unidademedicos.sd04_i_medico ";
    $sSql.= "                inner join cgm            on cgm.z01_numcgm                   = medicos.sd03_i_cgm ";
    $sSql.= "                inner join unidades       on unidades.sd02_i_codigo           = unidademedicos.sd04_i_unidade ";
    $sSql.= "                inner join db_depart      on db_depart.coddepto               = unidades.sd02_i_codigo ";
    $sSql.= "          where sd04_i_medico = $codigoMedico ";
    $sSql.= "            and extract(isodow from ($dataInicial + dias_soma)) + 1 = sd30_i_diasemana ";
    $sSql.= "            and ($dataInicial + dias_soma) between sd30_d_valinicial ";
    $sSql.= "                                     and case when sd30_d_valfinal is null ";
    $sSql.= "                                              then $dataFinal ";
    $sSql.= "                                              else sd30_d_valfinal ";
    $sSql.= "                                          end ";
    $sSql.= "       ) as agenda_medica ";
    $sSql.= "       left join ( ";
    $sSql.= "                  select ($dataInicial + dias)                      as data_afastamento, ";
    $sSql.= "                         sd04_i_unidade                   as unidade_afastamento, ";
    $sSql.= "                         sd06_d_inicio                    as inicio_afastamento, ";
    $sSql.= "                         sd06_d_fim                       as fim_afastamento, ";
    $sSql.= "                         sd06_i_especmed                  as especialidade_medico_afastado, ";
    $sSql.= "                         case
                                        when trim(sd06_c_horainicio) != ''
                                        then trim(sd06_c_horainicio)
                                        else null
                                      end as hora_inicio_afastamento, ";
    $sSql.= "                         case
                                        when trim(sd06_c_horafim) != ''
                                        then trim(sd06_c_horafim)
                                        else null
                                      end as hora_fim_afastamento, ";
    $sSql.= "                         sau_motivo_ausencia.s139_c_descr as motivo_afastamento ";
    $sSql.= "                    from generate_series(0, abs($dataInicial - $dataFinal)) as dias, ";
    $sSql.= "                         ausencias ";
    $sSql.= "                         inner join especmedico         on especmedico.sd27_i_codigo         = ausencias.sd06_i_especmed ";
    $sSql.= "                         inner join unidademedicos      on unidademedicos.sd04_i_codigo      = especmedico.sd27_i_undmed ";
    $sSql.= "                         inner join unidades            on unidades.sd02_i_codigo            = unidademedicos.sd04_i_unidade ";
    $sSql.= "                         inner join medicos             on medicos.sd03_i_codigo             = unidademedicos.sd04_i_medico ";
    $sSql.= "                         inner join db_depart           on db_depart.coddepto                = unidades.sd02_i_codigo ";
    $sSql.= "                         inner join sau_motivo_ausencia on sau_motivo_ausencia.s139_i_codigo = ausencias.sd06_i_tipo ";
    $sSql.= "                    where sd04_i_medico = $codigoMedico ";
    $sSql.= "                      and ($dataInicial::date + dias) between sd06_d_inicio ";
    $sSql.= "                                                and sd06_d_fim ";
    $sSql.= "                 ) as resultado_ausencias on agenda_medica.data_atendimento = resultado_ausencias.data_afastamento ";
    $sSql.= "                                         and agenda_medica.codigo_unidade   = resultado_ausencias.unidade_afastamento ";
    $sSql.= "                                         and (
                                                            coalesce(agenda_medica.hora_inicial, '00:00')::time,
                                                            coalesce(agenda_medica.hora_final,   '23:59')::time
                                                          ) ";
    $sSql.= "                                               overlaps ";
    $sSql.= "                                             (
                                                            coalesce(resultado_ausencias.hora_inicio_afastamento, '00:00')::time,
                                                            coalesce(resultado_ausencias.hora_fim_afastamento, '23:59')::time
                                                          ) ";
    $sSql.= " where agenda_medica.codigo_unidade = $codigoUnidade ";
    $sSql.= "   and agenda_medica.codigo_especialidade in ($codigosEspecialidade) ";
    $sSql.= " order by agenda_medica.data_atendimento, ";
    $sSql.= "          agenda_medica.hora_inicial; ";
    return $sSql;
  }
}
