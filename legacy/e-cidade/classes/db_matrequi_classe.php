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

//MODULO: material
//CLASSE DA ENTIDADE matrequi
class cl_matrequi {
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
   var $m40_codigo = 0;
   var $m40_data_dia = null;
   var $m40_data_mes = null;
   var $m40_data_ano = null;
   var $m40_data = null;
   var $m40_almox = 0;
   var $m40_depto = 0;
   var $m40_login = 0;
   var $m40_hora = null;
   var $m40_obs = null;
   var $m40_auto = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 m40_codigo = int8 = Código Requisição 
                 m40_data = date = Data 
                 m40_almox = int4 = Almoxarifado 
                 m40_depto = int4 = Departamento 
                 m40_login = int4 = Cod. Usuário 
                 m40_hora = varchar(5) = Hora 
                 m40_obs = text = Observação 
                 m40_auto = bool = Atendimento Automático 
                 ";
   //funcao construtor da classe
   function cl_matrequi() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matrequi");
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
       $this->m40_codigo = ($this->m40_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m40_codigo"]:$this->m40_codigo);
       if($this->m40_data == ""){
         $this->m40_data_dia = ($this->m40_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m40_data_dia"]:$this->m40_data_dia);
         $this->m40_data_mes = ($this->m40_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m40_data_mes"]:$this->m40_data_mes);
         $this->m40_data_ano = ($this->m40_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m40_data_ano"]:$this->m40_data_ano);
         if($this->m40_data_dia != ""){
            $this->m40_data = $this->m40_data_ano."-".$this->m40_data_mes."-".$this->m40_data_dia;
         }
       }
       $this->m40_almox = ($this->m40_almox == ""?@$GLOBALS["HTTP_POST_VARS"]["m40_almox"]:$this->m40_almox);
       $this->m40_depto = ($this->m40_depto == ""?@$GLOBALS["HTTP_POST_VARS"]["m40_depto"]:$this->m40_depto);
       $this->m40_login = ($this->m40_login == ""?@$GLOBALS["HTTP_POST_VARS"]["m40_login"]:$this->m40_login);
       $this->m40_hora = ($this->m40_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["m40_hora"]:$this->m40_hora);
       $this->m40_obs = ($this->m40_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["m40_obs"]:$this->m40_obs);
       $this->m40_auto = ($this->m40_auto == "f"?@$GLOBALS["HTTP_POST_VARS"]["m40_auto"]:$this->m40_auto);
     }else{
       $this->m40_codigo = ($this->m40_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m40_codigo"]:$this->m40_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($m40_codigo){
      $this->atualizacampos();
     if($this->m40_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "m40_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m40_almox == null ){
       $this->erro_sql = " Campo Almoxarifado nao Informado.";
       $this->erro_campo = "m40_almox";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m40_depto == null ){
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "m40_depto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m40_login == null ){
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "m40_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m40_hora == null ){
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "m40_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m40_auto == null ){
       $this->erro_sql = " Campo Atendimento Automático nao Informado.";
       $this->erro_campo = "m40_auto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m40_codigo == "" || $m40_codigo == null ){
       $result = db_query("select nextval('matrequi_m40_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matrequi_m40_codigo_seq do campo: m40_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->m40_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from matrequi_m40_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m40_codigo)){
         $this->erro_sql = " Campo m40_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m40_codigo = $m40_codigo;
       }
     }
     if(($this->m40_codigo == null) || ($this->m40_codigo == "") ){
       $this->erro_sql = " Campo m40_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matrequi(
                                       m40_codigo 
                                      ,m40_data 
                                      ,m40_almox 
                                      ,m40_depto 
                                      ,m40_login 
                                      ,m40_hora 
                                      ,m40_obs 
                                      ,m40_auto 
                       )
                values (
                                $this->m40_codigo 
                               ,".($this->m40_data == "null" || $this->m40_data == ""?"null":"'".$this->m40_data."'")." 
                               ,$this->m40_almox 
                               ,$this->m40_depto 
                               ,$this->m40_login 
                               ,'$this->m40_hora' 
                               ,'$this->m40_obs' 
                               ,'$this->m40_auto' 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matrequi ($this->m40_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matrequi já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matrequi ($this->m40_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m40_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m40_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6865,'$this->m40_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1128,6865,'','".AddSlashes(pg_result($resaco,0,'m40_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1128,6866,'','".AddSlashes(pg_result($resaco,0,'m40_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1128,9994,'','".AddSlashes(pg_result($resaco,0,'m40_almox'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1128,6867,'','".AddSlashes(pg_result($resaco,0,'m40_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1128,6868,'','".AddSlashes(pg_result($resaco,0,'m40_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1128,6869,'','".AddSlashes(pg_result($resaco,0,'m40_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1128,6875,'','".AddSlashes(pg_result($resaco,0,'m40_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1128,7345,'','".AddSlashes(pg_result($resaco,0,'m40_auto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($m40_codigo=null) {
      $this->atualizacampos();
     $sql = " update matrequi set ";
     $virgula = "";
     if(trim($this->m40_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m40_codigo"])){
       $sql  .= $virgula." m40_codigo = $this->m40_codigo ";
       $virgula = ",";
       if(trim($this->m40_codigo) == null ){
         $this->erro_sql = " Campo Código Requisição nao Informado.";
         $this->erro_campo = "m40_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m40_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m40_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m40_data_dia"] !="") ){
       $sql  .= $virgula." m40_data = '$this->m40_data' ";
       $virgula = ",";
       if(trim($this->m40_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "m40_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["m40_data_dia"])){
         $sql  .= $virgula." m40_data = null ";
         $virgula = ",";
         if(trim($this->m40_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "m40_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->m40_almox)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m40_almox"])){
       $sql  .= $virgula." m40_almox = $this->m40_almox ";
       $virgula = ",";
       if(trim($this->m40_almox) == null ){
         $this->erro_sql = " Campo Almoxarifado nao Informado.";
         $this->erro_campo = "m40_almox";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m40_depto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m40_depto"])){
       $sql  .= $virgula." m40_depto = $this->m40_depto ";
       $virgula = ",";
       if(trim($this->m40_depto) == null ){
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "m40_depto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m40_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m40_login"])){
       $sql  .= $virgula." m40_login = $this->m40_login ";
       $virgula = ",";
       if(trim($this->m40_login) == null ){
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "m40_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m40_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m40_hora"])){
       $sql  .= $virgula." m40_hora = '$this->m40_hora' ";
       $virgula = ",";
       if(trim($this->m40_hora) == null ){
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "m40_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m40_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m40_obs"])){
       $sql  .= $virgula." m40_obs = '$this->m40_obs' ";
       $virgula = ",";
     }
     if(trim($this->m40_auto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m40_auto"])){
       $sql  .= $virgula." m40_auto = '$this->m40_auto' ";
       $virgula = ",";
       if(trim($this->m40_auto) == null ){
         $this->erro_sql = " Campo Atendimento Automático nao Informado.";
         $this->erro_campo = "m40_auto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m40_codigo!=null){
       $sql .= " m40_codigo = $this->m40_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m40_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6865,'$this->m40_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m40_codigo"]) || $this->m40_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1128,6865,'".AddSlashes(pg_result($resaco,$conresaco,'m40_codigo'))."','$this->m40_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m40_data"]) || $this->m40_data != "")
           $resac = db_query("insert into db_acount values($acount,1128,6866,'".AddSlashes(pg_result($resaco,$conresaco,'m40_data'))."','$this->m40_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m40_almox"]) || $this->m40_almox != "")
           $resac = db_query("insert into db_acount values($acount,1128,9994,'".AddSlashes(pg_result($resaco,$conresaco,'m40_almox'))."','$this->m40_almox',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m40_depto"]) || $this->m40_depto != "")
           $resac = db_query("insert into db_acount values($acount,1128,6867,'".AddSlashes(pg_result($resaco,$conresaco,'m40_depto'))."','$this->m40_depto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m40_login"]) || $this->m40_login != "")
           $resac = db_query("insert into db_acount values($acount,1128,6868,'".AddSlashes(pg_result($resaco,$conresaco,'m40_login'))."','$this->m40_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m40_hora"]) || $this->m40_hora != "")
           $resac = db_query("insert into db_acount values($acount,1128,6869,'".AddSlashes(pg_result($resaco,$conresaco,'m40_hora'))."','$this->m40_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m40_obs"]) || $this->m40_obs != "")
           $resac = db_query("insert into db_acount values($acount,1128,6875,'".AddSlashes(pg_result($resaco,$conresaco,'m40_obs'))."','$this->m40_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m40_auto"]) || $this->m40_auto != "")
           $resac = db_query("insert into db_acount values($acount,1128,7345,'".AddSlashes(pg_result($resaco,$conresaco,'m40_auto'))."','$this->m40_auto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matrequi nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m40_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matrequi nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m40_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m40_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($m40_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m40_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6865,'$m40_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1128,6865,'','".AddSlashes(pg_result($resaco,$iresaco,'m40_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1128,6866,'','".AddSlashes(pg_result($resaco,$iresaco,'m40_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1128,9994,'','".AddSlashes(pg_result($resaco,$iresaco,'m40_almox'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1128,6867,'','".AddSlashes(pg_result($resaco,$iresaco,'m40_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1128,6868,'','".AddSlashes(pg_result($resaco,$iresaco,'m40_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1128,6869,'','".AddSlashes(pg_result($resaco,$iresaco,'m40_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1128,6875,'','".AddSlashes(pg_result($resaco,$iresaco,'m40_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1128,7345,'','".AddSlashes(pg_result($resaco,$iresaco,'m40_auto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matrequi
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m40_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m40_codigo = $m40_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matrequi nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m40_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matrequi nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m40_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m40_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:matrequi";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $m40_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matrequi ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matrequi.m40_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matrequi.m40_depto";
     $sql .= "      inner join db_almox  on  db_almox.m91_codigo = matrequi.m40_almox";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     //$sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_depart.loginresp";
     $sql .= "      left  join db_depart  as a on   a.coddepto = db_almox.m91_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($m40_codigo!=null ){
         $sql2 .= " where matrequi.m40_codigo = $m40_codigo ";
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
   function sql_query_file ( $m40_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matrequi ";
     $sql2 = "";
     if($dbwhere==""){
       if($m40_codigo!=null ){
         $sql2 .= " where matrequi.m40_codigo = $m40_codigo ";
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
   function atendRequi($iCodRequi, $iCodAtend=null, $iUsuario, $dData, $tHora, $iCodDepto, $aItens, $sErro, $iCodEstoque='') {
    // Declara variaveis
    //$sErro = "";
    $lErro = false;

    // Verifica se requisicao já não foi totalmente atendida
    $sqlverifica = $this->sql_query_unid($iCodRequi, "coalesce(sum(m41_quant), 0) as qtd_requi, coalesce(sum(m43_quantatend), 0) as qtd_atend", null, null);
    $resverifica = $this->sql_record($sqlverifica);
    if(pg_numrows($resverifica)>0) {
      db_fieldsmemory($resverifica, 0);
      if(@$qtd_requi > @$qtd_atend) {
        $lErro = true;
        $sErro = "Requisição $iCodRequi totalmente atendida!";
      }
    } else {
      $lErro = true;
      $sErro = "Requisição $iCodRequi não encontrada";
    }

    if($lErro) {
      return $lErro;
    }

    // Instancia Classes
    $oAtendRequi     = new cl_atendrequi;
    $oAtendRequiItem = new cl_atendrequiitem;
    $oMatEstoqueIni  = new cl_matestoqueini;
    $oMatEstoque     = new cl_matestoque;

    // Gera um registro de Atendimento de Requisicao, caso nao passe um Codigo de Atendimento
    if($iCodAtend == null) {
      $oAtendRequi->m42_login = $iUsuario;
      $oAtendRequi->m42_depto = $iCodDepto;
      $oAtendRequi->m42_data  = $dData;
      $oAtendRequi->m42_hora  = $tHora;
      $oAtendRequi->incluir(null);
      // Informacoes da Classe
      $sErro     = $oAtendRequi->erro_msg;
      $iCodAtend = $oAtendRequi->m42_codigo;
      $lErro     = ($oAtendRequi->erro_status == 0);
    }

    // Caso nao tenha ocorrido nenhum erro
    if(!$lErro) {
      $oMatEstoqueIni->m80_login    = $iUsuario;
      $oMatEstoqueIni->m80_data     = $dData;
      $oMatEstoqueIni->m80_hora     = date('H:i:s');
      $oMatEstoqueIni->m80_obs      = "";
      $oMatEstoqueIni->m80_codtipo  = "17"; // MatEstoqueTipo: 17 - ATENDIMENTO DE REQUISIÇÃO
      $oMatEstoqueIni->m80_coddepto = $iCodDepto;
      $oMatEstoqueIni->incluir(null);
      // Informacoes da Classe
      $sErro             = $oMatEstoqueIni->erro_msg;
      $iCodMatEstoqueIni = $oMatEstoqueIni->m80_codigo;
      $lErro             = ($oMatEstoqueIni->erro_status == 0);
    }

    //var_dump($aItens);
    //die();

    // Percorre Itens do Atendimento
    if(!$lErro) {
      for($i=0; $i<count($aItens); $i++) {
        $oAtendRequiItem->m43_codatendrequi   = $iCodAtend;
        $oAtendRequiItem->m43_codmatrequiitem = $aItens[$i]["codmatrequiitem"];
        $oAtendRequiItem->m43_quantatend      = $aItens[$i]["quantatend"];
        $oAtendRequiItem->incluir(null);
        // Informacoes da Classe
        $sErro         = $oAtendRequiItem->erro_msg;
        $iCodAtendItem = $oAtendRequiItem->m43_codigo;
        $lErro         = ($oAtendRequiItem->erro_status == 0);

        if($lErro) {
          break;
        }

        $iCodMater = $aItens[$i]["codmatmater"];
        if (isset($aItens[$i]["codmatestoqueitem"])&&$aItens[$i]["codmatestoqueitem"]!=""){
          $iCodMatestoqueitem = $aItens[$i]["codmatestoqueitem"];
        }else{
          $iCodMatestoqueitem = null;
        }

        // Atualiza Estoque (matestoque e matestoqueitem)
        $aAuxiliar["codatenditem"]     = $oAtendRequiItem->m43_codigo;
        $aAuxiliar["codmatestoqueini"] = $oMatEstoqueIni->m80_codigo;
        $lErro = $oMatEstoque->atualizaEstoque($iCodMater, $iCodDepto, 17, $aItens[$i]["quantatend"], $dData,
                              null, $aAuxiliar, $sErro,$iCodMatestoqueitem , $iCodEstoque);

        if($lErro) {
          break;
        }

      } // fim FOR Itens
    }

    return $lErro;
  }
   function sql_query_almox($m40_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matrequi ";
    $sql .= "      inner join db_usuarios     on  db_usuarios.id_usuario = matrequi.m40_login";
    $sql .= "      inner join db_depart       on  db_depart.coddepto     = matrequi.m40_depto";
    $sql .= "      inner join db_almox        on  db_almox.m91_codigo    = matrequi.m40_almox";
    $sql .= "      inner join db_depart almox on  almox.coddepto         = db_almox.m91_depto";
    $sql2 = "";
    if ($dbwhere=="") {
      if ($m40_codigo!=null ) {
        $sql2 .= " where matrequi.m40_codigo = $m40_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_almoxleft($m40_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select distinct ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
  } else {
      $sql .= $campos;
    }
    $sql .= " from matrequi ";
    $sql .= "   inner join db_usuarios     on db_usuarios.id_usuario  = matrequi.m40_login                 ";
    $sql .= "   inner join db_depart       on db_depart.coddepto      = matrequi.m40_depto                 ";
    $sql .= "   inner join db_almox        on db_almox.m91_codigo     = matrequi.m40_almox                 ";
    $sql .= "   inner join db_depart almox on almox.coddepto          = db_almox.m91_depto                 ";
    $sql .= "  	left join matrequiitem     on matrequiitem.m41_codmatrequi = matrequi.m40_codigo           ";
    $sql .= "   left join atendrequiitem   on atendrequiitem.m43_codmatrequiitem = matrequiitem.m41_codigo ";
    $sql .= "   left join atendrequi       on atendrequi.m42_codigo = atendrequiitem.m43_codatendrequi     ";

    $sql2 = "";
    if ($dbwhere=="") {
      if ($m40_codigo!=null ) {
        $sql2 .= " where matrequi.m40_codigo = $m40_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_atentimentos($m40_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select distinct ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
  } else {
      $sql .= $campos;
    }
    $sql .= " from matrequi ";
    $sql .= "   inner join db_usuarios      on db_usuarios.id_usuario  = matrequi.m40_login                 ";
    $sql .= "   inner join db_depart        on db_depart.coddepto      = matrequi.m40_depto                 ";
    $sql .= "   inner join db_almox         on db_almox.m91_codigo     = matrequi.m40_almox                 ";
    $sql .= "   inner join db_depart almox  on almox.coddepto          = db_almox.m91_depto                 ";
    $sql .= "   left join matrequiitem      on matrequiitem.m41_codmatrequi = matrequi.m40_codigo           ";
    $sql .= "   left join atendrequiitem    on atendrequiitem.m43_codmatrequiitem = matrequiitem.m41_codigo ";
    $sql .= "   left join atendrequi        on atendrequi.m42_codigo = atendrequiitem.m43_codatendrequi     ";
    $sql .= "   left join matestoquedevitem on atendrequiitem.m43_codigo =  m46_codatendrequiitem ";

    $sql2 = "";
    if ($dbwhere=="") {
      if ($m40_codigo!=null ) {
        $sql2 .= " where matrequi.m40_codigo = $m40_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_matrequi($m40_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matrequi ";
    $sql .= "      inner join matrequiitem   on  matrequiitem.m41_codmatrequi = matrequi.m40_codigo";
    $sql .= "      inner join matmater   on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
    $sql .= "      inner join matunid a  on  a.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      inner join matmaterunisai on matmaterunisai.m62_codmater = matmater.m60_codmater";
    $sql .= "      inner join matunid b  on  b.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      left  join atendrequiitem on atendrequiitem.m43_codmatrequiitem = matrequiitem.m41_codigo";
    $sql2 = "";
    if ($dbwhere=="") {
      if ($m40_codigo!=null ) {
        $sql2 .= " where matrequi.m40_codigo = $m40_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_matrequi_almox($m40_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matrequi ";
    $sql .= "      inner join matrequiitem   on  matrequiitem.m41_codmatrequi = matrequi.m40_codigo";
    $sql .= "      inner join matmater   on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
    $sql .= "      inner join matunid a  on  a.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      left join matmaterunisai on matmaterunisai.m62_codmater = matmater.m60_codmater";
    $sql .= "      left join matunid b  on  b.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      left  join atendrequiitem on atendrequiitem.m43_codmatrequiitem = matrequiitem.m41_codigo";
    $sql .= "      left join db_almox on m40_almox = m91_codigo";
    $sql2 = "";
    if ($dbwhere=="") {
      if ($m40_codigo!=null ) {
        $sql2 .= " where matrequi.m40_codigo = $m40_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_matrequi_atend_rel($m40_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matrequi ";
    $sql .= "      inner join matrequiitem   on  matrequiitem.m41_codmatrequi = matrequi.m40_codigo";
    $sql .= "      inner join matmater   on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
    $sql .= "      inner join matunid a  on  a.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      left join matmaterunisai on matmaterunisai.m62_codmater = matmater.m60_codmater";
    $sql .= "      left join matunid b  on  b.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      left join db_almox on m40_almox = m91_codigo";
    $sql2 = "";
    if ($dbwhere=="") {
      if ($m40_codigo!=null ) {
        $sql2 .= " where matrequi.m40_codigo = $m40_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_requisaida($m40_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from matrequi ";
    $sql .= "      inner join matrequiitem   on matrequiitem.m41_codmatrequi       = matrequi.m40_codigo";
    $sql .= "      inner join db_usuarios    on db_usuarios.id_usuario             = matrequi.m40_login";
    $sql .= "      inner join db_depart      on db_depart.coddepto                 = matrequi.m40_depto";
    $sql .= "      left  join atendrequiitem on atendrequiitem.m43_codmatrequiitem = matrequiitem.m41_codigo";
    $sql .= "      left  join atendrequi     on atendrequi.m42_codigo              = atendrequiitem.m43_codatendrequi";

    $sql2 = "";
    if ($dbwhere=="") {
      if ($m40_codigo!=null ) {
        $sql2 .= " where matrequi.m40_codigo = $m40_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_requisaidaalmox($m40_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from matrequi ";
    $sql .= "      inner join matrequiitem   on matrequiitem.m41_codmatrequi       = matrequi.m40_codigo";
    $sql .= "      inner join db_usuarios    on db_usuarios.id_usuario             = matrequi.m40_login";
    $sql .= "      inner join db_depart      on db_depart.coddepto                 = matrequi.m40_depto";
    $sql .= "      left  join atendrequiitem on atendrequiitem.m43_codmatrequiitem = matrequiitem.m41_codigo";
    $sql .= "      left  join atendrequi     on atendrequi.m42_codigo              = atendrequiitem.m43_codatendrequi";
    $sql .= "      left join db_almox on m40_almox = m91_codigo";

    $sql2 = "";
    if ($dbwhere=="") {
      if ($m40_codigo!=null ) {
        $sql2 .= " where matrequi.m40_codigo = $m40_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_unid($m40_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matrequi ";
    $sql .= "      inner join matrequiitem   on  matrequiitem.m41_codmatrequi = matrequi.m40_codigo";
    $sql .= "      inner join matmater   on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
    $sql .= "      left join matunid a  on  a.m61_codmatunid = matrequiitem.m41_codunid";
    $sql .= "      left  join atendrequiitem on atendrequiitem.m43_codmatrequiitem = matrequiitem.m41_codigo";
    $sql2 = "";
    if ($dbwhere=="") {
      if ($m40_codigo!=null ) {
        $sql2 .= " where matrequi.m40_codigo = $m40_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>