<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: licitação
//CLASSE DA ENTIDADE liclicitem
class cl_liclicitem { 
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
   var $l21_codigo = 0; 
   var $l21_codliclicita = 0; 
   var $l21_codpcprocitem = 0; 
   var $l21_situacao = 0; 
   var $l21_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l21_codigo = int8 = Cod. Sequencial 
                 l21_codliclicita = int8 = Cod. Sequencial 
                 l21_codpcprocitem = int8 = Código sequencial do item no processo 
                 l21_situacao = int4 = Situação 
                 l21_ordem = int4 = Seqüência 
                 ";
   //funcao construtor da classe 
   function cl_liclicitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liclicitem"); 
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
       $this->l21_codigo = ($this->l21_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l21_codigo"]:$this->l21_codigo);
       $this->l21_codliclicita = ($this->l21_codliclicita == ""?@$GLOBALS["HTTP_POST_VARS"]["l21_codliclicita"]:$this->l21_codliclicita);
       $this->l21_codpcprocitem = ($this->l21_codpcprocitem == ""?@$GLOBALS["HTTP_POST_VARS"]["l21_codpcprocitem"]:$this->l21_codpcprocitem);
       $this->l21_situacao = ($this->l21_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["l21_situacao"]:$this->l21_situacao);
       $this->l21_ordem = ($this->l21_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["l21_ordem"]:$this->l21_ordem);
     }else{
       $this->l21_codigo = ($this->l21_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l21_codigo"]:$this->l21_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($l21_codigo){ 
      $this->atualizacampos();
     if($this->l21_codliclicita == null ){ 
       $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
       $this->erro_campo = "l21_codliclicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l21_codpcprocitem == null ){ 
       $this->erro_sql = " Campo Código sequencial do item no processo nao Informado.";
       $this->erro_campo = "l21_codpcprocitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l21_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "l21_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l21_ordem == null ){ 
       $this->l21_ordem = "1";
     }
     if($l21_codigo == "" || $l21_codigo == null ){
       $result = db_query("select nextval('liclicitem_l21_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclicitem_l21_codigo_seq do campo: l21_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l21_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from liclicitem_l21_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $l21_codigo)){
         $this->erro_sql = " Campo l21_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l21_codigo = $l21_codigo; 
       }
     }
     if(($this->l21_codigo == null) || ($this->l21_codigo == "") ){ 
       $this->erro_sql = " Campo l21_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liclicitem(
                                       l21_codigo 
                                      ,l21_codliclicita 
                                      ,l21_codpcprocitem 
                                      ,l21_situacao 
                                      ,l21_ordem 
                       )
                values (
                                $this->l21_codigo 
                               ,$this->l21_codliclicita 
                               ,$this->l21_codpcprocitem 
                               ,$this->l21_situacao 
                               ,$this->l21_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "liclicitem ($this->l21_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "liclicitem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "liclicitem ($this->l21_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l21_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l21_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7600,'$this->l21_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1261,7600,'','".AddSlashes(pg_result($resaco,0,'l21_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1261,7601,'','".AddSlashes(pg_result($resaco,0,'l21_codliclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1261,7602,'','".AddSlashes(pg_result($resaco,0,'l21_codpcprocitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1261,10011,'','".AddSlashes(pg_result($resaco,0,'l21_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1261,10973,'','".AddSlashes(pg_result($resaco,0,'l21_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l21_codigo=null) { 
      $this->atualizacampos();
     $sql = " update liclicitem set ";
     $virgula = "";
     if(trim($this->l21_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l21_codigo"])){ 
       $sql  .= $virgula." l21_codigo = $this->l21_codigo ";
       $virgula = ",";
       if(trim($this->l21_codigo) == null ){ 
         $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
         $this->erro_campo = "l21_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l21_codliclicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l21_codliclicita"])){ 
       $sql  .= $virgula." l21_codliclicita = $this->l21_codliclicita ";
       $virgula = ",";
       if(trim($this->l21_codliclicita) == null ){ 
         $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
         $this->erro_campo = "l21_codliclicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l21_codpcprocitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l21_codpcprocitem"])){ 
       $sql  .= $virgula." l21_codpcprocitem = $this->l21_codpcprocitem ";
       $virgula = ",";
       if(trim($this->l21_codpcprocitem) == null ){ 
         $this->erro_sql = " Campo Código sequencial do item no processo nao Informado.";
         $this->erro_campo = "l21_codpcprocitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l21_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l21_situacao"])){ 
       $sql  .= $virgula." l21_situacao = $this->l21_situacao ";
       $virgula = ",";
       if(trim($this->l21_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "l21_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l21_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l21_ordem"])){ 
        if(trim($this->l21_ordem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["l21_ordem"])){ 
           $this->l21_ordem = "0" ; 
        } 
       $sql  .= $virgula." l21_ordem = $this->l21_ordem ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($l21_codigo!=null){
       $sql .= " l21_codigo = $this->l21_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l21_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7600,'$this->l21_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l21_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1261,7600,'".AddSlashes(pg_result($resaco,$conresaco,'l21_codigo'))."','$this->l21_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l21_codliclicita"]))
           $resac = db_query("insert into db_acount values($acount,1261,7601,'".AddSlashes(pg_result($resaco,$conresaco,'l21_codliclicita'))."','$this->l21_codliclicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l21_codpcprocitem"]))
           $resac = db_query("insert into db_acount values($acount,1261,7602,'".AddSlashes(pg_result($resaco,$conresaco,'l21_codpcprocitem'))."','$this->l21_codpcprocitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l21_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1261,10011,'".AddSlashes(pg_result($resaco,$conresaco,'l21_situacao'))."','$this->l21_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l21_ordem"]))
           $resac = db_query("insert into db_acount values($acount,1261,10973,'".AddSlashes(pg_result($resaco,$conresaco,'l21_ordem'))."','$this->l21_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "liclicitem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l21_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "liclicitem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l21_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l21_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l21_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l21_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7600,'$l21_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1261,7600,'','".AddSlashes(pg_result($resaco,$iresaco,'l21_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1261,7601,'','".AddSlashes(pg_result($resaco,$iresaco,'l21_codliclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1261,7602,'','".AddSlashes(pg_result($resaco,$iresaco,'l21_codpcprocitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1261,10011,'','".AddSlashes(pg_result($resaco,$iresaco,'l21_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1261,10973,'','".AddSlashes(pg_result($resaco,$iresaco,'l21_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from liclicitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l21_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l21_codigo = $l21_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "liclicitem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l21_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "liclicitem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l21_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l21_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:liclicitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $l21_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitem ";
     $sql .= "      inner join pcprocitem   on  pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem";
     $sql .= "      inner join liclicita    on  liclicita.l20_codigo     = liclicitem.l21_codliclicita";
     $sql .= "      inner join solicitem    on  solicitem.pc11_codigo    = pcprocitem.pc81_solicitem";
     $sql .= "      inner join pcproc       on  pcproc.pc80_codproc      = pcprocitem.pc81_codproc";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario   = liclicita.l20_id_usucria";
     $sql .= "      inner join cflicita     on  cflicita.l03_codigo      = liclicita.l20_codtipocom";
     $sql .= "      inner join liclocal     on  liclocal.l26_codigo      = liclicita.l20_liclocal";
     $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo   = liclicita.l20_liccomissao";
     $sql .= "       left join solicitatipo on  solicitatipo.pc12_numero = solicitem.pc11_numero"; 
     $sql .= "       left join pctipocompra on  pctipocompra.pc50_codcom = solicitatipo.pc12_tipo";     
     $sql2 = "";
     if($dbwhere==""){
       if($l21_codigo!=null ){
         $sql2 .= " where liclicitem.l21_codigo = $l21_codigo "; 
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
   function sql_query_anulados ( $l21_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitem ";
     $sql .= "      inner join liclicita        on liclicita.l20_codigo            = liclicitem.l21_codliclicita";
     $sql .= "      inner join cflicita         on cflicita.l03_codigo             = liclicita.l20_codtipocom";
     $sql .= "      inner join pctipocompra     on pctipocompra.pc50_codcom        = cflicita.l03_codcom";
     $sql .= "      inner join pcprocitem       on liclicitem.l21_codpcprocitem    = pcprocitem.pc81_codprocitem";
     $sql .= "      inner join pcproc           on pcproc.pc80_codproc             = pcprocitem.pc81_codproc";
     $sql .= "      inner join solicitem        on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita         on solicita.pc10_numero            = solicitem.pc11_numero";
     $sql .= "      inner join db_depart        on db_depart.coddepto              = solicita.pc10_depto";
     $sql .= "      inner join db_usuarios      on solicita.pc10_login             = db_usuarios.id_usuario";
     $sql .= "      left  join solicitemunid    on solicitemunid.pc17_codigo       = solicitem.pc11_codigo";
     $sql .= "      left  join matunid          on matunid.m61_codmatunid          = solicitemunid.pc17_unid";     
     $sql .= "      left  join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater          on pcmater.pc01_codmater           = solicitempcmater.pc16_codmater"; 
     $sql .= "      left  join solicitemele     on solicitemele.pc18_solicitem     = solicitem.pc11_codigo";    
     $sql .= "      left  join liclicitemanu    on liclicitemanu.l07_liclicitem    = liclicitem.l21_codigo"; 
     $sql .= "      left  join licsituacao      on licsituacao.l08_sequencial    = liclicita.l20_licsituacao"; 

     $sql2 = "";
     if($dbwhere==""){
       if($l21_codigo!=null ){
         $sql2 .= " where liclicitem.l21_codigo = $l21_codigo "; 
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
   function sql_query_file ( $l21_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($l21_codigo!=null ){
         $sql2 .= " where liclicitem.l21_codigo = $l21_codigo "; 
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
   function sql_query_inf ( $l21_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitem ";
     $sql .= "      inner join pcprocitem           on liclicitem.l21_codpcprocitem        = pcprocitem.pc81_codprocitem";
     $sql .= "      inner join pcproc               on pcproc.pc80_codproc                 = pcprocitem.pc81_codproc";
     $sql .= "      inner join solicitem            on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita             on solicita.pc10_numero                = solicitem.pc11_numero";
     $sql .= "      inner join db_depart            on db_depart.coddepto                  = solicita.pc10_depto";
     $sql .= "      left  join liclicita            on liclicita.l20_codigo                = liclicitem.l21_codliclicita";
     $sql .= "      left  join cflicita             on cflicita.l03_codigo                 = liclicita.l20_codtipocom";
     $sql .= "      left  join pctipocompra         on pctipocompra.pc50_codcom            = cflicita.l03_codcom";
     $sql .= "      left  join solicitemunid        on solicitemunid.pc17_codigo           = solicitem.pc11_codigo";
     $sql .= "      left  join matunid              on matunid.m61_codmatunid              = solicitemunid.pc17_unid";
     $sql .= "      left  join pcorcamitemlic       on l21_codigo                          = pc26_liclicitem ";
     $sql .= "      left  join pcorcamval           on pc26_orcamitem                      = pc23_orcamitem ";     
     $sql .= "      left  join db_usuarios          on pcproc.pc80_usuario                 = db_usuarios.id_usuario";
     $sql .= "      left  join solicitempcmater     on solicitempcmater.pc16_solicitem     = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater              on pcmater.pc01_codmater               = solicitempcmater.pc16_codmater";
     $sql .= "      left  join pcsubgrupo           on pcsubgrupo.pc04_codsubgrupo         = pcmater.pc01_codsubgrupo";
     $sql .= "      left  join pctipo               on pctipo.pc05_codtipo                 = pcsubgrupo.pc04_codtipo";
     $sql .= "      left  join solicitemele         on solicitemele.pc18_solicitem         = solicitem.pc11_codigo";
     $sql .= "      left  join orcelemento          on orcelemento.o56_codele              = solicitemele.pc18_codele";
     $sql .= "                                     and orcelemento.o56_anousu              = ".db_getsession("DB_anousu");
     $sql .= "      left  join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";    
     $sql .= "      left  join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
     $sql .= "                                     and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
     $sql .= "      left  join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori ";
     $sql .= "      left  join empempaut            on empempaut.e61_autori                = empautitem.e55_autori ";     
     $sql .= "      left  join empempenho           on empempenho.e60_numemp               = empempaut.e61_numemp ";
     $sql .= "      left  join pcdotac              on solicitem.pc11_codigo               = pcdotac.pc13_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($l21_codigo!=null ){
         $sql2 .= " where liclicitem.l21_codigo = $l21_codigo "; 
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
   function sql_query_orc ( $l21_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitem ";
     $sql .= "      inner join pcprocitem  on  liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem";
     $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = pcproc.pc80_depto";
     $sql .= "      inner join db_usuarios  on  pcproc.pc80_usuario = db_usuarios.id_usuario";
     $sql .= "      inner join pcdotac on  solicitem.pc11_codigo = pcdotac.pc13_codigo ";
     $sql .= "      inner join orcdotacao on  o58_coddot = pc13_coddot and pc13_anousu = o58_anousu  ";
     $sql .= "      inner join orcorgao on o40_orgao = o58_orgao and o40_anousu=o58_anousu ";
     $sql2 = "";
     if($dbwhere==""){
       if($l21_codigo!=null ){
         $sql2 .= " where liclicitem.l21_codigo = $l21_codigo "; 
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
   function sql_query_proc ( $l21_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitem ";
     $sql .= "      inner join pcprocitem  on  liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem";
     $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = pcproc.pc80_depto";
     $sql .= "      inner join db_usuarios  on  pcproc.pc80_usuario = db_usuarios.id_usuario";     
     $sql2 = "";
     if($dbwhere==""){
       if($l21_codigo!=null ){
         $sql2 .= " where liclicitem.l21_codigo = $l21_codigo "; 
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
   function sql_query_sol ( $l21_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitem ";
     $sql .= "      inner join pcprocitem           on  liclicitem.l21_codpcprocitem    = pcprocitem.pc81_codprocitem";
     $sql .= "      inner join pcproc               on  pcproc.pc80_codproc             = pcprocitem.pc81_codproc";
     $sql .= "      inner join solicitem            on  solicitem.pc11_codigo           = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita             on  solicita.pc10_numero            = solicitem.pc11_numero";
     $sql .= "      inner join db_depart            on  db_depart.coddepto              = solicita.pc10_depto";
     $sql .= "      inner join db_usuarios          on  solicita.pc10_login             = db_usuarios.id_usuario";
     $sql .= "      left  join solicitemunid        on  solicitemunid.pc17_codigo       = solicitem.pc11_codigo";
     $sql .= "      left  join matunid              on  matunid.m61_codmatunid          = solicitemunid.pc17_unid";     
     $sql .= "      left  join solicitempcmater     on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater  				    on  pcmater.pc01_codmater           = solicitempcmater.pc16_codmater"; 
     $sql .= "      left  join solicitemele         on  solicitemele.pc18_solicitem     = solicitem.pc11_codigo";    
     $sql .= "      left  join solicitaprotprocesso on solicitaprotprocesso.pc90_solicita  = solicita.pc10_numero ";
     $sql2 = "";
     if($dbwhere==""){
       if($l21_codigo!=null ){
         $sql2 .= " where liclicitem.l21_codigo = $l21_codigo "; 
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
  
function sql_query_soljulg ( $l21_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitem ";
     $sql .= "      inner join liclicita on l21_codliclicita = l20_codigo";
     $sql .= "      inner join pcprocitem  on  liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem";
     $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      inner join db_usuarios  on  solicita.pc10_login = db_usuarios.id_usuario";
     $sql .= "      inner join pcorcamitemlic on l21_codigo = pc26_liclicitem ";
     $sql .= "      inner join pcorcamval     on pc26_orcamitem = pc23_orcamitem ";
     $sql .= "      inner join pcorcamjulg    on pc23_orcamitem = pc24_orcamitem ";
     $sql .= "                               and pc23_orcamforne = pc24_orcamforne";
     $sql .= "                               and pc24_pontuacao  = 1";
     $sql .= "      inner join pcorcamforne   on pc23_orcamforne = pc21_orcamforne ";
     $sql .= "      left  join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
     $sql .= "      left  join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";     
     $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater"; 
     $sql .= "      left  join solicitemele  on  solicitemele.pc18_solicitem = solicitem.pc11_codigo";    
     $sql .= "      left  join acordoliclicitem  on  l21_codigo = ac24_liclicitem";    
     
     $sql2 = "";
     if($dbwhere==""){
       if($l21_codigo!=null ){
         $sql2 .= " where liclicitem.l21_codigo = $l21_codigo "; 
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
  
 function sql_query_dotacao_reserva ( $l21_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitem ";
     $sql .= "      inner join pcprocitem  on  liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem";
     $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join pcdotac on  solicitem.pc11_codigo = pcdotac.pc13_codigo ";
     $sql .= "      left join  orcreservasol  on pc13_sequencial = o82_pcdotac";
     $sql .= "      left join  orcreserva     on o82_codres      = o80_codres";
     $sql2 = "";
     $sql2 = "";
     if($dbwhere==""){
       if($l21_codigo!=null ){
         $sql2 .= " where liclicitem.l21_codigo = $l21_codigo "; 
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