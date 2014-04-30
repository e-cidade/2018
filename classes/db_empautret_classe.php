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

//MODULO: empenho
//CLASSE DA ENTIDADE empautret
class cl_empautret { 
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
   var $e66_autori = 0; 
   var $e66_seqretencao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e66_autori = int4 = Autorização 
                 e66_seqretencao = int4 = Retenção 
                 ";
   //funcao construtor da classe 
   function cl_empautret() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empautret"); 
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
       $this->e66_autori = ($this->e66_autori == ""?@$GLOBALS["HTTP_POST_VARS"]["e66_autori"]:$this->e66_autori);
       $this->e66_seqretencao = ($this->e66_seqretencao == ""?@$GLOBALS["HTTP_POST_VARS"]["e66_seqretencao"]:$this->e66_seqretencao);
     }else{
       $this->e66_autori = ($this->e66_autori == ""?@$GLOBALS["HTTP_POST_VARS"]["e66_autori"]:$this->e66_autori);
       $this->e66_seqretencao = ($this->e66_seqretencao == ""?@$GLOBALS["HTTP_POST_VARS"]["e66_seqretencao"]:$this->e66_seqretencao);
     }
   }
   // funcao para inclusao
   function incluir ($e66_autori,$e66_seqretencao){ 
      $this->atualizacampos();
       $this->e66_autori = $e66_autori; 
       $this->e66_seqretencao = $e66_seqretencao; 
     if(($this->e66_autori == null) || ($this->e66_autori == "") ){ 
       $this->erro_sql = " Campo e66_autori nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->e66_seqretencao == null) || ($this->e66_seqretencao == "") ){ 
       $this->erro_sql = " Campo e66_seqretencao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empautret(
                                       e66_autori 
                                      ,e66_seqretencao 
                       )
                values (
                                $this->e66_autori 
                               ,$this->e66_seqretencao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ligação das retenções com autorização ($this->e66_autori."-".$this->e66_seqretencao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ligação das retenções com autorização já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ligação das retenções com autorização ($this->e66_autori."-".$this->e66_seqretencao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e66_autori."-".$this->e66_seqretencao;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e66_autori,$this->e66_seqretencao));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9640,'$this->e66_autori','I')");
       $resac = db_query("insert into db_acountkey values($acount,9641,'$this->e66_seqretencao','I')");
       $resac = db_query("insert into db_acount values($acount,1658,9640,'','".AddSlashes(pg_result($resaco,0,'e66_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1658,9641,'','".AddSlashes(pg_result($resaco,0,'e66_seqretencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e66_autori=null,$e66_seqretencao=null) { 
      $this->atualizacampos();
     $sql = " update empautret set ";
     $virgula = "";
     if(trim($this->e66_autori)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e66_autori"])){ 
       $sql  .= $virgula." e66_autori = $this->e66_autori ";
       $virgula = ",";
       if(trim($this->e66_autori) == null ){ 
         $this->erro_sql = " Campo Autorização nao Informado.";
         $this->erro_campo = "e66_autori";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e66_seqretencao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e66_seqretencao"])){ 
       $sql  .= $virgula." e66_seqretencao = $this->e66_seqretencao ";
       $virgula = ",";
       if(trim($this->e66_seqretencao) == null ){ 
         $this->erro_sql = " Campo Retenção nao Informado.";
         $this->erro_campo = "e66_seqretencao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e66_autori!=null){
       $sql .= " e66_autori = $this->e66_autori";
     }
     if($e66_seqretencao!=null){
       $sql .= " and  e66_seqretencao = $this->e66_seqretencao";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e66_autori,$this->e66_seqretencao));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9640,'$this->e66_autori','A')");
         $resac = db_query("insert into db_acountkey values($acount,9641,'$this->e66_seqretencao','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e66_autori"]))
           $resac = db_query("insert into db_acount values($acount,1658,9640,'".AddSlashes(pg_result($resaco,$conresaco,'e66_autori'))."','$this->e66_autori',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e66_seqretencao"]))
           $resac = db_query("insert into db_acount values($acount,1658,9641,'".AddSlashes(pg_result($resaco,$conresaco,'e66_seqretencao'))."','$this->e66_seqretencao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação das retenções com autorização nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e66_autori."-".$this->e66_seqretencao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação das retenções com autorização nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e66_autori."-".$this->e66_seqretencao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e66_autori."-".$this->e66_seqretencao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e66_autori=null,$e66_seqretencao=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e66_autori,$e66_seqretencao));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9640,'$e66_autori','E')");
         $resac = db_query("insert into db_acountkey values($acount,9641,'$e66_seqretencao','E')");
         $resac = db_query("insert into db_acount values($acount,1658,9640,'','".AddSlashes(pg_result($resaco,$iresaco,'e66_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1658,9641,'','".AddSlashes(pg_result($resaco,$iresaco,'e66_seqretencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empautret
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e66_autori != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e66_autori = $e66_autori ";
        }
        if($e66_seqretencao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e66_seqretencao = $e66_seqretencao ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação das retenções com autorização nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e66_autori."-".$e66_seqretencao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação das retenções com autorização nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e66_autori."-".$e66_seqretencao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e66_autori."-".$e66_seqretencao;
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
        $this->erro_sql   = "Record Vazio na Tabela:empautret";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function db_deleteEmpAutRet($autorizacao){
     $result_exclusoes = @pg_exec("select distinct e66_seqretencao from empautret where e66_autori = " .$autorizacao);
     $numrows_exclusoes = pg_num_rows($result_exclusoes);
     if($result_exclusoes==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Problemas ao buscar dados para exclusão. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else if($numrows_exclusoes > 0){
       $excluir_empautret = @pg_exec("delete from empautret where e66_autori = " .$autorizacao);
       if($excluir_empautret==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Problemas ao excluir da tabela empautret. Exclusão Abortada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         $this->numrows_excluir = 0;
         return false;
       }else{
         for($i=0; $i<$numrows_exclusoes; $i++){
           global $e66_seqretencao;
           db_fieldsmemory($result_exclusoes, $i);
           $excluir_empretencao = @pg_exec("delete from empretencao where e65_seq = " .$e66_seqretencao);
           if($excluir_empautret==false){
             $this->erro_banco = str_replace("\n","",@pg_last_error());
             $this->erro_sql   = "Problemas ao excluir da tabela empretencao. Exclusão Abortada.\\n";
             $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
             $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
             $this->erro_status = "0";
             $this->numrows_excluir = 0;
             return false;
           }
         }
       }
     }else{
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Nenhum item encontrado para que possa efetuar exclusão.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "1";
       $this->numrows_excluir = 0;
       return true;
     }
     $this->erro_banco = str_replace("\n","",@pg_last_error());
     $this->erro_sql   = "Exclusão Efetuada com Sucesso.\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_excluir = pg_affected_rows($excluir_empautret);
     return true;
  }
   function sql_query ( $e66_autori=null,$e66_seqretencao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empautret ";
     $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = empautret.e66_autori";
     $sql .= "      inner join empretencao  on  empretencao.e65_seq = empautret.e66_seqretencao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empautoriza.e54_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = empautoriza.e54_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empautoriza.e54_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = empautoriza.e54_depto";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empautoriza.e54_codcom";
     $sql2 = "";
     if($dbwhere==""){
       if($e66_autori!=null ){
         $sql2 .= " where empautret.e66_autori = $e66_autori "; 
       } 
       if($e66_seqretencao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empautret.e66_seqretencao = $e66_seqretencao "; 
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
   function sql_query_file ( $e66_autori=null,$e66_seqretencao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empautret ";
     $sql2 = "";
     if($dbwhere==""){
       if($e66_autori!=null ){
         $sql2 .= " where empautret.e66_autori = $e66_autori "; 
       } 
       if($e66_seqretencao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empautret.e66_seqretencao = $e66_seqretencao "; 
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
   function sql_query_retencao ( $e66_autori=null,$e66_seqretencao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empautret ";
     $sql .= "      inner join empretencao  on  empretencao.e65_seq = empautret.e66_seqretencao";
     $sql .= "      inner join tabrec       on  tabrec.k02_codigo   = empretencao.e65_receita ";
     $sql2 = "";
     if($dbwhere==""){
       if($e66_autori!=null ){
         $sql2 .= " where empautret.e66_autori = $e66_autori "; 
       } 
       if($e66_seqretencao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empautret.e66_seqretencao = $e66_seqretencao "; 
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