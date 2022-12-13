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
//CLASSE DA ENTIDADE matestoqueinimeimri
class cl_matestoqueinimeimri { 
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
   var $m48_codigo = 0; 
   var $m48_codmatrequiitem = 0; 
   var $m48_codmatestoqueinimei = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m48_codigo = int8 = Sequencial 
                 m48_codmatrequiitem = int8 = Codigo Item Requisição 
                 m48_codmatestoqueinimei = int8 = Código matestoqueinimei 
                 ";
   //funcao construtor da classe 
   function cl_matestoqueinimeimri() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoqueinimeimri"); 
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
       $this->m48_codigo = ($this->m48_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m48_codigo"]:$this->m48_codigo);
       $this->m48_codmatrequiitem = ($this->m48_codmatrequiitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m48_codmatrequiitem"]:$this->m48_codmatrequiitem);
       $this->m48_codmatestoqueinimei = ($this->m48_codmatestoqueinimei == ""?@$GLOBALS["HTTP_POST_VARS"]["m48_codmatestoqueinimei"]:$this->m48_codmatestoqueinimei);
     }else{
       $this->m48_codigo = ($this->m48_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m48_codigo"]:$this->m48_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($m48_codigo){ 
      $this->atualizacampos();
     if($this->m48_codmatrequiitem == null ){ 
       $this->erro_sql = " Campo Codigo Item Requisição nao Informado.";
       $this->erro_campo = "m48_codmatrequiitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m48_codmatestoqueinimei == null ){ 
       $this->erro_sql = " Campo Código matestoqueinimei nao Informado.";
       $this->erro_campo = "m48_codmatestoqueinimei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m48_codigo == "" || $m48_codigo == null ){
       $result = db_query("select nextval('matestoqueinimeimri_m48_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matestoqueinimeimri_m48_codigo_seq do campo: m48_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m48_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matestoqueinimeimri_m48_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m48_codigo)){
         $this->erro_sql = " Campo m48_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m48_codigo = $m48_codigo; 
       }
     }
     if(($this->m48_codigo == null) || ($this->m48_codigo == "") ){ 
       $this->erro_sql = " Campo m48_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoqueinimeimri(
                                       m48_codigo 
                                      ,m48_codmatrequiitem 
                                      ,m48_codmatestoqueinimei 
                       )
                values (
                                $this->m48_codigo 
                               ,$this->m48_codmatrequiitem 
                               ,$this->m48_codmatestoqueinimei 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Liga tabela matestoqeinimei a matrequiitem ($this->m48_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Liga tabela matestoqeinimei a matrequiitem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Liga tabela matestoqeinimei a matrequiitem ($this->m48_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m48_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m48_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6951,'$this->m48_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1149,6951,'','".AddSlashes(pg_result($resaco,0,'m48_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1149,6952,'','".AddSlashes(pg_result($resaco,0,'m48_codmatrequiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1149,6953,'','".AddSlashes(pg_result($resaco,0,'m48_codmatestoqueinimei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m48_codigo=null) { 
      $this->atualizacampos();
     $sql = " update matestoqueinimeimri set ";
     $virgula = "";
     if(trim($this->m48_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m48_codigo"])){ 
       $sql  .= $virgula." m48_codigo = $this->m48_codigo ";
       $virgula = ",";
       if(trim($this->m48_codigo) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "m48_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m48_codmatrequiitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m48_codmatrequiitem"])){ 
       $sql  .= $virgula." m48_codmatrequiitem = $this->m48_codmatrequiitem ";
       $virgula = ",";
       if(trim($this->m48_codmatrequiitem) == null ){ 
         $this->erro_sql = " Campo Codigo Item Requisição nao Informado.";
         $this->erro_campo = "m48_codmatrequiitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m48_codmatestoqueinimei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m48_codmatestoqueinimei"])){ 
       $sql  .= $virgula." m48_codmatestoqueinimei = $this->m48_codmatestoqueinimei ";
       $virgula = ",";
       if(trim($this->m48_codmatestoqueinimei) == null ){ 
         $this->erro_sql = " Campo Código matestoqueinimei nao Informado.";
         $this->erro_campo = "m48_codmatestoqueinimei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m48_codigo!=null){
       $sql .= " m48_codigo = $this->m48_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m48_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6951,'$this->m48_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m48_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1149,6951,'".AddSlashes(pg_result($resaco,$conresaco,'m48_codigo'))."','$this->m48_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m48_codmatrequiitem"]))
           $resac = db_query("insert into db_acount values($acount,1149,6952,'".AddSlashes(pg_result($resaco,$conresaco,'m48_codmatrequiitem'))."','$this->m48_codmatrequiitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m48_codmatestoqueinimei"]))
           $resac = db_query("insert into db_acount values($acount,1149,6953,'".AddSlashes(pg_result($resaco,$conresaco,'m48_codmatestoqueinimei'))."','$this->m48_codmatestoqueinimei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Liga tabela matestoqeinimei a matrequiitem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m48_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Liga tabela matestoqeinimei a matrequiitem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m48_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m48_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m48_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m48_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6951,'$m48_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1149,6951,'','".AddSlashes(pg_result($resaco,$iresaco,'m48_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1149,6952,'','".AddSlashes(pg_result($resaco,$iresaco,'m48_codmatrequiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1149,6953,'','".AddSlashes(pg_result($resaco,$iresaco,'m48_codmatestoqueinimei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoqueinimeimri
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m48_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m48_codigo = $m48_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Liga tabela matestoqeinimei a matrequiitem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m48_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Liga tabela matestoqeinimei a matrequiitem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m48_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m48_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoqueinimeimri";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m48_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueinimeimri ";
     $sql .= "      inner join matrequiitem  on  matrequiitem.m41_codigo = matestoqueinimeimri.m48_codmatrequiitem";
     $sql .= "      inner join matestoqueinimei  on  matestoqueinimei.m82_codigo = matestoqueinimeimri.m48_codmatestoqueinimei";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
     $sql .= "      inner join matrequi  on  matrequi.m40_codigo = matrequiitem.m41_codmatrequi";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem";
     $sql .= "      inner join matestoqueini  as a on   a.m80_codigo = matestoqueinimei.m82_matestoqueini";
     $sql2 = "";
     if($dbwhere==""){
       if($m48_codigo!=null ){
         $sql2 .= " where matestoqueinimeimri.m48_codigo = $m48_codigo "; 
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
   function sql_query_file ( $m48_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueinimeimri ";
     $sql2 = "";
     if($dbwhere==""){
       if($m48_codigo!=null ){
         $sql2 .= " where matestoqueinimeimri.m48_codigo = $m48_codigo "; 
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