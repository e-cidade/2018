<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: pessoal
//CLASSE DA ENTIDADE relac
class cl_relac { 
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
   var $r55_instit = 0; 
   var $r55_codeve = null; 
   var $r55_descr = null; 
   var $r55_rubr01 = null; 
   var $r55_rubr02 = null; 
   var $r55_rubr03 = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r55_instit = int4 = Cod. Instituição 
                 r55_codeve = char(4) = relacionamento 
                 r55_descr = char(40) = relacionamento 
                 r55_rubr01 = char(4) = 1.rubrica 
                 r55_rubr02 = char(4) = 2.rubrica 
                 r55_rubr03 = char(4) = 3.rubrica 
                 ";
   //funcao construtor da classe 
   function cl_relac() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("relac"); 
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
       $this->r55_instit = ($this->r55_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r55_instit"]:$this->r55_instit);
       $this->r55_codeve = ($this->r55_codeve == ""?@$GLOBALS["HTTP_POST_VARS"]["r55_codeve"]:$this->r55_codeve);
       $this->r55_descr = ($this->r55_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r55_descr"]:$this->r55_descr);
       $this->r55_rubr01 = ($this->r55_rubr01 == ""?@$GLOBALS["HTTP_POST_VARS"]["r55_rubr01"]:$this->r55_rubr01);
       $this->r55_rubr02 = ($this->r55_rubr02 == ""?@$GLOBALS["HTTP_POST_VARS"]["r55_rubr02"]:$this->r55_rubr02);
       $this->r55_rubr03 = ($this->r55_rubr03 == ""?@$GLOBALS["HTTP_POST_VARS"]["r55_rubr03"]:$this->r55_rubr03);
     }else{
       $this->r55_instit = ($this->r55_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r55_instit"]:$this->r55_instit);
       $this->r55_codeve = ($this->r55_codeve == ""?@$GLOBALS["HTTP_POST_VARS"]["r55_codeve"]:$this->r55_codeve);
     }
   }
   // funcao para inclusao
   function incluir ($r55_codeve,$r55_instit){ 
      $this->atualizacampos();
     if($this->r55_descr == null ){ 
       $this->erro_sql = " Campo relacionamento nao Informado.";
       $this->erro_campo = "r55_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r55_codeve = $r55_codeve; 
       $this->r55_instit = $r55_instit; 
     if(($this->r55_codeve == null) || ($this->r55_codeve == "") ){ 
       $this->erro_sql = " Campo r55_codeve nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r55_instit == null) || ($this->r55_instit == "") ){ 
       $this->erro_sql = " Campo r55_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into relac(
                                       r55_instit 
                                      ,r55_codeve 
                                      ,r55_descr 
                                      ,r55_rubr01 
                                      ,r55_rubr02 
                                      ,r55_rubr03 
                       )
                values (
                                $this->r55_instit 
                               ,'$this->r55_codeve' 
                               ,'$this->r55_descr' 
                               ,'$this->r55_rubr01' 
                               ,'$this->r55_rubr02' 
                               ,'$this->r55_rubr03' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivo de relacionamento ($this->r55_codeve."-".$this->r55_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivo de relacionamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivo de relacionamento ($this->r55_codeve."-".$this->r55_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r55_codeve."-".$this->r55_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r55_codeve,$this->r55_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4402,'$this->r55_codeve','I')");
       $resac = db_query("insert into db_acountkey values($acount,9898,'$this->r55_instit','I')");
       $resac = db_query("insert into db_acount values($acount,586,9898,'','".AddSlashes(pg_result($resaco,0,'r55_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,586,4402,'','".AddSlashes(pg_result($resaco,0,'r55_codeve'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,586,4403,'','".AddSlashes(pg_result($resaco,0,'r55_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,586,4404,'','".AddSlashes(pg_result($resaco,0,'r55_rubr01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,586,4405,'','".AddSlashes(pg_result($resaco,0,'r55_rubr02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,586,4406,'','".AddSlashes(pg_result($resaco,0,'r55_rubr03'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r55_codeve=null,$r55_instit=null) { 
      $this->atualizacampos();
     $sql = " update relac set ";
     $virgula = "";
     if(trim($this->r55_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r55_instit"])){ 
       $sql  .= $virgula." r55_instit = $this->r55_instit ";
       $virgula = ",";
       if(trim($this->r55_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "r55_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r55_codeve)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r55_codeve"])){ 
       $sql  .= $virgula." r55_codeve = '$this->r55_codeve' ";
       $virgula = ",";
       if(trim($this->r55_codeve) == null ){ 
         $this->erro_sql = " Campo relacionamento nao Informado.";
         $this->erro_campo = "r55_codeve";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r55_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r55_descr"])){ 
       $sql  .= $virgula." r55_descr = '$this->r55_descr' ";
       $virgula = ",";
       if(trim($this->r55_descr) == null ){ 
         $this->erro_sql = " Campo relacionamento nao Informado.";
         $this->erro_campo = "r55_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r55_rubr01)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r55_rubr01"])){ 
       $sql  .= $virgula." r55_rubr01 = '$this->r55_rubr01' ";
       $virgula = ",";
     }
     if(trim($this->r55_rubr02)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r55_rubr02"])){ 
       $sql  .= $virgula." r55_rubr02 = '$this->r55_rubr02' ";
       $virgula = ",";
     }
     if(trim($this->r55_rubr03)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r55_rubr03"])){ 
       $sql  .= $virgula." r55_rubr03 = '$this->r55_rubr03' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($r55_codeve!=null){
       $sql .= " r55_codeve = '$this->r55_codeve'";
     }
     if($r55_instit!=null){
       $sql .= " and  r55_instit = $this->r55_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r55_codeve,$this->r55_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4402,'$this->r55_codeve','A')");
         $resac = db_query("insert into db_acountkey values($acount,9898,'$this->r55_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r55_instit"]))
           $resac = db_query("insert into db_acount values($acount,586,9898,'".AddSlashes(pg_result($resaco,$conresaco,'r55_instit'))."','$this->r55_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r55_codeve"]))
           $resac = db_query("insert into db_acount values($acount,586,4402,'".AddSlashes(pg_result($resaco,$conresaco,'r55_codeve'))."','$this->r55_codeve',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r55_descr"]))
           $resac = db_query("insert into db_acount values($acount,586,4403,'".AddSlashes(pg_result($resaco,$conresaco,'r55_descr'))."','$this->r55_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r55_rubr01"]))
           $resac = db_query("insert into db_acount values($acount,586,4404,'".AddSlashes(pg_result($resaco,$conresaco,'r55_rubr01'))."','$this->r55_rubr01',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r55_rubr02"]))
           $resac = db_query("insert into db_acount values($acount,586,4405,'".AddSlashes(pg_result($resaco,$conresaco,'r55_rubr02'))."','$this->r55_rubr02',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r55_rubr03"]))
           $resac = db_query("insert into db_acount values($acount,586,4406,'".AddSlashes(pg_result($resaco,$conresaco,'r55_rubr03'))."','$this->r55_rubr03',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo de relacionamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r55_codeve."-".$this->r55_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo de relacionamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r55_codeve."-".$this->r55_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r55_codeve."-".$this->r55_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r55_codeve=null,$r55_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r55_codeve,$r55_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4402,'$r55_codeve','E')");
         $resac = db_query("insert into db_acountkey values($acount,9898,'$r55_instit','E')");
         $resac = db_query("insert into db_acount values($acount,586,9898,'','".AddSlashes(pg_result($resaco,$iresaco,'r55_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,586,4402,'','".AddSlashes(pg_result($resaco,$iresaco,'r55_codeve'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,586,4403,'','".AddSlashes(pg_result($resaco,$iresaco,'r55_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,586,4404,'','".AddSlashes(pg_result($resaco,$iresaco,'r55_rubr01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,586,4405,'','".AddSlashes(pg_result($resaco,$iresaco,'r55_rubr02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,586,4406,'','".AddSlashes(pg_result($resaco,$iresaco,'r55_rubr03'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from relac
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r55_codeve != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r55_codeve = '$r55_codeve' ";
        }
        if($r55_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r55_instit = $r55_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo de relacionamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r55_codeve."-".$r55_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo de relacionamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r55_codeve."-".$r55_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r55_codeve."-".$r55_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:relac";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r55_codeve=null,$r55_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from relac ";
     $sql .= "      inner join db_config  on  db_config.codigo = relac.r55_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r55_codeve!=null ){
         $sql2 .= " where trim(relac.r55_codeve) = '$r55_codeve' "; 
       } 
       if($r55_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " relac.r55_instit = $r55_instit "; 
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
   function sql_query_file ( $r55_codeve=null,$r55_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from relac ";
     $sql2 = "";
     if($dbwhere==""){
       if($r55_codeve!=null ){
         $sql2 .= " where relac.r55_codeve = '$r55_codeve' "; 
       } 
       if($r55_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " relac.r55_instit = $r55_instit "; 
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
   function sql_query_rubricas ( $r55_codeve=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from relac ";
     $sql .= "      left join rhrubricas a on a.rh27_rubric::char(4) = relac.r55_rubr01 and a.rh27_instit = " . db_getsession("DB_instit");
     $sql .= "      left join rhrubricas b on b.rh27_rubric::char(4) = relac.r55_rubr02 and b.rh27_instit = " . db_getsession("DB_instit");
     $sql .= "      left join rhrubricas c on c.rh27_rubric::char(4) = relac.r55_rubr03 and c.rh27_instit = " . db_getsession("DB_instit");
     $sql2 = "";
     if($dbwhere==""){
       if($r55_codeve!=null ){
         $sql2 .= " where relac.r55_codeve = '$r55_codeve' "; 
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