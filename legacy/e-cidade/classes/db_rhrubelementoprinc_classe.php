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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhrubelementoprinc
class cl_rhrubelementoprinc { 
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
   var $rh24_instit = 0; 
   var $rh24_rubric = null; 
   var $rh4_codele = 0; 
   var $rh24_codele = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh24_instit = int4 = Cod. Instituição 
                 rh24_rubric = varchar(4) = Código da Rubrica 
                 rh4_codele = int4 = Código elemento 
                 rh24_codele = int4 = Elemento 
                 ";
   //funcao construtor da classe 
   function cl_rhrubelementoprinc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhrubelementoprinc"); 
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
       $this->rh24_instit = ($this->rh24_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh24_instit"]:$this->rh24_instit);
       $this->rh24_rubric = ($this->rh24_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh24_rubric"]:$this->rh24_rubric);
       $this->rh4_codele = ($this->rh4_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["rh4_codele"]:$this->rh4_codele);
       $this->rh24_codele = ($this->rh24_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["rh24_codele"]:$this->rh24_codele);
     }else{
       $this->rh24_instit = ($this->rh24_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh24_instit"]:$this->rh24_instit);
       $this->rh24_rubric = ($this->rh24_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh24_rubric"]:$this->rh24_rubric);
       $this->rh4_codele = ($this->rh4_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["rh4_codele"]:$this->rh4_codele);
     }
   }
   // funcao para inclusao
   function incluir ($rh24_rubric,$rh4_codele,$rh24_instit){ 
      $this->atualizacampos();
     if($this->rh24_codele == null ){ 
       $this->erro_sql = " Campo Elemento nao Informado.";
       $this->erro_campo = "rh24_codele";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->rh24_rubric = $rh24_rubric; 
       $this->rh4_codele = $rh4_codele; 
       $this->rh24_instit = $rh24_instit; 
     if(($this->rh24_rubric == null) || ($this->rh24_rubric == "") ){ 
       $this->erro_sql = " Campo rh24_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh4_codele == null) || ($this->rh4_codele == "") ){ 
       $this->erro_sql = " Campo rh4_codele nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh24_instit == null) || ($this->rh24_instit == "") ){ 
       $this->erro_sql = " Campo rh24_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhrubelementoprinc(
                                       rh24_instit 
                                      ,rh24_rubric 
                                      ,rh4_codele 
                                      ,rh24_codele 
                       )
                values (
                                $this->rh24_instit 
                               ,'$this->rh24_rubric' 
                               ,$this->rh4_codele 
                               ,$this->rh24_codele 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Elemento de despesa default da rubrica. ($this->rh24_rubric."-".$this->rh4_codele."-".$this->rh24_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Elemento de despesa default da rubrica. já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Elemento de despesa default da rubrica. ($this->rh24_rubric."-".$this->rh4_codele."-".$this->rh24_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh24_rubric."-".$this->rh4_codele."-".$this->rh24_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh24_rubric,$this->rh4_codele,$this->rh24_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7126,'$this->rh24_rubric','I')");
       $resac = db_query("insert into db_acountkey values($acount,7127,'$this->rh4_codele','I')");
       $resac = db_query("insert into db_acountkey values($acount,9910,'$this->rh24_instit','I')");
       $resac = db_query("insert into db_acount values($acount,1179,9910,'','".AddSlashes(pg_result($resaco,0,'rh24_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1179,7126,'','".AddSlashes(pg_result($resaco,0,'rh24_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1179,7127,'','".AddSlashes(pg_result($resaco,0,'rh4_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1179,8985,'','".AddSlashes(pg_result($resaco,0,'rh24_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh24_rubric=null,$rh4_codele=null,$rh24_instit=null) { 
      $this->atualizacampos();
     $sql = " update rhrubelementoprinc set ";
     $virgula = "";
     if(trim($this->rh24_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh24_instit"])){ 
       $sql  .= $virgula." rh24_instit = $this->rh24_instit ";
       $virgula = ",";
       if(trim($this->rh24_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "rh24_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh24_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh24_rubric"])){ 
       $sql  .= $virgula." rh24_rubric = '$this->rh24_rubric' ";
       $virgula = ",";
       if(trim($this->rh24_rubric) == null ){ 
         $this->erro_sql = " Campo Código da Rubrica nao Informado.";
         $this->erro_campo = "rh24_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh4_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh4_codele"])){ 
       $sql  .= $virgula." rh4_codele = $this->rh4_codele ";
       $virgula = ",";
       if(trim($this->rh4_codele) == null ){ 
         $this->erro_sql = " Campo Código elemento nao Informado.";
         $this->erro_campo = "rh4_codele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh24_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh24_codele"])){ 
       $sql  .= $virgula." rh24_codele = $this->rh24_codele ";
       $virgula = ",";
       if(trim($this->rh24_codele) == null ){ 
         $this->erro_sql = " Campo Elemento nao Informado.";
         $this->erro_campo = "rh24_codele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh24_rubric!=null){
       $sql .= " rh24_rubric = '$this->rh24_rubric'";
     }
     if($rh4_codele!=null){
       $sql .= " and  rh4_codele = $this->rh4_codele";
     }
     if($rh24_instit!=null){
       $sql .= " and  rh24_instit = $this->rh24_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh24_rubric,$this->rh4_codele,$this->rh24_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7126,'$this->rh24_rubric','A')");
         $resac = db_query("insert into db_acountkey values($acount,7127,'$this->rh4_codele','A')");
         $resac = db_query("insert into db_acountkey values($acount,9910,'$this->rh24_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh24_instit"]))
           $resac = db_query("insert into db_acount values($acount,1179,9910,'".AddSlashes(pg_result($resaco,$conresaco,'rh24_instit'))."','$this->rh24_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh24_rubric"]))
           $resac = db_query("insert into db_acount values($acount,1179,7126,'".AddSlashes(pg_result($resaco,$conresaco,'rh24_rubric'))."','$this->rh24_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh4_codele"]))
           $resac = db_query("insert into db_acount values($acount,1179,7127,'".AddSlashes(pg_result($resaco,$conresaco,'rh4_codele'))."','$this->rh4_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh24_codele"]))
           $resac = db_query("insert into db_acount values($acount,1179,8985,'".AddSlashes(pg_result($resaco,$conresaco,'rh24_codele'))."','$this->rh24_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Elemento de despesa default da rubrica. nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh24_rubric."-".$this->rh4_codele."-".$this->rh24_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Elemento de despesa default da rubrica. nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh24_rubric."-".$this->rh4_codele."-".$this->rh24_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh24_rubric."-".$this->rh4_codele."-".$this->rh24_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh24_rubric=null,$rh4_codele=null,$rh24_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh24_rubric,$rh4_codele,$rh24_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7126,'$rh24_rubric','E')");
         $resac = db_query("insert into db_acountkey values($acount,7127,'$rh4_codele','E')");
         $resac = db_query("insert into db_acountkey values($acount,9910,'$rh24_instit','E')");
         $resac = db_query("insert into db_acount values($acount,1179,9910,'','".AddSlashes(pg_result($resaco,$iresaco,'rh24_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1179,7126,'','".AddSlashes(pg_result($resaco,$iresaco,'rh24_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1179,7127,'','".AddSlashes(pg_result($resaco,$iresaco,'rh4_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1179,8985,'','".AddSlashes(pg_result($resaco,$iresaco,'rh24_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhrubelementoprinc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh24_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh24_rubric = '$rh24_rubric' ";
        }
        if($rh4_codele != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh4_codele = $rh4_codele ";
        }
        if($rh24_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh24_instit = $rh24_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Elemento de despesa default da rubrica. nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh24_rubric."-".$rh4_codele."-".$rh24_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Elemento de despesa default da rubrica. nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh24_rubric."-".$rh4_codele."-".$rh24_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh24_rubric."-".$rh4_codele."-".$rh24_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhrubelementoprinc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh24_rubric=null,$rh4_codele=null,$rh24_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhrubelementoprinc ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhrubelementoprinc.rh24_instit";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = rhrubelementoprinc.rh24_codele and orcelemento.o56_anousu=".db_getsession("DB_anousu");
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = rhrubelementoprinc.rh24_rubric";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
     $sql2 = "";
     if($dbwhere==""){
       if($rh24_rubric!=null ){
         $sql2 .= " where rhrubelementoprinc.rh24_rubric = '$rh24_rubric' "; 
       } 
       if($rh4_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhrubelementoprinc.rh4_codele = $rh4_codele "; 
       } 
       if($rh24_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhrubelementoprinc.rh24_instit = $rh24_instit "; 
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
   function sql_query_file ( $rh24_rubric=null,$rh4_codele=null,$rh24_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhrubelementoprinc ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh24_rubric!=null ){
         $sql2 .= " where rhrubelementoprinc.rh24_rubric = '$rh24_rubric' "; 
       } 
       if($rh4_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhrubelementoprinc.rh4_codele = $rh4_codele "; 
       } 
       if($rh24_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhrubelementoprinc.rh24_instit = $rh24_instit "; 
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