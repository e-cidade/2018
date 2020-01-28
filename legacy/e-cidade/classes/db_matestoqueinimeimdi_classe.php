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
//CLASSE DA ENTIDADE matestoqueinimeimdi
class cl_matestoqueinimeimdi { 
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
   var $m50_codigo = 0; 
   var $m50_codmatestoquedevitem = 0; 
   var $m50_codmatestoqueinimei = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m50_codigo = int8 = Sequencial 
                 m50_codmatestoquedevitem = int4 = Código item da devolução 
                 m50_codmatestoqueinimei = int8 = Código matestoqueinimei 
                 ";
   //funcao construtor da classe 
   function cl_matestoqueinimeimdi() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoqueinimeimdi"); 
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
       $this->m50_codigo = ($this->m50_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m50_codigo"]:$this->m50_codigo);
       $this->m50_codmatestoquedevitem = ($this->m50_codmatestoquedevitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m50_codmatestoquedevitem"]:$this->m50_codmatestoquedevitem);
       $this->m50_codmatestoqueinimei = ($this->m50_codmatestoqueinimei == ""?@$GLOBALS["HTTP_POST_VARS"]["m50_codmatestoqueinimei"]:$this->m50_codmatestoqueinimei);
     }else{
       $this->m50_codigo = ($this->m50_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m50_codigo"]:$this->m50_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($m50_codigo){ 
      $this->atualizacampos();
     if($this->m50_codmatestoquedevitem == null ){ 
       $this->erro_sql = " Campo Código item da devolução nao Informado.";
       $this->erro_campo = "m50_codmatestoquedevitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m50_codmatestoqueinimei == null ){ 
       $this->erro_sql = " Campo Código matestoqueinimei nao Informado.";
       $this->erro_campo = "m50_codmatestoqueinimei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m50_codigo == "" || $m50_codigo == null ){
       $result = db_query("select nextval('matestoqueinimeimdi_m50_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matestoqueinimeimdi_m50_codigo_seq do campo: m50_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m50_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matestoqueinimeimdi_m50_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m50_codigo)){
         $this->erro_sql = " Campo m50_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m50_codigo = $m50_codigo; 
       }
     }
     if(($this->m50_codigo == null) || ($this->m50_codigo == "") ){ 
       $this->erro_sql = " Campo m50_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoqueinimeimdi(
                                       m50_codigo 
                                      ,m50_codmatestoquedevitem 
                                      ,m50_codmatestoqueinimei 
                       )
                values (
                                $this->m50_codigo 
                               ,$this->m50_codmatestoquedevitem 
                               ,$this->m50_codmatestoqueinimei 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Liga tabela matestoqeini a matestoquedevitem ($this->m50_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Liga tabela matestoqeini a matestoquedevitem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Liga tabela matestoqeini a matestoquedevitem ($this->m50_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m50_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m50_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6957,'$this->m50_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1151,6957,'','".AddSlashes(pg_result($resaco,0,'m50_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1151,6958,'','".AddSlashes(pg_result($resaco,0,'m50_codmatestoquedevitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1151,6959,'','".AddSlashes(pg_result($resaco,0,'m50_codmatestoqueinimei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m50_codigo=null) { 
      $this->atualizacampos();
     $sql = " update matestoqueinimeimdi set ";
     $virgula = "";
     if(trim($this->m50_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m50_codigo"])){ 
       $sql  .= $virgula." m50_codigo = $this->m50_codigo ";
       $virgula = ",";
       if(trim($this->m50_codigo) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "m50_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m50_codmatestoquedevitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m50_codmatestoquedevitem"])){ 
       $sql  .= $virgula." m50_codmatestoquedevitem = $this->m50_codmatestoquedevitem ";
       $virgula = ",";
       if(trim($this->m50_codmatestoquedevitem) == null ){ 
         $this->erro_sql = " Campo Código item da devolução nao Informado.";
         $this->erro_campo = "m50_codmatestoquedevitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m50_codmatestoqueinimei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m50_codmatestoqueinimei"])){ 
       $sql  .= $virgula." m50_codmatestoqueinimei = $this->m50_codmatestoqueinimei ";
       $virgula = ",";
       if(trim($this->m50_codmatestoqueinimei) == null ){ 
         $this->erro_sql = " Campo Código matestoqueinimei nao Informado.";
         $this->erro_campo = "m50_codmatestoqueinimei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m50_codigo!=null){
       $sql .= " m50_codigo = $this->m50_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m50_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6957,'$this->m50_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m50_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1151,6957,'".AddSlashes(pg_result($resaco,$conresaco,'m50_codigo'))."','$this->m50_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m50_codmatestoquedevitem"]))
           $resac = db_query("insert into db_acount values($acount,1151,6958,'".AddSlashes(pg_result($resaco,$conresaco,'m50_codmatestoquedevitem'))."','$this->m50_codmatestoquedevitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m50_codmatestoqueinimei"]))
           $resac = db_query("insert into db_acount values($acount,1151,6959,'".AddSlashes(pg_result($resaco,$conresaco,'m50_codmatestoqueinimei'))."','$this->m50_codmatestoqueinimei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Liga tabela matestoqeini a matestoquedevitem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m50_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Liga tabela matestoqeini a matestoquedevitem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m50_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m50_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m50_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m50_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6957,'$m50_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1151,6957,'','".AddSlashes(pg_result($resaco,$iresaco,'m50_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1151,6958,'','".AddSlashes(pg_result($resaco,$iresaco,'m50_codmatestoquedevitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1151,6959,'','".AddSlashes(pg_result($resaco,$iresaco,'m50_codmatestoqueinimei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoqueinimeimdi
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m50_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m50_codigo = $m50_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Liga tabela matestoqeini a matestoquedevitem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m50_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Liga tabela matestoqeini a matestoquedevitem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m50_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m50_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoqueinimeimdi";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m50_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueinimeimdi ";
     $sql .= "      inner join matestoqueinimei  on  matestoqueinimei.m82_codigo = matestoqueinimeimdi.m50_codmatestoqueinimei";
     $sql .= "      inner join matestoquedevitem  on  matestoquedevitem.m46_codigo = matestoqueinimeimdi.m50_codmatestoquedevitem";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem";
     $sql .= "      inner join matestoqueini  on  matestoqueini.m80_codigo = matestoqueinimei.m82_matestoqueini";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matestoquedevitem.m46_codmatmater";
     $sql .= "      inner join matrequiitem  on  matrequiitem.m41_codigo = matestoquedevitem.m46_codmatrequiitem";
     $sql .= "      inner join atendrequiitem  on  atendrequiitem.m43_codigo = matestoquedevitem.m46_codatendrequiitem";
     $sql .= "      inner join matestoquedev  as a on   a.m45_codigo = matestoquedevitem.m46_codmatestoquedev";
     $sql2 = "";
     if($dbwhere==""){
       if($m50_codigo!=null ){
         $sql2 .= " where matestoqueinimeimdi.m50_codigo = $m50_codigo "; 
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
   function sql_query_file ( $m50_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueinimeimdi ";
     $sql2 = "";
     if($dbwhere==""){
       if($m50_codigo!=null ){
         $sql2 .= " where matestoqueinimeimdi.m50_codigo = $m50_codigo "; 
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