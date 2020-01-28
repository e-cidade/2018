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

//MODULO: protocolo
//CLASSE DA ENTIDADE proctipovar
class cl_proctipovar { 
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
   var $p55_codproc = 0; 
   var $p55_codvar = 0; 
   var $p55_codcam = 0; 
   var $p55_conteudo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p55_codproc = int4 = código do processo 
                 p55_codvar = int4 = código da variável 
                 p55_codcam = int4 = código 
                 p55_conteudo = text = conteúdo 
                 ";
   //funcao construtor da classe 
   function cl_proctipovar() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("proctipovar"); 
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
       $this->p55_codproc = ($this->p55_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["p55_codproc"]:$this->p55_codproc);
       $this->p55_codvar = ($this->p55_codvar == ""?@$GLOBALS["HTTP_POST_VARS"]["p55_codvar"]:$this->p55_codvar);
       $this->p55_codcam = ($this->p55_codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["p55_codcam"]:$this->p55_codcam);
       $this->p55_conteudo = ($this->p55_conteudo == ""?@$GLOBALS["HTTP_POST_VARS"]["p55_conteudo"]:$this->p55_conteudo);
     }else{
       $this->p55_codproc = ($this->p55_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["p55_codproc"]:$this->p55_codproc);
       $this->p55_codvar = ($this->p55_codvar == ""?@$GLOBALS["HTTP_POST_VARS"]["p55_codvar"]:$this->p55_codvar);
       $this->p55_codcam = ($this->p55_codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["p55_codcam"]:$this->p55_codcam);
     }
   }
   // funcao para inclusao
   function incluir ($p55_codproc,$p55_codvar,$p55_codcam){ 
      $this->atualizacampos();
     if($this->p55_conteudo == null ){ 
       $this->erro_sql = " Campo conteúdo nao Informado.";
       $this->erro_campo = "p55_conteudo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->p55_codproc = $p55_codproc; 
       $this->p55_codvar = $p55_codvar; 
       $this->p55_codcam = $p55_codcam; 
     if(($this->p55_codproc == null) || ($this->p55_codproc == "") ){ 
       $this->erro_sql = " Campo p55_codproc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->p55_codvar == null) || ($this->p55_codvar == "") ){ 
       $this->erro_sql = " Campo p55_codvar nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->p55_codcam == null) || ($this->p55_codcam == "") ){ 
       $this->erro_sql = " Campo p55_codcam nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into proctipovar(
                                       p55_codproc 
                                      ,p55_codvar 
                                      ,p55_codcam 
                                      ,p55_conteudo 
                       )
                values (
                                $this->p55_codproc 
                               ,$this->p55_codvar 
                               ,$this->p55_codcam 
                               ,'$this->p55_conteudo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->p55_codproc."-".$this->p55_codvar."-".$this->p55_codcam) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->p55_codproc."-".$this->p55_codvar."-".$this->p55_codcam) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p55_codproc."-".$this->p55_codvar."-".$this->p55_codcam;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p55_codproc,$this->p55_codvar,$this->p55_codcam));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2446,'$this->p55_codproc','I')");
       $resac = db_query("insert into db_acountkey values($acount,2447,'$this->p55_codvar','I')");
       $resac = db_query("insert into db_acountkey values($acount,2448,'$this->p55_codcam','I')");
       $resac = db_query("insert into db_acount values($acount,401,2446,'','".AddSlashes(pg_result($resaco,0,'p55_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,401,2447,'','".AddSlashes(pg_result($resaco,0,'p55_codvar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,401,2448,'','".AddSlashes(pg_result($resaco,0,'p55_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,401,2449,'','".AddSlashes(pg_result($resaco,0,'p55_conteudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p55_codproc=null,$p55_codvar=null,$p55_codcam=null) { 
     $this->atualizacampos();
     $sql = " update proctipovar set ";
     $virgula = "";
     if(trim($this->p55_codproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p55_codproc"])){ 
       $sql  .= $virgula." p55_codproc = $this->p55_codproc ";
       $virgula = ",";
       if(trim($this->p55_codproc) == null ){ 
         $this->erro_sql = " Campo código do processo nao Informado.";
         $this->erro_campo = "p55_codproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p55_codvar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p55_codvar"])){ 
       $sql  .= $virgula." p55_codvar = $this->p55_codvar ";
       $virgula = ",";
       if(trim($this->p55_codvar) == null ){ 
         $this->erro_sql = " Campo código da variável nao Informado.";
         $this->erro_campo = "p55_codvar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p55_codcam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p55_codcam"])){ 
       $sql  .= $virgula." p55_codcam = $this->p55_codcam ";
       $virgula = ",";
       if(trim($this->p55_codcam) == null ){ 
         $this->erro_sql = " Campo código nao Informado.";
         $this->erro_campo = "p55_codcam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p55_conteudo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p55_conteudo"])){ 
       $sql  .= $virgula." p55_conteudo = '$this->p55_conteudo' ";
       $virgula = ",";
       if(trim($this->p55_conteudo) == null ){ 
         $this->erro_sql = " Campo conteúdo nao Informado.";
         $this->erro_campo = "p55_conteudo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($this->p55_codproc!=null){
       $sql .= " p55_codproc = $this->p55_codproc";
     }
     if($this->p55_codvar!=null){
       $sql .= " and  p55_codvar = $this->p55_codvar";
     }
     if($this->p55_codcam!=null){
       $sql .= " and  p55_codcam = $this->p55_codcam";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p55_codproc,$this->p55_codvar,$this->p55_codcam));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2446,'$this->p55_codproc','A')");
         $resac = db_query("insert into db_acountkey values($acount,2447,'$this->p55_codvar','A')");
         $resac = db_query("insert into db_acountkey values($acount,2448,'$this->p55_codcam','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p55_codproc"]))
           $resac = db_query("insert into db_acount values($acount,401,2446,'".AddSlashes(pg_result($resaco,$conresaco,'p55_codproc'))."','$this->p55_codproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p55_codvar"]))
           $resac = db_query("insert into db_acount values($acount,401,2447,'".AddSlashes(pg_result($resaco,$conresaco,'p55_codvar'))."','$this->p55_codvar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p55_codcam"]))
           $resac = db_query("insert into db_acount values($acount,401,2448,'".AddSlashes(pg_result($resaco,$conresaco,'p55_codcam'))."','$this->p55_codcam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p55_conteudo"]))
           $resac = db_query("insert into db_acount values($acount,401,2449,'".AddSlashes(pg_result($resaco,$conresaco,'p55_conteudo'))."','$this->p55_conteudo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p55_codproc."-".$this->p55_codvar."-".$this->p55_codcam;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p55_codproc."-".$this->p55_codvar."-".$this->p55_codcam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p55_codproc."-".$this->p55_codvar."-".$this->p55_codcam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p55_codproc=null,$p55_codvar=null,$p55_codcam=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p55_codproc,$p55_codvar,$p55_codcam));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2446,'$p55_codproc','E')");
         $resac = db_query("insert into db_acountkey values($acount,2447,'$p55_codvar','E')");
         $resac = db_query("insert into db_acountkey values($acount,2448,'$p55_codcam','E')");
         $resac = db_query("insert into db_acount values($acount,401,2446,'','".AddSlashes(pg_result($resaco,$iresaco,'p55_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,401,2447,'','".AddSlashes(pg_result($resaco,$iresaco,'p55_codvar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,401,2448,'','".AddSlashes(pg_result($resaco,$iresaco,'p55_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,401,2449,'','".AddSlashes(pg_result($resaco,$iresaco,'p55_conteudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from proctipovar
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p55_codproc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p55_codproc = $p55_codproc ";
        }
        if($p55_codvar != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p55_codvar = $p55_codvar ";
        }
        if($p55_codcam != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p55_codcam = $p55_codcam ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p55_codproc."-".$p55_codvar."-".$p55_codcam;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p55_codproc."-".$p55_codvar."-".$p55_codcam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p55_codproc."-".$p55_codvar."-".$p55_codcam;
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
        $this->erro_sql   = "Record Vazio na Tabela:proctipovar";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $p55_codproc=null,$p55_codvar=null,$p55_codcam=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from proctipovar ";
     $sql .= "      inner join procvar  on  procvar.p54_codigo = proctipovar.p55_codvar and  procvar.p54_codcam = proctipovar.p55_codcam";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = proctipovar.p55_codproc";
     $sql .= "      inner join db_syscampo  on  db_syscampo.codcam = procvar.p54_codcam";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = procvar.p54_codigo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  as a on   a.p51_codigo = protprocesso.p58_codproc";
     $sql .= "      inner join procandam  on  procandam.p61_codandam = protprocesso.p58_codandam";
     $sql2 = "";
     if($dbwhere==""){
       if($p55_codproc!=null ){
         $sql2 .= " where proctipovar.p55_codproc = $p55_codproc "; 
       } 
       if($p55_codvar!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " proctipovar.p55_codvar = $p55_codvar "; 
       } 
       if($p55_codcam!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " proctipovar.p55_codcam = $p55_codcam "; 
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
   function sql_query_file ( $p55_codproc=null,$p55_codvar=null,$p55_codcam=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from proctipovar ";
     $sql2 = "";
     if($dbwhere==""){
       if($p55_codproc!=null ){
         $sql2 .= " where proctipovar.p55_codproc = $p55_codproc "; 
       } 
       if($p55_codvar!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " proctipovar.p55_codvar = $p55_codvar "; 
       } 
       if($p55_codcam!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " proctipovar.p55_codcam = $p55_codcam "; 
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