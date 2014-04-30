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
//CLASSE DA ENTIDADE empageconfgera
class cl_empageconfgera { 
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
   var $e90_codmov = 0; 
   var $e90_codgera = 0; 
   var $e90_correto = 'f'; 
   var $e90_cancelado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e90_codmov = int4 = Movimento 
                 e90_codgera = int4 = Código 
                 e90_correto = bool = Correto 
                 e90_cancelado = bool = Cancelado 
                 ";
   //funcao construtor da classe 
   function cl_empageconfgera() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empageconfgera"); 
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
       $this->e90_codmov = ($this->e90_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e90_codmov"]:$this->e90_codmov);
       $this->e90_codgera = ($this->e90_codgera == ""?@$GLOBALS["HTTP_POST_VARS"]["e90_codgera"]:$this->e90_codgera);
       $this->e90_correto = ($this->e90_correto == "f"?@$GLOBALS["HTTP_POST_VARS"]["e90_correto"]:$this->e90_correto);
       $this->e90_cancelado = ($this->e90_cancelado == "f"?@$GLOBALS["HTTP_POST_VARS"]["e90_cancelado"]:$this->e90_cancelado);
     }else{
       $this->e90_codmov = ($this->e90_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e90_codmov"]:$this->e90_codmov);
       $this->e90_codgera = ($this->e90_codgera == ""?@$GLOBALS["HTTP_POST_VARS"]["e90_codgera"]:$this->e90_codgera);
     }
   }
   // funcao para inclusao
   function incluir ($e90_codmov,$e90_codgera){ 
      $this->atualizacampos();
     if($this->e90_correto == null ){ 
       $this->erro_sql = " Campo Correto nao Informado.";
       $this->erro_campo = "e90_correto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e90_cancelado == null ){ 
       $this->erro_sql = " Campo Cancelado nao Informado.";
       $this->erro_campo = "e90_cancelado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->e90_codmov = $e90_codmov; 
       $this->e90_codgera = $e90_codgera; 
     if(($this->e90_codmov == null) || ($this->e90_codmov == "") ){ 
       $this->erro_sql = " Campo e90_codmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->e90_codgera == null) || ($this->e90_codgera == "") ){ 
       $this->erro_sql = " Campo e90_codgera nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empageconfgera(
                                       e90_codmov 
                                      ,e90_codgera 
                                      ,e90_correto 
                                      ,e90_cancelado 
                       )
                values (
                                $this->e90_codmov 
                               ,$this->e90_codgera 
                               ,'$this->e90_correto' 
                               ,'$this->e90_cancelado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "empageconfgera ($this->e90_codmov."-".$this->e90_codgera) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "empageconfgera já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "empageconfgera ($this->e90_codmov."-".$this->e90_codgera) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e90_codmov."-".$this->e90_codgera;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e90_codmov,$this->e90_codgera));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6210,'$this->e90_codmov','I')");
       $resac = db_query("insert into db_acountkey values($acount,6211,'$this->e90_codgera','I')");
       $resac = db_query("insert into db_acount values($acount,1005,6210,'','".AddSlashes(pg_result($resaco,0,'e90_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1005,6211,'','".AddSlashes(pg_result($resaco,0,'e90_codgera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1005,7233,'','".AddSlashes(pg_result($resaco,0,'e90_correto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1005,19303,'','".AddSlashes(pg_result($resaco,0,'e90_cancelado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e90_codmov=null,$e90_codgera=null) { 
      $this->atualizacampos();
     $sql = " update empageconfgera set ";
     $virgula = "";
     if(trim($this->e90_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e90_codmov"])){ 
       $sql  .= $virgula." e90_codmov = $this->e90_codmov ";
       $virgula = ",";
       if(trim($this->e90_codmov) == null ){ 
         $this->erro_sql = " Campo Movimento nao Informado.";
         $this->erro_campo = "e90_codmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e90_codgera)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e90_codgera"])){ 
       $sql  .= $virgula." e90_codgera = $this->e90_codgera ";
       $virgula = ",";
       if(trim($this->e90_codgera) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "e90_codgera";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e90_correto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e90_correto"])){ 
       $sql  .= $virgula." e90_correto = '$this->e90_correto' ";
       $virgula = ",";
       if(trim($this->e90_correto) == null ){ 
         $this->erro_sql = " Campo Correto nao Informado.";
         $this->erro_campo = "e90_correto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e90_cancelado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e90_cancelado"])){ 
       $sql  .= $virgula." e90_cancelado = '$this->e90_cancelado' ";
       $virgula = ",";
       if(trim($this->e90_cancelado) == null ){ 
         $this->erro_sql = " Campo Cancelado nao Informado.";
         $this->erro_campo = "e90_cancelado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e90_codmov!=null){
       $sql .= " e90_codmov = $this->e90_codmov";
     }
     if($e90_codgera!=null){
       $sql .= " and  e90_codgera = $this->e90_codgera";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e90_codmov,$this->e90_codgera));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6210,'$this->e90_codmov','A')");
         $resac = db_query("insert into db_acountkey values($acount,6211,'$this->e90_codgera','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e90_codmov"]))
           $resac = db_query("insert into db_acount values($acount,1005,6210,'".AddSlashes(pg_result($resaco,$conresaco,'e90_codmov'))."','$this->e90_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e90_codgera"]))
           $resac = db_query("insert into db_acount values($acount,1005,6211,'".AddSlashes(pg_result($resaco,$conresaco,'e90_codgera'))."','$this->e90_codgera',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e90_correto"]))
           $resac = db_query("insert into db_acount values($acount,1005,7233,'".AddSlashes(pg_result($resaco,$conresaco,'e90_correto'))."','$this->e90_correto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e90_cancelado"]) || $this->e90_cancelado != "")
           $resac = db_query("insert into db_acount values($acount,1005,19303,'".AddSlashes(pg_result($resaco,$conresaco,'e90_cancelado'))."','$this->e90_cancelado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "empageconfgera nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e90_codmov."-".$this->e90_codgera;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "empageconfgera nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e90_codmov."-".$this->e90_codgera;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e90_codmov."-".$this->e90_codgera;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e90_codmov=null,$e90_codgera=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e90_codmov,$e90_codgera));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6210,'$e90_codmov','E')");
         $resac = db_query("insert into db_acountkey values($acount,6211,'$e90_codgera','E')");
         $resac = db_query("insert into db_acount values($acount,1005,6210,'','".AddSlashes(pg_result($resaco,$iresaco,'e90_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1005,6211,'','".AddSlashes(pg_result($resaco,$iresaco,'e90_codgera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1005,7233,'','".AddSlashes(pg_result($resaco,$iresaco,'e90_correto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1005,19303,'','".AddSlashes(pg_result($resaco,$iresaco,'e90_cancelado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empageconfgera
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e90_codmov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e90_codmov = $e90_codmov ";
        }
        if($e90_codgera != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e90_codgera = $e90_codgera ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "empageconfgera nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e90_codmov."-".$e90_codgera;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "empageconfgera nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e90_codmov."-".$e90_codgera;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e90_codmov."-".$e90_codgera;
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
        $this->erro_sql   = "Record Vazio na Tabela:empageconfgera";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e90_codmov=null,$e90_codgera=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empageconfgera ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageconfgera.e90_codmov";
     $sql .= "      inner join empagegera  on  empagegera.e87_codgera = empageconfgera.e90_codgera";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql2 = "";
     if($dbwhere==""){
       if($e90_codmov!=null ){
         $sql2 .= " where empageconfgera.e90_codmov = $e90_codmov "; 
       } 
       if($e90_codgera!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empageconfgera.e90_codgera = $e90_codgera "; 
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

  function sql_query_arq ( $e90_codmov=null,$e90_codgera=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empageconfgera ";
     $sql .= "      inner join empagemov      on  empagemov.e81_codmov   = empageconfgera.e90_codmov  ";
     $sql .= "      inner join empagegera     on  empagegera.e87_codgera = empageconfgera.e90_codgera ";
     $sql .= "      left  join empempenho     on  empempenho.e60_numemp  = empagemov.e81_numemp       ";
     $sql .= "      left  join empagemovforma on  e97_codmov             = e81_codmov                 ";     
     $sql .= "      inner join empage         on  empage.e80_codage      = empagemov.e81_codage       ";
     $sql2 = "";
     if($dbwhere==""){
       if($e90_codmov!=null ){
         $sql2 .= " where empageconfgera.e90_codmov = $e90_codmov "; 
       } 
       if($e90_codgera!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empageconfgera.e90_codgera = $e90_codgera "; 
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


   function sql_query_arqcanc ( $e90_codmov=null,$e90_codgera=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empageconfgera ";

     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageconfgera.e90_codmov ";
     $sql .= "      left join empageconf on empageconf.e86_codmov = empagemov.e81_codmov ";
     $sql .= "      inner join empagegera  on  empagegera.e87_codgera = empageconfgera.e90_codgera ";
     $sql .= "      left join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp ";
     $sql .= "      left join empage  on  empage.e80_codage = empagemov.e81_codage ";
     $sql .= "      left join empord  on  empord.e82_codmov = empagemov.e81_codmov ";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm ";
     $sql .= "      left  join empagepag on empagepag.e85_codmov = empagemov.e81_codmov ";
     $sql .= "      left  join empagetipo on empagetipo.e83_codtipo = empagepag.e85_codtipo ";
     $sql .= "      left  join empagedadosret on empagedadosret.e75_codgera = empageconfgera.e90_codgera ";
     $sql .= "      left  join pagordemconta on pagordemconta.e49_codord = empord.e82_codord ";
     $sql .= "      left  join cgm a on a.z01_numcgm = pagordemconta.e49_numcgm ";  
     $sql .= "      left  join empageslip on empageslip.e89_codmov = empagemov.e81_codmov ";
     $sql .= "      left  join slip on empageslip.e89_codigo = slip.k17_codigo ";       
     $sql .= "      left  join slipnum on slipnum.k17_codigo = slip.k17_codigo ";       
     $sql .= "      left  join cgm cgmslip on slipnum.k17_numcgm = cgmslip.z01_numcgm ";       

     
     $sql2 = "";
     if($dbwhere==""){
       if($e90_codmov!=null ){
         $sql2 .= " where empageconfgera.e90_codmov = $e90_codmov ";
       }
       if($e90_codgera!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empageconfgera.e90_codgera = $e90_codgera ";
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
   function sql_query_file ( $e90_codmov=null,$e90_codgera=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empageconfgera ";
     $sql2 = "";
     if($dbwhere==""){
       if($e90_codmov!=null ){
         $sql2 .= " where empageconfgera.e90_codmov = $e90_codmov "; 
       } 
       if($e90_codgera!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empageconfgera.e90_codgera = $e90_codgera "; 
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
   function sql_query_inf ( $e90_codmov=null,$e90_codgera=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empageconfgera ";

     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageconfgera.e90_codmov ";
     $sql .= "      left join empageconf on empageconf.e86_codmov = empagemov.e81_codmov ";
     $sql .= "      inner join empagegera  on  empagegera.e87_codgera = empageconfgera.e90_codgera ";
     $sql .= "      left join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp ";
     $sql .= "      left join empage  on  empage.e80_codage = empagemov.e81_codage ";
     $sql .= "      left join empord  on  empord.e82_codmov = empagemov.e81_codmov ";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm ";
     $sql .= "      left  join empagepag on empagepag.e85_codmov = empagemov.e81_codmov ";
     $sql .= "      left  join empagetipo on empagetipo.e83_codtipo = empagepag.e85_codtipo ";

     
     $sql2 = "";
     if($dbwhere==""){
       if($e90_codmov!=null ){
         $sql2 .= " where empageconfgera.e90_codmov = $e90_codmov ";
       }
       if($e90_codgera!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empageconfgera.e90_codgera = $e90_codgera ";
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
  
  function sql_query_buscacodretempagedadosretmov ( $e90_codmov=null,$e90_codgera=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empageconfgera ";
    $sql .= "      inner join empagedadosret on empagedadosret.e75_codgera = empageconfgera.e90_codgera ";
    $sql2 = "";
    if($dbwhere==""){
      if($e90_codmov!=null ){
        $sql2 .= " where empageconfgera.e90_codmov = $e90_codmov ";
      }
      if($e90_codgera!=null ){
      if($sql2!=""){
      $sql2 .= " and ";
      }else{
      $sql2 .= " where ";
      }
        $sql2 .= " empageconfgera.e90_codgera = $e90_codgera ";
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
  
  function sql_query_movimentacoes_banco ( $e90_codmov = null, $e90_codgera = null, $campos = "*", $ordem = null, $dbwhere = "") {
    
    $sql = "select ";
    
    if ($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula    = "";
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }
    
    $sql .= " from empageconfgera ";
    $sql .= "      inner join empagemov  on  empagemov.e81_codmov   = empageconfgera.e90_codmov";
    $sql .= "      inner join empagegera on  empagegera.e87_codgera = empageconfgera.e90_codgera";
    $sql .= "      left  join empempenho on  empempenho.e60_numemp  = empagemov.e81_numemp";
    $sql .= "      inner join empage     on  empage.e80_codage      = empagemov.e81_codage";
    $sql2 = "";
    
    if ($dbwhere == "") {
      
      if ($e90_codmov != null ) {
        $sql2 .= " where empageconfgera.e90_codmov = $e90_codmov ";
      }
      if ($e90_codgera != null ) {
        
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " empageconfgera.e90_codgera = $e90_codgera ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    
    $sql .= $sql2;
    if ($ordem != null ) {
      
      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula = "";
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }
}
?>