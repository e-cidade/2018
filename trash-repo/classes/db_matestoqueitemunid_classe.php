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

//MODULO: material
//CLASSE DA ENTIDADE matestoqueitemunid
class cl_matestoqueitemunid { 
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
   var $m75_codmatestoqueitem = 0; 
   var $m75_codmatunid = 0; 
   var $m75_quant = 0; 
   var $m75_quantmult = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m75_codmatestoqueitem = int8 = Código do Lançamento 
                 m75_codmatunid = int8 = Código da Unidade 
                 m75_quant = float8 = Quantidade 
                 m75_quantmult = float8 = Quant. Multiplicadora 
                 ";
   //funcao construtor da classe 
   function cl_matestoqueitemunid() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoqueitemunid"); 
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
       $this->m75_codmatestoqueitem = ($this->m75_codmatestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m75_codmatestoqueitem"]:$this->m75_codmatestoqueitem);
       $this->m75_codmatunid = ($this->m75_codmatunid == ""?@$GLOBALS["HTTP_POST_VARS"]["m75_codmatunid"]:$this->m75_codmatunid);
       $this->m75_quant = ($this->m75_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["m75_quant"]:$this->m75_quant);
       $this->m75_quantmult = ($this->m75_quantmult == ""?@$GLOBALS["HTTP_POST_VARS"]["m75_quantmult"]:$this->m75_quantmult);
     }else{
       $this->m75_codmatestoqueitem = ($this->m75_codmatestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m75_codmatestoqueitem"]:$this->m75_codmatestoqueitem);
     }
   }
   // funcao para inclusao
   function incluir ($m75_codmatestoqueitem){ 
      $this->atualizacampos();
     if($this->m75_codmatunid == null ){ 
       $this->erro_sql = " Campo Código da Unidade nao Informado.";
       $this->erro_campo = "m75_codmatunid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m75_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "m75_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m75_quantmult == null ){ 
       $this->erro_sql = " Campo Quant. Multiplicadora nao Informado.";
       $this->erro_campo = "m75_quantmult";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->m75_codmatestoqueitem = $m75_codmatestoqueitem; 
     if(($this->m75_codmatestoqueitem == null) || ($this->m75_codmatestoqueitem == "") ){ 
       $this->erro_sql = " Campo m75_codmatestoqueitem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoqueitemunid(
                                       m75_codmatestoqueitem 
                                      ,m75_codmatunid 
                                      ,m75_quant 
                                      ,m75_quantmult 
                       )
                values (
                                $this->m75_codmatestoqueitem 
                               ,$this->m75_codmatunid 
                               ,$this->m75_quant 
                               ,$this->m75_quantmult 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Guarda a unidade de entrada do item  ($this->m75_codmatestoqueitem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Guarda a unidade de entrada do item  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Guarda a unidade de entrada do item  ($this->m75_codmatestoqueitem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m75_codmatestoqueitem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m75_codmatestoqueitem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6960,'$this->m75_codmatestoqueitem','I')");
       $resac = db_query("insert into db_acount values($acount,1152,6960,'','".AddSlashes(pg_result($resaco,0,'m75_codmatestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1152,6961,'','".AddSlashes(pg_result($resaco,0,'m75_codmatunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1152,6962,'','".AddSlashes(pg_result($resaco,0,'m75_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1152,6963,'','".AddSlashes(pg_result($resaco,0,'m75_quantmult'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m75_codmatestoqueitem=null) { 
      $this->atualizacampos();
     $sql = " update matestoqueitemunid set ";
     $virgula = "";
     if(trim($this->m75_codmatestoqueitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m75_codmatestoqueitem"])){ 
       $sql  .= $virgula." m75_codmatestoqueitem = $this->m75_codmatestoqueitem ";
       $virgula = ",";
       if(trim($this->m75_codmatestoqueitem) == null ){ 
         $this->erro_sql = " Campo Código do Lançamento nao Informado.";
         $this->erro_campo = "m75_codmatestoqueitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m75_codmatunid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m75_codmatunid"])){ 
       $sql  .= $virgula." m75_codmatunid = $this->m75_codmatunid ";
       $virgula = ",";
       if(trim($this->m75_codmatunid) == null ){ 
         $this->erro_sql = " Campo Código da Unidade nao Informado.";
         $this->erro_campo = "m75_codmatunid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m75_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m75_quant"])){ 
       $sql  .= $virgula." m75_quant = $this->m75_quant ";
       $virgula = ",";
       if(trim($this->m75_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "m75_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m75_quantmult)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m75_quantmult"])){ 
       $sql  .= $virgula." m75_quantmult = $this->m75_quantmult ";
       $virgula = ",";
       if(trim($this->m75_quantmult) == null ){ 
         $this->erro_sql = " Campo Quant. Multiplicadora nao Informado.";
         $this->erro_campo = "m75_quantmult";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m75_codmatestoqueitem!=null){
       $sql .= " m75_codmatestoqueitem = $this->m75_codmatestoqueitem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m75_codmatestoqueitem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6960,'$this->m75_codmatestoqueitem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m75_codmatestoqueitem"]))
           $resac = db_query("insert into db_acount values($acount,1152,6960,'".AddSlashes(pg_result($resaco,$conresaco,'m75_codmatestoqueitem'))."','$this->m75_codmatestoqueitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m75_codmatunid"]))
           $resac = db_query("insert into db_acount values($acount,1152,6961,'".AddSlashes(pg_result($resaco,$conresaco,'m75_codmatunid'))."','$this->m75_codmatunid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m75_quant"]))
           $resac = db_query("insert into db_acount values($acount,1152,6962,'".AddSlashes(pg_result($resaco,$conresaco,'m75_quant'))."','$this->m75_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m75_quantmult"]))
           $resac = db_query("insert into db_acount values($acount,1152,6963,'".AddSlashes(pg_result($resaco,$conresaco,'m75_quantmult'))."','$this->m75_quantmult',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Guarda a unidade de entrada do item  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m75_codmatestoqueitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Guarda a unidade de entrada do item  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m75_codmatestoqueitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m75_codmatestoqueitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m75_codmatestoqueitem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m75_codmatestoqueitem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6960,'$m75_codmatestoqueitem','E')");
         $resac = db_query("insert into db_acount values($acount,1152,6960,'','".AddSlashes(pg_result($resaco,$iresaco,'m75_codmatestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1152,6961,'','".AddSlashes(pg_result($resaco,$iresaco,'m75_codmatunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1152,6962,'','".AddSlashes(pg_result($resaco,$iresaco,'m75_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1152,6963,'','".AddSlashes(pg_result($resaco,$iresaco,'m75_quantmult'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoqueitemunid
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m75_codmatestoqueitem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m75_codmatestoqueitem = $m75_codmatestoqueitem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Guarda a unidade de entrada do item  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m75_codmatestoqueitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Guarda a unidade de entrada do item  nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m75_codmatestoqueitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m75_codmatestoqueitem;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoqueitemunid";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m75_codmatestoqueitem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitemunid ";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matestoqueitemunid.m75_codmatunid";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueitemunid.m75_codmatestoqueitem";
     $sql .= "      inner join matestoque  as a on   a.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql2 = "";
     if($dbwhere==""){
       if($m75_codmatestoqueitem!=null ){
         $sql2 .= " where matestoqueitemunid.m75_codmatestoqueitem = $m75_codmatestoqueitem "; 
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
   function sql_query_file ( $m75_codmatestoqueitem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitemunid ";
     $sql2 = "";
     if($dbwhere==""){
       if($m75_codmatestoqueitem!=null ){
         $sql2 .= " where matestoqueitemunid.m75_codmatestoqueitem = $m75_codmatestoqueitem "; 
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