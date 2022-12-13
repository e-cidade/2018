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

//MODULO: saude
//CLASSE DA ENTIDADE sau_lote
class cl_sau_lote { 
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
   var $sd58_i_codigo = 0; 
   var $sd58_i_login = 0; 
   var $sd58_d_data_dia = null; 
   var $sd58_d_data_mes = null; 
   var $sd58_d_data_ano = null; 
   var $sd58_d_data = null; 
   var $sd58_c_hora = null; 
   var $sd58_c_digitada = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd58_i_codigo = int4 = Lote 
                 sd58_i_login = int4 = Login 
                 sd58_d_data = date = Cadastro 
                 sd58_c_hora = varchar(20) = Hora 
                 sd58_c_digitada = char(1) = Digitado 
                 ";
   //funcao construtor da classe 
   function cl_sau_lote() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_lote"); 
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
       $this->sd58_i_codigo = ($this->sd58_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd58_i_codigo"]:$this->sd58_i_codigo);
       $this->sd58_i_login = ($this->sd58_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["sd58_i_login"]:$this->sd58_i_login);
       if($this->sd58_d_data == ""){
         $this->sd58_d_data_dia = ($this->sd58_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd58_d_data_dia"]:$this->sd58_d_data_dia);
         $this->sd58_d_data_mes = ($this->sd58_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd58_d_data_mes"]:$this->sd58_d_data_mes);
         $this->sd58_d_data_ano = ($this->sd58_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd58_d_data_ano"]:$this->sd58_d_data_ano);
         if($this->sd58_d_data_dia != ""){
            $this->sd58_d_data = $this->sd58_d_data_ano."-".$this->sd58_d_data_mes."-".$this->sd58_d_data_dia;
         }
       }
       $this->sd58_c_hora = ($this->sd58_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["sd58_c_hora"]:$this->sd58_c_hora);
       $this->sd58_c_digitada = ($this->sd58_c_digitada == ""?@$GLOBALS["HTTP_POST_VARS"]["sd58_c_digitada"]:$this->sd58_c_digitada);
     }else{
       $this->sd58_i_codigo = ($this->sd58_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd58_i_codigo"]:$this->sd58_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd58_i_codigo){ 
      $this->atualizacampos();
     if($this->sd58_i_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "sd58_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd58_d_data == null ){ 
       $this->sd58_d_data = "now()";
     }
     if($this->sd58_c_hora == null ){ 
       $this->sd58_c_hora = "'||current_time||'";
     }
     if($this->sd58_c_digitada == null ){ 
       $this->sd58_c_digitada = "N";
     }
     if($sd58_i_codigo == "" || $sd58_i_codigo == null ){
       $result = db_query("select nextval('sau_lote_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_lote_codigo_seq do campo: sd58_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd58_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_lote_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd58_i_codigo)){
         $this->erro_sql = " Campo sd58_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd58_i_codigo = $sd58_i_codigo; 
       }
     }
     if(($this->sd58_i_codigo == null) || ($this->sd58_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd58_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_lote(
                                       sd58_i_codigo 
                                      ,sd58_i_login 
                                      ,sd58_d_data 
                                      ,sd58_c_hora 
                                      ,sd58_c_digitada 
                       )
                values (
                                $this->sd58_i_codigo 
                               ,$this->sd58_i_login 
                               ,".($this->sd58_d_data == "null" || $this->sd58_d_data == ""?"null":"'".$this->sd58_d_data."'")." 
                               ,'$this->sd58_c_hora' 
                               ,'$this->sd58_c_digitada' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lotes Saúde ($this->sd58_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lotes Saúde já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lotes Saúde ($this->sd58_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd58_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd58_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12302,'$this->sd58_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2145,12302,'','".AddSlashes(pg_result($resaco,0,'sd58_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2145,12303,'','".AddSlashes(pg_result($resaco,0,'sd58_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2145,12304,'','".AddSlashes(pg_result($resaco,0,'sd58_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2145,12305,'','".AddSlashes(pg_result($resaco,0,'sd58_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2145,12313,'','".AddSlashes(pg_result($resaco,0,'sd58_c_digitada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd58_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_lote set ";
     $virgula = "";
     if(trim($this->sd58_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd58_i_codigo"])){ 
        if(trim($this->sd58_i_codigo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd58_i_codigo"])){ 
           $this->sd58_i_codigo = "0" ; 
        } 
       $sql  .= $virgula." sd58_i_codigo = $this->sd58_i_codigo ";
       $virgula = ",";
     }
     if(trim($this->sd58_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd58_i_login"])){ 
       $sql  .= $virgula." sd58_i_login = $this->sd58_i_login ";
       $virgula = ",";
       if(trim($this->sd58_i_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "sd58_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd58_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd58_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd58_d_data_dia"] !="") ){ 
       $sql  .= $virgula." sd58_d_data = '$this->sd58_d_data' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd58_d_data_dia"])){ 
         $sql  .= $virgula." sd58_d_data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->sd58_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd58_c_hora"])){ 
       $sql  .= $virgula." sd58_c_hora = '$this->sd58_c_hora' ";
       $virgula = ",";
     }
     if(trim($this->sd58_c_digitada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd58_c_digitada"])){ 
       $sql  .= $virgula." sd58_c_digitada = '$this->sd58_c_digitada' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($sd58_i_codigo!=null){
       $sql .= " sd58_i_codigo = $this->sd58_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd58_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12302,'$this->sd58_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd58_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2145,12302,'".AddSlashes(pg_result($resaco,$conresaco,'sd58_i_codigo'))."','$this->sd58_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd58_i_login"]))
           $resac = db_query("insert into db_acount values($acount,2145,12303,'".AddSlashes(pg_result($resaco,$conresaco,'sd58_i_login'))."','$this->sd58_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd58_d_data"]))
           $resac = db_query("insert into db_acount values($acount,2145,12304,'".AddSlashes(pg_result($resaco,$conresaco,'sd58_d_data'))."','$this->sd58_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd58_c_hora"]))
           $resac = db_query("insert into db_acount values($acount,2145,12305,'".AddSlashes(pg_result($resaco,$conresaco,'sd58_c_hora'))."','$this->sd58_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd58_c_digitada"]))
           $resac = db_query("insert into db_acount values($acount,2145,12313,'".AddSlashes(pg_result($resaco,$conresaco,'sd58_c_digitada'))."','$this->sd58_c_digitada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lotes Saúde nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd58_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lotes Saúde nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd58_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd58_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd58_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd58_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12302,'$sd58_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2145,12302,'','".AddSlashes(pg_result($resaco,$iresaco,'sd58_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2145,12303,'','".AddSlashes(pg_result($resaco,$iresaco,'sd58_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2145,12304,'','".AddSlashes(pg_result($resaco,$iresaco,'sd58_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2145,12305,'','".AddSlashes(pg_result($resaco,$iresaco,'sd58_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2145,12313,'','".AddSlashes(pg_result($resaco,$iresaco,'sd58_c_digitada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_lote
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd58_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd58_i_codigo = $sd58_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lotes Saúde nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd58_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lotes Saúde nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd58_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd58_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_lote";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $sd58_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_lote ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_lote.sd58_i_login";
     $sql2 = "";
     if($dbwhere==""){
       if($sd58_i_codigo!=null ){
         $sql2 .= " where sau_lote.sd58_i_codigo = $sd58_i_codigo "; 
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
   function sql_query_file ( $sd58_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_lote ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd58_i_codigo!=null ){
         $sql2 .= " where sau_lote.sd58_i_codigo = $sd58_i_codigo "; 
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