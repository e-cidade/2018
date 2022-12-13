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
//CLASSE DA ENTIDADE orcparamrecurso
class cl_orcparamrecurso { 
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
   var $o44_anousu = 0; 
   var $o44_codparrel = 0; 
   var $o44_sequencia = 0; 
   var $o44_codrec = 0; 
   var $o44_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o44_anousu = int4 = Exercício 
                 o44_codparrel = int4 = parametro do relatorio 
                 o44_sequencia = int4 = sequencia 
                 o44_codrec = int8 = codigo do recurso 
                 o44_instit = int4 = instituicao 
                 ";
   //funcao construtor da classe 
   function cl_orcparamrecurso() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcparamrecurso"); 
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
       $this->o44_anousu = ($this->o44_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o44_anousu"]:$this->o44_anousu);
       $this->o44_codparrel = ($this->o44_codparrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o44_codparrel"]:$this->o44_codparrel);
       $this->o44_sequencia = ($this->o44_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["o44_sequencia"]:$this->o44_sequencia);
       $this->o44_codrec = ($this->o44_codrec == ""?@$GLOBALS["HTTP_POST_VARS"]["o44_codrec"]:$this->o44_codrec);
       $this->o44_instit = ($this->o44_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o44_instit"]:$this->o44_instit);
     }else{
       $this->o44_anousu = ($this->o44_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o44_anousu"]:$this->o44_anousu);
       $this->o44_codparrel = ($this->o44_codparrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o44_codparrel"]:$this->o44_codparrel);
       $this->o44_sequencia = ($this->o44_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["o44_sequencia"]:$this->o44_sequencia);
       $this->o44_codrec = ($this->o44_codrec == ""?@$GLOBALS["HTTP_POST_VARS"]["o44_codrec"]:$this->o44_codrec);
     }
   }
   // funcao para inclusao
   function incluir ($o44_anousu,$o44_codparrel,$o44_sequencia,$o44_codrec){ 
      $this->atualizacampos();
     if($this->o44_instit == null ){ 
       $this->erro_sql = " Campo instituicao nao Informado.";
       $this->erro_campo = "o44_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o44_anousu = $o44_anousu; 
       $this->o44_codparrel = $o44_codparrel; 
       $this->o44_sequencia = $o44_sequencia; 
       $this->o44_codrec = $o44_codrec; 
     if(($this->o44_anousu == null) || ($this->o44_anousu == "") ){ 
       $this->erro_sql = " Campo o44_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o44_codparrel == null) || ($this->o44_codparrel == "") ){ 
       $this->erro_sql = " Campo o44_codparrel nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o44_sequencia == null) || ($this->o44_sequencia == "") ){ 
       $this->erro_sql = " Campo o44_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o44_codrec == null) || ($this->o44_codrec == "") ){ 
       $this->erro_sql = " Campo o44_codrec nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcparamrecurso(
                                       o44_anousu 
                                      ,o44_codparrel 
                                      ,o44_sequencia 
                                      ,o44_codrec 
                                      ,o44_instit 
                       )
                values (
                                $this->o44_anousu 
                               ,$this->o44_codparrel 
                               ,$this->o44_sequencia 
                               ,$this->o44_codrec 
                               ,$this->o44_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->o44_anousu."-".$this->o44_codparrel."-".$this->o44_sequencia."-".$this->o44_codrec) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->o44_anousu."-".$this->o44_codparrel."-".$this->o44_sequencia."-".$this->o44_codrec) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o44_anousu."-".$this->o44_codparrel."-".$this->o44_sequencia."-".$this->o44_codrec;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o44_anousu,$this->o44_codparrel,$this->o44_sequencia,$this->o44_codrec));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5713,'$this->o44_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,6582,'$this->o44_codparrel','I')");
       $resac = db_query("insert into db_acountkey values($acount,5709,'$this->o44_sequencia','I')");
       $resac = db_query("insert into db_acountkey values($acount,8367,'$this->o44_codrec','I')");
       $resac = db_query("insert into db_acount values($acount,1416,5713,'','".AddSlashes(pg_result($resaco,0,'o44_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1416,6582,'','".AddSlashes(pg_result($resaco,0,'o44_codparrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1416,5709,'','".AddSlashes(pg_result($resaco,0,'o44_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1416,8367,'','".AddSlashes(pg_result($resaco,0,'o44_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1416,6783,'','".AddSlashes(pg_result($resaco,0,'o44_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o44_anousu=null,$o44_codparrel=null,$o44_sequencia=null,$o44_codrec=null) { 
      $this->atualizacampos();
     $sql = " update orcparamrecurso set ";
     $virgula = "";
     if(trim($this->o44_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o44_anousu"])){ 
       $sql  .= $virgula." o44_anousu = $this->o44_anousu ";
       $virgula = ",";
       if(trim($this->o44_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o44_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o44_codparrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o44_codparrel"])){ 
       $sql  .= $virgula." o44_codparrel = $this->o44_codparrel ";
       $virgula = ",";
       if(trim($this->o44_codparrel) == null ){ 
         $this->erro_sql = " Campo parametro do relatorio nao Informado.";
         $this->erro_campo = "o44_codparrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o44_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o44_sequencia"])){ 
       $sql  .= $virgula." o44_sequencia = $this->o44_sequencia ";
       $virgula = ",";
       if(trim($this->o44_sequencia) == null ){ 
         $this->erro_sql = " Campo sequencia nao Informado.";
         $this->erro_campo = "o44_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o44_codrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o44_codrec"])){ 
       $sql  .= $virgula." o44_codrec = $this->o44_codrec ";
       $virgula = ",";
       if(trim($this->o44_codrec) == null ){ 
         $this->erro_sql = " Campo codigo do recurso nao Informado.";
         $this->erro_campo = "o44_codrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o44_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o44_instit"])){ 
       $sql  .= $virgula." o44_instit = $this->o44_instit ";
       $virgula = ",";
       if(trim($this->o44_instit) == null ){ 
         $this->erro_sql = " Campo instituicao nao Informado.";
         $this->erro_campo = "o44_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o44_anousu!=null){
       $sql .= " o44_anousu = $this->o44_anousu";
     }
     if($o44_codparrel!=null){
       $sql .= " and  o44_codparrel = $this->o44_codparrel";
     }
     if($o44_sequencia!=null){
       $sql .= " and  o44_sequencia = $this->o44_sequencia";
     }
     if($o44_codrec!=null){
       $sql .= " and  o44_codrec = $this->o44_codrec";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o44_anousu,$this->o44_codparrel,$this->o44_sequencia,$this->o44_codrec));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5713,'$this->o44_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,6582,'$this->o44_codparrel','A')");
         $resac = db_query("insert into db_acountkey values($acount,5709,'$this->o44_sequencia','A')");
         $resac = db_query("insert into db_acountkey values($acount,8367,'$this->o44_codrec','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o44_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1416,5713,'".AddSlashes(pg_result($resaco,$conresaco,'o44_anousu'))."','$this->o44_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o44_codparrel"]))
           $resac = db_query("insert into db_acount values($acount,1416,6582,'".AddSlashes(pg_result($resaco,$conresaco,'o44_codparrel'))."','$this->o44_codparrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o44_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1416,5709,'".AddSlashes(pg_result($resaco,$conresaco,'o44_sequencia'))."','$this->o44_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o44_codrec"]))
           $resac = db_query("insert into db_acount values($acount,1416,8367,'".AddSlashes(pg_result($resaco,$conresaco,'o44_codrec'))."','$this->o44_codrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o44_instit"]))
           $resac = db_query("insert into db_acount values($acount,1416,6783,'".AddSlashes(pg_result($resaco,$conresaco,'o44_instit'))."','$this->o44_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o44_anousu."-".$this->o44_codparrel."-".$this->o44_sequencia."-".$this->o44_codrec;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o44_anousu."-".$this->o44_codparrel."-".$this->o44_sequencia."-".$this->o44_codrec;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o44_anousu."-".$this->o44_codparrel."-".$this->o44_sequencia."-".$this->o44_codrec;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o44_anousu=null,$o44_codparrel=null,$o44_sequencia=null,$o44_codrec=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o44_anousu,$o44_codparrel,$o44_sequencia,$o44_codrec));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5713,'$o44_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,6582,'$o44_codparrel','E')");
         $resac = db_query("insert into db_acountkey values($acount,5709,'$o44_sequencia','E')");
         $resac = db_query("insert into db_acountkey values($acount,8367,'$o44_codrec','E')");
         $resac = db_query("insert into db_acount values($acount,1416,5713,'','".AddSlashes(pg_result($resaco,$iresaco,'o44_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1416,6582,'','".AddSlashes(pg_result($resaco,$iresaco,'o44_codparrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1416,5709,'','".AddSlashes(pg_result($resaco,$iresaco,'o44_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1416,8367,'','".AddSlashes(pg_result($resaco,$iresaco,'o44_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1416,6783,'','".AddSlashes(pg_result($resaco,$iresaco,'o44_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcparamrecurso
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o44_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o44_anousu = $o44_anousu ";
        }
        if($o44_codparrel != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o44_codparrel = $o44_codparrel ";
        }
        if($o44_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o44_sequencia = $o44_sequencia ";
        }
        if($o44_codrec != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o44_codrec = $o44_codrec ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o44_anousu."-".$o44_codparrel."-".$o44_sequencia."-".$o44_codrec;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o44_anousu."-".$o44_codparrel."-".$o44_sequencia."-".$o44_codrec;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o44_anousu."-".$o44_codparrel."-".$o44_sequencia."-".$o44_codrec;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcparamrecurso";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o44_anousu=null,$o44_codparrel=null,$o44_sequencia=null,$o44_codrec=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamrecurso ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcparamrecurso.o44_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcparamrecurso.o44_codrec";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($o44_anousu!=null ){
         $sql2 .= " where orcparamrecurso.o44_anousu = $o44_anousu "; 
       } 
       if($o44_codparrel!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamrecurso.o44_codparrel = $o44_codparrel "; 
       } 
       if($o44_sequencia!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamrecurso.o44_sequencia = $o44_sequencia "; 
       } 
       if($o44_codrec!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamrecurso.o44_codrec = $o44_codrec "; 
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
   function sql_query_file ( $o44_anousu=null,$o44_codparrel=null,$o44_sequencia=null,$o44_codrec=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamrecurso ";
     $sql2 = "";
     if($dbwhere==""){
       if($o44_anousu!=null ){
         $sql2 .= " where orcparamrecurso.o44_anousu = $o44_anousu "; 
       } 
       if($o44_codparrel!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamrecurso.o44_codparrel = $o44_codparrel "; 
       } 
       if($o44_sequencia!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamrecurso.o44_sequencia = $o44_sequencia "; 
       } 
       if($o44_codrec!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamrecurso.o44_codrec = $o44_codrec "; 
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