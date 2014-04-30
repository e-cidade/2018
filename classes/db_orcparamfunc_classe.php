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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcparamfunc
class cl_orcparamfunc { 
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
   var $o45_sequencia = 0; 
   var $o45_codparrel = 0; 
   var $o45_anousu = 0; 
   var $o45_func = 0; 
   var $o45_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o45_sequencia = int4 = Seqüência 
                 o45_codparrel = int4 = Cód. de Parâmetro 
                 o45_anousu = int4 = Exercício 
                 o45_func = int4 = Função 
                 o45_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_orcparamfunc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcparamfunc"); 
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
       $this->o45_sequencia = ($this->o45_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_sequencia"]:$this->o45_sequencia);
       $this->o45_codparrel = ($this->o45_codparrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_codparrel"]:$this->o45_codparrel);
       $this->o45_anousu = ($this->o45_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_anousu"]:$this->o45_anousu);
       $this->o45_func = ($this->o45_func == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_func"]:$this->o45_func);
       $this->o45_instit = ($this->o45_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_instit"]:$this->o45_instit);
     }else{
       $this->o45_sequencia = ($this->o45_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_sequencia"]:$this->o45_sequencia);
       $this->o45_codparrel = ($this->o45_codparrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_codparrel"]:$this->o45_codparrel);
       $this->o45_anousu = ($this->o45_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_anousu"]:$this->o45_anousu);
       $this->o45_func = ($this->o45_func == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_func"]:$this->o45_func);
     }
   }
   // funcao para inclusao
   function incluir ($o45_sequencia,$o45_anousu,$o45_codparrel,$o45_func){ 
      $this->atualizacampos();
     if($this->o45_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "o45_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o45_sequencia = $o45_sequencia; 
       $this->o45_anousu = $o45_anousu; 
       $this->o45_codparrel = $o45_codparrel; 
       $this->o45_func = $o45_func; 
     if(($this->o45_sequencia == null) || ($this->o45_sequencia == "") ){ 
       $this->erro_sql = " Campo o45_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o45_anousu == null) || ($this->o45_anousu == "") ){ 
       $this->erro_sql = " Campo o45_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o45_codparrel == null) || ($this->o45_codparrel == "") ){ 
       $this->erro_sql = " Campo o45_codparrel nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o45_func == null) || ($this->o45_func == "") ){ 
       $this->erro_sql = " Campo o45_func nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcparamfunc(
                                       o45_sequencia 
                                      ,o45_codparrel 
                                      ,o45_anousu 
                                      ,o45_func 
                                      ,o45_instit 
                       )
                values (
                                $this->o45_sequencia 
                               ,$this->o45_codparrel 
                               ,$this->o45_anousu 
                               ,$this->o45_func 
                               ,$this->o45_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->o45_sequencia."-".$this->o45_anousu."-".$this->o45_codparrel."-".$this->o45_func) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->o45_sequencia."-".$this->o45_anousu."-".$this->o45_codparrel."-".$this->o45_func) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o45_sequencia."-".$this->o45_anousu."-".$this->o45_codparrel."-".$this->o45_func;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o45_sequencia,$this->o45_anousu,$this->o45_codparrel,$this->o45_func));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10249,'$this->o45_sequencia','I')");
       $resac = db_query("insert into db_acountkey values($acount,10247,'$this->o45_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,10248,'$this->o45_codparrel','I')");
       $resac = db_query("insert into db_acountkey values($acount,10250,'$this->o45_func','I')");
       $resac = db_query("insert into db_acount values($acount,1770,10249,'','".AddSlashes(pg_result($resaco,0,'o45_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1770,10248,'','".AddSlashes(pg_result($resaco,0,'o45_codparrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1770,10247,'','".AddSlashes(pg_result($resaco,0,'o45_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1770,10250,'','".AddSlashes(pg_result($resaco,0,'o45_func'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1770,10251,'','".AddSlashes(pg_result($resaco,0,'o45_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o45_sequencia=null,$o45_anousu=null,$o45_codparrel=null,$o45_func=null) { 
      $this->atualizacampos();
     $sql = " update orcparamfunc set ";
     $virgula = "";
     if(trim($this->o45_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o45_sequencia"])){ 
       $sql  .= $virgula." o45_sequencia = $this->o45_sequencia ";
       $virgula = ",";
       if(trim($this->o45_sequencia) == null ){ 
         $this->erro_sql = " Campo Seqüência nao Informado.";
         $this->erro_campo = "o45_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o45_codparrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o45_codparrel"])){ 
       $sql  .= $virgula." o45_codparrel = $this->o45_codparrel ";
       $virgula = ",";
       if(trim($this->o45_codparrel) == null ){ 
         $this->erro_sql = " Campo Cód. de Parâmetro nao Informado.";
         $this->erro_campo = "o45_codparrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o45_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o45_anousu"])){ 
       $sql  .= $virgula." o45_anousu = $this->o45_anousu ";
       $virgula = ",";
       if(trim($this->o45_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o45_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o45_func)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o45_func"])){ 
       $sql  .= $virgula." o45_func = $this->o45_func ";
       $virgula = ",";
       if(trim($this->o45_func) == null ){ 
         $this->erro_sql = " Campo Função nao Informado.";
         $this->erro_campo = "o45_func";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o45_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o45_instit"])){ 
       $sql  .= $virgula." o45_instit = $this->o45_instit ";
       $virgula = ",";
       if(trim($this->o45_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "o45_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o45_sequencia!=null){
       $sql .= " o45_sequencia = $this->o45_sequencia";
     }
     if($o45_anousu!=null){
       $sql .= " and  o45_anousu = $this->o45_anousu";
     }
     if($o45_codparrel!=null){
       $sql .= " and  o45_codparrel = $this->o45_codparrel";
     }
     if($o45_func!=null){
       $sql .= " and  o45_func = $this->o45_func";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o45_sequencia,$this->o45_anousu,$this->o45_codparrel,$this->o45_func));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10249,'$this->o45_sequencia','A')");
         $resac = db_query("insert into db_acountkey values($acount,10247,'$this->o45_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,10248,'$this->o45_codparrel','A')");
         $resac = db_query("insert into db_acountkey values($acount,10250,'$this->o45_func','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o45_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1770,10249,'".AddSlashes(pg_result($resaco,$conresaco,'o45_sequencia'))."','$this->o45_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o45_codparrel"]))
           $resac = db_query("insert into db_acount values($acount,1770,10248,'".AddSlashes(pg_result($resaco,$conresaco,'o45_codparrel'))."','$this->o45_codparrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o45_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1770,10247,'".AddSlashes(pg_result($resaco,$conresaco,'o45_anousu'))."','$this->o45_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o45_func"]))
           $resac = db_query("insert into db_acount values($acount,1770,10250,'".AddSlashes(pg_result($resaco,$conresaco,'o45_func'))."','$this->o45_func',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o45_instit"]))
           $resac = db_query("insert into db_acount values($acount,1770,10251,'".AddSlashes(pg_result($resaco,$conresaco,'o45_instit'))."','$this->o45_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o45_sequencia."-".$this->o45_anousu."-".$this->o45_codparrel."-".$this->o45_func;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o45_sequencia."-".$this->o45_anousu."-".$this->o45_codparrel."-".$this->o45_func;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o45_sequencia."-".$this->o45_anousu."-".$this->o45_codparrel."-".$this->o45_func;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o45_sequencia=null,$o45_anousu=null,$o45_codparrel=null,$o45_func=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o45_sequencia,$o45_anousu,$o45_codparrel,$o45_func));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10249,'$o45_sequencia','E')");
         $resac = db_query("insert into db_acountkey values($acount,10247,'$o45_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,10248,'$o45_codparrel','E')");
         $resac = db_query("insert into db_acountkey values($acount,10250,'$o45_func','E')");
         $resac = db_query("insert into db_acount values($acount,1770,10249,'','".AddSlashes(pg_result($resaco,$iresaco,'o45_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1770,10248,'','".AddSlashes(pg_result($resaco,$iresaco,'o45_codparrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1770,10247,'','".AddSlashes(pg_result($resaco,$iresaco,'o45_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1770,10250,'','".AddSlashes(pg_result($resaco,$iresaco,'o45_func'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1770,10251,'','".AddSlashes(pg_result($resaco,$iresaco,'o45_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcparamfunc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o45_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o45_sequencia = $o45_sequencia ";
        }
        if($o45_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o45_anousu = $o45_anousu ";
        }
        if($o45_codparrel != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o45_codparrel = $o45_codparrel ";
        }
        if($o45_func != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o45_func = $o45_func ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o45_sequencia."-".$o45_anousu."-".$o45_codparrel."-".$o45_func;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o45_sequencia."-".$o45_anousu."-".$o45_codparrel."-".$o45_func;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o45_sequencia."-".$o45_anousu."-".$o45_codparrel."-".$o45_func;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcparamfunc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   function sql_query ( $o45_sequencia=null,$o45_anousu=null,$o45_codparrel=null,$o45_func=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamfunc ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcparamfunc.o45_instit";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcparamfunc.o45_func";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($o45_sequencia!=null ){
         $sql2 .= " where orcparamfunc.o45_sequencia = $o45_sequencia "; 
       } 
       if($o45_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamfunc.o45_anousu = $o45_anousu "; 
       } 
       if($o45_codparrel!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamfunc.o45_codparrel = $o45_codparrel "; 
       } 
       if($o45_func!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamfunc.o45_func = $o45_func "; 
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

   function sql_query_file ( $o45_sequencia=null,$o45_anousu=null,$o45_codparrel=null,$o45_func=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamfunc ";
     $sql2 = "";
     if($dbwhere==""){
       if($o45_sequencia!=null ){
         $sql2 .= " where orcparamfunc.o45_sequencia = $o45_sequencia "; 
       } 
       if($o45_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamfunc.o45_anousu = $o45_anousu "; 
       } 
       if($o45_codparrel!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamfunc.o45_codparrel = $o45_codparrel "; 
       } 
       if($o45_func!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamfunc.o45_func = $o45_func "; 
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