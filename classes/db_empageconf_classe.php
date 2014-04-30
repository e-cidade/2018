<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: empenho
//CLASSE DA ENTIDADE empageconf
class cl_empageconf { 
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
   var $e86_codmov = 0; 
   var $e86_data_dia = null; 
   var $e86_data_mes = null; 
   var $e86_data_ano = null; 
   var $e86_data = null; 
   var $e86_cheque = null; 
   var $e86_correto = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e86_codmov = int4 = Movimento 
                 e86_data = date = Data 
                 e86_cheque = varchar(20) = Cheque 
                 e86_correto = bool = Correto 
                 ";
   //funcao construtor da classe 
   function cl_empageconf() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empageconf"); 
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
       $this->e86_codmov = ($this->e86_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e86_codmov"]:$this->e86_codmov);
       if($this->e86_data == ""){
         $this->e86_data_dia = ($this->e86_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e86_data_dia"]:$this->e86_data_dia);
         $this->e86_data_mes = ($this->e86_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e86_data_mes"]:$this->e86_data_mes);
         $this->e86_data_ano = ($this->e86_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e86_data_ano"]:$this->e86_data_ano);
         if($this->e86_data_dia != ""){
            $this->e86_data = $this->e86_data_ano."-".$this->e86_data_mes."-".$this->e86_data_dia;
         }
       }
       $this->e86_cheque = ($this->e86_cheque == ""?@$GLOBALS["HTTP_POST_VARS"]["e86_cheque"]:$this->e86_cheque);
       $this->e86_correto = ($this->e86_correto == "f"?@$GLOBALS["HTTP_POST_VARS"]["e86_correto"]:$this->e86_correto);
     }else{
       $this->e86_codmov = ($this->e86_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e86_codmov"]:$this->e86_codmov);
     }
   }
   // funcao para inclusao
   function incluir ($e86_codmov){ 
      $this->atualizacampos();
     if($this->e86_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "e86_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e86_cheque == null ){ 
       $this->erro_sql = " Campo Cheque nao Informado.";
       $this->erro_campo = "e86_cheque";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e86_correto == null ){ 
       $this->erro_sql = " Campo Correto nao Informado.";
       $this->erro_campo = "e86_correto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->e86_codmov = $e86_codmov; 
     if(($this->e86_codmov == null) || ($this->e86_codmov == "") ){ 
       $this->erro_sql = " Campo e86_codmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empageconf(
                                       e86_codmov 
                                      ,e86_data 
                                      ,e86_cheque 
                                      ,e86_correto 
                       )
                values (
                                $this->e86_codmov 
                               ,".($this->e86_data == "null" || $this->e86_data == ""?"null":"'".$this->e86_data."'")." 
                               ,'$this->e86_cheque' 
                               ,'$this->e86_correto' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "confirmacao ($this->e86_codmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "confirmacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "confirmacao ($this->e86_codmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e86_codmov;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e86_codmov));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6188,'$this->e86_codmov','I')");
       $resac = db_query("insert into db_acount values($acount,1000,6188,'','".AddSlashes(pg_result($resaco,0,'e86_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1000,6189,'','".AddSlashes(pg_result($resaco,0,'e86_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1000,6190,'','".AddSlashes(pg_result($resaco,0,'e86_cheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1000,7312,'','".AddSlashes(pg_result($resaco,0,'e86_correto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e86_codmov=null) { 
      $this->atualizacampos();
     $sql = " update empageconf set ";
     $virgula = "";
     if(trim($this->e86_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e86_codmov"])){ 
       $sql  .= $virgula." e86_codmov = $this->e86_codmov ";
       $virgula = ",";
       if(trim($this->e86_codmov) == null ){ 
         $this->erro_sql = " Campo Movimento nao Informado.";
         $this->erro_campo = "e86_codmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e86_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e86_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e86_data_dia"] !="") ){ 
       $sql  .= $virgula." e86_data = '$this->e86_data' ";
       $virgula = ",";
       if(trim($this->e86_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "e86_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e86_data_dia"])){ 
         $sql  .= $virgula." e86_data = null ";
         $virgula = ",";
         if(trim($this->e86_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "e86_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->e86_cheque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e86_cheque"])){ 
       $sql  .= $virgula." e86_cheque = '$this->e86_cheque' ";
       $virgula = ",";
       if(trim($this->e86_cheque) == null ){ 
         $this->erro_sql = " Campo Cheque nao Informado.";
         $this->erro_campo = "e86_cheque";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e86_correto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e86_correto"])){ 
       $sql  .= $virgula." e86_correto = '$this->e86_correto' ";
       $virgula = ",";
       if(trim($this->e86_correto) == null ){ 
         $this->erro_sql = " Campo Correto nao Informado.";
         $this->erro_campo = "e86_correto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e86_codmov!=null){
       $sql .= " e86_codmov = $this->e86_codmov";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e86_codmov));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6188,'$this->e86_codmov','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e86_codmov"]))
           $resac = db_query("insert into db_acount values($acount,1000,6188,'".AddSlashes(pg_result($resaco,$conresaco,'e86_codmov'))."','$this->e86_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e86_data"]))
           $resac = db_query("insert into db_acount values($acount,1000,6189,'".AddSlashes(pg_result($resaco,$conresaco,'e86_data'))."','$this->e86_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e86_cheque"]))
           $resac = db_query("insert into db_acount values($acount,1000,6190,'".AddSlashes(pg_result($resaco,$conresaco,'e86_cheque'))."','$this->e86_cheque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e86_correto"]))
           $resac = db_query("insert into db_acount values($acount,1000,7312,'".AddSlashes(pg_result($resaco,$conresaco,'e86_correto'))."','$this->e86_correto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "confirmacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e86_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "confirmacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e86_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e86_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e86_codmov=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e86_codmov));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6188,'$e86_codmov','E')");
         $resac = db_query("insert into db_acount values($acount,1000,6188,'','".AddSlashes(pg_result($resaco,$iresaco,'e86_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1000,6189,'','".AddSlashes(pg_result($resaco,$iresaco,'e86_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1000,6190,'','".AddSlashes(pg_result($resaco,$iresaco,'e86_cheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1000,7312,'','".AddSlashes(pg_result($resaco,$iresaco,'e86_correto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empageconf
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e86_codmov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e86_codmov = $e86_codmov ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "confirmacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e86_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "confirmacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e86_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e86_codmov;
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
        $this->erro_sql   = "Record Vazio na Tabela:empageconf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e86_codmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empageconf ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageconf.e86_codmov";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql2 = "";
     if($dbwhere==""){
       if($e86_codmov!=null ){
         $sql2 .= " where empageconf.e86_codmov = $e86_codmov "; 
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
   function sql_query_canc ( $e86_codmov=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empageconf ";

     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageconf.e86_codmov";
     $sql .= "      left  join empageconfgera on empageconfgera.e90_codmov = empageconf.e86_codmov ";
     $sql .= "      inner join empagegera  on  empagegera.e87_codgera = empageconfgera.e90_codgera";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql .= "      inner join empord  on  empord.e82_codmov = empagemov.e81_codmov";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      inner join empagepag  on  empagepag.e85_codmov = empagemov.e81_codmov";
     $sql .= "      inner join empagetipo  on  empagetipo.e83_codtipo = empagepag.e85_codtipo";

     $sql2 = "";
     if($dbwhere==""){
       if($e86_codmov!=null ){
         $sql2 .= " where empageconf.e86_codmov = $e86_codmov ";
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
   function sql_query_cancslip ( $e86_codmov=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empageconf ";

     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageconf.e86_codmov";
     $sql .= "      left  join empageconfgera on empageconfgera.e90_codmov = empageconf.e86_codmov ";
     $sql .= "      inner join empagegera  on  empagegera.e87_codgera = empageconfgera.e90_codgera";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql .= "      inner join empageslip  on  e89_codmov = empagemov.e81_codmov";
     $sql .= "      inner join empagepag  on  empagepag.e85_codmov = empagemov.e81_codmov";
     $sql .= "      inner join empagetipo  on  empagetipo.e83_codtipo = empagepag.e85_codtipo";
     $sql .= "      inner join slip s on   e89_codigo = s.k17_codigo";
     $sql .= "      left  join emphist on s.k17_hist = e40_codhist";
     $sql .= "      left  join conhist on s.k17_hist = c50_codhist";
     $sql .= "      inner join conplanoreduz x on x.c61_reduz = s.k17_debito and x.c61_anousu =  ".db_getsession("DB_anousu");
     $sql .= "      inner join conplano z on z.c60_codcon = x.c61_codcon and z.c60_anousu=x.c61_anousu";
     $sql .= "      left join slipnum o on o.k17_codigo = s.k17_codigo";
     $sql .= "      left join cgm on z01_numcgm = o.k17_numcgm";
     $sql .= "      left join empageconfche on empageconfche.e91_codmov = empagemov.e81_codmov and e91_ativo is true ";
     $sql .= "      left join corconf on corconf.k12_codmov =  empageconfche.e91_codcheque and corconf.k12_ativo is true ";

     $sql2 = "";
     if($dbwhere==""){
       if($e86_codmov!=null ){
         $sql2 .= " where empageconf.e86_codmov = $e86_codmov ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2!=""?" and ":" where ") . " k17_instit = " . db_getsession("DB_instit");
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
   function sql_query_file ( $e86_codmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empageconf ";
     $sql2 = "";
     if($dbwhere==""){
       if($e86_codmov!=null ){
         $sql2 .= " where empageconf.e86_codmov = $e86_codmov "; 
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
   function sql_query_test ( $e86_codmov=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empageconf ";
     $sql .= "      left join empageconfgera on empageconfgera.e90_codmov = empageconf.e86_codmov ";

     $sql2 = "";
     if($dbwhere==""){
       if($e86_codmov!=null ){
         $sql2 .= " where empageconf.e86_codmov = $e86_codmov ";
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