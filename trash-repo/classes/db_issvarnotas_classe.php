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

//MODULO: issqn
//CLASSE DA ENTIDADE issvarnotas
class cl_issvarnotas { 
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
   var $q06_codigo = 0; 
   var $q06_seq = 0; 
   var $q06_nota = null; 
   var $q06_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q06_codigo = int8 = Código 
                 q06_seq = int4 = Sequencia 
                 q06_nota = varchar(100) = Número da nota 
                 q06_valor = float8 = Valor da nota 
                 ";
   //funcao construtor da classe 
   function cl_issvarnotas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issvarnotas"); 
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
       $this->q06_codigo = ($this->q06_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q06_codigo"]:$this->q06_codigo);
       $this->q06_seq = ($this->q06_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["q06_seq"]:$this->q06_seq);
       $this->q06_nota = ($this->q06_nota == ""?@$GLOBALS["HTTP_POST_VARS"]["q06_nota"]:$this->q06_nota);
       $this->q06_valor = ($this->q06_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["q06_valor"]:$this->q06_valor);
     }else{
       $this->q06_codigo = ($this->q06_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q06_codigo"]:$this->q06_codigo);
       $this->q06_seq = ($this->q06_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["q06_seq"]:$this->q06_seq);
     }
   }
   // funcao para inclusao
   function incluir ($q06_codigo,$q06_seq){ 
      $this->atualizacampos();
     if($this->q06_valor == null ){ 
       $this->erro_sql = " Campo Valor da nota nao Informado.";
       $this->erro_campo = "q06_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->q06_codigo = $q06_codigo; 
       $this->q06_seq = $q06_seq; 
     if(($this->q06_codigo == null) || ($this->q06_codigo == "") ){ 
       $this->erro_sql = " Campo q06_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q06_seq == null) || ($this->q06_seq == "") ){ 
       $this->erro_sql = " Campo q06_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issvarnotas(
                                       q06_codigo 
                                      ,q06_seq 
                                      ,q06_nota 
                                      ,q06_valor 
                       )
                values (
                                $this->q06_codigo 
                               ,$this->q06_seq 
                               ,'$this->q06_nota' 
                               ,$this->q06_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Notas do issqn complementar ($this->q06_codigo."-".$this->q06_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Notas do issqn complementar já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Notas do issqn complementar ($this->q06_codigo."-".$this->q06_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q06_codigo."-".$this->q06_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q06_codigo,$this->q06_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4852,'$this->q06_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,4855,'$this->q06_seq','I')");
       $resac = db_query("insert into db_acount values($acount,655,4852,'','".AddSlashes(pg_result($resaco,0,'q06_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,655,4855,'','".AddSlashes(pg_result($resaco,0,'q06_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,655,4853,'','".AddSlashes(pg_result($resaco,0,'q06_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,655,4854,'','".AddSlashes(pg_result($resaco,0,'q06_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q06_codigo=null,$q06_seq=null) { 
      $this->atualizacampos();
     $sql = " update issvarnotas set ";
     $virgula = "";
     if(trim($this->q06_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q06_codigo"])){ 
       $sql  .= $virgula." q06_codigo = $this->q06_codigo ";
       $virgula = ",";
       if(trim($this->q06_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "q06_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q06_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q06_seq"])){ 
       $sql  .= $virgula." q06_seq = $this->q06_seq ";
       $virgula = ",";
       if(trim($this->q06_seq) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "q06_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q06_nota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q06_nota"])){ 
       $sql  .= $virgula." q06_nota = '$this->q06_nota' ";
       $virgula = ",";
     }
     if(trim($this->q06_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q06_valor"])){ 
       $sql  .= $virgula." q06_valor = $this->q06_valor ";
       $virgula = ",";
       if(trim($this->q06_valor) == null ){ 
         $this->erro_sql = " Campo Valor da nota nao Informado.";
         $this->erro_campo = "q06_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q06_codigo!=null){
       $sql .= " q06_codigo = $this->q06_codigo";
     }
     if($q06_seq!=null){
       $sql .= " and  q06_seq = $this->q06_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q06_codigo,$this->q06_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4852,'$this->q06_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,4855,'$this->q06_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q06_codigo"]))
           $resac = db_query("insert into db_acount values($acount,655,4852,'".AddSlashes(pg_result($resaco,$conresaco,'q06_codigo'))."','$this->q06_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q06_seq"]))
           $resac = db_query("insert into db_acount values($acount,655,4855,'".AddSlashes(pg_result($resaco,$conresaco,'q06_seq'))."','$this->q06_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q06_nota"]))
           $resac = db_query("insert into db_acount values($acount,655,4853,'".AddSlashes(pg_result($resaco,$conresaco,'q06_nota'))."','$this->q06_nota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q06_valor"]))
           $resac = db_query("insert into db_acount values($acount,655,4854,'".AddSlashes(pg_result($resaco,$conresaco,'q06_valor'))."','$this->q06_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Notas do issqn complementar nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q06_codigo."-".$this->q06_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Notas do issqn complementar nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q06_codigo."-".$this->q06_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q06_codigo."-".$this->q06_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q06_codigo=null,$q06_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q06_codigo,$q06_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4852,'$q06_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,4855,'$q06_seq','E')");
         $resac = db_query("insert into db_acount values($acount,655,4852,'','".AddSlashes(pg_result($resaco,$iresaco,'q06_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,655,4855,'','".AddSlashes(pg_result($resaco,$iresaco,'q06_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,655,4853,'','".AddSlashes(pg_result($resaco,$iresaco,'q06_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,655,4854,'','".AddSlashes(pg_result($resaco,$iresaco,'q06_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issvarnotas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q06_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q06_codigo = $q06_codigo ";
        }
        if($q06_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q06_seq = $q06_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Notas do issqn complementar nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q06_codigo."-".$q06_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Notas do issqn complementar nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q06_codigo."-".$q06_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q06_codigo."-".$q06_seq;
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
        $this->erro_sql   = "Record Vazio na Tabela:issvarnotas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q06_codigo=null,$q06_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issvarnotas ";
     $sql .= "      inner join issvar  on  issvar.q05_codigo = issvarnotas.q06_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($q06_codigo!=null ){
         $sql2 .= " where issvarnotas.q06_codigo = $q06_codigo "; 
       } 
       if($q06_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " issvarnotas.q06_seq = $q06_seq "; 
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
   function sql_query_file ( $q06_codigo=null,$q06_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issvarnotas ";
     $sql2 = "";
     if($dbwhere==""){
       if($q06_codigo!=null ){
         $sql2 .= " where issvarnotas.q06_codigo = $q06_codigo "; 
       } 
       if($q06_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " issvarnotas.q06_seq = $q06_seq "; 
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