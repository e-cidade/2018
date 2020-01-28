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
//CLASSE DA ENTIDADE atendrequiitem
class cl_atendrequiitem { 
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
   var $m43_codigo = 0; 
   var $m43_codatendrequi = 0; 
   var $m43_codmatrequiitem = 0; 
   var $m43_quantatend = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m43_codigo = int8 = Código 
                 m43_codatendrequi = int8 = Código 
                 m43_codmatrequiitem = int8 = Codigo Sequencial 
                 m43_quantatend = float8 = Quant. Atendida 
                 ";
   //funcao construtor da classe 
   function cl_atendrequiitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atendrequiitem"); 
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
       $this->m43_codigo = ($this->m43_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m43_codigo"]:$this->m43_codigo);
       $this->m43_codatendrequi = ($this->m43_codatendrequi == ""?@$GLOBALS["HTTP_POST_VARS"]["m43_codatendrequi"]:$this->m43_codatendrequi);
       $this->m43_codmatrequiitem = ($this->m43_codmatrequiitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m43_codmatrequiitem"]:$this->m43_codmatrequiitem);
       $this->m43_quantatend = ($this->m43_quantatend == ""?@$GLOBALS["HTTP_POST_VARS"]["m43_quantatend"]:$this->m43_quantatend);
     }else{
       $this->m43_codigo = ($this->m43_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m43_codigo"]:$this->m43_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($m43_codigo){ 
      $this->atualizacampos();
     if($this->m43_codatendrequi == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "m43_codatendrequi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m43_codmatrequiitem == null ){ 
       $this->erro_sql = " Campo Codigo Sequencial nao Informado.";
       $this->erro_campo = "m43_codmatrequiitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m43_quantatend == null ){ 
       $this->erro_sql = " Campo Quant. Atendida nao Informado.";
       $this->erro_campo = "m43_quantatend";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m43_codigo == "" || $m43_codigo == null ){
       $result = db_query("select nextval('atendrequiitem_m43_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atendrequiitem_m43_codigo_seq do campo: m43_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m43_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from atendrequiitem_m43_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m43_codigo)){
         $this->erro_sql = " Campo m43_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m43_codigo = $m43_codigo; 
       }
     }
     if(($this->m43_codigo == null) || ($this->m43_codigo == "") ){ 
       $this->erro_sql = " Campo m43_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atendrequiitem(
                                       m43_codigo 
                                      ,m43_codatendrequi 
                                      ,m43_codmatrequiitem 
                                      ,m43_quantatend 
                       )
                values (
                                $this->m43_codigo 
                               ,$this->m43_codatendrequi 
                               ,$this->m43_codmatrequiitem 
                               ,$this->m43_quantatend 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "atendrequiitem ($this->m43_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "atendrequiitem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "atendrequiitem ($this->m43_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m43_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m43_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6881,'$this->m43_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1131,6881,'','".AddSlashes(pg_result($resaco,0,'m43_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1131,6882,'','".AddSlashes(pg_result($resaco,0,'m43_codatendrequi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1131,6883,'','".AddSlashes(pg_result($resaco,0,'m43_codmatrequiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1131,6884,'','".AddSlashes(pg_result($resaco,0,'m43_quantatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m43_codigo=null) { 
      $this->atualizacampos();
     $sql = " update atendrequiitem set ";
     $virgula = "";
     if(trim($this->m43_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m43_codigo"])){ 
       $sql  .= $virgula." m43_codigo = $this->m43_codigo ";
       $virgula = ",";
       if(trim($this->m43_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "m43_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m43_codatendrequi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m43_codatendrequi"])){ 
       $sql  .= $virgula." m43_codatendrequi = $this->m43_codatendrequi ";
       $virgula = ",";
       if(trim($this->m43_codatendrequi) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "m43_codatendrequi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m43_codmatrequiitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m43_codmatrequiitem"])){ 
       $sql  .= $virgula." m43_codmatrequiitem = $this->m43_codmatrequiitem ";
       $virgula = ",";
       if(trim($this->m43_codmatrequiitem) == null ){ 
         $this->erro_sql = " Campo Codigo Sequencial nao Informado.";
         $this->erro_campo = "m43_codmatrequiitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m43_quantatend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m43_quantatend"])){ 
       $sql  .= $virgula." m43_quantatend = $this->m43_quantatend ";
       $virgula = ",";
       if(trim($this->m43_quantatend) == null ){ 
         $this->erro_sql = " Campo Quant. Atendida nao Informado.";
         $this->erro_campo = "m43_quantatend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m43_codigo!=null){
       $sql .= " m43_codigo = $this->m43_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m43_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6881,'$this->m43_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m43_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1131,6881,'".AddSlashes(pg_result($resaco,$conresaco,'m43_codigo'))."','$this->m43_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m43_codatendrequi"]))
           $resac = db_query("insert into db_acount values($acount,1131,6882,'".AddSlashes(pg_result($resaco,$conresaco,'m43_codatendrequi'))."','$this->m43_codatendrequi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m43_codmatrequiitem"]))
           $resac = db_query("insert into db_acount values($acount,1131,6883,'".AddSlashes(pg_result($resaco,$conresaco,'m43_codmatrequiitem'))."','$this->m43_codmatrequiitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m43_quantatend"]))
           $resac = db_query("insert into db_acount values($acount,1131,6884,'".AddSlashes(pg_result($resaco,$conresaco,'m43_quantatend'))."','$this->m43_quantatend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "atendrequiitem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m43_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "atendrequiitem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m43_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m43_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m43_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m43_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6881,'$m43_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1131,6881,'','".AddSlashes(pg_result($resaco,$iresaco,'m43_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1131,6882,'','".AddSlashes(pg_result($resaco,$iresaco,'m43_codatendrequi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1131,6883,'','".AddSlashes(pg_result($resaco,$iresaco,'m43_codmatrequiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1131,6884,'','".AddSlashes(pg_result($resaco,$iresaco,'m43_quantatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atendrequiitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m43_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m43_codigo = $m43_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "atendrequiitem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m43_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "atendrequiitem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m43_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m43_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:atendrequiitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m43_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendrequiitem ";
     $sql .= "      inner join matrequiitem  on  matrequiitem.m41_codigo = atendrequiitem.m43_codmatrequiitem";
     $sql .= "      inner join atendrequi  on  atendrequi.m42_codigo = atendrequiitem.m43_codatendrequi";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
     $sql .= "      inner join matrequi  on  matrequi.m40_codigo = matrequiitem.m41_codmatrequi";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = atendrequi.m42_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = atendrequi.m42_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($m43_codigo!=null ){
         $sql2 .= " where atendrequiitem.m43_codigo = $m43_codigo "; 
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
   function sql_query_devol ( $m43_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendrequiitem ";
     $sql .= "      inner join matrequiitem  on  matrequiitem.m41_codigo = atendrequiitem.m43_codmatrequiitem";
     $sql .= "      inner join atendrequi  on  atendrequi.m42_codigo = atendrequiitem.m43_codatendrequi";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
     $sql .= "      inner join matrequi  on  matrequi.m40_codigo = matrequiitem.m41_codmatrequi";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = atendrequi.m42_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = atendrequi.m42_depto";
     $sql .= "      left join matestoquedevitem  on  m46_codatendrequiitem = m43_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($m43_codigo!=null ){
         $sql2 .= " where atendrequiitem.m43_codigo = $m43_codigo "; 
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
   function sql_query_file ( $m43_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendrequiitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($m43_codigo!=null ){
         $sql2 .= " where atendrequiitem.m43_codigo = $m43_codigo "; 
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
  
 function sql_query_inimei ( $m43_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendrequiitem ";
     $sql .= "      inner join matrequiitem  on  matrequiitem.m41_codigo = atendrequiitem.m43_codmatrequiitem";
     $sql .= "      inner join atendrequi  on  atendrequi.m42_codigo = atendrequiitem.m43_codatendrequi";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
     $sql .= "      inner join matrequi  on  matrequi.m40_codigo = matrequiitem.m41_codmatrequi";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = atendrequi.m42_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = atendrequi.m42_depto";
     $sql .= "      inner join matestoqueinimeiari  on  m49_codatendrequiitem   = m43_codigo";
     $sql .= "      inner join matestoqueinimei     on  m49_codmatestoqueinimei = m82_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($m43_codigo!=null ){
         $sql2 .= " where atendrequiitem.m43_codigo = $m43_codigo "; 
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