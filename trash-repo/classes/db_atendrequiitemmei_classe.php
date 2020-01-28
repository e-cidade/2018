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
//CLASSE DA ENTIDADE atendrequiitemmei
class cl_atendrequiitemmei { 
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
   var $m44_codigo = 0; 
   var $m44_codatendreqitem = 0; 
   var $m44_codmatestoqueitem = 0; 
   var $m44_quant = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m44_codigo = int8 = Codigo 
                 m44_codatendreqitem = int8 = Código 
                 m44_codmatestoqueitem = int8 = Código sequencial do lançamento 
                 m44_quant = float8 = Quantidade 
                 ";
   //funcao construtor da classe 
   function cl_atendrequiitemmei() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atendrequiitemmei"); 
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
       $this->m44_codigo = ($this->m44_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m44_codigo"]:$this->m44_codigo);
       $this->m44_codatendreqitem = ($this->m44_codatendreqitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m44_codatendreqitem"]:$this->m44_codatendreqitem);
       $this->m44_codmatestoqueitem = ($this->m44_codmatestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m44_codmatestoqueitem"]:$this->m44_codmatestoqueitem);
       $this->m44_quant = ($this->m44_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["m44_quant"]:$this->m44_quant);
     }else{
       $this->m44_codigo = ($this->m44_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m44_codigo"]:$this->m44_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($m44_codigo){ 
      $this->atualizacampos();
     if($this->m44_codatendreqitem == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "m44_codatendreqitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m44_codmatestoqueitem == null ){ 
       $this->erro_sql = " Campo Código sequencial do lançamento nao Informado.";
       $this->erro_campo = "m44_codmatestoqueitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m44_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "m44_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m44_codigo == "" || $m44_codigo == null ){
       $result = db_query("select nextval('atendrequiitemmei_m44_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atendrequiitemmei_m44_codigo_seq do campo: m44_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m44_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from atendrequiitemmei_m44_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m44_codigo)){
         $this->erro_sql = " Campo m44_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m44_codigo = $m44_codigo; 
       }
     }
     if(($this->m44_codigo == null) || ($this->m44_codigo == "") ){ 
       $this->erro_sql = " Campo m44_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atendrequiitemmei(
                                       m44_codigo 
                                      ,m44_codatendreqitem 
                                      ,m44_codmatestoqueitem 
                                      ,m44_quant 
                       )
                values (
                                $this->m44_codigo 
                               ,$this->m44_codatendreqitem 
                               ,$this->m44_codmatestoqueitem 
                               ,$this->m44_quant 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "atendrequiitemmei ($this->m44_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "atendrequiitemmei já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "atendrequiitemmei ($this->m44_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m44_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m44_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6895,'$this->m44_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1132,6895,'','".AddSlashes(pg_result($resaco,0,'m44_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1132,6886,'','".AddSlashes(pg_result($resaco,0,'m44_codatendreqitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1132,6885,'','".AddSlashes(pg_result($resaco,0,'m44_codmatestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1132,6894,'','".AddSlashes(pg_result($resaco,0,'m44_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m44_codigo=null) { 
      $this->atualizacampos();
     $sql = " update atendrequiitemmei set ";
     $virgula = "";
     if(trim($this->m44_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m44_codigo"])){ 
       $sql  .= $virgula." m44_codigo = $this->m44_codigo ";
       $virgula = ",";
       if(trim($this->m44_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "m44_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m44_codatendreqitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m44_codatendreqitem"])){ 
       $sql  .= $virgula." m44_codatendreqitem = $this->m44_codatendreqitem ";
       $virgula = ",";
       if(trim($this->m44_codatendreqitem) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "m44_codatendreqitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m44_codmatestoqueitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m44_codmatestoqueitem"])){ 
       $sql  .= $virgula." m44_codmatestoqueitem = $this->m44_codmatestoqueitem ";
       $virgula = ",";
       if(trim($this->m44_codmatestoqueitem) == null ){ 
         $this->erro_sql = " Campo Código sequencial do lançamento nao Informado.";
         $this->erro_campo = "m44_codmatestoqueitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m44_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m44_quant"])){ 
       $sql  .= $virgula." m44_quant = $this->m44_quant ";
       $virgula = ",";
       if(trim($this->m44_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "m44_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m44_codigo!=null){
       $sql .= " m44_codigo = $this->m44_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m44_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6895,'$this->m44_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m44_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1132,6895,'".AddSlashes(pg_result($resaco,$conresaco,'m44_codigo'))."','$this->m44_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m44_codatendreqitem"]))
           $resac = db_query("insert into db_acount values($acount,1132,6886,'".AddSlashes(pg_result($resaco,$conresaco,'m44_codatendreqitem'))."','$this->m44_codatendreqitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m44_codmatestoqueitem"]))
           $resac = db_query("insert into db_acount values($acount,1132,6885,'".AddSlashes(pg_result($resaco,$conresaco,'m44_codmatestoqueitem'))."','$this->m44_codmatestoqueitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m44_quant"]))
           $resac = db_query("insert into db_acount values($acount,1132,6894,'".AddSlashes(pg_result($resaco,$conresaco,'m44_quant'))."','$this->m44_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "atendrequiitemmei nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m44_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "atendrequiitemmei nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m44_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m44_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m44_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m44_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6895,'$m44_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1132,6895,'','".AddSlashes(pg_result($resaco,$iresaco,'m44_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1132,6886,'','".AddSlashes(pg_result($resaco,$iresaco,'m44_codatendreqitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1132,6885,'','".AddSlashes(pg_result($resaco,$iresaco,'m44_codmatestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1132,6894,'','".AddSlashes(pg_result($resaco,$iresaco,'m44_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atendrequiitemmei
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m44_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m44_codigo = $m44_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "atendrequiitemmei nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m44_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "atendrequiitemmei nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m44_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m44_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:atendrequiitemmei";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m44_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendrequiitemmei ";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = atendrequiitemmei.m44_codmatestoqueitem";
     $sql .= "      inner join atendrequiitem  on  atendrequiitem.m43_codigo = atendrequiitemmei.m44_codatendreqitem";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join matrequiitem  on  matrequiitem.m41_codigo = atendrequiitem.m43_codmatrequiitem";
     $sql .= "      inner join atendrequi  as a on   a.m42_codigo = atendrequiitem.m43_codatendrequi";
     $sql2 = "";
     if($dbwhere==""){
       if($m44_codigo!=null ){
         $sql2 .= " where atendrequiitemmei.m44_codigo = $m44_codigo "; 
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
   function sql_query_file ( $m44_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendrequiitemmei ";
     $sql2 = "";
     if($dbwhere==""){
       if($m44_codigo!=null ){
         $sql2 .= " where atendrequiitemmei.m44_codigo = $m44_codigo "; 
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