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
//CLASSE DA ENTIDADE matestoquedevitemmei
class cl_matestoquedevitemmei { 
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
   var $m47_codigo = 0; 
   var $m47_codmatestoquedevitem = 0; 
   var $m47_codmatestoqueitem = 0; 
   var $m47_quantdev = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m47_codigo = int8 = Código da Devolução Item 
                 m47_codmatestoquedevitem = int4 = Código 
                 m47_codmatestoqueitem = int8 = Código sequencial do lançamento 
                 m47_quantdev = float8 = Quant. Devolvida 
                 ";
   //funcao construtor da classe 
   function cl_matestoquedevitemmei() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoquedevitemmei"); 
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
       $this->m47_codigo = ($this->m47_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m47_codigo"]:$this->m47_codigo);
       $this->m47_codmatestoquedevitem = ($this->m47_codmatestoquedevitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m47_codmatestoquedevitem"]:$this->m47_codmatestoquedevitem);
       $this->m47_codmatestoqueitem = ($this->m47_codmatestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m47_codmatestoqueitem"]:$this->m47_codmatestoqueitem);
       $this->m47_quantdev = ($this->m47_quantdev == ""?@$GLOBALS["HTTP_POST_VARS"]["m47_quantdev"]:$this->m47_quantdev);
     }else{
       $this->m47_codigo = ($this->m47_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m47_codigo"]:$this->m47_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($m47_codigo){ 
      $this->atualizacampos();
     if($this->m47_codmatestoquedevitem == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "m47_codmatestoquedevitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m47_codmatestoqueitem == null ){ 
       $this->erro_sql = " Campo Código sequencial do lançamento nao Informado.";
       $this->erro_campo = "m47_codmatestoqueitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m47_quantdev == null ){ 
       $this->erro_sql = " Campo Quant. Devolvida nao Informado.";
       $this->erro_campo = "m47_quantdev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m47_codigo == "" || $m47_codigo == null ){
       $result = db_query("select nextval('matestoquedevitemmei_m47_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matestoquedevitemmei_m47_codigo_seq do campo: m47_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m47_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matestoquedevitemmei_m47_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m47_codigo)){
         $this->erro_sql = " Campo m47_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m47_codigo = $m47_codigo; 
       }
     }
     if(($this->m47_codigo == null) || ($this->m47_codigo == "") ){ 
       $this->erro_sql = " Campo m47_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoquedevitemmei(
                                       m47_codigo 
                                      ,m47_codmatestoquedevitem 
                                      ,m47_codmatestoqueitem 
                                      ,m47_quantdev 
                       )
                values (
                                $this->m47_codigo 
                               ,$this->m47_codmatestoquedevitem 
                               ,$this->m47_codmatestoqueitem 
                               ,$this->m47_quantdev 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matestoquedevitemmei ($this->m47_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matestoquedevitemmei já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matestoquedevitemmei ($this->m47_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m47_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m47_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6917,'$this->m47_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1138,6917,'','".AddSlashes(pg_result($resaco,0,'m47_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1138,6920,'','".AddSlashes(pg_result($resaco,0,'m47_codmatestoquedevitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1138,6919,'','".AddSlashes(pg_result($resaco,0,'m47_codmatestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1138,6918,'','".AddSlashes(pg_result($resaco,0,'m47_quantdev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m47_codigo=null) { 
      $this->atualizacampos();
     $sql = " update matestoquedevitemmei set ";
     $virgula = "";
     if(trim($this->m47_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m47_codigo"])){ 
       $sql  .= $virgula." m47_codigo = $this->m47_codigo ";
       $virgula = ",";
       if(trim($this->m47_codigo) == null ){ 
         $this->erro_sql = " Campo Código da Devolução Item nao Informado.";
         $this->erro_campo = "m47_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m47_codmatestoquedevitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m47_codmatestoquedevitem"])){ 
       $sql  .= $virgula." m47_codmatestoquedevitem = $this->m47_codmatestoquedevitem ";
       $virgula = ",";
       if(trim($this->m47_codmatestoquedevitem) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "m47_codmatestoquedevitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m47_codmatestoqueitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m47_codmatestoqueitem"])){ 
       $sql  .= $virgula." m47_codmatestoqueitem = $this->m47_codmatestoqueitem ";
       $virgula = ",";
       if(trim($this->m47_codmatestoqueitem) == null ){ 
         $this->erro_sql = " Campo Código sequencial do lançamento nao Informado.";
         $this->erro_campo = "m47_codmatestoqueitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m47_quantdev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m47_quantdev"])){ 
       $sql  .= $virgula." m47_quantdev = $this->m47_quantdev ";
       $virgula = ",";
       if(trim($this->m47_quantdev) == null ){ 
         $this->erro_sql = " Campo Quant. Devolvida nao Informado.";
         $this->erro_campo = "m47_quantdev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m47_codigo!=null){
       $sql .= " m47_codigo = $this->m47_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m47_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6917,'$this->m47_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m47_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1138,6917,'".AddSlashes(pg_result($resaco,$conresaco,'m47_codigo'))."','$this->m47_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m47_codmatestoquedevitem"]))
           $resac = db_query("insert into db_acount values($acount,1138,6920,'".AddSlashes(pg_result($resaco,$conresaco,'m47_codmatestoquedevitem'))."','$this->m47_codmatestoquedevitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m47_codmatestoqueitem"]))
           $resac = db_query("insert into db_acount values($acount,1138,6919,'".AddSlashes(pg_result($resaco,$conresaco,'m47_codmatestoqueitem'))."','$this->m47_codmatestoqueitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m47_quantdev"]))
           $resac = db_query("insert into db_acount values($acount,1138,6918,'".AddSlashes(pg_result($resaco,$conresaco,'m47_quantdev'))."','$this->m47_quantdev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matestoquedevitemmei nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m47_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matestoquedevitemmei nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m47_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m47_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m47_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m47_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6917,'$m47_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1138,6917,'','".AddSlashes(pg_result($resaco,$iresaco,'m47_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1138,6920,'','".AddSlashes(pg_result($resaco,$iresaco,'m47_codmatestoquedevitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1138,6919,'','".AddSlashes(pg_result($resaco,$iresaco,'m47_codmatestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1138,6918,'','".AddSlashes(pg_result($resaco,$iresaco,'m47_quantdev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoquedevitemmei
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m47_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m47_codigo = $m47_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matestoquedevitemmei nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m47_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matestoquedevitemmei nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m47_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m47_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoquedevitemmei";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m47_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoquedevitemmei ";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoquedevitemmei.m47_codmatestoqueitem";
     $sql .= "      inner join matestoquedevitem  on  matestoquedevitem.m46_codigo = matestoquedevitemmei.m47_codmatestoquedevitem";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join matrequiitem  on  matrequiitem.m41_codigo = matestoquedevitem.m46_codmatrequiitem";
     $sql .= "      inner join matestoquedev  as a on   a.m45_codigo = matestoquedevitem.m46_codmatestoquedev";
     $sql2 = "";
     if($dbwhere==""){
       if($m47_codigo!=null ){
         $sql2 .= " where matestoquedevitemmei.m47_codigo = $m47_codigo "; 
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
   function sql_query_file ( $m47_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoquedevitemmei ";
     $sql2 = "";
     if($dbwhere==""){
       if($m47_codigo!=null ){
         $sql2 .= " where matestoquedevitemmei.m47_codigo = $m47_codigo "; 
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