<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: Secretaria de Educação
//CLASSE DA ENTIDADE logexcgeral
class cl_logexcgeral {
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
   var $ed256_i_codigo = 0;
   var $ed256_i_usuario = 0;
   var $ed256_i_escola = 0;
   var $ed256_d_data_dia = null;
   var $ed256_d_data_mes = null;
   var $ed256_d_data_ano = null;
   var $ed256_d_data = null;
   var $ed256_c_hora = null;
   var $ed256_c_evento = null;
   var $ed256_t_descr = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed256_i_codigo = int8 = Código
                 ed256_i_usuario = int8 = Usuário
                 ed256_i_escola = int8 = Escola
                 ed256_d_data = date = Data
                 ed256_c_hora = char(5) = Hora
                 ed256_c_evento = char(50) = Evento
                 ed256_t_descr = text = Descrição
                 ";
   //funcao construtor da classe
   function cl_logexcgeral() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("logexcgeral");
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
       $this->ed256_i_codigo = ($this->ed256_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed256_i_codigo"]:$this->ed256_i_codigo);
       $this->ed256_i_usuario = ($this->ed256_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed256_i_usuario"]:$this->ed256_i_usuario);
       $this->ed256_i_escola = ($this->ed256_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed256_i_escola"]:$this->ed256_i_escola);
       if($this->ed256_d_data == ""){
         $this->ed256_d_data_dia = ($this->ed256_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed256_d_data_dia"]:$this->ed256_d_data_dia);
         $this->ed256_d_data_mes = ($this->ed256_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed256_d_data_mes"]:$this->ed256_d_data_mes);
         $this->ed256_d_data_ano = ($this->ed256_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed256_d_data_ano"]:$this->ed256_d_data_ano);
         if($this->ed256_d_data_dia != ""){
            $this->ed256_d_data = $this->ed256_d_data_ano."-".$this->ed256_d_data_mes."-".$this->ed256_d_data_dia;
         }
       }
       $this->ed256_c_hora = ($this->ed256_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ed256_c_hora"]:$this->ed256_c_hora);
       $this->ed256_c_evento = ($this->ed256_c_evento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed256_c_evento"]:$this->ed256_c_evento);
       $this->ed256_t_descr = ($this->ed256_t_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed256_t_descr"]:$this->ed256_t_descr);
     }else{
       $this->ed256_i_codigo = ($this->ed256_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed256_i_codigo"]:$this->ed256_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed256_i_codigo){
      $this->atualizacampos();
     if($this->ed256_i_usuario == null ){
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ed256_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed256_i_escola == null ){
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed256_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed256_d_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed256_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed256_c_hora == null ){
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "ed256_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed256_c_evento == null ){
       $this->erro_sql = " Campo Evento nao Informado.";
       $this->erro_campo = "ed256_c_evento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed256_t_descr == null ){
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ed256_t_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed256_i_codigo == "" || $ed256_i_codigo == null ){
       $result = db_query("select nextval('logexcgeral_ed256_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: logexcgeral_ed256_i_codigo_seq do campo: ed256_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed256_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from logexcgeral_ed256_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed256_i_codigo)){
         $this->erro_sql = " Campo ed256_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed256_i_codigo = $ed256_i_codigo;
       }
     }
     if(($this->ed256_i_codigo == null) || ($this->ed256_i_codigo == "") ){
       $this->erro_sql = " Campo ed256_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into logexcgeral(
                                       ed256_i_codigo
                                      ,ed256_i_usuario
                                      ,ed256_i_escola
                                      ,ed256_d_data
                                      ,ed256_c_hora
                                      ,ed256_c_evento
                                      ,ed256_t_descr
                       )
                values (
                                $this->ed256_i_codigo
                               ,$this->ed256_i_usuario
                               ,$this->ed256_i_escola
                               ,".($this->ed256_d_data == "null" || $this->ed256_d_data == ""?"null":"'".$this->ed256_d_data."'")."
                               ,'$this->ed256_c_hora'
                               ,'$this->ed256_c_evento'
                               ,'$this->ed256_t_descr'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Exclusão Geral de Registros - Escola ($this->ed256_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Exclusão Geral de Registros - Escola já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Exclusão Geral de Registros - Escola ($this->ed256_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed256_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed256_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13423,'$this->ed256_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2348,13423,'','".AddSlashes(pg_result($resaco,0,'ed256_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2348,13424,'','".AddSlashes(pg_result($resaco,0,'ed256_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2348,13428,'','".AddSlashes(pg_result($resaco,0,'ed256_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2348,13425,'','".AddSlashes(pg_result($resaco,0,'ed256_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2348,13426,'','".AddSlashes(pg_result($resaco,0,'ed256_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2348,13427,'','".AddSlashes(pg_result($resaco,0,'ed256_c_evento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2348,13429,'','".AddSlashes(pg_result($resaco,0,'ed256_t_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed256_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update logexcgeral set ";
     $virgula = "";
     if(trim($this->ed256_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed256_i_codigo"])){
       $sql  .= $virgula." ed256_i_codigo = $this->ed256_i_codigo ";
       $virgula = ",";
       if(trim($this->ed256_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed256_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed256_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed256_i_usuario"])){
       $sql  .= $virgula." ed256_i_usuario = $this->ed256_i_usuario ";
       $virgula = ",";
       if(trim($this->ed256_i_usuario) == null ){
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ed256_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed256_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed256_i_escola"])){
       $sql  .= $virgula." ed256_i_escola = $this->ed256_i_escola ";
       $virgula = ",";
       if(trim($this->ed256_i_escola) == null ){
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed256_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed256_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed256_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed256_d_data_dia"] !="") ){
       $sql  .= $virgula." ed256_d_data = '$this->ed256_d_data' ";
       $virgula = ",";
       if(trim($this->ed256_d_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed256_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed256_d_data_dia"])){
         $sql  .= $virgula." ed256_d_data = null ";
         $virgula = ",";
         if(trim($this->ed256_d_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed256_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed256_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed256_c_hora"])){
       $sql  .= $virgula." ed256_c_hora = '$this->ed256_c_hora' ";
       $virgula = ",";
       if(trim($this->ed256_c_hora) == null ){
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "ed256_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed256_c_evento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed256_c_evento"])){
       $sql  .= $virgula." ed256_c_evento = '$this->ed256_c_evento' ";
       $virgula = ",";
       if(trim($this->ed256_c_evento) == null ){
         $this->erro_sql = " Campo Evento nao Informado.";
         $this->erro_campo = "ed256_c_evento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed256_t_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed256_t_descr"])){
       $sql  .= $virgula." ed256_t_descr = '$this->ed256_t_descr' ";
       $virgula = ",";
       if(trim($this->ed256_t_descr) == null ){
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ed256_t_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed256_i_codigo!=null){
       $sql .= " ed256_i_codigo = $this->ed256_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed256_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13423,'$this->ed256_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed256_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2348,13423,'".AddSlashes(pg_result($resaco,$conresaco,'ed256_i_codigo'))."','$this->ed256_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed256_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,2348,13424,'".AddSlashes(pg_result($resaco,$conresaco,'ed256_i_usuario'))."','$this->ed256_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed256_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,2348,13428,'".AddSlashes(pg_result($resaco,$conresaco,'ed256_i_escola'))."','$this->ed256_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed256_d_data"]))
           $resac = db_query("insert into db_acount values($acount,2348,13425,'".AddSlashes(pg_result($resaco,$conresaco,'ed256_d_data'))."','$this->ed256_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed256_c_hora"]))
           $resac = db_query("insert into db_acount values($acount,2348,13426,'".AddSlashes(pg_result($resaco,$conresaco,'ed256_c_hora'))."','$this->ed256_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed256_c_evento"]))
           $resac = db_query("insert into db_acount values($acount,2348,13427,'".AddSlashes(pg_result($resaco,$conresaco,'ed256_c_evento'))."','$this->ed256_c_evento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed256_t_descr"]))
           $resac = db_query("insert into db_acount values($acount,2348,13429,'".AddSlashes(pg_result($resaco,$conresaco,'ed256_t_descr'))."','$this->ed256_t_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Exclusão Geral de Registros - Escola nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed256_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão Geral de Registros - Escola nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed256_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed256_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed256_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed256_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13423,'$ed256_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2348,13423,'','".AddSlashes(pg_result($resaco,$iresaco,'ed256_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2348,13424,'','".AddSlashes(pg_result($resaco,$iresaco,'ed256_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2348,13428,'','".AddSlashes(pg_result($resaco,$iresaco,'ed256_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2348,13425,'','".AddSlashes(pg_result($resaco,$iresaco,'ed256_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2348,13426,'','".AddSlashes(pg_result($resaco,$iresaco,'ed256_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2348,13427,'','".AddSlashes(pg_result($resaco,$iresaco,'ed256_c_evento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2348,13429,'','".AddSlashes(pg_result($resaco,$iresaco,'ed256_t_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from logexcgeral
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed256_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed256_i_codigo = $ed256_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Exclusão Geral de Registros - Escola nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed256_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão Geral de Registros - Escola nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed256_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed256_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:logexcgeral";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed256_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from logexcgeral ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = logexcgeral.ed256_i_escola";
     //$sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     //$sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     //$sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     //$sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     //$sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     //$sql .= "      inner join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     //$sql .= "      left  join censoorgreg  on  censoorgreg.ed263_i_codigo = escola.ed18_i_censoorgreg";
     //$sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql2 = "";
     if($dbwhere==""){
       if($ed256_i_codigo!=null ){
         $sql2 .= " where logexcgeral.ed256_i_codigo = $ed256_i_codigo ";
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
   function sql_query_file ( $ed256_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from logexcgeral ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed256_i_codigo!=null ){
         $sql2 .= " where logexcgeral.ed256_i_codigo = $ed256_i_codigo ";
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