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

//MODULO: cadastro
//CLASSE DA ENTIDADE ruascep
class cl_ruascep { 
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
   var $j29_codigo = 0; 
   var $j29_inicio = 0; 
   var $j29_final = 0; 
   var $j29_cep = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j29_codigo = int4 = Rua 
                 j29_inicio = int4 = Numero Inicial 
                 j29_final = int4 = Numeracao Final 
                 j29_cep = char(8) = CEP 
                 ";
   //funcao construtor da classe 
   function cl_ruascep() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ruascep"); 
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
       $this->j29_codigo = ($this->j29_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j29_codigo"]:$this->j29_codigo);
       $this->j29_inicio = ($this->j29_inicio == ""?@$GLOBALS["HTTP_POST_VARS"]["j29_inicio"]:$this->j29_inicio);
       $this->j29_final = ($this->j29_final == ""?@$GLOBALS["HTTP_POST_VARS"]["j29_final"]:$this->j29_final);
       $this->j29_cep = ($this->j29_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["j29_cep"]:$this->j29_cep);
     }else{
       $this->j29_codigo = ($this->j29_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j29_codigo"]:$this->j29_codigo);
       $this->j29_inicio = ($this->j29_inicio == ""?@$GLOBALS["HTTP_POST_VARS"]["j29_inicio"]:$this->j29_inicio);
     }
   }
   // funcao para inclusao
   function incluir ($j29_codigo,$j29_inicio){ 
      $this->atualizacampos();
     if($this->j29_final == null ){ 
       $this->erro_sql = " Campo Numeracao Final nao Informado.";
       $this->erro_campo = "j29_final";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j29_cep == null ){ 
       $this->erro_sql = " Campo CEP nao Informado.";
       $this->erro_campo = "j29_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->j29_codigo = $j29_codigo; 
       $this->j29_inicio = $j29_inicio; 
     if(($this->j29_codigo == null) || ($this->j29_codigo == "") ){ 
       $this->erro_sql = " Campo j29_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j29_inicio == null) || ($this->j29_inicio == "") ){ 
       $this->erro_sql = " Campo j29_inicio nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ruascep(
                                       j29_codigo 
                                      ,j29_inicio 
                                      ,j29_final 
                                      ,j29_cep 
                       )
                values (
                                $this->j29_codigo 
                               ,$this->j29_inicio 
                               ,$this->j29_final 
                               ,'$this->j29_cep' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->j29_codigo."-".$this->j29_inicio) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->j29_codigo."-".$this->j29_inicio) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j29_codigo."-".$this->j29_inicio;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j29_codigo,$this->j29_inicio));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,73,'$this->j29_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,74,'$this->j29_inicio','I')");
       $resac = db_query("insert into db_acount values($acount,17,73,'','".AddSlashes(pg_result($resaco,0,'j29_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,17,74,'','".AddSlashes(pg_result($resaco,0,'j29_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,17,75,'','".AddSlashes(pg_result($resaco,0,'j29_final'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,17,76,'','".AddSlashes(pg_result($resaco,0,'j29_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j29_codigo=null,$j29_inicio=null) { 
      $this->atualizacampos();
     $sql = " update ruascep set ";
     $virgula = "";
     if(trim($this->j29_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j29_codigo"])){ 
       $sql  .= $virgula." j29_codigo = $this->j29_codigo ";
       $virgula = ",";
       if(trim($this->j29_codigo) == null ){ 
         $this->erro_sql = " Campo Rua nao Informado.";
         $this->erro_campo = "j29_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j29_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j29_inicio"])){ 
       $sql  .= $virgula." j29_inicio = $this->j29_inicio ";
       $virgula = ",";
       if(trim($this->j29_inicio) == null ){ 
         $this->erro_sql = " Campo Numero Inicial nao Informado.";
         $this->erro_campo = "j29_inicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j29_final)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j29_final"])){ 
       $sql  .= $virgula." j29_final = $this->j29_final ";
       $virgula = ",";
       if(trim($this->j29_final) == null ){ 
         $this->erro_sql = " Campo Numeracao Final nao Informado.";
         $this->erro_campo = "j29_final";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j29_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j29_cep"])){ 
       $sql  .= $virgula." j29_cep = '$this->j29_cep' ";
       $virgula = ",";
       if(trim($this->j29_cep) == null ){ 
         $this->erro_sql = " Campo CEP nao Informado.";
         $this->erro_campo = "j29_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j29_codigo!=null){
       $sql .= " j29_codigo = $this->j29_codigo";
     }
     if($j29_inicio!=null){
       $sql .= " and  j29_inicio = $this->j29_inicio";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j29_codigo,$this->j29_inicio));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,73,'$this->j29_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,74,'$this->j29_inicio','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j29_codigo"]))
           $resac = db_query("insert into db_acount values($acount,17,73,'".AddSlashes(pg_result($resaco,$conresaco,'j29_codigo'))."','$this->j29_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j29_inicio"]))
           $resac = db_query("insert into db_acount values($acount,17,74,'".AddSlashes(pg_result($resaco,$conresaco,'j29_inicio'))."','$this->j29_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j29_final"]))
           $resac = db_query("insert into db_acount values($acount,17,75,'".AddSlashes(pg_result($resaco,$conresaco,'j29_final'))."','$this->j29_final',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j29_cep"]))
           $resac = db_query("insert into db_acount values($acount,17,76,'".AddSlashes(pg_result($resaco,$conresaco,'j29_cep'))."','$this->j29_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j29_codigo."-".$this->j29_inicio;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j29_codigo."-".$this->j29_inicio;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j29_codigo."-".$this->j29_inicio;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j29_codigo=null,$j29_inicio=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j29_codigo,$j29_inicio));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,73,'$j29_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,74,'$j29_inicio','E')");
         $resac = db_query("insert into db_acount values($acount,17,73,'','".AddSlashes(pg_result($resaco,$iresaco,'j29_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,17,74,'','".AddSlashes(pg_result($resaco,$iresaco,'j29_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,17,75,'','".AddSlashes(pg_result($resaco,$iresaco,'j29_final'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,17,76,'','".AddSlashes(pg_result($resaco,$iresaco,'j29_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ruascep
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j29_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j29_codigo = $j29_codigo ";
        }
        if($j29_inicio != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j29_inicio = $j29_inicio ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j29_codigo."-".$j29_inicio;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j29_codigo."-".$j29_inicio;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j29_codigo."-".$j29_inicio;
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
        $this->erro_sql   = "Record Vazio na Tabela:ruascep";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j29_codigo=null,$j29_inicio=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ruascep ";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = ruascep.j29_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($j29_codigo!=null ){
         $sql2 .= " where ruascep.j29_codigo = $j29_codigo "; 
       } 
       if($j29_inicio!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " ruascep.j29_inicio = $j29_inicio "; 
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
   function sql_query_file ( $j29_codigo=null,$j29_inicio=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ruascep ";
     $sql2 = "";
     if($dbwhere==""){
       if($j29_codigo!=null ){
         $sql2 .= " where ruascep.j29_codigo = $j29_codigo "; 
       } 
       if($j29_inicio!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " ruascep.j29_inicio = $j29_inicio "; 
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