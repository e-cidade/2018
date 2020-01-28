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

//MODULO: ambulatorial
//CLASSE DA ENTIDADE sau_fecharquivo
class cl_sau_fecharquivo {
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
   var $sd99_i_codigo = 0;
   var $sd99_t_arquivo = null;
   var $sd99_i_fechamento = 0;
   var $sd99_c_hora = null;
   var $sd99_i_login = 0;
   var $sd99_d_data_dia = null;
   var $sd99_d_data_mes = null;
   var $sd99_d_data_ano = null;
   var $sd99_d_data = null;
   var $sd99_objarquivo = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 sd99_i_codigo = int4 = Código
                 sd99_t_arquivo = text = Arquivo
                 sd99_i_fechamento = int4 = Fechamento
                 sd99_c_hora = varchar(20) = Hora
                 sd99_i_login = int4 = Login
                 sd99_d_data = date = Data
                 sd99_objarquivo = oid = Arquivo
                 ";
   //funcao construtor da classe
   function cl_sau_fecharquivo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_fecharquivo");
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
       $this->sd99_i_codigo = ($this->sd99_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd99_i_codigo"]:$this->sd99_i_codigo);
       $this->sd99_t_arquivo = ($this->sd99_t_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd99_t_arquivo"]:$this->sd99_t_arquivo);
       $this->sd99_i_fechamento = ($this->sd99_i_fechamento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd99_i_fechamento"]:$this->sd99_i_fechamento);
       $this->sd99_c_hora = ($this->sd99_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["sd99_c_hora"]:$this->sd99_c_hora);
       $this->sd99_i_login = ($this->sd99_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["sd99_i_login"]:$this->sd99_i_login);
       if($this->sd99_d_data == ""){
         $this->sd99_d_data_dia = ($this->sd99_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd99_d_data_dia"]:$this->sd99_d_data_dia);
         $this->sd99_d_data_mes = ($this->sd99_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd99_d_data_mes"]:$this->sd99_d_data_mes);
         $this->sd99_d_data_ano = ($this->sd99_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd99_d_data_ano"]:$this->sd99_d_data_ano);
         if($this->sd99_d_data_dia != ""){
            $this->sd99_d_data = $this->sd99_d_data_ano."-".$this->sd99_d_data_mes."-".$this->sd99_d_data_dia;
         }
       }
       $this->sd99_objarquivo = ($this->sd99_objarquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd99_objarquivo"]:$this->sd99_objarquivo);
     }else{
       $this->sd99_i_codigo = ($this->sd99_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd99_i_codigo"]:$this->sd99_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd99_i_codigo){
      $this->atualizacampos();
     if($this->sd99_t_arquivo == null ){
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "sd99_t_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd99_i_fechamento == null ){
       $this->erro_sql = " Campo Fechamento nao Informado.";
       $this->erro_campo = "sd99_i_fechamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd99_c_hora == null ){
       $this->sd99_c_hora = "'||current_time||'";
     }
     if($this->sd99_i_login == null ){
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "sd99_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd99_d_data == null ){
       $this->sd99_d_data = "now()";
     }
     if($this->sd99_objarquivo == null ){
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "sd99_objarquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd99_i_codigo == "" || $sd99_i_codigo == null ){
       $result = db_query("select nextval('sau_fecharquivo_sd99_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_fecharquivo_sd99_codigo_seq do campo: sd99_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->sd99_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from sau_fecharquivo_sd99_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd99_i_codigo)){
         $this->erro_sql = " Campo sd99_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd99_i_codigo = $sd99_i_codigo;
       }
     }
     if(($this->sd99_i_codigo == null) || ($this->sd99_i_codigo == "") ){
       $this->erro_sql = " Campo sd99_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_fecharquivo(
                                       sd99_i_codigo
                                      ,sd99_t_arquivo
                                      ,sd99_i_fechamento
                                      ,sd99_c_hora
                                      ,sd99_i_login
                                      ,sd99_d_data
                                      ,sd99_objarquivo
                       )
                values (
                                $this->sd99_i_codigo
                               ,'$this->sd99_t_arquivo'
                               ,$this->sd99_i_fechamento
                               ,'$this->sd99_c_hora'
                               ,$this->sd99_i_login
                               ,".($this->sd99_d_data == "null" || $this->sd99_d_data == ""?"null":"'".$this->sd99_d_data."'")."
                               ,$this->sd99_objarquivo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sau_fecharquivo ($this->sd99_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sau_fecharquivo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sau_fecharquivo ($this->sd99_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd99_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd99_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12379,'$this->sd99_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2157,12379,'','".AddSlashes(pg_result($resaco,0,'sd99_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2157,12380,'','".AddSlashes(pg_result($resaco,0,'sd99_t_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2157,12382,'','".AddSlashes(pg_result($resaco,0,'sd99_i_fechamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2157,12385,'','".AddSlashes(pg_result($resaco,0,'sd99_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2157,12383,'','".AddSlashes(pg_result($resaco,0,'sd99_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2157,12384,'','".AddSlashes(pg_result($resaco,0,'sd99_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2157,18124,'','".AddSlashes(pg_result($resaco,0,'sd99_objarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($sd99_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update sau_fecharquivo set ";
     $virgula = "";
     if(trim($this->sd99_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd99_i_codigo"])){
       $sql  .= $virgula." sd99_i_codigo = $this->sd99_i_codigo ";
       $virgula = ",";
       if(trim($this->sd99_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd99_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd99_t_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd99_t_arquivo"])){
       $sql  .= $virgula." sd99_t_arquivo = '$this->sd99_t_arquivo' ";
       $virgula = ",";
       if(trim($this->sd99_t_arquivo) == null ){
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "sd99_t_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd99_i_fechamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd99_i_fechamento"])){
       $sql  .= $virgula." sd99_i_fechamento = $this->sd99_i_fechamento ";
       $virgula = ",";
       if(trim($this->sd99_i_fechamento) == null ){
         $this->erro_sql = " Campo Fechamento nao Informado.";
         $this->erro_campo = "sd99_i_fechamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd99_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd99_c_hora"])){
       $sql  .= $virgula." sd99_c_hora = '$this->sd99_c_hora' ";
       $virgula = ",";
     }
     if(trim($this->sd99_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd99_i_login"])){
       $sql  .= $virgula." sd99_i_login = $this->sd99_i_login ";
       $virgula = ",";
       if(trim($this->sd99_i_login) == null ){
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "sd99_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd99_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd99_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd99_d_data_dia"] !="") ){
       $sql  .= $virgula." sd99_d_data = '$this->sd99_d_data' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd99_d_data_dia"])){
         $sql  .= $virgula." sd99_d_data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->sd99_objarquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd99_objarquivo"])){
       $sql  .= $virgula." sd99_objarquivo = $this->sd99_objarquivo ";
       $virgula = ",";
       if(trim($this->sd99_objarquivo) == null ){
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "sd99_objarquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd99_i_codigo!=null){
       $sql .= " sd99_i_codigo = $this->sd99_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd99_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12379,'$this->sd99_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd99_i_codigo"]) || $this->sd99_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2157,12379,'".AddSlashes(pg_result($resaco,$conresaco,'sd99_i_codigo'))."','$this->sd99_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd99_t_arquivo"]) || $this->sd99_t_arquivo != "")
           $resac = db_query("insert into db_acount values($acount,2157,12380,'".AddSlashes(pg_result($resaco,$conresaco,'sd99_t_arquivo'))."','$this->sd99_t_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd99_i_fechamento"]) || $this->sd99_i_fechamento != "")
           $resac = db_query("insert into db_acount values($acount,2157,12382,'".AddSlashes(pg_result($resaco,$conresaco,'sd99_i_fechamento'))."','$this->sd99_i_fechamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd99_c_hora"]) || $this->sd99_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2157,12385,'".AddSlashes(pg_result($resaco,$conresaco,'sd99_c_hora'))."','$this->sd99_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd99_i_login"]) || $this->sd99_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2157,12383,'".AddSlashes(pg_result($resaco,$conresaco,'sd99_i_login'))."','$this->sd99_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd99_d_data"]) || $this->sd99_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2157,12384,'".AddSlashes(pg_result($resaco,$conresaco,'sd99_d_data'))."','$this->sd99_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd99_objarquivo"]) || $this->sd99_objarquivo != "")
           $resac = db_query("insert into db_acount values($acount,2157,18124,'".AddSlashes(pg_result($resaco,$conresaco,'sd99_objarquivo'))."','$this->sd99_objarquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_fecharquivo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd99_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_fecharquivo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd99_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd99_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($sd99_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd99_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12379,'$sd99_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2157,12379,'','".AddSlashes(pg_result($resaco,$iresaco,'sd99_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2157,12380,'','".AddSlashes(pg_result($resaco,$iresaco,'sd99_t_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2157,12382,'','".AddSlashes(pg_result($resaco,$iresaco,'sd99_i_fechamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2157,12385,'','".AddSlashes(pg_result($resaco,$iresaco,'sd99_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2157,12383,'','".AddSlashes(pg_result($resaco,$iresaco,'sd99_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2157,12384,'','".AddSlashes(pg_result($resaco,$iresaco,'sd99_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2157,18124,'','".AddSlashes(pg_result($resaco,$iresaco,'sd99_objarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_fecharquivo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd99_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd99_i_codigo = $sd99_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_fecharquivo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd99_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_fecharquivo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd99_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd99_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_fecharquivo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $sd99_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_fecharquivo ";
     $sql .= "      inner join sau_fechamento  on  sau_fechamento.sd97_i_codigo = sau_fecharquivo.sd99_i_fechamento";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_fechamento.sd97_i_login";
     $sql .= "      left  join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_fechamento.sd97_i_financiamento";
     $sql2 = "";
     if($dbwhere==""){
       if($sd99_i_codigo!=null ){
         $sql2 .= " where sau_fecharquivo.sd99_i_codigo = $sd99_i_codigo ";
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
   function sql_query_file ( $sd99_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_fecharquivo ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd99_i_codigo!=null ){
         $sql2 .= " where sau_fecharquivo.sd99_i_codigo = $sd99_i_codigo ";
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
 * Função que retorna o sql da produção do BPA
 * @param object $dados
 */
function sql_query_prd_bpa($oDados){

  $sSql2  = " select distinct";
  $sSql2 .= "    sd02_v_cnes as prd_ups, ";
  $sSql2 .= "    lpad(sd63_c_procedimento,10,'0') as prd_pa, ";
  $sSql2 .= "    (select count(*)  from sau_proccid ";
  $sSql2 .= "       where sd72_i_procedimento = sd63_i_codigo ";
  $sSql2 .= "         and sd72_i_anocomp = sd63_i_anocomp ";
  $sSql2 .= "         and sd72_i_mescomp = sd63_i_mescomp) as proc_quant_cid, ";
  $sSql2 .= "    lpad(rh70_estrutural,6,'0') as prd_cbo,";
  /* Se tipo de BPSA for igual a Individual */
  if ($oDados->sTipo == "02") {

    $sSql2  .= "    sd24_i_codigo as cod_faa, ";
    $sSql2  .= "    '$oDados->iCompano".str_pad ($oDados->iCompmes,2, "0", STR_PAD_LEFT )."' as prd_cmp, ";
    $sSql2  .= "    sd29_d_data as prd_dtaten, ";
    $sSql2  .= "    (select  s115_c_cartaosus from cgs_cartaosus ";
    $sSql2  .= "     where s115_i_cgs=cgs.z01_i_numcgs ";
    $sSql2  .= "     order by  s115_c_tipo asc limit 1) as prd_cnspac, ";
    $sSql2  .= "    lpad(cast(z01_v_sexo as text),1,' ') as prd_sexo, ";
    $sSql2  .= "    lpad('$oDados->iCidade',6,' ') as prd_ibge, ";
    $sSql2  .= "    lpad(cast(sd70_c_cid as text),4,' ') as prd_cid, ";
    $sSql2  .= "    lpad('1',6,'0') as prd_qt, ";
    $sSql2  .= "    '01' as prd_caten, ";
    $sSql2  .= "    '             ' as prd_naut, ";
    $sSql2  .= "    'BPA' as prd_org, ";
    $sSql2  .= "    lpad(cast(z01_v_nome as text),30,' ') as prd_nmpac, ";
    $sSql2  .= "    z01_d_nasc as prd_dtnasc, ";
    $sSql2  .= "    '99' as prd_raca, ";
    $sSql2  .= "    null as prd_flh, ";
    $sSql2  .= "    null as prd_seq, ";
    $sSql2  .= "    z02_i_cns as prd_cnsmed,";
    $sSql2  .= "    'I' as prd_orig, ";
    $sSql2  .= "    case when fc_idade(z01_d_nasc,sd29_d_data) > 99 then 40 ";
    $sSql2  .= "     else fc_idade(z01_d_nasc,sd29_d_data) ";
    $sSql2  .= "    end as prd_idade, ";
    $sSql2  .= "    z01_nome as nome_med, ";
    $sSql2  .= "    sd03_i_codigo    as cod_prof, ";
    $sSql2  .= "    z01_i_cgsund  as cod_pac, ";
    $sSql2  .= "    case when (select  s115_c_cartaosus from cgs_cartaosus";
    $sSql2  .= "               where s115_i_cgs=cgs.z01_i_numcgs order by  s115_c_tipo asc limit 1)";
    $sSql2  .= "    is null then false ";
    $sSql2  .= "    else fc_valida_cns( (select  s115_c_cartaosus from cgs_cartaosus ";
    $sSql2  .= "                         where s115_i_cgs=cgs.z01_i_numcgs order by  s115_c_tipo asc limit 1) ) ";
    $sSql2  .= "    end as valida_cns_cgs,";
    $sSql2  .= "    case when z02_i_cns is null then false ";
    $sSql2  .= "         else fc_valida_cns(z02_i_cns)  ";
    $sSql2  .= "    end as valida_cns_med";

  } else {

    /* Se tipo de retirada for igual a consolidado */
    $sSql2  .= "    'C' as prd_orig, ";
    $sSql2  .= "    ' ' as cod_procedimento, ";
    $sSql2  .= "    rh70_estrutural, ";
    $sSql2 .= "       case when ( select sd73_c_detalhe ";
    $sSql2 .= "                   from sau_procdetalhe ";
    $sSql2 .= "                   inner join sau_detalhe on sau_detalhe.sd73_i_codigo = sau_procdetalhe.sd74_i_detalhe ";
    $sSql2 .= "                   where sau_procdetalhe.sd74_i_procedimento  = sau_procedimento.sd63_i_codigo ";
    $sSql2 .= "                   and sd73_c_detalhe = '012' ";
    $sSql2 .= "                   limit 1 ";
    $sSql2 .= "                 ) = '012' then  ";
    $sSql2 .= "            case when fc_idade(z01_d_nasc,sd29_d_data) > 99 then 40 ";
    $sSql2 .= "            else fc_idade(z01_d_nasc,sd29_d_data)  ";
    $sSql2 .= "            end  ";
    $sSql2 .= "       else '999'  ";
    $sSql2 .= "       end as prd_idade, ";
    $sSql2  .= "    lpad(cast(count(sd63_i_codigo) as integer),6,0) as prd_qt";

  }
  $sSql2 .= " from sau_fechapront ";
  $sSql2 .= " inner join prontproced       on sau_fechapront.sd98_i_prontproced = prontproced.sd29_i_codigo ";
  $sSql2 .= " inner join prontuarios       on prontuarios.sd24_i_codigo         = prontproced.sd29_i_prontuario ";
  $sSql2 .= " left  join prontanulado      on prontuarios.sd24_i_codigo         = prontanulado.sd57_i_prontuario";
  $sSql2 .= " inner join cgs               on cgs.z01_i_numcgs                  = prontuarios.sd24_i_numcgs ";
  $sSql2 .= " inner join cgs_und           on cgs_und.z01_i_cgsund              = cgs.z01_i_numcgs ";
  $sSql2 .= " inner join sau_procedimento  on sau_procedimento.sd63_i_codigo    = prontproced.sd29_i_procedimento ";
  $sSql2 .= " inner join sau_financiamento on sau_financiamento.sd65_i_codigo   = sau_procedimento.sd63_i_financiamento ";
  $sSql2 .= " left  join especmedico       on especmedico.sd27_i_codigo         = prontproced.sd29_i_profissional ";
  $sSql2 .= " left  join rhcbo             on rhcbo.rh70_sequencial             = especmedico.sd27_i_rhcbo ";
  $sSql2 .= " left  join unidademedicos    on unidademedicos.sd04_i_codigo      = especmedico.sd27_i_undmed ";
  $sSql2 .= " left  join unidades          on unidades.sd02_i_codigo            = unidademedicos.sd04_i_unidade ";
  $sSql2 .= " left  join db_depart         on db_depart.coddepto                = unidades.sd02_i_codigo ";
  $sSql2 .= " left  join medicos           on medicos.sd03_i_codigo             = unidademedicos.sd04_i_medico ";
  $sSql2 .= " left  join cgm m             on m.z01_numcgm                      = medicos.sd03_i_cgm ";
  $sSql2 .= " left  join cgmdoc            on cgmdoc.z02_i_cgm                  = m.z01_numcgm ";
  $sSql2 .= " left join prontprocedcid on prontprocedcid.s135_i_prontproced = prontproced.sd29_i_codigo ";
  $sSql2 .= " left join sau_cid        on sau_cid.sd70_i_codigo             = prontprocedcid.s135_i_cid ";
  $sSql2 .= " left  join (select distinct on (s115_i_cgs) s115_i_cgs, ";
  $sSql2 .= "                    s115_c_cartaosus, ";
  $sSql2 .= "                    s115_c_tipo ";
  $sSql2 .= "               from cgs_cartaosus ";
  $sSql2 .= "              order by s115_i_cgs, s115_c_tipo asc) as cartao on  cartao.s115_i_cgs = cgs.z01_i_numcgs ";
  $sSql2 .= "where  sd98_i_fechamento=$oDados->iFechamento ";
  $sSql2 .= "   and prontanulado.sd57_i_prontuario is null";
  $sSql2 .= "   and exists ( select *  ";
  $sSql2 .= "                  from sau_procregistro ";
  $sSql2 .= "                inner join sau_registro  on sau_registro.sd84_i_codigo = sau_procregistro.sd85_i_registro";
  $sSql2 .= "                                         and sau_registro.sd84_c_registro = '$oDados->sTipo'  ";
  $sSql2 .= "                 where sau_procregistro.sd85_i_procedimento = sau_procedimento.sd63_i_codigo ";
  $sSql2 .= "              ) ";
  if ($oDados->iFinanciamento != 0) {
    $sSql2 .= " and  sd65_c_financiamento=(select sd65_c_financiamento from sau_financiamento where sd65_i_codigo=$oDados->iFinanciamento)";
  }
  if ($oDados->iUnidade != "") {
    $sSql2 .= "  and sd24_i_unidade in($oDados->iUnidade) ";
  }
  /* Se tipo for igual a consolidado*/
  if ($oDados->sTipo == "01") {

    $sSql2 .= " group by sd63_i_anocomp, sd63_i_mescomp, sd02_v_cnes, rh70_estrutural, sd63_c_procedimento, ";
    $sSql2 .= " sd63_i_codigo, prd_idade ";
    $sSql2 .= " order by prd_ups, prd_cbo, prd_pa, prd_idade";

  } else {

    /* Se tipo for igual individual */
    $sSql2 .= " order by prd_ups, prd_cnsmed, prd_cbo, prd_pa, prd_idade";

  }
  return $sSql2;

 }

/**
 * Função que retorna o sql da produção do BPA
 * @param object $dados
 */
function sql_query_cbr_bpa($oDados, $sSql) {

  $sSql1  = " select ";
  $sSql1 .= "   '#BPA#' as cbc_hdr, ";
  $sSql1 .= "   lpad($oDados->iCompano,4,'0')||lpad($oDados->iCompmes,2,'0')  as cbc_mvm,";
  $sSql1 .= "   lpad($oDados->iLinhas,6,'0')  as cbc_lin,";
  $sSql1 .= "   lpad(ceil($oDados->iLinhas/20),6,'0')  as cbc_flh,";
  $sSql1 .= "   '$oDados->sOrgResp'  as cbc_rsp, ";
  $sSql1 .= "   lpad('$oDados->sSigla',6,' ')  as cbc_sgl, ";
  $sSql1 .= "   (select cgc from db_config where codigo = ".db_getsession ( "DB_instit" ).") as cbc_cgccpf, ";
  $sSql1 .= "   lpad('$oDados->sDestino',40,' ')  as cbc_dst, ";
  $sSql1 .= "   'M' as cbc_dst_in, ";
  $sSql1 .= "   lpad('$oDados->sVersao',10,' ') as cbc_versao, ";
  $sSql1 .= "   (sum(prd_pa::bigint)+sum(prd_qt::bigint))%1111+1111 as cbc_smt_vrf ";
  $sSql1 .= " from ($sSql) as a ";
  return $sSql1;

}


 /**
  * Busca os programas realizados
  * @param string $sWhere
  * @return string
  */
  function sql_query_programas ($sWhere = null) {
    
    
    $sSql  = " select ";
    $sSql .= "        distinct                                                            ";
                      /* dados do medico */
    $sSql .= "        medicos.sd03_i_codigo                 as codigo_medico,             ";
    $sSql .= "        m.z01_nome                            as nome_medico,               ";
    $sSql .= "        cgmdoc.z02_i_cns                      as cnsmedico,                 ";
    $sSql .= "        rhcbo.rh70_estrutural                 as cbo,                       ";
    
                      /* dados atendimento */
    $sSql .= "        unidades.sd02_i_codigo                as unidade,                   ";
    $sSql .= "        unidades.sd02_v_cnes                  as cnes_unidade,              ";
    $sSql .= "        prontuarios.sd24_i_codigo             as faa,                       ";
    $sSql .= "        prontproced.sd29_d_data               as data_atendimento,          ";
    $sSql .= "        sau_procedimento.sd63_i_codigo        as codigo_procedimento,       ";
    $sSql .= "        sau_procedimento.sd63_c_procedimento  as procedimento,              ";
    $sSql .= "        sau_cid.sd70_c_cid                    as cid,                       ";
    $sSql .= "        '01'::varchar                         as char_atendimento,          ";
    $sSql .= "        1                                     as quantidade,                ";
//     $sSql .= "        sau_registro.sd84_c_registro          as tipo_registro,             ";
    
    
    $sSql .= "        ( select array_to_string(array_accum( sau_registro.sd84_c_registro), ',') ";
    $sSql .= "            from sau_procregistro                                                 ";
    $sSql .= "           inner join sau_registro  on sau_registro.sd84_i_codigo = sau_procregistro.sd85_i_registro     ";
    $sSql .= "           where sau_procregistro.sd85_i_procedimento = sau_procedimento.sd63_i_codigo) as tipo_registro,";
                      /* dados do paciente */
    
    $sSql .= "        cgs_und.z01_i_cgsund                  as codigo_paciente,           ";
    $sSql .= "        cgs_und.z01_v_nome                    as nome_paciente,             ";
    $sSql .= "        (select s115_c_cartaosus                                            ";
    $sSql .= "           from cgs_cartaosus                                               ";
    $sSql .= "          where s115_i_cgs = cgs.z01_i_numcgs                               ";
    $sSql .= "            and s115_c_cartaosus is not null                                ";
    $sSql .= "          ORDER BY s115_c_tipo ASC LIMIT 1)   as cartao_sus,                ";
    $sSql .= "        cgs_und.z01_v_sexo                    as sexo,                      ";
    $sSql .= "        cgs_und.z01_d_nasc                    as data_nascimento,           ";
    $sSql .= "        CASE                                                                ";
    $sSql .= "          WHEN fc_idade(z01_d_nasc, sd29_d_data) > 99                       ";
    $sSql .= "            THEN 40                                                         ";
    $sSql .= "          ELSE fc_idade(z01_d_nasc, sd29_d_data)                            ";
    $sSql .= "        END                                   as idade_atendimento,         ";
    $sSql .= "        cgs_und.z01_v_email                   as email,                     ";
    $sSql .= "        cgs_und.z01_c_raca                    as raca,                      ";
    $sSql .= "        cgs_und.z01_v_ender                   as endereco_paciente,         ";
    $sSql .= "        cgs_und.z01_v_compl                   as complemento_end_paciente,  ";
    $sSql .= "        cgs_und.z01_i_numero                  as numero_end_paciente,       ";
    $sSql .= "        cgs_und.z01_v_bairro                  as bairro_end_paciente,       ";
    $sSql .= "        cgs_und.z01_v_cep                     as cep_paciente,              ";
    $sSql .= "        cgs_und.z01_v_telef                   as telefone_paciente,         ";
    $sSql .= "        etnia.s200_identificador              as etinia,                    ";
    $sSql .= "        medicos.sd03_i_tipo                   as tipo_profissional          ";

    $sSql .= "   FROM sau_fechapront                                                                                  ";
    $sSql .= "        inner join prontproced       ON sau_fechapront.sd98_i_prontproced = prontproced.sd29_i_codigo   ";
    $sSql .= "        inner join prontuarios       ON prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario       ";
    $sSql .= "        left  join prontanulado      ON prontuarios.sd24_i_codigo = prontanulado.sd57_i_prontuario      ";
    $sSql .= "        inner join cgs               ON cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs                    ";
    $sSql .= "        inner join cgs_und           ON cgs_und.z01_i_cgsund = cgs.z01_i_numcgs                         ";
    $sSql .= "        left  join cgs_undetnia      ON cgs_undetnia.s201_cgs_unid = cgs_und.z01_i_cgsund               ";
    $sSql .= "        left  join etnia             ON etnia.s200_codigo = cgs_undetnia.s201_etnia                     ";
    $sSql .= "        inner join sau_procedimento  ON sau_procedimento.sd63_i_codigo = prontproced.sd29_i_procedimento ";
    $sSql .= "        inner join sau_financiamento ON sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento ";
    $sSql .= "        inner join sau_procregistro  ON sau_procregistro.sd85_i_procedimento = sau_procedimento.sd63_i_codigo ";
//     $sSql .= "        inner join sau_registro      ON sau_registro.sd84_i_codigo = sau_procregistro.sd85_i_registro   ";
    $sSql .= "        left  join especmedico       ON especmedico.sd27_i_codigo = prontproced.sd29_i_profissional     ";
    $sSql .= "        left  join rhcbo             ON rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo                ";
    $sSql .= "        left  join unidademedicos    ON unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed        ";
    $sSql .= "        left  join unidades          ON unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade          ";
    $sSql .= "        left  join db_depart         ON db_depart.coddepto = unidades.sd02_i_codigo                     ";
    $sSql .= "        left  join medicos           ON medicos.sd03_i_codigo = unidademedicos.sd04_i_medico            ";
    $sSql .= "        left  join cgm m             on m.z01_numcgm          = medicos.sd03_i_cgm                      ";
    $sSql .= "        left  join cgmdoc            ON cgmdoc.z02_i_cgm = m.z01_numcgm                                 ";
    $sSql .= "        left  join prontprocedcid    ON prontprocedcid.s135_i_prontproced = prontproced.sd29_i_codigo   ";
    $sSql .= "        left  join sau_cid           ON sau_cid.sd70_i_codigo = prontprocedcid.s135_i_cid               ";
    $sSql .= "  where prontanulado.sd57_i_prontuario is null                                                          ";
    
    if (!empty($sWhere)) {
      $sSql .= " and {$sWhere} " ;
    }
    
    $sSql .= "  order by cnsmedico,cnes_unidade, cbo, procedimento, codigo_procedimento, idade_atendimento";
    
    return $sSql;
  }
}
?>