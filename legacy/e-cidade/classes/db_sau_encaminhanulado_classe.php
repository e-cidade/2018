<?
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
//CLASSE DA ENTIDADE sau_encaminhanulado
class cl_sau_encaminhanulado {
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
   var $s149_i_codigo = 0;
   var $s149_i_encaminhamento = 0;
   var $s149_d_data_dia = null;
   var $s149_d_data_mes = null;
   var $s149_d_data_ano = null;
   var $s149_d_data = null;
   var $s149_c_hora = null;
   var $s149_i_login = 0;
   var $s149_t_obs = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 s149_i_codigo = int4 = Código
                 s149_i_encaminhamento = int4 = Encaminhamento
                 s149_d_data = date = Data
                 s149_c_hora = varchar(8) = Horário
                 s149_i_login = int4 = Login
                 s149_t_obs = text = Obs.
                 ";
   //funcao construtor da classe
   function cl_sau_encaminhanulado() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_encaminhanulado");
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
       $this->s149_i_codigo = ($this->s149_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s149_i_codigo"]:$this->s149_i_codigo);
       $this->s149_i_encaminhamento = ($this->s149_i_encaminhamento == ""?@$GLOBALS["HTTP_POST_VARS"]["s149_i_encaminhamento"]:$this->s149_i_encaminhamento);
       if($this->s149_d_data == ""){
         $this->s149_d_data_dia = ($this->s149_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s149_d_data_dia"]:$this->s149_d_data_dia);
         $this->s149_d_data_mes = ($this->s149_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s149_d_data_mes"]:$this->s149_d_data_mes);
         $this->s149_d_data_ano = ($this->s149_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s149_d_data_ano"]:$this->s149_d_data_ano);
         if($this->s149_d_data_dia != ""){
            $this->s149_d_data = $this->s149_d_data_ano."-".$this->s149_d_data_mes."-".$this->s149_d_data_dia;
         }
       }
       $this->s149_c_hora = ($this->s149_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["s149_c_hora"]:$this->s149_c_hora);
       $this->s149_i_login = ($this->s149_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["s149_i_login"]:$this->s149_i_login);
       $this->s149_t_obs = ($this->s149_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["s149_t_obs"]:$this->s149_t_obs);
     }else{
       $this->s149_i_codigo = ($this->s149_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s149_i_codigo"]:$this->s149_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s149_i_codigo){
      $this->atualizacampos();
     if($this->s149_i_encaminhamento == null ){
       $this->erro_sql = " Campo Encaminhamento nao Informado.";
       $this->erro_campo = "s149_i_encaminhamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s149_d_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "s149_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s149_c_hora == null ){
       $this->erro_sql = " Campo Horário nao Informado.";
       $this->erro_campo = "s149_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s149_i_login == null ){
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "s149_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s149_t_obs == null ){
       $this->erro_sql = " Campo Obs. nao Informado.";
       $this->erro_campo = "s149_t_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s149_i_codigo == "" || $s149_i_codigo == null ){
       $result = db_query("select nextval('sau_encaminhanulado_s149_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_encaminhanulado_s149_i_codigo_seq do campo: s149_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->s149_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from sau_encaminhanulado_s149_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s149_i_codigo)){
         $this->erro_sql = " Campo s149_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s149_i_codigo = $s149_i_codigo;
       }
     }
     if(($this->s149_i_codigo == null) || ($this->s149_i_codigo == "") ){
       $this->erro_sql = " Campo s149_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_encaminhanulado(
                                       s149_i_codigo
                                      ,s149_i_encaminhamento
                                      ,s149_d_data
                                      ,s149_c_hora
                                      ,s149_i_login
                                      ,s149_t_obs
                       )
                values (
                                $this->s149_i_codigo
                               ,$this->s149_i_encaminhamento
                               ,".($this->s149_d_data == "null" || $this->s149_d_data == ""?"null":"'".$this->s149_d_data."'")."
                               ,'$this->s149_c_hora'
                               ,$this->s149_i_login
                               ,'$this->s149_t_obs'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sau_encaminhanulado ($this->s149_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sau_encaminhanulado já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sau_encaminhanulado ($this->s149_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s149_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s149_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15541,'$this->s149_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2727,15541,'','".AddSlashes(pg_result($resaco,0,'s149_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2727,15542,'','".AddSlashes(pg_result($resaco,0,'s149_i_encaminhamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2727,15543,'','".AddSlashes(pg_result($resaco,0,'s149_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2727,15544,'','".AddSlashes(pg_result($resaco,0,'s149_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2727,15545,'','".AddSlashes(pg_result($resaco,0,'s149_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2727,15546,'','".AddSlashes(pg_result($resaco,0,'s149_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($s149_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update sau_encaminhanulado set ";
     $virgula = "";
     if(trim($this->s149_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s149_i_codigo"])){
       $sql  .= $virgula." s149_i_codigo = $this->s149_i_codigo ";
       $virgula = ",";
       if(trim($this->s149_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "s149_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s149_i_encaminhamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s149_i_encaminhamento"])){
       $sql  .= $virgula." s149_i_encaminhamento = $this->s149_i_encaminhamento ";
       $virgula = ",";
       if(trim($this->s149_i_encaminhamento) == null ){
         $this->erro_sql = " Campo Encaminhamento nao Informado.";
         $this->erro_campo = "s149_i_encaminhamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s149_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s149_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s149_d_data_dia"] !="") ){
       $sql  .= $virgula." s149_d_data = '$this->s149_d_data' ";
       $virgula = ",";
       if(trim($this->s149_d_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "s149_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["s149_d_data_dia"])){
         $sql  .= $virgula." s149_d_data = null ";
         $virgula = ",";
         if(trim($this->s149_d_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "s149_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s149_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s149_c_hora"])){
       $sql  .= $virgula." s149_c_hora = '$this->s149_c_hora' ";
       $virgula = ",";
       if(trim($this->s149_c_hora) == null ){
         $this->erro_sql = " Campo Horário nao Informado.";
         $this->erro_campo = "s149_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s149_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s149_i_login"])){
       $sql  .= $virgula." s149_i_login = $this->s149_i_login ";
       $virgula = ",";
       if(trim($this->s149_i_login) == null ){
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "s149_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s149_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s149_t_obs"])){
       $sql  .= $virgula." s149_t_obs = '$this->s149_t_obs' ";
       $virgula = ",";
       if(trim($this->s149_t_obs) == null ){
         $this->erro_sql = " Campo Obs. nao Informado.";
         $this->erro_campo = "s149_t_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s149_i_codigo!=null){
       $sql .= " s149_i_codigo = $this->s149_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s149_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15541,'$this->s149_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s149_i_codigo"]) || $this->s149_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2727,15541,'".AddSlashes(pg_result($resaco,$conresaco,'s149_i_codigo'))."','$this->s149_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s149_i_encaminhamento"]) || $this->s149_i_encaminhamento != "")
           $resac = db_query("insert into db_acount values($acount,2727,15542,'".AddSlashes(pg_result($resaco,$conresaco,'s149_i_encaminhamento'))."','$this->s149_i_encaminhamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s149_d_data"]) || $this->s149_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2727,15543,'".AddSlashes(pg_result($resaco,$conresaco,'s149_d_data'))."','$this->s149_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s149_c_hora"]) || $this->s149_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2727,15544,'".AddSlashes(pg_result($resaco,$conresaco,'s149_c_hora'))."','$this->s149_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s149_i_login"]) || $this->s149_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2727,15545,'".AddSlashes(pg_result($resaco,$conresaco,'s149_i_login'))."','$this->s149_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s149_t_obs"]) || $this->s149_t_obs != "")
           $resac = db_query("insert into db_acount values($acount,2727,15546,'".AddSlashes(pg_result($resaco,$conresaco,'s149_t_obs'))."','$this->s149_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_encaminhanulado nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s149_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_encaminhanulado nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s149_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s149_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($s149_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s149_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15541,'$s149_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2727,15541,'','".AddSlashes(pg_result($resaco,$iresaco,'s149_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2727,15542,'','".AddSlashes(pg_result($resaco,$iresaco,'s149_i_encaminhamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2727,15543,'','".AddSlashes(pg_result($resaco,$iresaco,'s149_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2727,15544,'','".AddSlashes(pg_result($resaco,$iresaco,'s149_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2727,15545,'','".AddSlashes(pg_result($resaco,$iresaco,'s149_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2727,15546,'','".AddSlashes(pg_result($resaco,$iresaco,'s149_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_encaminhanulado
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s149_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s149_i_codigo = $s149_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_encaminhanulado nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s149_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_encaminhanulado nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s149_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s149_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_encaminhanulado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $s149_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_encaminhanulado ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_encaminhanulado.s149_i_login";
     $sql .= "      inner join sau_encaminhamentos  on  sau_encaminhamentos.s142_i_codigo = sau_encaminhanulado.s149_i_encaminhamento";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = sau_encaminhamentos.s142_i_rhcbo";
     $sql .= "      left  join sau_prestadores  on  sau_prestadores.s110_i_codigo = sau_encaminhamentos.s142_i_prestadora";
     $sql .= "      left  join unidades  on  unidades.sd02_i_codigo = sau_encaminhamentos.s142_i_unidade";
     $sql .= "      left  join medicos  on  medicos.sd03_i_codigo = sau_encaminhamentos.s142_i_profissional";
     $sql .= "      left  join prontuarios  on  prontuarios.sd24_i_codigo = sau_encaminhamentos.s142_i_prontuario";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = sau_encaminhamentos.s142_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($s149_i_codigo!=null ){
         $sql2 .= " where sau_encaminhanulado.s149_i_codigo = $s149_i_codigo ";
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
   function sql_query_file ( $s149_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_encaminhanulado ";
     $sql2 = "";
     if($dbwhere==""){
       if($s149_i_codigo!=null ){
         $sql2 .= " where sau_encaminhanulado.s149_i_codigo = $s149_i_codigo ";
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
?>